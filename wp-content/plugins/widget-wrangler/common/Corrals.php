<?php

namespace WidgetWrangler;

/**
 * Class Corrals
 * @package WidgetWrangler
 */
class Corrals {

	/**
	 * All corrals in the system.
	 *
	 * @return array
	 */
	public static function all() {
		$corrals = get_option('ww_sidebars', array());
		return maybe_unserialize($corrals);
	}

	/**
	 * Get a single corral name by slug.
	 *
	 * @param $slug
	 *
	 * @return string
	 */
	public static function get( $slug ) {
		$all = self::all();
		$corral = '';

		if ( !empty( $all[$slug] ) ) {
			$corral = $all[$slug];
		}

		return $corral;
	}

	/**
	 * Reorder the corrals
	 *
	 * @param array $new_order
	 */
	public static function reorder($new_order = array()) {
		$all = self::all();
		$reordered = array();

		if ( is_array( $new_order ) ) {
			$i = 1;
			while( $i <= count($new_order ) ) {
				$reordered[ $new_order[ $i ] ] = $all[ $new_order[ $i ] ];
				$i++;
			}

			self::save( $reordered );
		}
	}

	/**
	 * Add a new corral
	 *
	 * @param $name string
	 * @param null $slug
	 */
	public static function add( $name, $slug = null ) {
		if ( !$slug ) {
			$slug = Utils::makeSlug( $name );
		}

		$all = self::all();
		$all[ $slug ] = sanitize_text_field( $name );
		self::save( $all );
	}

	/**
	 * Remove a specific corral by slug
	 *
	 * @param $slug string
	 */
	public static function remove( $slug ) {
		$all = self::all();

		if ( isset( $all[ $slug ] ) ) {
			unset( $all[ $slug ] );
			self::save( $all );
		}
	}

	/**
	 * Update/Edit a sidebar
	 *
	 * @param $old_slug
	 * @param $slug
	 * @param $name
	 */
	public static function update( $old_slug, $name, $slug = null ) {
		self::remove( $old_slug );
		self::add( $name, $slug );
	}

	/**
	 * Save provided corrals
	 *
	 * @param array $corrals
	 */
	public static function save( $corrals = array() ) {
		update_option('ww_sidebars', $corrals );
	}

}
