<?php
/**
 * Add custom fields to widgets
 */
function graphene_add_widget_fields( $widget, $return, $instance ) {
 
    $instance['device_display'] = isset( $instance['device_display'] ) ? $instance['device_display'] : 'both';

    ?>
        <p>
        	<label for="<?php echo $widget->get_field_id( 'device_display' ); ?>">
                <?php _e( 'Display on:', 'graphene' ); ?>
            </label>
            <select id="<?php echo $widget->get_field_id( 'device_display' ); ?>" name="<?php echo $widget->get_field_name( 'device_display' ); ?>">
            	<option value="both" <?php selected( $instance['device_display'], 'both' );?>><?php _e( 'Desktop and mobile', 'graphene' ); ?></option>
            	<option value="desktop" <?php selected( $instance['device_display'], 'desktop' );?>><?php _e( 'Desktop only', 'graphene' ); ?></option>
            	<option value="mobile" <?php selected( $instance['device_display'], 'mobile' );?>><?php _e( 'Mobile only', 'graphene' ); ?></option>
            </select>
        </p>
    <?php
}
add_filter( 'in_widget_form', 'graphene_add_widget_fields', 10, 3 );


/**
 * Save custom fields in widgets
 */
function graphene_save_widget_fields( $instance, $new_instance ) {
 
    if ( isset( $new_instance['device_display'] ) && in_array( $new_instance['device_display'], array( 'both', 'desktop', 'mobile' ) ) ) {
        $instance['device_display'] = $new_instance['device_display'];
    } else {
    	$instance['device_display'] = 'both';
    }
 
    return $instance;
}
add_filter( 'widget_update_callback', 'graphene_save_widget_fields', 10, 2 );


/**
 * Modify widgets parameters
 */
function graphene_dynamic_sidebar_params( $params ){

	if ( is_admin() ) return $params;

	global $wp_registered_widgets;
	$current_widget_id = $params[0]['widget_id'];
	$widget_number = $params[1]['number'];
	$widget = $wp_registered_widgets[$current_widget_id]['callback'][0];

	if ( is_object( $widget ) && method_exists( $widget, 'get_settings' ) ) {
		$instance = $widget->get_settings();
		if ( isset( $instance[$widget_number]['device_display'] ) ) {
			if ( $instance[$widget_number]['device_display'] == 'desktop' ) $params[0]['before_widget'] = str_replace( 'class="', 'class="desktop-only ', $params[0]['before_widget'] );
			if ( $instance[$widget_number]['device_display'] == 'mobile' ) $params[0]['before_widget'] = str_replace( 'class="', 'class="mobile-only ', $params[0]['before_widget'] );
		}
	}
	
	return $params;
}
add_filter( 'dynamic_sidebar_params', 'graphene_dynamic_sidebar_params', 9 );