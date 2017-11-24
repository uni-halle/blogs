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
		$widget_ops = array( 'classname' => 'widget-wrangler-widget-classname', 'description' => __('A single Widget Wrangler Corral', 'widgetwrangler') );

		// Widget control settings.
		$control_ops = array( 'id_base' => 'widget-wrangler-sidebar');

		// Create the widget.
		parent::__construct( 'widget-wrangler-sidebar', __('Widget Wrangler - Corral', 'widgetwrangler'), $widget_ops, $control_ops );
	}

	/**
	 * Output the configured corral's widgets.
	 *
	 * @param array $args
	 * @param array $instance
	 */
	function widget( $args, $instance )
	{
		$settings = \WidgetWrangler\Settings::instance();
		$display = new WidgetWrangler\Display($settings->values);
		$display->dynamic_corral($instance['sidebar'], $args);
	}

	/**
	 * Update the widget settings.
	 *
	 * @param array $new_instance
	 * @param array $old_instance
	 *
	 * @return array
	 */
	function update( $new_instance, $old_instance )
	{
		$instance = $old_instance;
		$corrals = \WidgetWrangler\Corrals::all();

		if ( !empty( $corrals[ $new_instance['sidebar'] ] ) ) {
			$instance['title'] = $corrals[ $new_instance['sidebar'] ];
			$instance['sidebar'] = $new_instance['sidebar'];
        }

		return $instance;
	}

	/**
	 * Displays the widget settings controls on the widget panel.
	 * Make use of the get_field_id() and get_field_name() function
	 * when creating your form elements. This handles the confusing stuff.
	 *
	 * @param array $instance
	 *
	 * @return string|void
	 */
	function form( $instance )
	{
		// Set up some default widget settings.
		$defaults = array( 'title' => __('Widget Wrangler Corral', 'widgetwrangler'), 'sidebar' => '' );
		$instance = wp_parse_args( (array) $instance, $defaults );
		$corrals = \WidgetWrangler\Corrals::all();
		$corral_title = !empty($instance['sidebar']) && !empty($corrals[$instance['sidebar']]) ? $corrals[$instance['sidebar']] : '';
		?>
		<?php // Widget Title: Hidden Input ?>
        <input type="hidden" id="<?php echo $this->get_field_id( 'title' ); ?>" name="<?php echo $this->get_field_name( 'title' ); ?>" value="<?php echo $corral_title; ?>" style="width:100%;" />

		<?php // Sidebar: Select Box ?>
        <p>
            <label for="<?php echo $this->get_field_id( 'sidebar' ); ?>"><?php _e('Corral', 'widgetwrangler'); ?>:</label>
            <select id="<?php echo $this->get_field_id( 'sidebar' ); ?>" name="<?php echo $this->get_field_name( 'sidebar' ); ?>" class="widefat" style="width:100%;">
				<?php
				foreach($corrals as $slug => $name)
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
