<?php

/* 
 * @desc Topbar Settings page
*/

function shrsb_tb_settings_page() {
	global $shrsb_tb_plugopts;
    // Add all the global varaible declarations for the $shrsb_tb_plugopts
	echo '<div class="wrap""><div class="icon32" id="icon-options-general"><br></div><h2>Share Buttons: Top Bar Settings</h2></div>';

	// processing form submission
	$status_message = "";
	$error_message = "";
	if(isset($_POST['save_changes_tb']) && check_admin_referer('save-settings','shareaholic_nonce')) {

    	// Set success message
		$status_message = __('Your changes have been saved successfully!', 'shrsb');
		$_POST['pageorpost'] = shrsb_set_content_type();
        foreach (array(
                'topbar', 'useSbSettings' , 'tb_bg_color' ,'tb_border_color', 'addv',
                
                'likeButtonSetTop','fbLikeButtonTop','fbSendButtonTop','googlePlusOneButtonTop','tweetButtonTop','likeButtonSetSizeTop','likeButtonSetCountTop',
                'likeButtonOrderTop','likeButtonSetAlignmentTop','pageorpost'
            
            )as $field) {
                if(isset($_POST[$field])) { // this is to prevent warning if $_POST[$field] is not defined
                    $shrsb_tb_plugopts[$field] = $_POST[$field];
                } else {
                    $shrsb_tb_plugopts[$field] = NULL;
                }
          }
          
          update_option('ShareaholicTopbar', $shrsb_tb_plugopts);
          
      
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
?>

<form name="shareaholic-topbar" id="shareaholic-topbar" action="" method="post">
    <div id="shrsb-col-left" style="width: 100%">
		<ul id="shrsb-sortables">
            
            <li>
				<div class="box-mid-head">
					<h2 class="fugue f-globe-plus"><?php _e('Top Bar', 'shrsb'); ?> <span style="color:orange;">* <?php _e('switch on "new mode" in the SexyBookmarks tab to enable', 'shrsb'); ?></span></h2>
				</div>
                <div class="box-mid-body" id="toggle2">
					<div class="padding">
						<div id="genopts">

                                    <table><tbody>
                                    <tr class="alert-success">
                                        <td><span class="shrsb_option"><?php _e('Enable the Top Sharing Bar?', 'shrsb'); ?> </span>
                                        </td>
                                        <td style="width:125px"><label><input <?php echo (($shrsb_tb_plugopts['topbar'] == "1")? 'checked="checked"' : ""); ?> name="topbar" id="topbar-yes" type="radio" value="1" /> <?php _e('Yes', 'shrsb'); ?></label>
                                        </td><td><label><input <?php echo (($shrsb_tb_plugopts['topbar'] == "0")? 'checked="checked"' : ""); ?> name="topbar" id="topbar-no" type="radio" value="0" /> <?php _e('No', 'shrsb'); ?></label>
                                        </td>
                                    </tr>
                                    
                                    <tr>
                                        <td><span class="shrsb_option"><?php _e('Use Default Settings?', 'shrsb'); ?></span>
                                        </td>
                                        <td style="width:125px"><label><input <?php echo (($shrsb_tb_plugopts['useSbSettings'] == "1")? 'checked="checked"' : ""); ?> name="useSbSettings" id="useSbSettings-yes" type="radio" value="1" /> <?php _e('Yes', 'shrsb'); ?></label>
                                        </td><td><label><input <?php echo (($shrsb_tb_plugopts['useSbSettings'] == "0")? 'checked="checked"' : ""); ?> name="useSbSettings" id="useSbSettings-no" type="radio" value="0" /> <?php _e('No', 'shrsb'); ?></label>
                                        </td>
                                    </tr>
                                    <tr class="topbar_prefs" style="display:none">
                                                <td><label class="tab" for="tb_bg_color" style="margin-top:7px;"><?php _e('Background Color for Toolbar:', 'shrsb'); ?></label></td>
                                                <td><input style="margin-top:7px;" type="text" id="tb_bg_color" name="tb_bg_color" value="<?php echo $shrsb_tb_plugopts['tb_bg_color']; ?>" /></td>
                                                <td style="padding-bottom: 9px;"><div id="tb_bg_color_picker" class ="color_selector">
                                                    <div style="background-color:<?php echo $shrsb_tb_plugopts['tb_bg_color']; ?>; "></div>
                                                </div>
                                                </td>
                                                <td><div id="tb_bg_color_picker_holder" style="display:none; margin-top: 5px; position: absolute;" ></div></td>
                                                <td> <div id="tb_bg_color_reset" style="margin-left: 5px;"><a href="javascript:void(0);"><?php _e('reset', 'shrsb'); ?></a></div></td>
                                    </tr>
                                    <tr class="topbar_prefs" style="display:none">
                                                <td><label class="tab" for="tb_border_color" style="margin-top:7px;"><?php _e('Bottom border color:', 'shrsb'); ?></label></td>
                                                <td><input style="margin-top:7px;" type="text" id="tb_border_color" name="tb_border_color" value="<?php echo $shrsb_tb_plugopts['tb_border_color']; ?>" /></td>
                                                <td style="padding-bottom: 9px;"><div id="tb_border_color_picker" class ="color_selector">
                                                    <div style="background-color:<?php echo $shrsb_tb_plugopts['tb_border_color']; ?>; "></div>
                                                </div>
                                                </td>
                                                <td><div id="tb_border_color_picker_holder" style="display:none; margin-top: 5px; position: absolute;" ></div></td>
                                                <td> <div id="tb_border_color_reset" style="margin-left: 5px;"><a href="javascript:void(0);"><?php _e('reset', 'shrsb'); ?></a></div></td>
                                    </tr>
                                    
                                    <tr>
                                        <td><span class="shrsb_option"><?php _e('Show Message?', 'shrsb'); ?></span>
                                        </td>
                                        <td style="width:125px"><label><input <?php echo (($shrsb_tb_plugopts['addv'] == "1")? 'checked="checked"' : ""); ?> name="addv" id="addv-yes" type="radio" value="1" /> <?php _e('Yes', 'shrsb'); ?></label>
                                        </td><td><label><input <?php echo (($shrsb_tb_plugopts['addv'] == "0")? 'checked="checked"' : ""); ?> name="addv" id="addv-no" type="radio" value="0" /> <?php _e('No', 'shrsb'); ?></label>
                                        </td>
                                    </tr>
                                    
                                    </tbody></table>
                                    
                                <br />

<!--                                <span style="display:block;"><?php echo sprintf(__('Check out %sour blog%s for additional customization options.', 'shrsb'), '<a target="_blank" href="http://blog.shareaholic.com/?p=1917">', '</a>'); ?></span><br />-->
                        </div>
                    </div>
                </div>

            </li>
            <li>
				<div class="box-mid-head">
					<h2 class="fugue f-globe-plus"><?php _e('Top Bar Sharing Buttons', 'shrsb'); ?></h2>
				</div>
                <div class="box-mid-body" id="toggle2">
					<div class="padding">
						<div id="genopts">

                                   
                                    <table><tbody>

                                    <tr>
                                        <td><span class="shrsb_option"><?php _e('Customize the buttons to be shown in topbar?', 'shrsb'); ?> </span>
                                        </td>
                                        <td style="width:125px"><label><input <?php echo (($shrsb_tb_plugopts['likeButtonSetTop'] == "1")? 'checked="checked"' : ""); ?> name="likeButtonSetTop" id="likeButtonSetTop-yes" type="radio" value="1" /> <?php _e('Yes', 'shrsb'); ?></label>
                                        </td><td><label><input <?php echo (($shrsb_tb_plugopts['likeButtonSetTop'] == "0")? 'checked="checked"' : ""); ?> name="likeButtonSetTop" id="likeButtonSetTop-no" type="radio" value="0" /> <?php _e('No', 'shrsb'); ?></label>
                                        </td>
                                        
                                    </tr>
                                    <?php
                                        shrsb_likeButtonSetHTML($shrsb_tb_plugopts,'Top');
                                    ?>

                                    </tbody></table>

                        </div>
                    </div>
                </div>

            </li>
			<li>
				<div class="box-mid-head">
					<h2 class="fugue f-footer"><?php _e('Top Bar Placement', 'shrsb'); ?></h2>
				</div>
				<div class="box-mid-body" id="toggle5">
					<div class="padding">
												
                        <?php shrsb_options_menu_type($shrsb_tb_plugopts['pageorpost']); ?>
                                                
						<br />
					</div>
				</div>
			</li>
		</ul>
		<div style="clear:both;"></div>
		<input type="hidden" name="save_changes_tb" value="1" />
    <?php wp_nonce_field('save-settings','shareaholic_nonce'); ?>
    <div class="shrsbsubmit"><input type="submit" id="save_changes_tb" value="<?php _e('Save Changes', 'shrsb'); ?>" /></div>
	</form>
	
	<?php echo shrsb_getfooter(); ?>
	
</div>

<?php

//Right Side helpful links
echo shrsb_right_side_menu();
//Snap Engage
echo get_snapengage();

}//closing brace for function "shrsb_settings_page"

?>