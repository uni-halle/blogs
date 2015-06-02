<?php
if(!empty($_SERVER['SCRIPT_FILENAME']) && basename(__FILE__) == basename($_SERVER['SCRIPT_FILENAME']))
	die('You can not access this page directly!');

class Skype_Online_Status_Admin extends Skype_Online_Status {
	
	public static function meta_box_advanced($object, $data) {
		?>
		<fieldset class="options">
			<h4><?php _e('Post content', 'skype-online-status') ?></h4>
			<p><?php printf(__('When writing posts you can insert a Skype button with a simple quicktag %1$s or %2$s but to make life even easier, a small button on the WYSIWYG editor can do it for you. Check this option to show %3$s or uncheck to hide it. You may still insert the quicktag  in the HTML code of your post or page content manually.', 'skype-online-status'),"<strong>[skype-status]</strong>","<strong>&lt;!--skype status--&gt;</strong>","<img src=\"".plugins_url('/skype_button.gif', __FILE__)."\" alt=\"".__('Skype Legacy Buttons', 'skype-online-status')."\" style=\"vertical-align:text-bottom;\" />") ?><br /><br />
			<input type="checkbox" name="use_buttonsnap" id="use_buttonsnap"<?php if ( Skype_Online_Status::$config['use_buttonsnap'] == "on") { print " checked=\"checked\""; } ?> /> <label for="use_buttonsnap"><?php _e('Use <strong>Skype shortcode button</strong> in the RTE for posts.','skype-online-status') ?></label></p>
		</fieldset>

		<fieldset class="options">
			<h4><?php _e('Display &amp; Function', 'skype-online-status') ?></h4>
			<p><?php _e('These settings define which options should be used to replace their respective tag (if present) in the selected template file. If unchecked, the tags will be blanked out.','skype-online-status') ?></p> 
			<p><input type="checkbox" name="use_voicemail" id="use_voicemail"<?php if ( Skype_Online_Status::$config['use_voicemail'] == "on" ) { print " checked=\"checked\""; } ?> /> <label for="use_voicemail"><?php printf(__('Use %1$s in dropdown button. Leave unchecked if you do not have %2$s or %3$s.', 'skype-online-status'),"<strong>".__('Leave me voicemail', 'skype-online-status')."</strong>", '<a href="https://support.skype.com/en/category/ONLINE_NUMBER_SKYPEIN/" target="_top">'.__('SkypeIn','skype-online-status').'</a><img src="//www.ftjcfx.com/image-3049686-10520919" width="1" height="1" style="border:0" alt="" />', '<a href="https://support.skype.com/en/category/VOICEMAIL/" target="_top">'.__('Skype Voicemail','skype-online-status').'</a>
<img src="//www.ftjcfx.com/image-3049686-10423078" width="1" height="1" style="border:0" alt="" />') ?></label>

			<br /><input type="checkbox" name="use_function" id="use_function"<?php if ( Skype_Online_Status::$config['use_function'] == "on" ) { print " checked=\"checked\""; } ?> /> <label for="use_function"><?php printf(__('Use %1$s (define below) for %2$s tags.', 'skype-online-status'),"<strong>".__('Action text', 'skype-online-status')."</strong>","{add/call/chat/userinfo/voicemail/sendfile}") ?></label>

			<br /><input type="checkbox" name="local_images" id="local_images"<?php if ( Skype_Online_Status::$config['local_images'] == "on" ) { print " checked=\"checked\""; } ?> /> <label for="local_images"><?php _e('Use local images instead of the ones hosted on the Skype servers.', 'skype-online-status') ?></label>

<!--			<br /><input type="checkbox" name="no_scheme" id="no_scheme"<?php if ( Skype_Online_Status::$config['no_scheme'] == "on" ) { print " checked=\"checked\""; } ?> /> <label for="no_scheme"><?php _e('Use schemeless URLs / protocol relative paths for TLS compatibility.', 'skype-online-status') ?></label> -->


<!--		<fieldset class="options">
			<h4><?php //_e('Function', 'skype-online-status') ?></h4>
			<p><?php //_e('Some of the button themes will show your online status in an icon/image, without having a specific function assigned. This means you can select what the button should do when clicked by a visitor.', 'skype-online-status') ?></p>
			<p><label for="button_function"><?php //_e('Function', 'skype-online-status'); //_e(': ', 'skype-online-status') ?></label>
			<select name="button_function" id="button_function">
				<?php /*foreach (Skype_Online_Status::$avail_functions as $key => $value) {
				echo '<option value="'.$key.'"';
				if ( Skype_Online_Status::$config['button_function'] == $key ) echo ' selected="selected"';
				echo '>'.$value.'</option>
				'; } 
				unset($value)*/ ?> 
			</select> <a href="#function_info" class="info">?</a></p>
			<blockquote id="function_info" style="display:none"><em><?php //_e('Note:', 'skype-online-status'); echo " "; printf(__('This setting will only be used in the \'%1$s\' theme templates, or in your own custom template when the tags %2$s and %3$s are used.', 'skype-online-status'),"My Status","{function}","{functiontxt}");?></em></blockquote>
		</fieldset>
-->

<!--			<br /><label for="use_status"><strong><?php //_e('Status texts', 'skype-online-status') ?></strong> <?php //printf(__('for the %s tag', 'skype-online-status'),"{status}"); _e(': ', 'skype-online-status') ?></label><select name="use_status" id="use_status">
						<option value=""<?php //if ( empty(Skype_Online_Status::$config['use_status']) ) print " selected=\"selected\"" ?>><?php //_e('Disabled', 'skype-online-status') ?></option>
						<option value="custom"<?php //if ( !empty(Skype_Online_Status::$config['use_status']) && Skype_Online_Status::$config['use_status'] == "custom" ) print " selected=\"selected\"" ?>><?php //_e('Custom...', 'skype-online-status') ?></option>
						<?php /*foreach (Skype_Online_Status::$avail_languages as $key => $value) {
						echo '<option value="'.$key.'"';
						if ( Skype_Online_Status::$config['use_status'] == $key ) echo ' selected="selected"';
						echo '>Skype default text in '.$value.'</option>
						'; } 
						unset($value)*/ ?> 
					</select> <a href="#statustext_info" class="info">?</a><p>
			<blockquote id="statustext_info" style="display:none"><em><?php //printf(__('If you select %1$s, the tags %2$s, %3$s and %4$s in the button template will be ignored.', 'skype-online-status'),"<strong>".__('Disabled', 'skype-online-status')."</strong>","{status}","{statustxt}","{sep2}"); 
						//echo "<br />"; 
						//printf(__('When security settings on your server are too tight (<strong>safe_mode</strong> enabled, <strong>open_basedir</strong> restictions or <strong>allow_url_fopen</strong> being disabled), your status does not show or the Skype button doesn\'t load and you find an error like "Warning: file_get_contents() [function.file-get-contents]: URL file-access is disabled in the server configuration..." in the server log files, use either %1$s or %2$s here.','skype-online-status'),"<strong>".__('Custom...', 'skype-online-status')."</strong>","<strong>".__('Disabled', 'skype-online-status')."</strong>") ?></em></blockquote>
					<?php // if (defined('SOSREMOTE') && SOSREMOTE == false) {
						// echo "<p><span style=\"color:red\">" . __('Note:', 'skype-online-status') . " " . __('The security settings on your server are too tight for the online status to be read from the Skype server.', 'skype-online-status') . " " . sprintf(__('It is advised to set %1$s to %2$s here untill this is fixed.', 'skype-online-status'),"<strong>".__('Status texts', 'skype-online-status')."</strong>","<strong>".__('Disabled', 'skype-online-status')."</strong>") . sprintf(__('You can also select %1$s but only the %2$s status text will be available.', 'skype-online-status'),"<strong>".__('Custom...', 'skype-online-status')."</strong>","<strong>".__('Error', 'skype-online-status')."</strong>") . "</span></p>"; } ?>
-->

				<p><input type="checkbox" name="use_getskype" id="use_getskype"<?php if ( Skype_Online_Status::$config['use_getskype'] == "on") { print " checked=\"checked\""; } ?> /> <label for="use_getskype"><?php printf(__('Use %s link.', 'skype-online-status'),"<strong>".__('Download Skype now!','skype-online-status')."</strong>") ?></label>
					<br /><input type="checkbox" name="getskype_newline" id="getskype_newline"<?php if ( Skype_Online_Status::$config['getskype_newline'] == "on") { print " checked=\"checked\""; } ?> /> <label for="getskype_newline"><?php _e('Place link on a new line.', 'skype-online-status') ?></label><br />
					<label for="getskype_text"><?php _e('Link text', 'skype-online-status'); _e(': ', 'skype-online-status') ?></label><input type="text" name="getskype_text" style="width: 250px;" id="getskype_text" value="<?php echo stripslashes(htmlspecialchars(Skype_Online_Status::$config['getskype_text'])) ?>" /><br />
					<label for="getskype_link"><?php _e('Link URL', 'skype-online-status'); _e(': ', 'skype-online-status') ?></label>
						<select name="getskype_link" id="getskype_link">
							<option value=""<?php if ( Skype_Online_Status::$config['getskype_link'] == "" ) print " selected=\"selected\"" ?>><?php _e('Default affiliate link', 'skype-online-status') ?></option>
							<option value="skype_mainpage"<?php if ( Skype_Online_Status::$config['getskype_link'] == "skype_mainpage" ) print " selected=\"selected\"" ?>><?php _e('Skype main page', 'skype-online-status') ?></option>
							<option value="skype_downloadpage"<?php if ( Skype_Online_Status::$config['getskype_link'] == "skype_downloadpage" ) print " selected=\"selected\"" ?>><?php _e('Skype download page', 'skype-online-status') ?></option>
							<option value="custom_link"<?php if ( Skype_Online_Status::$config['getskype_link'] == "custom_link" ) print " selected=\"selected\"" ?>><?php _e('Custom...', 'skype-online-status') ?></option>
						</select> <a href="#linkurl_info" class="info">?</a> </p>
						<blockquote id="linkurl_info" style="display:none"><em><?php _e('Leave to Default if you are generous and think downloads should create some small possible revenue for the developer of this plugin. Or you could donate with the PayPal button on this page. -- Thanks! :)', 'skype-online-status');?> <?php printf(__('If you want to create your own link to get possible revenue from downloads yourself, select %1$s and paste the link code in the textarea under %2$s below.', 'skype-online-status'),"<strong>".__('Custom...', 'skype-online-status')."</strong>","<strong>".__('Advanced Options', 'skype-online-status')." / ".__('Custom Download Link', 'skype-online-status')."</strong>") ?></em></blockquote>
		</fieldset>

<!--		<fieldset class="options">
			<h4><?php //_e('Custom Status texts', 'skype-online-status') ?></h4>
			<p><?php //printf(__('Text that will replace the %s tag depending on actual online status.', 'skype-online-status'),"{status}"); 

/*	if (Skype_Online_Status::$config['use_status'] != "custom") { 
		echo " " . sprintf(__('Please, change %1$s to %2$s under %3$s (above) for these options to become available first.', 'skype-online-status'),"<strong>".__('Status texts', 'skype-online-status')."</strong>","<strong>".__('Custom...', 'skype-online-status')."</strong>","<strong>".__('Advanced Options', 'skype-online-status')." / ".__('Display', 'skype-online-status')."</strong>");
		$status_readonly = " readonly=\"readonly\""; 
		$status_style = "color:grey;"; 
	} else {
*/		$status_readonly = ""; 
		$status_style = ""; 
//	}
	?>
				<br /><?php //_e('Note:', 'skype-online-status') ?> <?php //_e('These custom texts will not repace the texts in the status images, just the alt-texts or text links. When using custom texts, the status images will default to the English versions.', 'skype-online-status'); ?></p>
				<table style="margin-left:5px;margin-top:10px">
					<tr>
						<th><?php //_e('Status', 'skype-online-status') ?></th>
						<th><?php //_e('Value', 'skype-online-status') ?></th>
						<th><?php //_e('Text', 'skype-online-status') ?></th>
					</tr>
				<?php /*
				foreach (Skype_Online_Status::$avail_statusmsg as $key => $value) {
				echo '
					<tr>
						<td><label for="status_'.$key.'_text">';
				echo $value.'</label></td>
						<td>('.$key.')</td>
						<td><input type="text" name="status_'.$key.'_text" id="status_'.$key.'_text" value="';
				echo stripslashes(htmlspecialchars(Skype_Online_Status::$config['status_'.$key.'_text']));
				echo '"'.$status_readonly.' style="'.$status_style.'" /></td>
					</tr>';
				} 
				unset($value);
				*/ ?> 
					<tr>
						<td><label for="status_error_text"><?php //_e('Error', 'skype-online-status') ?></label></td>
						<td><?php //_e('(none)', 'skype-online-status') ?></td>
						<td><input type="text" name="status_error_text" id="status_error_text" value="<?php //echo stripslashes(htmlspecialchars(Skype_Online_Status::$config['status_error_text'])) ?>" /></td>
					</tr>
				</table>
		</fieldset>
		-->
		<fieldset class="options">
			<h4><?php _e('Tag texts', 'skype-online-status') ?></h4>
			<p><?php _e('Define texts to replace their respective template tags relating to the Skype button action.', 'skype-online-status') ?></p> 
			<table style="margin-left:5px;margin-top:10px">
				<tr>
					<th><?php _e('Action', 'skype-online-status') ?></th>
					<th><?php _e('Tag', 'skype-online-status') ?></th>
					<th><?php _e('Text', 'skype-online-status') ?></th>
				</tr>
			<?php foreach (Skype_Online_Status::$avail_functions as $key => $value) {
			echo '
				<tr>
					<td><label for="'.$key.'_text">';
			echo $value.'</label></td>
					<td>{'.$key.'}</td>
					<td><input type="text" name="'.$key.'_text" id="'.$key.'_text" value="';
			echo stripslashes(htmlspecialchars(Skype_Online_Status::$config[$key.'_text']));
			echo '" /></td>
				</tr>';
			} 
			unset($value) ?> 
			</table>
	<?php 
/*	if (Skype_Online_Status::$config['use_status'] == "") { 
		echo "<p>" . sprintf(__('Please, change %1$s under %3$s (above) to any value other than %2$s for the following options to become available first.', 'skype-online-status'),"<strong>".__('Status texts', 'skype-online-status')."</strong>","<strong>".__('Disabled', 'skype-online-status')."</strong>","<strong>".__('Advanced Options', 'skype-online-status')." / ".__('Display', 'skype-online-status')."</strong>") . "</p>";
		$other_readonly = " readonly=\"readonly\"";
		$other_style = "color:grey;"; 
	} else {
*/		echo "				<br />"; 
		$other_readonly = "";
		$other_style = ""; 
//	}
	?>
				<table style="margin-left:5px;margin-top:10px">
					<tr>
						<th><?php _e('Other', 'skype-online-status') ?></th>
						<th><?php _e('Tag', 'skype-online-status') ?></th>
						<th><?php _e('Text', 'skype-online-status') ?></th>
					</tr>
					<tr>
						<td><label for="seperator1_text"><?php _e('Separator', 'skype-online-status') ?></label></td>
						<td>{sep1}</td>
						<td><input type="text" name="seperator1_text" id="seperator1_text" value="<?php echo stripslashes(htmlspecialchars(Skype_Online_Status::$config['seperator1_text'])) ?>"<?php echo $other_readonly ?> style="<?php echo $other_style ?>" /></td>
					</tr>
<!--					<tr>
						<td><label for="seperator2_text"><?php //_e('Second seperator', 'skype-online-status') ?></label></td>
						<td>{sep2}</td>
						<td><input type="text" name="seperator2_text" id="seperator2_text" value="<?php //echo stripslashes(htmlspecialchars(Skype_Online_Status::$config['seperator2_text'])) ?>"<?php //echo $other_readonly ?> style="<?php //echo $other_style ?>" /></td>
					</tr>
					<tr>
						<td><label for="my_status_text"><?php //_e('My status', 'skype-online-status') ?></label></td>
						<td>{statustxt}</td>
						<td><input type="text" name="my_status_text" id="my_status_text" value="<?php //echo stripslashes(htmlspecialchars(Skype_Online_Status::$config['my_status_text'])) ?>"<?php //echo $other_readonly ?> style="<?php //echo $other_style ?>" /></td>
					</tr>-->
				</table>
		</fieldset>

		<fieldset class="options">
			<h4><?php _e('Custom Template', 'skype-online-status') ?></h4>
			<p><?php printf(__('The currently selected template has been loaded into the database. You can edit it here if you like, but be sure to select %1$s under %2$s or your changes will be lost.', 'skype-online-status'),"<strong>".__('Custom...', 'skype-online-status')."</strong>","<strong>".__('Theme', 'skype-online-status')."</strong>") ?> <br /><br /><label for="button_template"><?php _e('Customize currently loaded template', 'skype-online-status'); _e(': ', 'skype-online-status') ?></label> <a href="#customtemplate_info" class="info">?</a><br />
			<textarea name="button_template" id="button_template" style="width:98%;height:240px;"><?php echo stripslashes(htmlspecialchars(Skype_Online_Status::$config['button_template'])) ?></textarea></p>
			<blockquote id="customtemplate_info" style="display:none"><em><?php _e('Available tags:','skype-online-status') ?> {skypeid} {username} {add} {call} {chat} {userinfo} {voicemail} {sendfile} {sep1}<br /><?php _e('Available markers:','skype-online-status') ?> &lt;!-- voicemail_start --&gt; &lt;!-- voicemail_end --&gt; <br /><?php printf(__('See %s for more instructions.','skype-online-status'),"<strong>".__('Quick Guide', 'skype-online-status')."</strong>") ?><br /><span style="color:red"><?php printf(__('Oh, and did I mention this? Changes to the template will only be loaded when the option %1$s under %2$s is selected!', 'skype-online-status'),"<strong>".__('Custom...', 'skype-online-status')."</strong>","<strong>".__('Theme', 'skype-online-status')."</strong>") ?></span></em></blockquote>
		</fieldset>

		<fieldset class="options">
			<h4><?php _e('Custom Download Link', 'skype-online-status') ?></h4>
			<p><?php printf(__('If you are a <a href="%1$s">Skype Affiliate</a> select %2$s at %3$s (above) and paste your link/banner code (HTML/Javascript) here.', 'skype-online-status'),"http://affiliates.skype.com/","<strong>".__('Custom...', 'skype-online-status')."</strong>","<strong>".__('Advanced Options', 'skype-online-status')." / ".__('Display &amp; Function', 'skype-online-status')."</strong>") ?><br /><br /><label for="getskype_custom_link"><strong><?php _e('Link/Banner Code', 'skype-online-status'); _e(': ', 'skype-online-status') ?></strong></label><br /><textarea name="getskype_custom_link" id="getskype_custom_link" style="width:98%;height:100px;"><?php echo stripslashes(htmlspecialchars(Skype_Online_Status::$config['getskype_custom_link'])) ?></textarea></p>
		</fieldset>

		<p class="submit">
			<input type="submit" name="skype_status_update" value="<?php _e('Save Changes') ?> &raquo;" />
		</p>

		<p style="clear:both;text-align:right;padding-top:10px"><a href="#wpwrap"><?php _e('Top') ?></a></p>
		<?php
	}
	
	public static function meta_box_discussion($object, $data) {
		?>
			<h4><?php _e('WordPress Support','skype-online-status'); _e(': ','skype-online-status') ?> <?php _e('Skype Legacy Buttons','skype-online-status') ?></h4>
			<div class='rss-widget'>
			<?php if(function_exists('wp_widget_rss_output')) {
				wp_widget_rss_output( "https://wordpress.org/support/rss/tags/skype-online-status/", array('show_date' => 1, 'items' => 5) );
				echo "<p style=\"text-align:right\"><a href=\"https://wordpress.org/tags/skype-online-status/#latest\">".__('More...')."</a></p>"; }
			else {
				echo "<p><a href=\"https://wordpress.org/support/rss/tags/skype-online-status/\">https://wordpress.org/support/rss/tags/skype-online-status/</a></p>"; } ?>
			</div>
		<?php	
	}
	
	public static function meta_box_submit($object, $data) {
		?>
		  <div class="submitbox" id="submitpost">
		    <div id="minor-publishing">
		      <div class="misc-pub-section">
			<ul><li>
			<a style="color:#d54e21" id="settingslink" href="#settings"><?php echo translate('Settings') ?></a>
			</li>
			<li>
			<a id="guidelink" href="#guide"><?php _e('Quick Guide', 'skype-online-status') ?></a>
			</li>
			<li>
			<a id="noteslink" href="#notes"><?php _e('Notes','skype-online-status') ?></a>
			</li></ul>
		      </div>

		      <div class="misc-pub-section misc-pub-section-last">
<!--			<p><strong><?php //_e('Current theme','skype-online-status') ?></strong><br /><br />

			<?php //if (Skype_Online_Status::$current_theme_fullname) echo Skype_Online_Status::$current_theme_fullname; else _e('Custom...','skype-online-status') ?></p>

			<?php //if (!Skype_Online_Status::$config['skype_id']) echo "<span class=\"error\">" . __('Skype button disabled:', 'skype-online-status') . " " . __('Missing Skype ID.', 'skype-online-status') . "</span>";
			//else echo Skype_Online_Status::skype_status();  ?>

			<br />
-->		      </div>
		    </div>
		    <div id="major-publishing-actions">
			<div id="publishing-action">
				<input type="submit" name="skype_status_update" class="button-primary" value="<?php _e('Save Changes') ?> &raquo;" />
			</div>
			<div id="delete-action">
				<input type="submit" class="submitdelete deletion" onclick='return confirm("<?php esc_attr_e('WARNING !', 'skype-online-status') ?> \r\n \r\n<?php esc_attr_e('All your personal settings will be overwritten with the plugin default settings, including Skype ID, User name and Theme.', 'skype-online-status') ?> \r\n \r\n<?php esc_attr_e('Are you sure?', 'skype-online-status') ?>");' name="skype_status_reset" value="<?php esc_attr_e('Reset','skype-online-status') ?> &raquo;" /> <br />
				<input type="submit" class="submitdelete deletion" onclick='return confirm("<?php _e('WARNING !', 'skype-online-status') ?>  \r\n \r\n<?php esc_attr_e('All your plugin AND widget settings will be cleared from the database so the plugin can be COMPLETELY removed. All Skype buttons on your blog will be deactivated.', 'skype-online-status') ?> \r\n \r\n<?php esc_attr_e('Are you sure?', 'skype-online-status') ?>");' name="skype_status_remove" value="<?php esc_attr_e('Remove') ?> &raquo;" />
			</div>
			<div class="clear"></div>

		    </div>
		  </div>
		<?php
	}
	
	public static function meta_box_preview($object, $data) {
		?>
			<div class="alternate no_underline" style="margin:5px 0 0 0;padding:5px;">
				<div class="preview-wrapper" id="custom_edit" style="display:<?php if ( Skype_Online_Status::$config['button_theme'] == 'custom_edit' ) echo 'block'; else echo 'none'; ?>;margin:0;padding:0">
					<div style="height:38px;border-bottom:1px dotted grey;margin:0 0 5px 0"><?php _e('Custom...', 'skype-online-status'); _e(' (edit under advanced options)', 'skype-online-status'); ?></div>
					<div class="custom_edit_preview"><?php echo Skype_Online_Status::parse_theme(Skype_Online_Status::$preview_options); ?></div>
				</div>
				<?php echo Skype_Online_Status::$previews ?>
			</div>
			<p><?php _e('Note:', 'skype-online-status') ?> <?php _e('The preview button uses the Skype Test Call service to allow testing its function.','skype-online-status') ?></p>
		<?php
	}

	public static function meta_box_support($object, $data) {
		?>
			<p><?php _e('For all support questions and suggestions, please go to','skype-online-status') ?> <a href="https://wordpress.org/tags/skype-online-status/"><?php _e('WordPress Support','skype-online-status') ?> - <?php _e('Skype Legacy Buttons','skype-online-status') ?></a>.</p>
			<p><?php printf(__('For <strong>feature requests</strong> or general help with <strong>WordPress</strong> or <strong>hosting</strong>, please contact <em>RavanH</em> via e-mail %s or Skype chat:','skype-online-status'),'<a href="mailto:ravanhagen@gmail.com">ravanhagen@gmail.com</a>') ?></p>

			<br />
			<iframe frameborder="0" scrolling="no" allowtransparency="yes" style="margin:0;padding:0;border:1px solid #ddd;width:100%;height:440px;background-color:#f9f9f9" src="//status301.net/skype-online-status/ads/?ad=big&amp;ref=<?php echo rawurlencode( $_SERVER['HTTP_HOST'] ); ?>" id="ad_big"></iframe>
		<?php
	}

	public static function meta_box_donations($object, $data) {
		?>
			<h4><?php _e('Translations','skype-online-status') ?></h4>
			<p><?php _e('Translation contributions are highly appreciated. Authors of new translations or updates will be mentioned here.','skype-online-status') ?></p>

			<iframe frameborder="0" scrolling="auto" allowtransparency="yes" style="margin:0;padding:0;border:1px solid #ddd;width:100%;height:330px;background-color:#f9f9f9" src="//status301.net/skype-online-status/translators/?ref=<?php echo rawurlencode( $_SERVER['HTTP_HOST'] ); ?>"></iframe>

			<p><?php _e('Want to make your own translation too? Read the <a href="http://svn.wp-plugins.org/skype-online-status/trunk/languages/language-support.txt">translation instructions</a> included with this plugin to get started.','skype-online-status') ?></p>

			<h4><?php _e('Donations','skype-online-status') ?></h4>
			<p><?php _e('All donations are much appreciated and will (without objection) be mentioned here as a way of expressing my gratitude.','skype-online-status') ?></p>

			<iframe frameborder="0" scrolling="auto" allowtransparency="yes" style="margin:0;padding:0;border:1px solid #ddd;width:100%;height:220px;background-color:#f9f9f9" src="//status301.net/skype-online-status/donors/?ref=<?php echo rawurlencode( $_SERVER['HTTP_HOST'] ); ?>"></iframe>

			<p><?php _e('Please <strong>rate this plugin</strong> at <a href="https://wordpress.org/plugins/skype-online-status/">WordPress</a>','skype-online-status') ?></p>
			<p><?php _e('Do you want your name and/or link up there too? Or just appreciate my work?','skype-online-status') ?><br /><br />
			<a href="https://www.paypal.com/cgi-bin/webscr?cmd=_donations&amp;business=ravanhagen%40gmail%2ecom&amp;item_name=Skype%20Online%20Status&amp;item_number=<?php echo SOSVERSION ?>&amp;no_shipping=0&amp;tax=0&amp;bn=PP%2dDonationsBF&amp;charset=UTF%2d8&amp;lc=us" title="<?php _e('Donate with PayPal - it\'s fast, free and secure!','skype-online-status') ?>"><img src="https://www.paypalobjects.com/en_US/i/btn/btn_donate_LG.gif" style="border:none; vertical-align:text-bottom;" alt="<?php _e('Donate with PayPal - it\'s fast, free and secure!','skype-online-status') ?>"/></a></p>
			<p><?php _e('Thanks!','skype-online-status') ?><br /><em>RavanH</em></p>
		<?php
	}


	public static function meta_box_more($object, $data) {
		?>
			<iframe frameborder="0" scrolling="no" allowtransparency="yes" style="margin:0;padding:0;border:none;width:100%;height:700px" src="//status301.net/skype-online-status/ads/?ad=xxl&amp;ref=<?php echo rawurlencode( $_SERVER['HTTP_HOST'] ); ?>"></iframe>
		<?php
	}


	public static function meta_box_resources($object, $data) {
		?>
			<ul>
				<li><a target="_blank" href="http://www.skype.com/en/features/skype-buttons/create-skype-buttons/"><?php _e('Skype Buttons','skype-online-status') ?></a></li>
<!--				<li><a target="_blank" class="thickbox thickbox-preview" href="http://mystatus.skype.com/<?php echo Skype_Online_Status::$config['skype_id'] ?>?TB_iframe=true&amp;width=130&amp;=60"><?php // printf(__('View %s\'s online status on the Skype server','skype-online-status'),Skype_Online_Status::$config['skype_id']) ?></a></li> -->
				<li><a target="_blank" class="thickbox thickbox-preview" href="http://c.skype.com/i/legacy/images/share/buttons/privacy_shot.jpg"><?php _e('Edit Privacy Options in your Skype client','skype-online-status') ?></a></li>
				<li><a target="_blank" href="https://support.skype.com/category/CALLING_PHONES_SKYPEOUT/" title="<?php _e('SkypeOut','skype-online-status') ?>"><?php _e('SkypeOut','skype-online-status'); _e(': ','skype-online-status'); _e('Call any phone directly from Skype.','skype-online-status') ?></a></li>
				<li><a target="_blank" href="https://support.skype.com/category/ONLINE_NUMBER_SKYPEIN/" title="<?php _e('SkypeIn','skype-online-status') ?>"><?php _e('SkypeIn','skype-online-status'); _e(': ','skype-online-status'); _e('Your personal online number.','skype-online-status') ?></a></li>
				<li><a target="_blank" href="https://support.skype.com/category/VOICEMAIL/" title="<?php _e('Skype Voicemail','skype-online-status') ?>"><?php _e('Skype Voicemail','skype-online-status'); _e(': ','skype-online-status'); _e('Never miss a call!','skype-online-status');?></a></li>
				<li><a target="_blank" href="https://support.skype.com/category/GROUP_VIDEO_CALLING/" title="<?php _e('Group Video calling','skype-online-status') ?>"><?php _e('Group Video calling','skype-online-status'); ?></a></li>
			</ul>
		<?php
	}


}


/***
 *
 * ADMIN PAGE TEMPLATE
 *
 */

?>
<br />
<div id="skype_status_options-metaboxes-general" class="wrap">
	<div id="icon-edit-comments" class="icon32"><br /></div>
<h2><?php echo __('Skype Legacy Buttons', 'skype-online-status')." ".SOSVERSION ?></h2>

<?php
	// check if database has been cleared for removal or else updated after plugin upgrade 
	if ( !empty($_POST['skype_status_remove']) ) { // hit remove button
		define('SOSREMOVEFLAG', TRUE);
		delete_option('skype_status');
		delete_option('widget_skype-status');
		echo '<div class="error fade"><p><strong>'.__('Your Skype Legacy Buttons database settings have been cleared from the database for removal of this plugin!', 'skype-online-status').'</strong><br />'.__('You can still resave the old settings shown below to (partly) undo this action but custom widget settings will be reverted to default.', 'skype-online-status').'<br /><br />'.__('Are you sure?', 'skype-online-status');
		if (function_exists('wp_nonce_url')) 
			echo ' <a href="' . wp_nonce_url('plugins.php?action=deactivate&amp;plugin='.SOSPLUGINBASENAME, 'deactivate-plugin_'.SOSPLUGINBASENAME) . '" title="' . __('Deactivate this plugin') . '" class="delete">' . __('Deactivate') . '</a></p></div>';
		else
			echo ' ' . __('Go to the <a href="plugins.php">Plugins page</a> and deactivate it.', 'skype-online-status') . '</p></div>';
		Skype_Online_Status::$config = '';
	} elseif ( !empty(Skype_Online_Status::$config['upgraded']) ) {
		Skype_Online_Status::$config['upgraded'] = FALSE;
		update_option('skype_status',Skype_Online_Status::$config);
		echo "<div class=\"updated fade\"><p><strong>".__('Skype Legacy Buttons plugin has been upgraded to version ', 'skype-online-status').SOSVERSION."</strong><br />".__('Please, verify your settings now.', 'skype-online-status')."</p><p><strong>".__('What\'s new?', 'skype-online-status')."</strong></p>".Skype_Online_Status::$whats_new."</div>";
	} elseif ( !empty(Skype_Online_Status::$config['installed']) ) {
		Skype_Online_Status::$config['installed'] = FALSE;
		update_option('skype_status',Skype_Online_Status::$config);
		echo "<div class=\"updated fade\"><p><strong>";
		printf(__('Skype Legacy Buttons plugin version %s has been installed!','skype-online-status'),SOSVERSION);
		echo "</strong> ".__('Please, adapt the default settings to your personal preference so you can start using Skype buttons anywhere on your site.','skype-online-status')." ";
		printf(__('Read the %s section for more instructions.','skype-online-status'),"<strong>".__('Quick Guide','skype-online-status')."</strong>");
		echo "</p></div>";
	}

