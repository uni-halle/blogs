<?php
/**
 * The main template file.
 *
 * This is the most generic template file in a WordPress theme
 * and one of the two required files for a theme (the other being style.css).
 * It is used to display a page when nothing more specific matches a query.
 * E.g., it puts together the home page when no home.php file exists.
 * Learn more: http://codex.wordpress.org/Template_Hierarchy
 *
 * @package Esquire
 */

get_header(); ?>

	<?php
		/* Queue the first post, that way we know
		 * what date we're dealing with (if that is the case).
		 *
		 * We reset this later so we can run the loop
		 * properly with a call to rewind_posts().
		 */
		if ( have_posts() )
			the_post();
	?>

	<?php if ( is_archive() ) : ?>
	<div id="header">
		<h2>
		<?php if ( is_day() ) : ?>
			<?php printf( __( 'Posted on %s &hellip;', 'esquire' ), '<span>' . get_the_date() . '</span>' ); ?>
		<?php elseif ( is_month() ) : ?>
			<?php printf( __( 'Posted in %s &hellip;', 'esquire' ), '<span>' . get_the_date( 'F Y' ) . '</span>' ); ?>
		<?php elseif ( is_year() ) : ?>
			<?php printf( __( 'Posted in %s &hellip;', 'esquire' ), '<span>' . get_the_date( 'Y' ) . '</span>' ); ?>
		<?php elseif( is_author() ) : ?>
			<?php printf( __( 'Posted by %s &hellip;', 'esquire' ), '<span>' . get_the_author() . '</span>' ); ?>
		<?php elseif ( is_category() ) : ?>
			<?php printf( __( 'Filed under %s &hellip;', 'esquire' ), '<span>' . single_cat_title( '', false ) . '</span>' ); ?>
		<?php elseif ( is_tag() ) : ?>
			<?php printf( __( 'Tagged with %s &hellip;', 'esquire' ), '<span>' . single_tag_title( '', false ) . '</span>' ); ?>
		<?php endif; ?>
		</h2>
	</div>
	<?php endif; ?>
	<?php if ( is_search() ) : ?>
	<div id="header">
		<h2>
			<?php printf( __( 'Matches for: &ldquo;%s&rdquo; &hellip;', 'esquire' ), '<span>' . get_search_query() . '</span>' ); ?>
		</h2>
	</div>
	<?php endif; ?>

	<?php
		/* Since we called the_post() above, we need to
		 * rewind the loop back to the beginning that way
		 * we can run the loop properly, in full.
		 */
		rewind_posts();
	?>

	<div id="posts">
		<div class="hentry image-attachment">
			<div class="postbody text">
				<h1><?php the_title(); ?></h1>
				<div class="content">
					<?php
						/**
						 * Grab the IDs of all the image attachments in a gallery so we can get the URL of the next adjacent image in a gallery,
						 * or the first image (if we're looking at the last image in a gallery), or, in a gallery of one, just the link to that image file
						 */
						$attachments = array_values( get_children( array( 'post_parent' => $post->post_parent, 'post_status' => 'inherit', 'post_type' => 'attachment', 'post_mime_type' => 'image', 'order' => 'ASC', 'orderby' => 'menu_order ID' ) ) );
						foreach ( $attachments as $k => $attachment ) {
							if ( $attachment->ID == $post->ID )
								break;
						}
						$k++;
						// If there is more than 1 attachment in a gallery
						if ( count( $attachments ) > 1 ) {
							if ( isset( $attachments[ $k ] ) )
								// get the URL of the next image attachment
								$next_attachment_url = get_attachment_link( $attachments[ $k ]->ID );
							else
								// or get the URL of the first image attachment
								$next_attachment_url = get_attachment_link( $attachments[ 0 ]->ID );
						} else {
							// or, if there's only 1 image, get the URL of the image
							$next_attachment_url = wp_get_attachment_url();
						}
					?>
					<p>
						<a href="<?php echo $next_attachment_url; ?>" title="<?php echo esc_attr( get_the_title() ); ?>" rel="attachment"><?php
					$attachment_size = apply_filters( 'esquire_attachment_size', 560 );
					echo wp_get_attachment_image( $post->ID, array( 560, 560 ) );
					?></a>
					</p>

					<?php if ( ! empty( $post->post_excerpt ) ) : ?>
					<p><?php the_excerpt(); ?></p>
					<?php endif; ?>

					<?php the_content(); ?>
					<?php wp_link_pages( array( 'before' => '<div class="page-link">' . __( 'Pages:', 'esquire' ), 'after' => '</div>' ) ); ?>

					<p>
						<?php
							$metadata = wp_get_attachment_metadata();
							printf( __( 'Published <abbr class="published" title="%1$s">%2$s</abbr> at <a href="%3$s" title="Link to full-size image">%4$s &times; %5$s</a> in <a href="%6$s" title="Return to %7$s" rel="gallery">%7$s</a>', 'esquire' ),
								esc_attr( get_the_time() ),
								get_the_date(),
								wp_get_attachment_url(),
								$metadata['width'],
								$metadata['height'],
								get_permalink( $post->post_parent ),
								get_the_title( $post->post_parent )
							);
						?>
						<?php edit_post_link( __( 'Edit', 'esquire' ), '<span class="sep">|</span> <span class="edit-link">', '</span>' ); ?>
					</p>
				</div><!-- .content -->
			</div><!-- .postbody -->
		</div>
	</div><!-- #posts -->

<?php get_footer(); ?>