<?php get_header();?>
<div id="main-container">
		<ul class="posts">		
			<?php if(have_posts()):?>
			<?php while(have_posts()):the_post();?>
				<?php if (is_paged()) : ?>
				<?php $postclass = ('post'); ?>
				<?php else : ?>
				<?php $postclass = ($post == $posts[0]) ? 'featured-post' : 'post'; ?>
				<?php endif; ?>
			  
					<?php if ($postclass == 'featured-post'){ ?>
							<div class="feature-post">								
								<a href="<?php the_permalink() ?>" title="<?php the_title() ?>">
									<?php if ( get_post_meta($post->ID, 'image', true) ) { ?> 
									<img src="<?php bloginfo('template_directory'); ?>/scripts/timthumb.php?src=<?php echo get_post_meta($post->ID, 'image', $single = true); ?>&h=274&w=304&zc=1&q=100" class="feature-image" />
									<?php } else { ?> 
									<img src="<?php bloginfo('template_directory'); ?>/images/missing-image.jpg" class="feature-image" />
									<?php } ?>
								</a>
								<small>Feature Story</small>
								<div class="post-title-big"><a href="<?php the_permalink();?>" title="<?php the_title()?>"><?php the_title()?></a></div>
								<?php the_excerpt();?>
								<a href="<?php the_permalink();?>" id="continue-btn" title="Continue Reading">Continue</a>
							</div><!--feature-post-->
					<?php } else { ?>
						<li>
							<div class="<?php echo $postclass;?>">							
							<div class="post-title"><span class="comments-bubble"><?php comments_number('0', '1', '%');?></span><a href="<?php the_permalink() ?>" title="<?php the_title();?>"><?php the_title();?></a><!--comments-bubble--></div><!--post-title-->
							<?php if ( get_post_meta($post->ID, 'image', true) ) { ?> 
							<a href="<?php the_permalink()?>" title="<?php the_title()?>"><img src="<?php bloginfo('template_directory'); ?>/scripts/timthumb.php?src=<?php echo get_post_meta($post->ID, 'image', $single = true); ?>&h=164&w=320&zc=1&q=100" class="thumb-image" /></a>
							<?php } ?>
							<div class="post-excerpt">
							<?php the_excerpt();?>
							</div><!--post-excerpt-->							
							</div><!--post--> 
						</li>
					<?php }	?>
				
				<?php endwhile;?>
		</ul>			
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