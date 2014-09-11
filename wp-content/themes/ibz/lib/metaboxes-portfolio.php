<?php
/**
 * Reusable meta boxes for posts
 */
 
// Add the Meta Box
function add_portfolio_meta_box() {
    add_meta_box(
		'portfolio_meta_box', // $id
		'Options', // $title 
		'show_portfolio_meta_box', // $callback
		'portfolio', // $page
		'normal', // $context
		'high'); // $priority
}
add_action('add_meta_boxes', 'add_portfolio_meta_box');

// Field Array
$prefix = 'portfolio_';
$portfolio_meta_fields = array(

	array(
		'label'	=> 'Video URL',
		'desc'	=> 'Paste YouTube or Vimeo link here if this item is a video.',
		'id'	=> $prefix.'video_url',
		'type'	=> 'text'
	),
	
);

// The Callback
function show_portfolio_meta_box() {
	global $portfolio_meta_fields, $post;
	// Use nonce for verification
	echo '<input type="hidden" name="portfolio_meta_box_nonce" value="'.wp_create_nonce(basename(__FILE__)).'" />';
	
	// Begin the field table and loop
	echo '<table class="form-table">';
	foreach ($portfolio_meta_fields as $field) {
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
function save_portfolio_meta($post_id) {
    global $portfolio_meta_fields;
	
	// verify nonce
	if (!wp_verify_nonce(isset($_POST['portfolio_meta_box_nonce']) && $_POST['portfolio_meta_box_nonce'], basename(__FILE__))) 
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
	foreach ($portfolio_meta_fields as $field) {
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
add_action('save_post', 'save_portfolio_meta');

?>