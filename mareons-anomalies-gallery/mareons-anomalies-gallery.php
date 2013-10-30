<?php
/**
 * The WordPress Plugin Boilerplate.
 *
 * A foundation off of which to build well-documented WordPress plugins that
 * also follow WordPress Coding Standards and PHP best practices.
 *
 * @package   Mareons_Anomalies_Gallery
 * @author    theProf <theProf@gerggle.com>
 * @license   GPL-2.0+
 * @link      http://gerggle.com
 * @copyright 2013 theProf
 *
 * @wordpress-plugin
 * Plugin Name:       Mareons Anomalies Gallery
 * Plugin URI:        TODO
 * Description:       Gallery with rollover caption
 * Version:           1.0.0
 * Author:            theProf
 * Author URI:        theProf.gerggle.com
 * Text Domain:       mareons-anomalies-gallery-locale
 * License:           GPL-2.0+
 * License URI:       http://www.gnu.org/licenses/gpl-2.0.txt
 * Domain Path:       /languages
 * GitHub Plugin URI: https://github.com/theProf/WordPress-Plugin-Boilerplate/tree/mareons-anomalies-gallery
 */

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}

/*
 * TODO:
 *
 * - replace `class-mareons-anomalies-gallery.php` with the name of the plugin's class file
 * - replace `class-plugin-admin.php` with the name of the plugin's admin file
 *
 */
require_once( plugin_dir_path( __FILE__ ) . 'class-mareons-anomalies-gallery.php' );
require_once( plugin_dir_path( __FILE__ ) . 'class-mareons-anomalies-gallery-admin.php' );

/*
 * Register hooks that are fired when the plugin is activated or deactivated.
 * When the plugin is deleted, the uninstall.php file is loaded.
 *
 * TODO:
 *
 * - replace Mareons_Anomalies_Gallery with the name of the class defined in
 *   `class-mareons-anomalies-gallery.php`
 */
register_activation_hook( __FILE__, array( 'Mareons_Anomalies_Gallery', 'activate' ) );
register_deactivation_hook( __FILE__, array( 'Mareons_Anomalies_Gallery', 'deactivate' ) );

/*
 * TODO:
 *
 * - replace Mareons_Anomalies_Gallery with the name of the class defined in
 *   `class-mareons-anomalies-gallery.php`
 * - replace Mareons_Anomalies_Gallery_Admin with the name of the class defined in
 *   `class-mareons-anomalies-gallery-admin.php`
 */
add_action( 'plugins_loaded', array( 'Mareons_Anomalies_Gallery', 'get_instance' ) );
add_action( 'plugins_loaded', array( 'Mareons_Anomalies_Gallery_Admin', 'get_instance' ) );