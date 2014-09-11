<?php

/**
 * Upload and associates the given files with the specified Ad.
 *
 * @param $files	An array of elements of $_FILES.
 * @since 3.0.2
 */
function awpcp_upload_files( $ad, $files, &$errors=array() ) {
    $media = awpcp_media_api();

    $constraints = awpcp_get_upload_file_constraints();
    $image_mime_types = awpcp_get_image_mime_types();

    $uploaded = array();
    foreach ( $files as $name => $info ) {
    	$can_upload = awpcp_can_upload_file_to_ad( $info, $ad );
    	if ( $can_upload !== true ) {
    		if ( $can_upload !== false ) {
    			$errors[ $name ] = $can_upload;
    		} else {
    			$message = _x( 'An error occurred trying to upload the file %s.', 'upload files', 'AWPCP' );
    			$errors[ $name ] = sprintf( $message, '<strong>' . $info['name'] . '</strong>' );
    		}
    		continue;
    	}

        if ( $result = awpcp_upload_file( $info, $constraints, $error ) ) {
            $file = $media->create( array(
                'ad_id' => $ad->ad_id,
                'name' => $result['filename'],
                'path' => $result['path'],
                'mime_type' => $result['mime_type'],
                'is_primary' => in_array( $info['type'], $image_mime_types ) && awpcp_array_data( 'is_primary', false, $info ),
            ) );

            if ( ! is_null( $file ) ) {
            	if ( $file->is_image() && $file->is_primary() ) {
	                $media->set_ad_primary_image( $ad, $file );
	            }

	            $uploaded[] = $file;
            } else {
                $message = _x( 'The file %s was properly uploaded but there was a problem trying to save the information to the database.', 'upload files', 'AWPCP' );
                $errors[ $name ] = sprintf( $message, '<strong>' . $result['original'] . '</strong>' );
            }
        } else {
            $errors[ $name ] = $error;
        }
    }

    return $uploaded;
}


/**
 * Check that the given file meets the file size, dimensions and file type
 * constraints and moves the file to the AWPCP Uploads directory.
 *
 * @param $error	if an error occurs the error message will be returned by reference
 *					using this variable.
 * @param $action	'upload' if the file was uplaoded using an HTML File field.
 *					'copy' if the file was uplaoded using a different method. Images
 *					extracted from a ZIP file during Ad import.
 * @return			false if an error occurs or an array with the upload file information
 *					on success.
 * @since 3.0.2
 */
