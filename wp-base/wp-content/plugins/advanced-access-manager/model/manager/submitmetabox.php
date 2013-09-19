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
 * Submit Metabox Manager
 *
 * @package AAM
 * @subpackage Model
 */
class mvb_Model_Manager_SubmitMetabox {

    public static $parent;

    /**
     *
     * @global array $submenu
     * @param string $tmpl
     * @param mvb_Model_Manager $parent
     * @return string
     */
    public static function render($tmpl, $parent) {

        self::$parent = $parent;

        $content = mvb_Model_Template::replaceSub(
                        'MULTISITE_SELECTOR', self::renderSiteSelector($tmpl), $tmpl
        );
        $content = mvb_Model_Template::replaceSub(
                        'ROLE_LIST', self::renderRoleSelector($content), $content
        );
        $content = mvb_Model_Template::replaceSub(
                        'USER_LIST', self::renderUserSelector($content, $parent), $content
        );

        $content = mvb_Model_Template::replaceSub(
                        'SYNC_MULTISITE', self::renderSync($content, $parent), $content
        );

        return $content;
    }

    public static function renderSync($tmpl){

         if (mvb_Model_API::isNetworkPanel()
                 || mvb_Model_ConfigPress::getOption('aam.multisite.complete_sync')) {
             $tmpl = mvb_Model_Template::retrieveSub('SYNC_MULTISITE', $tmpl);
         }else{
             $tmpl = '';
         }

         return $tmpl;
    }

    public static function renderSiteSelector($tmpl) {
        global $wpdb;

        $tmpl = mvb_Model_Template::retrieveSub('MULTISITE_SELECTOR', $tmpl);
        $content = '';
        $render_mss = mvb_Model_Helper::getParam('render_mss', 'REQUEST');
        if (mvb_Model_API::isNetworkPanel() || $render_mss) {
            $list_tmpl = mvb_Model_Template::retrieveSub('ROLE_LIST', $tmpl);
            $list = '';

            $sites = mvb_Model_Helper::getSiteList();
            $current = mvb_Model_Helper::getParam(
                            'site', 'REQUEST', get_current_blog_id()
            );
            if (is_array($sites)) {
                foreach ($sites as $site) {
                    $blog_prefix = $wpdb->get_blog_prefix($site->blog_id);
                    //get Site Name
                    $query = "SELECT option_value FROM {$blog_prefix}options ";
                    $query .= "WHERE option_name = 'blogname'";
                    $name = $wpdb->get_var($query);
                    if ($site->blog_id == $current) {
                        $is_current = 'selected';
                        $c_name = $name;
                    } else {
                        $is_current = '';
                    }
                    $markers = array(
                        '###value###' => $site->blog_id,
                        '###title###' => $name . '&nbsp;', //nicer view :)
                        '###selected###' => $is_current,
                    );
                    $list .= mvb_Model_Template::updateMarkers($markers, $list_tmpl);
                }
            }
            $content = mvb_Model_Template::replaceSub('ROLE_LIST', $list, $tmpl);
            $markers = array(
                '###current_site###' => mvb_Model_Helper::cutStr($c_name, 15),
                '###title_full###' => $c_name
            );
            $content = mvb_Model_Template::updateMarkers($markers, $content);
        }

        return $content;
    }

    public static function renderRoleSelector($tmpl) {

        $tmpl = mvb_Model_Template::retrieveSub('ROLE_LIST', $tmpl);
        $list = '';
        foreach (mvb_Model_API::getRoleList() as $role => $data) {
            $selected = (self::$parent->getCurrentRole() == $role ? 'selected' : '');
            $markers = array(
                '###value###' => $role,
                '###title###' => stripcslashes($data['name']) . '&nbsp;',
                '###selected###' => $selected,
            );
            $list .= mvb_Model_Template::updateMarkers($markers, $tmpl);
        }

        return $list;
    }

    public static function renderUserSelector($tmpl, $parent) {

        $tmpl = mvb_Model_Template::retrieveSub('USER_LIST', $tmpl);
        $list = '';
        $users = mvb_Model_Helper::getUserList($parent->getCurrentRole());
        $default = new stdClass();
        $default->ID = 0;
        $default->user_login = mvb_Model_Label::get('LABEL_120');
        array_unshift($users, $default);
        foreach ($users as $user) {
            $selected = ($parent->getCurrentUser() == $user->ID ? 'selected' : '');
            $markers = array(
                '###value###' => $user->ID,
                '###title###' => stripcslashes($user->user_login) . '&nbsp;',
                '###selected###' => $selected,
            );
            $list .= mvb_Model_Template::updateMarkers($markers, $tmpl);
        }

        return $list;
    }

}

?>