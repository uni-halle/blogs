<?php

/**
 * @deprecated 3.4
 */
function awpcp_display_ads($where, $byl, $hidepager, $grouporderby, $adorcat, $before_content='') {
    _deprecated_function( __FUNCTION__, '3.4', 'awpcp_display_listings' );

    global $wpdb;
    global $awpcp_plugin_path;
    global $hasregionsmodule;

    $output = '';

    $awpcp_browsecats_pageid=awpcp_get_page_id_by_ref('browse-categories-page-name');
    $browseadspageid=awpcp_get_page_id_by_ref('browse-ads-page-name');
    $searchadspageid=awpcp_get_page_id_by_ref('search-ads-page-name');

    // filters to provide alternative method of storing custom layouts (e.g. can be outside of this plugin's directory)
    if ( has_action('awpcp_browse_ads_template_action') || has_filter('awpcp_browse_ads_template_filter') ) {
        do_action('awpcp_browse_ads_template_action');
        $output = apply_filters('awpcp_browse_ads_template_filter');
        return;

    } else if (file_exists("$awpcp_plugin_path/awpcp_display_ads_my_layout.php") &&
               get_awpcp_option('activatemylayoutdisplayads'))
    {
        include("$awpcp_plugin_path/awpcp_display_ads_my_layout.php");

    } else {
        $output .= "<div id=\"classiwrapper\">";

        $uiwelcome=stripslashes_deep(get_awpcp_option('uiwelcome'));

        $output .= apply_filters( 'awpcp-content-before-listings-page', '' );
        $output .= "<div class=\"uiwelcome\">$uiwelcome</div>";
        $output .= awpcp_menu_items();

        if ($hasregionsmodule ==  1) {
            // Do not show Region Control form when showing Search Ads page
            // search result. Changing the current location will redirect the user
            // to the form instead of a filterd version of the form and that's confusing
            if ( is_page( awpcp_get_page_id_by_ref( 'search-ads-page-name' ) ) && isset( $_POST['a']) && $_POST['a'] == 'dosearch' ) {
                // do nothing
            } else {
                $output .= awpcp_region_control_selector();
            }
        }

        $output .= $before_content;

        $tbl_ads = $wpdb->prefix . "awpcp_ads";

        $from="$tbl_ads";

        $ads_exist = ads_exist();

        if (!$ads_exist) {
            $showcategories="<p style=\"padding:10px\">";
            $showcategories.=__("There are currently no ads in the system","AWPCP");
            $showcategories.="</p>";
            $pager1='';
            $pager2='';

        } else {
            $awpcp_image_display_list=array();

            if ($adorcat == 'cat') {
                $tpname = get_permalink($awpcp_browsecats_pageid);
            } elseif ($adorcat == 'search') {
                $tpname = get_permalink($searchadspageid);
            } elseif ( preg_match( '/^custom:/', $adorcat ) ) {
                $tpname = str_replace( 'custom:', '', $adorcat );
            } else {
                $tpname = get_permalink($browseadspageid);
            }

            $results = get_awpcp_option( 'adresultsperpage', 10 );
            $results = absint( awpcp_request_param( 'results', $results ) );
            $offset = absint( awpcp_request_param( 'offset', 0 ) );

            if ( $results === 0 ) {
                $results = 10;
            }

            $args = array(
                'order' => AWPCP_Ad::get_order_conditions( $grouporderby ),
                'offset' => $offset,
                'limit' => $results,
            );
            $ads = AWPCP_Ad::get_enabled_ads( $args, array( $where ) );

            // get_where_conditions() is called from get_enabled_ads(), we need the
            // WHERE conditions here to pass them to create_pager()
            $where = AWPCP_Ad::get_where_conditions( array( $where ) );

            if (!isset($hidepager) || empty($hidepager) ) {
                //Unset the page and action here...these do the wrong thing on display ad
                unset($_GET['page_id']);
                unset($_POST['page_id']);
                //unset($params['page_id']);
                $pager1=create_pager($from, join( ' AND ', $where ),$offset,$results,$tpname);
                $pager2=create_pager($from, join( ' AND ', $where ),$offset,$results,$tpname);
            } else {
                $pager1='';
                $pager2='';
            }

            $items = awpcp_render_listings_items( $ads, 'listings' );

            $opentable = "";
            $closetable = "";

            if (empty($ads)) {
                $showcategories="<p style=\"padding:20px;\">";
                $showcategories.=__("There were no ads found","AWPCP");
                $showcategories.="</p>";
                $pager1='';
                $pager2='';
            } else {
                $showcategories = smart_table($items, intval($results/$results), $opentable, $closetable);
            }
        }

        $show_category_id = absint( awpcp_request_param( 'category_id' ) );

        if (!isset($url_browsecatselect) || empty($url_browsecatselect)) {
            $url_browsecatselect = get_permalink($awpcp_browsecats_pageid);
        }

        if ($ads_exist) {
            $category_id = (int) awpcp_request_param('category_id', -1);
            $category_id = $category_id === -1 ? (int) get_query_var('cid') : $category_id;

            $output .= "<div class=\"changecategoryselect\"><form method=\"post\" action=\"$url_browsecatselect\">";

            $output .= '<div class="awpcp-category-dropdown-container">';
            $dropdown = new AWPCP_CategoriesDropdown();
            $output .= $dropdown->render( array( 'context' => 'search', 'name' => 'category_id', 'selected' => $category_id ) );
            $output .= '</div>';

            $output .= "<input type=\"hidden\" name=\"a\" value=\"browsecat\" />&nbsp;<input class=\"button\" type=\"submit\" value=\"";
            $output .= __("Change Category","AWPCP");
            $output .= "\" /></form></div>";

            $output .= "<div class=\"pager\">$pager1</div><div class=\"fixfloat\"></div>";

            $output .= "<div id='awpcpcatname' class=\"fixfloat\">";

            if ($category_id > 0) {
                $output .= "<h3>" . __("Category: ", "AWPCP") . get_adcatname($category_id) . "</h3>";
            }

            $output .= "</div>";
        }

        $output .= apply_filters('awpcp-display-ads-before-list', '');
        $output .= "$showcategories";

        if ($ads_exist) {
            $output .= "&nbsp;<div class=\"pager\">$pager2</div>";
        }

        $output .= apply_filters( 'awpcp-content-after-listings-page', '' );
        $output .= "</div>";

    }
    return $output;
}

