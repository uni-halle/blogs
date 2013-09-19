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
 * Upgrade functionality
 *
 * @package AAM
 * @subpackage Models
 * @author Vasyl Martyniuk <martyniuk.vasyl@gmail.com>
 * @copyright Copyright © 2011 Vasyl Martyniuk
 * @license GNU General Public License {@link http://www.gnu.org/licenses/}
 */
class mvb_Model_Upgrade {

    /**
     * Main function
     * Check if upgrade is required
     *
     * @access public
     * @return boolean
     */
    public static function doUpgrade() {

        $updated = FALSE;
        $db_v = mvb_Model_API::getBlogOption(WPACCESS_PREFIX . 'version', NULL);
        if (!function_exists('get_plugin_data')) {
            require_once(ABSPATH . 'wp-admin/includes/plugin.php');
        }
        $cr_v = get_plugin_data(WPACCESS_BASE_DIR . 'mvb_wp_access.php');

        if ($db_v != $cr_v['Version']) { //do upgrade
            self::transferConfigPress();

            if (is_null($db_v)) {
                $updated = self::upgradeTo1652();
            } elseif ($db_v < '1.6.5') {
                $updated = self::upgradeTo165();
            } elseif ($db_v < '1.6.5.2') {
                $updated = self::upgradeTo1652();
            } elseif ($db_v < '1.6.6') {
                $updated = self::upgradeTo166();
            }else{
                $updated = true;
            }

            self::setVersion($cr_v['Version']);
        }

        return $updated;
    }

    protected static function setVersion($version) {

        mvb_Model_API::updateBlogOption(WPACCESS_PREFIX . 'version', $version);
    }

    /**
     * Transfer ConfigPress settings
     *
     * @access protected
     */
    protected static function transferConfigPress() {

        //transfer ConfigPress first
        $blog = mvb_Model_API::getBlog(1);
        $config = mvb_Model_API::getBlogOption(
                        WPACCESS_PREFIX . 'config_press', '', $blog
        );
        mvb_Model_ConfigPress::saveConfig($config);
    }

    /**
     * Add some additional capabilities
     *
     * @access protected
     * @param array $cap_list
     * @todo - Does not support multisite
     */
    protected static function extendCapabilities($cap_list) {

        $roles = mvb_Model_API::getRoleList(FALSE);
        if (isset($roles[WPACCESS_SADMIN_ROLE])) {
            $roles[WPACCESS_SADMIN_ROLE]['capabilities'] = array_merge(
                    $roles[WPACCESS_SADMIN_ROLE]['capabilities'], $cap_list
            );
        }
        $roles[WPACCESS_ADMIN_ROLE]['capabilities'] = array_merge(
                $roles[WPACCESS_ADMIN_ROLE]['capabilities'], $cap_list
        );
        mvb_Model_API::updateBlogOption('user_roles', $roles);
    }

    //***************************************************************
    //=================== UPGRADE TO RELEASE 1.6.5 ==================
    //***************************************************************

    protected static function upgradeTo165() {
        global $wpdb;

        //add custom capabilities
        self::extendCapabilities(array(
            'edit_comment' => 1,
            'approve_comment' => 1,
            'unapprove_comment' => 1,
            'reply_comment' => 1,
            'quick_edit_comment' => 1,
            'spam_comment' => 1,
            'unspam_comment' => 1,
            'trash_comment' => 1,
            'untrash_comment' => 1,
            'delete_comment' => 1,
            'edit_permalink' => 1)
        );
        $roles = mvb_Model_API::getRoleList(FALSE);

        //upgrade Restrictions - Roles first
        foreach ($roles as $role => $dummy) {
            $config = mvb_Model_API::getBlogOption(WPACCESS_PREFIX . 'config_' . $role);
            if (!$config) {
                $options = (object) self::getRoleOldData($role, WPACCESS_PREFIX . 'options');
                $m_order = self::getRoleOldData($role, WPACCESS_PREFIX . 'menu_order');
                $restric = self::getRoleOldData($role, WPACCESS_PREFIX . 'restrictions');
                $exclude = self::getExcludeList($restric);
                $config = (object) array();
                $config->menu = (isset($options->menu) ? $options->menu : array());
                $config->metaboxes = (isset($options->metaboxes) ? $options->metaboxes : array());
                $config->menu_order = (is_array($m_order) ? $m_order : array());
                $config->restrictions = (is_array($restric) ? $restric : array());
                $config->excludes = (is_array($exclude) ? $exclude : array());
            } else {
                $config->excludes = array();
            }

            $up_config = new stdClass();
            $up_config->menu = $config->menu;
            $up_config->metaboxes = $config->metaboxes;
            $up_config->menu_order = $config->menu_order;
            $up_config->restrictions = self::prepareRestrictions($config->restrictions, $config->excludes);
            mvb_Model_API::updateBlogOption(WPACCESS_PREFIX . 'config_' . $role, $up_config);
        }

        //update all users
        $query = "SELECT * FROM {$wpdb->usermeta} WHERE meta_key = '" . WPACCESS_PREFIX . "_config'";
        $results = $wpdb->get_results($query);
        if (is_array($results)) {
            foreach ($results as $user) {
                $config = unserialize($user->meta_value);
                if (!$config) {
                    $options = (object) self::getUserOldData($user->user_id, WPACCESS_PREFIX . 'options');
                    $m_order = self::getUserOldData($user->user_id, WPACCESS_PREFIX . 'menu_order');
                    $restric = self::getUserOldData($user->user_id, WPACCESS_PREFIX . 'restrictions');
                    $exclude = self::getExcludeList($restric);
                    $config = (object) array();
                    $config->menu = (isset($options->menu) ? $options->menu : array());
                    $config->metaboxes = (isset($options->metaboxes) ? $options->metaboxes : array());
                    $config->menu_order = (is_array($m_order) ? $m_order : array());
                    $config->restrictions = (is_array($restric) ? $restric : array());
                    $config->excludes = (is_array($exclude) ? $exclude : array());
                } else {
                    $config->excludes = array();
                }
                $up_config = new stdClass();
                $up_config->menu = $config->menu;
                $up_config->metaboxes = $config->metaboxes;
                $up_config->menu_order = $config->menu_order;
                $up_config->restrictions = self::prepareRestrictions($config->restrictions, $config->excludes);
                update_user_meta($user->user_id, WPACCESS_PREFIX . 'config', $up_config);
            }
        }
    }

    protected static function prepareRestrictions($old_list, $excludes) {

        $new_list = array();
        if (is_array($old_list)) {
            if (isset($old_list['posts'])) {
                $new_list['post'] = array();
                foreach ($old_list['posts'] as $post_id => $data) {
                    $new_list['post'][$post_id] = array();
                    if (isset($data['restrict']) && $data['restrict']) {
                        $new_list['post'][$post_id]['backend_post_list'] = 1;
                        $new_list['post'][$post_id]['backend_post_trash'] = 1;
                        $new_list['post'][$post_id]['backend_post_delete'] = 1;
                        $new_list['post'][$post_id]['backend_post_edit'] = 1;
                        $new_list['post'][$post_id]['backend_post_publish'] = 1;
                    }
                    if (isset($data['restrict_front']) && $data['restrict_front']) {
                        $new_list['post'][$post_id]['frontend_post_list'] = 1;
                        $new_list['post'][$post_id]['frontend_post_exclude'] = 1;
                        $new_list['post'][$post_id]['frontend_post_read'] = 1;
                        $new_list['post'][$post_id]['frontend_post_comment'] = 1;
                    }
                    if (isset($excludes[$post_id])) {
                        $new_list['post'][$post_id]['frontend_post_exclude'] = 1;
                    }
                }
            }
            if (isset($old_list['categories'])) {
                $new_list['taxonomy'] = array();
                foreach ($old_list['categories'] as $tax_id => $data) {
                    $new_list['taxonomy'][$tax_id] = array();
                    if (isset($data['restrict_front']) && $data['restrict_front']) {
                        $new_list['taxonomy'][$tax_id]['frontend_post_list'] = 1;
                        $new_list['taxonomy'][$tax_id]['frontend_list'] = 1;
                        $new_list['taxonomy'][$tax_id]['frontend_browse'] = 1;
                        $new_list['taxonomy'][$tax_id]['post_in_category'] = 1;
                    }
                    if (isset($data['restrict']) && $data['restrict']) {
                        $new_list['taxonomy'][$tax_id]['backend_post_list'] = 1;
                        $new_list['taxonomy'][$tax_id]['backend_post_trash'] = 1;
                        $new_list['taxonomy'][$tax_id]['backend_post_trash'] = 1;
                        $new_list['taxonomy'][$tax_id]['backend_post_delete'] = 1;
                        $new_list['taxonomy'][$tax_id]['backend_post_edit'] = 1;
                        $new_list['taxonomy'][$tax_id]['backend_post_publish'] = 1;
                        $new_list['taxonomy'][$tax_id]['backend_list'] = 1;
                        $new_list['taxonomy'][$tax_id]['backend_browse'] = 1;
                        $new_list['taxonomy'][$tax_id]['backend_edit'] = 1;
                        $new_list['taxonomy'][$tax_id]['post_in_category'] = 1;
                    }
                }
            }
        }

        return $new_list;
    }

    /**
     * Get Data from Database
     *
     * @param string $option
     * @return array
     * @todo Delete in next releases
     */
    protected static function getRoleOldData($role, $option) {

        $data = mvb_Model_API::getBlogOption($option);
        $data = ( isset($data[$role]) ? $data[$role] : array());

        return $data;
    }

    /**
     * Get Data from Database
     *
     * @param string $option
     * @return array
     * @todo Delete in next releases
     */
    protected static function getUserOldData($user, $option) {

        $data = get_user_meta($user, $option, TRUE);
        $data = ( is_array($data) ? $data : array());

        return $data;
    }

    /**
     * Get Exclude list from current configurations
     *
     * @access protected
     * @param array $restric
     * @return array
     */
    protected static function getExcludeList($restric) {

        $exclude = array();
        if (isset($restric['excludes']) && is_array($restric['excludes'])) {
            foreach ($restric['excludes'] as $post_id => $data) {
                $exclude[$post_id] = 1;
            }
        }

        return $exclude;
    }

    //***************************************************************
    //=================== UPGRADE TO RELEASE 1.6.5.2 ================
    //***************************************************************

    protected static function upgradeTo1652() {

        //add custom capabilities
        self::extendCapabilities(array(
            'edit_comment' => 1,
            'approve_comment' => 1,
            'unapprove_comment' => 1,
            'reply_comment' => 1,
            'quick_edit_comment' => 1,
            'spam_comment' => 1,
            'unspam_comment' => 1,
            'trash_comment' => 1,
            'untrash_comment' => 1,
            'delete_comment' => 1,
            'edit_permalink' => 1)
        );
    }

    //***************************************************************
    //=================== UPGRADE TO RELEASE 1.6.6 ==================
    //***************************************************************

    protected static function upgradeTo166() {

        self::upgradeTo1652();
    }

}