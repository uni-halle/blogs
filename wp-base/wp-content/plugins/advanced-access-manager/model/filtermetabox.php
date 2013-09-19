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
 * Filter for Metaboxes and Widgets
 *
 * Probably it future releases this will be used also for filtering Front-End
 * Widgets. But still this issue is under consideration
 *
 * @package AAM
 * @subpackage Models
 * @author Vasyl Martyniuk <martyniuk.vasyl@gmail.com>
 * @copyrights Copyright © 2011 Vasyl Martyniuk
 * @license GNU General Public License {@link http://www.gnu.org/licenses/}
 */
class mvb_Model_FilterMetabox extends mvb_Model_Abstract_Filter {

    /**
     *
     * @global type $wp_meta_boxes
     * @global type $post
     * @param type $area
     */
    function manage($area = 'post', $additional = NULL) {
        global $wp_meta_boxes, $post, $wp_registered_widgets;

        switch ($area) {
            case 'widgets':
                if (is_array($wp_registered_widgets)) {
                    foreach ($wp_registered_widgets as $id => $data) {
                        if (is_object($data['callback'][0])){
                            $classname = get_class($data['callback'][0]);
                        }elseif(is_string($data['callback'][0])){
                            $classname = $data['callback'][0];
                        }
                        if ($this->getCaller()->getAccessControl()->getUserConfig()->hasMetabox($classname)) {
                            unset($wp_registered_widgets[$id]);
                            unregister_widget($classname);
                            foreach ($additional as $id => $list) {
                                if (is_array($list)) {
                                    $t = array_search($id, $list);
                                    if ($t !== FALSE) {
                                        array_splice($additional, $t);
                                    }
                                }
                            }
                        }
                    }
                }

                return $additional; //TODO
                break;
            case 'dashboard':
                $type = 'dashboard';
                break;

            default:
                $type = isset($post->post_type) ? $post->post_type : '';
                break;
        }

        if (isset($wp_meta_boxes[$type]) && is_array($wp_meta_boxes[$type])) {
            foreach ($wp_meta_boxes[$type] as $position => $metaboxes) {
                foreach ($metaboxes as $priority => $metaboxes1) {
                    foreach ($metaboxes1 as $metabox => $data) {
                        if ($this->getCaller()->getAccessControl()->getUserConfig()->hasMetabox($type . '-' . $metabox)) {
                            unset($wp_meta_boxes[$type][$position][$priority][$metabox]);
                        }
                    }
                }
            }
        }
    }

}

?>