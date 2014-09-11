<?php

/**
 * ======================================================================
 * LICENSE: This file is subject to the terms and conditions defined in *
 * file 'license.txt', which is part of this source code package.       *
 * ======================================================================
 */

/**
 * Feature Secure
 * 
 * @package AAM
 * @author Vasyl Martyniuk <support@wpaam.com>
 * @copyright Copyright C  Vasyl Martyniuk
 * @license GNU General Public License {@link http://www.gnu.org/licenses/}
 */
class AAM_Secure extends AAM_Core_Extension {

    /**
     * Unique Feature ID
     */
    const FEATURE_ID = 'secure';

    /**
     *
     * @var type 
     */
    private $_cache = array();

    /**
     *
     * @var type 
     */
    private $_cacheLimit = 1000;

    /**
     *
     * @var type 
     */
    private $_stats = array();

    /**
     * Constructor
     *
     * @param aam $parent Main AAM object
     *
     * @return void
     *
     * @access public
     */
    public function __construct(aam $parent) {
        parent::__construct($parent);

        if (is_admin()) {
            //print required JS & CSS
            add_action('admin_print_scripts', array($this, 'printScripts'));
            add_action('admin_print_styles', array($this, 'printStyles'));
            add_action('admin_head', array($this, 'adminHead'));

            //manager Admin Menu
            if (aam_Core_API::isNetworkPanel()) {
                add_action('network_admin_menu', array($this, 'adminMenu'), 999);
            } else {
                add_action('admin_menu', array($this, 'adminMenu'), 999);
            }
            //manager AAM Ajax Requests
            add_action('wp_ajax_aam_security', array($this, 'ajax'));
        }

        add_filter('wp_login_errors', array($this, 'loginFailure'), 10, 2);
        add_action('wp_login', array($this, 'login'), 10, 2);

        //add_filter('authenticate', array($this, 'authenticate'), 999, 3);
    }

    /**
     *
     * @param type $username
     * @param type $user
     */
    public function login($username, $user) {
        $this->_cache = aam_Core_API::getBlogOption(
                        'aam_security_login_cache', array()
        );
        $ip = aam_Core_Request::server('REMOTE_ADDR');
        if ($this->hasIPCache($ip)) {
            $data = $this->getIPCache($ip);
            $data->attempts = 0; //reset counter
            $this->addIPCache($ip, $data);
            aam_Core_API::updateBlogOption(
                    'aam_security_login_cache', $this->_cache
            );
        }
    }

    /**
     * 
     * @return type
     */
    public function isGeoLookupOn() {
        $geo_lookup = aam_Core_ConfigPress::getParam(
                        'security.login.geo_lookup', 'false'
        );

        return ($geo_lookup == 'true' ? true : false);
    }

    /**
     * 
     * @return type
     */
    public function isLoginLockoutOn() {
        $login_lock = aam_Core_ConfigPress::getParam(
                        'security.login.lockout', 'false'
        );

        return ($login_lock == 'true' ? true : false);
    }

    /**
     * 
     * @param type $errors
     * @param type $redirect_to
     */
    public function loginFailure($errors, $redirect_to) {
        $this->_cache = aam_Core_API::getBlogOption(
                        'aam_security_login_cache', array()
        );
        $this->_cacheLimit = aam_Core_ConfigPress::getParam(
                        'security.login.cache_limit', 1000
        );
        if ($this->isGeoLookupOn()) {
            $this->_stats = aam_Core_API::getBlogOption(
                            'aam_security_login_stats', array()
            );
            $info = $this->retrieveGeoData();
            if ($info instanceof stdClass) {
                if (!isset($this->_stats[$info->countryCode])) {
                    $this->_stats[$info->countryCode] = array(
                        'failed' => 0
                    );
                }
                $this->_stats[$info->countryCode]['failed']++;
                aam_Core_API::updateBlogOption(
                        'aam_security_login_stats', $this->_stats
                );
            }
        }
        if ($this->isLoginLockoutOn()) {
            $this->loginLockout();
        }
        aam_Core_API::updateBlogOption(
                'aam_security_login_cache', $this->_cache
        );

        return $errors;
    }

    /**
     * 
     */
    protected function loginLockout() {
        $ip = aam_Core_Request::server('REMOTE_ADDR');
        if ($this->hasIPCache($ip)) {
            $info = $this->getIPCache($ip);
        } else {
            $info = new stdClass;
        }

        if (!isset($info->attempts)) {
            $info->attempts = 1;
        } else {
            $info->attempts++;
        }
        $threshold = aam_Core_ConfigPress::getParam(
                        'security.login.attempts', 10
        );
        if ($info->attempts >= $threshold) {
            $action = aam_Core_ConfigPress::getParam(
                            'security.login.attempt_failure', 'slowdown'
            );
            switch ($action) {
                case 'slowdown':
                    $time = aam_Core_ConfigPress::getParam(
                                    'security.login.slowdown_time', '5'
                    );
                    sleep(intval($time));
                    break;

                case 'die':
                    wp_die(aam_Core_ConfigPress::getParam(
                                    'security.login.die_message', 'You are not allowed to login'
                    ));
                    break;

                default:
                    break;
            }
        }
    }

