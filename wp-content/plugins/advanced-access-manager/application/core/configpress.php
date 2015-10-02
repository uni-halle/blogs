<?php

/**
 * ======================================================================
 * LICENSE: This file is subject to the terms and conditions defined in *
 * file 'license.txt', which is part of this source code package.       *
 * ======================================================================
 */

/**
 * ConfigPress handler
 * 
 * @package AAM
 * @author Vasyl Martyniuk <support@wpaam.com>
 * @copyright Copyright C 2013 Vasyl Martyniuk
 * @license GNU General Public License {@link http://www.gnu.org/licenses/}
 */
final class aam_Core_ConfigPress {

    /**
     * Read ConfigPress File content
     * 
     * @return string
     * 
     * @access public
     * @static
     */
    public static function read() {
        $filename = aam_Core_API::getBlogOption('aam_configpress', '');
        if ($filename && file_exists(AAM_TEMP_DIR . $filename)) {
            $content = file_get_contents(AAM_TEMP_DIR . $filename);
        } else {
            $content = '';
        }

        return $content;
    }

    /**
     * Get ConfigPress parameter
     * 
     * @param string $param
     * @param mixed $default
     * 
     * @return mixed
     * 
     * @access public
     * @static
     */
    public static function getParam($param, $default = null) {
        if (class_exists('ConfigPress')) {
            $response = ConfigPress::get($param, $default);
        } else {
            $response = $default;
        }

        return self::parseParam($response, $default);
    }

    /**
     * Parse found parameter
     * 
     * @param mixed $param
     * @param mixed $default
     * 
     * @return mixed
     * 
     * @access protected
     * @static
     */
    protected static function parseParam($param, $default) {
        if (is_array($param) && isset($param['userFunc'])) {
            if (is_callable($param['userFunc'])) {
                $response = call_user_func($param['userFunc']);
            } else {
                $response = $default;
            }
        } else {
            $response = $param;
        }

        return $response;
    }

}