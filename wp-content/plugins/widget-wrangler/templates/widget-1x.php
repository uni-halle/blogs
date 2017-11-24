<?php
/*
 * Since this is a WordPress post, you are able to get
 *   additional information about the widget using normal functions such as:
 *   get_post_meta( $widget->ID, 'some meta key', TRUE);
 * Note, the widget is not loaded with a normal WP_Query(), so "template tags"
 *   such as get_the_ID(), get_the_title(), etc will not work.
 *
 * ----
 * Some useful variables
 *
 * $widget->ID:            The widget's post ID.
 * $widget->post_title:    The widget's title.
 * $widget->post_name:     The widget's slug.
 * $widget->post_content:  The widget's content. On a standard widget, this is
 *   the content within the editor area. On clone widgets, this is the output of
 *   the widget that was cloned.
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
