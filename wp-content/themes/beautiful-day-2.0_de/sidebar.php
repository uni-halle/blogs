
<div class="sidenav" id="sidebar">
  <?php if ( !function_exists('dynamic_sidebar')
        || !dynamic_sidebar() ) : ?>
  <h2>Kategorien</h2>
  <ul>
    <?php wp_list_cats('sort_column=name&hierarchical=0'); ?>
  </ul>
  <h2>Archiv</h2>
  <ul>
    <?php wp_get_archives('type=monthly'); ?>
  </ul>
  <h2>
    <?php _e('Blogroll'); ?>
  </h2>
  <ul>
    <?php get_links(-1, '<li>', '</li>', '', FALSE, 'name', FALSE, FALSE, -1, FALSE); ?>
  </ul>
  <h2>Suche</h2>
  <ul>
    <li>
      <form method="get" id="searchform" action="<?php echo $_SERVER['PHP_SELF']; ?>">
        <input type="text" value="<?php echo wp_specialchars($s, 1); ?>" name="s" id="s" size="17" />
        <input type="submit" id="sidebarsubmit" value="Suche" style="font-size: 10px;" />
      </form>
    </li>
  </ul>
  <h2>
    <?php _e('Meta'); ?>
  </h2>
  <ul>
    <li><a href="<?php bloginfo('rss2_url'); ?>" title="<?php _e('Syndicate this site using RSS'); ?>">
      <?php _e('<abbr title="Really Simple Syndication">RSS</abbr>'); ?>
      </a></li>
    <li><a href="<?php bloginfo('comments_rss2_url'); ?>" title="<?php _e('The latest comments to all posts in RSS'); ?>">
      <?php _e('Comments <abbr title="Really Simple Syndication">RSS</abbr>'); ?>
      </a></li>
    <li><a href="http://validator.w3.org/check/referer" title="<?php _e('This page validates as XHTML 1.0 Transitional'); ?>">
      <?php _e('Valid <abbr title="eXtensible HyperText Markup Language">XHTML</abbr>'); ?>
      </a></li>
    <li><a href="http://gmpg.org/xfn/"><abbr title="XHTML Friends Network">XFN</abbr></a></li>
    <?php wp_meta(); ?>
  </ul>
  <?php endif; ?>
</div>
