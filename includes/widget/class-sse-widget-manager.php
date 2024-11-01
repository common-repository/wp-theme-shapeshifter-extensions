<?php

if ( ! defined( 'ABSPATH' ) ) exit;

class SSE_Widget_Manager extends SSE_Unique_Abstract {

	/**
	 * Static
	**/
		/**
		 * Instance
		 * @var SSE_Widget_Manager
		**/
		protected static $instance = null;

		/**
		 * Defaults
		**/
		public static $defaults = array();

	/**
	 * Init
	**/
		/**
		 * Public Initializer
		 * @return SSE_Widget_Manager
		**/
		public static function get_instance()
		{
			if ( null === self::$instance ) self::$instance = new Self();
			return self::$instance;
		}

		/**
		 * Constructor
		**/
		protected function __construct()
		{
			$this->init();
			$this->init_hooks();
		}

		/**
		 * Init Hooks
		**/
		protected function init()
		{

			// Setup Defaults
				self::$defaults = array(
					'shapeshifter_display_home' => '',
					'shapeshifter_display_front' => '',
					'shapeshifter_display_blog' => '',
					'shapeshifter_display_archive' => '',
					'shapeshifter_display_singular' => '',
					'shapeshifter_display_device' => '',

					'shapeshifter_not_display_woocommerce' => '',
					'shapeshifter_not_display_cart' => '',
					'shapeshifter_not_display_checkout' => '',
					'shapeshifter_not_display_account_page' => '',
				);

				// Post Types
				$post_types = get_post_types();
				foreach( $post_types as $post_type => $name ) { 
					self::$defaults['shapeshifter_display_' . $post_type ] = '';
				} $post_types = null;

		}

		/**
		 * Init Hooks
		**/
		protected function init_hooks()
		{

			// Register Widgets
			$this->register_widgets();

			// Enqueue Admin Scripts
				add_action( 'admin_enqueue_scripts', array( $this, 'admin_enqueue_scripts' ) );

			// Add Settings to Each Widget
				add_filter( 'widget_display_callback', array( $this, 'widget_display_callback' ), 10, 3 );
				add_filter( 'widget_update_callback', array( $this, 'widget_update_callback' ), 10, 4 );
				add_action( 'in_widget_form', array( $this, 'in_widget_form' ), 10, 3 );

		}

		/**
		 * Register Widgets
		**/
		protected function register_widgets()
		{

			// Register Widgets
			$this->init_widget( 'ShapeShifter_Widget_Text' );
			$this->init_widget( 'ShapeShifter_New_Entries' );
			$this->init_widget( 'ShapeShifter_Related_Entries' );
			$this->init_widget( 'ShapeShifter_Popular_Entries' );
			$this->init_widget( 'ShapeShifter_Entries_Channels' );
			$this->init_widget( 'ShapeShifter_TOC' );
			$this->init_widget( 'ShapeShifter_SNS_Share_Icons' );
			$this->init_widget( 'ShapeShifter_SNS_Share_Buttons' );
			$this->init_widget( 'ShapeShifter_Feed_Reader' );
			$this->init_widget( 'ShapeShifter_Download_Link' );
			$this->init_widget( 'ShapeShifter_Slide_Gallary' );

		}

		/**
		 * Add action for init widget class
		 * Need seperate by php version( 5.3+ or not )
		 * 
		 * @param string $widget_class_name
		**/
		protected function init_widget( $widget_class_name ) {

			// Check the required param
			if ( ! isset( $widget_class_name ) || ! is_string( $widget_class_name ) || '' === $widget_class_name ) {
				return;
			}

			// 5.2+ or less
			if ( version_compare( PHP_VERSION, '5.3.0', '<' ) ) {
				add_action( 'widgets_init', create_function( '', 'return register_widget( "' . $widget_class_name . '" );' ) );
			}
			// 5.3+
			else {
				add_action( 'widgets_init', function() use ( $widget_class_name ) { register_widget( $widget_class_name ); } );
			}

		}

