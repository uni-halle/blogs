<?php

/*
  Copyright (C) <2011>  Vasyl Martyniuk <martyniuk.vasyl@gmail.com>

  This program is free software: you can redistribute it and/or modify
  it under the terms of the GNU General Public License as published by
  the Free Software Foundation, either version 3 of the License, or
  (at your option) any later version.

  This program is distributed in the hope that it will be useful,
  but WITHOUT ANY WARRANTY; without even the implied warranty of
  MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
  GNU General Public License for more details.

  You should have received a copy of the GNU General Public License
  along with this program.  If not, see <http://www.gnu.org/licenses/>.

 */

/**
 * Access Script Model Class
 *
 * Access Script
 *
 * @package AAM
 * @subpackage Models
 * @author Vasyl Martyniuk <martyniuk.vasyl@gmail.com>
 * @copyrights Copyright © 2011 Vasyl Martyniuk
 * @license GNU General Public License {@link http://www.gnu.org/licenses/}
 */
class mvb_Model_ConfigPress {

    protected static $config = NULL;

    protected static function getConfig() {

        $file = WPACCESS_BASE_DIR . 'config.ini';
        if ( (self::$config == NULL) && is_readable($file) ) {
            require_once('Zend/Config.php');
            require_once('Zend/Config/Ini.php');
            try{
                self::$config = new Zend_Config_Ini($file);
            }catch(Zend_Config_Exception $e){
                add_action('admin_notices', 'mvb_Model_ConfigPress::triggerError');
            }
        }

        return self::$config;
    }

    public static function triggerError(){

        mvb_Model_Label::initGUILabels();
        mvb_Model_Helper::triggerNotice(mvb_Model_Label::get('LABEL_95'));
    }

    public static function saveConfig($config) {

        $file = WPACCESS_BASE_DIR . 'config.ini';
        if (is_writable($file) || chmod($file, 0755)) {
            file_put_contents($file, $config);
        }
        //also save to db as backup
        $default_blog = mvb_Model_API::getBlog(1);
        mvb_Model_API::updateBlogOption(
                WPACCESS_PREFIX . 'config_press', $config, $default_blog
        );
        //clear cache
        self::$config = NULL;
    }

    public static function readConfig() {

        $file = WPACCESS_BASE_DIR . 'config.ini';

        if (is_readable($file)) {
            $config = file_get_contents(WPACCESS_BASE_DIR . 'config.ini');
        } else {
            $config = FALSE;
        }

        return $config;
    }

    protected static function parseParam($param) {

        $result = FALSE;
        if (is_object($param) && isset($param->userFunc)) {
            $func = trim($param->userFunc);
            if (is_string($func) && is_callable($func)) {
                $result = call_user_func($func);
            }
        } else {
            $result = $param;
        }

        return $result;
    }

    public static function getOption($option, $default = NULL) {

        $tree = self::getConfig();
        foreach (explode('.', $option) as $param) {
            if (isset($tree->{$param})) {
                $tree = $tree->{$param};
            } else {
                $tree = $default;
                break;
            }
        }

        return self::parseParam($tree);
    }

}

?>