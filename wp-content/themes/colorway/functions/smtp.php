<?php

$parse_uri = explode('wp-content', $_SERVER['SCRIPT_FILENAME']);
require_once( $parse_uri[0] . 'wp-load.php' );

global $current_user;
$user_id = $current_user->ID;

if (isset($_GET["wp_email_tracking"]) && $_GET["wp_email_tracking"] == "email_smtp_allow_tracking") {
    $email = get_option("admin_email");
    if (isset($email) && $email != "") {
        if (isset($current_user->user_firstname) && $current_user->user_firstname != '') {
            $name = $current_user->user_firstname . ' ' . $current_user->user_lastname;
        } elseif (isset($current_user->user_login) && $current_user->user_login != '') {
            $name = $current_user->user_login;
        } else {
            $name = '';
        }

        $mg_api_key = "EsT96nYTlxED";
        /**
         * Mailget file File
         */
        require_once ('mailget_curl.php');

        $list_arr = array();
        $mg_obj = new mailget_curl($mg_api_key);
        $mg_arr = array(array(
                'name' => esc_attr($name),
                'email' => sanitize_email($email),
                'get_date' => date('j-m-y'),
                'ip' => ''
            )
        );
        $curt_status = $mg_obj->curl_data($mg_arr, "Ijc2MDUzNSI_3D", 'single');
    }
    add_user_meta($user_id, 'wp_email_tracking_ignore_notice', 'true', true);
    $url = get_option('siteurl') . '/wp-admin/index.php';
    wp_redirect($url);
}

if (isset($_GET["wp_email_tracking"]) && $_GET["wp_email_tracking"] == "email_smtp_hide_tracking") {
    add_user_meta($user_id, 'wp_email_tracking_ignore_notice', 'true', true);
    $url = get_option('siteurl') . '/wp-admin/index.php';
    wp_redirect($url);
}
