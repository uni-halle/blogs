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
 * Option Manager Model Class
 *
 * @package AAM
 * @subpackage Models
 * @author Vasyl Martyniuk <martyniuk.vasyl@gmail.com>
 * @copyrights Copyright © 2011 Vasyl Martyniuk
 * @license GNU General Public License {@link http://www.gnu.org/licenses/}
 */
class mvb_Model_Manager {

    /**
     * HTML templated from file
     *
     * @var string Template to work with
     * @access protected
     */
    protected $template;

    /**
     * Array of User Roles
     *
     * @var array
     * @access protected
     */
    protected $roles;

    /**
     * Current role to work with
     *
     * @var string
     * @access protected
     */
    protected $current_role;

    /**
     * Current user to work with
     *
     * @var int
     * @access protected
     */
    protected $current_user;

    /**
     * Copy of a config array from main object
     *
     * @var array
     * @access protected
     */
    protected $config;

    /**
     * Cache config
     *
     * @var array
     * @access protected
     */
    protected $cache;
    protected $error = FALSE;
    protected $error_message = '';

    /**
     * Initiate an object and other parameters
     *
     * @param object Main Object
     * @param string $role Current role to work with
     * @param string $rser Current user to work with
     */
    public function __construct($parent, $role = FALSE, $user = FALSE) {

        $templatePath = WPACCESS_TEMPLATE_DIR . 'admin_options.html';
        $this->template = mvb_Model_Template::readTemplate($templatePath);
        $this->roles = mvb_Model_API::getRoleList();

        $this->setCurrentRole($role);
        $this->setCurrentUser($user);
        $this->initConfig();

        mvb_Model_Label::initAllLabels();
    }

    protected function initConfig() {

        if (mvb_Model_API::isNetworkPanel()) {
            $blog_id = mvb_Model_Helper::getParam('site', 'GET', get_current_blog_id());
            mvb_Model_API::setCurrentBlog($blog_id);
        }

        if ($this->current_user) {
            $this->config = mvb_Model_API::getUserAccessConfig($this->current_user);
        } else {
            $this->config = mvb_Model_API::getRoleAccessConfig($this->current_role);
        }
    }

    protected function setCurrentRole($role) {

        if ($this->role_exists($role)) {
            $this->current_role = $role;
        } else {
            $this->current_role = mvb_Model_API::getCurrentEditableUserRole();
        }

        return TRUE;
    }

    protected function setCurrentUser($user) {

        if ($this->user_exists($user)) {
            $this->current_user = $user;
        } else {
            $this->current_user = FALSE;
        }
    }

    public function user_exists($user_id) {

        $result = (get_user_by('id', $user_id) ? TRUE : FALSE);

        return $result;
    }

    function role_exists($role) {

        $exists = (isset($this->roles[$role]) ? TRUE : FALSE);

        return $exists;
    }

    public function getCurrentRole() {

        return $this->current_role;
    }

    public function getCurrentUser() {

        return $this->current_user;
    }

    public function renderMenuTab() {

        $template = mvb_Model_Template::retrieveSub(
                        'MAIN_MENU_TAB', $this->template
        );

        return mvb_Model_Manager_Menu::render($template, $this);
    }

    public function renderMetaboxTab() {

        $template = mvb_Model_Template::retrieveSub(
                        'METABOX_TAB', $this->template
        );

        return mvb_Model_Manager_Metabox::render($template, $this);
    }

    public function renderCapabilityTab() {

        $template = mvb_Model_Template::retrieveSub(
                        'CAPABILITY_TAB', $this->template
        );

        return mvb_Model_Manager_Capability::render($template, $this);
    }

    public function renderRestrictionTab() {

        $template = mvb_Model_Template::retrieveSub(
                        'RESTRICTION_TAB', $this->template
        );

        return mvb_Model_Manager_Restriction::render($template, $this);
    }

    public function renderConfigPressTab() {

        $template = mvb_Model_Template::retrieveSub(
                        'CONFIG_PRESS_TAB', $this->template
        );

        return mvb_Model_Manager_ConfigPress::render($template, $this);
    }

    public function renderSubmitMetabox() {

        $template = mvb_Model_Template::retrieveSub(
                        'SUBMIT_METABOX', $this->template
        );

        return mvb_Model_Manager_SubmitMetabox::render($template, $this);
    }

    public function renderRoleMetabox() {

        $template = mvb_Model_Template::retrieveSub(
                        'ROLE_METABOX', $this->template
        );

        return mvb_Model_Manager_RoleMetabox::render($template, $this);
    }

    public function renderAHMMetabox() {
        if (!function_exists('plugins_api')) {
            require_once(ABSPATH . '/wp-admin/includes/plugin-install.php');
        }
        $api = plugins_api('plugin_information', array('slug' => 'wp-bug-tracker'));
        $status = install_plugin_install_status($api);
        if ($status['status'] == 'install') {
            $template = mvb_Model_Template::retrieveSub(
                            'AHM_METABOX', $this->template
            );
            $template = mvb_Model_Template::updateMarkers(
                            array('###install_link###' => $status['url']), $template
            );
        } else {
            $template = '';
        }

        return $template;
    }

    public function getConfig() {

        return $this->config;
    }

    function manage() {

        //render error list if applicable
        $tmpl = $this->renderErrorList($this->template);

        //render version indicator
        $tmpl = mvb_Model_Template::replaceSub(
                        'VERSION', $this->renderVersionIndicator(), $tmpl
        );

        //render Admin Menu Tab
        $tmpl = mvb_Model_Template::replaceSub(
                        'MAIN_MENU_TAB', $this->renderMenuTab(), $tmpl
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

        //render Submit Metabox
        $tmpl = mvb_Model_Template::replaceSub(
                        'SUBMIT_METABOX', $this->renderSubmitMetabox(), $tmpl
        );

        //render Role Manager Metabox
        $tmpl = mvb_Model_Template::replaceSub(
                        'ROLE_METABOX', $this->renderRoleMetabox(), $tmpl
        );

        //render AHM banner
        $tmpl = mvb_Model_Template::replaceSub(
                        'AHM_METABOX', $this->renderAHMMetabox(), $tmpl
        );

        $tmpl = $this->updateMarkers($tmpl);

        $tmpl = mvb_Model_Label::clearLabels($tmpl);
        $tmpl = mvb_Model_Template::clearTemplate($tmpl);

        //add filter to future add-ons
        echo apply_filters(WPACCESS_PREFIX . 'option_page', $tmpl);
    }

    public function renderVersionIndicator() {

        $template = mvb_Model_Template::retrieveSub(
                        'VERSION', $this->template
        );

        if (mvb_Model_Helper::isPremium()) {
            $tmpl = mvb_Model_Template::retrieveSub('VERSION_PREMIUM', $template);
            $template = mvb_Model_Template::replaceSub('VERSION_BASIC', '', $template);
            $template = mvb_Model_Template::replaceSub('VERSION_PREMIUM', $tmpl, $template);
        } else {
            $tmpl = mvb_Model_Template::retrieveSub('VERSION_BASIC', $template);
            $template = mvb_Model_Template::replaceSub('VERSION_PREMIUM', '', $template);
            $template = mvb_Model_Template::replaceSub('VERSION_BASIC', $tmpl, $template);
        }

        return $template;
    }

    public function renderErrorList($tmpl) {

        $item_tmpl = mvb_Model_Template::retrieveSub('ERROR_LIST', $tmpl);
        $list = '';
        if (!is_writable(WPACCESS_BASE_DIR . 'config.ini')) {
            $list .= mvb_Model_Template::updateMarkers(
                            array(
                        '###message###' => mvb_Model_Label::get('LABEL_162'),
                        '###url###' => WPACCESS_ERROR162_URL
                            ), $item_tmpl
            );
        }
        if (!is_writable(WPACCESS_BASE_DIR . 'model')) {
            $list .= mvb_Model_Template::updateMarkers(
                            array(
                        '###message###' => mvb_Model_Label::get('LABEL_164'),
                        '###url###' => WPACCESS_ERROR164_URL
                            ), $item_tmpl
            );
        }

        return mvb_Model_Template::replaceSub('ERROR_LIST', $list, $tmpl);
    }

    public function updateMarkers($tmpl) {

        //TODO - render_mss do not like it
        $render_mss = mvb_Model_Helper::getParam('render_mss', 'REQUEST');
        if (mvb_Model_API::isNetworkPanel() || $render_mss) {
            $submit_link = network_admin_url('admin.php?page=wp_access');
            $blog_id = mvb_Model_Helper::getParam(
                            'site', 'REQUEST', get_current_blog_id()
            );
            $submit_link = add_query_arg('site', $blog_id, $submit_link);
        } else {
            $submit_link = admin_url('admin.php?page=wp_access');
        }

        $submited = mvb_Model_Helper::getParam('submited', 'POST');
        $show_msg = mvb_Model_Helper::getParam('show_message');
        $message_class = ($submited || $show_msg ? 'message-active' : 'message-passive');

        $markers = array(
            '###info_image###' => WPACCESS_CSS_URL . 'images/Info-tooltip.png',
            '###critical_image###' => WPACCESS_CSS_URL . 'images/Critical-tooltip.png',
            '###current_role###' => $this->roles[$this->getCurrentRole()]['name'],
            '###error_indicator###' => ($this->error ? 1 : 0),
            '###error_message###' => $this->error_message,
            '###current_role_id###' => $this->current_role,
            '###site_url###' => site_url(),
            '###nonce###' => wp_nonce_field(WPACCESS_PREFIX . 'options', '_wpnonce', TRUE, FALSE),
            '###form_action###' => $submit_link,
            '###message_class###' => $message_class,
            '###reference_url###' => WPACCESS_BASE_URL . 'view/reference.php'
        );

        //get current user data
        if ($this->current_user) {
            $user = get_userdata($this->current_user);
            $markers['###current_user###'] = $user->user_login;
            $markers['###current_user_id###'] = $user->ID;
        } else {
            $markers['###current_user_id###'] = 0;
            $markers['###current_user###'] = mvb_Model_Label::get('LABEL_120');
        }

        return mvb_Model_Template::updateMarkers($markers, $tmpl);
    }

    function do_save() {

        if (isset($_POST['submited'])) {
            $params = $this->getParamSet();
            $error_message = NULL;
            //save config for current submitted role or user
            $this->config->setMenu($params['menu']);
            $this->config->setMetaboxes($params['metabox']);
            $this->config->setCapabilities($params['advance']);
            $this->config->saveConfig();
            if (mvb_Model_Helper::multisiteApplyAll() && !$this->current_user) {
                $config = clone $this->config;
                $roles = mvb_Model_API::getRoleList(FALSE);
                $m = new mvb_Model_Role(); //just in case
                $apply = (array) mvb_Model_ConfigPress::getOption(
                                'aam.multisite.apply', array('menu', 'menu_order', 'metaboxes', 'capabilities', 'current_role')
                );
                foreach (mvb_Model_Helper::getApplySiteList() as $site) {
                    if ($site->blog_id == 'error') {
                        $error_message = mvb_Model_Label::get('LABEL_148');
                        break;
                    }
                    mvb_Model_API::setCurrentBlog($site->blog_id);
                    //apply settings based on ConfigPress settings
                    if (in_array('role_list', $apply)) {
                        mvb_Model_API::updateBlogOption('user_roles', $roles);
                    } elseif (in_array('current_role', $apply)) {
                        if (mvb_Model_API::getRole($this->current_role) === FALSE) {
                            $m->createNewRole(
                                    $roles[$this->current_role]['name'], $roles[$this->current_role]['capabilities']
                            );
                        }
                    }
                    $this->initConfig();
                    if (in_array('menu', $apply)) {
                        $this->config->setMenu($config->getMenu());
                    }
                    if (in_array('menu_order', $apply)) {
                        $this->config->setMenuOrder($config->getMenuOrder());
                    }
                    if (in_array('metaboxes', $apply)) {
                        $this->config->setMetaboxes($config->getMetaboxes());
                    }
                    if (in_array('capabilities', $apply)) {
                        $this->config->setCapabilities($config->getCapabilities());
                    }
                    if (in_array('restrictions', $apply)) {
                        $this->config->setRestrictions($config->getRestrictions());
                    }
                    $this->config->saveConfig();
                }
            }

            mvb_Model_ConfigPress::saveConfig(stripslashes($params['config_press']));
        } else {
            $error_message = FALSE;
        }

        return $error_message;
    }

    protected function getParamSet() {
        $params = mvb_Model_Helper::getParam('wpaccess', 'POST', array());
        if (!isset($params['menu'])) {
            $params['menu'] = array();
        }
        if (!isset($params['metabox'])) {
            $params['metabox'] = array();
        }
        if (!isset($params['advance'])) {
            $params['advance'] = array();
        }
        if (!isset($params['config_press'])){
            $params['config_press'] = '';
        }

        return $params;
    }

    /**
     * Get Menu Ordered according to settings
     *
     * @global type $menu
     * @return type
     * @todo the same functionality as in filtermenu
     */
    public function getRoleMenu() {
        global $menu;

        $r_menu = array();

        if (is_array($menu)) {
            $r_menu = $menu;
            ksort($r_menu);
            $w_menu = array();
            foreach ($this->config->getMenuOrder() as $mid) {
                foreach ($menu as $data) {
                    if (isset($data[5]) && ($data[5] == $mid)) {
                        $w_menu[] = $data;
                    }
                }
            }
            $cur_pos = 0;
            foreach ($r_menu as &$data) {
                for ($i = 0; $i < count($w_menu); $i++) {
                    if (isset($data[5]) && ($w_menu[$i][5] == $data[5])) {
                        $data = $w_menu[$cur_pos++];
                        break;
                    }
                }
            }
        }

        return $r_menu;
    }

}

?>