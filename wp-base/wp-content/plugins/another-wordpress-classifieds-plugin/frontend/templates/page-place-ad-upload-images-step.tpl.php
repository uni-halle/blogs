<h2><?php _e('Upload Images', 'AWPCP') ?></h2>

<?php awpcp_print_messages(); ?>

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

	<h3><?php _e('Existing Images', 'AWPCP') ?></h3>

	<p>
		<?php _ex('To choose one of the existing images as the primary image for your Ad, click the corresponding check icon (green means that is the primary image).', 'upload images', 'AWPCP') ?>
		<?php if ($images_left > 0): ?>
		<br><?php _ex('You can also upload a new image and mark it as primary using the fields below.', 'upload images step', 'AWPCP'); ?>
		<?php endif ?>
	</p>

	<?php
		$url = add_query_arg($hidden, $this->url());
		$link = '<a href="%1$s" title="%2$s"><span>%2$s</span></a>';
	?>

	<ul class="uploaded-images clearfix" id="uploaded-images">
	<?php foreach ($images as $image): ?>

		<?php $class = array($image->is_primary ? 'primary-image' : '', $image->disabled ? 'disabled' : 'enabled') ?>

		<li class="<?php echo join(' ', array_filter($class)) ?>">
			<img src="<?php echo awpcp_get_image_url($image, 'thumbnail') ?>" />

			<?php
				$is_primary_set = $is_primary_set || $image->is_primary;
				$url = add_query_arg('image', $image->key_id, $url);
			?>

			<ul class="image-actions clearfix">
				<?php if ($actions['enable'] && $image->disabled): ?>
				<li class="enable">
					<?php $href = add_query_arg(array('step' => 'upload-images', 'a' => 'enable-picture'), $url); ?>
					<?php echo sprintf($link, $href, _x('Enable', 'upload images', 'AWPCP')) ?>
				</li>
				<?php endif ?>

				<?php if ($actions['disable'] && $image->disabled == 0): ?>
				<li class="disable">
					<?php $href = add_query_arg(array('step' => 'upload-images', 'a' => 'disable-picture'), $url); ?>
					<?php echo sprintf($link, $href, _x('Disable', 'upload images', 'AWPCP')) ?>
				</li>
				<?php endif ?>

				<li class="delete">
					<?php $href = add_query_arg(array('step' => 'upload-images', 'a' => 'delete-picture'), $url); ?>
					<?php echo sprintf($link, $href, _x('Delete', 'upload images', 'AWPCP')) ?>
				</li>

				<?php if ($image->is_primary): ?>
				<li class="primary">
					<?php $href = add_query_arg(array('step' => 'upload-images', 'a' => 'make-not-primary'), $url); ?>
					<?php echo sprintf($link, $href, _x('Unset as Primary Image', 'upload images', 'AWPCP')) ?>
				</li>
				<?php else: ?>
				<li class="not-primary">
					<?php $href = add_query_arg(array('step' => 'upload-images', 'a' => 'make-primary'), $url); ?>
					<?php echo sprintf($link, $href, _x('Set as Primary Image', 'upload images', 'AWPCP')) ?>
				</li>
				<?php endif ?>
			</ul>
		</li>
	<?php endforeach ?>
	</ul>
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
		<?php if ($images_uploaded == 0): ?>
		<input class="button" type="submit" value="<?php _e('Place Ad without Images', 'AWPCP') ?>" id="submit" name="submit-no-images">
		<?php else: ?>
		<input class="button" type="submit" value="<?php _e('Finish', 'AWPCP') ?>" id="submit" name="submit-no-images">
		<?php endif ?>

		<?php if ($images_left > 0): ?>
		<input class="button" type="submit" value="<?php _e('Upload Images', 'AWPCP') ?>" id="submit" name="submit">
		<?php endif ?>

		<input type="hidden" name="step" value="upload-images">
		<?php foreach ($hidden as $name => $value): ?>
		<input type="hidden" name="<?php echo esc_attr($name) ?>" value="<?php echo esc_attr($value) ?>">
		<?php endforeach ?>
	</p>
</form>
