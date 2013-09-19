      </div>
      <div id="contentbottom"></div>
    </div>
    <div id="sidebarcontainer">
      <div id="sidebarhideable">
        <div id="sidebartop"> </div>
        <div id="sidebar">
          <ul>
            <?php  /* Widgetized sidebar, bah. */ 
            if ( !function_exists('dynamic_sidebar') || !dynamic_sidebar() ) : 
            ?> 
            <?php wp_list_categories('show_count=1&title_li=<h2>Kategorien</h2>'); ?>
            <?php if (function_exists('wp_tag_cloud')) { ?>
            <!--
            <li><h2>Tags</h2>
              <ul>
                <li id="wp_tag_cloud"><?php wp_tag_cloud('smallest=10&largest=20&unit=px'); ?></li>
              </ul>
            </li>
            -->
            <?php } ?>
            <?php wp_list_pages('title_li=<h2>Seiten</h2>' ); ?>
            <?php if (is_home()) { ?>
            <?php wp_list_bookmarks(); ?>
            <li><h2>Archiv</h2>
                <ul>
                <?php wp_get_archives('show_post_count=1&type=monthly'); ?>
                </ul>
            </li>
            <?php } ?>
            <li><h2>Suche</h2>
              <?php include (TEMPLATEPATH . '/searchform.php'); ?>
            </li>
            <li><h2>Meta</h2>
            <ul>
              <li>
                <a href="http://wordpress.org/">WordPress</a>.<a href="http://wordpress-deutschland.org">de</a>
              </li>
              <li>
                <a href="http://mootools.net">mootools</a> | 
                <a href="http://gmpg.org/xfn/"><abbr title="XHTML Friends Network">XFN</abbr></a>
              </li>
              <li>
                <a href="http://validator.w3.org/check/referer" title="Valides XHTML 1.1"><abbr title="eXtensible HyperText Markup Language">XHTML</abbr></a> | 
                <a href="http://jigsaw.w3.org/css-validator/check/referer" title="Valides CSS"><abbr title="Cascading Style Sheet">CSS</abbr></a>
              </li>
              <?php wp_register(); ?>
              <li><?php wp_loginout(); ?></li>
              <?php wp_meta(); ?>
            </ul>
            </li>
          <?php endif; ?> 
          </ul>
        </div>
        <div id="sidebarbottom"></div>
      </div>
    </div>
