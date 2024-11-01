<?php
if ( ! defined( 'ABSPATH' ) ) exit;

class SSE_Metabox_SEO extends SSE_Metabox_Abstract {

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
		 * Instance of this Class
		 * 
		 * @var $defualts
		**/
		protected static $defualts = array(
			'is_seo_meta_on'       => '',
			'seo_meta_robots'      => 'index,follow',
			'seo_meta_description' => '',
			'seo_meta_keywords'    => '',
		);

	/**
	 * Properties
	**/
		/**
		 * Slug
		 * @var string
		**/
		protected $id = 'seo';

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
				esc_html__( '%s - SEO Settings', ShapeShifter_Extensions::TEXTDOMAIN ),
				shapeshifter()->get_theme_data( 'Name' )
			);
		}

		public function admin_enqueue_scripts( $hook )
		{
			wp_enqueue_script( 'sse-metabox-seo' );
		}

		/**
		 * Add Metaboxes
		 * @param WP_Post $post
		 * @param array $args Default array()
		**/
		function render( $post, $args = array() ) {
			
			wp_nonce_field( esc_attr( sse()->get_prefixed_post_meta_name( 'save_meta_box_data' ) ), esc_attr( sse()->get_prefixed_post_meta_name( 'meta_box_nonce' ) ) );
		
			// Get Saved Data
				$saved_seo = get_post_meta( $post->ID, sse()->get_prefixed_post_meta_name( 'seo_meta_json' ), true );
				$seo_meta_json = json_decode( $saved_seo, true );
			// Data
				$is_seo_meta_on = esc_attr( 
					! empty( $seo_meta_json['is_seo_meta_on'] )
					? $seo_meta_json['is_seo_meta_on']
					: self::$defualts['is_seo_meta_on']
				);
				$seo_meta_robots = esc_attr( 
					! empty( $seo_meta_json['seo_meta_robots'] )
					? $seo_meta_json['seo_meta_robots']
					: self::$defualts['seo_meta_robots']
				);
				$seo_meta_description = esc_html( 
					! empty( $seo_meta_json['seo_meta_description'] )
					? $seo_meta_json['seo_meta_description']
					: self::$defualts['seo_meta_description']
				);
				$seo_meta_keywords = esc_attr( 
					! empty( $seo_meta_json['seo_meta_keywords'] )
					? $seo_meta_json['seo_meta_keywords']
					: self::$defualts['seo_meta_keywords']
				);
			
			echo '<input type="hidden" id="shapeshifter-meta-box-seo-json" name="' . esc_attr( sse()->get_prefixed_post_meta_name( 'seo_meta_json' ) ) . '" mame="' . esc_attr( sse()->get_prefixed_post_meta_name( 'seo_meta_json' ) ) . '" value="">';

			
			echo '<table id="seo-meta-box-table">
			<tbody>';

			// メタボックスの設定を適用するか
				echo '<tr>
					<th><label for="' . esc_attr( sse()->get_prefixed_post_meta_name( 'is_seo_meta_output' ) ) . '">
						' . esc_html__( 'Print meta tags with settings below', ShapeShifter_Extensions::TEXTDOMAIN ) . '
					</label></th>
					<td><input 
						type="checkbox" 
						id="' . esc_attr( sse()->get_prefixed_post_meta_name( 'is_seo_meta_on' ) ) . '"
						class="shapeshifter-meta-box-seo" 
						name="' . esc_attr( sse()->get_prefixed_post_meta_name( 'is_seo_meta_on' ) ) . '" 
						index="is_seo_meta_on"
						value="is_seo_meta_on" 
						' . checked( $is_seo_meta_on, 'is_seo_meta_on', false ) . ' 
					/></td>
				</tr>';//以下の設定で<br />メタタグを出力する
				
			// ロボットメタの設定
				echo '<tr>
					<th><label for="' . esc_attr( sse()->get_prefixed_post_meta_name( 'seo_meta_robots' ) ) . '">
						' . esc_html__( 'Robots Meta', ShapeShifter_Extensions::TEXTDOMAIN ) . '
					</label></th>
					<td><select 
						id="' . esc_attr( SSE_THEME_PREFIX . 'seo_meta_robots' ) . '" 
						class="shapeshifter-meta-box-seo" 
						name="' . esc_attr( sse()->get_prefixed_post_meta_name( 'seo_meta_robots' ) ) . '"
						index="seo_meta_robots"
					>
						<option value="index,follow" ' . selected( $seo_meta_robots, 'index,follow', false ) . '>
							index,follow
						</option>
						<option value="noindex,follow" ' . selected( $seo_meta_robots, 'noindex,follow', false ) . '>
							noindex,follow,noarchive
						</option>
						<option value="noindex,nofollow" ' . selected( $seo_meta_robots, 'noindex,nofollow', false  ) . '>
							noindex,nofollow,noarchive
						</option>
						
					 </select></td>
				 </tr>';

			// 詳細メタの設定
				echo '<tr>
					<th><label for="' . esc_attr( sse()->get_prefixed_post_meta_name( 'seo_meta_description' ) ) . '">
						' . esc_html__( 'Meta Description', ShapeShifter_Extensions::TEXTDOMAIN ) . '
					</label></th>
					<td><textarea 
						id="' . esc_attr( sse()->get_prefixed_post_meta_name( 'seo_meta_description' ) ) . '" 
						class="shapeshifter-meta-box-seo" 
						name="' . esc_attr( sse()->get_prefixed_post_meta_name( 'seo_meta_description' ) ) . '"
						index="seo_meta_description"
					>' . $seo_meta_description . '</textarea></td>
				</tr>';

			// キーワードメタの設定
				echo '<tr>
					<th><label for="' . esc_attr( sse()->get_prefixed_post_meta_name( 'seo_meta_keywords' ) ) . '">
						' . esc_html__( 'Meta Keywords', ShapeShifter_Extensions::TEXTDOMAIN ) . '
					</label></th>
					<td><input
						type="text" 
						id="' . esc_attr( sse()->get_prefixed_post_meta_name( 'seo_meta_keywords' ) ) . '" 
						class="shapeshifter-meta-box-seo" 
						name="' . esc_attr( sse()->get_prefixed_post_meta_name( 'seo_meta_keywords' ) ) . '" 
						index="seo_meta_keywords"
						value="' . $seo_meta_keywords . '" 
					/></td>
				</tr>';

			echo '</tbody></table>';

		}
	


		/**
		 * Save the Settings
		 * 
		 * @param int $post_id
		**/
		function save_metabox_settings( $post_id ) {

			if ( empty( $_POST[ sse()->get_prefixed_post_meta_name( 'meta_box_nonce' ) ] ) )
				return $post_id;

			check_admin_referer( sse()->get_prefixed_post_meta_name( 'save_meta_box_data' ), sse()->get_prefixed_post_meta_name( 'meta_box_nonce' ) );

			if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) 
				return $post_id;
			
			if ( 'page' == $_POST['post_type'] ) {

				if ( ! current_user_can( 'edit_page', $post_id ) )
					return $post_id;
		
			} else {

				if ( ! current_user_can( 'edit_post', $post_id ) )
					return $post_id;

			}

			// Save as JSON String
				$seo_settings_json = (
					isset( $_POST[ sse()->get_prefixed_post_meta_name( 'seo_meta_json' ) ] )
					? $_POST[ sse()->get_prefixed_post_meta_name( 'seo_meta_json' ) ]
					: '{}' 
				);
				$seo_settings = json_decode( str_replace( '\\"', '"', $seo_settings_json ), true );
				update_post_meta( $post_id, sse()->get_prefixed_post_meta_name( 'seo_meta_json' ), $seo_settings );
				foreach ( $seo_settings as $index => $value ) {
					$value = sanitize_text_field( $value );
					$seo_settings[ $index ] = $value;
				}
				$seo_settings_json = json_encode( $seo_settings, JSON_UNESCAPED_UNICODE );
				update_post_meta( $post_id, sse()->get_prefixed_post_meta_name( 'seo_meta_json' ), $seo_settings_json );
			
		}

	/**
	 * Sanitize
	**/
		/**
		 * Save the Settings
		 * @param string $is_seo_meta_on
		 * @return string
		**/
		public function sanitize_is_seo_meta_on( $is_seo_meta_on )
		{
			if ( is_string( $is_seo_meta_on )
				&& 'is_seo_meta_on' !== $is_seo_meta_on
			) {
				return $is_seo_meta_on;
			}
			return self::$defualts['is_seo_meta_on'];
		}

		/**
		 * Save the Settings
		 * @param string $seo_meta_robots
		 * @return string
		**/
		public function sanitize_seo_meta_robots( $seo_meta_robots )
		{
			if ( is_string( $seo_meta_robots )
				&& '' !== $seo_meta_robots
			) {
				return $seo_meta_robots;
			}
			return self::$defualts['seo_meta_robots'];
		}

		/**
		 * Save the Settings
		 * @param string $seo_meta_description
		 * @return string
		**/
		public function sanitize_seo_meta_description( $seo_meta_description )
		{
			if ( is_string( $seo_meta_description )
				&& '' !== $seo_meta_description
			) {
				return $seo_meta_description;
			}
			return self::$defualts['seo_meta_description'];
		}

		/**
		 * Save the Settings
		 * @param string $seo_meta_keywords
		 * @return string
		**/
		public function sanitize_seo_meta_keywords( $seo_meta_keywords )
		{
			if ( is_string( $seo_meta_keywords )
				&& '' !== $seo_meta_keywords
			) {
				return $seo_meta_keywords;
			}
			return self::$defualts['seo_meta_keywords'];
		}


	
}


