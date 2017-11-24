<?php

namespace WidgetWrangler;

/**
 * Class Context
 * @package WidgetWrangler
 */
class Context {

	/**
	 * Apply filter so all addons can help find the appropriate page_widgets
	 *
	 * @return array|null
	 */
	public static function pageWidgets() {
		$context = self::context();
		$widgets = apply_filters( 'widget_wrangler_find_all_page_widgets', NULL, $context );

		if ( empty( $widgets ) ) {
			$widgets = $context['widgets'];
		}

		return $widgets;
	}

	/**
	 * Provide some useful information to help determine the current page and
	 * its Widget Wrangler data.
	 *
	 * @return array
	 */
	public static function context() {
		$context = array(
			'id'       => NULL,
			'post'     => NULL,
			'preset'   => NULL,
			'term'     => NULL,
			'taxonomy' => NULL,
			'widgets'  => NULL,
		);

		// Admin page for presets
		if ( is_admin() && isset( $_REQUEST['preset_id'] ) ) {
			$context['preset'] = Presets::get( $_REQUEST['preset_id'] );
			$context['widgets'] = $context['preset']->widgets;

			return $context;
		}

		// Single page/post: admin & frontend
		$post = get_post();
		if ( $post ) {
			$context = array_replace( $context, self::getSingleContext( $post->ID ) );
		}

		// Taxonomy &/ term: admin
		if ( is_admin() && ( ! empty( $_GET['taxonomy'] ) ) ) {
			$context = array_replace( $context, self::getTaxonomyContext( $_GET['taxonomy'] ) );

			// term
			if ( ! empty( $_GET['tag_ID'] ) ) {
				$context = array_replace( $context, self::getTermContext( $_GET['tag_ID'] ) );
			}
		}

		// Taxonomy &/ term: frontend
		if ( ( is_tax() || is_category() || is_tag() ) && $term = get_queried_object() ) {
			$context = array_replace( $context, self::getTermContext( $term->term_id ) );
		}

		// Postspage: blog home, not "is_front()"
		if ( is_home() ) {
			$context['preset'] = Presets::getCore( 'postspage' );
			$context['widgets'] = $context['preset']->widgets;
		}

		// Default core widgets
		if ( empty( $context['widgets'] ) ) {
			$context['preset'] = Presets::getCore( 'default' );
			$context['widgets'] = $context['preset']->widgets;
		}

		return $context;
	}

	/**
	 * Get the preset and widgets for a single post.
	 *
	 * @param $post_id
	 *
	 * @return array
	 */
	public static function getSingleContext( $post_id ) {
		$context = array();

		if ( Settings::instance()->isEnabledPostType( get_post_type( $post_id ) ) ) {
			$context['post'] = get_post( $post_id );
			$context['id'] = $context['post']->ID;
			$context['widgets'] = Utils::getPostWidgets( $post_id );

			$preset_id = get_post_meta( $post_id, 'ww_post_preset_id', TRUE );

			if ( ! empty( $preset_id ) ) {
				$context['preset'] = Presets::get( $preset_id );
				$context['widgets'] = $context['preset']->widgets;
			}
		}

		return $context;
	}

	/**
	 * Get the preset and widgets for a taxonomy term page.
	 *
	 * @param $term_id
	 *
	 * @return array
	 */
	public static function getTermContext( $term_id ) {
		$context = array();
		$term = get_term( $term_id );

		if ( $term && Settings::instance()->isEnabledTaxonomy( $term->taxonomy ) ) {
			$context['term'] = $term;
			$context['id'] = $term->term_id;

			$extra = Extras::get( array(
				'type' => 'taxonomy',
				'variety' => 'term',
				'extra_key' => $term->term_id,
			) );

			// see if this term has widgets set
			if ( $extra ) {
				$context['widgets'] = $extra->widgets;

				if ( ! empty( $extra->data['preset_id'] ) ) {
					$context['preset'] = Presets::get( $extra->data['preset_id'] );
					$context['widgets'] = $context['preset']->widgets;
				}
			} // otherwise see if the taxonomy is overriding
			else {
				$context = self::getTaxonomyContext( $term->taxonomy );
			}
		}

		return $context;
	}

	/**
	 * Get the preset and widgets about a given taxonomy.
	 *
	 * @param $taxonomy
	 *
	 * @return array
	 */
	public static function getTaxonomyContext( $taxonomy ) {
		$context = array();

		if ( Settings::instance()->isEnabledTaxonomy( $taxonomy ) ) {
			$context['taxonomy'] = get_taxonomy( $taxonomy );
			$context['id'] = $taxonomy;

			$extra = Extras::get( array(
				'type' => 'taxonomy',
				'variety' => 'taxonomy',
				'extra_key' => $taxonomy,
			) );

			if ( $extra && isset( $extra->data['override_default'] ) ) {
				$context['widgets'] = $extra->widgets;

				if ( ! empty( $extra->data['preset_id'] ) ) {
					$context['preset'] = Presets::get( $extra->data['preset_id'] );
					$context['widgets'] = $context['preset']->widgets;
				}
			}
		}

		return $context;
	}

}
