<?
/**
 * @package WordPress
 * @subpackage magazine_obsession
 */

/**
 * Show the General Settings for Admin oanel
 *
 * @since 2.7.0
 *
 */
function obwp_general_settings()
{
    global $themename, $options;

	$options = array (
				array(	"name" => "General Settings",
						"type" => "heading"),
						
				array(	"name" => "Twitter ID",
						"desc" => "Enter twitter id.<br /><br />",
			    		"id" => SHORTNAME."_twitter_id",
			    		"std" => "",
			    		"type" => "text")
																														
		  );
	
	obwp_add_admin('obwp-settings.php');
}



?>