<?php if (get_option('organic_theme_tabs') === "true" ) { ?>
<div id="tabs">
	<ul>		
			<div id="tabvanilla" class="widget">
				<ul class="tabnav">
					<li><a href="#latest">Latest</a></li>
					<li class="pop-tab"><a href="#popular">Popular</a></li>
					<li><a href="#comments">Comments</a></li>
				</ul>	
				<div id="latest" class="tabdiv">
					<ul>
					<?php wp_get_archives('type=postbypost&limit=10'); ?>
					</ul>
				</div>
				<div id="popular" class="tabdiv">
                    <?php
                         if (function_exists('get_mostpopular')) {
                            get_mostpopular();
                         } else {
                             echo "<p style='margin-left:15px;'>Please <a href=http://wordpress.org/extend/plugins/wordpress-popular-posts/ style='text-decoration:underline;' target=_blank> click here to download</a> the plugin required to make popular posts work!</p>";
                         }
                    ?>
				</div>
				<div id="comments" class="tabdiv">
					<ul>
                       <?php
                         if (function_exists('get_recent_comments')) {
                            get_recent_comments();
                         } else {
                             echo "<p>Please <a href=http://blog.jodies.de/2004/11/recent-comments/ style='text-decoration:underline;' target=_blank> click here to download</a> the plugin required to make recent comments work!</p>";
                         }
                    ?>
					</ul>
				</div>
			</div>		
	</ul>
</div>
<?php } ?>	
<div id="menu">
<ul>
	<?php if ( !function_exists('dynamic_sidebar') || !dynamic_sidebar() ) : ?>
		<?php wp_list_categories('show_count=1&title_li=' . __('<h3>Categories</h3>')); ?>
			<li><h3>Calendar</h3><?php get_calendar(); ?></li>
			
			<li><h3>Archives</h3>
				<ul>
					<?php wp_get_archives('show_post_count=1&type=monthly'); ?>
				</ul>
			</li>
			
			<li><h3>Blogroll</h3>
				<ul>
					<?php wp_list_bookmarks('categorize=0&title_li='); ?>
				</ul>
			</li> 
	<?php endif; ?>
</ul>
</div>
