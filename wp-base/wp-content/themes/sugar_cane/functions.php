<?php
// Add a link back to your Child Theme release page in the [theme-link] shortcode
function childtheme_theme_link($themelink) {
    return $themelink . ' &amp; <a class="Sugar Cane" href="http://ableparris.com/" title="A sweet Wordpress Theme" rel="designer">Sugar Cane by Able Parris</a>';
}
add_filter('thematic_theme_link', 'childtheme_theme_link');
?>