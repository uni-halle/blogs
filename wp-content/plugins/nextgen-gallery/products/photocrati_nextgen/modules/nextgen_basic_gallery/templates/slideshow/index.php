<?php $this->start_element('nextgen_gallery.gallery_container', 'container', $displayed_gallery); ?>

<?php $this->include_template('photocrati-nextgen_gallery_display#container/before'); ?>

<div class="ngg-galleryoverview ngg-slideshow"
	 id="<?php echo esc_attr($anchor); ?>"
     style="max-width: <?php echo esc_attr($gallery_width); ?>px; max-height: <?php echo esc_attr($gallery_height); ?>px;">

 	<?php for ($i = 0; $i < count($images); $i++) {

 		$image = $images[$i];
 		$image->style = 'style="height:' . esc_attr($gallery_height) . 'px"';
		$template_params = array(
			'index' => $i,
			'class' => 'ngg-gallery-slideshow-image'
		);
		$template_params = array_merge(get_defined_vars(), $template_params);
		
			$this->include_template('photocrati-nextgen_gallery_display#image/before', $template_params);

			 	?>
					
					<a href="<?php echo esc_attr($storage->get_image_url($image)); ?>"
                      title="<?php echo esc_attr($image->description); ?>"
                      data-src="<?php echo esc_attr($storage->get_image_url($image)); ?>"
                      data-thumbnail="<?php echo esc_attr($storage->get_image_url($image, 'thumb')); ?>"
                      data-image-id="<?php echo esc_attr($image->{$image->id_field}); ?>"
                      data-title="<?php echo esc_attr($image->alttext); ?>"
                      data-description="<?php echo esc_attr(stripslashes($image->description)); ?>"
                      <?php echo $effect_code ?>>

						<img data-image-id='<?php echo esc_attr($image->pid); ?>'
					     title="<?php echo esc_attr($image->description)?>"
					     alt="<?php echo esc_attr($image->alttext)?>"
					     src="<?php echo esc_attr($storage->get_image_url($image, 'full', TRUE))?>"
					     style="max-height: <?php echo esc_attr($gallery_height -20); ?>px;"
					     />

					</a>

				<?php
		
			$this->include_template('photocrati-nextgen_gallery_display#image/after', $template_params);

	} ?>
	
</div>

<?php $this->include_template('photocrati-nextgen_gallery_display#container/after'); ?>

<?php if ($show_thumbnail_link) { ?>
		<!-- Thumbnails Link -->
	<div class="slideshowlink" style="max-width: <?php echo esc_attr($gallery_width); ?>px;">
        <a href='<?php echo esc_attr($thumbnail_link); ?>'><?php echo esc_html($thumbnail_link_text); ?></a>
	</div>
<?php } ?>

<?php $this->end_element(); ?>