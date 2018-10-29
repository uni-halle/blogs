<?php

class inkthemes_Customizer {

    public static function inkthemes_Register($wp_customize) {

        self::inkthemes_Sections($wp_customize);

        self::inkthemes_Controls($wp_customize);
    }

    public static function inkthemes_Sections($wp_customize) {


        $wp_customize->register_panel_type('CW_WP_Customize_Panel');

        $cw_parent_setting_panel = new CW_WP_Customize_Panel($wp_customize, 'cw_parent_setting_panel', array(
            'title' => 'ColorWay Homepage Setting',
            'priority' => 5,
        ));
        $wp_customize->add_panel($cw_parent_setting_panel);

        $home_page_slider_panel = new CW_WP_Customize_Panel($wp_customize, 'home_page_slider_panel', array(
            'title' => 'Slider Setting',
            'panel' => 'cw_parent_setting_panel',
        ));

        $wp_customize->add_panel($home_page_slider_panel);


        $home_feature_area_panel = new CW_WP_Customize_Panel($wp_customize, 'home_feature_area_panel', array(
            'title' => 'Feature Area Setting',
            'panel' => 'cw_parent_setting_panel',
        ));
        $wp_customize->add_panel($home_feature_area_panel);

        $home_middle_area_panel = new CW_WP_Customize_Panel($wp_customize, 'home_middle_area_panel', array(
            'title' => 'Middle Area Setting',
            'panel' => 'cw_parent_setting_panel',
        ));
        $wp_customize->add_panel($home_middle_area_panel);

        $home_testimonial_area_panel = new CW_WP_Customize_Panel($wp_customize, 'home_testimonial_area_panel', array(
            'title' => 'Testimonial Area Setting',
            'panel' => 'cw_parent_setting_panel',
        ));
        $wp_customize->add_panel($home_testimonial_area_panel);

        /**
         * Add panel for home page feature area
         */
        $wp_customize->add_panel('general_setting_panel', array(
            'title' => __('General Settings', 'colorway'),
            'description' => __('Allows you to setup home page feature area section for ColorWay Theme.', 'colorway'),
            'priority' => '1',
            'capability' => 'edit_theme_options'
        ));
        /**
         * Add panel for home page feature area
         */
        $wp_customize->add_panel('layout_setting_panel', array(
            'title' => __('Layout Settings', 'colorway'),
            'description' => __('Allows you to setup Layouts for ColorWay Theme.', 'colorway'),
            'priority' => '2',
            'capability' => 'edit_theme_options'
        ));
        /**
         * HomePage setting section
         */
//        $wp_customize->add_section('static_front_page', array(
//            'title' => __('Homepage settings', 'colorway'),
//            'description' => __('Allows you to setup Static Home Page for ColorWay Theme.', 'colorway'),
//            'priority' => '',
//            'capability' => 'edit_theme_options'
//        ));

        /**
         * Container Layout Section
         */
        $wp_customize->add_section('nav_menus_button', array(
            'title' => __('Custom Menu Text/Html', 'colorway'),
            'priority' => '',
            'panel' => 'nav_menus',
        ));
        /**
         * Container Layout Section
         */
        $wp_customize->add_section('menu_button_css', array(
            'title' => __('Custom Menu Button Style', 'colorway'),
            'priority' => '',
            'panel' => 'nav_menus'
        ));
        /**
         * Container Layout Section
         */
        $wp_customize->add_section('container_layout_section', array(
            'title' => __('Container Layout', 'colorway'),
            'priority' => '1',
            'description' => __('Allows you to setup Container Layout for ColorWay Theme.', 'colorway'),
            'panel' => 'layout_setting_panel'
        ));
        /**
         * Header Layout Section
         */
        $wp_customize->add_section('header_layout_section', array(
            'title' => __('Header Layout', 'colorway'),
            'priority' => '2',
            'description' => __('Allows you to setup Header Layout for ColorWay Theme.', 'colorway'),
            'panel' => 'layout_setting_panel'
        ));
        /**
         * Blog/ Archives Layout Section
         */
        $wp_customize->add_section('blog_layout_section', array(
            'title' => __('Blog/ Archives/ Category Layout', 'colorway'),
            'priority' => '3',
            'description' => __('Allows you to setup Blog, Archives, Category and Tag Layouts for ColorWay Theme.', 'colorway'),
            'panel' => 'layout_setting_panel'
        ));
        /**
         * Page Layout Section
         */
        $wp_customize->add_section('page_layout_section', array(
            'title' => __('Page Layout', 'colorway'),
            'priority' => '4',
            'description' => __('Allows you to setup Page Layout for ColorWay Theme.', 'colorway'),
            'panel' => 'layout_setting_panel'
        ));
        /**
         * Page Layout Section
         */
        $wp_customize->add_section('singlepage_layout_section', array(
            'title' => __('Single Post Layout', 'colorway'),
            'priority' => '5',
            'description' => __('Allows you to setup Single Post Layout for ColorWay Theme.', 'colorway'),
            'panel' => 'layout_setting_panel'
        ));

        /**
         * Layout Settings
         */
        $wp_customize->add_section('footer_col_layout', array(
            'title' => __('Footer Widget Layout', 'colorway'),
            'description' => sprintf(__("<b>You Can Customize Your Widget Area By Following These Steps</b><br/><br/><ul><li>Go To Dashboard->Appearance->Widget.</li><br/><li> Place your desired Widgets under the Footer Widget Areas (First, Second, Third, etc.) they will appear on the footer of your site. </li><br/><li> If you want to hide the footer widget contents then, just place a Blank Text Widget under Footer Widget Areas. </li><br/><li> You can also refer this tutorial link: <a href='https://www.inkthemes.com/how-to-use-widgets-in-wordpress/'>How To Use Widgets In Wordpress</a></li><br/><br/> Allows you to turn on and off Footer Area Widgets for ColorWay Theme.", 'colorway')),
            'priority' => '6',
            'panel' => 'layout_setting_panel'
        ));
        /**
         * Footer Layout Section
         */
        $wp_customize->add_section('footer_layout_section', array(
            'title' => __('Bottom Footer Layout', 'colorway'),
            'priority' => '7',
            'description' => __('Allows you to setup Bottom Footer Layout for ColorWay Theme.', 'colorway'),
            'panel' => 'layout_setting_panel'
        ));
        /**
         * Site Title Section
         */
        $wp_customize->add_section('title_tagline', array(
            'title' => __('Site Title, Tagline & icon', 'colorway'),
            'priority' => '',
            'panel' => 'general_setting_panel'
        ));

        /**
         * Remove control for site icon
         */
        //$wp_customize->remove_control('site_icon');

        /**
         * Logo and favicon section
         */
        $wp_customize->add_section('logo_fevi_setting', array(
            'title' => __('Logo', 'colorway'),
            'description' => __('Allows you to customize header logo, favicon settings for ColorWay Theme.', 'colorway'), //Descriptive tooltip
            'panel' => 'general_setting_panel',
            'priority' => '',
            'capability' => 'edit_theme_options'
                )
        );

        /**
         * Tracking Code Section
         */
        $wp_customize->add_section('tracking_code_setting', array(
            'title' => __('Tracking Code', 'colorway'),
            'description' => __('Paste your Google Analytics (or other) tracking code here.', 'colorway'), //Descriptive tooltip
            'panel' => 'general_setting_panel',
            'priority' => '',
            'capability' => 'edit_theme_options'
                )
        );

        /**
         * Dummy Data Section
         */
        $wp_customize->add_section('dummy_data_setting', array(
            'title' => __('Dummy Data', 'colorway'),
            'description' => __('Allows you to Enable or Disable Dummy Data for ColorWay Theme.', 'colorway'), //Descriptive tooltip
            'panel' => 'general_setting_panel',
            'priority' => '',
            'capability' => 'edit_theme_options'
                )
        );

        /**
         * Home page slider panel
         */
//        $wp_customize->add_panel('home_page_slider_panel', array(
//            'title' => __('Slider Settings', 'colorway'),
//            'description' => __('Allows you to setup home page slider for ColorWay Theme.', 'colorway'),
//            'priority' => '11',
//            'capability' => 'edit_theme_options'
//        ));

        /**
         * Slider control section
         */
        $wp_customize->add_section('home_page_slider_control', array(
            'title' => __('Slider Control', 'colorway'),
            'description' => __('Turn on or off the home page Slider as per your requirement.', 'colorway'), //Descriptive tooltip
            'panel' => 'home_page_slider_panel',
            'priority' => '',
            'capability' => 'edit_theme_options'
                )
        );

        /**
         * First Slider section
         */
        $wp_customize->add_section('home_page_slider_1', array(
            'title' => __('First Slider', 'colorway'),
            'description' => __('Allows you to setup first slider for ColorWay Theme.', 'colorway'), //Descriptive tooltip
            'panel' => 'home_page_slider_panel',
            'priority' => '',
            'capability' => 'edit_theme_options'
                )
        );

        /**
         * Second Slider section
         */
        $wp_customize->add_section('home_page_slider_2', array(
            'title' => __('Second Slider', 'colorway'),
            'description' => __('Allows you to setup second slider for ColorWay Theme.', 'colorway'), //Descriptive tooltip
            'panel' => 'home_page_slider_panel',
            'priority' => '',
            'capability' => 'edit_theme_options'
                )
        );

        /**
         * Home page main heading
         */
        $wp_customize->add_section('home_page_main_heading', array(
            'title' => __('Main Heading Setting', 'colorway'),
            'description' => __('Allows you to setup mani heading for ColorWay Theme.', 'colorway'), //Descriptive tooltip
            'panel' => 'cw_parent_setting_panel',
            'priority' => '12',
            'capability' => 'edit_theme_options'
                )
        );

        /**
         * Home Page Feature area panel
         */
//        $wp_customize->add_panel('home_feature_area_panel', array(
//            'title' => __('Feature Area Settings', 'colorway'),
//            'description' => __('Allows you to setup home page feature area section for ColorWay Theme.', 'colorway'),
//            'priority' => '13',
//            'capability' => 'edit_theme_options'
//        ));

        /**
         * Home Page feature select
         */
        $wp_customize->add_section('home_feature_area_select', array(
            'title' => __('Feature Area Control', 'colorway'),
            'description' => __("Feature Area Control", 'colorway'),
            'panel' => 'home_feature_area_panel',
            'priority' => '1',
            'capability' => 'edit_theme_options'
                )
        );
        /**
         * Home Page feature area 1
         */
        $wp_customize->add_section('home_feature_area_1', array(
            'title' => __('First Feature Area', 'colorway'),
            'description' => __('Allows you to setup first feature area section for ColorWay Theme.', 'colorway'),
            'panel' => 'home_feature_area_panel',
            'priority' => '2',
            'capability' => 'edit_theme_options'
                )
        );

        /**
         * Home Page feature area 2
         */
        $wp_customize->add_section('home_feature_area_2', array(
            'title' => __('Second Feature Area', 'colorway'),
            'description' => __('Allows you to setup second feature area section for ColorWay Theme.', 'colorway'),
            'panel' => 'home_feature_area_panel',
            'priority' => '3',
            'capability' => 'edit_theme_options'
                )
        );

        /**
         * Home Page feature area 3
         */
        $wp_customize->add_section('home_feature_area_3', array(
            'title' => __('Third Feature Area', 'colorway'),
            'description' => __('Allows you to setup third feature area section for ColorWay Theme.', 'colorway'),
            'panel' => 'home_feature_area_panel',
            'priority' => '4',
            'capability' => 'edit_theme_options'
                )
        );

        /**
         * Home Page feature area 4
         */
        $wp_customize->add_section('home_feature_area_4', array(
            'title' => __('Fourth Feature Area', 'colorway'),
            'description' => __('Allows you to setup fourth feature area section for ColorWay Theme.', 'colorway'),
            'panel' => 'home_feature_area_panel',
            'priority' => '5',
            'capability' => 'edit_theme_options'
                )
        );

//        $wp_customize->remove_control('display_header_text');
        $wp_customize->remove_control('header_textcolor');


        /**
         * Home Page Middle area panel
         */
//        $wp_customize->add_panel('home_middle_area_panel', array(
//            'title' => __('Middle Area Settings', 'colorway'),
//            'description' => __('Allows you to setup home page middle area section for ColorWay Theme.', 'colorway'),
//            'priority' => '14',
//            'capability' => 'edit_theme_options'
//        ));

        /*
         * home page middle area control section
         */
        $wp_customize->add_section('home_middle_area_control', array(
            'title' => __('Middle Area Control', 'colorway'),
            'description' => __('Allows you to setup home page middle area control for ColorWay Theme.', 'colorway'),
            'panel' => 'home_middle_area_panel',
            'priority' => '',
            'capability' => 'edit_theme_options'
                )
        );
        /*
         * home page middle area left section
         */
        $wp_customize->add_section('home_middle_area_left', array(
            'title' => __('Home Page Left Widget Heading', 'colorway'),
            'description' => __('Allows you to setup home page left widget heading for ColorWay Theme.', 'colorway'),
            'panel' => 'home_middle_area_panel',
            'priority' => '',
            'capability' => 'edit_theme_options'
                )
        );

        /*
         * home page middle area right section
         */
        $wp_customize->add_section('home_middle_area_right', array(
            'title' => __('Home Page Blog Area', 'colorway'),
            'description' => __('Allows you to setup home page blog area for ColorWay Theme.', 'colorway'),
            'panel' => 'home_middle_area_panel',
            'priority' => '',
            'capability' => 'edit_theme_options'
                )
        );

        /**
         * Home Page Testimonial Area panel
         */
//        $wp_customize->add_panel('home_testimonial_area_panel', array(
//            'title' => __('Testimonial Area Settings', 'colorway'),
//            'description' => __('Allows you to setup home page testimonial area for ColorWay Theme.', 'colorway'),
//            'priority' => '15',
//            'capability' => 'edit_theme_options'
//        ));

        /*
         * Home page testimonial area control section
         */
        $wp_customize->add_section('home_testimonial_control', array(
            'title' => __('Testimonial Control', 'colorway'),
            'description' => __('Allows you to control testimonial section for ColorWay Theme.', 'colorway'),
            'priority' => '',
            'panel' => 'home_testimonial_area_panel'
        ));

        /*
         * Home page first testimonial section
         */
        $wp_customize->add_section('home_testimonial_1', array(
            'title' => __('First Testimonial', 'colorway'),
            'description' => __('Allows you to setup first testimonial section for ColorWay Theme.', 'colorway'),
            'priority' => '',
            'panel' => 'home_testimonial_area_panel'
        ));

        /*
         * Home page second testimonial section
         */
        $wp_customize->add_section('home_testimonial_2', array(
            'title' => __('Second Testimonial', 'colorway'),
            'description' => __('Allows you to setup second testimonial section for ColorWay Theme.', 'colorway'),
            'priority' => '',
            'panel' => 'home_testimonial_area_panel'
        ));

        /*
         * Home page third testimonial section
         */
        $wp_customize->add_section('home_testimonial_3', array(
            'title' => __('Third Testimonial', 'colorway'),
            'description' => __('Allows you to setup third testimonial section for ColorWay Theme.', 'colorway'),
            'priority' => '',
            'panel' => 'home_testimonial_area_panel'
        ));

        /**
         * Footer Setting
         */
        $wp_customize->add_section('footer_section', array(
            'title' => __('Social Icons', 'colorway'),
            'description' => __('Allows you to setup footer social icon for ColorWay Theme.', 'colorway'),
            'priority' => '16',
            'panel' => ''
        ));
        /**
         * Background and Color
         */
        $wp_customize->add_panel('color_section', array(
            'title' => __('Colors and Backgrounds ', 'colorway'),
            'description' => __("Allows you to set Background and Colors for ColorWay Theme.", 'colorway'),
            'priority' => '3',
        ));
        /**
         * Header color Section
         */
        $wp_customize->add_section('header_colors_section', array(
            'title' => __('Header Color', 'colorway'),
            'description' => __('Allows you to change Header color for ColorWay Theme.', 'colorway'),
            'priority' => '1',
            'panel' => 'color_section'
        ));
        /**
         * Content Area Color Section
         */
        $wp_customize->add_section('theme_color_section', array(
            'title' => __('Content Area Color', 'colorway'),
            'description' => __("Allows you to set Content Area Color for ColorWay Theme.", 'colorway'),
            'priority' => '2',
            'panel' => 'color_section'
        ));
        /**
         * Footer Color Section
         */
        $wp_customize->add_section('footer_color_section', array(
            'title' => __('Footer Color', 'colorway'),
            'description' => __("Allows you to set Footer Color for ColorWay Theme.", 'colorway'),
            'priority' => '3',
            'panel' => 'color_section'
        ));

        /**
         * Background Image Section
         */
        $wp_customize->add_section('background_image', array(
            'title' => __('Body Background Image', 'colorway'),
            'description' => __('Allows you to change Body Background Image for ColorWay Theme. This will overright the Body Background Color property.', 'colorway'),
            'priority' => '4',
            'panel' => 'color_section'
        ));
        /**
         * footer Image Section
         */
        $wp_customize->add_section('footer_bgimage', array(
            'title' => __('Footer Background Image', 'colorway'),
            'description' => __('Allows you to change Footer Background Image for ColorWay Theme. This will overright the Footer Background Color property.', 'colorway'),
            'priority' => '4',
            'panel' => 'color_section'
        ));
        /**
         * Background Image Section
         */
        $wp_customize->add_section('colors', array(
            'title' => __('Body Background Color', 'colorway'),
            'description' => __('Allows you to change Body Background Color for ColorWay Theme.', 'colorway'),
            'priority' => '4',
            'panel' => 'color_section'
        ));
        /**
         * Header Image Section
         */
        $wp_customize->add_section('header_image', array(
            'title' => __('Header Image', 'colorway'),
            'description' => __('Allows you to change Header image for ColorWay Theme.', 'colorway'),
            'priority' => '5',
            'panel' => 'color_section'
        ));

        /**
         * Typography
         */
        $wp_customize->add_panel('typography_section', array(
            'title' => __('Typography', 'colorway'),
            'description' => __("Allows you to set Font Styling for ColorWay Theme.", 'colorway'),
            'priority' => '4',
        ));
        /**
         * Typography font family
         */
        $wp_customize->add_section('typography_font_family', array(
            'title' => __('Font Family', 'colorway'),
            'description' => __("Allows you to set Font Family for ColorWay Theme.", 'colorway'),
            'priority' => '3',
            'panel' => 'typography_section'
        ));
        /**
         * Typography font weight
         */
        $wp_customize->add_section('typography_font_weight', array(
            'title' => __('Font Weight', 'colorway'),
            'description' => __("Allows you to set Font Weight for ColorWay Theme.", 'colorway'),
            'priority' => '3',
            'panel' => 'typography_section'
        ));
        /**
         * Typography font Size
         */
        $wp_customize->add_section('typography_font_size', array(
            'title' => __('Font Size', 'colorway'),
            'description' => __("Allows you to set Font size for ColorWay Theme.", 'colorway'),
            'priority' => '3',
            'panel' => 'typography_section'
        ));
    }

