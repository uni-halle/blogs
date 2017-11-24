<?php 
/**
 * Build the custom CSS styles per colour option
 *
 * @global array $graphene_settings
 * @global array $graphene_defaults
 * @return string
 *
 * @package Graphene
 * @since Graphene 1.8
 */
function graphene_build_style( $styles, $extra_args = array() ){
	global $graphene_defaults, $graphene_settings;
	$out = '';

	foreach ( $styles as $opts => $style ) {
		if ( stripos( $opts, '|' ) !== false ) $opts = explode( '|', $opts );
		else $opts = (array) $opts;
		
		if ( graphene_is_settings_custom( $opts ) ) {
			foreach ( $opts as $key => $opt ) {

				if ( stripos( $opt, '@' ) !== false ) {
					// Check for alpha-channel setting
					$opt = explode( '@', $opt );
					$alpha = $opt[1];
					$opt = $opt[0];

					$rgba = graphene_rgb2hex( $graphene_settings[$opt] );
					$rgba[] = $alpha;
					$opts[$key] = 'rgba(' . join( ',', $rgba) . ')';
				} else {
					$opts[$key] = $graphene_settings[$opt];
				}
			}
			$args = array_merge( array( $style ), $opts, $extra_args );
			if ( $args ) $out .= call_user_func_array( 'sprintf', $args );
		}
	}
	
	return $out;
}


/**
 * Check if the user settings are different than the default settings
 *
 * @param array $settings
 * @global array $graphene_settings
 * @global array $graphene_defaults
 * @return bool
 *
 * @package Graphene
 * @since Graphene 1.8
 */
function graphene_is_settings_custom( $settings ){
	global $graphene_defaults, $graphene_settings;
	$settings = (array) $settings;
	
	$diff = false;
	foreach ( $settings as $key ) {

		// Remove alpha-channel setting for colour options
		if ( stripos( $key, '@' ) !== false ) {
			$key = explode( '@', $key );
			$key = $key[0];
		}

		if ( $graphene_defaults[$key] !== $graphene_settings[$key] ) {
			$diff = true;
			break;
		}
	}
	
	return $diff;
}


/**
 * Basic CSS minifier, based on the codes by Kit McAllister (http://kitmacallister.com/2011/minify-css-with-php/)
 *
 * @param string Regular CSS string to be minified
 * @return string Minified CSS string
 *
 * @package Graphene
 * @since Graphene 1.8
 */
function graphene_minify_css( $css ){

	/* Strip comments */
	$css = preg_replace('!/\*.*?\*/!s','', $css);
	$css = preg_replace('/\n\s*\n/',"\n", $css);

	/* Minify */
	$css = preg_replace('/[\n\r \t]/',' ', $css);
	$css = preg_replace('/ +/',' ', $css);
	$css = preg_replace('/ ?([,:;{}] ) ?/','$1',$css);

	/* Kill trailing semicolon */
	$css = preg_replace('/;}/','}',$css);

	return $css;
}


/**
 * Get the custom style attributes, these are defined by theme options.
 * 
 * @global type $graphene_settings
 * @global type $graphene_defaults
 * @global type $content_width
 * @return string 
 */
