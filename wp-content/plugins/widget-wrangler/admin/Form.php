<?php

namespace WidgetWrangler;

/**
 * Class Form
 *
 * @package WidgetWrangler
 */
class Form {
    
	/**
	 * Necessary argument defaults for a working form
	 *
	 * @var array
	 */
	public $default_form_args = array(
		'id' => '',
		'class' => array(),
		'method' => 'POST',
		'action' => '',
		'attributes' => array(),
		'style' => 'flat',
		'field_prefix' => '',
        'fields' => array(),
	);

	/**
	 * Form settings (arguments). Combination of arguments provided to the
	 * constructor and the default arguments
	 *
	 * @var array
	 */
	public $form_args = array();

	/**
	 * Form Styles
	 *
	 * Array of keyed set of callbacks for determining the wrapping form and
	 * field styles.
	 *
	 * Ex:
	 * ---------
	 *  array(
	 *		'my_form_style' => array(
	 *          'form_open'     => 'my_form_style_form_open_callback',
	 *          'form_close'    => 'my_form_style_form_close_callback',
	 *          'field_wrapper' => 'my_form_style_field_wrapper_callback',
	 *      )
	 *  )
	 *
	 * @see WidgetWranglerForm::form_open_flat()
	 * @see WidgetWranglerForm::form_close_flat()
	 * @see WidgetWranglerForm::field_wrapper_flat()
	 *
	 * @var array
	 */
	public $form_styles = array();


	/**
	 * Field Types
	 *
	 * Array of keyed set of callbacks for creating HTML output for a form field
	 *
	 * Ex:
	 * ---------
	 *
	 * array(
	 *      'my_field_type' => 'my_field_type_callback'
	 * )
	 *
	 * function my_field_type_callback( $field = array() ) {}
	 *
	 * @see WidgetWranglerForm::template_textarea()
	 *
	 * @var array
	 */
	public $field_types = array();

	/**
	 *  Necessary argument defaults for a working field
	 *
	 * @var array
	 */
	public $default_field_args = array(
		'title' => '',
		'description' => '',
		'help' => '',
		'type' => 'text',
		'class' => array(),
		'value' => '',
		'name' => '',
		'label_first' => TRUE,
		'access' => TRUE,

		// [top-lvl][mid-lvl][bottom-lvl]
		'name_prefix' => '',

		// additional special attributes like size, rows, cols, etc
		'attributes' => array(),

		// only for some field types
		// options = array(),

		# generated automatically
		#'form_name' => '',
		#'id' => '',
	);

	/**
     * Array of field definitions.
     *
	 * @var array
	 */
	public $fields = array();

	/**
	 * WidgetWranglerForm constructor.
	 *
	 * @param array $form_args
	 */
	function __construct( $form_args = array() ){
		$this->form_args = array_replace( $this->default_form_args, $form_args );
		$this->fields = $this->form_args['fields'];
		$this->form_styles = $this->default_form_styles();
		$this->field_types = $this->default_field_types();
	}

	/**
	 * Core form styles
	 *
	 * @return array
	 */
	function default_form_styles(){
		return array(
			'flat' => array(
				'form_open' => array( $this, 'form_open_flat' ),
				'form_close' => array( $this, 'form_close_flat' ),
				'field_wrapper' => array( $this, 'field_wrapper_flat' ),
			),
			'box' => array(
				'form_open' => array( $this, 'form_open_flat' ),
				'form_close' => array( $this, 'form_close_flat' ),
				'field_wrapper' => array( $this, 'field_wrapper_box' ),
			),
			'inline' => array(
				'form_open' => array( $this, 'form_open_flat' ),
				'form_close' => array( $this, 'form_close_flat' ),
				'field_wrapper' => array( $this, 'field_wrapper_inline' ),
			),
			'table' => array(
				'form_open' => array( $this, 'form_open_table' ),
				'form_close' => array( $this, 'form_close_table' ),
				'field_wrapper' => array( $this, 'field_wrapper_table' ),
			),
		);
	}