    public static function inkthemes_Section_Content() {

        $section_content = array(
            'logo_fevi_setting' => array(
                 'logo_option',
                'colorway_logo',
               
            ),
            'blog_layout_section' => array(
                'inkthemes_date',
                'inkthemes_author',
                'inkthemes_categories',
                'inkthemes_comments',
            ),
            'nav_menus_button' => array(
                'colorway_button_html',
                'btn_on_off'
            ),
            'menu_button_css' => array(
                'button_link_color',
                'button_link_hover_color',
                'button_bg_color',
                'button_bg_hover_color',
            ),
            'singlepage_layout_section' => array(
                'singlepage_date',
                'singlepage_author',
                'singlepage_categories',
                'singlepage_comments',
            ),
//            'title_tagline' => array(
//                'display_header_text',
//                'display_header_description'
//            ),
//            'static_front_page' => array(
//                'classic_homepage'
//            ),
            'tracking_code_setting' => array(
                'colorway_analytics'
            ),
            'dummy_data_setting' => array(
                'colorway_dummy_data'
            ),
            'header_layout_section' => array(
                'header-layout',
                'header_v_pad',
                'border_bottom_on_off',
                'colorway_sticky_header',
                'content_v_pad',
                 'sticky_logo_setting',
                'stky_logo_width'
            ),
            'typography_font_family' => array(
                'typography_logo_family',
                'typography_menu_family',
                'typography_nav_family',
                'typography_para',
                'typography_heading1',
                'typography_heading2',
                'typography_heading3',
                'typography_heading4',
                'typography_heading5',
                'typography_heading6',
            ),
            'typography_font_weight' => array(
                'typography_title_fontweight',
                'typography_tagline_fontweight',
                'typography_fontweight_navmenu',
                'typography_fontweight_para',
                'typography_fontweight_heading1',
                'typography_fontweight_heading2',
                'typography_fontweight_heading3',
                'typography_fontweight_heading4',
                'typography_fontweight_heading5',
                'typography_fontweight_heading6',
            ),
            'typography_font_size' => array(
                'typography_menu_fontsize',
                'typography_btn_fontsize',
                'typography_fontsize_para',
                'typography_fontsize_heading1',
                'typography_fontsize_heading2',
                'typography_fontsize_heading3',
                'typography_fontsize_heading4',
                'typography_fontsize_heading5',
                'typography_fontsize_heading6',
                'footer_fontsize_para',
                'footer_fontsize_link',
            ),
            'home_page_slider_control' => array(
                'colorway_home_page_slider'
            ),
            'home_page_slider_1' => array(
                'colorway_slideimage1',
                'colorway_slideheading1',
                'colorway_slidedescription1',
                'colorway_slidelink1'
            ),
            'home_page_slider_2' => array(
                'colorway_slideimage2',
                'colorway_slideheading2',
                'colorway_slidedescription2',
                'colorway_slidelink2'
            ),
            'home_page_main_heading' => array(
                'inkthemes_mainheading'
            ),
            'home_feature_area_1' => array(
                'inkthemes_fimg1',
                'inkthemes_headline1',
                'inkthemes_feature1',
                'inkthemes_link1'
            ),
            'home_feature_area_2' => array(
                'inkthemes_fimg2',
                'inkthemes_headline2',
                'inkthemes_feature2',
                'inkthemes_link2'
            ),
            'home_feature_area_3' => array(
                'inkthemes_fimg3',
                'inkthemes_headline3',
                'inkthemes_feature3',
                'inkthemes_link3'
            ),
            'home_feature_area_4' => array(
                'inkthemes_fimg4',
                'inkthemes_headline4',
                'inkthemes_feature4',
                'inkthemes_link4'
            ),
            'home_feature_area_select' => array(
                'feature_on_off',
                'colorway_feature_select'
            ),
            'home_middle_area_control' => array(
                'colorway_home_page_blog_post'
            ),
            'home_middle_area_left' => array(
                'inkthemes_col_head'
            ),
            'home_middle_area_right' => array(
                'inkthemes_blog_head',
                'inkthemes_blog_posts'
            ),
            'home_testimonial_control' => array(
                'colorway_testimonial_status',
                'inkthemes_testimonial_main_head',
                'inkthemes_testimonial_main_desc'
            ),
            'home_testimonial_1' => array(
                'inkthemes_testimonial_img',
                'inkthemes_testimonial_name',
                'inkthemes_testimonial'
            ),
            'home_testimonial_2' => array(
                'inkthemes_testimonial_img_2',
                'inkthemes_testimonial_name_2',
                'inkthemes_testimonial_2'
            ),
            'home_testimonial_3' => array(
                'inkthemes_testimonial_img_3',
                'inkthemes_testimonial_name_3',
                'inkthemes_testimonial_3'
            ),
            'footer_section' => array(
                'colorway_facebook',
                'colorway_twitter',
                'colorway_rss',
                'colorway_linkedin',
                'colorway_stumble',
                'colorway_digg',
                'inkthemes_flickr',
                'inkthemes_instagram',
                'inkthemes_pinterest',
                'inkthemes_tumblr',
                'inkthemes_youtube',
                'inkthemes_google'
            ),
            'footer_col_layout' => array(
                'footer_col_on_off',
                'footer_col_area_select',
            ),
            'header_colors_section' => array(
                'header_bg_color',
                'site_title_color',
                'site_tagline_color',
                'menu_link_color',
                'menu_hover_color',
                'menu_background_color',
                'menu_background_hover_color',
                'menu_dropdown_hover_color',
            ),
            'theme_color_section' => array(
                'theme_link_color',
                'theme_link_hover_color',
                'theme_para_color',
                'theme_h1_color',
                'theme_h2_color',
                'theme_h3_color',
                'theme_h4_color',
                'theme_h5_color',
                'theme_h6_color',
            ),
            'footer_color_section' => array(
                'footer_link_color',
                'footer_link_hover_color',
                'footer_text_color',
                'footer_header_color',
                'footer_col_bg_color',
                'bottom_footer_bg_color',
            ),
            'footer_bgimage' => array(
                'inkthemes_footerbg'
            )
        );
        return $section_content;
    }

