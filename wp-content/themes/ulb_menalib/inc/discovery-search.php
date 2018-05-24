<?php
/**
 * Shortcode für Hal:Lit Discovery Suche
 *
 * @package ulb_menalib 
 */



function discovery_search($atts = [], $tag = '') 
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
<div class="discovery-search $style">
      <form accept-charset="UTF-8" action="https://search.ebscohost.com/login.aspx" method="get" target="_blank">
            <input name="direct" value="true" type="hidden">
            <input name="scope" value="site" type="hidden">
            <input name="site" value="eds-live" type="hidden">
      <!-- Authentifizierung via IP, sonst Gast -->
            <input name="authtype" value="ip,guest" type="hidden">
            <input name="custid" value="s6374753" type="hidden">
            <input name="groupid" value="main" type="hidden">
            <input name="profile" value="eds" type="hidden">
      <!-- Einschraenkung: Verfuegbar (1. Limiter ID + Limiter Value: Verfuegbar in Bibliothek + Value Yes) -->
            <input name="cli0" value="FT1" type="hidden">
            <input name="clv0" value="N" type="hidden">
      <!-- Suchbox und Button -->         
            <input name="bquery" size="35" type="text" value="" placeholder="$placeholder">
            <input value="$submit" type="submit">
    </form>
    </div>

FORMULAR;
}


add_shortcode('discovery_search', 'discovery_search');



/*
Usage: 

[discovery_search submit="Go!" placeholder="Suche und Du wirst finden"]

*/
