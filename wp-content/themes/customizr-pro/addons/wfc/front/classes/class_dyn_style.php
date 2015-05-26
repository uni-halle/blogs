<?php
/**
* Dynamic stylesheet generation
* @author Nicolas GUILLAUME
* @since 1.0
*/
class TC_dyn_style {
    
    //Access any method or var of the class with classname::$instance -> var or method():
    static $instance;
    public $raw_settings;

    function __construct () {
        self::$instance =& $this;
        add_action( '__dyn_style'                   , array( $this , 'tc_render_dyn_style' ) , 10 , 1 );

        //V1.19 FIX FOR THE MENU ITEMS
        add_action( '__dyn_style'                   , array( $this , 'tc_additional_styles' ) , 20 );
        $this -> raw_settings = TC_wfc::$instance -> tc_get_raw_option();
    }



    /*
    * @since v1.19
    * Fixes a default Customizr style for the menu items first letter
    */
    function tc_additional_styles() {
        if ( false === strpos(TC_wfc::$theme_name, 'customizr') )        
            return;
        
        $_raw = $this -> raw_settings;
        if ( isset($_raw['menu_items']) && !empty($_raw['menu_items']) )
            //Disable the first letter default Customizr setting
            printf( '%1$s%2$s {%3$s}%4$s',
                "/* Menu items first letter fix */ \n",
                '.navbar .nav>li>a:first-letter',
                "font-size: inherit;",
                "\n\n"
            );

    }



    function tc_render_dyn_style( $what = null ) {
        $_raw                           = $this -> raw_settings;
        $default_settings               = TC_wfc::$instance -> tc_get_selector_list();
        $merged_settings_with_selector  = TC_wfc::$instance -> tc_get_saved_option( 'selector_in_key' , false );
        
        foreach ( $merged_settings_with_selector as $key => $data) {

            $split                  = explode("|", $key);
            $customizer_setting     = $split[0];
            $selector               = $split[1];

            if ( !isset($_raw[$customizer_setting]) || empty($_raw[$customizer_setting]) )
                continue;

            //check what is requested : font or other properties
            if ( 'fonts' == $what && ! isset($_raw[$customizer_setting]['font-family']) )
                continue;

            //selector css block
            printf( '%1$s%2$s {%3$s}%4$s',
                "/* Setting : ".$customizer_setting." */ \n",
                $selector,
                "\n".$this -> tc_get_properties($customizer_setting , $_raw[$customizer_setting] , $data , $special = null , $what ),
                "\n\n"
            );

            //hover color
            if ( 'fonts' != $what && array_key_exists('color-hover', $_raw[$customizer_setting]) ) {
                //if several selectors, then add :hover to each selector
                if ( false !== strpos($selector, ",") ) {
                    $sel_array =  explode(",", $selector);
                    foreach ($sel_array as $key => $sel) {
                        $sel_array[$key] = $sel.':hover';
                    }
                    $selector = implode(",", $sel_array);
                } else {
                    $selector = $selector.':hover';
                }

                printf( '%1$s%2$s {%3$s}%4$s',
                    "/* Setting : ".$customizer_setting." */ \n",
                    $selector,
                    "\n".$this -> tc_get_properties($customizer_setting , $_raw[$customizer_setting] , $data , $special = 'hover', $what ),
                    "\n\n"
                );
            }

            //Handles some Static effect exceptions
            if ( 'fonts' != $what && array_key_exists('static-effect', $_raw[$customizer_setting]) ) {
                if ('inset' == $_raw[$customizer_setting]['static-effect'] ) {
                    printf( '%1$s%2$s {%3$s}%4$s',
                        "/* Hack for Mozilla and IE Inset Effect (not supported) : ".$customizer_setting." */ \n",
                        '.mozilla '.$selector . ', .ie '.$selector,
                        "\n".sprintf( '%1$s : %2$s;%3$s',
                                'background-color',
                                'rgba(0, 0, 0, 0) !important',
                                "\n"
                            ),
                        "\n\n"
                    );
                }
            }
        }
    }

