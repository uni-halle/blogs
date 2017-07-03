<?php
/**
 * The frontpage template for displaying posts
 *
 * @package Cryout Creations
 * @subpackage Tempera
 * @since Tempera 1.1
 */
$temperas = tempera_get_theme_options();
?>

		<section id="container" class="one-column <?php //echo tempera_get_layout_class(); ?>">

			<div id="content" role="main">

			<?php //cryout_before_content_hook();

			if ( have_posts() ) :

				/* Start the Loop */
				$paged = ( get_query_var('paged') ) ? get_query_var('paged') : 1;
				$the_query = new WP_Query( array('posts_per_page'=>$temperas['tempera_frontpostscount'],'paged'=> $paged) ); 
				while ( $the_query->have_posts() ) : $the_query->the_post(); 
 
 		            global $more; $more=0; 
					get_template_part( 'content/content', get_post_format() );

				endwhile;

				if($temperas['tempera_pagination']=="Enable") tempera_pagination($the_query->max_num_pages); else tempera_content_nav( 'nav-below' );

			else : ?>

				<article id="post-0" class="post no-results not-found">
					<header class="entry-header">
						<h1 class="entry-title"><?php _e( 'No Posts to Display', 'tempera' ); ?></h1>
					</header><!-- .entry-header -->

					<div class="entry-content">
						<p><?php printf(
							__( 'You currently have no published posts. To hide this message either <a href="%s">add some posts</a> or disable displaying posts on the Presentation Page in <a href="%s">theme settings</a>.', 'tempera' ),
							esc_url( admin_url()."post-new.php"),
							esc_url( admin_url()."themes.php?page=tempera-page") ); ?>
						</p>
					</div><!-- .entry-content -->
				</article><!-- #post-0 -->

			<?php
			endif;
			//cryout_after_content_hook();
			?>

			</div><!-- #content -->
		<?php //tempera_get_sidebar(); ?>
		</section><!-- #container -->
