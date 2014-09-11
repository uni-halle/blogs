<?php
/*
Template Name: Links
*/
?>
<?php get_header(); ?>
	
		<div class="marg" id="content">
			<div> <h2 class="largeheadline"><?php _e("Links: ", "magazinetheme"); ?></h2></div>
			<ul>
				<?php wp_list_bookmarks(); ?>
			</ul>
		</div>
		<!-- end content div-->
		<?php get_sidebar(); ?>
	
	<!-- end main div-->
<?php get_footer(); ?>
