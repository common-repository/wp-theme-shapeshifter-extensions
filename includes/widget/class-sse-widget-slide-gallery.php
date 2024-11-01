<?php
class ShapeShifter_Slide_Gallary extends SSE_Widget {

	public static $defaults = array();

	#
	# Properties
	#
		/**
		 * Widget Number
		 * 
		 * @var int $widget_number
		**/
		private $widget_number;

	#
	# Init
	#
		/**
		 * Constructor
		**/
		function __construct() {

			self::$defaults = array(
				'title_slide_gallery' => esc_html__( 'Gallary', ShapeShifter_Extensions::TEXTDOMAIN ),
				'title_not_display' => '',
				'type_text_textarea_number' => 1,
				'image_item_num' => 0
			);

			# CSS JS
				add_action( 'wp_enqueue_scripts', array( $this, 'wp_enqueue_scripts' ) );

			# AJAX
				add_action( 'wp_ajax_get_settings_form_for_each_item', array( $this, 'get_settings_form_for_each_item' ) );
				add_action( 'wp_ajax_get_settings_form_for_text', array( $this, 'get_settings_form_for_text' ) );
				add_action( 'wp_ajax_get_settings_form_for_textarea', array( $this, 'get_settings_form_for_textarea' ) );
				add_action( 'wp_ajax_get_settings_form_for_download', array( $this, 'get_settings_form_for_download' ) );

			parent::__construct( false, $name = 'ShapeShifter Slide Gallary' );

		}