/**
 * @deprecated 3.4
 */
function awpcp_render_ads($ads, $context='listings', $config=array(), $pagination=array()) {
    _deprecated_function( __FUNCTION__, '3.4', 'awpcp_display_listings' );

    $config = shortcode_atts(array('show_menu' => true, 'show_intro' => true), $config);

    if (has_action('awpcp_browse_ads_template_action') || has_filter('awpcp_browse_ads_template_filter')) {
        do_action('awpcp_browse_ads_template_action');
        $output = apply_filters('awpcp_browse_ads_template_filter');
        return;
    } else if (file_exists(AWPCP_DIR . "/awpcp_display_ads_my_layout.php") && get_awpcp_option('activatemylayoutdisplayads')) {
        include(AWPCP_DIR . "/awpcp_display_ads_my_layout.php");
        return;
    }

    $items = awpcp_render_listings_items( $ads, $context );

    $before_content = apply_filters('awpcp-listings-before-content', array(), $context);
    $after_content = apply_filters('awpcp-listings-after-content', array(), $context);
    $pagination_block = is_array( $pagination ) ? awpcp_pagination( $pagination, '' ) : '';

    ob_start();
        include(AWPCP_DIR . '/frontend/templates/listings.tpl.php');
        $output = ob_get_contents();
    ob_end_clean();

    return $output;
}

/**
 * Upload and associates the given files with the specified Ad.
 *
 * @param $files    An array of elements of $_FILES.
 * @since 3.0.2
 * @deprecated 3.4
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
 * @param $error    if an error occurs the error message will be returned by reference
 *                  using this variable.
 * @param $action   'upload' if the file was uplaoded using an HTML File field.
 *                  'copy' if the file was uplaoded using a different method. Images
 *                  extracted from a ZIP file during Ad import.
 * @return          false if an error occurs or an array with the upload file information
 *                  on success.
 * @since 3.0.2
 * @deprecated  3.4
 */
