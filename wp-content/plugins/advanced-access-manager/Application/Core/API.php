<?php

/**
 * ======================================================================
 * LICENSE: This file is subject to the terms and conditions defined in *
 * file 'license.txt', which is part of this source code package.       *
 * ======================================================================
 */

/**
 * AAM core API
 * 
 * @package AAM
 * @author Vasyl Martyniuk <vasyl@vasyltech.com>
 */
final class AAM_Core_API {

    /**
     * Get current blog's option
     *
     * @param string $option
     * @param mixed  $default
     * @param int    $blog_id
     *
     * @return mixed
     *
     * @access public
     * @static
     */
    public static function getOption($option, $default = FALSE, $blog_id = null) {
        if (is_multisite()) {
            $blog = (is_null($blog_id) ? get_current_blog_id() : $blog_id);
            $response = get_blog_option($blog, $option, $default);
        } else {
            $response = get_option($option, $default);
        }

        return $response;
    }

    /**
     * Update Blog Option
     *
     * @param string $option
     * @param mixed  $data
     * @param int    $blog_id
     *
     * @return bool
     *
     * @access public
     * @static
     */
    public static function updateOption($option, $data, $blog_id = null) {
        if (is_multisite()) {
            $blog = (is_null($blog_id) ? get_current_blog_id() : $blog_id);
            $response = update_blog_option($blog, $option, $data);
        } else {
            $response = update_option($option, $data);
        }

        return $response;
    }

    /**
     * Delete Blog Option
     *
     * @param string $option
     * @param int    $blog_id
     * 
     * @return bool
     *
     * @access public
     * @static
     */
    public static function deleteOption($option, $blog_id = null) {
        if (is_multisite()) {
            $blog = (is_null($blog_id) ? get_current_blog_id() : $blog_id);
            $response = delete_blog_option($blog, $option);
        } else {
            $response = delete_option($option);
        }

        return $response;
    }

    /**
     * Initiate HTTP request
     *
     * @param string $url Requested URL
     * @param bool $send_cookies Wheather send cookies or not
     * 
     */
    public static function cURL($url, $send_cookies = TRUE) {
        $header = array(
            'User-Agent' => AAM_Core_Request::server('HTTP_USER_AGENT')
        );

        $cookies = array();
        if (is_array($_COOKIE) && $send_cookies) {
            foreach ($_COOKIE as $key => $value) {
                //SKIP PHPSESSID - some servers don't like it for security reason
                if ($key !== 'PHPSESSID') {
                    $cookies[] = new WP_Http_Cookie(array(
                        'name' => $key,
                        'value' => $value
                    ));
                }
            }
        }

        return wp_remote_request($url, array(
            'headers' => $header,
            'cookies' => $cookies,
            'timeout' => 5)
        );
    }
    
    /**
     * 
     * @global WP_Roles $wp_roles
     * 
     * @return \WP_Roles
     */
    public static function getRoles() {
        global $wp_roles;
        
        if (function_exists('wp_roles')) {
            $roles = wp_roles();
        } elseif(isset($wp_roles)) {
            $roles = $wp_roles;
        } else {
            $roles = $wp_roles = new WP_Roles();
        }
        
        return $roles;
    }
    
     /**
     * Reject the request
     *
     * Redirect or die the execution based on ConfigPress settings
     *
     * @return void
     *
     * @access public
     */
    public static function reject() {
        $redirect = AAM_Core_ConfigPress::get('frontend.access.deny.redirect');

        if (filter_var($redirect, FILTER_VALIDATE_URL)) {
            wp_redirect($redirect);
        } elseif (is_int($redirect)) {
            wp_redirect(get_post_permalink($redirect));
        } else {
            $message = AAM_Core_ConfigPress::get(
                'frontend.access.deny.message', __('Access Denied', AAM_KEY)
            );
            wp_die($message);
        }
        exit;
    }

}