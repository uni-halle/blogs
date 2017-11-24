<?php
/**
 * Graphene General options
 *
 * @package Graphene
 * @since 2.0
 */
function graphene_customizer_general_options( $wp_customize ){

	/* =Top Bar
	--------------------------------------------------------------------------------------*/
	$wp_customize->add_section( 'graphene-general-header', array(
	  'title' 		=> __( 'Header', 'graphene' ),
	  'panel'		=> 'graphene-general',
	) );
	
	$wp_customize->add_control( 'graphene_settings[hide_top_bar]', array(
	  'type' 	=> 'checkbox',
	  'section' => 'graphene-general-header',
	  'label' 	=> __( 'Disable Top Bar', 'graphene' ),
	) );

	$wp_customize->add_control( 'graphene_settings[light_header]', array(
	  'type' 	=> 'checkbox',
	  'section' => 'graphene-general-header',
	  'label' 	=> __( 'Use light-coloured header bars', 'graphene' ),
	) );

	$wp_customize->add_control( 'graphene_settings[link_header_img]', array(
	  'type' 		=> 'checkbox',
	  'section' 	=> 'graphene-general-header',
	  'label' 		=> __( 'Link header image to front page', 'graphene' ),
	  'description'	=> __( 'Check this if you disable the header texts and want the header image to be linked to the front page.', 'graphene' )
	) );

	$wp_customize->add_control( 'graphene_settings[featured_img_header]', array(
	  'type' 		=> 'checkbox',
	  'section' 	=> 'graphene-general-header',
	  'label' 		=> __( 'Disable Featured Image replacing header image', 'graphene' ),
	  'description'	=> __( 'Check this to prevent the posts Featured Image replacing the header image regardless of the featured image dimension.', 'graphene' )
	) );

	$wp_customize->add_control( new Graphene_Enhanced_Text_Control( $wp_customize, 'graphene_settings[header_img_height]', array(
		'type' 		=> 'number',
		'section' 	=> 'graphene-general-header',
		'label' 	=> __( 'Header image max height', 'graphene' ),
		'unit'		=> 'px',
	) ) );

	$wp_customize->add_control( 'graphene_settings[search_box_location]', array(
		'type' 		=> 'radio',
		'section' 	=> 'graphene-general-header',
		'label' 	=> __( 'Search box location', 'graphene' ),
		'choices'	=> array(
			'top_bar'	=> __( 'Top bar', 'graphene' ),
			'nav_bar'	=> __( 'Navigation bar', 'graphene' ),
			'disabled'	=> __( 'Disabled', 'graphene' ),
		),
	) );


	/* =Slider
	--------------------------------------------------------------------------------------*/
	$wp_customize->add_section( 'graphene-general-slider', array(
	  'title' 		=> __( 'Slider', 'graphene' ),
	  'panel'		=> 'graphene-general',
	) );

	$wp_customize->add_control( 'graphene_settings[slider_disable]', array(
	  'type' 		=> 'checkbox',
	  'section' 	=> 'graphene-general-slider',
	  'label' 		=> __( 'Disable slider', 'graphene' ),
	) );

	$wp_customize->add_control( 'graphene_settings[slider_full_width]', array(
	  'type' 		=> 'checkbox',
	  'section' 	=> 'graphene-general-slider',
	  'label' 		=> __( 'Extend slider to full width of theme', 'graphene' ),
	) );

	$wp_customize->add_control( 'graphene_settings[slider_type]', array(
		'type' 		=> 'select',
		'section' 	=> 'graphene-general-slider',
		'label' 	=> __( 'Slider content', 'graphene' ),
		'choices'	=> array(
			'latest_posts'	=> __( 'Show latest posts', 'graphene' ),
			'random'		=> __( 'Show random posts', 'graphene' ),
			'posts_pages'	=> __( 'Show specific posts/pages', 'graphene' ),
			'categories'	=> __( 'Show posts from categories', 'graphene' ),
		),
	) );

	$wp_customize->add_control( 'graphene_settings[slider_with_image_only]', array(
	  'type' 		=> 'checkbox',
	  'section' 	=> 'graphene-general-slider',
	  'label' 		=> __( 'Include posts with featured image only', 'graphene' ),
	) );

	$wp_customize->add_control( 'graphene_settings[slider_specific_posts]', array(
	  'type' 		=> 'text',
	  'section' 	=> 'graphene-general-slider',
	  'label' 		=> __( 'Posts and/or pages to display', 'graphene' ),
	  'description'	=> __( 'Enter ID of posts and/or pages to be displayed, separated by comma.<br / >Applicable only if "Show specific posts/pages" is selected above.<br />Example: <code>1,13,45,33</code>', 'graphene' )
	) );

	$cat_choices = array();
	$categories = get_categories( array( 'hide_empty' => true ) );
    foreach ( $categories as $cat ) $cat_choices[$cat->cat_ID] = $cat->cat_name;
	
	$wp_customize->add_control( new Graphene_Multiple_Select_Control( $wp_customize, 'graphene_settings[slider_specific_categories]', array(
		'type' 		=> 'select',
		'section' 	=> 'graphene-general-slider',
		'label' 	=> __( 'Categories to display', 'graphene' ),
		'multiple'	=> true,
		'choices'	=> $cat_choices,
		'description'	=> __( 'All posts within the categories selected here will be displayed on the slider. Usage example: create a new category "Featured" and assign all posts to be displayed on the slider to that category, and then select that category here.', 'graphene' ),
		'input_attrs'	=> array(
			'data-placeholder'	=> __( 'Select categories', 'graphene' ),
		)
	) ) );

	$wp_customize->add_control( 'graphene_settings[slider_exclude_categories]', array(
	  'type' 		=> 'checkbox',
	  'section' 	=> 'graphene-general-slider',
	  'label' 		=> __( 'Exclude the categories from posts listing', 'graphene' ),
	) );

	$wp_customize->add_control( 'graphene_settings[slider_random_category_posts]', array(
	  'type' 		=> 'checkbox',
	  'section' 	=> 'graphene-general-slider',
	  'label' 		=> __( 'Show posts from categories in random order', 'graphene' ),
	) );

	$wp_customize->add_control( new Graphene_Enhanced_Text_Control( $wp_customize, 'graphene_settings[slider_postcount]', array(
		'type' 		=> 'number',
		'section' 	=> 'graphene-general-slider',
		'label' 	=> __( 'Number of posts to display', 'graphene' ),
		'unit'		=> __( 'posts', 'graphene' ),
	) ) );

	$wp_customize->add_control( 'graphene_settings[slider_img]', array(
		'type' 		=> 'select',
		'section' 	=> 'graphene-general-slider',
		'label' 	=> __( 'Slider image', 'graphene' ),
		'choices'	=> array(
			'disabled'		=> __( "Don't show image", 'graphene' ),
			'featured_image'=> __( 'Featured image', 'graphene' ),
			'post_image'	=> __( 'First image in post', 'graphene' ),
			'custom_url'	=> __( 'Custom image', 'graphene' ),
		),
	) );

	$wp_customize->add_control(	new WP_Customize_Image_Control(	$wp_customize, 'graphene_settings[slider_imgurl]', array(
    	'label'      => __( 'Custom slider image', 'graphene' ),
       	'section'    => 'graphene-general-slider',
       	'settings'   => 'graphene_settings[slider_imgurl]',
       	'description'=> __( 'Make sure you select "Custom image" in the slider image option above to use this image.', 'graphene' )
   	) )	);

   	$wp_customize->add_control( 'graphene_settings[slider_display_style]', array(
		'type' 		=> 'select',
		'section' 	=> 'graphene-general-slider',
		'label' 	=> __( 'Slider display style', 'graphene' ),
		'choices'	=> array(
			'bgimage-excerpt'	=> __( 'Background image and excerpt', 'graphene' ),
			'full-post'			=> __( 'Full post content', 'graphene' ),
		),
	) );

	$wp_customize->add_control( new Graphene_Enhanced_Text_Control( $wp_customize, 'graphene_settings[slider_height]', array(
		'type' 		=> 'number',
		'section' 	=> 'graphene-general-slider',
		'label' 	=> __( 'Slider height', 'graphene' ),
		'unit'		=> __( 'px', 'graphene' ),
	) ) );

	$wp_customize->add_control( new Graphene_Enhanced_Text_Control( $wp_customize, 'graphene_settings[slider_height_mobile]', array(
		'type' 		=> 'number',
		'section' 	=> 'graphene-general-slider',
		'label' 	=> __( 'Slider height (mobile)', 'graphene' ),
		'unit'		=> __( 'px', 'graphene' ),
	) ) );

	$wp_customize->add_control( new Graphene_Enhanced_Text_Control( $wp_customize, 'graphene_settings[slider_speed]', array(
		'type' 		=> 'number',
		'section' 	=> 'graphene-general-slider',
		'label' 	=> __( 'Slider speed', 'graphene' ),
		'unit'		=> __( 'milliseconds', 'graphene' ),
		'description'	=> __( 'This is the duration that each slider item will be shown.', 'graphene' )
	) ) );

	$wp_customize->add_control( 'graphene_settings[slider_position]', array(
	  'type' 		=> 'checkbox',
	  'section' 	=> 'graphene-general-slider',
	  'label' 		=> __( 'Move slider to bottom of page', 'graphene' ),
	) );


	/* =Infinite Scroll
	--------------------------------------------------------------------------------------*/
	$wp_customize->add_section( 'graphene-general-infinite-scroll', array(
	  'title' 		=> __( 'Infinite Scroll', 'graphene' ),
	  'panel'		=> 'graphene-general',
	) );
	
	$wp_customize->add_control( 'graphene_settings[inf_scroll_enable]', array(
	  'type' 	=> 'checkbox',
	  'section' => 'graphene-general-infinite-scroll',
	  'label' 	=> __( 'Enable for posts', 'graphene' ),
	) );

	$wp_customize->add_control( 'graphene_settings[inf_scroll_comments]', array(
	  'type' 	=> 'checkbox',
	  'section' => 'graphene-general-infinite-scroll',
	  'label' 	=> __( 'Enable for comments', 'graphene' ),
	) );

	$wp_customize->add_control( 'graphene_settings[inf_scroll_method]', array(
	  'type' 	=> 'radio',
	  'section' => 'graphene-general-infinite-scroll',
	  'label' 	=> __( 'Loading method', 'graphene' ),
	  'choices'	=> array(
			'auto'	=> __( 'Auto-load', 'graphene' ),
			'click'	=> __( 'Click-to-load', 'graphene' ),
		),
	) );


	/* =Front Page
	--------------------------------------------------------------------------------------*/
	$wp_customize->add_section( 'graphene-general-front-page', array(
	  'title' 		=> __( 'Front Page', 'graphene' ),
	  'panel'		=> 'graphene-general',
	) );

	$wp_customize->add_control( new Graphene_Multiple_Select_Control( $wp_customize, 'graphene_settings[frontpage_posts_cats]', array(
		'type' 		=> 'select',
		'section' 	=> 'graphene-general-front-page',
		'label' 	=> __( 'Front page posts categories', 'graphene' ),
		'multiple'	=> true,
		'choices'	=> $cat_choices,
		'description'	=> __( 'Only posts that belong to the categories selected here will be displayed on the front page. Works only if not using Static Front Page.', 'graphene' ),
		'input_attrs'	=> array(
			'data-placeholder'	=> __( 'Select categories', 'graphene' ),
		)
	) ) );


	/* =Home Page Panes
	--------------------------------------------------------------------------------------*/
	$wp_customize->add_section( 'graphene-general-home-page-panes', array(
	  'title' 		=> __( 'Home Page Panes', 'graphene' ),
	  'panel'		=> 'graphene-general',
	) );

	$wp_customize->add_control( 'graphene_settings[disable_homepage_panes]', array(
	  'type' 	=> 'checkbox',
	  'section' => 'graphene-general-home-page-panes',
	  'label' 	=> __( 'Disable homepage panes', 'graphene' ),
	) );

	$wp_customize->add_control( 'graphene_settings[show_post_type]', array(
		'type' 		=> 'select',
		'section' 	=> 'graphene-general-home-page-panes',
		'label' 	=> __( 'Type of content to show', 'graphene' ),
		'choices'	=> array(
			'latest-posts'	=> __( 'Latest posts', 'graphene' ),
			'posts'			=> __( 'Show specific posts/pages', 'graphene' ),
		),
	) );

	$wp_customize->add_control( 'graphene_settings[homepage_panes_count]', array(
	  'type' 		=> 'number',
	  'section' 	=> 'graphene-general-home-page-panes',
	  'label' 		=> __( 'Number of posts', 'graphene' ),
	) );

	$wp_customize->add_control( new Graphene_Multiple_Select_Control( $wp_customize, 'graphene_settings[homepage_panes_cat]', array(
		'type' 		=> 'select',
		'section' 	=> 'graphene-general-home-page-panes',
		'label' 	=> __( 'Categories to show latest posts from', 'graphene' ),
		'multiple'	=> true,
		'choices'	=> $cat_choices,
		'description'	=> __( 'Only posts that belong to the categories selected here will be displayed in the home page panes.', 'graphene' ),
		'input_attrs'	=> array(
			'data-placeholder'	=> __( 'Select categories', 'graphene' ),
		)
	) ) );

	$wp_customize->add_control( 'graphene_settings[homepage_panes_posts]', array(
	  'type' 		=> 'text',
	  'section' 	=> 'graphene-general-home-page-panes',
	  'label' 		=> __( 'Posts and/or pages to display', 'graphene' ),
	  'description'	=> __( 'Enter ID of posts and/or pages to be displayed, separated by comma.<br / >Applicable only if "Show specific posts/pages" is selected above.<br />Example: <code>1,13,45,33</code>', 'graphene' )
	) );


	/* =Comments
	--------------------------------------------------------------------------------------*/
	$wp_customize->add_section( 'graphene-general-comments', array(
	  'title' 		=> __( 'Comments', 'graphene' ),
	  'panel'		=> 'graphene-general',
	) );

	$wp_customize->add_control( 'graphene_settings[comments_setting]', array(
		'type' 			=> 'select',
		'section' 		=> 'graphene-general-comments',
		'label' 		=> __( 'Commenting', 'graphene' ),
		'description'	=> __( 'Overrides the global WordPress Discussion Setting called "Allow people to post comments on new articles" and also the "Allow comments" option for individual posts/pages.', 'graphene' ),
		'choices'		=> array(
			'wordpress'				=> __( 'Use WordPress settings', 'graphene' ),
			'disabled_pages'		=> __( 'Disable for pages', 'graphene' ),
			'disabled_completely'	=> __( 'Disable completely', 'graphene' ),
		),
	) );


	/* =Child pages
	--------------------------------------------------------------------------------------*/
	$wp_customize->add_section( 'graphene-general-child-pages', array(
	  'title' 		=> __( 'Child Pages', 'graphene' ),
	  'panel'		=> 'graphene-general',
	) );

	$wp_customize->add_control( 'graphene_settings[hide_parent_content_if_empty]', array(
	  'type' 	=> 'checkbox',
	  'section' => 'graphene-general-child-pages',
	  'label' 	=> __( 'Hide parent box if content is empty', 'graphene' ),
	) );

	$wp_customize->add_control( 'graphene_settings[disable_child_pages_nav]', array(
	  'type' 	=> 'checkbox',
	  'section' => 'graphene-general-child-pages',
	  'label' 	=> __( 'Disable contextual navigation in the sidebar', 'graphene' ),
	) );

	$wp_customize->add_control( 'graphene_settings[child_page_listing]', array(
		'type' 			=> 'select',
		'section' 		=> 'graphene-general-child-pages',
		'label' 		=> __( 'Child page listings', 'graphene' ),
		'choices'		=> array(
			'show_always'			=> __( 'Show listing', 'graphene' ),
			'hode'					=> __( 'Hide listing', 'graphene' ),
			'show_if_parent_empty'	=> __( 'Show only if parent is empty', 'graphene' ),
		),
	) );


	/* =Widget Area
	--------------------------------------------------------------------------------------*/
	$wp_customize->add_section( 'graphene-general-widget-areas', array(
	  'title' 		=> __( 'Widget Areas', 'graphene' ),
	  'panel'		=> 'graphene-general',
	) );

	$wp_customize->add_control( 'graphene_settings[enable_header_widget]', array(
	  'type' 	=> 'checkbox',
	  'section' => 'graphene-general-widget-areas',
	  'label' 	=> __( 'Enable header widget area', 'graphene' ),
	  'description'	=> __( '<strong>Important:</strong> This widget area is unstyled, as it is often used for advertisement banners, etc. If you enable it, make sure you style it to your needs using the Custom CSS option.', 'graphene' ),
	) );

	$wp_customize->add_control( 'graphene_settings[alt_home_sidebar]', array(
	  'type' 	=> 'checkbox',
	  'section' => 'graphene-general-widget-areas',
	  'label'	=> __( 'Enable alternative sidebar widget area in front page.', 'graphene' ),
	) );

	$wp_customize->add_control( 'graphene_settings[alt_home_footerwidget]', array(
	  'type' 	=> 'checkbox',
	  'section' => 'graphene-general-widget-areas',
	  'label' 	=> __( 'Enable alternative footer widget area in front page.', 'graphene' ),
	) );

	$wp_customize->add_control( 'graphene_settings[footerwidget_column]', array(
	  'type' 		=> 'text',
	  'section' 	=> 'graphene-general-widget-areas',
	  'label' 		=> __( 'Divide footer widget area into this number of columns', 'graphene' ),
	) );

	$wp_customize->add_control( 'graphene_settings[alt_footerwidget_column]', array(
	  'type' 		=> 'text',
	  'section' 	=> 'graphene-general-widget-areas',
	  'label' 		=> __( 'Divide alternative footer widget area into this number of columns', 'graphene' ),
	) );


	/* =Social Profiles
	--------------------------------------------------------------------------------------*/
	$wp_customize->add_section( 'graphene-general-social-profiles', array(
	  'title' 		=> __( 'Social Profiles', 'graphene' ),
	  'panel'		=> 'graphene-general',
	) );

	$wp_customize->add_control( 'graphene_settings[social_media_new_window]', array(
	  'type' 	=> 'checkbox',
	  'section' => 'graphene-general-social-profiles',
	  'label' 	=> __( 'Open social media links in new window', 'graphene' ),
	) );

	$wp_customize->add_control( new Graphene_Social_Profiles_Control( $wp_customize, 'graphene_settings[social_profiles]', array(
		'section' 	=> 'graphene-general-social-profiles',
		'label' 	=> __( 'Social profiles', 'graphene' ),
	) ) );


	/* =Social Sharing
	--------------------------------------------------------------------------------------*/
	$wp_customize->add_section( 'graphene-general-social', array(
	  'title' 		=> __( 'Social Sharing', 'graphene' ),
	  'panel'		=> 'graphene-general',
	) );

	$wp_customize->add_control( 'graphene_settings[show_addthis]', array(
	  'type' 	=> 'checkbox',
	  'section' => 'graphene-general-social',
	  'label' 	=>  __( 'Show social sharing buttons', 'graphene' )
	) );

	$wp_customize->add_control( 'graphene_settings[show_addthis_page]', array(
	  'type' 	=> 'checkbox',
	  'section' => 'graphene-general-social',
	  'label' 	=> __( 'Include static Pages', 'graphene' ),
	) );

	$wp_customize->add_control( 'graphene_settings[show_addthis_archive]', array(
	  'type' 	=> 'checkbox',
	  'section' => 'graphene-general-social',
	  'label' 	=> __( 'Include home and archive pages', 'graphene' ),
	) );

	$wp_customize->add_control( 'graphene_settings[addthis_location]', array(
		'type' 			=> 'select',
		'section' 		=> 'graphene-general-social',
		'label' 		=> __( 'Buttons location', 'graphene' ),
		'choices'		=> array(
			'post-bottom'	=> __( 'Bottom of posts', 'graphene' ),
			'post-top'		=> __( 'Top of posts', 'graphene' ),
			'top-bottom'	=> __( 'Top and bottom of posts', 'graphene' ),
		),
	) );

	$wp_customize->add_control( new Graphene_Code_Control( $wp_customize, 'graphene_settings[addthis_code]', array(
		'type' 		=> 'textarea',
		'section' 	=> 'graphene-general-social',
		'label' 	=> __( 'Social sharing buttons code', 'graphene' ),
		'description'	=>  __( 'You can use codes from any popular social sharing sites, like Facebook, Digg, AddThis, etc.', 'graphene' ),
		'input_attrs'	=> array(
			'rows'	=> 3,
			'cols'	=> 60
		)
	) ) );


	/* =Adsense
	--------------------------------------------------------------------------------------*/
	$wp_customize->add_section( 'graphene-general-adsense', array(
	  'title' 		=> __( 'Ads', 'graphene' ),
	  'panel'		=> 'graphene-general',
	) );

	$wp_customize->add_control( 'graphene_settings[show_adsense]', array(
	  'type' 	=> 'checkbox',
	  'section' => 'graphene-general-adsense',
	  'label' 	=>  __( 'Show ads', 'graphene' )
	) );

	$wp_customize->add_control( 'graphene_settings[adsense_show_frontpage]', array(
	  'type' 	=> 'checkbox',
	  'section' => 'graphene-general-adsense',
	  'label' 	=>  __( 'Include Front Page', 'graphene' )
	) );	

	$wp_customize->add_control( new Graphene_Code_Control( $wp_customize, 'graphene_settings[adsense_code]', array(
		'type' 		=> 'textarea',
		'section' 	=> 'graphene-general-adsense',
		'label' 	=> __( 'Ads code', 'graphene' ),
		'input_attrs'	=> array(
			'rows'	=> 3,
			'cols'	=> 60
		)
	) ) );


	/* =Analytics
	--------------------------------------------------------------------------------------*/
	$wp_customize->add_section( 'graphene-general-analytics', array(
	  'title' 		=> __( 'Analytics', 'graphene' ),
	  'panel'		=> 'graphene-general',
	) );

	$wp_customize->add_control( 'graphene_settings[show_ga]', array(
	  'type' 	=> 'checkbox',
	  'section' => 'graphene-general-analytics',
	  'label' 	=>  __( 'Enable analytics tracking', 'graphene' )
	) );

	$wp_customize->add_control( new Graphene_Code_Control( $wp_customize, 'graphene_settings[ga_code]', array(
		'type' 		=> 'textarea',
		'section' 	=> 'graphene-general-analytics',
		'label' 	=> __( 'Analytics code', 'graphene' ),
		'description'	=> sprintf( __( 'The analytics code will be added inside the %s element of all pages in this site.', 'graphene' ), '&lt;head&gt;' ),
		'input_attrs'	=> array(
			'rows'	=> 3,
			'cols'	=> 60
		)
	) ) );


	/* =Footer
	--------------------------------------------------------------------------------------*/
	$wp_customize->add_section( 'graphene-general-footer', array(
	  'title' 		=> __( 'Footer', 'graphene' ),
	  'panel'		=> 'graphene-general',
	) );

	$wp_customize->add_control( new Graphene_Code_Control( $wp_customize, 'graphene_settings[copy_text]', array(
		'type' 		=> 'textarea',
		'section' 	=> 'graphene-general-footer',
		'label' 	=> __( 'Copyright text (html allowed)', 'graphene' ),
		'input_attrs'	=> array(
			'rows'	=> 3,
			'cols'	=> 60
		)
	) ) );

	$wp_customize->add_control( 'graphene_settings[hide_copyright]', array(
	  'type' 	=> 'checkbox',
	  'section' => 'graphene-general-footer',
	  'label' 	=> __( 'Do not show copyright info', 'graphene' ),
	) );

	$wp_customize->add_control( 'graphene_settings[hide_return_top]', array(
	  'type' 	=> 'checkbox',
	  'section' => 'graphene-general-footer',
	  'label' 	=> __( 'Disable "Return to top" button', 'graphene' ),
	) );


	/* =Print
	--------------------------------------------------------------------------------------*/
	$wp_customize->add_section( 'graphene-general-print', array(
	  'title' 		=> __( 'Print', 'graphene' ),
	  'panel'		=> 'graphene-general',
	) );

	$wp_customize->add_control( 'graphene_settings[print_css]', array(
	  'type' 	=> 'checkbox',
	  'section' => 'graphene-general-print',
	  'label' 	=> __( 'Clean up single posts and pages when printing', 'graphene' ),
	) );

	$wp_customize->add_control( 'graphene_settings[print_button]', array(
	  'type' 	=> 'checkbox',
	  'section' => 'graphene-general-print',
	  'label' 	=> __( 'Show print button', 'graphene' ),
	) );
}