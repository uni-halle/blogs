<?php if(preg_match('#' . basename(__FILE__) . '#', $_SERVER['PHP_SELF'])) { die('You are not allowed to call this page directly.'); } ?>
<?php if ( ! empty( $_POST ) && ! wp_verify_nonce( $_REQUEST['wp_create_nonce'], 'content-management-add-nonce' ) )  { die('<p>Security check failed.</p>'); } ?>
<div class="wrap">
<?php
$pop_errors = array();
$pop_success = '';
$pop_error_found = FALSE;

// Preset the form fields
$form = array(
	'pop_width' => '',
	'pop_height' => '',
	'pop_headercolor' => '',
	'pop_bordercolor' => '',
	'pop_header_fontcolor' => '',
	'pop_title' => '',
	'pop_content' => '',
	'pop_caption' => '',
	'pop_id' => ''
);

// Form submitted, check the data
if (isset($_POST['pop_form_submit']) && $_POST['pop_form_submit'] == 'yes')
{
	//	Just security thingy that wordpress offers us
	check_admin_referer('pop_form_add');
	
	$form['pop_width'] = isset($_POST['pop_width']) ? $_POST['pop_width'] : '';
	if ($form['pop_width'] == '')
	{
		$pop_errors[] = __('Please enter the popup window width, only number.', 'anything-popup');
		$pop_error_found = TRUE;
	}

	$form['pop_height'] = isset($_POST['pop_height']) ? $_POST['pop_height'] : '';
	if ($form['pop_height'] == '')
	{
		$pop_errors[] = __('Please enter the popup window height, only number.', 'anything-popup');
		$pop_error_found = TRUE;
	}
	
	$form['pop_headercolor'] = isset($_POST['pop_headercolor']) ? $_POST['pop_headercolor'] : '';
	if ($form['pop_headercolor'] == '')
	{
		$pop_errors[] = __('Please enter the header color.', 'anything-popup');
		$pop_error_found = TRUE;
	}
	
	$form['pop_bordercolor'] = isset($_POST['pop_bordercolor']) ? $_POST['pop_bordercolor'] : '';
	if ($form['pop_headercolor'] == '')
	{
		$pop_errors[] = __('Please enter the border color.', 'anything-popup');
		$pop_error_found = TRUE;
	}
	
	$form['pop_header_fontcolor'] = isset($_POST['pop_header_fontcolor']) ? $_POST['pop_header_fontcolor'] : '';
	if ($form['pop_header_fontcolor'] == '')
	{
		$pop_errors[] = __('Please enter the heder font color.', 'anything-popup');
		$pop_error_found = TRUE;
	}
	
	$form['pop_title'] = isset($_POST['pop_title']) ? $_POST['pop_title'] : '';
	if ($form['pop_title'] == '')
	{
		$pop_errors[] = __('Please enter the popup title.', 'anything-popup');
		$pop_error_found = TRUE;
	}
	
	$form['pop_content'] = isset($_POST['pop_content']) ? $_POST['pop_content'] : '';
	if ($form['pop_content'] == '')
	{
		$pop_errors[] = __('Please enter the popup link text/image.', 'anything-popup');
		$pop_error_found = TRUE;
	}
	
	$form['pop_caption'] = isset($_POST['pop_caption']) ? $_POST['pop_caption'] : '';
	if ($form['pop_caption'] == '')
	{
		$pop_errors[] = __('Please enter the popup content.', 'anything-popup');
		$pop_error_found = TRUE;
	}
	

	//	No errors found, we can add this Group to the table
	if ($pop_error_found == FALSE)
	{
		$sql = $wpdb->prepare(
			"INSERT INTO `".AnythingPopupTable."`
			(`pop_width`, `pop_height`, `pop_headercolor`, `pop_bordercolor`, `pop_header_fontcolor`, `pop_title`, `pop_content`, `pop_caption`)
			VALUES(%s, %s, %s, %s, %s, %s, %s, %s)",
			array($form['pop_width'], $form['pop_height'], $form['pop_headercolor'], $form['pop_bordercolor'], $form['pop_header_fontcolor'], 
					$form['pop_title'], $form['pop_content'], $form['pop_caption'])
		);
		$wpdb->query($sql);
		
		$pop_success = __('New details was successfully added.', 'anything-popup');
		
		// Reset the form fields
		$form = array(
			'pop_width' => '',
			'pop_height' => '',
			'pop_headercolor' => '',
			'pop_bordercolor' => '',
			'pop_header_fontcolor' => '',
			'pop_title' => '',
			'pop_content' => '',
			'pop_caption' => '',
			'pop_id' => ''
		);
	}
}

