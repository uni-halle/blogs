<?php

/**
 * ======================================================================
 * LICENSE: This file is subject to the terms and conditions defined in *
 * file 'license.txt', which is part of this source code package.       *
 * ======================================================================
 */

/**
 * AAM frontend manager
 * 
 * @package AAM
 * @author Vasyl Martyniuk <vasyl@vasyltech.com>
 */
class AAM_Frontend_Manager {

    /**
     * Instance of itself
     * 
     * @var AAM_Frontend_Manager
     * 
     * @access private 
     */
    private static $_instance = null;

    /**
     * Construct the manager
     * 
     * @return void
     * 
     * @access public
     */
    public function __construct() {
        //control WordPress frontend
        add_action('wp', array($this, 'wp'), 999);
        //filter navigation pages & taxonomies
        add_filter('get_pages', array($this, 'getPages'));
        add_filter('wp_get_nav_menu_items', array($this, 'getNavigationMenu'));
        //widget filters
        add_filter('sidebars_widgets', array($this, 'widgetFilter'), 999);
        //get control over commenting stuff
        add_filter('comments_open', array($this, 'commentOpen'), 10, 2);
        //user login control
        add_filter('wp_authenticate_user', array($this, 'authenticate'), 1, 2);
        //add post filter for LIST restriction
        add_filter('the_posts', array($this, 'thePosts'), 999, 2);
    }

    /**
     * Main Frontend access control hook
     *
     * @return void
     *
     * @access public
     * @global WP_Post $post
     */
    public function wp() {
        global $post;

        $user = AAM::getUser();
        if (is_single() || is_page()) {
            $object = $user->getObject('post', $post->ID);
            if ($object->has('frontend.read')) {
                AAM_Core_API::reject();
            }
            //trigger any action that is listeting 
            do_action('aam-wp-action', $object);
        }
    }

    /**
     * Filter Pages that should be excluded in frontend
     *
     * @param array $pages
     *
     * @return array
     *
     * @access public
     */
    public function getPages($pages) {
        if (is_array($pages)) {
            foreach ($pages as $i => $page) {
                $object = AAM::getUser()->getObject('post', $page->ID);
                if ($object->has('frontend.list')) {
                    unset($pages[$i]);
                }
            }
        }

        return $pages;
    }

    /**
     * Filter Navigation menu
     *
     * @param array $pages
     *
     * @return array
     *
     * @access public
     */
    public function getNavigationMenu($pages) {
        if (is_array($pages)) {
            $user = AAM::getUser();
            foreach ($pages as $i => $page) {
                $filter = false;
                
                if ($page->type == 'post_type') {
                    $filter = $user->getObject('post', $page->object_id)->has(
                            'frontend.list'
                    );
                }

                if ($filter) {
                    unset($pages[$i]);
                }
            }
        }

        return $pages;
    }

    /**
     * Filter Frontend widgets
     *
     * @param array $widgets
     *
     * @return array
     *
     * @access public
     */
    public function widgetFilter($widgets) {
        return AAM::getUser()->getObject('metabox')->filterFrontend($widgets);
    }

    /**
     * Control Frontend commenting freature
     *
     * @param boolean $open
     * @param int $post_id
     *
     * @return boolean
     *
     * @access public
     */
    public function commentOpen($open, $post_id) {
        $object = AAM::getUser()->getObject('post', $post_id);
        
        if ($object->has('frontend.comment')) {
            $open = false;
        }

        return $open;
    }

    /**
     * Control User Block flag
     *
     * @param WP_Error $user
     *
     * @return WP_Error|WP_User
     *
     * @access public
     */
    public function authenticate($user) {
        if ($user->user_status == 1) {
            $user = new WP_Error();
            
            $message  = '[ERROR]: User is locked. Please contact your website ';
            $message .= 'administrator.';
            
            $user->add(
                'authentication_failed', 
                AAM_Backend_Helper::preparePhrase($message, 'strong')
            );
        }

        return $user;
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

        foreach ($posts as $post) {
            $object = AAM::getUser()->getObject('post', $post->ID);
            if (!$object->has('frontend.list')) {
                $filtered[] = $post;
            }
        }

        return $filtered;
    }

    /**
     * Bootstrap the manager
     * 
     * @return void
     * 
     * @access public
     */
    public static function bootstrap() {
        if (is_null(self::$_instance)) {
            self::$_instance = new self;
        }
    }

}