<?php

namespace AGPressGraph;


/**
 * migrationManager class.
 * @since 6.0
 */
class migrationManager{
	public function migrateToVersion6(){
		$didMigrate = get_option("AGPressGraphMigrated", 0);
		if($didMigrate){
			$options = array(

				'appid'  => 'appid', //AppID
				'type'   => 'type', //Button Type
				'pos'    => 'pos', //Position
				'layout' => 'layout', //Layout
				'face'   => 'face', //Show Faces
				'verb'   => 'verb', //Verb to display
				'color'  => 'color', //Button Color
				'width'  => 'width', //Container Width
				'height' => 'height', //Container Height
				'ht'     => 'ht', //Height Type px or em
				'css'    => 'css', //Container CSS Class
				'home'   => 'home', //Show in home
				'page'   => 'page', //Show in pages
				'post'   => 'post',  //show in posts
				'cat'    => 'cat',   // show in cats
				'arch'   => 'arch', // show in archive
				'admeta' => 'admeta',
				'dimage' => 'dimage',
				'enimg'  => 'enimg',
				'align'  => 'align',
				'social'  => 'social',
				'add'    => 'add',
				"show_in" => "show_in",
				"admin_id" => "admin_id",
				"locale" => "locale",
				"font" => "font",
				"include_share" => "include_share",
				"kid_restricted" => "kid_restricted",
				"didUpdateOptions" => "didUpdateOptions"


			);

			foreach($options as $option){ //Get Options Names
				add_option("AGPressGraph_like_" . $option, get_option("fb_like_".$option, ""));
				delete_option("fb_like_".$option);
				register_setting( "AGPressGraph", "AGPressGraph_like_" . $option);
			}
			
			update_option("AGPressGraphMigrated", 1);
		}
	}
}

?>