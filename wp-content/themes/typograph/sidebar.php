<div id="sidebar">
<h1>main control center:</h1>
<!-- TAB BOX -->
<?php include ('tabbedBox.php'); ?>
<!-- END TAB BOX -->
	
	<div id="barLeft">
		<ul>
<?php if (!function_exists('dynamic_sidebar') || !dynamic_sidebar(1) ) : // begin primary sidebar widgets ?>

			<li id="pages">
				<h3><?php _e('Pages') ?></h3>
				<ul>
<?php wp_list_pages('title_li=&sort_column=post_title' ) ?>
				</ul>
			</li>

			<li id="categories">
				<h3><?php _e('Categories'); ?></h3>
				<ul>
<?php wp_list_categories('title_li=&show_count=0&hierarchical=1') ?> 

				</ul>
			</li>

			<li id="archives">
				<h3><?php _e('Archives') ?></h3>
				<ul>
<?php wp_get_archives('type=monthly') ?>

				</ul>
			</li>
<?php endif; // end primary sidebar widgets  ?>
		</ul>
		<div class="clear"></div>
	</div><!-- END #barLeft -->

	<div id="barRight">
		<ul>
<?php if (!function_exists('dynamic_sidebar') || !dynamic_sidebar(2) ) : // begin  secondary sidebar widgets ?>
			
<!-- Search Widget is disabled by default. If you want to activate it, simply remove this comment line and the one below the widget.
			
			<li id="search">
				<h3><label for="s"><?php _e('Search') ?></label></h3>
				<form id="searchform" method="get" action="<?php bloginfo('home') ?>">
					<div>
						<input id="s" name="s" type="text" value="<?php echo wp_specialchars(stripslashes($_GET['s']), true) ?>" size="10" tabindex="10" />
						<input id="searchsubmit" name="searchsubmit" type="submit" value="<?php _e('Find') ?>" tabindex="11" />
					</div>
				</form>
			</li>

To activate the Search Widget in default mode, remove this comment line -->

<?php wp_list_bookmarks('title_before=<h3>&title_after=</h3>&show_images=1') ?>

			<li id="rss-links">
				<h3><?php _e('RSS Feeds') ?></h3>
				<ul>
					<li><a href="<?php bloginfo('rss2_url') ?>" title="<?php echo wp_specialchars(get_bloginfo('name'), 1) ?> <?php _e('Posts RSS feed'); ?>" rel="alternate" type="application/rss+xml"><?php _e('All posts') ?></a></li>
					<li><a href="<?php bloginfo('comments_rss2_url') ?>" title="<?php echo wp_specialchars(bloginfo('name'), 1) ?> <?php _e('Comments RSS feed'); ?>" rel="alternate" type="application/rss+xml"><?php _e('All comments') ?></a></li>
				</ul>
			</li>

			<li id="meta">
				<h3><?php _e('Meta') ?></h3>
				<ul>
					<?php wp_register() ?>

					<li><?php wp_loginout() ?></li>
					<?php wp_meta() ?>

				</ul>
			</li>
<?php endif; // end secondary sidebar widgets  ?>
		</ul>
		<div class="clear"></div>
	</div><!-- END #barRight -->
</div> <!-- END #sidebar -->