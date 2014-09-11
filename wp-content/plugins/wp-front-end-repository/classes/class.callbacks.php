<?php
/*
 * processing ajax callbacks
* front end
* admin side
*/

class nmRepositoryCallbacks extends nmRepositoryAction{

	var $obj = '';	//the main object for plugin
	
	function __construct(){
		
		$this -> obj = new nmRepository();

		
		/*
		 * uploading file
		 */
		add_action( 'wp_ajax_repo_uploadFile', array($this, 'uploadFile') );
		add_action( 'wp_ajax_nopriv_repo_uploadFile', array($this, 'uploadFile') );
		
		/*
		 * ajax action callback to create directory
		* defined in js/script.js
		*/
		add_action( 'wp_ajax_repo_createDirectory', array($this, 'createDirectory') );
		add_action( 'wp_ajax_nopriv_repo_createDirectory', array($this, 'createDirectory') );
		
		
		/*
		 * ajax action callback to save file
		* defined in js/scrtip.js
		*/
		add_action( 'wp_ajax_repo_saveFile', array($this, 'saveFile') );
		add_action( 'wp_ajax_nopriv_repo_saveFile', array($this,'saveFile') );
		
		
		/*
		 * ajax action callback to delete file
		* defined in js/scrtip.js
		*/
		add_action( 'wp_ajax_repo_deleteFile', array($this, 'deleteFile') );
		add_action( 'wp_ajax_nopriv_repo_deleteFile', array($this, 'deleteFile') );
		
		/*
		 * ajax action callback to delete Dir
		* defined in js/scrtip.js
		*/
		add_action( 'wp_ajax_repo_deleteDir', array($this, 'deleteDir') );
		add_action( 'wp_ajax_nopriv_repo_deleteDir', array($this, 'deleteDir') );
		
		
		
		/* ============================== ADMIN BLOCK ================================== */
		
		add_action( 'wp_ajax_nopriv_saveRepoSettings', array($this, 'saveRepoSettings'));
		add_action( 'wp_ajax_saveRepoSettings', array($this, 'saveRepoSettings'));
		
		
		/* ============================== ADMIN BLOCK ================================== */
		
	}

	
	function uploadFile(){

		//print_r($_REQUEST);
		
		$this -> obj -> uploadFile($_REQUEST['username'], intval($_REQUEST['parentID']));
		//nmFileUploader::uploadFile($_REQUEST['username']);
	
		die(0);
	}
	
	
	
	function createDirectory(){
	
		print_r($_REQUEST);
	
		if($this -> obj -> createDirectory(sanitize_text_field($_REQUEST['directory-name']), sanitize_text_field($_REQUEST['parent-directory'])))		//US: Ultimate Security
			echo 'Directory created successfully';
	
		die(0);
	}
	
	
	function saveFile(){
	
		/* print_r($_REQUEST); exit; */
	
		$filename 	= sanitize_text_field($_REQUEST['filename']);
		$filedetail = sanitize_text_field($_REQUEST['filedetail']);
	
		$this -> obj -> saveFile($filename, $filedetail, intval($_REQUEST['parentID']));		
	
		die(0);
	}
	
	
	function deleteFile(){
	
		if($this -> obj -> deleteFile(intval($_REQUEST['fid']), intval($_REQUEST['parentID'])))
			_e('Deleted successfully', $this->plugin_shortname);
	
		die(0);
	}
	
	function deleteDir(){
	
		if($this -> obj -> deleteDir(intval($_REQUEST['fid']), intval($_REQUEST['parentID'])))
			_e('Deleted successfully', $this->plugin_shortname);
	
		die(0);
	}

	
	
	/* ============================== ADMIN BLOCK ================================== */
	
	function saveRepoSettings(){
	
		//pa($_REQUEST);
		$existingOptions = get_option('nm_repo_settings');
		//pa($existingOptions);
		
		update_option('nm_repo_settings', $_REQUEST);
		_e('All options are updated', $this -> plugin_shortname);
		die(0);
	}
	
	
	/* ============================== ADMIN BLOCK ================================== */
	
	
}