    /**
     * 
     * @return null
     */
    protected function retrieveGeoData() {
        $ip = aam_Core_Request::server('REMOTE_ADDR');
        if ($this->hasIPCache($ip)) {
            $location = $this->getIPCache($ip);
        } else {
            $service = aam_Core_ConfigPress::getParam(
                            'security.login.geoip.service', 'FreeGeoIP'
            );
            $filename = dirname(__FILE__) . '/geoip/' . strtolower($service) . '.php';

            if (file_exists($filename)) {
                require_once($filename);
                $location = call_user_func("{$service}::query", $ip);
                $this->addIPCache($ip, $location);
            } else {
                $location = null;
            }
        }

        return $location;
    }

    /**
     * 
     * @param type $ip
     * @return type
     */
    protected function hasIPCache($ip) {
        return (isset($this->_cache[$ip]) ? true : false);
    }

    /**
     * 
     * @param type $ip
     * @return type
     */
    protected function getIPCache($ip) {
        return ($this->hasIPCache($ip) ? $this->_cache[$ip] : null);
    }

    /**
     * 
     * @param type $ip
     * @param type $data
     */
    protected function addIPCache($ip, $data) {
        if (!is_null($data)) {
            if ((count($this->_cache) >= $this->_cacheLimit) && !isset($this->_cache[$ip])) {
                array_shift($this->_cache);
            }
            $this->_cache[$ip] = $data;
        }
    }

    /**
     * Print necessary styles
     *
     * @return void
     *
     * @access public
     */
    public function printStyles() {
        if ($this->isSecurityScreen()) {
            wp_enqueue_style('dashboard');
            wp_enqueue_style('global');
            wp_enqueue_style('wp-admin');
            wp_enqueue_style('aam-ui-style', AAM_MEDIA_URL . 'css/jquery-ui.css');
            wp_enqueue_style('aam-common-style', AAM_MEDIA_URL . 'css/common.css');
            wp_enqueue_style('aam-security-style', AAM_SECURITY_BASE_URL . '/stylesheet/security.css');
            if ($this->isGeoLookupOn()) {
                wp_enqueue_style('aam-datatable', AAM_MEDIA_URL . 'css/jquery.dt.css');
                wp_enqueue_style('aam-country-flags', AAM_SECURITY_BASE_URL . '/stylesheet/flags32.css');
            }
        }
    }

    /**
     * Print necessary scripts
     *
     * @return void
     *
     * @access public
     */
    public function printScripts() {
        if ($this->isSecurityScreen()) {
            wp_enqueue_script('postbox');
            wp_enqueue_script('dashboard');
            if ($this->isGeoLookupOn()) {
                wp_enqueue_script('aam-datatable', AAM_MEDIA_URL . 'js/jquery.dt.js');
                wp_enqueue_script('google-jsapi', 'https://www.google.com/jsapi');
            }
            wp_enqueue_script('aam-security', AAM_SECURITY_BASE_URL . '/javascript/security.js');
            $localization = array(
                'nonce' => wp_create_nonce('aam_ajax'),
                'ajaxurl' => admin_url('admin-ajax.php'),
            );
            wp_localize_script('aam-security', 'aamLocal', $localization);
        }
    }

    /**
     * 
     */
    public function adminHead() {
        if ($this->isSecurityScreen() && $this->isGeoLookupOn()) {
            echo '<script type="text/javascript">';
            echo file_get_contents(__DIR__ . '/javascript/loader.js');
            echo '</script>';
        }
    }

    /**
     * 
     * @return type
     */
    public function isSecurityScreen() {
        return (aam_Core_Request::get('page') == 'aam-security' ? true : false);
    }

    /**
     * Register Admin Menu
     *
     * @return void
     *
     * @access public
     */
    public function adminMenu() {
        //register submenus
        add_submenu_page(
                'aam', __('Security', 'aam'), __('Security', 'aam'), aam_Core_ConfigPress::getParam(
                        'aam.page.security.capability', 'administrator'
                ), 'aam-security', array($this, 'content')
        );
    }

    /**
     * 
     */
    public function content() {
        require_once(dirname(__FILE__) . '/security.php');
        $security = new aam_View_Security();
        echo $security->run();
    }

    public function ajax() {
        check_ajax_referer('aam_ajax');

        //clean buffer to make sure that nothing messing around with system
        while (@ob_end_clean());

        //process ajax request
        try {
            require_once(dirname(__FILE__) . '/security.php');
            $model = new aam_View_Security();
            echo $model->processAjax();
        } catch (Exception $e) {
            echo '-1';
        }
        die();
    }

    /**
     * 
     * @param type $user
     * @param type $username
     * @param type $password
     * @return type
     */
    public function authenticate($user, $username, $password) {
        if (!is_wp_error($user)) {
            $login_history = get_user_meta($user->ID, 'aam_login_history', true);
            $ip = aam_Core_Request::server('REMOTE_ADDR');
        }

        return $user;
    }

}
