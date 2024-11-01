<?php
if ( ! defined( 'ABSPATH' ) ) exit;

if( ! class_exists( 'SSE_Styles_Manager' ) ) {
class SSE_Styles_Manager {

	/**
	 * Static
	**/
		/**
		 * Instance
		 * @var SSE_Styles_Manager
		**/
		protected static $instance = null;

	/**
	 * Properties
	**/
		public $font_families;
		public $font_face_style = '';

	/**
	 * Init
	**/
		/**
		 * Public initializer
		 * @var SSE_Styles_Manager
		**/
		public static function get_instance()
		{
			if ( null === self::$instance ) {
				self::$instance = new Self();
			}
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
		}

		/**
		 * Init hooks
		**/
		protected function init_hooks()
		{
			add_action( shapeshifter()->get_prefixed_action_hook( 'setup_theme_mods' ), array( $this, 'setup_theme_mods' ) );
			add_action( 'shapeshifter_frontend_after_define_content_area_layout', array( $this, 'setup_widths' ) );
		}

		public function setup_theme_mods()
		{

			# Theme Mods 
				$this->theme_mods = sse()->get_theme_mods();

				$this->sidebar_left_width = absint( $this->theme_mods['sidebar_left_max_width'] );
				$this->content_inner_width = absint( $this->theme_mods['main_content_max_width'] );
				$this->sidebar_right_width = absint( $this->theme_mods['sidebar_right_max_width'] );
				$this->content_width = $this->sidebar_left_width + $this->content_inner_width + $this->sidebar_right_width;

			# Fonts
				$this->setup_font_faces();

			# Optional Widget Areas
				$this->optional_widget_areas_args = sse()->get_widget_area_manager()->get_optional_widget_areas();

			# Screens
				$this->devices = array( 'pc', 'mobile' );
				$this->break_points = (
					shapeshifter_boolval( $this->theme_mods['is_responsive'] )
					? array( 
						'common', 
						320, 
						640, 
						1024
					)
					: array(
						'common'
					)
				);

		}

		/**
		 * Setup Widths
		 * 
		**/
		public function setup_widths( $ss_frontend_manager )
		{
			$this->content_width = absint( $ss_frontend_manager->content_width );
			$this->content_inner_width = absint( $ss_frontend_manager->content_inner_width );
			$this->is_one_column_page_width_size_max = shapeshifter_boolval( $this->theme_mods['is_one_column_main_content_max_width_on'] );
		}

		/**
		 * Setup Font Faces
		 * 
		**/
		public function setup_font_faces()
		{

			$upload_dir = wp_upload_dir();
			$fonts_dir = $upload_dir['baseurl'] . '/custom-fonts/';
			$this->font_families = get_option( sse()->get_prefixed_option_name( 'custom_fonts' ), '' );
			if ( is_array( $this->font_families ) ) { foreach( $this->font_families as $font_family => $font_files ) {

				$this->font_face_style .= ' @font-face {';
				$this->font_face_style .= ' font-family: "' . sanitize_text_field( $font_family ) . '";';
				$this->font_face_style .= ' font-display: auto;';
				$this->font_face_style .= ' font-style: normal;';
				$this->font_face_style .= ' src:';

				foreach( $font_files as $index => $font_file ) {

					$font_file = sanitize_text_field( $font_file );

					preg_match( '/([^\s]+)\.(otf|ttf|eot|woff|woff2)$/', $font_file, $matched );

					if ( in_array( $matched[ 2 ], array( 'woff', 'woff2' ) ) ) {
						$font_format = $matched[ 2 ];
					} elseif ( $matched[ 2 ] == 'otf' ) {
						$font_format = 'opentype';
					} elseif ( $matched[ 2 ] == 'ttf' ) {
						$font_format = 'truetype';
					} elseif ( $matched[ 2 ] == 'eot' ) {
						$font_format = 'embedded-opentype';
					}
					$this->font_face_style .= ' url("' . esc_url_raw( $fonts_dir . $font_file ) . '") format("' . sanitize_text_field( $font_format ) . '"), ';

				}
				$this->font_face_style = substr( $this->font_face_style, 0, -2 );

				$this->font_face_style .= '; }';

			} }

		}

	# Setup
		public function set_styles() {

			foreach( $this->devices as $index => $device ) {

				$this->styles[ $device ] = array();

				$style = '';

				foreach( $this->break_points as $index => $break_point ) {

					//if ( $break_point !== 'common' ) continue;
					if( ! method_exists( $this, 'get_' . $break_point . '_styles' ) ) continue;

					$this->styles[ $device ]['screen_' . $break_point ] = '';

					$this->styles[ $device ]['screen_' . $break_point ] .= '@media screen' . ( 
						$break_point !== 'common' 
						? ' and ( max-width: ' . $break_point . 'px )' 
						: '' 
					) . ' {' . PHP_EOL;

						$this->styles[ $device ]['screen_' . $break_point ] .= $this->trim_style(
							apply_filters(
								'shapeshifter_extensions_' . $device . '_' . $break_point . '_customized_style',
								call_user_func_array( 
									array( $this, 'get_' . $break_point . '_styles' ), 
									array( $device, $break_point ) 
								),
								$device,
								$break_point
							)
						);

					$this->styles[ $device ]['screen_' . $break_point ] .= PHP_EOL . '} ' . PHP_EOL;

					$style .= apply_filters( 'shapeshifter_extensions_filter_mods_styles_device_breakpoint', $this->styles[ $device ]['screen_' . $break_point ], $device, $break_point );

				}

				$this->styles[ $device ]['total'] = apply_filters( 'shapeshifter_extensions_filter_mods_styles_device', $style, $device );

			}

		}

	# Common styles
		function get_common_styles( $device = 'pc', $break_point = 'common' ) {

			# Init
				$style = '';

			# Body
				$style .= $this->get_common_body_styles( $device, $break_point );

			# Header
				# Logo Background
					$style .= $this->get_common_logo_background_styles( $device, $break_point );
				# Logo
					$style .= $this->get_common_logo_styles( $device, $break_point );

			# Top Nav Menu
				# Main Menu
					$style .= $this->get_common_top_nav_main_menu_styles( $device, $break_point );
				# Sub and after Sub
					$style .= $this->get_common_top_nav_sub_menu_styles( $device, $break_point );

			# Sub Nav Menu
				$style .= $this->get_common_nav_main_styles( $device, $break_point );

			# Archive
				$style .= $this->get_common_archive_page_styles( $device, $break_point );

			# Content Items
				$style .= $this->get_common_content_items_styles( $device, $break_point );

			# Shortcodes
				$style .= $this->get_common_shortcode_items_styles( $device, $break_point );

			# Widget Areas
				$style .= $this->get_common_widget_areas_styles( $device, $break_point );

			# Widgets
				$style .= $this->get_common_widget_styles( $device, $break_point );

			# Skins
				if( get_theme_mod( 'theme_skin', 'none' ) !== 'none' ) {
					$style .= $this->modify_customized_styles( $device, $break_point );
				}

			# Row
				$style .= '
					@media screen and ( max-width: ' . ( $this->content_inner_width ) . 'px ) {

						.shapeshifter-row .shapeshifter-col {
							width: 100% !important;
						}

					}
				';

			# End
				return $style;

		}

			# Body
				function get_common_body_styles( $device = 'pc', $break_point = 'common' ) {

					$style = '';

					$page_types = array( 
						'home' => array(
							'name' => esc_html__( 'Home', ShapeShifter_Extensions::TEXTDOMAIN ),
							'description' => esc_html__( 'This Section is for Default "Home"', ShapeShifter_Extensions::TEXTDOMAIN ),
							'class' => '.home.blog'
						),
						'blog' => array(
							'name' => esc_html__( 'Blog', ShapeShifter_Extensions::TEXTDOMAIN ),
							'description' => esc_html__( 'This Section is for "Blog"', ShapeShifter_Extensions::TEXTDOMAIN ),
							'class' => '.blog'
						),
						'front_page' => array(
							'name' => esc_html__( 'Front Page', ShapeShifter_Extensions::TEXTDOMAIN ),
							'description' => esc_html__( 'This Section is for "Front Page"', ShapeShifter_Extensions::TEXTDOMAIN ),
							'class' => '.home.page'
						),
						'archive' => array(
							'name' => esc_html__( 'Archive Page', ShapeShifter_Extensions::TEXTDOMAIN ),
							'description' => esc_html__( 'This Section is for "Archive Page"', ShapeShifter_Extensions::TEXTDOMAIN ),
							'class' => '.archive'
						),
						'post' => array(
							'name' => esc_html__( 'Posts', ShapeShifter_Extensions::TEXTDOMAIN ),
							'description' => esc_html__( 'This Section is for "Single-Type Page"', ShapeShifter_Extensions::TEXTDOMAIN ),
							'class' => '.single'
						),
						'page' => array(
							'name' => esc_html__( 'Pages', ShapeShifter_Extensions::TEXTDOMAIN ),
							'description' => esc_html__( 'This Section is for "Page-Type Page"', ShapeShifter_Extensions::TEXTDOMAIN ),
							'class' => '.page'
						),
					);

					foreach( $page_types as $page_type => $data ) {

						$class = $data['class'];

						$background_color = sanitize_text_field( $this->theme_mods['body_' . $page_type . '_background_color'] );
						$background_image = sanitize_text_field( $this->theme_mods['body_' . $page_type . '_background_image'] );
						$background_image_size = sanitize_text_field( $this->theme_mods['body_' . $page_type . '_background_image_size'] );
						$background_position_row = sanitize_text_field( $this->theme_mods['body_' . $page_type . '_background_image_position_row'] );
						$background_position_column = sanitize_text_field( $this->theme_mods['body_' . $page_type . '_background_image_position_column'] );
						$background_image_repeat = sanitize_text_field( $this->theme_mods['body_' . $page_type . '_background_image_repeat'] );
						$background_image_attachment = sanitize_text_field( $this->theme_mods['body_' . $page_type . '_background_image_attachment'] );

						$style .= '
							body' . $class . ' {
								' . $this->get_background_color_style( $background_color ) . '
								' . $this->get_background_image_style( $background_image ) . '
								' . $this->get_background_size_style( $background_image_size ) . '
								' . $this->get_background_position_y_style( $background_position_row ) . '
								' . $this->get_background_position_x_style( $background_position_column ) . '
								' . $this->get_background_repeat_style( $background_image_repeat ) . '
								' . $this->get_background_attachment_style( $background_image_attachment ) . '
							}
						';

						# Array Ver
							$this->styles_array[ $device ][ $break_point ]['body' . $class ]['background-color'] = $background_color;
							$this->styles_array[ $device ][ $break_point ]['body' . $class ]['background-image'] = 'url(' . $background_image . ')';
							$this->styles_array[ $device ][ $break_point ]['body' . $class ]['background-size'] = $background_image_size;
							$this->styles_array[ $device ][ $break_point ]['body' . $class ]['background-position-y'] = $background_position_row;
							$this->styles_array[ $device ][ $break_point ]['body' . $class ]['background-position-x'] = $background_position_column;
							$this->styles_array[ $device ][ $break_point ]['body' . $class ]['background-repeat'] = $background_image_repeat;
							$this->styles_array[ $device ][ $break_point ]['body' . $class ]['background-attachment'] = $background_image_attachment;

					}

					return $style;

				}

			# Header
				# Logo Background
					function get_common_logo_background_styles( $device = 'pc', $break_point = 'common' ) {

						$style = '';

						$style .= '
							#logo-image-wrapper{ 
								width: 100%;

								' . $this->get_background_color_style( $this->theme_mods['header_image_background_color'] ) . '
								' . $this->get_background_image_style( $this->theme_mods['header_image_background_image'] ) . '
								' . $this->get_background_size_style( $this->theme_mods['header_image_background_image_size'] ) . '
								' . $this->get_background_position_y_style( $this->theme_mods['header_image_background_image_position_row'] ) . '
								' . $this->get_background_position_x_style( $this->theme_mods['header_image_background_image_position_column'] ) . '
								' . $this->get_background_repeat_style( $this->theme_mods['header_image_background_image_repeat'] ) . '
								' . $this->get_background_attachment_style( $this->theme_mods['header_image_background_image_attachment'] ) . '

							}
							#logo-image-inner-wrapper{
								' . $this->get_number_style( 'margin-left', $this->theme_mods['header_image_margin_side'], '', 'px', 'intval' ) . '
								' . $this->get_number_style( 'margin-right', $this->theme_mods['header_image_margin_side'], '', 'px', 'intval' ) . '
							}
							#logo-image-top-space{
								' . $this->get_number_style( 'height', $this->theme_mods['header_image_margin_top'], '', 'px', 'intval' ) . '
							}
							#logo-image-bottom-space{
								' . $this->get_number_style( 'height', $this->theme_mods['header_image_margin_bottom'], '', 'px', 'intval' ) . '
							}
						';

						return $style;

					}

				# Logo
					function get_common_logo_styles( $device = 'pc', $break_point = 'common' ) {

						$style = '';

						$style .= '
							#logo-image-wrapper-div {

								' . $this->get_background_color_style( $this->theme_mods['header_image_background_color'] ) . '
								' . $this->get_background_image_style( $this->theme_mods['header_image_url'] ) . '
								
								' . $this->get_number_style( 'max-width', $this->theme_mods['header_image_size_width'], '', 'px', 'absint' ) . '
								' . $this->get_number_style( 'height', $this->theme_mods['header_image_size_height'], '', 'px', 'absint' ) . '
								
								' . sanitize_text_field( $this->get_theme_mods_logo_position() ) . '

							}

							#logo-title-description-p{
								padding:' . intval( $this->theme_mods['header_image_title_description_padding'] ) . 'px;

								' . sanitize_text_field( $this->get_theme_mods_logo_title_description_position() ) . '

							}
							#logo-title-span {
								' . $this->get_font_family_style( $this->theme_mods['header_image_title_font_family'] ) . '
								display:' . ( 
									shapeshifter_boolval( $this->theme_mods['header_image_title_display_toggle'] )
									? 'block' 
									: 'none' 
								) . ';
								font-size:' . absint( $this->theme_mods['header_image_title_font_size'] ) . 'px;
								' . $this->get_color_style( $this->theme_mods['header_image_title_color'] ) . '
							}
							#logo-description-span {
								' . $this->get_font_family_style( $this->theme_mods['header_image_description_font_family'] ) . '
								display:' . ( 
									shapeshifter_boolval( $this->theme_mods['header_image_description_display_toggle'] )
									? 'block' 
									: 'none' 
								) . ';

								font-size:' . absint( $this->theme_mods['header_image_description_font_size'] ) . 'px;
								' . $this->get_color_style( $this->theme_mods['header_image_description_color'] ) . '
							}

						';

						return $style;

					}

			# Top Nav Menu
				/*function get_common_top_nav_menu_styles( $device = 'pc', $break_point = 'common' ) {

					$style = '';

					$style .= '
						#top-menu-nav{
							margin-left: auto;
						}
					';

					$style .= $this->get_common_top_nav_main_menu_styles( $device, $break_point );

					$style .= $this->get_common_top_nav_sub_menu_styles( $device, $break_point );

					return $style;

				}*/
					# Main Menu
						function get_common_top_nav_main_menu_styles( $device = 'pc', $break_point = 'common' ) {

							$style = '';

							return $style;

						}

					# Sub and after Sub
						function get_common_top_nav_sub_menu_styles( $device = 'pc', $break_point = 'common' ) {

							$style = '';

							$style .= '
								/* Custom */
									.shapeshifter-top-nav-menu .shapeshifter-nav-menu-item-inner-wrapper {

										/* transition: .2s; */

										position: absolute;
										overflow: hidden;

										left: 0;
										width: 100%;
										height: 0;

									}
									.shapeshifter-top-nav-menu a:hover + .shapeshifter-nav-menu-item-inner-wrapper,
									.shapeshifter-top-nav-menu .shapeshifter-nav-menu-item-inner-wrapper:hover {

										overflow: visible;
										height: 250px;

										z-index: 1;

									}
										.shapeshifter-top-nav-menu a:hover + .shapeshifter-nav-menu-item-inner-wrapper > .shapeshifter-nav-menu-item-inner,
										.shapeshifter-top-nav-menu .shapeshifter-nav-menu-item-inner-wrapper:hover > .shapeshifter-nav-menu-item-inner {

											overflow-y: scroll;

											display: flex;
											flex-wrap: wrap;

											max-width: 960px;
											height: 250px;
											margin: auto;
											padding: 20px 0;

											' . (
												sanitize_text_field( $this->theme_mods['header_background_color'] ) != '' 
												? 'background-color: ' . sanitize_text_field( $this->theme_mods['header_background_color'] ) . ';' 
												: 'background-color: rgba(255,255,255,0.9);' 
											) . '

										}

											.shapeshifter-top-nav-menu .shapeshifter-nav-menu-item-inner > .shapeshifter-nav-menu-item-thumbnail-images {

												justify-content: space-around;

											}
											.shapeshifter-top-nav-menu .shapeshifter-nav-menu-item-inner > .shapeshifter-nav-menu-item-children-wrapper {

												justify-content: space-around;

											}

												/* Depth 0 */
													/* Title Description */
														.shapeshifter-top-nav-menu li > a:hover + .nav-menu-item-inner-wrapper-depth-0 > .shapeshifter-nav-menu-item-inner > .shapeshifter-nav-menu-item-children-title-description,
														.shapeshifter-top-nav-menu li > a:focus + .nav-menu-item-inner-wrapper-depth-0 > .shapeshifter-nav-menu-item-inner > .shapeshifter-nav-menu-item-children-title-description,
														.shapeshifter-top-nav-menu .nav-menu-item-inner-wrapper-depth-0:hover > .shapeshifter-nav-menu-item-inner > .shapeshifter-nav-menu-item-children-title-description {
															width: 90%;
															margin: auto;
															border-bottom: solid #000 1px;
														}
														
														/* Title */
															.shapeshifter-top-nav-menu li > a:hover + .nav-menu-item-inner-wrapper-depth-0 > .shapeshifter-nav-menu-item-inner > .shapeshifter-nav-menu-item-children-title-description > .shapeshifter-nav-menu-item-children-title,
															.shapeshifter-top-nav-menu li > a:focus + .nav-menu-item-inner-wrapper-depth-0 > .shapeshifter-nav-menu-item-inner > .shapeshifter-nav-menu-item-children-title-description > .shapeshifter-nav-menu-item-children-title,
															.shapeshifter-top-nav-menu .nav-menu-item-inner-wrapper-depth-0:hover > .shapeshifter-nav-menu-item-inner > .shapeshifter-nav-menu-item-children-title-description > .shapeshifter-nav-menu-item-children-title {
																margin: 10px auto;
																text-align: center;
																font-size: 16px;
															}

														/* Description */
															.shapeshifter-top-nav-menu li > a:hover + .nav-menu-item-inner-wrapper-depth-0 > .shapeshifter-nav-menu-item-inner > .shapeshifter-nav-menu-item-children-title-description > .shapeshifter-nav-menu-item-children-description,
															.shapeshifter-top-nav-menu li > a:focus + .nav-menu-item-inner-wrapper-depth-0 > .shapeshifter-nav-menu-item-inner > .shapeshifter-nav-menu-item-children-title-description > .shapeshifter-nav-menu-item-children-description,
															.shapeshifter-top-nav-menu .nav-menu-item-inner-wrapper-depth-0:hover > .shapeshifter-nav-menu-item-inner > .shapeshifter-nav-menu-item-children-title-description > .shapeshifter-nav-menu-item-children-description {
																width: 70%;
																margin: 10px auto;
																font-size: 12px;
															}

													/* Images */
														.shapeshifter-top-nav-menu li > a:hover + .nav-menu-item-inner-wrapper-depth-0 > .shapeshifter-nav-menu-item-inner > .shapeshifter-nav-menu-item-thumbnail-images,
														.shapeshifter-top-nav-menu li > a:focus + .nav-menu-item-inner-wrapper-depth-0 > .shapeshifter-nav-menu-item-inner > .shapeshifter-nav-menu-item-thumbnail-images,
														.shapeshifter-top-nav-menu .nav-menu-item-inner-wrapper-depth-0:hover > .shapeshifter-nav-menu-item-inner > .shapeshifter-nav-menu-item-thumbnail-images {

															width: 300px;
															height: 200px;
															padding: 10px;

														}

													/* Children */
														ul.shapeshifter-top-nav-menu > li > a:hover + .nav-menu-item-inner-wrapper-depth-0 > .shapeshifter-nav-menu-item-inner > .shapeshifter-nav-menu-item-children-wrapper,
														ul.shapeshifter-top-nav-menu > li > a:focus + .nav-menu-item-inner-wrapper-depth-0 > .shapeshifter-nav-menu-item-inner > .shapeshifter-nav-menu-item-children-wrapper,
														ul.shapeshifter-top-nav-menu > li > .nav-menu-item-inner-wrapper-depth-0:hover > .shapeshifter-nav-menu-item-inner > .shapeshifter-nav-menu-item-children-wrapper {

															flex-grow: 3;

															width: 300px;

															padding: 10px;

														}

															.shapeshifter-top-nav-menu li > a:hover + .nav-menu-item-inner-wrapper-depth-0 > .shapeshifter-nav-menu-item-inner > .shapeshifter-nav-menu-item-children-wrapper > ul.sub-menu,
															.shapeshifter-top-nav-menu li > a:focus + .nav-menu-item-inner-wrapper-depth-0 > .shapeshifter-nav-menu-item-inner > .shapeshifter-nav-menu-item-children-wrapper > ul.sub-menu,
															.shapeshifter-top-nav-menu .nav-menu-item-inner-wrapper-depth-0:hover > .shapeshifter-nav-menu-item-inner > .shapeshifter-nav-menu-item-children-wrapper > ul.sub-menu {

																display: flex;
																flex-wrap: wrap;
																min-width: 320px;
																max-width: 960px;
																margin: auto;

															}

																.shapeshifter-top-nav-menu li > a:hover + .nav-menu-item-inner-wrapper-depth-0 > .shapeshifter-nav-menu-item-inner > .shapeshifter-nav-menu-item-children-wrapper > ul.sub-menu > li.menu-item,
																.shapeshifter-top-nav-menu li > a:focus + .nav-menu-item-inner-wrapper-depth-0 > .shapeshifter-nav-menu-item-inner > .shapeshifter-nav-menu-item-children-wrapper > ul.sub-menu > li.menu-item,
																.shapeshifter-top-nav-menu .nav-menu-item-inner-wrapper-depth-0:hover > .shapeshifter-nav-menu-item-inner > .shapeshifter-nav-menu-item-children-wrapper > ul.sub-menu > li.menu-item {

																	justify-content: space-around;
																	width: 300px;
																	padding: 10px;
																	text-align: center;

																}

																	.shapeshifter-top-nav-menu li > a:hover + .nav-menu-item-inner-wrapper-depth-0 > .shapeshifter-nav-menu-item-inner > .shapeshifter-nav-menu-item-children-wrapper > ul.sub-menu > li.menu-item > a,
																	.shapeshifter-top-nav-menu li > a:focus + .nav-menu-item-inner-wrapper-depth-0 > .shapeshifter-nav-menu-item-inner > .shapeshifter-nav-menu-item-children-wrapper > ul.sub-menu > li.menu-item > a,
																	.shapeshifter-top-nav-menu .nav-menu-item-inner-wrapper-depth-0:hover > .shapeshifter-nav-menu-item-inner > .shapeshifter-nav-menu-item-children-wrapper > ul.sub-menu > li.menu-item > a {

																		font-size: 14px;
																		margin: 5px 0;

																	}

												/* Depth 1 */
													.nav-menu-item-inner-wrapper-depth-0 > .shapeshifter-nav-menu-item-inner > .shapeshifter-nav-menu-item-children-wrapper > ul.sub-menu > li.menu-item > a + ul.sub-menu > li.menu-item {
														font-size: 10px;
													}

							';

							return $style;

						}

			# Nav Menu
				function get_common_nav_main_styles( $device = 'pc', $break_point = 'common' ) {

					$style = '

						nav.shapeshifter-main-regular-nav {
							' . ( 
								sanitize_text_field( $this->theme_mods['nav_text_font_family'] ) != ''
								? 'font-family: ' . sanitize_text_field( $this->theme_mods['nav_text_font_family'] ) . ';'
								: ''
							) . '
							width: 100%;
							z-index: 11;

							overflow:auto;
							
							border-top:solid ' . sanitize_text_field( 
								! empty( $this->theme_mods['header_image_and_nav_border_color'] ) 
								? $this->theme_mods['header_image_and_nav_border_color'] 
								: (
									! empty( $this->theme_mods['logo_and_nav_border_color'] )
									? $this->theme_mods['logo_and_nav_border_color']
									: '#CCCCCC'
								)
							) . ' 1px;
							border-bottom:solid ' . sanitize_text_field( 
								! empty( $this->theme_mods['header_image_and_nav_border_color'] ) 
								? $this->theme_mods['header_image_and_nav_border_color'] 
								: ( 
									! empty( $this->theme_mods['logo_and_nav_border_color'] )
									? $this->theme_mods['logo_and_nav_border_color']
									: '#CCCCCC'
								)
							) . ' 1px;

							box-shadow: 0 2px 4px ' . sanitize_text_field( 
								! empty( $this->theme_mods['header_image_and_nav_border_color'] ) 
								? $this->theme_mods['header_image_and_nav_border_color'] 
								: ( 
									! empty( $this->theme_mods['logo_and_nav_border_color'] )
									? $this->theme_mods['logo_and_nav_border_color']
									: '#000000'
								)
							) . ';

							' . ( 
								shapeshifter_boolval( $this->theme_mods['nav_background_gradient_on'] )
								? 'background:linear-gradient(' . ( 
									sanitize_text_field( $this->theme_mods['main_content_background_color'] )
									? sanitize_text_field( $this->theme_mods['main_content_background_color'] )
									: ( 
										sanitize_text_field( $this->theme_mods['content_area_background_color'] )
										? sanitize_text_field( $this->theme_mods['content_area_background_color'] )
										: '#FFFFFF'
									)
								) . ',
									' . sanitize_text_field( $this->theme_mods['nav_background_color'] ) . ');
										background: -webkit-gradient(
											linear,
											left top,
											left bottom,
											from(' . ( 
												sanitize_text_field( $this->theme_mods['main_content_background_color'] )
												? sanitize_text_field( $this->theme_mods['main_content_background_color'] )
												: ( 
													sanitize_text_field( $this->theme_mods['content_area_background_color'] )
													? sanitize_text_field( $this->theme_mods['content_area_background_color'] )
													: '#FFFFFF'
												)
											) . '),
											to(' . sanitize_text_field( $this->theme_mods['nav_background_color'] ) . ')
										);
										background: -moz-linear-gradient(
											top,
										' . ( 
												sanitize_text_field( $this->theme_mods['main_content_background_color'] )
												? sanitize_text_field( $this->theme_mods['main_content_background_color'] )
												: ( 
													sanitize_text_field( $this->theme_mods['content_area_background_color'] ) 
													? sanitize_text_field( $this->theme_mods['content_area_background_color'] )
													: '#FFFFFF'
												)
										) . ',
										' . sanitize_text_field( $this->theme_mods['nav_background_color'] ) . '
										);'
								: 'background-color:' . sanitize_text_field( $this->theme_mods['nav_background_color'] ) . ';'
							) . '
							' . ( 
								sanitize_text_field( $this->theme_mods['nav_menu_background_image'] )
								? 'background-image: url(' . esc_url_raw( $this->theme_mods['nav_menu_background_image'] ) . ');'
								: '' 
							) . '
							background-size:auto;
							background-repeat:no-repeat;
						}
						nav.shapeshifter-main-regular-nav div.shapeshifter-main-nav-wrapper-div{
							margin: auto;
							margin-left: 0;
						}

						nav.shapeshifter-main-regular-nav div.shapeshifter-main-nav-div{
												
						}

						div.shapeshifter-main-nav-div > ul.shapeshifter-main-nav-menu{
							margin: auto;
						}
						nav.shapeshifter-main-regular-nav ul.shapeshifter-main-nav-menu > li.menu-item{
							text-align: center;
							float:left;
							padding:10px;
							margin:0;
							' . ( 
								shapeshifter_boolval( $this->theme_mods['nav_items_background_gradient_on'] )
								? 'background:linear-gradient(
									' . ( 
										sanitize_text_field( $this->theme_mods['main_content_background_color'] )
										? sanitize_text_field( $this->theme_mods['main_content_background_color'] )
										: ( 
											sanitize_text_field( $this->theme_mods['content_area_background_color'] ) 
											? sanitize_text_field( $this->theme_mods['content_area_background_color'] )
											: '#FFFFFF'
										)
									) . ',
									' . sanitize_text_field( $this->theme_mods['nav_items_background_color'] ) . '
									);
									background: -webkit-gradient(
										linear,
										left top,
										left bottom,
										from(' . ( 
											sanitize_text_field( $this->theme_mods['main_content_background_color'] )
											? sanitize_text_field( $this->theme_mods['main_content_background_color'] )
											: ( 
												sanitize_text_field( $this->theme_mods['content_area_background_color'] )
												? sanitize_text_field( $this->theme_mods['content_area_background_color'] )
												: '#FFFFFF'
											)
										) . '),
										to(' . sanitize_text_field( $this->theme_mods['nav_items_background_color'] ) . ')
									);
									background: -moz-linear-gradient(
										top,
									' . ( 
											sanitize_text_field( $this->theme_mods['main_content_background_color'] )
											? sanitize_text_field( $this->theme_mods['main_content_background_color'] )
											: ( 
												sanitize_text_field( $this->theme_mods['content_area_background_color'] )
												? sanitize_text_field( $this->theme_mods['content_area_background_color'] )
												: '#FFFFFF'
											)
									) . ',
									' . sanitize_text_field( $this->theme_mods['nav_items_background_color'] ) . '
									);'
								: 'background-color:' . sanitize_text_field( $this->theme_mods['nav_items_background_color'] ) . ';'
							) . '
						}
						nav.shapeshifter-main-regular-nav ul.shapeshifter-main-nav-menu > #nav-menu-search-box{
							display:' . ( 
								shapeshifter_boolval( $this->theme_mods['nav_menu_add_search_box'] )
								? 'block' 
								: 'none' 
							) . ';
						}
						nav.shapeshifter-main-regular-nav .shapeshifter-main-nav-menu > li.menu-item > a {
							padding: 9px 5px;
						}
						nav.shapeshifter-main-regular-nav .shapeshifter-main-nav-menu > li.menu-item:hover > a,
						nav.shapeshifter-main-regular-nav .shapeshifter-main-nav-menu > li.menu-item:hover > a:link,
						nav.shapeshifter-main-regular-nav .shapeshifter-main-nav-menu > li.menu-item:hover > a:visited {
							opacity:1;
							' . ( 
								sanitize_text_field( $this->theme_mods['nav_items_selected_border_color'] ) != '' 
								? 'border-bottom: solid ' . sanitize_text_field( $this->theme_mods['nav_items_selected_border_color'] ) . ' 2px;' 
								: '' 
							) . '
						}

						nav.shapeshifter-main-regular-nav ul.shapeshifter-main-nav-menu li > a:hover {
							opacity: 0.5;
						}

						nav.shapeshifter-main-regular-nav li.menu-item a,
						nav.shapeshifter-main-regular-nav li.menu-item a:link,
						nav.shapeshifter-main-regular-nav li.menu-item a:visited{
							color:' . sanitize_text_field( $this->theme_mods['nav_font_color'] ) . ';
						}
						nav.shapeshifter-main-regular-nav li.menu-item:hover > a,
						nav.shapeshifter-main-regular-nav li.menu-item:hover > a:link,
						nav.shapeshifter-main-regular-nav li.menu-item:hover > a:visited {
							opacity:1;
							' . ( 
								sanitize_text_field( $this->theme_mods['nav_items_selected_border_color'] ) != '' 
								? 'border-bottom: solid ' . sanitize_text_field( $this->theme_mods['nav_items_selected_border_color'] ) . ' 1px;' 
								: '' 
							) . '
						}
						nav.shapeshifter-main-regular-nav li.page_item{
							margin: 0;
						}
					';

					$style .= $this->get_nav_sub_styles( $device, $break_point );

					$style .= $this->get_nav_after_sub_styles( $device, $break_point );

					return $style;

				}
					# Sub Menu
						function get_nav_sub_styles( $device = 'pc', $break_point = 'common' ) {

							$style = '';

							if( $this->theme_mods['is_responsive'] ) {

								$style .= '@media screen and ( min-width: 1024px ) {';

									# Custom
										# Depth 0 Sub
											$style .= $this->get_nav_menu_custom_styles();

								$style .= '}';

								$style .= '@media screen and ( max-width: 1024px ) {';

									$style .= '
										.shapeshifter-main-regular-nav .shapeshifter-nav-menu-item-children-title-description,
										.shapeshifter-main-regular-nav .shapeshifter-nav-menu-item-thumbnail-images {
											display: none;
										}
									';

									$style .= '
										nav.shapeshifter-main-regular-nav ul.shapeshifter-main-nav-menu > li.menu-item > ul.sub-menu {

											display: block;
											height: 0;

											transition: .1s;

											min-width: 250px;
											margin:10px 0;
											margin-left:-15px;
											position:absolute;
											z-index:10;

										}

										nav.shapeshifter-main-regular-nav ul.shapeshifter-main-nav-menu > li.menu-item:hover > ul.sub-menu,
										nav.shapeshifter-main-regular-nav ul.shapeshifter-main-nav-menu > li.menu-item > a:focus + ul.sub-menu {
											overflow: visible;
										}

										nav.shapeshifter-main-regular-nav ul.shapeshifter-main-nav-menu > li.menu-item > ul.sub-menu > li.menu-item {
											text-align: left;
											height:0;

											transition: .2s;

											padding:0px;s
											display:block;
											position:relative;

											border: none;
											float: none;
											' . ( 
												shapeshifter_boolval( $this->theme_mods['nav_items_background_gradient_on'] )
												? 'background:linear-gradient(
													' . ( 
														sanitize_text_field( $this->theme_mods['main_content_background_color'] ) 
														? sanitize_text_field( $this->theme_mods['main_content_background_color'] )
														: ( 
															sanitize_text_field( $this->theme_mods['content_area_background_color'] )
															? sanitize_text_field( $this->theme_mods['content_area_background_color'] )
															: '#FFFFFF'
														)
													) . ',
													' . sanitize_text_field( $this->theme_mods['nav_items_background_color'] ) . '
													);
													background: -webkit-gradient(
														linear,
														left top,
														left bottom,
														from(' . ( 
															sanitize_text_field( $this->theme_mods['main_content_background_color'] ) 
															? sanitize_text_field( $this->theme_mods['main_content_background_color'] ) 
															: ( 
																sanitize_text_field( $this->theme_mods['content_area_background_color'] )
																? sanitize_text_field( $this->theme_mods['content_area_background_color'] )
																: '#FFFFFF'
															)
														) . '),
														to(' . sanitize_text_field( $this->theme_mods['nav_items_background_color'] ) . ')
													);
													background: -moz-linear-gradient(
														top,
													' . ( 
														sanitize_text_field( $this->theme_mods['main_content_background_color'] )
														? sanitize_text_field( $this->theme_mods['main_content_background_color'] )
														: ( 
															sanitize_text_field( $this->theme_mods['content_area_background_color'] ) 
															? sanitize_text_field( $this->theme_mods['content_area_background_color'] )
															: '#FFFFFF'
														)
													) . ',
													' . sanitize_text_field( $this->theme_mods['nav_items_background_color'] ) . '
													);'
												: 'background-color:' . sanitize_text_field( $this->theme_mods['nav_items_background_color'] ) . ';'
											) . '
										}
										nav.shapeshifter-main-regular-nav ul.shapeshifter-main-nav-menu > li.menu-item > ul.sub-menu > li.menu-item > a {
											overflow: hidden;
											text-align: left;
											font-size: 0;

											transition: .2s;

											padding: 0px;
											display:block;
											position:relative;

											border: none;
											float: none;

											' . ( 
												shapeshifter_boolval( $this->theme_mods['nav_items_background_gradient_on'] )
												? 'background:linear-gradient(
													' . ( 
														sanitize_text_field( $this->theme_mods['main_content_background_color'] ) 
														? sanitize_text_field( $this->theme_mods['main_content_background_color'] )
														: ( 
															sanitize_text_field( $this->theme_mods['content_area_background_color'] )
															? sanitize_text_field( $this->theme_mods['content_area_background_color'] )
															: '#FFFFFF'
														)
													) . ',
													' . sanitize_text_field( $this->theme_mods['nav_items_background_color'] ) . '
													);
													background: -webkit-gradient(
														linear,
														left top,
														left bottom,
														from(' . ( 
															sanitize_text_field( $this->theme_mods['main_content_background_color'] ) 
															? sanitize_text_field( $this->theme_mods['main_content_background_color'] )
															: ( 
																sanitize_text_field( $this->theme_mods['content_area_background_color'] )
																? sanitize_text_field( $this->theme_mods['content_area_background_color'] )
																: '#FFFFFF'
															)
														) . '),
														to(' . sanitize_text_field( $this->theme_mods['nav_items_background_color'] ) . ')
													);
													background: -moz-linear-gradient(
														top,
													' . ( 
														sanitize_text_field( $this->theme_mods['main_content_background_color'] )
														? sanitize_text_field( $this->theme_mods['main_content_background_color'] )
														: ( 
															sanitize_text_field( $this->theme_mods['content_area_background_color'] )
															? sanitize_text_field( $this->theme_mods['content_area_background_color'] )
															: '#FFFFFF'
														)
													) . ',
													' . sanitize_text_field( $this->theme_mods['nav_items_background_color'] ) . '
													);'
												: 'background-color:' . sanitize_text_field( $this->theme_mods['nav_items_background_color'] ) . ';'
											) . '
										}
										nav.shapeshifter-main-regular-nav ul.shapeshifter-main-nav-menu > li.menu-item:hover > ul.sub-menu > li.menu-item,
										nav.shapeshifter-main-regular-nav ul.shapeshifter-main-nav-menu > li.menu-item > a:focus + ul.sub-menu > li.menu-item {
											overflow: visible;
											height: 40px;
										}
										nav.shapeshifter-main-regular-nav ul.shapeshifter-main-nav-menu > li.menu-item:hover > ul.sub-menu > li.menu-item > a,
										nav.shapeshifter-main-regular-nav ul.shapeshifter-main-nav-menu > li.menu-item > a:focus + ul.sub-menu > li.menu-item > a,
										nav.shapeshifter-main-regular-nav ul.shapeshifter-main-nav-menu > li.menu-item > ul.sub-menu > li.menu-item > a:focus {
											overflow: visible;
											font-size: 100%;
											padding: 10px 5px;
										}

										nav.shapeshifter-main-regular-nav ul.shapeshifter-main-nav-menu > li.menu-item > ul.sub-menu > li.menu-item > a{
										}
									';

									$style .= '
										nav.shapeshifter-main-regular-nav ul.shapeshifter-main-nav-menu > li.menu-item > ul.sub-menu {

											display: block;
											height: 0;

											transition: .2s;

											min-width: 250px;
											margin:10px 0;
											margin-left:-15px;
											position:absolute;
											z-index:10;

										}

										nav.shapeshifter-main-regular-nav ul.shapeshifter-main-nav-menu > li.menu-item:hover > ul.sub-menu,
										nav.shapeshifter-main-regular-nav ul.shapeshifter-main-nav-menu > li.menu-item > a:focus + ul.sub-menu {
											overflow: visible;
										}

										nav.shapeshifter-main-regular-nav ul.shapeshifter-main-nav-menu > li.menu-item > ul.sub-menu > li.menu-item {
											text-align: left;
											height:0;

											transition: .2s;

											padding:0px;s
											display:block;
											position:relative;

											border: none;
											float: none;
											' . ( 
												shapeshifter_boolval( $this->theme_mods['nav_items_background_gradient_on'] )
												? 'background:linear-gradient(
													' . ( 
														sanitize_text_field( $this->theme_mods['main_content_background_color'] ) 
														? sanitize_text_field( $this->theme_mods['main_content_background_color'] )
														: ( 
															sanitize_text_field( $this->theme_mods['content_area_background_color'] )
															? sanitize_text_field( $this->theme_mods['content_area_background_color'] )
															: '#FFFFFF'
														)
													) . ',
													' . sanitize_text_field( $this->theme_mods['nav_items_background_color'] ) . '
													);
													background: -webkit-gradient(
														linear,
														left top,
														left bottom,
														from(' . ( 
															sanitize_text_field( $this->theme_mods['main_content_background_color'] ) 
															? sanitize_text_field( $this->theme_mods['main_content_background_color'] ) 
															: ( 
																sanitize_text_field( $this->theme_mods['content_area_background_color'] )
																? sanitize_text_field( $this->theme_mods['content_area_background_color'] )
																: '#FFFFFF'
															)
														) . '),
														to(' . sanitize_text_field( $this->theme_mods['nav_items_background_color'] ) . ')
													);
													background: -moz-linear-gradient(
														top,
													' . ( 
														sanitize_text_field( $this->theme_mods['main_content_background_color'] )
														? sanitize_text_field( $this->theme_mods['main_content_background_color'] )
														: ( 
															sanitize_text_field( $this->theme_mods['content_area_background_color'] ) 
															? sanitize_text_field( $this->theme_mods['content_area_background_color'] )
															: '#FFFFFF'
														)
													) . ',
													' . sanitize_text_field( $this->theme_mods['nav_items_background_color'] ) . '
													);'
												: 'background-color:' . sanitize_text_field( $this->theme_mods['nav_items_background_color'] ) . ';'
											) . '
										}
										nav.shapeshifter-main-regular-nav ul.shapeshifter-main-nav-menu > li.menu-item > ul.sub-menu > li.menu-item > a {
											overflow: hidden;
											text-align: left;
											font-size: 0;

											transition: .2s;

											padding: 0px;
											display:block;
											position:relative;

											border: none;
											float: none;

											' . ( 
												shapeshifter_boolval( $this->theme_mods['nav_items_background_gradient_on'] )
												? 'background:linear-gradient(
													' . ( 
														sanitize_text_field( $this->theme_mods['main_content_background_color'] ) 
														? sanitize_text_field( $this->theme_mods['main_content_background_color'] )
														: ( 
															sanitize_text_field( $this->theme_mods['content_area_background_color'] )
															? sanitize_text_field( $this->theme_mods['content_area_background_color'] )
															: '#FFFFFF'
														)
													) . ',
													' . sanitize_text_field( $this->theme_mods['nav_items_background_color'] ) . '
													);
													background: -webkit-gradient(
														linear,
														left top,
														left bottom,
														from(' . ( 
															sanitize_text_field( $this->theme_mods['main_content_background_color'] ) 
															? sanitize_text_field( $this->theme_mods['main_content_background_color'] )
															: ( 
																sanitize_text_field( $this->theme_mods['content_area_background_color'] )
																? sanitize_text_field( $this->theme_mods['content_area_background_color'] )
																: '#FFFFFF'
															)
														) . '),
														to(' . sanitize_text_field( $this->theme_mods['nav_items_background_color'] ) . ')
													);
													background: -moz-linear-gradient(
														top,
													' . ( 
														sanitize_text_field( $this->theme_mods['main_content_background_color'] )
														? sanitize_text_field( $this->theme_mods['main_content_background_color'] )
														: ( 
															sanitize_text_field( $this->theme_mods['content_area_background_color'] )
															? sanitize_text_field( $this->theme_mods['content_area_background_color'] )
															: '#FFFFFF'
														)
													) . ',
													' . sanitize_text_field( $this->theme_mods['nav_items_background_color'] ) . '
													);'
												: 'background-color:' . sanitize_text_field( $this->theme_mods['nav_items_background_color'] ) . ';'
											) . '
										}
										nav.shapeshifter-main-regular-nav ul.shapeshifter-main-nav-menu > li.menu-item:hover > ul.sub-menu > li.menu-item,
										nav.shapeshifter-main-regular-nav ul.shapeshifter-main-nav-menu > li.menu-item > a:focus + ul.sub-menu > li.menu-item {
											overflow: visible;
											height: 40px;
										}
										nav.shapeshifter-main-regular-nav ul.shapeshifter-main-nav-menu > li.menu-item:hover > ul.sub-menu > li.menu-item > a,
										nav.shapeshifter-main-regular-nav ul.shapeshifter-main-nav-menu > li.menu-item > a:focus + ul.sub-menu > li.menu-item > a,
										nav.shapeshifter-main-regular-nav ul.shapeshifter-main-nav-menu > li.menu-item > ul.sub-menu > li.menu-item > a:focus {
											overflow: visible;
											font-size: 100%;
											padding: 10px 5px;
										}

										nav.shapeshifter-main-regular-nav ul.shapeshifter-main-nav-menu > li.menu-item > ul.sub-menu > li.menu-item > a{
										}
									';

								$style .= '}';

							} else {

								$style .= $this->get_nav_menu_custom_styles();

							}

							return $style;

						}
							function get_nav_menu_custom_styles() {

								$style = '';

								$style .= '
									/* Custom */
										ul.shapeshifter-main-nav-menu .shapeshifter-nav-menu-item-inner-wrapper {

											/* transition: .2s; */

											position: absolute;
											overflow: hidden;

											left: 0;
											width: 100%;
											height: 0;

										}
										ul.shapeshifter-main-nav-menu a:hover + .shapeshifter-nav-menu-item-inner-wrapper,
										ul.shapeshifter-main-nav-menu .shapeshifter-nav-menu-item-inner-wrapper:hover {

											overflow: visible;
											height: 250px;

											z-index: 1;

										}
											ul.shapeshifter-main-nav-menu a:hover + .shapeshifter-nav-menu-item-inner-wrapper > .shapeshifter-nav-menu-item-inner,
											ul.shapeshifter-main-nav-menu .shapeshifter-nav-menu-item-inner-wrapper:hover > .shapeshifter-nav-menu-item-inner {

												overflow-y: scroll;

												display: flex;
												flex-wrap: wrap;

												max-width: 960px;
												height: 250px;
												margin: auto;
												padding: 20px 0;

												background-color: ' . sanitize_text_field(
													$this->theme_mods['nav_items_background_color']
													? $this->theme_mods['nav_items_background_color']
													: 'rgba(255,255,255,0.9);'
												) . ';

											}

												ul.shapeshifter-main-nav-menu .shapeshifter-nav-menu-item-inner > .shapeshifter-nav-menu-item-thumbnail-images {

													justify-content: space-around;

												}
												ul.shapeshifter-main-regular-nav .shapeshifter-nav-menu-item-inner > .shapeshifter-nav-menu-item-children-wrapper {

													justify-content: space-around;

												}

													/* Depth 0 */
														/* Title Description */
															ul.shapeshifter-main-nav-menu li > a:hover + .nav-menu-item-inner-wrapper-depth-0 > .shapeshifter-nav-menu-item-inner > .shapeshifter-nav-menu-item-children-title-description,
															ul.shapeshifter-main-nav-menu li > a:focus + .nav-menu-item-inner-wrapper-depth-0 > .shapeshifter-nav-menu-item-inner > .shapeshifter-nav-menu-item-children-title-description,
															ul.shapeshifter-main-nav-menu .nav-menu-item-inner-wrapper-depth-0:hover > .shapeshifter-nav-menu-item-inner > .shapeshifter-nav-menu-item-children-title-description {
																width: 90%;
																margin: auto;
																border-bottom: solid #000 1px;
															}
															
															/* Title */
																ul.shapeshifter-main-nav-menu li > a:hover + .nav-menu-item-inner-wrapper-depth-0 > .shapeshifter-nav-menu-item-inner > .shapeshifter-nav-menu-item-children-title-description > .shapeshifter-nav-menu-item-children-title,
																ul.shapeshifter-main-nav-menu li > a:focus + .nav-menu-item-inner-wrapper-depth-0 > .shapeshifter-nav-menu-item-inner > .shapeshifter-nav-menu-item-children-title-description > .shapeshifter-nav-menu-item-children-title,
																ul.shapeshifter-main-nav-menu .nav-menu-item-inner-wrapper-depth-0:hover > .shapeshifter-nav-menu-item-inner > .shapeshifter-nav-menu-item-children-title-description > .shapeshifter-nav-menu-item-children-title {
																	margin: 10px auto;
																	text-align: center;
																	font-size: 16px;
																}

															/* Description */
																ul.shapeshifter-main-nav-menu li > a:hover + .nav-menu-item-inner-wrapper-depth-0 > .shapeshifter-nav-menu-item-inner > .shapeshifter-nav-menu-item-children-title-description > .shapeshifter-nav-menu-item-children-description,
																ul.shapeshifter-main-nav-menu li > a:focus + .nav-menu-item-inner-wrapper-depth-0 > .shapeshifter-nav-menu-item-inner > .shapeshifter-nav-menu-item-children-title-description > .shapeshifter-nav-menu-item-children-description,
																ul.shapeshifter-main-nav-menu .nav-menu-item-inner-wrapper-depth-0:hover > .shapeshifter-nav-menu-item-inner > .shapeshifter-nav-menu-item-children-title-description > .shapeshifter-nav-menu-item-children-description {
																	width: 70%;
																	margin: 10px auto;
																	font-size: 12px;
																}

														/* Images */
															ul.shapeshifter-main-nav-menu li > a:hover + .nav-menu-item-inner-wrapper-depth-0 > .shapeshifter-nav-menu-item-inner > .shapeshifter-nav-menu-item-thumbnail-images,
															ul.shapeshifter-main-nav-menu li > a:focus + .nav-menu-item-inner-wrapper-depth-0 > .shapeshifter-nav-menu-item-inner > .shapeshifter-nav-menu-item-thumbnail-images,
															ul.shapeshifter-main-nav-menu .nav-menu-item-inner-wrapper-depth-0:hover > .shapeshifter-nav-menu-item-inner > .shapeshifter-nav-menu-item-thumbnail-images {

																width: 300px;
																height: 200px;
																padding: 10px;

															}

														/* Children */
															ul.shapeshifter-main-nav-menu > li > a:hover + .nav-menu-item-inner-wrapper-depth-0 > .shapeshifter-nav-menu-item-inner > .shapeshifter-nav-menu-item-children-wrapper,
															ul.shapeshifter-main-nav-menu > li > a:focus + .nav-menu-item-inner-wrapper-depth-0 > .shapeshifter-nav-menu-item-inner > .shapeshifter-nav-menu-item-children-wrapper,
															ul.shapeshifter-main-nav-menu > li > .nav-menu-item-inner-wrapper-depth-0:hover > .shapeshifter-nav-menu-item-inner > .shapeshifter-nav-menu-item-children-wrapper {

																flex-grow: 3;

																width: 300px;

																padding: 10px;

															}

																ul.shapeshifter-main-nav-menu li > a:hover + .nav-menu-item-inner-wrapper-depth-0 > .shapeshifter-nav-menu-item-inner > .shapeshifter-nav-menu-item-children-wrapper > ul.sub-menu,
																ul.shapeshifter-main-nav-menu li > a:focus + .nav-menu-item-inner-wrapper-depth-0 > .shapeshifter-nav-menu-item-inner > .shapeshifter-nav-menu-item-children-wrapper > ul.sub-menu,
																ul.shapeshifter-main-nav-menu .nav-menu-item-inner-wrapper-depth-0:hover > .shapeshifter-nav-menu-item-inner > .shapeshifter-nav-menu-item-children-wrapper > ul.sub-menu {

																	display: flex;
																	flex-wrap: wrap;
																	min-width: 320px;
																	max-width: 960px;
																	margin: auto;

																}

																	ul.shapeshifter-main-nav-menu li > a:hover + .nav-menu-item-inner-wrapper-depth-0 > .shapeshifter-nav-menu-item-inner > .shapeshifter-nav-menu-item-children-wrapper > ul.sub-menu > li.menu-item,
																	ul.shapeshifter-main-nav-menu li > a:focus + .nav-menu-item-inner-wrapper-depth-0 > .shapeshifter-nav-menu-item-inner > .shapeshifter-nav-menu-item-children-wrapper > ul.sub-menu > li.menu-item,
																	ul.shapeshifter-main-nav-menu .nav-menu-item-inner-wrapper-depth-0:hover > .shapeshifter-nav-menu-item-inner > .shapeshifter-nav-menu-item-children-wrapper > ul.sub-menu > li.menu-item {

																		justify-content: space-around;
																		width: 300px;
																		padding: 10px;
																		text-align: center;

																	}

																		ul.shapeshifter-main-nav-menu li > a:hover + .nav-menu-item-inner-wrapper-depth-0 > .shapeshifter-nav-menu-item-inner > .shapeshifter-nav-menu-item-children-wrapper > ul.sub-menu > li.menu-item > a,
																		ul.shapeshifter-main-nav-menu li > a:focus + .nav-menu-item-inner-wrapper-depth-0 > .shapeshifter-nav-menu-item-inner > .shapeshifter-nav-menu-item-children-wrapper > ul.sub-menu > li.menu-item > a,
																		ul.shapeshifter-main-nav-menu .nav-menu-item-inner-wrapper-depth-0:hover > .shapeshifter-nav-menu-item-inner > .shapeshifter-nav-menu-item-children-wrapper > ul.sub-menu > li.menu-item > a {

																			font-size: 14px;
																			margin: 5px 0;

																		}

													/* Depth 1 */
														.nav-menu-item-inner-wrapper-depth-0 > .shapeshifter-nav-menu-item-inner > .shapeshifter-nav-menu-item-children-wrapper > ul.sub-menu > li.menu-item > a + ul.sub-menu > li.menu-item {
															font-size: 10px;
														}

								';

								return $style;

							}

					# After Sub Menu
						function get_nav_after_sub_styles( $device = 'pc', $break_point = 'common' ) {

							$style = '';

							return $style;

						}

			# Archive Page
				function get_common_archive_page_styles( $device = 'pc', $break_point = 'common' ) {

					$style = '';

					$style .= '
						.post-list-read-later .post-list-read-later-sns-share-icons {
							overflow: visible;
						}
					';

					return $style;

				}

			# Content Items
				function get_common_content_items_styles( $device = 'pc', $break_point = 'common' ) {

					$style = '';

					$style .= '
						.shapeshifter .sp-next-arrow:after,
						.shapeshifter .sp-next-arrow:before,
						.shapeshifter .sp-previous-arrow:after,
						.shapeshifter .sp-previous-arrow:before {
							background-color: #CCCCCC;
						}
					';

					# Table
						$style .= '
							.entry-content .shapeshifter-table-wrapper {
								width: 100%;
								margin: auto;
								overflow-x: scroll;
							}
							.entry-content .shapeshifter-table-wrapper .shapeshifter-table {
								width: ' . absint( $this->content_inner_width -22 ) . 'px;
								margin: auto;
								border: solid #000 1px;
								padding: 5px;
							}
							.entry-content .shapeshifter-table-wrapper .shapeshifter-table caption {
								font-weight: bold;
								font-size: 14px;
								padding: 5px 10px;
							}
							.entry-content .shapeshifter-table-wrapper table thead th {
								text-align: center;
							}
						';

					# Balloon
						$style .= '
							/* Wrapper */
								.entry-content .shapeshifter-balloon-wrapper {
									display: flex;
									width: 100%;
									margin: 0;
								}

								/* Image */
									.entry-content .shapeshifter-balloon-wrapper .shapeshifter-balloon-image-wrapper {

									}
									.entry-content .shapeshifter-balloon-wrapper.align-left .shapeshifter-balloon-image-wrapper {
										margin-left: 10px;
										order: 1;
									}

									.entry-content .shapeshifter-balloon-wrapper.align-right .shapeshifter-balloon-image-wrapper {
										order: 2;
									}

									.entry-content img.shapeshifter-balloon-image {
										width: 100px;
										height: 100px;
										border-radius: 50px;
									}
									.entry-content .shadow-left-bottom {
										box-shadow: -2px 2px 4px #000;
									}
									.entry-content .shadow-right-bottom {
										box-shadow: 2px 2px 4px #000;
									}
										/* Image Figure */
											.entry-content .shapeshifter-balloon-image-wrapper .shapeshifter-balloon-image-figure {
												width: 100px;
											}

										/* Image Name */
											.entry-content .shapeshifter-balloon-image-figure .shapeshifter-balloon-image-caption {
												padding: 10px 0;
												width: 100px;

												text-align: center;
											}

											.entry-content .shapeshifter-balloon-image-figure p {
												padding: 0;
												margin: 0;
											}

								/* Dialog */
									.entry-content div.shapeshifter-balloon-dialog {
										flex-grow: 1;
										position: relative;
										margin: auto 10px;
										padding: 15px;

										border: solid #EEEEEE 2px;
										border-radius: 15px;
									}

										.entry-content .shapeshifter-balloon-wrapper.align-left div.shapeshifter-balloon-dialog {
											order: 2;
										}

											.entry-content .shapeshifter-balloon-wrapper.align-left div.shapeshifter-balloon-dialog:before {
												content: "";
												position: absolute;
												border-right: 8px solid #EEEEEE;
												border-bottom: 8px solid transparent;
												border-top: 8px solid transparent;
												top: 10px;
												left: -9px;
											}

										.entry-content .shapeshifter-balloon-wrapper.align-right div.shapeshifter-balloon-dialog {
											order: 1;
										}

											.entry-content .shapeshifter-balloon-wrapper.align-right div.shapeshifter-balloon-dialog:after {
												content: "";
												position: absolute;
												border-left: 8px solid #EEEEEE;
												border-bottom: 8px solid transparent;
												border-top: 8px solid transparent;
												top: 10px;
												right: -9px;
											}

									.entry-content div.shapeshifter-balloon-dialog:after {

									}
						';

					return $style;

				}

			# Shortcodes
				function get_common_shortcode_items_styles( $device = 'pc', $break_point = 'common' ) {

					$styles = '';

					# New Entries
						$styles .= '
							.shapeshifter-entries-slider-wrapper {
								visibility: hidden;
							}
								.shapeshifter-entries-slider-wrapper .shapeshifter-entries-slides {

								}
									.shapeshifter-entries-slider-wrapper .shapeshifter-entries-slides .shapeshifter-entries-wrapper {

									}
										.shapeshifter-entries-slider-wrapper .shapeshifter-entries-slides .shapeshifter-entries-wrapper .shapeshifter-entries-thumbnail-wrapper {
											text-align: center;
										}
											.shapeshifter-entries-slider-wrapper .shapeshifter-entries-slides .shapeshifter-entries-wrapper .shapeshifter-entries-thumbnail-wrapper .shapeshifter-entries-thumbnail-img {
												width: 200px;
												height: 150px;
											}
										.shapeshifter-entries-slider-wrapper .shapeshifter-entries-slides .shapeshifter-entries-wrapper .shapeshifter-entries-title-wrapper {
											text-align: center;
										}
											.shapeshifter-entries-slider-wrapper .shapeshifter-entries-slides .shapeshifter-entries-wrapper .shapeshifter-entries-title-wrapper .shapeshifter-entries-title {
												text-align: center;
											}
												.shapeshifter-entries-slider-wrapper .shapeshifter-entries-slides .shapeshifter-entries-wrapper .shapeshifter-entries-title-wrapper .shapeshifter-entries-title .shapeshifter-entries-title-a {
													color: #000;
													font-weight: bold;
												}
										.shapeshifter-entries-slider-wrapper .shapeshifter-entries-slides .shapeshifter-entries-wrapper .shapeshifter-entries-description-wrapper {
										}
											.shapeshifter-entries-slider-wrapper .shapeshifter-entries-slides .shapeshifter-entries-wrapper .shapeshifter-entries-description-wrapper .shapeshifter-entries-description {
												font-size: 8px;
											}
						';

					# Search Entries
						$styles .= '
							.shapeshifter-search-entries-slider-wrapper {

							}
						';
					# Slider 
						$styles .= '
						.entry-content .sp-next-arrow:after,
						.entry-content .sp-next-arrow:before,
						.entry-content .sp-previous-arrow:after,
						.entry-content .sp-previous-arrow:before {
							background-color: #CCCCCC;
						}
					';

					return $styles;

				}

			# Widget Areas
				function get_common_widget_areas_styles( $device = 'pc', $break_point = 'common' ) {

					$style = '';

					// Top Right
						$style .= $this->get_common_widget_top_right_styles( $device, $break_point );

					// Optional Widget Areas Wrapper
						$style .= $this->get_optional_widget_areas_wrapper_styles( $device, $break_point );

					// Optional Areas
						$style .= $this->get_optional_widget_areas_styles( $device, $break_point );

					// End
						return $style;

				}

					// Top Right
					function get_common_widget_top_right_styles( $device = 'pc', $break_point = 'common' ) {

						$style = '
							.widget-area.top-right {
								
								position:' . ( 
									sanitize_text_field( $this->theme_mods['is_widget_area_top_right_fixed'] ) != ''
									? 'fixed' 
									: 'absolute' 
								) . ';
								top:' . intval( $this->theme_mods['top_right_fixed_area_top'] ) . 'px;
								right:' . intval( $this->theme_mods['top_right_fixed_area_side'] ) . 'px;
							}

						';

						return $style;

					}

					// Optional Widget Areas Wrapper
					function get_optional_widget_areas_wrapper_styles( $device = 'pc', $break_point = 'common' ) {

						$style = '';

							$style .= '
								.shapeshifter-is-responsive #widget-area-in-footer-wrapper {
									color: #000;
								}
								.shapeshifter-is-responsive #widget-area-in-footer-wrapper a,
								.shapeshifter-is-responsive #widget-area-in-footer-wrapper a:link,
								.shapeshifter-is-responsive #widget-area-in-footer-wrapper a:visited {
									color: #000;					
								}
								.shapeshifter-is-responsive #widget-area-in-footer-wrapper div {
									overflow: auto;
								}
							';

						// Optional Areas Wrapper
							$optional_widget_areas_wrapper_args = array(
								'mobile_sidebar' => array(
									'wrapper' => '#shapeshifter-mobile-side-menu-aside',
									'area'    => '.widget-area-mobile-side-menu',
									'key'     => 'mobile-side-menu',
								),
								'after_header' => array(
									'wrapper' => '#optional-widget-area-wrapper-after-header',
									'area'    => '.widget-area-after-header',
									'key'     => 'after-header',
								),
								'before_content_area' => array(
									'wrapper' => '#optional-widget-area-wrapper-before-content-area',
									'area'    => '.widget-area-before-content-area',
									'key'     => 'before-content-area',
								),
								'before_content' => array(
									'wrapper' => '#optional-widget-area-wrapper-before-content',
									'area'    => '.widget-area-before-content',
									'key'     => 'before-content',
								),
								'beginning_of_content' => array(
									//'wrapper' => '.beginning-of-content-wrapper',
									'wrapper' => '#optional-widget-area-wrapper-beginning-of-content',
									'area'    => '.widget-area-beginning-of-content',
									'key'     => 'beginning-of-content',
								),
								'before_1st_h2_of_content' => array(
									'wrapper' => '#optional-widget-area-wrapper-before-1st-h2-of-content',
									'area'    => '.widget-area-before-1st-h2-of-content',
									'key'     => 'before-1st-h2-of-content',
								),
								'end_of_content' => array(
									'wrapper' => '#optional-widget-area-wrapper-end-of-content',
									'area'    => '.widget-area-end-of-content',
									'key'     => 'end-of-content',
								),
								'after_content' => array(
									'wrapper' => '#optional-widget-area-wrapper-after-content',
									'area'    => '.widget-area-after-content',
									'key'     => 'after-content',
								),
								'before_footer' => array(
									//'wrapper' => '.before-footer-div',
									'wrapper' => '#optional-widget-area-wrapper-before-footer',
									'area'    => '.widget-area-before-footer',
									'key'     => 'before-footer',
								),
								'in_footer' => array(
									'wrapper' => '#optional-widget-area-wrapper-in-footer',
									'area'    => '.widget-area-in-footer',
									'key'     => 'in-footer',
								),
							);

							foreach( $optional_widget_areas_wrapper_args as $hook => $data ) {

								$selector_wrapper = $data['wrapper'];
								$selector_area = $data['area'];
								$selector_key = $data['key'];

								$style .= '
									' . $selector_wrapper . ' {

										width: ' . ( $hook === 'mobile_sidebar' ? '300px' : '100%' ) . ';

										background-color: ' . sanitize_text_field( 
											( in_array( $hook, array( 'after_header', 'before_footer', 'in_footer' ) )
												&& $this->theme_mods[ $hook . '_wrapper_background_color'] === 'rgba(255,255,255,0)'
											)
											? 'rgba(255,255,255,0.8)'
											: $this->theme_mods[ $hook . '_wrapper_background_color']
										) . ';

										' . $this->get_background_image_style( $this->theme_mods[ $hook . '_wrapper_background_image'] ) . '
										' . $this->get_background_size_style( $this->theme_mods[ $hook . '_wrapper_background_image_size'] ) . '
										' . $this->get_background_position_y_style( $this->theme_mods[ $hook . '_wrapper_background_image_position_row'] ) . '
										' . $this->get_background_position_x_style( $this->theme_mods[ $hook . '_wrapper_background_image_position_column'] ) . '
										' . $this->get_background_repeat_style( $this->theme_mods[ $hook . '_wrapper_background_image_repeat'] ) . '
										' . $this->get_background_attachment_style( $this->theme_mods[ $hook . '_wrapper_background_image_attachment'] ) . '

									}
								';
							} unset( $optional_widget_areas_wrapper_args );

						return $style;

					}

					// Optional Widget Areas
					function get_optional_widget_areas_styles( $device = 'pc', $break_point = 'common' ) {

						$style = '';

						$index = 0;
						if ( is_array( $this->optional_widget_areas_args ) ) { foreach( $this->optional_widget_areas_args as $id => $widget_area_data ) {

							if ( ! isset( $widget_area_data['hook'] ) ) continue; 

							$selector_key = sse()->get_widget_area_manager()->get_wa_key( $widget_area_data['hook'] );

							// DIV
							$widget_area_class = '#' . $widget_area_data['id'];
							// UL
							$widget_area_ul_class = '#widget-list-' . $widget_area_data['id'];
							// LI
							$widget_class = $widget_area_class . ' .widget-li';
							// DIV
							$widget_inner_class = $widget_area_class . ' .widget';
							// P
							$widget_title_class = $widget_area_class . ' .widget-title';

							$prefix_hook_theme_mods = $widget_area_data['hook'] . '_' . $index;

							$style .= '
								' . $widget_area_class . ' {

									' . $this->get_background_color_style( $this->theme_mods[ $prefix_hook_theme_mods . '_area_background_color'] ) . '
									' . $this->get_background_image_style( $this->theme_mods[ $prefix_hook_theme_mods . '_area_background_image'] ) . '
									' . $this->get_background_size_style( $this->theme_mods[ $prefix_hook_theme_mods . '_area_background_image_size'] ) . '
									' . $this->get_background_position_y_style( $this->theme_mods[ $prefix_hook_theme_mods . '_area_background_image_position_row'] ) . '
									' . $this->get_background_position_x_style( $this->theme_mods[ $prefix_hook_theme_mods . '_area_background_image_position_column'] ) . '
									' . $this->get_background_repeat_style( $this->theme_mods[ $prefix_hook_theme_mods . '_area_background_image_repeat'] ) . '
									' . $this->get_background_attachment_style( $this->theme_mods[ $prefix_hook_theme_mods . '_area_background_image_attachment'] ) . '

									padding: ' . intval( $this->theme_mods[ $prefix_hook_theme_mods . '_area_padding'] ) . 'px;

									' . $this->get_font_family_style( $this->theme_mods[ $prefix_hook_theme_mods . '_font_family'] ) . '
								}
								
								' . $widget_class . ' {

									' . $this->get_background_color_style( $this->theme_mods[ $prefix_hook_theme_mods . '_outer_background_color'] ) . '
									' . $this->get_background_image_style( $this->theme_mods[ $prefix_hook_theme_mods . '_outer_background_image'] ) . '
									' . $this->get_background_size_style( $this->theme_mods[ $prefix_hook_theme_mods . '_outer_background_image_size'] ) . '
									' . $this->get_background_position_y_style( $this->theme_mods[ $prefix_hook_theme_mods . '_outer_background_image_position_row'] ) . '
									' . $this->get_background_position_x_style( $this->theme_mods[ $prefix_hook_theme_mods . '_outer_background_image_position_column'] ) . '
									' . $this->get_background_repeat_style( $this->theme_mods[ $prefix_hook_theme_mods . '_outer_background_image_repeat'] ) . '
									' . $this->get_background_attachment_style( $this->theme_mods[ $prefix_hook_theme_mods . '_outer_background_image_attachment'] ) . '

									box-shadow: ' . ( shapeshifter_boolval( $this->theme_mods[ $prefix_hook_theme_mods . '_widget_border'] ) ? '0 0 5px' : 'none' ) . ';
									border-radius: ' . absint( $this->theme_mods[ $prefix_hook_theme_mods . '_widget_border_radius'] ) . 'px;

									padding: ' . intval( $this->theme_mods[ $prefix_hook_theme_mods . '_widget_inner_padding'] ) . 'px;
								}

								' . $widget_inner_class . ' {
									overflow: auto;

									' . $this->get_background_color_style( $this->theme_mods[ $prefix_hook_theme_mods . '_inner_background_color'] ) . '
									' . $this->get_background_image_style( $this->theme_mods[ $prefix_hook_theme_mods . '_inner_background_image'] ) . '
									' . $this->get_background_size_style( $this->theme_mods[ $prefix_hook_theme_mods . '_inner_background_image_size'] ) . '
									' . $this->get_background_position_y_style( $this->theme_mods[ $prefix_hook_theme_mods . '_inner_background_image_position_row'] ) . '
									' . $this->get_background_position_x_style( $this->theme_mods[ $prefix_hook_theme_mods . '_inner_background_image_position_column'] ) . '
									' . $this->get_background_repeat_style( $this->theme_mods[ $prefix_hook_theme_mods . '_inner_background_image_repeat'] ) . '
									' . $this->get_background_attachment_style( $this->theme_mods[ $prefix_hook_theme_mods . '_inner_background_image_attachment'] ) . '

								}

								' . $widget_title_class . ':before {
									font-family: FontAwesome;
									content: "' . (
										sanitize_text_field( $this->theme_mods[ $prefix_hook_theme_mods . '_widget_title_fontawesome_icon_select'] ) != 'none' 
										? '\\' . sanitize_text_field( $this->theme_mods[ $prefix_hook_theme_mods . '_widget_title_fontawesome_icon_select'] )
										: ''
									) . '";
									margin-right: 10px;
									' . $this->get_color_style( $this->theme_mods[ $prefix_hook_theme_mods . '_widget_title_fontawesome_icon_color'] ) . '
								}

								' . $widget_title_class . ' {
									' . $this->get_color_style( $this->theme_mods[ $prefix_hook_theme_mods . '_title_color'] ) . '
								}

								' . $widget_inner_class . ' li.cat-item:before,
								' . $widget_inner_class . ' li.archive-list-item:before,
								' . $widget_inner_class . ' ul.menu li.menu-item:before,
								' . $widget_inner_class . ' li.page_item:before,
								' . $widget_inner_class . ' li.recentcomments:before {
									font-family: FontAwesome;
									content: "' . (
										sanitize_text_field( $this->theme_mods[ $prefix_hook_theme_mods . '_widget_list_fontawesome_icon_select'] ) != 'none'
										? '\\' . sanitize_text_field( $this->theme_mods[ $prefix_hook_theme_mods . '_widget_list_fontawesome_icon_select'] )
										: ''
									) . '";
									margin-right: 10px;
									' . $this->get_color_style( $this->theme_mods[ $prefix_hook_theme_mods . '_widget_list_fontawesome_icon_color'] ) . '
								}
								
								' . $widget_inner_class . ' > div {
									' . $this->get_color_style( $this->theme_mods[ $prefix_hook_theme_mods . '_text_color'] ) . '
								}

								' . $widget_inner_class . ' > div a,
								' . $widget_inner_class . ' > div a:link,
								' . $widget_inner_class . ' > div a:visited {
									' . $this->get_color_style( $this->theme_mods[ $prefix_hook_theme_mods . '_link_text_color'] ) . '
								}
							';

							$index++;

						} }

						return $style;

					}

			# Widgets
				function get_common_widget_styles( $device = 'pc', $break_point = 'common' ) {

					$style = '';

					$style .= $this->get_common_widget_entry_style( $device, $break_point );

					return $style;

				}
					# List Item
						function get_common_widget_entry_style( $device = 'pc', $break_point = 'common' ) {

							$style = '';

							return $style;

						}

			# Skins
				function modify_customized_styles( $device = 'pc', $break_point = 'common' ) {

					$theme_skin = get_theme_mod( 'theme_skin' );

					$styles = '';

					if( $device === 'pc' ) {

						# Skins
							# Pawer
								if( $theme_skin === 'pawer' ) {
									$styles .= $this->get_common_skin_pawer_styles( $device, $break_point );
								} 
							# Bella
								else if( $theme_skin === 'bella' ) {
									$styles .= $this->get_common_skin_bella_styles( $device, $break_point );
								}

					} elseif( $device === 'mobile' ) {

						# Skins
							# Pawer
								//$styles .= $this->get_common_skin_pawer_styles( $device, $break_point );

					}

					// Testing
						$styles .= $this->get_common_skin_testing_styles( $device, $break_point );

					return $styles;

				}
					# Pawer
						function get_common_skin_pawer_styles( $device = 'pc', $break_point = 'common' ) {

							$style = '';

							# Content Area
								$style .= '
									body.shapeshifter-skin-pawer .content-area {
										border-top: solid #eee 1px;
										border-bottom: solid #eee 1px;
									}
									body.shapeshifter-skin-pawer .content-inner {
										border-left: solid #eee 1px;
										border-right: solid #eee 1px;
									}
								';

							# Main Content
								$style .= '
									/* Title */
										body.shapeshifter-skin-pawer .shapeshifter-singular-title-wrapper h1.entry-title {
											position: relative;
										}
										body.shapeshifter-skin-pawer .shapeshifter-singular-title-wrapper h1.entry-title:before,
										body.shapeshifter-skin-pawer .shapeshifter-singular-title-wrapper h2.entry-title:before {

											font-family: FontAwesome;
											font-size: 18px;

											text-shadow: none;
											text-align: center;
											padding-top: 6px;

											margin-right: 15px;

											background-color: #FFF;
											width: 36px;
											height: 36px;
											border-radius: 18px;
											border: solid #eee 3px;

											position: absolute;
											left: -39px;
											top: -2px;

											z-index: 1;
										}
										body.shapeshifter-skin-pawer.one-column .shapeshifter-singular-title-wrapper h2.entry-title:before {
											content: none;
										}
										
										body.shapeshifter-skin-pawer.one-column-content-area-width-max #main-content .shapeshifter-singular-title {
											margin-left: 39px;
										}


									/* H2 */
										body.shapeshifter-skin-pawer .entry-content h2:before {

										}
										body.shapeshifter-skin-pawer .entry-content h2:after {
											
										}

									/* H3 */
										body.shapeshifter-skin-pawer .entry-content h3:before {

										}
										body.shapeshifter-skin-pawer .entry-content h3:after {
											
										}

									/* H4 */
										body.shapeshifter-skin-pawer .entry-content h4:before {

										}
										body.shapeshifter-skin-pawer .entry-content h4:after {
											
										}

									/* H5 */
										body.shapeshifter-skin-pawer .entry-content h5:before {

										}
										body.shapeshifter-skin-pawer .entry-content h5:after {
											
										}

									/* H6 */
										body.shapeshifter-skin-pawer .entry-content h6:before {

										}
										body.shapeshifter-skin-pawer .entry-content h6:after {
											
										}

								';

							# Sidebar Left
								$style .= '
									body.shapeshifter-skin-pawer p.widget-area-sidebar-left-p,
									body.shapeshifter-skin-pawer p.widget-area-sidebar-left-fixed-p {
										position: relative;
									}

									body.shapeshifter-skin-pawer p.widget-area-sidebar-left-p:before,
									body.shapeshifter-skin-pawer p.widget-area-sidebar-left-fixed-p:before {

										font-family: FontAwesome;
										font-size: 14px;

										text-align: center;
										position: absolute;
										padding-top: 1px;
										right: -35px;
										text-shadow: none;

										background-color: #ffffff;
										width: 28px;
										height: 28px;
										border-radius: 14px;
										border: solid #eee 3px;
										z-index: 1;
									}
								';

							# Sidebar Right
								$style .= '
									body.shapeshifter-skin-pawer p.widget-area-sidebar-right-p,
									body.shapeshifter-skin-pawer p.widget-area-sidebar-right-fixed-p {
										position: relative;
									}

									body.shapeshifter-skin-pawer p.widget-area-sidebar-right-p:before,
									body.shapeshifter-skin-pawer p.widget-area-sidebar-right-fixed-p:before {

										font-family: FontAwesome;
										font-size: 14px;

										text-align: center;
										position: absolute;
										padding-top: 1px;
										left: -35px;
										text-shadow: none;

										background-color: #ffffff;
										width: 28px;
										height: 28px;
										border-radius: 14px;
										border: solid #eee 3px;
									}
								';

							return $style;

						}

					# Bella
						function get_common_skin_bella_styles( $device = 'pc', $break_point = 'common' ) {

							$style = '';

							# Content Area
								$style .= '
								';

							return $style;

						}

					# Testing
						function get_common_skin_testing_styles( $device = 'pc', $break_point = 'common' ) {

							$style = '';

							# Content Area
								$style .= '
									
								';

							return $style;

						}

	# Mobile
		function get_common_mobile_styles( $device = 'mobile', $break_point = 'common' ) {

			# Logo Background
				$style .= '
					#logo-image-wrapper{ 

					' . $this->get_background_color_style( $this->theme_mods['header_image_background_color'] ) . '
					' . $this->get_background_image_style( $this->theme_mods['header_image_background_image'] ) . '
					' . $this->get_background_size_style( $this->theme_mods['header_image_background_image_size'] ) . '
					' . $this->get_background_position_y_style( $this->theme_mods['header_image_background_image_position_row'] ) . '
					' . $this->get_background_position_x_style( $this->theme_mods['header_image_background_image_position_column'] ) . '
					' . $this->get_background_repeat_style( $this->theme_mods['header_image_background_image_repeat'] ) . '
					' . $this->get_background_attachment_style( $this->theme_mods['header_image_background_image_attachment'] ) . '

					}
				';

			# Logo
				$style .= '
					#logo-image-wrapper-div{
						
						' . $this->get_background_color_style( $this->theme_mods['header_image_background_color'] ) . '
						' . $this->get_background_image_style( $this->theme_mods['header_image_url'] ) . '
						
						max-width: ' . absint( $this->theme_mods['header_image_size_width'] ) . 'px;
						
						' . sanitize_text_field( $this->get_theme_mods_logo_position( $this->theme_mods ) ) . '

					}

					#logo-title-description-p{
						padding:' . intval( $this->theme_mods['header_image_title_description_padding'] ) . 'px;

						' . sanitize_text_field( $this->get_theme_mods_logo_title_description_position_for_mobile( $this->theme_mods ) ) . '

					}
					#logo-title-span {
						' . $this->get_font_family_style( $this->theme_mods['header_image_title_font_family'] ) . '
						display:' . ( shapeshifter_boolval( $this->theme_mods['header_image_title_display_toggle'] ) ? 'block' : 'none' ) . ';

						font-size:' . ( absint( $this->theme_mods['header_image_title_font_size'] ) / 3 ) . 'px;
						' . $this->get_color_style( $this->theme_mods['header_image_title_color'] ) . '
					}
					#logo-description-span {
						' . $this->get_font_family_style( $this->theme_mods['header_image_description_font_family'] ) . '
						display:' . ( $this->theme_mods['header_image_description_display_toggle'] ? 'block' : 'none' ) . ';

						font-size:' . ( $this->theme_mods['header_image_description_font_size'] / 3 ) . 'px;
						' . $this->get_color_style( $this->theme_mods['header_image_description_color'] ) . '
					}
				';

			# Content Items

			# Row
				$style .= '
					.shapeshifter-row .shapeshifter-col {
						width: 100% !important;
					}
				';

			# Table
				$style .= '
					/* Tables
					-------------------------------------------------------------- */
						.entry-content .shapeshifter-table-wrapper {
							width: 100%;
							margin: auto;
							overflow-x: scroll;
						}
						.entry-content .shapeshifter-table-wrapper .shapeshifter-table {
							width: ' . ( $this->content_inner_width -22 ) . 'px;
							margin: auto;
							border: solid #000 1px;
							padding: 5px;
						}
						.entry-content .shapeshifter-table-wrapper .shapeshifter-table caption {
							font-weight: bold;
							font-size: 14px;
							padding: 5px 10px;
						}
						.entry-content .shapeshifter-table-wrapper table thead th {
							text-align: center;
						}
				';

			# Balloon
				$style .= '
					/* Balloon
					-------------------------------------------------------------- */
						.entry-content .shapeshifter-balloon-wrapper {
							display: flex;
							width: 100%;
							margin: 0;
						}

						/* Image
						-------------------------------------------------------------- */
							.entry-content .shapeshifter-balloon-wrapper .shapeshifter-balloon-image-wrapper {

							}
							.entry-content .shapeshifter-balloon-wrapper.align-left .shapeshifter-balloon-image-wrapper {
								margin-left: 10px;
								order: 1;
							}

							.entry-content .shapeshifter-balloon-wrapper.align-right .shapeshifter-balloon-image-wrapper {
								order: 2;
							}

							.entry-content img.shapeshifter-balloon-image {
								width: 100px;
								height: 100px;
								border-radius: 50px;
							}
							.entry-content .shadow-left-bottom {
								box-shadow: -2px 2px 4px #000;
							}
							.entry-content .shadow-right-bottom {
								box-shadow: 2px 2px 4px #000;
							}

								/* Image Figure */
									.entry-content .shapeshifter-balloon-image-wrapper .shapeshifter-balloon-image-figure {
										width: 100px;
									}

								/* Image Name */
									.entry-content .shapeshifter-balloon-image-figure .shapeshifter-balloon-image-caption {
										padding: 10px 0;
										width: 100px;

										text-align: center;
									}

									.entry-content .shapeshifter-balloon-image-figure p {
										padding: 0;
										margin: 0;
									}

						/* Dialog
						-------------------------------------------------------------- */
							.entry-content div.shapeshifter-balloon-dialog {
								flex-grow: 1;
								position: relative;
								margin: auto 10px;
								padding: 15px;

								border: solid #EEEEEE 2px;
								border-radius: 15px;
							}

								.entry-content .shapeshifter-balloon-wrapper.align-left div.shapeshifter-balloon-dialog {
									order: 2;
								}

									.entry-content .shapeshifter-balloon-wrapper.align-left div.shapeshifter-balloon-dialog:before {
										content: "";
										position: absolute;
										border-right: 8px solid #EEEEEE;
										border-bottom: 8px solid transparent;
										border-top: 8px solid transparent;
										top: 10px;
										left: -9px;
									}

								.entry-content .shapeshifter-balloon-wrapper.align-right div.shapeshifter-balloon-dialog {
									order: 1;
								}

									.entry-content .shapeshifter-balloon-wrapper.align-right div.shapeshifter-balloon-dialog:after {
										content: "";
										position: absolute;
										border-left: 8px solid #EEEEEE;
										border-bottom: 8px solid transparent;
										border-top: 8px solid transparent;
										top: 10px;
										right: -9px;
									}

							.entry-content div.shapeshifter-balloon-dialog:after {

							}
				';

			# Optional Widget Areas
				$style .= '
				';

				$optional_widget_areas_wrapper_args = array(
					'mobile_sidebar' => array(
						'wrapper' => '#shapeshifter-mobile-side-menu-aside',
						'area'    => '.widget-area-mobile-side-menu',
						'key'     => 'mobile-side-menu',
					),
					'after_header' => array(
						'wrapper' => '#optional-widget-area-wrapper-after-header',
						'area'    => '.widget-area-after-header',
						'key'     => 'after-header',
					),
					'before_content_area' => array(
						'wrapper' => '#optional-widget-area-wrapper-before-content-area',
						'area'    => '.widget-area-before-content-area',
						'key'     => 'before-content-area',
					),
					'before_content' => array(
						'wrapper' => '#optional-widget-area-wrapper-before-content',
						'area'    => '.widget-area-before-content',
						'key'     => 'before-content',
					),
					'beginning_of_content' => array(
						//'wrapper' => '.beginning-of-content-wrapper',
						'wrapper' => '#optional-widget-area-wrapper-beginning-of-content',
						'area'    => '.widget-area-beginning-of-content',
						'key'     => 'beginning-of-content',
					),
					'before_1st_h2_of_content' => array(
						'wrapper' => '#optional-widget-area-wrapper-before-1st-h2-of-content',
						'area'    => '.widget-area-before-1st-h2-of-content',
						'key'     => 'before-1st-h2-of-content',
					),
					'end_of_content' => array(
						'wrapper' => '#optional-widget-area-wrapper-end-of-content',
						'area'    => '.widget-area-end-of-content',
						'key'     => 'end-of-content',
					),
					'after_content' => array(
						'wrapper' => '#optional-widget-area-wrapper-after-content',
						'area'    => '.widget-area-after-content',
						'key'     => 'after-content',
					),
					'before_footer' => array(
						//'wrapper' => '.before-footer-div',
						'wrapper' => '#optional-widget-area-wrapper-before-footer',
						'area'    => '.widget-area-before-footer',
						'key'     => 'before-footer',
					),
					'in_footer' => array(
						'wrapper' => '#optional-widget-area-wrapper-in-footer',
						'area'    => '.widget-area-in-footer',
						'key'     => 'in-footer',
					),
				);

				foreach( $optional_widget_areas_wrapper_args as $hook => $data ) {

					$selector_wrapper = $data['wrapper'];
					$selector_area = $data['area'];
					$selector_key = $data['key'];

					$style .= '
						' . $selector_wrapper . ' {

							width: ' . ( $hook === 'mobile_sidebar' ? '300px' : '100%' ) . ';

							background-color: ' . sanitize_text_field( 
								( in_array( $hook, array( 'after_header', 'before_footer', 'in_footer' ) )
									&& $this->theme_mods[ $hook . '_wrapper_background_color'] === 'rgba(255,255,255,0)'
								)
								? 'rgba(255,255,255,0.8)'
								: $this->theme_mods[ $hook . '_wrapper_background_color']
							) . ';

							' . $this->get_background_image_style( $this->theme_mods[ $hook . '_wrapper_background_image'] ) . '
							' . $this->get_background_size_style( $this->theme_mods[ $hook . '_wrapper_background_image_size'] ) . '
							' . $this->get_background_position_y_style( $this->theme_mods[ $hook . '_wrapper_background_image_position_row'] ) . '
							' . $this->get_background_position_x_style( $this->theme_mods[ $hook . '_wrapper_background_image_position_column'] ) . '
							' . $this->get_background_repeat_style( $this->theme_mods[ $hook . '_wrapper_background_image_repeat'] ) . '
							' . $this->get_background_attachment_style( $this->theme_mods[ $hook . '_wrapper_background_image_attachment'] ) . '

						}
					';
				} unset( $optional_widget_areas_wrapper_args );

				$index = 0;
				if ( is_array( $this->optional_widget_areas_args ) ) { foreach( $this->optional_widget_areas_args as $id => $widget_area_data ) {

					if ( ! isset( $widget_area_data['hook'] ) ) continue; 

					$selector_key = sse()->get_widget_area_manager()->get_wa_key( $widget_area_data['hook'] );

					// DIV
					$widget_area_class = '#' . $widget_area_data['id'];
					// UL
					$widget_area_ul_class = '#widget-list-' . $widget_area_data['id'];
					// LI
					$widget_class = $widget_area_class . ' .widget-li';
					// DIV
					$widget_inner_class = $widget_area_class . ' .widget';
					// P
					$widget_title_class = $widget_area_class . ' .widget-title';

					$prefix_hook_theme_mods = $widget_area_data['hook'] . '_' . $index;

					$style .= '
						' . $widget_area_class . ' {

							' . $this->get_background_color_style( $this->theme_mods[ $prefix_hook_theme_mods . '_area_background_color'] ) . '
							' . $this->get_background_image_style( $this->theme_mods[ $prefix_hook_theme_mods . '_area_background_image'] ) . '
							' . $this->get_background_size_style( $this->theme_mods[ $prefix_hook_theme_mods . '_area_background_image_size'] ) . '
							' . $this->get_background_position_y_style( $this->theme_mods[ $prefix_hook_theme_mods . '_area_background_image_position_row'] ) . '
							' . $this->get_background_position_x_style( $this->theme_mods[ $prefix_hook_theme_mods . '_area_background_image_position_column'] ) . '
							' . $this->get_background_repeat_style( $this->theme_mods[ $prefix_hook_theme_mods . '_area_background_image_repeat'] ) . '
							' . $this->get_background_attachment_style( $this->theme_mods[ $prefix_hook_theme_mods . '_area_background_image_attachment'] ) . '

							padding: ' . intval( $this->theme_mods[ $prefix_hook_theme_mods . '_area_padding'] ) . 'px;

							' . $this->get_font_family_style( $this->theme_mods[ $prefix_hook_theme_mods . '_font_family'] ) . '
						}
						
						' . $widget_class . ' {

							' . $this->get_background_color_style( $this->theme_mods[ $prefix_hook_theme_mods . '_outer_background_color'] ) . '
							' . $this->get_background_image_style( $this->theme_mods[ $prefix_hook_theme_mods . '_outer_background_image'] ) . '
							' . $this->get_background_size_style( $this->theme_mods[ $prefix_hook_theme_mods . '_outer_background_image_size'] ) . '
							' . $this->get_background_position_y_style( $this->theme_mods[ $prefix_hook_theme_mods . '_outer_background_image_position_row'] ) . '
							' . $this->get_background_position_x_style( $this->theme_mods[ $prefix_hook_theme_mods . '_outer_background_image_position_column'] ) . '
							' . $this->get_background_repeat_style( $this->theme_mods[ $prefix_hook_theme_mods . '_outer_background_image_repeat'] ) . '
							' . $this->get_background_attachment_style( $this->theme_mods[ $prefix_hook_theme_mods . '_outer_background_image_attachment'] ) . '

							box-shadow: ' . ( shapeshifter_boolval( $this->theme_mods[ $prefix_hook_theme_mods . '_widget_border'] ) ? '0 0 5px' : 'none' ) . ';
							border-radius: ' . absint( $this->theme_mods[ $prefix_hook_theme_mods . '_widget_border_radius'] ) . 'px;

							padding: ' . intval( $this->theme_mods[ $prefix_hook_theme_mods . '_widget_inner_padding'] ) . 'px;
						}

						' . $widget_inner_class . ' {
							overflow: auto;

							' . $this->get_background_color_style( $this->theme_mods[ $prefix_hook_theme_mods . '_inner_background_color'] ) . '
							' . $this->get_background_image_style( $this->theme_mods[ $prefix_hook_theme_mods . '_inner_background_image'] ) . '
							' . $this->get_background_size_style( $this->theme_mods[ $prefix_hook_theme_mods . '_inner_background_image_size'] ) . '
							' . $this->get_background_position_y_style( $this->theme_mods[ $prefix_hook_theme_mods . '_inner_background_image_position_row'] ) . '
							' . $this->get_background_position_x_style( $this->theme_mods[ $prefix_hook_theme_mods . '_inner_background_image_position_column'] ) . '
							' . $this->get_background_repeat_style( $this->theme_mods[ $prefix_hook_theme_mods . '_inner_background_image_repeat'] ) . '
							' . $this->get_background_attachment_style( $this->theme_mods[ $prefix_hook_theme_mods . '_inner_background_image_attachment'] ) . '

						}

						' . $widget_title_class . ':before {
							font-family: FontAwesome;
							content: "' . (
								sanitize_text_field( $this->theme_mods[ $prefix_hook_theme_mods . '_widget_title_fontawesome_icon_select'] ) != 'none' 
								? '\\' . sanitize_text_field( $this->theme_mods[ $prefix_hook_theme_mods . '_widget_title_fontawesome_icon_select'] )
								: ''
							) . '";
							margin-right: 10px;
							' . $this->get_color_style( $this->theme_mods[ $prefix_hook_theme_mods . '_widget_title_fontawesome_icon_color'] ) . '
						}

						' . $widget_title_class . ' {
							' . $this->get_color_style( $this->theme_mods[ $prefix_hook_theme_mods . '_title_color'] ) . '
						}

						' . $widget_inner_class . ' li.cat-item:before,
						' . $widget_inner_class . ' li.archive-list-item:before,
						' . $widget_inner_class . ' ul.menu li.menu-item:before,
						' . $widget_inner_class . ' li.page_item:before,
						' . $widget_inner_class . ' li.recentcomments:before {
							font-family: FontAwesome;
							content: "' . (
								sanitize_text_field( $this->theme_mods[ $prefix_hook_theme_mods . '_widget_list_fontawesome_icon_select'] ) != 'none'
								? '\\' . sanitize_text_field( $this->theme_mods[ $prefix_hook_theme_mods . '_widget_list_fontawesome_icon_select'] )
								: ''
							) . '";
							margin-right: 10px;
							' . $this->get_color_style( $this->theme_mods[ $prefix_hook_theme_mods . '_widget_list_fontawesome_icon_color'] ) . '
						}
						
						' . $widget_inner_class . ' > div {
							' . $this->get_color_style( $this->theme_mods[ $prefix_hook_theme_mods . '_text_color'] ) . '
						}

						' . $widget_inner_class . ' > div a,
						' . $widget_inner_class . ' > div a:link,
						' . $widget_inner_class . ' > div a:visited {
							' . $this->get_color_style( $this->theme_mods[ $prefix_hook_theme_mods . '_link_text_color'] ) . '
						}
					';

					$index++;

				} }


			return $style;

		}

	/**
	 * 
	**/
		public function get_post_meta_icon_style( $postmeta )
		{

			$headlines = array(
				'h1' => '.entry-title:before',
				'h2' => '.entry-content h2:before',
				'h3' => '.entry-content h3:before',
				'h4' => '.entry-content h4:before',
				'h5' => '.entry-content h5:before',
				'h6' => '.entry-content h6:before',
			);
			$style = '';
			foreach ( $headlines as $hl => $selector ) {
				$icon_key = sse()->get_prefixed_theme_post_meta_name( $hl . '_icons_select' );
				$color_key = sse()->get_prefixed_theme_post_meta_name( $hl . '_icons_color' );
				$style .= '
					' . $selector . ' {
						content: "' . ( 
							( isset( $postmeta[ $icon_key ] )
								&& 'none' !== $postmeta[ $icon_key ]
							)
							? '\\' . $postmeta[ $icon_key ] . ' '
							: '' 
						) . '" !important;
						color:' . $postmeta[ $color_key ] . ' !important;
					}
				'; 

			}

			return $this->trim_style( $style );
		}

		public function trim_style( $style )
		{

			return wp_strip_all_tags( 
				preg_replace( 
					'/(\n|\r|\t)/', 
					'', 
					$style
				)
			);

		}

	# 320
		function get_320_styles( $device = 'pc', $break_point = 'common' ) {

			# Init
				$style = '';

			# Body
				$style .= $this->get_320_body_styles( $device, $break_point );

			# End
				return $style;

		}

			# Body
				function get_320_body_styles( $device, $break_point ) {

					$style = '';

					$page_types = array( 
						'home' => array(
							'name' => __( 'Home', ShapeShifter_Extensions::TEXTDOMAIN ),
							'description' => __( 'This Section is for Default "Home"', ShapeShifter_Extensions::TEXTDOMAIN ),
							'class' => '.home.blog'
						),
						'blog' => array(
							'name' => __( 'Blog', ShapeShifter_Extensions::TEXTDOMAIN ),
							'description' => __( 'This Section is for "Blog"', ShapeShifter_Extensions::TEXTDOMAIN ),
							'class' => '.blog'
						),
						'front_page' => array(
							'name' => __( 'Front Page', ShapeShifter_Extensions::TEXTDOMAIN ),
							'description' => __( 'This Section is for "Front Page"', ShapeShifter_Extensions::TEXTDOMAIN ),
							'class' => '.home.page'
						),
						'archive' => array(
							'name' => __( 'Archive Page', ShapeShifter_Extensions::TEXTDOMAIN ),
							'description' => __( 'This Section is for "Archive Page"', ShapeShifter_Extensions::TEXTDOMAIN ),
							'class' => '.archive'
						),
						'post' => array(
							'name' => __( 'Posts', ShapeShifter_Extensions::TEXTDOMAIN ),
							'description' => __( 'This Section is for "Single-Type Page"', ShapeShifter_Extensions::TEXTDOMAIN ),
							'class' => '.single'
						),
						'page' => array(
							'name' => __( 'Pages', ShapeShifter_Extensions::TEXTDOMAIN ),
							'description' => __( 'This Section is for "Page-Type Page"', ShapeShifter_Extensions::TEXTDOMAIN ),
							'class' => '.page'
						),
					);

					foreach( $page_types as $page_type => $data ) {

						$class = $data['class'];

						$background_color = sanitize_text_field( $this->theme_mods['body_' . $page_type . '_background_color'] );
						$background_image = esc_url_raw( $this->theme_mods['body_' . $page_type . '_background_image'] );
						$background_image_size = sanitize_text_field( $this->theme_mods['body_' . $page_type . '_background_image_size'] );
						$background_position_row = sanitize_text_field( $this->theme_mods['body_' . $page_type . '_background_image_position_row'] );
						$background_position_column = sanitize_text_field( $this->theme_mods['body_' . $page_type . '_background_image_position_column'] );
						$background_image_repeat = sanitize_text_field( $this->theme_mods['body_' . $page_type . '_background_image_repeat'] );
						$background_image_attachment = sanitize_text_field( $this->theme_mods['body_' . $page_type . '_background_image_attachment'] );

						$style .= '/* Body */
							body' . $class . ' {

								' . $this->get_background_color_style( $background_color ) . '
								' . $this->get_background_image_style( $background_image ) . '
								' . $this->get_background_size_style( $background_image_size ) . '
								' . $this->get_background_position_y_style( $background_position_row ) . '
								' . $this->get_background_position_x_style( $background_position_column ) . '
								' . $this->get_background_repeat_style( $background_image_repeat ) . '
								' . $this->get_background_attachment_style( $background_image_attachment ) . '

							}
						';

					}

					return $style;

				}

	# 640
		function get_640_styles( $device = 'pc', $break_point = 'common' ) {

			# Init
				$style = '';

			# Body
				$style .= $this->get_640_body_styles( $device, $break_point );

			# End
				return $style;

		}

			# Body
				function get_640_body_styles( $device, $break_point ) {

					$style = '';

					$page_types = array( 
						'home' => array(
							'name' => esc_html__( 'Home', ShapeShifter_Extensions::TEXTDOMAIN ),
							'description' => esc_html__( 'This Section is for Default "Home"', ShapeShifter_Extensions::TEXTDOMAIN ),
							'class' => '.home.blog'
						),
						'blog' => array(
							'name' => esc_html__( 'Blog', ShapeShifter_Extensions::TEXTDOMAIN ),
							'description' => esc_html__( 'This Section is for "Blog"', ShapeShifter_Extensions::TEXTDOMAIN ),
							'class' => '.blog'
						),
						'front_page' => array(
							'name' => esc_html__( 'Front Page', ShapeShifter_Extensions::TEXTDOMAIN ),
							'description' => esc_html__( 'This Section is for "Front Page"', ShapeShifter_Extensions::TEXTDOMAIN ),
							'class' => '.home.page'
						),
						'archive' => array(
							'name' => esc_html__( 'Archive Page', ShapeShifter_Extensions::TEXTDOMAIN ),
							'description' => esc_html__( 'This Section is for "Archive Page"', ShapeShifter_Extensions::TEXTDOMAIN ),
							'class' => '.archive'
						),
						'post' => array(
							'name' => esc_html__( 'Posts', ShapeShifter_Extensions::TEXTDOMAIN ),
							'description' => esc_html__( 'This Section is for "Single-Type Page"', ShapeShifter_Extensions::TEXTDOMAIN ),
							'class' => '.single'
						),
						'page' => array(
							'name' => esc_html__( 'Pages', ShapeShifter_Extensions::TEXTDOMAIN ),
							'description' => esc_html__( 'This Section is for "Page-Type Page"', ShapeShifter_Extensions::TEXTDOMAIN ),
							'class' => '.page'
						),
					);

					foreach( $page_types as $page_type => $data ) {

						$class = sanitize_text_field( $data['class'] );

						$background_color = sanitize_text_field( $this->theme_mods['body_' . $page_type . '_background_color'] );
						$background_image = esc_url_raw( $this->theme_mods['body_' . $page_type . '_background_image'] );
						$background_image_size = sanitize_text_field( $this->theme_mods['body_' . $page_type . '_background_image_size'] );
						$background_position_row = sanitize_text_field( $this->theme_mods['body_' . $page_type . '_background_image_position_row'] );
						$background_position_column = sanitize_text_field( $this->theme_mods['body_' . $page_type . '_background_image_position_column'] );
						$background_image_repeat = sanitize_text_field( $this->theme_mods['body_' . $page_type . '_background_image_repeat'] );
						$background_image_attachment = sanitize_text_field( $this->theme_mods['body_' . $page_type . '_background_image_attachment'] );

						$style .= '
							body' . $class . ' {

								' . $this->get_background_color_style( $background_color ) . '
								' . $this->get_background_image_style( $background_image ) . '
								' . $this->get_background_size_style( $background_image_size ) . '
								' . $this->get_background_position_y_style( $background_position_row ) . '
								' . $this->get_background_position_x_style( $background_position_column ) . '
								' . $this->get_background_repeat_style( $background_image_repeat ) . '
								' . $this->get_background_attachment_style( $background_image_attachment ) . '

							}
						';

					}

					return $style;

				}

	# 1024
		function get_1024_styles( $device = 'pc', $break_point = 1024 ) {

			# Init
				$style = '';

			# Body
				$style .= $this->get_1024_body_styles( $device, $break_point );

			# End
				return $style;

		}

			# Body
				function get_1024_body_styles( $device, $break_point ) {

					$style = '';

					$page_types = array( 
						'home' => array(
							'name' => __( 'Home', ShapeShifter_Extensions::TEXTDOMAIN ),
							'description' => __( 'This Section is for Default "Home"', ShapeShifter_Extensions::TEXTDOMAIN ),
							'class' => '.home.blog'
						),
						'blog' => array(
							'name' => __( 'Blog', ShapeShifter_Extensions::TEXTDOMAIN ),
							'description' => __( 'This Section is for "Blog"', ShapeShifter_Extensions::TEXTDOMAIN ),
							'class' => '.blog'
						),
						'front_page' => array(
							'name' => __( 'Front Page', ShapeShifter_Extensions::TEXTDOMAIN ),
							'description' => __( 'This Section is for "Front Page"', ShapeShifter_Extensions::TEXTDOMAIN ),
							'class' => '.home.page'
						),
						'archive' => array(
							'name' => __( 'Archive Page', ShapeShifter_Extensions::TEXTDOMAIN ),
							'description' => __( 'This Section is for "Archive Page"', ShapeShifter_Extensions::TEXTDOMAIN ),
							'class' => '.archive'
						),
						'post' => array(
							'name' => __( 'Posts', ShapeShifter_Extensions::TEXTDOMAIN ),
							'description' => __( 'This Section is for "Single-Type Page"', ShapeShifter_Extensions::TEXTDOMAIN ),
							'class' => '.single'
						),
						'page' => array(
							'name' => __( 'Pages', ShapeShifter_Extensions::TEXTDOMAIN ),
							'description' => __( 'This Section is for "Page-Type Page"', ShapeShifter_Extensions::TEXTDOMAIN ),
							'class' => '.page'
						),
					);

					foreach( $page_types as $page_type => $data ) {

						$class = $data['class'];

						$background_color = $this->theme_mods['body_' . $page_type . '_background_color'];
						$background_image = $this->theme_mods['body_' . $page_type . '_background_image'];
						$background_image_size = $this->theme_mods['body_' . $page_type . '_background_image_size'];
						$background_position_row = $this->theme_mods['body_' . $page_type . '_background_image_position_row'];
						$background_position_column = $this->theme_mods['body_' . $page_type . '_background_image_position_column'];
						$background_image_repeat = $this->theme_mods['body_' . $page_type . '_background_image_repeat'];
						$background_image_attachment = $this->theme_mods['body_' . $page_type . '_background_image_attachment'];

						$style .= '
							body' . $class . ' {

								' . $this->get_background_color_style( $background_color ) . '
								' . $this->get_background_image_style( $background_image ) . '
								' . $this->get_background_size_style( $background_image_size ) . '
								' . $this->get_background_position_y_style( $background_position_row ) . '
								' . $this->get_background_position_x_style( $background_position_column ) . '
								' . $this->get_background_repeat_style( $background_image_repeat ) . '
								' . $this->get_background_attachment_style( $background_image_attachment ) . '

							}
						';

					}

					return $style;

				}

	#
	# Style Code
	#
		function get_style_code( $property, $value, $args ) {

		}

		function get_number_style( $property, $value, $default = '', $unit = 'px', $filter_callable = 'intval' ) {

			# Check if Property is String
				if( ! is_string( $property ) ) {
					return '';
				}

			# Check the Default
				if( is_int( $default ) )
					unset( $default );

			# Check if Value is set. If no, Use Default value
			# Default is also not set return empty string
				if( ! isset( $value ) || ! is_int( $value ) ) {
					if( isset( $default ) )
						$value = $default;
					else 
						return '';
				}

			# Set the Return String CSS Code
				$return = $property . ': ' . $filter_callable( $value ) . $unit . ';';

			# End
				return $return;

		}

	#
	# Color
	#
		function get_color_style( $value, $default = '' ) {

			$return = '';

			if( ! empty( $value ) ) {
				$return = 'color: ' . sanitize_text_field( $value ) . ';';
			} else {
				if( ! empty( $default ) ) {
					$return = 'color: ' . sanitize_text_field( $default ) . ';';
				}
			}

			return $return;

		}

	# Font Family
		function get_font_family_style( $value, $check_if_none = false ) {

			$return = '';

			if( $check_if_none ) {
				if( ! empty( $value ) && is_string( $value ) && $value !== 'none' ) {
					$return = 'font-family: ' . sanitize_text_field( $value ) . ';';
				}
			} else {
				if( ! empty( $value ) && is_string( $value ) ) {
					$return = 'font-family: ' . sanitize_text_field( $value ) . ';';
				}
			}

			return $return;

		}

	#
	# Background
	#
		# Color
			function get_background_color_style( $value, $default = '' ) {

				$return = '';

				if( ! empty( $value ) ) {
					$return = 'background-color: ' . sanitize_text_field( $value ) . ';';
				} else {
					if( ! empty( $default ) ) {
						$return = 'background-color: ' . sanitize_text_field( $default ) . ';';
					}
				}

				return $return;

			}

		# Image
			function get_background_image_style( $value, $default = '' ) {

				$return = '';
				$image_url = esc_url_raw( $value );

				if( ! empty( $value ) ) {
					$return = 'background-image: url(' . sanitize_text_field( $value ) . ');';
				} else {
					if( ! empty( $default ) ) {
						$return = 'background-image: url(' . sanitize_text_field( $default ) . ');';
					} else {
						$return = 'background-image: none;';
					}
				}

				return $return;

			}

		# Size
			function get_background_size_style( $value, $default = '' ) {

				$return = '';

				if( ! empty( $value ) ) {
					$return = 'background-size: ' . sanitize_text_field( $value ) . ';';
				} else {
					if( ! empty( $default ) ) {
						$return = 'background-size: ' . sanitize_text_field( $default ) . ';';
					}
				}

				return $return;

			}

		# Position Y
			function get_background_position_y_style( $value, $default = '' ) {

				$return = '';

				if( ! empty( $value ) ) {
					$return = 'background-position-y: ' . sanitize_text_field( $value ) . ';';
				} else {
					if( ! empty( $default ) ) {
						$return = 'background-position-y: ' . sanitize_text_field( $default ) . ';';
					}
				}

				return $return;

			}

		# Position X
			function get_background_position_x_style( $value, $default = '' ) {

				$return = '';

				if( ! empty( $value ) ) {
					$return = 'background-position-x: ' . sanitize_text_field( $value ) . ';';
				} else {
					if( ! empty( $default ) ) {
						$return = 'background-position-x: ' . sanitize_text_field( $default ) . ';';
					}
				}

				return $return;

			}

		# Repeat
			function get_background_repeat_style( $value, $default = '' ) {

				$return = '';

				if( ! empty( $value ) ) {
					$return = 'background-repeat: ' . sanitize_text_field( $value ) . ';';
				} else {
					if( ! empty( $default ) ) {
						$return = 'background-repeat: ' . sanitize_text_field( $default ) . ';';
					}
				}

				return $return;

			}

		# Attachment
			function get_background_attachment_style( $value, $default = '' ) {

				$return = '';

				if( ! empty( $value ) ) {
					$return = 'background-attachment: ' . sanitize_text_field( $value ) . ';';
				} else {
					if( ! empty( $default ) ) {
						$return = 'background-attachment: ' . sanitize_text_field( $default ) . ';';
					}
				}

				return $return;

			}

	#
	# Header Image
	#
		function get_theme_mods_logo_position() {

			if ( $this->theme_mods['header_image_position'] == 'left' ) {
				return 'margin:auto;margin-left:0;';
			} elseif ( $this->theme_mods['header_image_position'] == 'center' ) {
				return 'margin:auto;';
			} elseif ( $this->theme_mods['header_image_position'] == 'right' ) {
				return 'margin:auto;margin-right:0;';
			}
		
		}

		function get_theme_mods_logo_title_description_position() {

			if ( $this->theme_mods['header_image_title_description_position'] == 'left-top' ) {
				return 'position:absolute;top:0;left:0;';
			} elseif ( $this->theme_mods['header_image_title_description_position'] == 'left-bottom' ) {
				return 'position:absolute;left:0;bottom:0;';
			} elseif ( $this->theme_mods['header_image_title_description_position'] == 'right-top' ) {
				return 'position:absolute;top:0;right:0;';
			} elseif ( $this->theme_mods['header_image_title_description_position'] == 'right-bottom' ) {
				return 'position:absolute;right:0;bottom:0;';
			} else {
			}

		}

		function get_theme_mods_logo_title_description_position_for_mobile() {

			if ( $this->theme_mods['header_image_title_description_position'] == 'left-top' ) {
				return 'position:relative;top:0;left:0;';
			} elseif ( $this->theme_mods['header_image_title_description_position'] == 'left-bottom' ) {
				return 'position:relative;left:0;bottom:0;';
			} elseif ( $this->theme_mods['header_image_title_description_position'] == 'right-top' ) {
				return 'position:relative;top:0;right:0;';
			} elseif ( $this->theme_mods['header_image_title_description_position'] == 'right-bottom' ) {
				return 'position:relative;right:0;bottom:0;';
			} else {
			}

		}

}
}

?>