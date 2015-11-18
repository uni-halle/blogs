<?php
/**
 * GeneratePress Customizer
 *
 * @package GeneratePress
 */

/**
 * Add postMessage support for site title and description for the Theme Customizer.
 *
 * @param WP_Customize_Manager $wp_customize Theme Customizer object.
 */
add_action( 'customize_register', 'generate_customize_register' );
function generate_customize_register( $wp_customize ) {

	$defaults = generate_get_defaults();

	// Load custom controls
	require_once get_template_directory() . '/inc/controls.php';
	require_once get_template_directory() . '/inc/sanitize.php';
	
	$wp_customize->get_section('title_tagline')->title = __( 'Site Identity', 'generate' );
	$wp_customize->get_control('blogdescription')->priority = 3;
	$wp_customize->get_setting( 'blogdescription' )->transport = 'postMessage';
	$wp_customize->get_control('blogname')->priority = 1;
	$wp_customize->get_setting( 'blogname' )->transport = 'postMessage';
	
	$static_front_page = wp_list_pages( array( 'echo' => false ) );
	if ( ! empty( $static_front_page ) ) :
		$wp_customize->get_section('static_front_page')->title = __( 'Set Front Page', 'generate' );
		$wp_customize->get_section('static_front_page')->priority = 10;
	endif;
	
	$wp_customize->remove_section('background_image');
	$wp_customize->remove_section('colors');
	
	// Remove title
	$wp_customize->add_setting( 
		'generate_settings[hide_title]', 
		array(
			'default' => $defaults['hide_title'],
			'type' => 'option',
			'sanitize_callback' => 'generate_sanitize_checkbox'
		)
	);
	
	$wp_customize->add_control(
		'generate_settings[hide_title]',
		array(
			'type' => 'checkbox',
			'label' => __('Hide site title','generate'),
			'section' => 'title_tagline',
			'priority' => 2
		)
	);
	
	$wp_customize->add_setting( 
		'generate_settings[hide_tagline]', 
		array(
			'default' => $defaults['hide_tagline'],
			'type' => 'option',
			'sanitize_callback' => 'generate_sanitize_checkbox'
		)
	);
	
	$wp_customize->add_control(
		'generate_settings[hide_tagline]',
		array(
			'type' => 'checkbox',
			'label' => __('Hide site tagline','generate'),
			'section' => 'title_tagline',
			'priority' => 4
		)
	);
	
	$wp_customize->add_setting( 
		'generate_settings[logo]', 
		array(
			'default' => $defaults['logo'],
			'type' => 'option',
			'sanitize_callback' => 'esc_url_raw'
		)
	);
 
	$wp_customize->add_control(
		new WP_Customize_Image_Control(
			$wp_customize,
			'generate_settings[logo]',
			array(
				'label' => __('Logo','generate'),
				'section' => 'title_tagline',
				'settings' => 'generate_settings[logo]'
			)
		)
	);
	
	if ( class_exists( 'WP_Customize_Panel' ) ) :
		if ( ! $wp_customize->get_panel( 'generate_colors_panel' ) ) {
			$wp_customize->add_panel( 'generate_colors_panel', array(
				'priority'       => 30,
				'capability'     => 'edit_theme_options',
				'theme_supports' => '',
				'title'          => __( 'Colors','generate' ),
				'description'    => '',
			) );
		}
	endif;
	
	$wp_customize->add_section(
		// ID
		'body_section',
		// Arguments array
		array(
			'title' => __( 'Base Colors', 'generate' ),
			'capability' => 'edit_theme_options',
			'priority' => 30,
			'panel' => 'generate_colors_panel'
		)
	);
	
		// Add color settings
	$body_colors = array();
	$body_colors[] = array(
		'slug'=>'background_color', 
		'default' => $defaults['background_color'],
		'label' => __('Background Color', 'generate'),
		'transport' => 'postMessage'
	);
	$body_colors[] = array(
		'slug'=>'text_color', 
		'default' => $defaults['text_color'],
		'label' => __('Text Color', 'generate'),
		'transport' => 'postMessage'
	);
	$body_colors[] = array(
		'slug'=>'link_color', 
		'default' => $defaults['link_color'],
		'label' => __('Link Color', 'generate'),
		'transport' => 'postMessage'
	);
	$body_colors[] = array(
		'slug'=>'link_color_hover', 
		'default' => $defaults['link_color_hover'],
		'label' => __('Link Color Hover', 'generate'),
		'transport' => 'refresh'
	);
	$body_colors[] = array(
		'slug'=>'link_color_visited', 
		'default' => $defaults['link_color_visited'],
		'label' => __('Link Color Visited', 'generate'),
		'transport' => 'refresh'
	);

	foreach( $body_colors as $color ) {
		// SETTINGS
		$wp_customize->add_setting(
			'generate_settings[' . $color['slug'] . ']', array(
				'default' => $color['default'],
				'type' => 'option', 
				'capability' => 'edit_theme_options',
				'sanitize_callback' => 'generate_sanitize_hex_color',
				'transport' => $color['transport']
			)
		);
		// CONTROLS
		$wp_customize->add_control(
			new WP_Customize_Color_Control(
				$wp_customize,
				$color['slug'], 
				array(
					'label' => $color['label'], 
					'section' => 'body_section',
					'settings' => 'generate_settings[' . $color['slug'] . ']'
				)
			)
		);
	}
	
	if ( !function_exists( 'generate_colors_customize_register' ) && ! defined( 'GP_PREMIUM_VERSION' ) ) {

		$wp_customize->add_control(
			new Generate_Customize_Misc_Control(
				$wp_customize,
				'colors_get_addon_desc',
				array(
					'section'     => 'body_section',
					'type'        => 'addon',
					'label'			=> __( 'More Settings','generate' ),
					'url' => 'http://www.generatepress.com/downloads/generate-colors/',
					'description' => sprintf(
						__( 'Looking to add more color settings?<br /> %s.', 'generate' ),
						sprintf(
							'<a href="%1$s" target="_blank">%2$s</a>',
							esc_url( 'http://www.generatepress.com/downloads/generate-colors/' ),
							__( 'Check out Generate Colors', 'generate' )
						)
					),
					'priority'    => 30
				)
			)
		);
	}
	
	// Add Layout section
	$wp_customize->add_section(
		// ID
		'layout_section',
		// Arguments array
		array(
			'title' => __( 'Layout', 'generate' ),
			'capability' => 'edit_theme_options',
			'description' => __( 'Allows you to edit your theme\'s layout.', 'generate' ),
			'priority' => 25
		)
	);
	
	// Container width
	$wp_customize->add_setting( 
		'generate_settings[container_width]', 
		array(
			'default' => $defaults['container_width'],
			'type' => 'option',
			'sanitize_callback' => 'generate_sanitize_integer',
			'transport' => 'postMessage'
		)
	);
		
	$wp_customize->add_control( 
		new Generate_Customize_Width_Slider_Control( 
			$wp_customize, 
			'generate_settings[container_width]', 
			array(
				'label' => __('Container Width','generate'),
				'section' => 'layout_section',
				'settings' => 'generate_settings[container_width]',
				'priority' => 0
			)
		)
	);
	
	// Add Header Layout setting
	$wp_customize->add_setting(
		// ID
		'generate_settings[header_layout_setting]',
		// Arguments array
		array(
			'default' => $defaults['header_layout_setting'],
			'type' => 'option',
			'sanitize_callback' => 'generate_sanitize_header_layout'
		)
	);
	
	// Add Header Layout control
	$wp_customize->add_control(
		// ID
		'header_layout_control',
		// Arguments array
		array(
			'type' => 'select',
			'label' => __( 'Header Layout', 'generate' ),
			'section' => 'layout_section',
			'choices' => array(
				'fluid-header' => __( 'Fluid / Full Width', 'generate' ),
				'contained-header' => __( 'Contained', 'generate' )
			),
			// This last one must match setting ID from above
			'settings' => 'generate_settings[header_layout_setting]',
			'priority' => 5
		)
	);
	
	// Add navigation setting
	$wp_customize->add_setting(
		// ID
		'generate_settings[header_alignment_setting]',
		// Arguments array
		array(
			'default' => $defaults['header_alignment_setting'],
			'type' => 'option',
			'sanitize_callback' => 'generate_sanitize_alignment'
		)
	);
	
	// Add navigation control
	$wp_customize->add_control(
		// ID
		'header_alignment_control',
		// Arguments array
		array(
			'type' => 'select',
			'label' => __( 'Header Alignment', 'generate' ),
			'section' => 'layout_section',
			'choices' => array(
				'left' => __( 'Left', 'generate' ),
				'center' => __( 'Center', 'generate' ),
				'right' => __( 'Right', 'generate' )
			),
			// This last one must match setting ID from above
			'settings' => 'generate_settings[header_alignment_setting]',
			'priority' => 10
		)
	);
	
	// Add navigation setting
	$wp_customize->add_setting(
		// ID
		'generate_settings[nav_layout_setting]',
		// Arguments array
		array(
			'default' => $defaults['nav_layout_setting'],
			'type' => 'option',
			'sanitize_callback' => 'generate_sanitize_nav_layout'
		)
	);
	
	// Add navigation control
	$wp_customize->add_control(
		// ID
		'nav_layout_control',
		// Arguments array
		array(
			'type' => 'select',
			'label' => __( 'Navigation Layout', 'generate' ),
			'section' => 'layout_section',
			'choices' => array(
				'fluid-nav' => __( 'Fluid / Full Width', 'generate' ),
				'contained-nav' => __( 'Contained', 'generate' )
			),
			// This last one must match setting ID from above
			'settings' => 'generate_settings[nav_layout_setting]',
			'priority' => 15
		)
	);
	
	// Add navigation setting
	$wp_customize->add_setting(
		// ID
		'generate_settings[nav_position_setting]',
		// Arguments array
		array(
			'default' => $defaults['nav_position_setting'],
			'type' => 'option',
			'sanitize_callback' => 'generate_sanitize_nav_position'
		)
	);
	
	// Add navigation control
	$wp_customize->add_control(
		// ID
		'nav_position_control',
		// Arguments array
		array(
			'type' => 'select',
			'label' => __( 'Navigation Position', 'generate' ),
			'section' => 'layout_section',
			'choices' => array(
				'nav-below-header' => __( 'Below Header', 'generate' ),
				'nav-above-header' => __( 'Above Header', 'generate' ),
				'nav-float-right' => __( 'Float Right', 'generate' ),
				'nav-float-left' => __( 'Float Left', 'generate' ),
				'nav-left-sidebar' => __( 'Left Sidebar', 'generate' ),
				'nav-right-sidebar' => __( 'Right Sidebar', 'generate' ),
				'' => __( 'No Navigation', 'generate' )
			),
			// This last one must match setting ID from above
			'settings' => 'generate_settings[nav_position_setting]',
			'priority' => 20
		)
	);
	
	// Add navigation setting
	$wp_customize->add_setting(
		// ID
		'generate_settings[nav_alignment_setting]',
		// Arguments array
		array(
			'default' => $defaults['nav_alignment_setting'],
			'type' => 'option',
			'sanitize_callback' => 'generate_sanitize_alignment'
		)
	);
	
	// Add navigation control
	$wp_customize->add_control(
		// ID
		'nav_alignment_control',
		// Arguments array
		array(
			'type' => 'select',
			'label' => __( 'Navigation Alignment', 'generate' ),
			'section' => 'layout_section',
			'choices' => array(
				'left' => __( 'Left', 'generate' ),
				'center' => __( 'Center', 'generate' ),
				'right' => __( 'Right', 'generate' )
			),
			// This last one must match setting ID from above
			'settings' => 'generate_settings[nav_alignment_setting]',
			'priority' => 22
		)
	);
	
	// Add navigation setting
	$wp_customize->add_setting(
		// ID
		'generate_settings[nav_search]',
		// Arguments array
		array(
			'default' => $defaults['nav_search'],
			'type' => 'option',
			'sanitize_callback' => 'generate_sanitize_nav_search'
		)
	);
	
	// Add navigation control
	$wp_customize->add_control(
		// ID
		'nav_search_control',
		// Arguments array
		array(
			'type' => 'select',
			'label' => __( 'Navigation Search', 'generate' ),
			'section' => 'layout_section',
			'choices' => array(
				'enable' => __( 'Enabled', 'generate' ),
				'disable' => __( 'Disabled', 'generate' )
			),
			// This last one must match setting ID from above
			'settings' => 'generate_settings[nav_search]',
			'priority' => 23
		)
	);
	
	// Add content setting
	$wp_customize->add_setting(
		// ID
		'generate_settings[content_layout_setting]',
		// Arguments array
		array(
			'default' => $defaults['content_layout_setting'],
			'type' => 'option',
			'sanitize_callback' => 'generate_sanitize_content_layout'
		)
	);
	
	// Add content control
	$wp_customize->add_control(
		// ID
		'content_layout_control',
		// Arguments array
		array(
			'type' => 'select',
			'label' => __( 'Content Layout', 'generate' ),
			'section' => 'layout_section',
			'choices' => array(
				'separate-containers' => __( 'Separate Containers', 'generate' ),
				'one-container' => __( 'One Container', 'generate' )
			),
			// This last one must match setting ID from above
			'settings' => 'generate_settings[content_layout_setting]',
			'priority' => 25
		)
	);
	
	// Add Layout setting
	$wp_customize->add_setting(
		// ID
		'generate_settings[layout_setting]',
		// Arguments array
		array(
			'default' => $defaults['layout_setting'],
			'type' => 'option',
			'sanitize_callback' => 'generate_sanitize_sidebar_layout'
		)
	);
	
	// Add Layout control
	$wp_customize->add_control(
		// ID
		'layout_control',
		// Arguments array
		array(
			'type' => 'select',
			'label' => __( 'Sidebar Layout', 'generate' ),
			'section' => 'layout_section',
			'choices' => array(
				'left-sidebar' => __( 'Sidebar / Content', 'generate' ),
				'right-sidebar' => __( 'Content / Sidebar', 'generate' ),
				'no-sidebar' => __( 'Content (no sidebars)', 'generate' ),
				'both-sidebars' => __( 'Sidebar / Content / Sidebar', 'generate' ),
				'both-left' => __( 'Sidebar / Sidebar / Content', 'generate' ),
				'both-right' => __( 'Content / Sidebar / Sidebar', 'generate' )
			),
			// This last one must match setting ID from above
			'settings' => 'generate_settings[layout_setting]',
			'priority' => 30
		)
	);
	
	// Add Layout setting
	$wp_customize->add_setting(
		// ID
		'generate_settings[blog_layout_setting]',
		// Arguments array
		array(
			'default' => $defaults['blog_layout_setting'],
			'type' => 'option',
			'sanitize_callback' => 'generate_sanitize_sidebar_layout'
		)
	);
	
	// Add Layout control
	$wp_customize->add_control(
		// ID
		'blog_layout_control',
		// Arguments array
		array(
			'type' => 'select',
			'label' => __( 'Blog Sidebar Layout', 'generate' ),
			'section' => 'layout_section',
			'choices' => array(
				'left-sidebar' => __( 'Sidebar / Content', 'generate' ),
				'right-sidebar' => __( 'Content / Sidebar', 'generate' ),
				'no-sidebar' => __( 'Content (no sidebars)', 'generate' ),
				'both-sidebars' => __( 'Sidebar / Content / Sidebar', 'generate' ),
				'both-left' => __( 'Sidebar / Sidebar / Content', 'generate' ),
				'both-right' => __( 'Content / Sidebar / Sidebar', 'generate' )
			),
			// This last one must match setting ID from above
			'settings' => 'generate_settings[blog_layout_setting]',
			'priority' => 35
		)
	);
	
	// Add Layout setting
	$wp_customize->add_setting(
		// ID
		'generate_settings[single_layout_setting]',
		// Arguments array
		array(
			'default' => $defaults['single_layout_setting'],
			'type' => 'option',
			'sanitize_callback' => 'generate_sanitize_sidebar_layout'
		)
	);
	
	// Add Layout control
	$wp_customize->add_control(
		// ID
		'single_layout_control',
		// Arguments array
		array(
			'type' => 'select',
			'label' => __( 'Single Post Sidebar Layout', 'generate' ),
			'section' => 'layout_section',
			'choices' => array(
				'left-sidebar' => __( 'Sidebar / Content', 'generate' ),
				'right-sidebar' => __( 'Content / Sidebar', 'generate' ),
				'no-sidebar' => __( 'Content (no sidebars)', 'generate' ),
				'both-sidebars' => __( 'Sidebar / Content / Sidebar', 'generate' ),
				'both-left' => __( 'Sidebar / Sidebar / Content', 'generate' ),
				'both-right' => __( 'Content / Sidebar / Sidebar', 'generate' )
			),
			// This last one must match setting ID from above
			'settings' => 'generate_settings[single_layout_setting]',
			'priority' => 36
		)
	);
	
	// Add footer setting
	$wp_customize->add_setting(
		// ID
		'generate_settings[footer_layout_setting]',
		// Arguments array
		array(
			'default' => $defaults['footer_layout_setting'],
			'type' => 'option',
			'sanitize_callback' => 'generate_sanitize_footer_layout'
		)
	);
	
	// Add content control
	$wp_customize->add_control(
		// ID
		'footer_layout_control',
		// Arguments array
		array(
			'type' => 'select',
			'label' => __( 'Footer Layout', 'generate' ),
			'section' => 'layout_section',
			'choices' => array(
				'fluid-footer' => __( 'Fluid / Full Width', 'generate' ),
				'contained-footer' => __( 'Contained', 'generate' )
			),
			// This last one must match setting ID from above
			'settings' => 'generate_settings[footer_layout_setting]',
			'priority' => 40
		)
	);
	
	// Add footer widget setting
	$wp_customize->add_setting(
		// ID
		'generate_settings[footer_widget_setting]',
		// Arguments array
		array(
			'default' => $defaults['footer_widget_setting'],
			'type' => 'option',
			'sanitize_callback' => 'generate_sanitize_footer_widgets'
		)
	);
	
	// Add footer widget control
	$wp_customize->add_control(
		// ID
		'footer_widget_control',
		// Arguments array
		array(
			'type' => 'select',
			'label' => __( 'Footer Widgets', 'generate' ),
			'section' => 'layout_section',
			'choices' => array(
				'0' => '0',
				'1' => '1',
				'2' => '2',
				'3' => '3',
				'4' => '4',
				'5' => '5'
			),
			// This last one must match setting ID from above
			'settings' => 'generate_settings[footer_widget_setting]',
			'priority' => 45
		)
	);
	
	// Add Layout section
	$wp_customize->add_section(
		// ID
		'blog_section',
		// Arguments array
		array(
			'title' => __( 'Blog', 'generate' ),
			'capability' => 'edit_theme_options',
			'description' => '',
			'priority' => 100
		)
	);
	
	// Add Layout setting
	$wp_customize->add_setting(
		// ID
		'generate_settings[post_content]',
		// Arguments array
		array(
			'default' => $defaults['post_content'],
			'type' => 'option',
			'sanitize_callback' => 'generate_sanitize_blog_excerpt'
		)
	);
	
	// Add Layout control
	$wp_customize->add_control(
		// ID
		'blog_content_control',
		// Arguments array
		array(
			'type' => 'select',
			'label' => __( 'Blog Post Content', 'generate' ),
			'section' => 'blog_section',
			'choices' => array(
				'full' => __( 'Show full post', 'generate' ),
				'excerpt' => __( 'Show excerpt', 'generate' )
			),
			// This last one must match setting ID from above
			'settings' => 'generate_settings[post_content]',
			'priority' => 10
		)
	);
	
	if ( !function_exists( 'generate_blog_customize_register' ) && ! defined( 'GP_PREMIUM_VERSION' ) ) {

		$wp_customize->add_control(
			new Generate_Customize_Misc_Control(
				$wp_customize,
				'blog_get_addon_desc',
				array(
					'section'     => 'blog_section',
					'type'        => 'addon',
					'label'			=> __( 'More Settings','generate' ),
					'url' => 'http://www.generatepress.com/downloads/generate-blog/',
					'description' => sprintf(
						__( 'Looking to add more blog settings?<br /> %s.', 'generate' ),
						sprintf(
							'<a href="%1$s" target="_blank">%2$s</a>',
							esc_url( 'http://www.generatepress.com/downloads/generate-blog/' ),
							__( 'Check out Generate Blog', 'generate' )
						)
					),
					'priority'    => 30
				)
			)
		);
	}
}

