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
     * 
     * @return type
     */
    public function getUtilityOptionList($category = 'miscellaneous') {
        static $options = null;
        
        if (is_null($options)) {
            $filename = dirname(__FILE__) . '/../View/UtilityOptionList.php';
            $options  = apply_filters(
                    'aam-utility-option-list-filter', include $filename
            );
        }
        
        $filtered = array();
        
        foreach($options as $id => $option) {
            if (isset($option['category'])) {
                $cat = $option['category'];
            } else {
                $cat = 'miscellaneous';
            }
            
            if ($cat == $category) {
                $filtered[$id] = $option;
            }
        }
        
        return $filtered;
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
    public function export() {
        $exporter = new AAM_Core_Exporter(AAM_Core_Config::get(
            'export', array('system' => 'roles,utilities,configpress')
        ));
        
        return json_encode(array(
            'status'  => 'success',
            'content' => base64_encode(json_encode($exporter->run()))
        ));
    }
    
    /**
     * 
     * @return type
     */
    public function import() {
        $importer = new AAM_Core_Importer(filter_input(INPUT_POST, 'json'));
        
        return json_encode(array('status'  => $importer->run()));
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
        $oquery  = "DELETE FROM {$wpdb->options} WHERE (`option_name` LIKE %s) AND ";
        $oquery .= "(`option_name` NOT IN ('aam-extensions', 'aam-uid'))";
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
     * 
     * @return type
     */
    public function clearCache() {
        AAM_Core_Cache::clear();
        
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
        if (is_main_site()) {
            if (AAM_Core_API::capabilityExists('aam_manage_utilities')) {
                $cap = 'aam_manage_utilities';
            } else {
                $cap = AAM_Core_Config::get(
                        self::getAccessOption(), AAM_Backend_View::getAAMCapability()
                );
            }

            AAM_Backend_Feature::registerFeature((object) array(
                'uid'        => 'utilities',
                'position'   => 100,
                'title'      => __('Utilities', AAM_KEY),
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

}