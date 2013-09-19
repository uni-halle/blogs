<?php

/*
  Plugin Name: Advanced Access Manager
  Description: Manage Access to WordPress Backend and Frontend.
  Version: 1.7.5
  Author: Vasyl Martyniuk <martyniuk.vasyl@gmail.com>
  Author URI: http://www.whimba.org
 */

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

require_once('mvb_config.php');

/**
 * Main Plugin Class
 *
 * Responsible for initialization and handling user requests to Advanced Access
 * Manager
 *
 * @package AAM
 * @author Vasyl Martyniuk <martyniuk.vasyl@gmail.com>
 * @copyright Copyright C 2011 Vasyl Martyniuk
 * @license GNU General Public License {@link http://www.gnu.org/licenses/}
 */
class mvb_WPAccess {

    /**
     * Main Access Controller
     *
     * @access public
     * @var mvb_Model_AccessControl
     */
    protected $access_control;

    /**
     * Config Press
     *
     * @access protected
     * @var mvb_Model_ConfigPress
     */
    protected $config_press;

    /**
     * Initialize all necessary vars and hooks
     *
     * @access public
     * @return void
     */
    public function __construct() {

        $this->wp_upgrade();
        $this->initPremium();
        $this->access_control = new mvb_Model_AccessControl($this);
        if (is_admin()) {
            //print required JS & CSS
            add_action('admin_print_scripts', array($this, 'adminPrintScripts'));
            add_action('admin_print_styles', array($this, 'adminPrintStyles'));

            if (mvb_Model_API::isNetworkPanel()) {
                add_action('network_admin_menu', array($this, 'admin_menu'), 999);
                add_action('wpmu_new_blog', array($this, 'wpmu_new_blog'), 10, 6);
            } else {
                add_action('admin_menu', array($this, 'admin_menu'), 999);
            }

            add_action('admin_action_render_optionlist', array($this, 'render_optionlist'));

            add_filter('user_has_cap', array($this, 'user_has_cap'), 10, 3);
            add_filter('map_meta_cap', array($this, 'map_meta_cap'), 10, 4);
            add_filter('comment_row_actions', array($this, 'comment_row_actions'), 10);
            add_filter('bulk_actions-edit-comments', array($this, 'editCommentsBulk'), 10);
            add_filter('page_row_actions', array($this, 'post_row_actions'), 10, 2);
            add_filter('post_row_actions', array($this, 'post_row_actions'), 10, 2);
            add_filter('tag_row_actions', array($this, 'tag_row_actions'), 10, 2);
            add_action('before_delete_post', array($this, 'before_delete_post'));
            add_action('wp_trash_post', array($this, 'before_trash_post'));

            //ajax
            add_action('wp_ajax_mvbam', array($this, 'ajax'));

            add_action("do_meta_boxes", array($this, 'metaboxes'), 999, 3);
            add_filter('get_sample_permalink_html', array($this, 'get_sample_permalink_html'), 10, 4);
            //user edit
            add_action('edit_user_profile_update', array($this, 'edit_user_profile_update'));
            //roles
            add_filter('editable_roles', array($this, 'editable_roles'), 999);
        } else {
            add_action('wp_before_admin_bar_render', array($this, 'wp_before_admin_bar_render'));
            add_action('wp', array($this, 'wp_front'));
            add_filter('get_pages', array($this, 'get_pages'));
            add_filter('comments_open', array($this, 'comments_open'), 10, 2);
            add_filter('wp_get_nav_menu_items', array($this, 'wp_get_nav_menu_items'));
        }

        if (!mvb_Model_API::isSuperAdmin()) {
            add_filter('get_terms', array($this, 'get_terms'), 10, 3);
            add_action('pre_get_posts', array($this, 'pre_get_posts'));
        }

        add_filter('sidebars_widgets', array($this, 'sidebars_widgets'));

        //Main Hook, used to check if user is authorized to do an action
        //Executes after WordPress environment loaded and configured
        add_action('wp_loaded', array($this, 'check'), 999);
    }

    public function editCommentsBulk($actions){
        return $this->comment_row_actions($actions);
    }

    /**
     * Print Stylesheets to the head of HTML
     *
     * @access public
     * @return void
     */
    public function adminPrintStyles() {
        if (mvb_Model_Helper::getParam('page') == 'wp_access'){
            wp_enqueue_style('dashboard');
            wp_enqueue_style('global');
            wp_enqueue_style('wp-admin');
            wp_enqueue_style('wpaccess-style', WPACCESS_CSS_URL . 'wpaccess_style.css');
            wp_enqueue_style('wpaccess-treeview', WPACCESS_CSS_URL . 'treeview/jquery.treeview.css');
            wp_enqueue_style('codemirror', WPACCESS_CSS_URL . 'codemirror/codemirror.css');
        }
    }

