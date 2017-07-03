<?php

/*
 *
 * Settings arrays
 *
 */

/* Font family arrays */
$tempera_colorschemes_array = array(
// color scheme presets are defined via schemes.php
);

$tempera_fonts = array(

	'Theme Fonts' => array(
					"Droid Sans",
					"Ubuntu",
					"Ubuntu Light",
					"Open Sans",
					"Open Sans Light",
					"Bebas Neue",
					"Oswald",
					"Oswald Light",
					"Yanone Kaffeesatz Regular",
					"Yanone Kaffeesatz Light"
	),

	'Sans-Serif' => array(
					"Segoe UI, Arial, sans-serif",
					"Verdana, Geneva, sans-serif " ,
					"Geneva, sans-serif ",
					"Helvetica Neue, Arial, Helvetica, sans-serif",
					"Helvetica, sans-serif" ,
					"Century Gothic, AppleGothic, sans-serif",
				    "Futura, Century Gothic, AppleGothic, sans-serif",
					"Calibri, Arian, sans-serif",
				    "Myriad Pro, Myriad,Arial, sans-serif",
					"Trebuchet MS, Arial, Helvetica, sans-serif" ,
					"Gill Sans, Calibri, Trebuchet MS, sans-serif",
					"Impact, Haettenschweiler, Arial Narrow Bold, sans-serif ",
					"Tahoma, Geneva, sans-serif" ,
					"Arial, Helvetica, sans-serif" ,
					"Arial Black, Gadget, sans-serif",
					"Lucida Sans Unicode, Lucida Grande, sans-serif "
	),

	'Serif' => array(
					"Georgia, Times New Roman, Times, serif" ,
					"Times New Roman, Times, serif",
					"Cambria, Georgia, Times, Times New Roman, serif",
					"Palatino Linotype, Book Antiqua, Palatino, serif",
					"Book Antiqua, Palatino, serif",
					"Palatino, serif",
				    "Baskerville, Times New Roman, Times, serif",
 					"Bodoni MT, serif",
					"Copperplate Light, Copperplate Gothic Light, serif",
					"Garamond, Times New Roman, Times, serif"
	),

	'MonoSpace' => array( 
					"Courier New, Courier, monospace" ,
					"Lucida Console, Monaco, monospace",
					"Consolas, Lucida Console, Monaco, monospace",
					"Monaco, monospace"
	),

	'Cursive' => array( 
					"Lucida Casual, Comic Sans MS , cursive ",
				    "Brush Script MT,Phyllis,Lucida Handwriting,cursive",
					"Phyllis,Lucida Handwriting,cursive",
					"Lucida Handwriting,cursive",
					"Comic Sans MS, cursive"
	),
); // fonts


/* Social media links */

$tempera_socialNetworks = array (
		"AboutMe", "AIM", "Amazon", "Contact", "Delicious", "DeviantArt",
		"Digg", "Dribbble", "Etsy", "Facebook", "Flickr",
		"FriendFeed", "Github", "GoodReads", "GooglePlus", "IMDb", "Instagram",
		"LastFM", "LinkedIn", "Mail", "MindVox", "MySpace", "Newsvine", "Phone",
		"Picasa", "Pinterest", "Reddit", "RSS", "ShareThis",
		"Skype", "Steam", "SoundCloud", "StumbleUpon", "Technorati", "TripAdvisor",
		"Tumblr",  "Twitch", "Twitter", "Vimeo", "VK",
		"WordPress", "Yahoo", "Yelp", "YouTube", "Xing" 
);

/*
 *
 * Validate user data
 *
 */
