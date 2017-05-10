<?php

/**
 * ======================================================================
 * LICENSE: This file is subject to the terms and conditions defined in *
 * file 'license.txt', which is part of this source code package.       *
 * ======================================================================
 */

/**
 * Teaser object
 * 
 * @package AAM
 * @author Vasyl Martyniuk <vasyl@vasyltech.com>
 */
class AAM_Core_Object_Teaser extends AAM_Core_Object {
    
    /**
     * Constructor
     *
     * @param AAM_Core_Subject $subject
     *
     * @return void
     *
     * @access public
     */
    public function __construct(AAM_Core_Subject $subject) {
        parent::__construct($subject);

        $this->read();
    }
    
    /**
     *
     * @return void
     *
     * @access public
     */
    public function read() {
        $option = $this->getSubject()->readOption('teaser');
       
        //inherit from default Administrator role
        if (empty($option)) {
             //inherit from parent subject
            $option = $this->getSubject()->inheritFromParent('teaser');
            if (empty($option)) {
                $option = array();
                $this->readByArea('frontend', $option);
            }
        } elseif (method_exists($this, 'setOverwritten')) { //TODO - Support legacy
            $this->setOverwritten(true);
        }
        
        $this->setOption($option);
    }
    
    /**
     * 
     * @param type $area
     * @param type $option
     */
    protected function readByArea($area, &$option) {
        $message = AAM_Core_Config::get("{$area}.teaser.message");
        $excerpt = AAM_Core_Config::get("{$area}.teaser.excerpt");
        if ($message || $excerpt) {
            $option["{$area}.teaser.message"] = $message;
            $option["{$area}.teaser.excerpt"] = $excerpt;
        }
    }

    /**
     * Save options
     * 
     * @param string  $property
     * @param boolean $value
     * 
     * @return boolean
     * 
     * @access public
     */
    public function save($property, $value) {
        $option            = $this->getOption();
        $option[$property] = $value;
        
        return $this->getSubject()->updateOption($option, 'teaser');
    }
    
    /**
     * 
     * @return type
     */
    public function reset() {
        return $this->getSubject()->deleteOption('teaser');
    }

    /**
     * 
     * @param string $param
     * 
     * @return boolean
     * 
     * @access public
     */
    public function has($param) {
        $option = $this->getOption();
        
        return isset($option[$param]);
    }
    
    /**
     * 
     * @param string $param
     * 
     * @return boolean
     * 
     * @access public
     */
    public function get($param) {
        $option = $this->getOption();
        
        return !empty($option[$param]) ? $option[$param] : null;
    }
    
}