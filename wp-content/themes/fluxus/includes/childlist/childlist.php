<?php
add_shortcode('childlist', 'childlist');
function childlist($options) {
	global $post;

	$atts = shortcode_atts( array( 'parent' => $post->post_title), $options );
	$parentpage = get_page_by_title( $atts['parent'], '', 'page' );
	if($parentpage){
		$childs = get_pages("parent=".$parentpage->ID."&sort_column=menu_order");
		if($childs) {
			$output = "<ul class='childlist'>";
			foreach($childs as $key => $child) {
				$output .= "<li class='childlist__item'><a class='childlist__link' href='" . get_permalink($child->ID) . "'>" . $child->post_title . "</a></li>";
			}
			$output .= "</ul>";
			return $output;	
		}
	}
}


?>