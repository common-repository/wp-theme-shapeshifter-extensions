<?php
if ( ! class_exists( 'SSE_Admin_Manager' ) ) {
/**
 * Admin
**/
class SSE_Admin_Manager {

	/**
	 * Static
	**/
		/**
		 * Instance of this Class
		 * 
		 * @var $instance
		**/
		protected static $instance = null;

	/**
	 * Properties
	**/
		/**
		 * Options in Array
		 * 
		 * @var $options
		**/
		protected $options = array();

		/**
		 * Admin Page Manager
		 * @var SSE_Admin_Page_Manager
		**/
		protected $admin_page_manager = null;

		/**
		 * Get Admin Page Manager
		 * @return SSE_Admin_Page_Manager
		**/
		public function get_admin_page_manager()
		{
			return $this->admin_page_manager;
		}

		/**
		 * Meta Box Manager
		 * @var SSE_Admin_MetaBox_Manager
		**/
		protected $metabox_manager = null;

		/**
		 * Get Meta Box Manager
		 * @return SSE_Admin_MetaBox_Manager
		**/
		public function get_metabox_manager()
		{
			return $this->metabox_manager;
		}

		/**
		 * Meta Box Manager
		 * @var SSE_Admin_Meta_Box_Manager
		**/
		protected $notification_manager = null;

		/**
		 * Get Meta Box Manager
		 * @return SSE_Admin_Meta_Box_Manager
		**/
		public function get_notification_manager()
		{
			return $this->notification_manager;
		}

	#
	# Init
	#
		/**
		 * Public Initializer
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
			$this->options = sse()->get_options();
			$this->init_classes();
			$this->init_hooks();
		}

		/**
		 * Init Classes
		**/
		protected function init_classes()
		{
			// Admin Page Manager
//			$this->dashboard_manager  = SSE_Dashboard_Manager::get_instance();
			$this->icon_manager       = SSE_Icon_Manager::get_instance();
			$this->admin_page_manager = SSE_Admin_Page_Manager::get_instance();
			$this->metabox_manager    = SSE_Metabox_Manager::get_instance();
			$this->user_meta_manager  = SSE_User_Meta_Manager::get_instance();
			$this->nav_menu_editor    = SSE_Nav_Menu_Editor::get_instance();
			$this->taxonomy_editor    = SSE_Taxonomy_Editor::get_instance();

		}

		/**
		 * Init WP Hooks
		**/
		protected function init_hooks() {

			// Save Style
				// When Fonts Saved

				// Enqueue Scripts
					add_action( 'admin_enqueue_scripts', array( $this, 'admin_enqueue_scripts' ) );

				// JS Templates
					add_action( 'admin_print_footer_scripts', array( $this, 'append_js_templates' ) );

			// Filters
				add_filter( 'default_content', array( $this, 'set_default_content_text' ) );

		}

	/**
	 * Hooks
	**/
		/**
		 * Enqueue Scripts
		 * 
		 * @param string $hook
		**/
		public function admin_enqueue_scripts( $hook ) {

			wp_enqueue_media();
				
			// Theme Settings
			if( 'appearance_page_theme_settings_menu' == $hook ) {

				wp_localize_script( 'theme-settings-menu-widget-areas', 'sseSettingsMenuWidgetAreasArgs', array(
					'themeOption' => SSE_THEME_OPTIONS,
					'widget_area' => esc_html__( 'Widget Area', ShapeShifter_Extensions::TEXTDOMAIN ),
					'description_s_d' => esc_html__( 'This is \"%s %d\".', ShapeShifter_Extensions::TEXTDOMAIN ),
					'ThisIsWidgetArea_d' => esc_html__( 'This is "Widget Area %d".', ShapeShifter_Extensions::TEXTDOMAIN ),
					'hookToDisplay' => esc_html__( 'Hook to display', ShapeShifter_Extensions::TEXTDOMAIN ),
					'custom' => esc_html__( 'Custom', ShapeShifter_Extensions::TEXTDOMAIN ),
					'afterTheHeader' => esc_html__( 'After the Header', ShapeShifter_Extensions::TEXTDOMAIN ),
					'beforeTheContent' => esc_html__( 'Before the Content', ShapeShifter_Extensions::TEXTDOMAIN ),
					'atTheBeginningOfTheContent' => esc_html__( 'At the Beginning of the Content', ShapeShifter_Extensions::TEXTDOMAIN ),
					'beforeTheFirstH2TagOfTheContent' => esc_html__( 'Before the First H2 tag of the Content', ShapeShifter_Extensions::TEXTDOMAIN ),
					'atTheEndOfTheContent' => esc_html__( 'At the End of the Content', ShapeShifter_Extensions::TEXTDOMAIN ),
					'afterTheContent' => esc_html__( 'After the Content', ShapeShifter_Extensions::TEXTDOMAIN ),
					'beforeTheFooter' => esc_html__( 'Before the Footer', ShapeShifter_Extensions::TEXTDOMAIN ),
					'inTheFooter' => esc_html__( 'In the Footer', ShapeShifter_Extensions::TEXTDOMAIN ),
					'width' => esc_html__( 'Width', ShapeShifter_Extensions::TEXTDOMAIN ),
					'max_100' => esc_html__( 'Maximum ( 100% )', ShapeShifter_Extensions::TEXTDOMAIN ),
					'adaptedToContentAreaWidth' => esc_html__( 'Adapted to Content Area Width', ShapeShifter_Extensions::TEXTDOMAIN ),
					'threeColumns1280' => esc_html__( '1280px ( width for 3 columns )', ShapeShifter_Extensions::TEXTDOMAIN ),
					'twoColumns960' => esc_html__( '960px ( width for 2 columns )', ShapeShifter_Extensions::TEXTDOMAIN ),
					'oneColumn870' => esc_html__( '870px ( width for 1 column )', ShapeShifter_Extensions::TEXTDOMAIN ),
					'sixHundred' => esc_html__( '600px', ShapeShifter_Extensions::TEXTDOMAIN ),
					'fourHundred' => esc_html__( '400px', ShapeShifter_Extensions::TEXTDOMAIN ),
					'threeHundred' => esc_html__( '300px', ShapeShifter_Extensions::TEXTDOMAIN ),
					'printInSideMenu_onlyForMobile' => esc_html__( 'Print in Side Menu ( only for Mobile )', ShapeShifter_Extensions::TEXTDOMAIN ),
					'id' => esc_html__( 'ID', ShapeShifter_Extensions::TEXTDOMAIN ),
					'class' => esc_html__( 'Class', ShapeShifter_Extensions::TEXTDOMAIN ),
					'name' => esc_html__( 'Name', ShapeShifter_Extensions::TEXTDOMAIN ),
					'description' => esc_html__( 'Description', ShapeShifter_Extensions::TEXTDOMAIN ),
					'beforeWidget' => esc_html__( 'Before Widget', ShapeShifter_Extensions::TEXTDOMAIN ),
					'afterWidget' => esc_html__( 'After Widget', ShapeShifter_Extensions::TEXTDOMAIN ),
					'beforeTitle' => esc_html__( 'Before Widget Title', ShapeShifter_Extensions::TEXTDOMAIN ),
					'afterTitle' => esc_html__( 'After Widget Title', ShapeShifter_Extensions::TEXTDOMAIN ),

				) );
				wp_enqueue_script( 'theme-settings-menu-widget-areas' );

			}

			// For Edit Widget
			//if( 'widgets.php' == $hook ){

				wp_enqueue_style( 'sse-widget-settings-form' );

				wp_enqueue_script( 'widgets-get-images_js' );
				wp_enqueue_script( 'sse-widget-settings' );

			//}

			// Script to update User Meta not to display message
			wp_enqueue_script( 'sse-user-meta-notifications-dismiss' );
			
		}

		/**
		 * JS Templates
		**/
		public function append_js_templates() {

			include_once( SHAPESHIFTER_EXTENSIONS_TEMPLATES_DIR . 'admin-js/page-builder.php' );

		}

		/**
		 * Default Content
		 * 
		 * @param string $content 
		 * 
		 * @return string
		**/
		public function set_default_content_text( $content ) {

			return $content . html_entity_decode( $this->options['auto_insert']->get_prop( 'content_editor' ) );

		}

	/**
	 * Need fix
	**/

}
}

