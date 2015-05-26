<?php
/**
* Customizer actions and filters
*
*
* @package      FPU
* @subpackage   classes
* @since        1.0
* @author       Nicolas GUILLAUME <nicolas@presscustomizr.com>
* @copyright    copyright (c) 2013-2015 Nicolas GUILLAUME
*/

class TC_back_fpu {

    //Access any method or var of the class with classname::$instance -> var or method():
    static $instance;

    function __construct () {

      self::$instance =& $this;

      add_action ( 'customize_register'         , array( $this , 'tc_add_controls_class' ) ,10,1);
      add_action ( 'customize_controls_enqueue_scripts' , array( $this , 'tc_customize_controls_js_css' ), 100);
      add_action ( 'customize_register'         , array( $this , 'tc_customize_register' ) , 20, 1 );
      add_action ( 'customize_preview_init'     , array( $this , 'tc_customize_preview_js' ));
      add_filter ( 'plugin_action_links'        , array( $this , 'tc_plugin_action_links' ), 10 , 2 );
      //refresh the post / CPT / page thumbnail on save. Since v2.0.5
      add_action ( 'save_post'                  , array( $this , 'tc_refresh_thumbnail') );
    }


    /*
    * @return void
    * updates the fpu-thumb-fld post meta with the relevant thumb id and type
    */
    function tc_refresh_thumbnail( $post_id ) {
      // If this is just a revision, don't send the email.
      if ( wp_is_post_revision( $post_id ) )
        return;

      if ( ! class_exists( 'TC_utils_thumb' ) ) {
        require_once ( dirname( dirname( dirname( __FILE__ ) ) ) . '/utils/classes/class_utils_thumbnails.php' );
      }
      new TC_utils_thumb;
      TC_utils_thumb::$instance -> tc_set_thumb_info( $post_id );
    }


    function tc_plugin_action_links( $links, $file ) {
    if ( $file == plugin_basename( dirname( dirname( dirname(__FILE__) ) ). '/' . basename( TC_fpu::$instance -> plug_file ) ) ) {
      $links[] = '<a href="' . admin_url( 'customize.php' ) . '">'.__( 'Settings' ).'</a>';
      $links[] = '<a href="' . admin_url( 'options.php?page=tc-system-info' ) . '">'.__( 'System infos' ).'</a>';
    }
    return $links;
  }


  function tc_add_controls_class( $type) {
    require_once ( dirname( __FILE__ ) . '/class_controls_fpu.php');
  }


  function tc_customize_register( $wp_customize) {
    return $this -> tc_customize_factory ( $wp_customize , $args = $this -> tc_customize_arguments(), $setup = TC_utils_fpu::$instance -> tc_customizer_map() );
  }


  function tc_customize_arguments() {
    $args = array(
        'panels' => array(
                'title' ,
                'description',
                'priority' ,
                'theme_supports',
                'capability'
        ),
        'sections' => array(
              'title' ,
              'priority' ,
              'description',
              'panel',
              'theme_supports'
        ),
        'settings' => array(
              'default'     =>  null,
              'capability'    =>  'manage_options' ,
              'setting_type'    =>  'option' ,
              'sanitize_callback' =>  null,
              'transport'     =>  null
        ),
        'controls' => array(
              'title' ,
              'text' ,
              'label' ,
              'section' ,
              'settings' ,
              'type' ,
              'choices' ,
              'priority' ,
              'sanitize_callback' ,
              'notice' ,
              'buttontext' ,//button specific
              'link' ,//button specific
              'step' ,//number specific
              'min' ,//number specific
              'range-input' ,
              'max',
              'dropdown-posts-pages'
        )
    );
    return apply_filters( 'fpc_customizer_arguments', $args );
  }





