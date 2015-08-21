<?php
/**
* Defines filters and actions used in several templates/classes
*
*
* @package      FPU
* @subpackage   classes
* @since        3.0
* @author       Nicolas GUILLAUME <nicolas@presscustomizr.com>
* @copyright    copyright (c) 2013-2015 Nicolas GUILLAUME
* @link         http://presscustomizr.com/extension/featured-pages-unlimited/
* @license      http://www.gnu.org/licenses/old-licenses/gpl-2.0.html
*/

class TC_utils_fpu {
    //Access any method or var of the class with classname::$instance -> var or method():
    static $instance;
    public $default_options;
    public $options;//not used in customizer context only
    public $plug_lang;
    public $is_customizing;

    function __construct () {
        self::$instance =& $this;

        $this -> plug_lang = TC_fpu::$instance -> plug_lang;

        //Filter the default fp rendering
        add_filter( 'fpc_featured_pages_ids'                , array( $this , 'tc_get_custom_fp_nb' ) );
        add_filter( 'fpc_per_line'                          , array( $this , 'tc_get_custom_fp_nb_per_line' ) );

        //get single option
        add_filter  ( '__get_fpc_option'                    , array( $this , 'tc_fpc_get_option' ), 10, 2 );

        //some useful filters
        add_filter  ( '__ID'                                , array( $this , 'tc_get_the_ID' ));
        add_filter  ( '__is_home'                           , array( $this , 'tc_is_home' ) );
        add_filter  ( '__is_home_empty'                     , array( $this , 'tc_is_home_empty' ) );

        //default options
        $this -> is_customizing   = $this -> tc_is_customizing();
        $this -> default_options  = $this -> tc_get_default_options();
    }



    /**
    * Returns a boolean on the customizer's state
    *
    * @package FPU
    * @since FPU 1.4
    */
    function tc_is_customizing() {
        //checks if is customizing : two contexts, admin and front (preview frame)
        global $pagenow;
        $is_customizing = false;
        if ( is_admin() && isset( $pagenow ) && 'customize.php' == $pagenow ) {
            $is_customizing = true;
        } else if ( ! is_admin() && isset($_REQUEST['wp_customize']) ) {
            $is_customizing = true;
        }
        return $is_customizing;
    }



    /**
    * Return the default options array from a customizer map + add slider option
    *
    * @package FPU
    * @since FPU 1.4
    */
    function tc_get_default_options() {
        $prefix             = TC_fpu::$instance -> plug_option_prefix;
        $def_options        = get_option( "{$prefix}_default" );
        //Always update the default option when (OR) :
        // 1) they are not defined
        // 2) customzing => takes into account if user has set a filter or added a new customizer setting
        // 3) theme version not defined
        // 4) versions are different
        if ( ! $def_options || $this -> is_customizing || ! isset($def_options['ver']) || 0 != version_compare( $def_options['ver'] , TC_fpu::$instance -> plug_version ) ) {
            $def_options          = $this -> tc_generate_default_options( $this -> tc_customizer_map( $get_default_option = 'true' ) , $prefix );
            //Adds the version
            $def_options['ver']   =  TC_fpu::$instance -> plug_version;
            update_option( "{$prefix}_default" , $def_options );
        }
        return apply_filters( "{$prefix}_default", $def_options );
    }



    /**
    *
    * @package FPU
    * @since FPU 1.4.3
    */
    function tc_generate_default_options( $map, $option_group = null ) {
        //do we have to look in a specific group of option (plugin?)
        $option_group   = is_null($option_group) ? TC_fpu::$instance -> plug_option_prefix : $option_group;
        foreach ($map['add_setting_control'] as $key => $options) {
            //check it is a customizr option
            if( false !== strpos( $key  , $option_group ) ) {
                //isolate the option name between brackets [ ]
                $option_name = '';
                $option = preg_match_all( '/\[(.*?)\]/' , $key , $match );
                if ( isset( $match[1][0] ) ) {
                      $option_name = $match[1][0];
                }
                //write default option in array
                if(isset($options['default'])) {
                  $defaults[$option_name] = $options['default'];
                }
                else {
                  $defaults[$option_name] = null;
                }
            }//end if
        }//end foreach
      return $defaults;
    }



