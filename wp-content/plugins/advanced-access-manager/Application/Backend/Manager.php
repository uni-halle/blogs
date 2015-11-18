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
        //print required JS & CSS
        add_action('admin_print_scripts', array($this, 'printJavascript'));
        add_action('admin_print_styles', array($this, 'printStylesheet'));

        //manager Admin Menu
        add_action('admin_menu', array($this, 'adminMenu'), 999);

        //manager AAM Ajax Requests
        add_action('wp_ajax_aam', array($this, 'ajax'));
        //manager AAM Features Content rendering
        add_action('admin_action_aam-content', array($this, 'renderContent'));
        //manager user search and authentication control
        add_filter('user_search_columns', array($this, 'searchColumns'));
        //manage access action to the user list
        add_filter('user_row_actions', array($this, 'userActions'), 10, 2);
        
        //check extension version
        $this->checkExtensionList();

        //register backend hooks and filters
        AAM_Backend_Filter::register();
    }
    
    /**
     * 
     */
    protected function checkExtensionList() {
        $list = AAM_Core_API::getOption('aam-extension-list', array());
        $repo = AAM_Core_Repository::getInstance();
        
        foreach($list as $extension) {
            $status = $repo->extensionStatus($extension->title);
            if ($status == AAM_Core_Repository::STATUS_UPDATE) {
                AAM_Core_Console::add(
                    sprintf(
                        __('Extension %s has new update available for download.'), 
                        $extension->title
                    )
                );
            }
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
        $url = admin_url('admin.php?page=aam&user=' . $user->ID);
        
        $actions['aam']  = '<a href="' . $url . '">';
        $actions['aam'] .= __('Manage Access', AAM_KEY) . '</a>';
        
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
            wp_enqueue_script('aam-bt', AAM_MEDIA . '/js/bootstrap.min.js');
            wp_enqueue_script('aam-dt', AAM_MEDIA . '/js/datatables.min.js');
            wp_enqueue_script('aam-dwn', AAM_MEDIA . '/js/download.min.js');
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
        
        wp_localize_script($localKey, 'aamLocal', array(
            'nonce' => wp_create_nonce('aam_ajax'),
            'ajaxurl' => admin_url('admin-ajax.php'),
            'url' => array(
                'site' => admin_url('index.php'),
                'jsbase' => AAM_MEDIA . '/js',
                'editUser' => admin_url('user-edit.php'),
                'addUser' => admin_url('user-new.php')
            ),
            'subject' => array(
                'type' => $subject->type,
                'id' => $subject->id,
                'name'=> $subject->name,
                'blog' => get_current_blog_id()
            ),
            'welcome' => AAM_Core_API::getOption('aam-welcome', 1),
            'translation' => require (dirname(__FILE__) . '/Localization.php')
        ));
    }
    
    /**
     * 
     * @return type
     */
    protected function getCurrentSubject() {
        $userId  = AAM_Core_Request::get('user');
        if ($userId) {
            $u = get_user_by('id', $userId);
            $subject = array(
                'type' => 'user',
                'id' => $userId,
                'name' => ($u->display_name ? $u->display_name : $u->user_nicename)
            );
        } else {
            $roles = array_keys(get_editable_roles());
            $role  = array_shift($roles);
            
            $subject = array(
                'type' => 'role',
                'id' => $role,
                'name' => AAM_Core_API::getRoles()->get_role($role)->name
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
            __('AAM', AAM_KEY), 
            __('AAM', AAM_KEY) . $counter, 
            AAM_Core_ConfigPress::get('aam.page.capability', 'administrator'), 
            'aam', 
            array($this, 'renderPage'), 
            AAM_MEDIA . '/active-menu.png'
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

        echo AAM_Backend_View::getInstance()->renderContent();
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
        echo AAM_Backend_View::getInstance()->processAjax();
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
        if (is_null(self::$_instance)) {
            self::bootstrap();
        }

        return self::$_instance;
    }

}