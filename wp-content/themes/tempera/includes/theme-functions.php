<?php
/**
 * Misc functions breadcrumbs / pagination / transient data /back to top button
 *
 * @package tempera
 * @subpackage Functions
 */


 /**
 * Loads necessary scripts
 * Adds HTML5 tags for IE8
 * Used in header.php
*/
function tempera_header_scripts() {
	?>
	<!--[if lt IE 9]>
	<script>
	document.createElement('header');
	document.createElement('nav');
	document.createElement('section');
	document.createElement('article');
	document.createElement('aside');
	document.createElement('footer');
	</script>
	<![endif]-->
	<?php
} // tempera_header_scripts()

add_action('wp_head','tempera_header_scripts',100);


 /**
 * Adds title and description to heaer
 * Used in header.php
*/
function tempera_title_and_description() {
	$temperas = tempera_get_theme_options();
	global $post;

	// Check if this is a post or page, if it has a thumbnail, and if it's a big one
	if ( get_header_image() != '' ) { $himgsrc = get_header_image(); }
	if ( is_singular() && has_post_thumbnail( $post->ID ) && 
		($temperas['tempera_fheader'] == "Enable") &&
		( $image = wp_get_attachment_image_src( get_post_thumbnail_id( $post->ID ), 'header' ) ) && 
		$image[1] >= HEADER_IMAGE_WIDTH ) {
			$himgsrc = esc_url( $image[0] );
	};

	if ( isset( $himgsrc ) && ( $himgsrc != '' ) ) { echo '<img id="bg_image" alt="" title="" src="' . esc_url( $himgsrc ) . '"  />'; } ?>
	<div id="header-container">
	<?php

	switch ($temperas['tempera_siteheader']) {
		case 'Site Title and Description':
			echo '<div>';
			$heading_tag = ( ( is_home() || is_front_page() ) && !is_page() ) ? 'h1' : 'div';
			echo '<'.$heading_tag.' id="site-title">';
			echo '<span> <a href="' . esc_url ( home_url( '/' ) ) . '" title="' . esc_attr( get_bloginfo( 'name', 'display' ) ) . '" rel="home">' . get_bloginfo( 'name' ) . '</a> </span>';
			echo '</'.$heading_tag.'>';
			echo '<div id="site-description" >' . get_bloginfo( 'description' ) . '</div></div>';
		break;

		case 'Clickable header image' :
			echo '<a href="' . esc_url( home_url( '/' ) ) . '" id="linky"></a>' ;
		break;

		case 'Custom Logo' :
			if ( isset($temperas['tempera_logoupload']) && ($temperas['tempera_logoupload'] != '') ) :
				echo '<div><a id="logo" href="' . esc_url( home_url( '/' ) ) . '" ><img title="' . esc_attr( get_bloginfo( 'name', 'display' ) ) . '" alt="' .
                    esc_attr( get_bloginfo( 'name', 'display' ) ) . '" src="' . esc_url( $temperas['tempera_logoupload'] ) . '" /></a></div>';
			endif;
		break;

		case 'Empty' :
		break;
	}
	echo '</div>';
} // tempera_title_and_description()

add_action ( 'cryout_branding_hook', 'tempera_title_and_description' );


/**
 * Add social icons in header / undermneu left / undermenu right / footer / left browser side / right browser side
 * Used in header.php and footer.php
*/
function tempera_header_socials() {
	tempera_set_social_icons('sheader');
}

function tempera_smenul_socials() {
	tempera_set_social_icons('smenul');
}

function tempera_smenur_socials() {
	tempera_set_social_icons('smenur');
}

function tempera_footer_socials() {
	tempera_set_social_icons('sfooter');
}

function tempera_slefts_socials() {
	tempera_set_social_icons('slefts');
}

function tempera_srights_socials() {
	tempera_set_social_icons('srights');
}

