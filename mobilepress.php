<?php
/*
Plugin Name: MobilePress
Plugin URI: http://mobilepress.co.za
Description: Turn your WordPress blog into a mobile website/blog.
Version: 1.1.5
Author: Aduity
Author URI: http://aduity.com

Copyright 2009  Aduity  (email: mobilepress@aduity.com)

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
Foundation, Inc., 51 Franklin St, Fifth Floor, Boston, MA  02110-1301  USA
*/
global $wpdb;

// Load the config
require_once(dirname(__FILE__) . '/system/config/config.php');

// Load the helpers
require_once(dirname(__FILE__) . '/system/helpers/functions.php');

// Load the versions (must be loaded after the function helpers)
require_once(dirname(__FILE__) . '/system/config/versions.php');

// Load the core class
require_once(dirname(__FILE__) . '/system/classes/core.php');

if (class_exists('MobilePress_core'))
{	
	// New MobilePress object
	$mobilepress = new MobilePress_core;
	
	// Setup the installer on activation of the plugin
	register_activation_hook(__FILE__, array(&$mobilepress, 'load_activation'));
	
	// Shut down the plugin on deactivation
	register_deactivation_hook(__FILE__, array(&$mobilepress, 'load_deactivation'));
	
	// Setup admin panel only if we are inside the admin area, otherwise run the normal render code
	if (is_admin())
	{
		// Setup the admin area
		$mobilepress->create_admin();
	}
	else
	{
		// Start a session if not started already
		if ( ! session_id())
		{
			@session_start();
		}
		
		// Load the ad/analytics helpers
		require_once(dirname(__FILE__) . '/system/helpers/ad_functions.php');
		
		// Initialize the MobilePress check logic and rendering
		$mobilepress->load_site();
	}
}

// Are we uninstalling the plugin?
if (function_exists('register_uninstall_hook'))
{
	register_uninstall_hook(__FILE__, 'mopr_load_uninstall');
}

if ( ! function_exists('mopr_load_uninstall'))
{
	function mopr_load_uninstall()
	{
		require_once(dirname(__FILE__) . '/system/classes/uninstall.php');
		$uninstall = new MobilePress_uninstall;
	}
}		
?>