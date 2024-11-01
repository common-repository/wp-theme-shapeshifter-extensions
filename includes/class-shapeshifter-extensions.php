<?php
if( ! defined( 'ABSPATH' ) ) exit;
/**
 * WP Theme ShapeShifter Extensions
 * 
 * Constants
 * 	SHAPESHIFTER_IS_LAZYLOAD_ON : Should Override this
 * 
 * 	Options 
 * 		SHAPESHIFTER_EXTENSIONS_OPTION : "shapeshifter_" ( = SHAPESHIFTER_EXTENSIONS_OPTION )
 * 		@Theme ShapeShifter Uses "shapeshifter_link_pages_select" : Default : "number"
 * 
 * 	Post Meta
 * 		SHAPESHIFTER_EXTENSIONS_POST_META : "_shapeshifter_" ( = SHAPESHIFTER_THEME_POST_META )
 * 		SSE_THEME_POST_META               : "_" . THEME_NAME_WITH_UNDERSCORE_IN_UPPER . "_"
 * 
 * 
 * @todo 
**/
final class ShapeShifter_Extensions {

	/**
	 * Consts
	**/
		const NAME       = 'WP Theme ShapeShifter Extensions';
		const VERSION    = '1.2.7';
		const TEXTDOMAIN = 'wp-theme-shapeshifter-extensions';

		const REQUIRED_THEME_VERSION       = '1.2.0';
		const REQUIRED_THEME_VERSION_LIMIT = '1.3.0';

		const PLUGIN_DIR_NAME = 'wp-theme-shapeshifter-extensions';

		const UNIQUE_KEY       = 'sse';
		const OPTION_PREFIX    = 'sse_';
		const POST_META_PREFIX = '_sse_';

	/**
	 * Static
	**/
		public static $instance = null;

	/**
	 * Properties
	**/
		/**
		 * Options
		 * @var array
		**/
		public $options = array();

		/**
		 * Get Options
		 * @return mixed
		**/
		public function get_options()
		{
			return $this->get_option_manager()->get_options();
		}

		/**
		 * Get Option
		 * @param string $key
		 * @return bool|mixed Returns false for errors
		**/
		public function get_option( $key = '' )
		{
			return $this->get_option_manager()->get_option( $key );
		}

	/**
	 * Instances
	**/
		/**
		 * Mobile Detect
		 * @var Mobile_Detect $mobile_detect
		**/
		protected $mobile_detect = null;

		/**
		 * Get Mobile Detect
		 * @return Mobile_Detect
		**/
		public function get_mobile_detect()
		{
			return $this->mobile_detect;
		}

		/**
		 * Theme Mod Manager
		 * @var SSE_Theme_Mod_Manager
		**/
		protected $theme_mod_manager = null;

		/**
		 * Get Theme Mod Manager
		 * @return SSE_Theme_Mod_Manager
		**/
		public function get_theme_mod_manager()
		{
			return $this->theme_mod_manager;
		}

		/**
		 * Option Manager
		 * @var SSE_Option_Manager
		**/
		protected $option_manager = null;

		/**
		 * Get Option Manager
		 * @return SSE_Option_Manager
		**/
		public function get_option_manager()
		{
			return $this->option_manager;
		}

		/**
		 * Widget Area Manager
		 * @var SSE_Widget_Manager
		**/
		protected $widget_manager = null;

		/**
		 * Get Widget Manager
		 * @return SSE_Widget_Manager
		**/
		public function get_widget_manager()
		{
			return $this->widget_manager;
		}

		/**
		 * Widget Area Manager
		 * @var SSE_Widget_Area_Manager
		**/
		protected $widget_area_manager = null;

		/**
		 * Get Widget Area Manager
		 * @return SSE_Widget_Area_Manager
		**/
		public function get_widget_area_manager()
		{
			return $this->widget_area_manager;
		}

		/**
		 * Style Manager
		 * @var SSE_Style_Manager
		**/
		protected $style_manager = null;

		/**
		 * Get Style Manager
		 * @return SSE_Style_Manager
		**/
		public function get_style_manager()
		{
			return $this->style_manager;
		}

