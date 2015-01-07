<?php
/**
 * @package Choco
 */
$format = get_post_format();
if ( false === $format )
	$format = 'standard';
?>
<div <?php post_class( 'post' ); ?>>

	<?php // Print entry format if the post is not standard format. ?>
	<?php if ( 'standard' != $format ) : ?>
		<a class="entry-format" href="<?php echo esc_url( get_post_format_link( get_post_format() ) ); ?>" title="<?php echo esc_attr( sprintf( __( 'All %s posts', 'choco' ), get_post_format_string( get_post_format() ) ) ); ?>"><?php echo get_post_format_string( get_post_format() ); ?></a>
	<?php endif; ?>

	<div class="post-title-holder clear-fix">
		<?php // Don't display post titles if the post is either aside or quote format. ?>
		<?php if ( 'aside' != $format && 'quote' != $format && 'link' != $format ) : ?>
			<h2 class="post-title">
				<?php if ( ! is_single() ) : ?>
					<a href="<?php the_permalink(); ?>" rel="bookmark"><?php the_title(); ?></a>
				<?php else : ?>
					<?php the_title(); ?>
				<?php endif; ?>
			</h2>
		<?php endif; ?>

		<?php // Don't display post dates for pages in search result page. ?>
		<?php if ( $post->post_type=='post' ) : ?>
			<div class="date">
				<?php if ( ! is_single() ) : ?>
				<a href="<?php the_permalink(); ?>" rel="bookmark" title="<?php echo esc_attr( sprintf( __( 'Permalink to %s', 'choco' ), the_title_attribute( 'echo=0' ) ) ); ?>">
					<?php the_time( 'F d' ); ?>
				</a>
				<?php else : ?>
					<?php the_time( 'F d' ); ?>
				<?php endif; ?>
			</div><!-- .date -->
		<?php endif ?>
	</div><!-- /.post-title-holder clear-fix -->

	<div class="entry">
		<?php if ( has_post_thumbnail() ) { ?>
			<?php if ( ! is_single() ) : ?>
				<a href="<?php the_permalink(); ?>" rel="bookmark" title="<?php echo esc_attr( sprintf( __( 'Permalink to %s', 'choco' ), the_title_attribute( 'echo=0' ) ) ); ?>">
					<?php the_post_thumbnail( 'thumbnail', array( 'class' => 'post-thumbnail', 'alt' => get_the_title(), 'title' => get_the_title() ) ); ?>
				</a>
			<?php else : ?>
				<?php the_post_thumbnail( 'thumbnail', array( 'class' => 'post-thumbnail', 'alt' => get_the_title(), 'title' => get_the_title() ) ); ?>
			<?php endif; ?>
		<?php } ?>

		<?php the_content( 'Read the rest of this entry &raquo;' ); ?>

		<div class="cl">&nbsp;</div>

		<?php wp_link_pages( array( 'before' => '<div class="page-navigation"><p><strong>' . __( 'Pages:', 'choco' ) . ' </strong> ', 'after' => '</p></div>', 'next_or_number' => 'number' ) ); ?>

		<?php edit_post_link( __( 'Edit', 'choco' ), '<span class="edit-link">', '</span>' ); ?>
	</div><!-- .entry -->

	<?php // Don't display post dates for pages in search result page. ?>
	<?php if ( $post->post_type=='post' ) : ?>
		<div class="meta">
			<div class="meta-inner clear-fix">
				<p><?php _e( 'Posted by', 'choco' ); ?> <?php the_author_posts_link(); ?> <?php _e( 'on', 'choco' ); ?> <?php echo get_the_date( get_option( 'date_format' ) ); ?> <?php _e( 'in', 'choco' ); ?> <?php the_category( ', ' ); ?></p>
				<span class="comments-num"><?php comments_popup_link( __( 'Leave a comment', 'choco' ), __( '1 Comment', 'choco' ), __( '% Comments', 'choco' ) ); ?></span>
			</div><!-- /.meta-inner clear-fix -->
		</div><!-- .meta -->
		<?php the_tags( '<p class="tags">' . __( 'Tags:', 'choco' ) . ' ', ', ', '</p>' ); ?>
	<?php endif ?>
</div>