  /**
   * Generates customizer
   */
  function tc_customize_factory ( $wp_customize , $args, $setup ) {
    global $wp_version;
    //add panels if current WP version >= 4.0
    if ( isset( $setup['add_panel']) && version_compare( $wp_version, '4.0', '>=' ) ) {
      foreach ( $setup['add_panel'] as $p_key => $p_options ) {
        //declares the clean section option array
        $panel_options = array();
        //checks authorized panel args
        foreach( $args['panels'] as $p_set) {
          $panel_options[$p_set] = isset( $p_options[$p_set]) ?  $p_options[$p_set] : null;
        }
        $wp_customize -> add_panel( $p_key, $panel_options );
      }
    }

    //remove sections
    if ( isset( $setup['remove_section'])) {
      foreach ( $setup['remove_section'] as $section) {
        $wp_customize -> remove_section( $section);
      }
    }

    //add sections
    if ( isset( $setup['add_section'])) {
      foreach ( $setup['add_section'] as  $key => $options) {
        //generate section array
        $option_section = array();

        foreach( $args['sections'] as $sec) {
          $option_section[$sec] = isset( $options[$sec]) ?  $options[$sec] : null;
        }

        //add section
        $wp_customize -> add_section( $key,$option_section);
      }//end foreach
    }//end if


    //get_settings
    if ( isset( $setup['get_setting'])) {
      foreach ( $setup['get_setting'] as $setting) {
        $wp_customize -> get_setting( $setting )->transport = 'postMessage';
      }
    }

    //add settings and controls
    if ( isset( $setup['add_setting_control'])) {

      foreach ( $setup['add_setting_control'] as $key => $options) {
        //isolates the option name for the setting's filter
        $f_option_name = 'setting';
        $f_option = preg_match_all( '/\[(.*?)\]/' , $key , $match );
              if ( isset( $match[1][0] ) ) {$f_option_name = $match[1][0];}

        //declares settings array
        $option_settings = array();
        foreach( $args['settings'] as $set => $set_value) {
          if ( $set == 'setting_type' ) {
            $option_settings['type'] = isset( $options['setting_type']) ?  $options['setting_type'] : $args['settings'][$set];
            $option_settings['type'] = apply_filters( $f_option_name .'_customizer_set', $option_settings['type'] , $set );
          }
          else {
            $option_settings[$set] = isset( $options[$set]) ?  $options[$set] : $args['settings'][$set];
            $option_settings[$set] = apply_filters( $f_option_name .'_customizer_set' , $option_settings[$set] , $set );
          }
        }

        //add setting
        $wp_customize -> add_setting( $key, $option_settings );

        //generate controls array
        $option_controls = array();
        foreach( $args['controls'] as $con) {
          $option_controls[$con] = isset( $options[$con]) ?  $options[$con] : null;
        }

        //add control with a dynamic class instanciation if not default
        if(!isset( $options['control'])) {
            $wp_customize -> add_control( $key,$option_controls );
        }
        else {
            $wp_customize -> add_control( new $options['control']( $wp_customize, $key, $option_controls ));
        }

      }//end for each
    }//end if isset

  }//end of customize generator function





  /**
   *  Binds JS handlers to make Theme Customizer preview reload changes asynchronously.
   */
  function tc_customize_preview_js() {
    $plug_option_prefix     = TC_fpu::$instance -> plug_option_prefix;

    wp_enqueue_script(
      'tc-fpu-preview' ,
      sprintf('%1$s/back/assets/js/fpu-customizer-preview%2$s.js' , TC_FPU_BASE_URL, ( defined('WP_DEBUG') && true === WP_DEBUG ) ? '' : '.min'),
      array( 'customize-preview' ),
      TC_fpu::$instance -> plug_version ,
      true
    );

    //dynamically generates the preview
    $custom_fp_text_param = array();
    foreach ( TC_utils_fpu::$instance -> tc_get_custom_fp_nb() as $fp_name ) {
      if ( in_array( $fp_name , array('one' , 'two' ,'three')) )
        continue;
      $custom_fp_text_param[$fp_name] = "{$plug_option_prefix}[tc_featured_text_{$fp_name}]";
    }

    //localizes
    wp_localize_script(
          'tc-fpu-preview',
          'TCFPCPreviewParams',
          apply_filters('tc_fpc_js_preview_params' ,
            array(
              'OptionPrefix'  => TC_fpu::$instance -> plug_option_prefix,
              'FPpreview'   => $custom_fp_text_param
            )
        )
        );
  }


