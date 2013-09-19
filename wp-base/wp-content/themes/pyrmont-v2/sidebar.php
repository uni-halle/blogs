<div id="sidebar">
	
	<ul>
		<?php if ( !function_exists('dynamic_sidebar') || !dynamic_sidebar() ) : ?>
		
		<li>
			<h2><?php _e('Recent Posts', 'pyrmont_v2'); ?></h2>
			<ul class="latest_post">
				<?php wp_get_archives('type=postbypost&limit=8'); ?>
			</ul>
		</li>
		
		<li>
			<h2><?php _e('Recent Comments', 'pyrmont_v2'); ?></h2>
			<ul class="recentcomment">
				<?php rc(); ?>
			</ul>
		</li>
		
		<li>
			<h2><?php _e('Meta', 'pyrmont_v2'); ?></h2>
			<ul>
				<?php wp_register(); ?>
				<li><?php wp_loginout(); ?></li>
			</ul>
		</li>
		
		<?php endif; ?>
	</ul><!-- end ul -->
</div><!-- end sidebar -->