	// check for new version
	do_action('load-plugins.php');
	$current = get_option('update_plugins');
	if ( isset( $current->response[SOSPLUGINBASENAME] ) ) {
		$r = $current->response[SOSPLUGINBASENAME];
		echo "<div class=\"updated fade-ff0000\"><p><strong>";
		if ( !current_user_can('edit_plugins') )
			printf( __('There is a new version of %1$s available. <a href="%2$s">Download version %3$s here</a>.'), __('Skype Legacy Buttons', 'skype-online-status'), $r->url, $r->new_version);
		else if ( empty($r->package) )
			printf( __('There is a new version of %1$s available. <a href="%2$s">Download version %3$s here</a> <em>automatic upgrade unavailable for this plugin</em>.'), __('Skype Legacy Buttons', 'skype-online-status'), $r->url, $r->new_version);
		else
			printf( __('There is a new version of %1$s available. <a href="%2$s">Download version %3$s here</a> or <a href="%4$s">upgrade automatically</a>.'), __('Skype Legacy Buttons', 'skype-online-status'), $r->url, $r->new_version, wp_nonce_url('update.php?action=upgrade-plugin&amp;plugin='.SOSPLUGINBASENAME, 'upgrade-plugin_'.SOSPLUGINBASENAME) );
		echo "</strong></p></div>";
	}

