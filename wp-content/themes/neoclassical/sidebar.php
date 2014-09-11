<div id="right_bar" class="sidebar">
	<ul class="sidebar_list">
<?php 
		if (!function_exists('dynamic_sidebar') || !dynamic_sidebar(2)) : ?>
		<li class="widget">
			<h2>Search This Site</h2>
			<?php include (TEMPLATEPATH . '/searchform.php'); ?>
		</li>
		<li class="widget">
			<h2>Categories</h2>
			<ul>
				<?php wp_list_categories('title_li=0'); ?>
			</ul>
		</li>
<?php get_links_list('id'); ?>
<?php 
		endif; ?>
	</ul>
</div>