<?php
/**
 * Handler for Taxonomy Meta
 *
 * @author     ThemeFusion
 * @copyright  (c) Copyright by ThemeFusion
 * @link       http://theme-fusion.com
 * @package    Avada
 * @subpackage Core
 * @since      5.3
 */

// Do not allow directly accessing this file.
if ( ! defined( 'ABSPATH' ) ) {
	exit( 'Direct script access denied.' );
}

/**
 * Handle taxonomy meta.
 */
class Avada_Taxonomy_Meta {
	/**
	 * Holds meta box object
	 *
	 * @var object
	 * @access protected
	 */
	protected $fusion_meta_box;

	/**
	 * Holds meta box fields.
	 *
	 * @access protected
	 * @since 5.3
	 * @var array
	 */
	protected $meta_fields;

	/**
	 * Type of form; edit or new term.
	 *
	 * @access protected
	 * @since 5.3
	 * @var string
	 */
	protected $form_type;

	/**
	 * Options name.
	 *
	 * @static
	 * @access protected
	 * @var string
	 */
	protected static $options_name = 'fusion_taxonomy_options';

	/**
	 * Construct the object and init hooks
	 *
	 * @access public
	 * @since 5.3
	 * @param array $config Configuration data.
	 */
	public function __construct( $config ) {
		// Return if not admin.
		if ( ! is_admin() ) {
			return;
		}

		// Set config values.
		$this->fusion_meta_box = $config;

		// Add Actions.
		add_action( 'admin_init', array( $this, 'init_hooks' ) );

		// Add styles and scripts.
		add_action( 'admin_print_styles', array( $this, 'add_scripts_styles' ) );
	}

	/**
	 * Add Meta Boxes for post types.
	 *
	 * @access public
	 * @since 5.3
	 */
	public function init_hooks() {
		// Loop through array and init hooks.
		foreach ( $this->fusion_meta_box['screens'] as $screen ) {
			// add fields to edit form.
			add_action( $screen . '_edit_form',array( $this, 'render_edit_form' ) );
			// add fields to add new form.
			add_action( $screen . '_add_form_fields',array( $this, 'render_new_form' ) );
			// this saves the edit fields.
			add_action( 'edited_' . $screen, array( $this, 'save_data' ), 10, 2 );
			// this saves the add fields.
			add_action( 'created_' . $screen, array( $this, 'save_data' ), 10, 2 );
		}
	}

	/**
	 * Add styles and scripts.
	 *
	 * @access public
	 * @since 5.3
	 */
	public function add_scripts_styles() {
		$screen = get_current_screen();

		// Add resources on required screens only.
		if ( 'edit-tags' === $screen->base || 'term' === $screen->base ) {
			// Enqueu built-in script and style for color picker.
			wp_enqueue_style( 'wp-color-picker' );
			wp_enqueue_script( 'wp-color-picker' );

			if ( defined( 'FUSION_LIBRARY_URL' ) ) {
				wp_enqueue_script(
					'wp-color-picker-alpha',
					trailingslashit( FUSION_LIBRARY_URL ) . 'inc/redux/custom-fields/color_alpha/wp-color-picker-alpha.js',
					array( 'wp-color-picker' ),
					'1.2'
				);
			}

			// Enqueu built-in script and styles for media JavaScript APIs.
			wp_enqueue_media();

			$ver = Avada::get_theme_version();

			wp_enqueue_script(
				'avada-tax-meta-js',
				trailingslashit( Avada::$template_dir_url ) . 'assets/admin/js/avada-tax-meta.js',
				array( 'jquery' ),
				$ver,
				true
			);

			wp_enqueue_style(
				'avada-tax-meta-css',
				trailingslashit( Avada::$template_dir_url ) . 'assets/admin/css/avada-tax-meta.css',
				array(),
				$ver
			);

			if ( class_exists( 'Avada' ) ) {
				wp_enqueue_script(
					'select2-js',
					Avada::$template_dir_url . '/assets/admin/js/select2.min.js',
					array( 'jquery' ),
					'4.0.3'
				);
				wp_enqueue_style(
					'select2-css',
					Avada::$template_dir_url . '/assets/admin/css/select2.css',
					array(),
					'4.0.3',
					'all'
				);
			} // End if().
		} // End if().
	}

