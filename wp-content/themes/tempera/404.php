<?php
/**
 * The template for displaying 404 pages (Not Found).
 *
 * @package Cryout Creations
 * @subpackage tempera
 * @since tempera 0.5
 */

get_header(); ?>

	<div id="container" class="<?php echo tempera_get_layout_class(); ?>">
	
		<div id="content" role="main">

			<div id="post-0" class="post error404 not-found">
				<h1 class="entry-title"><?php _e( 'Not Found', 'tempera' ); ?></h1>
				<div class="entry-content">
					<div class="contentsearch">
					<p><?php _e( 'Apologies, but the page you requested could not be found. Perhaps searching will help.', 'tempera' ); ?></p>
					<?php get_search_form(); ?>
					</div>
				</div><!-- .entry-content -->
			</div><!-- #post-0 -->

		</div><!-- #content -->
<?php tempera_get_sidebar(); ?>
	</div><!-- #container -->
<?php get_footer(); ?>