	# Output
		function widget( $args, $instance ) {

			$max_item_num = absint( $instance['image_item_num'] );

			if( $max_item_num > 0 ) {

				extract( $args ); $args = null;

				echo $before_widget; $before_widget = null;

				$widget_number = esc_attr( $this->number );

				$title_slide_gallery = esc_html( strip_tags( $instance['title_slide_gallery'] ) );
				$title_not_display = ( ! empty( $instance['title_not_display'] ) ? true : false );
				if( ! $title_not_display ) {
					if ( '' !== $title_slide_gallery )
						echo $before_title . $title_slide_gallery . $after_title;
				} $title_not_display = $before_title = $title_slide_gallery = $after_title = null;

				echo '<div id="shapeshifter-slider-wrapper-' . $widget_number . '" class="shapeshifter-slider-wrapper slider-pro" style="visibility:hidden;">';
					echo '<div id="sp-slides-container-' . $widget_number . '" class="sp-slides-container">';
					echo '<div id="sp-slides-' . $widget_number . '" class="sp-slides">';

					for( $item_num = 1; $item_num <= $max_item_num; $item_num++ ) {

						$item_num = absint( $item_num );

						$item_type = esc_attr( $instance['item_type_' . $item_num ] );

						# Slider Type
							if( $item_type == 'none' ) {
								continue;
							} elseif( $item_type == 'text' ) {

								if( 0 == intval( $instance['type_text_textarea_number_' . $item_num ] )  ) {
									continue;
								}

							} elseif( $item_type == 'download' ) {

								if( isset( $instance['download_title_' . $item_num ] ) 
									&& html_entity_decode( $instance['download_title_' . $item_num ] ) == '' 
								) {
									continue;
								}

							} elseif( $item_type == 'movie' ) {

								if( isset( $instance['youtube_movie_url_' . $item_num ] ) 
									&& html_entity_decode( $instance['youtube_movie_url_' . $item_num ] ) == '' 
								) {
									continue;
								}

							} elseif( $item_type == 'post' ) {

								if( isset( $instance['post_id_' . $item_num ] ) 
									&& html_entity_decode( $instance['post_id_' . $item_num ] ) == 'none' 
								) {
									continue;
								}

							} elseif( $item_type == 'page' ) {

								if( isset( $instance['page_id_' . $item_num ] ) 
									&& html_entity_decode( $instance['page_id_' . $item_num ] ) == 'none' 
								) {
									continue;
								}

							}

						$thumbnail_image_url = esc_url( $instance['image_url_' . $item_num ] );

						if( $item_type == 'post' ) {

							$slider_post = get_post( $instance['post_id_' . $item_num ] );
							$permalink = get_permalink( $slider_post->ID );
							$post_url = esc_url( $permalink );

							$image_id = get_post_thumbnail_id( $instance['post_id_' . $item_num ] );
							if( ! empty( $image_id ) ) {

								$attachment_image_src = wp_get_attachment_image_src( $image_id, 'full' );
								$thumbnail_image_url = esc_url( $attachment_image_src[ 0 ] );

							}

						} elseif( $item_type == 'page' ) {

							$page = get_post( $instance['page_id_' . $item_num ] );

							$page_url = esc_url( get_page_link( $page->ID ) );

							$image_id = get_post_thumbnail_id( $instance['page_id_' . $item_num ] );
							if( ! empty( $image_id ) ) {

								$attachment_image_src = wp_get_attachment_image_src( $image_id, 'full' );
								$thumbnail_image_url = esc_url( $attachment_image_src[ 0 ] );

							}

						}

						echo '<div class="sp-slide sp-slide-' . $item_num . ' sp-slide-' . $item_type . '" ';
							if( ! in_array( $item_type, array( 'text', 'movie' ) ) ) 
								echo 'style="background-image:url(' . $thumbnail_image_url . '); background-size: cover; background-position: center center; background-repeat: no-repeat;" ';
						echo '>';

							if( $item_type == 'text' ) {
								$slide_gallery_item_link_wrap = esc_url( $instance['slide_gallery_item_link_wrap_' . $item_num ] );
								if( $slide_gallery_item_link_wrap != '' ) {
									echo '<a href="' . $slide_gallery_item_link_wrap . '">';
								}
							}

							if( $item_type == 'text' ) { 

								echo '<canvas ';
									echo 'id="sp-image-' . $item_num . '" ';
									echo 'class="sp-image" ';
									echo 'style="background-image:url(' . $thumbnail_image_url . '); background-size: 100%; background-position: center center; background-repeat: no-repeat;" ';
								echo '></canvas>';

								for( $textarea_number = 1; $textarea_number <= intval( $instance['type_text_textarea_number_' . $item_num ] ); $textarea_number++ ) {

									$textarea_number = absint( $textarea_number );

									if( isset( $instance['slide_gallery_text_' . $item_num . '_' . $textarea_number ] ) 
										&& html_entity_decode( $instance['slide_gallery_text_' . $item_num . '_' . $textarea_number ] ) != '' 
									) {

										echo '<div ';
											echo 'id="sp-layer-'. $item_num . '-' . $textarea_number . '" ';
											echo 'class="sp-layer sp-padding" ';
											echo 'data-position="' . esc_attr( $instance['slide_gallery_text_position_' . $item_num . '_' . $textarea_number ] ) . '" ';
											//echo 'data-width="200" ';
											echo 'data-horizontal="5%" ';
											echo 'data-vertical="5%" ';
											echo 'data-show-transition="left" ';
											echo 'data-show-delay="300" '; 
											echo ' style="color: ' . esc_attr( $instance['slide_gallery_text_color_' . $item_num . '_' . $textarea_number ] ) . '; background-color: ' . esc_attr( $instance['slide_gallery_text_background_color_' . $item_num . '_' . $textarea_number ] ) . '; opacity: 0.7 !important;"';
										echo '>';
										
											echo html_entity_decode( $instance['slide_gallery_text_' . $item_num . '_' . $textarea_number ] );

										echo '</div>';

									}

								}

							} elseif( $item_type == 'download' ) {

								echo '<div class="sp-slide-innder-wrapper-for-item sp-layer" data-position="centerCenter" style="width: 100%; color: ' . esc_attr( $instance['slide_gallery_download_text_color_' . $item_num ] != '' ? $instance['slide_gallery_download_text_color_' . $item_num ] : '#FFF' ) . '">';

									echo '<p class="download-title">
										' . esc_html( $instance['download_title_' . $item_num ] ) . '
									</p>';
									echo '<p class="download-description"><small style="word-wrap: break-word;">
										' . html_entity_decode( $instance['download_description_' . $item_num ] ) . '
									</small></p>';

									echo '<p class="download-button" style="color: ' . esc_attr( $instance['slide_gallery_download_text_color_' . $item_num ] != '' ? $instance['slide_gallery_download_text_color_' . $item_num ] : '#FFF' ) . '">';
										if( $instance['demo_page_url_' . $item_num ] != '' ) {
											echo '<a class="demo-page" href="' . esc_url( $instance['demo_page_url_' . $item_num ] ) . '" style="color: ' . esc_attr( $instance['slide_gallery_download_text_color_' . $item_num ] != '' ? $instance['slide_gallery_download_text_color_' . $item_num ] : '#FFF' ) . '; border: solid ' . esc_attr( $instance['slide_gallery_download_text_color_' . $item_num ] != '' ? $instance['slide_gallery_download_text_color_' . $item_num ] : '#FFF' ) . ' 2px;">
													' . esc_html__( 'Demo', ShapeShifter_Extensions::TEXTDOMAIN ) . '
												</a>';
										}
										echo '<a class="download-link" href="' . esc_url( $instance['download_url_' . $item_num ] ) . '" style="color: ' . esc_attr( $instance['slide_gallery_download_text_color_' . $item_num ] != '' ? $instance['slide_gallery_download_text_color_' . $item_num ] : '#FFF' ) . '; border: solid ' . esc_attr( $instance['slide_gallery_download_text_color_' . $item_num ] != '' ? $instance['slide_gallery_download_text_color_' . $item_num ] : '#FFF' ) . ' 2px;">
											' . esc_html__( 'Download', ShapeShifter_Extensions::TEXTDOMAIN ) . '
										</a>';
									echo '</p>';

								echo '</div>';

							} elseif( $item_type == 'movie' ) {

								echo '<div class="sp-layer sp-static" data-position="centerCenter">';

									echo '<a class="sp-video" href="' . esc_url( $instance['youtube_movie_url_' . $item_num ] ) . '">';
										echo '<img id="' . $widget_number . '-sp-video-' . $item_num . '" ';
											echo 'class="sp-image" ';
											echo 'alt="' . esc_attr__( 'Thumbnail for Movie', ShapeShifter_Extensions::TEXTDOMAIN ) . '" ';
											echo 'src="' . esc_url( SHAPESHIFTER_EXTENSIONS_THIRD_URL ) . 'slider-pro-master/dist/css/images/blank.gif" ';
											echo 'data-src="' . $thumbnail_image_url . '" ';
											//echo 'width="100%" height="100%"';
										echo '>';
									echo '</a>';

								echo '</div>';

							} elseif( $item_type == 'post' ) {

								echo '<a href="' . $post_url . '">';

									echo '<div class="sp-layer sp-static sp-white sp-padding" data-position="bottomLeft" data-width="100%">';
										echo '<p class="" style="font-size: 16px;">' . esc_html( strip_tags( $slider_post->post_title ) ) . '</p>';
										echo '<p class="" style="margin: 0;">' . esc_html( strip_tags( sse_get_the_excerpt( $slider_post->post_content, 100 ) ) ) . '</p>';
									echo '</div>';

								echo '</a>';

							} elseif( $item_type == 'page' ) {

								echo '<a href="' . $page_url . '">';

									echo '<div class="sp-layer sp-static sp-white sp-padding" data-position="bottomLeft" data-width="100%">';
										echo '<p class="" style="font-size: 16px;">' . esc_html( strip_tags( $page->post_title ) ) . '</p>';
										echo '<p class="" style="margin: 0;">' . esc_html( strip_tags( sse_get_the_excerpt( $page->post_content, 100 ) ) ) . '</p>';
									echo '</div>';

								echo '</a>';

							} else {

								echo '</div>';

								continue;

							}

							if( $item_type == 'text' ) {
								if( $slide_gallery_item_link_wrap != '' ) {
									echo '</a>';
								}
							}

							$thumbnail_title = esc_html( strip_tags( $instance['slide_gallery_thumbnail_title_' . $item_num ] ) );
							$thumbnail_description = html_entity_decode( $instance['slide_gallery_thumbnail_description_' . $item_num ] );

							echo '<div class="sp-thumbnail sp-thumbnail-' . $item_num . '">';

								echo '<canvas ';
									echo 'id="' . $widget_number . '-sp-thumbnail-image-' . $item_num . '" ';
									echo 'class="sp-layer" ';
									echo 'style="background-image:url(' . $thumbnail_image_url . '); background-size: cover; background-repeat: no-repeat; background-position: center center; width: 130px; height: 100px; float: left;" ';
								echo '></canvas>';
								echo '<div ';
									echo 'class="sp-layer" ';
									//echo 'style="width: 170px; height: 100px; margin-left: 130px; float: right;" ';
								echo '>';
									echo '<p class="sp-thumbnail-text sp-thumbnail-text-' . $item_num . '" ';
										echo 'style="padding: 5px; margin: 0;" ';
									echo '>' . $thumbnail_title . '</p>';
									echo '<p class="sp-thumbnail-text sp-thumbnail-text-' . $item_num . '" ';
										echo 'style="padding: 5px; margin: 0; font-size: 12px;" ';
									echo '>' . $thumbnail_description . '</p>';
								echo '</div>';

							echo '</div>';

						echo '</div>';

					} $thumbnail_image_url = $textarea_number = $slider_post = $post_url = $page = $page_url = $slide_gallery_item_link_wrap = $thumbnail_title = $thumbnail_description = $instance = $item_num = $item_type = null;

					echo '</div>';
					echo '</div>';

				echo '</div>'; $widget_number = null;

				echo $after_widget; $after_widget = null;

			} else {
				return;
			}


		}
			function wp_enqueue_scripts() {

				# This Plugin
					wp_enqueue_script( 'sse-widget-slide-gallery' );

			}
	
