	   <div id="explore-area">
	   
	       <div id="archives-area">
	       
                <h3>Archives <span>by</span> Month</h3>
                <ul>
                    <?php wp_get_archives('type=monthly'); ?>
                </ul>
		  
	       </div>
	       
	       <div id="blogroll-area">
	       
	           <h3><span>The</span> Blogroll</h3>
	           
	           <ul>
	            <?php wp_list_bookmarks('title_li=&categorize=0'); ?>
	           </ul>
	           
	       </div>
	       
	       <div id="search-area">
	       
	           <h3>Search <span>this</span> Site</h3>
	           
	           <?php get_search_form(); ?>
	       
	       </div>
	   
		  
		</div>
		
	
	</div> <!-- #wrap -->

<div id="footer">
	<p>
		<span class="left">&copy; <?= date('Y'); ?> <a href="<?php bloginfo('url'); ?>/"><?php bloginfo('name'); ?></a></span>
		<span class="right">Proudly powered by <a href="http://wordpress.org">WordPress</a></span>
	</p>
</div>

<?php wp_footer(); ?>

</body>
</html>