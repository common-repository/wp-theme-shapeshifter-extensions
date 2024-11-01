<?php

// From Saved Theme Mods Values
	// Get
	$theme_mods = sse()->get_theme_mods();

	// Headlines List
	$headlines = array(
		'h1' => esc_html__( 'Title', ShapeShifter_Extensions::TEXTDOMAIN ),//ã‚¿ã‚¤ãã«
		'h2' => 'h2',
		'h3' => 'h3',
		'h4' => 'h4',
		'h5' => 'h5',
		'h6' => 'h6'
	);
	
	// Default Values of Icon Select
	$icon_def = array(
		'h1' => ( 
			( isset( $theme_mods['singular_page_h1_fontawesome_icon_select'] ) 
				&& is_string( $theme_mods['singular_page_h1_fontawesome_icon_select'] )
				&& "" !== $theme_mods['singular_page_h1_fontawesome_icon_select']
			)
			? sanitize_text_field( $theme_mods['singular_page_h1_fontawesome_icon_select'] )
			: 'f1b2'
		),
		'h2' => (
			( isset( $theme_mods['singular_page_h2_fontawesome_icon_select'] ) 
				&& is_string( $theme_mods['singular_page_h2_fontawesome_icon_select'] )
				&& "" !== $theme_mods['singular_page_h2_fontawesome_icon_select']
			)
			? sanitize_text_field( $theme_mods['singular_page_h2_fontawesome_icon_select'] )
			: 'f04b'
		),
		'h3' => (
			( isset( $theme_mods['singular_page_h3_fontawesome_icon_select'] ) 
				&& is_string( $theme_mods['singular_page_h3_fontawesome_icon_select'] )
				&& "" !== $theme_mods['singular_page_h3_fontawesome_icon_select']
			)
			? sanitize_text_field( $theme_mods['singular_page_h3_fontawesome_icon_select'] )
			: 'f0d0' 
		),
		'h4' => (
			( isset( $theme_mods['singular_page_h4_fontawesome_icon_select'] ) 
				&& is_string( $theme_mods['singular_page_h4_fontawesome_icon_select'] )
				&& "" !== $theme_mods['singular_page_h4_fontawesome_icon_select']
			)
			? sanitize_text_field( $theme_mods['singular_page_h4_fontawesome_icon_select'] )
			: 'none' 
		),
		'h5' => (
			( isset( $theme_mods['singular_page_h5_fontawesome_icon_select'] ) 
				&& is_string( $theme_mods['singular_page_h5_fontawesome_icon_select'] )
				&& "" !== $theme_mods['singular_page_h5_fontawesome_icon_select']
			)
			? sanitize_text_field( $theme_mods['singular_page_h5_fontawesome_icon_select'] )
			: 'none' 
			),
		'h6' => (
			( isset( $theme_mods['singular_page_h6_fontawesome_icon_select'] ) 
				&& is_string( $theme_mods['singular_page_h6_fontawesome_icon_select'] )
				&& "" !== $theme_mods['singular_page_h6_fontawesome_icon_select']
			)
			? sanitize_text_field( $theme_mods['singular_page_h6_fontawesome_icon_select'] )
			: 'none' 
		)
	);

	// Default Values of Icon Color 
	$color_def = array(
		'h1' => (
			( isset( $theme_mods['singular_page_h1_fontawesome_icon_color'] ) 
				&& is_string( $theme_mods['singular_page_h1_fontawesome_icon_color'] )
				&& "" !== $theme_mods['singular_page_h1_fontawesome_icon_color']
			)
			? sanitize_text_field( $theme_mods['singular_page_h1_fontawesome_icon_color'] )
			: '#000000' 
		),
		'h2' => (
			( isset( $theme_mods['singular_page_h2_fontawesome_icon_color'] ) 
				&& is_string( $theme_mods['singular_page_h2_fontawesome_icon_color'] )
				&& "" !== $theme_mods['singular_page_h2_fontawesome_icon_color']
			)
			? sanitize_text_field( $theme_mods['singular_page_h2_fontawesome_icon_color'] )
			: '#000000' 
		),
		'h3' => (
			( isset( $theme_mods['singular_page_h3_fontawesome_icon_color'] ) 
				&& is_string( $theme_mods['singular_page_h3_fontawesome_icon_color'] )
				&& "" !== $theme_mods['singular_page_h3_fontawesome_icon_color']
			)
			? sanitize_text_field( $theme_mods['singular_page_h3_fontawesome_icon_color'] )
			: '#000000' 
		),
		'h4' => (
			( isset( $theme_mods['singular_page_h4_fontawesome_icon_color'] ) 
				&& is_string( $theme_mods['singular_page_h4_fontawesome_icon_color'] )
				&& "" !== $theme_mods['singular_page_h4_fontawesome_icon_color']
			)
			? sanitize_text_field( $theme_mods['singular_page_h4_fontawesome_icon_color'] )
			: '#000000' 
		),
		'h5' => (
			( isset( $theme_mods['singular_page_h5_fontawesome_icon_color'] ) 
				&& is_string( $theme_mods['singular_page_h5_fontawesome_icon_color'] )
				&& "" !== $theme_mods['singular_page_h5_fontawesome_icon_color']
			)
			? sanitize_text_field( $theme_mods['singular_page_h5_fontawesome_icon_color'] )
			: '#000000' 
		),
		'h6' => (
			( isset( $theme_mods['singular_page_h6_fontawesome_icon_color'] ) 
				&& is_string( $theme_mods['singular_page_h6_fontawesome_icon_color'] )
				&& "" !== $theme_mods['singular_page_h6_fontawesome_icon_color']
			)
			? sanitize_text_field( $theme_mods['singular_page_h6_fontawesome_icon_color'] )
			: '#000000' 
		)
	);

