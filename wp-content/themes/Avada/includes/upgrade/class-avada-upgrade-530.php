<?php
/**
 * Upgrades Handler.
 *
 * @author     ThemeFusion
 * @copyright  (c) Copyright by ThemeFusion
 * @link       http://theme-fusion.com
 * @package    Avada
 * @subpackage Core
 */

// Do not allow directly accessing this file.
if ( ! defined( 'ABSPATH' ) ) {
	exit( 'Direct script access denied.' );
}

/**
 * Handle migrations for Avada 5.3.0.
 *
 * @since 5.3.0
 */
class Avada_Upgrade_530 extends Avada_Upgrade_Abstract {

	/**
	 * The version.
	 *
	 * @access protected
	 * @since 5.3.0
	 * @var string
	 */
	protected $version = '5.3.0';

	/**
	 * The actual migration process.
	 *
	 * @access protected
	 * @since 5.3.0
	 * @return void
	 */
	protected function migration_process() {

		$this->migrate_nav_typography_settings();
		$this->migrate_mobile_menu_typography_settings();
		$this->migrate_sliding_bar_settings();
		$this->migrate_portfolio_meta_font_size();
		$this->migrate_grid_separator_options();
		$this->migrate_flyout_menu_options();
		$this->migrate_page_title_bar_options();

		add_action( 'init', array( $this, 'migrate_shop_page_options' ) );

	}

	/**
	 * Update nav typography settings.
	 *
	 * @access private
	 * @since 5.3.0
	 * @return void
	 */
	private function migrate_nav_typography_settings() {
		$options = get_option( $this->option_name, array() );

		$options['nav_typography']['font-size'] = $options['nav_font_size'];
		$options['nav_typography']['color']     = $options['menu_first_color'];

		unset( $options['nav_font_size'] );
		unset( $options['menu_first_color'] );

		update_option( $this->option_name, $options );
	}

	/**
	 * Updated mobile nav typography settings.
	 *
	 * @access private
	 * @since 5.3.0
	 * @return void
	 */
	private function migrate_mobile_menu_typography_settings() {
		$options = get_option( $this->option_name, array() );

		if ( isset( $options['body_typography'] ) ) {
			$options['mobile_menu_typography'] = array(
				'font-family'    => $options['body_typography']['font-family'],
				'font-weight'    => $options['body_typography']['font-weight'],
				'letter-spacing' => $options['body_typography']['letter-spacing'],
				'font-size'      => ( isset( $options['mobile_menu_font_size'] ) ) ? $options['mobile_menu_font_size'] : '12px',
				'line-height'    => ( isset( $options['mobile_menu_nav_height'] ) && $options['mobile_menu_nav_height'] ) ? absint( $options['mobile_menu_nav_height'] ) . 'px' : '35px',
				'color'          => ( isset( $options['mobile_menu_font_color'] ) ) ? $options['mobile_menu_font_color'] : '#333333',
			);
		}

		if ( isset( $options['mobile_menu_font_color'] ) ) {
			$options['mobile_menu_font_hover_color'] = $options['mobile_menu_font_color'];
		}

		update_option( $this->option_name, $options );
	}

	/**
	 * Update sliding bar TO settings.
	 *
	 * @access private
	 * @since 5.3.0
	 * @return void
	 */
	private function migrate_sliding_bar_settings() {
		$options = get_option( $this->option_name, array() );

		$options['slidingbar_border'] = $options['slidingbar_top_border'];

		unset( $options['slidingbar_top_border'] );

		$options['slidingbar_sticky'] = 0;

		update_option( $this->option_name, $options );
	}

	/**
	 * Update post meta font size setting.
	 *
	 * @access private
	 * @since 5.3.0
	 * @return void
	 */
	private function migrate_portfolio_meta_font_size() {
		$options = get_option( $this->option_name, array() );

		$options['portfolio_meta_font_size'] = $options['h4_typography']['font-size'];

		update_option( $this->option_name, $options );
	}

