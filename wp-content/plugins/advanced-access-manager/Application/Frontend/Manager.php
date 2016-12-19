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
     * pre_get_posts flag
     */
    protected $skip = false;

    /**
     * Construct the manager
     * 
     * @return void
     * 
     * @access public
     */
    public function __construct() {
        if (AAM_Core_Config::get('frontend-access-control', true)) {
            //login hook
            add_action('wp_login', array($this, 'login'), 10, 2);
        
            //control WordPress frontend
            add_action('wp', array($this, 'wp'), 999);
            add_action('404_template', array($this, 'themeRedirect'), 999);
            //filter navigation pages & taxonomies
            add_filter('get_pages', array($this, 'thePosts'));
            add_filter('wp_get_nav_menu_items', array($this, 'getNavigationMenu'));
            //widget filters
            add_filter('sidebars_widgets', array($this, 'widgetFilter'), 999);
            //get control over commenting stuff
            add_filter('comments_open', array($this, 'commentOpen'), 10, 2);
            //user login control
            add_filter('wp_authenticate_user', array($this, 'authenticate'), 1, 2);
            //add post filter for LIST restriction
            add_filter('the_posts', array($this, 'thePosts'), 999, 2);
            if (AAM_Core_Config::get('large-post-number', false)) {
                add_action('pre_get_posts', array($this, 'preparePostQuery'));
            }
            //filter post content
            add_filter('the_content', array($this, 'theContent'), 999);
            //admin bar
            $this->checkAdminBar();
        }
    }
    
    /**
     * 
     * @param type $login
     * @param type $user
     */
    public function login($login, $user = null) {
        if (is_a($user, 'WP_User')) {
            AAM_Core_API::deleteOption('aam-user-switch-' . $user->ID); 
        }
    }

    /**
     * Main frontend access control hook
     *
     * @return void
     *
     * @access public
     * @global WP_Post $post
     */
    public function wp() {
        $post = $this->getCurrentPost();
        
        if (is_a($post, 'WP_Post')) {
            $this->checkPostReadAccess($post);
        }
    }
    
    /**
     * Theme redirect
     * 
     * Super important function that cover the 404 redirect that triggered by theme
     * when page is not found. This covers the scenario when page is restricted from
     * listing and read.
     * 
     * @global type $wp_query
     * 
     * @param type $template
     * 
     * @return string
     * 
     * @access public
     */
    public function themeRedirect($template) {
        global $wp_query;
        
        $object = (isset($wp_query->queried_object) ? $wp_query->queried_object : 0);
        if ($object && is_a($object, 'WP_Post')) {
            $this->checkPostReadAccess($object);
        }
        
        return $template;
    }
    
    /**
     * 
     * @global type $wp_query
     * @return type
     */
    protected function getCurrentPost() {
        global $wp_query;
        
        $current = null;
        
        if (!empty($wp_query->queried_object)) {
            $current = $wp_query->queried_object;
        } elseif (!empty($wp_query->post)) {
            $current = $wp_query->post;
        } elseif (!empty($wp_query->query['name']) && !empty($wp_query->posts)) {
            //Important! Cover the scenario of NOT LIST but ALLOW READ
            foreach($wp_query->posts as $post) {
                if ($post->post_name == $wp_query->query['name']) {
                    $current = $post;
                    break;
                }
            }
        }
        
        return (is_a($current, 'WP_Post') ? $current : null);
    }
    
    /**
     * Check post read access
     * 
     * @param WP_Post $post
     * 
     * @return void
     * 
     * @access protected
     */
    protected function checkPostReadAccess($post) {
        $object = AAM::getUser()->getObject('post', $post->ID);
        $read   = $object->has('frontend.read');
        $others = $object->has('frontend.read_others');

        if ($read || ($others && !$this->isAuthor($post))) {
            AAM_Core_API::reject(
                'frontend', 
                array('object' => $object, 'action' => 'frontend.read')
            );
        }
        //trigger any action that is listeting 
        do_action('aam-wp-action', $object);
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
        $current = $this->getCurrentPost();
        
        if (is_array($posts)) {
            foreach ($posts as $i => $post) {
                if ($current && ($current->ID == $post->ID)) { continue; } 
                
                $object = AAM::getUser()->getObject('post', $post->ID);
                $list   = $object->has('frontend.list');
                $others = $object->has('frontend.list_others');
                
                if ($list || ($others && !$this->isAuthor($post))) {
                    unset($posts[$i]);
                }
            }
        }

        return $posts;
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
                if ($page->type == 'post_type') {
                    $object = $user->getObject('post', $page->object_id);
                    $list   = $object->has('frontend.list');
                    $others = $object->has('frontend.list_others');
                    
                    if ($list || ($others && !$this->isAuthor($page))) {
                        unset($pages[$i]);
                    }
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
     * Check admin bar
     * 
     * Make sure that current user can see admin bar
     * 
     * @return void
     * 
     * @access public
     */
    public function checkAdminBar() {
        $caps = AAM_Core_API::getAllCapabilities();
        
        if (isset($caps['show_admin_bar'])) {
            if (!AAM::getUser()->hasCapability('show_admin_bar')) {
                show_admin_bar(false);
            }
        }
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
                AAM_Backend_View_Helper::preparePhrase($message, 'strong')
            );
        }

        return $user;
    }
    
    /**
     * 
     * @param type $query
     */
    public function preparePostQuery($query) {
        if ($this->skip === false) {
            $filtered = array();

            foreach ($this->fetchPosts($query) as $id) {
                if (AAM::getUser()->getObject('post', $id)->has('frontend.list')) {
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
            'fields'      => 'ids'
        ));
                    
        $this->skip = false;
        
        return $posts;
    }
    
    /**
     * 
     * @global WP_Post $post
     * @param type $content
     * 
     * @return string
     * 
     * @access public
     */
    public function theContent($content) {
        global $post;
        
        if (is_a($post, 'WP_Post')) {
            $object = AAM::getUser()->getObject('post', $post->ID);

            if ($object->has('frontend.limit')) {
                $message = apply_filters(
                    'aam-filter-teaser-option', 
                    AAM_Core_Config::get("frontend.teaser.message"),
                    "frontend.teaser.message",
                    AAM::getUser()
                );
                $excerpt = apply_filters(
                    'aam-filter-teaser-option', 
                    AAM_Core_Config::get("frontend.teaser.excerpt"),
                    "frontend.teaser.excerpt",
                    AAM::getUser()
                );

                $html  = (intval($excerpt) ? $post->post_excerpt : '');
                $html .= stripslashes($message);
                $content = do_shortcode($html);
            }
        }
        
        return $content;
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