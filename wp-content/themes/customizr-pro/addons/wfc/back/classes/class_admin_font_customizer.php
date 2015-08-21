<?php

class TC_admin_font_customizer {
	static $instance;

	public $font_weight_list;
	public $font_style_list;
	public $text_align_list;
	public $text_decoration_list;
	public $text_transform_list;
	public $plug_lang;
	public $tc_zone_map;
	public $tc_control_tree;
	public $tc_static_effect_list;
	public $tc_selector_title_map;
	public $tc_control_zones;

	function __construct () {
		self::$instance =& $this;

		//loads the custom control classes and settings
		add_action ( 'customize_register' 						      , array( $this , 'tc_customizer_control_classes') );
		add_action ( 'customize_register'						        , array( $this , 'tc_customize_register' ) , 20, 1 );
		//control scripts
		add_action ( 'customize_controls_enqueue_scripts'		, array( $this , 'tc_plugin_controls_js_css' ));
		//preview scripts
		add_action ( 'customize_preview_init'					      , array( $this , 'tc_plugin_preview_js' ));
		//save last modif date in db
		add_action ( 'customize_save_after'       				  , array( $this , 'tc_db_actions') , 10 );
		//adds dynstyle to WP editor
		add_action ( 'after_setup_theme'                    , array( $this , 'tc_add_editor_style' ), 100 );

		add_filter ( 'plugin_action_links' 						      , array( $this , 'tc_plugin_action_links' ), 10 , 2 );
		//adds the custom zone to customizer
		add_filter ( 'wfc_active_zones' 						        , array( $this , 'tc_add_custom_selectors_zone' ) );
		//ajax save custom selector
		add_action ( 'wp_ajax_register_custom_selector'			, array( $this , 'tc_register_custom_selector' ) );
		//ajax remove custom selector
		add_action ( 'wp_ajax_remove_custom_selector'			  , array( $this , 'tc_remove_custom_selector' ) );

		//ajax save as JSON
		add_action ( 'wp_ajax_save_custom_json'					    , array( $this , 'tc_save_custom_json' ) );


		$this -> tc_zone_map 									= $this -> tc_get_zone_map();
		$this -> tc_static_effect_list 							= $this -> tc_get_static_effect_list();
		$this -> tc_control_zones 								= $this -> tc_get_control_zones();

		//lang
		$this -> plug_lang 										= TC_wfc::$instance -> plug_lang;

        $this -> font_weight_list = array(
				'normal' 	=> 'normal',
				'bold' 		=> 'bold',
				'bolder' 	=> 'bolder',
				'lighter' 	=> 'lighter',
				100 		=> 100,
				200 		=> 200,
				300 		=> 300,
				400 		=> 400,
				500 		=> 500,
				600 		=> 600,
				700 		=> 700,
				800 		=> 800,
				900 		=> 900,
		);

		$this -> font_style_list = array(
				'inherit' 	=> 'inherit',
				'italic' 	=> 'italic',
				'normal'	=> 'normal',
				'oblique'	=> 'oblique',
		);

		$this -> text_align_list = array(
				'center' 	=> 'center',
				'justify' 	=> 'justify',
				'inherit' 	=> 'inherit',
				'left' 		=> 'left',
				'right' 	=> 'right',
		);

		$this -> text_decoration_list =  array(
				'none'			=> 'none',
				'inherit'		=> 'inherit',
				'line-through' => 'line-through',
				'overline'		=> 'overline',
				'underline'		=> 'underline'
		);

		$this -> text_transform_list =  array(
				'none'			=> 'none',
				'inherit'		=> 'inherit',
				'capitalize' 	=> 'capitalize',
				'uppercase'		=> 'uppercase',
				'lowercase'		=> 'lowercase',
		);

		$this -> tc_control_tree 		= $this -> tc_get_control_tree();

	}//end of construct


	/***********************************************
	**************** CUSTOMIZER ********************
	************************************************/
	/**
	* Adds controls to customizer
	*
	*/
	function tc_customizer_control_classes() {
		require_once( dirname(__FILE__) . '/class_customizer_font_controls.php' );
	}



	function tc_customize_register( $wp_customize) {
		return $this -> tc_customize_factory ( $wp_customize , $args = $this -> tc_customize_arguments(), $setup = TC_utils_wfc::$instance -> tc_customizer_map() );
	}

