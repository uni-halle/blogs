<?php


/**
 * @param $file A $_FILES item
 */
function awpcp_upload_image_file($directory, $filename, $tmpname, $min_size, $max_size, $min_width, $min_height, $uploaded=true) {
	$filename = sanitize_file_name($filename);
	$newname = wp_unique_filename($directory, $filename);
	$newpath = trailingslashit($directory) . $newname;

	if ( !file_exists( $tmpname ) ) {
		return sprintf( __( 'The specified image file does not exists: %s.', 'AWPCP' ), $filename );
	}

	$ext = strtolower(pathinfo($filename, PATHINFO_EXTENSION));
	$imginfo = getimagesize($tmpname);
	$size = filesize($tmpname);

	$allowed_extensions = array('gif', 'jpg', 'jpeg', 'png');

	if (empty($filename)) {
		return __('No file was selected.', 'AWPCP');
	}

	if ($uploaded && !is_uploaded_file($tmpname)) {
		return __('Unknown error encountered while uploading the image.', 'AWPCP');
	}

	if (empty($size) || $size <= 0) {
		$message = "There was an error trying to find out the file size of the image %s.";
		return __(sprintf($message, $filename), 'AWPCP');
	}

	if (!(in_array($ext, $allowed_extensions))) {
		return __('The file has an invalid extension and was rejected.', 'AWPCP');

	} elseif ($size < $min_size) {
		$message = __('The size of %1$s was too small. The file was not uploaded. File size must be greater than %2$d bytes.', 'AWPCP');
		return sprintf($message, $filename, $min_size);

	} elseif ($size > $max_size) {
		$message = __('The file %s was larger than the maximum allowed file size of %s bytes. The file was not uploaded.', 'AWPCP');
		return sprintf($message, $filename, $max_size);

	} elseif (!isset($imginfo[0]) && !isset($imginfo[1])) {
		return __('The file does not appear to be a valid image file.', 'AWPCP');

	} elseif ($imginfo[0] < $min_height) {
		$message = __('The image did not meet the minimum width of %s pixels. The file was not uploaded.', 'AWPCP');
		return sprintf($message, $min_width);

	} elseif ($imginfo[1] < $min_height) {
		$message = __('The image did not meet the minimum height of %s pixels. The file was not uploaded.', 'AWPCP');
		return sprintf($message, $min_width);
	}

	if ($uploaded && !@move_uploaded_file($tmpname, $newpath)) {
		$message = __('The file %s could not be moved to the destination directory.', 'AWPCP');
		return sprintf($message, $filename);

	} else if (!$uploaded && !@copy($tmpname, $newpath)) {
		$message = __('The file %s could not be moved to the destination directory.', 'AWPCP');
		return sprintf($message, $filename);
	}

	if (!awpcp_create_image_versions($newname, $directory)) {
		$message = __('Could not create resized versions of image %s.', 'AWPCP');
		# TODO: unlink resized version, thumbnail and primary image
		@unlink($newpath);
		return sprintf($message, $filename);
	}

	@chmod($newpath, 0644);

	return array('original' => $filename, 'filename' => $newname);
}


/**
 * Used in the admin panels to add images to existing ads
 */
function admin_handleimagesupload($adid) {
	global $wpdb, $wpcontentdir, $awpcp_plugin_path;

	list($images_dir, $thumbs_dir) = awpcp_setup_uploads_dir();
	list($min_width, $min_height, $min_size, $max_size) = awpcp_get_image_constraints();

	$ad = AWPCP_Ad::find_by_id($adid);
	if (!is_null($ad)) {

		list($images_allowed, $images_uploaded, $images_left) = awpcp_get_ad_images_information($adid);

		if ($images_left > 0) {
			$filename = awpcp_array_data('name', '', $_FILES['awpcp_add_file']);
			$tmpname = awpcp_array_data('tmp_name', '', $_FILES['awpcp_add_file']);
			$result = awpcp_upload_image_file($images_dir, $filename, $tmpname,
											  $min_size, $max_size, $min_width, $min_height);
		} else {
			$message = __('No more images can be added to this Ad. The Ad already have %d of %d images allowed.', 'AWPCP');
			$result = sprintf($message, $images_uploaded, $images_allowed);
		}
	} else {
		$result = __("The Ad doesn't exists. All uploaded files were rejected.", 'AWPCP');
	}

	if (is_array($result) && isset($result['filename'])) {
		// TODO: consider images approve settings
		$sql = 'insert into ' . AWPCP_TABLE_ADPHOTOS . " set image_name = '%s', ad_id = '$adid', disabled = 0";
		$sql = $wpdb->prepare($sql, $result['filename']);
		$result = $wpdb->query($sql) ;
	} else {
		return '<div class="error"><p>' . $result . '</p></div>';
	}

	return $result !== false ? true : false;
}