    public static function inkthemes_Settings() {
        /*
         * Including Alpha Color Picker Class File
         */
        require_once( trailingslashit(get_template_directory()) . 'includes/alpha-color-picker/alpha-color-picker.php' );
        // Typography Array
        $font_family = array('ABeeZee', 'Abel', 'Abril Fatface', 'Aclonica', 'Acme', 'Actor', 'Adamina', 'Advent Pro', 'Aguafina Script', 'Akronim', 'Aladin', 'Aldrich', 'Alef', 'Alegreya', 'Alegreya SC', 'Alex Brush', 'Alfa Slab One', 'Alice', 'Alike', 'Alike Angular', 'Allan', 'Allerta', 'Allerta Stencil', 'Allura', 'Almendra', 'Almendra Display', 'Almendra SC', 'Amarante', 'Amaranth', 'Amatic SC', 'Amethysta', 'Anaheim', 'Andada', 'Andika', 'Angkor', 'Annie Use Your Telescope', 'Anonymous Pro', 'Antic', 'Antic Didone', 'Antic Slab', 'Anton', 'Arapey', 'Arbutus', 'Arbutus Slab', 'Architects Daughter', 'Archivo Black', 'Archivo Narrow', 'Arial Black', 'Arial Narrow', 'Arimo', 'Arizonia', 'Armata', 'Artifika', 'Arvo', 'Asap', 'Asset', 'Astloch', 'Asul', 'Atomic Age', 'Aubrey', 'Audiowide', 'Autour One', 'Average', 'Average Sans', 'Averia Gruesa Libre', 'Averia Libre', 'Averia Sans Libre', 'Averia Serif Libre', 'Bad Script', 'Balthazar', 'Bangers', 'Basic', 'Battambang', 'Baumans', 'Bayon', 'Belgrano', 'Bell MT', 'Bell MT Alt', 'Belleza', 'BenchNine', 'Bentham', 'Berkshire Swash', 'Bevan', 'Bigelow Rules', 'Bigshot One', 'Bilbo', 'Bilbo Swash Caps', 'Bitter', 'Black Ops One', 'Bodoni', 'Bokor', 'Bonbon', 'Boogaloo', 'Bowlby One', 'Bowlby One SC', 'Brawler', 'Bree Serif', 'Bubblegum Sans', 'Bubbler One', 'Buenard', 'Butcherman', 'Butcherman Caps', 'Butterfly Kids', 'Cabin', 'Cabin Condensed', 'Cabin Sketch', 'Caesar Dressing', 'Cagliostro', 'Calibri', 'Calligraffitti', 'Cambo', 'Cambria', 'Candal', 'Cantarell', 'Cantata One', 'Cantora One', 'Capriola', 'Cardo', 'Carme', 'Carrois Gothic', 'Carrois Gothic SC', 'Carter One', 'Caudex', 'Cedarville Cursive', 'Ceviche One', 'Changa One', 'Chango', 'Chau Philomene One', 'Chela One', 'Chelsea Market', 'Chenla', 'Cherry Cream Soda', 'Cherry Swash', 'Chewy', 'Chicle', 'Chivo', 'Cinzel', 'Cinzel Decorative', 'Clara', 'Clicker Script', 'Coda', 'Codystar', 'Combo', 'Comfortaa', 'Coming Soon', 'Concert One', 'Condiment', 'Consolas', 'Content', 'Contrail One', 'Convergence', 'Cookie', 'Copse', 'Corben', 'Corsiva', 'Courgette', 'Courier New', 'Cousine', 'Coustard', 'Covered By Your Grace', 'Crafty Girls', 'Creepster', 'Creepster Caps', 'Crete Round', 'Crimson Text', 'Croissant One', 'Crushed', 'Cuprum', 'Cutive', 'Cutive Mono', 'Damion', 'Dancing Script', 'Dangrek', 'Dawning of a New Day', 'Days One', 'Delius', 'Delius Swash Caps', 'Delius Unicase', 'Della Respira', 'Denk One', 'Devonshire', 'Dhyana', 'Didact Gothic', 'Diplomata', 'Diplomata SC', 'Domine', 'Donegal One', 'Doppio One', 'Dorsa', 'Dosis', 'Dr Sugiyama', 'Droid Arabic Kufi', 'Droid Arabic Naskh', 'Droid Sans', 'Droid Sans Mono', 'Droid Sans TV', 'Droid Serif', 'Duru Sans', 'Dynalight', 'EB Garamond', 'Eagle Lake', 'Eater', 'Eater Caps', 'Economica', 'Electrolize', 'Elsie', 'Elsie Swash Caps', 'Emblema One', 'Emilys Candy', 'Engagement', 'Englebert', 'Enriqueta', 'Erica One', 'Esteban', 'Euphoria Script', 'Ewert', 'Exo', 'Expletus Sans', 'Fanwood Text', 'Fascinate', 'Fascinate Inline', 'Faster One', 'Fasthand', 'Fauna One', 'Federant', 'Federo', 'Felipa', 'Fenix', 'Finger Paint', 'Fjalla One', 'Fjord One', 'Flamenco', 'Flavors', 'Fondamento', 'Fontdiner Swanky', 'Forum', 'Francois One', 'Freckle Face', 'Fredericka the Great', 'Fredoka One', 'Freehand', 'Fresca', 'Frijole', 'Fruktur', 'Fugaz One', 'GFS Didot', 'GFS Neohellenic', 'Gabriela', 'Gafata', 'Galdeano', 'Galindo', 'Garamond', 'Gentium Basic', 'Gentium Book Basic', 'Geo', 'Geostar', 'Geostar Fill', 'Germania One', 'Gilda Display', 'Give You Glory', 'Glass Antiqua', 'Glegoo', 'Gloria Hallelujah', 'Goblin One', 'Gochi Hand', 'Gorditas', 'Goudy Bookletter 1911', 'Graduate', 'Grand Hotel', 'Gravitas One', 'Great Vibes', 'Griffy', 'Gruppo', 'Gudea', 'Habibi', 'Hammersmith One', 'Hanalei', 'Hanalei Fill', 'Handlee', 'Hanuman', 'Happy Monkey', 'Headland One', 'Helvetica Neue', 'Henny Penny', 'Herr Von Muellerhoff', 'Holtwood One SC', 'Homemade Apple', 'Homenaje', 'IM Fell DW Pica', 'IM Fell DW Pica SC', 'IM Fell Double Pica', 'IM Fell Double Pica SC', 'IM Fell English', 'IM Fell English SC', 'IM Fell French Canon', 'IM Fell French Canon SC', 'IM Fell Great Primer', 'IM Fell Great Primer SC', 'Iceberg', 'Iceland', 'Imprima', 'Inconsolata', 'Inder', 'Indie Flower', 'Inika', 'Irish Grover', 'Irish Growler', 'Istok Web', 'Italiana', 'Italianno', 'Jacques Francois', 'Jacques Francois Shadow', 'Jim Nightshade', 'Jockey One', 'Jolly Lodger', 'Josefin Sans', 'Josefin Sans Std Light', 'Josefin Slab', 'Joti One', 'Judson', 'Julee', 'Julius Sans One', 'Junge', 'Jura', 'Just Another Hand', 'Just Me Again Down Here', 'Kameron', 'Karla', 'Kaushan Script', 'Kavoon', 'Keania One', 'Kelly Slab', 'Kenia', 'Khmer', 'Kite One', 'Knewave', 'Kotta One', 'Koulen', 'Kranky', 'Kreon', 'Kristi', 'Krona One', 'La Belle Aurore', 'Lancelot', 'Lateef', 'Lato', 'League Script', 'Leckerli One', 'Ledger', 'Lekton', 'Lemon', 'Lemon One', 'Libre Baskerville', 'Life Savers', 'Lilita One', 'Lily Script One', 'Limelight', 'Linden Hill', 'Lobster', 'Lobster Two', 'Lohit Bengali', 'Lohit Devanagari', 'Lohit Tamil', 'Londrina Outline', 'Londrina Shadow', 'Londrina Sketch', 'Londrina Solid', 'Lora', 'Love Ya Like A Sister', 'Loved by the King', 'Lovers Quarrel', 'Luckiest Guy', 'Lusitana', 'Lustria', 'Macondo', 'Macondo Swash Caps', 'Magra', 'Maiden Orange', 'Mako', 'Marcellus', 'Marcellus SC', 'Marck Script', 'Margarine', 'Marko One', 'Marmelad', 'Marvel', 'Mate', 'Mate SC', 'Maven Pro', 'McLaren', 'Meddon', 'MedievalSharp', 'Medula One', 'Megrim', 'Meie Script', 'Merienda', 'Merienda One', 'Merriweather', 'Merriweather Sans', 'Metal', 'Metal Mania', 'Metamorphous', 'Metrophobic', 'Michroma', 'Milonga', 'Miltonian', 'Miltonian Tattoo', 'Miniver', 'Miss Fajardose', 'Miss Saint Delafield', 'Modern Antiqua', 'Molengo', 'Monda', 'Monofett', 'Monoton', 'Monsieur La Doulaise', 'Montaga', 'Montez', 'Montserrat', 'Montserrat Alternates', 'Montserrat Subrayada', 'Moul', 'Moulpali', 'Mountains of Christmas', 'Mouse Memoirs', 'Mr Bedford', 'Mr Bedfort', 'Mr Dafoe', 'Mr De Haviland', 'Mrs Saint Delafield', 'Mrs Sheppards', 'Muli', 'Mystery Quest', 'Neucha', 'Neuton', 'New Rocker', 'News Cycle', 'Niconne', 'Nixie One', 'Nobile', 'Nokora', 'Norican', 'Nosifer', 'Nosifer Caps', 'Nothing You Could Do', 'Noticia Text', 'Noto Sans', 'Noto Sans UI', 'Noto Serif', 'Nova Cut', 'Nova Flat', 'Nova Mono', 'Nova Oval', 'Nova Round', 'Nova Script', 'Nova Slim', 'Nova Square', 'Numans', 'Nunito', 'OFL Sorts Mill Goudy TT', 'Odor Mean Chey', 'Offside', 'Old Standard TT', 'Oldenburg', 'Oleo Script', 'Oleo Script Swash Caps', 'Open Sans', 'Oranienbaum', 'Orbitron', 'Oregano', 'Orienta', 'Original Surfer', 'Oswald', 'Over the Rainbow', 'Overlock', 'Overlock SC', 'Ovo', 'Oxygen', 'Oxygen Mono', 'PT Mono', 'PT Sans', 'PT Sans Caption', 'PT Sans Narrow', 'PT Serif', 'PT Serif Caption', 'Pacifico', 'Paprika', 'Parisienne', 'Passero One', 'Passion One', 'Pathway Gothic One', 'Patrick Hand', 'Patrick Hand SC', 'Patua One', 'Paytone One', 'Peralta', 'Permanent Marker', 'Petit Formal Script', 'Petrona', 'Philosopher', 'Piedra', 'Pinyon Script', 'Pirata One', 'Plaster', 'Play', 'Playball', 'Playfair Display', 'Playfair Display SC', 'Podkova', 'Poiret One', 'Poller One', 'Poly', 'Pompiere', 'Pontano Sans', 'Port Lligat Sans', 'Port Lligat Slab', 'Prata', 'Preahvihear', 'Press Start 2P', 'Princess Sofia', 'Prociono', 'Prosto One', 'Proxima Nova', 'Proxima Nova Tabular Figures', 'Puritan', 'Poppins', 'Purple Purse', 'Quando', 'Quantico', 'Quattrocento', 'Quattrocento Sans', 'Questrial', 'Quicksand', 'Quintessential', 'Qwigley', 'Racing Sans One', 'Radley', 'Raleway', 'Raleway Dots', 'Rambla', 'Rammetto One', 'Ranchers', 'Rancho', 'Rationale', 'Redressed', 'Reenie Beanie', 'Revalia', 'Ribeye', 'Ribeye Marrow', 'Righteous', 'Risque', 'Roboto', 'Roboto Condensed', 'Roboto Slab', 'Rochester', 'Rock Salt', 'Rokkitt', 'Romanesco', 'Ropa Sans', 'Rosario', 'Rosarivo', 'Rouge Script', 'Ruda', 'Rufina', 'Ruge Boogie', 'Ruluko', 'Rum Raisin', 'Ruslan Display', 'Russo One', 'Ruthie', 'Rye', 'Sacramento', 'Sail', 'Salsa', 'Sanchez', 'Sancreek', 'Sansita One', 'Sans Serif', 'Sarina', 'Satisfy', 'Scada', 'Scheherazade', 'Schoolbell', 'Seaweed Script', 'Sevillana', 'Seymour One', 'Shadows Into Light', 'Shadows Into Light Two', 'Shanti', 'Share', 'Share Tech', 'Share Tech Mono', 'Shojumaru', 'Short Stack', 'Siamreap', 'Siemreap', 'Sigmar One', 'Signika', 'Signika Negative', 'Simonetta', 'Sintony', 'Sirin Stencil', 'Six Caps', 'Skranji', 'Slackey', 'Smokum', 'Smythe', 'Snippet', 'Snowburst One', 'Sofadi One', 'Sofia', 'Sonsie One', 'Sorts Mill Goudy', 'Source Code Pro', 'Source Sans Pro', 'Special Elite', 'Spicy Rice', 'Spinnaker', 'Spirax', 'Squada One', 'Stalemate', 'Stalin One', 'Stalinist One', 'Stardos Stencil', 'Stint Ultra Condensed', 'Stint Ultra Expanded', 'Stoke', 'Strait', 'Sue Ellen Francisco', 'Sunshiney', 'Supermercado One', 'Suwannaphum', 'Swanky and Moo Moo', 'Syncopate', 'Tahoma', 'Tangerine', 'Taprom', 'Tauri', 'Telex', 'Tenor Sans', 'Terminal Dosis', 'Terminal Dosis Light', 'Text Me One', 'Thabit', 'The Girl Next Door', 'Tienne', 'Tinos', 'Titan One', 'Titillium Web', 'Trade Winds', 'Trocchi', 'Trochut', 'Trykker', 'Tulpen One', 'Ubuntu', 'Ubuntu Condensed', 'Ubuntu Mono', 'Ultra', 'Uncial Antiqua', 'Underdog', 'Unica One', 'UnifrakturMaguntia', 'Unkempt', 'Unlock', 'Unna', 'VT323', 'Vampiro One', 'Varela', 'Varela Round', 'Vast Shadow', 'Vibur', 'Vidaloka', 'Viga', 'Voces', 'Volkhov', 'Vollkorn', 'Voltaire', 'Waiting for the Sunrise', 'Wallpoet', 'Walter Turncoat', 'Warnes', 'Wellfleet', 'Wendy One', 'Wire One', 'Yanone Kaffeesatz', 'Yellowtail', 'Yeseva One', 'Yesteryear', 'Zeyada', 'jsMath cmbx10', 'jsMath cmex10', 'jsMath cmmi10', 'jsMath cmr10', 'jsMath cmsy10', 'jsMath cmti10', 'Maracellus');
        $fontwt = array('Default', 'normal', 'bold', 'bolder', 'lighter', '100', '200', '300', '400', '500', '600', '700', '800', '900');
        $fontsz = array('8', '9', '10', '11', '12', '13', '14', '15', '16', '17', '18', '19', '20', '21', '22', '23', '24', '25', '26', '27', '28', '29', '30', '31', '32', '33', '34', '35', '36', '37', '38', '39', '40');

        $theme_settings = array(
            'logo_option' => array(
                'id' => 'inkthemes_options[logo_option]',
                'label' => __('Enable Logo', 'colorway'),
                'description' => __('Here you can Enable the Logo for your Website.', 'colorway'),
                'type' => 'option',
                'setting_type' => 'checkbox',
                'default' => '',
            ),
            'colorway_logo' => array(
                'id' => 'inkthemes_options[colorway_logo]',
                'label' => __('Custom Logo', 'colorway'),
                'description' => __('Upload a logo for your Website. The recommended size for the logo is 215px width x 55px height.', 'colorway'),
                'type' => 'option',
                'setting_type' => 'image',
                // 'transport' => 'postMessage',
                'default' => get_template_directory_uri() . '/assets/images/logo.png'
            ),
            'sticky_logo_setting' => array(
                'id' => 'inkthemes_options[sticky_logo_setting]',
                'label' => __('Sticky Header Logo', 'colorway'),
                'description' => __('Here you can upload a Sticky Header Logo for your Website. Specified size is 215px width x 55px height.', 'colorway'),
                'type' => 'option',
                'setting_type' => 'image',
//                'transport' => 'postMessage',
                'default' => ''
            ),
            'colorway_sticky_header' => array(
                'id' => 'inkthemes_options[colorway_sticky_header]',
                'label' => __('Enable Sticky Header', 'colorway'),
                'description' => __('After Configuring Sticky Option, you must publish the customizer once and configure the below settings.', 'colorway'),
                'type' => 'option',
                'setting_type' => 'checkbox',
                'default' => false
            ),
            'border_bottom_on_off' => array(
                'id' => 'inkthemes_options[border_bottom_on_off]',
                'label' => __('Switch On/Off Border Bottom', 'colorway'),
                'description' => __('Here you can enable or disable border bottom of heading.', 'colorway'),
                'type' => 'option',
                'setting_type' => 'radio',
                'default' => 'on',
                'choices' => array(
                    'on' => 'On',
                    'off' => 'Off'
                )
            ),
//            'display_header_description' => array(
//                'id' => 'inkthemes_options[display_header_description]',
//                'label' => __('Enable Site Tagline', 'colorway'),
//                'description' => __('Here you can Enable the Site Tagline for your Website.', 'colorway'),
//                'type' => 'option',
//                'setting_type' => 'checkbox',
//                'default' => 'yes'
//            ),
            'colorway_analytics' => array(
                'id' => 'inkthemes_options[colorway_analytics]',
                'label' => __('Tracking Code', 'colorway'),
                'description' => __('Paste your Google Analytics (or other) tracking code here.', 'colorway'),
                'type' => 'option',
                'setting_type' => 'textarea',
                'transport' => 'postMessage',
                'default' => ''
            ),
            'colorway_button_html' => array(
                'id' => 'inkthemes_options[colorway_button_html]',
                'label' => __('Custom Menu Html/Text', 'colorway'),
                'description' => __('Custom Menu Html/Text', 'colorway'),
                'type' => 'option',
                'setting_type' => 'textarea',
//                'transport' => 'postMessage',
                'default' => '<button><a href="#">Contact Us</a></button>'
            ),
            'btn_on_off' => array(
                'id' => 'inkthemes_options[btn_on_off]',
                'label' => __('Custom Menu Html/Text On/Off', 'colorway'),
                'description' => __('Custom Menu Html/Text On/Off', 'colorway'),
                'type' => 'option',
                'setting_type' => 'radio',
                'transport' => 'postMessage',
                'default' => 'on',
                'choices' => array(
                    'on' => 'On',
                    'off' => 'Off'
                )
            ),
            'button_link_color' => array(
                'id' => 'inkthemes_options[button_link_color]',
                'label' => __('Menu Button Link Color', 'colorway'),
                'description' => __('Menu Button Link Color', 'colorway'),
                'type' => 'option',
                'setting_type' => 'alpha-color',
                'palette' => true,
                'default' => ''
            ),
            'button_link_hover_color' => array(
                'id' => 'inkthemes_options[button_link_hover_color]',
                'label' => __('Menu Button Link Hover Color', 'colorway'),
                'description' => __('Menu Button Link Hover Color', 'colorway'),
                'type' => 'option',
                'setting_type' => 'alpha-color',
                'palette' => true,
                'default' => ''
            ),
            'button_bg_color' => array(
                'id' => 'inkthemes_options[button_bg_color]',
                'label' => __('Menu Button Background Color', 'colorway'),
                'description' => __('Menu Button Background Color', 'colorway'),
                'type' => 'option',
                'setting_type' => 'alpha-color',
                'palette' => true,
                'default' => ''
            ),
            'button_bg_hover_color' => array(
                'id' => 'inkthemes_options[button_bg_hover_color]',
                'label' => __('Menu Button Background Hover Color', 'colorway'),
                'description' => __('Menu Button Background Hover Color', 'colorway'),
                'type' => 'option',
                'setting_type' => 'alpha-color',
                'palette' => true,
                'default' => ''
            ),
            'colorway_dummy_data' => array(
                'id' => 'inkthemes_options[colorway_dummy_data]',
                'label' => __('Dummy Data', 'colorway'),
                'description' => __('Enable or Disable Dummy Data for the theme', 'colorway'),
                'type' => 'option',
                'setting_type' => 'radio',
                'transport' => 'postMessage',
                'default' => 'off',
                'choices' => array(
                    'on' => 'On',
                    'off' => 'Off'
                )
            ),
//            'classic_homepage' => array(
//                'id' => 'inkthemes_options[classic_homepage]',
//                'label' => __('ColorWay Homepage', 'colorway'),
//                'description' => __('Enable or Disable HomePage for the theme', 'colorway'),
//                'type' => 'option',
//                'setting_type' => 'radio',
//                'default' => 'classic',
//                'choices' => array(
//                    'template-home.php' => 'Classic',
//                    'elementor_header_footer' => 'Advanced'
//                )
//            ),
            'colorway_home_page_blog_post' => array(
                'id' => 'inkthemes_options[colorway_home_page_blog_post]',
                'label' => __('Home Page Blog post On/Off', 'colorway'),
                'description' => __('Turn on or off the home page blog post as per your requirement.', 'colorway'),
                'type' => 'option',
                'setting_type' => 'radio',
                'transport' => 'postMessage',
                'default' => 'on',
                'choices' => array(
                    'on' => 'On',
                    'off' => 'Off'
                )
            ),
            'colorway_home_page_slider' => array(
                'id' => 'inkthemes_options[colorway_home_page_slider]',
                'label' => __('Home Page Slider On/Off', 'colorway'),
                'description' => __('Turn on or off the home page Slider as per your requirement.', 'colorway'),
                'type' => 'option',
                'setting_type' => 'radio',
                'transport' => 'postMessage',
                'default' => 'on',
                'choices' => array(
                    "on" => "On",
                    "off" => "Off")
            ),
            'colorway_slideimage1' => array(
                'id' => 'inkthemes_options[colorway_slideimage1]',
                'label' => __('First Slider Image', 'colorway'),
                'description' => __('Choose Image for your Home page First Slider. Optimal Size: 1171px x 526px', 'colorway'),
                'type' => 'option',
                'setting_type' => 'image',
                //'transport' => 'postMessage',
                'default' => ''
            ),
            'colorway_slideheading1' => array(
                'id' => 'inkthemes_options[colorway_slideheading1]',
                'label' => __('First Slider Heading', 'colorway'),
                'description' => __('Enter the Heading for Home page First slider', 'colorway'),
                'type' => 'option',
                'setting_type' => 'textarea',
                'transport' => 'postMessage',
                'default' => ''
            ),
            'colorway_slidelink1' => array(
                'id' => 'inkthemes_options[colorway_slidelink1]',
                'label' => __('First Slide Link', 'colorway'),
                'description' => __('Enter the Link URL for Home Page First Slider', 'colorway'),
                'type' => 'option',
                'setting_type' => 'link',
                'transport' => 'postMessage',
                'default' => ''
            ),
            'colorway_slidedescription1' => array(
                'id' => 'inkthemes_options[colorway_slidedescription1]',
                'label' => __('First Slide Description', 'colorway'),
                'description' => __('Enter the Description for Home Page First Slides Show', 'colorway'),
                'type' => 'option',
                'setting_type' => 'textarea',
                'transport' => 'postMessage',
                'default' => ''
            ),
            'colorway_slideimage2' => array(
                'id' => 'inkthemes_options[colorway_slideimage2]',
                'label' => __('Second Slider Image', 'colorway'),
                'description' => __('Choose Image for your Home page Second Slider. Optimal Size: 1171px x 526px', 'colorway'),
                'type' => 'option',
                'setting_type' => 'image',
                //'transport' => 'postMessage',
                'default' => ''
            ),
            'colorway_slideheading2' => array(
                'id' => 'inkthemes_options[colorway_slideheading2]',
                'label' => __('Second Slider Heading', 'colorway'),
                'description' => __('Enter the Heading for Home page Second slider', 'colorway'),
                'type' => 'option',
                'setting_type' => 'textarea',
                'transport' => 'postMessage',
                'default' => ''
            ),
            'colorway_slidelink2' => array(
                'id' => 'inkthemes_options[colorway_slidelink2]',
                'label' => __('Second Slide Link', 'colorway'),
                'description' => __('Enter the Link URL for Home Page Second Slider', 'colorway'),
                'type' => 'option',
                'setting_type' => 'link',
                'transport' => 'postMessage',
                'default' => ''
            ),
            'colorway_slidedescription2' => array(
                'id' => 'inkthemes_options[colorway_slidedescription2]',
                'label' => __('Seocnd Slide Description', 'colorway'),
                'description' => __('Enter the Description for Home Page Second Slider', 'colorway'),
                'type' => 'option',
                'setting_type' => 'textarea',
                'transport' => 'postMessage',
                'default' => ''
            ),
            'inkthemes_mainheading' => array(
                'id' => 'inkthemes_options[inkthemes_mainheading]',
                'label' => __('Home Page Main Heading', 'colorway'),
                'description' => __('Enter your heading text for home page', 'colorway'),
                'type' => 'option',
                'setting_type' => 'textarea',
                'transport' => 'postMessage',
                'default' => ''
            ),
            'inkthemes_fimg1' => array(
                'id' => 'inkthemes_options[inkthemes_fimg1]',
                'label' => __('First Feature Image', 'colorway'),
                'description' => __('Choose image for your feature column first. Optimal size 198px x 115px', 'colorway'),
                'type' => 'option',
                'setting_type' => 'image',
                'transport' => 'postMessage',
                'default' => ''
            ),
            'inkthemes_headline1' => array(
                'id' => 'inkthemes_options[inkthemes_headline1]',
                'label' => __('First Feature Heading', 'colorway'),
                'description' => __('Enter text for first feature area heading.', 'colorway'),
                'type' => 'option',
                'setting_type' => 'textarea',
                'transport' => 'postMessage',
                'default' => ''
            ),
            'inkthemes_feature1' => array(
                'id' => 'inkthemes_options[inkthemes_feature1]',
                'label' => __('First Feature Description', 'colorway'),
                'description' => __('Enter text for first feature area description.', 'colorway'),
                'type' => 'option',
                'setting_type' => 'textarea',
                'transport' => 'postMessage',
                'default' => ''
            ),
            'inkthemes_link1' => array(
                'id' => 'inkthemes_options[inkthemes_link1]',
                'label' => __('First feature Link', 'colorway'),
                'description' => __('Enter link url for first feature area.', 'colorway'),
                'type' => 'option',
                'setting_type' => 'link',
                'transport' => 'postMessage',
                'default' => '#'
            ),
            'inkthemes_fimg2' => array(
                'id' => 'inkthemes_options[inkthemes_fimg2]',
                'label' => __('Second Feature Image', 'colorway'),
                'description' => __('Choose image for your feature column second. Optimal size 198px x 115px', 'colorway'),
                'type' => 'option',
                'setting_type' => 'image',
                'transport' => 'postMessage',
                'default' => ''
            ),
            'inkthemes_headline2' => array(
                'id' => 'inkthemes_options[inkthemes_headline2]',
                'label' => __('Second Feature Heading', 'colorway'),
                'description' => __('Enter text for second feature area heading.', 'colorway'),
                'type' => 'option',
                'setting_type' => 'textarea',
                'transport' => 'postMessage',
                'default' => ''
            ),
            'inkthemes_feature2' => array(
                'id' => 'inkthemes_options[inkthemes_feature2]',
                'label' => __('Second Feature Description', 'colorway'),
                'description' => __('Enter text for second feature area description.', 'colorway'),
                'type' => 'option',
                'setting_type' => 'textarea',
                'transport' => 'postMessage',
                'default' => ''
            ),
            'inkthemes_link2' => array(
                'id' => 'inkthemes_options[inkthemes_link2]',
                'label' => __('Second feature Link', 'colorway'),
                'description' => __('Enter link url for second feature area.', 'colorway'),
                'type' => 'option',
                'setting_type' => 'link',
                'transport' => 'postMessage',
                'default' => '#'
            ),
            'inkthemes_fimg3' => array(
                'id' => 'inkthemes_options[inkthemes_fimg3]',
                'label' => __('Third Feature Image', 'colorway'),
                'description' => __('Choose image for your feature column third. Optimal size 198px x 115px', 'colorway'),
                'type' => 'option',
                'setting_type' => 'image',
                'transport' => 'postMessage',
                'default' => ''
            ),
            'inkthemes_headline3' => array(
                'id' => 'inkthemes_options[inkthemes_headline3]',
                'label' => __('Third Feature Heading', 'colorway'),
                'description' => __('Enter text for third feature area heading.', 'colorway'),
                'type' => 'option',
                'setting_type' => 'textarea',
                'transport' => 'postMessage',
                'default' => ''
            ),
            'inkthemes_feature3' => array(
                'id' => 'inkthemes_options[inkthemes_feature3]',
                'label' => __('Third Feature Description', 'colorway'),
                'description' => __('Enter text for third feature area description.', 'colorway'),
                'type' => 'option',
                'setting_type' => 'textarea',
                'transport' => 'postMessage',
                'default' => ''
            ),
            'inkthemes_link3' => array(
                'id' => 'inkthemes_options[inkthemes_link3]',
                'label' => __('Third feature Link', 'colorway'),
                'description' => __('Enter link url for third feature area.', 'colorway'),
                'type' => 'option',
                'setting_type' => 'link',
                'transport' => 'postMessage',
                'default' => '#'
            ),
            'inkthemes_fimg4' => array(
                'id' => 'inkthemes_options[inkthemes_fimg4]',
                'label' => __('Fourth Feature Image', 'colorway'),
                'description' => __('Choose image for your feature column fourth. Optimal size 198px x 115px', 'colorway'),
                'type' => 'option',
                'setting_type' => 'image',
                'transport' => 'postMessage',
                'default' => ''
            ),
            'inkthemes_headline4' => array(
                'id' => 'inkthemes_options[inkthemes_headline4]',
                'label' => __('Fourth Feature Heading', 'colorway'),
                'description' => __('Enter text for fourth feature area heading.', 'colorway'),
                'type' => 'option',
                'setting_type' => 'textarea',
                'transport' => 'postMessage',
                'default' => ''
            ),
            'inkthemes_feature4' => array(
                'id' => 'inkthemes_options[inkthemes_feature4]',
                'label' => __('Fourth Feature Description', 'colorway'),
                'description' => __('Enter text for fourth feature area description.', 'colorway'),
                'type' => 'option',
                'setting_type' => 'textarea',
                'transport' => 'postMessage',
                'default' => ''
            ),
            'inkthemes_link4' => array(
                'id' => 'inkthemes_options[inkthemes_link4]',
                'label' => __('Fourth feature Link', 'colorway'),
                'description' => __('Enter link url for fourth feature area.', 'colorway'),
                'type' => 'option',
                'setting_type' => 'link',
                'transport' => 'postMessage',
                'default' => '#'
            ),
            'inkthemes_col_head' => array(
                'id' => 'inkthemes_options[inkthemes_col_head]',
                'label' => __('Home Page Widget Heading', 'colorway'),
                'description' => __('Enter your text for homepage Widget heading.', 'colorway'),
                'type' => 'option',
                'setting_type' => 'textarea',
                'transport' => 'postMessage',
                'default' => ''
            ),
            'inkthemes_blog_head' => array(
                'id' => 'inkthemes_options[inkthemes_blog_head]',
                'label' => __('Home Page Blog Heading', 'colorway'),
                'description' => __('Enter your text for home Page blog heading section', 'colorway'),
                'type' => 'option',
                'setting_type' => 'textarea',
                'transport' => 'postMessage',
                'default' => ''
            ),
            'inkthemes_blog_posts' => array(
                'id' => 'inkthemes_options[inkthemes_blog_posts]',
                'label' => __('Home Page Blog Posts', 'colorway'),
                'description' => __('Enter Number of Post you want to show on Home page.', 'colorway'),
                'type' => 'option',
                'setting_type' => 'number',
                'transport' => 'postMessage',
                'default' => ''
            ),
            'colorway_testimonial_status' => array(
                'id' => 'inkthemes_options[colorway_testimonial_status]',
                'label' => __('Testimonial Section On/Off', 'colorway'),
                'description' => __('Turn on or off the home page testimonial section as per your requirement.', 'colorway'),
                'type' => 'option',
                'setting_type' => 'radio',
                'transport' => 'postMessage',
                'default' => 'on',
                'choices' => array(
                    'on' => 'On',
                    'off' => 'Off'
                )
            ),
            'inkthemes_testimonial_main_head' => array(
                'id' => 'inkthemes_options[inkthemes_testimonial_main_head]',
                'label' => __('Testimonial Main Heading', 'colorway'),
                'description' => __('Here mention the text testimonial Section Main Description', 'colorway'),
                'type' => 'option',
                'setting_type' => 'textarea',
                'transport' => 'postMessage',
                'default' => ''
            ),
            'inkthemes_testimonial_main_desc' => array(
                'id' => 'inkthemes_options[inkthemes_testimonial_main_desc]',
                'label' => __('Testimonial Main Description', 'colorway'),
                'description' => __('Here mention the text testimonial Section Description', 'colorway'),
                'type' => 'option',
                'setting_type' => 'textarea',
                'transport' => 'postMessage',
                'default' => ''
            ),
            'inkthemes_testimonial' => array(
                'id' => 'inkthemes_options[inkthemes_testimonial]',
                'label' => __('First Testimonial Description', 'colorway'),
                'description' => __('Here mention the first testimonial description of client', 'colorway'),
                'type' => 'option',
                'setting_type' => 'textarea',
                'transport' => 'postMessage',
                'default' => ''
            ),
            'inkthemes_testimonial_img' => array(
                'id' => 'inkthemes_options[inkthemes_testimonial_img]',
                'label' => __('First Testimonial Image', 'colorway'),
                'description' => __('Here mention the first testimonial Image of client', 'colorway'),
                'type' => 'option',
                'setting_type' => 'image',
                'transport' => 'postMessage',
                'default' => ''
            ),
            'inkthemes_testimonial_name' => array(
                'id' => 'inkthemes_options[inkthemes_testimonial_name]',
                'label' => __('First Testimonial Name', 'colorway'),
                'description' => __('Here mention the name of  testimonial name of client', 'colorway'),
                'type' => 'option',
                'setting_type' => 'textarea',
                'transport' => 'postMessage',
                'default' => ''
            ),
            'inkthemes_testimonial_2' => array(
                'id' => 'inkthemes_options[inkthemes_testimonial_2]',
                'label' => __('Second Testimonial Description', 'colorway'),
                'description' => __('Here mention the Second testimonial description of client', 'colorway'),
                'type' => 'option',
                'setting_type' => 'textarea',
                'transport' => 'postMessage',
                'default' => ''
            ),
            'inkthemes_testimonial_img_2' => array(
                'id' => 'inkthemes_options[inkthemes_testimonial_img_2]',
                'label' => __('Second Testimonial Image', 'colorway'),
                'description' => __('Here mention the Second testimonial Image of client', 'colorway'),
                'type' => 'option',
                'setting_type' => 'image',
                'transport' => 'postMessage',
                'default' => ''
            ),
            'inkthemes_testimonial_name_2' => array(
                'id' => 'inkthemes_options[inkthemes_testimonial_name_2]',
                'label' => __('Second Testimonial Name', 'colorway'),
                'description' => __('Here mention the name of  testimonial name of client', 'colorway'),
                'type' => 'option',
                'setting_type' => 'textarea',
                'transport' => 'postMessage',
                'default' => ''
            ),
            'inkthemes_testimonial_3' => array(
                'id' => 'inkthemes_options[inkthemes_testimonial_3]',
                'label' => __('Third Testimonial Description', 'colorway'),
                'description' => __('Here mention the Third testimonial description of client', 'colorway'),
                'type' => 'option',
                'setting_type' => 'textarea',
                'transport' => 'postMessage',
                'default' => ''
            ),
            'inkthemes_testimonial_img_3' => array(
                'id' => 'inkthemes_options[inkthemes_testimonial_img_3]',
                'label' => __('Third Testimonial Image', 'colorway'),
                'description' => __('Here mention the Third testimonial Image of client', 'colorway'),
                'type' => 'option',
                'setting_type' => 'image',
                'transport' => 'postMessage',
                'default' => ''
            ),
            'inkthemes_testimonial_name_3' => array(
                'id' => 'inkthemes_options[inkthemes_testimonial_name_3]',
                'label' => __('Third Testimonial Name', 'colorway'),
                'description' => __('Here mention the name of  testimonial name of client', 'colorway'),
                'type' => 'option',
                'setting_type' => 'textarea',
                'transport' => 'postMessage',
                'default' => ''
            ),
            'colorway_facebook' => array(
                'id' => 'inkthemes_options[colorway_facebook]',
                'label' => __('Facebook URL', 'colorway'),
                'description' => __('Enter your Facebook URL if you have one', 'colorway'),
                'type' => 'option',
                'setting_type' => 'link',
                'transport' => 'postMessage',
                'default' => '#'
            ),
            'colorway_twitter' => array(
                'id' => 'inkthemes_options[colorway_twitter]',
                'label' => __('Twitter URL', 'colorway'),
                'description' => __('Enter your Twitter URL if you have one', 'colorway'),
                'type' => 'option',
                'setting_type' => 'link',
                'transport' => 'postMessage',
                'default' => '#'
            ),
            'colorway_rss' => array(
                'id' => 'inkthemes_options[colorway_rss]',
                'label' => __('RSS Feed URL', 'colorway'),
                'description' => __('Enter your RSS Feed URL if you have one', 'colorway'),
                'type' => 'option',
                'setting_type' => 'link',
                'transport' => 'postMessage',
                'default' => ''
            ),
            'colorway_linkedin' => array(
                'id' => 'inkthemes_options[colorway_linkedin]',
                'label' => __('Linked In URL', 'colorway'),
                'description' => __('Enter your Linkedin URL if you have one', 'colorway'),
                'type' => 'option',
                'setting_type' => 'link',
                'transport' => 'postMessage',
                'default' => ''
            ),
            'colorway_stumble' => array(
                'id' => 'inkthemes_options[colorway_stumble]',
                'label' => __('Stumble Upon URL', 'colorway'),
                'description' => __('Enter your Stumble Upon URL if you have one', 'colorway'),
                'type' => 'option',
                'setting_type' => 'link',
                'transport' => 'postMessage',
                'default' => ''
            ),
            'colorway_digg' => array(
                'id' => 'inkthemes_options[colorway_digg]',
                'label' => __('Digg URL', 'colorway'),
                'description' => __('Enter your Stumble Upon URL if you have one', 'colorway'),
                'type' => 'option',
                'setting_type' => 'link',
                'transport' => 'postMessage',
                'default' => ''
            ),
            'inkthemes_flickr' => array(
                'id' => 'inkthemes_options[inkthemes_flickr]',
                'label' => __('Flickr URL', 'colorway'),
                'description' => __('Quickly add your Flickr URL code to your theme by writing the code in this block.', 'colorway'),
                'type' => 'option',
                'setting_type' => 'link',
                'transport' => 'postMessage',
                'default' => ''
            ),
            'inkthemes_instagram' => array(
                'id' => 'inkthemes_options[inkthemes_instagram]',
                'label' => __('Instagram URL', 'colorway'),
                'description' => __('Quickly add your Instagram URL code to your theme by writing the code in this block.', 'colorway'),
                'type' => 'option',
                'setting_type' => 'link',
                'transport' => 'postMessage',
                'default' => ''
            ),
            'inkthemes_pinterest' => array(
                'id' => 'inkthemes_options[inkthemes_pinterest]',
                'label' => __('Pinterest URL', 'colorway'),
                'description' => __('Quickly add your Pinterest URL code to your theme by writing the code in this block.', 'colorway'),
                'type' => 'option',
                'setting_type' => 'link',
                'transport' => 'postMessage',
                'default' => ''
            ),
            'inkthemes_tumblr' => array(
                'id' => 'inkthemes_options[inkthemes_tumblr]',
                'label' => __('Tumblr URL', 'colorway'),
                'description' => __('Quickly add your Tumblr URL code to your theme by writing the code in this block.', 'colorway'),
                'type' => 'option',
                'setting_type' => 'link',
                'transport' => 'postMessage',
                'default' => ''
            ),
            'inkthemes_youtube' => array(
                'id' => 'inkthemes_options[inkthemes_youtube]',
                'label' => __('Youtube URL', 'colorway'),
                'description' => __('Quickly add your Youtube URL code to your theme by writing the code in this block.', 'colorway'),
                'type' => 'option',
                'setting_type' => 'link',
                'transport' => 'postMessage',
                'default' => ''
            ),
            'inkthemes_google' => array(
                'id' => 'inkthemes_options[inkthemes_google]',
                'label' => __('Google+ URL', 'colorway'),
                'description' => __('Quickly add your Google+ URL code to your theme by writing the code in this block.', 'colorway'),
                'type' => 'option',
                'setting_type' => 'link',
                'transport' => 'postMessage',
                'default' => ''
            ),
            'footer_col_area_select' => array(
                'id' => 'inkthemes_options[footer_col_area_select]',
                'label' => __('Footer Widgetized Area Selector', 'colorway'),
                'description' => __('Select the number of Widget Areas to be displayed.', 'colorway'),
                'type' => 'option',
                'setting_type' => 'radio',
                'transport' => 'postMessage',
                'priority' => '3',
                'default' => '4',
                'choices' => array(
                    '1' => 'Display One Column',
                    '2' => 'Display Two Column',
                    '3' => 'Display Three Column',
                    '4' => 'Display Four Column'
                )
            ),
            'footer_col_on_off' => array(
                'id' => 'inkthemes_options[footer_col_on_off]',
                'label' => __('Footer Widget Area On/Off', 'colorway'),
                'description' => __('On/Off the Footer Widget Area.', 'colorway'),
                'type' => 'option',
                'setting_type' => 'radio',
                'transport' => 'postMessage',
                'default' => 'on',
                'choices' => array(
                    'on' => 'On',
                    'off' => 'Off'
                )
            ),
            'feature_on_off' => array(
                'id' => 'inkthemes_options[feature_on_off]',
                'label' => __('Feature Section On/Off', 'colorway'),
                'description' => __('Turn on or off the home page Feature section as per your requirement.', 'colorway'),
                'type' => 'option',
                'setting_type' => 'radio',
                'transport' => 'postMessage',
                'default' => 'on',
                'choices' => array(
                    'on' => 'On',
                    'off' => 'Off'
                )
            ),
            'colorway_feature_select' => array(
                'id' => 'inkthemes_options[colorway_feature_select]',
                'label' => __('Feature Area Selector', 'colorway'),
                'description' => __('Select the number of Feature Areas to be displayed.', 'colorway'),
                'type' => 'option',
                'setting_type' => 'radio',
                'transport' => 'postMessage',
                'default' => '4',
                'choices' => array(
                    '1' => 'One Column Feature',
                    '2' => 'Two Column Feature',
                    '3' => 'Three Column Feature',
                    '4' => 'Four Column Feature'
                )
            ),
            'typography_logo_family' => array(
                'id' => 'inkthemes_options[typography_logo_family]',
                'label' => __('Logo Font Family', 'colorway'),
                'description' => __('Allows you to set Font Family For Logo.', 'colorway'),
                'type' => 'option',
                'setting_type' => 'select',
                'transport' => 'postMessage',
                'default' => '525',
                'choices' => $font_family
            ),
            'typography_menu_family' => array(
                'id' => 'inkthemes_options[typography_menu_family]',
                'label' => __('Navigation Menu Font Family', 'colorway'),
                'description' => __('Allows you to set Font Family for Navigation Menu.', 'colorway'),
                'type' => 'option',
                'setting_type' => 'select',
                'transport' => 'postMessage',
                'default' => '525',
                'choices' => $font_family
            ),
            'typography_nav_family' => array(
                'id' => 'inkthemes_options[typography_nav_family]',
                'label' => __('Body Font Family', 'colorway'),
                'description' => __('Allows you to set Font Family For Body.', 'colorway'),
                'type' => 'option',
                'setting_type' => 'select',
                'transport' => 'postMessage',
                'default' => '345',
                'choices' => $font_family
            ),
            'typography_para' => array(
                'id' => 'inkthemes_options[typography_para]',
                'label' => __('Paragraph Font Family', 'colorway'),
                'description' => __('Allows you to set Font Family For Paragraph.', 'colorway'),
                'type' => 'option',
                'setting_type' => 'select',
                'transport' => 'postMessage',
                'default' => '',
                'choices' => $font_family
            ),
            'typography_heading1' => array(
                'id' => 'inkthemes_options[typography_heading1]',
                'label' => __('Heading 1 Font Family', 'colorway'),
                'description' => __('Allows you to set Font Family For Heading 1.', 'colorway'),
                'type' => 'option',
                'setting_type' => 'select',
                'transport' => 'postMessage',
                'default' => '525',
                'choices' => $font_family
            ),
            'typography_heading2' => array(
                'id' => 'inkthemes_options[typography_heading2]',
                'label' => __('Heading 2 Font Family', 'colorway'),
                'description' => __('Allows you to set Font Family For Heading 2.', 'colorway'),
                'type' => 'option',
                'setting_type' => 'select',
                'transport' => 'postMessage',
                'default' => '525',
                'choices' => $font_family
            ),
            'typography_heading3' => array(
                'id' => 'inkthemes_options[typography_heading3]',
                'label' => __('Heading 3 Font Family', 'colorway'),
                'description' => __('Allows you to set Font Family For Heading 3.', 'colorway'),
                'type' => 'option',
                'setting_type' => 'select',
                'transport' => 'postMessage',
                'default' => '525',
                'choices' => $font_family
            ),
            'typography_heading4' => array(
                'id' => 'inkthemes_options[typography_heading4]',
                'label' => __('Heading 4 Font Family', 'colorway'),
                'description' => __('Allows you to set Font Family For Heading 4.', 'colorway'),
                'type' => 'option',
                'setting_type' => 'select',
                'transport' => 'postMessage',
                'default' => '525',
                'choices' => $font_family
            ),
            'typography_heading5' => array(
                'id' => 'inkthemes_options[typography_heading5]',
                'label' => __('Heading 5 Font Family', 'colorway'),
                'description' => __('Allows you to set Font Family For Heading 5.', 'colorway'),
                'type' => 'option',
                'setting_type' => 'select',
                'transport' => 'postMessage',
                'default' => '525',
                'choices' => $font_family
            ),
            'typography_heading6' => array(
                'id' => 'inkthemes_options[typography_heading6]',
                'label' => __('Heading 6 Font Family', 'colorway'),
                'description' => __('Allows you to set Font Family For Heading 6.', 'colorway'),
                'type' => 'option',
                'setting_type' => 'select',
                'transport' => 'postMessage',
                'default' => '525',
                'choices' => $font_family
            ),
            'typography_title_fontweight' => array(
                'id' => 'inkthemes_options[typography_title_fontweight]',
                'label' => __('Site Title Font Weight', 'colorway'),
                'description' => __('Allows you to set Site Title Font Weight.', 'colorway'),
                'type' => 'option',
                'setting_type' => 'select',
                'transport' => 'postMessage',
                'default' => '10',
                'choices' => $fontwt
            ),
            'typography_tagline_fontweight' => array(
                'id' => 'inkthemes_options[typography_tagline_fontweight]',
                'label' => __('Site Tagline Font Weight', 'colorway'),
                'description' => __('Allows you to set Site Tagline Font Weight.', 'colorway'),
                'type' => 'option',
                'setting_type' => 'select',
                'transport' => 'postMessage',
                'default' => '8',
                'choices' => $fontwt
            ),
            'typography_fontweight_navmenu' => array(
                'id' => 'inkthemes_options[typography_fontweight_navmenu]',
                'label' => __('Menu Font Weight', 'colorway'),
                'description' => __('Allows you to set Navigation Menu Font Weight.', 'colorway'),
                'type' => 'option',
                'setting_type' => 'select',
                'transport' => 'postMessage',
                'default' => '8',
                'choices' => $fontwt
            ),
            'typography_fontweight_heading1' => array(
                'id' => 'inkthemes_options[typography_fontweight_heading1]',
                'label' => __('Heading 1 Font Weight', 'colorway'),
                'description' => __('Allows you to set Heading 1 Font Weight.', 'colorway'),
                'type' => 'option',
                'setting_type' => 'select',
                'transport' => 'postMessage',
                'default' => '10',
                'choices' => $fontwt
            ),
            'typography_fontweight_heading2' => array(
                'id' => 'inkthemes_options[typography_fontweight_heading2]',
                'label' => __('Heading 2 Font Weight', 'colorway'),
                'description' => __('Allows you to set Heading 2 Font Weight.', 'colorway'),
                'type' => 'option',
                'setting_type' => 'select',
                'transport' => 'postMessage',
                'default' => '10',
                'choices' => $fontwt
            ),
            'typography_fontweight_heading3' => array(
                'id' => 'inkthemes_options[typography_fontweight_heading3]',
                'label' => __('Heading 3 Font Weight', 'colorway'),
                'description' => __('Allows you to set Heading 3 Font Weight.', 'colorway'),
                'type' => 'option',
                'setting_type' => 'select',
                'transport' => 'postMessage',
                'default' => '10',
                'choices' => $fontwt
            ),
            'typography_fontweight_heading4' => array(
                'id' => 'inkthemes_options[typography_fontweight_heading4]',
                'label' => __('Heading 4 Font Weight', 'colorway'),
                'description' => __('Allows you to set Heading 4 Font Weight.', 'colorway'),
                'type' => 'option',
                'setting_type' => 'select',
                'transport' => 'postMessage',
                'default' => '8',
                'choices' => $fontwt
            ),
            'typography_fontweight_heading5' => array(
                'id' => 'inkthemes_options[typography_fontweight_heading5]',
                'label' => __('Heading 5 Font Weight', 'colorway'),
                'description' => __('Allows you to set Heading 5 Font Weight.', 'colorway'),
                'type' => 'option',
                'setting_type' => 'select',
                'transport' => 'postMessage',
                'default' => '10',
                'choices' => $fontwt
            ),
            'typography_fontweight_heading6' => array(
                'id' => 'inkthemes_options[typography_fontweight_heading6]',
                'label' => __('Heading 6 Font Weight', 'colorway'),
                'description' => __('Allows you to set Heading 6 Font Weight.', 'colorway'),
                'type' => 'option',
                'setting_type' => 'select',
                'transport' => 'postMessage',
                'default' => '8',
                'choices' => $fontwt
            ),
            'typography_fontweight_para' => array(
                'id' => 'inkthemes_options[typography_fontweight_para]',
                'label' => __('Paragraph Font Weight', 'colorway'),
                'description' => __('Allows you to set Paragraph Font Weight.', 'colorway'),
                'type' => 'option',
                'setting_type' => 'select',
                'transport' => 'postMessage',
                'default' => '',
                'choices' => $fontwt
            ),
            'typography_fontsize_para' => array(
                'id' => 'inkthemes_options[typography_fontsize_para]',
                'label' => __('Paragraph Font Size', 'colorway'),
                'description' => __('Allows you to set Font Size for Paragraph', 'colorway'),
                'type' => 'option',
                'setting_type' => 'select',
                'transport' => 'postMessage',
                'default' => '',
                'choices' => $fontsz
            ),
            'typography_fontsize_heading1' => array(
                'id' => 'inkthemes_options[typography_fontsize_heading1]',
                'label' => __('Heading 1 Font Size', 'colorway'),
                'description' => __('Allows you to set Font Size for Heading 1.', 'colorway'),
                'type' => 'option',
                'setting_type' => 'select',
                'transport' => 'postMessage',
                'default' => '27',
                'choices' => $fontsz
            ),
//            'typography_fontsize_heading1' => array(
//                'id' => 'inkthemes_options[typography_fontsize_heading1]',
//                'label' => __('Heading 1 Font Size', 'colorway'),
//                'description' => __('Allows you to set Font Size for Heading 1.', 'colorway'),
//                'type' => 'option',
//                'setting_type' => 'select',
//                'transport' => 'postMessage',
//                'default' => '24',
//                'choices' => $fontsz
//            ),
            'typography_fontsize_heading2' => array(
                'id' => 'inkthemes_options[typography_fontsize_heading2]',
                'label' => __('Heading 2 Font Size', 'colorway'),
                'description' => __('Allows you to set Font Size for Heading 2.', 'colorway'),
                'type' => 'option',
                'setting_type' => 'select',
                'transport' => 'postMessage',
                'default' => '24',
                'choices' => $fontsz
            ),
            'typography_fontsize_heading3' => array(
                'id' => 'inkthemes_options[typography_fontsize_heading3]',
                'label' => __('Heading 3 Font Size', 'colorway'),
                'description' => __('Allows you to set Font Size for Heading 3.', 'colorway'),
                'type' => 'option',
                'setting_type' => 'select',
                'transport' => 'postMessage',
                'default' => '21',
                'choices' => $fontsz
            ),
            'typography_fontsize_heading4' => array(
                'id' => 'inkthemes_options[typography_fontsize_heading4]',
                'label' => __('Heading 4 Font Size', 'colorway'),
                'description' => __('Allows you to set Font Size for Heading 4.', 'colorway'),
                'type' => 'option',
                'setting_type' => 'select',
                'transport' => 'postMessage',
                'default' => '16',
                'choices' => $fontsz
            ),
            'typography_fontsize_heading5' => array(
                'id' => 'inkthemes_options[typography_fontsize_heading5]',
                'label' => __('Heading 5 Font Size', 'colorway'),
                'description' => __('Allows you to set Font Size for Heading 5.', 'colorway'),
                'type' => 'option',
                'setting_type' => 'select',
                'transport' => 'postMessage',
                'default' => '16',
                'choices' => $fontsz
            ),
            'typography_fontsize_heading6' => array(
                'id' => 'inkthemes_options[typography_fontsize_heading6]',
                'label' => __('Footer Title Font Size', 'colorway'),
                'description' => __('Allows you to set Font Size for Footer Title.', 'colorway'),
                'type' => 'option',
                'setting_type' => 'select',
                'transport' => 'postMessage',
                'default' => '14',
                'choices' => $fontsz
            ),
            'footer_fontsize_para' => array(
                'id' => 'inkthemes_options[footer_fontsize_para]',
                'label' => __('Footer Paragraph Font Size', 'colorway'),
                'description' => __('Allows you to set Footer Paragraph Font Size.', 'colorway'),
                'type' => 'option',
                'setting_type' => 'select',
                'transport' => 'postMessage',
                'default' => '',
                'choices' => $fontsz
            ),
            'footer_fontsize_link' => array(
                'id' => 'inkthemes_options[footer_fontsize_link]',
                'label' => __('Bottom Footer Link Font Size', 'colorway'),
                'description' => __('Allows you to set Bottom Footer Link Font Size.', 'colorway'),
                'type' => 'option',
                'setting_type' => 'select',
                'transport' => 'postMessage',
                'default' => '8',
                'choices' => $fontsz
            ),
            'typography_menu_fontsize' => array(
                'id' => 'inkthemes_options[typography_menu_fontsize]',
                'label' => __('Menu Text Font Size', 'colorway'),
                'description' => __('Allows you to set Font Size for Menu Text.', 'colorway'),
                'type' => 'option',
                'setting_type' => 'select',
                'transport' => 'postMessage',
                'default' => '8',
                'choices' => $fontsz
            ),
            'typography_btn_fontsize' => array(
                'id' => 'inkthemes_options[typography_btn_fontsize]',
                'label' => __('Menu Button Font Size', 'colorway'),
                'description' => __('Allows you to set Font Size for Menu Button.', 'colorway'),
                'type' => 'option',
                'setting_type' => 'select',
                'transport' => 'postMessage',
                'default' => '9',
                'choices' => $fontsz
            ),
            'inkthemes_date' => array(
                'id' => 'inkthemes_options[inkthemes_date]',
                'label' => __('Display Date Meta', 'colorway'),
                'description' => '',
                'type' => 'option',
                'setting_type' => 'radio',
                'default' => 'on',
                'choices' => array(
                    'on' => 'On',
                    'off' => 'Off'
                )
            ),
            'inkthemes_author' => array(
                'id' => 'inkthemes_options[inkthemes_author]',
                'label' => __('Display Author Meta', 'colorway'),
                'description' => '',
                'type' => 'option',
                'setting_type' => 'radio',
                'default' => 'on',
                'choices' => array(
                    'on' => 'On',
                    'off' => 'Off'
                )
            ),
            'inkthemes_categories' => array(
                'id' => 'inkthemes_options[inkthemes_categories]',
                'label' => __('Display Categories ', 'colorway'),
                'description' => '',
                'type' => 'option',
                'setting_type' => 'radio',
                'default' => 'on',
                'choices' => array(
                    'on' => 'On',
                    'off' => 'Off'
                )
            ),
            'inkthemes_comments' => array(
                'id' => 'inkthemes_options[inkthemes_comments]',
                'label' => __('Display Comments', 'colorway'),
                'description' => '',
                'type' => 'option',
                'setting_type' => 'radio',
                'default' => 'on',
                'choices' => array(
                    'on' => 'On',
                    'off' => 'Off'
                )
            ),
            'singlepage_date' => array(
                'id' => 'inkthemes_options[singlepage_date]',
                'label' => __('Display Date', 'colorway'),
                'description' => '',
                'type' => 'option',
                'setting_type' => 'radio',
                'default' => 'on',
                'choices' => array(
                    'on' => 'On',
                    'off' => 'Off'
                )
            ),
            'singlepage_author' => array(
                'id' => 'inkthemes_options[singlepage_author]',
                'label' => __('Display Author', 'colorway'),
                'description' => '',
                'type' => 'option',
                'setting_type' => 'radio',
                'default' => 'on',
                'choices' => array(
                    'on' => 'On',
                    'off' => 'Off'
                )
            ),
            'singlepage_categories' => array(
                'id' => 'inkthemes_options[singlepage_categories]',
                'label' => __('Display Categories', 'colorway'),
                'description' => '',
                'type' => 'option',
                'setting_type' => 'radio',
                'default' => 'on',
                'choices' => array(
                    'on' => 'On',
                    'off' => 'Off'
                )
            ),
            'singlepage_comments' => array(
                'id' => 'inkthemes_options[singlepage_comments]',
                'label' => __('Display Comments', 'colorway'),
                'description' => '',
                'type' => 'option',
                'setting_type' => 'radio',
                'default' => 'on',
                'choices' => array(
                    'on' => 'On',
                    'off' => 'Off'
                )
            ),
            'header_bg_color' => array(
                'id' => 'inkthemes_options[header_bg_color]',
                'label' => __('Header Background Color', 'colorway'),
                'description' => __('Set Header Background Color for the Theme.', 'colorway'),
                'type' => 'option',
                'setting_type' => 'alpha-color',
                'palette' => true,
                'default' => '',
            ),
            'site_title_color' => array(
                'id' => 'inkthemes_options[site_title_color]',
                'label' => __('Site Title Color', 'colorway'),
                'description' => __('Set Site Title Color for Header.', 'colorway'),
                'type' => 'option',
                'setting_type' => 'alpha-color',
                'palette' => true,
                'default' => '#3868bb',
            ),
            'site_tagline_color' => array(
                'id' => 'inkthemes_options[site_tagline_color]',
                'label' => __('Site Tagline Color', 'colorway'),
                'description' => __('Set Site Tagline Color for Header.', 'colorway'),
                'type' => 'option',
                'setting_type' => 'alpha-color',
                'palette' => true,
                'default' => '',
            ),
            'menu_link_color' => array(
                'id' => 'inkthemes_options[menu_link_color]',
                'label' => __('Menu Link Color', 'colorway'),
                'description' => __('Set Menu Link Color for Header.', 'colorway'),
                'type' => 'option',
                'setting_type' => 'alpha-color',
                'palette' => true,
                'default' => '',
            ),
            'menu_hover_color' => array(
                'id' => 'inkthemes_options[menu_hover_color]',
                'label' => __('Menu Link Hover Color', 'colorway'),
                'description' => __('Set Menu Hover Link Color for Header.', 'colorway'),
                'type' => 'option',
                'setting_type' => 'alpha-color',
                'palette' => true,
                'default' => '',
            ),
            'menu_background_color' => array(
                'id' => 'inkthemes_options[menu_background_color]',
                'label' => __('Menu Background Color', 'colorway'),
                'description' => __('Set Menu background Color for Header.', 'colorway'),
                'type' => 'option',
                'setting_type' => 'alpha-color',
                'palette' => true,
                'default' => '',
            ),
            'menu_background_hover_color' => array(
                'id' => 'inkthemes_options[menu_background_hover_color]',
                'label' => __('Menu Background Hover Color', 'colorway'),
                'description' => __('Set Menu Hover background Color for Header.', 'colorway'),
                'type' => 'option',
                'setting_type' => 'alpha-color',
                'palette' => true,
                'default' => '',
            ),
            'menu_dropdown_hover_color' => array(
                'id' => 'inkthemes_options[menu_dropdown_hover_color]',
                'label' => __('Menu Dropdown Background Hover Color', 'colorway'),
                'description' => __('Set Menu Dropdown Hover background Color for Header.', 'colorway'),
                'type' => 'option',
                'setting_type' => 'alpha-color',
                'palette' => true,
                'default' => '',
            ),
            'theme_link_color' => array(
                'id' => 'inkthemes_options[theme_link_color]',
                'label' => __('Link Color', 'colorway'),
                'description' => __('Set Link Color for Theme.', 'colorway'),
                'type' => 'option',
                'setting_type' => 'alpha-color',
                'palette' => true,
                'default' => '',
            ),
            'theme_link_hover_color' => array(
                'id' => 'inkthemes_options[theme_link_hover_color]',
                'label' => __('Link Hover Color', 'colorway'),
                'description' => __('Set Link Hover Color for Theme.', 'colorway'),
                'type' => 'option',
                'setting_type' => 'alpha-color',
                'palette' => true,
                'default' => '',
            ),
            'theme_para_color' => array(
                'id' => 'inkthemes_options[theme_para_color]',
                'label' => __('Paragraph Text Color', 'colorway'),
                'description' => __('Set Text Color for Theme.', 'colorway'),
                'type' => 'option',
                'setting_type' => 'alpha-color',
                'palette' => true,
                'default' => '',
            ),
            'theme_h1_color' => array(
                'id' => 'inkthemes_options[theme_h1_color]',
                'label' => __('Heading 1 Text Color', 'colorway'),
                'description' => __('Set Heading 1 Text Color for Theme.', 'colorway'),
                'type' => 'option',
                'setting_type' => 'alpha-color',
                'palette' => true,
                'default' => '',
            ),
            'theme_h2_color' => array(
                'id' => 'inkthemes_options[theme_h2_color]',
                'label' => __('Heading 2 Text Color', 'colorway'),
                'description' => __('Set Heading 2 Text Color for Theme.', 'colorway'),
                'type' => 'option',
                'setting_type' => 'alpha-color',
                'palette' => true,
                'default' => '',
            ),
            'theme_h3_color' => array(
                'id' => 'inkthemes_options[theme_h3_color]',
                'label' => __('Heading 3 Text Color', 'colorway'),
                'description' => __('Set Heading 3 Text Color for Theme.', 'colorway'),
                'type' => 'option',
                'setting_type' => 'alpha-color',
                'palette' => true,
                'default' => '',
            ),
            'theme_h4_color' => array(
                'id' => 'inkthemes_options[theme_h4_color]',
                'label' => __('Heading 4 Text Color', 'colorway'),
                'description' => __('Set Heading 4 Text Color for Theme.', 'colorway'),
                'type' => 'option',
                'setting_type' => 'alpha-color',
                'palette' => true,
                'default' => '',
            ),
            'theme_h5_color' => array(
                'id' => 'inkthemes_options[theme_h5_color]',
                'label' => __('Heading 5 Text Color', 'colorway'),
                'description' => __('Set Heading 5 Text Color for Theme.', 'colorway'),
                'type' => 'option',
                'setting_type' => 'alpha-color',
                'palette' => true,
                'default' => '',
            ),
            'theme_h6_color' => array(
                'id' => 'inkthemes_options[theme_h6_color]',
                'label' => __('Heading 6 Text Color', 'colorway'),
                'description' => __('Set Heading 6 Text Color for Theme.', 'colorway'),
                'type' => 'option',
                'setting_type' => 'alpha-color',
                'palette' => true,
                'default' => '',
            ),
            'footer_link_color' => array(
                'id' => 'inkthemes_options[footer_link_color]',
                'label' => __('Link Color', 'colorway'),
                'description' => __('Set Link Color for Footer.', 'colorway'),
                'type' => 'option',
                'setting_type' => 'alpha-color',
                'palette' => true,
                'default' => '#949494',
            ),
            'footer_link_hover_color' => array(
                'id' => 'inkthemes_options[footer_link_hover_color]',
                'label' => __('Link Hover Color', 'colorway'),
                'description' => __('Set Link Hover Color for Footer.', 'colorway'),
                'type' => 'option',
                'setting_type' => 'alpha-color',
                'palette' => true,
                'default' => '',
            ),
            'footer_text_color' => array(
                'id' => 'inkthemes_options[footer_text_color]',
                'label' => __('Text Color', 'colorway'),
                'description' => __('Set Text Color for Footer.', 'colorway'),
                'type' => 'option',
                'default' => '#949494',
                'setting_type' => 'alpha-color',
                'palette' => true
            ),
            'footer_header_color' => array(
                'id' => 'inkthemes_options[footer_header_color]',
                'label' => __('Footer Heading Color', 'colorway'),
                'description' => __('Set Header Color for Footer.', 'colorway'),
                'type' => 'option',
                'setting_type' => 'alpha-color',
                'palette' => true,
                'default' => '#cccccc',
            ),
            'footer_col_bg_color' => array(
                'id' => 'inkthemes_options[footer_col_bg_color]',
                'label' => __('Footer widgetized Background Color', 'colorway'),
                'description' => __('Set Footer widgetized Background Color for Theme.', 'colorway'),
                'type' => 'option',
                'setting_type' => 'alpha-color',
                'palette' => true,
                'default' => '#343434',
            ),
            'bottom_footer_bg_color' => array(
                'id' => 'inkthemes_options[bottom_footer_bg_color]',
                'label' => __('Bottom Footer Background Color', 'colorway'),
                'description' => __('Set Bottom Footer Background Color for Theme.', 'colorway'),
                'type' => 'option',
                'setting_type' => 'alpha-color',
                'palette' => true,
                'default' => '#292929',
            ),
            'inkthemes_footerbg' => array(
                'id' => 'inkthemes_options[inkthemes_footerbg]',
                'label' => __('Footer Background Image', 'colorway'),
                'description' => __('Set Footer Background Image for Theme.', 'colorway'),
                'type' => 'option',
                'setting_type' => 'image',
                'default' => '',
            ),
        );
        return $theme_settings;
    }