	/**
	 * Generates customizer
	 */
	function tc_customize_factory ( $wp_customize , $args, $setup ) {

		//remove sections
		if ( isset( $setup['remove_section'])) {
			foreach ( $setup['remove_section'] as $section) {
				$wp_customize	-> remove_section( $section);
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
				$wp_customize	-> add_section( $key,$option_section);
			}//end foreach
		}//end if


		//get_settings
		if ( isset( $setup['get_setting'])) {
			foreach ( $setup['get_setting'] as $setting) {
				$wp_customize	-> get_setting( $setting )->transport = 'postMessage';
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
				$wp_customize	-> add_setting( $key, $option_settings );

				//generate controls array
				$option_controls = array();
				foreach( $args['controls'] as $con) {
					$option_controls[$con] = isset( $options[$con]) ?  $options[$con] : null;
				}

				//add control with a dynamic class instanciation if not default
				if(!isset( $options['control'])) {
						$wp_customize	-> add_control( $key,$option_controls );
				}
				else {
						$wp_customize	-> add_control( new $options['control']( $wp_customize, $key, $option_controls ));
				}

			}//end for each
		}//end if isset
	}//end of customize generator function



	function tc_customize_arguments() {
		$args = array(
				'sections' => array(
							'title' ,
							'priority' ,
							'description'
				),
				'settings' => array(
							'default'			=>	null,
							'capability'		=>	'manage_options' ,
							'setting_type'		=>	'option' ,
							'sanitize_callback'	=>	null,
							'transport'			=>	null
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
							'dropdown-posts-pages',
							'savedsettings',
							'selector'
				)
		);
		return apply_filters( 'fpc_customizer_arguments', $args );
	}

	function tc_get_zone_map() {
		return apply_filters(
			'tc_font_customizer_zone_map',
			array(
				'body' 				=> array('full-layout' 	, __( 'Body' , $this -> plug_lang )),
				'header'			=> array('full-layout' 	, __( 'Header' , $this -> plug_lang )),
				'marketing'			=> array('full-layout' 	, __( 'Sliders and Featured pages' , $this -> plug_lang )),
				'post'				=> array('full-layout' 	, __( 'Posts / Pages' , $this -> plug_lang )),
				'comments'			=> array('full-layout' 	, __( 'Comments' , $this -> plug_lang )),
				'sidebars'			=> array('full-layout' 	, __( 'Sidebars' , $this -> plug_lang )),
				'footer'			=> array('full-layout' 	, __( 'Footer' , $this -> plug_lang )),
				'headings' 			=> array('headings'		, __( 'Headings' , $this -> plug_lang )),
				'paragraphs'		=> array('paragraphs' 	, __( 'Paragraphs' , $this -> plug_lang )),
				'links'				=> array('links' 		, __( 'Links' , $this -> plug_lang )),
				'blockquotes'		=> array('blockquotes' 	, __( 'Blockquotes' , $this -> plug_lang ))
			)//end of array
		);//end of filters
	}


	/***********************************************
	************** AJAX FUNCTIONS ******************
	************************************************/
	function tc_save_custom_json() {
		if ( ! is_user_logged_in() )
			wp_die( 0 );
		check_ajax_referer( 'wfc-nonce', 'WFCnonce' );

		if ( ! current_user_can( 'edit_theme_options' ) )
			wp_die( -1 );

		$theme_name 	= TC_wfc::$theme_name;
    $_opt_prefix  = TC_wfc::$instance -> plug_option_prefix;
		$_saved 		  = get_option( "{$_opt_prefix}_saved_options" );
		$_customs 		= get_option( "{$_opt_prefix}_customs_{$theme_name}" );
		$_to_save 		= array();
		foreach ($_customs as $unique_id => $data) {
			$_to_save[$unique_id] = array();
			foreach ($data as $key => $value) {
				switch ($key) {
					case 'zone':
					case 'selector':
					case 'font-size':
					case 'line-height':
					case 'title' :
						$_value_to_save = isset($_saved['settings'][$unique_id][$key]) ? $_saved['settings'][$unique_id][$key] : 0;
					break;

					case 'color-hover' :
						$_value_to_save = isset($_saved['settings'][$unique_id][$key]) ? $_saved['settings'][$unique_id][$key] : 'main';
					break;

					case 'color' :
						$_value_to_save = "#5A5A5A";
					default:
						$_value_to_save = 0;
					break;
				}
				$_to_save[$unique_id][] = $_value_to_save;

				//handles the color-hover case. If not set in the saved options, 0 has to be inserted after color in the JSON.
				if ( 'color' == $key && ! isset($data['color-hover']) )
					array_push($_to_save[$unique_id] , 0 );

			}
		}
		@file_put_contents( dirname( dirname(dirname( __FILE__) ) ) ."/sets/default_settings.json" , json_encode($_to_save));
		wp_send_json_success($json);
	}