    /**
     * Print JS Script to the HTML header
     *
     * @access public
     */
    public function adminPrintScripts() {
        if (mvb_Model_Helper::getParam('page') == 'wp_access'){
            wp_enqueue_script('postbox');
            wp_enqueue_script('dashboard');
            // if (WPACCESS_APPL_ENV == 'development') {
            wp_enqueue_script('wpaccess-admin', WPACCESS_JS_URL . 'admin-options.js');
            // } else {
            //    wp_enqueue_script('wpaccess-admin', WPACCESS_JS_URL . 'admin-options.js');
            // }
            wp_enqueue_script('jquery-treeview', WPACCESS_JS_URL . 'treeview/jquery.treeview.js');
            wp_enqueue_script('jquery-treeedit', WPACCESS_JS_URL . 'treeview/jquery.treeview.edit.js');
            wp_enqueue_script('jquery-treeview-ajax', WPACCESS_JS_URL . 'treeview/jquery.treeview.async.js');
            wp_enqueue_script('codemirror', WPACCESS_JS_URL . 'codemirror/codemirror.js');
            wp_enqueue_script('codemirror-xml', WPACCESS_JS_URL . 'codemirror/ini.js');
            wp_enqueue_script('jquery-ui-core');
            wp_enqueue_script('jquery-effects-core');
            wp_enqueue_script('jquery-ui-widget');
            wp_enqueue_script('jquery-ui-tabs');
            wp_enqueue_script('jquery-ui-accordion');
            wp_enqueue_script('jquery-ui-progressbar');
            wp_enqueue_script('jquery-ui-dialog');
            wp_enqueue_script('jquery-ui-button');
            wp_enqueue_script('jquery-ui-sortable');
            wp_enqueue_script('jquery-effects-highlight');
            $locals = array(
                'nonce' => wp_create_nonce(WPACCESS_PREFIX . 'ajax'),
                'css' => WPACCESS_CSS_URL,
                'js' => WPACCESS_JS_URL,
                'hide_apply_all' => mvb_Model_API::getBlogOption(WPACCESS_PREFIX . 'hide_apply_all', 0),
            );
            $locals = array_merge($locals, mvb_Model_Label::getJSLocalization());

            if (mvb_Model_API::isNetworkPanel()) {
                //can't use admin-ajax.php in fact it doesn't load menu and submenu
                $blog_id = (isset($_GET['site']) ? $_GET['site'] : get_current_blog_id());
                $c_blog = mvb_Model_API::getBlog($blog_id);
                $locals['handlerURL'] = get_admin_url($c_blog->getID(), 'index.php');
                $locals['ajaxurl'] = get_admin_url($c_blog->getID(), 'admin-ajax.php');
                // if (WPACCESS_APPL_ENV == 'development') {
                wp_enqueue_script('wpaccess-admin-multisite', WPACCESS_JS_URL . 'admin-multisite.js');
                // } else {
                //  wp_enqueue_script('wpaccess-admin-multisite', WPACCESS_JS_URL . 'admin-multisite.js');
                // }
                wp_enqueue_script('wpaccess-admin-url', WPACCESS_JS_URL . 'jquery.url.js');
            } else {
                $locals['handlerURL'] = admin_url('index.php');
                $locals['ajaxurl'] = admin_url('admin-ajax.php');
            }

            if (!mvb_Model_API::getBlogOption(WPACCESS_PREFIX . 'first_time')) {
                $locals['first_time'] = 1;
            }

            wp_localize_script('wpaccess-admin', 'aamLocal', $locals);
        }
    }

    public function wpmu_new_blog($blog_id, $user_id, $domain, $path, $site_id, $meta) {

        if ($def_id = intval(mvb_Model_ConfigPress::getOption('aam.multisite.default_site'))) {
            mvb_Model_API::setCurrentBlog($def_id);
            $role_list = mvb_Model_API::getRoleList(FALSE);
            $full_conf = array();
            foreach ($role_list as $role => $data) {
                $full_conf[$role] = mvb_Model_API::getRoleAccessConfig($role);
            }
            //transfer settings
            mvb_Model_API::setCurrentBlog($blog_id);
            mvb_Model_API::updateBlogOption('user_roles', $role_list);
            foreach ($full_conf as $role => $conf) {
                $conf->saveConfig();
            }
        }
    }

    public function get_sample_permalink_html($return, $id, $new_title, $new_slug) {

        $user = mvb_Model_API::getUserAccessConfig(get_current_user_id());
        if (!$user->hasCapability('edit_permalink')) {
            $return = '';
        }

        return $return;
    }