/**
 * Resize images if they're too wide or too tall based on admin's Image Settings.
 * Requires both max width and max height to be set otherwise no resizing 
 * takes place. If the image exceeds either max width or max height then the 
 * image is resized proportionally.
 */
function awpcp_resizer($filename, $dir) {
	$maxwidth = get_awpcp_option('imgmaxwidth');
	$maxheight = get_awpcp_option('imgmaxheight');

	if ('' == trim($maxheight) || '' == trim ($maxwidth)) {
		return false;
	}

	$parts = pathinfo( $filename );

	if( 'jpg' == $parts['extension'] || 'jpeg' == $parts['extension'] ) {
		$src = imagecreatefromjpeg( $dir . $filename );
	} else if ( 'png' == $parts['extension'] ) {
		$src = imagecreatefrompng( $dir . $filename );
	} else {
		$src = imagecreatefromgif( $dir . $filename );
	}

	list($width, $height) = getimagesize($dir . $filename);

	if ($width < $maxwidth && $height < $maxheight) {
		return true;
	}
	 
	$newwidth = '';
	$newheight = '';

	$aspect_ratio = (float) $height / $width;

	$newheight = $maxheight;
	$newwidth = round($newheight / $aspect_ratio);

	if ($newwidth > $maxwidth) {
		$newwidth = $maxwidth;
		$newheight = round( $newwidth * $aspect_ratio );
	}

	$tmp = imagecreatetruecolor( $newwidth, $newheight );

	imagecopyresampled($tmp, $src, 0, 0, 0, 0, $newwidth, $newheight, $width, $height);

	$newname = $dir . $filename;

	switch ($parts['extension']) {
		case 'gif': 
			@imagegif($tmp, $newname);
			break;
		case 'png': 
			@imagepng($tmp, $newname, 0);
			break;
		case 'jpg': 
		case 'jpeg':
			@imagejpeg($tmp, $newname, 100);
			break;
	}

	imagedestroy($src);
	imagedestroy($tmp);

	return true;
}


function awpcp_setup_uploads_dir() {
	global $wpcontentdir;

	$upload_dir_name = get_awpcp_option('uploadfoldername', 'uploads');
	$upload_dir = $wpcontentdir . '/' . $upload_dir_name . '/';

	// Required to set permission on main upload directory
	require_once(AWPCP_DIR . '/fileop.class.php');

	$fileop = new fileop();
	$owner = fileowner($wpcontentdir);

	if (!is_dir($upload_dir) && is_writable($wpcontentdir)) {
		umask(0);
		mkdir($upload_dir, 0777);
		chown($upload_dir, $owner);
	}
	$fileop->set_permission($upload_dir,0777);
	
	$images_dir = $upload_dir . 'awpcp/';
	$thumbs_dir = $upload_dir . 'awpcp/thumbs/';

	if (!is_dir($images_dir) && is_writable($upload_dir)) {
		umask(0);
		@mkdir($images_dir, 0777);
		@chown($images_dir, $owner);
	}

	if (!is_dir($thumbs_dir) && is_writable($upload_dir)) {
		umask(0);
		@mkdir($thumbs_dir, 0777);
		@chown($thumbs_dir, $owner);
	}

	$fileop->set_permission($images_dir, 0777);
	$fileop->set_permission($thumbs_dir, 0777);

	return array($images_dir, $thumbs_dir);
}


function awpcp_get_image_constraints() {
	$min_width = get_awpcp_option('imgminwidth');
	$min_height = get_awpcp_option('imgminheight');
	$min_size = get_awpcp_option('minimagesize');
	$max_size = get_awpcp_option('maximagesize');
	return array($min_width, $min_height, $min_size, $max_size);
}


