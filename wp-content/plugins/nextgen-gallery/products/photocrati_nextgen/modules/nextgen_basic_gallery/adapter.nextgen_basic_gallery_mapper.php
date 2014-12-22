<?php

class A_NextGen_Basic_Gallery_Mapper extends Mixin
{
	function initialize()
	{
		$this->object->add_post_hook(
			'set_defaults',
            'NextGen Basic Gallery Defaults',
			'Hook_NextGen_Basic_Gallery_Defaults'
		);
	}
}

/**
 * Sets default values for the NextGen Basic Slideshow display type
 */
class Hook_NextGen_Basic_Gallery_Defaults extends Hook
{
	function set_defaults($entity)
	{
		if (isset($entity->name)) {
			if ($entity->name == NGG_BASIC_SLIDESHOW)
				$this->set_slideshow_defaults($entity);

			else if ($entity->name == NGG_BASIC_THUMBNAILS)
				$this->set_thumbnail_defaults($entity);
		}
	}
    
    function set_slideshow_defaults($entity)
    {
        $settings = C_NextGen_Settings::get_instance();
        $this->object->_set_default_value($entity, 'settings', 'gallery_width', $settings->irWidth);
        $this->object->_set_default_value($entity, 'settings', 'gallery_height', $settings->irHeight);
        $this->object->_set_default_value($entity, 'settings', 'thumbnail_width', $settings->thumbwidth);
        $this->object->_set_default_value($entity, 'settings', 'thumbnail_height', $settings->thumbheight);
        $this->object->_set_default_value($entity, 'settings', 'cycle_interval', $settings->irRotatetime);
        $this->object->_set_default_value($entity, 'settings', 'cycle_effect', $settings->slideFx);
        $this->object->_set_default_value($entity, 'settings', 'effect_code', $settings->thumbCode);
        $this->object->_set_default_value($entity, 'settings', 'show_thumbnail_link', $settings->galShowSlide ? 1 : 0);
        $this->object->_set_default_value($entity, 'settings', 'thumbnail_link_text', $settings->galTextGallery);
        $this->object->_set_default_value($entity, 'settings', 'template', '');

        // Part of the pro-modules
        $this->object->_set_default_value($entity, 'settings', 'ngg_triggers_display', 'never');
    }
    
    
    function set_thumbnail_defaults($entity)
    {
        $settings = C_NextGen_Settings::get_instance();
        $this->object->_set_default_value($entity, 'settings', 'images_per_page', $settings->galImages);
        $this->object->_set_default_value($entity, 'settings', 'number_of_columns', $settings->galColumns);
        $this->object->_set_default_value($entity, 'settings', 'thumbnail_width', $settings->thumbwidth);
        $this->object->_set_default_value($entity, 'settings', 'thumbnail_height', $settings->thumbheight);
        $this->object->_set_default_value($entity, 'settings', 'show_all_in_lightbox', $settings->galHiddenImg);
        $this->object->_set_default_value($entity, 'settings', 'ajax_pagination', $settings->galAjaxNav);
        $this->object->_set_default_value($entity, 'settings', 'use_imagebrowser_effect', $settings->galImgBrowser);
        $this->object->_set_default_value($entity, 'settings', 'template', '');
        $this->object->_set_default_value($entity, 'settings', 'display_no_images_error', 1);

        // TODO: Should this be called enable pagination?
        $this->object->_set_default_value($entity, 'settings', 'disable_pagination', 0);

        // Alternative view support
        $this->object->_set_default_value($entity, 'settings', 'show_slideshow_link', $settings->galShowSlide ? 1 : 0);
        $this->object->_Set_default_value($entity, 'settings', 'slideshow_link_text', $settings->galTextSlide);

        // override thumbnail settings
        $this->object->_set_default_value($entity, 'settings', 'override_thumbnail_settings', 0);
        $this->object->_set_default_value($entity, 'settings', 'thumbnail_quality', '100');
        $this->object->_set_default_value($entity, 'settings', 'thumbnail_crop', 1);
        $this->object->_set_default_value($entity, 'settings', 'thumbnail_watermark', 0);

        // Show piclens link ?
        $this->object->_set_default_value($entity, 'settings', 'piclens_link_text', __('[Show PicLens]', 'nggallery'));
        $this->object->_set_default_value($entity, 'settings', 'show_piclens_link',
            isset($entity->settings['show_piclens_link']) &&
              preg_match("/^true|yes|y$/", $entity->settings['show_piclens_link']) ?
                1 : 0
        );

        // Part of the pro-modules
        $this->object->_set_default_value($entity, 'settings', 'ngg_triggers_display', 'never');
    }
}
