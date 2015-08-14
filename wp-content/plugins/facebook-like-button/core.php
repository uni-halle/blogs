<?php
	
/*
Plugin Name: Facebook Like (PressGraph)
Plugin URI: http://www.ahmedgeek.com/facebook-like-v6
Description: Plugin that helps you add the Facebook Like button to your website, no coding required.
Version: 6.0.3
Author: AhmedGeek
Author URI: http://www.ahmedgeek.com
License: GPL2

Copyright 2010-2015  Facebook Like Button  (email : me@ahmedgeek.com)

This program is free software; you can redistribute it and/or modify
it under the terms of the GNU General Public License, version 2, as 
published by the Free Software Foundation.

This program is distributed in the hope that it will be useful,
but WITHOUT ANY WARRANTY; without even the implied warranty of
MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
GNU General Public License for more details.

You should have received a copy of the GNU General Public License
along with this program; if not, write to the Free Software
Foundation, Inc., 51 Franklin St, Fifth Floor, Boston, MA  02110-1301  USA

*/

//Include AGPressGraph Core Manager

namespace AGPressGraph;

$AGPressGraphPath = plugin_dir_path(__FILE__);

include_once($AGPressGraphPath . "core/AGLB.coremanager.php");

$AGPressGraphCore = new coreManager();
	
?>