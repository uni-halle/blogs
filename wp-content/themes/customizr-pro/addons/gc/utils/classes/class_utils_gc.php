<?php
/**
* Defines filters and actions used in several templates/classes
*
*
* @package      GC
* @subpackage   classes
* @since        3.0
* @author       Nicolas GUILLAUME <nicolas@themesandco.com>, Rocco ALIBERTI <rocco@themesandco.com>
* @copyright    Copyright (c) 2015, Nicolas GUILLAUME - Rocco ALIBERTI
* @link         http://www.themesandco.com/extension/grid-customizer/
* @license      http://www.gnu.org/licenses/old-licenses/gpl-2.0.html
*/

class TC_utils_gc {
    //Access any method or var of the class with classname::$instance -> var or method():
    static $instance;
    public $default_options;
    public $options;//not used in customizer context only
    public $plug_lang;
    public $is_customizing;
    public $addon_opt_prefix;

    function __construct () {
        self::$instance =& $this;

        $this -> plug_lang          = TC_gc::$instance -> plug_lang;
        $this -> is_customizing     = TC_gc::$instance -> tc_is_customizing();
        $this -> addon_opt_prefix   = TC_gc::$instance -> addon_opt_prefix;//=>tc_theme_options since only used as an addon for now

        add_filter ( 'tc_add_setting_control_map', array( $this ,  'gc_update_setting_control_map'), 50 );
    }


    function tc_get_effects_list() {
        return apply_filters( 'gc_effect' ,
            array(
                'effect-1' =>  __( 'Effect #1' , $this -> plug_lang ), //Apollo
                'effect-2' =>  __( 'Effect #2' , $this -> plug_lang ), //Goliath
                'effect-3' =>  __( 'Effect #3' , $this -> plug_lang ), //Selena
                'effect-4' =>  __( 'Effect #4' , $this -> plug_lang ), //Julia
                'effect-5' =>  __( 'Effect #5' , $this -> plug_lang ), //Steve
                'effect-6' =>  __( 'Effect #6' , $this -> plug_lang ), //Jazz
                'effect-7' =>  __( 'Effect #7' , $this -> plug_lang ), //Ming
                'effect-8' =>  __( 'Effect #8' , $this -> plug_lang ), //Lexy
                'effect-9' =>  __( 'Effect #9' , $this -> plug_lang ), //Duke
                'effect-10' =>  __( 'Effect #10' , $this -> plug_lang ), //Lily
                'effect-11' =>  __( 'Effect #11' , $this -> plug_lang ), //Sadie
                'effect-12' =>  __( 'Effect #12' , $this -> plug_lang ), //Layla
                'effect-13' =>  __( 'Effect #13' , $this -> plug_lang ), //Oscar
                'effect-14' =>  __( 'Effect #14' , $this -> plug_lang ), //Marley
                'effect-15' =>  __( 'Effect #15' , $this -> plug_lang ), //Ruby
                'effect-16' =>  __( 'Effect #16' , $this -> plug_lang ), //Milo
                'effect-17' =>  __( 'Effect #17' , $this -> plug_lang ), //Dexter
                'effect-18' =>  __( 'Effect #18' , $this -> plug_lang ), //Sarah
                'effect-19' =>  __( 'Effect #19' , $this -> plug_lang ), //Roxy
                'effect-20' =>  __( 'Effect #20' , $this -> plug_lang ), //Bubba
                'effect-21' =>  __( 'Effect #21' , $this -> plug_lang ), //Romeo
            )
        );
    }