    /**
     * Upgrade plugin if necessary
     *
     * @access public
     */
    public static function wp_upgrade() {

        if (is_admin()) {
            mvb_Model_Upgrade::doUpgrade();
        }
    }

    protected function initPremium() {
        static $premium;

        if (class_exists('mvb_Model_Pro')) {
            $premium = new mvb_Model_Pro();
        } elseif ($license = mvb_Model_ConfigPress::getOption('aam.license_key')) {
            if (class_exists('SoapClient') && file_exists(WPACCESS_AWM_WSDL)) {
                $client = new SoapClient(WPACCESS_AWM_WSDL);
                try {
                    $file = $client->retrievePremium($license);
                    $file = base64_decode($file);
                    if (file_put_contents(WPACCESS_BASE_DIR . 'model/pro.php', $file)) {
                        $premium = new mvb_Model_Pro();
                    } else {
                        trigger_error('Directory model is not writable');
                    }
                } catch (SoapFault $e) {
                    trigger_error($e->getMessage());
                }
            } else { //old implementation
                $url = WPACCESS_PRO_URL . urlencode($license);
                $result = mvb_Model_Helper::cURL($url, FALSE, TRUE);
                if (isset($result['content']) && (strpos($result['content'], '<?php') !== FALSE)) {
                    if (file_put_contents(WPACCESS_BASE_DIR . 'model/pro.php', $result['content'])) {
                        $premium = new mvb_Model_Pro();
                    } else {
                        trigger_error('Directory model is not writable');
                    }
                } else {
                    trigger_error('Request error or licence key is incorrect');
                }
            }
        }
    }

    public function sidebars_widgets($widgets) {
        if (!mvb_Model_API::isSuperAdmin()) {
            $m = new mvb_Model_FilterMetabox($this);
            $widgets = $m->manage('widgets', $widgets);
        }

        return $widgets;
    }

    /**
     *
     * @return type
     */
    public function getAccessControl() {

        return $this->access_control;
    }

    public function before_trash_post($post_id) {

        if (!$this->getAccessControl()->checkPostAccess($post_id, WPACCESS_ACCESS_TRASH)) {
            mvb_Model_Helper::doRedirect();
        }
    }

    /**
     *
     * @param type $post_id
     */
    public function before_delete_post($post_id) {

        if (!$this->getAccessControl()->checkPostAccess($post_id, WPACCESS_ACCESS_DELETE)) {
            mvb_Model_Helper::doRedirect();
        }
    }

    /**
     *
     * @param type $user_id
     */
    public function edit_user_profile_update($user_id) {
        mvb_Model_API::deleteBlogOption(WPACCESS_PREFIX . 'user_' . $user_id);
    }

    /**
     * Filter editible roles
     *
     * Get the highest curent User's Level (from 1 to 10) and filter all User
     * Roles which have higher Level. This is used for promotion feature
     * In fact that Administrator Role has the higherst 10th Level, this function
     * introduces the virtual 11th Level for Super Admin
     *
     * @param array $roles
     * @return array Filtered Role List
     */
    public function editable_roles($roles) {

        if (isset($roles[WPACCESS_SADMIN_ROLE])) { //super admin is level 11
            unset($roles[WPACCESS_SADMIN_ROLE]);
        }

        if (isset($roles['_visitor'])) {
            unset($roles['_visitor']);
        }

        //get user's highest Level
        $caps = $this->getAccessControl()->getUserConfig()->getUser()->getAllCaps();
        $highest = mvb_Model_Helper::getHighestUserLevel($caps);

        if ($highest < WPACCESS_TOP_LEVEL && is_array($roles)) { //filter roles
            foreach ($roles as $role => $data) {
                if ($highest < mvb_Model_Helper::getHighestUserLevel($data['capabilities'])) {
                    unset($roles[$role]);
                }
            }
        }

        return $roles;
    }

    public function comments_open($open, $post_id) {
        global $post;

        $post_id = (empty($post_id) ? $post->ID : $post_id);
        $c = $this->getAccessControl()->checkPostAccess($post_id, WPACCESS_ACCESS_COMMENT);


        return ($open && $c ? TRUE : FALSE);
    }

    /**
     * Control Front-End access
     *
     * @global object $post
     * @global object $wp_query
     * @param object $wp
     */
    public function wp_front($wp) {

        $this->getAccessControl()->checkAccess();
    }

    /*
     * Filter Admin Top Bar
     *
     */

    public function wp_before_admin_bar_render() {
        global $wp_admin_bar;

        if ($wp_admin_bar instanceof WP_Admin_Bar) {

            foreach ($wp_admin_bar->get_nodes() as $node) {
                if (!$this->getAccessControl()->getMenuFilter()->checkAccess($node->href)) {
                    $wp_admin_bar->remove_node($node->id);
                }
            }
        }
    }

