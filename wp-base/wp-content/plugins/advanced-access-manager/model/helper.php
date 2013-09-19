<?php

/*
  Copyright (C) <2011>  Vasyl Martyniuk <martyniuk.vasyl@gmail.com>

  This program is free software: you can redistribute it and/or modify
  it under the terms of the GNU General Public License as published by
  the Free Software Foundation, either version 3 of the License, or
  (at your option) any later version.

  This program is distributed in the hope that it will be useful,
  but WITHOUT ANY WARRANTY; without even the implied warranty of
  MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
  GNU General Public License for more details.

  You should have received a copy of the GNU General Public License
  along with this program.  If not, see <http://www.gnu.org/licenses/>.

 */

/**
 * Helper Model Class
 *
 * @package AAM
 * @subpackage Models
 * @author Vasyl Martyniuk <martyniuk.vasyl@gmail.com>
 * @copyrights Copyright © 2011 Vasyl Martyniuk
 * @license GNU General Public License {@link http://www.gnu.org/licenses/}
 */
class mvb_Model_Helper {

    /**
     * Current Blog Object
     *
     * @var object
     */
    public static $current_blog;

    /**
     * User is Super Admin
     *
     * @var int
     */
    public static $is_super_admin = FALSE;

    /**
     * Skip term filtering
     *
     * @var type
     */
    public static $skip_term_filter = FALSE;

    /**
     * Holds all configuration options WPACCESS_PREFIX . 'options'
     *
     * @var array
     */
    public static $config_cache = array();

    /**
     * Make the lable shorter
     *
     * @param string $title
     * @return string
     */
    public static function shortTitle($title) {
        //TODO - not the best way
        if (strlen($title) > 30) {
            $title = substr($title, 0, 30) . '...';
        }

        return $title;
    }

    /**
     * Check what type of visibility current post has
     *
     * @global arra $wp_post_statuses
     * @param object $post
     * @return string
     */
    public static function checkVisibility($post) {
        global $wp_post_statuses;

        if (!empty($post->post_password)) {
            $visibility = __('Password Protected', 'aam');
        } elseif ($post->post_status == 'private') {
            $visibility = $wp_post_statuses['private']->label;
        } else {
            $visibility = __('Public', 'aam');
        }

        return $visibility;
    }

    public static function doRedirect() {
        if (is_admin()) {
            $redirect = mvb_Model_ConfigPress::getOption('backend.access.deny.redirect');
            $message = mvb_Model_ConfigPress::getOption('backend.access.deny.message');
        } else {
            $redirect = mvb_Model_ConfigPress::getOption('frontend.access.deny.redirect');
            $message = mvb_Model_ConfigPress::getOption('frontend.access.deny.message');
        }

        if (filter_var($redirect, FILTER_VALIDATE_URL)) {
            wp_redirect($redirect);
            exit;
        } elseif (is_int($redirect)) {
            wp_redirect(get_post_permalink($redirect));
            exit;
        }

        if (is_null($message)) {
            mvb_Model_Label::initGUILabels();
            $message = mvb_Model_Label::get('LABEL_127');
        }

        wp_die($message);
    }

    public static function multisiteApplyAll(){

        if (mvb_Model_API::isNetworkPanel()
                && (mvb_Model_ConfigPress::getOption('aam.multisite.apply_all') == 'true')){
            $result = TRUE;
        }else{
            $result = FALSE;
        }

        return $result;
    }

    /**
     * Check if AAM is Premium
     *
     * @access public
     * @return boolean
     */
    public static function isPremium() {

        if (defined('AAM_PREMIUM')) {
            $result = TRUE;
        } else {
            $result = FALSE;
        }

        return $result;
    }

    /**
     * Return Edit Post Link
     *
     * @param object $post
     * @return string
     */
    public static function editPostLink($post) {

        if (!$url = get_edit_post_link($post->ID))
            return;

        $st = self::shortTitle($post->post_title);
        $link = '<a href="' . $url . '" target="_blank" title="' . esc_attr($post->post_title) . '">' . $st . '</a>';

        return $link;
    }

