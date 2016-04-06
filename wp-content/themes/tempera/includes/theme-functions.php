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
	$temperas= tempera_get_theme_options();
	foreach ($temperas as $key => $value) { ${"$key"} = $value ; }
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
<script type="text/javascript">
function makeDoubleDelegate(function1, function2) {
	/* concatenate functions */
    return function() { if (function1) function1(); if (function2) function2(); }
}

function tempera_onload() {
<?php if ($tempera_mobile=="Enable") { // If mobile view is enabled ?>
    /* Add responsive videos */
    jQuery(".entry-content").fitVids();
	<?php } ?>
}; // tempera_onload

jQuery(document).ready(function(){
<?php if ($tempera_mobile=="Enable") { // If mobile view is enabled ?>
	/* enable mobile menu handle */
	tempera_mobilemenu_init();
<?php } ?>
});

// make sure not to lose previous onload events
window.onload = makeDoubleDelegate(window.onload, tempera_onload );
</script>
<?php
} // tempera_header_scripts()

add_action('wp_head','tempera_header_scripts',100);


 /**
 * Adds title and description to heaer
 * Used in header.php
*/
function tempera_title_and_description() {
	$temperas = tempera_get_theme_options();
	foreach ($temperas as $key => $value) { ${"$key"} = $value ; }
	// Header styling and image loading
	// Check if this is a post or page, if it has a thumbnail, and if it's a big one
	global $post;

	if (get_header_image() != '') { $himgsrc=get_header_image(); }
	if ( is_singular() && has_post_thumbnail( $post->ID ) && $tempera_fheader == "Enable" &&
		( $image = wp_get_attachment_image_src( get_post_thumbnail_id( $post->ID ), 'header' ) ) &&
		$image[1] >= HEADER_IMAGE_WIDTH ) : $himgsrc= $image[0];
	endif;


	if (isset($himgsrc) && ($himgsrc != '')) : echo '<img id="bg_image" alt="" title="" src="'.$himgsrc.'"  />';  endif;
?>
<div id="header-container">
<?php

	switch ($tempera_siteheader) {
		case 'Site Title and Description':
			echo '<div>';
			$heading_tag = ( ( is_home() || is_front_page() ) && !is_page() ) ? 'h1' : 'div';
			echo '<'.$heading_tag.' id="site-title">';
			echo '<span> <a href="'.esc_url( home_url( '/' ) ).'" title="'.esc_attr( get_bloginfo( 'name', 'display' ) ).'" rel="home">'.get_bloginfo( 'name' ).'</a> </span>';
			echo '</'.$heading_tag.'>';
			echo '<div id="site-description" >'.get_bloginfo( 'description' ).'</div></div>';
		break;

		case 'Clickable header image' :
			echo '<a href="'.esc_url( home_url( '/' ) ).'" id="linky"></a>' ;
		break;

		case 'Custom Logo' :
			if (isset($tempera_logoupload) && ($tempera_logoupload != '')) :
				echo '<div><a id="logo" href="'.esc_url( home_url( '/' ) ).'" ><img title="" alt="" src="'.$tempera_logoupload.'" /></a></div>';
			endif;
		break;

		case 'Empty' :
		break;
	}
	echo '</div>';
} // tempera_title_and_description()

add_action ('cryout_branding_hook','tempera_title_and_description');


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

//Adding socials to the topbar
if($tempera_socialsdisplay0) add_action('cryout_topbar_hook', 'tempera_header_socials',13);
// Adding socials to the footer
if($tempera_socialsdisplay3) add_action('cryout_footer_hook', 'tempera_footer_socials',13);
// Adding socials to the left and right browser sides
if($tempera_socialsdisplay4) add_action('cryout_wrapper_hook', 'tempera_slefts_socials',13);
if($tempera_socialsdisplay5) add_action('cryout_wrapper_hook', 'tempera_srights_socials',13);


if ( ! function_exists( 'tempera_set_social_icons' ) ) :
/**
 * Social icons function
 */
