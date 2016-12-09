<?php

/**
 * ======================================================================
 * LICENSE: This file is subject to the terms and conditions defined in *
 * file 'license.txt', which is part of this source code package.       *
 * ======================================================================
 */

/**
 * Backend Utility manager
 * 
 * @package AAM
 * @author Vasyl Martyniuk <vasyl@vasyltech.com>
 */
class AAM_Backend_Feature_Utility  extends AAM_Backend_Feature_Abstract {
    
    /**
     * @inheritdoc
     */
    public static function getAccessOption() {
        return 'feature.utility.capability';
    }
    
    /**
     * @inheritdoc
     */
    public static function getTemplate() {
        return 'utility.phtml';
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
     * Clear all AAM settings
     * 
     * @global wpdb $wpdb
     * 
     * @return string
     * 
     * @access public
     */
    public function clear() {
        global $wpdb;
        
        //clear wp_options
        $oquery = "DELETE FROM {$wpdb->options} WHERE `option_name` LIKE %s";
        $wpdb->query($wpdb->prepare($oquery, 'aam%' ));
        
        //clear wp_postmeta
        $pquery = "DELETE FROM {$wpdb->postmeta} WHERE `meta_key` LIKE %s";
        $wpdb->query($wpdb->prepare($pquery, 'aam%' ));
        
        //clear wp_usermeta
        $uquery = "DELETE FROM {$wpdb->usermeta} WHERE `meta_key` LIKE %s";
        $wpdb->query($wpdb->prepare($uquery, 'aam%' ));
        
        $mquery = "DELETE FROM {$wpdb->usermeta} WHERE `meta_key` LIKE %s";
        $wpdb->query($wpdb->prepare($mquery, $wpdb->prefix . 'aam%' ));
        
        return json_encode(array('status' => 'success'));
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
            'uid'        => 'utilities',
            'position'   => 100,
            'title'      => __('Utilities', AAM_KEY),
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