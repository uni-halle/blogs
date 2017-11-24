<?php

namespace WidgetWrangler;

/**
 * Class WidgetWranglerWidgets
 * @package WidgetWrangler
 */
class Widgets {

	/**
	 * Returns all published widgets
	 *
	 * @param array $post_status
	 *
	 * @return array
	 */
	public static function all( $post_status = array('publish') ) {
		$results = get_posts(array(
			'posts_per_page' => -1,
			'post_type' => 'widget',
			'post_status' => $post_status,
		));

		$widgets = array();

		foreach( $results as $widget ) {
			$widgets[ $widget->ID ] = self::get( $widget );
		}

		return $widgets;
	}

	/**
	 * Retrieve and return a single widget by its ID
	 *
	 * @param $widget int | \WP_Post
	 * @param bool $widget_status
	 *
	 * @return object|false
	 */
	public static function get( $widget, $widget_status = false ) {
		// make sure method called with parameter
		if ( empty( $widget ) ) { return false; }

		$widget = get_post( $widget );

		if ($widget && $widget_status && $widget->post_status != $widget_status ) {
			return false;
		}

		if ( $widget ) {
			// do this first so the following get_post_meta queries are pulled from cache
			$widget->widget_meta  = get_post_meta($widget->ID);

			$widget->adv_enabled  = get_post_meta($widget->ID,'ww-adv-enabled',TRUE);
			$widget->adv_template = get_post_meta($widget->ID,'ww-adv-template',TRUE);
			$widget->parse        = get_post_meta($widget->ID,'ww-parse', TRUE);
			$widget->wpautop      = get_post_meta($widget->ID,'ww-wpautop', TRUE);
			$widget->widget_type  = get_post_meta($widget->ID,'ww-widget-type', TRUE);

			if ( empty( $widget->widget_type ) ) {
				$widget->widget_type = "standard";
			}

			// output related variables
			$widget->display_logic_enabled  = get_post_meta($widget->ID,'ww-display-logic-enabled',TRUE);
			$widget->display_logic  = get_post_meta($widget->ID,'ww-display-logic',TRUE);
			$widget->wp_widget_args = array('before_widget' => '', 'before_title' => '', 'after_title' => '', 'after_widget' => '');
			$widget->hide_title = get_post_meta($widget->ID,'ww-hide-title', TRUE);
			$widget->hide_from_wrangler = get_post_meta($widget->ID,'ww-hide-from-wrangler', TRUE);
			$widget->override_output_html = get_post_meta($widget->ID,'ww-override-output-html', TRUE);
			$widget->html = array(
				'wrapper_element' => get_post_meta($widget->ID,'ww-html-wrapper-element', TRUE),
				'wrapper_id'      => get_post_meta($widget->ID,'ww-html-wrapper-id', TRUE),
				'wrapper_classes' => get_post_meta($widget->ID,'ww-html-wrapper-classes', TRUE),
				'title_element'   => get_post_meta($widget->ID,'ww-html-title-element', TRUE),
				'title_classes'   => get_post_meta($widget->ID,'ww-html-title-classes', TRUE),
				'content_element' => get_post_meta($widget->ID,'ww-html-content-element', TRUE),
				'content_classes' => get_post_meta($widget->ID,'ww-html-content-classes', TRUE),
			);

			$widget->custom_template_suggestion = get_post_meta($widget->ID,'ww-custom-template-suggestion', TRUE);

			// clones
			$widget->clone_classname = get_post_meta($widget->ID,'ww-clone-classname', TRUE);
			$widget->clone_instance = get_post_meta($widget->ID,'ww-clone-instance', TRUE);

			$widget->in_preview = FALSE;

			return $widget;
		}

		return false;
	}

	/**
	 * Get a list of widgets to be used as options in a select list.
	 *
	 * @param array $widgets
	 *
	 * @return array Simple key -> value pairs of widget id and widget title.
	 */
	public static function asOptions( $widgets = array() ) {
		$options = array();

		if ( empty( $widgets ) ) {
			$widgets = self::all();
		}

		foreach( $widgets as $id => $widget ) {
			if ( ! $widget->hide_from_wrangler ) {
				$options[ $id ] = $widget->post_title;
			}
		}

		return $options;
	}

}
