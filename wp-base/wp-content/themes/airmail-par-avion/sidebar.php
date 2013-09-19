<?php if ( !function_exists('dynamic_sidebar') || !dynamic_sidebar() ) : ?>		
		<div id="primary" class="widget-area">
		<img src="<?php bloginfo('template_url')?>/images/logo.png">
		<div class="spacer"></div>
			<ul class="xoxo">
				<?php dynamic_sidebar('primary_widget_area'); ?>
			</ul>
		</div><!-- #primary .widget-area -->
<?php endif; ?>		
		
<?php if ( !function_exists('dynamic_sidebar') || !dynamic_sidebar() ) : ?>
		<div id="secondary" class="widget-area">
			<ul class="xoxo">
				<?php dynamic_sidebar('secondary_widget_area'); ?>
			</ul>
		</div><!-- #secondary .widget-area -->
<?php endif; ?>		
