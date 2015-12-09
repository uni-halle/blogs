<?php

/**
 * ======================================================================
 * LICENSE: This file is subject to the terms and conditions defined in *
 * file 'license.txt', which is part of this source code package.       *
 * ======================================================================
 */

/**
 * Backend manager
 * 
 * @package AAM
 * @author Vasyl Martyniuk <vasyl@vasyltech.com>
 */
class AAM_Backend_Filter {

    /**
     * Instance of itself
     * 
     * @var AAM_Backend_Filter
     * 
     * @access private 
     */
    private static $_instance = null;

    /**
     * Initialize backend filters
     * 
     * @return void
     * 
     * @access protected
     */
    protected function __construct() {
        //menu filter
        add_filter('parent_file', array($this, 'filterMenu'), 999, 1);
        
        //manager WordPress metaboxes
        add_action("in_admin_header", array($this, 'metaboxes'), 999);
        
        //post restrictions
        add_filter('page_row_actions', array($this, 'postRowActions'), 10, 2);
        add_filter('post_row_actions', array($this, 'postRowActions'), 10, 2);
        add_action('admin_action_edit', array($this, 'adminActionEdit'));
        
        //control permalink editing
        add_filter('get_sample_permalink_html', array($this, 'permalinkHTML'));
        
        //wp die hook
        add_filter('wp_die_handler', array($this, 'backendDie'));
        
        //add post filter for LIST restriction
        add_filter('the_posts', array($this, 'thePosts'), 999, 2);
        
        //some additional filter for user capabilities
        add_filter('user_has_cap', array($this, 'checkUserCap'), 999, 4);
    }

    /**
     * Filter the Admin Menu
     *
     * @param string $parent_file
     *
     * @return string
     *
     * @access public
     */
    public function filterMenu($parent_file) {
        //filter admin menu
        AAM::getUser()->getObject('menu')->filter();

        return $parent_file;
    }

    /**
     * Hanlde Metabox initialization process
     *
     * @return void
     *
     * @access public
     */
    public function metaboxes() {
        global $post;

        //make sure that nobody is playing with screen options
        if ($post instanceof WP_Post) {
            $screen = $post->post_type;
        } elseif ($screen_object = get_current_screen()) {
            $screen = $screen_object->id;
        } else {
            $screen = '';
        }

        if (AAM_Core_Request::get('init') == 'metabox') {
            $model = new AAM_Backend_Metabox;
            $model->initialize($screen);
        } else {
            AAM::getUser()->getObject('metabox')->filterBackend($screen);
        }
    }

    /**
     * Post Quick Menu Actions Filtering
     *
     * @param array $actions
     * @param WP_Post $post
     *
     * @return array
     *
     * @access public
     */
    public function postRowActions($actions, $post) {
        $object = AAM::getUser()->getObject('post', $post->ID);
        
        //filter edit menu
        if ($object->has('backend.edit')) {
            if (isset($actions['edit'])) { 
                unset($actions['edit']); 
            }
            if (isset($actions['inline hide-if-no-js'])) {
                unset($actions['inline hide-if-no-js']);
            }
        }

        //filter delete menu
        if ($object->has('backend.delete')) {
            if (isset($actions['trash'])) {
                unset($actions['trash']);
            }
            if (isset($actions['delete'])) {
                unset($actions['delete']);
            }
        }

        return $actions;
    }

    /**
     * Control Edit Post
     *
     * Make sure that current user does not have access to edit Post
     *
     * @return void
     *
     * @access public
     */
    public function adminActionEdit() {
        global $post;
        
        if (is_a($post, 'WP_Post')) {
            $user = AAM::getUser();
            if ($user->getObject('post', $post->ID)->has('backend.edit')) {
                AAM_Core_API::reject();
            }
        }
    }

    /**
     * Get Post ID
     *
     * Replication of the same mechanism that is in wp-admin/post.php
     *
     * @return WP_Post|null
     *
     * @access public
     */
    public function getPost() {
        if (get_post()) {
            $post = get_post();
        } elseif ($post_id = AAM_Core_Request::get('post')) {
            $post = get_post($post_id);
        } elseif ($post_id = AAM_Core_Request::get('post_ID')) {
            $post = get_post($post_id);
        } else {
            $post = null;
        }

        return $post;
    }

    /**
     * Take control over wp_die function
     *
     * @param callback $function
     *
     * @return void
     *
     * @access public
     */
    public function backendDie($function) {
        $redirect = AAM_Core_ConfigPress::get('backend.access.deny.redirect');
        $message = AAM_Core_ConfigPress::get(
                    'backend.access.deny.message', __('Access Denied', 'aam')
        );

        if (filter_var($redirect, FILTER_VALIDATE_URL)) {
            wp_redirect($redirect);
            exit;
        } elseif (is_int($redirect)) {
            wp_redirect(get_post_permalink($redirect));
            exit;
        } else {
            call_user_func($function, $message, '', array());
        }
    }

    /**
     * Control edit permalink feature
     * 
     * @param string $html
     * 
     * @return string
     */
    public function permalinkHTML($html) {
        if (AAM_Core_ConfigPress::get('aam.control_permalink') === 'true') {
            if (AAM::getUser()->hasCapability('manage_permalink') === false) {
                $html = '';
            }
        }

        return $html;
    }
    
    /**
     * Filter posts from the list
     *  
     * @param array $posts
     * 
     * @return array
     * 
     * @access public
     */
    public function thePosts($posts) {
        $filtered = array();
        
        if (AAM::isAAM()) { //skip post filtering if this is AAM page
            $filtered = $posts;
        } else {
            foreach ($posts as $post) {
                $object = AAM::getUser()->getObject('post', $post->ID);
                if (!$object->has('backend.list')) {
                    $filtered[] = $post;
                }
            }
        }

        return $filtered;
    }
    
    /**
     * Check user capability
     * 
     * This is a hack function that add additional layout on top of WordPress
     * core functionality. Based on the capability passed in the $args array as
     * "0" element, it performs additional check on user's capability to manage
     * post.
     * 
     * @param array $allCaps
     * @param array $metaCaps
     * @param array $args
     * 
     * @return array
     * 
     * @access public
     */
    public function checkUserCap($allCaps, $metaCaps, $args) {
        switch($args[0]) {
            case 'edit_post':
                $object = AAM::getUser()->getObject('post', $args[2]);
                if ($object->has('backend.edit')) {
                    $allCaps = $this->restrictPostActions($allCaps, $metaCaps);
                }
                break;
            
            case 'delete_post' :
                $object = AAM::getUser()->getObject('post', $args[2]);
                if ($object->has('backend.delete')) {
                    $allCaps = $this->restrictPostActions($allCaps, $metaCaps);
                }
                break;
        }
        
        return $allCaps;
    }
    
    /**
     * Restrict user capabilities
     * 
     * Iterate through the list of meta capabilities and disable them in the
     * list of all user capabilities. Keep in mind that this disable caps only
     * for one time call.
     * 
     * @param array $allCaps
     * @param array $metaCaps
     * 
     * @return array
     * 
     * @access protected
     */
    protected function restrictPostActions($allCaps, $metaCaps) {
        foreach($metaCaps as $cap) {
            $allCaps[$cap] = false;
        }
        
        return $allCaps;
    }

    /**
     * Register backend filters and actions
     * 
     * @return void
     * 
     * @access public
     */
    public static function register() {
        if (is_null(self::$_instance)) {
            self::$_instance = new self;
        }
    }

}