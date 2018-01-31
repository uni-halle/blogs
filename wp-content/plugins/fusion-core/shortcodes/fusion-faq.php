<?php
/**
 * Fusion-Builder Shortcode Element.
 *
 * @package Fusion-Core
 * @since 3.1.0
 */

if ( function_exists( 'fusion_is_element_enabled' ) && fusion_is_element_enabled( 'fusion_faq' ) ) {

	if ( ! class_exists( 'FusionSC_Faq' ) && class_exists( 'Fusion_Element' ) ) {
		/**
		 * Shortcode class.
		 *
		 * @package fusion-core
		 * @since 1.0
		 */
		class FusionSC_Faq extends Fusion_Element {

			/**
			 * FAQ counter.
			 *
			 * @access private
			 * @since 1.0
			 * @var int
			 */
			private $faq_counter = 1;

			/**
			 * An array of the shortcode arguments.
			 *
			 * @static
			 * @access public
			 * @since 1.0
			 * @var array
			 */
			public static $args;

			/**
			 * Constructor.
			 *
			 * @access public
			 * @since 1.0
			 */
			public function __construct() {
				parent::__construct();
				add_shortcode( 'fusion_faq', array( $this, 'render' ) );
			}

			/**
			 * Render the shortcode
			 *
			 * @access public
			 * @since 1.0
			 * @param  array  $args    Shortcode parameters.
			 * @param  string $content Content between shortcode.
			 * @return string          HTML output.
			 */
			public function render( $args, $content = '' ) {

				global $fusion_settings, $fusion_library;

				$defaults = FusionBuilder::set_shortcode_defaults(
					array(
						'hide_on_mobile'   => fusion_builder_default_visibility( 'string' ),
						'class'            => '',
						'id'               => '',
						'cats_slug'        => '',
						'exclude_cats'     => '',
						'order'            => 'DESC',
						'orderby'          => 'date',
						'featured_image'   => $fusion_settings->get( 'faq_featured_image' ),
						'filters'          => $fusion_settings->get( 'faq_filters' ),
						'type'             => ( '' !== $fusion_settings->get( 'faq_accordion_type' ) ) ? $fusion_settings->get( 'faq_accordion_type' ) : 'accordions',
						'boxed_mode'       => ( '' !== $fusion_settings->get( 'faq_accordion_boxed_mode' ) ) ? $fusion_settings->get( 'faq_accordion_boxed_mode' ) : 'no',
						'border_size'      => intval( $fusion_settings->get( 'faq_accordion_border_size' ) ) . 'px',
						'border_color'     => ( '' !== $fusion_settings->get( 'faq_accordian_border_color' ) ) ? $fusion_settings->get( 'faq_accordian_border_color' ) : '#cccccc',
						'background_color' => ( '' !== $fusion_settings->get( 'faq_accordian_background_color' ) ) ? $fusion_settings->get( 'faq_accordian_background_color' ) : '#ffffff',
						'hover_color'      => ( '' !== $fusion_settings->get( 'faq_accordian_hover_color' ) ) ? $fusion_settings->get( 'faq_accordian_hover_color' ) : $fusion_library->sanitize->color( $fusion_settings->get( 'primary_color' ) ),
						'divider_line'     => $fusion_settings->get( 'faq_accordion_divider_line' ),
						'icon_size'        => ( '' !== $fusion_settings->get( 'faq_accordion_icon_size' ) ) ? $fusion_settings->get( 'faq_accordion_icon_size' ) : '13px',
						'icon_color'       => ( '' !== $fusion_settings->get( 'faq_accordian_icon_color' ) ) ? $fusion_settings->get( 'faq_accordian_icon_color' ) : '#ffffff',
						'icon_boxed_mode'  => ( '' !== $fusion_settings->get( 'faq_accordion_icon_boxed' ) ) ? $fusion_settings->get( 'faq_accordion_icon_boxed' ) : 'no',
						'icon_alignment'   => ( '' !== $fusion_settings->get( 'faq_accordion_icon_align' ) ) ? $fusion_settings->get( 'faq_accordion_icon_align' ) : 'left',
					), $args
				);

				$defaults['border_size'] = FusionBuilder::validate_shortcode_attr_value( $defaults['border_size'], 'px' );
				$defaults['icon_size']   = FusionBuilder::validate_shortcode_attr_value( $defaults['icon_size'], 'px' );
				$defaults['cat_slugs']   = $defaults['cats_slug'];

				// @codingStandardsIgnoreLine
				extract( $defaults );

				self::$args = $defaults;

				$style_tag = $styles = '';

				if ( '1' == self::$args['boxed_mode'] || 'yes' === self::$args['boxed_mode'] ) {

					if ( ! empty( self::$args['hover_color'] ) ) {
						$styles .= '#accordian-' . $this->faq_counter . ' .fusion-panel:hover{ background-color: ' . self::$args['hover_color'] . ' }';
					}

					$styles .= ' #accordian-' . $this->faq_counter . ' .fusion-panel {';

					if ( ! empty( self::$args['border_color'] ) ) {
						$styles .= ' border-color:' . self::$args['border_color'] . ';';
					}

					if ( ! empty( self::$args['border_size'] ) ) {
						$styles .= ' border-width:' . self::$args['border_size'] . ';';
					}

					if ( ! empty( self::$args['background_color'] ) ) {
						$styles .= ' background-color:' . self::$args['background_color'] . ';';
					}

					$styles .= ' }';
				}

				if ( ! empty( self::$args['icon_size'] ) ) {
					$styles .= '.fusion-accordian  #accordian-' . $this->faq_counter . ' .panel-title a .fa-fusion-box:before{ font-size: ' . self::$args['icon_size'] . ';}';
				}

				if ( ! empty( self::$args['icon_color'] ) ) {
					$styles .= '.fusion-accordian  #accordian-' . $this->faq_counter . ' .panel-title a .fa-fusion-box{ color: ' . self::$args['icon_color'] . ';}';
				}

				if ( ! empty( self::$args['icon_alignment'] ) && 'right' === self::$args['icon_alignment'] ) {
					$styles .= '.fusion-accordian  #accordian-' . $this->faq_counter . '.fusion-toggle-icon-right .fusion-toggle-heading{ margin-right: ' . FusionBuilder::validate_shortcode_attr_value( intval( self::$args['icon_size'] ) + 18, 'px' ) . ';}';
				}

				if ( $styles ) {

					$style_tag = '<style type="text/css" scoped="scoped">' . $styles . '</style>';

				}

				// Transform $cat_slugs to array.
				if ( $cat_slugs ) {
					$cat_slugs = preg_replace( '/\s+/', '', $cat_slugs );
					$cat_slugs = explode( ',', $cat_slugs );
				} else {
					$cat_slugs = array();
				}

				// Transform $cats_to_exclude to array.
				if ( $exclude_cats ) {
					$cats_to_exclude = preg_replace( '/\s+/', '', $exclude_cats );
					$cats_to_exclude = explode( ',' , $cats_to_exclude );
				} else {
					$cats_to_exclude = array();
				}

				// Initialize the query array.
				$args = array(
					'post_type'      => 'avada_faq',
					'posts_per_page' => -1,
					'has_password'   => false,
					'orderby'        => $orderby,
					'order'          => $order,
				);

				// Check if the are categories that should be excluded.
				if ( ! empty( $cats_to_exclude ) ) {

					// Exclude the correct cats from tax_query.
					$args['tax_query'] = array(
						array(
							'taxonomy' => 'faq_category',
							'field'    => 'slug',
							'terms'    => $cats_to_exclude,
							'operator' => 'NOT IN',
						),
					);

					// Include the correct cats in tax_query.
					if ( ! empty( $cat_slugs ) ) {
						$args['tax_query']['relation'] = 'AND';
						$args['tax_query'][]           = array(
							'taxonomy' => 'faq_category',
							'field'    => 'slug',
							'terms'    => $cat_slugs,
							'operator' => 'IN',
						);
					}
				} else {
					// Include the cats from $cat_slugs in tax_query.
					if ( ! empty( $cat_slugs ) ) {
						$args['tax_query'] = array(
							array(
								'taxonomy' => 'faq_category',
								'field'    => 'slug',
								'terms'    => $cat_slugs,
							),
						);
					}
				}

				$class = fusion_builder_visibility_atts( $hide_on_mobile, $class );

				$html  = $style_tag;
				$html .= '<div class="fusion-faq-shortcode ' . $class . '">';

				// Setup the filters.
				$faq_terms = get_terms( 'faq_category' );

				// Check if we should display filters.
				if ( $faq_terms && 'no' !== $filters ) {

					$html .= '<ul class="fusion-filters clearfix">';

					// Check if the "All" filter should be displayed.
					$first_filter = true;
					if ( 'yes' === $filters ) {
						$html .= '<li class="fusion-filter fusion-filter-all fusion-active">';
						$html .= '<a data-filter="*" href="#">' . apply_filters( 'fusion_faq_all_filter_name', esc_html__( 'All', 'fusion-core' ) ) . '</a>';
						$html .= '</li>';
						$first_filter = false;
					}

					// Loop through the terms to setup all filters.
					foreach ( $faq_terms as $faq_term ) {
						// Only display filters of non excluded categories.
						if ( ! in_array( $faq_term->slug, $cats_to_exclude, true ) ) {
							// Check if current term is part of chosen terms, or if no terms at all have been chosen.
							if ( ( ! empty( $cat_slugs ) && in_array( $faq_term->slug, $cat_slugs, true ) ) || empty( $cat_slugs ) ) {
								// If the "All" filter is disabled, set the first real filter as active.
								if ( $first_filter ) {
									$html .= '<li class="fusion-filter fusion-active">';
									$html .= '<a data-filter=".' . urldecode( $faq_term->slug ) . '" href="#">' . $faq_term->name . '</a>';
									$html .= '</li>';
									$first_filter = false;
								} else {
									$html .= '<li class="fusion-filter fusion-hidden">';
									$html .= '<a data-filter=".' . urldecode( $faq_term->slug ) . '" href="#">' . $faq_term->name . '</a>';
									$html .= '</li>';
								}
							}
						}
					}

					$html .= '</ul>';
				} // End if().

				// Setup the posts.
				$faq_items = fusion_cached_query( $args );

				if ( ! $faq_items->have_posts() ) {
					return fusion_builder_placeholder( 'avada_faq', 'FAQ posts' );
				}

				$wrapper_classes = '';

				if ( 'right' == self::$args['icon_alignment'] ) {
					$wrapper_classes .= ' fusion-toggle-icon-right';
				}

				if ( '0' == self::$args['icon_boxed_mode'] || 'no' === self::$args['icon_boxed_mode'] ) {
					$wrapper_classes .= ' fusion-toggle-icon-unboxed';
				}

				$html .= '<div class="fusion-faqs-wrapper">';
				$html .= '<div class="accordian fusion-accordian">';
				$html .= '<div class="panel-group ' . $wrapper_classes . '" id="accordian-' . $this->faq_counter . '">';

				$this_post_id = get_the_ID();

				while ( $faq_items->have_posts() ) :
					$faq_items->the_post();

					// If used on a faq item itself, thzis is needed to prevent an infinite loop.
					if ( get_the_ID() === $this_post_id ) {
						continue;
					}

					// Get all terms of the post and it as classes; needed for filtering.
					$post_classes = '';
					$item_classes = '';
					$post_id = get_the_ID();
					$post_terms = get_the_terms( $post_id, 'faq_category' );
					if ( $post_terms ) {
						foreach ( $post_terms as $post_term ) {
							$post_classes .= urldecode( $post_term->slug ) . ' ';
						}
					}

					if ( '1' == self::$args['boxed_mode'] || 'yes' === self::$args['boxed_mode'] ) {
						$item_classes .= ' fusion-toggle-no-divider fusion-toggle-boxed-mode';
					} elseif ( '0' == self::$args['divider_line'] || 'no' === self::$args['divider_line'] ) {
						$item_classes .= ' fusion-toggle-no-divider';
					}

					$html .= '<div class="fusion-panel' . $item_classes . ' panel-default fusion-faq-post ' . $post_classes . '">';
					// Get the rich snippets for the post.
					$html .= avada_render_rich_snippets_for_pages();

					$html .= '<div class="panel-heading">';
					$html .= '<h4 class="panel-title toggle">';
					if ( 'toggles' === self::$args['type'] ) {
						$html .= '<a data-toggle="collapse" class="collapsed" data-target="#collapse-' . $this->faq_counter . '-' . $post_id . '" href="#collapse-' . $this->faq_counter . '-' . $post_id . '">';
					} else {
						$html .= '<a data-toggle="collapse" class="collapsed" data-parent="#accordian-' . $this->faq_counter . '" data-target="#collapse-' . $this->faq_counter . '-' . $post_id . '" href="#collapse-' . $this->faq_counter . '-' . $post_id . '">';
					}

					$html .= '<div class="fusion-toggle-icon-wrapper"><div class="fusion-toggle-icon-wrapper-main"><div class="fusion-toggle-icon-wrapper-sub"><i class="fa-fusion-box"></i></div></div></div>';
					$html .= '<div class="fusion-toggle-heading">' . get_the_title() . '</div>';
					$html .= '</a>';
					$html .= '</h4>';
					$html .= '</div>';

					$html .= '<div id="collapse-' . $this->faq_counter . '-' . $post_id . '" class="panel-collapse collapse">';
					$html .= '<div class="panel-body toggle-content post-content">';

					// Render the featured image of the post.
					if ( ( '1' === $featured_image || 'yes' === $featured_image ) && has_post_thumbnail() ) {
						$featured_image_src = wp_get_attachment_image_src( get_post_thumbnail_id(), 'full' );

						if ( $featured_image_src[0] ) {
							$html .= '<div class="fusion-flexslider flexslider fusion-flexslider-loading post-slideshow fusion-post-slideshow">';
							$html .= '<ul class="slides">';
							$html .= '<li>';
							$html .= '<a href="' . $featured_image_src[0] . '" data-rel="iLightbox[gallery]" data-title="' . get_post_field( 'post_title', get_post_thumbnail_id() ) . '" data-caption="' . get_post_field( 'post_excerpt', get_post_thumbnail_id() ) . '">';
							$html .= '<span class="screen-reader-text">' . esc_attr__( 'View Larger Image', 'fusion-core' ) . '</span>';
							$html .= get_the_post_thumbnail( $post_id, 'blog-large' );
							$html .= '</a>';
							$html .= '</li>';
							$html .= '</ul>';
							$html .= '</div>';
						}
					}
					ob_start();
					the_content();
					$html .= ob_get_clean();
					$html .= '</div>';
					$html .= '</div>';
					$html .= '</div>';

				endwhile; // Loop through faq_items.
				wp_reset_postdata();

				$html .= '</div>';
				$html .= '</div>';
				$html .= '</div>';

				$html .= '</div>';

				$this->faq_counter++;

				return $html;

			}

			/**
			 * Gets the query arguments.
			 *
			 * @access private
			 * @since 1.0
			 * @param array $term_slugs       The term slugs.
			 * @param array $terms_to_exclude The terms we wish to exclude.
			 */
			private function get_query_args( $term_slugs, $terms_to_exclude ) {

			}

			/**
			 * Adds settings to element options panel.
			 *
			 * @access public
			 * @since 1.1
			 * @return array $sections FAQ settings.
			 */
			public function add_options() {

				global $fusion_settings, $fusion_library;

				if ( ! class_exists( 'Fusion_Settings' ) ) {
					return;
				}

				$option_name = Fusion_Settings::get_option_name();

				return array(
					'faq_shortcode_section' => array(
						'label'       => esc_html__( 'FAQ Element', 'fusion-core' ),
						'description' => '',
						'id'          => 'faq_shortcode_section',
						'type'        => 'sub-section',
						'fields'      => array(
							'faq_featured_image' => array(
								'label'       => esc_html__( 'FAQ Featured Images', 'fusion-core' ),
								'description' => esc_html__( 'Turn on to display featured images.', 'fusion-core' ),
								'id'          => 'faq_featured_image',
								'default'     => '0',
								'type'        => 'switch',
								'option_name' => $option_name,
							),
							'faq_filters' => array(
								'label'       => esc_html__( 'FAQ Filters', 'fusion-core' ),
								'description' => esc_html__( 'Controls how the filters display for FAQs.', 'fusion-core' ),
								'id'          => 'faq_filters',
								'default'     => 'yes',
								'type'        => 'radio-buttonset',
								'choices'     => array(
									'yes'             => esc_html__( 'Show', 'fusion-core' ),
									'yes_without_all' => esc_html__( 'Show without "All"', 'fusion-core' ),
									'no'              => esc_html__( 'Hide', 'fusion-core' ),
								),
								'option_name' => $option_name,
							),
							'faq_accordion_type' => array(
								'label'       => esc_html__( 'FAQs in Toggles or Accordions', 'fusion-builder' ),
								'description' => esc_html__( 'Toggles allow several items to be open at a time. Accordions only allow one item to be open at a time.', 'fusion-builder' ),
								'id'          => 'faq_accordion_type',
								'default'     => 'accordions',
								'type'        => 'radio-buttonset',
								'choices'     => array(
									'toggles'    => esc_html__( 'Toggles', 'fusion-builder' ),
									'accordions' => esc_html__( 'Accordions', 'fusion-builder' ),
								),
							),
							'faq_accordion_boxed_mode' => array(
								'label'       => esc_html__( 'FAQ Items in Boxed Mode', 'fusion-builder' ),
								'description' => esc_html__( 'Turn on to display items in boxed mode. FAQ Item divider line must be disabled for this option to work.', 'fusion-builder' ),
								'id'          => 'faq_accordion_boxed_mode',
								'default'     => '0',
								'type'        => 'switch',
							),
							'faq_accordion_border_size' => array(
								'label'       => esc_html__( 'FAQ Item Boxed Mode Border Width', 'fusion-builder' ),
								'description' => esc_html__( 'Controls the border size of the FAQ item.', 'fusion-builder' ),
								'id'          => 'faq_accordion_border_size',
								'default'     => '1',
								'type'        => 'slider',
								'required'    => array(
									array(
										'setting'  => 'faq_accordion_boxed_mode',
										'operator' => '!=',
										'value'    => '0',
									),
								),
								'choices'     => array(
									'min'  => '0',
									'max'  => '20',
									'step' => '1',
								),
							),
							'faq_accordian_border_color' => array(
								'label'       => esc_html__( 'FAQ Item Boxed Mode Border Color', 'fusion-builder' ),
								'description' => esc_html__( 'Controls the border color of the FAQ item.', 'fusion-builder' ),
								'id'          => 'faq_accordian_border_color',
								'default'     => '#cccccc',
								'type'        => 'color-alpha',
								'required'    => array(
									array(
										'setting'  => 'faq_accordion_boxed_mode',
										'operator' => '!=',
										'value'    => '0',
									),
								),
							),
							'faq_accordian_background_color' => array(
								'label'       => esc_html__( 'FAQ Item Boxed Mode Background Color', 'fusion-builder' ),
								'description' => esc_html__( 'Controls the background color of the FAQ item.', 'fusion-builder' ),
								'id'          => 'faq_accordian_background_color',
								'default'     => '#ffffff',
								'type'        => 'color-alpha',
								'required'    => array(
									array(
										'setting'  => 'faq_accordion_boxed_mode',
										'operator' => '!=',
										'value'    => '0',
									),
								),
							),
							'faq_accordian_hover_color' => array(
								'label'       => esc_html__( 'FAQ Item Boxed Mode Background Hover Color', 'fusion-builder' ),
								'description' => esc_html__( 'Controls the background hover color of the FAQ item.', 'fusion-builder' ),
								'id'          => 'faq_accordian_hover_color',
								'default'     => '#f9f9f9',
								'type'        => 'color-alpha',
								'required'    => array(
									array(
										'setting'  => 'faq_accordion_boxed_mode',
										'operator' => '!=',
										'value'    => '0',
									),
								),
							),
							'faq_accordion_divider_line' => array(
								'label'       => esc_html__( 'FAQ Item Divider Line', 'fusion-builder' ),
								'description' => esc_html__( 'Turn on to display a divider line between each item.', 'fusion-builder' ),
								'id'          => 'faq_accordion_divider_line',
								'default'     => '1',
								'type'        => 'switch',
								'required'    => array(
									array(
										'setting'  => 'faq_accordion_boxed_mode',
										'operator' => '!=',
										'value'    => '1',
									),
								),
							),
							'faq_accordian_inactive_color' => array(
								'label'       => esc_html__( 'FAQ Item Inactive Box Color', 'fusion-builder' ),
								'description' => esc_html__( 'Controls the color of the inactive FAQ box.', 'fusion-builder' ),
								'id'          => 'faq_accordian_inactive_color',
								'default'     => '#333333',
								'type'        => 'color-alpha',
							),
							'faq_accordian_active_color' => array(
								'label'       => esc_html__( 'FAQ Item Active Box Color', 'fusion-builder' ),
								'description' => esc_html__( 'Controls the color of the active FAQ box.', 'fusion-builder' ),
								'id'          => 'faq_accordian_active_color',
								'default'     => $fusion_library->sanitize->color( $fusion_settings->get( 'primary_color' ) ),
								'type'        => 'color-alpha',
							),
							'faq_accordion_icon_size' => array(
								'label'       => esc_html__( 'FAQ Item Icon Size', 'fusion-builder' ),
								'description' => esc_html__( 'Set the size of the icon.', 'fusion-builder' ),
								'id'          => 'faq_accordion_icon_size',
								'default'     => '13',
								'min'         => '1',
								'max'         => '40',
								'step'        => '1',
								'type'        => 'slider',
							),
							'faq_accordian_icon_color' => array(
								'label'       => esc_html__( 'FAQ Item Icon Color', 'fusion-builder' ),
								'description' => esc_html__( 'Controls the color of icon in FAQ box.', 'fusion-builder' ),
								'id'          => 'faq_accordian_icon_color',
								'default'     => '#ffffff',
								'type'        => 'color-alpha',
							),
							'faq_accordion_icon_boxed' => array(
								'label'       => esc_html__( 'FAQ Item Icon Boxed Mode', 'fusion-builder' ),
								'description' => esc_html__( 'Turn on to display icon in boxed mode.', 'fusion-builder' ),
								'id'          => 'faq_accordion_icon_boxed',
								'default'     => '1',
								'type'        => 'switch',
							),
							'faq_accordion_icon_align' => array(
								'label'       => esc_html__( 'FAQ Item Icon Alignment', 'fusion-builder' ),
								'description' => esc_html__( 'Controls the alignment of the icon.', 'fusion-builder' ),
								'id'          => 'faq_accordion_icon_align',
								'default'     => 'left',
								'type'        => 'radio-buttonset',
								'choices'     => array(
									'left'    => esc_html__( 'Left', 'fusion-builder' ),
									'right'   => esc_html__( 'Right', 'fusion-builder' ),
								),
							),
						),
					),
				);
			}

			/**
			 * Builds the dynamic styling.
			 *
			 * @access public
			 * @since 3.1
			 * @return array
			 */
			public function add_styling() {
				global $wp_version, $content_media_query, $six_fourty_media_query, $three_twenty_six_fourty_media_query, $ipad_portrait_media_query, $fusion_settings, $fusion_library;

				$css['global']['.fusion-filters .fusion-filter.fusion-active a']['color'] = $fusion_library->sanitize->color( $fusion_settings->get( 'primary_color' ) );
				$css['global']['.fusion-filters .fusion-filter.fusion-active a']['border-color'] = $fusion_library->sanitize->color( $fusion_settings->get( 'primary_color' ) );

				$css[ $content_media_query ]['.fusion-filters']['border-bottom'] = '0';
				$css[ $content_media_query ]['.fusion-filter']['float']          = 'none';
				$css[ $content_media_query ]['.fusion-filter']['margin']         = '0';
				$css[ $content_media_query ]['.fusion-filter']['border-bottom']  = '1px solid #E7E6E6';

				return $css;
			}

			/**
			 * Sets the necessary scripts.
			 *
			 * @access public
			 * @since 3.1
			 * @return void
			 */
			public function add_scripts() {
				Fusion_Dynamic_JS::enqueue_script(
					'avada-faqs',
					FusionCore_Plugin::$js_folder_url . '/avada-faqs.js',
					FusionCore_Plugin::$js_folder_path . '/avada-faqs.js',
					array( 'jquery', 'isotope', 'jquery-infinite-scroll' ),
					'1',
					true
				);
			}
		}
	} // End if().

	new FusionSC_Faq();
} // End if().