	// warning about inability to check remote status file 
/*	if (defined('SOSREMOTE') && SOSREMOTE == false) {  
		echo "<div class=\"error fade\"><p>".__('The security settings on your server are too tight for the online status to be read from the Skype server.', 'skype-online-status').__('Please, check if one of the following options can be met -or- ask your server admin / hosting provider to take care of this.', 'skype-online-status')."</p><ul><li>".__('Install and activate cURL libraries on the server (preferred)', 'skype-online-status')."</li><li>".__('Upgrade PHP to a version that includes fsockopen (PHP 4+)', 'skype-online-status')."</li><li>".__('Change INI setting <strong>allow_url_fopen</strong> to ON (not advised)', 'skype-online-status')."</li></ul><p>".__('Reading your online status from the remote Skype server has been disabled. As a result, some of the advanced options will not be available. However, Skype button themes that also show your online status by way of images (that are provided by the same Skype server) will still do so.')."</p></div>";
	}*/ 
?>

<!--	<div id="loading" class="error fade"><p><strong><?php //_e('Please, wait while page has loaded completely.<br /> When the Skype server at http://mystatus.skype.com/ is slow or down, this might take a while...', 'skype-online-status') ?></strong></p></div>
-->
<?php	// update the options if form is saved
	if (!empty($_POST['skype_status_update'])) { // pressed udate button

			if ($_POST['button_theme']!="custom_edit") // get template file content to load into db
				$_POST['button_template'] = Skype_Online_Status::get_template_file($_POST['button_theme']);

			$skype_id_array = array('');
			if (isset($_POST['skype_id'])) { 
				$skype_id_array = explode("@",str_replace("live:","",$_POST['skype_id']));
			}

			$option = array(
				"skype_id" => $skype_id_array[0],
				"user_name" => isset($_POST['user_name']) ? $_POST['user_name'] : '',
				"button_theme" => isset($_POST['button_theme']) ? $_POST['button_theme'] : '',
				"button_template" => isset($_POST['button_template']) ? stripslashes($_POST['button_template']) : '',
				"use_function" => isset($_POST['use_function']) ? $_POST['use_function'] : '',
				"use_voicemail" => isset($_POST['use_voicemail']) ? $_POST['use_voicemail'] : '',
				"use_buttonsnap" => isset($_POST['use_buttonsnap']) ? $_POST['use_buttonsnap'] : '',
				"local_images" => isset($_POST['local_images']) ? $_POST['local_images'] : '',
				"no_scheme" => isset($_POST['no_scheme']) ? $_POST['no_scheme'] : '',
				"add_text" => isset($_POST['add_text']) ? $_POST['add_text'] : '',
				"call_text" => isset($_POST['call_text']) ? $_POST['call_text'] : '',
				"chat_text" => isset($_POST['chat_text']) ? $_POST['chat_text'] : '',
				"sendfile_text" => isset($_POST['sendfile_text']) ? $_POST['sendfile_text'] : '',
				"userinfo_text" => isset($_POST['userinfo_text']) ? $_POST['userinfo_text'] : '',
				"voicemail_text" => isset($_POST['voicemail_text']) ? $_POST['voicemail_text'] : '',
				"seperator1_text" => isset($_POST['seperator1_text']) ? $_POST['seperator1_text'] : '',
/*				"status_error_text" => isset($_POST['status_error_text']) ? $_POST['status_error_text'] : '',
				"status_0_text" => isset($_POST['status_0_text']) ? $_POST['status_0_text'] : '',
				"status_1_text" => isset($_POST['status_1_text']) ? $_POST['status_1_text'] : '',
				"status_2_text" => isset($_POST['status_2_text']) ? $_POST['status_2_text'] : '',
				"status_3_text" => isset($_POST['status_3_text']) ? $_POST['status_3_text'] : '',
				"status_4_text" => isset($_POST['status_4_text']) ? $_POST['status_4_text'] : '',
				"status_5_text" => isset($_POST['status_5_text']) ? $_POST['status_5_text'] : '',
				"status_6_text" => isset($_POST['status_6_text']) ? $_POST['status_6_text'] : '',
				"status_7_text" => isset($_POST['status_7_text']) ? $_POST['status_7_text'] : '',
				"my_status_text" => isset($_POST['my_status_text']) ? $_POST['my_status_text'] : '',
				"button_function" => isset($_POST['button_function']) ? $_POST['button_function'] : '',
				"use_status" => isset($_POST['use_status']) ? $_POST['use_status'] : '',
				"seperator2_text" => isset($_POST['seperator2_text']) ? $_POST['seperator2_text'] : '',
*/				"use_getskype" => isset($_POST['use_getskype']) ? $_POST['use_getskype'] : '',
				"getskype_newline" => isset($_POST['getskype_newline']) ? $_POST['getskype_newline'] : '',
				"getskype_text" => isset($_POST['getskype_text']) ? $_POST['getskype_text'] : '',
				"getskype_link" => isset($_POST['getskype_link']) ? $_POST['getskype_link'] : '',
				"getskype_custom_link" => isset($_POST['getskype_custom_link']) ? $_POST['getskype_custom_link'] : ''
			);

			$option = array_merge (Skype_Online_Status::$config, $option);

			// strip nostatus flag after settings have been updated
			if ( isset($option['nostatus']) ) unset($option['nostatus']);

			update_option('skype_status',$option);
			echo "<div id=\"notice\" class=\"updated fade\"><p><strong>".__('Options updated!', 'skype-online-status')."</strong></p></div>";
			Skype_Online_Status::$config = $option;

	} else if (!empty($_POST['skype_status_reset'])) { // pressed reset button
			Skype_Online_Status::$config = Skype_Online_Status::get_default_values();
			update_option('skype_status',Skype_Online_Status::$config);
			echo "<div id=\"notice\" class=\"updated fade\"><p><strong>".__('Options reset!', 'skype-online-status')."</strong></p></div>";
	} else {
		$option = Skype_Online_Status::$config;
	}