	# Update
		function update( $new_instance, $old_instance ) {

			$new_instance = wp_parse_args( $new_instance, self::$defaults );
			$instance = $old_instance;

			# Title
				$instance['title_slide_gallery'] = $instance['title'] = sanitize_text_field(
					isset( $new_instance['title_slide_gallery'] )
					? $new_instance['title_slide_gallery']
					: ''
				);

			# Not Display
				$instance['title_not_display'] = sanitize_text_field( 
					isset( $new_instance['title_not_display'] )
					? $new_instance['title_not_display']
					: false
				);

			# Item Number
				$image_item_num = 0;
				if( intval( $new_instance['image_item_num'] ) > 0 ) { 
					
					for( $i = 1; $i <= intval( $new_instance['image_item_num'] ); $i++ ) {

						//if( $new_instance['item_type_' . $i ] == 'none' ) continue;

						$image_item_num++;

						# Item Type
							$instance['item_type_' . $image_item_num ] = sanitize_text_field( strip_tags( $new_instance['item_type_' . $i ] ) );

						# Thumbnail
							$instance['slide_gallery_thumbnail_title_' . $image_item_num ] = sanitize_text_field( $new_instance['slide_gallery_thumbnail_title_' . $i ] );
							$instance['slide_gallery_thumbnail_description_' . $image_item_num ] = sanitize_text_field( $new_instance['slide_gallery_thumbnail_description_' . $i ] );

						# Image Source
							$instance['image_url_' . $image_item_num ] = esc_url_raw( $new_instance['image_url_' . $i ] );

						# If Item Type is defined
						if( $instance['item_type_' . $image_item_num ] == 'text' ) {

							# Text
								$instance['slide_gallery_item_link_wrap_' . $image_item_num ] = esc_url_raw( $new_instance['slide_gallery_item_link_wrap_' . $i ] );

								$i3 = 0;
								$i4 = 1;

								for( $i2 = 1; $i2 <= $new_instance['type_text_textarea_number_' . $i ]; $i2++ ) {

									$new_instance['slide_gallery_text_' . $i . '_' . $i2 ] = (
										isset( $new_instance['slide_gallery_text_' . $i . '_' . $i2 ] )
										? $new_instance['slide_gallery_text_' . $i . '_' . $i2 ]
										: ''
									);

									if( $new_instance['slide_gallery_text_' . $i . '_' . $i2 ] == '' ) {
										$i3++;
									} else {
										$instance['slide_gallery_text_' . $image_item_num . '_' . $i4 ] = esc_textarea( $new_instance['slide_gallery_text_' . $i . '_' . $i2 ] );
										$instance['slide_gallery_text_position_' . $image_item_num . '_' . $i4 ] = sanitize_text_field( $new_instance['slide_gallery_text_position_' . $i . '_' . $i2 ] );
										$instance['slide_gallery_text_color_' . $image_item_num . '_' . $i4 ] = sanitize_text_field( $new_instance['slide_gallery_text_color_' . $i . '_' . $i2 ] );
										$instance['slide_gallery_text_background_color_' . $image_item_num . '_' . $i4 ] = sanitize_text_field( $new_instance['slide_gallery_text_background_color_' . $i . '_' . $i2 ] );
										$i4++;
									}
								}
								$instance['type_text_textarea_number_' . $image_item_num ] = intval( intval( $new_instance['type_text_textarea_number_' . $i ] ) - $i3 );

						} elseif( $instance['item_type_' . $image_item_num ] == 'download' ) {

							# Download
								$instance['slide_gallery_download_text_color_' . $image_item_num ] = sanitize_text_field( $new_instance['slide_gallery_download_text_color_' . $i ] );
								$instance['download_title_' . $image_item_num ] = sanitize_text_field( $new_instance['download_title_' . $i ] );
								$instance['download_url_' . $image_item_num ] = esc_url_raw( $new_instance['download_url_' . $i ] );
								$instance['demo_page_url_' . $image_item_num ] = esc_url_raw( $new_instance['demo_page_url_' . $i ] );
								$instance['download_description_' . $image_item_num ] = esc_textarea( $new_instance['download_description_' . $i ] );

						} elseif( $instance['item_type_' . $image_item_num ] == 'movie' ) {

							# Movie
								$instance['youtube_movie_url_' . $image_item_num ] = esc_url_raw( strip_tags( $new_instance['youtube_movie_url_' . $i ] ) );

						} elseif( $instance['item_type_' . $image_item_num ] == 'post' ) {
							
							# Post
								$instance['post_id_' . $image_item_num ] = absint( $new_instance['post_id_' . $i ] );

						} elseif( $instance['item_type_' . $image_item_num ] == 'page' ) {

							# Page
								$instance['page_id_' . $image_item_num ] = absint( $new_instance['page_id_' . $i ] );
							
						}

					}

				}
				$instance['image_item_num'] = absint( $image_item_num );

			return $instance;

		}