    function tc_get_properties( $customizer_setting , $raw_single_setting , $data , $special = null , $what = null ) {
        $default_settings   = TC_wfc::$instance -> tc_get_selector_list();

        //authorized css properties
        $css_properties = array('font-family','font-weight','font-style','color','font-size','line-height','letter-spacing','text-align','text-decoration','text-transform','color-hover');

        //declares the property rendering var
        $properties_to_render   = array();
        $render_properties      = '';
        
        foreach ( $data as $property => $value ) {
            //checks if it an authorized property
            if ( ! in_array( $property, $css_properties ) )
                continue;

            //checks what is requested : fonts or the rest
            if ( 'fonts' == $what && 'font-family' != $property )
                continue;
            if ( 'other' == $what && 'font-family' == $property )
                continue;

            //checks if there are DB saved settings for this property
            if ( 'notcase' != $special && ! isset($raw_single_setting[$property]) )
                continue;

            //hover case
            if ( 'hover' == $special && 'color-hover' != $property )
                continue;

            switch ($property) {
                case 'font-family':
                    //font: [font-stretch] [font-style] [font-variant] [font-weight] [font-size]/[line-height] [font-family];
                    //special treatment for font-family
                    if ( strstr( $value, '[gfont]') ) {
                        $split                      = explode(":", $value);
                        $value                      = $split[0];
                        //only numbers for font-weight. 400 is default
                        $properties_to_render['font-weight']    = $split[1] ? preg_replace('/\D/', '', $split[1]) : '';
                        $properties_to_render['font-weight']    = empty($properties_to_render['font-weight']) ? 400 : $properties_to_render['font-weight'];
                        $properties_to_render['font-style']     = ( $split[1] && strstr($split[1], 'italic') ) ? 'italic' : 'normal';
                    }
                    $value = str_replace( array( '[gfont]', '[cfont]') , '' , $value );
                    $properties_to_render['font-family'] = in_array( $value, TC_utils_wfc::$cfonts_list) ? $value : "'" . str_replace( '+' , ' ' , $value ) . "'";
                break;
                
                case 'font-size' :
                case 'line-height' :
                case 'letter-spacing' :
                    //if no unit specified, then set px if value > 8, else em.
                    $unit = preg_replace('/[^a-z\s]/i', '', $value);
                    if ('' == $unit) {
                        $unit = ( $value > 9 ) ? 'px' : 'em';
                        $value = $value.$unit;
                    }
                break;

                case 'font-style' :
                case 'font-weight' :
                    $value = ( is_null($value) || !$value || empty($value) ) ? 'normal' : $value;
                break;

                case 'color' : 
                    if ( isset($raw_single_setting['static-effect']) && 'inset' == $raw_single_setting['static-effect'] ) {
                       $properties_to_render['background-color'] = $value.'!important';
                    }
                break;
            }//end switch
            
            //not font family additional treatment
            if ( $property != 'font-family') {
                $properties_to_render[$property] = $value;
            }

            //color hover additional treatment
            if ( 'color-hover' == $property && 'hover' == $special ) {
                $properties_to_render['color'] = $properties_to_render['color-hover'];
            }
            if ( isset($properties_to_render['color-hover']) )
                unset($properties_to_render['color-hover']);

        }//end foreach
        
        foreach ($properties_to_render as $prop => $prop_val) {
            //checks what is requested : fonts or the rest
            if ( 'fonts' == $what && 'font-family' != $prop )
                continue;
            
            $default_prop       = isset($default_settings[$customizer_setting][$prop]) ? $default_settings[$customizer_setting][$prop] : '' ;

            //check if is !important : it can be set in the raw json config file OR manually set by user in the customizer
            $has_important      = ( strpos($default_prop,'important') !== false ) ? true : false;
            $has_important      = ( isset($raw_single_setting['important']) && $raw_single_setting['important'] ) ? true : $has_important;
            $render_properties .=   sprintf( '%1$s : %2$s;%3$s',
                            $prop,
                            ( $has_important || 'font-family' == $prop ) ? $prop_val.'!important' : $prop_val,
                            "\n"
            );
        }//end foreach

        return $render_properties;

    }//end of function

} //end of class
