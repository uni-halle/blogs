<?php get_header();?>
<div id="main-container">
			<?php if(have_posts()):?>
			<?php while(have_posts()):the_post();?>
			<div class="post-in-single">
				<div class="post-title-big"><?php the_title();?></div><!--post-title-->				
				<div class="post-content">
				<?php the_content();?>
				</div><!--post-excerpt-->							
			</div><!--post-in-single--> 			
			<?php endwhile;?>
			<?php endif;?>	
		
		
		
		
		
		
		
		<div class="page-navigation">		
		<?php if(function_exists('wp_pagenavi')) {?>
		<?php wp_pagenavi(); ?>
		<?php } else { ?>
		<?php posts_nav_link();?>
		<?php } ?>
		</div><!--page-navigation-->		
</div><!--main-container-->
<?php get_footer();?>