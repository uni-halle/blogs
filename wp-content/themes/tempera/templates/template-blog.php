<?php /*
Template Name: Blog Template ( Posts Page)
*/ ?>


<?php get_header(); ?>

		<section id="container" class="<?php echo tempera_get_layout_class(); ?>">
			<div id="content" role="main">
			<?php cryout_before_content_hook(); ?>
	


	<?php 
$paged = (get_query_var('paged')) ? get_query_var('paged') : 1;
$the_query = new WP_Query( 'post_status=publish&orderby=date&order=desc&posts_per_page='.get_option('posts_per_page').'&paged=' . $paged);?>


		<?php if ( $the_query->have_posts() ) : 

				 /* Start the Loop */ 
				 while ( $the_query->have_posts() ) : $the_query->the_post(); 
					 global $more; $more=0; 
					 get_template_part( 'content/content', get_post_format() ); 

				 endwhile; 

				if ( $temperas['tempera_pagination']=="Enable" ) tempera_pagination($the_query->max_num_pages); else tempera_content_nav( 'nav-below' );

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

			<?php endif; ?>



			<?php cryout_after_content_hook(); ?>
			</div><!-- #content -->
	<?php tempera_get_sidebar(); ?>
		</section><!-- #container -->

<?php get_footer(); ?>