    /**
    * Returns an option from the options array of the theme.
    *
    * @package FPU
    * @since FPU 1.4
    */
    function tc_fpc_get_option( $option_name , $option_group = null ) {
        //do we have to look in a specific group of option (plugin?)
        $option_group       = is_null($option_group) ? TC_fpu::$instance -> plug_option_prefix : $option_group;
        $saved              = (array) get_option( $option_group );
        $defaults           = $this -> is_customizing ? $this -> tc_get_default_options() : $this -> default_options;
        $__options          = wp_parse_args( $saved, $defaults );
        $returned_option    = isset($__options[$option_name]) ? $__options[$option_name] : false;
      return apply_filters( "tc_fpc_get_opt_{$option_name}" , $returned_option , $option_name , $option_group );
    }




    function tc_get_theme_config( $what = 'hook_list' ) {
        $theme_name         = TC_fpu::$theme_name;
        $prefix             = TC_fpu::$instance -> plug_option_prefix;
        $def_options        = get_option( "{$prefix}_default" );
        //Always update the transient when (OR) :
        // 1) it is not defined
        // 2) the plugin version has changed or is not defined

        //checks if transient exists or has expired
        if ( false == get_transient( 'tc_fpu_config' ) || ! isset($def_options['ver']) || 0 != version_compare( $def_options['ver'] , TC_fpu::$instance -> plug_version ) ) {
            $config_raw          = @file_get_contents( dirname( dirname(__FILE__) ) ."/assets/config/config.json" );
            if ( $config_raw === false ) {
                  $config_raw = wp_remote_fopen( dirname( dirname(__FILE__) ) ."/assets/config/config.json" );
            }
            $config_raw     = json_decode( $config_raw , true );
            set_transient( 'tc_fpu_config' , $config_raw , 60*60*24*10 );//10 days
        } else {
            $config_raw = get_transient( 'tc_fpu_config' );
        }


        $translations =  array(
            'before_header'         => __('Before header'   , $this -> plug_lang ),
            'after_menu'            => __('After main menu'    , $this -> plug_lang ),
            'after_header'          => __('After header'    , $this -> plug_lang ),
            'before_featured'       => __('Before featured posts'   , $this -> plug_lang ),
            'after_featured'        => __('After featured posts'   , $this -> plug_lang ),
            'before_main_wrapper'   => __('Before main wrapper'   , $this -> plug_lang ),
            'after_main_wrapper'    => __('After main wrapper'   , $this -> plug_lang ),
            'before_content'        => __('Before content'  , $this -> plug_lang ),
            'before_footer'         => __('Before footer'   , $this -> plug_lang ),
            'custom_hook'           => __('Custom location' , $this -> plug_lang )
        );

        $config                 = isset($config_raw[$theme_name]) ? $config_raw[$theme_name] : false ;

        //generates the config array for the current theme or fallbacks to the default values
        $default_hook       = 'loop_start';
        $default_bgcolor    = '#fff';
        $default_textcolor  = 'inherit';
        $theme_hooks    = array(
            'loop_start'        =>      isset($translations['before_content']) ? $translations['before_content'] : 'before_content'
        );
        $menu_location  = 'primary';

        if ( $config ) {
            foreach ( $config as $setting => $data ) {
                //sets default bgcolor if exists
                switch ($setting) {
                    case 'menu' :
                        $menu_location = $data;
                    break;

                    case 'bgcolor':
                        $default_bgcolor = $data;
                    break;

                    case 'textcolor':
                        $default_textcolor = $data;
                    break;

                    case 'hooks' :
                        foreach ( $data as $hook => $position ) {
                            if ( false !== strpos($hook, '[default]') ) {
                                $hook           = str_replace('[default]', '', $hook);
                                $default_hook   = $hook;
                            }
                            $theme_hooks[$hook] = isset($translations[$position]) ? $translations[$position] : $position;
                        }//end foreach
                    break;
                }
            }//end foreach
        }//end if isset


        //add a user's defined hook option
        $theme_hooks['custom_hook'] =  isset($translations['custom_hook']) ? $translations['custom_hook'] : 'custom_hook';

        switch ($what) {
            case 'default_bgcolor':
                return apply_filters( 'fpc_default_bgcolor', $default_bgcolor );
            break;

            case 'default_textcolor':
                return apply_filters( 'fpc_default_textcolor', $default_textcolor );
            break;

            case 'default_hook':
                return apply_filters( 'fpc_default_hook', $default_hook );
            break;

            case 'hook_list':
                return apply_filters( 'fpc_theme_hooks', $theme_hooks );
            break;

            case 'menu' :
                return apply_filters( 'fpc_theme_menu', $menu_location );
            break;
        }
    }



