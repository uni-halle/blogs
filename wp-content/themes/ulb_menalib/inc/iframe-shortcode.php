<?php
/**
 * Shortcode für iframes in Posts
 *
 * @package ulb_menalib 
 */



function iFrame($atts, $content) 
{
 if (!$atts['width']) { $atts['width'] = '100%'; }
 if (!$atts['height']) { $atts['height'] = '400'; }
 if (!$atts['class']) { $atts['class'] = 'iframe'; }

 return '<iframe border="0" src="' . $atts['src'] . '" width="' . $atts['width'] . '" height="' . $atts['height'] . '" class="' . $atts['class'] . '" allowfullscreen></iframe>';
}

add_shortcode('iframe', 'iFrame');



/*
Usage: 

[list_subpages style="test"]

*/
