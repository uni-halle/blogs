<?php 

/*************************************************************
* Do not modify unless you know what you're doing, SERIOUSLY!
*************************************************************/

/* Admin framework version 2.0 by Zeljan Topic */

// Theme variables
require_once (TEMPLATEPATH . '/library/functions/theme_variables.php');

//** ADMINISTRATION FILES **//

    // Theme admin functions
    require_once ($functions_path . 'admin_functions.php');

    // Theme admin options
    require_once ($functions_path . 'admin_options.php');

    // Theme admin Settings
    require_once ($functions_path . 'admin_settings.php');

   
//** FRONT-END FILES **//

    // Widgets
    require_once ($functions_path . 'widgets_functions.php');

    // Custom
    require_once ($functions_path . 'custom_functions.php');

    // Comments
    require_once ($functions_path . 'comments_functions.php');
	
	// Yoast's plugins
    require_once ($functions_path . 'yoast-breadcrumbs.php');
	
    require_once ($functions_path . 'yoast-posts.php');
	
	require_once ($functions_path . 'yoast-canonical.php');

?>
