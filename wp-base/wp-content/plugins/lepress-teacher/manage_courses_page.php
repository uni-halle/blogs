<?php
	global $LePressTeacher_plugin, $wpdb, $current_user;
	get_currentuserinfo();
		
	if ( isset( $_REQUEST['action'] ) && $_REQUEST['action'] == "create_course" ) {
		if(!empty($current_user->first_name) && !empty($current_user -> last_name)) {
			$response = '';
			$cat_name = $_REQUEST['name'];
			$cat_description = $_REQUEST['description'];
			$cat_slug = $_REQUEST['slug'];
			$cat_parent = $_REQUEST['parent'];
			$course_org_name = $_REQUEST['organisation_name'];
			$first_name = ( isset( $_REQUEST['teacher_f_name'] ) ) ? $_REQUEST['teacher_f_name'] : '';
			$last_name = ( isset( $_REQUEST['teacher_l_name'] ) ) ? $_REQUEST['teacher_l_name'] : '';
			$email = ( isset( $_REQUEST['teacher_email'] ) ) ? $_REQUEST['teacher_email'] : '';
	
			$cat_array = array(
				'cat_name' => $cat_name,
				'category_description' => $cat_description,
				'category_nicename' => $cat_slug,
				'category_parent' => $cat_parent );
	
			$cat_id = wp_insert_category( $cat_array, true );
			if( is_wp_error( $cat_id ) ){
				$response = $cat_id->get_error_message();
			}else{
				$LePressTeacher_plugin->update_category( $cat_id, '1', $course_org_name, $first_name, $last_name, $email );
				$response = __('Course added successfully');
			}
		}
	}

	if ( isset( $_REQUEST['action'] ) && $_REQUEST['action'] == "mass_edit_courses" ) {
		$is_courses = $_REQUEST['is_course'];
		$shown_courses = $_REQUEST['shown_categories_id'];
		
		$current_user;
		get_currentuserinfo();
		
		if(is_array($is_courses)) {
			foreach( $is_courses as $term_id ){
				$has_subscribers = $LePressTeacher_plugin->get_subscribed_users_full_data($term_id);
				if(empty($has_subscribers)) {
					
					$course_data = $LePressTeacher_plugin->get_course($term_id);
					$organis_name = empty($course_data['organis_name']) ? '' : $course_data['organis_name'];
					$first_name = empty($course_data['teacher_first_name']) ? $current_user->first_name : $course_data['teacher_first_name'];
					$last_name = empty($course_data['teacher_last_name']) ? $current_user->last_name : $course_data['teacher_last_name'];
					$email = empty($course_data['teacher_email']) ? $current_user->user_email : $course_data['teacher_email'];
					
					$LePressTeacher_plugin->update_category( $term_id, '1', $organis_name, $first_name, $last_name, $email);
				}
			}
		}
		foreach( $shown_courses as $term_id ) {
			$has_subscribers = $LePressTeacher_plugin->get_subscribed_users_full_data($term_id);
			
			$course_data = $LePressTeacher_plugin->get_course($term_id);
			$organis_name = empty($course_data['organis_name']) ? '' : $course_data['organis_name'];
			$first_name = empty($course_data['teacher_first_name']) ? $current_user->first_name : $course_data['teacher_first_name'];
			$last_name = empty($course_data['teacher_last_name']) ? $current_user->last_name : $course_data['teacher_last_name'];
			$email = empty($course_data['teacher_email']) ? $current_user->user_email : $course_data['teacher_email'];
						
			if(is_array($is_courses)) {
				if( in_array( $term_id, $is_courses ) == false ){
					if(empty($has_subscribers)) {			
						$LePressTeacher_plugin->update_category( $term_id, '0', $organis_name, $first_name, $last_name, $email);
						$posts = get_posts('numberposts=-1&cat='.$term_id);
						foreach($posts as $post) {
							$this->remove_assignment($post->ID);	
						}
					}
				}
			} else {
				if(empty($has_subscribers)) {
						$LePressTeacher_plugin->update_category( $term_id, '0', $organis_name, $first_name, $last_name, $email);
						$posts = get_posts('numberposts=-1&cat='.$term_id);
						foreach($posts as $post) {
							$this->remove_assignment($post->ID);	
						}
					}
			}
		}
	}
	
		// if profile is not filled
	if((empty($current_user->first_name) || empty($current_user -> last_name)) && !isSet($response)) {
		$response =  __("You cannot create/edit course before filling out your profile. Firstname and/or lastname is not set.").' <a href="profile.php" style="text-decoration: none;" class="row-title" title="'.__("Edit profile").'">'.__("Edit profile").'</a>';
	} 
	
	if(!empty($response)) {
		echo '<div style="background-color: rgb(255, 251, 204);" id="message" class="updated fade"><p>' . $response . '</p></div>';
	}

