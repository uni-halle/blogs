<?php
	if ( !current_user_can('edit_posts') ){
		wp_die(__('Cheatin&#8217; uh?'));
	}
	
	global $LePressTeacher_plugin, $user_ID, $wpdb, $response;
	get_currentuserinfo();
	$response = '';
	$redirect = false;
	
	if ( isset( $_REQUEST['action'] ) && $_REQUEST['action'] == 'postAssignement' && 
		 isset($_REQUEST['assignement_title']) && isset($_REQUEST['content']) &&
		 isset($_REQUEST['startDat']) && isset($_REQUEST['deadline']) ) {
		if( empty( $_REQUEST['post_category'] ) == false ){
			$title = $wpdb->escape( $_REQUEST['assignement_title'] );
			$body =  $_REQUEST['content'];
			$tags =  $_REQUEST['tag_input'];
			$category = $_REQUEST['post_category'];
			
			$_REQUEST['startDat'] = trim($_REQUEST['startDat']);
			$_REQUEST['deadline'] = trim($_REQUEST['deadline']);
			
			$start = strtotime( $wpdb->escape( !empty($_REQUEST['startDat'])?$_REQUEST['startDat'] : date('Y-m-d') ) );
			$start_d = date('d', $start);
			$start_m = date('m', $start);
			$start_y = date('Y', $start);
			$start_db_formated = $start_y . '-' . $start_m . '-' . $start_d;
			
			$end = strtotime( $wpdb->escape( !empty($_REQUEST['deadline'])?$_REQUEST['deadline'] : date('Y-m-d', strtotime(date("Y-m-d", $start) . " +1 day"))));
			$end_d = date('d', $end);
			$end_m = date('m', $end);
			$end_y = date('Y', $end);
			$end_db_formated = $end_y . '-' . $end_m . '-' . $end_d;
			
			$my_post = array();
			$my_post['post_title'] = $title;
			$my_post['post_content'] = $body;
			$my_post['post_status'] = 'publish';
			$my_post['post_author'] = $user_ID;
			$my_post['post_category'] = $category;
			$my_post['tags_input'] = $tags;
			$post_id = wp_insert_post( $my_post );
			if( $post_id !== 0 ) {
				//post was successful
				$LePressTeacher_plugin->add_assignement( $post_id, $start_db_formated, $end_db_formated );
				$response .= 'Assignment successfuly added.</br> Redirecting...';
				$redirect = true;
			} else {
				$response .= 'Adding assignment post failed, check your fields.</br>';
			}
		}else{
			$response .= 'You must select category.</br>';
		}
	}
	
	if( $response != '' ) {
		echo '<div style="background-color: rgb(255, 251, 204);" id="message" class="updated fade"><p>' . $response . '</p></div>';
	}
	
