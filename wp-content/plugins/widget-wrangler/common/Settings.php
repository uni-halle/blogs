<?php

namespace WidgetWrangler;

/**
 * Class Settings
 * @package WidgetWrangler
 */
class Settings {

	// wp option name/key
	public $option_name = 'ww_settings';

	// stored option values array
	public $values = array();

	// default plugin settings values
	public $default_settings = array(
		'exclude_from_search' => 1,
		'theme_compat' => 1,
		'capabilities' => 'simple',
		'advanced_capability' => '',
		'post_types' => array(
			'page' => 'page',
			'post' => 'post',
		),
		'taxonomies' => array(),
		'override_elements' => array(
			'div', 'h2', 'h3', 'aside', 'strong', 'span',
		),
		'legacy_template_suggestions' => 0,

		// begin the weaning of features that will be removed
		// dead end features:  override html elements, shortcode tinymce
		'previously_pro' => 0,
		'override_elements_enabled' => 0,
		'shortcode_tinymce' => 0,
	);

	/**
	 * WidgetWranglerSettings constructor.
	 */
	 private function __construct(){}

	/**
	 * Singleton.
	 *
	 * @return \WidgetWrangler\Settings
	 */
	public static function instance() {
	 	$instance = null;

	 	if ( empty( $instance ) ) {
	 		$instance = new self();
	    }

	    $instance->refresh();
	    return $instance;
	}

	/**
	 * Load the option and merge with default values.
	 */
	public function refresh() {
		$this->values = get_option( $this->option_name, $this->default_settings );
		// help with over serialization in previous versions
		$this->values = maybe_unserialize( maybe_unserialize( maybe_unserialize($this->values) ) );
		$this->values = array_replace( $this->default_settings, $this->values );
	}

	/**
	 * Helper function to determine if enabled post type.
	 *
	 * @param $post_type
	 *
	 * @return bool
	 */
	function isEnabledPostType( $post_type ) {
		return !empty( $this->post_types[ $post_type ] );
	}

	/**
	 * Helper function to determine if enabled taxonomy.
	 *
	 * @param $taxonomy
	 *
	 * @return bool
	 */
	function isEnabledTaxonomy( $taxonomy ) {
		return !empty( $this->taxonomies[ $taxonomy ] );
	}

	/**
	 * Save the stored values to the option row
	 */
	function save(){
		// clean up checkboxes
		foreach ($this->values as $key => $value) {
			if ( is_string( $value ) ) {
				if ( $value == 'on' ) {
					$value = 1;
				}
				else if ( $value == '0' ) {
					$value = 0;
				}

				$this->values[ $key ] = $value;
			}
		}

		// make override elements an array
		if ( isset( $this->values['override_elements'] ) && is_string( $this->values['override_elements'] ) ) {
			$this->values['override_elements'] = explode("\n", $this->values['override_elements']);
		}

		// manage simple vs advanced capability automatically
		if ( isset( $this->values['advanced_capability'] ) ) {
			$this->values['advanced_capability'] = sanitize_text_field( trim( $this->values['advanced_capability'] ) );

			$this->values['capabilities'] = 'simple';

			if ( !empty( $this->values['advanced_capability'] ) ) {
				$this->values['capabilities'] = 'advanced';
			}
		}
		
		update_option( $this->option_name, $this->values );
	}

	/**
	 * Magic getter
	 *
	 * @param $key
	 *
	 * @return mixed|null
	 */
	function __get( $key ){
		if ( isset( $this->values[ $key ] ) ) {
			return $this->values[ $key ];
		}

		return null;
	}

	/**
	 * Magic setter
	 *
	 * @param $key
	 * @param $value
	 */
	function __set( $key, $value ){
		$this->values[ $key ] = $value;
	}

	/**
	 * Magic isset check
	 * @param $key
	 *
	 * @return bool
	 */
	function __isset( $key ){
		return isset( $this->values[ $key ] );
	}

	/**
	 * Magic unset
	 * @param $key
	 */
	function __unset( $key ){
		unset( $this->values[ $key ]);
	}
}
