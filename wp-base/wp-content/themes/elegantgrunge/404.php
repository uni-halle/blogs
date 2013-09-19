<?php
header("HTTP/1.1 404 Not Found");
header("Status: 404 Not Found");
?>
<?php get_header(); ?>
<div id="content-container">

	<div id="content">
	<div id="body">
			<h3><?php _e('Not found', 'elegant-grunge') ?></h3>
			<p><?php _e('Sorry, no posts could be found here.  Try searching below:', 'elegant-grunge'); ?></p>
			<?php include (TEMPLATEPATH . "/searchform.php"); ?>

               <?php if (function_exists('smart404_loop') && smart404_loop()) : ?>
                   <p><?php _e('Or, try one of these posts:', 'elegant-grunge') ?></p>
                   <?php while (have_posts()) : the_post(); ?>
					<h4><a href="<?php the_permalink() ?>" rel="bookmark" title="<?php printf(__('Permanent Link to %s', 'elegant-grunge'), get_the_title()); ?>"><?php the_title(); ?></a></h4>
					<small><?php the_excerpt(); ?></small>
		        <?php endwhile; ?>
              	<?php endif; ?>
		
		<div class="clear"></div>
	</div>
	
	  <?php get_sidebar(); ?>
	  
	  <div class="clear"></div>
</div><!-- end content -->
</div>
	
<?php get_footer(); ?>
