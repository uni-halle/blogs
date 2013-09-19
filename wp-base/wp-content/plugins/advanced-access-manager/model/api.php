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
 * External API Class for AAM
 *
 * @package AAM
 * @subpackage Models
 * @author Vasyl Martyniuk <martyniuk.vasyl@gmail.com>
 * @copyrights Copyright © 2011 Vasyl Martyniuk
 * @license GNU General Public License {@link http://www.gnu.org/licenses/}
 */
final class mvb_Model_API {

    /**
     * Cache current blog object
     *
     * @access protected
     * @var object
     */
    protected static $current_blog;

    /**
     * Cache Role List
     *
     * @access protected
     * @var array
     */
    protected static $role_cache;

    /**
     * Current User's Object
     *
     * @access protected
     * @var object
     */
    protected static $current_user;

    /**
     * Role Config Cache
     *
     * @access protected
     * @var array
     */
    protected static $roleconfig_cache = array();

    /**
     * Clear cache
     */
    public static function clearCache() {

        self::$current_user = NULL;
        self::$roleconfig_cache = array();
    }

    /**
     * Check if is multisite network panel is used now
     *
     * @see is_multisite(), is_network_admin()
     * @since version 1.5.5
     * @return bool
     */
    public static function isNetworkPanel() {

        return (is_multisite() && is_network_admin() ? TRUE : FALSE);
    }

    /**
     * Check if user is super admin
     *
     * @since version 1.5.5
     * @param int $user_id
     * @return bool
     */
    public static function isSuperAdmin($user_id = FALSE) {

        $user_id = ($user_id ? $user_id : get_current_user_id());

        if (mvb_Model_API::isNetworkPanel()) {
            $super = (is_super_admin($user_id) ? TRUE : FALSE);
        } else {
            $super = (self::getCurrentUser()->has_cap(WPACCESS_SADMIN_ROLE) ? TRUE : FALSE);
        }

        return $super;
    }

    /**
     * Get Blog information
     *
     * If it is not a multisite setup will return default blog object
     *
     * @global object $wpdb
     * @param init $blog_id
     * @return object Return object mvb_Model_Blog or FALSE if blog not found
     */
    public static function getBlog($blog_id) {
        global $wpdb;

        $blog = FALSE;

        if (is_multisite()) {
            $query = "SELECT * FROM {$wpdb->blogs} WHERE blog_id = %d";
            $query = $wpdb->prepare($query, $blog_id);
            $data = $wpdb->get_row($query);

            if ($data) {
                $blog = new mvb_Model_Blog(array(
                            'id' => $data->blog_id,
                            'url' => get_site_url($data->blog_id),
                            'prefix' => $wpdb->get_blog_prefix($data->blog_id))
                );
            } else {
                Throw new Exception('Blog with ID ' . $blog_id . ' does not exist');
            }
        } else {
            $blog = new mvb_Model_Blog(array(
                        'id' => ($blog_id ? $blog_id : 1),
                        'url' => site_url(),
                        'prefix' => $wpdb->prefix)
            );
        }

        return $blog;
    }

    /**
     * Get Current Blog info
     *
     * @return object
     */
    public static function getCurrentBlog() {

        if (!self::$current_blog) {
            self::$current_blog = self::getBlog(get_current_blog_id());
        }

        return self::$current_blog;
    }

    /**
     * Set current blog
     *
     * @param int $blog_id
     * @return bool
     */
    public static function setCurrentBlog($blog_id) {

        if ($blog = self::getBlog($blog_id)) {
            self::$current_blog = $blog;
        }

        return ($blog ? TRUE : FALSE);
    }

    /**
     * Get current blog's option
     *
     * Check if multisite and execute a proper WP function to get option from DB
     *
     * @global object $wpdb
     * @param string $option
     * @param mixed $default
     * @param object $blog
     * @return mixed
     */
    public static function getBlogOption($option, $default = FALSE, $blog = FALSE) {
        global $wpdb;

        if (is_multisite()) {
            if (!($blog instanceof mvb_Model_Blog)) { //user current blog
                $blog = self::getCurrentBlog();
            }
            $result = get_blog_option($blog->getID(), $blog->getPrefix() . $option, $default);
        } else {
            $result = get_option($wpdb->prefix . $option, $default);
        }

        return $result;
    }

    /**
     * Get User Access Config Object
     *
     * @param int $user_id
     * @param array $force_roles
     * @return object
     */
    public static function getUserAccessConfig($user_id, $force_roles = FALSE) {

        $config = mvb_Model_Cache::getCacheData('user', $user_id);

        if ($force_roles || !$config) {

            $config = new mvb_Model_UserConfig($user_id);

            if (!$config->getID()) { //user is logged in
                $config = new mvb_Model_RoleConfig('_visitor');
            } else {
                if (is_array($force_roles)) {
                    $role_list = $force_roles;
                } else {
                    $role_list = $config->getUser()->getRoles();
                }
                //get first role and use as base
                //TODO - probably implement multirole support
                $r_config = mvb_Model_API::getRoleAccessConfig(array_shift($role_list));

                if (!count($config->getMenu())) {
                    $config->setMenu($r_config->getMenu());
                }

                if (!count($config->getMetaboxes())) {
                    $config->setMetaboxes($r_config->getMetaboxes());
                }

                if (!count($config->getMenuOrder())) {
                    $config->setMenuOrder($r_config->getMenuOrder());
                }

                if (!count($config->getCapabilities())) {
                    $config->setCapabilities($r_config->getCapabilities());
                }

                //merge restrictions
                $role_restrs = $r_config->getRestrictions();
                if (count($role_restrs)) {
                    if (isset($role_restrs['post'])) {
                        foreach ($role_restrs['post'] as $id => $data) {
                            if (!$config->hasRestriction('post', $id)) {
                                $config->addRestriction('post', $id, $data);
                            }
                        }
                    }
                    if (isset($role_restrs['taxonomy'])){
                        foreach ($role_restrs['taxonomy'] as $id => $data) {
                            if (!$config->hasRestriction('taxonomy', $id)) {
                                $config->addRestriction('taxonomy', $id, $data);
                            }
                        }
                    }
                }

                if (!$force_roles) {
                    mvb_Model_Cache::saveCacheData('user', $user_id, $config);
                }
            }
        }

        return $config;
    }

    /**
     * Get User Role configuration
     *
     * @param object $conf
     * @param string $role
     */
    public static function getRoleAccessConfig($role) {

        if (!isset(self::$roleconfig_cache[$role])) {
            self::$roleconfig_cache[$role] = new mvb_Model_RoleConfig($role);
        }

        return self::$roleconfig_cache[$role];
    }

    /**
     * Get list of User Roles
     *
     * Depending on $all parameter it'll return whole list of roles or filtered
     *
     * @global object $wpdb
     * @param bool $filter
     * @return array
     */
    public static function getRoleList($filter = TRUE) {
        global $wpdb;

        $roles = self::getBlogOption('user_roles', array());

        if ($filter) {
            //unset super admin role
            if (isset($roles[WPACCESS_SADMIN_ROLE])) {
                unset($roles[WPACCESS_SADMIN_ROLE]);
            }

            if (!mvb_Model_API::isSuperAdmin() && isset($roles[WPACCESS_ADMIN_ROLE])) {
                //exclude Administrator from list of allowed roles
                unset($roles[WPACCESS_ADMIN_ROLE]);
            }
        }

        return $roles;
    }

    /**
     * Get User Role by ID
     *
     * @access public
     * @param string $id
     * @return boolean|array
     */
    public static function getRole($id){

        $role_list = self::getRoleList(FALSE);
        if (isset($role_list[$id])){
            $result = $role_list[$id];
        }else{
            $result = FALSE;
        }

        return $result;
    }

    /**
     * Get list of all capabilities in the system
     *
     * @return type
     */
    public static function getAllCapabilities() {

        $cap_list = array();

        foreach (self::getRoleList(FALSE) as $role => $data) {
            if (isset($data['capabilities']) && is_array($data['capabilities'])){
                $cap_list = array_merge($cap_list, $data['capabilities']);
            }
        }

        if (isset($cap_list[WPACCESS_SADMIN_ROLE])) {
            unset($cap_list[WPACCESS_SADMIN_ROLE]);
        }

        return $cap_list;
    }

    /**
     * Update Blog Option
     *
     * If $blog is not specified, will user current blog
     *
     * @global object $wpdb
     * @param string $option
     * @param mixed $data
     * @param object $blog
     * @return bool
     */
    public static function updateBlogOption($option, $data, $blog = FALSE) {
        global $wpdb;

        if (is_multisite()) {
            if ($blog === FALSE) { //user current blog
                $blog = self::getCurrentBlog();
            }
            $result = update_blog_option($blog->getID(), $blog->getPrefix() . $option, $data);
        } else {
            $result = update_option($wpdb->prefix . $option, $data);
        }

        return $result;
    }

    /**
     * Delete Blog Option
     *
     * @global object $wpdb
     * @param string $option
     * @param object $blog
     * @return bool
     */
    public static function deleteBlogOption($option, $blog = FALSE) {
        global $wpdb;

        if (is_multisite()) {
            if ($blog === FALSE) { //user current blog
                $blog = self::getCurrentBlog();
            }
            $result = delete_blog_option($blog->getID(), $blog->getPrefix() . $option);
        } else {
            $result = delete_option($wpdb->prefix . $option);
        }

        return $result;
    }

    /**
     * Get current User Role
     *
     * This function is for web developers how are developing own component for
     * Advaced Access Manager. It'll return current User Role
     *
     * @return string
     */
    public static function getCurrentEditableUserRole() {

        if (isset($_REQUEST['current_role'])) {
            $c_role = $_REQUEST['current_role'];
        } else {
            //TODO - Don't like $role_cache
            $roles = (self::$role_cache ? self::$role_cache : self::getRoleList());
            $t_list = array_keys($roles);
            $c_role = $t_list[0];
        }

        return $c_role;
    }

    /**
     *
     * @return boolean
     */
    public static function getCurrentEditableUser() {

        if (isset($_REQUEST['current_user'])) {
            $c_user = $_REQUEST['current_user'];
        } else {
            $c_user = FALSE;
        }

        return $c_user;
    }

    /**
     * Return User Role List
     *
     * @param int $user_id
     * @return array
     */
    public static function getUserRoleList($user_id = FALSE) {

        return self::getCurrentUser()->getRoles();
    }

    /**
     * Get Current User
     *
     * @return mvb_Model_User
     */
    public static function getCurrentUser() {

        if (!self::$current_user) {
            self::$current_user = new mvb_Model_User(get_current_user_id());
        }

        return self::$current_user;
    }

}

?>