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
 * Capabilities Manager
 *
 * @package AAM
 * @subpackage Model
 */
class mvb_Model_Manager_Capability {

    protected static $groups = array(
        'system' => array(
            'level_0', 'level_1', 'level_2', 'level_3', 'level_4', 'level_5',
            'level_6', 'level_7', 'level_8', 'level_9', 'level_10'
        ),
        'post' => array(
            'delete_others_pages', 'delete_others_posts', 'delete_pages',
            'delete_posts', 'delete_private_pages', 'delete_private_posts',
            'delete_published_pages', 'delete_published_posts', 'edit_others_pages',
            'edit_others_posts', 'edit_pages', 'edit_private_posts',
            'edit_private_pages', 'edit_posts', 'edit_published_pages',
            'edit_published_posts', 'publish_pages', 'publish_posts', 'read',
            'read_private_pages', 'read_private_posts', 'edit_permalink'
        ),
        'comment' => array(
            'delete_comment', 'approve_comment', 'edit_comment', 'moderate_comments',
            'quick_edit_comment', 'spam_comment', 'reply_comment', 'trash_comment',
            'unapprove_comment', 'untrash_comment', 'unspam_comment',
        ),
        'backend' => array(
            'aam_manage', 'activate_plugins', 'add_users', 'create_users',
            'delete_users', 'delete_themes', 'edit_dashboard', 'edit_files',
            'edit_plugins', 'edit_theme_options', 'edit_themes', 'edit_users',
            'export', 'import', 'install_plugins', 'install_themes', 'list_users',
            'manage_options', 'manage_links', 'manage_categories', 'promote_users',
            'unfiltered_html', 'unfiltered_upload', 'update_themes', 'update_plugins',
            'update_core', 'upload_files', 'delete_plugins', 'remove_users',
            'switch_themes'
        )
    );

    private static $premium_caps = array(
        'approve_comment', 'spam_comment', 'delete_comment', 'trash_comment'
    );

    /**
     *
     * @global array $submenu
     * @param string $tmpl
     * @param mvb_Model_Manager $parent
     * @return string
     */
    public static function render($tmpl, $parent) {

        $all_caps = mvb_Model_API::getAllCapabilities();
        $list = '';
        if (is_array($all_caps) && count($all_caps)) {
            ksort($all_caps);
            $list_tmpl = mvb_Model_Template::retrieveSub(
                            'CAPABILITY_LIST', $tmpl
            );
            $row_tmpl = mvb_Model_Template::retrieveSub(
                            'CAPABILITY_ROW', $list_tmpl
            );
            $item_tmpl = mvb_Model_Template::retrieveSub(
                            'CAPABILITY_ITEM', $row_tmpl
            );
            $conf = mvb_Model_ConfigPress::getOption('aam.delete_capabilities');
            $allow_delete = ($conf == 'true' ? TRUE : FALSE);

            $grouped_list = array(
                'Post & Page' => array(),
                'Comment' => array(),
                'Backend Interface' => array(),
                'System' => array(),
                'Miscelaneous' => array()
            );

            foreach ($all_caps as $cap => $dumy) {
                if (in_array($cap, self::$groups['system'])) {
                    $grouped_list['System'][] = $cap;
                } elseif (in_array($cap, self::$groups['post'])) {
                    $grouped_list['Post & Page'][] = $cap;
                } elseif (in_array($cap, self::$groups['comment'])) {
                    $grouped_list['Comment'][] = $cap;
                } elseif (in_array($cap, self::$groups['backend'])) {
                    $grouped_list['Backend Interface'][] = $cap;
                } else {
                    $grouped_list['Miscelaneous'][] = $cap;
                }
            }
            foreach ($grouped_list as $group => $caps) {
                $item_list = '';
                if (count($caps)) {
                    foreach ($caps as $cap) {
                        $item_list .= self::renderRow($cap, $item_tmpl, $parent, $allow_delete);
                    }
                    $list .= mvb_Model_Template::updateMarkers(
                                    array('###group_title###' => __($group, 'aam')), mvb_Model_Template::replaceSub(
                                            'CAPABILITY_ITEM', $item_list, $row_tmpl
                                    )
                    );
                }
            }
            $content = mvb_Model_Template::replaceSub(
                            'CAPABILITY_ROW', $list, $list_tmpl
            );
            $content = mvb_Model_Template::replaceSub(
                            'CAPABILITY_LIST', $content, $tmpl
            );
            $content = mvb_Model_Template::replaceSub(
                            'CAPABILITY_LIST_EMPTY', '', $content
            );
        } else {
            $empty = mvb_Model_Template::retrieveSub(
                            'CAPABILITY_LIST_EMPTY', $tmpl
            );
            $content = mvb_Model_Template::replaceSub(
                            'CAPABILITY_LIST', '', $tmpl
            );
            $content = mvb_Model_Template::replaceSub(
                            'CAPABILITY_LIST_EMPTY', $empty, $content
            );
        }

        return $content;
    }

    public static function renderRow($cap, $tmpl, $parent, $allow_delete) {
        $title = mvb_Model_Helper::getHumanTitle($cap);
        $markers = array(
            '###title###' => $cap,
            '###premium###' => self::isPremium($cap),
            '###checked###' => ($parent->getConfig()->hasCapability($cap) ? 'checked' : ''),
            '###cap_name###' => mvb_Model_Helper::cutStr($title, 22),
            '###cap_name_full###' => $title
        );
        $content = mvb_Model_Template::updateMarkers($markers, $tmpl);
        if ($allow_delete) {
            $del_tmpl = mvb_Model_Template::retrieveSub(
                            'CAPABILITY_DELETE', $content
            );
            $content = mvb_Model_Template::replaceSub(
                            'CAPABILITY_DELETE', $del_tmpl, $content
            );
        } else {
            $content = mvb_Model_Template::replaceSub(
                            'CAPABILITY_DELETE', '', $content
            );
        }

        return $content;
    }

    private static function isPremium($capability){

        return (in_array($capability, self::$premium_caps) && !mvb_Model_Helper::isPremium() ? 'premium' : '');
    }

}

?>