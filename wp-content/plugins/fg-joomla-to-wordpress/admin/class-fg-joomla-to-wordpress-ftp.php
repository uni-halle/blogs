<?php
/**
 * FTP module
 *
 * @link       http://www.fredericgilles.net/fg-joomla-to-wordpress/
 * @since      2.7.0
 *
 * @package    FG_Joomla_to_WordPress_Premium
 * @subpackage FG_Joomla_to_WordPress_Premium/admin
 */

if ( !class_exists('FG_Joomla_to_WordPress_FTP', false) ) {

	/**
	 * Tags class
	 *
	 * @package    FG_Joomla_to_WordPress_Premium
	 * @subpackage FG_Joomla_to_WordPress_Premium/admin
	 * @author     Frédéric GILLES
	 */
	class FG_Joomla_to_WordPress_FTP {

		public $conn_id = false;

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
				'ftp_host'		=> '',
				'ftp_login'		=> '',
				'ftp_password'	=> '',
				'ftp_dir'		=> '',
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
			foreach ( $this->plugin->ftp_options as $key => $value ) {
				$data[$key] = $value;
			}
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
			$ftp_login = filter_input(INPUT_POST, 'ftp_login', FILTER_SANITIZE_STRING);
			$ftp_password = filter_input(INPUT_POST, 'ftp_password', FILTER_SANITIZE_STRING);
			$ftp_dir = filter_input(INPUT_POST, 'ftp_dir', FILTER_SANITIZE_STRING);
			return array(
				'ftp_host'		=> isset($ftp_host)? $ftp_host : '',
				'ftp_login'		=> isset($ftp_login)? $ftp_login : '',
				'ftp_password'	=> isset($ftp_password)? $ftp_password : '',
				'ftp_dir'		=> isset($ftp_dir)? $ftp_dir : '',
			);
		}
		
		/**
		 * Test FTP connection
		 *
		 */
		public function test_ftp_connection() {
			if ( isset($_POST['ftp_test']) ) {

				// Save database options
				$this->plugin->save_plugin_options();

				// Test the database connection
				if ( check_admin_referer( 'parameters_form', 'fgj2wp_nonce' ) ) { // Security check
					$this->test_connection();
				}
			}
		}

		/**
		 * FTP login
		 *
		 * @return bool Login successful or not
		 */
		public function login() {
			$result = false;
			
			// Catch the warnings
			set_error_handler(array($this, 'myErrorHandler'));
			
			try {
				$this->conn_id = $this->connect();
				if ( $this->conn_id ) {
					if ( is_a($this->conn_id, 'Net_SFTP') ) {
						if ( $this->conn_id->login($this->plugin->ftp_options['ftp_login'], $this->plugin->ftp_options['ftp_password']) ) {
							$result = $this->conn_id->chdir($this->plugin->ftp_options['ftp_dir']);
						}
					} else {
						if ( ftp_login($this->conn_id, $this->plugin->ftp_options['ftp_login'], $this->plugin->ftp_options['ftp_password']) ) {
							$result = ftp_chdir($this->conn_id, $this->plugin->ftp_options['ftp_dir']);
						}
					}
					if ( !$result ) {
						$this->plugin->display_admin_error(__('FTP connection failed', 'fg-joomla-to-wordpress'));
						$this->logout();
						$this->conn_id = false;
					}
				}
			} catch ( ErrorException $e ) {
				$this->plugin->display_admin_error(__('FTP connection failed', 'fg-joomla-to-wordpress') . '<br />' . $e->getMessage());
				$this->conn_id = false;
				$result = false;
			}
			restore_error_handler();
			return $result;
		}
		
		/**
		 * Error handler to catch the FTP warnings
		 * 
		 * @param int $errno
		 * @param string $errstr
		 * @param string $errfile
		 * @param int $errline
		 */
		public function myErrorHandler($errno, $errstr, $errfile, $errline) {
			// error was suppressed with the @-operator
			if (0 === error_reporting()) {
				return false;
			}
			throw new ErrorException($errstr, 0, $errno, $errfile, $errline);
		}
		
		/**
		 * FTP connect
		 *
		 * @return resource Connection ID
		 */
		private function connect() {
			$timeout = 2;
			$host_port = explode(':', $this->plugin->ftp_options['ftp_host']);
			$host = $host_port[0];
			// Determine the port
			if ( isset($host_port[1]) ) {
				$port = $host_port[1];
			} else {
				$port = 21; // Default FTP port
			}
			$conn_id = ftp_connect($host, $port, $timeout);
			if ( !$conn_id ) {
				// Try to connect through SSH
				set_include_path(get_include_path() . PATH_SEPARATOR . dirname(__FILE__) . '/phpseclib1.0.0');
				require_once('Net/SFTP.php');
				$conn_id = new Net_SFTP($host, $port, $timeout);
			}
			return $conn_id;
		}

		/**
		 * FTP logout
		 */
		public function logout() {
			if ( is_a($this->conn_id, 'Net_SFTP') ) {
				$this->conn_id->disconnect();
			} else {
				ftp_close($this->conn_id);
			}
		}

		/**
		 * Test FTP connection
		 *
		 * @return bool Connection successful or not
		 */
		public function test_connection() {
			$result = false;
			if ( $this->login()) {
				$this->plugin->display_admin_notice(__('FTP connection successful', 'fg-joomla-to-wordpress'));
				$this->logout();
				$result = true;
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
			
			// Catch the warnings
			set_error_handler(array($this, 'myErrorHandler'));
			
			try {
				if ( $this->conn_id ) {
					$full_dir = trailingslashit($this->plugin->ftp_options['ftp_dir']) . $directory;
					if ( is_a($this->conn_id, 'Net_SFTP') ) {
						if ( $this->conn_id->chdir($full_dir) ) {
							return $this->conn_id->nlist();
						}
					} else {
						if ( ftp_chdir($this->conn_id, $full_dir) ) {
							$files_list = ftp_nlist($this->conn_id, '');
						}
					}
				}
			} catch ( ErrorException $e ) {
				$this->plugin->display_admin_error(__('FTP connection failed', 'fg-joomla-to-wordpress') . '<br />' . $e->getMessage());
			}
			restore_error_handler();
			return $files_list;
		}

		/**
		 * Get a file
		 *
		 * @param string $source Original filename
		 * @param string $destination Destination filename
		 * @return bool File downloaded or not
		 */
		public function get($source, $destination) {
			if ( is_a($this->conn_id, 'Net_SFTP') ) {
				return $this->conn_id->get($source, $destination);
			} else {
				return ftp_get($this->conn_id, $destination, $source, FTP_BINARY);
			}
		}
	}
}
