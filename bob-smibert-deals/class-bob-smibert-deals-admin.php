<?php
/**
 * Bob Smibert Deals.
 *
 * @package   Bob_Smibert_Deals_Admin
 * @author    Your Name <email@example.com>
 * @license   GPL-2.0+
 * @link      http://example.com
 * @copyright 2013 Your Name or Company Name
 */

/**
 * Plugin class. This class should ideally be used to work with the
 * administrative side of the WordPress site.
 *
 * If you're interested in introducing public-facing
 * functionality, then refer to `class-bob-smibert-deals.php`
 *
 * TODO: Rename this class to a proper name for your plugin.
 *
 * @package Bob_Smibert_Deals_Admin
 * @author  Your Name <email@example.com>
 */
class Bob_Smibert_Deals_Admin {

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
		 * - Rename "Bob_Smibert_Deals" to the name of your initial plugin class
		 *
		 */
		$plugin = Bob_Smibert_Deals::get_instance();
		$this->plugin_slug = $plugin->get_plugin_slug();

		// Load admin style sheet and JavaScript.
		add_action( 'admin_enqueue_scripts', array( $this, 'enqueue_admin_styles' ) );
		add_action( 'admin_enqueue_scripts', array( $this, 'enqueue_admin_scripts' ) );

		// Add the options page and menu item.
		add_action( 'admin_menu', array( $this, 'add_plugin_admin_menu' ) );

		// Add an action link pointing to the options page.
		$plugin_basename = plugin_basename( plugin_dir_path( __FILE__ ) . $this->plugin_slug . '.php' );
		add_filter( 'plugin_action_links_' . $plugin_basename, array( $this, 'add_action_links' ) );
		