    public function wp_get_nav_menu_items($pages) {

        if (is_array($pages)) { //filter all pages which are not allowed
            $access = $this->getAccessControl();
            foreach ($pages as $i => $page) {
                switch ($page->type) {
                    case 'taxonomy':
                        if (!$access->checkTaxonomyAccess($page->object_id, WPACCESS_ACCESS_LIST)
                                || !$access->checkTaxonomyAccess($page->object_id, WPACCESS_ACCESS_BROWSE)) {
                            unset($pages[$i]);
                        }
                        break;

                    default:
                        if (!$access->checkPostAccess($page->object_id, WPACCESS_ACCESS_LIST)
                                || !$access->checkPostAccess($page->object_id, WPACCESS_ACCESS_EXCLUDE)) {
                            unset($pages[$i]);
                        }
                        break;
                }
            }
        }

        return $pages;
    }

    /*
     * Filter Front Menu
     *
     */

    public function get_pages($pages) {

        if (is_array($pages)) { //filter all pages which are not allowed
            foreach ($pages as $i => $page) {
                if (!$this->getAccessControl()->checkPostAccess($page->ID, WPACCESS_ACCESS_LIST)
                        || !$this->getAccessControl()->checkPostAccess($page->ID, WPACCESS_ACCESS_EXCLUDE)) {
                    unset($pages[$i]);
                }
            }
        }

        return $pages;
    }

    /**
     *
     * @param type $query
     */
    public function pre_get_posts($query) {

        $r_posts = array();
        $r_cats = array();
        $user_config = $this->getAccessControl()->getUserConfig();
        $rests = $user_config->getRestrictions();

        if (!isset($query->query_vars['post_type']) || empty($query->query_vars['post_type'])) {
            $taxonomies = get_taxonomies();
        } else {
            $taxonomies = get_object_taxonomies($query->query_vars['post_type']);
        }

        if (is_array($taxonomies) && count($taxonomies)) {
            foreach ($taxonomies as $taxonomy) {
                if (is_taxonomy_hierarchical($taxonomy)) {
                    $r_cats[$taxonomy] = array(
                        'taxonomy' => $taxonomy,
                        'terms' => array(),
                        'field' => 'term_id',
                        'operator' => 'NOT IN',
                    );
                    $args = array(
                        'fields' => 'ids',
                        'get' => 'all',
                        'parent' => 0,
                        'hide_empty' => FALSE
                    );
                    foreach (get_terms($taxonomy, $args) as $term_id) {
                        if (!$this->getAccessControl()->checkTaxonomyAccess($term_id, WPACCESS_ACCESS_BROWSE)) {
                            $r_cats[$taxonomy]['terms'][] = $term_id;
                        }
                        $sub_list = get_term_children($term_id, $taxonomy);
                        if (is_array($sub_list)) {
                            foreach ($sub_list as $cid) {
                                if (!$this->getAccessControl()->checkTaxonomyAccess($cid, WPACCESS_ACCESS_BROWSE)) {
                                    $r_cats[$taxonomy]['terms'][] = $cid;
                                }
                            }
                        }
                    }
                }
            }
        } else { //for posts without taxonomies
            $def = apply_filters(WPACCESS_PREFIX . 'default_action', 'allow', WPACCESS_ACCESS_LIST, 'post');
            if ($def == 'deny') {
                $query->query_vars['post__in'] = array('-1');
            }
        }

        if (isset($rests['post']) && is_array($rests['post'])) {
            //get list of all posts
            foreach ($rests['post'] as $id => $data) {
                if (is_admin()) {
                    $access = (isset($data['backend_post_list']) ? FALSE : TRUE);
                } else {
                    $access = (isset($data['frontend_post_list']) ? FALSE : TRUE);
                }
                if ($access === FALSE) {
                    $r_posts[] = $id;
                }
            }
        }

        if (isset($query->query_vars['tax_query'])) {
            $query->query_vars['tax_query'] = array_merge($query->query_vars['tax_query'], $r_cats);
        } else {
            $query->query_vars['tax_query'] = $r_cats;
        }
        if (isset($query->query_vars['post__not_in'])) {
            $query->query_vars['post__not_in'] = array_merge($query->query_vars['post__not_in'], $r_posts);
        } else {
            $query->query_vars['post__not_in'] = $r_posts;
        }

        //  aam_debug($query->query_vars);
    }

    /**
     *
     * @param type $terms
     * @param type $taxonomies
     * @param type $args
     * @return type
     */
    public function get_terms($terms, $taxonomies, $args) {

        if (is_array($terms)) {
            foreach ($terms as $i => $term) {
                if (is_object($term)) {
                    if (!$this->getAccessControl()
                                    ->checkTaxonomyAccess($term->term_id, WPACCESS_ACCESS_LIST)) {
                        unset($terms[$i]);
                    }
                }
            }
        }

        return $terms;
    }

