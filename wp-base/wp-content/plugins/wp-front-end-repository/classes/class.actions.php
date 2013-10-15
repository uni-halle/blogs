<?php
/*
 * doing all action/hooking up
 */

class nmRepositoryAction {
	
	var $obj = '';	//the main object for plugin
	
	var $plugin_shortname = 'nm_repository';
	var $plugin_fullname = 'N-Media Repository Manager';
	
	var $plugin_path, $plugin_url, $currentUser, $rootPath;
	
	var $files_per_page, $total_pages;
	
	static $tblName = 'nm_repositories';
	static $nm_repo_db_version = '1.0.0';
	
	var $nm_repo_settings = '';
	
	function __construct(){
		
		
		$this -> obj = new nmRepository();
		
		$this -> plugin_path = WP_PLUGIN_DIR . '/wp-front-end-repository';
		$this -> plugin_url = WP_PLUGIN_URL . '/wp-front-end-repository';
		
		//getting saved settings
		$this -> nm_repo_settings = get_option('nm_repo_settings');
		
		/*
		 * hooking up scripts for front end
		*/
		add_action('wp_enqueue_scripts', array($this, 'loadJS'));
		add_action('wp_print_styles', array($this, 'loadStyle'));
		
		
		/*
		 * hooking up scripts for front Admin
		*/
		add_action('admin_enqueue_scripts', array($this, 'loadJSAdmin'));
		
		
		/*
		 * adding shortcode
		*/
		add_shortcode('nm-wp-repo', array($this, 'renderRepo_open'));
		
		
		// activate textdomain for translations
		add_action('init', array($this, 'nmRepositoryLocale'));
		
		
	}
	
	/*
	 * loading JS scripts
	*/
	function loadJS(){
	
		wp_enqueue_script('jquery');
		
		global $user_login;
		get_currentuserinfo();
		
		$this -> currentUser = $user_login;
		
		$arrLocalizedVars = array('ajaxurl' => admin_url( 'admin-ajax.php' ),
				'plugin_url' 		=> $this -> plugin_url,
				'loading_image_url' => $this -> plugin_url.'/images/loading.gif',
				'current_user'		=> $user_login,
				'filesaved_message'	=> $this -> getOption('_file_saved'),
				'dircreated_message'	=> $this -> getOption('_dir_created'),
				'repo_settings'	=> $this -> nm_repo_settings,
				'file_meta'		=> '',
				'parent_id'			=> ($_REQUEST['dir'] != '') ? intval($_REQUEST['dir']) : 0);
		
		wp_enqueue_script( 'nm_repository_script', $this -> plugin_url . '/js/script.js', array( 'jquery' ) );
		wp_localize_script( 'nm_repository_script', 'nm_repository_vars', $arrLocalizedVars);
		
		/*
		 * swfobject script
		*/
		wp_register_script('repo_swfobject', $this -> plugin_url.'/js/uploadify/swfobject.js', __FILE__);
		wp_enqueue_script('repo_swfobject');
		
		/*
		 * uploadify core script
		*/
		wp_register_script('repo_uploadify', $this -> plugin_url.'/js/uploadify/jquery.uploadify.v2.1.4.min.js', __FILE__);
		wp_enqueue_script('repo_uploadify');
	}
	
	
	/*
	 * this function is extrating single
	 * option from nm_repo_settings serialzied object/data
	 */
	
	function getOption($key){
		
		//HINT: $key should be under schore (_) prefix
		
		$fullKey = $this -> plugin_shortname . $key;
		return stripcslashes( $this -> nm_repo_settings[ $fullKey ] );
	}
	
	/*
	 * loading style
	*/
	
	function loadStyle(){

		wp_register_style('repo_uploader_stylesheet', $this -> plugin_url .'/styles.css', __FILE__);
		wp_enqueue_style( 'repo_uploader_stylesheet');
		
		
		wp_register_style('repo_uploadify_stylesheet', $this -> plugin_url.'/js/uploadify/uploadify.css', __FILE__);
		wp_enqueue_style( 'repo_uploadify_stylesheet');
		
		
	}
	
	/*
	 * loading Admin script
	 */
	
	function loadJSAdmin(){
		
		wp_register_script('repo_easytabs_script', $this -> plugin_url.'/js/easytabs/jquery.easytabs.js');
		wp_register_script("repo_global_script", $this -> plugin_url.'/js/nm-global.js');
		
		wp_register_script("nm_repository_scrtip_admin", $this -> plugin_url.'/js/admin.js');
		
	}
	
