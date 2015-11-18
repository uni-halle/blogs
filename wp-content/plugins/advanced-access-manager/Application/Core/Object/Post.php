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

        //cache extension in place first
        $option = apply_filters('aam-read-cache-filter', null, $opname, $subject);

        if (empty($option)) { //no cache, than try to read it from DB
            $option = get_post_meta($this->getPost()->ID, $opname, true);
        }
        
        //try to inherit from parent
        if (empty($option)) {
            $option = $subject->inheritFromParent('post', $this->getPost()->ID);
        }

        $this->setOption(apply_filters('aam-post-access-filter', $option, $this));

        //trigger caching mechanism
        do_action('aam-write-cache-action', $opname, $option, $subject);
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
        
        if ($checked) {
            $option[$property] = $checked;
        } elseif (isset($option[$property])) {
            //this is important, because if you uncheck all options, AAM will try to
            //inherit settings from the parent. This way there is no need to 
            //implement "Reset Settings" feature
            unset($option[$property]); //remove it
        }
        
        //clear cache
        do_action('aam-clear-cache-action', $this->getSubject());
        
        return update_post_meta(
                $this->getPost()->ID, $this->getOptionName(), $option
        );
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

}