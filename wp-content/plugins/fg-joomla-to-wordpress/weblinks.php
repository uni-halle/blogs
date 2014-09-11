<?php
/**
 * FG Joomla to WordPress
 * Module to import the web links
 */

// Exit if accessed directly
if ( !defined( 'ABSPATH' ) ) exit;

if ( !class_exists('fgj2wp_links', false) ) {
	class fgj2wp_links {
		
		public $links_count = 0;
		
		/**
		 * Sets up the plugin
		 *
		 */
		public function __construct($plugin) {
			
			$this->plugin = $plugin;
			
			add_action( 'fgj2wp_post_empty_database', array (&$this, 'empty_links'), 10, 1 );
			add_action( 'fgj2wp_post_import', array(&$this, 'import_links') );
			add_action( 'fgj2wp_post_remove_category_prefix', array (&$this, 'remove_category_prefix') );
			add_action( 'fgj2wp_import_notices', array (&$this, 'display_links_count') );
			add_filter( 'fgj2wp_pre_display_admin_page', array (&$this, 'process_admin_page'), 11, 1 );
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
			
			// Links categories
			$cat_count = $this->import_categories();
			$this->plugin->display_admin_notice(sprintf(_n('%d links category imported', '%d links categories imported', $cat_count, 'fgj2wp'), $cat_count));
			
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
			global $joomla_db;
			$links = array();
			$cat_prefix = 'cl';

			$last_id = (int)get_option('fgj2wp_last_link_id'); // to restore the import where it left
			try {
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
				
				$query = $joomla_db->query($sql);
				if ( is_object($query) ) {
					foreach ( $query as $row ) {
						$links[] = $row;
					}
				}
				
			} catch ( PDOException $e ) {
				$this->plugin->display_admin_error(__('Error:', 'fgj2wp') . $e->getMessage());
			}
			return $links;		
		}
		
		/**
		 * Import the web links categories
		 *
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
						$this->plugin->display_admin_error(__('Error:', 'fgj2wp') . ' ' . print_r($cat_id, true));
						continue;
					}
					
					// Hook after inserting the category
					do_action('fgj2wp_post_insert_category', $cat_id, $category);
				}
				
				// Hook after importing all the categories
				do_action('fgj2wp_post_import_categories', $categories);
				
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
			
			$this->plugin->display_admin_notice(sprintf(_n('%d web link imported', '%d web links imported', $this->links_count, 'fgj2wp'), $this->links_count));
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
				$data['database_info'][] = sprintf(_n('%d link', '%d links', $links_count, 'fgj2wp'), $links_count);
			}
			return $data;
		}
		
	}
}
?>
