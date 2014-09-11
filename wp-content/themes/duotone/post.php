<?php
/**
 * @package Duotone
 */
?>

<div id="container"<?php post_class(); ?>>

	<?php if ( is_singular() ) : ?>
		<?php the_title( '<h2 class="post-title">', '</h2>' ); ?>
	<?php else: ?>
		<?php the_title( '<h2 class="post-title"><a href="' . esc_url( get_permalink() ) . '" rel="bookmark" title="' .  the_title_attribute( array( 'echo' => false ) ) . '">', '</a></h2>' ); ?>
	<?php endif; ?>

<div id="postmetadata">
	<div class="sleeve">
		<?php if ( ! is_page() ) : ?>
		<p><?php printf( __( 'By: %1$s', 'duotone' ), '<cite>' . esc_html( get_the_author() ) . '</cite>' ); ?></p>
		<p><small><a href="<?php echo get_month_link( get_the_time( 'Y' ), get_the_time( 'm' ) ); ?>"><?php the_time( __( 'M d Y', 'duotone' ) ); ?></a></small></p>
		<p><?php the_tags( __( 'Tags: ', 'duotone' ), ', ', '<br />' ); ?></p>

		<p><?php _e( 'Category:', 'duotone' ); ?> <?php the_category( ', ' ) ?></p>
		<p><?php edit_post_link( __( 'Edit This Post', 'duotone' ), '', '' ); ?></p>

		<?php if ( ! post_password_required() && ( comments_open() || '0' != get_comments_number() ) ) : ?>
			<p><?php comments_popup_link(
				__( 'Leave a Comment', 'duotone' ),
				__( '1 Comment',       'duotone' ),
				__( '% Comments',      'duotone' )
			); ?></p>
		<?php endif; ?>

		<?php else: ?>

		<p><?php edit_post_link( __( 'Edit This Page', 'duotone' ), '', '' ); ?></p>

		<?php endif; ?>

		<?php Duotone::exif_table(); ?>
	</div>
</div>

<div id="post">
	<div class="sleeve">
		<?php the_content( __( 'Read the rest of this entry &rarr;', 'duotone' ) ); ?>
		<?php wp_link_pages( array(
			'before' => '<div class="page-link">' . __( 'Pages: ', 'duotone' ),
			'after'  => '</div>',
		) ) ; ?>
	</div>
</div>

<?php if ( is_single() ) : ?>
	<div class="navigation">
		<div class="prev"><?php next_post_link( '%link', '&rsaquo;' ); ?></div>
		<div class="next"><?php previous_post_link( '%link', '&lsaquo;' ); ?></div>
	</div>
	<?php comments_template(); ?>
<?php endif; ?>
</div>
