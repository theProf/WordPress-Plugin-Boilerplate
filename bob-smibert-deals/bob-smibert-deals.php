<?php
/**
 * The WordPress Plugin Boilerplate.
 *
 * A foundation off of which to build well-documented WordPress plugins that
 * also follow WordPress Coding Standards and PHP best practices.
 *
 * @package   Bob_Smibert_Deals
 * @author    theProf <email@example.com>
 * @license   GPL-2.0+
 * @link      http://example.com
 * @copyright 2013 Your Name or Company Name
 *
 * @wordpress-plugin
 * Plugin Name:       Bob Smibert Deals
 * Plugin URI:        TODO
 * Description:       TODO
 * Version:           1.0.0
 * Author:            TODO
 * Author URI:        TODO
 * Text Domain:       bob-smibert-deals-locale
 * License:           GPL-2.0+
 * License URI:       http://www.gnu.org/licenses/gpl-2.0.txt
 * Domain Path:       /languages
 * GitHub Plugin URI: https://github.com/<owner>/<repo>
 */

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}

/*
 * TODO:
 *
 * - replace `class-bob-smibert-deals.php` with the name of the plugin's class file
 * - replace `class-plugin-admin.php` with the name of the plugin's admin file
 *
 */
require_once( plugin_dir_path( __FILE__ ) . 'class-bob-smibert-deals.php' );
require_once( plugin_dir_path( __FILE__ ) . 'class-bob-smibert-deals-admin.php' );

/*
 * Register hooks that are fired when the plugin is activated or deactivated.
 * When the plugin is deleted, the uninstall.php file is loaded.
 *
 * TODO:
 *
 * - replace Bob_Smibert_Deals with the name of the class defined in
 *   `class-bob-smibert-deals.php`
 */
register_activation_hook( __FILE__, array( 'Bob_Smibert_Deals', 'activate' ) );
register_deactivation_hook( __FILE__, array( 'Bob_Smibert_Deals', 'deactivate' ) );

/*
 * TODO:
 *
 * - replace Bob_Smibert_Deals with the name of the class defined in
 *   `class-bob-smibert-deals.php`
 * - replace Bob_Smibert_Deals_Admin with the name of the class defined in
 *   `class-bob-smibert-deals-admin.php`
 */
add_action( 'plugins_loaded', array( 'Bob_Smibert_Deals', 'get_instance' ) );
add_action( 'plugins_loaded', array( 'Bob_Smibert_Deals_Admin', 'get_instance' ) );