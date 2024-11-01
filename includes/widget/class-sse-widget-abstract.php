<?php
if( ! class_exists( 'SSE_Widget' ) ) {
/**
 * ShapeShifter Extensions Widget Base
 * 
**/
abstract class SSE_Widget extends WP_Widget {

	#
	# Vars
	#
		/**
		 * Vegas Transition Reference
		 * 
		 * @var array $vegas_transition_array
		**/
		protected static $vegas_transition_array = array(
			'fade' => 'fade',
			'fade2' => 'fade2',
			'slideLeft' => 'slideLeft',
			'slideLeft2' => 'slideLeft2',
			'slideRight' => 'slideRight',
			'slideRight2' => 'slideRight2',
			'slideUp' => 'slideUp',
			'slideUp2' => 'slideUp2',
			'slideDown' => 'slideDown',
			'slideDown2' => 'slideDown2',
			'zoomIn' => 'zoomIn',
			'zoomIn2' => 'zoomIn2',
			'zoomOut' => 'zoomOut',
			'zoomOut2' => 'zoomOut2',
			'swirlLeft' => 'swirlLeft',
			'swirlLeft2' => 'swirlLeft2',
			'swirlRight' => 'swirlRight',
			'swirlRight2' => 'swirlRight2',
			'burn' => 'burn',
			'burn2' => 'burn2',
			'blur' => 'blur',
			'blur2' => 'blur2',
			'flash' => 'flash',
			'flash2' => 'flash2'
		);

		/**
		 * Widget Slider Number Count
		 * 
		 * @var int $widget_slider_number_count
		**/
		protected static $widget_slider_number_count = 0;

	#
	# Init
	#
		/**
		 * Constructor
		**/
		function __construct( $id_base, $name, $widget_options = array(), $control_options = array() ) {

			parent::__construct( $id_base, $name, $widget_options, $control_options );

		}

	#
	# Generals
	#
		/**
		 * Print HTML for Entry LI of Widget
		 * 
		 * @param obj $post
		 * @param string $class_prefix
		 * @param bool $display_excerpt
		 * @param bool $display_date
		 * 
		 * @return void
		**/
		public static function print_widget_entry_li( $post, $class_prefix, $display_excerpt = false, $display_date = false ) { 

			include( SHAPESHIFTER_EXTENSIONS_TEMPLATES_DIR . 'widgets/frontend/widget-frontend-entry-li.php' );

		}

		/**
		 * Get Current URL by Query for SNS Share
		 * 
		 * @return string
		**/
		public static function shapeshifter_get_current_url_by_query_for_sns_share() {

			global $post;
			global $wp_query;

			// URLを取得
			if( is_search() || is_paged() || is_404() || is_attachment() ) {
				return false;
			} elseif( is_home() || is_front_page() ) {
				$permalink_url = urlencode( esc_url( SITE_URL ) );
			} elseif( function_exists( 'is_woocommerce' ) && is_woocommerce() ) { // WooCommerce
				if( is_shop() ) { // The main shop
					$permalink_url = urlencode( esc_url( get_post_type_archive_link( 'product' ) ) );
				} elseif( is_product_taxonomy() ) { // タクソノミーページ
					if( is_product_category() ) { // A product category
						$permalink_url = urlencode( esc_url( get_term_link( $wp_query->queried_object->term_id, $wp_query->queried_object->taxonomy ) ) );
					} elseif( is_product_tag() ) { // A product tag
						$permalink_url = urlencode( esc_url( get_term_link( $wp_query->queried_object->term_id, $wp_query->queried_object->taxonomy ) ) );
					}
				} elseif( is_product() ) { // A single product
					$permalink_url = urlencode( esc_url( get_permalink( $post->ID ) ) );
				}
			} elseif( function_exists( 'is_woocommerce' ) && is_cart() ) { // The cart
				return false;
			} elseif( function_exists( 'is_woocommerce' ) && is_checkout() ) { // The checkout
				return false;
			} elseif( function_exists( 'is_woocommerce' ) && is_account_page() ) { // Customer account
				return false;
			} elseif( function_exists( 'is_bbpress' ) && is_bbpress() ) { // bbPress
				$permalink_url = urlencode( esc_url( get_permalink( $post->ID ) ) );
			} elseif( is_singular() ) {
				$permalink_url = urlencode( esc_url( get_permalink( $post->ID ) ) );
			} elseif( is_archive() ) {
				if( is_category() || is_tag() || is_tax() ) {
					$permalink_url = urlencode( esc_url( get_term_link( $wp_query->queried_object->term_id, $wp_query->queried_object->taxonomy ) ) );
				} elseif( is_author() ) {
					$permalink_url = urlencode( esc_url( get_author_posts_url( $wp_query->queried_object->data->ID ) ) );
				} elseif( is_date() ) {
					if( is_year() ) {
						$permalink_url = urlencode( esc_url( get_year_link( $wp_query->query['year'] ) ) );
					} elseif( is_month() ) {
						$permalink_url = urlencode( esc_url( get_month_link( $wp_query->query['year'], $wp_query->query['monthnum'] ) ) );
					} elseif( is_day() ) {
						$permalink_url = urlencode( esc_url( get_day_link( $wp_query->query['year'], $wp_query->query['monthnum'], $wp_query->query['day'] ) ) );
					} else {
						return false;
					}
				} else {
					return false;
				}
			} else {
				return false;
			}
			return $permalink_url;

		}

		public static function shapeshifter_get_the_excerpt( $post_content, $excerpt_length = 200 ) { // 抜粋を取得（200字）

			// 空白や改行、HTMLタグ、ショートコードを除去
			$the_excerpt = preg_replace( '/\[[^\]]+]/i', '', $post_content );
			$the_excerpt = wp_strip_all_tags( $the_excerpt );
			$the_excerpt = str_replace( array( "\n", "\r", '　', '"' ), '', $the_excerpt );
			$the_excerpt = mb_ereg_replace( "/[^a-zA-Z0-9]\s[^a-zA-Z0-9]/i", '', $the_excerpt );
			return mb_substr( $the_excerpt, 0, $excerpt_length );

		}

		public static function shapeshifter_get_tax_query_post_formats( $post_formats ) {

			$post_formats = wp_parse_args( $post_formats, 
				array(
					'standard' => true,
					'aside' => true,
					'gallery' => true,
					'image' => true,
					'link' => true,
					'quote' => true,
					'status' => true,
					'video' => true,
					'audio' => true,
					'chat' => true,
				)
			);

			$post_format_terms = array();
			if( $post_formats['standard'] != '' ) {
				
				foreach( $post_formats as $post_format => $text ) {

					if( $post_format == 'standard' ) continue;
					if( $text == '' )
						$post_format_terms[] = esc_attr( 'post-format-' . $post_format );

				}

				return array(
					'taxonomy' => 'post_format',
					'field'	=> 'slug',
					'terms'	=> $post_format_terms,
					'operator' => 'NOT IN'
				);

			} else {

				foreach( $post_formats as $post_format => $text ) {

					if( $post_format == 'standard' ) continue;
					if( $text != '' ) 
						$post_format_terms[] = esc_attr( 'post-format-' . $post_format );

				}

				return array(
					'taxonomy' => 'post_format',
					'field'	=> 'slug',
					'terms'	=> $post_format_terms,
				);

			}

		}

		// Images
			public static function shapeshifter_the_default_thumbnail_url( $post ) {
				echo esc_url( shapeshifter_get_the_default_thumbnail_url( $post ) );
			}
			public static function shapeshifter_get_the_default_thumbnail_url( $post ) {

				$cat = get_the_category( $post->ID );

				if( isset( $cat[ 0 ] ) ) {
					$default_cat_thumbnail = get_term_meta( $cat[ 0 ]->term_id, 'shapeshifter_term_default_thumbnail', true );
				} else {
					$default_cat_thumbnail = '';
				}

				return ( 
					$default_cat_thumbnail != ''
					? esc_url( $default_cat_thumbnail )
					: get_theme_mod( 'default_thumbnail_image', SSE_ASSETS_URL . 'images/no-img.png' )
				);

			}
			// デフォルトのサムネイルdivタグ
				public static function shapeshifter_default_thumbnail_div_tag( $class = 'default-post-thumbnail', $size = array( 'width' => '80px', 'height' => '80px' ) ) {
					echo self::shapeshifter_get_default_thumbnail_div_tag( $class, $size );
				}
				public static function shapeshifter_get_default_thumbnail_div_tag( $class = 'default-post-thumbnail', $size = array( 'width' => '80px', 'height' => '80px' ), $optional_def_image_url = '' ) {

					// カスタマイザーでデフォルトを設定している場合
					$default_thumbnail_url = esc_url( $optional_def_image_url ? $optional_def_image_url : get_theme_mod( 'default_thumbnail_image', SSE_ASSETS_URL . 'images/no-img.png' ) );	

					if( SHAPESHIFTER_IS_LAZYLOAD_ON && ! is_customize_preview() ) { // lazy-loadの場合
						$atts = array(
							'class' => esc_attr( $class ? $class . ' ' . $class . '-lazy default-thumbnail-lazy' : 'default-thumbnail default-thumbnail-lazy' ),
							'data-original' => $default_thumbnail_url,
							'data-style' => esc_attr( 'width: ' . $size['width'] . '; height: ' . $size['height'] . '; background-image: url(' . $default_thumbnail_url . '); background-size: ' . $size['width'] . ' ' . $size['height'] . '; background-position: center center; background-repeat: no-repeat;' )
						);
						$return = self::shapeshifter_get_generated_tag( 'div', $atts, '', true );
						$atts = array(
							'class' => esc_attr( $class ? $class . ' default-thumbnail lazy' : 'default-thumbnail lazy' ),
							'style' => esc_attr( 'width: ' . $size['width'] . '; height: ' . $size['height'] . '; background-image: url(' . $default_thumbnail_url . '); background-size: ' . $size['width'] . ' ' . $size['height'] . '; background-position: center center; background-repeat: no-repeat;' )
						);
						$return .= '<noscript>' . $return . '</noscript>';
					} else { // 通常の場合
						$atts = array(
							'class' => esc_attr( $class ? $class . ' default-thumbnail' : 'default-thumbnail' ),
							'style' => esc_attr( 'width: ' . $size['width'] . '; height: ' . $size['height'] . '; background-image: url(' . $default_thumbnail_url . '); background-size: ' . $size['width'] . ' ' . $size['height'] . '; background-position: center center; background-repeat: no-repeat;' )
						);
						$return = self::shapeshifter_get_generated_tag( 'div', $atts, '', true );
					}

					return $return;

				}

			// ポストサムネイルをdivタグ
				public static function shapeshifter_post_thumbnail_div_tag( $post_id, $div_class = 'post-thumbnail', $size = array( 'width' => '80px', 'height' => '80px' ) ) {
					echo self::shapeshifter_get_post_thumbnail_div_tag( $post_id, $div_class, $size );
				}
				public static function shapeshifter_get_post_thumbnail_div_tag( $post_thumbnail_url, $div_class = 'post-thumbnail', $size = array( 'width' => '80px', 'height' => '80px' ) ) {

					if( empty( $post_thumbnail_url ) )
						return;

					$post_thumbnail_url = esc_url( $post_thumbnail_url );

					if( SHAPESHIFTER_IS_LAZYLOAD_ON 
						&& ! is_customize_preview() 
					) { // lazy-loadの場合
						$atts = array(
							'class' => esc_attr( $div_class ? $div_class . ' default-thumbnail-lazy' : 'default-thumbnail-lazy' ),
							'data-original' => $post_thumbnail_url,
							'data-style' => esc_attr( 'background-image: url(' . $post_thumbnail_url . '); background-size: ' . $size['width'] . ' ' . $size['height'] . '; background-position: center center; background-repeat: no-repeat;' )
						);
						$return = self::shapeshifter_get_generated_tag( 'div', $atts );
						$atts = array(
							'class' => esc_attr( $div_class ? $div_class . ' default-thumbnail lazy' : 'default-thumbnail lazy' ),
							'style' => esc_attr( 'background-image: url(' . $post_thumbnail_url . '); background-size: ' . $size['width'] . ' ' . $size['height'] . '; background-position: center center; background-repeat: no-repeat;' )
						);
						$return .= '<noscript>' . $return . '</noscript>';
					} else { // 通常の場合
						$atts = array(
							'class' => esc_attr( $div_class ),
							'style' => esc_attr( 'background-image: url(' . $post_thumbnail_url . '); background-size: ' . $size['width'] . ' ' . $size['height'] . '; background-position: center center; background-repeat: no-repeat;' )
						);
						$return = self::shapeshifter_get_generated_tag( 'div', $atts );
					}
					// フィルター
					return $return;
				}

			// General Element
				public static function shapeshifter_generated_tag( $element, $atts = array(), $text = '', $wrap = false ) {
					echo shapeshifter_get_generated_tag( $element, $atts, $text, $wrap );
				}
				public static function shapeshifter_get_generated_tag( $element, $atts = array(), $text = '', $wrap = false ) {
					$return = '<' . $element;
					foreach( $atts as $key => $val ) { $return .= ' ' . $key . '="' . esc_attr( $val ) . '"'; }
					if( $wrap ) {
						$return .= '>' . esc_html( $text ) . '</' . $element . '>';
					} else {
						if( $text != '' ) { $return .= ' ' . SHAPESHIFTER_THEME_PREFIX . 'data-text="' . esc_attr( $text ) . '"'; }
						$return .= '/>';
					}
					return $return;
				}

	#
	# Forms
	#
		// フォーム用のメソッド
		function shapeshifter_print_input_tag( $label, $type, $id, $name, $value, $atts = array() ) {

			if( $type != 'hidden' && ! empty( $label ) )
				echo '<label for="' . esc_attr( $id ) . '"><strong>' . esc_html( $label ) . '</strong></label><br>';

			echo '<input ';
				echo 'type="' . esc_attr( $type ) . '" ';
				echo 'id="' . esc_attr( $id ) . '" ';
				echo 'name="' . esc_attr( $name ) . '" ';
				echo 'value="' . esc_attr( $value ) . '" ';
				if( is_array( $atts ) ) {
					foreach( $atts as $attr_name => $attr_val ) {
						echo $attr_name . '="' . esc_attr( $attr_val ) . '" ';
					}
				}
			echo '>';

		}

		function shapeshifter_print_textarea_tag( $label, $id, $name, $value, $wp_editor = true, $atts = array() ) {

			// 「$atts」は、「$wp_editor」の真偽値によって扱いが異なる。

			if( ! empty( $label ) )
				echo '<label for="' . esc_attr( $id ) . '"><strong>' . esc_html( $label ) . '</strong></label><br>';

			if( $wp_editor ) {

				$settings = wp_parse_args( $atts, array(
					'textarea_name' => esc_attr( $name ),
					'quicktags' => true,
					'tinymce' => false,
					'media_buttons' => false,
				) );

				wp_editor( $value, $id, $settings );

			} else {

				echo '<textarea ';
					echo 'name="' . esc_attr( $name ) . '" ';
					echo 'id="' . esc_attr( $id ) . '" ';
					if( is_array( $atts ) ) { foreach( $atts as $attr_name => $attr_val ) {
						echo $attr_name . '="' . esc_attr( $attr_val ) . '" ';
					} }
				echo '>' . esc_textarea( $value ) . '</textarea>';

			}

		}
		function shapeshifter_print_checkbox( $label, $id, $name, $value, $current_value, $atts = array() ) {

			if( ! empty( $label ) )
				echo '<label for="' . esc_attr( $id ) . '"><strong>' . esc_html( $label ) . '</strong></label>';

			echo '<input ';
				echo 'type="checkbox" ';
				echo 'id="' . esc_attr( $id ) . '" ';
				echo 'name="' . esc_attr( $name ) . '" ';
				echo 'value="' . esc_attr( $value ) . '" ';
				if( is_array( $atts ) ) { foreach( $atts as $attr_name => $attr_val ) {
					echo $attr_name . '="' . esc_attr( $attr_val ) . '" ';
				} }
				echo ( $value == $current_value ? 'checked' : '' );
			echo '>';

		}
		function shapeshifter_print_select_tag( $label, $id, $name, $current_value, $choices, $atts = array() ) {

			if( ! empty( $label ) )
				echo '<label for="' . esc_attr( $id ) . '"><strong>' . esc_html( $label ) . '</strong></label><br>';

			echo '<select ';
				echo 'id="' . esc_attr( $id ) . '" ';
				echo 'name="' . esc_attr( $name ) . '" ';
				if( is_array( $atts ) ) { foreach( $atts as $attr_name => $attr_val ) {
					echo $attr_name . '="' . esc_attr( $attr_val ) . '" ';
				} }
			echo '>';
				echo '<option value="none">' . esc_html__( 'Select', ShapeShifter_Extensions::TEXTDOMAIN ) . '</option>';
				if( is_array( $choices ) ) { foreach( $choices as $val => $text ) {
					echo '<option value="' . esc_attr( $val ) . '" ' . ( $val == $current_value ? 'selected' : '' ) . '>' . esc_html( $text ) . '</option>';
				} }
			echo '</select>';

		}
		function shapeshifter_print_image_select( $label, $id, $name, $src ) {
			
			echo '<label for="' . esc_attr( $id ) . '"><strong>' . esc_html( $label ) . '</strong></label><br>';

			echo '<div id="image-box-' . esc_attr( $id ) . '" class="image-box">
				<img id="img-tag-' . esc_attr( $id ) . '" src="' . esc_url( $src ) . '" class="printed-img-tag" style="width:100px; height:100px;">
			</div>
			<div style="clear:both;margin-bottom:10px;"></div>

			<input  
				id="' . esc_attr( $id ) . '" 
				name="' . esc_attr( $name ) . '" 
				type="hidden" 
				value="' . esc_url( $src ) . '" 
			/>';

		}

		// JS関数用のボタン
		function shapeshifter_print_js_call_a_button( $text, $id, $class, $atts = array(), $js_func = '', $js_args = array() ) {
			echo '<a ';
				echo 'id="' . esc_attr( $id ) . '" ';
				echo 'class="' . esc_attr( $class ) . '" ';
				echo 'href="javascript:';

				if( $js_func != '' ) {
					echo $js_func . '(';

						if( is_array( $js_args ) && isset( $js_args[ 0 ] ) ) {
							
							$number = count( $js_args );

							foreach( $js_args as $index => $arg ) {

								if( $arg['quote'] == true ) echo '\'';

								echo $arg['value'];

								if( $arg['quote'] == true ) echo '\'';

								if( $number != ( $index + 1 ) ) echo ',';

							}

						}

					echo ');" ';
				} else {
					echo 'void(0);" ';
				}
				if( $atts ) { foreach( $atts as $attr_name => $attr_val ) {
					echo $attr_name . '="' . esc_attr( $attr_val ) . '" ';
				} }


			echo '>' . esc_html( $text ) . '</a>';
		}

	#
	# Vegas
	#
		# Output
		function output_vegas_background_images_for_widget( $instance ) {

			$image_url_str = esc_attr( $instance['image_url_str'] );
			$image_slider_preg_str = '/([^, ]+)/';
			if( preg_match_all( $image_slider_preg_str, $image_url_str, $image_slider_preg_str_s ) ) {
				$image_slider_array = $image_slider_preg_str_s[ 0 ];
			} $image_url_str = $image_slider_preg_str = $image_slider_preg_str_s = null;
			$array_count = ( isset( $image_slider_array ) ? count( $image_slider_array ) : 0 );

			$download_delay = intval( $instance['download_delay'] );
			$download_transition = esc_attr( $instance['download_transition'] );
			$download_transition_duration = intval( $instance['download_transition_duration'] );

			if( $array_count <= 0 ) { 
				return; 
			} else { ?>
				<script>
					if( typeof vegasData == "undefined" ) {
						var vegasData = [];
					}
					vegasData.push( {
						"selectorId": "#<?php echo $this->id; ?>",
						"properties": {
							delay: <?php echo $download_delay; ?>,
							transition: '<?php echo $download_transition; ?>',
							transitionDuration: <?php echo $download_transition_duration; ?>,
							shuffle: true,
							slides: [ 
								<?php for( $i = 0; $i < $array_count; $i++ ) {
								?>{ src: "<?php echo $image_slider_array[ $i ]; ?>" }<?php 
									if( $i + 1 !== $array_count ) { echo ','; }
								} 
							?>]
						}
					} );
				</script>
			<?php }

		}

		# Sanitize
		function update_vegas_background_images_for_widget( $new_instance, $instance ) {

			// Image URLs
			$image_url_str = str_replace( ' ', '', $new_instance['image_url_str'] );
			$image_url_in_array = preg_split( '/([,]+)/', $image_url_str );


			$instance['image_url_str'] = sanitize_text_field( isset( $new_instance['image_url_str'] ) ? $new_instance['image_url_str'] : '' );
			$instance['download_transition'] = sanitize_text_field( isset( $new_instance['download_transition'] ) ? $new_instance['download_transition'] : '' );
			$instance['download_delay'] = absint( $new_instance['download_delay'] );
			$instance['download_transition_duration'] = absint( $new_instance['download_transition_duration'] );

			return $instance;

		}

		# Form
		function form_vegas_background_images_for_widget( $instance ) {

			include( SHAPESHIFTER_EXTENSIONS_TEMPLATES_DIR . 'widgets/forms/widget-form-vegas-background-images.php' );

		}


}
}
?>