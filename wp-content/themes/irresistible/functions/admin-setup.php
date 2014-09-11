<?php

// Register widgetized areas
if ( function_exists('register_sidebar') )
    register_sidebars(1,array('name' => 'Sidebar','before_widget' => '<div id="%1$s" class="">','after_widget' => '</div></div>','before_title' => '<h3>','after_title' => '</h3><div class="list3 box1">'));
    register_sidebars(1,array('name' => 'Sidebar Top','before_widget' => '<div id="%1$s" class="">','after_widget' => '</div></div>','before_title' => '<h3>','after_title' => '</h3><div class="list3 box1">'));
	
	

// Options panel stylesheet
function woothemes_admin_head() { 
	echo '<link rel="stylesheet" type="text/css" href="'.get_bloginfo('template_directory').'/functions/admin-style.css" media="screen" />';
}

$options = array();
global $options;

$GLOBALS['template_path'] = get_bloginfo('template_directory');

$layout_path = TEMPLATEPATH . '/layouts/'; 
$layouts = array();

$alt_stylesheet_path = TEMPLATEPATH . '/styles/';
$alt_stylesheets = array();

$ads_path = TEMPLATEPATH . '/images/ads/';
$ads = array();

$woo_categories_obj = get_categories('hide_empty=0');
$woo_categories = array();

$woo_pages_obj = get_pages('sort_column=post_parent,menu_order');
$woo_pages = array();

if ( is_dir($layout_path) ) {
	if ($layout_dir = opendir($layout_path) ) { 
		while ( ($layout_file = readdir($layout_dir)) !== false ) {
			if(stristr($layout_file, ".php") !== false) {
				$layouts[] = $layout_file;
			}
		}	
	}
}	

if ( is_dir($alt_stylesheet_path) ) {
	if ($alt_stylesheet_dir = opendir($alt_stylesheet_path) ) { 
		while ( ($alt_stylesheet_file = readdir($alt_stylesheet_dir)) !== false ) {
			if(stristr($alt_stylesheet_file, ".css") !== false) {
				$alt_stylesheets[] = $alt_stylesheet_file;
			}
		}	
	}
}	

if ( is_dir($ads_path) ) {
	if ($ads_dir = opendir($ads_path) ) { 
		while ( ($ads_file = readdir($ads_dir)) !== false ) {
			if((stristr($ads_file, ".jpg") !== false) || (stristr($ads_file, ".png") !== false) || (stristr($ads_file, ".gif") !== false)) {
				$ads[] = $ads_file;
			}
		}	
	}
}

foreach ($woo_categories_obj as $woo_cat) {
	$woo_categories[$woo_cat->cat_ID] = $woo_cat->cat_name;
}

foreach ($woo_pages_obj as $woo_page) {
	$woo_pages[$woo_page->ID] = $woo_page->post_name;
}

$other_entries = array("Select a number:","1","2","3","4","5","6","7","8","9","10","11","12","13","14","15","16","17","18","19");
$categories_tmp = array_unshift($woo_categories, "Select a category:");
$woo_pages_tmp = array_unshift($woo_pages, "Select a page:");

// OTHER FUNCTIONS

function gravatar($rating = false, $size = false, $default = false, $border = false) {
	global $comment;
	$out = "http://www.gravatar.com/avatar.php?gravatar_id=".md5($comment->comment_author_email);
	if($rating && $rating != '')
		$out .= "&amp;rating=".$rating;
	if($size && $size != '')
		$out .="&amp;size=".$size;
	if($default && $default != '')
		$out .= "&amp;default=".urlencode($default);
	if($border && $border != '')
		$out .= "&amp;border=".$border;
	echo $out;
}

add_action('widgets_init', 'remove_default_widgets', 0);
function remove_default_widgets() {
if (function_exists('unregister_sidebar_widget')) {
unregister_sidebar_widget('Search');
	}
}

// Check for widgets in widget-ready areas http://wordpress.org/support/topic/190184?replies=7#post-808787
// Thanks to Chaos Kaizer http://blog.kaizeku.com/
function is_sidebar_active( $index = 1){
	$sidebars	= wp_get_sidebars_widgets();
	$key		= (string) 'sidebar-'.$index;
 
	return (isset($sidebars[$key]));
}

$bm_trackbacks = array();
$bm_comments = array();

function split_comments( $source ) {

    if ( $source ) foreach ( $source as $comment ) {

        global $bm_trackbacks;
        global $bm_comments;

        if ( $comment->comment_type == 'trackback' || $comment->comment_type == 'pingback' ) {
            $bm_trackbacks[] = $comment;
        } else {
            $bm_comments[] = $comment;
        }
    }
} 

// Show menu in header.php
// Exlude the pages from the slider
function woo_show_pagemenu( $exclude="" ) {
	// Split the featured pages from the options, and put in an array
	if ( get_option('woo_ex_featpages') ) {
		$menupages = get_option('woo_featpages');
		$exclude = $menupages . ',' . $exclude;
	}
	
	$pages = wp_list_pages('sort_column=menu_order&title_li=&echo=0&depth=1&exclude='.$exclude);
	$pages = preg_replace('%<a ([^>]+)>%U','<a $1><span>', $pages);
	$pages = str_replace('</a>','</span></a>', $pages);
	echo $pages;
}

// Get the style path currently selected
function woo_style_path() {
	$style = $_REQUEST[style];
	if ($style != '') {
		$style_path = $style;
	} else {
		$stylesheet = get_option('woo_alt_stylesheet');
		$style_path = str_replace(".css","",$stylesheet);
	}
	if ($style_path == "default")
	  echo 'images/';
	else
	  echo 'styles/'.$style_path;
}

