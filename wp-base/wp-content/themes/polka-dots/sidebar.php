	<div id="sidebar" class="sidecol">
	<ul>

  <?php include('ad_sidebar.php'); ?>


<?php if ( function_exists('dynamic_sidebar') && dynamic_sidebar(2) ) : else : ?>

  <li>
  <h2><?php _e('Search'); ?></h2>
	<form id="searchform" method="get" action="<?php bloginfo('siteurl')?>/">
		<input type="text" name="s" id="s" class="textbox" value="<?php echo wp_specialchars($s, 1); ?>" />
		<input id="btnSearch" type="submit" name="submit" value="<?php _e('Go'); ?>" />
	</form>
  </li>  




<li>
    <h2>Feed on</h2>
    <ul>
      <li class="feed"><a title="RSS Feed of Posts" href="<?php bloginfo('rss2_url'); ?>">Posts RSS</a></li>
      <li class="feed"><a title="RSS Feed of Comments" href="<?php bloginfo('comments_rss2_url'); ?>">Comments RSS</a></li>
    </ul>
  </li>
<?php get_links_list(); ?>      


  <?php if (function_exists('wp_tag_cloud')) {	?>


  <?php } ?>
  <li>
    <h4>
      <?php _e('Monthly'); ?>
    </h4>
    <ul>
      <?php wp_get_archives('type=monthly&show_post_count=true'); ?>
    </ul>
  </li>
  <li>
    <h2><?php _e('Pages'); ?></h2>
    <ul>
      <?php wp_list_pages('title_li=' ); ?>
    </ul>
  </li>
    
<?php endif; ?>

</ul>
</div>
<div style="clear:both;"></div>