	function tc_remove_custom_selector() {
		if ( ! is_user_logged_in() )
			wp_die( 0 );
		check_ajax_referer( 'wfc-nonce', 'WFCnonce' );

		if ( ! current_user_can( 'edit_theme_options' ) )
			wp_die( -1 );

		if ( ! isset($_POST['customsel']) )
			return;

    $theme_name     = TC_wfc::$theme_name;
    $_opt_prefix    = TC_wfc::$instance -> plug_option_prefix;

		//Sanitize
		$sel_unique_id 	= esc_attr( $_POST['customsel']);

		//update db options
		$customs 		= get_option( "{$_opt_prefix}_customs_{$theme_name}" );
		$saved 			= get_option( "{$_opt_prefix}_saved_options" );
		$raw 			  = get_option( 'tc_font_customizer_plug' );

		if ( isset($customs[$sel_unique_id]) ) {
			unset($customs[$sel_unique_id]);
			update_option( "{$_opt_prefix}_customs_{$theme_name}", $customs );
		}
		if ( isset($saved['setting'][$sel_unique_id]) ) {
			unset($saved['setting'][$sel_unique_id]);
			update_option( "{$_opt_prefix}_saved_options" , $saved );
		}
		if ( isset($saved['bools'][$sel_unique_id]) ) {
			unset($saved['bools'][$sel_unique_id]);
			update_option( "{$_opt_prefix}_saved_options" , $saved );
		}
		if ( isset($raw[$sel_unique_id]) ) {
			unset($raw[$sel_unique_id]);
			update_option( 'tc_font_customizer_plug' , $raw );
		}
		wp_send_json_success();
	}




	function tc_register_custom_selector() {
		if ( ! is_user_logged_in() )
			wp_die( 0 );
		check_ajax_referer( 'wfc-nonce', 'WFCnonce' );

		if ( ! current_user_can( 'edit_theme_options' ) )
			wp_die( -1 );

		$theme_name 				= TC_wfc::$theme_name;
    $_opt_prefix        = TC_wfc::$instance -> plug_option_prefix;
		$property_list      = TC_wfc::$instance ->  tc_get_property_list();
		$property_list_keys = array_keys($property_list);
		$is_edit 					  = ( isset($_POST['edit']) && 'false' != esc_attr($_POST['edit']) ) ? true : false;

		//Sanitize
		$sel_name 					= esc_attr( $this -> _cleanInput ( $_POST['customsel']['name'] , 'name' ) );
		$sel_name 					= str_replace('  ', ' ', $sel_name);
		$sel_value 					= esc_attr( $_POST['customsel']['selector'] );
		$sel_zone 					= esc_attr( $_POST['customsel']['zone'] );
		$add_hover_color_control 	= esc_attr( $_POST['customsel']['color-hover'] );

		//Unicity for name
		$sel_unique_id 				= $this -> _sanitize($sel_name);
		$sel_unique_id				= $is_edit ? esc_attr($_POST['edit']) : $this -> _generate_unique_ID( $sel_unique_id , $is_edit );

		$new_selector = array(
			$sel_unique_id => $property_list
		);

		//set some values
		$new_selector[$sel_unique_id]['zone'] 		= ( isset($sel_zone) && !is_null($sel_zone) ) ? $sel_zone : 'custom';
		$new_selector[$sel_unique_id]['selector'] 	= $sel_value;
		if ( ! $add_hover_color_control && isset($new_selector[$sel_unique_id]['color-hover']) )
			unset( $new_selector[$sel_unique_id]['color-hover'] );

		//add the name
		$new_selector[$sel_unique_id]['title'] 		= $sel_name;

		//Create custom option if don't exist
		if ( ! get_option( "{$_opt_prefix}_customs_{$theme_name}" ) )
			update_option( "{$_opt_prefix}_customs_{$theme_name}", $new_selector );
		else {
			if ( $is_edit ) {
				$this -> _update_custom_selector_options( $theme_name , $sel_unique_id, $new_selector );
			}
			else {
				$customs 		= get_option( "{$_opt_prefix}_customs_{$theme_name}" );
				update_option( "{$_opt_prefix}_customs_{$theme_name}", array_merge($customs , $new_selector ) );
			}
		}
		//Set the "just added" transient => used to toggle the right panel when opening the customizer
		set_transient( 'custom_selector_added', array( 'sel_name' => $sel_unique_id, 'zone' => $sel_zone ) , 60 );
		wp_send_json_success();
	}