/*
Plugin Name: WP-PageNavi 
Plugin URI: http://www.lesterchan.net/portfolio/programming.php 
*/ 

function wp_pagenavi($before = '', $after = '', $prelabel = '', $nxtlabel = '', $pages_to_show = 5, $always_show = false) {
	global $request, $posts_per_page, $wpdb, $paged;
	if(empty($prelabel)) {
		$prelabel  = '<strong>&laquo;</strong>';
	}
	if(empty($nxtlabel)) {
		$nxtlabel = '<strong>&raquo;</strong>';
	}
	$half_pages_to_show = round($pages_to_show/2);
	if (!is_single()) {
		if(!is_category()) {
			preg_match('#FROM\s(.*)\sORDER BY#siU', $request, $matches);		
		} else {
			preg_match('#FROM\s(.*)\sGROUP BY#siU', $request, $matches);		
		}
		$fromwhere = $matches[1];
		$numposts = $wpdb->get_var("SELECT COUNT(DISTINCT ID) FROM $fromwhere");
		$max_page = ceil($numposts /$posts_per_page);
		if(empty($paged)) {
			$paged = 1;
		}
		if($max_page > 1 || $always_show) {
			echo "$before <div class='Nav'>";
			if ($paged >= ($pages_to_show-1)) {
				echo '<a href="'.get_pagenum_link().'">&laquo; First</a>';
			}
			previous_posts_link($prelabel);
			for($i = $paged - $half_pages_to_show; $i  <= $paged + $half_pages_to_show; $i++) {
				if ($i >= 1 && $i <= $max_page) {
					if($i == $paged) {
						echo "<strong class='on'>$i</strong>";
					} else {
						echo ' <a href="'.get_pagenum_link($i).'">'.$i.'</a> ';
					}
				}
			}
			next_posts_link($nxtlabel, $max_page);
			if (($paged+$half_pages_to_show) < ($max_page)) {
				echo '<a href="'.get_pagenum_link($max_page).'">Last &raquo;</a>';
			}
			echo "</div> $after";
		}
	}
}

//  Get Custom Embed
function woo_get_custom($key, $width=0, $height=0) {

global $post;
$custom_field = get_post_meta($post->ID, $key, true);

if ($custom_field) { 
	
	if ($width == 0) :
		// Get custom width and height
		$width = get_post_meta($post->ID, 'width', true);
		$height = get_post_meta($post->ID, 'height', true);	
	endif;

	// Set values
	if ( !$width ) $width = 'width="313"'; else $width = 'width="'.$width.'"';
	if ( !$height ) $height = 'height="250"'; else $height = 'height="'.$height.'"';
	
	$custom_field = preg_replace( '/width="[^"]+"/' , $width , $custom_field );
	$custom_field = preg_replace( '/height="[^"]+"/' , $height , $custom_field );	
?>
<div class="video">
	<?php echo $custom_field; ?>
</div>
<?php 
}
else { //else, return
	return;
}
}



// This function gets the custom field image and uses thumb.php to resize it
// Parameters: 
// 		$key = Custom field key eg. "image"
// 		$type = Predefined type eg. "featured"
//		$width = Set width manually without using $type
//		$height = Set height manually without using $type
// 		$class = CSS class to use on the img tag eg. "alignleft". Default is "thumbnail"
//		$quality = Enter a quality between 80-100. Default is 95
function woo_get_image($key, $type, $width = 0, $height = 0, $class = "thumbnail", $quality = 90) {

	
// Set defaul sizes if width and height not set
if (!$width && !$height) {
	if ($type == "featured") {
		$width = "540"; $height = "210";
		// Get custom sizes from options panel
		if ( get_option('woo_image_width') && get_option('woo_image_height') ) {
			$width = get_option('woo_image_width');
			$height = get_option('woo_image_height');
		} 		
	} elseif ($type == "home") {
		$width = "247"; $height = "92";
		// Get custom sizes from options panel
		if ( get_option('woo_home_thumb_width') && get_option('woo_home_thumb_height') ) {
			$width = get_option('woo_home_thumb_width');
			$height = get_option('woo_home_thumb_height');
		} 		
	} elseif ($type == "thumbnail") {
		$width = "88"; $height = "88";
		// Get custom sizes from options panel
		if ( get_option('woo_thumb_width') && get_option('woo_thumb_height') ) {
			$width = get_option('woo_thumb_width');
			$height = get_option('woo_thumb_height');
		} 		
	} elseif ($type == "single") {
		$width = "180"; $height = "120";	
		// Get custom sizes from options panel
		if ( get_option('woo_single_width') && get_option('woo_single_height') ) {
			$width = get_option('woo_single_width');
			$height = get_option('woo_single_height');
		} 		
	} elseif ($type == "popular") {
		$width = "35"; $height = "35";	
	}
}

global $post;
$custom_field = get_post_meta($post->ID, $key, true);

if($custom_field) { //if the user set a custom field ?>

<a title="Permanent Link to <?php the_title(); ?>" href="<?php if (is_single()) { echo $custom_field; } else { the_permalink(); } ?>"><img src="<?php echo bloginfo('template_url'); ?>/thumb.php?src=<?php echo $custom_field; ?>&amp;h=<?php echo $height; ?>&amp;w=<?php echo $width; ?>&amp;zc=1&amp;q=<?php echo $quality; ?>" alt="<?php the_title(); ?>" class="<?php echo $class; ?>" /></a>

<?php 
}
else { //else, return
	return;
}
}

?>