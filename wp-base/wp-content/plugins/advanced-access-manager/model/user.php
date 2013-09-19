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
 * User Model Class
 * 
 * @package AAM
 * @subpackage Models
 * @author Vasyl Martyniuk <martyniuk.vasyl@gmail.com>
 * @copyrights Copyright © 2011 Vasyl Martyniuk
 * @license GNU General Public License {@link http://www.gnu.org/licenses/}
 */
class mvb_Model_User extends WP_User {

    /**
     * Initialize User Object
     * 
     * @param int $user_id 
     */
    function __construct($user_id = FALSE) {

        if (!$user_id) {
            $user_id = get_current_user_id();
        }
        parent::__construct($user_id);
    }

    /**
     * Get list of current User's Roles
     * 
     * This function is compatible with WP releases 3.2.x and 3.3
     * 
     * @global string $wp_version
     * @return array 
     */
    function getRoles() {
        global $wp_version;

        if (version_compare($wp_version, '3.2.1', '>')) {
            $result = (is_array($this->roles) ? $this->roles : array());
        } else {
            //deprecated, will be deleted in release 1.5
            if (is_object($this->data) && isset($this->data->{$this->cap_key})) {
                $result = array_keys($this->data->{$this->cap_key});
            } else {
                $result = array();
            }
        }

        return $result;
    }

    function setRoles($roles) {
        global $wp_version;

        if (version_compare($wp_version, '3.2.1', '>')) {
            $this->roles = $roles;
        } else {
            //TODO deprecated, will be deleted in release 1.5
            if (is_object($this->data) && isset($this->data->{$this->cap_key})) {
                $t = array();
                foreach($roles as $role){
                    $t[$role] = 1;
                }
                $this->data->{$this->cap_key} = $t;
            } 
        }
    }

    /**
     * Return list of all capabilities registered in the System
     * 
     * @return array
     */
    function getAllCaps() {

        $caps = (is_array($this->allcaps) ? $this->allcaps : array());

        if (isset($caps[WPACCESS_SADMIN_ROLE])) {
            unset($caps[WPACCESS_SADMIN_ROLE]);
        }

        return $caps;
    }

}

?>