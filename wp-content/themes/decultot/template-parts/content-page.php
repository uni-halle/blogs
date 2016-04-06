<?php
/**
 * The template used for displaying page content
 *
 * @package WordPress
 * @subpackage Twenty_Sixteen
 * @since Twenty Sixteen 1.0
 */
?>

<article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
	<?php // if ( !is_front_page() ) : ?>
		<header class="entry-header">
			<?php the_title( '<h1 class="entry-title">', '</h1>' ); ?>
		</header><!-- .entry-header -->
	<?php // endif; ?>

	<?php twentysixteen_post_thumbnail(); ?>

	<div class="entry-content">
		<?php
		the_content();

		?>
	</div><!-- .entry-content -->

	<?php
		edit_post_link(
			sprintf(
				/* translators: %s: Name of current post */
				__( 'Edit<span class="screen-reader-text"> "%s"</span>', 'twentysixteen' ),
				get_the_title()
			),
			'<footer class="entry-footer"><span class="edit-link">',
			'</span></footer><!-- .entry-footer -->'
		);
	?>

</article><!-- #post-## -->

<?php
	if(is_front_page()) :

		$catID = ICL_LANGUAGE_CODE === 'de' ? 5 : 9;
		$args = array(
				'post_type' => 'post',
				'cat' => $catID,
				'post_status' => 'publish',
				'posts_per_page' => 3,
		);
		$news = new WP_Query($args);
		?>

		<h2><?php echo get_cat_name($catID);?></h2>

		<div id="home_articles" class="archive cf">
			<!--				<h2>--><?php //_e('Letzte Nachrichten'); ?><!--</h2>-->
			<?php
			// Start the Loop.
			if( $news->have_posts() ) :
				while ( $news->have_posts() ) : $news->the_post();

					/*
					 * Include the Post-Format-specific template for the content.
					 * If you want to override this in a child theme, then include a file
					 * called content-___.php (where ___ is the Post Format name) and that will be used instead.
					 */
					get_template_part( 'template-parts/content', 'home-news' );

					// End the loop.
				endwhile;
			endif;

			?>

		</div>	<!-- #archive_articles -->

		<?php
	endif;
?>