	/**
	 * Set type of the form.
	 *
	 * @access public
	 * @since 5.3
	 * @param string $type Type of the form.
	 */
	public function set_form_type( $type ) {
		$this->form_type = $type;
	}

	/**
	 * Callback function to show fields on term edit form.
	 *
	 * @access public
	 * @since 5.3
	 * @param mixed $term_id ID of current term.
	 */
	public function render_edit_form( $term_id ) {
		$this->set_form_type( 'edit' );
		$this->render_fields( $term_id );
	}

	/**
	 * Callback function to show fields on add new taxonomy term form.
	 *
	 * @access public
	 * @since 5.3
	 * @param mixed $term_id ID of current term.
	 */
	public function render_new_form( $term_id ) {
		$this->set_form_type( 'new' );
		$this->render_fields( $term_id );
	}

	/**
	 * Callback function to show fields in meta box.
	 *
	 * @access public
	 * @since 5.3
	 * @param mixed $term_id ID of current term.
	 */
	public function render_fields( $term_id ) {

		// Check for Object.
		$term_id = is_object( $term_id ) ? $term_id->term_id : $term_id;
		$options = get_term_meta( $term_id, self::$options_name, true );

		if ( 'edit' === $this->form_type ) {
			?>
			<table class="avada-tax-meta-table">
			<tr class="avada-tax-meta-spacer"><td colspan="2"><?php wp_nonce_field( basename( __FILE__ ), 'fusion_taxnonomy_meta_nonce' );?></td></tr>
			<?php
		} else {
			wp_nonce_field( basename( __FILE__ ), 'fusion_taxnonomy_meta_nonce' );
		}

		foreach ( $this->meta_fields as $field ) {
			$name     = $field['id'];
			// Field value.
			$meta     = isset( $options[ $name ] ) ? $options[ $name ] : '';
			$meta     = ( '' !== $meta ) ? $meta : ( ( isset( $field['default'] ) && 'color' !== $field['type'] ) ? $field['default'] : '');

			if ( 'image' !== $field['type'] ) {
				$meta = is_array( $meta ) ? array_map( 'esc_attr', $meta ) : esc_attr( $meta );
			}

			if ( 'edit' === $this->form_type ) {
				?>
				<tr class="form-field avada-tax-meta-field <?php echo esc_attr( $field['class'] ); ?>">
				<?php
			}

			// Call Separated methods for displaying each type of field.
			call_user_func( array( $this, 'render_field_' . $field['type'] ), $field, is_array( $meta ) ? $meta : stripslashes( $meta ) );
			if ( 'edit' === $this->form_type ) {
				?>
				</tr>
				<?php
			}
		}
		if ( 'edit' === $this->form_type ) {
			?>
			</table>
			<?php
		}
	}

	/**
	 * Save Data from Metabox
	 *
	 * @access public
	 * @since 5.3
	 * @param string $term_id ID of the current term.
	 */
	public function save_data( $term_id ) {
		$fields_data = array();

		// Return if inline save.
		if ( isset( $_REQUEST['action'] ) && 'inline-save-tax' === $_REQUEST['action'] ) {
			return $term_id;
		}

		// Check revision, nonce, current taxonomy type, support of current taxonomy type and permission.
		if ( ! isset( $term_id ) || ( ! check_admin_referer( basename( __FILE__ ), 'fusion_taxnonomy_meta_nonce' ) ) || ( ! isset( $_POST['taxonomy'] ) ) || ( ! in_array( wp_unslash( $_POST['taxonomy'] ), $this->fusion_meta_box['screens'] ) ) || ( ! current_user_can( 'manage_categories' ) ) ) {
			return $term_id;
		}

		foreach ( $this->meta_fields as $field ) {

			$name     = $field['id'];
			$type     = $field['type'];
			$new      = isset( $_POST[ $name ] ) ? sanitize_text_field( wp_unslash( $_POST[ $name ] ) ) : '';

			// Save field data in array.
			$fields_data[ $name  ] = $new;
		}

		$this->save_fields_data( $fields_data, $term_id );

		// Reset all caches except demo_data, fb_pages and patcher_messages.
		avada_reset_all_caches(
			array(
				'demo_data'        => false,
				'fb_pages'         => false,
				'patcher_messages' => false,
			)
		);
	}

