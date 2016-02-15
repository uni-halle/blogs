<?php
/**
 * The template for displaying the Front Page.
 *
 * The front-page.php template file is used on the site's front page
 * regardless of whether 'Settings > Reading ->Front page displays' is set to "A static page" or "Your latest posts,"
 *
 * @link https://codex.wordpress.org/Template_Hierarchy
 *
 * @package Coni
 */

get_header(); ?>

	<?php if ( is_home() ) { ?>
	
    	<main id="main" class="site-main col-md-8" role="main">

			<?php if ( have_posts() ) : ?>

				<?php /* Start the Loop */ ?>
				<?php while ( have_posts() ) : the_post(); ?>

					<?php

						/*
						 * Include the Post-Format-specific template for the content.
						 * If you want to override this in a child theme, then include a file
						 * called content-___.php (where ___ is the Post Format name) and that will be used instead.
						 */
						get_template_part( 'template-parts/content', get_post_format() );
					?>

				<?php endwhile; ?>

				<?php get_template_part( 'template-parts/pagination', 'index' ); ?>

			<?php else : ?>

				<?php get_template_part( 'template-parts/content', 'none' ); ?>

			<?php endif; ?>

		</main><!-- #main -->

		<?php get_sidebar(); ?>
	
	<?php }else { ?>
	
		<main id="main" class="site-main" role="main">


	        <?php get_template_part( 'template-parts/section-welcome', 'front-page' ); ?>


			<?php get_template_part( 'template-parts/section-services', 'front-page' ); ?>


			<?php get_template_part( 'template-parts/section-quote', 'front-page' ); ?>


			<?php get_template_part( 'template-parts/section-video', 'front-page' ); ?>


			<?php get_template_part( 'template-parts/section-testimonials', 'front-page' ); ?>


			<?php get_template_part( 'template-parts/section-image', 'front-page' ); ?>


			<?php get_template_part( 'template-parts/section-phone', 'front-page' ); ?>


			<?php get_template_part( 'template-parts/section-tagline', 'front-page' ); ?>


	        <?php get_template_part( 'template-parts/section-clients', 'front-page' ); ?>


	        <?php get_template_part( 'template-parts/section-pricing', 'front-page' ); ?>


	        <?php get_template_part( 'template-parts/section-blog', 'front-page' ); ?>


		</main><!-- #main -->

	<?php } ?>


<?php get_footer(); ?>
