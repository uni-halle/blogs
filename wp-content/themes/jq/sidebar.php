<?php
/* This code retrieves all our admin options. */
global $options;
foreach ($options as $value) {
	if (get_settings( $value['id'] ) === FALSE) { $$value['id'] = $value['std']; } else { $$value['id'] = get_settings( $value['id'] ); }
}
?>

<div id="right">
<p id="toggle-all"><a href="#" id="toggle" class="hide-all">Toggle posts</a></p>
<?php if($jq_font_size==0) { ?>
<p id="font-resize">
<a id="default" href="#">A </a><a id="larger" href="#">A+ </a><a id="largest" href="#">A++</a>
</p>
<?php } ?>
<div class="clearfix"></div>
<!-- widget -->
<?php if ( !function_exists('dynamic_sidebar') || !dynamic_sidebar('sidebar_right') ) : ?>
<div class="widget">
<h4><a href="#" title="Toggle" class="hide_widget">Categories</a></h4>
<ul>
<?php wp_list_categories('title_li=0&categorize=0'); ?>
</ul>
</div>
<div class="widget">
<h4><a href="#" title="Toggle" class="hide_widget">Blogroll</a></h4>
<ul>
<?php wp_list_bookmarks('title_li=0&categorize=0'); ?>
</ul>
</div>
<div class="widget">
<h4><a href="#" title="Toggle" class="hide_widget">Meta</a></h4>	
<ul>
<?php wp_register(); ?>
<li><?php wp_loginout(); ?></li>
<li><a href="<?php bloginfo('rss2_url'); ?>" title="<?php _e('Syndicate this site using RSS'); ?>"><?php _e('<abbr title="Really Simple Syndication">RSS</abbr>'); ?></a></li>
<li><a href="<?php bloginfo('comments_rss2_url'); ?>" title="<?php _e('The latest comments to all posts in RSS'); ?>"><?php _e('Comments <abbr title="Really Simple Syndication">RSS</abbr>'); ?></a></li>
<?php wp_meta(); ?>
</ul>
</div>
<?php endif; // endif widget ?>     
</div>
