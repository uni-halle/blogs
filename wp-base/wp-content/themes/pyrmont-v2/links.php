<?php
/*
Template Name: Links
*/
?>

<?php get_header(); ?>
<div id="container">
	<div id="main">
    	<div class="post">
			<div class="title">
				<h2><?php _e('Links', 'pyrmont_v2'); ?></h2>
			</div><!-- end title -->
			<div class="clear"></div>
	
			<div class="linkpage">
				<ul>
					<?php wp_list_bookmarks('categorize=1&orderby=rand&before=<li>&after=</li>&show_images=0&show_discription=0&title_before=<h3>&title_after=</h3>'); ?>
				</ul>
				<div class="clear"></div>
			</div><!-- end entry -->
		</div><!-- end post -->
	</div><!-- end main --> 
<?php get_sidebar(); ?>
<div class="clear"></div>
</div><!-- end container -->
<?php get_footer(); ?>