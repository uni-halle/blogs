<?php
/*
Template Name: Archives
*/
?>
<?php get_header();?>
<div id="content">
<div id="content-main">
<div class="post">
				<h2 class="post-title"><a href="<?php the_permalink() ?>" rel="bookmark" title="<?php _e('Permanent Link:','ml');?> <?php the_title(); ?>"><?php the_title(); ?></a></h2>
				<p class="post-info"><?php edit_post_link(); ?></p>
				<div class="entry">
				<h3><?php _e('by Categories'); ?></h3>
				<ul>
				<?php
				if (function_exists('wp_list_categories'))
				{
					wp_list_categories('show_count=1&hierarchical=1&title_li=');
				}
				else
				{
					wp_list_cats('optioncount=1');
				}
				?>
				</ul>
					<h3><?php _e('by Month','ml'); ?></h3>
					<ul><?php wp_get_archives('type=monthly'); ?></ul>
				  <?php if (function_exists('wp_tag_cloud')) {	?>
				  <h3>
					<?php _e('Tags','ml'); ?>
				  </h3>
				  <p>
					<?php wp_tag_cloud(); ?>
				  </p>
				  <?php } ?>
					<h3><?php _e('Last 50 Entries','ml');?></h3>
					<ul>
					<?php $posts = query_posts('showposts=50');?>
					<?php if ($posts) : foreach ($posts as $post) : the_post(); ?>
						<li><h4><em><?php the_time(__('d M Y','ml')); ?></em><a href="<?php the_permalink() ?>" rel="bookmark" title="<?php _e('Permanent Link:','ml');?> <?php the_title(); ?>"><?php the_title(); ?></a></h4></li>
					<?php endforeach; else: ?>
					<p><?php _e('Sorry, no posts matched your criteria.','ml'); ?></p>
					<?php endif; ?>
					</ul>
				</div>
			</div>
</div><!-- end id:content-main -->
<?php get_sidebar();?>
<?php get_footer();?>