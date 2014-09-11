<?php
	if ( !current_user_can('edit_posts') ){
		wp_die(__('Cheatin&#8217; uh?'));
	}
	
	global $LePressStudent_plugin, $user_ID, $wpdb;
	$assignment;	//if  $_REQUEST['id'] isset, this variable is used
	$response = '';
	
	if ( isset( $_REQUEST['action'] ) && $_REQUEST['action'] == 'postAssesment' && 
		 isset($_REQUEST['assesment_title']) && isset($_REQUEST['content']) &&
		 isset($_REQUEST['assignmentCombo']) ) {
		  
		  
		
		$title = $wpdb->escape( $_REQUEST['assesment_title'] );
		$body = $wpdb->escape( $_REQUEST['content'] );
		$assignment_id = $wpdb->escape( $_REQUEST['assignmentCombo'] );
		$tags = $wpdb->escape( $_REQUEST['tag_input'] );
		
		$category = ( empty( $_REQUEST['post_category'] ) ) ? array(1) : $_REQUEST['post_category'];
		
		$my_post = array();
		$my_post['post_title'] = $title;
		$my_post['post_content'] = $body;
		if( isset( $_REQUEST['is_draft'] ) && $_REQUEST['is_draft'] == 'true' ){
			$my_post['post_status'] = 'draft';
		}else {
			$my_post['post_status'] = 'publish';
		}
		$my_post['post_author'] = $user_ID;
		$my_post['post_category'] = $category;
		$my_post['tags_input'] = $tags;
		$assignment = $LePressStudent_plugin->get_assignment( $assignment_id );
		$post_id = wp_insert_post( $my_post );
		if( $post_id != 0 ) {
			//post was successful
			global $id;
			$id = $post_id;
			$tb_url = get_trackback_url();
			if( $tb_url == '/trackback/' ) {
				$tb_url = get_permalink( $id ) . 'trackback/';
			}

			$my_post = array();
			$my_post['ID'] = $post_id;
			$url = $assignment['feedback_url'];
			$url_has_query_parameters = parse_url($url, PHP_URL_QUERY);

			if($url_has_query_parameters == NULL) {
				$my_post['to_ping'] = $assignment['feedback_url'] . '?trackback_url=' . $tb_url;
			}else{
				$my_post['to_ping'] = $assignment['feedback_url'] . '&trackback_url=' . $tb_url;
			}
	
			wp_update_post( $my_post );
			
			if( $LePressStudent_plugin->add_assesment( $post_id, $assignment_id ) ){
				//post and assignment linked
				$response .= __( 'Inserting post was successful! Redirecting...' );
				$post_success = true;
				
			}else {
				$response .= ' </br>'. __( 'Inserting assesment was NOT successful!' );
			}
		} else {
			$response .= __( 'Inserting post was not successful!' );
		}
	}
	
	echo '<div style="background-color: rgb(255, 251, 204);" id="message" class="fade"><p>' . $response . '</p></div>';
?>
<script language="javascript">

	<?php if($post_success) {
		echo 'setTimeout(redirect, 3500)';	
		}
	
	?>
	
	function redirect() {
			window.location = 'admin.php?page=manage_assignments_page_stnt';
	}

	var xmlhttp=false;

	if (!xmlhttp && typeof XMLHttpRequest!='undefined') {
		try {
		  xmlhttp = new XMLHttpRequest();
		} catch (e) {
		  xmlhttp = false;
		}
	}

	function getAssignments() {
		if (xmlhttp) {
			var docname = "admin-ajax.php?action=getAssignments&id=" + document.getElementById("courseCombo").options[document.getElementById("courseCombo").selectedIndex].value;
			xmlhttp.open("GET", docname,true);
			document.getElementById("courseCombo").disabled = true;
			xmlhttp.onreadystatechange=function() {
				if (xmlhttp.readyState==4) {
					var returnStr = document.createElement('div');
					returnStr.innerHTML = xmlhttp.responseText;
					if( returnStr.getElementsByTagName("select")[0] ){
						document.getElementById("assignmentCombo").innerHTML = returnStr.getElementsByTagName("select")[0].innerHTML;
					} else {
						document.getElementById("assignmentCombo").innerHTML = "";
					}
					update_url();
					document.getElementById("courseCombo").disabled = false;
				}
			}
		xmlhttp.send(null)
		}
	}
	
	function update_url() {
		document.getElementById("assignment_url").innerHTML = document.getElementById("assignmentCombo").options[document.getElementById("assignmentCombo").selectedIndex].getAttribute('url');
		document.getElementById("assignment_url").href = document.getElementById("assignmentCombo").options[document.getElementById("assignmentCombo").selectedIndex].getAttribute('url');
	}
	
	<?php
	if( !isset( $_REQUEST['id'] ) ){
	?>
		if( window.addEventListener ) {
			window.addEventListener('load',getAssignments,false);
		} else if( window.attachEvent ) {
			window.attachEvent('onload',getAssignments);
		}
	<?php
	}
	?>
	
