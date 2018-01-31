<?php
/**
 * Adds Page Options import / export feature.
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
 * Adds Page Options import / export feature.
 */
class Avada_Page_Options {

	/**
	 * WP Filesystem object.
	 *
	 * @var object
	 */
	private $wp_filesystem;

	/**
	 * Page Options Export directory path.
	 *
	 * @var string
	 */
	private $po_dir_path;

	/**
	 * Page Options Export URL.
	 *
	 * @var string
	 */
	private $po_dir_url;

	/**
	 * The class constructor.
	 */
	public function __construct() {

		$this->wp_filesystem = Fusion_Helper::init_filesystem();

		$upload_dir        = wp_upload_dir();
		$this->po_dir_path = wp_normalize_path( trailingslashit( $upload_dir['basedir'] ) . 'fusion-page-options-export/' );
		$this->po_dir_url  = trailingslashit( $upload_dir['baseurl'] ) . 'fusion-page-options-export/';

		add_action( 'init', array( $this, 'init_post_type' ) );

		add_filter( 'avada_metabox_tabs', array( $this, 'add_options_tab' ), 10, 2 );
		add_action( 'init', array( $this, 'export_options' ) );
		add_action( 'wp_ajax_fusion_page_options_import', array( $this, 'ajax_import_options' ) );
		add_action( 'wp_ajax_fusion_page_options_save', array( $this, 'ajax_save_options' ) );
		add_action( 'wp_ajax_fusion_page_options_delete', array( $this, 'ajax_delete_options' ) );
		add_action( 'wp_ajax_fusion_page_options_import_saved', array( $this, 'ajax_import_options_saved' ) );
	}

	/**
	 * Inits needed post type.
	 */
	public function init_post_type() {

		$labels = array(
			'name'               => _x( 'Avada Page Options', 'element type general name', 'Avada' ),
			'singular_name'      => _x( 'Item', 'Item singular name', 'Avada' ),
			'add_new'            => _x( 'Add Item', 'Item', 'Avada' ),
			'add_new_item'       => esc_attr__( 'Add New Item', 'Avada' ),
			'edit_item'          => esc_attr__( 'Edit Item', 'Avada' ),
			'new_item'           => esc_attr__( 'New Item', 'Avada' ),
			'all_items'          => esc_attr__( 'All Items', 'Avada' ),
			'view_item'          => esc_attr__( 'View Item', 'Avada' ),
			'search_items'       => esc_attr__( 'Search Items', 'Avada' ),
			'not_found'          => esc_attr__( 'Nothing found', 'Avada' ),
			'not_found_in_trash' => esc_attr__( 'Nothing found in Trash', 'Avada' ),
			'parent_item_colon'  => '',
		);

		$args = array(
			'labels'             => $labels,
			'public'             => false,
			'publicly_queryable' => false,
			'can_export'         => true,
			'query_var'          => false,
			'has_archive'        => false,
			'capability_type'    => 'post',
			'map_meta_cap'       => true,
			'hierarchical'       => false,
			'supports'           => array( 'title' ),
		);

		register_post_type( 'avada_page_options', $args );

	}

	/**
	 * Adds Page Options Tab
	 *
	 * @param array  $tabs      The requested tabs.
	 * @param string $post_type Post type.
	 *
	 * @return array
	 */
	public function add_options_tab( $tabs, $post_type ) {

		$tab_key  = 'avada_page_options';
		$tab_name = esc_html__( 'Import / Export', 'Avada' );

		$tabs['requested_tabs'][]       = $tab_key;
		$tabs['tabs_names'][ $tab_key ] = $tab_name;
		$tabs['tabs_path'][ $tab_key ]  = Avada::$template_dir_path . '/includes/metaboxes/tabs/tab_' . $tab_key . '.php';

		return $tabs;
	}

	/**
	 * AJAX callback function. Used to export Page Options.
	 */
	public function export_options() {

		if ( ! isset( $_GET['action'] ) || 'download-avada-po' !== $_GET['action'] ) {
			return;
		}

		// @codingStandardsIgnoreLine WordPress.VIP.ValidatedSanitizedInput.InputNotSanitized
		if ( ! isset( $_GET['_wpnonce'] ) || ! wp_verify_nonce( $_GET['_wpnonce'] ) ) {
			die();
		}

		$post_id = 0;
		if ( isset( $_GET['post_id'] ) ) {
			$post_id = absint( $_GET['post_id'] );
		}

		header( 'Content-Description: File Transfer' );
		header( 'Content-type: application/txt' );
		header( 'Content-Disposition: attachment; filename="avada-options-' . $post_id . '-' . date( 'd-m-Y' ) . '.json"' );
		header( 'Content-Transfer-Encoding: binary' );
		header( 'Expires: 0' );
		header( 'Cache-Control: must-revalidate' );
		header( 'Pragma: public' );

		echo json_encode( $this->get_avada_post_custom_fields( $post_id ) );
		die();
	}

	/**
	 * Gets all Avada's custom fields for specified post.
	 *
	 * @param int $post_id Post ID.
	 *
	 * @return array
	 */
	private function get_avada_post_custom_fields( $post_id ) {

		$post_custom_fields  = get_post_custom( $post_id );
		$avada_custom_fields = array();

		foreach ( $post_custom_fields as $key => $value ) {
			if ( 0 === strpos( $key, 'pyre_' ) || 0 === strpos( $key, 'sbg_' ) ) {
				$avada_custom_fields[ $key ] = isset( $value[0] ) ? maybe_unserialize( $value[0] ) : '';
			}
		}

		return $avada_custom_fields;
	}

