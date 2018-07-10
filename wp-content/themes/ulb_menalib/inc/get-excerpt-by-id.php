<?php
/**
 * Returns the excerpt or a shortened content by a given post id
 *
 * @package ulb_menalib 
 */

function get_excerpt_by_id($postId, $num=35)
{
	$post = get_post($postId);
	$excerpt = '';
	$excerpt = apply_filters('the_excerpt', get_post_field('post_excerpt', $postId));

/*	if($excerpt=='')
	{
		$excerpt = $post->post_content; //Gets post_content to be used as a basis for the excerpt

		$excerpt = strip_tags(strip_shortcodes($excerpt)); //Strips tags and images
		$words = explode(' ', $excerpt, $num + 1);
		if(count($words) > $num) 
		{
			array_pop($words);
			array_push($words, '…');
			$excerpt = implode(' ', $words);
		}
	}*/
	return $excerpt;
}