	/**
	 * Common function for saving fields.
	 *
	 * @access public
	 * @since 5.3
	 * @param array  $fields_data   data of all fields.
	 * @param string $term_id       ID of current temr.
	 */
	public function save_fields_data( $fields_data, $term_id ) {

		delete_term_meta( $term_id, self::$options_name );

		update_term_meta( $term_id, self::$options_name, $fields_data );
	}

	/**
	 *  Add Text Field to meta box
	 *
	 * @access public
	 * @since 5.3
	 * @param string $id   ID of the field.
	 * @param array  $args field aruguments.
	 */
	public function text( $id, $args ) {

		$field = array(
			'type'    => 'text',
			'id'      => $id,
			'default' => '',
			'class'   => '',
			'desc'    => '',
			'style'   => '',
			'name'    => 'Text Field',
		);

		$field = array_merge( $field, $args );

		$this->meta_fields[] = $field;
	}

	/**
	 *  Add Select Field to meta box
	 *
	 * @access public
	 * @since 5.3
	 * @param string $id      ID of the field.
	 * @param array  $options Array of available options.
	 * @param array  $args    Field aruguments.
	 */
	public function select( $id, $options, $args ) {
		$field = array(
			'type'    => 'select',
			'id'      => $id,
			'default' => '',
			'class'   => '',
			'desc'    => '',
			'style'   => '',
			'name'    => 'Select Field',
			'options' => $options,
		);

		$field = array_merge( $field, $args );

		$this->meta_fields[] = $field;
	}

	/**
	 *  Add Radio Button Field to meta box
	 *
	 * @access public
	 * @since 5.3
	 * @param string $id      ID of the field.
	 * @param array  $options Array of available options.
	 * @param array  $args    Field aruguments.
	 */
	public function buttonset( $id, $options, $args ) {
		$field = array(
			'type'    => 'buttonset',
			'id'      => $id,
			'default' => '',
			'class'   => '',
			'desc'    => '',
			'style'   => '',
			'name'    => 'Radio Field',
			'options' => $options,
		);

		$field = array_merge( $field, $args );

		$this->meta_fields[] = $field;
	}

	/**
	 *  Add Color Picket Field to meta box
	 *
	 * @access public
	 * @since 5.3
	 * @param string $id   ID of the field.
	 * @param array  $args Field aruguments.
	 */
	public function colorpicker( $id, $args ) {

		$field = array(
			'type'    => 'color',
			'id'      => $id,
			'default' => '',
			'class'   => '',
			'desc'    => '',
			'style'   => '',
			'name'    => 'ColorPicker Field',
		);

		$field = array_merge( $field, $args );

		$this->meta_fields[] = $field;
	}

	/**
	 *  Add Color Picket Field to meta box
	 *
	 * @access public
	 * @since 5.3
	 * @param string $id   ID of the field.
	 * @param array  $args Field aruguments.
	 */
	public function image( $id, $args ) {

		$field = array(
			'type'    => 'image',
			'id'      => $id,
			'default' => '',
			'class'   => '',
			'desc'    => '',
			'style'   => '',
			'url'     => '',
			'name'    => 'Image Field',
		);

		$field = array_merge( $field, $args );

		$this->meta_fields[] = $field;
	}

	/**
	 *  Add header to meta box
	 *
	 * @access public
	 * @since 5.3
	 * @param string $id   ID of the field.
	 * @param array  $args Field aruguments.
	 */
	public function header( $id, $args ) {

		$field = array(
			'type'    => 'header',
			'id'      => $id,
			'value'   => '',
			'style'   => '',
			'default' => '',
		);

		$field = array_merge( $field, $args );

		$this->meta_fields[] = $field;
	}

	/**
	 * Render dependency markup.
	 *
	 * @since 5.3
	 * @param array $dependency dependence options.
	 * @return string $data_dependence markup
	 */
	private function render_dependency( $dependency = array() ) {

		// Disable dependencies if 'dependencies_status' is set to 0.
		if ( '0' === Avada()->settings->get( 'dependencies_status' ) ) {
			return '';
		}

		$data_dependency = '';
		if ( 0 < count( $dependency ) ) {
			$data_dependency .= '<div class="avada-tax-dependency">';
			foreach ( $dependency as $dependence ) {
				$data_dependency .= '<span class="hidden" data-value="' . $dependence['value'] . '" data-field="' . $dependence['field'] . '" data-comparison="' . $dependence['comparison'] . '"></span>';
			}
			$data_dependency .= '</div>';
		}
		return $data_dependency;
	}

