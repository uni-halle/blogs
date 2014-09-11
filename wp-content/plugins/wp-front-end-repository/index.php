<?php 
/*
 Plugin Name: N-Media Repository Manager
Plugin URI: http://www.najeebmedia.com
Description: This Plugin is developed by NajeebMedia.com
Version: 1.1
Author: Najeeb Ahmad
Author URI: http://www.najeebmedia.com/
*/

/* ini_set('display_errors',1);
error_reporting(E_ALL); */

include('functions.php');

include('classes/class.repository.php');

include('classes/class.actions.php');

$repo = new nmRepositoryAction();

include('classes/class.admin.php');
$admin = new nmRepositoryAdmin();


include('classes/class.callbacks.php');
$callbacks = new nmRepositoryCallbacks();


/*
 * activation/install the plugin data
*/
register_activation_hook( __FILE__, array('nmRepositoryAction', 'activatePlugin'));
register_deactivation_hook( __FILE__, array('nmRepositoryAction', 'deactivatePlugin'));

?>