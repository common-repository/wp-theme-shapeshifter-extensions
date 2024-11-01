<?php

if ( ! defined( 'ABSPATH' ) ) exit;

class SSE_Frontend_Filter_Manager {

	/**
	 * Static
	**/
		/**
		 * Instance
		 * @var SSE_Frontend_Filter_Manager
		**/
		protected static $instance = null;

	/**
	 * Init
	**/
		/**
		 * Public Initializer
		 * @return SSE_Frontend_Filter_Manager
		**/
		public static function get_instance( SSE_Frontend_Manager $frontend_manager )
		{
			if ( null === self::$instance ) self::$instance = new Self( $frontend_manager );
			return self::$instance;
		}

		/**
		 * Constructor
		**/
		protected function __construct( SSE_Frontend_Manager $frontend_manager )
		{
			$this->frontend_manager  = $frontend_manager;
			$this->rendering_methods = $frontend_manager->get_rendering_methods();
			$this->init_hooks();
		}

		protected function init_hooks()
		{

			// Contents
			add_filter( 'the_content', array( $this, 'content_filter' ), 11 );

			//add_filter( 'the_excerpt', array( $this, 'filter_excerpt' ) );

			// Widget Thumbnail Image
				// IMG to DIV
				add_filter( 'shapeshifter_filter_widget_entry_thumbnail_image', array( $this, 'convert_img_to_div' ) );

			// JS CSS
				// Append 'property="url" to CSS Link Tags
				add_filter( 'style_loader_tag', array( $this, 'add_property_css_filters' ), 10, 2 );
				// Append attr 'async' to JS Script Tags
				if( ! is_admin() )
					add_filter( 'script_loader_tag', array( $this, 'async_scripts_filter' ), 10, 2 );

			// Widget Areas
				// Mobile
				add_filter( 'shapeshifter_filter_mobile_sidebar', array( $this, 'filter_widget_areas_for_mobile' ) );

		}

	/**
	 * Filters
	**/
		// Content
			/**
			 * Filter Method in hook "the_content"
			 * 
			 * @param string $content
			 * 
			 * @return string
			**/
			function content_filter( $content ) {

				// Lazyload
				if( SHAPESHIFTER_IS_LAZYLOAD_ON && ! is_customize_preview() ){ 
					$content = preg_replace_callback(
						'/(<img[^>]+)(src\s*=\s*[\'"][^\'"]+[\'"])([^>]*\/>)/i', 
						array( $this, 'preg_lazyload' ), 
						$content
					);
				}

				return $content;

			}

				/**
				 * Lazyload filter method
				 * 
				 * @param string $img_match
				 * 
				 * @return string
				**/
				function preg_lazyload( $img_match ) {

					$img_replace = $img_match[ 1 ] . 'src="' . esc_url( SSE_ASSETS_URL . 'images/dummy.png' ) . '" data-original' . substr( $img_match[ 2 ], 3 ) . ' data-mobile' . substr( $img_match[ 2 ], 3 ) . $img_match[3];
					$img_replace = preg_replace( '/class\s*=\s*"/i', 'class="lazy ', $img_replace );
					if( strpos( $img_replace, 'class=' ) === false ) {
						$img_replace = preg_replace(
							'/(<img[^>]+)([^>]*\/>)/i',
							'${1} class="lazy" ${2}',
							$img_replace
						);
					}
					$img_replace .= '<noscript>' . $img_match[ 0 ] . '</noscript>'; $img_match = null;
					return $img_replace;

				}

		// Excerpt
			/**
			 * Filter Excerpt
			 * @param string  $post_content
			 * @param int     $excerpt_length
			 * @return string
			**/
			function filter_excerpt( $post_content, $excerpt_length = 200 ) { // 抜粋を取得（200字）

				// 空白や改行、HTMLタグ、ショートコードを除去
				$the_excerpt = preg_replace( '/\[[^\]]+]/i', '', $post_content );
				$the_excerpt = wp_strip_all_tags( $the_excerpt );
				$the_excerpt = str_replace( array( "\n", "\r", '　', '"' ), '', $the_excerpt );
				$the_excerpt = mb_ereg_replace( "/[^a-zA-Z0-9]\s[^a-zA-Z0-9]/i", '', $the_excerpt );
				return mb_substr( $the_excerpt, 0, $excerpt_length );

			}

		// JS CSS
			/**
			 * Append 'property="url" to CSS Link Tags
			 * 
			 * @param string $tag
			 * @param string $handle
			 * 
			 * @return string
			**/
			function add_property_css_filters( $tag, $handle ) {
				
				if( strpos( $tag, 'property' ) ) {

					return $tag;

				} else {

					return str_replace( ' rel', ' property="url" rel', $tag );

				}

			}

