<?php if ( is_home() ) { ?>
<li class="shortcut"><h3 class="centered"><?php _e('Welcome','avenue'); ?></h3>
<?php _e('This blog actually contains ','avenue'); ?><?php global $numposts; echo $numposts; ?><?php _e(' Entries and ','avenue'); ?><?php global $numcmnts; echo $numcmnts;?><?php _e(' Comments. To get the latest information, you can subscribe to the RSS Feed.','avenue'); ?></li><?php } ?>