// Adding socials to the topbar
if ( $temperas['tempera_socialsdisplay0'] ) add_action( 'cryout_topbar_hook', 'tempera_header_socials', 13 );
// Adding socials to the footer
if ( $temperas['tempera_socialsdisplay3'] ) add_action( 'cryout_footer_hook', 'tempera_footer_socials', 13 );
// Adding socials to the left and right sides
if ( $temperas['tempera_socialsdisplay4'] ) add_action( 'cryout_wrapper_hook', 'tempera_slefts_socials', 13 );
if ( $temperas['tempera_socialsdisplay5'] ) add_action( 'cryout_wrapper_hook', 'tempera_srights_socials', 13 );


if ( ! function_exists( 'tempera_set_social_icons' ) ) :
/**
 * Social icons function
 */
function tempera_set_social_icons($idd) {
	$cryout_special_keys = array('Mail', 'Skype', 'Phone');
	global $temperas;
	extract($temperas);
	
	echo '<div class="socials" id="'.$idd.'">';
	for ($i = 1; $i <= 9; $i+=2) {
		$j = $i + 1;
		if ( ${"tempera_social$j"} ) {
			if (in_array(${"tempera_social$i"},$cryout_special_keys)) :
				$cryout_current_social = esc_html( ${"tempera_social$j"} );
			else :
				$cryout_current_social = esc_url( ${"tempera_social$j"} );
			endif;	?>

			<a <?php if ($temperas['tempera_social_target'.$i]) {echo ' target="_blank" ';} ?> href="<?php echo $cryout_current_social; ?>"
			class="socialicons social-<?php echo esc_attr(${"tempera_social$i"}); ?>" title="<?php echo ${"tempera_social_title$i"} !="" ? esc_attr(${"tempera_social_title$i"}) : esc_attr(${"tempera_social$i"}); ?>">
				<img alt="<?php echo esc_attr(${"tempera_social$i"}); ?>" src="<?php echo get_template_directory_uri().'/images/socials/'.${"tempera_social$i"}.'.png'; ?>" />
			</a><?php
		}
	}
	echo '</div>';
} // tempera_set_social_icons()
endif;


/**
 * Tempera back to top button
 * Creates div for js
*/
if ( ! function_exists( 'tempera_back_top' ) ) :
function tempera_back_top() {
	echo '<div id="toTop"><i class="crycon-back2top"></i> </div>';
} // tempera_back_top()
endif;

if ( $temperas['tempera_backtop']=="Enable" ) add_action( 'cryout_main_hook', 'tempera_back_top' );


 /**
 * Creates breadcrumns with page sublevels and category sublevels.
 */
