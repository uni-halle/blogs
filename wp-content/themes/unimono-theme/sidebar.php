 <!-- Sidebar -->  
 
<div class="sidebar">
	<ul>
        <li>
           <form method="get" class="sidebar_search"  id="searchform" action="<?php bloginfo('url'); ?>/"><input type="text" value="Suche" name="s" id="s" class="search_input" onfocus="javascript:inputcheck(this,'focus')" onblur="javascript:inputcheck(this,'blur')" /><input type="submit" value="go"  value="search" class="submit_search" /></form>            
        </li>
    	<?php if ( !function_exists('dynamic_sidebar') || !dynamic_sidebar(1) ) : ?>
        <li>
        <h2>Categories</h2>
        <ul>
        <?php wp_list_categories('title_li=0&categorize=0'); ?>
        </ul>
        </li>
        <li>
        <h2>Blogroll</h2>
        <ul>
        <?php wp_list_bookmarks('title_li=0&categorize=0'); ?>
        </ul>
        </li>
        <li>
        <h2>Meta</h2>	
        <ul>
        <?php wp_register(); ?>
        <li><?php wp_loginout(); ?></li>
        <li><a href="<?php bloginfo('rss2_url'); ?>" title="<?php _e('Syndicate this site using RSS'); ?>"><?php _e('<abbr title="Really Simple Syndication">RSS</abbr>'); ?></a></li>
        <li><a href="<?php bloginfo('comments_rss2_url'); ?>" title="<?php _e('The latest comments to all posts in RSS'); ?>"><?php _e('Comments <abbr title="Really Simple Syndication">RSS</abbr>'); ?></a></li>
        <?php wp_meta(); ?>
        </ul>
        </li>
        <?php endif; ?>
    </ul>
</div>  
<div class="clear"></div>
</div>
</div>