		/**
		 * Frontend Manager
		 * @var SSE_Frontend_Manager
		**/
		protected $frontend_manager = null;

		/**
		 * Get Frontend Manager
		 * @return SSE_Frontend_Manager
		**/
		public function get_frontend_manager()
		{
			return $this->frontend_manager;
		}

		/**
		 * Admin Manager
		 * @var SSE_Admin_Manager
		**/
		protected $admin_manager = null;

		/**
		 * Get Admin Manager
		 * @return SSE_Admin_Manager
		**/
		public function get_admin_manager()
		{
			return $this->admin_manager;
		}

		/**
		 * Notification Manager
		 * @var SSE_Notification_Manager
		**/
		protected $notification_manager = null;

		/**
		 * Get Notification Manager
		 * @return SSE_Notification_Manager
		**/
		public function get_notification_manager()
		{
			return $this->notification_manager;
		}

		/**
		 * Add notice message
		 * @param string $text
		 * @param string $type
		 * @return bool
		**/
		public function add_notice_message( $text, $type = 'notice' )
		{
			if ( null !== $this->get_notification_manager() )
				$this->get_notification_manager()->add_notice_message( $text, $type );
		}

		/**
		 * Theme Customizer
		 * @var SSE_Theme_Customizer
		**/
		protected $theme_customizer = null;

		/**
		 * Get Theme Customizer
		 * @return SSE_Theme_Customizer
		**/
		public function get_theme_customizer()
		{
			return $this->theme_customizer;
		}

		/**
		 * ShapeShifter CSS
		 * @var array
		**/
		protected $ss_css_list_keep_enqueued = array( 'shapeshifter-style' );

		/**
		 * Frontend CSS
		 * @var array
		**/
		protected $frontend_css = array(
/*
			'slider-pro' => array( 
				'src' => SHAPESHIFTER_EXTENSIONS_DIR_URL . 'includes/3rd/slider-pro-master/dist/css/slider-pro.min.css' 
			),
			'vegas' => array( 
				'src' => SHAPESHIFTER_EXTENSIONS_DIR_URL . 'includes/3rd/vegas/vegas.min.css' 
			),
			'icomoon' => array( 
				'src' => SHAPESHIFTER_EXTENSIONS_DIR_URL . 'includes/3rd/icomoon/style.min.css' 
			),
			'shapeshifter-extensions-frontend-general' => array(
				'src' => SHAPESHIFTER_EXTENSIONS_DIR_URL . 'assets/css/style.css' 
			)
*/
		);

		/**
		 * Get Frontend CSS
		 * @return array
		**/
		public function get_frontend_css()
		{
			return $this->frontend_css;
		}

	#
	# Settings
	#
		/**
		 * Cloning is forbidden.
		 * @since 1.0
		 */
		public function __clone() {
			_doing_it_wrong( __FUNCTION__, esc_html__( 'DO NOT Clone.', ShapeShifter_Extensions::TEXTDOMAIN ), '1.0.0' );
		}

		/**
		 * Unserializing instances of this class is forbidden.
		 * @since 1.0
		 */
		public function __wakeup() {
			_doing_it_wrong( __FUNCTION__, esc_html__( 'DO NOT Unserialize', ShapeShifter_Extensions::TEXTDOMAIN ), '1.0.0' );
		}

		#
		# Activate
		#
			/**
			 * Method Called by Activation
			 * 
			 * @uses $this->setup_constants()
			 * @uses $this->setup_vars()
			 * @uses $this->second_init()
			 * @uses SSE_Admin_Manager::set_icons()
			 * @uses SSE_TinyMCE_Manager::save_editor_styles()
			**/
			function activate()
			{

				//$this->before_setup_theme();

				//$this->include_required_files();

				//include_once( 'exec/include/required-files.php' );

				// First Setup
					//$this->setup_constants();

					//$this->setup_vars();

					//$this->second_init();

				// Set Icons and Styles
					//add_action( 'wp_footer', array( 'SSE_TinyMCE_Manager', 'save_editor_styles' ) );
					//add_action( 'admin_notice', array( 'SSE_TinyMCE_Manager', 'save_editor_styles' ) );
					//SSE_Admin_Manager::set_icons();
					//SSE_TinyMCE_Manager::save_editor_styles();

			}