    public static function inkthemes_Controls($wp_customize) {

        $sections = self::inkthemes_Section_Content();
        $settings = self::inkthemes_Settings();

        /*
         * Begin: Calling Selective refresh function for each settings.
         */
        foreach ($settings as $k => $v) {
            if ($v['id'] == 'inkthemes_options[sticky_logo_setting]' || $v['id'] == 'inkthemes_options[border_bottom_on_off]' || $v['id'] == 'inkthemes_options[button_bg_hover_color]' || $v['id'] == 'inkthemes_options[inkthemes_footerbg]' || $v['id'] == 'inkthemes_options[colorway_slideimage1]' || $v['id'] == 'inkthemes_options[colorway_slideimage2]' || $v['id'] == 'inkthemes_options[colorway_logo]' || $v['id'] == 'inkthemes_options[colorway_dummy_data]' || $v['id'] == 'inkthemes_options[colorway_button_html]' || $v['id'] == 'inkthemes_options[colorway_feature_select]' || $v['id'] == 'inkthemes_options[footer_col_area_select]' || $v['id'] == 'inkthemes_options[colorway_twitter]' || $v['id'] == 'inkthemes_options[colorway_facebook]' || $v['id'] == 'inkthemes_options[colorway_rss]' || $v['id'] == 'inkthemes_options[colorway_linkedin]' || $v['id'] == 'inkthemes_options[colorway_stumble]' || $v['id'] == 'inkthemes_options[colorway_digg]' || $v['id'] == 'inkthemes_options[inkthemes_flickr]' || $v['id'] == 'inkthemes_options[inkthemes_instagram]' || $v['id'] == 'inkthemes_options[inkthemes_pinterest]' || $v['id'] == 'inkthemes_options[inkthemes_tumblr]' || $v['id'] == 'inkthemes_options[youtube]' || $v['id'] == 'inkthemes_options[google]' || $v['id'] == 'inkthemes_options[logo_option]' || $v['id'] == 'inkthemes_options[inkthemes_fimg1]' || $v['id'] == 'inkthemes_options[inkthemes_fimg2]' || $v['id'] == 'inkthemes_options[inkthemes_fimg3]' || $v['id'] == 'inkthemes_options[inkthemes_fimg4]' || $v['id'] == 'inkthemes_options[inkthemes_testimonial_img]' || $v['id'] == 'inkthemes_options[inkthemes_testimonial_img_2]' || $v['id'] == 'inkthemes_options[inkthemes_testimonial_img_3]' || $v['id'] == 'inkthemes_options[singlepage_date]' || $v['id'] == 'inkthemes_options[singlepage_author]' || $v['id'] == 'inkthemes_options[singlepage_categories]' || $v['id'] == 'inkthemes_options[singlepage_comments]' || $v['id'] == 'inkthemes_options[inkthemes_date]' || $v['id'] == 'inkthemes_options[inkthemes_author]' || $v['id'] == 'inkthemes_options[inkthemes_categories]' || $v['id'] == 'inkthemes_options[inkthemes_comments]' || $v['id'] == 'inkthemes_options[typography_logo_family]' || $v['id'] == 'inkthemes_options[typography_menu_family]' || $v['id'] == 'inkthemes_options[typography_nav_family]' || $v['id'] == 'inkthemes_options[typography_para]' || $v['id'] == 'inkthemes_options[typography_heading1]' || $v['id'] == 'inkthemes_options[typography_heading2]' || $v['id'] == 'inkthemes_options[typography_heading3]' || $v['id'] == 'inkthemes_options[typography_heading4]' || $v['id'] == 'inkthemes_options[typography_heading5]' || $v['id'] == 'inkthemes_options[typography_heading6]' || $v['id'] == 'inkthemes_options[typography_title_fontweight]' || $v['id'] == 'inkthemes_options[typography_tagline_fontweight]' || $v['id'] == 'inkthemes_options[typography_fontweight_navmenu]' || $v['id'] == 'inkthemes_options[typography_fontweight_para]' || $v['id'] == 'inkthemes_options[typography_fontweight_heading1]' || $v['id'] == 'inkthemes_options[typography_fontweight_heading2]' || $v['id'] == 'inkthemes_options[typography_fontweight_heading3]' || $v['id'] == 'inkthemes_options[typography_fontweight_heading4]' || $v['id'] == 'inkthemes_options[typography_fontweight_heading5]' || $v['id'] == 'inkthemes_options[typography_fontweight_heading6]' || $v['id'] == 'inkthemes_options[typography_btn_fontsize]' || $v['id'] == 'inkthemes_options[typography_menu_fontsize]' || $v['id'] == 'inkthemes_options[typography_fontsize_para]' || $v['id'] == 'inkthemes_options[typography_fontsize_heading1]' || $v['id'] == 'inkthemes_options[typography_fontsize_heading2]' || $v['id'] == 'inkthemes_options[typography_fontsize_heading3]' || $v['id'] == 'inkthemes_options[typography_fontsize_heading4]' || $v['id'] == 'inkthemes_options[typography_fontsize_heading5]' || $v['id'] == 'inkthemes_options[typography_fontsize_heading6]' || $v['id'] == 'inkthemes_options[footer_fontsize_para]' || $v['id'] == 'inkthemes_options[footer_fontsize_link]' || $v['id'] == 'inkthemes_options[typography_fontsize_heading6]' || $v['id'] == 'inkthemes_options[btn_on_off]' || $v['id'] == 'inkthemes_options[footer_col_on_off]' || $v['id'] == 'inkthemes_options[feature_on_off]' || $v['id'] == 'inkthemes_options[colorway_testimonial_status]' || $v['id'] == 'inkthemes_options[colorway_home_page_slider]' || $v['id'] == 'inkthemes_options[colorway_home_page_blog_post]' || $v['id'] == 'inkthemes_options[footer_link_hover_color]' || $v['id'] == 'inkthemes_options[theme_link_hover_color]' || $v['id'] == 'inkthemes_options[menu_hover_color]' || $v['id'] == 'inkthemes_options[menu_background_hover_color]') {
                $wp_customize->selective_refresh->add_partial($v['id'], array(
                    'selector' => '.' . $k,
                ));
            } else {
                $wp_customize->selective_refresh->add_partial($v['id'], array(
                    'selector' => '.' . $k,
                    'container_inclusive' => false,
                    'fallback_refresh' => false,
                ));
            }
        }
        /*
         * End: Calling Selective refresh function for each settings.
         */

        foreach ($sections as $section_id => $section_content) {

            foreach ($section_content as $section_content_id) {

                switch ($settings[$section_content_id]['setting_type']) {
                    case 'image':
                        self::add_setting($wp_customize, $settings[$section_content_id]['id'], $settings[$section_content_id]['default'], $settings[$section_content_id]['type'], 'inkthemes_sanitize_url');
                        $wp_customize->add_control(new WP_Customize_Image_Control(
                                $wp_customize, $settings[$section_content_id]['id'], array(
                            'label' => $settings[$section_content_id]['label'],
                            'description' => $settings[$section_content_id]['description'],
                            'section' => $section_id,
                            'settings' => $settings[$section_content_id]['id'],
                                )
                        ));
                        break;

                    case 'text':
                        self::add_setting($wp_customize, $settings[$section_content_id]['id'], $settings[$section_content_id]['default'], $settings[$section_content_id]['type'], 'inkthemes_sanitize_text');

                        $wp_customize->add_control(new WP_Customize_Control(
                                $wp_customize, $settings[$section_content_id]['id'], array(
                            'label' => $settings[$section_content_id]['label'],
                            'description' => $settings[$section_content_id]['description'],
                            'section' => $section_id,
                            'settings' => $settings[$section_content_id]['id'],
                            'type' => 'text'
                                )
                        ));
                        break;

                    case 'textarea':

                        self::add_setting($wp_customize, $settings[$section_content_id]['id'], $settings[$section_content_id]['default'], $settings[$section_content_id]['type'], 'inkthemes_sanitize_textarea');

                        $wp_customize->add_control(new WP_Customize_Control(
                                $wp_customize, $settings[$section_content_id]['id'], array(
                            'label' => $settings[$section_content_id]['label'],
                            'description' => $settings[$section_content_id]['description'],
                            'section' => $section_id,
                            'settings' => $settings[$section_content_id]['id'],
                            'type' => 'textarea'
                                )
                        ));
                        break;

                    case 'link':

                        self::add_setting($wp_customize, $settings[$section_content_id]['id'], $settings[$section_content_id]['default'], $settings[$section_content_id]['type'], 'inkthemes_sanitize_url');

                        $wp_customize->add_control(new WP_Customize_Control(
                                $wp_customize, $settings[$section_content_id]['id'], array(
                            'label' => $settings[$section_content_id]['label'],
                            'description' => $settings[$section_content_id]['description'],
                            'section' => $section_id,
                            'settings' => $settings[$section_content_id]['id'],
                            'type' => 'text'
                                )
                        ));
                        break;

//                    case 'color':
//                        self::add_setting($wp_customize, $settings[$section_content_id]['id'], $settings[$section_content_id]['default'], $settings[$section_content_id]['type'], 'inkthemes_sanitize_color');
//
//                        $wp_customize->add_control(new WP_Customize_Color_Control(
//                                $wp_customize, $settings[$section_content_id]['id'], array(
//                            'label' => $settings[$section_content_id]['label'],
//                            'description' => $settings[$section_content_id]['description'],
//                            'section' => $section_id,
//                            'settings' => $settings[$section_content_id]['id'],
//                                )
//                        ));
//                        break;

                    case 'alpha-color':
                        self::add_setting($wp_customize, $settings[$section_content_id]['id'], $settings[$section_content_id]['default'], $settings[$section_content_id]['type'], 'inkthemes_sanitize_color');
                        $wp_customize->add_control(new Customize_Alpha_Color_Control(
                                $wp_customize, $settings[$section_content_id]['id'], array(
                            'label' => $settings[$section_content_id]['label'],
                            'description' => $settings[$section_content_id]['description'],
                            'section' => $section_id,
                            'settings' => $settings[$section_content_id]['id'],
                            'palette' => $settings[$section_content_id]['palette']
                                )
                        ));
                        break;
                    case 'number':

                        self::add_setting($wp_customize, $settings[$section_content_id]['id'], $settings[$section_content_id]['default'], $settings[$section_content_id]['type'], 'inkthemes_sanitize_number');

                        $wp_customize->add_control(new WP_Customize_Control(
                                $wp_customize, $settings[$section_content_id]['id'], array(
                            'label' => $settings[$section_content_id]['label'],
                            'description' => $settings[$section_content_id]['description'],
                            'section' => $section_id,
                            'settings' => $settings[$section_content_id]['id'],
                            'type' => 'text'
                                )
                        ));
                        break;

                    case 'select':

                        self::add_setting($wp_customize, $settings[$section_content_id]['id'], $settings[$section_content_id]['default'], $settings[$section_content_id]['type'], 'inkthemes_sanitize_select');

                        $wp_customize->add_control(new WP_Customize_Control(
                                $wp_customize, $settings[$section_content_id]['id'], array(
                            'label' => $settings[$section_content_id]['label'],
                            'description' => $settings[$section_content_id]['description'],
                            'section' => $section_id,
                            'settings' => $settings[$section_content_id]['id'],
                            'type' => 'select',
                            'choices' => $settings[$section_content_id]['choices']
                                )
                        ));
                        break;

                    case 'radio':

                        self::add_setting($wp_customize, $settings[$section_content_id]['id'], $settings[$section_content_id]['default'], $settings[$section_content_id]['type'], 'inkthemes_sanitize_radio');

                        $wp_customize->add_control(new WP_Customize_Control(
                                $wp_customize, $settings[$section_content_id]['id'], array(
                            'label' => $settings[$section_content_id]['label'],
                            'description' => $settings[$section_content_id]['description'],
                            'section' => $section_id,
                            'settings' => $settings[$section_content_id]['id'],
                            'type' => 'radio',
                            'choices' => $settings[$section_content_id]['choices'],
                            'transport' => 'postMessage'
                                )
                        ));
                        break;


                    case 'checkbox':

                        self::add_setting($wp_customize, $settings[$section_content_id]['id'], $settings[$section_content_id]['default'], $settings[$section_content_id]['type'], 'inkthemes_sanitize_checkbox');

                        $wp_customize->add_control(new WP_Customize_Control(
                                $wp_customize, $settings[$section_content_id]['id'], array(
                            'label' => $settings[$section_content_id]['label'],
                            'description' => $settings[$section_content_id]['description'],
                            'section' => $section_id,
                            'settings' => $settings[$section_content_id]['id'],
                            'type' => 'checkbox',
                                )
                        ));
                        break;

                    default:
                        break;
                }
            }
        }
    }

