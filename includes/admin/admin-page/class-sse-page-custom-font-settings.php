<?php
if( ! defined( 'ABSPATH' ) ) exit; 

/**
 * 
**/
class SSE_Page_Custom_Font_Settings extends SSE_Page_Abstract {

	/**
	/**
	 * Static
	**/
		protected static $instance;

	/**
	 * Properties
	**/
		/**
		 * Menu Slut
		 * @var string
		**/
		protected $menu_slug = 'font_settings_menu';

		/**
		 * Form Template 
		 * @var string
		**/
		protected $form_template = 'view/form-custom-font-settings.php';



		protected $google_fonts_api_key;

		protected $options = array();

		public $font_files_list = array();

		protected $upload_dir = array();
		protected $fonts_dir;

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
		protected function __construct() {
			// Google Fonts API Key
			$this->google_fonts_api_key = get_option( sse()->get_prefixed_option_name( 'google_fonts_api_key' ), '' );
			$this->init();
			$this->init_hooks();
		}

		/**
		 * Init
		**/
		protected function init()
		{
			$this->page_title = esc_html__( "Custom Fonts", ShapeShifter_Extensions::TEXTDOMAIN );
			$this->menu_title = esc_html__( "Custom Fonts", ShapeShifter_Extensions::TEXTDOMAIN );
		}

		/**
		 * Init WP Hooks
		 * 
		**/
		protected function init_hooks()
		{

			// Messages
			add_action( 'admin_notices', array( $this, 'option_update_message' ) );
			// Add Page
			add_action( 'admin_menu', array( $this, 'admin_menu' ) );
			// Admin Enqueue Scripts
			add_action( 'admin_enqueue_scripts', array( $this, 'admin_enqueue_scripts' ) );

			// AJAX
				// Save Google Fonts API Key
				add_action( 'wp_ajax_shapeshifter_save_google_fonts_api_key', array( $this, 'shapeshifter_save_google_fonts_api_key' ) );
				add_action( 'wp_ajax_nopriv_shapeshifter_save_google_fonts_api_key', array( $this, 'shapeshifter_save_google_fonts_api_key' ) );
				// Print TBODY > TR
				add_action( 'wp_ajax_shapeshifter_print_google_fonts_list', array( $this, 'shapeshifter_print_google_fonts_list' ) );
				add_action( 'wp_ajax_nopriv_shapeshifter_print_google_fonts_list', array( $this, 'shapeshifter_print_google_fonts_list' ) );
				// Save Applied Google Fonts List
				add_action( 'wp_ajax_shapeshifter_save_applied_google_fonts_list', array( $this, 'shapeshifter_save_applied_google_fonts_list' ) );
				add_action( 'wp_ajax_nopriv_shapeshifter_save_applied_google_fonts_list', array( $this, 'shapeshifter_save_applied_google_fonts_list' ) );

				add_action( 'wp_ajax_priv_message', array( $this, 'priv_message' ) );
				add_action( 'wp_ajax_nopriv_nopriv_message', array( $this, 'nopriv_message' ) );

		}

	/**
	 * Actions
	**/
		/**
		 * Messages
		 * @return [type] [description]
		**/
		public function option_update_message()
		{
			
			if( isset( $_POST['submit-upload-font-file'] ) && $_POST['submit-upload-font-file'] ) {
				
				if( empty( $_FILES['upload-font-file']['name'] ) ) {
					
					echo '<div class="error"><p class="message">' . esc_html__( 'Select a file to upload.', ShapeShifter_Extensions::TEXTDOMAIN ) . '</p></div>';//アップロードするファイルを指定してください。

				} elseif( check_admin_referer( 'upload-font-file-check', 'upload-font-file-check-nonce' ) ) {

					if( ! preg_match( '/([^\s]+)\.(ttf|otf|eot|woff|woff2)$/', $_FILES['upload-font-file']['name'] ) )
						echo '<div class="error"><p class="message">' . esc_html__( "None of following file types : 'ttf' 'otf' 'eot' 'woff' 'woff2'", ShapeShifter_Extensions::TEXTDOMAIN ) . '</p></div>';//「ttf」「otf」「eot」「woff」「woff2」ファイルのいずれでもありません。
					else 
						echo '<div class="updated"><p class="message">' . esc_html__( 'File Uploaded.', ShapeShifter_Extensions::TEXTDOMAIN ) . '</p></div>';//ファイルをアップロードしました。

				} else {

				}

			} elseif( isset( $_POST['submit-remove-fonts-files'] ) && $_POST['submit-remove-fonts-files'] ) {

				check_admin_referer( 'remove-font-file-check', 'remove-font-file-check-nonce' );

				if( ! isset( $_POST['target-font-name'][ 0 ] ) ) {
					
					echo '<div class="error"><p class="message">' . esc_html__( 'Please select fonts to delete.', ShapeShifter_Extensions::TEXTDOMAIN ) . '</p></div>';//フォントが指定されていません。チェックを入れてください。

				} else {

					echo '<div class="updated"><p class="message">' . esc_html__( 'Deleted the selected File(s)', ShapeShifter_Extensions::TEXTDOMAIN ) . '</p></div>';//指定された「font-face」に関連するファイルを削除しました。

				}

			}

		}

