<div id="sidebar" class="yui-b secondary">
	<ul>
		<?php 	/* Widgetized sidebar, if you have the plugin installed. */
				if ( !function_exists('dynamic_sidebar') || !dynamic_sidebar() ) : ?>

		<li class="widget">
			<div class="widget-container">
				<div class="widget-title">Archives</div>
				<ul>
				<?php wp_get_archives('type=monthly'); ?>
				</ul>
			</div>
		</li>

		<li class="widget">
			<ul class="widget-container">
				<?php wp_list_categories('show_count=1&title_li=<div class="widget-title">Categories</div>'); ?>
			</ul>
		</li>
		
		<?php /* If this is the frontpage */ if ( is_home() || is_page() ) { ?>

			<li class="widget">
				<div class="widget-container">
					<div class="widget-title">Meta</div>
					<ul>
						<?php wp_register(); ?>
						<li><?php wp_loginout(); ?></li>
						<li><a href="http://validator.w3.org/check/referer" title="This page validates as XHTML 1.0 Transitional">Valid <abbr title="eXtensible HyperText Markup Language">XHTML</abbr></a></li>
						<li><a href="http://gmpg.org/xfn/"><abbr title="XHTML Friends Network">XFN</abbr></a></li>
						<li><a href="http://wordpress.org/" title="Powered by WordPress, state-of-the-art semantic personal publishing platform.">WordPress</a></li>
						<?php wp_meta(); ?>
					</ul>
				</div>
			</li>
		<?php } ?>

		<?php endif; ?>
	</ul>
</div>