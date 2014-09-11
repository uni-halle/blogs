<?php
/*
Template Name: Archives
*/
?>

<?php get_header(); ?>
		
	<div id="content_box">
	
		<div id="left_box">
			
			<div id="content" class="archives">
					
				<h1>Browse the Archives</h1>
					
				<div id="content_inner">
					<div class="format_text">
						<h3 class="top">By Month:</h3>
						<ul>
							<?php wp_get_archives('type=monthly'); ?>
						</ul>
						<h3>By Category:</h3>
						<ul>
							<?php wp_list_categories('title_li=0'); ?>
						</ul>
					</div>
				</div>
			
			</div>

			<?php include (TEMPLATEPATH . '/left_bar.php'); ?>

		</div>

		<?php get_sidebar(); ?>
	
	</div>
		
<?php get_footer(); ?>