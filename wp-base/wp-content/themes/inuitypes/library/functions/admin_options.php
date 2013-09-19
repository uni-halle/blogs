<?php

$options[] = array(	"type" => "maintabletop");

    ////// General Settings
	    $options[] = array(	"name" => "General Settings",
						"type" => "heading");
						
		    $options[] = array(	"name" => "Customize Your Design",
						        "toggle" => "true",
								"type" => "subheadingtop");
						
				$options[] = array(	"label" => "Use Custom Stylesheet",
						            "desc" => "If you want to make custom design changes using CSS enable and <a href='". $customcssurl . "'>edit custom.css file here</a>.",
						            "id" => $shortname."_customcss",
						            "std" => "false",
						            "type" => "checkbox");	
						
			$options[] = array(	"type" => "subheadingbottom");
			
			$options[] = array(	"name" => "Favicon",
						        "toggle" => "true",
								"type" => "subheadingtop");

				$options[] = array(	"desc" => "Paste the full URL for your favicon image here if you wish to show it in browsers. <a href='http://www.favicon.cc/'>Create one here</a>",
						            "id" => $shortname."_favicon",
						            "std" => "",
						            "type" => "text");	
			
			$options[] = array(	"type" => "subheadingbottom");
			
			$options[] = array(	"name" => "Logo",
						        "toggle" => "true",
								"type" => "subheadingtop");

                $options[] = array(	"name" => "Choose Your Logo",
				                    "desc" => "Paste the full URL to your logo here. Must be within <code>620x100 px</code> dimensions!",
						            "id" => $shortname."_logo_url",
						            "std" => "",
						            "type" => "text");

				$options[] = array(	"name" => "Choose Blog Title over Logo",
				                    "desc" => "This option will overwrite your logo selection above - You can <a href='". $generaloptionsurl . "'>change your settings here</a>",
						            "label" => "Show Blog Title + Tagline.",
						            "id" => $shortname."_show_blog_title",
						            "std" => "true",
						            "type" => "checkbox");	

			$options[] = array(	"type" => "subheadingbottom");
			
			$options[] = array(	"name" => "Relative dates in posts",
						        "toggle" => "true",
								"type" => "subheadingtop");
				
				$options[] = array(	"name" => "Use relative dates in posts",
				                    "label" => "Show relative dates in posts.",
						            "id" => $shortname."_relative_date",
						            "std" => "false",
						            "type" => "checkbox");	
						
		    $options[] = array(	"type" => "subheadingbottom");
			
			$options[] = array(	"name" => "Comments Appearance",
						        "toggle" => "true",
								"type" => "subheadingtop");
						
				$options[] = array(	"label" => "Display Comments Count",
						            "desc" => "Show comments count in Front/Archive",
						            "id" => $shortname."_commentcount",
						            "std" => "false",
						            "type" => "checkbox");	
						
			$options[] = array(	"type" => "subheadingbottom");
			
			$options[] = array(	"name" => "Syndication / Feed",
						        "toggle" => "true",
								"type" => "subheadingtop");			
						
				$options[] = array( "desc" => "If you are using a service like Feedburner to manage your RSS feed, enter full URL to your feed into box above. If you'd prefer to use the default WordPress feed, simply leave this box blank.",
			    		            "id" => $shortname."_feedburner_url",
			    		            "std" => "",
			    		            "type" => "text");	
						
			$options[] = array(	"type" => "subheadingbottom");
								
		$options[] = array(	"type" => "maintablebreak");
		
    /// Featured Images and Thumbnails											
				
		$options[] = array(	"name" => "Images & Thumbnails",
						    "type" => "heading");
										
			$options[] = array(	"name" => "Featured Images in Single Posts",
						        "toggle" => "true",
								"type" => "subheadingtop");
						
				$options[] = array(	"label" => "Hide Featured Images in Single Posts",
						            "desc" => "Check this option if you want to hide auto resized featured images displayed in single posts. By default custom field images are visible in single posts at the top.",
						            "id" => $shortname."_hide_singleimg",
						            "std" => "false",
						            "type" => "checkbox");	
						
			$options[] = array(	"type" => "subheadingbottom");
						
		$options[] = array(	"type" => "maintablebreak");
		
	/// Navigation Settings												
				
		$options[] = array(	"name" => "Navigation Settings",
						    "type" => "heading");
										
			$options[] = array(	"name" => "Exclude Pages from Header",
								"toggle" => "true",
								"type" => "subheadingtop");
						
				$options[] = array(	"type" => "multihead");
						
				$options = pages_exclude($options);
					
			$options[] = array(	"type" => "subheadingbottom");
						
		$options[] = array(	"type" => "maintablebreak");

		
	/// Home Page Settings											
				
		$options[] = array(	"name" => "Home Page Settings",
						    "type" => "heading");
			
		    $options[] = array(	"name" => "About Your Blog",
						        "toggle" => "true",
								"type" => "subheadingtop");
				
				$options[] = array(	"desc" => "Write something about your blog here. Choose your words wisely and write only what is relevant to your blog content! --> This section will be visible on top of your front page.",
					                "id" => $shortname."_about_blog",
					                "std" => "",
					                "type" => "textarea2");		
						
		    $options[] = array(	"type" => "subheadingbottom");
			
			$options[] = array(	"name" => "Featured Post Entries",
						        "toggle" => "true",
								"type" => "subheadingtop");
				
				$options[] = array(	"desc" => "Select max number of featured entries you wish to appear on homepage. Featured entries are the latest highlighted posts.",
			    		            "id" => $shortname."_featured_entries",
			    		            "std" => "Select a Number:",
			    		            "type" => "select",
			    		            "options" => $other_entries);
						
		    $options[] = array(	"type" => "subheadingbottom");
						
		$options[] = array(	"type" => "maintablebreak");
		
	/// Layout Settings												
				
		$options[] = array(	"name" => "Layout Settings",
						    "type" => "heading");
			
		    $options[] = array(	"name" => "Featured Posts Appearance",
						        "toggle" => "true",
								"type" => "subheadingtop");
				
				$options[] = array(	"name" => "I Want One Column Featured Posts",
					                "desc" => "Show featured posts in one column instead of default two columns",
					                "id" => $shortname."_one_column_featposts",
					                "std" => "false",
					                "type" => "checkbox");	
						
		    $options[] = array(	"type" => "subheadingbottom");
			
			$options[] = array(	"name" => "Normal Posts Appearance",
						        "toggle" => "true",
								"type" => "subheadingtop");
				
				$options[] = array(	"name" => "I Want One Column Normal Posts",
					                "desc" => "Show normal posts in one column instead of default two columns",
					                "id" => $shortname."_one_column_posts",
					                "std" => "false",
					                "type" => "checkbox");	
						
		    $options[] = array(	"type" => "subheadingbottom");
			
			$options[] = array(	"name" => "Sidebar",
						        "toggle" => "true",
								"type" => "subheadingtop");
				
				$options[] = array(	"name" => "Show sidebar on the left or right?",
				                    "label" => "Show sidebar content on the right.",
					                "id" => $shortname."_right_sidebar",
					                "std" => "true",
					                "type" => "checkbox");		
						
		    $options[] = array(	"type" => "subheadingbottom");
						
		$options[] = array(	"type" => "maintablebreak");
				
