<?php

/**
 * Module to import the web links
 *
 * @link       https://wordpress.org/plugins/fg-joomla-to-wordpress/
 * @since      2.0.0
 *
 * @package    FG_Joomla_to_WordPress
 * @subpackage FG_Joomla_to_WordPress/admin
 */

if ( !class_exists('FG_Joomla_to_WordPress_Weblinks', false) ) {

	/**
	 * Class to import the web links
	 *
	 * @package    FG_Joomla_to_WordPress
	 * @subpackage FG_Joomla_to_WordPress/admin
	 * @author     Frédéric GILLES
	 */
	class FG_Joomla_to_WordPress_Weblinks {

		public $links_count = 0; // Number of imported weblinks

		/**
		 * Initialize the class and set its properties.
		 *
		 * @since    2.0.0
		 * @param    object    $plugin       Admin plugin
		 */
		public function __construct( $plugin ) {

			$this->plugin = $plugin;

		}

		/**
		 * Delete all links from the database
		 *
		 * @param string $action	newposts = removes only new imported posts
		 * 							all = removes all
		 * @return boolean
		 */
		public function empty_links($action) {
			global $wpdb;
			$result = true;

			if ( $action == 'all' ) {
				$sql = "TRUNCATE $wpdb->links";
				$result = $wpdb->query($sql);
				update_option('fgj2wp_last_link_id', 0);
			}
			return ($result !== false);
		}

		/**
		 * Count the web links
		 *
		 * @return int Number of web links in the database
		 */
		public function count_links() {
			global $wpdb;

			$sql = "SELECT COUNT(*) AS nb FROM $wpdb->links";
			return $wpdb->get_var($sql);
		}

		/**
		 * Import the web links
		 *
		 */
		public function import_links() {
			if ( isset($this->plugin->premium_options['skip_weblinks']) && $this->plugin->premium_options['skip_weblinks'] ) {
				return;
			}
			if ( !$this->plugin->table_exists('weblinks') ) { // Joomla 3.4
				return;
			}

			// Links categories
			$cat_count = $this->import_categories();
			$this->plugin->display_admin_notice(sprintf(_n('%d links category imported', '%d links categories imported', $cat_count, 'fg-joomla-to-wordpress'), $cat_count));

			$links = $this->get_weblinks();
			foreach ( $links as $link ) {

				// Categories
				$category = $link['category'];
				if ( array_key_exists($category, $this->categories) ) {
					$cat_id = $this->categories[$category];
				} else {
					$cat_id = ''; // default category
				}

				$linkdata = array(
					'link_name'			=> $link['title'],
					'link_url'			=> $link['url'],
					'link_description'	=> $link['description'],
					'link_target'		=> '_blank',
					'link_category'		=> $cat_id,
				);
				$new_link_id = wp_insert_link( $linkdata );
				if ( $new_link_id ) {
					$this->links_count++;
					// Increment the Joomla last imported link
					update_option('fgj2wp_last_link_id', $new_link_id);
				}
			}
		}

		/**
		 * Get Joomla web links
		 *
		 * @return array of Links
		 */
		private function get_weblinks() {
			$links = array();
			$cat_prefix = 'cl';

			$last_id = (int)get_option('fgj2wp_last_link_id'); // to restore the import where it left
			$prefix = $this->plugin->plugin_options['prefix'];
			switch ( $this->plugin->plugin_options['version'] ) {
				case '1.0':
					$sql = "
						SELECT l.id, l.title, l.url, l.description, l.ordering, l.date, CONCAT('$cat_prefix', c.id, '-', c.name) AS category
						FROM ${prefix}weblinks l
						LEFT JOIN ${prefix}categories AS c ON c.id = l.catid
						WHERE l.published = 1
						AND l.id > '$last_id'
						ORDER BY l.id
					";
					break;

				case '1.5':
					$sql = "
						SELECT l.id, l.title, l.url, l.description, l.ordering, l.date, CONCAT('$cat_prefix', c.id, '-', IF(c.alias <> '', c.alias, c.name)) AS category
						FROM ${prefix}weblinks l
						LEFT JOIN ${prefix}categories AS c ON c.id = l.catid
						WHERE l.published = 1
						AND l.id > '$last_id'
						ORDER BY l.id
					";
					break;

				default:
					$sql = "
						SELECT l.id, l.title, l.url, l.description, l.ordering, l.created AS date, CONCAT('$cat_prefix', c.id, '-', c.alias) AS category
						FROM ${prefix}weblinks l
						LEFT JOIN ${prefix}categories AS c ON c.id = l.catid
						WHERE l.state = 1
						AND l.id > '$last_id'
						ORDER BY l.id
					";
					break;
			}
			$links = $this->plugin->joomla_query($sql);
			return $links;
		}

		/**
		 * Import the web links categories
		 *
		 * @return int Number of imported categories
		 */
		private function import_categories() {
			$cat_count = 0;
			$taxonomy = 'link_category';
			$this->categories = array();
			$categories = $this->plugin->get_component_categories('com_weblinks', 'cl');
			if ( is_array($categories) ) {
				$terms = array();
				foreach ( $categories as $category ) {
					$obj_cat = get_term_by('slug', $category['name'], $taxonomy);
					if ( $obj_cat !== false ) {
						$this->categories[$category['name']] = $obj_cat->term_id;
						continue; // Do not import already imported category
					}

					// Insert the category
					$new_category = array(
						'cat_name' 				=> $category['title'],
						'category_description'	=> $category['description'],
						'category_nicename'		=> $category['name'], // slug
						'taxonomy'				=> $taxonomy,
					);

					// Hook before inserting the category
					$new_category = apply_filters('fgj2wp_pre_insert_category', $new_category, $category);

					$cat_id = wp_insert_category($new_category, true);
					if ( !is_a($cat_id, 'WP_Error') ) {
						$cat_count++;
						$terms[] = $cat_id;
						$this->categories[$category['name']] = $cat_id;
					} else {
						$this->plugin->display_admin_error(__('Error:', 'fg-joomla-to-wordpress') . ' ' . print_r($cat_id, true));
						continue;
					}

					// Hook after inserting the category
					do_action('fgj2wp_post_insert_category', $cat_id, $category);
				}

				// Update cache
				if ( !empty($terms) ) {
					wp_update_term_count_now($terms, $taxonomy);
					$this->plugin->clean_cache($terms);
				}
			}
			return $cat_count;
		}

		/**
		 * Remove the prefixes categories
		 */
		public function remove_category_prefix() {
			$matches = array();
			$taxonomy = 'link_category';
			$categories = get_terms( $taxonomy, array('hide_empty' => 0) );
			if ( !empty($categories) ) {
				foreach ( $categories as $cat ) {
					if ( preg_match('/^cl\d+-(.*)/', $cat->slug, $matches) ) {
						wp_update_term($cat->term_id, $taxonomy, array(
							'slug' => $matches[1]
						));
					}
				}
			}
		}

		/**
		 * Display the number of imported links
		 * 
		 */
		public function display_links_count() {
			if ( isset($this->plugin->premium_options['skip_weblinks']) && $this->plugin->premium_options['skip_weblinks'] ) {
				return;
			}
			if ( !$this->plugin->table_exists('weblinks') ) { // Joomla 3.4
				return;
			}

			$this->plugin->display_admin_notice(sprintf(_n('%d web link imported', '%d web links imported', $this->links_count, 'fg-joomla-to-wordpress'), $this->links_count));
		}

		/**
		 * Add information to the admin page
		 * 
		 * @param array $data
		 * @return array
		 */
		public function process_admin_page($data) {
			$links_count = $this->count_links();

			if ( $links_count > 0 ) {
				$data['database_info'][] = sprintf(_n('%d link', '%d links', $links_count, 'fg-joomla-to-wordpress'), $links_count);
			}
			return $data;
		}

	}
}
