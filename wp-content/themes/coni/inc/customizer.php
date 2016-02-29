<?php
/**
 * Coni Theme Customizer.
 *
 * @package Coni
 */

/**
 * Add postMessage support for site title and description for the Theme Customizer.
 *
 * @param WP_Customize_Manager $wp_customize Theme Customizer object.
 */
function coni_customize_register( $wp_customize ) {

	/**
	 * Control for the PRO buttons
	 */
	class coni_Pro_Version extends WP_Customize_Control{
		public function render_content()
		{
			$args = array(
				'a' => array(
					'href' => array(),
					'title' => array()
					),
				'br' => array(),
				'em' => array(),
				'strong' => array(),
				);
			echo wp_kses( $this->label, $args );
		}
	}

	$wp_customize->get_setting( 'blogname' )->transport         = 'postMessage';
	$wp_customize->get_setting( 'blogdescription' )->transport  = 'postMessage';
	$wp_customize->get_setting( 'header_textcolor' )->transport = 'postMessage';


	/*
	Logo
	------------------------------ */
	$wp_customize->add_section( 'coni_logo_section', array(
		'title' => esc_attr__( 'Logo', 'coni' ),
		'priority' => 80,
	) );
	$wp_customize->add_setting( 'coni_probtn_logo', array( 'default' => '', 'sanitize_callback' => 'coni_sanitize_text', ) );
	$wp_customize->add_control( new coni_Display_Text_Control( $wp_customize, 'coni_probtn_logo', array(
		'section' => 'coni_logo_section', // Required, core or custom.
		'label' => sprintf( __( 'Check out the <a href="%s" target="_blank">PRO version</a> to use an image logo.', 'coni' ), 'https://www.quemalabs.com/theme/coni-pro/' ),
	) ) );


	/*
    Colors
    ===================================================== */
    	$wp_customize->add_setting( 'coni_probtn_colors', array( 'default' => '', 'sanitize_callback' => 'coni_sanitize_text', ) );
		$wp_customize->add_control( new coni_Display_Text_Control( $wp_customize, 'coni_probtn_colors', array(
			'section' => 'colors', // Required, core or custom.
			'label' => sprintf( __( 'Check out the <a href="%s" target="_blank">PRO version</a> to change Text, Links and Featured colors.', 'coni' ), 'https://www.quemalabs.com/theme/coni-pro/' ),
		) ) );





	/*
    Sections
    ===================================================== */
    $wp_customize->add_panel( 'coni_front_page_sections', array(
		'title' => esc_attr__( 'Front Page Sections', 'coni' ),
		'description' => '', // Include html tags such as <p>.
		'priority' => 160,
		'active_callback' => 'is_front_page',
	) );

    	/*
    	Welcome
    	------------------------------ */
    	$wp_customize->add_section( 'coni_welcome_section', array(
			'title' => esc_attr__( 'Welcome', 'coni' ),
			'description' => esc_attr__( 'Display a big image and welcome message.', 'coni' ),
			'panel' => 'coni_front_page_sections',
			'priority' => 80,
		) );

		$wp_customize->add_setting( 'coni_welcome_title', array( 'default' => esc_attr__( 'Be the one to stand out in the crowd', 'coni' ), 'transport' => 'postMessage', 'sanitize_callback' => 'coni_sanitize_text', ) );
		$wp_customize->add_control( 'coni_welcome_title', array(
			'type' => 'text',
			'section' => 'coni_welcome_section', // Required, core or custom.
			'label' => esc_attr__( 'Welcome Message', 'coni' ),
		) );

		$wp_customize->add_setting( 'coni_welcome_link_title', array( 'default' => esc_html__( 'Learn More', 'coni' ), 'transport' => 'postMessage', 'sanitize_callback' => 'coni_sanitize_text', ) );
		$wp_customize->add_control( 'coni_welcome_link_title', array(
			'type' => 'text',
			'section' => 'coni_welcome_section', // Required, core or custom.
			'label' => esc_attr__( "Link Title", 'coni' ),
		) );

		$wp_customize->add_setting( 'coni_welcome_link_url', array( 'default' => '', 'transport' => 'postMessage', 'sanitize_callback' => 'coni_sanitize_url', ) );
		$wp_customize->add_control( 'coni_welcome_link_url', array(
			'type' => 'url',
			'section' => 'coni_welcome_section', // Required, core or custom.
			'label' => esc_attr__( "Link URL", 'coni' ),
		) );

		$wp_customize->add_setting( 'coni_welcome_image', array( 'default' => '', 'transport' => 'postMessage', 'sanitize_callback' => 'attachment_url_to_postid', ) );
		    $wp_customize->add_control( new WP_Customize_Image_Control( $wp_customize, 'coni_welcome_image', array(
		        'label'    => esc_attr__( 'Welcome Image', 'coni' ),
		        'section'  => 'coni_welcome_section',
		        'settings' => 'coni_welcome_image',
		    ) ) );

		$wp_customize->add_setting( 'coni_welcome_enable', array( 'default' => true, 'transport' => 'postMessage', 'sanitize_callback' => 'coni_sanitize_bool', ) );
	    $wp_customize->add_control( 'coni_welcome_enable', array(
			'section' => 'coni_welcome_section', // Required, core or custom.
			'label' => esc_attr__( "Use this section?", 'coni' ),
			'type'    => 'checkbox',
		) );

    	/*
    	Services
    	------------------------------ */
    	$wp_customize->add_section( 'coni_services_section', array(
			'title' => esc_attr__( 'Services', 'coni' ),
			'description' => esc_attr__( 'Display services with icons.', 'coni' ),
			'panel' => 'coni_front_page_sections',
			'priority' => 100,
		) );

		$wp_customize->add_setting( 'coni_services_text', array( 'default' => '', 'sanitize_callback' => 'coni_sanitize_text', ) );
		$wp_customize->add_control( new coni_Display_Text_Control( $wp_customize, 'coni_services_text', array(
			'section' => 'coni_services_section', // Required, core or custom.
			'label' => __( 'To add services go to: <br><a href="#" data-section="sidebar-widgets-services-section">Customize -> Widgets -> Front Page - Service Section</a>. <br>Then add the "<strong>Coni - Service widget</strong>"', 'coni' ),
		) ) );

		$wp_customize->add_setting( 'coni_services_enable', array( 'default' => true, 'transport' => 'postMessage', 'sanitize_callback' => 'coni_sanitize_bool', ) );
	    $wp_customize->add_control( 'coni_services_enable', array(
			'section' => 'coni_services_section', // Required, core or custom.
			'label' => esc_attr__( "Use this section?", 'coni' ),
			'type'    => 'checkbox',
		) );

    	/*
    	Quote
    	------------------------------ */
		$wp_customize->add_section( 'coni_quote_section', array(
			'title' => esc_attr__( 'Quote', 'coni' ),
			'description' => esc_attr__( 'Display a quote and an image inside a laptop.', 'coni' ),
			'panel' => 'coni_front_page_sections',
			'priority' => 120,
		) );

		$wp_customize->add_setting( 'coni_quote', array( 'default' => esc_html__( '"We are an agency passionate about what we do, providing a great service to small businesses. Fusce dapibus, tellus ac cursus commodo, tortor mauris condimentum nibh, ut fermentum massa justo sit amet risus."', 'coni' ), 'transport' => 'postMessage', 'sanitize_callback' => 'coni_sanitize_textarea', ) );
		$wp_customize->add_control( 'coni_quote', array(
			'type' => 'textarea',
			'section' => 'coni_quote_section', // Required, core or custom.
			'label' => esc_attr__( 'Quote', 'coni' ),
			//'description' => esc_attr__( '', 'coni' ),
		) );

		$wp_customize->add_setting( 'coni_quote_cite', array( 'default' => __( 'John Smith <span>CEO, Coni Inc.</span>', 'coni' ), 'transport' => 'postMessage', 'sanitize_callback' => 'coni_sanitize_text_html', ) );
		$wp_customize->add_control( 'coni_quote_cite', array(
			'type' => 'text',
			'section' => 'coni_quote_section', // Required, core or custom.
			'label' => esc_attr__( "Quote's Author", 'coni' ),
			//'description' => esc_attr__( 'Example: John Smith <span>CEO, Coni Inc.</span>', 'coni' ),
		) );

		$wp_customize->add_setting( 'coni_quote_image', array( 'default' => '', 'transport' => 'postMessage', 'sanitize_callback' => 'coni_sanitize_integer', ) );
		$wp_customize->add_control( new WP_Customize_Cropped_Image_Control( $wp_customize, 'coni_quote_image', array(
		    'section'     => 'coni_quote_section',
		    'label'       => esc_attr__( 'Image' , 'coni' ),
		    'description' => esc_attr__( 'Screenshot to be displayed within the laptop.', 'coni' ),
		    'flex_width'  => false, // Allow any width, making the specified value recommended. False by default.
		    'flex_height' => false, // Require the resulting image to be exactly as tall as the height attribute (default).
		    'width'       => 900,
		    'height'      => 563,
		) ) );

		$wp_customize->add_setting( 'coni_quote_bck_color', array( 'default' => '#50e3c2', 'transport' => 'postMessage', 'sanitize_callback' => 'sanitize_hex_color', ) );
		$wp_customize->add_control( new WP_Customize_Color_Control( $wp_customize, 'coni_quote_bck_color', array(
			'label'        => esc_attr__( 'Background Color', 'coni' ),
			'section'    => 'coni_quote_section',
		) ) );

		$wp_customize->add_setting( 'coni_quote_enable', array( 'default' => true, 'transport' => 'postMessage', 'sanitize_callback' => 'coni_sanitize_bool', ) );
	    $wp_customize->add_control( 'coni_quote_enable', array(
			'section' => 'coni_quote_section', // Required, core or custom.
			'label' => esc_attr__( "Use this section?", 'coni' ),
			'type'    => 'checkbox',
		) );

		/*
    	Video
    	------------------------------ */
		$wp_customize->add_section( 'coni_video_section', array(
			'title' => esc_attr__( 'Video', 'coni' ),
			'description' => esc_attr__( 'Display a video from YouTube or Vimeo and text aside.', 'coni' ),
			'panel' => 'coni_front_page_sections',
			'priority' => 140,
		) );

		$wp_customize->add_setting( 'coni_video_url', array( 'default' => 'https://vimeo.com/137643804', 'sanitize_callback' => 'coni_sanitize_url', ) );
		$wp_customize->add_control( 'coni_video_url', array(
			'type' => 'url',
			'section' => 'coni_video_section', // Required, core or custom.
			'label' => esc_attr__( "Video URL", 'coni' ),
			'description' => esc_attr__( "Must be a YouTube or Vimeo URL", 'coni' ),
		) );

		/* translators: Lorem ipsum text, It is not strictly necessary to translate. */
		$wp_customize->add_setting( 'coni_video_title', array( 'default' => esc_html__( 'Praesent commodo cursus magna, vel scelerisque nisl consectetur et', 'coni' ), 'transport' => 'postMessage', 'sanitize_callback' => 'coni_sanitize_text', ) );
		$wp_customize->add_control( 'coni_video_title', array(
			'type' => 'text',
			'section' => 'coni_video_section', // Required, core or custom.
			'label' => esc_attr__( "Title", 'coni' ),
		) );

		/* translators: Lorem ipsum text, It is not strictly necessary to translate. */
		$wp_customize->add_setting( 'coni_video_text', array( 'default' => esc_html__( 'Cras justo odio, dapibus ac facilisis in, egestas eget quam. Nullam quis risus eget urna mollis ornare vel eu leo. Cras justo odio, dapibus ac facilisis in, egestas eget quam. Praesent commodo cursus magna.', 'coni' ), 'transport' => 'postMessage', 'sanitize_callback' => 'coni_sanitize_text_html', ) );
		$wp_customize->add_control( 'coni_video_text', array(
			'type' => 'textarea',
			'section' => 'coni_video_section', // Required, core or custom.
			'label' => esc_attr__( 'Text', 'coni' ),
		) );

		$wp_customize->add_setting( 'coni_video_link_title', array( 'default' => esc_html__( 'Learn More', 'coni' ), 'transport' => 'postMessage', 'sanitize_callback' => 'coni_sanitize_text', ) );
		$wp_customize->add_control( 'coni_video_link_title', array(
			'type' => 'text',
			'section' => 'coni_video_section', // Required, core or custom.
			'label' => esc_attr__( "Link Title", 'coni' ),
		) );

		$wp_customize->add_setting( 'coni_video_link_url', array( 'default' => '', 'transport' => 'postMessage', 'sanitize_callback' => 'coni_sanitize_url', ) );
		$wp_customize->add_control( 'coni_video_link_url', array(
			'type' => 'url',
			'section' => 'coni_video_section', // Required, core or custom.
			'label' => esc_attr__( "Link URL", 'coni' ),
		) );

		$wp_customize->add_setting( 'coni_video_enable', array( 'default' => true, 'transport' => 'postMessage', 'sanitize_callback' => 'coni_sanitize_bool', ) );
	    $wp_customize->add_control( 'coni_video_enable', array(
			'section' => 'coni_video_section', // Required, core or custom.
			'label' => esc_attr__( "Use this section?", 'coni' ),
			'type'    => 'checkbox',
		) );

		/*
    	Testimonial
    	------------------------------ */
    	if ( class_exists( 'Jetpack' ) ){

			$wp_customize->add_section( 'coni_testimonials_section', array(
				'title' => esc_attr__( 'Testimonials', 'coni' ),
				'description' => sprintf( __( 'To create a testimonial go to your <a href="%s">Admin Panel >> Testimonials >> Add New</a>.', 'coni' ), get_admin_url( null, 'post-new.php?post_type=jetpack-testimonial' ) ),
				'panel' => 'coni_front_page_sections',
				'priority' => 160,
			) );

			$wp_customize->add_setting( 'coni_testimonial_image', array( 'default' => '', 'transport' => 'postMessage', 'sanitize_callback' => 'attachment_url_to_postid', ) );
		    $wp_customize->add_control( new WP_Customize_Image_Control( $wp_customize, 'coni_testimonial_image', array(
		        'label'    => esc_attr__( 'Background Image', 'coni' ),
		        'section'  => 'coni_testimonials_section',
		        'settings' => 'coni_testimonial_image',
		    ) ) );

		    $wp_customize->add_setting( 'coni_testimonial_enable', array( 'default' => true, 'transport' => 'postMessage', 'sanitize_callback' => 'coni_sanitize_bool', ) );
		    $wp_customize->add_control( 'coni_testimonial_enable', array(
				'section' => 'coni_testimonials_section', // Required, core or custom.
				'label' => esc_attr__( "Use this section?", 'coni' ),
				'type'    => 'checkbox',
			) );

		}

		/*
    	Image
    	------------------------------ */
		$wp_customize->add_section( 'coni_image_section', array(
			'title' => esc_attr__( 'Image', 'coni' ),
			'description' => esc_attr__( 'Display an image and text aside.', 'coni' ),
			'panel' => 'coni_front_page_sections',
			'priority' => 180,
		) );

		$wp_customize->add_setting( 'coni_image_image', array( 'default' => '', 'transport' => 'postMessage', 'sanitize_callback' => 'attachment_url_to_postid', ) );
	    $wp_customize->add_control( new WP_Customize_Image_Control( $wp_customize, 'coni_image_image', array(
	        'label'    => esc_attr__( 'Featured Image', 'coni' ),
	        'section'  => 'coni_image_section',
	        'settings' => 'coni_image_image',
	    ) ) );

	    /* translators: Lorem ipsum text, It is not strictly necessary to translate. */
		$wp_customize->add_setting( 'coni_image_title', array( 'default' => esc_html__( 'Donec id elit non mi porta gravida at eget metus', 'coni' ), 'transport' => 'postMessage', 'sanitize_callback' => 'coni_sanitize_text', ) );
		$wp_customize->add_control( 'coni_image_title', array(
			'type' => 'text',
			'section' => 'coni_image_section', // Required, core or custom.
			'label' => esc_attr__( "Title", 'coni' ),
		) );

		/* translators: Lorem ipsum text, It is not strictly necessary to translate. */
		$wp_customize->add_setting( 'coni_image_text', array( 'default' => esc_html__( 'Donec sed odio dui. Duis mollis, est non commodo luctus, nisi erat porttitor ligula, eget lacinia odio sem nec elit. Duis mollis, est non commodo luctus, nisi erat porttitor ligula, eget lacinia odio sem nec elit. Nulla vitae elit libero, a pharetra augue. Cum sociis natoque penatibus et magnis dis parturient montes, nascetur ridiculus mus. Etiam porta sem malesuada magna mollis euismod. Lorem ipsum dolor sit amet, consectetur adipiscing elit.', 'coni' ), 'transport' => 'postMessage', 'sanitize_callback' => 'coni_sanitize_text_html', ) );
		$wp_customize->add_control( 'coni_image_text', array(
			'type' => 'textarea',
			'section' => 'coni_image_section', // Required, core or custom.
			'label' => esc_attr__( 'Text', 'coni' ),
		) );

		$wp_customize->add_setting( 'coni_image_link_title', array( 'default' => esc_html__( 'Learn More', 'coni' ), 'transport' => 'postMessage', 'sanitize_callback' => 'coni_sanitize_text', ) );
		$wp_customize->add_control( 'coni_image_link_title', array(
			'type' => 'text',
			'section' => 'coni_image_section', // Required, core or custom.
			'label' => esc_attr__( "Link Title", 'coni' ),
		) );

		$wp_customize->add_setting( 'coni_image_link_url', array( 'default' => '', 'transport' => 'postMessage', 'sanitize_callback' => 'coni_sanitize_url', ) );
		$wp_customize->add_control( 'coni_image_link_url', array(
			'type' => 'url',
			'section' => 'coni_image_section', // Required, core or custom.
			'label' => esc_attr__( "Link URL", 'coni' ),
		) );

		$wp_customize->add_setting( 'coni_image_enable', array( 'default' => true, 'transport' => 'postMessage', 'sanitize_callback' => 'coni_sanitize_bool', ) );
	    $wp_customize->add_control( 'coni_image_enable', array(
			'section' => 'coni_image_section', // Required, core or custom.
			'label' => esc_attr__( "Use this section?", 'coni' ),
			'type'    => 'checkbox',
		) );

		/*
    	Team
    	------------------------------ */
    	$wp_customize->add_section( 'coni_team_section', array(
			'title' => esc_attr__( 'Team', 'coni' ),
			'description' => esc_attr__( 'Display your team members.', 'coni' ),
			'panel' => 'coni_front_page_sections',
			'priority' => 200,
		) );

		$wp_customize->add_setting( 'coni_probtn_team', array( 'default' => '', 'sanitize_callback' => 'coni_sanitize_text', ) );
		$wp_customize->add_control( new coni_Display_Text_Control( $wp_customize, 'coni_probtn_team', array(
			'section' => 'coni_team_section', // Required, core or custom.
			'label' => sprintf( __( 'Check out the <a href="%s" target="_blank">PRO version</a> to include team members.', 'coni' ), 'https://www.quemalabs.com/theme/coni-pro/' ),
		) ) );


		/*
    	Phone
    	------------------------------ */
		$wp_customize->add_section( 'coni_phone_section', array(
			'title' => esc_attr__( 'Phone', 'coni' ),
			'description' => esc_attr__( 'Display a phone screenshot and services.', 'coni' ),
			'panel' => 'coni_front_page_sections',
			'priority' => 220,
		) );

		$wp_customize->add_setting( 'coni_phone_image', array( 'default' => '', 'transport' => 'postMessage', 'sanitize_callback' => 'coni_sanitize_integer', ) );
		$wp_customize->add_control( new WP_Customize_Cropped_Image_Control( $wp_customize, 'coni_phone_image', array(
		    'section'     => 'coni_phone_section',
		    'label'       => esc_attr__( 'Phone Screen' , 'coni' ),
		    'description' => esc_attr__( 'Screenshot to be displayed within the phone.', 'coni' ),
		    'flex_width'  => false, // Allow any width, making the specified value recommended. False by default.
		    'flex_height' => false, // Require the resulting image to be exactly as tall as the height attribute (default).
		    'width'       => 750,
		    'height'      => 1334,
		) ) );

		$wp_customize->add_setting( 'coni_phone_text', array( 'default' => '', 'sanitize_callback' => 'coni_sanitize_text', ) );
		$wp_customize->add_control( new coni_Display_Text_Control( $wp_customize, 'coni_phone_text', array(
			'section' => 'coni_phone_section', // Required, core or custom.
			'label' => __( 'To add services go to: <br><a href="#" data-section="sidebar-widgets-phone-section">Customize -> Widgets -> Front Page - Phone Section</a>. <br>Then add the "<strong>Coni - Service widget</strong>"', 'coni' ),
		) ) );

		$wp_customize->add_setting( 'coni_phone_link_title', array( 'default' => esc_html__( 'Learn More', 'coni' ), 'transport' => 'postMessage', 'sanitize_callback' => 'coni_sanitize_text', ) );
		$wp_customize->add_control( 'coni_phone_link_title', array(
			'type' => 'text',
			'section' => 'coni_phone_section', // Required, core or custom.
			'label' => esc_attr__( "Link Title", 'coni' ),
		) );

		$wp_customize->add_setting( 'coni_phone_link_url', array( 'default' => '#', 'transport' => 'postMessage', 'sanitize_callback' => 'coni_sanitize_url', ) );
		$wp_customize->add_control( 'coni_phone_link_url', array(
			'type' => 'url',
			'section' => 'coni_phone_section', // Required, core or custom.
			'label' => esc_attr__( "Link URL", 'coni' ),
		) );

		$wp_customize->add_setting( 'coni_phone_bck_color', array( 'default' => '#68beec', 'transport' => 'postMessage', 'sanitize_callback' => 'sanitize_hex_color', ) );
		$wp_customize->add_control( new WP_Customize_Color_Control( $wp_customize, 'coni_phone_bck_color', array(
			'label'        => esc_attr__( 'Background Color', 'coni' ),
			'section'    => 'coni_phone_section',
		) ) );

	    $wp_customize->add_setting( 'coni_phone_color', array( 'default' => 'black', 'transport' => 'postMessage', 'sanitize_callback' => 'sanitize_key', ) );
	    $wp_customize->add_control( 'coni_phone_color', array(
			'section' => 'coni_phone_section', // Required, core or custom.
			'label' => esc_attr__( "Phone Color", 'coni' ),
			'type'    => 'select',
	        'choices'    => array(
	            'black' => 'Black',
	            'gray' => 'Gray',
	            'gold' => 'Gold',
	        ),
		) );

		$wp_customize->add_setting( 'coni_phone_enable', array( 'default' => true, 'transport' => 'postMessage', 'sanitize_callback' => 'coni_sanitize_bool', ) );
	    $wp_customize->add_control( 'coni_phone_enable', array(
			'section' => 'coni_phone_section', // Required, core or custom.
			'label' => esc_attr__( "Use this section?", 'coni' ),
			'type'    => 'checkbox',
		) );

		/*
    	Tagline
    	------------------------------ */
		$wp_customize->add_section( 'coni_tagline_section', array(
			'title' => esc_attr__( 'Tagline', 'coni' ),
			'description' => esc_attr__( 'Display and link a tagline.', 'coni' ),
			'panel' => 'coni_front_page_sections',
			'priority' => 240,
		) );

		$wp_customize->add_setting( 'coni_tagline_text', array( 'default' => esc_html__( 'Start captivating the attention of your client', 'coni' ), 'transport' => 'postMessage', 'sanitize_callback' => 'coni_sanitize_text', ) );
		$wp_customize->add_control( 'coni_tagline_text', array(
			'type' => 'text',
			'section' => 'coni_tagline_section', // Required, core or custom.
			'label' => esc_attr__( "Tagline", 'coni' ),
		) );

		$wp_customize->add_setting( 'coni_tagline_sub_text', array( 'default' => esc_html__( 'Cras justo odio, dapibus ac facilisis in, egestas eget quam', 'coni' ), 'transport' => 'postMessage', 'sanitize_callback' => 'coni_sanitize_text', ) );
		$wp_customize->add_control( 'coni_tagline_sub_text', array(
			'type' => 'text',
			'section' => 'coni_tagline_section', // Required, core or custom.
			'label' => esc_attr__( "Sub-Text", 'coni' ),
		) );

		$wp_customize->add_setting( 'coni_tagline_link_url', array( 'default' => '', 'transport' => 'postMessage', 'sanitize_callback' => 'coni_sanitize_url', ) );
		$wp_customize->add_control( 'coni_tagline_link_url', array(
			'type' => 'url',
			'section' => 'coni_tagline_section', // Required, core or custom.
			'label' => esc_attr__( "Tagline Link", 'coni' ),
		) );

		$wp_customize->add_setting( 'coni_tagline_bck_color', array( 'default' => '#68d2ae', 'transport' => 'postMessage', 'sanitize_callback' => 'sanitize_hex_color', ) );
		$wp_customize->add_control( new WP_Customize_Color_Control( $wp_customize, 'coni_tagline_bck_color', array(
			'label'        => esc_attr__( 'Background Color', 'coni' ),
			'section'    => 'coni_tagline_section',
		) ) );

		$wp_customize->add_setting( 'coni_tagline_enable', array( 'default' => true, 'transport' => 'postMessage', 'sanitize_callback' => 'coni_sanitize_bool', ) );
	    $wp_customize->add_control( 'coni_tagline_enable', array(
			'section' => 'coni_tagline_section', // Required, core or custom.
			'label' => esc_attr__( "Use this section?", 'coni' ),
			'type'    => 'checkbox',
		) );

		/*
    	Clients
    	------------------------------ */
		$wp_customize->add_section( 'coni_clients_section', array(
			'title' => esc_attr__( 'Clients', 'coni' ),
			'description' => esc_attr__( "Display client's logos.", 'coni' ),
			'panel' => 'coni_front_page_sections',
			'priority' => 260,
		) );

		$wp_customize->add_setting( 'coni_clients_title', array( 'default' => esc_html__( 'People Who Trust Us', 'coni' ), 'transport' => 'postMessage', 'sanitize_callback' => 'coni_sanitize_text', ) );
		$wp_customize->add_control( 'coni_clients_title', array(
			'type' => 'text',
			'section' => 'coni_clients_section', // Required, core or custom.
			'label' => esc_attr__( "Section Title", 'coni' ),
		) );

		$wp_customize->add_setting( 'coni_clients_text', array( 'default' => '', 'sanitize_callback' => 'coni_sanitize_text', ) );
		$wp_customize->add_control( new coni_Display_Text_Control( $wp_customize, 'coni_clients_text', array(
			'section' => 'coni_clients_section', // Required, core or custom.
			'label' => __( 'To add services go to: <br><a href="#" data-section="sidebar-widgets-clients-section">Customize -> Widgets -> Front Page - Clients Section</a>. <br>Then add the "<strong>Coni - Client widget</strong>"', 'coni' ),
		) ) );

		$wp_customize->add_setting( 'coni_clients_enable', array( 'default' => true, 'transport' => 'postMessage', 'sanitize_callback' => 'coni_sanitize_bool', ) );
	    $wp_customize->add_control( 'coni_clients_enable', array(
			'section' => 'coni_clients_section', // Required, core or custom.
			'label' => esc_attr__( "Use this section?", 'coni' ),
			'type'    => 'checkbox',
		) );

		/*
    	Map
    	------------------------------ */
		$wp_customize->add_section( 'coni_map_section', array(
			'title' => esc_attr__( 'Map', 'coni' ),
			'description' => esc_attr__( "Display a map and your contact information.", 'coni' ),
			'panel' => 'coni_front_page_sections',
			'priority' => 280,
		) );

		$wp_customize->add_setting( 'coni_probtn_map', array( 'default' => '', 'sanitize_callback' => 'coni_sanitize_text', ) );
		$wp_customize->add_control( new coni_Display_Text_Control( $wp_customize, 'coni_probtn_map', array(
			'section' => 'coni_map_section', // Required, core or custom.
			'label' => sprintf( __( 'Check out the <a href="%s" target="_blank">PRO version</a> to create a map and contact info.', 'coni' ), 'https://www.quemalabs.com/theme/coni-pro/' ),
		) ) );


		/*
    	Pricing
    	------------------------------ */
		$wp_customize->add_section( 'coni_pricing_section', array(
			'title' => esc_attr__( 'Pricing', 'coni' ),
			'description' => esc_attr__( "Display pricing lists.", 'coni' ),
			'panel' => 'coni_front_page_sections',
			'priority' => 300,
		) );

		$wp_customize->add_setting( 'coni_pricing_title', array( 'default' => esc_html__( 'Pricing', 'coni' ), 'transport' => 'postMessage', 'sanitize_callback' => 'coni_sanitize_text', ) );
		$wp_customize->add_control( 'coni_pricing_title', array(
			'type' => 'text',
			'section' => 'coni_pricing_section', // Required, core or custom.
			'label' => esc_attr__( "Section Title", 'coni' ),
		) );

		$wp_customize->add_setting( 'coni_pricing_text', array( 'default' => '', 'sanitize_callback' => 'coni_sanitize_text', ) );
		$wp_customize->add_control( new coni_Display_Text_Control( $wp_customize, 'coni_pricing_text', array(
			'section' => 'coni_pricing_section', // Required, core or custom.
			'label' => __( 'To add a list go to: <br><a href="#" data-section="sidebar-widgets-pricing-section">Customize -> Widgets -> Front Page - Pricing Section</a>. <br>Then add the "<strong>Coni - Pricing widget</strong>"', 'coni' ),
		) ) );

		$wp_customize->add_setting( 'coni_pricing_enable', array( 'default' => true, 'transport' => 'postMessage', 'sanitize_callback' => 'coni_sanitize_bool', ) );
	    $wp_customize->add_control( 'coni_pricing_enable', array(
			'section' => 'coni_pricing_section', // Required, core or custom.
			'label' => esc_attr__( "Use this section?", 'coni' ),
			'type'    => 'checkbox',
		) );

		/*
    	Portfolio
    	------------------------------ */
		$wp_customize->add_section( 'coni_portfolio_section', array(
			'title' => esc_attr__( 'Portfolio', 'coni' ),
			'description' => esc_attr__( "Display portfolio items.", 'coni' ),
			'panel' => 'coni_front_page_sections',
			'priority' => 320,
		) );

		$wp_customize->add_setting( 'coni_probtn_portfolio', array( 'default' => '', 'sanitize_callback' => 'coni_sanitize_text', ) );
		$wp_customize->add_control( new coni_Display_Text_Control( $wp_customize, 'coni_probtn_portfolio', array(
			'section' => 'coni_portfolio_section', // Required, core or custom.
			'label' => sprintf( __( 'Check out the <a href="%s" target="_blank">PRO version</a> to create a portfolio.', 'coni' ), 'https://www.quemalabs.com/theme/coni-pro/' ),
		) ) );


		/*
    	Blog
    	------------------------------ */
		$wp_customize->add_section( 'coni_blog_section', array(
			'title' => esc_attr__( 'Blog', 'coni' ),
			'description' => esc_attr__( "Display blog posts.", 'coni' ),
			'panel' => 'coni_front_page_sections',
			'priority' => 340,
		) );

		$wp_customize->add_setting( 'coni_blog_title', array( 'default' => esc_html__( 'From the blog', 'coni' ), 'transport' => 'postMessage', 'sanitize_callback' => 'coni_sanitize_text', ) );
		$wp_customize->add_control( 'coni_blog_title', array(
			'type' => 'text',
			'section' => 'coni_blog_section', // Required, core or custom.
			'label' => esc_attr__( "Section Title", 'coni' ),
		) );

		$wp_customize->add_setting( 'coni_blog_enable', array( 'default' => true, 'transport' => 'postMessage', 'sanitize_callback' => 'coni_sanitize_bool', ) );
	    $wp_customize->add_control( 'coni_blog_enable', array(
			'section' => 'coni_blog_section', // Required, core or custom.
			'label' => esc_attr__( "Use this section?", 'coni' ),
			'type'    => 'checkbox',
		) );


		

		






}
add_action( 'customize_register', 'coni_customize_register' );











