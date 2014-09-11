<?php get_header(); ?>

<div id="content" class="fullpage">

	<?php if ( have_posts() ) while ( have_posts() ) : the_post(); ?>

				<div id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
				<div class="post-header">
				<h1><?php the_title(); ?></h1>
				<p>
				<?php
							printf(__('<span class="%1$s">By</span> %2$s', 'jenny'),
								'meta-prep meta-prep-author',
								sprintf( '<span class="author vcard"><a class="url fn n" href="%1$s" title="%2$s">%3$s</a></span>',
									get_author_posts_url( get_the_author_meta( 'ID' ) ),
									sprintf( esc_attr__( 'View all posts by %s', 'jenny' ), get_the_author() ),
									get_the_author()
								)
							);
						?>
						|
						<?php
							printf( __('%2$s', 'jenny'),
								'meta-prep meta-prep-entry-date',
								sprintf( '%2$s',
									esc_attr( get_the_time() ),
									get_the_date()
								)
							);
							if ( wp_attachment_is_image() ) {
								echo ' <span class="meta-sep">|</span> ';
								$metadata = wp_get_attachment_metadata();
								printf( __( 'Full size is %s pixels', 'jenny'),
									sprintf( '<a href="%1$s" title="%2$s">%3$s &times; %4$s</a>',
										wp_get_attachment_url(),
										esc_attr( __('Link to full-size image', 'jenny') ),
										$metadata['width'],
										$metadata['height']
									)
								);
							}
						?>
						<?php edit_post_link( __( 'Edit', 'jenny' ), '| ', '' ); ?>
					</p>
				</div>


				<div class="entry-attachment">
<?php if ( wp_attachment_is_image() ) :
	$attachments = array_values( get_children( array( 'post_parent' => $post->post_parent, 'post_status' => 'inherit', 'post_type' => 'attachment', 'post_mime_type' => 'image', 'order' => 'ASC', 'orderby' => 'menu_order ID' ) ) );
	foreach ( $attachments as $k => $attachment ) {
		if ( $attachment->ID == $post->ID )
			break;
	}
	$k++;
	// If there is more than 1 image attachment in a gallery
	if ( count( $attachments ) > 1 ) {
		if ( isset( $attachments[ $k ] ) )
			// get the URL of the next image attachment
			$next_attachment_url = get_attachment_link( $attachments[ $k ]->ID );
		else
			// or get the URL of the first image attachment
			$next_attachment_url = get_attachment_link( $attachments[ 0 ]->ID );
	} else {
		// or, if there's only 1 image attachment, get the URL of the image
		$next_attachment_url = wp_get_attachment_url();
	}
?>
						<p class="attachment"><a href="<?php echo $next_attachment_url; ?>" title="<?php echo esc_attr( get_the_title() ); ?>" rel="attachment"><?php
							$attachment_size = apply_filters( 'jenny_attachment_size', 860 );
							echo wp_get_attachment_image( $post->ID, array( $attachment_size, 9999 ) ); // filterable image width with, essentially, no limit for image height.
						?></a></p>

						<div class="entry-caption"><?php if ( !empty( $post->post_excerpt ) ) the_excerpt(); ?></div>

						<?php the_content( __('<p> Read more &raquo;</p>', 'jenny') ); ?>
						<?php wp_link_pages( __('before=<div class="post-page-links">Pages:&after=</div>', 'jenny')) ; ?>

						<ul class="attachment-navi">
							<li><?php previous_image_link( false, __('&laquo; Previous Image', 'jenny') ); ?></li>
							<li><?php next_image_link( false, __('Next Image &raquo;', 'jenny') ); ?></li>
						</ul><!-- #nav-below -->
						
						<?php if ( ! empty( $post->post_parent ) ) : ?>
						<p class="return-attachment">Return to <a href="<?php echo get_permalink( $post->post_parent ); ?>" title="<?php esc_attr( printf( __( 'Return to %s', 'jenny' ), get_the_title( $post->post_parent ) ) ); ?>" rel="gallery"><?php
								printf( __( '%s', 'jenny' ), get_the_title( $post->post_parent ) );
							?></a></p>
						
						<div class="post-meta">
							<ul>
								<li><?php comments_popup_link( __('Leave your comment', 'jenny'), __( '1 comment', 'jenny'), __('% comments', 'jenny')); ?> &bull; <?php the_category(' &bull; ');?></li>
								<?php the_tags( __('<li>Tagged as: ', 'jenny'), ' &bull; ', '</li>'); ?>
								<li><?php _e('Share on ', 'jenny'); ?><a href="http://twitter.com/home?status=Currently reading: <?php the_title_attribute(); ?> <?php the_permalink(); ?>"><?php _e('Twitter','jenny'); ?></a>, <a href="http://www.facebook.com/share.php?u=<?php the_permalink(); ?>&amp;t=<?php the_title_attribute(); ?>"><?php _e('Facebook', 'jenny'); ?></a>, <a href="http://del.icio.us/post?v=4;url=<?php the_permalink(); ?>"><?php _e('Delicious', 'jenny'); ?></a>, <a href="http://digg.com/submit?url=<?php the_permalink(); ?>"><?php _e('Digg', 'jenny'); ?></a>, <a href="http://www.reddit.com/submit?url=<?php the_permalink(); ?>&amp;title=<?php the_title_attribute(); ?>"><?php _e('Reddit', 'jenny'); ?></a></li>
								<?php edit_post_link(__('Edit this post','jenny'), '<li>', '</li>'); ?>
							</ul>
						</div>
						<?php endif; ?>

<?php else : ?>
						<a href="<?php echo wp_get_attachment_url(); ?>" title="<?php echo esc_attr( get_the_title() ); ?>" rel="attachment"><?php echo basename( get_permalink() ); ?></a>
<?php endif; ?>


		</div><!-- .entry-attachment -->

		</div><!-- #post-## -->

<?php comments_template(); ?>
<?php endwhile; ?>

</div><!--#content-->

<?php get_footer(); ?>