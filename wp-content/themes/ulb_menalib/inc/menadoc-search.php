<?php
/**
 * Shortcode für MENAdoc Suche
 *
 * @package ulb_menalib 
 */



function menadoc_search($atts = [], $tag = '') 
{

      // https://developer.wordpress.org/plugins/shortcodes/shortcodes-with-parameters/
     // normalize attribute keys, lowercase
    $atts = array_change_key_case((array)$atts, CASE_LOWER);
       // override default attributes with user attributes
    $search_atts = shortcode_atts([
                                     'placeholder' => 'Search Ha:Lit',
                                     'submit' => 'Suche',
                                     'style' => 'default',
                                 ], $atts, $tag);
    $placeholder = esc_html__($search_atts['placeholder'], 'search');
    $submit = esc_html__($search_atts['submit'], 'search');
    $style = esc_html__($search_atts['style'], 'search');

   /*print esc_html__($search_atts['placeholder'], 'search');*/
      
return <<<FORMULAR
<div class="external-search $style">
      <form accept-charset="UTF-8" action="https://menadoc.bibliothek.uni-halle.de/search/quick" method="get" target="_blank">

      <!-- Suchbox und Button -->         
            <input name="query" size="5" type="text" value="" placeholder="$placeholder">
            <!--old: <input value="$submit" type="submit">-->
            <button>$submit</button>
    </form>
    </div>

FORMULAR;
}


add_shortcode('menadoc_search', 'menadoc_search');



/*
Usage: 

[menadoc_search submit="Go!" placeholder="Suche und Du wirst finden"]

*/
