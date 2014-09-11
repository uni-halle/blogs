<?php
/*
Template Name: Archives
*/
?>

<?php get_header(); ?>
		
	<div id="content_box">

		<div id="content" class="page">
		
			<h1>Browse the Archives...</h1>
			<div class="entry">
				<p><strong>Monthly archives:</strong><p>
				<ul>
					<?php wp_get_archives('type=monthly'); ?>
				</ul>
				<p><strong>Topical archives:</strong></p>
				<ul>
					<?php wp_list_categories('title_li=0'); ?>
				</ul>
			</div>
			
		</div>	
		
		<?php get_sidebar(); ?>
			
	</div>
		
<?php get_footer(); ?>