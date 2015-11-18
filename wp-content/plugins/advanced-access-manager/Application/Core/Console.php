<?php

/**
 * ======================================================================
 * LICENSE: This file is subject to the terms and conditions defined in *
 * file 'license.txt', which is part of this source code package.       *
 * ======================================================================
 */

/**
 * AAM Core Consol Panel
 * 
 * Track and display list of all warnings that has been detected during AAM 
 * execution. The consol is used only when AAM interface was triggered in Admin side.
 * 
 * @package AAM
 * @author Vasyl Martyniuk <vasyl@vasyltech.com>
 */
class AAM_Core_Console {

    /**
     * List of Runtime errors related to AAM
     * 
     * @var array
     * 
     * @access private 
     * @static 
     */
    private static $_warnings = array();

    /**
     * Add new warning
     * 
     * @param string $message
     * 
     * @return void
     * 
     * @access public
     * @static
     */
    public static function add($message) {
        self::$_warnings[] = $message;
    }

    /**
     * Check if there is any warning during execution
     * 
     * @return boolean
     * 
     * @access public
     * @static
     */
    public static function hasIssues() {
        return (count(self::$_warnings) ? true : false);
    }

    /**
     * Get list of all warnings
     * 
     * @return array
     * 
     * @access public
     * @static
     */
    public static function getWarnings() {
        return self::$_warnings;
    }
    
    /**
     * 
     * @return type
     */
    public static function count() {
        return count(self::$_warnings);
    }

}