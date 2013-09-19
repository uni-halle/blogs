<?php
$wpconfig = realpath("../../../../wp-config.php");
if (!file_exists($wpconfig))  {
	echo "Could not found wp-config.php. Error in path :\n\n".$wpconfig ;	
	die;	
}
require_once($wpconfig);
require_once(ABSPATH.'/wp-admin/admin.php');
global $wpdb;
?>

<html xmlns="http://www.w3.org/1999/xhtml">
<head>
	<title>Insert Prezi presentation</title>

	<script language="javascript" type="text/javascript" src="<?php echo get_option('siteurl') ?>/wp-includes/js/tinymce/tiny_mce_popup.js"></script>
	<script language="javascript" type="text/javascript" src="<?php echo get_option('siteurl') ?>/wp-includes/js/tinymce/utils/form_utils.js"></script>
	
	<script>
	
	function init() {
		tinyMCEPopup.resizeToInnerSize();
	}

	function getCheckedValue(radioObj) {
		if(!radioObj)
			return "";
		var radioLength = radioObj.length;
		if(radioLength == undefined)
			if(radioObj.checked)
				return radioObj.value;
			else
				return "";
		for(var i = 0; i < radioLength; i++) {
			if(radioObj[i].checked) {
				return radioObj[i].value;
			}
		}
		return "";
	}

	function insertPrezicode() {

		var tagtext;
		
		var customSize = document.getElementById('rebelicPreziCustomSize').checked;
		var url = document.getElementById('rebelicPreziUrl').value;
		var width = document.getElementById('rebelicPreziWidth').value;
		var height = document.getElementById('rebelicPreziHeight').value;
		
		if (customSize)
			tagtext = "[prezi width='" + width + "' height='" + height  + "'";
		else
			tagtext = "[prezi";
		
		window.tinyMCE.execInstanceCommand('content', 'mceInsertContent', false, tagtext+']' + url + '[/prezi]');
		tinyMCEPopup.editor.execCommand('mceRepaint');
		tinyMCEPopup.close();
		return;
	}
			
	</script>
	
	<base target="_self" />
</head>
		<body id="link" onload="tinyMCEPopup.executeOnLoad('init();');document.body.style.display='';" style="display: none">
<!-- <form onsubmit="insertLink();return false;" action="#"> -->
	<form name="rebelicPrezi" action="#">
		<table border="0" cellpadding="4" cellspacing="0">
         <tr>
			<td nowrap="nowrap"><label for="rebelicPreziUrl"><?php _e("Prezi URL", 'rebelic_prezi'); ?></label></td>
			<td>
				<input type="text" name="rebelicPreziUrl" id="rebelicPreziUrl" />
			</td>
          </tr>
          <tr>
			<td nowrap="nowrap" valign="top"><label for="rebelicPreziCustomSize"><?php _e("Use custom size", 'rebelic_prezi'); ?></label></td>
            <td><label><input name="rebelicPreziCustomSize" id='rebelicPreziCustomSize' type="checkbox" /></label></td>
          </tr>
          <tr>
			<td nowrap="nowrap" valign="top"><label for="rebelicPreziWidth"><?php _e("width", 'rebelic_prezi'); ?></label></td>
            <td>
            	<input type="text" name="rebelicPreziWidth" id="rebelicPreziWidth" />
         	</td>
        </tr>
        <tr>
			<td nowrap="nowrap" valign="top"><label for="rebelicPreziHeight"><?php _e("height", 'rebelic_prezi'); ?></label></td>
            <td>
            	<input type="text" name="rebelicPreziHeight" id="rebelicPreziHeight" />	
			</td>
          </tr>
        </table>
	<div class="mceActionPanel">
		<div style="float: left">
			    <input type="button" id="cancel" name="cancel" value="<?php _e("Cancel", 'cctb_main'); ?>" onclick="tinyMCEPopup.close();" />
		</div>

		<div style="float: right">
				<input type="submit" id="insert" name="insert" value="<?php _e("Insert", 'cctb_main'); ?>" onclick="insertPrezicode();" />
		</div>
	</div>
</form>
</body>
</html>