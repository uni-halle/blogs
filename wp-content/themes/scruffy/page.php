<?php get_header(); ?>

		<div id="content">
		
		<?php $count = 0; ?>
		
		<?php if(have_posts()): ?>
		<?php while(have_posts()) : the_post();?>
		
			<div class="post <?php if($count==0){?>first<?php } ?>" id="post-<?php the_ID(); ?>">
				
				<h2 class="title"><a href="<?php the_permalink(); ?>#content" title="Permalink to <?php the_title(); ?>"><?php the_title(); ?></a></h2>
				
				<div class="entry">
				
					<?php the_content(''); ?>
					
					<?php wp_link_pages('before=<div id="paginate">Pages: &after=</div>&next_or_number=number&pagelink=%'); ?>
					
				</div><!-- /entry -->
				
				<div class="postbottom">
				</div>
				
			</div><!-- /post -->
			
			<?php $count++; ?>
			
		<?php endwhile; ?>		
	
		<?php else: ?>
	
			<div class="post">
				<h2><?php _e('Not Found'); ?></h2>
			</div><!-- /post -->
	
		<?php endif; ?>
	
		</div><!-- /content -->
		
<?php get_sidebar() ?>

<?php get_footer(); ?>