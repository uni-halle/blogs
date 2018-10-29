<?php
namespace ElementorPro\Modules\QueryControl;

use Elementor\Controls_Manager;
use Elementor\Core\Ajax_Manager;
use Elementor\Utils;
use Elementor\Widget_Base;
use ElementorPro\Base\Module_Base;
use ElementorPro\Modules\QueryControl\Controls\Group_Control_Posts;
use ElementorPro\Modules\QueryControl\Controls\Query;
use ElementorPro\Plugin;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

class Module extends Module_Base {

	const QUERY_CONTROL_ID = 'query';

	public static $displayed_ids = [];

	public function __construct() {
		parent::__construct();

		$this->add_actions();
	}

	public static function add_to_avoid_list( $ids ) {
		self::$displayed_ids = array_merge( self::$displayed_ids, $ids );
	}

	public static function get_avoid_list_ids() {
		return self::$displayed_ids;
	}

	/**
	 * @param Widget_Base $widget
	 */
	public static function add_exclude_controls( $widget ) {
		$widget->add_control(
			'exclude',
			[
				'label' => __( 'Exclude', 'elementor-pro' ),
				'type' => Controls_Manager::SELECT2,
				'multiple' => true,
				'options' => [
					'current_post' => __( 'Current Post', 'elementor-pro' ),
					'manual_selection' => __( 'Manual Selection', 'elementor-pro' ),
				],
				'label_block' => true,
			]
		);

		$widget->add_control(
			'exclude_ids',
			[
				'label' => __( 'Search & Select', 'elementor-pro' ),
				'type' => self::QUERY_CONTROL_ID,
				'post_type' => '',
				'options' => [],
				'label_block' => true,
				'multiple' => true,
				'filter_type' => 'by_id',
				'condition' => [
					'exclude' => 'manual_selection',
				],
			]
		);

		$widget->add_control(
			'avoid_duplicates',
			[
				'label' => __( 'Avoid Duplicates', 'elementor-pro' ),
				'type' => Controls_Manager::SWITCHER,
				'default' => '',
				'description' => __( 'Set to Yes to avoid duplicate posts from showing up. This only effects the frontend.', 'elementor-pro' ),
			]
		);

	}

	public function get_name() {
		return 'query-control';
	}

	public function ajax_posts_filter_autocomplete() {
		Plugin::elementor()->editor->verify_ajax_nonce();

		if ( empty( $_POST['filter_type'] ) || empty( $_POST['q'] ) ) {
			wp_send_json_error( new \WP_Error( 'Bad Request' ) );
		}

		$results = [];

		switch ( $_POST['filter_type'] ) {
			case 'taxonomy':
				$query_params = [
					'taxonomy' => $_POST['object_type'],
					'search' => $_POST['q'],
					'hide_empty' => false,
				];

				$terms = get_terms( $query_params );

				global $wp_taxonomies;

				foreach ( $terms as $term ) {
					if ( ! empty( $_POST['include_type'] ) ) {
						$text = $wp_taxonomies[ $term->taxonomy ]->labels->singular_name . ': ' . $term->name;
					} else {
						$text = $term->name;
					}

					$results[] = [
						'id' => $term->term_id,
						'text' => $text,
					];
				}

				break;

			case 'by_id':
			case 'post':
				$query_params = [
					'post_type' => $_POST['object_type'],
					's' => $_POST['q'],
					'posts_per_page' => -1,
				];

				if ( 'attachment' === $query_params['post_type'] ) {
					$query_params['post_status'] = 'inherit';
				}

				$query = new \WP_Query( $query_params );

				foreach ( $query->posts as $post ) {
					if ( ! empty( $_POST['include_type'] ) ) {
						$post_type_obj = get_post_type_object( $post->post_type );
						$text = $post_type_obj->labels->singular_name . ': ' . $post->post_title;
					} else {
						$text = $post->post_title;
					}

					$results[] = [
						'id' => $post->ID,
						'text' => $text,
					];
				}
				break;

			case 'author':
				$query_params = [
					'who' => 'authors',
					'has_published_posts' => true,
					'fields' => [
						'ID',
						'display_name',
					],
					'search' => '*' . $_POST['q'] . '*',
					'search_columns' => [
						'user_login',
						'user_nicename',
					],
				];

				$user_query = new \WP_User_Query( $query_params );

				foreach ( $user_query->get_results() as $author ) {
					$results[] = [
						'id' => $author->ID,
						'text' => $author->display_name,
					];
				}
				break;
		} // End switch().

		wp_send_json_success(
			[
				'results' => $results,
			]
		);
	}

