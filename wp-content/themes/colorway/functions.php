<?php

ob_start();
include_once get_template_directory() . '/functions/inkthemes-functions.php';
include_once get_template_directory() . '/functions/themes-page.php';
include_once get_template_directory() . '/functions/customizer.php';

//get the theme option from options array
function inkthemes_get_option($name, $default = '') {
    $options = get_option('inkthemes_options');
    if (isset($options[$name])) {
        return $options[$name];
    } else {
        return $default;
    }
}

// Save all option in single array
function inkthemes_save_option($option) {
    if (!empty($option)) {
        return update_option('inkthemes_options', $option);
    }
}

//update theme option
function inkthemes_update_option($name, $value) {
    $options = get_option('inkthemes_options');
    $options[$name] = $value;
    return update_option('inkthemes_options', $options);
}

//delete theme option
function inkthemes_delete_option($name) {
    $options = get_option('inkthemes_options');
    unset($options[$name]);
    return update_option('inkthemes_options', $options);
}

$inkthemes_backup_data = get_option('inkthemes_backup_data');
if (!$inkthemes_backup_data) {
    $colorway_options = get_option('colorway');
    $inkthemes_options = get_option('inkthemes_options');
    if (!empty($colorway_options) && empty($inkthemes_options)) {
        foreach ($colorway_options as $key => $val) {
            inkthemes_update_option($key, $val);
        }
        update_option('inkthemes_backup_data', '1');
    } elseif (!empty($inkthemes_options)) {
        foreach ($colorway_options as $key => $val) {
            $previous_value = inkthemes_get_option($key);
            if ($previous_value == '') {
                inkthemes_update_option($key, $val);
            }
        }
        update_option('inkthemes_backup_data', '1');
    } elseif (empty($colorway_options) && empty($inkthemes_options)) {
        update_option('inkthemes_backup_data', '1');
    }
}
/*
  /* Styles Enqueue
 */

function inkthemes_add_stylesheet() {
    if (!is_admin()) {
        wp_enqueue_style('inkthemes_reset_stylesheet', get_template_directory_uri() . "/css/reset.css", '', '', 'all');
        wp_enqueue_style('inkthemes_responsive_stylesheet', get_template_directory_uri() . "/css/960_24_col_responsive.css", '', '', 'all');
        wp_enqueue_style('inkthemes_stylesheet', get_template_directory_uri() . "/style.css", '', '', 'all');
        wp_enqueue_style('inkthemes_superfish', get_template_directory_uri() . "/css/superfish.css", '', '', 'all');
        wp_enqueue_style('inkthemes-media', get_template_directory_uri() . "/css/media.css", '', '', 'all');
        wp_enqueue_style('inkthemes-animate', get_template_directory_uri() . "/css/animate.css", '', '', 'all');
    }
}

add_action('init', 'inkthemes_add_stylesheet');
/* ----------------------------------------------------------------------------------- */
/* jQuery Enqueue */
/* ---------------------------------------------------------------------------------- */

function inkthemes_wp_enqueue_scripts() {
    if (!is_admin()) {
        wp_enqueue_script('jquery');
        wp_enqueue_script('inkthemes_sfish', get_template_directory_uri() . "/js/superfish.js", array('jquery'));
        wp_enqueue_script('slides', get_template_directory_uri() . '/js/jquery.flexslider.js', array('jquery'));
        wp_enqueue_script('inkthemes_tipsy', get_template_directory_uri() . '/js/jquery.tipsy.js', array('jquery'));
        wp_enqueue_script('inkthemes-responsive-menu-2', get_template_directory_uri() . '/js/menu/jquery.meanmenu.2.0.min.js', array('jquery'));
        wp_enqueue_script('inkthemes-responsive-menu-2-options', get_template_directory_uri() . '/js/menu/jquery.meanmenu.options.js', array('jquery'));
        wp_enqueue_script('inkthemes-modernizr', get_template_directory_uri() . '/js/modernizr.custom.79639.js', array('jquery'));
        wp_enqueue_script('inkthemes-ba-cond', get_template_directory_uri() . '/js/jquery.ba-cond.js', array('jquery'), false, true);
        wp_enqueue_script('inkthemes-slitslider', get_template_directory_uri() . '/js/jquery.slitslider.js', array('jquery'), false, true);
        wp_enqueue_script('inkthemes-sliderinit', get_template_directory_uri() . '/js/slider-init.js', array('jquery'), false, true);
        wp_enqueue_script('inkthemes_custom', get_template_directory_uri() . '/js/custom.js', array('jquery'));
    } elseif (is_admin()) {
        
    }
}

