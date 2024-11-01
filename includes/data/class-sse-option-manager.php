<?php
if ( ! defined( 'ABSPATH' ) ) exit;

class SSE_Option_Manager extends SSE_Unique_Abstract {

	/**
	 * Static
	**/
		/**
		 * Instace
		 * @var SSE_Theme_Mod_Manager
		**/
		protected static $instance = null;

		public static $defaults = array(
			'general' => array(
				'default_settings_tab' => 'general-settings',
			),
			'not_display_post_formats' => array(
				'aside' => false,
				'gallery' => false,
				'image' => false,
				'link' => false,
				'quote' => false,
				'status' => false,
				'video' => false,
				'audio' => false,
				'chat' => false,
			),
			'remove_action' => array(
				'rsd_link' => false,
				'wlwmanifest_link' => false,
				'wp_generator' => false,
				'feed_links_extra' => false,
				'feed_links' => false,
				'index_rel_link' => false,
				'parent_post_rel_link' => false,
				'start_post_rel_link' => false,
				'adjacent_posts_rel_link_wp_head' => false,
			),
			'auto_insert' => array(
				'excerpt_length' => 200,
				'content_editor' => '',
				'header_code' => '',
				'after_start_body_code' => '',
				'footer_code' => '',
			),
			'speed_adjust' => array(
				'async_script_on'		=> false,
				//'async_script_tags' 	=> 'jquery,sse-general-methods,magnific-popup,slider-pro,vegas,shapeshifter-animate,shapeshifter-javascripts,shapeshifter-widget-slide-gallery',
				'lazy_load' 			=> false,
				'ajax_load_posts'	    => false,
				'pjax_switch' 			=> false,
				'pjax_reload_codes' 	=> '',
			),
			'widget_areas' => array(),
			'seo' => array(
				'json_ld_markup_on' => false,
				'json_ld_logo' => SSE_THEME_ROOT_URI . '/screenshot.png',
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
			),
			'others' => array(
				'reset_page_view_count' => true,
				'auto_page_view_count_reset' => 'no',
				'pixabay_key' => ''
			),
			'fonts' => array(
				1 => array( 
					'font-family' => '',
					'src' => array(),
				)
			),
			'icons' => array(),
		);

	/**
	 * Init
	**/
		/**
		 * Public Initializer
		 * @return SSE_Option_Manager
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
			$this->init_vars();
			$this->init_hooks();
		}

		/**
		 * Init vars
		**/
		protected function init_vars()
		{

			// Options
				// Generals
					$this->options['general'] = SSE_Data_Option::get_instance( 'general', self::$defaults['general'] );
					$this->options['not_display_post_formats'] = SSE_Data_Option::get_instance( 'not_display_post_formats', self::$defaults['not_display_post_formats'] );
					$this->options['remove_action'] = SSE_Data_Option::get_instance( 'remove_action', self::$defaults['remove_action'] );

				// Auto Insert
					$this->options['auto_insert'] = SSE_Data_Option::get_instance( 'auto_insert', self::$defaults['auto_insert'] );

				// Speed Adjust
					$this->options['speed_adjust'] = SSE_Data_Option::get_instance( 'speed_adjust', self::$defaults['speed_adjust'] );

						# AJAX Load
							if ( ! defined( 'SHAPESHIFTER_IS_AJAX_LOAD_ON' ) ) define( 'SHAPESHIFTER_IS_AJAX_LOAD_ON', shapeshifter_boolval( $this->options['speed_adjust']['ajax_load_posts'] ) );

						# lazy_load Constant
							if( ! defined( 'SHAPESHIFTER_IS_LAZYLOAD_ON' ) ) define( 'SHAPESHIFTER_IS_LAZYLOAD_ON', shapeshifter_boolval( $this->options['speed_adjust']['lazy_load'] ) );
							if( ! defined( 'SSE_IS_LAZYLOAD_ON' ) ) define( 'SSE_IS_LAZYLOAD_ON', shapeshifter_boolval( $this->options['speed_adjust']['lazy_load'] ) );

				// Widget Areas
					/*$this->options['widget_areas_general'] = SSE_Data_Option::get_instance( 'widget_areas_general', array(
						'num' => 1,
					) );
					$widtet_num = $this->options['widget_areas_general']->get_prop( 'num' );
					if( ! is_numeric( $widtet_num ) )
						$this->options['widget_areas_general']->get_prop( 'num', 0 );*/
					$this->options['widget_areas'] = SSE_Data_Theme_Option::get_instance( 'widget_areas', self::$defaults['widget_areas'] );

				// SEO
					$this->options['seo'] = SSE_Data_Option::get_instance( 'seo', self::$defaults['seo'] );

				// Others
					$this->options['others'] = SSE_Data_Option::get_instance( 'others', self::$defaults['others'] );

				// Fonts
					/*$this->options['fonts_general'] = SSE_Data_Option::get_instance( 'fonts_general', self::$defaults['fonts_general'] );
					$font_num = intval( $this->options['widget_areas_general']->get_prop( 'num' ) );
					if( ! is_numeric( $font_num ) )
						$this->options['fonts_general']['num'] = intval( $this->options['fonts_general']['num'] );*/
					$this->options['fonts'] = SSE_Data_Theme_Option::get_instance( 'fonts', self::$defaults['fonts'] );

				// Icons
				if ( is_admin() || is_customize_preview() ) {
					// Others
					$this->options['icons'] = SSE_Data_Option::get_instance( 'icons', self::$defaults['icons'] );
				}

		}

		/**
		 * Init hooks
		**/
		protected function init_hooks()
		{

		}

		/**
		 * Get Options
		 * @return mixed
		**/
		public function get_options()
		{
			return $this->options;
		}

		/**
		 * Get Options
		 * @return mixed
		**/
		public function get_option( string $key )
		{
			if ( ! is_string( $key ) 
				|| '' === $key
				|| ! isset( $this->options[ $key ] )
				|| ! $this->options[ $key ] instanceof SSE_Data_Option
			) {
				return false;
			}
			return $this->options[ $key ];
		}


}

