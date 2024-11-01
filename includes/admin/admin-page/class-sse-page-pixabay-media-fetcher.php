<?php
if( ! defined( 'ABSPATH' ) ) exit; 

class SSE_Page_Pixabay_Media_Fetcher extends SSE_Page_Abstract {

	/**
	 * Static
	**/
		protected static $instance;

	/**
	 * Properties
	**/
		/**
		 * Page
		 * @var string
		**/
		protected $admin_page = 'upload.php';

		/**
		 * Menu Slut
		 * @var string
		**/
		protected $menu_slug = 'pixabay_image_fetcher';

		/**
		 * Form Template 
		 * @var string
		**/
		protected $form_template = SHAPESHIFTER_EXTENSIONS_INCLUDES_DIR . 'admin/admin-page/view/page-pixabay-media-fetcher.php';

		/**
		 * Options
		 * @var array
		**/
		protected $options = array();

		/**
		 * Upload dirs
		 * @var string[]
		**/
		protected $upload_dir = array();

	/**
	 * Init
	**/
		/**
		 * Public initializer
		 * @return SSE_Pixabay_Media_Fetcher
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

			$this->options = sse()->get_options();

			$this->init();
			$this->init_hooks();

		}

		protected function init()
		{
			$this->page_title = esc_html__( "Pixabay Image Fetcher", ShapeShifter_Extensions::TEXTDOMAIN );
			$this->menu_title = esc_html__( "Pixabay Image Fetcher", ShapeShifter_Extensions::TEXTDOMAIN );
		}

		protected function init_hooks() {

			parent::init_hooks();

			# Admin Footer Template
				add_action( 'admin_print_footer_scripts', array( $this, 'admin_print_footer_scripts' ) );

			# AJAX
				add_action( 'wp_ajax_shapeshifter_save_pixabay_save_api_key', array( $this, 'shapeshifter_save_pixabay_save_api_key' ) );
				add_action( 'wp_ajax_shapeshifter_import_pixabay_images', array( 'SSE_Page_Pixabay_Media_Fetcher', 'shapeshifter_import_pixabay_images' ) );
				add_action( 'wp_ajax_shapeshifter_save_pixabay_image', array( 'SSE_Page_Pixabay_Media_Fetcher', 'shapeshifter_save_pixabay_image' ) );

		}

		# Scripts
		function admin_enqueue_scripts( $hook ) {

			wp_localize_script( 'sse-pixabay-media-fetcher', 'shapeshifterPixabayMediaFetcherObject', array(
				'adminURL' => admin_url()
			) );
			wp_enqueue_script( 'sse-pixabay-media-fetcher' );

		}

	/**
	 * Actions
	**/
		# Media Page
		function render() {
			parent::render();
		}

		# Admin Footer
		function admin_print_footer_scripts() {

			include_once( SHAPESHIFTER_EXTENSIONS_TEMPLATES_DIR . 'admin-js/pixabay-media-fetcher/results.php' );

		}

	#
	# AJAX
	#
		function shapeshifter_save_pixabay_save_api_key() {

			if( ! empty( $_REQUEST['ssNonce'] ) ) $ss_nonce = $_REQUEST['ssNonce'];
			if( ! empty( $_REQUEST['apiKey'] ) ) $api_key = $_REQUEST['apiKey'];

			wp_verify_nonce( $ss_nonce, 'pixabay-media-fetcher' );
			//check_ajax_referer( 'pixabay-media-fetcher', 'pixabay-media-fetcher-nonce' );

			update_option( sse()->get_prefixed_option_name( 'pixabay_api_key' ), sanitize_text_field( $api_key ) );

			wp_die( json_encode( array( 
				'message' => esc_html__( 'API Key Successfully Saved.', ShapeShifter_Extensions::TEXTDOMAIN )
			) ) );

		}