add_action( 'customize_preview_init', 'generate_customizer_live_preview' );
function generate_customizer_live_preview()
{
	wp_enqueue_script( 
		  'generate-themecustomizer',
		  get_template_directory_uri().'/inc/js/customizer.js',
		  array( 'jquery','customize-preview' ),
		  GENERATE_VERSION,
		  true
	);
}

/**
 * Heading area
 *
 * Since 0.1
 **/
if ( class_exists( 'WP_Customize_Control' ) ) {
    # Adds textarea support to the theme customizer
    class GenerateLabelControl extends WP_Customize_Control {
        public $type = 'label';
        public function __construct( $manager, $id, $args = array() ) {
            $this->statuses = array( '' => __( 'Default', 'generate' ) );
            parent::__construct( $manager, $id, $args );
        }
 
        public function render_content() {
            echo '<span class="generate_customize_label">' . esc_html( $this->label ) . '</span>';
        }
    }
 
}

/**
 * Class Generate_Customize_Misc_Control
 *
 * Control for adding arbitrary HTML to a Customizer section.
 *
 * @since 1.0.7
 */
if ( class_exists( 'WP_Customize_Control' ) && ! class_exists( 'Generate_Customize_Misc_Control' ) ) {
	class Generate_Customize_Misc_Control extends WP_Customize_Control {
		public $settings = 'blogname';
		public $description = '';
		public $url = '';
		public $group = '';

		public function render_content() {
			switch ( $this->type ) {
				default:
				case 'text' :
					echo '<p class="description">' . $this->description . '</p>';
					break;

				case 'addon':
					echo '<span class="get-addon">' . sprintf(
								'<a href="%1$s" target="_blank">%2$s</a>',
								esc_url( $this->url ),
								__('Add-on available','generate')
							) . '</span>';
					echo '<p class="description" style="margin-top:5px;">' . $this->description . '</p>';
					break;
					
				case 'line' :
					echo '<hr />';
					break;
			}
		}
	}
}