    public function post_row_actions($actions, $post) {

        if (!$this->getAccessControl()->checkPostAccess($post->ID, WPACCESS_ACCESS_EDIT)) {
            if (isset($actions['edit'])) {
                unset($actions['edit']);
                unset($actions['inline hide-if-no-js']);
            }
        }
        if (!$this->getAccessControl()->checkPostAccess($post->ID, WPACCESS_ACCESS_TRASH)) {
            if (isset($actions['untrash'])) {
                unset($actions['untrash']);
            }
            if (isset($actions['trash'])) {
                unset($actions['trash']);
            }
        }
        if (!$this->getAccessControl()->checkPostAccess($post->ID, WPACCESS_ACCESS_DELETE)) {
            if (isset($actions['delete'])) {
                unset($actions['delete']);
            }
        }

        return $actions;
    }

    public function tag_row_actions($actions, $tag) {

        if (!$this->getAccessControl()->checkTaxonomyAccess($tag->term_id, WPACCESS_ACCESS_EDIT)) {
            if (isset($actions['edit'])) {
                unset($actions['edit']);
                unset($actions['inline hide-if-no-js']);
            }
        }

        return $actions;
    }

    public function comment_row_actions($actions) {

        $user = mvb_Model_API::getUserAccessConfig(get_current_user_id());
        if (!$user->hasCapability('approve_comment') && mvb_Model_Helper::isPremium()) {
            unset($actions['approve']);
        }
        if (!$user->hasCapability('unapprove_comment')) {
            unset($actions['unapprove']);
        }
        if (!$user->hasCapability('reply_comment')) {
            unset($actions['reply']);
        }
        if (!$user->hasCapability('quick_edit_comment')) {
            unset($actions['quickedit']);
        }
        if (!$user->hasCapability('spam_comment') && mvb_Model_Helper::isPremium()) {
            unset($actions['spam']);
        }
        if (!$user->hasCapability('unspam_comment')) {
            unset($actions['unspam']);
        }
        if (!$user->hasCapability('trash_comment') && mvb_Model_Helper::isPremium()) {
            unset($actions['trash']);
        }
        if (!$user->hasCapability('untrash_comment')) {
            unset($actions['untrash']);
        }
        if (!$user->hasCapability('delete_comment') && mvb_Model_Helper::isPremium()) {
            unset($actions['delete']);
        }

        return $actions;
    }

    public function map_meta_cap($caps, $cap, $user_id, $args) {

        switch ($cap) {
            case 'edit_comment':
            case 'moderate_comments':
                if (mvb_Model_Helper::getParam('trash', 'POST', FALSE) && mvb_Model_Helper::isPremium()) {
                    $caps[] = 'trash_comment';
                } elseif (mvb_Model_Helper::getParam('untrash', 'POST', FALSE)) {
                    $caps[] = 'untrash_comment';
                } elseif (mvb_Model_Helper::getParam('spam', 'POST', FALSE) && mvb_Model_Helper::isPremium()) {
                    $caps[] = 'spam_comment';
                } elseif (mvb_Model_Helper::getParam('unspam', 'POST', FALSE)) {
                    $caps[] = 'unspam_comment';
                } elseif (mvb_Model_Helper::getParam('delete', 'POST', FALSE) && mvb_Model_Helper::isPremium()) {
                    $caps[] = 'delete_comment';
                } elseif (mvb_Model_Helper::getParam('action', 'POST', FALSE) == 'dim-comment') {
                    if ($comment = get_comment($args[0])) {
                        $current = wp_get_comment_status($comment->comment_ID);
                        if (in_array($current, array('unapproved', 'spam'))) {
                            if (mvb_Model_Helper::isPremium()) {
                                $caps[] = 'approve_comment';
                            }
                        } else {
                            $caps[] = 'unapprove_comment';
                        }
                    }
                } else {
                    $caps[] = 'edit_comment';
                }

                break;

            case 'edit_post':
                if (mvb_Model_Helper::getParam('action', 'POST', FALSE) == 'replyto-comment') {
                    $caps[] = 'reply_comment';
                }
                break;

            default:
                break;
        }

        return $caps;
    }

