<?php

/**
 * ======================================================================
 * LICENSE: This file is subject to the terms and conditions defined in *
 * file 'license.txt', which is part of this source code package.       *
 * ======================================================================
 */

/**
 * Post object
 * 
 * @package AAM
 * @author Vasyl Martyniuk <vasyl@vasyltech.com>
 */
class AAM_Core_Object_Post extends AAM_Core_Object {

    /**
     * Post object
     * 
     * @var WP_Post
     * 
     * @access private
     */
    private $_post;

    /**
     * Constructor
     *
     * @param AAM_Core_Subject $subject
     * @param WP_Post|Int      $post
     *
     * @return void
     *
     * @access public
     */
    public function __construct(AAM_Core_Subject $subject, $post) {
        parent::__construct($subject);

        //make sure that we are dealing with WP_Post object
        if ($post instanceof WP_Post) {
            $this->setPost($post);
        } elseif (intval($post)) {
            $this->setPost(get_post($post));
        }

        if ($this->getPost()) {
            $this->read();
        }
    }

    /**
     * Read the Post AAM Metadata
     *
     * Get all settings related to specified post.
     *
     * @return void
     *
     * @access public
     */
    public function read() {
        $subject = $this->getSubject();
        $opname = $this->getOptionName();
        $chname = $opname . '|' . $this->getPost()->ID;

        //read cache first
        $option = apply_filters('aam-read-cache-filter', array(), $chname, $subject);
        
        if ($option === false) { //if false, then the cache is empty but exist
            $option = array();
        } else {
            if (empty($option)) { //no cache for this element
                $option = get_post_meta($this->getPost()->ID, $opname, true);
            }
            
            //try to inherit from terms or default first - AAM Plus Package or any
            //other extension that use this filter
            $option = apply_filters('aam-post-access-filter', $option, $this);

            //try to inherit from parent
            if (empty($option)) {
                $option = $subject->inheritFromParent('post', $this->getPost()->ID);
                $this->setInherited(empty($option) ? null : 'role');
            }
        }
        
        $this->setOption($option);

        //trigger caching mechanism
        do_action(
            'aam-write-cache-action', 
            $chname, 
            //if cache is on and result is empty, simply cache the false to speed-up
            (empty($option) ? false : $option), 
            $subject
        );
    }

    /**
     * Save options
     * 
     * @return boolean
     * 
     * @access public
     */
    public function save($property, $checked) {
        $option = $this->getOption();
        
        $option[$property] = $checked;
        
        return update_post_meta(
                $this->getPost()->ID, $this->getOptionName(), $option
        );
    }
    
    /**
     * Reset post settings
     * 
     * @return boolean
     * 
     * @access public
     */
    public function reset() {
        return delete_post_meta($this->getPost()->ID, $this->getOptionName());
    }

    /**
     * Set Post. Cover all unexpectd wierd issues with WP Core
     *
     * @param WP_Post $post
     *
     * @return void
     *
     * @access public
     */
    public function setPost($post) {
        if ($post instanceof WP_Post) {
            $this->_post = $post;
        } else {
            $this->_post = (object) array('ID' => 0);
        }
    }

    /**
     * Generate option name
     * 
     * @return string
     * 
     * @access protected
     */
    protected function getOptionName() {
        $subject = $this->getSubject();
        
        //prepare option name
        $meta_key = 'aam-post-access-' . $subject->getUID();
        $meta_key .= ($subject->getId() ? $subject->getId() : '');

        return $meta_key;
    }

    /**
     * Check if action is resricted
     * 
     * @param string $area
     * @param string $action
     * 
     * @return boolean
     * 
     * @access public
     */
    public function has($action) {
        $option = $this->getOption();

        return !empty($option[$action]);
    }

    /**
     * Get Post
     *
     * @return WP_Post|stdClass
     *
     * @access public
     */
    public function getPost() {
        return $this->_post;
    }
    
    /**
     * Check if options were overwritten
     * 
     * In order to consider options overwritten there are three conditions to be met:
     * - Current object should have no empty option set;
     * - The inherited flad should be null;
     * 
     * @return boolean
     * 
     * @access public
     */
    public function isOverwritten () {
        $option  = $this->getOption();
        $inherit = $this->getInherited();
        
        return (!empty($option) && is_null($inherit));
    }

}