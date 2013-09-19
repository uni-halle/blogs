<div id="primary" class="sidebar">

	<?php if ( !function_exists('dynamic_sidebar') || !dynamic_sidebar(1) ) : // begin primary sidebar widgets ?>

	<div class="widget">
	<?php get_calendar(); ?>
	</div>
	
	<div class="widget">
	<?php wp_list_pages('title_li=<h3>'.__('Pages', 'win7blog').'</h3>' ); ?>
	</div>
	
	<div class="widget">
	<?php wp_list_categories('show_count=1&title_li=<h3>'.__('Categories', 'win7blog').'</h3>'); ?>
	</div>

	<li class="widget">
		<h3><?php _e('Archives', 'win7blog') ?></h3>
		<ul>
			<?php wp_get_archives('type=monthly'); ?>
		</ul>
	</li>

	<li class="widget">
		<h3><?php _e('Meta', 'win7blog') ?></h3>
		<ul>
			<?php wp_register(); ?>
			<li><?php wp_loginout(); ?></li>
		</ul>
	</li>
    <?php endif; // end primary sidebar widgets  ?>

</div><!-- #primary .sidebar -->

<div id="secondary" class="sidebar">
	<?php if ( !function_exists('dynamic_sidebar') || !dynamic_sidebar(2) ) : // begin secondary sidebar widgets ?>
	<?php endif; // end secondary sidebar widgets  ?>
</div><!-- #secondary .sidebar -->