    public static function add_setting($wp_customize, $setting_id, $default, $type, $sanitize_callback) {
        $wp_customize->add_setting($setting_id, array(
            'default' => $default,
            'capability' => 'edit_theme_options',
            'sanitize_callback' => array('inkthemes_Customizer', $sanitize_callback),
            'type' => $type,
            'transport' => 'postMessage'
                )
        );
    }

    /**
     * adds sanitization callback funtion : textarea
     * @package inkthemes
     */
    public static function inkthemes_sanitize_textarea($value) {
        $value = esc_html($value);
        return $value;
    }

    /**
     * adds sanitization callback funtion : url
     * @package inkthemes
     */
    public static function inkthemes_sanitize_url($value) {
        $value = esc_url($value);
        return $value;
    }

    /**
     * adds sanitization callback funtion : text
     * @package inkthemes
     */
    public static function inkthemes_sanitize_text($value) {
        $value = sanitize_text_field($value);
        return $value;
    }

    /**
     * adds sanitization callback funtion : email
     * @package inkthemes
     */
    public static function inkthemes_sanitize_email($value) {
        $value = sanitize_email($value);
        return $value;
    }

//    public static function inkthemes_sanitize_checkbox($checked) {
//        // Boolean check.
//        return ( ( isset($checked) && 'yes' == $checked ) ? 'yes' : 'no' );
//    }

