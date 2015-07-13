<?php

namespace AGPressGraph;

class coreManager{
	public $coreDirPath;
	public function __construct(){
		$coreDirPath = plugin_dir_path(__FILE__);

		include_once($coreDirPath . "AGLB.thebuttons.php");
		include_once($coreDirPath . "AGLB.admin.php");
		include_once($coreDirPath . "AGLB.migrationmanager.php");
		include_once($coreDirPath . "AGLB.postmeta.php");
		include_once($coreDirPath . "AGLB.manipulator.php");
		
		//Exec Init Actions
		coreManager::initActions();
	}
	
	public function initActions(){
		
		add_action("admin_menu", array("AGPressGraph\adminManager", "registerAdminMenu"));
		add_action("admin_init", array("AGPressGraph\migrationManager", "migrateToVersion6"));
		add_action("admin_init", array("AGPressGraph\adminManager", "likeSettingsFieldsAndSections"));
		add_action("admin_init", array("AGPressGraph\postMeta", "postMetaBox"));
		add_action( 'save_post', array("AGPressGraph\postMeta", "savePostCustomMeta"),10, 3);
		add_action( 'admin_enqueue_scripts', array("AGPressGraph\adminManager", "AGPressGraph_like_admin_scripts") );
		add_action("wp_head", array("AGPressGraph\manipulator", "openGraphData"));
		
		add_filter( 'the_content', array("AGPressGraph\manipulator", "insertTheButtonForContent"));
		add_filter( 'language_attributes', array("AGPressGraph\manipulator", "addOpenGraphHTMLAttributes") );
		
		add_action("init", array("AGPressGraph\TheButtons", "addButtonsShortcodes"));
		
		if(!get_option("AGPressGraph_like_didUpdateOptions", false)){
			add_action("admin_notices", array("AGPressGraph\adminManager","upgradeNotice"));
		}
	}
		
}
	
?>