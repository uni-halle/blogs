	<!-- NAVIGATION AND PAGES -->
	<?php sandbox_globalnav() ?>
	
	<div id="initial" class="sidebar">
		<ul class="xoxo">
<?php if (!function_exists('dynamic_sidebar') || !dynamic_sidebar(1) ) : // begin Tabbed widgets ?>
	
			<div class="tabmenu">
				<ul id="countrytabs" class="shadetabs">
					<li><a href="#" rel="country1" class="selected">Tab Info</a></li>
					<li><a href="#" rel="country2">New Posts</a></li>
					<li><a href="#" rel="country3">Other</a></li>
				</ul>
				<br />
				
				<div id="menu-item-wrap">
					
					<div id="country1" class="tabcontent">
						<p>Edit this in <em>sidebar.php</em>. Aliquam vitae leo eu odio mattis bibendum. In congue volutpat lorem. Quisque odio. Praesent vehicula. Vestibulum ut felis? Phasellus nulla lorem, tempus ac, sollicitudin suscipit, tempor et, sem. Proin ultricies risus nec purus commodo pulvinar! Nunc tortor sem, elementum quis.</p>
					</div>
					<div id="country2" class="tabcontent">
						<p>Donec quis velit ut libero vehicula vestibulum. Cras pellentesque accumsan massa. Proin tempor, ipsum in lobortis interdum, mi nisi malesuada augue, at sollicitudin sem sem quis urna. Sed ullamcorper ante. Phasellus non justo sit amet est volutpat porttitor.</p>
					</div>
					
					<div id="country3" class="tabcontent">
						<p>Fusce dignissim. Nullam pede. Praesent massa. Pellentesque habitant morbi tristique senectus et netus et malesuada fames ac turpis egestas. </p>
					</div>		
				</div>
				
				<script type="text/javascript">
				
				var countries=new ddtabcontent("countrytabs")
				countries.setpersist(true)
				countries.setselectedClassTarget("link") //"link" or "linkparent"
				countries.init()
				
				</script>
			</div>
<?php endif; // end initial sidebar widgets  ?>
		</ul>
	</div>

	<div id="primary" class="sidebar">
		<ul class="xoxo">
<?php if (!function_exists('dynamic_sidebar') || !dynamic_sidebar(2) ) : // begin primary sidebar widgets ?>

			<li id="pages">
				<h3><?php _e('Pages', 'sandbox') ?></h3>
				<ul>
<?php wp_list_pages('title_li=&sort_column=post_title' ) ?>
				</ul>
			</li>

			<li id="categories">
				<h3><?php _e('Categories', 'sandbox'); ?></h3>
				<ul>
<?php wp_list_categories('title_li=&show_count=0&hierarchical=1') ?> 

				</ul>
			</li>

			<li id="archives">
				<h3><?php _e('Archives', 'sandbox') ?></h3>
				<ul>
<?php wp_get_archives('type=monthly') ?>

				</ul>
			</li>

			<li id="meta">
				<h3><?php _e('Meta', 'sandbox') ?></h3>
				<ul>
					<?php wp_register() ?>

					<li><?php wp_loginout() ?></li>
					<?php wp_meta() ?>

				</ul>
			</li>
<?php endif; // end primary sidebar widgets  ?>
		</ul>
	</div><!-- #primary .sidebar -->

	<div id="secondary" class="sidebar">
		<ul class="xoxo">
<?php if (!function_exists('dynamic_sidebar') || !dynamic_sidebar(3) ) : // begin  secondary sidebar widgets ?>
			<li id="search">
				<h3><label for="s"><?php _e('Search', 'sandbox') ?></label></h3>
				<form id="searchform" method="get" action="<?php bloginfo('home') ?>">
					<div>
						<input id="s" name="s" type="text" value="<?php echo wp_specialchars(stripslashes($_GET['s']), true) ?>" size="10" tabindex="1" />
						<input id="searchsubmit" name="searchsubmit" type="submit" value="<?php _e('Find', 'sandbox') ?>" tabindex="2" />
					</div>
				</form>
			</li>

			<li id="rss-links">
				<h3><?php _e('RSS Feeds', 'sandbox') ?></h3>
				<ul>
					<li><a href="<?php bloginfo('rss2_url') ?>" title="<?php echo wp_specialchars(get_bloginfo('name'), 1) ?> <?php _e('Posts RSS feed', 'sandbox'); ?>" rel="alternate" type="application/rss+xml"><?php _e('All posts', 'sandbox') ?></a></li>
					<li><a href="<?php bloginfo('comments_rss2_url') ?>" title="<?php echo wp_specialchars(bloginfo('name'), 1) ?> <?php _e('Comments RSS feed', 'sandbox'); ?>" rel="alternate" type="application/rss+xml"><?php _e('All comments', 'sandbox') ?></a></li>
				</ul>
			</li>
			
			<?php wp_list_bookmarks('title_before=<h3>&title_after=</h3>&show_images=1') ?>

<?php endif; // end secondary sidebar widgets  ?>
		</ul>
	</div><!-- #secondary .sidebar -->