	//return void();
	function _update_custom_selector_options( $theme_name , $unique_id, $selector) {
    $_opt_prefix     = TC_wfc::$instance -> plug_option_prefix;
		$customs 		     = get_option( "{$_opt_prefix}_customs_{$theme_name}" );
		$saved 			     = get_option( "{$_opt_prefix}_saved_options" );
		$raw 			       = get_option( 'tc_font_customizer_plug' );

		//keep the old id, was already unique!
		if ( isset($customs[$unique_id]) ) {
			$customs[$unique_id] = $selector[$unique_id];
			update_option( "{$_opt_prefix}_customs_{$theme_name}",  $customs );
		}
		if ( isset($saved['setting'][$unique_id]) ) {
			$saved['setting'][$unique_id] = $selector[$unique_id];
			update_option( "{$_opt_prefix}_saved_options" , $saved['setting'] );
		}
		/*if ( isset($saved['bools'][$old_unique_id]) ) {
			$old_bool 						= $saved['bools'][$old_unique_id];
			$saved['bools'][$old_unique_id];
			$saved['bools'][$new_unique_id] = $old_bool;
			update_option( "{$_opt_prefix}_saved_options" , $saved );
		}*/
		/*if ( isset($raw[$old_unique_id]) ) {
			$old_raw 				= $raw[$old_unique_id];
			unset($raw[$old_unique_id]);
			$raw[$new_unique_id] 	= $old_raw;
			update_option( 'tc_font_customizer_plug' , $raw );
		}*/
	}






	/* SANITIZATION */
	function _cleanInput( $input , $type = null ) {
	  	$search = array(
		    '@<script[^>]*?>.*?</script>@si',   // Strip out javascript
		    '@<[\/\!]*?[^<>]*?>@si',            // Strip out HTML tags
	  	);
	    $output = preg_replace($search,'', $input);
	    //replace all non alphanumerical by _
	    $output = ( is_null($type) || 'name' != $type ) ? preg_replace('/[^A-Za-z0-9]/', '_' , $output) : preg_replace('/[^,;a-zA-Z0-9_-]|[,;]$/s', ' ', $output);
	    return $output;
	}


	function _sanitize( $input ) {
	    if ( is_array($input)) {
	        foreach($input as $var => $val) {
	            $output[$var] = $this -> _sanitize($val);
	        }
	    }
	    else {
	        if ( get_magic_quotes_gpc() ) {
	            $input = stripslashes($input);
	        }
	        $input  = $this -> _cleanInput($input);
	        $output = mysql_real_escape_string($input);
	    }
	    return $output;
	}



	/* UNICITY (recursive) */
	function _generate_unique_ID( $_maybe_unique_id , $is_edit = false ) {
		$theme_name 		= TC_wfc::$theme_name;
    $_opt_prefix    = TC_wfc::$instance -> plug_option_prefix;
		$customs 			  = get_option( "{$_opt_prefix}_customs_{$theme_name}" );
		$defaults 			= TC_wfc::$instance -> tc_get_selector_list();

		//check if customs have been set before building an array of keys
		$custom_keys 		= ! $customs ? array() : array_keys($customs);
		$all_ids 			= array_merge( array_keys($defaults) , $custom_keys );
		$_maybe_unique_id 	= strtolower( sanitize_file_name($_maybe_unique_id) );
		//don't go further if is edit case
		if ( ! $is_edit && in_array($_maybe_unique_id, $all_ids) ) {
			//if last char is numeric => increment +1
			if ( is_numeric(substr($_maybe_unique_id, -1)) ) {
				$num_suffix 		= substr($_maybe_unique_id, -1);
				$_maybe_unique_id 	= substr($_maybe_unique_id, 0 , -1) . ($num_suffix + 1);
			} else {
				$_maybe_unique_id 	=  "{$_maybe_unique_id}-2";
				//if still not unique, then call the function recursively
				$_maybe_unique_id 	= in_array($_maybe_unique_id, $all_ids) ? $this -> _generate_unique_ID($_maybe_unique_id) : $_maybe_unique_id;
			}
		}
		return $_maybe_unique_id;
	}



	function tc_add_custom_selectors_zone( $zones ) {
		$zones['custom'] = array( 'full-layout' , __( 'Custom controls' , $this -> plug_lang ) );
		return $zones;
	}


