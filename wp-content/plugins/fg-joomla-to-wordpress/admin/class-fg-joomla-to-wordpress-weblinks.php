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

if ( !class_exists('FG_Joomla_to_WordPress_Weblinks', FALSE) ) {

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
		public function empty_links() {
			global $wpdb;

			$result = $wpdb->query("TRUNCATE $wpdb->links");
			update_option('fgj2wp_last_link_id', 0);
			return ($result !== FALSE);
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
			if ( $this->plugin->import_stopped() ) {
				return;
			}

			// Links categories
			$cat_count = $this->import_categories();
			$this->plugin->display_admin_notice(sprintf(_n('%d links category imported', '%d links categories imported', $cat_count, 'fg-joomla-to-wordpress'), $cat_count));

			$this->plugin->log(__('Importing web links...', 'fg-joomla-to-wordpress'));
			
			$links = $this->get_weblinks();
			$weblinks_count = count($links);
			foreach ( $links as $link ) {

				// Categories
				$category = $link['catid'];
				if ( array_key_exists($category, $this->plugin->imported_categories) ) {
					$cat_id = $this->plugin->imported_categories[$category];
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
			$this->plugin->progressbar->increment_current_count($weblinks_count);
			$this->plugin->display_admin_notice(sprintf(_n('%d web link imported', '%d web links imported', $this->links_count, 'fg-joomla-to-wordpress'), $this->links_count));
		}

		/**
		 * Get Joomla web links
		 *
		 * @return array of Links
		 */
		private function get_weblinks() {
			$links = array();

			$last_id = (int)get_option('fgj2wp_last_link_id'); // to restore the import where it left
			$prefix = $this->plugin->plugin_options['prefix'];
			if ( version_compare($this->plugin->joomla_version, '1.5', '<=') ) {
				$sql = "
					SELECT l.id, l.title, l.url, l.description, l.ordering, l.date, l.catid
					FROM ${prefix}weblinks l
					WHERE l.published = 1
					AND l.id > '$last_id'
					ORDER BY l.id
				";
			} else {
				$sql = "
					SELECT l.id, l.title, l.url, l.description, l.ordering, l.created AS date, l.catid
					FROM ${prefix}weblinks l
					WHERE l.state = 1
					AND l.id > '$last_id'
					ORDER BY l.id
				";
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
			$categories = $this->plugin->get_component_categories('com_weblinks', 'fgj2wp_last_weblink_category_id'); // Get the web links categories
			
			if ( count($categories) > 0 ) {
				$cat_count = $this->plugin->insert_categories($categories, 'link_category', 'fgj2wp_last_weblink_category_id');
			}
			return $cat_count;
		}

		/**
		 * Get the WordPress database info
		 * 
		 * @param string $database_info Database info
		 * @return string Database info
		 */
		public function get_database_info($database_info) {
			$links_count = $this->count_links();
			if ( $links_count > 0 ) {
				$database_info .= sprintf(_n('%d link', '%d links', $links_count, 'fg-joomla-to-wordpress'), $links_count) . "<br />";
			}
			return $database_info;
		}

	}
}