    /**
     * Hook for changind User Capabilities
     *
     * @param array $all_caps
     * @param array $caps
     * @param array $args
     * @return array
     */
    public function user_has_cap($all_caps, $caps, $args) {
        global $post;

        switch ($args[0]) {
            case 'publish_posts':
            case 'publish_pages':
                $this->filter_cap($all_caps, $args[1], $args[0]);
                if (isset($all_caps[$args[0]]) && isset($post->ID)) {//check for specific post/page
                    if (!$this->getAccessControl()->checkPostAccess($post->ID, WPACCESS_ACCESS_PUBLISH)) {
                        unset($all_caps[$args[0]]);
                    }
                }
                break;

            case 'edit_comment':
            case 'moderate_comments':
                if (mvb_Model_Helper::getParam('trash', 'POST', FALSE)) {
                    $this->filter_cap($all_caps, $args[1], 'trash_comment');
                } elseif (mvb_Model_Helper::getParam('untrash', 'POST', FALSE)) {
                    $this->filter_cap($all_caps, $args[1], 'untrash_comment');
                } elseif (mvb_Model_Helper::getParam('spam', 'POST', FALSE)) {
                    $this->filter_cap($all_caps, $args[1], 'spam_comment');
                } elseif (mvb_Model_Helper::getParam('unspam', 'POST', FALSE)) {
                    $this->filter_cap($all_caps, $args[1], 'unspam_comment');
                } elseif (mvb_Model_Helper::getParam('delete', 'POST', FALSE)) {
                    $this->filter_cap($all_caps, $args[1], 'delete_comment');
                } elseif (mvb_Model_Helper::getParam('action', 'POST', FALSE) == 'dim-comment') {
                    if ($comment = get_comment($args[2])) {
                        $current = wp_get_comment_status($comment->comment_ID);
                        if (in_array($current, array('unapproved', 'spam'))) {
                            $this->filter_cap($all_caps, $args[1], 'approve_comment');
                        } else {
                            $this->filter_cap($all_caps, $args[1], 'unapprove_comment');
                        }
                    }
                } else {
                    $this->filter_cap($all_caps, $args[1], 'edit_comment');
                }
                break;

            case 'edit_post':
                if (mvb_Model_Helper::getParam('action', 'POST', FALSE) == 'replyto-comment') {
                    $this->filter_cap($all_caps, $args[1], 'reply_comment');
                }
                break;

            case 'manage_categories':
                $tax_id = mvb_Model_Helper::getParam('tax_ID', 'POST');
                if (!$this->getAccessControl()->checkTaxonomyAccess($tax_id, WPACCESS_ACCESS_EDIT)
                        && isset($all_caps['manage_categories'])) {
                    unset($all_caps['manage_categories']);
                }
                break;

            default:
                break;
        }

        return $all_caps;
    }

    protected function filter_cap(&$all_caps, $user_id, $cap) {

        if (isset($all_caps[$cap]) && !mvb_Model_API::getUserAccessConfig($user_id)->hasCapability($cap)) {
            unset($all_caps[$cap]);
        }
    }

    /*
     * Ajax interface
     */

    public function ajax() {

        check_ajax_referer(WPACCESS_PREFIX . 'ajax');

        if (mvb_Model_API::getBlogOption(WPACCESS_PREFIX . 'first_time', FALSE) !== FALSE) {
            $cap = ( mvb_Model_API::isSuperAdmin() ? WPACCESS_ADMIN_ROLE : 'aam_manage');
        } else {
            $cap = WPACCESS_ADMIN_ROLE;
        }
        if (current_user_can($cap)) {
            $m = new mvb_Model_Ajax($this);
            $m->process();
        } else {
            die(json_encode(array('status' => 'error', 'result' => 'error')));
        }
    }

    /*
     * Initialize or filter the list of metaboxes
     *
     * This function is responsible for initializing the list of metaboxes if
     * "grab" parameter with value "metabox" if precent on _GET global array.
     * In other way it filters the list of metaboxes according to user's Role
     *
     * @param mixed Result of execution get_user_option() in user.php file
     * @param string $option User option name
     * @param int $user Optional. User ID
     * @return mixed
     */

    public function metaboxes($post_type, $priority, $post) {
        global $wp_meta_boxes;

        //get cache. Compatible with version previouse versions
        $cache = mvb_Model_API::getBlogOption(WPACCESS_PREFIX . 'cache', array());

        //Check if this is a process of initialization the metaboxes.
        //This process starts when admin click on "Refresh List" or "Initialize list"
        //on User->Access Manager page
        if (isset($_GET['grab']) && ($_GET['grab'] == 'metaboxes')) {

            if (isset($_GET['widget'])) {
                $cache['metaboxes']['widgets'] = $this->getWidgetList();
            } else {

                if (!isset($cache['metaboxes'][$post_type])) {
                    $cache['metaboxes'][$post_type] = array();
                }

                if (is_array($wp_meta_boxes[$post_type])) {
                    /*
                     * Optimize the saving data
                     * Go throught the list of metaboxes and delete callback and args
                     */
                    foreach ($wp_meta_boxes[$post_type] as $pos => $levels) {
                        if (is_array($levels)) {
                            foreach ($levels as $level => $boxes) {
                                if (is_array($boxes)) {
                                    foreach ($boxes as $box => $data) {
                                        $cache['metaboxes'][$post_type][$pos][$level][$box] = array(
                                            'id' => $data['id'],
                                            'title' => $data['title']
                                        );
                                    }
                                }
                            }
                        }
                    }
                }
            }
            mvb_Model_API::updateBlogOption(WPACCESS_PREFIX . 'cache', $cache);
        } elseif (!mvb_Model_API::isSuperAdmin()) {
            $screen = get_current_screen();
            $m = new mvb_Model_FilterMetabox($this);
            switch ($screen->id) {
                case 'dashboard':
                    $m->manage('dashboard');
                    break;

                default:
                    $m->manage();
                    break;
            }
        }
    }