		#
		# Update
		#
			/**
			 * Method Called by Upgrade
			 * 
			 * @param object $upgrader_object
			 * @param array  $hook_extra
			 * 
			 * @uses $this->activate()
			**/
			function upgrader_process_complete( $upgrader_object, $hook_extra )
			{

				# Plugin Info
					$current_plugin_path_name = plugin_basename( SHAPESHIFTER_EXTENSIONS_MAIN_FILE );
					if ( $hook_extra['action'] == 'update' && $hook_extra['type'] == 'plugin' ) {
						foreach ( $hook_extra['plugins'] as $each_plugin ) {
							if ( $current_plugin_path_name === $each_plugin ) {

								$this->activate();
								do_action( 'sse_upgrader_process_complete', $upgrader_object, $hook_extra, $this );

							}
						}
					}

			}

	#
	# Tools
	#
		/**
		 * Define Constant
		 * 
		 * @param string $name
		 * @param bool|int|string
		 * 
		 * @return bool
		**/
		private function define_const( $name, $value ) {

			// Check Name
				if( empty( $name ) || ! is_string( $name ) ) {
					return false;
				}

			// Check Value
				if( ! isset( $value ) || is_array( $value ) || is_object( $value ) ) {
					return false;
				}

			// Exec
				if( ! defined( $name ) ) {
					define( $name, $value );
					return true;
				}

			// End
				return false;

		}

	#
	# Init
	#
		/**
		 * Public Initializer
		**/
		public static function get_instance() {
			if ( null === self::$instance ) self::$instance = new Self();
			return self::$instance;
		}

		/**
		 * Constructor
		**/
		protected function __construct()
		{

			// Activate
				register_activation_hook( SHAPESHIFTER_EXTENSIONS_MAIN_FILE, array( $this, 'activate' ) );
//				add_action( 'upgrader_process_complete', array( $this, 'upgrader_process_complete' ), 10, 2 );

			// TextDomain
				load_plugin_textdomain(
					ShapeShifter_Extensions::TEXTDOMAIN,
					false,
					dirname( plugin_basename( SHAPESHIFTER_EXTENSIONS_MAIN_FILE ) ) . '/languages'
				);

			// Check if Using Theme "ShapeShifter" or the child theme
				$shapeshifter_extension_theme = wp_get_theme();
				if ( ! ( $shapeshifter_extension_theme['Name'] === 'ShapeShifter'
					|| $shapeshifter_extension_theme['Template'] === 'shapeshifter'
				) ) return;
				unset( $shapeshifter_extension_theme );

			// Theme Version Check
				$theme_shapeshifter = wp_get_theme( 'shapeshifter' );
				if ( version_compare( $theme_shapeshifter['Version'], ShapeShifter_Extensions::REQUIRED_THEME_VERSION, '<' ) ) { unset( $theme_shapeshifter ); return; }
				if ( version_compare( $theme_shapeshifter['Version'], ShapeShifter_Extensions::REQUIRED_THEME_VERSION_LIMIT, '>=' ) ) { unset( $theme_shapeshifter ); return; }
				unset( $theme_shapeshifter );

			// Define
			// First Setup
			$this->init_hooks();

		}