if ( ! function_exists( 'tempera_breadcrumbs' ) ) :
function tempera_breadcrumbs() {

	$temperas = tempera_get_theme_options();

	$showOnHome = 0; 									// 1 - show breadcrumbs on the homepage, 0 - don't show
	$separator = '<i class="crycon-angle-right"></i>'; 	// separator between crumbs
	$home = '<a href="' . esc_url( home_url() ) . '"><i class="crycon-homebread"></i><span class="screen-reader-text">' . __("Home", "tempera") . '</span></a>'; // text for the 'Home' link
	$showCurrent = 1; 									// 1 - show current post/page title in breadcrumbs, 0 - don't show
	$before = '<span class="current">'; 				// tag before the current crumb
	$after = '</span>'; 								// tag after the current crumb

	global $post;
	$homeLink = esc_url( home_url() );
	if ( is_front_page() && ($temperas['tempera_frontpage']=="Enable") ) { return; }
	if ( is_home() && ($temperas['tempera_frontpage']!="Enable") ) {

		if ($showOnHome == 1) echo '<div class="breadcrumbs"><a href="' . $homeLink . '"><i class="crycon-homebread"></i>' .  __('Home Page','tempera') . '</a></div>';

	} else {

		echo '<div class="breadcrumbs">' . $home . $separator . ' ';

		if ( is_category() ) {
			// category
			$thisCat = get_category(get_query_var('cat'), false);
			if ( $thisCat->parent != 0 ) echo get_category_parents( $thisCat->parent, TRUE, ' ' . $separator . ' ' );
			echo $before . __('Archive by category','tempera').' "' . single_cat_title('', false) . '"' . $after;
		} elseif ( is_search() ) {
			// search
			echo $before . __('Search results for','tempera').' "' . get_search_query() . '"' . $after;
		} elseif ( is_day() ) {
			// daily archive
			echo '<a href="' . get_year_link(get_the_time('Y')) . '">' . get_the_time('Y') . '</a> ' . $separator . ' ';
			echo '<a href="' . get_month_link(get_the_time('Y'),get_the_time('m')) . '">' . get_the_time('F') . '</a> ' . $separator . ' ';
			echo $before . get_the_time('d') . $after;
		} elseif ( is_month() ) {
			// monthly archive
			echo '<a href="' . get_year_link(get_the_time('Y')) . '">' . get_the_time('Y') . '</a> ' . $separator . ' ';
			echo $before . get_the_time('F') . $after;
		} elseif ( is_year() ) {
			// yearly archive
			echo $before . get_the_time('Y') . $after;
		} elseif ( is_single() && !is_attachment() ) {
			// single post
			if ( get_post_type() != 'post' ) {
				$post_type = get_post_type_object(get_post_type());
				$slug = $post_type->rewrite;
				echo '<a href="' . $homeLink . '/' . $slug['slug'] . '/">' . $post_type->labels->singular_name . '</a>';
				if ( $showCurrent == 1 ) echo ' ' . $separator . ' ' . $before . get_the_title() . $after;
			} else {
				$cat = get_the_category();
				if ( !empty($cat[0]) ) { $cat = $cat[0]; } else { $cat = false; };
				if ( $cat ) { $cats = get_category_parents($cat, TRUE, ' ' . $separator . ' '); } else { $cats=false; };
				if ( $showCurrent == 0 && $cats ) $cats = preg_replace("#^(.+)\s$separator\s$#", "$1", $cats);
				echo $cats;
				if ( $showCurrent == 1 ) echo $before . get_the_title() . $after;
			}
		} elseif ( !is_single() && !is_page() && get_post_type() != 'post' && !is_404() ) {
			// some other item
			$post_type = get_post_type_object(get_post_type());
			echo $before . $post_type->labels->singular_name . $after;
		} elseif ( is_attachment() ) {
			// attachment
			$parent = get_post($post->post_parent);
			$cat = get_the_category($parent->ID);
			if ( !empty($cat[0]) ) { $cat = $cat[0]; } else { $cat=false; }
			if ( $cat ) echo get_category_parents($cat, TRUE, ' ' . $separator . ' ');
			echo '<a href="' . esc_url( get_permalink($parent) ) . '">' . $parent->post_title . '</a>';
			if ( $showCurrent == 1 ) echo ' ' . $separator . ' ' . $before . get_the_title() . $after;
		} elseif ( is_page() && !$post->post_parent ) {
			// parent page
			if ($showCurrent == 1) echo $before . get_the_title() . $after;
		} elseif ( is_page() && $post->post_parent ) {
			// child page
			$parent_id  = $post->post_parent;
			$breadcrumbs = array();
			while ($parent_id) {
				$page = get_page($parent_id);
				$breadcrumbs[] = '<a href="' . esc_url( get_permalink($page->ID) ) . '">' . get_the_title($page->ID) . '</a>';
				$parent_id  = $page->post_parent;
			}
			$breadcrumbs = array_reverse($breadcrumbs);
			for ( $i = 0; $i < count($breadcrumbs); $i++ ) {
				echo $breadcrumbs[$i];
				if ($i != count($breadcrumbs)-1) echo ' ' . $separator . ' ';
			}
			if ( $showCurrent == 1 ) echo ' ' . $separator . ' ' . $before . get_the_title() . $after;
		} elseif ( is_tag() ) {
			// tag archive
			echo $before . __('Posts tagged','tempera').' "' . single_tag_title('', false) . '"' . $after;
		} elseif ( is_author() ) {
			// author archive
			global $author;
			$userdata = get_userdata($author);
			echo $before . __('Articles posted by','tempera'). ' ' . $userdata->display_name . $after;
		} elseif ( is_404() ) {
			// 404 archive
			echo $before . __('Error 404','tempera') . $after;
		}
		elseif ( get_post_format() ) {
			// post format
			echo $before . '"' . ucwords( get_post_format() ) . '" ' . __( 'Post format', 'tempera' ) . $after;
		}

		if ( get_query_var('paged') ) {
			if ( is_category() || is_day() || is_month() || is_year() || is_search() || is_tag() || is_author() ) echo ' (';
			echo __('Page','tempera') . ' ' . get_query_var('paged');
			if ( is_category() || is_day() || is_month() || is_year() || is_search() || is_tag() || is_author() ) echo ')';
		}

		echo '</div>';

	}

} // breadcrumbs
endif;

