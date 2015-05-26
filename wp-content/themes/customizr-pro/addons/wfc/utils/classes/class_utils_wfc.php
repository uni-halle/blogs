<?php
class TC_utils_wfc {
	static $instance;
	public static $cfonts_list;
	public $plug_lang;
	public $tc_selector_title_map;
	public $default_options;
	public $is_customizing;

	function __construct () {
		self::$instance 	=& $this;
		self::$cfonts_list 	= array(
						  'Arial Black,Arial Black,Gadget,sans-serif',
						  'Century Gothic',
					      'Comic Sans MS,Comic Sans MS,cursive',
					      'Courier New,Courier New,Courier,monospace',
					      'Georgia,Georgia,serif',
					      'Helvetica Neue, Helvetica, Arial, sans-serif',
					      'Impact,Charcoal,sans-serif',
					      'Lucida Console,Monaco,monospace',
					      'Lucida Sans Unicode,Lucida Grande,sans-serif',
					      'Palatino Linotype,Book Antiqua,Palatino,serif',
					      'Tahoma,Geneva,sans-serif',
					      'Times New Roman,Times,serif',
					      'Trebuchet MS,Helvetica,sans-serif',
					      'Verdana,Geneva,sans-serif',
		);//end of array;
		//get single option
        add_filter  ( '__get_wfc_option'                    , array( $this , 'wfc_get_option' ), 10, 2 );
        $this -> tc_selector_title_map 	= $this -> tc_get_selector_title_map();
        $this -> is_customizing   		= TC_wfc::$instance -> tc_is_customizing();
        $this -> default_options  		= $this -> tc_get_default_options();
        $this -> plug_lang 		  		= TC_wfc::$instance -> plug_lang;
	}//end of construct



