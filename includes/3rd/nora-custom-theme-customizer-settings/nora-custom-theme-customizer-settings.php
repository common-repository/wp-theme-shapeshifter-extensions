<?php
if ( ! class_exists( 'ShapeShifter_Theme_Customizer_Settings' ) ) {
	class ShapeShifter_Theme_Customizer_Settings {
		
		private $wp_customize;
		private $content_width;
		private $theme_mods = array();
		private $section;
		private $selectors;
		private $settings = array();

		private $setting_data_json;

		private $inputs_hidden;

		private static $this_dir;

		function __construct( $section, $selectors, $settings, $this_dir, $textdomain = '' ) {

			$this->textdomain = $textdomain;

			# Define Constants
				$nora_custom_theme = wp_get_theme();
				if ( ! defined( 'NORA_CUSTOM_THEME_NAME' ) ) define( 'NORA_CUSTOM_THEME_NAME', $nora_custom_theme['Name'] );
				unset( $nora_custom_theme );
				if ( ! defined( 'NORA_CUSTOM_THEME_PREFIX' ) ) define( 'NORA_CUSTOM_THEME_PREFIX', str_replace( array( ' ', '-' ), '_', strtolower( NORA_CUSTOM_THEME_NAME ) ) . '-' );
				if ( ! defined( 'NORA_CUSTOM_THEME_OPTIONS' ) ) define( 'NORA_CUSTOM_THEME_OPTIONS', str_replace( array( ' ', '-' ), '_', NORA_CUSTOM_THEME_PREFIX ) . 'options_' );

				if ( ! defined( 'NORA_CUSTOM_IS_ADMIN' ) ) define( 'NORA_CUSTOM_IS_ADMIN', ( is_admin() ? 1 : 0 ) );
				if ( ! defined( 'NORA_CUSTOM_IS_ADMIN_BAR_SHOWING' ) ) define( 'NORA_CUSTOM_IS_ADMIN_BAR_SHOWING', is_admin_bar_showing() );
				if ( ! defined( 'NORA_CUSTOM_IS_CUSTOMIZE_PREVIEW' ) ) define( 'NORA_CUSTOM_IS_CUSTOMIZE_PREVIEW', is_customize_preview() );

				if ( ! defined( 'NORA_CUSTOM_DIR_URL' ) ) define( 'NORA_CUSTOM_DIR_URL', $this_dir );

			# Setting ID
				$this->setting_id = $settings['setting_id'];

			if ( NORA_CUSTOM_IS_CUSTOMIZE_PREVIEW ) {

				global $content_width;
				$this->content_width = $content_width;
				$this->theme_mods = get_theme_mods();

				$this->section = $section;
				$this->selectors = $selectors;
				//$this->setting_id = $settings['setting_id'];
				$this->settings = $settings['properties'];

				$setting_data = array( 
					'selector' => $this->selectors,
					'data' => array()
				);

				foreach( $this->settings as $property => $property_data ) {
					
					if ( gettype( $property ) == 'integer' ) {

						$func_name = str_replace( '-', '_', 'setting_' . $property_data );
						$setting_id = str_replace( '-', '_', 'nora_custom_' . $this->setting_id . '_' . $property_data );
						
						array_push( $setting_data['data'], array(
							'id' => $setting_id,
							'property' => $property_data
						));

					} else {

						$func_name = str_replace( '-', '_', 'setting_' . $property );
						$setting_id = str_replace( '-', '_', 'nora_custom_' . $this->setting_id . '_' . $property );
						
						array_push( $setting_data['data'], array(
							'id' => $setting_id,
							'property' => $property,
							'propertyData' => $property_data
						));

					}

				}

				$this->setting_data_json = wp_json_encode( $setting_data );
				$setting_data = null;
			}

			$this->add_actions();

		}

		function add_actions() {

			if ( NORA_CUSTOM_IS_CUSTOMIZE_PREVIEW ) {

				# Theme Customizer
					add_action( 'customize_register', array( $this, 'nora_custom_theme_customize_register' ), 100 ); 

				# Control Scripts
					add_action( 'customize_controls_print_footer_scripts', array( $this, 'nora_custom_theme_customizer_control_scripts' ) );

			}

			if ( $this->selectors != '' ) {

				# Preview Scripts
					if ( NORA_CUSTOM_IS_CUSTOMIZE_PREVIEW )
						add_action( 'customize_preview_init', array( $this, 'nora_custom_theme_customizer_live_preview' ) );

				# Style Scripts
					add_action( 'wp_head', array( $this, 'nora_custom_print_theme_customizer_styles' ) );

			}

			if ( NORA_CUSTOM_IS_CUSTOMIZE_PREVIEW || NORA_CUSTOM_IS_ADMIN ) {

				add_action( 'customize_save_after', array( $this, 'nora_custom_save_theme_customizer_styles' ) );

			}

		}

		# Print Style
			function nora_custom_print_theme_customizer_styles() {

				//$style = get_option( NORA_CUSTOM_THEME_OPTIONS . 'SHAPESHIFTERized_styles_' . $this->setting_id );
				$style = wp_strip_all_tags( $this->nora_custom_get_theme_customizer_styles(), true );
				
				echo '<style id="shapeshifter-styles-' . $this->setting_id . '">' . $style . '</style>';

			}

		# Save Styles
			function nora_custom_save_theme_customizer_styles() {

				$style = preg_replace( '/(\n|\r|\t)/', '', $this->nora_custom_get_theme_customizer_styles() );
				update_option( NORA_CUSTOM_THEME_OPTIONS . 'SHAPESHIFTERized_styles_' . $this->setting_id, $style );

			}

		# Get Styles
			function nora_custom_get_theme_customizer_styles() {

				$style = $this->selectors . '{
					';
				foreach( $this->settings as $property => $property_data ) {

					if ( gettype( $property ) == 'integer' ) {
						$setting_id = 'nora_custom_' . $this->setting_id . '_' . str_replace( '-', '_', $property_data );
						$style .= $this->nora_custom_get_theme_customizer_style_template( $setting_id, $property_data );			
					} else {
						$setting_id = 'nora_custom_' . $this->setting_id . '_' . str_replace( '-', '_', $property );
						$default = ( isset( $property_data['default'] ) ? $property_data['default'] : '' );
						$style .= $this->nora_custom_get_theme_customizer_style_template( $setting_id, $property, $default );
					}
				}
				$style .= '
					}';
				return $style;

			}

				function nora_custom_get_theme_customizer_style_template( $setting_id, $property, $default = '' ) {

					return ( preg_match( '/^https?\:\/\//', $this->theme_mods[ $setting_id ] ) 
						? $property . ':url(' . $this->theme_mods[ $setting_id ] . ')'
						: ( $this->theme_mods[ $setting_id ] == ''
							? ( isset( $this->theme_mods[ $setting_id ] )
								? ''
								: ( $default == ''
									? ''
									: $property . ':' . $default . ';'
								)
							)
							: $property . ':' . $this->theme_mods[ $setting_id ] . ';'
						)
					);

				}

		# Control Scripts
			function nora_custom_theme_customizer_control_scripts() { 
				echo $this->inputs_hidden;
			}

		# Preview Scripts
			function nora_custom_theme_customizer_live_preview() {

				echo PHP_EOL . '<script id="shapeshifter-theme-customizer-settings-' . $this->setting_id . '-js">' . PHP_EOL . '
				if ( typeof noraSettingsDataJSON == \'undefined\' )
					var noraSettingsDataJSON = [];
				noraSettingsDataJSON.push( ' . $this->setting_data_json . ' );' . PHP_EOL .
				'</script>' . PHP_EOL;

				wp_enqueue_script(
					'shapeshifter-theme-customizer-controls-js',
					NORA_CUSTOM_DIR_URL . 'js/shapeshifter-theme-customizer-controls.js',
					array( 'jquery', 'customize-preview' ),
					'',
					true
				);

			}

		#
		# Settings
		#
			function nora_custom_theme_customize_register( $wp_customize ) { 

				$this->wp_customize =& $wp_customize;

				$is_section_exists = $this->wp_customize->get_section( $this->section['id'] );

				if ( $is_section_exists == null ) {
					
					if ( isset( $this->section['panel']['id'] ) ) {

						$is_panel_exists = $this->wp_customize->get_panel( $this->section['panel']['id'] );

						if ( $is_panel_exists == null ) {
							
							$args = array(
								'title'		  => ( isset( $this->section['panel']['title'] ) ? $this->section['panel']['title'] : esc_html__( 'Panel Title', $this->textdomain ) ),
								'description'	=> ( isset( $this->section['panel']['description'] ) ? $this->section['panel']['description'] : esc_html__( 'Panel Description', $this->textdomain ) ),
							);
							
							$wp_customize->add_panel( $this->section['panel']['id'], $args );

						} $is_panel_exists = null;

						$args = array(
							'title'	   => ( isset( $this->section['title'] ) ? $this->section['title'] : esc_html__( 'Section Title', $this->textdomain ) ),
							'description' => ( isset( $this->section['description'] ) ? $this->section['description'] : esc_html__( 'Section Description', $this->textdomain ) ) . __( '<br>If you have any questions, to "<a target="_blank" href="http://wp-works.net">http://wp-works.net</a>", please.', $this->textdomain ),
						);

						$args['panel'] = $this->section['panel']['id'];

					}
					
					$this->wp_customize->add_section( $this->section['id'], $args );
					$args = null;

				} $is_section_exists = null;

				foreach( $this->settings as $property => $property_data ) {

					if ( gettype( $property ) == 'integer' ) {

						$func_name = str_replace( '-', '_', 'setting_' . $property_data );
						$setting_id = str_replace( '-', '_', 'nora_custom_' . $this->setting_id . '_' . $property_data );

						if ( method_exists( $this, $func_name ) ) {

							call_user_func_array( 
								array( $this, $func_name ), 
								array( $setting_id, $property_data ) 
							);

						} else {

							$label = sprintf( esc_html__( 'Property "%s" is Not Supported', $this->textdomain ), $property_data );
							$description = sprintf( esc_html__( 'Please edit by yourself with Setting ID "%s"', $this->textdomain ), $setting_id );
							$propertydata = array( 
								'default' => '' 
							);

							$this->setting_input_text( $setting_id, $propertydata, $label, $description );

						}

					} else {

						$func_name = str_replace( '-', '_', 'setting_' . $property );
						$setting_id = str_replace( '-', '_', 'nora_custom_' . $this->setting_id . '_' . $property );

						if ( method_exists( $this, $func_name ) ) {

							call_user_func_array( 
								array( $this, $func_name ), 
								array( $setting_id, $property_data ) 
							);

						} else {

							$label = esc_html( isset( $property_data['label'] ) ? $property_data['label'] : sprintf( esc_html__( 'Property "%s" is Not Supported', $this->textdomain ), $property ) );
							$description = ( isset( $property_data['description'] ) ? $property_data['description'] : '' );

							if ( isset( $property_data['choices'] ) ) {
								$this->setting_input_with_choices( $setting_id, $property_data, $label, $description, $choices );
							} elseif ( isset( $property_data['type'] ) ) { 

								$func_suffix = $property_data['type'];

								# Text
									if ( in_array( $func_suffix, array( 'text', 'textarea' ) ) ) {

										call_user_func_array( 
											array( $this, 'setting_input_' . $func_suffix ), 
											array( $setting_id, $property_data, $property_data['label'], $property_data['description'] ) 
										);

									}

								# Range
									elseif ( $func_suffix == 'range' ) {

										$min = ( isset( $property_data['min'] ) ? $property_data['min'] : '-1000' );
										$max = ( isset( $property_data['max'] ) ? $property_data['max'] : '1000' );
										$step = ( isset( $property_data['step'] ) ? $property_data['step'] : '10' );
										$class = $property;
										call_user_func_array( 
											array( $this, 'setting_input_range' ), 
											array( $setting_id, $property_data, $property_data['label'], $property_data['description'], $min, $max, $step, $class )
										);

									}

								# Image
									elseif ( $func_suffix == 'image' ) {

										call_user_func_array( 
											array( $this, 'setting_image_upload' ), 
											array( $setting_id, $property_data, $property_data['label'], $property_data['description'] ) 
										);

									}

								# Color
									elseif ( $func_suffix == 'color' ) {

										call_user_func_array( 
											array( $this, 'setting_color_picker' ), 
											array( $setting_id, $property_data, $property_data['label'], $property_data['description'] ) 
										);

								# Other
									} else {

										return;

									}

							} else {
								$this->setting_input_text( $setting_id, $property_data, $label, $description );
							}
						}


					}

				} 
			}

			# Registering Method
				# Text
					function setting_input_text( $setting_id, $property_data, $label, $description ) {

						$args = array(
							'default' => ( isset( $property_data['default'] ) ? $property_data['default'] : '' ),
							'transport' => 'postMessage',
							'sanitize_callback' => 'sanitize_text_field',
						);
						$this->wp_customize->add_setting( $setting_id, $args );
						$args = array(
							'label' => esc_html( $label ),
							'description' => ( $description != '' ? $description : '' ),
							'section' => $this->section['id'],
							'settings' => $setting_id,
							'type' => 'text',
						);
						$this->wp_customize->add_control( $setting_id, $args );

					}

				# TextArea
					function setting_input_textarea( $setting_id, $property_data, $label, $description ) {
						$args = array(
							'default' => ( isset( $property_data['default'] ) ? $property_data['default'] : '' ),
							'transport' => 'postMessage',
							'sanitize_callback' => 'esc_textarea',
						);
						$this->wp_customize->add_setting( $setting_id, $args );
						$args = array(
							'label' => esc_html( $label ),
							'description' => ( $description != '' ? $description : '' ),
							'section' => $this->section['id'],
							'settings' => $setting_id,
							'type' => 'textarea',
						);
						$this->wp_customize->add_control( $setting_id, $args );
					}

				# Range
					function setting_input_range( $setting_id, $property_data, $label, $description, $min = '-1000', $max = '1000', $step = '10', $class = 'setting-class' ) {
						$args = array(
							'default' => ( isset( $property_data['default'] ) ? $property_data['default'] : 0 ),
							'transport' => 'postMessage',
							'sanitize_callback' => 'sanitize_text_field',
						);
						$this->wp_customize->add_setting( $setting_id, $args );
						$args = array(
							'label' => esc_html( $label ),
							'description' => ( $description != '' ? $description : '' ),
							'section' => $this->section['id'],
							'settings' => $setting_id,
							'type' => 'text',
							'input_attrs' => array(
								'class' => 'shapeshifter-input-text-associated-with-range',
								'data-min' => $min,
								'data-max' => $max,
								'data-step' => $step,
								'data-class' => $class,
								'data-value' => preg_replace( '/([^0-9\.\-]+)/', '', ( isset( $this->theme_mods[ $setting_id ] ) ? $this->theme_mods[ $setting_id ] : 0 ) ),
							),
						);
						$this->wp_customize->add_control( $setting_id, $args );
					}

				# Choices
					function setting_input_with_choices( $setting_id, $property_data, $label, $description, $choices = array() ) {

						if ( ! isset( $property_data['type'] ) 
							|| ! in_array( $property_data['type'], array( 'select', 'radio' ) ) 
						) {
							$this->setting_input_radio( $setting_id, $property_data, $label, $description, $choices );
						} else {
							$func_name = 'setting_input_' . $property_data['type'];
							if ( method_exists( $this, $func_name ) ) {
								call_user_func_array( 
									array( $this, $func_name ), 
									array( $setting_id, $property_data, $label, $description, $choices ) 
								);
							} 
							return;
						}
					}

						function setting_input_select( $setting_id, $property_data, $label, $description, $choices ) {
							if ( isset( $property_data['choices'] ) )
								$choices = array_merge( $choices, $property_data['choices'] );
							$args = array(
								'default' => ( isset( $property_data['default'] ) ? $property_data['default'] : '' ),
								'transport' => 'postMessage',
								'sanitize_callback' => 'sanitize_text_field',
							);
							$this->wp_customize->add_setting( $setting_id, $args );
							$args = array(
								'label' => esc_html( $label ),
								'description' => ( $description != '' ? $description : '' ),
								'section' => $this->section['id'],
								'settings' => $setting_id,
								'type' => 'select',
								'choices' => $choices,
							);
							$this->wp_customize->add_control( $setting_id, $args );
						}

						function setting_input_radio( $setting_id, $property_data, $label, $description, $choices ) { 
							if ( isset( $property_data['choices'] ) )
								$choices = array_merge( $choices, $property_data['choices'] );
							foreach( $choices as $index => $value ) {
								$this->inputs_hidden .= '<input type="hidden" value="' . $index . '" class="radio-' . $setting_id . '" data-text="' . $value . '">' . PHP_EOL;
							}
							$choices = array_merge( array( 'custom' => '<input type="text" value="' . ( isset( $this->theme_mods[ $setting_id ] ) ? $this->theme_mods[ $setting_id ] : '' ). '" class="shapeshifter-input-text-associated-with-radio">' ), $choices );
							$args = array(
								'default' => ( isset( $property_data['default'] ) ? $property_data['default'] : '' ),
								'transport' => 'postMessage',
								'sanitize_callback' => 'sanitize_text_field',
							);
							$this->wp_customize->add_setting( $setting_id, $args );
							$args = array(
								'label' => esc_html( $label ),
								'description' => ( $description != '' ? $description : '' ),
								'section' => $this->section['id'],
								'settings' => $setting_id,
								'type' => 'hidden',
								'input_attrs' => array(
									'class' => 'shapeshifter-input-radio-text',
									'data-value' => ( isset( $this->theme_mods[ $setting_id ] ) ? $this->theme_mods[ $setting_id ] : '' ),
								),
							);
							$this->wp_customize->add_control( $setting_id, $args );
						}

				# Checkbox
					function setting_input_checkbox( $setting_id, $property_data, $label, $description ) { 
						$args = array(
							'default' => ( isset( $property_data['default'] ) ? $property_data['default'] : '' ),
							'transport' => 'postMessage',
							'sanitize_callback' => 'sanitize_text_field',
						);
						$this->wp_customize->add_setting( $setting_id, $args );
						$args = array(
							'label' => esc_html( $label ),
							'description' => ( $description != '' ? $description : '' ),
							'section' => $this->section['id'],
							'settings' => $setting_id,
							'type' => 'checkbox',
						);
						$this->wp_customize->add_control( 'header_image_title_display_toggle', $args );
					}

				# Image
					function setting_image_upload( $setting_id, $property_data, $label, $description ) { 
						$args = array(
							'default' => ( isset( $property_data['default'] ) ? $property_data['default'] : '' ),
							'transport'=> 'postMessage',
							'sanitize_callback' => 'esc_url_raw',
						);
						$this->wp_customize->add_setting( $setting_id, $args );
						$args = array(
							'label' => esc_html( $label ),
							'description' => ( $description != '' ? $description : '' ),
							'section' => $this->section['id'],
							'settings' => $setting_id,
						);
						$this->wp_customize->add_control( new WP_Customize_Image_Control( $this->wp_customize, $setting_id, $args ) );
					}

				# Color
					function setting_color_picker( $setting_id, $property_data, $label, $description ) { 
						$args = array( 
							'default' => ( isset( $property_data['default'] ) ? $property_data['default'] : '' ),
							'transport' => 'postMessage',
							'sanitize_callback' => 'sanitize_text_field',
						);
						$this->wp_customize->add_setting( $setting_id, $args );
						$args = array(
							'label' => esc_html( $label ),
							'description' => ( $description != '' ? $description : '' ),
							'section' => $this->section['id'],
							'settings' => $setting_id,
						);
						$this->wp_customize->add_control( new WP_Customize_Color_Control( $this->wp_customize, $setting_id, $args ) );
					}

			# Style Property
				# Color
					function setting_color( $setting_id, $property_data ) {
						$label = esc_html__( 'Text Color', $this->textdomain );
						$description = sprintf( esc_html__( 'Pick a Color of %1$s for the element. ( property: "%2$s" )', $this->textdomain ), esc_html__( 'Text', $this->textdomain ), 'color' );
						$this->setting_color_picker( $setting_id, $property_data, $label, $description );
					}
					function setting_opacity( $setting_id, $property_data ) {
						$label = esc_html__( 'Opacity', $this->textdomain );
						$description = sprintf( esc_html__( 'Set the value of %1$s for the element. ( property: "%2$s" ) * step value for slidebar : "%3$d"', $this->textdomain ), esc_html__( 'Opacity', $this->textdomain ), 'opacity', '0.1' );
						$this->setting_input_range( $setting_id, $property_data, $label, $description, '0.0', '1.0', '0.1', 'opacity' );
					}

				# Background
					function setting_background( $setting_id, $property_data ) {
						$label = esc_html__( 'Background', $this->textdomain );
						$description = sprintf( esc_html__( 'Enter the value of CSS property "%s" for the element', $this->textdomain ), 'background' );
						$this->setting_input_text( $setting_id, $property_data, $label, $description );
					}
					function setting_background_attachment( $setting_id, $property_data ) {
						$label = esc_html__( 'Background Attachment', $this->textdomain );
						$description = sprintf( esc_html__( 'Select the value of %1$s for the element. ( property : "%2$s" )', $this->textdomain ), esc_html__( 'Background Attachment', $this->textdomain ) . ' ( fixed or scrollable )', 'background-attachement' );
						$choices = array(
							'fixed' => esc_html__( 'fixed', $this->textdomain ) ,
							'scroll' => esc_html__( 'scrollable', $this->textdomain ),
							'local' => esc_html__( 'based on the contents of the element', $this->textdomain ),
							'initial' => esc_html__( 'Default Value', $this->textdomain ),
							'inherit' => esc_html__( 'Inherit from parent', $this->textdomain ),
						);
						$this->setting_input_with_choices( $setting_id, $property_data, $label, $description, $choices );
					}
					function setting_background_blend_mode( $setting_id, $property_data ) {
						$label = esc_html__( 'Background Blend Mode', $this->textdomain );
						$description = sprintf( esc_html__( 'Select the value of %1$s for the element. ( property : "%2$s" )', $this->textdomain ), esc_html__( 'Background Blend Mode', $this->textdomain ) . ' ( color and image )', 'background-blend-mode' );
						$choices = array(
							'normal'      => esc_html__( 'normal', $this->textdomain ),
							'multiply'    => esc_html__( 'multiply', $this->textdomain ),
							'screen'      => esc_html__( 'screen', $this->textdomain ),
							'overlay'     => esc_html__( 'overlay', $this->textdomain ),
							'darken'      => esc_html__( 'darken', $this->textdomain ),
							'lighten'     => esc_html__( 'lighten', $this->textdomain ),
							'color-dodge' => esc_html__( 'color-dodge', $this->textdomain ),
							'saturation'  => esc_html__( 'saturation', $this->textdomain ),
							'color'       => esc_html__( 'color', $this->textdomain ),
							'luminosity'  => esc_html__( 'luminosity', $this->textdomain )
						);
						$this->setting_input_with_choices( $setting_id, $property_data, $label, $description, $choices );
					}
					function setting_background_color( $setting_id, $property_data ) {
						$label = esc_html__( 'Background Color', $this->textdomain );
						$description = sprintf( esc_html__( 'Pick a Color of %1$s for the element. ( property: "%2$s" )', $this->textdomain ), esc_html__( 'Background', $this->textdomain ), 'background-color' );
						$this->setting_color_picker( $setting_id, $property_data, $label, $description );
					}
					function setting_background_image( $setting_id, $property_data ) {
						$label = esc_html__( 'Background Image', $this->textdomain );
						$description = sprintf( esc_html__( 'Select an image of %1$s for the element. ( property: "%2$s" )', $this->textdomain ), esc_html__( 'Background', $this->textdomain ), 'background-image' );
						$this->setting_image_upload( $setting_id, $property_data, $label, $description );
					}
					function setting_background_position( $setting_id, $property_data ) {
						$label = esc_html__( 'Background Position', $this->textdomain );
						$description = sprintf( esc_html__( 'Enter the value of CSS property "%s" for the element', $this->textdomain ), 'background-position' );
						$this->setting_input_text( $setting_id, $property_data, $label, $description );
					}
					function setting_background_repeat( $setting_id, $property_data ) {
						$label = esc_html__( 'Background Repeat', $this->textdomain );
						$description = sprintf( esc_html__( 'Select the value of %1$s for the element. ( property : "%2$s" )', $this->textdomain ), esc_html__( 'Background Repeat', $this->textdomain ), 'background-repeat' );
						$choices = array(
							'repeat' => esc_html__( 'Repeat ( X and Y )', $this->textdomain ),
							'repeat-x' => esc_html__( 'Repeat X', $this->textdomain ),
							'repeat-y' => esc_html__( 'Repeat Y', $this->textdomain ),
							'no-repeat' =>  esc_html__( 'No Repeat', $this->textdomain ),
						);
						$this->setting_input_with_choices( $setting_id, $property_data, $label, $description, $choices );
					}
					function setting_background_clip( $setting_id, $property_data ) {
						$label = esc_html__( 'Background Clip', $this->textdomain );
						$description = sprintf( esc_html__( 'Select the value of %1$s for the element. ( property : "%2$s" )', $this->textdomain ), esc_html__( 'Background Clip', $this->textdomain ), 'background-clip' );
						$choices = array(
							'border-box' => esc_html__( 'border-box', $this->textdomain ),
							'padding-box' => esc_html__( 'padding-box', $this->textdomain ),
							'content-box' => esc_html__( 'content-box', $this->textdomain )
						);
						$this->setting_input_with_choices( $setting_id, $property_data, $label, $description, $choices );
					}
					function setting_background_origin( $setting_id, $property_data ) {
						$label = esc_html__( 'Background Origin', $this->textdomain );
						$description = sprintf( esc_html__( 'Select the value of %1$s for the element. ( property : "%2$s" )', $this->textdomain ), esc_html__( 'Background Origin', $this->textdomain ), 'background-origin' );
						$choices = array(
							'border-box' => esc_html__( 'border-box', $this->textdomain ),
							'padding-box' => esc_html__( 'padding-box', $this->textdomain ),
							'content-box' => esc_html__( 'content-box', $this->textdomain )
						);
						$this->setting_input_with_choices( $setting_id, $property_data, $label, $description, $choices );
					}
					function setting_background_size( $setting_id, $property_data ) {
						$label = esc_html__( 'Background Size', $this->textdomain );
						$description = sprintf( esc_html__( 'Set the value of %1$s for the element. ( property: "%2$s" ) * step value for slidebar : "%3$d"', $this->textdomain ), esc_html__( 'Background Size', $this->textdomain ), 'background-size', '10' ) . sprintf( esc_html__( ' * Unit for the slidebar is "%s".', $this->textdomain ), 'px' );
						$this->setting_input_range( $setting_id, $property_data, $label, $description, 10, $this->content_width, '10', 'background-size' );
					}
				// 'background', 'background-attachment', 'background-blend-mode', 'background-color', 'background-image', 'background-position', 'background-repeat', 'background-clip', 'background-origin', 'background-size'

				# Border
					function setting_border( $setting_id, $property_data ) {
						$label = esc_html__( 'Border', $this->textdomain );
						$description = sprintf( esc_html__( 'Enter the value of CSS property "%s" for the element', $this->textdomain ), 'border' );
						$this->setting_input_text( $setting_id, $property_data, $label, $description );
					}
					function setting_border_color( $setting_id, $property_data ) {
						$label = esc_html__( 'Border Color', $this->textdomain );
						$description = sprintf( esc_html__( 'Pick a Color of %1$s for the element. ( property: "%2$s" )', $this->textdomain ), esc_html__( 'Border', $this->textdomain ), 'border-color' );
						$this->setting_color_picker( $setting_id, $property_data, $label, $description );
					}
					function setting_border_style( $setting_id, $property_data ) {
						$label = esc_html__( 'Border Style', $this->textdomain );
						$description = sprintf( esc_html__( 'Select the value of %1$s for the element. ( property : "%2$s" )', $this->textdomain ), esc_html__( 'Border Style', $this->textdomain ), 'border-style' );
						$choices = array(
							'none' => esc_html__( 'None', $this->textdomain ),
							'hidden' => esc_html__( 'Hidden', $this->textdomain ),
							'dotted' => esc_html__( 'Dotted', $this->textdomain ),
							'dashed' => esc_html__( 'Dashed', $this->textdomain ),
							'solid' => esc_html__( 'Solid', $this->textdomain ),
							'double' => esc_html__( 'Double', $this->textdomain ),
							'groove' => esc_html__( 'Groove', $this->textdomain ),
							'ridge' => esc_html__( 'Ridge', $this->textdomain ),
							'inset' => esc_html__( 'Inset', $this->textdomain ),
							'outset' => esc_html__( 'Outset', $this->textdomain ),
						);
						$this->setting_input_with_choices( $setting_id, $property_data, $label, $description, $choices, $choices );
					}
					function setting_border_width( $setting_id, $property_data ) {
						$label = esc_html__( 'Border Width', $this->textdomain );
						$description = sprintf( esc_html__( 'Select the value of %1$s for the element. ( property : "%2$s" )', $this->textdomain ), esc_html__( 'Border Width', $this->textdomain ), 'border-width' );
						$choices = array(
							'thin' => esc_html__( 'Thin', $this->textdomain ),
							'medium' => esc_html__( 'Medium', $this->textdomain ),
							'thick' => esc_html__( 'Thick', $this->textdomain ),
							'1px' => '1px',
							'10px' => '10px',
							'20px' => '20px',
						);
						$this->setting_input_with_choices( $setting_id, $property_data, $label, $description, $choices, $choices );
					}
					function setting_border_top( $setting_id, $property_data ) {
						$label = esc_html__( 'Border Top', $this->textdomain );
						$description = sprintf( esc_html__( 'Enter the value of CSS property "%s" for the element', $this->textdomain ), 'border-top' );
						$this->setting_input_text( $setting_id, $property_data, $label, $description );
					}
					function setting_border_top_color( $setting_id, $property_data ) {
						$label = esc_html__( 'Border Top Color', $this->textdomain );
						$description = sprintf( esc_html__( 'Pick a Color of %1$s for the element. ( property: "%2$s" )', $this->textdomain ), esc_html__( 'Border Top', $this->textdomain ), 'border-top-color' );
						$this->setting_color_picker( $setting_id, $property_data, $label, $description );
					}
					function setting_border_top_style( $setting_id, $property_data ) {
						$label = esc_html__( 'Border Top Style', $this->textdomain );
						$description = sprintf( esc_html__( 'Select the value of %1$s for the element. ( property : "%2$s" )', $this->textdomain ), esc_html__( 'Border Top Style', $this->textdomain ), 'border-top-style' );
						$choices = array(
							'none' => esc_html__( 'None', $this->textdomain ),
							'hidden' => esc_html__( 'Hidden', $this->textdomain ),
							'dotted' => esc_html__( 'Dotted', $this->textdomain ),
							'dashed' => esc_html__( 'Dashed', $this->textdomain ),
							'solid' => esc_html__( 'Solid', $this->textdomain ),
							'double' => esc_html__( 'Double', $this->textdomain ),
							'groove' => esc_html__( 'Groove', $this->textdomain ),
							'ridge' => esc_html__( 'Ridge', $this->textdomain ),
							'inset' => esc_html__( 'Inset', $this->textdomain ),
							'outset' => esc_html__( 'Outset', $this->textdomain ),
						);
						$this->setting_input_with_choices( $setting_id, $property_data, $label, $description, $choices );
					}
					function setting_border_top_width( $setting_id, $property_data ) {
						$label = esc_html__( 'Border Top Width', $this->textdomain );
						$description = sprintf( esc_html__( 'Select the value of %1$s for the element. ( property : "%2$s" )', $this->textdomain ), esc_html__( 'Border Top Width', $this->textdomain ), 'border-top-width' );
						$choices = array(
							'thin' => esc_html__( 'Thin', $this->textdomain ),
							'medium' => esc_html__( 'Medium', $this->textdomain ),
							'thick' => esc_html__( 'Thick', $this->textdomain ),
							'1px' => '1px',
							'10px' => '10px',
							'20px' => '20px',
						);
						$this->setting_input_with_choices( $setting_id, $property_data, $label, $description, $choices );
					}
					function setting_border_bottom( $setting_id, $property_data ) {
						$label = esc_html__( 'Border Bottom', $this->textdomain );
						$description = sprintf( esc_html__( 'Enter the value of CSS property "%s" for the element', $this->textdomain ), 'border-bottom' );
						$this->setting_input_text( $setting_id, $property_data, $label, $description );
					}
					function setting_border_bottom_color( $setting_id, $property_data ) {
						$label = esc_html__( 'Border Bottom Color', $this->textdomain );
						$description = sprintf( esc_html__( 'Pick a Color of %1$s for the element. ( property: "%2$s" )', $this->textdomain ), esc_html__( 'Border Bottom', $this->textdomain ), 'border-bottom-color' );
						$this->setting_color_picker( $setting_id, $property_data, $label, $description );
					}
					function setting_border_bottom_style( $setting_id, $property_data ) {
						$label = esc_html__( 'Border Bottom Style', $this->textdomain );
						$description = sprintf( esc_html__( 'Select the value of %1$s for the element. ( property : "%2$s" )', $this->textdomain ), esc_html__( 'Border Bottom Style', $this->textdomain ), 'border-bottom-style' );
						$choices = array(
							'none' => esc_html__( 'None', $this->textdomain ),
							'hidden' => esc_html__( 'Hidden', $this->textdomain ),
							'dotted' => esc_html__( 'Dotted', $this->textdomain ),
							'dashed' => esc_html__( 'Dashed', $this->textdomain ),
							'solid' => esc_html__( 'Solid', $this->textdomain ),
							'double' => esc_html__( 'Double', $this->textdomain ),
							'groove' => esc_html__( 'Groove', $this->textdomain ),
							'ridge' => esc_html__( 'Ridge', $this->textdomain ),
							'inset' => esc_html__( 'Inset', $this->textdomain ),
							'outset' => esc_html__( 'Outset', $this->textdomain ),
						);
						$this->setting_input_with_choices( $setting_id, $property_data, $label, $description, $choices );
					}
					function setting_border_bottom_width( $setting_id, $property_data ) {
						$label = esc_html__( 'Border Bottom Width', $this->textdomain );
						$description = sprintf( esc_html__( 'Select the value of %1$s for the element. ( property : "%2$s" )', $this->textdomain ), esc_html__( 'Border Bottom Width', $this->textdomain ), 'border-bottom-width' );
						$choices = array(
							'thin' => esc_html__( 'Thin', $this->textdomain ),
							'medium' => esc_html__( 'Medium', $this->textdomain ),
							'thick' => esc_html__( 'Thick', $this->textdomain ),
							'1px' => '1px',
							'10px' => '10px',
							'20px' => '20px',
						);
						$this->setting_input_with_choices( $setting_id, $property_data, $label, $description, $choices );
					}
					function setting_border_left( $setting_id, $property_data ) {
						$label = esc_html__( 'Border Left', $this->textdomain );
						$description = sprintf( esc_html__( 'Enter the value of CSS property "%s" for the element', $this->textdomain ), 'border-left' );
						$this->setting_input_text( $setting_id, $property_data, $label, $description );
					}
					function setting_border_left_color( $setting_id, $property_data ) {
						$label = esc_html__( 'Border Left Color', $this->textdomain );
						$description = sprintf( esc_html__( 'Pick a Color of %1$s for the element. ( property: "%2$s" )', $this->textdomain ), esc_html__( 'Border Left', $this->textdomain ), 'border-left-color' );
						$this->setting_color_picker( $setting_id, $property_data, $label, $description );
					}
					function setting_border_left_style( $setting_id, $property_data ) {
						$label = esc_html__( 'Border Left Style', $this->textdomain );
						$description = sprintf( esc_html__( 'Select the value of %1$s for the element. ( property : "%2$s" )', $this->textdomain ), esc_html__( 'Border Left Style', $this->textdomain ), 'border-left-style' );
						$choices = array(
							'none' => esc_html__( 'None', $this->textdomain ),
							'hidden' => esc_html__( 'Hidden', $this->textdomain ),
							'dotted' => esc_html__( 'Dotted', $this->textdomain ),
							'dashed' => esc_html__( 'Dashed', $this->textdomain ),
							'solid' => esc_html__( 'Solid', $this->textdomain ),
							'double' => esc_html__( 'Double', $this->textdomain ),
							'groove' => esc_html__( 'Groove', $this->textdomain ),
							'ridge' => esc_html__( 'Ridge', $this->textdomain ),
							'inset' => esc_html__( 'Inset', $this->textdomain ),
							'outset' => esc_html__( 'Outset', $this->textdomain ),
						);
						$this->setting_input_with_choices( $setting_id, $property_data, $label, $description, $choices );
					}
					function setting_border_left_width( $setting_id, $property_data ) {
						$label = esc_html__( 'Border Left Width', $this->textdomain );
						$description = sprintf( esc_html__( 'Select the value of %1$s for the element. ( property : "%2$s" )', $this->textdomain ), esc_html__( 'Border Left Width', $this->textdomain ), 'border-left-width' );
						$choices = array(
							'thin' => esc_html__( 'Thin', $this->textdomain ),
							'medium' => esc_html__( 'Medium', $this->textdomain ),
							'thick' => esc_html__( 'Thick', $this->textdomain ),
							'1px' => '1px',
							'10px' => '10px',
							'20px' => '20px',
						);
						$this->setting_input_with_choices( $setting_id, $property_data, $label, $description, $choices );
					}
					function setting_border_right( $setting_id, $property_data ) {
						$label = esc_html__( 'Border Right', $this->textdomain );
						$description = sprintf( esc_html__( 'Enter the value of CSS property "%s" for the element', $this->textdomain ), 'border-right' );
						$this->setting_input_text( $setting_id, $property_data, $label, $description );
					}
					function setting_border_right_color( $setting_id, $property_data ) {
						$label = esc_html__( 'Border Right Color', $this->textdomain );
						$description = sprintf( esc_html__( 'Pick a Color of %1$s for the element. ( property: "%2$s" )', $this->textdomain ), esc_html__( 'Border Right', $this->textdomain ), 'border-right-color' );
						$this->setting_color_picker( $setting_id, $property_data, $label, $description );
					}
					function setting_border_right_style( $setting_id, $property_data ) {
						$label = esc_html__( 'Border Right Style', $this->textdomain );
						$description = sprintf( esc_html__( 'Select the value of %1$s for the element. ( property : "%2$s" )', $this->textdomain ), esc_html__( 'Border Right Style', $this->textdomain ), 'border-right-style' );
						$choices = array(
							'none' => esc_html__( 'None', $this->textdomain ),
							'hidden' => esc_html__( 'Hidden', $this->textdomain ),
							'dotted' => esc_html__( 'Dotted', $this->textdomain ),
							'dashed' => esc_html__( 'Dashed', $this->textdomain ),
							'solid' => esc_html__( 'Solid', $this->textdomain ),
							'double' => esc_html__( 'Double', $this->textdomain ),
							'groove' => esc_html__( 'Groove', $this->textdomain ),
							'ridge' => esc_html__( 'Ridge', $this->textdomain ),
							'inset' => esc_html__( 'Inset', $this->textdomain ),
							'outset' => esc_html__( 'Outset', $this->textdomain ),
						);
						$this->setting_input_with_choices( $setting_id, $property_data, $label, $description, $choices );
					}
					function setting_border_right_width( $setting_id, $property_data ) {
						$label = esc_html__( 'Border Right Width', $this->textdomain );
						$description = sprintf( esc_html__( 'Select the value of %1$s for the element. ( property : "%2$s" )', $this->textdomain ), esc_html__( 'Border Right Width', $this->textdomain ), 'border-right-width' );
						$choices = array(
							'thin' => esc_html__( 'Thin', $this->textdomain ),
							'medium' => esc_html__( 'Medium', $this->textdomain ),
							'thick' => esc_html__( 'Thick', $this->textdomain ),
							'1px' => '1px',
							'10px' => '10px',
							'20px' => '20px',
						);
						$this->setting_input_with_choices( $setting_id, $property_data, $label, $description, $choices );
					}
					function setting_border_radius( $setting_id, $property_data ) {
						$label = esc_html__( 'Border Radius', $this->textdomain );
						$description = sprintf( esc_html__( 'Set the value of %1$s for the element. ( property: "%2$s" ) * step value for slidebar : "%3$d"', $this->textdomain ), esc_html__( 'Border Radius', $this->textdomain ), 'border-radius', '1' ) . sprintf( esc_html__( ' * Unit for the slidebar is "%s".', $this->textdomain ), 'px' );
						$this->setting_input_range( $setting_id, $property_data, $label, $description, 1, ( $this->content_width / 2 ), '1', 'border-radius' );
					}
					function setting_border_top_left_radius( $setting_id, $property_data ) {
						$label = esc_html__( 'Border Top Left Radius', $this->textdomain );
						$description = sprintf( esc_html__( 'Set the value of %1$s for the element. ( property: "%2$s" ) * step value for slidebar : "%3$d"', $this->textdomain ), esc_html__( 'Border Top Left Radius', $this->textdomain ), 'border-top-left-radius', '1' ) . sprintf( esc_html__( ' * Unit for the slidebar is "%s".', $this->textdomain ), 'px' );
						$this->setting_input_range( $setting_id, $property_data, $label, $description, 1, ( $this->content_width / 2 ), '1', 'border-top-left-radius' );
					}
					function setting_border_top_right_radius( $setting_id, $property_data ) {
						$label = esc_html__( 'Border Top Right Radius', $this->textdomain );
						$description = sprintf( esc_html__( 'Set the value of %1$s for the element. ( property: "%2$s" ) * step value for slidebar : "%3$d"', $this->textdomain ), esc_html__( 'Border Top Right Radius', $this->textdomain ), 'border-top-right-radius', '1' ) . sprintf( esc_html__( ' * Unit for the slidebar is "%s".', $this->textdomain ), 'px' );
						$this->setting_input_range( $setting_id, $property_data, $label, $description, 1, ( $this->content_width / 2 ), '1', 'border-top-right-radius' );
					}
					function setting_border_bottom_left_radius( $setting_id, $property_data ) {
						$label = esc_html__( 'Border Bottom Left Radius', $this->textdomain );
						$description = sprintf( esc_html__( 'Set the value of %1$s for the element. ( property: "%2$s" ) * step value for slidebar : "%3$d"', $this->textdomain ), esc_html__( 'Border Bottom Left Radius', $this->textdomain ), 'border-bottom-left-radius', '1' ) . sprintf( esc_html__( ' * Unit for the slidebar is "%s".', $this->textdomain ), 'px' );
						$this->setting_input_range( $setting_id, $property_data, $label, $description, 1, ( $this->content_width / 2 ), '1', 'border-bottom-left-radius' );
					}
					function setting_border_bottom_right_radius( $setting_id, $property_data ) {
						$label = esc_html__( 'Border Bottom Right Radius', $this->textdomain );
						$description = sprintf( esc_html__( 'Set the value of %1$s for the element. ( property: "%2$s" ) * step value for slidebar : "%3$d"', $this->textdomain ), esc_html__( 'Border Bottom Right Radius', $this->textdomain ), 'border-bottom-right-radius', '1' ) . sprintf( esc_html__( ' * Unit for the slidebar is "%s".', $this->textdomain ), 'px' );
						$this->setting_input_range( $setting_id, $property_data, $label, $description, 1, ( $this->content_width / 2 ), '1', 'border-bottom-right-radius' );
					}
					function setting_border_image( $setting_id, $property_data ) {
						$label = esc_html__( 'Border Image', $this->textdomain );
						$description = sprintf( esc_html__( 'Enter the value of CSS property "%s" for the element', $this->textdomain ), 'border-image' );
						$this->setting_input_text( $setting_id, $property_data, $label, $description );
					}
					function setting_border_image_source( $setting_id, $property_data ) {
						$label = esc_html__( 'Border Image Source', $this->textdomain );
						$description = sprintf( esc_html__( 'Select an image of %1$s for the element. ( property: "%2$s" )', $this->textdomain ), esc_html__( 'Border Image Source', $this->textdomain ), 'border-image-source' );
						$this->setting_image_upload( $setting_id, $property_data, $label, $description );
					}
					function setting_border_image_slice( $setting_id, $property_data ) {
						$label = esc_html__( 'Border Image Slice', $this->textdomain );
						$description = sprintf( esc_html__( 'Set the value of %1$s for the element. ( property: "%2$s" ) * step value for slidebar : "%3$d"', $this->textdomain ), esc_html__( 'Border Image Slice', $this->textdomain ), 'border-image-slice', '5' );
						$this->setting_input_range( $setting_id, $property_data, $label, $description, 10, 50, '5', 'border-image-slice' );
					}
					function setting_border_image_width( $setting_id, $property_data ) {
						$label = esc_html__( 'Border Image Width', $this->textdomain );
						$description = sprintf( esc_html__( 'Set the value of %1$s for the element. ( property: "%2$s" ) * step value for slidebar : "%3$d"', $this->textdomain ), esc_html__( 'Border Image Width', $this->textdomain ), 'border-image-width', '1' ) . sprintf( esc_html__( ' * Unit for the slidebar is "%s".', $this->textdomain ), 'px' );
						$this->setting_input_range( $setting_id, $property_data, $label, $description, 1, 50, '1', 'border-image-width' );
					}
					function setting_border_image_outset( $setting_id, $property_data ) {
						$label = esc_html__( 'Border Image Outset', $this->textdomain );
						$description = sprintf( esc_html__( 'Set the value of %1$s for the element. ( property: "%2$s" ) * step value for slidebar : "%3$d"', $this->textdomain ), esc_html__( 'Border Image Outset', $this->textdomain ), 'border-image-outset', '1' ) . sprintf( esc_html__( ' * Unit for the slidebar is "%s".', $this->textdomain ), 'px' );
						$this->setting_input_range( $setting_id, $property_data, $label, $description, 1, 50, '1', 'border-image-outset' );
					}
					function setting_border_image_repeat( $setting_id, $property_data ) {
						$label = esc_html__( 'Border Image Repeat', $this->textdomain );
						$description = sprintf( esc_html__( 'Select the value of %1$s for the element. ( property : "%2$s" )', $this->textdomain ), esc_html__( 'Border Image Repeat', $this->textdomain ), 'border-image-repeat' );
						$choices = array(
							'stretch' => esc_html__( 'stretch', $this->textdomain ),
							'repeat' => esc_html__( 'repeat', $this->textdomain ),
							'round' => esc_html__( 'round', $this->textdomain ),
							'space' => esc_html__( 'space', $this->textdomain ),
						);
						$this->setting_input_with_choices( $setting_id, $property_data, $label, $description, $choices );
					}
					function setting_box_decoration_break( $setting_id, $property_data ) {
						$label = esc_html__( 'Box Decoration Break', $this->textdomain );
						$description = sprintf( esc_html__( 'Select the value of %1$s for the element. ( property : "%2$s" )', $this->textdomain ), esc_html__( 'Box Decoration Break', $this->textdomain ), 'box-decoration-break' );
						$choices = array(
							'slice' => esc_html__( 'slice', $this->textdomain ),
							'clone' => esc_html__( 'clone', $this->textdomain ),
						);
						$this->setting_input_with_choices( $setting_id, $property_data, $label, $description );
					}
					function setting_box_shadow( $setting_id, $property_data ) {
						$label = esc_html__( 'Box Shadow', $this->textdomain );
						$description = sprintf( esc_html__( 'Enter the value of CSS property "%s" for the element', $this->textdomain ), 'box-shadow' );
						$this->setting_input_text( $setting_id, $property_data, $label, $description );
					}
				// 'border', 'border-color', 'border-style', 'border-width', 'border-top', 'border-top-color', 'border-top-style', 'border-top-width', 'border-bottom', 'border-bottom-color', 'border-bottom-style', 'border-bottom-width', 'border-left', 'border-left-color', 'border-left-style', 'border-left-width', 'border-right', 'border-right-color', 'border-right-style', 'border-right-width', 'border-radius', 'border-top-radius', 'border-bottom-radius', 'border-left-radius', 'border-right-radius', 'border-image', 'border-image-source', 'border-image-slice', 'border-image-width', 'border-image-outset', 'border-image-repeat', 'box-decoration-break', 'box-shadow'

				# Box Size
					function setting_width( $setting_id, $property_data ) {
						$label = esc_html__( 'Width', $this->textdomain );
						$description = sprintf( esc_html__( 'Set the value of %1$s for the element. ( property: "%2$s" ) * step value for slidebar : "%3$d"', $this->textdomain ), esc_html__( 'Width', $this->textdomain ), 'width', '10' ) . sprintf( esc_html__( ' * Unit for the slidebar is "%s".', $this->textdomain ), 'px' );
						$this->setting_input_range( $setting_id, $property_data, $label, $description, '10', $this->content_width, '10', 'width' );
					}
					function setting_max_width( $setting_id, $property_data ) {
						$label = esc_html__( 'Max Width', $this->textdomain );
						$description = sprintf( esc_html__( 'Set the value of %1$s for the element. ( property: "%2$s" ) * step value for slidebar : "%3$d"', $this->textdomain ), esc_html__( 'Max Width', $this->textdomain ), 'max-width', '10' ) . sprintf( esc_html__( ' * Unit for the slidebar is "%s".', $this->textdomain ), 'px' );
						$this->setting_input_range( $setting_id, $property_data, $label, $description, '10', $this->content_width, '10', 'max-width' );
					}
					function setting_min_width( $setting_id, $property_data ) {
						$label = esc_html__( 'Min Width', $this->textdomain );
						$description = sprintf( esc_html__( 'Set the value of %1$s for the element. ( property: "%2$s" ) * step value for slidebar : "%3$d"', $this->textdomain ), esc_html__( 'Min Width', $this->textdomain ), 'min-width', '10' ) . sprintf( esc_html__( ' * Unit for the slidebar is "%s".', $this->textdomain ), 'px' );
						$this->setting_input_range( $setting_id, $property_data, $label, $description, '10', $this->content_width, '10', 'min-width' );
					}
					function setting_height( $setting_id, $property_data ) {
						$label = esc_html__( 'Height', $this->textdomain );
						$description = sprintf( esc_html__( 'Set the value of %1$s for the element. ( property: "%2$s" ) * step value for slidebar : "%3$d"', $this->textdomain ), esc_html__( 'Height', $this->textdomain ), 'height', '10' ) . sprintf( esc_html__( ' * Unit for the slidebar is "%s".', $this->textdomain ), 'px' );
						$this->setting_input_range( $setting_id, $property_data, $label, $description, '10', $this->content_width, '10', 'height' );
					}
					function setting_max_height( $setting_id, $property_data ) {
						$label = esc_html__( 'Max Height', $this->textdomain );
						$description = sprintf( esc_html__( 'Set the value of %1$s for the element. ( property: "%2$s" ) * step value for slidebar : "%3$d"', $this->textdomain ), esc_html__( 'Max Height', $this->textdomain ), 'max-height', '10' ) . sprintf( esc_html__( ' * Unit for the slidebar is "%s".', $this->textdomain ), 'px' );
						$this->setting_input_range( $setting_id, $property_data, $label, $description, '10', $this->content_width, '10', 'max-height' );
					}
					function setting_min_height( $setting_id, $property_data ) {
						$label = esc_html__( 'Min Height', $this->textdomain );
						$description = sprintf( esc_html__( 'Set the value of %1$s for the element. ( property: "%2$s" ) * step value for slidebar : "%3$d"', $this->textdomain ), esc_html__( 'Min Height', $this->textdomain ), 'min-height', '10' ) . sprintf( esc_html__( ' * Unit for the slidebar is "%s".', $this->textdomain ), 'px' );
						$this->setting_input_range( $setting_id, $property_data, $label, $description, '10', $this->content_width, '10', 'min-height' );
					}
				// 'width', 'max-width', 'min-width', 'height', 'max-height', 'min-height'

				# Margin
					function setting_margin( $setting_id, $property_data ) {
						$label = esc_html__( 'Margin', $this->textdomain );
						$description = sprintf( esc_html__( 'Set the value of %1$s for the element. ( property: "%2$s" ) * step value for slidebar : "%3$d"', $this->textdomain ), esc_html__( 'Margin', $this->textdomain ), 'margin', '1' ) . sprintf( esc_html__( ' * Unit for the slidebar is "%s".', $this->textdomain ), 'px' );
						$this->setting_input_range( $setting_id, $property_data, $label, $description, '-100', 100, '1', 'margin' );
					}
					function setting_margin_top( $setting_id, $property_data ) {
						$label = esc_html__( 'Margin Top', $this->textdomain );
						$description = sprintf( esc_html__( 'Set the value of %1$s for the element. ( property: "%2$s" ) * step value for slidebar : "%3$d"', $this->textdomain ), esc_html__( 'Margin Top', $this->textdomain ), 'margin-top', '1' ) . sprintf( esc_html__( ' * Unit for the slidebar is "%s".', $this->textdomain ), 'px' );
						$this->setting_input_range( $setting_id, $property_data, $label, $description, '-100', 100, '1', 'margin-top' );
					}
					function setting_margin_bottom( $setting_id, $property_data ) {
						$label = esc_html__( 'Margin Bottom', $this->textdomain );
						$description = sprintf( esc_html__( 'Set the value of %1$s for the element. ( property: "%2$s" ) * step value for slidebar : "%3$d"', $this->textdomain ), esc_html__( 'Margin Bottom', $this->textdomain ), 'margin-bottom', '1' ) . sprintf( esc_html__( ' * Unit for the slidebar is "%s".', $this->textdomain ), 'px' );
						$this->setting_input_range( $setting_id, $property_data, $label, $description, '-100', 100, '1', 'margin-bottom' );
					}
					function setting_margin_left( $setting_id, $property_data ) {
						$label = esc_html__( 'Margin Left', $this->textdomain );
						$description = sprintf( esc_html__( 'Set the value of %1$s for the element. ( property: "%2$s" ) * step value for slidebar : "%3$d"', $this->textdomain ), esc_html__( 'Margin Left', $this->textdomain ), 'margin-left', '1' ) . sprintf( esc_html__( ' * Unit for the slidebar is "%s".', $this->textdomain ), 'px' );
						$this->setting_input_range( $setting_id, $property_data, $label, $description, '-100', 100, '1', 'margin-left' );
					}
					function setting_margin_right( $setting_id, $property_data ) {
						$label = esc_html__( 'Margin Right', $this->textdomain );
						$description = sprintf( esc_html__( 'Set the value of %1$s for the element. ( property: "%2$s" ) * step value for slidebar : "%3$d"', $this->textdomain ), esc_html__( 'Margin Right', $this->textdomain ), 'margin-right', '1' ) . sprintf( esc_html__( ' * Unit for the slidebar is "%s".', $this->textdomain ), 'px' );
						$this->setting_input_range( $setting_id, $property_data, $label, $description, '-100', 100, '1', 'margin-right' );
					}
				// 'margin', 'margin-top', 'margin-bottom', 'margin-left', 'margin-right'

				# Paddings
					function setting_padding( $setting_id, $property_data ) {
						$label = esc_html__( 'Padding', $this->textdomain );
						$description = sprintf( esc_html__( 'Set the value of %1$s for the element. ( property: "%2$s" ) * step value for slidebar : "%3$d"', $this->textdomain ), esc_html__( 'Padding', $this->textdomain ), 'padding', '1' ) . sprintf( esc_html__( ' * Unit for the slidebar is "%s".', $this->textdomain ), 'px' );
						$this->setting_input_range( $setting_id, $property_data, $label, $description, '-100', 100, '1', 'padding' );
					}
					function setting_padding_top( $setting_id, $property_data ) {
						$label = esc_html__( 'Padding Top', $this->textdomain );
						$description = sprintf( esc_html__( 'Set the value of %1$s for the element. ( property: "%2$s" ) * step value for slidebar : "%3$d"', $this->textdomain ), esc_html__( 'Padding Top', $this->textdomain ), 'padding-top', '1' ) . sprintf( esc_html__( ' * Unit for the slidebar is "%s".', $this->textdomain ), 'px' );
						$this->setting_input_range( $setting_id, $property_data, $label, $description, '-100', 100, '1', 'padding-top' );
					}
					function setting_padding_bottom( $setting_id, $property_data ) {
						$label = esc_html__( 'Padding Bottom', $this->textdomain );
						$description = sprintf( esc_html__( 'Set the value of %1$s for the element. ( property: "%2$s" ) * step value for slidebar : "%3$d"', $this->textdomain ), esc_html__( 'Padding Bottom', $this->textdomain ), 'padding-bottom', '1' ) . sprintf( esc_html__( ' * Unit for the slidebar is "%s".', $this->textdomain ), 'px' );
						$this->setting_input_range( $setting_id, $property_data, $label, $description, '-100', 100, '1', 'padding-bottom' );
					}
					function setting_padding_left( $setting_id, $property_data ) {
						$label = esc_html__( 'Padding Left', $this->textdomain );
						$description = sprintf( esc_html__( 'Set the value of %1$s for the element. ( property: "%2$s" ) * step value for slidebar : "%3$d"', $this->textdomain ), esc_html__( 'Padding Left', $this->textdomain ), 'padding-left', '1' ) . sprintf( esc_html__( ' * Unit for the slidebar is "%s".', $this->textdomain ), 'px' );
						$this->setting_input_range( $setting_id, $property_data, $label, $description, '-100', 100, '1', 'padding-left' );
					}
					function setting_padding_right( $setting_id, $property_data ) {
						$label = esc_html__( 'Padding Right', $this->textdomain );
						$description = sprintf( esc_html__( 'Set the value of %1$s for the element. ( property: "%2$s" ) * step value for slidebar : "%3$d"', $this->textdomain ), esc_html__( 'Padding Right', $this->textdomain ), 'padding-right', '1' ) . sprintf( esc_html__( ' * Unit for the slidebar is "%s".', $this->textdomain ), 'px' );
						$this->setting_input_range( $setting_id, $property_data, $label, $description, '-100', 100, '1', 'padding-right' );
					}
				// 'padding', 'padding-top', 'padding-bottom', 'padding-left', 'padding-right'

				# Display
					function setting_overflow( $setting_id, $property_data ) {
						$label = esc_html__( 'Overflow', $this->textdomain );
						$description = sprintf( esc_html__( 'Select the value of %1$s for the element. ( property : "%2$s" )', $this->textdomain ), esc_html__( 'Overflow', $this->textdomain ), 'overflow' );
						$choices = array(
							'visible' => esc_html__( 'Visible', $this->textdomain ),
							'hidden' => esc_html__( 'Hidden', $this->textdomain ),
							'scroll' => esc_html__( 'Scroll', $this->textdomain ),
							'auto' => esc_html__( 'Auto', $this->textdomain ),
						);
						$this->setting_input_with_choices( $setting_id, $property_data, $label, $description, $choices );
					}
					function setting_overflow_x( $setting_id, $property_data ) {
						$label = esc_html__( 'Overflow X', $this->textdomain );
						$description = sprintf( esc_html__( 'Select the value of %1$s for the element. ( property : "%2$s" )', $this->textdomain ), esc_html__( 'Overflow X', $this->textdomain ), 'overflow-x' );
						$choices = array(
							'visible' => esc_html__( 'Visible', $this->textdomain ),
							'hidden' => esc_html__( 'Hidden', $this->textdomain ),
							'scroll' => esc_html__( 'Scroll', $this->textdomain ),
							'auto' => esc_html__( 'Auto', $this->textdomain ),
						);
						$this->setting_input_with_choices( $setting_id, $property_data, $label, $description, $choices );
					}
					function setting_overflow_y( $setting_id, $property_data ) {
						$label = esc_html__( 'Overflow Y', $this->textdomain );
						$description = sprintf( esc_html__( 'Select the value of %1$s for the element. ( property : "%2$s" )', $this->textdomain ), esc_html__( 'Overflow Y', $this->textdomain ), 'overflow-y' );
						$choices = array(
							'visible' => esc_html__( 'Visible', $this->textdomain ),
							'hidden' => esc_html__( 'Hidden', $this->textdomain ),
							'scroll' => esc_html__( 'Scroll', $this->textdomain ),
							'auto' => esc_html__( 'Auto', $this->textdomain ),
						);
						$this->setting_input_with_choices( $setting_id, $property_data, $label, $description, $choices );
					}
					function setting_position( $setting_id, $property_data ) {
						$label = esc_html__( 'Position', $this->textdomain );
						$description = sprintf( esc_html__( 'Select the value of %1$s for the element. ( property : "%2$s" )', $this->textdomain ), esc_html__( 'Position', $this->textdomain ), 'position' );
						$choices = array(
							'static' => esc_html__( 'Static', $this->textdomain ),
							'absolute' => esc_html__( 'Absolute', $this->textdomain ),
							'fixed' => esc_html__( 'Fixed', $this->textdomain ),
							'relative' => esc_html__( 'Relative', $this->textdomain ),
						);
						$this->setting_input_with_choices( $setting_id, $property_data, $label, $description, $choices );
					}
					function setting_top( $setting_id, $property_data ) {
						$label = esc_html__( 'Top', $this->textdomain );
						$description = sprintf( esc_html__( 'Set the value of %1$s for the element. ( property: "%2$s" ) * step value for slidebar : "%3$d"', $this->textdomain ), esc_html__( 'Top', $this->textdomain ), 'top', '1' ) . sprintf( esc_html__( ' * Unit for the slidebar is "%s".', $this->textdomain ), 'px' );
						$this->setting_input_range( $setting_id, $property_data, $label, $description, 1, 200, '1', 'top' );
					}
					function setting_bottom( $setting_id, $property_data ) {
						$label = esc_html__( 'Bottom', $this->textdomain );
						$description = sprintf( esc_html__( 'Set the value of %1$s for the element. ( property: "%2$s" ) * step value for slidebar : "%3$d"', $this->textdomain ), esc_html__( 'Bottom', $this->textdomain ), 'bottom', '1' ) . sprintf( esc_html__( ' * Unit for the slidebar is "%s".', $this->textdomain ), 'px' );
						$this->setting_input_range( $setting_id, $property_data, $label, $description, 1, 200, '1', 'bottom' );
					}
					function setting_left( $setting_id, $property_data ) {
						$label = esc_html__( 'Left', $this->textdomain );
						$description = sprintf( esc_html__( 'Set the value of %1$s for the element. ( property: "%2$s" ) * step value for slidebar : "%3$d"', $this->textdomain ), esc_html__( 'Left', $this->textdomain ), 'left', '1' ) . sprintf( esc_html__( ' * Unit for the slidebar is "%s".', $this->textdomain ), 'px' );
						$this->setting_input_range( $setting_id, $property_data, $label, $description, 1, 200, '1', 'left' );
					}
					function setting_right( $setting_id, $property_data ) {
						$label = esc_html__( 'Right', $this->textdomain );
						$description = sprintf( esc_html__( 'Set the value of %1$s for the element. ( property: "%2$s" ) * step value for slidebar : "%3$d"', $this->textdomain ), esc_html__( 'Right', $this->textdomain ), 'right', '1' ) . sprintf( esc_html__( ' * Unit for the slidebar is "%s".', $this->textdomain ), 'px' );
						$this->setting_input_range( $setting_id, $property_data, $label, $description, 1, 200, '1', 'right' );
					}
					function setting_display( $setting_id, $property_data ) {
						$label = esc_html__( 'Display', $this->textdomain );
						$description = sprintf( esc_html__( 'Select the value of %1$s for the element. ( property : "%2$s" )', $this->textdomain ), esc_html__( 'Display', $this->textdomain ), 'display' );
						$choices = array(
							'block' => esc_html__( 'Block', $this->textdomain ),
							'flex' => esc_html__( 'Flex', $this->textdomain ),

							'inline' => esc_html__( 'Inline', $this->textdomain ),
							'inline-block' => esc_html__( 'Inline-block', $this->textdomain ),
							'inline-flex' => esc_html__( 'Inline-flex', $this->textdomain ),
							'inline-table' => esc_html__( 'Inline-table', $this->textdomain ),
							'list-item' => esc_html__( 'List-item', $this->textdomain ),
							
							'run-in' => esc_html__( 'Run-in', $this->textdomain ),

							'table' => esc_html__( 'Table', $this->textdomain ),
							'table-caption' => esc_html__( 'Table-caption', $this->textdomain ),
							'table-column-group' => esc_html__( 'Table-column-group', $this->textdomain ),
							'table-header-group' => esc_html__( 'Table-header-group', $this->textdomain ),
							'table-footer-group' => esc_html__( 'Table-footer-group', $this->textdomain ),
							'table-row-group' => esc_html__( 'Table-row-group', $this->textdomain ),
							'table-cell' => esc_html__( 'Table-cell', $this->textdomain ),
							'table-column' => esc_html__( 'Table-column', $this->textdomain ),
							'table-row' => esc_html__( 'Table-row', $this->textdomain ),

							'none' => esc_html__( 'None', $this->textdomain ),
							'initial' => esc_html__( 'Initial', $this->textdomain ),
							'inherit' => esc_html__( 'Inherit', $this->textdomain ),
						);
						$this->setting_input_with_choices( $setting_id, $property_data, $label, $description, $choices );
					}
					function setting_float( $setting_id, $property_data ) {
						$label = esc_html__( 'Float', $this->textdomain );
						$description = sprintf( esc_html__( 'Select the value of %1$s for the element. ( property : "%2$s" )', $this->textdomain ), esc_html__( 'Float', $this->textdomain ), 'float' );
						$choices = array(
							'none' => esc_html__( 'None', $this->textdomain ),
							'left' => esc_html__( 'Left', $this->textdomain ),
							'right' => esc_html__( 'Right', $this->textdomain ),
							'initial' => esc_html__( 'Initial', $this->textdomain ),
							'inherit' => esc_html__( 'Inherit', $this->textdomain ),
						);
						$this->setting_input_with_choices( $setting_id, $property_data, $label, $description, $choices );
					}
					function setting_clear( $setting_id, $property_data ) {
						$label = esc_html__( 'Clear', $this->textdomain );
						$description = sprintf( esc_html__( 'Select the value of %1$s for the element. ( property : "%2$s" )', $this->textdomain ), esc_html__( 'Clear', $this->textdomain ), 'clear' );
						$choices = array(
							'none' => esc_html__( 'none', $this->textdomain ),
							'left' => esc_html__( 'left', $this->textdomain ),
							'right' => esc_html__( 'right', $this->textdomain ),
							'both' => esc_html__( 'both', $this->textdomain ),
							'initial' => esc_html__( 'initial', $this->textdomain ),
							'inherit' => esc_html__( 'inherit', $this->textdomain ),
						);
						$this->setting_input_with_choices( $setting_id, $property_data, $label, $description, $choices );
					}
					function setting_z_index( $setting_id, $property_data ) {
						$label = esc_html__( 'Z Index', $this->textdomain );
						$description = sprintf( esc_html__( 'Set the value of %1$s for the element. ( property: "%2$s" ) * step value for slidebar : "%3$d"', $this->textdomain ), esc_html__( 'Z Index', $this->textdomain ), 'z-index', '1' );
						$this->setting_input_range( $setting_id, $property_data, $label, $description, 1, 100000, '1', 'z-index' );
					}
					function setting_visibility( $setting_id, $property_data ) {
						$label = esc_html__( 'Visibility', $this->textdomain );
						$description = sprintf( esc_html__( 'Select the value of %1$s for the element. ( property : "%2$s" )', $this->textdomain ), esc_html__( 'Visibility', $this->textdomain ), 'visibility' );
						$choices = array(
							'visible' => esc_html__( 'Visible', $this->textdomain ),
							'hidden' => esc_html__( 'Hidden', $this->textdomain ),
							'collapse' => esc_html__( 'Collapse', $this->textdomain ),
							'initial' => esc_html__( 'Initial', $this->textdomain ),
							'inherit' => esc_html__( 'Inherit', $this->textdomain ),
						);
						$this->setting_input_with_choices( $setting_id, $property_data, $label, $description, $choices );
					}
					function setting_vertical_align( $setting_id, $property_data ) {
						$label = esc_html__( 'Vertical Align', $this->textdomain );
						$description = sprintf( esc_html__( 'Select the value of %1$s for the element. ( property : "%2$s" )', $this->textdomain ), esc_html__( 'Vertical Align', $this->textdomain ), 'vertical-align' );
						$choices = array(
							'baseline' => esc_html__( 'baseline', $this->textdomain ),
							'sub' => esc_html__( 'sub', $this->textdomain ),
							'super' => esc_html__( 'super', $this->textdomain ),
							'top' => esc_html__( 'top', $this->textdomain ),
							'text-top' => esc_html__( 'text-top', $this->textdomain ),
							'middle' => esc_html__( 'middle', $this->textdomain ),
							'bottom' => esc_html__( 'bottom', $this->textdomain ),
							'text-bottom' => esc_html__( 'text-bottom', $this->textdomain ),
							'initial' => esc_html__( 'initial', $this->textdomain ),
						);
						$this->setting_input_with_choices( $setting_id, $property_data, $label, $description, $choices );
					}
					function setting_clip( $setting_id, $property_data ) {
						$label = esc_html__( 'Clip', $this->textdomain );
						$description = sprintf( esc_html__( 'Enter the value of CSS property "%s" for the element', $this->textdomain ), 'clip' ) . esc_html__( '* You can specify by "rect( top, right, bottom, left )".', $this->textdomain );
						$this->setting_input_text( $setting_id, $property_data, $label, $description );
					}
				// 'overflow', 'overflow-x', 'overflow-y', 'position', 'top', 'bottom', 'left', 'right', 'display', 'float', 'clear', 'z-index', 'visibility', 'clip', 'direction', 'unicode-bidi', 'writing-mode'

				# Layout of Flex Box
					function setting_align_content( $setting_id, $property_data ) {
						$label = esc_html__( 'Align Content', $this->textdomain );
						$description = sprintf( esc_html__( 'Select the value of %1$s for the element. ( property : "%2$s" )', $this->textdomain ), esc_html__( 'Align Content', $this->textdomain ), 'align-content' );
						$choices = array(
							'stretch' => esc_html__( 'Stretch', $this->textdomain ),
							'center' => esc_html__( 'Center', $this->textdomain ),
							'flex-start' => esc_html__( 'Flex-start', $this->textdomain ),
							'flex-end' => esc_html__( 'Flex-end', $this->textdomain ),
							'space-between' => esc_html__( 'Space-between', $this->textdomain ),
							'space-around' => esc_html__( 'Space-around', $this->textdomain ),
							'initial' => esc_html__( 'Initial', $this->textdomain ),
						);
						$this->setting_input_with_choices( $setting_id, $property_data, $label, $description, $choices );
					}
					function setting_align_items( $setting_id, $property_data ) {
						$label = esc_html__( 'Align Items', $this->textdomain );
						$description = sprintf( esc_html__( 'Select the value of %1$s for the element. ( property : "%2$s" )', $this->textdomain ), esc_html__( 'Align Items', $this->textdomain ), 'align-items' );
						$choices = array(
							'stretch' => esc_html__( 'Stretch', $this->textdomain ),
							'center' => esc_html__( 'Center', $this->textdomain ),
							'flex-start' => esc_html__( 'Flex-start', $this->textdomain ),
							'flex-end' => esc_html__( 'Flex-end', $this->textdomain ),
							'baseline' => esc_html__( 'Baseline', $this->textdomain ),
							'initial' => esc_html__( 'Initial', $this->textdomain ),
							'inherit' => esc_html__( 'Inherit', $this->textdomain ),
						);
						$this->setting_input_with_choices( $setting_id, $property_data, $label, $description, $choices );
					}
					function setting_align_self( $setting_id, $property_data ) {
						$label = esc_html__( 'Align Self', $this->textdomain );
						$description = sprintf( esc_html__( 'Select the value of %1$s for the element. ( property : "%2$s" )', $this->textdomain ), esc_html__( 'Align Self', $this->textdomain ), 'align-self' );
						$choices = array(
							'auto' => esc_html__( 'Auto', $this->textdomain ),
							'stretch' => esc_html__( 'Stretch', $this->textdomain ),
							'center' => esc_html__( 'Center', $this->textdomain ),
							'flex-start' => esc_html__( 'Flex-start', $this->textdomain ),
							'flex-end' => esc_html__( 'Flex-end', $this->textdomain ),
							'baseline' => esc_html__( 'Baseline', $this->textdomain ),
							'initial' => esc_html__( 'Initial', $this->textdomain ),
							'inherit' => esc_html__( 'Inherit', $this->textdomain ),
						);
						$this->setting_input_with_choices( $setting_id, $property_data, $label, $description, $choices );
					}
					function setting_flex( $setting_id, $property_data ) {
						$label = esc_html__( 'Flex', $this->textdomain );
						$description = sprintf( esc_html__( 'Select the value of %1$s for the element. ( property : "%2$s" )', $this->textdomain ), esc_html__( 'Flex', $this->textdomain ), 'flex' );
						$choices = array(
							'flex-grow' => esc_html__( 'Flex-grow', $this->textdomain ),
							'flex-shrink' => esc_html__( 'Flex-shrink', $this->textdomain ),
							'flex-basis' => esc_html__( 'Flex-basis', $this->textdomain ),
							'auto' => esc_html__( 'Auto', $this->textdomain ),
							'initial' => esc_html__( 'Initial', $this->textdomain ),
							'none' => esc_html__( 'None', $this->textdomain ),
							'inherit' => esc_html__( 'Inherit', $this->textdomain ),
						);
						$this->setting_input_with_choices( $setting_id, $property_data, $label, $description, $choices );
					}
					function setting_flex_basis( $setting_id, $property_data ) {
						$label = esc_html__( 'Flex Basis', $this->textdomain );
						$description = sprintf( esc_html__( 'Select the value of %1$s for the element. ( property : "%2$s" )', $this->textdomain ), esc_html__( 'Flex Basis', $this->textdomain ), 'flex-basis' );
						$choices = array(
							'initial' => esc_html__( 'Initial', $this->textdomain ),
							'auto' => esc_html__( 'Auto', $this->textdomain ),
							'inherit' => esc_html__( 'Inherit', $this->textdomain ),
						);
						$this->setting_input_with_choices( $setting_id, $property_data, $label, $description, $choices );
					}
					function setting_flex_direction( $setting_id, $property_data ) {
						$label = esc_html__( 'Flex Direction', $this->textdomain );
						$description = sprintf( esc_html__( 'Select the value of %1$s for the element. ( property : "%2$s" )', $this->textdomain ), esc_html__( 'Flex Direction', $this->textdomain ), 'flex-direction' );
						$choices = array(
							'row' => esc_html__( 'Row', $this->textdomain ),
							'row-reverse' => esc_html__( 'Row Reverse', $this->textdomain ),
							'column' => esc_html__( 'Column', $this->textdomain ),
							'column-reverse' => esc_html__( 'Column Reverse', $this->textdomain ),
							'initial' => esc_html__( 'Initial', $this->textdomain ),
							'inherit' => esc_html__( 'Inherit', $this->textdomain ),
						);
						$this->setting_input_with_choices( $setting_id, $property_data, $label, $description, $choices );
					}
					function setting_flex_wrap( $setting_id, $property_data ) {
						$label = esc_html__( 'Flex Wrap', $this->textdomain );
						$description = sprintf( esc_html__( 'Select the value of %1$s for the element. ( property : "%2$s" )', $this->textdomain ), esc_html__( 'Flex Wrap', $this->textdomain ), 'flex-wrap' );
						$choices = array(
							'nowrap' => esc_html__( 'Nowrap', $this->textdomain ),
							'wrap' => esc_html__( 'Wrap', $this->textdomain ),
							'wrap-reverse' => esc_html__( 'Wrap Reverse', $this->textdomain ),
							'initial' => esc_html__( 'Initial', $this->textdomain ),
							'inherit' => esc_html__( 'Inherit', $this->textdomain ),
						);
						$this->setting_input_with_choices( $setting_id, $property_data, $label, $description, $choices );
					}
					function setting_align_flow( $setting_id, $property_data ) {
						$label = esc_html__( 'Align Flex', $this->textdomain );
						$description = sprintf( esc_html__( 'Select the value of %1$s for the element. ( property : "%2$s" )', $this->textdomain ), esc_html__( 'Align Flex', $this->textdomain ), 'align-flex' );
						$choices = array(
							'initial' => esc_html__( 'Initial', $this->textdomain ),
							'row nowrap' => esc_html__( 'Row Nowrap', $this->textdomain ),
							'row-reverse nowrap' => esc_html__( 'Row-Reverse Nowrap', $this->textdomain ),
							'column nowrap' => esc_html__( 'Column Nowrap', $this->textdomain ),
							'column-reverse nowrap' => esc_html__( 'Column-Reverse Nowrap', $this->textdomain ),
							'row wrap' => esc_html__( 'Row wrap', $this->textdomain ),
							'row-reverse wrap' => esc_html__( 'Row-Reverse Wrap', $this->textdomain ),
							'column wrap' => esc_html__( 'Column Wrap', $this->textdomain ),
							'column-reverse wrap' => esc_html__( 'Column-Reverse Wrap', $this->textdomain ),
							'row wrap-reverse' => esc_html__( 'Row Wrap-Reverse', $this->textdomain ),
							'row-reverse wrap-reverse' => esc_html__( 'Row-Reverse Wrap-Reverse', $this->textdomain ),
							'column wrap-reverse' => esc_html__( 'Column Wrap-Reverse', $this->textdomain ),
							'column-reverse wrap-reverse' => esc_html__( 'Column-Reverse Wrap-Reverse', $this->textdomain ),
							'inherit' => esc_html__( 'Inherit', $this->textdomain ),
						);
						$this->setting_input_with_choices( $setting_id, $property_data, $label, $description, $choices );
					}
					function setting_flex_grow( $setting_id, $property_data ) {
						$label = esc_html__( 'Flex Grow', $this->textdomain );
						$description = sprintf( esc_html__( 'Set the value of %1$s for the element. ( property: "%2$s" ) * step value for slidebar : "%3$d"', $this->textdomain ), esc_html__( 'Flex Grow', $this->textdomain ), 'flex-grow', '1' );
						$this->setting_input_range( $setting_id, $property_data, $label, $description, '0', '20', '1', 'flex-grow' );
					}
					function setting_flex_shrink( $setting_id, $property_data ) {
						$label = esc_html__( 'Flex Shrink', $this->textdomain );
						$description = sprintf( esc_html__( 'Set the value of %1$s for the element. ( property: "%2$s" ) * step value for slidebar : "%3$d"', $this->textdomain ), esc_html__( 'Flex Shrink', $this->textdomain ), 'flex-shrink', '1' );
						$this->setting_input_range( $setting_id, $property_data, $label, $description, '0', '20', '1', 'flex-shrink' );
					}
					function setting_justify_content( $setting_id, $property_data ) {
						$label = esc_html__( 'Justfy Content', $this->textdomain );
						$description = sprintf( esc_html__( 'Select the value of %1$s for the element. ( property : "%2$s" )', $this->textdomain ), esc_html__( 'Justfy Content', $this->textdomain ), 'justify-content' );
						$choices = array(
							'flex-start' => esc_html__( 'Flex-Start', $this->textdomain ),
							'flex-end' => esc_html__( 'Flex-End', $this->textdomain ),
							'center' => esc_html__( 'Center', $this->textdomain ),
							'space-between' => esc_html__( 'Space-Between', $this->textdomain ),
							'space-around' => esc_html__( 'Space-Around', $this->textdomain ),
							'initial' => esc_html__( 'Initial', $this->textdomain ),
							'inherit' => esc_html__( 'Inherit', $this->textdomain ),
						);
						$this->setting_input_with_choices( $setting_id, $property_data, $label, $description, $choices );
					}
					function setting_order( $setting_id, $property_data ) {
						$label = esc_html__( 'Order', $this->textdomain );
						$description = sprintf( esc_html__( 'Set the value of %1$s for the element. ( property: "%2$s" ) * step value for slidebar : "%3$d"', $this->textdomain ), esc_html__( 'Order', $this->textdomain ), 'order', '1' );
						$this->setting_input_range( $setting_id, $property_data, $label, $description, '0', '20', '1', 'order' );
					}
				// 'align-content', 'align-items', 'align-self', 'flex', 'flex-basis', 'flex-direction', 'flex-wrap', 'flex-flow', 'flex-grow', 'flex-shrink', 'justify-content', 'order'

				# Text
					function setting_hanging_punctuation( $setting_id, $property_data ) {
						$label = esc_html__( 'Hanging Punctuation', $this->textdomain );
						$description = sprintf( esc_html__( 'Select the value of %1$s for the element. ( property : "%2$s" )', $this->textdomain ), esc_html__( 'Hanging Punctuation', $this->textdomain ), 'hanging-punctuation' );
						$choices = array(
							'initial' => esc_html__( 'Initial', $this->textdomain ),
							'none' => esc_html__( 'None', $this->textdomain ),
							'first' => esc_html__( 'First', $this->textdomain ),
							'last' => esc_html__( 'Last', $this->textdomain ),
							'allow-end' => esc_html__( 'Allow-end', $this->textdomain ),
							'force-end' => esc_html__( 'Force-end', $this->textdomain ),
							'inherit' => esc_html__( 'Inherit', $this->textdomain ),
						);
						$this->setting_input_with_choices( $setting_id, $property_data, $label, $description, $choices );
					}
					function setting_hyphens( $setting_id, $property_data ) {
						$label = esc_html__( 'Hyphens', $this->textdomain );
						$description = sprintf( esc_html__( 'Select the value of %1$s for the element. ( property : "%2$s" )', $this->textdomain ), esc_html__( 'Hyphens', $this->textdomain ), 'hyphens' );
						$choices = array(
							'none' => esc_html__( 'None', $this->textdomain ),
							'manual' => esc_html__( 'Manual', $this->textdomain ),
							'auto' => esc_html__( 'Auto', $this->textdomain ),
							'last' => esc_html__( 'Last', $this->textdomain ),
							'allow-end' => esc_html__( 'Allow-end', $this->textdomain ),
							'force-end' => esc_html__( 'Force-end', $this->textdomain ),
							'inherit' => esc_html__( 'Inherit', $this->textdomain ),
						);
						$this->setting_input_with_choices( $setting_id, $property_data, $label, $description, $choices );
					}
					function setting_letter_spacing( $setting_id, $property_data ) {
						$label = esc_html__( 'Letter Spacing', $this->textdomain );
						$description = sprintf( esc_html__( 'Set the value of %1$s for the element. ( property: "%2$s" ) * step value for slidebar : "%3$d"', $this->textdomain ), esc_html__( 'Letter Spacing', $this->textdomain ), 'letter-spacing', '1' ) . sprintf( esc_html__( ' * Unit for the slidebar is "%s".', $this->textdomain ), 'px' );
						$this->setting_input_range( $setting_id, $property_data, $label, $description, 1, 10, '1', 'letter-spacing' );
					}
					function setting_line_break( $setting_id, $property_data ) {
						$label = esc_html__( 'Line Break', $this->textdomain );
						$description = sprintf( esc_html__( 'Select the value of %1$s for the element. ( property : "%2$s" )', $this->textdomain ), esc_html__( 'Line Break', $this->textdomain ), 'line-break' );
						$choices = array(
							'auto' => esc_html__( 'Auto', $this->textdomain ),
							'loose' => esc_html__( 'Loose', $this->textdomain ),
							'normal' => esc_html__( 'Normal', $this->textdomain ),
							'strict' => esc_html__( 'Strict', $this->textdomain ),
						);
						$this->setting_input_with_choices( $setting_id, $property_data, $label, $description, $choices );
					}
					function setting_line_height( $setting_id, $property_data ) {
						$label = esc_html__( 'Line Height', $this->textdomain );
						$description = sprintf( esc_html__( 'Set the value of %1$s for the element. ( property: "%2$s" ) * step value for slidebar : "%3$d"', $this->textdomain ), esc_html__( 'Line Height', $this->textdomain ), 'line-height', '0.1' );
						$this->setting_input_range( $setting_id, $property_data, $label, $description, 1, 10, '0.1', 'line-height' );
					}
					function setting_overflow_wrap( $setting_id, $property_data ) {
						$label = esc_html__( 'Overflow Wrap', $this->textdomain );
						$description = sprintf( esc_html__( 'Select the value of %1$s for the element. ( property : "%2$s" )', $this->textdomain ), esc_html__( 'Overflow Wrap', $this->textdomain ), 'overflow-wrap' );
						$choices = array(
							'initial' => esc_html__( 'Initial', $this->textdomain ),
							'normal' => esc_html__( 'Normal', $this->textdomain ),
							'break-word' => esc_html__( 'Break-word', $this->textdomain ),
							'inherit' => esc_html__( 'Inherit', $this->textdomain ),
						);
						$this->setting_input_with_choices( $setting_id, $property_data, $label, $description, $choices );
					}
					function setting_tab_size( $setting_id, $property_data ) {
						$label = esc_html__( 'Tab Size', $this->textdomain );
						$description = sprintf( esc_html__( 'Set the value of %1$s for the element. ( property: "%2$s" ) * step value for slidebar : "%3$d"', $this->textdomain ), esc_html__( 'Tab Size', $this->textdomain ), 'tab-size', '1' );
						$this->setting_input_range( $setting_id, $property_data, $label, $description, 2, 16, '1', 'tab-size' );
					}
					function setting_text_align( $setting_id, $property_data ) {
						$label = esc_html__( 'Text Align', $this->textdomain );
						$description = sprintf( esc_html__( 'Select the value of %1$s for the element. ( property : "%2$s" )', $this->textdomain ), esc_html__( 'Text Align', $this->textdomain ), 'text-align' );
						$choices = array(
							'initial' => esc_html__( 'Initial', $this->textdomain ),
							'left' => esc_html__( 'Left', $this->textdomain ),
							'right' => esc_html__( 'Right', $this->textdomain ),
							'center' => esc_html__( 'Center', $this->textdomain ),
							'justify' => esc_html__( 'Justify', $this->textdomain ),
							'inherit' => esc_html__( 'Inherit', $this->textdomain ),
						);
						$this->setting_input_with_choices( $setting_id, $property_data, $label, $description, $choices );
					}
					function setting_text_align_last( $setting_id, $property_data ) {
						$label = esc_html__( 'Text Align Last', $this->textdomain );
						$description = sprintf( esc_html__( 'Select the value of %1$s for the element. ( property : "%2$s" )', $this->textdomain ), esc_html__( 'Text Align Last', $this->textdomain ), 'text-align-last' );
						$choices = array(
							'initial' => esc_html__( 'Initial', $this->textdomain ),
							'auto' => esc_html__( 'Auto', $this->textdomain ),
							'left' => esc_html__( 'Left', $this->textdomain ),
							'right' => esc_html__( 'Right', $this->textdomain ),
							'center' => esc_html__( 'Center', $this->textdomain ),
							'justify' => esc_html__( 'Justify', $this->textdomain ),
							'start' => esc_html__( 'Start', $this->textdomain ),
							'end' => esc_html__( 'End', $this->textdomain ),
							'inherit' => esc_html__( 'Inherit', $this->textdomain ),
						);
						$this->setting_input_with_choices( $setting_id, $property_data, $label, $description, $choices );
					}
					function setting_text_combine_upright( $setting_id, $property_data ) {
						$label = esc_html__( 'Text Combine Upright', $this->textdomain );
						$description = sprintf( esc_html__( 'Select the value of %1$s for the element. ( property : "%2$s" )', $this->textdomain ), esc_html__( 'Text Combine Upright', $this->textdomain ), 'text-combine-upright' ) . esc_html__( 'You can specify with "digits num" in text field', $this->textdomain );
						$choices = array(
							'initial' => esc_html__( 'Initial', $this->textdomain ),
							'none' => esc_html__( 'None', $this->textdomain ),
							'all' => esc_html__( 'All', $this->textdomain ),
							'inherit' => esc_html__( 'Inherit', $this->textdomain ),
						);
						$this->setting_input_with_choices( $setting_id, $property_data, $label, $description, $choices );
					}
					function setting_text_indent( $setting_id, $property_data ) {
						$label = esc_html__( 'Text Indent', $this->textdomain );
						$description = sprintf( esc_html__( 'Set the value of %1$s for the element. ( property: "%2$s" ) * step value for slidebar : "%3$d"', $this->textdomain ), esc_html__( 'Text Indent', $this->textdomain ), 'text-indent', '1' );
						$this->setting_input_range( $setting_id, $property_data, $label, $description, 2, 16, '1', 'text-indent' );
					}
					function setting_text_justify( $setting_id, $property_data ) {
						$label = esc_html__( 'Text Justify', $this->textdomain );
						$description = sprintf( esc_html__( 'Select the value of %1$s for the element. ( property : "%2$s" )', $this->textdomain ), esc_html__( 'Text Justify', $this->textdomain ), 'text-justify' );
						$choices = array(
							'initial' => esc_html__( 'Initial', $this->textdomain ),
							'auto' => esc_html__( 'Auto', $this->textdomain ),
							'inter-word' => esc_html__( 'Inter-Word', $this->textdomain ),
							'inter-ideograph' => esc_html__( 'Inter-Ideograph', $this->textdomain ),
							'inter-cluster' => esc_html__( 'Inter-Cluster', $this->textdomain ),
							'distribute' => esc_html__( 'Distribute', $this->textdomain ),
							'kashida' => esc_html__( 'Kashida', $this->textdomain ),
							'trim' => esc_html__( 'Trim', $this->textdomain ),
							'none' => esc_html__( 'None', $this->textdomain ),
							'inherit' => esc_html__( 'Inherit', $this->textdomain ),
						);
						$this->setting_input_with_choices( $setting_id, $property_data, $label, $description, $choices );
					}
					function setting_text_transform( $setting_id, $property_data ) {
						$label = esc_html__( 'Text Transform', $this->textdomain );
						$description = sprintf( esc_html__( 'Select the value of %1$s for the element. ( property : "%2$s" )', $this->textdomain ), esc_html__( 'Text Transform', $this->textdomain ), 'text-transform' );
						$choices = array(
							'initial' => esc_html__( 'Initial', $this->textdomain ),
							'none' => esc_html__( 'None', $this->textdomain ),
							'capitalize' => esc_html__( 'Capitalize', $this->textdomain ),
							'uppercase' => esc_html__( 'Uppercase', $this->textdomain ),
							'lowercase' => esc_html__( 'Lowercase', $this->textdomain ),
							'inherit' => esc_html__( 'Inherit', $this->textdomain ),
						);
						$this->setting_input_with_choices( $setting_id, $property_data, $label, $description, $choices );
					}
					function setting_white_space( $setting_id, $property_data ) {
						$label = esc_html__( 'White Space', $this->textdomain );
						$description = sprintf( esc_html__( 'Select the value of %1$s for the element. ( property : "%2$s" )', $this->textdomain ), esc_html__( 'White Space', $this->textdomain ), 'white-space' );
						$choices = array(
							'initial' => esc_html__( 'Initial', $this->textdomain ),
							'normal' => esc_html__( 'Normal', $this->textdomain ),
							'nowrap' => esc_html__( 'Nowrap', $this->textdomain ),
							'pre' => esc_html__( 'Pre', $this->textdomain ),
							'pre-line' => esc_html__( 'Pre-Line', $this->textdomain ),
							'pre-wrap' => esc_html__( 'Pre-Wrap', $this->textdomain ),
							'inherit' => esc_html__( 'Inherit', $this->textdomain ),
						);
						$this->setting_input_with_choices( $setting_id, $property_data, $label, $description, $choices );
					}
					function setting_word_break( $setting_id, $property_data ) {
						$label = esc_html__( 'Word Break', $this->textdomain );
						$description = sprintf( esc_html__( 'Select the value of %1$s for the element. ( property : "%2$s" )', $this->textdomain ), esc_html__( 'Word Break', $this->textdomain ), 'word-break' );
						$choices = array(
							'initial' => esc_html__( 'Initial', $this->textdomain ),
							'normal' => esc_html__( 'Normal', $this->textdomain ),
							'break-all' => esc_html__( 'Break-all', $this->textdomain ),
							'keep-all' => esc_html__( 'Keep-all', $this->textdomain ),
							'inherit' => esc_html__( 'Inherit', $this->textdomain ),
						);
						$this->setting_input_with_choices( $setting_id, $property_data, $label, $description, $choices );
					}
					function setting_word_spacing( $setting_id, $property_data ) {
						$label = esc_html__( 'Word Spacing', $this->textdomain );
						$description = sprintf( esc_html__( 'Set the value of %1$s for the element. ( property: "%2$s" ) * step value for slidebar : "%3$d"', $this->textdomain ), esc_html__( 'Word Spacing', $this->textdomain ), 'word-spacing', '1' );
						$this->setting_input_range( $setting_id, $property_data, $label, $description, 1, 50, '1', 'word-spacing' );
					}
					function setting_word_wrap( $setting_id, $property_data ) {
						$label = esc_html__( 'Word Wrap', $this->textdomain );
						$description = sprintf( esc_html__( 'Select the value of %1$s for the element. ( property : "%2$s" )', $this->textdomain ), esc_html__( 'Word Wrap', $this->textdomain ), 'word-wrap' );
						$choices = array(
							'initial' => esc_html__( 'Initial', $this->textdomain ),
							'normal' => esc_html__( 'Normal', $this->textdomain ),
							'break-word' => esc_html__( 'Break-Word', $this->textdomain ),
							'inherit' => esc_html__( 'Inherit', $this->textdomain ),
						);
						$this->setting_input_with_choices( $setting_id, $property_data, $label, $description, $choices );
					}
				// 'hanging-punctuation', 'hyphens', 'letter-spacing', 'line-break', 'line-height', 'overflow-wrap', 'tab-size', 'text-align', 'text-align-last', 'text-combine-upright', 'text-indent', 'text-justify', 'text-transform', 'white-space', 'word-break', 'word-spacing', 'word-wrap'

				# Text Decoration
					function setting_text_decoration( $setting_id, $property_data ) {
						$label = esc_html__( 'Text Decoration', $this->textdomain );
						$description = sprintf( esc_html__( 'Select the value of %1$s for the element. ( property : "%2$s" )', $this->textdomain ), esc_html__( 'Text Decoration', $this->textdomain ), 'text-decoration' );
						$choices = array(
							'initial' => esc_html__( 'Initial', $this->textdomain ),
							'none' => esc_html__( 'None', $this->textdomain ),
							'underline' => esc_html__( 'Underline', $this->textdomain ),
							'overline' => esc_html__( 'Overline', $this->textdomain ),
							'line-through' => esc_html__( 'Line-through', $this->textdomain ),
							'inherit' => esc_html__( 'Inherit', $this->textdomain ),
						);
						$this->setting_input_with_choices( $setting_id, $property_data, $label, $description, $choices );
					}
					function setting_text_decoration_color( $setting_id, $property_data ) {
						$label = esc_html__( 'Text Decoration Color', $this->textdomain );
						$description = sprintf( esc_html__( 'Pick a Color of %1$s for the element. ( property: "%2$s" )', $this->textdomain ), esc_html__( 'Text Decoration', $this->textdomain ), 'text-decoration-color' );
						$this->setting_color_picker( $setting_id, $property_data, $label, $description );
					}
					function setting_text_decoration_line( $setting_id, $property_data ) {
						$label = esc_html__( 'Text Decoration Line', $this->textdomain );
						$description = sprintf( esc_html__( 'Select the value of %1$s for the element. ( property : "%2$s" )', $this->textdomain ), esc_html__( 'Text Decoration Line', $this->textdomain ), 'text-decoration-line' );
						$choices = array(
							'initial' => esc_html__( 'Initial', $this->textdomain ),
							'none' => esc_html__( 'None', $this->textdomain ),
							'underline' => esc_html__( 'Underline', $this->textdomain ),
							'overline' => esc_html__( 'Overline', $this->textdomain ),
							'line-through' => esc_html__( 'Line-through', $this->textdomain ),
							'inherit' => esc_html__( 'Inherit', $this->textdomain ),
						);
						$this->setting_input_with_choices( $setting_id, $property_data, $label, $description, $choices );
					}
					function setting_text_decoration_style( $setting_id, $property_data ) {
						$label = esc_html__( 'Text Decoration Style', $this->textdomain );
						$description = sprintf( esc_html__( 'Select the value of %1$s for the element. ( property : "%2$s" )', $this->textdomain ), esc_html__( 'Text Decoration Style', $this->textdomain ), 'text-decoration-style' );
						$choices = array(
							'initial' => esc_html__( 'Initial', $this->textdomain ),
							'solid' => esc_html__( 'Solid', $this->textdomain ),
							'double' => esc_html__( 'Double', $this->textdomain ),
							'dotted' => esc_html__( 'Dotted', $this->textdomain ),
							'dashed' => esc_html__( 'Dashed', $this->textdomain ),
							'wavy' => esc_html__( 'Wavy', $this->textdomain ),
							'inherit' => esc_html__( 'Inherit', $this->textdomain ),
						);
						$this->setting_input_with_choices( $setting_id, $property_data, $label, $description, $choices );
					}
					function setting_text_shadow( $setting_id, $property_data ) {
						$label = esc_html__( 'Text Shadow', $this->textdomain );
						$description = sprintf( esc_html__( 'Select the value of %1$s for the element. ( property : "%2$s" )', $this->textdomain ), esc_html__( 'Text Shadow', $this->textdomain ), 'text-shadow' ) . esc_html__( 'For text field, you can specify each value with the form of "horizontal vertical blur"', $this->textdomain );
						$choices = array(
							'initial' => esc_html__( 'Initial', $this->textdomain ),
							'none' => esc_html__( 'None', $this->textdomain ),
							'inherit' => esc_html__( 'Inherit', $this->textdomain ),
						);
						$this->setting_input_with_choices( $setting_id, $property_data, $label, $description, $choices );
					}
					function setting_text_underline_position( $setting_id, $property_data ) {
						$label = esc_html__( 'Text Underline Position', $this->textdomain );
						$description = sprintf( esc_html__( 'Select the value of %1$s for the element. ( property : "%2$s" )', $this->textdomain ), 'Text Underline Position', 'text-underline-position' );
						$choices = array(
							'initial' => esc_html__( 'Initial', $this->textdomain ),
							'auto' => esc_html__( 'Auto', $this->textdomain ),
							'under' => esc_html__( 'Under', $this->textdomain ),
							'left' => esc_html__( 'Left', $this->textdomain ),
							'right' => esc_html__( 'Right', $this->textdomain ),
							'inherit' => esc_html__( 'Inherit', $this->textdomain ),
						);
						$this->setting_input_with_choices( $setting_id, $property_data, $label, $description, $choices );
					}
				// 'text-decoration', 'text-decoration-color', 'text-decoration-line', 'text-decoration-style', 'text-shadow', 'text-underline-position'

				# Font
					function setting_font( $setting_id, $property_data ) {
						$label = esc_html__( 'Font', $this->textdomain );
						$description = sprintf( esc_html__( 'Enter the value of CSS property "%s" for the element', $this->textdomain ), 'font' );
						$this->setting_input_text( $setting_id, $property_data, $label, $description );
					}
					function setting_font_family( $setting_id, $property_data ) {
						$label = esc_html__( 'Font Family', $this->textdomain );
						$description = sprintf( esc_html__( 'Select the value of %1$s for the element. ( property : "%2$s" )', $this->textdomain ), esc_html__( 'Font Family', $this->textdomain ), 'font-family' );
						$choices = array(
							'initial' => esc_html__( 'Initial', $this->textdomain ),
							'Georgia' => esc_html__( 'Georgia', $this->textdomain ),
							'Palatino Linotype' => esc_html__( 'Palatino Linotype', $this->textdomain ),
							'Book Antiqua' => esc_html__( 'Book Antiqua', $this->textdomain ),
							'Times New Roman' => esc_html__( 'Times New Roman', $this->textdomain ),
							'Arial' => esc_html__( 'Arial', $this->textdomain ),
							'Helvetica' => esc_html__( 'Helvetica', $this->textdomain ),
							'Arial Black' => esc_html__( 'Arial Black', $this->textdomain ),
							'Impact' => esc_html__( 'Impact', $this->textdomain ),
							'Lucida Sans Unicode' => esc_html__( 'Lucida Sans Unicode', $this->textdomain ),
							'Tahoma' => esc_html__( 'Tahoma', $this->textdomain ),
							'Verdana' => esc_html__( 'Verdana', $this->textdomain ),
							'Courier New' => esc_html__( 'Courier New', $this->textdomain ),
							'Lucida Console' => esc_html__( 'Lucida Console', $this->textdomain ),
							'inherit' => esc_html__( 'Inherit', $this->textdomain ),
						);
						$this->setting_input_with_choices( $setting_id, $property_data, $label, $description, $choices );
					}
					function setting_font_feature_settings( $setting_id, $property_data ) {
						$label = esc_html__( 'Font Feature Settings', $this->textdomain );
						$description = sprintf( esc_html__( 'Enter the value of CSS property "%s" for the element', $this->textdomain ), 'font-feature-settings' );
						$this->setting_input_text( $setting_id, $property_data, $label, $description );
					}
					function setting_font_kerning( $setting_id, $property_data ) {
						$label = esc_html__( 'Font Kerning', $this->textdomain );
						$description = sprintf( esc_html__( 'Select the value of %1$s for the element. ( property : "%2$s" )', $this->textdomain ), esc_html__( 'Font Kerning', $this->textdomain ), 'font-kerning' );
						$choices = array(
							'initial' => esc_html__( 'Initial', $this->textdomain ),
							'auto' => esc_html__( 'Auto', $this->textdomain ),
							'normal' => esc_html__( 'Normal', $this->textdomain ),
							'none' => esc_html__( 'None', $this->textdomain ),
							'inherit' => esc_html__( 'Inherit', $this->textdomain ),
						);
						$this->setting_input_with_choices( $setting_id, $property_data, $label, $description, $choices );
					}
					function setting_font_language_override( $setting_id, $property_data ) {
						$label = esc_html__( 'Font Language Override', $this->textdomain );
						$description = sprintf( esc_html__( 'Select the value of %1$s for the element. ( property : "%2$s" )', $this->textdomain ), esc_html__( 'Font Language Override', $this->textdomain ), 'font-language-override' );
						$choices = array(
							'initial' => esc_html__( 'Initial', $this->textdomain ),
							'unset' => esc_html__( 'Unset', $this->textdomain ),
							'normal' => esc_html__( 'Normal', $this->textdomain ),
							'inherit' => esc_html__( 'Inherit', $this->textdomain ),
						);
						$this->setting_input_with_choices( $setting_id, $property_data, $label, $description, $choices );
					}
					function setting_font_size( $setting_id, $property_data ) {
						$label = esc_html__( 'Font Size', $this->textdomain );
						$description = sprintf( esc_html__( 'Select the value of %1$s for the element. ( property : "%2$s" )', $this->textdomain ), esc_html__( 'Font Size', $this->textdomain ), 'font-size' );
						$choices = array(
							'initial' => esc_html__( 'Initial', $this->textdomain ),
							'xx-small' => esc_html__( 'XX-Small', $this->textdomain ),
							'x-small' => esc_html__( 'X-Small', $this->textdomain ),
							'small' => esc_html__( 'Small', $this->textdomain ),
							'medium' => esc_html__( 'Medium', $this->textdomain ),
							'large' => esc_html__( 'Large', $this->textdomain ),
							'x-large' => esc_html__( 'X-Large', $this->textdomain ),
							'xx-large' => esc_html__( 'XX-Large', $this->textdomain ),
							'smaller' => esc_html__( 'Smaller', $this->textdomain ),
							'inherit' => esc_html__( 'Inherit', $this->textdomain ),
						);
						$this->setting_input_with_choices( $setting_id, $property_data, $label, $description, $choices );
					}
					function setting_font_size_adjust( $setting_id, $property_data ) {
						$label = esc_html__( 'Font Size Adjust', $this->textdomain );
						$description = sprintf( esc_html__( 'Set the value of %1$s for the element. ( property: "%2$s" ) * step value for slidebar : "%3$d"', $this->textdomain ), esc_html__( 'Font Size Adjust', $this->textdomain ), 'font-size-adjust', '0.01' ) . sprintf( esc_html__( ' * Unit for the slidebar is "%s".', $this->textdomain ), 'px' );
						$this->setting_input_range( $setting_id, $property_data, $label, $description, '0.01', '10', '0.01', 'font-size-adjust' );
					}
					function setting_font_stretch( $setting_id, $property_data ) {
						$label = esc_html__( 'Font Stretch', $this->textdomain );
						$description = sprintf( esc_html__( 'Select the value of %1$s for the element. ( property : "%2$s" )', $this->textdomain ), esc_html__( 'Font Stretch', $this->textdomain ), 'font-stretch' );
						$choices = array(
							'initial' => esc_html__( 'Initial', $this->textdomain ),
							'ultra-condensed' => esc_html__( 'Ultra-Condensed', $this->textdomain ),
							'extra-condensed' => esc_html__( 'Extra-Condensed', $this->textdomain ),
							'condensed' => esc_html__( 'Condensed', $this->textdomain ),
							'semi-condensed' => esc_html__( 'Semi-Condensed', $this->textdomain ),
							'normal' => esc_html__( 'Normal', $this->textdomain ),
							'semi-expanded' => esc_html__( 'Semi-Expanded', $this->textdomain ),
							'expanded' => esc_html__( 'Expanded', $this->textdomain ),
							'extra-expanded' => esc_html__( 'Extra-Expanded', $this->textdomain ),
							'ultra-expanded' => esc_html__( 'Ultra-Expanded', $this->textdomain ),
							'inherit' => esc_html__( 'Inherit', $this->textdomain ),
						);
						$this->setting_input_with_choices( $setting_id, $property_data, $label, $description, $choices );
					}
					function setting_font_style( $setting_id, $property_data ) {
						$label = esc_html__( 'Font Style', $this->textdomain );
						$description = sprintf( esc_html__( 'Select the value of %1$s for the element. ( property : "%2$s" )', $this->textdomain ), esc_html__( 'Font Style', $this->textdomain ), 'font-style' );
						$choices = array(
							'initial' => esc_html__( 'Initial', $this->textdomain ),
							'normal' => esc_html__( 'Normal', $this->textdomain ),
							'italic' => esc_html__( 'Italic', $this->textdomain ),
							'oblique' => esc_html__( 'Oblique', $this->textdomain ),
							'inherit' => esc_html__( 'Inherit', $this->textdomain ),
						);
						$this->setting_input_with_choices( $setting_id, $property_data, $label, $description, $choices );
					}
					function setting_font_synthesis( $setting_id, $property_data ) {
						$label = esc_html__( 'Font Synthesis', $this->textdomain );
						$description = sprintf( esc_html__( 'Select the value of %1$s for the element. ( property : "%2$s" )', $this->textdomain ), esc_html__( 'Font Synthesis', $this->textdomain ), 'font-synthesis' );
						$choices = array(
							'initial' => esc_html__( 'Initial', $this->textdomain ),
							'none' => esc_html__( 'None', $this->textdomain ),
							'weight' => esc_html__( 'Weight', $this->textdomain ),
							'style' => esc_html__( 'Style', $this->textdomain ),
							'inherit' => esc_html__( 'Inherit', $this->textdomain ),
						);
						$this->setting_input_with_choices( $setting_id, $property_data, $label, $description, $choices );
					}
					function setting_font_variant( $setting_id, $property_data ) {
						$label = esc_html__( 'Font Variant', $this->textdomain );
						$description = sprintf( esc_html__( 'Select the value of %1$s for the element. ( property : "%2$s" )', $this->textdomain ), esc_html__( 'Font Variant', $this->textdomain ), 'font-variant' );
						$choices = array(
							'initial' => esc_html__( 'Initial', $this->textdomain ),
							'normal' => esc_html__( 'Normal', $this->textdomain ),
							'small-caps' => esc_html__( 'Small-Caps', $this->textdomain ),
							'inherit' => esc_html__( 'Inherit', $this->textdomain ),
						);
						$this->setting_input_with_choices( $setting_id, $property_data, $label, $description, $choices );
					}
					function setting_font_variant_alternates( $setting_id, $property_data ) {
						$label = esc_html__( 'Font Variant Alternates', $this->textdomain );
						$description = sprintf( esc_html__( 'Enter the value of CSS property "%s" for the element', $this->textdomain ), 'font-variant-alternates' ) . ' * Required to define "@font-feature-values"';
						$this->setting_input_text( $setting_id, $property_data, $label, $description );
					}
					function setting_font_variant_caps( $setting_id, $property_data ) {
						$label = esc_html__( 'Font Variant Caps', $this->textdomain );
						$description = sprintf( esc_html__( 'Select the value of %1$s for the element. ( property : "%2$s" )', $this->textdomain ), esc_html__( 'Font Variant Caps', $this->textdomain ), 'font-variant-caps' );
						$choices = array(
							'initial' => esc_html__( 'Initial', $this->textdomain ),
							'normal' => esc_html__( 'Normal', $this->textdomain ),
							'small-caps' => esc_html__( 'Small-Caps', $this->textdomain ),
							'all-small-caps' => esc_html__( 'All-Small-Caps', $this->textdomain ),
							'petite-caps' => esc_html__( 'Petite-Caps', $this->textdomain ),
							'all-petite-caps' => esc_html__( 'All-Petite-Caps', $this->textdomain ),
							'unicase' => esc_html__( 'Unicase', $this->textdomain ),
							'titling-caps' => esc_html__( 'Titling-Caps', $this->textdomain ),
							'inherit' => esc_html__( 'Inherit', $this->textdomain ),
						);
						$this->setting_input_with_choices( $setting_id, $property_data, $label, $description, $choices );
					}
					function setting_font_variant_east_asian( $setting_id, $property_data ) {
						$label = esc_html__( 'Font Variant East Asian', $this->textdomain );
						$description = sprintf( esc_html__( 'Select the value of %1$s for the element. ( property : "%2$s" )', $this->textdomain ), esc_html__( 'Font Variant East Asian', $this->textdomain ), 'font-variant-east-asian' );
						$choices = array(
							'initial' => esc_html__( 'Initial', $this->textdomain ),
							'normal' => esc_html__( 'Normal', $this->textdomain ),
							'ruby' => esc_html__( 'Ruby', $this->textdomain ),
							'jis78' => esc_html__( 'JIS78', $this->textdomain ),
							'jis83' => esc_html__( 'JIS83', $this->textdomain ),
							'jis90' => esc_html__( 'JIS90', $this->textdomain ),
							'jis04' => esc_html__( 'JIS04', $this->textdomain ),
							'simplified' => esc_html__( 'Simplified', $this->textdomain ),
							'traditional' => esc_html__( 'Traditional', $this->textdomain ),
							'full-width' => esc_html__( 'Full-Width', $this->textdomain ),
							'proportional-width' => esc_html__( 'Proportional-Width', $this->textdomain ),
							'inherit' => esc_html__( 'Inherit', $this->textdomain ),
						);
						$this->setting_input_with_choices( $setting_id, $property_data, $label, $description, $choices );
					}
					function setting_font_variant_ligatures( $setting_id, $property_data ) {
						$label = esc_html__( 'Font Variant Ligatures', $this->textdomain );
						$description = sprintf( esc_html__( 'Select the value of %1$s for the element. ( property : "%2$s" )', $this->textdomain ), esc_html__( 'Font Variant Ligatures', $this->textdomain ), 'font-variant-ligatures' );
						$choices = array(
							'initial' => esc_html__( 'Initial', $this->textdomain ),
							'normal' => esc_html__( 'Normal', $this->textdomain ),
							'none' => esc_html__( 'None', $this->textdomain ),
							'common-ligatures' => esc_html__( 'Common-Ligatures', $this->textdomain ),
							'no-common-ligatures' => esc_html__( 'No-Common-Ligatures', $this->textdomain ),
							'discretionary-ligatures' => esc_html__( 'Discretionary-Ligatures', $this->textdomain ),
							'no-discretionary-ligatures' => esc_html__( 'No-Discretionary-Ligatures', $this->textdomain ),
							'historical-ligatures' => esc_html__( 'Historical-Ligatures', $this->textdomain ),
							'no-historical-ligatures' => esc_html__( 'No-Historical-Ligatures', $this->textdomain ),
							'contextual' => esc_html__( 'Contextual', $this->textdomain ),
							'no-contextual' => esc_html__( 'No-Contextual', $this->textdomain ),
							'inherit' => esc_html__( 'Inherit', $this->textdomain ),
						);
						$this->setting_input_with_choices( $setting_id, $property_data, $label, $description, $choices );
					}
					function setting_font_variant_numeric( $setting_id, $property_data ) {
						$label = esc_html__( 'Font Variant Numeric', $this->textdomain );
						$description = sprintf( esc_html__( 'Select the value of %1$s for the element. ( property : "%2$s" )', $this->textdomain ), esc_html__( 'Font Variant Numeric', $this->textdomain ), 'font-variant-numeric' );
						$choices = array(
							'initial' => esc_html__( 'Initial', $this->textdomain ),
							'normal' => esc_html__( 'Normal', $this->textdomain ),
							'lining-nums' => esc_html__( 'Lining-Nums', $this->textdomain ),
							'oldstyle-nums' => esc_html__( 'Oldstyle-Nums', $this->textdomain ),
							'proportional-nums' => esc_html__( 'Proportional-Nums', $this->textdomain ),
							'tabular-nums' => esc_html__( 'Tabular-Nums', $this->textdomain ),
							'diagonal-fractions' => esc_html__( 'Diagonal-Fractions', $this->textdomain ),
							'stacked-fractions' => esc_html__( 'Stacked-Fractions', $this->textdomain ),
							'ordinal' => esc_html__( 'Ordinal', $this->textdomain ),
							'slashed-zero' => esc_html__( 'Slashed-Zero', $this->textdomain ),
							'inherit' => esc_html__( 'Inherit', $this->textdomain ),
						);
						$this->setting_input_with_choices( $setting_id, $property_data, $label, $description, $choices );
					}
					function setting_font_variant_position( $setting_id, $property_data ) {
						$label = esc_html__( 'Font Variant Position', $this->textdomain );
						$description = sprintf( esc_html__( 'Select the value of %1$s for the element. ( property : "%2$s" )', $this->textdomain ), esc_html__( 'Font Variant Position', $this->textdomain ), 'font-variant-position' );
						$choices = array(
							'initial' => esc_html__( 'Initial', $this->textdomain ),
							'normal' => esc_html__( 'Normal', $this->textdomain ),
							'sub' => esc_html__( 'Sub', $this->textdomain ),
							'super' => esc_html__( 'Super', $this->textdomain ),
							'inherit' => esc_html__( 'Inherit', $this->textdomain ),
						);
						$this->setting_input_with_choices( $setting_id, $property_data, $label, $description, $choices );
					}
					function setting_font_weight( $setting_id, $property_data ) {
						$label = esc_html__( 'Font Weight', $this->textdomain );
						$description = sprintf( esc_html__( 'Select the value of %1$s for the element. ( property : "%2$s" )', $this->textdomain ), esc_html__( 'Font Weight', $this->textdomain ), 'font-weight' );
						$choices = array(
							'initial' => esc_html__( 'Initial', $this->textdomain ),
							'normal' => esc_html__( 'Normal', $this->textdomain ),
							'bold' => esc_html__( 'Bold', $this->textdomain ),
							'lighter' => esc_html__( 'Lighter', $this->textdomain ),
							'bolder' => esc_html__( 'Bolder', $this->textdomain ),
							'100' => '100',
							'200' => '200',
							'300' => '300',
							'400' => '400',
							'500' => '500',
							'600' => '600',
							'700' => '700',
							'800' => '800',
							'900' => '900',
							'inherit' => esc_html__( 'Inherit', $this->textdomain ),
						);
						$this->setting_input_with_choices( $setting_id, $property_data, $label, $description, $choices );
					}
				// 'font', 'font-family', 'font-feature-settings', 'font-kerning', 'font-language-override', 'font-size', 'font-size-adjust', 'font-stretch', 'font-style', 'font-synthesis', 'font-variant', 'font-variant-alternates', 'font-variant-caps', 'font-variant-east-asian', 'font-variant-ligatures', 'font-variant-numeric', 'font-variant-position', 'font-weight'

				# Writing Mode
					function setting_direction( $setting_id, $property_data ) {
						$label = esc_html__( 'Direction', $this->textdomain );
						$description = sprintf( esc_html__( 'Select the value of %1$s for the element. ( property : "%2$s" )', $this->textdomain ), esc_html__( 'Direction', $this->textdomain ), 'direction' );
						$choices = array(
							'initial' => esc_html__( 'Initial', $this->textdomain ),
							'ltr' => esc_html__( 'Left to Right', $this->textdomain ),
							'rtl' => esc_html__( 'Right to Left', $this->textdomain ),
							'inherit' => esc_html__( 'Inherit', $this->textdomain ),
						);
						$this->setting_input_with_choices( $setting_id, $property_data, $label, $description, $choices );
					}
					function setting_text_orientation( $setting_id, $property_data ) {
						$label = esc_html__( 'Text Orientation', $this->textdomain );
						$description = sprintf( esc_html__( 'Select the value of %1$s for the element. ( property : "%2$s" )', $this->textdomain ), esc_html__( 'Text Orientation', $this->textdomain ), 'text-orientation' );
						$choices = array(
							'initial' => esc_html__( 'Initial', $this->textdomain ),
							'mixed' => esc_html__( 'Mixed', $this->textdomain ),
							'upright' => esc_html__( 'Upright', $this->textdomain ),
							'sideways' => esc_html__( 'Sideways', $this->textdomain ),
							'sideways-right' => esc_html__( 'sideways-Right', $this->textdomain ),
							'use-glyph-orientation' => esc_html__( 'Use-Glyph-Orientation', $this->textdomain ),
							'inherit' => esc_html__( 'Inherit', $this->textdomain ),
						);
						$this->setting_input_with_choices( $setting_id, $property_data, $label, $description, $choices );
					}
					function setting_unicode_bidi( $setting_id, $property_data ) {
						$label = esc_html__( 'Unicode Bidi', $this->textdomain );
						$description = sprintf( esc_html__( 'Select the value of %1$s for the element. ( property : "%2$s" )', $this->textdomain ), esc_html__( 'Unicode Bidi', $this->textdomain ), 'unicode-bidi' );
						$choices = array(
							'initial' => esc_html__( 'Initial', $this->textdomain ),
							'embed' => esc_html__( 'Embed', $this->textdomain ),
							'bidi-override' => esc_html__( 'BidiOoverride', $this->textdomain ),
							'isolate' => esc_html__( 'Isolate', $this->textdomain ),
							'isolate-override' => esc_html__( 'Isolate-Override', $this->textdomain ),
							'plaintext' => esc_html__( 'Plaintext', $this->textdomain ),
							'inherit' => esc_html__( 'Inherit', $this->textdomain ),
						);
						$this->setting_input_with_choices( $setting_id, $property_data, $label, $description, $choices );
					}
					function setting_writing_mode( $setting_id, $property_data ) {
						$label = esc_html__( 'Writing Mode', $this->textdomain );
						$description = sprintf( esc_html__( 'Select the value of %1$s for the element. ( property : "%2$s" )', $this->textdomain ), esc_html__( 'Writing Mode', $this->textdomain ), 'writing-mode' );
						$choices = array(
							'initial' => esc_html__( 'Initial', $this->textdomain ),
							'horizontal-tb' => esc_html__( 'Horizontal-TB', $this->textdomain ),
							'vertical-rl' => esc_html__( 'Vertical-RL', $this->textdomain ),
							'vertical-lr' => esc_html__( 'Vertical-LR', $this->textdomain ),
							'sideways-rl' => esc_html__( 'Sideways-RL', $this->textdomain ),
							'sideways-lr' => esc_html__( 'Sideways-LR', $this->textdomain ),
							'inherit' => esc_html__( 'Inherit', $this->textdomain ),
						);
						$this->setting_input_with_choices( $setting_id, $property_data, $label, $description, $choices );
					}
				// 'dirction', 'text-orientation', 'unicode-bidi', 'writing-mode'

				# Table
					function setting_border_collapse( $setting_id, $property_data ) {
						$label = esc_html__( 'Border Collapse', $this->textdomain );
						$description = sprintf( esc_html__( 'Select the value of %1$s for the element. ( property : "%2$s" )', $this->textdomain ), esc_html__( 'Border Collapse', $this->textdomain ), 'border-collapse' );
						$choices = array(
							'initial' => esc_html__( 'Initial', $this->textdomain ),
							'separate' => esc_html__( 'Separate', $this->textdomain ),
							'collapse' => esc_html__( 'Collapse', $this->textdomain ),
							'inherit' => esc_html__( 'Inherit', $this->textdomain ),
						);
						$this->setting_input_with_choices( $setting_id, $property_data, $label, $description, $choices );
					}
					function setting_border_spacing( $setting_id, $property_data ) {
						$label = esc_html__( 'Border Spacing', $this->textdomain ) . esc_html__( ' ( For "separate" )', $this->textdomain );
						$description = sprintf( esc_html__( 'Set the value of %1$s for the element. ( property: "%2$s" ) * step value for slidebar : "%3$d"', $this->textdomain ), esc_html__( 'Border Spacing', $this->textdomain ), 'border-spacing', '1' ) . sprintf( esc_html__( ' * Unit for the slidebar is "%s".', $this->textdomain ), 'px' );
						$this->setting_input_range( $setting_id, $property_data, $label, $description, 0, 100, '1', 'border-spacing' );
					}
					function setting_caption_side( $setting_id, $property_data ) {
						$label = esc_html__( 'Caption Side', $this->textdomain );
						$description = sprintf( esc_html__( 'Select the value of %1$s for the element. ( property : "%2$s" )', $this->textdomain ), esc_html__( 'Caption Side', $this->textdomain ), 'caption-side' );
						$choices = array(
							'initial' => esc_html__( 'Initial', $this->textdomain ),
							'top' => esc_html__( 'Top', $this->textdomain ),
							'bottom' => esc_html__( 'Bottom', $this->textdomain ),
							'inherit' => esc_html__( 'Inherit', $this->textdomain ),
						);
						$this->setting_input_with_choices( $setting_id, $property_data, $label, $description, $choices );
					}
					function setting_empty_cells( $setting_id, $property_data ) {
						$label = esc_html__( 'Empty Cells', $this->textdomain );
						$description = sprintf( esc_html__( 'Select the value of %1$s for the element. ( property : "%2$s" )', $this->textdomain ), esc_html__( 'Empty Cells', $this->textdomain ), 'empty-cells' );
						$choices = array(
							'initial' => esc_html__( 'Initial', $this->textdomain ),
							'show' => esc_html__( 'Show', $this->textdomain ),
							'hide' => esc_html__( 'Hide', $this->textdomain ),
							'inherit' => esc_html__( 'Inherit', $this->textdomain ),
						);
						$this->setting_input_with_choices( $setting_id, $property_data, $label, $description, $choices );
					}
					function setting_table_layout( $setting_id, $property_data ) {
						$label = esc_html__( 'Table Layout', $this->textdomain );
						$description = sprintf( esc_html__( 'Select the value of %1$s for the element. ( property : "%2$s" )', $this->textdomain ), esc_html__( 'Table Layout', $this->textdomain ), 'table-layout' );
						$choices = array(
							'initial' => esc_html__( 'Initial', $this->textdomain ),
							'auto' => esc_html__( 'Auto', $this->textdomain ),
							'fixed' => esc_html__( 'Fixed', $this->textdomain ),
							'inherit' => esc_html__( 'Inherit', $this->textdomain ),
						);
						$this->setting_input_with_choices( $setting_id, $property_data, $label, $description, $choices );
					}
				// 'table-layout', 'caption-side', 'border-collapse', 'border-spacing', 'empty-cells'

				# List
					function setting_counter_increment( $setting_id, $property_data ) {
						$label = esc_html__( 'Counter Increment', $this->textdomain );
						$description = sprintf( esc_html__( 'Select the value of %1$s for the element. ( property : "%2$s" )', $this->textdomain ), esc_html__( 'Counter Increment', $this->textdomain ), 'counter-increment' ) . esc_html__( 'You can define ID in text field', $this->textdomain );
						$choices = array(
							'initial' => esc_html__( 'Initial', $this->textdomain ),
							'none' => esc_html__( 'None', $this->textdomain ),
							'inherit' => esc_html__( 'Inherit', $this->textdomain ),
						);
						$this->setting_input_with_choices( $setting_id, $property_data, $label, $description, $choices );
					}
					function setting_counter_reset( $setting_id, $property_data ) {
						$label = esc_html__( 'Counter Reset', $this->textdomain );
						$description = sprintf( esc_html__( 'Select the value of %1$s for the element. ( property : "%2$s" )', $this->textdomain ), esc_html__( 'Counter Reset', $this->textdomain ), 'counter-reset' ) . esc_html__( 'You can specify Value by Entering "id Value" in text field', $this->textdomain );
						$choices = array(
							'none' => esc_html__( 'None', $this->textdomain ),
						);
						$this->setting_input_with_choices( $setting_id, $property_data, $label, $description, $choices );
					}			
					function setting_list_style( $setting_id, $property_data ) {
						$label = esc_html__( 'List Style', $this->textdomain );
						$description = sprintf( esc_html__( 'Enter the value of CSS property "%s" for the element', $this->textdomain ), 'list-style' );
						$this->setting_input_text( $setting_id, $property_data, $label, $description );
					}
					function setting_list_style_image( $setting_id, $property_data ) {
						$label = esc_html__( 'List Style Image', $this->textdomain );
						$description = sprintf( esc_html__( 'Select an image of %1$s for the element. ( property: "%2$s" )', $this->textdomain ), esc_html__( 'List Style Image', $this->textdomain ), 'list-style-image' );
						$this->setting_image_upload( $setting_id, $property_data, $label, $description );
					}
					function setting_list_style_position( $setting_id, $property_data ) {
						$label = esc_html__( 'List Style Position', $this->textdomain );
						$description = sprintf( esc_html__( 'Select the value of %1$s for the element. ( property : "%2$s" )', $this->textdomain ), esc_html__( 'List Style Position', $this->textdomain ), 'list-style-position' );
						$choices = array(
							'initial' => esc_html__( 'Initial', $this->textdomain ),
							'outside' => esc_html__( 'Outside', $this->textdomain ),
							'inside' => esc_html__( 'Inside', $this->textdomain ),
							'inherit' => esc_html__( 'Inherit', $this->textdomain ),
						);
						$this->setting_input_with_choices( $setting_id, $property_data, $label, $description, $choices );
					}
					function setting_list_style_type( $setting_id, $property_data ) {
						$label = esc_html__( 'List Style Type', $this->textdomain );
						$description = sprintf( esc_html__( 'Select the value of %1$s for the element. ( property : "%2$s" )', $this->textdomain ), esc_html__( 'List Style Type', $this->textdomain ), 'list-style-type' );
						$choices = array(
							'initial' => esc_html__( 'Initial', $this->textdomain ),
							'disc' => esc_html__( 'Disc', $this->textdomain ),
							'armenian' => esc_html__( 'Armenian', $this->textdomain ),
							'circle' => esc_html__( 'Circle', $this->textdomain ),
							'cjk-ideographic' => esc_html__( 'CJK-Ideographic', $this->textdomain ),
							'decimal' => esc_html__( 'Decimal', $this->textdomain ),
							'decimal-leading-zero' => esc_html__( 'Decimal-Leading-Zero', $this->textdomain ),
							'georgian' => esc_html__( 'Georgian', $this->textdomain ),
							'hebrew' => esc_html__( 'Hebrew', $this->textdomain ),
							'hiragana' => esc_html__( 'Hiragana', $this->textdomain ),
							'hiragana-iroha' => esc_html__( 'Hiragana-iroha', $this->textdomain ),
							'katakana' => esc_html__( 'Katakana', $this->textdomain ),
							'katakana-iroha' => esc_html__( 'Katakana-Iroha', $this->textdomain ),
							'lower-alpha' => esc_html__( 'Lower-Alpha', $this->textdomain ),
							'lower-greek' => esc_html__( 'Lower-Greek', $this->textdomain ),
							'lower-latin' => esc_html__( 'Lower-Latin', $this->textdomain ),
							'lower-roman' => esc_html__( 'Lower-Roman', $this->textdomain ),
							'none' => esc_html__( 'None', $this->textdomain ),
							'square' => esc_html__( 'Square', $this->textdomain ),
							'upper-alpha' => esc_html__( 'Upper-Alpha', $this->textdomain ),
							'upper-latin' => esc_html__( 'Upper-Latin', $this->textdomain ),
							'upper-roman' => esc_html__( 'Upper-Roman', $this->textdomain ),
							'inherit' => esc_html__( 'Inherit', $this->textdomain ),
						);
						$this->setting_input_with_choices( $setting_id, $property_data, $label, $description, $choices );
					}
				// 'list-style', 'list-style-image', 'list-style-type', 'list-style-position', 'marker-offset'

				# UI
					function setting_box_sizing( $setting_id, $property_data ) {
						$label = esc_html__( 'Box Sizing', $this->textdomain );
						$description = sprintf( esc_html__( 'Select the value of %1$s for the element. ( property : "%2$s" )', $this->textdomain ), esc_html__( 'Box Sizing', $this->textdomain ), 'box-sizing' );
						$choices = array(
							'initial' => esc_html__( 'Initial', $this->textdomain ),
							'content-box' => esc_html__( 'Content-Box', $this->textdomain ),
							'padding-box' => esc_html__( 'Padding-Box', $this->textdomain ),
							'border-box' => esc_html__( 'Border-Box', $this->textdomain ),
							'inherit' => esc_html__( 'Inherit', $this->textdomain ),
						);
						$this->setting_input_with_choices( $setting_id, $property_data, $label, $description, $choices );
					}
					function setting_content( $setting_id, $property_data ) {
						$label = esc_html__( 'Content', $this->textdomain ) . esc_html__( ' ( for Pseudo-elements "::before" "::after" )', $this->textdomain );
						$description = sprintf( esc_html__( 'Select the value of %1$s for the element. ( property : "%2$s" )', $this->textdomain ), esc_html__( 'Content', $this->textdomain ), 'content' ) . esc_html__( 'Text should be surrounded by double-quotes', $this->textdomain );
						$choices = array(
							'initial' => esc_html__( 'Initial', $this->textdomain ),
							'normal' => esc_html__( 'Normal', $this->textdomain ),
							'counter' => esc_html__( 'Counter', $this->textdomain ),
							'open-quote' => esc_html__( 'Open-Quote', $this->textdomain ),
							'close-quote' => esc_html__( 'Close-Quote', $this->textdomain ),
							'no-open-quote' => esc_html__( 'No-Open-Quote', $this->textdomain ),
							'no-close-quote' => esc_html__( 'No-Close-Quote', $this->textdomain ),
							'none' => esc_html__( 'None', $this->textdomain ),
							'inherit' => esc_html__( 'Inherit', $this->textdomain ),
						);
						$this->setting_input_with_choices( $setting_id, $property_data, $label, $description, $choices );
					}
					function setting_cursor( $setting_id, $property_data ) {
						$label = esc_html__( 'Cursor', $this->textdomain );
						$description = sprintf( esc_html__( 'Select the value of %1$s for the element. ( property : "%2$s" )', $this->textdomain ), esc_html__( 'Cursor', $this->textdomain ), 'cursor' );
						$choices = array(
							'initial' => esc_html__( 'Initial', $this->textdomain ),
							'alias' => esc_html__( 'Alias', $this->textdomain ),
							'all-scroll' => esc_html__( 'All-scroll', $this->textdomain ),
							'auto' => esc_html__( 'Auto', $this->textdomain ),
							'cell' => esc_html__( 'Cell', $this->textdomain ),
							'context-menu' => esc_html__( 'Context-Menu', $this->textdomain ),
							'col-resize' => esc_html__( 'Col-Resize', $this->textdomain ),
							'copy' => esc_html__( 'Copy', $this->textdomain ),
							'crosshair' => esc_html__( 'Crosshair', $this->textdomain ),
							'default' => esc_html__( 'Default', $this->textdomain ),
							'e-resize' => esc_html__( 'E-Resize', $this->textdomain ),
							'ew-resize' => esc_html__( 'EW-Resize', $this->textdomain ),
							'grab' => esc_html__( 'Grab', $this->textdomain ),
							'grabbing' => esc_html__( 'Grabbing', $this->textdomain ),
							'help' => esc_html__( 'Help', $this->textdomain ),
							'move' => esc_html__( 'Move', $this->textdomain ),
							'n-resize' => esc_html__( 'N-Resize', $this->textdomain ),
							'ne-resize' => esc_html__( 'NE-Resize', $this->textdomain ),
							'nesw-resize' => esc_html__( 'NESW-Resize', $this->textdomain ),
							'ns-resize' => esc_html__( 'NS-Resize', $this->textdomain ),
							'nw-resize' => esc_html__( 'NW-Resize', $this->textdomain ),
							'nwse-resize' => esc_html__( 'NWSE-Resize', $this->textdomain ),
							'no-drop' => esc_html__( 'No-Drop', $this->textdomain ),
							'none' => esc_html__( 'None', $this->textdomain ),
							'not-allowed' => esc_html__( 'Not-Allowed', $this->textdomain ),
							'pointer' => esc_html__( 'Pointer', $this->textdomain ),
							'progress' => esc_html__( 'Progress', $this->textdomain ),
							'row-resize' => esc_html__( 'Row-Resize', $this->textdomain ),
							's-resize' => esc_html__( 'S-Resize', $this->textdomain ),
							'se-resize' => esc_html__( 'SE-Resize', $this->textdomain ),
							'sw-resize' => esc_html__( 'SW-Resize', $this->textdomain ),
							'text' => esc_html__( 'Text', $this->textdomain ),
							'vertical-text' => esc_html__( 'Vertical-Text', $this->textdomain ),
							'w-resize' => esc_html__( 'W-Resize', $this->textdomain ),
							'wait' => esc_html__( 'Wait', $this->textdomain ),
							'zoom-in' => esc_html__( 'Zoom-In', $this->textdomain ),
							'zoom-out' => esc_html__( 'Zoom-Out', $this->textdomain ),
							'inherit' => esc_html__( 'Inherit', $this->textdomain ),
						);
					}
					function setting_ime_mode( $setting_id, $property_data ) {
						$label = esc_html__( 'IME Mode', $this->textdomain );
						$description = sprintf( esc_html__( 'Select the value of %1$s for the element. ( property : "%2$s" )', $this->textdomain ), esc_html__( 'IME Mode', $this->textdomain ), 'ime-mode' );
						$choices = array(
							'initial' => esc_html__( 'Initial', $this->textdomain ),
							'auto' => esc_html__( 'Auto', $this->textdomain ),
							'normal' => esc_html__( 'Normal', $this->textdomain ),
							'active' => esc_html__( 'Active', $this->textdomain ),
							'inactive' => esc_html__( 'Inactive', $this->textdomain ),
							'disabled' => esc_html__( 'Disabled', $this->textdomain ),
							'inherit' => esc_html__( 'Inherit', $this->textdomain ),
						);
						$this->setting_input_with_choices( $setting_id, $property_data, $label, $description, $choices );
					}
					function setting_outline( $setting_id, $property_data ) {
						$label = esc_html__( 'Outline', $this->textdomain );
						$description = sprintf( esc_html__( 'Enter the value of CSS property "%s" for the element', $this->textdomain ), 'outline' );
						$this->setting_input_text( $setting_id, $property_data, $label, $description );
					}
					function setting_outline_color( $setting_id, $property_data ) {
						$label = esc_html__( 'Outline Color', $this->textdomain );
						$description = sprintf( esc_html__( 'Pick a Color of %1$s for the element. ( property: "%2$s" )', $this->textdomain ), esc_html__( 'Outline', $this->textdomain ), 'outline-color' );
						$this->setting_color_picker( $setting_id, $property_data, $label, $description );
					}
					function setting_outline_offset( $setting_id, $property_data ) {
						$label = esc_html__( 'Outline Offset', $this->textdomain );
						$description = sprintf( esc_html__( 'Set the value of %1$s for the element. ( property: "%2$s" ) * step value for slidebar : "%3$d"', $this->textdomain ), esc_html__( 'Outline Offset', $this->textdomain ), 'outline-offset', '1' ) . sprintf( esc_html__( ' * Unit for the slidebar is "%s".', $this->textdomain ), 'px' );
						$this->setting_input_range( $setting_id, $property_data, $label, $description, 1, 100, '1', 'outline-offset' );
					}
					function setting_outline_style( $setting_id, $property_data ) {
						$label = esc_html__( 'Outline Style', $this->textdomain );
						$description = sprintf( esc_html__( 'Select the value of %1$s for the element. ( property : "%2$s" )', $this->textdomain ), esc_html__( 'Outline Style', $this->textdomain ), 'outline-style' );
						$choices = array(
							'initial' => esc_html__( 'initial', $this->textdomain ),
							'none' => esc_html__( 'none', $this->textdomain ),
							'hidden' => esc_html__( 'hidden', $this->textdomain ),
							'dotted' => esc_html__( 'dotted', $this->textdomain ),
							'dashed' => esc_html__( 'dashed', $this->textdomain ),
							'solid' => esc_html__( 'solid', $this->textdomain ),
							'double' => esc_html__( 'double', $this->textdomain ),
							'groove' => esc_html__( 'groove', $this->textdomain ),
							'ridge' => esc_html__( 'ridge', $this->textdomain ),
							'inset' => esc_html__( 'inset', $this->textdomain ),
							'outset' => esc_html__( 'outset', $this->textdomain ),
							'inherit' => esc_html__( 'inherit', $this->textdomain ),
						);
						$this->setting_input_with_choices( $setting_id, $property_data, $label, $description, $choices );
					}
					function setting_outline_width( $setting_id, $property_data ) {
						$label = esc_html__( 'Outline Width', $this->textdomain );
						$description = sprintf( esc_html__( 'Select the value of %1$s for the element. ( property : "%2$s" )', $this->textdomain ), esc_html__( 'Outline Width', $this->textdomain ), 'outline-width' );
						$choices = array(
							'initial' => esc_html__( 'Initial', $this->textdomain ),
							'medium' => esc_html__( 'Medium', $this->textdomain ),
							'thin' => esc_html__( 'Thin', $this->textdomain ),
							'thick' => esc_html__( 'Thick', $this->textdomain ),
							'inherit' => esc_html__( 'Inherit', $this->textdomain ),
						);
						$this->setting_input_with_choices( $setting_id, $property_data, $label, $description, $choices );
					}
					function setting_resize( $setting_id, $property_data ) {
						$label = esc_html__( 'Resize', $this->textdomain );
						$description = sprintf( esc_html__( 'Select the value of %1$s for the element. ( property : "%2$s" )', $this->textdomain ), esc_html__( 'Resize', $this->textdomain ), 'resize' );
						$choices = array(
							'initial' => esc_html__( 'Initial', $this->textdomain ),
							'none' => esc_html__( 'None', $this->textdomain ),
							'both' => esc_html__( 'Both', $this->textdomain ),
							'horizontal' => esc_html__( 'Horizontal', $this->textdomain ),
							'vertical' => esc_html__( 'Vertical', $this->textdomain ),
							'inherit' => esc_html__( 'Inherit', $this->textdomain ),
						);
						$this->setting_input_with_choices( $setting_id, $property_data, $label, $description, $choices );
					}
					function setting_text_overflow( $setting_id, $property_data ) {
						$label = esc_html__( 'Text Overflow', $this->textdomain );
						$description = sprintf( esc_html__( 'Select the value of %1$s for the element. ( property : "%2$s" )', $this->textdomain ), esc_html__( 'Text Overflow', $this->textdomain ), 'text-overflow' );
						$choices = array(
							'initial' => esc_html__( 'Initial', $this->textdomain ),
							'clip' => esc_html__( 'Clip', $this->textdomain ),
							'ellipsis' => esc_html__( 'Ellipsis', $this->textdomain ),
							'inherit' => esc_html__( 'Inherit', $this->textdomain ),
						);
						$this->setting_input_with_choices( $setting_id, $property_data, $label, $description, $choices );
					}
				// 'box-sizing', content', 'cursor', 'ime-mode', 'outline', 'outline-color', 'outline-offset', 'outline-style', 'outline-width', 'resize', 'text-overflow'

				# Multi-Columns Layout
					function setting_break_after( $setting_id, $property_data ) {
						$label = esc_html__( 'Break After', $this->textdomain );
						$description = sprintf( esc_html__( 'Select the value of %1$s for the element. ( property : "%2$s" )', $this->textdomain ), esc_html__( 'Break After', $this->textdomain ), 'break-after' );
						$choices = array(
							'initial' => esc_html__( 'Initial', $this->textdomain ),
							'auto' => esc_html__( 'Auto', $this->textdomain ),
							'always' => esc_html__( 'Always', $this->textdomain ),
							'avoid' => esc_html__( 'Avoid', $this->textdomain ),
							'left' => esc_html__( 'Left', $this->textdomain ),
							'right' => esc_html__( 'Right', $this->textdomain ),
							'page' => esc_html__( 'Page', $this->textdomain ),
							'column' => esc_html__( 'Column', $this->textdomain ),
							'region' => esc_html__( 'Region', $this->textdomain ),
							'recto' => esc_html__( 'Recto', $this->textdomain ),
							'verso' => esc_html__( 'Verso', $this->textdomain ),
							'avoid-page' => esc_html__( 'Avoid-Page', $this->textdomain ),
							'avoid-column' => esc_html__( 'Avoid-Column', $this->textdomain ),
							'avoid-region' => esc_html__( 'Avoid-Region', $this->textdomain ),
							'inherit' => esc_html__( 'Inherit', $this->textdomain ),
						);
						$this->setting_input_with_choices( $setting_id, $property_data, $label, $description, $choices );
					}
					function setting_break_before( $setting_id, $property_data ) {
						$label = esc_html__( 'Break Before', $this->textdomain );
						$description = sprintf( esc_html__( 'Select the value of %1$s for the element. ( property : "%2$s" )', $this->textdomain ), esc_html__( 'Break Before', $this->textdomain ), 'break-before' );
						$choices = array(
							'initial' => esc_html__( 'Initial', $this->textdomain ),
							'auto' => esc_html__( 'Auto', $this->textdomain ),
							'always' => esc_html__( 'Always', $this->textdomain ),
							'avoid' => esc_html__( 'Avoid', $this->textdomain ),
							'left' => esc_html__( 'Left', $this->textdomain ),
							'right' => esc_html__( 'Right', $this->textdomain ),
							'page' => esc_html__( 'Page', $this->textdomain ),
							'column' => esc_html__( 'Column', $this->textdomain ),
							'region' => esc_html__( 'Region', $this->textdomain ),
							'recto' => esc_html__( 'Recto', $this->textdomain ),
							'verso' => esc_html__( 'Verso', $this->textdomain ),
							'avoid-page' => esc_html__( 'Avoid-Page', $this->textdomain ),
							'avoid-column' => esc_html__( 'Avoid-Column', $this->textdomain ),
							'avoid-region' => esc_html__( 'Avoid-Region', $this->textdomain ),
							'inherit' => esc_html__( 'Inherit', $this->textdomain ),
						);
						$this->setting_input_with_choices( $setting_id, $property_data, $label, $description, $choices );
					}
					function setting_break_inside( $setting_id, $property_data ) {
						$label = esc_html__( 'Break Inside', $this->textdomain );
						$description = sprintf( esc_html__( 'Select the value of %1$s for the element. ( property : "%2$s" )', $this->textdomain ), esc_html__( 'Break Inside', $this->textdomain ), 'break-inside' );
						$choices = array(
							'initial' => esc_html__( 'Initial', $this->textdomain ),
							'auto' => esc_html__( 'Auto', $this->textdomain ),
							'avoid-page' => esc_html__( 'Avoid-Page', $this->textdomain ),
							'avoid-column' => esc_html__( 'Avoid-Column', $this->textdomain ),
							'avoid-region' => esc_html__( 'Avoid-Region', $this->textdomain ),
							'inherit' => esc_html__( 'Inherit', $this->textdomain ),
						);
						$this->setting_input_with_choices( $setting_id, $property_data, $label, $description, $choices );
					}
					function setting_column_count( $setting_id, $property_data ) {
						$label = esc_html__( 'Column Count', $this->textdomain );
						$description = sprintf( esc_html__( 'Select the value of %1$s for the element. ( property : "%2$s" )', $this->textdomain ), esc_html__( 'Column Count', $this->textdomain ), 'column-count' );
						$choices = array(
							'initial' => esc_html__( 'Initial', $this->textdomain ),
							'auto' => esc_html__( 'Auto', $this->textdomain ),
							'1' => '1',
							'2' => '2',
							'3' => '3',
							'4' => '4',
							'5' => '5',
							'inherit' => esc_html__( 'Inherit', $this->textdomain ),
						);
						$this->setting_input_with_choices( $setting_id, $property_data, $label, $description, $choices );
					}
					function setting_column_fill( $setting_id, $property_data ) {
						$label = esc_html__( 'Column Fill', $this->textdomain );
						$description = sprintf( esc_html__( 'Select the value of %1$s for the element. ( property : "%2$s" )', $this->textdomain ), esc_html__( 'Column Fill', $this->textdomain ), 'column-fill' );
						$choices = array(
							'initial' => esc_html__( 'Initial', $this->textdomain ),
							'auto' => esc_html__( 'Auto', $this->textdomain ),
							'balance' => esc_html__( 'Balance', $this->textdomain ),
							'inherit' => esc_html__( 'Inherit', $this->textdomain ),
						);
						$this->setting_input_with_choices( $setting_id, $property_data, $label, $description, $choices );
					}
					function setting_column_gap( $setting_id, $property_data ) {
						$label = esc_html__( 'Column Gap', $this->textdomain );
						$description = sprintf( esc_html__( 'Set the value of %1$s for the element. ( property: "%2$s" ) * step value for slidebar : "%3$d"', $this->textdomain ), esc_html__( 'Column Gap', $this->textdomain ), 'column-gap', '1' ) . sprintf( esc_html__( ' * Unit for the slidebar is "%s".', $this->textdomain ), 'px' );
						$this->setting_input_range( $setting_id, $property_data, $label, $description, 1, 100, '1', 'column-gap' );
					}
					function setting_column_rule( $setting_id, $property_data ) {
						$label = esc_html__( 'Column Rule', $this->textdomain );
						$description = sprintf( esc_html__( 'Enter the value of CSS property "%s" for the element', $this->textdomain ), 'column-rule' );
						$this->setting_input_text( $setting_id, $property_data, $label, $description );
					}
					function setting_column_rule_color( $setting_id, $property_data ) {
						$label = esc_html__( 'Column Rule Color', $this->textdomain );
						$description = sprintf( esc_html__( 'Pick a Color of %1$s for the element. ( property: "%2$s" )', $this->textdomain ), esc_html__( 'Column Rule', $this->textdomain ), 'column-rule-color' );
						$this->setting_color_picker( $setting_id, $property_data, $label, $description );
					}
					function setting_column_rule_style( $setting_id, $property_data ) {
						$label = esc_html__( 'Column Rule Style', $this->textdomain );
						$description = sprintf( esc_html__( 'Select the value of %1$s for the element. ( property : "%2$s" )', $this->textdomain ), esc_html__( 'Column Rule Style', $this->textdomain ), 'column-rule-style' );
						$choices = array(
							'initial' => esc_html__( 'Initial', $this->textdomain ),
							'none' => esc_html__( 'None', $this->textdomain ),
							'hidden' => esc_html__( 'Hidden', $this->textdomain ),
							'dotted' => esc_html__( 'Dotted', $this->textdomain ),
							'dashed' => esc_html__( 'Dashed', $this->textdomain ),
							'solid' => esc_html__( 'Solid', $this->textdomain ),
							'double' => esc_html__( 'Double', $this->textdomain ),
							'groove' => esc_html__( 'Groove', $this->textdomain ),
							'ridge' => esc_html__( 'Ridge', $this->textdomain ),
							'inset' => esc_html__( 'Inset', $this->textdomain ),
							'outset' => esc_html__( 'Outset', $this->textdomain ),
							'inherit' => esc_html__( 'Inherit', $this->textdomain ),
						);
						$this->setting_input_with_choices( $setting_id, $property_data, $label, $description, $choices );
					}
					function setting_column_rule_width( $setting_id, $property_data ) {
						$label = esc_html__( 'Column Rule Width', $this->textdomain );
						$description = sprintf( esc_html__( 'Select the value of %1$s for the element. ( property : "%2$s" )', $this->textdomain ), esc_html__( 'Column Rule Width', $this->textdomain ), 'column-rule-width' );
						$choices = array(
							'initial' => esc_html__( 'Initial', $this->textdomain ),
							'medium' => esc_html__( 'Medium', $this->textdomain ),
							'thin' => esc_html__( 'Thin', $this->textdomain ),
							'thick' => esc_html__( 'Thick', $this->textdomain ),
							'inherit' => esc_html__( 'Inherit', $this->textdomain ),
						);
						$this->setting_input_with_choices( $setting_id, $property_data, $label, $description, $choices );
					}
					function setting_column_span( $setting_id, $property_data ) {
						$label = esc_html__( 'Column Span', $this->textdomain );
						$description = sprintf( esc_html__( 'Select the value of %1$s for the element. ( property : "%2$s" )', $this->textdomain ), esc_html__( 'Column Span', $this->textdomain ), 'column-span' );
						$choices = array(
							'initial' => esc_html__( 'Initial', $this->textdomain ),
							'none' => esc_html__( 'None', $this->textdomain ),
							'all' => esc_html__( 'All', $this->textdomain ),
							'inherit' => esc_html__( 'Inherit', $this->textdomain ),
						);
						$this->setting_input_with_choices( $setting_id, $property_data, $label, $description, $choices );
					}
					function setting_column_width( $setting_id, $property_data ) {
						$label = esc_html__( 'Column Width', $this->textdomain );
						$description = sprintf( esc_html__( 'Set the value of %1$s for the element. ( property: "%2$s" ) * step value for slidebar : "%3$d"', $this->textdomain ), esc_html__( 'Column Column Width', $this->textdomain ), 'column-width', '10' ) . sprintf( esc_html__( ' * Unit for the slidebar is "%s".', $this->textdomain ), 'px' );
						$this->setting_input_range( $setting_id, $property_data, $label, $description, 50, 600, '10', 'column-width' );
					}
					function setting_columns( $setting_id, $property_data ) {
						$label = esc_html__( 'Columns', $this->textdomain );
						$description = sprintf( esc_html__( 'Enter the value of CSS property "%s" for the element', $this->textdomain ), 'columns' );
						$this->setting_input_text( $setting_id, $property_data, $label, $description );
					}
					function setting_widows( $setting_id, $property_data ) {
						$label = esc_html__( 'Widows', $this->textdomain );
						$description = sprintf( esc_html__( 'Set the value of %1$s for the element. ( property: "%2$s" ) * step value for slidebar : "%3$d"', $this->textdomain ), esc_html__( 'Widows', $this->textdomain ), 'widows', '1' );
						$this->setting_input_range( $setting_id, $property_data, $label, $description, 1, 5, '1', 'widows' );
					}
				// 'break-after', 'break-before', 'break-inside', 'column-count', 'column-fill', 'column-gap', 'column-rule', 'column-rule-color', 'column-rule-style', 'column-rule-width', 'column-span', 'column-width', 'columns', 'widows'

				# Page
					function setting_orphans( $setting_id, $property_data ) {
						$label = esc_html__( 'Orphans', $this->textdomain );
						$description = sprintf( esc_html__( 'Set the value of %1$s for the element. ( property: "%2$s" ) * step value for slidebar : "%3$d"', $this->textdomain ), esc_html__( 'Orphans', $this->textdomain ), 'orphans', '1' );
						$this->setting_input_range( $setting_id, $property_data, $label, $description, 1, 5, '1', 'orphans' );
					}
					function setting_page_break_after( $setting_id, $property_data ) {
						$label = esc_html__( 'Page Break After', $this->textdomain );
						$description = sprintf( esc_html__( 'Select the value of %1$s for the element. ( property : "%2$s" )', $this->textdomain ), esc_html__( 'Page Break After', $this->textdomain ), 'page-break-after' );
						$choices = array(
							'initial' => esc_html__( 'Initial', $this->textdomain ),
							'auto' => esc_html__( 'Auto', $this->textdomain ),
							'always' => esc_html__( 'Always', $this->textdomain ),
							'avoid' => esc_html__( 'Avoid', $this->textdomain ),
							'left' => esc_html__( 'Left', $this->textdomain ),
							'right' => esc_html__( 'Right', $this->textdomain ),
							'recto' => esc_html__( 'Recto', $this->textdomain ),
							'verso' => esc_html__( 'Verso', $this->textdomain ),
							'inherit' => esc_html__( 'Inherit', $this->textdomain ),
						);
						$this->setting_input_with_choices( $setting_id, $property_data, $label, $description, $choices );
					}
					function setting_page_break_before( $setting_id, $property_data ) {
						$label = esc_html__( 'Page Break Before', $this->textdomain );
						$description = sprintf( esc_html__( 'Select the value of %1$s for the element. ( property : "%2$s" )', $this->textdomain ), esc_html__( 'Page Break Before', $this->textdomain ), 'page-break-before' );
						$choices = array(
							'initial' => esc_html__( 'Initial', $this->textdomain ),
							'auto' => esc_html__( 'Auto', $this->textdomain ),
							'always' => esc_html__( 'Always', $this->textdomain ),
							'avoid' => esc_html__( 'Avoid', $this->textdomain ),
							'left' => esc_html__( 'Left', $this->textdomain ),
							'right' => esc_html__( 'Right', $this->textdomain ),
							'recto' => esc_html__( 'Recto', $this->textdomain ),
							'verso' => esc_html__( 'Verso', $this->textdomain ),
							'inherit' => esc_html__( 'Inherit', $this->textdomain ),
						);
						$this->setting_input_with_choices( $setting_id, $property_data, $label, $description, $choices );
					}
					function setting_page_break_inside( $setting_id, $property_data ) {
						$label = esc_html__( 'Page Break Inside', $this->textdomain );
						$description = sprintf( esc_html__( 'Select the value of %1$s for the element. ( property : "%2$s" )', $this->textdomain ), esc_html__( 'Page Break Inside', $this->textdomain ), 'page-break-inside' );
						$choices = array(
							'initial' => esc_html__( 'Initial', $this->textdomain ),
							'auto' => esc_html__( 'auto', $this->textdomain ),
							'avoid' => esc_html__( 'avoid', $this->textdomain ),
							'inherit' => esc_html__( 'Inherit', $this->textdomain ),
						);
						$this->setting_input_with_choices( $setting_id, $property_data, $label, $description, $choices );
					}
				// 'orphans', 'page-break-after', 'page-break-before', 'page-break-inside'

				# Generated COntents
					function setting_marks( $setting_id, $property_data ) {
						$label = esc_html__( 'Marks', $this->textdomain );
						$description = sprintf( esc_html__( 'Select the value of %1$s for the element. ( property : "%2$s" )', $this->textdomain ), esc_html__( 'Marks', $this->textdomain ), 'marks' );
						$choices = array(
							'initial' => esc_html__( 'Initial', $this->textdomain ),
							'crop' => esc_html__( 'Crop', $this->textdomain ),
							'cross' => esc_html__( 'Cross', $this->textdomain ),
							'none' => esc_html__( 'None', $this->textdomain ),
							'inherit' => esc_html__( 'Inherit', $this->textdomain ),
						);
						$this->setting_input_with_choices( $setting_id, $property_data, $label, $description, $choices );
					}
					function setting_quotes( $setting_id, $property_data ) {
						$label = esc_html__( 'Quotes', $this->textdomain );
						$description = sprintf( esc_html__( 'Enter the value of CSS property "%s" for the element', $this->textdomain ), 'quotes' );
						$this->setting_input_text( $setting_id, $property_data, $label, $description );
					}
				// 'marks', 'quotes'

				# Filters
					function setting_filters( $setting_id, $property_data ) {
						$label = esc_html__( 'Filters', $this->textdomain );
						$description = sprintf( esc_html__( 'Enter the value of CSS property "%s" for the element', $this->textdomain ), 'filters' ) . esc_html__( 'Like "func( val )", "url( file )"', $this->textdomain );
						$this->setting_input_text( $setting_id, $property_data, $label, $description );
					}
				// 'filters'

				# Image Related
					function setting_image_orientation( $setting_id, $property_data ) {
						$label = esc_html__( 'Image Orientation', $this->textdomain );
						$description = sprintf( esc_html__( 'Select the value of %1$s for the element. ( property : "%2$s" )', $this->textdomain ), esc_html__( 'Image Orientation', $this->textdomain ), 'image-orientation' );
						$choices = array(
							'initial' => esc_html__( 'Initial', $this->textdomain ),
							'from-image' => esc_html__( 'Refer to EXIF of the Image', $this->textdomain ),
							'flip' => esc_html__( 'Flip', $this->textdomain ),
							'inherit' => esc_html__( 'Inherit', $this->textdomain ),
						);
						$this->setting_input_with_choices( $setting_id, $property_data, $label, $description, $choices );
					}
					function setting_image_rendering( $setting_id, $property_data ) {
						$label = esc_html__( 'Image Rendering', $this->textdomain );
						$description = sprintf( esc_html__( 'Select the value of %1$s for the element. ( property : "%2$s" )', $this->textdomain ), esc_html__( 'Image Rendering', $this->textdomain ), 'image-rendering' );
						$choices = array(
							'initial' => esc_html__( 'Initial', $this->textdomain ),
							'auto' => esc_html__( 'Rely on User-Agent', $this->textdomain ),
							'optimizeQuality' => esc_html__( 'Optimize Quality', $this->textdomain ),
							'optimizeSpeed' => esc_html__( 'Optimize Speed', $this->textdomain ),
							'inherit' => esc_html__( 'Inherit', $this->textdomain ),
						);
						$this->setting_input_with_choices( $setting_id, $property_data, $label, $description, $choices );
					}
				# Not Yet
					/*function setting_image_resolution( $setting_id, $property_data ) {

					}*/
					function setting_object_fit( $setting_id, $property_data ) {
						$label = esc_html__( 'Object Fit', $this->textdomain );
						$description = sprintf( esc_html__( 'Select the value of %1$s for the element. ( property : "%2$s" )', $this->textdomain ), esc_html__( 'Object Fit', $this->textdomain ), 'object-fit' );
						$choices = array(
							'initial' => esc_html__( 'Initial', $this->textdomain ),
							'fill' => esc_html__( 'Fill', $this->textdomain ),
							'contain' => esc_html__( 'Contain', $this->textdomain ),
							'cover' => esc_html__( 'Cover', $this->textdomain ),
							'none' => esc_html__( 'None', $this->textdomain ),
							'scale-down' => esc_html__( 'Scale-Down', $this->textdomain ),
							'inherit' => esc_html__( 'Inherit', $this->textdomain ),
						);
						$this->setting_input_with_choices( $setting_id, $property_data, $label, $description, $choices );
					}
					function setting_object_position( $setting_id, $property_data ) {
						$label = esc_html__( 'Object Position', $this->textdomain );
						$description = sprintf( esc_html__( 'Set the value of %1$s for the element. ( property: "%2$s" ) * step value for slidebar : "%3$d"', $this->textdomain ), esc_html__( 'Object Position', $this->textdomain ), 'object-position', '1' ) . sprintf( esc_html__( ' * Unit for the slidebar is "%s".', $this->textdomain ), 'px' );
						$this->setting_input_range( $setting_id, $property_data, $label, $description, 0, 500, '1', 'object-position' );
					}
				// 'image-orientation', 'image-rendering', 'image-resolution', 'object-fit', 'object-position'

				# Mask
					function setting_mask( $setting_id, $property_data ) {
						$label = esc_html__( 'Mask', $this->textdomain );
						$description = sprintf( esc_html__( 'Enter the value of CSS property "%s" for the element', $this->textdomain ), 'mask' );
						$this->setting_input_text( $setting_id, $property_data, $label, $description );
					}
					function setting_mask_type( $setting_id, $property_data ) {
						$label = esc_html__( 'Mask Type', $this->textdomain );
						$description = sprintf( esc_html__( 'Select the value of %1$s for the element. ( property : "%2$s" )', $this->textdomain ), esc_html__( 'Mask Type', $this->textdomain ), 'mask-type' );
						$choices = array(
							'initial' => esc_html__( 'Initial', $this->textdomain ),
							'luminance' => esc_html__( 'Luminance', $this->textdomain ),
							'alpha' => esc_html__( 'Alpha', $this->textdomain ),
							'inherit' => esc_html__( 'Inherit', $this->textdomain ),
						);
						$this->setting_input_with_choices( $setting_id, $property_data, $label, $description, $choices );
					}
				// 'mask', mask-type

				# Speach
				# Later
				// 'mark', 'mark-after', 'mark-before', 'phonemes', 'rest', 'rest-after', 'rest-before', 'voice-balance', 'voice-duration', 'voice-pitch', 'voice-pitch-range', 'voice-rate', 'voice-stress', 'voice-volume'

				# Marquee
				# Later
				// 'marquee-direction', 'marquee-play-count', 'marquee-speed', 'marquee-style'

			# Sanitize
				function sanitize_checkbox( $input ) {
					if ( $input == true ) {
						return true;
					} else {
						return false;
					}
				}
				function sanitize_int( $input ) {
					return intval( $input );
				}

	}
}
?>