<?php
/**
 * Bob Smibert Deals.
 *
 * @package   Bob_Smibert_Deals
 * @author    Your Name <email@example.com>
 * @license   GPL-2.0+
 * @link      http://example.com
 * @copyright 2013 Your Name or Company Name
 */

/**
 * Plugin class. This class should ideally be used to work with the
 * public-facing side of the WordPress site.
 *
 * If you're interested in introducing administrative or dashboard
 * functionality, then refer to `class-bob-smibert-deals-admin.php`
 *
 * TODO: Rename this class to a proper name for your plugin.
 *
 * @package Bob_Smibert_Deals
 * @author  Your Name <email@example.com>
 */
class Bob_Smibert_Deals {

	/**
	 * Plugin version, used for cache-busting of style and script file references.
	 *
	 * @since   1.0.0
	 *
	 * @var     string
	 */
	const VERSION = '1.0.0';

	/**
	 * TODO - Rename "bob-smibert-deals" to the name your your plugin
	 *
	 * Unique identifier for your plugin.
	 *
	 *
	 * The variable name is used as the text domain when internationalizing strings
	 * of text. Its value should match the Text Domain file header in the main
	 * plugin file.
	 *
	 * @since    1.0.0
	 *
	 * @var      string
	 */
	protected $plugin_slug = 'bob-smibert-deals';

	/**
	 * Instance of this class.
	 *
	 * @since    1.0.0
	 *
	 * @var      object
	 */
	protected static $instance = null;

	/**
	 * Initialize the plugin by setting localization and loading public scripts
	 * and styles.
	 *
	 * @since     1.0.0
	 */
	private function __construct() {

		// Load plugin text domain
		add_action( 'init', array( $this, 'load_plugin_textdomain' ) );

		// Activate plugin when new blog is added
		add_action( 'wpmu_new_blog', array( $this, 'activate_new_site' ) );

		// Load public-facing style sheet and JavaScript.
		add_action( 'wp_enqueue_scripts', array( $this, 'enqueue_styles' ) );
		add_action( 'wp_enqueue_scripts', array( $this, 'enqueue_scripts' ) );

		add_shortcode( 'bob_smibert_deals_monthly' , array( $this, 'shortcode_monthly' ) );
		add_shortcode( 'bob_smibert_deals_student' , array( $this, 'shortcode_student' ) );

	}

