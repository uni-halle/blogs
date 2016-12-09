<?php

/**
 * ======================================================================
 * LICENSE: This file is subject to the terms and conditions defined in *
 * file 'license.txt', which is part of this source code package.       *
 * ======================================================================
 */

/**
 * AAM Core Config
 * 
 * @package AAM
 * @author Vasyl Martyniuk <vasyl@vasyltech.com>
 */
class AAM_Core_Config {
    
    /**
     * Core settings database option
     * 
     * aam-utilities slug is used because AAM Utilities with v3.4 became a core
     * feature instead of independent extension.
     */
    const CONFIG_OPTION = 'aam-utilities';
    
    /**
     * Core config
     * 
     * @var array
     * 
     * @access protected 
     */
    protected static $config = array();
    
    /**
     * Load core AAM settings
     * 
     * @return void
     * 
     * @access public
     */
    public static function bootstrap() {
        self::$config = AAM_Core_API::getOption(self::CONFIG_OPTION, array());
    }
    
    /**
     * Get config option
     * 
     * @param string $option
     * @param mixed  $default
     * 
     * @return mixed
     * 
     * @access public
     * @static
     */
    public static function get($option, $default = null) {
        if (isset(self::$config[$option])) {
            $value = self::$config[$option];
        } else { //try to get option from ConfigPress
            $value = self::readConfigPress($option, $default);
        }
        
        return apply_filters('aam-filter-config-get', $value, $option);
    }
    
    /**
     * Set config
     * 
     * @param string $option
     * @param mixed  $value
     * 
     * @return boolean
     * 
     * @access public
     */
    public static function set($option, $value) {
        self::$config[$option] = $value;
        
        //save config to database
        return AAM_Core_API::updateOption(self::CONFIG_OPTION, self::$config);
    }
    
    /**
     * Get ConfigPress parameter
     * 
     * @param string $param
     * @param mixed  $default
     * 
     * @return mixed
     * 
     * @access public
     * @static
     */
    protected static function readConfigPress($param, $default = null) {
        if (class_exists('ConfigPress')) {
            $config = ConfigPress::get('aam.' . $param, $default);
        } else {
            $config = $default;
        }

        if (is_array($config) && isset($config['userFunc'])) {
            if (is_callable($config['userFunc'])) {
                $response = call_user_func($config['userFunc']);
            } else {
                $response = $default;
            }
        } else {
            $response = $config;
        }

        return $response;
    }

}