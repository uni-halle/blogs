<?php

/**
 * ======================================================================
 * LICENSE: This file is subject to the terms and conditions defined in *
 * file 'license.txt', which is part of this source code package.       *
 * ======================================================================
 */

/**
 * AAM Core Extension
 *
 * @package AAM
 * @author Vasyl Martyniuk <support@wpaam.com>
 * @copyright Copyright C 2014 Vasyl Martyniuk
 */
class AAM_Core_Extension {

    /**
     * Parent AAM object
     * 
     * @var aam
     * 
     * @access public 
     */
    private $_parent = null;
   
    /**
     * Constructor
     * 
     * @param aam $parent
     * 
     * @return void
     * 
     * @access public 
     */
    public function __construct(aam $parent) {
        $this->setParent($parent);
    }
    
    /**
     * Activate hook
     * 
     * @return boolean
     * 
     * @access public
     */
    public function activate(){
        return true;
    }
    
    /**
     * Set Parent Object
     * 
     * This is reference to main AAM class
     * 
     * @param aam $parent
     * 
     * @return void
     * 
     * @access public
     */
    public function setParent(aam $parent) {
        $this->_parent = $parent;
    }

    /**
     * Get Parent Object
     * 
     * @return aam
     * 
     * @access public
     */
    public function getParent() {
        return $this->_parent;
    }

    /**
     * Get current User
     * 
     * @return aam_Control_Subject_User
     * 
     * @access public
     */
    public function getUser() {
        return $this->getParent()->getUser();
    }

}