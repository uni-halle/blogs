<?php
namespace ElementorPro\Modules\Library;

use ElementorPro\Base\Module_Base;
use ElementorPro\Modules\Library\Classes\Shortcode;
use ElementorPro\Plugin;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

class Module extends Module_Base {

	public function get_widgets() {
		return [
			'Template',
		];
	}

	public function __construct() {
		parent::__construct();

		$this->add_filters();
		$this->add_actions();

		new Shortcode();
	}

	public function get_name() {
		return 'library';
	}

	public function register_wp_widgets() {
		register_widget( 'ElementorPro\Modules\Library\WP_Widgets\Elementor_Library' );
	}

	public function localize_settings( $settings ) {
		$settings = array_replace_recursive( $settings, [
			'i18n' => [
				'home_url' => home_url(),
				'edit_template' => __( 'Edit Template', 'elementor-pro' ),
			],
		] );

		return $settings;
	}

	public function add_actions() {
		add_action( 'widgets_init', [ $this, 'register_wp_widgets' ] );
	}

	public function add_filters() {
		add_filter( 'elementor_pro/editor/localize_settings', [ $this, 'localize_settings' ] );
		add_filter( 'elementor_pro/admin/localize_settings', [ $this, 'localize_settings' ] ); // For WordPress Widgets and Customizer

		add_filter( 'elementor/widgets/black_list', function( $black_list ) {
			$black_list[] = 'ElementorPro\Modules\Library\WP_Widgets\Elementor_Library';

			return $black_list;
		} );
	}

	public static function get_templates() {
		return Plugin::elementor()->templates_manager->get_source( 'local' )->get_items();
	}

	public static function empty_templates_message() {
		return '<div id="elementor-widget-template-empty-templates">
				<div class="elementor-widget-template-empty-templates-icon"><i class="eicon-nerd"></i></div>
				<div class="elementor-widget-template-empty-templates-title">' . __( 'You Haven’t Saved Templates Yet.', 'elementor-pro' ) . '</div>
				<div class="elementor-widget-template-empty-templates-footer">' . __( 'Want to learn more about Elementor library?', 'elementor-pro' ) . ' <a class="elementor-widget-template-empty-templates-footer-url" href="https://go.elementor.com/docs-library/" target="_blank">' . __( 'Click Here', 'elementor-pro' ) . '</a>
				</div>
				</div>';
	}
}
