<?php

/**
 * ======================================================================
 * LICENSE: This file is subject to the terms and conditions defined in *
 * file 'license.txt', which is part of this source code package.       *
 * ======================================================================
 */

/**
 * Backend manager
 * 
 * @package AAM
 * @author Vasyl Martyniuk <vasyl@vasyltech.com>
 */
class AAM_Backend_Manager {

    /**
     * Single instance of itself
     * 
     * @var AAM_Backend_Manager
     * 
     * @access private 
     */
    private static $_instance = null;

    /**
     * Initialize the object
     * 
     * @return void
     * 
     * @access protected
     */
    protected function __construct() {
        //check if user is allowed to see backend
        $this->checkUserAccess();
        
        //check if user switch is required
        $this->checkUserSwitch();
        
        //print required JS & CSS
        add_action('admin_print_scripts', array($this, 'printJavascript'));
        add_action('admin_print_styles', array($this, 'printStylesheet'));

        //manager Admin Menu
        if (is_multisite() && is_network_admin()) {
            //register AAM in the network admin panel
            add_action('network_admin_menu', array($this, 'adminMenu'), 999);
        } else {
            add_action('admin_menu', array($this, 'adminMenu'), 999);
            add_action('all_admin_notices', array($this, 'notification'));
        }
        
        if (AAM_Core_Config::get('render-access-metabox', true)) {
            add_action('edit_category_form_fields', array($this, 'renderTermMetabox'), 1);
            add_action('edit_link_category_form_fields', array($this, 'renderTermMetabox'), 1);
            add_action('edit_tag_form_fields', array($this, 'renderTermMetabox'), 1);
            
            //register custom access control metabox
            add_action('add_meta_boxes', array($this, 'metabox'));
        }
        
        //manager AAM Ajax Requests
        add_action('wp_ajax_aam', array($this, 'ajax'));
        //manager AAM Features Content rendering
        add_action('admin_action_aamc', array($this, 'renderContent'));
        //manager user search and authentication control
        add_filter('user_search_columns', array($this, 'searchColumns'));
        
        //manager WordPress metaboxes
        add_action("in_admin_header", array($this, 'initMetaboxes'), 999);
        
        if (AAM_Core_Config::get('show-access-link', true)) {
            //extend post inline actions
            add_filter('page_row_actions', array($this, 'postRowActions'), 10, 2);
            add_filter('post_row_actions', array($this, 'postRowActions'), 10, 2);

            //extend term inline actions
            add_filter('tag_row_actions', array($this, 'tagRowActions'), 10, 2);
            
            //manage access action to the user list
            add_filter('user_row_actions', array($this, 'userActions'), 10, 2);
        }
        
        //footer thank you
        add_filter('admin_footer_text', array($this, 'thankYou'), 999);
        
        //control admin area
        add_action('admin_init', array($this, 'adminInit'));
        
        //register login widget
        add_action('widgets_init', array($this, 'registerLoginWidget'));
        add_action('wp_ajax_nopriv_aamlogin', array($this, 'handleLogin'));
        
        //register backend hooks and filters
        if (AAM_Core_Config::get('backend-access-control', true)) {
            AAM_Backend_Filter::register();
        }
        
        //register CodePinch affiliate
        AAM_Backend_View_CodePinch::bootstrap();
    }
    
    /**
     * 
     * @return type
     */
    public function handleLogin() {
        $login = AAM_Core_Login::getInstance();

        echo json_encode($login->execute());
        exit;
    }
    
    /**
     * 
     */
    public function adminInit() {
        $user = AAM::getUser();
        
        if (AAM_Core_API::capabilityExists('aam_manage_posts')) {
            $cap = 'aam_manage_posts';
        } else {
            $cap = AAM_Core_Config::get(
                    AAM_Backend_Feature_Post::getAccessOption(), 
                    AAM_Backend_View::getAAMCapability()
            );
        }
        
        if (AAM_Core_Request::get('aamframe') && $user->hasCapability($cap)) {
            echo AAM_Backend_View::getInstance()->renderAccessFrame();
            exit;
        }
    }
    
    /**
     * 
     * @param type $text
     * @return string
     */
    public function thankYou($text) {
        if (AAM::isAAM()) {
            $text  = '<span id="footer-thankyou">';
            $text .= '<b>Please help us</b> and submit your review <a href="';
            $text .= 'https://wordpress.org/support/plugin/advanced-access-manager/reviews/"';
            $text .= 'target="_blank"><i class="icon-star"></i>';
            $text .= '<i class="icon-star"></i><i class="icon-star"></i>';
            $text .= '<i class="icon-star"></i><i class="icon-star"></i></a>';
            $text .= '</span>';
        }
        
        return $text;
    }
    
