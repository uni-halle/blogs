<?php $this->start_element('nextgen_gallery.gallery_container', 'container', $displayed_gallery); ?>

<?php if ($show_thumbnail_link) { ?>
	<!-- Thumbnails Link -->
	<div class="slideshowlink">
        <a href='<?php esc_attr_e($thumbnail_link); ?>'><?php esc_html_e($thumbnail_link_text); ?></a>
	</div>
<?php } ?>

<div class="ngg-slideshow-image-list ngg-slideshow-nojs" id="<?php esc_attr_e($anchor); ?>-image-list">
	<?php
	$this->include_template('photocrati-nextgen_gallery_display#list/before');
	for ($i = 0; $i < count($images); $i++) {
		// Determine image dimensions
		$image = $images[$i];
		$image_size = $storage->get_original_dimensions($image);
		if ($image_size == null)
		{
			$image_size['width'] = $image->meta_data['width'];
			$image_size['height'] = $image->meta_data['height'];
		}

		// Determine whether an image is hidden or not
		if (isset($image->hidden) && $image->hidden) {
			$image->style = 'style="display: none;"';
		}
		else {
			$image->style = '';
		}

		// Determine image aspect ratio
		$image_ratio = $image_size['width'] / $image_size['height'];
		if ($image_ratio > $aspect_ratio)
		{
			if ($image_size['width'] > $gallery_width)
			{
				$image_size['width'] = $gallery_width;
				$image_size['height'] = (int) round($gallery_width / $image_ratio);
			}
		}
		else {
			if ($image_size['height'] > $gallery_height)
			{
				$image_size['width'] = (int) round($gallery_height * $image_ratio);
				$image_size['height'] = $gallery_height;
			}
		}

		$template_params = array(
			'index' => $i,
			'class' => 'ngg-gallery-slideshow-image'
		);
		$template_params = array_merge(get_defined_vars(), $template_params);
		$this->include_template('photocrati-nextgen_gallery_display#image/before', $template_params);
		?>
		<img data-image-id='<?php echo esc_attr($image->pid); ?>'
		     title="<?php echo esc_attr($image->description)?>"
		     alt="<?php echo esc_attr($image->alttext)?>"
		     src="<?php echo esc_attr($storage->get_image_url($image, 'full', TRUE))?>"
		     width="<?php echo esc_attr($image_size['width'])?>"
		     height="<?php echo esc_attr($image_size['height'])?>"/>
		<?php
		$this->include_template('photocrati-nextgen_gallery_display#image/after', $template_params);
	}
	$this->include_template('photocrati-nextgen_gallery_display#list/after');
	?>
</div>
<?php $this->include_template('photocrati-nextgen_gallery_display#container/before'); ?>
<div class="ngg-galleryoverview ngg-slideshow"
     id="<?php esc_attr_e($anchor); ?>"
     data-placeholder="<?php echo nextgen_esc_url($placeholder); ?>"
     style="max-width: <?php esc_attr_e($gallery_width); ?>px; max-height: <?php esc_attr_e($gallery_height); ?>px;">
	<div class="ngg-slideshow-loader"
	     id="<?php esc_attr_e($anchor); ?>-loader"
	     style="width: <?php esc_attr_e($gallery_width); ?>px; height: <?php esc_attr_e($gallery_height); ?>px;">
		<img src="<?php esc_attr_e(NGGALLERY_URLPATH); ?>images/loader.gif" alt=""/>
	</div>
</div>
<?php $this->include_template('photocrati-nextgen_gallery_display#container/after'); ?>
<script type="text/javascript">
	jQuery('#<?php esc_attr_e($anchor); ?>-image-list').hide().removeClass('ngg-slideshow-nojs');
	jQuery(function($) {
		jQuery('#<?php esc_attr_e($anchor); ?>').nggShowSlideshow({
			id: '<?php esc_attr_e($displayed_gallery_id); ?>',
			fx: '<?php esc_attr_e($cycle_effect); ?>',
			width: <?php esc_attr_e($gallery_width); ?>,
			height: <?php esc_attr_e($gallery_height); ?>,
			domain: '<?php esc_attr_e(trailingslashit(home_url())); ?>',
			timeout: <?php esc_attr_e(intval($cycle_interval) * 1000); ?>
		});
	});
</script>
<?php $this->end_element(); ?>