function graphene_get_custom_style(){ 
	global $graphene_settings, $graphene_defaults, $content_width;
	
	$background = get_theme_mod( 'background_image', false);
	$bgcolor = get_theme_mod( 'background_color', false);
	$widgetcolumn = $graphene_settings['footerwidget_column'];
	$widgetcolumn_alt = $graphene_settings['alt_footerwidget_column'];
	$container_width = apply_filters( 'graphene_container_width', $graphene_settings['container_width'] );
	$gutter = $graphene_settings['gutter_width'];
        
	$style = '';
	
	/* Disable default background if a custom background colour is defined */
	if ( ! $background && $bgcolor ) {
		$style .= 'body{background-image:none;}';
	}
	
	/* Header text */
	$header_textcolour = get_theme_mod( 'header_textcolor', HEADER_TEXTCOLOR );
	if ( $header_textcolour != apply_filters( 'graphene_header_textcolor', HEADER_TEXTCOLOR ) )
		$style .= '.header_title, .header_title a, .header_title a:visited, .header_title a:hover, .header_desc {color:#' . $header_textcolour . ';}';
		
	/* Header title text style */ 
	$font_style = '';
	$font_style .= ( $graphene_settings['header_title_font_type'] ) ? 'font-family:'.$graphene_settings['header_title_font_type'].';' : '';
	$font_style .= ( $graphene_settings['header_title_font_lineheight'] ) ? 'line-height:'.$graphene_settings['header_title_font_lineheight'].';' : '';
	$font_style .= ( $graphene_settings['header_title_font_size'] ) ? 'font-size:'.$graphene_settings['header_title_font_size'].';' : '';
	$font_style .= ( $graphene_settings['header_title_font_weight'] ) ? 'font-weight:'.$graphene_settings['header_title_font_weight'].';' : '';
	$font_style .= ( $graphene_settings['header_title_font_style'] ) ? 'font-style:'.$graphene_settings['header_title_font_style'].';' : '';
	if ( $font_style ) { $style .= '#header .header_title { '.$font_style.' }'; }

	/* Header description text style */ 
	$font_style = '';
	$font_style .= ( $graphene_settings['header_desc_font_type'] ) ? 'font-family:'.$graphene_settings['header_desc_font_type'].';' : '';
	$font_style .= ( $graphene_settings['header_desc_font_size'] ) ? 'font-size:'.$graphene_settings['header_desc_font_size'].';' : '';
	$font_style .= ( $graphene_settings['header_desc_font_lineheight'] ) ? 'line-height:'.$graphene_settings['header_desc_font_lineheight'].';' : '';
	$font_style .= ( $graphene_settings['header_desc_font_weight'] ) ? 'font-weight:'.$graphene_settings['header_desc_font_weight'].';' : '';
	$font_style .= ( $graphene_settings['header_desc_font_style'] ) ? 'font-style:'.$graphene_settings['header_desc_font_style'].';' : '';
	if ( $font_style ) { $style .= '#header .header_desc { '.$font_style.' }'; }
	
	/* Content text style */ 
	$font_style = '';
	$font_style .= ( $graphene_settings['content_font_type'] ) ? 'font-family:'.$graphene_settings['content_font_type'].';' : '';
	$font_style .= ( $graphene_settings['content_font_size'] ) ? 'font-size:'.$graphene_settings['content_font_size'].';' : '';
	$font_style .= ( $graphene_settings['content_font_lineheight'] ) ? 'line-height:'.$graphene_settings['content_font_lineheight'].';' : '';
	$font_style .= ( $graphene_settings['content_font_colour'] != $graphene_defaults['content_font_colour'] ) ? 'color:'.$graphene_settings['content_font_colour'].';' : '';
	if ( $font_style ) { $style .= '.entry-content, .sidebar, .comment-entry { '.$font_style.' }'; }
	
	/* Slider */
	if ( $graphene_settings['slider_height'] || $graphene_settings['slider_height_mobile'] ) {
		$style .= '.carousel, .carousel .item{height:'.$graphene_settings['slider_height'].'px;}';
		$style .= '@media (max-width: 767px) {.carousel, .carousel .item{height:'.$graphene_settings['slider_height_mobile'].'px;}}';
	}
	
	/* Header image height */
	if ( $graphene_settings['header_img_height'] && $graphene_settings['header_img_height'] != $graphene_defaults['header_img_height'] ){
		$style .= '#header{max-height:'. HEADER_IMAGE_HEIGHT .'px;}';
	}
		
	// Link style
	// $link_styles = array(
	// 				'link_colour_normal' 	=> 'a,.post-title,.post-title a,#comments > h4.current a{color: %s}',
	// 				'link_colour_visited'	=> 'a:visited,.post-title a:visited{color: %s}',
	// 				'link_colour_hover'		=> 'a:hover,.post-title a:hover{color: %s}',
	// 				'link_decoration_normal'=> 'a,.post-title a{text-decoration: %s}',
	// 				'link_decoration_hover'	=> 'a:hover,.post-title a:hover{text-decoration: %s}',
	// 			);
	// $style .= graphene_build_style( $link_styles );

	
	/* Title text colour */
	$font_style = array(
						'title_font_colour' => '.post-title, .post-title a, .post-title a:hover, .post-title a:visited { color: %s }',
					);
	$style .= graphene_build_style( $font_style );
	
	// Custom column width
	$style .= graphene_get_custom_column_width();
	
	return $style;
}


