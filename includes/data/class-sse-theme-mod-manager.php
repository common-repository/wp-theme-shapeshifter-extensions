<?php

if ( ! defined( 'ABSPATH' ) ) exit;

class SSE_Theme_Mod_Manager extends ShapeShifter_Theme_Mod_Manager {

	/**
	 * Static
	**/
		/**
		 * Instace
		 * @var SSE_Theme_Mod_Manager
		**/
		protected static $instance = null;

	/**
	 * Properties
	**/
		/**
		 * $data
		**/

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
		protected function __construct()
		{
			parent::__construct();
			$this->init_hooks();
		}

		protected function init_hooks()
		{
			add_filter( 'shapeshifter_filters_default_theme_mods', array( $this, 'default_theme_mods' ), 10, 4 );
		}

	/**
	 * Filters
	**/
		/**
		 * Theme Mods
		 * 
		 * @param array  $theme_mods
		 * @param string $theme_options_prefix
		 * @parma array  $widget_areas
		 * @param array $animated_elements_in_content
		 * 
		 * @return array
		**/
		function default_theme_mods( $theme_mods, $theme_options_prefix, $widget_areas, $animated_elements_in_content ) {

			// Default
				$default_theme_mods = array(
					// Logo
						// Logo Background
							'header_image_url' => '',
							'header_image_size_width' => 1270,
							'header_image_size_height' => 0,
							'header_image_position' => 'left',
							'header_image_margin_top' => 0,
							'header_image_margin_side' => 0,
							'header_image_margin_bottom' => 0,
						// Logo Background Image
							'header_image_background_image' => '',
							'header_image_background_image_size' => 'contain',
							'header_image_background_image_position_row' => 'center',
							'header_image_background_image_position_column' => 'center',
							'header_image_background_image_repeat' => 'no-repeat',
							'header_image_background_image_attachment' => 'scroll',
						// Logo Title Description
							'header_image_title_display_toggle' => false,
							'header_image_description_display_toggle' => false,
							'header_image_title_description_position' => 'left-top',
							'header_image_title_font_size' => 30,
							'header_image_title_font_family' => 'HG正楷書体-PRO',
							'header_image_description_font_family' => '"Yu Gothic", "游ゴシック"',
							'header_image_description_font_size' => 14,
							'header_image_title_description_padding' => 10,
						// Logo Colors
							'header_image_background_color' => false,
							'header_image_title_color' => '#666666',
							'header_image_description_color' => '#666666',
				);

			// Body
				$page_types = array( 'home', 'blog', 'front_page', 'archive', 'post', 'page' );
				foreach( $page_types as $page_type ) {
					$default_theme_mods['body_' . $page_type . '_background_color'] = '#FFFFFF';
					$default_theme_mods['body_' . $page_type . '_background_image'] = '';
					$default_theme_mods['body_' . $page_type . '_background_image_size'] = 'auto';
					$default_theme_mods['body_' . $page_type . '_background_image_position_row'] = 'center';
					$default_theme_mods['body_' . $page_type . '_background_image_position_column'] = 'center';
					$default_theme_mods['body_' . $page_type . '_background_image_repeat'] = 'repeat';
					$default_theme_mods['body_' . $page_type . '_background_image_attachment'] = 'scroll';
				}

			// Optional Widget Areas Wrapper
				foreach( array( 'mobile_sidebar', 'after_header', 'before_content_area', 'before_content', 'beginning_of_content', 'before_1st_h2_of_content', 'end_of_content', 'after_content', 'before_footer', 'in_footer' ) as $index => $widget_area ) {

					// Wrapper
						$default_theme_mods[ $widget_area . '_wrapper_background_color'] = 'rgba(255,255,255,0)';
						$default_theme_mods[ $widget_area . '_wrapper_background_image'] = '';
						$default_theme_mods[ $widget_area . '_wrapper_background_image_size'] = 'cover';
						$default_theme_mods[ $widget_area . '_wrapper_background_image_position_row'] = 'center';
						$default_theme_mods[ $widget_area . '_wrapper_background_image_position_column'] = 'center';
						$default_theme_mods[ $widget_area . '_wrapper_background_image_repeat'] = 'no-repeat';
						$default_theme_mods[ $widget_area . '_wrapper_background_image_attachment'] = 'scroll';

				}

			// Optional Widget Areas
				$optional_widget_areas_args = sse()->get_option( 'widget_areas' )->get_data();
				if ( is_array( $optional_widget_areas_args ) && 0 < count( $optional_widget_areas_args ) ) {
				foreach( $optional_widget_areas_args as $index => $widget_areas_data ) {

					if ( ! isset( $widget_areas_data['hook'] ) ) continue;

					$widget_area = $widget_areas_data['hook'] . '_' . $index;

					// Font Family
						$default_theme_mods[ $widget_area . '_font_family'] = '"Yu Gothic", "游ゴシック"';
					// CSS Animation
						$default_theme_mods[ $widget_area . '_area_animation_enter'] = 'none';
					// Widget Area Background
						$default_theme_mods[ $widget_area . '_area_background_color'] = 'rgba(255,255,255,0.9)';
						$default_theme_mods[ $widget_area . '_area_background_image'] = '';
						$default_theme_mods[ $widget_area . '_area_background_image_size'] = 'cover';
						$default_theme_mods[ $widget_area . '_area_background_image_position_row'] = 'center';
						$default_theme_mods[ $widget_area . '_area_background_image_position_column'] = 'center';
						$default_theme_mods[ $widget_area . '_area_background_image_repeat'] = 'no-repeat';
						$default_theme_mods[ $widget_area . '_area_background_image_attachment'] = 'scroll';
						$default_theme_mods[ $widget_area . '_area_padding'] = 0;
					// Widget Outer Background
						$default_theme_mods[ $widget_area . '_outer_background_color'] = 'rgba(255,255,255,0)';
						$default_theme_mods[ $widget_area . '_outer_background_image'] = '';
						$default_theme_mods[ $widget_area . '_outer_background_image_size'] = 'cover';
						$default_theme_mods[ $widget_area . '_outer_background_image_position_row'] = 'center';
						$default_theme_mods[ $widget_area . '_outer_background_image_position_column'] = 'center';
						$default_theme_mods[ $widget_area . '_outer_background_image_repeat'] = 'no-repeat';
						$default_theme_mods[ $widget_area . '_outer_background_image_attachment'] = 'scroll';
					// Widget Inner Background
						$default_theme_mods[ $widget_area . '_inner_background_color'] = 'rgba(255,255,255,0)';
						$default_theme_mods[ $widget_area . '_inner_background_image'] = '';
						$default_theme_mods[ $widget_area . '_inner_background_image_size'] = 'cover';
						$default_theme_mods[ $widget_area . '_inner_background_image_position_row'] = 'center';
						$default_theme_mods[ $widget_area . '_inner_background_image_position_column'] = 'center';
						$default_theme_mods[ $widget_area . '_inner_background_image_repeat'] = 'no-repeat';
						$default_theme_mods[ $widget_area . '_inner_background_image_attachment'] = 'scroll';
					// Designs
						$default_theme_mods[ $widget_area . '_widget_border'] = false;
						$default_theme_mods[ $widget_area . '_widget_border_radius'] = 0;
						$default_theme_mods[ $widget_area . '_widget_inner_padding'] = 0;					
					// Icons
						$default_theme_mods[ $widget_area . '_widget_title_fontawesome_icon_select'] = 'f150';
						$default_theme_mods[ $widget_area . '_widget_title_fontawesome_icon_color'] = '#000000';
						$default_theme_mods[ $widget_area . '_widget_list_fontawesome_icon_select'] = 'f101';
						$default_theme_mods[ $widget_area . '_widget_list_fontawesome_icon_color'] = '#000000';
					// Text Colors
						$default_theme_mods[ $widget_area . '_title_color'] = '#000000';
						$default_theme_mods[ $widget_area . '_text_color'] = '#666666';
						$default_theme_mods[ $widget_area . '_link_text_color'] = '#337ab7';

				}
				}


			# End
				return apply_filters(
					'shapeshifter_extensions_filters_default_theme_mods',
					wp_parse_args( $theme_mods, $default_theme_mods ),
					$widget_areas,
					$optional_widget_areas_args,
					$animated_elements_in_content
				);

		}


}
