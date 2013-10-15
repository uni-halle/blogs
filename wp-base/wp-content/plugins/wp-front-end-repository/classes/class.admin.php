<?php
/*
 * working behind the seen
 */

include_once($repo -> plugin_path . '/admin/admin-options.php');

class nmRepositoryAdmin extends nmRepositoryAction{
	
	function __construct(){
		
		add_action('admin_menu', array($this, 'hookMeInAdmin'));
	}
	
	
	/*
	 * creating menu page for this plugin
	 */
	
	function hookMeInAdmin(){
		
		global $repo;
		
		$page = add_menu_page($repo -> plugin_fullname,
				__("NMRepoManager", $repo -> plugin_shortname),
				'edit_plugins',
				$repo -> plugin_shortname,
				array($this, 'renderOptions'),
				$repo -> plugin_url.'/images/option.png');
		
		$page_stats = add_submenu_page( $repo -> plugin_shortname,
				'Files Stats',
				'Files Stats',
				'manage_options',
				'nm-repo-files',
				array($this, 'fileStats'));
		
		
		/*
		 * loading sytle only for plugin page
		 */
		add_action('admin_print_styles-'.$page, array($repo, 'loadStyleAdmin'));
		add_action('admin_print_styles-'.$page_stats, array($repo, 'loadStyleAdmin'));
	}
	
	/*
	 * rendering options
	 */
	
	function renderOptions(){
		
		global $repo_options, $repo;
		
		$settingsFile = $repo -> plugin_path.'/admin/settings.php';
		include ($settingsFile);
		
	}
	
	
	function fileStats(){
		global $repo;
		
		$file = $repo -> plugin_path.'/includes/admin/file-stats.php';
		include($file);
	}
}