  /**
   * Add script to controls
   * Dependency : customize-controls located in wp-includes/script-loader.php
   * Hooked on customize_controls_enqueue_scripts located in wp-admin/customize.php
   */
  function tc_customize_controls_js_css() {
    $plug_option_prefix   = TC_fpu::$instance -> plug_option_prefix;

    wp_enqueue_style(
      'tc-fpc-controls-style' ,
      sprintf('%1$s/back/assets/css/fpu-customizer-control%2$s.css' , TC_FPU_BASE_URL, ( defined('WP_DEBUG') && true === WP_DEBUG ) ? '' : '.min'),
      array( 'customize-controls' ),
      TC_fpu::$instance -> plug_version,
      $media = 'all'
    );

    //loads the jquery plugins assets when is (OR) :
    //1) customizr version < 3.2.5
    //2) any theme different than customizr-pro
    if ( ('customizr' == TC_fpu::$theme_name && version_compare( CUSTOMIZR_VER, '3.2.5', '<' ) )
      || ( 'customizr-pro' != TC_fpu::$theme_name  && ('customizr' == TC_fpu::$theme_name && version_compare( CUSTOMIZR_VER, '3.2.5', '>=' ) ) )
      || ( 'customizr-pro' != TC_fpu::$theme_name  &&  'customizr' != TC_fpu::$theme_name )
      ) {
      //ICHECK
      wp_enqueue_style(
        'fpu-icheck-style',
        sprintf('%1$s/back/assets/css/icheck%2$s.css' , TC_FPU_BASE_URL, ( defined('WP_DEBUG') && true === WP_DEBUG ) ? '' : '.min'),
        array( 'customize-controls' ),
        TC_fpu::$instance -> plug_version,
        $media = 'all'
      );
      wp_enqueue_script(
        'icheck-script',
        //dev / debug mode mode?
        sprintf('%1$s/back/assets/js/lib/lib_icheck.js' , TC_FPU_BASE_URL),
        $deps = array('jquery'),
        TC_fpu::$instance -> plug_version,
        $in_footer = true
      );

      //SELECTER
      wp_enqueue_style(
        'fpu-selecter-style',
        sprintf('%1$s/back/assets/css/selecter%2$s.css' , TC_FPU_BASE_URL, ( defined('WP_DEBUG') && true === WP_DEBUG ) ? '' : '.min'),
        array( 'customize-controls' ),
        TC_fpu::$instance -> plug_version,
        $media = 'all'
      );
      wp_enqueue_script(
        'selecter-script',
        //dev / debug mode mode?
        sprintf('%1$s/back/assets/js/lib/lib_selecter.js' , TC_FPU_BASE_URL),
        $deps = array('jquery'),
        TC_fpu::$instance -> plug_version,
        $in_footer = true
      );

      //STEPPER
      wp_enqueue_style(
        'fpu-stepper-style',
        sprintf('%1$s/back/assets/css/stepper%2$s.css' , TC_FPU_BASE_URL, ( defined('WP_DEBUG') && true === WP_DEBUG ) ? '' : '.min'),
        array( 'customize-controls' ),
        TC_fpu::$instance -> plug_version,
        $media = 'all'
      );
      wp_enqueue_script(
        'stepper-script',
        //dev / debug mode mode?
        sprintf('%1$s/back/assets/js/lib/lib_stepper.js' , TC_FPU_BASE_URL),
        $deps = array('jquery'),
        TC_fpu::$instance -> plug_version,
        $in_footer = true
      );
    }//end of jquery plugin assets

    wp_enqueue_script(
      'tc-fpc-controls' ,
      sprintf('%1$s/back/assets/js/fpu-customizer-control%2$s.js' , TC_FPU_BASE_URL, ( defined('WP_DEBUG') && true === WP_DEBUG ) ? '' : '.min'),
      array( 'customize-controls' ),
      TC_fpu::$instance -> plug_version ,
      true
    );

    //gets the featured pages id from init
    $fp_ids       = apply_filters( 'fpc_featured_pages_ids' , TC_fpu::$instance -> fpc_ids);

    //declares the controls dependencies
    $tc_show_fp     = array();
    foreach (TC_utils_fpu::$instance -> default_options as $key => $value) {
      if ( 'tc_show_fp' == $key )
        continue;
      $tc_show_fp[] = "{$plug_option_prefix}[{$key}]";
    }
    $tc_show_excerpt    = array("{$plug_option_prefix}[tc_fp_text_limit]");
    foreach (TC_utils_fpu::$instance -> default_options as $key => $value) {
      if ( false == strpos( $key, 'tc_featured_text_') )
        continue;
      $tc_show_excerpt[]  = "{$plug_option_prefix}[{$key}]";
    }
    $tc_show_button     = array( "{$plug_option_prefix}[tc_fp_button_text]" , "{$plug_option_prefix}[tc_fp_button_color]" , "{$plug_option_prefix}[tc_fp_button_text_color]", "{$plug_option_prefix}[tc_fp_button_color_override]" );
    $tc_show_img      = array( "{$plug_option_prefix}[tc_show_fp_img_override]" );
    $tc_fp_custom_position  = array( "{$plug_option_prefix}[tc_fp_custom_position]" );
    $tc_random_enabled    = array( "{$plug_option_prefix}[tc_show_fp_img_override]" , "{$plug_option_prefix}[tc_fp_text_color_override]", "{$plug_option_prefix}[tc_fp_button_color_override]");
    $tc_fp_by_line      = array( "{$plug_option_prefix}[tc_disable_reordering_768]" );

    /*$page_dropdowns     = array();
    $text_fields      = array();

    //adds filtered page dropdown fields
    foreach ( $fp_ids as $id ) {
      $page_dropdowns[]   = "{$plug_option_prefix}[tc_featured_page_{$id}]";
      $text_fields[]    = "{$plug_option_prefix}[tc_featured_text_{$id}]";
    }*/

    //localizes
    wp_localize_script(
          'tc-fpc-controls',
          'TCFPCControlParams',
          apply_filters('tc_fpc_js_control_params' ,
            array(
              'OptionPrefix'  => $plug_option_prefix,
              'ShowFP'        => $tc_show_fp,
              'ShowExcerpt'   => $tc_show_excerpt,
              'ShowButton'    => $tc_show_button,
              'ShowImg'       => $tc_show_img,
              'CustomHook'    => $tc_fp_custom_position,
              'RandomEnabled' => $tc_random_enabled,
              'FPbyline'      => $tc_fp_by_line
            )
        )
        );

    //adds some nice google fonts to the customizer
        wp_enqueue_style(
          'fpc-google-fonts',
          $this-> tc_customizer_gfonts_url(),
          array(),
          null
        );
  }



  /**
  * Builds Google Fonts url
  */
  function tc_customizer_gfonts_url() {
      //declares the google font vars
      $fonts_url          = '';
      $font_families      = apply_filters( 'tc_fpc_customizer_google_fonts' , array('Raleway') );

      $query_args         = array(
          'family' => implode( '|', $font_families ),
          //'subset' => urlencode( 'latin,latin-ext' ),
      );

      $fonts_url          = add_query_arg( $query_args, "//fonts.googleapis.com/css" );

      return $fonts_url;
    }

}//end of class