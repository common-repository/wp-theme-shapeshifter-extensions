<?php 

if ( ! defined( 'ABSPATH' ) ) exit;

class SSE_Rendering_Manager extends SSE_Unique_Abstract {

	/**
	 * Static
	**/
		/**
		 * Instance
		 * @var SSE_Rendering_Manager
		**/
		protected static $instance = null;

	/**
	 * Init
	**/
		/**
		 * Public initializer
		 * @return SSE_Rendering_Manager
		**/
		public static function get_instance()
		{
			if ( null === self::$instance ) {
				self::$instance = new Self();
			}
			return self::$instance;
		}

		/**
		 * Constructor
		**/
		function __construct() {

			add_filter( 'shapeshifter_filter_default_thumbnail_div_tag', array( $this, 'filter_default_thumbnail_div_tag' ), 10, 4 );
			add_filter( 'shapeshifter_filter_default_thumbnail_img_tag', array( $this, 'filter_default_thumbnail_img_tag' ), 10, 5 );
			add_filter( 'shapeshifter_filter_post_thumbnail_div_tag', array( $this, 'sse_filter_post_thumbnail_div_tag' ), 10, 5 );

			// Init
			do_action( 'sse_action_init_rendering_methods' );

		}

		/**
		 * Get Default Post Thumbnail in Div Tag
		 * 
		 * @static
		 * 
		 * @param string $return
		 * @param string $class                  : Default "default-post-thumbnail"
		 * @param array  $size                   : Default
		 * 		'width' => "{$int}px",
		 * 		'height' => "{$int}px",
		 * @param string $optional_def_image_url : Default ""
		 * 
		 * @see ShapeShifter_Frontend_Methods::shapeshifter_get_generated_tag( $element, $atts = array(), $text = '', $wrap = false )
		 * @see filter "shapeshifter_filter_default_thumbnail_div_tag"
		 * 
		 * @return string
		**/
		function filter_default_thumbnail_div_tag( $return, $class, $size, $optional_def_image_url ) {

			// Default Thumbnail URL
			$default_thumbnail_url = esc_url( 
				$optional_def_image_url 
				? $optional_def_image_url 
				: get_theme_mod( 'default_thumbnail_image', '' )
				//: get_theme_mod( 'default_thumbnail_image', SHAPESHIFTER_ASSETS_DIR_URI . 'images/no-img.png' )
			);

			if ( SSE_IS_LAZYLOAD_ON && ! is_customize_preview() ) {

				$atts = array(
					'class' => esc_attr( $class ? $class . ' ' . $class . '-lazy default-thumbnail-lazy' : 'default-thumbnail default-thumbnail-lazy' ),
					'data-original' => $default_thumbnail_url,
					'data-style' => esc_attr( 'width: ' . $size[ 'width' ] . '; height: ' . $size[ 'height' ] . '; background-image: url(' . $default_thumbnail_url . '); background-size: ' . $size[ 'width' ] . ' ' . $size[ 'height' ] . '; background-position: center center; background-repeat: no-repeat;' )
				);
				$return = ShapeShifter_Frontend_Methods::shapeshifter_get_generated_tag( 'div', $atts, '', true );
				$atts = array(
					'class' => esc_attr( $class ? $class . ' default-thumbnail lazy' : 'default-thumbnail lazy' ),
					'style' => esc_attr( 'width: ' . $size[ 'width' ] . '; height: ' . $size[ 'height' ] . '; background-image: url(' . $default_thumbnail_url . '); background-size: ' . $size[ 'width' ] . ' ' . $size[ 'height' ] . '; background-position: center center; background-repeat: no-repeat;' )
				);
				$noscript = ShapeShifter_Frontend_Methods::shapeshifter_get_generated_tag( 'div', $atts, '', true );
				$return .= '<noscript>' . $noscript . '</noscript>';

			}

			return $return;

		}
		/**
		 * Get Default Post Thumbnail in Div Tag
		 * 
		 * @static
		 * 
		 * @param string $return
		 * @param string $class                  : Default "default-post-thumbnail"
		 * @param array  $size                   : Default
		 * 		'width' => "{$int}px",
		 * 		'height' => "{$int}px",
		 * @param string $optional_def_image_url : Default ""
		 * 
		 * @see ShapeShifter_Frontend_Methods::shapeshifter_get_generated_tag( $element, $atts = array(), $text = '', $wrap = false )
		 * @see filter "shapeshifter_filter_default_thumbnail_img_tag"
		 * 
		 * @return string
		**/
		public function filter_default_thumbnail_img_tag( $return, $class = 'default-post-thumbnail', $size = array( 'width' => 80, 'height' => 80 ), $alt = '', $optional_def_image_url = '' ) {

			$default_thumbnail_url = esc_url( 
				$optional_def_image_url 
				? $optional_def_image_url 
				: get_theme_mod( 'default_thumbnail_image', SHAPESHIFTER_ASSETS_DIR_URI . 'images/no-img.png' )
			);

			if ( SSE_IS_LAZYLOAD_ON && ! is_customize_preview() ) { // lazy-loadの場合

				$atts = array(
					'class' => esc_attr( $class ? $class . ' ' . $class . '-lazy default-thumbnail-lazy' : 'default-thumbnail default-thumbnail-lazy' ),
					'data-original' => esc_url( $default_thumbnail_url ),
					'width' => absint( $size[ 'width' ] ),
					'height' => absint( $size[ 'height' ] ),
					'alt' => esc_attr( $alt )
				);
				$return = ShapeShifter_Frontend_Methods::shapeshifter_get_generated_tag( 'img', $atts, '', true );
				$atts = array(
					'class' => esc_attr( $class ? $class . ' default-thumbnail lazy' : 'default-thumbnail lazy' ),
					'src' => esc_url( $default_thumbnail_url ),
					'width' => absint( $size[ 'width' ] ),
					'height' => absint( $size[ 'height' ] ),
					'alt' => esc_attr( $alt )
				);
				$noscript = ShapeShifter_Frontend_Methods::shapeshifter_get_generated_tag( 'img', $atts, '', true );
				$return .= '<noscript>' . $noscript . '</noscript>';

			}

			return $return;

		}

