<?php
if ( ! defined( 'ABSPATH' ) ) exit;
class SSE_Deprecated_Manager extends SSE_Deprecated_Manager_Abstract {

	/**
	 * Consts
	**/
		const OPTION_NAME_VERSION_CURRENT          = '1.2.0';
		const THEME_OPTION_NAME_VERSION_CURRENT    = '1.2.0';
		const POST_META_NAME_VERSION_CURRENT       = '1.2.0';
		const THEME_POST_META_NAME_VERSION_CURRENT = '1.2.0';

		const SSE_OPTION_PREFIX_BEFORE_1_2_0         = 'shapeshifter_option_';
		const SSE_THEME_OPTION_PREFIX_BEFORE_1_2_0   = SSE_THEME_OLD_OPTIONS;
		const SSE_POSTMETA_PREFIX_BEFORE_1_2_0       = '_shapeshifter_extensions_';
		const SSE_THEME_POSTMETA_PREFIX_BEFORE_1_2_0 = SSE_THEME_OLD_POST_META;

	/**
	 * Static
	**/
		/**
		 * Current Version
		 * @var string Version Format
		**/
		protected static $instance = null;

	/**
	 * Properties
	**/
		/**
		 * Current Version
		 * @var string Version Format
		**/
		protected $current_version = '1.2.0';

		/**
		 * Current Version
		 * @var string Version Format
		**/
		protected $version_prefixes = array();

		/**
		 * Old Options
		 * @var array
		**/
		protected $old_options = array();

		/**
		 * Deprecated Options
		 * @var string Version Format
		**/
		protected $deprecated_option_data = array();

		/**
		 * Deprecated Options
		 * @var string Version Format
		**/
		protected $deprecated_theme_option_data = array();

		/**
		 * Deprecated Post Meta
		 * @var string Version Format
		**/
		protected $deprecated_postmeta_data = array();

		/**
		 * Deprecated Post Meta
		 * @var string Version Format
		**/
		protected $deprecated_theme_postmeta_data = array();

		/**
		 * Current Version
		 * @var string Version Format
		**/
		protected $deprecated_callables = array();

