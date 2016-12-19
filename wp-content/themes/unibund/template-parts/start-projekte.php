<?php
/**
 * The template part for displaying content
 *
 * @package WordPress
 * @subpackage Twenty_Sixteen
 * @since Twenty Sixteen 1.0
 */
?>

<article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
	
	
	
	
	<div class="side1 top-project-<?php the_field('top_projekt'); ?>">
		<?php twentysixteen_post_thumbnail(); ?>
	</div>
	<div class="side2 top-project-<?php the_field('top_projekt'); ?>">
		<div class="side-text">
			<header class="entry-header">
				<?php if ( is_sticky() && is_home() && ! is_paged() ) : ?>
					<span class="sticky-post"><?php _e( 'Featured', 'twentysixteen' ); ?></span>
				<?php endif; ?>
		
				<?php the_title( sprintf( '<h2 class="entry-title"><a href="%s" rel="bookmark">', esc_url( get_permalink() ) ), '</a></h2>' ); ?>
			</header><!-- .entry-header -->
			<span class="project-teaser"><a href="<?php echo esc_url( get_permalink() ); ?>"><?php the_field('teaser_text'); ?></a></span>
			
			<div><a href="<?php echo esc_url( get_permalink() ); ?>"><span class="text-icons"><i class="material-icons">arrow_forward</i></span></a></div>
			
		</div>
	</div>
	
	

	
	
	

	

	
</article><!-- #post-## -->
