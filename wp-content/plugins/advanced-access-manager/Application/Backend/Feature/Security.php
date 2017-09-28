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