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

function aam_debug($what) {
    echo '<pre>';
    print_r($what);
    echo '</pre>';
}

function init_wpaccess() {
    static $main;

    $main = new mvb_WPAccess();
}

/**
 * Autoloader for project Advanced Access Manager
 *
 * Try to load a class if prefix is mvb_
 *
 * @param string $class_name
 */
function mvb_autoload($class_name) {

    $parts = explode('_', $class_name);

    if (array_shift($parts) == 'mvb') {
        $path = WPACCESS_BASE_DIR . strtolower(implode(DIRECTORY_SEPARATOR, $parts) . '.php');
        if (file_exists($path)) {
            require($path);
        }
    }
}

spl_autoload_register('mvb_autoload');

function aam_set_current_user() {
    global $current_user;

    //overwrite user capabilities
    if (!isset($current_user->roles) || !in_array(WPACCESS_SADMIN_ROLE, $current_user->roles)) {
        $config = mvb_Model_API::getUserAccessConfig($current_user->ID);
        if ($config instanceof mvb_Model_UserConfig) {
            $current_user->allcaps = $config->getCapabilities();
            if ($config->getUser() instanceof WP_User) {
                foreach ($config->getUser()->getRoles() as $role) {
                    $current_user->allcaps[$role] = 1;
                }
            }
        }
    }
}