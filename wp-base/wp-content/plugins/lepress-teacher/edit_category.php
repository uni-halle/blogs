<?php
	$cat_ID = (int) $_GET['cat_ID'];
	$category = get_category_to_edit($cat_ID);
	global $LePressTeacher_plugin, $current_user;
	$course = $LePressTeacher_plugin->get_course( $cat_ID );
	$has_subscribers = $LePressTeacher_plugin->get_subscribed_users_full_data($cat_ID);
	
	global $current_user;
	get_currentuserinfo();
	
	if(empty($current_user->first_name) || empty($current_user->last_name)) {
		echo '<script type="text/javascript">window.location = "'.get_option('siteurl') .'/wp-admin/admin.php?page=LePressTeacher/main.php";'.'</script>';
		exit('Edit your profile first, firstname and lastname empty!');
	}
?>
<div class="wrap">
<h2><?php _e( 'Assign Course' ); ?></h2>
<div id="ajax-response"></div>
<form name="edit" id="edit" method="post" action="<?php echo $_SERVER['REQUEST_URI']; ?>" class="validate">
<input type="hidden" name="action" value="editedcat" />
<input type="hidden" name="cat_ID" value="<?php echo $category->term_id ?>" />
	<table class="form-table">
		<tr class="form-field form-required">
			<th scope="row" valign="top"><label for="cat_name"><?php _e('Category Name') ?></label></th>
			<td><?php echo attribute_escape($category->name); ?><br /><?php _e('Name of course: Any name for new course, will appear in categories list too.');?></td>
		</tr>
		<tr class="form-field form-required">
			<th scope="row" valign="top"><label for="teacher_f_name"><?php _e('Teacher First Name') ?></label></th>
			<td><input name="teacher_f_name" id="teacher_f_name" type="text" value="<?php
				echo ( $course['teacher_first_name'] != '' ) ? $course['teacher_first_name'] : $current_user->user_firstname;
				?>" /><br /><?php _e('Your name. It can be changed here.');?></td>
		</tr>
		<tr class="form-field form-required">
			<th scope="row" valign="top"><label for="teacher_l_name"><?php _e('Teacher Last Name') ?></label></th>
			<td><input name="teacher_l_name" id="teacher_l_name" type="text" value="<?php
				echo ( $course['teacher_last_name'] != '' ) ? $course['teacher_last_name'] : $current_user->user_lastname;
				?>" /><br /> <?php _e('Your last name. It can be changed here.'); ?></td>
		</tr>
		<tr class="form-field form-required">
			<th scope="row" valign="top"><label for="teacher_email"><?php _e('Teacher Email Address') ?></label></th>
			<td><input name="teacher_email" id="teacher_email" type="text" value="<?php
				echo ( $course['teacher_email'] != '' ) ? $course['teacher_email'] : $current_user->user_email;
				?>" /><br /><?php _e('Your email address. It can be changed here.'); ?></td>
		</tr>
		<tr class="form-field">
			<th scope="row" valign="top"><label for="category_is_course"><?php _e('Category is a Course') ?></label></th>
			<td align="left"><input name="category_is_course" id="category_is_course" type="checkbox" value="1" <?php echo ( $LePressTeacher_plugin->get_is_course( $category->cat_ID ) ) ? 'checked="checked"' : ''; echo !empty($has_subscribers) ? 'disabled="disabled"': ''; ?> style="width:10px"><br /> <?php _e('Whether the category is a course not.');?></td>
		</tr>
		<tr class="form-field">
			<th scope="row" valign="top"><label for="category_organisation_name"><?php _e('Organisation name') ?></label></th>
			<td><input name="category_organisation_name" id="category_organisation_name" type="text" value="<?php
				echo attribute_escape( $course['organis_name'] ); ?>" size="40" /><br /><?php _e('There can be name of educational institution on the base course is provided. It will be showed in microformats metadata related to course.');?></td>
		</tr>
			<tr class="form-field">
			<th scope="row" valign="top"><label for="parent"><?php _e('Parent:') ?></label></th>
			<td><select name="parent" id="parent">
			<option value="-1">None</option>
			<?php
			$cats = get_categories('hide_empty=0&exclude='.$cat_ID);
			foreach ((array)$cats as $categ) {
				if($category->parent == $categ->cat_ID) {
					echo '<option class="level-0" value="' . $categ->cat_ID . '" selected="selected">' . $categ->cat_name . '</option>';
				} else {
					echo '<option class="level-0" value="' . $categ->cat_ID . '">' . $categ->cat_name . '</option>';
				}
			}
			?>
			</select><br /> <?php _e('Categories, unlike tags, can have a hierarchy. So if you create course, you can arrange it in some hierarchy of courses too. ');?></td>
		</tr>
		<tr class="form-field">
			<th scope="row" valign="top"><label for="description"><?php _e('Description:') ?></label></th>
			<td><textarea name="description" id="description" rows=5 ><?php echo $category->category_description;?></textarea><br /><?php _e('The description is field from category. Additional info about course may be provided.');?></td>
		</tr>
	</table>
	<?php if( $course['teacher_first_name'] == '' || $course['teacher_last_name'] == '' || $course['teacher_email'] == '' ){
		echo '<div style="background-color: rgb(255, 251, 204);" id="message" class="updated fade"><p>'
			. __( 'Some fields are autofilled, click \'Save changes\' to save them!' ) . '</p></div>';}
			
			if(isSet($validate_failed) && $validate_failed) {
				echo '<div style="background-color: rgb(255, 251, 204);" id="message" class="updated fade"><p>'
			. __( 'Firstname, lastname and email cannot be empty!' ) . '</p></div>';
			}
			
			?>
<p class="submit"><input type="submit" class="button-primary" name="submit" value="<?php _e('Save changes'); ?>" /></p>
</form>
</div>