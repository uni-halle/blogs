<?php
/**
 * The template part for displaying content
 *
 * @package WordPress
 * @subpackage Twenty_Sixteen
 * @since Twenty Sixteen 1.0
 */
?>

<article class="events-row" id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
	<div class="events-box">
		<div class="events-date e-date">
			<?php the_field('zeit'); ?>
		</div>
		<div class="events-text">
			<?php the_title( sprintf( '<h4><a href="%s" rel="bookmark">', esc_url( get_permalink() ) ), '</a></h4>' ); ?>
			<div class="e-teaser">
				<?php  
					$content = get_the_content();
					echo substr($content, 0, 80).'... ';
				?>
				
				<a href="<?php echo esc_url( get_permalink() ); ?>">Weiterlesen &rsaquo;</a>
			</div>
		</div>
		<div class="clearfix"></div>
	</div>
</article><!-- #post-## -->
