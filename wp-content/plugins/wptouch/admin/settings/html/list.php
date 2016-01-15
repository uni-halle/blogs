<?php wptouch_admin_the_setting_desc(); ?>

<?php if ( wptouch_admin_setting_has_tooltip() ) { ?>
	<i class="wptouch-tooltip" title="<?php wptouch_admin_the_setting_tooltip(); ?>"></i>
<?php } ?>

<?php if ( wptouch_admin_is_setting_new() ) { ?>
	<span class="new">&nbsp;<?php _e( 'New', 'wptouch-pro' ); ?></span>
<?php } ?>

<select name="<?php wptouch_admin_the_encoded_setting_name(); ?>" id="<?php wptouch_admin_the_setting_name(); ?>" class="list">
	<?php while ( wptouch_admin_has_list_options() ) { ?>
		<?php wptouch_admin_the_list_option(); ?>
		<option value="<?php wptouch_admin_the_list_option_key(); ?>"<?php if ( wptouch_admin_is_list_option_selected() ) echo " selected"; ?>>
			<?php wptouch_admin_the_list_option_desc(); ?>
		</option>
	<?php } ?>
</select>