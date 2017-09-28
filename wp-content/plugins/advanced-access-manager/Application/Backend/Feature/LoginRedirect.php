<?php

/**
 * ======================================================================
 * LICENSE: This file is subject to the terms and conditions defined in *
 * file 'license.txt', which is part of this source code package.       *
 * ======================================================================
 */

/**
 * Login redirect
 * 
 * @package AAM
 * @author Vasyl Martyniuk <vasyl@vasyltech.com>
 */
class AAM_Backend_Feature_LoginRedirect extends AAM_Backend_Feature_Abstract {
    
    /**
     * 
     * @return type
     */
    public function isDefault() {
        return (AAM_Backend_View::getSubject()->getUID() == 'default');
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
        $object = AAM_Backend_View::getSubject()->getObject('loginRedirect');
        
        return $object->isOverwritten();
    }
    
    /**
     * 
     * @param type $option
     * @return type
     */
    public function getOption($option, $default = null) {
        $object = AAM_Backend_View::getSubject()->getObject('loginRedirect');
        $value  = $object->get($option);
        
        return (!is_null($value) ? $value : $default);
    }
    
    /**
     * @inheritdoc
     */
    public static function getAccessOption() {
        return 'feature.login-redirect.capability';
    }
    
    /**
     * @inheritdoc
     */
    public static function getTemplate() {
        return 'object/login-redirect.phtml';
    }
    
    /**
     * Register Contact/Hire feature
     * 
     * @return void
     * 
     * @access public
     */
    public static function register() {
        if (AAM_Core_API::capabilityExists('aam_manage_login_redirect')) {
            $cap = 'aam_manage_login_redirect';
        } else {
            $cap = AAM_Core_Config::get(
                    self::getAccessOption(), AAM_Backend_View::getAAMCapability()
            );
        }
        
        AAM_Backend_Feature::registerFeature((object) array(
            'uid'        => 'login_redirect',
            'position'   => 40,
            'title'      => __('Login Redirect', AAM_KEY),
            'capability' => $cap,
            'subjects'   => array(
                'AAM_Core_Subject_Role', 
                'AAM_Core_Subject_User', 
                'AAM_Core_Subject_Default'
            ),
            'view'       => __CLASS__
        ));
    }

}