    public static function inkthemes_sanitize_checkbox($checked) {
        // Boolean check.
        return ( ( isset($checked) && true == $checked ) ? true : false );
    }

    /**
     * adds sanitization callback funtion : number
     * @package inkthemes
     */
    public static function inkthemes_sanitize_number($value) {
        $value = preg_replace("/[^0-9+ ]/", "", $value);
        return $value;
    }

    /**
     * adds sanitization callback funtion : number
     * @package inkthemes
     */
    public static function inkthemes_sanitize_color($value) {
        return $value;
    }

    /**
     * adds sanitization callback funtion : select
     * @package inkthemes
     */
    public static function inkthemes_sanitize_select($value, $setting) {
        global $wp_customize;
        $control = $wp_customize->get_control($setting->id);
        if (array_key_exists($value, $control->choices)) {
            return $value;
        } else {
            return $setting->default;
        }
    }

    /**
     * adds sanitization callback funtion : radio
     * @package inkthemes
     */
    public static function inkthemes_sanitize_radio($value, $setting) {
        global $wp_customize;
        $control = $wp_customize->get_control($setting->id);
        if (array_key_exists($value, $control->choices)) {
            return $value;
        } else {
            return $setting->default;
        }
    }

}

// Setup the Theme Customizer settings and controls...
add_action('customize_register', array('inkthemes_Customizer', 'inkthemes_Register'));

