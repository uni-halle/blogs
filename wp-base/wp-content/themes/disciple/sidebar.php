<!-- right sidebar -->

<div id="sidebar">

<?php include (TEMPLATEPATH . '/searchform.php'); ?>

	<ul id="widgets">

<?php
$children = wp_list_pages('title_li=&child_of='.$post->ID.'&echo=0');
if ($children) { ?>
	<li class="widget">	
		<ul>
			<?php echo $children; ?>
		</ul>
	</li>
<?php } ?>

	<?php if ( function_exists('dynamic_sidebar') && dynamic_sidebar() ) : else : ?>

	<li class="widget">	
	<h2> <?php _e('About this blog','disciplede') ;?></h2>
		<p><?php _e('This is a sidebar example to show how it could look. Customize your sidebar by adding Widgets in WP Admin > Design > Widgets or edit the sidebar files on your own.','disciplede') ;?></p>
	</li>

	<li class="widget widget_categories">
	<h2><?php _e('Categories','disciplede') ;?></h2>
		<ul>
			<?php wp_list_categories('sort_column=name&title_li='); ?>
		</ul>
	</li>
		
	<li class="widget widget_archive">
	<h2><?php _e('Archives','disciplede') ;?></h2>
		<ul>
			<?php wp_get_archives('type=monthly'); ?>
		</ul>
	</li>
	
	<li class="widget widget_links">
	<h2><?php _e('Blogroll','disciplede') ;?></h2>
		<ul>
			<?php get_links(-1, '<li>', '</li>', ' - '); ?>
		</ul>
	</li>

	<?php endif; ?>
	</ul>
			
</div>

<!-- /right sidebar -->