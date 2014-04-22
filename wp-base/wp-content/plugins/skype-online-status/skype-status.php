<?php
/*
Plugin Name: Skype Online Status
Plugin URI: http://status301.net/wordpress-plugins/skype-online-status/
Description: Add multiple, highly customizable and accessible Skype buttons to post/page content (quick-tags), sidebar (unlimited number of widgets) or anywhere else (template code). Find documentation and advanced configuration options on the settings page or just go straight to your <a href="widgets.php">Widgets</a> page and add one there...  
Text Domain: skype-online-status
Domain Path: languages
Version: 2.9.5
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
define('SOSVERSION', '2.9.5');
define('SOSVERSION_DATE', '2014-04-17');

if (file_exists(dirname(__FILE__).'/skype-online-status'))
	$skype_mu_dir = "/skype-online-status";
else
	$skype_mu_dir = "";
		
// Plugin constants
define('SOSPLUGINURL', plugins_url($skype_mu_dir, __FILE__));
define('SOSPLUGINDIR', dirname(__FILE__).$skype_mu_dir);
define('SOSPLUGINBASENAME', plugin_basename(__FILE__));
define('SOSPLUGINFILE', basename(__FILE__));

// Checks whether your server is capable and allowing the remote Skype status file to be read
if (function_exists('curl_exec') || ini_get('allow_url_fopen'))
	define('SOSREMOTE', TRUE);
else 
	define('SOSREMOTE', FALSE);

// Print all Skype settings from the database at the bottom of the settings page for debugging (normally, leave to FALSE)
define('SOSDATADUMP', FALSE);

// load classes
require_once(SOSPLUGINDIR . '/skype-classes.php');

Skype_Online_Status::init();

// template tag functions 
function get_skype_status($args = '') {
	echo Skype_Online_Status::skype_status($args);
}
