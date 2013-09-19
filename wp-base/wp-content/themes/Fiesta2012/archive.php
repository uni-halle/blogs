<?php 
get_header(); 
if (have_posts()) 
{
	$post = $posts[0];
	$title = '';
	if (is_category()){
		$title .= single_cat_title('', false);
	} elseif( is_tag() ) {
		$title .= sprintf(__('Posts Tagged &#8216;%s&#8217;', THEME_NS), single_tag_title('', false) );
	} elseif( is_day() ) {
		$title .= sprintf(_c('Archive for %s|Daily archive page', THEME_NS), get_the_date());
	} elseif( is_month() ) {
		$title .= sprintf(_c('Archive for %s|Monthly archive page', THEME_NS), get_the_date('F Y'));
	} elseif( is_year() ) {
		$title .= sprintf(_c('Archive for %s|Yearly archive page', THEME_NS), get_the_date('Y'));
	} elseif( is_author() ) {
		$title .= __('Author Archive', THEME_NS);
	} elseif( isset($_GET['paged']) && !empty($_GET['paged']) ) {
		$title .= __('Blog Archives', THEME_NS);
	}
	art_page_navi($title);
    while (have_posts())  
    {
        art_post();
    }
    art_page_navi();
} else {    
	$title = '';
	if ( is_category() ) {
		$title .= sprintf(__("Sorry, but there aren't any posts in the %s category yet.", THEME_NS), single_cat_title('',false));
	} else if ( is_date() ) { 
		$title .= __("Sorry, but there aren't any posts with this date.", THEME_NS);
	} else if ( is_author() ) { 
		$userdata = get_userdatabylogin(get_query_var('author_name'));
		$title .= sprintf(__("Sorry, but there aren't any posts by %s yet.", THEME_NS), $userdata->display_name);
	} else {
		$title .= __('No posts found.', THEME_NS);
	}
    art_not_found_msg($title);
}
get_footer(); 
