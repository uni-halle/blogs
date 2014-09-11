<li><h1><?php _e('Meta','avenue'); ?></h1><ul>

<?php wp_register(); ?>
<li><?php wp_loginout(); ?></li>

<li><a href="<?php bloginfo('rss2_url'); ?>" title="<?php _e('Entries (RSS)','avenue')?>"><?php _e('Entries RSS','avenue')?> 
<img src="<?php bloginfo('template_directory'); ?>/images/rss.jpg" alt="<?php _e('Entries (RSS)','avenue')?>" height="11" width="20" /></a></li>

<li><a href="<?php bloginfo('comments_rss2_url'); ?>" title="<?php _e('Comments (RSS)','avenue')?>"><?php _e('Comments RSS','avenue')?> 
<img src="<?php bloginfo('template_directory'); ?>/images/rss.jpg" alt="<?php _e('Comments (RSS)','avenue')?>" /></a></li>

<li><a href="http://wordpress-deutschland.org/" title="<?php _e('Wordpress Germany','avenue')?>"><?php _e('Wordpress Germany','avenue')?></a></li>

<?php wp_meta(); ?></ul></li>