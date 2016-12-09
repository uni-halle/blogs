<?php $this->start_element('nextgen_gallery.gallery_container', 'container', $displayed_gallery); ?>
<div class="ngg-albumoverview">
    <?php foreach ($galleries as $gallery) { ?>
        <div class="ngg-album-compact">
            <div class="ngg-album-compactbox">
                <div class="ngg-album-link">
                    <?php $this->start_element('nextgen_gallery.album_gallery', 'item', $gallery); ?>
                    <?php if ($open_gallery_in_lightbox AND $gallery->entity_type == 'gallery'): ?>
                        <a
                            <?php echo $gallery->displayed_gallery->effect_code ?>
                            href="<?php echo esc_attr($gallery->previewpic_fullsized_url)?>"
                            data-fullsize="<?php echo esc_attr($gallery->previewpic_fullsized_url) ?>"
                            data-src="<?php echo esc_attr($gallery->previewpic_fullsized_url) ?>"
                            data-thumbnail="<?php echo esc_attr($gallery->previewurl)?>"
                            data-title="<?php echo esc_attr($gallery->previewpic_image->alttext)?>"
                            data-description="<?php echo esc_attr(stripslashes($gallery->previewpic_image->description))?>"
                            data-image-id="<?php echo esc_attr($gallery->previewpic)?>"
                        >
                            <img class="Thumb"
                                 alt="<?php echo esc_attr($gallery->title); ?>"
                                 src="<?php echo nextgen_esc_url($gallery->previewurl); ?>"/>
                        </a>
                    <?php else: ?>
                        <a class="Link gallery_link" href="<?php echo nextgen_esc_url($gallery->pagelink); ?>">
                            <img class="Thumb"
                                 alt="<?php echo esc_attr($gallery->title); ?>"
                                 src="<?php echo nextgen_esc_url($gallery->previewurl); ?>"/>
                        </a>
                    <?php endif ?>
                    <?php $this->end_element(); ?>
                </div>
            </div>
            <?php if (!empty($image_gen_params)) {
                $max_width = 'style="max-width: ' . ($image_gen_params['width'] + 20) . 'px"';
            } else {
                $max_width = '';
            } ?>
            <h4>
                <a class="ngg-album-desc"
                   title="<?php echo esc_attr($gallery->title); ?>"
                   href="<?php echo nextgen_esc_url($gallery->pagelink); ?>"
                    <?php echo $max_width; ?>>
                    <?php echo_safe_html($gallery->title); ?>
                </a>
            </h4>
            <p class="ngg-album-gallery-image-counter">
                <?php if (isset($gallery->counter) && $gallery->counter > 0) { ?>
                    <strong><?php echo $gallery->counter; ?></strong>&nbsp;<?php _e('Photos', 'nggallery'); ?>
                <?php } else { ?>
                    &nbsp;
                <?php } ?>
            </p>
        </div>
    <?php } ?>
    <br class="ngg-clear"/>
    <?php echo $pagination ?>
</div>
<?php $this->end_element(); ?>