	// get all the selected options (except test call id) and their previews into an array
	Skype_Online_Status::$preview_options = wp_parse_args( array('skype_id' => 'echo123','user_name' => __('Skype Test Call', 'skype-online-status')), $option );
	Skype_Online_Status::$walk = Skype_Online_Status::walk_templates('', Skype_Online_Status::$preview_options, "", "", true);

	// build output
	foreach (Skype_Online_Status::$walk['previews'] as $key => $value) { 
		Skype_Online_Status::$previews .= "<div class=\"preview-wrapper\" id=\"$value[0]\" style=\"display:"; 
		if ($value[0] == $option['button_theme']) {
			Skype_Online_Status::$previews .= "block"; 
			Skype_Online_Status::$current_theme_fullname = $key; 
		} else { 
			Skype_Online_Status::$previews .= "none";
		}
		Skype_Online_Status::$previews .= "\"><div style=\"height:38px;border-bottom:1px dotted grey;margin:0 0 5px 0\">$key</div>$value[1]</div>"; 
	} 
	unset($value);
	?>

<form enctype="multipart/form-data" method="post" action="#" id="online-status">

<?php wp_nonce_field('skype_status_options-metaboxes-general'); ?>
<?php wp_nonce_field('closedpostboxes','closedpostboxesnonce',false) ?>
<?php wp_nonce_field('meta-box-order','meta-box-order-nonce',false) ?>
<input type="hidden" name="action" value="save_skype_status_options_metaboxes_general" />

<div id="poststuff">

