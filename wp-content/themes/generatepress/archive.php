<?php
/**
 * The template for displaying Archive pages.
 *
 * Learn more: http://codex.wordpress.org/Template_Hierarchy
 *
 * @package GeneratePress
 */

get_header(); ?>

	<section id="primary" <?php generate_content_class(); ?>>
		<main id="main" <?php generate_main_class(); ?>>
		<?php do_action('generate_before_main_content'); ?>
		<?php if ( have_posts() ) : ?>

			<header class="page-header<?php if ( is_author() ) echo ' clearfix';?>">
				<?php do_action( 'generate_before_archive_title' ); ?>
				<h1 class="page-title">
					<?php
						if ( is_category() ) :
							single_cat_title();

						elseif ( is_tag() ) :
							single_tag_title();

						elseif ( is_author() ) :
							/* Queue the first post, that way we know
							 * what author we're dealing with (if that is the case).
							*/
							the_post();
							echo get_avatar( get_the_author_meta( 'ID' ), 75 );
							printf( __( 'About %s', 'generate' ), '<span class="vcard">' . get_the_author() . '</span>' );
							/* Since we called the_post() above, we need to
							 * rewind the loop back to the beginning that way
							 * we can run the loop properly, in full.
							 */
							rewind_posts();

						elseif ( is_day() ) :
							printf( __( 'Day: %s', 'generate' ), '<span>' . get_the_date() . '</span>' );

						elseif ( is_month() ) :
							printf( __( 'Month: %s', 'generate' ), '<span>' . get_the_date( 'F Y' ) . '</span>' );

						elseif ( is_year() ) :
							printf( __( 'Year: %s', 'generate' ), '<span>' . get_the_date( 'Y' ) . '</span>' );

						elseif ( is_tax( 'post_format', 'post-format-aside' ) ) :
							_e( 'Asides', 'generate' );

						elseif ( is_tax( 'post_format', 'post-format-image' ) ) :
							_e( 'Images', 'generate');

						elseif ( is_tax( 'post_format', 'post-format-video' ) ) :
							_e( 'Videos', 'generate' );

						elseif ( is_tax( 'post_format', 'post-format-quote' ) ) :
							_e( 'Quotes', 'generate' );

						elseif ( is_tax( 'post_format', 'post-format-link' ) ) :
							_e( 'Links', 'generate' );

						else :
							_e( 'Archives', 'generate' );

						endif;
					?>
				</h1>
				<?php do_action( 'generate_after_archive_title' ); ?>
				<?php
					// Show an optional term description.
					$term_description = term_description();
					if ( ! empty( $term_description ) ) :
						printf( '<div class="taxonomy-description">%s</div>', $term_description );
					endif;
					
					if ( get_the_author_meta('description') && is_author() ) : // If a user has filled out their decscription show a bio on their entries
						echo '<div class="author-info">' . get_the_author_meta('description') . '</div>';
					endif;
				?>
				<?php do_action( 'generate_after_archive_description' ); ?>
			</header><!-- .page-header -->

			<?php /* Start the Loop */ ?>
			<?php while ( have_posts() ) : the_post(); ?>

				<?php
					/* Include the Post-Format-specific template for the content.
					 * If you want to override this in a child theme, then include a file
					 * called content-___.php (where ___ is the Post Format name) and that will be used instead.
					 */
					get_template_part( 'content', get_post_format() );
				?>

			<?php endwhile; ?>

			<?php generate_content_nav( 'nav-below' ); ?>

		<?php else : ?>

			<?php get_template_part( 'no-results', 'archive' ); ?>

		<?php endif; ?>
		<?php do_action('generate_after_main_content'); ?>
		</main><!-- #main -->
	</section><!-- #primary -->

<?php 
do_action('generate_sidebars');
get_footer();