	/**
	 * AJAX callback function. Used to import Page Options.
	 */
	public function ajax_import_options() {

		check_ajax_referer( 'fusion-page-options-nonce', 'fusion_po_nonce' );
		$response = array();

		$post_id = 0;
		if ( isset( $_POST['post_id'] ) ) {
			$post_id = absint( $_POST['post_id'] );
		}

		$response = array();

		if ( ! isset( $_FILES['po_file_upload']['name'] ) ) {
			return false;
		}

		// @codingStandardsIgnoreLine WordPress.VIP.ValidatedSanitizedInput.InputNotSanitized
		$json_file_path  = wp_normalize_path( $this->po_dir_path . wp_unslash( $_FILES['po_file_upload']['name'] ) );

		if ( ! file_exists( $this->po_dir_path ) ) {
			wp_mkdir_p( $this->po_dir_path );
		}

		if ( ! isset( $_FILES['po_file_upload'] ) || ! isset( $_FILES['po_file_upload']['tmp_name'] ) ) {
			return false;
		}
		// We're already checking if defined above.
		// @codingStandardsIgnoreLine WordPress.VIP.ValidatedSanitizedInput.InputNotSanitized
		if ( ! $this->wp_filesystem->move( wp_normalize_path( $_FILES['po_file_upload']['tmp_name'] ), $json_file_path, true ) ) {
			return false;
		}

		$content_json = $this->wp_filesystem->get_contents( $json_file_path );

		// @codingStandardsIgnoreLine
		$custom_fields = json_decode( $content_json, true );
		if ( $custom_fields ) {

			/* $this->update_avada_custom_fields( $post_id, $custom_fields ); */

			$response['custom_fields'] = $custom_fields;
		}

		$this->wp_filesystem->delete( $json_file_path );

		echo json_encode( $response );
		die();
	}

	/**
	 * Updates Avada's custom fields from specified array.
	 *
	 * @param int   $post_id Post ID.
	 * @param array $custom_fields Array of custom fields.
	 */
	private function update_avada_custom_fields( $post_id, $custom_fields ) {

		foreach ( $custom_fields as $key => $value ) {
			if ( 0 === strpos( $key, 'pyre_' ) || 0 === strpos( $key, 'sbg_' ) ) {
				update_post_meta( $post_id, $key, $value );
			}
		}
	}

	/**
	 * AJAX callback function. Used to save Page Options.
	 */
	public function ajax_save_options() {

		check_ajax_referer( 'fusion-page-options-nonce', 'fusion_po_nonce' );
		$response = array();

		$post_id = 0;
		if ( isset( $_GET['post_id'] ) ) {
			$post_id = absint( $_GET['post_id'] );
		}

		$options_title = isset( $_GET['options_title'] ) ? sanitize_text_field( wp_unslash( $_GET['options_title'] ) ) : '';
		$post_type     = isset( $_GET['post_type'] ) ? sanitize_text_field( wp_unslash( $_GET['post_type'] ) ) : '';

		$custom_fields = $this->get_avada_post_custom_fields( $post_id );

		$post_id                      = $this->insert_options_post( $options_title, $custom_fields, $post_type );
		$response['saved_post_id']    = $post_id;
		$response['saved_post_title'] = get_the_title( $post_id );

		echo json_encode( $response );
		die();
	}

	/**
	 * AJAX callback function. Used to delete Page Options.
	 */
	public function ajax_delete_options() {

		check_ajax_referer( 'fusion-page-options-nonce', 'fusion_po_nonce' );
		$response = array();

		$saved_post_id = 0;
		if ( isset( $_GET['saved_post_id'] ) ) {
			$saved_post_id = absint( $_GET['saved_post_id'] );
		}

		$this->delete_options_post( $saved_post_id );

		echo json_encode( $response );
		die();
	}

	/**
	 * Creates new post with custom fields.
	 *
	 * @param string $options_title Name of the options to be saved.
	 * @param array  $custom_fields Array of custom fields to be saved.
	 * @param string $post_type     Slug of post for which options are to be saved.
	 *
	 * @return int|WP_Error
	 */
	private function insert_options_post( $options_title = '', $custom_fields, $post_type ) {

		if ( empty( $options_title ) ) {
			$posts_no = wp_count_posts( 'avada_page_options' );
			$options_title = sprintf( __( 'Custom page options %d ', 'Avada' ), ($posts_no->publish + 1) );
		}

		$post_id = wp_insert_post(
			array(
				'post_title'  => $options_title,
				'post_type'   => 'avada_page_options',
				'post_status' => 'publish',
			)
		);

		if ( $post_id ) {
			add_post_meta( $post_id, 'fusion_page_options', $custom_fields );
		}

		return $post_id;

	}

	/**
	 * Deletes previously saved post.
	 *
	 * @param int $post_id ID of post which needs to be deleted.
	 */
	private function delete_options_post( $post_id ) {

		wp_delete_post( $post_id, true );
	}

	/**
	 * AJAX callback function. Used to import Page Options from previously saved set.
	 */
	public function ajax_import_options_saved() {

		check_ajax_referer( 'fusion-page-options-nonce', 'fusion_po_nonce' );
		$response = array();

		$post_id = 0;
		if ( isset( $_GET['post_id'] ) ) {
			$post_id = absint( $_GET['post_id'] );
		}
		$saved_post_id = 0;
		if ( isset( $_GET['saved_post_id'] ) ) {
			$saved_post_id = absint( $_GET['saved_post_id'] );
		}

		$custom_fields = get_post_meta( $saved_post_id, 'fusion_page_options', true );

		/* $this->update_avada_custom_fields( $post_id, $custom_fields ); */

		$response['custom_fields'] = $custom_fields;

		echo json_encode( $response );
		die();

	}

}

/* Omit closing PHP tag to avoid "Headers already sent" issues. */
