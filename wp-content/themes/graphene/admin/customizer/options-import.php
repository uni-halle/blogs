<?php 
/**
 * Export the theme's options
 */
function graphene_export_options(){
	if ( ! isset( $_GET['graphene-export'] ) ) return;
    ob_clean();
	
	/* Check authorisation */
	$authorised = true;
	if ( ! wp_verify_nonce( $_GET['nonce'], 'graphene-export' ) ) $authorised = false;
	if ( ! current_user_can( 'edit_theme_options' ) ) $authorised = false;
	
	if ( $authorised ) {
		$is_legacy = ( $_GET['type'] ) ? true : false;

		if ( $is_legacy ) {
			$name = 'graphene-legacy-options-' . current_time( 'Ymd-Hi' ) . '.txt';
			$data = get_option( 'graphene_legacy_settings', array() );
		} else {
			$name = 'graphene-options-' . current_time( 'Ymd-Hi' ) . '.txt';
			$data = get_option( 'graphene_settings', array() );
		}
		
		if ( array_key_exists( 'template_dir', $data ) ) unset( $data['template_dir'] );
		
		$data = json_encode( $data );
		$size = strlen( $data );
	
		header( 'Content-Type: text/plain' );
		header( 'Content-Disposition: attachment; filename="'.$name.'"' );
		header( "Content-Transfer-Encoding: binary" );
		header( 'Accept-Ranges: bytes' );
	
		/* The three lines below basically make the download non-cacheable */
		header( "Cache-control: private" );
		header( 'Pragma: private' );
		header( "Expires: Mon, 26 Jul 1997 05:00:00 GMT" );
	
		header( "Content-Length: " . $size);
		print( $data );
	
	} else {
		wp_die( __( 'ERROR: You are not authorised to perform that operation', 'graphene' ) );
	}

    die();   
}
add_action( 'admin_init', 'graphene_export_options' );


/**
 * This file manages the colour presets uploading and import operations.
 *
 * @package Graphene
 * @since Graphene 1.9
*/
function graphene_import_colour_presets(){            
    
    $bytes = apply_filters( 'import_upload_size_limit', wp_max_upload_size() );
    $size = size_format( $bytes, 2 );
    $upload_dir = wp_upload_dir();
    if ( ! empty( $upload_dir['error'] ) ) :
        ?><div class="error"><p><?php _e( 'Before you can upload your import file, you will need to fix the following error:', 'graphene' ); ?></p>
            <p><strong><?php echo $upload_dir['error']; ?></strong></p></div><?php
    else :
    ?>
    <div class="wrap">
        <div id="icon-tools" class="icon32"><br></div>
        <h2><?php echo __( 'Import Graphene Theme Colour Presets', 'graphene' );?></h2>
        <form enctype="multipart/form-data" id="import-upload-form" method="post" action="<?php echo admin_url( 'themes.php?page=graphene_options&tab=colours' ); ?>" onsubmit="return grapheneCheckFile(this,'colours');">
            <p>
                <label for="upload"><?php _e( 'Choose a Graphene theme\'s colour presets export file from your computer:', 'graphene' ); ?></label><br />
                <input type="file" id="upload" name="import" size="25" /> <span class="description">(<?php printf( __( 'Maximum size: %s', 'graphene' ), $size ); ?>)</span>
                <input type="hidden" name="action" value="save" />
                <input type="hidden" name="max_file_size" value="<?php echo $bytes; ?>" />
                <?php wp_nonce_field( 'graphene-import', 'graphene-import' ); ?>
                <input type="hidden" name="graphene_import_confirmed" value="true" />
                <input type="hidden" name="graphene_import_colour_presets_confirmed" value="true" />
            </p>
            <input type="submit" class="button-primary" value="<?php _e( 'Upload file and import', 'graphene' ); ?>" />
        </form>
    </div> <!-- end wrap -->
    <?php
    endif;
}


