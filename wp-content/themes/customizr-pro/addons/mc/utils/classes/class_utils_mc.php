<?php
/**
* Defines filters and actions used in several templates/classes
*
*
* @package      MC
* @subpackage   classes
* @since        3.0
* @author       Nicolas GUILLAUME <nicolas@themesandco.com>, Rocco ALIBERTI <rocco@themesandco.com>
* @copyright    Copyright (c) 2015, Nicolas GUILLAUME - Rocco ALIBERTI
* @link         http://www.themesandco.com/extension/grid-customizer/
* @license      http://www.gnu.org/licenses/old-licenses/gpl-2.0.html
*/

class PC_utils_mc {
    //Access any method or var of the class with classname::$instance -> var or method():
    static $instance;
    public $default_options;
    public $options;//not used in customizer context only
    public $plug_lang;
    public $is_customizing;
    public $addon_opt_prefix;

    function __construct () {
        self::$instance =& $this;

        $this -> plug_lang          = PC_mc::$instance -> plug_lang;
        $this -> is_customizing     = PC_mc::$instance -> pc_is_customizing();
        $this -> addon_opt_prefix   = PC_mc::$instance -> addon_opt_prefix;

        add_filter ( 'tc_add_setting_control_map', array( $this ,  'mc_update_setting_control_map'), 50 );

    }


    /**
    * Defines sections, settings and function of customizer and return and array
     */
    function mc_update_setting_control_map( $_map ) {
      $_new_settings = array(
          "tc_mc_effect"  =>  array(
                            'default'       => 'mc_slide_along',
                            'control'       => 'TC_controls' ,
                            'title'         => __( 'Side Menu Reveal Animation' , 'customizr'),
                            'label'         => __( 'Select an animation to reveal the side menu' , $this -> plug_lang ),
                            'section'       => 'nav' ,
                            'type'          => 'select',
                            'choices'       => array(
                                    'mc_reveal'              => __( 'Reveal'            ,  $this -> plug_lang ),
                                    'mc_slide_top'           => __( 'Slide on Top'      ,  $this -> plug_lang ),
                                    'mc_push'                => __( 'Push'              ,  $this -> plug_lang ),
                                    'mc_fall_down'           => __( 'Fall Down'         ,  $this -> plug_lang ),
                                    'mc_slide_along'         => __( 'Slide Along'       ,  $this -> plug_lang ),
                                    'mc_rev_slide_out'       => __( 'Reverse Slide Out' ,  $this -> plug_lang ),
                                    'mc_persp_rotate_in'     => __( 'Rotate In'         ,  $this -> plug_lang ),
                                    'mc_persp_rotate_out'    => __( 'Rotate Out'        ,  $this -> plug_lang ),
                                    'mc_persp_scale_up'      => __( 'Scale Up'          ,  $this -> plug_lang ),
                                    'mc_persp_rotate_delay'  => __( 'Delayed Rotate'    ,  $this -> plug_lang ),
                            ),
                            'priority'      => 53,
                            //'notice'        =>  __( "Applies beautiful reveal effects to your side nav." , $this -> plug_lang ),
                            'transport'     => 'postMessage'
         ),
      );
      return array_merge($_map , $_new_settings );
    }
}//end of class