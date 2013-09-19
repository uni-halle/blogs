<div id="sidebar">
	<?php 	if ( ! dynamic_sidebar( 'top-sidebar-widgets' ) ) : ?>
		<div class="section">
			<h3><?php _e('Search', 'jenny'); ?></h3>
			<?php get_search_form(); ?>
		</div>
		
		<div class="section widget_categories">
			<h3><?php _e('Categories', 'jenny'); ?></h3>
			<ul>
				<?php wp_list_categories('title_li=&hierarchical=0'); ?>
			</ul>
		</div>
	<?php endif; ?>
	
	<?php
	// A second sidebar for widgets, just because.
	if ( is_active_sidebar( 'secondary-sidebar-widgets' ) ) : ?>
	<?php dynamic_sidebar( 'secondary-sidebar-widgets' ); ?>
	<?php endif; ?>
</div><!--#sidebar-->

<hr />