	/**
	 * Move shop page options to archives.
	 *
	 * @access private
	 * @since 5.3.0
	 * @return void
	 */
	public function migrate_shop_page_options() {
		if ( class_exists( 'WooCommerce' ) ) {
			$shop_page_id    = get_option( 'woocommerce_shop_page_id' );
			$header_bg_color = '';

			if ( get_post_meta( $shop_page_id, 'pyre_header_bg_color', true ) ) {
				$color_obj = Fusion_Color::new_color( get_post_meta( $shop_page_id, 'pyre_header_bg_color', true ) );
				$mode = 'hex';
				if ( get_post_meta( $shop_page_id, 'pyre_header_bg_opacity', true ) ) {
					$color_obj = $color_obj->getNew( 'alpha', get_post_meta( $shop_page_id, 'pyre_header_bg_opacity', true ) );
					$mode = 'rgba';
				}
				$header_bg_color = $color_obj->to_css( $mode );
			}

			$page_options = array(
				'fusion_tax_heading'       => '',
				'slider_type'              => get_post_meta( $shop_page_id, 'pyre_slider_type', true ),
				'fusion_tax_slider'        => get_post_meta( $shop_page_id, 'pyre_slider', true ),
				'fusion_tax_wooslider'     => get_post_meta( $shop_page_id, 'pyre_wooslider', true ),
				'fusion_tax_revslider'     => get_post_meta( $shop_page_id, 'pyre_revslider', true ),
				'fusion_tax_elasticslider' => get_post_meta( $shop_page_id, 'pyre_revslider', true ),
				'slider_position'          => get_post_meta( $shop_page_id, 'pyre_slider_position', true ),
				'main_padding_top'         => get_post_meta( $shop_page_id, 'pyre_main_top_padding', true ),
				'main_padding_bottom'      => get_post_meta( $shop_page_id, 'pyre_main_bottom_padding', true ),
				'header_bg_color'          => $header_bg_color,
				'page_title_bg'            => get_post_meta( $shop_page_id, 'pyre_page_title_bar_bg', true ),
				'page_title_bg_retina'     => get_post_meta( $shop_page_id, 'pyre_page_title_bar_bg_retina', true ),
				'page_title_height'        => get_post_meta( $shop_page_id, 'pyre_page_title_height', true ),
				'page_title_mobile_height' => get_post_meta( $shop_page_id, 'pyre_page_title_mobile_height', true ),
			);

			$args = array(
				'taxonomy'   => 'product_cat',
				'number'     => 0,
				'hide_empty' => false,
			);

			$product_categories = get_terms( $args );

			foreach ( $product_categories as $category ) {
				if ( isset( $category->term_id ) ) {
					update_term_meta( $category->term_id, 'fusion_taxonomy_options', $page_options );
				}
			}
		} // End if().
	}

	/**
	 * Set the grid element styling options to the new grid box options.
	 *
	 * @access private
	 * @since 5.3.0
	 * @return void
	 */
	private function migrate_grid_separator_options() {
		$options = get_option( $this->option_name, array() );

		if ( isset( $options['separator_style_type'] ) ) {
			$options['grid_separator_style_type'] = $options['separator_style_type'];

			// Make sure single and double are in new format.
			if ( 'single' === $options['grid_separator_style_type'] || 'double' === $options['grid_separator_style_type'] ) {
				$options['grid_separator_style_type'] = $options['grid_separator_style_type'] . '|solid';
			}
		}

		if ( isset( $options['sep_color'] ) ) {
			$options['grid_separator_color'] = $options['sep_color'];
		}

		update_option( $this->option_name, $options );
	}

	/**
	 * Set 'nav_padding' value to new 'flyout_nav_icons_padding' option.
	 *
	 * @access private
	 * @since 5.3.0
	 * @return void
	 */
	private function migrate_flyout_menu_options() {
		$options = get_option( $this->option_name, array() );

		$options['flyout_nav_icons_padding'] = $options['nav_padding'];

		update_option( $this->option_name, $options );
	}

	/**
	 * Update the blog page title bar option.
	 *
	 * @access private
	 * @since 5.3.0
	 * @return void
	 */
	private function migrate_page_title_bar_options() {
		$options = get_option( $this->option_name, array() );

		// Existing blog page option migrate to new format.
		if ( isset( $options['blog_show_page_title_bar'] ) ) {
			if ( 0 === $options['blog_show_page_title_bar'] || '0' === $options['blog_show_page_title_bar'] ) {

				// Was disabled, set to hide.
				$options['blog_show_page_title_bar'] = 'hide';
			} else {

				// Was enabled, inherit from main PTB option.
				$options['blog_show_page_title_bar'] = isset( $options['page_title_bar'] ) ? $options['page_title_bar'] : 'bar_and_content';
			}
		}

		// Copy main PTB option to new blog specific option.
		if ( isset( $options['page_title_bar'] ) ) {
			$options['blog_page_title_bar'] = $options['page_title_bar'];
		}

		update_option( $this->option_name, $options );
	}
}