add_action('customize_controls_print_styles', 'generate_customize_preview_css');
function generate_customize_preview_css() {
	?>
	<style>
		#accordion-section-layout_section .accordion-section-content .customize-control {
			border-bottom: 1px solid #eee;
			padding-bottom: 10px;
		}
		#accordion-section-layout_section .accordion-section-content .customize-control:last-child {
			border-bottom: 0;
			padding-bottom: 0;
		}
		#customize-control-blogname,
		#customize-control-blogdescription {
			margin-bottom: 0;
		}
		
		.customize-control-title.addon {
			display:inline;
		}

		.get-addon a {
			background: #D54E21;
			color:#FFF;
			text-transform: uppercase;
			font-size:11px;
			padding: 3px 5px;
			font-weight: bold;
		}
		
		.customize-control-addon {
			margin-top: 10px;
		}
		
	</style>
	<?php
}

add_action('customize_controls_print_footer_scripts', 'generate_customize_preview_js');
function generate_customize_preview_js()
{
	if ( generate_addons_available() !== true )
		return;
	?>
	<script>
		jQuery('#customize-info').append('<span class="get-addon" style="display:block;"><a style="display:block;padding-left: 15px;padding-right:0;" href="<?php echo esc_url('http://generatepress.com/add-ons');?>" target="_blank"><?php _e('Add-ons Available! Take a look','generate');?> &rarr;</a></span>');
	</script>
	<?php
}