		/**
		 * Add Page
		 * @return void
		**/
		public function admin_menu() {
			$this->manage_fonts_dir();
			parent::admin_menu();
		}

		/**
		 * Enqueue Scripts
		 * @return void
		**/
		public function admin_enqueue_scripts( $hook ) {

			if ( ! isset( $_GET['page'] ) 
				|| 'sse_font_settings_menu' !== $_GET['page']
			) {
				return;
			}

			wp_enqueue_script( 'sse-admin-custom-fonts' );
		}

	/**
	 * AJAX
	**/
		/**
		 * Save Google Fonts API Key
		 * @return void
		**/
		public function shapeshifter_save_google_fonts_api_key() {

			# Get Data From JavaScript
			# and Save
				$google_fonts_api_key = sanitize_text_field( $_REQUEST['googleFontsAPIKey'] );
				if( strlen( $google_fonts_api_key ) > 0 ) {
					# Save
						update_option( sse()->get_prefixed_option_name( 'google_fonts_api_key' ), $google_fonts_api_key );
						wp_die( 'saved' );
				} else {
					# Delete
						delete_option( sse()->get_prefixed_option_name( 'google_fonts_api_key' ) );
						wp_die( 'deleted' );
				}

		}

		/**
		 * Print TBODY > TR
		 * @return void
		**/
		public function shapeshifter_print_google_fonts_list() {

			# Setup
				# AJAX
					if( isset( $_REQUEST['googleFontsData'] ) ) {

						# Nonce Check
							check_ajax_referer( 'google-font-file-check', 'ss_google_fonts_check_nonce' );

						# JSON Data Setup
							try {
								# Conversion Error
									if( ! sse_is_json_string( str_replace( array( '\\' ), '', $_REQUEST['googleFontsData'] ) ) ) {
										throw new Exception( esc_html__( 'Failed conversion into JSON.', ShapeShifter_Extensions::TEXTDOMAIN ) );
									}
									else if( json_decode( str_replace( array( '\\' ), '', $_REQUEST['googleFontsData'] ), true ) == null ) {
										throw new Exception( esc_html__( 'Failed conversion into JSON.', ShapeShifter_Extensions::TEXTDOMAIN ) );
									}
									else if( json_last_error() !== JSON_ERROR_NONE ) {
										throw new Exception( esc_html__( 'Failed conversion into JSON.', ShapeShifter_Extensions::TEXTDOMAIN ) );
									}

								# Conversion
									$google_fonts_json_data = json_decode( str_replace( array( '\\' ), '', $_REQUEST['googleFontsData'] ), true );

								# Making Array
									$google_fonts_list = array();
									if( isset( $google_fonts_json_data['items'] ) 
										&& is_array( $google_fonts_json_data['items'] ) 
									) { foreach( $google_fonts_json_data['items'] as $google_font_item ) {

										# Setup sub font family to append Font Family as CSS Property
											$sub_font_family = '';
											if( $google_font_item['category'] === 'display' ) {
												$sub_font_family = 'cursive';
											} elseif( $google_font_item['category'] === 'handwriting' ) {
												$sub_font_family = 'cursive';
											} elseif( $google_font_item['category'] === 'serif' ) {
												$sub_font_family = 'serif';
											} elseif( $google_font_item['category'] === 'sans-serif' ) {
												$sub_font_family = 'sans-serif';
											} elseif( $google_font_item['category'] === 'monospace' ) {
												$sub_font_family = 'monospace';
											} else {
												$sub_font_family = 'cursive';
											}

										# Font Data
											array_push(
												$google_fonts_list,
												array( 
													'font-name-display' => sanitize_text_field( $google_font_item['family'] ),
													'font-family' => sanitize_text_field( $google_font_item['family'] ),
													'font-family-in-url' => sanitize_text_field( str_replace( array( ' ' ), '+', $google_font_item['family'] ) ),
													'font-family-in-css' => sanitize_text_field( '"' . $google_font_item['family'] . '", ' . $sub_font_family ),
													'category' => sanitize_text_field( $google_font_item['category'] ),
													'sub-font-family' => sanitize_text_field( $sub_font_family )
												)
											);

									} }

								# Update the List
									update_option( sse()->get_prefixed_option_name( 'google_fonts_list' ), $google_fonts_list );

							} catch( Exception $e ) {
								//delete_option( sse()->get_prefixed_option_name( 'google_fonts_list' ) );
								wp_die( $e );
							}

					} 

				# Not AJAX
					else {
						$google_fonts_list = get_option( sse()->get_prefixed_option_name( 'google_fonts_list' ), false 	);
						if( get_option( sse()->get_prefixed_option_name( 'google_fonts_api_key' ), '' ) == '' ) {
							return;
						}
					}

			# Saved Data
				$applied_google_fonts_list = get_option( sse()->get_prefixed_option_name( 'applied_google_fonts_list' ), array() );
				if( is_array( $google_fonts_list ) 
					&& count( $google_fonts_list ) > 0 
				) { foreach( $google_fonts_list as $index => $google_font ) { 
					$font_famliy = esc_attr( $google_font['font-family'] );
					?>
					<tr>
						<th class="check-column">
							<input type="checkbox" name="applied-font-name" 
								class="regular-checkbox applied-font-input"
								value="<?php echo esc_attr( $google_font['font-family'] ); ?>"
								data-font-name-display="<?php echo esc_attr( $google_font['font-name-display'] ); ?>"
								data-font-family="<?php echo esc_attr( $google_font['font-family'] ); ?>"
								data-font-family-in-url="<?php echo esc_attr( $google_font['font-family-in-url'] ); ?>"
								data-font-family-in-css="<?php echo esc_attr( $google_font['font-family-in-css'] ); ?>"
								data-category="<?php echo esc_attr( $google_font['category'] ); ?>"
								data-sub-font-family="<?php echo esc_attr( $google_font['sub-font-family'] ); ?>"
								<?php echo ( 
									isset( $applied_google_fonts_list[ $font_famliy ] )
									&& $google_font['font-family'] == $applied_google_fonts_list[ $font_famliy ]['font-family'] 
									? 'checked' 
									: '' 
								); ?>
							>
						</th>
						<td><label>
							<?php echo esc_html( $google_font['font-name-display'] ); ?>
						</label></td>
					</tr>
				<?php } }

			if( ! empty( $_REQUEST['googleFontsData'] ) ) {
				wp_die();
			}

		}

