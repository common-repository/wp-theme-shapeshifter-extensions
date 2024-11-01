<?php

if ( ! defined( 'ABSPATH' ) ) exit;

class SSE_Page_Frontend_Settings extends SSE_Page_Abstract {

	/**
	 * Static
	**/
		protected static $instance;

		protected static $option_indexes = array(
			'general',
			'not_display_post_formats',
			'remove_action',

			'auto_insert',

			'speed_adjust', 

			'seo',

			'others',
		);

		protected static $theme_option_indexes = array(
			'widget_areas'
		);

		protected static $sanitize_callables = array(
			'general'                  => array( 'SSE_Admin_Page_Manager', 'sanitize_general' ),
			'not_display_post_formats' => array( 'SSE_Admin_Page_Manager', 'sanitize_not_display_post_formats' ),
			'remove_action'            => array( 'SSE_Admin_Page_Manager', 'sanitize_remove_actions' ),

			'auto_insert'              => array( 'SSE_Admin_Page_Manager', 'sanitize_auto_inserts' ),

			'speed_adjust'             => array( 'SSE_Admin_Page_Manager', 'sanitize_speed_adjust' ), 

			'widget_areas'             => array( 'SSE_Admin_Page_Manager', 'sanitize_widget_areas' ),
			'seo'                      => array( 'SSE_Admin_Page_Manager', 'sanitize_seos' ),

			'others'                   => array( 'SSE_Admin_Page_Manager', 'sanitize_others' ),
		);

	/**
	 * Properties
	**/
		/**
		 * Menu Slut
		 * @var string
		**/
		protected $menu_slug = 'frontend_settings';

		/**
		 * Form Template 
		 * @var string
		**/
		protected $form_template = 'view/form-frontend-settings.php';

		/**
		 * Data Option
		 * @var SSE_Data_Option[]
		**/
		protected $data_options = array();

		/**
		 * Data
		 * @var array
		**/
		protected $options = array();

	/**
	 * Init
	**/
		/**
		 * Public Initializer
		 * @return SSE_Admin_Page_Manager
		**/
		public static function get_instance()
		{
			if ( null === self::$instance ) self::$instance = new Self();
			return self::$instance;
		}

		/**
		 * Init
		**/
		protected function init()
		{
			$this->data_options = sse()->get_options();
			foreach ( $this->data_options as $index => $data ) {
				$this->options[ $index ] = $data->get_data();
			}

			$this->page_title = esc_html__( "Settings by ShapeShifter", ShapeShifter_Extensions::TEXTDOMAIN );
			$this->menu_title = esc_html__( "Settings by ShapeShifter", ShapeShifter_Extensions::TEXTDOMAIN );
		}

		protected function init_hooks()
		{
			add_action( 'admin_menu', array( $this, 'update_options' ) );
			add_action( 'admin_menu', array( $this, 'update_theme_options' ) );
			//add_action( sse()->get_prefixed_action_hook( 'frontend_setting_form' ), array( $this, 'update_options' ), 10, 1 );
			parent::init_hooks();
		}

		/**
		 * Admin Enqueue Scripts
		**/
		public function admin_enqueue_scripts( $hook )
		{

			if ( ! isset( $_GET['page'] ) 
				|| 'sse_frontend_settings' !== $_GET['page']
			) {
				return;
			}

			wp_enqueue_style( 'sse-admin-pages' );

			wp_enqueue_script( 'sse-admin-page-frontend-settings' );


			wp_enqueue_script( 'sse-admin-page-setting-page-tab' );
			wp_localize_script( 'sse-admin-page-setting-page-tab', 'sseOptions', $this->options );

			wp_enqueue_script( 'sse-admin-page-widget-areas' );
			wp_localize_script( 'sse-admin-page-widget-areas', 'sseSavedWidgetAreas', sse()->get_option( 'widget_areas' )->get_data() );
			wp_localize_script( 'sse-admin-page-widget-areas', 'sseSettingsMenuWidgetAreasArgs', array(
				'themeOption' => SSE_THEME_OPTIONS,
				'widget_area' => esc_html__( 'Widget Area', ShapeShifter_Extensions::TEXTDOMAIN ),
				'description_s_d' => esc_html__( 'This is "%s %d".', ShapeShifter_Extensions::TEXTDOMAIN ),
				'ThisIsWidgetArea_d' => esc_html__( 'This is "Widget Area %d".', ShapeShifter_Extensions::TEXTDOMAIN ),
				'hookToDisplay' => esc_html__( 'Hook to display', ShapeShifter_Extensions::TEXTDOMAIN ),
				'custom' =>  esc_html__( 'Custom', ShapeShifter_Extensions::TEXTDOMAIN ),
				'afterTheHeader' => esc_html__( 'After the Header', ShapeShifter_Extensions::TEXTDOMAIN ),
				'beforeContentArea' => esc_html__( 'Before Content Area', ShapeShifter_Extensions::TEXTDOMAIN ),
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

				'selectAnImage' => esc_html__( 'Select an Image', ShapeShifter_Extensions::TEXTDOMAIN ),
				'setTheImage' => esc_html__( 'Set the Image', ShapeShifter_Extensions::TEXTDOMAIN ),

			) );
		}

