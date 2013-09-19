	<div id="sidebar">

<?php if ( !function_exists('dynamic_sidebar') || !dynamic_sidebar() ) : ?>

		<div class="widget">
			<h4>Pages</h4>
			<ul>
			<?php wp_list_pages('title_li=' ); ?>
			</ul>
		</div>

		<div class="widget">
			<h4>Category</h4>
			<ul>
			<?php wp_list_categories('show_count=1&title_li='); ?>
			</ul>
		</div>

		<div class="widget">
			<h4>Archives</h4>
			<ul>
			<?php wp_get_archives('type=monthly'); ?>
			</ul>
		</div>

		<div class="widget">
			<h4>Recent Comments</h4>
			<?php include (TEMPLATEPATH . '/recent_comments.php'); /* recent comments plugin by: www.g-loaded.eu */?>
		</div>

<?php endif; ?>

	</div>
	<!--/sidebar -->