/**
 * This file manages the colour presets export operations.
 *
 * @package Graphene
 * @since Graphene 1.9
*/
function graphene_export_colour_presets(){
	global $graphene_settings;
	?>
	<div class="wrap">
        <div id="icon-tools" class="icon32"><br></div>
        <h2><?php echo __( 'Export Graphene Theme Colour Presets', 'graphene' );?></h2>    
        <form enctype="multipart/form-data" id="import-upload-form" method="post" action="">
            <p>
                <?php _e( 'Please select the colour presets to be exported.', 'graphene' ); ?>
                <ul>
                <?php foreach ( $graphene_settings['colour_presets'] as $key => $preset ) : ?>
                	<li><input type="checkbox" name="presets[]" value="<?php echo $key; ?>" id="preset-<?php echo $key; ?>" /> <label for="preset-<?php echo $key; ?>"><?php echo $preset['name']; ?></label></li>
                <?php endforeach; ?>
                </ul>
            </p>
            <?php wp_nonce_field( 'graphene-export', 'graphene-export' ); ?>
            <input type="hidden" name="graphene_export" value="true" />
            <input type="hidden" name="graphene_export_colours" value="true" />
            <input type="submit" class="button-primary" value="<?php _e( 'Export colour presets', 'graphene' ); ?>" />
			<p><a href="<?php echo admin_url( 'themes.php?page=graphene_options&tab=colours' ); ?>"><?php _e( '&laquo; Return to Graphene Options', 'graphene' ); ?></a></p>
        </form>
    </div>
    <?php
}


/**
 * Manages file upload and settings import
 */
function graphene_import_file() {
	if ( ! isset( $_REQUEST['graphene-import'] ) ) return;
    global $graphene_settings;
    
    /* Check authorisation */
    $authorised = true;
    if ( ! wp_verify_nonce( $_POST['graphene-nonce'], 'graphene-import' ) ) $authorised = false;
    if ( ! current_user_can( 'edit_theme_options' ) ) $authorised = false;
    if ( ! $authorised ) wp_die( __( 'Authorisation checks failed to perform this operation.', 'graphene' ) );
    
	if ( ! function_exists( 'wp_handle_upload' ) ) require_once( ABSPATH . 'wp-admin/includes/file.php' );
	$file = $_FILES['graphene-import-file'];

	$import_file = wp_handle_upload( $file, array( 'test_form' => false, 'mimes' => array( 'txt' => 'text/plain' ) ) );
	if ( isset( $import_file['error'] ) ) wp_die( $import_file['error'] );

	/* Get filesystem credentials to read the file */
	global $wp_filesystem;
	$url = wp_nonce_url( 'customize.php?graphene-import=1', 'graphene-import' );

	if ( false === ( $creds = request_filesystem_credentials( $url, '', false, false, array( 'import' ) ) ) ) return true;
	if ( ! WP_Filesystem( $creds ) ) {
		request_filesystem_credentials( $url, $method, true, false, $form_fields );
		return true;
	}

	$data = $wp_filesystem->get_contents( $import_file['file'] );
	wp_delete_file( $import_file['file'] );

	/* Check that the file has data */
	if ( $data === false ) wp_die( __( 'Could not read uploaded file. Please check the file and try again.', 'graphene' ) );

	/* Check that the file data is Graphene settings */
	$settings = json_decode( $data, true );
	if ( ! $settings ) wp_die( __( 'Uploaded file does not contain any setting to import. Please check and try again.', 'graphene' ) );
	if ( ! isset( $settings['db_version'] ) ) wp_die( __( 'The uploaded file does not contain valid Graphene options. Please check and try again.', 'graphene' ) );

	/* Update the settings if everything checks out */
	$settings = array_merge( $graphene_settings, $settings );
	update_option( 'graphene_settings', $settings );
}
add_action( 'admin_init', 'graphene_import_file' ); 