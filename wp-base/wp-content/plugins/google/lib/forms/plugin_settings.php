<div class="wrap">
	<h2><?php _e('Google+ settings', 'wdgpo');?></h2>

<?php if (WP_NETWORK_ADMIN) { ?>
	<form action="settings.php" method="post">
<?php } else { ?>
	<form action="options.php" method="post">
<?php } ?>

	<?php settings_fields('wdgpo'); ?>
	<?php do_settings_sections('wdgpo_options_page'); ?>
	<p class="submit">
		<input name="Submit" type="submit" class="button-primary" value="<?php esc_attr_e('Save Changes'); ?>" />
	</p>
	</form>

<?php _e('<h2>Shortcode</h2> <p>In addition to (or instead of) the auto-inserted Google +1 buttons, you may want to use the shortcode to embed the button in your posts.</p> <dl> <dt>Tag: <code>wdgpo_plusone</code></dt> <dd>Embeds Google +1 button in your post</dd> <dd> Arguments:  <ul> <li> <code>appearance</code> (<em>optional</em>) - Accepts one of these values: <code>small</code>, <code>medium</code>, <code>standard</code>, <code>tall</code>. Default values are set on plugin settings page.  </li> <li> <code>show_count</code> (<em>optional</em>) - Accepts <code>yes</code> or <code>no</code> as values. Default values are set on plugin settings page.  </li> </ul> </dd> <dd> Examples:  <ul> <li> <code>[wdgpo_plusone]</code> - Embeds Google +1 button in your post, with defaults set on plugin settings page.  </li> <li> <code>[wdgpo_plusone appearance="tall"]</code> - Embeds Google +1 <em>tall</em> button in your post, with other options taken from plugin settings.  </li> <li> <code>[wdgpo_plusone show_count="no"]</code> - Embeds Google +1 button without count in your post, with other options taken from plugin settings.  </li> </ul> </dd> <dt>Tag: <code>wdgpo_gplus_page</code></dt> <dd>Embeds your Google+ page widget</dd> <dt>Tag: <code>wdgpo_activities</code></dt> <dd>Embeds your imported Google+ activities</dd> <dd> Arguments: <ul> <li><code>id</code> - a single Google+ ID or a list of comma-separated Google+ IDs to show activities for.</li> <li><code>show_faces</code> - show author images for activities. Accepts <code>yes</code>.</li> <li><code>template</code> - use a custom template to show your activities. You will need to have a file named <code>wdgpo-activities-TEMPLATE.php</code> (where TEMPLATE is the value of this argument) in your theme directory.</li> <li><code>orderby</code> - determines how to order the activities. Valid values are <code>date</code>, <code>title</code> and <code>rand</code>.</li> <li><code>order</code> - determines ordering direction. Valid values are <code>ASC</code> and <code>DESC</code>.</li> <li><code>limit</code> - only show this many activities.</li> <li><code>buffering</code> - By default, the shortcode will push your other content to the bottom. Use this argument if you wish to specify the placement within your content. Accepts <code>yes</code>. <em>Note: enabling this can cause problems on certain configurations.</em></li> </ul> </dd> </dl> <h2>Styling</h2> <p>If you need some extra styling done (e.g. floating the button), the button is wrapped in a <code>DIV</code> with class <code>wdgpo</code>.</p> <p>Based on the rendered button appearance and count, additional classes will be set:</p> <ul> <li><code>wdgpo_small_count</code></li> <li><code>wdgpo_small_nocount</code></li> <li><code>wdgpo_medium_count</code></li> <li><code>wdgpo_medium_nocount</code></li> <li><code>wdgpo_standard_count</code></li> <li><code>wdgpo_standard_nocount</code></li> <li><code>wdgpo_tall_count</code></li> <li><code>wdgpo_tall_nocount</code></li> </ul>', 'wdgpo'); ?>

</div>