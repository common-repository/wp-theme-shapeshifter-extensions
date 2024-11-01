<?php
if ( ! class_exists( 'SSE_Frontend_Manager' ) ) {
/**
 * Frontend
**/
class SSE_Frontend_Manager {

	/**
	 * Static
	**/
		/**
		 * Instance
		 * @var SSE_Frontend_Manager
		**/
		protected static $instance = null;

	/**
	 * Properties
	**/
		/**
		 * Theme Mods
		 * @var array
		**/
		protected $theme_mods = array();

		/**
		 * Get Theme Mods
		 * @return array
		**/
		public function get_theme_mods()
		{
			return sse()->get_theme_mods();
		}

		/**
		 * Instance
		 * @var array
		**/
		protected $outputs_to_widget_area_hook = array();

		/**
		 * Get outputs_to_widget_area_hook
		 * @return array
		**/
		public function get_outputs_to_widget_area_hook()
		{
			return $this->outputs_to_widget_area_hook;
		}

		/**
		 * Post Meta
		 * @var $deactivate_widget_areas
		**/
		protected $deactivate_widget_areas = array();

		/**
		 * Get outputs_to_widget_area_hook
		 * @return array
		**/
		public function get_deactivate_widget_areas()
		{
			return $this->deactivate_widget_areas;
		}

		/**
		 * Post format terms
		 * @var [array] $post_format_terms
		**/
		protected $post_format_terms = array();

		/**
		 * Get post_format_terms
		 * @return array
		**/
		public function get_post_format_terms()
		{
			return $this->post_format_terms;
		}

	/**
	 * Instances
	**/
		/**
		 * Rendering Methods
		 * @var SSE_Frontend_Rendering_Methods
		**/
		public $rendering_methods;

		/**
		 * Get Rendering Methods
		 * @return SSE_Frontend_Rendering_Methods
		**/
		public function get_rendering_methods()
		{
			if ( $this->rendering_methods instanceof SSE_Frontend_Rendering_Methods ) {
				return $this->rendering_methods;
			}
			return false;
		}

		/**
		 * Rendering Manager
		 * @var SSE_Rendering_Manager
		**/
		public $rendering_manager;

		/**
		 * Get Rendering Manager
		 * @return SSE_Rendering_Manager
		**/
		public function get_rendering_manager()
		{
			if ( $this->rendering_manager instanceof  SSE_Rendering_Manager ) {
				return $this->rendering_manager;
			}
			return false;
		}

		/**
		 * Filter Manager
		 * @var SSE_Frontend_Filter_Manager
		**/
		public $filter_manager;

		/**
		 * Get Filter Manager
		 * @return SSE_Frontend_Filter_Manager
		**/
		public function get_filter_manager()
		{
			if ( $this->filter_manager instanceof SSE_Frontend_Filter_Manager ) {
				return $this->filter_manager;
			}
			return false;
		}



	/**
	 * Tools
	**/
		/**
		 * Need Header Logo
		 * @return bool
		**/
		public function need_header_logo()
		{
			$need_header_logo = false;
			$has_background_image = '' !== $this->theme_mods['header_image_background_image'] ? true : false;
			$has_header_image     = '' !== $this->theme_mods['header_image_url'] ? true : false;
			$display_title        = shapeshifter_boolval( $this->theme_mods['header_image_title_display_toggle'] );
			$display_description  = shapeshifter_boolval( $this->theme_mods['header_image_description_display_toggle'] );
			if ( $has_background_image
				|| $has_header_image
				|| $display_title
				|| $display_description
			) {
				$need_header_logo = true;
			}
			return shapeshifter_boolval( $need_header_logo );
		}

	/**
	 * Init
	**/
		/**
		 * Public Initializer
		 * @return SSE_Frontend_Manager
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

			$this->options = sse()->get_options();


			# Custom CSS
				$this->applied_css_files_list = get_option( sse()->get_prefixed_theme_option_name( 'apply_selected_css' ), array() );

			# Async Target Settings
				$script_handles = $this->options['speed_adjust']->get_prop( 'async_script_tags' );
				$script_handles_preg_str = '/([^, ]+)/';
				if ( preg_match_all( $script_handles_preg_str, $script_handles, $script_handles_preg_str_s ) ) {
					$this->script_handles_array = $script_handles_preg_str_s[ 0 ];
				}
				//print_r( $this->script_handles_array );

			# Post Formats
				foreach( $this->options['not_display_post_formats'] as $post_format => $value ) {
					if( $value != '' ) {
						$this->post_format_terms[] = esc_attr( 'post-format-' . $post_format );
					}
				}

			# Mods
				$this->init_classes();
				$this->init_hooks();
				//$this->remove_actions();
				//$this->change_actions();
				//$this->add_actions();
				//$this->add_filters();
				//
			//
				do_action( 'sse_action_init_frontend', $this );

		}

		protected function init_classes()
		{
			//$this->page_view_counter = SSE_Page_View_Count::get_instance( self::$options );
			$this->rendering_methods = SSE_Frontend_Rendering_Methods::get_instance( $this );
			$this->rendering_manager = SSE_Rendering_Manager::get_instance( $this );
			$this->filter_manager    = SSE_Frontend_Filter_Manager::get_instance( $this );
		}

		/**
		 * Init WP Hooks
		**/
		protected function init_hooks() {

			add_action( shapeshifter()->get_prefixed_action_hook( 'setup_theme_mods' ), array( $this, 'setup_theme_mods' ) );

			// Remove
				// WordPress Default Actions
				if( $this->options['remove_action']->get_prop( 'rsd_link' ) ) remove_action( 'wp_head', 'rsd_link' );
				if( $this->options['remove_action']->get_prop( 'wlwmanifest_link' ) ) remove_action( 'wp_head', 'wlwmanifest_link' );
				if( $this->options['remove_action']->get_prop( 'wp_generator' ) ) remove_action( 'wp_head', 'wp_generator' );
				if( $this->options['remove_action']->get_prop( 'feed_links_extra' ) ) remove_action( 'wp_head', 'feed_links_extra', 3 );
				if( $this->options['remove_action']->get_prop( 'feed_links' ) ) remove_action( 'wp_head', 'feed_links', 2 );
				if( $this->options['remove_action']->get_prop( 'index_rel_link' ) ) remove_action( 'wp_head', 'index_rel_link' );
				if( $this->options['remove_action']->get_prop( 'parent_post_rel_link' ) ) remove_action( 'wp_head', 'parent_post_rel_link', 10, 0 );
				if( $this->options['remove_action']->get_prop( 'start_post_rel_link' ) ) remove_action( 'wp_head', 'start_post_rel_link', 10, 0 );
				if( $this->options['remove_action']->get_prop( 'adjacent_posts_rel_link_wp_head' ) ) remove_action( 'wp_head', 'adjacent_posts_rel_link_wp_head', 10, 0 );

			// Pre Get Posts
				add_action( 'pre_get_posts', array( $this, 'pre_get_posts' ), 1 );

			// Posts Selection
				add_action( 'posts_selection', array( $this, 'posts_selection' ), 1 );

			// Enqueue CSS JS
				add_action( 'wp_enqueue_scripts', array( $this, 'wp_enqueue_scripts' ), 100 );

			// After Setup Post Meta Vars
			if ( ! is_admin() ) {
				add_action( shapeshifter()->get_prefixed_action_hook( 'init_frontend_post_meta' ), array( $this, 'set_post_meta_vars_with_post_id' ) );
			}

			// Methods Except Page Generators
				// Init Public
					// After Define Classes
						add_action( 'shapeshifter_frontend_after_define_classes', array( $this, 'public_extended_after_define_classes' ) );

			// SEO
				// JSON LD
					if( $this->options['seo']->get_prop( 'json_ld_markup_on' ) ) { add_action( 'wp_head', array( $this, 'json_ld_markup' ), 2 ); }
				// Prev Next
					if( $this->options['seo']->get_prop( 'insert_prev_next_link' ) ) { add_action( 'wp_head', array( $this, 'insert_prev_next_link' ), 2 ); }
				// Canonical Link
					if( $this->options['seo']->get_prop( 'canonical_link_on' ) ) { add_action( 'wp_head', array( $this, 'canonical_link' ), 2 ); }
				// Meta Robots
					add_action( 'wp_head', array( $this, 'seo_meta_robots' ), 2 );
				// Meta Description
					add_action( 'wp_head', array( $this, 'meta_description' ), 2 );
				// Meta Keywords
					add_action( 'wp_head', array( $this, 'meta_keywords' ), 2 );	
				// Twitter Card
					if( $this->options['seo']->get_prop( 'twitter_card_on' ) ) { add_action( 'wp_head', array( $this, 'twitter_card' ), 2 ); }
				// Open Graph
					if( $this->options['seo']->get_prop( 'open_graph_on' ) ) { add_action( 'wp_head', array( $this, 'open_graph' ), 2 ); }
				// Google Plus
					add_action( 'wp_head', array( $this, 'google_plus_url' ), 2 );

			// Auto Insert
				// in HEAD Tag
					add_action( 'wp_head', array( $this, 'add_to_wp_head' ), 2 );
				// Starting BODY Tag
					add_action( 'shapeshifter_body_wrapper_start', array( $this, 'after_starting_body_code' ), 2 );
				// Footer ( Before Closing BODY Tag )
					add_action( 'wp_footer', array( $this, 'add_to_wp_footer' ), 2 );

			// Header Logo
				add_action( 'shapeshifter_header_logo', array( $this->rendering_methods, 'shapeshifter_header_logo' ) );

			// Read Later
				add_action( 'shapeshifter_archive_read_later', array( $this->rendering_methods, 'shapeshifter_archive_read_later' ), 10, 2 );
					add_action( 'sse_share_buttons', array( $this->rendering_methods, 'share_buttons' ), 10, 2 );
					add_action( 'sse_share_icons', array( $this->rendering_methods, 'share_icons' ), 10, 2 );

			// AJAX Load
				add_action( 'shapeshifter_pagination', array( $this->rendering_methods, 'shapeshifter_ajax_pagination' ), 9 );

			// Post Meta
				add_action( 'shapeshifter_post_meta_outputs_in_widget_area_hook', array( $this->rendering_methods, 'shapeshifter_post_meta_outputs_in_widget_area_hook' ) );


			// wp_footer
				add_action( 'wp_footer', array( $this->rendering_methods, 'print_in_wp_footer' ), 100 );

			// Change
				add_filter( 'shapeshifter_filters_walker_nav_menu_instance', array( $this, 'filter_walker_nav_menu_instance' ), 10, 2 );
				//remove_action( 'shapeshifter_post_meta_outputs_in_widget_area_hook', array( parent, 'post_meta_outputs_in_widget_area_hook' ) );
				//add_action( 'shapeshifter_post_meta_outputs_in_widget_area_hook', array( $this, 'post_meta_outputs_in_widget_area_hook' ) );

		}

		public function setup_theme_mods()
		{
			// Get Theme Mods Settings by Theme Customizer
				$this->theme_mods = sse()->get_theme_mods();
		}

		/**
		 * WP Enqueue Scripts
		**/
		public function wp_enqueue_scripts() {

			// CSS
				// Theme Mods Styles
					$style_manager = sse()->get_style_manager();
					$style_manager->set_styles();
					$this->styles = $style_manager->font_face_style;
					$this->styles .= (
						SSE_IS_MOBILE 
						? $style_manager->styles['mobile']['total'] 
						: $style_manager->styles['pc']['total'] 	
					);

				// Post Meta
					// Optional CSS
					if ( isset( $this->post_metas[ sse()->get_prefixed_theme_post_meta_name( 'is_fa_icons_mods_on' ) ] ) 
						&& 'is_fa_icons_mods_on' === $this->post_metas[ sse()->get_prefixed_theme_post_meta_name( 'is_fa_icons_mods_on' ) ] 
			
					) {

						// Settings for Each Article
						$this->styles .= $style_manager->get_post_meta_icon_style( $this->post_metas );

					}
					if ( is_customize_preview() ) {
						$this->styles .= '
							.hatena-icon-in-widget-li-icon, 
							.shapeshifter-post-list-hatena-icon-li {
								line-height: 1;
							}
						';
					}

				// General
					//wp_enqueue_style( 'shapeshifter-style' );
					wp_add_inline_style(
						'sse-frontend-general', 
						wp_strip_all_tags( $this->styles, true ) 
					);
					wp_enqueue_style( 'sse-frontend-general' );

				// Custom CSS
					if( is_array( $this->applied_css_files_list ) ) { 
						foreach( $this->applied_css_files_list as $index => $data ) {
							wp_enqueue_style( 
								$data . '_custom_css', 
								esc_url( SITE_URL . '/wp-content/uploads/custom-css/' . $data . '.css' )
							);
						}
					}

				// Google Fonts
					$google_fonts_url = get_option( sse()->get_prefixed_option_name( 'applied_google_fonts_css_url' ) );
					wp_enqueue_style( 'google-fonts', esc_url( $google_fonts_url ), array(), null );

			// JS
				// General
					/*
					wp_localize_script( 
						'sse-frontend', 
						'shapeshifterCSS', 
						array(
							'bootstrap'     => SHAPESHIFTER_THIRD_DIR_URI . 'bootstrap/css/bootstrap.min.css',
							'fontAwesome'   => SHAPESHIFTER_THIRD_DIR_URI . 'font-awesome/css/font-awesome.min.css',
							'CSSAnimate'    => SHAPESHIFTER_THIRD_DIR_URI . 'animate.css-master/animate.min.css',
							'magnificPopup' => SHAPESHIFTER_THIRD_DIR_URI . 'Magnific-Popup/dist/magnific-popup.min.css',
							'style'         => SHAPESHIFTER_THIRD_DIR_URI . 'animate.css-master/style.css',
						)
					);
					*/

					wp_localize_script( 'sse-frontend', 'sseFrontendStyles', sse()->get_frontend_css() );
					wp_enqueue_script( 'sse-frontend' );

				// Google+
					wp_enqueue_script( 'google-plus', 'https://apis.google.com/js/platform.js', array(), false, true );
				// Hatena
					wp_enqueue_script( 'hatena-bookmark', 'https://b.st-hatena.com/js/bookmark_button.js', array(), false, true );

				// Google Map
					/*$google_browser_api_key = get_option( sse()->get_prefixed_option_name( 'google_fonts_api_key' ) );
					if( ! empty( $google_browser_api_key ) ) {
						$google_map_callback = 'shapeshifterGoogleMapInit';
						wp_enqueue_script( 
							'google-map-api',
							'https://maps.googleapis.com/maps/api/js?key=' . $google_browser_api_key . '&callback=' . $google_map_callback,
							array( 'sse-frontend' ),
							null,
							true
						);
					}*/

		}

	#
	# Actions
	#
		// Methods Except Page Generators
			/**
			 * Pre Get Posts
			**/
			function pre_get_posts( $query ) {

			    if ( is_admin() || ! $query->is_main_query() )
			        return;

			    if ( is_home() ) {

					# Post Format
						foreach( $this->options['not_display_post_formats']->get_data() as $post_format => $value ) {
							if( ! empty( $value ) )
								$this->post_format_terms[] = 'post-format-' . $post_format;
						}

					if( is_home() && is_front_page() ) {

						$args = array(
							'tax_query' => array(
								array(
									'taxonomy' => 'post_format',
									'field'	=> 'slug',
									'terms'	=> $this->post_format_terms,
									'operator' => 'NOT IN'
								),
							),
							'paged' => ( get_query_var( 'paged' ) ? get_query_var( 'paged' ) : 1 ),
						);
						$query->parse_query( $args );
						$this->post_format_terms = null;

					} elseif( is_front_page() ) {

					} elseif( is_home() || is_post_type_archive( 'post' ) ) {

						$args = array(
							'tax_query' => array(
								array(
									'taxonomy' => 'post_format',
									'field'	=> 'slug',
									'terms'	=> $this->post_format_terms,
									'operator' => 'NOT IN'
								),
							),
							'paged' => ( get_query_var( 'paged' ) ? get_query_var( 'paged' ) : 1 ),
						);
						$query->parse_query( $args );
						$this->post_format_terms = null;

					}

			    }

			}

			/**
			 * Posts Selection
			**/
			function posts_selection( $query ) {

			}

			/**
			 * After Define Classes
			**/
			function public_extended_after_define_classes() {

				if( SHAPESHIFTER_IS_LAZYLOAD_ON && ! is_customize_preview() ) {
					array_push( 
						shapeshifter()->get_frontend_manager()->classes['wrapper'], 
						'shapeshifter-body-lazyload-on'
					);
				}

				if( $this->theme_mods['theme_skin'] !== 'none' ) {
					array_push( 
						shapeshifter()->get_frontend_manager()->classes['wrapper'], 
						esc_attr( 'shapeshifter-skin-' . $this->theme_mods['theme_skin'] )
					);
				} unset( $theme_skin );

			}

		// Setup Post Meta
			/**
			 * Setup Vars for Post Meta
			**/
			function set_post_meta_vars_with_post_id() {

				// Post ID
					global $wp_query;
					if ( is_home() && is_front_page() ) { # Home
						return;
					} elseif ( is_front_page() ) { # Front Page

						global $post; $post_id = intval( $post->ID );

					} elseif ( is_home() ) { # Blog

						$this->home_id = intval( get_option( 'page_for_posts' ) );
						$post_id = intval( $this->home_id );

					} elseif ( is_singular() ) { # Singular

						global $post; $post_id = intval( $post->ID );

					} else {
						return;
					}

				# Post Meta
					$this->post_metas = get_post_meta( $post_id );
					$this->post_meta_seo = json_decode( get_post_meta( $post_id, sse()->get_prefixed_post_meta_name( 'seo_meta_json' ), true ), true );

				# Remove Data Except "index: 0"
					if ( is_array( $this->post_metas ) ) { 
						foreach( $this->post_metas as $index => $data ) {
							$this->post_metas[ $index ] = esc_attr( 
								isset( $data[ 0 ] ) 
									&& ( $data[ 0 ] != '' ) 
								? $data[ 0 ]
								: ''
							);
						} 
					}

				# SEO Switch
					$this->is_seo_meta_on = isset( $this->post_meta_seo['is_seo_meta_on'] ) && 'is_seo_meta_on' === $this->post_meta_seo['is_seo_meta_on'];

				# Settings for Deactivation of Widget Areas
					// the Const "SHAPESHIFTER_THEME_POST_META" is defined in the Theme "ShapeShifter"
					$deactivate_widget_areas = get_post_meta( $post_id, sse()->get_prefixed_theme_post_meta_name( 'deactivate_widget_area' ), true );
					$this->deactivate_widget_areas = ( 
						( 
							is_array( $deactivate_widget_areas ) 
							&& 0 < count( $deactivate_widget_areas )
						)
						? $deactivate_widget_areas
						: array()
					);

				unset( $this->post_metas[ sse()->get_prefixed_post_meta_name( 'is_seo_meta_on' ) ], $this->post_metas[ sse()->get_prefixed_theme_post_meta_name( 'deactivate_widget_area' ) ] );

				# Optional Output by Post Meta
					$this->outputs_to_widget_area_hook = $this->shapeshifter_get_post_meta_to_print_in_widget_area_hook( intval( $post_id ) );

			}

				/**
				 * Optional Output by Post Meta
				 * 
				 * @param int $post_id
				**/
				function shapeshifter_get_post_meta_to_print_in_widget_area_hook( $post_id ) {

					$data = array();

					$sub_contents_json_data_string = get_post_meta( $post_id, sse()->get_prefixed_theme_post_meta_name( 'sub_contents_json' ), true );
					$sub_contents_json = SSE_Data_Post_Meta::get_instance( $post_id, 'sub_contents_json', array() )->get_data();
					$widgets_arranged_data = $sub_contents_json;

					$item_num_max = intval( get_post_meta( $post_id, sse()->get_prefixed_post_meta_name( 'items_number_for_the_content' ), true ) );

					if ( ! is_array( $widgets_arranged_data ) || 0 >= count( $widgets_arranged_data ) ) {
						return $data;
					}
					foreach ( $widgets_arranged_data as $item_num => $item_data ) {

						$image_preg_str = '/([, ]+)/';
						$background_images = preg_replace(
							'/[\s]+/i',
							'',
							isset( $widgets_arranged_data[ $item_num ]['sub_content_background_images'] )
							? $widgets_arranged_data[ $item_num ]['sub_content_background_images']
							: ''
						);
						$background_images_array = array();
						$background_images_array = preg_split( '/([, ]+)/', $background_images );

						$data[] = array(
							// 一般
								'title' => esc_attr( 
									isset( $widgets_arranged_data[ $item_num ]['sub_content_item_title'] )
									? $widgets_arranged_data[ $item_num ]['sub_content_item_title']
									: ''
								),
								'type' => esc_attr(
									isset( $widgets_arranged_data[ $item_num ]['sub_content_item_type'] )
									? $widgets_arranged_data[ $item_num ]['sub_content_item_type']
									: ''
								),
								'hook' => esc_attr(
									isset( $widgets_arranged_data[ $item_num ]['sub_content_item_hook'] )
									? $widgets_arranged_data[ $item_num ]['sub_content_item_hook']
									: ''
								),
							// 共通
								'device_detect' => (
									isset( $widgets_arranged_data[ $item_num ]['sub_content_device_detect'] )
									? $widgets_arranged_data[ $item_num ]['sub_content_device_detect']
									: ''
								),
								'title_is_display' => esc_attr(
									isset( $widgets_arranged_data[ $item_num ]['sub_content_title_is_display'] )
									? $widgets_arranged_data[ $item_num ]['sub_content_title_is_display']
									: ''
								),
								'text_color' => esc_attr(
									isset( $widgets_arranged_data[ $item_num ]['sub_content_text_color'] )
									? $widgets_arranged_data[ $item_num ]['sub_content_text_color']
									: ''
								),
								'background_images' => $background_images_array,

							// スライダー
								'slider_item_num' => esc_attr(
									isset( $widgets_arranged_data[ $item_num ]['sub_content_slider_item_num'] )
									? $widgets_arranged_data[ $item_num ]['sub_content_slider_item_num']
									: ''
								),
								'slider_type' => esc_attr(
									isset( $widgets_arranged_data[ $item_num ]['sub_content_slider_type'] )
									? $widgets_arranged_data[ $item_num ]['sub_content_slider_type']
									: ''
								),
								'slider_style_type' => (
									isset( $widgets_arranged_data[ $item_num ]['sub_content_slider_style_type'] )
									? $widgets_arranged_data[ $item_num ]['sub_content_slider_style_type']
									: ''
								),
								'post_formats_is_display' => (
									isset( $widgets_arranged_data[ $item_num ]['sub_content_slider_post_formats'] )
									? $widgets_arranged_data[ $item_num ]['sub_content_slider_post_formats']
									: ''
								),
							// 配置済みウィジェット
								'widget_from_widget_area' => (
									isset( $widgets_arranged_data[ $item_num ]['sub_content_widget_item_data'] )
									? $widgets_arranged_data[ $item_num ]['sub_content_widget_item_data']
									: ''
								),
							// 新規登録ウィジェット
								'new_registered_widget_selected_class' => (
									isset( $widgets_arranged_data[ $item_num ]['sub_content_new_registered_widget_selected_class'] )
									? $widgets_arranged_data[ $item_num ]['sub_content_new_registered_widget_selected_class']
									: ''
								),
								'new_registered_widget_instance' => (
									isset( $widgets_arranged_data[ $item_num ]['sub_content_new_registered_widget_instance'] )
									? $widgets_arranged_data[ $item_num ]['sub_content_new_registered_widget_instance']
									: ''
								)

						);

					}

					return $data;

				}

			/**
			 * Set 
			 * 
			 * @param array $post_formats: Associative Array which has boolean value for each index named post-format
			 * 
			**/
			function shapeshifter_get_tax_query_post_formats( $post_formats ) {

				$post_formats = wp_parse_args( $post_formats, 
					array(
						'standard' => true,
						'aside' => true,
						'gallery' => true,
						'image' => true,
						'link' => true,
						'quote' => true,
						'status' => true,
						'video' => true,
						'audio' => true,
						'chat' => true,
					)
				);

				$post_format_terms = array();
				if( $post_formats['standard'] != '' ) {
					
					foreach( $post_formats as $post_format => $text ) {

						if( $post_format == 'standard' ) continue;
						if( $text == '' )
							$post_format_terms[] = esc_attr( 'post-format-' . $post_format );

					}

					return array(
						'taxonomy' => 'post_format',
						'field'	=> 'slug',
						'terms'	=> $post_format_terms,
						'operator' => 'NOT IN'
					);

				} else {

					foreach( $post_formats as $post_format => $text ) {

						if( $post_format == 'standard' ) continue;
						if( $text != '' ) 
							$post_format_terms[] = esc_attr( 'post-format-' . $post_format );

					}

					return array(
						'taxonomy' => 'post_format',
						'field'	=> 'slug',
						'terms'	=> $post_format_terms,
					);

				}

			}

		// SEO
			/**
			 * JSON LD Markup
			**/
			function json_ld_markup() {

				echo '<!-- ' . shapeshifter()->get_theme_data( 'Name' ) . ' JSON LD MARKUP -->' . PHP_EOL;

				$website = json_encode( array(
					"@context" => esc_url( "http://schema.org" ),
					"@type" => "Website",
					"name" => SHAPESHIFTER_SITE_NAME,
					"url" => esc_url( trailingslashit( home_url() ) ),
					"potentialAction" => array(
						"@type" => "SearchAction",
						"target" => esc_url( trailingslashit( home_url() ) ) . "?s={search_term_string}",
						"query-input" => "required name=search_term_string"
						),
				) );
				echo '<script type="application/ld+json">' . $website . '</script>' . PHP_EOL; $website = null;

				if( is_singular( 'post' ) ) {
					global $post;
					if ( have_posts() ) {
						while ( have_posts() ) {
							the_post();
							$page_title = esc_html( wp_strip_all_tags( get_the_title() ) );
							$author_name = esc_html( wp_strip_all_tags( get_the_author() ) );
							$date_published = esc_attr( get_the_date( 'Y-n-j' ) );
							$date_modified = esc_attr( get_the_modified_time( 'Ymd' ) );
							$image_id = get_post_thumbnail_id();
							$image = ( $image_id 
								? wp_get_attachment_image_src( $image_id, 'full' ) 
								: array( 
									esc_url( get_theme_mod( 'default_thumbnail_image', SSE_ASSETS_URL . 'images/no-img.png' ) ),
									'150',
									'150'
								)
							); $image_id = null;
							$logo_image = wp_get_attachment_image_src( $this->options['seo']->get_prop( 'json_ld_logo' ) );

							$article_body = get_the_content();
							$url = esc_url( get_permalink() );

							$json_post = array(
								'@context' => 'http://schema.org',
								'@type' => 'Article',
								'mainEntityOfPage' => array(
									'@type' => 'WebPage',
									'@id' => $url,
								),
								'name' => $page_title,
								'author' => array(
									'@type' => 'Person',
									'name' => $author_name
								),
								'datePublished' => $date_published,
								'dateModified' => $date_modified,
								'image' => array(
									'@type' => 'ImageObject',
									'url' => $image[ 0 ],
									'width' => ( $image[ 1 ] > 696 ? $image[ 1 ] : 696 ),
									'height' => ( $image[ 1 ] > 696 ? $image[ 2 ] : 696 )
								),
								'articleBody' => $article_body,
								'url' => $url,
								'publisher' => array(
									'@type' => 'Organization',
									'name' => SHAPESHIFTER_SITE_NAME,
									'logo' => array(
										'@type' => 'ImageObject',
										'url' => esc_url( $logo_image[ 0 ] ),
										'width' => absint( $logo_image[ 1 ] <= 600 ? $logo_image[ 1 ] : 600 ),
										'height' => absint( $logo_image[ 1 ] <= 60 ? $logo_image[ 2 ] : 60 ),
									)
								),
								'headline' => esc_html( $page_title ),
							); $page_title = $author_name = $date_published = $date_modified = $image = $logo_image = $article_body = $url = null;
							
							$article_section = array();
							$cats = get_the_terms( $post->ID, 'category' );
							if( ! empty( $cats ) ) {
								if( is_array( $cats ) ) { foreach( $cats as $cat ) {
									$article_section[] = esc_attr( wp_strip_all_tags( $cat->name ) );
								} }
							} $cats = null;
							$tags = get_the_terms( $post->ID, 'post_tag' );
							if( ! empty( $tags ) ) {
								if( is_array( $tags ) ) { foreach( $tags as $tag ) {
									$article_section[] = esc_attr( wp_strip_all_tags( $tag->name ) );
								} }
							}
							$json_post['articleSection'] = $article_section; $article_section = null;

							$json_post = json_encode( $json_post );
							echo '<script type="application/ld+json">' . $json_post . '</script>' . PHP_EOL; $json_post = null;
						}
					}
					rewind_posts();
				}

			}

			/**
			 * Link Tags for Prev and Next
			**/
			function insert_prev_next_link() {

				echo '<!-- ' . shapeshifter()->get_theme_data( 'Name' ) . ' Prev Next Link -->' . PHP_EOL;
				if( is_singular() ) {
					//1ページを複数に分けた分割ページ（マルチページ）でのタグ出力
					global $post, $page;
					$num_pages = substr_count( $post->post_content, '<!--nextpage-->' ) + 1;
					if( $num_pages >= 2 ) {

						$prev_num = $next_num = 0;
						if( $page !== 0 )
							$prev_num = $page - 1;
						if( $num_pages - $page !== 0 )
							$next_num = $page + 1;
						if( $prev_num === 1 ) {
							echo '<link rel="prev" href="' . esc_url( get_permalink( $post->ID ) ) . '" />' . PHP_EOL;
						} elseif( $prev_num >= 2 ) {
							echo '<link rel="prev" href="' . esc_url( get_permalink( $post->ID ) ) . intval( $prev_num ) . '/" />' . PHP_EOL;
						}
						if( $next_num ) {
							echo '<link rel="next" href="' . esc_url( get_permalink( $post->ID ) ) . intval( $next_num ) . '/" />' . PHP_EOL;
						}
					} $num_pages = $page = $prev_num = $next_num = null;
				} else {
					//トップページやカテゴリページなどのページネーションでのタグ出力
					global $paged;
					if ( get_previous_posts_link() ){
						echo '<link rel="prev" href="' . esc_url( get_pagenum_link( $paged - 1 ) ) . '" />' . PHP_EOL;
					}
					if ( get_next_posts_link() ){
						echo '<link rel="next" href="' . esc_url( get_pagenum_link( $paged + 1 ) ) . '" />' . PHP_EOL;
					}
				}

			}

			/**
			 * Canonical Link
			**/
			function canonical_link() {

				echo '<!-- ' . shapeshifter()->get_theme_data( 'Name' ) . ' Canonical Link -->' . PHP_EOL;
				if( is_home() && is_front_page() ) {
					echo '<link rel="canonical" href="' . esc_url( SITE_URL ) . '" />';
				} elseif( is_front_page() ) {
					echo '<link rel="canonical" href="' . esc_url( SITE_URL ) . '" />';
				} elseif( is_home() && ! is_front_page() ) {
					echo '<link rel="canonical" href="' . esc_url( get_permalink( get_option( 'page_for_posts' ) ) ) . '" />';
				} elseif( is_category() ) {
					$term_url = esc_url( get_category_link( get_query_var( 'cat' ) ) );
					echo '<link rel="canonical" href="' . $term_url . '" />';
				} elseif( is_tag() ) {
					$term_url = esc_url( get_tag_link( get_query_var( 'tag_id' ) ) );
					echo '<link rel="canonical" href="' . $term_url . '" />';
				} elseif( is_day() ) {
					$term_url = esc_url( get_month_link( get_the_time( 'Y' ), get_the_time( 'n' ). get_the_time( 'j' ) ) );
					echo '<link rel="canonical" href="' . $term_url . '" />';
				} elseif( is_month() ) {
					$term_url = esc_url( get_month_link( get_the_time( 'Y' ), get_the_time( 'n' ) ) );
					echo '<link rel="canonical" href="' . $term_url . '" />';
				} elseif( is_year() ) {
					$term_url = esc_url( get_year_link( get_the_time( 'Y' ) ) );
					echo '<link rel="canonical" href="' . $term_url . '" />';
				} elseif( is_author() ) {
					$term_url = esc_url( get_author_posts_url( get_query_var( 'author' ) ) );
					echo '<link rel="canonical" href="' . $term_url . '" />';
				} elseif( is_singular() ) {
					global $post;
					echo '<link rel="canonical" href="' . esc_url( get_permalink( $post->ID ) ) . '" />';
				} $term_url = null;
				echo PHP_EOL;

			}

			/**
			 * Meta Robots
			**/
			function seo_meta_robots() {

				echo '<!-- ' . shapeshifter()->get_theme_data( 'Name' ) . ' SEO Meta Robots -->' . PHP_EOL;
				if( is_tag() || is_search() || is_404() ) {
					echo '<meta name="robots" content="noindex,follow">' . PHP_EOL;
					return;
				}

				global $page, $paged;
				if( ( is_home() && is_front_page() ) || is_front_page() ) {
				} elseif( 
					( is_home() 
						|| is_singular() 
					)
					&& ( $this->is_seo_meta_on )
				) {
					// checkboxes
					if( ! isset( $this->post_meta_seo['seo_meta_robots'] ) 
						|| $this->post_meta_seo['seo_meta_robots'] == 'index,follow' 
					) {
						return;
					} else { 
						echo '<meta name="robots" content="' . esc_attr( $this->post_meta_seo['seo_meta_robots'] ) . '">';
					} $this->post_meta_seo['seo_meta_robots'] = null;

				} else if( $this->options['seo']->get_prop( 'meta_robots_on' ) ) {
					if( ( is_home() || is_front_page() || is_singular() || is_category() ) 
					&& ! ( $paged >= 2 || $page >= 2 ) ) {
						return;
					} 
					echo '<meta name="robots" content="noindex,follow">';
				}
				echo PHP_EOL;

			}

			/**
			 * Meta Description
			**/
			function meta_description() {

				echo '<!-- ' . shapeshifter()->get_theme_data( 'Name' ) . ' SEO Meta Description -->' . PHP_EOL;
				if( is_404() || is_search() ) { 
					return;
				}
				if( is_home() && is_front_page() ) {
					echo '<meta name="description" content="' . esc_attr( SHAPESHIFTER_SITE_DESCRIPTION ) . '">' . PHP_EOL;
					return;
				} elseif( is_front_page() ) {
					echo '<meta name="description" content="' . esc_attr( SHAPESHIFTER_SITE_DESCRIPTION ) . '">' . PHP_EOL;
					return;
				} elseif( is_home() || is_singular() ) {

					if( is_home() ) {
						$post = get_post( $this->home_id );
					} else {
						global $post;
					}

					$meta_description = isset( $this->post_meta_seo['seo_meta_description'] ) ? $this->post_meta_seo['seo_meta_description'] : '';
					$post_excerpt = ( $this->options['seo']->get_prop( 'meta_description_on' ) 
						? esc_attr( sse_get_the_excerpt( $post->post_content ) )
						: '' 
					);

					$meta_description = esc_attr( 
						( $this->is_seo_meta_on )
						? ( $meta_description 
							? $meta_description 
							: $post_excerpt 
						) 
						: $post_excerpt
					);
					if( $meta_description != '' ) {
						echo '<meta name="description" content="' . $meta_description . '">' . PHP_EOL;
						return;
					}
				} else {
					return;
				}

			}

			/**
			 * Meta Keywords
			**/
			function meta_keywords() {

				echo '<!-- ' . shapeshifter()->get_theme_data( 'Name' ) . ' SEO Meta Keywords -->' . PHP_EOL;
				if( ( is_home() && is_front_page() ) || is_search() || is_404() ) {
					return;
				} elseif( is_home() || is_front_page() || is_singular( 'post' ) ) {
					if( is_singular( 'post' ) ) {
						global $post;
					}
					
					$cats_tags = '';
					if( is_singular( 'post' ) ) {
						$cats = get_the_terms( $post->ID, 'category' );
						if( ! empty( $cats ) ) {
							if( is_array( $cats ) ) { foreach( $cats as $cat ) {
								$cats_tags .= esc_html( $cat->name ) . ',';
							} }
						}
						$tags = get_the_terms( $post->ID, 'post_tag' );
						if( ! empty( $tags ) ) {
							if( is_array( $tags ) ) { foreach( $tags as $tag ) {
								$cats_tags .= esc_html( $tag->name ) . ',';
							} }
						}
						$cats_tags = ( $this->options['seo']->get_prop( 'meta_keywords_on' )
							? substr( $cats_tags, 0, strlen( $cats_tags ) - 1 )
							: ''
						);
					}
					$meta_keywords = ( 
						( isset( $this->post_meta_seo['seo_meta_keywords'] ) 
							&& $this->post_meta_seo['seo_meta_keywords'] != '' )
						? $this->post_meta_seo['seo_meta_keywords']
						: $cats_tags
					);
					$meta_keywords = esc_attr( 
						$this->is_seo_meta_on
						? $meta_keywords
						: ''
					);
					if( $meta_keywords != '' )
						echo '<meta name="keywords" content="' . $meta_keywords . '">' . PHP_EOL;
					return;
				} else {
					return;
				}

			}

			/**
			 * Twitter Card
			**/
			function twitter_card() {

				echo '<!-- ' . shapeshifter()->get_theme_data( 'Name' ) . ' Twitter Card -->' . PHP_EOL;
				if( is_404() || is_search() ) { return; }
				// 「Summary」の出力
				echo '<meta name="twitter:card" content="summary" />' . PHP_EOL;

				$twitter_card_account = ( get_user_meta( get_the_author_meta( 'ID' ), 'twitter', true )
					? get_user_meta( get_the_author_meta( 'ID' ), 'twitter', true )
					: $this->options['seo']->get_prop( 'twitter_card_account' )
				);
				if( ! empty( $twitter_card_account ) ) {
					// 「Site」の出力
					echo '<meta name="twitter:site" content="@' . esc_attr( $twitter_card_account ) . '" />' . PHP_EOL;	
				}
				if( is_home() && is_front_page() ) {
					// 「Title」「Description」「Image」「URL」の出力
					echo '<meta name="twitter:title" content="' . esc_attr( SHAPESHIFTER_SITE_NAME . ' - ' . SHAPESHIFTER_SITE_DESCRIPTION ) . '" />' . PHP_EOL . 
					'<meta name="twitter:description" content="' . esc_attr( SHAPESHIFTER_SITE_DESCRIPTION ) . '" />' . PHP_EOL .
					'<meta name="twitter:image" content="' . esc_url( $this->options['seo']->get_prop( 'tc_og_image' ) ) . '" />' . PHP_EOL .
					'<meta name="twitter:url" content="' . esc_url( SITE_URL ) . '" />';	
				} elseif( is_front_page() ) { 
					// 「Title」「Description」「Image」「URL」の出力
					echo '<meta name="twitter:title" content="' . esc_attr( SHAPESHIFTER_SITE_NAME . ' - ' . SHAPESHIFTER_SITE_DESCRIPTION ) . '" />' . PHP_EOL . 
					'<meta name="twitter:description" content="' . esc_attr( SHAPESHIFTER_SITE_DESCRIPTION ) . '" />' . PHP_EOL .
					'<meta name="twitter:image" content="' . esc_url( $this->options['seo']->get_prop( 'tc_og_image' ) ) . '" />' . PHP_EOL .
					'<meta name="twitter:url" content="' . esc_url( SITE_URL ) . '" />';	
				} elseif( is_home() || is_singular() ) { 
					if( is_home() ) {
						$post = get_post( $this->home_id );
					} else {
						global $post;
					}
					$meta_description = esc_attr( 
						isset( $this->post_meta_seo['seo_meta_description'] )
							&& $this->post_meta_seo['seo_meta_description'] !== ''
						? $this->post_meta_seo['seo_meta_description']
						: ''
					);
					$meta_description = ( 
						( isset( $this->is_seo_meta_on ) && $this->is_seo_meta_on )
						? ( $meta_description 
							? $meta_description 
							: esc_attr( sse_get_the_excerpt( $post->post_content ) ) 
						)
						: esc_attr( sse_get_the_excerpt( $post->post_content ) )
					);
					// 「Title」「Description」「URL」の出力
					echo '<meta name="twitter:title" content="' . esc_attr( get_the_title( $post->ID ) . ' - ' . SHAPESHIFTER_SITE_NAME ) . '" />' . PHP_EOL .
					'<meta name="twitter:description" content="'. esc_attr( $meta_description ) .'" />' . PHP_EOL .
					'<meta name="twitter:url" content="' . esc_url( get_the_permalink( $post->ID ) ) . '" />' . PHP_EOL;	
					// 「Image」の出力
					$image_src = wp_get_attachment_image_src( get_post_thumbnail_id( $post->ID ), 'full' );
					$image_src = esc_url( 
						! empty( $image_src )
						? $image_src[ 0 ]
						: ( get_user_meta( get_the_author_meta( 'ID' ), 'tc_og_image', true )
							? get_user_meta( get_the_author_meta( 'ID' ), 'tc_og_image', true )
							: $this->options['seo']->get_prop( 'tc_og_image' )
						)
					);
					echo '<meta name="twitter:image" content="' . esc_url( $image_src ) . '" />';	
				}
				echo PHP_EOL;

			}

			/**
			 * Open Graph
			**/
			function open_graph() {

				echo '<!-- ' . shapeshifter()->get_theme_data( 'Name' ) . ' Open Graph -->' . PHP_EOL;
				// 「Locale」「Site Name」「Type」の出力
				echo '<meta property="og:locale" content="ja_JP" />' . PHP_EOL .
				'<meta property="og:site_name" content="' . esc_attr( SHAPESHIFTER_SITE_NAME ) . '" />' . PHP_EOL .
				'<meta property="og:type" content="article" />' . PHP_EOL;

				if( is_home() && is_front_page() ) {
					// 「Title」「Description」「URL」「Image」の出力
					echo '<meta property="og:title" content="' . esc_attr( SHAPESHIFTER_SITE_NAME ) . '" />' . PHP_EOL .
					'<meta property="og:description" content="' . esc_attr( SHAPESHIFTER_SITE_DESCRIPTION ) . '" />' . PHP_EOL .
					'<meta property="og:url" content="' . esc_url( SITE_URL ) . '" />' . PHP_EOL .
					'<meta property="og:image" content="' . esc_attr( $this->options['seo']->get_prop( 'tc_og_image' ) ) . '" />' . PHP_EOL;
				} elseif ( is_front_page() ) { 
					// 「Title」「Description」「URL」「Image」の出力
					echo '<meta property="og:title" content="' . esc_attr( SHAPESHIFTER_SITE_NAME ) . '" />' . PHP_EOL .
					'<meta property="og:description" content="' . esc_attr( SHAPESHIFTER_SITE_DESCRIPTION ) . '" />' . PHP_EOL .
					'<meta property="og:url" content="' . esc_url( SITE_URL ) . '" />' . PHP_EOL .
					'<meta property="og:image" content="' . esc_url( $this->options['seo']->get_prop( 'tc_og_image' ) ) . '" />' . PHP_EOL;
				} elseif ( is_home() || is_singular() ) { 
					if( is_home() ) {
						$post = get_post( $this->home_id );
					} else {
						global $post;
					}
					$meta_description = esc_attr( get_post_meta( $post->ID, '_ss_seo_meta_description', true ) );
					$meta_description = ( $meta_description 
						? $meta_description 
						: esc_attr( sse_get_the_excerpt( $post->post_content ) )
					);
					if( is_singular( 'post' ) ) {
						$cat = get_the_category( $post->ID );
						// 「Title」「Description」「URL」「Article Section」「Article Published Time」「Article Modified Time」「Updated Time」
						echo '<meta property="og:title" content="' . esc_attr( get_the_title( $post->ID ) ) . ' - ' . SHAPESHIFTER_SITE_NAME . '" />' . PHP_EOL .
						'<meta property="og:description" content="' . $meta_description . '" />' . PHP_EOL .
						'<meta property="og:url" content="' . esc_url( get_the_permalink( $post->ID ) ) . '" />' . PHP_EOL .
						'<meta property="article:section" content="' . esc_attr( $cat[ 0 ]->cat_name ) . '" />' . PHP_EOL .
						'<meta property="article:published_time" content="' . esc_attr( get_the_time( 'Ymd' ) ) . '" />' . PHP_EOL .
						'<meta property="article:modified_time" content="' . esc_attr( get_the_modified_time( 'Ymd' ) ) . '" />' . PHP_EOL .
						'<meta property="og:updated_time" content="' . esc_attr( get_the_modified_time( 'Ymd' ) ) . '" />' . PHP_EOL; $meta_description = $cat = null;
					}
					// 「Image」の出力
					$image_src = wp_get_attachment_image_src( get_post_thumbnail_id( $post->ID ), 'full' );
					$image_src = esc_url( $image_src 
						? $image_src[ 0 ]
						: ( get_user_meta( get_the_author_meta( 'ID' ), 'tc_og_image', true )
							? get_user_meta( get_the_author_meta( 'ID' ), 'tc_og_image', true )
							: $this->options['seo']->get_prop( 'tc_og_image' )
						)
					);
					echo '<meta property="og:image" content="' . esc_url( $image_src ) .'" />' . PHP_EOL; $image_src = null;
				}

			}

			/**
			 * Link tag for Google Plus URL
			**/
			function google_plus_url() {

				echo '<!-- ' . shapeshifter()->get_theme_data( 'Name' ) . ' Google Plus Link -->' . PHP_EOL;
				$google_plus_url = esc_url( get_user_meta( get_the_author_meta( 'ID' ), 'googleplus', true )
					? get_user_meta( get_the_author_meta( 'ID' ), 'googleplus', true )
					: $this->options['seo']->get_prop( 'google_plus_url' )
				);
				if( ! empty( $google_plus_url ) && is_string( $google_plus_url ) )
					echo '<link rel="publisher" href="' . esc_url( $google_plus_url ) . '"/>' . PHP_EOL; 
				$google_plus_url = null;

			}

		// Auto Insert
			/**
			 * in the HEAD Tag ( wp_head )
			**/
			function add_to_wp_head() {
				echo html_entity_decode( $this->options['auto_insert']->get_prop( 'header_code' ), ENT_QUOTES, get_option( 'blog_charset' ) );
			}

			/**
			 * After Starting BODY Tag
			**/
			function after_starting_body_code() {
				echo html_entity_decode( $this->options['auto_insert']->get_prop( 'after_start_body_code' ), ENT_QUOTES, get_option( 'blog_charset' ) );
			}

			/**
			 * Before Ending BODY Tag
			**/
			function add_to_wp_footer() {
				echo html_entity_decode( $this->options['auto_insert']->get_prop( 'footer_code' ), ENT_QUOTES, get_option( 'blog_charset' ) );
			}

	/**
	 * Walk Nav Menu
	**/
		function filter_walker_nav_menu_instance( $walker_nav_menu, $theme_location ) {

			return new SSE_Walker_Nav_Menu;

		}

}
}




