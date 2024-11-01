<?php
if ( ! defined( 'ABSPATH' ) ) exit;

class SSE_Metabox_Subcontents extends SSE_Metabox_Abstract {

	/**
	 * Static
	**/
		/**
		 * Instance of this Class
		 * @var $instance
		**/
		protected static $instance = null;

		/**
		 * Item types
		 * @var array
		**/
		public static $item_types = array();

		/**
		 * Post Formats
		 * @var array
		**/
		public static $post_formats = array();

	/**
	 * Properties
	**/
		/**
		 * Slug
		 * @var string
		**/
		protected $id = 'subcontents';

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
		 * Post Meta
		 * @var SSE_Data_Post_Meta
		**/
		protected $data_postmeta = array();

		/**
		 * Post Meta
		 * @var array
		**/
		protected $postmeta = array();

		/**
		 * Post Meta Index
		 * @var string
		**/
		protected $postmeta_name = '';

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

			self::$item_types = array(
				'slider' => esc_html__( 'Slider', ShapeShifter_Extensions::TEXTDOMAIN ),
				'widget_from_widget_areas' => esc_html__( 'Widget from Widget Areas', ShapeShifter_Extensions::TEXTDOMAIN ),
				'new_registered_widget' => esc_html__( 'New Registered Widget', ShapeShifter_Extensions::TEXTDOMAIN )
			);

			self::$post_formats = array(
				'standard' => esc_html__( 'Standard', ShapeShifter_Extensions::TEXTDOMAIN ),
				'aside' => esc_html__( 'Aisde', ShapeShifter_Extensions::TEXTDOMAIN ),
				'gallery' => esc_html__( 'Gallary', ShapeShifter_Extensions::TEXTDOMAIN ),
				'image' => esc_html__( 'Image', ShapeShifter_Extensions::TEXTDOMAIN ),
				'link' => esc_html__( 'Link', ShapeShifter_Extensions::TEXTDOMAIN ),
				'quote' => esc_html__( 'Quote', ShapeShifter_Extensions::TEXTDOMAIN ),
				'status' => esc_html__( 'Status', ShapeShifter_Extensions::TEXTDOMAIN ),
				'video' => esc_html__( 'Video', ShapeShifter_Extensions::TEXTDOMAIN ),
				'audio' => esc_html__( 'Audio', ShapeShifter_Extensions::TEXTDOMAIN ),
				'chat' => esc_html__( 'Chat', ShapeShifter_Extensions::TEXTDOMAIN ),
			);

			$this->postmeta_name = sse()->get_prefixed_post_meta_name( 'sub_contents_json' );

			$this->title = sprintf( 
				esc_html__( '%s Settings for Subcontents in Widget Areas', ShapeShifter_Extensions::TEXTDOMAIN ),
				shapeshifter()->get_theme_data( 'Name' )
			);


