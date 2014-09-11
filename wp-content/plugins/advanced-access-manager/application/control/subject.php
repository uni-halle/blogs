<?php

/**
 * ======================================================================
 * LICENSE: This file is subject to the terms and conditions defined in *
 * file 'license.txt', which is part of this source code package.       *
 * ======================================================================
 */

/**
 * Abstract Subject Controller
 *
 * @package AAM
 * @author Vasyl Martyniuk <support@wpaam.com>
 * @copyright Copyright C 2013 Vasyl Martyniuk
 * @license GNU General Public License {@link http://www.gnu.org/licenses/}
 */
abstract class aam_Control_Subject {

    /**
     * Flag that idicates that subject has been modified
     */
    const FLAG_MODIFIED = 'modified_flag';
    
    /**
     * Subject ID
     *
     * Whether it is User ID or Role ID
     *
     * @var string|int
     *
     * @access private
     */
    private $_id;

    /**
     * WordPres Subject
     *
     * It can be WP_User or WP_Role, based on what class has been used
     *
     * @var WP_Role|WP_User
     *
     * @access private
     */
    private $_subject;

    /**
     * List of Objects to be access controled for current subject
     *
     * All access control objects like Admin Menu, Metaboxes, Posts etc
     *
     * @var array
     *
     * @access private
     */
    private $_objects = array();

    /**
     * Update Cache flag
     *
     * If there is any new object instantiated, update cache too
     *
     * @var boolean
     *
     * @access private
     */
    private $_updateCache = false;

    /**
     * Constructor
     *
     * @param string|int $id
     *
     * @return void
     *
     * @access public
     */
    public function __construct($id) {
        //set subject
        $this->setId($id);
        //retrieve and set subject itself
        $this->setSubject($this->retrieveSubject());
        //retrieve cache if there is any
        $this->initCache();
    }

    /**
     * Initialize cache
     *
     * @return void
     *
     * @access public
     */
    public function initCache(){
        if (aam_Core_ConfigPress::getParam('aam.caching', 'false') === "true"){
            $this->setObjects($this->readCache());
            foreach($this->_objects as $objects){
                foreach($objects as $object){
                    if (!($object instanceof __PHP_Incomplete_Class)) {
                        $object->setSubject($this);
                    }
                }
            }
        }
    }

    /**
     * Desctruct the subject
     *
     * Execute extra actions during application shutdown
     *
     * @return void
     *
     * @access public
     */
    public function saveCache(){
        $caching = aam_Core_ConfigPress::getParam('aam.caching', 'false');
        if (($this->_updateCache === true) && ($caching === "true")){
            $this->updateCache();
        }
    }

    /**
     * Trigger Subject native methods
     *
     * @param string $name
     * @param array  $arguments
     *
     * @return mixed
     *
     * @access public
     */
    public function __call($name, $arguments) {
        $subject = $this->getSubject();
        //make sure that method is callable
        if (method_exists($subject, $name)) {
            $response = call_user_func_array(array($subject, $name), $arguments);
        } else {
            $response = null;
        }

        return $response;
    }

    /**
     * Get Subject's native properties
     *
     * @param string $name
     *
     * @return mixed
     *
     * @access public
     */
    public function __get($name) {
        $subject = $this->getSubject();
        //TODO - In multisite Wp_User roles are not initialized if admin not a part
        //of the site
        return @$subject->$name;
    }

    /**
     * Set Subject's native properties
     *
     * @param string $name
     *
     * @return mixed
     *
     * @access public
     */
    public function __set($name, $value) {
        $subject = $this->getSubject();
        $subject->$name = $value;
    }

    /**
     * Set Subject ID
     *
     * @param string|int
     *
     * @return void
     *
     * @access public
     */
    public function setId($id) {
        $this->_id = $id;
    }

    /**
     * Get Subject ID
     *
     * @return string|int
     *
     * @access public
     */
    public function getId() {
        return $this->_id;
    }

    /**
     * Get Subject
     *
     * @return WP_Role|WP_User
     *
     * @access public
     */
    public function getSubject() {
        return $this->_subject;
    }

    /**
     * Set Subject
     *
     * @param WP_Role|WP_User $subject
     *
     * @return void
     *
     * @access public
     */
    public function setSubject($subject) {
        $this->_subject = $subject;
    }

    /**
     * Set Objects
     *
     * If there is any cache, set the complete set of objects
     *
     * @return void
     *
     * @access public
     */
    public function setObjects($objects) {
        $this->_objects = $objects;
    }