	/**
	 * Core field types
	 *
	 * @return array
	 */
	function default_field_types(){
		return array(
			'text' => array( $this, 'template_input' ),
			'hidden' => array( $this, 'template_input' ),
			'number' => array( $this, 'template_input' ),
			'email' => array( $this, 'template_input' ),
			'submit' => array( $this, 'template_input' ),
			'button' => array( $this, 'template_input' ),
			'textarea' => array( $this, 'template_textarea' ),
			'checkbox' => array( $this, 'template_checkbox' ),
			'checkboxes' => array( $this, 'template_checkboxes' ),
			'select' => array( $this, 'template_select' ),
			'item_list' => array( $this, 'template_item_list' ),
            'markup' => array( $this, 'template_markup' ),
		);
	}

	/**
	 * Retrieve the current form_style array
	 *
	 * @return array
	 */
	function get_form_style(){
		// default to flat style
		$style = $this->form_styles['flat'];

		if ( isset( $this->form_styles[ $this->form_args['style'] ] ) ){
			$style = $this->form_styles[ $this->form_args['style'] ];
		}

		return $style;
	}

	/**
	 * Merge default and set attributes for the html form element
	 *
	 * @return array
	 */
	function get_form_attributes(){
		$atts_keys = array( 'id', 'action', 'method', 'class' );
		$attributes = array();

		foreach( $atts_keys as $key ){
			if ( !empty( $this->form_args[ $key ] ) ) {
				$attributes[ $key ] = $this->form_args[ $key ];
			}
		}

		if ( !empty( $this->form_args['attributes'] ) ) {
			$attributes = array_replace( $attributes, $this->form_args['attributes'] );
		}

		if ( !empty( $attributes['class'] ) ) {
			$attributes['class'] = implode( ' ', $attributes['class'] );
		}

		return $attributes;
	}

	/**
	 * Opening form html and form style html.
	 *
	 * @return string
	 */
	function open(){
		$output = $this->form_open();

		$style = $this->get_form_style();

		if ( is_callable( $style['form_open'] ) ){
			$output.= call_user_func( $style['form_open'] );
		}

		return $output;
	}

	/**
	 * Closing form html and form style html.
	 *
	 * @return string
	 */
	function close(){
		$output = '';

		$style = $this->get_form_style();

		if ( is_callable( $style['form_close'] ) ){
			$output.= call_user_func( $style['form_close'] );
		}

		$output.= $this->form_close();

		return $output;
	}

	/**
     * Form html open.
     *
	 * @return string
	 */
	function form_open() {
	    return '<form ' . $this->attributes( $this->get_form_attributes() ). '>';
    }

	/**
     * Form html close.
     *
	 * @return string
	 */
    function form_close() {
	    return '</form>';
    }

	/**
     * Render an entire form that is instantiated with fields.
     *
	 * @return string
	 */
	function render() {
	    $output = $this->open();

	    foreach( $this->fields as $name => $field ) {
	        if ( empty($field['name']) ) {
	            $field['name'] = $name;
            }
	        $output.= $this->render_field($field);
        }

        $output.= $this->close();

	    return $output;
    }

	/**
	 * Execute the filters and methods that render a field
	 *
	 * @param $field
	 *
	 * @return string
	 */
	function render_field( $field ){
		$field = $this->make_field( $field );
		$field_html = '';

		// do not render fields users do not have access to.
		if ( !$field['access'] ) {
		    return $field_html;
        }

		// template the field
		if ( isset( $this->field_types[ $field['type'] ] ) && is_callable( $this->field_types[ $field['type'] ] ) ){
			ob_start();
			call_user_func( $this->field_types[ $field['type'] ], $field );
			$field_html = ob_get_clean();
		}

		// do not wrap very simple fields
		if ( empty( $field['title'] ) && empty( $field['description'] ) && empty( $field['help'] ) ) {
			return $field_html;
		}

		// template the wrapper
		$wrapper_html = $field_html;
		$style = $this->get_form_style();

		if ( is_callable( $style['field_wrapper'] ) ){
			ob_start();
			call_user_func( $style['field_wrapper'], $field, $field_html );
			$wrapper_html = ob_get_clean();
		}

		return $wrapper_html;
	}

