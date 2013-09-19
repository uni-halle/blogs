<?php
/**
 * @package WordPress
 * @subpackage Aggiornare
 */

// Load main options panel file
require_once (TEMPLATEPATH . '/functions/aggiornare-menu.php');

$content_width = 600;

automatic_feed_links();

if ( function_exists('register_sidebar') ) {
register_sidebar(array('name'=>'Home Sidebar',
		'before_widget' => '<li id="%1$s" class="sidebarItem widget %2$s">',
		'after_widget' => '</li>',
		'before_title' => '<h2 class="widgettitle">',
		'after_title' => '</h2>',
	));

	register_sidebar(array('name'=>'Sidebar',
		'before_widget' => '<li id="%1$s" class="sidebarItem widget %2$s">',
		'after_widget' => '</li>',
		'before_title' => '<h2 class="widgettitle">',
		'after_title' => '</h2>',
	));
	
	register_sidebar(array('name'=>'Footer - Left Column',
'before_widget' => '',
'after_widget' => '',
'before_title' => '<h3>',
'after_title' => '</h3>',
));
register_sidebar(array('name'=>'Footer - Right Column',
'before_widget' => '<div class="footerWidget">',
'after_widget' => '</div>',
'before_title' => '<h3>',
'after_title' => '</h3>',
));
}

function my_search_form($form) {
$form = '<form method="get" id="searchform" action="' . get_option('home') . '/" >
<div><label class="hidden" for="s">' . __('Search for:') . '</label>
<input type="text" value="' . attribute_escape(apply_filters('the_search_query', get_search_query())) . '" name="s" id="s" />
<input type="submit" id="searchsubmit" value="'.attribute_escape(__('Search')).'" />
</div>
</form>';
return $form;
}

add_filter('get_search_form', 'my_search_form'); 

?>