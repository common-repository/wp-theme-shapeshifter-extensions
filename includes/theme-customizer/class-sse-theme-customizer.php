<?php
if( ! class_exists( 'SSE_Theme_Customizer' ) ) {
/**
 * Theme Customizer
**/
class SSE_Theme_Customizer {

	#
	# Vars
	#
		/**
		 * Instance of this Class
		 * 
		 * @var $instance
		**/
		protected static $instance = null;

	#
	# Properties
	#
		/**
		 * Page Types in Array
		 * 
		 * @var $page_types
		**/
		protected $page_types = array();

		/**
		 * FontAwesome Icons in Array
		 * 
		 * @var $page_types
		**/
		protected $icons = array();

		/**
		 * Widget Areas Settings in Array
		 * 
		 * @var $page_types
		**/
		protected $widget_areas_settings = array();

		/**
		 * Theme Mods in Array
		 * 
		 * @var $page_types
		**/
		protected $theme_mods = array();

	#
	# Init
	#
		/**
		 * Public Initializer
		**/
		public static function get_instance() {

			if ( null === self::$instance ) {
				self::$instance = new Self();
			}

			return self::$instance;

		}

		/**
		 * Constructor
		**/
		protected function __construct() {

			$this->init_hooks();
			$this->init_vars();

		}

		/**
		 * Init Vars
		**/
		protected function init_vars() {

			$this->options = sse()->get_options();

			// Page Types
				$this->page_types = array( 
					'home' => array(
						'name' => esc_html__( 'Home', ShapeShifter_Extensions::TEXTDOMAIN ),
						'description' => __( 'This Section is for Default "Home"', ShapeShifter_Extensions::TEXTDOMAIN ),
						'class' => '.home.blog'
					),
					'blog' => array(
						'name' => esc_html__( 'Blog', ShapeShifter_Extensions::TEXTDOMAIN ),
						'description' => __( 'This Section is for "Blog"', ShapeShifter_Extensions::TEXTDOMAIN ),
						'class' => '.blog'
					),
					'front_page' => array(
							'name' => esc_html__( 'Front Page', ShapeShifter_Extensions::TEXTDOMAIN ),
						'description' => __( 'This Section is for "Front Page"', ShapeShifter_Extensions::TEXTDOMAIN ),
						'class' => '.home.page'
					),
					'archive' => array(
						'name' => esc_html__( 'Archive Page', ShapeShifter_Extensions::TEXTDOMAIN ),
						'description' => __( 'This Section is for "Archive Page"', ShapeShifter_Extensions::TEXTDOMAIN ),
						'class' => '.archive'
					),
					'post' => array(
						'name' => esc_html__( 'Posts', ShapeShifter_Extensions::TEXTDOMAIN ),
						'description' => __( 'This Section is for "Single-Type Page"', ShapeShifter_Extensions::TEXTDOMAIN ),
						'class' => '.single'
					),
					'page' => array(
						'name' => esc_html__( 'Pages', ShapeShifter_Extensions::TEXTDOMAIN ),
						'description' => __( 'This Section is for "Page-Type Page"', ShapeShifter_Extensions::TEXTDOMAIN ),
						'class' => '.page'
					),
				);

			// Icons
				$data_option_icons = sse()->get_option( 'icons' );
				$this->icons = $data_option_icons->get_data();
				if( isset( $this->icons['FontAwesome'] ) ) {
					foreach( $this->icons['FontAwesome'] as $index => $value ) {
						$this->icons['FontAwesome'][ $index ] = esc_attr( $value );
					}
				}
				$this->icons['FontAwesome']['none'] = esc_html__( 'No Icons', ShapeShifter_Extensions::TEXTDOMAIN );
				asort( $this->icons['FontAwesome'] );

			// Widget Areas
				$this->widget_areas_settings = sse()->get_widget_area_manager()->optional_widget_areas;

			// Animate CSS
				$this->animate_css = sse()->get_theme_mod_manager()->get_animate_css_class_array();

			// Font Families
				$this->font_families = sse()->get_theme_mod_manager()->get_shapeshifter_font_families();

			// Image Sizes
				$this->background_image_sizes = sse()->get_theme_mod_manager()->get_shapeshifter_theme_mods_choices_background_size();
			// Background Position Row
				$this->background_position_row = sse()->get_theme_mod_manager()->get_shapeshifter_theme_mods_choices_background_position_row();
			// Background Position Column
				$this->background_position_column = sse()->get_theme_mod_manager()->get_shapeshifter_theme_mods_choices_background_position_column();
			// Background Repeat
				$this->background_repeats = sse()->get_theme_mod_manager()->get_shapeshifter_theme_mods_choices_background_repeats();
			// Background Attachment
				$this->background_attachments = sse()->get_theme_mod_manager()->get_shapeshifter_theme_mods_choices_background_attachments();

			// Footer Align
				$this->footer_align = sse()->get_theme_mod_manager()->get_shapeshifter_theme_mods_choices_footer_aligns();

			// ShapeShifter_Theme_Customizer_Settings
				if ( isset( $GLOBALS['custom_theme_customizers'] ) ) {

					if ( is_array( $GLOBALS['custom_theme_customizers'] ) ) { foreach( $GLOBALS['custom_theme_customizers'] as $index => $custom_theme_customizer ) {

						foreach( $custom_theme_customizer as $type => $data ) {

							if ( $type == 'section' ) {
								$section = $data;
							} elseif ( $type == 'selectors' ) {
								$selectors = $data;
							} elseif ( $type == 'settings' ) {
								$settings = $data;
							}

						}

						$this->shapeshifter_theme_customizer_settings[] = new ShapeShifter_Theme_Customizer_Settings( $section, $selectors, $settings, SHAPESHIFTER_EXTENSIONS_THIRD_DIR . 'nora-custom-theme-customizer-settings/' );

					} }

				}

		}

		/**
		 * Init WP Hooks
		**/
		protected function init_hooks() {

			add_action( shapeshifter()->get_prefixed_action_hook( 'setup_theme_mods' ), array( $this, 'setup_theme_mods' ) );

			// Actions
				// Register Theme Customizer
				// Preview Enqueue
				// Setup Theme Customizer
					add_action( 'customize_register', array( $this, 'set_theme_customizer' ), 11 ); 
				// Preview Scripts
					add_action( 'customize_preview_init', array( $this, 'theme_customizer_live_preview' ), 11 );
				// Control Scripts
					//add_action( 'customize_controls_print_footer_scripts', array( $this, 'shapeshifter_theme_customizer_control_scripts' ) );


			// Filter
				add_filter( 'shapeshifter_filter_font_families', array( $this, 'append_font_families' ) );

		}

		public function setup_theme_mods()
		{
			// Theme Mods
				$this->theme_mods = sse()->get_theme_mods();
			}

		/**
		 * Enqueue Scripts
		**/
		public function theme_customizer_live_preview() {

			# JS Object
			wp_localize_script( 'sse-theme-customizer', 'sseThemeMods', $this->theme_mods );
			wp_localize_script( 'sse-theme-customizer', 'sseOptionalWidgetAreasData', $this->widget_areas_settings );
			wp_localize_script( 'sse-theme-customizer', 'ssePageTypes', $this->page_types );
			wp_localize_script( 'sse-theme-customizer', 'sseThemeCustomizerObject', array(
				'pluginRootURI' => SHAPESHIFTER_EXTENSIONS_DIR_URL,
				'pluginAssetsURI' => SSE_ASSETS_URL,
			) );

			wp_enqueue_script( 'sse-theme-customizer' );
			wp_enqueue_script( 'alpha-color-picker' );
			
		}

		public function append_font_families( $font_families )
		{

			if( get_option( sse()->get_prefixed_option_name( 'google_fonts_api_key' ), '' ) != '' ) {
				$applied_google_fonts_list = get_option( sse()->get_prefixed_option_name( 'applied_google_fonts_list' ) );
				if( is_array( $applied_google_fonts_list ) ) { foreach( $applied_google_fonts_list as $index => $data ) {
					$index_font_family = str_replace( array( '\\' ), '', $data['font-family-in-css'] );
					$value = esc_attr( $data['font-name-display'] );
					$font_families[ $index_font_family ] = $value;
				} }
				unset( $applied_google_fonts_list );
			}

			$custom_fonts = get_option( sse()->get_prefixed_option_name( 'custom_fonts' ), '' );
			if ( is_array( $custom_fonts ) ) { foreach( $custom_fonts as $font_family => $font_files ) {
				$font_families[ $font_family ] = $font_family;
			} }

			return $font_families;
		}

	#
	# Setup Theme Customizer
	#
		/**
		 * Setup Theme Customizer
		 * 
		 * @param obj $wp_customize
		**/
		public function set_theme_customizer( $wp_customize ) {

			$this->wp_customize =& $wp_customize;

			# Wrapper
				$this->set_wrapper_theme_mods();

			# Header
				$this->set_header_theme_mods();

			# Logo
				$this->set_header_image_theme_mods();

			# Nav Menu
				$this->set_nav_menu_theme_mods();

			# Content Area
				$this->set_content_area_theme_mods();

			# Archive
				$this->set_archive_page_theme_mods();

			# Singular
				$this->set_singular_page_theme_mods();

			# Footer
				$this->set_footer_theme_mods();

			# Standard Widget Areas
				$this->set_standard_widget_areas_theme_mods();

			# Optional Widget Areas
				$this->set_optional_widget_areas_theme_mods();

			# Others
				$this->set_others_theme_mods();

		}

			/**
			 * Wrapper
			 * 	Panel: "body_styles_panel"
			 * 		Sections: "common_styles_section"
			**/
			function set_wrapper_theme_mods() {

				# Page Settings
					foreach( $this->page_types as $page_type => $data ) {

						$page_type = esc_attr( $page_type );

						# Style Setting Section
							$this->wp_customize->add_section( 'body_' . $page_type . '_styles_section', array(
								'title' => sprintf( esc_html__( '%s Styles', ShapeShifter_Extensions::TEXTDOMAIN ), $data['name'] ),
								'description' => $data['description'],
								'panel' => 'body_styles_panel',
							));
								# Background Color
									$this->wp_customize->add_setting( 'body_' . $page_type . '_background_color', array( 
										'default' => '#FFFFFF', 
										'transport' => 'postMessage',
										'sanitize_callback' => array( 'SSE_Sanitize_Methods', 'sanitize_color_value' ),
										'sanitize_js_callback' => array( 'SSE_Sanitize_Methods', 'sanitize_color_value' ),
									));
									$this->wp_customize->add_control( new ShapeShifter_Alpha_Color_Control( $this->wp_customize, 'body_' . $page_type . '_background_color', array(
										'label' => esc_html__( 'Background Color', ShapeShifter_Extensions::TEXTDOMAIN ),
										'section' => 'body_' . $page_type . '_styles_section',
										'settings' => 'body_' . $page_type . '_background_color',
									)));

								# Background Image
									$this->wp_customize->add_setting( 'body_' . $page_type . '_background_image', array(
										'default' => '',
										'capability' => 'edit_theme_options',
										'transport' => 'postMessage',
										'sanitize_callback' => 'esc_url_raw',
										'sanitize_js_callback' => 'esc_url_raw',
									));
									$this->wp_customize->add_control( new WP_Customize_Image_Control(
										$this->wp_customize, 'body_' . $page_type . '_background_image', array(
											'label' => esc_html__( 'Background Image', ShapeShifter_Extensions::TEXTDOMAIN ),
											'section' => 'body_' . $page_type . '_styles_section',
											'settings' => 'body_' . $page_type . '_background_image',
										)
									));

								# Background Image Size
									$this->wp_customize->add_setting( 'body_' . $page_type . '_background_image_size', array(
										'default'  => 'auto',
										'capability' => 'edit_theme_options',
										'transport'=> 'postMessage',
										'sanitize_callback' => array( 'SSE_Sanitize_Methods', 'sanitize_background_image_size' ), 
										'sanitize_js_callback' => array( 'SSE_Sanitize_Methods', 'sanitize_background_image_size' ),
									));
									$this->wp_customize->add_control( 'body_' . $page_type . '_background_image_size', array(
										'section' => 'body_' . $page_type . '_styles_section',
										'settings' => 'body_' . $page_type . '_background_image_size',
										'label' => esc_html__( 'Background Image Size', ShapeShifter_Extensions::TEXTDOMAIN ),
										'type' => 'select',
										'choices' => $this->background_image_sizes,
									));

								# Background Image Position Row
									$this->wp_customize->add_setting( 'body_' . $page_type . '_background_image_position_row', array(
										'default'  => 'center',
										'capability' => 'edit_theme_options',
										'transport' => 'postMessage',
										'sanitize_callback' => array( 'SSE_Sanitize_Methods', 'sanitize_background_position_row' ), 
										'sanitize_js_callback' => array( 'SSE_Sanitize_Methods', 'sanitize_background_position_row' ),
									));
									$this->wp_customize->add_control( 'body_' . $page_type . '_background_image_position_row', array(
										'section' => 'body_' . $page_type . '_styles_section',
										'settings' => 'body_' . $page_type . '_background_image_position_row',
										'label' => esc_html__( 'Background Position Row', ShapeShifter_Extensions::TEXTDOMAIN ),
										'type' => 'select',
										'choices' => $this->background_position_row,
									));

								# Background Image Position Column
									$this->wp_customize->add_setting( 'body_' . $page_type . '_background_image_position_column', array(
										'default'  => 'center',
										'capability' => 'edit_theme_options',
										'transport' => 'postMessage',
										'sanitize_callback' => array( 'SSE_Sanitize_Methods', 'sanitize_background_position_column' ), 
										'sanitize_js_callback' => array( 'SSE_Sanitize_Methods', 'sanitize_background_position_column' ),
									));
									$this->wp_customize->add_control( 'body_' . $page_type . '_background_image_position_column', array(
										'section' => 'body_' . $page_type . '_styles_section',
										'settings' => 'body_' . $page_type . '_background_image_position_column',
										'label' => esc_html__( 'Background Position Column', ShapeShifter_Extensions::TEXTDOMAIN ),
										'type' => 'select',
										'choices' => $this->background_position_column,
									));

								# Background Image Repeat
									$this->wp_customize->add_setting( 'body_' . $page_type . '_background_image_repeat', array(
										'default'  => 'no-repeat',
										'capability' => 'edit_theme_options',
										'transport'=> 'postMessage',
										'sanitize_callback' => array( 'SSE_Sanitize_Methods', 'sanitize_background_repeat' ), 
										'sanitize_js_callback' => array( 'SSE_Sanitize_Methods', 'sanitize_background_repeat' ),
									));
									$this->wp_customize->add_control( 'body_' . $page_type . '_background_image_repeat', array(
										'section' => 'body_' . $page_type . '_styles_section',
										'settings' => 'body_' . $page_type . '_background_image_repeat',
										'label' => esc_html__( 'Background Repeat', ShapeShifter_Extensions::TEXTDOMAIN ),
										'type' => 'select',
										'choices' => $this->background_repeats,
									));

								# Background Image Attachment
									$this->wp_customize->add_setting( 'body_' . $page_type . '_background_image_attachment', array(
										'default'  => 'scroll',
										'capability' => 'edit_theme_options',
										'transport'=> 'postMessage',
										'sanitize_callback' => array( 'SSE_Sanitize_Methods', 'sanitize_background_attachment' ), 
										'sanitize_js_callback' => array( 'SSE_Sanitize_Methods', 'sanitize_background_attachment' ),
									));
									$this->wp_customize->add_control( 'body_' . $page_type . '_background_image_attachment', array(
										'section' => 'body_' . $page_type . '_styles_section',
										'settings' => 'body_' . $page_type . '_background_image_attachment',
										'label' => esc_html__( 'Background Repeat', ShapeShifter_Extensions::TEXTDOMAIN ),
										'type' => 'select',
										'choices' => $this->background_attachments,
									));

					}

			}

			/**
			 * Header
			 * 	Panel: "header_settings_panel"
			 * 		Sections: "header_color_section", "header_title_section", "header_top_nav_menu_section"
			**/
			function set_header_theme_mods() {

			}

			/**
			 * Header Image
			**/
			function set_header_image_theme_mods() {

				# Logo Panel
					$this->wp_customize->add_panel( 'header_image_settings_panel', array(
						'capability'	 => 'edit_theme_options',
						'theme_supports' => '',
						'title'		  => esc_html__( 'Header Image', ShapeShifter_Extensions::TEXTDOMAIN ),
						'description'	=> __( 'Styles of Header Image ( Some of settings are meaningless for Mobile Devices )', ShapeShifter_Extensions::TEXTDOMAIN ),
					) );

						# Image Section
							$this->wp_customize->add_section( 'header_image_display_section', array(
								'title' => esc_html__( 'Header Image', ShapeShifter_Extensions::TEXTDOMAIN ),
								'description' => __( 'Settings for Header Image', ShapeShifter_Extensions::TEXTDOMAIN ),
								'panel' => 'header_image_settings_panel'
							));

								# Header Image Select
									$this->wp_customize->add_setting( 'header_image_url', array(
										'default' => '',
										'capability' => 'edit_theme_options',
										'transport'=> 'postMessage',
										'sanitize_callback' => 'esc_url_raw',
										'sanitize_js_callback' => 'esc_url_raw'
									));
									$this->wp_customize->add_control( new WP_Customize_Image_Control(
										$this->wp_customize, 'header_image_url', array(
											'label' => esc_html__( 'Select an Image', ShapeShifter_Extensions::TEXTDOMAIN ),
											'description' => __( 'Select an Header Image. If you just want to print Text without Image, just ignore here.', ShapeShifter_Extensions::TEXTDOMAIN ),
											'section' => 'header_image_display_section',
											'settings' => 'header_image_url',
										)
									));
								
								# Size Width
									$this->wp_customize->add_setting( 'header_image_size_width', array(
										'default' => 800,
										'capability' => 'edit_theme_options',
										'transport' => 'postMessage',
										'sanitize_callback' => 'absint',
										'sanitize_js_callback' => 'absint',
									));
									$this->wp_customize->add_control( 'header_image_size_width', array(
										'section' => 'header_image_display_section',
										'settings' => 'header_image_size_width',
										'label' => esc_html__( 'Image Max Widths', ShapeShifter_Extensions::TEXTDOMAIN ),
										'type' => 'range',
										'description' => '',
										'input_attrs' => array(
											'min' => 1,
											'max' => 1500,
											'step' => 1,
											'value' => ( $this->theme_mods['header_image_size_width'] ? $this->theme_mods['header_image_size_width'] : 800 ),
											'id' => 'header_image_size_width_id',
											'style' => 'width:100%;',
										),
									));

								# Size Height
									$this->wp_customize->add_setting( 'header_image_size_height', array(
										'default' => 0,
										'capability' => 'edit_theme_options',
										'transport' => 'postMessage',
										'sanitize_callback' => 'absint',
										'sanitize_js_callback' => 'absint',
									));
									$this->wp_customize->add_control( 'header_image_size_height', array(
										'section' => 'header_image_display_section',
										'settings' => 'header_image_size_height',
										'label' => esc_html__( 'Image Height', ShapeShifter_Extensions::TEXTDOMAIN ),
										'type' => 'range',
										'input_attrs' => array(
											'min' => 0,
											'max' => 600,
											'step' => 1,
											'value' => ( $this->theme_mods['header_image_size_height'] ? $this->theme_mods['header_image_size_height'] : 0 ),
											'id' => 'header_image_size_height_id',
											'style' => 'width:100%;',
										),
									));

								# Header Image Position
									$this->wp_customize->add_setting( 'header_image_position', array(
										'default'  => 'left',
										'transport' => 'postMessage',
										'sanitize_callback' => 'sanitize_text_field',
										'sanitize_js_callback' => 'sanitize_text_field',
									));
									$this->wp_customize->add_control( 'header_image_position', array(
										'section' => 'header_image_display_section',
										'settings' => 'header_image_position',
										'label' => esc_html__( 'Header Image Position', ShapeShifter_Extensions::TEXTDOMAIN ),
										'type' => 'select',
										'choices' => array(
											'left' => esc_html__( 'Left', ShapeShifter_Extensions::TEXTDOMAIN ),
											'center' => esc_html__( 'Center', ShapeShifter_Extensions::TEXTDOMAIN ),
											'right' => esc_html__( 'Right', ShapeShifter_Extensions::TEXTDOMAIN ),
										),
									));
								
								# Margin Top
									$this->wp_customize->add_setting( 'header_image_margin_top', array(
										'default' => 0,
										'capability' => 'edit_theme_options',
										'transport' => 'postMessage',
										'sanitize_callback' => 'absint',
										'sanitize_js_callback' => 'absint',
									));
									$this->wp_customize->add_control( 'header_image_margin_top', array(
										'section' => 'header_image_display_section',
										'settings' => 'header_image_margin_top',
										'label' => esc_html__( 'Margin Top', ShapeShifter_Extensions::TEXTDOMAIN ),
										'type' => 'range',
										'description' => '',
										'input_attrs' => array(
											'min' => 0,
											'max' => 100,
											'step' => 1,
											'value' => ( $this->theme_mods['header_image_margin_top'] ? $this->theme_mods['header_image_margin_top'] : 0 ),
											'id' => 'header_image_margin_top_id',
											'style' => 'width:100%;',
										),
									));

								# Margin Side
									$this->wp_customize->add_setting( 'header_image_margin_side', array(
										'default' => 0,
										'capability' => 'edit_theme_options',
										'transport' => 'postMessage',
										'sanitize_callback' => 'absint',
										'sanitize_js_callback' => 'absint',
									));
									$this->wp_customize->add_control( 'header_image_margin_side', array(
										'section' => 'header_image_display_section',
										'settings' => 'header_image_margin_side',
										'label' => esc_html__( 'Margin Side', ShapeShifter_Extensions::TEXTDOMAIN ),
										'type' => 'range',
										'description' => '',
										'input_attrs' => array(
											'min' => 0,
											'max' => 100,
											'step' => 1,
											'value' => ( $this->theme_mods['header_image_margin_side'] ? $this->theme_mods['header_image_margin_side'] : 0 ),
											'id' => 'header_image_margin_side_id',
											'style' => 'width:100%;',
										),
									));

								# Margin Bottom
									$this->wp_customize->add_setting( 'header_image_margin_bottom', array(
										'default' => 0,
										'capability' => 'edit_theme_options',
										'transport' => 'postMessage',
										'sanitize_callback' => 'absint',
										'sanitize_js_callback' => 'absint',
									));
									$this->wp_customize->add_control( 'header_image_margin_bottom', array(
										'section' => 'header_image_display_section',
										'settings' => 'header_image_margin_bottom',
										'label' => esc_html__( 'Margin bottom', ShapeShifter_Extensions::TEXTDOMAIN ),
										'type' => 'range',
										'description' => '',
										'input_attrs' => array(
											'min' => 0,
											'max' => 100,
											'step' => 1,
											'value' => ( $this->theme_mods['header_image_margin_bottom'] ? $this->theme_mods['header_image_margin_bottom'] : 0 ),
											'id' => 'header_image_margin_bottom_id',
											'style' => 'width:100%;',
										),
									));

						# Header Background Image Section
							$this->wp_customize->add_section( 'header_image_background_image_section', array(
								'title' => esc_html__( 'Background Image', ShapeShifter_Extensions::TEXTDOMAIN ),
								'description' => __( 'Background Image', ShapeShifter_Extensions::TEXTDOMAIN ),
								'panel' => 'header_image_settings_panel'
							));

								# Background Image
									$this->wp_customize->add_setting( 'header_image_background_image', array(
										'default' => '',
										'capability' => 'edit_theme_options',
										'transport' => 'postMessage',
										'sanitize_callback' => 'esc_url_raw',
										'sanitize_js_callback' => 'esc_url_raw',
									));
									$this->wp_customize->add_control( new WP_Customize_Image_Control(
										$this->wp_customize, 'header_image_background_image', array(
											'label' => esc_html__( 'Select an Image', ShapeShifter_Extensions::TEXTDOMAIN ),
											'section' => 'header_image_background_image_section',
											'settings' => 'header_image_background_image',
										)
									));

								# Background Image Size
									$this->wp_customize->add_setting( 'header_image_background_image_size', array(
										'default' => 'auto',
										'capability' => 'edit_theme_options',
										'transport'=> 'postMessage',
										'sanitize_callback' => array( 'SSE_Sanitize_Methods', 'sanitize_background_image_size' ), 
										'sanitize_js_callback' => array( 'SSE_Sanitize_Methods', 'sanitize_background_image_size' ),
									));
									$this->wp_customize->add_control( 'header_image_background_image_size', array(
										'section' => 'header_image_background_image_section',
										'settings' => 'header_image_background_image_size',
										'label' => esc_html__( 'Background Image Size', ShapeShifter_Extensions::TEXTDOMAIN ),
										'description' => __( "Enter the value of 'background-size'. examples '100% 200px', 'auto', 'contain', 'cover'.", ShapeShifter_Extensions::TEXTDOMAIN ),
										'type' => 'select',
										'choices' => $this->background_image_sizes,
									));	

								# Background Image Position Row
									$this->wp_customize->add_setting( 'header_image_background_image_position_row', array(
										'default'  => 'center',
										'capability' => 'edit_theme_options',
										'transport' => 'postMessage',
										'sanitize_callback' => array( 'SSE_Sanitize_Methods', 'sanitize_background_position_row' ), 
										'sanitize_js_callback' => array( 'SSE_Sanitize_Methods', 'sanitize_background_position_row' ),
									));
									$this->wp_customize->add_control( 'header_image_background_image_position_row', array(
										'section' => 'header_image_background_image_section',
										'settings' => 'header_image_background_image_position_row',
										'label' => esc_html__( 'Background Position Row', ShapeShifter_Extensions::TEXTDOMAIN ),
										'type' => 'select',
										'choices' => $this->background_position_row,
									));

								# Background Image Position Column
									$this->wp_customize->add_setting( 'header_image_background_image_position_column', array(
										'default'  => 'center',
										'capability' => 'edit_theme_options',
										'transport' => 'postMessage',
										'sanitize_callback' => array( 'SSE_Sanitize_Methods', 'sanitize_background_position_column' ), 
										'sanitize_js_callback' => array( 'SSE_Sanitize_Methods', 'sanitize_background_position_column' ),
									));
									$this->wp_customize->add_control( 'header_image_background_image_position_column', array(
										'section' => 'header_image_background_image_section',
										'settings' => 'header_image_background_image_position_column',
										'label' => esc_html__( 'Background Position Column', ShapeShifter_Extensions::TEXTDOMAIN ),
										'type' => 'select',
										'choices' => $this->background_position_column,
									));

								# Background Image Repeat
									$this->wp_customize->add_setting( 'header_image_background_image_repeat', array(
										'default'  => 'no-repeat',
										'capability' => 'edit_theme_options',
										'transport'=> 'postMessage',
										'sanitize_callback' => array( 'SSE_Sanitize_Methods', 'sanitize_background_repeat' ), 
										'sanitize_js_callback' => array( 'SSE_Sanitize_Methods', 'sanitize_background_repeat' ),
									));
									$this->wp_customize->add_control( 'header_image_background_image_repeat', array(
										'section' => 'header_image_background_image_section',
										'settings' => 'header_image_background_image_repeat',
										'label' => esc_html__( 'Background Repeat', ShapeShifter_Extensions::TEXTDOMAIN ),
										'type' => 'select',
										'choices' => $this->background_repeats,
									));

								# Background Image Attachment
									$this->wp_customize->add_setting( 'header_image_background_image_attachment', array(
										'default'  => 'scroll',
										'capability' => 'edit_theme_options',
										'transport'=> 'postMessage',
										'sanitize_callback' => array( 'SSE_Sanitize_Methods', 'sanitize_background_attachment' ), 
										'sanitize_js_callback' => array( 'SSE_Sanitize_Methods', 'sanitize_background_attachment' ),
									));
									$this->wp_customize->add_control( 'header_image_background_image_attachment', array(
										'section' => 'header_image_background_image_section',
										'settings' => 'header_image_background_image_attachment',
										'label' => esc_html__( 'Background Attachment', ShapeShifter_Extensions::TEXTDOMAIN ),
										'type' => 'select',
										'choices' => $this->background_attachments,
									));

						# Title Description Section
							$this->wp_customize->add_section( 'header_image_title_description_section', array(
								'title' => esc_html__( 'Title and Description', ShapeShifter_Extensions::TEXTDOMAIN ),
								'description' => __( 'Styles for Title and Description of Website. If Logo Image displays Title or/and Descriptioin, Recommended NOT to display them', ShapeShifter_Extensions::TEXTDOMAIN ),
								'panel' => 'header_image_settings_panel'
							));
							
								# Title Display Toggle
									$this->wp_customize->add_setting( 'header_image_title_display_toggle', array(
										'default'  => false,
										'transport' => 'postMessage',
										'sanitize_callback' => array( 'SSE_Sanitize_Methods', 'sanitize_checkbox' ),
										'sanitize_js_callback' => array( 'SSE_Sanitize_Methods', 'sanitize_checkbox' ),
									));
									$this->wp_customize->add_control( 'header_image_title_display_toggle', array(
										'section' => 'header_image_title_description_section',
										'settings' => 'header_image_title_display_toggle',
										'label' => esc_html__( 'Title Display', ShapeShifter_Extensions::TEXTDOMAIN ),
										'type' => 'checkbox',
									));

								# Description Display Toggle
									$this->wp_customize->add_setting( 'header_image_description_display_toggle', array(
										'default'  => false,
										'transport' => 'postMessage',
										'sanitize_callback' => array( 'SSE_Sanitize_Methods', 'sanitize_checkbox' ),
										'sanitize_js_callback' => array( 'SSE_Sanitize_Methods', 'sanitize_checkbox' ),
									));
									$this->wp_customize->add_control( 'header_image_description_display_toggle', array(
										'section' => 'header_image_title_description_section',
										'settings' => 'header_image_description_display_toggle',
										'label' => esc_html__( 'Description Display', ShapeShifter_Extensions::TEXTDOMAIN ),
										'type' => 'checkbox',
									));

								# Title Description Position
									$this->wp_customize->add_setting( 'header_image_title_description_position', array(
										'default'  => 'left-top',
										'transport' => 'postMessage',
										'sanitize_callback' => 'sanitize_text_field',
										'sanitize_js_callback' => 'sanitize_text_field',
									));
									$this->wp_customize->add_control( 'header_image_title_description_position', array(
										'section' => 'header_image_title_description_section',
										'settings' => 'header_image_title_description_position',
										'label' => esc_html__( 'Position to Display', ShapeShifter_Extensions::TEXTDOMAIN ),
										'type' => 'select',
										'choices' => array(
											'left-top' => esc_html__( 'Left Top', ShapeShifter_Extensions::TEXTDOMAIN ),
											'left-bottom' => esc_html__( 'Left Bottom', ShapeShifter_Extensions::TEXTDOMAIN ),
											'right-top' => esc_html__( 'Right Top', ShapeShifter_Extensions::TEXTDOMAIN ),
											'right-bottom' => esc_html__( 'Right Bottom', ShapeShifter_Extensions::TEXTDOMAIN ),
										),
									));

								# Title Font Size
									$this->wp_customize->add_setting( 'header_image_title_font_size', array(
										'default' => 30,
										'capability' => 'edit_theme_options',
										'transport' => 'postMessage',
										'sanitize_callback' => 'absint',
										'sanitize_js_callback' => 'absint',
									));
									$this->wp_customize->add_control( 'header_image_title_font_size', array(
										'section' => 'header_image_title_description_section',
										'settings' => 'header_image_title_font_size',
										'label' => esc_html__( 'Font Size of Title', ShapeShifter_Extensions::TEXTDOMAIN ),
										'type' => 'range',
										'description' => '',
										'input_attrs' => array(
											'min' => 8,
											'max' => 50,
											'step' => 1,
											'value' => ( $this->theme_mods['header_image_title_font_size'] ? $this->theme_mods['header_image_title_font_size'] : 30 ),
											'id' => 'header_image_title_font_size_id',
											'style' => 'width:100%;',
										),
									));

								# Description Font Size
									$this->wp_customize->add_setting( 'header_image_description_font_size', array(
										'default' => 14,
										'capability' => 'edit_theme_options',
										'transport' => 'postMessage',
										'sanitize_callback' => 'absint',
										'sanitize_js_callback' => 'absint',
									));
									$this->wp_customize->add_control( 'header_image_description_font_size', array(
										'section' => 'header_image_title_description_section',
										'settings' => 'header_image_description_font_size',
										'label' => esc_html__( 'Font Size of Description', ShapeShifter_Extensions::TEXTDOMAIN ),
										'type' => 'range',
										'description' => '',
										'input_attrs' => array(
											'min' => 8,
											'max' => 50,
											'step' => 1,
											'value' => intval( $this->theme_mods['header_image_description_font_size'] ? $this->theme_mods['header_image_description_font_size'] : 14 ),
											'id' => 'header_image_description_font_size_id',
											'style' => 'width:100%;',
										),
									));

								# Title Font Family
									$this->wp_customize->add_setting( 'header_image_title_font_family', array(
										'default'  => 'none',
										'transport' => 'postMessage',
										'sanitize_callback' => array( 'SSE_Sanitize_Methods', 'sanitize_font_families' ),
										'sanitize_js_callback' => array( 'SSE_Sanitize_Methods', 'sanitize_font_families' ),
									));
									$this->wp_customize->add_control( 'header_image_title_font_family', array(
										'section' => 'header_image_title_description_section',
										'settings' => 'header_image_title_font_family',
										'label' => esc_html__( 'Font Family of Title', ShapeShifter_Extensions::TEXTDOMAIN ),
										'type' => 'select',
										'choices' => $this->font_families,
									));

								# Description Font Family
									$this->wp_customize->add_setting( 'header_image_description_font_family', array(
										'default'  => 'none',
										'transport' => 'postMessage',
										'sanitize_callback' => array( 'SSE_Sanitize_Methods', 'sanitize_font_families' ),
										'sanitize_js_callback' => array( 'SSE_Sanitize_Methods', 'sanitize_font_families' ),
									));
									$this->wp_customize->add_control( 'header_image_description_font_family', array(
										'section' => 'header_image_title_description_section',
										'settings' => 'header_image_description_font_family',
										'label' => esc_html__( 'Font Family of Description', ShapeShifter_Extensions::TEXTDOMAIN ),
										'type' => 'select',
										'choices' => $this->font_families,
									));

								# Title Description margin
									$this->wp_customize->add_setting( 'header_image_title_description_padding', array(
										'default' => 10,
										'capability' => 'edit_theme_options',
										'transport' => 'postMessage',
										'sanitize_callback' => 'absint',
									));
									$this->wp_customize->add_control( 'header_image_title_description_padding', array(
										'section' => 'header_image_title_description_section',
										'settings' => 'header_image_title_description_padding',
										'label' => esc_html__( 'Padding', ShapeShifter_Extensions::TEXTDOMAIN ),
										'type' => 'range',
										'description' => '',
										'input_attrs' => array(
											'min' => 0,
											'max' => 100,
											'step' => 1,
											'value' => ( $this->theme_mods['header_image_title_description_padding'] ? $this->theme_mods['header_image_title_description_padding'] : 10 ),
											'id' => 'header_image_title_description_padding_id',
											'style' => 'width:100%;',
										),
									));
							
						# Header Image Colors Section
							$this->wp_customize->add_section( 'header_image_color_section', array(
								'title' => esc_html__( 'Colors', ShapeShifter_Extensions::TEXTDOMAIN ),
								'description' => __( 'This is the section you can set the value for color settings related to Header Image', ShapeShifter_Extensions::TEXTDOMAIN ),
								'panel' => 'header_image_settings_panel'
							));
							
								# Background Color
									$this->wp_customize->add_setting( 'header_image_background_color', array( 
										'default' => 'rgba(255,255,255,0)',
										'transport' => 'postMessage',
										'sanitize_callback' => array( 'SSE_Sanitize_Methods', 'sanitize_color_value' ),
										'sanitize_js_callback' => array( 'SSE_Sanitize_Methods', 'sanitize_color_value' ),
									));
									$this->wp_customize->add_control( new ShapeShifter_Alpha_Color_Control( $this->wp_customize, 'header_image_background_color', array(
										'label' => esc_html__( 'Header Image Background', ShapeShifter_Extensions::TEXTDOMAIN ),
										'section' => 'header_image_color_section',
										'settings' => 'header_image_background_color',
									)));

								# Title Color
									$this->wp_customize->add_setting( 'header_image_title_color', array( 
										'default' => '#666666',
										'transport' => 'postMessage',
										'sanitize_callback' => array( 'SSE_Sanitize_Methods', 'sanitize_color_value' ),
										'sanitize_js_callback' => array( 'SSE_Sanitize_Methods', 'sanitize_color_value' ),
									));
									$this->wp_customize->add_control( new ShapeShifter_Alpha_Color_Control( $this->wp_customize, 'header_image_title_color', array(
										'label' => esc_html__( 'Title', ShapeShifter_Extensions::TEXTDOMAIN ),
										'section' => 'header_image_color_section',
										'settings' => 'header_image_title_color',
									)));

								# Description Color
									$this->wp_customize->add_setting( 'header_image_description_color', array( 
										'default' => '#666666',
										'transport' => 'postMessage',
										'sanitize_callback' => array( 'SSE_Sanitize_Methods', 'sanitize_color_value' ),
										'sanitize_js_callback' => array( 'SSE_Sanitize_Methods', 'sanitize_color_value' ),
									));
									$this->wp_customize->add_control( new ShapeShifter_Alpha_Color_Control( $this->wp_customize, 'header_image_description_color', array(
										'label' => esc_html__( 'Description', ShapeShifter_Extensions::TEXTDOMAIN ),
										'section' => 'header_image_color_section',
										'settings' => 'header_image_description_color',
									)));

			}

			/**
			 * Nav Menu
			 * 	Panel: "nav_menu_settings_panel"
			 * 		Sections: "nav_menu_display_section", "nav_menu_color_section", "nav_menu_background_image_section", "nav_mobile_nav_menu_section"
			**/
			function set_nav_menu_theme_mods() {

				# Display
					$this->set_nav_menu_display_theme_mods();

				# Colors
					$this->set_nav_menu_colors_theme_mods();

				# Background Image
					$this->set_nav_menu_background_image_theme_mods();

				# Mobile
					$this->set_nav_menu_mobile_theme_mods();

			}

				/**
				 * Display
				**/
				function set_nav_menu_display_theme_mods() {

				}

				/**
				 * Colors
				**/
				function set_nav_menu_colors_theme_mods() {

				}

				/**
				 * Background Image
				**/
				function set_nav_menu_background_image_theme_mods() {

				}

				/**
				 * Mobile
				**/
				function set_nav_menu_mobile_theme_mods() {

				}

			/**
			 * Content Area
			 *	Panel: "content_area_settings_panel"
			 *		Sections: "content_area_design_section", "content_area_color_section", "content_area_background_image_section", "main_content_background_image_section"
			**/
			function set_content_area_theme_mods() {


			}

			/**
			 * Archive Page
			 * 	Panel: "archive_page_settings_panel"
			 * 		Sections: "archive_page_design_section", "archive_page_animations_section", "archive_page_color_section", "archive_page_post_thumbnail_section", "archive_page_" . $headline . "_section", ""
			**/
			function set_archive_page_theme_mods() {

				# Each Text Section
					$headlines = array( 
						'post_list_title' => __( 'Post List Item Title', ShapeShifter_Extensions::TEXTDOMAIN ),
					);

					foreach( $headlines as $headline => $title ) {

						# FontAwesome Icon
							$this->wp_customize->add_setting( 'archive_page_' . $headline . '_fontawesome_icon_select', array(
								'default'  => sanitize_text_field( $this->theme_mods['archive_page_' . $headline . '_fontawesome_icon_select'] ),
								'transport' => 'postMessage',
								'sanitize_callback' => 'sanitize_text_field',
								'sanitize_js_callback' => 'sanitize_text_field',
							));
							$this->wp_customize->add_control( 'archive_page_' . $headline . '_fontawesome_icon_select', array(
								'section' => 'archive_page_' . $headline . '_section',
								'settings' => 'archive_page_' . $headline . '_fontawesome_icon_select',
								'label' => esc_html__( 'Font Awesome Icon', ShapeShifter_Extensions::TEXTDOMAIN ),
								'description' => __( 'FYR, check the reference page "<a rel="nofollow" target="_blank" href="http://fortawesome.github.io/Font-Awesome/icons/">Fontawesome Icons</a>". * This settings choices don\'t include alias.', ShapeShifter_Extensions::TEXTDOMAIN ),
								'type' => 'select',
								'choices' => $this->icons['FontAwesome'],
							));

						# Icon Color
							$this->wp_customize->add_setting( 'archive_page_' . $headline . '_fontawesome_icon_color', array( 
								'default' => sanitize_text_field( $this->theme_mods['archive_page_' . $headline . '_fontawesome_icon_color'] ),
								'transport' => 'postMessage',
								'sanitize_callback' => array( 'SSE_Sanitize_Methods', 'sanitize_color_value' ),
								'sanitize_js_callback' => array( 'SSE_Sanitize_Methods', 'sanitize_color_value' ),
							));
							$this->wp_customize->add_control( new ShapeShifter_Alpha_Color_Control( $this->wp_customize, 'archive_page_' . $headline . '_fontawesome_icon_color', array(
								'label' => esc_html__( 'Icon Color', ShapeShifter_Extensions::TEXTDOMAIN ),
								'section' => 'archive_page_' . $headline . '_section',
								'settings' => 'archive_page_' . $headline . '_fontawesome_icon_color',
							)));

					}

				# SNS Share Buttons
					$this->wp_customize->add_section( 'archive_page_display_section', array(
						'title' => esc_html__( 'SNS Share Buttons', ShapeShifter_Extensions::TEXTDOMAIN ),//'表示設定',
						'panel' => 'archive_page_settings_panel',
					));

					// SNS Icons
						$this->wp_customize->add_setting( 'archive_page_read_later_text', array(
							'default'  => '',
							'transport' => 'postMessage',
							'sanitize_callback' => 'sanitize_text_field',
							'sanitize_js_callback' => 'sanitize_text_field',
						));
						$this->wp_customize->add_control( 'archive_page_read_later_text', array(
							'section' => 'archive_page_display_section',
							'settings' => 'archive_page_read_later_text',
							'label' => esc_html__( 'Text before Share Buttons/Icons', ShapeShifter_Extensions::TEXTDOMAIN ),//'投稿一覧のシェア用テキスト',
							'type' => 'text',
						));

						$this->wp_customize->add_setting( 'archive_page_read_later_type', array(
							'default'  => 'none',
							//'transport' => 'postMessage',
							'sanitize_callback' => 'sanitize_text_field',
							'sanitize_js_callback' => 'sanitize_text_field',
						));
						$this->wp_customize->add_control( 'archive_page_read_later_type', array(
							'section' => 'archive_page_display_section',
							'settings' => 'archive_page_read_later_type',
							'label' => esc_html__( 'SNS Share type for Post List Item', ShapeShifter_Extensions::TEXTDOMAIN ),//'投稿一覧のシェアタイプ',
							'description' => esc_html__( "'Official Buttons' aren't recommended for AJAX Load for Archive Page", ShapeShifter_Extensions::TEXTDOMAIN ),//'アーカイブページのAJAXロード機能をONにしている場合、「公式ボタン」は非推奨です。また、変更後一度更新しないと正しく表示されません。',
							'type' => 'select',
							'choices' => array(
								'none' => esc_html__( 'Select the Type', ShapeShifter_Extensions::TEXTDOMAIN ),//'アイコン'
								'icons' => esc_html__( 'General Icons', ShapeShifter_Extensions::TEXTDOMAIN ),//'アイコン'
								'buttons' => esc_html__( 'Official Buttons', ShapeShifter_Extensions::TEXTDOMAIN ),//'公式ボタン',
							),
						));

						$this->wp_customize->add_setting( 'archive_page_read_later_twitter', array(
							'default'  => false,
							'transport' => 'postMessage',
							'sanitize_callback' => array( 'SSE_Sanitize_Methods', 'sanitize_checkbox' ),
							'sanitize_js_callback' => array( 'SSE_Sanitize_Methods', 'sanitize_checkbox' ),
						));
						$this->wp_customize->add_control( 'archive_page_read_later_twitter', array(
							'section' => 'archive_page_display_section',
							'settings' => 'archive_page_read_later_twitter',
							'label' => esc_html__( 'Twitter Share Button', ShapeShifter_Extensions::TEXTDOMAIN ),//'Twitterのシェアボタン',
							'type' => 'checkbox',
						));

						$this->wp_customize->add_setting( 'archive_page_read_later_facebook', array(
							'default'  => false,
							'transport' => 'postMessage',
							'sanitize_callback' => array( 'SSE_Sanitize_Methods', 'sanitize_checkbox' ),
							'sanitize_js_callback' => array( 'SSE_Sanitize_Methods', 'sanitize_checkbox' ),
						));
						$this->wp_customize->add_control( 'archive_page_read_later_facebook', array(
							'section' => 'archive_page_display_section',
							'settings' => 'archive_page_read_later_facebook',
							'label' => esc_html__( "Facebook Share Button ( Require Javascript SDK in header to display 'Official Buttons' )", ShapeShifter_Extensions::TEXTDOMAIN ),//'Facebookのシェアボタン（使用するにはヘッダーにFacebookの「JavaScript SDK」を出力する必要があります。）',
							'type' => 'checkbox',
						));

						$this->wp_customize->add_setting( 'archive_page_read_later_googleplus', array(
							'default'  => false,
							'transport' => 'postMessage',
							'sanitize_callback' => array( 'SSE_Sanitize_Methods', 'sanitize_checkbox' ),
							'sanitize_js_callback' => array( 'SSE_Sanitize_Methods', 'sanitize_checkbox' ),
						));
						$this->wp_customize->add_control( 'archive_page_read_later_googleplus', array(
							'section' => 'archive_page_display_section',
							'settings' => 'archive_page_read_later_googleplus',
							'label' => esc_html__( 'Google+ Share Button', ShapeShifter_Extensions::TEXTDOMAIN ),//'Google+のシェアボタン',
							'type' => 'checkbox',
						));

						$this->wp_customize->add_setting( 'archive_page_read_later_hatena', array(
							'default'  => false,
							'transport' => 'postMessage',
							'sanitize_callback' => array( 'SSE_Sanitize_Methods', 'sanitize_checkbox' ),
							'sanitize_js_callback' => array( 'SSE_Sanitize_Methods', 'sanitize_checkbox' ),
						));
						$this->wp_customize->add_control( 'archive_page_read_later_hatena', array(
							'section' => 'archive_page_display_section',
							'settings' => 'archive_page_read_later_hatena',
							'label' => esc_html__( 'Hatena Bookmark Share Button', ShapeShifter_Extensions::TEXTDOMAIN ),//'はてなブックマークのシェアボタン',
							'type' => 'checkbox',
						));

						$this->wp_customize->add_setting( 'archive_page_read_later_pocket', array(
							'default'  => false,
							'transport' => 'postMessage',
							'sanitize_callback' => array( 'SSE_Sanitize_Methods', 'sanitize_checkbox' ),
							'sanitize_js_callback' => array( 'SSE_Sanitize_Methods', 'sanitize_checkbox' ),
						));
						$this->wp_customize->add_control( 'archive_page_read_later_pocket', array(
							'section' => 'archive_page_display_section',
							'settings' => 'archive_page_read_later_pocket',
							'label' => esc_html__( 'Pocket Share Button', ShapeShifter_Extensions::TEXTDOMAIN ),//'Pocketのシェアボタン',
							'type' => 'checkbox',
						));

						$this->wp_customize->add_setting( 'archive_page_read_later_line', array(
							'default'  => false,
							'transport' => 'postMessage',
							'sanitize_callback' => array( 'SSE_Sanitize_Methods', 'sanitize_checkbox' ),
							'sanitize_js_callback' => array( 'SSE_Sanitize_Methods', 'sanitize_checkbox' ),
						));
						$this->wp_customize->add_control( 'archive_page_read_later_line', array(
							'section' => 'archive_page_display_section',
							'settings' => 'archive_page_read_later_line',
							'label' => esc_html__( 'LINE Share Button', ShapeShifter_Extensions::TEXTDOMAIN ),//'LINEのシェアボタン',
							'type' => 'checkbox',
						));

			}

			/**
			 * Singular
			 * 	Panel: "singular_page_settings_panel"
			 * 		Sections: "singular_page_display_section", "singular_page_animations_section", "singular_page_color_section", "singular_page_" . $headline . "_section"
			**/
			function set_singular_page_theme_mods() {

				$headlines = array( 
					'h1' => esc_html__( 'Title', ShapeShifter_Extensions::TEXTDOMAIN ),
					'h2' => esc_html__( 'H2 Tags', ShapeShifter_Extensions::TEXTDOMAIN ),
					'h3' => esc_html__( 'H3 Tags', ShapeShifter_Extensions::TEXTDOMAIN ),
					'h4' => esc_html__( 'H4 Tags', ShapeShifter_Extensions::TEXTDOMAIN ),
					'h5' => esc_html__( 'H5 Tags', ShapeShifter_Extensions::TEXTDOMAIN ),
					'h6' => esc_html__( 'H6 Tags', ShapeShifter_Extensions::TEXTDOMAIN ),
					'p' => esc_html__( 'P Tags', ShapeShifter_Extensions::TEXTDOMAIN ),
				);
				foreach( $headlines as $headline => $title ) {

					# FontAwesome Icon
						if ( $headline !== 'p' ) {

							# Icon Select
								$this->wp_customize->add_setting( 'singular_page_' . $headline . '_fontawesome_icon_select', array(
									'default'  => sanitize_text_field( $this->theme_mods['singular_page_' . $headline . '_fontawesome_icon_select'] ),
									'transport' => 'postMessage',
									'sanitize_callback' => 'sanitize_text_field',
									'sanitize_js_callback' => 'sanitize_text_field',
								));
								$this->wp_customize->add_control( 'singular_page_' . $headline . '_fontawesome_icon_select', array(
									'section' => 'singular_page_' . $headline . '_section',
									'settings' => 'singular_page_' . $headline . '_fontawesome_icon_select',
									'label' => esc_html__( 'Font Awesome Icon', ShapeShifter_Extensions::TEXTDOMAIN ),
									'description' => __( 'FYR, check the reference page "<a rel="nofollow" target="_blank" href="http://fortawesome.github.io/Font-Awesome/icons/">Fontawesome Icons</a>". * This settings choices don\'t include alias.', ShapeShifter_Extensions::TEXTDOMAIN ),
									'type' => 'select',
									'choices' => $this->icons['FontAwesome'],
								));

							# Icon Color
								$this->wp_customize->add_setting( 'singular_page_' . $headline . '_fontawesome_icon_color', array( 
									'default' => sanitize_text_field( $this->theme_mods['singular_page_' . $headline . '_fontawesome_icon_color'] ),
									'transport' => 'postMessage',
									'sanitize_callback' => array( 'SSE_Sanitize_Methods', 'sanitize_color_value' ),
									'sanitize_js_callback' => array( 'SSE_Sanitize_Methods', 'sanitize_color_value' ),
								));
								$this->wp_customize->add_control( new ShapeShifter_Alpha_Color_Control( $this->wp_customize, 'singular_page_' . $headline . '_fontawesome_icon_color', array(
									'label' => esc_html__( 'Icon Color', ShapeShifter_Extensions::TEXTDOMAIN ),
									'section' => 'singular_page_' . $headline . '_section',
									'settings' => 'singular_page_' . $headline . '_fontawesome_icon_color',
								)));

						}

				}

			}

			/**
			 * Footer
			 * 	Panel: "footer_settings_panel"
			 * 		Sections: "footer_text_section", "footer_background_image", "footer_color_section", ""
			**/
			function set_footer_theme_mods() {

			}

			/**
			 * Standard Widget Areas
			 * 	Panel: "default_widget_area_settings_panel"
			 * 		Sections: "widget_area_' . $data['id']", "top_right_fixed_area", "footer_color_section", ""
			**/
			function set_standard_widget_areas_theme_mods() {

				# Widget Area Settings
					$default_widget_areas_args = array(
						'slidebar_left' => array(
							'id' => 'slidebar_left',
							'name' => esc_html__( 'Slidebar Left', ShapeShifter_Extensions::TEXTDOMAIN ),
						),
						'sidebar_left' => array(
							'id' => 'sidebar_left',
							'name' => esc_html__( 'Sidebar Left', ShapeShifter_Extensions::TEXTDOMAIN ),
						),
						'sidebar_left_fixed' => array(
							'id' => 'sidebar_left_fixed',
							'name' => esc_html__( 'Sidebar Left Fixed', ShapeShifter_Extensions::TEXTDOMAIN ),
						),
						'slidebar_right' => array(
							'id' => 'slidebar_right',
							'name' => esc_html__( 'Slidebar Right', ShapeShifter_Extensions::TEXTDOMAIN ),
						),
						'sidebar_right' => array(
							'id' => 'sidebar_right',
							'name' => esc_html__( 'Sidebar Right', ShapeShifter_Extensions::TEXTDOMAIN ),
						),
						'sidebar_right_fixed' => array(
							'id' => 'sidebar_right_fixed',
							'name' => esc_html__( 'Sidebar Right Fixed', ShapeShifter_Extensions::TEXTDOMAIN ),
						),
						'mobile_sidebar' => array(
							'id' => 'mobile_sidebar',
							'name' => esc_html__( 'Mobile Sidebar', ShapeShifter_Extensions::TEXTDOMAIN ),
						)
					);

					foreach( $default_widget_areas_args as $name => $data ) { 

						# Default Widget Area
							if ( is_active_sidebar( $data['id'] ) ) {

								# Title FontAwesome Icons
									# Select
										$this->wp_customize->add_setting( $data['id'] . '_widget_title_fontawesome_icon_select', array(
											'default'  => 'f150',
											'transport' => 'postMessage',
											'sanitize_callback' => 'sanitize_text_field',
											'sanitize_js_callback' => 'sanitize_text_field',
										));
										$this->wp_customize->add_control( $data['id'] . '_widget_title_fontawesome_icon_select', array(
											'section' => 'widget_area_' . $data['id'],
											'settings' => $data['id'] . '_widget_title_fontawesome_icon_select',
											'label' => esc_html__( 'Font Awesome Icon for Widget Title', ShapeShifter_Extensions::TEXTDOMAIN ),
											'description' => __( 'FYR, check the reference page "<a rel="nofollow" target="_blank" href="http://fortawesome.github.io/Font-Awesome/icons/">Fontawesome Icons</a>". * This settings choices don\'t include alias.', ShapeShifter_Extensions::TEXTDOMAIN ),
											'type' => 'select',
											'choices' => $this->icons['FontAwesome'],
										));
							
									# Color
										$this->wp_customize->add_setting( $data['id'] . '_widget_title_fontawesome_icon_color', array( 
											'default' => '#000000',
											'transport' => 'postMessage',
											'sanitize_callback' => array( 'SSE_Sanitize_Methods', 'sanitize_color_value' ),
											'sanitize_js_callback' => array( 'SSE_Sanitize_Methods', 'sanitize_color_value' ),
										));
										$this->wp_customize->add_control( new ShapeShifter_Alpha_Color_Control( $this->wp_customize, $data['id'] . '_widget_title_fontawesome_icon_color', array(
											'label' => esc_html__( 'Icon Color', ShapeShifter_Extensions::TEXTDOMAIN ),
											'section' => 'widget_area_' . $data['id'],
											'settings' => $data['id'] . '_widget_title_fontawesome_icon_color',
										)));

								# List FontAwesome Icons
									# Select
										$this->wp_customize->add_setting( $data['id'] . '_widget_list_fontawesome_icon_select', array(
											'default'  => 'f101',
											'transport' => 'postMessage',
											'sanitize_callback' => 'sanitize_text_field',
											'sanitize_js_callback' => 'sanitize_text_field',
										));
										$this->wp_customize->add_control( $data['id'] . '_widget_list_fontawesome_icon_select', array(
											'section' => 'widget_area_' . $data['id'],
											'settings' => $data['id'] . '_widget_list_fontawesome_icon_select',
											'label' => esc_html__( 'Font Awesome Icon for List of Widget Item ( Like "Category" )', ShapeShifter_Extensions::TEXTDOMAIN ),
											'type' => 'select',
											'choices' => $this->icons['FontAwesome']
										));

									# Color
										$this->wp_customize->add_setting( $data['id'] . '_widget_list_fontawesome_icon_color', array( 
											'default' => '#000000',
											'transport' => 'postMessage',
											'sanitize_callback' => array( 'SSE_Sanitize_Methods', 'sanitize_color_value' ),
											'sanitize_js_callback' => array( 'SSE_Sanitize_Methods', 'sanitize_color_value' ),
										));
										$this->wp_customize->add_control( new ShapeShifter_Alpha_Color_Control( $this->wp_customize, $data['id'] . '_widget_list_fontawesome_icon_color', array(
											'label' => esc_html__( 'Icon Color for List of Widget Item ( Like "Category" )', ShapeShifter_Extensions::TEXTDOMAIN ),
											'section' => 'widget_area_' . $data['id'],
											'settings' => $data['id'] . '_widget_list_fontawesome_icon_color',
										)));

							}

					}

				# Top Right
					$this->set_standard_widget_areas_top_right_theme_mods();

			}

				/**
				 * Top Right
				**/
				function set_standard_widget_areas_top_right_theme_mods() {

					# Top Right Fixed Section
						$this->wp_customize->add_section( 'top_right_fixed_area', array(
							'title' => esc_html__( 'Top Right Fixed', ShapeShifter_Extensions::TEXTDOMAIN ),
							'panel' => 'default_widget_area_settings_panel',
						));
						
							# Is Fixed ?
								$this->wp_customize->add_setting( 'is_widget_area_top_right_fixed', array(
									'default'  => true,
									'transport' => 'postMessage',
									'sanitize_callback' => array( 'SSE_Sanitize_Methods', 'sanitize_checkbox' ),
									'sanitize_js_callback' => array( 'SSE_Sanitize_Methods', 'sanitize_checkbox' ),
								));
								$this->wp_customize->add_control( 'is_widget_area_top_right_fixed', array(
									'section' => 'top_right_fixed_area',
									'settings' => 'is_widget_area_top_right_fixed',
									'label' => esc_html__( 'Fixed Switch', ShapeShifter_Extensions::TEXTDOMAIN ),
									'type' => 'checkbox'
								));

							# Position
								# Top
									$this->wp_customize->add_setting( 'top_right_fixed_area_top', array(
										'default' => 10,
										'type' => 'theme_mod',
										'capability' => 'edit_theme_options',
										'transport' => 'postMessage',
										'sanitize_callback' => 'absint',
										'sanitize_js_callback' => 'absint'
									) );
									$this->wp_customize->add_control( 'top_right_fixed_area_top', array(
										'section' => 'top_right_fixed_area',
										'settings' => 'top_right_fixed_area_top',
										'label' => esc_html__( 'Distance from Top', ShapeShifter_Extensions::TEXTDOMAIN ),
										'type' => 'range',
										'description' => '',
										'input_attrs' => array(
											'min' => 0,
											'max' => 300,
											'step' => 1,
											'value' => absint( $this->theme_mods['top_right_fixed_area_top'] ),
											'id' => 'top_right_fixed_area_top_id',
											'style' => 'width: 100%;'
										)
									) );

								# Side
									$this->wp_customize->add_setting( 'top_right_fixed_area_side', array(
										'default' => absint( $this->theme_mods['top_right_fixed_area_side'] ),
										'transport' => 'postMessage',
										'type' => 'theme_mod',
										'capability' => 'edit_theme_options',
										'sanitize_callback' => 'absint',
										'sanitize_js_callback' => 'absint'
									));
									$this->wp_customize->add_control( 'top_right_fixed_area_side', array(
										'section' => 'top_right_fixed_area',
										'settings' => 'top_right_fixed_area_side',
										'label' => esc_html__( 'Distance from Side', ShapeShifter_Extensions::TEXTDOMAIN ),
										'type' => 'range',
										'description' => '',
										'input_attrs' => array(
											'min' => 0,
											'max' => 200,
											'step' => 1,
											'value' => absint( $this->theme_mods['top_right_fixed_area_side'] ),
											'id' => 'top_right_fixed_area_side_id',
											'style' => 'width:100%;'
										)
									));

				}


			/**
			 * Optional Widget Areas
			 * 	Panel: "optional_widget_area_settings_panel"
			 * 		Sections: "widget_area_" . $data['id'], "widget_area_" . $id_fix
			**/
			function set_optional_widget_areas_theme_mods() {

				# Optional
					$this->wp_customize->add_panel( 'optional_widget_area_settings_panel', array(
						'capability'	 => 'edit_theme_options',
						'theme_supports' => '',
						'title'		     => esc_html__( 'Optional Widget Areas', ShapeShifter_Extensions::TEXTDOMAIN ),
						'description'	 => '',
					));

				# Wrapper
					$this->set_optional_widget_areas_wrapper_theme_mods();
				# Each Item
					$this->set_optional_widget_areas_appended_areas_theme_mods();

			}
				/**
				 * Wrapper
				**/
				function set_optional_widget_areas_wrapper_theme_mods() {

					# Widget Areas
						$optional_widget_areas_args = array(
							'mobile_sidebar' => array(
								'id' => 'mobile_sidebar',
								'name' => esc_html__( 'Mobile Sidebar ( Wrapper )', ShapeShifter_Extensions::TEXTDOMAIN ),
							),
							'after_header' => array(
								'id' => 'after_header',
								'name' => esc_html__( 'After the Header ( Wrapper )', ShapeShifter_Extensions::TEXTDOMAIN ),
							),
							'before_content_area' => array(
								'id' => 'before_content_area',
								'name' => esc_html__( 'Before Content Area ( Wrapper )', ShapeShifter_Extensions::TEXTDOMAIN ),
							),
							'before_content' => array(
								'id' => 'before_content',
								'name' => esc_html__( 'Before the Content ( Wrapper )', ShapeShifter_Extensions::TEXTDOMAIN ),
							),
							'beginning_of_content' => array(
								'id' => 'beginning_of_content',
								'name' => esc_html__( 'At the Beginning of the Content ( Wrapper )', ShapeShifter_Extensions::TEXTDOMAIN ),
							),
							'before_1st_h2_of_content' => array(
								'id' => 'before_1st_h2_of_content',
								'name' => esc_html__( 'Before the First H2 tag of the Content ( Wrapper )', ShapeShifter_Extensions::TEXTDOMAIN ),
							),
							'end_of_content' => array(
								'id' => 'end_of_content',
								'name' => esc_html__( 'At the End of the Content ( Wrapper )', ShapeShifter_Extensions::TEXTDOMAIN ),
							),
							'after_content' => array(
								'id' => 'after_content',
								'name' => esc_html__( 'After the Content ( Wrapper )', ShapeShifter_Extensions::TEXTDOMAIN ),
							),
							'before_footer' => array(
								'id' => 'before_footer',
								'name' => esc_html__( 'Before the Footer ( Wrapper )', ShapeShifter_Extensions::TEXTDOMAIN ),
							),
							'in_footer' => array(
								'id' => 'in_footer',
								'name' => esc_html__( 'In the Footer ( Wrapper )', ShapeShifter_Extensions::TEXTDOMAIN ),
							),
						);

					foreach( $optional_widget_areas_args as $name => $data ) { 

						//if( in_array( $name, array( '' ) ) )

						$this->wp_customize->add_section( 'widget_area_' . $data['id'], array(
							'title' => $data['name'],
							'panel' => 'optional_widget_area_settings_panel',
						));

							# Wrapper Background
								# Background Color
									$this->wp_customize->add_setting( $data['id'] . '_wrapper_background_color', array( 
										'default' => 'rgba(255,255,255,0)',
										'transport' => 'postMessage',
										'sanitize_callback' => array( 'SSE_Sanitize_Methods', 'sanitize_color_value' ),
										'sanitize_js_callback' => array( 'SSE_Sanitize_Methods', 'sanitize_color_value' ),
									));
									$this->wp_customize->add_control( new ShapeShifter_Alpha_Color_Control( $this->wp_customize, $data['id'] . '_wrapper_background_color', array(
										'label' => esc_html__( 'Background Color ( Area Wrapper )', ShapeShifter_Extensions::TEXTDOMAIN ),
										'section' => 'widget_area_' . $data['id'],
										'settings' => $data['id'] . '_wrapper_background_color',
									)));

								# Background Image
									$this->wp_customize->add_setting( $data['id'] . '_wrapper_background_image', array(
										'default' => '',
										'capability' => 'edit_theme_options',
										'transport' => 'postMessage',
										'sanitize_callback' => 'esc_url_raw',
										'sanitize_js_callback' => 'esc_url_raw',
									));
									$this->wp_customize->add_control( new WP_Customize_Image_Control(
										$this->wp_customize, $data['id'] . '_wrapper_background_image', array(
											'label' => esc_html__( 'Background Image ( Area Wrapper )', ShapeShifter_Extensions::TEXTDOMAIN ),
											'section' => 'widget_area_' . $data['id'],
											'settings' => $data['id'] . '_wrapper_background_image',
										)
									));	
								
								# Background Image Size
									$this->wp_customize->add_setting( $data['id'] . '_wrapper_background_image_size', array(
										'default' => '100px',
										'capability' => 'edit_theme_options',
										'transport'=> 'postMessage',
										'sanitize_callback' => array( 'SSE_Sanitize_Methods', 'sanitize_background_image_size' ), 
										'sanitize_js_callback' => array( 'SSE_Sanitize_Methods', 'sanitize_background_image_size' ),
									));
									$this->wp_customize->add_control( $data['id'] . '_wrapper_background_image_size', array(
										'section' => 'widget_area_' . $data['id'],
										'settings' => $data['id'] . '_wrapper_background_image_size',
										'label' => esc_html__( 'Background Size ( Area Wrapper )', ShapeShifter_Extensions::TEXTDOMAIN ),
										'description' => __( 'Enter the value like "Horizontal Vertical" with units', ShapeShifter_Extensions::TEXTDOMAIN ),
										'type' => 'select',
										'choices' => $this->background_image_sizes,
									));	
							
								# Background Image Position Row
									$this->wp_customize->add_setting( $data['id'] . '_wrapper_background_image_position_row', array(
										'default'  => 'center',
										'capability' => 'edit_theme_options',
										'transport' => 'postMessage',
										'sanitize_callback' => array( 'SSE_Sanitize_Methods', 'sanitize_background_position_row' ), 
										'sanitize_js_callback' => array( 'SSE_Sanitize_Methods', 'sanitize_background_position_row' ),
									));
									$this->wp_customize->add_control( $data['id'] . '_wrapper_background_image_position_row', array(
										'section' => 'widget_area_' . $data['id'],
										'settings' => $data['id'] . '_wrapper_background_image_position_row',
										'label' => esc_html__( 'Background Position Row ( Area Wrapper )', ShapeShifter_Extensions::TEXTDOMAIN ),
										'type' => 'select',
										'choices' => $this->background_position_row,
									));	

								# Background Image Position Column
									$this->wp_customize->add_setting( $data['id'] . '_wrapper_background_image_position_column', array(
										'default'  => 'center',
										'capability' => 'edit_theme_options',
										'transport' => 'postMessage',
										'sanitize_callback' => array( 'SSE_Sanitize_Methods', 'sanitize_background_position_column' ), 
										'sanitize_js_callback' => array( 'SSE_Sanitize_Methods', 'sanitize_background_position_column' ),
									));
									$this->wp_customize->add_control( $data['id'] . '_wrapper_background_image_position_column', array(
										'section' => 'widget_area_' . $data['id'],
										'settings' => $data['id'] . '_wrapper_background_image_position_column',
										'label' => esc_html__( 'Background Position Column ( Area Wrapper )', ShapeShifter_Extensions::TEXTDOMAIN ),
										'type' => 'select',
										'choices' => $this->background_position_column,
									));	
							
								# Background Image Repeat
									$this->wp_customize->add_setting( $data['id'] . '_wrapper_background_image_repeat', array(
										'default'  => 'no-repeat',
										'capability' => 'edit_theme_options',
										'transport' => 'postMessage',
										'sanitize_callback' => array( 'SSE_Sanitize_Methods', 'sanitize_background_repeat' ), 
										'sanitize_js_callback' => array( 'SSE_Sanitize_Methods', 'sanitize_background_repeat' ),
									));
									$this->wp_customize->add_control( $data['id'] . '_wrapper_background_image_repeat', array(
										'section' => 'widget_area_' . $data['id'],
										'settings' => $data['id'] . '_wrapper_background_image_repeat',
										'label' => esc_html__( 'Background Repeat ( Area Wrapper )', ShapeShifter_Extensions::TEXTDOMAIN ),
										'type' => 'select',
										'choices' => $this->background_repeats,
									));	
								
								# Background Image Attacment
									$this->wp_customize->add_setting( $data['id'] . '_wrapper_background_image_attachment', array(
										'default'  => 'scroll',
										'capability' => 'edit_theme_options',
										'transport' => 'postMessage',
										'sanitize_callback' => array( 'SSE_Sanitize_Methods', 'sanitize_background_attachment' ), 
										'sanitize_js_callback' => array( 'SSE_Sanitize_Methods', 'sanitize_background_attachment' ),
									));
									$this->wp_customize->add_control( $data['id'] . '_wrapper_background_image_attachment', array(
										'section' => 'widget_area_' . $data['id'],
										'settings' => $data['id'] . '_wrapper_background_image_attachment',
										'label' => esc_html__( 'Background Attachment ( Area Wrapper )', ShapeShifter_Extensions::TEXTDOMAIN ),
										'type' => 'select',
										'choices' => $this->background_attachments,
									));	
								
					}

				}

				/**
				 * Each Item
				**/
				function set_optional_widget_areas_appended_areas_theme_mods() {

					$index = 0;
					if ( is_array( $this->widget_areas_settings ) ) { foreach( $this->widget_areas_settings as $id => $widget_area_data ) {

						if ( ! isset( $widget_area_data['hook'] ) || ! is_string( $widget_area_data['hook'] ) ) continue;

						$index = sanitize_text_field( $index );
						$widget_area_data['hook'] = sanitize_text_field( $widget_area_data['hook'] );
						$widget_area_data['name'] = esc_html( $widget_area_data['name'] );

						$id_fix = $widget_area_data['hook'] . '_' . $index;

						$this->wp_customize->add_section( 'widget_area_' . $id_fix, array(
							'title' => $widget_area_data['name'],
							'panel' => 'optional_widget_area_settings_panel',
						));

							# Font Family
								$this->wp_customize->add_setting( $id_fix . '_font_family', array(
									'default'  => '"Yu Gothic", "游ゴシック"',
									'transport' => 'postMessage',
									'sanitize_callback' => array( 'SSE_Sanitize_Methods', 'sanitize_font_families' ),
									'sanitize_js_callback' => array( 'SSE_Sanitize_Methods', 'sanitize_font_families' ),
								));
								$this->wp_customize->add_control( $id_fix . '_font_family', array(
									'section' => 'widget_area_' . $id_fix,
									'settings' => $id_fix . '_font_family',
									'label' => esc_html__( 'Font Family', ShapeShifter_Extensions::TEXTDOMAIN ),
									'type' => 'select',
									'choices' => $this->font_families,
								));

							# Enter Animation
								# Widget Area
									$this->wp_customize->add_setting( $id_fix . '_area_animation_enter', array(
										'default'  => 'none',
										'transport' => 'postMessage',
										'sanitize_callback' => 'sanitize_text_field',
										'sanitize_js_callback' => 'sanitize_text_field',
									));
									$this->wp_customize->add_control( $id_fix . '_area_animation_enter', array(
										'section' => 'widget_area_' . $id_fix,
										'settings' => $id_fix . '_area_animation_enter',
										'label' => esc_html__( 'Area Animation Enter', ShapeShifter_Extensions::TEXTDOMAIN ),
										'type' => 'select',
										'choices' => $this->animate_css['enter'],
									));

							# Widget Area Background
								# Background Color
									$this->wp_customize->add_setting( $id_fix . '_area_background_color', array( 
										'default' => 'rgba(255,255,255,0)',
										'transport' => 'postMessage',
										'sanitize_callback' => array( 'SSE_Sanitize_Methods', 'sanitize_color_value' ),
										'sanitize_js_callback' => array( 'SSE_Sanitize_Methods', 'sanitize_color_value' ),
									));
									$this->wp_customize->add_control( new ShapeShifter_Alpha_Color_Control( $this->wp_customize, $id_fix . '_area_background_color', array(
										'label' => esc_html__( 'Background Color ( Each Area )', ShapeShifter_Extensions::TEXTDOMAIN ),
										'section' => 'widget_area_' . $id_fix,
										'settings' => $id_fix . '_area_background_color',
									)));

								# Background Image
									$this->wp_customize->add_setting( $id_fix . '_area_background_image', array(
										'default' => '',
										'capability' => 'edit_theme_options',
										'transport' => 'postMessage',
										'sanitize_callback' => 'esc_url_raw',
										'sanitize_js_callback' => 'esc_url_raw',
									));
									$this->wp_customize->add_control( new WP_Customize_Image_Control(
										$this->wp_customize, $id_fix . '_area_background_image', array(
											'label' => esc_html__( 'Background Image ( Each Area )', ShapeShifter_Extensions::TEXTDOMAIN ),
											'section' => 'widget_area_' . $id_fix,
											'settings' => $id_fix . '_area_background_image',
										)
									));	

								# Background Image Size
									$this->wp_customize->add_setting( $id_fix . '_area_background_image_size', array(
										'default' => '100px',
										'capability' => 'edit_theme_options',
										'transport'=> 'postMessage',
										'sanitize_callback' => array( 'SSE_Sanitize_Methods', 'sanitize_background_image_size' ), 
										'sanitize_js_callback' => array( 'SSE_Sanitize_Methods', 'sanitize_background_image_size' ),
									));
									$this->wp_customize->add_control( $id_fix . '_area_background_image_size', array(
										'section' => 'widget_area_' . $id_fix,
										'settings' => $id_fix . '_area_background_image_size',
										'label' => esc_html__( 'Background Size ( Each Area )', ShapeShifter_Extensions::TEXTDOMAIN ),
										'description' => __( 'Enter the value like "Horizontal Vertical" with units', ShapeShifter_Extensions::TEXTDOMAIN ),
										'type' => 'select',
										'choices' => $this->background_image_sizes,
									));	

								# Background Image Position Row
									$this->wp_customize->add_setting( $id_fix . '_area_background_image_position_row', array(
										'default'  => 'center',
										'capability' => 'edit_theme_options',
										'transport' => 'postMessage',
										'sanitize_callback' => array( 'SSE_Sanitize_Methods', 'sanitize_background_position_row' ), 
										'sanitize_js_callback' => array( 'SSE_Sanitize_Methods', 'sanitize_background_position_row' ),
									));
									$this->wp_customize->add_control( $id_fix . '_area_background_image_position_row', array(
										'section' => 'widget_area_' . $id_fix,
										'settings' => $id_fix . '_area_background_image_position_row',
										'label' => esc_html__( 'Background Position Row ( Each Area )', ShapeShifter_Extensions::TEXTDOMAIN ),
										'type' => 'select',
										'choices' => $this->background_position_row,
									));	

								# Background Image Position Column
									$this->wp_customize->add_setting( $id_fix . '_area_background_image_position_column', array(
										'default'  => 'center',
										'capability' => 'edit_theme_options',
										'transport' => 'postMessage',
										'sanitize_callback' => array( 'SSE_Sanitize_Methods', 'sanitize_background_position_column' ), 
										'sanitize_js_callback' => array( 'SSE_Sanitize_Methods', 'sanitize_background_position_column' ),
									));
									$this->wp_customize->add_control( $id_fix . '_area_background_image_position_column', array(
										'section' => 'widget_area_' . $id_fix,
										'settings' => $id_fix . '_area_background_image_position_column',
										'label' => esc_html__( 'Background Position Column ( Each Area )', ShapeShifter_Extensions::TEXTDOMAIN ),
										'type' => 'select',
										'choices' => $this->background_position_column,
									));	

								# Background Image Repeat
									$this->wp_customize->add_setting( $id_fix . '_area_background_image_repeat', array(
										'default'  => 'no-repeat',
										'capability' => 'edit_theme_options',
										'transport' => 'postMessage',
										'sanitize_callback' => array( 'SSE_Sanitize_Methods', 'sanitize_background_repeat' ), 
										'sanitize_js_callback' => array( 'SSE_Sanitize_Methods', 'sanitize_background_repeat' ),
									));
									$this->wp_customize->add_control( $id_fix . '_area_background_image_repeat', array(
										'section' => 'widget_area_' . $id_fix,
										'settings' => $id_fix . '_area_background_image_repeat',
										'label' => esc_html__( 'Background Repeat ( Each Area )', ShapeShifter_Extensions::TEXTDOMAIN ),
										'type' => 'select',
										'choices' => $this->background_repeats,
									));	

								# Background Image Attachment
									$this->wp_customize->add_setting( $id_fix . '_area_background_image_attachment', array(
										'default'  => 'scroll',
										'capability' => 'edit_theme_options',
										'transport' => 'postMessage',
										'sanitize_callback' => array( 'SSE_Sanitize_Methods', 'sanitize_background_attachment' ), 
										'sanitize_js_callback' => array( 'SSE_Sanitize_Methods', 'sanitize_background_attachment' ),
									));
									$this->wp_customize->add_control( $id_fix . '_area_background_image_attachment', array(
										'section' => 'widget_area_' . $id_fix,
										'settings' => $id_fix . '_area_background_image_attachment',
										'label' => esc_html__( 'Background Attachment ( Each Area )', ShapeShifter_Extensions::TEXTDOMAIN ),
										'type' => 'select',
										'choices' => $this->background_attachments,
									));	

								# Padding
									$this->wp_customize->add_setting( $id_fix . '_area_padding', array(
										'default' => 0,
										'capability' => 'edit_theme_options',
										'transport' => 'postMessage',
										'sanitize_callback' => 'absint',
										'sanitize_js_callback' => 'absint',
									));
									$this->wp_customize->add_control( $id_fix . '_area_padding', array(
										'section' => 'widget_area_' . $id_fix,
										'settings' => $id_fix . '_area_padding',
										'label' => esc_html__( 'Padding for Widget Area', ShapeShifter_Extensions::TEXTDOMAIN ),
										'type' => 'range',
										'input_attrs' => array(
											'min' => 0,
											'max' => 100,
											'step' => 1,
											'value' => absint( 
												isset( $this->theme_mods[ $id_fix . '_area_padding'] )
												? $this->theme_mods[ $id_fix . '_area_padding']
												: 0
											),
											'id' => $id_fix . '_area_padding_id',
											'style' => 'width:100%;',
										),
									));

							# Widget Outer Background
								# Background Color
									$this->wp_customize->add_setting( $id_fix . '_outer_background_color', array( 
										'default' => 'rgba(255,255,255,0)',
										'transport' => 'postMessage',
										'sanitize_callback' => array( 'SSE_Sanitize_Methods', 'sanitize_color_value' ),
										'sanitize_js_callback' => array( 'SSE_Sanitize_Methods', 'sanitize_color_value' ),
									));
									$this->wp_customize->add_control( new ShapeShifter_Alpha_Color_Control( $this->wp_customize, $id_fix . '_outer_background_color', array(
										'label' => esc_html__( 'Background Color ( Outside of Widget )', ShapeShifter_Extensions::TEXTDOMAIN ),
										'section' => 'widget_area_' . $id_fix,
										'settings' => $id_fix . '_outer_background_color',
									)));

								# Background Image
									$this->wp_customize->add_setting( $id_fix . '_outer_background_image', array(
										'default' => '',
										'capability' => 'edit_theme_options',
										'transport' => 'postMessage',
										'sanitize_callback' => 'esc_url_raw',
										'sanitize_js_callback' => 'esc_url_raw',
									));
									$this->wp_customize->add_control( new WP_Customize_Image_Control(
										$this->wp_customize, $id_fix . '_outer_background_image', array(
											'label' => esc_html__( 'Background Image ( Outside of Widget )', ShapeShifter_Extensions::TEXTDOMAIN ),
											'section' => 'widget_area_' . $id_fix,
											'settings' => $id_fix . '_outer_background_image',
										)
									));
								
								# Background Image Size
									$this->wp_customize->add_setting( $id_fix . '_outer_background_image_size', array(
										'default' => '100px',
										'capability' => 'edit_theme_options',
										'transport'=> 'postMessage',
										'sanitize_callback' => array( 'SSE_Sanitize_Methods', 'sanitize_background_image_size' ), 
										'sanitize_js_callback' => array( 'SSE_Sanitize_Methods', 'sanitize_background_image_size' ),
									));
									$this->wp_customize->add_control( $id_fix . '_outer_background_image_size', array(
										'section' => 'widget_area_' . $id_fix,
										'settings' => $id_fix . '_outer_background_image_size',
										'label' => esc_html__( 'Background Size ( Outside of Widget )', ShapeShifter_Extensions::TEXTDOMAIN ),
										'description' => __( 'Enter the value like "Horizontal Vertical" with units', ShapeShifter_Extensions::TEXTDOMAIN ),
										'type' => 'select',
										'choices' => $this->background_image_sizes,
									));

								# Background Image Position Row
									$this->wp_customize->add_setting( $id_fix . '_outer_background_image_position_row', array(
										'default'  => 'center',
										'capability' => 'edit_theme_options',
										'transport' => 'postMessage',
										'sanitize_callback' => array( 'SSE_Sanitize_Methods', 'sanitize_background_position_row' ), 
										'sanitize_js_callback' => array( 'SSE_Sanitize_Methods', 'sanitize_background_position_row' ),
									));
									$this->wp_customize->add_control( $id_fix . '_outer_background_image_position_row', array(
										'section' => 'widget_area_' . $id_fix,
										'settings' => $id_fix . '_outer_background_image_position_row',
										'label' => esc_html__( 'Background Position Row ( Outside of Widget )', ShapeShifter_Extensions::TEXTDOMAIN ),
										'type' => 'select',
										'choices' => $this->background_position_row,
									));

								# Background Image Position Column
									$this->wp_customize->add_setting( $id_fix . '_outer_background_image_position_column', array(
										'default'  => 'center',
										'capability' => 'edit_theme_options',
										'transport' => 'postMessage',
										'sanitize_callback' => array( 'SSE_Sanitize_Methods', 'sanitize_background_position_column' ), 
										'sanitize_js_callback' => array( 'SSE_Sanitize_Methods', 'sanitize_background_position_column' ),
									));
									$this->wp_customize->add_control( $id_fix . '_outer_background_image_position_column', array(
										'section' => 'widget_area_' . $id_fix,
										'settings' => $id_fix . '_outer_background_image_position_column',
										'label' => esc_html__( 'Background Position Column ( Outside of Widget )', ShapeShifter_Extensions::TEXTDOMAIN ),
										'type' => 'select',
										'choices' => $this->background_position_column,
									));	
							
								# Background Image Repeat
									$this->wp_customize->add_setting( $id_fix . '_outer_background_image_repeat', array(
										'default'  => 'no-repeat',
										'capability' => 'edit_theme_options',
										'transport' => 'postMessage',
										'sanitize_callback' => array( 'SSE_Sanitize_Methods', 'sanitize_background_repeat' ), 
										'sanitize_js_callback' => array( 'SSE_Sanitize_Methods', 'sanitize_background_repeat' ),
									));
									$this->wp_customize->add_control( $id_fix . '_outer_background_image_repeat', array(
										'section' => 'widget_area_' . $id_fix,
										'settings' => $id_fix . '_outer_background_image_repeat',
										'label' => esc_html__( 'Background Repeat ( Outside of Widget )', ShapeShifter_Extensions::TEXTDOMAIN ),
										'type' => 'select',
										'choices' => $this->background_repeats,
									));

								# Background Image Attachment
									$this->wp_customize->add_setting( $id_fix . '_outer_background_image_attachment', array(
										'default'  => 'scroll',
										'capability' => 'edit_theme_options',
										'transport' => 'postMessage',
										'sanitize_callback' => array( 'SSE_Sanitize_Methods', 'sanitize_background_attachment' ), 
										'sanitize_js_callback' => array( 'SSE_Sanitize_Methods', 'sanitize_background_attachment' ),
									));
									$this->wp_customize->add_control( $id_fix . '_outer_background_image_attachment', array(
										'section' => 'widget_area_' . $id_fix,
										'settings' => $id_fix . '_outer_background_image_attachment',
										'label' => esc_html__( 'Background Attachment ( Outside of Widget )', ShapeShifter_Extensions::TEXTDOMAIN ),
										'type' => 'select',
										'choices' => $this->background_attachments,
									));	

							# Widget Inner Background
								# Background Color
									$this->wp_customize->add_setting( $id_fix . '_inner_background_color', array( 
										'default' => 'rgba(255,255,255,0)',
										'transport' => 'postMessage',
										'sanitize_callback' => array( 'SSE_Sanitize_Methods', 'sanitize_color_value' ),
										'sanitize_js_callback' => array( 'SSE_Sanitize_Methods', 'sanitize_color_value' ),
									));
									$this->wp_customize->add_control( new ShapeShifter_Alpha_Color_Control( $this->wp_customize, $id_fix . '_inner_background_color', array(
										'label' => esc_html__( 'Background Color ( Inside of Widget )', ShapeShifter_Extensions::TEXTDOMAIN ),
										'section' => 'widget_area_' . $id_fix,
										'settings' => $id_fix . '_inner_background_color',
									)));

								# Background Image
									$this->wp_customize->add_setting( $id_fix . '_inner_background_image', array(
										'default' => '',
										'capability' => 'edit_theme_options',
										'transport' => 'postMessage',
										'sanitize_callback' => 'esc_url_raw',
										'sanitize_js_callback' => 'esc_url_raw',
									));
									$this->wp_customize->add_control( new WP_Customize_Image_Control(
										$this->wp_customize, $id_fix . '_inner_background_image', array(
											'label' => esc_html__( 'Background Image ( Inside of Widget )', ShapeShifter_Extensions::TEXTDOMAIN ),
											'section' => 'widget_area_' . $id_fix,
											'settings' => $id_fix . '_inner_background_image',
										)
									));	

								# Background Image Size
									$this->wp_customize->add_setting( $id_fix . '_inner_background_image_size', array(
										'default' => '100px',
										'capability' => 'edit_theme_options',
										'transport'=> 'postMessage',
										'sanitize_callback' => array( 'SSE_Sanitize_Methods', 'sanitize_background_image_size' ), 
										'sanitize_js_callback' => array( 'SSE_Sanitize_Methods', 'sanitize_background_image_size' ),
									));
									$this->wp_customize->add_control( $id_fix . '_inner_background_image_size', array(
										'section' => 'widget_area_' . $id_fix,
										'settings' => $id_fix . '_inner_background_image_size',
										'label' => esc_html__( 'Background Size ( Inside of Widget )', ShapeShifter_Extensions::TEXTDOMAIN ),
										'description' => __( 'Enter the value like "Horizontal Vertical" with units', ShapeShifter_Extensions::TEXTDOMAIN ),
										'type' => 'select',
										'choices' => $this->background_image_sizes,
									));	

								# Background Image Position Row
									$this->wp_customize->add_setting( $id_fix . '_inner_background_image_position_row', array(
										'default'  => 'center',
										'capability' => 'edit_theme_options',
										'transport' => 'postMessage',
										'sanitize_callback' => array( 'SSE_Sanitize_Methods', 'sanitize_background_position_row' ), 
										'sanitize_js_callback' => array( 'SSE_Sanitize_Methods', 'sanitize_background_position_row' ),
									));
									$this->wp_customize->add_control( $id_fix . '_inner_background_image_position_row', array(
										'section' => 'widget_area_' . $id_fix,
										'settings' => $id_fix . '_inner_background_image_position_row',
										'label' => esc_html__( 'Background Position Row ( Inside of Widget )', ShapeShifter_Extensions::TEXTDOMAIN ),
										'type' => 'select',
										'choices' => $this->background_position_row,
									));

								# Background Image Position Column
									$this->wp_customize->add_setting( $id_fix . '_inner_background_image_position_column', array(
										'default'  => 'center',
										'capability' => 'edit_theme_options',
										'transport' => 'postMessage',
										'sanitize_callback' => array( 'SSE_Sanitize_Methods', 'sanitize_background_position_column' ), 
										'sanitize_js_callback' => array( 'SSE_Sanitize_Methods', 'sanitize_background_position_column' ),
									));
									$this->wp_customize->add_control( $id_fix . '_inner_background_image_position_column', array(
										'section' => 'widget_area_' . $id_fix,
										'settings' => $id_fix . '_inner_background_image_position_column',
										'label' => esc_html__( 'Background Position Column ( Inside of Widget )', ShapeShifter_Extensions::TEXTDOMAIN ),
										'type' => 'select',
										'choices' => $this->background_position_column,
									));

								# Background Image Repeat
									$this->wp_customize->add_setting( $id_fix . '_inner_background_image_repeat', array(
										'default'  => 'no-repeat',
										'capability' => 'edit_theme_options',
										'transport' => 'postMessage',
										'sanitize_callback' => array( 'SSE_Sanitize_Methods', 'sanitize_background_repeat' ), 
										'sanitize_js_callback' => array( 'SSE_Sanitize_Methods', 'sanitize_background_repeat' ),
									));
									$this->wp_customize->add_control( $id_fix . '_inner_background_image_repeat', array(
										'section' => 'widget_area_' . $id_fix,
										'settings' => $id_fix . '_inner_background_image_repeat',
										'label' => esc_html__( 'Background Repeat ( Inside of Widget )', ShapeShifter_Extensions::TEXTDOMAIN ),
										'type' => 'select',
										'choices' => $this->background_repeats,
									));

								# Background Image Attachment
									$this->wp_customize->add_setting( $id_fix . '_Inner_background_image_attachment', array(
										'default'  => 'scroll',
										'capability' => 'edit_theme_options',
										'transport' => 'postMessage',
										'sanitize_callback' => array( 'SSE_Sanitize_Methods', 'sanitize_background_attachment' ), 
										'sanitize_js_callback' => array( 'SSE_Sanitize_Methods', 'sanitize_background_attachment' ),
									));
									$this->wp_customize->add_control( $id_fix . '_Inner_background_image_attachment', array(
										'section' => 'widget_area_' . $id_fix,
										'settings' => $id_fix . '_inner_background_image_attachment',
										'label' => esc_html__( 'Background Attachment ( Inside of Widget )', ShapeShifter_Extensions::TEXTDOMAIN ),
										'type' => 'select',
										'choices' => $this->background_attachments,
									));	
							
							# Design
								# Widget Border
									$this->wp_customize->add_setting( $id_fix . '_widget_border', array(
										'default'  => false,
										'transport' => 'postMessage',
										'sanitize_callback' => array( 'SSE_Sanitize_Methods', 'sanitize_checkbox' ),
										'sanitize_js_callback' => array( 'SSE_Sanitize_Methods', 'sanitize_checkbox' ),
									));
									$this->wp_customize->add_control( $id_fix . '_widget_border', array(
										'section' => 'widget_area_' . $id_fix,
										'settings' => $id_fix . '_widget_border',
										'label' => esc_html__( 'Border for Each Item', ShapeShifter_Extensions::TEXTDOMAIN ),
										'type' => 'checkbox',
									));

								# Border Radius
									$this->wp_customize->add_setting( $id_fix . '_widget_border_radius', array(
										'default' => 0,
										'capability' => 'edit_theme_options',
										'transport' => 'postMessage',
										'sanitize_callback' => 'absint',
										'sanitize_js_callback' => 'absint',
									));
									$this->wp_customize->add_control( $id_fix . '_widget_border_radius', array(
										'section' => 'widget_area_' . $id_fix,
										'settings' => $id_fix . '_widget_border_radius',
										'label' => esc_html__( 'Border Radius of Each Item', ShapeShifter_Extensions::TEXTDOMAIN ),
										'type' => 'range',
										'input_attrs' => array(
											'min' => 0,
											'max' => 50,
											'step' => 1,
											'value' => absint( 
												isset( $this->theme_mods[ $id_fix . '_widget_border_radius'] )
												? $this->theme_mods[ $id_fix . '_widget_border_radius']
												: 0
											),
											'id' => $id_fix . '_widget_border_radius_id',
											'style' => 'width:100%;',
										),
									));

								# Inner Padding
									$this->wp_customize->add_setting( $id_fix . '_widget_inner_padding', array(
										'default' => 0,
										'capability' => 'edit_theme_options',
										'transport' => 'postMessage',
										'sanitize_callback' => 'absint',
										'sanitize_js_callback' => 'absint',
									));
									$this->wp_customize->add_control( $id_fix . '_widget_inner_padding', array(
										'section' => 'widget_area_' . $id_fix,
										'settings' => $id_fix . '_widget_inner_padding',
										'label' => esc_html__( 'Padding for Each Item', ShapeShifter_Extensions::TEXTDOMAIN ),
										'type' => 'range',
										'description' => '',
										'input_attrs' => array(
											'min' => 0,
											'max' => 50,
											'step' => 1,
											'value' => absint( 
												isset( $this->theme_mods[ $id_fix . '_widget_inner_padding'] )
												? $this->theme_mods[ $id_fix . '_widget_inner_padding']
												: 0
											),
											'id' => $id_fix . '_widget_inner_padding_id',
											'style' => 'width:100%;',
										),
									));

							# Title FontAwesome Icon
								# Icon Select
									$this->wp_customize->add_setting( $id_fix . '_widget_title_fontawesome_icon_select', array(
										'default'  => 'f150',
										'transport' => 'postMessage',
										'sanitize_callback' => 'sanitize_text_field',
										'sanitize_js_callback' => 'sanitize_text_field',
									));
									$this->wp_customize->add_control( $id_fix . '_widget_title_fontawesome_icon_select', array(
										'section' => 'widget_area_' . $id_fix,
										'settings' => $id_fix . '_widget_title_fontawesome_icon_select',
										'label' => esc_html__( 'Font Awesome Icon for Widget Title', ShapeShifter_Extensions::TEXTDOMAIN ),
										'description' => __( 'FYR, check the reference page "<a rel="nofollow" target="_blank" href="http://fortawesome.github.io/Font-Awesome/icons/">Fontawesome Icons</a>". * This settings choices don\'t include alias.', ShapeShifter_Extensions::TEXTDOMAIN ),
										'type' => 'select',
										'choices' => $this->icons['FontAwesome'],
									));

								# Icon Color
									$this->wp_customize->add_setting( $id_fix . '_widget_title_fontawesome_icon_color', array( 
										'default' => '#000000',
										'transport' => 'postMessage',
										'sanitize_callback' => array( 'SSE_Sanitize_Methods', 'sanitize_color_value' ),
										'sanitize_js_callback' => array( 'SSE_Sanitize_Methods', 'sanitize_color_value' ),
									));
									$this->wp_customize->add_control( new ShapeShifter_Alpha_Color_Control( $this->wp_customize, $id_fix . '_widget_title_fontawesome_icon_color', array(
										'label' => esc_html__( 'Icon Color', ShapeShifter_Extensions::TEXTDOMAIN ),
										'section' => 'widget_area_' . $id_fix,
										'settings' => $id_fix . '_widget_title_fontawesome_icon_color',
									)));

							# List FontAwesome Icon
								# Icon Select
									$this->wp_customize->add_setting( $id_fix . '_widget_list_fontawesome_icon_select', array(
										'default'  => 'f101',
										'transport' => 'postMessage',
										'sanitize_callback' => 'sanitize_text_field',
										'sanitize_js_callback' => 'sanitize_text_field',
									));
									$this->wp_customize->add_control( $id_fix . '_widget_list_fontawesome_icon_select', array(
										'section' => 'widget_area_' . $id_fix,
										'settings' => $id_fix . '_widget_list_fontawesome_icon_select',
										'label' => esc_html__( 'Font Awesome Icon for List of Widget Item ( Like "Category" )', ShapeShifter_Extensions::TEXTDOMAIN ),
										'type' => 'select',
										'choices' => $this->icons['FontAwesome']
									));

								# Icon Color
									$this->wp_customize->add_setting( $id_fix . '_widget_list_fontawesome_icon_color', array( 
										'default' => '#000000',
										'transport' => 'postMessage',
										'sanitize_callback' => array( 'SSE_Sanitize_Methods', 'sanitize_color_value' ),
										'sanitize_js_callback' => array( 'SSE_Sanitize_Methods', 'sanitize_color_value' ),
									));
									$this->wp_customize->add_control( new ShapeShifter_Alpha_Color_Control( $this->wp_customize, $id_fix . '_widget_list_fontawesome_icon_color', array(
										'label' => esc_html__( 'Icon Color for List of Widget Item ( Like "Category" )', ShapeShifter_Extensions::TEXTDOMAIN ),
										'section' => 'widget_area_' . $id_fix,
										'settings' => $id_fix . '_widget_list_fontawesome_icon_color',
									)));

							# Colors
								# Title
									$this->wp_customize->add_setting( $id_fix . '_title_color', array( 
										'default' => '#000000',
										'transport' => 'postMessage',
										'sanitize_callback' => array( 'SSE_Sanitize_Methods', 'sanitize_color_value' ),
										'sanitize_js_callback' => array( 'SSE_Sanitize_Methods', 'sanitize_color_value' ),
									));
									$this->wp_customize->add_control( new ShapeShifter_Alpha_Color_Control( $this->wp_customize, $id_fix . '_title_color', array(
										'label' => esc_html__( 'Title Color', ShapeShifter_Extensions::TEXTDOMAIN ),
										'section' => 'widget_area_' . $id_fix,
										'settings' => $id_fix . '_title_color',
									)));

								# Text
									$this->wp_customize->add_setting( $id_fix . '_text_color', array( 
										'default' => '#666666',
										'transport' => 'postMessage',
										'sanitize_callback' => array( 'SSE_Sanitize_Methods', 'sanitize_color_value' ),
										'sanitize_js_callback' => array( 'SSE_Sanitize_Methods', 'sanitize_color_value' ),
									));
									$this->wp_customize->add_control( new ShapeShifter_Alpha_Color_Control( $this->wp_customize, $id_fix . '_text_color', array(
										'label' => esc_html__( 'Text Color', ShapeShifter_Extensions::TEXTDOMAIN ),
										'section' => 'widget_area_' . $id_fix,
										'settings' => $id_fix . '_text_color',
									)));

								# Link Text
									$this->wp_customize->add_setting( $id_fix . '_link_text_color', array( 
										'default' => '#337ab7',
										'transport' => 'postMessage',
										'sanitize_callback' => array( 'SSE_Sanitize_Methods', 'sanitize_color_value' ),
										'sanitize_js_callback' => array( 'SSE_Sanitize_Methods', 'sanitize_color_value' ),
									));
									$this->wp_customize->add_control( new ShapeShifter_Alpha_Color_Control( $this->wp_customize, $id_fix . '_link_text_color', array(
										'label' => esc_html__( 'Text Link Color', ShapeShifter_Extensions::TEXTDOMAIN ),
										'section' => 'widget_area_' . $id_fix,
										'settings' => $id_fix . '_link_text_color',
									)));

						$index++;

					} }


				}

			/**
			 * Others
			 * 	Panel: "other_custom_settings_panel"
			 * 		Sections: "image_section", "optional_styles_section"
			**/
			function set_others_theme_mods() {

				# Skins
					$this->set_others_skin_theme_mods();

			}

				/**
				 * Skins
				**/
				function set_others_skin_theme_mods() {

					# Select Skin
						$this->wp_customize->add_setting( 'theme_skin', array(
							'default'  => 'none',
							'capability' => 'edit_theme_options',
							'transport' => 'postMessage',
							'sanitize_callback' => 'sanitize_text_field',
							'sanitize_js_callback' => 'sanitize_text_field',
						));
						$this->wp_customize->add_control( 'theme_skin', array(
							'section' => 'optional_styles_section',
							'settings' => 'theme_skin',
							'label' => esc_html__( 'Skin', ShapeShifter_Extensions::TEXTDOMAIN ),
							'description' => esc_html__( 'Please, reload in order to check the change. * This is the testing functionality.', ShapeShifter_Extensions::TEXTDOMAIN ),
							'type' => 'select',
							'choices' => array(
								'none' => __( 'Default', ShapeShifter_Extensions::TEXTDOMAIN ),
								'pawer' => __( 'Pawer', ShapeShifter_Extensions::TEXTDOMAIN ),
							),
						));

				}

}
}


