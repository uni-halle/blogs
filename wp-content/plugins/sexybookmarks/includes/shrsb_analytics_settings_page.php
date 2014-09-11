<?php

/* 
 * @desc Topbar Settings page
*/

function shrsb_analytics_settings_page() {
	global $shrsb_analytics;
    // Add all the global varaible declarations for the $shrsb_tb_plugopts
	echo '<div class="wrap""><div class="icon32" id="icon-options-general"><br></div><h2>'.__('Social Analytics Settings', 'shrsb').'</h2></div>';

	// processing form submission
	$status_message = "";
	$error_message = "";
	$setting_changed = false;
		
	if(isset($_POST['save_changes_sa']) && check_admin_referer('save-settings','shareaholic_nonce') ) {

    // Set success message
    $status_message = __('Your changes have been saved successfully!', 'shrsb');

    foreach (array(
        'pubGaSocial', 'pubGaKey'
    )as $field) {
        if(isset($_POST[$field])) { // this is to prevent warning if $_POST[$field] is not defined
			$fieldval = $_POST[$field];
			if($field == 'pubGaSocial' && $fieldval != $shrsb_analytics[$field]) {
			  $setting_changed = true;
			}
            $shrsb_analytics[$field] = $fieldval;
        } else {
            $shrsb_analytics[$field] = NULL;
        }
    }

    update_option('ShareaholicAnalytics',$shrsb_analytics);
    
    if ($setting_changed == true){
      shr_sendTrackingEvent('FeatureToggle', array('f_updated' => 'f_analytics', 'enabled' => ($shrsb_analytics['pubGaSocial'] == '1' ? 'true' : 'false')));
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

<form name="shareaholic-analytics" id="shareaholic-analytics" action="" method="post">
    <div id="shrsb-col-left" style="width:100%">
		<ul id="shrsb-sortables">

		<li>
                <div class="box-mid-head">
                    <h2 class="fugue f-status"><?php _e('Shareaholic Social Analytics - Grow Your Traffic and Referrals', 'shrsb'); ?></h2>
                </div>
				<div class="box-mid-body">
                        <div style="padding:8px;background:#FDF6E5;"><img src="<?php echo SHRSB_PLUGPATH; ?>images/chart.png" align="right" alt="New!" />
                                <?php $parse = parse_url(get_bloginfo('url')); ?>

                                  <?php  echo sprintf(__('<span style="font-size: 12px;">Shareaholic reports all of your important social media metrics including popular pages on your website, referral channels, and who are making referrals and spreading your webpages on the internet on your behalf bringing you back more traffic and new visitors for free.</span> <br><br> <b><span style="color:#CC1100;">What are you waiting for?</span> You can access detailed %ssocial engagement analytics%s about your website right now.</b>', 'shrsb'), '<a href="https://shareaholic.com/publishers/analytics/'.$parse['host'].'/">', '</a>');
                                ?>

                        </div>
                </div>
            </li>


       <?php if (current_user_can('manage_options')){ ?>
	
          <li>
            <div class="box-mid-head">
                <h2><img src="<?php echo SHRSB_PLUGPATH; ?>/images/ga-icon.png" style="float:left;margin-top:2px;margin-right:10px;" alt="Google Analytics" /> <?php _e('Google Analytics', 'shrsb'); ?></h2>
            </div>
            <div class="box-mid-body" id="toggle5">

                <div class="padding">
                    <div id="genopts">
                        <table>
                          <tbody>
                                <tr>
                                    <td><span class="shrsb_option"><?php _e('Enable Google Analytics Social Tracking', 'shrsb'); ?> (<a href="http://code.google.com/apis/analytics/docs/tracking/gaTrackingSocial.html" target="_blank">?</a>)</span></td>
                                    <td WIDTH="120"><label><input <?php echo ((@$shrsb_analytics['pubGaSocial'] == "1")? 'checked="checked"' : ""); ?> name="pubGaSocial" id="pubGaSocial-yes" type="radio" value="1" /> <?php _e('Yes', 'shrsb'); ?></label></td>
                                    <td WIDTH="120"><label><input <?php echo ((@$shrsb_analytics['pubGaSocial'] == "0")? 'checked="checked"' : ""); ?> name="pubGaSocial" id="pubGaSocial-no" type="radio" value="0" /> <?php _e('No', 'shrsb'); ?></label></td>
                                </tr>

                                <tr class="pubGaSocial_prefs" style="display:none;">
                                    <td><label class="tab" for="pubGaKey" style="margin-top:7px;"><?php _e('Your Google Analytics Property ID:', 'shrsb'); ?></label></td>
                                    <td colspan="2"><input style="margin-top:7px;" type="text" id="pubGaKey" name="pubGaKey" size="35" placeholder="ex. UA-XXXXXXXX-X" value="<?php echo @$shrsb_analytics['pubGaKey']; ?>" /></td>
                                </tr>
                                
                        </tbody>
                      </table>
                    </div>
                </div>
            </li>

		<?php } ?>
				
		</ul>
		
		<?php if (current_user_can('manage_options')){ ?>
			
			<div style="clear:both;"></div>
			<input type="hidden" name="save_changes_sa" value="1" />
      <?php wp_nonce_field('save-settings','shareaholic_nonce'); ?>
      <div class="shrsbsubmit"><input type="submit" id="save_changes_sa" value="<?php _e('Save Changes', 'shrsb'); ?>" /></div>
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


// Old analytics Page
function shrsb_analytics_page() {
?>
    <h2 class="shrsblogo"><span class="sh-logo"></span></h2>

    <div id="shrsb-col-left" style="width:100%;">

        <ul id="shrsb-sortables">
            <li>
                <div class="box-mid-head">
                    <h2 class="fugue f-status"><?php _e('Shareaholic Analtyics', 'shrsb'); ?></h2>
                </div>
                <div class="box-mid-body">
                      <div class="padding">
                        <div style="position:relative;width:80%;">
                            <p><strong>
                                <?php _e('Shareaholic Analtyics is coming soon!', 'shrsb'); ?>
                                </strong>
                                <br><br>
                                <?php _e('Register your account today to recieve update info via email.', 'shrsb'); ?>
                                <div class="shrsbsubmit">
                                    <input type="button" onclick ="window.open('https://shareaholic.com/publishers_apps/new_publishers_app')" value="<?php _e('Get Share Pro', 'shrsb'); ?>" />
                                </div>
                            </p>
                        </div>
                      </div>
                </div>
            </li>
        </ul>
    </div>
<?php
    }
?>