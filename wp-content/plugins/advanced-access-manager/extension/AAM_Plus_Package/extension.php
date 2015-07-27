<?php

/**
  Copyright (C) <2013-2014>  Vasyl Martyniuk <support@wpaam.com>

  This program is commercial software: you are not allowed to redistribute it
  and/or modify. Unauthorized copying of this file, via any medium is strictly
  prohibited.
  For any questions or concerns contact Vasyl Martyniuk <support@wpaam.com>
 */

/**
 * AAM Plus Extension Extension
 *
 * @package AAM
 * @author Vasyl Martyniuk <support@wpaam.com>
 * @copyright Copyright C 2013-2014 Vasyl Martyniuk
 */
class AAM_Extension_Plus extends AAM_Core_Extension {

    /**
     * 
     */
    const VERSION = 1;
    
    /**
     * List Posts
     */
    const ACTION_LIST = 'list';

    /**
     * Add New
     */
    const ACTION_ADD = 'add';

    /**
     * Current Subject
     * 
     * @var aam_Control_Subject
     * 
     * @access private 
     */
    private $_subject = null;

    /**
     * Additional Capability list
     * 
     * @var array
     * 
     * @access private 
     */
    private $_comment = array(
        'delete_comment', 'approve_comment', 'edit_comment', 'moderate_comments',
        'spam_comment', 'reply_comment', 'trash_comment', 'unapprove_comment',
        'untrash_comment', 'unspam_comment',
    );

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
        parent::__construct($parent);

        if (is_admin()) {
            add_action('admin_print_scripts', array($this, 'printScripts'));
            add_action(
                    'aam_post_features_render', array($this, 'postFeatureRender')
            );
            add_filter('aam_ajax_call', array($this, 'ajax'), 10, 2);
            add_filter('aam_capability_groups', array($this, 'capabilityGroups'));
            add_filter(
                    'aam_capability_group', array($this, 'capabilityGroup'), 10, 2
            );
            add_filter(
                    'comment_row_actions', array($this, 'commentRowActions'), 10, 2
            );
            add_filter('aam_core_setting', array($this, 'coreSettings'), 10, 2);
            //control post type registration for ADD action
            add_action(
                    'registered_post_type', array($this, 'registredPostType'), 999, 2
            );
            //legacy
            add_filter('wpaccess_restrict_limit', array($this, 'extendAccess'));
        }
        
        add_filter(
            'aam_post_access_option', array($this, 'postAccessOption'), 10, 2
        );
        add_filter(
            'aam_term_access_option', array($this, 'termAccessOption'), 10, 2
        );