	/**
	 * Preprocess field
	 *
	 * @param array $args
	 *
	 * @return array
	 */
	function make_field( $args = array() ){
		$field = array_replace( $this->default_field_args, $args );
		$field['name'] = sanitize_title( $args['name'] );

		if ($field['type'] == 'checkbox') {
			$field['label_first'] = FALSE;
        }

		// build the field's entire form name
		$field['form_name'] = '';
		if ( !empty( $this->form_args['field_prefix'] ) ){
			$field['form_name'].= $this->form_args['field_prefix'];
		}
		if ( !empty( $field['name_prefix'] ) ) {
			$field['form_name'].= $field['name_prefix'];
		}

		if ( !empty( $field['form_name'] ) ) {
			$field['form_name'].= '[' . $field['name'] . ']';
		}
		else {
			$field['form_name'].= $field['name'];
        }

		// gather field classes
		if ( !is_array( $field['class'] ) ){
			$field['class'] = array( $field['class'] );
		}
		$field['class'][] = 'ww-field';
		$field['class'][] = 'ww-field-' . $this->form_args['style'];
		$field['class'][] = 'ww-field-type-' . $field['type'];
		$field['class'] = implode( ' ', $field['class'] );

		if ( empty( $field['id'] ) ) {
			$field['id'] = 'edit--' . sanitize_title( $field['form_name'] );
		}
		return $field;
	}

	/**
	 * Simple conversion of an array to tml attributes string
	 *
	 * @param array $array
	 * @param string $prefix
	 *
	 * @return string
	 */
	function attributes( $array = array(), $prefix = '' ){
		$html = '';

		foreach( $array as $key => $value ){
			if ( !empty( $value ) ) {
				$value = esc_attr( $value );
				$html .= " {$prefix}{$key}='{$value}'";
			}
		}

		return $html;
	}

	/**
	 * Single checkbox field has a hidden predecessor to provide a default value
	 *
	 * @param $field
	 */
	function template_checkbox( $field ){
	    $hidden = array_replace( $field, array(
			'type' => 'hidden',
			'value' => 0,
			'id' => $field['id'] . '--hidden',
			'attributes' => array(),
			'class' => 'ww-field-hidden',
		));
		$this->template_input( $hidden );

		if ( !empty( $field['value'] ) ) {
			$field['attributes']['checked'] = 'checked';
		}
		$field['value'] = 'on';
		$this->template_input( $field );
	}

	/**
	 * Generic input field
	 *
	 * @param $field
	 */
	function template_input( $field ) {
		?>
        <input type="<?php echo esc_attr( $field['type'] ) ?>"
               name="<?php echo esc_attr( $field['form_name'] ); ?>"
               id="<?php echo esc_attr( $field['id'] ); ?>"
               class="<?php echo esc_attr( $field['class'] ); ?>"
               value="<?php echo esc_attr( $field['value'] ); ?>"
			<?php echo $this->attributes( $field['attributes'] ); ?>
        >
		<?php
	}

	/**
	 * Textarea
	 *
	 * @param $field
	 */
	function template_textarea( $field ) {
		?>
        <textarea name="<?php echo esc_attr( $field['form_name'] ); ?>"
                  id="<?php echo esc_attr( $field['id'] ); ?>"
                  class="<?php echo esc_attr( $field['class'] ); ?>"
			<?php echo $this->attributes( $field['attributes'] ); ?>
        ><?php echo $this->esc_textarea( $field['value'] ); ?></textarea>
		<?php
	}

	/**
	 * Help prevent excessive slashes and potential malicious code
	 *
	 * @param $value
	 * @return string
	 */
	function esc_textarea( $value ){
		return stripcslashes( esc_textarea( str_replace( "\\", "", $value ) ) );
	}

	/**
	 * Group of checkboxes
	 *  - expects an array of values as $field['value']
	 *
	 * @param $field
	 */
	function template_checkboxes( $field ){
		$field['class'].= ' ww-checkboxes-item';
		$i = 0;
		if ( !is_array( $field['value'] ) ) {
			$field['value'] = array( $field['value'] => $field['value'] );
		}

		foreach( $field['options'] as $value => $details ){
			// default to assuming not-array
			$label = $details;
			$description = null;
			$data = null;

			// if array is given, get the title, description, and data
			if ( is_array( $details ) && isset( $details['title'] ) ){
				$label = $details['title'];

				if ( !empty( $details['description'] ) ){
					$description = $details['description'];
				}

				if ( !empty( $details['data'] ) ) {
					$data = $details['data'];
				}
			}

			?>
            <div class="ww-checkboxes-wrapper">
                <label for="<?php echo esc_attr( $field['id'] ); ?>--<?php echo $i; ?>">
                    <input type="checkbox"
                           name="<?php echo esc_attr( $field['form_name'] ); ?>[<?php echo esc_attr( $value ); ?>]"
                           id="<?php echo esc_attr( $field['id'] ); ?>--<?php echo $i; ?>"
                           class="<?php echo esc_attr( $field['class'] ); ?>"
                           value="<?php echo esc_attr( $value ); ?>"
						<?php checked( isset( $field['value'][ $value ] ) ); ?>
						<?php if ( $data ) print $this->attributes( $data, 'data-' ); ?>
                    >
					<?php echo $label; ?>
                </label>
				<?php if ( $description ): ?>
                    <p class="description"><?php echo $description; ?></p>
				<?php endif; ?>
            </div>
			<?php
			$i++;
		}
	}

