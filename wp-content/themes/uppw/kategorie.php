<?php
/*
* Template Name: Alle Veranstaltungen
*/

get_header(); ?>

		<div id="container">
			<div id="content" role="main">

        <?php query_posts("cat=1&orderby=date&order=DESC&posts_per_page=10"); ?>

<?php while ( have_posts() ) : the_post(); ?>

		<div id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
			<h2 class="entry-title"><a href="<?php the_permalink(); ?>" title="<?php printf( esc_attr__( 'Permalink to %s', 'twentyten' ), the_title_attribute( 'echo=0' ) ); ?>" rel="bookmark"><?php the_title(); ?></a></h2>


			<div class="entry-content">
				<?php the_content( __( 'Continue reading <span class="meta-nav">&rarr;</span>', 'twentyten' ) ); ?>
				<?php wp_link_pages( array( 'before' => '<div class="page-link">' . __( 'Pages:', 'twentyten' ), 'after' => '</div>' ) ); ?>
			</div><!-- .entry-content -->

		</div><!-- #post-## -->

<?php endwhile; // End the loop. Whew. ?>
			</div><!-- #content -->
		</div><!-- #container -->



<?php get_sidebar(); ?>
<?php get_footer(); ?>