<?php
/*
Plugin Name: The Events Calendar Widget
Description: The Events Calendar Widget that displays the events filtered by category. Depends on "The Events Calendar" plugin.
Version: 0.9.4
Author: Simple
Author URI: http://simpl.pro
Text Domain: simple-events-calendar-widget
License: GPLv2 or later
*/

include_once( ABSPATH . 'wp-admin/includes/plugin.php' ); 
   
if (is_plugin_active('the-events-calendar/the-events-calendar.php')) {
	include_once( ABSPATH . '/wp-content/plugins/the-events-calendar/lib/widget-list.class.php' ); 
	class simple_events_listwidget_plugin extends TribeEventsListWidget {

		// constructor
		function simple_events_listwidget_plugin() {
			parent::WP_Widget(false, $name = __('Events List category widget', 'wp_widget_plugin') );
		}

		// widget form creation
		function form($instance) {

			if( $instance) {
			     $title = esc_attr($instance['title']);
			     $category = esc_attr($instance['category']); 
			} else {
			     $title = '';
			     $category = ''; 
			}
			?>

			<p>
				<label for="<?php echo $this->get_field_id('title'); ?>"><?php _e('Widget Title', 'wp_widget_plugin'); ?></label>
				<input class="widefat" id="<?php echo $this->get_field_id('title'); ?>" name="<?php echo $this->get_field_name('title'); ?>" type="text" value="<?php echo $title; ?>" />
			</p>
			<p>
				<label for="<?php echo $this->get_field_id('category'); ?>"><?php _e('Events List category', 'wp_widget_plugin'); ?></label>
				<select name="<?php echo $this->get_field_name('category'); ?>" id="<?php echo $this->get_field_id('category'); ?>" class="widefat">
				<?php
				$taxonomies = array( 
				    'tribe_events_cat'
				);
				$args = array(
				    'orderby'       => 'name', 
				    'order'         => 'ASC',
				    'hide_empty'    => true
				); 
				$taxonomies=get_terms($taxonomies,$output); 

				foreach ($taxonomies as $taxonomy) {
				echo '<option value="' . $taxonomy->term_id . '" id="cat' .  $taxonomy->term_id . '"', $category == $taxonomy->term_id ? ' selected="selected"' : '', '>', $taxonomy->name, '</option>';
				}
				?>
				</select>
			</p>

			<p>
				<label for="<?php echo $this->get_field_id( 'limit' ); ?>"><?php _e('Show:','tribe-events-calendar');?></label>
				<select id="<?php echo $this->get_field_id( 'limit' ); ?>" name="<?php echo $this->get_field_name( 'limit' ); ?>" class="widefat">
				<?php for ($i=1; $i<=10; $i++)
				{?>
				<option <?php if ( $i == $instance['limit'] ) {echo 'selected="selected"';}?> > <?php echo $i;?> </option>
				<?php } ?>
				</select>
			</p>
				<label for="<?php echo $this->get_field_id( 'no_upcoming_events' ); ?>"><?php _e('Show widget only if there are upcoming events:','tribe-events-calendar');?></label>
				<input id="<?php echo $this->get_field_id( 'no_upcoming_events' ); ?>" name="<?php echo $this->get_field_name( 'no_upcoming_events' ); ?>" type="checkbox" <?php checked( $instance['no_upcoming_events'], 1 ); ?> value="1" />
			<p>
			<?php
		}

		// widget update
		function update($new_instance, $old_instance) {
			$instance = $old_instance;
			// Fields
			$instance['title'] = strip_tags($new_instance['title']);
			$instance['category'] = strip_tags($new_instance['category']);
			$instance['limit'] = $new_instance['limit'];
			$instance['no_upcoming_events'] = $new_instance['no_upcoming_events'];
			return $instance;
		}

		// widget display
		function widget($args, $instance) {
			
			return $this->widget_output( $args, $instance );	

		}
		function widget_output( $args, $instance, $template_name='list-widget', $subfolder = 'widgets', $namespace = '/', $pluginPath = '', $template_name = 'widgets/list-widget' ) {
			global $wp_query, $tribe_ecp, $post;
			extract( $args, EXTR_SKIP );
			// The view expects all these $instance variables, which may not be set without pro
			$instance = wp_parse_args($instance, array(
				'limit' => 5,
				'title' => '',
			));
			extract( $instance, EXTR_SKIP );

			// temporarily unset the tribe bar params so they don't apply
			$hold_tribe_bar_args =  array();
			foreach ( $_REQUEST as $key => $value ) {
				if ( $value && strpos( $key, 'tribe-bar-' ) === 0 ) {
					$hold_tribe_bar_args[$key] = $value;
					unset( $_REQUEST[$key] );
				}
			}

			// extracting $instance provides $title, $limit
			$title = apply_filters('widget_title', $title );
			if (!function_exists('tribe_get_events')) return;
			$category = $instance['category'];
			if ( ! isset( $category ) || $category === '-1' ) {
				$category = 0;
			}

			if ( tribe_get_option( 'viewOption' ) == 'upcoming' ) {
				$event_url = tribe_get_listview_link( $category );
			} else {
				$event_url = tribe_get_gridview_link( $category );
			}

			if ( function_exists( 'tribe_get_events' ) ) {

				$args = array(
					'eventDisplay'   => 'upcoming',
					'posts_per_page' => $limit,
				);

				if ( ! empty( $category ) ) {
					$args['tax_query'] = array(
						array(
							'taxonomy'         => TribeEvents::TAXONOMY,
							'terms'            => $category,
							'field'            => 'ID',
							'include_children' => false
						)
					);
				}

				$posts    = tribe_get_events( apply_filters('tribe_events_list_widget_query_args',$args) );
			}

			//print_r($args);
			//print_r($posts);
		// If no posts, and the don't show if no posts checked, let's bail
		if (!$posts && $no_upcoming_events) return;

		echo $before_widget;
		do_action('tribe_events_before_list_widget');
		do_action('tribe_events_list_widget_before_the_title');

		echo ($title) ? $before_title . $title . $after_title : '';
		do_action('tribe_events_list_widget_after_the_title');

		// Include template file
		include TribeEventsTemplates::getTemplateHierarchy($template_name);
		do_action('tribe_events_after_list_widget');

		echo $after_widget;
		wp_reset_query();

		// Reinstate the tribe bar params
		if (!empty($hold_tribe_bar_args))
			foreach ($hold_tribe_bar_args as $key => $value)
				$_REQUEST[$key] = $value;
			
		}
	}

	// register widget
	add_action('widgets_init', create_function('', 'return register_widget("simple_events_listwidget_plugin");'));

}else{
	
	deactivate_plugins( __FILE__);
	deactivate_plugins('the-events-calendar/the-events-calendar.php');
	exit('The Events Calendar is not installed!');

}
?>
