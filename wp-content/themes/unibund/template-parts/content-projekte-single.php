<?php
/**
 * The template part for displaying single posts
 *
 * @package WordPress
 * @subpackage Twenty_Sixteen
 * @since Twenty Sixteen 1.0
 */
?>


<section class="single-page">
	
	
	<div class="row single-table">
		<div class="single-table-row">
			<div class="sp-image"><?php twentysixteen_post_thumbnail(); ?></div>
			<div class="sp-headline">
				
					<?php the_title( '<h1 class="entry-title">', '</h1>' ); ?> 
				
			</div>
		</div>
	</div>


	<div class="single-content">
		
		<?php the_field('teaser_text'); ?>
		<?php twentysixteen_excerpt(); ?>
		
		
			<?php
				the_content();
	
				wp_link_pages( array(
					'before'      => '<div class="page-links"><span class="page-links-title">' . __( 'Pages:', 'twentysixteen' ) . '</span>',
					'after'       => '</div>',
					'link_before' => '<span>',
					'link_after'  => '</span>',
					'pagelink'    => '<span class="screen-reader-text">' . __( 'Page', 'twentysixteen' ) . ' </span>%',
					'separator'   => '<span class="screen-reader-text">, </span>',
				) );
	
				if ( '' !== get_the_author_meta( 'description' ) ) {
					get_template_part( 'template-parts/biography' );
				}
			?>
		<div class="content-link"><a href="javascript:history.back();"><span class="text-icons"><i class="material-icons">arrow_backward</i></span><span class="link-text">zurück</span></a></div>


	</div>
	
	
	
	<article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
	
	
	
</article><!-- #post-## -->


</section>


