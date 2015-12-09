<?php

class inkthemes_Customizer {

    public static function inkthemes_Register($wp_customize) {

        self::inkthemes_Sections($wp_customize);

        self::inkthemes_Controls($wp_customize);
    }

    public static function inkthemes_Sections($wp_customize) {

        /**
         * Add panel for home page feature area
         */
        $wp_customize->add_panel('general_setting_panel', array(
            'title' => __('General Settings', 'colorway'),
            'description' => __('Allows you to setup home page feature area section for ColorWay Theme.', 'colorway'),
            'priority' => '10',
            'capability' => 'edit_theme_options'
        ));

        /**
         * Site Title Section
         */
        $wp_customize->add_section('title_tagline', array(
            'title' => __('Site Title & Tagline', 'colorway'),
            'priority' => '',
            'panel' => 'general_setting_panel'
        ));

        /**
         * Remove control for site icon
         */
        $wp_customize->remove_control('site_icon');

        /**
         * Logo and favicon section
         */
        $wp_customize->add_section('logo_fevi_setting', array(
            'title' => __('Logo & Favicon', 'colorway'),
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
         * Home page slider panel
         */
        $wp_customize->add_panel('home_page_slider_panel', array(
            'title' => __('Slider Settings', 'colorway'),
            'description' => __('Allows you to setup home page slider for ColorWay Theme.', 'colorway'),
            'priority' => '11',
            'capability' => 'edit_theme_options'
        ));

        /**
         * Slider controle section
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
            'panel' => '',
            'priority' => '12',
            'capability' => 'edit_theme_options'
                )
        );

        /**
         * Home Page Feature area panel
         */
        $wp_customize->add_panel('home_feature_area_panel', array(
            'title' => __('Feature Area Settings', 'colorway'),
            'description' => __('Allows you to setup home page feature area section for ColorWay Theme.', 'colorway'),
            'priority' => '13',
            'capability' => 'edit_theme_options'
        ));

        /**
         * Home Page feature area 1
         */
        $wp_customize->add_section('home_feature_area_1', array(
            'title' => __('First Feature Area', 'colorway'),
            'description' => __('Allows you to setup first feature area section for ColorWay Theme.', 'colorway'),
            'panel' => 'home_feature_area_panel',
            'priority' => '',
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
            'priority' => '',
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
            'priority' => '',
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
            'priority' => '',
            'capability' => 'edit_theme_options'
                )
        );

        /**
         * Home Page Middle area panel
         */
        $wp_customize->add_panel('home_middle_area_panel', array(
            'title' => __('Middle Area Settings', 'colorway'),
            'description' => __('Allows you to setup home page middle area section for ColorWay Theme.', 'colorway'),
            'priority' => '14',
            'capability' => 'edit_theme_options'
        ));

        /*
         * home page middle area control section
         */
        $wp_customize->add_section('home_middle_area_control', array(
            'title' => __('Middel Area Control', 'colorway'),
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
        $wp_customize->add_panel('home_testimonial_area_panel', array(
            'title' => __('Testimonial Area Settings', 'colorway'),
            'description' => __('Allows you to setup home page testimonial area for ColorWay Theme.', 'colorway'),
            'priority' => '15',
            'capability' => 'edit_theme_options'
        ));

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
            'title' => __('Footer Settings', 'colorway'),
            'description' => __('Allows you to setup footer social icon for ColorWay Theme.', 'colorway'),
            'priority' => '16',
            'panel' => ''
        ));

        /**
         * Add panel for styling option
         */
        $wp_customize->add_panel('style_setting', array(
            'title' => __('Style Settings', 'colorway'),
            'description' => __('Allows you to setup Theme text and Background color for ColorWay Theme.', 'colorway'),
            'priority' => '17',
            'capability' => 'edit_theme_options'
        ));

        /**
         * Background color Section
         */
        $wp_customize->add_section('colors', array(
            'title' => __('Background color Setting', 'colorway'),
            'description' => __('Allows you to change Background color for ColorWay Theme. This only works when no any image set as background image.', 'colorway'),
            'priority' => '',
            'panel' => 'style_setting'
        ));

        /**
         * Background Image Section
         */
        $wp_customize->add_section('background_image', array(
            'title' => __('Background Image setting', 'colorway'),
            'description' => __('Allows you to change background image for ColorWay Theme. This will overright the background color property', 'colorway'),
            'priority' => '',
            'panel' => 'style_setting'
        ));

        /**
         * Text color Section
         */
        $wp_customize->add_section('text_colors', array(
            'title' => __('Text color setting', 'colorway'),
            'description' => __('Allows you to change text color for ColorWay Theme.', 'colorway'),
            'priority' => '',
            'panel' => 'style_setting'
        ));

        /**
         * Style Section
         */
        $wp_customize->add_section('style_section', array(
            'title' => __('Custom Style Setting', 'colorway'),
            'description' => __('Allows you to change style using custom css for ColorWay Theme.', 'colorway'),
            'panel' => 'style_setting',
            'priority' => '',
            'capability' => 'edit_theme_options'
                )
        );
    }

    public static function inkthemes_Section_Content() {

        $section_content = array(
            'logo_fevi_setting' => array(
                'colorway_logo',
                'colorway_favicon'
            ),
            'tracking_code_setting' => array(
                'colorway_analytics'
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
            'home_middle_area_control' => array(
                'colorway_home_page_blog_post'
            ),
            'home_middle_area_left' => array(
                'inkthemes_widget_head'
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
                'colorway_digg'
            ),
            'style_section' => array(
                'inkthemes_customcss'
            ),
        );
        return $section_content;
    }

    public static function inkthemes_Settings() {

        $theme_settings = array(
            'colorway_logo' => array(
                'id' => 'inkthemes_options[colorway_logo]',
                'label' => __('Custom Logo', 'colorway'),
                'description' => __('Upload a logo for your Website. The recommended size for the logo is 215px width x 55px height.', 'colorway'),
                'type' => 'option',
                'setting_type' => 'image',
                'default' => get_template_directory_uri() . '/images/logo.png'
            ),
            'colorway_favicon' => array(
                'id' => 'inkthemes_options[colorway_favicon]',
                'label' => __('Custom Favicon', 'colorway'),
                'description' => __('Here you can upload a Favicon for your Website. Specified size is 16px x 16px.', 'colorway'),
                'type' => 'option',
                'setting_type' => 'image',
                'default' => ''
            ),
            'colorway_analytics' => array(
                'id' => 'inkthemes_options[colorway_analytics]',
                'label' => __('Tracking Code', 'colorway'),
                'description' => __('Paste your Google Analytics (or other) tracking code here.', 'colorway'),
                'type' => 'option',
                'setting_type' => 'textarea',
                'default' => ''
            ),
            'colorway_home_page_blog_post' => array(
                'id' => 'inkthemes_options[colorway_home_page_blog_post]',
                'label' => __('Home Page Blog post On/Off', 'colorway'),
                'description' => __('Turn on or off the home page blog post as per your requirement.', 'colorway'),
                'type' => 'option',
                'setting_type' => 'radio',
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
                'default' => ''
            ),
            'colorway_slideheading1' => array(
                'id' => 'inkthemes_options[colorway_slideheading1]',
                'label' => __('First Slider Heading', 'colorway'),
                'description' => __('Enter the Heading for Home page First slider', 'colorway'),
                'type' => 'option',
                'setting_type' => 'textarea',
                'default' => ''
            ),
            'colorway_slidelink1' => array(
                'id' => 'inkthemes_options[colorway_slidelink1]',
                'label' => __('First Slide Link', 'colorway'),
                'description' => __('Enter the Link URL for Home Page First Slider', 'colorway'),
                'type' => 'option',
                'setting_type' => 'link',
                'default' => ''
            ),
            'colorway_slidedescription1' => array(
                'id' => 'inkthemes_options[colorway_slidedescription1]',
                'label' => __('First Slide Description', 'colorway'),
                'description' => __('Enter the Description for Home Page First Slides Show', 'colorway'),
                'type' => 'option',
                'setting_type' => 'textarea',
                'default' => ''
            ),
            'colorway_slideimage2' => array(
                'id' => 'inkthemes_options[colorway_slideimage2]',
                'label' => __('Second Slider Image', 'colorway'),
                'description' => __('Choose Image for your Home page Second Slider. Optimal Size: 1171px x 526px', 'colorway'),
                'type' => 'option',
                'setting_type' => 'image',
                'default' => ''
            ),
            'colorway_slideheading2' => array(
                'id' => 'inkthemes_options[colorway_slideheading2]',
                'label' => __('Second Slider Heading', 'colorway'),
                'description' => __('Enter the Heading for Home page Second slider', 'colorway'),
                'type' => 'option',
                'setting_type' => 'textarea',
                'default' => ''
            ),
            'colorway_slidelink2' => array(
                'id' => 'inkthemes_options[colorway_slidelink2]',
                'label' => __('Second Slide Link', 'colorway'),
                'description' => __('Enter the Link URL for Home Page Second Slider', 'colorway'),
                'type' => 'option',
                'setting_type' => 'link',
                'default' => ''
            ),
            'colorway_slidedescription2' => array(
                'id' => 'inkthemes_options[colorway_slidedescription2]',
                'label' => __('Seocnd Slide Description', 'colorway'),
                'description' => __('Enter the Description for Home Page Second Slider', 'colorway'),
                'type' => 'option',
                'setting_type' => 'textarea',
                'default' => ''
            ),
            'inkthemes_mainheading' => array(
                'id' => 'inkthemes_options[inkthemes_mainheading]',
                'label' => __('Home Page Main Heading', 'colorway'),
                'description' => __('Enter your heading text for home page', 'colorway'),
                'type' => 'option',
                'setting_type' => 'textarea',
                'default' => ''
            ),
            'inkthemes_fimg1' => array(
                'id' => 'inkthemes_options[inkthemes_fimg1]',
                'label' => __('First Feature Image', 'colorway'),
                'description' => __('Choose image for your feature column first. Optimal size 198px x 115px', 'colorway'),
                'type' => 'option',
                'setting_type' => 'image',
                'default' => ''
            ),
            'inkthemes_headline1' => array(
                'id' => 'inkthemes_options[inkthemes_headline1]',
                'label' => __('First Feature Heading', 'colorway'),
                'description' => __('Enter text for first feature area heading.', 'colorway'),
                'type' => 'option',
                'setting_type' => 'textarea',
                'default' => ''
            ),
            'inkthemes_feature1' => array(
                'id' => 'inkthemes_options[inkthemes_feature1]',
                'label' => __('First Feature Description', 'colorway'),
                'description' => __('Enter text for first feature area description.', 'colorway'),
                'type' => 'option',
                'setting_type' => 'textarea',
                'default' => ''
            ),
            'inkthemes_link1' => array(
                'id' => 'inkthemes_options[inkthemes_link1]',
                'label' => __('First feature Link', 'colorway'),
                'description' => __('Enter link url for first feature area.', 'colorway'),
                'type' => 'option',
                'setting_type' => 'link',
                'default' => '#'
            ),
            'inkthemes_fimg2' => array(
                'id' => 'inkthemes_options[inkthemes_fimg2]',
                'label' => __('Second Feature Image', 'colorway'),
                'description' => __('Choose image for your feature column second. Optimal size 198px x 115px', 'colorway'),
                'type' => 'option',
                'setting_type' => 'image',
                'default' => ''
            ),
            'inkthemes_headline2' => array(
                'id' => 'inkthemes_options[inkthemes_headline2]',
                'label' => __('Second Feature Heading', 'colorway'),
                'description' => __('Enter text for second feature area heading.', 'colorway'),
                'type' => 'option',
                'setting_type' => 'textarea',
                'default' => ''
            ),
            'inkthemes_feature2' => array(
                'id' => 'inkthemes_options[inkthemes_feature2]',
                'label' => __('Second Feature Description', 'colorway'),
                'description' => __('Enter text for second feature area description.', 'colorway'),
                'type' => 'option',
                'setting_type' => 'textarea',
                'default' => ''
            ),
            'inkthemes_link2' => array(
                'id' => 'inkthemes_options[inkthemes_link2]',
                'label' => __('Second feature Link', 'colorway'),
                'description' => __('Enter link url for second feature area.', 'colorway'),
                'type' => 'option',
                'setting_type' => 'link',
                'default' => '#'
            ),
            'inkthemes_fimg3' => array(
                'id' => 'inkthemes_options[inkthemes_fimg3]',
                'label' => __('Third Feature Image', 'colorway'),
                'description' => __('Choose image for your feature column third. Optimal size 198px x 115px', 'colorway'),
                'type' => 'option',
                'setting_type' => 'image',
                'default' => ''
            ),
            'inkthemes_headline3' => array(
                'id' => 'inkthemes_options[inkthemes_headline3]',
                'label' => __('Third Feature Heading', 'colorway'),
                'description' => __('Enter text for third feature area heading.', 'colorway'),
                'type' => 'option',
                'setting_type' => 'textarea',
                'default' => ''
            ),
            'inkthemes_feature3' => array(
                'id' => 'inkthemes_options[inkthemes_feature3]',
                'label' => __('Third Feature Description', 'colorway'),
                'description' => __('Enter text for third feature area description.', 'colorway'),
                'type' => 'option',
                'setting_type' => 'textarea',
                'default' => ''
            ),
            'inkthemes_link3' => array(
                'id' => 'inkthemes_options[inkthemes_link3]',
                'label' => __('Third feature Link', 'colorway'),
                'description' => __('Enter link url for third feature area.', 'colorway'),
                'type' => 'option',
                'setting_type' => 'link',
                'default' => '#'
            ),
            'inkthemes_fimg4' => array(
                'id' => 'inkthemes_options[inkthemes_fimg4]',
                'label' => __('Fourth Feature Image', 'colorway'),
                'description' => __('Choose image for your feature column fourth. Optimal size 198px x 115px', 'colorway'),
                'type' => 'option',
                'setting_type' => 'image',
                'default' => ''
            ),
            'inkthemes_headline4' => array(
                'id' => 'inkthemes_options[inkthemes_headline4]',
                'label' => __('Fourth Feature Heading', 'colorway'),
                'description' => __('Enter text for fourth feature area heading.', 'colorway'),
                'type' => 'option',
                'setting_type' => 'textarea',
                'default' => ''
            ),
            'inkthemes_feature4' => array(
                'id' => 'inkthemes_options[inkthemes_feature4]',
                'label' => __('Fourth Feature Description', 'colorway'),
                'description' => __('Enter text for fourth feature area description.', 'colorway'),
                'type' => 'option',
                'setting_type' => 'textarea',
                'default' => ''
            ),
            'inkthemes_link4' => array(
                'id' => 'inkthemes_options[inkthemes_link4]',
                'label' => __('Fourth feature Link', 'colorway'),
                'description' => __('Enter link url for fourth feature area.', 'colorway'),
                'type' => 'option',
                'setting_type' => 'link',
                'default' => '#'
            ),
            'inkthemes_widget_head' => array(
                'id' => 'inkthemes_options[inkthemes_widget_head]',
                'label' => __('Home Page Widget Heading', 'colorway'),
                'description' => __('Enter your text for homepage Widget heading.', 'colorway'),
                'type' => 'option',
                'setting_type' => 'textarea',
                'default' => ''
            ),
            'inkthemes_blog_head' => array(
                'id' => 'inkthemes_options[inkthemes_blog_head]',
                'label' => __('Home Page Blog Heading', 'colorway'),
                'description' => __('Enter your text for home Page blog heading section', 'colorway'),
                'type' => 'option',
                'setting_type' => 'textarea',
                'default' => ''
            ),
            'inkthemes_blog_posts' => array(
                'id' => 'inkthemes_options[inkthemes_blog_posts]',
                'label' => __('Home Page Blog Posts', 'colorway'),
                'description' => __('Enter Number of Post you want to show on Home page.', 'colorway'),
                'type' => 'option',
                'setting_type' => 'number',
                'default' => ''
            ),
            'colorway_testimonial_status' => array(
                'id' => 'inkthemes_options[colorway_testimonial_status]',
                'label' => __('Testimonial Section On/Off', 'colorway'),
                'description' => __('Turn on or off the home page testimonial section as per your requirement.', 'colorway'),
                'type' => 'option',
                'setting_type' => 'radio',
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
                'default' => ''
            ),
            'inkthemes_testimonial_main_desc' => array(
                'id' => 'inkthemes_options[inkthemes_testimonial_main_desc]',
                'label' => __('Testimonial Main Description', 'colorway'),
                'description' => __('Here mention the text testimonial Section Description', 'colorway'),
                'type' => 'option',
                'setting_type' => 'textarea',
                'default' => ''
            ),
            'inkthemes_testimonial' => array(
                'id' => 'inkthemes_options[inkthemes_testimonial]',
                'label' => __('First Testimonial Description', 'colorway'),
                'description' => __('Here mention the first testimonial description of client', 'colorway'),
                'type' => 'option',
                'setting_type' => 'textarea',
                'default' => ''
            ),
            'inkthemes_testimonial_img' => array(
                'id' => 'inkthemes_options[inkthemes_testimonial_img]',
                'label' => __('First Testimonial Image', 'colorway'),
                'description' => __('Here mention the first testimonial Image of client', 'colorway'),
                'type' => 'option',
                'setting_type' => 'image',
                'default' => ''
            ),
            'inkthemes_testimonial_name' => array(
                'id' => 'inkthemes_options[inkthemes_testimonial_name]',
                'label' => __('First Testimonial Name', 'colorway'),
                'description' => __('Here mention the name of  testimonial name of client', 'colorway'),
                'type' => 'option',
                'setting_type' => 'textarea',
                'default' => ''
            ),
            'inkthemes_testimonial_2' => array(
                'id' => 'inkthemes_options[inkthemes_testimonial_2]',
                'label' => __('Second Testimonial Description', 'colorway'),
                'description' => __('Here mention the Second testimonial description of client', 'colorway'),
                'type' => 'option',
                'setting_type' => 'textarea',
                'default' => ''
            ),
            'inkthemes_testimonial_img_2' => array(
                'id' => 'inkthemes_options[inkthemes_testimonial_img_2]',
                'label' => __('Second Testimonial Image', 'colorway'),
                'description' => __('Here mention the Second testimonial Image of client', 'colorway'),
                'type' => 'option',
                'setting_type' => 'image',
                'default' => ''
            ),
            'inkthemes_testimonial_name_2' => array(
                'id' => 'inkthemes_options[inkthemes_testimonial_name_2]',
                'label' => __('Second Testimonial Name', 'colorway'),
                'description' => __('Here mention the name of  testimonial name of client', 'colorway'),
                'type' => 'option',
                'setting_type' => 'textarea',
                'default' => ''
            ),
            'inkthemes_testimonial_3' => array(
                'id' => 'inkthemes_options[inkthemes_testimonial_3]',
                'label' => __('Third Testimonial Description', 'colorway'),
                'description' => __('Here mention the Third testimonial description of client', 'colorway'),
                'type' => 'option',
                'setting_type' => 'textarea',
                'default' => ''
            ),
            'inkthemes_testimonial_img_3' => array(
                'id' => 'inkthemes_options[inkthemes_testimonial_img_3]',
                'label' => __('Third Testimonial Image', 'colorway'),
                'description' => __('Here mention the Third testimonial Image of client', 'colorway'),
                'type' => 'option',
                'setting_type' => 'image',
                'default' => ''
            ),
            'inkthemes_testimonial_name_3' => array(
                'id' => 'inkthemes_options[inkthemes_testimonial_name_3]',
                'label' => __('Third Testimonial Name', 'colorway'),
                'description' => __('Here mention the name of  testimonial name of client', 'colorway'),
                'type' => 'option',
                'setting_type' => 'textarea',
                'default' => ''
            ),
            'colorway_facebook' => array(
                'id' => 'inkthemes_options[colorway_facebook]',
                'label' => __('Facebook URL', 'colorway'),
                'description' => __('Enter your Facebook URL if you have one', 'colorway'),
                'type' => 'option',
                'setting_type' => 'link',
                'default' => ''
            ),
            'colorway_twitter' => array(
                'id' => 'inkthemes_options[colorway_twitter]',
                'label' => __('Twitter URL', 'colorway'),
                'description' => __('Enter your Twitter URL if you have one', 'colorway'),
                'type' => 'option',
                'setting_type' => 'link',
                'default' => ''
            ),
            'colorway_rss' => array(
                'id' => 'inkthemes_options[colorway_rss]',
                'label' => __('RSS Feed URL', 'colorway'),
                'description' => __('Enter your RSS Feed URL if you have one', 'colorway'),
                'type' => 'option',
                'setting_type' => 'link',
                'default' => ''
            ),
            'colorway_linkedin' => array(
                'id' => 'inkthemes_options[colorway_linkedin]',
                'label' => __('Linked In URL', 'colorway'),
                'description' => __('Enter your Linkedin URL if you have one', 'colorway'),
                'type' => 'option',
                'setting_type' => 'link',
                'default' => ''
            ),
            'colorway_stumble' => array(
                'id' => 'inkthemes_options[colorway_stumble]',
                'label' => __('Stumble Upon URL', 'colorway'),
                'description' => __('Enter your Stumble Upon URL if you have one', 'colorway'),
                'type' => 'option',
                'setting_type' => 'link',
                'default' => ''
            ),
            'colorway_digg' => array(
                'id' => 'inkthemes_options[colorway_digg]',
                'label' => __('Digg URL', 'colorway'),
                'description' => __('Enter your Stumble Upon URL if you have one', 'colorway'),
                'type' => 'option',
                'setting_type' => 'link',
                'default' => ''
            ),
            'inkthemes_customcss' => array(
                'id' => 'inkthemes_options[inkthemes_customcss]',
                'label' => __('Custom CSS', 'colorway'),
                'description' => __('Quickly add your custom CSS code to your theme by writing the code in this block.', 'colorway'),
                'type' => 'option',
                'setting_type' => 'textarea',
                'default' => ''
            )
        );
        return $theme_settings;
    }

    public static function inkthemes_Controls($wp_customize) {

        $sections = self::inkthemes_Section_Content();
        $settings = self::inkthemes_Settings();

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
                            'settings' => $settings[$section_content_id]['id']
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

                    case 'color':

                        self::add_setting($wp_customize, $settings[$section_content_id]['id'], $settings[$section_content_id]['default'], $settings[$section_content_id]['type'], 'inkthemes_sanitize_color');

                        $wp_customize->add_control(new WP_Customize_Color_Control(
                                $wp_customize, $settings[$section_content_id]['id'], array(
                            'label' => $settings[$section_content_id]['label'],
                            'description' => $settings[$section_content_id]['description'],
                            'section' => $section_id,
                            'settings' => $settings[$section_content_id]['id']
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
                            'choices' => $settings[$section_content_id]['choices']
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
            'type' => $type
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
        $value = sanitize_hex_color($value);
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

function inkthemes_registers() {
    wp_register_script('inkthemes_jquery_ui', '//code.jquery.com/ui/1.11.0/jquery-ui.js', array("jquery"), true);
    wp_register_script('inkthemes_customizer_script', get_template_directory_uri() . '/functions/js/inkthemes_customizer.js', array("jquery", "inkthemes_jquery_ui"), true);
    wp_enqueue_script('inkthemes_customizer_script');
    wp_localize_script('inkthemes_customizer_script', 'ink_advert', array(
        'pro' => __('View PRO version', 'colorway'),
        'url' => esc_url('http://www.inkthemes.com/wp-themes/colorway-wp-theme/')
    ));
}

add_action('customize_controls_enqueue_scripts', 'inkthemes_registers');
