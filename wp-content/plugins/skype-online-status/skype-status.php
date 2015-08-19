<?php
/*
Plugin Name: Skype Legacy Buttons
Plugin URI: http://status301.net/wordpress-plugins/skype-online-status/
Description: Add multiple, highly customizable and accessible Skype buttons to post/page content (quick-tags), sidebar (unlimited number of widgets) or anywhere else (template code). Find documentation and advanced configuration options on the settings page or just go straight to your <a href="widgets.php">Widgets</a> page and add one there...  
Text Domain: skype-online-status
Domain Path: languages
Version: 3.0.2
Author: RavanH
Author URI: http://status301.net/
*/

/*  Copyright 2009  RavanH  (email : ravanhagen@gmail.com)

    This program is free software; you can redistribute it and/or modify
    it under the terms of the GNU General Public License as published by
    the Free Software Foundation; either version 2 of the License, or
    (at your option) any later version.

    This program is distributed in the hope that it will be useful,
    but WITHOUT ANY WARRANTY; without even the implied warranty of
    MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
    GNU General Public License for more details.

    You should have received a copy of the GNU General Public License
    along with this program; if not, write to the Free Software
    Foundation, Inc., 59 Temple Place, Suite 330, Boston, MA  02111-1307  USA
*/

/*
    For Installation instructions, usage, revision history and other info: see readme.txt included in this package
*/

// Plugin version number and date
define('SOSVERSION', '3.0');
define('SOSVERSION_DATE', '2015-05-20');
		
// Plugin constants
define('SOSPLUGINDIR', dirname(__FILE__));
define('SOSPLUGINBASENAME', plugin_basename(__FILE__));
define('SOSPLUGINFILE', basename(__FILE__));

// load classes
require_once(SOSPLUGINDIR . '/skype-classes.php');

Skype_Online_Status::init();

// template tag functions 
function get_skype_status($args = '') {
	echo Skype_Online_Status::skype_status($args);
}
