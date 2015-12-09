<?php

/**
 * ======================================================================
 * LICENSE: This file is subject to the terms and conditions defined in *
 * file 'license.txt', which is part of this source code package.       *
 * ======================================================================
 */

/**
 * Backend posts & pages manager
 * 
 * @package AAM
 * @author Vasyl Martyniuk <vasyl@vasyltech.com>
 */
class AAM_Backend_Post {

    /**
     * Get HTML content
     * 
     * @return string
     * 
     * @access public
     */
    public function getContent() {
        ob_start();
        require_once(dirname(__FILE__) . '/view/object/post.phtml');
        $content = ob_get_contents();
        ob_end_clean();

        return $content;
    }

    /**
     * Get list for the table
     * 
     * @return string
     * 
     * @access public
     */
    public function getTable() {
        $type = trim(AAM_Core_Request::request('type'));

        if (empty($type)) {
            $response = $this->retrieveTypeList();
        } else {
            $response = $this->retrieveTypeContent($type);
        }

        return $this->wrapTable($response);
    }

    /**
     * Get breadcrumb for a post or term
     * 
     * @return string
     * 
     * @access public
     */
    public function getBreadcrumb() {
        $type = AAM_Core_Request::post('type');

        if ($type == 'term') {
            $breadcrub = $this->renderTermBreadcrumb();
        } else {
            $breadcrub = $this->renderPostBreadcrumb();
        }

        return json_encode(array(
                'status' => 'success',
                'breadcrumb' => ($breadcrub ? $breadcrub : __('Base Level', AAM_KEY))
        ));
    }

    /**
     * Render term breadcrumb
     * 
     * @return string
     * 
     * @access protected
     */
    protected function renderTermBreadcrumb() {
        list($term, $taxonomy) = explode('|', AAM_Core_Request::post('id'));
        $ancestors = array_reverse(get_ancestors($term, $taxonomy, 'taxonomy'));
        
        $breadcrumb = array();
        foreach ($ancestors as $id) {
            $breadcrumb[] = get_term($id, $taxonomy)->name;
        }

        return implode(' &Gt; ', $breadcrumb);
    }

    /**
     * Render post breadcrumb
     * 
     * @return string
     * 
     * @access protected
     */
    protected function renderPostBreadcrumb() {
        $post = get_post(AAM_Core_Request::post('id'));
        $terms = wp_get_object_terms(
                $post->ID, get_object_taxonomies($post)
        );
        $breadcrumb = array();
        foreach ($terms as $term) {
            if (is_taxonomy_hierarchical($term->taxonomy)) {
                $breadcrumb[] = $term->name;
            }
        }

        return implode('; ', $breadcrumb);
    }

    /**
     * Retrieve list of registered post types
     * 
     * @return array
     * 
     * @access protected
     */
    protected function retrieveTypeList() {
        $response = array('data' => array());

        foreach (get_post_types(array(), 'objects') as $type) {
            if ($type->public) {
                $response['data'][] = array(
                    $type->name,
                    null,
                    'type',
                    $type->labels->name,
                    'manage'
                );
            }
        }

        return $response;
    }

    /**
     * Get post type children
     * 
     * Retrieve list of all posts and terms that belong to specified post type
     * 
     * @param string $type
     * 
     * @return array
     * 
     * @access protected
     */
    protected function retrieveTypeContent($type) {
        $list = array();

        //first retrieve all hierarchical terms that belong to Post Type
        foreach (get_object_taxonomies($type, 'objects') as $tax) {
            if (is_taxonomy_hierarchical($tax->name)) {
                //get all terms that have no parent category
                $list = array_merge($list, $this->retrieveTermList($tax->name));
            }
        }

        //retrieve all posts
        $posts = get_posts(array(
            'post_type' => $type, 'category' => 0, 
            'numberposts' => -1, 'post_status' => 'any'
        ));

        foreach ($posts as $post) {
            $list[] = array(
                $post->ID,
                get_edit_post_link($post->ID, 'link'),
                'post',
                $post->post_title,
                'manage,edit'
            );
        }

        return array('data' => $list);
    }

