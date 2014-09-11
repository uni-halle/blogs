<?php
load_theme_textdomain('philna',get_template_directory() . '/lang');
add_action('admin_menu', array('philnaOptions', 'add'));
/** widgets */
require_once("admin/widgets.php");
require_once("admin/settings.php");

/**myfunctions */
require_once("app/yinheli_include.php");

?>