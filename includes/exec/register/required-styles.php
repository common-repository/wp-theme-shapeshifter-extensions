<?php


// 3rds
	/*
	// Slider Pro
	wp_register_style( 
		'slider-pro', 
		SHAPESHIFTER_EXTENSIONS_THIRD_URL . 'slider-pro-master/dist/css/slider-pro.min.css' 
	);
	// Vegas
	wp_register_style( 
		'vegas', 
		SHAPESHIFTER_EXTENSIONS_THIRD_URL . 'vegas/vegas.min.css' 
	);
	// Icomoon
	wp_register_style( 
		'icomoon', 
		SHAPESHIFTER_EXTENSIONS_THIRD_URL . 'icomoon/style.min.css' 
	);
	*/

// Public
	// Generals
	if ( SSE_IS_MOBILE ) {
		wp_register_style( 
			'sse-frontend-general', 
			SSE_ASSETS_URL . 'css/frontend/mobile.min.css'
			//array( 'slider-pro', 'vegas', 'icomoon' ) 
		);
	} else {
		wp_register_style( 
			'sse-frontend-general', 
			SSE_ASSETS_URL . 'css/frontend/frontend.min.css'
			//array( 'slider-pro', 'vegas', 'icomoon' ) 
		);
	}
	wp_register_style( 'sse-frontend-required', SSE_ASSETS_URL . 'css/frontend/style.css' );

// Admin
if ( is_admin() ) {

	wp_register_style( 'sse-font-awesome', SHAPESHIFTER_EXTENSIONS_THIRD_URL . 'font-awesome/css/font-awesome.css' );

	// 3rd
		// alpha-color-picker
			wp_register_style( 
				'alpha-color-picker', 
				SHAPESHIFTER_EXTENSIONS_DIR_URL . 'assets/css/custom-color-picker.min.css' 
			);

	// TinyMCE
		wp_register_style(
			'sse-mce-button',
			SSE_ASSETS_URL . 'css/admin/admin-mce-button.css'
		);

	// Admin Menu Pages
		wp_register_style( 
			'sse-admin-pages', 
			SSE_ASSETS_URL . 'css/admin/admin-pages.css' 
		);

	// Widget Settings
		wp_register_style( 
			'sse-widget-settings-form', 
			SSE_ASSETS_URL . 'css/admin/admin-widget-settings-form-popup.css' 
		);

	// Nav Menu
		wp_register_style( 
			'sse-nav-menu', 
			SSE_ASSETS_URL . 'css/admin/admin-nav-menu-edit.css' 
		);

	// Meta Boxes
		wp_register_style( 
			'sse-pupup-settings', 
			SSE_ASSETS_URL . 'css/admin/admin-popup-setting-form-menu.css',
			array( 'alpha-color-picker' )
		);

		wp_register_style( 
			'sse-metaboxes', 
			SSE_ASSETS_URL . 'css/admin/metaboxes.css',
			array( 'alpha-color-picker' )
		);

}

if ( is_customize_preview() ) {

	// alpha-color-picker
		wp_register_style( 
			'alpha-color-picker', 
			SHAPESHIFTER_EXTENSIONS_DIR_URL . 'assets/css/custom-color-picker.min.css' 
		);

}
