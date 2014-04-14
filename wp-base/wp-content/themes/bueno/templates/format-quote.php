<?php
/**
 * The template part for displaying quote post format.
 *
 * @package bueno
 */
?>	
<article id="post-<?php the_ID(); ?>" <?php post_class('post__holder'); ?>>
		<header class="post-header">
			<?php if(is_singular()) : ?>
			
			<h1 class="post-title"><?php the_title(); ?></h1>
			
			<?php endif; ?>

			<?php get_template_part('templates/post-meta'); ?>
			
		</header>
		
		<!-- Post Content -->
		<div class="post_content">
				<?php the_content( __( 'Continue reading', 'bueno' ) ); ?>
				<?php wp_link_pages( array( 'before' => '<div class="page-links">' . __( 'Pages:', 'bueno' ), 'after' => '</div>' ) ); ?>
		</div>
		<!-- //Post Content -->
		<?php if( ( has_tag() ) && ( is_singular() ) ) { ?>
			<footer class="post-footer">
				<i class="icon-tags"></i> <?php the_tags('Tags: ', ' ', ''); ?>
			</footer>
		<?php } ?>
		
<!--//.post-holder-->  
</article>

<?php if ( is_single() && get_the_author_meta( 'description' ) ) {
	get_template_part( 'templates/post-author-bio' );
} ?>