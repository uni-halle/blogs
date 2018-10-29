<?php
namespace ElementorPro\Modules\Woocommerce\Widgets;

use Elementor\Controls_Manager;
use Elementor\Group_Control_Typography;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

class Product_Additional_Information extends Widget_Base {

	public function get_name() {
		return 'woocommerce-product-additional-information';
	}

	public function get_title() {
		return __( 'Additional Information', 'elementor-pro' );
	}

	public function get_icon() {
		return ' eicon-product-info';
	}


	protected function render() {
		global $product;
		$product = wc_get_product();

		if ( empty( $product ) ) {
			return;
		}

		wc_get_template( 'single-product/tabs/additional-information.php' );
	}

	public function render_plain_content() {}
}
