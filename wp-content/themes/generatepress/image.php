<?php
/**
 * The template for displaying image attachments.
 *
 * @package Generate
 */

get_header();
?>

	<div id="primary" <?php generate_content_class('image-attachment'); ?>>
		<main id="main" class="site-main" role="main">
		<?php do_action('generate_before_main_content'); ?>
		<?php while ( have_posts() ) : the_post(); ?>

			<article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
				<div class="inside-article">
					<header class="entry-header">
						<?php the_title( '<h1 class="entry-title">', '</h1>' ); ?>

						<div class="entry-meta">
							<?php
								$metadata = wp_get_attachment_metadata();
								printf( __( 'Published <span class="entry-date"><time class="entry-date" datetime="%1$s">%2$s</time></span> at <a href="%3$s" title="Link to full-size image">%4$s &times; %5$s</a> in <a href="%6$s" title="Return to %7$s" rel="gallery">%8$s</a>', 'generate' ),
									esc_attr( get_the_date( 'c' ) ),
									esc_html( get_the_date() ),
									esc_url( wp_get_attachment_url() ),
									$metadata['width'],
									$metadata['height'],
									esc_url( get_permalink( $post->post_parent ) ),
									esc_attr( strip_tags( get_the_title( $post->post_parent ) ) ),
									get_the_title( $post->post_parent )
								);

								//edit_post_link( __( 'Edit', 'generate' ), '<span class="edit-link">', '</span>' );
							?>
						</div><!-- .entry-meta -->
					</header><!-- .entry-header -->

					<div class="entry-content">
						<div class="entry-attachment">
							<div class="attachment">
								<?php generate_the_attached_image(); ?>
							</div><!-- .attachment -->

							<?php if ( has_excerpt() ) : ?>
							<div class="entry-caption">
								<?php the_excerpt(); ?>
							</div><!-- .entry-caption -->
							<?php endif; ?>
						</div><!-- .entry-attachment -->

						<?php
							the_content();
							wp_link_pages( array(
								'before' => '<div class="page-links">' . __( 'Pages:', 'generate' ),
								'after'  => '</div>',
							) );
						?>
					</div><!-- .entry-content -->

					<footer class="entry-meta">
						<nav role="navigation" id="image-navigation" class="image-navigation">
							<div class="nav-previous"><span class="prev"><?php previous_image_link( false, __( 'Previous', 'generate' ) ); ?></span></div>
							<div class="nav-next"><span class="next"><?php next_image_link( false, __( 'Next', 'generate' ) ); ?></span></div>
						</nav><!-- #image-navigation -->
						<?php
							if ( comments_open() && pings_open() ) : // Comments and trackbacks open
								printf( __( '<a class="comment-link" href="#respond" title="Post a comment">Post a comment</a> or leave a trackback: <a class="trackback-link" href="%s" title="Trackback URL for your post" rel="trackback">Trackback URL</a>.', 'generate' ), esc_url( get_trackback_url() ) );
							elseif ( ! comments_open() && pings_open() ) : // Only trackbacks open
								printf( __( 'Comments are closed, but you can leave a trackback: <a class="trackback-link" href="%s" title="Trackback URL for your post" rel="trackback">Trackback URL</a>.', 'generate' ), esc_url( get_trackback_url() ) );
							elseif ( comments_open() && ! pings_open() ) : // Only comments open
								 _e( 'Trackbacks are closed, but you can <a class="comment-link" href="#respond" title="Post a comment">post a comment</a>.', 'generate' );
							elseif ( ! comments_open() && ! pings_open() ) : // Comments and trackbacks closed
								_e( 'Both comments and trackbacks are currently closed.', 'generate' );
							endif;

							edit_post_link( __( 'Edit', 'generate' ), ' <span class="edit-link">', '</span>' );
						?>
					</footer><!-- .entry-meta -->
				</div><!-- .inside-article -->
			</article><!-- #post-## -->

			<?php
				// If comments are open or we have at least one comment, load up the comment template
				if ( comments_open() || '0' != get_comments_number() )
					comments_template();
			?>

		<?php endwhile; // end of the loop. ?>
		<?php do_action('generate_after_main_content'); ?>
		</main><!-- #main -->
	</div><!-- #primary -->

<?php 
do_action('generate_sidebars');
get_footer(); ?>