$options[] = array(	"type" => "maintablebottom");
				
$options[] = array(	"type" => "maintabletop");

    /// Blog Stats and Scripts											
				
		$options[] = array(	"name" => "Blog Stats and Scripts",
						    "type" => "heading");
										
			$options[] = array(	"name" => "Blog Header Scripts",
						        "toggle" => "true",
								"type" => "subheadingtop");	
						
				$options[] = array(	"name" => "Header Scripts",
					                "desc" => "If you need to add scripts to your header (like <a href='http://haveamint.com/'>Mint</a> tracking code), do so here.",
					                "id" => $shortname."_scripts_header",
					                "std" => "",
					                "type" => "textarea");
			
			$options[] = array(	"type" => "subheadingbottom");
			
			$options[] = array(	"name" => "Blog Footer Scripts",
						        "toggle" => "true",
								"type" => "subheadingtop");	
						
				$options[] = array(	"name" => "Footer Scripts",
					                "desc" => "If you need to add scripts to your footer (like <a href='http://www.google.com/analytics/'>Google Analytics</a> tracking code), do so here.",
					                "id" => $shortname."_google_analytics",
					                "std" => "",
					                "type" => "textarea");
			
			$options[] = array(	"type" => "subheadingbottom");
						
		$options[] = array(	"type" => "maintablebreak");
		
	////// Advertising scripts
                
		$options[] = array(	"name" => "Advertising Scripts",
						    "type" => "heading");
						
			$options[] = array(	"name" => "Ad Scripts Code - above header",
						        "toggle" => "true",
								"type" => "subheadingtop");
						
				$options[] = array(	"name" => "Header Page",
					                "desc" => "Enter your ad script code here. Must be up to <code>920 px </code> width.",
					                "id" => $shortname."_header_adsense",
					                "std" => "",
					                "type" => "textarea");
						
		    $options[] = array(	"type" => "subheadingbottom");
			
			$options[] = array(	"name" => "Ad Scripts Code - below featured",
						        "toggle" => "true",
								"type" => "subheadingtop");
						
				$options[] = array(	"name" => "Between featured and rest of entries",
					                "desc" => "Enter your ad script code here. Must be up to <code>620 px</code> width.",
					                "id" => $shortname."_main_adsense",
					                "std" => "",
					                "type" => "textarea");
						
		    $options[] = array(	"type" => "subheadingbottom");
			
			$options[] = array(	"name" => "Ad Scripts Code - above search",
						        "toggle" => "true",
								"type" => "subheadingtop");
						
				$options[] = array(	"name" => "Search Page",
					                "desc" => "Enter your ad script code here. Must be up to <code>620 px</code> width.",
					                "id" => $shortname."_search_adsense",
					                "std" => "",
					                "type" => "textarea");
						
		    $options[] = array(	"type" => "subheadingbottom");
			
			$options[] = array(	"name" => "Ad Scripts Code - above comments",
						        "toggle" => "true",
								"type" => "subheadingtop");
				
				$options[] = array(	"name" => "Above Comments",
					                "desc" => "Enter your ad script code here. Must be up to <code>620 px</code> width.",
					                "id" => $shortname."_comment_adsense",
					                "std" => "",
					                "type" => "textarea");
						
		    $options[] = array(	"type" => "subheadingbottom");
						
		$options[] = array(	"type" => "maintablebreak");
					
    /// 250x250	
				
		$options[] = array(	"name" => "Banner Ad 250x250 <br/>- <em class='widgetsurl'><a href='". $widgetsurl . "'>enable in Widgets</a></em>",
						    "type" => "heading");
						
			$options[] = array(	"name" => "250x250 Ad Banner Settings",
						        "toggle" => "true",
								"type" => "subheadingtop");

				$options[] = array(	"name" => "Disable 250x250 Ad Banner",
						            "label" => "Ignore the 250x250 Ad.",
						            "id" => $shortname."_not_200",
						            "std" => "true",
						            "type" => "checkbox");

				$options[] = array(	"name" => "Display Only On Homepage?",
						            "label" => "Display this ad only on homepage. ",
						            "id" => $shortname."_home_only",
						            "std" => "false",
						            "type" => "checkbox");

				$options[] = array(	"name" => "250x250 Ad - Image Location",
						            "desc" => "Enter the URL for this Ad.",
						            "id" => $shortname."_block_image",
						            "std" => $template_path . "/images/250x250.png",
						            "type" => "text");
						
				$options[] = array(	"name" => "250x250 Ad - Destination",
						            "desc" => "Enter the URL where this Ad points to.",
			    		            "id" => $shortname."_block_url",
						            "std" => "http://bizzartic.com/bizzthemes/inuitypes/download/",
			    		            "type" => "text");
						
			$options[] = array(	"type" => "subheadingbottom");
			
		$options[] = array(	"type" => "maintablebreak");
			

	/// 125x125 top
				
		$options[] = array(	"name" => "Banner Ads 125x125 <br/>- <em class='widgetsurl'><a href='". $widgetsurl . "'>enable in Widgets</a></em>",
						    "type" => "heading");
			
			$options[] = array(	"name" => "125x125 Ad Banners Settings",
						        "toggle" => "true",
								"type" => "subheadingtop");

				$options[] = array(	"name" => "Display two (1 and 2) 125x125 ads",
						            "label" => "Display top 1st and 2nd Ad in sidebar.",
						            "id" => $shortname."_show_ads_top12",
						            "std" => "false",
						            "type" => "checkbox");

				$options[] = array(	"name" => "#1 - Image Location",
						            "desc" => "Enter the URL for this Ad.",
						            "id" => $shortname."_ad_image_1",
						            "std" => $template_path . "/images/ad-125x125.png",
						            "type" => "text");
						
				$options[] = array(	"name" => "#1 - Destination",
						            "desc" => "Enter the URL where this Ad points to.",
			    		            "id" => $shortname."_ad_url_1",
						            "std" => "http://www.bizzartic.com",
			    		            "type" => "text");						

				$options[] = array(	"name" => "#2 - Image Location",
						            "desc" => "Enter the URL for this Ad.",
						            "id" => $shortname."_ad_image_2",
						            "std" => $template_path . "/images/ad-125x125.png",
						            "type" => "text");
						
				$options[] = array(	"name" => "#2 - Destination",
					      	        "desc" => "Enter the URL where this Ad points to.",
			    		            "id" => $shortname."_ad_url_2",
						            "std" => "http://www.bizzartic.com",
			    		            "type" => "text");

				$options[] = array(	"name" => "Display two (3 and 4) 125x125 ads",
						            "label" => "Display 2nd and 3rd Ad in sidebar.",
						            "id" => $shortname."_show_ads_top34",
						            "std" => "false",
						            "type" => "checkbox");

				$options[] = array(	"name" => "#3 - Image Location",
						            "desc" => "Enter the URL for this Ad.",
						            "id" => $shortname."_ad_image_3",
						            "std" => $template_path . "/images/ad-125x125.png",
						            "type" => "text");
						
				$options[] = array(	"name" => "#3 - Destination",
						            "desc" => "Enter the URL where this Ad points to.",
			    		            "id" => $shortname."_ad_url_3",
						            "std" => "http://www.bizzartic.com",
			    		            "type" => "text");						

				$options[] = array(	"name" => "#4 - Image Location",
						            "desc" => "Enter the URL for this Ad.",
						            "id" => $shortname."_ad_image_4",
						            "std" => $template_path . "/images/ad-125x125.png",
						            "type" => "text");
						
				$options[] = array(	"name" => "#4 - Destination",
						            "desc" => "Enter the URL where this Ad points to.",
			    		            "id" => $shortname."_ad_url_4",
						            "std" => "http://www.bizzartic.com",
			    		            "type" => "text");
						
				$options[] = array(	"name" => "Display two (5 and 6) 125x125 ads",
						            "label" => "Display top 5th and 6th Ad in sidebar.",
						            "id" => $shortname."_show_ads_top56",
						            "std" => "false",
						            "type" => "checkbox");
						
				$options[] = array(	"name" => "#5 - Image Location",
					 	            "desc" => "Enter the URL for this Ad.",
						            "id" => $shortname."_ad_image_5",
						            "std" => $template_path . "/images/ad-125x125.png",
						            "type" => "text");
						
				$options[] = array(	"name" => "#5 - Destination",
						            "desc" => "Enter the URL where this Ad points to.",
			    		            "id" => $shortname."_ad_url_5",
						            "std" => "http://www.bizzartic.com",
			    		            "type" => "text");
						
				$options[] = array(	"name" => "#6 - Image Location",
						            "desc" => "Enter the URL for this Ad.",
						            "id" => $shortname."_ad_image_6",
						            "std" => $template_path . "/images/ad-125x125.png",
						            "type" => "text");
						
				$options[] = array(	"name" => "#6 - Destination",
					 	            "desc" => "Enter the URL where this Ad points to.",
			    	 	            "id" => $shortname."_ad_url_6",
						            "std" => "http://www.bizzartic.com",
			    		            "type" => "text");
						
			$options[] = array(	"type" => "subheadingbottom");
			
		$options[] = array(	"type" => "maintablebreak");
												
