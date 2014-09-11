	        <div id="ft" class="yui-gc">
							        
	        	<div class="yui-u first">
						
					<ul>
				
						<li><a href="<?php bloginfo('url'); ?>">Home</a></li>
			            <?php wp_list_pages('title_li=&depth=1'); ?>						
					</ul>
										
	        		<p>&#169; <?php the_time('Y'); ?> <span class="wp-title"><?php bloginfo('name'); ?></span>: <span class="wp-tagline"><?php bloginfo('description'); ?></span></p>
	        	
				</div>
	        	
	        	<div class="alignright yui-u">
					
					<p><a href="http://www.wordpress.org/">WordPress</a> theme by <a href="http://developdaly.com/" title="Develop Daly" class="highlight">Develop Daly</a></p>
					
	        	</div>
	        
	        </div><!-- end #ft -->	        
	    
	    </div><!-- end #doc4 -->
	    
		<?php wp_footer(); ?>
	
	</body>

</html>