/**
 * Get the custom colour style attributes defined by the theme colour settings
 * 
 * @global array $graphene_settings
 * @global array $graphene_defaults
 * @return string 
 */
function graphene_get_custom_colours( $hook_suffix = '', $force_all = false ){
	global $graphene_settings, $graphene_defaults;
	
	if ( ! $hook_suffix && is_admin() ) {
		$current_screen = get_current_screen();
		$hook_suffix = $current_screen->base;
	}
	$tab = ( isset( $_GET['tab'] ) ) ? $_GET['tab'] : '';
    
	$style = '';
    
	if ( ! is_admin() || ( $graphene_settings['hook_suffix'] == $hook_suffix && $tab == 'colours' ) || $force_all ) {
		
		// Top bar
		$colours = array(
			'top_bar_bg'	=> '#top-bar{background-color:%s}',
		);
		$style .= graphene_build_style( $colours );

		 
		// Primary Menu (top level)
		$colours = array(
			'menu_primary_bg'				=> '.navbar, .navbar #header-menu-wrap, .navbar #header-menu-wrap {background: %s}',
			'menu_primary_item'				=> '.navbar #header-menu-wrap .nav > li > a, .navbar #header-menu-wrap .nav > li > a {color: %s}',
			'menu_primary_active_bg'		=> '.navbar #header-menu-wrap .nav li:focus, .navbar #header-menu-wrap .nav li:hover, .navbar #header-menu-wrap .nav li.current-menu-item, .navbar #header-menu-wrap .nav li.current-menu-ancestor, .navbar #header-menu-wrap .dropdown-menu li, .navbar #header-menu-wrap .dropdown-menu > li > a:focus, .navbar #header-menu-wrap .dropdown-menu > li > a:hover, .navbar #header-menu-wrap .dropdown-menu > .active > a, .navbar #header-menu-wrap .dropdown-menu > .active > a:focus, .navbar #header-menu-wrap .dropdown-menu > .active > a:hover, .navbar #header-menu-wrap .navbar-nav>.open>a, .navbar #header-menu-wrap .navbar-nav>.open>a:focus, .navbar #header-menu-wrap .navbar-nav>.open>a:hover, .navbar .navbar-nav>.active>a, .navbar .navbar-nav>.active>a:focus, .navbar .navbar-nav>.active>a:hover {background: %s}',
			'menu_primary_active_item'		=> 	'.navbar #header-menu-wrap .navbar-nav>.active>a, .navbar #header-menu-wrap .navbar-nav>.active>a:focus, .navbar #header-menu-wrap .navbar-nav>.active>a:hover, .navbar #header-menu-wrap .navbar-nav>.open>a, .navbar #header-menu-wrap .navbar-nav>.open>a:focus, .navbar #header-menu-wrap .navbar-nav>.open>a:hover, .navbar #header-menu-wrap .navbar-nav>.current-menu-item>a, .navbar #header-menu-wrap .navbar-nav>.current-menu-item>a:hover, .navbar #header-menu-wrap .navbar-nav>.current-menu-item>a:focus, .navbar #header-menu-wrap .navbar-nav>.current-menu-ancestor>a, .navbar #header-menu-wrap .navbar-nav>.current-menu-ancestor>a:hover, .navbar #header-menu-wrap .navbar-nav>.current-menu-ancestor>a:focus, .navbar #header-menu-wrap .navbar-nav>li>a:focus, .navbar #header-menu-wrap .navbar-nav>li>a:hover {color: %s}',
			'menu_primary_dd_item'			=> '.navbar #header-menu-wrap .nav ul li a, .navbar #header-menu-wrap .nav ul li a {color: %s}',
			'menu_primary_dd_active_item'	=> '.navbar #header-menu-wrap .nav .dropdown-menu li:hover > a, .navbar #header-menu-wrap .nav .dropdown-menu li.current-menu-item > a, .navbar #header-menu-wrap .nav .dropdown-menu li.current-menu-ancestor > a {color: %s}'
		);
		$style .= graphene_build_style( $colours );
		
		
		// Secondary Menu (sub-level)
		$colours = array(
			'menu_sec_bg' 				=> '.navbar #secondary-menu-wrap {background: %s}',
			'menu_sec_border'			=> '.navbar #secondary-menu-wrap, .navbar-inverse .dropdown-submenu > .dropdown-menu {border-color:%s}',
			'menu_sec_item'				=> '.navbar #secondary-menu > li > a {color: %s}',
			'menu_sec_active_bg'		=> '.navbar #secondary-menu-wrap .nav li:focus, .navbar #secondary-menu-wrap .nav li:hover, .navbar #secondary-menu-wrap .nav li.current-menu-item, .navbar #secondary-menu-wrap .nav li.current-menu-ancestor, .navbar #secondary-menu-wrap .dropdown-menu li, .navbar #secondary-menu-wrap .dropdown-menu > li > a:focus, .navbar #secondary-menu-wrap .dropdown-menu > li > a:hover, .navbar #secondary-menu-wrap .dropdown-menu > .active > a, .navbar #secondary-menu-wrap .dropdown-menu > .active > a:focus, .navbar #secondary-menu-wrap .dropdown-menu > .active > a:hover, .navbar #secondary-menu-wrap .navbar-nav>.open>a, .navbar #secondary-menu-wrap .navbar-nav>.open>a:focus, .navbar #secondary-menu-wrap .navbar-nav>.open>a:hover {background-color: %s}',
			'menu_sec_active_item'		=> '.navbar #secondary-menu-wrap .navbar-nav>.active>a, .navbar #secondary-menu-wrap .navbar-nav>.active>a:focus, .navbar #secondary-menu-wrap .navbar-nav>.active>a:hover, .navbar #secondary-menu-wrap .navbar-nav>.open>a, .navbar #secondary-menu-wrap .navbar-nav>.open>a:focus, .navbar #secondary-menu-wrap .navbar-nav>.open>a:hover, .navbar #secondary-menu-wrap .navbar-nav>.current-menu-item>a, .navbar #secondary-menu-wrap .navbar-nav>.current-menu-item>a:hover, .navbar #secondary-menu-wrap .navbar-nav>.current-menu-item>a:focus, .navbar #secondary-menu-wrap .navbar-nav>.current-menu-ancestor>a, .navbar #secondary-menu-wrap .navbar-nav>.current-menu-ancestor>a:hover, .navbar #secondary-menu-wrap .navbar-nav>.current-menu-ancestor>a:focus, .navbar #secondary-menu-wrap .navbar-nav>li>a:focus, .navbar #secondary-menu-wrap .navbar-nav>li>a:hover {color: %s}',
			'menu_sec_dd_item'			=> '.navbar #secondary-menu-wrap .nav ul li a {color: %s}',
			'menu_sec_dd_active_item'	=> '.navbar #secondary-menu-wrap .nav .dropdown-menu li:hover > a, .navbar #secondary-menu-wrap .nav .dropdown-menu li.current-menu-item > a, .navbar #secondary-menu-wrap .nav .dropdown-menu li.current-menu-ancestor > a {color: %s}'
		);
		$style .= graphene_build_style( $colours );

		// 'content_font_colour' 		=> '#4a474b',
		// 'title_font_colour' 		=> '#1f1a22',
		// 'link_colour_normal' 		=> '#783d98',
		// 'link_colour_hover' 		=> '#9538c5',
		
		// Content area
		$colours = array(
			'content_wrapper_bg' 	=> '#content {background-color: %s}',
			'content_bg' 			=> '.post, .singular .hentry {background-color: %s;}',
			'meta_border'			=> '.entry-footer {border-color: %s;}',
			'content_font_colour' 	=> 'body, blockquote p {color: %s}',
			'title_font_colour' 	=> '.post-title, .post-title a, .post-title a:hover, .post-title a:visited {color: %s}',
			'link_colour_normal' 	=> 'a, .post .date .day, .pagination>li>a, .pagination>li>a:hover, .pagination>li>span, #comments > h4.current a, #comments > h4.current a .fa, .post-nav-top p, .post-nav-top a {color: %s}',
			'link_colour_hover' 	=> 'a:focus, a:hover, .post-nav-top a:hover {color: %s}',
			'sticky_border' 		=> '.sticky {border-color: %s;}',
			'child_page_content_bg' => '.child-page {background-color: %s;}',
		);
		$style .= graphene_build_style( $colours );

		
		// Widgets
		$colours = array(
			'widget_item_bg|widget_header_border'	=> '.sidebar .sidebar-wrap {background-color: %1$s; border-color: %2$s}',
			'widget_list'							=> '.sidebar ul li {border-color: %s}'
		);
		$style .= graphene_build_style( $colours );
		

		// Slider
		$colours = array(
			'slider_caption_bg|slider_caption_bg@0.8|slider_caption_text'	=> '.carousel-caption {background-color: %1$s; background-color: %2$s; color: %3$s} .carousel .slider_post_title, .carousel .slider_post_title a {color: %3$s}'
		);
		$style .= graphene_build_style( $colours );

		
		// Buttons and Labels
		$colours = array(
			'button_bg|button_label'=> '.btn, .Button, .colour-preview .button, input[type="submit"], button[type="submit"], #commentform #submit, .wpsc_buy_button, #back-to-top {background: %1$s; color: %2$s}',
			'label_bg|label_text'	=> '.label-primary, .pagination>.active>a, .pagination>.active>a:focus, .pagination>.active>a:hover, .pagination>.active>span, .pagination>.active>span:focus, .pagination>.active>span:hover, .list-group-item.parent, .list-group-item.parent:focus, .list-group-item.parent:hover {background: %1$s; border-color: %1$s; color: %2$s}'
		);
		$style .= graphene_build_style( $colours );


        // Archive
		$colours = array(
			'archive_bg|archive_border'	=> '.post-nav-top, .archive-title, .page-title, .category-desc {background-color: %1$s; border-color: %2$s}',
			'archive_label' 			=> '.archive-title span {color: %s}',
			'archive_text'				=> '.page-title, .archive-title, .category-desc {color: %s}',
		);
		$style .= graphene_build_style( $colours );


		// Comments area
		$colours = array(
			'comments_bg|comments_border|comments_box_shadow|comments_box_shadow@0.05|comments_text'
										=> '#comments .comment, #comments .pingback, #comments .trackback {background-color: %1$s; border-color: %2$s; box-shadow: 0 0 3px %3$s; box-shadow: 0 0 3px %4$s; color: %5$s}',
			'author_comments_border'	=> '#comments ol.children li.bypostauthor, #comments li.bypostauthor.comment {border-color: %s}',
		);
		$style .= graphene_build_style( $colours );

		
		// Footer
		$colours = array(
			'footer_bg|footer_text' => '#footer, .graphene-footer{background-color:%1$s;color:%2$s}',
			'footer_link'			=> '#footer a, #footer a:visited {color: %s}',
		);
		$style .= graphene_build_style( $colours );

	}
        
    return $style;
}


