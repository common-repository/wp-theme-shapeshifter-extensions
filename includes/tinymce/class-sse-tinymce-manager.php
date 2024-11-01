<?php

if ( ! defined( 'ABSPATH' ) ) exit;
/**
 * SSE_TinyMCE_Manager
**/
class SSE_TinyMCE_Manager extends SSE_Unique_Abstract {

	/**
	 * Static
	**/

	/**
	 * Properties
	**/
		public $options = array();

		//public $shapeshifter_button = array( 'shapeshifter_button' => 'shapeshifter_button' );
		public $shapeshifter_button = 'shapeshifter_button';

	/**
	 * Init
	**/
		/**
		 * Public Initializer
		 * @return SSE_TinyMCE_Manager
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
			$this->init_hooks();
		}

		/**
		 * Init hooks
		**/
		protected function init_hooks() {

			// Actions
				// Reference Page

				// Add Button
				add_action( 'admin_init', array( $this, 'admin_init' ) );
				add_action( 'admin_print_scripts', array( $this , 'admin_print_scripts' ) );

				// Insert Items AJAX
				add_action( 'wp_ajax_insert_google_map_from_shapeshifter_button', array( $this, 'insert_google_map_from_shapeshifter_button' ) );

				// admin_enqueue_scripts
				add_action( 'admin_enqueue_scripts', array( $this, 'admin_enqueue_scripts' ) );

				// Save Editor Styles
				//register_activation_hook( SHAPESHIFTER_EXTENSIONS_MAIN_FILE, array( 'SSE_TinyMCE_Manager', 'shapeshifter_activation_hook' ) );
				add_action( 'customize_save_after', array( 'SSE_TinyMCE_Manager', 'save_editor_styles' ) );
				add_action( 'admin_init', array( $this, 'add_editor_styles' ) );

				//add_action( 'admin_init', array( $this, 'save_editor_styles' ) );

				// AJAX
				add_action( 'wp_ajax_shapeshifter_get_post_data', array( $this, 'get_post_data' ) );


			// Filters
			//add_filter( 'tiny_mce_before_init', array( $this, 'tiny_mce_before_init' ) );

		}

		/**
		 * Add Button
		 * @return [type] [description]
		**/
		function admin_init() {

			// check user permissions
				if ( ! current_user_can( 'edit_posts' ) && ! current_user_can( 'edit_pages' ) ) {
					return;
				}

			// check if WYSIWYG is enabled
				if ( 'true' == get_user_option( 'rich_editing' ) ) {
					add_filter( 'mce_external_plugins', array( $this ,'mce_external_plugins' ) );
					add_filter( 'mce_buttons', array( $this, 'mce_buttons' ), 1000 );
				}

		}
			/**
			 * mce_external_plugins
			 * @param  string[] $plugin_array
			 * @return string[]
			**/
			function mce_external_plugins( $plugin_array ) {
				$plugin_array['shapeshifter_button'] = esc_url( SSE_ASSETS_URL . 'js/admin/admin-mce-button.js' );
				return $plugin_array;
			}

			/**
			 * MCE Button
			 * @param  string[] $buttons
			 * @return string[]
			**/
			function mce_buttons( $buttons ) {
				array_push( $buttons, $this->shapeshifter_button );
				return $buttons;
			}

			/**
			 * Button Style
			 * @return void
			**/
			function admin_print_scripts() {
				echo '<style>
				i.mce-i-shapeshifter_button:before {
				    content: "SS";
				}</style>
				';
			}

		/**
		 * Admin Enqueue Scripts
		 * @param  string $hook
		 * @return void
		 */
		function admin_enqueue_scripts( $hook ) {

			if( ! in_array( $hook, array( 'post.php', 'widgets.php' ) ) || is_customize_preview() ) {
				return;
			}

			wp_enqueue_style( 'sse-mce-button' );

			wp_enqueue_script( 'backbone' );
			wp_enqueue_script( 'slider-pro' );

			$data = array(
				'pluginRootURI' => SHAPESHIFTER_EXTENSIONS_DIR_URL,
				'left' => esc_html__( 'Left', ShapeShifter_Extensions::TEXTDOMAIN ),
				'right' => esc_html__( 'Right', ShapeShifter_Extensions::TEXTDOMAIN )
			);

			wp_localize_script( 'jquery', 'shapeshifterExtensionsTMCEObject', $data );

			//wp_enqueue_script( 'sse-tinymce' );
			wp_enqueue_script( 'sse-quicktags' );

		}