	/**
	 * Select box
	 *  - expects an array of options as $field['options']
	 *
	 * @param $field
	 */
	function template_select( $field ){
		?>
        <select name="<?php echo esc_attr( $field['form_name'] ); ?>"
                id="<?php echo esc_attr( $field['id'] ); ?>"
                class="<?php echo esc_attr( $field['class'] ); ?>"
			<?php echo $this->attributes( $field['attributes'] ); ?> >
			<?php foreach( $field['options'] as $value => $option ) : ?>
                <option value="<?php echo esc_attr( $value ); ?>" <?php selected( $value, $field['value'] ); ?>><?php echo esc_html( $option ); ?></option>
			<?php endforeach; ?>
        </select>
		<?php
	}

	/**
	 * Simple item list
	 *  - expects an array of items as $field['items']
	 *
	 * @param $field
	 */
	function template_item_list( $field ){
		?>
        <ul class="<?php echo esc_attr( $field['class'] ); ?>"
	        <?php echo $this->attributes( $field['attributes'] ); ?>>
			<?php
			foreach ( $field['items'] as $item ) { ?>
                <li><?php print $item; ?></li>
				<?php
			}
			?>
        </ul>
		<?php
	}

	/**
     * Generic output.
     *
	 * @param $field
	 */
	function template_markup( $field ) {
	    print $field['value'];
    }


	// **** styles ***** //


	/**
	 * Settings Table form style field wrapper HTML
	 *
	 * @param $field
	 * @param $field_html
	 */
	function field_wrapper_table( $field, $field_html ){
		?>
        <tr  id="<?php echo esc_attr( $field['id'] ) ;?>--wrapper"
             class="ww-field-wrapper ww-field-wrapper-table">
            <th scope="row">
                <?php if ( !empty( $field['title'] ) ) : ?>
                    <label for="<?php echo esc_attr( $field['id'] ); ?>" class="ww-field-label">
                        <?php echo $field['title']; ?>
                    </label>
                <?php endif; ?>
            </th>
            <td>
				<?php echo $field_html; ?>

				<?php if ( !empty( $field['description'] ) ) : ?>
                    <p class="description"><?php echo $field['description']; ?></p>
				<?php endif; ?>

				<?php if ( !empty( $field['help'] ) ) : ?>
                    <p class="description"><?php echo $field['help']; ?></p>
				<?php endif; ?>
            </td>
        </tr>
		<?php
	}

	/**
	 * Settings table form style open HTML
	 *
	 * @return string
	 */
	function form_open_table(){
		return '<table class="form-table">';
	}

	/**
	 * Settings table form style close HTML
	 *
	 * @return string
	 */
	function form_close_table(){
		return '</table>';
	}

	/**
	 * Flat form style
	 *
	 * @param $field
	 * @param $field_html
	 */
	function field_wrapper_flat( $field, $field_html ){
	    ?>
        <div id="<?php echo esc_attr( $field['id'] ) ;?>--wrapper"
             class="ww-field-wrapper ww-field-wrapper-flat">

            <?php if ( !empty( $field['title'] ) && $field['label_first']) : ?>
                <label for="<?php echo esc_attr( $field['id'] ); ?>" class="ww-field-label">
                    <?php echo $field['title']; ?>
                </label>
            <?php endif; ?>

			<?php if ( !empty( $field['description'] ) ) : ?>
                <p class="description"><?php echo $field['description']; ?></p>
			<?php endif; ?>

			<?php echo $field_html; ?>

		    <?php if ( !empty( $field['title'] ) && !$field['label_first']) : ?>
                <label for="<?php echo esc_attr( $field['id'] ); ?>" class="ww-field-label">
                    <?php echo $field['title']; ?>
                </label>
            <?php endif; ?>

			<?php if ( !empty($field['help']) ) : ?>
                <p class="description"><?php echo $field['help']; ?></p>
			<?php endif; ?>
        </div>
		<?php
	}

