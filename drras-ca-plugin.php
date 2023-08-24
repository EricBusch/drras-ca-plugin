<?php
/*
Plugin Name: Custom Plugin
Description: Custom code. Don't deactivate or delete.
License: GPL v3
Version: 1.0.0

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
 * Plugin version
 */
const DRRAS_VERSION = '1.0.0';

/**
 * /absolute/path/to/wp-content/plugins/profilemc/profilemc.php
 */
const DRRAS_PLUGIN_FILE = __FILE__;

/**
 * URL to plugin directory.
 *
 * Ex. https://example.com/wp-content/plugins/profilemc/
 */
define( 'DRRAS_URL', plugin_dir_url( __FILE__ ) );

/**
 * Absolute path to plugin directory.
 *
 * Ex. /absolute/path/to/wp-content/plugins/profilemc/
 */
define( 'DRRAS_PATH', plugin_dir_path( __FILE__ ) );

/**
 * Plugin basename.
 *
 * Ex. profilemc/profilemc.php
 */
define( 'DRRAS_BASENAME', plugin_basename( __FILE__ ) );

/**
 * Routes in this website.
 *
 * Don't edit the "keys" in this array. These are used in various places to link to specific pages.
 * You can edit the "values" (which are Post IDs) if you need to change a page to link to.
 */
const DRRAS_ROUTES = [
	'about'          => 10,
	'consultations'  => 286,
	'contact'        => 12,
	'homepage'       => 19,
	'privacy-policy' => 3,
	'treatments'     => 7,
];

/**
 * Load required files.
 */
require_once dirname( DRRAS_PLUGIN_FILE ) . '/functions.php';
require_once dirname( DRRAS_PLUGIN_FILE ) . '/actions.php';
require_once dirname( DRRAS_PLUGIN_FILE ) . '/filters.php';
require_once dirname( DRRAS_PLUGIN_FILE ) . '/shortcodes.php';
