<?php
/**
 * The default template for displaying content
 *
 * Used for both single and index/archive/search.
 *
 * @package Clean_Traditional
 * @since CleanTraditional 1.0
 */
?>

<article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>

	<header class="entry-header">
		<?php
			$toptitle = get_post_meta( get_the_ID(), 'custom_toptitle', true );
			$subtitle = get_post_meta( get_the_ID(), 'custom_subtitle', true );
			$title_class = empty ( $toptitle ) ? 'entry-title' : 'entry-title has-toptitle'; 
		?>
		
		<?php if ( ! empty ( $toptitle ) ) : ?>
			<?php $css_toptitle_class = ! is_singular() ? 'toptitle is-in-loop' : 'toptitle'; ?>
			<p class="<?php echo $css_toptitle_class; ?>"><?php echo $toptitle; ?></p>
		<?php endif; ?>

		<?php
			if ( is_singular() && ! is_front_page() ) :
				the_title( '<h1 class="' . $title_class . '">', '</h1>' );
			elseif ( ! is_singular() )  :
				the_title( sprintf( '<h2 class="' . $title_class . '"><a href="%s" rel="bookmark">', esc_url( get_permalink() ) ), '</a></h2>' );
			endif;
		?>

		<?php if ( ! empty ( $subtitle ) ) : ?>
			<p class="subtitle"><?php echo $subtitle; ?></p>		
		<?php endif; ?>
		
		<?php
			// Post thumbnail.
			cleantraditional_post_thumbnail();
		?>
	</header><!-- .entry-header -->	

	<div class="entry-content">
		<?php
			/* translators: %s: Name of current post */
			    the_content( sprintf(
				    __( 'weiterlesen %s', 'cleantraditional' ),
				    the_title( '<span class="screen-reader-text">', '</span>', false )
			    ) );

			wp_link_pages( array(
				'before'      => '<div class="page-links"><span class="page-links-title">' . __( 'Seiten:', 'cleantraditional' ) . '</span>',
				'after'       => '</div>',
				'link_before' => '<span>',
				'link_after'  => '</span> ',
				'pagelink'    => '<span class="screen-reader-text">' . __( 'Seite', 'cleantraditional' ) . ' </span>%',
				'separator'   => '<span class="screen-reader-text">, </span>',
			) );
		?>
	</div><!-- .entry-content -->

	<?php
		// Author bio.
		if ( is_single() && get_the_author_meta( 'description' ) ) :
			get_template_part( 'author-bio' );
		endif;
	?>

	<footer class="entry-footer">
		<?php cleantraditional_entry_meta(); ?>
		<?php edit_post_link( __( 'Bearbeiten', 'cleantraditional' ), '<span class="edit-link">', '</span>' ); ?>
	</footer><!-- .entry-footer -->

</article><!-- #post-## -->
