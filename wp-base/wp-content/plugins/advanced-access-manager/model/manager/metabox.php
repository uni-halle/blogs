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
 * Metabox & Widget Manager
 *
 * @package AAM
 * @subpackage Model
 */
class mvb_Model_Manager_Metabox {

    public static $parent;

    /**
     *
     * @global array $submenu
     * @param string $tmpl
     * @param mvb_Model_Manager $parent
     * @return string
     */
    public static function render($tmpl, $parent) {
        global $wp_post_types;

        self::$parent = $parent;
        //get cache. Compatible with version previouse versions
        $cache = mvb_Model_API::getBlogOption(WPACCESS_PREFIX . 'cache', NULL);
        if (!is_array($cache)) { //yeap this is new version
            $cache = mvb_Model_API::getBlogOption(
                    WPACCESS_PREFIX . 'options', array()
            );
            $cache = (isset($cache['settings']) ? $cache['settings'] : array());
            mvb_Model_API::updateBlogOption(WPACCESS_PREFIX . 'cache', $cache);
        }
        $row_tmpl = mvb_Model_Template::retrieveSub('METABOX_LIST_ITEM', $tmpl);
        $list = '';

        if (isset($cache['metaboxes']) && is_array($cache['metaboxes'])) {
            $list_tmpl = mvb_Model_Template::retrieveSub(
                            'POST_METABOXES_LIST', $row_tmpl
            );
            $item_tmpl = mvb_Model_Template::retrieveSub(
                            'POST_METABOXES_ITEM', $list_tmpl
            );
            $widget_tmpl = mvb_Model_Template::retrieveSub(
                            'WIDGET_ITEM', $list_tmpl
            );

            foreach ($cache['metaboxes'] as $type => $metaboxes) {

                $render = TRUE;
                switch ($type) {
                    case 'widgets':
                        $temp = self::renderWidget($widget_tmpl, $metaboxes);
                        $label = mvb_Model_Label::get('LABEL_79');
                        break;

                    case 'dashboard':
                        $temp = self::renderMetabox(
                                $item_tmpl, $metaboxes, $type
                        );
                        $label = mvb_Model_Label::get('LABEL_116');
                        break;

                    default:
                        if (isset($wp_post_types[$type])) {
                            $temp = self::renderMetabox(
                                    $item_tmpl, $metaboxes, $type
                            );
                            $label =  $wp_post_types[$type]->labels->name;
                        }else{
                            $render = FALSE;
                        }
                        break;
                }
                if ($render){
                    $temp = mvb_Model_Template::replaceSub(
                            'POST_METABOXES_LIST', $temp, $row_tmpl
                    );
                    $list .= mvb_Model_Template::updateMarkers(
                            array('###post_type_label###' => $label), $temp
                    );
                }
            }
            $content = mvb_Model_Template::replaceSub(
                    'METABOX_LIST_ITEM', $list, $tmpl
            );
            $content = mvb_Model_Template::replaceSub(
                    'METABOX_LIST_EMPTY', '', $content
            );
        }else{
            $empty_tmpl = mvb_Model_Template::retrieveSub(
                    'METABOX_LIST_EMPTY', $tmpl
            );
            $content = mvb_Model_Template::replaceSub(
                    'METABOX_LIST_ITEM', '', $tmpl
            );
            $content = mvb_Model_Template::replaceSub(
                    'METABOX_LIST_EMPTY', $empty_tmpl, $content
            );
        }

        return $content;
    }

    public static function renderWidget($tmpl, $list) {
        global $wp_registered_widgets;

        $content = '';
        if (is_array($list)) {
            foreach ($list as $classname => $data) {
                if (is_array($data)) {
                    $desc = mvb_Model_Helper::removeHTML($data['description']);
                    $markers = array(
                        '###title###' => (mvb_Model_Helper::removeHTML($data['title'])),
                        '###classname###' => $classname,
                        '###description###' => ($desc),
                        '###description_short###' => (mvb_Model_Helper::cutStr(
                                $desc, 20
                        )),
                        '###checked###' => (self::$parent->getConfig()
                            ->hasMetabox($classname) ? 'checked' : '')
                    );
                    $content .= mvb_Model_Template::updateMarkers(
                                    $markers, $tmpl
                    );
                }
            }
        }

        return $content;
    }

    public static function renderMetabox($tmpl, $list, $type) {

        $content = '';
        foreach ($list as $position => $set) {
            foreach ($set as $priority => $metaboxes) {
                if (is_array($metaboxes)) {
                    foreach ($metaboxes as $id => $data) {
                        if (is_array($data) && isset($data['id']) && !empty($data['id'])) {
                            $data['title'] = mvb_Model_Helper::removeHTML(
                                    $data['title']
                            );
                            $markers = array(
                                '###title###' => (mvb_Model_Helper::removeHTML(
                                        $data['title']
                                )),
                                '###short_id###' => mvb_Model_Helper::cutStr(
                                        $data['id'], 25
                                ),
                                '###id###' => $data['id'],
                                '###priority###' => $priority,
                                '###internal_id###' => $type . '-' . $id,
                                '###position###' => $position,
                                '###checked###' => (self::$parent->getConfig()
                                        ->hasMetabox($type . '-' . $id) ? 'checked' : '')
                             );
                            $content .= mvb_Model_Template::updateMarkers(
                                    $markers, $tmpl
                            );
                        }
                    }
                }
            }
        }

        return $content;
    }
}

?>