	# Form
		function form( $instance ) {

			$widget_id = esc_attr( $this->id );
			$widget_number = absint( $this->number );

			?><script>
				window['widgetNumber'] = parseInt( "<?php echo esc_attr( $widget_number ); ?>", 10 );
				//console.log( window['widgetNumber'] );
			</script><?php 

			if( $this->number == '__i__' ) {

				foreach( $this->get_settings() as $index => $settings ) {
					$widget_number = absint( $index );
				} if( empty( $widget_number ) ) $widget_number = 1;
				?>

				<input type="hidden" id="widget_id_<?php echo esc_attr( $this->number ); ?>" class="widget_num" value="<?php echo esc_attr( $this->number ); ?>">
				
				<script id="script-widget-number-<?php echo esc_attr( $this->number ); ?>" widget-number="<?php echo esc_attr( $this->number ); ?>">

					jQuery( document ).on( 'widget-after', function() {

						//console.log( window );

						//console.log( jQuery( window.document ).find( 'input.multi_number' ).val() );

						//console.log( jQuery( this )[ 0 ].currentScript );

						//console.log( jQuery( this ).find( 'input.multi_number' ).val() );

					} );

					//window['widgetNumber'] = jQuery( window.document.currentScript ).parent().find( 'input.multi_number' ).val();

					
					if( typeof plus == 'undefined' ) {
						var plus = 0;
					}
					plus++;
					window['widgetNumber'] = parseInt( "<?php echo intval( $widget_number ); ?>", 10 ) - 1;
					window['widgetNumber'] += plus;

					//console.log( widgetNumber );
					

				</script><?php $widget_number++;
			}

			$instance = wp_parse_args( ( array ) $instance, self::$defaults );

			$instance_JSON = wp_json_encode( $instance );

			$title_slide_gallery = esc_attr( $instance['title_slide_gallery'] );
			$title_not_display = esc_attr( $instance['title_not_display'] );

			$image_item_num = absint( $instance['image_item_num'] );

			?>

			<script>
				//console.log( typeof window['widgetNumber'] );
				jQuery( document ).ready( function() {

					widgetNumber = window['widgetNumber'];
					window['slideGallaryJSON' + widgetNumber ] = <?php echo $instance_JSON; ?>;

				});
				if( typeof window['widgetNumber'] != 'undefined' && window['widgetNumber'] != '__i__' ) {
					widgetNumber = window['widgetNumber'];
					window['slideGallaryJSON' + widgetNumber ] = <?php echo $instance_JSON; ?>;
					//console.log( window['slideGallaryJSON' + widgetNumber ] );
				} 
			</script>

			<p><?php # Title
				$this->shapeshifter_print_input_tag(
					esc_html__( 'Title', ShapeShifter_Extensions::TEXTDOMAIN ),
					'text',
					esc_attr( $this->get_field_id( 'title_slide_gallery' ) ),
					esc_attr( $this->get_field_name( 'title_slide_gallery' ) ),
					$title_slide_gallery,
					array( 'class' => 'regular-text-field' )
				); 
			?></p>

			<p><?php # Not Display the Title
				$this->shapeshifter_print_checkbox( 
					esc_html__( 'Not Display the Title', ShapeShifter_Extensions::TEXTDOMAIN ), 
					( $this->get_field_id( 'title_not_display' ) ), 
					( $this->get_field_name( 'title_not_display' ) ), 
					'title_not_display', 
					$title_not_display, 
					array(
						'style' => 'margin:5px;'
					) 
				); 
			?></p>
			
			<p>
				<label for="<?php echo esc_attr( $this->get_field_id( 'image_item_num' ) ); ?>">
					<strong><?php esc_html_e( 'Sliding Images', ShapeShifter_Extensions::TEXTDOMAIN ); ?></strong>
				</label><br>
				<small><strong><?php esc_html_e( 'How to Set', ShapeShifter_Extensions::TEXTDOMAIN ); ?></strong>
					<ol>
						<li><?php esc_html_e( 'Select Images as many as you want to slide. ( You can set the Image for each item later. )', ShapeShifter_Extensions::TEXTDOMAIN ); ?></li>
						<li><?php esc_html_e( 'Click the Image to setup for the Item.', ShapeShifter_Extensions::TEXTDOMAIN ); ?></li>
						<li><?php esc_html_e( '* Item whose Item Type is Not Set won\'t be saved.', ShapeShifter_Extensions::TEXTDOMAIN ); ?></li>
					</ol>
				
				</small>
				<div id="image-box-<?php echo esc_attr( $widget_id ); ?>" class="image-box">
					<?php if( $image_item_num > 0 ) { 
						for( $i = 1; $i <= $image_item_num; $i++ ) { $i = absint( $i ); ?>
							<a 
								id="<?php echo esc_attr( $widget_number . '-list-img-a-tag-wrapper-' . $i ); ?>" 
								class="settings-form-popup"
								href="javascript:void( 0 );"
								onclick="shapeshifterSlideGallaryMethods.popupSettingsMenuFormWidget( window['slideGallaryJSON<?php echo esc_attr( $widget_number ); ?>'], '<?php echo esc_attr( $widget_id ); ?>-settings-form-container-<?php echo $i; ?>', '<?php echo esc_attr( $widget_number ); ?>-list-img-a-tag-wrapper-<?php echo esc_attr( $i ); ?>' );" 
								data-widget-number="<?php echo esc_attr( $widget_number ); ?>" 
								data-item-num="<?php echo esc_attr( $image_item_num ); ?>" 
							>
								<img 
									id="<?php echo esc_attr( $widget_number ); ?>-list-img-url-<?php echo $i; ?>" 
									src="<?php 
										echo esc_url( isset( $instance['image_url_' . $i ] )
											? $instance['image_url_' . $i ]
											: ''
										); 
									?>" 
									class="images-from-library" 
									style="width:100px; height:100px; float:left;"
								>
							</a>
							<?php # Item Settings
							$this->get_settings_form_for_each_item( $instance, $widget_number, $i, $instance['image_url_' . $i ] );
						}
					} ?>
				</div>
				<div style="clear:both;margin-bottom:10px;"></div>

				<input class="widefat image-urls" id="<?php echo esc_attr( $this->get_field_id( 'image_item_num' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'image_item_num' ) ); ?>" type="hidden" value="<?php echo esc_attr( $image_item_num ); ?>" />
				<?php 
					// Replace Images
					$this->shapeshifter_print_js_call_a_button( 
						esc_html__( 'Set Images ( Replace )', ShapeShifter_Extensions::TEXTDOMAIN ), 
						esc_attr( 'set-image-box-' . $widget_id ), 
						'button', 
						$atts = array(), 
						$js_func = 'shapeshifterSlideGallaryMethods.getImagesForGallary', 
						$js_args = array(
							array( 'quote' => false, 'value' => 'window[ \'slideGallaryJSON' . $widget_number . '\']' ),
							array( 'quote' => false, 'value' => 'widgetNumber' ),
							array( 'quote' => true, 'value' => $widget_id ),
							array( 'quote' => true, 'value' => '#' . $this->get_field_id( 'image_item_num' ) ),
							array( 'quote' => true, 'value' => '#image-box-' . $widget_id ),
							array( 'quote' => false, 'value' => 'true' )
						)
					);
					echo ' ';
					// Append Images
					$this->shapeshifter_print_js_call_a_button( 
						esc_html__( 'Append Images', ShapeShifter_Extensions::TEXTDOMAIN ), 
						esc_attr( 'append-image-box-' . $widget_id ), 
						'button', 
						$atts = array(), 
						$js_func = 'shapeshifterSlideGallaryMethods.appendImagesForGallary', 
						$js_args = array(
							array( 'quote' => false, 'value' => 'window[ \'slideGallaryJSON' . $widget_number . '\']' ),
							array( 'quote' => false, 'value' => 'widgetNumber' ),
							array( 'quote' => true, 'value' => $widget_id ),
							array( 'quote' => true, 'value' => '#' . $this->get_field_id( 'image_item_num' ) ),
							array( 'quote' => true, 'value' => '#image-box-' . $widget_id ),
							array( 'quote' => false, 'value' => 'true' )
						)
					);
					echo ' ';
					// Reset
					$this->shapeshifter_print_js_call_a_button( 
						esc_html__( 'Reset', ShapeShifter_Extensions::TEXTDOMAIN ), 
						esc_attr( 'remove-image-box-' . $widget_id ), 
						'button', 
						$atts = array(), 
						$js_func = 'shapeshifterGeneralMethods.removeImagesFromBox', 
						$js_args = array(
							array( 'quote' => true, 'value' => '#' . $this->get_field_id( 'image_item_num' ) ),
							array( 'quote' => true, 'value' => '#image-box-' . $widget_id ),
							array( 'quote' => false, 'value' => 'widgetNumber' ),
						) 
					);

				?>

			</p>

			<?php 

		}

