<?php
/**
 * The template for displaying link post formats
 *
 * Used for both single and index/archive/search.
 *
 * @package WordPress
 * @subpackage Twenty_Fifteen
 * @since Twenty Fifteen 1.0
 */
?>

<article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
	<!-- #col3: Statische Spalte des Inhaltsbereiches -->
    <div class="col3">
      <div class="col3_content" class="clearfix" >
<?php
		// Post thumbnail.
		twentyfifteen_post_thumbnail();
		if ( is_single() ) { the_post_navigation();	}		
		
	?>
</div></div>

	<header class="entry-header">
		<?php
			if ( is_single() ) :
				the_title( sprintf( '<h1 class="entry-title">', esc_url( twentyfifteen_get_link_url() ) ), '</h1>' );
			else :
				the_title( sprintf( '<h2>', esc_url( twentyfifteen_get_link_url() ) ), '</h2>' );
			endif;
		?>
	</header>
	<!-- .entry-header -->

	<div class="entry-content">
		<?php
			/* translators: %s: Name of current post */
			the_content( sprintf(
				__( 'Continue reading %s', 'twentyfifteen' ),
				the_title( '<span class="screen-reader-text">', '</span>', false )
			) );

			wp_link_pages( array(
				'before'      => '<div class="page-links"><span class="page-links-title">' . __( 'Pages:', 'twentyfifteen' ) . '</span>',
				'after'       => '</div>',
				'link_before' => '<span>',
				'link_after'  => '</span>',
				'pagelink'    => '<span class="screen-reader-text">' . __( 'Page', 'twentyfifteen' ) . ' </span>%',
				'separator'   => '<span class="screen-reader-text">, </span>',
			) );
		?>
	</div>
	<!-- .entry-content -->

	<?php
		// Author bio.
		if ( is_single() && get_the_author_meta( 'description' ) ) :
			get_template_part( 'author-bio' );
		endif;
	?>

	<footer class="entry-footer">
		<?php edit_post_link( __( 'Edit', 'twentyfifteen' ), '<span class="edit-link">', '</span>' ); ?>
	</footer>
	<!-- .entry-footer -->
	
	

</article><!-- #post-## -->
