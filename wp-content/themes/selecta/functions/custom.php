<?php 

// Custom fields for WP write panel
// This code is protected under Creative Commons License: http://creativecommons.org/licenses/by-nc-nd/3.0/

$obox_metaboxes = array(
		"media" => array (
			"name"			=> "video_thumbnail",
			"default" 		=> "",
			"label" 		=> "Video Thumbnail url",
			"desc"      	=> "Add an image using the  <img src='images/media-button-image.gif' alt='Add Button' /> button above the WYSIWYG, and paste url here.",
			"input_type"  	=> "textarea"
		),
		"video" => array (
			"name"			=> "main_video",
			"default" 		=> "",
			"label" 		=> "Video Object",
			"desc"      	=> "Upload your video with the <img src='images/media-button-video.gif' alt='Add Video' /> button above the WYSIWYG, and paste url here.",
			"input_type"  	=> "textarea"
		),
		"media" => array (
			"name"			=> "other_media",
			"default" 		=> "",
			"label" 		=> "Image url",
			"desc"      	=> "Add an image using the  <img src='images/media-button-image.gif' alt='Add Button' /> button above the WYSIWYG, and paste url here.",
			"input_type"  	=> "textarea"
		)
	);
	
function create_meta_box_ui() {
	global $post, $obox_metaboxes;
	$meta_count = 0;
	foreach ($obox_metaboxes as $obox_custom_metabox) {
		$meta_count = ($meta_count + 1);
		$obox_metabox_value = get_post_meta($post->ID,$obox_custom_metabox["name"],true);
		
		if ($obox_metabox_value == "" || !isset($obox_metabox_value)) {
			$obox_metabox_value = $obox_custom_metabox['default'];
		}
		if($meta_count > 1) :
?>
			<br />
<?php 	endif ?>
			
		<table style="width: 100%;">
			<tr>
				<th style="text-align: left; vertical-align: top; padding-top: 5px; width: 100%;"><label for="<?php echo $obox_custom_metabox; ?>"><?php echo $obox_custom_metabox["label"]; ?></label></th>
			</tr>
			<tr>
            	<?php if($obox_custom_metabox["name"] == "main_video") : ?>
					<td><textarea style="width: 100%;" rows="8" name="<?php echo "obox_".$obox_custom_metabox["name"]; ?>" id="'.$obox_custom_metabox.'"><?php echo $obox_metabox_value; ?></textarea></td>
				<?php elseif($obox_custom_metabox["input_type"] == "textarea") : ?>
					<td><textarea style="width: 100%;" rows="4" name="<?php echo "obox_".$obox_custom_metabox["name"]; ?>" id="'.$obox_custom_metabox.'"><?php echo $obox_metabox_value; ?></textarea></td>
				<?php else : ?>
    	        	<td><input type="text" name="<?php echo "obox_".$obox_custom_metabox["name"]; ?>" id="<?php echo $obox_custom_metabox ?>" value="<?php echo $obox_metabox_value; ?>" size="<?php echo $obox_custom_metabox["input_size"] ?>" /></td>
				<?php endif; ?>
			</tr>
			<tr><td style=" text-align: left;"><p style="font-size: 11px;"><?php echo $obox_custom_metabox["desc"] ?></p></td></tr>
		</table>
<?php
	}
}

function insert_obox_metabox($pID) {
	global $obox_metaboxes;
	
	foreach ($obox_metaboxes as $obox_custom_metabox) {
		$var = "obox_".$obox_custom_metabox["name"];
		if (isset($_POST[$var])) {
			add_post_meta($pID,$obox_custom_metabox["name"],$_POST[$var],true) or update_post_meta($pID,$obox_custom_metabox["name"],$_POST[$var]);
		}
	}
}

function add_obox_meta_box() {
	if ( function_exists('add_meta_box') ) {
		add_meta_box('obox-meta-box',$GLOBALS['themename'].' Options','create_meta_box_ui','post','normal');
	}
}

add_action('admin_menu', 'add_obox_meta_box');
add_action('wp_insert_post', 'insert_obox_metabox');

?>