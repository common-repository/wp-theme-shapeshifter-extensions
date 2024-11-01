<?php
class SSE_Widget_Area_Manager {

	/**
	 * Static
	**/
		/**
		 * Instance of this Class
		**/
		protected static $instance = null;

	/**
	 * Properties
	**/
		/**
		 * Options
		 * @var array
		**/
		public $options = array();

		protected $deactivate_widget_areas = array();

		/**
		 * Optional Widget Area Data
		 * @var array
		**/
		public $optional_widget_areas = array();

		public function get_optional_widget_areas()
		{
			return $this->optional_widget_areas;
		}

		/**
		 * Optional Widget Area Data
		 * @var array
		**/
		public $owa_hsk = array(
			'customize'                => 'customize',
			'after_header'             => 'after-header',
			'before_content_area'      => 'before-content-area',
			'before_content'           => 'before-content',
			'beginning_of_content'     => 'beginning-of-content',
			'before_1st_h2_of_content' => 'before-1st-h2-of-content',
			'end_of_content'           => 'end-of-content',
			'after_content'            => 'after-content',
			'before_footer'            => 'before-footer',
			'in_footer'                => 'in-footer',
			'is_on_mobile_menu'        => 'mobile-side-menu',
		);

		/**
		 * Get Selector Key
		 * @param string $hook
		 * @return string
		**/
		public function get_wa_key( $hook )
		{
			if ( isset( $hook ) ) {
				return $this->owa_hsk[ $hook ];
			}
			return false;
		}

	/**
	 * Init
	**/
		/**
		 * Public Initializer
		**/
		public static function get_instance() {

			// Init if is not initialied yet
			if ( null === self::$instance ) {
				self::$instance = new Self();
			}

			// return instance
			return self::$instance;

		}

		/**
		 * Constructor
		**/
		protected function __construct() {

			$options = sse()->get_options();
			// Define
				// Optional Widget Areas
				$this->widget_areas = $options['widget_areas']->get_data();

				// Each Widget Area
				foreach ( $this->widget_areas as $index => $widget_area ) {
					$widget_num = $index + 1;
					$hook = $widget_area['hook'];
					$key = $this->owa_hsk[ $hook ];
					$widget_area_default = array(
						'id'            => sprintf( 'widget-area-%1$d', $widget_num ),
						'class'		    => sprintf( 'widget-area-%1$d', $widget_num ),
						'name'          => sprintf( esc_html__( 'Widget Area %1$d', ShapeShifter_Extensions::TEXTDOMAIN ), $widget_num ),
						'description'   => sprintf( esc_html__( 'This is "Widget Area %1$d".', ShapeShifter_Extensions::TEXTDOMAIN ), $widget_num ),
						'before_widget' => sprintf( '<li class="widget-li %1$s"><div class="widget %s">', $key, '%s' ),
						'after_widget'  => '</div></li>',
						'before_title'  => sprintf( '<p class="widget-title %1$s">', $key ),
						'after_title'   => '</p>'
					);

					$this->widget_areas[ $index ] = $widget_args = wp_parse_args( $widget_area, $widget_area_default );

					$id = $widget_args['id'];
					$args = array(
						'id'            => $id,
						'class'		    => $widget_args['class'],
						'name'          => $widget_args['name'],
						'description'   => $widget_args['description'],
						'before_widget' => html_entity_decode( $widget_args['before_widget'] ),
						'after_widget'  => html_entity_decode( $widget_args['after_widget'] ),
						'before_title'  => html_entity_decode( $widget_args['before_title'] ),
						'after_title'   => html_entity_decode( $widget_args['after_title'] ),
					);
					$args = $widget_args;
					$this->optional_widget_areas[ $id ] = $args;
				}

			$this->init_hooks();

		}

		/**
		 * Init WP Hooks
		**/
		protected function init_hooks() {

			// Register Widget Areas
			add_action( 'widgets_init', array( $this, 'register_widget_areas' ), 11 );

			add_action( shapeshifter()->get_prefixed_action_hook( 'init_frontend_post_meta' ), array( $this, 'setup_post_meta_deactivate_widget_area' ), 100 );

			// Enqueue Scripts
			add_action( 'admin_enqueue_scripts', array( $this, 'admin_enqueue_scripts' ) );

			// Widget Area Top Right
			if ( ! SSE_IS_MOBILE ) {
				add_action( 'wp_footer', array( $this, 'shapeshifter_top_right_fixed' ) );
			}

			// Render
			foreach ( $this->owa_hsk as $hook => $selector_key ) {
				add_action( 'shapeshifter_widget_areas', array( $this, 'print_widget_areas' ), 10, 1 );
			}
		}