    /**
     * 
     */
    public function registerLoginWidget() {
        register_widget('AAM_Backend_Widget_Login');
    }
    
    /**
     * 
     */
    protected function checkUserAccess() {
        $uid = get_current_user_id();
        
        if ($uid && AAM_Core_API::capabilityExists('access_dashboard')) {
            $caps = AAM::getUser()->allcaps;
            if (empty($caps['access_dashboard'])) {
                //also additionally check for AJAX calls
                if (defined('DOING_AJAX') && empty($caps['allow_ajax_calls'])) {
                    AAM_Core_API::reject(
                            'backend', array('hook' => 'access_dashboard')
                    );
                } elseif (!defined('DOING_AJAX')) {
                    AAM_Core_API::reject(
                            'backend', array('hook' => 'access_dashboard')
                    );
                }
            }
        }
    }
    
    /**
     * 
     */
    protected function checkUserSwitch() {
        if (AAM_Core_Request::get('action') == 'aam-switch-back') {
            $current  = get_current_user_id();
            $uid      = AAM_Core_API::getOption('aam-user-switch-' . $current);
            $redirect = admin_url('admin.php?page=aam&user=' . $current);
            
            check_admin_referer('aam-switch-' . $uid);
            
            wp_clear_auth_cookie();
            wp_set_auth_cookie( $uid, true );
            wp_set_current_user( $uid );
            
            AAM_Core_API::deleteOption('aam-user-switch-' . $current);
            
            wp_redirect($redirect);
            exit;
        }
    }
    
    /**
     * 
     */
    public function notification() {
        $uid = AAM_Core_API::getOption('aam-user-switch-' . get_current_user_id());
        
        if ($uid) {
            //get user's name
            $user  = new WP_User($uid);
            $name = $user->display_name ? $user->display_name : $user->user_nicename;
            
            //generate switch back URL
            $url = wp_nonce_url(
                    'index.php?action=aam-switch-back', 'aam-switch-' . $uid
            );
            
            $style = 'padding: 10px; font-weight: 700; letter-spacing:0.5px;';
            
            echo '<div class="updated notice">';
            echo '<p style="' . $style . '">';
            echo sprintf('Switch back to <a href="%s">%s</a>.', $url, $name);
            echo '</p></div>';
        }
    }
    
    /**
     * 
     */
    public function metabox() {
        if (AAM_Core_API::capabilityExists('aam_manage_posts')) {
            $cap = 'aam_manage_posts';
        } else {
            $cap = AAM_Core_Config::get(
                    AAM_Backend_Feature_Post::getAccessOption(), 
                    AAM_Backend_View::getAAMCapability()
            );
        }
        
        $frontend = AAM_Core_Config::get('frontend-access-control', true);
        $backend  = AAM_Core_Config::get('backend-access-control', true);
        
        if (($frontend || $backend) && AAM::getUser()->hasCapability($cap)) {
            add_meta_box(
                'aam-acceess-manager', 
                __('Access Manager', AAM_KEY) . ' <small style="color:#999999;">by AAM plugin</small>', 
                array($this, 'renderPostMetabox'),
                null,
                'advanced',
                'high'
            );
        }
    }
    
    /**
     * 
     * @global type $post
     */
    public function renderPostMetabox() {
        global $post;
        
        if (is_a($post, 'WP_Post')) {
            echo AAM_Backend_View::getInstance()->renderPostMetabox($post);
        }
    }
    
    /**
     * 
     * @param type $term
     */
    public function renderTermMetabox($term) {
        if (is_a($term, 'WP_Term') && is_taxonomy_hierarchical($term->taxonomy)) {
            if (AAM_Core_API::capabilityExists('aam_manage_posts')) {
                $cap = 'aam_manage_posts';
            } else {
                $option = AAM_Backend_Feature_Post::getAccessOption();
                $cap    = AAM_Core_Config::get(
                        $option, AAM_Backend_View::getAAMCapability()
                );
            }
            
            $frontend = AAM_Core_Config::get('frontend-access-control', true);
            $backend  = AAM_Core_Config::get('backend-access-control', true);

            if (($frontend || $backend) && AAM::getUser()->hasCapability($cap)) {
                echo AAM_Backend_View::getInstance()->renderTermMetabox($term);
            }
        }
    }
    
