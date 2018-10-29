<?php
namespace ElementorPro\Modules\Woocommerce\Widgets;

use Elementor\Controls_Manager;
use ElementorPro\Modules\QueryControl\Controls\Group_Control_Posts;
use ElementorPro\Modules\QueryControl\Module;
use ElementorPro\Modules\Woocommerce\Classes\Products_Renderer;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

class Products extends Archive_Products {

	public function get_name() {
		return 'woocommerce-products';
	}

	public function get_title() {
		return __( 'Products', 'elementor-pro' );
	}

	public function get_categories() {
		return [
			'woocommerce-elements',
		];
	}

	protected function _register_controls() {
		parent::_register_controls();

		$this->update_control(
			'query_post_type',
			[
				'default' => 'product',
			],
			[
				'recursive' => true,
			]
		);

		$this->update_control(
			'rows',
			[
				'default' => 1,
			],
			[
				'recursive' => true,
			]
		);
	}
}
