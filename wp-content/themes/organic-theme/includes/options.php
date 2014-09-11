<?php
$options = array (

array(    "name" => "Organic by <a href='http://www.theforge.co.za' target='_blank'>The Forge Web Creations</a>",
        "type" => "title"),

array(    "type" => "open"),

array(    "name" => "",
		  "type" => "logos"),

array(    "name" => "Theme Logo:<br />",
        "desc" => "",
        "id" => $shortname."_theme_logo",
        "std" => "",
        "type" => "select",
		"options" => $diff_logos),	
		
array(    "name" => "Theme Colour:<br />",
        "desc" => "",
        "id" => $shortname."_theme_style",
        "std" => "",
        "type" => "select",
		"options" => $diff_stylesheets),		
		
array(  "name" => "Admin Link in Footer?",
        "desc" => "",
        "id" => $shortname."_theme_admin",
        "type" => "checkbox",
        "std" => ""),	
		
array(  "name" => "Social Links for Posts?",
        "desc" => "",
        "id" => $shortname."_theme_social",
        "type" => "checkbox",
        "std" => ""), 	
		
array(  "name" => "Sidebar Tabbed Content?",
        "desc" => "",
        "id" => $shortname."_theme_tabs",
        "type" => "checkbox",
        "std" => ""),			

array(    "type" => "close"),

array(    "name" => "Top Navigation Page Setup",
        "type" => "title"),

array(    "type" => "open"),

array(    "name" => "Before you set up your navigation, please make sure that you have created all the pages you require, you can see your pages by <a href=\"edit.php\">clicking here</a>. <br /><br />Once your pages are created, please select your pages from the drop down menus below and type a short description of each page. Your pages will only appear on your blog once you have added a description.",
        "desc" => "",
        "id" => "",
        "std" => "",
        "type" => "info"),

/* Page 1 Setup */

array(    "name" => "Page 1:",
        "desc" => "",
        "id" => $shortname."_theme_page_one_title",
        "std" => "",
        "type" => "selecter",
		"options" => $organic_pages),
array(    "name" => "Page 1 Description:",
        "desc" => "",
        "id" => $shortname."_theme_page_one_desc",
        "std" => "",
        "type" => "texterend"),
/* END */

/* Page 2 Setup */

array(    "name" => "Page 2:",
        "desc" => "",
        "id" => $shortname."_theme_page_two_title",
        "std" => "",
        "type" => "selecter",
		"options" => $organic_pages),
array(    "name" => "Page 2 Description:",
        "desc" => "",
        "id" => $shortname."_theme_page_two_desc",
        "std" => "",
        "type" => "texterend"),
/* END */

/* Page 3 Setup */

array(    "name" => "Page 3:",
        "desc" => "",
        "id" => $shortname."_theme_page_three_title",
        "std" => "",
        "type" => "selecter",
		"options" => $organic_pages),
array(    "name" => "Page 3 Description:",
        "desc" => "",
        "id" => $shortname."_theme_page_three_desc",
        "std" => "",
        "type" => "texterend"),
/* END */

/* Page 4 Setup */

array(    "name" => "Page 4:",
        "desc" => "",
        "id" => $shortname."_theme_page_four_title",
        "std" => "",
        "type" => "selecter",
		"options" => $organic_pages),
array(    "name" => "Page 4 Description:",
        "desc" => "",
        "id" => $shortname."_theme_page_four_desc",
        "std" => "",
        "type" => "texterend"),
/* END */

/* Page 5 Setup */

array(    "name" => "Page 5:",
        "desc" => "",
        "id" => $shortname."_theme_page_five_title",
        "std" => "",
        "type" => "selecter",
		"options" => $organic_pages),
array(    "name" => "Page 5 Description:",
        "desc" => "",
        "id" => $shortname."_theme_page_five_desc",
        "std" => "",
        "type" => "texterend"),
/* END */

array(    "type" => "close"),

array(    "name" => "Social Groups",
        "type" => "title"),

array(    "type" => "open"),

/* SOCIAL GROUPS */

array(    "name" => "Instructions:<br /><br />You may choose to use 5 social groups for your theme. Please note that if you add more than 5, only the first 5 will be used. Leaving the field blank will exclude the social group.",
        "desc" => "",
        "id" => "",
        "std" => "",
        "type" => "info2"),

array(    "name" => "Facebook:",
        "desc" => "",
        "id" => $shortname."_facebook",
        "std" => "",
        "type" => "texter"),

array(    "name" => "Twitter:",
        "desc" => "",
        "id" => $shortname."_twitter",
        "std" => "",
        "type" => "texter"),

array(    "name" => "FriendFeed:",
        "desc" => "",
        "id" => $shortname."_friendfeed",
        "std" => "",
        "type" => "texter"),

array(    "name" => "Flickr:",
        "desc" => "",
        "id" => $shortname."_flickr",
        "std" => "",
        "type" => "texter"),

array(    "name" => "Linked In:",
        "desc" => "",
        "id" => $shortname."_linkedin",
        "std" => "",
        "type" => "texter"),
		
array(    "name" => "Last.fm:",
        "desc" => "",
        "id" => $shortname."_lastfm",
        "std" => "",
        "type" => "texter"),
		
array(    "name" => "YouTube:",
        "desc" => "",
        "id" => $shortname."_youtube",
        "std" => "",
        "type" => "texter"),				



/* END SOCIAL GROUPS */

array(    "type" => "close"),

array(    "name" => "Statistics Tracking",
        "type" => "title"),

array(    "type" => "open"),

array(    "name" => "Feedburner URL:",
        "desc" => "If you use FeedBurner, you can put your URL above.<br />Eg. <a href='http://feeds2.feedburner.com/theforgeweb' target='_blank'>http://feeds2.feedburner.com/theforgeweb</a>",
        "id" => $shortname."_feedburner",
        "std" => "",
        "type" => "texter"),

array(    "name" => "Google Analytics Code:",
        "desc" => "If you use Google Analytics, you can put your tracking code above.<br /> Please note: This is the <strong>whole</strong> snippet of Javascript code that Google gives you, not just your unique number!",
        "id" => $shortname."_analytics",
        "std" => "",
        "type" => "textarea"),

array(    "type" => "close")

);
?>
