<?php

/**
 * ======================================================================
 * LICENSE: This file is subject to the terms and conditions defined in *
 * file 'license.txt', which is part of this source code package.       *
 * ======================================================================
 */

/**
 * Backend contact/hire manager
 * 
 * @package AAM
 * @author Vasyl Martyniuk <vasyl@vasyltech.com>
 */
class AAM_Backend_Feature_Contact extends AAM_Backend_Feature_Abstract {
    
    /**
     * @inheritdoc
     */
    public static function getAccessOption() {
        return 'feature.contact.capability';
    }
    
    /**
     * @inheritdoc
     */
    public static function getTemplate() {
        return 'contact.phtml';
    }
    
    /**
     * Register Contact/Hire feature
     * 
     * @return void
     * 
     * @access public
     */
    public static function register() {
        if (AAM_Core_API::capabilityExists('aam_view_contact')) {
            $cap = 'aam_view_contact';
        } else {
            $cap = AAM_Core_Config::get(
                    self::getAccessOption(), AAM_Backend_View::getAAMCapability()
            );
        }
        
        AAM_Backend_Feature::registerFeature((object) array(
            'uid'        => 'contact',
            'position'   => 9999,
            'title'      => __('Contact Us', AAM_KEY),
            'capability' => $cap,
            'subjects'   => array(
                'AAM_Core_Subject_Role', 
                'AAM_Core_Subject_User', 
                'AAM_Core_Subject_Visitor',
                'AAM_Core_Subject_Default'
            ),
            'view'       => __CLASS__
        ));
    }

}