if ($temperas['tempera_breadcrumbs']=="Enable") add_action( 'cryout_before_content_hook', 'tempera_breadcrumbs' );

/* Add search box to menus */
if ( !empty($temperas['tempera_searchbar']['top']) ) add_filter('wp_nav_menu_items','cryout_search_topmenu', 10, 2);
if ( !empty($temperas['tempera_searchbar']['main']) ) add_filter('wp_nav_menu_items','cryout_search_primarymenu', 10, 2);
if ( !empty($temperas['tempera_searchbar']['footer']) ) add_filter('wp_nav_menu_items','cryout_search_footermenu', 10, 2);

function cryout_search_topmenu( $items, $args ) {
	if( $args->theme_location == 'top') {
		$items = $items . "<li class='menu-header-search'> <i class='search-icon'></i> " . get_search_form( FALSE ) . "</li>";
	}
	return $items;
}

function cryout_search_primarymenu( $items, $args ) {
	if( $args->theme_location == 'primary') {
		$items = $items . "<li class='menu-main-search'> " . get_search_form( FALSE ) . " </li>";
	}
	return $items;
}

function cryout_search_footermenu( $items, $args ) {
	if( $args->theme_location == 'footer') {
		$items = $items . "<li class='menu-footer-search'> " . get_search_form( FALSE ) . " </li>";
	}
	return $items;
}

if ( ! function_exists( 'tempera_pagination' ) ) :
/**
 * Creates pagination for blog pages.
 */
function tempera_pagination( $pages = '', $range = 2, $prefix ='' ) {
     $showitems = ($range * 2)+1;

     global $paged;
     if(empty($paged)) $paged = 1;

     if ($pages == '')
     {
         global $wp_query;
         $pages = $wp_query->max_num_pages;
         if(!$pages)
         {
             $pages = 1;
         }
     }

     if (1 != $pages)
     {
		echo "<div class='pagination_container'><nav class='pagination'>";
         if ($prefix) {echo "<span id='paginationPrefix'>$prefix </span>";}
         if($paged > 2 && $paged > $range+1 && $showitems < $pages) echo "<a href='".get_pagenum_link(1)."'>&laquo;</a>";
         if($paged > 1 && $showitems < $pages) echo "<a href='".get_pagenum_link($paged - 1)."'>&lsaquo;</a>";

         for ($i=1; $i <= $pages; $i++)
         {
             if (1 != $pages &&( !($i >= $paged+$range+1 || $i <= $paged-$range-1) || $pages <= $showitems ))
             {
                 echo ($paged == $i)? "<span class='current'>".$i."</span>":"<a href='".get_pagenum_link($i)."' class='inactive' >".$i."</a>";
             }
         }

         if ($paged < $pages && $showitems < $pages) echo "<a href='".get_pagenum_link($paged + 1)."'>&rsaquo;</a>";
         if ($paged < $pages-1 &&  $paged+$range-1 < $pages && $showitems < $pages) echo "<a href='".get_pagenum_link($pages)."'>&raquo;</a>";
         echo "</nav></div>\n";
     }
} // tempera_pagination()
endif;

function tempera_nextpage_links($defaults) {
	$args = array(
		'link_before'	=> '<em>',
		'link_after'	=> '</em>',
	);
	$r = wp_parse_args($args, $defaults);
	return $r;
}
add_filter( 'wp_link_pages_args', 'tempera_nextpage_links' );


/**
 * Site info
 */
