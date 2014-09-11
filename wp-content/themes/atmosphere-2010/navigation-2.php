<?php $shortname = 'drcms'; ?>
<?php if( !get_option($shortname. '_hide_top_menu') ) : ?>
			<div id="pagemenu" role="navigation">
				<?php wp_nav_menu( array( 'container_class' => 'page-header', 'menu_class' => 'page-menu', 'theme_location' => 'secondary', 'depth' => 1, 'fallback_cb' => '' ) ); ?>
			</div>
<?php endif; ?>