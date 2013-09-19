	<div id="sidebar">
		<div id="sidebar_search">
			<form method="get" action="<?php bloginfo('url'); ?>/">
				<div>
					<input type="text" value="Type your search here..." name="s" id="sidebar_search_val" onclick="this.value='';" />
					<input type="image" src="<?php bloginfo('template_url')?>/images/button_go.gif" id="sidebar_search_sub" />
				</div>
			</form>
		</div>
		<div id="sidebar_about">
			<h2>About</h2>
			<?php $about_description = obwp_get_meta(SHORTNAME.'_about_description'); ?>
			<?php $about_logo = theme_about_logo(); ?>
			<p><?php if(!empty($about_logo)) : ?><img src="<?php echo $about_logo; ?>" alt="about" /><?php endif; echo $about_description; ?></p>
		</div>
		<div id="sidebar_left" class="sidebar_widgets">
			<ul>
			<?php if ( function_exists('dynamic_sidebar') && dynamic_sidebar(1) ){
				} else { ?>	
				
				<li class="widget_categories">
					<h2 class="widgettitle">Category</h2>
					<ul>
						<?php wp_list_cats('sort_column=name&optioncount=1'); ?>
					</ul>
				</li>	
				
				<li class="widget_recent_entries">
					<h2 class="widgettitle">recent posts</h2>
					<?php obwp_list_recent_posts(5); ?>
				</li>
				
			<?php } ?>
			</ul>
		</div>
		<div id="sidebar_right" class="sidebar_widgets">
			<ul>
			<?php if ( function_exists('dynamic_sidebar') && dynamic_sidebar(2) ){
				} else { ?>		
	
				<li class="widget_archives">
					<h2 class="widgettitle">Archives</h2>
					<ul>
					<?php wp_get_archives('type=monthly'); ?>
					</ul>
				</li>
	
				<li class="widget_links">
					<h2 class="widgettitle">Partner Links</h2>
					<ul>
					<?php wp_list_bookmarks('title_li=&categorize=0'); ?>
					</ul>
				</li>
				
			<?php } ?>
			</ul>
		</div>
	</div>

