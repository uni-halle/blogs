<?php
/**
 * The template part for displaying status post format.
 *
 * @package bueno
 */
?>
<!--BEGIN .hentry -->
<article id="post-<?php the_ID(); ?>" <?php post_class('post__holder'); ?>>				
	
	<?php get_template_part('templates/post-meta'); ?>		

	<!-- Post Content -->
	<div class="post_content <?php if( is_singular() && is_sticky() ) echo esc_attr( $stickyclass ); ?>">
		<?php if ( is_sticky() ) echo "<span class='featured_badge'><i class='icon-pushpin'></i><strong>".__( 'Featured', 'bueno' )."</strong></span>"; ?>
		<?php the_content( __( 'Continue reading', 'bueno' ) ); ?>
		<?php wp_link_pages( array( 'before' => '<div class="page-links">' . __( 'Pages:', 'bueno' ), 'after' => '</div>' ) ); ?>
		
	</div>
	<!-- //Post Content -->
	<?php if( ( has_tag() ) && ( is_singular() ) ) { ?>
		<footer class="post-footer">
			<i class="icon-tags"></i> <?php the_tags('Tags: ', ' ', ''); ?>
		</footer>
	<?php } ?>        

</article><!--END .hentry-->

<?php if ( is_single() && get_the_author_meta( 'description' ) ) {
	get_template_part( 'templates/post-author-bio' );
} ?>