<?php /* Template Name: News */ ?>

<?php

$catID = ICL_LANGUAGE_CODE === 'de' ? 5 : 9;

$args = array(
		'post_type' => 'post',
		'cat' => $catID,
		'post_status' => 'future',
		'order' => 'ASC',
		'posts_per_page' => -1
);
$news = new WP_Query($args);
?>


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

		<?php if ($news->have_posts()) : ?>
			<div id="archive_articles" class="archive">

				<header class="page-header">
					<h1 class="page-title">
						<?php echo get_the_title();; ?>
					</h1>
				</header><!-- .page-header -->

				<div id="articles" class="cf">

				<?php
				// Start the Loop.

					while ($news->have_posts()) : $news->the_post();

						/*
						 * Include the Post-Format-specific template for the content.
						 * If you want to override this in a child theme, then include a file
						 * called content-___.php (where ___ is the Post Format name) and that will be used instead.
						 */
						get_template_part('template-parts/content');

						// End the loop.
					endwhile;

				?>

				</div>	<!-- #articles -->
			</div>  <!-- #archive_articles -->
		<?php else :
			get_template_part('template-parts/content', 'no-upcoming-events');

		endif; ?>

			<p class="past-events read-more">
				<a href="<?php echo get_category_link($catID); ?>"><?php _e('Vergangene Veranstaltungen', 'dct'); ?></a>
			</p>
		</main>
	</div>	<!-- #primary -->

	<?php get_footer();