	/**
	 * Actions
	**/
		/**
		 * Register Widget Areas
		**/
		function register_widget_areas() {

			$this->theme_mods = sse()->get_theme_mods();

			// Widget Area Top Right
			register_sidebar( array(
				'id' => 'top_right',
				'class' => 'top_right',
				'name' => esc_html__( 'Top Right Fixed', ShapeShifter_Extensions::TEXTDOMAIN ),
				'description' => esc_html__( 'You can fix this by settings on theme customizer', ShapeShifter_Extensions::TEXTDOMAIN ),
				'before_widget' => '<li class="widget-li top-right"><div class="widget top-right">',
				'after_widget' => '</div></li>',
				'before_title' => '<p class="widget-title top-right"><span>',
				'after_title' => '</span></p>',
			) );

			// Optional Widget Areas
			foreach( $this->optional_widget_areas as $id => $widget_area ) {
				register_sidebar( $widget_area );
			}

		}

		public function setup_post_meta_deactivate_widget_area()
		{
			$this->deactivate_widget_areas = sse()->get_frontend_manager()->get_deactivate_widget_areas();
		}

		/**
		 * Enqueue Scripts
		 * 
		 * @param string $hook
		 * 
		 * @return void
		**/
		function admin_enqueue_scripts( $hook ) {

			// ウィジェットの編集画面用
			//if( 'widgets.php' == $hook ){

				wp_enqueue_style( 'wp-color-picker' );		
				wp_enqueue_script( 'wp-color-picker' );
				wp_enqueue_media();
				
				wp_enqueue_style( 'sse-widget-settings-form' );
				
				wp_enqueue_script( 'sse-widget-settings' );


			//}

		}

	/**
	 * Render
	**/
		// Top Right
			/**
			 * Print Top Right
			 * 
			 * @uses $this->shapeshifter_get_top_right_fixed()
			 * 
			 * @return void
			**/
			function shapeshifter_top_right_fixed() {

				echo $this->shapeshifter_get_top_right_fixed();

			}

			/**
			 * Get Top Right HTML
			 * 
			 * @uses $this->shapeshifter_get_standard_widget_area_by_hook()
			 * @uses filter "shapeshifter_filters_widget_area_top_right_fixed"
			 * 
			 * @return string
			**/
			function shapeshifter_get_top_right_fixed() {

				return apply_filters( 'shapeshifter_filters_widget_area_top_right_fixed', shapeshifter()->get_frontend_manager()->get_rendering_methods()->shapeshifter_get_standard_widget_area_by_hook( 
					'top_right',
					'top-right',
					'<aside class="widget-area top-right"><ul class="widget-list top-right">',
					'</ul></aside>'
				) );

			}

		// Optional Widget Areas
			/**
			 * Print Optional Widget Area
			 * 
			 * @param int    $number
			 * @param string $hook
			 * @param string $selectors_key
			 * @param array  $widget_areas_data
			 * @param int    $shapeshifter_content_width
			 * 
			 * @uses $this->shapeshifter_get_optional_widget_area_by_hook( $number, $hook, $selectors_key, $widget_areas_data, $shapeshifter_content_width )
			 * 
			 * @return void
			**/
			function shapeshifter_optional_widget_area_by_hook( $number, $hook, $selectors_key, $widget_areas_data, $shapeshifter_content_width ) {
				
				// Hook
				$hook = esc_attr( $hook );
				$selectors_key = esc_attr( $selectors_key );
				$shapeshifter_content_width = absint( $shapeshifter_content_width );

				// Print 
				echo $this->shapeshifter_get_optional_widget_area_by_hook( $number, $hook, $selectors_key, $widget_areas_data, $shapeshifter_content_width );

			}

