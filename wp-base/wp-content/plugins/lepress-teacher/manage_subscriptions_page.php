<?php
	global $LePressTeacher_plugin;
	$response = '';
	if ( isset( $_REQUEST['action'] ) && $_REQUEST['action'] == "acceptUsers" ) {
		$accepted = ( empty( $_REQUEST['accept'] ) ) ? array() : $_REQUEST['accept'];
		$declined = ( empty( $_REQUEST['decline'] ) ) ? array() : $_REQUEST['decline'];
		$affected_courses = array();
		
		$succ_accept = 0;
		$fail_accept = 0;
		
		foreach ( $accepted as $subscriptionID ){
			$responseText = ( empty( $_REQUEST['responseText' . $subscriptionID] ) ) ? '' : $_REQUEST['responseText' . $subscriptionID];
			//connect users wordpress page and give information about accepting subscription
			//if user wordpress page does not answer, user wont be accepted nor declined and request wont be removed
			if ( $LePressTeacher_plugin->notify_subscription_user_web( $subscriptionID, true ) ) {
				//send email about accepting subscription to course
				$LePressTeacher_plugin->notify_subscription_user_email( $subscriptionID, true, $responseText );
				//make changes in db
				$LePressTeacher_plugin->accept_subscription( $subscriptionID );
				$succ_accept++;
			} else {
				$fail_accept++;	
			}
		}
		
		if($succ_accept > 0) {
			$response .= 'You accepted '.$succ_accept.' '.($succ_accept == 1 ? 'subscriber' : 'subscribers').'.<br />';
		}
		if($fail_accept > 0) {
			$response .= 'Failed to accept '.$fail_accept.' '.($fail_accept == 1 ? 'subscriber' : 'subscribers').'.<br />';
		}
		
		
		$succ_decline = 0;
		$fail_decline = 0;
		
		foreach ( $declined as $subscriptionID ){
			$responseText = ( empty( $_REQUEST['responseText' . $subscriptionID] ) ) ? '' : $_REQUEST['responseText' . $subscriptionID];
			//connect users wordpress page and give information about declineing subscription
			if($LePressTeacher_plugin->notify_subscription_user_web( $subscriptionID, false )) {
				//send email about declining subscription to course
				$LePressTeacher_plugin->notify_subscription_user_email( $subscriptionID, false, $responseText );
				//make changes in db
				$LePressTeacher_plugin->remove_subscription( $subscriptionID );
				$succ_decline++;
			} else {
			     if(isSet($_REQUEST['force_subcr_action'])) {
			         $LePressTeacher_plugin->remove_subscription( $subscriptionID );
			     } else {
				    $fail_decline++;
			     }	
			}
		} 
		
		if($succ_decline > 0) {
			$response .= 'You declined '.$succ_decline.' '.($succ_decline == 1 ? 'subscriber' : 'subscribers').'.<br />';
		}
		if($fail_decline > 0) {
			$response .= 'Failed to decline '.$fail_decline.' '.($fail_decline == 1 ? 'subscriber' : 'subscribers').'.<br />';
		}


	} elseif ( isset( $_REQUEST['action'] ) && $_REQUEST['action'] == "inviteStudents" ) {
		$emails = Array();
		$bad_emails = Array();

		if ($_FILES["file"]["error"] == 0) {
			//we take list of users to be invited from file
			$file=fopen($_FILES["file"]["tmp_name"],"r");
			while( ($data = fgetcsv( $file ) ) !== false )
			{
				for ( $i = 0; $i < count( $data ); $i++ ){
					if ( filter_var( $data[$i], FILTER_VALIDATE_EMAIL) ){
						//if email address validates it will be put into $emails array
						array_push( $emails, $data[$i] );
					} else {
						//if email address didnt validate it will be put into $bad_emails array
						array_push( $bad_emails, $data[$i] );
					}
				}
			}
			fclose($file);
		} elseif ( isset( $_REQUEST['emails'] ) ){
			//we take list of users to be invited from field
			$unchecked_emails = split( "\n", $_REQUEST['emails'] );
			for ( $i = 0; $i < count( $unchecked_emails ); $i++ ){
				$unchecked_emails[$i] = trim( $unchecked_emails[$i] );
				if(strlen($unchecked_emails[$i]) > 0) {
					if ( filter_var( $unchecked_emails[$i], FILTER_VALIDATE_EMAIL) ){
						//if email address validates it will be put into $emails array
						array_push( $emails, $unchecked_emails[$i] );
					} else {
						//if email address didnt validate it will be put into $bad_emails array
						array_push( $bad_emails, $unchecked_emails[$i] );
					}
				}
			}

		}

		if( count( $emails ) != 0 && count( $bad_emails ) == 0  && !empty($_REQUEST['course_id'])) {
			//if atleast one email address fails validation, no email will be sent
			//but if one or email addresses validate and no email will fail validation email(s) will be send
			$course_ids = $_REQUEST['course_id'];
			$key = (int)$_REQUEST['key'];
			$message = $_REQUEST['invite_message'];
			
			$LePressTeacher_plugin->invite_students_email( $emails, $course_ids, $key, $message );
			$response .= __( 'Invite(s) successfully sent.' ) . ' </br>';

			//clear $_REQUEST variabe
			unset($_REQUEST);

			//echo "key: " . $_REQUEST['key'];
		} else {
			//no course(s) were selected
			if(empty($_REQUEST['course_id'])) { $response .= __("No courses selected.")."<br />"; }
			//no emails were found
			if ( count( $emails ) == 0) { $response .= __( "No emails were found." ) . "<br/>"; }
			//email(s) failed validation
			if ( count( $bad_emails ) > 0) {
				//echo count( $bad_emails ) . " " . __( "email(s) failed validation!" ) . " (";
				$str_bad_emails = "";
				for ( $i = 0; $i < count( $bad_emails ); $i++ ){
					$str_bad_emails .= $bad_emails[$i] . ", ";
				}
				$str_bad_emails[ strlen( $str_bad_emails ) - 2] = ")";
				//echo $str_bad_emails;
				$response .= __( 'No invite(s) were sent, because following emails failed validation: ' ) . '(' . $str_bad_emails;
			}
		}

	} elseif ( isset( $_REQUEST['action'] ) && $_REQUEST['action'] == "removeSubscription" && isset( $_REQUEST['id'] ) ) {
		$subscriptionID = (int)$_REQUEST['id'];
		$LePressTeacher_plugin->notify_subscription_user_web( $subscriptionID, false );
		//send email about declining subscription to course
		$responseText = 'Your subscription has been deleted.';
		$LePressTeacher_plugin->notify_subscription_user_email( $subscriptionID, false, $responseText );
		//$subscription = $LePressTeacher_plugin->get_subscription( $subscriptionID );
		//$LePressTeacher_plugin->send_new_classmates_list( $subscription['term_id'] );
		//make changes in db
		$LePressTeacher_plugin->remove_subscription( $subscriptionID );
		$response .= __( 'You successfully removed subscription.' ) . ' </br>';
	}

	echo '<div style="background-color: rgb(255, 251, 204);" id="message" class="fade"><p>' . $response . '</p></div>';
	
	$course_id_last = isSet($_REQUEST['s_course']) ? $_REQUEST['s_course'] : -1;

	
	?>
	<script type="text/javascript">

	function fillSubscribedStudentsTableBody(btn, last_course_id) {
		if(last_course_id > -1) {
			jQuery('#filter-termid').val(last_course_id);
		}
		var course_id = jQuery('#filter-termid').val();
		var url =  "admin-ajax.php?action=getSubsStudents&id="+course_id+"&amp;t="+Math.ceil(Math.random()*25);
		
		if(btn) {
			var old_value = btn.value;		
			btn.disabled = "disabled";
			btn.value = "Loading …";
		}
		
		jQuery.get(url, function(data) { 
			
			var returnStr = document.createElement('div');
			returnStr.innerHTML = data;
				
			if( returnStr.getElementsByTagName("table")[0].getElementsByTagName("tbody")[0] ){
				html_out = returnStr.getElementsByTagName("table")[0].getElementsByTagName("tbody")[0].innerHTML;
			} else {
				html_out = "<tr><td colspan='6'>There is no subscribed students for this course.</td></tr>";
			}
			
			jQuery('#subscribed-students-body').html(html_out); 
			
			if(btn) {
				btn.disabled = "";
				btn.value = old_value;
			}
			jQuery('#last_selected_course').val(course_id);
		});
	}

	jQuery(document).ready(function() {
			fillSubscribedStudentsTableBody(false, <?php echo $course_id_last; ?>);
	});
	</script>
	
	<div class="wrap">
	<h2>Subscribed students</h2>
		<table id="subscribed-students" class="widefat" cellspacing="0">
			<thead>
				<tr>
					<th scope="col" class="manage-column column-table-name" colspan="7">Course (Category) 
						<select name="filter-termid" id="filter-termid">
							<?php
								foreach( $LePressTeacher_plugin->get_courses_names() as $id => $name ) {
									echo "<option value='" . $id . "'>" . $name . "</option>";
									$course_exist = true;
								}
								if(!$course_exist) {
									echo '<option value="-1">No courses</option>';	
								}
							?>
						</select>
						<input id="submit-subscribed-students-Btn" type="submit" class="button" value="Choose" onclick="fillSubscribedStudentsTableBody(this)"/>
					</th>
				</tr>
				<tr>
					<th scope="col" class="manage-column column-id" width="30px">ID</th>
					<th scope="col" class="manage-column column-name">Name</th>
					<th scope="col" class="manage-column column-email" >E-mail</th>
					<th scope="col" class="manage-column column-results">Results</th>
					<th scope="col" class="manage-column column-blog">Blog</th>
					<th scope="col" class="manage-column column-actions" colspan="2"  style="width:30px">Actions</th>
				</tr>
			</thead>
			<tbody id="subscribed-students-body">
				
			</tbody>
		</table>
	</div>
	<div class="wrap">
		<h2>Students awaiting authorization</h2>
		<form method="post">
			<input type="hidden" name="action" value="acceptUsers" />
			<input type="hidden" name="s_course" id="last_selected_course" value="-1"/>
			<table id="unsubscribed-students" class="widefat fixed" cellspacing="0">
				<thead>
					<tr>
						<th scope="col" class="manage-column column-name">Name</th>
						<th scope="col" class="manage-column column-email" style="width:170px">E-mail</th>
						<th scope="col" class="manage-column column-results" >Course</th>
						<th scope="col" class="manage-column column-blog" style="width:60px" >Accept</th>
						<th scope="col" class="manage-column column-actions" style="width:60px" >Decline</th>
						<th scope="col" class="manage-column column-actions" style="width:60%">Response</th>
					</tr>
				</thead>
				<tbody id="unsubscribed-students-body">
					<?php
					   $noAwaitingAuths = true;
						foreach( $LePressTeacher_plugin->get_unsubscribed_users() as $subscription ) {
							$noAwaitingAuths = false;
							echo "<tr>";
								echo "<td>" . $subscription["fName"] . " " . $subscription["lName"] . "</td>";
								echo "<td>" . $subscription["user_email"] . "</td>";
								echo "<td>" . $subscription["courseName"] . "</td>";
								echo "<td><input type='checkbox' id='accept" . $subscription["id"] . "' name='accept[]' value=" . $subscription["id"] . " onClick='document.getElementById(\"decline" . $subscription["id"] . "\").checked = false;' /></td>";
								echo "<td><input type='checkbox' id='decline" . $subscription["id"] . "' name='decline[]' value=" . $subscription["id"] . " onClick='document.getElementById(\"accept" . $subscription["id"] . "\").checked = false;' /></td>";
								echo "<td><input type='text' id='responseText' name='responseText" . $subscription["id"] . "' style='width:100%' /></td>";
							echo "</tr>";
						}
						
						if($noAwaitingAuths) {
							echo '<tr><td colspan="6">There is no pending authorizations.</td></tr>';	
						} else {
							echo "<tr><td colspan='2'>Force decline <input type='checkbox' name='force_subcr_action' value='1' /></td><td colspan='4' align='right' ><input type='submit' class='button-primary' value='Confirm' /></td></tr>";
						}
						
					?>
				</tbody>
			</table>
		</form>
	</div>
	<div class="wrap">
	<h2>Invite students</h2>
	<form method="post" enctype="multipart/form-data">
		<input type="hidden" name="action" value="inviteStudents" />
		<table class="form-table" >
			<tr class="form-field">
				<th scope="row" valign="top"><label for="importList"><?php _e('Import list of students:') ?></label></th>
				<td><input type="file" name="file" id="file"/> <br /><?php _e('Text file with emails separated by new line can upload here. ');?> </td>
			</tr>
			<tr class="form-field">
				<th scope="row" valign="top"><label for="emails"><?php _e('Or add manually:'); echo '<br/>('; _e('Seperate with new row'); ?>)</label></th>
				<td><textarea id="emails" name="emails" rows=6><?php
				if( isset( $bad_emails ) && count( $bad_emails ) > 0 && isset( $_REQUEST['emails'] ) || !isSet($_REQUEST['course_id'])) {
					echo $_REQUEST['emails'];
				}
				?></textarea><br/><?php _e('Separate each email with new line.')?></td>
			</tr>
			<tr>
				<th scope="row" valign="top"><label for="course_id"><?php _e('Category (Course) to invite:') ?></label></th>
				<td>
					<?php
						foreach( $LePressTeacher_plugin->get_courses_names() as $id => $name ) {
							unset($select);
							if(is_array($course_ids = $_REQUEST['course_id'])) {
								if(in_array($id, $course_ids)) {
									$select = 'checked = "checked"';
								}
							}
							echo "<input type=checkbox name=\"course_id[]\" value=" . $id . " ".$select."/>" . $name . "<br/>";
							$course_exist = true;
						}
						if(!$course_exist) {
							echo 'No courses.';	
						}
					?><br /> <?php _e('Mark the courses to which you want to invite students.'); ?>
				</td>
			</tr>
			<tr>
				<th scope="row" valign="top"><label for="key"><?php _e('Key:') ?></label></th>
				<td><input type="radio" id="key" name="key" value=0 checked="yes"/> <?php _e('None'); ?></td>
			</tr>
			<tr>
				<td/>
				<td><input type="radio" id="key" name="key" value=1 /> <?php _e('Invidual'); ?> (<?php _e('Everyone will receive individual key, what will let to join the course without being accepted by teacher.'); ?>)</td>
			</tr>
			<tr>
				<td/>
				<td><input type="radio" id="key" name="key" value=2 /> <?php _e('Group'); ?> (<?php _e('Everyone will receive individual key, what will let to join the course without being accepted by teacher.'); ?>)</td>
			</tr>
			<tr class="form-field">
				<th scope="row" valign="top"><label for="invite_message"><?php _e('Message to student/s:') ?> (<?php _e('optional') ?>)</label></th>
				<td><textarea name="invite_message" id="invite_message" rows="4"></textarea><br /><?php _e('Optional. Here you can write some invitation and additional information to identify your email for student.'); ?></td>
			</tr>
		</table>
		<p class="submit"><input type="submit" class="button-primary" name="submit" value="<?php _e('Invite students'); ?>" /></p>
	</form>
	</div>