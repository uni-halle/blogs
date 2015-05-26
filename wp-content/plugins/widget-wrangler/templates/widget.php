<?php
/*
 * Some useful variables:
 *
 *  $wrapper_element
 *  $wrapper_id
 *  $wrapper_classes
 *  $title_element
 *  $title_classes
 *  $content_element
 *  $content_classes
 *  
 *  $widget->post_title
 *  $widget->post_content
 *  $widget->post_name
 *  $widget->ID
 *
 */

?>
<?php if ($wrapper_element) { ?><<?php print $wrapper_element; ?> id="<?php print $wrapper_id; ?>" class="<?php print $wrapper_classes; ?>"><?php } ?>
  <?php if($widget->post_title) { ?>
    <?php if ($title_element) { ?><<?php print $title_element;?> class="<?php print $title_classes; ?>"><?php } ?>
      <?php print $widget->post_title;?>
    <?php if ($title_element) { ?></<?php print $title_element; ?>><?php } ?>  
  <?php } ?>
  <?php if($widget->post_content) { ?>
    <?php if ($content_element) { ?><<?php print $content_element; ?> class="<?php print $content_classes; ?>"><?php } ?>
      <?php print $widget->post_content; ?>
    <?php if ($content_element) { ?></<?php print $content_element; ?>><?php } ?>
  <?php } ?>
<?php if ($wrapper_element) { ?></<?php print $wrapper_element; ?>><?php } ?>