	function tc_get_control_tree() {
		return apply_filters(
			'tc_font_customizer_control_tree',
			array(
				//sections => array(title, array ( setting type => class name)

				'font-family' 	=> array(
						'title' 	=> __('Font Family' , $this -> plug_lang),
						'controls' 	=> array(
								'subset' 			=> array(
									__('Subset' , $this -> plug_lang ),
									array('tc-select-input', TC_utils_wfc::$instance -> get_gfonts('subsets') ),
								),
								'font-family' 		=> array(
									__('Font family' , $this -> plug_lang ),
									array('tc-div-input', array_merge( TC_utils_wfc::$instance -> get_cfonts() , TC_utils_wfc::$instance -> get_gfonts() ) ),
								),
								'static-effect' 	=> array(
									__('Apply an effect' , $this -> plug_lang ),
									array('tc-div-input', $this -> tc_static_effect_list )
								)
						),
				),

				'font-style' 	=> array(
						'title' 	=> __('Font Style' , $this -> plug_lang),
						'controls' 	=> array(
								'color' 			=> array(
									__('Color' , $this -> plug_lang ),
									array('color-picker-hex',false),
								),
								'color-hover' 	=> array(
									__('Color on hover' , $this -> plug_lang ),
									array('color-picker-hex',false),
								),
								'font-size' 		=> array(
									__('Font size' , $this -> plug_lang ),
									array('tc-number-input',false),
								),
								'line-height' 		=> array(
									__('Line height' , $this -> plug_lang ),
									array('tc-number-input',false),
								),
								'font-weight'   	=> array(
									__('Font weight' , $this -> plug_lang ),
									array('tc-select-input', $this -> font_weight_list ),
								),
								'font-style'		=> array(
									__('Font style' , $this -> plug_lang ),
									array('tc-select-input', $this -> font_style_list ),
								),
								'text-align'		=> array(
									__('Text align' , $this -> plug_lang ),
									array('tc-select-input', $this -> text_align_list ),
								),
								'text-decoration'	=> array(
									__('Text decoration' , $this -> plug_lang ),
									array('tc-select-input', $this -> text_decoration_list ),
								),
								'text-transform' 	=> array(
									__('Text Transform' , $this -> plug_lang ),
									array('tc-select-input', $this -> text_transform_list ),
								),
								'letter-spacing'	=> array(
									__('Letter spacing' , $this -> plug_lang ),
									array('tc-number-input',false)
								),
						),
				),

				'other-settings' => array(
						'title' 	=> __('Other settings' , $this -> plug_lang),
						'controls' 	=> array(
								'icon' 		=> array(
									__('Display icon' , $this -> plug_lang ),
									array('tc-check-input',false)
								),
								'important' 		=> array(
									__('Override any other style' , $this -> plug_lang ),
									array('tc-check-input',false)
								),
						),
				)

			)//end of control tree array
		);//end of filter
	}




	function tc_get_static_effect_list() {
		return 	apply_filters( 'tc_static_effect_list',
				array(
					//key => effect name, class, recommended color
					'none'				=> array( __('No effect' , $this -> plug_lang ) , 'no-effect', ''),
					'emboss' 			=> array('Emboss', 'font-effect-emboss', '#ddd'),
					'3d-one' 			=> array( __('3D one' , $this -> plug_lang ) , 'font-effect-3d-one', '#fff'),
					'3d-two' 			=> array( __('3D two' , $this -> plug_lang ) , 'font-effect-3d-two', '#555'),
					'3d-float' 			=> array('3D-float', 'font-effect-3d-float', '#fff'),
					'static' 			=> array('Static', 'font-effect-static', '#343956'),
					'outline' 			=> array('Outline', 'font-effect-outline', '#fff'),
					'shadow-soft' 		=> array( __('Shadow soft' , $this -> plug_lang ) , 'font-effect-shadow-soft', '#5a5a5a'),
					'shadow-simple' 	=> array( __('Shadow simple' , $this -> plug_lang ) , 'font-effect-shadow-simple', '#5a5a5a'),
					'shadow-distant' 	=> array( __('Shadow distant' , $this -> plug_lang ) , 'font-effect-shadow-distant', '#5a5a5a'),
					'shadow-close-one' 	=> array( __('Shadow close one' , $this -> plug_lang ) , 'font-effect-shadow-close-one', '#5a5a5a'),
					'shadow-close-two' 	=> array( __('Shadow close two' , $this -> plug_lang ) , 'font-effect-shadow-close-two', '#5a5a5a'),
					'shadow-multiple' 	=> array( __('Shadow multiple' , $this -> plug_lang ) , 'font-effect-shadow-multiple', '#222'),
					'vintage-retro' 	=> array( __('Vintage retro' , $this -> plug_lang ) , 'font-effect-vintage-retro', '#5a5a5a'),
					'neon-blue' 		=> array( __('Neon blue' , $this -> plug_lang ) , 'font-effect-neon-blue', '#fff'),
					'neon-green' 		=> array( __('Neon green' , $this -> plug_lang ) , 'font-effect-neon-green', '#fff'),
					'neon-orange' 		=> array( __('Neon orange' , $this -> plug_lang ) , 'font-effect-neon-orange', '#fff'),
					'neon-pink' 		=> array( __('Neon pink' , $this -> plug_lang ) , 'font-effect-neon-pink', '#fff'),
					'neon-red' 			=> array( __('Neon red' , $this -> plug_lang ) , 'font-effect-neon-red', '#fff'),
					'neon-grey' 		=> array( __('Neon grey' , $this -> plug_lang ) , 'font-effect-neon-grey', '#fff'),
					'neon-black' 		=> array( __('Neon black' , $this -> plug_lang ) , 'font-effect-neon-black', '#fff'),
					'neon-white' 		=> array( __('Neon white' , $this -> plug_lang ) , 'font-effect-neon-white', '#fff'),
					'fire' 				=> array('Fire', 'font-effect-fire', '#ffe'),
					'fire-animation' 	=> array('Fire Animation', 'font-effect-fire-animation', '#ffe'),
					'anaglyph' 			=> array('Anaglyph', 'font-effect-anaglyph', ''),
					'inset' 			=> array('Inset', 'font-effect-inset', '#555'),
					'brick-sign' 		=> array('Brick Sign', 'font-effect-brick-sign', '#600'),
					'canvas-print' 		=> array('Canvas Print', 'font-effect-canvas-print', '#7A5C3E'),
					'crackle' 			=> array('Crackle', 'font-effect-crackle', '#963'),
					'decaying' 			=> array('Decaying', 'font-effect-decaying', '#958e75'),
					'destruction' 		=> array('Destruction', 'font-effect-destruction', '#e10707'),
					'distressed' 		=> array('Distressed', 'font-effect-distressed', '#306'),
					'distressed-wood' 	=> array('Distressed Wood', 'font-effect-distressed-wood', '#4d2e0d'),
					'fragile' 			=> array('Fragile', 'font-effect-fragile', '#663'),
					'grass' 			=> array('Grass', 'font-effect-grass', '#390'),
					'ice' 				=> array('Ice', 'font-effect-ice', '#0cf'),
					'mitosis' 			=> array('Mitosis', 'font-effect-mitosis', '#600'),
					'putting-green' 	=> array('Putting green', 'font-effect-putting-green', '#390'),
					'scuffed-steel' 	=> array('Scuffed Steel', 'font-effect-scuffed-steel', '#acacac'),
					'splintered' 		=> array('Splintered', 'font-effect-splintered', '#5a3723'),
					'stonewash' 		=> array('Stonewash', 'font-effect-stonewash', '#343956'),
					'vintage' 			=> array('Vintage', 'font-effect-vintage', '#db8'),
					'wallpaper' 		=> array('Wallpaper', 'font-effect-wallpaper', '#9c7')
				)
		);//end of filter
	}



