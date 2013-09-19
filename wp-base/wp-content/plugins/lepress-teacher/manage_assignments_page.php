<?php
	global $LePressTeacher_plugin, $response;
	if( isset( $_REQUEST['action'] ) && $_REQUEST['action'] == 'sendAssessmentFeedback' &&
			isset( $_REQUEST['id'] ) ) {
		$comment_id = $_REQUEST['id'];
		$grade = $_REQUEST['assessment_grade'];
		$feedback_content = $_REQUEST['content'];
		/*save grade*/
		$comment = get_comment($comment_id);
		if($LePressTeacher_plugin->post_is_assignment($comment->comment_post_ID)) {
			$LePressTeacher_plugin->set_assesment_grade( $comment_id, $grade );
			/*send feedback and grade*/
			$LePressTeacher_plugin->send_assesment_feedback( $comment_id, $feedback_content );
		}
	}elseif(isset( $_REQUEST['action'] ) && $_REQUEST['action'] == 'make_assignment' &&
			isset( $_REQUEST['post_id'] ) ){
				foreach($_REQUEST['post_id'] as $post_id) {
					$LePressTeacher_plugin->add_assignement( $post_id, date('Y-m-d'), date('Y-m-d', strtotime('+1 day')) );
				}
		echo '<script type="text/javascript">setTimeout(window.location="?page=manage_assignments_page&course_id='.$_REQUEST['course_id'].'", 1000);</script>';
	}
	if( isset( $_REQUEST['action'] ) && $_REQUEST['action'] == 'edit_dates' &&
			isset( $_REQUEST['assignment_id'] ) && isset( $_REQUEST['start_date'] ) && 
			isset( $_REQUEST['end_date'] ) ) {
		$LePressTeacher_plugin->update_assesment_dates( $_REQUEST['assignment_id'], $_REQUEST['start_date'], $_REQUEST['end_date'] );
	}else{
		//all fields are not filled
	}
	
	if( $response != '' ) {
		echo '<div style="background-color: rgb(255, 251, 204);" id="message" class="updated fade"><p>' . $response . '</p></div>';
	}
