<div id="sidebar" class="sidebar">
	<ul>
		<?php 	/* Widgetized sidebar, if you have the plugin installed. */
		if ( !function_exists('dynamic_sidebar') || !dynamic_sidebar('Sidebar') ) : ?>
		<li>
			<?php include (TEMPLATEPATH . '/searchform.php'); ?>
		</li>

		<!-- Author information is disabled per default. Uncomment and fill in your details if you want to use it.
		<li><h2>Author</h2>
		<p>A little something about you, the author. Nothing lengthy, just an overview.</p>
		</li>
		-->

		<?php if ( is_404() || is_category() || is_day() || is_month() ||
					is_year() || is_search() || is_paged() ) {
		?> <li>

		<?php /* If this is a 404 page */ if (is_404()) { ?>
		<?php /* If this is a category archive */ } elseif (is_category()) { ?>
		<p><?php printf(__('You are currently browsing the archives for the %s category.', 'elegant-grunge'), single_cat_title('', false)) ?></p>

		<?php /* If this is a yearly archive */ } elseif (is_day()) { ?>
		<p><?php printf(__('You are currently browsing the %1$s blog archives for the day %2$s', 'elegant-grunge'), 
			'<a href="'.get_bloginfo('url').'/">'.get_bloginfo('name').'</a>', 
		    get_the_time(__('l, F jS, Y', 'elegant-grunge'))) ?></p>

		<?php /* If this is a monthly archive */ } elseif (is_month()) { ?>
		<p><?php printf(__('You are currently browsing the %1$s blog archives for %2$s', 'elegant-grunge'), 
				'<a href="'.get_bloginfo('url').'/">'.get_bloginfo('name').'</a>', 
			    get_the_time(__('F, Y', 'elegant-grunge'))) ?>.</p>

		<?php /* If this is a yearly archive */ } elseif (is_year()) { ?>
		<p><?php printf(__('You are currently browsing the %1$s blog archives for the year %2$s', 'elegant-grunge'), 
				'<a href="'.get_bloginfo('url').'/">'.get_bloginfo('name').'</a>', 
			    get_the_time(__('Y', 'elegant-grunge'))) ?></p>

		<?php /* If this is a monthly archive */ } elseif (is_search()) { ?>
		<p><?php printf(__('You have searched the %1$s blog archives for <strong>%2$s</strong>. If you are unable to find anything in these search results, you can try one of these links.', 'elegant-grunge'),
			'<a href="'.get_bloginfo('url').'/">'.get_bloginfo('name').'</a>', get_search_query()) ?></p>

		<?php /* If this is a monthly archive */ } elseif (isset($_GET['paged']) && !empty($_GET['paged'])) { ?>
		<p>	<?php printf(__('You are currently browsing the %s blog archives', 'elegant-grunge'), 
					'<a href="'.get_bloginfo('url').'/">'.get_bloginfo('name').'</a>') ?></p>

		<?php } ?>

		</li> <?php }?>

		<?php wp_list_pages('title_li=<h2>'.__('Pages', 'elegant-grunge').'</h2>' ); ?>

		<li><h2><?php _e('Archives', 'elegant-grunge') ?></h2>
			<ul>
			<?php wp_get_archives('type=monthly'); ?>
			</ul>
		</li>

		<?php wp_list_categories('show_count=1&title_li=<h2>'.__('Categories', 'elegant-grunge').'</h2>'); ?>

		<?php /* If this is the frontpage */ if ( is_home() || is_page() ) { ?>
			<?php wp_list_bookmarks(); ?>

			<li><h2><?php _e('Meta', 'elegant-grunge') ?></h2>
			<ul>
				<?php wp_register(); ?>
				<li><?php wp_loginout(); ?></li>
				<li><a href="http://validator.w3.org/check/referer" title="This page validates as XHTML 1.0 Transitional">Valid <abbr title="eXtensible HyperText Markup Language">XHTML</abbr></a></li>
				<li><a href="http://gmpg.org/xfn/"><abbr title="XHTML Friends Network">XFN</abbr></a></li>
				<li><a href="http://wordpress.org/" title="Powered by WordPress, state-of-the-art semantic personal publishing platform.">WordPress</a></li>
				<?php wp_meta(); ?>
			</ul>
			</li>
		<?php } ?>

		<?php endif; ?>
	</ul>
</div>
<?php if ( defined('EG_BODY_CLASS') && EG_BODY_CLASS == 'double-right-sidebar' ) : ?>
<div id="sidebar2" class="sidebar">
	<ul>
		<?php if ( function_exists('dynamic_sidebar') ) dynamic_sidebar('Sidebar 2'); ?>
	</ul>
</div>
<?php endif; ?>