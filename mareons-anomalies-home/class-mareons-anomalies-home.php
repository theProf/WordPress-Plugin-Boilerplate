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
 * public-facing side of the WordPress site.
 *
 * If you're interested in introducing administrative or dashboard
 * functionality, then refer to `class-mareons-anomalies-home-admin.php`
 *
 * TODO: Rename this class to a proper name for your plugin.
 *
 * @package Mareons_Anomalies_Home
 * @author  theProf <theProf@gerggle.com>
 */
class Mareons_Anomalies_Home {

	/**
	 * Plugin version, used for cache-busting of style and script file references.
	 *
	 * @since   1.0.0
	 *
	 * @var     string
	 */
	const VERSION = '1.0.0';

	/**
	 * TODO - Rename "mareons-anomalies-home" to the name your your plugin
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
	protected $plugin_slug = 'mareons-anomalies-home';

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

		/*
		 * Custom Filters
		 */
		add_filter( 'home_style', array( $this, 'test_filter' ) );
/* 		add_filter( 'get_post_home', array( $this, 'test_filter' ) ); */

		// create the shortcode
		add_shortcode( 'mareon_anomalies_home', array( $this, 'portfolio_home_shortcode') );
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

	/**
	 * NOTE:  Filters are points of execution in which WordPress modifies data
	 *        before saving it or sending it to the browser.
	 *
	 *        Filters: http://codex.wordpress.org/Plugin_API#Filters
	 *        Reference:  http://codex.wordpress.org/Plugin_API/Filter_Reference
	 *
	 * @since    1.0.0
	 */
	public function test_filter($html_style) {
		dbgx_trace_var( $html_style );
		dbgx_trace_var( $post );
		dbgx_trace_var( $posts );
		dbgx_trace_var( $page );
	}
	
	
	// define the shortcode function
	public function portfolio_home_shortcode( $atts ) {
		
		STATIC $i=0;
		
		//has a custom post id been declared or should we use current page ID?
		if ( ! $id ) { $id = get_the_ID(); }
	
		//count the attachments
		$attachments = get_children( array ( 'post_parent' => $id, 'post_type' => 'attachment', 'post_mime_type' => 'image' ) );
		
		$ps_count = count( $attachments );
				
		$html .= '<div id="mareon-anomalies-home-'.$i.'" class="mareon-anomalies-home">';
	
		$slideID = 0;
		$size = 'medium';
		
		$attachments = get_posts( array( 'order'          => 'ASC',
			'orderby' 		 => 'menu_order ID',
			'post_type'      => 'attachment',
			'post_parent'    => $id,
			'post_mime_type' => 'image',
			'post_status'    => null,
			'numberposts'    => -1,
			'size'			 => $size) );
	
		if ( $attachments ) { //if attachments are found, run the home
		
			//begin the home loop
			foreach ( $attachments as $attachment ) {
					
				$alttext = get_post_meta( $attachment->ID, '_wp_attachment_image_alt', true );
		
				if ( ! $alttext ) {
					$alttext = $attachment->post_title;
				}
					
				$html .= '<div class="home-content">';
					 
				$imagelink = get_post_meta( $attachment->ID, '_mareon_link', true );
				
				if ( ! $imagelink ) { 
					$imagelink = '/' . $attachment->post_title;
				}
										
				$html .= '<a href="'.$imagelink.'">';
				
				/*
				 * This is the part of the loop that actually returns the images
				 */
					
				$img =  wp_get_attachment_image_src( $attachment->ID, $size );
						
				$html .= '<img src="' . $img[0] . '" alt="' . $alttext . '" />';		
										
				$html .= '<div class="home-meta">';
				$title = $attachment->post_title;
				if ( $title ) { 
					$html .= '<p class="home-title">'.$title.'</p>'; 
				} 
				
				$caption = $attachment->post_excerpt;
				if ( $caption ) { 
					$html .= '<p class="home-caption">'.$caption.'</p>'; 
				}
				
				$description = $attachment->post_content;
				if ( $description ) { 
					$html .= '<div class="home-description">'. wpautop( $description ) .'</div>'; 
				}
				
				$html .= '</div>
				</a>
				</div>
				';
				
				$slideID++;
						
			}  // end home loop
		} // end if ( $attachments)
	
		$html .= "</div><!--#mareon-anomalies-home-->";
				
		$html .='</div><!--#mareon-anomalies-home-wrapper-->';
	
		$i++;
	
		return $html;	//that's the home
		
		
	} //ends the portfolio_shortcode function
	
	

}