	/**
	 * Editor Style
	**/
		# MCE Editor Styles
		public static function save_editor_styles() {

			// Preparations
				if ( ! current_user_can( 'manage_options' ) ) {
					return;
				}

				$creds = SSE_TinyMCE_Manager::shapeshifter_ajax_init_filesystem( SHAPESHIFTER_EXTENSIONS_PREFIX . 'editor-style' );

				if( ! $creds ) {
					echo 'Creds False' . PHP_EOL;
					return;
				}

				global $wp_filesystem;

			// Is Static
				$theme_mods = sse()->get_theme_mods();

			// Editor Styles
				$style = '
					body#tinymce.wp-editor { font-size: 12px; padding: 10px; }
					body#tinymce.wp-editor * {margin: 0; padding:0;}
					body#tinymce.wp-editor li {display: list-item;}

					/* WordPress Core
					-------------------------------------------------------------- */
						.sticky {

						}
						.gallery-caption {

						}
						.bypostauthor {
							
						}
						body#tinymce.wp-editor .alignnone {
							margin: 5px 20px 20px 0;
						}

						body#tinymce.wp-editor .aligncenter,
						body#tinymce.wp-editor div.aligncenter {
							display: block;
							margin: 5px auto 5px auto;
						}

						body#tinymce.wp-editor .alignright {
							float:right;
							margin: 5px 0 20px 20px;
						}

						body#tinymce.wp-editor .alignleft {
							float: left;
							margin: 5px 20px 20px 0;
						}

						body#tinymce.wp-editor a img.alignright {
							float: right;
							margin: 5px 0 20px 20px;
						}

						body#tinymce.wp-editor a img.alignnone {
							margin: 5px 20px 20px 0;
						}

						body#tinymce.wp-editor a img.alignleft {
							float: left;
							margin: 5px 20px 20px 0;
						}

						body#tinymce.wp-editor a img.aligncenter {
							display: block;
							margin-left: auto;
							margin-right: auto
						}

						body#tinymce.wp-editor .wp-caption {
							background: #fff;
							border: 1px solid #f0f0f0;
							max-width: 96%; /* Image does not overflow the content area */
							padding: 5px 3px 10px;
							text-align: center;
						}

						body#tinymce.wp-editor .wp-caption.alignnone {
							margin: 5px 20px 20px 0;
						}

						body#tinymce.wp-editor .wp-caption.alignleft {
							margin: 5px 20px 20px 0;
						}

						body#tinymce.wp-editor .wp-caption.alignright {
							margin: 5px 0 20px 20px;
						}

						body#tinymce.wp-editor .wp-caption img {
							border: 0 none;
							height: auto;
							margin: 0;
							max-width: 98.5%;
							padding: 0;
							width: auto;
						}

						body#tinymce.wp-editor .wp-caption p.wp-caption-text {
							font-size: 11px;
							line-height: 17px;
							margin: 0;
							padding: 0 4px 5px;
						}

						/* Text meant only for screen readers. */
							body#tinymce.wp-editor .screen-reader-text {
								clip: rect(1px, 1px, 1px, 1px);
								position: absolute !important;
								height: 1px;
								width: 1px;
								overflow: hidden;
							}

							body#tinymce.wp-editor .screen-reader-text:focus {
								background-color: #f1f1f1;
								border-radius: 3px;
								box-shadow: 0 0 2px 2px rgba(0, 0, 0, 0.6);
								clip: auto !important;
								color: #21759b;
								display: block;
								font-size: 14px;
								font-size: 0.875rem;
								font-weight: bold;
								height: auto;
								left: 5px;
								line-height: normal;
								padding: 15px 23px 14px;
								text-decoration: none;
								top: 5px;
								width: auto;
								z-index: 100000; /* Above WP toolbar. */
							}

							body#tinymce.wp-editor .clearfix:before {
								clear: both;
							}

							body#tinymce.wp-editor iframe{
								width: 100%;
							}

					/* ShapeShifter 
					-------------------------------------------------------------- */
						body#tinymce.wp-editor {
							padding: 10px;
							margin-bottom:20px;

							color:' . $theme_mods['singular_page_text_color'] . ';
							
						}
						body#tinymce.wp-editor > * {
							margin: 10px;
						}

						body#tinymce.wp-editor a:hover{
							text-decoration:none !important;
							opacity:0.5;
						}
						body#tinymce.wp-editor a:hover p,body#tinymce.wp-editor a:hover div{
							opacity:0.5;
						}

						body#tinymce.wp-editor ol,
						body#tinymce.wp-editor ul,
						body#tinymce.wp-editor dl{
							padding-left: 40px;
						}

						/* Contents Texts
						-------------------------------------------------------------- */
							body#tinymce.wp-editor > p {
								line-height: 1.5;
								padding: 10px;
								text-decoration: none;
							}
							body#tinymce.wp-editor h2,
							body#tinymce.wp-editor h3,
							body#tinymce.wp-editor h4,
							body#tinymce.wp-editor h5,
							body#tinymce.wp-editor h6,
							body#tinymce.wp-editor p {
								padding: 5px 10px;
								background-size: 100% 100%;
								background-repeat: no-repeat;
							}

							body#tinymce.wp-editor h2:before,
							body#tinymce.wp-editor h3:before,
							body#tinymce.wp-editor h4:before,
							body#tinymce.wp-editor h5:before,
							body#tinymce.wp-editor h6:before,
							body#tinymce.wp-editor p:before {
								font-family: FontAwesome;
								margin-right: 15px;
							}

					/* ShapeShifter TinyMCE
					-------------------------------------------------------------- */

						/* General
						-------------------------------------------------------------- */
							body#tinymce.wp-editor .shapeshifter-flex-wrapper {
								border: dashed #eee 1px;
								margin: 10px 0;
							}
							body#tinymce.wp-editor .shapeshifter-flex-wrapper > div {

								overflow: hidden;

								border: dashed #eee 1px;

							}

							body#tinymce.wp-editor h2,
							body#tinymce.wp-editor h3,
							body#tinymce.wp-editor h4,
							body#tinymce.wp-editor h5,
							body#tinymce.wp-editor h6,
							body#tinymce.wp-editor p {
								padding: 5px 10px;
								background-size: 100% 100%;
								background-repeat: no-repeat;
							}

							body#tinymce.wp-editor h2:before,
							body#tinymce.wp-editor h3:before,
							body#tinymce.wp-editor h4:before,
							body#tinymce.wp-editor h5:before,
							body#tinymce.wp-editor h6:before,
							body#tinymce.wp-editor p:before {
								font-family: FontAwesome;
								margin-right: 15px;
							}

							/* iframe
							-------------------------------------------------------------- */
								.mce-object-iframe {
									width: 100%;
								}

						/* Row
						-------------------------------------------------------------- */
							body#tinymce.wp-editor .shapeshifter-row > div {

								overflow: hidden;

								height: 100px;

								border: dashed #eee 1px;

							}

							body#tinymce.wp-editor .shapeshifter-row > div:focused {

								border: solid #000 1px;

							}
								body#tinymce.wp-editor .shapeshifter-row > div > button.shapeshifter-edit-col {
									width: 100%;
									height: 100%;
								}

						/* Slider
						-------------------------------------------------------------- */

							/* Wrapper */
								body#tinymce.wp-editor .shapeshifter-slider-pro-simple-images:before {
									content: "Image Slider";
									font-size: 16px;
								}

								body#tinymce.wp-editor .shapeshifter-slider-pro .sp-slides {
									width: 100%;
									height: 150px;
									border: solid #000 1px;
									overflow: scroll;
									display: flex;
									flex-wrap: wrap;
								}
								body#tinymce.wp-editor .shapeshifter-slider-pro .sp-slides .sp-slide {
								}
								body#tinymce.wp-editor .shapeshifter-slider-pro .sp-slides .sp-slide p {

								}
								body#tinymce.wp-editor .shapeshifter-slider-pro .sp-slides .sp-slide img {
									width: 100px;
									height: 100px;
									margin: 5px;

								}
								body#tinymce.wp-editor .shapeshifter-slider-pro .sp-thumbnail {
									display: none;
								}

						/* Link
						-------------------------------------------------------------- */
							.shapeshifter-link-wrapper {
								border: solid #000 1px;
								width: 100%;
							}

				';

				if ( class_exists( 'ShapeShifter_Styles_Handler' ) ) {
					$style_manager = new ShapeShifter_Styles_Handler();
					if( method_exists( $style_manager, 'get_common_content_text_styles' ) )
						$style .= str_replace( '.shapeshifter-content', 'body#tinymce.wp-editor', $style_manager->get_common_content_text_styles() );
					if( method_exists( $style_manager, 'get_common_content_items_styles' ) )
						$style .= str_replace( '.shapeshifter-content', 'body#tinymce.wp-editor', $style_manager->get_common_content_items_styles() );
				}
				
				if ( class_exists( 'SSE_Styles_Manager' ) ) {
					$sse_style_manager = SSE_Styles_Manager::get_instance();
					if( method_exists( $sse_style_manager, 'get_common_content_items_styles' ) )
						$style .= str_replace( '.shapeshifter-content', 'body#tinymce.wp-editor', $sse_style_manager->get_common_content_items_styles() );
				}

			// Prepared to update
				$style = sanitize_text_field( $style );
				$editor_style_file =  SSE_ASSETS_DIR . 'css/editor-style.css';

			// Update File
				if( $wp_filesystem->is_writable( $editor_style_file ) ) {
					$wp_filesystem->put_contents( $editor_style_file, $style );
				}

		}

			# AJAX Init File System
			public static function shapeshifter_ajax_init_filesystem( $nonce ) {

				$access_type = get_filesystem_method();
				if( $access_type === 'direct' ) {

					$nonce_url = esc_url( wp_nonce_url( admin_url(), $nonce ) );

					$creds = request_filesystem_credentials( $nonce_url, '', false, false, array() );

					if ( ! WP_Filesystem( $creds ) ) {
						return false;
					}	

					return true;

				}

				return false;

			}

		public function add_editor_styles() {
return;
			# Standard CSS
				add_editor_style( esc_url( SHAPESHIFTER_THEME_ROOT_URI . '/includes/3rd/normalize-css/normalize.css' ) );
				add_editor_style( esc_url( SHAPESHIFTER_THEME_ROOT_URI . '/includes/3rd/bootstrap/css/bootstrap.min.css' ) );
				add_editor_style( esc_url( SHAPESHIFTER_THEME_ROOT_URI . '/includes/3rd/bootstrap/css/bootstrap-theme.min.css' ) );
				add_editor_style( esc_url( SHAPESHIFTER_THEME_ROOT_URI . '/includes/3rd/font-awesome/css/font-awesome.min.css' ) );

			# Generated CSS
				add_editor_style( esc_url( SHAPESHIFTER_THEME_ROOT_URI . '/style.css' ) );
				add_editor_style( esc_url( SSE_ASSETS_URL . 'css/editor-style.css' ) );

		}

	/**
	 * AJAX
	**/
		/**
		 * Post
		 * @return array
		**/
		public function get_post_data() {

			if( isset( $_POST['post_id'] ) ) $post_id = absint( $_POST['post_id'] );

			$permalink = esc_url( get_the_permalink( $post_id ) );
			$thumbnail_url = esc_url(
				get_the_post_thumbnail_url( $post_id )
				? get_the_post_thumbnail_url( $post_id )
				: get_theme_mod( 'default_thumbnail_image', SSE_ASSETS_URL . 'images/no-img.png' )
			);

			$post = get_post( $post_id, 'ARRAY_A' );
			$post['thumbnail_url'] = $thumbnail_url;
			$post['permalink'] = $permalink;

			if( isset( $_POST['post_id'] ) ) wp_die( wp_json_encode( $post ) );

		}

	/**
	 * Filters
	**/
		/**
		 * TinyMCE Init
		 * @param  string[] $init_array
		 * @return string[]
		**/
		function tiny_mce_before_init( $init_array ) {
			//$init_array['toolbar1'] .= 'shapeshifter_button';
			return $init_array;
		}

}
