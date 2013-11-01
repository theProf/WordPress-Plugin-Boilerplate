<?php
/**
 * The WordPress Plugin Boilerplate.
 *
 * A foundation off of which to build well-documented WordPress plugins that
 * also follow WordPress Coding Standards and PHP best practices.
 *
 * @package   Mareons_Anomalies_Home
 * @author    theProf <theProf@gerggle.com>
 * @license   GPL-2.0+
 * @link      http://gerggle.com
 * @copyright 2013 theProf
 *
 * @wordpress-plugin
 * Plugin Name:       Mareons Anomalies Home
 * Plugin URI:        TODO
 * Description:       home with rollover caption
 * Version:           1.0.0
 * Author:            theProf
 * Author URI:        theProf.gerggle.com
 * Text Domain:       mareons-anomalies-home-locale
 * License:           GPL-2.0+
 * License URI:       http://www.gnu.org/licenses/gpl-2.0.txt
 * Domain Path:       /languages
 * GitHub Plugin URI: https://github.com/theProf/WordPress-Plugin-Boilerplate/tree/mareons-anomalies-home
 */

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}

/*
 * TODO:
 *
 * - replace `class-mareons-anomalies-home.php` with the name of the plugin's class file
 * - replace `class-plugin-admin.php` with the name of the plugin's admin file
 *
 */
require_once( plugin_dir_path( __FILE__ ) . 'class-mareons-anomalies-home.php' );
require_once( plugin_dir_path( __FILE__ ) . 'class-mareons-anomalies-home-admin.php' );

/*
 * Register hooks that are fired when the plugin is activated or deactivated.
 * When the plugin is deleted, the uninstall.php file is loaded.
 *
 * TODO:
 *
 * - replace Mareons_Anomalies_home with the name of the class defined in
 *   `class-mareons-anomalies-home.php`
 */
register_activation_hook( __FILE__, array( 'Mareons_Anomalies_Home', 'activate' ) );
register_deactivation_hook( __FILE__, array( 'Mareons_Anomalies_Home', 'deactivate' ) );

/*
 * TODO:
 *
 * - replace Mareons_Anomalies_home with the name of the class defined in
 *   `class-mareons-anomalies-home.php`
 * - replace Mareons_Anomalies_home_Admin with the name of the class defined in
 *   `class-mareons-anomalies-home-admin.php`
 */
add_action( 'plugins_loaded', array( 'Mareons_Anomalies_Home', 'get_instance' ) );
add_action( 'plugins_loaded', array( 'Mareons_Anomalies_Home_Admin', 'get_instance' ) );