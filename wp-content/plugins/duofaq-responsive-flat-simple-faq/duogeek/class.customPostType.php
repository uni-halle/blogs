<?php

if ( ! defined( 'ABSPATH' ) ) wp_die( __( 'Sorry hackers! This is not your place!', 'sn' ) );

if( ! class_exists( 'customPostType' ) ) {
	
	abstract class customPostType {
		
		protected $custom_post_type = array();
		protected $custom_tax = array();
		
		protected function __construct( $args = array() ) {
			
			$defaults = array(
				'add_new'			=> 'Add New',
				'add_new_item'		=> 'Add New Item',
				'new_item'			=> 'New Item',
				'edit_item'			=> 'Edit Item',
				'view_item'			=> 'View Item',
				'all_items'			=> 'All Items',
				'search_items'		=> 'Search Items',
				'parent_item_colon'	=> 'Parent Items:',
				'not_found'			=> 'No items found',
				'not_found_in_trash'=> 'No items found in trash',
				'capability_type'	=> 'post',
				'supports'			=> array( 'title', 'editor', 'author', 'thumbnail', 'excerpt', 'comments' )
			);
			
			$this->custom_post_type = wp_parse_args( $args, $defaults );
			 
		}
		
		
		protected function register_custom_post_type() {
			$labels = array(
				'name'               => $this->custom_post_type['name'],
				'singular_name'      => $this->custom_post_type['singular_name'],
				'menu_name'          => apply_filters( 'duo_change_'. $this->custom_post_type['post_type'] .'_menu_name', $this->custom_post_type['menu_name'] ),
				'name_admin_bar'     => $this->custom_post_type['name_admin_bar'],
				'add_new'            => $this->custom_post_type['add_new'],
				'add_new_item'       => $this->custom_post_type['add_new_item'],
				'new_item'           => $this->custom_post_type['new_item'],
				'edit_item'          => $this->custom_post_type['edit_item'],
				'view_item'          => $this->custom_post_type['view_item'],
				'all_items'          => $this->custom_post_type['all_items'],
				'search_items'       => $this->custom_post_type['search_items'],
				'parent_item_colon'  => $this->custom_post_type['parent_item_colon'],
				'not_found'          => $this->custom_post_type['not_found'],
				'not_found_in_trash' => $this->custom_post_type['not_found_in_trash']
			);
		
			$args = array(
				'labels'             => $labels,
				'public'             => true,
				'publicly_queryable' => true,
				'show_ui'            => true,
				'show_in_menu'       => true,
				'query_var'          => true,
				'rewrite'            => array( 'slug' => $this->custom_post_type['rewrite'] ),
				'capability_type'    => 'post',
				'has_archive'        => true,
				'hierarchical'       => false,
				'menu_position'      => null,
				'supports'           => $this->custom_post_type['supports']
			);

			register_post_type( $this->custom_post_type['post_type'], $args );
		}


		protected function set_tax( $args ){
			
			$defaults = array(
				'search_items'      => 'Search Items',
				'all_items'         => 'All Items',
				'parent_item'       => 'Parent Items',
				'parent_item_colon' => 'Parent Item:',
				'edit_item'         => 'Edit Item',
				'update_item'       => 'Update Item',
				'add_new_item'      => 'Add New Item',
				'new_item_name'     => 'New Item Name',
				'menu_name'         => 'Item',
				'hierarchical'      => false,
				'show_ui'           => true,
				'show_admin_column' => true,
				'query_var'         => true
			);
			
			$this->custom_tax = wp_parse_args( $args, $defaults );
		}

		
		protected function register_custom_taxonomies() {
			$labels = array(
				'name'              => $this->custom_tax['name'],
				'singular_name'     => $this->custom_tax['singular_name'],
				'search_items'      => $this->custom_tax['search_items'],
				'all_items'         => $this->custom_tax['all_items'],
				'parent_item'       => $this->custom_tax['parent_item'],
				'parent_item_colon' => $this->custom_tax['parent_item_colon'],
				'edit_item'         => $this->custom_tax['edit_item'],
				'update_item'       => $this->custom_tax['update_item'],
				'add_new_item'      => $this->custom_tax['add_new_item'],
				'new_item_name'     => $this->custom_tax['new_item_name'],
				'menu_name'         => $this->custom_tax['menu_name'],
			);
		
			$args = array(
				'hierarchical'      => $this->custom_tax['hierarchical'],
				'labels'            => $labels,
				'show_ui'           => $this->custom_tax['show_ui'],
				'show_admin_column' => $this->custom_tax['show_admin_column'],
				'query_var'         => $this->custom_tax['query_var'],
				'rewrite'           => array( 'slug' => $this->custom_tax['rewrite'] ),
			);
		
			register_taxonomy( $this->custom_tax['tax_name'], $this->custom_post_type['post_type'], $args );
		}
                
                
                public function add_custom_meta_boxes( $meta_boxes ) {
			foreach( $meta_boxes as $meta_box ){
				add_meta_box(
					$meta_box['id'],
					$meta_box['title'],
					$meta_box['callback'],
					$meta_box['post_type'],
					$meta_box['context'],
					$meta_box['priority']
				);
			}
		}

		
		protected function get_terms_from_taxonomies( $taxonomies, $args = array() ){
			return get_terms( $taxonomies, $args );
		}


		public function get_tax_list( $taxonomies, $args = array() ){
			$defaults = array(
				'orderby'           => 'name',
				'order'             => 'ASC',
				'hide_empty'        => true,
				'exclude'           => array(),
				'exclude_tree'      => array(),
				'include'           => array(),
				'number'            => '',
				'fields'            => 'all',
				'slug'              => '',
				'parent'            => '',
				'hierarchical'      => true,
				'child_of'          => 0,
				'get'               => '',
				'name__like'        => '',
				'description__like' => '',
				'pad_counts'        => false,
				'offset'            => '',
				'search'            => '',
				'cache_domain'      => 'core'
			);

			$args = wp_parse_args( $args, $defaults );
			return get_terms($taxonomies, $args);
		}

		public function insert_post( $args ){
			$defaults = array(
				'post_status'           => 'publish',
				'post_title'    		  => 'My post',
				'post_content'  		  => 'This is my post.',
				'post_type'             => 'post',
				'post_author'           => get_current_user_id(),
				'ping_status'           => get_option('default_ping_status'),
				'post_parent'           => 0,
				'menu_order'            => 0,
				'to_ping'               =>  '',
				'pinged'                => '',
				'post_password'         => '',
				'guid'                  => '',
				'post_content_filtered' => '',
				'post_excerpt'          => '',
				'import_id'             => 0
			);

			$post = wp_parse_args( $args, $defaults );
			return wp_insert_post( $post, true );

		}

		public function get_all_posts( $args ){
			$defaults = array(
				'post_status'           => 'publish',
				'post_type'             => 'post',
				'author'           => get_current_user_id(),
				'posts_per_page'		 => -1
			);
			$args = wp_parse_args( $args, $defaults );
			return new WP_Query( $args );
		}

		public function log( $post_id, $msg, $show = false ){
			$m = get_post_meta( $post_id, 'sn_log_msg', true );
			if( $show ) return $m;
			$m[] = $msg;
			update_post_meta( $post_id, 'sn_log_msg', $m );
		}
		
	}
	
}