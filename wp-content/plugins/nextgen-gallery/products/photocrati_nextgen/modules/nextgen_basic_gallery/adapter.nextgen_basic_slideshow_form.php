<?php

/**
 * Provides the display settings form for the NextGen Basic Slideshow
 */
class A_NextGen_Basic_Slideshow_Form extends Mixin_Display_Type_Form
{
	function get_display_type_name()
	{
		return NGG_BASIC_SLIDESHOW;
	}

    function enqueue_static_resources()
    {
        wp_enqueue_script(
            'nextgen_basic_slideshow_settings-js',
            $this->get_static_url('photocrati-nextgen_basic_gallery#slideshow/nextgen_basic_slideshow_settings.js'),
            array('jquery.nextgen_radio_toggle')
        );
	
	$atp = $this->object->get_registry()->get_utility('I_Attach_To_Post_Controller');
	
	if ($atp != null) {
		$atp->mark_script('nextgen_basic_slideshow_settings-js');
	}
    }

    /**
     * Returns a list of fields to render on the settings page
     */
    function _get_field_names()
    {
        return array(
            'nextgen_basic_slideshow_gallery_dimensions',
            'nextgen_basic_slideshow_cycle_effect',
            'nextgen_basic_slideshow_cycle_interval',
            'nextgen_basic_slideshow_show_thumbnail_link',
            'nextgen_basic_slideshow_thumbnail_link_text'
        );
    }

    function _render_nextgen_basic_slideshow_cycle_interval_field($display_type)
    {
        return $this->_render_number_field(
            $display_type,
            'cycle_interval',
            __('Interval', 'nggallery'),
            $display_type->settings['cycle_interval'],
            '',
            FALSE,
            __('# of seconds', 'nggallery'),
            1
        );
    }

    function _render_nextgen_basic_slideshow_cycle_effect_field($display_type)
    {
        return $this->_render_select_field(
            $display_type,
            'cycle_effect',
            'Effect',
			array(
			'fade' => 'fade',
			'blindX' => 'blindX',
			'cover' => 'cover',
			'scrollUp' => 'scrollUp',
			'scrollDown' => 'scrollDown',
			'shuffle' => 'shuffle',
			'toss' => 'toss',
			'wipe' => 'wipe'
			),
            $display_type->settings['cycle_effect'],
            '',
            FALSE
        );
    }

    function _render_nextgen_basic_slideshow_gallery_dimensions_field($display_type)
    {
        return $this->render_partial('photocrati-nextgen_basic_gallery#slideshow/nextgen_basic_slideshow_settings_gallery_dimensions', array(
            'display_type_name' => $display_type->name,
            'gallery_dimensions_label' => __('Maximum dimensions', 'nggallery'),
            'gallery_dimensions_tooltip' => __('Certain themes may allow images to flow over their container if this setting is too large', 'nggallery'),
            'gallery_width' => $display_type->settings['gallery_width'],
            'gallery_height' => $display_type->settings['gallery_height'],
        ), True);
    }

    /**
     * Renders the show_thumbnail_link settings field
     *
     * @param C_Display_Type $display_type
     * @return string
     */
    function _render_nextgen_basic_slideshow_show_thumbnail_link_field($display_type)
    {
        return $this->_render_radio_field(
            $display_type,
            'show_thumbnail_link',
            __('Show thumbnail link', 'nggallery'),
            $display_type->settings['show_thumbnail_link']
        );
    }

    /**
     * Renders the thumbnail_link_text settings field
     *
     * @param C_Display_Type $display_type
     * @return string
     */
    function _render_nextgen_basic_slideshow_thumbnail_link_text_field($display_type)
    {
        return $this->_render_text_field(
            $display_type,
            'thumbnail_link_text',
            __('Thumbnail link text', 'nggallery'),
            $display_type->settings['thumbnail_link_text'],
            '',
            !empty($display_type->settings['show_thumbnail_link']) ? FALSE : TRUE
        );
    }
}
