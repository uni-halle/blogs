<?php

/**
 * Widget Wrangler Sidebar Widget class.
 * This class handles everything that needs to be handled with the widget:
 * the settings, form, display, and update.  Nice!
 *
 * @since 0.1
 */
class WidgetWrangler_Corral_Widget extends WP_Widget {
  /**
   * Widget setup.
   */
  function __construct()
  {
    // Widget settings. 
    $widget_ops = array( 'classname' => 'widget-wrangler-widget-classname', 'description' => __('A single Widget Wrangler Corral (Sidebar)', 'widgetwrangler') );
    
    // Widget control settings. 
    $control_ops = array( 'id_base' => 'widget-wrangler-sidebar');
    
    // Create the widget. 
    parent::__construct( 'widget-wrangler-sidebar', __('Widget Wrangler - Corral', 'widgetwrangler'), $widget_ops, $control_ops );
    
    global $widget_wrangler;
    $this->ww = $widget_wrangler;
  }
  
  /**
   * How to display the widget on the screen.
   */
  function widget( $args, $instance )
  {
    $this->ww->display->dynamic_corral($instance['sidebar'], $args);
  }
  
	/**
	 * Update the widget settings.
	 */
	function update( $new_instance, $old_instance )
  {
    $instance = $old_instance; 
    $instance['title'] = $new_instance['title'];
    $instance['sidebar'] = $new_instance['sidebar'];
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
    $defaults = array( 'title' => __('Widget Wrangler Corral', 'widgetwrangler'), 'sidebar' => '' );
    $instance = wp_parse_args( (array) $instance, $defaults );
    $sidebars = $this->ww->corrals;
    ?>
    <?php // Widget Title: Hidden Input ?>
    <input type="hidden" id="<?php echo $this->get_field_id( 'title' ); ?>" name="<?php echo $this->get_field_name( 'title' ); ?>" value="<?php echo $sidebars[$instance['sidebar']]; ?>" style="width:100%;" />
    
    <?php // Sidebar: Select Box ?>
    <p>
     <label for="<?php echo $this->get_field_id( 'sidebar' ); ?>"><?php _e('Corral', 'widgetwrangler'); ?>:</label>
     <select id="<?php echo $this->get_field_id( 'sidebar' ); ?>" name="<?php echo $this->get_field_name( 'sidebar' ); ?>" class="widefat" style="width:100%;">
      <?php
        foreach($sidebars as $slug => $name)
        {
          ?>
          <option <?php if ($instance['sidebar'] == $slug){ print 'selected="selected"'; }?> value="<?php print $slug; ?>"><?php print $name; ?></option>
          <?php
        }
      ?>
     </select>
    </p>
    <?php
  }
}
