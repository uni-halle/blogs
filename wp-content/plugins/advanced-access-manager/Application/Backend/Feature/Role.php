<?php

/**
 * ======================================================================
 * LICENSE: This file is subject to the terms and conditions defined in *
 * file 'license.txt', which is part of this source code package.       *
 * ======================================================================
 */

/**
 * Role view manager
 * 
 * @package AAM
 * @author Vasyl Martyniuk <vasyl@vasyltech.com>
 */
class AAM_Backend_Feature_Role {
    
    /**
     * Constructor
     * 
     * @return void
     * 
     * @access public
     * @throws Exception
     */
    public function __construct() {
        $cap = AAM_Core_Config::get('page.capability', 'administrator');
        if (!AAM::getUser()->hasCapability($cap)) {
            Throw new Exception(__('Access Denied', AAM_KEY));
        }
    }

    /**
     * Get role list
     * 
     * Prepare and return the list of roles for the table view
     * 
     * @return string JSON Encoded role list
     * 
     * @access public
     */
    public function getTable() {
        //retrieve list of users
        $count = count_users();
        $stats = $count['avail_roles'];

        $filtered = $this->fetchRoleList();

        $response = array(
            'recordsTotal'    => count(get_editable_roles()),
            'recordsFiltered' => count($filtered),
            'draw'            => AAM_Core_Request::request('draw'),
            'data'            => array(),
        );
        
        foreach ($filtered as $id => $data) {
            $uc    = (isset($stats[$id]) ? $stats[$id] : 0);
            $allow = current_user_can('delete_users');
            
            $response['data'][] = array(
                $id,
                $uc,
                translate_user_role($data['name']),
                'manage,edit,clone' . ($uc || !$allow ? '' : ',delete'),
                AAM_Core_API::maxLevel($data['capabilities'])
            );
        }

        return json_encode(apply_filters('aam-get-role-list-filter', $response));
    }
    
    /**
     * Retrieve Pure Role List
     * 
     * @return string
     */
    public function getList(){
        return json_encode($this->fetchRoleList());
    }
    
    /**
     * Fetch role list
     * 
     * @return array
     * 
     * @access protected
     */
    protected function fetchRoleList() {
        $response = array();
         
        //filter by name
        $search  = trim(AAM_Core_Request::request('search.value'));
        $exclude = trim(AAM_Core_Request::request('exclude'));
        
        $roles   = get_editable_roles();
        foreach ($roles as $id => $role) {
            $match = preg_match('/^' . $search . '/i', $role['name']);
            if (($exclude != $id) && (!$search || $match)) {
                $response[$id] = $role;
            }
        }
        
        return $response;
    }

    /**
     * Add New Role
     * 
     * @return string
     * 
     * @access public
     */
    public function add() {
        $name    = sanitize_text_field(AAM_Core_Request::post('name'));
        $roles   = AAM_Core_API::getRoles();
        $role_id = strtolower($name);
        
        //if inherited role is set get capabilities from it
        $parent = $roles->get_role(trim(AAM_Core_Request::post('inherit')));
        $caps   = ($parent ? $parent->capabilities : array());

        if ($role = $roles->add_role($role_id, $name, $caps)) {
            $response = array(
                'status' => 'success',
                'role'   => array(
                    'id'    => $role_id,
                    'name'  => $name,
                    'level' => AAM_Core_API::maxLevel($caps)
                )
            );
            if (AAM_Core_Request::post('clone')) {
                $this->cloneSettings($role, $parent);
            }
            do_action('aam-post-add-role-action', $role, $parent);
        } else {
            $response = array('status' => 'failure');
        }

        return json_encode($response);
    }
    
    /**
     * 
     * @global type $wpdb
     * @param type $role
     * @param type $parent
     */
    protected function cloneSettings($role, $parent) {
        global $wpdb;
        
        //clone _options settings
        $oquery = "SELECT * FROM {$wpdb->options} WHERE `option_name` LIKE %s";
        if ($wpdb->query($wpdb->prepare($oquery, 'aam_%_role_' . $parent->name))) {
            foreach($wpdb->last_result as $setting) {
                AAM_Core_API::updateOption(
                    str_replace($parent->name, $role->name, $setting->option_name), 
                    maybe_unserialize($setting->option_value)
                );
            }
        }
        
        //clone _postmeta settings
        $pquery = "SELECT * FROM {$wpdb->postmeta} WHERE `meta_key` LIKE %s";
        if ($wpdb->query($wpdb->prepare($pquery, 'aam-%-role' . $parent->name))) {
            foreach($wpdb->last_result as $setting) {
                add_post_meta(
                    $setting->post_id, 
                    str_replace($parent->name, $role->name, $setting->meta_key), 
                    maybe_unserialize($setting->meta_value)
                );
            }
        }
    }
    
    /**
     * Edit role name
     * 
     * @return string
     * 
     * @access public
     */
    public function edit() {
        $role    = AAM_Backend_View::getSubject();
        $role->update(trim(AAM_Core_Request::post('name')));
        
        do_action('aam-post-update-role-action', $role);
        
        return json_encode(array('status' => 'success'));
    }

    /**
     * Delete role
     * 
     * @return string
     * 
     * @access public
     */
    public function delete() {
        if (AAM_Backend_View::getSubject()->delete()) {
            $status = 'success';
        } else {
            $status = 'failure';
        }

        return json_encode(array('status' => $status));
    }

}