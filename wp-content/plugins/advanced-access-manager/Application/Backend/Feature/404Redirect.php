<?php

/**
 * ======================================================================
 * LICENSE: This file is subject to the terms and conditions defined in *
 * file 'license.txt', which is part of this source code package.       *
 * ======================================================================
 */

/**
 * Backend 404 redirect manager
 * 
 * @package AAM
 * @author Vasyl Martyniuk <vasyl@vasyltech.com>
 */
class AAM_Backend_Feature_404Redirect  extends AAM_Backend_Feature_Abstract {
    
    /**
     * @inheritdoc
     */
    public static function getAccessOption() {
        return 'feature.404redirect.capability';
    }
    
    /**
     * @inheritdoc
     */
    public static function getTemplate() {
        return '404redirect.phtml';
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
     * Register 404 redirect feature
     * 
     * @return void
     * 
     * @access public
     */
    public static function register() {
        if (is_main_site()) {
            if (AAM_Core_API::capabilityExists('aam_manage_404_redirect')) {
                $cap = 'aam_manage_404_redirect';
            } else {
                $cap = AAM_Core_Config::get(
                        self::getAccessOption(), AAM_Backend_View::getAAMCapability()
                );
            }

            AAM_Backend_Feature::registerFeature((object) array(
                'uid'        => '404redirect',
                'position'   => 50,
                'title'      => __('404 Redirect', AAM_KEY),
                'capability' => $cap,
                'subjects'   => array(
                    'AAM_Core_Subject_Default'
                ),
                'view'       => __CLASS__
            ));
        }
    }

}