add_action('wp_enqueue_scripts', 'inkthemes_wp_enqueue_scripts');

/**
 * Enqueues the javascript for comment replys 
 * 
 * */
function inkthemes_enqueue_scripts() {
    if (is_singular() and get_site_option('thread_comments')) {
        wp_print_scripts('comment-reply');
    }
}

add_action('wp_enqueue_scripts', 'inkthemes_enqueue_scripts');
//Front Page Rename
$get_status = inkthemes_get_option('re_nm');
$get_file_ac = get_template_directory() . '/front-page.php';
$get_file_dl = get_template_directory() . '/front-page-hold.php';
//True Part
if ($get_status === 'off' && file_exists($get_file_ac)) {
    rename("$get_file_ac", "$get_file_dl");
}
//False Part
if ($get_status === 'on' && file_exists($get_file_dl)) {
    rename("$get_file_dl", "$get_file_ac");
}
/**
 * Load plugin notification file
 */
require_once(get_template_directory() . '/functions/plugin-activation.php');
require_once(get_template_directory() . '/functions/inkthemes-plugin-notify.php');
add_action('tgmpa_register', 'inkthemes_plugins_notify');

//delete_option('colorway_migrate_option');
add_action('init', 'colorway_migrate_option');

function colorway_migrate_option() {
    if (get_option('inkthemes_options') && !get_option('colorway_migrate_option')) {
        $theme_option = array('colorway_logo', 'colorway_favicon', 'colorway_slideimage1', 'colorway_slideimage2', 'inkthemes_fimg1', 'inkthemes_fimg2', 'inkthemes_fimg3', 'inkthemes_fimg4', 'inkthemes_testimonial_img', 'inkthemes_testimonial_img_2', 'inkthemes_testimonial_img_3');
        $wp_upload_dir = wp_upload_dir();
        require ( ABSPATH . 'wp-admin/includes/image.php' );
        foreach ($theme_option as $option) {
            $option_value = inkthemes_get_option($option);
            if ($option_value && $option_value != '') {
                $filetype = wp_check_filetype(basename($option_value), null);
                $image_name = preg_replace('/\.[^.]+$/', '', basename($option_value));
                $new_image_url = $wp_upload_dir['path'] . '/' . $image_name . '.' . $filetype['ext'];
                colorway_import_file($new_image_url);
            }
        }
        update_option('colorway_migrate_option', true);
    }
}