?>
<div class="wrap">
<?php

	if( isset( $_REQUEST['action'] ) && $_REQUEST['action'] == 'sendAssessmentFeedbackPage' &&
		isset( $_REQUEST['id'] )) {
			$comment = get_comment( $_REQUEST['id'] );
		}

	if( isset( $_REQUEST['action'] ) && $_REQUEST['action'] == 'sendAssessmentFeedbackPage' &&
		isset( $_REQUEST['id'] ) && $LePressTeacher_plugin->post_is_assignment($comment->comment_post_ID)) {
			$assignment = $this->getAssignment($comment->comment_post_ID);
			$student_assignment_post = $LePressTeacher_plugin->get_post_from_student_blog($comment->comment_author_url);
			$student = $LePressTeacher_plugin->getStudentNameByCommentId($comment->comment_ID);
		?>
		<h2>Write assesment feedback</h2>
	
		<form method="post">
			<input type="hidden" name="action" value="sendAssessmentFeedback" />
			<input type="hidden" name="id" value="<?php echo $_REQUEST['id']; ?>" />
			<input type="hidden" name="open_assignment" value="<?php echo $assignment['id']; ?>" />
			<input type="hidden" name="course_id" value="<?php echo $_REQUEST['course_id']; ?>" />
			<div id="poststuff" class="metabox-holder">
			<div id="post-body" class="meta-box">
			<div id="post-body-content">
				<div  class="postbox">
					<h3><span>Submission of <?php echo $student->first_name." ".$student->last_name; echo ' - '.htmlspecialchars_decode($student_assignment_post->title); ?></span></h3>
					<div class="inside">
						<?php $str = nl2br(strip_tags(htmlspecialchars_decode($student_assignment_post->content), '<br><strong><ul><ol><li><ul><code><img><b><i><a><ins>')); 
							if($student_assignment_post->code == -1) {
								echo "Requested student submission was not found in student's blog";
							} else if(empty($str)) {
								echo "Could not fetch student submission, connection failed. Try again.";	
							} else {
								echo $str;	
							}
						?>
					</div>
				</div>
				<div class="postbox">
					<h3><span>Feedback</span></h3>
					<div class="inside">
						<textarea style="color:black;" rows="10" class="theEditor" cols="40" name="content" tabindex="2" id="content"></textarea>
					</div>
					<div class="inside">
						<p class="meta-options">
							<label class="selectit">Grade
								<input name="assessment_grade" size="1" tabindex="1" value="" id="assessment_grade" type="text" maxlength="2">
							</label>
						</p>
					</div>
					<div class="inside">
						<p class="meta-options">
							<label>Feedback to assessment: 
								<?php
									$url = $comment->comment_author_url;
									echo '<a href="' . $url . '">' . urldecode($url) . '</a>';
								?>
							</label>
						</p>
					</div>
				</div>
				<div align='right'>
					<input type='submit' class="button-primary" value='Publish' />
				</div>
			</div>
			</div>
			</div>
		</form>
		<?php
	}else{
?>
		<h2>Manage assignments</h2>
			<form method="post" id="manage_assignments_form" action="?page=manage_assignments_page">
				<div>
					<input type="hidden" name="action" value="choose_course" />
					<select name="course_id" id="course_id" onChange="this.form.submit()">
					<?php
						foreach( $LePressTeacher_plugin->get_courses_names() as $id => $name ) {
							echo '<option value=' . $id;
							if( isset( $_REQUEST['course_id'] ) && $_REQUEST['course_id'] == $id ){
								echo ' selected="selected"';
							}
							echo '>' . $name . '</option>';
							$coursesExist = true;
						}
					if(!$coursesExist) {
						echo '<option value="-1">No courses</option>';	
					}
					?>
					</select>
					<input type='submit' class="button-primary" value='Choose' />
				</div>
			</form>
			<div>
				<?php
					$assignment_post_ids_str = '';
					if ( isset( $_REQUEST['course_id'] ) ){
						$assignments = $LePressTeacher_plugin->get_assignments_names( $_REQUEST['course_id'], $_REQUEST['open_assignment']); /* actualy it is term_id */
						
						if(!isSet($_REQUEST['open_assignment'])) {
						?>
							
							<table id="list-assignments" class="widefat fixed" cellspacing="0" style="margin-top: 10px;">
								<thead>
									<tr>
										<th scope="col" class="manage-column column-assignment">Assignment</th>
										<th scope="col" class="manage-column column-start">Start</th>
										<th scope="col" class="manage-column column-end">End</th>
										<th scope="col" class="manage-column column-ungraded">Ungraded</th>
										<th scope="col" class="manage-column column-students">View</th>
									</tr>
								</thead>
								<tbody id="list-assignments-body">
						
					<?php 
					
						if( count( $assignments ) == 0 ){
							echo '<tr><td colspan="5">There is no assessments in this course.</td></tr>';
						}
					
					   $assignment_post_ids_str = '';
					   	
						foreach( $assignments as $id ) {
							$ungraded_count = 0;
							foreach( $LePressTeacher_plugin->get_course_users_ids( $_REQUEST['course_id'] /* actualy it is term_id */ ) as $userId ){
								$post_id = $id['post_id'];								
								$assessment = $LePressTeacher_plugin->get_assessment( $post_id , $userId['id'] );
								if($assessment && is_null($assessment['grade'])){
									$ungraded_count++;	
								}
							}
							$assignment_post_ids_str .= ' ' . $id['post_id'];
									
							echo '<tr><td><a href="?page=manage_assignments_page&course_id='.$_REQUEST['course_id'].'&open_assignment='.$id['id'].'"><b>' . $id['name'] . '</b></a></td><td>'.$LePressTeacher_plugin->assignment_start($id['post_id']).'</td><td>'.$LePressTeacher_plugin->assignment_end($id['post_id']).'</td><td>'.$ungraded_count.'</td><td><a href="' . get_permalink( $id['post_id'] ) . '" target="_blank">View assignment</a></td></tr>';
						}
					?>
								</tbody>
							</table>
								
						<?php
						} // end of assignments list
						 else {
						 
						 echo '<p><a href="?page=manage_assignments_page&course_id='.$_REQUEST['course_id'].'">'.__('Go back').'</a></p>';
						 
						foreach( $assignments as $id ) {
							echo '<a href="' . get_permalink( $id['post_id'] ) . '"><h3>' . $id['name'] . '</h3></a>';
							?>
							<form method="post" onsubmit="return validate_form(this)">
								<input type="hidden" name="action" value="edit_dates" />
								<input type="hidden" id="calendarInit" value=""/>
								<input type="hidden" name="assignment_id" value="<?php echo $id['id']; ?>" />
								<input type="hidden" name="course_id" value="<?php echo $_REQUEST['course_id']; ?>" />
								<div>Start: <input  type="text" name="start_date"  onfocus="calendar1.setTarget(this)" value="<?php 
									echo $LePressTeacher_plugin->assignment_start( $id['post_id'] ); 
								?>" /> End: <input type="text" name="end_date" onfocus="calendar1.setTarget(this)" value="<?php 
									echo $LePressTeacher_plugin->assignment_end( $id['post_id'] ); 
								?>"/><input type='submit' class="button-primary" value='Save Change' />
								</div>
								
							</form>
							<table id="filed-assignment-<?php echo $id['id']; ?>" class="widefat fixed" cellspacing="0">
								<thead>
									<tr>
										<!--<th scope="col" class="manage-column column-id" style="width:30px">ID</th>-->
										<th scope="col" class="manage-column column-name">Name</th>
										<th scope="col" class="manage-column column-status">Status</th>
										<th scope="col" class="manage-column column-grade" style="width:90px">Grade</th>
										<th scope="col" class="manage-column column-feedback">Feedback</th>
										<th scope="col" class="manage-column column-actions">Actions</th>
										<th scope="col" class="manage-column column-time">Submission time</th>
									</tr>
								</thead>
								<tbody id="filed-assignments-body">
									<?php
									    $participantsFound = false;
										foreach( $LePressTeacher_plugin->get_course_users_ids( $_REQUEST['course_id'] /* actualy it is term_id */ ) as $userId ){
											$participantsFound = true;
											$post_id = $id['post_id'];
											$assessment = $LePressTeacher_plugin->get_assessment( $post_id , $userId['id'] );
											if( $assessment != false ){

												echo '<tr>';
												//echo '<td>' . $assessment['id'] . '</td>';
												
												echo '<td>' . $assessment['first_name'].'  '.$assessment['last_name'] . 
													'<a href="' . $assessment['wp_url'] . '"> (Click to Blog)</a>' . '</td>';
												echo '<td>' . '<a href="' . $assessment['comment_url'] .
													'">Accomplished (click to view result)</a>' . '</td>';
												echo '<td>'; 
												if(!is_null($assessment['grade'])){
													echo $assessment['grade'];
												}else{
													echo '<a href="?page=' . $_REQUEST['page'] . 
													'&action=sendAssessmentFeedbackPage&id=' . $assessment['id'] .
													'&course_id=' . $_REQUEST['course_id'] . '">Click to grade</a>';
												}
												echo '</td>';
												echo '<td>'; 
												
												if(!is_null($assessment['grade'])){
													echo 'Is sent <a href="' . $assessment['comment_url'] . '">(click to view)</a>';
													echo ' <a href="?page=' . $_REQUEST['page'] . 
													'&action=sendAssessmentFeedbackPage&id=' . $assessment['id'] .
													'&course_id=' . $_REQUEST['course_id'] . '">(click to regrade)</a>';
												}else{
													echo 'Is not sent <a href="?page=' . $_REQUEST['page'] . 
													'&course_id=' . $_REQUEST['course_id'] . '&action=sendAssessmentFeedbackPage&id=' . $assessment['id'] .
													'">(click to send)</a>';
												}
												echo '</td>';
												echo '<td>' . '<a href="mailto:' . $assessment['email'] . '">Send e-mail</a></td>';
												echo '<td>'.$assessment['submission_time'].'</td>';
												echo '</tr>';
											}else{
												$user = $LePressTeacher_plugin->get_user( $userId['id'] );
												echo '<tr>';
												echo '<td>' . $user['first_name'] .' '. $user['last_name'] . 
													'<a href="' . $user['wp_url'] . '"> (Click to Blog)</a>' . '</td>';
												echo '<td>Not Accomplished</td>';
												echo '<td>&nbsp;</td>';
												echo '<td></td>';
												echo '<td>' . '<a href="mailto:' . $user['email'] . '">Send e-mail</a></td>';
												echo '<td>'.$assessment['submission_time'].'</td>';
												echo '</tr>';
											}
										}
										//Assignment has now participants
										if(!$participantsFound) {
											echo '<tr><td colspan="5">There is no subscribed students who need to accomplish this assessment.</td></tr>';
										}
									?>
								</tbody>
							</table>
							<?php
						   }
						 }
					}
				?>
			</div>
			<?php 
			if( isset( $_REQUEST['course_id'] ) && !isSet($_REQUEST['open_assignment'])){ ?>
				<div>
				<h2>Available posts</h2>
				<form method="post">
					<input type="hidden" name="action" value="make_assignment" />
					<input type="hidden" name="course_id" value="<?php echo $_REQUEST['course_id']; ?>"/>
					<table class="widefat fixed" cellspacing="0">
						<thead>
									<tr>
										<th scope="col" class="manage-column check-column"><input type="checkbox"/></th>
										<th scope="col" class="manage-column column-title">Title</th>
										<th scope="col" class="manage-column column-excerpt">Excerpt</th>
										<th scope="col" class="manage-column column-link">View</th>
									</tr>
								</thead>
						<tbody id="available-posts">
				
						<?php
						
						$myposts = get_posts('numberposts=-1&cat=' . $_REQUEST['course_id'] . '&exclude=' . $assignment_post_ids_str );
						foreach($myposts as $post){
							echo '<tr><th scope="row" class="check-column"><input type="checkbox" name="post_id[]" value="'.$post->ID.'"/></th><td class="column-title">'.$post->post_title.'</td><td class="column-excerpt">'.$post->post_excerpt.'</td><td class="column-link"><a href="'.$post->guid.'" target="_blank">'.('View post').'</a></td></tr>';
						}
						if(count($myposts) == 0) {
							echo '<tr><td colspan="2">'.__('No regular posts in this course').'</td></tr>';
						}
						?>
					</tbody>
					</table>
					<input type='submit' class="button-primary" value='Make into assignment' />
				</form>
				</div>
				<?php 
			} ?>
<?php
	}
?>
</div>