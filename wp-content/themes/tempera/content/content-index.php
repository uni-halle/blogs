<?php
/**
 * The index template for displaying content
 *
 * @package Cryout Creations
 * @subpackage Tempera
 * @since Tempera 1.1
 */

?>

		<section id="container" class="<?php echo tempera_get_layout_class(); ?>">

			<div id="content" role="main">

			<?php cryout_before_content_hook();

			if ( have_posts() ) :

				/* Start the Loop */
				while ( have_posts() ) : the_post();

					get_template_part( 'content/content', get_post_format() );

				endwhile;

				if ( $temperas['tempera_pagination']=="Enable" ) tempera_pagination(); else tempera_content_nav( 'nav-below' );

			else : ?>

				<article id="post-0" class="post no-results not-found">
					<header class="entry-header">
						<h1 class="entry-title"><?php _e( 'Nothing Found', 'tempera' ); ?></h1>
					</header><!-- .entry-header -->

					<div class="entry-content">
						<p><?php _e( 'Apologies, but no results were found for the requested archive. Perhaps searching will help find a related post.', 'tempera' ); ?></p>
						<?php get_search_form(); ?>
					</div><!-- .entry-content -->
				</article><!-- #post-0 -->

			<?php
			endif;
			cryout_after_content_hook();
			?>

			</div><!-- #content -->
		<?php tempera_get_sidebar(); ?>
		</section><!-- #container -->
