<?php

/**
 * ======================================================================
 * LICENSE: This file is subject to the terms and conditions defined in *
 * file 'license.txt', which is part of this source code package.       *
 * ======================================================================
 */

/**
 * AAM Core Cache
 * 
 * @package AAM
 * @author Vasyl Martyniuk <vasyl@vasyltech.com>
 */
class AAM_Core_Cache {
    
    /**
     * DB Cache option
     */
    const CACHE_OPTION = 'cache';
    
    /**
     * Core config
     * 
     * @var array
     * 
     * @access protected 
     */
    protected static $cache = false;
    
    /**
     * Update cache flag
     * 
     * @var boolean
     * 
     * @access protected 
     */
    protected static $updated = false;
    
    /**
     * Get cached option
     * 
     * @param string $option
     * 
     * @return mixed
     * 
     * @access public
     */
    public static function get($option) {
        return (isset(self::$cache[$option]) ? self::$cache[$option] : null);
    }
    
    /**
     * Set cache option
     * 
     * @param AAM_Core_Subject $subject
     * @param string           $option
     * @param mixed            $data
     * 
     * @return void
     * 
     * @access public
     */
    public static function set($subject, $option, $data) {
        if ($subject instanceof AAM_Core_Subject_User) { //cache only for actual user
            if (!isset(self::$cache[$option]) || self::$cache[$option] != $data) {
                self::$cache[$option] = $data;
                self::$updated = true;
            }
        }
    }
    
    /**
     * Clear cache
     * 
     * @return void
     * 
     * @access public
     * @global WPDB $wpdb
     */
    public static function clear() {
        global $wpdb;
        
        //clear visitor cache
        $oquery = "DELETE FROM {$wpdb->options} WHERE `option_name` = %s";
        $wpdb->query($wpdb->prepare($oquery, 'aam_visitor_cache' ));
        
        $mquery = "DELETE FROM {$wpdb->usermeta} WHERE `meta_key` = %s";
        $wpdb->query($wpdb->prepare($mquery, $wpdb->prefix . 'aam_cache' ));
        
        //clear updated flag
        self::$updated = false;
    }
    
    /**
     * Save cache
     * 
     * Save aam cache but only if changes deleted
     * 
     * @return void
     * 
     * @access public
     */
    public static function save() {
        if (self::$updated) {
            AAM::getUser()->updateOption(self::$cache, self::CACHE_OPTION);
        }
    }
    
    /**
     * Bootstrap cache
     * 
     * Do not load cache if user is on AAM page
     * 
     * @return void
     * 
     * @access public
     */
    public static function bootstrap() {
        if (!AAM::isAAM()) {
            self::$cache = AAM::getUser()->readOption(self::CACHE_OPTION);
            add_action('shutdown', 'AAM_Core_Cache::save');
        }
    }
    
}