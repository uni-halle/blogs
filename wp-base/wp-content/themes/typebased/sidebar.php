		<!--- Sidebar Starts -->
		
		<div id="sidebar" class="right-col">
		
			<div id="search">
				<form method="get" id="searchform" action="<?php bloginfo('url'); ?>/">
					<div>
						<input type="text" class="search_box" name="s" id="s" />
						<input type="image" src="<?php bloginfo('template_directory'); ?>/images/search.gif" class="submit" name="submit" />
					</div>
				</form>
			</div>
						
			<div id="sidebar_in">
			
			<?php if (function_exists('dynamic_sidebar') && dynamic_sidebar(1) ) : else : ?>
			
			
			<?php endif; ?>
			
			</div>
				
		</div>
		
		<!--- Sidebar Ends -->