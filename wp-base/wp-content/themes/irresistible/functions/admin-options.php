<?php

// THIS IS THE DIFFERENT FIELDS
$options[] = array(	"name" => "General Settings",
					"type" => "heading");
						
$options[] = array(	"name" => "Theme Stylesheet",
					"desc" => "Please select your colour scheme here.",
					"id" => $shortname."_alt_stylesheet",
					"std" => "",
					"type" => "select",
					"options" => $alt_stylesheets);

$options[] = array(	"name" => "Custom Logo",
					"desc" => "Paste the full URL of your custom logo image, should you wish to replace our default logo e.g. 'http://www.yoursite.com/logo.png'.",
					"id" => $shortname."_logo",
					"std" => "",
					"type" => "text");					 							    

$options[] = array(	"name" => "Google Analytics",
					"desc" => "Please paste your Google Analytics (or other) tracking code here.",
					"id" => $shortname."_google_analytics",
					"std" => "",
					"type" => "textarea");		

$options[] = array(	"name" => "Feedburner RSS URL",
					"desc" => "Enter your Feedburner URL here.",
					"id" => $shortname."_feedburner_url",
					"std" => "",
					"type" => "text");			

$options[] = array(	"name" => "Customized Homepage",
					"type" => "heading");	

$options[] = array(	"name" => "Enable homepage",
					"desc" => "Check this to use the customized homepage (located in custom-home.php). You must setup the settings underneath also.",
					"id" => $shortname."_home",
					"std" => "false",
					"type" => "checkbox");	

$options[] = array(	"name" => "Number of posts",
					"desc" => "Enter the number of posts you want to display under MyWritings.",
					"id" => $shortname."_home_posts",
					"std" => "",
					"type" => "text");			

$options[] = array(	"name" => "Lifestream posts",
					"desc" => "Enter the number of posts you want to display under MyLifestream. You must have the <a href=\"http://wordpress.org/extend/plugins/lifestream/\">Lifestream</a> plugin installed",
					"id" => $shortname."_home_lifestream",
					"std" => "",
					"type" => "text");			

$options[] = array(	"name" => "Flickr ID",
					"desc" => "Enter your Flickr ID here. Use <a href=\"http://www.idgettr.com\">IDGettr</a> to find it.",
					"id" => $shortname."_home_flickr_user",
					"std" => "",
					"type" => "text");			

$options[] = array(	"name" => "Flickr count",
					"desc" => "Enter how many flickr photos you want to display. Max 10.",
					"id" => $shortname."_home_flickr_count",
					"std" => "",
					"type" => "text");			

$options[] = array(	"name" => "Flickr URl",
					"desc" => "Enter the URl to your flickr account here.",
					"id" => $shortname."_home_flickr_url",
					"std" => "",
					"type" => "text");			

$options[] = array(	"name" => "Archives URl",
					"desc" => "Enter the URl to archive page template e.g. http://mysite.com/archives/. To make an archive page: Add new page with page template 'Archive Page'.",
					"id" => $shortname."_home_archives",
					"std" => "",
					"type" => "text");			

$options[] = array(	"name" => "Blog Layout Options",
					"type" => "heading");	

$options[] = array(	"name" => "Left Sidebar",
					"desc" => "Check this to show the sidebar on the left side instead of right.",
					"id" => $shortname."_mainright",
					"std" => "false",
					"type" => "checkbox");	

$options[] = array(	"name" => "Category Navigation?",
					"desc" => "Check this to show categories instead of pages in the top navigation.",
					"id" => $shortname."_nav",
					"std" => "false",
					"type" => "checkbox");	

$options[] = array(	"name" => "Disable Tabs?",
					"desc" => "Check this to disable the sidebar tabs.",
					"id" => $shortname."_tabs",
					"std" => "false",
					"type" => "checkbox");	

$options[] = array(	"name" => "Disable Video?",
					"desc" => "Check this to disable the sidebar video widget.",
					"id" => $shortname."_video",
					"std" => "false",
					"type" => "checkbox");	

$options[] = array(	"name" => "About You",
					"type" => "heading");	

$options[] = array(	"name" => "About You Snippet",
					"desc" => "Include a little snippet about yourself that is displayed in the header.",
					"id" => $shortname."_about",
					"std" => "",
					"type" => "textarea");	

$options[] = array(	"name" => "About You Read More Link",
					"desc" => "URL of the read more link e.g. http://www.yoursite.com/about",
					"id" => $shortname."_aboutlink",
					"std" => "#",
					"type" => "text");	

$options[] = array(	"name" => "Banner Ads Sidebar - Widget (125x125)",
					"type" => "heading");

$options[] = array(	"name" => "Rotate banners?",
					"desc" => "Check this to randomly rotate the banner ads.",
					"id" => $shortname."_ads_rotate",
					"std" => "true",
					"type" => "checkbox");	

$options[] = array(	"name" => "Banner Ad #1 - Image Location",
					"desc" => "Enter the URL for this banner ad.",
					"id" => $shortname."_ad_image_1",
					"std" => "http://www.woothemes.com/ads/woothemes-125x125-1.gif",
					"type" => "text");
						
$options[] = array(	"name" => "Banner Ad #1 - Destination",
					"desc" => "Enter the URL where this banner ad points to.",
					"id" => $shortname."_ad_url_1",
					"std" => "http://www.woothemes.com",
					"type" => "text");						

$options[] = array(	"name" => "Banner Ad #2 - Image Location",
					"desc" => "Enter the URL for this banner ad.",
					"id" => $shortname."_ad_image_2",
					"std" => "http://www.woothemes.com/ads/woothemes-125x125-2.gif",
					"type" => "text");
						
$options[] = array(	"name" => "Banner Ad #2 - Destination",
					"desc" => "Enter the URL where this banner ad points to.",
					"id" => $shortname."_ad_url_2",
					"std" => "http://www.woothemes.com",
					"type" => "text");

$options[] = array(	"name" => "Banner Ad #3 - Image Location",
					"desc" => "Enter the URL for this banner ad.",
					"id" => $shortname."_ad_image_3",
					"std" => "http://www.woothemes.com/ads/woothemes-125x125-3.gif",
					"type" => "text");
						
$options[] = array(	"name" => "Banner Ad #3 - Destination",
					"desc" => "Enter the URL where this banner ad points to.",
					"id" => $shortname."_ad_url_3",
					"std" => "http://www.woothemes.com",
					"type" => "text");

$options[] = array(	"name" => "Banner Ad #4 - Image Location",
					"desc" => "Enter the URL for this banner ad.",
					"id" => $shortname."_ad_image_4",
					"std" => "http://www.woothemes.com/ads/woothemes-125x125-4.gif",
					"type" => "text");
						
$options[] = array(	"name" => "Banner Ad #4 - Destination",
					"desc" => "Enter the URL where this banner ad points to.",
					"id" => $shortname."_ad_url_4",
					"std" => "http://www.woothemes.com",
					"type" => "text");

?>