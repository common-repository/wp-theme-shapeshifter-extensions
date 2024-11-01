<?php
# Check if This is read by WordPress.
if ( ! defined( 'ABSPATH' ) ) exit;

if ( ! class_exists( 'SSE_Icon_Manager' ) ) { 
class SSE_Icon_Manager extends SSE_Unique_Abstract {

	/**
	 * Static
	**/
		/**
		 * Instance
		 * @var SSE_Taxonomy_Editor
		**/
		protected static $instance = null;

		/**
		 * CSS Files
		**/
		protected static $css_files = array();

	/**
	 * Init
	**/
		/**
		 * Public Initialier
		 * @return SSE_Taxonomy_Editor
		**/
		public static function get_instance()
		{
			if ( null === self::$instance ) self::$instance = new Self();
			return self::$instance;
		}
		/**
		 * Constructor
		**/
		protected function __construct() {
			$this->init();
			$this->init_hooks();
		}

		function init() {

			// Register in minified form
				self::$css_files = array(
					'FontAwesome' => SHAPESHIFTER_THEME_THIRD_DIR_URI . 'font-awesome/css/font-awesome.min.css',
				);

		}

		function init_hooks()
		{

			$version = get_option( sse()->get_prefixed_option_name( 'version_upgraded_icon_array' ), '1.0.0' );
			if ( version_compare( $version, ShapeShifter_Extensions::VERSION, '<' ) ) {
				//add_action( 'admin_init', array( __CLASS__, 'set_icons' ) );
				add_action( 'all_admin_notices', array( __CLASS__, 'set_icons' ) );
			}

		}

		/**
		 * Save the Icons
		**/
		public static function set_icons() {

			// Get Icons
				// Old Icons
					$option_data_icons = sse()->get_option( 'icons' );
					$old_icons = $option_data_icons->get_data();

				// Current Icons
					$icons = SSE_Icon_Manager::get_icons();

			// Save when they are Different
				if ( is_array( $icons )
					&& 0 < count( $icons )
					&& $old_icons !== $icons
				) { 
					$option_data_icons = sse()->get_option( 'icons' );
					$option_data_icons->set_props( $icons );
					$option_data_icons->update();
					update_option( sse()->get_prefixed_option_name( 'version_upgraded_icon_array' ), ShapeShifter_Extensions::VERSION );
				}

		}

		/**
		 * Get the Icons
		**/
		public static function get_icons() {

			// Return
				return SSE_Icon_Manager::get_input_icons( self::$css_files );;
			
		}

	
	# Return Array of "Font Awesome Icons"
	# Like : '\fXXX' => '(fa-)something'
	public static function get_input_icons( $css_files ) {

		# Preparations
			if ( ! current_user_can( 'manage_options' ) ) {
				return;
			}

			$creds = SSE_Filesystem_Methods::ajax_init_filesystem( 'shapeshifter-save-icons' );
			if( ! $creds ) {
				return;
			}

		global $wp_filesystem;

		$icons = array();

		if ( is_array( $css_files ) ) { foreach( $css_files as $name => $file ) {

			# Regex to get Icons
			# First Class, Second Unicode
				$search = '/\.([^:]+):before\{content:\"\\\([a-z0-9]{4})\"}/';

			# Get Text from CSS Files// CSSファイルからテキストを取得
				$css = $wp_filesystem->get_contents( $file );

			if ( preg_match_all( $search, $css, $matches ) ) {

				for( $i = 0; $i < count( $matches[1] ); $i++ ) {

					$key = sanitize_text_field( $matches[ 2 ][ $i ] );
					$val = sanitize_text_field( $matches[ 1 ][ $i ] );

					$icons[ $name ][ $key ] = $val;

				}

				asort( $icons[ $name ] );

			}

		} }

		return $icons;

	} 

}

}
