<?php
/**
 * A widget to render a selected post of the custom post type "Person" in the selected template.
 */

class TestimonialSlideshow extends WP_Widget {

	/**
	 * Spawn a new instance of the widget.
	 */
	function TestimonialSlideshow() {
		// Instantiate the parent object
		parent::__construct(
			'testimonial_slideshow_widget', // Base ID
			__( 'Testimonial Slideshow', 'orma-responsive' ), // Name
			array(
				'description' => __( 'A slideshow of alumni/student testimonials.', 'orma-responsive' ),
				'panels_groups' => array('orma'),
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

		$query_args = array(
			'post_type' => 'person',
			'post__in' => $instance['persons']
		);
		$query = new WP_Query( $query_args );

		echo '<div class="row">';
		echo '<ul data-orbit="" class="example-orbit-content">';

		while( $query->have_posts() ) {
			$query->the_post();

			$profile_picture = get_field( 'profile_picture' );
			$occupation = get_field( 'occupation' );
			?>

				<li data-orbit-slide="testimonial-<?php echo $query->current_post; ?>">
					<blockquote class="row">
						<?php if( !empty($profile_picture) ): ?>
							<img src="<?php echo $profile_picture['url']; ?>" alt="<?php echo $profile_picture['alt']; ?>" class="medium-4 columns" />
						<?php endif; ?>
						<div class="medium-8 columns">
							<p><?php the_field( 'testimonial' ); ?></p>
							<h5><?php the_title(); ?></h5>
							<h6><?php if (!empty($occupation)) { echo $occupation .', ';}; the_field( 'status' ); ?></h6>
						</div>
					</blockquote>
				</li>

			<?php

		}
		echo '</ul></div>';

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
		$instance['persons'][0] = $new_instance['person-1'];
		$instance['persons'][1] = $new_instance['person-2'];
		$instance['persons'][2] = $new_instance['person-3'];
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

		// Query all available alumni and students for the select lists.
		$args = array(
			'post_type' => 'person',
			'tax_query' => array(
				array(
					'taxonomy'     => 'orma-person',
					'field' => 'slug',
					'terms' => array('student', 'alumni'),
				)
			)
		);
		$persons = get_posts( $args );

		?>
		<p>
			<label for="<?php echo $this->get_field_id('person-1'); ?>"><?php _e('First Person:'); ?></label>
			<select class="widefat" id="<?php echo $this->get_field_id('person-id-1'); ?>" name="<?php echo $this->get_field_name('person-1'); ?>" type="text">
				<?php
				// List all persons and mark the currently selected one.
				foreach ($persons as $person) {
					echo '<option value="'. $person->ID .'"'. selected($instance['persons'][0], $person->ID) .'>'. $person->post_title .'</option>';
				}
				?>
			</select>

			<label for="<?php echo $this->get_field_id('person-2'); ?>"><?php _e('Second Person:'); ?></label>
			<select class="widefat" id="<?php echo $this->get_field_id('person-id-2'); ?>" name="<?php echo $this->get_field_name('person-2'); ?>" type="text">
				<?php
				// List all persons and mark the currently selected one.
				foreach ($persons as $person) {
					echo '<option value="'. $person->ID .'"'. selected($instance['persons'][1], $person->ID) .'>'. $person->post_title .'</option>';
				}
				?>
			</select>

			<label for="<?php echo $this->get_field_id('person-3'); ?>"><?php _e('Third Person:'); ?></label>
			<select class="widefat" id="<?php echo $this->get_field_id('person-id-3'); ?>" name="<?php echo $this->get_field_name('person-3'); ?>" type="text">
				<?php
				// List all persons and mark the currently selected one.
				foreach ($persons as $person) {
					echo '<option value="'. $person->ID .'"'. selected($instance['persons'][2], $person->ID) .'>'. $person->post_title .'</option>';
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
function testimonial_slideshow_register_widgets() {
	register_widget( 'TestimonialSlideshow' );
}

add_action( 'widgets_init', 'testimonial_slideshow_register_widgets' );
