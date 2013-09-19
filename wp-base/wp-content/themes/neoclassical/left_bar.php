<div id="left_bar" class="sidebar">
	<ul class="sidebar_list">
		<li class="widget">
			<h2>Navigation</h2>
			<ul>
<?php include (TEMPLATEPATH . '/nav_menu.php'); ?>

			</ul>
		</li>
<?php 
		if (!function_exists('dynamic_sidebar') || !dynamic_sidebar(1)) : ?>
		<li class="widget">
			<h2>Latest Blog Entries</h2>
			<ul>
<?php 
			query_posts('showposts=10');
			if (have_posts()) : 
				while (have_posts()) : the_post(); ?>
				<li><a href="<?php the_permalink() ?>"><?php the_title() ?></a></li>
<?php
				endwhile;
			endif; ?>
			</ul>
		</li>
<?php 
		endif; ?>
	</ul>
</div>