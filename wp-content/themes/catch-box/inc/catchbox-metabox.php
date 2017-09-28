<?php
/**
 * Catch Box Pro Custom meta box
 *
 * @package Catch Themes
 * @subpackage Catch_Box_Pro
 * @since Catch Box Pro 1.0
 */
/**
 * Enqueue scripts and styles for Metaboxes
 * @uses wp_register_script, wp_enqueue_script, and  wp_enqueue_style
 *
 * @action admin_print_scripts-post-new, admin_print_scripts-post, admin_print_scripts-page-new, admin_print_scripts-page
 *
 * @since Catch Box 4.1
 */
function catchbox_enqueue_metabox_scripts() {
    //CSS Styles
	wp_enqueue_style( 'catchbox-metabox', get_template_directory_uri() . '/css/catchbox-metabox.css' );
}
add_action( 'admin_print_scripts-post-new.php', 'catchbox_enqueue_metabox_scripts', 11 );
add_action( 'admin_print_scripts-post.php', 'catchbox_enqueue_metabox_scripts', 11 );
add_action( 'admin_print_scripts-page-new.php', 'catchbox_enqueue_metabox_scripts', 11 );
add_action( 'admin_print_scripts-page.php', 'catchbox_enqueue_metabox_scripts', 11 );


// Add the Meta Box
function catchbox_add_custom_box() {
	add_meta_box(
		'siderbar-layout',							//Unique ID
       __( 'Catch Box Options', 'catch-box' ),		//Title
        'catchbox_sidebar_layout',					//Callback function
        'page'										//show metabox in pages
    );
	add_meta_box(
		'siderbar-layout',							//Unique ID
       __( 'Catch Box Options', 'catch-box' ),		//Title
        'catchbox_sidebar_layout',					//Callback function
        'post'										//show metabox in pages
    );
}
add_action( 'add_meta_boxes', 'catchbox_add_custom_box' );


global $sidebar_layout;
$sidebar_layout = array(
	'default-sidebar' => array(
		'id'		=> 'catchbox-sidebarlayout',
		'value' 	=> 'default',
		'label'		=> sprintf( __( 'Default Layout Set in <a href="%s">Theme Settings</a>', 'catch-box' ), esc_url( admin_url('admin.php?page=theme_options' ) ) ),
		'thumbnail' => ' '
	),
	'right-sidebar' => array(
		'id'        => 'catchbox-sidebarlayout',
		'value'     => 'right-sidebar',
		'label'     => __( 'Right sidebar', 'catch-box' ),
		'thumbnail' => get_template_directory_uri() . '/images/right-sidebar.png'
	),
	'left-sidebar' => array(
		'id'		=> 'catchbox-sidebarlayout',
		'value' 	=> 'left-sidebar',
		'label' 	=> __( 'Left sidebar', 'catch-box' ),
		'thumbnail' => get_template_directory_uri() . '/images/left-sidebar.png'
	),
	'no-sidebar-one-column' => array(
		'id'		=> 'catchbox-sidebarlayout',
		'value' 	=> 'no-sidebar-one-column',
		'label' 	=> __( 'No Sidebar, One Column', 'catch-box' ),
		'thumbnail' => get_template_directory_uri() . '/images/no-sidebar.png'
	)
);


/**
 * @renders metabox to for sidebar layout
 */
function catchbox_sidebar_layout() {
    global $sidebar_layout, $post;
    // Use nonce for verification
    wp_nonce_field( basename( __FILE__ ), 'custom_meta_box_nonce' );

    // Begin the field table and loop  ?>
    <div class="catchbox-meta" style="border-bottom: 2px solid #dfdfdf; margin-bottom: 10px; padding-bottom: 10px;">
    	<h4 class="title"><?php _e('Sidebar Layout Options', 'catch-box'); ?></h4>
        <table id="sidebar-metabox" class="form-table" width="100%">
            <tbody>
                <tr>
                    <?php
                    foreach ($sidebar_layout as $field) {
                        $meta = get_post_meta( $post->ID, $field['id'], true );
                        if (empty( $meta ) ){
                            $meta='default';
                        }
                        if ( $field['thumbnail']==' ' ): ?>
                                <label class="description">
                                    <input type="radio" name="<?php echo $field['id']; ?>" value="<?php echo $field['value']; ?>" <?php checked( $field['value'], $meta ); ?>/>&nbsp;&nbsp;<?php echo $field['label']; ?>
                                </label>
                        <?php else: ?>
                             <td>
                                <label class="description">
                                    <span><img src="<?php echo esc_url( $field['thumbnail'] ); ?>" width="136" height="122" alt="" /></span></br>
                                    <input type="radio" name="<?php echo $field['id']; ?>" value="<?php echo $field['value']; ?>" <?php checked( $field['value'], $meta ); ?>/>&nbsp;&nbsp;<?php echo $field['label']; ?>
                                </label>
                            </td>
                        <?php endif;
                    } // end foreach
                    ?>
                </tr>
            </tbody>
        </table>
  	</div><!-- .catchbox-meta -->
<?php
}


/**
 * save the custom metabox data
 * @hooked to save_post hook
 */
function catchbox_save_custom_meta( $post_id ) {
	global $sidebar_layout, $sidebar_options, $post;

	// Verify the nonce before proceeding.
    if ( !isset( $_POST[ 'custom_meta_box_nonce' ] ) || !wp_verify_nonce( $_POST[ 'custom_meta_box_nonce' ], basename( __FILE__ ) ) )
        return;

	// Stop WP from clearing custom fields on autosave
    if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE)
        return;

	if ('page' == $_POST['post_type']) {
        if (!current_user_can( 'edit_page', $post_id ) )
            return $post_id;
    } elseif (!current_user_can( 'edit_post', $post_id ) ) {
            return $post_id;
    }

	foreach ($sidebar_layout as $field) {
		//Execute this saving function
		$old = get_post_meta( $post_id, $field['id'], true);
		$new = $_POST[$field['id']];
		if ($new && $new != $old) {
			update_post_meta($post_id, $field['id'], $new);
		} elseif ('' == $new && $old) {
			delete_post_meta($post_id, $field['id'], $old);
		}
	 } // end foreach
}
add_action('save_post', 'catchbox_save_custom_meta');