		/**
		 * Enqueue Scripts in Admin
		**/
		public function admin_enqueue_scripts( $hook )
		{

			// Slide Gallary
				$JSON = array(

					'themeDirectoryURI' => get_template_directory_uri(),

					'slidingItemType' => esc_html__( 'Sliding Item Type', ShapeShifter_Extensions::TEXTDOMAIN ),
					'text' => esc_html__( 'Text', ShapeShifter_Extensions::TEXTDOMAIN ),
					'download' => esc_html__( 'Download', ShapeShifter_Extensions::TEXTDOMAIN ),
					'close' => esc_html__( 'Close', ShapeShifter_Extensions::TEXTDOMAIN ),
					'slidingImage' => esc_html__( 'Sliding Images', ShapeShifter_Extensions::TEXTDOMAIN ),
					'itemD' => esc_html__( 'Item %d', ShapeShifter_Extensions::TEXTDOMAIN ),
					'select' => esc_html__( 'Select', ShapeShifter_Extensions::TEXTDOMAIN ),

					'topLeft' => esc_html__( 'Top Left', ShapeShifter_Extensions::TEXTDOMAIN ),
					'topCenter' => esc_html__( 'Top Center', ShapeShifter_Extensions::TEXTDOMAIN ),
					'topRight' => esc_html__( 'Top Right', ShapeShifter_Extensions::TEXTDOMAIN ),
					'bottomLeft' => esc_html__( 'Bottom Left', ShapeShifter_Extensions::TEXTDOMAIN ),
					'bottomCenter' => esc_html__( 'Bottom Center', ShapeShifter_Extensions::TEXTDOMAIN ),
					'bottomRight' => esc_html__( 'Bottom Right', ShapeShifter_Extensions::TEXTDOMAIN ),
					'centerLeft' => esc_html__( 'Center Left', ShapeShifter_Extensions::TEXTDOMAIN ),
					'centerCenter' => esc_html__( 'Center', ShapeShifter_Extensions::TEXTDOMAIN ),
					'centerRight' => esc_html__( 'CenterRight', ShapeShifter_Extensions::TEXTDOMAIN ),

					'itemContentTextD' => esc_html__( 'Item Content Text %d', ShapeShifter_Extensions::TEXTDOMAIN ),
					'positionD' => esc_html__( 'Position %d', ShapeShifter_Extensions::TEXTDOMAIN ),
					'textColorD' => esc_html__( 'Text Color %d', ShapeShifter_Extensions::TEXTDOMAIN ),
					'backgroundColorD' => esc_html__( 'Background Color %d', ShapeShifter_Extensions::TEXTDOMAIN ),

					'mobileSidebar' => esc_html__( 'Mobile Sidebar', ShapeShifter_Extensions::TEXTDOMAIN ),
					'afterHeader' => esc_html__( 'After the Header', ShapeShifter_Extensions::TEXTDOMAIN ),
					'beforeContentArea' => esc_html__( 'Before Content Area', ShapeShifter_Extensions::TEXTDOMAIN ),
					'beforeContent' => esc_html__( 'Before the Content', ShapeShifter_Extensions::TEXTDOMAIN ),
					'beginningOfContent' => esc_html__( 'At the Beginning of the Content', ShapeShifter_Extensions::TEXTDOMAIN ),
					'before1stH2OfContent' => esc_html__( 'Before the First H2 tag of the Content', ShapeShifter_Extensions::TEXTDOMAIN ),
					'endOfContent' => esc_html__( 'At the End of the Content', ShapeShifter_Extensions::TEXTDOMAIN ),
					'afterContent' => esc_html__( 'After the Content', ShapeShifter_Extensions::TEXTDOMAIN ),
					'beforeFooter' => esc_html__( 'Before the Footer', ShapeShifter_Extensions::TEXTDOMAIN ),
					'inFooter' => esc_html__( 'In the Footer', ShapeShifter_Extensions::TEXTDOMAIN ),

				);
				wp_localize_script( 'sse-admin-widget-slide-gallery', 'shapeshifterJSTranslatedObject', $JSON );
				wp_enqueue_script( 'sse-admin-widget-slide-gallery' );

		}

