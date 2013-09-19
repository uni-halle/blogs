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
		$pop_errors[] = __('Please enter the popup window width, only number.', AnythingPopup_UNIQUE_NAME);
		$pop_error_found = TRUE;
	}

	$form['pop_height'] = isset($_POST['pop_height']) ? $_POST['pop_height'] : '';
	if ($form['pop_height'] == '')
	{
		$pop_errors[] = __('Please enter the popup window height, only number.', AnythingPopup_UNIQUE_NAME);
		$pop_error_found = TRUE;
	}
	
	$form['pop_headercolor'] = isset($_POST['pop_headercolor']) ? $_POST['pop_headercolor'] : '';
	if ($form['pop_headercolor'] == '')
	{
		$pop_errors[] = __('Please enter the header color.', AnythingPopup_UNIQUE_NAME);
		$pop_error_found = TRUE;
	}
	
	$form['pop_bordercolor'] = isset($_POST['pop_bordercolor']) ? $_POST['pop_bordercolor'] : '';
	if ($form['pop_headercolor'] == '')
	{
		$pop_errors[] = __('Please enter the border color.', AnythingPopup_UNIQUE_NAME);
		$pop_error_found = TRUE;
	}
	
	$form['pop_header_fontcolor'] = isset($_POST['pop_header_fontcolor']) ? $_POST['pop_header_fontcolor'] : '';
	if ($form['pop_header_fontcolor'] == '')
	{
		$pop_errors[] = __('Please enter the heder font color.', AnythingPopup_UNIQUE_NAME);
		$pop_error_found = TRUE;
	}
	
	$form['pop_title'] = isset($_POST['pop_title']) ? $_POST['pop_title'] : '';
	if ($form['pop_title'] == '')
	{
		$pop_errors[] = __('Please enter the popup title.', AnythingPopup_UNIQUE_NAME);
		$pop_error_found = TRUE;
	}
	
	$form['pop_content'] = isset($_POST['pop_content']) ? $_POST['pop_content'] : '';
	if ($form['pop_content'] == '')
	{
		$pop_errors[] = __('Please enter the popup link text/image.', AnythingPopup_UNIQUE_NAME);
		$pop_error_found = TRUE;
	}
	
	$form['pop_caption'] = isset($_POST['pop_caption']) ? $_POST['pop_caption'] : '';
	if ($form['pop_caption'] == '')
	{
		$pop_errors[] = __('Please enter the popup content.', AnythingPopup_UNIQUE_NAME);
		$pop_error_found = TRUE;
	}
	

	//	No errors found, we can add this Group to the table
	if ($pop_error_found == FALSE)
	{
		$sql = $wpdb->prepare(
			"INSERT INTO `".AnythingPopupTable."`
			(`pop_width`, `pop_height`, `pop_headercolor`, `pop_bordercolor`, `pop_header_fontcolor`, `pop_title`, `pop_content`, `pop_caption`)
			VALUES(%s, %s, %s, %s, %s, %s, %s, %s)",
			array($form['pop_width'], $form['pop_height'], $form['pop_headercolor'], $form['pop_bordercolor'], $form['pop_header_fontcolor'], $form['pop_title'], $form['pop_content'], $form['pop_caption'])
		);
		$wpdb->query($sql);
		
		$pop_success = __('New details was successfully added.', AnythingPopup_UNIQUE_NAME);
		
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
		<p><strong><?php echo $pop_success; ?> <a href="<?php echo get_option('siteurl'); ?>/wp-admin/options-general.php?page=anything-popup">Click here</a> to view the details</strong></p>
	  </div>
	  <?php
	}
?>
<script language="JavaScript" src="<?php echo get_option('siteurl'); ?>/wp-content/plugins/anything-popup/pages/setting.js"></script>
<div class="form-wrap">
	<div id="icon-edit" class="icon32 icon32-posts-post"><br></div>
	<h2><?php echo AnythingPopup_TITLE; ?></h2>
	<form name="pop_form" method="post" action="#" onsubmit="return _pop_submit()"  >
      <h3>Add details</h3>
      
		<label for="tag-a">Window width</label>
		<input name="pop_width" type="text" id="pop_width" value="" size="20" maxlength="3" />
		<p>Enter your popup window width. (Ex: 300)</p>
		
		<label for="tag-a">Window height</label>
		<input name="pop_height" type="text" id="pop_height" value="" size="20" maxlength="3" />
		<p>Enter your popup window height. (Ex: 250)</p>
		
		<label for="tag-a">Header color</label>
		<input name="pop_headercolor" type="text" id="pop_headercolor" value="" size="20" maxlength="7" />
		<p>Enter your popup window header bg color. (Ex: #4D4D4D)</p>
	  
	  	<label for="tag-a">Border color</label>
		<input name="pop_bordercolor" type="text" id="pop_bordercolor" value="" size="20" maxlength="7" />
		<p>Enter your popup window border color. (Ex: #4D4D4D)</p>
		
		<label for="tag-a">Header font color</label>
		<input name="pop_header_fontcolor" type="text" id="pop_header_fontcolor" value="" maxlength="7" />
		<p>Enter your popup window title font color. (Ex: #FFFFFF)</p>
		
		<label for="tag-a">Popup title</label>
		<input name="pop_title" type="text" id="pop_title" value="" size="50" maxlength="500" />
		<p>Enter your popup window title.</p>
		
		<label for="tag-a">Popup Link Text / Label / Image</label>
		<input name="pop_caption" type="text" id="pop_caption" value="" size="150" maxlength="200" />
		<p>Enter your popup button.</p>
		
		<label for="tag-a">Popup content</label>
		<?php wp_editor("", "pop_content");?>
		<p>Enter your popup content.</p>
	  
      <input name="pop_id" id="pop_id" type="hidden" value="">
      <input type="hidden" name="pop_form_submit" value="yes"/>
      <p class="submit">
        <input name="publish" lang="publish" class="button add-new-h2" value="Insert Details" type="submit" />
        <input name="publish" lang="publish" class="button add-new-h2" onclick="_pop_redirect()" value="Cancel" type="button" />
        <input name="Help" lang="publish" class="button add-new-h2" onclick="_pop_help()" value="Help" type="button" />
      </p>
	  <?php wp_nonce_field('pop_form_add'); ?>
    </form>
</div>
<p class="description"><?php echo AnythingPopup_LINK; ?></p>
</div>