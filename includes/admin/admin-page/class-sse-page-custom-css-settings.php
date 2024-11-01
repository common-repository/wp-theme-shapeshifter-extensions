<?php
if( ! defined( 'ABSPATH' ) ) exit; 

if ( ! class_exists( 'SSE_Page_Custom_CSS_Settings' ) ) {

class SSE_Page_Custom_CSS_Settings extends SSE_Page_Abstract {

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
		protected $menu_slug = 'css_settings_menu';

		/**
		 * Form Template 
		 * @var string
		**/
		protected $form_template = 'view/form-custom-css-settings.php';

		protected $options = array();

		protected $css_files_list = array();
		protected $applied_css_files_list = array();

		protected $upload_dir = array();
		protected $css_dir;

	/**
	 * Init
	**/
		/**
		 * Public Initializer
		**/
		public static function get_instance() {
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
			$this->page_title = esc_html__( "Custom CSS", ShapeShifter_Extensions::TEXTDOMAIN );
			$this->menu_title = esc_html__( "Custom CSS", ShapeShifter_Extensions::TEXTDOMAIN );
		}

	#
	# Actions
	#
		/**
		 * Message
		**/
		function option_update_message() {

			if( isset( $_POST['submit-upload-css-file'] ) ) {

				if( ! ( isset( $_FILES['upload-css-file'] ) && current_user_can( 'manage_options' ) ) ) {

					echo '<div class="updated"><p class="message">' . esc_html__( 'File to upload is NOT selected.', ShapeShifter_Extensions::TEXTDOMAIN ) . '</p></div>';

				} else {

					check_admin_referer( 'upload-css-file-check', 'upload-css-file-check-nonce' ); 

					echo '<div class="updated"><p class="message">' . esc_html__( 'File was successfully Uploaded.', ShapeShifter_Extensions::TEXTDOMAIN ) . '</p></div>';

				}

			} elseif( isset( $_POST['submit-remove-css-files'] ) && $_POST['submit-remove-css-files'] ) {

				if( ! isset( $_POST['target-css-name'][ 0 ] ) ) {

					echo '<div class="updated"><p class="message">' . esc_html__( 'Files to remove are NOT selected.', ShapeShifter_Extensions::TEXTDOMAIN ) . '</p></div>';

				} else {

					check_admin_referer( 'remove-css-file-check', 'remove-css-file-check-nonce' );

					echo '<div class="updated"><p class="message">' . esc_html__( 'Selected files were successfully removed.', ShapeShifter_Extensions::TEXTDOMAIN ) . '</p></div>';

				}

			} elseif( isset( $_POST['submit-apply-css-files'] ) && $_POST['submit-apply-css-files'] ) {

				echo '<div class="updated"><p class="message">' . esc_html__( 'Settings Saved.', ShapeShifter_Extensions::TEXTDOMAIN ) . '</p></div>';

			}

		}

		// Setting and Update
		function admin_menu() {
			$this->manage_css_dir();
			$this->applied_css_files_list = get_option( sse()->get_prefixed_theme_option_name( 'apply_selected_css' ) );
			parent::admin_menu();
		}

	#
	# Dir Related
	#
		/**
		 * Font Settings
		**/
		function manage_css_dir() {

			// ファイルシステム初期化
				$is_filesystem_init = SSE_Filesystem_Methods::init_file_system(
					admin_url( 'themes.php?page=sse_css_settings_menu' ),
					SHAPESHIFTER_EXTENSIONS_PREFIX . 'custom-css'
				);

				if ( ! $is_filesystem_init ) return;

				global $wp_filesystem;

			// 変数など設定
				$this->setup_css_files( $wp_filesystem );

			// ファイル操作
				if( isset( $_POST['submit-upload-css-file'] ) && $_POST['submit-upload-css-file'] ) {

					$this->upload_css_file( $wp_filesystem );

				} elseif( isset( $_POST['submit-remove-css-files'] ) && $_POST['submit-remove-css-files'] ) {

					$this->remove_css_file( $wp_filesystem );

				} elseif( isset( $_POST['submit-apply-css-files'] ) && $_POST['submit-apply-css-files'] ) {

				
					$this->apply_css_files();

				}

		}

		// Init CSS Files
		function setup_css_files( $wp_filesystem ) {

			// アップロード用のディレクトリの作成
				$this->upload_dir = wp_upload_dir();
				$css_dir = $this->upload_dir['basedir'] . '/custom-css';
				$this->css_dir = $css_dir;
				//echo $css_dir;
				if( ! $wp_filesystem->is_dir( $css_dir ) ) {
					//echo 'no dir';
					$result = $wp_filesystem->mkdir( $css_dir, 0755 );
					if( $result ) {
						//echo 'created directory "custom-css"';
					}
				}

			// フォントファイルの取得
				$css_files_list = $wp_filesystem->dirlist( $css_dir );

				foreach( $css_files_list as $index => $css_file ) {
					if( preg_match( '/([^\s]+)\.css$/', $css_file['name'], $matched ) ) {

						$css_name = $matched[ 1 ];
						$this->css_files_list[ $css_name ] = sanitize_file_name( $matched[ 0 ] );

					}
				} //print_r( $this->css_files_list ); // チェック用

		}

		/**
		 * Uplaod CSS Files
		**/
		function upload_css_file( $wp_filesystem ) {

			check_admin_referer( 'upload-css-file-check', 'upload-css-file-check-nonce' );

			if( ! empty( $_FILES['upload-css-file'] ) && current_user_can( 'manage_options' ) ) {

				$uploaded_file_name = sanitize_file_name( $_FILES['upload-css-file']['name'] );

				if( preg_match( '/([^\s]+)\.css$/', $uploaded_file_name, $matched ) ) {

					$css_name = sanitize_file_name( $matched[ 1 ] );
					$file_path = $this->upload_dir['basedir'] . "/custom-css/{$css_name}.css";

					// move_uploaded_file()
					$wp_filesystem->move(
						$_FILES['upload-css-file']['tmp_name'],
						$file_path,
						true
					);

					$this->css_files_list[ $css_name ] = sanitize_file_name( $matched[ 0 ] );

					$wp_filesystem->chmod( $file_path, 0644 );

				}

			}

		}

		/**
		 * Delete CSS Files
		**/
		function remove_css_file( $wp_filesystem ) {

			check_admin_referer( 'remove-css-file-check', 'remove-css-file-check-nonce' );

			$cssdir_list = $wp_filesystem->dirlist( $this->css_dir );

			if( is_array( $cssdir_list ) ) {
				foreach( $cssdir_list as $index => $data ) {
					if( preg_match( '/([^\s]+)\.css$/', $index, $matched ) ) {
						$css_file_names[] = $matched[ 1 ];
					}
				}
			}

			if( ! isset( $_POST['target-css-name'][ 0 ] ) ) {
			} else {

				foreach( $_POST['target-css-name'] as $index => $css_name ) {

					if( ! in_array( $css_name, $css_file_names ) ) continue;

					$wp_filesystem->delete( $this->upload_dir['basedir'] . "/custom-css/{$css_name}.css" );

					unset( $this->css_files_list[ $css_name ] );

				}

			}

		}
		/**
		 * Apply CSS Files
		**/
		function apply_css_files() {

			check_admin_referer( 'apply-css-file-check', 'apply-css-file-check-nonce' );

			$css_files_list = array();

			if( isset( $_POST['apply-css-file-name'] ) ) { foreach( $_POST['apply-css-file-name'] as $index => $css_file ) {

				$file_name = sanitize_file_name( $css_file );
					
				$css_files_list[ $file_name ] = $file_name;

			} }

			// 設定を保存
				update_option( sse()->get_prefixed_theme_option_name( 'apply_selected_css' ), $css_files_list );

		}

}
}


?>