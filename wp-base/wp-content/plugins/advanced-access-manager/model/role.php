<?php

/*
  Copyright (C) <2011>  Vasyl Martyniuk <martyniuk.vasyl@gmail.com>

  This program is free software: you can redistribute it and/or modify
  it under the terms of the GNU General Public License as published by
  the Free Software Foundation, either version 3 of the License, or
  (at your option) any later version.

  This program is distributed in the hope that it will be useful,
  but WITHOUT ANY WARRANTY; without even the implied warranty of
  MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
  GNU General Public License for more details.

  You should have received a copy of the GNU General Public License
  along with this program.  If not, see <http://www.gnu.org/licenses/>.

 */

/**
 * Role Model Class
 *
 * @package AAM
 * @subpackage Models
 * @author Vasyl Martyniuk <martyniuk.vasyl@gmail.com>
 * @copyrights Copyright © 2011 Vasyl Martyniuk
 * @license GNU General Public License {@link http://www.gnu.org/licenses/}
 */
class mvb_Model_Role extends WP_Roles {

    function __construct() {

        parent::__construct();
    }

    /**
     * Create a New User's Role
     *
     * Use add_role function from WP_Roles to create a new User Role
     *
     * @param string User Role title
     * @return array Result
     */
    public function createNewRole($newRoleTitle, $caps = array()) {

        $role_id = sanitize_title_with_dashes($newRoleTitle);
        $role_id = str_replace('-', '_', $role_id);
        $label = htmlspecialchars(trim($newRoleTitle));

        if ($this->add_role($role_id, $label, $caps)) {
            $status = 'success';
        } else {
            $status = 'error';
        }

        $result = array(
            'result' => $status,
            'new_role' => $role_id,
        );

        return $result;
    }

    public function add_role($role, $display_name, $capabilities = array()) {

        if (mvb_Model_API::isNetworkPanel()){
            $roles = mvb_Model_API::getRoleList(FALSE);
            if (!isset($roles[$role])){
                $roles[$role] = array(
                    'name' => $display_name,
                    'capabilities' => $capabilities
                );
                $result = mvb_Model_API::updateBlogOption('user_roles', $roles);
            }else{
                $result = FALSE;
            }
        }else{
            $result = parent::add_role($role, $display_name, $capabilities);
        }

        return $result;
    }

}

?>