		/**
		 * Init Hooks
		**/
		private function init_hooks()
		{

			// Before the Theme Setup
				// Actions

			// 
			// Theme classes are setup
					// Initializations
					add_action( 'after_setup_theme', array( $this, 'before_setup_theme' ), 1 );

					// Include files
					add_action( 'shapeshifter_action_include_required_files', array( $this, 'include_required_files' ) );

				// Global End Hook
					// Unset
					add_filter( 'shapeshifter_filter_data_registered_css', array( $this, 'filter_shapeshifter_css' ), 10, 2 );
					// CSS JS Register
					add_action( 'wp_enqueue_scripts', array( $this, 'register_scripts' ) );
					add_action( 'admin_enqueue_scripts', array( $this, 'register_scripts' ) );
					add_action( 'customize_preview_init', array( $this, 'register_scripts' ) );
					add_action( 'customize_controls_print_footer_scripts', array( $this, 'register_scripts' ) );

				// Filters
					// Title
					add_filter( 'wp_title', array( $this, 'title_filter' ), 10, 2 );

					// Post Slug
					add_filter( 'wp_unique_post_slug', array( $this, 'auto_post_slug' ), 10, 4 );

					// Excerpt Length
					add_filter( 'excerpt_length', array( $this, 'excerpt_length' ), 999 );
					add_filter( 'shapeshifter_filter_excerpt_length', array( $this, 'excerpt_length' ) );

					// Font Families
					add_filter( 'shapeshifter_filter_font_families', array( $this, 'shapeshifter_extensions_filter_font_families' ) );

					// AJAX Class
					add_filter( 'shapeshifter_filters_class_post_list_maybe_ajax', array( $this, 'shapeshifter_filters_class_post_list_maybe_ajax' ) );

					// Nav Menu Walker Class
					add_filter( 'shapeshifter_filters_walker_nav_menu_instance', array( $this, 'filter_walker_nav_menu_instance' ), 10, 2 );

					// Lazyload
					add_filter( 'shapeshifter_filter_is_lazylaod_on', array( $this, 'filter_lazyload_bool' ), 10, 1 );

			// After Theme Setup

		}

	/**
	 * First Initializations
	**/
		/**
		 * Init
		**/
		function before_setup_theme() {

			// Constants
			include_once( 'exec/define/required-consts.php' );

			// Vars
			$this->setup_vars();

			// Theme Supports
			$this->add_theme_supports();

		}

