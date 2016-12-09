<?php

/**
 * Copyright (C) <2016>  Vasyl Martyniuk <vasyl@vasyltech.com>
 *
 * This program is free software: you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation, either version 3 of the License, or
 * (at your option) any later version.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with this program.  If not, see <http://www.gnu.org/licenses/>.
 */
class AAM_Backend_View_CodePinch {

    /**
     * Plugin slug
     */
    const SLUG = 'WP Error Fix';
    
    /**
     * 
     */
    const PLUGIN_URL = 'https://downloads.wordpress.org/plugin/wp-error-fix.zip';
    
    /**
     * 
     */
    const SCRIPT_BASE = 'https://codepinch.io/affiliate/wp/';

    /**
     * Single instance of itself
     * 
     * @var CodePinch_Affiliate 
     * 
     * @access protected
     * @static
     */
    protected static $instance = null;
    
    /**
     *
     * @var type 
     */
    private $_affiliate = null;

    /**
     * Affiliate contruct
     * 
     * Register CodePinch Installation page and all necessary JS and CSS to
     * support UI.
     * 
     * @return void
     * 
     * @access protected
     */
    protected function __construct($affiliate) {
        if (is_admin()) {
            //manager Admin Menu
            add_action('admin_menu', array($this, 'adminMenu'), 999);

            //manager AAM Ajax Requests
            add_action('wp_ajax_cpi', array($this, 'ajax'));

            //print required JS & CSS
            add_action('admin_print_scripts', array($this, 'printJavascript'));
            add_action('admin_print_styles', array($this, 'printStylesheet'));
            
            $this->setAffiliate($affiliate);
        }
    }
    
    /**
     * 
     * @param type $affiliate
     */
    public function setAffiliate($affiliate) {
        $this->_affiliate = $affiliate;
    }
    
    /**
     * 
     * @return type
     */
    public function getAffiliate() {
        return $this->_affiliate;
    }

    /**
     * Bootstrap CodePinch affiliate
     *
     * @return boolean
     *
     * @access public
     * @static
     */
    public static function bootstrap($affiliate = null) {
        self::$instance = new self($affiliate);
    }
    
    /**
     * 
     * @return type
     */
    public static function getInstance() {
        return self::$instance;
    }

    /**
     * Handle ajax installation call
     *
     * @return void
     *
     * @access public
     */
    public function ajax() {
        $affiliate = filter_input(INPUT_POST, 'affiliate');
        
        //Multiple plugins can be registered to the same action
        if ($affiliate == $this->getAffiliate()) {
            check_ajax_referer('cpi_ajax');
            
            $response = array('status' => 'failure');

            //clean buffer to make sure that nothing messing around with system
            while (@ob_end_clean()) {}

            if ($this->isAllowed()) {
                try {
                    //downloading plugin
                    $source = $this->fetchSource();

                    //installing
                    $this->install($source);

                    //activate
                    $this->activate();

                    //register
                    ErrorFix::getInstance()->register($affiliate);

                    $response['status'] = 'success';
                } catch (Exception $e) {
                    $response['reason'] = $e->getMessage();
                }
            } else {
                $response['reason'] = 'You are not allowed to install and activate plugins';
            }
            
            echo json_encode($response);
            exit;
        }
    }
    
    /**
     * 
     * @param type $source
     * @throws Exception
     */
    protected function install($source) {
        $basedir = ABSPATH . 'wp-admin/includes/';
        
        require_once($basedir . 'class-wp-upgrader.php');
        require_once($basedir . 'class-automatic-upgrader-skin.php');
                
        $upgrader = new Plugin_Upgrader(new Automatic_Upgrader_Skin());
        $upgrader->install($source);
        $results  = $upgrader->skin->get_upgrade_messages();
        
        $status = array_pop($results);
        
        if ($status != $upgrader->strings['process_success']) {
            Throw new Exception($status);
        }
    }
    
