<?php

/**
 * ======================================================================
 * LICENSE: This file is subject to the terms and conditions defined in *
 * file 'license.txt', which is part of this source code package.       *
 * ======================================================================
 */

/**
 *
 * @package AAM
 * @author Vasyl Martyniuk <support@wpaam.com>
 * @copyright Copyright C 2013 Vasyl Martyniuk
 * @license GNU General Public License {@link http://www.gnu.org/licenses/}
 */
class aam_View_User extends aam_View_Abstract {

    /**
     * Generate UI content
     * 
     * @return string
     * 
     * @access public
     */
    public function content() {
        return $this->loadTemplate(dirname(__FILE__) . '/tmpl/user.phtml');
    }

    /**
     * Retrieve list of users
     * 
     * Based on filters, get list of users
     * 
     * @return string JSON encoded list of users
     * 
     * @access public
     */
    public function retrieveList() {
        //get total number of users
        $total = count_users();
        $result = $this->query();
        $response = array(
            'iTotalRecords' => $total['total_users'],
            'iTotalDisplayRecords' => $result->get_total(),
            'sEcho' => aam_Core_Request::request('sEcho'),
            'aaData' => array(),
        );
        foreach ($result->get_results() as $user) {
            $response['aaData'][] = array(
                $user->ID,
                $user->user_login,
                ($user->display_name ? $user->display_name : $user->user_nicename),
                '',
                $user->user_status,
                ($this->canManage($user) ? 1 : 0)
            );
        }

        return json_encode($response);
    }
    
    /**
     * Check if specified user can be managed by current user
     * 
     * @param WP_User $user
     * 
     * @return boolean
     * 
     * @access public
     */
    public function canManage(WP_User $user = null){
        //AAM does not support multi-roles. Get only one first role
        $roles = $user->roles;
        $role = get_role(array_shift($roles));
        //get user's highest level
        $level = aam_Core_API::getUserLevel();
        
        if (empty($role->capabilities['level_' . $level]) 
                    || !$role->capabilities['level_' . $level]
                    || aam_Core_API::isSuperAdmin()){
            $response = true;
        } else {
            $response = false;
        }
        
        return $response;
    }

    /**
     * Query database for list of users
     * 
     * Based on filters and settings get the list of users from database
     * 
     * @return \WP_User_Query
     * 
     * @access public
     */
    public function query() {
        if ($search = trim(aam_Core_Request::request('sSearch'))) {
            $search = "{$search}*";
        }
        $args = array(
            'number' => '',
            'blog_id' => get_current_blog_id(),
            'role' => aam_Core_Request::request('role'),
            'fields' => 'all',
            'number' => aam_Core_Request::request('iDisplayLength'),
            'offset' => aam_Core_Request::request('iDisplayStart'),
            'search' => $search,
            'search_columns' => array('user_login', 'user_email', 'display_name'),
            'orderby' => 'user_nicename',
            'order' => 'ASC'
        );

        return new WP_User_Query($args);
    }

    /**
     * Block user
     * 
     * @return string
     * 
     * @access public
     */
    public function block() {
        if ($this->isManagable() && $this->getSubject()->block()){
            $response = array(
                'status' => 'success',
                'user_status' => $this->getSubject()->user_status
            );
        } else{
            $response = array('status' => 'failure');
        }

        return json_encode($response);
    }

    /**
     * Delete user
     * 
     * @return string
     * 
     * @access public
     */
    public function delete() {
        if ($this->isManagable($this->getSubject()->getUser())){
            $response = $this->getSubject()->delete();
        } else {
            $response = false;
        }
        
        return json_encode(array('status' => $response ? 'success' : 'failure'));
    }

}