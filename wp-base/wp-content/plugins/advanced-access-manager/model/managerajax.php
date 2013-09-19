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
 * Ajax Option Manager Model Class
 *
 * @package AAM
 * @subpackage Models
 * @author Vasyl Martyniuk <martyniuk.vasyl@gmail.com>
 * @copyrights Copyright © 2011 Vasyl Martyniuk
 * @license GNU General Public License {@link http://www.gnu.org/licenses/}
 */
class mvb_Model_ManagerAjax extends mvb_Model_Manager {

    public function init($data) {

        foreach ($data as $key => $value) {
            $this->{$key} = $value;
        }
    }

    public function manage_ajax($action) {

        switch ($action) {
            case 'option_list':
                $response = $this->renderOptionList();
                break;

            case 'metabox_list':
                $response = $this->renderMetaboxList();
                break;

            case 'add_capability':
                $response = $this->renderCapability();
                break;

            case 'user_list':
                $response = $this->renderUserList();
                break;

            case 'add_role':
                $response = $this->renderRoleRow();
                break;

            case 'get_info':
                $response = $this->renderInfo();
                break;

            default:
                break;
        }

        return $response;
    }

    public function renderInfo() {

        $id = mvb_Model_Helper::getParam('id', 'POST');
        $type = mvb_Model_Helper::getParam('type', 'POST');

        if (mvb_Model_Helper::getParam('restore', 'POST')) {
            $user = mvb_Model_Helper::getParam('user', 'POST');
            $role = mvb_Model_Helper::getParam('role', 'POST');
            if ($user) {
                $config = mvb_Model_API::getUserAccessConfig($user);
            } else {
                $config = mvb_Model_API::getRoleAccessConfig($role);
            }
            $config->deleteRestriction($type, $id);
            $config->saveConfig();
        }

        $tmpl = mvb_Model_Template::retrieveSub(
                        'POST_INFORMATION', $this->template
        );
        $result = mvb_Model_Manager_Restriction::renderInfo(
                        $id, $type, $this, $tmpl
        );

        return $result;
    }

    public function renderRoleRow() {

        $tmpl = mvb_Model_Template::retrieveSub('ROLE_METABOX', $this->template);
        $tmpl = mvb_Model_Template::retrieveSub('DELETE_ROLE_LIST', $tmpl);
        $tmpl = mvb_Model_Template::retrieveSub('DELETE_ROLE_ITEM', $tmpl);

        $content = mvb_Model_Manager_RoleMetabox::renderRoleRow(
                        $this->role, array('name' => $this->role_label), $tmpl
        );
        $content = mvb_Model_Label::clearLabels($content);

        return mvb_Model_Template::clearTemplate($content);
    }

    public function renderUserList() {

        $tmpl = mvb_Model_Template::retrieveSub(
                        'SUBMIT_METABOX', $this->template
        );
        $content = mvb_Model_Manager_SubmitMetabox::renderUserSelector($tmpl, $this);
        $content = mvb_Model_Label::clearLabels($content);

        $result = array(
            'status' => 'success',
            'html' => mvb_Model_Template::clearTemplate($content)
        );

        return $result;
    }

    public function renderCapability() {

        $this->template = mvb_Model_Template::retrieveSub(
                        'CAPABILITY_TAB', $this->template
        );
        $template = mvb_Model_Template::retrieveSub(
                        'CAPABILITY_LIST', $this->template
        );
        $template = mvb_Model_Template::retrieveSub(
                        'CAPABILITY_ITEM', $template
        );
        $conf = mvb_Model_ConfigPress::getOption('aam.delete_capabilities');
        $allow_delete = ($conf == 'true' ? TRUE : FALSE);
        $content = mvb_Model_Manager_Capability::renderRow(
                        $this->cap, $template, $this, $allow_delete
        );

        $markers = array(
            '###info_image###' => WPACCESS_CSS_URL . 'images/Info-tooltip.png',
            '###critical_image###' => WPACCESS_CSS_URL . 'images/Critical-tooltip.png',
        );
        $content = mvb_Model_Template::updateMarkers($markers, $content);
        $content = mvb_Model_Label::clearLabels($content);

        $result = array(
            'status' => 'success',
            'html' => mvb_Model_Template::clearTemplate($content)
        );

        return $result;
    }

    public function renderMetaboxList() {

        $template = mvb_Model_Template::retrieveSub(
                        'METABOX_TAB', $this->template
        );

        $content = mvb_Model_Manager_Metabox::render($template, $this);
        $content = $this->updateMarkers($content);
        $content = mvb_Model_Label::clearLabels($content);

        $result = array(
            'status' => 'success',
            'html' => mvb_Model_Template::clearTemplate($content)
        );

        return $result;
    }

    public function renderOptionList() {

        $this->template = mvb_Model_Template::retrieveSub(
                        'MAIN_OPTIONS_LIST', $this->template
        );

        //render Admin Menu Tab
        $tmpl = mvb_Model_Template::replaceSub(
                        'MAIN_MENU_TAB', $this->renderMenuTab(), $this->template
        );

        //render Metabox & Widgets Tab
        $tmpl = mvb_Model_Template::replaceSub(
                        'METABOX_TAB', $this->renderMetaboxTab(), $tmpl
        );

        //render Capabilities Tab
        $tmpl = mvb_Model_Template::replaceSub(
                        'CAPABILITY_TAB', $this->renderCapabilityTab(), $tmpl
        );

        //render Restriction Tab
        $tmpl = mvb_Model_Template::replaceSub(
                        'RESTRICTION_TAB', $this->renderRestrictionTab(), $tmpl
        );

        //render ConfigPress Tab
        $tmpl = mvb_Model_Template::replaceSub(
                        'CONFIG_PRESS_TAB', $this->renderConfigPressTab(), $tmpl
        );

        $tmpl = $this->updateMarkers($tmpl);
        $tmpl = mvb_Model_Label::clearLabels($tmpl);
        $tmpl = mvb_Model_Template::clearTemplate($tmpl);

        $result = array(
            'html' => apply_filters(WPACCESS_PREFIX . 'option_page', $tmpl),
            'status' => 'success'
        );

        return $result;
    }

}

?>
