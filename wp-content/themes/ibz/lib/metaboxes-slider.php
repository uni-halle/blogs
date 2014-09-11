<?php
/**
 * Reusable meta boxes for posts
 */

// Add the Meta Box
function add_slider_meta_box() {
    add_meta_box(
		'slider_meta_box', // $id
		'Caption', // $title 
		'show_slider_meta_box', // $callback
		'flex_slider', // $page
		'normal', // $context
		'high'); // $priority
}
add_action('add_meta_boxes', 'add_slider_meta_box');

// Field Array
$prefix = 'slider_';
$slider_meta_fields = array(
	array(
		'label'	=> 'Optional Caption',
		'desc'	=> '',
		'id'	=> $prefix.'caption',
		'type'	=> 'textarea'
	),
	
);

// The Callback
function show_slider_meta_box() {
	global $slider_meta_fields, $post;
	// Use nonce for verification
	echo '<input type="hidden" name="slider_meta_box_nonce" value="'.wp_create_nonce(basename(__FILE__)).'" />';
	
	// Begin the field table and loop
	echo '<table class="form-table">';
	foreach ($slider_meta_fields as $field) {
		// get value of this field if it exists for this post
		$meta = get_post_meta($post->ID, $field['id'], true);
		// begin a table row with
		echo '<tr>
				<th><label for="'.$field['id'].'">'.$field['label'].'</label></th>
				<td>';
				switch($field['type']) {
					// text
					case 'text':
						echo '<input type="text" name="'.$field['id'].'" id="'.$field['id'].'" value="'.$meta.'" size="30" />
								<br /><span class="description">'.$field['desc'].'</span>';
					break;
					// textarea
					case 'textarea':
						echo '<textarea name="'.$field['id'].'" id="'.$field['id'].'" cols="60" rows="4">'.$meta.'</textarea>
								<br /><span class="description">'.$field['desc'].'</span>';
					break;

				} //end switch
		echo '</td></tr>';
	} // end foreach
	echo '</table>'; // end table
}

// Save the Data
function save_slider_meta($post_id) {
    global $slider_meta_fields;
	
	// verify nonce
	if (!wp_verify_nonce(isset($_POST['slider_meta_box_nonce']) && $_POST['slider_meta_box_nonce'], basename(__FILE__))) 
		return $post_id;
	// check autosave
	if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE)
		return $post_id;
	// check permissions
	if ('page' == $_POST['post_type']) {
		if (!current_user_can('edit_page', $post_id))
			return $post_id;
		} elseif (!current_user_can('edit_post', $post_id)) {
			return $post_id;
	}
	
	// loop through fields and save the data
	foreach ($slider_meta_fields as $field) {
		if($field['type'] == 'tax_select') continue;
		$old = get_post_meta($post_id, $field['id'], true);
		$new = $_POST[$field['id']];
		if ($new && $new != $old) {
			update_post_meta($post_id, $field['id'], $new);
		} elseif ('' == $new && $old) {
			delete_post_meta($post_id, $field['id'], $old);
		}
	} // enf foreach
}
add_action('save_post', 'save_slider_meta');
?>