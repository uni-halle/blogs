<?php
/**
 * Returns a string with HTML for a post / page preview
 *
 * @package ulb_menalib 
 */



function get_short_post_preview_by_id($postId = 0, $style = '') 
{

	$post = the_post($postId);


	

	$articleId = 'post-'.$postId;
    $style = $articleId.' '.$style;


   $title = get_the_title($postId);
    $excerpt = get_the_excerpt($postId);
   // $excerpt = get_excerpt_by_id($postId);
    $permalink = get_the_permalink($postId);

	$output = '';
    
    $output .= '<article id="'.$articleId.'" class="'.$style.'">';

    $output .= '<header class="entry-header">';
  	$output .= '<h2 class="entry-title">';
    $output .= $title;
    $output .= '</h2>';
    $output .= '</header>';

    $output .= '<div class="entry-content">';
	$output .= $excerpt.'-';
	$output .= '<a class="" href="'.$permalink.'">'.'more'.'</a>';
    $output .= '</div>';

    $output .= '<footer class="entry-meta">';
    $output .= '</footer>';


    $output .=  '</article>';

    wp_reset_query();

    

    return $output;

}



