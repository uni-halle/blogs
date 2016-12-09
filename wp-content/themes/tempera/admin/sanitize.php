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

$fonts = array(

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
					 "Yanone Kaffeesatz Light"),

	'Sans-Serif' => array("Segoe UI, Arial, sans-serif",
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
					 "Lucida Sans Unicode, Lucida Grande, sans-serif "),

	'Serif' => array("Georgia, Times New Roman, Times, serif" ,
					  "Times New Roman, Times, serif",
					  "Cambria, Georgia, Times, Times New Roman, serif",
					  "Palatino Linotype, Book Antiqua, Palatino, serif",
					  "Book Antiqua, Palatino, serif",
					  "Palatino, serif",
				       "Baskerville, Times New Roman, Times, serif",
 					  "Bodoni MT, serif",
					  "Copperplate Light, Copperplate Gothic Light, serif",
					  "Garamond, Times New Roman, Times, serif"),

	'MonoSpace' => array( "Courier New, Courier, monospace" ,
					  "Lucida Console, Monaco, monospace",
					  "Consolas, Lucida Console, Monaco, monospace",
					  "Monaco, monospace"),

	'Cursive' => array(  "Lucida Casual, Comic Sans MS , cursive ",
				      "Brush Script MT,Phyllis,Lucida Handwriting,cursive",
					 "Phyllis,Lucida Handwriting,cursive",
					 "Lucida Handwriting,cursive",
					 "Comic Sans MS, cursive")
); // fonts


/* Social media links */

$socialNetworks = array (
		"AboutMe", "AIM", "Amazon", "Contact", "Delicious", "DeviantArt", 
		"Digg", "Dribbble", "Etsy", "Facebook", "Flickr",
		"FriendFeed", "GoodReads", "GooglePlus", "IMDb", "Instagram",
		"LastFM", "LinkedIn", "Mail", "MindVox", "MySpace", "Newsvine", "Phone",
		"Picasa", "Pinterest", "Reddit", "RSS", "ShareThis",  
		"Skype", "Steam", "SoundCloud", "StumbleUpon", "Technorati", 
		"Tumblr",  "Twitch", "Twitter", "Vimeo", "VK",
		"WordPress", "Yahoo", "Yelp", "YouTube", "Xing" );

if (!function_exists ('tempera_options_validate') ) :
/*
 *
 * Validate user data
 *
 */
