<?php get_header(); ?>
<div id="multi_posts">
		<?php if (have_posts()) : ?>
		<?php while (have_posts()) : the_post(); ?>
		<div <?php post_class() ?> id="post-<?php the_ID(); ?>">
												<span class="date">
																	<span class="month"><?php the_time('M') ?></span>
																	<span class="day"><?php the_time('d') ?></span>
												</span>
												<h2><a href="<?php the_permalink() ?>" rel="bookmark" title="View <?php the_title_attribute(); ?>">
												<?php the_title(); ?>
												</a></h2>
												<div class="entry">
													<?php the_content('( Read more )'); ?>
												</div>
												<p class="edit_comments">
														<span class="edit"><?php edit_post_link('+ edit +', '', '  '); ?></span>
														<span class="commentct"><?php comments_popup_link('0', '1', '%'); ?><br /><span class="c_title">comments</span></span>		
												</p>										
												<p class="postmetadata">
													<span class="category">Posted in <?php the_category(', ') ?></span> 	<span class="tags"><?php the_tags(' ',' '); ?></span>
												</p>	
</div><!-- END POST -->
<?php endwhile; endif; ?>

<div class="navigation">
		<div class="alignleft"><?php next_posts_link('&laquo; Older Entries') ?></div>
		<div class="alignright"><?php previous_posts_link('Newer Entries &raquo;') ?></div>
</div>

</div><!-- END MULTIPOSTS -->
<?php get_footer(); ?>
