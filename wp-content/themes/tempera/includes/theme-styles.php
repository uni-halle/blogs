<?php
/*
 * Styles and scripts registration and enqueuing
 *
 * @package tempera
 * @subpackage Functions
 */

// Adding the viewport meta if the mobile view has been enabled

function tempera_register_styles() {
	wp_register_style( 'tempera-style', get_stylesheet_uri(), NULL, _CRYOUT_THEME_VERSION );
	wp_register_style( 'tempera-fonts', get_template_directory_uri() . '/fonts/fontfaces.css', NULL, _CRYOUT_THEME_VERSION );
	if (is_rtl()) wp_enqueue_style( 'parabola-rtl', get_template_directory_uri() . '/styles/rtl.css', NULL, _CRYOUT_THEME_VERSION );
}

add_action('init', 'tempera_register_styles' );


function tempera_enqueue_styles() {
	global $temperas;
	foreach ($temperas as $key => $value) { ${"$key"} = $value ;}

	$gfonts = array();

	if($tempera_mobile=="Enable") { wp_register_style( 'tempera-mobile', get_template_directory_uri() . '/styles/style-mobile.css', NULL, _CRYOUT_THEME_VERSION ); }
	if($tempera_frontpage=="Enable" ) { wp_register_style( 'tempera-frontpage', get_template_directory_uri() . '/styles/style-frontpage.css', NULL, _CRYOUT_THEME_VERSION ); }

	if($tempera_googlefont) $gfonts[] = esc_attr(preg_replace( '/\s+/', '+', $tempera_googlefont ));
	if($tempera_googlefonttitle) $gfonts[] = esc_attr(preg_replace( '/\s+/', '+',$tempera_googlefonttitle ));
	if($tempera_googlefontside) $gfonts[] = esc_attr(preg_replace( '/\s+/', '+',$tempera_googlefontside ));
	if($tempera_headingsgooglefont) $gfonts[] = esc_attr(preg_replace( '/\s+/', '+',$tempera_headingsgooglefont ));
	if($tempera_sitetitlegooglefont) $gfonts[] = esc_attr(preg_replace( '/\s+/', '+',$tempera_sitetitlegooglefont ));
	if($tempera_menugooglefont) $gfonts[] = esc_attr(preg_replace( '/\s+/', '+',$tempera_menugooglefont ));

	wp_enqueue_style( 'tempera-fonts');

	// enqueue fonts with subsets separately
	foreach($gfonts as $i=>$gfont):
		if (strpos($gfont,"&") === false):
		   // do nothing
		else:
			wp_enqueue_style( 'tempera-googlefont_'.$i, '//fonts.googleapis.com/css?family=' . $gfont );
			unset($gfonts[$i]);
		endif;
	endforeach;

	// merged fonts
	if ( count($gfonts)>0 ):
		wp_enqueue_style( 'tempera-googlefonts', '//fonts.googleapis.com/css?family=' . implode( "|" , array_unique($gfonts) ), array(), null, 'screen' );
	endif;
	wp_enqueue_style( 'tempera-style' );

}

if( !is_admin() ) { add_action('wp_head', 'tempera_enqueue_styles', 5 ); }

function tempera_styles_echo() {
	global $temperas;

	foreach ($temperas as $key => $value) { ${"$key"} = $value ;}
	echo preg_replace("/[\n\r\t\s]+/"," " ,tempera_custom_styles())."\n";

	if(($tempera_frontpage=="Enable")&&is_front_page()) { echo preg_replace("/[\n\r\t\s]+/"," " ,tempera_presentation_css())."\n";}
	echo preg_replace("/[\n\r\t\s]+/"," " ,tempera_customcss())."\n";
}

add_action('wp_head', 'tempera_styles_echo', 20);

function tempera_load_mobile_css() {
	global $temperas;
	if ($temperas['tempera_mobile']=="Enable") {
		echo "<link rel='stylesheet' id='tempera-style-mobile'  href='".get_template_directory_uri() . "/styles/style-mobile.css?ver=" . _CRYOUT_THEME_VERSION . "' type='text/css' media='all' />";
	}
}
if( !is_admin() ) { add_action ('wp_head','tempera_load_mobile_css', 30); }

// JS loading and hook into wp_enque_scripts
add_action('wp_footer', 'tempera_customjs', 35 );



// Scripts loading and hook into wp_enque_scripts

function tempera_scripts_method() {
	global $temperas;
	foreach ($temperas as $key => $value) { ${"$key"} = $value; }

	wp_enqueue_script('tempera-frontend',get_template_directory_uri() . '/js/frontend.js', array('jquery'), _CRYOUT_THEME_VERSION, true );

	if ($tempera_frontpage == "Enable" && is_front_page()) {
			// if PP and the current page is frontpage - load the nivo slider js
			wp_enqueue_script('tempera-nivoslider',get_template_directory_uri() . '/js/nivo.slider.min.js', array('jquery'), _CRYOUT_THEME_VERSION, true);
			// add slider init js in footer
			add_action('wp_footer', 'tempera_pp_slider' );
	}

	$js_options = array(
		//'masonry' => $tempera_masonry,
		'mobile' => (($tempera_mobile=='Enable')?1:0),
		'fitvids' => $tempera_fitvids,
	);
	wp_localize_script( 'tempera-frontend', 'tempera_settings', $js_options );
	
	/* We add some JavaScript to pages with the comment form
	 * to support sites with threaded comments (when in use).
	 */
	if ( is_singular() && get_option( 'thread_comments' ) )
		wp_enqueue_script( 'comment-reply' );
	
} // tempera_scripts_method()

if( !is_admin() ) { add_action('wp_enqueue_scripts', 'tempera_scripts_method'); }
