<?php
/**
 * The loop that displays an attachments.
 *
 *
 * @package WordPress
 * @subpackage Choco
 */
get_header(); ?>

<?php if ( have_posts() ) while ( have_posts() ) : the_post(); ?>

		<div id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
			<h1 class="entry-title"><?php the_title(); ?></h1>

			<div class="entry-meta">
				<?php
					printf( __( '<span class="%1$s">By</span> %2$s', 'choco' ),
						'meta-prep meta-prep-author',
						sprintf( '<span class="author vcard"><a class="url fn n" href="%1$s" title="%2$s">%3$s</a></span>',
							esc_url( get_author_posts_url( get_the_author_meta( 'ID' ) ) ),
							esc_attr( sprintf( __( 'View all posts by %s', 'choco' ), get_the_author() ) ),
							esc_html( get_the_author() )
						)
					);
				?>
				<span class="meta-sep">|</span>
			    <?php
			    	if ( ! empty( $post->post_parent ) ) :
			    		$metadata = wp_get_attachment_metadata();
			    		printf( __( '<span class="meta-prep meta-prep-entry-date">Published </span> <span class="entry-date"><abbr class="published" title="%1$s">%2$s</abbr></span>  at <a href="%3$s" title="Link to full-size image">%4$s &times; %5$s</a> in <a href="%6$s" title="Return to %7$s" rel="gallery">%7$s</a>', 'choco' ),
			    			esc_attr( get_the_time() ),
			    			esc_html( get_the_date() ),
			    			wp_get_attachment_url(),
			    			$metadata['width'],
			    			$metadata['height'],
			    			esc_url( get_permalink( $post->post_parent ) ),
			    			esc_html( get_the_title( $post->post_parent ) )
			    		);
			    ?>
			    	<?php edit_post_link( __( 'Edit', 'choco' ), '<span class="meta-sep">|</span> <span class="edit-link">', '</span>' ); ?>

			    <?php
			    	else :

			    	$metadata = wp_get_attachment_metadata();
			    	printf( __( '<span class="meta-prep meta-prep-entry-date">Uploaded </span> at <a href="%1$s" title="Link to full-size image">%2$s &times; %3$s</a>', 'choco' ),
			    		wp_get_attachment_url(),
			    		$metadata['width'],
			    		$metadata['height']
			    	);
			    ?>
			    	<?php edit_post_link( __( 'Edit', 'choco' ), '<span class="meta-sep">|</span> <span class="edit-link">', '</span>' ); ?>
			    <?php endif; ?>
			</div><!-- .entry-meta -->

			<div class="entry-content">
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
							$attachment_size = apply_filters( 'choco_attachment_size', 645 );
							echo wp_get_attachment_image( $post->ID, array( $attachment_size, 9999 ) ); // filterable image width with, essentially, no limit for image height.
						?></a></p>

						<div class="post-navigation clear-fix">
							<div class="nav-previous"><?php previous_image_link( false ); ?></div>
							<div class="nav-next"><?php next_image_link( false ); ?></div>
						</div><!-- .post-navigation -->

					<?php else : ?>
						<a href="<?php echo wp_get_attachment_url(); ?>" title="<?php echo esc_attr( get_the_title() ); ?>" rel="attachment"><?php echo basename( get_permalink() ); ?></a>
					<?php endif; ?>
				</div><!-- .entry-attachment -->

				<div class="entry-caption"><?php if ( !empty( $post->post_excerpt ) ) the_excerpt(); ?></div>

				<?php the_content( __( 'Continue reading <span class="meta-nav">&rarr;</span>', 'choco' ) ); ?>

				<?php wp_link_pages( array( 'before' => '<div class="page-link">' . __( 'Pages:', 'choco' ), 'after' => '</div>' ) ); ?>
			</div><!-- .entry-content -->

			<div class="entry-utility">
				<?php edit_post_link( __( 'Edit', 'choco' ), ' <span class="edit-link">', '</span>' ); ?>
			</div><!-- .entry-utility -->
		</div><!-- #post-## -->

	<?php comments_template(); ?>

<?php endwhile; // end of the loop. ?>

<?php get_sidebar(); ?>
<?php get_footer(); ?>