function colorway_import_file($file, $post_id = 0, $import_date = 'file') {
    set_time_limit(120);

    // Initially, Base it on the -current- time.
    $time = current_time('mysql', 1);
//     Next, If it's post to base the upload off:

    $time = gmdate('Y-m-d H:i:s', @filemtime($file));


//     A writable uploads dir will pass this test. Again, there's no point overriding this one.
    if (!( ( $uploads = wp_upload_dir($time) ) && false === $uploads['error'] )) {
        return new WP_Error('upload_error', $uploads['error']);
    }

    $wp_filetype = wp_check_filetype($file, null);

    extract($wp_filetype);

    if ((!$type || !$ext ) && !current_user_can('unfiltered_upload')) {
        return new WP_Error('wrong_file_type', __('Sorry, this file type is not permitted for security reasons.', 'colorway')); //A WP-core string..
    }

    $file_name = str_replace('\\', '/', $file);

    if (preg_match('|^' . preg_quote(str_replace('\\', '/', $uploads['basedir'])) . '(.*)$|i', $file_name, $mat)) {
        $filename = basename($file);
        $new_file = $file;
        $url = $uploads['baseurl'] . $mat[1];
        $attachment = get_posts(array('post_type' => 'attachment', 'meta_key' => '_wp_attached_file', 'meta_value' => ltrim($mat[1], '/')));
        if (!empty($attachment)) {
            return new WP_Error('file_exists', __('Sorry, That file already exists in the WordPress media library.', 'colorway'));
        }

        //Ok, Its in the uploads folder, But NOT in WordPress's media library.
        if ('file' == $import_date) {
            $time = @filemtime($file);
            if (preg_match("|(\d+)/(\d+)|", $mat[1], $datemat)) { //So lets set the date of the import to the date folder its in, IF its in a date folder.
                $hour = $min = $sec = 0;
                $day = 1;
                $year = $datemat[1];
                $month = $datemat[2];

                // If the files datetime is set, and it's in the same region of upload directory, set the minute details to that too, else, override it.
                if ($time && date('Y-m', $time) == "$year-$month") {
                    list($hour, $min, $sec, $day) = explode(';', date('H;i;s;j', $time));
                }
                $time = mktime($hour, $min, $sec, $month, $day, $year);
            }
            $time = gmdate('Y-m-d H:i:s', $time);

            // A new time has been found! Get the new uploads folder:
            // A writable uploads dir will pass this test. Again, there's no point overriding this one.
            if (!( ( $uploads = wp_upload_dir($time) ) && false === $uploads['error'] ))
                return new WP_Error('upload_error', $uploads['error']);
            $url = $uploads['baseurl'] . $mat[1];
        }
    } else {
        $filename = wp_unique_filename($uploads['path'], basename($file));
        // copy the file to the uploads dir
        $new_file = $uploads['path'] . '/' . $filename;
        if (false === @copy($file, $new_file))
            return new WP_Error('upload_error', sprintf(__('The selected file could not be copied to %s.', 'colorway'), $uploads['path']));

        // Set correct file permissions
        $stat = stat(dirname($new_file));
        $perms = $stat['mode'] & 0000666;
        @ chmod($new_file, $perms);
        // Compute the URL
        $url = $uploads['url'] . '/' . $filename;

        if ('file' == $import_date)
            $time = gmdate('Y-m-d H:i:s', @filemtime($file));
    }

    //Apply upload filters
    $return = apply_filters('wp_handle_upload', array('file' => $new_file, 'url' => $url, 'type' => $type));
    $new_file = $return['file'];
    $url = $return['url'];
    $type = $return['type'];

    $title = preg_replace('!\.[^.]+$!', '', basename($file));
    $content = '';

    if ($time) {
        $post_date_gmt = $time;
        $post_date = $time;
    } else {
        $post_date = current_time('mysql');
        $post_date_gmt = current_time('mysql', 1);
    }

    // Construct the attachment array
    $attachment = array(
        'post_mime_type' => $type,
        'guid' => $url,
        'post_parent' => $post_id,
        'post_title' => $title,
        'post_name' => $title,
        'post_content' => $content,
        'post_date' => $post_date,
        'post_date_gmt' => $post_date_gmt
    );

    $attachment = apply_filters('afs-import_details', $attachment, $file, $post_id, $import_date);

    //Win32 fix:
    $new_file = str_replace(strtolower(str_replace('\\', '/', $uploads['basedir'])), $uploads['basedir'], $new_file);

    // Save the data

    $id = wp_insert_attachment($attachment, $new_file, $post_id);
    if (!is_wp_error($id)) {
        $data = wp_generate_attachment_metadata($id, $new_file);
        wp_update_attachment_metadata($id, $data);
    }
    //update_post_meta( $id, '_wp_attached_file', $uploads['subdir'] . '/' . $filename );

    return $id;
}

ob_clean();
?>