	/**
	 * Additional Settings for Each Widget
	**/
		/**
		 * Filter method to Judge if $widget is displayed
		 * 
		 * @param array $instance
		 * @param obj $widget
		 * @param array $args
		 * 
		 * @return array|bool $instance : return $instance if is displayed, otherwise returns false
		**/
		function widget_display_callback( $instance, $widget, $args ) {

			// Get Device Settings
				$shapeshifter_display_device = esc_attr( isset( $instance['shapeshifter_display_device'] ) ? $instance['shapeshifter_display_device'] : 'shapeshifter_display_any' );
			// Device Detect
				if( $shapeshifter_display_device == 'shapeshifter_display_any' ) {
				} elseif( ! SSE_IS_MOBILE && $shapeshifter_display_device == 'shapeshifter_display_pc' ) {
				} elseif( SSE_IS_MOBILE && $shapeshifter_display_device == 'shapeshifter_display_mobile' ) {
				} elseif( SSE_IS_TABLET && $shapeshifter_display_device == 'shapeshifter_display_tablet' ) {
				} elseif( ( SSE_IS_MOBILE && ! SSE_IS_TABLET ) && $shapeshifter_display_device == 'shapeshifter_display_phone' ) {
				} else { return false; } $shapeshifter_display_device = null;

			# Priority
				$shapeshifter_display_home = esc_attr( isset( $instance['shapeshifter_display_home'] ) ? $instance['shapeshifter_display_home'] : 'shapeshifter_display_home' );
				$shapeshifter_display_front = esc_attr( isset( $instance['shapeshifter_display_front'] ) ? $instance['shapeshifter_display_front'] : 'shapeshifter_display_front' );
				$shapeshifter_display_blog = esc_attr( isset( $instance['shapeshifter_display_blog'] ) ? $instance['shapeshifter_display_blog'] : 'shapeshifter_display_blog' );

			# General Page
				$shapeshifter_display_archive = esc_attr( isset( $instance['shapeshifter_display_archive'] ) ? $instance['shapeshifter_display_archive'] : 'shapeshifter_display_archive' );
				$shapeshifter_display_singular = esc_attr( isset( $instance['shapeshifter_display_singular'] ) ? $instance['shapeshifter_display_singular'] : 'shapeshifter_display_singular' );

			# Page Detect
				if( is_home() && is_front_page() ) {
					if( $shapeshifter_display_home == '' ) return false;
				} elseif( ! is_home() && is_front_page() ) {
					if( $shapeshifter_display_front == '' ) return false;
				} elseif( is_home() && ! is_front_page() ) { 
					if( $shapeshifter_display_blog == '' ) return false;
				} elseif( $shapeshifter_display_archive && is_archive() ) { 
					if( $shapeshifter_display_archive == '' ) return false;
				} elseif( $shapeshifter_display_singular && is_singular() ) { 
					if( $shapeshifter_display_singular == '' ) return false;
				//} elseif( function_exists( 'is_bbpress' ) && is_bbpress() ) { 
				} else { return false; }
				$shapeshifter_display_home = $shapeshifter_display_front = $shapeshifter_display_blog = $shapeshifter_display_archive = $shapeshifter_display_singular = null;

			# Post Type
				if( ! is_front_page() && is_singular() ) {
					global $post;
					$post_types = get_post_types();

					if( function_exists( 'is_woocommerce' ) && is_woocommerce() && ! empty( $instance['shapeshifter_not_display_woocommerce'] ) ) { return false;
					} elseif( function_exists( 'is_woocommerce' ) && is_cart() && ! empty( $instance['shapeshifter_not_display_cart'] ) ) { return false;
					} elseif( function_exists( 'is_woocommerce' ) && is_checkout() && ! empty( $instance['shapeshifter_not_display_checkout'] ) ) { return false;
					} elseif( function_exists( 'is_woocommerce' ) && is_account_page() && ! empty( $instance['shapeshifter_not_display_account_page'] ) ) { return false; 
					}

					foreach( $post_types as $post_type => $name ) { 

						if( function_exists( 'is_woocommerce' ) && is_woocommerce() ) {
							if( in_array( 
								esc_attr( 
									isset( $instance['shapeshifter_display_' . $post_type ] ) 
									? $instance['shapeshifter_display_' . $post_type ] 
									: '' 
								), 
								array( 
									'shapeshifter_display_product', 
									'shapeshifter_display_product_variation', 
									'shapeshifter_display_shop_order', 
									'shapeshifter_display_shop_order_refund', 
									'shapeshifter_display_shop_coupon', 
									'shapeshifter_display_shop_webhook' 
								) 
							) ) { return false; }
						}

						if( 'shapeshifter_display_' . $post->post_type 
							== esc_attr( isset( $instance['shapeshifter_display_' . $post_type ] ) ? $instance['shapeshifter_display_' . $post_type ] : '' ) 
						) { return false; }

					} $post_types = null;
				} else {
					if( ( function_exists( 'is_woocommerce' ) && is_woocommerce() ) 
						&& ! empty( $instance['shapeshifter_not_display_woocommerce'] ) 
					) { return false; }
				}

			return $instance;

		}