/**
 * Binds JS handlers to make Theme Customizer preview reload changes asynchronously.
 */
function coni_customize_preview_js() {
	
	wp_register_script( 'coni_customizer_preview', get_template_directory_uri() . '/js/customizer-preview.js', array( 'customize-preview' ), '20151024', true );
	wp_localize_script( 'coni_customizer_preview', 'wp_customizer', array(
		'ajax_url' => admin_url( 'admin-ajax.php' ),
		'theme_url' => get_template_directory_uri(),
		'site_name' => get_bloginfo( 'name' )
	));
	wp_enqueue_script( 'coni_customizer_preview' );

}
add_action( 'customize_preview_init', 'coni_customize_preview_js' );


/**
 * Load scripts on the Customizer not the Previewer (iframe)
 */
function coni_customize_js() {

	wp_enqueue_script( 'coni_customizer_top_buttons', get_template_directory_uri() . '/js/theme-customizer-top-buttons.js', array( 'jquery' ), true  );
	wp_localize_script( 'coni_customizer_top_buttons', 'topbtns', array(
			'pro' => esc_html__( 'View PRO version', 'coni' ),
            'documentation' => esc_html__( 'Documentation', 'coni' )
	) );
	
	wp_enqueue_script( 'coni_customizer', get_template_directory_uri() . '/js/customizer.js', array( 'customize-controls' ), '20151024', true );

}
add_action( 'customize_controls_enqueue_scripts', 'coni_customize_js' );










