<?php
/**
 * A unique identifier is defined to store the options in the database and reference them from the theme.
 * By default it uses the theme name, in lowercase and without spaces, but this can be changed if needed.
 * If the identifier changes, it'll appear as if the options have been reset.
 * 
 */

if(!function_exists('optionsframework_option_name')) {
	function optionsframework_option_name() {
		// This gets the theme name from the stylesheet (lowercase and without spaces)
		$themename = 'bueno';
		
		$optionsframework_settings = get_option('optionsframework');
		$optionsframework_settings['id'] = $themename;
		update_option('optionsframework', $optionsframework_settings);
		
	}
}

/**
 * Defines an array of options that will be used to generate the settings page and be saved in the database.
 * When creating the "id" fields, make sure to use all lowercase and no spaces.
 *  
 */

if(!function_exists('optionsframework_options')) {

	function optionsframework_options() {
	
		// Logo type
		$logo_type = array(
			"image_logo" => __( "Image Logo", 'bueno'),
			"text_logo" => __( "Text Logo", 'bueno')
		);
		
		// Search box in the header
		$g_search_box = array(
			"yes" => __( "Yes", 'bueno'),
			"no" => __( "No", 'bueno')
		);

		// Breadcrumbs
		$g_breadcrumb = array(
			"yes" => __( "Yes", 'bueno'),
			"no" => __( "No", 'bueno')
		);
		
		// Superfish fade-in animation
		$sf_f_animation_array = array(
			"show" => __( "Enable fade-in animation", 'bueno'),
			"false" => __( "Disable fade-in animation", 'bueno')
		);
		
		// Superfish slide-down animation
		$sf_sl_animation_array = array(
			"show" => __( "Enable slide-down animation", 'bueno'),
			"false" => __( "Disable slide-down animation", 'bueno')
		);
		
		// Superfish animation speed
		$sf_speed_array = array(
			"slow" => __( "Slow", 'bueno'),
			"normal" => __( "Normal", 'bueno'),
			"fast" => __( "Fast", 'bueno')
		);
		
		// Superfish arrows markup
		$sf_arrows_array = array(
			"true" => __( "Yes", 'bueno'),
			"false" => __( "No", 'bueno')
		);
		
		// Slider show
		$sl_show_array = array(
			"yes" => __( "Show", 'bueno'),
			"no" => __( "Hide", 'bueno')
		);

		// Slider effects
		$sl_effect_array = array(
			"fade" => __( "Fade", 'bueno'),
			"slide" => __( "Slide", 'bueno')
		);

		// Slider slideshow
		$sl_slideshow_array = array(
			"true" => __( "Yes", 'bueno'),
			"false" => __( "No", 'bueno')
		);

		// Slider control nav
		$sl_control_array = array(
			"true" => __( "Yes", 'bueno'),
			"false" => __( "No", 'bueno')
		);

		// Slider direction nav
		$sl_direction_nav_array = array(
			"true" => __( "Yes", 'bueno'),
			"false" => __( "No", 'bueno')
		);

		// Slide as link
		$sl_as_link_array = array(
			"true" => __( "Yes", 'bueno'),
			"false" => __( "No", 'bueno')
		);

		// Slide as link
		$portf_format_array = array(
			"all" => __( "All Posts", 'bueno'),
			"images" => __( "Only Image Post Format", 'bueno')
		);


		// Slider number nav
		$sl_num_array = array('1' => '1', '2' => '2', '3' => '3', '4' => '4', '5' => '5', '6' => '6', '7' => '7', '8' => '8', '9' => '9', '10' => '10', '11' => '11', '12' => '12', '13' => '13', '14' => '14', '15' => '15');


		// Slider caption
		$sl_caption_array = array('show' => __( 'Show', 'bueno'), 'hide' => __( 'Hide', 'bueno') );

		// Slider caption title
		$sl_capt_title_array = array('show' => __( 'Show', 'bueno'), 'hide' => __( 'Hide', 'bueno') );

		// Slider caption excerpt
		$sl_capt_exc_array = array('show' => __( 'Show', 'bueno'), 'hide' => __( 'Hide', 'bueno') );

		// Slider caption button
		$sl_capt_btn_array = array('show' => __( 'Show', 'bueno'), 'hide' => __( 'Hide', 'bueno') );


		// Footer menu
		$footer_menu_array = array("true" => __( "Yes", 'bueno'), "false" => __( "No", 'bueno') );


		$post_sidebar_array = array("left" => __( "Left Sidebar", 'bueno'), "right" => __( "Right Sidebar", 'bueno') );
		
		// Columns number
		$post_cols = array(
			"two" => __( "Two columns", 'bueno'),
			"one" => __( "One column", 'bueno')
		);

		// Featured image on the blog.
		$post_image_array = array(
			"normal" => __( "Yes", 'bueno'),
			"noimg" => __( "No", 'bueno')
		);
		
		// Featured image size on the single page.
		$single_image_array = array(
			"normal" => __( "Yes", 'bueno'),
			"noimg" => __( "No", 'bueno')
		);
		
		// True/False options array for blog
		$post_opt_array = array("true" => __( "Yes", 'bueno'), "false" => __( "No", 'bueno') );
		
		
		
		
		
		// Pull all the categories into an array
		$options_categories = array();  
		$options_categories_obj = get_categories();
		foreach ($options_categories_obj as $category) {
				$options_categories[$category->cat_ID] = $category->cat_name;
		}
		
		$all_cats_array = array('from_all' => __( 'Select from all', 'duena' ) ) + $options_categories;
		
		// Pull all the pages into an array
		$options_pages = array();  
		$options_pages_obj = get_pages('sort_column=post_parent,menu_order');
		$options_pages[''] = __( "Select a page:", "bueno" );
		foreach ($options_pages_obj as $page) {
				$options_pages[$page->ID] = $page->post_title;
		}
			
		// If using image radio buttons, define a directory path
		$imagepath =  get_template_directory_uri() . '/inc/images/';
			
		$options = array();
		
		$options[] = array( "name" => __( "General", "bueno" ),
							"type" => "heading");

		$options['g_header_slogan'] = array( "name" => __( "Header slogan", "bueno" ),
							"desc" => __( "Enter additional slogan text (will be displayed at the right top corner of page)", "bueno" ),
							"id" => "g_header_slogan",
							"std" => __( "Hello, and welcome to my site!", "bueno" ),
							"type" => "text");

		$options['g_header_banner_img'] = array( "name" => __( "Header Banner image", "bueno" ),
							"desc" => __( "Upload Header Banner image (728x90px recommended)", "bueno" ),
							"id" => "g_header_banner_img",
							"type" => "upload");

		$options['g_header_banner_url'] = array( "name" => __( "Header Banner URL", "bueno" ),
							"desc" => __( "Enter Header Banner URL (can be empty, only image will be displayed)", "bueno" ),
							"id" => "g_header_banner_url",
							"type" => "text",
							"std" => "");	

		$options['g_search_box_id'] = array( "name" => __( "Display search box?", "bueno" ),
							"desc" => __( "Display search box in the header?", "bueno" ),
							"id" => "g_search_box_id",
							"type" => "radio",
							"std" => "yes",
							"options" => $g_search_box);
		
		$options['g_breadcrumbs_id'] = array( "name" => __( "Display breadcrumbs?", "bueno" ),
							"desc" => __( "Display breadcrumbs?", "bueno" ),
							"id" => "g_breadcrumbs_id",
							"type" => "radio",
							"std" => "yes",
							"options" => $g_breadcrumb);

		$options['g_sidebar_socials_title'] = array( "name" => __( "Sidebar socials block title", "bueno" ),
							"desc" => __( "Enter your sidebar socials block title (leave empty for display solcial block without title)", "bueno" ),
							"id" => "g_sidebar_socials_title",
							"type" => "text",
							"std" => __( "Get Social", "bueno" ));

		$options['g_twitter_url'] = array( "name" => __( "Twitter URL (for sidebar socials block)", "bueno" ),
							"desc" => __( "Enter your Twitter profile URL (leave empty for hide Twitter icon from sidebar)", "bueno" ),
							"id" => "g_twitter_url",
							"type" => "text",
							"std" => "#");

		$options['g_facebook_url'] = array( "name" => __( "Facebook URL (for sidebar socials block)", "bueno" ),
							"desc" => __( "Enter your Facebook page URL (leave empty for hide Facebook icon from sidebar)", "bueno" ),
							"id" => "g_facebook_url",
							"type" => "text",
							"std" => "#");

		$options['g_linkedin_url'] = array( "name" => __( "Linkedin URL (for sidebar socials block)", "bueno" ),
							"desc" => __( "Enter your Linkedin profile URL (leave empty for hide Linkedin icon from sidebar)", "bueno" ),
							"id" => "g_linkedin_url",
							"type" => "text",
							"std" => "#");

		$options['g_google_url'] = array( "name" => __( "Google+ URL (for sidebar socials block)", "bueno" ),
							"desc" => __( "Enter your Google+ profile URL (leave empty for hide Google+ icon from sidebar)", "bueno" ),
							"id" => "g_google_url",
							"type" => "text",
							"std" => "#");

		$options['g_pinterest_url'] = array( "name" => __( "Pinterest URL (for sidebar socials block)", "bueno" ),
							"desc" => __( "Enter your Pinterest profile URL (leave empty for hide Pinterest icon from sidebar)", "bueno" ),
							"id" => "g_pinterest_url",
							"type" => "text",
							"std" => "#");

		$options['g_rss_url'] = array( "name" => __( "RSS Feed URL (for sidebar socials block)", "bueno" ),
							"desc" => __( "Enter your RSS Feed URL (leave empty for hide RSS icon from sidebar)", "bueno" ),
							"id" => "g_rss_url",
							"type" => "text",
							"std" => "#");

		
		$options[] = array( "name" => __( "Logo & Favicon", "bueno" ),
							"type" => "heading");
		
		$options['logo_type'] = array( "name" => __( "What kind of logo?", "bueno" ),
							"desc" => __( "Select whether you want your main logo to be an image or text. If you select 'image' you can put in the image url in the next option, and if you select 'text' your Site Title will show instead.", "bueno" ),
							"id" => "logo_type",
							"std" => "text_logo",
							"type" => "radio",
							"options" => $logo_type);
		
		$options['logo_url'] = array( "name" => __( "Logo Image Path", "bueno" ),
							"desc" => __( "Upload logo image", "bueno" ),
							"id" => "logo_url",
							"type" => "upload");
		
		$options['favicon'] = array( "name" => __( "Favicon", "bueno" ),
							"desc" => __( "Click Upload or Enter the direct path to your favicon.", "bueno" ),
							"id" => "favicon",
							"std" => "",
							"type" => "upload");
		
		$options[] = array( "name" => __( "Navigation", "bueno" ),
							"type" => "heading");
		
		$options['sf_delay'] = array( "name" => __( "Delay", "bueno" ),
							"desc" => __( "miliseconds delay on mouseout.", "bueno" ),
							"id" => "sf_delay",
							"std" => "1000",
							"class" => "mini",
							"type" => "text");
		
		$options['sf_f_animation'] = array( "name" => __( "Fade-in animation", "bueno" ),
							"desc" => __( "Fade-in animation.", "bueno" ),
							"id" => "sf_f_animation",
							"std" => "show",
							"type" => "radio",
							"options" => $sf_f_animation_array);
		
		$options['sf_sl_animation'] = array( "name" => __( "Slide-down animation", "bueno" ),
							"desc" => __( "Slide-down animation.", "bueno" ),
							"id" => "sf_sl_animation",
							"std" => "show",
							"type" => "radio",
							"options" => $sf_sl_animation_array);
		
		$options['sf_speed'] = array( "name" => __( "Speed", "bueno" ),
							"desc" => __( "Animation speed.", "bueno" ),
							"id" => "sf_speed",
							"type" => "select",
							"std" => "normal",
							"class" => "tiny", //mini, tiny, small
							"options" => $sf_speed_array);
		
		$options['sf_arrows'] = array( "name" => __( "Arrows markup", "bueno" ),
							"desc" => __( "Do you want to generate arrow mark-up?", "bueno" ),
							"id" => "sf_arrows",
							"std" => "false",
							"type" => "radio",
							"options" => $sf_arrows_array);
		
		



		$options[] = array( "name" => __( "Slider (visualisation)", "bueno" ),
							"type" => "heading");
 
		$options['sl_show'] = array( "name" => __( "Show slider", "bueno" ),
                            "desc" => __( "Show / Hide slider on home page", "bueno" ),
                            "id" => "sl_show",
                            "std" => "yes",
                            "type" => "radio",
                            "class" => "tiny", //mini, tiny, small
                            "options" => $sl_show_array);

        $options['sl_effect'] = array( "name" => __( "Sliding effect", "bueno" ),
                            "desc" => __( "Select your animation type", "bueno" ),
                            "id" => "sl_effect",
                            "std" => "fade",
                            "type" => "select",
                            "class" => "tiny", //mini, tiny, small
                            "options" => $sl_effect_array);

        $options['sl_slideshow'] = array( "name" => __( "Slideshow", "bueno" ),
                            "desc" => __( "Animate slider automatically?", "bueno" ),
                            "id" => "sl_slideshow",
                            "std" => "true",
                            "type" => "radio",
                            "class" => "tiny", //mini, tiny, small
                            "options" => $sl_slideshow_array);

        $options['sl_control'] = array( "name" => __( "Paging control", "bueno" ),
                            "desc" => __( "Create navigation for paging control of each slide?", "bueno" ),
                            "id" => "sl_control",
                            "std" => "true",
                            "type" => "radio",
                            "class" => "tiny", //mini, tiny, small
                            "options" => $sl_control_array);

        $options['sl_direction_nav'] = array( "name" => __( "Previous/Next navigation", "bueno" ),
                            "desc" => __( "Create controls for previous/next navigation?", "bueno" ),
                            "id" => "sl_direction_nav",
                            "std" => "true",
                            "type" => "radio",
                            "class" => "tiny", //mini, tiny, small
                            "options" => $sl_direction_nav_array);

        


		$options[] = array( "name" => __( "Slider (content)", "bueno" ),
							"type" => "heading");
 
		$options['sl_num'] = array( "name" => __( "How many slides to show?", "bueno" ),
                            "desc" => __( "This is how many slides will be displayed. Slider items are selected from the blog posts with featured image (featured image is displaying as slide)", "bueno" ),
                            "id" => "sl_num",
                            "std" => "10",
                            "type" => "select",
                            "class" => "tiny", //mini, tiny, small
                            "options" => $sl_num_array);

		$options['sl_category'] = array( "name" => __( "Which category to pull from?", "bueno" ),
                            "desc" => __( "Select the category you'd like to pull slides from.", "bueno" ),
                            "id" => "sl_category",
                            "std" => "",
                            "type" => "select",
                            "options" => $all_cats_array);

		$options['sl_as_link'] = array( "name" => __( "Slide as link to the post", "bueno" ),
                            "desc" => __( "Add/remove permalink to slides", "bueno" ),
                            "id" => "sl_as_link",
                            "std" => "true",
                            "type" => "radio",
                            "class" => "tiny",
                            "options" => $sl_as_link_array);

		$options['sl_caption'] = array( "name" => __( "Show/Hide slide caption", "bueno" ),
                            "desc" => __( "Show/Hide slide caption.", "bueno" ),
                            "id" => "sl_caption",
                            "std" => "hide",
                            "type" => "radio",
                            "class" => "tiny",
                            "options" => $sl_caption_array);

		$options['sl_capt_title'] = array( "name" => __( "Show/Hide slide caption title", "bueno" ),
                            "desc" => __( "Show/Hide slide caption title.", "bueno" ),
                            "id" => "sl_capt_title",
                            "std" => "show",
                            "type" => "radio",
                            "class" => "tiny hidden",
                            "options" => $sl_capt_title_array);

		$options['sl_capt_exc'] = array( "name" => __( "Show/Hide slide caption excerpt", "bueno" ),
                            "desc" => __( "Show/Hide slide caption excerpt.", "bueno" ),
                            "id" => "sl_capt_exc",
                            "std" => "show",
                            "type" => "radio",
                            "class" => "tiny hidden",
                            "options" => $sl_capt_exc_array);

		$options['sl_capt_exc_length'] = array( "name" => __( "Slide caption excerpt length", "bueno" ),
                            "desc" => __( "How many words are displayed in the excerpt?", "bueno" ),
                            "id" => "sl_capt_exc_length",
                            "std" => "20",
                            "type" => "text",
                            "class" => "tiny hidden");

		$options['sl_capt_btn'] = array( "name" => __( "Show/Hide slide caption button", "bueno" ),
                            "desc" => __( "Show/Hide slide caption button", "bueno" ),
                            "id" => "sl_capt_btn",
                             "std" => "show",
                            "type" => "radio",
                            "class" => "tiny hidden",
                            "options" => $sl_capt_btn_array);

		$options['sl_capt_btn_txt'] = array( "name" => __( "Slide caption button text", "bueno" ),
                            "desc" => __( "Slide caption button text", "bueno" ),
                            "id" => "sl_capt_btn_txt",
                            "std" => __( 'Read more', 'bueno' ),
                            "type" => "text",
                            "class" => "tiny hidden");


		
		$options[] = array( "name" => __( "Blog", "bueno" ),
							"type" => "heading");
		
		$options['blog_sidebar_pos'] = array( "name" => __( "Sidebar position", "bueno" ),
							"desc" => __( "Choose sidebar position.", "bueno" ),
							"id" => "blog_sidebar_pos",
							"std" => "right",
							"type" => "radio",
							"options" => $post_sidebar_array);

		$options['blog_page_columns'] = array( "name" => __( "Columns number", "bueno" ),
							"desc" => __( "Select columns number on blog page.", "bueno" ),
							"id" => "blog_page_columns",
							"std" => "two",
							"type" => "radio",
							"options" => $post_cols);
		
		$options['post_image_size'] = array( "name" => __( "Show featured image on Blog page", "bueno" ),
							"desc" => __( "Show or hide featured image on Blog page", "bueno" ),
							"id" => "post_image_size",
							"type" => "select",
							"std" => "normal",
							"class" => "small", //mini, tiny, small
							"options" => $post_image_array);
		
		$options['single_image_size'] = array( "name" => __( "Show featured image on Single post page", "bueno" ),
							"desc" => __( "Show or hide featured image on Single post page", "bueno" ),
							"id" => "single_image_size",
							"type" => "select",
							"std" => "normal",
							"class" => "small", //mini, tiny, small
							"options" => $single_image_array);
		
		$options['post_meta_author'] = array( "name" => __( "Show post author", "bueno" ),
							"desc" => __( "Show post author link in post meta info", "bueno" ),
							"id" => "post_meta_author",
							"std" => "true",
							"type" => "radio",
							"options" => $post_opt_array);

		$options['post_meta_date'] = array( "name" => __( "Show post date", "bueno" ),
							"desc" => __( "Show post date in post meta info", "bueno" ),
							"id" => "post_meta_date",
							"std" => "true",
							"type" => "radio",
							"options" => $post_opt_array);

		$options['post_meta_comments'] = array( "name" => __( "Show post comments number", "bueno" ),
							"desc" => __( "Show post comments number in post meta info", "bueno" ),
							"id" => "post_meta_comments",
							"std" => "false",
							"type" => "radio",
							"options" => $post_opt_array);

		$options['post_meta_categories'] = array( "name" => __( "Show post categories", "bueno" ),
							"desc" => __( "Show post categories in post meta info", "bueno" ),
							"id" => "post_meta_categories",
							"std" => "false",
							"type" => "radio",
							"options" => $post_opt_array);
		
		$options['post_excerpt'] = array( "name" => __( "Enable excerpt for blog posts?", "bueno" ),
							"desc" => __( "Enable or Disable excerpt for blog posts.", "bueno" ),
							"id" => "post_excerpt",
							"std" => "true",
							"type" => "radio",
							"options" => $post_opt_array);

		$options['post_button'] = array( "name" => __( "Enable read more button for blog posts?", "bueno" ),
							"desc" => __( "Enable or Disable read more button for blog posts.", "bueno" ),
							"id" => "post_button",
							"std" => "true",
							"type" => "radio",
							"options" => $post_opt_array);

		$options['post_author'] = array( "name" => __( "Show author bio on single post page?", "bueno" ),
							"desc" => __( "Show or hide author bio on single post page.", "bueno" ),
							"id" => "post_author",
							"std" => "true",
							"type" => "radio",
							"options" => $post_opt_array);
		

		$options['blog_related'] = array( "name" => __( "Related Posts Title", "bueno" ),
							"desc" => __( "Enter Your Title used on Single Post page for related posts.", "bueno" ),
							"id" => "blog_related",
							"std" => __( 'Related posts', 'bueno' ),
							"type" => "text");
		

		$options[] = array( "name" => __( "Portfolio", "bueno" ),
							"type" => "heading");

		$options['g_portfolio_per_page'] = array( "name" => __( "Items per page", "bueno" ),
							"desc" => __( "Enter number items per page on portfolio pages", "bueno" ),
							"id" => "g_portfolio_per_page",
							"type" => "text",
							"std" => "8");

		$options['g_portfolio_format'] = array( "name" => __( "Portfolio items are selected from:", "bueno" ),
							"desc" => __( "If 'All posts' - items on portfolio page template are selected from all posts with featured image, If 'Only Image Post Format' - items on portfolio page template are selected from posts with Image post format with featured image", "bueno" ),
							"id" => "g_portfolio_format",
							"type" => "select",
							"std" => "all",
							"class" => "small", //mini, tiny, small
							"options" => $portf_format_array);

		$options['g_portfolio_cat'] = array( "name" => __( "Select category for portfolio page", "bueno" ),
							"desc" => __( "Select category to pull portfolio page from", "bueno" ),
							"id" => "g_portfolio_cat",
							"type" => "select",
							"std" => "",
							"options" => $all_cats_array);

		$options['g_portfolio_preview_btn'] = array( "name" => __( "Preview button", "bueno" ),
							"desc" => __( "Add preview button to portfolio items", "bueno" ),
							"id" => "g_portfolio_preview_btn",
							"std" => "false",
							"type" => "radio",
							"options" => $post_opt_array);

		$options[] = array( "name" => __( "Footer", "bueno" ),
							"type" => "heading");
		
		$options['footer_text'] = array( "name" => __( "Footer copyright text", "bueno" ),
							"desc" => __( "Enter text used in the right side of the footer. HTML tags are allowed.", "bueno" ),
							"id" => "footer_text",
							"std" => "",
							"type" => "textarea");
		
		$options['footer_menu'] = array( "name" => __( "Display Footer menu?", "bueno" ),
							"desc" => __( "Do you want to display footer menu?", "bueno" ),
							"id" => "footer_menu",
							"std" => "false",
							"type" => "radio",
							"options" => $footer_menu_array);
					
		
		return $options;
	}

}

