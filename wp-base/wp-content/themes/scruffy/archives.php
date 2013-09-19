<?php 
/*
Template name: Archives
*/
?>

<?php get_header(); ?>

<div id="content">

	<div class="post first">

		<h2 class="title"><?php bloginfo('name') ?> Archives</h2>
		
		<h3>By Category</h3>
		
		<ul>
			<?php wp_list_categories('title_li=&show_count=1&hide_empty=0'); ?>
		</ul>
		

		<h3>By Month</h3>
		
		<ul>
			<?php wp_get_archives('type=monthly&limit=&format=html&before=&after=&show_post_count=1'); ?>
		</ul>

		
		<h3>By Year</h3>
		
		<ul>
			<?php wp_get_archives('type=yearly&limit=&format=html&before=&after=&show_post_count=1'); ?>
		</ul>
		
		
		<?php if(function_exists('wp_tag_cloud')) { ?>
			<h3>By Tag</h3>
			
			<?php wp_tag_cloud(''); ?>
			
		<?php } ?>
		
		
		<h3>By Author</h3>
		
		<ul>
			<?php wp_list_authors('exclude_admin=0&optioncount=1&feed='); ?>
		</ul>
		
	</div><!-- /post -->

</div><!-- /content -->

<?php get_sidebar(); ?>

<?php get_footer(); ?>