		/**
		 * Save Applied Google Fonts List
		 * @return void
		**/
		public function shapeshifter_save_applied_google_fonts_list() {

			if( isset( $_REQUEST['appliedGoogleFonts'] ) )
				$applied_google_fonts_list = $_REQUEST['appliedGoogleFonts'];

			# Applied Google Fonts List
				$saved_array = array();
				$css_url = 'https://fonts.googleapis.com/css?family=';
				if( is_array( $applied_google_fonts_list ) 
					&& count( $applied_google_fonts_list ) > 0 
				) { foreach( $applied_google_fonts_list as $index => $applied_google_font ) {
					# Data Set
						$font_family = $applied_google_font['font-family'];
						$saved_array[ $font_family ] = array(
							'font-name-display' => sanitize_text_field( $applied_google_font['font-name-display'] ),
							'font-family' => sanitize_text_field( $applied_google_font['font-family'] ),
							'font-family-in-url' => sanitize_text_field( $applied_google_font['font-family-in-url'] ),
							'font-family-in-css' => sanitize_text_field( $applied_google_font['font-family-in-css'] ),
							'category' => sanitize_text_field( $applied_google_font['category'] ),
							'sub-font-family' => sanitize_text_field( $applied_google_font['sub-font-family'] )
						);
					# For CSS
						$css_url .= $saved_array[ $font_family ]['font-family-in-url'] . '|';
				} }

				# Last Trimming
					$css_url = esc_url_raw( substr( $css_url, 0, -1 ) );

			# Updates
				update_option( sse()->get_prefixed_option_name( 'applied_google_fonts_list' ), $saved_array );
				update_option( sse()->get_prefixed_option_name( 'applied_google_fonts_css_url' ), $css_url );

			# Return to JS
				wp_die( wp_json_encode( $saved_array ) );

		}

	/**
	 * Files
	**/
		/**
		 * Font Settings
		 * @return void
		**/
		function manage_fonts_dir() {

			# Init File System
				if ( ! current_user_can( 'manage_options' ) ) {
					return;
				}

				$nonce_url = wp_nonce_url( esc_url( admin_url( 'theme.php?page=sse_font_settings_menu' ) ), sse()->get_prefixed_option_name( 'mkdir-upload' ) );
				
				# Check if is Writable
					if( false === ( $creds = request_filesystem_credentials( $nonce_url, '', false, false, null ) ) ) {
						return;
					}
				
				# Init Class WP_Filesystem_Base
					if ( ! WP_Filesystem( $creds ) ) {
						request_filesystem_credentials( $nonce_url, '', true, false, null );
						return;
					}

				global $wp_filesystem;

			# Setup Vars
				$this->setup_fonts_files( $wp_filesystem );

			# Manipulate
				if( ! empty( $_POST['submit-upload-font-file'] ) ) {

					$this->upload_font_file( $wp_filesystem );

				} elseif( ! empty( $_POST['submit-remove-fonts-files'] ) ) {

					$this->remove_font_file( $wp_filesystem );

				}

			$this->save_font_face_style( $wp_filesystem );

		}

