<?php get_header(); ?>
<div id="multi_posts">
		<?php if (have_posts()) : ?>
		<?php $post = $posts[0]; // Hack. Set $post so that the_date() works. ?>
 	  
 	  <?php /* If this is a category archive */ if (is_category()) { ?>
		<h2 class="pagetitle">Category: <?php single_cat_title(); ?></h2>
 	  
 	  <?php /* If this is a tag archive */ } elseif( is_tag() ) { ?>
		<h2 class="pagetitle">Tagged: <?php single_tag_title(); ?></h2>
 	  
 	  <?php /* If this is a daily archive */ } elseif (is_day()) { ?>
		<h2 class="pagetitle">Archive for <?php the_time('F jS, Y'); ?></h2>
 	  
 	  <?php /* If this is a monthly archive */ } elseif (is_month()) { ?>
		<h2 class="pagetitle">Archive for <?php the_time('F, Y'); ?></h2>
 	  
 	  <?php /* If this is a yearly archive */ } elseif (is_year()) { ?>
		<h2 class="pagetitle">Archive for <?php the_time('Y'); ?></h2>
	  
	  <?php /* If this is an author archive */ } elseif (is_author()) { ?>
		<h2 class="pagetitle">Author Archive</h2>
 	  
 	  <?php /* If this is a paged archive */ } elseif (isset($_GET['paged']) && !empty($_GET['paged'])) { ?>
		<h2 class="pagetitle">Blog Archives</h2>
 	 
 	  <?php } ?>
		
		
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