    /**
     * Retrieve term list
     * 
     * @param string $taxonomy
     * 
     * @return array
     * 
     * @access protected
     */
    protected function retrieveTermList($taxonomy) {
        $response = array();

        $terms = get_terms($taxonomy, array(
            'hide_empty' => false
        ));

        foreach ($terms as $term) {
            $response[] = array(
                $term->term_id . '|' . $taxonomy,
                get_edit_term_link($term->term_id, $taxonomy),
                'term',
                $term->name,
                'manage,edit'
            );
        }

        return $response;
    }

    /**
     * Prepare response
     * 
     * @param array $response
     * 
     * @return string
     * 
     * @access protected
     */
    protected function wrapTable($response) {
        $response['draw'] = AAM_Core_Request::request('draw');

        return json_encode($response);
    }

    /**
     * Get Post or Term access
     *
     * @return string
     *
     * @access public
     */
    public function getAccess() {
        $type = AAM_Core_Request::post('type');
        $id = AAM_Core_Request::post('id');

        $object = AAM_Backend_View::getSubject()->getObject($type, $id);

        //prepare the response object
        if ($object instanceof AAM_Core_Object) {
            $access   = $object->getOption();
            $metadata = array(
                'inherited'   => $object->getInherited(),
                'overwritten' => $object->isOverwritten()
            );
        } else {
            $access = $metadata = array();
        }

        return json_encode(array(
            'access' => $access,
            'meta'   => $metadata
        ));
    }
    
    /**
     * Save post properties
     * 
     * @return string
     * 
     * @access public
     */
    public function save() {
        if ($this->checkLimit()) {
            $object = AAM_Core_Request::post('object');
            $objectId = AAM_Core_Request::post('objectId', 0);

            $param = AAM_Core_Request::post('param');
            $value = filter_var(
                    AAM_Core_Request::post('value'), FILTER_VALIDATE_BOOLEAN
            );
            
            //clear cache
            do_action('aam-clear-cache-action', AAM_Backend_View::getSubject());

            $result = AAM_Backend_View::getSubject()->save(
                    $param, $value, $object, $objectId
            );
        } else {
            $result = false;
            $error  = __('You reached your limitation.', AAM_KEY);
        }

        return json_encode(array(
                    'status' => ($result ? 'success' : 'failure'),
                    'error' => (empty($error) ? '' : $error)
        ));
    }
    
    /**
     * Reset the object settings
     * 
     * @return string
     * 
     * @access public
     */
    public function reset() {
        $type = AAM_Core_Request::post('type');
        $id = AAM_Core_Request::post('id', 0);

        $object = AAM_Backend_View::getSubject()->getObject($type, $id);
        if ($object instanceof AAM_Core_Object) {
            $result = $object->reset();
            //clear cache
            do_action('aam-clear-cache-action', AAM_Backend_View::getSubject());
        } else {
            $result = false;
        }
        
        return json_encode(
                array('status' => ($result ? 'success' : 'failure'))
        );
    }

    /**
     * 
     * @global type $wpdb
     * @return type
     */
    protected function checkLimit() {
        global $wpdb;
        
        $limit = apply_filters('aam-post-limit', 0);
        
        if ($limit != -1) {
            //count number of posts that have access saved
            $query = "SELECT COUNT(*) as `total` FROM {$wpdb->postmeta} "
                   . "WHERE meta_key LIKE %s";
            
            $row = $wpdb->get_row($wpdb->prepare($query, 'aam_post_access_%'));
            $limit = ($row->total < 10 ? -1 : 0);
        }
        
        return ($limit == -1);
    }

    /**
     * Register Posts & Pages feature
     * 
     * @return void
     * 
     * @access public
     */
    public static function register() {
        AAM_Backend_Feature::registerFeature((object) array(
            'uid' => 'post',
            'position' => 20,
            'title' => __('Posts & Pages', AAM_KEY),
            'subjects' => array(
                'AAM_Core_Subject_Role',
                'AAM_Core_Subject_User',
                'AAM_Core_Subject_Visitor'
            ),
            'view' => __CLASS__
        ));
    }

}