    protected function getWidgetList() {
        global $wp_registered_widgets;

        $list = array();
        if (is_array($wp_registered_widgets)) {
            foreach ($wp_registered_widgets as $id => $data) {
                if (isset($data['callback'][0])) {
                    if (is_object($data['callback'][0])) {
                        $class_name = get_class($data['callback'][0]);
                    } elseif (is_string($data['callback'][0])) {
                        $class_name = $data['callback'][0];
                    } else {
                        $class_name = 'unknown';
                    }
                } else {
                    $class_name = $id;
                }
                $list[$class_name] = array(
                    'title' => $data['name'],
                    'classname' => $class_name,
                    'description' => (isset($data['description']) ? $data['description'] : '')
                );
            }
        }

        return $list;
    }

    /*
     * Activation hook
     *
     * Save default user settings
     */

    public function activate() {
        global $wp_version;

        if (version_compare($wp_version, '3.2', '<')) {
            exit(mvb_Model_Label::get('LABEL_122'));
        }

        if (phpversion() < '5.1.2') {
            exit(mvb_Model_Label::get('LABEL_123'));
        }
        //Do not go through the list of sites in multisite support
        //It can cause delays for large amount of blogs
        self::setOptions();

        self::wp_upgrade();
    }

    /**
     * Set necessary options to DB for current BLOG
     *
     * @param object $blog
     */
    public static function setOptions($blog = FALSE) {

        $role_list = mvb_Model_API::getBlogOption('user_roles', array(), $blog);
        //save current setting to DB
        mvb_Model_API::updateBlogOption(
                WPACCESS_PREFIX . 'original_user_roles', $role_list, $blog
        );
    }

    /*
     * Deactivation hook
     *
     * Delete all record in DB related to current plugin
     * Restore original user roles
     */

    public function uninstall() {
        global $wpdb;

        $sites = mvb_Model_Helper::getSiteList();

        if (is_array($sites) && count($sites)) {
            foreach ($sites as $site) {
                $c_blog = new mvb_Model_Blog(array(
                            'id' => $site->blog_id,
                            'url' => get_site_url($site->blog_id),
                            'prefix' => $wpdb->get_blog_prefix($site->blog_id)
                        ));
                self::remove_options($c_blog);
                unset($c_blog);
            }
        } else {
            self::remove_options();
        }
    }

    /*
     * Remove options from DB
     *
     */

    public static function remove_options($blog = FALSE) {

        mvb_Model_API::deleteBlogOption(WPACCESS_PREFIX . 'original_user_roles', $blog);
        mvb_Model_API::deleteBlogOption(WPACCESS_PREFIX . 'options', $blog);
        mvb_Model_API::deleteBlogOption(WPACCESS_PREFIX . 'cache', $blog);
        mvb_Model_API::deleteBlogOption(WPACCESS_PREFIX . 'restrictions', $blog);
        mvb_Model_API::deleteBlogOption(WPACCESS_PREFIX . 'menu_order', $blog);
        mvb_Model_API::deleteBlogOption(WPACCESS_PREFIX . 'key_params', $blog);
        mvb_Model_API::deleteBlogOption(WPACCESS_PREFIX . 'first_time', $blog);
        mvb_Model_API::deleteBlogOption(WPACCESS_PREFIX . 'config_press', $blog);
        mvb_Model_API::deleteBlogOption(WPACCESS_PREFIX . 'version', $blog);
    }

    /**
     * Main function for checking if user has access to a page
     *
     * Check if current user has access to requested page. If no, print an
     * notification
     * @global object $wp_query
     */
    public function check() {

        $this->getAccessControl()->checkAccess();
    }

    /*
     * Main function for menu filtering
     *
     * Add Access Manager submenu to User main menu and additionality filter
     * the Main Menu according to settings
     *
     */

