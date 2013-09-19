<?php get_header();?>
<div id="main-container">
			<?php if(have_posts()):?>
			<?php while(have_posts()):the_post();?>
			<div class="post-in-single">
				<div class="post-title-big"><?php the_title();?></div><!--post-title-->
				<?php if ( get_post_meta($post->ID, 'image', true) ) { ?> 			
				<div id="single-image">					
				<img src="<?php bloginfo('template_directory'); ?>/scripts/timthumb.php?src=<?php echo get_post_meta($post->ID, 'image', $single = true); ?>&h=284&w=424&zc=1&q=100" />	
				</div><!--single-image-->
				<?php } ?>						
				<div class="post-content">
				<?php the_content();?>				
				</div><!--post-content-->							
			</div><!--post-in-single--> 				
			<?php endwhile;?>	
			<?php endif;?>
			
			
			
			<?php comments_template();?>
			
		
</div><!--main-container-->
<?php get_footer();?>