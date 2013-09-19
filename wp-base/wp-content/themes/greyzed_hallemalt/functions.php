<?php
/**
 * @package WordPress
 * @subpackage Greyzed Theme
 */


//3.0 Menu Management
if (function_exists('add_theme_support')) {
    add_theme_support('menus');
}

function register_my_menus() {
	register_nav_menu( 'main-menu', __( 'Main Menu' ) );
}
add_action( 'init', 'register_my_menus' );
//END 3.0 Menu Management

?>