		public static function shapeshifter_import_pixabay_images() {

			set_time_limit(0);

			# Nonce
				if( ! empty( $_REQUEST['ssNonce'] ) ) $ss_nonce = $_REQUEST['ssNonce'];
				wp_verify_nonce( $ss_nonce, 'pixabay-media-fetcher' );
				//check_ajax_referer( 'pixabay-media-fetcher', 'pixabay-media-fetcher-nonce' );

			# Vars
				$images_data = $temp = array();
				if( ! empty( $_REQUEST['imagesData'] ) )
					$images_data = json_decode( stripslashes( $_REQUEST['imagesData'] ), true );
				if( empty( $images_data ) ) return;

			# Preparations
				$creds = SSE_Filesystem_Methods::ajax_init_filesystem( 'shapeshifter-media-fetcher' );
				if( ! $creds ) {
					wp_die( 'not cred' );
				}

			# Dir
				$current = time();
				$month = date( "m", $current );
				$year = date( "Y", $current );
				$upload_directory = wp_upload_dir();
				$baseurl = $upload_directory['baseurl'] . '/' . $year . '/' . $month . '/';
				$basedir = $upload_directory['basedir'] . '/' . $year . '/' . $month . '/';

			# Each Image
				$images_contents = array();
				foreach( $images_data as $index => $image_data ) {

					$images_contents[$index] = SSE_Page_Pixabay_Media_Fetcher::shapeshifter_save_pixabay_image( $image_data, $basedir, $baseurl );

				}

			wp_die( wp_json_encode( $images_contents ) );

		}

			public static function shapeshifter_save_pixabay_image( $image_data, $basedir, $baseurl ) {

				if( empty( $image_data ) ) return;

				global $wp_filesystem;

				# Vars
					$is_hd_image = ( isset( $image_data['imagefullhdurl'] ) ? true : false );

					$image_key = ( $is_hd_image ? 'imagefullhdurl' : 'imagewebformaturl' );
					$image_url = $image_data[$image_key];
					$file_type = wp_check_filetype( basename( $image_url ), null );

					$image_id = ( $is_hd_image ? $image_data['imageid_hash'] : $image_data['imageid'] );
					$image_name = sanitize_file_name( 'pixabay-' . $image_id . '.' . $file_type['ext'] );
					$saved_image_file_path = $basedir . $image_name;
					$image_headers = get_headers( $image_url, 1 );

				# Check if Exists
					if( file_exists( $saved_image_file_path ) ) {
						return array(
							'message' => 'exists',
						);
					}

				# Upload
					$image_contents = $wp_filesystem->get_contents( $image_url );
					$is_success = $wp_filesystem->put_contents( $saved_image_file_path, $image_contents );
					if( ! $is_success ) return;

				# Size
					$image_size = getimagesize( $saved_image_file_path );

				# Registering as WP Attachment
					# Vars
						$attachment = array(
							'guid' => $baseurl . $image_name,
							'post_mime_type' => $file_type['type'],
							'post_title' => preg_replace( '/\.[^.]+$/', '', basename( $image_url ) ),
							'post_content' => '',
							'post_status' => 'inherit'
						);
					# Register
						$attachment_id = wp_insert_attachment( $attachment, $saved_image_file_path );
	
					# Meta
						# Include for Usage of wp_generate_attachment_metadata()
							include_once( ABSPATH . 'wp-admin/includes/image.php' );

						# Generate MetaData and Update Database
							$attachment_data = wp_generate_attachment_metadata( $attachment_id, $saved_image_file_path );
							wp_update_attachment_metadata( $attachment_id, $attachment_data );

					return array(
						'message' => esc_html__( 'Inserted', ShapeShifter_Extensions::TEXTDOMAIN ),
						'path' => $saved_image_file_path,
						'mime-type' => $file_type,
						'image-size' => $image_size,
						'image-header' => $image_headers,
						'attachment-data' => $attachment_data,
						'meta' => wp_read_image_metadata( $saved_image_file_path )
					);

			}

}


