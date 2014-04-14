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
     * Parsed ConfigPress from the file
     * 
     * @var Zend_Config_Ini
     * 
     * @access private
     * @static 
     */
    private static $_config = null;

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
     * Write ConfigPres to file
     * 
     * @param string $content
     * 
     * @return boolean
     * 
     * @access public
     * @static
     */
    public static function write($content) {
        if (is_writable(AAM_TEMP_DIR)) {
            $filename = aam_Core_API::getBlogOption('aam_configpress', '');
            if (!$filename) { //file already was created
                $filename = sha1(uniqid('aam'));
                aam_Core_API::updateBlogOption('aam_configpress', $filename);
            }
            $response = file_put_contents(
                    AAM_TEMP_DIR . $filename, stripcslashes($content)
            );
        } else {
            $response = false;
        }

        return $response;
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
        //initialize the ConfigPress if empty
        if (is_null(self::$_config)) {
            $filename = aam_Core_API::getBlogOption('aam_configpress', '');
            if ($filename && file_exists(AAM_TEMP_DIR . $filename)) {
                //parse the file content & create Config INI Object
                self::parseConfig(AAM_TEMP_DIR . $filename);
            }
        }

        //find the parameter
        $tree = self::$_config;
        foreach (explode('.', $param) as $section) {
            if (isset($tree->{$section})) {
                $tree = $tree->{$section};
            } else {
                $tree = $default;
                break;
            }
        }

        return self::parseParam($tree, $default);
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
        if (is_object($param) && isset($param->userFunc)) {
            $func = trim($param->userFunc);
            if (is_string($func) && is_callable($func)) {
                $response = call_user_func($func);
            } else {
                $response = $default;
            }
        } else {
            $response = $param;
        }

        return $response;
    }

    /**
     * Parse ConfigPress file and create an object
     * 
     * @param string $filename
     * 
     * @return void
     * 
     * @access protected
     * @static
     */
    protected static function parseConfig($filename) {
        //include third party library
        if (!class_exists('Zend_Config')) {
            require_once(AAM_LIBRARY_DIR . 'Zend/Exception.php');
            require_once(AAM_LIBRARY_DIR . 'Zend/Config/Exception.php');
            require_once(AAM_LIBRARY_DIR . 'Zend/Config.php');
            require_once(AAM_LIBRARY_DIR . 'Zend/Config/Ini.php');
        }
        //parse ini file
        try {
            self::$_config = new Zend_Config_Ini($filename);
        } catch (Zend_Config_Exception $e) {
            //do nothing
        }
    }

}