		/**
		 * Filter Method to Update
		 * 
		 * @param array  $instance
		 * @param array  $new_instance
		 * @param array  $old_instance
		 * @param object $widget
		 * 
		 * @return array $instance
		**/
		function widget_update_callback( $instance, $new_instance, $old_instance, $widget ) {

			// Set Defaults
				$new_instance = wp_parse_args( $new_instance, self::$defaults );

			// Page Type and Device
				$instance['shapeshifter_display_home'] = sanitize_text_field( $new_instance['shapeshifter_display_home'] );
				$instance['shapeshifter_display_front'] = sanitize_text_field( $new_instance['shapeshifter_display_front'] );
				$instance['shapeshifter_display_blog'] = sanitize_text_field( $new_instance['shapeshifter_display_blog'] );
				$instance['shapeshifter_display_archive'] = sanitize_text_field( $new_instance['shapeshifter_display_archive'] );
				$instance['shapeshifter_display_singular'] = sanitize_text_field( $new_instance['shapeshifter_display_singular'] );
				$instance['shapeshifter_display_device'] = sanitize_text_field( $new_instance['shapeshifter_display_device'] );

			// Post Type
				$post_types = get_post_types();
				foreach( $post_types as $post_type => $name ) { 
					$instance['shapeshifter_display_' . $post_type ] = sanitize_text_field( 
						isset( $new_instance['shapeshifter_display_' . $post_type ] )
						? $new_instance['shapeshifter_display_' . $post_type ]
						: false
					);
				} $post_types = null;

			// WooCommerce
				if( function_exists( 'is_woocommerce' ) ) {
					$instance['shapeshifter_not_display_woocommerce'] = sanitize_text_field( SSE_Sanitize_Methods::validate_checked_value( $new_instance['shapeshifter_not_display_woocommerce'] ) );
					$instance['shapeshifter_not_display_cart'] = sanitize_text_field( SSE_Sanitize_Methods::validate_checked_value( $new_instance['shapeshifter_not_display_cart'] ) );
					$instance['shapeshifter_not_display_checkout'] = sanitize_text_field( SSE_Sanitize_Methods::validate_checked_value( $new_instance['shapeshifter_not_display_checkout'] ) );
					$instance['shapeshifter_not_display_account_page'] = sanitize_text_field( SSE_Sanitize_Methods::validate_checked_value( $new_instance['shapeshifter_not_display_account_page'] ) );
				}

			return $instance;

		}
			
		/**
		 * Print Form in Widget Form
		**/
		function in_widget_form( $widget, $return, $instance ) {

			$defaults = array(
				'shapeshifter_display_home' => 'shapeshifter_display_home',
				'shapeshifter_display_front' => 'shapeshifter_display_front',
				'shapeshifter_display_blog' => 'shapeshifter_display_blog',
				'shapeshifter_display_archive' => 'shapeshifter_display_archive',
				'shapeshifter_display_singular' => 'shapeshifter_display_singular',
				'shapeshifter_display_device' => 'shapeshifter_display_any',
			);
			$instance = wp_parse_args( ( array ) $instance, $defaults );

			// Priority
			$shapeshifter_display_home = esc_attr( $instance['shapeshifter_display_home'] );
			$shapeshifter_display_front = esc_attr( $instance['shapeshifter_display_front'] );
			$shapeshifter_display_blog = esc_attr( $instance['shapeshifter_display_blog'] );

			// General Page
			$shapeshifter_display_archive = esc_attr( $instance['shapeshifter_display_archive'] );
			$shapeshifter_display_singular = esc_attr( $instance['shapeshifter_display_singular'] );

			// Post Type
			$post_types = get_post_types();
			foreach( $post_types as $post_type ) { 
				$shapeshifter_display[ $post_type ] = ( 
					( isset( $instance['shapeshifter_display_' . $post_type ] ) 
						&& $instance['shapeshifter_display_' . $post_type ] != '' 
					) 
					? esc_attr( $instance['shapeshifter_display_' . $post_type ] ) 
					: '' 
				);
			}

			// Device Detect
			$shapeshifter_display_device = esc_attr( $instance['shapeshifter_display_device'] );

			// Load Template
			include( SHAPESHIFTER_EXTENSIONS_TEMPLATES_DIR . 'widgets/forms/widget-form-display-condition-detect.php' );

		}


}