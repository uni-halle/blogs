<?php
add_shortcode('breadcrump', 'breadcrump');
function breadcrump($options) {
	global $post;

	$ancestors = $post->ancestors;
	$ancestors = array_reverse($ancestors);
//	if($ancestors) {
		$breadcrump_nav = "<nav class='breadcrump'>";
		$breadcrump_nav .= "<a class='breadcrump__link' href='" . esc_url( home_url( '/' ) ) . "'>Home</a>&nbsp;";
		foreach($ancestors as $key => $ancestor){
			$breadcrump_nav .= "› <a class='breadcrump__link' href='" . get_permalink($ancestor) . "'>" . get_the_title($ancestor) . "</a> ";
		}
		$breadcrump_nav .= "› <a class='breadcrump__link' href='" . get_permalink($post->ID) . "'>" . get_the_title($post->ID) . "</a>";
		$breadcrump_nav .= "</nav>";
		return $breadcrump_nav;
//	}
}


?>