			/**
			 * Setup Vars
			**/
			function setup_vars() {

				// Options
					// Generals
						$this->options['general'] = wp_parse_args( get_option( sse()->get_prefixed_option_name( 'general' ) ), array(
							'default_settings_tab' => 'general-settings'
						) );
						$this->options['not_display_post_formats'] = wp_parse_args( get_option( sse()->get_prefixed_option_name( 'not_display_post_formats' ) ), array(
							'aside' => false,
							'gallery' => false,
							'image' => false,
							'link' => false,
							'quote' => false,
							'status' => false,
							'video' => false,
							'audio' => false,
							'chat' => false,
						) );
						$this->options['remove_action'] = wp_parse_args( get_option( sse()->get_prefixed_option_name( 'remove_action' ) ), array(
							'rsd_link' => false,
							'wlwmanifest_link' => false,
							'wp_generator' => false,
							'feed_links_extra' => false,
							'feed_links' => false,
							'index_rel_link' => false,
							'parent_post_rel_link' => false,
							'start_post_rel_link' => false,
							'adjacent_posts_rel_link_wp_head' => false,
						) );

					// Speed Adjust
						$this->options['speed_adjust'] = wp_parse_args( get_option( sse()->get_prefixed_option_name( 'speed_adjust' ) ), array(
							'style_min' 			=> false,
							'async_script_on'		=> false,
							'async_script_tags' 	=> 'jquery,sse-general-methods,magnific-popup,slider-pro,vegas,shapeshifter-animate,shapeshifter-javascripts,shapeshifter-widget-slide-gallery',
							'lazy_load' 			=> false,
							'merge_enqueued_css'	=> false,
							'merge_enqueued_js'	    => false,
							'ajax_load_posts'	    => false,
							'pjax_switch' 			=> false,
							'pjax_reload_codes' 	=> '',
						) );

							# AJAX Load
								if ( ! defined( 'SHAPESHIFTER_IS_AJAX_LOAD_ON' ) ) define( 'SHAPESHIFTER_IS_AJAX_LOAD_ON', ( bool ) $this->options['speed_adjust']['ajax_load_posts'] );

							# lazy_load Constant
								if( ! defined( 'SHAPESHIFTER_IS_LAZYLOAD_ON' ) ) define( 'SHAPESHIFTER_IS_LAZYLOAD_ON', ( bool ) $this->options['speed_adjust']['lazy_load'] );
								if( ! defined( 'SSE_IS_LAZYLOAD_ON' ) ) define( 'SSE_IS_LAZYLOAD_ON', ( bool ) $this->options['speed_adjust']['lazy_load'] );

					// Widget Areas
						$this->options['widget_areas_general'] = get_option( sse()->get_prefixed_theme_option_name( 'widget_areas_general' ), array(
							'num' => 1,
						) );
						if( isset( $this->options['widget_areas_general']['num'] ) )
							$this->options['widget_areas_general']['num'] = intval( $this->options['widget_areas_general']['num'] );

						$this->options['widget_areas'] = get_option( sse()->get_prefixed_theme_option_name( 'widget_areas' ) );

					// SEO
						$this->options['seo'] = wp_parse_args( get_option( sse()->get_prefixed_option_name( 'seo' ) ), array(
							'json_ld_markup_on' => false,
							'json_ld_logo' => array( SSE_THEME_ROOT_URI . '/screenshot.png', 800, 696 ),
							'insert_prev_next_link' => false,
							'canonical_link_on' => false,
							'meta_robots_on' => false,
							'meta_description_on' => false,
							'meta_keywords_on' => false,
							'twitter_card_on' => false,
							'twitter_card_account' => '',
							'open_graph_on' => false,
							'tc_og_image' => '',
							'google_plus_url' => '',
						) );

					// Auto Insert
						$this->options['auto_insert'] = wp_parse_args( get_option( sse()->get_prefixed_option_name( 'auto_insert' ) ), array(
							'excerpt_length' => 200,
							'content_editor' => '',
							'header_code' => '',
							'after_start_body_code' => '',
							'footer_code' => '',
						) );

					// Others
						$this->options['others'] = wp_parse_args( get_option( sse()->get_prefixed_option_name( 'others' ) ), array(
							'reset_page_view_count' => true,
							'auto_page_view_count_reset' => 'no',
							'pixabay_key' => ''
						) );

					// Fonts
						$this->options['fonts_general'] = get_option( sse()->get_prefixed_option_name( 'fonts_general' ), array(
							'num' => 1,
						) );
						if( isset( $this->options['fonts_general']['num'] ) )
							$this->options['fonts_general']['num'] = intval( $this->options['fonts_general']['num'] );
						$this->options['fonts'] = wp_parse_args( get_option( sse()->get_prefixed_option_name(  'fonts' ) ), array(
							1 => array( 
								'font-family' => '',
								'src' => array(),
							)
						) );

			}

			/**
			 * Add Theme Supports
			**/
			function add_theme_supports() {

				// General Supports
				add_theme_support( 
					'post-formats', 
					array( 'aside', 'gallery', 'image', 'link', 'quote', 'status', 'video', 'audio', 'chat' ) 
				);

			}

			/**
			 * Register Scripts
			**/
				/**
				 * Filter Frontend CSS
				**/
				public function filter_shapeshifter_css( $frontend_css, $shapeshifter_obj )
				{
					if ( is_admin() ) {
						return $frontend_css;
					}

					$frontend_css_holder = $target_css_handlers = array();
					foreach ( $frontend_css as $css_handler => $css_data ) {

						// Remove and set
						if ( ! in_array( $css_handler, $this->ss_css_list_keep_enqueued ) ) {

							array_push( $target_css_handlers, $css_handler );
							$css_data['dep'] = array();
							$frontend_css_holder[ $css_handler ] = $css_data;
							unset( $frontend_css[ $css_handler ] );

						}

					}

					foreach ( $frontend_css as $css_handler => $css_data ) {
						$dep = is_array( $css_data['dep'] ) ? $css_data['dep'] : array();
						if ( 0 >= count( $dep ) ) continue;
						foreach ( $dep as $each_dep ) {
							if ( in_array( $each_dep, $target_css_handlers ) ) {
								$dep_key = array_search( $each_dep, $css_data['dep'] );
								unset( $css_data['dep'][ $dep_key ] );//= null;
							}
						} 
						$frontend_css[ $css_handler ] = $css_data;
					}

					foreach ( $this->frontend_css as $css_handler => $css_data ) {
						$frontend_css_holder[ $css_handler ] = $css_data;
					}

					$this->frontend_css = $frontend_css_holder;

					return $frontend_css;

				}