function awpcp_upload_file( $file, $constraints, &$error=false, $action='upload' ) {
	$filename = sanitize_file_name( $file['name'] );
	$tmpname = $file['tmp_name'];

	$mime_type = $file[ 'type' ];

	if ( ! in_array( $mime_type, $constraints[ 'mime_types' ] ) ) {
		$error = _x( 'The type of the uplaoded file %s is not allowed.', 'upload files', 'AWPCP' );
		$error = sprintf( $error, '<strong>' . $filename . '</strong>' );
		return false;
	}

	$paths = awpcp_get_uploads_directories();

	if ( ! file_exists( $tmpname ) ) {
		$error = _x( 'The specified file does not exists: %s.', 'upload files', 'AWPCP' );
		$error = sprintf( $error, '<strong>' . $filename . '</strong>' );
		return false;
	}

	if ( $action == 'upload' && ! is_uploaded_file( $tmpname ) ) {
		$error = _x( 'Unknown error encountered while uploading the image.', 'upload files', 'AWPCP' );
		$error = sprintf( $error, '<strong>' . $filename . '</strong>' );
		return false;
	}

	$file_size = filesize( $tmpname );

	if ( empty( $file_size ) || $file_size <= 0 ) {
		$error = _x( 'There was an error trying to find out the file size of the image %s.', 'upload files', 'AWPCP' );
		$error = sprintf( $error, '<strong>' . $filename . '</strong>' );
		return false;
	}

	if ( in_array( $mime_type, awpcp_get_image_mime_types() ) ) {
		if ( $file_size > $constraints['max_image_size'] ) {
			$error = _x( 'The file %s was larger than the maximum allowed file size of %s bytes. The file was not uploaded.', 'upload files', 'AWPCP' );
			$error = sprintf( $error, '<strong>' . $filename . '</strong>', $constraints['max_image_size'] );
			return false;
		}

		if ( $file_size < $constraints['min_image_size'] ) {
			$error = _x( 'The file %s does not appear to be a valid image file.', 'upload files', 'AWPCP' );
			$error = sprintf( $error, '<strong>' . $filename . '</strong>' );
			return false;
		}

		$img_info = getimagesize( $tmpname );

		if ( ! isset( $img_info[ 0 ] ) && ! isset( $img_info[ 0 ] ) ) {
			$error = _x( 'The size of %1$s was too small. The file was not uploaded. File size must be greater than %2$d bytes.', 'upload files', 'AWPCP' );
			$error = sprintf( $error, '<strong>' . $filename . '</strong>', $constraints['min_image_size'] );
			return false;
		}

		if ( $img_info[ 0 ] < $constraints['min_image_width'] ) {
			$error = _x( 'The image %s did not meet the minimum width of %s pixels. The file was not uploaded.', 'upload files', 'AWPCP');
			$error = sprintf( $error, '<strong>' . $filename . '</strong>', $constraints['min_image_width'] );
			return false;
		}

		if ( $img_info[ 1 ] < $constraints['min_image_height'] ) {
			$error = _x( 'The image %s did not meet the minimum height of %s pixels. The file was not uploaded.', 'upload files', 'AWPCP');
			$error = sprintf( $error, '<strong>' . $filename . '</strong>', $constraints['min_image_height'] );
			return false;
		}
	} else {
		if ( $file_size > $constraints['max_attachment_size'] ) {
			$error = _x( 'The file %s was larger than the maximum allowed file size of %s bytes. The file was not uploaded.', 'upload files', 'AWPCP' );
			$error = sprintf( $error, '<strong>' . $filename . '</strong>', $constraints['max_attachment_size'] );
			return false;
		}
	}

	$newname = wp_unique_filename( $paths['files_dir'], $filename );
	$newpath = trailingslashit( $paths['files_dir'] ) . $newname;

	if ( $action == 'upload' && ! @move_uploaded_file( $tmpname, $newpath ) ) {
		$error = _x( 'The file %s could not be moved to the destination directory.', 'upload files', 'AWPCP' );
		$error = sprintf( $error, '<strong>' . $filename . '</strong>' );
		return false;
	} else if ( $action == 'copy' && ! @copy( $tmpname, $newpath ) ) {
		$error = _x( 'The file %s could not be copied to the destination directory.', 'upload files', 'AWPCP' );
		$error = sprintf( $message, '<strong>' . $filename . '</strong>' );
		return false;
	}

	if ( in_array( $mime_type, awpcp_get_image_mime_types() ) ) {
		if ( ! awpcp_create_image_versions( $newname, $paths['files_dir'] ) ) {
			$error = _x( 'Could not create resized versions of image %s.', 'upload files', 'AWPCP' );
			$error = sprintf( $error, '<strong>' . $filename . '</strong>' );

			# TODO: unlink resized version, thumbnail and primary image
			@unlink( $newpath );

			return false;
		}
	}

	@chmod( $newpath, 0644 );

	return array(
		'original' => $filename,
		'filename' => basename( $newpath ),
		'path' => str_replace( $paths['files_dir'], '', $newpath ),
		'mime_type' => $mime_type,
	);
}

/**
 * Return mime types associated with image files.
 *
 * @since 3.0.2
 */
function awpcp_get_image_mime_types() {
	return array(
		'image/png',
		'image/jpg', 'image/jpeg', 'image/pjpeg',
		'image/gif',
	);
}

/**
 * @since 3.0.2
 */
function awpcp_get_allowed_mime_types() {
	return awpcp_array_data( 'mime_types', array(), awpcp_get_upload_file_constraints() );
}

/**
 * File type, size and dimension constraints for uplaoded files.
 *
 * @since 3.0.2
 */
function awpcp_get_upload_file_constraints( ) {
	return apply_filters( 'awpcp-upload-file-constraints', array(
		'mime_types' => awpcp_get_image_mime_types(),

		'max_image_size' => get_awpcp_option( 'maximagesize' ),
		'min_image_size' => get_awpcp_option( 'minimagesize' ),
		'min_image_height' => get_awpcp_option( 'imgminheight' ),
		'min_image_width' => get_awpcp_option( 'imgminwidth' ),
	) );
}

/**
 * Returns information about the number of files uplaoded to an Ad, and
 * the number of files that can still be added to that same Ad.
 *
 * @since 3.0.2
 */
function awpcp_get_ad_uploaded_files_stats( $ad ) {
    $payment_term = awpcp_payments_api()->get_ad_payment_term( $ad );

    $images_allowed = get_awpcp_option( 'imagesallowedfree', 0 );
    $images_allowed = awpcp_get_property( $payment_term, 'images', $images_allowed );
    $images_uploaded = $ad->count_image_files();
    $images_left = max( $images_allowed - $images_uploaded, 0 );

    return apply_filters( 'awpcp-ad-uploaded-files-stats', array(
        'images_allowed' => $images_allowed,
        'images_uploaded' => $images_uploaded,
        'images_left' => $images_left,
	), $ad );
}

