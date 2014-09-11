<?php
/*
Template Name: Links
*/
?>
<?php get_header(); ?>
<div id="content">
	<div id="content-main">
			<div class="post">
				<h2 class="post-title"><a href="<?php the_permalink() ?>" rel="bookmark" title="<?php _e('Permanent Link:','ml');?> <?php the_title(); ?>"><?php the_title(); ?></a></h2>
				<p class="post-info"><?php the_time(__('M jS, Y','ml')) ?></p>
				<div class="entry">
					<ul>
						<?php wp_list_bookmarks();?>
					</ul>					
				</div>
			</div>
	</div>
<?php get_sidebar(); ?>
<?php get_footer(); ?>