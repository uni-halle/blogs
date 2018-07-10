<?php
/**
 * Shortcode für Unterseiten in einer Seite
 *
 * @package ulb_menalib 
 */



function list_subpages($atts = [], $tag = '') 
{

      // https://developer.wordpress.org/plugins/shortcodes/shortcodes-with-parameters/
     // normalize attribute keys, lowercase
    $atts = array_change_key_case((array)$atts, CASE_LOWER);
       // override default attributes with user attributes
    $search_atts = shortcode_atts([

                                     'style' => 'default',
                                 ], $atts, $tag);
    $style = esc_html__($search_atts['style']);




$args = array(
    'post_type'      => 'page',
    'posts_per_page' => -1,
    'post_parent'    => get_the_ID(),
    'order'          => 'ASC',
    'orderby'        => 'menu_order'
 );





$output = '<div class="'.$style.'">';

	

$children = get_children( $args);

wp_reset_query();


if ( $children ) 
{





	
	foreach ( $children AS $post) 
	{
		//var $pst = $child->the_post();

/*		$output .=  'ID???  '. $post->ID.'<br><br><br><br><br><br>';*/
		$postId = $post->ID;
		$preview = get_short_post_preview_by_id($postId);

		$output .= $preview;
			
	}
	
}

$output =  $output.'</div>';



return $output;




}


add_shortcode('list_subpages', 'list_subpages');



/*
Usage: 

[list_subpages style="test"]

*/
