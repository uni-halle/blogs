<?php $this->start_element('nextgen_gallery.gallery_container', 'container', $displayed_gallery); ?>
<div class="ngg-albumoverview">
    <?php foreach ($galleries as $gallery) { ?>
        <div class="ngg-album">
            <div class="ngg-albumtitle">
                <a href="<?php echo nextgen_esc_url($gallery->pagelink); ?>"><?php echo_safe_html($gallery->title); ?></a>
            </div>
            <div class="ngg-albumcontent">
                <div class="ngg-thumbnail">
                <?php if ($open_gallery_in_lightbox AND $gallery->entity_type == 'gallery'): ?>
                    <a
                        <?php echo $gallery->displayed_gallery->effect_code ?>
                        href="<?php echo esc_attr($gallery->previewpic_fullsized_url)?>"
                        data-fullsize="<?php echo esc_attr($gallery->previewpic_fullsized_url) ?>"
                        data-src="http://sandbox.dev/wp-content/gallery/wood-cutting/DSC_0236.JPG"
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
                        <a
                            class="gallery_link"
                            href="<?php echo nextgen_esc_url($gallery->pagelink); ?>"
                        >
                            <img
                                class="Thumb"
                                alt="<?php echo esc_attr($gallery->title); ?>"
                                src="<?php echo nextgen_esc_url($gallery->previewurl); ?>"
                            />
                        </a>
                <?php endif ?>
                </div>
                <div class="ngg-description">
                    <p><?php echo_safe_html($gallery->galdesc); ?></p>
                    <?php if (isset($gallery->counter) && $gallery->counter > 0) { ?>
                        <p class="ngg-album-gallery-image-counter"><strong><?php echo $gallery->counter; ?></strong>&nbsp;<?php _e('Photos', 'nggallery'); ?></p>
                    <?php } ?>
                </div>
            </div>
        </div>
    <?php } ?>
    <?php echo $pagination ?>
</div>
<?php $this->end_element(); ?>
