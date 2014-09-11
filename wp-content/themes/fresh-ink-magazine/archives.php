<?php
/*
Template Name: Archives
*/
?>
<?php get_header(); ?>
	
		<div id="content"><div id="archivemarg">
			<?php get_search_form(); ?>
			<h2 class="largeheadline"><?php _e("Archives by Month:", "magazinetheme"); ?></h2>
			<ul>
				<?php wp_get_archives('type=monthly'); ?>
			</ul>
			<h2 class="largeheadline"><?php _e("Archives by Subject:", "magazinetheme"); ?></h2>
			<ul>
				<?php wp_list_categories(); ?>
			</ul>
		</div>
</div>
		<p>
		  <!-- end content div -->
	  </p>
			<?php get_sidebar(); ?>
        

	<p>
	  <!-- end main div -->
	  </p>
	<?php get_footer(); ?>
