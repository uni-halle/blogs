<?php
/**
 * The template for displaying all pages.
 *
 * This is the template that displays all pages by default.
 * Please note that this is the WordPress construct of pages
 * and that other 'pages' on your WordPress site will use a
 * different template.
 *
 * @package Cryout Creations
 * @subpackage tempera
 * @since tempera 0.5
 */
get_header();
if ( ($temperas['tempera_frontpage']=="Enable") && is_front_page() && 'posts' == get_option( 'show_on_front' )): get_template_part( 'frontpage' );
else :
?>
		<section id="container" class="<?php echo tempera_get_layout_class(); ?>">

			<div id="content" role="main">
			<?php cryout_before_content_hook(); ?>

				<?php get_template_part( 'content/content', 'page'); ?>

			<?php cryout_after_content_hook(); ?>
			</div><!-- #content -->
			<?php tempera_get_sidebar(); ?>
		</section><!-- #container -->


<?php
endif;
get_footer();
?>
