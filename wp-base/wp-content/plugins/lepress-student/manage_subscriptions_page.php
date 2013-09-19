<?php
	global $LePressStudent_plugin;
	global $current_user;
	get_currentuserinfo();
	
	if ( isset( $_REQUEST['action'] ) && $_REQUEST['action'] == "subscribeCourse" ) {
		$course_url = trim($_REQUEST['course_url']);
		$course_message = $_REQUEST['course_message'];
		$key = $_REQUEST['course_key'];
		
			if( $LePressStudent_plugin->get_course_by_url( $course_url ) == false ){
				$response = $LePressStudent_plugin->subscribe_course( $course_url, $course_message, $key );
				if(empty($response)) {
					$response = "No such course exists, check entered URL and try again.";	
					$error_found = true;
				}
			}else{
				$response = 'You are already subscribed to this course.';
			}
	} elseif ( isset( $_REQUEST['action'] ) && $_REQUEST['action'] == "unsubscribeCourse" ) {
		$done = $LePressStudent_plugin->unsubscribe_course( $_REQUEST['id'] );
		if($done) {
			$response = "You have unsubscribed from the course.";	
		} else {
			$response = "Unsubscribing failed, try again later";	
		}
	}
	
	// if profile is not filled
	if((empty($current_user->first_name) || empty($current_user -> last_name)) && !isSet($response)) {
		$response =  __("You cannot subscribe to course before filling out your profile. Firstname and/or lastname is not set.").' <a href="profile.php" style="text-decoration: none;" class="row-title" title="'.__("Edit profile").'">'.__("Edit profile").'</a>';
	} 
	
	if(!empty($response)) {
		echo '<div style="background-color: rgb(255, 251, 204);" id="message" class="updated fade"><p>' . $response . '</p></div>';
	}
?>
<div class="wrap">
<h2>Subscribed courses</h2>
	<table id="subscribed-courses" class="widefat fixed" cellspacing="0">
		<thead>
			<tr>
				<th scope="col" class="manage-column column-id" style="width:30px">ID</th>
				<th scope="col" class="manage-column column-name">Course</th>
				<th scope="col" class="manage-column column-results">Results</th>
				<th scope="col" class="manage-column column-blog">Address of Course</th>
				<th scope="col" class="manage-column column-actions" colspan=2>Actions</th>
			</tr>
		</thead>
		<tbody id="subscribed-courses-body">
			<?php
				foreach( $LePressStudent_plugin->get_subscribed_courses() as $subscription ) {
					echo "<tr>";
						echo "<td>" . $subscription["courseId"] . "</td>";
						echo "<td>" . $subscription["courseName"] . "</td>";
						echo "<td><a href=\"admin.php?page=manage_assignments_page_stnt\">".__('View assignments')."</a></td>";
						echo "<td><a href=\"" . $subscription["courseURL"] . "\">" . urldecode($subscription["courseURL"]). "</a></td>";
						echo "<td><a href=\"mailto:" . $subscription["teacherEmail"] . "\">Send e-mail to teacher</a></td>";
						echo "<td><a href=\"?page=".$_REQUEST['page'] .
							"&action=unsubscribeCourse&id=" . $subscription["courseId"] . "\">Unsubscribe</a></td>";
					echo "</tr>";
					$subscriptionsFound = true;
				}
				
				if(!$subscriptionsFound) {
					echo '<tr><td colspan="2">There is no subscribed courses.</td></tr>';	
				}
			?>
		</tbody>
	</table>
</div>
<div class="wrap">
	<h2>Awaiting authorization to courses</h2>
	<table id="unsubscribed-courses" class="widefat fixed" cellspacing="0">
		<thead>
			<tr>
				<th scope="col" class="manage-column column-results" >Course</th>
				<th scope="col" class="manage-column column-actions" >Address of Course</th>
			</tr>
		</thead>
		<tbody id="unsubscribed-students-body">
			<?php
				foreach( $LePressStudent_plugin->get_unsubscribed_courses() as $subscription ) {
					echo "<tr>";
						echo "<td>" . $subscription["courseName"] . "</td>";
						echo "<td><a href=\"" . $subscription["courseURL"] . "\">" . urldecode($subscription["courseURL"]). "</a></td>";
					echo "</tr>";
				$requestsFound = true;
				}				
				if(!$requestsFound) {
					echo '<tr><td colspan="2">There is no pending authorization requests.</td></tr>';	
				}
			?>
		</tbody>
	</table>
</div>
<div class="wrap" align='center'>
<script type="text/javascript">
	function disableSubmit(form) {
		form.submit.disabled = "disabled";
		form.submit.value = "<?php _e('Subscribing...'); ?>";	
	}
</script>
<?php 
	if(empty($current_user->first_name) || empty($current_user -> last_name)) {	
		$disabled = "disabled='disabled'";
	} else {
		$disabled = '';	
	}
 ?>
<h2>Subscribe to Course</h2>
<form method="post" id="subscribe-form" onsubmit="disableSubmit(this)">
	<input type="hidden" name="action" value="subscribeCourse" />
	<table class="form-table" >
		<tr class="form-field form-required">
			<th scope="row" valign="top"><label for="course_url"><?php _e('Address of Course:') ?></label></th>
			<td><input name="course_url" <?php echo $disabled; ?>  id="course_url" type="text" value="<?php echo $error_found?$course_url: '';?>"/><br/><?php _e("Blog's Category address, i.e.: http://teacher.wordpress.org/somecourse");?></td>
		</tr>
		<tr class="form-field form-required">
			<th scope="row" valign="top"><label for="course_key"><?php _e('Invite key:') ?> (<?php _e('optional') ?>)</label></th>
			<td><input name="course_key" <?php echo $disabled; ?> id="course_key" type="text"/><br /><?php _e('Optional. Enter it if you received it in email invitation.');?></td>
		</tr>
		<tr class="form-field">
			<th scope="row" valign="top"><label for="course_message"><?php _e('Message to Teacher:') ?> (<?php _e('optional') ?>)</label></th>
			<td><textarea name="course_message" <?php echo $disabled; ?>  id="course_message" rows="4"></textarea><br /><?php _e('Optional. Enter here text if you need to say something related to registration to this course.');?></td>
		</tr>
	</table>
	
	<p class="submit"><input type="submit" class="button-primary" <?php echo $disabled; ?>  name="submit" value="<?php _e('Subscribe'); ?>" /></p>

</form>
</div>