			$this->widget_settings = array();

		}

		/**
		 * Add Action
		**/
		protected function init_hooks()
		{
			//parent::init_hooks();
			add_action( 'add_meta_boxes', array( $this, 'add_meta_boxes' ), 10, 2 );
			add_action( 'admin_enqueue_scripts', array( $this, 'admin_enqueue_scripts' ) );
			add_action( 'wp_ajax_print_table_row', array( $this, 'print_table_row' ) );
			add_action( 'wp_ajax_save_to_output_widget_areas', array( $this, 'save_metabox_settings' ) );
			// AJAX Save Post Meta( Triggered by Parent )
		}

		/**
		 * Enqueue Scripts
		 * @param string $hook
		**/
		protected function enqueue_scripts()
		{
			// CSS
				wp_enqueue_style( 'sse-widget-settings-form' );
				wp_enqueue_style( 'sse-pupup-settings' );

			// JS
				wp_enqueue_script( 'sse-metabox-subcontents' );

				wp_enqueue_script( 'sse-admin-widget-slide-gallery' );
				wp_enqueue_script( 'sse-widget-settings' );

		}

		/**
		 * Add Metaboxes
		 * @param WP_Post $post
		 * @param array $args Default array()
		**/
		public function render( $post, $args = array() ) {

			wp_nonce_field( sse()->get_prefixed_post_meta_name( 'save_to_output_in_widget_area' ), sse()->get_prefixed_post_meta_name( 'output_in_widget_area_meta_box_nonce' ) );

			$post_id = intval( $post->ID );
			$this->data_postmeta['sub_contents_json'] = SSE_Data_Post_Meta::get_instance( $post_id, 'sub_contents_json', array() );

			$data_sub_contents = $this->data_postmeta['sub_contents_json']->get_data();
			$sub_contents_arranged_json = SSE_Data_Post_Meta::get_instance( $post_id, 'sub_contents_arranged_json', array() )->get_data();

			// Subcontents
			$this->postmeta['sub_contents'] = $data_sub_contents;
			//$this->postmeta['sub_contents_arranged'] = SSE_Array_Methods::get_json_settings_assoc_array( $this->postmeta['sub_contents'] );

			//$this->postmeta['sub_contents_arranged_json'] = SSE_Data_Post_Meta::get_instance( $post_id, 'sub_contents_arranged_json', true );

			include( 'view/template-metabox-subcontents.php' );

		}

		/**
		 * Table Row
		 * 
		 * @param int    $post_id
		 * @param int    $item_num
		 * @param array  $item_data
		**/
		public function print_table_row( $post_id = 0, $item_num = 0, $item_data = array() ) {

			global $wp_registered_sidebars;
			$this->widget_areas = $wp_registered_sidebars;

			$post_id = absint( $post_id );
			$item_num = absint( $item_num );

			if( ! empty( $_REQUEST['post_id'] ) ) { $post_id = absint( $_REQUEST['post_id'] ); }
			if( ! empty( $_REQUEST['item_num'] ) ) { $item_num = absint( $_REQUEST['item_num'] ); }

			//$item_title_key = sse()->get_prefixed_post_meta_name( 'sub_content_item_title_' . $item_num );
			$item_title_key = 'sub_content_item_title';
			$item_title = esc_attr( 
				isset( $item_data[ $item_title_key ] )
				? $item_data[ $item_title_key ] 
				: sprintf( esc_html__( 'Item %d', ShapeShifter_Extensions::TEXTDOMAIN ), ( $item_num + 1 ) ) 
			);

			$item_type_key = 'sub_content_item_type';
			$item_type = esc_attr( 
				isset( $item_data[ $item_type_key ] ) 
				? $item_data[ $item_type_key ] 
				: 'none' 
			);

			$item_hook_key = 'sub_content_item_hook';
			$item_hook = esc_attr( 
				isset( $item_data[ $item_hook_key ] )
				? $item_data[ $item_hook_key ]
				: 'none' 
			);

			echo '<tr id="post-meta-row-item-' . $item_num . '" data-item-num="' . $item_num . '">';
				echo '<th class="check-column">';
					echo '<input type="checkbox" id="check-to-remove-item-' . $item_num . '" class="regular-checkbox selected-item-checkboxes-to-remove">';
				echo '</th>';
				
				echo '<td>';
					echo '<input class="shapeshifter-meta-box-optional-output" name="' . esc_attr( sse()->get_prefixed_post_meta_name( sprintf( 'sub_contents_json[%d][sub_content_item_title]', $item_num ) ) ) . '" type="text" value="' . esc_attr( $item_title ) . '">';
				echo '</td>';
				
				echo '<td>';
					echo '<select 
						id="sub-content-item-type-' . $item_num . '" 
						class="sub-content-item-type shapeshifter-meta-box-optional-output"
						name="' . esc_attr( sse()->get_prefixed_post_meta_name( sprintf( 'sub_contents_json[%d][sub_content_item_type]', $item_num ) ) ) . '"
						data-item-num="' . $item_num . '"
					>';
						echo '<option value="none">' . esc_html__( 'Please Select One.', ShapeShifter_Extensions::TEXTDOMAIN ) . '</option>';
						if( is_array( self::$item_types ) ) { foreach( self::$item_types as $index => $text ) {
							echo '<option value="' . esc_attr( $index ) . '" ' . ( $index == $item_type ? 'selected' : '' ) . '>';
								echo esc_html( $text );
							echo '</option>';
						} }
					echo '</select>';
				echo '</td>';
				
				echo '<td>';
					echo '<select 
						id="' . esc_attr( sse()->get_prefixed_post_meta_name( 'sub_content_item_hook_' . $item_num ) ) . '" 
						class="sub-content-item-hook shapeshifter-meta-box-optional-output"
						name="' . esc_attr( sse()->get_prefixed_post_meta_name( sprintf( 'sub_contents_json[%d][sub_content_item_hook]', $item_num ) ) ) . '"
						data-item-num="' . $item_num . '"
					>';
						echo '<option value="none">' . esc_html__( 'Please Select One.', ShapeShifter_Extensions::TEXTDOMAIN ) . '</option>';
						foreach( $wp_registered_sidebars as $id => $data ) {
							echo '<option value="' . esc_attr( $id ) . '" ' . ( $id == $item_hook ? 'selected' : '' ) . '>';
								echo esc_html( $data['name'] );
							echo '</option>';
						}
					echo '</select>';
				echo '</td>';
				
				echo '<td>';
					echo '<a 
						id="edit-meta-box-item-' . $item_num . '" 
						class="button-primary edit-meta-box-item" 
						href="javascript:void(0);" 
						data-item-num="' . $item_num . '"
					>' . esc_html__( 'Edit the Item', ShapeShifter_Extensions::TEXTDOMAIN ) . '</a>';
					echo '<div 
						id="meta-box-popup-menu-' . $item_num . '"
						class="meta-box-popup-menu"
					>';
						$this->popup_menu( $post_id, $item_num, $item_type, $item_data );
					echo '</div>';

				echo '</td>';
			echo '</tr>';

			if( isset( $_REQUEST['post_id'] ) ) { wp_die(); }
		
		}

			/**
			 * Popup Menu
			 * 
			 * @param int    $post_id
			 * @param int    $item_num
			 * @param string $item_type
			 * @param array  $item_data
			**/
			protected function popup_menu( $post_id, $item_num, $item_type, $item_data ) {

				$post_id = esc_attr( $post_id );
				$item_num = esc_attr( $item_num );

				echo '<div 
					class="post-meta-popup-menu"
				>';
					// Common
						// Data
							$device_detect_key = 'sub_content_device_detect';
							$device_detect = (
								isset( $item_data[ $device_detect_key ] )
								? $item_data[ $device_detect_key ]
								: array(
									'is_pc' => 'is_pc',
									'is_mobile' => 'is_mobile'
								)
							);
							$title_is_display_key = 'sub_content_title_is_display';
							$title_is_display = esc_attr( 
								isset( $item_data[ $title_is_display_key ] )
								? $item_data[ $title_is_display_key ]
								: ''
							);
							$text_color_key = 'sub_content_text_color';
							$text_color = esc_attr( 
								isset( $item_data[ $text_color_key ] ) 
								? $item_data[ $text_color_key ] 
								: '#000'
							);
							$background_images_key = 'sub_content_background_images';
							$background_images = esc_attr( 
								isset( $item_data[ $background_images_key ] )
								? $item_data[ $background_images_key ]
								: ''
							);
						// Call Method
							$this->form_generals( $post_id, $item_num, $device_detect, $title_is_display, $text_color, $background_images );

					// Slider
						// Data
							$slider_item_num_key = 'sub_content_slider_item_num';
							$slider_item_num = absint( 
								isset( $item_data[ $slider_item_num_key ] )
								? $item_data[ $slider_item_num_key ]
								: 3
							);
							$slider_type_key = 'sub_content_slider_type';
							$slider_type = esc_attr( 
								isset( $item_data[ $slider_type_key ] )
								? $item_data[ $slider_type_key ]
								: 'new_posts'
							);
							$style_type_key = 'sub_content_slider_style_type';
							$slider_style_type = (
								isset( $item_data[ $style_type_key ] )
								? $item_data[ $style_type_key ]
								: array(
									'sidecontrols' => 'sidecontrols',
									'buttons' => '',
									//'thumbnails' => ''
								)
							);
							$slider_post_formats_key = 'sub_content_slider_post_formats';
							$post_formats_is_display = ( 
								isset( $item_data[ $slider_post_formats_key ] )
								? $item_data[ $slider_post_formats_key ]
								: array(
									'standard' => 'standard',
									'aside' => 'aside',
									'gallery' => 'gallery',
									'image' => 'image',
									'link' => 'link',
									'quote' => 'quote',
									'status' => 'status',
									'video' => 'video',
									'audio' => 'audio',
									'chat' => 'chat'
								)
							);
						// Call Method
							$this->form_type_slider( $post_id, $item_num, $item_type, $slider_item_num, $slider_type, $slider_style_type, $post_formats_is_display );

					// Placed Widget
						// Data
							$selected_widget_area_key = 'sub_content_selected_widget_area';
							$selected_widget_area = esc_attr( 
								isset( $item_data[ $selected_widget_area_key ] )
								? $item_data[ $selected_widget_area_key ]
								: ''
							);
							$selected_widget_from_widget_area_key = 'sub_content_selected_widget_from_widget_area';
							$selected_widget_from_widget_area = esc_attr( 
								isset( $item_data[ $selected_widget_from_widget_area_key ] )
								? $item_data[ $selected_widget_from_widget_area_key ]
								: ''
							);
							$widget_item_data_key = 'sub_content_widget_item_data';
							$selected_widget_data = ( 
								isset( $item_data[ $widget_item_data_key ] )
								? $item_data[ $widget_item_data_key ]
								: array()
							);
						// Call Method
							$this->form_type_placed_widget( $post_id, $item_num, $item_type, $selected_widget_area, $selected_widget_from_widget_area, $selected_widget_data );

					// New Registered Widget
						// Data
							$new_registered_widget_select_key = 'sub_content_new_registered_widget_select';
							$new_registered_widget = esc_attr( 
								isset( $item_data[ $new_registered_widget_select_key ] )
								? $item_data[ $new_registered_widget_select_key ]
								: ''
							);
							$new_registered_widget_selected_class_key = 'sub_content_new_registered_widget_selected_class';
							$widget_class_name = (
								isset( $item_data[ $new_registered_widget_selected_class_key ] )
								? $item_data[ $new_registered_widget_selected_class_key ]
								: ''
							);
							$new_registered_widget_instance_key = 'sub_content_new_registered_widget_instance';
							$widget_instance = (
								isset( $item_data[ $new_registered_widget_instance_key ] )
								? $item_data[ $new_registered_widget_instance_key ]
								: array()
							);
						// Call Method
							$this->form_type_new_registered_widget( $post_id, $item_num, $item_type, $new_registered_widget, $widget_class_name, $widget_instance );

					// Close Button
						echo '<p style="clear: both;"><a id="close-post-meta-popup-menu-' . $item_num . '" class="button close-post-meta-popup-menu" href="javascript: void( 0 );">' . esc_html__( 'Close', ShapeShifter_Extensions::TEXTDOMAIN ) . '</a></p>';

				echo '</div>';

			}

				/**
				 * Common
				 * 
				 * @param int    $post_id
				 * @param int    $item_num
				 * @param array  $device_detect
				 * @param string $title_is_display
				 * @param string $text_color
				 * @param string $background_images
				**/
				protected function form_generals( $post_id, $item_num, $device_detect, $title_is_display, $text_color, $background_images ) {

					$post_id = absint( $post_id );
					$item_num = absint( $item_num );

					$image_preg_str = '/([^, ]+)/';
					$background_images_array = array();
					if( preg_match_all( $image_preg_str, $background_images, $image_preg_str_s ) ) {
						$background_images_array = $image_preg_str_s[ 0 ];
					}

					echo '<div 
						id="post-meta-item-generals-' . $item_num . '" 
						class="post-meta-item-generals" 
					>';

						echo '<label class="post-meta-setting-label">' . esc_html__( 'General Settings', ShapeShifter_Extensions::TEXTDOMAIN ) . '</label><br><br>';

						// Display in PC
						echo '<label for="' . esc_attr(	sse()->get_prefixed_post_meta_name( sprintf( 'sub_contents_json[%d][sub_content_device_detect][is_pc]', $item_num ) ) ) . '" class="post-meta-general-setting-label-for-checkbox">' . esc_html__( 'Display on PC', ShapeShifter_Extensions::TEXTDOMAIN ) . '</label>';
						echo '<input 
							type="checkbox" 
							name="' . esc_attr( sse()->get_prefixed_post_meta_name( sprintf( 'sub_contents_json[%d][sub_content_device_detect][is_pc]', $item_num ) ) ) . '" 
							id="' . esc_attr( sse()->get_prefixed_post_meta_name( sprintf( 'sub_contents_json[%d][sub_content_device_detect][is_pc]', $item_num ) ) ) . '" 
							class="shapeshifter-meta-box-optional-output"
							value="is_pc"
							' . ( $device_detect['is_pc'] == 'is_pc' ? 'checked' : '' ) . '
						><br>';

						// Display in Mobile
						echo '<label for="' . esc_attr( sse()->get_prefixed_post_meta_name( sprintf( 'sub_contents_json[%d][sub_content_device_detect][is_mobile]', $item_num ) ) ) . '" class="post-meta-general-setting-label-for-checkbox">' . esc_html__( 'Display on Mobile', ShapeShifter_Extensions::TEXTDOMAIN ) . '</label>';
						echo '<input 
							type="checkbox" 
							name="' . esc_attr( sse()->get_prefixed_post_meta_name( sprintf( 'sub_contents_json[%d][sub_content_device_detect][is_mobile]', $item_num ) ) ) . '" 
							id="' . esc_attr( sse()->get_prefixed_post_meta_name( sprintf( 'sub_contents_json[%d][sub_content_device_detect][is_mobile]', $item_num ) ) ) . '" 
							class="shapeshifter-meta-box-optional-output"
							value="is_mobile"
							' . ( $device_detect['is_mobile'] == 'is_mobile' ? 'checked' : '' ) . '
						><br>';

						// Title Display
						echo '<label for="' . esc_attr( sse()->get_prefixed_post_meta_name( sprintf( 'sub_contents_json[%d][sub_content_title_is_display]', $item_num ) ) ) . '" class="post-meta-general-setting-label-for-checkbox">' . esc_html__( 'Title Display', ShapeShifter_Extensions::TEXTDOMAIN ) . '</label>';
						echo '<input 
							type="checkbox" 
							name="' . esc_attr( sse()->get_prefixed_post_meta_name( sprintf( 'sub_contents_json[%d][sub_content_title_is_display]', $item_num ) ) ) . '" 
							id="' . esc_attr( sse()->get_prefixed_post_meta_name( sprintf( 'sub_contents_json[%d][sub_content_title_is_display]', $item_num ) ) ) . '" 
							value="title_is_display"
							class="shapeshifter-meta-box-optional-output"
							' . ( $title_is_display == 'title_is_display' ? 'checked' : '' ) . '
						><br>';

						// Text Color
						echo '<label for="' . esc_attr( sse()->get_prefixed_post_meta_name( sprintf( 'sub_contents_json[%d][sub_content_text_color]', $item_num ) ) ) . '" class="post-meta-general-setting-label-for-color">' . esc_html__( 'Text Color', ShapeShifter_Extensions::TEXTDOMAIN ) . '</label>';
						echo '<div class="post-meta-settings-color">';
							echo '<input name="' . esc_attr( sse()->get_prefixed_post_meta_name( sprintf( 'sub_contents_json[%d][sub_content_text_color]', $item_num ) ) ) . '" type="text" id="' . esc_attr( sse()->get_prefixed_post_meta_name( sprintf( 'sub_contents_json[%d][sub_content_text_color]', $item_num ) ) ) . '" class="alpha-color-picker shapeshifter-meta-box-optional-output" value="' . esc_attr( $text_color ) . '"><br>';
						echo '</div><div class="clearfix"></div>';

						// Background Image
						echo '<div id="image-settings-wrapper-general-' . $item_num . '" class="post-meta-image-settings-wrapper">';

							echo '<label for="' . esc_attr( sse()->get_prefixed_post_meta_name( sprintf( 'sub_contents_json[%d][sub_content_background_images]', $item_num ) ) ) . '" class="post-meta-setting-label">' . esc_html__( 'Background Image Set', ShapeShifter_Extensions::TEXTDOMAIN ) . '</label><br>';

							echo '<p class="post-meta-settings-description">' . __( 'Images will be background of this output by Vegas. <strong>* This settings Will Not be applied for Slider</strong>.', ShapeShifter_Extensions::TEXTDOMAIN ) . '</p>';

							echo '<input type="hidden" name="' . esc_attr( sse()->get_prefixed_post_meta_name( sprintf( 'sub_contents_json[%d][sub_content_background_images]', $item_num ) ) ) . '" id="' . esc_attr( sse()->get_prefixed_post_meta_name( sprintf( 'sub_contents_json[%d][sub_content_background_images]', $item_num ) ) ) . '" class="shapeshifter-meta-box-optional-output" value="' . esc_attr( $background_images ) . '">';
							echo '<div id="image-box-general-' . $item_num . '" class="image-box">';

								if( is_array( $background_images_array ) ) { foreach( $background_images_array as $index => $url ) {
									echo '<img class="post-meta-image" src="' . esc_url( $url ) . '" width="100" height="100">';
								} }

							echo '</div><div class="clearfix"></div>';
							echo '<p>';
								echo '<a class="button-primary post-meta-set-images" href="javascript: void( 0 );" data-input-hidden="' . esc_attr( sse()->get_prefixed_post_meta_name( sprintf( 'sub_contents_json[%d][sub_content_background_images]', $item_num ) ) ) . '" data-image-box="#image-box-general-' . $item_num . '">' . esc_html__( 'Select Images', ShapeShifter_Extensions::TEXTDOMAIN ) . '</a>';

								echo '&nbsp';

								echo '<button class="button post-meta-remove-images" data-input-hidden="#' . esc_attr( sse()->get_prefixed_post_meta_name( sprintf( 'sub_contents_json[%d][sub_content_background_images]', $item_num ) ) ) . '" data-image-box="#image-box-general-' . $item_num . '">' . esc_html__( 'Remove Images', ShapeShifter_Extensions::TEXTDOMAIN ) . '</button>';
							echo '</p>';
						echo '</div>';

					echo '</div>';

				}

				/**
				 * Slider
				 * 
				 * @param int    $post_id
				 * @param int    $item_num
				 * @param string $item_type
				 * @param int    $slider_item_num
				 * @param string $slider_type
				 * @param array  $slider_style_type
				 * @param array  $post_formats_is_display
				**/
				protected function form_type_slider( $post_id, $item_num, $item_type, $slider_item_num, $slider_type, $slider_style_type, $post_formats_is_display ) {

					$post_id = absint( $post_id );
					$item_num = absint( $item_num );

					echo '<div 
						id="post-meta-item-slider-' . $item_num . '" 
						class="post-meta-item-slider post-meta-item-form" 
						style="
							' . esc_attr( 'slider' !== $item_type ? 'display: none;' : '' ) . '
						" 
					>';

						echo '<label for="' . esc_attr( sse()->get_prefixed_post_meta_name( sprintf( 'sub_contents_json[%d][sub_content_slider_item_num]', $item_num ) ) ) . '" class="post-meta-setting-label">' . esc_html__( 'Slider Item Num', ShapeShifter_Extensions::TEXTDOMAIN ) . '</label><br>';
						echo '<input 
							type="number" 
							name="' . esc_attr( sse()->get_prefixed_post_meta_name( sprintf( 'sub_contents_json[%d][sub_content_slider_item_num]', $item_num ) ) ) . '" 
							class="regular-input-number shapeshifter-meta-box-optional-output"
							id="' . esc_attr( sse()->get_prefixed_post_meta_name( sprintf( 'sub_contents_json[%d][sub_content_slider_item_num]', $item_num ) ) ) . '" 
							value="' . esc_attr( $slider_item_num ) . '"
						><br>';

						echo '<label for="' . esc_attr( sse()->get_prefixed_post_meta_name( sprintf( 'sub_contents_json[%d][sub_content_slider_type]', $item_num ) ) ) . '" class="post-meta-setting-label">' . esc_html__( 'Slider Type', ShapeShifter_Extensions::TEXTDOMAIN ) . '</label><br>';
						echo '<select 
							id="' . esc_attr( sse()->get_prefixed_post_meta_name( sprintf( 'sub_contents_json[%d][sub_content_slider_type]', $item_num ) ) ) . '" 
							class="sub_content_slider_type shapeshifter-meta-box-optional-output"
							name="' . esc_attr( sse()->get_prefixed_post_meta_name( sprintf( 'sub_contents_json[%d][sub_content_slider_type]', $item_num ) ) ) . '"
						>';
							//echo '<option value="selected_pages" ' . ( 'selected_pages' == $slider_type ? 'selected' : '' ) . '>' . __( 'Selected Pages', ShapeShifter_Extensions::TEXTDOMAIN ) . '</option>';
							echo '<option value="new_posts" ' . esc_attr( 'new_posts' == $slider_type ? 'selected' : '' ) . '>' . esc_html__( 'New Posts', ShapeShifter_Extensions::TEXTDOMAIN ) . '</option>';
							echo '<option value="popular_posts" ' . esc_attr( 'popular_posts' == $slider_type ? 'selected' : '' ) . '>' . esc_html__( 'Popular Posts', ShapeShifter_Extensions::TEXTDOMAIN ) . '</option>';
						echo '</select><br>';

						$this->form_type_slider_styles( $post_id, $item_num, $item_type, $slider_style_type );

						$this->form_type_slider_post_formats( $post_id, $item_num, $item_type, $post_formats_is_display );

					echo '</div>';

				}

					/**
					 * Slider Settings
					 * 
					 * @param int    $post_id
					 * @param int    $item_num
					 * @param string $item_type
					 * @param array  $slider_style_type
					**/
					protected function form_type_slider_styles( $post_id, $item_num, $item_type, $slider_style_type ) {

						$post_id = absint( $post_id );
						$item_num = absint( $item_num );

						echo '<label for="' . esc_attr( sse()->get_prefixed_post_meta_name( sprintf( 'sub_contents_json[%d][sub_content_slider_style_type]', $item_num ) ) ) . '" class="post-meta-setting-label">' . esc_html__( 'Slider Side Controls', ShapeShifter_Extensions::TEXTDOMAIN ) . '</label><br><br>';
						echo '<label for="' . esc_attr( sse()->get_prefixed_post_meta_name( sprintf( 'sub_contents_json[%d][sub_content_slider_style_type][sidecontrols]', $item_num ) ) ) . '" class="post-meta-slider-setting-label">' . esc_html__( 'Slider Side Controls', ShapeShifter_Extensions::TEXTDOMAIN ) . '</label>';
						echo '<input 
							type="checkbox" 
							name="' . esc_attr( sse()->get_prefixed_post_meta_name( sprintf( 'sub_contents_json[%d][sub_content_slider_style_type][sidecontrols]', $item_num ) ) ) . '" 
							id="' . esc_attr( sse()->get_prefixed_post_meta_name( sprintf( 'sub_contents_json[%d][sub_content_slider_style_type][sidecontrols]', $item_num ) ) ) . '" 
							class="post-meta-checkbox-list shapeshifter-meta-box-optional-output" 
							value="sidecontrols"
							' . ( $slider_style_type['sidecontrols'] == 'sidecontrols' ? 'checked' : '' ) . '
						><br>';

						echo '<label for="' . esc_attr( sse()->get_prefixed_post_meta_name( sprintf( 'sub_contents_json[%d][sub_content_slider_style_type][buttons]', $item_num ) ) ) . '" class="post-meta-slider-setting-label">' . esc_html__( 'Slider Buttons', ShapeShifter_Extensions::TEXTDOMAIN ) . '</label>';
						echo '<input 
							type="checkbox" 
							name="' . esc_attr( sse()->get_prefixed_post_meta_name( sprintf( 'sub_contents_json[%d][sub_content_slider_style_type][buttons]', $item_num ) ) ) . '" 
							id="' . esc_attr( sse()->get_prefixed_post_meta_name( sprintf( 'sub_contents_json[%d][sub_content_slider_style_type][buttons]', $item_num ) ) ) . '" 
							class="post-meta-checkbox-list shapeshifter-meta-box-optional-output" 
							value="buttons"
							' . ( $slider_style_type['buttons'] == 'buttons' ? 'checked' : '' ) . '
						><br>';

						echo '<br>';
					}

					/**
					 * Post Formats
					 * 
					 * @param int    $post_id
					 * @param int    $item_num
					 * @param string $item_type
					 * @param array  $post_formats_is_display
					**/
					protected function form_type_slider_post_formats( $post_id, $item_num, $item_type, $post_formats_is_display ) {

						$post_id = absint( $post_id );
						$item_num = absint( $item_num );

						echo '<label for="' . esc_attr( sse()->get_prefixed_post_meta_name( 'sub_content_slider_post_formats_' . $item_num ) ) . '" class="post-meta-setting-label">' . esc_html__( 'Select Post Formats to Display', ShapeShifter_Extensions::TEXTDOMAIN ) . '</label><br><br>';

						foreach( self::$post_formats as $value => $text ) {

							echo '<label for="' . esc_attr( sse()->get_prefixed_post_meta_name( sprintf( 'sub_contents_json[%d][sub_content_slider_post_formats][%s]', $item_num, $value ) ) ) . '" class="post-meta-slider-setting-label">' . esc_html( $text ) . '</label>';
							echo '<input 
								type="checkbox" 
								name="' . esc_attr( sse()->get_prefixed_post_meta_name( sprintf( 'sub_contents_json[%d][sub_content_slider_post_formats][%s]', $item_num, $value ) ) ) . '" 
								id="' . esc_attr( sse()->get_prefixed_post_meta_name( sprintf( 'sub_contents_json[%d][sub_content_slider_post_formats][%s]', $item_num, $value ) ) ) . '" 
								class="post-meta-checkbox-list shapeshifter-meta-box-optional-output" 
								value="' . esc_attr( $value ) . '"
								' . ( $post_formats_is_display[ $value ] === $value ? 'checked' : '' ) . '
							><br>';

						}
						echo '<br>';

					}

				/**
				 * Item from Placed Widget
				 * 
				 * @param int    $post_id
				 * @param int    $item_num
				 * @param string $item_type
				 * @param string $selected_widget_area
				 * @param string $selected_widget_from_widget_area
				 * @param array  $selected_widget_data
				**/
				protected function form_type_placed_widget( $post_id, $item_num, $item_type, $selected_widget_area, $selected_widget_from_widget_area, $selected_widget_data = array() ) {

					global $wp_registered_widgets;

					$post_id = absint( $post_id );
					$item_num = absint( $item_num );

					//print_r( $selected_widget_data );

					# Setup
						# Widget
							global $wp_registered_sidebars;
							$this->widget_areas = $wp_registered_sidebars;

						foreach( $wp_registered_widgets as $index => $widget ) {

							//print_r( $widget );
							
							# Widget Object
								$widget_instance = $widget['callback'][ 0 ];
								$option_name = $widget_instance->option_name;

							# Widget ID
								$id_base = $widget_instance->id_base;

							# Options for Widget Class// ウィジェットクラス毎のオプション配列
								$options = get_option( $option_name, array() );
								$widget_class = get_class( $widget_instance );
								$widget_name_base = $widget_instance->name;

							if( is_array( $options ) ) { foreach( $options as $index2 => $data ) {
								
								if( ! is_array( $data ) || ! intval( $data ) ) continue;

								$id = $id_base . '-' . $index2;

								$this->widget_settings[ $id ] = $data;
								$this->widget_settings[ $id ]['widget_class'] = $widget_class;
								$this->widget_settings[ $id ]['widget_name_base'] = $widget_name_base;

							} }

						}

						# Placed Widgets
							//print_r( $this->widget_settings ); //チェック用

						# Widgets and Widget Areas 
							global $_wp_sidebars_widgets, $sidebars_widgets;

						# Select Widget Area
							$widget_area_select = '<label for="' . esc_attr( sse()->get_prefixed_post_meta_name( sprintf( 'sub_contents_json[%d][sub_content_selected_widget_area]', $item_num ) ) ) . '">' . esc_html__( 'Widget Area', ShapeShifter_Extensions::TEXTDOMAIN ) . '</label>: ';
							$widget_area_select .= '<select 
								name="' . esc_attr( sse()->get_prefixed_post_meta_name( sprintf( 'sub_contents_json[%d][sub_content_selected_widget_area]', $item_num ) ) ) . '" 
								id="' . esc_attr( sse()->get_prefixed_post_meta_name( sprintf( 'sub_contents_json[%d][sub_content_selected_widget_area]', $item_num ) ) ) . '" 
								class="post-meta-widget-area-select shapeshifter-meta-box-optional-output"
								data-item-num="' . $item_num . '"
							>
								<option value="none">' . esc_html__( 'Select One', ShapeShifter_Extensions::TEXTDOMAIN ) . '</option>';

						// OPTION tags Loop
						$widget_select = '';
						$widget_select_options = array();
						foreach( $sidebars_widgets as $widget_area => $widgets ) {

							# Assoc Array of Sidebars Name
								if( isset( $this->widget_areas[ $widget_area ] ) ) {
									$sidebars[ $widget_area ] = $this->widget_areas[ $widget_area ];
								} elseif( $widget_area == 'wp_inactive_widgets' ) {
									$sidebars[ $widget_area ] = array( 
										'name' => esc_html__( 'Inactive Widgets', ShapeShifter_Extensions::TEXTDOMAIN ),// 使用停止中のウィジェット
									);
								} else {
									continue;
								}

							# Widget List for Each Widget Area// ウィジェットエリア毎のウィジェットリスト
								if( isset( $widgets[ 0 ] ) ) {
									$widgets_list[ $widget_area ] = $widgets;
								}
							# Select Item for Each Widget Area// ウィジェットエリア毎のウィジェットアイテムの選択肢
								$widget_select_options[ $widget_area ] = '';
								foreach( $this->widget_settings as $id => $data ) {

									if( in_array( $id, $widgets )  ) {
										$widget_select_options[ $widget_area ] .= '<option value="' . esc_attr( $id ) . '" 
											' . esc_attr( 
											$id === $selected_widget_from_widget_area 
											? 'selected'
											: '' 
										) . '>' . esc_html( 
											$data['widget_name_base'] . ( 
												isset( $data['title'] ) 
												? ' - ' . $data['title'] 
												: '' 
											) 
										) . '</option>';
									}

								}

							# If has Selectable items
							if( $widget_select_options[ $widget_area ] != '' ) {

								# Select Widget Area
									$widget_area_select .= '<option value="' . esc_attr( $widget_area ) . '" ' . ( $widget_area === $selected_widget_area ? 'selected' : '' ) . '>' . esc_html( $sidebars[ $widget_area ]['name'] ) . '</option>';

								# Select Widget from Widget Area
									$widget_select .= '<div 
										id="' . esc_attr( 'post-meta-select-widget-item-' . $widget_area . '-' . $item_num ) . '" 
										class="' . esc_attr( 'post-meta-select-widget-item-' . $widget_area ) . ' post-meta-select-widget-item" 
										style="
											' . esc_attr( $widget_area !== $selected_widget_area ? 'display: none;' : '' ) . '
										" 
									>';

										$widget_select .= '<label for="' . esc_attr( sse()->get_prefixed_post_meta_name( sprintf( 'sub_contents_json[%d][sub_content_selected_widget_from_widget_area_%s]', $item_num, $widget_area ) ) ) . '">'
											 . esc_html( sprintf( __( 'Widgets in "%s"', ShapeShifter_Extensions::TEXTDOMAIN ), $sidebars[ $widget_area ]['name'] ) ) . 
										'</label>: ';

										$widget_select .= '<select 
											name="' . esc_attr( sse()->get_prefixed_post_meta_name( sprintf( 'sub_contents_json[%d][sub_content_selected_widget_from_widget_area_%s]', $item_num, $widget_area ) ) ) . '" 
											id="' . esc_attr( sse()->get_prefixed_post_meta_name( sprintf( 'sub_contents_json[%d][sub_content_selected_widget_from_widget_area_%s]', $item_num, $widget_area ) ) ) . '" 
											class="shapeshifter-meta-box-optional-output"
										>
											<option value="none">' . esc_html__( 'Select One', ShapeShifter_Extensions::TEXTDOMAIN ) . '</option>';

											$widget_select .= $widget_select_options[ $widget_area ];

										$widget_select .= '</select><br>';

									$widget_select .= '</div>';

							}

						}

						$widget_area_select .= '</select><br>';

					// 出力
						echo '<div 
							id="post-meta-item-widget_from_widget_areas-' . $item_num . '" 
							class="post-meta-item-widget_from_widget_areas post-meta-item-form" 
							style="
								' . esc_attr( 'widget_from_widget_areas' !== $item_type ? 'display: none;' : '' ) . '
							" 
						>';

							echo '<label class="post-meta-setting-label">' . esc_html__( 'Selecting Widget', ShapeShifter_Extensions::TEXTDOMAIN ) . '</label><br><br>';

							echo '<p>';
								esc_html_e( 'Please Select one that WILL NOT be printed by Widget Settings because it may duplicate element ID.', ShapeShifter_Extensions::TEXTDOMAIN );
							echo '</p>';

							// ウィジェットエリア
							echo $widget_area_select;

							// ウィジェット
							echo $widget_select;

							//echo $input_hiddens;

						echo '</div>';

				}

				/**
				 * New Registered Widget
				 * 
				 * @param int    $post_id
				 * @param int    $item_num
				 * @param string $item_type
				 * @param string $new_registered_widget
				 * @param string $widget_class_name
				 * @param array  $widget_instance
				**/
				protected function form_type_new_registered_widget( $post_id, $item_num, $item_type, $new_registered_widget, $widget_class_name = '', $widget_instance = array() ) {

					$post_id = absint( $post_id );
					$item_num = absint( $item_num );

					# All Registered Widgets
						global $wp_widget_factory;

					echo '<div 
						id="post-meta-item-new_registered_widget-' . $item_num . '" 
						class="post-meta-item-new_registered_widget post-meta-item-form" 
						style="
							' . esc_attr( 'new_registered_widget' !== $item_type ? 'display: none;' : '' ) . '
						" 
					>';

						# Widget Select

							echo '<p>';

								echo '<label for="' . esc_attr( sse()->get_prefixed_post_meta_name( sprintf( 'sub_contents_json[%d][sub_content_new_registered_widget_select]', $item_num ) ) ) . '">' . esc_html__( 'Select a Widget', ShapeShifter_Extensions::TEXTDOMAIN ) . '</label>';

								echo '<select id="' . esc_attr( sse()->get_prefixed_post_meta_name( sprintf( 'sub_contents_json[%d][sub_content_new_registered_widget_select]', $item_num ) ) ) . '" class="post-meta-new-registered-widget-select shapeshifter-meta-box-optional-output" name="' . esc_attr( sse()->get_prefixed_post_meta_name( sprintf( 'sub_contents_json[%d][sub_content_new_registered_widget_select]', $item_num ) ) ) . '" data-item-num="' . $item_num . '">';


									echo '<option value="none" ' . selected( 'none', $new_registered_widget, false ) . '>' . esc_html__( 'Select One', ShapeShifter_Extensions::TEXTDOMAIN ) . '</option>';

									foreach( $wp_widget_factory->widgets as $class => $widget_object ) {

										# Skip SiteOrigin Widgets
											if( is_subclass_of( $widget_object, 'SiteOrigin_Widget' ) ) continue;

										$id_base = esc_attr( $widget_object->id_base );

										echo '<option value="' . $id_base . '" ' . selected( $id_base, $new_registered_widget, false ) . '>' . esc_html( $widget_object->name ) . '</option>';

									}

								echo '</select>';

							echo '</p>';

						# Widget Form
							foreach( $wp_widget_factory->widgets as $class => $widget_object ) {

								# Skip SiteOrigin Widgets
									if( is_subclass_of( $widget_object, 'SiteOrigin_Widget' ) ) continue;

								# Number ( Set )
									$widget_object->_set( absint( $item_num ) );
								# ID Base
									# $widget_object->id_base;
										$id_base = esc_attr( $widget_object->id_base );
									# $widget_object->control_options['id_base'];
								# Name
									# $widget_object->name;

								echo '<div id="' . esc_attr( 'widget-settings-form-' . $id_base . '-' . $item_num ) . '" class="post-meta-new-registered-widget-settings-form post-meta-new-registered-widget-settings-form-' . esc_attr( $id_base ) . '" style="border: solid #000 1px; padding: 10px; margin: 10px; ' . esc_attr( $new_registered_widget === $id_base ? 'display: block;' : 'display: none;' ) . '">';

									echo '<p>' . esc_html( $widget_object->name ) . '</p>';

									$widget_object->form( $widget_instance );

									echo '<input type="hidden" name="widget-' . esc_attr( $id_base . '[' . absint( $widget_object->number ) . '][widget_class_name]' ) . '" class="shapeshifter-meta-box-optional-output" value="' . esc_attr( $class ) . '">';

								echo '</div>';

							}

					echo '</div>';

				}

		/**
		 * Save metabox settings
		 * @param int $post_id
		**/
		public function save_metabox_settings( $post_id = 0 )
		{

			//$json = json_decode( str_replace( '\\"', '"', $_POST[ sse()->get_prefixed_post_meta_name( 'sub_contents_json' ) ] ) , true );
			//update_post_meta( $post_id, $this->postmeta_name, $json );

			//return;
			# Before Save
				if( isset( $_REQUEST['post_id'] ) ) { $post_id = absint( $_REQUEST['post_id'] ); }
				
				if( ! empty( $_REQUEST['action'] ) 
					&& $_REQUEST['action'] == 'save_to_output_widget_areas'
				) {

					check_ajax_referer( sse()->get_prefixed_post_meta_name( 'save_to_output_in_widget_area' ), 'output_in_widget_area_meta_box_nonce' );

				} else {

					if ( ! isset( $_POST[ sse()->get_prefixed_post_meta_name( 'output_in_widget_area_meta_box_nonce' ) ] ) ) {
						//return;
					}

					check_admin_referer( sse()->get_prefixed_post_meta_name( 'save_to_output_in_widget_area' ), sse()->get_prefixed_post_meta_name( 'output_in_widget_area_meta_box_nonce' ) );

					if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) {
						//return;
					}
				
					if ( isset( $_POST['post_type'] ) && 'page' === $_POST['post_type'] ) {
				
						if ( ! current_user_can( 'edit_page', $post_id ) ) {
							return;
						}
				
					} else {
				
						if ( ! current_user_can( 'edit_post', $post_id ) ) {
							return;
						}

					}

				// Subcontents
					if ( ! isset( $_POST[ $this->postmeta_name ] ) ) {
						update_post_meta( $post_id, sse()->get_prefixed_post_meta_name( 'sub_contents_arranged_json' ), array() );
						update_post_meta( $post_id, sse()->get_prefixed_post_meta_name( 'items_number_for_the_content' ), $max_item_num );
						return;
					}

				}
			
				//$sub_contents = $_POST[ $this->postmeta_name ];
				$sub_contents = array();
				if ( isset( $_POST[ $this->postmeta_name ] ) ) {
					$posted_data = str_replace( '\\"', '"', $_POST[ $this->postmeta_name ] );
					update_post_meta( $post_id, '_test_posted_data_subcontents', $posted_data );
					$subcontents_data = json_decode( $posted_data, true );
				}

				if( ! empty( $_REQUEST['action'] ) 
					&& $_REQUEST['action'] == 'save_to_output_widget_areas'
					&& isset( $_REQUEST['sub_contents_json'] )
				) {
					$subcontents_data = $_REQUEST['sub_contents_json'];
				}

			// Old Data
				$this->data_postmeta['sub_contents_json'] = SSE_Data_Post_Meta::get_instance( $post_id, 'sub_contents_json', array() );
				$old_sub_contents = $this->data_postmeta['sub_contents_json']->get_data();

				foreach ( $subcontents_data as $item_num => $data ) {

					$index = absint( $item_num );
					$sub_contents[ $index ] = array();
					//$data = $arranged_json_data[ $index ];

					# General
						# Data
							$sub_contents[ $index ]['sub_content_item_title'] = $item_title = sanitize_text_field(
								isset( $subcontents_data[ $index ]['sub_content_item_title'] )
								? $subcontents_data[ $index ]['sub_content_item_title']
								: ''
							);
							$sub_contents[ $index ]['sub_content_item_type'] = $item_type = sanitize_text_field(
								isset( $subcontents_data[ $index ]['sub_content_item_type'] )
								? $subcontents_data[ $index ]['sub_content_item_type']
								: ''
							);
							$sub_contents[ $index ]['sub_content_item_hook'] = $item_hook = sanitize_text_field(
								isset( $subcontents_data[ $index ]['sub_content_item_hook'] )
								? $subcontents_data[ $index ]['sub_content_item_hook']
								: ''
							);

					# Common
						# Data
							//$device_detect = array();
							$sub_contents[ $index ]['sub_content_device_detect']['is_pc'] = sanitize_text_field(
								isset( $subcontents_data[ $index ]['sub_content_device_detect']['is_pc'] )
								? $subcontents_data[ $index ]['sub_content_device_detect']['is_pc']
								: ''
							);
							$sub_contents[ $index ]['sub_content_device_detect']['is_mobile'] = sanitize_text_field(
								isset( $subcontents_data[ $index ]['sub_content_device_detect']['is_mobile'] )
								? $subcontents_data[ $index ]['sub_content_device_detect']['is_mobile']
								: ''
							);
							$device_detect = $sub_contents[ $index ]['sub_content_device_detect'];

							$sub_contents[ $index ]['sub_content_title_is_display'] = $title_is_display = sanitize_text_field(
								isset( $subcontents_data[ $index ]['sub_content_title_is_display'] ) 
								? $subcontents_data[ $index ]['sub_content_title_is_display']
								: ''
							);
							$sub_contents[ $index ]['sub_content_text_color'] = $text_color = sanitize_text_field(
								isset( $subcontents_data[ $index ]['sub_content_text_color'] )
								? $subcontents_data[ $index ]['sub_content_text_color']
								: ''
							);
							// スライダーの場合は保存しない
							if( ! in_array( $item_type, array( 'none', 'slider' ) ) ) {
								$sub_contents[ $index ]['sub_content_background_images'] = $item_background_images = sanitize_text_field(
									isset( $subcontents_data[ $index ]['sub_content_background_images'] )
									? $subcontents_data[ $index ]['sub_content_background_images']
									: ''
								);
							}

					# Slider
						if( in_array( $item_type, array( 'slider' ) ) ) {

							# Data
								// 一般
									$sub_contents[ $index ]['sub_content_slider_item_num'] = $slider_item_num = absint(
										isset( $subcontents_data[ $index ]['sub_content_slider_item_num'] )
										? $subcontents_data[ $index ]['sub_content_slider_item_num']
										: ''
									);
									$sub_contents[ $index ]['sub_content_slider_type'] = $slider_type = sanitize_text_field(
										isset( $subcontents_data[ $index ]['sub_content_slider_type'] )
										? $subcontents_data[ $index ]['sub_content_slider_type']
										: ''
									);
								# Slider Style
									//$slider_style_type = array();
									$sub_contents[ $index ]['sub_content_slider_style_type']['sidecontrols'] = (
										isset( $subcontents_data[ $index ]['sub_content_slider_style_type']['sidecontrols'] )
										? $subcontents_data[ $index ]['sub_content_slider_style_type']['sidecontrols']
										: ''
									);
									$sub_contents[ $index ]['sub_content_slider_style_type']['buttons'] = $slider_style_type = (
										isset( $subcontents_data[ $index ]['sub_content_slider_style_type']['buttons'] )
										? $subcontents_data[ $index ]['sub_content_slider_style_type']['buttons']
										: ''
									);
								# Post Formats
									//$slider_post_formats = array();
									foreach( self::$post_formats as $value => $text ) {

										$sub_contents[ $index ]['sub_content_slider_post_formats'][ $value ] = (
											isset( $subcontents_data[ $index ]['sub_content_slider_post_formats'][ $value ] )
											? $subcontents_data[ $index ]['sub_content_slider_post_formats'][ $value ]
											: ''
										);

									}
									$slider_post_formats = $subcontents_data[ $index ]['sub_content_slider_post_formats'];

						}

					# Placed Widget
						if( in_array( $item_type, array( 'widget_from_widget_areas' ) ) ) {

							# Data
								$sub_contents[ $index ]['sub_content_selected_widget_area'] = $widget_area_id = (
									isset( $subcontents_data[ $index ]['sub_content_selected_widget_area'] ) 
									? $subcontents_data[ $index ]['sub_content_selected_widget_area']
									: 'none'
								);
								$sub_contents[ $index ]['sub_content_selected_widget_from_widget_area'] = $widget_select = (
									isset( $subcontents_data[ $index ]['sub_content_selected_widget_from_widget_area_' . $widget_area_id ] )
									? $subcontents_data[ $index ]['sub_content_selected_widget_from_widget_area_' . $widget_area_id]
									: 'none'
								);
								
								global $wp_registered_widgets;
								foreach( $wp_registered_widgets as $index2 => $widget ) {

									// ウィジェットのオブジェクト
										$widget_instance = $widget['callback'][ 0 ];
										$option_name = $widget_instance->option_name;

									// ウィジェットのIDに使用
										$id_base = esc_attr( $widget_instance->id_base );

									// ウィジェットクラス毎のオプション配列
										$options = get_option( $option_name );
										$widget_class = get_class( $widget_instance );
										$widget_name_base = $widget_instance->name;

									foreach( $options as $index3 => $widget_data ) {
										
										if( ! is_array( $widget_data ) || ! intval( $widget_data ) ) continue;

										$id = esc_attr( $id_base . '-' . $index3 );

										$this->widget_settings[ $id ] = $widget_data;
										$this->widget_settings[ $id ]['widget_class'] = $widget_class;
										$this->widget_settings[ $id ]['widget_name_base'] = $widget_name_base;

										if( $id === $widget_select ) {
											$sub_contents[ $index ]['sub_content_widget_item_data'] = $selected_widget_data = $this->widget_settings[ $id ];
											break;
										} else {
											//$sub_contents[ $index ]['sub_content_widget_item_data'] = $selected_widget_data = array;
										}

									}

								}

						}

					# New Registered Widget
						if( in_array( $item_type, array( 'new_registered_widget' ) ) ) {

							global $wp_widget_factory;

							# Data
								$sub_contents[ $index ]['sub_content_new_registered_widget_select'] = $widget_select = sanitize_text_field(
									isset( $subcontents_data[ $index ]['sub_content_new_registered_widget_select'] )
									? $subcontents_data[ $index ]['sub_content_new_registered_widget_select']
									: ''
								);

								$widget_instance = (
									isset( $subcontents_data[ $index ]['sub_content_new_registered_widget_instance'] )
									? $subcontents_data[ $index ]['sub_content_new_registered_widget_instance']
									: array()
								);

								$sub_contents[ $index ]['sub_content_new_registered_widget_selected_class'] = $widget_selected_class = sanitize_text_field( $widget_instance['widget_class_name'] );

								$sub_contents[ $index ]['sub_content_new_registered_widget_instance'] = $widget_instance = call_user_func( 
									array( $wp_widget_factory->widgets[ $widget_selected_class ], 'update' ), 
									$widget_instance, 
									( isset( $old_sub_contents[ $index ]['sub_content_new_registered_widget_instance'] )
										? $old_sub_contents[ $index ]['sub_content_new_registered_widget_instance']
										: array()
									)
								);

						}
					
				}
				$subcontents_json = json_encode( $sub_contents, JSON_UNESCAPED_UNICODE );
				update_post_meta( $post_id, $this->postmeta_name, $subcontents_json );

		}


}