	function tc_get_control_zones() {
        $default_options    = TC_wfc::$instance -> tc_get_selector_list();
        $zone_map 			= $this -> tc_zone_map;
        $zones              = $zone_map;
        /*foreach( $default_options as $selector => $settings ) {
              $z_key          = $settings['zone'];
              $zones[$z_key]  = isset($zone_map[$z_key]) ? $zone_map[$z_key] : $z_key;
        }*/
        return apply_filters( 'wfc_active_zones' , $zones );
    }




	/**
	 *  Binds JS handlers to make Theme Customizer preview reload changes asynchronously.
	 * @package Customizr
	 * @since Customizr 1.0
	 */
	function tc_plugin_preview_js() {
		wp_enqueue_script(
			'font-customizer-preview' ,
      sprintf('%1$s/back/assets/js/font-customizer-preview%2$s.js' , TC_WFC_BASE_URL, ( defined('WP_DEBUG') && true === WP_DEBUG ) ? '' : '.min'),
			array( 'customize-preview' ),
			TC_wfc::$instance -> plug_version,
			true );

		wp_localize_script(
	        'font-customizer-preview',
	        'TCFontPreview',
            array(
            	'SettingPrefix'					=> TC_wfc::$instance -> setting_prefix,
                'DefaultSettings'				=> TC_wfc::$instance -> tc_get_selector_list(),
                'DBSettings' 					=> TC_wfc::$instance -> tc_get_saved_option( null , false ),
                'SkinColors'					=> TC_wfc::$instance -> tc_skin_colors,
                'CurrentSkin'					=> tc__f( '__get_wfc_option' , 'tc_skin' ),
                'POST' 							=> $_POST
            )
      	);
	}



