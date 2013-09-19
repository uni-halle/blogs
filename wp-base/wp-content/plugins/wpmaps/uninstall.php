<?php
if (defined('WP_UNINSTALL_PLUGIN')) {
	delete_option('wpmaps');
	$GLOBALS['wpdb']->query("OPTIMIZE TABLE `" .$GLOBALS['wpdb']->options. "`");
}
?>