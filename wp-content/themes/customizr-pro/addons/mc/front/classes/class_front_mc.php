<?php
/**
* FRONT END CLASS
* @package  MC
* @author Nicolas GUILLAUME, Rocco ALIBERTI
* @since 1.0
*/
class PC_front_mc {

    //Access any method or var of the class with clasmcame::$instance -> var or method():
    static $instance;

    function __construct () {
        self::$instance     =& $this;

        add_action( 'wp_head'                  , array( $this , 'pc_set_mc_hooks') );
        add_action( 'wp_enqueue_scripts'       , array( $this , 'pc_enqueue_plug_resources'));

    }//end of construct


    /***************************************
    * HOOKS SETTINGS ***********************
    ****************************************/
    /**
    * hook : wp_head
    */
    function pc_set_mc_hooks() {
      add_filter( 'tc_sidenav_body_class'             , array( $this, 'pc_mc_body_class') );
    }

    /**
    * hook : tc_sidenav_body_class filter
    *
    * @package Customizr
    * @since Customizr 3.3+
    */
    function pc_mc_body_class( $_class ){
      return $_class .= '-' . $this -> pc_mc_open_effect();
    }


    /******************************
    HELPERS
    *******************************/

    /******************************************
    * SETTERS / GETTTERS / CALLBACKS
    ******************************************/

    /**
    * @return string
    */
    private function pc_mc_open_effect() {
      return apply_filters( 'pc_mc_open_effect', esc_attr( TC_utils::$inst->tc_opt( 'tc_mc_effect') ) );
    }



    /******************************
    * ASSETS
    *******************************/
    /* Enqueue Plugin resources */
    function pc_enqueue_plug_resources() {
      $_script_suffix = ( defined('WP_DEBUG') && true === WP_DEBUG ) ? '' : '.min'; /* prod */

      wp_enqueue_style(
        'mc-front-style' ,
        sprintf('%1$s/front/assets/css/mc-front%2$s.css' , PC_MC_BASE_URL, $_script_suffix),
        null,
        PC_mc::$instance -> plug_version,
        $media = 'all'
      );
    }
} //end of class