/* 
 * This is an example of how to add custom scripts to the options panel.
 * This example shows/hides an option when a checkbox is clicked.
 */

add_action('optionsframework_custom_scripts', 'optionsframework_custom_scripts');


if(!function_exists('optionsframework_custom_scripts')) {

	function optionsframework_custom_scripts() { ?>

		<script type="text/javascript">
		jQuery(document).ready(function($) {

			$('#example_showhidden').click(function() {
					$('#section-example_text_hidden').fadeToggle(400);
			});
			
			if ($('#example_showhidden:checked').val() !== undefined) {
				$('#section-example_text_hidden').show();
			}
			
		});
		</script>

		<?php
		}

}


/**
* Front End Customizer
*
* WordPress 3.4 Required
*/
add_action( 'customize_register', 'bueno_register' );

if(!function_exists('bueno_register')) {

	function bueno_register($wp_customize) {
		/**
		 * This is optional, but if you want to reuse some of the defaults
		 * or values you already have built in the options panel, you
		 * can load them into $options for easy reference
		 */
		$options = optionsframework_options();
		
		
		
		/*-----------------------------------------------------------------------------------*/
		/*	General
		/*-----------------------------------------------------------------------------------*/
		$wp_customize->add_section( 'bueno_header', array(
			'title' => __( 'General', 'bueno' ),
			'priority' => 200
		));
		
		/* Header slogan */
		$wp_customize->add_setting( 'bueno[g_header_slogan]', array(
				'default' => $options['g_header_slogan']['std'],
				'type' => 'option'
		) );
		$wp_customize->add_control( 'bueno_g_header_slogan', array(
				'label' => $options['g_header_slogan']['name'],
				'section' => 'bueno_header',
				'settings' => 'bueno[g_header_slogan]',
				'type' => $options['g_header_slogan']['type'],
				'priority' => 11
		) );

		/* Header banner image */
		$wp_customize->add_setting( 'bueno[g_header_banner_img]', array(
			'type' => 'option'
		) );
		
		$wp_customize->add_control( new WP_Customize_Image_Control( $wp_customize, 'g_header_banner_img', array(
			'label' => $options['g_header_banner_img']['name'],
			'section' => 'bueno_header',
			'settings' => 'bueno[g_header_banner_img]',
			'priority' => 12
		) ) );

		/* Header banner url */
		$wp_customize->add_setting( 'bueno[g_header_banner_url]', array(
				'default' => $options['g_header_banner_url']['std'],
				'type' => 'option'
		) );
		$wp_customize->add_control( 'bueno_g_header_banner_url', array(
				'label' => $options['g_header_banner_url']['name'],
				'section' => 'bueno_header',
				'settings' => 'bueno[g_header_banner_url]',
				'type' => $options['g_header_banner_url']['type'],
				'priority' => 13
		) );

		/* Search Box */
		$wp_customize->add_setting( 'bueno[g_search_box_id]', array(
				'default' => $options['g_search_box_id']['std'],
				'type' => 'option'
		) );
		$wp_customize->add_control( 'bueno_g_search_box_id', array(
				'label' => $options['g_search_box_id']['name'],
				'section' => 'bueno_header',
				'settings' => 'bueno[g_search_box_id]',
				'type' => $options['g_search_box_id']['type'],
				'choices' => $options['g_search_box_id']['options'],
				'priority' => 14
		) );
		
		/* Breadcrumbs */
		$wp_customize->add_setting( 'bueno[g_breadcrumbs_id]', array(
				'default' => $options['g_breadcrumbs_id']['std'],
				'type' => 'option'
		) );
		$wp_customize->add_control( 'bueno_g_breadcrumbs_id', array(
				'label' => $options['g_breadcrumbs_id']['name'],
				'section' => 'bueno_header',
				'settings' => 'bueno[g_breadcrumbs_id]',
				'type' => $options['g_breadcrumbs_id']['type'],
				'choices' => $options['g_breadcrumbs_id']['options'],
				'priority' => 15
		) );	

		/* Sidebar social - title */
		$wp_customize->add_setting( 'bueno[g_sidebar_socials_title]', array(
				'default' => $options['g_sidebar_socials_title']['std'],
				'type' => 'option'
		) );
		$wp_customize->add_control( 'bueno_g_sidebar_socials_title', array(
				'label' => $options['g_sidebar_socials_title']['name'],
				'section' => 'bueno_header',
				'settings' => 'bueno[g_sidebar_socials_title]',
				'type' => $options['g_sidebar_socials_title']['type'],
				'priority' => 18
		) );

		/* Sidebar social - twitter */
		$wp_customize->add_setting( 'bueno[g_twitter_url]', array(
				'default' => $options['g_twitter_url']['std'],
				'type' => 'option'
		) );
		$wp_customize->add_control( 'bueno_g_twitter_url', array(
				'label' => $options['g_twitter_url']['name'],
				'section' => 'bueno_header',
				'settings' => 'bueno[g_twitter_url]',
				'type' => $options['g_twitter_url']['type'],
				'priority' => 19
		) );

		/* Sidebar social - facebook */
		$wp_customize->add_setting( 'bueno[g_facebook_url]', array(
				'default' => $options['g_facebook_url']['std'],
				'type' => 'option'
		) );
		$wp_customize->add_control( 'bueno_g_facebook_url', array(
				'label' => $options['g_facebook_url']['name'],
				'section' => 'bueno_header',
				'settings' => 'bueno[g_facebook_url]',
				'type' => $options['g_facebook_url']['type'],
				'priority' => 20
		) );

		/* Sidebar social - linkedin */
		$wp_customize->add_setting( 'bueno[g_linkedin_url]', array(
				'default' => $options['g_linkedin_url']['std'],
				'type' => 'option'
		) );
		$wp_customize->add_control( 'bueno_g_linkedin_url', array(
				'label' => $options['g_linkedin_url']['name'],
				'section' => 'bueno_header',
				'settings' => 'bueno[g_linkedin_url]',
				'type' => $options['g_linkedin_url']['type'],
				'priority' => 21
		) );

		/* Sidebar social - google */
		$wp_customize->add_setting( 'bueno[g_google_url]', array(
				'default' => $options['g_google_url']['std'],
				'type' => 'option'
		) );
		$wp_customize->add_control( 'bueno_g_google_url', array(
				'label' => $options['g_google_url']['name'],
				'section' => 'bueno_header',
				'settings' => 'bueno[g_google_url]',
				'type' => $options['g_google_url']['type'],
				'priority' => 22
		) );

		/* Sidebar social - pinterest */
		$wp_customize->add_setting( 'bueno[g_pinterest_url]', array(
				'default' => $options['g_pinterest_url']['std'],
				'type' => 'option'
		) );
		$wp_customize->add_control( 'bueno_g_pinterest_url', array(
				'label' => $options['g_pinterest_url']['name'],
				'section' => 'bueno_header',
				'settings' => 'bueno[g_pinterest_url]',
				'type' => $options['g_pinterest_url']['type'],
				'priority' => 23
		) );

		/* Sidebar social - rss */
		$wp_customize->add_setting( 'bueno[g_rss_url]', array(
				'default' => $options['g_rss_url']['std'],
				'type' => 'option'
		) );
		$wp_customize->add_control( 'bueno_g_rss_url', array(
				'label' => $options['g_twitter_url']['name'],
				'section' => 'bueno_header',
				'settings' => 'bueno[g_rss_url]',
				'type' => $options['g_rss_url']['type'],
				'priority' => 24
		) );
		
		/*-----------------------------------------------------------------------------------*/
		/*	Logo
		/*-----------------------------------------------------------------------------------*/
		
		$wp_customize->add_section( 'bueno_logo', array(
			'title' => __( 'Logo & Favicon', 'bueno' ),
			'priority' => 201
		) );
		
		/* Logo Type */
		$wp_customize->add_setting( 'bueno[logo_type]', array(
				'default' => $options['logo_type']['std'],
				'type' => 'option'
		) );
		$wp_customize->add_control( 'bueno_logo_type', array(
				'label' => $options['logo_type']['name'],
				'section' => 'bueno_logo',
				'settings' => 'bueno[logo_type]',
				'type' => $options['logo_type']['type'],
				'choices' => $options['logo_type']['options'],
				'priority' => 11
		) );

		/* Logo Path */
		$wp_customize->add_setting( 'bueno[logo_url]', array(
			'type' => 'option'
		) );
		
		$wp_customize->add_control( new WP_Customize_Image_Control( $wp_customize, 'logo_url', array(
			'label' => $options['logo_url']['name'],
			'section' => 'bueno_logo',
			'settings' => 'bueno[logo_url]',
			'priority' => 12
		) ) );

		/* Favicon Path */
		$wp_customize->add_setting( 'bueno[favicon]', array(
			'type' => 'option'
		) );
		
		$wp_customize->add_control( new WP_Customize_Image_Control( $wp_customize, 'favicon', array(
			'label' => $options['favicon']['name'],
			'section' => 'bueno_logo',
			'settings' => 'bueno[favicon]',
			'priority' => 13
		) ) );
		
		/*-----------------------------------------------------------------------------------*/
		/*	Navigation
		/*-----------------------------------------------------------------------------------*/
		
		/* Nav Delay */
		$wp_customize->add_setting( 'bueno[sf_delay]', array(
				'default' => $options['sf_delay']['std'],
				'type' => 'option'
		) );
		$wp_customize->add_control( 'bueno_sf_delay', array(
				'label' => $options['sf_delay']['name'],
				'section' => 'nav',
				'settings' => 'bueno[sf_delay]',
				'type' => $options['sf_delay']['type'],
				'priority' => 11
		) );

		/* nav_fade_in */
		$wp_customize->add_setting( 'bueno[sf_f_animation]', array(
				'default' => $options['sf_f_animation']['std'],
				'type' => 'option'
		) );
		$wp_customize->add_control( 'bueno_sf_f_animation', array(
				'label' => $options['sf_f_animation']['name'],
				'section' => 'nav',
				'settings' => 'bueno[sf_f_animation]',
				'type' => $options['sf_f_animation']['type'],
				'choices' => $options['sf_f_animation']['options'],
				'priority' => 12
		) );

		/* nav_slide_down */
		$wp_customize->add_setting( 'bueno[sf_sl_animation]', array(
				'default' => $options['sf_sl_animation']['std'],
				'type' => 'option'
		) );
		$wp_customize->add_control( 'bueno_sf_sl_animation', array(
				'label' => $options['sf_sl_animation']['name'],
				'section' => 'nav',
				'settings' => 'bueno[sf_sl_animation]',
				'type' => $options['sf_sl_animation']['type'],
				'choices' => $options['sf_sl_animation']['options'],
				'priority' => 13
		) );

		/* nav_speed */
		$wp_customize->add_setting( 'bueno[sf_speed]', array(
				'default' => $options['sf_speed']['std'],
				'type' => 'option'
		) );
		$wp_customize->add_control( 'bueno_sf_speed', array(
				'label' => $options['sf_speed']['name'],
				'section' => 'nav',
				'settings' => 'bueno[sf_speed]',
				'type' => $options['sf_speed']['type'],
				'choices' => $options['sf_speed']['options'],
				'priority' => 14
		) );

		/* Nav Arrows */
		$wp_customize->add_setting( 'bueno[sf_arrows]', array(
				'default' => $options['sf_arrows']['std'],
				'type' => 'option'
		) );
		$wp_customize->add_control( 'bueno_sf_arrows', array(
				'label' => $options['sf_arrows']['name'],
				'section' => 'nav',
				'settings' => 'bueno[sf_arrows]',
				'type' => $options['sf_arrows']['type'],
				'choices' => $options['sf_arrows']['options'],
				'priority' => 15
		) );


		/*-----------------------------------------------------------------------------------*/
		/*  Slider (visualisation)
		/*-----------------------------------------------------------------------------------*/
 
		$wp_customize->add_section( 'bueno_slider_visual', array(
		    'title' => __( 'Slider (visualisation)', 'bueno' ),
		    'priority' => 202
		) );

		/* Slider Show */
		$wp_customize->add_setting( 'bueno[sl_show]', array(
		        'default' => $options['sl_show']['std'],
		        'type' => 'option'
		) );
		$wp_customize->add_control( 'bueno_sl_show', array(
		        'label' => $options['sl_show']['name'],
		        'section' => 'bueno_slider_visual',
		        'settings' => 'bueno[sl_show]',
		        'type' => $options['sl_show']['type'],
		        'choices' => $options['sl_show']['options'],
		        'priority' => 11
		) );
		 
		/* Slider Effect */
		$wp_customize->add_setting( 'bueno[sl_effect]', array(
		        'default' => $options['sl_effect']['std'],
		        'type' => 'option'
		) );
		$wp_customize->add_control( 'bueno_sl_effect', array(
		        'label' => $options['sl_effect']['name'],
		        'section' => 'bueno_slider_visual',
		        'settings' => 'bueno[sl_effect]',
		        'type' => $options['sl_effect']['type'],
		        'choices' => $options['sl_effect']['options'],
		        'priority' => 12
		) );

		/* Slider Slideshow */
		$wp_customize->add_setting( 'bueno[sl_slideshow]', array(
		        'default' => $options['sl_slideshow']['std'],
		        'type' => 'option'
		) );
		$wp_customize->add_control( 'bueno_sl_slideshow', array(
		        'label' => $options['sl_slideshow']['name'],
		        'section' => 'bueno_slider_visual',
		        'settings' => 'bueno[sl_slideshow]',
		        'type' => $options['sl_slideshow']['type'],
		        'choices' => $options['sl_slideshow']['options'],
		        'priority' => 14
		) );

		/* Slider Controls */
		$wp_customize->add_setting( 'bueno[sl_control]', array(
		        'default' => $options['sl_control']['std'],
		        'type' => 'option'
		) );
		$wp_customize->add_control( 'bueno_sl_control', array(
		        'label' => $options['sl_control']['name'],
		        'section' => 'bueno_slider_visual',
		        'settings' => 'bueno[sl_control]',
		        'type' => $options['sl_control']['type'],
		        'choices' => $options['sl_control']['options'],
		        'priority' => 15
		) );

		/* Slider Effect */
		$wp_customize->add_setting( 'bueno[sl_direction_nav]', array(
		        'default' => $options['sl_direction_nav']['std'],
		        'type' => 'option'
		) );
		$wp_customize->add_control( 'bueno_sl_direction_nav', array(
		        'label' => $options['sl_direction_nav']['name'],
		        'section' => 'bueno_slider_visual',
		        'settings' => 'bueno[sl_direction_nav]',
		        'type' => $options['sl_direction_nav']['type'],
		        'choices' => $options['sl_direction_nav']['options'],
		        'priority' => 16
		) );
 

		/*-----------------------------------------------------------------------------------*/
		/*  Slider (content)
		/*-----------------------------------------------------------------------------------*/
 
		$wp_customize->add_section( 'bueno_slider_content', array(
		    'title' => __( 'Slider (content)', 'bueno' ),
		    'priority' => 203
		) );
		
		/* Slider Number */
		$wp_customize->add_setting( 'bueno[sl_num]', array(
		        'default' => $options['sl_num']['std'],
		        'type' => 'option'
		) );
		$wp_customize->add_control( 'bueno_sl_num', array(
		        'label' => $options['sl_num']['name'],
		        'section' => 'bueno_slider_content',
		        'settings' => 'bueno[sl_num]',
		        'type' => $options['sl_num']['type'],
		        'choices' => $options['sl_num']['options'],
		        'priority' => 11
		) );

		/* Slider Category */
		$wp_customize->add_setting( 'bueno[sl_category]', array(
		        'default' => $options['sl_category']['std'],
		        'type' => 'option'
		) );
		$wp_customize->add_control( 'bueno_sl_category', array(
		        'label' => $options['sl_category']['name'],
		        'section' => 'bueno_slider_content',
		        'settings' => 'bueno[sl_category]',
		        'type' => $options['sl_category']['type'],
		        'priority' => 12
		) );

		/* Slider Link */
		$wp_customize->add_setting( 'bueno[sl_as_link]', array(
		        'default' => $options['sl_as_link']['std'],
		        'type' => 'option'
		) );
		$wp_customize->add_control( 'bueno_sl_as_link', array(
		        'label' => $options['sl_as_link']['name'],
		        'section' => 'bueno_slider_content',
		        'settings' => 'bueno[sl_as_link]',
		        'type' => $options['sl_as_link']['type'],
		        'choices' => $options['sl_as_link']['options'],
		        'priority' => 13
		) );

		/* Slider Caption */
		$wp_customize->add_setting( 'bueno[sl_caption]', array(
		        'default' => $options['sl_caption']['std'],
		        'type' => 'option'
		) );
		$wp_customize->add_control( 'bueno_sl_caption', array(
		        'label' => $options['sl_caption']['name'],
		        'section' => 'bueno_slider_content',
		        'settings' => 'bueno[sl_caption]',
		        'type' => $options['sl_caption']['type'],
		        'choices' => $options['sl_caption']['options'],
		        'priority' => 14
		) );

		/* Slider Caption Title */
		$wp_customize->add_setting( 'bueno[sl_capt_title]', array(
		        'default' => $options['sl_capt_title']['std'],
		        'type' => 'option'
		) );
		$wp_customize->add_control( 'bueno_sl_capt_title', array(
		        'label' => $options['sl_capt_title']['name'],
		        'section' => 'bueno_slider_content',
		        'settings' => 'bueno[sl_capt_title]',
		        'type' => $options['sl_capt_title']['type'],
		        'choices' => $options['sl_capt_title']['options'],
		        'priority' => 15
		) );

		/* Slider Captiopn Excerpt */
		$wp_customize->add_setting( 'bueno[sl_capt_exc]', array(
		        'default' => $options['sl_capt_exc']['std'],
		        'type' => 'option'
		) );
		$wp_customize->add_control( 'bueno_sl_capt_exc', array(
		        'label' => $options['sl_capt_exc']['name'],
		        'section' => 'bueno_slider_content',
		        'settings' => 'bueno[sl_capt_exc]',
		        'type' => $options['sl_capt_exc']['type'],
		        'choices' => $options['sl_capt_exc']['options'],
		        'priority' => 16
		) );

		/* Slider Caption Excerpt Length */
		$wp_customize->add_setting( 'bueno[sl_capt_exc_length]', array(
		        'default' => $options['sl_capt_exc_length']['std'],
		        'type' => 'option'
		) );
		$wp_customize->add_control( 'bueno_sl_capt_exc_length', array(
		        'label' => $options['sl_capt_exc_length']['name'],
		        'section' => 'bueno_slider_content',
		        'settings' => 'bueno[sl_capt_exc_length]',
		        'type' => $options['sl_capt_exc_length']['type'],
		        'priority' => 17
		) );

		/* Slider Caption Button */
		$wp_customize->add_setting( 'bueno[sl_capt_btn]', array(
		        'default' => $options['sl_capt_btn']['std'],
		        'type' => 'option'
		) );
		$wp_customize->add_control( 'bueno_sl_capt_btn', array(
		        'label' => $options['sl_capt_btn']['name'],
		        'section' => 'bueno_slider_content',
		        'settings' => 'bueno[sl_capt_btn]',
		        'type' => $options['sl_capt_btn']['type'],
		        'choices' => $options['sl_capt_btn']['options'],
		        'priority' => 18
		) );
		
		/* Slider Caption Button Text */
		$wp_customize->add_setting( 'bueno[sl_capt_btn_txt]', array(
		        'default' => $options['sl_capt_btn_txt']['std'],
		        'type' => 'option'
		) );
		$wp_customize->add_control( 'bueno_sl_capt_btn_txt', array(
		        'label' => $options['sl_capt_btn_txt']['name'],
		        'section' => 'bueno_slider_content',
		        'settings' => 'bueno[sl_capt_btn_txt]',
		        'type' => $options['sl_capt_btn_txt']['type'],
		        'priority' => 19
		) );

		/*-----------------------------------------------------------------------------------*/
		/*	Blog
		/*-----------------------------------------------------------------------------------*/
		
		
		$wp_customize->add_section( 'bueno_blog', array(
				'title' => __( 'Blog', 'bueno' ),
				'priority' => 204
		) );

		/* Blog sidebar position */
		$wp_customize->add_setting( 'bueno[blog_sidebar_pos]', array(
				'default' => $options['blog_sidebar_pos']['std'],
				'type' => 'option'
		) );
		$wp_customize->add_control( 'bueno_blog_sidebar_pos', array(
				'label' => $options['blog_sidebar_pos']['name'],
				'section' => 'bueno_blog',
				'settings' => 'bueno[blog_sidebar_pos]',
				'type' => $options['blog_sidebar_pos']['type'],
				'choices' => $options['blog_sidebar_pos']['options'],
				'priority' => 11
		) );

		/* Blog columns number */
		$wp_customize->add_setting( 'bueno[blog_page_columns]', array(
				'default' => $options['blog_page_columns']['std'],
				'type' => 'option'
		) );
		$wp_customize->add_control( 'bueno_blog_page_columns', array(
				'label' => $options['blog_page_columns']['name'],
				'section' => 'bueno_blog',
				'settings' => 'bueno[blog_page_columns]',
				'type' => $options['blog_page_columns']['type'],
				'choices' => $options['blog_page_columns']['options'],
				'priority' => 12
		) );
		
		/* Blog image size */
		$wp_customize->add_setting( 'bueno[post_image_size]', array(
				'default' => $options['post_image_size']['std'],
				'type' => 'option'
		) );
		$wp_customize->add_control( 'bueno_post_image_size', array(
				'label' => $options['post_image_size']['name'],
				'section' => 'bueno_blog',
				'settings' => 'bueno[post_image_size]',
				'type' => $options['post_image_size']['type'],
				'choices' => $options['post_image_size']['options'],
				'priority' => 13
		) );
		
		/* Single post image size */
		$wp_customize->add_setting( 'bueno[single_image_size]', array(
				'default' => $options['single_image_size']['std'],
				'type' => 'option'
		) );
		$wp_customize->add_control( 'bueno_single_image_size', array(
				'label' => $options['single_image_size']['name'],
				'section' => 'bueno_blog',
				'settings' => 'bueno[single_image_size]',
				'type' => $options['single_image_size']['type'],
				'choices' => $options['single_image_size']['options'],
				'priority' => 14
		) );
		
		/* Post Meta Author */
		$wp_customize->add_setting( 'bueno[post_meta_author]', array(
				'default' => $options['post_meta_author']['std'],
				'type' => 'option'
		) );
		$wp_customize->add_control( 'bueno_post_meta_author', array(
				'label' => $options['post_meta_author']['name'],
				'section' => 'bueno_blog',
				'settings' => 'bueno[post_meta_author]',
				'type' => $options['post_meta_author']['type'],
				'choices' => $options['post_meta_author']['options'],
				'priority' => 15
		) );

		/* Post Meta Date */
		$wp_customize->add_setting( 'bueno[post_meta_date]', array(
				'default' => $options['post_meta_date']['std'],
				'type' => 'option'
		) );
		$wp_customize->add_control( 'bueno_post_meta_date', array(
				'label' => $options['post_meta_date']['name'],
				'section' => 'bueno_blog',
				'settings' => 'bueno[post_meta_date]',
				'type' => $options['post_meta_date']['type'],
				'choices' => $options['post_meta_date']['options'],
				'priority' => 16
		) );

		/* Post Meta Comments */
		$wp_customize->add_setting( 'bueno[post_meta_comments]', array(
				'default' => $options['post_meta_comments']['std'],
				'type' => 'option'
		) );
		$wp_customize->add_control( 'bueno_post_meta_comments', array(
				'label' => $options['post_meta_comments']['name'],
				'section' => 'bueno_blog',
				'settings' => 'bueno[post_meta_comments]',
				'type' => $options['post_meta_comments']['type'],
				'choices' => $options['post_meta_comments']['options'],
				'priority' => 17
		) );

		/* Post Meta Categories */
		$wp_customize->add_setting( 'bueno[post_meta_categories]', array(
				'default' => $options['post_meta_categories']['std'],
				'type' => 'option'
		) );
		$wp_customize->add_control( 'bueno_post_meta_categories', array(
				'label' => $options['post_meta_categories']['name'],
				'section' => 'bueno_blog',
				'settings' => 'bueno[post_meta_categories]',
				'type' => $options['post_meta_categories']['type'],
				'choices' => $options['post_meta_categories']['options'],
				'priority' => 18
		) );
		
		/* Post Excerpt */
		$wp_customize->add_setting( 'bueno[post_excerpt]', array(
				'default' => $options['post_excerpt']['std'],
				'type' => 'option'
		) );
		$wp_customize->add_control( 'bueno_post_excerpt', array(
				'label' => $options['post_excerpt']['name'],
				'section' => 'bueno_blog',
				'settings' => 'bueno[post_excerpt]',
				'type' => $options['post_excerpt']['type'],
				'choices' => $options['post_excerpt']['options'],
				'priority' => 19
		) );

		/* Post Button */
		$wp_customize->add_setting( 'bueno[post_button]', array(
				'default' => $options['post_button']['std'],
				'type' => 'option'
		) );
		$wp_customize->add_control( 'bueno_post_button', array(
				'label' => $options['post_button']['name'],
				'section' => 'bueno_blog',
				'settings' => 'bueno[post_button]',
				'type' => $options['post_button']['type'],
				'choices' => $options['post_button']['options'],
				'priority' => 20
		) );

		/* Post Button */
		$wp_customize->add_setting( 'bueno[post_button]', array(
				'default' => $options['post_button']['std'],
				'type' => 'option'
		) );
		$wp_customize->add_control( 'bueno_post_button', array(
				'label' => $options['post_button']['name'],
				'section' => 'bueno_blog',
				'settings' => 'bueno[post_button]',
				'type' => $options['post_button']['type'],
				'choices' => $options['post_button']['options'],
				'priority' => 21
		) );


		/* Post author */
		$wp_customize->add_setting( 'bueno[post_author]', array(
				'default' => $options['post_author']['std'],
				'type' => 'option'
		) );
		$wp_customize->add_control( 'bueno_post_author', array(
				'label' => $options['post_author']['name'],
				'section' => 'bueno_blog',
				'settings' => 'bueno[post_author]',
				'type' => $options['post_author']['type'],
				'choices' => $options['post_author']['options'],
				'priority' => 22
		) );
		

		/* Related title */
		$wp_customize->add_setting( 'bueno[blog_related]', array(
				'default' => $options['blog_related']['std'],
				'type' => 'option'
		) );
		$wp_customize->add_control( 'bueno_blog_related', array(
				'label' => $options['blog_related']['name'],
				'section' => 'bueno_blog',
				'settings' => 'bueno[blog_related]',
				'type' => $options['blog_related']['type'],
				'priority' => 23
		) );
		
		/*-----------------------------------------------------------------------------------*/
		/*	Portfolio
		/*-----------------------------------------------------------------------------------*/
		$wp_customize->add_section( 'bueno_portfolio', array(
			'title' => __( 'Portfolio', 'bueno' ),
			'priority' => 205
		));
		
		/* Portfolio per page */
		$wp_customize->add_setting( 'bueno[g_portfolio_per_page]', array(
				'default' => $options['g_portfolio_per_page']['std'],
				'type' => 'option'
		) );
		$wp_customize->add_control( 'g_portfolio_per_page', array(
				'label' => $options['g_portfolio_per_page']['name'],
				'section' => 'bueno_portfolio',
				'settings' => 'bueno[g_portfolio_per_page]',
				'type' => $options['g_portfolio_per_page']['type'],
				'priority' => 11
		) );

		/* Portfolio Format */
		$wp_customize->add_setting( 'bueno[g_portfolio_format]', array(
				'default' => $options['g_portfolio_format']['std'],
				'type' => 'option'
		) );
		$wp_customize->add_control( 'bueno_g_portfolio_format', array(
				'label' => $options['g_portfolio_format']['name'],
				'section' => 'bueno_portfolio',
				'settings' => 'bueno[g_portfolio_format]',
				'type' => $options['g_portfolio_format']['type'],
				'choices' => $options['g_portfolio_format']['options'],
				'priority' => 12
		) );

		/* Portfolio Category */
		$wp_customize->add_setting( 'bueno[g_portfolio_cat]', array(
				'default' => $options['g_portfolio_cat']['std'],
				'type' => 'option'
		) );
		$wp_customize->add_control( 'bueno_g_portfolio_cat', array(
				'label' => $options['g_portfolio_cat']['name'],
				'section' => 'bueno_portfolio',
				'settings' => 'bueno[g_portfolio_cat]',
				'type' => $options['g_portfolio_cat']['type'],
				'choices' => $options['g_portfolio_cat']['options'],
				'priority' => 13
		) );

		/* Portfolio preview button */
		$wp_customize->add_setting( 'bueno[g_portfolio_preview_btn]', array(
				'default' => $options['g_portfolio_preview_btn']['std'],
				'type' => 'option'
		) );
		$wp_customize->add_control( 'g_portfolio_preview_btn', array(
				'label' => $options['g_portfolio_preview_btn']['name'],
				'section' => 'bueno_portfolio',
				'settings' => 'bueno[g_portfolio_preview_btn]',
				'type' => $options['g_portfolio_preview_btn']['type'],
				'choices' => $options['g_portfolio_preview_btn']['options'],
				'priority' => 14
		) );	
		
		/*-----------------------------------------------------------------------------------*/
		/*	Footer
		/*-----------------------------------------------------------------------------------*/
		
		$wp_customize->add_section( 'bueno_footer', array(
			'title' => __( 'Footer', 'bueno' ),
			'priority' => 206
		) );
			
		/* Footer Copyright Text */
		$wp_customize->add_setting( 'bueno[footer_text]', array(
				'default' => $options['footer_text']['std'],
				'type' => 'option'
		) );
		$wp_customize->add_control( 'bueno_footer_text', array(
				'label' => $options['footer_text']['name'],
				'section' => 'bueno_footer',
				'settings' => 'bueno[footer_text]',
				'type' => 'text'
		) );
		
		
		/* Display Footer Menu */
		$wp_customize->add_setting( 'bueno[footer_menu]', array(
				'default' => $options['footer_menu']['std'],
				'type' => 'option'
		) );
		$wp_customize->add_control( 'bueno_footer_menu', array(
				'label' => $options['footer_menu']['name'],
				'section' => 'bueno_footer',
				'settings' => 'bueno[footer_menu]',
				'type' => $options['footer_menu']['type'],
				'choices' => $options['footer_menu']['options']
		) );
		

	
	};

}