function tempera_site_info() { ?>
	<span style="display:block;float:right;text-align:right;padding:0 20px 5px;font-size:.9em;">
	<?php _e('Powered by','tempera')?> <a target="_blank" href="<?php echo 'http://www.cryoutcreations.eu';?>" title="<?php echo 'Tempera Theme by '.
			'Cryout Creations';?>"><?php echo 'Tempera' ?></a> &amp; <a target="_blank" href="<?php echo 'http://wordpress.org/'; ?>"
			title="<?php _e('Semantic Personal Publishing Platform', 'tempera'); ?>"> <?php printf(' %s.', 'WordPress' ); ?>
		</a>
	</span><!-- #site-info -->
	<?php
} // tempera_site_info()
add_action( 'cryout_footer_hook', 'tempera_site_info', 99 );


/**
 * Copyright text
 */
function tempera_copyright() {
	global $temperas;
	echo '<div id="site-copyright">' . wp_kses_post( $temperas['tempera_copyright'] ) . '</div>';
} // tempera_copyright()


if ( $temperas['tempera_copyright'] != '' ) add_action( 'cryout_footer_hook', 'tempera_copyright', 11 );

add_action( 'wp_ajax_nopriv_do_ajax', 'tempera_ajax_function' );
add_action( 'wp_ajax_do_ajax', 'tempera_ajax_function' );

if ( ! function_exists( 'tempera_ajax_function' ) ) :
function tempera_ajax_function(){
	ob_clean();

   // the first part is a SWTICHBOARD that fires specific functions
   // according to the value of Query Var 'fn'

	switch($_REQUEST['fn']){
		case 'get_latest_posts':
			$output = tempera_ajax_get_latest_posts( $_REQUEST['count'], $_REQUEST['categName'] );
		break;
		default:
			$output = 'No function specified, check your jQuery.ajax() call';
		break;
	}

	// at this point, $output contains some sort of valuable data!
	// Now, convert $output to JSON and echo it to the browser
	// That way, we can recapture it with jQuery and run our success function

	$output = json_encode($output);
	if ( is_array($output) ) { print_r($output); }
						else { echo $output; }
	die;
} // tempera_ajax_function()
endif;

if ( ! function_exists( 'tempera_ajax_get_latest_posts' ) ) :
function tempera_ajax_get_latest_posts( $count, $categName ){
	$testVar='';
	// The Query
	$the_query = new WP_Query( 'category_name=' . $categName);
	// The Loop
	if ( $the_query->have_posts() ) :
		while ( $the_query->have_posts() ) : $the_query->the_post();
			$testVar .= the_title("<option>","</option>",0);
		endwhile;
	endif;
	return $testVar;
} // tempera_ajax_get_latest_posts()
endif;

if ( ! function_exists( 'tempera_get_sidebar' ) ) :
function tempera_get_sidebar() {
	global $temperas;
	switch ($temperas['tempera_side']) {

		case '2cSl':
			get_sidebar('left');
		break;

		case '2cSr':
			get_sidebar('right');
		break;

		case '3cSl' : case '3cSr' : case '3cSs' :
			get_sidebar('left');
			get_sidebar('right');
		break;

	}
} // tempera_get_sidebar()
endif;

if ( ! function_exists( 'tempera_get_layout_class' ) ) :
function tempera_get_layout_class() {
	global $temperas;
	switch ($temperas['tempera_side']) {
		case '2cSl': return "two-columns-left"; break;
		case '2cSr': return "two-columns-right"; break;
		case '3cSl': return "three-columns-left"; break;
		case '3cSr' : return "three-columns-right"; break;
		case '3cSs' : return "three-columns-sided"; break;
		case '1c':
		default: return "one-column"; break;
	}
} // tempera_get_layout_class()
endif;


/**
* Retrieves the IDs for images in a gallery.
* @since tempera 1.0.3
* @return array List of image IDs from the post gallery.
*/
function tempera_get_gallery_images() {
       $images = array();

       if ( function_exists( 'get_post_galleries' ) ) {
               $galleries = get_post_galleries( get_the_ID(), false );
               if ( isset( $galleries[0]['ids'] ) )
                       $images = explode( ',', $galleries[0]['ids'] );
       } else {
               $pattern = get_shortcode_regex();
               preg_match( "/$pattern/s", get_the_content(), $match );
               $atts = shortcode_parse_atts( $match[3] );
               if ( isset( $atts['ids'] ) )
                       $images = explode( ',', $atts['ids'] );
       }

       if ( ! $images ) {
               $images = get_posts( array(
                       'fields'         => 'ids',
                       'numberposts'    => 999,
                       'order'          => 'ASC',
                       'orderby'        => 'none',
                       'post_mime_type' => 'image',
                       'post_parent'    => get_the_ID(),
                       'post_type'      => 'attachment',
               ) );
       }

       return $images;
} // tempera_get_gallery_images()


