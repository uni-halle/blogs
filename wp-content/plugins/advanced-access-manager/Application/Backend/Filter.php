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
     * pre_get_posts flag
     */
    protected $skip = false;

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
        
        //control admin area
        add_action('admin_init', array($this, 'adminInit'));
        
        //post restrictions
        add_filter('page_row_actions', array($this, 'postRowActions'), 10, 2);
        add_filter('post_row_actions', array($this, 'postRowActions'), 10, 2);
        add_action('admin_action_edit', array($this, 'adminActionEdit'));
        
        //wp die hook
        add_filter('wp_die_handler', array($this, 'backendDie'));
        
        //add post filter for LIST restriction
        add_filter('the_posts', array($this, 'thePosts'), 999, 2);
        if (AAM_Core_Config::get('large-post-number', false)) {
            add_action('pre_get_posts', array($this, 'preparePostQuery'));
        }
        
        //some additional filter for user capabilities
        add_filter('user_has_cap', array($this, 'checkUserCap'), 999, 4);
        
        //screen options & contextual help hooks
        add_filter('screen_options_show_screen', array($this, 'screenOptions'));
        add_filter('contextual_help', array($this, 'helpOptions'), 10, 3);
    }
    
    /**
     * Control Admin Area access
     *
     * @return void
     *
     * @access public
     * @since  3.3
     */
    public function adminInit() {
        global $plugin_page;

        //compile menu
        if (empty($plugin_page)){
            $menu     = basename(AAM_Core_Request::server('SCRIPT_NAME'));
            
            $taxonomy = AAM_Core_Request::get('taxonomy');
            $postType = AAM_Core_Request::get('post_type');
            $page     = AAM_Core_Request::get('page');
            
            if (!empty($taxonomy)) {
                $menu .= '?taxonomy=' . $taxonomy;
            } elseif (!empty($postType)) {
                $menu .= '?post_type=' . $postType;
            } elseif (!empty($page)) {
                $menu .= '?page=' . $page;
            }
        } else {
            $menu = $plugin_page;
        }
        
        $object = AAM::getUser()->getObject('menu');

        if ($object->has($menu)) {
            AAM_Core_API::reject('backend', array('object' => $object, 'id' => $menu));
        }
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
     * Handle metabox initialization process
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

        if (AAM_Core_Request::get('init') != 'metabox') {
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
        
        $edit   = $object->has('backend.edit');
        $others = $object->has('backend.edit_others');
        
        //filter edit menu
        if ($edit || ($others && !$this->isAuthor($post))) {
            if (isset($actions['edit'])) { 
                unset($actions['edit']); 
            }
            if (isset($actions['inline hide-if-no-js'])) {
                unset($actions['inline hide-if-no-js']);
            }
        }
        
        $delete = $object->has('backend.delete');
        $others = $object->has('backend.delete_others');

        //filter delete menu
        if ($delete || ($others && !$this->isAuthor($post))) {
            if (isset($actions['trash'])) {
                unset($actions['trash']);
            }
            if (isset($actions['delete'])) {
                unset($actions['delete']);
            }
        }
        
        $publish = $object->has('backend.publish');
        $others  = $object->has('backend.publish_others');
        
        //filter edit menu
        if ($publish || ($others && !$this->isAuthor($post))) {
            if (isset($actions['inline hide-if-no-js'])) {
                unset($actions['inline hide-if-no-js']);
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
            $object = AAM::getUser()->getObject('post', $post->ID);
            $edit   = $object->has('backend.edit');
            $others = $object->has('backend.edit_others');
            if ($edit || ($others && !$this->isAuthor($post))) {
                AAM_Core_API::reject(
                        'backend', 
                        array('object' => $object, 'action' => 'backend.edit')
                );
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
        $post = null;
        
        if (get_post()) {
            $post = get_post();
        } elseif ($post_id = AAM_Core_Request::get('post')) {
            $post = get_post($post_id);
        } elseif ($post_id = AAM_Core_Request::get('post_ID')) {
            $post = get_post($post_id);
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
        if (AAM_Core_Config::get('access-denied-handler', true)) {
            AAM_Core_API::reject(
                'backend', array('callback' => $function, 'skip-die' => true)
            );
        }
        
        return $function;
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
                $list   = $object->has('backend.list');
                $others = $object->has('backend.list_others');
                
                if (!$list && (!$others || $this->isAuthor($post))) {
                    $filtered[] = $post;
                }
            }
        }

        return $filtered;
    }
    
    /**
     * 
     * @param type $query
     */
    public function preparePostQuery($query) {
        if ($this->skip === false) {
            $filtered = array();
            
            foreach ($this->fetchPosts($query) as $id) {
                if (AAM::getUser()->getObject('post', $id)->has('backend.list')) {
                    $filtered[] = $id;
                }
            }
            
            if (isset($query->query_vars['post__not_in'])) {
                $query->query_vars['post__not_in'] = array_merge(
                        $query->query_vars['post__not_in'], $filtered
                );
            } else {
                $query->query_vars['post__not_in'] = $filtered;
            }
        }
    }

    /**
     * 
     * @param type $query
     * @return type
     */
    protected function fetchPosts($query) {
        $this->skip = true;
        
        if (!empty($query->query['post_type'])) {
            $postType = $query->query['post_type'];
        } elseif (!empty($query->query_vars['post_type'])) {
            $postType = $query->query_vars['post_type'];
        } else {
            $postType = 'post';
        }
        
        $posts = get_posts(array(
            'post_type'   => (is_string($postType) ? $postType : 'post'),
            'numberposts' => -1,
            'fields'      => 'ids',
            'post_status' => 'any'
        ));
                    
        $this->skip = false;
        
        return $posts;
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
        global $post;
        
        //make sure that $args[2] is actually post ID
        if (isset($args[2]) && is_scalar($args[2])) { 
            switch($args[0]) {
                case 'edit_post':
                    $object = AAM::getUser()->getObject('post', $args[2]);
                    $edit   = $object->has('backend.edit');
                    $others = $object->has('backend.edit_others');
                    if ($edit || ($others && !$this->isAuthor($object->getPost()))) {
                        $allCaps = $this->restrictPostActions($allCaps, $metaCaps);
                    }
                    break;

                case 'delete_post' :
                    $object = AAM::getUser()->getObject('post', $args[2]);
                    $delete = $object->has('backend.delete');
                    $others = $object->has('backend.delete_others');
                    if ($delete || ($others && !$this->isAuthor($object->getPost()))) {
                        $allCaps = $this->restrictPostActions($allCaps, $metaCaps);
                    }
                    break;
                
                default:
                    break;
            }
        } elseif (is_a($post, 'WP_Post')) {
            switch ($args[0]) {
                case 'publish_posts':
                case 'publish_pages':
                    $object = AAM::getUser()->getObject('post', $post->ID);
                    $publish = $object->has('backend.publish');
                    $others  = $object->has('backend.publish_others');
                    if ($publish || ($others && !$this->isAuthor($post))) {
                        $allCaps = $this->restrictPostActions($allCaps, $metaCaps);
                    }
                    break;
                
                default:
                    break;
            }
        }
        
        return $allCaps;
    }
    
    /**
     * 
     * @param type $flag
     * @return type
     */
    public function screenOptions($flag) {
        //IMPORTANT!! Do not use AAM::getUser()->hasCapability because 
        //show_screen_options is custom capability and it may not be present for new
        //website
        $caps = AAM_Core_API::getAllCapabilities();
        
        if (isset($caps['show_screen_options'])) {
            $flag = AAM::getUser()->hasCapability('show_screen_options');
        }
        
        return $flag;
    }
    
    /**
     * 
     * @param array $help
     * @param type $id
     * @param type $screen
     * @return array
     */
    public function helpOptions($help, $id, $screen) {
        //IMPORTANT!! Do not use AAM::getUser()->hasCapability because 
        //show_screen_options is custom capability and it may not be present for new
        //website
        $caps = AAM_Core_API::getAllCapabilities();
        
        if (isset($caps['show_help_tabs'])) {
            if (!AAM::getUser()->hasCapability('show_help_tabs')) {
                $screen->remove_help_tabs();
                $help = array();
            }
        }
        
        return $help;
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
     * Check if user is post author
     * 
     * @param WP_Post $post
     * 
     * @return boolean
     * 
     * @access protected
     */
    protected function isAuthor($post) {
        return ($post->post_author == get_current_user_id());
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