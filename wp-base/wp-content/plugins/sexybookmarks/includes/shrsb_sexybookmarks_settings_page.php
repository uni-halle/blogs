<?php

//write settings page
function shrsb_sb_settings_page() {
	global $shrsb_plugopts, $shrsb_bookmarks_data, $wpdb, $shrsb_custom_sprite,$shrsb_analytics;
    // Add all the global varaible declarations for the $shrsb_plugopts default options e.g.,

	echo '<div class="wrap""><div class="icon32" id="icon-options-general"><br></div><h2>Share Buttons: SexyBookmarks Settings</h2></div>';

    //Defaults - set if not present
    if (!isset($_POST['reset_all_options_sb'])){$_POST['reset_all_options_sb'] = '1';}
    if (!isset($_POST['shrsbresetallwarn-choice'])){$_POST['shrsbresetallwarn-choice'] = 'no';}
    if (!isset($_POST['custom-mods'])  || $shrsb_plugopts['custom-mods'] == ""){$_POST['custom-mods'] = 'no';}

	if(($_POST['reset_all_options_sb'] == '0') && check_admin_referer('reset-settings','shareaholic_nonce')) {
		echo '
		<div id="shrsbresetallwarn" class="dialog-box-warning" style="float:none;width:97%;">
			<div class="dialog-left fugue f-warn">
				'.__("WARNING: You are about to reset all settings to their default state! Do you wish to continue?", "shrsb").'
			</div>
			<div class="dialog-right">
				<form action="" method="post" id="resetalloptionsaccept">'. wp_nonce_field('reset-settings','shareaholic_nonce') .'
					<label><input name="shrsbresetallwarn-choice" id="shrsbresetallwarn-yes" type="radio" value="yes" />'.__('Yes', 'shrsb').'</label> &nbsp; <label><input name="shrsbresetallwarn-choice" id="shrsbresetallwarn-cancel" type="radio" value="cancel" />'.__('Cancel', 'shrsb').'</label>
				</form>
			</div>
		</div>';
	}

	//Reset all options to default settings if user clicks the reset button
	if($_POST['shrsbresetallwarn-choice'] == "yes") { //check for reset button click
		
        // Resting the settings
		$shrsb_plugopts = shrsb_sb_set_options('reset');

        //$shrsb_plugopts['tweetconfig'] = urlencode($shrsb_plugopts['tweetconfig']);
        
        if($shrsb_plugopts['preventminify'] == '1') {
            exclude_from_minify_list();
        }

        /* Short URLs */
        $shrsb_plugopts['shortyapi']['bitly']['user'] = "";
        $shrsb_plugopts['shortyapi']['bitly']['key'] = "";
        $shrsb_plugopts['shortyapi']['awesm']['user'] = "";
        $shrsb_plugopts['shortyapi']['awesm']['key'] = "";
        $shrsb_plugopts['shortyapi']['jmp']['user'] = "";
        $shrsb_plugopts['shortyapi']['jmp']['key'] = "";
        $shrsb_plugopts['shortyapi']['supr']['chk'] = "0";
        $shrsb_plugopts['shortyapi']['supr']['user'] = "";
        $shrsb_plugopts['shortyapi']['supr']['key'] = "";
        /* Short URLs End */
		
		update_option('SexyBookmarks', $shrsb_plugopts);
    $shrsb_plugopts['tweetconfig'] = urldecode($shrsb_plugopts['tweetconfig']);
		delete_option('SHRSB_CustomSprite');
		
		echo '
		<div id="statmessage" class="shrsb-success">
			<div class="dialog-left fugue f-success">
				'.__('All settings have been reset to their default values.', 'shrsb').'
			</div>
			<div class="dialog-right">
				<img src="'.SHRSB_PLUGPATH.'images/success-delete.jpg" class="del-x" alt=""/>
			</div>
		</div>';
	}

	// create folders for custom mods
	// then copy original files into new folders
	if($_POST['custom-mods'] == 'yes' || $shrsb_plugopts['custom-mods'] == 'yes') {
		if(is_admin() === true && !is_dir(WP_CONTENT_DIR.'/sexy-mods')) {
			$shrsb_oldloc = SHRSB_PLUGDIR;
			$shrsb_newloc = WP_CONTENT_DIR.'/sexy-mods/';

			wp_mkdir_p(WP_CONTENT_DIR.'/sexy-mods');
			wp_mkdir_p(WP_CONTENT_DIR.'/sexy-mods/css');
			wp_mkdir_p(WP_CONTENT_DIR.'/sexy-mods/images');
			wp_mkdir_p(WP_CONTENT_DIR.'/sexy-mods/js');

			copy($shrsb_oldloc.'css/style.dev.css', $shrsb_newloc.'css/style.css');
			copy($shrsb_oldloc.'js/sexy-bookmarks-public.js', $shrsb_newloc.'js/sexy-bookmarks-public.js');
			copy($shrsb_oldloc.'images/shr-sprite.png', $shrsb_newloc.'images/shr-sprite.png');
			copy($shrsb_oldloc.'images/share-enjoy.png', $shrsb_newloc.'images/share-enjoy.png');
			copy($shrsb_oldloc.'images/share-german.png', $shrsb_newloc.'images/share-german.png');
			copy($shrsb_oldloc.'images/share-love-hearts.png', $shrsb_newloc.'images/share-love-hearts.png');
			copy($shrsb_oldloc.'images/share-wealth.png', $shrsb_newloc.'images/share-wealth.png');
			copy($shrsb_oldloc.'images/sharing-caring-hearts.png', $shrsb_newloc.'images/sharing-caring-hearts.png');
			copy($shrsb_oldloc.'images/sharing-caring.png', $shrsb_newloc.'images/sharing-caring.png');
			copy($shrsb_oldloc.'images/sharing-shr.png', $shrsb_newloc.'images/sharing-shr.png');
		}
	}

	// processing form submission
	$status_message = "";
	$error_message = "";
	$setting_changed = false;
	
	if(isset($_POST['save_changes_sb']) && check_admin_referer('save-settings','shareaholic_nonce')) {

    if(isset($_POST['bookmark']['shr-fleck'])) {
      unset($_POST['bookmark']['shr-fleck']);
    }
        $_POST['pageorpost'] = shrsb_set_content_type();
		// Set success message
		$status_message = __('Your changes have been saved successfully!', 'shrsb');

		$errmsgmap = array(
			'position'=>__('Please choose where you would like the menu to be displayed.', 'shrsb'),
			'bookmark'=>__("You can't display the menu if you don't choose a few sites to add to it!", 'shrsb'),
			'pageorpost'=>__('Please choose where you want the menu displayed.', 'shrsb'),
		);
		foreach ($errmsgmap as $field=>$msg) {
			if ($_POST[$field] == '') {
				$error_message = $msg;
				break;
			}
		}
		// Twitter friendly Links & YOURLs Plugins: check to see if they have the plugin activated
		if ($_POST['shorty'] == 'tflp' && !function_exists('permalink_to_twitter_link')) {
			$error_message = sprintf(__('You must first download and activate the %sTwitter Friendly Links Plugin%s before hosting your own short URLs...', 'shrsb'), '<a href="http://wordpress.org/extend/plugins/twitter-friendly-links/">', '</a>');
		} elseif ($_POST['shorty'] == 'yourls' && !function_exists('wp_ozh_yourls_raw_url')) {
			$error_message = sprintf(__('You must first download and activate the %sYOURLS Plugin%s before hosting your own short URLs...', 'shrsb'), '<a href="http://wordpress.org/extend/plugins/yourls-wordpress-to-twitter/">', '</a>');
		}
          
          if ( isset($_POST['bookmark']) && is_array($_POST['bookmark']) && sizeof($_POST['bookmark']) > 0 && $shrsb_plugopts['shareaholic-javascript'] == '1') {
            $service_ids = array();
            foreach ( $_POST['bookmark'] as $bm ) {
              if ($this_id = $shrsb_bookmarks_data[$bm]['id']) {
                $service_ids[] = $this_id;
              }
            }
            $shrsb_plugopts['service'] = implode(',', $service_ids);
            shrsb_refresh_cache();
            _shrsb_copy_file(SHRSB_UPLOADDIR.'index.html', SHRSB_PLUGDIR.'spritegen_default/index.html');
            _shrsb_copy_file(SHRSB_UPLOADDIR.'spritegen/index.html', SHRSB_PLUGDIR.'spritegen_default/index.html');
          }

		if (!$error_message) {
			//generate a new sprite, to reduce the size of the image
			if(shrsb_preFlight_Checks()) {
				if ( isset($_POST['bookmark']) && is_array($_POST['bookmark']) and sizeof($_POST['bookmark']) > 0 ) {
					$spritegen_opts = '&service=';
					foreach ( $_POST['bookmark'] as $bm ) {
						$spritegen_opts .= substr($bm, 4) . ',';
					}
					$spritegen_opts = substr($spritegen_opts,0,-1);
					$spritegen_opts .= '&bgimg=' . $_POST['bgimg'] . '&expand=' . $_POST['expand'];
                    $save_return[0] = get_sprite_file($spritegen_opts, 'png');
                    $save_return[1] = get_sprite_file($spritegen_opts, 'css');
				}
                if($save_return[0] == 2 || $save_return[1] == 2) {
					echo '<div id="warnmessage" class="shrsb-warning"><div class="dialog-left fugue f-warn">'.__('WARNING: The request for a custom sprite has timed out. Reverting to default sprite files.', 'shrsb').'</div><div class="dialog-right"><img src="'.SHRSB_PLUGPATH.'images/warning-delete.jpg" class="del-x" alt=""/></div></div><div style="clear:both;"></div>';
					$shrsb_custom_sprite = '';
					$status_message = __('Changes saved successfully. However, you should try to generate a custom sprite again later.', 'shrsb');
				}
				elseif($save_return[0] == 1 || $save_return[1] == 1) {
					if (!is_writable(SHRSB_UPLOADDIR.'spritegen')) {
						echo '<div id="warnmessage" class="shrsb-warning"><div class="dialog-left fugue f-warn">'.sprintf(__('WARNING: Your %sspritegen folder%s is not writeable by the server! %sNeed Help?%s', 'shrsb'), '<a href="'.SHRSB_UPLOADPATH.'spritegen" target="_blank">','</a>','<a href="https://shareaholic.com/tools/wordpress/usage-installation#chmodinfo" target="_blank">', '</a>').'</div><div class="dialog-right"><img src="'.SHRSB_PLUGPATH.'images/warning-delete.jpg" class="del-x" alt=""/></div></div><div style="clear:both;"></div>';
						$shrsb_custom_sprite = '';
						$status_message = __('Changes saved successfully. However, settings are not optimal until you resolve the issue listed above.', 'shrsb');
					}
					elseif(file_exists(SHRSB_UPLOADDIR.'spritegen/shr-custom-sprite.png') && is_writable(SHRSB_UPLOADDIR.'spritegen') && !is_writable(SHRSB_UPLOADDIR.'spritegen/shr-custom-sprite.png')) {
						echo '<div id="warnmessage" class="shrsb-warning"><div class="dialog-left fugue f-warn">'.sprintf(__('WARNING: You need to delete the current custom sprite %s before the plugin can write to the folder. %sNeed Help?%s', 'shrsb'), '(<a href="'.SHRSB_UPLOADDIR.'spritegen/shr-custom-sprite.png" target="_blank">'.SHRSB_UPLOADDIR.'spritegen/shr-custom-sprite.png</a>)','<a href="https://shareaholic.com/tools/wordpress/usage-installation#chmodinfo" target="_blank">', '</a>').'</div><div class="dialog-right"><img src="'.SHRSB_PLUGPATH.'images/warning-delete.jpg" class="del-x" alt=""/></div></div><div style="clear:both;"></div>';
						$shrsb_custom_sprite = '';
						$status_message = __('Changes saved successfully. However, settings are not optimal until you resolve the issue listed above.', 'shrsb');
					}
					elseif(file_exists(SHRSB_UPLOADDIR.'spritegen/shr-custom-sprite.css') && is_writable(SHRSB_UPLOADDIR.'spritegen') && !is_writable(SHRSB_UPLOADDIR.'spritegen/shr-custom-sprite.css')) {
						echo '<div id="warnmessage" class="shrsb-warning"><div class="dialog-left fugue f-warn">'.sprintf(__('WARNING: You need to delete the current custom stylesheet %s before the plugin can write to the folder. %sNeed Help?%s', 'shrsb'), '(<a href="'.SHRSB_UPLOADDIR.'spritegen/shr-custom-sprite.css" target="_blank">'.SHRSB_UPLOADDIR.'spritegen/shr-custom-sprite.css</a>)','<a href="https://shareaholic.com/tools/wordpress/usage-installation#chmodinfo" target="_blank">', '</a>').'</div><div class="dialog-right"><img src="'.SHRSB_PLUGPATH.'images/warning-delete.jpg" class="del-x" alt=""/></div></div><div style="clear:both;"></div>';
						$shrsb_custom_sprite = '';
						$status_message = __('Changes saved successfully. However, settings are not optimal until you resolve the issue listed above.', 'shrsb');
					}
				}
				else {
					$shrsb_custom_sprite = SHRSB_UPLOADPATH.'spritegen/shr-custom-sprite.css';
				}
			}
			else{
                if (!is_writable(SHRSB_UPLOADDIR.'spritegen')) {
                    echo '<div id="warnmessage" class="shrsb-warning"><div class="dialog-left fugue f-warn">'.sprintf(__('WARNING: Your %sspritegen folder%s is not writeable by the server! %sNeed Help?%s', 'shrsb'), '<a href="'.SHRSB_UPLOADPATH.'spritegen" target="_blank">','</a>','<a href="https://shareaholic.com/tools/wordpress/usage-installation#chmodinfo" target="_blank">', '</a>').'</div><div class="dialog-right"><img src="'.SHRSB_PLUGPATH.'images/warning-delete.jpg" class="del-x" alt=""/></div></div><div style="clear:both;"></div>';
                    $status_message = __('Changes saved successfully. However, settings are not optimal until you resolve the issue listed above.', 'shrsb');
                }
                elseif(file_exists(SHRSB_UPLOADDIR.'spritegen/shr-custom-sprite.png') && is_writable(SHRSB_UPLOADDIR.'spritegen') && !is_writable(SHRSB_UPLOADDIR.'spritegen/shr-custom-sprite.png')) {
                    echo '<div id="warnmessage" class="shrsb-warning"><div class="dialog-left fugue f-warn">'.sprintf(__('WARNING: You need to delete the current custom sprite %s before the plugin can write to the folder. %sNeed Help?%s', 'shrsb'), '(<a href="'.SHRSB_UPLOADDIR.'spritegen/shr-custom-sprite.png" target="_blank">'.SHRSB_UPLOADDIR.'spritegen/shr-custom-sprite.png</a>)','<a href="https://shareaholic.com/tools/wordpress/usage-installation#chmodinfo" target="_blank">', '</a>').'</div><div class="dialog-right"><img src="'.SHRSB_PLUGPATH.'images/warning-delete.jpg" class="del-x" alt=""/></div></div><div style="clear:both;"></div>';
                    $status_message = __('Changes saved successfully. However, settings are not optimal until you resolve the issue listed above.', 'shrsb');
                }
                elseif(file_exists(SHRSB_UPLOADDIR.'spritegen/shr-custom-sprite.css') && is_writable(SHRSB_UPLOADDIR.'spritegen') && !is_writable(SHRSB_UPLOADDIR.'spritegen/shr-custom-sprite.css')) {
                    echo '<div id="warnmessage" class="shrsb-warning"><div class="dialog-left fugue f-warn">'.sprintf(__('WARNING: You need to delete the current custom stylesheet %s before the plugin can write to the folder. %sNeed Help?%s', 'shrsb'), '(<a href="'.SHRSB_UPLOADDIR.'spritegen/shr-custom-sprite.css" target="_blank">'.SHRSB_UPLOADDIR.'spritegen/shr-custom-sprite.css</a>)','<a href="https://shareaholic.com/tools/wordpress/usage-installation#chmodinfo" target="_blank">', '</a>').'</div><div class="dialog-right"><img src="'.SHRSB_PLUGPATH.'images/warning-delete.jpg" class="del-x" alt=""/></div></div><div style="clear:both;"></div>';
                    $status_message = __('Changes saved successfully. However, settings are not optimal until you resolve the issue listed above.', 'shrsb');
                }
            }

        foreach (array(
            
                'sexybookmark',
         
                'position', 'reloption', 'targetopt', 'bookmark',
                'shorty', 'pageorpost', 'tweetconfig', 'bgimg-yes', 'mobile-hide', 'bgimg',
                'feed', 'expand', 'doNotIncludeJQuery', 'autocenter', 'custom-mods',
                'scriptInFooter', 'shareaholic-javascript', 'shrbase', 'showShareCount',
                'likeButtonSetTop','fbLikeButtonTop','fbSendButtonTop','googlePlusOneButtonTop','tweetButtonTop','likeButtonSetSizeTop','likeButtonSetCountTop',
                'likeButtonOrderTop','likeButtonSetAlignmentTop',
                'likeButtonSetBottom','fbLikeButtonBottom','fbSendButtonBottom','googlePlusOneButtonBottom','tweetButtonBottom','likeButtonSetSizeBottom','likeButtonSetCountBottom',
                'likeButtonOrderBottom','likeButtonSetAlignmentBottom','locale',

                'fbNameSpace','designer_toolTips' , 'tip_bg_color',
                'tip_text_color' , 'preventminify', 'shrlink', 'perfoption','spritegen_path', 'apikey','ogtags' , 'promo'
            )as $field) {
                if(isset($_POST[$field])) { // this is to prevent warning if $_POST[$field] is not defined
					
					$fieldval = $_POST[$field];
					
					if ($field == 'sexybookmark' && $fieldval != $shrsb_plugopts[$field]) {
					  $setting_changed = true;
					}
		            $shrsb_plugopts[$field] = $fieldval;
                } else {
                    $shrsb_plugopts[$field] = NULL;
                }
        }
  
          /*
          *   @note WordPress autoescapes (= adds slashes) to all post data. This is a workaround for that.
          */

          $shrsb_plugopts['tweetconfig'] = stripslashes($shrsb_plugopts['tweetconfig']);
          $shrsb_plugopts['spritegen_path'] = shrb_addTrailingChar(stripslashes($shrsb_plugopts['spritegen_path']),'/');

          /* Short URLs */
          //trim also at the same time as at times while copying, some whitespace also gets copied
          //check fields dont need trim function

          $shrsb_plugopts['shortyapi']['bitly']['user'] = trim(htmlspecialchars($_POST['shortyapiuser-bitly'], ENT_QUOTES));
          $shrsb_plugopts['shortyapi']['bitly']['key'] = trim(htmlspecialchars($_POST['shortyapikey-bitly'], ENT_QUOTES));
          $shrsb_plugopts['shortyapi']['jmp']['user'] = trim(htmlspecialchars($_POST['shortyapiuser-jmp'], ENT_QUOTES));
          $shrsb_plugopts['shortyapi']['jmp']['key'] = trim(htmlspecialchars($_POST['shortyapikey-jmp'], ENT_QUOTES));

          /* Short URLs End */

          $shrsb_plugopts['tweetconfig'] = urlencode($shrsb_plugopts['tweetconfig']);
          if($shrsb_plugopts['preventminify'] == '1') {
                exclude_from_minify_list();
          }
          
          $shrsb_plugopts['firstrun'] = '0';
          update_option('SexyBookmarks', $shrsb_plugopts);
          
          if ($setting_changed == true){
            shr_sendTrackingEvent('FeatureToggle', array('f_updated' => 'f_sexy', 'enabled' => ($shrsb_plugopts['sexybookmark'] == '1' ? 'true' : 'false')));
          }
          
          $shrsb_plugopts['tweetconfig'] = urldecode($shrsb_plugopts['tweetconfig']);

          update_option('SHRSB_CustomSprite', $shrsb_custom_sprite);
          update_option('SHRSBvNum', SHRSB_vNum);
      }
  }//Closed Save

	//if there was an error, construct error messages
	if ($error_message != '') {
		echo '
		<div id="errmessage" class="shrsb-error">
			<div class="dialog-left fugue f-error">
				'.$error_message.'
			</div>
			<div class="dialog-right">
				<img src="'.SHRSB_PLUGPATH.'images/error-delete.jpg" class="del-x" alt=""/>
			</div>
		</div>';
	} elseif ($status_message != '') {
		echo '<style type="text/css">#update_sb{display:none !important;}</style>
		<div id="statmessage" class="shrsb-success">
			<div class="dialog-left fugue f-success">
				'.$status_message.'
			</div>
			<div class="dialog-right">
				<img src="'.SHRSB_PLUGPATH.'images/success-delete.jpg" class="del-x" alt=""/>
			</div>
		</div>';
	}
    
    $parse = parse_url(get_bloginfo('url'));
?>

<form name="sexy-bookmarks" id="sexy-bookmarks" action="" method="post">
	<div id="shrsb-col-left">
		<ul id="shrsb-sortables">
            
            <!--
            <?php
                $resave_required = shrsb_requires_resave();
                $chmod_required = shrsb_requires_chmod($shrsb_plugopts['shareaholic-javascript']);
                $phpupdate_required = shrsb_requires_phpupdate();
            ?>
            -->
            
            <li id="third-party-modal" class="">
			<a name="3rdpartyservices"></a>
				<div class="box-mid-head">
					<h2 class="fugue f-wrench"><?php _e('Status', 'shrsb'); ?></h2>
                    
				</div>
				<div class="box-mid-body" id="toggle2">
					<div class="padding">
						<div id="plugin_health">
                            <table>
                            <tbody>
                                <tr>
                                    <td style="width: 22px;"><img class="shrsb_health_icon" src=
                                        <?php
                                            $color = $chmod_required ? "red":"green";
                                            echo SHRSB_PLUGPATH."images/circle_$color.png";
                                        ?>
                                    ></td>
                                    <td style="min-width: 240px;"><span class=""><?php _e('Directory Permissions', 'shrsb'); ?></span></td>
                                    <td>
                                        <?php
                                            echo $chmod_required ? sprintf(__('To Fix: Please appropriately
                                                                        %sCHMOD%s your /spritegen directory.', 'shrsb'),
                                                                    '<a href="https://shareaholic.com/tools/wordpress/usage-installation#chmodinfo"
                                                                        target = "_blank" style="color:#ca0c01">', '</a>') : "";
                                        ?>
                                    </td>
                                </tr>

                                <tr>
                                    <td class="" style="width: 22px;"><img class="shrsb_health_icon" src=
                                        <?php
                                            $color = $resave_required ? "yellow":"green";
                                            echo SHRSB_PLUGPATH."images/circle_$color.png";
                                        ?>
                                    ></td>
                                    <td><span class=""><?php _e('Load Time Optimized', 'shrsb'); ?></span></td>
                                    <td><?php
                                        echo $resave_required ? "To Do: click the \"Save Changes\" button at the bottom of this page." : "";
                                        ?>
                                    </td>
                                </tr>

                                <tr>
                                    <td class="" style="width: 22px;"><img class="shrsb_health_icon" src=
                                        <?php
                                            $color = $phpupdate_required ? "red":"green";
                                            echo SHRSB_PLUGPATH."images/circle_$color.png";
                                        ?>
                                    ></td>
                                    <td><span class=""><?php _e('Running PHP5+', 'shrsb'); ?></span></td>
                                    <td>
                                        <?php
                                        echo $phpupdate_required ? 'To Fix: Upgrade to PHP 5 or higher.' : "" ;
                                        ?>
                                    </td>
                                </tr>

                            </tbody>
                        </table>
                            </div>
                            <div id="genopts">
                            <table><tbody>
                                    <tr class="alert-success">
                                        <td><span class="shrsb_option"><?php _e('Enable the Sexybookmarks Sharing Bar?', 'shrsb'); ?> </span>
                                        </td>
                                        <td><label><input <?php echo (( @$shrsb_plugopts['firstrun'] == '1' || @$shrsb_plugopts['sexybookmark'] == "1") ? 'checked="checked"' : ""); ?> name="sexybookmark" id="sexybookmark-yes" type="radio" value="1" /> <?php _e('Yes', 'shrsb'); ?></label>
                                        </td><td><label><input <?php echo (( @$shrsb_plugopts['firstrun'] !== '1' &&  $shrsb_plugopts['sexybookmark'] == "0")? 'checked="checked"' : ""); ?> name="sexybookmark" id="sexybookmark-no" type="radio" value="0" /> <?php _e('No', 'shrsb'); ?></label>
                                        </td>
                                    </tr>

									<tr>
                                        <td><span class="shrsb_option">	<?php _e('Use "new mode"?') ?></span>
                                        </td>
                                        <td><label><input <?php echo (($shrsb_plugopts['shareaholic-javascript'] == "1")? 'checked="checked"' : ""); ?> name="shareaholic-javascript" id="shareaholic-javascript-1" type="radio" value="1" /> <?php _e('Yes', 'shrsb'); ?> (recommended)</label></td>
										<td><label><input <?php echo (($shrsb_plugopts['shareaholic-javascript'] != "1")? 'checked="checked"' : ""); ?> name="shareaholic-javascript" id="shareaholic-javascript-0" type="radio" value="" /> <?php _e('No', 'shrsb'); ?></label>
                                        </td>
                                    </tr>

                                    <tr>
                                        <td><span class="shrsb_option" style="padding-bottom: 10px"><?php _e('Enable 3rd Party Services to use the following features:', 'shrsb'); ?></span></td>
                                        <td><label><input <?php echo (($shrsb_plugopts['perfoption'] == "1")? 'checked="checked"' : ""); ?> name="perfoption" id="perfoption-yes" type="radio" value="1" /> <?php _e('Yes (recommended)', 'shrsb'); ?></label></td>
                                        <td><label><input <?php echo (($shrsb_plugopts['perfoption'] == "0")? 'checked="checked"' : ""); ?> name="perfoption" id="perfoption-no" type="radio" value="0" /> <?php _e('No', 'shrsb'); ?></label></td>
                                    </tr>
                                    <tr>
                                        <td><span class="tab" style="display:block; font-size: 11px; color: #666666;"><?php _e('Facebook, Twitter, LinkedIn and Delicious Share Counters', 'shrsb'); ?></span></td>
                                        <td><div class="icon-ok"></div></td>
                                        <td><div class="icon-remove"></div></td>
                                        <td><a href="#functionality"> Configure </a></td>
                                    </tr>
                                    
<!--                                    <tr>
                                        <td><span class="tab" style="display:block; font-size: 11px; color: #666666;"><?php _e('Facebook Like & Send, Google +1 buttons', 'shrsb'); ?></span></td>
                                        <td><div class="icon-ok"></div></td>
                                        <td><div class="icon-remove"></div></td>
                                        <td><a href="#likebuttonset">Configure</a></td>
                                    </tr>-->
                                    
                                    <tr>
                                        <td><span class="tab" style="display:block; font-size: 11px; color: #666666;"><?php _e('Shareaholic Social Analytics', 'shrsb'); ?></span></td>
                                        <td><div class="icon-ok"></div></td>
                                        <td><div class="icon-remove"></div></td>
                                        <td><a target="_blank" href="https://shareaholic.com/publishers/analytics/<?php echo $parse['host']?>/">Preview</a></td>
                                    </tr>
                                    
                                    <tr>
                                        <td><span class="tab" style="display:block; font-size: 11px; color: #666666;"><?php _e('Google Analytics Social Tracking', 'shrsb'); ?></span></td>
                                        <td><div class="icon-ok"></div></td>
                                        <td><div class="icon-remove"></div></td>
                                        <td><a href="./admin.php?page=shareaholic_analytics.php">Configure</a></td>
                                    </tr>

                            </tbody></table>
                        </div>					
					</div>	
				</div>	
			</li>
            
			<li>
				<div class="box-mid-head" id="iconator">
					<h2 class="fugue f-globe-plus"><?php _e('Enabled Networks', 'shrsb'); ?></h2>
				</div>
				<div class="box-mid-body iconator" id="toggle1">
					<div class="padding">
						<p><?php _e('Select the Networks to display. Drag to reorder.', 'shrsb'); ?></p>
						<ul class="multi-selection">
							<li><?php _e('Select', 'shrsb'); ?>:&nbsp;</li>
							<li><a id="sel-all" href="javascript:void(0);"><?php _e('All', 'shrsb'); ?></a>&nbsp;|&nbsp;</li>
							<li><a id="sel-none" href="javascript:void(0);"><?php _e('None', 'shrsb'); ?></a>&nbsp;|&nbsp;</li>
							<li><a id="sel-pop" href="javascript:void(0);"><?php _e('Most Popular', 'shrsb'); ?></a>&nbsp;</li>
		        </ul>
						<div id="shrsb-networks"><ul>
							<?php
								foreach ($shrsb_plugopts['bookmark'] as $name){if(array_key_exists($name, $shrsb_bookmarks_data)) {print shrsb_network_input_select($name, $shrsb_bookmarks_data[$name]['id'], $shrsb_bookmarks_data[$name]['check']);}}
								$unused_networks=array_diff(array_keys($shrsb_bookmarks_data), $shrsb_plugopts['bookmark']);
								foreach ($unused_networks as $name) print shrsb_network_input_select($name, $shrsb_bookmarks_data[$name]['id'], $shrsb_bookmarks_data[$name]['check']);
							?>
						</ul></div>
					</div>
					<div style="padding:10px; float:right;color:#999999;"><?php _e('Made with Much Love, these Icons are © Shareaholic', 'shrsb'); ?></div>
				</div>
			</li>
            

            <li>
				<div class="box-mid-head">
					<h2 class="fugue f-globe-plus"><?php _e('Additional Buttons', 'shrsb'); ?> <span style="color:orange;">* <?php _e('switch on "new mode" above to enable these exclusive features', 'shrsb'); ?></span></h2>
                    <a name="likebuttonset"></a>
				</div>
                <div class="box-mid-body" id="toggle2">
					<div class="padding">
						<div id="genopts">

                                    <table><tbody>
                                    <tr>
                                        <td><span class="shrsb_option"><?php _e('Include Open Graph Meta Tags?', 'shrsb'); ?></span>
                                        </td>
                                        <td style="width:125px"><label><input <?php echo (($shrsb_plugopts['ogtags'] == "1")? 'checked="checked"' : ""); ?> name="ogtags" id="ogtags-yes" type="radio" value="1" /> <?php _e('Yes', 'shrsb'); ?></label>
                                        </td><td><label><input <?php echo (($shrsb_plugopts['ogtags'] == "0")? 'checked="checked"' : ""); ?> name="ogtags" id="ogtags-no" type="radio" value="0" /> <?php _e('No', 'shrsb'); ?></label>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td><span class="shrsb_option"><?php _e('Include the like button-set just above the post?', 'shrsb'); ?></span>
                                        </td>
                                        <td style="width:125px"><label><input <?php echo (($shrsb_plugopts['likeButtonSetTop'] == "1")? 'checked="checked"' : ""); ?> name="likeButtonSetTop" id="likeButtonSetTop-yes" type="radio" value="1" /> <?php _e('Yes', 'shrsb'); ?></label>
                                        </td><td><label><input <?php echo (($shrsb_plugopts['likeButtonSetTop'] == "0")? 'checked="checked"' : ""); ?> name="likeButtonSetTop" id="likeButtonSetTop-no" type="radio" value="0" /> <?php _e('No', 'shrsb'); ?></label>
                                        </td>
                                    </tr>
                                    </tbody></table>

                                    <?php
                                        shrsb_likeButtonSetHTML($shrsb_plugopts,'Top');
                                    ?>

                                    <table><tbody>

                                    <tr>
                                        <td><span class="shrsb_option"><?php _e('Include the like button-set below the post?', 'shrsb'); ?></span>
                                        </td>
                                        <td style="width:125px"><label><input <?php echo (($shrsb_plugopts['likeButtonSetBottom'] == "1")? 'checked="checked"' : ""); ?> name="likeButtonSetBottom" id="likeButtonSetBottom-yes" type="radio" value="1" /> <?php _e('Yes', 'shrsb'); ?></label>
                                        </td><td><label><input <?php echo (($shrsb_plugopts['likeButtonSetBottom'] == "0")? 'checked="checked"' : ""); ?> name="likeButtonSetBottom" id="likeButtonSetBottom-no" type="radio" value="0" /> <?php _e('No', 'shrsb'); ?></label>
                                        </td>
                                    </tr>
                                    <?php
                                        shrsb_likeButtonSetHTML($shrsb_plugopts,'Bottom');
                                    ?>

                                    </tbody></table>

                                <br />

                                <span style="display:block;"><?php echo sprintf(__('Check out %sour support site%s for additional customization options.', 'shrsb'), '<a target="_blank" href="http://support.shareaholic.com/customer/portal/articles/656378-fb-like-send-google-1-sharing-buttons-support-">', '</a>'); ?></span>
                        </div>
                    </div>
                </div>

            </li>

			<li>
				<div class="box-mid-head">
					<h2 class="fugue f-wrench"><?php _e('Functionality Settings', 'shrsb'); ?></h2>
                    <a name="functionality"></a>
				</div>
				<div class="box-mid-body" id="toggle2">
					<div class="padding">
						<div id="genopts">
                            <table><tbody>
                                    <tr>
                                        <td><span class="shrsb_option"><?php _e('Show Share Counters', 'shrsb'); ?> <span style="color:orange;">*</span><sup style="color:#08C;"> #</sup></span>
                                            <span style="display:block; font-size: 11px; color: #666666;"><?php _e('For Facebook, LinkedIn, Twitter and Delicious', 'shrsb'); ?></span>
                                        </td>
                                            <td><label><input <?php echo (($shrsb_plugopts['showShareCount'] == "1")? 'checked="checked"' : ""); ?> name="showShareCount" id="showShareCount-yes" type="radio" value="1" /> <?php _e('Yes (recommended)', 'shrsb'); ?></label>
                                    </td><td><label><input <?php echo (($shrsb_plugopts['showShareCount'] == "0")? 'checked="checked"' : ""); ?> name="showShareCount" id="showShareCount-no" type="radio" value="0" /> <?php _e('No', 'shrsb'); ?></label>
                                        </td>
                                    </tr>

                                    <tr>
                                        <td><span class="shrsb_option"><?php _e('Use Designer Tooltips', 'shrsb'); ?> <span style="color:orange;">*</span></span></td>
                                        <td><label><input <?php echo (($shrsb_plugopts['designer_toolTips'] == "1")? 'checked="checked"' : ""); ?> name="designer_toolTips" id="designer_toolTips-yes" type="radio" value="1" /> <?php _e('Yes (recommended)', 'shrsb'); ?></label></td>
                                        <td><label><input <?php echo (($shrsb_plugopts['designer_toolTips'] == "0")? 'checked="checked"' : ""); ?> name="designer_toolTips" id="designer_toolTips-no" type="radio" value="0" /> <?php _e('No', 'shrsb'); ?></label></td>
						            </tr>

                                    <tr class="designer_toolTip_prefs" style="display:none">
                                                <td><label class="tab" for="tip_bg_color" style="margin-top:7px;"><?php _e('Background Color for Tooltips:', 'shrsb'); ?></label></td>
                                                <td><input style="margin-top:7px;" type="text" id="tip_bg_color" name="tip_bg_color" value="<?php echo $shrsb_plugopts['tip_bg_color']; ?>" /></td>
                                                <td style="padding-bottom: 9px"><div id="tip_bg_color_picker" class ="color_selector">
                                                    <div style="background-color:<?php echo $shrsb_plugopts['tip_bg_color']; ?>; "></div>
                                                </div>
                                                </td>
                                                <td><div id="tip_bg_color_picker_holder" style="display:none; margin-top: 5px; position: absolute;" ></div></td>
                                                <td> <div id="tip_bg_color_reset" style="margin-left: 5px;"><a href="javascript:void(0);"><?php _e('reset', 'shrsb'); ?></a></div></td>
                                    </tr>
                                    <tr class="designer_toolTip_prefs" style="display:none">
                                        <td><label class="tab" style="margin-top:7px;" for="tip_text_color"><?php _e('Text Color for Tooltips:', 'shrsb'); ?></label></td>
                                        <td><input style="margin-top:7px;" type="text" id="tip_text_color" name="tip_text_color" value="<?php echo $shrsb_plugopts['tip_text_color']; ?>" /></td>
                                        <td style="padding-bottom: 9px"><div id="tip_text_color_picker" class ="color_selector">
                                            <div style="background-color: <?php echo $shrsb_plugopts['tip_text_color']; ?>; "></div>
                                        </div>
                                        </td>
                                        <td><div id="tip_text_color_picker_holder" style="display:none; margin-top: 5px; position: absolute;"></div></td>
                                        <td> <div id="tip_text_color_reset" style="margin-left: 5px;"><a href="javascript:void(0);"><?php _e('reset', 'shrsb'); ?></a></div></td>
                                    </tr>
									<tr class="designer_toolTip_prefs" style="display:none">
										<td><label class="tab" style="margin-top:7px;" for="tip_text_color"><?php _e('Language for Tooltips:', 'shrsb'); ?> (<a href="http://blog.shareaholic.com/2011/05/shareaholic-for-publishers-now-features-automagic-translation/">?</a>)</label></td>
										<td colspan="3"><select name="locale" id="locale">
											<?php
											 $locales = array(
												'0'	=>	'Automagic Translation (recommended)',
												'en'	=>	'English',
												'es'	=>	'Spanish',
												'fr'	=>	'French',
												'de'	=>	'German',
												'tr'	=>	'Turkish',
												'it'	=>	'Italian',
												'pt'	=>	'Portugese',
										  		//'pt_BR'	=>	'Portugese Brazil',
												'et'	=>	'Estonian',
												'hu'	=>	'Hungarian',
												'bg'	=>	'Bulgarian',
												'el'	=>	'Greek',
												'lt'	=>	'Lithuanian',
												'he'	=>	'Hebrew',
												'nl'	=>	'Dutch'
											);    

												//run translation on each value								
				                                foreach($locales as $value)
													$value = __($value,'shrsb');

												// output locale select options	
												print shrsb_select_option_group('locale', $locales, $shrsb_plugopts);

											?>

										</select></td>
										<td style="padding-bottom: 9px"></td>
									</tr>

                                    <tr>
                                        <td><span class="shrsb_option"><?php _e('Add Nofollow to Links', 'shrsb'); ?></span></td>
                                        <td><label><input <?php echo (($shrsb_plugopts['reloption'] == "nofollow")? 'checked="checked"' : ""); ?> name="reloption" id="reloption-yes" type="radio" value="nofollow" /> <?php _e('Yes', 'shrsb'); ?></label>
                                        </td><td><label><input <?php echo (($shrsb_plugopts['reloption'] == "1")? 'checked="checked"' : ""); ?> name="reloption" id="reloption-no" type="radio" value="1" /> <?php _e('No', 'shrsb'); ?></label>
                                        </td>
                                    </tr>

                                    <tr>
                                        <td><span class="shrsb_option"><?php _e('Open Links in New Window', 'shrsb'); ?></span></td>
                                        <td><label><input <?php echo (($shrsb_plugopts['targetopt'] == "_blank")? 'checked="checked"' : ""); ?> name="targetopt" id="targetopt-blank" type="radio" value="_blank" /> <?php _e('Yes', 'shrsb'); ?></label>
                                        </td><td><label><input <?php echo (($shrsb_plugopts['targetopt'] == "_self")? 'checked="checked"' : ""); ?> name="targetopt" id="targetopt-self" type="radio" value="_self" /> <?php _e('No', 'shrsb'); ?></label>
                                        </td>
                                    </tr>

                                    <tr>
                                        <td>
                                            <span class="shrsb_option"><?php _e('Show Shareaholic Link', 'shrsb'); ?></span>
                                        </td>
                                        <td><label><input <?php echo (($shrsb_plugopts['shrlink'] == "1" || $shrsb_plugopts['shrlink'] == '')? 'checked="checked"' : ""); ?> name="shrlink" id="shrlink-yes" type="radio" value="1" /> <?php _e('Yes', 'shrsb'); ?></label>
                                        </td><td><label><input <?php echo (($shrsb_plugopts['shrlink'] == "0")? 'checked="checked"' : ""); ?> name="shrlink" id="shrlink-no" type="radio" value="0" /> <?php _e('No', 'shrsb'); ?></label>
                                        </td>
                                    </tr>

									<tr>
                                        <td><span class="shrsb_option"><?php _e('Want to know about new products?', 'shrsb'); ?></span></td>
                                        <td><label><input <?php echo (($shrsb_plugopts['promo'] == "1")? 'checked="checked"' : ""); ?> name="promo" id="promo-1" type="radio" value="1" /> <?php _e('Yes', 'shrsb'); ?></label></td>
										<td><label><input <?php echo (($shrsb_plugopts['promo'] != "1")? 'checked="checked"' : ""); ?> name="promo" id="promo-0" type="radio" value="" /> <?php _e('No', 'shrsb'); ?></label></td>
                                    </tr>

		                            <input type="hidden" name="shrbase" value="<?php echo $shrsb_plugopts['shrbase'] ?>"/>
		                            <input type="hidden" name="apikey" placeholder="8afa39428933be41f8afdb8ea21a495c" value="<?php echo $shrsb_plugopts['apikey']?$shrsb_plugopts['apikey']:'8afa39428933be41f8afdb8ea21a495c' ?>"/>

                            </tbody></table>
							<br />
							<span style="display:block;"><span style="color:orange;">* <?php _e('switch on "new" mode above to enable these exclusive features', 'shrsb'); ?></span></span>
                            <span style="display:block;"><span style="color:#08C;"># <?php echo sprintf( __('Click %shere%s to enable 3rd party services to use this feature', 'shrsb'), '<a href="#3rdpartyservices">', '</a>'); ?></span></span>	
                        </div>
					</div>
				</div>
			</li>		

			<li id="twitter-defaults" <?php if(!in_array('shr-twitter', $shrsb_plugopts['bookmark'])) { ?> class="hide"<?php } ?>>
				<div class="box-mid-head" id="iconator">
					<h2><img src="<?php echo SHRSB_PLUGPATH; ?>images/twitter-16x16.png" alt="Twitter!" align="absmiddle"  style="margin-right: 8px;" /><?php _e('Twitter Options', 'shrsb'); ?></h2>
				</div>
				<div class="box-mid-body" id="toggle6">
					<div class="padding">

					    <p id="tweetinstructions">
                            <strong><?php _e('Configuration Instructions:', 'shrsb'); ?></strong><br />
                            <?php echo sprintf(__('Using the strings %s and %s you can fully customize your tweet output.', 'shrsb'), '<strong>${title}</strong>', '<strong>${short_link}</strong>'); ?><br /><br />
                            <strong><?php _e('Example Configurations:', 'shrsb'); ?></strong><br />
                            <em>${title} - ${short_link} (via @Shareaholic)</em><br />
                            <?php _e('or', 'shrsb'); ?><br />
                            <em>RT @Shareaholic: ${title} - ${short_link}</em>
                          </p>
                          <div style="position:relative;width:80%;">
                            <label for="tweetconfig"><?php _e('Configure Custom Tweet Template:', 'shrsb'); ?></label><small id="tweetcounter"><?php _e('Characters:', 'shrsb'); ?> <span></span></small><br />
                            <textarea id="tweetconfig" name="tweetconfig"><?php if(!empty($shrsb_plugopts['tweetconfig'])) { echo urldecode($shrsb_plugopts['tweetconfig']); } else { echo '${title} - ${short_link} via @Shareaholic'; } ?></textarea>
                          </div>
                          <p id="tweetoutput"><strong><?php _e('Example Tweet Output:', 'shrsb'); ?></strong><br /><span></span></p>

				        <label for="shorty"><?php _e('Which URL Shortener?', 'shrsb'); ?></label><br />
						<select name="shorty" id="shorty">
							<?php
								// output shorty select options
								print shrsb_select_option_group('shorty', 
                                        array(
                                            'none'      =>__("Don't use a shortener", 'shrsb'),
                                            'shrlc'     =>  'shr.lc',
											                      'bitly'     =>  'bit.ly',
                                            'jmp'       =>  'j.mp',
                                            'google'    =>  'Google (goo.gl)',
                                            'tinyurl'   =>  'tinyurl',
                                            'tflp'      =>  'Twitter Friendly Links WP Plugin',
                                            'yourls'    =>  'YOURLS WP Plugin'
                                        ), 
                                        $shrsb_plugopts
                                );
							?>

						</select>
						<div id="shortyapimdiv-bitly"<?php if($shrsb_plugopts['shorty'] != "bitly") { ?> class="hidden"<?php } ?>>
							<div id="shortyapidiv-bitly">
								<label for="shortyapiuser-bitly"><?php _e('User ID:', 'shrsb'); ?></label>
								<input type="text" id="shortyapiuser-bitly" name="shortyapiuser-bitly" value="<?php echo $shrsb_plugopts['shortyapi']['bitly']['user']; ?>" />
								<label for="shortyapikey-bitly"><?php _e('API Key:', 'shrsb'); ?></label>
								<input type="text" id="shortyapikey-bitly" name="shortyapikey-bitly" value="<?php echo $shrsb_plugopts['shortyapi']['bitly']['key']; ?>" />
							</div>
						</div>
                        
            <div id="shortyapimdiv-jmp"<?php if($shrsb_plugopts['shorty'] != "jmp") { ?> class="hidden"<?php } ?>>
							<div id="shortyapidiv-jmp">
								<label for="shortyapiuser-jmp"><?php _e('User ID:', 'shrsb'); ?></label>
								<input type="text" id="shortyapiuser-jmp" name="shortyapiuser-jmp" value="<?php echo $shrsb_plugopts['shortyapi']['jmp']['user']; ?>" />
								<label for="shortyapikey-jmp"><?php _e('API Key:', 'shrsb'); ?></label>
								<input type="text" id="shortyapikey-jmp" name="shortyapikey-jmp" value="<?php echo $shrsb_plugopts['shortyapi']['jmp']['key']; ?>" />
							</div>
						</div>

						<div class="clearbig"></div>
					</div>
				</div>
			</li>

			<li>
				<div class="box-mid-head">
					<h2 class="fugue f-pallette"><?php _e('Plugin Aesthetics', 'shrsb'); ?></h2>
				</div>
				<div class="box-mid-body" id="toggle3">
					<div class="padding">
						<div id="custom-mods-notice">
							<h1><?php _e('Warning!', 'shrsb'); ?></h1>
              <p><?php echo sprintf(__('This option is intended %STRICTLY%s for users who understand how to edit CSS/JS and intend to change/edit the associated images themselves. Unfortunately, no support will be offered for this feature, as I cannot be held accountable for your coding and/or image editing mistakes.', 'shrsb'), '<strong>', '</strong>'); ?></p>
							<h3><?php _e('How it works...', 'shrsb'); ?></h3>
							<p><?php _e('Since you have chosen for the plugin to override the style settings with your own custom mods, it will now pull the files from the new folders it is going to create on your server as soon as you save your changes. The file/folder locations should be as follows:', 'shrsb'); ?></p>
							<ul>
								<li class="custom-mods-folder"><a href="<?php echo WP_CONTENT_URL.'/sexy-mods'; ?>"><?php echo WP_CONTENT_URL.'/sexy-mods'; ?></a></li>
								<li class="custom-mods-folder"><a href="<?php echo WP_CONTENT_URL.'/sexy-mods/css'; ?>"><?php echo WP_CONTENT_URL.'/sexy-mods/css'; ?></a></li>
								<li class="custom-mods-folder"><a href="<?php echo WP_CONTENT_URL.'/sexy-mods/js'; ?>"><?php echo WP_CONTENT_URL.'/sexy-mods/js'; ?></a></li>
								<li class="custom-mods-folder"><a href="<?php echo WP_CONTENT_URL.'/sexy-mods/images'; ?>"><?php echo WP_CONTENT_URL.'/sexy-mods/images'; ?></a></li>
								<li class="custom-mods-code"><a href="<?php echo WP_CONTENT_URL.'/sexy-mods/js/sexy-bookmarks-public.js'; ?>"><?php echo WP_CONTENT_URL.'/sexy-mods/js/sexy-bookmarks-public.js'; ?></a></li>
								<li class="custom-mods-code"><a href="<?php echo WP_CONTENT_URL.'/sexy-mods/css/style.css'; ?>"><?php echo WP_CONTENT_URL.'/sexy-mods/css/style.css'; ?></a></li>
								<li class="custom-mods-image"><a href="<?php echo WP_CONTENT_URL.'/sexy-mods/images/shr-sprite.png'; ?>"><?php echo WP_CONTENT_URL.'/sexy-mods/images/shr-sprite.png'; ?></a></li>
								<li class="custom-mods-image"><a href="<?php echo WP_CONTENT_URL.'/sexy-mods/images/share-enjoy.png'; ?>"><?php echo WP_CONTENT_URL.'/sexy-mods/images/share-enjoy.png'; ?></a></li>
								<li class="custom-mods-image"><a href="<?php echo WP_CONTENT_URL.'/sexy-mods/images/share-german.png'; ?>"><?php echo WP_CONTENT_URL.'/sexy-mods/images/share-german.png'; ?></a></li>
								<li class="custom-mods-image"><a href="<?php echo WP_CONTENT_URL.'/sexy-mods/images/share-love-hearts.png'; ?>"><?php echo WP_CONTENT_URL.'/sexy-mods/images/share-love-hearts.png'; ?></a></li>
								<li class="custom-mods-image"><a href="<?php echo WP_CONTENT_URL.'/sexy-mods/images/share-wealth.png'; ?>"><?php echo WP_CONTENT_URL.'/sexy-mods/images/share-wealth.png'; ?></a></li>
								<li class="custom-mods-image"><a href="<?php echo WP_CONTENT_URL.'/sexy-mods/images/sharing-caring-hearts.png'; ?>"><?php echo WP_CONTENT_URL.'/sexy-mods/images/sharing-caring-hearts.png'; ?></a></li>
								<li class="custom-mods-image"><a href="<?php echo WP_CONTENT_URL.'/sexy-mods/images/sharing-caring.png'; ?>"><?php echo WP_CONTENT_URL.'/sexy-mods/images/sharing-caring.png'; ?></a></li>
								<li class="custom-mods-image"><a href="<?php echo WP_CONTENT_URL.'/sexy-mods/images/sharing-shr.png'; ?>"><?php echo WP_CONTENT_URL.'/sexy-mods/images/sharing-shr.png'; ?></a></li>
							</ul>
							<p><?php _e('Once you have saved your changes, you will be able to edit the image sprite that holds all of the icons for Shareaholic as well as the CSS which accompanies it. Just be sure that you do in fact edit the CSS if you edit the images, as it is unlikely the heights, widths, and background positions of the images will stay the same after you are done.', 'shrsb'); ?></p>
							<p><?php _e('Just a quick note... When you edit the styles and images to include your own custom backgrounds, icons, and CSS styles, be aware that those changes will not be reflected on the plugin options page. In other words: when you select your networks to be displayed, or when you select the background image to use, it will still be displaying the images from the original plugin directory.', 'shrsb'); ?></p>
							<h3><?php _e('In Case of Emergency', 'shrsb'); ?></h3>
							<p><?php _e('If you happen to mess things up, you can follow these directions to reset the plugin back to normal and try again if you wish:', 'shrsb'); ?></p>
							<ol>
								<li><?php _e('Login to your server via FTP or SSH. (whichever you are more comfortable with)', 'shrsb'); ?></li>
								<li><?php _e('Navigate to your wp-content directory.', 'shrsb'); ?></li>
								<li><?php _e('Delete the directory named "sexy-mods".', 'shrsb'); ?></li>
								<li><?php _e('Login to your WordPress dashboard.', 'shrsb'); ?></li>
								<li><?php _e('Go to the SexyBookmarks plugin options page. (Settings->SexyBookmarks)', 'shrsb'); ?></li>
								<li><?php _e('Deselect the "Use custom mods" option.', 'shrsb'); ?></li>
								<li><?php _e('Save your changes.', 'shrsb'); ?></li>
							</ol>
							<span class="fugue f-delete custom-mods-notice-close"><?php _e('Close Message', 'shrsb'); ?></span>
						</div>
						<div class="custom-mod-check fugue f-plugin">
							<label for="custom-mods" class="shrsb_option" style="display:inline;">
								<?php _e('Override Styles With Custom Mods Instead?', 'shrsb'); ?>
							</label>
							<input <?php echo (($shrsb_plugopts['custom-mods'] == "yes")? 'checked' : ""); ?> name="custom-mods" id="custom-mods" type="checkbox" value="yes" />
						</div>

						<span class="shrsb_option"><?php _e('Animate-expand multi-lined bookmarks?', 'shrsb'); ?></span>
						<label><input <?php echo (($shrsb_plugopts['expand'] == "1")? 'checked="checked"' : ""); ?> name="expand" id="expand-yes" type="radio" value="1" /><?php _e('Yes', 'shrsb'); ?></label>
						<label><input <?php echo (($shrsb_plugopts['expand'] != "1")? 'checked="checked"' : ""); ?> name="expand" id="expand-no" type="radio" value="0" /><?php _e('No', 'shrsb'); ?></label>
						<span class="shrsb_option"><?php _e('Auto-space/center the bookmarks?', 'shrsb'); ?></span>
						<label><input <?php echo (($shrsb_plugopts['autocenter'] == "2")? 'checked="checked"' : ""); ?> name="autocenter" id="autospace-yes" type="radio" value="2" /><?php _e('Space', 'shrsb'); ?></label>
						<label><input <?php echo (($shrsb_plugopts['autocenter'] == "1")? 'checked="checked"' : ""); ?> name="autocenter" id="autocenter-yes" type="radio" value="1" /><?php _e('Center', 'shrsb'); ?></label>
						<label><input <?php echo (($shrsb_plugopts['autocenter'] == "0")? 'checked="checked"' : ""); ?> name="autocenter" id="autocenter-no" type="radio" value="0" /><?php _e('No', 'shrsb'); ?></label>

						<span class="shrsb_option">
							<?php _e('Use a background image?', 'shrsb'); ?> <input <?php echo (($shrsb_plugopts['bgimg-yes'] == "yes")? 'checked' : ""); ?> name="bgimg-yes" id="bgimg-yes" type="checkbox" value="yes" />
						</span>
						<div id="bgimgs" class="<?php if(!isset($shrsb_plugopts['bgimg-yes'])) { ?>hidden<?php } else { echo ''; }?>">
							<label class="share-sexy">
								<input <?php echo (($shrsb_plugopts['bgimg'] == "shr")? 'checked="checked"' : ""); ?> id="bgimg-sexy" name="bgimg" type="radio" value="shr" />
							</label>
							<label class="share-care">
								<input <?php echo (($shrsb_plugopts['bgimg'] == "caring")? 'checked="checked"' : ""); ?> id="bgimg-caring" name="bgimg" type="radio" value="caring" />
							</label>
							<label class="share-care-old">
								<input <?php echo (($shrsb_plugopts['bgimg'] == "care-old")? 'checked="checked"' : ""); ?> id="bgimg-care-old" name="bgimg" type="radio" value="care-old" />
							</label>
							<label class="share-love">
								<input <?php echo (($shrsb_plugopts['bgimg'] == "love")? 'checked="checked"' : ""); ?> id="bgimg-love" name="bgimg" type="radio" value="love" />
							</label>
							<label class="share-wealth">
								<input <?php echo (($shrsb_plugopts['bgimg'] == "wealth")? 'checked="checked"' : ""); ?> id="bgimg-wealth" name="bgimg" type="radio" value="wealth" />
							</label>
							<label class="share-enjoy">
								<input <?php echo (($shrsb_plugopts['bgimg'] == "enjoy")? 'checked="checked"' : ""); ?> id="bgimg-enjoy" name="bgimg" type="radio" value="enjoy" />
							</label>
							<label class="share-german">
								<input <?php echo (($shrsb_plugopts['bgimg'] == "german")? 'checked="checked"' : ""); ?> id="bgimg-german" name="bgimg" type="radio" value="german" />
							</label>
							<label class="share-knowledge">
								<input <?php echo (($shrsb_plugopts['bgimg'] == "knowledge")? 'checked="checked"' : ""); ?> id="bgimg-knowledge" name="bgimg" type="radio" value="knowledge" />
							</label>
						</div>
					</div>
				</div>
			</li>

            <li>
                <div class="box-mid-head">
					<h2 class="fugue f-wrench"><?php _e('Compatibility Settings', 'shrsb'); ?></h2>
				</div>
				<div class="box-mid-body" id="toggle2">
					<div class="padding">

                        <?php if (class_exists('WPMinify')) { ?>
                        <span class="shrsb_option"><?php _e('WP-Minify Compatibility Mode', 'shrsb'); ?></span>
							<label><input <?php echo (($shrsb_plugopts['preventminify'] == "1")? 'checked="checked"' : ""); ?> name="preventminify" id="preventminify-yes" type="radio" value="1" /> <?php _e('Enabled (recommended)', 'shrsb'); ?></label>
							<label><input <?php echo (($shrsb_plugopts['preventminify'] == "0")? 'checked="checked"' : ""); ?> name="preventminify" id="preventminify-no" type="radio" value="0" /> <?php _e('Disabled', 'shrsb'); ?></label>
							<span style="display:block;"><?php _e('(SexyBookmarks may not work with this option turned off)', 'shrsb'); ?></span>
                         <?php } ?>
                            <span class="shrsb_option"><input type="checkbox" id="doNotIncludeJQuery" name="doNotIncludeJQuery" <?php echo (($shrsb_plugopts['doNotIncludeJQuery'] == "1")? 'checked' : ""); ?> value="1" /> <?php _e('jQuery Compatibility Fix', 'shrsb'); ?></span>
						<label for="doNotIncludeJQuery"><?php _e("Check this box ONLY if you notice jQuery being loaded twice in your source code!", "shrsb"); ?></label>
						
						<span class="shrsb_option"><input type="checkbox" id="scriptInFooter" name="scriptInFooter" <?php echo (($shrsb_plugopts['scriptInFooter'] == "1")? 'checked' : ""); ?> value="1" /> <?php _e('Load scripts in Footer', 'shrsb'); ?></span>
						<label for="scriptInFooter"><?php _e("Check this box if you want the SexyBookmarks javascript to be loaded in your blog's footer.", 'shrsb'); ?> (<a href="http://developer.yahoo.com/performance/rules.html#js_bottom" target="_blank">?</a>)</label>

                        <span class="shrsb_option"><input type="checkbox" id="fbNameSpace" name="fbNameSpace" <?php echo (($shrsb_plugopts['fbNameSpace'] == "1")? 'checked' : ""); ?> value="1" /> <?php _e('Add Facebook required namespaces to your HTML tag? (recommended)', 'shrsb'); ?></span>
						<label for="fbNameSpace"><?php _e("Check this box if you include Facebook's Like/Send buttons. These buttons may not work with this option turned off.", 'shrsb'); ?></label>
						
                       
                        <span class="shrsb_option"><?php _e('Custom Path to Shareaholic File Resources', 'shrsb'); ?></span>
                        <label for="spritegen_path"><?php _e("Set Custom Path:", "shrsb"); ?>
                            <input class="span12" style="margin-top:7px; min-width: 500px;" type="text" id="spritegen_path" name="spritegen_path"  value="<?php echo shrb_addTrailingChar(stripslashes($shrsb_plugopts['spritegen_path']), '/'); ?>" /></label>
                        <p><?php _e("Default Path: ", "shrsb"); echo SHRSB_UPLOADDIR_DEFAULT; ?> </p>
                    </div>
                </div>
            </li>

			<li>
				<div class="box-mid-head">
					<h2 class="fugue f-footer"><?php _e('Menu Placement', 'shrsb'); ?></h2>
				</div>
				<div class="box-mid-body" id="toggle5">
					<div class="padding">
						<div class="dialog-box-information" id="info-manual">
							<div class="dialog-left fugue f-info">
								<?php echo sprintf(__('Need help with this? Find it in the %sofficial install guide%s.', 'shrsb'), '<a href="https://shareaholic.com/tools/wordpress/usage-installation">', '</a>'); ?></a>
							</div>
							<div class="dialog-right">
								<img src="<?php echo SHRSB_PLUGPATH; ?>images/information-delete.jpg" class="del-x" alt=""/>
							</div>
						</div>
						<span class="shrsb_option"><?php _e('Menu Location (in relation to content):', 'shrsb'); ?></span>
						<label><input <?php echo (($shrsb_plugopts['position'] == "above")? 'checked="checked"' : ""); ?> name="position" id="position-above" type="radio" value="above" /> <?php _e('Above Content', 'shrsb'); ?></label>
						<label><input <?php echo (($shrsb_plugopts['position'] == "below")? 'checked="checked"' : ""); ?> name="position" id="position-below" type="radio" value="below" /> <?php _e('Below Content', 'shrsb'); ?></label>
            <label><input <?php echo (($shrsb_plugopts['position'] == "both")? 'checked="checked"' : ""); ?> name="position" id="position-both" type="radio" value="both" /> <?php _e('Above & Below Content', 'shrsb'); ?></label>
						<label><input <?php echo (($shrsb_plugopts['position'] == "manual")? 'checked="checked"' : ""); ?> name="position" id="position-manual" type="radio" value="manual" /> <?php _e('Manual Mode', 'shrsb'); ?></label>
						
                        <?php shrsb_options_menu_type($shrsb_plugopts['pageorpost']); ?>
                        
                        <span class="shebang-info fugue f-question" title="<?php _e('Click here for help with this option', 'shrsb'); ?>"> </span>
						<span class="shrsb_option"><?php _e('Show in RSS feed?', 'shrsb'); ?></span>
						<label><input <?php echo (($shrsb_plugopts['feed'] == "1")? 'checked="checked"' : ""); ?> name="feed" id="feed-show" type="radio" value="1" /> <?php _e('Yes', 'shrsb'); ?></label>
						<label><input <?php echo (($shrsb_plugopts['feed'] == "0" || empty($shrsb_plugopts['feed']))? 'checked="checked"' : ""); ?> name="feed" id="feed-hide" type="radio" value="0" /> <?php _e('No', 'shrsb'); ?></label>
						<span class="shrsb_option" style="margin-top:12px;">
							<?php _e('Hide menu from mobile browsers?', 'shrsb'); ?> <input <?php echo (($shrsb_plugopts['mobile-hide'] == "yes")? 'checked' : ""); ?> name="mobile-hide" id="mobile-hide" type="checkbox" value="yes" />
						</span>
						<br />
					</div>
				</div>
			</li>
		</ul>
		<div style="clear:both;"></div>
		<input type="hidden" name="save_changes_sb" value="1" />
		<?php wp_nonce_field('save-settings','shareaholic_nonce'); ?>
        <div class="shrsbsubmit"><input type="submit" id="save_changes_sb" value="<?php _e('Save Changes', 'shrsb'); ?>" /></div>
	</form>
	<form action="" method="post">
		<input type="hidden" name="reset_all_options_sb" id="reset_all_options_sb" value="0" />
		<?php wp_nonce_field('reset-settings','shareaholic_nonce'); ?>
		<div class="shrsbreset"><input type="submit" value="<?php _e('Reset Settings', 'shrsb'); ?>" /></div>
	</form>

<div id="third-party-submit-modal" class="reveal-modal" style="width: 520px;">
    
    <a class="close-reveal-modal hide-reveal-modal">&#215;</a>
    
	<h3><?php _e('3rd Party Features', 'shrsb'); ?></h3>
    
    <div class="modal-body" style="padding-left: 0px;">
      <h3><?php _e('You have chosen to enable features that are dependent on 3rd party services like Google.  What would you like to do?', 'shrsb'); ?></h3>
    </div>
    <p>
      <a href="#" id="enable3rdParty" class="pull-right btn btn-primary hide-reveal-modal"><?php _e('Enable 3rd Party Features (highly recommended)', 'shrsb'); ?></a> 
      <a href="#" id="disable3rdParty" class="pull-right hide-reveal-modal" style="padding: 7px 10px 0 0;"><?php _e('Disable', 'shrsb'); ?></a>
    </p>
</div>

<script type="text/javascript">

function showModal(){
    var $= jQuery;

    var o = $('#third-party-submit-modal');
    
    $('#enable3rdParty',o).click(function(){
        $('input:radio[name=perfoption]').removeAttr('checked');
        $('#perfoption-yes').attr('checked',true)
        forceSubmit();
    });
    
    $('#disable3rdParty',o).click(function(){
        $('input:radio[name=perfoption]').removeAttr('checked');
        $('#perfoption-no').attr('checked',true)
        forceSubmit();
    });
    
    o.reveal({
        scrollheight:0
        , closeonbackgroundclick: true
        , dismissmodalclass:"hide-reveal-modal"
        , animation:'fadeAndPop'
        , backdrop: true
    });
    
    function forceSubmit(){
        $('#sexy-bookmarks').submit();
    }
    
}

function isRadioEnabled(name){
    return jQuery('input:radio[name='+ name + ']:checked').val() == '1';
}


function is3rdPartyDependent() {
    
     var shrsb_analytics = <?php echo json_encode($shrsb_analytics) ?> ;
    
    return (shrsb_analytics.pubGaSocial == '1') ||  isRadioEnabled('showShareCount') ;
}

function handleSubmit(){
    
    var $= jQuery;
    
//    $('#sexy-bookmarks').submit(function(){
    $('#save_changes_sb').click(function(){
        
        // If 3rd party dependent
        if( is3rdPartyDependent() && !isRadioEnabled('perfoption') ){
            showModal();
            return false;
        }
    })
}

handleSubmit();

</script>	


	<?php echo shrsb_getfooter(); ?>
	
</div>


<?php

//Right Side helpful links
echo shrsb_right_side_menu();
//Snap Engage
echo get_snapengage();

}//closing brace for function "shrsb_settings_page"


/*
*   @desc Checks to see if wp-minify is installed, if so, whitelist our files
*/
function exclude_from_minify_list() {
    $minify_opts = get_option("wp_minify");

    if(is_array($minify_opts) && is_array($minify_opts["js_exclude"])) {
        $sbfound = false;
        $tbfound = false;
        $shr_dough_recipe = false;
        foreach($minify_opts["js_exclude"] as $url) {
            if($url == 'jquery.shareaholic-publishers-sb.min.js') {
                $sbfound = true;
            }
            if($url == 'jquery.shareaholic-share-buttons.min.js') {
                $tbfound = true;
            }
            if($url == 'shareaholic-analytics.js') {
                $shr_dough_recipe = true;
            }
        }
        if(!$sbfound) {
            array_push($minify_opts["js_exclude"],'jquery.shareaholic-publishers-sb.min.js');
        }
        if(!$tbfound) {
            array_push($minify_opts["js_exclude"],'jquery.shareaholic-share-buttons.min.js');
        }
        if(!$shr_dough_recipe) {
            array_push($minify_opts["js_exclude"],'shareaholic-analytics.js');
        }
        update_option("wp_minify", $minify_opts);
    }
}

function _make_params($params) {
  $pairs = array();
  foreach ($params as $k => $v) {
    $pairs[] = implode('=', array(urlencode($k), urlencode($v)));
  }
  return implode('&', $pairs);
}



/**
 * Make a local copy of all shareaholic resources
 */
function shrsb_refresh_cache() {
  global $shrsb_plugopts, $shrsb_bgimg_map, $default_spritegen;

  $script_sb = _shrsb_fetch_content('/media/js/jquery.shareaholic-publishers-sb.min.js', '/jquery.shareaholic-publishers-sb.min.js', true);
  $script_tb = _shrsb_fetch_content('/media/js/jquery.shareaholic-share-buttons.min.js', '/jquery.shareaholic-share-buttons.min.js', true);

  // Sort services to make request more cacheable.
  $services = explode(',', $shrsb_plugopts['service']);
  sort($services, SORT_NUMERIC);
  $services = implode(',', $services);

  $sprite_opts = array(
    'v' => 2,
    'apikey' => $shrsb_plugopts['apikey'],
    'service' => $services,
    'bgimg' => $shrsb_bgimg_map[$shrsb_plugopts['bgimg']]['url'],
    'bgimg_padding' => $shrsb_bgimg_map[$shrsb_plugopts['bgimg']]['padding']
  );
  // save as css so mime types work on normal servers
  $css_sb = _shrsb_fetch_content('/api/sprite/?'._make_params($sprite_opts), '/sprite.css', true);
  $css_tb = _shrsb_fetch_content('/media/css/shareaholic-share-button.css', '/shareaholic-share-button.css', true);
  
  $sprite_opts['apitype'] = 'png';
  $png_sb = _shrsb_fetch_content('/api/sprite/?'._make_params($sprite_opts), '/sprite.png', true);
  $png_tb = _shrsb_fetch_content('/media/images/styles/tb/shareaholic-publishers-mini.png', '/shareaholic-publishers-mini.png', true);
  $png_tb_arrow_up = _shrsb_fetch_content('/media/images/styles/tb/arrow_up.png', '/arrow_up.png', true);
  $png_tb_arrow_down = _shrsb_fetch_content('/media/images/styles/tb/arrow_down.png', '/arrow_down.png', true);

  if(!$script_sb || !$script_tb || !$css_sb || !$css_tb || !$png_sb || !$png_tb || !$png_tb_arrow_up || !$png_tb_arrow_down) {
    update_option('SHRSB_DefaultSprite',true);
    $default_spritegen = true;
  } else {
    update_option('SHRSB_DefaultSprite',false);
    $default_spritegen = false;
  }
}

function shrsb_requires_resave() {
        global $shrsb_plugopts,$default_spritegen;
        $resave_required = false;
        if(($shrsb_plugopts['shareaholic-javascript'] == '1'  //new mode
                    && $default_spritegen)
                || ($shrsb_plugopts['shareaholic-javascript'] != '1'      //old mode
                    && !(file_exists(SHRSB_UPLOADDIR.'spritegen/shr-custom-sprite.png')
                            && file_exists(SHRSB_UPLOADDIR.'spritegen/shr-custom-sprite.css')
                        )
                    )
        ){
            $resave_required = true;
        }

        return $resave_required;
}
/*
*   @desc Check for chmod for new-custom and old-custom mode only
*/
function shrsb_requires_chmod($mode = NULL) {
    return !(is_writable(SHRSB_UPLOADDIR.'spritegen'));
}

function shrsb_requires_phpupdate() {
    return (strnatcmp(phpversion(),'5.0') < 0);
}

?>