<?php /* Template Name: Past */ ?>

<?php

$catID = ICL_LANGUAGE_CODE === 'de' ? 5 : 9;

$args = array(
		'post_type' => 'post',
		'cat' => $catID,
		'post_status' => 'publish',
		'order' => 'DESC',
		'posts_per_page' => -1
);
$past = new WP_Query($args);
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

			<?php
			// Start the Loop.
			if ($past->have_posts()) :

				?>

				<section id="archive_articles" class="archive">

					<header class="page-header"><h1><?php _e('Vergangene Veranstaltungen', 'dct'); ?></h1></header>
					<div class="section-posts">
						<?php
						while ($past->have_posts()) : $past->the_post();

							get_template_part('template-parts/content');

						endwhile;
						?>

					</div>
				</section>

				<?php
			endif;
			?>

		</main>
	</div>  <!-- #primary -->

<?php get_footer();