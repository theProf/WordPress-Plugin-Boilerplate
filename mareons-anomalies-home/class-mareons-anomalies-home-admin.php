<?php
/**
 * Mareons Anomalies home
 *
 * @package   Mareons_Anomalies_Home
 * @author    theProf <theProf@gerggle.com>
 * @license   GPL-2.0+
 * @link      http://gerggle.com
 * @copyright 2013 theProf
 */

/**
 * Plugin class. This class should ideally be used to work with the
 * administrative side of the WordPress site.
 *
 * If you're interested in introducing public-facing
 * functionality, then refer to `class-mareons-anomalies-plugin.php`
 *
 * TODO: Rename this class to a proper name for your plugin.
 *
 * @package Mareons_Anomalies_Home_Admin
 * @author  theProf <theProf@gerggle.com>
 */
class Mareons_Anomalies_Home_Admin {

	/**
	 * Instance of this class.
	 *
	 * @since    1.0.0
	 *
	 * @var      object
	 */
	protected static $instance = null;

	/**
	 * Slug of the plugin screen.
	 *
	 * @since    1.0.0
	 *
	 * @var      string
	 */
	protected $plugin_screen_hook_suffix = null;

	/**
	 * Initialize the plugin by loading admin scripts & styles and adding a
	 * settings page and menu.
	 *
	 * @since     1.0.0
	 */
	private function __construct() {

		/*
		 * Call $plugin_slug from public plugin class.
		 *
		 * TODO:
		 *
		 * - Rename "Mareons_Anomalies_home" to the name of your initial plugin class
		 *
		 */
		$plugin = Mareons_Anomalies_Home::get_instance();
		$this->plugin_slug = $plugin->get_plugin_slug();

		// Load admin style sheet and JavaScript.
		add_action( 'admin_enqueue_scripts', array( $this, 'enqueue_admin_styles' ) );
		add_action( 'admin_enqueue_scripts', array( $this, 'enqueue_admin_scripts' ) );

		/*
		 * Define custom functionality.
		 */
		
		// now attach our function to the hook
		add_filter("attachment_fields_to_edit", array($this, "add_image_attachment_fields_to_edit"), null, 2);
		
		// now attach our function to the hook.
		add_filter("attachment_fields_to_save", array($this, "add_image_attachment_fields_to_save"), null, 2);

	}

	/**
	 * Return an instance of this class.
	 *
	 * @since     1.0.0
	 *
	 * @return    object    A single instance of this class.
	 */
	public static function get_instance() {

		// If the single instance hasn't been set, set it now.
		if ( null == self::$instance ) {
			self::$instance = new self;
		}

		return self::$instance;
	}

	/**
	 * Register and enqueue admin-specific style sheet.
	 * 
	 * TODO:
	 *
	 * - Rename "Mareons_Anomalies_home" to the name your plugin
	 *
	 * @since     1.0.0
	 *
	 * @return    null    Return early if no settings page is registered.
	 */
	public function enqueue_admin_styles() {

		if ( ! isset( $this->plugin_screen_hook_suffix ) ) {
			return;
		}

		$screen = get_current_screen();
		if ( $this->plugin_screen_hook_suffix == $screen->id ) {
			wp_enqueue_style( $this->plugin_slug .'-admin-styles', plugins_url( 'css/admin.css', __FILE__ ), array(), Mareons_Anomalies_home::VERSION );
		}

	}

	/**
	 * Register and enqueue admin-specific JavaScript.
	 * 
	 * TODO:
	 *
	 * - Rename "Mareons_Anomalies_home" to the name your plugin
	 *
	 * @since     1.0.0
	 *
	 * @return    null    Return early if no settings page is registered.
	 */
	public function enqueue_admin_scripts() {

		if ( ! isset( $this->plugin_screen_hook_suffix ) ) {
			return;
		}

		$screen = get_current_screen();
		if ( $this->plugin_screen_hook_suffix == $screen->id ) {
			wp_enqueue_script( $this->plugin_slug . '-admin-script', plugins_url( 'js/admin.js', __FILE__ ), array( 'jquery' ), Mareons_Anomalies_Home::VERSION );
		}

	}

	/**
	 * Register the administration menu for this plugin into the WordPress Dashboard menu.
	 *
	 * @since    1.0.0
	 */
	public function add_plugin_admin_menu() {

		/*
		 * Add a settings page for this plugin to the Settings menu.
		 *
		 * NOTE:  Alternative menu locations are available via WordPress administration menu functions.
		 *
		 *        Administration Menus: http://codex.wordpress.org/Administration_Menus
		 *
		 * TODO:
		 *
		 * - Change 'Page Title' to the title of your plugin admin page
		 * - Change 'Menu Text' to the text for menu item for the plugin settings page
		 * - Change 'manage_options' to the capability you see fit
		 *   For reference: http://codex.wordpress.org/Roles_and_Capabilities
		 */
		$this->plugin_screen_hook_suffix = add_options_page(
			__( 'Page Title', $this->plugin_slug ),
			__( 'Menu Text', $this->plugin_slug ),
			'manage_options',
			$this->plugin_slug,
			array( $this, 'display_plugin_admin_page' )
		);

	}

	/**
	 * Render the settings page for this plugin.
	 *
	 * @since    1.0.0
	 */
	public function display_plugin_admin_page() {
		include_once( 'views/admin.php' );
	}

	/**
	 * Add settings action link to the plugins page.
	 *
	 * @since    1.0.0
	 */
	public function add_action_links( $links ) {

		return array_merge(
			array(
				'settings' => '<a href="' . admin_url( 'options-general.php?page=' . $this->plugin_slug ) . '">' . __( 'Settings', $this->plugin_slug ) . '</a>'
			),
			$links
		);

	}
	
	/* For adding custom field to home popup */
	function add_image_attachment_fields_to_edit($form_fields, $post) {
	  // $form_fields is a an array of fields to include in the attachment form
	  // $post is nothing but attachment record in the database
	  //     $post->post_type == 'attachment'
	  // attachments are considered as posts in WordPress. So value of post_type in wp_posts table will be attachment
	  // now add our custom field to the $form_fields array
	  // input type="text" name/id="attachments[$attachment->ID][custom1]"
	  $form_fields["mareon_link"] = array(
	    "label" => __("Mareon Link"),
	    "input" => "text", // this is default if "input" is omitted
	    "value" => get_post_meta($post->ID, "_mareon_link", true),
	                "helps" => __("Page link on homepage."),
	  );
	   return $form_fields;
	}
	
	function add_image_attachment_fields_to_save($post, $attachment) {
	  // $attachment part of the form $_POST ($_POST[attachments][postID])
	        // $post['post_type'] == 'attachment'
	  if( isset($attachment['mareon_link']) ){
	    // update_post_meta(postID, meta_key, meta_value);
	    update_post_meta($post['ID'], '_mareon_link', $attachment['mareon_link']);
	  }
	  return $post;
	}
	
	
}