     function tc_get_custom_fp_nb() {
        $tc_options                             = get_option( TC_fpu::$instance -> plug_option_prefix );
        $fp_nb                                  = apply_filters( 'fpc_number' , isset( $tc_options['tc_fp_number'] ) ? $tc_options['tc_fp_number'] : 3 );
        $default                                = array( 'one' , 'two' , 'three' );
        $custom_fp = array();
        for ($i = 0; $i < $fp_nb ; $i++) {
            $custom_fp[] = isset($default[$i]) ? $default[$i] : $i + 1;
        }

        return apply_filters( 'fpc_custom_number' , $custom_fp );
    }


    function tc_get_custom_fp_nb_per_line() {
        $saved      = esc_attr( tc__f( '__get_fpc_option' , 'tc_fp_number_per_line' ) );
        $fp_per_line = array(
            'one'       => 1,
            'two'       => 2,
            'three'     => 3,
            'four'      => 4,
        );
        return ( !isset($saved) || is_null($saved) ) ? 3 : $fp_per_line[$saved];
    }



    /**
    * Returns the "real" queried post ID or if !isset, get_the_ID()
    * Checks some contextual booleans
    *
    * @package FPU
    * @since FPU 1.4
    */
    function tc_get_the_ID()  {
        $queried_object   = get_queried_object();
        $tc_id            = get_post() ? get_the_ID() : null;
        $tc_id            = ( isset ($queried_object -> ID) ) ? $queried_object -> ID : $tc_id;
        return ( is_404() || is_search() || is_archive() ) ? null : $tc_id;
    }




    /**
    * Check if we are displaying posts lists or front page
    *
    * @since FPU 1.4.6
    *
    */
    function tc_is_home() {
      //get info whether the front page is a list of last posts or a page
      return ( (is_home() && ( 'posts' == get_option( 'show_on_front' ) || 'nothing' == get_option( 'show_on_front' ) ) ) || is_front_page() ) ? true : false;
    }





    /**
    * Check if we show posts or page content on home page
    *
    * @since FPU 1.4.6
    *
    */
    function tc_is_home_empty() {
      //check if the users has choosen the "no posts or page" option for home page
      return ( (is_home() || is_front_page() ) && 'nothing' == get_option( 'show_on_front' ) ) ? true : false;
    }



