<?php
/**
 * A widget to render a selected post of the custom post type "Person" in the selected template.
 */

class PersonListWidget extends WP_Widget {

	/**
	 * Spawn a new instance of the widget.
	 */
	function PersonListWidget() {
		// Instantiate the parent object
		parent::__construct(
			'personlist_widget', // Base ID
			__( 'Person List', 'orma-responsive' ), // Name
			array(
					'description' => __( 'A list of persons that belong to a given category.', 'orma-responsive' ),
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
		$term = $instance['person_group'];

		$query_args = array(
			'post_type' => 'person',
			'tax_query' => array(
				array(
					'taxonomy' => 'orma-person',
					'terms'    => $term,
				),
			),
		);
		$query = new WP_Query( $query_args );

		// Start a counter to generate odd-even classes.

		while( $query->have_posts() ) {
			//$counter = $query->current_post;
			$query->the_post();
			include( locate_template( 'templates/person-list.php') );
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
		$instance['person_group'] = $new_instance['person_group'];
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
		$selected_term = $instance['person_group'];

		// Query all available person categories for the select lists.
		$args = array(
			'taxonomy'     => 'orma-person',
			'show_count'   => 1,
		);
		$terms = get_categories( $args );

		?>
		<p>
			<label for="<?php echo $this->get_field_id('person_group'); ?>"><?php _e('Person Group:'); ?></label>
			<select class="widefat" id="<?php echo $this->get_field_id('person-id'); ?>" name="<?php echo $this->get_field_name('person_group'); ?>" type="text">
				<?php
					// List all persons and mark the currently selected one.
					foreach ($terms as $term) {
						echo '<option value="'. $term->term_id .'"'. selected($selected_term, $term->term_id) .'>'. $term->name .'</option>';
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
function personlistwidget_register_widgets() {
	register_widget( 'PersonListWidget' );
}

add_action( 'widgets_init', 'personlistwidget_register_widgets' );



/**
 * Shortcut handler for person listings.
 * @param $atts
 *
 * @return string
 */
function personlist_shortcode( $atts ) {
	$term_obj = get_term_by('slug', $atts['person_group'], 'orma-person');
	$atts['person_group'] = $term_obj->term_id;
	$a = shortcode_atts( array(
		// Defaults to team members.
		'person_group' => 5,
	), $atts );

	ob_start();
	the_widget( 'PersonListWidget', $a );
	$output = ob_get_contents();
	ob_end_clean();
	return $output;
}
add_shortcode( 'personlist', 'personlist_shortcode' );




// init process for registering our button
add_action('init', 'personlist_shortcode_button_init');
function personlist_shortcode_button_init() {

	//Abort early if the user will never see TinyMCE
	if ( ! current_user_can('edit_posts') && ! current_user_can('edit_pages') && get_user_option('rich_editing') == 'true')
		return;

	//Add a callback to regiser our tinymce plugin   
	add_filter("mce_external_plugins", "personlist_register_tinymce_plugin");

	// Add a callback to add our button to the TinyMCE toolbar
	add_filter('mce_buttons', 'personlist_add_tinymce_button');
}


//This callback registers our plug-in
function personlist_register_tinymce_plugin($plugin_array) {
	$plugin_array['personlist_button'] = get_stylesheet_directory_uri() . '/js/custom/PersonListWidget.shortcodeeditor.js';
	return $plugin_array;
}

//This callback adds our button to the toolbar
function personlist_add_tinymce_button($buttons) {
	//Add the button ID to the $button array
	$buttons[] = "personlist_button";
	return $buttons;
}



foreach ( array('post.php','post-new.php') as $hook ) {
	add_action( "admin_head-$hook", 'personlist_variables' );
}

/**
 * Localize Script
 */
function personlist_variables() {
	// Query all available person categories for the select lists.
	$args = array(
		'taxonomy'     => 'orma-person',
		'show_count'   => 1,
	);
	$terms = get_categories( $args );
	$js_terms = array();
	foreach ( $terms as $term ) {
		$js_terms[] = array(
			'text' => $term->name,
			'value' => $term->slug,
		);
	}
	$js_terms = json_encode($js_terms);
	?>
	<!-- TinyMCE Shortcode Plugin -->
	<script type='text/javascript'>
		var personlist_button = {
			'terms': '<?php echo $js_terms; ?>'
		};
	</script>
	<!-- TinyMCE Shortcode Plugin -->
	<?php
}