				/**
				 * Register Scripts
				**/
				function register_scripts() {

					// CSS
					include_once( 'exec/register/required-styles.php' );

					// JS
					include_once( 'exec/register/required-scripts.php' );

				}

			/**
			 * filters
			**/
				/**
				 * Title Filter
				 * 
				 * @param string $title
				 * @param string $sep
				 * 
				 * @return string
				**/
				function title_filter( $title, $sep ) {

					global $page, $paged;
					if( is_home() || is_front_page() ) {

						$title = SITE_NAME . ' ' . $sep . ' ' . SITE_DESCRIPTION;

					}

					elseif( is_singular() ) {
						global $post;
						$title = get_the_title( $post->ID ) . ' ' . $sep . ' ' . SITE_NAME;
					}

					elseif( is_archive() ) {

						global $pages;
						return $title;

					}

					elseif( is_404() ) {
						$title = '404 ' . $sep . ' ' . SITE_NAME;
					}

					// After Page 2
					if( $paged >= 2 || $page >= 2 ) { 
						$title .= $sep . max( $paged, $page ) . 'ページ';
					}

					return $title;

				}

				/**
				 * Filter Method for Post Slug of Permalink
				 * 
				 * @param string $slug
				 * @param int    $post_id
				 * @param string $post_status
				 * @param string $post_type
				 * 
				 * @return string
				**/
				function auto_post_slug( $slug, $post_id, $post_status, $post_type ) { 

					if( ! is_admin() ) 
						return $slug;

					if( ! in_array( $post_type, array( 'post' ) ) ) 
						return $slug;

					if ( preg_match( '/(%[0-9a-f]{2})+/', $slug ) ) 
						$slug = utf8_uri_encode( $post_type ) . '-' . $post_id;

					return $slug;

				}

				/**
				 * Excerpt Length
				 * 
				 * @param int $length
				 * 
				 * @return int
				**/
				function excerpt_length( $length ) {

					$length = absint( $this->options['auto_insert']['excerpt_length'] );

					return absint( $length );

				}

				/**
				 * Font Families
				 * 
				 * @param array $font_families
				 * 
				 * @return array
				**/
				function shapeshifter_extensions_filter_font_families( $font_families ) {

					$saved_font_families = get_option( sse()->get_prefixed_option_name( 'custom_fonts' ), array() );

					if( is_array( $saved_font_families ) && ! empty( $saved_font_families ) ) {
						foreach( $saved_font_families as $index => $font_file ) {
							$index = esc_attr( $index );
							$font_families[ $index ] = $index;
						}
					}

					return $font_families;

				}

				/**
				 * AJAX Class
				 * 
				 * @param string $class
				 * 
				 * @return string
				**/
				function shapeshifter_filters_class_post_list_maybe_ajax( $class ) {

					if( SHAPESHIFTER_IS_AJAX_LOAD_ON ) {
						return 'ajax-post-list';
					}

					return $class;

				}

				/**
				 * Nav Menu Walker Class
				 * 
				 * @param obj    $instance
				 * @param string $theme_location
				 * 
				 * @return obj
				**/
				function filter_walker_nav_menu_instance( $instance, $theme_location ) {

					if( $theme_location === 'footer_nav'
						|| $theme_location === 'mobile_nav_menu'
					) {
						return $instance;
					}

					return new SSE_Walker_Nav_Menu;

				}

			/**
			 * Check if SSE Lazyload is on
			 * 
			 * @param bool $is_lazyload_on
			 * 
			 * @return bool
			**/
			function filter_lazyload_bool( $is_lazyload_on ) {

				if ( SSE_IS_LAZYLOAD_ON ) {
					return true;
				}

				return false;

			}

