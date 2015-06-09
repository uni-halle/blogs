<h2><?php _e('Upload Files', 'AWPCP') ?></h2>

<?php
    if ( isset( $transaction ) && get_awpcp_option( 'show-create-listing-form-steps' ) ) {
        echo awpcp_render_listing_form_steps( 'upload-files', $transaction );
    }
?>

<?php
    if (get_awpcp_option('imagesapprove') == 1) {
        $messages[] = __('Image approval is in effect so any new images you upload will not be visible to viewers until an admin approves them.', 'AWPCP');
    }

    if ($images_uploaded > 0) {
        $messages[] = _x('Thumbnails of already uploaded images are shown below.', 'images upload step', 'AWPCP');
    }

    foreach ($messages as $message) {
        echo awpcp_print_message($message);
    }

	foreach($errors as $error) {
		echo awpcp_print_message($error, array('error'));
	}
?>

<?php include( AWPCP_DIR . '/templates/components/media-center.tpl.php' ); ?>

<form class="awpcp-upload-images-form" method="post" enctype="multipart/form-data">
	<p class="form-submit">
		<input class="button" type="submit" value="<?php echo esc_attr( $next ); ?>" id="submit-no-images" name="submit-no-images">

		<input type="hidden" name="step" value="upload-images">
		<?php foreach ($hidden as $name => $value): ?>
		<input type="hidden" name="<?php echo esc_attr($name) ?>" value="<?php echo esc_attr($value) ?>">
		<?php endforeach ?>
	</p>
</form>
