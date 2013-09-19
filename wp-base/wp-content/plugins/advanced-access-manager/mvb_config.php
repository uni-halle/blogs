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

 //Core constants
define('WPACCESS_PREFIX', 'wpaccess_');
define('WPACCESS_BASE_DIR', dirname(__FILE__) . DIRECTORY_SEPARATOR);
define('WPACCESS_DIRNAME', basename(WPACCESS_BASE_DIR));
define('WPACCESS_AWM_WSDL', dirname(__FILE__) . '/service/awm.wsdl');
define('WPACCESS_PRO_URL', 'http://whimba.org/get-pro/');

define('WPACCESS_ERROR162_URL', 'http://whimba.org/forum/viewtopic.php?f=7&t=244');
define('WPACCESS_ERROR164_URL', 'http://whimba.org/forum/viewtopic.php?f=7&t=246');
define('WPACCESS_ERROR166_URL', 'http://whimba.org/forum/viewtopic.php?f=7&t=5');
define('WPACCESS_ERROR167_URL', 'http://whimba.org/forum/viewtopic.php?f=7&t=248');

define('WPACCESS_ACCESS_LIST', 'list');
define('WPACCESS_ACCESS_EXCLUDE', 'exclude');
define('WPACCESS_ACCESS_BROWSE', 'browse');
define('WPACCESS_ACCESS_EDIT', 'edit');
define('WPACCESS_ACCESS_READ', 'read');
define('WPACCESS_ACCESS_TRASH', 'trash');
define('WPACCESS_ACCESS_DELETE', 'delete');
define('WPACCESS_ACCESS_COMMENT', 'comment');
define('WPACCESS_ACCESS_PUBLISH', 'publish');

//Plugin constants
define('WPACCESS_BASE_URL', WP_PLUGIN_URL . '/' . WPACCESS_DIRNAME . '/');
define('WPACCESS_TEMPLATE_DIR', WPACCESS_BASE_DIR . 'view/html/');
define('WPACCESS_CSS_URL', WPACCESS_BASE_URL . 'view/css/');
define('WPACCESS_JS_URL', WPACCESS_BASE_URL . 'view/js/');

define('WPACCESS_ADMIN_ROLE', 'administrator');
define('WPACCESS_SADMIN_ROLE', 'super_admin');
define('WPACCESS_RESTRICTION_LIMIT', 5);
define('WPACCESS_APPLY_LIMIT', 4);
define('WPACCESS_TOP_LEVEL', 10);

define('WPACCESS_LOG_DIR', WPACCESS_BASE_DIR . 'logs'); //logs dir

define('WPACCESS_APPL_ENV', (getenv('APPLICATION_ENV') ? getenv('APPLICATION_ENV') : 'production'));

//configure include path for library
$path = WPACCESS_BASE_DIR . 'library/';
set_include_path(get_include_path() . PATH_SEPARATOR . $path);

//require phpQuery
if (!class_exists('phpQuery')) {
    require_once(WPACCESS_BASE_DIR . 'library/phpQuery/phpQuery.php');
}

//load general files
require_once('mvb_functions.php');

load_plugin_textdomain('aam', false, WPACCESS_DIRNAME . '/langs');