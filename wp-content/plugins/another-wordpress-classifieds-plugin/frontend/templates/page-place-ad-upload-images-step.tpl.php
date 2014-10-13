<h2><?php _e('Upload Images', 'AWPCP') ?></h2>

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

<ul class="upload-conditions clearfix">
	<li><?php _e('Image slots available', 'AWPCP') ?>: <strong><?php echo esc_html( $images_left ); ?></strong></li>
	<li><?php _e('Max image size', 'AWPCP') ?>: <strong><?php echo esc_html( $max_image_size / 1000 ); ?> KB</strong></li>
</ul>

<?php $fm = awpcp_file_manager_component(); ?>
<?php $fm->configure( array( 'images_allowed' => $images_allowed ) ); ?>
<?php echo $fm->render( $listing, $images ); ?>

<form class="awpcp-upload-images-form" method="post" enctype="multipart/form-data">
	<h3><?php _e('Add Images', 'AWPCP') ?></h3>

	<p><?php _ex('Use the check icons in front of each upload field to mark the uploaded image as the primary image for the Ad.', 'upload images step', 'AWPCP'); ?></p>

	<?php $files_left = max( 1, $images_left ); ?>
    <?php include( AWPCP_DIR . '/frontend/templates/page-place-ad-upload-fields.tpl.php' ); ?>

	<p class="form-submit">
		<input class="button" type="submit" value="<?php echo esc_attr( $next ); ?>" id="submit-no-images" name="submit-no-images">
		<input class="button" type="submit" value="<?php echo esc_attr( __( 'Upload Images', 'AWPCP' ) ); ?>" id="submit" name="submit">

		<input type="hidden" name="step" value="upload-images">
		<?php foreach ($hidden as $name => $value): ?>
		<input type="hidden" name="<?php echo esc_attr($name) ?>" value="<?php echo esc_attr($value) ?>">
		<?php endforeach ?>
	</p>
</form>