	/**
	 * Show Field Text.
	 *
	 * @access public
	 * @since 5.3
	 * @param array  $field Field data.
	 * @param string $meta  Meta data.
	 */
	public function render_field_text( $field, $meta ) {
		$this->render_field_start( $field, $meta );
		?>
		<input type="text" class="avada-tax-text" name="<?php echo esc_attr( $field['id'] ); ?>" id="<?php echo esc_attr( $field['id'] ); ?>" value="<?php echo esc_attr( $meta ); ?>" style="<?php echo esc_attr( $field['style'] ); ?>" size='30' />
		<?php
		$this->render_field_end( $field, $meta );
	}

	/**
	 * Show Field Select.
	 *
	 * @access public
	 * @since 5.3
	 * @param array  $field Field data.
	 * @param string $meta  Meta data.
	 */
	public function render_field_select( $field, $meta ) {

		if ( ! is_array( $meta ) ) {
			$meta = (array) $meta;
		}

		$this->render_field_start( $field, $meta );
		?>
		<select class="avada-tax-select" style="<?php echo esc_attr( $field['style'] ); ?>" name="<?php echo esc_attr( $field['id'] ); ?>" id="<?php echo esc_attr( $field['id'] ); ?>">
		<?php
		foreach ( $field['options'] as $key => $value ) :
			?>
			<option value="<?php echo esc_attr( $key );?>" <?php echo selected( in_array( $key, $meta ), true, false ); ?>><?php echo esc_attr( $value ); ?></option>
		<?php endforeach; ?>
		</select>
		<?php

		$this->render_field_end( $field, $meta );
	}

	/**
	 * Show Field Select.
	 *
	 * @access public
	 * @since 5.3
	 * @param array  $field Field data.
	 * @param string $meta  Meta data.
	 */
	public function render_field_buttonset( $field, $meta ) {

		if ( empty( $meta ) ) {
			$meta = 'default';
		}

		$this->render_field_start( $field, $meta );

		?>
		<div class="avada-tax-buttonset avada-buttonset">
			<div class="avada-tax-button-set ui-buttonset">
				<input type="hidden" id="<?php echo esc_attr( $field['id'] ); ?>" name="<?php echo esc_attr( $field['id'] ); ?>" value="<?php echo esc_attr( $meta ); ?>" class="button-set-value" />
				<?php foreach ( $field['options'] as $key => $option ) : ?>
					<?php $selected = ( $key == $meta ) ? ' ui-state-active' : ''; ?>
					<a href="#" class="ui-button buttonset-item<?php echo esc_attr( $selected ); ?>" data-value="<?php echo esc_attr( $key ); ?>"><?php echo esc_attr( $option ); ?></a>
				<?php endforeach; ?>
			</div>
		</div>
		<?php
		$this->render_field_end( $field, $meta );
	}

	/**
	 * Show Color Picker.
	 *
	 * @access public
	 * @since 5.3
	 * @param array  $field Field data.
	 * @param string $meta  Meta data.
	 */
	public function render_field_color( $field, $meta ) {

		$this->render_field_start( $field, $meta );
		?>
		<input class="avada-tax-color color-picker" data-alpha="true" type="text" name="<?php echo esc_attr( $field['id'] ); ?>" value="<?php echo esc_attr( $meta );?>" data-default="<?php echo esc_attr( $field['default'] ); ?>" />
		<?php
		$this->render_field_end( $field, $meta );
	}