if( $redirect == true ){
	$redirect_url = get_option('siteurl') . '/wp-admin/admin.php?page=manage_assignments_page&course_id=' . $category[0];
	echo '<script type="text/javascript">';
	echo 'function redirect(){';
	echo '	window.location = "' . $redirect_url . '";';
	echo '}';
	echo 'timer = setTimeout( "redirect()", 2500 );';
	echo '</script>';
}else{
?>
<div class="wrap">
<h2>Write new assignment</h2>
<script type="text/javascript">
function validate_form( thisform ) {
	if(thisform.assignement_title.value.trim().length == 0) {
		alert("No title entered.");
		return false;	
	}
	
	if(thisform.startDat.value.trim() != "") { 
		if( Date.parse(calendar1.selectedDates) <= Date.parse(calendar2.selectedDates) 
			&& Date.parse(calendar2.selectedDates) > Date.parse(calendar2.curDate) ){
			return true;
		}
		alert( 'Ending date must be greater than start date and current date.' );
		return false;
	}
}
</script>
<form method="post" onsubmit="return validate_form(this)">
<input type="hidden" name="action" value="postAssignement" />
<div id="poststuff" class="metabox-holder has-right-sidebar">

	<div id="side-info-column" class="inner-sidebar">
		<div id="categorydiv" class="postbox ">
			<h3 class="hndle">
				<span>Courses</span>
			</h3>
			<div class="inside">
				<ul id="categorychecklist" class="list:category categorychecklist form-no-clear">
					<?php
						$courses = $LePressTeacher_plugin->get_courses_names();
						foreach( $courses as $id => $name ) {
							echo '<li id="category-' . $id . '">';
							echo '<label class="selectit">';
							echo '<input id="in-category-' . $id . '" type="radio" name="post_category[]" value="' . $id . '" ';
							if( count( $courses ) == 1 ){
								echo 'checked ';
							}
							echo '/>';
							echo $name;
							echo '</label>';
							echo '</li>';
							$courses_exist = true;
						}
						if(!$courses_exist) {
							echo '<li><label class="selectit">Please add a course first.</label></li>';	
						}
					?>
				</ul>
			</div>
		</div>
		<div id="tagsdiv-post_tag" class="postbox">
			<div class="handlediv" title="Click to toggle">
			<br/>
			</div>
			<h3 class="hndle">
				<span>Post Tags</span>
			</h3>
			<div class="tagsdiv" >
				<div class="jaxtag">
					<div class="inside">
						<p><?php _e('Add or remove tags'); ?></p>
						<textarea name="tag_input" class="the-tags" id="tag_input"><?php 
						echo (isset($_REQUEST['tag_input'])) ? $_REQUEST['tag_input']: '';
						?></textarea>
						<p class="howto"><?php _e('Separate tags with commas.'); ?></p>
					</div>
				</div>
			</div>
		</div>
	</div>

	
	<div id="post-body" class="meta-box">
			<div id="post-body-content">
				<div id="titlewrap" class="postbox">
					<div class="handlediv" title="Click to toggle"><br></div>
					<h3 class="hndle"><span>Title</span></h3>
					<div class="inside">
						<input name="assignement_title" style="width:99%;font-size:1.7em" size="30" tabindex="1" value="<?php 
						echo (isset($_REQUEST['assignement_title'])) ? $_REQUEST['assignement_title']: '';
						?>" id="assignement_title" type="text">
					</div>
				</div>
				<div id="postassignment" class="postbox">
					<div class="handlediv" title="Click to toggle"><br></div>
					<h3 class="hndle"><span>Assignment</span></h3>
					<div class="inside">
						<input name="advanced_view" value="1" type="hidden">
						<input id="calendarInit" type="hidden" value=""/>
						<p class="meta-options">
							<label class="selectit">Start Date<input onfocus="calendar1.setTarget(this)" name="startDat" type="text" value="<?php 
							echo (isset($_REQUEST['startDat'])) ? $_REQUEST['startDat']: '';
							?>"><br /><?php _e('You can choose day of start of assignment for informational and reporting purpose. Today by default.'); ?></label>	
							<br>
							<label class="selectit">Deadline<input onfocus="calendar1.setTarget(this)" name="deadline" type="text" value="<?php 
							echo (isset($_REQUEST['deadline'])) ? $_REQUEST['deadline']: '';
							?>"> <br /> <?php _e('End Date is a day of the deadline for assignment accomplishment. It will be reminded to students in LePress calendar.');?></label>
						</p>
					</div>
				</div>
				<div class="postbox">
					<h3 class="hndle"><span>Post</span></h3>
					<div id="<?php echo user_can_richedit() ? 'postdivrich' : 'postdiv'; ?>" class="postarea">
						<?php
							the_editor( (isset($_REQUEST['content'])) ? $_REQUEST['content']: '', 'content',  'assignement_title', false);
						?>
					</div>
				</div>
				<div align='right'>
					<input type='submit' class="button-primary" value='Publish' />
				</div>
			
			</div>
	</div>
</div>
</form>
</div>
<?php
}
?>