    /**
     * Returst Edit Term Link
     *
     * @param object $term
     * @return string
     */
    public static function editTermLink($term) {

        $st = mvb_Model_Helper::shortTitle($term->name);
        $link = '<a href="' . get_edit_term_link($term->term_id, 'category') . '" target="_blank" title="' . esc_attr($term->name) . '">' . $st . '</a>';

        return $link;
    }

    /**
     * Initiate HTTP request
     *
     * @param string $url Requested URL
     * @param bool $send_cookies Wheather send cookies or not
     * @param bool $return_content Return content or not
     * @return bool Always return TRUE
     */
    public static function cURL($url, $send_cookies = TRUE, $return_content = FALSE) {
        $header = array(
            'User-Agent' => $_SERVER['HTTP_USER_AGENT']
        );

        $cookies = array();
        if (is_array($_COOKIE) && $send_cookies) {
            foreach ($_COOKIE as $key => $value) {
                //SKIP PHPSESSID - some servers does not like it for security reason
                if ($key == 'PHPSESSID') {
                    continue;
                }
                $cookies[] = new WP_Http_Cookie(array(
                            'name' => $key,
                            'value' => $value
                        ));
            }
        }

        $res = wp_remote_request($url, array(
            'headers' => $header,
            'cookies' => $cookies,
            'timeout' => 5)
        );

        if ($res instanceof WP_Error) {
            $result = array(
                'status' => 'error',
                'url' => $url
            );
        } else {
            $result = array('status' => 'success');
            if ($return_content) {
                $result['content'] = $res['body'];
            }
        }

        return $result;
    }

    /**
     * Get current Advanced Access Manager version
     *
     * @return string
     */
    public static function getCurrentVersion() {

        $plugins = get_plugins();

        if (isset($plugins[WPACCESS_DIRNAME . '/mvb_wp_access.php'])) {
            $version = $plugins[WPACCESS_DIRNAME . '/mvb_wp_access.php']['Version'];
        } else {
            $version = '1.0';
        }

        return $version;
    }

    /**
     * Get Highest User Level according to set of capabilities
     *
     * @param array $cap_list
     * @return int
     */
    public static function getHighestUserLevel($cap_list) {

        $highest = 0;
        for ($i = 0; $i <= WPACCESS_TOP_LEVEL; $i++) {
            if (isset($cap_list["level_{$i}"]) && ($highest < $i)) {
                $highest = $i;
            }
        }

        return $highest;
    }

    /**
     *
     * @param type $f_config
     * @param type $s_config
     * @return type
     */
    public static function isLowerLevel($f_config, $s_config) {

        $f = self::getHighestUserLevel($f_config->getCapabilities());
        $s = self::getHighestUserLevel($s_config->getCapabilities());

        return ($f < $s ? TRUE : FALSE);
    }

    /**
     *
     * @param type $name
     * @param type $method
     * @param type $default
     * @return type
     */
    public static function getParam($name, $method = 'GET', $default = FALSE) {

        $result = $default;
        switch ($method) {
            case 'GET':
                if (isset($_GET[$name])) {
                    $result = $_GET[$name];
                }
                break;

            case 'POST':
                if (isset($_POST[$name])) {
                    $result = $_POST[$name];
                }
                break;

            default:
                if (isset($_REQUEST[$name])) {
                    $result = $_REQUEST[$name];
                }
                break;
        }

        return $result;
    }

    /**
     * Make a string shorter
     *
     * @param string $str
     * @param int $length
     * @param string $add
     * @return string
     */
    public static function cutStr($str, $length, $add = '...') {

        if (is_string($str)) {
            $str = (strlen($str) > $length ? substr($str, 0, $length) . $add : $str);
        }

        return $str;
    }

