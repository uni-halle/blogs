<?php
/**
* Customizer actions and filters
*
*
* @package      GC
* @subpackage   classes
* @since        1.0
* @author       Nicolas GUILLAUME <nicolas@themesandco.com>, Rocco ALIBERTI <rocco@themesandco.com>
* @copyright    Copyright (c) 2015, Nicolas GUILLAUME, Rocco ALIBERTI
*/

class TC_back_gc {

    //Access any method or var of the class with classname::$instance -> var or method():
    static $instance;
    public $addon_opt_prefix;

    function __construct () {

      self::$instance =& $this;
      $this -> addon_opt_prefix = TC_gc::$instance -> addon_opt_prefix;

      add_action ( 'customize_controls_enqueue_scripts' , array( $this , 'tc_customize_controls_js_css' ), 100);
      add_action ( 'customize_preview_init'     , array( $this , 'tc_customize_preview_js' ));
    }



  function tc_customize_preview_js() {
    wp_enqueue_script(
      'tc-gc-preview' ,
      sprintf('%1$s/back/assets/js/gc-customizer-preview%2$s.js' , TC_GC_BASE_URL, ( defined('WP_DEBUG') && true === WP_DEBUG ) ? '' : '.min'),
      array( 'customize-preview', 'underscore' ),
      TC_gc::$instance -> plug_version ,
      true
    );

    //localizes
    wp_localize_script(
      'tc-gc-preview',
      'TCGCPreviewParams',
      apply_filters('tc_gc_js_preview_params' ,
        array(
          'OptionPrefix'  => TC_gc::$instance -> addon_opt_prefix,
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
    $addon_opt_prefix = TC_gc::$instance -> addon_opt_prefix;

    wp_enqueue_script(
      'tc-gc-controls' ,
      sprintf('%1$s/back/assets/js/gc-customizer-control%2$s.js' , TC_GC_BASE_URL, ( defined('WP_DEBUG') && true === WP_DEBUG ) ? '' : '.min'),
      array( 'customize-controls' ),
      TC_gc::$instance -> plug_version,
      true
    );

    //localizes
    wp_localize_script(
      'tc-gc-controls',
      'TCGCControlParams',
      apply_filters('tc_gc_js_control_params' ,
        array(
          'OptionPrefix'    => TC_gc::$instance -> addon_opt_prefix
        )
      )
    );
  }

}//end of class