			/**
			 * Append attr 'async' to JS Script Tags
			 * 
			 * @param string $tag
			 * @param string $handle
			 * 
			 * @return string
			**/
			function async_scripts_filter( $tag, $handle ) {

				if( is_customize_preview() || $handle === 'jquery-core' )
					return $tag;

				# Async
					$data_option_sa = sse()->get_option( 'speed_adjust' );
					if( $data_option_sa->get_prop( 'async_script_on' ) ) { 

						if( ! preg_match( '/<script[^>]+><\/script>/i', $tag ) )
							return $tag;

						if( is_array( $this->script_handles_array ) 
							&& in_array( $handle, $this->script_handles_array )
						) {

							return $tag;

						} else if( 
							$this->script_handles_array == $handle 
							|| strpos( $tag, 'async' ) !== false 
							|| strpos( $handle, 'jquery-core' ) !== false
						) {

							return $tag;

						}

						return str_replace( ' src', ' id="' . esc_attr( $handle ) . '-js" async defer src', $tag );

					}

				return $tag;
				
			}

		// Widget Thumbnail Image
			/**
			 * IMG to DIV
			 * 
			 * @param string $tag
			 * 
			 * @return string
			**/
			function thumbnail_filter( $tag ){

				if( preg_match( '/class\s*=\s*[\'"]attachment\-shop\_thumbnail/i', $tag ) ) {

					return $tag;

				} elseif( ! ( function_exists( 'is_woocommerce' ) 
					&& ( is_woocommerce() || is_cart() || is_checkout() || is_account_page() ) 
				) ) {

					// Convert IMG into DIV
					$tag = $this->convert_img_to_div( $tag ); 

				}

				return $tag;

			}

			/**
			 * Convert IMG into DIV
			 * 
			 * @param string $tag
			 * 
			 * @return string
			**/
			function convert_img_to_div( $tag ) {

				// Attributes to filter
				$atts = array();
				$search = '/(src|class|data-style|style)\s*=\s*[\'"]([^\'"]+)[\'"]/i';

				// Searching them and define as $searched
				preg_match_all( $search, $tag, $searched );
				$search = $tag = null;
				$i = 0;
				$limit = count( $searched[ 1 ] );
				for( $i = 0; $i < $limit; $i++ ) {

					// Get Each Attribute
					if( $searched[ 1 ][ $i ] == 'src' ) { $atts['src'] = $searched[ 2 ][ $i ]; }
					if( $searched[ 1 ][ $i ] == 'class' ) { $atts['class'] = $searched[ 2 ][ $i ]; }
					if( $searched[ 1 ][ $i ] == 'data-style' ) { $atts['data_style'] = $searched[ 2 ][ $i ]; }
					if( $searched[ 1 ][ $i ] == 'style' ) { $atts['style'] = $searched[ 2 ][ $i ]; }

				} 
				$atts['src'] = ( isset( $atts['src'] ) ? $atts['src'] : '' );
				$atts['class'] = ( isset( $atts['class'] ) ? $atts['class'] : '' );
				$atts['data_style'] = ( isset( $atts['data_style'] ) ? $atts['data_style'] : '' );
				$atts['style'] = ( isset( $atts['style'] ) ? $atts['style'] : '' );

				// Return DIV tag
				if( SHAPESHIFTER_IS_LAZYLOAD_ON && ! is_customize_preview() ) {
					return '<div class="' . esc_attr( $atts['class'] ) . '" data-original="' . esc_url( $atts['src'] ) . '" data-style="' . esc_attr( $atts['data_style'] ) . '"></div>' . PHP_EOL .
					'<noscript><div class="' . esc_attr( $atts['class'] ) . '" style="' . esc_attr( $atts['style'] ) . ' background: url(' . esc_url( $atts['src'] ) . '); ' . esc_attr( $atts['data_style'] ) . '"></div></noscript>';
				}
				return '<div class="' . esc_attr( $atts['class'] ) . '" style="' . esc_attr( $atts['style'] ) . ' background: url(' . esc_url( $atts['src'] ) . '); ' . esc_attr( $atts['data_style'] ) . '" data-original="' . esc_url( $atts['src'] ) . '" data-style="' . esc_attr( $atts['data_style'] ) . '"></div>';

			}

		// Widget Areas
			/**
			 * Before 1st 2h
			 * 
			 * @param string $before_1st_2h
			 * 
			 * @return string
			**/
			function filter_widget_areas_before_1st_2h( $before_1st_2h ) {
				$before_1st_2h .= sse()->get_widget_area_manager()->get_widget_areas( 'before_1st_h2_of_content' );
				return $before_1st_2h;
			}

			/**
			 * Mobile
			 * 
			 * @param string $mobile_side_menu_total
			 * 
			 * @return string
			**/
			function filter_widget_areas_for_mobile( $mobile_side_menu_total ) {

				$mobile_side_menu_total .= sse()->get_widget_area_manager()->get_widget_areas( 'is_on_mobile_menu' );

				return $mobile_side_menu_total;

			}


}