	public function ajax_posts_control_value_titles( $request ) {
		$ids = (array) $request['id'];

		$results = [];

		if ( 'taxonomy' === $request['filter_type'] ) {

			$terms = get_terms(
				[
					'include' => $ids,
					'hide_empty' => false,
				]
			);

			foreach ( $terms as $term ) {
				$results[ $term->term_id ] = $term->name;
			}
		} elseif ( 'by_id' === $request['filter_type'] || 'post' === $request['filter_type'] ) {
			$query = new \WP_Query(
				[
					'post_type' => 'any',
					'post__in' => $ids,
					'posts_per_page' => -1,
				]
			);

			foreach ( $query->posts as $post ) {
				$results[ $post->ID ] = $post->post_title;
			}
		} elseif ( 'author' === $request['filter_type'] ) {
			$query_params = [
				'who' => 'authors',
				'has_published_posts' => true,
				'fields' => [
					'ID',
					'display_name',
				],
				'include' => $ids,
			];

			$user_query = new \WP_User_Query( $query_params );

			foreach ( $user_query->get_results() as $author ) {
				$results[ $author->ID ] = $author->display_name;
			}
		} // End if().

		return $results;
	}

	public function register_controls() {
		$controls_manager = Plugin::elementor()->controls_manager;

		$controls_manager->add_group_control( Group_Control_Posts::get_type(), new Group_Control_Posts() );

		$controls_manager->register_control( self::QUERY_CONTROL_ID, new Query() );
	}

	public static function get_query_args( $control_id, $settings ) {
		$defaults = [
			$control_id . '_post_type' => 'post',
			$control_id . '_posts_ids' => [],
			'orderby' => 'date',
			'order' => 'desc',
			'posts_per_page' => 3,
			'offset' => 0,
		];

		$settings = wp_parse_args( $settings, $defaults );

		$post_type = $settings[ $control_id . '_post_type' ];

		if ( 'current_query' === $post_type ) {
			$current_query_vars = $GLOBALS['wp_query']->query_vars;

			/**
			 * Current query variables.
			 *
			 * Filters the query variables for the current query.
			 *
			 * @since 1.0.0
			 *
			 * @param array $current_query_vars Current query variables.
			 */
			$current_query_vars = apply_filters( 'elementor_pro/query_control/get_query_args/current_query', $current_query_vars );

			return $current_query_vars;
		}

		$query_args = [
			'orderby' => $settings['orderby'],
			'order' => $settings['order'],
			'ignore_sticky_posts' => 1,
			'post_status' => 'publish', // Hide drafts/private posts for admins
		];

		if ( 'by_id' === $post_type ) {
			$query_args['post_type'] = 'any';
			$query_args['posts_per_page'] = -1;

			$query_args['post__in'] = $settings[ $control_id . '_posts_ids' ];

			if ( empty( $query_args['post__in'] ) ) {
				// If no selection - return an empty query
				$query_args['post__in'] = [ 0 ];
			}
		} else {
			$query_args['post_type'] = $post_type;
			$query_args['posts_per_page'] = $settings['posts_per_page'];
			$query_args['tax_query'] = [];

			if ( 0 < $settings['offset'] ) {
				/**
				 * Due to a WordPress bug, the offset will be set later, in $this->fix_query_offset()
				 * @see https://codex.wordpress.org/Making_Custom_Queries_using_Offset_and_Pagination
				 */
				$query_args['offset_to_fix'] = $settings['offset'];
			}

			$taxonomies = get_object_taxonomies( $post_type, 'objects' );

			foreach ( $taxonomies as $object ) {
				$setting_key = $control_id . '_' . $object->name . '_ids';

				if ( ! empty( $settings[ $setting_key ] ) ) {
					$query_args['tax_query'][] = [
						'taxonomy' => $object->name,
						'field' => 'term_id',
						'terms' => $settings[ $setting_key ],
					];
				}
			}
		}

		if ( ! empty( $settings[ $control_id . '_authors' ] ) ) {
			$query_args['author__in'] = $settings[ $control_id . '_authors' ];
		}

		$post__not_in = [];
		if ( ! empty( $settings['exclude'] ) ) {
			if ( in_array( 'current_post', $settings['exclude'] ) ) {
				if ( Utils::is_ajax() && ! empty( $_REQUEST['post_id'] ) ) {
					$post__not_in[] = $_REQUEST['post_id'];
				} elseif ( is_singular() ) {
					$post__not_in[] = get_queried_object_id();
				}
			}

			if ( in_array( 'manual_selection', $settings['exclude'] ) && ! empty( $settings['exclude_ids'] ) ) {
				$post__not_in = array_merge( $post__not_in, $settings['exclude_ids'] );
			}
		}

		if ( ! empty( $settings['avoid_duplicates'] ) && 'yes' === $settings['avoid_duplicates'] ) {
			$post__not_in = array_merge( $post__not_in, self::$displayed_ids );
		}

		$query_args['post__not_in'] = $post__not_in;

		return $query_args;
	}