function awpcp_handle_uploaded_images($ad_id, &$form_errors=array()) {
	global $wpdb;

	list($images_dir, $thumbs_dir) = awpcp_setup_uploads_dir();
	list($images_allowed, $images_uploaded, $images_left) = awpcp_get_ad_images_information($ad_id);
	list($min_width, $min_height, $min_size, $max_size) = awpcp_get_image_constraints();

	$primary = awpcp_post_param('primary-image');
	$disabled = get_awpcp_option('imagesapprove') == 1 ? 1 : 0;

	if ($images_left <= 0) {
		$form_errors['form'] = __("You can't add more images to this Ad. There are not remaining images slots.", 'AWPCP');
	}

	$count = 0;
	for ($i=0; $i < $images_left; $i++) {
		$field = 'AWPCPfileToUpload' . $i;
		$file = $_FILES[$field];

		if ($file['error'] !== 0) {
			continue;
		}

		$filename = sanitize_file_name($file['name']);
		$tmpname = awpcp_array_data('tmp_name', '', $file);

		$uploaded = awpcp_upload_image_file($images_dir, $filename, $tmpname, $min_size, $max_size, $min_width, $min_height);

		if (is_array($uploaded) && isset($uploaded['filename'])) {
			$sql = 'INSERT INTO ' . AWPCP_TABLE_ADPHOTOS . " SET image_name = '%s', ad_id = %d, disabled = %d";
			$sql = $wpdb->prepare($sql, $uploaded['filename'], $ad_id, $disabled);
			$result = $wpdb->query($sql);

			if ($result !== false) {
				if ($primary == "field-$i") {
					awpcp_set_ad_primary_image($ad_id, $wpdb->insert_id);
				}
				$count += 1;
			} else {
				$msg = __("Could not save the information to the database for: %s", 'AWPCP');
				$form_errors[$field] = sprintf($msg, $uploaded['original']);
			}
		} else {
			$form_errors[$field] = $uploaded;
		}
	}

	if (intval($primary) > 0) {
		awpcp_set_ad_primary_image($ad_id, intval($primary));
	}

	if (empty($form_errors) && $count <= 0) {
		$form_errors['form'] = __('No image files were uploaded');
	}

	$form_errors = array_filter($form_errors);

	if (!empty($form_errors)) {
		return false;
	}

	return true;
}


function handleimagesupload($adid, $adtermid, $nextstep, $adpaymethod, $adaction, $adkey) {
	return awpcp_handle_uploaded_images($ad_id);
}


/**
 * Create thumbnails and resize original image to match image size 
 * restrictions.
 */
function awpcp_create_image_versions($filename, $directory) {
// function awpcpcreatethumb($filename, $directory, $width, $height) {
	$directory = trailingslashit($directory);
	$thumbnails = $directory . 'thumbs/';

	$filepath = $directory . $filename;

	// create thumbnail
	$width = get_awpcp_option('imgthumbwidth');
	$height = get_awpcp_option('imgthumbheight');
	$crop = get_awpcp_option('crop-thumbnails');
	$thumbnail = awpcp_make_intermediate_size($filepath, $thumbnails, $width, $height, $crop);

	// create primary image thumbnail
	$width = get_awpcp_option('primary-image-thumbnail-width');
	$height = get_awpcp_option('primary-image-thumbnail-height');
	$crop = get_awpcp_option('crop-primary-image-thumbnails');
	$primary = awpcp_make_intermediate_size($filepath, $thumbnails, $width, $height, $crop, 'primary');

	// resize original image to match restrictions
	$width = get_awpcp_option('imgmaxwidth');
	$height = get_awpcp_option('imgmaxheight');
	$resized = awpcp_make_intermediate_size($filepath, $directory, $width, $height, false, 'large');

	return $resized && $thumbnail && $primary;
}


function awpcp_make_intermediate_size($file, $directory, $width, $height, $crop=false, $suffix='') {
	$info = pathinfo($file);
	$filename = preg_replace("/\.{$info['extension']}/", '', $info['basename']);
	$suffix = empty($suffix) ? '.' : "-$suffix.";

	$newpath = trailingslashit($directory) . $filename . $suffix . $info['extension'];

	$image = image_make_intermediate_size($file, $width, $height, $crop);

	if (!is_writable($directory)) {
		@chmod($directory, 0755);
		if (!is_writable($directory)) {
			@chmod($directory, 0777);
		}
	}

	if (is_array($image) && !empty($image)) {
		$tmppath = trailingslashit($info['dirname']) . $image['file'];
		$result = rename($tmppath, $newpath);
	} else {
		$result = copy($file, $newpath);
	}
	@chmod($newpath, 0644);

	return $result;
}


function awpcp_GD() {
	$myreturn=array();
	if (function_exists('gd_info')) {
		$myreturn=gd_info();
	} else {
		$myreturn=array('GD Version'=>'');
		ob_start();
		phpinfo(8);
		$info=ob_get_contents();
		ob_end_clean();
		foreach (explode("\n",$info) as $line) {
			if (strpos($line,'GD Version')!==false) {
				$myreturn['GD Version']=trim(str_replace('GD Version', '', strip_tags($line)));
			}
		}
	}
	return $myreturn;
}
