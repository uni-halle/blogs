<?php
/**
 * Define our settings sections
 *
 * array key=$id, array value=$title in: add_settings_section( $id, $title, $callback, $page );
 * @return array
 */
function maja_options_page_sections() {
	
	$sections = array();
	// $sections[$id] 				= __($title, 'maja');
	$sections['appearance_section'] = __('Appearance', 'maja');
	$sections['details_section'] 	= __('Site Details', 'maja');
	$sections['social_section'] 	= __('Social Profiles', 'maja');	
	$sections['map_section'] 	= __('Google Map', 'maja');		
	return $sections;	
}

/**
 * Define our form fields (settings) 
 *
 * @return array
 */
function maja_options_page_fields() {
	
	//Appearance section
	$options[] = array(
		"section" => "appearance_section",
		"id"      => MAJA_SHORTNAME . "_logo",
		"title"   => __( 'Logo', 'maja' ),
		"desc"    => __( 'Paste an image URL of your logo here.', 'maja' ),
		"type"    => "text",
		"std"     => "",
		"class"   => "url"
	);	
	
	$options[] = array(
		"section" => "appearance_section",
		"id"      => MAJA_SHORTNAME . "_favicon",
		"title"   => __( 'Favicon', 'maja' ),
		"desc"    => __( 'Paste an image URL of your favicon here (16x16px ICO file).', 'maja' ),
		"type"    => "text",
		"std"     => "",
		"class"   => "url"
	);		
	
	$options[] = array(
		"section" => "appearance_section",
		"id"      => MAJA_SHORTNAME . "_color",
		"title"   => __( 'Color Scheme', 'maja' ),
		"desc"    => __( 'This will change the color of ribbons and selections.', 'maja' ),
		"type"    => "select2",
		"std"    => "Green",
		"choices" => array(__('Black','maja') . "|black", __('Blue','maja') . "|blue", __('Green','maja') . "|green", __('Orange','maja') . "|orange", __('Violet','maja') . "|violet",  __('Red','maja') . "|red", __('Brown','maja') . "|brown")		
	);
	
	$options[] = array(
		"section" => "appearance_section",
		"id"      => MAJA_SHORTNAME . "_customcss",
		"title"   => __( 'Custom CSS code', 'maja' ),
		"desc"    => __( 'For example: h1, h2, h3 { color:blue; }', 'maja' ),
		"type"    => "textarea",
		"std"     => "",
		"class"   => "allowlinebreaks"
	);	
	
	//Site details
	$options[] = array(
		"section" => "details_section",
		"id"      => MAJA_SHORTNAME . "_copyright",
		"title"   => __( 'Copyright information <br/> (will appear inside the left sidebar)', 'maja' ),
		"desc"    => __( 'Some inline HTML (&lt;a&gt;, &lt;b&gt;, &lt;em&gt;, &lt;i&gt;, &lt;strong&gt;) is allowed.', 'maja' ),
		"type"    => "text",
		"std"     => __('&copy; 2012, Site name','maja')
	);
	
	$options[] = array(
		"section" => "details_section",
		"id"      => MAJA_SHORTNAME . "_nav-title",
		"title"   => __( 'Default text for mobile navigation', 'maja' ),
		"desc"    => __( 'This text will be displayed as a front text for  dropdown navigation when viewing on a small screen device.', 'maja' ),
		"type"    => "text",
		"std"     => __('Go to page...','maja')
	);	
	
	$options[] = array(
		"section" => "details_section",
		"id"      => MAJA_SHORTNAME . "_rssfeed",
		"title"   => __( 'Display RSS Feed icon', 'maja' ),
		"desc"    => __( 'RSS icon will appear alongside with social profiles icons.', 'maja' ),
		"type"    => "checkbox",
		"std"     => 1 // 0 for off
	);	
	
	$options[] = array(
		"section" => "details_section",
		"id"      => MAJA_SHORTNAME . "_slider",
		"title"   => __( 'Display Flex Slider', 'maja' ),
		"desc"    => __( 'Flex Slider will be displayed where [flex-slider] shortcode is placed', 'maja' ),
		"type"    => "checkbox",
		"std"     => 1 // 0 for off
	);			
	
	$options[] = array(
		"section" => "details_section",
		"id"      => MAJA_SHORTNAME . "_quicksand",
		"title"   => __( 'Filter portfolio categories', 'maja' ),
		"desc"    => __( 'Check if you want to use category filtering inside portfolio (Quicksand).', 'maja' ),
		"type"    => "checkbox",
		"std"     => 1 // 0 for off
	);		
	
	//Social Profiles
	
	/* forrst */	
	$options[] = array(
		"section" => "social_section",
		"id"      => MAJA_SHORTNAME . "_forrst",
		"title"   => __( 'Forrst', 'maja' ),
		"desc"    => __( 'Your Forrst profile link', 'maja' ),
		"type"    => "text",
		"std"     => "",
		"class"   => "url"		
	);		
	
	/* facebook */
	$options[] = array(
		"section" => "social_section",
		"id"      => MAJA_SHORTNAME . "_facebook",
		"title"   => __( 'Facebook', 'maja' ),
		"desc"    => __( 'Your Facebook profile link', 'maja' ),
		"type"    => "text",
		"std"     => "",
		"class"   => "url"
	);	
	
	/* dribbble */	
	$options[] = array(
		"section" => "social_section",
		"id"      => MAJA_SHORTNAME . "_dribbble",
		"title"   => __( 'Dribbble', 'maja' ),
		"desc"    => __( 'Your Dribbble profile link', 'maja' ),
		"type"    => "text",
		"std"     => "",
		"class"   => "url"		
	);	
	
	/* youtube */	
	$options[] = array(
		"section" => "social_section",
		"id"      => MAJA_SHORTNAME . "_youtube",
		"title"   => __( 'YouTube', 'maja' ),
		"desc"    => __( 'Your YouTube profile link', 'maja' ),
		"type"    => "text",
		"std"     => "",
		"class"   => "url"		
	);	
		
	/* vimeo */	
	$options[] = array(
		"section" => "social_section",
		"id"      => MAJA_SHORTNAME . "_vimeo",
		"title"   => __( 'Vimeo', 'maja' ),
		"desc"    => __( 'Your Vimeo profile link', 'maja' ),
		"type"    => "text",
		"std"     => "",
		"class"   => "url"		
	);	
	
	/* twitter */	
	$options[] = array(
		"section" => "social_section",
		"id"      => MAJA_SHORTNAME . "_twitter",
		"title"   => __( 'Twitter', 'maja' ),
		"desc"    => __( 'Your Twitter profile link', 'maja' ),
		"type"    => "text",
		"std"     => "",
		"class"   => "url"	
	);	
	
	
	//Google map
	$options[] = array(
		"section" => "map_section",
		"id"      => MAJA_SHORTNAME . "_map-check",
		"title"   => __( 'Display Google Map', 'maja' ),
		"desc"    => __( 'Google map will be displayed where [google-map] shortcode is placed', 'maja' ),
		"type"    => "checkbox",
		"std"     => 1 // 0 for off
	);		
	
	$options[] = array(
		"section" => "map_section",
		"id"      => MAJA_SHORTNAME . "_map-lat",
		"title"   => __( 'Latitude', 'maja' ),
		"desc"    => __( 'Enter your location latitude (from maps.google.com).', 'maja' ),
		"type"    => "text",
		"std"     => "-37.812537",
		"class"   => "numeric"
	);		
	
	$options[] = array(
		"section" => "map_section",
		"id"      => MAJA_SHORTNAME . "_map-lng",
		"title"   => __( 'Longitude', 'maja' ),
		"desc"    => __( 'Enter your location longitude (from maps.google.com).', 'maja' ),
		"type"    => "text",
		"std"     => "144.969041",
		"class"   => "numeric"
	);	
	
	$options[] = array(
		"section" => "map_section",
		"id"      => MAJA_SHORTNAME . "_map-zoom",
		"title"   => __( 'Zoom level', 'maja' ),
		"desc"    => __( 'Enter zoom level (default 15).', 'maja' ),
		"type"    => "text",
		"std"     => "15",
		"class"   => "numeric"
	);			
	
	return $options;	
}
?>