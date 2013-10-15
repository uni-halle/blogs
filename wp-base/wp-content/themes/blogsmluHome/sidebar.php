<div id="sidebar">
	
<?php if ( !function_exists('dynamic_sidebar')
        || !dynamic_sidebar('Sidebar') ) : ?>

	<div class="sidelist">
		<h2>Suche</h2>
		<?php get_search_form(); ?>
	</div>
	
	<div class="sidelist">
		<h2>Kategorien</h2>
		<ul>
			<?php wp_list_categories('title_li='); ?>
		</ul>
	</div>
	
	<div class="sidelist">
		<h2>Archiv</h2>
		<ul>
			<?php wp_get_archives('type=monthly'); ?>
		</ul>
	</div>
	
	<div class="sidelist">
		<h2>Blogroll</h2>
		<ul>
			<?php wp_list_bookmarks('category_name=Blogroll&categorize=0&title_li='); ?>
		</ul>
	</div>

<?php endif; ?>

</div><!-- END Sidebar -->
