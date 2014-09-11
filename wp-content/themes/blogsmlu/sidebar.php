<div id="sidebar">

<?php if ( !function_exists('dynamic_sidebar')
        || !dynamic_sidebar('Sidebar') ) : ?>

	<div class="sidelist">
		<h2><?php _e('Search', 'blogsmlu'); ?></h2>
			<?php get_search_form(); ?>
	</div>
	
	<div class="sidelist">
		<h2><?php _e('Categories', 'blogsmlu'); ?></h2>
		<ul>
			<?php wp_list_categories('title_li='); ?>
		</ul>
	</div>
	
	<div class="sidelist">
		<h2><?php _e('Archives', 'blogsmlu'); ?></h2>
		<ul>
			<?php wp_get_archives('type=monthly'); ?>
		</ul>
	</div>
	
	<div class="sidelist">
		<h2><?php _e('Blogroll', 'blogsmlu'); ?></h2>
		<ul>
			<?php wp_list_bookmarks('category_name=Blogroll&categorize=0&title_li='); ?>
		</ul>
	</div>

	<?php endif; ?>

</div><!-- END Sidebar -->