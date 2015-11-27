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
class AAM_Backend_Role {

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
            'recordsTotal' => count(get_editable_roles()),
            'recordsFiltered' => count($filtered),
            'draw' => AAM_Core_Request::request('draw'),
            'data' => array(),
        );
        
        foreach ($filtered as $role => $data) {
            $uc = (isset($stats[$role]) ? $stats[$role] : 0);
            $response['data'][] = array(
                $role,
                $uc,
                translate_user_role($data['name']),
                'manage,edit' . ($uc || !current_user_can('delete_users') ? ',no-delete' : ',delete')
            );
        }

        return json_encode($response);
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
        $search = trim(AAM_Core_Request::request('search.value'));
        $roles = get_editable_roles();
        foreach ($roles as $id => $role) {
            if (!$search || preg_match('/^' . $search . '/i', $role['name'])) {
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
        $name = trim(AAM_Core_Request::post('name'));
        $roles = new WP_Roles;
        $role_id = strtolower($name);
        //if inherited role is set get capabilities from it
        $parent = trim(AAM_Core_Request::post('inherit'));
        if ($parent && $roles->get_role($parent)){
            $caps = $roles->get_role($parent)->capabilities;
        } else {
            $caps = array();
        }

        if ($roles->add_role($role_id, $name, $caps)) {
            $response = array(
                'status' => 'success',
                'role' => $role_id
            );
        } else {
            $response = array('status' => 'failure');
        }

        return json_encode($response);
    }

    /**
     * Edit role name
     * 
     * @return string
     * 
     * @access public
     */
    public function edit() {
        $result = AAM_Backend_View::getSubject()->update(
                trim(AAM_Core_Request::post('name'))
        );
        
        return json_encode(
                array('status' => ($result ? 'success' : 'failure'))
        );
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