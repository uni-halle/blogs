<?php

/**
 * ======================================================================
 * LICENSE: This file is subject to the terms and conditions defined in *
 * file 'license.txt', which is part of this source code package.       *
 * ======================================================================
 */

/**
 * AAM Importer
 * 
 * @package AAM
 * @author Vasyl Martyniuk <vasyl@vasyltech.com>
 */
class AAM_Core_Importer {
    
    /**
     *
     * @var type 
     */
    protected $input = null;
    
    /**
     * 
     * @param type $input
     */
    public function __construct($input) {
        $this->input = json_decode($input);
    }
    
    /**
     * 
     * @return type
     */
    public function run() {
        foreach($this->input->dataset as $table => $data) {
            if ($table == '_options') {
                $this->insertOptions($data);
            } elseif ($table == '_postmeta') {
                $this->insertPostmeta($data);
            } elseif ($table == '_usermeta') {
                $this->insertUsermeta($data);
            } else {
                do_action('aam-import', $table, $data);
            }
        }
        
        return 'success';
    }
    
    protected function insertOptions($data) {
        global $wpdb;
        
        foreach($data as $key => $value) {
            update_option(
                    preg_replace('/^_/', $wpdb->prefix, $key), 
                    $this->prepareValue($value)
            );
        }
    }
    
    protected function insertUsermeta($data) {
        global $wpdb;
        
        foreach($data as $id => $set) {
            foreach($set as $key => $value) {
                update_user_meta(
                        $id, 
                        preg_replace('/^_/', $wpdb->prefix, $key), 
                        $this->prepareValue($value)
                );
            }
        }
    }
    
     protected function insertPostmeta($data) {
        global $wpdb;
         
        foreach($data as $id => $set) {
            foreach($set as $key => $value) {
                update_post_meta(
                        $id, 
                        preg_replace('/^_/', $wpdb->prefix, $key), 
                        $this->prepareValue($value)
                );
            }
        }
    }
    
    protected function prepareValue($value) {
        if (is_serialized($value)) {
            $value = unserialize($value);
        }
        
        return $value;
    }
    
}