/**
 * Build and return the CSS styles custom column width
 *
 * @package Graphene
 * @since 1.6
 * @return string $style CSS styles
*/
function graphene_get_custom_column_width(){
	global $graphene_settings, $graphene_defaults;
	$container = $graphene_settings['container_width'];
	$gutter = $graphene_settings['gutter_width'];
	$style = '';
	
	/* Custom container width */
	if ( $container != $graphene_defaults['container_width'] ) $style .= "@media (min-width: 1200px) {.container {width:{$container}px}}";
	if ( $gutter != $graphene_defaults['gutter_width'] ) $style .= ".container {padding-left:{$gutter}px;padding-right:{$gutter}px;}";

	
	return apply_filters( 'graphene_custom_column_width_style', $style );
}
 

/**
 * Sets the various customised styling according to the options set for the theme.
 *
 * @param bool $out Whether to echo the styles or not
 * @param bool $minify Whether to minify the styles or not
 * @param bool $force_all If set to true, it returns the full generated CSS as it will be in the front end
 *
 * @package Graphene
 * @since Graphene 1.0.8
*/
function graphene_custom_style( $echo = true, $minify = true, $force_all = false ){
	if ( ! is_bool( $echo ) ) $echo = true;
	global $graphene_settings;
	
	$style = '';
		
	// only get the custom css styles and colours when were not in the admin mode
	if ( ! is_admin() || $force_all ) {
		$style .= graphene_get_custom_colours( '', $force_all );
		$style .= graphene_get_custom_style();	
	}
	if ( $minify ) $style = graphene_minify_css( $style );
	
	$style = apply_filters( 'graphene_custom_style', $style, $echo, $minify, $force_all );
	
    if ( $style && $echo ) echo '<style type="text/css">' . "\n" . $style . "\n" . '</style>' . "\n";
	else return $style;
}
add_action( 'wp_head', 'graphene_custom_style' );