		# Each Item
			function get_settings_form_for_each_item( $instance = '', $widget_number = '', $item_num = '', $img_src = '' ) { 

				if( isset( $_REQUEST['instance'] ) && $instance == '' ) 
					$instance = $_REQUEST['instance'];

				//print_r( $_REQUEST['instance'] );

				if( isset( $_REQUEST['widget_number'] ) && $widget_number == '' ) 
					$widget_number = absint( $_REQUEST['widget_number'] );

				if( isset( $_REQUEST['item_num'] ) && $item_num == '' ) 
					$item_num = absint( $_REQUEST['item_num'] );

				if( isset( $_REQUEST['image_src'] ) && $img_src == '' ) 
					$img_src = esc_url( $_REQUEST['image_src'] );

				$widget_number = absint( $widget_number );
				$item_num = absint( $item_num );
				$img_src = esc_url( $img_src );

				$class_name = esc_attr( strtolower( get_class( $this ) ) );

				$widget_id = esc_attr( $class_name . '-' . $widget_number );

				$name_base = esc_attr( 'widget-' . $class_name . '[' . $widget_number . ']' );
				$id_base = esc_attr( 'widget-' . $widget_id . '-' );

				?>

				<div id="<?php echo esc_attr( $widget_id . '-settings-form-container-' . $item_num ); ?>" class="settings-form-container" data-item-num="<?php echo esc_attr( $item_num ); ?>" data-widget-number="<?php echo $widget_number; ?>">

					<h4><?php printf( esc_html__( 'Item %d', ShapeShifter_Extensions::TEXTDOMAIN ), $item_num ); ?></h4>

					<p><?php # Thumbnail Title
						$this->shapeshifter_print_input_tag( 
							esc_html__( 'Thumbnail Title', ShapeShifter_Extensions::TEXTDOMAIN ), 
							'text', 
							esc_attr( $id_base . 'slide_gallery_thumbnail_title_' . $item_num ), 
							esc_attr( $name_base . '[slide_gallery_thumbnail_title_' . $item_num . ']' ), 
							esc_attr( 
								! empty( $instance['slide_gallery_thumbnail_title_' . $item_num ] ) 
								? $instance['slide_gallery_thumbnail_title_' . $item_num ] 
								: ''
							), 
							array(
								'class' => 'regular-text-field',
								'style' => 'width: 50%;'
							) 
						);
					?></p>

					<p><?php # Thumbnail Description
						$this->shapeshifter_print_input_tag( 
							esc_html__( 'Thumbnail Description', ShapeShifter_Extensions::TEXTDOMAIN ), 
							'text', 
							esc_attr( $id_base . 'slide_gallery_thumbnail_description_' . $item_num ), 
							esc_attr( $name_base . '[slide_gallery_thumbnail_description_' . $item_num . ']' ), 
							esc_attr( 
								isset( $instance['slide_gallery_thumbnail_description_' . $item_num ] ) 
								? $instance['slide_gallery_thumbnail_description_' . $item_num ] 
								: ''
							), 
							array(
								'class' => 'regular-text-field',
								'style' => 'width: 100%;'
							) 
						);
					?></p>

					<p>
						<label for="<?php echo esc_attr( $id_base . 'image_url_' . $item_num ); ?>">
							<strong><?php esc_html_e( 'Sliding Images', ShapeShifter_Extensions::TEXTDOMAIN ); ?></strong>
						</label>
						<div id="image-box-<?php echo esc_attr( $widget_id ); ?>" class="image-box">
							<img id="<?php echo esc_attr( $widget_number . '-img-url-' . $item_num ); ?>" src="<?php echo esc_url( $img_src ); ?>" class="images-from-library" style="width:100px; height:100px; float:left;">
						</div>
						<div style="clear:both; margin-bottom:10px;"></div>
						
						<input  
							id="<?php echo esc_attr( $id_base . 'image_url_' . $item_num ); ?>" 
							class="widefat slide-item-image-url"
							name="<?php echo esc_attr( $name_base . '[image_url_' . $item_num . ']' ); ?>" 
							type="hidden" 
							value="<?php echo esc_url( $img_src ); ?>" 
						/>

						<?php 
							# Search for Image
							$this->shapeshifter_print_js_call_a_button( 
								esc_html__( 'Search for Image', ShapeShifter_Extensions::TEXTDOMAIN ), 
								esc_attr( 'set-image-box-' . $widget_id ), 
								'button', 
								array(), 
								'shapeshifterSlideGallaryMethods.getImagesForGallary', 
								array(
									array( 'quote' => false, 'value' => 'slideGallaryJSON' . $widget_number ),
									array( 'quote' => false, 'value' => $widget_number ),
									array( 'quote' => true, 'value' => $widget_id ),
									array( 'quote' => true, 'value' => '#' . $id_base . 'image_url_' . $item_num ),
									array( 'quote' => true, 'value' => '#' . $widget_number . '-img-url-' . $item_num . ', #' . $widget_number . '-list-img-url-' . $item_num ),
									array( 'quote' => false, 'value' => 'false' )
								)
							);

							echo '&nbsp;';

							# Reset
							$this->shapeshifter_print_js_call_a_button( 
								esc_html__( 'Reset', ShapeShifter_Extensions::TEXTDOMAIN ), 
								esc_attr( 'remove-image-box-' . $widget_id ), 
								'button', 
								array(), 
								'shapeshifterSlideGallaryMethods.removeImageSource', 
								array(
									array( 'quote' => true, 'value' => '#' . $widget_number . '-image-url-' . $item_num ),
									array( 'quote' => true, 'value' => '#' . $widget_number . '-img-url-' . $item_num . ', #' . $widget_number . '-list-img-url-' . $item_num ),
								) 
							);

						?>

					</p>

					<p><?php # Item Type
						$this->shapeshifter_print_select_tag( 
							esc_html__( 'Sliding Item Type', ShapeShifter_Extensions::TEXTDOMAIN ), 
							esc_attr( $id_base . 'item_type_' . $item_num ), 
							esc_attr( $name_base . '[item_type_' . $item_num . ']' ),
							esc_attr( isset( $instance['item_type_' . $item_num ] )
								? $instance['item_type_' . $item_num ]
								: 'none'
							), 
							array(
								'text' => esc_html__( 'Text', ShapeShifter_Extensions::TEXTDOMAIN ),
								'download' => esc_html__( 'Download', ShapeShifter_Extensions::TEXTDOMAIN ),
								'movie' => esc_html__( 'Movie', ShapeShifter_Extensions::TEXTDOMAIN ),
								'post' => esc_html__( 'Post', ShapeShifter_Extensions::TEXTDOMAIN ),
								'page' => esc_html__( 'Page', ShapeShifter_Extensions::TEXTDOMAIN ),
							), 
							array(
								'class' => esc_attr( 'item-type-select-for-widget-slider' ),
								'data-widget-number' => $widget_number,
								'data-item-num' => $item_num,
							) 
						);
					?></p>

					<div id="<?php echo esc_attr( $widget_id . '-type-settings-form-container-' . $item_num ); ?>" class="type-settings-form-container">
						<?php
							$this->get_settings_form_for_text( $instance, $widget_number, $item_num );
							$this->get_settings_form_for_download( $instance, $widget_number, $item_num );
							$this->get_settings_form_for_movie( $instance, $widget_number, $item_num );
							$this->get_settings_form_for_post( $instance, $widget_number, $item_num );
							$this->get_settings_form_for_page( $instance, $widget_number, $item_num );
						?>
					</div>

					<a href="javascript:void( 0 );" onclick="shapeshifterGeneralMethods.closeSettingsBox( '#<?php echo esc_attr( $widget_id ); ?>-settings-form-container-<?php echo $item_num; ?>', '#shapeshifter-widget-popup-background' );" class="button" style="margin-top:20px;"><?php esc_html_e( 'Close', ShapeShifter_Extensions::TEXTDOMAIN ); ?></a>

				</div>
				
				<?php 

				if( isset( $_REQUEST['instance'] ) && $_REQUEST['instance'] ) wp_die();

			}

			# Text
				function get_settings_form_for_text( $instance = '', $widget_number = '', $item_num = '' ) {

					if( isset( $_REQUEST['instance_for_text'] ) && $instance == '' ) 
						$instance = $_REQUEST['instance_for_text'];
					if( isset( $_REQUEST['widget_number'] ) && $widget_number == '' )
						$widget_number = absint( $_REQUEST['widget_number'] );
					if( isset( $_REQUEST['item_num'] ) && $item_num == '' ) 
						$item_num = absint( $_REQUEST['item_num'] );

					$widget_number = absint( $widget_number );
					$item_num = absint( $item_num );

					$item_type = esc_attr( 
						isset( $instance['item_type_' . $item_num ] ) 
						? $instance['item_type_' . $item_num ] 
						: 'text'
					);

					$class_name = esc_attr( strtolower( get_class( $this ) ) );

					$widget_id = esc_attr( $class_name . '-' . $widget_number );

					$name_base = esc_attr( 'widget-' . $class_name . '[' . $widget_number . ']' );
					$id_base = esc_attr( 'widget-' . $widget_id . '-' );

					$type_text_textarea_number = ( 
						isset( $instance['type_text_textarea_number_' . $item_num ] ) 
						? absint( $instance['type_text_textarea_number_' . $item_num ] ) 
						: 1 
					);
					?>

					<div id="<?php echo esc_attr( $widget_number . '-slide-item-text-' . $item_num ); ?>" 
						class="slide-item-text slide-item-settings-form" 
						style="display:<?php 
							echo esc_attr( isset( $instance['item_type_' . $item_num ] ) 
								? ( $instance['item_type_' . $item_num ] == 'text' 
									? 'block' 
									: 'none'
								) 
								: 'none'
							);
						?>;" 
						data-item-num="<?php echo esc_attr( $item_num ); ?>"
					>

						<p><?php # Slide Link
							$this->shapeshifter_print_input_tag( 
								esc_html__( 'Item Link', ShapeShifter_Extensions::TEXTDOMAIN ), 
								'text', 
								esc_attr( $id_base . 'slide_gallery_item_link_wrap_' . $item_num ), 
								esc_attr( $name_base . '[slide_gallery_item_link_wrap_' . $item_num . ']' ), 
								esc_url( isset( $instance['slide_gallery_item_link_wrap_' . $item_num ] ) 
									? $instance['slide_gallery_item_link_wrap_' . $item_num ] 
									: '' 
								), 
								$atts = array(
									'class' => esc_attr( 'regular-text-field' ),
									'style' => esc_attr( 'width:100%;' )
								) 
							);
						?></p>

						<input type="hidden" 
							id="<?php echo esc_attr( $widget_number . '_type_text_textarea_number_' . $item_num ); ?>"
							name="<?php echo esc_attr( $name_base . '[type_text_textarea_number_' . $item_num . ']' ); ?>" 
							value="<?php echo esc_attr( 
								isset( $instance['type_text_textarea_number_' . $item_num ] ) 
								? absint( $instance['type_text_textarea_number_' . $item_num ] ) 
								: $item_num 
							); ?>"
						>
						