  <div id="post-body" class="metabox-holder<?php echo ( empty($screen_layout_columns) || 2 == $screen_layout_columns ) ? ' columns-2' : ''; ?>">

    <div id="post-body-content">

      <div id="settings">

	  <iframe frameborder="0" scrolling="no" allowtransparency="yes" style="float:right;margin:0 0 0 5px;padding:0;border:0;width:450px;height:220px;background-color:transparent" src="//status301.net/skype-online-status/ads/?ad=top&amp;ref=<?php echo rawurlencode( $_SERVER['HTTP_HOST'] ); ?>" id="ad_big"></iframe>

	  <p style="text-align:justify"><?php _e('Define all your <em>default</em> Skype settings here.', 'skype-online-status') ?> 
		<?php printf(__('Start simply by setting the basics like %1$s, %2$s and the button %3$s you want to show on your blog.', 'skype-online-status'),"<strong>".__('Skype ID', 'skype-online-status')."</strong>","<strong>".__('Full Name', 'skype-online-status')."</strong>","<strong>".__('Theme', 'skype-online-status')."</strong>") ?> 
		<?php printf(__('Then activate a Skype Button widget on your <a href="widgets.php">Widgets</a> page or use the Skype shortcode button %s in the WYSIWYG editor (TinyMCE) to place a Skype legacy button in any post or page.', 'skype-online-status'),'<img src="'.plugins_url('/skype_button.gif', __FILE__).'" alt="'.__('Skype Legacy Buttons', 'skype-online-status').'" style="vertical-align:text-bottom;" />') ?> 
		<?php _e('Later on, you can fine-tune everything until it fits just perfectly on you pages.', 'skype-online-status') ?><br />
		<?php _e('Note:', 'skype-online-status') ?> <?php _e('Some basic settings may be overridden per Widget settings or when calling the Skype button with a template function.', 'skype-online-status') ?></p>
	  <p style="text-align:justify"><?php printf(__('Read more about configuring this plugin and more ways to trigger Skype buttons on your blog in the %1$s section. If you have any remaining questions, see the %2$s page to get help.', 'skype-online-status'),"<strong>".__('Quick Guide', 'skype-online-status')."</strong>","<strong>".__('Notes', 'skype-online-status')."</strong>") ?></p>

