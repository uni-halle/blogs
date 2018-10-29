<?php
/**
 * Shortcode für tag cloud in Posts
 *
 * @package ulb_menalib 
 */




function tagcloud($echo = false) 
{
    if (function_exists('wp_tag_cloud'))
    {
        return wp_tag_cloud( array('number' => '99') );
    }
}

add_shortcode('tagcloud', 'tagcloud');



/*
Usage: 

[list_subpages style="test"]

*/
