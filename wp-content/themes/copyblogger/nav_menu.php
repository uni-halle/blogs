<li><a <?php if (is_home()) echo('class="current" '); ?>href="<?php bloginfo('url'); ?>">home</a></li>
<li><a <?php if (is_page('archives')) echo('class="current" '); ?>href="<?php bloginfo('url'); ?>/archives/">archives</a></li>
<li><a <?php if (is_page('about')) echo('class="current" '); ?>href="<?php bloginfo('url'); ?>/about/">about</a></li>