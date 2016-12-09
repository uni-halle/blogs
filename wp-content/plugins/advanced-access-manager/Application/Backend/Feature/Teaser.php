<?php

/**
 * ======================================================================
 * LICENSE: This file is subject to the terms and conditions defined in *
 * file 'license.txt', which is part of this source code package.       *
 * ======================================================================
 */

/**
 * Content teaser manager
 * 
 * @package AAM
 * @author Vasyl Martyniuk <vasyl@vasyltech.com>
 */
class AAM_Backend_Feature_Teaser extends AAM_Backend_Feature_Abstract {
    
    /**
     * 
     */
    public function save() {
        $param   = AAM_Core_Request::post('param');
        $value   = AAM_Core_Request::post('value');
        $subject = AAM_Backend_View::getSubject();
        
        if ($this->isAdministrator()) {
            AAM_Core_Config::set($param, $value);
        } else {
            do_action('aam-action-teaser-save', $subject, $param, $value);
        }
        
        return json_encode(array('status' => 'success'));
    }
    
    /**
     * 
     * @return type
     */
    public function reset() {
        do_action('aam-action-teaser-reset', AAM_Backend_View::getSubject());
        
        return json_encode(array('status' => 'success')); 
    }
    
    /**
     * 
     * @return type
     */
    public function isAdministrator() {
        $subject = AAM_Backend_View::getSubject();
        $adminId = AAM_Core_Config::get('core.admin.id', 'administrator');
        
        return ($subject->getUID() == 'role' && $subject->getId() == $adminId);
    }
    
    /**
     * 
     * @param type $option
     * @return type
     */
    public function getOption($option, $default = null) {
        $value = AAM_Core_Config::get($option, $default);
        
        if (!$this->isAdministrator()) {
            $subject = AAM_Backend_View::getSubject();
            $value = apply_filters(
                    'aam-filter-teaser-option', $value, $option, $subject
            );
        }
        
        return $value;
    }
    
    /**
     * @inheritdoc
     */
    public static function getAccessOption() {
        return 'feature.teaser.capability';
    }
    
    /**
     * @inheritdoc
     */
    public static function getTemplate() {
        return 'object/teaser.phtml';
    }
    
    /**
     * Register Contact/Hire feature
     * 
     * @return void
     * 
     * @access public
     */
    public static function register() {
        $cap = AAM_Core_Config::get(self::getAccessOption(), 'administrator');
        
        AAM_Backend_Feature::registerFeature((object) array(
            'uid'        => 'teaser',
            'position'   => 40,
            'title'      => __('Content Teaser', AAM_KEY),
            'capability' => $cap,
            'subjects'   => array(
                'AAM_Core_Subject_Role', 
                'AAM_Core_Subject_User', 
                'AAM_Core_Subject_Visitor'
            ),
            'view'       => __CLASS__
        ));
    }

}