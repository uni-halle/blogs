    <div style="clear:both"></div>
    </div> <!-- end of #container -->

    <div id="footer">
 
    	<div id="top-footer">                       
          	<ul>
                <li><a href="/" <?php if (is_home()) { ?>class="current"<?php } ?>>Home</a></li>
                <?php wp_list_pages('title_li=&depth=1'); ?> 
            </ul>
        </div>
        
        <div id="credits">
		  <span ><a href="<?php bloginfo('rss2_url'); ?>" class="rss-small" title="<?php _e('Syndicate this site using RSS'); ?>" ></a>
          <?php bloginfo('name'); ?> &copy; <?php the_time('Y'); ?>. All Rights Reserved. </span> 
   	    </div>

  	    <?php wp_footer(); ?>
        
    </div>


</div> <!-- end of #wrap -->

</body>
</html>