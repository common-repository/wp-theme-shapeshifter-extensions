<?php
if ( ! defined( 'ABSPATH' ) ) exit;
if ( class_exists( 'SSE_Sanitize_Methods' ) ) return;
class SSE_Sanitize_Methods {

	public static function sanitize_general_settings( $general_settings ) {
		
		if( is_array( $general ) ) { foreach( $general as $index => $settings ) {
			if( $index == 'default_settings_tab' ) 
				$general[ $index ] =  sanitize_text_field( $settings );

		} }

		return $general;
	}

	public static function sanitize_auto_insert_settings( $auto_insert_settings ) {

		$for_sanitize_absint = array( 'excerpt_length' );

		$for_sanitize_text_field = array();
		
		$for_esc_textarea = array( 'content_editor', 'header_code', 'after_start_body_code', 'footer_code' );

		if( is_array( $auto_insert_settings ) ) { foreach( $auto_insert_settings as $index => $settings ) {

			if( in_array( $index, $for_sanitize_text_field ) )
				$auto_insert_settings[ $index ] = sanitize_text_field( $settings );

			if( in_array( $index, $for_esc_textarea ) )
				$auto_insert_settings[ $index ] = esc_textarea( $settings );

			if( in_array( $index, $for_sanitize_absint ) ) {

				$auto_insert_settings[ $index ] = ( 
					intval( $settings ) < 1
					? 20 
					: intval( $settings ) 
				);

			}

		} }

		return $auto_insert_settings;

	}

	public static function sanitize_widget_areas_settings( $widget_areas_settings ) {

		$for_sanitize_text_field = array( 'hook', 'width', 'is_on_mobile_menu', 'id', 'class' );

		$for_esc_textarea = array( 'description', 'before_widget', 'after_widget', 'before_title', 'after_title' );

		if( is_array( $widget_areas ) ) { foreach( $widget_areas as $number => $settings ) {

			foreach( $settings as $index => $setting ) {

				if( in_array( $index, $for_sanitize_text_field ) )
					$widget_areas[ $number ][ $index ] =  sanitize_text_field( $setting );
				
				if( in_array( $index, $for_esc_textarea ) )
					$widget_areas[ $number ][ $index ] =  esc_textarea( $setting );

			}

		} }

		return $widget_areas;

	}

	public static function sanitize_debug_mode_settings( $debug_mode_settings ) {

		$for_sanitize_text_field = array( 'auto_page_view_count_reset' );

		if( is_array( $debug_modes ) ) { foreach( $debug_modes as $index => $settings ) {
			if( in_array( $index, $for_sanitize_text_field ) )
				$debug_modes[ $index ] =  sanitize_text_field( $settings );
		} }

		return $debug_modes;

	}

	public static function sanitize_post_view_count_settings( $post_view_count_settings ) {
		
	}
	

	/**
	 * Check if is set. return value if true. Otherwise, return false.
	 *
	 * @return $return
	**/
	public static function validate_checked_value( $value ) {

		if( ! isset( $value ) ) {
			return false;
		} else {
			return $value;
		}

	}

	/**
	 * Sanitize Color Value
	 *
	 * @return $value
	**/
	public static function sanitize_color_value( $value ) {

		# Is RGB
			$is_rgb = strpos( $value, 'rgb' ) !== false;

		# Default Value
			$return = '';

		# If is RGB
			if( $is_rgb ) {

				preg_match( '/rgba?\((\s*?([0-9]){1,3}\,?){3}(0|1)\.?[0-9]*?\)/i', $value, $matched );
				if( isset( $matched[0] ) )
					$return = sanitize_text_field( $matched[0] );

			}

		# If is HEX 
			elseif( strpos( $value, '#' ) !== false ) {

				$return = sanitize_hex_color( $value );

			}

		# If is no HEX 
			else {

				$return = sanitize_hex_color_no_hash( $value );

			}

		# End
			return $return;

	}

	/**
	 * Sanitize Checkbox Value
	 *
	 * @return $value
	**/
	public static function sanitize_checkbox( $input ) {

		if ( $input == true ) {
			return true;
		} else {
			return false;
		}

	}

	/**
	 * Sanitize Int Value
	 *
	 * @return $value
	**/
	public static function sanitize_int( $input ) {

		return intval( $input );

	}

