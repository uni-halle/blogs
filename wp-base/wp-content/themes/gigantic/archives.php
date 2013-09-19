<?php
/*
Template Name: Archives Page
*/
?>

<?php get_header() ?>

		<div id="content">

			<div id="categories">
	
				<h3 class="cat_header">Subjects</h3>

				<ul class="archive_list">

					<?php wp_list_categories('title_li=&show_count=0&hierarchical=1') ?> 

				</ul>
				
				<h3 class="cat_header">By date</h3>

				<ul class="archive_list">

					<?php wp_get_archives(); ?>

				</ul>

			</div>
			
		</div> <!-- end content -->
		
	</div> <!-- end main -->

	<?php get_footer(); ?>