/**
 * Determines if a file of the given type can be added to an Ad based solely
 * on the number of files of the same type that are already attached to
 * the Ad.
 *
 * @since 3.0.2
 */
function awpcp_can_upload_file_to_ad( $file, $ad ) {
    $stats = awpcp_get_ad_uploaded_files_stats( $ad );

    $image_mime_types = awpcp_get_image_mime_types();
    $images_allowed = $stats['images_allowed'];
    $images_uploaded = $stats['images_uploaded'];

    $result = true;

    if ( in_array( $file['type'], $image_mime_types ) ) {
    	if ( $images_allowed <= $images_uploaded ) {
    		$result = _x( "You can't add more images to this Ad. There are not remaining images slots.", 'upload files', 'AWPCP' );
    	}
    }

    return apply_filters( 'awpcp-can-upload-file-to-ad', $result, $file, $ad, $stats );
}

/**
 * Verifies the upload directories exists and have proper permissions, then
 * returns the path to the directories to store raw files and image thumbnails.
 *
 * @since 3.0.2
 */
function awpcp_get_uploads_directories() {
	static $uploads_directories = null;

	if ( is_null( $uploads_directories ) ) {
		global $wpcontentdir;

		$permissions = awpcp_directory_permissions();

		$upload_dir_name = get_awpcp_option( 'uploadfoldername', 'uploads' );
		$upload_dir = $wpcontentdir . '/' . $upload_dir_name . '/';

		// Required to set permission on main upload directory
		require_once(AWPCP_DIR . '/fileop.class.php');

		$fileop = new fileop();
		$owner = fileowner( $wpcontentdir );

		if ( ! is_dir( $upload_dir ) && is_writable( $wpcontentdir ) ) {
			umask( 0 );
			mkdir( $upload_dir, $permissions );
			chown( $upload_dir, $owner );
		}

		$fileop->set_permission( $upload_dir, $permissions );

		$files_dir = $upload_dir . 'awpcp/';
		$thumbs_dir = $upload_dir . 'awpcp/thumbs/';

		if ( ! is_dir( $files_dir ) && is_writable( $upload_dir ) ) {
			umask( 0 );
			@mkdir( $files_dir, $permissions );
			@chown( $files_dir, $owner );
		}

		if ( ! is_dir( $thumbs_dir ) && is_writable( $upload_dir ) ) {
			umask( 0 );
			@mkdir( $thumbs_dir, $permissions );
			@chown( $thumbs_dir, $owner );
		}

		$fileop->set_permission( $files_dir, $permissions );
		$fileop->set_permission( $thumbs_dir, $permissions );

		$uploads_directories = array(
			'files_dir' => $files_dir,
			'thumbnails_dir' => $thumbs_dir,
		);
	}

	return $uploads_directories;
}

// ----------------------------------------------------------------------------
// ----------------------------------------------------------------------------
// ----------------------------------------------------------------------------
// ----------------------------------------------------------------------------
// ----------------------------------------------------------------------------
// ----------------------------------------------------------------------------

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
		return sprintf( __('The file %s has an invalid extension and was rejected.', 'AWPCP'), $filename );

	} elseif ($size < $min_size) {
		$message = __('The size of %1$s was too small. The file was not uploaded. File size must be greater than %2$d bytes.', 'AWPCP');
		return sprintf($message, $filename, $min_size);

	} elseif ($size > $max_size) {
		$message = __('The file %s was larger than the maximum allowed file size of %s bytes. The file was not uploaded.', 'AWPCP');
		return sprintf($message, $filename, $max_size);

	} elseif (!isset($imginfo[0]) && !isset($imginfo[1])) {
		return sprintf( __('The file %s does not appear to be a valid image file.', 'AWPCP' ), $filename );

	} elseif ( $imginfo[0] < $min_width ) {
		$message = __('The image %s did not meet the minimum width of %s pixels. The file was not uploaded.', 'AWPCP');
		return sprintf($message, $filename, $min_width);

	} elseif ($imginfo[1] < $min_height) {
		$message = __('The image %s did not meet the minimum height of %s pixels. The file was not uploaded.', 'AWPCP');
		return sprintf( $message, $filename, $min_height );
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

	$permissions = awpcp_directory_permissions();

	$upload_dir_name = get_awpcp_option('uploadfoldername', 'uploads');
	$upload_dir = $wpcontentdir . '/' . $upload_dir_name . '/';

	// Required to set permission on main upload directory
	require_once(AWPCP_DIR . '/fileop.class.php');

	$fileop = new fileop();
	$owner = fileowner($wpcontentdir);

	if (!is_dir($upload_dir) && is_writable($wpcontentdir)) {
		umask(0);
		mkdir( $upload_dir, $permissions );
		chown($upload_dir, $owner);
	}

	$fileop->set_permission( $upload_dir, $permissions );

	$images_dir = $upload_dir . 'awpcp/';
	$thumbs_dir = $upload_dir . 'awpcp/thumbs/';

	if (!is_dir($images_dir) && is_writable($upload_dir)) {
		umask(0);
		@mkdir( $images_dir, $permissions );
		@chown($images_dir, $owner);
	}

	if (!is_dir($thumbs_dir) && is_writable($upload_dir)) {
		umask(0);
		@mkdir( $thumbs_dir, $permissions );
		@chown($thumbs_dir, $owner);
	}

	$fileop->set_permission( $images_dir, $permissions );
	$fileop->set_permission( $thumbs_dir, $permissions );

	return array($images_dir, $thumbs_dir);
}