    /**
     * Hanlde Metabox initialization process
     *
     * @return void
     *
     * @access public
     */
    public function initMetaboxes() {
        global $post;

        if (AAM_Core_Request::get('init') == 'metabox') {
            //make sure that nobody is playing with screen options
            if (is_a($post, 'WP_Post')) {
                $screen = $post->post_type;
            } elseif ($screen_object = get_current_screen()) {
                $screen = $screen_object->id;
            } else {
                $screen = '';
            }
        
            $model = new AAM_Backend_Feature_Metabox;
            $model->initialize($screen);
        }
    }
    
    /**
     * Add extra column to search in for User search
     *
     * @param array $columns
     *
     * @return array
     *
     * @access public
     */
    public function searchColumns($columns) {
        $columns[] = 'display_name';

        return $columns;
    }
    
    /**
     * 
     * @param type $actions
     * @param type $post
     * @return string
     */
    public function postRowActions($actions, $post) {
        if (AAM::getUser()->hasCapability(AAM_Backend_View::getAAMCapability())) {
            $url = admin_url('admin.php?page=aam&oid=' . $post->ID . '&otype=post#post');

            $actions['aam']  = '<a href="' . $url . '" target="_blank">';
            $actions['aam'] .= __('Access', AAM_KEY) . '</a>';
        }
        
        return $actions;
    }
    
    /**
     * 
     * @param type $actions
     * @param type $term
     * @return string
     */
    public function tagRowActions($actions, $term) {
        if (AAM::getUser()->hasCapability(AAM_Backend_View::getAAMCapability())) {
            $oid = $term->term_id . '|' . $term->taxonomy;
            $url = admin_url('admin.php?page=aam&oid=' . $oid . '&otype=term#post');

            $actions['aam']  = '<a href="' . $url . '" target="_blank">';
            $actions['aam'] .= __('Access', AAM_KEY) . '</a>';
        }
        
        return $actions;
    }
    
    /**
     * Add "Manage Access" action
     * 
     * Add additional action to the user list table.
     * 
     * @param array   $actions
     * @param WP_User $user
     * 
     * @return array
     * 
     * @access public
     */
    public function userActions($actions, $user) {
        if (current_user_can(AAM_Backend_View::getAAMCapability(), $user->ID)) {
            $url = admin_url('admin.php?page=aam&user=' . $user->ID);

            $actions['aam']  = '<a href="' . $url . '" target="_blank">';
            $actions['aam'] .= __('Access', AAM_KEY) . '</a>';
        }
        
        return $actions;
    }

    /**
     * Print javascript libraries
     *
     * @return void
     *
     * @access public
     */
    public function printJavascript() {
        if (AAM::isAAM()) {
            wp_enqueue_script('aam-vendor', AAM_MEDIA . '/js/vendor.js');
            wp_enqueue_script('aam-main', AAM_MEDIA . '/js/aam.js');
            
            //add plugin localization
            $this->printLocalization('aam-main');
        }
    }
    
    /**
     * Print plugin localization
     * 
     * @param string $localKey
     * 
     * @return void
     * 
     * @access protected
     */
    protected function printLocalization($localKey) {
        $subject = $this->getCurrentSubject();
        
        $locals = array(
            'nonce'   => wp_create_nonce('aam_ajax'),
            'ajaxurl' => admin_url('admin-ajax.php'),
            'url' => array(
                'site'     => admin_url('index.php'),
                'editUser' => admin_url('user-edit.php'),
                'addUser'  => admin_url('user-new.php')
            ),
            'level'     => AAM_Core_API::maxLevel(wp_get_current_user()->allcaps),
            'subject'   => array(
                'type'  => $subject->type,
                'id'    => $subject->id,
                'name'  => $subject->name,
                'level' => $subject->level,
                'blog'  => get_current_blog_id()
            ),
            'translation' => require (dirname(__FILE__) . '/View/Localization.php'),
            'caps'        => array(
                'create_roles' => AAM_Backend_View::userCan('aam_create_roles'),
                'create_users' => AAM_Backend_View::userCan('create_users')
            )
        );
        
        if (AAM_Core_Request::get('aamframe')) {
            $locals['ui'] = 'post';
        }
        
        wp_localize_script($localKey, 'aamLocal', $locals);
    }
    
