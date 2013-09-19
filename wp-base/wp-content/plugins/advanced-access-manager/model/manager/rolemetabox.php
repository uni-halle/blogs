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
 * Role Metabox Manager
 *
 * @package AAM
 * @subpackage Model
 */
class mvb_Model_Manager_RoleMetabox {

    public static $user_summary;
    /**
     *
     * @global array $submenu
     * @param string $tmpl
     * @param mvb_Model_Manager $parent
     * @return string
     */
    public static function render($tmpl, $parent) {

        self::$user_summary = count_users();
        $list_tmpl = mvb_Model_Template::retrieveSub('DELETE_ROLE_LIST', $tmpl);
        $item_tmpl = mvb_Model_Template::retrieveSub('DELETE_ROLE_ITEM', $list_tmpl);
        $list = '';
        foreach (mvb_Model_API::getRoleList() as $role => $data) {
            $list .= self::renderRoleRow($role, $data, $item_tmpl);
        }
        $list = mvb_Model_Template::replaceSub(
                'DELETE_ROLE_ITEM', $list, $list_tmpl
        );

        return mvb_Model_Template::replaceSub('DELETE_ROLE_LIST', $list, $tmpl);
    }

    public static function renderRoleRow($role, $data, $tmpl) {

        $count = 0;
        if (isset(self::$user_summary['avail_roles'][$role])){
            $count = self::$user_summary['avail_roles'][$role];
        }

        $markers = array(
            '###role_id###' => esc_js($role),
            '###role_name###' => (stripcslashes($data['name'])),
            '###count###' => $count,
        );
        if ($count) {
            $content = mvb_Model_Template::replaceSub(
                    'DELETE_ROLE_BUTTON', '', $tmpl
            );
        } else {
            $delete_tmpl = mvb_Model_Template::retrieveSub(
                    'DELETE_ROLE_BUTTON', $tmpl
            );
            $content = mvb_Model_Template::replaceSub(
                    'DELETE_ROLE_BUTTON', $delete_tmpl, $tmpl
            );
        }

        return mvb_Model_Template::updateMarkers($markers, $content);
    }

}

?>