<?php

if ( ! defined( 'ABSPATH' ) ) exit;

class SSE_Shortcode_Manager extends SSE_Unique_Abstract {

	/**
	 * Init
	**/
		/**
		 * Public Initializer
		 * @return SSE_Shortcode_Manager
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

			// Add Shortcodes
				// New Posts
				add_shortcode( 'shapeshifter_new_entries', array( $this, 'shapeshifter_new_entries' ) );
				// Search Posts
				add_shortcode( 'shapeshifter_search_entries', array( $this, 'shapeshifter_search_entries' ) );

		}

	/**
	 * Shortcodes
	**/
		/**
		 * New Entries
		 * @param  array  $atts
		 * @return string      
		**/
		function shapeshifter_new_entries( $atts = array() ) {

			$atts = shortcode_atts( array(
				'number'          => 3,
				'slide-type'      => 'standard',
				'is-thumbnail-on' => 'true',
				'excerpt-number'  => '200',
			), $atts );

			$args = array(
				'post_type'      => 'post',
				'post_status'    => 'publish',
				'posts_per_page' => $atts['number'],
				'order'          => 'DESC',
			);
			
			$posts = get_posts( $args ); unset( $args );

			ob_start();

			?>

			<div class="shapeshifter-entries-slider-wrapper shapeshifter-new-slider-wrapper slider-pro"><div class="shapeshifter-entries-slides shapeshifter-new-slides sp-slides<?php echo esc_attr( ' shapeshifter-entries-slide-type-' . $atts['slide-type'] ); ?>">

			<?php 
				if( is_array( $posts ) ) { foreach( $posts as $post ) {

					$this->shapeshifter_print_entries_slider_html( 'new-entries', $atts, $post );

				} }
			?>

			</div></div>

			<?php

			$new_entries = ob_get_clean();

			return $new_entries;

		}

		/**
		 * Search Posts
		 * @param  array  $atts
		 * @return string
		**/
		function shapeshifter_search_entries( $atts = array() ) { 

			$atts = shortcode_atts( array(
				'keywords' => '',
				'number' => 3,
				'orderby' => 'none',
				'slide-type' => 'standard',
				'is-thumbnail-on' => 'true',
				'excerpt-number' => '200',
			), $atts );

			if( 
				$atts['keywords'] == '' 
				|| $atts['number'] == '' 
			) return;

			$args = array(
				'post_type'      => 'post',
				'post_status'    => 'publish',
				'posts_per_page' => -1,
				'order'          => 'DESC',
				'orderby'        => $atts['orderby'],
			);

			$posts = get_posts( $args );

			if( count( $posts ) > 0 ) {

				$count = 0;
				$post_count = 0;

				ob_start();

				?>

				<div class="shapeshifter-entries-slider-wrapper shapeshifter-search-slider-wrapper slider-pro"><div class="shapeshifter-entries-slides shapeshifter-search-slides sp-slides<?php echo esc_attr( ' shapeshifter-entries-slide-type-' . $atts['slide-type'] ); ?>">

				<?php

				foreach( $posts as $post ) {	

					$search_title = esc_html( get_the_title( $post->ID ) );
					$search_permalink = esc_url( get_permalink( $post->ID ) );

					$content = $post->post_content;
					$content = wp_strip_all_tags( $content );

					$search1 = strpos( $content, $atts['keywords'] );
					$search2 = strpos( $search_title, $atts['keywords'] );

					if( $search1 === false && $search2 === false ) continue;

					$this->shapeshifter_print_entries_slider_html( 'search-entries', $atts, $post );

					$post_count += 1;

					if( $post_count >= $atts['number'] ) {
						//echo $postlink; // チェック用
						wp_reset_postdata();
						break;
					}

					$count += 1;

				}

				?>

				</div></div>

				<?php 

				$search_entries = ob_get_clean();

			}

			return $search_entries;

		}

		/**
		 * Posts
		 * @param  string  $type
		 * @param  array   $atts
		 * @param  WP_Post $post 
		 * @return void
		**/
		function shapeshifter_print_entries_slider_html( $type, $atts, $post ) { 
			include( 'view/template-entries.php' );
		}

	/**
	 * Excerpt
	**/
		/**
		 * Print Excerpt
		 * @param string $post_content
		 * @param int    $excerpt_length
		 * @return void
		**/
		function shapeshifter_the_excerpt( $post_content, $excerpt_length = 200 ) {
			echo wp_strip_all_tags( sse_get_the_excerpt( $post_content, $excerpt_length ) );
		}

		/**
		 * Get Excerpt
		 * @param string $post_content
		 * @param int    $excerpt_length
		 * @return string
		**/
		function shapeshifter_get_the_excerpt( $post_content, $excerpt_length = 200 ) {

			// Remove Spaces, Line-Breaks, Tag-Wrappers and Shortcodes
			$the_excerpt = preg_replace( '/\[[^\]]+]/i', '', $post_content );
			$the_excerpt = wp_strip_all_tags( $the_excerpt );
			$the_excerpt = str_replace( array( "\n", "\r", '　', '"', "'", '&nbsp;' ), '', $the_excerpt );
			$the_excerpt = mb_ereg_replace( "/[^a-zA-Z0-9]\s[^a-zA-Z0-9]/i", '', $the_excerpt );
			return mb_substr( $the_excerpt, 0, $excerpt_length );

		}


}
