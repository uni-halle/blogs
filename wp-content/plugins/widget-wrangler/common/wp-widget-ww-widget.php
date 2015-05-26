<?php

/**
 * Widget Wrangler Sidebar Widget class.
 * This class handles everything that needs to be handled with the widget:
 * the settings, form, display, and update.  Nice!
 *
 * @since 0.1
 */
class WidgetWrangler_Widget_Widget extends WP_Widget {
  /**
   * Widget setup.
   */
  function __construct()
  {
    // Widget settings. 
    $widget_ops = array( 'classname' => 'widget-wrangler-widget-widget-classname', 'description' => __('A single Widget Wrangler Widget', 'widgetwrangler') );
    
    // Widget control settings. 
    $control_ops = array( 'id_base' => 'widget-wrangler-widget');
    
    // Create the widget. 
    $this->WP_Widget( 'widget-wrangler-widget', __('Widget Wrangler - Widget', 'widgetwrangler'), $widget_ops, $control_ops );
    
    global $widget_wrangler;
    $this->ww = $widget_wrangler;
  }
  
  /**
   * How to display the widget on the screen.
   */
  function widget( $args, $instance )
  {
    if ($widget = $this->ww->get_single_widget($instance['post_id'])){
      print $this->ww->display->theme_single_widget($widget, $args);
    }
  }
  
	/**
	 * Update the widget settings.
	 */
	function update( $new_instance, $old_instance )
  {
    $instance = $old_instance; 
    $instance['title'] = $new_instance['title'];
    $instance['post_id'] = $new_instance['post_id'];
    return $instance;
	}
  
  /**
   * Displays the widget settings controls on the widget panel.
   * Make use of the get_field_id() and get_field_name() function
   * when creating your form elements. This handles the confusing stuff.
   */
  function form( $instance )
  {
    // Set up some default widget settings. 
    $defaults = array( 'title' => __('Widget', 'widgetwrangler'), 'post_id' => '' );
    $instance = wp_parse_args( (array) $instance, $defaults );
    $widgets = $this->ww->get_all_widgets(array('publish', 'draft'));

    // keep the post_title for easy UI reference
    if ( !empty($instance['post_id']) ){
      $this_widget = $this->ww->get_single_widget( $instance['post_id'] );
      $instance['title'] = $this_widget->post_title;
    }
    ?>
    <?php // Widget Title: Hidden Input ?>
    <input type="hidden" id="<?php echo $this->get_field_id( 'title' ); ?>" name="<?php echo $this->get_field_name( 'title' ); ?>" value="<?php echo $instance['title']; ?>" style="width:100%;" />
    
    <?php // Sidebar: Select Box ?>
    <p>
     <label for="<?php echo $this->get_field_id( 'post_id' ); ?>"><?php _e('Widget', 'widgetwrangler'); ?>:</label>
     <select id="<?php echo $this->get_field_id( 'post_id' ); ?>" name="<?php echo $this->get_field_name( 'post_id' ); ?>" class="widefat" style="width:100%;">
      <?php
        foreach($widgets as $widget)
        {
          $title = $widget->post_title;
          if ($widget->post_status == "draft") {
            $title.=  " - <em>(draft)</em>";
          }
          ?>
          <option <?php selected( $instance['post_id'], $widget->ID ); ?> value="<?php print $widget->ID; ?>"><?php print $title ?></option>
          <?php
        }
      ?>
     </select>
    </p>
    <?php
  }
}
