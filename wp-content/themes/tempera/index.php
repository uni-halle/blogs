<?php
/**
 * The main template file.
 *
 * This is the most generic template file in a WordPress theme
 * and one of the two required files for a theme (the other being style.css).
 * It is used to display a page when nothing more specific matches a query.
 * E.g., it puts together the home page when no home.php file exists.
 * Learn more: http://codex.wordpress.org/Template_Hierarchy
 *
 * @package Cryout Creations
 * @subpackage Tempera
 */
get_header();
if ( ($temperas['tempera_frontpage']=="Enable") && is_front_page() && ('posts' == get_option( 'show_on_front' )) ) {
	get_template_part( 'frontpage' );
// if is_page() -> additional check in page.php
} else { 
	get_template_part('content/content', 'index');
};
get_footer();
?>