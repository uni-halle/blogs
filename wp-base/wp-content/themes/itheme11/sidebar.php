<div class="dbx-group" id="sidebar">

  <?php if ( !function_exists('dynamic_sidebar') || !dynamic_sidebar() ) : ?>

      <!--sidebox start -->
      <div id="categories" class="dbx-box">
        <h3 class="dbx-handle"><?php _e('Categories'); ?></h3>
        <div class="dbx-content">
          <ul>
            <?php wp_list_cats('sort_column=name&optioncount=1&hierarchical=0'); ?>
          </ul>
        </div>
      </div>
      <!--sidebox end -->

      <!--sidebox start -->
      <div id="archives" class="dbx-box">
        <h3 class="dbx-handle"><?php _e('Archives'); ?></h3>
        <div class="dbx-content">
          <ul>
            <?php wp_get_archives('type=monthly'); ?>
          </ul>
        </div>
      </div>
      <!--sidebox end -->

      <!--sidebox start -->
      <div id="links" class="dbx-box">
        <h3 class="dbx-handle"><?php _e('Links'); ?></h3>
        <div class="dbx-content">
          <ul>
            <?php get_links('-1', '<li>', '</li>', '<br />', FALSE, 'id', FALSE, FALSE, -1, FALSE); ?>
          </ul>
        </div>
      </div>
      <!--sidebox end -->

      <!--sidebox start -->
      <div id="meta" class="dbx-box">
        <h3 class="dbx-handle">Meta</h3>
        <div class="dbx-content">
          <ul>
              <li class="rss"><a href="<?php bloginfo('rss2_url'); ?>">Entries (RSS)</a></li>
              <li class="rss"><a href="<?php bloginfo('comments_rss2_url'); ?>">Comments (RSS)</a></li>
              <li class="wordpress"><a href="http://www.wordpress.org" title="Powered by WordPress">WordPress</a></li>
              <li class="login"><?php wp_loginout(); ?></li>
          </ul>
        </div>
      </div>
      <!--sidebox end -->

  <?php endif; ?>

</div><!--/sidebar -->