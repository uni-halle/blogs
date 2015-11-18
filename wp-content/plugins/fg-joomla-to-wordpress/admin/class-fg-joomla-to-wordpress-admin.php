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

if ( !class_exists('FG_Joomla_to_WordPress_Admin', false) ) {

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
		private $version;

		public $plugin_options;				// Plug-in options

		protected $post_type = 'post';		// post or page
		protected $faq_url;					// URL of the FAQ page

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

			wp_enqueue_script( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'js/fg-joomla-to-wordpress-admin.js', array( 'jquery' ), $this->version, false );
			wp_localize_script( $this->plugin_name, 'objectL10n', array(
				'delete_new_posts_confirmation_message' => __( 'All new imported posts or pages and their comments will be deleted from WordPress.', 'fg-joomla-to-wordpress' ),
				'delete_all_confirmation_message' => __( 'All content will be deleted from WordPress.', 'fg-joomla-to-wordpress' ),
				'delete_no_answer_message' => __( 'Please select a remove option.', 'fg-joomla-to-wordpress' ),
			) );

		}

		/**
		 * Initialize the plugin
		 */
		public function init() {
			register_importer('fgj2wp', __('Joomla (FG)', 'fg-joomla-to-wordpress'), __('Import categories, articles and medias (images, attachments) from a Joomla database into WordPress.', 'fg-joomla-to-wordpress'), array($this, 'dispatch'));
		}

		/**
		 * Display admin notice
		 */
		public function display_admin_notice( $message )	{
			echo '<div class="updated"><p>['.$this->plugin_name.'] '.$message.'</p></div>';
			error_log('[INFO] [' . $this->plugin_name . '] ' . $message);
		}

		/**
		 * Display admin error
		 */
		public function display_admin_error( $message )	{
			echo '<div class="error"><p>['.$this->plugin_name.'] '.$message.'</p></div>';
			error_log('[ERROR] [' . $this->plugin_name . '] ' . $message);
		}

		/**
		 * Dispatch the actions
		 */
		public function dispatch() {
			set_time_limit(7200); // Timeout = 2 hours

			// Suspend the cache during the migration to avoid exhausted memory problem
			wp_suspend_cache_addition(true);
			wp_suspend_cache_invalidation(true);

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
				'first_image'			=> 'as_is_and_featured',
				'import_external'		=> 0,
				'import_duplicates'		=> 0,
				'force_media_import'	=> 0,
				'meta_keywords_in_tags'	=> 0,
				'import_as_pages'		=> 0,
				'timeout'				=> 5,
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

			if ( isset($_POST['empty']) ) {

				// Delete content
				if ( check_admin_referer( 'empty', 'fgj2wp_nonce' ) ) { // Security check
					if ($this->empty_database($_POST['empty_action'])) { // Empty WP database
						$this->display_admin_notice(__('WordPress content removed', 'fg-joomla-to-wordpress'));
					} else {
						$this->display_admin_error(__('Couldn\'t remove content', 'fg-joomla-to-wordpress'));
					}
					wp_cache_flush();
				}
			}

			elseif ( isset($_POST['save']) ) {

				// Save database options
				$this->save_plugin_options();
				$this->display_admin_notice(__('Settings saved', 'fg-joomla-to-wordpress'));
			}

			elseif ( isset($_POST['test']) ) {

				// Save database options
				$this->save_plugin_options();

				// Test the database connection
				if ( check_admin_referer( 'parameters_form', 'fgj2wp_nonce' ) ) { // Security check
					$this->test_database_connection();
				}
			}

			elseif ( isset($_POST['import']) ) {

				// Save database options
				$this->save_plugin_options();

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
				if ( check_admin_referer( 'parameters_form', 'fgj2wp_nonce' ) ) { // Security check
					$this->import();
				}
			}

			elseif ( isset($_POST['remove_cat_prefix']) ) {

				// Remove the prefixes from the categories
				if ( check_admin_referer( 'remove_cat_prefix', 'fgj2wp_nonce' ) ) { // Security check
					$result = $this->remove_category_prefix();
					$this->display_admin_notice(__('Prefixes removed from categories', 'fg-joomla-to-wordpress'));
				}
			}

			elseif ( isset($_POST['modify_links']) ) {

				// Modify internal links
				if ( check_admin_referer( 'modify_links', 'fgj2wp_nonce' ) ) { // Security check
					$result = $this->modify_links();
					$this->display_admin_notice(sprintf(_n('%d internal link modified', '%d internal links modified', $result['links_count'], 'fg-joomla-to-wordpress'), $result['links_count']));
				}
			} else {
				// Do other actions
				do_action('fgj2wp_dispatch');
			}

			$this->display_admin_page(); // Display the admin page
		}

		/**
		 * Display the admin page
		 * 
		 */
		private function display_admin_page() {
			$cat_count = count(get_categories(array('hide_empty' => 0)));
			$tags_count = count(get_tags(array('hide_empty' => 0)));

			$data = $this->plugin_options;

			$data['title'] = __('Import Joomla (FG)', 'fg-joomla-to-wordpress');
			$data['description'] = __('This plugin will import sections, categories, posts, medias (images, attachments) and web links from a Joomla database into WordPress.<br />Compatible with Joomla versions 1.5, 1.6, 1.7, 2.5, 3.0, 3.1, 3.2, 3.3 and 3.4.', 'fg-joomla-to-wordpress');
			$data['description'] .= "<br />\n" . __('For any issue, please read the <a href="http://wordpress.org/plugins/fg-joomla-to-wordpress/faq/" target="_blank">FAQ</a> first.', 'fg-joomla-to-wordpress');
			$data['posts_count'] = $this->count_posts('post');
			$data['pages_count'] = $this->count_posts('page');
			$data['media_count'] = $this->count_posts('attachment');
			$data['database_info'] = array(
				sprintf(_n('%d category', '%d categories', $cat_count, 'fg-joomla-to-wordpress'), $cat_count),
				sprintf(_n('%d post', '%d posts', $data['posts_count'], 'fg-joomla-to-wordpress'), $data['posts_count']),
				sprintf(_n('%d page', '%d pages', $data['pages_count'], 'fg-joomla-to-wordpress'), $data['pages_count']),
				sprintf(_n('%d media', '%d medias', $data['media_count'], 'fg-joomla-to-wordpress'), $data['media_count']),
				sprintf(_n('%d tag', '%d tags', $tags_count, 'fg-joomla-to-wordpress'), $tags_count),
			);

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
		protected function joomla_connect() {
			global $joomla_db;

			if ( !class_exists('PDO') ) {
				$this->display_admin_error(__('PDO is required. Please enable it.', 'fg-joomla-to-wordpress'));
				return false;
			}
			try {
				$joomla_db = new PDO('mysql:host=' . $this->plugin_options['hostname'] . ';port=' . $this->plugin_options['port'] . ';dbname=' . $this->plugin_options['database'], $this->plugin_options['username'], $this->plugin_options['password'], array(PDO::MYSQL_ATTR_INIT_COMMAND => 'SET NAMES \'UTF8\''));
				if ( defined('WP_DEBUG') && WP_DEBUG ) {
					$joomla_db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION); // Display SQL errors
				}
			} catch ( PDOException $e ) {
				$this->display_admin_error(__('Couldn\'t connect to the Joomla database. Please check your parameters. And be sure the WordPress server can access the Joomla database.', 'fg-joomla-to-wordpress') . '<br />' . $e->getMessage() . '<br />' . sprintf(__('Please read the <a href="%s" target="_blank">FAQ for the solution</a>.', 'fg-joomla-to-wordpress'), $this->faq_url));
				return false;
			}
			$this->plugin_options['version'] = $this->joomla_version();
			return true;
		}

		/**
		 * Execute a SQL query on the Joomla database
		 * 
		 * @param string $sql SQL query
		 * @param bool $display_error Display the error?
		 * @return array Query result
		 */
		public function joomla_query($sql, $display_error = true) {
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
		 * @param string $action	newposts = removes only new imported posts
		 * 							all = removes all
		 * @return boolean
		 */
		private function empty_database($action) {
			global $wpdb;
			$result = true;

			$wpdb->show_errors();

			// Hook for doing other actions before emptying the database
			do_action('fgj2wp_pre_empty_database', $action);

			$sql_queries = array();

			if ( $action == 'all' ) {
				// Remove all content
				$start_id = 1;
				update_option('fgj2wp_start_id', $start_id);

				$sql_queries[] = "TRUNCATE $wpdb->commentmeta";
				$sql_queries[] = "TRUNCATE $wpdb->comments";
				$sql_queries[] = "TRUNCATE $wpdb->term_relationships";
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
				// Remove only new imported posts
				// WordPress post ID to start the deletion
				$start_id = intval(get_option('fgj2wp_start_id'));
				if ( $start_id != 0) {

					$sql_queries[] = <<<SQL
-- Delete Comments meta
DELETE FROM $wpdb->commentmeta
WHERE comment_id IN
(
SELECT comment_ID FROM $wpdb->comments
WHERE comment_post_ID IN
	(
	SELECT ID FROM $wpdb->posts
	WHERE (post_type IN ('post', 'page', 'attachment', 'revision')
	OR post_status = 'trash'
	OR post_title = 'Brouillon auto')
	AND ID >= $start_id
	)
);
SQL;

					$sql_queries[] = <<<SQL
-- Delete Comments
DELETE FROM $wpdb->comments
WHERE comment_post_ID IN
(
SELECT ID FROM $wpdb->posts
WHERE (post_type IN ('post', 'page', 'attachment', 'revision')
OR post_status = 'trash'
OR post_title = 'Brouillon auto')
AND ID >= $start_id
);
SQL;

					$sql_queries[] = <<<SQL
-- Delete Term relashionships
DELETE FROM $wpdb->term_relationships
WHERE `object_id` IN
(
SELECT ID FROM $wpdb->posts
WHERE (post_type IN ('post', 'page', 'attachment', 'revision')
OR post_status = 'trash'
OR post_title = 'Brouillon auto')
AND ID >= $start_id
);
SQL;

					$sql_queries[] = <<<SQL
-- Delete Post meta
DELETE FROM $wpdb->postmeta
WHERE post_id IN
(
SELECT ID FROM $wpdb->posts
WHERE (post_type IN ('post', 'page', 'attachment', 'revision')
OR post_status = 'trash'
OR post_title = 'Brouillon auto')
AND ID >= $start_id
);
SQL;

					$sql_queries[] = <<<SQL
-- Delete Posts
DELETE FROM $wpdb->posts
WHERE (post_type IN ('post', 'page', 'attachment', 'revision')
OR post_status = 'trash'
OR post_title = 'Brouillon auto')
AND ID >= $start_id;
SQL;
				}
			}

			// Execute SQL queries
			if ( count($sql_queries) > 0 ) {
				foreach ( $sql_queries as $sql ) {
					$result &= $wpdb->query($sql);
				}
			}

			// Hook for doing other actions after emptying the database
			do_action('fgj2wp_post_empty_database', $action);

			// Reset the Joomla last imported post ID
			update_option('fgj2wp_last_joomla_id', 0);

			// Re-count categories and tags items
			$this->terms_count();

			// Update cache
			$this->clean_cache();

			$this->optimize_database();

			$wpdb->hide_errors();
			return ($result !== false);
		}

		/**
		 * Optimize the database
		 *
		 */
		protected function optimize_database() {
			global $wpdb;

			$sql = <<<SQL
OPTIMIZE TABLE 
`$wpdb->commentmeta` ,
`$wpdb->comments` ,
`$wpdb->options` ,
`$wpdb->postmeta` ,
`$wpdb->posts` ,
`$wpdb->terms` ,
`$wpdb->term_relationships` ,
`$wpdb->term_taxonomy`
SQL;
			$wpdb->query($sql);
		}

		/**
		 * Test the database connection
		 * 
		 * @return boolean
		 */
		function test_database_connection() {
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

					return true;

				} catch ( PDOException $e ) {
					$this->display_admin_error(__('Couldn\'t connect to the Joomla database. Please check your parameters. And be sure the WordPress server can access the Joomla database.', 'fg-joomla-to-wordpress') . '<br />' . $e->getMessage() . '<br />' . sprintf(__('Please read the <a href="%s" target="_blank">FAQ for the solution</a>.', 'fg-joomla-to-wordpress'), $this->faq_url));
					return false;
				}
				$joomla_db = null;
			}
		}

		/**
		 * Test for Joomla version 1.0
		 *
		 * @return bool False if Joomla version < 1.5 (for Joomla 1.0 and Mambo)
		 */
		public function test_joomla_1_0() {
			if ( version_compare($this->plugin_options['version'], '1.5', '<') ) {
				$this->display_admin_error(__('Your version of Joomla (probably 1.0) is not supported by this plugin. Please consider upgrading to the <a href="http://www.fredericgilles.net/fg-joomla-to-wordpress/" target="_blank">Premium version</a>.', 'fg-joomla-to-wordpress'));
				// Deactivate the Joomla info feature
				remove_action('fgj2wp_post_test_database_connection', array($this, 'get_joomla_info'), 9);
				return false;
			}
			return true;
		}

		/**
		 * Get some Joomla information
		 *
		 */
		public function get_joomla_info() {
			$message = __('Joomla data found:', 'fg-joomla-to-wordpress') . '<br />';

			// Sections
			if ( version_compare($this->plugin_options['version'], '1.5', '<=') ) {
				$sections_count = $this->get_sections_count();
				$message .= sprintf(_n('%d section', '%d sections', $sections_count, 'fg-joomla-to-wordpress'), $sections_count) . '<br />';
			}

			// Categories
			$cat_count = $this->get_categories_count();
			$message .= sprintf(_n('%d category', '%d categories', $cat_count, 'fg-joomla-to-wordpress'), $cat_count) . '<br />';

			// Articles
			$posts_count = $this->get_posts_count();
			$message .= sprintf(_n('%d article', '%d articles', $posts_count, 'fg-joomla-to-wordpress'), $posts_count) . '<br />';

			// Users
			$users_count = $this->get_users_count();
			$message .= sprintf(_n('%d user', '%d users', $users_count, 'fg-joomla-to-wordpress'), $users_count) . '<br />';

			// Web links
			if ( $this->table_exists('weblinks') ) { // Joomla 3.4
				$weblinks_count = $this->get_weblinks_count();
				$message .= sprintf(_n('%d web link', '%d web links', $weblinks_count, 'fg-joomla-to-wordpress'), $weblinks_count) . '<br />';
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
			if ( version_compare($this->plugin_options['version'], '1.5', '<=') ) {
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
		 * Get the number of Joomla articles
		 * 
		 * @return int Number of articles
		 */
		private function get_posts_count() {
			$prefix = $this->plugin_options['prefix'];
			$sql = "
				SELECT COUNT(*) AS nb
				FROM ${prefix}content c
				WHERE c.state >= -1 -- don't get the trash
			";
			$result = $this->joomla_query($sql);
			$posts_count = isset($result[0]['nb'])? $result[0]['nb'] : 0;
			return $posts_count;
		}

		/**
		 * Get the number of Joomla users
		 * 
		 * @return int Number of users
		 */
		private function get_users_count() {
			$prefix = $this->plugin_options['prefix'];
			$sql = "
				SELECT COUNT(*) AS nb
				FROM ${prefix}users u
			";
			$result = $this->joomla_query($sql);
			$users_count = isset($result[0]['nb'])? $result[0]['nb'] : 0;
			return $users_count;
		}

		/**
		 * Get the number of Joomla web links
		 * 
		 * @return int Number of web links
		 */
		private function get_weblinks_count() {
			$prefix = $this->plugin_options['prefix'];
			if ( version_compare($this->plugin_options['version'], '1.5', '<=') ) {
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
				'username'				=> filter_input(INPUT_POST, 'username', FILTER_SANITIZE_STRING),
				'password'				=> filter_input(INPUT_POST, 'password', FILTER_SANITIZE_STRING),
				'prefix'				=> filter_input(INPUT_POST, 'prefix', FILTER_SANITIZE_STRING),
				'introtext'				=> filter_input(INPUT_POST, 'introtext', FILTER_SANITIZE_STRING),
				'archived_posts'		=> filter_input(INPUT_POST, 'archived_posts', FILTER_SANITIZE_STRING),
				'skip_media'			=> filter_input(INPUT_POST, 'skip_media', FILTER_VALIDATE_BOOLEAN),
				'first_image'			=> filter_input(INPUT_POST, 'first_image', FILTER_SANITIZE_STRING),
				'import_external'		=> filter_input(INPUT_POST, 'import_external', FILTER_VALIDATE_BOOLEAN),
				'import_duplicates'		=> filter_input(INPUT_POST, 'import_duplicates', FILTER_VALIDATE_BOOLEAN),
				'force_media_import'	=> filter_input(INPUT_POST, 'force_media_import', FILTER_VALIDATE_BOOLEAN),
				'meta_keywords_in_tags'	=> filter_input(INPUT_POST, 'meta_keywords_in_tags', FILTER_VALIDATE_BOOLEAN),
				'import_as_pages'		=> filter_input(INPUT_POST, 'import_as_pages', FILTER_VALIDATE_BOOLEAN),
				'timeout'				=> filter_input(INPUT_POST, 'timeout', FILTER_SANITIZE_NUMBER_INT),
			);
		}

		/**
		 * Import
		 *
		 */
		private function import() {
			if ( $this->joomla_connect() ) {

				$time_start = microtime(true);

				// To solve the issue of links containing ":" in multisite mode
				kses_remove_filters();

				// Check prerequesites before the import
				$do_import = apply_filters('fgj2wp_pre_import_check', true);
				if ( !$do_import) {
					return;
				}

				$this->post_type = ($this->plugin_options['import_as_pages'] == 1) ? 'page' : 'post';

				// Hook for doing other actions before the import
				do_action('fgj2wp_pre_import');

				// Categories
				if ( !isset($this->premium_options['skip_categories']) || !$this->premium_options['skip_categories'] ) {
					$cat_count = $this->import_categories();
					$this->display_admin_notice(sprintf(_n('%d category imported', '%d categories imported', $cat_count, 'fg-joomla-to-wordpress'), $cat_count));
				}

				if ( !isset($this->premium_options['skip_articles']) || !$this->premium_options['skip_articles'] ) {
					// Posts and medias
					$result = $this->import_posts();
					if ( $result === false ) { // Anti-duplicate
						return;
					}
					switch ($this->post_type) {
						case 'page':
							$this->display_admin_notice(sprintf(_n('%d page imported', '%d pages imported', $result['posts_count'], 'fg-joomla-to-wordpress'), $result['posts_count']));
							break;
						case 'post':
						default:
							$this->display_admin_notice(sprintf(_n('%d post imported', '%d posts imported', $result['posts_count'], 'fg-joomla-to-wordpress'), $result['posts_count']));
					}
					$this->display_admin_notice(sprintf(_n('%d media imported', '%d medias imported', $result['media_count'], 'fg-joomla-to-wordpress'), $result['media_count']));

					// Tags
					if ($this->post_type == 'post') {
						if ( $this->plugin_options['meta_keywords_in_tags'] ) {
							$this->display_admin_notice(sprintf(_n('%d tag imported', '%d tags imported', $result['tags_count'], 'fg-joomla-to-wordpress'), $result['tags_count']));
						}
					}
				}

				// Hook for doing other actions after the import
				do_action('fgj2wp_post_import');

				// Hook for other notices
				do_action('fgj2wp_import_notices');

				// Debug info
				if ( defined('WP_DEBUG') && WP_DEBUG ) {
					$this->display_admin_notice(sprintf("Memory used: %s bytes<br />\n", number_format(memory_get_usage())));
					$time_end = microtime(true);
					$this->display_admin_notice(sprintf("Duration: %d sec<br />\n", $time_end - $time_start));
				}

				$this->display_admin_notice(__("Don't forget to modify internal links.", 'fg-joomla-to-wordpress'));

				wp_cache_flush();
			}
		}

		/**
		 * Import categories
		 *
		 * @return int Number of categories imported
		 */
		private function import_categories() {
			$cat_count = 0;
			if ( version_compare($this->plugin_options['version'], '1.5', '<=') ) {
				$sections = $this->get_sections(); // Get the Joomla sections
			} else {
				$sections = array();
			}
			$categories = $this->get_categories(); // Get the Joomla categories
			$categories = array_merge($sections, $categories);
			if ( is_array($categories) ) {
				$terms = array('1'); // unclassified category
				foreach ( $categories as $category ) {

					if ( get_category_by_slug($category['name']) ) {
						continue; // Do not import already imported category
					}

					// Insert the category
					$new_category = array(
						'cat_name' 				=> $category['title'],
						'category_description'	=> $category['description'],
						'category_nicename'		=> $category['name'], // slug
					);

					// Hook before inserting the category
					$new_category = apply_filters('fgj2wp_pre_insert_category', $new_category, $category);

					if ( ($cat_id = wp_insert_category($new_category)) !== false ) {
						$cat_count++;
						$terms[] = $cat_id;
					}

					// Hook after inserting the category
					do_action('fgj2wp_post_insert_category', $cat_id, $category);
				}

				// Update the categories with their parent ids
				// We need to do it in a second step because the children categories
				// may have been imported before their parent
				foreach ( $categories as $category ) {
					$cat = get_category_by_slug($category['name']);
					if ( $cat ) {
						// Parent category
						if ( !empty($category['parent']) ) {
							$parent_cat = get_category_by_slug($category['parent']);
							if ( $parent_cat ) {
								// Hook before editing the category
								$cat = apply_filters('fgj2wp_pre_edit_category', $cat, $parent_cat);
								wp_update_term($cat->term_id, 'category', array('parent' => $parent_cat->term_id));
								// Hook after editing the category
								do_action('fgj2wp_post_edit_category', $cat);
							}
						}
					}
				}

				// Hook after importing all the categories
				do_action('fgj2wp_post_import_categories', $categories);

				// Update cache
				if ( !empty($terms) ) {
					wp_update_term_count_now($terms, 'category');
					$this->clean_cache($terms);
				}
			}
			return $cat_count;
		}

		/**
		 * Clean the cache
		 * 
		 */
		public function clean_cache($terms = array()) {
			delete_option("category_children");
			clean_term_cache($terms, 'category');
		}

		/**
		 * Import posts
		 *
		 * @param bool $test_mode Test mode active: import only one post
		 * @return array:
		 * 		int posts_count: Number of posts imported
		 * 		int media_count: Number of medias imported
		 */
		private function import_posts($test_mode = false) {
			$posts_count = 0;
			$media_count = 0;
			$imported_tags = array();
			$step = $test_mode? 1 : 1000; // to limit the results

			$tab_categories = $this->tab_categories(); // Get the categories list

			// Set the WordPress post ID to start the deletion (used when we want to remove only the new imported posts)
			$start_id = intval(get_option('fgj2wp_start_id'));
			if ( $start_id == 0) {
				$start_id = $this->get_next_post_autoincrement();
				update_option('fgj2wp_start_id', $start_id);
			}

			// Hook for doing other actions before the import
			do_action('fgj2wp_pre_import_posts');

			$test_antiduplicate = false;
			
			do {
				$posts = $this->get_posts($step); // Get the Joomla posts

				if ( is_array($posts) ) {
					foreach ( $posts as $post ) {

						// Archived posts not imported
						if ( ($this->plugin_options['archived_posts'] == 'not_imported') && (in_array($post['state'], array(-1, 2))) ) {
							update_option('fgj2wp_last_joomla_id', $post['id']);
							continue;
						}

						// Anti-duplicate
						if ( !$test_antiduplicate ) {
							sleep(2);
							$test_post_id = $this->get_wp_post_id_from_joomla_id($post['id']);
							if ( !empty($test_post_id) ) {
								$this->display_admin_error(__('The import process is still running. Please wait before running it again.', 'fg-joomla-to-wordpress'));
								return false;
							}
							$test_antiduplicate = true;
						}
						
						// Hook for modifying the Joomla post before processing
						$post = apply_filters('fgj2wp_pre_process_post', $post);

						// Date
						$post_date = ($post['date'] != '0000-00-00 00:00:00')? $post['date']: $post['modified'];

						// Medias
						if ( !$this->plugin_options['skip_media'] ) {
							// Extra featured image
							$featured_image = '';
							list($featured_image, $post) = apply_filters('fgj2wp_pre_import_media', array($featured_image, $post));
							// Import media
							$result = $this->import_media_from_content($featured_image . $post['introtext'] . $post['fulltext'], $post_date);
							$post_media = $result['media'];
							$media_count += $result['media_count'];
						} else {
							// Skip media
							$post_media = array();
						}

						// Categories IDs
						$categories = array($post['category']);
						// Hook for modifying the post categories
						$categories = apply_filters('fgj2wp_post_categories', $categories, $post);
						$categories_ids = array();
						foreach ( $categories as $category_name ) {
							$category = sanitize_title($category_name);
							if ( array_key_exists($category, $tab_categories) ) {
								$categories_ids[] = $tab_categories[$category];
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
							$imported_tags = array_merge($imported_tags, $tags);
						}

						// Insert the post
						$new_post = array(
							'post_category'		=> $categories_ids,
							'post_content'		=> $content,
							'post_date'			=> $post_date,
							'post_excerpt'		=> $excerpt,
							'post_status'		=> $status,
							'post_title'		=> $post['title'],
							'post_name'			=> $post['alias'],
							'post_type'			=> $this->post_type,
							'tags_input'		=> $tags,
							'menu_order'        => $post['ordering'],
						);

						// Hook for modifying the WordPress post just before the insert
						$new_post = apply_filters('fgj2wp_pre_insert_post', $new_post, $post);

						$new_post_id = wp_insert_post($new_post);

						if ( $new_post_id ) {
							// Add links between the post and its medias
							$this->add_post_media($new_post_id, $new_post, $post_media, $this->plugin_options['first_image'] != 'as_is');

							// Add the Joomla ID as a post meta in order to modify links after
							add_post_meta($new_post_id, '_fgj2wp_old_id', $post['id'], true);

							// Increment the Joomla last imported post ID
							update_option('fgj2wp_last_joomla_id', $post['id']);

							$posts_count++;

							// Hook for doing other actions after inserting the post
							do_action('fgj2wp_post_insert_post', $new_post_id, $post);
						}
					}
				}
			} while ( ($posts != null) && (count($posts) > 0) && !$test_mode);

			// Hook for doing other actions after the import
			do_action('fgj2wp_post_import_posts');

			return array(
				'posts_count'	=> $posts_count,
				'media_count'	=> $media_count,
				'tags_count'	=> count(array_unique($imported_tags)),
			);
		}

		/**
		 * Get Joomla sections
		 *
		 * @return array of Sections
		 */
		private function get_sections() {
			$sections = array();

			$prefix = $this->plugin_options['prefix'];
			$sql = "
				SELECT s.title, CONCAT('s', s.id, '-', IF(s.alias <> '', s.alias, s.name)) AS name, s.description
				FROM ${prefix}sections s
			";
			$sql = apply_filters('fgj2wp_get_sections_sql', $sql, $prefix);
			$sections = $this->joomla_query($sql);
			$sections = apply_filters('fgj2wp_get_sections', $sections);

			return $sections;
		}

		/**
		 * Get Joomla categories
		 *
		 * @return array of Categories
		 */
		private function get_categories() {
			$categories = array();

			$prefix = $this->plugin_options['prefix'];
			if ( version_compare($this->plugin_options['version'], '1.5', '<=') ) {
				$sql = "
					SELECT c.title, CONCAT('c', c.id, '-', IF(c.alias <> '', c.alias, c.name)) AS name, c.description, CONCAT('s', s.id, '-', IF(s.alias <> '', s.alias, s.name)) AS parent
					FROM ${prefix}categories c
					INNER JOIN ${prefix}sections AS s ON s.id = c.section
				";
			} else {
				$sql = "
					SELECT c.title, CONCAT('c', c.id, '-', c.alias) AS name, c.description, CONCAT('c', cp.id, '-', cp.alias) AS parent
					FROM ${prefix}categories c
					INNER JOIN ${prefix}categories AS cp ON cp.id = c.parent_id
					WHERE c.extension = 'com_content'
					ORDER BY c.lft
				";
			}
			$sql = apply_filters('fgj2wp_get_categories_sql', $sql, $prefix);
			$categories = $this->joomla_query($sql);
			$categories = apply_filters('fgj2wp_get_categories', $categories);

			return $categories;
		}

		/**
		 * Get Joomla component categories
		 *
		 * @param string $component Component name
		 * @param string $cat_prefix Category prefix to set
		 * @return array of Categories
		 */
		public function get_component_categories($component, $cat_prefix) {
			$categories = array();

			$prefix = $this->plugin_options['prefix'];
			if ( version_compare($this->plugin_options['version'], '1.5', '<=') ) {
				$sql = "
					SELECT c.title, CONCAT('$cat_prefix', c.id, '-', IF(c.alias <> '', c.alias, c.name)) AS name, c.description, CONCAT('$cat_prefix', cp.id, '-', IF(cp.alias <> '', cp.alias, cp.name)) AS parent
					FROM ${prefix}categories c
					LEFT JOIN ${prefix}categories AS cp ON cp.id = c.parent_id
					WHERE c.section = '$component'
				";
			} else {
				$sql = "
					SELECT c.title, CONCAT('$cat_prefix', c.id, '-', c.alias) AS name, c.description, CONCAT('$cat_prefix', cp.id, '-', cp.alias) AS parent
					FROM ${prefix}categories c
					LEFT JOIN ${prefix}categories AS cp ON cp.id = c.parent_id
					WHERE c.extension = '$component'
					ORDER BY c.lft
				";
			}
			$sql = apply_filters('fgj2wp_get_categories_sql', $sql, $prefix);
			$categories = $this->joomla_query($sql);
			return $categories;
		}

		/**
		 * Get Joomla posts
		 *
		 * @param int limit Number of posts max
		 * @return array of Posts
		 */
		protected function get_posts($limit=1000) {
			$posts = array();

			$last_joomla_id = (int)get_option('fgj2wp_last_joomla_id'); // to restore the import where it left
			$prefix = $this->plugin_options['prefix'];

			// The "name" column disappears in version 1.6+
			if ( version_compare($this->plugin_options['version'], '1.5', '<=') ) {
				$cat_field = "IF(c.alias <> '', c.alias, c.name)";
			} else {
				$cat_field = 'c.alias';
			}

			// Hooks for adding extra cols and extra joins
			$extra_cols = apply_filters('fgj2wp_get_posts_add_extra_cols', '');
			$extra_joins = apply_filters('fgj2wp_get_posts_add_extra_joins', '');

			$sql = "
				SELECT DISTINCT p.id, p.title, p.alias, p.introtext, p.fulltext, p.state, CONCAT('c', c.id, '-', $cat_field) AS category, p.modified, p.created AS `date`, p.attribs, p.metakey, p.metadesc, p.ordering
				$extra_cols
				FROM ${prefix}content p
				LEFT JOIN ${prefix}categories AS c ON p.catid = c.id
				$extra_joins
				WHERE p.state >= -1 -- don't get the trash
				AND p.id > '$last_joomla_id'
				ORDER BY p.id
				LIMIT $limit
			";
			$sql = apply_filters('fgj2wp_get_posts_sql', $sql, $prefix, $cat_field, $extra_cols, $extra_joins, $last_joomla_id, $limit);
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
					// Introtext imported in post content with a "Read more" tag
					$content = $post['introtext'] . "\n<!--more-->\n" . $post['fulltext'];
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
				$post_attribs = parse_ini_string($attribs, false, INI_SCANNER_RAW);
			} else {
				$post_attribs = json_decode($attribs, true);
			}
			return $post_attribs;
		}

		/**
		 * Return an array with all the categories sorted by name
		 *
		 * @return array categoryname => id
		 */
		public function tab_categories() {
			$tab_categories = array();
			$categories = get_categories(array('hide_empty' => '0'));
			if ( is_array($categories) ) {
				foreach ( $categories as $category ) {
					$tab_categories[$category->slug] = $category->term_id;
				}
			}
			return $tab_categories;
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
			
			if ( preg_match_all('#<(img|a)(.*?)(src|href)="(.*?)"(.*?)>#', $content, $matches, PREG_SET_ORDER) > 0 ) {
				if ( is_array($matches) ) {
					foreach ($matches as $match ) {
						$filename = $match[4];
						$other_attributes = $match[2] . $match[5];
						// Image Alt
						$image_alt = '';
						if (preg_match('#alt="(.*?)"#', $other_attributes, $alt_matches) ) {
							$image_alt = wp_strip_all_tags(stripslashes($alt_matches[1]), true);
						}
						$attachment_id = $this->import_media($image_alt, $filename, $post_date, $options);
						if ( $attachment_id !== false ) {
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
		 * @return int attachment ID or false
		 */
		public function import_media($name, $filename, $date, $options=array()) {
			if ( $date == '0000-00-00 00:00:00' ) {
				$date = date('Y-m-d H:i:s');
			}
			$import_external = ($this->plugin_options['import_external'] == 1) || (isset($options['force_external']) && $options['force_external'] );
			
			$filename = urldecode($filename); // for filenames with spaces or accents
			
			$filetype = wp_check_filetype($filename);
			if ( empty($filetype['type']) || ($filetype['type'] == 'text/html') ) { // Unrecognized file type
				return false;
			}

			// Upload the file from the Joomla web site to WordPress upload dir
			if ( preg_match('/^http/', $filename) ) {
				if ( $import_external || // External file 
					preg_match('#^' . $this->plugin_options['url'] . '#', $filename) // Local file
				) {
					$old_filename = $filename;
				} else {
					return false;
				}
			} else {
				if ( strpos($filename, '/') == 0 ) { // Avoid a double slash
					$old_filename = untrailingslashit($this->plugin_options['url']) . $filename;
				} else {
					$old_filename = trailingslashit($this->plugin_options['url']) . $filename;
				}
			}
			$old_filename = str_replace(" ", "%20", $old_filename); // for filenames with spaces
			$img_dir = strftime('%Y/%m', strtotime($date));
			$uploads = wp_upload_dir($img_dir);
			$new_upload_dir = $uploads['path'];

			$new_filename = $filename;
			if ( $this->plugin_options['import_duplicates'] == 1 ) {
				// Images with duplicate names
				$new_filename = preg_replace('#.*images/stories/#', '', $new_filename);
				$new_filename = preg_replace('#.*media/k2#', 'k2', $new_filename);
				$new_filename = str_replace('http://', '', $new_filename);
				$new_filename = str_replace('/', '_', $new_filename);
			}

			$basename = basename($new_filename);
			$new_full_filename = $new_upload_dir . '/' . $basename;

//			print "Copy \"$old_filename\" => $new_full_filename<br />";
			if ( ! @$this->remote_copy($old_filename, $new_full_filename) ) {
				$error = error_get_last();
				$error_message = $error['message'];
				$this->display_admin_error("Can't copy $old_filename to $new_full_filename : $error_message");
				return false;
			}
			
			$post_title = !empty($name)? $name : preg_replace('/\.[^.]+$/', '', $basename);
			
			// Image Alt
			$image_alt = '';
			if ( !empty($name) ) {
				$image_alt = wp_strip_all_tags(stripslashes($name), true);
			}
			// GUID
			$guid = $uploads['url'] . '/' . $basename;
			
			$attachment_id = $this->insert_attachment($post_title, $basename, $new_full_filename, $guid, $date, $filetype['type'], $image_alt);
			return $attachment_id;
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
		 * @return int|false Attachment ID or false
		 */
		public function insert_attachment($attachment_title, $basename, $new_full_filename, $guid, $date, $filetype, $image_alt='') {
			$post_name = sanitize_title($attachment_title);
			
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
				return false;
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
				return false;
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
				$attachments_found = false;
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
					$attachments_found = true;
				}
				if ( $attachments_found ) {

					// Remove the links from the content
					$this->post_link_count = 0;
					$this->post_link = array();
					$content = preg_replace_callback('#<(a) (.*?)(href)=(.*?)</a>#i', array($this, 'remove_links'), $content);
					$content = preg_replace_callback('#<(img) (.*?)(src)=(.*?)>#i', array($this, 'remove_links'), $content);

					// Process the stored medias links
					$first_image_removed = false;
					foreach ($this->post_link as &$link) {

						// Remove the first image from the content
						if ( ($this->plugin_options['first_image'] == 'as_featured') && !$first_image_removed && preg_match('#^<img#', $link['old_link']) ) {
							$link['new_link'] = '';
							$first_image_removed = true;
							continue;
						}
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
												if ((strpos($new_link, 'width=') === false) && (strpos($new_link, 'height=') === false)) {
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
		public function add_post_media($post_id, $post_data, $post_media, $set_featured_image=true) {
			$thumbnail_is_set = false;
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
							$thumbnail_is_set = true;
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
			if ( strpos($content, '{"video"') !== false ) {
				$content = preg_replace('/(<p>)?{"video":"(.*?)".*?}(<\/p>)?/', "$2", $content);
			}
			if ( strpos($content, '{audio}') !== false ) {
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
					$post = apply_filters('fgj2wp_post_get_post', $post); // Used to translate the links
					$content = $post->post_content;
					if ( preg_match_all('#<a(.*?)href="(.*?)"(.*?)>#', $content, $matches, PREG_SET_ORDER) > 0 ) {
						if ( is_array($matches) ) {
							foreach ( $matches as $match ) {
								$link = $match[2];
								list($link_without_anchor, $anchor_link) = $this->split_anchor_link($link); // Split the anchor link
								// Is it an internal link ?
								if ( $this->is_internal_link($link_without_anchor) ) {
									$linked_post = $this->get_wp_post_from_joomla_url($link_without_anchor);
									if ( $linked_post ) {
										$linked_post_id = $linked_post->ID;
										$linked_post_id = apply_filters('fgj2wp_post_get_post_by_joomla_id', $linked_post_id, $post); // Used to get the ID of the translated post
										$new_link = get_permalink($linked_post_id);
										if ( !empty($anchor_link) ) {
											$new_link .= '#' . $anchor_link;
										}
										$content = str_replace("href=\"$link\"", "href=\"$new_link\"", $content);
										// Update the post
										wp_update_post(array(
											'ID'			=> $post->ID,
											'post_content'	=> $content,
										));
										$links_count++;
									}
								}
							}
						}
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
				(preg_match("#^http#", $link) == 0);
			return $result;
		}
		
		/**
		 * Get a WordPress post that matches a Joomla URL
		 * 
		 * @param string $url URL
		 * @return Post WordPress post | false
		 */
		private function get_wp_post_from_joomla_url($url) {
			$post = false;
			$meta_key_value = $this->get_joomla_id_in_link($url);
			$joomla_id = $meta_key_value['meta_value'];
			// Can we find an ID in the link ?
			if ( $joomla_id != 0 ) {
				// Get the linked post
				$posts =  get_posts(array(
					'numberposts'	=> 1,
					'post_type'		=> 'any',
					'meta_key'		=> $meta_key_value['meta_key'],
					'meta_value'	=> $joomla_id,
				));
				if ( is_array($posts) && (count($posts) > 0) ) {
					$post = $posts[0];
				}
			}
			if ( !$post ) {
				$post = apply_filters('fgj2wp_get_wp_post_from_joomla_url', $post, $url);
			}
			return $post;
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
				if ( preg_match("#id=(\d+)#", $link, $matches) ) {
					$meta_key_value['meta_value'] = $matches[1];
				}
				// With URL rewriting
				elseif ( preg_match("#(.*)/(\d+)-(.*)#", $link, $matches) ) {
					$meta_key_value['meta_value'] = $matches[2];
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
			if ( $pos !== false ) {
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
			 * cwg enhancement: if destination already exists, just return true
			 *  this allows rebuilding the wp media db without moving files
			 */
			if ( !$this->plugin_options['force_media_import'] && file_exists($path) && (filesize($path) > 0) ) {
				return true;
			}

			$response = wp_remote_get($url, array(
				'timeout'     => $this->plugin_options['timeout'],
			)); // Uses WordPress HTTP API

			if ( is_wp_error($response) ) {
				trigger_error($response->get_error_message(), E_USER_WARNING);
				return false;
			} elseif ( $response['response']['code'] != 200 ) {
				trigger_error($response['response']['message'], E_USER_WARNING);
				return false;
			} else {
				file_put_contents($path, wp_remote_retrieve_body($response));
				return true;
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
				return true;
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
		 * Get the next post autoincrement
		 * 
		 * @return int post ID
		 */
		private function get_next_post_autoincrement() {
			global $wpdb;

			$sql = "SHOW TABLE STATUS LIKE '$wpdb->posts'";
			$row = $wpdb->get_row($sql);
			if ( $row ) {
				return $row->Auto_increment;
			} else {
				return 0;
			}
		}

		/**
		 * Remove the prefixes from the categories
		 */
		private function remove_category_prefix() {
			$matches = array();

			// Hook for doing other actions before removing the prefixes
			do_action('fgj2wp_pre_remove_category_prefix');

			$categories = get_terms( 'category', array('hide_empty' => 0) );
			if ( !empty($categories) ) {
				foreach ( $categories as $cat ) {
					if ( preg_match('/^(s|c(d|e|k|z)?)\d+-(.*)/', $cat->slug, $matches) ) {
						wp_update_term($cat->term_id, 'category', array(
							'slug' => $matches[3]
						));
					}
				}
			}

			// Hook for doing other actions after removing the prefixes
			do_action('fgj2wp_post_remove_category_prefix');
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
					$params = parse_ini_string($result[0]['params'], false, INI_SCANNER_RAW);
				} else {
					$params = json_decode($result[0]['params'], true);
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
		 * @return array of post IDs [joomla_article_id => wordpress_post_id]
		 */
		public function get_imported_joomla_posts() {
			global $wpdb;
			$posts = array();

			$sql = "SELECT post_id, meta_value FROM {$wpdb->postmeta} WHERE meta_key = '_fgj2wp_old_id'";
			$results = $wpdb->get_results($sql);
			foreach ( $results as $result ) {
				$posts[$result->meta_value] = $result->post_id;
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
		 * Returns the imported categories mapped with their Joomla ID
		 *
		 * @return array of category IDs [joomla_category_id => wordpress_category_id]
		 */
		public function get_imported_joomla_categories() {
			global $wpdb;
			$categories = array();
			$matches = array();

			$sql = "SELECT term_id, slug FROM {$wpdb->terms} WHERE slug LIKE 'c%'";
			$results = $wpdb->get_results($sql);
			foreach ( $results as $result ) {
				if ( preg_match("/^c(\d+)-/", $result->slug, $matches) ) {
					$cat_id = $matches[1];
					$categories[$cat_id] = $result->term_id;
				}
			}
			ksort($categories);
			return $categories;
		}

		/**
		 * Returns the imported sections mapped with their Joomla ID
		 *
		 * @return array of section IDs [joomla_section_id => wordpress_category_id]
		 */
		public function get_imported_joomla_sections() {
			global $wpdb;
			$sections = array();
			$matches = array();

			$sql = "SELECT term_id, slug FROM {$wpdb->terms} WHERE slug LIKE 's%'";
			$results = $wpdb->get_results($sql);
			foreach ( $results as $result ) {
				if ( preg_match("/^s(\d+)-/", $result->slug, $matches) ) {
					$section_id = $matches[1];
					$sections[$section_id] = $result->term_id;
				}
			}
			ksort($sections);
			return $sections;
		}

		/**
		 * Returns the imported users mapped with their Joomla ID
		 *
		 * @return array of user IDs [joomla_user_id => wordpress_user_id]
		 */
		public function get_imported_joomla_users() {
			global $wpdb;
			$users = array();

			$sql = "SELECT user_id, meta_value FROM {$wpdb->usermeta} WHERE meta_key = 'joomla_user_id'";
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
				if ( $query !== false ) {
					$result = $query->fetch();
					return !empty($result);
				} else {
					return false;
				}
			} catch ( PDOException $e ) {}
			return false;
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
				if ( $query !== false ) {
					$result = $query->fetch();
					return !empty($result);
				} else {
					return false;
				}
			} catch ( PDOException $e ) {}
			return false;
		}

		/**
		 * Test if a remote file exists
		 * 
		 * @param string $filePath
		 * @return boolean True if the file exists
		 */
		public function url_exists($filePath) {
			$url = str_replace(' ', '%20', $filePath);
			$url = str_replace('http://', '', $url);
			if ( strstr($url, '/') ) {
				$url = explode('/', $url, 2);
				$url[1] = '/' . $url[1];
			} else {
				$url = array($url, '/');
			}

			$fh = fsockopen($url[0], 80);
			if ( $fh ) {
				fputs($fh,'GET ' . $url[1] . " HTTP/1.1\nHost:" . $url[0] . "\n\n");
				$response = fread($fh, 22);
				fclose($fh);
				return (strpos($response, '200') !== false);
			} else {
				return false;
			}
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
