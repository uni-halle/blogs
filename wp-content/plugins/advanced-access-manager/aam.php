<?php

/**
  Plugin Name: Advanced Access Manager
  Description: Manage User and Role Access to WordPress Backend and Frontend.
  Version: 3.0 Beta
  Author: Vasyl Martyniuk <vasyl@vasyltech.com>
  Author URI: http://www.vasyltech.com

  -------
  LICENSE: This file is subject to the terms and conditions defined in
  file 'license.txt', which is part of Advanced Access Manager source package.
 *
 */

/**
 * Main plugin's class
 * 
 * @package AAM
 * @author Vasyl Martyniuk <vasyl@vasyltech.com>
 */
class AAM {

    /**
     * Single instance of itself
     *
     * @var AAM
     *
     * @access private
     */
    private static $_instance = null;

    /**
     * User Subject
     *
     * @var AAM_Core_Subject_User|AAM_Core_Subject_Visitor
     *
     * @access private
     */
    private $_user = null;

    /**
     * Initialize the AAM Object
     *
     * @return void
     *
     * @access protected
     */
    protected function __construct() {
        //initialize the user subject
        if (get_current_user_id()) {
            $this->setUser(new AAM_Core_Subject_User(get_current_user_id()));
        } else {
            $this->setUser(new AAM_Core_Subject_Visitor(''));
        }
        
        //load all installed extension
        AAM_Core_Repository::getInstance()->load();
        
        //bootstrap the correct interface
        if (is_admin()) {
            AAM_Backend_Manager::bootstrap();
        } else {
            AAM_Frontend_Manager::bootstrap();
        }
    }

    /**
     * Set Current User
     *
     * @param AAM_Core_Subject $user
     *
     * @return void
     *
     * @access public
     */
    protected function setUser(AAM_Core_Subject $user) {
        $this->_user = $user;
    }

    /**
     * Get current user
     * 
     * @return AAM_Core_Subject
     * 
     * @access public
     */
    public static function getUser() {
        return self::getInstance()->_user;
    }

    /**
     * Make sure that AAM UI Page is used
     *
     * @return boolean
     *
     * @access public
     */
    public static function isAAM() {
        return (AAM_Core_Request::get('page') == 'aam');
    }

    /**
     * Initialize the AAM plugin
     *
     * @return AAM
     *
     * @access public
     * @static
     */
    public static function getInstance() {
        if (is_null(self::$_instance)) {
            load_plugin_textdomain('aam', false, dirname(__FILE__) . '/Lang');
            self::$_instance = new self;
        }

        return self::$_instance;
    }

    /**
     * Run daily routine
     * 
     * Check server extension versions
     * 
     * @return void
     * 
     * @access public
     */
    public static function cron() {
        //grab the server extension list
        $response = AAM_Core_Server::check();
        if (!is_wp_error($response)) {
            AAM_Core_API::updateOption('aam-extension-list', $response);
        }
    }

    /**
     * Create aam folder
     * 
     * @return void
     * 
     * @access public
     */
    public static function activate() {
        global $wp_filesystem, $wp_version;
        
        //check PHP Version
        if (version_compare(PHP_VERSION, '5.2') == -1) {
            exit(__('PHP 5.2 or higher is required.', AAM_KEY));
        } elseif (version_compare($wp_version, '3.8') == -1) {
            exit(__('WP 3.8 or higher is required.', AAM_KEY));
        }

        //create an wp-content/aam folder if does not exist
        WP_Filesystem(); //initialize the WordPress filesystem

        $wp_content = $wp_filesystem->wp_content_dir();

        //make sure that we have always content dir
        if ($wp_filesystem->exists($wp_content . '/aam') === false) {
            $wp_filesystem->mkdir($wp_content . '/aam');
        }
    }

    /**
     * Uninstall hook
     *
     * Remove all leftovers from AAM execution
     *
     * @return void
     *
     * @access public
     */
    public static function uninstall() {
        global $wp_filesystem;
        
        //trigger any uninstall hook that is registered by any extension
        do_action('aam-uninstall-action');

        WP_Filesystem(); //initialize the WordPress filesystem

        $wp_content = $wp_filesystem->wp_content_dir();

        //remove the content directory
        $wp_filesystem->rmdir($wp_content . '/aam', true);
    }

}

if (defined('ABSPATH')) {
    //define few common constants
    define('AAM_MEDIA', plugins_url('/media', __FILE__));
    define('AAM_KEY', 'advanced-access-manager');
    
    //register autoloader
    require (dirname(__FILE__) . '/autoloader.php');
    AAM_Autoloader::register();
    
    //the highest priority (higher the core)
    //this is important to have to catch events like register core post types
    add_action('init', 'AAM::getInstance', -1);
    
    //schedule cron
    if (!wp_next_scheduled('aam-cron')) {
        wp_schedule_event(time(), 'daily', 'aam-cron');
    }
    add_action('aam-cron', 'AAM::cron');

    //activation & deactivation hooks
    register_activation_hook(__FILE__, array('AAM', 'activate'));
    register_uninstall_hook(__FILE__, array('AAM', 'uninstall'));
}