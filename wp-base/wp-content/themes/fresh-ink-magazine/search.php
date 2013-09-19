<?php get_header(); ?>

<div id="content">

<?php if (have_posts()) : ?>

	<div><h2 align="center" class="largeheadline"><?php _e("Search Results", "magazinetheme"); ?></h2></div>

<?php while (have_posts()) : the_post(); ?>

	<div>
<h2><a href="<?php the_permalink() ?>" rel="bookmark" title="<?php printf(__('Permanent Link to %s', 'magazinetheme'), the_title_attribute('echo=0')); ?>"><?php the_title(); ?></a>
		</h2>

		<div class="postmetadata">
			<?php _e("By ", "magazinetheme"); ?><?php the_author(); ?> 
			 <?php _e("Posted in ", "magazinetheme"); ?>
				<?php the_category(', ') ?>
				 <strong>|</strong> 
				<?php comments_popup_link('No Comments &#187;', '1 Comment &#187;', '% Comments &#187;'); ?>
		</div>

		<div class="entry">
			<?php the_excerpt(); ?>
			<span><a href="<?php the_permalink() ?>" rel="bookmark" title="<?php printf(__('Permanent Link to %s', 'magazinetheme'), the_title_attribute('echo=0')); ?>"><?php _e('Read the rest of this entry &raquo;', 'magazinetheme'); ?></a></span>
		</div>

	
    
    
 

</div><!-- end of post -->

<?php endwhile; ?><br /><br />

<div class="navigationtest">
			 
<?php get_pagination() ?>  

		</div>

<?php else : ?>
				<div id="post-0" class="post no-results not-found">
					<h2 class="entry-title"><?php _e("No Search Results Found", "magazinetheme"); ?></h2>
					<div class="entry-content">
						<p><?php _e("Sorry, but nothing matched your search criteria. Please try again with different keywords.", "magazinetheme"); ?></p>
						
					</div><!-- .entry-content -->
				</div><!-- #post-0 -->
<?php endif; ?>


</div>

<?php get_sidebar(); ?>

<?php get_footer(); ?>