//function inkthemes_registers() {
//    wp_register_script('inkthemes_jquery_ui', '//code.jquery.com/ui/1.11.0/jquery-ui.js', array("jquery"), true);
//    wp_register_script('inkthemes_customizer_script', get_template_directory_uri() . '/functions/js/inkthemes_customizer.js', array("jquery", "inkthemes_jquery_ui"), true);
//    wp_enqueue_script('inkthemes_customizer_script');
//    wp_localize_script('inkthemes_customizer_script', 'ink_advert', array(
//        'pro' => __('View PRO version', 'colorway'),
//        'url' => esc_url('https://www.inkthemes.com/market/colorway-wp-theme/'),
//        'support_text' => __('Need Help!', 'colorway'),
//        'support_url' => esc_url('https://www.inkthemes.com/contact-us/'),
//            )
//    );
//}

function inkthemes_registers() {
    if (is_plugin_active('colorway-sites/colorway-sites.php')) {
        wp_register_script('inkthemes_jquery_ui', '//code.jquery.com/ui/1.11.0/jquery-ui.js', array("jquery"), true);
        wp_register_script('inkthemes_customizer_script', get_template_directory_uri() . '/functions/js/inkthemes_customizer.js', array("jquery", "inkthemes_jquery_ui"), true);
        wp_enqueue_script('inkthemes_customizer_script');
        include_once ABSPATH . 'wp-admin/includes/plugin.php';

        wp_localize_script('inkthemes_customizer_script', 'ink_advert', array(
            'support_text' => __('See ColorWay Sites Library', 'colorway'),
            'support_url' => esc_url(admin_url('themes.php?page=colorway-sites')),
        ));
    }
}

add_action('customize_controls_enqueue_scripts', 'inkthemes_registers');