	/**
	 * @param \WP_Query $query
	 */
	public function fix_query_offset( &$query ) {
		if ( ! empty( $query->query_vars['offset_to_fix'] ) ) {
			if ( $query->is_paged ) {
				$query->query_vars['offset'] = $query->query_vars['offset_to_fix'] + ( ( $query->query_vars['paged'] - 1 ) * $query->query_vars['posts_per_page'] );
			} else {
				$query->query_vars['offset'] = $query->query_vars['offset_to_fix'];
			}
		}
	}

	/**
	 * @param int       $found_posts
	 * @param \WP_Query $query
	 *
	 * @return mixed
	 */
	public function fix_query_found_posts( $found_posts, $query ) {
		$offset_to_fix = $query->get( 'fix_pagination_offset' );

		if ( $offset_to_fix ) {
			$found_posts -= $offset_to_fix;
		}

		return $found_posts;
	}

	/**
	 * @param Ajax_Manager $ajax_manager
	 */
	public function register_ajax_actions( $ajax_manager ) {
		$ajax_manager->register_ajax_action( 'query_control_value_titles', [ $this, 'ajax_posts_control_value_titles' ] );
	}

	public function localize_settings( $settings ) {
		$settings = array_replace_recursive( $settings, [
			'i18n' => [
				'all' => __( 'All', 'elementor-pro' ),
			],
		] );

		return $settings;
	}

	protected function add_actions() {
		add_action( 'wp_ajax_elementor_pro_panel_posts_control_filter_autocomplete', [ $this, 'ajax_posts_filter_autocomplete' ] );
		add_action( 'elementor/ajax/register_actions', [ $this, 'register_ajax_actions' ] );
		add_action( 'elementor/controls/controls_registered', [ $this, 'register_controls' ] );

		add_filter( 'elementor_pro/editor/localize_settings', [ $this, 'localize_settings' ] );

		/**
		 * @see https://codex.wordpress.org/Making_Custom_Queries_using_Offset_and_Pagination
		 */
		add_action( 'pre_get_posts', [ $this, 'fix_query_offset' ], 1 );
		add_filter( 'found_posts', [ $this, 'fix_query_found_posts' ], 1, 2 );
	}
}
