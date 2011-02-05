<?php
/*
Plugin Name: Gecka IE Warning
Plugin URI: http://gecka-apps.com/wordpress-plugins/gecka-ie-warning
Description: Implements the bgstretcher jquery script
Version: 1.1
Author: Gecka
Author URI: http://gecka-apps.com
Licence: GPL2
*/

/* Copyright 2010  Gecka SARL (email: contact@gecka.nc). All rights reserved

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

// requires PHP 5
function gkiew_activation_check(){
	if (version_compare(PHP_VERSION, '5.0.0', '<')) {
		deactivate_plugins( basename(dirname(__FILE__)) . '/' . basename(__FILE__) ); // Deactivate ourself
		wp_die("Sorry, Gecka Images Max Size requires PHP 5 or higher. Ask your host how to enable PHP 5 as the default on your servers.");
	}
}
register_activation_hook(__FILE__, 'gkiew_activation_check');

define('GKIEW_PATH' , WP_PLUGIN_DIR . "/" . plugin_basename(dirname(__FILE__)) );
define('GKIEW_URL'  , WP_PLUGIN_URL . "/" . plugin_basename(dirname(__FILE__)) );

require GKIEW_PATH . '/gecka-ie-warning.class.php';

new Gecka_IeWarning;
