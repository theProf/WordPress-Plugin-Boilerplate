<?php
/**
 * Mareons Anomalies Gallery
 *
 * @package   Mareons_Anomalies_Gallery
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
 * functionality, then refer to `class-mareons-anomalies-gallery-admin.php`
 *
 * TODO: Rename this class to a proper name for your plugin.
 *
 * @package Mareons_Anomalies_Gallery
 * @author  theProf <theProf@gerggle.com>
 */
class Mareons_Anomalies_Gallery {

	/**
	 * Plugin version, used for cache-busting of style and script file references.
	 *
	 * @since   1.0.0
	 *
	 * @var     string
	 */
	const VERSION = '1.0.0';

	/**
	 * TODO - Rename "mareons-anomalies-gallery" to the name your your plugin
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
	protected $plugin_slug = 'mareons-anomalies-gallery';

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

		// create the shortcode
		add_shortcode( 'mareon_anomalies_gallery', array( $this, 'portfolio_gallery_shortcode') );
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
	
	// define the shortcode function
	public function portfolio_gallery_shortcode( $attr ) {
		$post = get_post();
dbgx_trace_var($post);
		static $instance = 0;
		$instance++;

		if ( ! empty( $attr['ids'] ) ) {
			// 'ids' is explicitly ordered, unless you specify otherwise.
			if ( empty( $attr['orderby'] ) )
				$attr['orderby'] = 'post__in';
			$attr['include'] = $attr['ids'];
		}
	
		// We're trusting author input, so let's at least make sure it looks like a valid orderby statement
		if ( isset( $attr['orderby'] ) ) {
			$attr['orderby'] = sanitize_sql_orderby( $attr['orderby'] );
			if ( !$attr['orderby'] )
				unset( $attr['orderby'] );
		}
	
		extract(shortcode_atts(array(
			'order'      => 'ASC',
			'orderby'    => 'menu_order ID',
			'id'         => $post->ID,
			'itemtag'    => 'dl',
			'icontag'    => 'dt',
			'captiontag' => 'dd',
			'columns'    => 3,
			'size'       => 'thumbnail',
			'include'    => '',
			'exclude'    => ''
		), $attr));
		
		$size = 'thumbnail';
	
		$id = intval($id);
		if ( 'RAND' == $order )
			$orderby = 'none';
	
		if ( !empty($include) ) {
			$_attachments = get_posts( array('include' => $include, 'post_status' => 'inherit', 'post_type' => 'attachment', 'post_mime_type' => 'image', 'order' => $order, 'orderby' => $orderby, 'size' => $size) );
	
			$attachments = array();
			foreach ( $_attachments as $key => $val ) {
				$attachments[$val->ID] = $_attachments[$key];
			}
		} elseif ( !empty($exclude) ) {
			$attachments = get_children( array('post_parent' => $id, 'exclude' => $exclude, 'post_status' => 'inherit', 'post_type' => 'attachment', 'post_mime_type' => 'image', 'order' => $order, 'orderby' => $orderby, 'size' => $size) );
		} else {
			$attachments = get_children( array('post_parent' => $id, 'post_status' => 'inherit', 'post_type' => 'attachment', 'post_mime_type' => 'image', 'order' => $order, 'orderby' => $orderby, 'size' => $size) );
		}		
		
		$ps_count = count( $attachments );
				
		$gallery .= '<h1 class="home-banner">We provide a specialized gift for you, your business and corporation</h1>';
		$gallery .= '<div id="heather-ruthig-gallery-'.$i.'" class="heather-ruthig-gallery">';
	
		$slideID = 0;
	
		if ( $attachments ) { //if attachments are found, run the gallery
			$gallery .= '<div class="gallery-thumbnails">';
			//begin the gallery loop
			foreach ( $attachments as $attachment ) {
				$current_class = 0 == $slideID ? "current" : "";
				$gallery .= '<div class="gallery-content gallery-content-thumbnail ' . $current_class . '">';
					 										
				$gallery .= '<a href="javascript: void(0);">';
				
				/*
				 * This is the part of the loop that actually returns the images
				 */
					
				$img =  wp_get_attachment_image_src( $attachment->ID, $size );
						
				$gallery .= '<img src="' . $img[0] . '" alt="' . $alttext . '"/>';		
										
				$gallery .= '</a>
				</div>
				';
				
				$slideID++;
						
			}  // end gallery loop
			$gallery .= '</div>';
		} // end if ( $attachments)
		
		// main images
		$size = 'fullsize'; 
		
		if ( !empty($include) ) {
			$_attachments = get_posts( array('include' => $include, 'post_status' => 'inherit', 'post_type' => 'attachment', 'post_mime_type' => 'image', 'order' => $order, 'orderby' => $orderby, 'size' => $size) );
	
			$attachments = array();
			foreach ( $_attachments as $key => $val ) {
				$attachments[$val->ID] = $_attachments[$key];
			}
		} elseif ( !empty($exclude) ) {
			$attachments = get_children( array('post_parent' => $id, 'exclude' => $exclude, 'post_status' => 'inherit', 'post_type' => 'attachment', 'post_mime_type' => 'image', 'order' => $order, 'orderby' => $orderby, 'size' => $size) );
		} else {
			$attachments = get_children( array('post_parent' => $id, 'post_status' => 'inherit', 'post_type' => 'attachment', 'post_mime_type' => 'image', 'order' => $order, 'orderby' => $orderby, 'size' => $size) );
		}		
		
	
		if ( $attachments ) { //if attachments are found, run the gallery
			$gallery .= '<div class="gallery-full">';
			$info .= '<div class="gallery-info">';
			$slideID = 0;
			//begin the gallery loop
			foreach ( $attachments as $attachment ) {
				$current_class = 0 == $slideID ? "current" : "";
				
				$alttext = get_post_meta( $attachment->ID, '_wp_attachment_image_alt', true );
		
				if ( ! $alttext ) {
					$alttext = $attachment->post_title;
				}
					
				$gallery .= '<div class="gallery-content ' . $current_class . '">';
				
				/*
				 * This is the part of the loop that actually returns the images
				 */
					
				$img =  wp_get_attachment_image_src( $attachment->ID, $size );
						
				$gallery .= '<img src="' . $img[0] . '" alt="' . $alttext . '" />';		
										
				$info .= '<div class="gallery-meta ' . $current_class . '">';
				/*
$title = $attachment->post_title;
				if ( $title ) { 
					$info .= '<p class="gallery-title">'.$title.'</p>'; 
				} 
*/
				
				$caption = $attachment->post_excerpt;
				if ( $caption ) { 
					$info .= '<p class="gallery-title">'.$caption.'</p>'; 
				}
				
				$description = $attachment->post_content;
				if ( $description ) { 
					$info .= '<div class="gallery-description">'. wpautop( $description ) .'</div>'; 
				}
				$info .= '</div>';
				
				$gallery .= '</div>
				';
				
				$slideID++;
						
			}  // end gallery loop
			$info .= '</div>';
			$gallery .= '</div>';
			$gallery .= $info;
		} // end if ( $attachments)
	
		$gallery .= "</div><!--#heather-ruthig-gallery-->";
				
		$gallery .='</div><!--#heather-ruthig-gallery-wrapper-->';
	
		$i++;
	
		return $gallery;	//that's the gallery
		
		
	} //ends the  function


}