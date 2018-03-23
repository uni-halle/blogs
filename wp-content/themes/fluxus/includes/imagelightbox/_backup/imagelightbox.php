<?php
//activate scripts
add_filter("template_redirect", "iljs_enqueue", 10, 1);
function iljs_enqueue() {
	wp_enqueue_script('imagelightbox', get_template_directory_uri() . '/includes/imagelightbox/imagelightbox.min.js', array('jquery'), 'r3', true);
}



// add imagetext
// get imagetext with: get_post_meta( $post->ID, '_imagetext', true )

//add metabox
function imagetext_add_meta_box() {
	$screens = array( 'attachment' );
	foreach ( $screens as $screen ) {
		add_meta_box(
			'imagetext_sectionid',
			__( 'Bildtext', 'imagetext_textdomain' ),
			'imagetext_meta_box_callback',
			$screen,
			'normal', // change to something other then normal, advanced or side
			'high'
		);
	}
}
add_action( 'add_meta_boxes', 'imagetext_add_meta_box' );

//print metabox
function imagetext_meta_box_callback( $post ) {
	wp_nonce_field( 'imagetext_meta_box', 'imagetext_meta_box_nonce' );
	wp_editor( get_post_meta( $post->ID, '_imagetext', true ), 'fluxusimagetext', array(
		'media_buttons' => false,
		'teeny' => true
		) );
}

//save metabox
function imagetext_save_meta_box_data( $post_id ) {
	if ( ! isset( $_POST['imagetext_meta_box_nonce'] ) ) { return; }
	if ( ! wp_verify_nonce( $_POST['imagetext_meta_box_nonce'], 'imagetext_meta_box' ) ) { return; }
	if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) { return; }
	if ( ! isset( $_POST['fluxusimagetext'] ) ) { return; }
	update_post_meta( $post_id, '_imagetext', $_POST['fluxusimagetext'] );
}
add_action( 'edit_attachment', 'imagetext_save_meta_box_data' );




// add imagetext
/*
add_filter('the_content', 'add_imgtxt');
function add_imgtxt($content){
	$content = preg_replace('/<img/iU', '<img imgtxt="' . wpautop(get_post_meta( $id, '_imagetext', true ) . '"', $content);
	return $content;
}
*/



?>