/*
Sanitize Callbacks
*/

/**
 * Sanitize for post's categories
 */
function coni_sanitize_categories( $value ) {
    if ( ! array_key_exists( $value, coni_categories_ar() ) )
        $value = '';
    return $value;
}

/**
 * Sanitize return an non-negative Integer
 */
function coni_sanitize_integer( $value ) {
    return absint( $value );
}


/**
 * Sanitize return pro version text
 */
function coni_pro_version( $input ) {
    return $input;
}

/**
 * Sanitize Any
 */
function coni_sanitize_any( $input ) {
    return $input;
}

/**
 * Sanitize Text
 */
function coni_sanitize_text( $str ) {
	return sanitize_text_field( $str );
} 

/**
 * Sanitize Textarea
 */
function coni_sanitize_textarea( $text ) {
	return esc_textarea( $text );
}

/**
 * Sanitize URL
 */
function coni_sanitize_url( $url ) {
	return esc_url( $url );
}

/**
 * Sanitize Boolean
 */
function coni_sanitize_bool( $string ) {
	return (bool)$string;
} 

/**
 * Sanitize Text with html
 */
function coni_sanitize_text_html( $str ) {
	$args = array(
			    'a' => array(
			        'href' => array(),
			        'title' => array()
			    ),
			    'br' => array(),
			    'em' => array(),
			    'strong' => array(),
			    'span' => array(),
			);
	return wp_kses( $str, $args );
}

