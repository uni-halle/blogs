<?php

/**
 * ======================================================================
 * LICENSE: This file is subject to the terms and conditions defined in *
 * file 'license.txt', which is part of this source code package.       *
 * ======================================================================
 */

/**
 * Redirect manager
 * 
 * @package AAM
 * @author Vasyl Martyniuk <vasyl@vasyltech.com>
 */
class AAM_Backend_Feature_Redirect extends AAM_Backend_Feature_Abstract {
    
    /**
     * 
     * @return type
     */
    public function isDefault() {
        return AAM_Backend_View::getSubject()->getUID() == 'default';
    }
    
    /**
     * 
     * @return type
     */
    public function isVisitor() {
        return AAM_Backend_View::getSubject()->getUID() == 'visitor';
    }
    
    /**
     * Check inheritance status
     * 
     * Check if redirect settings are overwritten
     * 
     * @return boolean
     * 
     * @access protected
     */
    protected function isOverwritten() {
        $object = AAM_Backend_View::getSubject()->getObject('redirect');
        
        return $object->isOverwritten();
    }
    
    /**
     * 
     * @param type $option
     * @return type
     */
    public function getOption($option, $default = null) {
        $object = AAM_Backend_View::getSubject()->getObject('redirect');
        $value  = $object->get($option);
        
        return (!is_null($value) ? $value : $default);
    }
    
    /**
     * @inheritdoc
     */
    public static function getAccessOption() {
        return 'feature.redirect.capability';
    }
    
    /**
     * @inheritdoc
     */
    public static function getTemplate() {
        return 'object/redirect.phtml';
    }
    
    /**
     * Register Contact/Hire feature
     * 
     * @return void
     * 
     * @access public
     */
    public static function register() {
        if (AAM_Core_API::capabilityExists('aam_manage_access_denied_redirect')) {
            $cap = 'aam_manage_access_denied_redirect';
        } else {
            $cap = AAM_Core_Config::get(
                    self::getAccessOption(), AAM_Backend_View::getAAMCapability()
            );
        }
        
        AAM_Backend_Feature::registerFeature((object) array(
            'uid'        => 'redirect',
            'position'   => 30,
            'title'      => __('Access Denied Redirect', AAM_KEY),
            'capability' => $cap,
            'subjects'   => array(
                'AAM_Core_Subject_Role', 
                'AAM_Core_Subject_User', 
                'AAM_Core_Subject_Visitor',
                'AAM_Core_Subject_Default'
            ),
            'option'     => 'backend-access-control,frontend-access-control',
            'view'       => __CLASS__
        ));
    }

}