/**
* Checks the browser agent string for mobile ids and adds "mobile" class to body if true
* @return array list of classes.
*/
function tempera_mobile_body_class($classes){
	global $temperas;
	if ($temperas['tempera_mobile']=="Enable"):
		$browser = (isset($_SERVER['HTTP_USER_AGENT'])?$_SERVER['HTTP_USER_AGENT']:'');
		$keys = 'mobile|android|mobi|tablet|ipad|opera mini|series 60|s60|blackberry';
		if (preg_match("/($keys)/i",$browser)): $classes[] = 'temobile'; endif; // mobile browser detected
	endif;
	return $classes;
}
add_filter('body_class', 'tempera_mobile_body_class');


////////// HELPER FUNCTIONS //////////

function cryout_optset($var,$val1,$val2='',$val3='',$val4=''){
	$vals = array($val1,$val2,$val3,$val4);
	if (in_array($var,$vals)): return false; else: return true; endif;
} // cryout_optset()

function cryout_fontname_cleanup( $fontid ) {
    // do not process non font ids
    if ( strtolower(trim($fontid)) == 'general font' ) return $fontid;
    $fontid = trim($fontid);
    $fonts = @explode(",", $fontid);
    // split multifont ids into fonts array
    if (is_array($fonts)){
        foreach ($fonts as &$font) {
            $font = trim($font);
            // if font has space in name, quote it
            if (strpos($font,' ')>-1) $font = '"' . $font . '"';
        };
        return implode(', ',$fonts);
    } elseif (strpos($fontid,' ')>-1) {
        // if font has space in name, quote it
        return '"' . $fontid . '"';
    } else return $fontid;
} // cryout_fontname_cleanup

function cryout_hex2rgb($hex) {
   $hex = str_replace("#", "", $hex);
   if (preg_match("/^([a-f0-9]{3}|[a-f0-9]{6})$/i",$hex)):
        if(strlen($hex) == 3) {
           $r = hexdec(substr($hex,0,1).substr($hex,0,1));
           $g = hexdec(substr($hex,1,1).substr($hex,1,1));
           $b = hexdec(substr($hex,2,1).substr($hex,2,1));
        } else {
           $r = hexdec(substr($hex,0,2));
           $g = hexdec(substr($hex,2,2));
           $b = hexdec(substr($hex,4,2));
        }
        $rgb = array($r, $g, $b);
        return implode(",", $rgb); // returns the rgb values separated by commas
   else: return "";  // input string is not a valid hex color code
   endif;
} // cryout_cryout_hex2rgb()


function cryout_hexadder($hex,$inc) {
   $hex = str_replace("#", "", $hex);
   if (preg_match("/^([a-f0-9]{3}|[a-f0-9]{6})$/i",$hex)):
        if(strlen($hex) == 3) {
           $r = hexdec(substr($hex,0,1).substr($hex,0,1));
           $g = hexdec(substr($hex,1,1).substr($hex,1,1));
           $b = hexdec(substr($hex,2,1).substr($hex,2,1));
        } else {
           $r = hexdec(substr($hex,0,2));
           $g = hexdec(substr($hex,2,2));
           $b = hexdec(substr($hex,4,2));
        }

		$rgb_array = array($r,$g,$b);
		$newhex="#";
		foreach ($rgb_array as $el) {
			$el+=$inc;
			if ($el<=0) { $el='00'; }
			elseif ($el>=255) {$el='ff';}
			else {$el=dechex($el);}
			if(strlen($el)==1)  {$el='0'.$el;}
			$newhex.=$el;
		}
		return $newhex;
   else: return "";  // input string is not a valid hex color code
   endif;
} // cryout_cryout_hex2rgb()

// FIN