      </div> <!-- settings -->

<?php include(SOSPLUGINDIR . '/skype-quickguide-notes.php') ?>

<div id="titlediv">

		<fieldset class="options">
			<p><label for="skype_id"><strong><?php _e('Skype ID', 'skype-online-status'); _e(': ', 'skype-online-status') ?></strong></label><input type="text" name="skype_id" id="skype_id" value="<?php echo Skype_Online_Status::$config['skype_id'] ?>" /> <a href="#skypeid_info" class="info">?</a></p>
			<blockquote id="skypeid_info" style="display:none"><em><?php printf(__('Simply enter your Skype ID. Or... If you want the button to invoke a Skype multichat or conference call, enter more then one Skype ID seperated with a semi-colon (<strong>;</strong>). You may also enter a regular phone number (starting with a <strong>+</strong> followed by country code; note that callers need to have %s to call). It just all depends on what you want to achieve!','skype-online-status'),'<a href="https://support.skype.com/en/category/CALLING_PHONES_SKYPEOUT/" title="SkypeOut">'.__('SkypeOut','skype-online-status').'</a>') ?> <?php _e('Note:', 'skype-online-status') ?></em></blockquote>

			<p><label for="user_name"><strong><?php _e('Full Name', 'skype-online-status'); _e(': ', 'skype-online-status') ?></strong></label><input type="text" style="width: 250px;" name="user_name" id="user_name" value="<?php echo stripslashes(htmlspecialchars(Skype_Online_Status::$config['user_name'])) ?>" /> <a href="#username_info" class="info">?</a></p>
			<blockquote id="username_info" style="display:none"><em><?php _e('Your full name as you want it to appear in Skype links, link-titles and image alt-texts on your blog.', 'skype-online-status') ?></em></blockquote>

