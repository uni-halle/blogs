<?php get_header(); ?>
<?php include (TEMPLATEPATH . '/topbox.php'); ?>		
<div id="boxer">
	<div id="content" class="fleft">
		<ul class="blogpost">
		<?php if (have_posts()) : while (have_posts()) : the_post(); ?>
			<li class="entry">
				<div class="post_head">
					<div class="pinfo"> <?php the_time('d'); ?> <br /><span><?php the_time('M'); ?></span> </div>
					<h1 class="maintitle fleft"><a class="free" href="<?php the_permalink() ?>" rel="bookmark"><?php the_title(); ?></a></h1>
				</div>		  
		  		<div class="clear"></div>
				<div class="entry_post">
					<?php the_content('Read more') ?> 
				</div>	
 				<div class="block_b">
 					<p><?php edit_post_link("Edit this post"); ?> </p>
 					<p>Posted <span>by</span> <?php the_author_posts_link(); ?> <span>in</span>  <?php the_category(', ') ?> </p> 
 		  			<?php	if (get_the_tags());?>
					<p><?php the_tags(); ?></p>
				</div>			
				<div id="single_middle">
					<p class="prevpost fleft"><?php previous_post_link('&larr; %link') ?> </p>
					<p class="nextpost fright"> <?php next_post_link(' %link &rarr;') ?></p>
					<div class="clear"></div>
				</div>
 			</li>
		<?php endwhile; ?>
		</ul>	
	<?php comments_template('', true); ?>
	<?php else: ?>
	<?php include (TEMPLATEPATH . '/notfound.php'); ?>
	<?php endif; ?>
	</div><!-- end of content -->	
	<?php get_sidebar(); ?>
	<div class="clear"></div>
</div><!-- end of boxer -->
<div class="pagination_bar">
	<div class="gototop"> <a href="#">Top</a></div>
	<div class="clear"></div>
</div>
<?php get_footer(); ?>