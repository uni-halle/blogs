<?php get_header(); ?>
<div id="container">
	<div id="main">
		<?php if (is_tag()){ ?>
			<div class="strong"><?php _e('Tag: ', 'pyrmont_v2'); ?><span class="keyword"><?php single_cat_title() ?></span></div>
		<?php } elseif (is_category()){ ?>
			<div class="strong"><?php _e('Category: ', 'pyrmont_v2'); ?><span class="keyword"><?php single_cat_title() ?></span></div>
		<?php } ?>

    	<?php if (have_posts()) : while (have_posts()) : the_post(); ?>
			<div class="post<?php if(function_exists (sticky_class)): sticky_class(); endif; ?>" id="post-<?php the_ID(); ?>">
				<div class="date">
					<?php the_time('Y') ?><br />
					<?php the_time('m.d') ?>
				</div>
				<div class="title">
					<h2><a href="<?php the_permalink() ?>" rel="bookmark" title="<?php _e('Permalink to: ', 'pyrmont_v2'); ?><?php the_title_attribute(); ?>"><?php the_title(); ?></a></h2>
					
					<div class="postmeta">
						<?php _e('Category', 'pyrmont_v2'); ?>:&nbsp;<span class="category"><?php the_category(', ') ?></span>&nbsp;/
						<?php
							$tag = get_the_tags();
							if(!$tag){
								echo __('Tags: no tag /', 'pyrmont_v2');
							}
							else{
						?>
						<?php _e('Tag', 'pyrmont_v2'); ?>:&nbsp;<?php the_tags('<span>',',&nbsp;','</span>'); ?>&nbsp;/
						<?php } ?>
						<span class="comments"><?php comments_popup_link(__('Add Comment', 'pyrmont_v2'), __('1 comment', 'pyrmont_v2'), __('% comments', 'pyrmont_v2')); ?></span>
						<?php edit_post_link(__('Edit', 'pyrmont_v2'), ' / ', ''); ?>
					</div><!-- end postmeta -->
				</div><!-- end title -->
				<div class="clear"></div>
				
				<?php if (!(is_tag()) && !(is_category())){ ?>
				<div class="entry">
					<?php the_content(__('</p><p>Read More >></p>', 'pyrmont_v2')); ?>
					<div class="clear"></div>
				</div><!-- end entry -->
				
				<?php } ?>
			</div><!-- end post -->
		<?php endwhile; ?>
			
    	<div class="navigation">
    		<div class="left"><?php next_posts_link(__('&laquo; Previous Entries', 'pyrmont_v2')); ?></div>
    		<div class="right"><?php previous_posts_link(__('Next Entries &raquo;', 'pyrmont_v2')); ?></div>
    		<div class="clear"></div>
    	</div><!-- end navigation -->
    	
	    <?php else : ?>
	    	<div class="post">
				<div class="title">
					<h2><?php _e('Sorry, nothing found!', 'pyrmont_v2'); ?></h2>
				</div>
				<div class="clear"></div>
				<div class="entry no_result">
					<p class="no_result"><?php _e('Please use the search function, or visit the archives page.', 'pyrmont_v2'); ?></p>
				</div>
			</div><!-- end post -->
		<?php endif; ?>
	</div><!-- end main -->
<?php get_sidebar(); ?>
<div class="clear"></div>
</div><!-- end container -->
<?php get_footer(); ?>