    /**
     * Get current subject
     * 
     * @return stdClass
     * 
     * @access protected
     */
    protected function getCurrentSubject() {
        $userId  = AAM_Core_Request::get('user');
        if ($userId && AAM_Backend_View::userCan('list_users')) {
            $u = get_user_by('id', $userId);
            $subject = array(
                'type'  => 'user',
                'id'    => $userId,
                'name'  => ($u->display_name ? $u->display_name : $u->user_nicename),
                'level' => AAM_Core_API::maxLevel($u->allcaps)
            );
        } elseif (AAM_Backend_View::userCan('aam_list_roles')) {
            $roles = array_keys(get_editable_roles());
            $id    = array_shift($roles);
            $role  = AAM_Core_API::getRoles()->get_role($id);
            
            $subject = array(
                'type' => 'role',
                'id'   => $id,
                'name' => $role->name,
                'level' => AAM_Core_API::maxLevel($role->capabilities)
            );
        } elseif (AAM_Backend_View::userCan('aam_manage_visitors')) {
            $subject = array(
                'type' => 'visitor',
                'id'   => null,
                'name' => __('Anonymous', AAM_KEY),
                'level' => 0
            );
        } elseif (AAM_Backend_View::userCan('aam_manage_default')) {
            $subject = array(
                'type' => 'default',
                'id'   => null,
                'name' => __('All Users, Roles and Visitor', AAM_KEY),
                'level' => 0
            );
        } else {
            $subject =  array(
                'type' => null,
                'id'   => null,
                'name' => null,
                'level' => 0
            );
        }
        
        return (object) $subject;
    }

    /**
     * Print necessary styles
     *
     * @return void
     *
     * @access public
     */
    public function printStylesheet() {
        if (AAM::isAAM()) {
            wp_enqueue_style('aam-bt', AAM_MEDIA . '/css/bootstrap.min.css');
            wp_enqueue_style('aam-db', AAM_MEDIA . '/css/datatables.min.css');
            wp_enqueue_style('aam-main', AAM_MEDIA . '/css/aam.css');
        }
    }

    /**
     * Register Admin Menu
     *
     * @return void
     *
     * @access public
     */
    public function adminMenu() {
        if (AAM_Core_Console::hasIssues()) {
            $counter = '&nbsp;<span class="update-plugins">'
                     . '<span class="plugin-count">' . AAM_Core_Console::count()
                     . '</span></span>';
        } else {
            $counter = '';
        }
        
        //register the menu
        add_menu_page(
            'AAM', 
            'AAM' . $counter, 
            AAM_Backend_View::getAAMCapability(), 
            'aam', 
            array($this, 'renderPage'), 
            AAM_MEDIA . '/active-menu.svg'
        );
    }
    
    /**
     * Render Main Content page
     *
     * @return void
     *
     * @access public
     */
    public function renderPage() {
        echo AAM_Backend_View::getInstance()->renderPage();
    }
    
    /**
     * Render list of AAM Features
     *
     * Must be separate from Ajax call because WordPress ajax does not load 
     * a lot of UI stuff like admin menu
     *
     * @return void
     *
     * @access public
     */
    public function renderContent() {
        check_ajax_referer('aam_ajax');
        
        if (AAM::getUser()->hasCapability(AAM_Backend_View::getAAMCapability())) {
            echo AAM_Backend_View::getInstance()->renderContent();
        } else {
            echo __('Access Denied', AAM_KEY);
        }
        
        exit();
    }

    /**
     * Handle Ajax calls to AAM
     *
     * @return void
     *
     * @access public
     */
    public function ajax() {
        check_ajax_referer('aam_ajax');

        //clean buffer to make sure that nothing messing around with system
        while (@ob_end_clean()){}
        
        //process ajax request
        if (AAM::getUser()->hasCapability(AAM_Backend_View::getAAMCapability())) {
            echo AAM_Backend_View::getInstance()->processAjax();
        } else {
            echo __('Access Denied', AAM_KEY);
        }
        
        exit();
    }

    /**
     * Bootstrap the manager
     * 
     * @return void
     * 
     * @access public
     */
    public static function bootstrap() {
        if (is_null(self::$_instance)) {
            self::$_instance = new self;
        }
    }
    
    /**
     * Get instance of itself
     * 
     * @return AAM_Backend_View
     * 
     * @access public
     */
    public static function getInstance() {
        self::bootstrap();

        return self::$_instance;
    }

}