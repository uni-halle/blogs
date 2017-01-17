<?php
/**
 * The template part for displaying content
 *
 * @package WordPress
 * @subpackage Twenty_Sixteen
 * @since Twenty Sixteen 1.0
 */
?>

<article  id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
	
	
	<div class="news-box">
		<div class="news-image">
			<?php twentysixteen_post_thumbnail(); ?>
		</div>
		<div class="news-text">
			<div class="n-date"><?php the_time('d. F Y'); ?></div>
			<?php the_title( sprintf( '<h4><a href="%s" rel="bookmark">', esc_url( get_permalink() ) ), '</a></h4>' ); ?>
			<div class="n-teaser">
				<?php  
					$content = get_the_content();
					echo substr($content, 0, 120).'... ';
				?>
				
				<a href="<?php echo esc_url( get_permalink() ); ?>">Weiterlesen &rsaquo;</a>
			</div>
			
		</div>
		<div class="clearfix"></div>
	</div>
	

</article><!-- #post-## -->