			<p><label for="button_theme"><strong><?php echo __('Theme', 'skype-online-status') . __(': ', 'skype-online-status') ?></strong></label> <select name="button_theme" id="button_theme"><option value="custom_edit"<?php if (Skype_Online_Status::$config['button_theme'] == "custom_edit") echo " selected=\"selected\"" ?> ><?php _e('Custom...', 'skype-online-status') ?>&nbsp;</option><?php foreach (Skype_Online_Status::$walk['select'] as $key => $value) { echo "<option value=\"$value\""; if ($value == Skype_Online_Status::$config['button_theme']) { echo " selected=\"selected\""; } echo ">$key&nbsp;</option>"; } unset($value) ?> </select> <a href="#theme_info" class="info">?</a></p>
			<blockquote id="theme_info" style="display:none"><em><?php printf(__('When %1$s is selected, you can edit the template to your liking below at %2$s under %3$s. When you make changes to that field but select another theme template here, those changes will be overwriten by the new template!', 'skype-online-status'),"<strong>".__('Custom...', 'skype-online-status')."</strong>","<strong>".__('Customize currently loaded template', 'skype-online-status')."</strong>","<strong>".__('Advanced Options', 'skype-online-status')." / ".__('Custom Template', 'skype-online-status')."</strong>") ?></em></blockquote>
			<p><?php printf(__('Start with <strong>selecting one of the predefined theme templates</strong> to load into the database. Change the options to see a preview. You might later select %1$s to edit the template in the text field under %2$s.', 'skype-online-status'),"<strong>".__('Custom...', 'skype-online-status')."</strong>","<strong>".__('Advanced Options', 'skype-online-status')." / ".__('Custom Template', 'skype-online-status')."</strong>") ?></p>
			<p><?php printf(__('If you cannot find a suitable theme, check out <a href="http://www.skype.com/en/features/skype-buttons/" target="_blank">http://www.skype.com/en/features/skype-buttons/</a>. Select your options there and copy/paste the output into the textarea under %s.', 'skype-online-status'),"<strong>".__('Advanced Options', 'skype-online-status')." / ".__('Display &amp; Function', 'skype-online-status')."</strong>") ?></p>
			<p><?php printf(__('If you have your basic settings correct and there is a Skype button visible on blog, you can fine-tune it\'s appearance and function with the advanced settings. Each option is annotated but you can read more in the %s section.', 'skype-online-status'),"<strong>".__('Quick Guide', 'skype-online-status')."</strong>") ?></p>
		</fieldset>

</div>
 
    </div> <!-- post-body-content has-sidebar-content -->

    <div id="postbox-container-1" class="postbox-container">

	  <?php do_meta_boxes(Skype_Online_Status::$pagehook, 'side', null); ?>

    </div> <!-- side-info-column inner-sidebar -->

    <div id="postbox-container-2" class="postbox-container">

  	  <?php do_meta_boxes(Skype_Online_Status::$pagehook, 'normal', null); ?>
 
    </div>


  </div> <!--post-body has-sidebar -->

</div> <!-- poststuff metabox-holder has-right-sidebar -->
</form>
</div> <!-- wrap -->

<script type="text/javascript">
//<![CDATA[
jQuery(document).ready( function($) {
	// close postboxes that should be closed
	$('.if-js-closed').removeClass('if-js-closed').addClass('closed');
	// postboxes setup
	postboxes.add_postbox_toggles('<?php echo Skype_Online_Status::$pagehook; ?>');
});
//]]>
</script>
