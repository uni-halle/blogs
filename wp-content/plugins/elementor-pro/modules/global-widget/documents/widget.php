<?php
namespace ElementorPro\Modules\GlobalWidget\Documents;

use Elementor\Modules\Library\Documents\Library_Document;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

class Widget extends Library_Document {

	public static function get_properties() {
		$properties = parent::get_properties();

		$properties['show_in_library'] = false;

		return $properties;
	}

	public function get_name() {
		return 'widget';
	}

	public static function get_title() {
		return __( 'Global Widget', 'elementor-pro' );
	}
}
