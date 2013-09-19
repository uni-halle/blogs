		<div id="sidebar">
			
			<a class="subscribe" href="<?php bloginfo('rss2_url'); ?>" title="Posts RSS">Subscribe</a>
			
			<?php include(TEMPLATEPATH.'/searchform.php')?>
			
			<div id="widgets">
			
				<?php if (function_exists('dynamic_sidebar')&&dynamic_sidebar()):else: ?>
			
				<div class="widget">
				
					<h3>Categories</h3>
				
					<ul>
						<?php wp_list_categories('title_li=&show_count=1'); ?>					
					</ul>
					
					<div class="widgetbottom"></div>
				</div><!-- /widget -->
				
				<div class="widget">
				
					<h3>Archives</h3>
				
					<ul>
						<?php wp_get_archives('type=monthly&limit=&format=html&before=&after=&show_post_count=1'); ?>			
					</ul>
					
					<div class="widgetbottom"></div>
				</div><!-- /widget -->
				
				<div class="widget">
				
					<h3>Links</h3>
				
					<ul>
						<?php wp_list_bookmarks('title_before=<h4>&title_after=</h4>'); ?>					
					</ul>
					
					<div class="widgetbottom"></div>
				</div><!-- /widget -->
			
				<?php endif; ?>
			
			</div><!-- /widgets -->
			
		</div><!-- /sidebar -->