	/**
    * Return the default options array from a customizer map + add slider option
    *
    * @package FPU
    * @since FPU 1.4
    */
    function tc_get_default_options() {
        $prefix             = TC_wfc::$instance -> plug_option_prefix;
        $def_options        = get_option( "{$prefix}_default" );
        //Always update the default option when (OR) :
        // 1) they are not defined
        // 2) customzing => takes into account if user has set a filter or added a new customizer setting
        // 3) theme version not defined
        // 4) versions are different
        if ( ! $def_options || $this -> is_customizing || ! isset($def_options['ver']) || 0 != version_compare( $def_options['ver'] , TC_wfc::$instance -> plug_version ) ) {
            $def_options          = $this -> tc_generate_default_options( $this -> tc_customizer_map( $get_default_option = 'true' ) , $prefix );
            //Adds the version
            $def_options['ver']   =  TC_wfc::$instance -> plug_version;
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
        $defaults = array();
        //do we have to look in a specific group of option (plugin?)
        $option_group   = is_null($option_group) ? TC_wfc::$instance -> plug_option_prefix : $option_group;
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
	*/
  function wfc_get_option( $option_name , $option_group = null ) {
      //do we have to look in a specific group of option (plugin?)
      $option_group       = is_null($option_group) ? TC_wfc::$instance -> plug_option_prefix : $option_group;
      $saved              = (array) get_option( $option_group );
      $defaults           = $this -> is_customizing ? $this -> tc_get_default_options() : $this -> default_options;
      $__options          = wp_parse_args( $saved, $defaults );
      $returned_option    = isset($__options[$option_name]) ? $__options[$option_name] : false;
    return apply_filters( 'wfc_get_option' , $returned_option , $option_name , $option_group );
  }


    /**
    * Defines sections, settings and function of customizer and return and array
    * Also used to get the default options array, in this case $get_default_option = true and we DISABLE the __get_option (=>infinite loop)
    */
    function tc_customizer_map( $get_default_option = false ) {
    	//customizer option array
        $remove_section 				= array();//end of remove_sections array
        $add_section 					= array(
				                        'add_section'           =>   array(
				                                        'tc_font_customizer_settings'   => array(
				                                                                            'title'         =>  __( 'Font Customizer' , $this -> plug_lang ),
				                                                                            'priority'      =>  0,
				                                                                            'description'   =>  __( 'Play with beautiful fonts!' , $this -> plug_lang )
				                                        ),
				                        )
        );//end of add_sections array
        //specifies the transport for some options
        $get_setting 					= array();

        $tc_font_customizer_settings 	= array();
        $selector_title_map 			= $this -> tc_selector_title_map;
		$priority 						= 10;
		//populate array with the selector list
		foreach ( TC_wfc::$instance -> tc_get_selector_list() as $key => $value) {
			$tc_font_customizer_settings[ TC_wfc::$instance -> setting_prefix. '[' . $key . ']'] = array(
										//'setting_type'	=> 	null,
										'transport' 	=>	'postMessage',
										'control'		=>	'TC_font_controls' ,
										'title'   		=> 	isset($selector_title_map[$key]) ? $selector_title_map[$key] : $key ,
										'section' 		=> 	'tc_font_customizer_settings' ,
										'selector'		=> 	$key,
										'sanitize_callback' => array( $this , 'tc_sanitize_before_db' ), //The name of the function that will be called to sanitize the input data before saving it to the database. Default: blank.
										//'sanitize_js_callback' => array( $this , 'tc_sanitize_after_db'),// The name of the function that will be called to sanitize the coming from the database on its way to the theme customizer. Default: blank.
										//'savedsettings' => 	'test',
										//'type'			=>	'checkbox' ,
										'priority'      => $priority,
			);
			$priority += 10;
		}
		$add_setting_control = array(
                        'add_setting_control'   =>   $tc_font_customizer_settings
        );
        $customizer_map = array_merge( $remove_section , $add_section , $get_setting , $add_setting_control );
        return apply_filters( 'wfc_customizer_map', $customizer_map );
    }



    function tc_get_selector_title_map() {
		$default_map =  apply_filters(
        	'tc_default_selector_title_map',
        	array(
        		'body' 					=> __( 'Default website font' , $this -> plug_lang ),
        		'site_title'			=> __( 'Site title' , $this -> plug_lang ),
        		'site_description' 		=> __( 'Site description' , $this -> plug_lang ),
        		'menu_items' 			=> __( 'Menu items' , $this -> plug_lang ),
        		'slider_title' 			=> __( 'Slider title' , $this -> plug_lang ),
        		'slider_text' 			=> __( 'Slider text' , $this -> plug_lang ),
        		'slider_button' 		=> __( 'Slider button' , $this -> plug_lang ),
        		'fp_title' 				=> __( 'Featured pages title' , $this -> plug_lang ),
        		'fp_text' 				=> __( 'Featured pages text' , $this -> plug_lang ),
        		'fp_btn' 				=> __( 'Featured pages button' , $this -> plug_lang ),
        		'single_post_title' 	=> __( 'Single post/page titles' , $this -> plug_lang ),
        		'post_list_titles' 		=> __( 'Post list titles' , $this -> plug_lang ),
        		'archive_titles' 		=> __( 'Archive titles' , $this -> plug_lang ),
        		'post_content' 			=> __( 'Post content / excerpt' , $this -> plug_lang ),
        		'post_metas' 			=> __( 'Post metas' , $this -> plug_lang ),
        		'post_links' 			=> __( 'Links in post/pages' , $this -> plug_lang ),
        		'post_hone' 			=> __( 'H1 headings' , $this -> plug_lang ),
        		'post_htwo' 			=> __( 'H2 headings' , $this -> plug_lang ),
        		'post_hthree' 			=> __( 'H3 headings' , $this -> plug_lang ),
        		'post_hfour' 			=> __( 'H4 headings' , $this -> plug_lang ),
        		'post_hfive' 			=> __( 'H5 headings' , $this -> plug_lang ),
        		'post_hsix' 			=> __( 'H6 headings' , $this -> plug_lang ),
        		'blockquote' 			=> __( 'Blockquotes' , $this -> plug_lang ),
        		'comment_title' 		=> __( 'Comments title' , $this -> plug_lang ),
        		'comment_author' 		=> __( 'Comments author' , $this -> plug_lang ),
        		'comment_content'		=> __( 'Comments content' , $this -> plug_lang ),
        		'sidebars_widget_title' => __( 'Sidebar widget titles' , $this -> plug_lang ),
        		'sidebars_links' 		=> __( 'Links in sidebars' , $this -> plug_lang ),
        		'footer_widget_title' 	=> __( 'Widget titles' , $this -> plug_lang ),
        		'footer_credits' 		=> __( 'Footer credits' , $this -> plug_lang )
        	)//end of array
		);//end of filter

		$theme_name 				= TC_wfc::$theme_name;
    $_opt_prefix        = TC_wfc::$instance -> plug_option_prefix;
		//returns default if no customs
		if ( ! get_option( "{$_opt_prefix}_customs_{$theme_name}" ) )
			return $default_map;

		$customs 					= get_option( "{$_opt_prefix}_customs_{$theme_name}" );
		$custom_map 				= array();
		foreach ($customs as $id => $data) {
			$custom_map[$id] 		= isset($data['title']) ? $data['title'] : $id;
		}
		return apply_filters( 'all_selectors_title_map' , array_merge( $default_map , $custom_map ) );
	}


    function tc_sanitize_before_db( $values_to_save ) {
		//fired only when necessary
		if ( ! isset($_POST['action'] ) || ( isset($_POST['action']) && 'customize_save' != $_POST['action'] ) )
			return;

		if ( empty($values_to_save) )
			return $values_to_save;

		$values_to_save = (array)json_decode($values_to_save);

		foreach ($values_to_save as $setting_type => $value) {
			switch ( $setting_type ) {
				case 'font-size' :
				case 'line-height' :
				case 'letter-spacing' :
					//number input have to be 2 digits (max) and 2 letters
					$value 		= esc_attr( $value); // clean input
					$unit 		= 'px';
					$unit 		= ( false != strpos($value,'px') ) ? 'px' : 'em';
					$split 		= explode( $unit , $value );
					$values_to_save[$setting_type] 		= (int) $split[0] . $unit; // Force the value into integer type and adds the unit.
				break;

				case 'color' :
				case 'color-hover' :
					$values_to_save[$setting_type] = '#' . sanitize_hex_color_no_hash($value);
				break;

				default :
					//to do very secure => check if entry exist in list
					$values_to_save[$setting_type] = sanitize_text_field($value);
				break;
			}
		}
		return json_encode($values_to_save);
	}


	function get_cfonts() {
		$cfonts = array();
		foreach ( self::$cfonts_list as $font ) {
			//no subsets for cfonts => epty array()
			$cfonts[] = array(
				'name' 		=> $font ,
				'subsets' 	=> array()
			);
		}
		return apply_filters( 'tc_font_customizer_cfonts', $cfonts );
	}


	function get_gfonts( $what = null ) {
		//checks if transient exists or has expired
        if ( false == get_transient( 'tc_gfonts' ) ) {
        	$gfont_raw  		= @file_get_contents( dirname( dirname(__FILE__) ) ."/assets/fonts/webfonts.json" );
			if ( $gfont_raw === false ) {
                  $gfont_raw = wp_remote_fopen( dirname( dirname(__FILE__) ) ."/assets/fonts/webfonts.json" );
            }
        	$gfonts_decoded		= json_decode( $gfont_raw, true );
        	set_transient( 'tc_gfonts' , $gfonts_decoded , 60*60*24*30 );
        } else {
        	$gfonts_decoded = get_transient( 'tc_gfonts' );
        }

		$gfonts = array();
		$subsets = array();

		$subsets['all-subsets'] = sprintf( '%1$s ( %2$s %3$s )',
			__( 'All subsets' , TC_wfc::$instance -> plug_lang ),
			count($gfonts_decoded['items']) + count( $this -> get_cfonts() ),
			__('fonts' , TC_wfc::$instance -> plug_lang )
		);

		foreach ( $gfonts_decoded['items'] as $font ) {
			foreach ( $font['variants'] as $variant ) {
				$name 		= str_replace( ' ', '+', $font['family'] );
				$gfonts[] 	= array(
					'name' 		=> $name . ':' .$variant ,
					'subsets' 	=> $font['subsets']
				);
			}
			//generates subset list : subset => font number
			foreach ( $font['subsets'] as $sub ) {
				$subsets[$sub] = isset($subsets[$sub]) ? $subsets[$sub]+1 : 1;
			}
		}

		//finalizes the subset array
		foreach ( $subsets as $subset => $font_number ) {
			if ( 'all-subsets' == $subset )
				continue;
			$subsets[$subset] = sprintf('%1$s ( %2$s %3$s )',
				$subset,
				$font_number,
				__('fonts' , TC_wfc::$instance -> plug_lang )
			);
		}

		return ('subsets' == $what) ? apply_filters( 'tc_font_customizer_gfonts_subsets ', $subsets ) : apply_filters( 'tc_font_customizer_gfonts', $gfonts )  ;
	}

	function get_font_list() {
		return array_merge( $this -> get_cfonts() , $this -> get_gfonts( 'font') );
	}


	/*
	* Extracts a clean list of Google fonts from saved options and save it in options
	*/
	function tc_update_front_end_gfonts() {
		$saved 				      = TC_wfc::$instance -> tc_get_saved_option( null , false );
		$front_end_gfonts 	= array();
    $_opt_prefix        = TC_wfc::$instance -> plug_option_prefix;

		//extract the gfont list
		foreach (TC_wfc::$instance -> tc_get_selector_list() as $key => $value) {
			$setting		= $saved[$key];
			$family 		= $setting['font-family'];
			//check if is gfont first
			if ( false != strstr( $family, '[gfont]') ) {
				//removes [gfont]
				$family = str_replace( '[gfont]', '' , $setting['font-family']);
				//add the font to the list if does not exist
				if ( isset( $front_end_gfonts[$family] ) ) {
					//adds another subset to the subset's array if don't exist
					if ( !in_array( $setting['subset'] , $front_end_gfonts[$family] ) )
						$front_end_gfonts[$family][] = $setting['subset'];
				} else {
					$front_end_gfonts[$family] = array( $setting['subset'] );
				}

			}
		}
		//creates the clean family list ready for link
		$families = array();
        $subsets  = array();
        foreach ($front_end_gfonts as $single_font => $single_font_subset) {
            //Creates the subsets array
            //if several subsets are defined for the same fonts > adds them and makes a subset array of unique subset values
            foreach ($single_font_subset as $key => $sub) {
                if ( 'all-subsets' == $sub )
                    continue;
                if ( $sub && ! in_array( $sub , $subsets) ) {
                    $subsets[] = $sub;
                }
            }//end foreach
            $families[] = $single_font;
        }//end foreach
        $families = implode( "|", $families );
        if ( ! empty($subsets) ) {
            $families = $families . '&subset=' . implode( ',' , $subsets );
        }
		update_option( "{$_opt_prefix}_gfonts" , $families );
	}

}//end of class