	/**
	 * Include required files
	**/
		/**
		 * Include required files
		**/
		public function include_required_files()
		{
			// Include
			include_once( 'exec/include/required-files.php' );

			// Detect Consts
			$this->setup_detect_consts();

			// Init
			$this->init_classes();

		}

		/**
		 * Init Trigger
		**/
		private function init_classes() {
			// Deprecated Manager
			if ( is_admin() ) {
				$this->deprecated_manager = SSE_Deprecated_Manager::get_instance();
			}

			// Data
				$this->theme_mod_manager = SSE_Theme_Mod_Manager::get_instance();
				$this->option_manager    = SSE_Option_Manager::get_instance();

			// Global
				$this->page_view_counter   = SSE_Page_View_Counter::get_instance();
				$this->shortcode_manager   = SSE_Shortcode_Manager::get_instance();
				$this->widget_manager      = SSE_Widget_Manager::get_instance();
				$this->widget_area_manager = SSE_Widget_Area_Manager::get_instance();

			// Frontend
				$this->style_manager    = SSE_Styles_Manager::get_instance();
				$this->frontend_manager = SSE_Frontend_Manager::get_instance();

			// Admin
			if( is_admin() ) {
				$this->tinymce_manager      = SSE_TinyMCE_Manager::get_instance();
				$this->admin_manager        = SSE_Admin_Manager::get_instance();
				$this->notification_manager = SSE_Notification_Manager::get_instance();
			}

			// Customizer
			if( is_customize_preview() ) {
				$this->theme_customizer = SSE_Theme_Customizer::get_instance();
			}

		}

		/**
		 * Setup Detect Constants
		**/
		function setup_detect_consts() {

			/**
			 * Mobile Detect
			 * @see http://mobiledetect.net/
			**/
			$this->mobile_detect = new Mobile_Detect();
			if( ! defined( 'SSE_IS_MOBILE' ) ) define( 'SSE_IS_MOBILE', $this->mobile_detect->isMobile() );
			if( ! defined( 'SSE_IS_TABLET' ) ) define( 'SSE_IS_TABLET', $this->mobile_detect->isTablet() );
			if( ! defined( 'SSE_IS_IOS' ) ) define( 'SSE_IS_IOS', $this->mobile_detect->isiOS() );
			if( ! defined( 'SSE_IS_ANDROIDOS' ) ) define( 'SSE_IS_ANDROIDOS', $this->mobile_detect->isAndroidOS() );

		}

	/**
	 * Second Initializations Hooked in "after_setup_theme"
	**/
		/**
		 * Second Init
		**/
		function after_setup_theme() {

			// Define
			// Constants
			$this->second_setup_constants();

		}

		/**
		 * Constants
		**/
		function second_setup_constants() {

			# Admin Page
				if( ! defined( 'IS_ADMIN' ) ) define( 'IS_ADMIN', is_admin() );
				if( ! defined( 'IS_ADMIN_BAR_SHOWING' ) ) define( 'IS_ADMIN_BAR_SHOWING', is_admin_bar_showing() );
				if( ! defined( 'IS_CUSTOMIZE_PREVIEW' ) ) define( 'IS_CUSTOMIZE_PREVIEW', is_customize_preview() );

		}

	/**
	 * Tools
	**/
		/**
		 * Sanitize unique prefix
		 * @param  [string] $prefix
		 * @param  [string] $sep
		 * @return [string]
		**/
		public function sanitize_unique_prefix( string $prefix, string $sep = '_' )
		{

			return strtolower( preg_replace( '/[^a-zA-Z0-9]+/i', $sep, $prefix ) );

		}

		/**
		 * Sanitize unique prefix
		 * @param  [string] $prefix
		 * @param  [string] $sep
		 * @return [string]
		**/
		public function sanitize_input_name_prefix( string $prefix, string $sep = '_' )
		{

			return strtolower( preg_replace( '/[^a-zA-Z0-9\[\]]+/i', $sep, $prefix ) );

		}