	/**
	 * Show Image Field.
	 *
	 * @access public
	 * @since 5.3
	 * @param array  $field Field data.
	 * @param string $meta  Meta data.
	 */
	public function render_field_image( $field, $meta ) {
		$this->render_field_start( $field, $meta );

		$name          = esc_attr( $field['id'] );
		$has_image     = empty( $meta ) ? false : true;
		$preview_style = 'max-width:100%';
		?>
		<span class="avada-tax-img-field <?php echo esc_attr( $name );?>">
		<input type="text" name="<?php echo esc_attr( $name );?>" value="<?php echo esc_attr( $meta ); ?>" id="<?php echo esc_attr( $name );?>" class="avada-tax-image-url" />
		<?php
		if ( $has_image ) {
		?>
			<input class="button  avada-tax-image-upload-clear" value="<?php esc_attr_e( 'Remove Image', 'Avada' ); ?>"  type="button" />
			<input class="button avada-tax-image-upload hidden" value="<?php esc_attr_e( 'Upload Image', 'Avada' ); ?>" type="button" />
		<?php
		} else {
		?>
			<input class="button  avada-tax-image-upload-clear hidden" value="<?php esc_attr_e( 'Remove Image', 'Avada' ); ?>" type="button" />
			<input class="button avada-tax-image-upload" value="<?php esc_attr_e( 'Upload Image', 'Avada' ); ?>" type="button" />
		<?php
		}
		?>
		</span>
		<?php
		$this->render_field_end( $field, $meta );
	}

	/**
	 * Show header.
	 *
	 * @access public
	 * @since 5.3
	 * @param array $field Field data.
	 * @param array $meta  Meta data.
	 */
	public function render_field_header( $field, $meta ) {
		if ( 'edit' === $this->form_type ) {
		?>
			<td colspan="2">
				<div class="avada-tax-meta-header">
					<h3 style="<?php echo esc_attr( $field['style'] ); ?>"> <?php echo esc_attr( $field['value'] );?></h3>
					<span class="avada-tax-meta-handle toggle-indicator"></span>
				</div>
			</td>
		<?php
		} else if ( 'new' === $this->form_type ) {
		?>
			<div class="form-field avada-tax-meta-field avada-tax-header">
			<h3 style="<?php echo esc_attr( $field['style'] ); ?>"> <?php echo esc_attr( $field['value'] );?></h3>
			<span class="avada-tax-meta-handle toggle-indicator"></span>
			</div>
		<?php
		}

	}

	/**
	 * Begin Field.
	 *
	 * @access public
	 * @since 5.3
	 * @param array  $field Field data.
	 * @param string $meta  Meta data.
	 */
	public function render_field_start( $field, $meta ) {
		if ( 'edit' == $this->form_type ) {
		?>
			<th scope="row">
		<?php
		} else {
		?>
			<div class="form-field avada-tax-meta-field <?php echo esc_attr( $field['class'] );?>" >
		<?php
		}

		if ( '' !== $field['name'] || false !== $field['name'] ) {
		?>
			<label> <?php echo esc_attr( $field['name'] ); ?></label>
		<?php
		}

		if ( isset( $field['desc'] ) && '' !== $field['desc'] && 'new' === $this->form_type ) {
		?>
			<p class='description'><?php echo $field['desc'] ; // // WPCS: XSS ok.?>
		<?php
		}

		if ( isset( $field['desc'] ) && '' !== $field['desc'] && 'edit' === $this->form_type ) {
		?>
			<p class='description'><?php echo $field['desc'] ; // // WPCS: XSS ok.?>
		<?php
		}

		if ( isset( $field['dependency'] ) && is_array( $field['dependency'] ) ) {
			echo $this->render_dependency( $field['dependency'] ); // WPCS: XSS ok.
		}

		if ( 'color' === $field['type'] ) {
			?>
			<span class="tax-meta-default-reset"><a href="#" id="default-<?php echo esc_attr( $field['id'] );?>" class="avada-range-default avada-hide-from-atts" type="radio" name="<?php echo esc_attr( $field['id'] );?>" value="" data-default="<?php echo esc_attr( $field['default'] );?>"><?php echo esc_attr( 'Reset to default.', 'Avada' ); ?></a><span><?php echo esc_attr( 'Using default value.', 'Avada' ); ?></span></span>
			<?php
		}
		?>
		</p>
		<?php

		if ( 'edit' === $this->form_type ) {
			?>
			</th><td>
			<?php
		}
	}

	/**
	 * End Field.
	 *
	 * @access public
	 * @since 5.3
	 * @param array  $field Field data.
	 * @param string $meta  Meta data.
	 */
	public function render_field_end( $field, $meta = null ) {
		if ( 'edit' === $this->form_type ) {
			?>
			</td>
			<?php
		} else {
			?>
			</div>
			<?php
		}
	}
}