	/**
	* Adds script to controls
	*/
	function tc_plugin_controls_js_css() {

		wp_enqueue_style(
			'font-customizer-fontselect-style',
      sprintf('%1$s/back/assets/css/fontselect%2$s.css' , TC_WFC_BASE_URL, ( defined('WP_DEBUG') && true === WP_DEBUG ) ? '' : '.min'),
			array( 'customize-controls' ),
      ( defined('WP_DEBUG') && true === WP_DEBUG ) ? time() : TC_wfc::$instance -> plug_version,
			$media = 'all'
		);

    //loads the jquery plugins assets when is (OR) :
    //1) customizr version < 3.2.5
    //2) any theme different than customizr-pro
    if ( ('customizr' == TC_wfc::$theme_name && version_compare( CUSTOMIZR_VER, '3.2.5', '<' ) )
      || ( 'customizr-pro' != TC_wfc::$theme_name  && ('customizr' == TC_wfc::$theme_name && version_compare( CUSTOMIZR_VER, '3.2.5', '>=' ) ) )
      || ( 'customizr-pro' != TC_wfc::$theme_name  &&  'customizr' != TC_wfc::$theme_name )
      ) {
      //ICHECK
      wp_enqueue_style(
        'wfc-icheck-style',
        sprintf('%1$s/back/assets/css/icheck%2$s.css' , TC_WFC_BASE_URL, ( defined('WP_DEBUG') && true === WP_DEBUG ) ? '' : '.min'),
        array( 'customize-controls' ),
        TC_wfc::$instance -> plug_version,
        $media = 'all'
      );
      wp_enqueue_script(
        'icheck-script',
        //dev / debug mode mode?
        sprintf('%1$s/back/assets/js/lib/lib_icheck.js' , TC_WFC_BASE_URL),
        $deps = array('jquery'),
        TC_wfc::$instance -> plug_version,
        $in_footer = true
      );

      //SELECTER
      wp_enqueue_style(
        'wfc-selecter-style',
        sprintf('%1$s/back/assets/css/selecter%2$s.css' , TC_WFC_BASE_URL, ( defined('WP_DEBUG') && true === WP_DEBUG ) ? '' : '.min'),
        array( 'customize-controls' ),
        TC_wfc::$instance -> plug_version,
        $media = 'all'
      );
      wp_enqueue_script(
        'selecter-script',
        //dev / debug mode mode?
        sprintf('%1$s/back/assets/js/lib/lib_selecter.js' , TC_WFC_BASE_URL),
        $deps = array('jquery'),
        TC_wfc::$instance -> plug_version,
        $in_footer = true
      );

      //STEPPER
      wp_enqueue_style(
        'wfc-stepper-style',
        sprintf('%1$s/back/assets/css/stepper%2$s.css' , TC_WFC_BASE_URL, ( defined('WP_DEBUG') && true === WP_DEBUG ) ? '' : '.min'),
        array( 'customize-controls' ),
        TC_wfc::$instance -> plug_version,
        $media = 'all'
      );
      wp_enqueue_script(
        'stepper-script',
        //dev / debug mode mode?
        sprintf('%1$s/back/assets/js/lib/lib_stepper.js' , TC_WFC_BASE_URL),
        $deps = array('jquery'),
        TC_wfc::$instance -> plug_version,
        $in_footer = true
      );
    }//end of jquery plugin assets

		wp_register_script(
			'require',
      sprintf('%1$s/back/assets/js/require.js' , TC_WFC_BASE_URL),
			array('jquery'),
			null,
			$in_footer = true
		);

		$_app_on_server				      = dirname(dirname(__FILE__)) . '/assets/js/require/app.js';
		$_app_path 					        = file_exists($_app_on_server) ? sprintf('%1$s/back/assets/js/%2$s' , TC_WFC_BASE_URL, 'require/app.js') : false;
		//displays unminified script in plugins dev mode only AND if unmified file exists!
    //checked with false === strpos( dirname( dirname( dirname (__FILE__) ) ) , 'addons/wfc' )
    $_use_unminified = defined('TC_DEV')
      && true === TC_DEV
      && false === strpos( dirname( dirname( dirname (__FILE__) ) ) , 'addons/wfc' )
      && file_exists( sprintf( '%s/assets/js/font-customizer-control.js' , dirname( dirname( __FILE__ ) ) ) );

    $_prod_script_path          = sprintf(
      '%1$s/back/assets/js/%2$s' ,
      TC_WFC_BASE_URL,
      $_use_unminified ? 'font-customizer-control.js' : 'font-customizer-control.min.js'
    );
		wp_register_script(
			'font-customizer-control',
			//dev / debug mode mode?
			( apply_filters( 'wfc_require_app_js' , false ) && false != $_app_path ) ? $_app_path : $_prod_script_path,
			$deps = array('customize-controls' , 'require', 'underscore'),
			( defined('WP_DEBUG') && true === WP_DEBUG ) ? time() : TC_wfc::$instance -> plug_version,
			$in_footer = true
		);
		wp_enqueue_script('font-customizer-control');

		$theme_name = TC_wfc::$theme_name;
		wp_localize_script(
          'font-customizer-control',
          'TCFontAdmin', apply_filters('tc_font_customizer_control_params',
            array(
                'SettingPrefix'					=> TC_wfc::$instance -> setting_prefix,
                'DefaultSettings'				=> TC_wfc::$instance -> tc_get_selector_list(),
                'CustomSets'					=> TC_wfc::$instance -> tc_get_custom_selector_list(),
                'DBSettings' 					=> TC_wfc::$instance -> tc_get_saved_option( null , false ),
                'HasSavedSets'					=> TC_wfc::$instance -> tc_get_saved_option($selector = null , $bool = true),
                'SkinColors'					=> TC_wfc::$instance -> tc_skin_colors,
                'Tree'							=> $this -> tc_control_tree,
                'CFonts'						=> TC_utils_wfc::$instance -> get_cfonts_names(),
                'Translations'		 			=> array(
                	'reset_all_button' 	=> __('Reset all' , TC_wfc::$instance -> plug_lang ),
                	'reset_all_confirm'	=> __('All settings reset to default' , TC_wfc::$instance -> plug_lang ),
                	'reset_all_warning'	=> __('Are you sure you want to reset all your font settings to default?' , TC_wfc::$instance -> plug_lang ),
                	'reset_all_yes'		=> __('Yes' , TC_wfc::$instance -> plug_lang ),
                	'reset_all_no'		=> __('No' , TC_wfc::$instance -> plug_lang ),
                ),
                'Zones' 						=> $this -> tc_control_zones,//all zones including those with no settings
                'AjaxUrl'          				=> admin_url( 'admin-ajax.php' ),
                'WFCNonce' 						=> wp_create_nonce( 'wfc-nonce' ),
                'HasCustomToAdd' 				=> get_transient( 'custom_selector_added' )
            )
         )//end filter
      	);
		//delete the transient after setting the js var HasCustomToAdd
		delete_transient( 'custom_selector_added' );

		//adds some nice google fonts to the customizer
        wp_enqueue_style(
          'customizer-google-fonts',
          $this-> tc_customizer_gfonts_url(array('Lobster Two' , 'Roboto' , 'PT Sans')),
          array( 'customize-controls' ),
          null
        );
    }



