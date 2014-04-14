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
	<li><?php _e('Image slots available', 'AWPCP') ?>: <strong><?php echo $images_left ?></strong></li>
	<li><?php _e('Max image size', 'AWPCP') ?>: <strong><?php echo $max_image_size/1000 ?> KB</strong></li>
</ul>

<form class="awpcp-upload-images-form" method="post" enctype="multipart/form-data">

	<?php $is_primary_set = false ?>

<?php if ($images_uploaded > 0): ?>

	<?php
		$url = add_query_arg($hidden, $this->url());
		$link = '<a href="%1$s" title="%2$s"><span>%2$s</span></a>';
	?>

	<?php include( AWPCP_DIR . '/frontend/templates/page-place-ad-uploaded-images.tpl.php' ); ?>

<?php endif ?>

<?php if ($images_left > 0): ?>
	<h3><?php _e('Add Images', 'AWPCP') ?></h3>

	<p><?php _ex('Use the check icons in front of each upload field to mark the uploaded image as the primary image for the Ad.', 'upload images step', 'AWPCP'); ?></p>

	<div class="clearfix">
<?php for ($i = 0; $i < $images_left; $i++): ?>
		<div class="uploadform">
			<input class="image-upload-field" type="file" name="AWPCPfileToUpload<?php echo $i ?>" id="AWPCPfileToUpload<?php echo $i ?>" size="18" />
			<ul class="image-actions clearfix">
				<?php if (!$is_primary_set && $i == 0): ?>
				<li class="primary">
					<input id="awpcp-image-upload-field-<?php echo $i ?>" checked="checked" type="radio" name="primary-image" value="field-<?php echo $i ?>" />
				<?php else: ?>
				<li class="not-primary">
					<input id="awpcp-image-upload-field-<?php echo $i ?>" type="radio" name="primary-image" value="field-<?php echo $i ?>" />
				<?php endif ?>
					<?php $label = _x('Use as Primary Image.', 'images upload step', 'AWPCP') ?>
					<label for="awpcp-image-upload-field-<?php echo $i ?>" title="<?php echo $label ?>"><span><?php echo $label ?></span></label>
				</li>
			</ul>
		</div>
<?php endfor ?>
	</div><br/>

<?php endif ?>

	<p class="form-submit">
		<input class="button" type="submit" value="<?php echo $next; ?>" id="submit-no-images" name="submit-no-images">

		<?php if ($images_left > 0): ?>
		<input class="button" type="submit" value="<?php _e('Upload Images', 'AWPCP') ?>" id="submit" name="submit">
		<?php endif ?>

		<input type="hidden" name="step" value="upload-images">
		<?php foreach ($hidden as $name => $value): ?>
		<input type="hidden" name="<?php echo esc_attr($name) ?>" value="<?php echo esc_attr($value) ?>">
		<?php endforeach ?>
	</p>
</form>
