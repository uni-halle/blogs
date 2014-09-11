<?php

/**
 * ======================================================================
 * LICENSE: This file is subject to the terms and conditions defined in *
 * file 'license.txt', which is part of this source code package.       *
 * ======================================================================
 */

/**
 * Abstract class for all View Models
 * 
 * @package AAM
 * @author Vasyl Martyniuk <support@wpaam.com>
 * @copyright Copyright C Vasyl Martyniuk
 * @license GNU General Public License {@link http://www.gnu.org/licenses/}
 */
abstract class aam_View_Abstract {

    /**
     * Current Subject
     * 
     * @var aam_Control_Subject
     * 
     * @access private
     */
    static private $_subject = null;
    
    /**
     * Construct the Object
     * 
     * Instantiate the subject one type that is going to be shared with all view
     * models.
     * 
     * @return void
     * 
     * @access public
     */
    public function __construct() {
        if (is_null(self::$_subject)) {
            $subject_class = 'aam_Control_Subject_' . ucfirst(
                trim(aam_Core_Request::request('subject'), '')
            );
            if (class_exists($subject_class)){
                $this->setSubject(new $subject_class(
                    aam_Core_Request::request('subject_id')
                ));
                //check if view for current subject can be managed
                $this->isManagable();
            }
        }
        
        //control default option list
        add_filter('aam_default_option_list', array($this, 'defaultOption'));
    }
    
    /**
     * Check if view can be managed
     * 
     * @return void
     * 
     * @access public
     * @throw Exception You are not allowed to manage current view 
     */
    public function isManagable(){
        if ($this->getSubject()->getUID() == aam_Control_Subject_Role::UID){
            $caps = $this->getSubject()->capabilities;
        } elseif ($this->getSubject()->getUID == aam_Control_Subject_User::UID){
            //AAM does not support multi-roles. Get only one first role
            $roles = $this->getSubject()->roles;
            $caps = get_role(array_shift($roles))->capabilities;
        } else {
            $caps = apply_filters('aam_managable_capabilities', null, $this);
        }
        
        if ($caps && !aam_Core_API::isSuperAdmin()){
            //get user's highest level
            $level = aam_Core_API::getUserLevel();
            if (!empty($caps['level_' . $level]) && $caps['level_' . $level]){
                Throw new Exception(
                        __('You are not allowed to manager current view', 'aam')
                );
            }
        }
        
        return true;
    }
    
    /**
     * Control default set of options
     * 
     * This is very important function. It control the default option for each 
     * feature. It covers the scenario when the set of UI options is represented by
     * checkboxes.
     * 
     * @param array $options
     * 
     * @return array
     * 
     * @access public
     */
    public function defaultOption($options){
        return $options;
    }

    /**
     * Get Subject
     * 
     * @return aam_Control_Subject
     * 
     * @access public
     */
    public function getSubject() {
        return self::$_subject;
    }

    /**
     * Set Subject
     * 
     * @param aam_Control_Subject $subject
     * 
     * @return void
     * 
     * @access public
     */
    public function setSubject(aam_Control_Subject $subject) {
        self::$_subject = $subject;
    }

    /**
     * Load View template
     * 
     * @param string $tmpl_path
     * 
     * @return string
     * 
     * @access public
     */
    public function loadTemplate($tmpl_path) {
        ob_start();
        require_once($tmpl_path);
        $content = ob_get_contents();
        ob_end_clean();

        return $content;
    }
    
}