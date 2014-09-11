<?php
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
			    		"type" => "text"),
				
				array(	"name" => "About Settings",
						"type" => "heading"),
						
				array(	"name" => "About Description",
						"desc" => "Enter About Description for sidebar.<br /><br />",
			    		"id" => SHORTNAME."_about_description",
			    		"std" => "",
			    		"type" => "textarea"),
																														
		  );
	
	/* option*/	
	$option_name = SHORTNAME.'_about_logo';
	$option_value = obwp_get_meta($option_name.'_url');
	if(!empty($option_value))
	{
		$option_img = '<a href="'.$option_value.'"><img src="'.$option_value.'" alt="" width="200" /></a>';
	}
	else
	{
		$option_img ='';
	}
	$options[] = array(	
					"id" => $option_name,
					"name" => "About Logo",
					"html" => $option_img.'<br /><input type="hidden" name="'.$option_name.'_url" id="'.$option_name.'_url" value="'.$option_value.'" /><input type="file" name="'.$option_name.'" id="'.$option_name.'" /><br /><br />',
					"type" => "html");
	
	
	/* option*/
	$option_name = SHORTNAME.'_featured_cat_id';
	$options[] = array(	
				"id" => $option_name,
				"name" => "Fetured category",
				"html" => obwp_admin_dropdown_categories($option_name).'<br /><br />',
				"type" => "html");
	
	obwp_add_admin('obwp-settings.php');
}



?>