// Nonce
	wp_nonce_field( sse()->get_prefixed_theme_post_meta_name( 'meta_icons_box' ), sse()->get_prefixed_theme_post_meta_name( 'meta_icons_box_nonce' ) );

// Saved Value of Icon Mods Bool
$is_fa_icons_mods_on = esc_attr( 
	get_post_meta( 
		$post->ID, 
		sse()->get_prefixed_theme_post_meta_name( 'is_fa_icons_mods_on' ), 
		true 
	) 
);

// Checkbox for Icon Mod
echo '<label for="' . esc_attr( sse()->get_prefixed_theme_post_meta_name( 'is_fa_icons_mods_on' ) ) . '">' . esc_html__( 'Apply the icon Settings below', ShapeShifter_Extensions::TEXTDOMAIN ) . '</label><br />';//
echo '<input type="checkbox" 
	id="' . esc_attr( sse()->get_prefixed_theme_post_meta_name( 'is_fa_icons_mods_on' ) ) . '"
	name="' . esc_attr( sse()->get_prefixed_theme_post_meta_name( 'is_fa_icons_mods_on' ) ) . '"
	value="is_fa_icons_mods_on"
	' . checked( $is_fa_icons_mods_on, 'is_fa_icons_mods_on', false ) . '
/><br /><br />';

echo '<pre>';
var_dump( $is_fa_icons_mods_on );
echo '</pre>';

// Each Headline
foreach( $headlines as $hl => $hl_val ) {
	
	// Sete Defaults
		$theme_mod_key_icon_select = 'singular_page_' . $hl . '_fontawesome_icon_select';
		$this->icon_defaults[ $hl ] = (
			( isset( $theme_mods[ $theme_mod_key_icon_select ] ) 
				&& is_string( $theme_mods[ $theme_mod_key_icon_select ] )
				&& "" !== $theme_mods[ $theme_mod_key_icon_select ]
			)
			? sanitize_text_field( $theme_mods[ $theme_mod_key_icon_select ] )
			: $this->icon_default_base[ $hl ]
		);

		$theme_mod_key_icon_color = 'singular_page_' . $hl . '_fontawesome_icon_color';
		$this->color_defaults[ $hl ] = (
			( isset( $theme_mods[ $theme_mod_key_icon_color ] ) 
				&& is_string( $theme_mods[ $theme_mod_key_icon_color ] )
				&& "" !== $theme_mods[ $theme_mod_key_icon_color ]
			)
			? sanitize_text_field( $theme_mods[ $theme_mod_key_icon_color ] )
			: $this->color_default_base[ $hl ]
		);

	// Vars
		// Icon Select 
		$postmeta_key_icon_select = sse()->get_prefixed_theme_post_meta_name( $hl . '_icons_select' );
		$icon_select = ( 
			! empty( get_post_meta( $post->ID, $postmeta_key_icon_select, true ) ) 
			? sanitize_text_field( get_post_meta( $post->ID, $postmeta_key_icon_select, true ) )
			: sanitize_text_field( $this->icon_defaults[ $hl ] )
		);

		// Icon Color
		$postmeta_key_icon_color = sse()->get_prefixed_theme_post_meta_name( $hl . '_icons_color' );
		$color_val = ( 
			sanitize_text_field( get_post_meta( $post->ID, $postmeta_key_icon_color, true ) )
			? sanitize_text_field( get_post_meta( $post->ID, $postmeta_key_icon_color, true ) ) 
			: sanitize_text_field( $this->color_defaults[ $hl ] )
		);

	// Icon Select
		echo '<label for="' . esc_attr( $postmeta_key_icon_select ) . '">
				' . sprintf( esc_html__( 'Select Icon of %s', ShapeShifter_Extensions::TEXTDOMAIN ), $hl_val ) . '
			</label><br />';
		echo '<select
			name="' . esc_attr( $postmeta_key_icon_select ) . '"
			id="' . esc_attr( $postmeta_key_icon_select ) . '"
			class="sse-preview-icons"
			style="font-family:FontAwesome;"
			multiple
		>
			<option value="none">' . esc_html__( 'None', ShapeShifter_Extensions::TEXTDOMAIN ) . '</option>';
			foreach( $this->icons['FontAwesome'] as $fa => $fa_val ) {

				$fa = esc_attr( $fa );
				$fa_val = esc_html( $fa_val );

				echo '<option value="' . $fa . '" ' . selected( $icon_select, $fa ) . '
					class=""
					data-class="fa ' . $fa_val . '"
					data-icon-unicode="&#x' . $fa . ';"
				>&#x' . $fa . ';  ' . $fa_val . '</option>';
				
			}
				
		echo '</select><br />';

	// Icon Colors
		echo '<label for="' . esc_attr( $postmeta_key_icon_color ) . '">'. sprintf( esc_html__( 'Icon Color of %s', ShapeShifter_Extensions::TEXTDOMAIN ), $hl_val ) . '</label><br />';
		echo '<input type="text" 
			name="' . esc_attr( $postmeta_key_icon_color ) . '"
			id="' . esc_attr( $postmeta_key_icon_color ) . '"
			value="' . esc_attr( $color_val ) . '"
			class="alpha-color-picker"
			data-default-color="' . esc_attr( $color_val ) . '" />';

		echo '<br /><br />';

}

