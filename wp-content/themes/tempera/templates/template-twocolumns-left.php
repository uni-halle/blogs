<?php /*
 * Template Name: Two columns, Sidebar on the Left
 *
 * @package Cryout Creations
 * @subpackage tempera
 * @since tempera 0.5
 */
get_header(); ?>

		<section id="container" class="two-columns-left">
	
			<div id="content" role="main">
				<?php cryout_before_content_hook(); ?>

				<?php get_template_part( 'content/content', 'page'); ?>

				<?php cryout_after_content_hook(); ?>
			</div><!-- #content -->
			<?php get_sidebar('left'); ?>
		</section><!-- #container -->


<?php get_footer(); ?>