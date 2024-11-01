<?php
if ( ! defined( 'ABSPATH' ) ) exit;

class SSE_Metabox_FontAwesome extends SSE_Metabox_Abstract {

	/**
	 * Static
	**/
		/**
		 * Instance of this Class
		 * 
		 * @var $instance
		**/
		protected static $instance = null;

	/**
	 * Properties
	**/
		/**
		 * Slug
		 * @var string
		**/
		protected $id = 'headline-icons';

		/**
		 * Title
		 * @var string
		**/
		protected $title;

		/**
		 * Title
		 * @var string[]
		**/
		protected $post_types = array( 'post', 'page' );

		/**
		 * Context : 'normal', 'side' and 'advanced'
		**/
		protected $context = 'side';

		/**
		 * Icons
		**/
		protected $icons = array();

		/**
		 * Defaults
		**/
		protected $icon_default_base  = array(
			'h1' => 'f1b2',
			'h2' => 'f04b',
			'h3' => 'f0d0',
			'h4' => 'none',
			'h5' => 'none',
			'h6' => 'none',
		);

		protected $icon_defaults  = array();

		protected $color_default_base = array(
			'h1' => '#000000',
			'h2' => '#000000',
			'h3' => '#000000',
			'h4' => '#000000',
			'h5' => '#000000',
			'h6' => '#000000',
		);

		protected $color_defaults = array();


	/**
	 * Init
	**/
		/**
		 * Public Initializer
		**/
		public static function get_instance()
		{
			if ( null === self::$instance ) self::$instance = new Self();
			return self::$instance;
		}

		/**
		 * Please Define $this->title'
		**/
		protected function init()
		{

			$this->title = sprintf( 
				esc_html__( '%s - FontAwesome Settings for Title and Headlines', ShapeShifter_Extensions::TEXTDOMAIN ),
				shapeshifter()->get_theme_data( 'Name' )
			);

			// Headlines List
			$this->headlines = array(
				'h1' => esc_html__( 'Title', ShapeShifter_Extensions::TEXTDOMAIN ),//ã‚¿ã‚¤ãã«
				'h2' => 'h2',
				'h3' => 'h3',
				'h4' => 'h4',
				'h5' => 'h5',
				'h6' => 'h6'
			);

			$this->icons = sse()->get_option( 'icons' )->get_data();

		}

		public function admin_enqueue_scripts( $hook )
		{
			if ( ! is_admin() && ! is_customize_preview() ) {
				return;
			}

			wp_enqueue_style( 'sse-font-awesome' );
			wp_enqueue_script( 'sse-metabox-icons-preview' );

		}

	/**
	 * Action
	**/
		/**
		 * Add Metaboxes
		 * @param WP_Post $post
		 * @param array $args Default array()
		**/
		public function render( $post, $args = array() ) {
			include( 'view/template-metabox-fontawesome.php' );
		}

		/**
		 * Save metabox settings
		 * @param int $post_id
		**/
		public function save_metabox_settings( $post_id )
		{

			// Prepare
				if ( ! isset( $_POST[ sse()->get_prefixed_theme_post_meta_name( 'meta_icons_box_nonce' ) ] ) ) {
					return;
				}
			
				if ( ! wp_verify_nonce( $_POST[ sse()->get_prefixed_theme_post_meta_name( 'meta_icons_box_nonce' ) ], sse()->get_prefixed_theme_post_meta_name( 'meta_icons_box' ) ) ) {
					return;
				}
			
				if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) {
					return;
				}
			
				if ( isset( $_POST['post_type'] ) && 'page' == $_POST['post_type'] ) {
			
					if ( ! current_user_can( 'edit_page', $post_id ) ) {
						return;
					}
			
				} else {
			
					if ( ! current_user_can( 'edit_post', $post_id ) ) {
						return;
					}

				}
			
			// Icon Mods Bool
				$key = sse()->get_prefixed_theme_post_meta_name( 'is_fa_icons_mods_on' );
				$is_fa_icons_mods_on = sanitize_text_field( 
					( isset( $_POST[ $key ] ) 
						&& '' !== $_POST[ $key ]
					)
					? $_POST[ $key ]
					: ''
				);
				update_post_meta( $post_id, sse()->get_prefixed_theme_post_meta_name( 'is_fa_icons_mods_on' ), $is_fa_icons_mods_on );

			// Each Headlines
				$headlines = array( 'h1', 'h2', 'h3', 'h4', 'h5', 'h6' );
				foreach( $headlines as $hl ) {

					// Icon Select
					$key = sse()->get_prefixed_theme_post_meta_name( $hl . '_icons_select' );
					$icons_select = sanitize_text_field( 
						( isset( $_POST[ $key ] )
							&& is_string( $_POST[ $key ] )
						)
						? $_POST[ $key ]
						: 'none'
					);
					update_post_meta( $post_id, sse()->get_prefixed_theme_post_meta_name( $hl . '_icons_select' ), $icons_select );

					// Icon Color
					$key = sse()->get_prefixed_theme_post_meta_name( $hl . '_icons_color' );
					$icons_color = sanitize_text_field( 
						isset( $_POST[ $key ] )
						? $_POST[ $key ]
						: ''
					);
					update_post_meta( $post_id, sse()->get_prefixed_theme_post_meta_name( $hl . '_icons_color' ), $icons_color );

				}

			parent::save_metabox_settings( $post_id );

		}

}

