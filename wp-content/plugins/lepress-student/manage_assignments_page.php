<?php
	global $LePressStudent_plugin;
	//check all courses feeds for new assignments
	$LePressStudent_plugin->download_assignments();
?>
<div class="wrap">
<h2>Manage assignments</h2>
	<div>
		<?php
			foreach( $LePressStudent_plugin->get_subscribed_courses() as $course /*for each subscribed course*/ ) {
				$subscribed_to_course = true;
				$assignments_found = false;
				echo '<h3>' . $course['courseName'] . '</h3>';
				?>
				<table id="assignment-<?php echo $course['courseId']; ?>" class="widefat fixed" cellspacing="0">
					<thead>
						<tr>
							<th scope="col" class="manage-column column-name">Assignment</th>
							<th scope="col" class="manage-column column-deadline">Start</th>
							<th scope="col" class="manage-column column-deadline">End</th>
							<th scope="col" class="manage-column column-status">Status</th>
							<th scope="col" class="manage-column column-grade">Grade</th>
							<th scope="col" class="manage-column column-actions" colspan="2">Actions</th>
						</tr>
					</thead>
					<tbody id="assignments-body">
						<?php						
							foreach( $LePressStudent_plugin->get_assignments( $course['courseId'] ) as $assignment /*for each assignment in course*/){
								$assignments_found = true;
								echo '<tr>';
								echo '<td><a href="' . $assignment['post_url'] . '">' . $assignment['title'] . '</a></td>';
								
								if(date('j.n.Y', strtotime($assignment['end'])) - date("j.n.Y") <= 2) {
								     $assignment['end'] = '<span style="color:red;">'.$assignment['end']."</span>";
								}
								echo '<td>'.$assignment['start'] . '</td>';
								echo '<td>'.$assignment['end'] . '</td>';
								echo '<td>';
								if ( $assignment['answer_post_id'] > 0 ) {
									echo 'Ready </br> <a href="' . get_permalink( $assignment['answer_post_id'] ) . 
									'">(view)</a>';
								}else {
									echo 'Not Ready';
								}
								echo '</td>';
								echo '<td>';
								if ( $assignment['grade'] != '' ) {
									echo $assignment['grade'];
								}else {
									echo '&nbsp;';
								}
								echo '</td>';
								if ( $assignment['answer_post_id'] > 0 ) {
									$post = get_post( $assignment['answer_post_id'] );
									echo "<td>Assignment is completed at " . $post->post_date . '</td>';
								}else {
									echo '<td><a href="' . 
									add_query_arg( array( 'id' =>  $assignment['id'], 'action' => 'write_submission' ) )
									. '">Submission</a></td>';
								}
								echo '<td><a href="mailto:' . $course['teacherEmail'] . '?subject=' . $assignment['title'] . '">Send e-mail to Teacher</a></td>';
								echo '</tr>';
							}
						if(!$assignments_found) {
							echo "<tr><td colspan='6'>".__('There no assignments in this course.')."</td></tr>";
						}
						?>
					</tbody>
				</table>
				<?php
			}
		if(!$subscribed_to_course) {
			echo __('No subscribed courses.')." <a href='?page=LePressStudent/main.php' title='Subscribe to course' style='text-decoration: none;'>".__('Subscribe to course')."</a>";	
		}
		?>
	</div>
	
<h2>Archived assignments</h2>
<div>
		<?php
			foreach( $LePressStudent_plugin->get_archived_courses() as $course /*for each subscribed course*/ ) {
				$archived_courses = true;
				$assignments_found = false;
				echo '<h3>' . $course['courseName'] . '</h3>';
				$course_id = $course['courseId'];
				?>
				<table id="archived-assignment-<?php echo $course['courseId']; ?>" class="widefat fixed" cellspacing="0">
					<thead>
						<tr>
							<th scope="col" class="manage-column column-name">Assignment</th>
							<th scope="col" class="manage-column column-status">Status</th>
							<th scope="col" class="manage-column column-grade">Grade</th>
							<th scope="col" class="manage-column column-actions" colspan=2>Actions</th>
						</tr>
					</thead>
					<tbody id="archived-assignments-body-<?php echo $course_id; ?>">
						<?php						
							foreach( $LePressStudent_plugin->get_assignments( $course_id ) as $assignment /*for each assignment in course*/){
								$assignments_found = true;
								echo '<tr>';
								echo '<td><a href="' . $assignment['post_url'] . '">' . $assignment['title'] . '</a></td>';
								echo '<td>';
								if ( $assignment['answer_post_id'] > 0 ) {
									echo 'Ready </br> <a href="' . get_permalink( $assignment['answer_post_id'] ) . 
									'">(view)</a>';
								}else {
									echo 'Not Ready';
								}
								echo '</td>';
								echo '<td>';
								if ( $assignment['grade'] != '' ) {
									echo $assignment['grade'];
								}else {
									echo 'NA';
								}
								echo '</td>';
								if ( $assignment['answer_post_id'] > 0 ) {
									$post = get_post( $assignment['answer_post_id'] );
									echo "<td>Assignment is completed at " . $post->post_date . '</td>';
								}else {
									echo '<td>NA</td>';
								}
								echo '<td><a href="mailto:' . $course['teacherEmail'] . '?subject=' . $assignment['title'] . '">Send e-mail to Teacher</a></td>';
								echo '</tr>';
							}
						if(!$assignments_found) {
							echo "<tr><td colspan='4'>".__('There no archived assignments in this course.')."</td></tr>";
						} 
						?>
					</tbody>
				</table>
				
				<?php 
				} // course foreach end
								
				if(!$archived_courses) {
					 _e('No archived courses.');	
				}
				 ?>
			</div>
</div>