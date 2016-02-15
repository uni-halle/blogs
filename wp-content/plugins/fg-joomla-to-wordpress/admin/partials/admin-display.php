<?php

/**
 * Provide an admin area view for the plugin
 *
 * This file is used to markup the admin-facing aspects of the plugin.
 *
 * @link       https://wordpress.org/plugins/fg-joomla-to-wordpress/
 * @since      2.0.0
 *
 * @package    FG_Joomla_to_WordPress
 * @subpackage FG_Joomla_to_WordPress/admin/partials
 */
?>
<div id="fgj2wp_admin_page" class="wrap">
	<h2><?php print $data['title'] ?></h2>
	
	<p><?php print $data['description'] ?></p>
	
	<div id="fgj2wp_settings">
		<?php require('database-info.php'); ?>
		<?php require('empty-content.php'); ?>
		
		
		<form id="form_import" method="post">

			<?php wp_nonce_field( 'parameters_form', 'fgj2wp_nonce' ); ?>

			<table class="form-table">
				<?php require('settings.php'); ?>
				<?php do_action('fgj2wp_post_display_settings_options'); ?>
				<?php require('behavior.php'); ?>
				<?php require('actions.php'); ?>
				<?php require('progress-bar.php'); ?>
				<?php require('logger.php'); ?>
			</table>
		</form>
		
		<?php require('after-migration.php'); ?>
	</div>
	
	<?php require('extra-features.php'); ?>
</div>
