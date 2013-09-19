<?php 

// Custom fields for WP write panel
// This code is protected under Creative Commons License: http://creativecommons.org/licenses/by-nc-nd/3.0/

$obox_meta = array(
		"thumbnail" => array (
			"name"			=> "video_thumbnail",
			"default" 		=> "",
			"label" 		=> "Video Thumbnail url",
			"desc"      	=> "Add an image for use as a thumbnail for your videos in the Four-Block Widget and Archives",
			"input_type"  	=> "image",
			"input_size"	=> "50",
			"img_width"		=> "300",
			"img_height"	=> "300"
		),
		"media" => array (
			"name"			=> "other_media",
			"default" 		=> "",
			"label" 		=> "Main Image",
			"desc"      	=> "Select a header image to use for your post.",
			"input_type"  	=> "textarea",
			"input_type"  	=> "image",
			"input_size"	=> "50",
			"img_width"		=> "560",
			"img_height"	=> ""
		),
		"video" => array (
			"name"			=> "main_video",
			"default" 		=> "",
			"label" 		=> "Video Object",
			"desc"      	=> "Upload your video with the <img src='images/media-button-video.gif' alt='Add Video' /> button above the WYSIWYG, and paste url here.",
			"input_type"  	=> "textarea"
		)
	);
	
function create_meta_box_ui() {
	global $post, $obox_meta;
		$meta_count = 0;
?>
	<table style="width: 100%;">
<?php
	foreach ($obox_meta as $metabox) :
		$meta_count = ($meta_count + 1);
		$obox_metabox_value = get_post_meta($post->ID,$metabox["name"],true);
		
		if ($obox_metabox_value == "" || !isset($obox_metabox_value)) :
			$obox_metabox_value = $metabox['default'];
		endif; ?>
			<tr>
            	<td width="10%" valign="top"><label for="<?php echo $metabox; ?>"><?php echo $metabox["label"]; ?></label></td>
				<td>
					<?php if($metabox["name"] == "main_video") : ?>
                        <textarea style="width: 70%;" rows="8" name="<?php echo "obox_".$metabox["name"]; ?>" id="'.$metabox.'"><?php echo $obox_metabox_value; ?></textarea>
                    <?php elseif($metabox["input_type"] == "image") : ?>
                    	<div style="float: left;">
                            <input type="text" name="<?php echo "obox_".$metabox["name"]; ?>" id="<?php echo $metabox ?>" value="<?php echo $obox_metabox_value; ?>" size="<?php echo $metabox["input_size"] ?>" />
                            <br /><br />
                            <input type="file" name="<?php echo "obox_".$metabox["name"]."_file"; ?>" />
                        </div>
                    	<div style="float: left; width: 450px; height: 200px; display:block; overflow: hidden;">
                        	<img src="<?php echo $obox_metabox_value; ?>" />
                        </div>
                    <?php elseif($metabox["input_type"] == "textarea") : ?>
                        <textarea style="width: 70%;" rows="3" name="<?php echo "obox_".$metabox["name"]; ?>" id="'.$metabox.'"><?php echo $obox_metabox_value; ?></textarea>
                    <?php else : ?>
                        <input type="text" name="<?php echo "obox_".$metabox["name"]; ?>" id="<?php echo $metabox ?>" value="<?php echo $obox_metabox_value; ?>" size="<?php echo $metabox["input_size"] ?>" />
                    <?php endif; ?>                
                    <p style="font-size: 11px; clear: both;"><?php echo $metabox["desc"] ?></p>
				</td>
			</tr>

<?php endforeach; ?>
    </table>
    <br />    
<?php
}

function insert_obox_metabox($pID) {
	global $obox_meta, $use_file_field, $set_width, $set_height, $image_name;
	foreach ($obox_meta as $metabox) {
		$var = "obox_".$metabox["name"];
		if (isset($_POST[$var])) :
			if($metabox["input_type"] == "image") :
				$use_file_field = $var."_file";
				/* Read File Information and desired proportions */
				$set_width = $metabox["img_width"];
				$set_height = $metabox["img_height"];
				$image_name = strtolower(date("dmy")."_".$_FILES[$use_file_field]["name"]);
				
				/* Check if we've actually selected a file */
				if($_FILES[$use_file_field]["name"] !== "") :
					upload_metaimage($use_file_field, $set_width, $set_height, $image_name);
					/* Set post Meta */
					$meta_name = str_replace("_file", "", $use_file_field);
					$meta_info = get_bloginfo("wpurl")."/wp-content/uploads/".$image_name;
					/* Update Post Meta */
					add_post_meta($pID, $metabox["name"], $meta_info,true) or update_post_meta($pID,  $metabox["name"], $meta_info);
				else :
					/* Update Post Meta */
					add_post_meta($pID,$metabox["name"],$_POST[$var],true) or update_post_meta($pID,$metabox["name"],$_POST[$var]);
				endif;
			else :
				add_post_meta($pID,$metabox["name"],$_POST[$var],true) or update_post_meta($pID,$metabox["name"],$_POST[$var]);
			endif;
		endif;
	}
}

function add_obox_meta_box() {
	if (function_exists('add_meta_box') ) {
		add_meta_box('obox-meta-box',$GLOBALS['themename'].' Options','create_meta_box_ui','post','normal');
	}
}
function upload_metaimage($use_file_field, $set_width, $set_height, $image_name) {
	global $use_file_field, $set_width, $set_height, $upload_image, $new_image, $mime_type, $resize_type, $resize_height, $resize_width, $resize_percent, $image_name;
	$file_count = count($_FILES[$use_file_field]);
		/* Upload original first */
		$final_upload = ABSPATH."wp-content/uploads/".$image_name;
		
		$test = move_uploaded_file($_FILES[$use_file_field]["tmp_name"], $final_upload);
		if($test === true) :
			// Create New Images and Mime Types
			if(strpos($image_name, "gif")) :
				$new_image = imagecreatefromgif($final_upload);
				$mime_type = "gif";
			elseif(strpos($image_name, "jpg") || strpos($image_name, "jpeg")) :	
				$new_image = imagecreatefromjpeg($final_upload);
				$mime_type = "jpg";
			elseif(strpos($image_name, "png")) :
				$new_image = imagecreatefrompng($final_upload);
				$mime_type = "png";
			endif;
			
			// Fetch the Original Image Sizes
			list($width, $height) = getimagesize($final_upload);
			$image_width = $width;
			$image_height = $height;
			
			//Set the resize dimensions
			$resize_type = "w";
			$resize_width = $set_width;
			$resize_height = $set_height;
			$resize_percent = "";
			$upload_image = $final_upload;
			$add_images = ocmx_save_resample($upload_image, $new_image, $mime_type, $resize_type, $resize_height, $resize_width, $resize_percent, $image_width, $image_height);
		endif;
}

function ocmx_change_metatype(){
?>
	<script type="text/javascript">
    /* <![CDATA[ */
        jQuery(document).ready(function(){
            jQuery('form#post').attr('enctype','multipart/form-data');
        });
    /* ]]> */
    </script>
<?php }

add_action('admin_menu', 'add_obox_meta_box');
add_action('admin_head', 'ocmx_change_metatype');
add_action('wp_insert_post', 'insert_obox_metabox'); ?>