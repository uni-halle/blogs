<?php get_header(); ?>

	<div id="content" class="narrow">

		<h2 class="page-title"><?php _e('Error 404 &mdash; Not Found', 'jenny'); ?></h2>
		
		<p><?php _e('You are trying to reach a page that does not exist here. Either it has been moved or you typed a wrong address. Try searching:', 'jenny'); ?></p>
		<?php get_search_form(); ?>
		
		<script type="text/javascript">
			// focus on search field after it has loaded
			document.getElementById('s') && document.getElementById('s').focus();
		</script>
	</div>
	
	<hr />
	
<?php get_sidebar(); ?>
<?php get_footer(); ?>
