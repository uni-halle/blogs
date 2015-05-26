<?php
/*
 * Some useful variables:
 * 
 * $widget->post_title
 * $widget->post_content
 * $widget->post_name
 * $widget->ID
 *
 */

?>
<div id="widget-<?php print $widget->ID; ?>" class="widget">
  <?php if($widget->post_title) { ?>
    <h3><?php print $widget->post_title;?></h3>
  <?php } ?>
  <?php if($widget->post_content) { ?>  
    <div class="content">
      <?php print $widget->post_content; ?>
    </div>
  <?php } ?>
</div>