function tempera_set_social_icons($idd) {
	$cryout_special_keys = array('Mail', 'Skype', 'Phone');
	global $temperas;
	foreach ($temperas as $key => $value) {
		${"$key"} = $value ;
	}
	echo '<div class="socials" id="'.$idd.'">';
	for ($i=1; $i<=9; $i+=2) {
		$j=$i+1;
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
function tempera_back_top() {
	echo '<div id="toTop"><i class="icon-back2top"></i> </div>';
} // tempera_back_top()

if ($tempera_backtop=="Enable") add_action ('cryout_main_hook','tempera_back_top');


 /**
 * Creates breadcrumns with page sublevels and category sublevels.
 */
function tempera_breadcrumbs() {
	$temperas= tempera_get_theme_options();
	foreach ($temperas as $key => $value) { ${"$key"} = $value ; }
	global $post;
	$separator = "<i class='icon-angle-right'></i> ";
	if (is_page() && !is_front_page() || is_single() || is_category() || is_archive()) {
		echo '<div class="breadcrumbs">';
        echo '<a href="'.home_url().'"><i class="icon-homebread"></i></a>'.$separator ;

        if (is_page()) {

			$ancestors = get_post_ancestors($post);
            if ($ancestors) {

				$ancestors = array_reverse($ancestors);
                foreach ($ancestors as $crumb) {
                    echo '<a href="'.get_permalink($crumb).'">'.get_the_title($crumb).$separator.'</a>';
                }
            }
        }

        if (is_single()) {
			if (has_category()) {
				$category = get_the_category();
				echo '<a href="'.get_category_link($category[0]->cat_ID).'">'.$category[0]->cat_name.$separator.'</a>';
			}
        }

        if (is_category()) {
            $category = get_the_category();
            echo ''.$category[0]->cat_name.'';
        }
		
		if (is_tag()) {
			echo ''.__('Tag','tempera').''.$separator.single_tag_title('', false);
		} 

        // Current page
        if (is_page() || is_single()) {
            echo ''.get_the_title().'';
        }
       echo '</div>';
    }
	elseif (is_home() && $tempera_frontpage!="Enable" ) {
        // Front page
        echo '<div class="breadcrumbs">';
        echo '<a href="'.home_url().'"><i class="icon-homebread"></i></a> '.$separator;
        _e('Home Page','tempera');
        echo '</div>';
    }

} // tempera_breadcrumbs()


if($tempera_breadcrumbs=="Enable")  add_action ('cryout_before_content_hook','tempera_breadcrumbs');


if ( ! function_exists( 'tempera_pagination' ) ) :
/**
 * Creates pagination for blog pages.
 */
function tempera_pagination($pages = '', $range = 2, $prefix ='')
{
     $showitems = ($range * 2)+1;

     global $paged;
     if(empty($paged)) $paged = 1;

     if($pages == '')
     {
         global $wp_query;
         $pages = $wp_query->max_num_pages;
         if(!$pages)
         {
             $pages = 1;
         }
     }

     if(1 != $pages)
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
'link_before'      => '<em>',
'link_after'       => '</em>',
);
$r = wp_parse_args($args, $defaults);
return $r;
}
add_filter('wp_link_pages_args','tempera_nextpage_links');


/**
 * Site info
 */
function tempera_site_info() {
	$temperas = tempera_get_theme_options();
	foreach ($temperas as $key => $value) { ${"$key"} = $value ; }	?>
	<span style="display:block;float:right;text-align:right;padding:5px 20px 5px;text-transform:uppercase;font-size:11px;">
	<?php _e('Powered by','tempera')?> <a target="_blank" href="<?php echo 'http://www.cryoutcreations.eu';?>" title="<?php echo 'Tempera Theme by '.
			'Cryout Creations';?>"><?php echo 'Tempera' ?></a> &amp; <a target="_blank" href="<?php echo esc_url('http://wordpress.org/' ); ?>"
			title="<?php esc_attr_e('Semantic Personal Publishing Platform', 'tempera'); ?>"> <?php printf(' %s.', 'WordPress' ); ?>
		</a>
	</span><!-- #site-info -->
	<?php
} // tempera_site_info()

add_action('cryout_footer_hook','tempera_site_info',99);


/**
 * Copyright text
 */
function tempera_copyright() {
	$temperas = tempera_get_theme_options();
	foreach ($temperas as $key => $value) { ${"$key"} = $value ; }
	echo '<div id="site-copyright">'.$tempera_copyright.'</div>';
} // tempera_copyright()


if ($tempera_copyright != '') add_action('cryout_footer_hook','tempera_copyright',11);

add_action('wp_ajax_nopriv_do_ajax', 'tempera_ajax_function');
add_action('wp_ajax_do_ajax', 'tempera_ajax_function');

if ( ! function_exists( 'tempera_ajax_function' ) ) :
function tempera_ajax_function(){
	ob_clean();

   // the first part is a SWTICHBOARD that fires specific functions
   // according to the value of Query Var 'fn'

	switch($_REQUEST['fn']){
		case 'get_latest_posts':
			$output = tempera_ajax_get_latest_posts($_REQUEST['count'],$_REQUEST['categName']);
		break;
		default:
			$output = 'No function specified, check your jQuery.ajax() call';
		break;
	}

	// at this point, $output contains some sort of valuable data!
	// Now, convert $output to JSON and echo it to the browser
	// That way, we can recapture it with jQuery and run our success function

	$output=json_encode($output);
	if(is_array($output)) { print_r($output); }
	                 else { echo $output; }
	die;
} // tempera_ajax_function()
endif;

if ( ! function_exists( 'tempera_ajax_get_latest_posts' ) ) :
function tempera_ajax_get_latest_posts($count,$categName){
	$testVar='';
	// The Query
	$the_query = new WP_Query( 'category_name='.$categName);
	// The Loop
	if ( $the_query->have_posts() ) :
		while ( $the_query->have_posts() ) : $the_query->the_post();
			$testVar .=the_title("<option>","</option>",0);
		endwhile;
	endif;
	return $testVar;
} // tempera_ajax_get_latest_posts()
endif;

if ( ! function_exists( 'tempera_get_sidebar' ) ) :
function tempera_get_sidebar() {
	$temperas = tempera_get_theme_options();
	foreach ($temperas as $key => $value) { ${"$key"} = $value ; }
	switch($tempera_side) {

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

		default:
		break;
	}
} // tempera_get_sidebar()
endif;

if ( ! function_exists( 'tempera_get_layout_class' ) ) :
function tempera_get_layout_class() {
	$temperas = tempera_get_theme_options();
	foreach ($temperas as $key => $value) { ${"$key"} = $value ; }
	switch($tempera_side) {
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
	$temperas = tempera_get_theme_options(); 
	if ($temperas['tempera_mobile']=="Enable"): 
		$browser = (isset($_SERVER['HTTP_USER_AGENT'])?$_SERVER['HTTP_USER_AGENT']:''); 
		$keys = 'mobile|android|mobi|tablet|ipad|opera mini|series 60|s60|blackberry'; 
		if (preg_match("/($keys)/i",$browser)): $classes[] = 'mobile'; endif; // mobile browser detected 
	endif; 
	return $classes; 
} 
 
add_filter('body_class', 'tempera_mobile_body_class');

////////// HELPER FUNCTIONS //////////

function cryout_optset($var,$val1,$val2='',$val3='',$val4=''){
	$vals = array($val1,$val2,$val3,$val4);
	if (in_array($var,$vals)): return false; else: return true; endif;
} // cryout_optset()

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