?>
<div class="wrap">
<h2>Available Categories</h2>
<form id="mass_edit_courses" method="post">
	<input type="hidden" name="action" value="mass_edit_courses" />
	<table class="widefat fixed" cellspacing="0">
		<thead>
		<tr>
		<th scope="col" id="id" class="manage-column column-id" style="width:42px">ID</th>
		<th scope="col" id="name" class="manage-column column-name" style="">Name</th>
		<th scope="col" id="is_course" class="manage-column column-is_course" style="">Is Course</th>
		<th scope="col" id="orginisation_name" class="manage-column column-orginisation_name" style="">Organisation name</th>
		<th scope="col" id="description" class="manage-column column-description" style="">Description</th>
		<th scope="col" id="slug" class="manage-column column-slug" style="">Slug</th>
		<th scope="col" id="posts" class="manage-column column-posts num" style="">Posts</th>
		</tr>
		</thead>

		<tbody id="the-list" class="list:cat">
	<?php
		$cats = get_categories('hide_empty=0');
		foreach ((array)$cats as $categ) {
			$has_subscribers = $LePressTeacher_plugin->get_subscribed_users_full_data($categ->cat_ID);
			echo '<input type="hidden" name="shown_categories_id[]" value="' . $categ->cat_ID . '" />';
			echo '<tr id="cat-' . $categ->cat_ID . '" class="iedit alternate">';
			echo '<td class="id column-id">' . $categ->cat_ID . '</td>';
			echo '<td class="name column-name">';
			
			if(!empty($current_user->first_name) && !empty($current_user->last_name)) {
					echo '<a class="row-title" href="' .
					 add_query_arg ( array( 'action' => 'edit', 'cat_ID' => $categ->cat_ID ) )
					. '" title="Edit &quot;' . $categ->cat_name . '&quot;"> ' . $categ->cat_name . '</a>';
					
			} else {
				echo $categ->cat_name;
			}
			
			echo '</td>';
			echo '<td class="is_course column-is_course">';
			echo '<input type="checkbox" name="is_course[]" value=' . $categ->cat_ID . ' ';
			echo ( $LePressTeacher_plugin->get_is_course( $categ->cat_ID ) ) ? 'checked="checked"' : '';
			echo !empty($has_subscribers) || (empty($current_user->first_name) || empty($current_user->last_name)) ? ' disabled="disabled"' : '';
			echo ' />';
			echo '</td>';
			echo '<td class="orginisation_name column-orginisation_name" >' .
					$LePressTeacher_plugin->get_course_organisation_name( $categ->cat_ID ) . '</td>';
			echo '<td class="description column-description">' . $categ->category_description  . '</td>';
			echo '<td class="slug column-slug">' . urldecode($categ->category_nicename) . '</td>';
			echo '<td class="posts column-posts num">' . $categ->category_count . '</td>';
		}
	?>
		</tbody>
	</table>
	<p class="submit"><input type="submit" class="button-primary" name="submit" value="<?php _e('Confirm All Changes'); ?>" /></p>