						<script>
						itemNumber = <?php echo $item_num; ?>;
						if( typeof window['textareaNumber<?php echo esc_js( $widget_number . '_' . $item_num ); ?>'] == 'undefined' ) {
							window['textareaNumber<?php echo esc_js( $widget_number . '_' . $item_num ); ?>'] = <?php echo absint( 
								isset( $instance['type_text_textarea_number_' . $item_num ] ) 
								? $instance['type_text_textarea_number_' . $item_num ] 
								: 1 
							); ?>;
						}
						window['textareaNumber<?php echo esc_js( $widget_number . '_' . $item_num ); ?>']++;
						</script>

						<?php for( $textarea_number = 1; $textarea_number <= absint( 
							isset( $instance['type_text_textarea_number_' . $item_num ] ) 
							? intval( $instance['type_text_textarea_number_' . $item_num ] ) 
							: 1 
						); $textarea_number++ ) { 

							$this->get_settings_form_for_textarea( $instance, $id_base, $name_base, $widget_number, $item_num, $textarea_number );
						
						}

						if( isset( $instance ) ) {

							# Append an Textarea
							$this->shapeshifter_print_js_call_a_button( 
								esc_html__( 'Append an Textarea', ShapeShifter_Extensions::TEXTDOMAIN ), 
								esc_attr( 'a-tag-append-textarea-' . $widget_number. '-' . $item_num ), 
								'button', 
								$atts = array(
									'data-textarea-number-input' => esc_attr( '#' . $widget_number . '_type_text_textarea_number_' . $item_num ),
									'style' => esc_attr( 'margin-top:10px;' ),
								), 
								$js_func = 'shapeshifterSlideGallaryMethods.addTextarea', 
								$js_args = array(
									array( 'quote' => true, 'value' => 'slide_gallery_text_' . $item_num . '_' . $textarea_number ),
									array( 'quote' => true, 'value' => $class_name ),

									array( 'quote' => true, 'value' => $widget_number ),
									array( 'quote' => true, 'value' => $item_num ),
									array( 'quote' => false, 'value' => 'window[ \'textareaNumber' . $widget_number . '_' . $item_num . '\']' ),
									array( 'quote' => true, 'value' => '#' . $widget_number . '_type_text_textarea_number_' . $item_num ),
									array( 'quote' => true, 'value' => '#a-tag-append-textarea-' . $widget_number. '-' . $item_num ),
								)
							);

						}
						?>

						<br>

					</div>

					<?php 