    /**
     * Generates the featured pages options
     *
     */
    function tc_generates_featured_pages() {
        $plug_option_prefix     = TC_fpu::$instance -> plug_option_prefix;

        $default = array(
            'dropdown'  =>  array(
                        'one'   => __( 'Home featured page one' , $this -> plug_lang ),
                        'two'   => __( 'Home featured page two' , $this -> plug_lang ),
                        'three' => __( 'Home featured page three' , $this -> plug_lang )
            ),
            'text'      => array(
                        'one'   => __( 'Featured text one (200 char. max)' , $this -> plug_lang ),
                        'two'   => __( 'Featured text two (200 char. max)' , $this -> plug_lang ),
                        'three' => __( 'Featured text three (200 char. max)' , $this -> plug_lang )
            )
        );

        //declares some loop's vars and the settings array
        $priority           = 50;
        $incr               = 0;
        $fp_setting_control = array();

        //gets the featured pages id from init
        $fp_ids             = apply_filters( 'fpc_featured_pages_ids' , TC_fpu::$instance -> fpc_ids);

        //dropdown field generator
        foreach ( $fp_ids as $id ) {
            $priority   = $priority + $incr;
            $fpc_opt    = "{$plug_option_prefix}[tc_featured_page_{$id}]";
            $fp_setting_control[$fpc_opt]       =  array(
                                        'default'       => 0,
                                        'control'       => 'TC_controls_fpu' ,
                                        'label'         => isset($default['dropdown'][$id]) ? $default['dropdown'][$id] :  sprintf( __('Custom featured page %1$s' , $this -> plug_lang ) , $id ),
                                        'section'       => 'tc_fpu' ,
                                        'type'          => 'dropdown-posts-pages' ,
                                        'priority'      => $priority
                                    );
            $incr += 10;
        }

        //text field generator
        $incr               = 10;
        foreach ( $fp_ids as $id ) {
            $priority   = $priority + $incr;
            $fpc_opt    = "{$plug_option_prefix}[tc_featured_text_{$id}]";
            $fp_setting_control[$fpc_opt]   = array(
                                        'sanitize_callback' => array( $this , 'tc_sanitize_textarea' ),
                                        'transport'     => 'postMessage',
                                        'control'       => 'TC_controls_fpu' ,
                                        'label'         => isset($default['text'][$id]) ? $default['text'][$id] : sprintf( __('Featured text %1$s (200 car. max)' , $this -> plug_lang ) , $id ),
                                        'section'       => 'tc_fpu' ,
                                        'type'          => 'textarea' ,
                                        'notice'        => __( 'You need to select a page/post first. Leave this field empty if you want to use the excerpt. You can include HTML tags.' , $this -> plug_lang ),
                                        'priority'      => $priority,
                                    );
            $incr += 10;
        }
        return $fp_setting_control;
    }


    function tc_get_button_color_list() {
        $colors = array(
                'none'    =>  __( ' &#45; Select &#45; ' ,  $this -> plug_lang ),
                'blue'    =>  __( 'Blue' , $this -> plug_lang ),
                'green'   =>  __( 'Green' , $this -> plug_lang ),
                'yellow'  =>  __( 'Yellow' , $this -> plug_lang ),
                'orange'  =>  __( 'Orange' , $this -> plug_lang ),
                'red'     =>  __( 'Red' , $this -> plug_lang ),
                'purple'  =>  __( 'Purple' , $this -> plug_lang ),
                'grey'    =>  __( 'Grey' , $this -> plug_lang ),
                'original' =>  __( 'Light grey' , $this -> plug_lang ),
                'black'   =>  __( 'Black' , $this -> plug_lang )
        );

        //if theme is customizr or customizr-pro add the skin "color" option
        if ( class_exists( 'TC___' ) )
          $colors = array_merge( $colors, array( 'skin' => __('Theme Skin', $this -> plug_lang ) ) );    
        
        return apply_filters( 'fpc_button_colors' , $colors );
    }