        if (aam_Core_ConfigPress::getParam('aam.page_category', 'true') == 'true') {
            register_taxonomy('page_category', 'page', array(
                'hierarchical' => TRUE,
                'rewrite' => TRUE,
                'public' => TRUE,
                'show_ui' => TRUE,
                'show_in_nav_menus' => TRUE,
            ));
        }
        //add post filter for LIST restriction
        add_filter('the_posts', array($this, 'thePosts'), 999, 2);
    }

    /**
     * Activation hook
     */
    public function activate() {
        if (aam_Core_API::getBlogOption('aam_plus_package', 0, 1) < self::VERSION) {
            $roles = new WP_Roles;
            $administrator = $roles->get_role('administrator');
            if ($administrator) {
                $administrator->add_cap('delete_comment', true);
                $administrator->add_cap('approve_comment', true);
                $administrator->add_cap('edit_comment', true);
                $administrator->add_cap('moderate_comments', true);
                $administrator->add_cap('quick_edit_comment', true);
                $administrator->add_cap('spam_comment', true);
                $administrator->add_cap('reply_comment', true);
                $administrator->add_cap('trash_comment', true);
                $administrator->add_cap('unapprove_comment', true);
                $administrator->add_cap('untrash_comment', true);
                $administrator->add_cap('unspam_comment', true);
            }
            aam_Core_API::updateBlogOption('aam_plus_package', self::VERSION, 1);
        }
    }

    /**
     * Control Post Type registration
     * 
     * @param string $post_type
     * @param array  $args
     * 
     * @return void
     * 
     * @access public
     */
    public function registredPostType($post_type, $args) {
        //check if current user has any default actions
        if ($this->getUser()) {
            $this->setSubject($this->getUser());
            $access = aam_Core_API::getBlogOption(
                            $this->getOptionName($post_type), null
            );
            //TODO - $this->getUser()->roles for multisite
            //I have to figure out how to deal with empty WP_User object when admin
            //does not belong to current site in multisite network
            if (is_null($access) && $this->getUser()->roles) {
                $roles = $this->getUser()->roles;
                $role = array_shift($roles);
                $access = aam_Core_API::getBlogOption(
                                $this->getOptionName(
                                        $post_type, aam_Control_Subject_Role::UID, $role
                                ), null
                );
            }

            if (!empty($access['post']['backend'][self::ACTION_ADD]) && intval($access['post']['backend'][self::ACTION_ADD])) {
                $object = get_post_type_object($post_type);
                $object->cap->create_posts = uniqid('aam_');
            }
        }
    }

    /**
     * Extend Access limit
     * 
     * @return boolean
     * 
     * @access public
     */
    public function extendAccess() {
        return -1;
    }

    /**
     * Add additional capability group
     * 
     * @param array $groups
     * 
     * @return array
     * 
     * @access public
     */
    public function capabilityGroups($groups) {
        //last is allways Miscellaneous
        $modified = array_slice($groups, 0, count($groups) - 1);
        $modified[] = __('Comments', 'aam');
        $modified[] = end($groups);

        return $modified;
    }

    /**
     * Check capability group
     * 
     * @param string $group
     * @param string $capability
     * 
     * @return string
     * 
     * @access public
     */
    public function capabilityGroup($group, $capability) {
        return (in_array($capability, $this->_comment) ? 'Comments' : $group);
    }

    /**
     * Filter Comment Inline actions
     * 
     * @param array $actions
     * @param int   $comment
     * 
     * @return array
     * 
     * @access public
     */
    public function commentRowActions($actions, $comment) {
        $capability = $this->getParent()->getUser()->getObject(
                aam_Control_Object_Capability::UID
        );

        if (isset($actions['approve']) && !$capability->has('approve_comment')) {
            unset($actions['approve']);
        }
        if (isset($actions['unapprove']) && !$capability->has('unapprove_comment')) {
            unset($actions['unapprove']);
        }
        if (isset($actions['reply']) && !$capability->has('reply_comment')) {
            unset($actions['reply']);
        }
        if (isset($actions['quickedit']) && !$capability->has('edit_comment')) {
            unset($actions['quickedit']);
        }
        if (isset($actions['edit']) && !$capability->has('edit_comment')) {
            unset($actions['edit']);
        }
        if (isset($actions['spam']) && !$capability->has('spam_comment')) {
            unset($actions['spam']);
        }
        if (isset($actions['unspam']) && !$capability->has('unspam_comment')) {
            unset($actions['unspam']);
        }
        if (isset($actions['trash']) && !$capability->has('trash_comment')) {
            unset($actions['trash']);
        }
        if (isset($actions['delete']) && !$capability->has('delete_comment')) {
            unset($actions['delete']);
        }

        return $actions;
    }

    /**
     * Print necessary scripts
     *
     * @return void
     *
     * @access public
     */
    public function printScripts() {
        if ($this->getParent()->isAAMScreen()) {
            wp_enqueue_script(
                    'aam-plus-admin', AAM_PLUS_BASE_URL . '/plus.js', array('aam-admin')
            );
        }
    }

    /**
     * Get additional UI html
     * 
     * @return void
     * 
     * @access public
     */
    public function postFeatureRender() {
        include(dirname(__FILE__) . '/ui.phtml');
    }

    /**
     * Handle Ajax calls
     * 
     * @param mixed $default
     * @param aam_Control_Subject $subject
     * 
     * @return string
     * 
     * @access public
     */
    public function ajax($default, aam_Control_Subject $subject = null) {
        $this->setSubject($subject);

        switch (aam_Core_Request::post('sub_action')) {
            case 'get_default_access':
                $response = $this->getDefaultAccess();
                break;

            case 'set_default_access':
                $response = $this->setDefaultAccess();
                break;

            case 'clear_default_access':
                $response = $this->clearDefaultAccess();
                break;

            default:
                $response = $default;
                break;
        }

        return $response;
    }

    /**
     * Get Default Access settings
     * 
     * @return array
     * 
     * @access public
     */
    public function getDefaultAccess() {
        return json_encode(
                aam_Core_API::getBlogOption(
                        $this->getOptionName(aam_Core_Request::post('id')), array()
                )
        );
    }

    /**
     * 
     * @param type $area
     * @return type
     */
    public function getTermAccessList($area) {
        $list = $this->getUser()->getObject(aam_Control_Object_Term::UID)
                ->getAccessList($area);

        //add additional actions if necessary
        return $list;
    }

    /**
     * 
     * @param type $area
     * @return type
     */
    public function coreSettings($response, $setting) {
        switch ($setting) {
            case 'post_backend_restrictions':
                $response[] = self::ACTION_LIST;
                break;

            case 'post_frontend_restrictions':
                $response[] = self::ACTION_LIST;
                break;
        }

        return $response;
    }

    /**
     * Set Default Access settings
     * 
     * @return string
     * 
     * @access public
     */
    public function setDefaultAccess() {
        aam_Core_API::updateBlogOption(
                $this->getOptionName(aam_Core_Request::post('id')), aam_Core_Request::post('access')
        );

        return json_encode(array('status' => 'success'));
    }

    /**
     * Clear Default Access settings
     * 
     * @return string
     * 
     * @access public
     */
    public function clearDefaultAccess() {
        aam_Core_API::deleteBlogOption(
                $this->getOptionName(aam_Core_Request::post('id'))
        );
        return json_encode(array('status' => 'success'));
    }

    /**
     * Get default Post Access
     * 
     * @param array                   $access
     * @param aam_Control_Object_Post $object
     * 
     * @return array
     * 
     * @access public
     */
    public function postAccessOption($access, $object) {
        $this->setSubject($object->getSubject());

        if (empty($access)) {
            $access = aam_Core_API::getBlogOption(
                            $this->getOptionName($object->getPost()->post_type), array()
            );
            if (!empty($access)) {
                $object->setInherited(true);
            }
        }

        return $access;
    }

    /**
     * Get default Term Access
     * 
     * @param array                   $access
     * @param aam_Control_Object_Term $object
     * 
     * @return array
     * 
     * @access public
     */
    public function termAccessOption($access, $object) {
        global $wp_post_types;

        $this->setSubject($object->getSubject());

        //find the object type
        $post_type = null;
        foreach ($wp_post_types as $type => $dump) {
            if (is_object_in_taxonomy($type, $object->getTerm()->taxonomy)) {
                $post_type = $type;
                break;
            }
        }

        if (empty($access)) {
            $access = aam_Core_API::getBlogOption(
                            $this->getOptionName($post_type), array()
            );
        }

        return $access;
    }

    /**
     * Get DB option name
     * 
     * The name is used to store the value into [wp_]options table
     * 
     * @param string $post_type
     * @param string $subject
     * @param string $subject_id
     * 
     * @return string
     * 
     * @access public
     */
    public function getOptionName($post_type, $subject = null, $subject_id = null) {
        if (is_null($subject)) {
            $subject = $this->getSubject()->getUID();
            $subject_id = $this->getSubject()->getId();
        }

        return "aam_{$post_type}_default_{$subject}_{$subject_id}";
    }

    /**
     * 
     * @param type $posts
     * @param type $query
     * @return type
     */
    public function thePosts($posts, $query) {
        $filtered = array();
        $area = (is_admin() ? 'backend' : 'frontend');

        foreach ($posts as $post) {
            $object = $this->getParent()->getUser()->getObject(
                    aam_Control_Object_Post::UID, $post->ID
            );
            if ($object->has($area, self::ACTION_LIST) === false) {
                $filtered[] = $post;
            }
        }

        return $filtered;
    }

    /**
     * Set subject
     * 
     * @param aam_Control_Subject $subject
     * 
     * @return void
     * 
     * @access public
     */
    public function setSubject($subject) {
        $this->_subject = $subject;
    }

    /**
     * Get Subject
     * 
     * @return aam_Control_Subject
     * 
     * @access public
     */
    public function getSubject() {
        return $this->_subject;
    }

}