function awpcp_upload_file( $file, $constraints, &$error=false, $action='upload' ) {
    $filename = sanitize_file_name( strtolower( $file['name'] ) );
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
            $error = _x( 'The size of %1$s was too small. The file was not uploaded. File size must be greater than %2$d bytes.', 'upload files', 'AWPCP' );
            $error = sprintf( $error, '<strong>' . $filename . '</strong>', $constraints['min_image_size'] );
            return false;
        }

        $img_info = getimagesize( $tmpname );

        if ( ! isset( $img_info[ 0 ] ) && ! isset( $img_info[ 1 ] ) ) {
            $error = _x( 'The file %s does not appear to be a valid image file.', 'upload files', 'AWPCP' );
            $error = sprintf( $error, '<strong>' . $filename . '</strong>' );
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

    $newname = awpcp_unique_filename( $tmpname, $filename, array( $paths['files_dir'], $paths['thumbnails_dir'] ) );
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
        'filename' => awpcp_utf8_basename( $newpath ),
        'path' => str_replace( $paths['files_dir'], '', $newpath ),
        'mime_type' => $mime_type,
    );
}

/**
 * @since 3.0.2
 * @deprecated 3.4
 */
function awpcp_get_allowed_mime_types() {
    return awpcp_array_data( 'mime_types', array(), awpcp_get_upload_file_constraints() );
}

/**
 * File type, size and dimension constraints for uplaoded files.
 *
 * @since 3.0.2
 * @deprecated 3.4
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
 * Determines if a file of the given type can be added to an Ad based solely
 * on the number of files of the same type that are already attached to
 * the Ad.
 *
 * @since 3.0.2
 * @deprecated 3.4
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
 * Returns information about the number of files uplaoded to an Ad, and
 * the number of files that can still be added to that same Ad.
 *
 * @since 3.0.2
 * @deprecated 3.4
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
 * Verifies the upload directories exists and have proper permissions, then
 * returns the path to the directories to store raw files and image thumbnails.
 *
 * @since 3.0.2
 * @deprecated 3.4
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

/**
 * Resize images if they're too wide or too tall based on admin's Image Settings.
 * Requires both max width and max height to be set otherwise no resizing 
 * takes place. If the image exceeds either max width or max height then the 
 * image is resized proportionally.
 *
 * @deprecated 3.4
 */
function awpcp_resizer($filename, $dir) {
    $maxwidth = get_awpcp_option('imgmaxwidth');
    $maxheight = get_awpcp_option('imgmaxheight');

    if ('' == trim($maxheight) || '' == trim ($maxwidth)) {
        return false;
    }

    $parts = awpcp_utf8_pathinfo( $filename );

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

/**
 * @deprecated 3.4
 */
function get_categorynameidall($cat_id = 0) {
    global $wpdb;

    $optionitem='';

    // Start with the main categories
    $query = "SELECT category_id,category_name FROM " . AWPCP_TABLE_CATEGORIES . " ";
    $query.= "WHERE category_parent_id=0 AND category_name <> '' ";
    $query.= "ORDER BY category_order, category_name ASC";

    $query_results = $wpdb->get_results( $query, ARRAY_N );

    foreach ( $query_results as $rsrow ) {
        $cat_ID = $rsrow[0];
        $cat_name = stripslashes(stripslashes($rsrow[1]));

        $opstyle = "class=\"dropdownparentcategory\"";

        if($cat_ID == $cat_id) {
            $maincatoptionitem = "<option $opstyle selected='selected' value='$cat_ID'>$cat_name</option>";
        } else {
            $maincatoptionitem = "<option $opstyle value='$cat_ID'>$cat_name</option>";
        }

        $optionitem.="$maincatoptionitem";

        // While still looping through main categories get any sub categories of the main category

        $maincatid = $cat_ID;

        $query = "SELECT category_id,category_name FROM " . AWPCP_TABLE_CATEGORIES . " ";
        $query.= "WHERE category_parent_id=%d ";
        $query.= "ORDER BY category_order, category_name ASC";

        $query = $wpdb->prepare( $query, $maincatid );

        $sub_query_results = $wpdb->get_results( $query, ARRAY_N );

        foreach ( $sub_query_results as $rsrow2) {
            $subcat_ID = $rsrow2[0];
            $subcat_name = stripslashes(stripslashes($rsrow2[1]));

            if($subcat_ID == $cat_id) {
                $subcatoptionitem = "<option selected='selected' value='$subcat_ID'>- $subcat_name</option>";
            } else {
                $subcatoptionitem = "<option  value='$subcat_ID'>- $subcat_name</option>";
            }

            $optionitem.="$subcatoptionitem";
        }
    }

    return $optionitem;
}