if (!function_exists ('tempera_settings_validate') ) :
function tempera_settings_validate($input) {
	global $tempera_defaults;
	global $temperas;
	global $tempera_colorschemes_array;

	$colorSchemes = ( ! empty( $input['tempera_schemessubmit']) ? true : false ) && ( isset($tempera_colorschemes_array[$input['tempera_colorschemes']]) );
	if ($colorSchemes) {
		$input = array_merge( $temperas, json_decode("{".$tempera_colorschemes_array[$input['tempera_colorschemes']]."}",true) );
		return $input;
	}
	
/*** generic checks, based on datatypes and on field names ***/
    foreach ($input as $name => $value):
	if (preg_match("/^tempera_.*/i",$name)): // only process theme settings
		if (is_array($value)):
			$input[$name] = cryout_proto_arrsan($value); // array
		else:
		switch($value):
			// colour field
			case (preg_match("/^(#[0-9a-f]{3}|#?[0-9a-f]{6})$/i", trim(wp_kses_data($value))) ? $value : !$value):
				$input[$name] = cryout_color_sanitize(trim(wp_kses_data($input[$name])));
			break;	
			// numeric field
			case (preg_match("/^[0-9]+$/i", trim(wp_kses_data($value))) ? $value : !$value):
				$input[$name] = intval(wp_kses_data($input[$name]));
			break;
			default:
				switch($name):
					// long content fields
					case (preg_match("/.*(copyright|excerpt|customcss|customjs|text).*/i",trim($name)) ? $name: !$name):
						$input[$name] = trim(wp_kses_post($input[$name]));
						break;
					// url fields
					case (preg_match("/.*(logoupload|favicon|sliderlink|url).*/i",trim($name)) ? $name: !$name):
						$input[$name] = esc_url_raw($input[$name]);
						break;
					// generic sanitization for the rest
					default:
						$input[$name] = trim(wp_kses_data($input[$name]));
				endswitch;
		endswitch;
		endif; // if array	

	endif;
	endforeach;

/*** more specific checks that should be kept (for now) ***/

/*** 1 ***/
	if ( isset($input['tempera_sidewidth']) && is_numeric($input['tempera_sidewidth']) && $input['tempera_sidewidth']>=500 && $input['tempera_sidewidth'] <=1760 ) { /* value is valid */ } else { $input['tempera_sidewidth'] = $tempera_defaults['tempera_sidewidth']; }
	if ( isset($input['tempera_sidebar']) && is_numeric($input['tempera_sidebar']) && $input['tempera_sidebar']>=220 && $input['tempera_sidebar'] <=800 ) { /* value is valid */ } else { $input['tempera_sidebar'] = $tempera_defaults['tempera_sidebar']; }

	$input['tempera_hheight'] =  intval(wp_kses_data($input['tempera_hheight']));
	$input['tempera_frontpostscount'] =  intval(wp_kses_data($input['tempera_frontpostscount']));
	$input['tempera_excerptwords'] =  intval(wp_kses_data($input['tempera_excerptwords']));
	$input['tempera_fwidth'] =  intval(wp_kses_data($input['tempera_fwidth']));
	$input['tempera_fheight'] =  intval(wp_kses_data($input['tempera_fheight']));
	$input['tempera_contentmargintop'] =  intval(wp_kses_data($input['tempera_contentmargintop']));
	$input['tempera_contentpadding'] =  intval(wp_kses_data($input['tempera_contentpadding']));
	$input['tempera_headermargintop'] =  intval(wp_kses_data($input['tempera_headermargintop']));
	$input['tempera_headermarginleft'] =  intval(wp_kses_data($input['tempera_headermarginleft']));
	$input['tempera_slideNumber'] =  intval(wp_kses_data($input['tempera_slideNumber']));
	$input['tempera_fpsliderwidth'] =  intval(wp_kses_data($input['tempera_fpsliderwidth']));
	$input['tempera_fpsliderheight'] = intval(wp_kses_data($input['tempera_fpsliderheight']));
	$input['tempera_fpslider_topmargin'] = intval(wp_kses_data($input['tempera_fpslider_topmargin']));
	$input['tempera_fpslider_bordersize'] = intval(wp_kses_data($input['tempera_fpslider_bordersize']));

/*** 2 ***/

	$cryout_special_terms = array('mailto:', 'callto://', 'tel:');
	$cryout_special_keys = array('Mail', 'Skype', 'Phone');
	for ( $i=1; $i<10; $i+=2 ) {
		if ( !isset($input['tempera_social_target'.$i]) ) {$input['tempera_social_target'.$i] = "0";}
		$input['tempera_social_title'.$i] = wp_kses_data(trim($input['tempera_social_title'.$i]));
		$j = $i+1;
		if (in_array($input['tempera_social'.$i],$cryout_special_keys)) :
			$input['tempera_social'.$j]	= wp_kses_data(str_replace($cryout_special_terms,'',$input['tempera_social'.$j]));
			if (in_array($input['tempera_social'.$i],$cryout_special_keys)):
				$prefix = $cryout_special_terms[array_search($input['tempera_social'.$i],$cryout_special_keys)];
				$input['tempera_social'.$j] = $prefix.$input['tempera_social'.$j];
			endif;
		else :
			$input['tempera_social'.$j] = esc_url_raw($input['tempera_social'.$j]);
		endif;
	} //
	for ( $i=0; $i<=5; $i++ ) {
		if (!isset($input['tempera_socialsdisplay'.$i])) {$input['tempera_socialsdisplay'.$i] = "0";}
	}

	$show_search= array("top","main","footer");
	foreach ($show_search as $item) {
		$input['tempera_searchbar'][$item] = (!empty($input['tempera_searchbar'][$item]) ? 1 : 0);
	}

	$show_blog = array("author","date","time","category","tag","comments");
	foreach ($show_blog as $item) {
		$input['tempera_blog_show'][$item] = (!empty($input['tempera_blog_show'][$item]) ? 1 : 0);
	}

	$show_single = array("author","date","time","category","tag","bookmark");
	foreach ($show_single as $item) {
		$input['tempera_single_show'][$item] = (!empty($input['tempera_single_show'][$item]) ? 1 : 0);
	}


/*** 3 ***/

	$input['tempera_columnNumber'] = intval(wp_kses_data($input['tempera_columnNumber']));
	$input['tempera_nrcolumns'] = intval(wp_kses_data($input['tempera_nrcolumns']));
	$input['tempera_colimageheight'] = intval(wp_kses_data($input['tempera_colimageheight']));

	
/*** 4 ***/
	
	// make sure all options have values, even blank
	foreach ( $tempera_defaults as $key => $value ) {
		if ( is_array($value) ) foreach ( $value as $subkey => $subvalue ) {
			if ( !isset($input[$key][$subkey]) ) $input[$key] = array( $subkey => '' );
		}
		if ( !isset($input[$key]) ) $input[$key] = '';
	}

	// handle settings reset
	$resetDefault = ( ! empty( $input['tempera_defaults']) ? true : false );
	if ($resetDefault) { $input = $tempera_defaults; }

	return $input; // return validated input

}

endif;

// FIN