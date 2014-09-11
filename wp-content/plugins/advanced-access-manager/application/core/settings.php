<?php

/**
 * ======================================================================
 * LICENSE: This file is subject to the terms and conditions defined in *
 * file 'license.txt', which is part of this source code package.       *
 * ======================================================================
 */

/**
 * Core Plugin Settings
 * 
 * Collection of core and default settings that are used across the plugin
 * 
 * @package AAM
 * @author Vasyl Martyniuk <support@wpaam.com>
 * @copyright Copyright C Vasyl Martyniuk
 * @license GNU General Public License {@link http://www.gnu.org/licenses/}
 */
final class aam_Core_Settings {

    /**
     * Collection of settings
     * 
     * @var array
     * 
     * @access private
     * @static 
     */
    private static $_collection = array();

    /**
     * Get Setting
     * 
     * @param string $setting
     * @param mixed  $default
     * 
     * @return mixed
     * 
     * @access public
     * @static
     */
    public static function get($setting, $default = null) {
        if (empty(self::$_collection)) {
            self::init();
        }

        if (isset(self::$_collection[$setting])) {
            $response = self::$_collection[$setting];
        } else {
            $response = $default;
        }

        return apply_filters('aam_core_setting', $response, $setting);
    }

    /**
     * Initialize all core & default settings
     * 
     * @return void
     * 
     * @access protected
     * @static
     */
    protected static function init() {
        //initialize default posts & terms restrictions
        self::$_collection['term_frontend_restrictions'] = array(
            aam_Control_Object_Term::ACTION_BROWSE,
            aam_Control_Object_Term::ACTION_EXCLUDE,
            aam_Control_Object_Term::ACTION_LIST
        );
        self::$_collection['term_backend_restrictions'] = array(
            aam_Control_Object_Term::ACTION_BROWSE,
            aam_Control_Object_Term::ACTION_EDIT,
            aam_Control_Object_Term::ACTION_LIST
        );
        self::$_collection['post_frontend_restrictions'] = array(
            aam_Control_Object_Post::ACTION_READ,
            aam_Control_Object_Post::ACTION_EXCLUDE,
            aam_Control_Object_Post::ACTION_COMMENT
        );
        self::$_collection['post_backend_restrictions'] = array(
            aam_Control_Object_Post::ACTION_TRASH,
            aam_Control_Object_Post::ACTION_DELETE,
            aam_Control_Object_Post::ACTION_EDIT
        );
    }

}