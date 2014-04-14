<?php
/**
 * A unique identifier is defined to store the options in the database and reference them from the theme.
 * By default it uses the theme name, in lowercase and without spaces, but this can be changed if needed.
 * If the identifier changes, it'll appear as if the options have been reset.
 * 
 */

function optionsframework_option_name() {
	// This gets the theme name from the stylesheet (lowercase and without spaces)
	$themename = 'Bizantine';
	
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
	$magpro_slider_start = array("false" => __("No", 'Bizantine' ),"true" => __("Yes", 'Bizantine' ));
	
	// Pull all the categories into an array
	$options_categories = array();  
	$options_categories_obj = get_categories();
	foreach ($options_categories_obj as $category) {
    	$options_categories[$category->cat_ID] = $category->cat_name;
	}
	
	// Pull all the pages into an array
	$options_pages = array();  
	$options_pages_obj = get_pages('sort_column=post_parent,menu_order');
	$options_pages[''] = __('Select a page:', 'Bizantine' );
	foreach ($options_pages_obj as $page) {
    	$options_pages[$page->ID] = $page->post_title;
	}
		
	// If using image radio buttons, define a directory path
	$imagepath =  get_template_directory_uri(). '/admin/images/';
		
	$options = array();
		
		
							
	$options[] = array( "name" => "country1",
						"type" => "innertabopen");	

		$options[] = array( "name" => __("Select a Skin", 'Bizantine' ),
							"type" => "groupcontaineropen");	

				$options[] = array( "name" => __("Select a Skin", 'Bizantine' ),
										"desc" => __("If you are not using child theme, selecting child theme will be same as using Bizantine skin. If you are using child theme, then lite.css from the child theme will be used.", 'Bizantine' ),
										"id" => "skin_style",
										"type" => "images",
										"std" => "bizantine",
										"options" => array(
											'bizantine' => $imagepath . 'bizantine.png',
											'radi' => $imagepath . 'radi.png',
											'blue' => $imagepath . 'blue.png',
											'purple' => $imagepath . 'purple.png',
											'yellow' => $imagepath . 'yellow.png',
											'green' => $imagepath . 'green.png',
											'aqua' => $imagepath . 'aqua.png',
											'grunge' => $imagepath . 'grunge.png',
											'child' => $imagepath . 'child.png')
										);						

										
		$options[] = array( "type" => "groupcontainerclose");
		
		$options[] = array( "name" => __("Single Post Settings", 'Bizantine' ),
							"type" => "groupcontaineropen");
							
					$options[] = array( "name" => __("Show Featured Image?", 'Bizantine' ),
										"desc" => __("Select yes if you want to show featured image.", 'Bizantine' ),
										"id" => "show_featured_image_single",
										"std" => "false",
										"type" => "select",
										"class" => "mini", //mini, tiny, small
										"options" => $magpro_slider_start);	
										
					$options[] = array( "name" => __("Show Ratings?", 'Bizantine' ),
										"desc" => __("Select yes if you want to show ratings under post title.", 'Bizantine' ),
										"id" => "show_rat_on_single",
										"std" => "true",
										"type" => "select",
										"class" => "mini", //mini, tiny, small
										"options" => $magpro_slider_start);										
										
					$options[] = array( "name" => __("Show Posted by and Date?", 'Bizantine' ),
										"desc" => __("Select yes if you want to show Posted by and Date under post title.", 'Bizantine' ),
										"id" => "show_pd_on_single",
										"std" => "true",
										"type" => "select",
										"class" => "mini", //mini, tiny, small
										"options" => $magpro_slider_start);											
										
					$options[] = array( "name" => __("Show Categories and Tags?", 'Bizantine' ),
										"desc" => __("Select yes if you want to show categories under post title.", 'Bizantine' ),
										"id" => "show_cats_on_single",
										"std" => "true",
										"type" => "select",
										"class" => "mini", //mini, tiny, small
										"options" => $magpro_slider_start);	
										
					$options[] = array( "name" => __("Show Next/Previous Box", 'Bizantine' ),
										"desc" => __("Select yes if you want to show Next/Previous box on single post page.", 'Bizantine' ),
										"id" => "show_np_box",
										"std" => "true",
										"type" => "select",
										"class" => "mini", //mini, tiny, small
										"options" => $magpro_slider_start);	
																																							
										
		$options[] = array( "type" => "groupcontainerclose");						
		
		
		
	$options[] = array( "type" => "innertabclose");	


	$options[] = array( "name" => "country2",
						"type" => "innertabopen");	
						
		$options[] = array( "name" => __("Social Settings", 'Bizantine' ),
							"type" => "groupcontaineropen");	

					$options[] = array( "name" => __("Twitter", 'Bizantine' ),
										"desc" => __("Enter your twitter id", 'Bizantine' ),
										"id" => "twitter_id",
										"std" => "",
										"type" => "text");

					$options[] = array( "name" => __("Redditt", 'Bizantine' ),
										"desc" => __("Enter your reddit url", 'Bizantine' ),
										"id" => "redit_id",
										"std" => "",
										"type" => "text");

					$options[] = array( "name" => __("Facebook", 'Bizantine' ),
										"desc" => __("Enter your facebook url", 'Bizantine' ),
										"id" => "facebook_id",
										"std" => "",
										"type" => "text");

					$options[] = array( "name" => __("Stumble", 'Bizantine' ),
										"desc" => __("Enter your stumbleupon url", 'Bizantine' ),
										"id" => "stumble_id",
										"std" => "",
										"type" => "text");

					$options[] = array( "name" => __("Flickr", 'Bizantine' ),
										"desc" => __("Enter your flickr url", 'Bizantine' ),
										"id" => "flickr_id",
										"std" => "",
										"type" => "text");

					$options[] = array( "name" => __("LinkedIn", 'Bizantine' ),
										"desc" => __("Enter your linkedin url", 'Bizantine' ),
										"id" => "linkedin_id",
										"std" => "",
										"type" => "text");

					$options[] = array( "name" => __("Google", 'Bizantine' ),
										"desc" => __("Enter your google url", 'Bizantine' ),
										"id" => "google_id",
										"std" => "",
										"type" => "text");

							
		$options[] = array( "type" => "groupcontainerclose");											
														
	$options[] = array( "type" => "innertabclose");

	$options[] = array( "name" => "country10",
						"type" => "innertabopen");
						
		$options[] = array( "name" => __("Logo Section Settings", 'Bizantine' ),
							"type" => "tabheading");
							
		$options[] = array( "name" => __("Logo Upload", 'Bizantine' ),
							"type" => "groupcontaineropen");	
					
				$options[] = array( "name" => __("Upload Logo", 'Bizantine' ),
										"desc" => __("Upload your logo here.", 'Bizantine' ),
										"id" => "logo_layout_upload",
										"type" => "proupgrade",
										);														
										
		$options[] = array( "type" => "groupcontainerclose");							
		
		$options[] = array( "name" => __("Logo Section Layout", 'Bizantine' ),
							"type" => "groupcontaineropen");	

					
				$options[] = array( "name" => __("Select a layout", 'Bizantine' ),
										"desc" => __("Images for logo section.", 'Bizantine' ),
										"id" => "logo_layout_style",
										"type" => "images",
										"std" => "onebone",
										"options" => array(
											'sbys' => $imagepath . 'logo1.png',
											'onebone' => $imagepath . 'logo2.png')
										);														

										
		$options[] = array( "type" => "groupcontainerclose");								
						
	$options[] = array( "type" => "innertabclose");	
	
	$options[] = array( "name" => "country3",
						"type" => "innertabopen");	

		$options[] = array( "name" => __("Header On/Off Settings", 'Bizantine' ),
							"type" => "groupcontaineropen");	
					
					$options[] = array( "name" => __("Show Header on homepage", 'Bizantine' ),
										"desc" => __("Select yes if you want to show Header on homepage.", 'Bizantine' ),
										"id" => "show_Bizantine_slider_home",
										"std" => "true",
										"type" => "select",
										"class" => "mini", //mini, tiny, small
										"options" => $magpro_slider_start);
										
					$options[] = array( "name" => __("Show Header on Single post page", 'Bizantine' ),
										"desc" => __("Select yes if you want to show Header on Single post page.", 'Bizantine' ),
										"id" => "show_Bizantine_slider_single",
										"std" => "false",
										"type" => "select",
										"class" => "mini", //mini, tiny, small
										"options" => $magpro_slider_start);	
										
					$options[] = array( "name" => __("Show Header on Pages", 'Bizantine' ),
										"desc" => __("Select yes if you want to show Header on Pages.", 'Bizantine' ),
										"id" => "show_Bizantine_slider_page",
										"std" => "false",
										"type" => "select",
										"class" => "mini", //mini, tiny, small
										"options" => $magpro_slider_start);	
										
					$options[] = array( "name" => __("Show Header on Category Pages", 'Bizantine' ),
										"desc" => __("Select yes if you want to show Header on Category Pages.", 'Bizantine' ),
										"id" => "show_Bizantine_slider_archive",
										"std" => "false",
										"type" => "select",
										"class" => "mini", //mini, tiny, small
										"options" => $magpro_slider_start);																														
										
		$options[] = array( "type" => "groupcontainerclose");
		
		$options[] = array( "name" => __("Header's/Slider's Available in PRO Version", 'Bizantine' ),
							"type" => "groupcontaineropen");	

					$options[] = array( "name" => __("Following header's/slider's are available in PRO version", 'Bizantine' ),
										"desc" => __("Upgrade to PRO version for above header's/Slider's", 'Bizantine' ),
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
						
		$options[] = array( "name" => __("Layout Settings", 'Bizantine' ),
							"type" => "groupcontaineropen");	

					$options[] = array( "name" => __("Select a homepage layout", 'Bizantine' ),
										"desc" => __("Images for layout.", 'Bizantine' ),
										"id" => "homepage_layout",
										"std" => "bseven",
										"type" => "images",
										"options" => array(
											'bone' => $imagepath . 'layout1.png',
											'bseven' => $imagepath . 'bseven.png',
											'spage' => $imagepath . 'layout2.png')
										);					

										
		$options[] = array( "type" => "groupcontainerclose");
		
		$options[] = array( "name" => __("Layouts available in PRO Version", 'Bizantine' ),
							"type" => "groupcontaineropen");	

					$options[] = array( "name" => __("Following layout's are available in PRO version", 'Bizantine' ),
										"desc" => __("Upgrade to PRO version for above layouts", 'Bizantine' ),
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
		
		$options[] = array( "name" => __("Quote Settings", 'Bizantine' ),
							"type" => "groupcontaineropen");	

					$options[] = array( "name" => __("Show Quote?", 'Bizantine' ),
										"desc" => __("Enter the welcome text", 'Bizantine' ),
										"id" => "show_quote",
										"type" => "proupgrade");	
										
					$options[] = array( "name" => __("Quote 1", 'Bizantine' ),
										"desc" => __("Enter the quote text", 'Bizantine' ),
										"id" => "show_quote1",
										"type" => "proupgrade");														

					$options[] = array( "name" => __("Customer 1", 'Bizantine' ),
										"desc" => __("Enter the customer name", 'Bizantine' ),
										"id" => "show_quote1_cust",
										"type" => "proupgrade");
										
					$options[] = array( "name" => __("Quote 2", 'Bizantine' ),
										"desc" => __("Enter the quote text", 'Bizantine' ),
										"id" => "show_quote2",
										"type" => "proupgrade");														

					$options[] = array( "name" => __("Customer 2", 'Bizantine' ),
										"desc" => __("Enter the customer name", 'Bizantine' ),
										"id" => "show_quote2_cust",
										"type" => "proupgrade");
										
					$options[] = array( "name" => __("Quote 3", 'Bizantine' ),
										"desc" => __("Enter the quote text", 'Bizantine' ),
										"id" => "show_quote3",
										"type" => "proupgrade");														

					$options[] = array( "name" => __("Customer 3", 'Bizantine' ),
										"desc" => __("Enter the customer name", 'Bizantine' ),
										"id" => "show_quote3_cust",
										"type" => "proupgrade");																				
										
		$options[] = array( "type" => "groupcontainerclose");
		
		$options[] = array( "name" => __("Client Logos", 'Bizantine' ),
							"type" => "groupcontaineropen");	

					$options[] = array( "name" => __("Show Client Logo Section?", 'Bizantine' ),
										"desc" => __("Select yes if you want to show client logos.", 'Bizantine' ),
										"id" => "show_quote",
										"type" => "proupgrade");	
										
					$options[] = array( "name" => __("Client Logo # 1", 'Bizantine' ),
										"desc" => __("upload the logo", 'Bizantine' ),
										"id" => "client_logo1",
										"type" => "proupgrade");														

					$options[] = array( "name" => __("Client Logo # 2", 'Bizantine' ),
										"desc" => __("upload the logo", 'Bizantine' ),
										"id" => "client_logo2",
										"type" => "proupgrade");
										
					$options[] = array( "name" => __("Client Logo # 3", 'Bizantine' ),
										"desc" => __("upload the logo", 'Bizantine' ),
										"id" => "client_logo3",
										"type" => "proupgrade");														

					$options[] = array( "name" => __("Client Logo # 4", 'Bizantine' ),
										"desc" => __("upload the logo", 'Bizantine' ),
										"id" => "client_logo4",
										"type" => "proupgrade");
										
		$options[] = array( "type" => "groupcontainerclose");							
						
	$options[] = array( "type" => "innertabclose");	
	
	$options[] = array( "name" => "country5",
						"type" => "innertabopen");
						
		$options[] = array( "name" => __("Biz One Settings", 'Bizantine' ),
							"type" => "tabheading");
																							
						
		$options[] = array( "name" => __("Welcome Section", 'Bizantine' ),
							"type" => "groupcontaineropen");	

					$options[] = array( "name" => __("Headline", 'Bizantine' ),
										"desc" => __("Enter the headline", 'Bizantine' ),
										"id" => "welcome_headline",
										"type" => "textarea");		
										
					$options[] = array( "name" => __("Welcome text", 'Bizantine' ),
										"desc" => __("Enter the welcome text", 'Bizantine' ),
										"id" => "welcome_text",
										"type" => "textarea");														

										
		$options[] = array( "type" => "groupcontainerclose");	
		
		$options[] = array( "name" => __("Left Section", 'Bizantine' ),
							"type" => "groupcontaineropen");	

					$options[] = array( "name" => __("Upload Image", 'Bizantine' ),
										"desc" => __("Upload your image here.", 'Bizantine' ),
										"id" => "left_section_image",
										"type" => "upload");					
					
					$options[] = array( "name" => __("Headline", 'Bizantine' ),
										"desc" => __("Enter the headline", 'Bizantine' ),
										"id" => "left_section_headline",
										"type" => "textarea");		
										
					$options[] = array( "name" => __("Welcome text", 'Bizantine' ),
										"desc" => __("Enter the welcome text", 'Bizantine' ),
										"id" => "left_section_text",
										"type" => "textarea");
										
					$options[] = array( "name" => __("Link", 'Bizantine' ),
										"desc" => __("Enter the link to product or service", 'Bizantine' ),
										"id" => "left_section_link",
										"type" => "text");																							

										
		$options[] = array( "type" => "groupcontainerclose");	
		
		$options[] = array( "name" => __("Center Section", 'Bizantine' ),
							"type" => "groupcontaineropen");	

					$options[] = array( "name" => __("Upload Image", 'Bizantine' ),
										"desc" => __("Upload your image here.", 'Bizantine' ),
										"id" => "center_section_image",
										"type" => "upload");					
					
					$options[] = array( "name" => __("Headline", 'Bizantine' ),
										"desc" => __("Enter the headline", 'Bizantine' ),
										"id" => "center_section_headline",
										"type" => "textarea");		
										
					$options[] = array( "name" => __("Welcome text", 'Bizantine' ),
										"desc" => __("Enter the welcome text", 'Bizantine' ),
										"id" => "center_section_text",
										"type" => "textarea");	
										
					$options[] = array( "name" => __("Link", 'Bizantine' ),
										"desc" => __("Enter the link to product or service", 'Bizantine' ),
										"id" => "center_section_link",
										"type" => "text");																							

										
		$options[] = array( "type" => "groupcontainerclose");	
		
		$options[] = array( "name" => __("Right Section", 'Bizantine' ),
							"type" => "groupcontaineropen");	

					$options[] = array( "name" => __("Upload Image", 'Bizantine' ),
										"desc" => __("Upload your image here.", 'Bizantine' ),
										"id" => "right_section_image",
										"type" => "upload");					
					
					$options[] = array( "name" => __("Headline", 'Bizantine' ),
										"desc" => __("Enter the headline", 'Bizantine' ),
										"id" => "right_section_headline",
										"type" => "textarea");		
										
					$options[] = array( "name" => __("Welcome text", 'Bizantine' ),
										"desc" => __("Enter the welcome text", 'Bizantine' ),
										"id" => "right_section_text",
										"type" => "textarea");
										
					$options[] = array( "name" => __("Link", 'Bizantine' ),
										"desc" => __("Enter the link to product or service", 'Bizantine' ),
										"id" => "right_section_link",
										"type" => "text");																								

										
		$options[] = array( "type" => "groupcontainerclose");
		
		$options[] = array( "name" => __("Quote Section", 'Bizantine' ),
							"type" => "groupcontaineropen");
							
					$options[] = array( "name" => __("Show Quote?", 'Bizantine' ),
										"desc" => __("Select yes if you want to show quote.", 'Bizantine' ),
										"id" => "show_Bizantine_quote_bizone",
										"std" => "true",
										"type" => "select",
										"class" => "mini", //mini, tiny, small
										"options" => $magpro_slider_start);								
					
					$options[] = array( "name" => __("Quote", 'Bizantine' ),
										"desc" => __("Enter the Quote Text", 'Bizantine' ),
										"id" => "quote_section_text",
										"type" => "textarea");		
										
					$options[] = array( "name" => __("Customer Name", 'Bizantine' ),
										"desc" => __("Enter the customer name", 'Bizantine' ),
										"id" => "quote_section_name",
										"type" => "text");														

										
		$options[] = array( "type" => "groupcontainerclose");	
		
		$options[] = array( "name" => __("Recent Posts", 'Bizantine' ),
							"type" => "groupcontaineropen");
														
					$options[] = array( "name" => __("Show Recent Posts Section?", 'Bizantine' ),
										"desc" => __("Select yes if you want to recent posts at the bottom.", 'Bizantine' ),
										"id" => "show_bizone_posts",
										"std" => "true",
										"type" => "select",
										"class" => "mini", //mini, tiny, small
										"options" => $magpro_slider_start);	
										
		$options[] = array( "type" => "groupcontainerclose");														
						
	$options[] = array( "type" => "innertabclose");
	
	$options[] = array( "name" => "country7",
						"type" => "innertabopen");
						

		$options[] = array( "name" => __("Biz Seven Settings", 'Bizantine' ),
							"type" => "tabheading");
							
		$options[] = array( "name" => __("Welcome Section", 'Bizantine' ),
							"type" => "groupcontaineropen");
							
					$options[] = array( "name" => __("Show Welcome Section?", 'Bizantine' ),
										"desc" => __("Select yes if you want to show welcome section.", 'Bizantine' ),
										"id" => "show_bseven_welcome_section",
										"std" => "true",
										"type" => "select",
										"class" => "mini", //mini, tiny, small
										"options" => $magpro_slider_start);							

					$options[] = array( "name" => __("Headline", 'Bizantine' ),
										"desc" => __("Enter the headline", 'Bizantine' ),
										"id" => "bseven_welcome_headline",
										"type" => "text");		
										
					$options[] = array( "name" => __("Welcome text", 'Bizantine' ),
										"desc" => __("Enter the welcome text.", 'Bizantine' ),
										"id" => "bseven_welcome_text",
										"type" => "textarea");														
										
		$options[] = array( "type" => "groupcontainerclose");	
		
		$options[] = array( "name" => __("Work Section", 'Bizantine' ),
							"type" => "groupcontaineropen");
							
					$options[] = array( "name" => __("Show Work Section?", 'Bizantine' ),
										"desc" => __("Select yes if you want to show work section.", 'Bizantine' ),
										"id" => "bseven_work_show",
										"std" => "true",
										"type" => "select",
										"class" => "mini", //mini, tiny, small
										"options" => $magpro_slider_start);								

					$options[] = array( "name" => __("Headline", 'Bizantine' ),
										"desc" => __("Enter the headline", 'Bizantine' ),
										"id" => "bseven_work_headline",
										"type" => "text");		
										
					$options[] = array( "name" => __("Welcome text", 'Bizantine' ),
										"desc" => __("Enter the welcome text.", 'Bizantine' ),
										"id" => "bseven_work_text",
										"type" => "textarea");
										
					$options[] = array( "name" => __("Upload Portfolio Image 1", 'Bizantine' ),
										"desc" => __("Upload your image here.", 'Bizantine' ),
										"id" => "bseven_port_image_1",
										"type" => "upload");
										
					$options[] = array( "name" => __("Upload Portfolio Image 2", 'Bizantine' ),
										"desc" => __("Upload your image here.", 'Bizantine' ),
										"id" => "bseven_port_image_2",
										"type" => "upload");
										
					$options[] = array( "name" => __("Upload Portfolio Image 3", 'Bizantine' ),
										"desc" => __("Upload your image here.", 'Bizantine' ),
										"id" => "bseven_port_image_3",
										"type" => "upload");
										
					$options[] = array( "name" => __("Upload Portfolio Image 4", 'Bizantine' ),
										"desc" => __("Upload your image here.", 'Bizantine' ),
										"id" => "bseven_port_image_4",
										"type" => "upload");
										
					$options[] = array( "name" => __("Upload Portfolio Image 5", 'Bizantine' ),
										"desc" => __("Upload your image here.", 'Bizantine' ),
										"id" => "bseven_port_image_5",
										"type" => "upload");
										
					$options[] = array( "name" => __("Upload Portfolio Image 6", 'Bizantine' ),
										"desc" => __("Upload your image here.", 'Bizantine' ),
										"id" => "bseven_port_image_6",
										"type" => "upload");
										
					$options[] = array( "name" => __("Upload Portfolio Image 7", 'Bizantine' ),
										"desc" => __("Upload your image here.", 'Bizantine' ),
										"id" => "bseven_port_image_7",
										"type" => "upload");
										
					$options[] = array( "name" => __("Upload Portfolio Image 8", 'Bizantine' ),
										"desc" => __("Upload your image here.", 'Bizantine' ),
										"id" => "bseven_port_image_8",
										"type" => "upload");																																																																
										
		$options[] = array( "type" => "groupcontainerclose");	
		
		$options[] = array( "name" => __("About Section", 'Bizantine' ),
							"type" => "groupcontaineropen");
							
					$options[] = array( "name" => __("Show About Section?", 'Bizantine' ),
										"desc" => __("Select yes if you want to show about section.", 'Bizantine' ),
										"id" => "bseven_about_show",
										"std" => "true",
										"type" => "select",
										"class" => "mini", //mini, tiny, small
										"options" => $magpro_slider_start);								
							
					$options[] = array( "name" => __("Headline", 'Bizantine' ),
										"desc" => __("Enter the headline", 'Bizantine' ),
										"id" => "bseven_about_headline",
										"type" => "text");		
										
					$options[] = array( "name" => __("Welcome text", 'Bizantine' ),
										"desc" => __("Enter the welcome text.", 'Bizantine' ),
										"id" => "bseven_about_text",
										"type" => "textarea");
										
		$options[] = array( "type" => "groupcontainerclose");									
							
		$options[] = array( "name" => __("Recent Posts", 'Bizantine' ),
							"type" => "groupcontaineropen");
														
					$options[] = array( "name" => __("Show Recent Posts Section?", 'Bizantine' ),
										"desc" => __("Select yes if you want to recent posts at the bottom.", 'Bizantine' ),
										"id" => "show_bizseven_posts",
										"std" => "true",
										"type" => "select",
										"class" => "mini", //mini, tiny, small
										"options" => $magpro_slider_start);	
										
		$options[] = array( "type" => "groupcontainerclose");																						
						
	$options[] = array( "type" => "innertabclose");			
	
	$options[] = array( "name" => "country9",
						"type" => "innertabopen");
						
		$options[] = array( "name" => __("Standard Page Settings", 'Bizantine' ),
							"type" => "groupcontaineropen");	

					$options[] = array( "name" => __("Show Comments?", 'Bizantine' ),
										"desc" => __("Select yes if you want to show comments", 'Bizantine' ),
										"id" => "show_comments_spage",
										"std" => "true",
										"type" => "select",
										"class" => "mini", //mini, tiny, small
										"options" => $magpro_slider_start);		
										
		$options[] = array( "type" => "groupcontainerclose");								
						
	$options[] = array( "type" => "innertabclose");	

	$options[] = array( "name" => "country19",
						"type" => "innertabopen");
						
		$options[] = array( "name" => __("Layouts available in PRO Version", 'Bizantine' ),
							"type" => "groupcontaineropen");	

					$options[] = array( "name" => __("Portfolio layout's are available in PRO version", 'Bizantine' ),
										"desc" => __("Upgrade to PRO version for above layouts", 'Bizantine' ),
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
						
		$options[] = array( "name" => __("Footer Settings", 'Bizantine' ),
							"type" => "tabheading");
							
		$options[] = array( "name" => __("Social Section", 'Bizantine' ),
							"type" => "groupcontaineropen");	
					
				$options[] = array( "name" => __("Show social Section?", 'Bizantine' ),
										"desc" => __("Select yes if you want to show social section.", 'Bizantine' ),
										"id" => "show_social_section",
										"type" => "proupgrade",
										);														
										
		$options[] = array( "type" => "groupcontainerclose");	
		
		$options[] = array( "name" => __("Footer Logo Upload", 'Bizantine' ),
							"type" => "groupcontaineropen");	
					
				$options[] = array( "name" => __("Upload Logo", 'Bizantine' ),
										"desc" => __("Upload your logo here.", 'Bizantine' ),
										"id" => "footer_logo_upload",
										"type" => "proupgrade",
										);														
										
		$options[] = array( "type" => "groupcontainerclose");	
		
		$options[] = array( "name" => __("Address Settings", 'Bizantine' ),
							"type" => "groupcontaineropen");	
					
				$options[] = array( "name" => __("Show Search?", 'Bizantine' ),
										"desc" => __("Select yes if you want to show search.", 'Bizantine' ),
										"id" => "show_foote_search",
										"type" => "proupgrade",
										);	
										
				$options[] = array( "name" => __("Address", 'Bizantine' ),
										"desc" => __("Enter Address", 'Bizantine' ),
										"id" => "footer_address",
										"type" => "proupgrade",
										);	
										
				$options[] = array( "name" => __("Email", 'Bizantine' ),
										"desc" => __("Enter Email Address", 'Bizantine' ),
										"id" => "footer_email_address",
										"type" => "proupgrade",
										);
										
				$options[] = array( "name" => __("Phone Number", 'Bizantine' ),
										"desc" => __("Enter Phone Number", 'Bizantine' ),
										"id" => "footer_phone",
										"type" => "proupgrade",
										);
										
				$options[] = array( "name" => __("Skype", 'Bizantine' ),
										"desc" => __("Enter Skype Address", 'Bizantine' ),
										"id" => "footer_skype_address",
										"type" => "proupgrade",
										);
										
				$options[] = array( "name" => __("Google Map", 'Bizantine' ),
										"desc" => __("Enter google map", 'Bizantine' ),
										"id" => "footer_map_address",
										"type" => "proupgrade",
										);																																																														
										
		$options[] = array( "type" => "groupcontainerclose");											
										
		$options[] = array( "name" => __("Footer Layouts", 'Bizantine' ),
							"type" => "groupcontaineropen");	

					$options[] = array( "name" => __("Select a footer layout", 'Bizantine' ),
										"desc" => __("Images for layout.", 'Bizantine' ),
										"id" => "footer_layout",
										"std" => "one",
										"type" => "images",
										"std" => "one",
										"options" => array(
											'one' => $imagepath . 'footer1.png',
											'two' => $imagepath . 'footer2.png')
										);	
										
		$options[] = array( "type" => "groupcontainerclose");
		
		$options[] = array( "name" => __("Footer Layouts available in PRO Version", 'Bizantine' ),
							"type" => "groupcontaineropen");	

					$options[] = array( "name" => __("Following layout's are available in PRO version", 'Bizantine' ),
										"desc" => __("Upgrade to PRO version for above layouts", 'Bizantine' ),
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