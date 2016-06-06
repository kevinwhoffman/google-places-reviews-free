<?php
/**
 * Plugin Name: Google Places Reviews
 * Plugin URI: http://wordimpress.com/plugins/google-places-reviews-pro/
 * Description: Display Google Places Reviews for one or many businesses anywhere on your WordPress site using an easy to use and intuitive widget.
 * Version: 1.4
 * Author: WordImpress
 * Author URI: https://wordimpress.com/
 * Text Domain: google-places-reviews
 * License: GPL2
 */


if ( ! class_exists( 'WP_Google_Places_Reviews_Free' ) ) {

	/**
	 * Class WP_Google_Places_Reviews_Free
	 */
	class WP_Google_Places_Reviews_Free {

		/**
		 * Plugin Instance
		 *
		 * @var null
		 */
		protected static $_instance = null;

		/**
		 * GPR_Plugin_Framework
		 *
		 * @var GPR_Plugin_Framework
		 */
		public $gpr_framework = null;

		/**
		 * Main Google Places Reviews Instance
		 *
		 * Ensures only one instance of GPR is loaded or can be loaded.
		 *
		 * @since 1.4
		 */
		public static function instance() {
			if ( is_null( self::$_instance ) ) {
				self::$_instance = new self();
			}

			return self::$_instance;
		}

		/**
		 * Class Constructor
		 */
		public function __construct() {

			add_action( 'init', array( $this, 'load_plugin_textdomain' ) );

			add_action( 'admin_enqueue_scripts', array( $this, 'options_scripts' ) );

			//Register the Google Places widget
			add_action( 'widgets_init', array( $this, 'setup_widget' ) );

			//Define Constants
			$home_url    = home_url();
			$plugins_url = plugins_url();

			if ( stripos( $home_url, 'https://' ) === false ) {
				$plugins_url = str_ireplace( 'https://', 'http://', $plugins_url );
			}

			define( 'GPR_PLUGIN_NAME', 'gpr' );
			define( 'GPR_PLUGIN_NAME_PLUGIN', plugin_basename( __FILE__ ) );
			define( 'GPR_PLUGIN_PATH', untrailingslashit( plugin_dir_path( __FILE__ ) ) );
			define( 'GPR_PLUGIN_URL', $plugins_url . '/' . basename( plugin_dir_path( __FILE__ ) ) );

		}

		/**
		 * Loads the plugin language files
		 *
		 * @access public
		 * @since  1.0
		 * @return void
		 */
		function load_plugin_textdomain() {

			// Set filter for plugins's languages directory
			$gpr_lang_dir = dirname( plugin_basename( __FILE__ ) ) . '/languages/';
			$gpr_lang_dir = apply_filters( 'gpr_languages_directory', $gpr_lang_dir );

			// Traditional WordPress plugin locale filter
			$locale = apply_filters( 'plugin_locale', get_locale(), 'google-places-reviews' );
			$mofile = sprintf( '%1$s-%2$s.mo', 'give', $locale );

			// Setup paths to current locale file
			$mofile_local  = $gpr_lang_dir . $mofile;
			$mofile_global = WP_LANG_DIR . '/google-places-reviews/' . $mofile;

			if ( file_exists( $mofile_global ) ) {
				// Look in global /wp-content/languages/google-places-reviews folder
				load_textdomain( 'google-places-reviews', $mofile_global );
			} elseif ( file_exists( $mofile_local ) ) {
				// Look in local location from filter `gpr_languages_directory`
				load_textdomain( 'google-places-reviews', $mofile_local );
			} else {
				// Load the default language files packaged up w/ Google Places Reviews
				load_plugin_textdomain( 'google-places-reviews', false, $gpr_lang_dir );
			}

		}

		/**
		 * Plugin Setup
		 */
		public function setup_widget() {

			// Include Core Framework class
			require_once GPR_PLUGIN_PATH . '/classes/core.php';

			// Create plugin instance
			$google_places_reviews = new GPR_Plugin_Framework( __FILE__ );

			// Include options set
			require_once GPR_PLUGIN_PATH . '/inc/options.php';

			// Create options page
			$google_places_reviews->add_options_page( array(), $google_places_reviews_options );

			// Make plugin meta translatable
			__( 'Google Places Reviews', 'google-places-reviews' );
			__( 'Devin Walker', 'google-places-reviews' );
			__( 'google-places-reviews', 'google-places-reviews' );

			//Include the widget
			if ( ! class_exists( 'Google_Places_Reviews' ) ) {
				require 'classes/widget.php';
			}

			//Admin only
			if ( is_admin() ) {
				//Deactivating normal activation banner for upgrade to Place ID banner
				require_once GPR_PLUGIN_PATH . '/inc/admin.php';

				//Display our upgrade notice
				require_once GPR_PLUGIN_PATH . '/inc/upgrades/upgrade-functions.php';
				require_once GPR_PLUGIN_PATH . '/inc/upgrades/upgrades.php';

			}

			register_widget( 'Google_Places_Reviews' );

			return $google_places_reviews;

		}


		/**
		 * Options Scripts
		 *
		 * @description: Custom JS/CSS for Options Page
		 *
		 * @param $hook
		 */
		function options_scripts( $hook ) {

			//Only on settings page
			if ( 'settings_page_googleplacesreviews' === $hook ) {
				wp_register_style( 'gpr_custom_options_styles', plugin_dir_url( __FILE__ ) . '/assets/css/options.css' );
				wp_enqueue_style( 'gpr_custom_options_styles' );
			}

		}

	}//end WP_Google_Places_Reviews_Free class

}//end If !class_exists

/**
 * Returns the main instance of Google Places Reviews.
 *
 * @since  1.4
 * @return WP_Google_Places_Reviews_Free()
 */
function WP_Google_Places_Reviews_Free() {
	return WP_Google_Places_Reviews_Free::instance();
}

WP_Google_Places_Reviews_Free();