		/**
		 * Get Default Thumbnail IMG Tag
		 * 
		 * @static
		 * 
		 * @param int $post_id
		 * @param string $class
		 * @param array $size
		 * 		'width' => "{$int}px",
		 * 		'height' => "{$int}px",
		 * 
		 * @see ShapeShifter_Frontend_Methods::shapeshifter_get_generated_tag( $element, $atts = array(), $text = '', $wrap = false )
		 * 
		 * @return string
		**/
		function filter_post_thumbnail_div_tag( $post_id, $div_class = 'post-thumbnail', $size = array( 'width' => '80px', 'height' => '80px' ) ) {

			$post_thumbnail_url = esc_url( get_the_post_thumbnail( $post_id ) );

			if ( ! $post_thumbnail_url ) {
				return;
			}

			if ( SSE_IS_LAZYLOAD_ON && ! is_customize_preview() ) { // lazy-loadの場合

				$atts = array(
					'class' => esc_attr( $div_class ? $div_class . ' default-thumbnail-lazy' : 'default-thumbnail-lazy' ),
					'data-original' => $post_thumbnail_url,
					'data-style' => 'background-image: url(' . $post_thumbnail_url . '); background-size: ' . esc_attr( $size[ 'width' ] ) . ' ' . esc_attr( $size[ 'height' ] ) . '; background-position: center center; background-repeat: no-repeat;'
				);
				$return = ShapeShifter_Frontend_Methods::shapeshifter_get_generated_tag( 'div', $atts );
				$atts = array(
					'class' => esc_attr( $div_class ? $div_class . ' default-thumbnail lazy' : 'default-thumbnail lazy' ),
					'style' => 'background-image: url(' . $post_thumbnail_url . '); background-size: ' . esc_attr( $size[ 'width' ] ) . ' ' . esc_attr( $size[ 'height' ] ) . '; background-position: center center; background-repeat: no-repeat;'
				);
				$noscript = ShapeShifter_Frontend_Methods::shapeshifter_get_generated_tag( 'div', $atts );
				$return .= '<noscript>' . $noscript . '</noscript>';

			}

			return $return;

		}

}
