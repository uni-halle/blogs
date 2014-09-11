<?php

/* 
 * @desc Classic Bookmarks Settings page
*/

function shrsb_cb_settings_page() {
	global $shrsb_cb;
  
    // Add all the global varaible declarations for the $shrsb_cb_plugopts
	echo '<div class="wrap""><div class="icon32" id="icon-options-general"><br></div><h2>'.__('Share Buttons: ClassicBookmarks Settings', 'shrsb').'</h2></div>';

	// processing form submission
	$status_message = "";
	$error_message = "";
	$setting_changed = false;
	
	if(isset($_POST['save_changes_cb']) && check_admin_referer('save-settings','shareaholic_nonce')) {

    // Set success message
    $status_message = __('Your changes have been saved successfully!', 'shrsb');
    $_POST['pageorpost'] = shrsb_set_content_type();
    foreach (array(
        'cb', 'size', 'pageorpost'
    )as $field) {
        if(isset($_POST[$field])) { // this is to prevent warning if $_POST[$field] is not defined
			$fieldval = $_POST[$field];
			if($field == 'cb' && $fieldval != $shrsb_cb[$field]) {
			  $setting_changed = true;
			}
            $shrsb_cb[$field] = $fieldval;
        } else {
            $shrsb_cb[$field] = NULL;
        }
    }

    update_option('ShareaholicClassicBookmarks',$shrsb_cb);
    
    if ($setting_changed == true){
      shr_sendTrackingEvent('FeatureToggle', array('f_updated' => 'f_classic', 'enabled' => ($shrsb_cb['cb'] == '1' ? 'true' : 'false')));
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
?>

<form name="shareaholic-classicbookmarks" id="shareaholic-classicbookmarks" action="" method="post">
    <div id="shrsb-col-left" style="width:100%">
		<ul id="shrsb-sortables">

		   <?php if (current_user_can('manage_options')){ ?>
	
          <li>
            <div class="box-mid-head">
							<h2><img src="<?php echo SHRSB_PLUGPATH; ?>/images/thumbs-icon.png" style="float:left;margin-top:2px;margin-right:10px;" alt="cb" /> <?php _e('Classic Bookmarks', 'shrsb'); ?></h2>
            </div>

            <div class="box-mid-body" id="toggle5">
                <div class="padding">
                    <div id="genopts">
                        <table><tbody>
													<tr class="alert-success">
														<td><span class="shrsb_option"><?php _e('Enable Classic Bookmarks', 'shrsb'); ?> </span></td>
                            <td WIDTH="120"><label><input <?php echo ((@$shrsb_cb['cb'] == "1")? 'checked="checked"' : ""); ?> name="cb" id="cb-yes" type="radio" value="1" /> <?php _e('Yes', 'shrsb'); ?></label></td>
                             <td WIDTH="120"><label><input <?php echo ((@$shrsb_cb['cb'] == "0")? 'checked="checked"' : ""); ?> name="cb" id="cb-no" type="radio" value="0" /> <?php _e('No', 'shrsb'); ?></label></td>                                    
                           </tr>
                           <tr class="cb_prefs" style="display:none">
														<td><label class="tab" for="num" style="margin-top:7px;"><?php _e('Size :', 'shrsb'); ?></label></td>
														<td WIDTH="300"><label><input <?php echo ((@$shrsb_cb['size'] == "16")? 'checked="checked"' : ""); ?> name="size" id="cb-yes" type="radio" value="16" /><img src="<?php echo SHRSB_PLUGPATH; ?>/images/classicbookmark_16x16.png" alt="cb16x16" /></label>
														<br/>
                            <label><input <?php echo ((@$shrsb_cb['size'] == "32")? 'checked="checked"' : ""); ?> name="size" id="cb-no" type="radio" value="32" /><img src="<?php echo SHRSB_PLUGPATH; ?>/images/classicbookmark_32x32.png" alt="cb32X32" /></label></td>
														<td></td>
                           </tr>
                        </tbody></table>
                    </div>
                </div>
            </li>
            <li>
							<div class="box-mid-head">
								<h2 class="fugue f-footer"><?php _e('Classic Bookmarks Placement', 'shrsb'); ?></h2>
              </div>
              <div class="box-mid-body" id="toggle5">
              	<div class="padding">
									<?php shrsb_options_menu_type(@$shrsb_cb['pageorpost']); ?><br />
                </div>
              </div>
            </li>
		<?php } ?>
		</ul>
		
		<?php if (current_user_can('manage_options')){ ?>
			
		<div style="clear:both;"></div>
		<input type="hidden" name="save_changes_cb" value="1" />
    <?php wp_nonce_field('save-settings','shareaholic_nonce'); ?>
    <div class="shrsbsubmit"><input type="submit" id="save_changes_cb" value="<?php _e('Save Changes', 'shrsb'); ?>" /></div>
		</form>
		
	<?php } ?>	

	<?php echo shrsb_getfooter(); ?>
	
</div>

<?php

//Right Side helpful links
echo shrsb_right_side_menu();
//Snap Engage
echo get_snapengage();

}//closing brace for function "shrsb_settings_page"

?>