$options[] = array(	"type" => "maintablebottom");
				
$options[] = array(	"type" => "maintabletop");

    /// SEO Options
				
		$options[] = array(	"name" => "SEO Options",
						    "type" => "heading");
						
			$options[] = array(	"name" => "Home Page <code>&lt;meta&gt;</code> tags",
						        "toggle" => "true",
								"type" => "subheadingtop");

				$options[] = array(	"name" => "Meta Description",
					                "desc" => "You should use meta descriptions to provide search engines with additional information about topics that appear on your site. This only applies to your home page.",
					                "id" => $shortname."_meta_description",
					                "std" => "",
					                "type" => "textarea");

				$options[] = array(	"name" => "Meta Keywords (comma separated)",
					                "desc" => "Meta keywords are rarely used nowadays but you can still provide search engines with additional information about topics that appear on your site. This only applies to your home page.",
						            "id" => $shortname."_meta_keywords",
						            "std" => "",
						            "type" => "text");
									
				$options[] = array(	"name" => "Meta Author",
					                "desc" => "You should write your <em>full name</em> here but only do so if this blog is writen only by one outhor. This only applies to your home page.",
						            "id" => $shortname."_meta_author",
						            "std" => "",
						            "type" => "text");
						
			$options[] = array(	"type" => "subheadingbottom");
			
			$options[] = array(	"name" => "Add <code>&lt;noindex&gt;</code> tags",
						        "toggle" => "true",
								"type" => "subheadingtop");

				$options[] = array(	"label" => "Add <code>&lt;noindex&gt;</code> to category archives.",
						            "id" => $shortname."_noindex_category",
						            "std" => "true",
						            "type" => "checkbox");
									
				$options[] = array(	"label" => "Add <code>&lt;noindex&gt;</code> to tag archives.",
						            "id" => $shortname."_noindex_tag",
						            "std" => "true",
						            "type" => "checkbox");
				
				$options[] = array(	"label" => "Add <code>&lt;noindex&gt;</code> to author archives.",
						            "id" => $shortname."_noindex_author",
						            "std" => "true",
						            "type" => "checkbox");
									
			    $options[] = array(	"label" => "Add <code>&lt;noindex&gt;</code> to Search Results.",
						            "id" => $shortname."_noindex_search",
						            "std" => "true",
						            "type" => "checkbox");
				
				$options[] = array(	"label" => "Add <code>&lt;noindex&gt;</code> to daily archives.",
						            "id" => $shortname."_noindex_daily",
						            "std" => "true",
						            "type" => "checkbox");
				
				$options[] = array(	"label" => "Add <code>&lt;noindex&gt;</code> to monthly archives.",
						            "id" => $shortname."_noindex_monthly",
						            "std" => "true",
						            "type" => "checkbox");
				
				$options[] = array(	"label" => "Add <code>&lt;noindex&gt;</code> to yearly archives.",
						            "id" => $shortname."_noindex_yearly",
						            "std" => "true",
						            "type" => "checkbox");
				
						
			$options[] = array(	"type" => "subheadingbottom");
			
			
		$options[] = array(	"type" => "maintablebreak");
		
		
	//////Translations		

	    $options[] = array(	"name" => "Translations",
						    "type" => "heading");
						
			$options[] = array(	"name" => "General Text",
			                    "toggle" => "true",
						        "type" => "subheadingtop");
				
				$options[] = array(	"desc" => "Change Home link text - next to category menu in header",
			    		            "id" => $shortname."_home_name",
			    		            "std" => "Home",
			    		            "type" => "text");
						
				$options[] = array(	"desc" => "Change Search text",
			    		            "id" => $shortname."_search_name",
			    		            "std" => "Search",
			    		            "type" => "text");
									
				$options[] = array(	"desc" => "Change Search results text",
			    		            "id" => $shortname."_search_results",
			    		            "std" => "Search results for",
			    		            "type" => "text");
									
				$options[] = array(	"desc" => "Change Nothing Found for Search text",
			    		            "id" => $shortname."_search_nothing_found",
			    		            "std" => "Nothing found, please search again.",
			    		            "type" => "text");
									
				$options[] = array(	"desc" => "Change Tags text",
			    		            "id" => $shortname."_general_tags_name",
			    		            "std" => "Tags",
			    		            "type" => "text");
				
			$options[] = array(	"type" => "subheadingbottom");
			
			$options[] = array(	"name" => "Subscription Text",
			                    "toggle" => "true",
						        "type" => "subheadingtop");
				
				$options[] = array(	"desc" => "Change Subscribe text",
			    		            "id" => $shortname."_subscribe_name",
			    		            "std" => "Subscribe",
			    		            "type" => "text");
						
				$options[] = array(	"desc" => "Change Browsing Headlines text",
			    		            "id" => $shortname."_subscribe_browse_headlines",
			    		            "std" => "Browsing Headlines",
			    		            "type" => "text");
									
				$options[] = array(	"desc" => "Change Follow Headlines text",
			    		            "id" => $shortname."_subscribe_follow_headlines",
			    		            "std" => "Follow Headlines",
			    		            "type" => "text");
									
				$options[] = array(	"desc" => "Change Follow Topic text",
			    		            "id" => $shortname."_subscribe_follow_topic",
			    		            "std" => "Follow This Topic",
			    		            "type" => "text");
									
				$options[] = array(	"desc" => "Change Follow Term text",
			    		            "id" => $shortname."_subscribe_follow_term",
			    		            "std" => "Follow This Term",
			    		            "type" => "text");
				
			$options[] = array(	"type" => "subheadingbottom");
			
			$options[] = array(	"name" => "Archives Text",
			                    "toggle" => "true",
						        "type" => "subheadingtop");
				
				$options[] = array(	"desc" => "Change Browsing Category text",
			    		            "id" => $shortname."_browsing_category",
			    		            "std" => "Browsing Category",
			    		            "type" => "text");
				
				$options[] = array(	"desc" => "Change Browsing Tag text",
			    		            "id" => $shortname."_browsing_tag",
			    		            "std" => "Browsing Tag",
			    		            "type" => "text");
									
				$options[] = array(	"desc" => "Change Browsing Day text",
			    		            "id" => $shortname."_browsing_day",
			    		            "std" => "Browsing Day",
			    		            "type" => "text");
									
				$options[] = array(	"desc" => "Change Browsing Month text",
			    		            "id" => $shortname."_browsing_month",
			    		            "std" => "Browsing Month",
			    		            "type" => "text");
									
				$options[] = array(	"desc" => "Change Browsing Year text",
			    		            "id" => $shortname."_browsing_year",
			    		            "std" => "Browsing Year",
			    		            "type" => "text");
				
			$options[] = array(	"type" => "subheadingbottom");
			
			$options[] = array(	"name" => "Sitemap Custom Template Text",
						        "toggle" => "true",
								"type" => "subheadingtop");
								
				$options[] = array(	"desc" => "Change Pages text",
			    		            "id" => $shortname."_pages_name",
			    		            "std" => "Pages",
			    		            "type" => "text");
						
				$options[] = array(	"desc" => "Change Last 60 blog posts text",
			    		            "id" => $shortname."_last_posts",
			    		            "std" => "Last 60 Blog Posts",
			    		            "type" => "text");
						
				$options[] = array(	"desc" => "Change Monthly archives text",
			    		            "id" => $shortname."_monthly_archives",
			    		            "std" => "Monthly Archives",
			    		            "type" => "text");
						
				$options[] = array(	"desc" => "Change Categories text",
			    		            "id" => $shortname."_categories_name",
			    		            "std" => "Categories",
			    		            "type" => "text");
						
				$options[] = array(	"desc" => "Change RSS feeds text",
			    		            "id" => $shortname."_rssfeeds_name",
			    		            "std" => "Available RSS Feeds",
			    	 	            "type" => "text");
						
	        $options[] = array(	"type" => "subheadingbottom");
			
			$options[] = array(	"name" => "404 Error Text",
						        "toggle" => "true",
								"type" => "subheadingtop");
						
				$options[] = array(	"desc" => "Change 404 error text",
			    		            "id" => $shortname."_404error_name",
			    		            "std" => "Error 404 | Nothing found!",
			    		            "type" => "text");
						
				$options[] = array(	"desc" => "Change 404 error solution text",
			    		            "id" => $shortname."_404solution_name",
			    		            "std" => "Sorry, but you are looking for something that is not here.",
			    		            "type" => "text");
						
			$options[] = array(	"type" => "subheadingbottom");
			
			$options[] = array(	"name" => "Comments Text",
						        "toggle" => "true",
								"type" => "subheadingtop");
						
				$options[] = array(	"desc" => "Change password protected text",
			    		            "id" => $shortname."_password_protected_name",
			    		            "std" => "This post is password protected. Enter the password to view comments.",
			    		            "type" => "text");
						
				$options[] = array( "desc" => "Change no responses text",
			    		            "id" => $shortname."_comment_responsesa_name",
			    		            "std" => "No Comments",
			    		            "type" => "text");
				
				$options[] = array( "desc" => "Change one response text",
			    		            "id" => $shortname."_comment_responsesb_name",
			    		            "std" => "One Comment",
			    		            "type" => "text");
				
				$options[] = array( "desc" => "Change multiple responses text, leave % intact!",
			    		            "id" => $shortname."_comment_responsesc_name",
			    		            "std" => "% Comments",
			    		            "type" => "text");
						
				$options[] = array( "desc" => "Change trackbacks text",
			    		            "id" => $shortname."_comment_trackbacks_name",
			    		            "std" => "Trackbacks For This Post",
			    		            "type" => "text");
						
				$options[] = array( "desc" => "Change comment moderation text",
			    		            "id" => $shortname."_comment_moderation_name",
			    		            "std" => "Your comment is awaiting moderation.",
			    	             	"type" => "text");
						
				$options[] = array( "desc" => "Change start conversation text",
			    		            "id" => $shortname."_comment_conversation_name",
			    		            "std" => "Be the first to start a conversation",
			    		            "type" => "text");
						
				$options[] = array( "desc" => "Change closed comments text",
			    		            "id" => $shortname."_comment_closed_name",
			    		            "std" => "Comments are closed.",
			    		            "type" => "text");
									
				$options[] = array( "desc" => "Change disabled comments text",
			    		            "id" => $shortname."_comment_off_name",
			    		            "std" => "Comments are off for this post",
			    		            "type" => "text");
						
				$options[] = array( "desc" => "Change leave a reply text",
			    		            "id" => $shortname."_comment_reply_name",
			    		            "std" => "Leave a Reply",
			    		            "type" => "text");
				
				$options[] = array( "desc" => "Change 'you must be' text",
			    		            "id" => $shortname."_comment_mustbe_name",
			    		            "std" => "You must be",
			    		            "type" => "text");
				
				$options[] = array( "desc" => "Change 'logged in' text",
			    		            "id" => $shortname."_comment_loggedin_name",
			    		            "std" => "logged in",
			    		            "type" => "text");
						
				$options[] = array( "desc" => "Change 'to post a comment' text",
			    		            "id" => $shortname."_comment_postcomment_name",
			    		            "std" => "to post a comment.",
			    		            "type" => "text");
						
				$options[] = array( "desc" => "Change Logout text",
			    		            "id" => $shortname."_comment_logout_name",
			    		            "std" => "Logout",
			    		            "type" => "text");
						
				$options[] = array( "desc" => "Change name text",
			    		            "id" => $shortname."_comment_name_name",
			    		            "std" => "Name",
			    		            "type" => "text");
						
				$options[] = array( "desc" => "Change mail text",
			    		            "id" => $shortname."_comment_mail_name",
			    		            "std" => "Mail",
			    		            "type" => "text");
						
				$options[] = array( "desc" => "Change website text",
			    		            "id" => $shortname."_comment_website_name",
			    		            "std" => "Website",
			    		            "type" => "text");
						
				$options[] = array( "desc" => "Change add comment text",
			    		            "id" => $shortname."_comment_addcomment_name",
			    		            "std" => "Add Comment",
			    		            "type" => "text");
						
				$options[] = array( "desc" => "Change 'reply' to threaded comment text",
			    		            "id" => $shortname."_comment_justreply_name",
			    		            "std" => "Reply",
			    		            "type" => "text");
						
				$options[] = array( "desc" => "Change 'edit' comment text, only visible to administrators",
			    		            "id" => $shortname."_comment_edit_name",
			    	            	"std" => "Edit",
			    		            "type" => "text");
						
				$options[] = array( "desc" => "Change 'delete' comment text, only visible to administrators",
			    		            "id" => $shortname."_comment_delete_name",
			    		            "std" => "Delete",
			    		            "type" => "text");
						
				$options[] = array( "desc" => "Change 'spam' comment text, only visible to administrators",
			    		            "id" => $shortname."_comment_spam_name",
			    		            "std" => "Spam",
			    		            "type" => "text");
						
			$options[] = array(	"type" => "subheadingbottom");
			
			$options[] = array(	"name" => "Pagination Text",
						        "toggle" => "true",
								"type" => "subheadingtop");
						
				$options[] = array(	"desc" => "Change first page text",
			    		            "id" => $shortname."_pagination_first_name",
			    	 	            "std" => "First",
			    		            "type" => "text");
						
				$options[] = array( "desc" => "Change last page text",
			    		            "id" => $shortname."_pagination_last_name",
			    		            "std" => "Last",
			    		            "type" => "text");
						
			$options[] = array(	"type" => "subheadingbottom");
			
			$options[] = array(	"name" => "Relative Dates Text",
						        "toggle" => "true",
								"type" => "subheadingtop");
						
				$options[] = array(	"desc" => "Change Posted text",
			    		            "id" => $shortname."_relative_posted",
			    	 	            "std" => "Posted",
			    		            "type" => "text");
				
				$options[] = array(	"desc" => "Change Ago text",
			    		            "id" => $shortname."_relative_ago",
			    	 	            "std" => "ago",
			    		            "type" => "text");
				
				$options[] = array(	"desc" => "Change plural text&nbsp;  [ i.e. hour &rarr; hours ]",
			    		            "id" => $shortname."_relative_s",
			    	 	            "std" => "s",
			    		            "type" => "text");
									
				$options[] = array( "desc" => "Change Year text",
			    		            "id" => $shortname."_relative_year",
			    		            "std" => "year",
			    		            "type" => "text");
									
				$options[] = array( "desc" => "Change Month text",
			    		            "id" => $shortname."_relative_month",
			    		            "std" => "month",
			    		            "type" => "text");
									
				$options[] = array( "desc" => "Change Week text",
			    		            "id" => $shortname."_relative_week",
			    		            "std" => "week",
			    		            "type" => "text");
									
				$options[] = array( "desc" => "Change Day text",
			    		            "id" => $shortname."_relative_day",
			    		            "std" => "day",
			    		            "type" => "text");
									
				$options[] = array( "desc" => "Change Hour text",
			    		            "id" => $shortname."_relative_hour",
			    		            "std" => "hour",
			    		            "type" => "text");
									
				$options[] = array( "desc" => "Change Minute text",
			    		            "id" => $shortname."_relative_minute",
			    		            "std" => "minute",
			    		            "type" => "text");
									
				$options[] = array( "desc" => "Change Second text",
			    		            "id" => $shortname."_relative_second",
			    		            "std" => "second",
			    		            "type" => "text");
									
				$options[] = array( "desc" => "Change Moments text",
			    		            "id" => $shortname."_relative_moments",
			    		            "std" => "moments",
			    		            "type" => "text");
						
			$options[] = array(	"type" => "subheadingbottom");
						
		$options[] = array(	"type" => "maintablebreak");
						
$options[] = array(	"type" => "maintablebottom");

?>