<?php

/**
 * ======================================================================
 * LICENSE: This file is subject to the terms and conditions defined in *
 * file 'license.txt', which is part of this source code package.       *
 * ======================================================================
 */

/**
 * Backend security manager
 * 
 * @package AAM
 * @author Vasyl Martyniuk <vasyl@vasyltech.com>
 */
class AAM_Backend_Feature_Security extends AAM_Backend_Feature_Abstract {
    
    /**
     * @inheritdoc
     */
    public static function getAccessOption() {
        return 'feature.security.capability';
    }
    
    /**
     * @inheritdoc
     */
    public static function getTemplate() {
        return 'security.phtml';
    }
    
    /**
     * Save AAM utility options
     * 
     * @return string
     *
     * @access public
     */
    public function save() {
        $param = AAM_Core_Request::post('param');
        $value = stripslashes(AAM_Core_Request::post('value'));
        
        AAM_Core_Config::set($param, $value);
        
        return json_encode(array('status' => 'success'));
    }
    
    /**
     * 
     * @return type
     */
    public function getOptionList() {
        $filename = dirname(__FILE__) . '/../View/SecurityOptionList.php';
        $options  = include $filename;
        
        return apply_filters('aam-security-option-list-filter', $options);
    }
    
    /**
     * Register Contact/Hire feature
     * 
     * @return void
     * 
     * @access public
     */
    public static function register() {
        if (is_main_site()) {
            if (AAM_Core_API::capabilityExists('aam_manage_security')) {
                $cap = 'aam_manage_security';
            } else {
                $cap = AAM_Core_Config::get(
                        self::getAccessOption(), AAM_Backend_View::getAAMCapability()
                );
            }

            AAM_Backend_Feature::registerFeature((object) array(
                'uid'        => 'security',
                'position'   => 90,
                'title'      => __('Security', AAM_KEY),
                'capability' => $cap,
                'subjects'   => array(
                    'AAM_Core_Subject_Role'
                ),
                'view'       => __CLASS__
            ));
        }
    }

}