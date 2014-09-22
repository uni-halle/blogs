<?php

add_action('admin_init', 'update_primary_shareaholic_plugin_file', 1);

function update_primary_shareaholic_plugin_file(){
  if (is_plugin_active('sexybookmarks/sexy-bookmarks.php')) {
    deactivate_plugins('sexybookmarks/sexy-bookmarks.php');
    activate_plugins('sexybookmarks/shareaholic.php');
  }
}

?>