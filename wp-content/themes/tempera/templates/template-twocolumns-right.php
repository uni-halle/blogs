<?php /*
 * Template Name: Two Columns, Sidebar on the Right
 *
 * @package Cryout Creations
 * @subpackage tempera
 * @since tempera 0.5
 */
get_header(); ?>

		<section id="container" class="two-columns-right">
	
			<div id="content" role="main">
				<?php cryout_before_content_hook(); ?>

				<?php get_template_part( 'content/content', 'page'); ?>

				<?php cryout_after_content_hook(); ?>
			</div><!-- #content -->
			<?php get_sidebar('right'); ?>
		</section><!-- #container -->


<?php get_footer(); ?>