    /**
     * Merge unlimited number or arrays recursively
     * It works the same way as array_merge_recursive but with one exception
     * it does not overwrite the integer keys
     *
     * @return array
     */
    public static function array_merge_recursive() {

        $arrays = func_get_args();
        $base = array_shift($arrays);

        foreach ($arrays as $array) {
            reset($base); //important
            while (list($key, $value) = @each($array)) {
                if (is_array($value) && @is_array($base[$key])) {
                    $base[$key] = self::array_merge_recursive($base[$key], $value);
                } else {
                    $base[$key] = $value;
                }
            }
        }

        return $base;
    }

    /**
     * Get list of sites if multisite setup
     *
     * @return mixed Array of sites of FALSE if not mulitisite setup
     */
    public static function getSiteList() {
        global $wpdb;

        if (isset($wpdb->blogs)) {
            $query = "SELECT * FROM {$wpdb->blogs}";
            $sites = $wpdb->get_results($query);
        } else {
            $sites = FALSE;
        }

        return $sites;
    }

    /**
     * Get list of site to Apply settings for
     *
     * @access public
     * @return array
     */
    public static function getApplySiteList($current_id = NULL){

        $limit = apply_filters(WPACCESS_PREFIX . 'msar_restrict_limit', WPACCESS_APPLY_LIMIT);
        $current = ($current_id ? $current_id : self::getParam('site'));
        $list = array();
        foreach(mvb_Model_Helper::getSiteList() as $i => $site){
            if ($site->blog_id == $current){
                continue;
            }elseif(($i + 1 >= WPACCESS_APPLY_LIMIT) && ($limit != -1)){
                $list[] = (object) array('blog_id' => 'error');
                break;
            }
            $list[] = $site;
        }

        return $list;
    }

    public static function getUserList($role) {

        $args = array(
            'number' => '',
            'blog_id' => mvb_Model_API::getCurrentBlog()->getID(),
            'role' => $role,
            'fields' => 'all',
            'orderby' => 'user_nicename',
            'order' => 'ASC'
        );

        // Query the user IDs for this page
        $wp_user_search = new WP_User_Query($args);
        $result = $wp_user_search->get_results();
        if (!is_array($result)) {
            $result = array();
        }

        return $result;
    }

    /**
     * Return label of given capability
     *
     * @todo Probably not the best place to keep this function here
     * @param string $cap
     * @return string
     */
    public static function getHumanTitle($cap) {

        $title = array();
        $parts = preg_split('/_/', $cap);
        if (is_array($parts)) {
            foreach ($parts as &$part) {
                $part = ucfirst($part);
            }
        }

        return implode(' ', $parts);
    }

    /**
     *
     * @global object $wpdb
     * @param type $term_id
     * @return type
     */
    public static function getTaxonomyByTerm($term_id) {
        global $wpdb;

        if ($term_id) {
            $query = "SELECT taxonomy FROM {$wpdb->term_taxonomy} ";
            $query .= "WHERE term_id = {$term_id}";
            $result = $wpdb->get_var($query);
        } else {
            $result = FALSE;
        }

        return $result;
    }

    /**
     * Get Current Post ID from REQUEST
     *
     * @access public
     * @return int
     */
    public static function getCurrentPostID() {

        if (isset($_GET['post'])) {
            $post_id = (int) $_GET['post'];
        } elseif (isset($_POST['post_ID'])) {
            $post_id = (int) $_POST['post_ID'];
        } else {
            $post_id = 0;
        }

        return $post_id;
    }

    /**
     * Clear unpropriate text
     *
     * @param string $text
     * @return string
     */
    public static function removeHTML($text) {
        $text = preg_replace(
                array(
            "'<span[^>]*?>.*?</span[^>]*?>'si",
                ), '', $text);

        $text = preg_replace('/<a[^>]*href[[:space:]]*=[[:space:]]*["\']?[[:space:]]*javascript[^>]*/i', '', $text);

        // Return clean content
        return $text;
    }

    public static function triggerNotice($message){

        echo '<div id="mvb-warning" class="updated"><p>',
                '<strong>' , $message , '</strong></p></div>';
    }

}

?>