function awpcp_get_image_constraints() {
	$min_width = get_awpcp_option('imgminwidth');
	$min_height = get_awpcp_option('imgminheight');
	$min_size = get_awpcp_option('minimagesize');
	$max_size = get_awpcp_option('maximagesize');
	return array($min_width, $min_height, $min_size, $max_size);
}


/**
 * Create thumbnails and resize original image to match image size
 * restrictions.
 */
function awpcp_create_image_versions($filename, $directory) {
	$directory = trailingslashit($directory);
	$thumbnails = $directory . 'thumbs/';

	$filepath = $directory . $filename;

	awpcp_fix_image_rotation( $filepath );

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

/**
 * @since 3.0.2
 */
function awpcp_fix_image_rotation( $filepath ) {
	if ( ! function_exists( 'exif_read_data' ) ) {
		return;
	}

	$exif_data = @exif_read_data( $filepath );

	$orientation = isset( $exif_data['Orientation'] ) ? $exif_data['Orientation'] : 0;
	$mime_type = isset( $exif_data['MimeType'] ) ? $exif_data['MimeType'] : '';

	$rotation_angle = 0;
	if ( 6 == $orientation ) {
		$rotation_angle = 90;
	} else if ( 3 == $orientation ) {
		$rotation_angle = 180;
	} else if ( 8 == $orientation ) {
		$rotation_angle = 270;
	}

	if ( $rotation_angle > 0 ) {
		awpcp_rotate_image( $filepath, $mime_type, $rotation_angle );
	}
}


/**
 * @since 3.0.2
 */
function awpcp_rotate_image( $file, $mime_type, $angle ) {
	if ( class_exists( 'Imagick' ) ) {
		awpcp_rotate_image_with_imagick( $file, $angle );
	} else {
		awpcp_rotate_image_with_gd( $file, $mime_type, $angle );
	}
}


/**
 * @since 3.0.2
 */
function awpcp_rotate_image_with_imagick( $filepath, $angle ) {
	$imagick = new Imagick();
	$imagick->readImage( $filepath );
	$imagick->rotateImage( new ImagickPixel(), $angle );
	$imagick->setImageOrientation( 1 );
	$imagick->writeImage( $filepath );
	$imagick->clear();
	$imagick->destroy();
}


/**
 * @since 3.0.2
 */
function awpcp_rotate_image_with_gd( $filepath, $mime_type, $angle ) {
    // GD needs negative degrees
    $angle = -$angle;

    switch ( $mime_type ) {
    	case 'image/jpeg':
    		$source = imagecreatefromjpeg( $filepath );
    		$rotate = imagerotate( $source, $angle, 0 );
    		imagejpeg( $rotate, $filepath );
    		break;
    	case 'image/png':
    		$source = imagecreatefrompng( $filepath );
    		$rotate = imagerotate( $source, $angle, 0 );
    		imagepng( $rotate, $filepath );
    		break;
    	case 'image/gif':
    		$source = imagecreatefromgif( $filepath );
    		$rotate = imagerotate( $source, $angle, 0 );
    		imagegif( $rotate, $filepath );
    		break;
    	default:
    		break;
    }
}

function awpcp_make_intermediate_size($file, $directory, $width, $height, $crop=false, $suffix='') {
	$info = pathinfo($file);
	$filename = preg_replace("/\.{$info['extension']}/", '', $info['basename']);
	$suffix = empty($suffix) ? '.' : "-$suffix.";

	$newpath = trailingslashit($directory) . $filename . $suffix . $info['extension'];

	$image = image_make_intermediate_size($file, $width, $height, $crop);

	if (!is_writable($directory)) {
		@chmod( $directory, awpcp_directory_permissions() );
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
