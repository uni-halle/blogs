<?php
if ( function_exists('register_sidebar') )
    register_sidebar(array(
        'before_widget' => '<li id="%1$s" class="widget %2$s">',
        'after_widget' => '</li>',
        'before_title' => '<h2 class="widgettitle">',
        'after_title' => '</h2>',
    ));

add_filter('comments_template', 'legacy_comments');
function legacy_comments($file) {
	if(!function_exists('wp_list_comments')) 	$file = TEMPLATEPATH . '/legacy.comments.php';
	return $file;
}

function feed_studylog($comment) {
   load_template( TEMPLATEPATH . '/wplogloader.php' );
}
function add_studylog_feed() {
add_feed('studylog', 'feed_studylog');
}
add_action('init','add_studylog_feed');


?>