    /**
     * Get Access Objects
     *
     * @return array
     *
     * @access public
     */
    public function getObjects() {
        return $this->_objects;
    }

    /**
     * Get Individual Object
     *
     * @param string $object
     * @param mixed  $object_id
     *
     * @return aam_Control_Object
     *
     * @access public
     */
    public function getObject($object, $object_id = 0) {
        //make sure that object group is defined
        if (!isset($this->_objects[$object])){
            $this->_objects[$object] = array();
        }
        //check if there is an object with specified ID
        if (!isset($this->_objects[$object][$object_id])) {
            $class_name = 'aam_Control_Object_' . ucfirst($object);
            if (class_exists($class_name)) {
                $this->_objects[$object][$object_id] = new $class_name(
                    $this, $object_id, $this
                );
            } else {
                $this->_objects[$object][$object_id] = apply_filters(
                        'aam_object', null, $object, $object_id, $this
                );
            }

            //set update cache flag to true if object can be cached
            if ($this->_objects[$object][$object_id]->cacheObject() === true){
                $this->_updateCache = true;
            }
        }

        return $this->_objects[$object][$object_id];
    }

    /**
     * Set Individual Access Object
     *
     * @param aam_Control_Object $object
     * @param string             $uid
     *
     * @return void
     *
     * @access public
     */
    public function setObject(aam_Control_Object $object, $uid) {
        $this->_objects[$uid] = $object;
    }
    
    /**
     * Release the object
     * 
     * @param string      $uid
     * @param string|int  $object_id
     * 
     * @return void
     * 
     * @access public
     */
    public function releaseObject($uid, $object_id = 0){
        if (isset($this->_objects[$uid][$object_id])){
            unset($this->_objects[$uid][$object_id]);
        }
    }

    /**
     * Get Subject Type
     *
     * @return string
     *
     * @access public
     */
    public function getType() {
        return get_class($this->getSubject());
    }

    /**
     *
     * @param type $capability
     * @return type
     */
    public function hasCapability($capability) {
        return $this->getSubject()->has_cap($capability);
    }
    
    /**
     * Save Access Parameters
     *
     * @param array $params
     *
     * @return boolean
     *
     * @access public
     */
    public function save(array $params) {
        foreach ($params as $object_type => $dump) {
            if ($object = $this->getObject($object_type)) {
                $object->save($dump);
            }
        }

        //clear cache
        $this->clearCache();
    }

    /**
     * Retrieve list of subject's capabilities
     *
     * @return array
     *
     * @access public
     */
    abstract public function getCapabilities();

    /**
     * Read Cache
     *
     * Cache all settings to speed-up the AAM execution
     *
     * @return void
     *
     * @access public
     */
    abstract public function readCache();

    /**
     * Update Cache
     *
     * If there is any change to cache, update it and save to database
     *
     * @return boolean
     *
     * @access public
     */
    abstract public function updateCache();

    /**
     * Clear the Subject Cache
     *
     * @return boolean
     *
     * @access public
     */
    abstract public function clearCache();

    /**
     * Clear all options
     *
     * Remove all options related to current user from database and any other
     * custom storage
     *
     * @return void
     *
     * @access public
     */
    abstract public function clearAllOptions();

    /**
     * Check if subject has specified flag
     *
     * @param string flag
     *
     * @return boolean
     *
     * @access public
     */
    abstract public function hasFlag($flag);

    /**
     * Set Subject Flag
     *
     * @param string   $flag
     * @param boolean  $value
     *
     * @return void
     *
     * @access protected
     */
    abstract public function setFlag($flag, $value = true);

    /**
     * Retrieve subject based on used class
     *
     * @return void
     *
     * @access protected
     */
    abstract protected function retrieveSubject();
    
    /**
     * Read object from parent subject
     * 
     * @param string $object
     * @param mixed  $object_id
     * 
     * @return mixed
     * 
     * @access public
     */
    public function readParentSubject($object, $object_id){
        if ($subject = $this->getParentSubject()){
            $option = $subject->getObject($object, $object_id)->getOption();
        } else {
            $option = null;
        }
        
        return $option;
    }
    
    /**
     * Retrive parent subject
     * 
     * If there is no parent subject, return null
     * 
     * @return aam_Control_Subject|null
     * 
     * @access public
     */
    abstract public function getParentSubject();
}