		/**
		 * Returns the key
		 * @uses  [string] self::UNIQUE_KEY
		 * @return [string]
		**/
		public function get_prefix_key()
		{
			return ShapeShifter_Extensions::UNIQUE_KEY;
		}

		/**
		 * Returns the key
		 * @uses  [string] $this->theme_data['Name']
		 * @return [string]
		**/
		public function get_theme_prefix_key()
		{
			return str_replace( array( ' ', '-' ), '_', strtolower( SSE_THEME_NAME ) );
		}

		/**
		 * Get prefixed name
		 * @param  [string] $name
		 * @param  [string] $sep
		 * @return [string]
		**/
		public function get_prefixed_name( string $name, string $sep = '_' )
		{

			return $this->sanitize_unique_prefix( implode( $sep, array(
				$this->get_prefix_key(),
				$name
			) ), $sep );

		}

		/**
		 * Get prefixed name with theme name
		 * @param  [string] $name
		 * @param  [string] $sep
		 * @return [string]
		**/
		public function get_theme_prefixed_name( string $name, string $sep = '_' )
		{

			return $this->sanitize_unique_prefix( implode( $sep, array(
				SSE_THEME_KEY,
				$name
			) ), $sep );

		}

		/**
		 * Get prefixed option name
		 * @param  [string] $name
		 * @param  [string] $sep
		 * @return [string]
		**/
		public function get_prefixed_option_name( string $name, string $sep = '_' )
		{

			return $this->sanitize_input_name_prefix( implode( $sep, array(
				ShapeShifter_Extensions::UNIQUE_KEY,
				$name
			) ), $sep );

		}

		/**
		 * Get prefixed option name
		 * @param  [string] $name
		 * @param  [string] $sep
		 * @return [string]
		**/
		public function get_prefixed_theme_option_name( string $name, string $sep = '_' )
		{

			return $this->sanitize_input_name_prefix( implode( $sep, array(
				SSE_THEME_KEY,
				$name
			) ), $sep );
		}

		/**
		 * Get prefixed option name
		 * @param  [string] $name
		 * @param  [string] $sep
		 * @return [string]
		**/
		public function get_prefixed_post_meta_name( string $name, string $sep = '_' )
		{

			return $this->sanitize_input_name_prefix( '_' . implode( $sep, array(
				ShapeShifter_Extensions::UNIQUE_KEY,
				$name
			) ), $sep );

		}

		/**
		 * Get prefixed option name
		 * @param  [string] $name
		 * @param  [string] $sep
		 * @return [string]
		**/
		public function get_prefixed_theme_post_meta_name( string $name, string $sep = '_' )
		{

			return $this->sanitize_input_name_prefix( '_' . implode( $sep, array(
				SSE_THEME_KEY,
				$name
			) ), $sep );

		}

		/**
		 * Get prefixed action hook
		 * @param  [string] $name
		 * @param  [string] $sep
		 * @return [string]
		**/
		public function get_prefixed_action_hook( string $name, string $sep = '_' )
		{

			return $this->sanitize_unique_prefix( implode( $sep, array(
				$this->get_prefix_key(),
				'action',
				$name
			) ), $sep );

		}

		/**
		 * Get prefixed filter hook
		 * @param  [string] $name
		 * @param  [string] $sep
		 * @return [string]
		**/
		public function get_prefixed_filter_hook( string $name, string $sep = '_' )
		{

			return $this->sanitize_unique_prefix( implode( $sep, array(
				$this->get_prefix_key(),
				'filter',
				$name
			) ), $sep );

		}

	/**
	 * Getters
	**/
		/**
		 * Get Theme Data
		 * @return array
		**/
		public function get_theme_mods()
		{
			return shapeshifter()->get_theme_mods();
		}

		/**
		 * Get Theme Data
		 * @param string $key
		 * @return null|mixed
		**/
		public function get_theme_mod( $key = '' )
		{
			return shapeshifter()->get_theme_mod( $key );
		}





}