</form>
</div>
<?php 
	if(empty($current_user->first_name) || empty($current_user -> last_name)) {	
		$disabled = "disabled='disabled'";
	} else {
		$disabled = '';	
	}
 ?>
<div class="wrap">
<h2>Create Course</h2>
<form id="create-course" method="post">
	<input type="hidden" name="action" value="create_course" />
	<table class="form-table" >
		<tr class="form-field">
			<th scope="row" valign="top"><label for="name"><?php _e('Name of course:') ?></label></th>
			<td><input type="text" name="name" id="name" <?php echo $disabled; ?> /><br /><?php _e('Name of course: Any name for new course, will appear in categories list too. ');?></td>
		</tr>
		<tr class="form-field">
			<th scope="row" valign="top"><label for="slug"><?php _e('Slug:') ?></label></th>
			<td><input type="text" name="slug" id="slug" <?php echo $disabled; ?> /><br/><?php _e(' The “slug” is the URL-friendly version of the name. It is usually all lowercase and contains only letters, numbers, and hyphens. ');?></td>
		</tr>
		<tr class="form-field">
			<th scope="row" valign="top"><label for="organisation_name"><?php _e('Organisation name:') ?></label></th>
			<td><input type="text" name="organisation_name" id="organisation_name" <?php echo $disabled; ?> /><br /> <?php _e('There can be name of educational institution on the base course is provided. It will be showed in microformats metadata related to course.');?> </td>
		</tr>
		<tr class="form-field form-required">
			<th scope="row" valign="top"><label for="teacher_f_name"><?php _e('Teacher First Name') ?></label></th>
			<td><input name="teacher_f_name" id="teacher_f_name" type="text" <?php echo $disabled; ?>  value="<?php
				echo $current_user->user_firstname;
				?>" /><br/><?php _e('Your name. It can be changed here.')?> </td>
		</tr>
		<tr class="form-field form-required">
			<th scope="row" valign="top"><label for="teacher_l_name"><?php _e('Teacher Last Name') ?></label></th>
			<td><input name="teacher_l_name" id="teacher_l_name" type="text" <?php echo $disabled; ?>  value="<?php
				echo $current_user->user_lastname;
				?>" /><br/><?php _e('Your last name. It can be changed here.');?> </td>
		</tr>
		<tr class="form-field form-required">
			<th scope="row" valign="top"><label for="teacher_email"><?php _e('Teacher Email Address') ?></label></th>
			<td><input name="teacher_email" id="teacher_email" <?php echo $disabled; ?>  type="text" value="<?php
				echo $current_user->user_email;
				?>" /><br /><?php _e('Your email address. It can be changed here.');?></td>
		</tr>
		<tr class="form-field">
			<th scope="row" valign="top"><label for="parent"><?php _e('Parent:') ?></label></th>
			<td><select name="parent" id="parent" <?php echo $disabled; ?> >
			<option value="-1">None</option>
			<?php
			foreach ((array)$cats as $categ) {
				echo '<option class="level-0" value="' . $categ->cat_ID . '">' . $categ->cat_name . '</option>';
			}
			?>
			</select><br /><?php _e('Categories, unlike tags, can have a hierarchy. So if you create course, you can arrange it in some hierarchy of courses too.'); ?> </td>
		</tr>
		<tr class="form-field">
			<th scope="row" valign="top"><label for="description"><?php _e('Description:') ?></label></th>
			<td><textarea name="description" id="description" rows=5 <?php echo $disabled; ?> ></textarea><br /><?php _e('The description is field from category. Additional info about course may be provided.');?></td>
		</tr>

	</table>
	<p class="submit"><input type="submit" class="button-primary" name="submit"  <?php echo $disabled; ?> value="<?php _e('Add Course'); ?>" /></p>
</form>
</div>