	/**
	 * Init
	**/
		/**
		 * Public Initializer
		 * @return SSE_Deprecated_Manager
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
		 * Init
		**/
		protected function init()
		{

			$this->version_prefixes = array(
				'1_2_0' => array(
					'option'         => SSE_Deprecated_Manager::SSE_OPTION_PREFIX_BEFORE_1_2_0,
					'theme_option'   => SSE_Deprecated_Manager::SSE_THEME_OPTION_PREFIX_BEFORE_1_2_0,
					'postmeta'       => SSE_Deprecated_Manager::SSE_POSTMETA_PREFIX_BEFORE_1_2_0,
					'theme_postmeta' => SSE_Deprecated_Manager::SSE_THEME_POSTMETA_PREFIX_BEFORE_1_2_0,
				),
			);

			$this->deprecated_option_data = apply_filters(
				sse()->get_prefixed_filter_hook( 'deprecated_option_data' ),
				array(
					'1_2_0' => array(
						SSE_Deprecated_Manager::SSE_OPTION_PREFIX_BEFORE_1_2_0 . 'general' => array( 
							'name'  => 'general',
							'since' => '1_2_0',
						),
						SSE_Deprecated_Manager::SSE_OPTION_PREFIX_BEFORE_1_2_0 . 'not_display_post_formats' => array( 
							'name'  => 'not_display_post_formats',
							'since' => '1_2_0',
						),
						SSE_Deprecated_Manager::SSE_OPTION_PREFIX_BEFORE_1_2_0 . 'remove_action' => array( 
							'name'  => 'remove_action',
							'since' => '1_2_0',
						),
						SSE_Deprecated_Manager::SSE_OPTION_PREFIX_BEFORE_1_2_0 . 'speed_adjust' => array( 
							'name'  => 'speed_adjust',
							'since' => '1_2_0',
						),
						SSE_Deprecated_Manager::SSE_OPTION_PREFIX_BEFORE_1_2_0 . 'seo' => array( 
							'name'  => 'seo',
							'since' => '1_2_0',
						),
						SSE_Deprecated_Manager::SSE_OPTION_PREFIX_BEFORE_1_2_0 . 'auto_insert' => array( 
							'name'  => 'auto_insert',
							'since' => '1_2_0',
						),
						SSE_Deprecated_Manager::SSE_OPTION_PREFIX_BEFORE_1_2_0 . 'others' => array( 
							'name'  => 'others',
							'since' => '1_2_0',
						),
						SSE_Deprecated_Manager::SSE_OPTION_PREFIX_BEFORE_1_2_0 . 'fonts_general' => array( 
							'name'  => 'fonts_general',
							'since' => '1_2_0',
						),
					),
				),
				ShapeShifter_Extensions::VERSION
			);

			$this->deprecated_theme_option_data = apply_filters(
				sse()->get_prefixed_filter_hook( 'deprecated_theme_option_data' ),
				array(
					'1_2_0' => array(
						SSE_Deprecated_Manager::SSE_THEME_OPTION_PREFIX_BEFORE_1_2_0 . 'widget_areas' => array( 
							'name'  => 'widget_areas',
							'since' => '1_2_0',
						),
					),
				),
				ShapeShifter_Extensions::VERSION
			);

			$this->deprecated_postmeta_data = apply_filters(
				sse()->get_prefixed_filter_hook( 'deprecated_postmeta_data' ),
				array(
					'1_2_0' => array(
						// SEO
							SSE_Deprecated_Manager::SSE_POSTMETA_PREFIX_BEFORE_1_2_0 . 'is_seo_meta_on' => array(
								'name'  => 'is_seo_meta_on',
								'since' => '1_2_0',
							),
							SSE_Deprecated_Manager::SSE_POSTMETA_PREFIX_BEFORE_1_2_0 . 'seo_meta_json' => array(
								'name'  => 'seo_meta_json',
								'since' => '1_2_0',
							),
							SSE_Deprecated_Manager::SSE_POSTMETA_PREFIX_BEFORE_1_2_0 . 'seo_meta_robots' => array(
								'name'  => 'seo_meta_robots',
								'since' => '1_2_0',
							),
							SSE_Deprecated_Manager::SSE_POSTMETA_PREFIX_BEFORE_1_2_0 . 'seo_meta_description' => array(
								'name'  => 'seo_meta_description',
								'since' => '1_2_0',
							),
							SSE_Deprecated_Manager::SSE_POSTMETA_PREFIX_BEFORE_1_2_0 . 'seo_meta_keywords' => array(
								'name'  => 'seo_meta_keywords',
								'since' => '1_2_0',
							),
						// Deactivation
							/*SSE_Deprecated_Manager::SSE_POSTMETA_PREFIX_BEFORE_1_2_0 . 'deactivate_widget_area' => array(
								'name'  => 'deactivate_widget_area',
								'since' => '1_2_0',
							),*/
						// Sub Contents
							SSE_Deprecated_Manager::SSE_POSTMETA_PREFIX_BEFORE_1_2_0 . 'sub_contents_json' => array(
								'name'  => 'sub_contents_json',
								'since' => '1_2_0',
							),
							SSE_Deprecated_Manager::SSE_POSTMETA_PREFIX_BEFORE_1_2_0 . 'sub_contents_arranged_json' => array(
								'name'  => 'sub_contents_arranged_json',
								'since' => '1_2_0',
							),
					),
				),
				ShapeShifter_Extensions::VERSION
			);

			$this->deprecated_theme_postmeta_data = apply_filters(
				sse()->get_prefixed_filter_hook( 'deprecated_theme_postmeta_data' ),
				array(
					'1_2_0' => array(
						// Icons
							SSE_Deprecated_Manager::SSE_THEME_POSTMETA_PREFIX_BEFORE_1_2_0 . 'deactivate_widget_area' => array(
								'name'  => 'deactivate_widget_area',
								'since' => '1_2_0',
							),
						// Sub Contents
							SSE_Deprecated_Manager::SSE_POSTMETA_PREFIX_BEFORE_1_2_0 . 'sub_contents_json' => array(
								'name'  => 'sub_contents_json',
								'since' => '1_2_0',
							),
							SSE_Deprecated_Manager::SSE_POSTMETA_PREFIX_BEFORE_1_2_0 . 'sub_contents_arranged_json' => array(
								'name'  => 'sub_contents_arranged_json',
								'since' => '1_2_0',
							),
					),
				),
				ShapeShifter_Extensions::VERSION
			);

			// Options
				// Generals
					$this->old_options['general'] = get_option( SHAPESHIFTER_EXTENSIONS_OPTION . 'general' );
					$this->old_options['not_display_post_formats'] = get_option( SHAPESHIFTER_EXTENSIONS_OPTION . 'not_display_post_formats' );
					$this->old_options['remove_action'] = get_option( SHAPESHIFTER_EXTENSIONS_OPTION . 'remove_action' );

				// Speed Adjust
					$this->old_options['speed_adjust'] = wp_parse_args( get_option( SHAPESHIFTER_EXTENSIONS_OPTION . 'speed_adjust' ), array(
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

				// Widget Areas
					$this->old_options['widget_areas_general'] = get_option( SSE_THEME_OPTIONS . 'widget_areas_general' );
					$this->old_options['widget_areas'] = get_option( SSE_THEME_OPTIONS . 'widget_areas' );

				// SEO
					$this->old_options['seo'] = get_option( SHAPESHIFTER_EXTENSIONS_OPTION . 'seo' );

				// Auto Insert
					$this->old_options['auto_insert'] = get_option( SHAPESHIFTER_EXTENSIONS_OPTION . 'auto_insert' );

				// Others
					$this->old_options['others'] = get_option( SHAPESHIFTER_EXTENSIONS_OPTION . 'others' );

				// Fonts
					$this->old_options['fonts_general'] = get_option( SHAPESHIFTER_EXTENSIONS_OPTION . 'fonts_general' );
					$this->old_options['fonts'] = get_option( SHAPESHIFTER_EXTENSIONS_OPTION . 'fonts' );

		}

	/**
	 * 1.2.0
	**/
		/**
		 * Option
		**/
			/**
			 * Upgrade Option Key
			 * @param string $key     :key name base
			 * @param string $version
			 * @return string
			**/
			protected function upgrade_option_key_1_2_0( $key )
			{
				$func = sprintf( 'upgrade_option_key_%1$s_%2$s', '1_2_0', $key );
				$method = array( $this, $func );
				if ( is_callable( $method ) ) {
					return call_user_func_array(
						$method,
						array( $key )
					);
				}
				return sse()->get_prefixed_option_name( $key );
			}

			/**
			 * Upgrade Option Key
			 * @param string $key     :key name base
			 * @param string $version
			 * @return string
			**/
			protected function upgrade_option_value_1_2_0( $value, $key )
			{

				$func = sprintf( 'upgrade_option_value_%1$s_%2$s', '1_2_0', $key );
				$method = array( $this, $func );
				if ( is_callable( $method ) ) {
					return call_user_func_array(
						$method,
						array( $value, $key )
					);
				}

				return $value;

			}


		/**
		 * Theme Option
		**/
			/**
			 * Upgrade Theme Option Key
			 * @param string $key     :key name base
			 * @param string $version
			 * @return string
			**/
			protected function upgrade_theme_option_key_1_2_0( $key )
			{

				$func = sprintf( 'upgrade_theme_option_key_%1$s_%2$s', '1_2_0', $key );
				$method = array( $this, $func );
				if ( is_callable( $method ) ) {
					return call_user_func_array(
						$method,
						array( $key )
					);
				}

				return sse()->get_prefixed_theme_option_name( $key );

			}

			/**
			 * Upgrade Theme Option Value
			 * @param string $value   :value name base
			 * @param string $version
			 * @return string
			**/
			protected function upgrade_theme_option_value_1_2_0( $value, $key )
			{

				$func = sprintf( 'upgrade_theme_option_value_%1$s_%2$s', '1_2_0', $value );
				$method = array( $this, $func );
				if ( is_callable( $method ) ) {
					return call_user_func_array(
						$method,
						array( $value )
					);
				}

				return $value;

			}

			/**
			 * Widget Areas
			**/
				protected function upgrade_theme_option_value_1_2_0_widget_areas( $value )
				{

					if ( is_array( $value ) ) {
						if ( 0 >= count( $value ) ) {
							return $value;
						}
						$value_in_array = $value;
					} else {
						$value_in_array = json_decode( $value, true );
						if ( null === $value_in_array ) {
							return $value;
						}
					}

					if ( ! is_array( $value_in_array ) 
						&& 0 >= count( $value_in_array ) 
					) {
						return $value;
					}

					$widget_area_data = array();
					foreach ( $value_in_array as $index => $each_widget_area ) {
						if ( ! isset( $each_widget_area['hook'] ) ) {
							continue;
						}
						$widget_area_data[] = array(
							'hook'              => $each_widget_area['hook'],
							'width'             => $each_widget_area['width'],
							'is_on_mobile_menu' => isset( $each_widget_area['is_on_mobile_menu'] ) ? $each_widget_area['is_on_mobile_menu'] : '',
							'name'              => $each_widget_area['name'],
							'description'       => $each_widget_area['description'],
						);
					}

					$value = json_encode( $widget_area_data, JSON_UNESCAPED_UNICODE );

					return $value;

				}


		/**
		 * Post Meta
		**/
			/**
			 * Upgrade Post Meta Key
			 * @param string $key     :key name base
			 * @param string $version
			 * @return string
			**/
			protected function upgrade_postmeta_key_1_2_0( $key )
			{

				$func = sprintf( 'upgrade_postmeta_key_%1$s_%2$s', '1_2_0', $key );
				$method = array( $this, $func );
				if ( is_callable( $method ) ) {
					return call_user_func_array(
						$method,
						array( $key )
					);
				}

				return sse()->get_prefixed_post_meta_name( $key );

			}

			/**
			 * Upgrade Post Meta Value
			 * @param string $key
			 * @param string $version
			 * @return string
			**/
			protected function upgrade_postmeta_value_1_2_0( $value, $key )
			{

				$func = sprintf( 'upgrade_postmeta_value_%1$s_%2$s', '1_2_0', $key );
				$method = array( $this, $func );
				if ( is_callable( $method ) ) {
					return call_user_func_array(
						$method,
						array( $value )
					);
				}

				$value = preg_replace( 
					'/([\'"])([^\'"]+)(\_\d)(\[[^\]]+\])?([\'"][\:\;])/i',
					'$1$2$4$5',
					$value
				);
				$value = preg_replace( 
					sprintf( '/([\'"])(%s)([^\'"]+)(\[[^\]]+\])?([\'"][\:\;])/i', $this->version_prefixes['1_2_0']['postmeta'] ),
					'$1$3$4$5',
					$value
				);

				return $value;
			}

			/**
			 * sub_contents_json
			**/
				protected function upgrade_postmeta_key_1_2_0_sub_contents_json( $key )
				{
					return sse()->get_prefixed_post_meta_name( $key );
				}

				protected function upgrade_postmeta_value_1_2_0_sub_contents_json( $value )
				{

					$value = preg_replace( 
						'/([\'"])([^\'"]+)(\_\d)(\[[^\]]+\])?([\'"][\:\;])/i',
						'$1$2$4$5',
						$value
					);
					$value = preg_replace( 
						sprintf( '/([\'"])(%s)([^\'"]+)(\[[^\]]+\])?([\'"][\:\;])/i', $this->version_prefixes['1_2_0']['postmeta'] ),
						'$1$3$4$5',
						$value
					);

					$json_decode_result = json_decode( $value, true );
					if ( null === $json_decode_result 
						|| 0 >= count( $json_decode_result )
					) {
						return $value;
					}

					$data = SSE_Array_Methods::get_json_settings_assoc_array( $json_decode_result );
					if ( ( is_array( $data )
							&& 0 <= count( $data )
						)
						&& ! isset( $data[0] ) 
					) {
						$temp = array();
						foreach ( $data as $index => $each_val ) {
							$new_index = intval( $index ) - 1;
							$temp[ $new_index ] = $each_val;
						}
						$data = $temp;
					}

					return json_encode( $data, JSON_UNESCAPED_UNICODE );

				}


		/**
		 * Theme Post Meta
		**/
			/**
			 * Upgrade Post Meta Key
			 * @param string $key     :key name base
			 * @param string $version
			 * @return string
			**/
			protected function upgrade_theme_postmeta_key_1_2_0( $key )
			{
				$func = sprintf( 'upgrade_theme_postmeta_key_%1$s_%2$s', '1_2_0', $key );
				$method = array( $this, $func );
				if ( is_callable( $method ) ) {
					return call_user_func_array(
						$method,
						array( $key )
					);
				}
				return sse()->get_prefixed_theme_post_meta_name( $key );
			}

			/**
			 * Upgrade Post Meta Value
			 * @param string $key
			 * @param string $version
			 * @return string
			**/
			protected function upgrade_theme_postmeta_value_1_2_0( $value, $key )
			{

				$func = sprintf( 'upgrade_theme_postmeta_value_%1$s_%2$s', '1_2_0', $key );
				$method = array( $this, $func );
				if ( is_callable( $method ) ) {
					return call_user_func_array(
						$method,
						array( $value )
					);
				}

				$value = preg_replace( 
					'/([\'"])([^\'"]+)(\_\d)(\[[^\]]+\])?([\'"][\:\;])/i',
					'$1$2$4$5',
					$value
				);
				$value = preg_replace( 
					sprintf( '/([\'"])(%s)([^\'"]+)(\[[^\]]+\])?([\'"][\:\;])/i', $this->version_prefixes['1_2_0']['theme_postmeta'] ),
					'$1$3$4$5',
					$value
				);

				return $value;
			}


	/**
	 * Test
	**/



}