    /**
    * Defines sections, settings and function of customizer and return and array
     */
    function gc_update_setting_control_map( $_map ) {
        $addon_opt_prefix     = $this -> addon_opt_prefix;
        $_new_settings = array(
          "{$addon_opt_prefix}[tc_gc_limit_excerpt_length]"  =>  array(
                            'default'       => 1,
                            'label'         => __( 'Limit the excerpt length when the grid customizer is enabled' , $this -> plug_lang ),
                            'section'       => 'tc_post_list_settings' ,
                            'control'       => 'TC_controls' ,
                            'type'          => 'checkbox',
                            'priority'      => 25,
                            'notice'        =>  __( "Note : bear in mind that some grid customizer effects look better when the excerpt's length is limited to only a few words." , $this -> plug_lang ),
              ),
          "{$addon_opt_prefix}[tc_gc_enabled]"  =>  array(
                            'default'       => 1,
                            'control'       => 'TC_controls' ,
                            'title'         => __( 'Grid Customizer' , $this -> plug_lang ),
                            'label'         => __( 'Enable the Grid Customizer' , $this -> plug_lang ),
                            'section'       => 'tc_post_list_settings' ,
                            'type'          => 'select',
                            'choices'       => array(
                                    1   => __( 'Enable' ,  $this -> plug_lang ),
                                    0    => __( 'Disable' ,  $this -> plug_lang ),
                            ),
                            'priority'      => 48,
                            'notice'        =>  __( "Applies beautiful reveal effects to your posts. <strong>Note :</strong> the Grid Customizer limits the excerpt's length to ensure an optimal rendering." , $this -> plug_lang ),
           ),
           "{$addon_opt_prefix}[tc_gc_effect]" =>  array(
                            'default'       => 'effect-1',
                            'label'         => __( 'Select the hover effect' , $this -> plug_lang ),
                            'section'       => 'tc_post_list_settings' ,
                            'type'          => 'select' ,
                            'choices'       => $this -> tc_get_effects_list(),
                            'priority'      => 50,
                            'transport'     => 'postMessage',
                            'notice'        =>  __( "Depending on the choosen effect, you might want to adjust the title and / or the excerpt length with the options above." , $this -> plug_lang ),
            ),
            "{$addon_opt_prefix}[tc_gc_random]" =>  array(
                            'default'       => 'no-random',
                            'label'         => __( 'Randomize the effects' , $this -> plug_lang ),
                            'section'       => 'tc_post_list_settings' ,
                            'type'          => 'select',
                            'control'       => 'TC_controls',
                            'choices'       => array(
                                'no-random'   => __( 'Random effect disabled' , $this -> plug_lang ),
                                'rand-global' => __( 'Same random effect to all posts' , $this -> plug_lang ),
                                'rand-each'   => __( 'Different random effects to each posts' , $this -> plug_lang )
                            ),
                            'priority'      => 51
            ),
           "{$addon_opt_prefix}[tc_gc_transp_bg]" =>  array(
                            'default'       => 'title-dark-bg',
                            'label'         => __( 'Background' , $this -> plug_lang ),
                            'section'       => 'tc_post_list_settings' ,
                            'control'       => 'TC_controls' ,
                            'type'          => 'select',
                            'control'       => 'TC_controls',
                            'choices'       => array(
                                'title-dark-bg'   => __( 'Dark transparent background on titles only' , $this -> plug_lang ),
                                'title-light-bg'  => __( 'Light transparent background on titles only' , $this -> plug_lang ),
                                'dark-bg'   => __( 'Dark transparent background' , $this -> plug_lang ),
                                'light-bg'  => __( 'Light transparent background' , $this -> plug_lang ),
                                'no-bg'     => __( 'No background' , $this -> plug_lang ),
                            ),
                            'priority'      => 52,
                            'transport'     => 'postMessage'
            ),
            "{$addon_opt_prefix}[tc_gc_title_location]" =>  array(
                            'default'       => 'over',
                            'label'         => __( 'Title location' , $this -> plug_lang ),
                            'section'       => 'tc_post_list_settings' ,
                            'control'       => 'TC_controls' ,
                            'type'          => 'select',
                            'control'       => 'TC_controls',
                            'choices'       => array(
                                'over'   => __( 'Over the post' , $this -> plug_lang ),
                                'below'  => __( 'Below the post' , $this -> plug_lang ),
                            ),
                            'priority'      => 53
            ),
            "{$addon_opt_prefix}[tc_gc_title_color]" =>  array(
                            'default'       => 'white',
                            'label'         => __( 'Title color' , $this -> plug_lang ),
                            'section'       => 'tc_post_list_settings' ,
                            'control'       => 'TC_controls' ,
                            'type'          => 'select',
                            'control'       => 'TC_controls',
                            'choices'       => array(
                                'white'     => __( 'White' , $this -> plug_lang ),
                                'skin'      => __( 'Skin main color' , $this -> plug_lang ),
                                'custom'  => __( 'Custom Color' , $this -> plug_lang )
                            ),
                            'priority'      => 54,
                            'transport'     => 'postMessage'
            ),
            "{$addon_opt_prefix}[tc_gc_title_custom_color]" => array(
                                'default'     => TC_utils::$inst -> tc_get_skin_color(),
                                'control'     => 'WP_Customize_Color_Control',
                                'label'       => __( 'Title custom color' , $this -> plug_lang ),
                                'section'     => 'tc_post_list_settings',
                                'type'        =>  'color' ,
                                'priority'    => 55,
                                'sanitize_callback'    => array( $this, 'tc_sanitize_hex_color' ),
                                'sanitize_js_callback' => 'maybe_hash_hex_color',
                                'transport'   => 'postMessage'
            ),
            "{$addon_opt_prefix}[tc_gc_title_caps]" =>  array(
                            'default'       => 0,
                            'label'         => __( 'Post titles in big caps' , $this -> plug_lang ),
                            'section'       => 'tc_post_list_settings' ,
                            'control'       => 'TC_controls' ,
                            'type'          => 'checkbox',
                            'priority'      => 56,
                            'transport'     => 'postMessage'
            ),
        );
        $_map['add_setting_control'] = array_merge($_map['add_setting_control'] , $_new_settings );
        return $_map;
    }

    /**
    * adds sanitization callback funtion : colors
    * @package Customizr
    * @since Customizr 1.1.4
    */
    function tc_sanitize_hex_color( $color ) {
      if ( $unhashed = sanitize_hex_color_no_hash( $color ) )
        return '#' . $unhashed;

      return $color;
    }
}//end of class
