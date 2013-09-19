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
 * Admin Menu Manager
 *
 * @package AAM
 * @subpackage Model
 */
class mvb_Model_Manager_Menu {

    /**
     *
     * @global array $submenu
     * @param string $tmpl
     * @param mvb_Model_Manager $parent
     * @return string
     */
    public static function render($tmpl, $parent) {
        global $submenu;

        $sorted_menu = $parent->getRoleMenu();
        $item_tmpl = mvb_Model_Template::retrieveSub(
                        'MAIN_MENU_ITEM', $tmpl
        );
        $sublist_tmpl = mvb_Model_Template::retrieveSub(
                        'MAIN_MENU_SUBLIST', $item_tmpl
        );
        $subitem_tmpl = mvb_Model_Template::retrieveSub(
                        'MAIN_MENU_SUBITEM', $sublist_tmpl
        );
        $list = '';

        foreach ($sorted_menu as $menu) {
            if (self::isSeperator($menu)) {
                continue;
            }
            //render submenu
            $sub_list = '';
            if (isset($submenu[$menu[2]]) && is_array($submenu[$menu[2]])) {
                foreach ($submenu[$menu[2]] as $sub_menu) {
                    $markers = array(
                        '###submenu_name###' => (mvb_Model_Helper::removeHTML($sub_menu[0])),
                        '###value###' => $sub_menu[2],
                        '###checked###' => ($parent->getConfig()
                                ->hasSubMenu($menu[2], $sub_menu[2]) ? 'checked' : '')
                    );
                    $sub_list .= mvb_Model_Template::updateMarkers(
                                    $markers, $subitem_tmpl
                    );
                }
                $sub_list = mvb_Model_Template::replaceSub(
                                'MAIN_MENU_SUBITEM', $sub_list, $sublist_tmpl
                );
            }

            $temp = mvb_Model_Template::replaceSub(
                            'MAIN_MENU_SUBLIST', $sub_list, $item_tmpl
            );

            $whole = $parent->getConfig()->getMenu($menu[2]);
            $markers = array(
                '###name###' => (mvb_Model_Helper::removeHTML($menu[0])),
                '###id###' => $menu[5],
                '###menu###' => $menu[2],
                '###whole_checked###' => (isset($whole['whole']) ? 'checked' : '')
            );
            $list .= mvb_Model_Template::updateMarkers($markers, $temp);
        }

        return mvb_Model_Template::replaceSub('MAIN_MENU_ITEM', $list, $tmpl);
    }

    /**
     *
     * @param array $menu
     * @return boolean
     */
    public static function isSeperator($menu) {

        return (isset($menu[0]) && $menu[0] ? FALSE : TRUE);
    }

}

?>