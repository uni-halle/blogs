<?php
/**
 * The template for displaying archive pages
 *
 * Used to display archive-type pages if nothing more specific matches a query.
 * For example, puts together date-based pages if no date.php file exists.
 *
 * If you'd like to further customize these archive views, you may create a
 * new template file for each one. For example, tag.php (Tag archives),
 * category.php (Category archives), author.php (Author archives), etc.
 *
 * @link https://codex.wordpress.org/Template_Hierarchy
 *
 * @package WordPress
 * @subpackage Twenty_Sixteen
 * @since Twenty Sixteen 1.0
 */

get_header(); ?>

	<div id="primary" class="content-area">
		<main id="main" class="site-main" role="main">

		<?php if ( have_posts() ) : ?>
			<div id="archive_articles">

			<header class="page-header">
				<h1 class="page-title">
					<?php echo single_cat_title( '', false ); ?>
				</h1>
			</header><!-- .page-header -->

			<div id="articles" class="cf">

				<?php
				// Start the Loop.
				while ( have_posts() ) : the_post();

					/*
					 * Include the Post-Format-specific template for the content.
					 * If you want to override this in a child theme, then include a file
					 * called content-___.php (where ___ is the Post Format name) and that will be used instead.
					 */
					get_template_part( 'template-parts/content', get_post_format() );

				// End the loop.
				endwhile;

				?>

			</div>	<!-- #  articles -->

			<?php

				$is_prev = get_previous_posts_link();
				$is_next = get_next_posts_link();

				if( $is_next || $is_prev ) :
					?>
					<div class="dct-pagenav">
						<?php

							if( $is_prev ) previous_posts_link( __( 'Neuere Beiträge', 'dct' ) );

							if( $is_next && $is_prev ) echo '&nbsp;&nbsp;/&nbsp;&nbsp;';

							if( $is_next ) next_posts_link( __( 'Ältere Beiträge', 'dct' ) );

						?>
					</div>
					<?php
				endif;
				?>

		</div>	<!-- #archive_articles -->

		<?php

		// If no content, include the "No posts found" template.
		else :
			get_template_part( 'template-parts/content', 'none' );

		endif;
		?>

		<?php
			$categories = get_the_category();
			$team_cat = false;

			foreach($categories as $category) {
				if( $category->cat_ID === 7 || $category->cat_ID === 11 ) $team_cat = true;
			}

			if($team_cat) : ?>

				<div id="hilfsmitarbeiter">
					<?php
						if( ICL_LANGUAGE_CODE === 'de' ) $id = 1050; // live
//						if( ICL_LANGUAGE_CODE === 'de' ) $id = 734; // development
						else $id = 1052; // live
//						else $id = 741; // development
						$post = get_post( $id );

						echo do_shortcode($post->post_content);
					?>
				</div>

			<?php endif;

		?>


		</main><!-- .site-main -->
	</div><!-- .content-area -->

<?php get_sidebar(); ?>
<?php get_footer();