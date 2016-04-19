<?php

// region ADD ACTIONS

if( ! function_exists( 'add_dct_styles' ) ) {
	function add_dct_styles() {
		wp_enqueue_style('style-dct', get_theme_root_uri().'/decultot/css/decultot.css');
		wp_enqueue_script('script-mfp', get_theme_root_uri().'/decultot/js/jquery.magnific-popup.min.js');
		wp_enqueue_script('script-dct', get_theme_root_uri().'/decultot/js/decultot.js');
	}
}
add_action('wp_enqueue_scripts', 'add_dct_styles', 11);

if( ! function_exists( 'dequeue_my_css' ) ) {
	function dequeue_my_css()
	{
		wp_dequeue_style('genericons');
		wp_deregister_style('genericons');
	}
}
//add_action('wp_enqueue_scripts','dequeue_my_css', 100);


function register_footer_menu() {
	register_nav_menu('footer-menu', __( 'Footer Menu' ));
}
add_action( 'init', 'register_footer_menu' );


if( ! function_exists('sort_events') ) {
	function sort_events($query){
		if( ! is_admin()
				&& $query->is_main_query()
				&& is_category( [ 5, 9 ] )
		){

			$query->set( 'post_status', 'future' );
			$query->set( 'order', 'ASC' );
			$query->set( 'posts_per_page', -1 );

		}
	}
}
//add_action( 'pre_get_posts', 'sort_events' );

// endregion ADD ACTIONS


// region ADD SHORTCODES

if ( !function_exists('dct_email') ) {
	function dct_email($atts, $content = null) {
		return '<span class="mail-me"></span>';
	}
}
add_shortcode( 'email', 'dct_email' );


if ( !function_exists('dct_seitenanfang') ) {
	function dct_seitenanfang($atts, $content = null) {
		$scrolltoplink = '<p class="seitenanfang"><a class="scroll-top-link">' . __( 'Seitenanfang', 'dct' ) . '</a></p>';

		return $scrolltoplink;
	}
}
add_shortcode( 'seitenanfang', 'dct_seitenanfang' );


if ( !function_exists('dct_video') ) {
	function dct_video($atts, $content = null) {
		$video =
			'<video id="video_decultot" width="640" height="360" controls poster="'. get_theme_root_uri()
			.'/decultot/video/Decultot-AvHProfessur_Vorschau640_3.jpg">
			  <source src="'. get_theme_root_uri().'/decultot/video/01-decultot_de.mp4" type="video/mp4">
				Your browser does not support the video tag.
			</video>';

		return $video;
	}
}
add_shortcode( 'video', 'dct_video' );

// endregion ADD SHORTCODES


// region ADD FILTERS

function modify_read_more_link() {
	return '<p><a class="read-more" href="' . get_permalink() . '">mehr</a>';
}
add_filter( 'the_content_more_link', 'modify_read_more_link' );


function limit_posts_per_page() {
	if ( is_category( [ 5, 9 ] ) )
		return 6;
	else if ( is_category( [ 6, 10 ] ) )
		return 3;
	else if ( is_category( [ 7, 11 ] ) )
		return -1;
	else
		return 12; // default: 5 posts per page
}
add_filter('pre_option_posts_per_page', 'limit_posts_per_page');


/* Show future posts for all categories, post types etc. */
function show_future_posts($posts)
{
	global $wp_query, $wpdb;
	if(is_single() && $wp_query->post_count == 0)
	{
		$posts = $wpdb->get_results($wp_query->request);
	}
	return $posts;
}
add_filter('the_posts', 'show_future_posts');


function future_permalink( $permalink, $post, $leavename, $sample = false ) {
	/* for filter recursion (infinite loop) */
	static $recursing = false;

	if ( empty( $post->ID ) ) {
		return $permalink;
	}

	if ( !$recursing ) {
		if ( isset( $post->post_status ) && ( 'future' === $post->post_status ) ) {
			// set the post status to publish to get the 'publish' permalink
			$post->post_status = 'publish';
			$recursing = true;
			return get_permalink( $post, $leavename ) ;
		}
	}

	$recursing = false;
	return $permalink;
}
// post, page post type
add_filter( 'post_link', 'future_permalink', 10, 3 );
// custom post types
add_filter( 'post_type_link', 'future_permalink', 10, 4 );


function new_join($pjoin){
	if(is_category( [ 7, 8, 11, 12 ] )){
		global $wpdb;
		$pjoin .= "LEFT JOIN (
				SELECT *
				FROM $wpdb->postmeta
				WHERE meta_key =  'nachname_gaeste' ) AS metasort
				ON $wpdb->posts.ID = metasort.post_id";
		}
	return ($pjoin);
}
add_filter('posts_join', 'new_join' );

function new_order( $orderby ){
	global $wpdb;
	$posts_table = $wpdb->prefix . 'posts';

	if(is_category( [ 7, 8, 11, 12 ] )){
		$orderby = "metasort.meta_value ASC, ".$posts_table.".post_title ASC";
	}

	return $orderby;
}
add_filter('posts_orderby', 'new_order' );


function wpa_45815($arr){
	$arr['block_formats'] = 'Paragraph=p;Heading 2=h2;Heading 3=h3;Heading 4=h4;Heading 5=h5;Heading 6=h6';
	return $arr;
}
add_filter('tiny_mce_before_init', 'wpa_45815');

// endregion ADD FILTERS


// region remove visual composer content elements

vc_remove_element( "vc_facebook" );
vc_remove_element( "vc_googleplus" );
vc_remove_element( "vc_tweetmeme" );
vc_remove_element( "vc_pinterest" );
vc_remove_element( "vc_message" );
vc_remove_element( "vc_icon" );
vc_remove_element( "vc_cta" );
vc_remove_element( "vc_widget_sidebar" );
vc_remove_element( "vc_custom_heading" );
vc_remove_element( "vc_images_carousel" );
vc_remove_element( "vc_gallery" );
vc_remove_element( "vc_toggle" );
vc_remove_element( "vc_masonry_grid" );
vc_remove_element( "vc_masonry_media_grid" );
vc_remove_element( "vc_media_grid" );
vc_remove_element( "vc_line_chart" );
vc_remove_element( "vc_basic_grid" );
vc_remove_element( "vc_round_chart" );
vc_remove_element( "vc_pie" );
vc_remove_element( "vc_progress_bar" );
vc_remove_element( "vc_flickr" );
vc_remove_element( "vc_posts_slider" );
vc_remove_element( "vc_wp_tagcloud" );
vc_remove_element( "vc_wp_pages" );
vc_remove_element( "vc_wp_calendar" );
vc_remove_element( "vc_wp_recentcomments" );
vc_remove_element( "vc_wp_meta" );
vc_remove_element( "vc_wp_search" );
vc_remove_element( "vc_wp_rss" );
vc_remove_element( "vc_wp_archives" );
vc_remove_element( "vc_wp_categories" );
vc_remove_element( "vc_wp_posts" );
vc_remove_element( "vc_wp_text" );
vc_remove_element( "vc_wp_custommenu" );

// endregion remove visual composer content elements
