<?php
/**
* Plugin front end functions
* @author Nicolas GUILLAUME
* @since 1.0
*/
class TC_front_font_customizer {

    //Access any method or var of the class with classname::$instance -> var or method():
    static $instance;

    public $is_customizing;

    function __construct () {
        //add_action( 'init'                              , array( $this, 'tc_add_style' ) );
        add_action( 'init'                              , array( $this , 'tc_enqueue_plug_resources' ) , 0 );
        add_action( 'wp_head'                           , array( $this , 'tc_write_gfonts'), 0 );
        add_action( 'wp_head'                           , array( $this , 'tc_write_font_dynstyle'), 0 );
        add_action( 'wp_head'                           , array( $this , 'tc_write_other_dynstyle'), 999 );

        //$this -> is_customizing = isset($_REQUEST['wp_customize']) ? 1 : 0;
    }//end of construct



    function tc_write_gfonts() {
        $_opt_prefix              = TC_wfc::$instance -> plug_option_prefix;
        if ( ! get_option("{$_opt_prefix}_gfonts") ) {
            TC_utils_wfc::$instance -> tc_update_front_end_gfonts();
        }
        $families   = get_option("{$_opt_prefix}_gfonts");
        if ( empty($families) )
            return;

        printf('<link rel="stylesheet" id="tc-front-gfonts" href="%1$s">',
            "//fonts.googleapis.com/css?family={$families}"
        );
    }


    function tc_write_font_dynstyle() {
        ?>
        <style id="dyn-style-fonts" type="text/css">
            <?php do_action( '__dyn_style' , 'fonts' ); ?>
        </style>
        <?php
    }


    function tc_write_other_dynstyle() {
        ?>
        <style id="dyn-style-others" type="text/css">
            <?php do_action( '__dyn_style' , 'other' ); ?>
        </style>
        <?php
    }


    /* PLUGIN FRONT END FUNCTIONS */
    function tc_enqueue_plug_resources() {
         wp_enqueue_style(
          'font-customizer-style' ,
          sprintf('%1$s/front/assets/css/font_customizer%2$s.css' , TC_WFC_BASE_URL, ( defined('WP_DEBUG') && true === WP_DEBUG ) ? '' : '.min'),
          array(),
          TC_wfc::$instance -> plug_version,
          $media = 'all'
        );
       
        //register and enqueue jQuery if necessary
        if ( ! wp_script_is( 'jquery', $list = 'registered') ) {
            wp_register_script('jquery', '//code.jquery.com/jquery-latest.min.js', array(), false, false );
        }
        if ( ! wp_script_is( 'jquery', $list = 'enqueued') ) {
          wp_enqueue_script( 'jquery');
        }

        //WFC front scripts
        wp_enqueue_script(
            'font-customizer-script' ,
            sprintf('%1$s/front/assets/js/font-customizer-front%2$s.js' , TC_WFC_BASE_URL, ( defined('WP_DEBUG') && true === WP_DEBUG ) ? '' : '.min'),
            array('jquery'),
            TC_wfc::$instance -> plug_version,
            true
        );

        //localize font-customizer-script with settings fonts
        wp_localize_script(
          'font-customizer-script',
          'FrontParams',
            array(
                'SavedSelectorsSettings'        => TC_wfc::$instance -> tc_get_saved_option( null , false ),
                'DefaultSettings'               => TC_wfc::$instance -> tc_get_selector_list(),
            )
        );
    }


} //end of class
