<?php

/* 
 * @desc Recommendations Settings page
*/

function shrsb_recommendations_settings_page() {
	global $shrsb_recommendations;
  
    // Add all the global varaible declarations for the $shrsb_recommendations_plugopts
	echo '<div class="wrap""><div class="icon32" id="icon-options-general"><br></div><h2>'.__('Recommendations: Related Content Settings', 'shrsb').'</h2></div>';

	// processing form submission
	$status_message = "";
	$error_message = "";
	$setting_changed = false;
	
	if(isset($_POST['save_changes_rd']) && check_admin_referer('save-settings','shareaholic_nonce')) {

    // Set success message
    $status_message = __('Your changes have been saved successfully!', 'shrsb');
    $_POST['pageorpost'] = shrsb_set_content_type();
    foreach (array(
        'recommendations', 'pageorpost','style'
    )as $field) {
        if(isset($_POST[$field])) { // this is to prevent warning if $_POST[$field] is not defined
			    $fieldval = $_POST[$field];
			    if($field == 'recommendations' && $fieldval != $shrsb_recommendations[$field]) {
			      $setting_changed = true;
			    }
            $shrsb_recommendations[$field] = $fieldval;
        } else {
            $shrsb_recommendations[$field] = NULL;
        }
    }

    update_option('ShareaholicRecommendations',$shrsb_recommendations);
    
    if ($setting_changed == true){
      shr_sendTrackingEvent('FeatureToggle', array('f_updated' => 'f_rec', 'enabled' => ($shrsb_recommendations['recommendations'] == '1' ? 'true' : 'false')));
      shr_recommendationsStatus();
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

<form name="shareaholic-recommendations" id="shareaholic-recommendations" action="" method="post">
    <div id="shrsb-col-left" style="width:100%">
		<ul id="shrsb-sortables">

		   <?php if (current_user_can('manage_options')){ ?>
	
          <li>
            <div class="box-mid-head">
                <h2><img src="<?php echo SHRSB_PLUGPATH; ?>/images/thumbs-icon.png" style="float:left;margin-top:2px;margin-right:10px;" alt="Recommendations" /> <?php _e('Recommendations', 'shrsb'); ?></h2>
            </div>
            <div class="box-mid-body" id="toggle5">

                <div class="padding">
                    <div id="genopts">
                        <table><tbody>
                                <tr class="alert-success">
                                    <td><span class="shrsb_option"><?php _e('Enable Recommendations', 'shrsb'); ?> </span></td>
                                    <td WIDTH="120"><label><input <?php echo ((@$shrsb_recommendations['recommendations'] == "1")? 'checked="checked"' : ""); ?> name="recommendations" id="recommendations-yes" type="radio" value="1" /> <?php _e('Yes', 'shrsb'); ?></label></td>
                                    <td WIDTH="120"><label><input <?php echo ((@$shrsb_recommendations['recommendations'] == "0")? 'checked="checked"' : ""); ?> name="recommendations" id="recommendations-no" type="radio" value="0" /> <?php _e('No', 'shrsb'); ?></label></td>                                    
                                </tr>
																
																<tr class="recommendations_prefs-1" style="display:none">
                                            <td><label class="tab" for="style" style="margin-top:7px;"><?php _e('Display thumbnails for each recommendation? </br>(If most posts on your blog don\'t include images, you should set this to \'No\'):', 'shrsb'); ?></label></td>
                                            <td WIDTH="120"><label><input <?php echo ((@$shrsb_recommendations['style'] == "image")? 'checked="checked"' : ""); ?> name="style" id="recommendations-style-image" type="radio" value="image" /> <?php _e('Yes', 'shrsb'); ?></label></td>
                                            <td WIDTH="120"><label><input <?php echo ((@$shrsb_recommendations['style'] == "text")? 'checked="checked"' : ""); ?> name="style" id="recommendations-style-text" type="radio" value="text" /> <?php _e('No', 'shrsb'); ?></label></td>
<!--                                            <td colspan="2"><input style="margin-top:7px;" type="text" id="num" name="num" size="35" placeholder="ex. UA-XXXXXXXX-X" value="<?php echo @$shrsb_recommendations['style']; ?>" /></td>-->

                                </tr>
                                
																<tr>
                                	<td colspan="3"><br />
                                	  <p>Once enabled, we will analyze your content and begin generating recommended posts to display. This may take up to several hours if you are a new Shareaholic user and depending on the number of posts on your blog. The quality of recommended stories will improve once we complete our crawl of your website. <a href="http://support.shareaholic.com/forums/21886992-Recommendations-Related-Content" target="_new">Learn more.</a></p><p><span class="label label-info">Tip</span> we recommend using Shareaholic sharing tools as they help boost the quality of your recommendations.</p>
                                	<p>
                                	  <strong>Data Status:</strong> 
                                	  <?php               	  
                                	    $status = shr_recommendationsStatus_code();
                                	    if ($status == "processing" || $status == 'unknown'){
                                	      echo '<img class="shrsb_health_icon"  align="top" src="'.SHRSB_PLUGPATH.'/images/circle_yellow.png" /> Processing';
                                	    } else {
                                	      echo '<img class="shrsb_health_icon"  align="top" src="'.SHRSB_PLUGPATH.'/images/circle_green.png" /> Ready';
                                	    }
                                	  ?>
                                	</p></td>
																</tr>
                        </tbody></table>
                    </div>
                </div>
            </li>
            <li>
                <div class="box-mid-head">
                  <h2 class="fugue f-footer"><?php _e('Recommendations Placement', 'shrsb'); ?></h2>
                </div>
                <div class="box-mid-body" id="toggle5">
                  <div class="padding">

                    <?php shrsb_options_menu_type(@$shrsb_recommendations['pageorpost']); ?>

                    <br />
                  </div>
              </div>
            </li>

		<?php } ?>
				
		</ul>

		<?php if (current_user_can('manage_options')){ ?>
			
			<div style="clear:both;"></div>
			<input type="hidden" name="save_changes_rd" value="1" />
			<?php wp_nonce_field('save-settings','shareaholic_nonce'); ?>
      <div class="shrsbsubmit"><input type="submit" id="save_changes_rd" value="<?php _e('Save Changes', 'shrsb'); ?>" /></div>
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