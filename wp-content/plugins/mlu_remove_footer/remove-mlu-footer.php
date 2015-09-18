<?php

/**
 * Plugin Name: Footer Removal
 * Description: Remove the MLU-Blogs-Footer
 * Version: 1.0
 * Author: Robert Jäckel
 * Author URI: mailto:robert.jaeckel@itz.uni-halle.de
 */

foreach (['html','css'] as $f) remove_filter('wp_footer','mlublogs_footer_'.$f);

