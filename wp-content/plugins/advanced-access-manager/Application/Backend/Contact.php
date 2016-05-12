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
class AAM_Backend_Contact {
    
    /**
     * Get HTML content
     * 
     * @return string
     * 
     * @access public
     */
    public function getContent() {
        ob_start();
        require_once(dirname(__FILE__) . '/view/contact.phtml');
        $content = ob_get_contents();
        ob_end_clean();

        return $content;
    }
    
    /**
     * Update the extension
     * 
     * @return string
     * 
     * @access public
     */
    public function update() {
        $extension = AAM_Core_Request::post('extension');
        
        $list = AAM_Core_API::getOption('aam-extension-license', array());
        if (isset($list[$extension])) {
            $response = $this->install($list[$extension]);
        } else {
            $response = json_encode(array(
                'status' => 'failure', 
                'error' => __('License key is missing.', AAM_KEY)
            ));
        }
        
        return $response;
    }
    
    /**
     * Register Contact/Hire feature
     * 
     * @return void
     * 
     * @access public
     */
    public static function register() {
        AAM_Backend_Feature::registerFeature((object) array(
            'uid' => 'contact',
            'position' => 1000,
            'title' => __('Contact Us', AAM_KEY),
            'subjects' => array(
                'AAM_Core_Subject_Role', 
                'AAM_Core_Subject_User', 
                'AAM_Core_Subject_Visitor'
            ),
            'view' => __CLASS__
        ));
    }

}