/**
 * Sanitize GPS Latitude and Longitud
 * http://stackoverflow.com/a/22007205
 */
function coni_sanitize_lat_long( $coords ) {
	if ( preg_match( '/^[-]?(([0-8]?[0-9])\.(\d+))|(90(\.0+)?),[-]?((((1[0-7][0-9])|([0-9]?[0-9]))\.(\d+))|180(\.0+)?)$/', $coords ) ) {
	    return $coords;
	} else {
	    return 'error';
	}
} 



/**
 * Create the "PRO version" buttons
 */
if ( ! function_exists( 'coni_pro_btns' ) ){
	function coni_pro_btns( $args ){

		$wp_customize = $args['wp_customize'];
		$title = $args['title'];
		$label = $args['label'];
		if ( isset( $args['priority'] ) || array_key_exists( 'priority', $args ) ) {
			$priority = $args['priority'];
		}else{
			$priority = 120;
		}
		if ( isset( $args['panel'] ) || array_key_exists( 'panel', $args ) ) {
			$panel = $args['panel'];
		}else{
			$panel = '';
		}

		$section_id = sanitize_title( $title );

		$wp_customize->add_section( $section_id , array(
			'title'       => $title,
			'priority'    => $priority,
			'panel' => $panel,
		) );
		$wp_customize->add_setting( $section_id, array(
			'sanitize_callback' => 'coni_pro_version'
		) );
		$wp_customize->add_control( new coni_Pro_Version( $wp_customize, $section_id, array(
	        'section' => $section_id,
	        'label' => $label
		   )
		) );
	}
}//end if function_exists

/**
 * Display Text Control
 * Custom Control to display text
 */
if ( class_exists( 'WP_Customize_Control' ) ) {
	class coni_Display_Text_Control extends WP_Customize_Control {
		/**
		* Render the control's content.
		*/
		public function render_content() {

	        $wp_kses_args = array(
			    'a' => array(
			        'href' => array(),
			        'title' => array(),
			        'data-section' => array(),
			    ),
			    'br' => array(),
			    'em' => array(),
			    'strong' => array(),
			    'span' => array(),
			);
			$label = wp_kses( $this->label, $wp_kses_args );
	        ?>
			<p><?php echo $label; ?></p>		
		<?php
		}
	}
}



/*
* AJAX call to retreive an image URI by its ID
*/
add_action( 'wp_ajax_nopriv_coni_get_image_src', 'coni_get_image_src' );
add_action( 'wp_ajax_coni_get_image_src', 'coni_get_image_src' );

function coni_get_image_src() {
	$image_id = $_POST['image_id'];
	$image = wp_get_attachment_image_src( absint( $image_id ), 'full' );
	$image = $image[0];
	echo $image;
	die();
}