	/**
	 * Sanitize Font Family
	 *
	 * @return $return
	**/
	public static function sanitize_font_families( $input ) {

		$return = '';

		$font_families = array_flip( sse()->get_theme_mod_manager()->get_shapeshifter_font_families() );

		if( in_array( $input, $font_families ) ) {

			$return = $input;

		}

		return $return;

	}

	/**
	 * Sanitize Background Image Size
	 *
	 * @return $return
	**/
	public static function sanitize_background_image_size( $input ) {

		$return = '';

		$background_image_sizes = array_flip( sse()->get_theme_mod_manager()->get_shapeshifter_theme_mods_choices_background_size() );

		if( in_array( $input, $background_image_sizes ) ) {

			$return = sanitize_text_field( $input );

		}

		return $return;

	}

	/**
	 * Sanitize Background Position Row
	 *
	 * @return $return
	**/
	public static function sanitize_background_position_row( $input ) {

		$return = '';

		$background_position_row = array_flip( sse()->get_theme_mod_manager()->get_shapeshifter_theme_mods_choices_background_position_row() );

		if( in_array( $input, $background_position_row ) ) {

			$return = sanitize_text_field( $input );

		}

		return $return;

	}

	/**
	 * Sanitize Background Position Column
	 *
	 * @return $return
	**/
	public static function sanitize_background_position_column( $input ) {

		$return = '';

		$background_position_column = array_flip( sse()->get_theme_mod_manager()->get_shapeshifter_theme_mods_choices_background_position_column() );

		if( in_array( $input, $background_position_column ) ) {

			$return = sanitize_text_field( $input );

		}

		return $return;

	}

	/**
	 * Sanitize Background Repeat
	 *
	 * @return $return
	**/
	public static function sanitize_background_repeat( $input ) {

		$return = '';

		$background_repeats = array_flip( sse()->get_theme_mod_manager()->get_shapeshifter_theme_mods_choices_background_repeats() );

		if( in_array( $input, $background_repeats ) ) {

			$return = sanitize_text_field( $input );

		}

		return $return;

	}

	/**
	 * Sanitize Background Attachment
	 *
	 * @return $return
	**/
	public static function sanitize_background_attachment( $input ) {

		$return = '';

		$background_attachment = array_flip( sse()->get_theme_mod_manager()->get_shapeshifter_theme_mods_choices_background_attachments() );

		if( in_array( $input, $background_attachment ) ) {

			$return = sanitize_text_field( $input );

		}

		return $return;

	}

	/**
	 * Sanitize CSS Animations Hover
	 *
	 * @return $return
	**/
	public static function sanitize_css_animation_hover( $input ) {

		$return = '';

		$css_animations = array_flip( sse()->get_theme_mod_manager()->get_animate_css_class_array()['hover'] );

		if( in_array( $input, $css_animations ) ) {

			$return = sanitize_text_field( $input );

		}

		return $return;

	}

	/**
	 * Sanitize CSS Animations Enter
	 *
	 * @return $return
	**/
	public static function sanitize_css_animation_enter( $input ) {

		$return = '';

		$css_animations = array_flip( sse()->get_theme_mod_manager()->get_animate_css_class_array()['enter'] );

		if( in_array( $input, $css_animations ) ) {

			$return = sanitize_text_field( $input );

		}

		return $return;

	}

	/**
	 * Sanitize Credit Type
	 *
	 * @return $return
	**/
	public static function sanitize_credit_type( $input ) {

		$return = '';

		$credit_types = array_flip( sse()->get_theme_mod_manager()->get_shapeshifter_theme_mods_choices_credit_types() );

		if( in_array( $input, $credit_types ) ) {

			$return = sanitize_text_field( $input );

		}

		return $return;

	}

	/**
	 * Sanitize Credit Type
	 *
	 * @return $return
	**/
	public static function sanitize_footer_align( $input ) {

		$return = '';

		$footer_align = array_flip( sse()->get_theme_mod_manager()->get_shapeshifter_theme_mods_choices_footer_aligns() );

		if( in_array( $input, $footer_align ) ) {

			$return = sanitize_text_field( $input );

		}

		return $return;

	}

}