			/**
			 * Get Optional Widget Area HTML
			 * 
			 * @param int    $number
			 * @param string $hook
			 * @param string $selectors_key
			 * @param array  $widget_areas_data
			 * @param int    $shapeshifter_content_width
			 * 
			 * @uses $this->shapeshifter_get_optional_widget_area_by_hook( $number, $hook, $selectors_key, $widget_areas_data, $shapeshifter_content_width )
			 * 
			 * @return string
			**/
			function shapeshifter_get_optional_widget_area_by_hook( $number, $hook, $selectors_key, $widget_areas_data, $shapeshifter_content_width ) {

				// Check Hook
					if ( $hook === 'is_on_mobile_menu' ) {

						if ( ! isset( $widget_areas_data['is_on_mobile_menu'] ) || $widget_areas_data['is_on_mobile_menu'] !== 'is_on_mobile_menu' ) return '';

					} else {

						if ( ! isset( $widget_areas_data['hook'] ) || $widget_areas_data['hook'] !== $hook ) return '';

					}

					ob_start();

						// Print by Post Meta
							do_action( 'shapeshifter_post_meta_outputs_in_widget_area_hook', $widget_areas_data );

						// Active Check
							if ( 
								! is_active_sidebar( $widget_areas_data['id'] ) 
								|| in_array( $widget_areas_data['id'], $this->deactivate_widget_areas )
							) {   
							} else {
								if ( function_exists( 'dynamic_sidebar' ) && dynamic_sidebar( $widget_areas_data['id'] ) ) {}
							}
					
					$widgets = ob_get_clean();
					if( empty( $widgets ) ) {
						return '';
					}

				// Sanitizations
					$hook = esc_attr( $hook );
					$selectors_key = esc_attr( $selectors_key );
					$shapeshifter_content_width = absint( $shapeshifter_content_width );
					$widget_areas_data['id'] = esc_attr( $widget_areas_data['id'] );
					$widget_areas_data['width'] = esc_attr( $widget_areas_data['width'] );

				// For CSS Animation
					$id_fix = esc_attr( $widget_areas_data['hook'] . '_' . $number );

				// Var to return
					$return = '';

				// Prints
					ob_start();
					
						preg_match( '/[0-9]+/i', $widget_areas_data['width'], $matched_number );
						$wrapper_classes = array( 'widget-area', 'optional', $selectors_key );
						echo '<div id ="' . $widget_areas_data['id'] . '"
							class="' . implode( ' ', $wrapper_classes );
								if(
									isset( $this->theme_mods[ $id_fix . '_area_animation_enter'] ) 
									&& $this->theme_mods[ $id_fix . '_area_animation_enter'] !== 'none'
								) {
									echo ' shapeshifter-hidden enter-animated';
								}
							echo '"';

							if( isset( $this->theme_mods[ $id_fix . '_area_animation_enter'] ) 
								&& $this->theme_mods[ $id_fix . '_area_animation_enter'] !== 'none' 
							) {
								echo ' data-animation-enter="' . esc_attr( $this->theme_mods[ $id_fix . '_area_animation_enter'] ) . '" ';
							}
							echo ' style="width:100%; ';
								echo 'max-width:' . esc_attr( 
									$widget_areas_data['width'] == 'auto'
									? absint( $shapeshifter_content_width ) . 'px' 
									: ( 
										$matched_number[ 0 ] <= $shapeshifter_content_width 
										? $widget_areas_data['width'] 
										: absint( $shapeshifter_content_width ) . 'px' 
									)
								) . ';';
							echo '"';
						echo '>';

						echo '<ul id="widget-list-' . $widget_areas_data['id'] . '" class="widget-list ' . esc_attr( $selectors_key ) . '">';
							echo $widgets;
						echo '</ul></div>';
					
					$widget_area = html_entity_decode( ob_get_clean() );

					return apply_filters( 'shapeshifter_filters_each_optional_widget_area', $widget_area );

			}

			/**
			 * Widget Areas
			 * @param string $hook
			**/
			public function print_widget_areas( $hook )
			{
				echo $this->get_widget_areas( $hook );
			}

			/**
			 * Widget Areas
			 * @param string $hook
			 * @return string
			**/
			public function get_widget_areas( $hook )
			{

				$selector_key = sse()->sanitize_unique_prefix( $hook, '_' );
				if ( isset( $this->owa_hsk[ $hook ] ) ) {
					$selector_key = $this->owa_hsk[ $hook ];
				}

				global $shapeshifter_content_width;

				$content_width = intval( shapeshifter()->get_frontend_manager()->content_width );

				$after_header_total = '';
				if ( $this->widget_areas ) { foreach( $this->widget_areas as $number => $widget_areas_data ) { 

					$after_header_total .= $this->shapeshifter_get_optional_widget_area_by_hook( $number, $hook, $selector_key, $widget_areas_data, $content_width );

				} }

				if ( empty( $after_header_total ) ) {
					return '';
				}


				ob_start();

					echo '<div id="optional-widget-area-wrapper-' . $selector_key . '" class="optional-widget-area-wrapper ' . $selector_key . '">';
						echo $after_header_total . '<div class="clearfix"></div>';
					echo '</div><div class="clearfix"></div>';

				$after_header = ob_get_clean();

				return apply_filters( sse()->get_prefixed_filter_hook( 'widget_areas' ), $after_header, $hook, $selector_key );

			}

}


?>