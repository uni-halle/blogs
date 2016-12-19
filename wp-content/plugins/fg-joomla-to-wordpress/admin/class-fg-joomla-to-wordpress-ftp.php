<?php
/**
 * FTP module
 *
 * @link       https://www.fredericgilles.net/fg-joomla-to-wordpress/
 * @since      2.7.0
 *
 * @package    FG_Joomla_to_WordPress_Premium
 * @subpackage FG_Joomla_to_WordPress_Premium/admin
 */

if ( !class_exists('FG_Joomla_to_WordPress_FTP', FALSE) ) {

	/**
	 * Tags class
	 *
	 * @package    FG_Joomla_to_WordPress_Premium
	 * @subpackage FG_Joomla_to_WordPress_Premium/admin
	 * @author     Frédéric GILLES
	 */
	class FG_Joomla_to_WordPress_FTP {

		/**
		 * Initialize the class and set its properties.
		 *
		 * @since    2.7.0
		 * @param    object    $plugin       Admin plugin
		 */
		public function __construct( $plugin ) {

			$this->plugin = $plugin;
			
			// Default values
			$this->plugin->ftp_options = array(
				'hostname'			=> '',
				'port'				=> 21,
				'username'			=> '',
				'password'			=> '',
				'connection_type'	=> '',
				'basedir'			=> '',
			);
			$options = get_option('fgj2wp_ftp_options');
			if ( is_array($options) ) {
				$this->plugin->ftp_options = array_merge($this->plugin->ftp_options, $options);
			}
		}
		
		/**
		 * Display the FTP settings
		 * 
		 */
		function display_ftp_settings() {
			$data = array();
			$data['ftp_host'] = $this->plugin->ftp_options['hostname'];
			$data['ftp_port'] = $this->plugin->ftp_options['port'];
			$data['ftp_login'] = $this->plugin->ftp_options['username'];
			$data['ftp_password'] = $this->plugin->ftp_options['password'];
			$data['ftp_connection_type'] = $this->plugin->ftp_options['connection_type'];
			$data['ftp_dir'] = $this->plugin->ftp_options['basedir'];
			require('partials/ftp-settings.php');
		}

		/**
		 * Save the FTP settings
		 * 
		 */
		function save_ftp_settings() {
			$this->plugin->ftp_options = array_merge($this->plugin->ftp_options, $this->validate_form_info());
			update_option('fgj2wp_ftp_options', $this->plugin->ftp_options);
		}
		
		/**
		 * Validate POST info
		 *
		 * @return array Form parameters
		 */
		private function validate_form_info() {
			$ftp_host = filter_input(INPUT_POST, 'ftp_host', FILTER_SANITIZE_STRING);
			$ftp_port = filter_input(INPUT_POST, 'ftp_port', FILTER_SANITIZE_STRING);
			$ftp_login = filter_input(INPUT_POST, 'ftp_login', FILTER_SANITIZE_STRING);
			$ftp_password = filter_input(INPUT_POST, 'ftp_password', FILTER_SANITIZE_STRING);
			$ftp_connection_type = filter_input(INPUT_POST, 'ftp_connection_type', FILTER_SANITIZE_STRING);
			$ftp_dir = filter_input(INPUT_POST, 'ftp_dir', FILTER_SANITIZE_STRING);
			return array(
				'hostname'			=> isset($ftp_host)? $ftp_host : '',
				'port'				=> isset($ftp_port)? $ftp_port : '',
				'username'			=> isset($ftp_login)? $ftp_login : '',
				'password'			=> isset($ftp_password)? $ftp_password : '',
				'connection_type'	=> (isset($ftp_connection_type) && $ftp_connection_type == 'ftps')? 'ftps' : '',
				'basedir'			=> isset($ftp_dir)? $ftp_dir : '',
			);
		}
		
		/**
		 * Test FTP connection
		 *
		 */
		public function test_ftp_connection($action) {
			if ( $action == 'test_ftp' ) {

				// Save database options
				$this->plugin->save_plugin_options();

				// Test the database connection
				if ( check_admin_referer( 'parameters_form', 'fgj2wp_nonce' ) ) { // Security check
					if ( $this->test_connection() ) {
						$result = array('status' => 'OK', 'message' => __('FTP connection successful', 'fg-joomla-to-wordpress'));
					} else {
						$result = array('status' => 'Error', 'message' => __('FTP connection failed', 'fg-joomla-to-wordpress'));
					}
					echo json_encode($result);
				}
			}
		}

		/**
		 * Test FTP connection
		 *
		 * @return bool Connection successful or not
		 */
		public function test_connection() {
			$result = FALSE;
			if ( $this->login()) {
				$this->plugin->display_admin_notice(__('FTP connection successful', 'fg-joomla-to-wordpress'));
				$result = TRUE;
			}
			return $result;
		}

		/**
		 * FTP login
		 *
		 * @return bool Login successful or not
		 */
		public function login() {
			$result = FALSE;
			
			if ( !defined('FS_CONNECT_TIMEOUT') ) {
				define('FS_CONNECT_TIMEOUT', 2);
			}
			$this->ftp = new WP_Filesystem_FTPext($this->plugin->ftp_options);
			if ( $this->ftp->connect() && $this->ftp->chdir($this->plugin->ftp_options['basedir']) ) {
				// Connection successful
				$result = TRUE;
			} else {
				// Connection error
				$error_message = '';
				if ( isset($this->ftp->errors->errors) ) {
					$errors = $this->ftp->errors->errors;
					foreach ( $errors as $key => $value ) {
						$error_message = "$key => " . implode("\n", $value) . "\n";
					}
				}
				$this->plugin->display_admin_error(__('FTP connection failed:', 'fg-joomla-to-wordpress') . ' ' . $error_message);
			}
			return $result;
		}
		
		/**
		 * List a FTP directory
		 *
		 * @param string $directory Directory
		 * @return array List of files
		 */
		public function list_directory($directory) {
			$files_list = array();
			
			if ( $this->ftp->link) {
				$full_directory = trailingslashit($this->plugin->ftp_options['basedir']) . $directory;
				return array_keys($this->ftp->dirlist($full_directory, FALSE));
			}
			return $files_list;
		}

		/**
		 * Change the current FTP directory
		 *
		 * @param string $directory Directory
		 * @return bool
		 */
		public function chdir($directory) {
			$result = FALSE;
			if ( $this->ftp->link ) {
				$full_directory = trailingslashit($this->plugin->ftp_options['basedir']) . $directory;
				$result = $this->ftp->chdir($full_directory);
			}
			return $result;
		}

		/**
		 * Get a file
		 *
		 * @param string $source Original filename
		 * @param string $destination Destination filename
		 * @return bool File downloaded or not
		 */
		public function get($source, $destination) {
			$result = FALSE;
			
			if ( $this->ftp->link) {
				$file_content = $this->ftp->get_contents($source);
				if ( $file_content ) {
					$result = (file_put_contents($destination, $file_content) !== FALSE);
				}
			}
			return $result;
		}
	}
}
