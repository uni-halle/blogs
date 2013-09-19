<?php get_header(); ?>

		<div id="content">
		
		<?php $count = 0; ?>
		
		<?php if(have_posts()): ?>
		<?php while(have_posts()) : the_post();?>
		
			<div class="post first" id="post-<?php the_ID(); ?>">
				
				<h2 class="title"><a href="<?php the_permalink(); ?>#content" title="Permalink to <?php the_title(); ?>"><?php the_title(); ?></a></h2>
				
				<p class="postmetadata">
					<span class="date"><?php the_time('d F Y') ;?></span>
					<span class="author"><?php _e('By'); ?> <?php  the_author_posts_link(); ?></span>
					<span class="comments"><?php comments_number('0', '1', '%'); ?></span>
					<span class="category"><?php _e('In'); ?> <?php the_category(', ') ?></span>
					<?php edit_post_link('Edit', '<span class="edit">', '</span>'); ?>
				</p>
				
				<div class="entry">
				
					<?php the_content(''); ?>
					
					<?php wp_link_pages('before=<div id="paginate">Pages: &after=</div>&next_or_number=number&pagelink=%'); ?>
		
					<p class="trackback"><a href="<?php trackback_url(); ?>" title="Trackback URL for <?php the_title();?>">Trackback URL</a></p>
					
					<p class="tags"><?php the_tags('', ', ', '<br />'); ?></p>
					
				</div><!-- /entry -->
				
				<div class="postbottom">
				</div>
				
			</div><!-- /post -->
			
			<div id="comments">
				<?php comments_template(); ?>
			</div><!--/comments-->	
			
			<?php $count++; ?>
			
		<?php endwhile; ?>
			
			<div id="navigation">
				<p class="prev floatl"><?php previous_post_link('&laquo; %link'); ?></p>
				<p class="next floatr"><?php next_post_link('%link &raquo;'); ?></p>
			</div><!-- /navigation -->
	
		<?php else: ?>
	
			<div class="post">
				<h2><?php _e('Not Found'); ?></h2>
			</div><!-- /post -->
	
		<?php endif; ?>
	
		</div><!-- /content -->
		
<?php get_sidebar() ?>

<?php get_footer(); ?>