function colorway_customize_register($wp_customize) {


    class WP_Customize_Range_Control extends WP_Customize_Control {

        public $type = 'custom_range';

        public function enqueue() {
            wp_enqueue_script('inkthemes-range-control', get_template_directory_uri() . '/functions/js/range-control.js', array('jquery'));
        }

        public function render_content() {
            ?>
            <label>
                <?php if (!empty($this->label)) : ?>
                    <span class="customize-control-title"><?php echo esc_html($this->label); ?></span>
                <?php endif; ?>
                <div class="cs-range-value"></div>
                <input data-input-type="range" type="range" <?php $this->input_attrs(); ?> value="<?php echo esc_attr($this->value()); ?>" <?php $this->link(); ?> />
                <?php if (!empty($this->description)) : ?>
                    <span class="description customize-control-description"><?php echo esc_html($this->description); ?></span>
                <?php endif; ?>
            </label>
            <?php
        }

    }

    //blog name and description
    $wp_customize->get_setting('blogname')->transport = 'postMessage';
    $wp_customize->get_setting('blogdescription')->transport = 'postMessage';
// Add the selective part


    $wp_customize->selective_refresh->add_partial('blogname', array(
        'selector' => '.site-title',
        'render_callback' => 'colorway_customize_partial_blogname',
        'container_inclusive' => false,
        'fallback_refresh' => false,
    ));
    $wp_customize->selective_refresh->add_partial('blogdescription', array(
        'selector' => '.site-description',
        'render_callback' => 'colorway_customize_partial_blogdescription',
        'container_inclusive' => false,
        'fallback_refresh' => false,
    ));

    //     blog layout and page layout
    // Load the radio image control class.
    require_once( trailingslashit(get_template_directory()) . 'functions/load.php' );

    // Register the radio image control class as a JS control type.
    $wp_customize->register_control_type('Colorway_Customize_Control_Radio_Image');



    // Add the Container layout setting.
    $wp_customize->add_setting(
            'container-layout', array(
        'type' => 'option',
        'default' => 'fullwidth-container',
        'sanitize_callback' => 'sanitize_key',
            )
    );
    // Add the Container layout control.
    $wp_customize->add_control(
            new Colorway_Customize_Control_Radio_Image(
            $wp_customize, 'container-layout', array(
        'label' => esc_html__('Container Layout', 'colorway'),
        'section' => 'container_layout_section',
        'priority' => '1',
        'choices' => array(
            'container' => array(
                'label' => esc_html__('Boxed Container Layout', 'colorway'),
                'url' => get_template_directory_uri() . '/assets/images/box-content.png'
            ),
            'fullwidth-container' => array(
                'label' => esc_html__('Fullwidth Container Layout', 'colorway'),
                'url' => get_template_directory_uri() . '/assets/images/full-width-content.png'
            ),
        )
            )
            )
    );

    // Add the Header layout setting.
    $wp_customize->add_setting(
            'header-layout', array(
        'type' => 'option',
        'default' => 'logo-menu',
        'sanitize_callback' => 'sanitize_key',
            )
    );

    // Add the Header layout control.
    $wp_customize->add_control(
            new Colorway_Customize_Control_Radio_Image(
            $wp_customize, 'header-layout', array(
        'label' => esc_html__('Header Layout', 'colorway'),
        'section' => 'header_layout_section',
        'priority' => '2',
        'choices' => array(
            'logo-menu' => array(
                'label' => esc_html__('Logo Menu Layout', 'colorway'),
                'url' => get_template_directory_uri() . '/assets/images/header-layout-1.png'
            ),
            'menu-logo' => array(
                'label' => esc_html__('Menu Logo Layout', 'colorway'),
                'url' => get_template_directory_uri() . '/assets/images/header-layout-2.png'
            ),
            'content-center' => array(
                'label' => esc_html__('Center Layout', 'colorway'),
                'url' => get_template_directory_uri() . '/assets/images/header-layout-3.png'
            )
        )
            )
            )
    );

    // Add the blog layout setting.
    $wp_customize->add_setting(
            'blog-layout', array(
        'type' => 'option',
        'default' => 'content-sidebar',
        'sanitize_callback' => 'sanitize_key',
            )
    );

    // Add the Blog Sidebar layout control.
    $wp_customize->add_control(
            new Colorway_Customize_Control_Radio_Image(
            $wp_customize, 'blog-layout', array(
        'label' => esc_html__('Blog/Archives/Category Sidebar Layout', 'colorway'),
        'section' => 'blog_layout_section',
        'priority' => '3',
        'choices' => array(
            'content-sidebar' => array(
                'label' => esc_html__('Left Layout', 'colorway'),
                'url' => get_template_directory_uri() . '/assets/images/right-sidebar.jpg'
            ),
            'sidebar-content' => array(
                'label' => esc_html__('Right Layout', 'colorway'),
                'url' => get_template_directory_uri() . '/assets/images/left-sidebar.jpg'
            ),
            'content' => array(
                'label' => esc_html__('Center Layout', 'colorway'),
                'url' => get_template_directory_uri() . '/assets/images/center.jpg'
            )
        )
            )
            )
    );

    // Add the page layout setting.
    $wp_customize->add_setting(
            'page-layout', array(
        'type' => 'option',
        'default' => 'content-sidebar',
        'sanitize_callback' => 'sanitize_key',
//               'transport' => 'postMessage'
            )
    );
    // Add the Page Sidebar layout control.
    $wp_customize->add_control(
            new Colorway_Customize_Control_Radio_Image(
            $wp_customize, 'page-layout', array(
        'label' => esc_html__('Page Sidebar Layout', 'colorway'),
        'section' => 'page_layout_section',
        'priority' => '4',
        'choices' => array(
            'content-sidebar' => array(
                'label' => esc_html__('Left Layout', 'colorway'),
                'url' => get_template_directory_uri() . '/assets/images/left-sidebar2.jpg'
            ),
            'sidebar-content' => array(
                'label' => esc_html__('Right Layout', 'colorway'),
                'url' => get_template_directory_uri() . '/assets/images/right-sidebar2.jpg'
            ),
            'content' => array(
                'label' => esc_html__('Center Layout', 'colorway'),
                'url' => get_template_directory_uri() . '/assets/images/center2.jpg'
            )
        )
            )
            )
    );
    // Add the page layout setting.
    $wp_customize->add_setting(
            'singlepage-layout', array(
        'type' => 'option',
        'default' => 'content-sidebar',
        'sanitize_callback' => 'sanitize_key',
            )
    );
    // Add the Page Sidebar layout control.
    $wp_customize->add_control(
            new Colorway_Customize_Control_Radio_Image(
            $wp_customize, 'singlepage-layout', array(
        'label' => esc_html__('Single Post Layout', 'colorway'),
        'section' => 'singlepage_layout_section',
        'priority' => '5',
        'choices' => array(
            'content-sidebar' => array(
                'label' => esc_html__('Left Layout', 'colorway'),
                'url' => get_template_directory_uri() . '/assets/images/left-sidebar2.jpg'
            ),
            'sidebar-content' => array(
                'label' => esc_html__('Right Layout', 'colorway'),
                'url' => get_template_directory_uri() . '/assets/images/right-sidebar2.jpg'
            ),
            'content' => array(
                'label' => esc_html__('Center Layout', 'colorway'),
                'url' => get_template_directory_uri() . '/assets/images/center2.jpg'
            )
        )
            )
            )
    );

    // Add the Footer layout setting.
    $wp_customize->add_setting(
            'footer-layout', array(
        'type' => 'option',
        'default' => 'inline-footer',
        'sanitize_callback' => 'sanitize_key',
        'transport' => 'postMessage'
            )
    );
    // Add the Footer layout control.
    $wp_customize->add_control(
            new Colorway_Customize_Control_Radio_Image(
            $wp_customize, 'footer-layout', array(
        'label' => esc_html__('Bottom Footer Layout', 'colorway'),
        'section' => 'footer_layout_section',
        'priority' => '7',
        'choices' => array(
            'inline-footer' => array(
                'label' => esc_html__('Footer Inline Layout', 'colorway'),
                'url' => get_template_directory_uri() . '/assets/images/footer-layout-2.png'
            ),
            'diable-footer' => array(
                'label' => esc_html__('Disable Footer Layout', 'colorway'),
                'url' => get_template_directory_uri() . '/assets/images/disabled-footer.png'
            ),
            'center-footer' => array(
                'label' => esc_html__('Center Footer Layout', 'colorway'),
                'url' => get_template_directory_uri() . '/assets/images/footer-layout-1.png'
            )
        )
            )
            )
    );

    /** Site Title Settings * */
    $wp_customize->add_setting('title_font_size', array(
        'default' => '34',
        'type' => 'option',
        'capability' => 'edit_theme_options',
        'type' => 'option',
        'sanitize_callback' => 'sanitize_key',
        'transport' => 'postMessage'
            )
    );
    $wp_customize->add_control(new WP_Customize_Range_Control($wp_customize, 'title_font_size_control', array(
        'label' => __('Site Title Font Size', 'colorway'),
        'section' => 'title_tagline',
        'settings' => 'title_font_size',
        'description' => __('Measurement is in pixel.', 'colorway'),
        'input_attrs' => array(
            'min' => 10,
            'max' => 70,
        ),
            )
            )
    );

    /** Site Description Settings * */
    $wp_customize->add_setting('desc_font_size', array(
        'default' => '16',
        'type' => 'option',
        'sanitize_callback' => 'sanitize_key',
        'capability' => 'edit_theme_options',
        'transport' => 'postMessage'
            )
    );
    $wp_customize->add_control(new WP_Customize_Range_Control($wp_customize, 'desc_font_size_control', array(
        'label' => __('Site Tagline Font Size', 'colorway'),
        'section' => 'title_tagline',
        'settings' => 'desc_font_size',
        'description' => __('Measurement is in pixel.', 'colorway'),
        'input_attrs' => array(
            'min' => 10,
            'max' => 50,
        ),
            )
            )
    );

    $wp_customize->add_setting('logo_width', array(
        'default' => '70',
        'type' => 'option',
        'sanitize_callback' => 'sanitize_key',
        'capability' => 'edit_theme_options',
        'transport' => 'postMessage'
            )
    );

    // Do stuff with $wp_customize, the WP_Customize_Manager object.
    $wp_customize->add_control(new WP_Customize_Range_Control($wp_customize, 'logo_width_option', array(
        'label' => __('Set Logo Size', 'colorway'),
        'section' => 'logo_fevi_setting',
        'settings' => 'logo_width',
        'description' => __('Measurement is in percentage.', 'colorway'),
        'input_attrs' => array(
            'min' => 1,
            'max' => 100,
        ),
            )
            )
    );
    $wp_customize->add_setting('stky_logo_width', array(
        'default' => '70',
        'type' => 'option',
        'sanitize_callback' => 'sanitize_key',
        'capability' => 'edit_theme_options',
        'transport' => 'postMessage'
            )
    );

    // Do stuff with $wp_customize, the WP_Customize_Manager object.
    $wp_customize->add_control(new WP_Customize_Range_Control($wp_customize, 'stky_logo_width_option', array(
        'label' => __('Set Sticky Logo Size', 'colorway'),
        'section' => 'header_layout_section',
        'settings' => 'stky_logo_width',
        'description' => __('Measurement is in percentage.', 'colorway'),
        'input_attrs' => array(
            'min' => 1,
            'max' => 100,
        ),
            )
            )
    );

    $wp_customize->add_setting('btn_rad', array(
        'default' => '5',
        'type' => 'option',
        'sanitize_callback' => 'sanitize_key',
        'capability' => 'edit_theme_options',
        'transport' => 'postMessage'
            )
    );

    // Do stuff with $wp_customize, the WP_Customize_Manager object.
    $wp_customize->add_control(new WP_Customize_Range_Control($wp_customize, 'border_rad_option', array(
        'label' => __('Set Button Radius', 'colorway'),
        'section' => 'menu_button_css',
        'settings' => 'btn_rad',
        'description' => __('Measurement is in pixel.', 'colorway'),
        'input_attrs' => array(
            'min' => 0,
            'max' => 30,
        ),
            )
            )
    );
    $wp_customize->add_setting('btn_h_pad', array(
        'default' => '21',
        'type' => 'option',
        'sanitize_callback' => 'sanitize_key',
        'capability' => 'edit_theme_options',
//        'transport' => 'postMessage'
            )
    );

    // Do stuff with $wp_customize, the WP_Customize_Manager object.
    $wp_customize->add_control(new WP_Customize_Range_Control($wp_customize, 'pad_h_option', array(
        'label' => __('Set Horizontal Padding', 'colorway'),
        'section' => 'menu_button_css',
        'settings' => 'btn_h_pad',
        'description' => __('Measurement is in pixel.', 'colorway'),
        'input_attrs' => array(
            'min' => 0,
            'max' => 100,
        ),
            )
            )
    );
    $wp_customize->add_setting('btn_v_pad', array(
        'default' => '3',
        'type' => 'option',
        'sanitize_callback' => 'sanitize_key',
        'capability' => 'edit_theme_options',
            // 'transport' => 'postMessage'
            )
    );

    // Do stuff with $wp_customize, the WP_Customize_Manager object.
    $wp_customize->add_control(new WP_Customize_Range_Control($wp_customize, 'pad_v_option', array(
        'label' => __('Set Vertical Padding', 'colorway'),
        'section' => 'menu_button_css',
        'settings' => 'btn_v_pad',
        'description' => __('Measurement is in pixel.', 'colorway'),
        'input_attrs' => array(
            'min' => 0,
            'max' => 100,
        ),
            )
            )
    );
//    $wp_customize->add_setting('header_height', array(
//        'default' => '',
//        'type' => 'option',
//        'capability' => 'edit_theme_options'
//            )
//    );
//
//    // Do stuff with $wp_customize, the WP_Customize_Manager object.
//    $wp_customize->add_control(new WP_Customize_Range_Control($wp_customize, 'header_height_option', array(
//        'label' => __('Header Height'),
//        'section' => 'header_layout_section',
//        'settings' => 'header_height',
//        'description' => __('Measurement is in pixel.'),
//        'input_attrs' => array(
//            'min' => 0,
//            'max' => 100,
//        ),
//            )
//            )
//    );
    $wp_customize->add_setting('header_v_pad', array(
        'default' => '45',
        'type' => 'option',
        'sanitize_callback' => 'sanitize_key',
        'capability' => 'edit_theme_options',
        'transport' => 'postMessage'
            )
    );

    // Do stuff with $wp_customize, the WP_Customize_Manager object.
    $wp_customize->add_control(new WP_Customize_Range_Control($wp_customize, 'header_v_option', array(
        'label' => __('Header Vertical Padding', 'colorway'),
        'section' => 'header_layout_section',
        'settings' => 'header_v_pad',
        'description' => __('Measurement is in pixel.', 'colorway'),
        'priority' => '3',
        'input_attrs' => array(
            'min' => 0,
            'max' => 100,
        ),
            )
            )
    );

    if (inkthemes_get_option('colorway_sticky_header') == true) {
        $content_v_pad_default = '160';
    } else {
        $content_v_pad_default = '0';
    }
    $wp_customize->add_setting('content_v_pad', array(
        'default' => $content_v_pad_default,
        'type' => 'option',
        'sanitize_callback' => 'sanitize_key',
        'capability' => 'edit_theme_options',
        'transport' => 'postMessage'
            )
    );

    // Do stuff with $wp_customize, the WP_Customize_Manager object.
    $wp_customize->add_control(new WP_Customize_Range_Control($wp_customize, 'content_v_option', array(
        'label' => __('Content Vertical Padding', 'colorway'),
        'section' => 'header_layout_section',
        'settings' => 'content_v_pad',
        'description' => __('Measurement is in pixel.', 'colorway'),
        'input_attrs' => array(
            'min' => 0,
            'max' => 400,
        ),
            )
            )
    );


    $wp_customize->add_setting('header_h_pad', array(
        'default' => '85',
        'type' => 'option',
        'sanitize_callback' => 'sanitize_key',
        'capability' => 'edit_theme_options',
        'transport' => 'postMessage'
            )
    );

    // Do stuff with $wp_customize, the WP_Customize_Manager object.
    $wp_customize->add_control(new WP_Customize_Range_Control($wp_customize, 'header_h_option', array(
        'label' => __('Website Container Width', 'colorway'),
        'section' => 'container_layout_section',
        'settings' => 'header_h_pad',
        'description' => __('Measurement is in percent.', 'colorway'),
        'input_attrs' => array(
            'min' => 74,
            'max' => 100,
        ),
            )
            )
    );

// Below code will add Content Width field in customizer
    $wp_customize->add_setting('content_h_pad', array(
        'default' => '100',
        'type' => 'option',
        'sanitize_callback' => 'sanitize_key',
        'capability' => 'edit_theme_options',
        'transport' => 'postMessage'
            )
    );


    $wp_customize->add_control(new WP_Customize_Range_Control($wp_customize, 'content_h_option', array(
        'label' => __('Page Content Width', 'colorway'),
        'section' => 'container_layout_section',
        'settings' => 'content_h_pad',
        'description' => __('Measurement is in percent.', 'colorway'),
        'input_attrs' => array(
            'min' => 60,
            'max' => 100,
        ),
            )
            )
    );
}

add_action('customize_register', 'colorway_customize_register');

/**
 * Render the site title for the selective refresh partial.
 */
function colorway_customize_partial_blogname() {
    bloginfo('name');
}

/**
 * Render the site tagline for the selective refresh partial.
 */
function colorway_customize_partial_blogdescription() {
    bloginfo('description');
}

if (class_exists('WP_Customize_Panel')) {

    class CW_WP_Customize_Panel extends WP_Customize_Panel {

        public $panel;
        public $type = 'cw_panel';

        public function json() {
            $array = wp_array_slice_assoc((array) $this, array('id', 'description', 'priority', 'type', 'panel',));
            $array['title'] = html_entity_decode($this->title, ENT_QUOTES, get_bloginfo('charset'));
            $array['content'] = $this->get_content();
            $array['active'] = $this->active();
            $array['instanceNumber'] = $this->instance_number;
            return $array;
        }

    }

}

// Enqueue our scripts and styles
function cw_customize_controls_scripts() {
    wp_enqueue_script('customizer-controls', get_theme_file_uri('/includes/js/customizer-controls.js'), array(), '1.0', true);
}

add_action('customize_controls_enqueue_scripts', 'cw_customize_controls_scripts');