		/**
		 * Update options
		 * @param
		**/
		public function update_options()
		{

			if ( 'admin_menu' !== current_filter() ) {
				return false;
			}

			if ( ! isset( $_POST['sse-frontend-setting-submit'] ) ) {
				return;
			}

			check_admin_referer( sse()->get_prefixed_option_name( 'nonce' ), sse()->get_prefixed_option_name( 'nonce_action' ) );

			$options = array();
			foreach ( $this->data_options as $index => $data ) {
				$options[ $index ] = $data->get_data();
			}

			foreach ( self::$option_indexes as $option_index ) {

				if( false === sse()->get_option( $option_index ) ) continue;

				$index = sse()->get_prefixed_option_name( $option_index );
				if ( ! isset( $_POST[ $index ] ) || ! isset( self::$sanitize_callables[ $option_index ] ) ) {
					continue;
				}

				$sanitize_callable = self::$sanitize_callables[ $option_index ];
				$inputs = call_user_func_array( $sanitize_callable, array( $_POST[ $index ] ) );

				try {
					sse()->get_option( $option_index )->delete();
					sse()->get_option( $option_index )->set_props( $inputs );
					sse()->get_option( $option_index )->update();
				} catch ( Exception $e ) {
					return;//var_dump( $e->getMessage() );
				}

			}

			sse()->add_notice_message( 'Updated Options.' );

		}

		public function update_theme_options()
		{


			if ( 'admin_menu' !== current_filter() ) {
				return false;
			}

			if ( ! isset( $_POST['sse-frontend-setting-submit'] ) ) {
				return;
			}

			check_admin_referer( sse()->get_prefixed_option_name( 'nonce' ), sse()->get_prefixed_option_name( 'nonce_action' ) );

			ob_start();

			foreach ( self::$theme_option_indexes as $option_index ) {

				if( false === sse()->get_option( $option_index ) ) continue;

				$index = sse()->get_prefixed_theme_option_name( $option_index );
				if ( 'widget_areas' === $option_index ) {
					$widget_areas_general_key = sse()->get_prefixed_theme_option_name( 'widget_areas_general' );
					if ( ! isset( $_POST[ $widget_areas_general_key ]['num'] )
						|| ! is_numeric( $_POST[ $widget_areas_general_key ]['num'] )
					) {
						continue;
					}
				}
				elseif ( ! isset( $_POST[ $index ] ) 
					|| ! isset( self::$sanitize_callables[ $option_index ] )
				) {
					continue;
				}

				$sanitize_callable = self::$sanitize_callables[ $option_index ];
				$inputs = call_user_func_array( $sanitize_callable, array( $_POST[ $index ] ) );

				try {
					var_dump( 'Try: ' . $option_index );
					var_dump( 'Inputs: ' );
					var_dump( $inputs );
					sse()->get_option( $option_index )->delete();
					sse()->get_option( $option_index )->set_props( $inputs );
					sse()->get_option( $option_index )->update();
				} catch ( Exception $e ) {
					var_dump( $e->getMessage() );
					continue;
				}

			}

			$output = ob_get_clean();

			sse()->add_notice_message( $output );

		}

		/**
		 * Sanitize options
		**/
		protected function sanitize_options( $value )
		{

		}

}

