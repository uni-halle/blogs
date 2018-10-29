<?php
namespace ElementorPro\Modules\ThemeBuilder\Conditions;

use ElementorPro\Modules\QueryControl\Module as QueryModule;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

class Post extends Condition_Base {

	private $post_type;
	private $post_taxonomies;

	public static function get_type() {
		return 'singular';
	}

	public function __construct( $data ) {
		$this->post_type = get_post_type_object( $data['post_type'] );
		$taxonomies = get_object_taxonomies( $data['post_type'], 'objects' );
		$this->post_taxonomies = wp_filter_object_list( $taxonomies, [
			'public' => true,
			'show_in_nav_menus' => true,
		] );

		parent::__construct();
	}

	public function get_name() {
		return $this->post_type->name;
	}

	public static function get_priority() {
		return 40;
	}

	public function get_label() {
		return $this->post_type->labels->singular_name;
	}

	public function get_all_label() {
		/* translators: %s: Post type label. */
		return sprintf( __( 'All %s', 'elementor-pro' ), $this->post_type->label );
	}

	public function check( $args ) {
		if ( isset( $args['id'] ) ) {
			$id = (int) $args['id'];
			if ( $id ) {
				return is_singular() && get_queried_object_id() === $id;
			}
		}

		return is_singular( $this->post_type->name );
	}

	public function register_sub_conditions() {
		foreach ( $this->post_taxonomies as $slug => $object ) {
			$condition = new In_Taxonomy( [
				'object' => $object,
			] );
			$this->register_sub_condition( $condition );
		}

		if ( $this->post_type->hierarchical ) {
			$condition = new Child_Of();
			$this->register_sub_condition( $condition );
		}
	}

	protected function _register_controls() {
		$this->add_control(
			'post_id',
			[
				'section' => 'settings',
				'type' => QueryModule::QUERY_CONTROL_ID,
				'select2options' => [
					'dropdownCssClass' => 'elementor-conditions-select2-dropdown',
				],
				'filter_type' => 'post',
				'object_type' => $this->get_name(),
			]
		);
	}
}
