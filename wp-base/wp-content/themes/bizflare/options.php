<?php
/**
 * A unique identifier is defined to store the options in the database and reference them from the theme.
 * By default it uses the theme name, in lowercase and without spaces, but this can be changed if needed.
 * If the identifier changes, it'll appear as if the options have been reset.
 * 
 */

function optionsframework_option_name() {
	// This gets the theme name from the stylesheet (lowercase and without spaces)
	$themename = 'BizFlare';
	
	$optionsframework_settings = get_option('optionsframework');
	$optionsframework_settings['id'] = $themename;
	update_option('optionsframework', $optionsframework_settings);
}

/**
 * Defines an array of options that will be used to generate the settings page and be saved in the database.
 * When creating the "id" fields, make sure to use all lowercase and no spaces.
 *  
 */

function optionsframework_options() {
	
	// Test data
	$magpro_slider_start = array("false" => __("No", 'BizFlare' ),"true" => __("Yes", 'BizFlare' ));
	
	// Pull all the categories into an array
	$options_categories = array();  
	$options_categories_obj = get_categories();
	foreach ($options_categories_obj as $category) {
    	$options_categories[$category->cat_ID] = $category->cat_name;
	}
	
	// Pull all the pages into an array
	$options_pages = array();  
	$options_pages_obj = get_pages('sort_column=post_parent,menu_order');
	$options_pages[''] = __('Select a page:', 'BizFlare' );
	foreach ($options_pages_obj as $page) {
    	$options_pages[$page->ID] = $page->post_title;
	}
		
	// If using image radio buttons, define a directory path
	$imagepath =  get_template_directory_uri(). '/admin/images/';
		
	$options = array();
		
		
							
	$options[] = array( "name" => "country1",
						"type" => "innertabopen");	

		$options[] = array( "name" => __("Select a Skin", 'BizFlare' ),
							"type" => "groupcontaineropen");	

				$options[] = array( "name" => __("Select a Skin", 'BizFlare' ),
										"desc" => __("If you are not using child theme, selecting child theme will be same as using BizFlare skin. If you are using child theme, then lite.css from the child theme will be used.", 'BizFlare' ),
										"id" => "skin_style",
										"type" => "images",
										"std" => "verve",
										"options" => array(
											'verve' => $imagepath . 'verve.png',
											'radi' => $imagepath . 'radi.png',
											'green' => $imagepath . 'green.png',
											'yellow' => $imagepath . 'yellow.png',
											'blue' => $imagepath . 'blue.png',
											'purple' => $imagepath . 'purple.png',
											'grunge' => $imagepath . 'grunge.png',
											'child' => $imagepath . 'child.png')
										);						

										
		$options[] = array( "type" => "groupcontainerclose");
		
		$options[] = array( "name" => __("Single Post Settings", 'BizFlare' ),
							"type" => "groupcontaineropen");
							
					$options[] = array( "name" => __("Show Featured Image?", 'BizFlare' ),
										"desc" => __("Select yes if you want to show featured image.", 'BizFlare' ),
										"id" => "show_featured_image_single",
										"std" => "false",
										"type" => "select",
										"class" => "mini", //mini, tiny, small
										"options" => $magpro_slider_start);	
										
					$options[] = array( "name" => __("Show Ratings?", 'BizFlare' ),
										"desc" => __("Select yes if you want to show ratings under post title.", 'BizFlare' ),
										"id" => "show_rat_on_single",
										"std" => "true",
										"type" => "select",
										"class" => "mini", //mini, tiny, small
										"options" => $magpro_slider_start);										
										
					$options[] = array( "name" => __("Show Posted by and Date?", 'BizFlare' ),
										"desc" => __("Select yes if you want to show Posted by and Date under post title.", 'BizFlare' ),
										"id" => "show_pd_on_single",
										"std" => "true",
										"type" => "select",
										"class" => "mini", //mini, tiny, small
										"options" => $magpro_slider_start);											
										
					$options[] = array( "name" => __("Show Categories and Tags?", 'BizFlare' ),
										"desc" => __("Select yes if you want to show categories under post title.", 'BizFlare' ),
										"id" => "show_cats_on_single",
										"std" => "true",
										"type" => "select",
										"class" => "mini", //mini, tiny, small
										"options" => $magpro_slider_start);	
										
					$options[] = array( "name" => __("Show Next/Previous Box", 'BizFlare' ),
										"desc" => __("Select yes if you want to show Next/Previous box on single post page.", 'BizFlare' ),
										"id" => "show_np_box",
										"std" => "true",
										"type" => "select",
										"class" => "mini", //mini, tiny, small
										"options" => $magpro_slider_start);	
																																							
										
		$options[] = array( "type" => "groupcontainerclose");						
		
		
		
	$options[] = array( "type" => "innertabclose");	


	$options[] = array( "name" => "country2",
						"type" => "innertabopen");	
						
		$options[] = array( "name" => __("Social Settings", 'BizFlare' ),
							"type" => "groupcontaineropen");	

					$options[] = array( "name" => __("Twitter", 'BizFlare' ),
										"desc" => __("Enter your twitter id", 'BizFlare' ),
										"id" => "twitter_id",
										"std" => "",
										"type" => "text");

					$options[] = array( "name" => __("Redditt", 'BizFlare' ),
										"desc" => __("Enter your reddit url", 'BizFlare' ),
										"id" => "redit_id",
										"std" => "",
										"type" => "text");

					$options[] = array( "name" => __("Facebook", 'BizFlare' ),
										"desc" => __("Enter your facebook url", 'BizFlare' ),
										"id" => "facebook_id",
										"std" => "",
										"type" => "text");

					$options[] = array( "name" => __("Stumble", 'BizFlare' ),
										"desc" => __("Enter your stumbleupon url", 'BizFlare' ),
										"id" => "stumble_id",
										"std" => "",
										"type" => "text");

					$options[] = array( "name" => __("Flickr", 'BizFlare' ),
										"desc" => __("Enter your flickr url", 'BizFlare' ),
										"id" => "flickr_id",
										"std" => "",
										"type" => "text");

					$options[] = array( "name" => __("LinkedIn", 'BizFlare' ),
										"desc" => __("Enter your linkedin url", 'BizFlare' ),
										"id" => "linkedin_id",
										"std" => "",
										"type" => "text");

					$options[] = array( "name" => __("Google", 'BizFlare' ),
										"desc" => __("Enter your google url", 'BizFlare' ),
										"id" => "google_id",
										"std" => "",
										"type" => "text");

							
		$options[] = array( "type" => "groupcontainerclose");											
														
	$options[] = array( "type" => "innertabclose");

	$options[] = array( "name" => "country10",
						"type" => "innertabopen");
						
		$options[] = array( "name" => __("Logo Section Settings", 'BizFlare' ),
							"type" => "tabheading");
							
		$options[] = array( "name" => __("Logo Upload", 'BizFlare' ),
							"type" => "groupcontaineropen");	
					
				$options[] = array( "name" => __("Upload Logo", 'BizFlare' ),
										"desc" => __("Upload your logo here.", 'BizFlare' ),
										"id" => "logo_layout_style",
										"type" => "proupgrade",
										);														
										
		$options[] = array( "type" => "groupcontainerclose");							
		
		$options[] = array( "name" => __("Logo Section Layout", 'BizFlare' ),
							"type" => "groupcontaineropen");	

					
				$options[] = array( "name" => __("Select a layout", 'BizFlare' ),
										"desc" => __("Images for logo section.", 'BizFlare' ),
										"id" => "logo_layout_style",
										"type" => "images",
										"std" => "sbys",
										"options" => array(
											'sbys' => $imagepath . 'logo1.png',
											'onebone' => $imagepath . 'logo2.png')
										);														

										
		$options[] = array( "type" => "groupcontainerclose");								
						
	$options[] = array( "type" => "innertabclose");	
	
	$options[] = array( "name" => "country3",
						"type" => "innertabopen");	
		
		$options[] = array( "name" => __("Header On/Off Settings", 'BizFlare' ),
							"type" => "groupcontaineropen");	

					
					$options[] = array( "name" => __("Show Header on homepage", 'BizFlare' ),
										"desc" => __("Select yes if you want to show Header on homepage.", 'BizFlare' ),
										"id" => "show_slider_home",
										"std" => "true",
										"type" => "select",
										"class" => "mini", //mini, tiny, small
										"options" => $magpro_slider_start);
										
					$options[] = array( "name" => __("Show Header on Single post page", 'BizFlare' ),
										"desc" => __("Select yes if you want to show Header on Single post page.", 'BizFlare' ),
										"id" => "show_slider_single",
										"std" => "false",
										"type" => "select",
										"class" => "mini", //mini, tiny, small
										"options" => $magpro_slider_start);	
										
					$options[] = array( "name" => __("Show Header on Pages", 'BizFlare' ),
										"desc" => __("Select yes if you want to show Header on Pages.", 'BizFlare' ),
										"id" => "show_slider_page",
										"std" => "false",
										"type" => "select",
										"class" => "mini", //mini, tiny, small
										"options" => $magpro_slider_start);	
										
					$options[] = array( "name" => __("Show Header on Category Pages", 'BizFlare' ),
										"desc" => __("Select yes if you want to show Header on Category Pages.", 'BizFlare' ),
										"id" => "show_slider_archive",
										"std" => "false",
										"type" => "select",
										"class" => "mini", //mini, tiny, small
										"options" => $magpro_slider_start);																														
										
		$options[] = array( "type" => "groupcontainerclose");
		
		$options[] = array( "name" => __("Header's/Slider's Available in PRO Version", 'BizFlare' ),
							"type" => "groupcontaineropen");	

					$options[] = array( "name" => __("Following header's/slider's are available in PRO version", 'BizFlare' ),
										"desc" => __("Upgrade to PRO version for above header's/Slider's", 'BizFlare' ),
										"id" => "header_slider",
										"std" => "one",
										"type" => "proimages",
										"std" => "one",
										"options" => array(
											'one' => $imagepath . 'slider1.png',
											'videoone' => $imagepath . 'video.png',
											'oneplus' => $imagepath . 'oneplus.png',
											'slidertwo' => $imagepath . 'slidertwo.png',
											'slit' => $imagepath . 'slit.png',
											'fraction' => $imagepath . 'fraction.png',
											'hero' => $imagepath . 'hero.png')
										);					

		$options[] = array( "type" => "groupcontainerclose");						
						
	$options[] = array( "type" => "innertabclose");	
	
	$options[] = array( "name" => "country4",
						"type" => "innertabopen");
						
		$options[] = array( "name" => __("Layout Settings", 'BizFlare' ),
							"type" => "groupcontaineropen");	

					$options[] = array( "name" => __("Select a homepage layout", 'BizFlare' ),
										"desc" => __("Images for layout.", 'BizFlare' ),
										"id" => "homepage_layout",
										"std" => "bone",
										"type" => "images",
										"options" => array(
											'bone' => $imagepath . 'layout1.png',
											'bsix' => $imagepath . 'bsix.png',
											'spage' => $imagepath . 'layout2.png')
										);					

										
		$options[] = array( "type" => "groupcontainerclose");
		
		$options[] = array( "name" => __("Layouts available in PRO Version", 'BizFlare' ),
							"type" => "groupcontaineropen");	

					$options[] = array( "name" => __("Following layout's are available in PRO version", 'BizFlare' ),
										"desc" => __("Upgrade to PRO version for above layouts", 'BizFlare' ),
										"id" => "homepage_layout",
										"std" => "bone",
										"type" => "proimages",
										"options" => array(
											'bone' => $imagepath . 'layout1.png',
											'btwo' => $imagepath . 'layout3.png',
											'boneplus' => $imagepath . 'boneplus.png',
											'bthree' => $imagepath . 'bthree.png',
											'bfour' => $imagepath . 'bfour.png',
											'bfive' => $imagepath . 'bfive.png',
											'bsix' => $imagepath . 'bsix.png',
											'bseven' => $imagepath . 'bseven.png',
											'beight' => $imagepath . 'beight.png',
											'bnine' => $imagepath . 'bnine.png')
										);					

										
		$options[] = array( "type" => "groupcontainerclose");	
		
		$options[] = array( "name" => __("Quote Settings", 'BizFlare' ),
							"type" => "groupcontaineropen");	

					$options[] = array( "name" => __("Show Quote?", 'BizFlare' ),
										"desc" => __("Enter the welcome text", 'BizFlare' ),
										"id" => "show_quote",
										"type" => "proupgrade");	
										
					$options[] = array( "name" => __("Quote 1", 'BizFlare' ),
										"desc" => __("Enter the quote text", 'BizFlare' ),
										"id" => "show_quote1",
										"type" => "proupgrade");														

					$options[] = array( "name" => __("Customer 1", 'BizFlare' ),
										"desc" => __("Enter the customer name", 'BizFlare' ),
										"id" => "show_quote1_cust",
										"type" => "proupgrade");
										
					$options[] = array( "name" => __("Quote 2", 'BizFlare' ),
										"desc" => __("Enter the quote text", 'BizFlare' ),
										"id" => "show_quote2",
										"type" => "proupgrade");														

					$options[] = array( "name" => __("Customer 2", 'BizFlare' ),
										"desc" => __("Enter the customer name", 'BizFlare' ),
										"id" => "show_quote2_cust",
										"type" => "proupgrade");
										
					$options[] = array( "name" => __("Quote 3", 'BizFlare' ),
										"desc" => __("Enter the quote text", 'BizFlare' ),
										"id" => "show_quote3",
										"type" => "proupgrade");														

					$options[] = array( "name" => __("Customer 3", 'BizFlare' ),
										"desc" => __("Enter the customer name", 'BizFlare' ),
										"id" => "show_quote3_cust",
										"type" => "proupgrade");																				
										
		$options[] = array( "type" => "groupcontainerclose");
		
		$options[] = array( "name" => __("Client Logos", 'BizFlare' ),
							"type" => "groupcontaineropen");	

					$options[] = array( "name" => __("Show Client Logo Section?", 'BizFlare' ),
										"desc" => __("Select yes if you want to show client logos.", 'BizFlare' ),
										"id" => "show_quote",
										"type" => "proupgrade");	
										
					$options[] = array( "name" => __("Client Logo # 1", 'BizFlare' ),
										"desc" => __("upload the logo", 'BizFlare' ),
										"id" => "client_logo1",
										"type" => "proupgrade");														

					$options[] = array( "name" => __("Client Logo # 2", 'BizFlare' ),
										"desc" => __("upload the logo", 'BizFlare' ),
										"id" => "client_logo2",
										"type" => "proupgrade");
										
					$options[] = array( "name" => __("Client Logo # 3", 'BizFlare' ),
										"desc" => __("upload the logo", 'BizFlare' ),
										"id" => "client_logo3",
										"type" => "proupgrade");														

					$options[] = array( "name" => __("Client Logo # 4", 'BizFlare' ),
										"desc" => __("upload the logo", 'BizFlare' ),
										"id" => "client_logo4",
										"type" => "proupgrade");
										
		$options[] = array( "type" => "groupcontainerclose");							
						
	$options[] = array( "type" => "innertabclose");	
	
	$options[] = array( "name" => "country5",
						"type" => "innertabopen");
						
		$options[] = array( "name" => __("Biz One Settings", 'BizFlare' ),
							"type" => "tabheading");
																							
						
		$options[] = array( "name" => __("Welcome Section", 'BizFlare' ),
							"type" => "groupcontaineropen");	

					$options[] = array( "name" => __("Headline", 'BizFlare' ),
										"desc" => __("Enter the headline", 'BizFlare' ),
										"id" => "welcome_headline",
										"type" => "text");		
										
					$options[] = array( "name" => __("Welcome text", 'BizFlare' ),
										"desc" => __("Enter the welcome text", 'BizFlare' ),
										"id" => "welcome_text",
										"type" => "textarea");														

										
		$options[] = array( "type" => "groupcontainerclose");	
		
		$options[] = array( "name" => __("Left Section", 'BizFlare' ),
							"type" => "groupcontaineropen");	

					$options[] = array( "name" => __("Upload Image", 'BizFlare' ),
										"desc" => __("Upload your image here.", 'BizFlare' ),
										"id" => "left_section_image",
										"type" => "upload");					
					
					$options[] = array( "name" => __("Headline", 'BizFlare' ),
										"desc" => __("Enter the headline", 'BizFlare' ),
										"id" => "left_section_headline",
										"type" => "text");		
										
					$options[] = array( "name" => __("Welcome text", 'BizFlare' ),
										"desc" => __("Enter the welcome text", 'BizFlare' ),
										"id" => "left_section_text",
										"type" => "textarea");
										
					$options[] = array( "name" => __("Link", 'BizFlare' ),
										"desc" => __("Enter the link to product or service", 'BizFlare' ),
										"id" => "left_section_link",
										"type" => "text");																							

										
		$options[] = array( "type" => "groupcontainerclose");	
		
		$options[] = array( "name" => __("Center Section", 'BizFlare' ),
							"type" => "groupcontaineropen");	

					$options[] = array( "name" => __("Upload Image", 'BizFlare' ),
										"desc" => __("Upload your image here.", 'BizFlare' ),
										"id" => "center_section_image",
										"type" => "upload");					
					
					$options[] = array( "name" => __("Headline", 'BizFlare' ),
										"desc" => __("Enter the headline", 'BizFlare' ),
										"id" => "center_section_headline",
										"type" => "text");		
										
					$options[] = array( "name" => __("Welcome text", 'BizFlare' ),
										"desc" => __("Enter the welcome text", 'BizFlare' ),
										"id" => "center_section_text",
										"type" => "textarea");	
										
					$options[] = array( "name" => __("Link", 'BizFlare' ),
										"desc" => __("Enter the link to product or service", 'BizFlare' ),
										"id" => "center_section_link",
										"type" => "text");																							

										
		$options[] = array( "type" => "groupcontainerclose");	
		
		$options[] = array( "name" => __("Right Section", 'BizFlare' ),
							"type" => "groupcontaineropen");	

					$options[] = array( "name" => __("Upload Image", 'BizFlare' ),
										"desc" => __("Upload your image here.", 'BizFlare' ),
										"id" => "right_section_image",
										"type" => "upload");					
					
					$options[] = array( "name" => __("Headline", 'BizFlare' ),
										"desc" => __("Enter the headline", 'BizFlare' ),
										"id" => "right_section_headline",
										"type" => "text");		
										
					$options[] = array( "name" => __("Welcome text", 'BizFlare' ),
										"desc" => __("Enter the welcome text", 'BizFlare' ),
										"id" => "right_section_text",
										"type" => "textarea");
										
					$options[] = array( "name" => __("Link", 'BizFlare' ),
										"desc" => __("Enter the link to product or service", 'BizFlare' ),
										"id" => "right_section_link",
										"type" => "text");																								

										
		$options[] = array( "type" => "groupcontainerclose");
		
		$options[] = array( "name" => __("Quote Section", 'BizFlare' ),
							"type" => "groupcontaineropen");
							
					$options[] = array( "name" => __("Show Quote?", 'BizFlare' ),
										"desc" => __("Select yes if you want to show quote.", 'BizFlare' ),
										"id" => "show_BizFlare_quote_bizone",
										"std" => "true",
										"type" => "select",
										"class" => "mini", //mini, tiny, small
										"options" => $magpro_slider_start);								
					
					$options[] = array( "name" => __("Quote", 'BizFlare' ),
										"desc" => __("Enter the Quote Text", 'BizFlare' ),
										"id" => "quote_section_text",
										"type" => "textarea");		
										
					$options[] = array( "name" => __("Customer Name", 'BizFlare' ),
										"desc" => __("Enter the customer name", 'BizFlare' ),
										"id" => "quote_section_name",
										"type" => "text");														

										
		$options[] = array( "type" => "groupcontainerclose");	
		
		$options[] = array( "name" => __("Recent Posts", 'BizFlare' ),
							"type" => "groupcontaineropen");
														
					$options[] = array( "name" => __("Show Recent Posts Section?", 'BizFlare' ),
										"desc" => __("Select yes if you want to recent posts at the bottom.", 'BizFlare' ),
										"id" => "show_bizone_posts",
										"std" => "true",
										"type" => "select",
										"class" => "mini", //mini, tiny, small
										"options" => $magpro_slider_start);	
										
		$options[] = array( "type" => "groupcontainerclose");														
						
	$options[] = array( "type" => "innertabclose");
	
	$options[] = array( "name" => "country7",
						"type" => "innertabopen");

		$options[] = array( "name" => __("Biz Six Settings", 'BizFlare' ),
							"type" => "tabheading");
																							
						
		$options[] = array( "name" => __("Welcome Section", 'BizFlare' ),
							"type" => "groupcontaineropen");	

					$options[] = array( "name" => __("Headline", 'BizFlare' ),
										"desc" => __("Enter the headline", 'BizFlare' ),
										"id" => "welcome_headline_bizsix",
										"type" => "text");		
										
					$options[] = array( "name" => __("Welcome text", 'BizFlare' ),
										"desc" => __("Enter the welcome text", 'BizFlare' ),
										"id" => "welcome_text_bizsix",
										"type" => "textarea");														

										
		$options[] = array( "type" => "groupcontainerclose");	
		
		$options[] = array( "name" => __("Top Section", 'BizFlare' ),
							"type" => "groupcontaineropen");	

					$options[] = array( "name" => __("Upload Image", 'BizFlare' ),
										"desc" => __("Upload your image here.", 'BizFlare' ),
										"id" => "top_section_image_bizsix",
										"type" => "upload");					
					
					$options[] = array( "name" => __("Headline", 'BizFlare' ),
										"desc" => __("Enter the headline", 'BizFlare' ),
										"id" => "top_section_headline_bizsix",
										"type" => "text");		
										
					$options[] = array( "name" => __("Welcome text", 'BizFlare' ),
										"desc" => __("Enter the welcome text", 'BizFlare' ),
										"id" => "top_section_text_bizsix",
										"type" => "textarea");
										
					$options[] = array( "name" => __("Link", 'BizFlare' ),
										"desc" => __("Enter the link to product or service", 'BizFlare' ),
										"id" => "top_section_link_bizsix",
										"type" => "text");																							

										
		$options[] = array( "type" => "groupcontainerclose");	
		
		$options[] = array( "name" => __("Middle Section", 'BizFlare' ),
							"type" => "groupcontaineropen");	

					$options[] = array( "name" => __("Upload Image", 'BizFlare' ),
										"desc" => __("Upload your image here.", 'BizFlare' ),
										"id" => "center_section_image_bizsix",
										"type" => "upload");					
					
					$options[] = array( "name" => __("Headline", 'BizFlare' ),
										"desc" => __("Enter the headline", 'BizFlare' ),
										"id" => "center_section_headline_bizsix",
										"type" => "text");		
										
					$options[] = array( "name" => __("Welcome text", 'BizFlare' ),
										"desc" => __("Enter the welcome text", 'BizFlare' ),
										"id" => "center_section_text_bizsix",
										"type" => "textarea");	
										
					$options[] = array( "name" => __("Link", 'BizFlare' ),
										"desc" => __("Enter the link to product or service", 'BizFlare' ),
										"id" => "center_section_link_bizsix",
										"type" => "text");																							

										
		$options[] = array( "type" => "groupcontainerclose");	
		
		$options[] = array( "name" => __("Bottom Section", 'BizFlare' ),
							"type" => "groupcontaineropen");	

					$options[] = array( "name" => __("Upload Image", 'BizFlare' ),
										"desc" => __("Upload your image here.", 'BizFlare' ),
										"id" => "bottom_section_image_bizsix",
										"type" => "upload");					
					
					$options[] = array( "name" => __("Headline", 'BizFlare' ),
										"desc" => __("Enter the headline", 'BizFlare' ),
										"id" => "bottom_section_headline_bizsix",
										"type" => "text");		
										
					$options[] = array( "name" => __("Welcome text", 'BizFlare' ),
										"desc" => __("Enter the welcome text", 'BizFlare' ),
										"id" => "bottom_section_text_bizsix",
										"type" => "textarea");
										
					$options[] = array( "name" => __("Link", 'BizFlare' ),
										"desc" => __("Enter the link to product or service", 'BizFlare' ),
										"id" => "bottom_section_link_bizsix",
										"type" => "text");																								

										
		$options[] = array( "type" => "groupcontainerclose");
		
		$options[] = array( "name" => __("Recent Posts", 'BizFlare' ),
							"type" => "groupcontaineropen");
														
					$options[] = array( "name" => __("Show Recent Posts Section?", 'BizFlare' ),
										"desc" => __("Select yes if you want to recent posts at the bottom.", 'BizFlare' ),
										"id" => "show_posts_bizsix",
										"std" => "true",
										"type" => "select",
										"class" => "mini", //mini, tiny, small
										"options" => $magpro_slider_start);	
										
		$options[] = array( "type" => "groupcontainerclose");																						
						
	$options[] = array( "type" => "innertabclose");			
	
	$options[] = array( "name" => "country9",
						"type" => "innertabopen");
						
		$options[] = array( "name" => __("Standard Page Settings", 'BizFlare' ),
							"type" => "groupcontaineropen");	

					$options[] = array( "name" => __("Show Comments?", 'BizFlare' ),
										"desc" => __("Select yes if you want to show comments", 'BizFlare' ),
										"id" => "show_comments_spage",
										"std" => "true",
										"type" => "select",
										"class" => "mini", //mini, tiny, small
										"options" => $magpro_slider_start);		
										
		$options[] = array( "type" => "groupcontainerclose");								
						
	$options[] = array( "type" => "innertabclose");	

	$options[] = array( "name" => "country19",
						"type" => "innertabopen");
						
		$options[] = array( "name" => __("Layouts available in PRO Version", 'BizFlare' ),
							"type" => "groupcontaineropen");	

					$options[] = array( "name" => __("Portfolio layout's are available in PRO version", 'BizFlare' ),
										"desc" => __("Upgrade to PRO version for above layouts", 'BizFlare' ),
										"id" => "portfolio_layout",
										"std" => "pone",
										"type" => "proimages",
										"options" => array(
											'pone' => $imagepath . 'pone.png',
											'ptwo' => $imagepath . 'ptwo.png',
											'pthree' => $imagepath . 'pthree.png',
											'pfour' => $imagepath . 'pfour.png')
										);					

										
		$options[] = array( "type" => "groupcontainerclose");						
						
	$options[] = array( "type" => "innertabclose");
								
	$options[] = array( "name" => "country11",
						"type" => "innertabopen");
						
		$options[] = array( "name" => __("Footer Settings", 'BizFlare' ),
							"type" => "tabheading");
							
		$options[] = array( "name" => __("Social Section", 'BizFlare' ),
							"type" => "groupcontaineropen");	
					
				$options[] = array( "name" => __("Show social Section?", 'BizFlare' ),
										"desc" => __("Select yes if you want to show social section.", 'BizFlare' ),
										"id" => "show_social_section",
										"type" => "proupgrade",
										);														
										
		$options[] = array( "type" => "groupcontainerclose");	
		
		$options[] = array( "name" => __("Footer Logo Upload", 'BizFlare' ),
							"type" => "groupcontaineropen");	
					
				$options[] = array( "name" => __("Upload Logo", 'BizFlare' ),
										"desc" => __("Upload your logo here.", 'BizFlare' ),
										"id" => "footer_logo_upload",
										"type" => "proupgrade",
										);														
										
		$options[] = array( "type" => "groupcontainerclose");	
		
		$options[] = array( "name" => __("Address Settings", 'BizFlare' ),
							"type" => "groupcontaineropen");	
					
				$options[] = array( "name" => __("Show Search?", 'BizFlare' ),
										"desc" => __("Select yes if you want to show search.", 'BizFlare' ),
										"id" => "show_foote_search",
										"type" => "proupgrade",
										);	
										
				$options[] = array( "name" => __("Address", 'BizFlare' ),
										"desc" => __("Enter Address", 'BizFlare' ),
										"id" => "footer_address",
										"type" => "proupgrade",
										);	
										
				$options[] = array( "name" => __("Email", 'BizFlare' ),
										"desc" => __("Enter Email Address", 'BizFlare' ),
										"id" => "footer_email_address",
										"type" => "proupgrade",
										);
										
				$options[] = array( "name" => __("Phone Number", 'BizFlare' ),
										"desc" => __("Enter Phone Number", 'BizFlare' ),
										"id" => "footer_phone",
										"type" => "proupgrade",
										);
										
				$options[] = array( "name" => __("Skype", 'BizFlare' ),
										"desc" => __("Enter Skype Address", 'BizFlare' ),
										"id" => "footer_skype_address",
										"type" => "proupgrade",
										);
										
				$options[] = array( "name" => __("Google Map", 'BizFlare' ),
										"desc" => __("Enter google map", 'BizFlare' ),
										"id" => "footer_map_address",
										"type" => "proupgrade",
										);																																																														
										
		$options[] = array( "type" => "groupcontainerclose");											
										
		$options[] = array( "name" => __("Footer Layouts", 'BizFlare' ),
							"type" => "groupcontaineropen");	

					$options[] = array( "name" => __("Select a footer layout", 'BizFlare' ),
										"desc" => __("Images for layout.", 'BizFlare' ),
										"id" => "footer_layout",
										"std" => "one",
										"type" => "images",
										"std" => "one",
										"options" => array(
											'one' => $imagepath . 'footer1.png',
											'two' => $imagepath . 'footer2.png')
										);	
										
		$options[] = array( "type" => "groupcontainerclose");
		
		$options[] = array( "name" => __("Footer Layouts available in PRO Version", 'BizFlare' ),
							"type" => "groupcontaineropen");	

					$options[] = array( "name" => __("Following layout's are available in PRO version", 'BizFlare' ),
										"desc" => __("Upgrade to PRO version for above layouts", 'BizFlare' ),
										"id" => "homepage_layout",
										"std" => "fone",
										"type" => "proimages",
										"options" => array(
											'fthree' => $imagepath . 'fthree.png',
											'ffour' => $imagepath . 'ffour.png',
											'ffive' => $imagepath . 'ffive.png',
											'fsix' => $imagepath . 'fsix.png')
										);					
										
		$options[] = array( "type" => "groupcontainerclose");																							
						
	$options[] = array( "type" => "innertabclose");			
							
						

							
		
	return $options;
}