/**
 * Check to see if there's a favicon.ico in wordpress root directory and add
 * appropriate head element for the favicon
*/
function graphene_favicon(){
	global $graphene_settings;
	if ( $graphene_settings['favicon_url'] ) { ?>
		<link rel="icon" href="<?php echo $graphene_settings['favicon_url']; ?>" type="image/x-icon" />
	<?php
    } elseif ( is_file( ABSPATH . 'favicon.ico' ) ){ ?>
		<link rel="icon" href="<?php echo home_url(); ?>/favicon.ico" type="image/x-icon" />
	<?php }
}
add_action( 'wp_head', 'graphene_favicon' );


/**
 * Add Google Analytics code if tracking is enabled 
 */ 
function graphene_google_analytics(){
	global $graphene_settings;
    if ( $graphene_settings['show_ga'] ) : ?>
    <!-- BEGIN Google Analytics script -->
    	<?php echo stripslashes( $graphene_settings['ga_code'] ); ?>
    <!-- END Google Analytics script -->
    <?php endif; 
}
add_action( 'wp_head', 'graphene_google_analytics', 1000);


/**
 * This function prints out the title for the website.
 * If present, the theme will display customised site title structure.
*/
function graphene_title( $title, $sep = '&raquo;', $seplocation = '' ){
	global $graphene_settings;
	if ( ! $graphene_settings['custom_site_title_frontpage'] && ! $graphene_settings['custom_site_title_content'] ) return;

	$default_title = $title;
	
	if ( is_feed() ){
		
		$title = $default_title;
		
	} elseif ( is_front_page() ) { 
	
		if ( $graphene_settings['custom_site_title_frontpage'] ) {
			$title = $graphene_settings['custom_site_title_frontpage'];
			$title = str_replace( '#site-name', get_bloginfo( 'name' ), $title );
			$title = str_replace( '#site-desc', get_bloginfo( 'description' ), $title );
		} else {
			$title = get_bloginfo( 'name' );
			$title .= ( $desc = get_bloginfo( 'description' ) ) ? " &raquo; " . $desc : '';
		}
		
	} else {
		
		if ( $graphene_settings['custom_site_title_content'] ) {
			$title = $graphene_settings['custom_site_title_content'];
			$title = str_replace( '#site-name', get_bloginfo( 'name' ), $title );
			$title = str_replace( '#site-desc', get_bloginfo( 'description' ), $title );
			$title = str_replace( '#post-title', get_the_title(), $title );
		} else {
			$title = $default_title . " &raquo; " . get_bloginfo( 'name' );
		}
	}
	
	return ent2ncr( apply_filters( 'graphene_title', trim( $title ) ) );
}
add_filter( 'wp_title', 'graphene_title', 10, 3 );
add_filter( 'pre_get_document_title', 'graphene_title', 10, 3 );


/**
 * Prints out custom <head> tags
 *
 * @package Graphene
 * @since Graphene 1.8
 */
function graphene_custom_head_tags(){
	global $graphene_settings;
	echo $graphene_settings['head_tags'];
}
add_action( 'wp_head', 'graphene_custom_head_tags', 100 );
