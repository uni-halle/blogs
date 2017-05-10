<?php
if (!current_user_can('manage_options')) {
  die('Access Denied');
}
function html_show_theme_calendar_widget() {  ?>
  <?php upgrade_pro_sp("themes"); ?>
  <br /><br />
  <img src="<?php echo plugins_url('images/spider_calendar_widget_themes.png', __FILE__); ?>" />
  <?php
}

?>