	/**
	 * Inline form style
	 *
	 * @param $field
	 * @param $field_html
	 */
	function field_wrapper_inline( $field, $field_html ){
	    ?>
        <span id="<?php echo esc_attr( $field['id'] ) ;?>--wrapper"
             class="ww-field-wrapper ww-field-wrapper-inline">

            <?php if ( !empty( $field['title'] ) && $field['label_first']) : ?>
                <label for="<?php echo esc_attr( $field['id'] ); ?>" class="ww-field-label">
                    <?php echo $field['title']; ?>
                </label>
            <?php endif; ?>

			<?php if ( !empty( $field['description'] ) ) : ?>
                <p class="description"><?php echo $field['description']; ?></p>
			<?php endif; ?>

			<?php echo $field_html; ?>

		    <?php if ( !empty( $field['title'] ) && !$field['label_first']) : ?>
                <label for="<?php echo esc_attr( $field['id'] ); ?>" class="ww-field-label">
                    <?php echo $field['title']; ?>
                </label>
            <?php endif; ?>

			<?php if ( !empty($field['help']) ) : ?>
                <p class="description"><?php echo $field['help']; ?></p>
			<?php endif; ?>
        </span>
		<?php
	}

	/**
	 * Box form style
	 *
	 * @param $field
	 * @param $field_html
	 */
	function field_wrapper_box( $field, $field_html ){
	    ?>
        <div id="<?php echo esc_attr( $field['id'] ) ;?>--wrapper"
             class="ww-field-wrapper ww-field-wrapper-box ww-box">

            <h3>
                <label for="<?php echo esc_attr( $field['id'] ); ?>" class="ww-field-label">
                    <?php echo $field['title']; ?>
                </label>
            </h3>

            <div>
			<?php if ( !empty( $field['description'] ) ) : ?>
                <p class="description"><?php echo $field['description']; ?></p>
			<?php endif; ?>

			<?php echo $field_html; ?>

		    <?php if ( !empty( $field['title'] ) && !$field['label_first']) : ?>
                <label for="<?php echo esc_attr( $field['id'] ); ?>" class="ww-field-label">
                    <?php echo $field['title']; ?>
                </label>
            <?php endif; ?>

			<?php if ( !empty($field['help']) ) : ?>
                <p class="description"><?php echo $field['help']; ?></p>
			<?php endif; ?>
            </div>
        </div>
		<?php
	}

	/**
	 * Flat form style opening HTML
	 *
	 * @return string
	 */
	function form_open_flat(){
		return '<div class="ww-form">';
	}

	/**
	 * Flat form style closing HTML
	 *
	 * @return string
	 */
	function form_close_flat(){
		return '</div>';
	}

	/**
	 * Get the value of a form field from the submitted array, by using
	 *  the field's complete "name" attribute.
	 *
	 * @param $form_name - string of field form name
	 *                   - like: some-prefix[top][middle][another]
	 * @param $data - an array of submitted data
	 *
	 * @return array
	 */
	function get_field_value_from_data( $form_name, $data ){
		$temp = explode( '[', $form_name );
		$keys = array_map( 'sanitize_title_with_dashes', $temp);

		return $this->array_query( $keys, $data );
	}

	/**
	 * Using an array of keys as a path, find a value in a multi-dimensional array
	 *
	 * @param $keys
	 * @param $data
	 *
	 * @return mixed|null
	 */
	function array_query( $keys, $data ){
		if ( empty( $keys ) ){
			return $data;
		}

		$key = array_shift( $keys );

		if ( isset( $data[ $key ] ) ) {

			// if this was the last key, we have found the value
			if ( empty( $keys ) ){
				return $data[ $key ];
			}
			// if there are remaining keys and this key leads to an array,
			// recurse using the remaining keys
			else if ( is_array( $data[ $key ] ) ) {
				return $this->array_query( $keys, $data[ $key ] );
			}
			// there are remaining keys, but this item is not an array
			else {
				return null;
			}
		}

		return null;
	}

}