	/**
	 * Return the plugin slug.
	 *
	 * @since    1.0.0
	 *
	 *@return    Plugin slug variable.
	 */
	public function get_plugin_slug() {
		return $this->plugin_slug;
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
	 * Fired when the plugin is activated.
	 *
	 * @since    1.0.0
	 *
	 * @param    boolean    $network_wide    True if WPMU superadmin uses
	 *                                       "Network Activate" action, false if
	 *                                       WPMU is disabled or plugin is
	 *                                       activated on an individual blog.
	 */
	public static function activate( $network_wide ) {

		if ( function_exists( 'is_multisite' ) && is_multisite() ) {

			if ( $network_wide  ) {

				// Get all blog ids
				$blog_ids = self::get_blog_ids();

				foreach ( $blog_ids as $blog_id ) {

					switch_to_blog( $blog_id );
					self::single_activate();
				}

				restore_current_blog();

			} else {
				self::single_activate();
			}

		} else {
			self::single_activate();
		}

	}

	/**
	 * Fired when the plugin is deactivated.
	 *
	 * @since    1.0.0
	 *
	 * @param    boolean    $network_wide    True if WPMU superadmin uses
	 *                                       "Network Deactivate" action, false if
	 *                                       WPMU is disabled or plugin is
	 *                                       deactivated on an individual blog.
	 */
	public static function deactivate( $network_wide ) {

		if ( function_exists( 'is_multisite' ) && is_multisite() ) {

			if ( $network_wide ) {

				// Get all blog ids
				$blog_ids = self::get_blog_ids();

				foreach ( $blog_ids as $blog_id ) {

					switch_to_blog( $blog_id );
					self::single_deactivate();

				}

				restore_current_blog();

			} else {
				self::single_deactivate();
			}

		} else {
			self::single_deactivate();
		}

	}

	/**
	 * Fired when a new site is activated with a WPMU environment.
	 *
	 * @since    1.0.0
	 *
	 * @param    int    $blog_id    ID of the new blog.
	 */
	public function activate_new_site( $blog_id ) {

		if ( 1 !== did_action( 'wpmu_new_blog' ) ) {
			return;
		}

		switch_to_blog( $blog_id );
		self::single_activate();
		restore_current_blog();

	}

	/**
	 * Get all blog ids of blogs in the current network that are:
	 * - not archived
	 * - not spam
	 * - not deleted
	 *
	 * @since    1.0.0
	 *
	 * @return   array|false    The blog ids, false if no matches.
	 */
	private static function get_blog_ids() {

		global $wpdb;

		// get an array of blog ids
		$sql = "SELECT blog_id FROM $wpdb->blogs
			WHERE archived = '0' AND spam = '0'
			AND deleted = '0'";

		return $wpdb->get_col( $sql );

	}

	/**
	 * Fired for each blog when the plugin is activated.
	 *
	 * @since    1.0.0
	 */
	private static function single_activate() {
		// TODO: Define activation functionality here
	}

	/**
	 * Fired for each blog when the plugin is deactivated.
	 *
	 * @since    1.0.0
	 */
	private static function single_deactivate() {
		// TODO: Define deactivation functionality here
	}

	/**
	 * Load the plugin text domain for translation.
	 *
	 * @since    1.0.0
	 */
	public function load_plugin_textdomain() {

		$domain = $this->plugin_slug;
		$locale = apply_filters( 'plugin_locale', get_locale(), $domain );

		load_textdomain( $domain, trailingslashit( WP_LANG_DIR ) . $domain . '/' . $domain . '-' . $locale . '.mo' );
		load_plugin_textdomain( $domain, FALSE, basename( dirname( __FILE__ ) ) . '/languages/' );

	}

	/**
	 * Register and enqueue public-facing style sheet.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_styles() {
		wp_enqueue_style( $this->plugin_slug . '-plugin-styles', plugins_url( 'css/public.css', __FILE__ ), array(), self::VERSION );
	}

	/**
	 * Register and enqueues public-facing JavaScript files.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_scripts() {
		wp_enqueue_script( $this->plugin_slug . '-plugin-script', plugins_url( 'js/public.js', __FILE__ ), array( 'jquery' ), self::VERSION );
	}	
	
	public function shortcode_monthly() {
		$settings = get_option( 'settings' );

		dbgx_trace_var($settings);
		ob_start();
		?>
		<article id='monthly-deals' class=''>
		<h2><?php echo $settings['deal-title'] ?></h2>
		<ul>
			<li>
				<strong><?php echo $settings['deal-1']['title'] ?></strong>
				<p>@ $<?php echo $settings['deal-1']['per-amount'] ?>/session</p>
				<?php
					if ( $settings['deal-1']['free'] > 0 ) { ?>
						<p><?php echo $settings['deal-1']['free'] ?> Free Session(s)</p>
				<?php } ?>
			</li>
			<li>
				<strong><?php echo $settings['deal-2']['title'] ?></strong>
				<p>@ $<?php echo $settings['deal-2']['per-amount'] ?>/session</p>
				<?php
					if ( $settings['deal-2']['free'] > 0 ) { ?>
						<p><?php echo $settings['deal-2']['free'] ?> Free Session(s)</p>
				<?php } ?>
			</li><li>
				<strong><?php echo $settings['deal-3']['title'] ?></strong>
				<p>@ $<?php echo $settings['deal-3']['per-amount'] ?>/session</p>
				<?php
					if ( $settings['deal-3']['free'] > 0 ) { ?>
						<p><?php echo $settings['deal-3']['free'] ?> Free Session(s)</p>
				<?php } ?>
			</li>
		</ul>
	</article>
		
	<?php 
		$content = "[wp_cart:Session Deals:price:[Options";
		
		$free = $settings['deal-1']['free'] > 0 ? " + " . $settings['deal-1']['free'] . " Free Session(s)" : "";
		$content .= "|" . $settings['deal-1']['title'] . " @ $" . $settings['deal-1']['per-amount'] . "/session" . $free . "," . $settings['deal-1']['paypal'];
		
		$free = $settings['deal-2']['free'] > 0 ? "+ " . $settings['deal-2']['free'] . " Free Session(s)" : "";
		$content .= "|" . $settings['deal-2']['title'] . " @ $" . $settings['deal-2']['per-amount'] . "/session" . $free . "," . $settings['deal-2']['paypal'];
		
		$free = $settings['deal-3']['free'] > 0 ? "+ " . $settings['deal-3']['free'] . " Free Session(s)" : "";
		$content .= "|" . $settings['deal-3']['title'] . " @ $" . $settings['deal-3']['per-amount'] . "/session" . $free . "," . $settings['deal-3']['paypal'];
		$content .= "]:end]";
	
	echo print_wp_cart_action($content); ?>
	
	<p><span>note: all session packages have a 1 year expiry date once purchased</span></p>
	<?php 
		return ob_get_clean();
	}	
	
	public function shortcode_student() {
		$settings = get_option( 'student_settings' );

		dbgx_trace_var($settings);
		ob_start();
		?>
		<article id='monthly-deals' class=''>
		<h2><?php echo $settings['deal-title'] ?></h2>
		<ul>
			<li>
				<strong><?php echo $settings['deal-1']['title'] ?></strong>
				<p>@ $<?php echo $settings['deal-1']['per-amount'] ?>/session</p>
				<?php
					if ( $settings['deal-1']['free'] > 0 ) { ?>
						<p><?php echo $settings['deal-1']['free'] ?> Free Session(s)</p>
				<?php } ?>
			</li>
			<li>
				<strong><?php echo $settings['deal-2']['title'] ?></strong>
				<p>@ $<?php echo $settings['deal-2']['per-amount'] ?>/session</p>
				<?php
					if ( $settings['deal-2']['free'] > 0 ) { ?>
						<p><?php echo $settings['deal-2']['free'] ?> Free Session(s)</p>
				<?php } ?>
			</li><li>
				<strong><?php echo $settings['deal-3']['title'] ?></strong>
				<p>@ $<?php echo $settings['deal-3']['per-amount'] ?>/session</p>
				<?php
					if ( $settings['deal-3']['free'] > 0 ) { ?>
						<p><?php echo $settings['deal-3']['free'] ?> Free Session(s)</p>
				<?php } ?>
			</li>
		</ul>
	</article>
		
	<?php 
		$content = "[wp_cart:Student Deals:price:[Options";
		
		$free = $settings['deal-1']['free'] > 0 ? " + " . $settings['deal-1']['free'] . " Free Session(s)" : "";
		$content .= "|" . $settings['deal-1']['title'] . " @ $" . $settings['deal-1']['per-amount'] . "/session" . $free . "," . $settings['deal-1']['paypal'];
		
		$free = $settings['deal-2']['free'] > 0 ? "+ " . $settings['deal-2']['free'] . " Free Session(s)" : "";
		$content .= "|" . $settings['deal-2']['title'] . " @ $" . $settings['deal-2']['per-amount'] . "/session" . $free . "," . $settings['deal-2']['paypal'];
		
		$free = $settings['deal-3']['free'] > 0 ? "+ " . $settings['deal-3']['free'] . " Free Session(s)" : "";
		$content .= "|" . $settings['deal-3']['title'] . " @ $" . $settings['deal-3']['per-amount'] . "/session" . $free . "," . $settings['deal-3']['paypal'];
		$content .= "]:end]";
	
	echo print_wp_cart_action($content); ?>
	
	<p><span>note: all session packages have a 1 year expiry date once purchased</span></p>
	<?php 
		return ob_get_clean();
	}	


}