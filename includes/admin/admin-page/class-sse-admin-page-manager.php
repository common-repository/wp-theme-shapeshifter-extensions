<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

if( ! class_exists( 'SSE_Admin_Page_Manager' ) ) { 
class SSE_Admin_Page_Manager extends SSE_Unique_Abstract {

	/**
	 * Static
	**/
		/**
		 * Instance
		 * @var SSE_Admin_Page_Manager
		**/
		protected static $instance;

		/**
		 * Instance
		 * @var array
		**/
		public static $theme_menu = array();

	/**
	 * Properties
	**/
		private $options = array();

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
		 * Constructor
		**/
		protected function __construct()
		{
			$this->init();
			$this->init_classes();
			$this->init_hooks();
		}
		
		protected function init()
		{
			$this->options = sse()->get_options();
		}

		protected function init_classes()
		{
			$this->page_frontend_settings    = SSE_Page_Frontend_Settings::get_instance();
			$this->page_custom_font_settings = SSE_Page_Custom_Font_Settings::get_instance();
			$this->page_custom_css_settings  = SSE_Page_Custom_CSS_Settings::get_instance();
			$this->pixabay_media_fetcher     = SSE_Page_Pixabay_Media_Fetcher::get_instance();
			//$this->page_required_settings    = SSE_Page_Required_Settings::get_instance();
		}

		protected function init_hooks()
		{

		}

	/**
	 * Sanitize Methods
	 * @param  [type] $general [description]
	 * @return [type]          [description]
	**/
		public static function sanitize_general( $general )
		{
			
			if( is_array( $general ) ) { foreach( $general as $index => $settings ) {
				if( $index == 'default_settings_tab' ) 
					$general[ $index ] =  sanitize_text_field( $settings );

			} }

			return $general;
		}

		public static function sanitize_not_display_post_formats( $not_display_post_formats )
		{
			
			if( is_array( $not_display_post_formats ) ) { foreach( $not_display_post_formats as $index => $settings ) {
				$not_display_post_formats[ $index ] =  sanitize_text_field( $settings );
			} }

			return $not_display_post_formats;
		}

		public static function sanitize_remove_actions( $remove_actions )
		{
			
			if( is_array( $remove_actions ) ) { foreach( $remove_actions as $index => $settings ) {
				$remove_actions[ $index ] =  sanitize_text_field( $settings );
			} }

			return $remove_actions;
		}

		public static function sanitize_auto_inserts( $auto_inserts )
		{

			$for_sanitize_absint = array( 'excerpt_length' );

			$for_sanitize_text_field = array();
			
			$for_esc_textarea = array( 'content_editor', 'header_code', 'after_start_body_code', 'footer_code' );

			if( is_array( $auto_inserts ) ) { foreach( $auto_inserts as $index => $settings ) {
				if( in_array( $index, $for_sanitize_text_field ) )
					$auto_inserts[ $index ] = sanitize_text_field( $settings );
				if( in_array( $index, $for_esc_textarea ) )
					$auto_inserts[ $index ] = esc_textarea( html_entity_decode( preg_replace( '/\\\([\'"])/i', '$1', $settings ) ) );
				if( in_array( $index, $for_sanitize_absint ) ) {
					$auto_inserts[ $index ] = ( 
						intval( $settings ) < 1
						? 20 
						: intval( $settings ) 
					);
				}
			} }

			return $auto_inserts;

		}

		public static function sanitize_speed_adjust( $speed_adjust )
		{
			
			$for_sanitize_text_field = array( 
				'style_min', 'async_script_on', 'async_script_tags', 'lazy_load', 'ajax_load_posts',
			);

			if( is_array( $speed_adjust ) ) { foreach( $speed_adjust as $index => $settings ) {
				if( in_array( $index, $for_sanitize_text_field ) )
					$speed_adjust[ $index ] =  sanitize_text_field( $settings );
			} }

			return $speed_adjust;

		}

		public static function sanitize_widget_areas_generals( $widget_areas_generals )
		{

			$for_intval = array( 'num' );

			if( is_array( $widget_areas_generals ) ) { foreach( $widget_areas_generals as $index => $settings ) {
				if( in_array( $index, $for_intval ) )
					$widget_areas_generals[ $index ] =  intval( $settings );
			} }

			return $widget_areas_generals;

		}

		public static function sanitize_widget_areas( $widget_areas )
		{

			$for_sanitize_text_field = array( 'hook', 'width', 'is_on_mobile_menu', 'id', 'class' );

			$for_esc_textarea = array( 'description', 'before_widget', 'after_widget', 'before_title', 'after_title' );

			if( is_array( $widget_areas ) ) { foreach( $widget_areas as $number => $settings ) {

				if ( ! isset( $widget_areas[ $number ]['is_on_mobile_menu'] ) ) {
					$widget_areas[ $number ]['is_on_mobile_menu'] = '';
				}

				foreach( $settings as $index => $setting ) {

					if( in_array( $index, $for_sanitize_text_field ) )
						$widget_areas[ $number ][ $index ] =  sanitize_text_field( $setting );
					
					if( in_array( $index, $for_esc_textarea ) )
						$widget_areas[ $number ][ $index ] =  esc_textarea( html_entity_decode( preg_replace( '/\\\([\'"])/i', '$1', $setting ) ) );

				}
			} } else { $widget_aeras = array(); }

			return $widget_areas;

		}

		public static function sanitize_seos( $seos )
		{

			$for_sanitize_text_field = array( 'json_ld_markup_on', 'json_ld_logo', 'insert_prev_next_link', 'canonical_link_on', 'meta_robots_on', 'meta_description_on', 'meta_keywords_on', 'twitter_card_on', 'twitter_card_account', 'open_graph_on', 'tc_og_image' );

			$for_esc_url_raw = array( 'google_plus_url' );

			if( is_array( $seos ) ) { foreach( $seos as $index => $settings ) {

				if( in_array( $index, $for_sanitize_text_field ) )
					$seos[ $index ] =  sanitize_text_field( $settings );
				
				if( in_array( $index, $for_esc_url_raw ) )
					$seos[ $index ] =  esc_url_raw( $settings );

			} }

			return $seos;

		}

		public static function sanitize_others( $others )
		{

			$for_sanitize_text_field = array( 'reset_page_view_count', 'auto_page_view_count_reset', 'pixabay_key' );

			if( is_array( $others ) ) { foreach( $others as $index => $settings ) {
				if( in_array( $index, $for_sanitize_text_field ) )
					$others[ $index ] =  sanitize_text_field( $settings );
			} }

			return $others;

		}

		public static function sanitize_debug_modes( $debug_modes )
		{

			$for_sanitize_text_field = array( 'auto_page_view_count_reset' );

			if( is_array( $debug_modes ) ) { foreach( $debug_modes as $index => $settings ) {
				if( in_array( $index, $for_sanitize_text_field ) )
					$debug_modes[ $index ] =  sanitize_text_field( $settings );
			} }

			return $debug_modes;

		}

}
}

?>