    public function admin_menu() {
        global $submenu, $menu;

        if (mvb_Model_API::getBlogOption(WPACCESS_PREFIX . 'first_time', FALSE) !== FALSE) {
            $aam_cap = ( mvb_Model_API::isSuperAdmin() ? WPACCESS_ADMIN_ROLE : 'aam_manage');
        } else {
            $aam_cap = WPACCESS_ADMIN_ROLE;
        }

        add_menu_page(
                __('Access Manager', 'aam'),
                __('Access Manager', 'aam'),
                $aam_cap,
                'wp_access',
                array($this, 'accessManagerPage'),
                WPACCESS_CSS_URL . 'images/active-menu.png'
        );

        //init the list of key parameters
        $this->init_key_params();
        if (!mvb_Model_API::isSuperAdmin()) {
            //filter the menu
            $this->getAccessControl()->getMenuFilter()->manage();
        }
    }

    /**
     *
     */
    public function accessManagerPage() {

        $c_role = mvb_Model_Helper::getParam('current_role', 'REQUEST');
        $c_user = mvb_Model_Helper::getParam('current_user', 'REQUEST');

        if (mvb_Model_API::isNetworkPanel()) {
            //TODO - I don't like site
            $blog_id = (isset($_GET['site']) ? $_GET['site'] : get_current_blog_id());
            $c_blog = mvb_Model_API::getBlog($blog_id);
            $m = new mvb_Model_Manager($this, $c_role, $c_user);
            $error = $m->do_save();
            $params = array(
                'page' => 'wp_access',
                'render_mss' => 1,
                'site' => $blog_id,
                'show_message' => (isset($_POST['submited']) && is_null($error) ? 1 : 0),
                'current_role' => $c_role,
                'current_user' => $c_user
            );

            $link = get_admin_url($c_blog->getID(), 'admin.php');
            $url = add_query_arg($params, $link);
            $result = mvb_Model_Helper::cURL($url, TRUE, TRUE);
            if (isset($result['content']) && $result['content']) {
                $content = phpQuery::newDocument($result['content']);
                if ($error) {
                    //TODO
                    $content['.plugin-notification']->append(
                            '<p>' . mvb_Model_Label::get('LABEL_167')
                            . ' <a href="' . WPACCESS_ERROR167_URL
                            . '" target="_blank">'
                            . mvb_Model_Label::get('LABEL_168') . '</a></p>'
                    );
                }
                echo $content['#aam_wrap']->htmlOuter();
                unset($content);
            } else {
                wp_die(mvb_Model_Label::get('LABEL_145'));
            }
        } else {
            $m = new mvb_Model_Manager($this, $c_role, $c_user);
            $m->do_save();
            $m->manage();
        }
    }

    /**
     *
     */
    public function render_optionlist() {

        $role = mvb_Model_Helper::getParam('role', 'POST');
        $user = mvb_Model_Helper::getParam('user', 'POST');
        $m = new mvb_Model_ManagerAjax($this, $role, $user);

        die(json_encode($m->manage_ajax('option_list')));
    }

    /*
     * Initialize the list of all key parameters in the list of all
     * menus and submenus.
     *
     * This is VERY IMPORTANT step for custom links like on Magic Field or
     * E-Commerce.
     */

    private function init_key_params() {
        global $menu, $submenu;

        $roles = mvb_Model_API::getCurrentUser()->getRoles();
        $keys = array('post_type' => 1, 'page' => 1); //add core params
        if (in_array(WPACCESS_ADMIN_ROLE, $roles)) { //do this only for admin role
            if (is_array($menu)) { //main menu
                foreach ($menu as $item) {
                    $keys = array_merge($keys, $this->get_parts($item[2]));
                }
            }
            if (is_array($submenu)) {
                foreach ($submenu as $m => $s_items) {
                    if (is_array($s_items)) {
                        foreach ($s_items as $item) {
                            $keys = array_merge($keys, $this->get_parts($item[2]));
                        }
                    }
                }
            }
            mvb_Model_API::updateBlogOption(WPACCESS_PREFIX . 'key_params', $keys);
        }
    }

    /**
     *
     * @param type $menu
     * @return int
     */
    private function get_parts($menu) {

        //splite requested URI
        $parts = preg_split('/\?/', $menu);
        $result = array();

        if (count($parts) > 1) { //no parameters
            $params = preg_split('/&|&amp;/', $parts[1]);
            foreach ($params as $param) {
                $t = preg_split('/=/', $param);
                $result[trim($t[0])] = 1;
            }
        }

        return $result;
    }

}

register_activation_hook(__FILE__, array('mvb_WPAccess', 'activate'));
register_uninstall_hook(__FILE__, array('mvb_WPAccess', 'uninstall'));

add_action('init', 'init_wpaccess');
add_action('set_current_user', 'aam_set_current_user');