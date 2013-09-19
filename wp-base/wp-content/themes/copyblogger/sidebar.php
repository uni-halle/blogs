<div id="sidebar">
	<p id="rss"><a href="<?php bloginfo('rss2_url'); ?>" title="Subscribe to this site's feed"></a></p>
	<ul class="sidebar_list">
		<li class="widget">
			<h2>Search</h2>
			<?php include (TEMPLATEPATH . '/searchform.php'); ?>
		</li>
		<?php if (!function_exists('dynamic_sidebar') || !dynamic_sidebar()) : ?>
		<li class="widget">
			<h2>Latest Blog Entries</h2>
			<ul>
				<?php query_posts('showposts=10'); ?>
				<?php if (have_posts()) : while (have_posts()) : the_post(); ?>
				<li><a href="<?php the_permalink() ?>"><?php the_title() ?></a></li>
				<?php endwhile; endif; ?>
			</ul>
		</li>
		<li class="widget">
			<h2>Categories</h2>
			<ul>
				<?php wp_list_categories('title_li=0'); ?>
			</ul>
		</li>
		<?php get_links_list('id'); ?>
		<?php endif; ?>
	</ul>
</div>