					if( isset( $_REQUEST['instance_for_text'] ) && $_REQUEST['instance_for_text'] ) wp_die();

				}
					function get_settings_form_for_textarea( $instance = '', $id_base = '', $name_base = '', $widget_number = '', $item_num = '', $textarea_number = '' ) { 

						if( isset( $_REQUEST['widget_number'] ) && $widget_number == '' ) { $widget_number = esc_attr( $_REQUEST['widget_number'] ); }
						if( isset( $_REQUEST['item_num'] ) && $item_num == '' ) { $item_num = esc_attr( $_REQUEST['item_num'] ); }
						if( isset( $_REQUEST['textarea_number'] ) && $textarea_number == '' ) { $textarea_number = esc_attr( $_REQUEST['textarea_number'] ); }

						if( isset( $_REQUEST['id_base'] ) && $id_base == '' ) { $id_base = esc_attr( $_REQUEST['id_base'] ); }
						if( isset( $_REQUEST['name_base'] ) && $name_base == '' ) { $name_base = esc_attr( $_REQUEST['name_base'] ); }

						?>

						<p id="<?php echo $widget_number; ?>-textarea-wrapper-<?php echo $item_num; ?>-<?php echo $textarea_number; ?>"><?php # Content Text
							$this->shapeshifter_print_textarea_tag( 
								sprintf( esc_html__( 'Item Content Text %d', ShapeShifter_Extensions::TEXTDOMAIN ), $textarea_number ),
								esc_attr( $id_base . 'slide_gallery_text_' . $item_num . '_' . $textarea_number ), 
								esc_attr( $name_base . '[slide_gallery_text_' . $item_num . '_' . $textarea_number . ']' ), 
								( 
									isset( $instance['slide_gallery_text_' . $item_num . '_' . $textarea_number ] )
									? html_entity_decode( $instance['slide_gallery_text_' . $item_num . '_' . $textarea_number ] )
									: '' 
								), 
								true,
								array(
									'editor_height' => '100',
								) 
							);
						?></p>
						<p id="<?php echo $widget_number; ?>-textarea-position-wrapper-<?php echo $item_num; ?>-<?php echo $textarea_number; ?>"><?php # Position to Output
							$this->shapeshifter_print_select_tag( 
								sprintf( esc_html__( 'Position %d', ShapeShifter_Extensions::TEXTDOMAIN ), $textarea_number ), 
								esc_attr( $id_base . 'slide_gallery_text_position_' . $item_num . '_' . $textarea_number ), 
								esc_attr( $name_base . '[slide_gallery_text_position_' . $item_num . '_' . $textarea_number . ']' ), 
								( 
									isset( $instance['slide_gallery_text_position_' . $item_num . '_' . $textarea_number ] )
									? esc_attr( $instance['slide_gallery_text_position_' . $item_num . '_' . $textarea_number ] )
									: 'center' 
								), 
								array(
									'topLeft' => esc_html__( 'Top Left', ShapeShifter_Extensions::TEXTDOMAIN ),
									'topCenter' => esc_html__( 'Top Center', ShapeShifter_Extensions::TEXTDOMAIN ),
									'topRight' => esc_html_x( 'Top Right', 'for Widget Slide Gallary', ShapeShifter_Extensions::TEXTDOMAIN ),
									'bottomLeft' => esc_html__( 'Bottom Left', ShapeShifter_Extensions::TEXTDOMAIN ),
									'bottomCenter' => esc_html__( 'Bottom Center', ShapeShifter_Extensions::TEXTDOMAIN ),
									'bottomRight' => esc_html__( 'Bottom Right', ShapeShifter_Extensions::TEXTDOMAIN ),
									'centerLeft' => esc_html__( 'Center Left', ShapeShifter_Extensions::TEXTDOMAIN ),
									'centerCenter' => esc_html__( 'Center', ShapeShifter_Extensions::TEXTDOMAIN ),
									'centerRight' => esc_html__( 'CenterRight', ShapeShifter_Extensions::TEXTDOMAIN ),
								), 
								array(
									'class' => 'slide-gallery-text-position',
								) 

							);
						?></p>
						<p id="<?php echo $widget_number; ?>-textarea-text-color-wrapper-<?php echo $item_num; ?>-<?php echo $textarea_number; ?>"><?php # Text Color
							$this->shapeshifter_print_input_tag( 
								sprintf( esc_html__( 'Text Color %d', ShapeShifter_Extensions::TEXTDOMAIN ), $textarea_number ), 
								'text', 
								esc_attr( $id_base . 'slide_gallery_text_color_' . $item_num . '_' . $textarea_number ), 
								esc_attr( $name_base . '[slide_gallery_text_color_' . $item_num . '_' . $textarea_number . ']' ), 
								esc_attr( 
									isset( $instance['slide_gallery_text_color_' . $item_num . '_' . $textarea_number ] ) 
									? $instance['slide_gallery_text_color_' . $item_num . '_' . $textarea_number ] 
									: '' 
								), 
								$atts = array(
									'class' => 'regular-text-field color-setting',
								) 
							);
						?></p>
						<p id="<?php echo $widget_number; ?>-textarea-background-color-wrapper-<?php echo $item_num; ?>-<?php echo $textarea_number; ?>"><?php # Background Color
							$this->shapeshifter_print_input_tag( 
								sprintf( esc_html__( 'Background Color %d', ShapeShifter_Extensions::TEXTDOMAIN ), $textarea_number ), 
								'text', 
								esc_attr( $id_base . 'slide_gallery_text_background_color_' . $item_num . '_' . $textarea_number ), 
								esc_attr( $name_base . '[slide_gallery_text_background_color_' . $item_num . '_' . $textarea_number . ']' ), 
								esc_attr( 
									isset( $instance['slide_gallery_text_background_color_' . $item_num . '_' . $textarea_number ] ) 
									? $instance['slide_gallery_text_background_color_' . $item_num . '_' . $textarea_number ] 
									: '' 
								), 
								$atts = array(
									'class' => 'regular-text-field color-setting',
								) 
							);
						?></p>

						<?php 

					}

			# Download
				function get_settings_form_for_download( $instance = '', $widget_number = '', $item_num = '' ) {

					if( isset( $_REQUEST['instance_for_download'] ) && $instance == '' ) { $instance = $_REQUEST['instance_for_download']; }
					if( isset( $_REQUEST['widget_number'] ) && $widget_number == '' ) { $widget_number = $_REQUEST['widget_number']; }
					if( isset( $_REQUEST['item_num'] ) && $item_num == '' ) { $item_num = $_REQUEST['item_num']; }

					$widget_number = absint( $widget_number );
					$item_num = absint( $item_num );

					$item_type = esc_attr( isset( $instance['item_type_' . $item_num ] ) ? $instance['item_type_' . $item_num ] : 'text' );

					$class_name = strtolower( get_class( $this ) );

					$widget_id = esc_attr( $class_name . '-' . $widget_number );

					$name_base = esc_attr( 'widget-' . $class_name . '[' . $widget_number . ']' );
					$id_base = esc_attr( 'widget-' . $widget_id . '-' );

					?>

					<div id="<?php echo $widget_number; ?>-slide-item-download-<?php echo $item_num; ?>" 
						class="slide-item-download slide-item-settings-form" 
						style="display:<?php 
							echo esc_attr( isset( $instance['item_type_' . $item_num ] ) 
								? ( $instance['item_type_' . $item_num ] == 'download' 
									? 'block' 
									: 'none'
								) 
								: 'none'
							);
						?>;" 
						data-item-num="<?php echo $item_num; ?>"
					>

						<p id="<?php echo $widget_number; ?>-download-text-color-wrapper-<?php echo $item_num; ?>"><?php # Text Color
							$this->shapeshifter_print_input_tag( 
								esc_html__( 'Text Color', ShapeShifter_Extensions::TEXTDOMAIN ), 
								'text', 
								esc_attr( $id_base . 'slide_gallery_download_text_color_' . $item_num ), 
								esc_attr( $name_base . '[slide_gallery_download_text_color_' . $item_num . ']' ), 
								esc_attr( 
									isset( $instance['slide_gallery_download_text_color_' . $item_num ] ) 
									? $instance['slide_gallery_download_text_color_' . $item_num ] 
									: '' 
								), 
								$atts = array(
									'class' => 'regular-text-field color-setting',
								) 
							);
						?></p>

						<p><?php # Name of Download Content
							$this->shapeshifter_print_input_tag( 
								esc_html__( 'Name of the Download Content', ShapeShifter_Extensions::TEXTDOMAIN ), 
								'text', 
								esc_attr( $id_base . 'download_title_' . $item_num ), 
								esc_attr( $name_base . '[download_title_' . $item_num . ']' ), 
								esc_attr( isset( $instance['download_title_' . $item_num ] ) 
									? $instance['download_title_' . $item_num ] 
									: '' 
								), 
								$atts = array(
									'class' => 'regular-text-field',
									'style' => 'width:100%;'
								) 
							);
						?></p>

						<p><?php # Download URL
							$this->shapeshifter_print_input_tag( 
								esc_html__( 'Download URL', ShapeShifter_Extensions::TEXTDOMAIN ), 
								'text', 
								esc_attr( $id_base . 'download_url_' . $item_num ), 
								esc_attr( $name_base . '[download_url_' . $item_num . ']' ), 
								esc_url( isset( $instance['download_url_' . $item_num ] )
									? $instance['download_url_' . $item_num ] 
									: '' 
								), 
								$atts = array(
									'class' => 'regular-text-field',
									'style' => 'width:100%;'
								) 
							);
						?></p>

						<p><?php # Demo URL
							$this->shapeshifter_print_input_tag( 
								esc_html__( 'Demo Page URL', ShapeShifter_Extensions::TEXTDOMAIN ), 
								'text', 
								esc_attr( $id_base . 'demo_page_url_' . $item_num ), 
								esc_attr( $name_base . '[demo_page_url_' . $item_num . ']' ), 
								esc_url( isset( $instance['demo_page_url_' . $item_num ]  ) 
									? $instance['demo_page_url_' . $item_num ]
									: '' 
								), 
								$atts = array(
									'class' => 'regular-text-field',
									'style' => 'width:100%;'
								) 
							);
						?></p>

						<p><?php # Download Description
							$this->shapeshifter_print_textarea_tag( 
								esc_html__( 'Description for the Download Content', ShapeShifter_Extensions::TEXTDOMAIN ), 
								esc_attr( $id_base . 'download_description_' . $item_num ), 
								esc_attr( $name_base . '[download_description_' . $item_num . ']' ), 
								( isset( $instance['download_description_' . $item_num ] )
									? html_entity_decode( $instance['download_description_' . $item_num ] )
									: ''
								), 
								true,
								array(
									'editor_height' => '200',
								) 
							);
						?></p>

					</div>

					<?php

					if( isset( $_REQUEST['instance_for_download'] ) && $_REQUEST['instance_for_download'] ) wp_die();

				}

			# Movie
				function get_settings_form_for_movie( $instance = '', $widget_number = '', $item_num = '' ) {

					if( isset( $_REQUEST['instance_for_movie'] ) && $instance == '' ) { $instance = $_REQUEST['instance_for_movie']; }
					if( isset( $_REQUEST['widget_number'] ) && $widget_number == '' ) { $widget_number = $_REQUEST['widget_number']; }
					if( isset( $_REQUEST['item_num'] ) && $item_num == '' ) { $item_num = $_REQUEST['item_num']; }

					$widget_number = absint( $widget_number );
					$item_num = absint( $item_num );

					$item_type = ( isset( $instance['item_type_' . $item_num ] ) ? $instance['item_type_' . $item_num ] : 'none' );

					$class_name = strtolower( get_class( $this ) );

					$widget_id = esc_attr( $class_name . '-' . $widget_number );

					$name_base = esc_attr( 'widget-' . $class_name . '[' . $widget_number . ']' );
					$id_base = esc_attr( 'widget-' . $widget_id . '-' );

					?>

					<div id="<?php echo $widget_number; ?>-slide-item-movie-<?php echo $item_num; ?>" 
						class="slide-item-movie slide-item-settings-form" 
						style="display:<?php 
							echo esc_attr( isset( $instance['item_type_' . $item_num ] ) 
								? ( $instance['item_type_' . $item_num ] == 'movie' 
									? 'block' 
									: 'none'
								) 
								: 'none'
							);
						?>;" 
						data-item-num="<?php echo $item_num; ?>"
					>

						<p><?php # Movie URL
							$this->shapeshifter_print_input_tag( 
								esc_html__( 'URL of Youtube Movie', ShapeShifter_Extensions::TEXTDOMAIN ), 
								'text', 
								esc_attr( $id_base . 'youtube_movie_url_' . $item_num ), 
								esc_attr( $name_base . '[youtube_movie_url_' . $item_num . ']' ), 
								esc_url( isset( $instance['youtube_movie_url_' . $item_num ] ) 
									? $instance['youtube_movie_url_' . $item_num ] 
									: '' 
								), 
								$atts = array(
									'class' => 'regular-text-field',
									'style' => 'width:100%;'
								) 
							);
						?></p>

					</div>

					<?php

				}

			# Post
				function get_settings_form_for_post( $instance = '', $widget_number = '', $item_num = '' ) {

					if( isset( $_REQUEST['instance_for_movie'] ) && $instance == '' ) { $instance = $_REQUEST['instance_for_movie']; }
					if( isset( $_REQUEST['widget_number'] ) && $widget_number == '' ) { $widget_number = $_REQUEST['widget_number']; }
					if( isset( $_REQUEST['item_num'] ) && $item_num == '' ) { $item_num = $_REQUEST['item_num']; }

					$widget_number = absint( $widget_number );
					$item_num = absint( $item_num );

					$item_type = ( isset( $instance['item_type_' . $item_num ] ) ? $instance['item_type_' . $item_num ] : 'none' );

					$class_name =  strtolower( get_class( $this ) );

					$widget_id = esc_attr( $class_name . '-' . $widget_number );

					$name_base = esc_attr( 'widget-' . $class_name . '[' . $widget_number . ']' );
					$id_base = esc_attr( 'widget-' . $widget_id . '-' );

					$posts = get_posts( array(
						'post_type' => 'post',
						'posts_per_page' => -1						
					) );

					?>

					<div id="<?php echo $widget_number; ?>-slide-item-post-<?php echo $item_num; ?>" 
						class="slide-item-post slide-item-settings-form" 
						style="display: <?php 
							echo esc_attr( isset( $instance['item_type_' . $item_num ] ) 
								? ( $instance['item_type_' . $item_num ] == 'post' 
									? 'block' 
									: 'none'
								) 
								: 'none'
							);
						?>;" 
						data-item-num="<?php echo $item_num; ?>"
					>
						<select 
							id="<?php echo esc_attr( $id_base . 'post_id_' . $item_num ); ?>"
							name="<?php echo esc_attr( $name_base . '[post_id_' . $item_num . ']' ); ?>"
							class="select-slide-post"
						>
							<option value="none"><?php esc_html_e( 'Select One', ShapeShifter_Extensions::TEXTDOMAIN ); ?></option>
							<?php foreach( $posts as $index => $post ) { ?>
								<option id="post-<?php echo esc_attr( $post->ID ); ?>" 
									class="post-type-<?php echo esc_attr( $post->post_type ); ?>" 
									value="<?php echo esc_attr( $post->ID ); ?>" 
									<?php echo ( isset( $instance['post_id_' . $item_num ] ) 
										? ( $instance['post_id_' . $item_num ] == intval( $post->ID ) 
											? 'selected' 
											: '' 
										) 
										: '' 
									); ?>
								><?php echo esc_html( $post->post_title ); ?></option>
							<?php } ?>
						</select>

					</div>

					<?php

				}

			# Page
				function get_settings_form_for_page( $instance = '', $widget_number = '', $item_num = '' ) {

					if( isset( $_REQUEST['instance_for_movie'] ) && $instance == '' ) { $instance = $_REQUEST['instance_for_movie']; }
					if( isset( $_REQUEST['widget_number'] ) && $widget_number == '' ) { $widget_number = $_REQUEST['widget_number']; }
					if( isset( $_REQUEST['item_num'] ) && $item_num == '' ) { $item_num = $_REQUEST['item_num']; }

					$widget_number = absint( $widget_number );
					$item_num = absint( $item_num );

					$item_type = ( isset( $instance['item_type_' . $item_num ] ) ? $instance['item_type_' . $item_num ] : 'none' );

					$class_name = strtolower( get_class( $this ) );

					$widget_id = esc_attr( $class_name . '-' . $widget_number );

					$name_base = esc_attr( 'widget-' . $class_name . '[' . $widget_number . ']' );
					$id_base = esc_attr( 'widget-' . $widget_id . '-' );

					$pages = get_pages( array(
						'posts_per_page' => -1						
					) );

					?>

					<div id="<?php echo $widget_number; ?>-slide-item-page-<?php echo $item_num; ?>" 
						class="slide-item-page slide-item-settings-form" 
						style="display:<?php 
							echo esc_attr( isset( $instance['item_type_' . $item_num ] ) 
								? ( $item_type == 'page' 
									? 'block' 
									: 'none'
								) 
								: 'none'
							);
						?>;" 
						data-item-num="<?php echo $item_num; ?>"
					>
						<select 
							id="<?php echo esc_attr( $id_base . 'page_id_' . $item_num ); ?>"
							name="<?php echo esc_attr( $name_base . '[page_id_' . $item_num . ']' ); ?>"
							class="select-slide-page"
						>
							<option value="none"><?php esc_html_e( 'Select One', ShapeShifter_Extensions::TEXTDOMAIN ); ?></option>
							<?php foreach( $pages as $index => $page ) { ?>
								<option id="page-<?php echo esc_attr( $page->ID ); ?>" 
									class="post-type-page" 
									value="<?php echo esc_attr( $page->ID ); ?>" 
									<?php echo ( isset( $instance['page_id_' . $item_num ] ) 
										? ( $instance['page_id_' . $item_num ] == intval( $page->ID ) 
											? 'selected' 
											: '' 
										) 
										: '' 
									); ?>
								><?php echo esc_html( $page->post_title ); ?></option>
							<?php } ?>
						</select>

					</div>

					<?php

				}

	function next_widget_id_number( $id_base ) {
		global $wp_registered_widgets;
		static $number = 1;
		foreach ( $wp_registered_widgets as $widget_id => $widget ) {
			if ( preg_match( '/' . $id_base . '-([0-9]+)$/', $widget_id, $matches ) )
				$number = max($number, $matches[1]);
		}
		$number++;
		return $number;
	}

}
?>