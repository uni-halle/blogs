<?php
// block direct access to plugin PHP files:
defined( 'ABSPATH' ) or die();
?>

<button data-opinionstage-content-launch class="button">
<img src="<?php echo plugins_url('admin/images/content-popup.png', plugin_dir_path( __FILE__ )) ?>"
		width="24"
		height="19"
		style="position: relative; left: -3px; top: -2px; padding: 0"
>
	Add a Poll, Survey, Quiz, Form or Slider
</button>