	/**
	* Builds Google Fonts url
	* @package Customizr
	* @since Customizr 3.1.1
	*/
	function tc_customizer_gfonts_url( $fonts = null ) {

      //declares the google font vars
      $fonts_url         = '';
      $fonts 			 = is_null($fonts) ? array('Raleway') : $fonts;
      $fonts 			 = is_array($fonts) ? $fonts : array($fonts);
      $font_families     = apply_filters( 'tc_customizer_google_fonts' , $fonts );

      $query_args        = array(
          'family' => implode( '|', $font_families ),
          //'subset' => urlencode( 'latin,latin-ext' ),
      );

      $fonts_url          = add_query_arg( $query_args, "//fonts.googleapis.com/css" );

      return $fonts_url;
    }



	function tc_db_actions() {
        $dt             = new DateTime(null, new DateTimeZone('UTC'));
        $dt             = $dt->format('D, d M Y H:i:s \G\M\T');
        //updates last modified option
        update_option( 'tc_font_customizer_last_modified' , $dt );
        //update global plulgin options
        TC_wfc::$instance -> tc_update_saved_options( false );
        //update front end google fonts option
        TC_utils_wfc::$instance -> tc_update_front_end_gfonts();
  }


  function tc_add_editor_style() {
		$_opt_prefix    = TC_wfc::$instance -> plug_option_prefix;
    //get gfonts if any
		if ( ! get_option("{$_opt_prefix}_gfonts") || TC_wfc::$instance -> tc_is_customizing() ) {
            TC_utils_wfc::$instance -> tc_update_front_end_gfonts();
        }
        $families   = get_option("{$_opt_prefix}_gfonts");
        $families = str_replace(",", "%2C", $families);
        $families_url   = "//fonts.googleapis.com/css?family={$families}";
    	add_editor_style( array( $families_url , sprintf('%s/front/assets/css/dyn-style.php?is_customizing=false' , TC_WFC_BASE_URL ) ) );
  }


    function tc_plugin_action_links( $links, $file ) {
		if ( $file == plugin_basename( dirname( dirname( dirname(__FILE__) ) ). '/' . basename( TC_wfc::$instance -> plug_file ) ) ) {
			$links[] = '<a href="' . admin_url( 'customize.php' ) . '">'.__( 'Settings' ).'</a>';
			$links[] = '<a href="' . admin_url( 'options.php?page=tc-system-info' ) . '">'.__( 'System infos' ).'</a>';
		}
		return $links;
	}

}//end of class

?>