function tempera_settings_validate($input) {
global $tempera_defaults;
global $temperas;
global $tempera_colorschemes_array ;

$colorSchemes = ( ! empty( $input['tempera_schemessubmit']) ? true : false );
if ($colorSchemes) : $input = array_merge($temperas,json_decode("{".$tempera_colorschemes_array[$input['tempera_colorschemes']]."}",true));
else :
/*** 1 ***/
	if(isset($input['tempera_sidewidth']) && is_numeric($input['tempera_sidewidth']) && $input['tempera_sidewidth']>=500 && $input['tempera_sidewidth'] <=1760) {} else {$input['tempera_sidewidth']=$tempera_defaults['tempera_sidewidth']; }
	if(isset($input['tempera_sidebar']) && is_numeric($input['tempera_sidebar']) && $input['tempera_sidebar']>=220 && $input['tempera_sidebar'] <=800) {} else {$input['tempera_sidebar']=$tempera_defaults['tempera_sidebar']; }

	$input['tempera_hheight'] =  intval(wp_kses_data($input['tempera_hheight']));
	$input['tempera_copyright'] = trim(wp_kses_post($input['tempera_copyright']));
	
	$input["tempera_headerwidgetwidth"] = trim(wp_kses_data($input['tempera_headerwidgetwidth']));

	$input["tempera_backcolorheader"] = trim(wp_kses_data($input['tempera_backcolorheader']));
	$input["tempera_backcolormain"] = trim(wp_kses_data($input['tempera_backcolormain']));
	$input["tempera_backcolorfooterw"] = trim(wp_kses_data($input['tempera_backcolorfooterw']));
	$input["tempera_backcolorfooter"] = trim(wp_kses_data($input['tempera_backcolorfooter']));

	$input["tempera_contentcolortxt"] = trim(wp_kses_data($input['tempera_contentcolortxt']));
	$input["tempera_contentcolortxtlight"] = trim(wp_kses_data($input['tempera_contentcolortxtlight']));
	$input["tempera_footercolortxt"] = trim(wp_kses_data($input['tempera_footercolortxt']));

	$input["tempera_titlecolor"] = trim(wp_kses_data($input['tempera_titlecolor']));
	$input["tempera_descriptioncolor"] = trim(wp_kses_data($input['tempera_descriptioncolor']));
	$input["tempera_descriptionbg"] = trim(wp_kses_data($input['tempera_descriptionbg']));

	$input["tempera_menucolorbgdefault"] = trim(wp_kses_data($input['tempera_menucolorbgdefault']));
	$input["tempera_submenucolorshadow"] = trim(wp_kses_data($input['tempera_submenucolorshadow']));
	$input["tempera_menucolortxtdefault"] = trim(wp_kses_data($input['tempera_menucolortxtdefault']));

	$input["tempera_topmenucolortxt"] = trim(wp_kses_data($input['tempera_topmenucolortxt']));
	$input["tempera_topmenucolortxthover"] = trim(wp_kses_data($input['tempera_topmenucolortxthover']));
	$input["tempera_topbarcolorbg"] = trim(wp_kses_data($input['tempera_topbarcolorbg']));

	$input["tempera_contentcolorbg"] = trim(wp_kses_data($input['tempera_contentcolorbg']));
	$input["tempera_contentcolortxttitle"] = trim(wp_kses_data($input['tempera_contentcolortxttitle']));
	$input["tempera_contentcolortxttitlehover"] = trim(wp_kses_data($input['tempera_contentcolortxttitlehover']));
	$input["tempera_contentcolortxtheadings"] = trim(wp_kses_data($input['tempera_contentcolortxtheadings']));

	$input["tempera_sidebg"] = trim(wp_kses_data($input['tempera_sidebg']));
	$input["tempera_sidetxt"] = trim(wp_kses_data($input['tempera_sidetxt']));
	$input["tempera_sidetitlebg"] = trim(wp_kses_data($input['tempera_sidetitlebg']));
	$input["tempera_sidetitletxt"] = trim(wp_kses_data($input['tempera_sidetitletxt']));

	$input["tempera_widgetbg"] = trim(wp_kses_data($input['tempera_widgetbg']));
	$input["tempera_widgettxt"] = trim(wp_kses_data($input['tempera_widgettxt']));
	$input["tempera_widgettitlebg"] = trim(wp_kses_data($input['tempera_widgettitlebg']));
	$input["tempera_widgettitletxt"] = trim(wp_kses_data($input['tempera_widgettitletxt']));

	$input["tempera_linkcolortext"] = trim(wp_kses_data($input['tempera_linkcolortext']));
	$input["tempera_linkcolorhover"] = trim(wp_kses_data($input['tempera_linkcolorhover']));
	$input["tempera_linkcolorside"] = trim(wp_kses_data($input['tempera_linkcolorside']));
	$input["tempera_linkcolorsidehover"] = trim(wp_kses_data($input['tempera_linkcolorsidehover']));
	$input["tempera_linkcolorwooter"] = trim(wp_kses_data($input['tempera_linkcolorwooter']));
	$input["tempera_linkcolorwooterhover"] = trim(wp_kses_data($input['tempera_linkcolorwooterhover']));
	$input["tempera_linkcolorfooter"] = trim(wp_kses_data($input['tempera_linkcolorfooter']));
	$input["tempera_linkcolorfooterhover"] = trim(wp_kses_data($input['tempera_linkcolorfooterhover']));

	$input["tempera_accentcolora"] = trim(wp_kses_data($input['tempera_accentcolora']));
	$input["tempera_accentcolorb"] = trim(wp_kses_data($input['tempera_accentcolorb']));
	$input["tempera_accentcolorc"] = trim(wp_kses_data($input['tempera_accentcolorc']));
	$input["tempera_accentcolord"] = trim(wp_kses_data($input['tempera_accentcolord']));
	$input["tempera_accentcolore"] = trim(wp_kses_data($input['tempera_accentcolore']));
	
	$input['tempera_frontpostscount'] =  intval(wp_kses_data($input['tempera_frontpostscount'])); 

	$input['tempera_fronttitlecolor'] =  wp_kses_data($input['tempera_fronttitlecolor']);
	$input['tempera_fpsliderbordercolor'] =  wp_kses_data($input['tempera_fpsliderbordercolor']);
	$input['tempera_fpslidercaptioncolor'] =  wp_kses_data($input['tempera_fpslidercaptioncolor']);
	$input['tempera_fpslidercaptionbg'] =  wp_kses_data($input['tempera_fpslidercaptionbg']);
	
	$input["tempera_socialcolorbg"] = trim(wp_kses_data($input['tempera_socialcolorbg']));
	$input["tempera_socialcolorbghover"] = trim(wp_kses_data($input['tempera_socialcolorbghover']));
	
	$input["tempera_metacoloricons"] = trim(wp_kses_data($input['tempera_metacoloricons']));
	$input["tempera_metacolorlinks"] = trim(wp_kses_data($input['tempera_metacolorlinks']));
	$input["tempera_metacolorlinkshover"] = trim(wp_kses_data($input['tempera_metacolorlinkshover']));

	$input['tempera_excerptwords'] =  intval(wp_kses_data($input['tempera_excerptwords']));
	$input['tempera_excerptdots'] =  wp_kses_data($input['tempera_excerptdots']);
	$input['tempera_excerptcont'] =  wp_kses_data($input['tempera_excerptcont']);

	$input['tempera_fwidth'] =  intval(wp_kses_data($input['tempera_fwidth']));
	$input['tempera_fheight'] =  intval(wp_kses_data($input['tempera_fheight']));
	
	$input['tempera_contentmargintop'] =  intval(wp_kses_data($input['tempera_contentmargintop']));
	$input['tempera_contentpadding'] =  intval(wp_kses_data($input['tempera_contentpadding']));

/*** 2 ***/

	$cryout_special_terms = array('mailto:', 'callto://', 'tel:');
	$cryout_special_keys = array('Mail', 'Skype', 'Phone');
	for ($i=1;$i<10;$i+=2) {
		if (!isset($input['tempera_social_target'.$i])) {$input['tempera_social_target'.$i] = "0";}
		$input['tempera_social_title'.$i] = wp_kses_data(trim($input['tempera_social_title'.$i]));
		$j=$i+1;
		if (in_array($input['tempera_social'.$i],$cryout_special_keys)) :
			$input['tempera_social'.$j]	= wp_kses_data(str_replace($cryout_special_terms,'',$input['tempera_social'.$j]));
			if (in_array($input['tempera_social'.$i],$cryout_special_keys)):
				$prefix = $cryout_special_terms[array_search($input['tempera_social'.$i],$cryout_special_keys)];
				$input['tempera_social'.$j] = $prefix.$input['tempera_social'.$j];
			endif;
		else :
			$input['tempera_social'.$j] = esc_url_raw($input['tempera_social'.$j]);
		endif;
	}
	for ($i=0;$i<=5;$i++) {
		if (!isset($input['tempera_socialsdisplay'.$i])) {$input['tempera_socialsdisplay'.$i] = "0";}
		}
		
	$show_blog= array("author","date","time","category","tag","comments");
	foreach ($show_blog as $item) :
		if (!isset($input['tempera_blog_show'][$item])) {$input['tempera_blog_show'][$item] = 0;}
	endforeach;
	
	$show_single= array("author","date","time","category","tag","bookmark");
	foreach ($show_single as $item) :
	if (!isset($input['tempera_single_show'][$item])) {$input['tempera_single_show'][$item] = 0;}
	endforeach;


	$input['tempera_favicon'] =  esc_url_raw($input['tempera_favicon']);
	$input['tempera_logoupload'] =  esc_url_raw($input['tempera_logoupload']);
	$input['tempera_headermargintop'] =  intval(wp_kses_data($input['tempera_headermargintop']));
	$input['tempera_headermarginleft'] =  intval(wp_kses_data($input['tempera_headermarginleft']));

	$input['tempera_customcss'] =  wp_kses_post(trim($input['tempera_customcss']));
	$input['tempera_customjs'] =  wp_kses_post(trim($input['tempera_customjs']));

	$input['tempera_googlefont'] = 	trim(wp_kses_data($input['tempera_googlefont']));
	$input['tempera_googlefonttitle'] = 	trim(wp_kses_data($input['tempera_googlefonttitle']));
	$input['tempera_googlefontside'] = 	trim(wp_kses_data($input['tempera_googlefontside']));
	$input['tempera_headingsgooglefont'] = 	trim(wp_kses_data($input['tempera_headingsgooglefont']));
	$input['tempera_sitetitlegooglefont'] = 	trim(wp_kses_data($input['tempera_sitetitlegooglefont']));
	$input['tempera_menugooglefont'] = 	trim(wp_kses_data($input['tempera_menugooglefont']));

	$input['tempera_slideNumber'] =  intval(wp_kses_data($input['tempera_slideNumber']));
	$input['tempera_slideSpecific'] = wp_kses_data($input['tempera_slideSpecific']);

	$input['tempera_fpsliderwidth'] =  intval(wp_kses_data($input['tempera_fpsliderwidth']));
	$input['tempera_fpsliderheight'] = intval(wp_kses_data($input['tempera_fpsliderheight']));
	$input['tempera_fpslider_topmargin'] = intval(wp_kses_data($input['tempera_fpslider_topmargin']));
	$input['tempera_fpslider_bordersize'] = intval(wp_kses_data($input['tempera_fpslider_bordersize']));
	
/** 3 ***/
	$input['tempera_sliderimg1'] =  wp_kses_data($input['tempera_sliderimg1']);
	$input['tempera_slidertitle1'] =  wp_kses_data($input['tempera_slidertitle1']);
	$input['tempera_slidertext1'] =  wp_kses_post($input['tempera_slidertext1']);
	$input['tempera_sliderlink1'] =  esc_url_raw($input['tempera_sliderlink1']);
	$input['tempera_sliderimg2'] =  wp_kses_data($input['tempera_sliderimg2']);
	$input['tempera_slidertitle2'] =  wp_kses_data($input['tempera_slidertitle2']);
	$input['tempera_slidertext2'] =  wp_kses_post($input['tempera_slidertext2']);
	$input['tempera_sliderlink2'] =  esc_url_raw($input['tempera_sliderlink2']);
	$input['tempera_sliderimg3'] =  wp_kses_data($input['tempera_sliderimg3']);
	$input['tempera_slidertitle3'] =  wp_kses_data($input['tempera_slidertitle3']);
	$input['tempera_slidertext3'] =  wp_kses_post($input['tempera_slidertext3']);
	$input['tempera_sliderlink3'] =  esc_url_raw($input['tempera_sliderlink3']);
	$input['tempera_sliderimg4'] =  wp_kses_data($input['tempera_sliderimg4']);
	$input['tempera_slidertitle4'] =  wp_kses_data($input['tempera_slidertitle4']);
	$input['tempera_slidertext4'] =  wp_kses_post($input['tempera_slidertext4']);
	$input['tempera_sliderlink4'] =  esc_url_raw($input['tempera_sliderlink4']);
	$input['tempera_sliderimg5'] =  wp_kses_data($input['tempera_sliderimg5']);
	$input['tempera_slidertitle5'] =  wp_kses_data($input['tempera_slidertitle5']);
	$input['tempera_slidertext5'] =  wp_kses_post($input['tempera_slidertext5']);
	$input['tempera_sliderlink5'] =  esc_url_raw($input['tempera_sliderlink5']);
	
	$input['tempera_columnNumber'] = intval(wp_kses_data($input['tempera_columnNumber']));
	$input['tempera_nrcolumns'] = intval(wp_kses_data($input['tempera_nrcolumns']));
	$input['tempera_colimageheight'] = intval(wp_kses_data($input['tempera_colimageheight']));
	
/** 4 **/
	$input['tempera_columnreadmore'] =  wp_kses($input['tempera_columnreadmore'],'');

	$input['tempera_fronttext1'] =  trim( wp_kses_post($input['tempera_fronttext1']));
	$input['tempera_fronttext2'] =  trim( wp_kses_post($input['tempera_fronttext2']));
	$input['tempera_fronttext3'] = trim( wp_kses_post($input['tempera_fronttext3']));
	$input['tempera_fronttext4'] = trim (wp_kses_post($input['tempera_fronttext4']));
	
	$input['tempera_postboxes'] = wp_kses_post($input['tempera_postboxes']);

	$resetDefault = ( ! empty( $input['tempera_defaults']) ? true : false );


	if ($resetDefault) { $input = $tempera_defaults; }
endif;

	return $input; // return validated input

}

endif;
?>