    /**
    * Defines sections, settings and function of customizer and return and array
    * Also used to get the default options array, in this case $get_default_option = true and we DISABLE the __get_option (=>infinite loop)
    */
    function tc_customizer_map( $get_default_option = false ) {
        $plug_option_prefix     = TC_fpu::$instance -> plug_option_prefix;
        //customizer option array
        $remove_section = array(
                        /*'remove_section'           =>   array(
                                                'background_image' ,
                                                'static_front_page' ,
                                                'colors'
                        )*/
        );//end of remove_sections array
        $remove_section = apply_filters( 'fpc_remove_section_map', $remove_section );

        $add_section = array(
                        'add_section'           =>   array(
                                        'tc_fpu'                            => array(
                                                                            'title'         =>  __( 'Featured Pages' , $this -> plug_lang ),
                                                                            'priority'      =>  0,
                                                                            'description'   =>  __( 'Customize your featured pages' , $this -> plug_lang )
                                        ),
                        )
        );//end of add_sections array

        //add the panel parameter if theme is customizr-pro
        if ( 'customizr-pro' == TC_fpu::$theme_name ) {
          $add_section['add_section']['tc_fpu']['panel'] = 'tc-content-panel';
        }

        $add_section = apply_filters( 'fpc_add_section_map', $add_section );

        //specifies the transport for some options
        $get_setting        = array(
                        /*'get_setting'         =>   array(
                                        'blogname' ,
                                        'blogdescription'
                        )*/
        );//end of get_setting array
        $get_setting = apply_filters( 'tc_fpc_get_setting_map', $get_setting );

        /*-----------------------------------------------------------------------------------------------------
                                                   FEATURED PAGES SETTINGS
        ------------------------------------------------------------------------------------------------------*/
        $fpc_option_map = array(

                        //Front page widget area
                        "{$plug_option_prefix}[tc_show_fp]" => array(
                                'default'       => 1,
                                'control'       => 'TC_controls_fpu' ,
                                //'title'           => __( 'Featured pages options' , $this -> plug_lang ),
                                'label'         => __( 'Display home featured pages area' , $this -> plug_lang ),
                                'section'       => 'tc_fpu' ,
                                'type'          => 'select' ,
                                'choices'       => array(
                                                1 => __( 'Enable' , $this -> plug_lang ),
                                                0 => __( 'Disable' , $this -> plug_lang ),
                                ),
                                'priority'      => 10,
                        ),

                        //hook select
                        "{$plug_option_prefix}[tc_fp_position]"         => array(
                                'default'       =>  $this -> tc_get_theme_config( 'default_hook'),
                                'control'       => 'TC_controls_fpu' ,
                                'label'         =>  __( 'Select a location' , $this -> plug_lang ),
                                'section'       =>  'tc_fpu' ,
                                'type'          =>  'select' ,
                                'choices'       =>  $get_default_option ? '' : $this -> tc_get_theme_config( 'hook_list'),
                                'priority'      => 11,
                        ),

                        //Custom hook
                        "{$plug_option_prefix}[tc_fp_custom_position]"         => array(
                                'default'       =>  '',
                                'control'       => 'TC_controls_fpu' ,
                                'label'         =>  __( 'Define a custom hook to display your featured pages' , $this -> plug_lang ),
                                'section'       =>  'tc_fpu' ,
                                'type'          =>  'text' ,
                                'notice'        => __( 'Add your custom hook in this field whithout any quotes, space or special characters (it will be stripped out) . Ex : my_custom_hook. Then in your WordPress template, simply add <?php do_action("my_custom_hook") ?> where you want to display your featured pages.' , $this -> plug_lang ),
                                'priority'      => 12,
                        ),

                        //number of featured pages
                        "{$plug_option_prefix}[tc_fp_number]" => array(
                                'default'       => 3,
                                'sanitize_callback' => array( $this , 'tc_sanitize_number' ),
                                'control'       => 'TC_controls_fpu' ,
                                'label'         => __( 'Featured pages number' , $this -> plug_lang ),
                                'section'       => 'tc_fpu' ,
                                'type'          => 'number' ,
                                'step'          => 1,
                                'min'           => 1,
                                'priority'      => 13,
                                'notice'        => __( '1) Set a number of featured pages. 2) Save and refresh the page 3) Edit your featured pages' , $this -> plug_lang ),
                                ),

                        //featured pages by line
                        "{$plug_option_prefix}[tc_fp_number_per_line]" => array(
                                'default'       => 'three',
                                'control'       => 'TC_controls_fpu' ,
                                'label'         => __( 'Featured pages layout' , $this -> plug_lang ),
                                'section'       => 'tc_fpu' ,
                                'type'          => 'select' ,
                                'choices'       => array(
                                            'one'       => __( '1 block by line' , $this -> plug_lang),
                                            'two'       => __( '2 blocks by line' , $this -> plug_lang),
                                            'three'     => __( '3 blocks by line' , $this -> plug_lang  ),
                                            'four'      => __( '4 blocks by line' , $this -> plug_lang  ),
                                ),
                                'priority'      => 14,
                        ),

                        //force responsive behaviour when 4 fp by line
                        "{$plug_option_prefix}[tc_disable_reordering_768]" => array(
                                'default'       => 0,
                                'control'       => 'TC_controls_fpu' ,
                                'label'         => __( 'Disable reordering for devices > 768px (portrait width of tablets) ' , $this -> plug_lang ),
                                'section'       => 'tc_fpu' ,
                                'type'          => 'checkbox' ,
                                'notice'        => __( "Check this box if you want to display 4 FP by lines on tablet's portrait layout" , $this -> plug_lang ),
                                'priority'      => 14,
                        ),


                        //background color
                        "{$plug_option_prefix}[tc_fp_background]" => array(
                                'default'       => $this -> tc_get_theme_config( 'default_bgcolor'),
                                'transport'     => 'postMessage' ,
                                'sanitize_callback'    => array( $this , 'tc_sanitize_hex_color' ),
                                'sanitize_js_callback' => 'maybe_hash_hex_color' ,
                                'control'       => 'TC_Color_Control' ,
                                'label'         => __( 'Background color', $this -> plug_lang),
                                'section'       => 'tc_fpu',
                                'priority'      =>  15,
                        ),

                        //enable/disable random colors
                        "{$plug_option_prefix}[tc_random_colors]" => array(
                                'default'       => 0,
                                'control'       => 'TC_controls_fpu' ,
                                'label'         => __( 'Enable random colors' , $this -> plug_lang ),
                                'section'       => 'tc_fpu' ,
                                'type'          => 'checkbox' ,
                                'notice'        => __( 'This option will apply a beautiful flat design look to your front page with random colors' , $this -> plug_lang ),
                                'priority'      => 16,
                        ),

                        //display featured page images
                        "{$plug_option_prefix}[tc_show_fp_img]" => array(
                                'default'       => 1,
                                'transport'     =>  'postMessage',
                                'control'       => 'TC_controls_fpu' ,
                                'label'         => __( 'Display thumbnails' , $this -> plug_lang ),
                                'section'       => 'tc_fpu' ,
                                'type'          => 'checkbox' ,
                                'notice'        => __( 'The images are set with the "featured image" of each pages or posts. Uncheck the option above to disable the featured images.' , $this -> plug_lang ),
                                'priority'      => 17,
                        ),

                        "{$plug_option_prefix}[tc_show_fp_img_override]" => array(
                                'default'       => 1,
                                //'transport'     =>  'postMessage',
                                'control'       => 'TC_controls_fpu' ,
                                'label'         => __( 'Override random colors' , $this -> plug_lang ),
                                'section'       => 'tc_fpu' ,
                                'type'          => 'checkbox' ,
                                'notice'        => __( 'If this option is checked and you have enable the random color option, your page/post thumbnail will be displayed instead of the random color.' , $this -> plug_lang ),
                                'priority'      => 18,
                        ),
                        "{$plug_option_prefix}[tc_thumb_shape]"  =>  array(
                                'default'       => 'rounded',
                                'control'       => 'TC_controls_fpu' ,
                                'label'         => __( "Thumbnails shape" , "customizr" ),
                                'section'       => 'tc_fpu' ,
                                'type'          =>  'select' ,
                                'choices'       => array(
                                        'rounded'                   => __( 'Rounded, expand on hover' , $this -> plug_lang),
                                        'fp-rounded-expanded'       => __( 'Rounded, no expansion' , $this -> plug_lang),
                                        'fp-squared'                => __( 'Squared, expand on hover' , $this -> plug_lang),
                                        'fp-squared-expanded'       => __( 'Squared, no expansion' , $this -> plug_lang)
                                ),
                                'priority'      => 19,
                                'transport'     =>  'postMessage'
                        ),
                        "{$plug_option_prefix}[tc_center_fp_img]" => array(
                                'default'       => 1,
                                //'transport'     =>  'postMessage',
                                'control'       => 'TC_controls_fpu' ,
                                'label'         => __( 'Dynamic thumbnails centering on any devices' , $this -> plug_lang ),
                                'section'       => 'tc_fpu' ,
                                'type'          => 'checkbox' ,
                                'priority'      => 20,
                                'notice'        => __( 'This option dynamically centers your images on any devices vertically or horizontally (without stretching them) according to their initial dimensions.' , $this -> plug_lang ),
                        ),

                        //enable/disable fp titles
                        "{$plug_option_prefix}[tc_show_fp_title]" => array(
                                'default'       => 1,
                                'transport'     =>  'postMessage',
                                'control'       => 'TC_controls_fpu' ,
                                'label'         => __( 'Display titles' , $this -> plug_lang ),
                                'section'       => 'tc_fpu' ,
                                'type'          => 'checkbox' ,
                                'priority'      => 21,
                        ),

                         //enable/disable fp text
                        "{$plug_option_prefix}[tc_show_fp_text]" => array(
                                'default'       => 1,
                                'transport'     =>  'postMessage',
                                'control'       => 'TC_controls_fpu' ,
                                'label'         => __( 'Display excerpts' , $this -> plug_lang ),
                                'section'       => 'tc_fpu' ,
                                'type'          => 'checkbox' ,
                                'priority'      => 22,
                        ),

                        //text color
                        "{$plug_option_prefix}[tc_fp_text_color]" => array(
                                'default'       => $this -> tc_get_theme_config( 'default_textcolor'),
                                'transport'     => 'postMessage' ,
                                'sanitize_callback'    => array( $this , 'tc_sanitize_hex_color' ),
                                'sanitize_js_callback' => 'maybe_hash_hex_color' ,
                                'control'       => 'TC_Color_Control' ,
                                'label'         => __( 'Title/excerpt color', $this -> plug_lang),
                                'section'       => 'tc_fpu',
                                'priority'      =>  24,
                        ),

                         //text color
                        "{$plug_option_prefix}[tc_fp_text_color_override]" => array(
                                'default'       => 1,
                                //'transport'     =>  'postMessage',
                                'control'       => 'TC_controls_fpu' ,
                                'label'         => __( 'Override random colors' , $this -> plug_lang ),
                                'section'       => 'tc_fpu' ,
                                'type'          => 'checkbox' ,
                                'notice'        => __( 'If enabled, your custom color will override the random color.' , $this -> plug_lang ),
                                'priority'      => 26,
                        ),

                        //text color
                        "{$plug_option_prefix}[tc_fp_text_limit]" => array(
                                'default'       => 1,
                                //'transport'     =>  'postMessage',
                                'control'       => 'TC_controls_fpu' ,
                                'label'         => __( 'Limit excerpt to 200 chars.' , $this -> plug_lang ),
                                'section'       => 'tc_fpu' ,
                                'type'          => 'checkbox' ,
                                'notice'        => __( 'Uncheck this option if you want to disable the default limit of the excerpt.' , $this -> plug_lang ),
                                'priority'      => 27,
                        ),

                        //enable/disable link button
                        "{$plug_option_prefix}[tc_show_fp_button]" => array(
                                'default'       => 1,
                                'transport'     =>  'postMessage',
                                'control'       => 'TC_controls_fpu' ,
                                'label'         => __( 'Display buttons' , $this -> plug_lang ),
                                'section'       => 'tc_fpu' ,
                                'type'          => 'checkbox' ,
                                'priority'      => 28,
                        ),
                         //button text
                        "{$plug_option_prefix}[tc_fp_button_text]" => array(
                                'default'       => __( 'Read more &raquo;' , $this -> plug_lang ),
                                'transport'     =>  'postMessage',
                                'label'         => __( 'Button text' , $this -> plug_lang ),
                                'section'       => 'tc_fpu' ,
                                'type'          => 'text' ,
                                'priority'      => 30,
                        ),

                        //button color
                        "{$plug_option_prefix}[tc_fp_button_color]" => array(
                                'default'       =>  'none',
                                'transport'     =>  'postMessage',
                                'control'       => 'TC_controls_fpu' ,
                                'label'         =>  __( 'Select a button style' , $this -> plug_lang ),
                                'section'       =>  'tc_fpu' ,
                                'type'          =>  'select' ,
                                'priority'      =>  32,
                                'choices'       =>  $get_default_option ? '' : $this-> tc_get_button_color_list()
                        ),


                        //text color
                        "{$plug_option_prefix}[tc_fp_button_color_override]" => array(
                                'default'       => 1,
                                //'transport'     =>  'postMessage',
                                'control'       => 'TC_controls_fpu' ,
                                'label'         => __( 'Override random colors' , $this -> plug_lang ),
                                'section'       => 'tc_fpu' ,
                                'type'          => 'checkbox' ,
                                'notice'        => __( 'If enabled, your custom button style will override the random color.' , $this -> plug_lang ),
                                'priority'      => 34,
                        ),

                        //button text color
                        "{$plug_option_prefix}[tc_fp_button_text_color]" => array(
                                'default'       => '#fff',
                                'transport'     => 'postMessage' ,
                                'sanitize_callback'    => array( $this , 'tc_sanitize_hex_color' ),
                                'sanitize_js_callback' => 'maybe_hash_hex_color' ,
                                'control'       => 'TC_Color_Control' ,
                                'label'         => __( 'Button text color', $this -> plug_lang),
                                'section'       => 'tc_fpu',
                                'priority'      =>  36,
                        ),

        );//end of $featured_pages_option_map

        $fpc_option_map = array_merge( $fpc_option_map , $this -> tc_generates_featured_pages() );
        $fpc_option_map = apply_filters( 'fpc_option_map', $fpc_option_map , $get_default_option );
        $add_setting_control = array(
                        'add_setting_control'   =>   $fpc_option_map
        );
        $add_setting_control = apply_filters( 'fpc_add_setting_control_map', $add_setting_control );
        //merges all customizer arrays
        $customizer_map = array_merge( $remove_section , $add_section , $get_setting , $add_setting_control );
        return apply_filters( 'fpc_customizer_map', $customizer_map );
    }//end of tc_customizer_map function



    /**
     * adds sanitization callback funtion : textarea
     */
    function tc_sanitize_textarea( $value) {
        $value = esc_html( $value);
        return $value;
    }



    /**
     * adds sanitization callback funtion : number
     */
    function tc_sanitize_number( $value) {
        $value = esc_attr( $value); // clean input
        $value = (int) $value; // Force the value into integer type.
        return ( 0 < $value ) ? $value : null;
    }



    /**
     * adds sanitization callback funtion : url
     */
    function tc_sanitize_url( $value) {
        $value = esc_url( $value);
        return $value;
    }


    /**
     * adds sanitization callback funtion : colors
     */
    function tc_sanitize_hex_color( $color ) {
        if ( $unhashed = sanitize_hex_color_no_hash( $color ) )
            return '#' . $unhashed;

        return $color;
    }

}//end of class