			# Init
			function setup_fonts_files( $wp_filesystem ) {

				# Make Dir
					$this->upload_dir = wp_upload_dir();
					$fonts_dir = $this->upload_dir['basedir'] . '/custom-fonts';
					$this->fonts_dir = $fonts_dir;
					//echo $fonts_dir;
					if( ! $wp_filesystem->is_dir( $fonts_dir ) ) {
						//echo 'no dir';
						$result = $wp_filesystem->mkdir( $fonts_dir, 0755 );
						if( $result ) {
							//echo 'created directory "custom-fonts"';
						}
					}

				# Make Font Files
					$font_files_list = $wp_filesystem->dirlist( $fonts_dir );

					foreach( $font_files_list as $index => $font_file ) {
						if( preg_match( '/([^\s]+)\.(otf|ttf|eof|woff|woff2)$/', $font_file['name'], $matched ) ) {

							$font_name = $matched[ 1 ];
							$file_tail = $matched[ 2 ];
							$this->font_files_list[ $font_name ][ $file_tail ] = $matched[ 0 ];

						}
					} //print_r( $this->font_files_list ); // Check

			}

			# Upload
			function upload_font_file( $wp_filesystem ) {

				check_admin_referer( 'upload-font-file-check', 'upload-font-file-check-nonce' );

				if( ! empty( $_FILES['upload-font-file'] ) && current_user_can( 'manage_options' ) ) {

					$sanitized_file_name = sanitize_file_name( $_FILES['upload-font-file']['name'] );

					if( preg_match(
						'/([^\s]+)\.(ttf|otf|eot|woff|woff2)$/',
						$sanitized_file_name,
						$matched
					) ) {

						$font_name = sanitize_file_name( $matched[ 1 ] );
						$file_tail = $matched[ 2 ];
						$file_path = $this->upload_dir['basedir'] . "/custom-fonts/{$sanitized_file_name}";

						//print_r( $this->font_files_list[ $font_name ] );

						if( ! empty( $this->font_files_list[ $font_name ] ) ) { 

							if( in_array( $matched[ 0 ], $this->font_files_list[ $font_name ] ) ) {
							
							} else {

								// move_uploaded_file()
								$wp_filesystem->move(
									$_FILES['upload-font-file']['tmp_name'],
									$file_path
								);

								$this->font_files_list[ $font_name ][ $file_tail ] = $sanitized_file_name;

							}

						} else {

							$wp_filesystem->move(
								$_FILES['upload-font-file'] ['tmp_name'],
								$file_path
							);

							$this->font_files_list[ $font_name ][ $file_tail ] = $sanitized_file_name;

						}

						$wp_filesystem->chmod( $file_path, 0644 );

					}

				}

			}

			# Delete
			function remove_font_file( $wp_filesystem ) {

				check_admin_referer( 'remove-font-file-check', 'remove-font-file-check-nonce' );

				$fontdir_list = $wp_filesystem->dirlist( $this->fonts_dir );

				if( is_array( $fontdir_list ) ) {
					foreach( $fontdir_list as $index => $data ) {
						$font_file_names[] = $index;
					}
				}

				if( ! isset( $_POST['target-font-name'][ 0 ] ) ) {
				} else {

					if( is_array( $_POST['target-font-name'] ) ) { foreach( $_POST['target-font-name'] as $index => $font_family ) {

						if( is_array( $this->font_files_list[ $font_family ] ) ) { foreach( $this->font_files_list[ $font_family ] as $index => $file_name ) {
							
							$wp_filesystem->delete( $this->upload_dir['basedir'] . "/custom-fonts/{$file_name}" );

						} }

						unset( $this->font_files_list[ $font_family ] );

					} }

				}

			}

		/**
		 * Save Font Face
		**/
		function save_font_face_style( $wp_filesystem ) {
			
			if( ! empty( $this->font_files_list ) ) { foreach( $this->font_files_list as $font_family => $files ) {
				if( ! empty( $files ) ) { foreach( $files as $file_tail => $file_name ) {
					$this->font_files_list[ $font_family ][ $file_tail ] = sanitize_file_name( $file_name );
				} }
			} }

			# Save
				update_option( sse()->get_prefixed_option_name( 'custom_fonts' ), $this->font_files_list );

		}

}

