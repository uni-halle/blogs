<?php
/**
 * The template for displaying Archive pages.
 *
 * Learn more: http://codex.wordpress.org/Template_Hierarchy
 *
 * @package bueno
 */

get_header(); ?>

	<div id="primary" class="span8 <?php echo esc_attr( of_get_option('blog_sidebar_pos') ) ?>">
		<div id="content" class="site-content" role="main">

		<?php if ( have_posts() ) : ?>

			<header class="page-header">
				<h1 class="page-title">
					<?php
						/* Queue the first post, that way we know
						 * what author we're dealing with (if that is the case).
						*/
						the_post();
						printf( __( 'About: <span>%s</span>', 'bueno' ), get_the_author() );
						/* Since we called the_post() above, we need to
						 * rewind the loop back to the beginning that way
						 * we can run the loop properly, in full.
						 */

					?>
				</h1>
			</header><!-- .page-header -->

			<div class="author-info author-page">
				<figure class="featured-thumbnail thumbnail">
					<?php echo get_avatar( get_the_author_meta( 'user_email' ), 120, '' ,get_the_author_meta( 'nickname' ) ); ?>
				</figure><!-- .author-avatar -->
				<div class="author-description">
					<p>
						<?php the_author_meta( 'description' ); ?>
					</p>
				</div><!-- .author-description -->
			</div><!-- .author-info -->
			<h2 class="page-title">
				<?php
					/* Queue the first post, that way we know
					 * what author we're dealing with (if that is the case).
					*/
					the_post();
					printf( __( 'Author Archives: <span>%s</span>', 'bueno' ), get_the_author() );
					/* Since we called the_post() above, we need to
					 * rewind the loop back to the beginning that way
					 * we can run the loop properly, in full.
					 */

				?>
			</h2>
			<?php rewind_posts(); ?>

			<?php if ( 'one' != of_get_option('blog_page_columns') ) {
				get_template_part( 'templates/blog-two-column' );
			} else {
				get_template_part( 'templates/blog-one-column' );
			} 
			get_template_part('templates/post-nav'); ?>

		<?php else : ?>

			<?php get_template_part( 'templates/no-results' ); ?>

		<?php endif; ?>
		</div><!-- #content -->
	</div><!-- #primary -->

<?php get_sidebar(); ?>
<?php get_footer(); ?>