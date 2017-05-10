<?php

/**
 * The admin-specific functionality of the plugin.
 *
 * @link       https://wordpress.org/plugins/fg-joomla-to-wordpress/
 * @since      2.0.0
 *
 * @package    FG_Joomla_to_WordPress
 * @subpackage FG_Joomla_to_WordPress/admin
 */

if ( !class_exists('FG_Joomla_to_WordPress_Admin', FALSE) ) {

	/**
	 * The admin-specific functionality of the plugin.
	 *
	 * @package    FG_Joomla_to_WordPress
	 * @subpackage FG_Joomla_to_WordPress/admin
	 * @author     Frédéric GILLES
	 */
	class FG_Joomla_to_WordPress_Admin extends WP_Importer {

		/**
		 * The ID of this plugin.
		 *
		 * @since    2.0.0
		 * @access   private
		 * @var      string    $plugin_name    The ID of this plugin.
		 */
		private $plugin_name;

		/**
		 * The version of this plugin.
		 *
		 * @since    2.0.0
		 * @access   private
		 * @var      string    $version    The current version of this plugin.
		 */
		private $version;					// Plugin version

		public $joomla_version;
		public $plugin_options;				// Plug-in options
		public $progressbar;
		public $imported_categories = array();
		public $chunks_size = 10;
		public $posts_count = 0;			// Number of imported posts
		public $media_count = 0;			// Number of imported medias
		public $tags_count = 0;				// Number of imported tags

		protected $post_type = 'post';		// post or page
		protected $faq_url;					// URL of the FAQ page
		protected $notices = array();		// Error or success messages
		
		private $log_file;
		private $log_file_url;
		private $test_antiduplicate = FALSE;
		private $imported_tags = array();

		/**
		 * Initialize the class and set its properties.
		 *
		 * @since    2.0.0
		 * @param    string    $plugin_name       The name of this plugin.
		 * @param    string    $version           The version of this plugin.
		 */
		public function __construct( $plugin_name, $version ) {

			$this->plugin_name = $plugin_name;
			$this->version = $version;
			$this->faq_url = 'https://wordpress.org/plugins/fg-joomla-to-wordpress/faq/';
			$upload_dir = wp_upload_dir();
			$this->log_file = $upload_dir['basedir'] . '/' . $this->plugin_name . '.log';
			$this->log_file_url = $upload_dir['baseurl'] . '/' . $this->plugin_name . '.log';

			// Progress bar
			$this->progressbar = new FG_Joomla_to_WordPress_ProgressBar($this);

		}

		/**
		 * The name of the plugin used to uniquely identify it within the context of
		 * WordPress and to define internationalization functionality.
		 *
		 * @since     2.0.0
		 * @return    string    The name of the plugin.
		 */
		public function get_plugin_name() {
			return $this->plugin_name;
		}

		/**
		 * Register the stylesheets for the admin area.
		 *
		 * @since    2.0.0
		 */
		public function enqueue_styles() {

			wp_enqueue_style( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'css/fg-joomla-to-wordpress-admin.css', array(), $this->version, 'all' );

		}

		/**
		 * Register the JavaScript for the admin area.
		 *
		 * @since    2.0.0
		 */
		public function enqueue_scripts() {

			wp_enqueue_script( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'js/fg-joomla-to-wordpress-admin.js', array( 'jquery', 'jquery-ui-progressbar' ), $this->version, FALSE );
			wp_localize_script( $this->plugin_name, 'objectL10n', array(
				'delete_imported_data_confirmation_message' => __( 'All new imported data will be deleted from WordPress.', 'fg-joomla-to-wordpress' ),
				'delete_all_confirmation_message' => __( 'All content will be deleted from WordPress.', 'fg-joomla-to-wordpress' ),
				'delete_no_answer_message' => __( 'Please select a remove option.', 'fg-joomla-to-wordpress' ),
				'import_complete' => __( 'IMPORT COMPLETE', 'fg-joomla-to-wordpress' ),
				'content_removed_from_wordpress' => __( 'Content removed from WordPress', 'fg-joomla-to-wordpress' ),
				'settings_saved' => __( 'Settings saved', 'fg-joomla-to-wordpress' ),
				'importing' => __( 'Importing…', 'fg-joomla-to-wordpress' ),
				'import_stopped_by_user' => __( 'IMPORT STOPPED BY USER', 'fg-joomla-to-wordpress' ),
				'internal_links_modified' => __( 'Internal links modified', 'fg-joomla-to-wordpress' ),
			) );
			wp_localize_script( $this->plugin_name, 'objectPlugin', array(
				'log_file_url' => $this->log_file_url,
				'progress_url' => $this->progressbar->get_url(),
			));

		}

		/**
		 * Initialize the plugin
		 */
		public function init() {
			register_importer('fgj2wp', __('Joomla (FG)', 'fg-joomla-to-wordpress'), __('Import categories, articles and medias (images, attachments) from a Joomla database into WordPress.', 'fg-joomla-to-wordpress'), array($this, 'importer'));
		}

		/**
		 * Display the stored notices
		 */
		public function display_notices() {
			foreach ( $this->notices as $notice ) {
				echo '<div class="' . $notice['level'] . '"><p>[' . $this->plugin_name . '] ' . $notice['message'] . "</p></div>\n";
			}
		}
		
		/**
		 * Write a message in the log file
		 * 
		 * @param string $message
		 */
		public function log($message) {
			file_put_contents($this->log_file, "$message\n", FILE_APPEND);
		}
		
		/**
		 * Store an admin notice
		 */
		public function display_admin_notice( $message )	{
			$this->notices[] = array('level' => 'updated', 'message' => $message);
			error_log('[INFO] [' . $this->plugin_name . '] ' . $message);
			$this->log($message);
		}

		/**
		 * Store an admin error
		 */
		public function display_admin_error( $message )	{
			$this->notices[] = array('level' => 'error', 'message' => $message);
			error_log('[ERROR] [' . $this->plugin_name . '] ' . $message);
			$this->log('[ERROR] ' . $message);
		}

		/**
		 * Store an admin warning
		 */
		public function display_admin_warning( $message )	{
			$this->notices[] = array('level' => 'error', 'message' => $message);
			error_log('[WARNING] [' . $this->plugin_name . '] ' . $message);
			$this->log('[WARNING] ' . $message);
		}

		/**
		 * Run the importer
		 */
		public function importer() {
			$feasible_actions = array(
				'empty',
				'save',
				'test_database',
				'test_ftp',
				'import',
				'modify_links',
			);
			$action = '';
			foreach ( $feasible_actions as $potential_action ) {
				if ( isset($_POST[$potential_action]) ) {
					$action = $potential_action;
					break;
				}
			}
			$this->dispatch($action);
			$this->display_admin_page(); // Display the admin page
		}
		
		/**
		 * Import triggered by AJAX
		 *
		 * @since    3.0.0
		 */
		public function ajax_importer() {
			$action = filter_input(INPUT_POST, 'plugin_action', FILTER_SANITIZE_STRING);
			
			if ( $action == 'update_wordpress_info') {
				// Update the WordPress database info
				echo $this->get_database_info();
				
			} else {
				ini_set('display_errors', TRUE); // Display the errors that may happen (ex: Allowed memory size exhausted)
				
				// Empty the log file if we empty the WordPress content
				if ( ($action == 'empty') || (($action == 'import') && filter_input(INPUT_POST, 'automatic_empty', FILTER_VALIDATE_BOOLEAN)) ) {
					file_put_contents($this->log_file, '');
				}

				$time_start = date('Y-m-d H:i:s');
				$this->display_admin_notice("=== START $action $time_start ===");
				$result = $this->dispatch($action);
				if ( !empty($result) ) {
					echo json_encode($result); // Send the result to the AJAX caller
				}
				$time_end = date('Y-m-d H:i:s');
				$this->display_admin_notice("=== END $action $time_end ===\n");
			}
			wp_die();
		}
		
		/**
		 * Dispatch the actions
		 * 
		 * @param string $action Action
		 * @return object Result to return to the caller
		 */
		public function dispatch($action) {
			set_time_limit(7200); // Timeout = 2 hours

			// Suspend the cache during the migration to avoid exhausted memory problem
			wp_suspend_cache_addition(TRUE);
			wp_suspend_cache_invalidation(TRUE);

			// Default values
			$this->plugin_options = array(
				'automatic_empty'		=> 0,
				'url'					=> null,
				'hostname'				=> 'localhost',
				'port'					=> 3306,
				'database'				=> null,
				'username'				=> 'root',
				'password'				=> '',
				'prefix'				=> 'jos_',
				'introtext'				=> 'in_content',
				'archived_posts'		=> 'not_imported',
				'skip_media'			=> 0,
				'featured_image'		=> 'fulltext',
				'only_featured_image'	=> 0,
				'remove_first_image'	=> 0,
				'remove_accents'		=> 0,
				'import_external'		=> 0,
				'import_duplicates'		=> 0,
				'force_media_import'	=> 0,
				'meta_keywords_in_tags'	=> 0,
				'import_as_pages'		=> 0,
				'timeout'				=> 5,
				'logger_autorefresh'	=> 1,
			);
			$options = get_option('fgj2wp_options');
			if ( is_array($options) ) {
				$this->plugin_options = array_merge($this->plugin_options, $options);
			}

			// Check if the upload directory is writable
			$upload_dir = wp_upload_dir();
			if ( !is_writable($upload_dir['basedir']) ) {
				$this->display_admin_error(__('The wp-content directory must be writable.', 'fg-joomla-to-wordpress'));
			}

			// Requires at least WordPress 4.5
			if ( version_compare(get_bloginfo('version'), '4.5', '<') ) {
				$this->display_admin_error(sprintf(__('WordPress 4.5+ is required. Please <a href="%s">update WordPress</a>.', 'fg-joomla-to-wordpress'), admin_url('update-core.php')));
			}
			
			elseif ( !empty($action) ) {
				switch($action) {
					
					// Delete content
					case 'empty':
						if ( check_admin_referer( 'empty', 'fgj2wp_nonce' ) ) { // Security check
							if ($this->empty_database($_POST['empty_action'])) { // Empty WP database
								$this->display_admin_notice(__('WordPress content removed', 'fg-joomla-to-wordpress'));
							} else {
								$this->display_admin_error(__('Couldn\'t remove content', 'fg-joomla-to-wordpress'));
							}
							wp_cache_flush();
						}
						break;
					
					// Save database options
					case 'save':
						$this->save_plugin_options();
						$this->display_admin_notice(__('Settings saved', 'fg-joomla-to-wordpress'));
						break;
					
					// Test the database connection
					case 'test_database':
						// Save database options
						$this->save_plugin_options();

						if ( check_admin_referer( 'parameters_form', 'fgj2wp_nonce' ) ) { // Security check
							if ( $this->test_database_connection() ) {
								return array('status' => 'OK', 'message' => __('Connection successful', 'fg-joomla-to-wordpress'));
							} else {
								return array('status' => 'Error', 'message' => __('Connection failed', 'fg-joomla-to-wordpress'));
							}
						}
						break;
					
					// Run the import
					case 'import':
						// Save database options
						$this->save_plugin_options();

						if ( check_admin_referer( 'parameters_form', 'fgj2wp_nonce' ) ) { // Security check
							if ( $this->test_database_connection() ) {
								// Automatic empty
								if ( $this->plugin_options['automatic_empty'] ) {
									if ($this->empty_database('all')) {
										$this->display_admin_notice(__('WordPress content removed', 'fg-joomla-to-wordpress'));
									} else {
										$this->display_admin_error(__('Couldn\'t remove content', 'fg-joomla-to-wordpress'));
									}
									wp_cache_flush();
								}

								// Import content
								$this->import();
							}
						}
						break;
					
					// Stop the import
					case 'stop_import':
						$this->stop_import();
						break;
					
					// Modify internal links
					case 'modify_links':
						if ( check_admin_referer( 'modify_links', 'fgj2wp_nonce' ) ) { // Security check
							$result = $this->modify_links();
							$this->display_admin_notice(sprintf(_n('%d internal link modified', '%d internal links modified', $result['links_count'], 'fg-joomla-to-wordpress'), $result['links_count']));
						}
						break;
					
					default:
						// Do other actions
						do_action('fgj2wp_dispatch', $action);
				}
			}
		}

		/**
		 * Display the admin page
		 * 
		 */
		private function display_admin_page() {
			$data = $this->plugin_options;

			$data['title'] = __('Import Joomla (FG)', 'fg-joomla-to-wordpress');
			$data['description'] = __('This plugin will import sections, categories, posts, medias (images, attachments) and web links from a Joomla database into WordPress.<br />Compatible with Joomla versions 1.5 to 3.6.', 'fg-joomla-to-wordpress');
			$data['description'] .= "<br />\n" . sprintf(__('For any issue, please read the <a href="%s" target="_blank">FAQ</a> first.', 'fg-joomla-to-wordpress'), $this->faq_url);
			$data['database_info'] = $this->get_database_info();

			// Hook for modifying the admin page
			$data = apply_filters('fgj2wp_pre_display_admin_page', $data);

			// Load the CSS and Javascript
			$this->enqueue_styles();
			$this->enqueue_scripts();
			
			include plugin_dir_path( dirname( __FILE__ ) ) . 'admin/partials/admin-display.php';

			// Hook for doing other actions after displaying the admin page
			do_action('fgj2wp_post_display_admin_page');

		}

		/**
		 * Get the WordPress database info
		 * 
		 * @return string Database info
		 */
		private function get_database_info() {
			$posts_count = $this->count_posts('post');
			$pages_count = $this->count_posts('page');
			$media_count = $this->count_posts('attachment');
			$cat_count = wp_count_terms('category', array('hide_empty' => 0));
			$tags_count = wp_count_terms('post_tag', array('hide_empty' => 0));

			$database_info =
				sprintf(_n('%d category', '%d categories', $cat_count, 'fg-joomla-to-wordpress'), $cat_count) . "<br />" .
				sprintf(_n('%d post', '%d posts', $posts_count, 'fg-joomla-to-wordpress'), $posts_count) . "<br />" .
				sprintf(_n('%d page', '%d pages', $pages_count, 'fg-joomla-to-wordpress'), $pages_count) . "<br />" .
				sprintf(_n('%d media', '%d medias', $media_count, 'fg-joomla-to-wordpress'), $media_count) . "<br />" .
				sprintf(_n('%d tag', '%d tags', $tags_count, 'fg-joomla-to-wordpress'), $tags_count) . "<br />";
			$database_info = apply_filters('fgj2wp_get_database_info', $database_info);
			return $database_info;
		}
		
		/**
		 * Count the number of posts for a post type
		 * @param string $post_type
		 */
		public function count_posts($post_type) {
			$count = 0;
			$excluded_status = array('trash', 'auto-draft');
			$tab_count = wp_count_posts($post_type);
			foreach ( $tab_count as $key => $value ) {
				if ( !in_array($key, $excluded_status) ) {
					$count += $value;
				}
			}
			return $count;
		}

		/**
		 * Add an help tab
		 * 
		 */
		public function add_help_tab() {
			$screen = get_current_screen();
			$screen->add_help_tab(array(
				'id'	=> 'fgj2wp_help_instructions',
				'title'	=> __('Instructions', 'fg-joomla-to-wordpress'),
				'content'	=> '',
				'callback' => array($this, 'help_instructions'),
			));
			$screen->add_help_tab(array(
				'id'	=> 'fgj2wp_help_options',
				'title'	=> __('Options', 'fg-joomla-to-wordpress'),
				'content'	=> '',
				'callback' => array($this, 'help_options'),
			));
			$screen->set_help_sidebar('<a href="' . $this->faq_url . '" target="_blank">' . __('FAQ', 'fg-joomla-to-wordpress') . '</a>');
		}

		/**
		 * Instructions help screen
		 * 
		 * @return string Help content
		 */
		public function help_instructions() {
			include plugin_dir_path( dirname( __FILE__ ) ) . 'admin/partials/help-instructions.tpl.php';
		}

		/**
		 * Options help screen
		 * 
		 * @return string Help content
		 */
		public function help_options() {
			include plugin_dir_path( dirname( __FILE__ ) ) . 'admin/partials/help-options.tpl.php';
		}

		/**
		 * Open the connection on Joomla database
		 *
		 * return boolean Connection successful or not
		 */
		public function joomla_connect() {
			global $joomla_db;

			if ( !class_exists('PDO') ) {
				$this->display_admin_error(__('PDO is required. Please enable it.', 'fg-joomla-to-wordpress'));
				return FALSE;
			}
			try {
				$joomla_db = new PDO('mysql:host=' . $this->plugin_options['hostname'] . ';port=' . $this->plugin_options['port'] . ';dbname=' . $this->plugin_options['database'], $this->plugin_options['username'], $this->plugin_options['password'], array(PDO::MYSQL_ATTR_INIT_COMMAND => 'SET NAMES \'UTF8\''));
				if ( defined('WP_DEBUG') && WP_DEBUG ) {
					$joomla_db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION); // Display SQL errors
				}
			} catch ( PDOException $e ) {
				$this->display_admin_error(__('Couldn\'t connect to the Joomla database. Please check your parameters. And be sure the WordPress server can access the Joomla database.', 'fg-joomla-to-wordpress') . "<br />\n" . $e->getMessage() . "<br />\n" . sprintf(__('Please read the <a href="%s" target="_blank">FAQ for the solution</a>.', 'fg-joomla-to-wordpress'), $this->faq_url));
				return FALSE;
			}
			$this->joomla_version = $this->joomla_version();
			return TRUE;
		}

		/**
		 * Execute a SQL query on the Joomla database
		 * 
		 * @param string $sql SQL query
		 * @param bool $display_error Display the error?
		 * @return array Query result
		 */
		public function joomla_query($sql, $display_error = TRUE) {
			global $joomla_db;
			$result = array();

			try {
				$query = $joomla_db->query($sql, PDO::FETCH_ASSOC);
				if ( is_object($query) ) {
					foreach ( $query as $row ) {
						$result[] = $row;
					}
				}

			} catch ( PDOException $e ) {
				if ( $display_error ) {
					$this->display_admin_error(__('Error:', 'fg-joomla-to-wordpress') . $e->getMessage());
				}
			}
			return $result;
		}

		/**
		 * Delete all posts, medias and categories from the database
		 *
		 * @param string $action	imported = removes only new imported data
		 * 							all = removes all
		 * @return boolean
		 */
		private function empty_database($action) {
			global $wpdb;
			$result = TRUE;

			$wpdb->show_errors();

			// Hook for doing other actions before emptying the database
			do_action('fgj2wp_pre_empty_database', $action);

			$sql_queries = array();

			if ( $action == 'all' ) {
				// Remove all content
				$sql_queries[] = "TRUNCATE $wpdb->commentmeta";
				$sql_queries[] = "TRUNCATE $wpdb->comments";
				$sql_queries[] = "TRUNCATE $wpdb->term_relationships";
				$sql_queries[] = "TRUNCATE $wpdb->termmeta";
				$sql_queries[] = "TRUNCATE $wpdb->postmeta";
				$sql_queries[] = "TRUNCATE $wpdb->posts";
				$sql_queries[] = <<<SQL
-- Delete Terms
DELETE FROM $wpdb->terms
WHERE term_id > 1 -- non-classe
SQL;
				$sql_queries[] = <<<SQL
-- Delete Terms taxonomies
DELETE FROM $wpdb->term_taxonomy
WHERE term_id > 1 -- non-classe
SQL;
				$sql_queries[] = "ALTER TABLE $wpdb->terms AUTO_INCREMENT = 2";
				$sql_queries[] = "ALTER TABLE $wpdb->term_taxonomy AUTO_INCREMENT = 2";
				
			} else {
				
				// (Re)create a temporary table with the IDs to delete
				$sql_queries[] = <<<SQL
DROP TEMPORARY TABLE IF EXISTS {$wpdb->prefix}fg_data_to_delete;
SQL;

				$sql_queries[] = <<<SQL
CREATE TEMPORARY TABLE IF NOT EXISTS {$wpdb->prefix}fg_data_to_delete (
`id` bigint(20) unsigned NOT NULL,
PRIMARY KEY (`id`)
) DEFAULT CHARSET=utf8;
SQL;
				
				// Insert the imported posts IDs in the temporary table
				$sql_queries[] = <<<SQL
INSERT IGNORE INTO {$wpdb->prefix}fg_data_to_delete (`id`)
SELECT post_id FROM $wpdb->postmeta
WHERE meta_key LIKE '_fgj2wp_%'
SQL;
				
				// Delete the imported posts and related data

				$sql_queries[] = <<<SQL
-- Delete Comments and Comment metas
DELETE c, cm
FROM $wpdb->comments c
LEFT JOIN $wpdb->commentmeta cm ON cm.comment_id = c.comment_ID
INNER JOIN {$wpdb->prefix}fg_data_to_delete del
WHERE c.comment_post_ID = del.id;
SQL;

				$sql_queries[] = <<<SQL
-- Delete Term relashionships
DELETE tr
FROM $wpdb->term_relationships tr
INNER JOIN {$wpdb->prefix}fg_data_to_delete del
WHERE tr.object_id = del.id;
SQL;

				$sql_queries[] = <<<SQL
-- Delete Posts Children and Post metas
DELETE p, pm
FROM $wpdb->posts p
LEFT JOIN $wpdb->postmeta pm ON pm.post_id = p.ID
INNER JOIN {$wpdb->prefix}fg_data_to_delete del
WHERE p.post_parent = del.id
AND p.post_type != 'attachment'; -- Don't remove the old medias attached to posts
SQL;

				$sql_queries[] = <<<SQL
-- Delete Posts and Post metas
DELETE p, pm
FROM $wpdb->posts p
LEFT JOIN $wpdb->postmeta pm ON pm.post_id = p.ID
INNER JOIN {$wpdb->prefix}fg_data_to_delete del
WHERE p.ID = del.id;
SQL;

				// Truncate the temporary table
				$sql_queries[] = <<<SQL
TRUNCATE {$wpdb->prefix}fg_data_to_delete;
SQL;
				
				// Insert the imported terms IDs in the temporary table
				$sql_queries[] = <<<SQL
INSERT IGNORE INTO {$wpdb->prefix}fg_data_to_delete (`id`)
SELECT term_id FROM $wpdb->termmeta
WHERE meta_key LIKE '_fgj2wp_%'
SQL;
				
				// Delete the imported terms and related data

				$sql_queries[] = <<<SQL
-- Delete Terms, Term taxonomies and Term metas
DELETE t, tt, tm
FROM $wpdb->terms t
LEFT JOIN $wpdb->term_taxonomy tt ON tt.term_id = t.term_id
LEFT JOIN $wpdb->termmeta tm ON tm.term_id = t.term_id
INNER JOIN {$wpdb->prefix}fg_data_to_delete del
WHERE t.term_id = del.id;
SQL;

				// Truncate the temporary table
				$sql_queries[] = <<<SQL
TRUNCATE {$wpdb->prefix}fg_data_to_delete;
SQL;
				
				// Insert the imported comments IDs in the temporary table
				$sql_queries[] = <<<SQL
INSERT IGNORE INTO {$wpdb->prefix}fg_data_to_delete (`id`)
SELECT comment_id FROM $wpdb->commentmeta
WHERE meta_key LIKE '_fgj2wp_%'
SQL;
				
				// Delete the imported comments and related data
				$sql_queries[] = <<<SQL
-- Delete Comments and Comment metas
DELETE c, cm
FROM $wpdb->comments c
LEFT JOIN $wpdb->commentmeta cm ON cm.comment_id = c.comment_ID
INNER JOIN {$wpdb->prefix}fg_data_to_delete del
WHERE c.comment_ID = del.id;
SQL;

			}

			// Execute SQL queries
			if ( count($sql_queries) > 0 ) {
				foreach ( $sql_queries as $sql ) {
					$result &= $wpdb->query($sql);
				}
			}

			// Hook for doing other actions after emptying the database
			do_action('fgj2wp_post_empty_database', $action);

			// Drop the temporary table
			$wpdb->query("DROP TEMPORARY TABLE IF EXISTS {$wpdb->prefix}fg_data_to_delete;");
				
			// Reset the Joomla import counters
			update_option('fgj2wp_last_article_id', 0);
			update_option('fgj2wp_last_category_id', 0);
			update_option('fgj2wp_last_section_id', 0);

			// Re-count categories and tags items
			$this->terms_count();

			// Update cache
			$this->clean_cache();
			delete_transient('wc_count_comments');

			$this->optimize_database();

			$this->progressbar->set_total_count(0);
			
			$wpdb->hide_errors();
			return ($result !== FALSE);
		}

		/**
		 * Optimize the database
		 *
		 */
		protected function optimize_database() {
			global $wpdb;

			$sql = <<<SQL
OPTIMIZE TABLE 
`$wpdb->commentmeta`,
`$wpdb->comments`,
`$wpdb->options`,
`$wpdb->postmeta`,
`$wpdb->posts`,
`$wpdb->terms`,
`$wpdb->term_relationships`,
`$wpdb->term_taxonomy`,
`$wpdb->termmeta`
SQL;
			$wpdb->query($sql);
		}

		/**
		 * Test the database connection
		 * 
		 * @return boolean
		 */
		private function test_database_connection() {
			global $joomla_db;

			if ( $this->joomla_connect() ) {
				try {
					$prefix = $this->plugin_options['prefix'];

					// Test that the "content" table exists
					$result = $joomla_db->query("DESC ${prefix}content");
					if ( !is_a($result, 'PDOStatement') ) {
						$errorInfo = $joomla_db->errorInfo();
						throw new PDOException($errorInfo[2], $errorInfo[1]);
					}

					$this->display_admin_notice(__('Connected with success to the Joomla database', 'fg-joomla-to-wordpress'));

					do_action('fgj2wp_post_test_database_connection');

					return TRUE;

				} catch ( PDOException $e ) {
					$this->display_admin_error(__('Couldn\'t connect to the Joomla database. Please check your parameters. And be sure the WordPress server can access the Joomla database.', 'fg-joomla-to-wordpress') . "<br />\n" . $e->getMessage() . "<br />\n" . sprintf(__('Please read the <a href="%s" target="_blank">FAQ for the solution</a>.', 'fg-joomla-to-wordpress'), $this->faq_url));
					return FALSE;
				}
				$joomla_db = null;
			}
			return FALSE;
		}

		/**
		 * Test for Joomla version 1.0
		 *
		 * @return bool False if Joomla version < 1.5 (for Joomla 1.0 and Mambo)
		 */
		public function test_joomla_1_0() {
			if ( version_compare($this->joomla_version, '1.5', '<') ) {
				$this->display_admin_error(sprintf(__('Your version of Joomla (probably 1.0) is not supported by this plugin. Please consider upgrading to the <a href="%s" target="_blank">Premium version</a>.', 'fg-joomla-to-wordpress'), 'https://www.fredericgilles.net/fg-joomla-to-wordpress/'));
				// Deactivate the Joomla info feature
				remove_action('fgj2wp_post_test_database_connection', array($this, 'get_joomla_info'), 9);
				return FALSE;
			}
			return TRUE;
		}

		/**
		 * Get some Joomla information
		 *
		 */
		public function get_joomla_info() {
			$message = __('Joomla data found:', 'fg-joomla-to-wordpress') . "\n";

			// Sections
			if ( version_compare($this->joomla_version, '1.5', '<=') ) {
				$sections_count = $this->get_sections_count();
				$message .= sprintf(_n('%d section', '%d sections', $sections_count, 'fg-joomla-to-wordpress'), $sections_count) . "\n";
			}

			// Categories
			$cat_count = $this->get_categories_count();
			$message .= sprintf(_n('%d category', '%d categories', $cat_count, 'fg-joomla-to-wordpress'), $cat_count) . "\n";

			// Articles
			$posts_count = $this->get_posts_count();
			$message .= sprintf(_n('%d article', '%d articles', $posts_count, 'fg-joomla-to-wordpress'), $posts_count) . "\n";

			// Web links
			if ( $this->table_exists('weblinks') ) { // Joomla 3.4
				$weblinks_count = $this->get_weblinks_count();
				$message .= sprintf(_n('%d web link', '%d web links', $weblinks_count, 'fg-joomla-to-wordpress'), $weblinks_count) . "\n";
			}

			$message = apply_filters('fgj2wp_pre_display_joomla_info', $message);

			$this->display_admin_notice($message);
		}

		/**
		 * Get the number of Joomla categories
		 * 
		 * @return int Number of categories
		 */
		private function get_categories_count() {
			$prefix = $this->plugin_options['prefix'];
			if ( version_compare($this->joomla_version, '1.5', '<=') ) {
				$sql = "
					SELECT COUNT(*) AS nb
					FROM ${prefix}categories c
					INNER JOIN ${prefix}sections AS s ON s.id = c.section
				";
			} else { // Joomla > 1.5
				$sql = "
					SELECT COUNT(*) AS nb
					FROM ${prefix}categories c
					WHERE c.extension = 'com_content'
				";
			}
			$result = $this->joomla_query($sql);
			$cat_count = isset($result[0]['nb'])? $result[0]['nb'] : 0;
			return $cat_count;
		}

		/**
		 * Get the number of Joomla sections
		 * 
		 * @return int Number of sections
		 */
		private function get_sections_count() {
			$prefix = $this->plugin_options['prefix'];
			$sql = "
				SELECT COUNT(*) AS nb
				FROM ${prefix}sections s
			";
			$result = $this->joomla_query($sql);
			$sections_count = isset($result[0]['nb'])? $result[0]['nb'] : 0;
			return $sections_count;
		}

		/**
		 * Get the number of Joomla categories
		 * 
		 * @param string $component Component name
		 * @return int Number of categories
		 */
		private function get_component_categories_count($component) {
			$prefix = $this->plugin_options['prefix'];
			if ( version_compare($this->joomla_version, '1.5', '<=') ) {
				$extension_field = 'c.section';
			} else {
				$extension_field = 'c.extension';
			}
			$sql = "
				SELECT COUNT(*) AS nb
				FROM ${prefix}categories c
				WHERE $extension_field = '$component'
			";
			$result = $this->joomla_query($sql);
			$cat_count = isset($result[0]['nb'])? $result[0]['nb'] : 0;
			return $cat_count;
		}

		/**
		 * Get the number of Joomla articles
		 * 
		 * @return int Number of articles
		 */
		private function get_posts_count() {
			$prefix = $this->plugin_options['prefix'];
			$archived_post_criteria = '';
			if ( $this->plugin_options['archived_posts'] == 'not_imported' ) {
				$archived_post_criteria = 'AND c.state != 2';
			}
			$sql = "
				SELECT COUNT(*) AS nb
				FROM ${prefix}content c
				WHERE c.state >= -1 -- don't get the trash
				$archived_post_criteria
			";
			$result = $this->joomla_query($sql);
			$posts_count = isset($result[0]['nb'])? $result[0]['nb'] : 0;
			return $posts_count;
		}

		/**
		 * Get the number of Joomla web links
		 * 
		 * @return int Number of web links
		 */
		private function get_weblinks_count() {
			$prefix = $this->plugin_options['prefix'];
			if ( version_compare($this->joomla_version, '1.5', '<=') ) {
				$published_field = 'published';
			} else {
				$published_field = 'state';
			}
			$sql = "
				SELECT COUNT(*) AS nb
				FROM ${prefix}weblinks l
				WHERE l.$published_field = 1
			";
			$result = $this->joomla_query($sql);
			$weblinks_count = isset($result[0]['nb'])? $result[0]['nb'] : 0;
			return $weblinks_count;
		}

		/**
		 * Save the plugin options
		 *
		 */
		public function save_plugin_options() {
			$this->plugin_options = array_merge($this->plugin_options, $this->validate_form_info());
			update_option('fgj2wp_options', $this->plugin_options);

			// Hook for doing other actions after saving the options
			do_action('fgj2wp_post_save_plugin_options');
		}

		/**
		 * Validate POST info
		 *
		 * @return array Form parameters
		 */
		private function validate_form_info() {
			// Add http:// before the URL if it is missing
			$url = filter_input(INPUT_POST, 'url', FILTER_SANITIZE_URL);
			if ( !empty($url) && (preg_match('#^https?://#', $url) == 0) ) {
				$url = 'http://' . $url;
			}
			return array(
				'automatic_empty'		=> filter_input(INPUT_POST, 'automatic_empty', FILTER_VALIDATE_BOOLEAN),
				'url'					=> $url,
				'hostname'				=> filter_input(INPUT_POST, 'hostname', FILTER_SANITIZE_STRING),
				'port'					=> filter_input(INPUT_POST, 'port', FILTER_SANITIZE_NUMBER_INT),
				'database'				=> filter_input(INPUT_POST, 'database', FILTER_SANITIZE_STRING),
				'username'				=> filter_input(INPUT_POST, 'username'),
				'password'				=> filter_input(INPUT_POST, 'password'),
				'prefix'				=> filter_input(INPUT_POST, 'prefix', FILTER_SANITIZE_STRING),
				'introtext'				=> filter_input(INPUT_POST, 'introtext', FILTER_SANITIZE_STRING),
				'archived_posts'		=> filter_input(INPUT_POST, 'archived_posts', FILTER_SANITIZE_STRING),
				'skip_media'			=> filter_input(INPUT_POST, 'skip_media', FILTER_VALIDATE_BOOLEAN),
				'featured_image'		=> filter_input(INPUT_POST, 'featured_image', FILTER_SANITIZE_STRING),
				'only_featured_image'	=> filter_input(INPUT_POST, 'only_featured_image', FILTER_VALIDATE_BOOLEAN),
				'remove_first_image'	=> filter_input(INPUT_POST, 'remove_first_image', FILTER_VALIDATE_BOOLEAN),
				'remove_accents'		=> filter_input(INPUT_POST, 'remove_accents', FILTER_VALIDATE_BOOLEAN),
				'import_external'		=> filter_input(INPUT_POST, 'import_external', FILTER_VALIDATE_BOOLEAN),
				'import_duplicates'		=> filter_input(INPUT_POST, 'import_duplicates', FILTER_VALIDATE_BOOLEAN),
				'force_media_import'	=> filter_input(INPUT_POST, 'force_media_import', FILTER_VALIDATE_BOOLEAN),
				'meta_keywords_in_tags'	=> filter_input(INPUT_POST, 'meta_keywords_in_tags', FILTER_VALIDATE_BOOLEAN),
				'import_as_pages'		=> filter_input(INPUT_POST, 'import_as_pages', FILTER_VALIDATE_BOOLEAN),
				'timeout'				=> filter_input(INPUT_POST, 'timeout', FILTER_SANITIZE_NUMBER_INT),
				'logger_autorefresh'	=> filter_input(INPUT_POST, 'logger_autorefresh', FILTER_VALIDATE_BOOLEAN),
			);
		}

		/**
		 * Import
		 *
		 */
		private function import() {
			if ( $this->joomla_connect() ) {

				$time_start = microtime(TRUE);

				update_option('fgj2wp_stop_import', FALSE, FALSE); // Reset the stop import action
				
				// To solve the issue of links containing ":" in multisite mode
				kses_remove_filters();

				// Check prerequesites before the import
				$do_import = apply_filters('fgj2wp_pre_import_check', TRUE);
				if ( !$do_import) {
					return;
				}

				$total_elements_count = $this->get_total_elements_count();
				$this->progressbar->set_total_count($total_elements_count);
				
				$this->post_type = ($this->plugin_options['import_as_pages'] == 1) ? 'page' : 'post';

				// Hook for doing other actions before the import
				do_action('fgj2wp_pre_import');

				// Categories
				if ( !isset($this->premium_options['skip_categories']) || !$this->premium_options['skip_categories'] ) {
					$cat_count = $this->import_categories();
					$this->display_admin_notice(sprintf(_n('%d category imported', '%d categories imported', $cat_count, 'fg-joomla-to-wordpress'), $cat_count));
				}

				// Set the list of previously imported categories
				$this->get_imported_categories();
				
				if ( !isset($this->premium_options['skip_articles']) || !$this->premium_options['skip_articles'] ) {
					// Posts and medias
					if ( !$this->import_posts() ) { // Anti-duplicate
						return;
					}
					switch ($this->post_type) {
						case 'page':
							$this->display_admin_notice(sprintf(_n('%d page imported', '%d pages imported', $this->posts_count, 'fg-joomla-to-wordpress'), $this->posts_count));
							break;
						case 'post':
						default:
							$this->display_admin_notice(sprintf(_n('%d post imported', '%d posts imported', $this->posts_count, 'fg-joomla-to-wordpress'), $this->posts_count));
					}
					$this->display_admin_notice(sprintf(_n('%d media imported', '%d medias imported', $this->media_count, 'fg-joomla-to-wordpress'), $this->media_count));

					// Tags
					if ($this->post_type == 'post') {
						if ( $this->plugin_options['meta_keywords_in_tags'] ) {
							$tags_count = count($this->imported_tags);
							$this->display_admin_notice(sprintf(_n('%d tag imported', '%d tags imported', $tags_count, 'fg-joomla-to-wordpress'), $tags_count));
						}
					}
				}
				if ( !$this->import_stopped() ) {
					// Hook for doing other actions after the import
					do_action('fgj2wp_post_import');
				}

				// Hook for other notices
				do_action('fgj2wp_import_notices');

				// Debug info
				if ( defined('WP_DEBUG') && WP_DEBUG ) {
					$this->display_admin_notice(sprintf("Memory used: %s bytes<br />\n", number_format(memory_get_usage())));
					$time_end = microtime(TRUE);
					$this->display_admin_notice(sprintf("Duration: %d sec<br />\n", $time_end - $time_start));
				}

				if ( $this->import_stopped() ) {
					
					// Import stopped by the user
					$this->display_admin_notice("IMPORT STOPPED BY USER");
					
				} else {
					// Import complete
					$this->display_admin_notice(__("Don't forget to modify internal links.", 'fg-joomla-to-wordpress'));
					$this->display_admin_notice("IMPORT COMPLETE");
				}
				
				wp_cache_flush();
			}
		}

		/**
		 * Get the number of elements to import
		 * 
		 * @return int Number of elements to import
		 */
		private function get_total_elements_count() {
			$count = 0;
			
			if ( !isset($this->premium_options['skip_categories']) || !$this->premium_options['skip_categories'] ) {
				// Sections
				if ( version_compare($this->joomla_version, '1.5', '<=') ) {
					$count += $this->get_sections_count();
				}

				// Categories
				$count += $this->get_categories_count();
			}

			// Articles
			if ( !isset($this->premium_options['skip_articles']) || !$this->premium_options['skip_articles'] ) {
				$count += $this->get_posts_count();
			}

			// Web links
			if ( !isset($this->premium_options['skip_weblinks']) || !$this->premium_options['skip_weblinks'] ) {
				if ( $this->table_exists('weblinks') ) { // Joomla 3.4
					$count += $this->get_weblinks_count();
					$count += $this->get_component_categories_count('com_weblinks');
				}
			}
			$count = apply_filters('fgj2wp_get_total_elements_count', $count);
			
			return $count;
		}
		
		/**
		 * Import categories
		 *
		 * @return int Number of categories imported
		 */
		private function import_categories() {
			$cat_count = 0;
			$taxonomy = 'category';
			$all_categories = array();
			
			if ( $this->import_stopped() ) {
				return 0;
			}
			
			$this->log(__('Importing categories...', 'fg-joomla-to-wordpress'));
			
			// Joomla sections (Joomla version ≤ 1.5)
			if ( version_compare($this->joomla_version, '1.5', '<=') ) {
				do {
					if ( $this->import_stopped() ) {
						break;
					}
					$sections = $this->get_sections($this->chunks_size);
					$all_categories = array_merge($all_categories, $sections);
					// Insert the sections
					$cat_count += $this->insert_categories($sections, $taxonomy, 'fgj2wp_last_section_id');
				} while ( ($sections != null) && (count($sections) > 0) );
			}
			
			do {
				if ( $this->import_stopped() ) {
					break;
				}
				$categories = $this->get_categories($this->chunks_size); // Get the Joomla categories
				
				if ( ($categories != null) && (count($categories) > 0) ) {
					$all_categories = array_merge($all_categories, $categories);
					// Insert the categories
					$cat_count += $this->insert_categories($categories);
				}
			} while ( ($categories != null) && (count($categories) > 0) );
			
			$all_categories = apply_filters('fgj2wp_import_categories', $all_categories);
			
			if ( !$this->import_stopped() ) {
				// Hook after importing all the categories
				do_action('fgj2wp_post_import_categories', $all_categories, $taxonomy);
			}

			return $cat_count;
		}
		
		/**
		 * Insert a list of categories in the database
		 * 
		 * @param array $categories List of categories
		 * @param string $taxonomy Taxonomy
		 * @param string $last_category_metakey Last category meta key
		 * @return int Number of inserted categories
		 */
		public function insert_categories($categories, $taxonomy='category', $last_category_metakey='fgj2wp_last_category_id') {
			$cat_count = 0;
			$processed_cat_count = count($categories);
			$term_metakey = '_fgj2wp_old_category_id';
			
			// Set the list of previously imported categories
			$this->get_imported_categories();
			
			$terms = array();
			if ( $taxonomy == 'category') {
				$terms[] = '1'; // unclassified category
			}
			
			foreach ( $categories as $category ) {

				$category_id = $category['id'];

				// Check if the category is already imported
				if ( array_key_exists($category_id, $this->imported_categories) ) {
					// Prevent the process to hang if the categories counter has been resetted
					$category_id_without_prefix = preg_replace('/^(\D*)/', '', $category_id);
					update_option($last_category_metakey, $category_id_without_prefix);

					continue; // Do not import already imported category
				}
				
				$parent_id = isset($category['parent_id']) && isset($this->imported_categories[$category['parent_id']])? $this->imported_categories[$category['parent_id']]: '';
				
				// If the slug is a date, get the title instead
				if ( preg_match('/^\d{4}-\d{2}-\d{2}-\d{2}-\d{2}-\d{2}$/', $category['name']) ) {
					$slug = $category['title'];
				} else {
					$slug = $category['name'];
				}
				
				// Insert the category
				$new_category = array(
					'cat_name' 				=> $category['title'],
					'category_description'	=> isset($category['description'])? $category['description']: '',
					'category_nicename'		=> $slug,
					'taxonomy'				=> $taxonomy,
					'category_parent'		=> $parent_id,
				);

				// Hook before inserting the category
				$new_category = apply_filters('fgj2wp_pre_insert_category', $new_category, $category);
				
				$new_cat_id = wp_insert_category($new_category, TRUE);
				
				// Store the last ID to resume the import where it left off
				$category_id_without_prefix = preg_replace('/^(\D*)/', '', $category_id);
				update_option($last_category_metakey, $category_id_without_prefix);
				
				if ( is_wp_error($new_cat_id) ) {
					if ( isset($new_cat_id->error_data['term_exists']) ) {
						// Store the Joomla category ID
						add_term_meta($new_cat_id->error_data['term_exists'], $term_metakey, $category_id, FALSE);
					}
					continue;
				}
				$cat_count++;
				$terms[] = $new_cat_id;
				$this->imported_categories[$category_id] = $new_cat_id;

				// Store the Joomla category ID
				add_term_meta($new_cat_id, $term_metakey, $category_id, TRUE);
				
				// Hook after inserting the category
				do_action('fgj2wp_post_insert_category', $new_cat_id, $category);
			}
			
			$this->progressbar->increment_current_count($processed_cat_count);
			
			// Update cache
			if ( !empty($terms) ) {
				wp_update_term_count_now($terms, $taxonomy);
				$this->clean_cache($terms, $taxonomy);
			}
			
			return $cat_count;
		}

		/**
		 * Update the categories hierarchy
		 * 
		 * @since 3.23.0
		 * 
		 * @param array $categories Categories
		 * @param string $taxonomy Taxonomy
		 * @param string $language Language code
		 */
		public function update_categories_hierarchy($categories, $taxonomy='category', $language='') {
			foreach ( $categories as $category ) {
				if ( !empty($category['parent_id']) ) {
					$joomla_category_id = $category['id'];
					$joomla_parent_category_id = $category['parent_id'];
					if ( !empty($language) ) {
						$joomla_category_id .= '-' . $language;
						$joomla_parent_category_id .= '-' . $language;
					}
					// Parent category
					if ( isset($this->imported_categories[$joomla_category_id]) && isset($this->imported_categories[$joomla_parent_category_id]) ) {
						$cat_id = $this->imported_categories[$joomla_category_id];
						$parent_cat_id = $this->imported_categories[$joomla_parent_category_id];
						wp_update_term($cat_id, $taxonomy, array('parent' => $parent_cat_id));
					}
				}
			}
		}
		
		/**
		 * Clean the cache
		 * 
		 */
		public function clean_cache($terms=array(), $taxonomy='category') {
			delete_option($taxonomy . '_children');
			clean_term_cache($terms, $taxonomy);
		}

		/**
		 * Import posts
		 *
		 * @param bool $test_mode Test mode active: import only one post
		 * @return bool Import successful or not
		 */
		private function import_posts($test_mode = FALSE) {
			$step = $test_mode? 1 : $this->chunks_size; // to limit the results

			$this->log(__('Importing posts...', 'fg-joomla-to-wordpress'));
			
			// Hook for doing other actions before the import
			do_action('fgj2wp_pre_import_posts');

			do {
				if ( $this->import_stopped() ) {
					break;
				}
				$posts = $this->get_posts($step); // Get the Joomla posts
				$posts_count = count($posts);
				
				if ( is_array($posts) ) {
					foreach ( $posts as $post ) {
						if ( $this->import_post($post) === FALSE ) {
							return FALSE;
						}
					}
				}
				$this->progressbar->increment_current_count($posts_count);
			} while ( ($posts != null) && ($posts_count > 0) && !$test_mode);

			if ( !$this->import_stopped() ) {
				// Hook for doing other actions after the import
				do_action('fgj2wp_post_import_posts');
			}

			return TRUE;
		}

		/**
		 * Import a post
		 * 
		 * @since 3.4.0
		 * 
		 * @param array $post Post data
		 * @param bool $test_mode Test mode active
		 * @return int new post ID | FALSE | WP_Error
		 */
		private function import_post($post, $test_mode = FALSE) {
			// Anti-duplicate
			if ( !$test_mode && !$this->test_antiduplicate ) {
				sleep(2);
				$test_post_id = $this->get_wp_post_id_from_joomla_id($post['id']);
				if ( !empty($test_post_id) ) {
					$this->display_admin_error(__('The import process is still running. Please wait before running it again.', 'fg-joomla-to-wordpress'));
					return FALSE;
				}
				$this->test_antiduplicate = TRUE;
			}

			// Hook for modifying the Joomla post before processing
			$post = apply_filters('fgj2wp_pre_process_post', $post);

			// If the slug is a date, get the title instead
			if ( preg_match('/^\d{4}-\d{2}-\d{2}-\d{2}-\d{2}-\d{2}$/', $post['alias']) ) {
				$slug = $post['title'];
			} else {
				$slug = $post['alias'];
			}

			// Date
			$post_date = ($post['date'] != '0000-00-00 00:00:00')? $post['date']: $post['modified'];

			// Medias
			$post_media = array();
			$featured_image_id = '';
			if ( !$this->plugin_options['skip_media'] ) {
				// Featured image
				list($featured_image_id, $post) = $this->get_and_process_featured_image($post);
				
				// Import media
				if ( !$this->plugin_options['only_featured_image'] ) {
					$result = $this->import_media_from_content($post['introtext'] . $post['fulltext'], $post_date);
					$post_media = $result['media'];
					$this->media_count += $result['media_count'];
				}
			}

			// Categories IDs
			$categories = array($post['catid']);
			// Hook for modifying the post categories
			$categories = apply_filters('fgj2wp_post_categories', $categories, $post);
			$categories_ids = array();
			foreach ( $categories as $catid ) {
				if ( array_key_exists($catid, $this->imported_categories) ) {
					$categories_ids[] = $this->imported_categories[$catid];
				}
			}
			if ( count($categories_ids) == 0 ) {
				$categories_ids[] = 1; // default category
			}

			// Define excerpt and post content
			list($excerpt, $content) = $this->set_excerpt_content($post);

			// Process content
			$excerpt = $this->process_content($excerpt, $post_media);
			$content = $this->process_content($content, $post_media);

			// Status
			switch ( $post['state'] ) {
				case 1: // published post
					$status = 'publish';
					break;
				case -1: // archived post
				case 2: // archived post in Joomla 2.5
					$status = ($this->plugin_options['archived_posts'] == 'published')? 'publish' : 'draft';
					break;
				default:
					$status = 'draft';
			}

			// Tags
			$tags = array();
			if ( $this->plugin_options['meta_keywords_in_tags'] && !empty($post['metakey']) ) {
				$tags = explode(',', $post['metakey']);
				$this->import_tags($tags, 'post_tag');
				$this->imported_tags = array_merge($this->imported_tags, $tags);
			}

			// Insert the post
			$new_post = array(
				'post_category'		=> $categories_ids,
				'post_content'		=> $content,
				'post_date'			=> $post_date,
				'post_excerpt'		=> $excerpt,
				'post_status'		=> $status,
				'post_title'		=> $post['title'],
				'post_name'			=> $slug,
				'post_type'			=> $this->post_type,
				'tags_input'		=> $tags,
				'menu_order'        => $post['ordering'],
			);

			// Hook for modifying the WordPress post just before the insert
			$new_post = apply_filters('fgj2wp_pre_insert_post', $new_post, $post);

			$new_post_id = wp_insert_post($new_post, TRUE);
			
			// Increment the Joomla last imported post ID
			update_option('fgj2wp_last_article_id', $post['id']);
			
			if ( is_wp_error($new_post_id) ) {
				$this->display_admin_error(sprintf(__('Article #%d:', 'fg-joomla-to-wordpress'), $post['id']) . ' ' . $new_post_id->get_error_message() . ' ' . $new_post_id->get_error_data());
			} else {
				// Add links between the post and its medias
				if ( !empty($featured_image_id) ) {
					$post_media[] = $featured_image_id;
				}
				$this->add_post_media($new_post_id, $new_post, $post_media, FALSE);
				
				// Set the featured image
				if ( !empty($featured_image_id) ) {
					set_post_thumbnail($new_post_id, $featured_image_id);
				}

				// Add the Joomla ID as a post meta in order to modify links after
				add_post_meta($new_post_id, '_fgj2wp_old_id', $post['id'], TRUE);

				$this->posts_count++;

				// Hook for doing other actions after inserting the post
				do_action('fgj2wp_post_insert_post', $new_post_id, $post);
			}

			return $new_post_id;
		}

		/**
		 * Import tags
		 * 
		 * @since 3.17.2
		 * 
		 * @param array $tags Tags
		 * @param string $taxonomy Taxonomy (post_tag | product_tag)
		 */
		public function import_tags($tags, $taxonomy) {
			foreach ( $tags as $tag ) {
				$new_term = wp_insert_term($tag, $taxonomy);
				if ( !is_wp_error($new_term) ) {
					add_term_meta($new_term['term_id'], '_fgj2wp_imported', 1, TRUE);
				}
			}
		}
		
		/**
		 * Determine the featured image and modify the post if needed
		 * 
		 * @since 3.4.0
		 * 
		 * @param array $post Post data
		 * @return array [Featured image ID, Post]
		 */
		public function get_and_process_featured_image($post) {
			$featured_image = '';
			$featured_image_id = 0;
			list($featured_image, $post) = apply_filters('fgj2wp_pre_import_media', array($featured_image, $post));
			
			if ( empty($featured_image) && $this->plugin_options['featured_image'] != 'none' ) {
				$featured_image = $this->get_first_image_from($post['introtext']);
				if ( empty($featured_image) ) {
					$featured_image = $this->get_first_image_from($post['fulltext']);
				}
				
				// Remove the featured image from the content
				if ( !empty($featured_image) && $this->plugin_options['remove_first_image'] ) {
					$post['introtext'] = $this->remove_image_from_content($featured_image, $post['introtext']);
					$post['fulltext'] = $this->remove_image_from_content($featured_image, $post['fulltext']);
				}
			}
			
			// Import the featured image
			if ( !empty($featured_image) ) {
				$result = $this->import_media_from_content($featured_image, $post['date']);
				if ( isset($result['media']) ) {
					foreach ( $result['media'] as $featured_image_id ) {
						$this->media_count++;
						break;
					}
				}
			}
			return array($featured_image_id, $post);
		}
		
		/**
		 * Get the first image from a content
		 * 
		 * @since 3.4.0
		 * 
		 * @param string $content
		 * @return string Featured image tag
		 */
		private function get_first_image_from($content) {
			$matches = array();
			$featured_image = '';
			
			$img_pattern = '#(<img .*?>)#i';
			if ( preg_match($img_pattern, $content, $matches) ) {
				$featured_image = $matches[1];
			}
			return $featured_image;
		}
		
		/**
		 * Remove the image from the content
		 * 
		 * @since 3.5.1
		 * 
		 * @param string $image Image to remove
		 * @param string $content Content
		 * @return string Content
		 */
		private function remove_image_from_content($image, $content) {
			$matches = array();
			$image_src = '';
			if ( preg_match('#src=["\'](.*?)["\']#', $image, $matches) ) {
				$image_src = $matches[1];
			}
			if ( !empty($image_src) ) {
				$img_pattern = '#(<img.*?src=["\']' . preg_quote($image_src) . '["\'].*?>)#i';
				$content = preg_replace($img_pattern, '', $content, 1);
			}
			return $content;
		}
		
		/**
		 * Stop the import
		 * 
		 */
		public function stop_import() {
			update_option('fgj2wp_stop_import', TRUE);
		}
		
		/**
		 * Test if the import needs to stop
		 * 
		 * @return boolean Import needs to stop or not
		 */
		public function import_stopped() {
			return get_option('fgj2wp_stop_import');
		}
		
		/**
		 * Get Joomla sections
		 *
		 * @param int $limit Number of categories max
		 * @return array of Sections
		 */
		protected function get_sections($limit=1000) {
			$sections = array();

			$prefix = $this->plugin_options['prefix'];
			$last_section_id = (int)get_option('fgj2wp_last_section_id'); // to restore the import where it left
			$sql = "
				SELECT CONCAT('s', s.id) AS id, s.title, IF(s.alias <> '', s.alias, s.name) AS name, s.description, 0 AS parent_id
				FROM ${prefix}sections s
				WHERE s.id > '$last_section_id'
				ORDER BY s.id
				LIMIT $limit
			";
			$sql = apply_filters('fgj2wp_get_sections_sql', $sql);
			$sections = $this->joomla_query($sql);
			return $sections;
		}

		/**
		 * Get Joomla categories
		 *
		 * @param int $limit Number of categories max
		 * @return array of Categories
		 */
		protected function get_categories($limit=1000) {
			$categories = array();

			$prefix = $this->plugin_options['prefix'];
			$last_category_id = (int)get_option('fgj2wp_last_category_id'); // to restore the import where it left
			
			// Hooks for adding extra cols and extra joins
			$extra_cols = apply_filters('fgj2wp_get_categories_add_extra_cols', '');
			$extra_joins = apply_filters('fgj2wp_get_categories_add_extra_joins', '');

			if ( version_compare($this->joomla_version, '1.5', '<=') ) {
				$sql = "
					SELECT c.id, c.title, IF(c.alias <> '', c.alias, c.name) AS name, c.description, CONCAT('s', s.id) AS parent_id
					$extra_cols
					FROM ${prefix}categories c
					INNER JOIN ${prefix}sections AS s ON s.id = c.section
					$extra_joins
					WHERE c.id > '$last_category_id'
					ORDER BY c.id
					LIMIT $limit
				";
			} else {
				$sql = "
					SELECT c.id, c.title, c.alias AS name, c.description, c.parent_id
					$extra_cols
					FROM ${prefix}categories c
					$extra_joins
					WHERE c.extension = 'com_content'
					AND c.id > '$last_category_id'
					ORDER BY c.id
					LIMIT $limit
				";
			}
			$sql = apply_filters('fgj2wp_get_categories_sql', $sql, $prefix);
			$categories = $this->joomla_query($sql);
			return $categories;
		}

		/**
		 * Get Joomla component categories
		 *
		 * @param string $component Component name
		 * @param string $last_category_metakey Last category meta key
		 * @return array of Categories
		 */
		public function get_component_categories($component, $last_category_metakey) {
			$categories = array();

			$prefix = $this->plugin_options['prefix'];
			if ( version_compare($this->joomla_version, '1.5', '<=') ) {
				$name_field = "IF(c.alias <> '', c.alias, c.name)";
				$extension_field = 'c.section';
			} else {
				$name_field = 'c.alias';
				$extension_field = 'c.extension';
			}
			$sql = "
				SELECT c.id, c.title, $name_field AS name, c.description, c.parent_id
				FROM ${prefix}categories c
				WHERE $extension_field = '$component'
				AND c.id > '$last_category_metakey'
				ORDER BY c.id
			";
			$sql = apply_filters('fgj2wp_get_categories_sql', $sql, $prefix);
			$categories = $this->joomla_query($sql);
			return $categories;
		}

		/**
		 * Get Joomla posts
		 *
		 * @param int $limit Number of posts max
		 * @return array of Posts
		 */
		protected function get_posts($limit=1000) {
			$posts = array();

			$last_joomla_id = (int)get_option('fgj2wp_last_article_id'); // to restore the import where it left
			$prefix = $this->plugin_options['prefix'];

			$archived_post_criteria = '';
			if ( $this->plugin_options['archived_posts'] == 'not_imported' ) {
				$archived_post_criteria = 'AND p.state != 2';
			}
			
			// Hooks for adding extra cols and extra joins
			$extra_cols = apply_filters('fgj2wp_get_posts_add_extra_cols', '');
			$extra_joins = apply_filters('fgj2wp_get_posts_add_extra_joins', '');

			$sql = "
				SELECT DISTINCT p.id, 'content' AS type, p.title, p.alias, p.introtext, p.fulltext, p.state, p.catid, p.modified, p.created AS `date`, p.attribs, p.metakey, p.metadesc, p.ordering
				$extra_cols
				FROM ${prefix}content p
				$extra_joins
				WHERE p.state >= -1 -- don't get the trash
				$archived_post_criteria
				AND p.id > '$last_joomla_id'
				ORDER BY p.id
				LIMIT $limit
			";
			$sql = apply_filters('fgj2wp_get_posts_sql', $sql, $prefix, $extra_cols, $extra_joins, $last_joomla_id, $limit);
			$posts = $this->joomla_query($sql);
			return $posts;
		}

		/**
		 * Return the excerpt and the content of a post
		 *
		 * @param array $post Post data
		 * @return array ($excerpt, $content)
		 */
		public function set_excerpt_content($post) {
			$excerpt = '';
			$content = '';

			// Attribs
			$post_attribs = $this->convert_post_attribs_to_array(array_key_exists('attribs', $post)? $post['attribs']: '');

			if ( empty($post['introtext']) ) {
				$content = isset($post['fulltext'])? $post['fulltext'] : '';
			} elseif ( empty($post['fulltext']) ) {
				// Posts without a "Read more" link
				$content = $post['introtext'];
			} else {
				// Posts with a "Read more" link
				$show_intro = (is_array($post_attribs) && array_key_exists('show_intro', $post_attribs))? $post_attribs['show_intro'] : '';
				if ( (($this->plugin_options['introtext'] == 'in_excerpt') && ($show_intro !== '1'))
					|| (($this->plugin_options['introtext'] == 'in_excerpt_and_content') && ($show_intro == '0')) ) {
					// Introtext imported in excerpt
					$excerpt = $post['introtext'];
					$content = $post['fulltext'];
				} elseif ( (($this->plugin_options['introtext'] == 'in_excerpt_and_content') && ($show_intro !== '0'))
					|| (($this->plugin_options['introtext'] == 'in_excerpt') && ($show_intro == '1')) ) {
					// Introtext imported in excerpt and in content
					$excerpt = $post['introtext'];
					$content = $post['introtext'] . "\n" . $post['fulltext'];
				} else {
					if ( $show_intro !== '0' ) {
						// Introtext imported in post content with a "Read more" tag
						$content = $post['introtext'] . "\n<!--more-->\n";
					}
					$content .= $post['fulltext'];
				}
			}
			return array($excerpt, $content);
		}

		/**
		 * Return the post attribs in an array
		 *
		 * @param string $attribs Post attribs as a string
		 * @return array Post attribs as an array
		 */
		function convert_post_attribs_to_array($attribs) {
			$attribs = trim($attribs);
			if ( (substr($attribs, 0, 1) != '{') && (substr($attribs, -1, 1) != '}') ) {
				$post_attribs = parse_ini_string($attribs, FALSE, INI_SCANNER_RAW);
			} else {
				$post_attribs = json_decode($attribs, TRUE);
			}
			return $post_attribs;
		}

		/**
		 * Import post medias from content
		 *
		 * @param string $content post content
		 * @param date $post_date Post date (for storing media)
		 * @param array $options Options
		 * @return array:
		 * 		array media: Medias imported
		 * 		int media_count:   Medias count
		 */
		public function import_media_from_content($content, $post_date, $options=array()) {
			$media = array();
			$media_count = 0;
			$matches = array();
			$alt_matches = array();
			
			if ( preg_match_all('#<(img|a)(.*?)(src|href)="(.*?)"(.*?)>#s', $content, $matches, PREG_SET_ORDER) > 0 ) {
				if ( is_array($matches) ) {
					foreach ($matches as $match ) {
						$filename = $match[4];
						$other_attributes = $match[2] . $match[5];
						// Image Alt
						$image_alt = '';
						if (preg_match('#alt="(.*?)"#', $other_attributes, $alt_matches) ) {
							$image_alt = wp_strip_all_tags(stripslashes($alt_matches[1]), TRUE);
						}
						$attachment_id = $this->import_media($image_alt, $filename, $post_date, $options);
						if ( $attachment_id !== FALSE ) {
							$media_count++;
							$media[$filename] = $attachment_id;
						}
					}
				}
			}
			return array(
				'media'			=> $media,
				'media_count'	=> $media_count
			);
		}
		
		/**
		 * Import a media
		 *
		 * @param string $name Image name
		 * @param string $filename Image URL
		 * @param date $date Date
		 * @param array $options Options
		 * @return int attachment ID or FALSE
		 */
		public function import_media($name, $filename, $date, $options=array()) {
			if ( $date == '0000-00-00 00:00:00' ) {
				$date = date('Y-m-d H:i:s');
			}
			$import_external = ($this->plugin_options['import_external'] == 1) || (isset($options['force_external']) && $options['force_external'] );
			
			$filename = urldecode($filename); // for filenames with spaces or accents
			// Filenames starting with //
			if ( preg_match('#^//#', $filename) ) {
				$filename = 'http:' . $filename;
			}
			
			$filetype = wp_check_filetype($filename);
			if ( empty($filetype['type']) || ($filetype['type'] == 'text/html') ) { // Unrecognized file type
				return FALSE;
			}

			// Upload the file from the Joomla web site to WordPress upload dir
			if ( preg_match('/^http/', $filename) ) {
				if ( $import_external || // External file 
					preg_match('#^' . $this->plugin_options['url'] . '#', $filename) // Local file
				) {
					$old_filename = $filename;
				} else {
					return FALSE;
				}
			} else {
				if ( strpos($filename, '/') === 0 ) { // Avoid a double slash
					$old_filename = untrailingslashit($this->plugin_options['url']) . $filename;
				} else {
					$old_filename = trailingslashit($this->plugin_options['url']) . $filename;
				}
			}
			$old_filename = str_replace(" ", "%20", $old_filename); // for filenames with spaces
			
			// Get the upload path
			$upload_path = $this->upload_dir($filename, $date, get_option('uploads_use_yearmonth_folders'));
			
			// Make sure we have an uploads directory.
			if ( !wp_mkdir_p($upload_path) ) {
				$this->display_admin_error(sprintf(__("Unable to create directory %s", 'fg-joomla-to-wordpress'), $upload_path));
				return FALSE;
			}
			
			$new_filename = $filename;
			
			// Remove the accents (useful on Windows system)
			if ( $this->plugin_options['remove_accents'] ) {
				$new_filename = remove_accents($new_filename);
			}
			
			if ( $this->plugin_options['import_duplicates'] == 1 ) {
				// Images with duplicate names
				$new_filename = preg_replace('#.*images/stories/#', '', $new_filename);
				$new_filename = preg_replace('#.*media/k2#', 'k2', $new_filename);
				$new_filename = str_replace('http://', '', $new_filename);
				$new_filename = str_replace('/', '_', $new_filename);
			}

			$basename = basename($new_filename);
			$new_full_filename = $upload_path . '/' . $basename;

//			print "Copy \"$old_filename\" => $new_full_filename<br />";
			if ( ! @$this->remote_copy($old_filename, $new_full_filename) ) {
				$error = error_get_last();
				$error_message = $error['message'];
				$this->display_admin_error("Can't copy $old_filename to $new_full_filename : $error_message");
				return FALSE;
			}
			
			$post_title = !empty($name)? $name : preg_replace('/\.[^.]+$/', '', $basename);
			
			// Image Alt
			$image_alt = '';
			if ( !empty($name) ) {
				$image_alt = wp_strip_all_tags(stripslashes($name), TRUE);
			}
			
			// GUID
			$upload_dir = wp_upload_dir();
			$guid = str_replace($upload_dir['basedir'], $upload_dir['baseurl'], $new_full_filename);
			
			$attachment_id = $this->insert_attachment($post_title, $basename, $new_full_filename, $guid, $date, $filetype['type'], $image_alt);
			return $attachment_id;
		}
		
		/**
		 * Determine the media upload directory
		 * 
		 * @since 2.13.0
		 * 
		 * @param string $filename Filename
		 * @param date $date Date
		 * @param bool $use_yearmonth_folders Use the Year/Month tree folder
		 * @return string Upload directory
		 */
		private function upload_dir($filename, $date, $use_yearmonth_folders=TRUE) {
			$upload_dir = wp_upload_dir(strftime('%Y/%m', strtotime($date)));
			if ( $use_yearmonth_folders ) {
				$upload_path = $upload_dir['path'];
			} else {
				$short_filename = preg_replace('#.*images/(stories/)?#', '/', $filename);
				if ( strpos($short_filename, '/') != 0 ) {
					$short_filename = '/' . $short_filename; // Add a slash before the filename
				}
				$upload_path = $upload_dir['basedir'] . untrailingslashit(dirname($short_filename));
			}
			return $upload_path;
		}
		
		/**
		 * Save the attachment and generates its metadata
		 * 
		 * @param string $attachment_title Attachment name
		 * @param string $basename Original attachment filename
		 * @param string $new_full_filename New attachment filename with path
		 * @param string $guid GUID
		 * @param date $date Date
		 * @param string $filetype File type
		 * @param string $image_alt Image description
		 * @return int|FALSE Attachment ID or FALSE
		 */
		public function insert_attachment($attachment_title, $basename, $new_full_filename, $guid, $date, $filetype, $image_alt='') {
			$post_name = 'attachment-' . sanitize_title($attachment_title); // Prefix the post name to avoid wrong redirect to a post with the same name
			
			// If the attachment does not exist yet, insert it in the database
			$attachment_id = 0;
			$attachment = $this->get_attachment_from_name($post_name);
			if ( $attachment ) {
				$attached_file = basename(get_attached_file($attachment->ID));
				if ( $attached_file == $basename ) { // Check if the filename is the same (in case of the legend is not unique)
					$attachment_id = $attachment->ID;
				}
			}
			if ( $attachment_id == 0 ) {
				$attachment_data = array(
					'guid'				=> $guid, 
					'post_date'			=> $date,
					'post_mime_type'	=> $filetype,
					'post_name'			=> $post_name,
					'post_title'		=> $attachment_title,
					'post_status'		=> 'inherit',
					'post_content'		=> '',
				);
				$attachment_id = wp_insert_attachment($attachment_data, $new_full_filename);
				add_post_meta($attachment_id, '_fgj2wp_imported', 1, TRUE); // To delete the imported attachments
			}
			
			if ( !empty($attachment_id) ) {
				if ( preg_match('/image/', $filetype) ) { // Images
					// you must first include the image.php file
					// for the function wp_generate_attachment_metadata() to work
					require_once(ABSPATH . 'wp-admin/includes/image.php');
					$attach_data = wp_generate_attachment_metadata( $attachment_id, $new_full_filename );
					wp_update_attachment_metadata($attachment_id, $attach_data);

					// Image Alt
					if ( !empty($image_alt) ) {
						update_post_meta($attachment_id, '_wp_attachment_image_alt', addslashes($image_alt)); // update_post_meta expects slashed
					}
				}
				return $attachment_id;
			} else {
				return FALSE;
			}
		}
		
		/**
		 * Check if the attachment exists in the database
		 *
		 * @param string $name
		 * @return object Post
		 */
		private function get_attachment_from_name($name) {
			$name = preg_replace('/\.[^.]+$/', '', basename($name));
			$r = array(
				'name'			=> $name,
				'post_type'		=> 'attachment',
				'numberposts'	=> 1,
			);
			$posts_array = get_posts($r);
			if ( is_array($posts_array) && (count($posts_array) > 0) ) {
				return $posts_array[0];
			}
			else {
				return FALSE;
			}
		}

		/**
		 * Process the post content
		 *
		 * @param string $content Post content
		 * @param array $post_media Post medias
		 * @return string Processed post content
		 */
		public function process_content($content, $post_media) {

			if ( !empty($content) ) {
				$content = str_replace(array("\r", "\n"), array('', ' '), $content);

				// Replace page breaks
				$content = preg_replace("#<hr([^>]*?)class=\"system-pagebreak\"(.*?)/>#", "<!--nextpage-->", $content);

				// Replace media URLs with the new URLs
				$content = $this->process_content_media_links($content, $post_media);

				// Replace audio and video links
				$content = $this->process_audio_video_links($content);

				// For importing backslashes
				$content = addslashes($content);
			}

			return $content;
		}

		/**
		 * Replace media URLs with the new URLs
		 *
		 * @param string $content Post content
		 * @param array $post_media Post medias
		 * @return string Processed post content
		 */
		private function process_content_media_links($content, $post_media) {
			$matches = array();
			$matches_caption = array();

			if ( is_array($post_media) ) {

				// Get the attachments attributes
				$attachments_found = FALSE;
				$medias = array();
				foreach ( $post_media as $old_filename => $attachment_id ) {
					$media = array();
					$media['attachment_id'] = $attachment_id;
					$media['url_old_filename'] = urlencode($old_filename); // for filenames with spaces or accents
					if ( preg_match('/image/', get_post_mime_type($attachment_id)) ) {
						// Image
						$image_src = wp_get_attachment_image_src($attachment_id, 'full');
						$media['new_url'] = $image_src[0];
						$media['width'] = $image_src[1];
						$media['height'] = $image_src[2];
					} else {
						// Other media
						$media['new_url'] = wp_get_attachment_url($attachment_id);
					}
					$medias[$old_filename] = $media;
					$attachments_found = TRUE;
				}
				if ( $attachments_found ) {

					// Remove the links from the content
					$this->post_link_count = 0;
					$this->post_link = array();
					$content = preg_replace_callback('#<(a) (.*?)(href)=(.*?)</a>#i', array($this, 'remove_links'), $content);
					$content = preg_replace_callback('#<(img) (.*?)(src)=(.*?)>#i', array($this, 'remove_links'), $content);

					// Process the stored medias links
					foreach ($this->post_link as &$link) {
						$new_link = $link['old_link'];
						$alignment = '';
						if ( preg_match('/(align="|float: )(left|right)/', $new_link, $matches) ) {
							$alignment = 'align' . $matches[2];
						}
						if ( preg_match_all('#(src|href)="(.*?)"#i', $new_link, $matches, PREG_SET_ORDER) ) {
							$caption = '';
							foreach ( $matches as $match ) {
								$old_filename = $match[2];
								$link_type = ($match[1] == 'src')? 'img': 'a';
								if ( array_key_exists($old_filename, $medias) ) {
									$media = $medias[$old_filename];
									if ( array_key_exists('new_url', $media) ) {
										if ( (strpos($new_link, $old_filename) > 0) || (strpos($new_link, $media['url_old_filename']) > 0) ) {
											// URL encode the filename
											$new_filename = basename($media['new_url']);
											$encoded_new_filename = rawurlencode($new_filename);
											$new_url = str_replace($new_filename, $encoded_new_filename, $media['new_url']);
											$new_link = preg_replace('#(' . preg_quote($old_filename) . '|' . preg_quote($media['url_old_filename']) . ')#', $new_url, $new_link, 1);

											if ( $link_type == 'img' ) { // images only
												// Define the width and the height of the image if it isn't defined yet
												if ((strpos($new_link, 'width=') === FALSE) && (strpos($new_link, 'height=') === FALSE)) {
													$width_assertion = isset($media['width'])? ' width="' . $media['width'] . '"' : '';
													$height_assertion = isset($media['height'])? ' height="' . $media['height'] . '"' : '';
												} else {
													$width_assertion = '';
													$height_assertion = '';
												}

												// Caption shortcode
												if ( preg_match('/class=".*caption.*?"/', $link['old_link']) ) {
													if ( preg_match('/title="(.*?)"/', $link['old_link'], $matches_caption) ) {
														$caption_value = str_replace('%', '%%', $matches_caption[1]);
														$align_value = ($alignment != '')? $alignment : 'alignnone';
														$caption = '[caption id="attachment_' . $media['attachment_id'] . '" align="' . $align_value . '"' . $width_assertion . ']%s' . $caption_value . '[/caption]';
													}
												}

												$align_class = ($alignment != '')? $alignment . ' ' : '';
												$new_link = preg_replace('#<img(.*?)( class="(.*?)")?(.*) />#', "<img$1 class=\"$3 " . $align_class . 'size-full wp-image-' . $media['attachment_id'] . "\"$4" . $width_assertion . $height_assertion . ' />', $new_link);
											}
										}
									}
								}
							}

							// Add the caption
							if ( $caption != '' ) {
								$new_link = sprintf($caption, $new_link);
							}
						}
						$link['new_link'] = $new_link;
					}

					// Reinsert the converted medias links
					$content = preg_replace_callback('#__fg_link_(\d+)__#', array($this, 'restore_links'), $content);
				}
			}
			return $content;
		}

		/**
		 * Remove all the links from the content and replace them with a specific tag
		 * 
		 * @param array $matches Result of the preg_match
		 * @return string Replacement
		 */
		private function remove_links($matches) {
			$this->post_link[] = array('old_link' => $matches[0]);
			return '__fg_link_' . $this->post_link_count++ . '__';
		}

		/**
		 * Restore the links in the content and replace them with the new calculated link
		 * 
		 * @param array $matches Result of the preg_match
		 * @return string Replacement
		 */
		private function restore_links($matches) {
			$link = $this->post_link[$matches[1]];
			$new_link = array_key_exists('new_link', $link)? $link['new_link'] : $link['old_link'];
			return $new_link;
		}

		/**
		 * Add a link between a media and a post (parent id + thumbnail)
		 *
		 * @param int $post_id Post ID
		 * @param array $post_data Post data
		 * @param array $post_media Post medias IDs
		 * @param boolean $set_featured_image Set the featured image?
		 */
		public function add_post_media($post_id, $post_data, $post_media, $set_featured_image=TRUE) {
			$thumbnail_is_set = FALSE;
			if ( is_array($post_media) ) {
				foreach ( $post_media as $attachment_id ) {
					$attachment = get_post($attachment_id);
					if ( !empty($attachment) ) {
						$attachment->post_parent = $post_id; // Attach the post to the media
						$attachment->post_date = $post_data['post_date'] ;// Define the media's date
						wp_update_post($attachment);

						// Set the featured image. If not defined, it is the first image of the content.
						if ( $set_featured_image && !$thumbnail_is_set ) {
							set_post_thumbnail($post_id, $attachment_id);
							$thumbnail_is_set = TRUE;
						}
					}
				}
			}
		}

		/**
		 * Modify the audio and video links
		 *
		 * @param string $content Content
		 * @return string Content
		 */
		private function process_audio_video_links($content) {
			if ( strpos($content, '{"video"') !== FALSE ) {
				$content = preg_replace('/(<p>)?{"video":"(.*?)".*?}(<\/p>)?/', "$2", $content);
			}
			if ( strpos($content, '{audio}') !== FALSE ) {
				$content = preg_replace('#{audio}(.*?){/audio}#', "$1", $content);
			}
			return $content;
		}
		
		/**
		 * Modify the internal links of all posts
		 *
		 * @return array:
		 * 		int links_count: Links count
		 */
		private function modify_links() {
			$links_count = 0;
			$step = 1000; // to limit the results
			$offset = 0;
			$matches = array();

			// Hook for doing other actions before modifying the links
			do_action('fgj2wp_pre_modify_links');

			do {
				$args = array(
					'numberposts'	=> $step,
					'offset'		=> $offset,
					'orderby'		=> 'ID',
					'order'			=> 'ASC',
					'post_type'		=> 'any',
				);
				$posts = get_posts($args);
				foreach ( $posts as $post ) {
					$links_found = FALSE;
					$post = apply_filters('fgj2wp_post_get_post', $post); // Used to translate the links
					$content = $post->post_content;
					if ( preg_match_all('#<a(.*?)href="(.*?)"(.*?)>#', $content, $matches, PREG_SET_ORDER) > 0 ) {
						if ( is_array($matches) ) {
							foreach ( $matches as $match ) {
								$link = $match[2];
								list($link_without_anchor, $anchor_link) = $this->split_anchor_link($link); // Split the anchor link
								// Is it an internal link ?
								if ( $this->is_internal_link($link_without_anchor) ) {
									$new_link = '';
									
									// Try to find a matching term
									$linked_term = $this->get_wp_term_from_joomla_url($link_without_anchor);
									if ( $linked_term ) {
										$new_link = get_term_link($linked_term->term_id, $linked_term->taxonomy);
									}
									
									if ( empty($new_link) ) {
										// Try to find a matching post
										$linked_post = $this->get_wp_post_from_joomla_url($link_without_anchor);
										if ( $linked_post ) {
											$linked_post_id = $linked_post->ID;
											$linked_post_id = apply_filters('fgj2wp_post_get_post_by_joomla_id', $linked_post_id, $post); // Used to get the ID of the translated post
											$new_link = get_permalink($linked_post_id);
										}
									}
									
									if ( !empty($new_link) ) {
										if ( !empty($anchor_link) ) {
											$new_link .= '#' . $anchor_link;
										}
										$content = str_replace("href=\"$link\"", "href=\"$new_link\"", $content);
										$links_found = TRUE;
										$links_count++;
									}
								}
							}
						}
					}
					if ( $links_found ) {
						// Update the post
						wp_update_post(array(
							'ID'			=> $post->ID,
							'post_content'	=> $content,
						));
						
					}
				}
				$offset += $step;
			} while ( ($posts != null) && (count($posts) > 0) );

			// Hook for doing other actions after modifying the links
			do_action('fgj2wp_post_modify_links');

			return array('links_count' => $links_count);
		}

		/**
		 * Test if the link is an internal link or not
		 *
		 * @param string $link
		 * @return bool
		 */
		private function is_internal_link($link) {
			$result = (preg_match("#^".$this->plugin_options['url']."#", $link) > 0) ||
				(preg_match("#^(http|//)#", $link) == 0);
			return $result;
		}
		
		/**
		 * Get a WordPress post that matches a Joomla URL
		 * 
		 * @param string $url URL
		 * @return WP_Post WordPress post | null
		 */
		private function get_wp_post_from_joomla_url($url) {
			$post = null;
			$post_name = $this->remove_html_extension(basename($url));
			
			// Try to find a post by its post name
			$post_id = $this->get_post_by_name($post_name);
			
			// Try to find a post in the redirect table
			if ( empty($post_id) && class_exists('FG_Joomla_to_WordPress_Redirect') ) {
				$redirect_obj = new FG_Joomla_to_WordPress_Redirect();
				$post = $redirect_obj->find_url_in_redirect_table($post_name);
				if ( $post ) {
					$post_id = $post->id;
				}
			}
			
			// Try to find a post by an ID in the URL
			if ( empty($post_id) ) {
				$meta_key_value = $this->get_joomla_id_in_link($url);
				$post_id = $this->get_wp_post_id_from_meta($meta_key_value['meta_key'], $meta_key_value['meta_value']);
			}
			
			if ( !empty($post_id) ) {
				$post = get_post($post_id);
			}
			if ( !$post ) {
				$post = apply_filters('fgj2wp_get_wp_post_from_joomla_url', $post, $url);
			}
			return $post;
		}

		/**
		 * Get a WordPress term that matches a Joomla URL
		 * 
		 * @since 3.19.0
		 * 
		 * @param string $url URL
		 * @return WP_Term WordPress term | null
		 */
		private function get_wp_term_from_joomla_url($url) {
			$term = apply_filters('fgj2wp_get_wp_term_from_joomla_url', null, $url);
			return $term;
		}
		
		/**
		 * Remove the file extension .html
		 * 
		 * @param string $url URL
		 * @return string URL
		 */
		private function remove_html_extension($url) {
			$url = preg_replace('/\.html$/', '', $url);
			return $url;
		}
		
		/**
		 * Get a post by its name
		 * 
		 * @global object $wpdb
		 * @param string $post_name
		 * @param string $post_type
		 * @return int $post_id
		 */
		private function get_post_by_name($post_name, $post_type = 'post') {
			global $wpdb;
			$post_id = $wpdb->get_var($wpdb->prepare("SELECT ID FROM $wpdb->posts WHERE post_name = %s AND post_type= %s", $post_name, $post_type));
			return $post_id;
		}
		
		/**
		 * Get the Joomla ID in a link
		 *
		 * @param string $link
		 * @return array('meta_key' => $meta_key, 'meta_value' => $meta_value)
		 */
		private function get_joomla_id_in_link($link) {
			$matches = array();

			$meta_key_value = array(
				'meta_key'		=> '',
				'meta_value'	=> 0);
			$meta_key_value = apply_filters('fgj2wp_pre_get_joomla_id_in_link', $meta_key_value, $link);
			if ( $meta_key_value['meta_value'] == 0 ) {
				$meta_key_value['meta_key'] = '_fgj2wp_old_id';
				// Without URL rewriting
				if ( preg_match("#[^a-zA-Z]id=(\d+)#", $link, $matches) ) {
					$meta_key_value['meta_value'] = $matches[1];
				}
				// With URL rewriting
				elseif ( preg_match("#^((.*)/)?(\d+)-(.*)#", $link, $matches) ) {
					$meta_key_value['meta_value'] = $matches[3];
				} else {
					$meta_key_value = apply_filters('fgj2wp_post_get_joomla_id_in_link', $meta_key_value);
				}
			}
			return $meta_key_value;
		}

		/**
		 * Split a link by its anchor link
		 * 
		 * @param string $link Original link
		 * @return array(string link, string anchor_link) [link without anchor, anchor_link]
		 */
		private function split_anchor_link($link) {
			$pos = strpos($link, '#');
			if ( $pos !== FALSE ) {
				// anchor link found
				$link_without_anchor = substr($link, 0, $pos);
				$anchor_link = substr($link, $pos + 1);
				return array($link_without_anchor, $anchor_link);
			} else {
				// anchor link not found
				return array($link, '');
			}
		}

		/**
		 * Copy a remote file
		 * in replacement of the copy function
		 * 
		 * @param string $url URL of the source file
		 * @param string $path destination file
		 * @return boolean
		 */
		public function remote_copy($url, $path) {

			/*
			 * cwg enhancement: if destination already exists, just return TRUE
			 *  this allows rebuilding the wp media db without moving files
			 */
			if ( !$this->plugin_options['force_media_import'] && file_exists($path) && (filesize($path) > 0) ) {
				return TRUE;
			}

			$response = wp_remote_get($url, array(
				'timeout' => $this->plugin_options['timeout'],
				'sslverify' => FALSE,
			)); // Uses WordPress HTTP API

			if ( is_wp_error($response) ) {
				trigger_error($response->get_error_message(), E_USER_WARNING);
				return FALSE;
			} elseif ( $response['response']['code'] != 200 ) {
				trigger_error($response['response']['message'], E_USER_WARNING);
				return FALSE;
			} else {
				file_put_contents($path, wp_remote_retrieve_body($response));
				return TRUE;
			}
		}

		/**
		 * Recount the items for a taxonomy
		 * 
		 * @return boolean
		 */
		private function terms_tax_count($taxonomy) {
			$terms = get_terms(array($taxonomy));
			// Get the term taxonomies
			$terms_taxonomies = array();
			foreach ( $terms as $term ) {
				$terms_taxonomies[] = $term->term_taxonomy_id;
			}
			if ( !empty($terms_taxonomies) ) {
				return wp_update_term_count_now($terms_taxonomies, $taxonomy);
			} else {
				return TRUE;
			}
		}

		/**
		 * Recount the items for each category and tag
		 * 
		 * @return boolean
		 */
		private function terms_count() {
			$result = $this->terms_tax_count('category');
			$result |= $this->terms_tax_count('post_tag');
		}

		/**
		 * Guess the Joomla version
		 *
		 * @return string Joomla version
		 */
		private function joomla_version() {
			if ( !$this->table_exists('content') ) {
				return '0.0';
			} elseif ( !$this->column_exists('content', 'alias') ) {
				return '1.0';
			} elseif ( !$this->column_exists('content', 'asset_id') ) {
				return '1.5';
			} elseif ( $this->column_exists('content', 'title_alias') ) {
				return '2.5';
			} elseif ( !$this->table_exists('tags') ) {
				return '3.0';
			} else {
				return '3.1';
			}
		}

		/**
		 * Get the Joomla installation language
		 *
		 * @return string Language code (eg: fr-FR)
		 */
		public function get_joomla_language() {
			$lang = '';

			$prefix = $this->plugin_options['prefix'];

			if ( $this->table_exists('extensions') ) {
				$sql = "
					SELECT `params`
					FROM ${prefix}extensions
					WHERE `element` = 'com_languages'
				";
			} elseif ( $this->table_exists('components') ) {
				$sql = "
					SELECT `params`
					FROM ${prefix}components
					WHERE `option` = 'com_languages'
				";
			} else {
				return '';
			}
			$result = $this->joomla_query($sql);
			if ( isset($result[0]['params']) ) {
				if ( (substr($result[0]['params'], 0, 1) != '{') && (substr($result[0]['params'], -1, 1) != '}') ) {
					$params = parse_ini_string($result[0]['params'], FALSE, INI_SCANNER_RAW);
				} else {
					$params = json_decode($result[0]['params'], TRUE);
				}
				if ( array_key_exists('site', $params)) {
					$lang = $params['site'];
				}
			}
			return $lang;
		}

		/**
		 * Returns the imported posts mapped with their Joomla ID
		 *
		 * @param string $meta_key Meta key (default = _fgj2wp_old_id)
		 * @return array of post IDs [joomla_article_id => wordpress_post_id]
		 */
		public function get_imported_joomla_posts($meta_key = '_fgj2wp_old_id') {
			global $wpdb;
			$posts = array();

			$sql = "SELECT post_id, meta_value FROM {$wpdb->postmeta} WHERE meta_key = '$meta_key'";
			$results = $wpdb->get_results($sql);
			foreach ( $results as $result ) {
				$posts[$result->meta_value] = $result->post_id;
			}
			ksort($posts);
			return $posts;
		}

		/**
		 * Returns the imported posts (including their post type) mapped with their Joomla ID
		 *
		 * @param string $meta_key Meta key (default = _fgj2wp_old_id)
		 * @return array of post IDs [joomla_article_id => [wordpress_post_id, wordpress_post_type]]
		 */
		public function get_imported_joomla_posts_with_post_type($meta_key = '_fgj2wp_old_id') {
			global $wpdb;
			$posts = array();

			$sql = "
				SELECT pm.post_id, pm.meta_value, p.post_type
				FROM {$wpdb->postmeta} pm
				INNER JOIN {$wpdb->posts} p ON p.ID = pm.post_id
				WHERE pm.meta_key = '$meta_key'
			";
			$results = $wpdb->get_results($sql);
			foreach ( $results as $result ) {
				$posts[$result->meta_value] = array(
					'post_id' => $result->post_id,
					'post_type' => $result->post_type,
				);
			}
			ksort($posts);
			return $posts;
		}

		/**
		 * Returns the imported post ID corresponding to a Joomla ID
		 *
		 * @param int $joomla_id Joomla article ID
		 * @return int WordPress post ID
		 */
		public function get_wp_post_id_from_joomla_id($joomla_id) {
			$post_id = $this->get_wp_post_id_from_meta('_fgj2wp_old_id', $joomla_id);
			return $post_id;
		}

		/**
		 * Returns the imported post ID corresponding to a meta key and value
		 *
		 * @param string $meta_key Meta key
		 * @param string $meta_value Meta value
		 * @return int WordPress post ID
		 */
		public function get_wp_post_id_from_meta($meta_key, $meta_value) {
			global $wpdb;

			$sql = "SELECT post_id FROM {$wpdb->postmeta} WHERE meta_key = '$meta_key' AND meta_value = '$meta_value' LIMIT 1";
			$post_id = $wpdb->get_var($sql);
			return $post_id;
		}

		/**
		 * Returns the imported term ID corresponding to a meta key and value
		 *
		 * @since 3.10.0
		 * 
		 * @param string $meta_key Meta key
		 * @param string $meta_value Meta value
		 * @return int WordPress term ID
		 */
		public function get_wp_term_id_from_meta($meta_key, $meta_value) {
			global $wpdb;

			$sql = "SELECT term_id FROM {$wpdb->termmeta} WHERE meta_key = '$meta_key' AND meta_value = '$meta_value' LIMIT 1";
			$term_id = $wpdb->get_var($sql);
			return $term_id;
		}

		/**
		 * Returns the imported users mapped with their Joomla ID
		 *
		 * @return array of user IDs [joomla_user_id => wordpress_user_id]
		 */
		public function get_imported_joomla_users() {
			global $wpdb;
			$users = array();

			$sql = "SELECT user_id, meta_value FROM {$wpdb->usermeta} WHERE meta_key = '_fgj2wp_old_user_id'";
			$results = $wpdb->get_results($sql);
			foreach ( $results as $result ) {
				$users[$result->meta_value] = $result->user_id;
			}
			ksort($users);
			return $users;
		}

		/**
		 * Test if a column exists
		 *
		 * @param string $table Table name
		 * @param string $column Column name
		 * @return bool
		 */
		public function column_exists($table, $column) {
			global $joomla_db;

			try {
				$prefix = $this->plugin_options['prefix'];

				$sql = "SHOW COLUMNS FROM ${prefix}${table} LIKE '$column'";
				$query = $joomla_db->query($sql, PDO::FETCH_ASSOC);
				if ( $query !== FALSE ) {
					$result = $query->fetch();
					return !empty($result);
				} else {
					return FALSE;
				}
			} catch ( PDOException $e ) {}
			return FALSE;
		}

		/**
		 * Test if a table exists
		 *
		 * @param string $table Table name
		 * @return bool
		 */
		public function table_exists($table) {
			global $joomla_db;

			try {
				$prefix = $this->plugin_options['prefix'];

				$sql = "SHOW TABLES LIKE '${prefix}${table}'";
				$query = $joomla_db->query($sql, PDO::FETCH_ASSOC);
				if ( $query !== FALSE ) {
					$result = $query->fetch();
					return !empty($result);
				} else {
					return FALSE;
				}
			} catch ( PDOException $e ) {}
			return FALSE;
		}

		/**
		 * Test if a remote file exists
		 * 
		 * @param string $filePath
		 * @return boolean True if the file exists
		 */
		public function url_exists($filePath) {
			$url = str_replace(' ', '%20', $filePath);
			
			// Try the get_headers method
			$headers = @get_headers($url);
			$result = preg_match("/200/", $headers[0]);
			
			if ( !$result && strpos($filePath, 'https:') !== 0 ) {
				// Try the fsock method
				$url = str_replace('http://', '', $url);
				if ( strstr($url, '/') ) {
					$url = explode('/', $url, 2);
					$url[1] = '/' . $url[1];
				} else {
					$url = array($url, '/');
				}

				$fh = fsockopen($url[0], 80);
				if ( $fh ) {
					fputs($fh,'GET ' . $url[1] . " HTTP/1.1\nHost:" . $url[0] . "\n");
					fputs($fh,"User-Agent: Mozilla/5.0 (Windows NT 6.2; WOW64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/27.0.1453.94 Safari/537.36\n\n");
					$response = fread($fh, 22);
					fclose($fh);
					$result = (strpos($response, '200') !== FALSE);
				} else {
					$result = FALSE;
				}
			}
			
			return $result;
		}
		
		/**
		 * Store the mapping of the imported categories
		 * 
		 * @since 3.22.0
		 */
		public function get_imported_categories($meta_key='_fgj2wp_old_category_id') {
			$this->imported_categories = $this->get_term_metas_by_metakey($meta_key);
		}
		
		/**
		 * Get all the term metas corresponding to a meta key
		 * 
		 * @param string $meta_key Meta key
		 * @return array List of term metas: term_id => meta_value
		 */
		public function get_term_metas_by_metakey($meta_key) {
			global $wpdb;
			$metas = array();
			
			$sql = "SELECT term_id, meta_value FROM {$wpdb->termmeta} WHERE meta_key = '$meta_key'";
			$results = $wpdb->get_results($sql);
			foreach ( $results as $result ) {
				$metas[$result->meta_value] = $result->term_id;
			}
			ksort($metas);
			return $metas;
		}
		
		/**
		 * Search a term by its slug (LIKE search)
		 * 
		 * @param string $slug slug
		 * @return int Term id
		 */
		public function get_term_id_by_slug($slug) {
			global $wpdb;
			return $wpdb->get_var("
				SELECT term_id FROM $wpdb->terms
				WHERE slug LIKE '$slug'
			");
		}

	}
}
