<?php
namespace ElementorPro\Modules\ThemeBuilder\Documents;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

class Footer extends Theme_Section_Document {

	public static function get_properties() {
		$properties = parent::get_properties();

		$properties['location'] = 'footer';

		return $properties;
	}

	public function get_name() {
		return 'footer';
	}

	public static function get_title() {
		return __( 'Footer', 'elementor-pro' );
	}

	protected static function get_editor_panel_categories() {
		// Move to top as active.
		$categories = [
			'theme-elements' => [
				'title' => __( 'Site', 'elementor-pro' ),
				'active' => true,
			],
		];

		return $categories + parent::get_editor_panel_categories();
	}
}
