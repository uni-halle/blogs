<ul>
	<?php 	/* Widgetized sidebar, if you have the plugin installed. */
			if ( !function_exists('dynamic_sidebar') || !dynamic_sidebar('extra-content-three') ) : ?>
	<li>
	<ul>
		<li><h2>Links</h2></li>
		
	<?php wp_list_bookmarks('title_li=&categorize=0'); ?></ul>
	</li>
<?php endif; ?>

</ul>