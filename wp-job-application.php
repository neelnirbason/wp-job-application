<?php
/**
 * The plugin bootstrap file
 *
 * This file is read by WordPress to generate the plugin information in the plugin
 * admin area. This file also includes all of the dependencies used by the plugin,
 * registers the activation and deactivation functions, and defines a function
 * that starts the plugin.
 *
 * @link              https://devkabir.shop
 * @since             1.0.0
 * @package           Init
 *
 * @wordpress-plugin
 * Plugin Name:       Job Application for WordPress
 * Plugin URI:        https://github.com/devkabir/wp-job-application
 * Description:       A lightweight plugin for handling job applications
 * Version:           1.0.0
 * Author:            Dev Kabir
 * Author URI:        https://devkabir.shop
 * License:           GPL-2.0+
 * License URI:       http://www.gnu.org/licenses/gpl-2.0.txt
 * Text Domain:       wp-job-application
 * Domain Path:       /languages
 */

/*
|--------------------------------------------------------------------------
| If this file is called directly, abort.
|--------------------------------------------------------------------------
 */

use DevKabir\Application\Activated;
use DevKabir\Application\Deactivated;
use DevKabir\Application\Init;

if ( ! defined( 'WPINC' ) ) {
	die;
}

/*
|--------------------------------------------------------------------------
| Register autoloader
|--------------------------------------------------------------------------
 */
require 'vendor/autoload.php';

/*
|--------------------------------------------------------------------------
| The code that runs during plugin activation
|--------------------------------------------------------------------------
 */
register_activation_hook( __FILE__, array( Activated::class, 'init' ) );

/*
|--------------------------------------------------------------------------
| The code that runs during plugin deactivation
|--------------------------------------------------------------------------
 */
register_deactivation_hook( __FILE__, array( Deactivated::class, 'init' ) );

/*
|--------------------------------------------------------------------------
| Start the plugin
|--------------------------------------------------------------------------
 */
( new Init() )->start();