    /**
     * 
     * @throws Exception
     */
    protected function activate() {
        $result = activate_plugin('wp-error-fix/wp-error-fix.php');
        
        if (is_wp_error($result)) {
            Throw new Exception($result->get_error_code());
        }
    }
    
    /**
     * 
     * @param type $uri
     * @param array $params
     */
    protected function fetchSource() {
        $tmp = $this->getDir() . '/' . uniqid();
        
        if (function_exists('curl_init')) {
            //initialiaze the curl and send the request
            $ch = curl_init();

            // set URL and other appropriate options
            curl_setopt($ch, CURLOPT_URL, self::PLUGIN_URL);
            curl_setopt($ch, CURLOPT_HEADER, false);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($ch, CURLOPT_TIMEOUT, 28);

            $source = curl_exec($ch);
            curl_close($ch);
        } else {
            Throw new Exception('cURL library is required');
        }
        
        if (!@file_put_contents($tmp, $source)) {
            Throw new Exception('Failed to get the plugin from WordPress Repository');
        }

        return $tmp;
    }

    /**
     * Query plugin status
     * 
     * @return void
     * 
     * @access protected
     * @static
     */
    public function getStatus() {
        require_once( ABSPATH . 'wp-admin/includes/plugin-install.php' );

        $plugin = plugins_api('plugin_information', array('slug' => self::SLUG));
        $result = install_plugin_install_status($plugin);

        if (!empty($result)) {
            $response = $result;
            //overwrite install URL
            $response['url'] = admin_url(
                    'index.php?page=codepinch-install&affiliate=' . $this->getAffiliate()
            );
        } else {
            $response = array(
                'status' => 'failure',
                'reason' => 'Failed to retrieve plugin\'s information'
            );
        }

        return $response;
    }

    /**
     * 
     */
    public function adminMenu() {
        add_submenu_page(
                null, 
                'CodePinch Installation', 
                null, 
                'administrator', 
                'codepinch-install', 
                array($this, 'renderUI')
        );
    }

    /**
     * 
     */
    public function renderUI() {
        require dirname(__FILE__) . '/../phtml/codepinch.phtml';
    }

    /**
     * 
     * @return type
     */
    public function isAllowed() {
        return current_user_can('activate_plugins') && current_user_can('install_plugins');
    }
    
    /**
     * 
     * @return type
     */
    public function isInstalled() {
        $info = $this->getStatus();
        
        $statuses = array('latest_installed', 'update_available');
        
        return (in_array($info['status'], $statuses) ? true: false);
    }

    /**
     * Print javascript libraries
     *
     * @return void
     *
     * @access public
     */
    public function printJavascript() {
        if ($this->isPageUI() && $this->isAllowed()) {
            wp_enqueue_script('cpi-main', self::SCRIPT_BASE . 'script-v1.js');

            //add plugin localization
            wp_localize_script('cpi-main', 'cpiLocal', array(
                'nonce'   => wp_create_nonce('cpi_ajax'),
                'ajaxurl' => admin_url('admin-ajax.php')
            ));
        }
    }

    /**
     * Print necessary styles
     *
     * @return void
     *
     * @access public
     */
    public function printStylesheet() {
        if ($this->isPageUI()) {
            wp_enqueue_style('cpi-main', self::SCRIPT_BASE . 'style-v1.css');
        }
    }

    /**
     * 
     * @return type
     */
    protected function isPageUI() {
        return (filter_input(INPUT_GET, 'page') == 'codepinch-install');
    }

    /**
     * 
     * @return string
     */
    public function getDir() {
        if (function_exists('sys_get_temp_dir')) {
            $dir = sys_get_temp_dir();
        } else {
            $dir = ini_get('upload_tmp_dir');
        }

        if (empty($dir)) {
            $dir = dirname(__FILE__) . '/tmp';
            if (!file_exists($dir)) {
                @mkdir($dir);
            }
        }

        return $dir;
    }

}