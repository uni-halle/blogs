<?php
/**
 * A widget to render a selected post of the custom post type "Person" in the selected template.
 */

class PersonBoxWidget extends WP_Widget {

	/**
	 * Spawn a new instance of the widget.
	 */
	function PersonBoxWidget() {
		// Instantiate the parent object
		parent::__construct(
			'personbox_widget', // Base ID
			__( 'Person Box', 'orma-responsive' ), // Name
			array(
					'description' => __( 'A box to display persons in different formats.', 'orma-responsive' ),
					'panels_groups' => array('orma')
				) // Args
		);
	}

	/**
	 * Generate and render the widget content.
	 * @param array $args
	 * @param array $instance
	 */
	function widget( $args, $instance ) {
		extract( $args );
		$person_id  = $instance['person-id'];
		$template   = $instance['template'];

		$query_args = array(
			'post_type' => 'person',
			'p' => $person_id,
		);
		$query = new WP_Query( $query_args );

		while( $query->have_posts() ) {
			$query->the_post();
			locate_template( $template, true, false );
		}
	}

	/**
	 * Update the widget content when saving.
	 *
	 * @param array $new_instance
	 * @param array $old_instance
	 *
	 * @return array $instance
	 */
	function update( $new_instance, $old_instance ) {
		// Save widget options
		$instance = $old_instance;
		$instance['person-id'] = $new_instance['person-id'];
		$instance['template'] = $new_instance['template'];
		return $instance;
	}

	/**
	 * Generate the form to input widget content.
	 *
	 * @param array $instance
	 * @return mixed
	 */
	function form( $instance ) {
		// Output admin widget options form
		$person_id  = $instance['person-id'];
		$template   = $instance['template'];

		// Query all available persons and page templates for the select lists.
		$query_args = array(
			'post_type' => 'person',
			//@TODO: Possibly add more parameters for ordering and maybe a differentiation between person types?
		);
		$persons = get_posts( $query_args );
		$templates = get_page_templates();

		?>
		<p>
			<label for="<?php echo $this->get_field_id('person-id'); ?>"><?php _e('Person:'); ?></label>
			<select class="widefat" id="<?php echo $this->get_field_id('person-id'); ?>" name="<?php echo $this->get_field_name('person-id'); ?>" type="text">
				<?php
					// List all persons and mark the currently selected one.
					foreach ($persons as $person) {
						echo '<option value="'. $person->ID .'"'. selected($person_id, $person->ID) .'>'. $person->post_title .'</option>';
					}
				?>
			</select>
		</p>
		<p>
			<label for="<?php echo $this->get_field_id('template'); ?>"><?php _e('Available Output Templates:'); ?></label>
			<select class="widefat" id="<?php echo $this->get_field_id('template'); ?>" name="<?php echo $this->get_field_name('template'); ?>" type="text">
				<?php
					// List all templates and mark the currently selected one.
					foreach ($templates as $key => $value) {
						echo '<option value="'. $value .'"'. selected($template, $value) .'>'. $key .'</option>';
					}
				?>
			</select>
		</p>
		<?php
	}
}

/**
 * Register the widget with WordPress.
 */
function personboxwidget_register_widgets() {
	register_widget( 'PersonBoxWidget' );
}

add_action( 'widgets_init', 'personboxwidget_register_widgets' );
