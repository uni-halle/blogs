<div class="SR"><div class="SRL">

<div class="Search">
<form action="<?php echo $_SERVER['PHP_SELF']; ?>" method="post">
<input type="text" name="s" class="keyword" />
<div id="buttonsearch"><input name="submit" type="image" class="search" title="Search" src="<?php bloginfo('template_url'); ?>/images/ButtonTransparent.png" alt="Search" />
</div>
</form>
</div>

<div class="Syn"><div class="SynTop"></div>
 <ul>
  <li><a href="<?php bloginfo('rss2_url'); ?>">Entries</a> (RSS)</li>
  <li><a href="<?php bloginfo('comments_rss2_url'); ?>">Comments</a> (RSS)</li>
 </ul>
</div>

<!-- Start Flickr Photostream -->
<?php if (function_exists('get_flickrrss')) { ?>
<div class="Flickr">
  <h2>PhotoStream</h2>
  <ul>
   <?php get_flickrrss(); ?> 
  </ul>
</div>
<?php } ?>
<!-- End Flickr Photostream -->

<?php if ( function_exists('dynamic_sidebar') && dynamic_sidebar('Sidebar_left') ) : else : ?>

<div class="widget widget_categories">
<h2>Categories</h2>
 <ul>
  <?php wp_list_cats(); ?>
 </ul>
</div>
    
<!-- Start Recent Comments -->
<?php if (function_exists('mdv_recent_comments')) { ?>
<div class="widget widget_recent_entries">
<h2>Recent Comments</h2>
 <ul>
  <?php mdv_recent_comments('10'); ?>
 </ul>
</div>
<?php } ?>
<!-- End Recent Comments -->

<?php endif; ?>
</div><div class="SRR">

<?php if ( function_exists('dynamic_sidebar') && dynamic_sidebar('Sidebar_right') ) : else : ?>

<div class="widget widget_links">
<h2>Links</h2>
 <ul><?php get_links('-1', '<li>', '</li>', '', FALSE, 'id', FALSE, 
FALSE, -1, FALSE); ?>
</ul>
</div>

<div class="widget widget_archives">
<h2>Archives</h2>
<ul>
<?php wp_get_archives('type=monthly'); ?>
</ul>
</div>
				
<div class="widget widget_meta">
<h2>Meta</h2>
<ul>
<?php wp_register(); ?>
<li><?php wp_loginout(); ?></li>
<li><a href="http://validator.w3.org/check/referer" title="This page validates as XHTML 1.0 Transitional">Valid <abbr title="eXtensible HyperText Markup Language">XHTML</abbr></a></li>
<li><a href="http://gmpg.org/xfn/"><abbr title="XHTML Friends Network">XFN</abbr></a></li>
<li><a href="http://wordpress.org/" title="Powered by WordPress, state-of-the-art semantic personal publishing platform.">WordPress</a></li>
<?php wp_meta(); ?>
</ul>
</div>
  
<?php endif; ?>
</div></div>