	/*
	 * loading admin side style
	 */
	function loadStyleAdmin(){
		
		wp_register_style('repo_tabs_style', $this -> plugin_url.'/js/easytabs/tabs.css');
		wp_enqueue_style( 'repo_tabs_style');
		
		wp_register_style('repo_admin_style', $this -> plugin_url.'/includes/admin/admin.css');
		wp_enqueue_style( 'repo_admin_style');
		
		/*
		 * Yes: script is being enqueued here
		 * because it will only shown on plugin admin page
		 */
		wp_enqueue_script('repo_easytabs_script');
		wp_enqueue_script('repo_global_script');
		
		$arrLocalizedVars = array('plugin_url' => $this -> plugin_url,
				'loading_image_url' => $this -> plugin_url.'/images/loading.gif');
		
		wp_enqueue_script('nm_repository_scrtip_admin');
		wp_localize_script( 'nm_repository_scrtip_admin', 'nm_repository_vars', $arrLocalizedVars);
		
		
		
		/* wp_register_style('nm_repository_style_admin', plugins_url('options.css', __FILE__));
		wp_enqueue_style( 'nm_repository_style_admin'); */
	}
	
	/*
	 * rendering shortcode meat
	*/
	
	function renderRepo_open(){

		global $wpdb ;
		global $user_ID;
		get_currentuserinfo();

		
		if ( is_user_logged_in()) {
			ob_start();

			$uploader = $this -> plugin_path.'/includes/uploader-form-filebrowser.php';
			include($uploader);
			
			$list_path = $this -> plugin_path.'/includes/nm-file-browser.php';
			include ($list_path);
			
			$output_string = ob_get_contents();
			ob_end_clean();
			return $output_string;
		}
		else
		{

			/*wp_redirect( home_url() ); exit;*/
			echo '<script type="text/javascript">
			window.location = "'.wp_login_url( get_permalink() ).'"
			</script>';
		}

	}
	
	
	/*
	 * rendering shortcode meat
	*/
	
	/* function renderRepo(){
	
		global $wpdb ;
		global $user_ID;
		get_currentuserinfo();
	
		nmFileUploaderActions::$userRole = nmFileUploaderActions::getUserRole($user_ID);
	
	
		extract(shortcode_atts(array(
				'file_meta'		=> 0
		), $atts));
			
	
		$res = $wpdb->get_row("SELECT `metaID`, `fileMeta`, `generalSettings` FROM ".$wpdb->prefix . nmAdmin::$tblMeta."
				WHERE metaID = {$file_meta}");
	
				if($res){
					nmFileUploaderActions::$fileMetaID 			= $res -> metaID;
					nmFileUploaderActions::$uploaderSettings	= $res -> generalSettings;
					nmFileUploaderActions::$fileMeta 			= $res -> fileMeta;
				}
	
				if ( is_user_logged_in()) {
					ob_start();
	
					$settings = json_decode(nmFileUploaderActions::$uploaderSettings);
	
					if(@$settings -> allowupload == "true"){
						$uploader = dirname(__FILE__).'/uploader-form-filebrowser.php';
						include($uploader);
					}
	
					if(@$settings -> showfiles != "none"){
	
						$list_path = dirname(__FILE__).'/nm-file-browser.php';
						include ($list_path);
					}
	
	
					$output_string = ob_get_contents();
					ob_end_clean();
					return $output_string;
				}
				else
				{
	
					echo '<script type="text/javascript">
					window.location = "'.wp_login_url( get_permalink() ).'"
					</script>';
				}
	
	} */
	
	/*
	 * Language support
	 */
	
	function nmRepositoryLocale() {
		load_plugin_textdomain('nm_file_uploader_pro', false, dirname(plugin_basename( __FILE__ )) . '/locale/');
	}
	
	
	/*
	 * setting up user directory
	 */
	
	function setupUserDirectory(){
	
		$upload_dir = wp_upload_dir();
		
		$this -> rootPath = $upload_dir['basedir'].'/user_uploads/'.$this -> currentUser.'/';
		if(!is_dir($this -> rootPath))
		{
			if(mkdir($this -> rootPath, 0775, true))
				return true;
			else
				return false;
		}
		else
		{
			return true;
		}
	}
	
	
	/*
	 * activating the plugin
	 */
	
	function activatePlugin(){
		
		global $wpdb;
			
		$table_name = $wpdb->prefix . nmRepositoryAction::$tblName;
		
		$sql = "CREATE TABLE `$table_name`
		(`fileID` INT( 9 ) NOT NULL AUTO_INCREMENT PRIMARY KEY ,
		`userID` INT( 7 ) NOT NULL ,
		`userName` VARCHAR( 100 ) NOT NULL ,
		`fileName` VARCHAR( 250 ) NULL ,
		`fileDetail` MEDIUMTEXT NULL,
		`isShared` INT( 1 ) DEFAULT 0,
		`fileSize` INT(12) NULL ,
		`fileType` VARCHAR( 15 ) NULL ,
		`fileMeta` MEDIUMTEXT NULL,
		`fileParent` INT(12) NULL ,
		`fileUploadedOn` DATETIME NOT NULL )ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;";
		
		require_once(ABSPATH . 'wp-admin/includes/upgrade.php');
		dbDelta($sql);
		
		add_option("nm_repo_db_version", nmRepositoryAction::$nm_repo_db_version);
		
	}
	
	
	/*
	 * De-activating the plugin
	*/
	
	function deactivatePlugin(){
	
	}
	
	
}