if ($pop_error_found == TRUE && isset($pop_errors[0]) == TRUE)
{
	?>
	<div class="error fade">
		<p><strong><?php echo $pop_errors[0]; ?></strong></p>
	</div>
	<?php
}
if ($pop_error_found == FALSE && strlen($pop_success) > 0)
{
	?>
	  <div class="updated fade">
		<p><strong><?php echo $pop_success; ?> 
		<a href="<?php echo ANYTHGPOPUP_ADMIN_URL; ?>"><?php _e('Click here to view the details', 'anything-popup'); ?></a></strong></p>
	  </div>
	  <?php
	}
?>
<script language="JavaScript" src="<?php echo ANYTHGPOPUP_PLUGIN_URL; ?>/pages/setting.js"></script>
<script language="JavaScript" src="<?php echo ANYTHGPOPUP_PLUGIN_URL; ?>/pages/color/jscolor.js"></script>
<div class="form-wrap">
	<div id="icon-edit" class="icon32 icon32-posts-post"><br></div>
	<h2><?php _e('Anything Popup', 'anything-popup'); ?></h2>
	<form name="pop_form" method="post" action="#" onsubmit="return _pop_submit()"  >
      <h3><?php _e('Add details', 'anything-popup'); ?></h3>
      
		<label for="tag-a"><?php _e('Window width', 'anything-popup'); ?></label>
		<input name="pop_width" type="text" id="pop_width" value="" size="20" maxlength="3" />
		<p><?php _e('Enter your popup window width.', 'anything-popup'); ?> (Ex: 300)</p>
		
		<label for="tag-a"><?php _e('Window height', 'anything-popup'); ?></label>
		<input name="pop_height" type="text" id="pop_height" value="" size="20" maxlength="3" />
		<p><?php _e('Enter your popup window height.', 'anything-popup'); ?> (Ex: 250)</p>
		
		<label for="tag-a"><?php _e('Header color', 'anything-popup'); ?></label>
		<input class="color" type="text" name="pop_headercolor" id="pop_headercolor" value="#4D4D4D" maxlength="7" />
		<p><?php _e('Select your popup window header bg color.', 'anything-popup'); ?> (Ex: #4D4D4D)</p>
	  
	  	<label for="tag-a"><?php _e('Border color', 'anything-popup'); ?></label>
		<input class="color" type="text" name="pop_bordercolor" id="pop_bordercolor" value="#4D4D4D" maxlength="7" />
		<p><?php _e('Select your popup window border color.', 'anything-popup'); ?> (Ex: #4D4D4D)</p>
		
		<label for="tag-a"><?php _e('Header font color', 'anything-popup'); ?></label>
		<input class="color" type="text" name="pop_header_fontcolor" id="pop_header_fontcolor" value="" maxlength="7" />
		<p><?php _e('Select your popup window title font color.', 'anything-popup'); ?> (Ex: #FFFFFF)</p>
		
		<label for="tag-a"><?php _e('Popup title', 'anything-popup'); ?></label>
		<input name="pop_title" type="text" id="pop_title" value="" size="50" maxlength="500" />
		<p><?php _e('Enter your popup window title.', 'anything-popup'); ?></p>
		
		<label for="tag-a"><?php _e('Popup Link Text / Label / Image', 'anything-popup'); ?></label>
		<input name="pop_caption" type="text" id="pop_caption" value="" size="100" maxlength="200" />
		<p><?php _e('Enter your popup button.', 'anything-popup'); ?></p>
		
		<label for="tag-a"><?php _e('Popup content', 'anything-popup'); ?></label>
		<?php wp_editor("", "pop_content");?>
		<p><?php _e('Enter your popup content.', 'anything-popup'); ?></p>
	  
      <input name="pop_id" id="pop_id" type="hidden" value="">
      <input type="hidden" name="pop_form_submit" value="yes"/>
      <p class="submit">
        <input name="publish" lang="publish" class="button add-new-h2" value="<?php _e('Insert Details', 'anything-popup'); ?>" type="submit" />
        <input name="publish" lang="publish" class="button add-new-h2" onclick="_pop_redirect()" value="<?php _e('Cancel', 'anything-popup'); ?>" type="button" />
        <input name="Help" lang="publish" class="button add-new-h2" onclick="_pop_help()" value="<?php _e('Help', 'anything-popup'); ?>" type="button" />
      </p>
	  <?php wp_nonce_field('pop_form_add'); ?>
	  <input type="hidden" name="wp_create_nonce" id="wp_create_nonce" value="<?php echo wp_create_nonce( 'content-management-add-nonce' ); ?>"/>
    </form>
</div>
<p class="description">
	<?php _e('Check official website for more information', 'anything-popup'); ?>
	<a target="_blank" href="<?php echo AnythingPopup_FAV; ?>"><?php _e('click here', 'anything-popup'); ?></a><br />
</p>
</div>