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
class AAM_Backend_Feature_Post extends AAM_Backend_Feature_Abstract {
    
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
     * Retrieve list of registered post types
     * 
     * @return array
     * 
     * @access protected
     */
    protected function retrieveTypeList() {
        $list     = $this->prepareTypeList();
        $response = array(
            'data'            => array(), 
            'recordsTotal'    => $list->total, 
            'recordsFiltered' => $list->filtered
        );
        
        foreach ($list->records as $type) {
            $response['data'][] = array(
                $type->name,
                null,
                'type',
                $type->labels->name,
                'drilldown,manage'
            );
        }
        
        return $response;
    }
    
    /**
     * 
     * @return type
     */
    protected function prepareTypeList() {
        $list     = get_post_types(array(), 'objects');
        $filtered = array();
        
        //filters
        $s      = AAM_Core_Request::post('search.value');
        $length = AAM_Core_Request::post('length');
        $start  = AAM_Core_Request::post('start');
        
        foreach (get_post_types(array(), 'objects') as $type) {
            if ($type->public 
                    && (empty($s) || stripos($type->labels->name, $s) !== false)) {
                $filtered[] = $type;
            }
        }
        
        return (object) array(
            'total'    => count($list),
            'filtered' => count($filtered),
            'records'  => array_slice($filtered, $start, $length)
        );
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
        $list     = $this->prepareContentList($type);
        $response = array(
            'data'            => array(), 
            'recordsTotal'    => $list->total, 
            'recordsFiltered' => $list->filtered
        );
        
        foreach($list->records as $record) {
            if (isset($record->ID)) { //this is post
                $response['data'][] = array(
                    $record->ID,
                    get_edit_post_link($record->ID, 'link'),
                    'post',
                    $record->post_title,
                    'manage,edit'
                );
            } else { //term
                $response['data'][] = array(
                    $record->term_id . '|' . $record->taxonomy,
                    get_edit_term_link($record->term_id, $record->taxonomy),
                    'term',
                    $record->name,
                    'manage,edit'
                );
            }
        } 


        return $response;
    }
    
    /**
     * 
     * @return type
     */
    protected function prepareContentList($type) {
        $list     = array();
        $filtered = array();
        
        //filters
        $s      = AAM_Core_Request::post('search.value');
        $length = AAM_Core_Request::post('length');
        $start  = AAM_Core_Request::post('start');
        
        //first retrieve all hierarchical terms that belong to Post Type
        foreach (get_object_taxonomies($type, 'objects') as $tax) {
            if (is_taxonomy_hierarchical($tax->name)) {
                //get all terms that have no parent category
                $list = array_merge($list, $this->retrieveTermList($tax->name));
            }
        }
        
        //retrieve all posts
        $list = array_merge(
            $list, 
            get_posts(array(
                'post_type'   => $type, 'category' => 0, 
                'numberposts' => -1, 'post_status' => 'any'
            ))
        );
        
        foreach($list as $row) {
            if (!empty($s)) {
                if (isset($row->term_id) && stripos($row->name, $s) !== false) {
                    $filtered[] = $row;
                } elseif (isset($row->ID) && stripos($row->post_title, $s) !== false) {
                    $filtered[] = $row;
                }
            } else {
                $filtered[] = $row;
            }
        }
        
        return (object) array(
            'total'    => count($list),
            'filtered' => count($filtered),
            'records'  => array_slice($filtered, $start, $length)
        );
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

        foreach (get_terms($taxonomy, array('hide_empty' => false)) as $term) {
            $term->taxonomy = $taxonomy;
            $response[] = $term;
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
        $type = trim(AAM_Core_Request::post('type'));
        $id   = AAM_Core_Request::post('id');

        $object = AAM_Backend_View::getSubject()->getObject($type, $id);

        //prepare the response object
        if ($object instanceof AAM_Core_Object) {
            $access   = $object->getOption();
            $metadata = array('overwritten' => $object->isOverwritten());
        } else {
            $access = $metadata = array();
        }

        return json_encode(array('access' => $access, 'meta' => $metadata));
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
            $subject = AAM_Backend_View::getSubject();
            
            $object = trim(AAM_Core_Request::post('object'));
            $id     = AAM_Core_Request::post('objectId', null);

            $param = AAM_Core_Request::post('param');
            $value = filter_var(
                    AAM_Core_Request::post('value'), FILTER_VALIDATE_BOOLEAN
            );
            
            //clear cache
            AAM_Core_Cache::clear();
            
            $result = $subject->save($param, $value, $object, $id);
        } else {
            $result = false;
            $error  = __('You reached your limitation.', AAM_KEY);
        }

        return json_encode(array(
                    'status' => ($result ? 'success' : 'failure'),
                    'error'  => (empty($error) ? '' : $error)
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
        $type = trim(AAM_Core_Request::post('type'));
        $id   = AAM_Core_Request::post('id', 0);

        $object = AAM_Backend_View::getSubject()->getObject($type, $id);
        if ($object instanceof AAM_Core_Object) {
            $result = $object->reset();
            //clear cache
            AAM_Core_Cache::clear();
        } else {
            $result = false;
        }
        
        return json_encode(array('status' => ($result ? 'success' : 'failure')));
    }

    /**
     * 
     * @global type $wpdb
     * @return type
     */
    public static function checkLimit() {
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
     * @inheritdoc
     */
    public static function getAccessOption() {
        return 'feature.post.capability';
    }
    
    /**
     * @inheritdoc
     */
    public static function getTemplate() {
        return 'object/post.phtml';
    }
    
    /**
     * 
     * @staticvar type $list
     * @param type $area
     * @return type
     */
    public function getAccessOptionList($area) {
        static $list = null;
        
        if (is_null($list)) {
            $list = apply_filters(
                    'aam-post-access-options-filter', 
                    require_once dirname(__FILE__) . '/../View/PostOptionList.php'
            );
        }
        
        return $list[$area];
    }
    
    /**
     * 
     * @return type
     */
    public function getCurrentPost() {
        $id = intval(AAM_Core_Request::post('oid'));
        
        if ($id) {
            $post = get_post($id);
        }
        
        return (isset($post) ? $post : null);
    }

    /**
     * Register Posts & Pages feature
     * 
     * @return void
     * 
     * @access public
     */
    public static function register() {
        $cap = AAM_Core_Config::get(self::getAccessOption(), 'administrator');
        
        AAM_Backend_Feature::registerFeature((object) array(
            'uid'        => 'post',
            'position'   => 20,
            'title'      => __('Posts & Pages', AAM_KEY),
            'capability' => $cap,
            'subjects'   => array(
                'AAM_Core_Subject_Role',
                'AAM_Core_Subject_User',
                'AAM_Core_Subject_Visitor'
            ),
            'view'       => __CLASS__
        ));
    }

}