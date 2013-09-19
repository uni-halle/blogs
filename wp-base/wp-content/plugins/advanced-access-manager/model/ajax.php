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
 * User Model Class
 *
 * @package AAM
 * @subpackage Models
 * @author Vasyl Martyniuk <martyniuk.vasyl@gmail.com>
 * @copyrights Copyright © 2011 Vasyl Martyniuk
 * @license GNU General Public License {@link http://www.gnu.org/licenses/}
 */
class mvb_Model_Ajax {

    /**
     * Parent Object
     *
     * Holds the main plugin object
     *
     * @var object
     * @access public
     */
    public $pObj;

    /**
     * Requested action
     *
     * @var string
     * @access protected
     */
    protected $action;

    /**
     * Default Capability Set
     *
     * @access protected
     * @var array
     */
    protected $default_caps = array('read' => 1, 'level_0' => 1);

    /**
     * Main Constructor
     *
     * @param object
     */
    public function __construct($pObj) {

        $this->pObj = $pObj;
        $this->action = $this->get_action();

        mvb_Model_Label::initAllLabels();
    }

    /**
     * Process Ajax request
     *
     */
    public function process() {

        switch ($this->action) {
            case 'add_blog_admin':
                $result = $this->add_blog_admin();
                break;

            case 'restore_role':
                $result = $this->restore_role($_POST['role']);
                break;

            case 'restore_user':
                $result = $this->restore_user($_POST['user']);
                break;

            case 'create_role':
                $result = $this->create_role($_POST['role']);
                break;

            case 'delete_role':
                $result = $this->delete_role();
                break;

            case 'render_metabox_list':
                $result = $this->render_metabox_list();
                break;

            case 'initiate_wm':
                $result = $this->initiate_wm();
                break;

            case 'initiate_url':
                $result = $this->initiate_url();
                break;

            case 'add_capability':
                $result = $this->add_capability();
                break;

            case 'delete_capability':
                $result = $this->delete_capability();
                break;

            case 'get_treeview':
                $result = $this->get_treeview();
                break;

            case 'get_info':
                $result = $this->get_info();
                break;

            case 'get_userlist':
                $result = $this->get_userlist();
                break;

            case 'save_info':
                $result = $this->save_info();
                break;

            case 'save_order':
                $result = $this->save_order();
                break;

            case 'export':
                $result = $this->export();
                break;

            case 'upload_config':
                $result = $this->upload_config();
                break;

            case 'create_super':
                $result = $this->create_super();
                break;

            case 'update_role_name':
                $result = $this->update_role_name();
                break;

            default:
                $result = array('status' => 'error');
                break;
        }

        die(json_encode($result));
    }

    /*
     * Update Roles Label
     *
     */

    protected function update_role_name() {

        //TODO - Here you can hack and change Super Admin and Admin Label
        //But this is not a big deal.
        $role_list = mvb_Model_API::getRoleList(FALSE);
        $role = $_POST['role_id'];
        //TODO - maybe not the best way
        $label = urldecode(sanitize_title($_POST['label']));
        if (isset($role_list[$role])) {
            $role_list[$role]['name'] = ucfirst($label);
            mvb_Model_API::updateBlogOption('user_roles', $role_list);
            $result = array('status' => 'success');
        } else {
            $result = array('status' => 'error');
        }


        return $result;
    }

    /*
     * Get current action
     *
     * @return bool Return true if ok
     */

    protected function get_action() {

        $a = (isset($_REQUEST['sub_action']) ? $_REQUEST['sub_action'] : FALSE);

        return $a;
    }

    protected function get_userlist() {

        $role = $_POST['role'];
        $m = new mvb_Model_ManagerAjax($this->pObj, $role, FALSE);
        $response = $m->manage_ajax('user_list');

        return $response;
    }

    /*
     * Restore default User Roles
     *
     * @param string User Role
     * @return bool True if success
     */

    protected function restore_role($role) {

        $or_roles = mvb_Model_API::getBlogOption(
                        WPACCESS_PREFIX . 'original_user_roles', array()
        );
        $roles = mvb_Model_API::getRoleList(FALSE);

        if (($role != WPACCESS_ADMIN_ROLE)
                || ( ($role == WPACCESS_ADMIN_ROLE) && mvb_Model_API::isSuperAdmin())) {

            if (isset($or_roles[$role])) {
                $roles[$role]['capabilities'] = $or_roles[$role]['capabilities'];
            } else {
                $roles[$role]['capabilities'] = $this->default_caps;
            }

            mvb_Model_API::updateBlogOption('user_roles', $roles);
            mvb_Model_API::deleteBlogOption(WPACCESS_PREFIX . 'config_' . $role);

            mvb_Model_Cache::clearCache();
            $result = array('status' => 'success');
        } else {
            $result = array('status' => 'error');
        }


        return $result;
    }

    /*
     * Restore default User Settings
     *
     * @param string User ID
     * @return bool True if success
     */

    protected function restore_user($user_id) {

        delete_user_meta($user_id, WPACCESS_PREFIX . 'config');
        mvb_Model_Cache::clearCache();

        return array('status' => 'success');
    }

    /**
     *
     * @param type $role
     * @param type $render_html
     * @return type
     */
    protected function create_role($role, $capabilities = FALSE, $render_html = TRUE) {

        $m = new mvb_Model_Role();
        $new_role = ($role ? $role : $_REQUEST['role']);
        $caps = ($capabilities ? $capabilities : $this->default_caps);
        $result = $m->createNewRole($new_role, $caps);
        if (($result['result'] == 'success') && $render_html) {
            $m = new mvb_Model_ManagerAjax($this->pObj, $result['new_role']);
            $m->init(array(
                'role' => $result['new_role'],
                'role_label' => $new_role
            ));
            $result['html'] = $m->manage_ajax('add_role');
        }

        return $result;
    }

    /*
     * Delete Role
     *
     */

    protected function delete_role() {

        $m = new mvb_Model_Role();
        //TODO - unsecure
        $m->remove_role($_POST['role']);
        $result = array('status' => 'success');

        return $result;
    }

    /*
     * Render metabox list after initialization
     *
     * Part of AJAX interface. Is used for rendering the list of initialized
     * metaboxes.
     *
     * @return string HTML string with result
     */

    protected function render_metabox_list() {

        $role = mvb_Model_Helper::getParam('role', 'POST');
        $user = mvb_Model_Helper::getParam('user', 'POST');
        $m = new mvb_Model_ManagerAjax($this->pObj, $role, $user);
        $response = $m->manage_ajax('metabox_list');

        return $response;
    }

    /*
     * Initialize Widgets and Metaboxes
     *
     * Part of AJAX interface. Using for metabox and widget initialization.
     * Go through the list of all registered post types and with http request
     * try to access the edit page and grab the list of rendered metaboxes.
     *
     * @return string JSON encoded string with result
     */

    protected function initiate_wm() {
        global $wp_post_types;

        /*
         * Go through the list of registered post types and try to grab
         * rendered metaboxes
         * Parameter next in _POST array shows the next port type in list of
         * registered metaboxes. This is done for emulating the progress bar
         * after clicking "Refresh List" or "Initialize List"
         */
        $next = trim($_POST['next']);
        $typeList = array_keys($wp_post_types);
        array_unshift($typeList, 'widgets');
        array_unshift($typeList, 'dashboard');
        //add dashboard
        // array_unshift($typeList, 'dashboard');
        $typeQuant = count($typeList) + 1;
        $i = 0;
        if ($next) { //if next present, means that process continuing
            while ($typeList[$i] != $next) { //find post type
                $i++;
            }
            $current = $next;
            if (isset($typeList[$i + 1])) { //continue the initialization process?
                $next = $typeList[$i + 1];
            } else {
                $next = FALSE;
            }
        } else { //this is the beggining
            $current = array_shift($typeList);
            $next = array_shift($typeList);
        }
        switch ($current) {
            case 'dashboard':
                $url = add_query_arg('grab', 'metaboxes', admin_url('index.php'));
                break;

            case 'widgets':
                $url = add_query_arg(array('grab' => 'metaboxes', 'widget' => 1), admin_url('index.php'));
                break;

            default:
                $url = add_query_arg('grab', 'metaboxes', admin_url('post-new.php?post_type=' . $current));
                break;
        }

        //grab metaboxes
        $result = mvb_Model_Helper::cURL($url);

        $result['value'] = round((($i + 1) / $typeQuant) * 100); //value for progress bar
        $result['next'] = ($next ? $next : '' ); //if empty, stop initialization

        return $result;
    }

    /*
     * Initialize single URL
     *
     * Sometimes not all metaboxes are rendered if there are conditions. For example
     * render Shipping Address Metabox if status of custom post type is Approved.
     * So this metabox will be not visible during general initalization in function
     * initiateWM(). That is why this function do that manually
     *
     * @return string JSON encoded string with result
     */

    protected function initiate_url() {

        $url = $_POST['url'];
        if ($url) {
            $url = add_query_arg('grab', 'metaboxes', $url);
            $result = mvb_Model_Helper::cURL($url);
        } else {
            $result = array('status' => 'error');
        }

        return $result;
    }

    /**
     * Add New Capability
     *
     * @global type $wpdb
     * @return type
     */
    protected function add_capability() {
        global $wpdb;

        $cap = strtolower(trim($_POST['cap']));

        if ($cap) {
            $cap = sanitize_title_with_dashes($cap);
            $cap = str_replace('-', '_', $cap);
            $capList = mvb_Model_API::getCurrentUser()->getAllCaps();

            if (!isset($capList[$cap])) { //create new capability
                $roles = mvb_Model_API::getRoleList(FALSE);
                if (isset($roles[WPACCESS_SADMIN_ROLE])) {
                    $roles[WPACCESS_SADMIN_ROLE]['capabilities'][$cap] = 1;
                }
                $roles[WPACCESS_ADMIN_ROLE]['capabilities'][$cap] = 1; //add this role for admin automatically
                mvb_Model_API::updateBlogOption('user_roles', $roles);
                //check if this is for specific user
                //TODO
                $user = mvb_Model_Helper::getParam('user', 'POST');
                if ($user) {
                    $conf = mvb_Model_API::getUserAccessConfig($user);
                    $conf->addCapability($cap);
                    $conf->saveConfig();
                }

                //render response
                $m = new mvb_Model_ManagerAjax($this->pObj, FALSE, FALSE);
                $m->init(array('cap' => $cap));
                $result = $m->manage_ajax('add_capability');
                mvb_Model_Cache::clearCache();
            } else {
                $result = array(
                    'status' => 'error',
                    'message' => 'Capability ' . $_POST['cap'] . ' already exists'
                );
            }
        } else {
            $result = array(
                'status' => 'error',
                'message' => mvb_Model_Label::get('LABEL_124'),
            );
        }

        return $result;
    }

    /*
     * Delete capability
     */

    protected function delete_capability() {
        global $wpdb;

        $cap = trim($_POST['cap']);
        if (mvb_Model_ConfigPress::getOption('aam.delete_capabilities') == 'true') {
            $roles = mvb_Model_API::getRoleList(FALSE);
            foreach ($roles as $role => $data) {
                if (isset($data['capabilities'][$cap])) {
                    unset($roles[$role]['capabilities'][$cap]);
                }
            }
            mvb_Model_API::updateBlogOption('user_roles', $roles);
            $result = array(
                'status' => 'success'
            );
            mvb_Model_Cache::clearCache();
        } else {
            $result = array(
                'status' => 'error',
                'message' => mvb_Model_Label::get('LABEL_125')
            );
        }

        return $result;
    }

    /*
     * Get Post tree
     *
     */

    protected function get_treeview() {
        global $wp_post_types;

        $type = $_REQUEST['root'];
        $tree = array();

        if ($type == "source") {
            if (is_array($wp_post_types)) {
                foreach ($wp_post_types as $post_type => $data) {
                    //show only list of post type which have User Interface
                    if ($data->show_ui) {
                        $tree[] = (object) array(
                                    'text' => $data->label,
                                    'expanded' => FALSE,
                                    'hasChildren' => TRUE,
                                    'id' => $post_type,
                                    'classes' => 'roots',
                        );
                    }
                }
            }
        } else {
            $parts = explode('-', $type);

            switch (count($parts)) {
                case 1: //root of the post type
                    $tree = $this->build_branch($parts[0]);
                    break;

                case 2: //post type
                    if ($parts[0] == 'post') {
                        $post_type = get_post_field('post_type', $parts[1]);
                        $tree = $this->build_branch($post_type, FALSE, $parts[1]);
                    } elseif ($parts[0] == 'cat') {
                        $taxonomy = mvb_Model_Helper::getTaxonomyByTerm($parts[1]);
                        $tree = $this->build_branch(NULL, $taxonomy, $parts[1]);
                    }
                    break;

                default:
                    break;
            }
        }

        if (!count($tree)) {
            $tree[] = (object) array(
                        'text' => '<i>[' . mvb_Model_Label::get('LABEL_153') . ']</i>',
                        'hasChildren' => FALSE,
                        'classes' => 'post-ontree',
                        'id' => 'empty-' . uniqid()
            );
        }

        return $tree;
    }

    private function build_branch($post_type, $taxonomy = FALSE, $parent = 0) {
        global $wpdb;

        $tree = array();
        if (!$parent && !$taxonomy) { //root of a branch
            $tree = $this->build_categories($post_type);
        } elseif ($taxonomy) { //build sub categories
            $tree = $this->build_categories('', $taxonomy, $parent);
        }
        //render list of posts in current category
        if ($parent == 0) {
            $query = "SELECT p.ID FROM `{$wpdb->posts}` AS p ";
            $query .= "LEFT JOIN `{$wpdb->term_relationships}` AS r ON ( p.ID = r.object_id ) ";
            $query .= "WHERE (p.post_type = '{$post_type}') AND (p.post_status NOT IN ('trash', 'auto-draft')) AND (p.post_parent = 0) AND r.object_id IS NULL";
            $posts = $wpdb->get_col($query);
        } elseif ($parent && $taxonomy) {
            $posts = get_objects_in_term($parent, $taxonomy);
        } elseif ($post_type && $parent) {
            $posts = get_posts(array('post_parent' => $parent, 'post_type' => $post_type, 'fields' => 'ids', 'nopaging' => TRUE));
        }

        if (is_array($posts)) {
            foreach ($posts as $post_id) {
                if ($post = get_post($post_id)) {
                    $onClick = "mObj.loadInfo(event, \"post\", {$post->ID});";
                    $tree[] = (object) array(
                                'text' => "<a href='#' onclick='{$onClick}'>{$post->post_title}</a>",
                                'hasChildren' => $this->has_post_childs($post),
                                'classes' => 'file',
                                'id' => 'post-' . $post->ID
                    );
                }
            }
        }

        return $tree;
    }

    private function build_categories($post_type, $taxonomy = FALSE, $parent = 0) {

        $tree = array();

        if ($parent) {
            //$taxonomy = $this->get_taxonomy_get_term($parent);
            //firstly render the list of sub categories
            $cat_list = get_terms(
                    $taxonomy, array(
                'get' => 'all',
                'parent' => $parent,
                'hide_empty' => FALSE
                    )
            );
            if (is_array($cat_list)) {
                foreach ($cat_list as $category) {
                    $tree[] = $this->build_category($category);
                }
            }
        } else {
            $taxonomies = get_object_taxonomies($post_type);
            foreach ($taxonomies as $taxonomy) {
                if (is_taxonomy_hierarchical($taxonomy)) {
                    $term_list = get_terms(
                            $taxonomy, array(
                        'parent' => $parent,
                        'hide_empty' => FALSE
                            )
                    );
                    if (is_array($term_list)) {
                        foreach ($term_list as $term) {
                            $tree[] = $this->build_category($term);
                        }
                    }
                }
            }
        }

        return $tree;
    }

    private function build_category($category) {

        $onClick = "mObj.loadInfo(event, \"taxonomy\", {$category->term_id});";
        $branch = (object) array(
                    'text' => "<a href='#' onclick='{$onClick}'>{$category->name}</a>",
                    'expanded' => FALSE,
                    'classes' => 'important folder',
        );
        if ($this->has_category_childs($category)) {
            $branch->hasChildren = TRUE;
            $branch->id = "cat-{$category->term_id}";
        }

        return $branch;
    }

    /*
     * Check if category has children
     *
     * @param int category ID
     * @return bool TRUE if has
     */

    protected function has_post_childs($post) {

        $posts = get_posts(array('post_parent' => $post->ID, 'post_type' => $post->post_type));

        return (count($posts) ? TRUE : FALSE);
    }

    /*
     * Check if category has children
     *
     * @param int category ID
     * @return bool TRUE if has
     */

    protected function has_category_childs($cat) {
        global $wpdb;

        //get number of categories
        $query = "SELECT COUNT(*) FROM {$wpdb->term_taxonomy} WHERE term_taxonomy_id={$cat->term_id}";
        $counter = $wpdb->get_var($query) + $cat->count;

        return ($counter ? TRUE : FALSE);
    }

    /**
     * Get Information about current post or page
     *
     * @global type $wp_post_statuses
     * @global type $wp_post_types
     * @return type
     */
    protected function get_info() {
        global $wp_post_statuses, $wp_post_types;

        $role = mvb_Model_Helper::getParam('role', 'POST');
        $user = mvb_Model_Helper::getParam('user', 'POST', FALSE);

        $m = new mvb_Model_ManagerAjax($this->pObj, $role, $user);

        return $m->manage_ajax('get_info');
    }

    /**
     *
     * @param type $config
     * @param type $info
     * @return string
     */
    protected function updateRestriction($config, $info) {

        $limit = apply_filters(
                WPACCESS_PREFIX . 'restrict_limit', WPACCESS_RESTRICTION_LIMIT
        );
        $rests = $config->getRestrictions();
        if ($info['type'] == 'post' && isset($rests['posts'])){
            $count = count($rests['post']);
        }elseif(isset($rests['taxonomy'])){
            $count = count($rests['taxonomy']);
        }else{
            $count = 0;
        }
        
        if (!$config->hasRestriction($info['type'], $info['id'])) {
            $count++;
        }

        if ($limit == -1 || $count <= $limit) {
            //prepare data
            $prep = $this->prepareRestrictions($info);
            $config->updateRestriction($info['type'], $info['id'], $prep);
            $result['status'] = 'success';
        } else {
            $result['status'] = 'error';
            $result['message'] = sprintf(
                    mvb_Model_Label::get('LABEL_2'), WPACCESS_RESTRICTION_LIMIT, 'http://whimba.org/advanced-access-manager'
            );
        }

        if ($result['status'] == 'success') {
            $config->saveConfig();
        }

        return $result;
    }

    protected function prepareRestrictions($data) {

        $prep = array();
        if ($data['type'] == 'taxonomy') {
            $prep['taxonomy'] = mvb_Model_Helper::getTaxonomyByTerm($data['id']);
        }
        if (isset($data['data']) && is_array($data['data'])) {
            foreach ($data['data'] as $input) {
                if (($data['type'] == 'taxonomy')
                        && (strpos($input['name'], '_post_') !== FALSE)
                        && !mvb_Model_Helper::isPremium()) {
                    continue;
                }
                $prep[$input['name']] = $input['value'];
            }
        }

        return $prep;
    }

    /**
     * Save Restriction Information
     *
     * @access protected
     * @return array
     */
    protected function save_info() {

        $role = mvb_Model_Helper::getParam('role', 'POST');
        $user = mvb_Model_Helper::getParam('user', 'POST');
        $apply_all = mvb_Model_Helper::getParam('apply', 'POST');
        $apply_all_cb = mvb_Model_Helper::getParam('apply_all_cb', 'POST');
        $info = mvb_Model_Helper::getParam('info', 'POST');

        mvb_Model_API::updateBlogOption(
                WPACCESS_PREFIX . 'hide_apply_all', $apply_all_cb
        );

        if ($user) {
            $config = mvb_Model_API::getUserAccessConfig($user);
            $result = $this->updateRestriction($config, $info);
        } else {
            if ($apply_all) {
                foreach (mvb_Model_API::getRoleList() as $role => $dummy) {
                    $config = mvb_Model_API::getRoleAccessConfig($role);
                    $result = $this->updateRestriction($config, $info);
                    if ($result['status'] == 'error') {
                        break;
                    }
                }
            } else {
                $config = mvb_Model_API::getRoleAccessConfig($role);
                $result = $this->updateRestriction($config, $info);
            }
        }

        return $result;
    }

    /**
     * Save menu order
     *
     * @return array
     */
    protected function save_order() {

        $apply_all = $_POST['apply_all'];
        $role = $_POST['role'];
        $user = $_POST['user'];


        if ($user) {
            $config = mvb_Model_API::getUserAccessConfig($user);
            $config->setMenuOrder($_POST['menu']);
            $config->saveConfig();
        } else {
            if ($apply_all) {
                foreach (mvb_Model_API::getRoleList() as $role => $dummy) {
                    $config = mvb_Model_API::getRoleAccessConfig($role);
                    $config->setMenuOrder($_POST['menu']);
                    $config->saveConfig();
                }
            } else {
                $config = mvb_Model_API::getRoleAccessConfig($role);
                $config->setMenuOrder($_POST['menu']);
                $config->saveConfig();
            }
        }

        mvb_Model_Cache::clearCache();

        return array('status' => 'success');
    }

    /*
     * Export configurations
     *
     */

    protected function export() {

        $file = $this->render_config();
        $file_b = basename($file);

        if (file_exists($file)) {
            header('Content-Description: File Transfer');
            header('Content-Type: application/octet-stream');
            header('Content-Disposition: attachment; filename=' . basename($file_b));
            header('Content-Transfer-Encoding: binary');
            header('Expires: 0');
            header('Cache-Control: must-revalidate, post-check=0, pre-check=0');
            header('Pragma: public');
            header('Content-Length: ' . filesize($file));
            ob_clean();
            flush();
            readfile($file);
        }

        die();
    }

    /*
     * Render Config File
     *
     */

    private function render_config() {

        $file_path = WPACCESS_BASE_DIR . 'backups/' . uniqid(WPACCESS_PREFIX) . '.ini';
        $m = new mvb_Model_Manager($this->pObj);
        $m->render_config($file_path);

        return $file_path;
    }

    /*
     * Uploading file
     *
     */

    protected function upload_config() {

        $result = 0;
        if (isset($_FILES["config_file"])) {
            $fdata = $_FILES["config_file"];
            if (is_uploaded_file($fdata["tmp_name"]) && ($fdata["error"] == 0)) {
                $file_name = 'import_' . uniqid() . '.ini';
                $file_path = WPACCESS_BASE_DIR . 'backups/' . $file_name;
                $result = move_uploaded_file($fdata["tmp_name"], $file_path);
            }
        }

        $data = array(
            'status' => ($result ? 'success' : 'error'),
            'file_name' => $file_name
        );

        return $data;
    }

    /*
     * Add Current User to Blog and make him a Super Admin
     *
     */

    protected function add_blog_admin() {

        $user_id = get_current_user_id();
        $blog_id = get_current_blog_id();
        $ok = add_user_to_blog($blog_id, $user_id, WPACCESS_ADMIN_ROLE);

        if ($ok) {
            mvb_Model_API::getCurrentUser()->add_role(WPACCESS_ADMIN_ROLE);
            mvb_Model_API::getCurrentUser()->add_role(WPACCESS_SADMIN_ROLE);
            $result = array('status' => 'success', 'message' => mvb_Model_Label::get('LABEL_154'));
        } else {
            $result = array('status' => 'error', 'message' => mvb_Model_Label::get('LABEL_155'));
        }

        return $result;
    }

    /*
     * Create super admin User Role
     */

    protected function create_super() {
        global $wpdb;

        $answer = intval($_POST['answer']);
        $user_id = get_current_user_id();

        if ($answer == 1) {
            $role_list = mvb_Model_API::getRoleList(FALSE);

            if (isset($role_list[WPACCESS_SADMIN_ROLE])) {
                $result = array(
                    'result' => 'success',
                    'new_role' => WPACCESS_SADMIN_ROLE
                );
            } else {
                $result = $this->create_role('Super Admin', mvb_Model_API::getAllCapabilities(), FALSE);
            }

            if ($result['result'] == 'success') {
                //update current user role
                if (!is_user_member_of_blog($user_id, get_current_blog_id())) {
                    $result = $this->add_blog_admin();
                } else {
                    $this->assign_role(WPACCESS_SADMIN_ROLE, $user_id);
                }
                if ($result['result'] == 'success') {
                    $this->deprive_role($user_id, WPACCESS_SADMIN_ROLE, WPACCESS_ADMIN_ROLE);
                    mvb_Model_API::updateBlogOption(WPACCESS_PREFIX . 'first_time', $answer);
                }
            } else {
                $result = array('result' => 'error');
            }
        } else {
            $result = array('result' => 'error');
        }

        return $result;
    }

    /*
     * Assigne Role to User
     *
     */

    protected function assign_role($role, $user_id) {

        $m = new mvb_Model_User($user_id);
        $m->add_role($role);
    }

    /**
     * Delete User Role for other Users
     *
     * @param int Skip User's ID
     * @param string User Role
     * @param string Role to Replace with
     */
    protected function deprive_role($skip_id, $role, $replace_role) {
        global $wpdb;

        //TODO Should be better way to grab the list of users
        $blog = mvb_Model_API::getCurrentBlog();
        $query = "SELECT user_id FROM {$wpdb->usermeta} WHERE ";
        $query .= 'meta_key = "' . $blog->getPrefix() . 'capabilities"';
        $list = $wpdb->get_results($query);

        if (is_array($list) && count($list)) {
            foreach ($list as $row) {
                if ($row->user_id == $skip_id) {
                    continue;
                }
                $m = new mvb_Model_User($row->user_id);

                if ($m->has_cap($role)) {
                    $m->remove_role($role);
                    $m->add_role($replace_role);
                }
            }
        }
    }

}

?>