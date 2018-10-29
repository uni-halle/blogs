<?php
namespace ElementorPro\Modules\CustomCss;

use Elementor\Controls_Manager;
use Elementor\Controls_Stack;
use Elementor\Core\Files\CSS\Post;
use Elementor\Element_Base;
use Elementor\Element_Column;
use Elementor\Element_Section;
use Elementor\Widget_Base;
use ElementorPro\Base\Module_Base;
use ElementorPro\Plugin;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

class Module extends Module_Base {

	public function __construct() {
		parent::__construct();

		$this->add_actions();
	}

	public function get_name() {
		return 'custom-css';
	}

	/**
	 * @param $element    Controls_Stack
	 * @param $section_id string
	 */
	public function register_controls( Controls_Stack $element, $section_id ) {
		// Remove Custom CSS Banner (From free version)
		if ( 'section_custom_css_pro' === $section_id ) {
			$this->remove_go_pro_custom_css( $element );
		}

		if ( $element instanceof Element_Section || $element instanceof Widget_Base ) {
			$required_section_id = '_section_responsive';
		} elseif ( $element instanceof Element_Column ) {
			$required_section_id = 'section_advanced';
		} else {
			$required_section_id = 'section_page_style';
		}

		if ( $required_section_id !== $section_id ) {
			return;
		}

		$element->start_controls_section(
			'section_custom_css',
			[
				'label' => __( 'Custom CSS', 'elementor-pro' ),
				'tab' => 'section_page_style' === $section_id ? Controls_Manager::TAB_STYLE : Controls_Manager::TAB_ADVANCED,
			]
		);

		$element->add_control(
			'custom_css_title',
			[
				'raw' => __( 'Add your own custom CSS here', 'elementor-pro' ),
				'type' => Controls_Manager::RAW_HTML,
			]
		);

		$element->add_control(
			'custom_css',
			[
				'type' => Controls_Manager::CODE,
				'label' => __( 'Custom CSS', 'elementor-pro' ),
				'language' => 'css',
				'render_type' => 'ui',
				'show_label' => false,
				'separator' => 'none',
			]
		);

		$element->add_control(
			'custom_css_description',
			[
				'raw' => __( 'Use "selector" to target wrapper element. Examples:<br>selector {color: red;} // For main element<br>selector .child-element {margin: 10px;} // For child element<br>.my-class {text-align: center;} // Or use any custom selector', 'elementor-pro' ),
				'type' => Controls_Manager::RAW_HTML,
				'content_classes' => 'elementor-descriptor',
			]
		);

		$element->end_controls_section();
	}

	/**
	 * @param $post_css Post
	 * @param $element  Element_Base
	 */
	public function add_post_css( $post_css, $element ) {
		$element_settings = $element->get_settings();

		if ( empty( $element_settings['custom_css'] ) ) {
			return;
		}

		$css = trim( $element_settings['custom_css'] );

		if ( empty( $css ) ) {
			return;
		}
		$css = str_replace( 'selector', $post_css->get_element_unique_selector( $element ), $css );

		// Add a css comment
		$css = sprintf( '/* Start custom CSS for %s, class: %s */', $element->get_name(), $element->get_unique_selector() ) . $css . '/* End custom CSS */';

		$post_css->get_stylesheet()->add_raw_css( $css );
	}

	/**
	 * @param $post_css Post
	 */
	public function add_page_settings_css( $post_css ) {
		$document = Plugin::elementor()->documents->get( $post_css->get_post_id() );
		$custom_css = $document->get_settings( 'custom_css' );

		$custom_css = trim( $custom_css );

		if ( empty( $custom_css ) ) {
			return;
		}

		$custom_css = str_replace( 'selector', 'body.elementor-page-' . $post_css->get_post_id(), $custom_css );

		// Add a css comment
		$custom_css = '/* Start custom CSS for page-settings */' . $custom_css . '/* End custom CSS */';

		$post_css->get_stylesheet()->add_raw_css( $custom_css );
	}

	/**
	 * @param $element Element_Base
	 */
	public function remove_go_pro_custom_css( $element ) {
		$controls_to_remove = [ 'section_custom_css_pro', 'custom_css_pro' ];

		Plugin::elementor()->controls_manager->remove_control_from_stack( $element->get_unique_name(), $controls_to_remove );
	}

	protected function add_actions() {
		add_action( 'elementor/element/after_section_end', [ $this, 'register_controls' ], 10, 2 );
		add_action( 'elementor/element/parse_css', [ $this, 'add_post_css' ], 10, 2 );
		add_action( 'elementor/post-css-file/parse', [ $this, 'add_page_settings_css' ] );
	}
}