/**
 * Map shortcode to Fusion Builder.
 *
 * @since 1.0
 */
function fusion_element_faq() {
	global $fusion_settings;
	fusion_builder_map(
		array(
			'name'       => esc_attr__( 'FAQ', 'fusion-core' ),
			'shortcode'  => 'fusion_faq',
			'icon'       => 'fa fa-lg fa-info-circle',
			'preview'    => FUSION_CORE_PATH . '/shortcodes/previews/fusion-faq-preview.php',
			'preview_id' => 'fusion-builder-block-module-faq-preview-template',
			'params'     => array(
				array(
					'type'        => 'radio_button_set',
					'heading'     => esc_attr__( 'Display Filters', 'fusion-core' ),
					'description' => esc_attr__( 'Display the FAQ filters.', 'fusion-core' ),
					'param_name'  => 'filters',
					'value'       => array(
						''                => esc_attr__( 'Default', 'fusion-core' ),
						'yes'             => esc_attr__( 'Show', 'fusion-core' ),
						'yes-without-all' => __( 'Show without "All"', 'fusion-core' ),
						'no'              => esc_attr__( 'Hide', 'fusion-core' ),
					),
					'default'     => '',
				),
				array(
					'type'        => 'radio_button_set',
					'heading'     => esc_attr__( 'Display Featured Images', 'fusion-core' ),
					'description' => esc_attr__( 'Display the FAQ featured images.', 'fusion-core' ),
					'param_name'  => 'featured_image',
					'value'       => array(
						''    => esc_attr__( 'Default', 'fusion-core' ),
						'yes' => esc_attr__( 'Yes', 'fusion-core' ),
						'no'  => esc_attr__( 'No', 'fusion-core' ),
					),
					'default'     => '',
				),
				array(
					'type'        => 'multiple_select',
					'heading'     => esc_attr__( 'Categories', 'fusion-core' ),
					'description' => esc_attr__( 'Select categories to include or leave blank for all.', 'fusion-core' ),
					'param_name'  => 'cats_slug',
					'value'       => fusion_builder_shortcodes_categories( 'faq_category' ),
					'default'     => '',
				),
				array(
					'type'        => 'multiple_select',
					'heading'     => esc_attr__( 'Exclude Categories', 'fusion-core' ),
					'description' => esc_attr__( 'Select categories to exclude.', 'fusion-core' ),
					'param_name'  => 'exclude_cats',
					'value'       => fusion_builder_shortcodes_categories( 'faq_category' ),
					'default'     => '',
				),
				array(
					'type'        => 'select',
					'heading'     => esc_attr__( 'Order By', 'fusion-builder' ),
					'description' => esc_attr__( 'Defines how FAQs should be ordered.', 'fusion-builder' ),
					'param_name'  => 'orderby',
					'default'     => 'date',
					'value'       => array(
						'date'          => esc_attr__( 'Date', 'fusion-builder' ),
						'title'         => esc_attr__( 'Post Title', 'fusion-builder' ),
						'menu_order'    => esc_attr__( 'FAQ Order', 'fusion-builder' ),
						'name'          => esc_attr__( 'Post Slug', 'fusion-builder' ),
						'author'        => esc_attr__( 'Author', 'fusion-builder' ),
						'comment_count' => esc_attr__( 'Number of Comments', 'fusion-builder' ),
						'modified'      => esc_attr__( 'Last Modified', 'fusion-builder' ),
						'rand'          => esc_attr__( 'Random', 'fusion-builder' ),
					),
				),
				array(
					'type'        => 'radio_button_set',
					'heading'     => esc_attr__( 'Order', 'fusion-builder' ),
					'description' => esc_attr__( 'Defines the sorting order of FAQs.', 'fusion-builder' ),
					'param_name'  => 'order',
					'default'     => 'DESC',
					'value'       => array(
						'DESC' => esc_attr__( 'Descending', 'fusion-builder' ),
						'ASC'  => esc_attr__( 'Ascending', 'fusion-builder' ),
					),
					'dependency'  => array(
						array(
							'element'  => 'orderby',
							'value'    => 'rand',
							'operator' => '!=',
						),
					),
				),
				array(
					'type'        => 'radio_button_set',
					'heading'     => esc_attr__( 'Toggles or Accordions', 'fusion-builder' ),
					'description' => esc_attr__( 'Toggles allow several items to be open at a time. Accordions only allow one item to be open at a time.', 'fusion-builder' ),
					'param_name'  => 'type',
					'value'       => array(
						''           => esc_attr__( 'Default', 'fusion-builder' ),
						'toggles'    => esc_attr__( 'Toggles', 'fusion-builder' ),
						'accordions' => esc_attr__( 'Accordions', 'fusion-builder' ),
					),
					'default' => '',
				),
				array(
					'type'        => 'radio_button_set',
					'heading'     => esc_attr__( 'Boxed Mode', 'fusion-builder' ),
					'description' => esc_attr__( 'Choose to display FAQs items in boxed mode.', 'fusion-builder' ),
					'param_name'  => 'boxed_mode',
					'value'       => array(
						''    => esc_attr__( 'Default', 'fusion-builder' ),
						'yes' => esc_attr__( 'Yes', 'fusion-builder' ),
						'no'  => esc_attr__( 'No', 'fusion-builder' ),
					),
					'default' => '',
				),
				array(
					'type'        => 'range',
					'heading'     => esc_attr__( 'Boxed Mode Border Width', 'fusion-builder' ),
					'description' => esc_attr__( 'Set the border width for FAQ item. In pixels.', 'fusion-builder' ),
					'param_name'  => 'border_size',
					'value'       => $fusion_settings->get( 'faq_accordion_border_size' ),
					'default'     => $fusion_settings->get( 'faq_accordion_border_size' ),
					'min'         => '0',
					'max'         => '20',
					'step'        => '1',
					'dependency'  => array(
						array(
							'element'  => 'boxed_mode',
							'value'    => 'no',
							'operator' => '!=',
						),
					),
				),
				array(
					'type'        => 'colorpickeralpha',
					'heading'     => esc_attr__( 'Boxed Mode Border Color', 'fusion-builder' ),
					'description' => esc_attr__( 'Set the border color for FAQ item.', 'fusion-builder' ),
					'param_name'  => 'border_color',
					'value'       => '',
					'default'     => $fusion_settings->get( 'faq_accordian_border_color' ),
					'dependency'  => array(
						array(
							'element'  => 'boxed_mode',
							'value'    => 'no',
							'operator' => '!=',
						),
						array(
							'element'  => 'border_size',
							'value'    => '0',
							'operator' => '!=',
						),
					),
				),
				array(
					'type'        => 'colorpickeralpha',
					'heading'     => esc_attr__( 'Boxed Mode Background Color', 'fusion-builder' ),
					'description' => esc_attr__( 'Set the background color for FAQ item.', 'fusion-builder' ),
					'param_name'  => 'background_color',
					'value'       => '',
					'default'     => $fusion_settings->get( 'accordian_background_color' ),
					'dependency'  => array(
						array(
							'element'  => 'boxed_mode',
							'value'    => 'no',
							'operator' => '!=',
						),
					),
				),
				array(
					'type'        => 'colorpickeralpha',
					'heading'     => esc_attr__( 'Boxed Mode Background Hover Color', 'fusion-builder' ),
					'description' => esc_attr__( 'Set the background hover color for FAQ item.', 'fusion-builder' ),
					'param_name'  => 'hover_color',
					'value'       => '',
					'default'     => $fusion_settings->get( 'faq_accordian_hover_color' ),
					'dependency'  => array(
						array(
							'element'  => 'boxed_mode',
							'value'    => 'no',
							'operator' => '!=',
						),
					),
				),
				array(
					'type'        => 'radio_button_set',
					'heading'     => esc_attr__( 'Divider Line', 'fusion-builder' ),
					'description' => esc_attr__( 'Choose to display a divider line between each item.', 'fusion-builder' ),
					'param_name'  => 'divider_line',
					'value'       => array(
						''    => esc_attr__( 'Default', 'fusion-builder' ),
						'yes' => esc_attr__( 'Yes', 'fusion-builder' ),
						'no'  => esc_attr__( 'No', 'fusion-builder' ),
					),
					'default' => '',
					'dependency'  => array(
						array(
							'element'  => 'boxed_mode',
							'value'    => 'yes',
							'operator' => '!=',
						),
					),
				),
				array(
					'heading'     => esc_html__( 'FAQ Item Icon Size', 'fusion-builder' ),
					'description' => esc_html__( 'Set the size of the icon. In pixels (px), ex: 13px.', 'fusion-builder' ),
					'param_name'  => 'icon_size',
					'default'     => $fusion_settings->get( 'faq_accordion_icon_size' ),
					'min'         => '1',
					'max'         => '40',
					'step'        => '1',
					'type'        => 'range',
				),
				array(
					'type'        => 'colorpicker',
					'heading'     => esc_attr__( 'FAQ Item Icon Color', 'fusion-builder' ),
					'description' => esc_attr__( 'Set the color of icon in toggle box.', 'fusion-builder' ),
					'param_name'  => 'icon_color',
					'value'       => '',
					'default'     => $fusion_settings->get( 'faq_accordian_icon_color' ),
				),
				array(
					'type'        => 'radio_button_set',
					'heading'     => esc_attr__( 'FAQ Icon Boxed Mode', 'fusion-builder' ),
					'description' => esc_attr__( 'Choose to display icon in boxed mode.', 'fusion-builder' ),
					'param_name'  => 'icon_boxed_mode',
					'value'       => array(
						''    => esc_attr__( 'Default', 'fusion-builder' ),
						'yes' => esc_attr__( 'Yes', 'fusion-builder' ),
						'no'  => esc_attr__( 'No', 'fusion-builder' ),
					),
					'default' => '',
				),
				array(
					'type'        => 'radio_button_set',
					'heading'     => esc_attr__( 'FAQ Icon Alignment', 'fusion-builder' ),
					'description' => esc_attr__( 'Controls the alignment of FAQ icon.', 'fusion-builder' ),
					'param_name'  => 'icon_alignment',
					'value'       => array(
						''       => esc_attr__( 'Default', 'fusion-builder' ),
						'left'   => esc_attr__( 'Left', 'fusion-builder' ),
						'right'  => esc_attr__( 'Right', 'fusion-builder' ),
					),
					'default' => '',
				),
				array(
					'type'        => 'checkbox_button_set',
					'heading'     => esc_attr__( 'Element Visibility', 'fusion-core' ),
					'param_name'  => 'hide_on_mobile',
					'value'       => fusion_builder_visibility_options( 'full' ),
					'default'     => fusion_builder_default_visibility( 'array' ),
					'description' => esc_attr__( 'Choose to show or hide the element on small, medium or large screens. You can choose more than one at a time.', 'fusion-core' ),
				),
				array(
					'type'        => 'textfield',
					'heading'     => esc_attr__( 'CSS Class', 'fusion-core' ),
					'description' => esc_attr__( 'Add a class to the wrapping HTML element.', 'fusion-core' ),
					'param_name'  => 'class',
					'value'       => '',
					'group'       => esc_attr__( 'General', 'fusion-core' ),
				),
				array(
					'type'        => 'textfield',
					'heading'     => esc_attr__( 'CSS ID', 'fusion-core' ),
					'description' => esc_attr__( 'Add an ID to the wrapping HTML element.', 'fusion-core' ),
					'param_name'  => 'id',
					'value'       => '',
					'group'       => esc_attr__( 'General', 'fusion-core' ),
				),
			),
		)
	);
}
add_action( 'wp_loaded', 'fusion_element_faq' );