		add_action( 'admin_init', array( $this, 'prep_settings' ) );

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
	 * - Rename "Bob_Smibert_Deals" to the name your plugin
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
			wp_enqueue_style( $this->plugin_slug .'-admin-styles', plugins_url( 'css/admin.css', __FILE__ ), array(), Bob_Smibert_Deals::VERSION );
		}

	}

	/**
	 * Register and enqueue admin-specific JavaScript.
	 * 
	 * TODO:
	 *
	 * - Rename "Bob_Smibert_Deals" to the name your plugin
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
			wp_enqueue_script( $this->plugin_slug . '-admin-script', plugins_url( 'js/admin.js', __FILE__ ), array( 'jquery' ), Bob_Smibert_Deals::VERSION );
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
			__( 'Bob Smibert Deals', $this->plugin_slug ),
			__( 'Bob Smibert Deals', $this->plugin_slug ),
			'manage_options',
			$this->plugin_slug,
			array( $this, 'my_options_page' )
		);
	}
	
	function prep_settings() {
		register_setting( 'bob-smibert-deals-group', 'settings' );
		register_setting( 'bob-smibert-deals-group', 'student_settings' );	
		/*
		 * Monthly Deal
		 */
		add_settings_section( 
			'section-one' , 
			'Monthly Deal', 
			array( $this, 'section_one_callback' ), 
			$this->plugin_slug 
		);
		
		add_settings_field( 
			'deal-title', 
			'Monthly Deal Title',
			array( $this, 'simple_text_input' ), 
			$this->plugin_slug, 
			'section-one',
			array( 
				'id' => 'deal-title',
				'settings' => 'settings'
			)
		);
		
		add_settings_field(
			'deal-1',
			'Deal 1 Title',
			array( $this, 'my_text_input' ), 
			$this->plugin_slug, 
			'section-one',
			array(
				'id' => 'deal-1',
				'settings' => 'settings'
			)
		);
		add_settings_field(
			'deal-2',
			'Deal 2 Title',
			array( $this, 'my_text_input' ), 
			$this->plugin_slug, 
			'section-one',
			array( 
				'id' => 'deal-2',
				'settings' => 'settings'
			) 
		);
		add_settings_field( 
			'deal-3', 
			'Deal 3 Title',
			array( $this, 'my_text_input' ), 
			$this->plugin_slug, 
			'section-one',
			array( 
				'id' => 'deal-3',
				'settings' => 'settings'
			) 
		);
		
		/*
		 * Student Deal
		 */
		add_settings_section( 
			'section-student', 
			'Student Deal', 
			array( $this, 'section_student_callback' ), 
			$this->plugin_slug 
		);
		
		add_settings_field( 
			'deal-title', 
			'Student Deal Title',
			array( $this, 'simple_text_input' ), 
			$this->plugin_slug, 
			'section-student',
			array( 
				'id' => 'deal-title',
				'settings' => 'student_settings'
			)
		);
		
		add_settings_field(
			'deal-1',
			'Deal 1 Title',
			array( $this, 'my_text_input' ), 
			$this->plugin_slug, 
			'section-student',
			array(
				'id' => 'deal-1',
				'settings' => 'student_settings'
			)
		);
		add_settings_field(
			'deal-2',
			'Deal 2 Title',
			array( $this, 'my_text_input' ), 
			$this->plugin_slug, 
			'section-student',
			array( 
				'id' => 'deal-2',
				'settings' => 'student_settings'
			) 
		);
		add_settings_field( 
			'deal-3', 
			'Deal 3 Title',
			array( $this, 'my_text_input' ), 
			$this->plugin_slug, 
			'section-student',
			array( 
				'id' => 'deal-3',
				'settings' => 'student_settings'
			) 
		);	
	}
	
	function section_one_callback() {
	    echo 'Some help text goes here.';
	}
	
	function section_student_callback() {
	    echo 'Some help text goes here.';
	}
	
	function my_text_input( $args ) {
		$settings_name = esc_attr( $args['settings'] );
		$settings = (array) get_option( $settings_name );
		
		$id = esc_attr( $args['id'] );
	    $value = esc_attr( $settings[$id]['title'] );
	    echo "<input type='text' name='" . $settings_name . "[$id][title]' value='$value' />";
	    
	    $this->text_input(
	    	'$ per Session:',  	// label
	    	'per-amount',		// attr name
	    	$id,				// id from above,
	    	$settings_name,
	    	$settings
	    );
	    
	    $this->text_input(
	    	'# Free Sessions:',  // label
	    	'free',				// attr name
	    	$id,				// id from above
	    	$settings_name,
	    	$settings
	    );
	    
	    $this->text_input(
	    	'Paypal Amount:',  	// label
	    	'paypal',			// attr name
	    	$id,				// id from above
	    	$settings_name,
	    	$settings
	    );
	}
	
	function text_input($label, $attr, $id, $settings_name, $settings) {
		$name = $settings_name . "[$id][$attr]";
		$value = esc_attr( $settings[$id][$attr] );
		
		echo "<label for='$name'>$label</label>";
	    echo "<input type='text' name='$name' value='$value' />";
	}
	
	function simple_text_input( $args ) {
		$settings_name = esc_attr( $args['settings'] );
		$settings = (array) get_option( $settings_name );
		
		$id = esc_attr( $args['id'] );
	    $value = esc_attr( $settings[$id] );
		$name = $settings_name . "[$id]";
		
	    echo "<input type='text' name='$name' value='$value' />";
	}
	
	function my_options_page() {
	    ?>
	    <div class="wrap">
	        <h2>Bob Smiberts Deals</h2>
	        <form action="options.php" method="POST">
	            <?php settings_fields( 'bob-smibert-deals-group' ); ?>
	            <?php do_settings_sections( $this->plugin_slug ); ?>
	            <?php submit_button(); ?>
	        </form>
	    </div>
	    <?php
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

	/**
	 * Render the settings page for this plugin.
	 *
	 * @since    1.0.0
	 */
	public function initialize_theme_options() {
		// First, we register a section. This is necessary since all future options must belong to one.  
	    add_settings_section(
	    	'general_settings_section',         // ID used to identify this section and with which to register options  
	        'Deal Options',                  // Title to be displayed on the administration page  
	        array( $this, 'general_options_callback'), // Callback used to render the description of the section  
	        'general'                           // Page on which to add this section of options  
	    );  
	}

}
