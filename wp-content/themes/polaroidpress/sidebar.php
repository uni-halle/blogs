<div id="sidebar">

<?php if ( !function_exists('dynamic_sidebar') || !dynamic_sidebar(1) ) : ?>
            
    <h2><?php _e('Categories'); ?></h2>
    <ul>
		<?php wp_list_cats('sort_column=name&optioncount=1&hierarchical=0'); ?>
    </ul>
      
    <h2><?php _e('Archive'); ?></h2>
	<ul>
	 <?php wp_get_archives('type=monthly'); ?>
	</ul>
	   
    <h2><?php _e('Links'); ?></h2>
    <ul>
    	<?php wp_list_bookmarks('categorize=0&title_li=0&title_after=&title_before='); ?>		
    </ul>

	<h2><?php _e('Meta'); ?></h2>
	<ul>
		<?php wp_register(); ?>
		<li><?php wp_loginout(); ?></li>
		<li><a href="<?php bloginfo('comments_rss2_url'); ?>" title="<?php _e('The latest comments to all posts in RSS'); ?>"><?php _e('Comments <abbr title="Really Simple Syndication">RSS</abbr>'); ?></a></li>
		<?php wp_meta(); ?>
	</ul>

<?php endif; ?>
        
</div>