function validate_assessment(form) {
	var title = form.assesment_title.value.trim();
	if(title.length > 0) {
		return true;	
	}
	alert("No title entered.");
	return false;
}
</script>
<div class="wrap">
<h2>Write new post/submission</h2>
<form method="post"  onsubmit="return validate_assessment(this)">
<input type="hidden" name="action" value="postAssesment"/>
<div id="poststuff" class="metabox-holder has-right-sidebar">

	<div id="side-info-column" class="inner-sidebar">
		<div id="categorydiv" class="postbox ">
			<h3 class="hndle">
				<span>Categories</span>
			</h3>
			<div class="inside">
				<ul id="categorychecklist" class="list:category categorychecklist form-no-clear">
					<?php
						$categories=  get_categories( 'child_of=0&hide_empty=0' ); 
						foreach( $categories as $category ) {
							echo '<li id="category-' . $category->cat_ID . '">';
							echo '<label class="selectit">';
							echo '<input id="in-category-' . $category->cat_ID . '" type="checkbox" name="post_category[]" value="' . $category->cat_ID . '"/>';
							echo $category->cat_name;
							echo '</label>';
							echo '</li>';
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
				<div id="titlediv" class="postbox">
					<div id="titlewrap">
						<h3 class="hndle"><span>Title</span></h3>
						<div class="inside">
							<input name="assesment_title" tabindex="1" value="<?php echo isset($_REQUEST['assesment_title']) ? $_REQUEST['assesment_title']:''; ?>" id="title" type="text">
						</div>
					</div>
				</div>
				<div id="postassignment" class="postbox">
					<h3 class="hndle"><span>Course</span></h3>
					<div class="inside">
						<input name="advanced_view" value="1" type="hidden">
						<p class="meta-options">
							<label class="selectit">This post is execution of assignment to course
							<select id="courseCombo" name="courseCombo" onchange="getAssignments()">
								<?php
									foreach( $LePressStudent_plugin->get_subscribed_courses() as $course /*for each subscribed course*/ ) {
										echo '<option value="' . $course['courseId'] . '" ';
										if( isset( $_REQUEST['id'] ) ){
											$assignment = $LePressStudent_plugin->get_assignment( $_REQUEST['id'] );
											if( $assignment['course_id'] == $course['courseId'] ){
												echo 'selected="selected"';
											}
										}
										echo '>' . $course['courseName'] . '</option>';
									}
								?>
							</select>
						</p>
					</div>
					<div class="inside">
						<p class="meta-options">
							<label class="selectit">Current assignment
							<select id="assignmentCombo" name="assignmentCombo" onchange="update_url()" >
								<?php
									$after_load_first_url = '';
									if( isset( $_REQUEST['id'] ) ){
										foreach( $LePressStudent_plugin->get_assignments( $assignment['course_id'] ) as $assignment ){
											echo '<option value="' . $assignment['id'] . '" url="' . $assignment['post_url'] . '" ';
											if( $assignment['id'] == $_REQUEST['id'] ){
												echo 'selected="selected"';
												$after_load_first_url = $assignment['post_url'];
											}
											
											echo '>' . $assignment['title'] . '</option>';
		
										}
									}      
								?>
							</select>
						</p>
					</div>
					<div class="inside">
						<p class="meta-options">
							<label class="selectit">URL to assignment: <a id="assignment_url" href="<?php echo $after_load_first_url; ?>"><?php echo urldecode($after_load_first_url); ?></a></label>
						</p>
					</div>
					<div class="inside">
						<p class="meta-options">
							<label class="selectit">This post is draft <input name="is_draft" id="is_draft" type="checkbox" value="true"></label>
						</p>
					</div>
				</div>
				<div class="postbox">
					<h3 class="hndle"><span>Post</span></h3>
					<div id="<?php echo user_can_richedit() ? 'postdivrich' : 'postdiv'; ?>" class="postarea">
						<?php
							the_editor( (isset($_REQUEST['content'])) ? $_REQUEST['content']: '', 'content',  'assesment_title', false);
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