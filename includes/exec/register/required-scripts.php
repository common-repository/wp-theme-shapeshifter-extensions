<?php

// Global
	// 3rds
		/*
		// Slider Pro
		wp_register_script( 
			'slider-pro', 
			SHAPESHIFTER_EXTENSIONS_THIRD_URL . 'slider-pro-master/dist/js/jquery.sliderPro.min.js', 
			array( 'jquery' ), 
			false, 
			true 
		);
		// Vegas
		wp_register_script( 
			'vegas', 
			SHAPESHIFTER_EXTENSIONS_THIRD_URL . 'vegas/vegas.min.js', 
			array( 'jquery' ), 
			false, 
			true 
		);

		// Widget
			// Slide Gallary
			wp_register_script(
				'sse-widget-slide-gallery',
				SSE_ASSETS_URL . 'js/frontend/widget-slide-gallery.js',
				array( 'jquery', 'slider-pro', 'shapeshifter-javascripts' ),
				false,
				true
			);
		*/
	// Theme
		// With Backbone
		/*wp_register_script( 
			'sse-admin-with-backbone', 
			SSE_ASSETS_URL . 'js/with-backbone.js', 
			array( 'jquery', 'jquery-ui-resizable', 'jquery-ui-sortable', 'jquery-ui-draggable', 'underscore', 'backbone', 'plupload', 'plupload-all' ) 
		);*/

// Public
	// General
	wp_register_script( 
		'sse-frontend', 
		SSE_ASSETS_URL . 'js/frontend/frontend-javascripts.js', 
//		array( 'jquery', 'slider-pro', 'vegas', 'sse-widget-slide-gallery' ), 
		array( 'jquery' ), 
		false, 
		true 
	);

if( is_admin() || is_customize_preview() ) {
	wp_localize_script( 'jquery', 'sseDirURLForJS', array(
		'frontPageURL' => esc_url( home_url() ),
		'adminProfileURL' => esc_url( admin_url( 'profile.php' ) ),
		'adminEditURL' => esc_url( admin_url( 'edit.php' ) ),
		'adminPostURL' => esc_url( admin_url( 'post.php' ) ),
		'adminPostNewURL' => esc_url( admin_url( 'post-new.php' ) ),
		'adminUploadURL' => esc_url( admin_url( 'upload.php' ) ),
		'adminAdminURL' => esc_url( admin_url( 'admin.php' ) ),
		'adminNavMenuURL' => esc_url( admin_url( 'nav-menus.php' ) ),
		'adminWidgetsURL' => esc_url( admin_url( 'widgets.php' ) ),
		'adminThemeURL' => esc_url( admin_url( 'themes.php' ) ),
		'adminFrontendSettingsPageURL' => esc_url( admin_url( 'themes.php?page=sse_frontend_settings' ) ),
		'adminRequiredSettingsPageURL' => esc_url( admin_url( 'themes.php?page=sse_required_settings_menu' ) ),
		'adminCSSSettingsPageURL' => esc_url( admin_url( 'themes.php?page=sse_css_settings_menu' ) ),
		'adminFontSettingsPageURL' => esc_url( admin_url( 'themes.php?page=sse_font_settings_menu' ) ),
		'adminFileEditorsPageURL' => esc_url( admin_url( 'themes.php?page=file_editors' ) ),
		'adminPixabayMediaFetcherPageURL' => esc_url( admin_url( 'upload.php?page=sse_pixabay_image_fetcher' ) ),
		'adminCustomizerURL' => esc_url( admin_url( 'customize.php' ) ),
		'assetsDirURL' => SSE_ASSETS_URL,
		'thirdDirURL' => SHAPESHIFTER_EXTENSIONS_THIRD_URL,
	) );
}

// Admin
if ( is_admin() ) {

	// General Methods
		wp_register_script( 
			'sse-general-methods', 
			SSE_ASSETS_URL . 'js/admin/admin-general-methods.js', 
			array( 'jquery', 'alpha-color-picker' ) 
		);

		// TinyMCE
			wp_register_script( 
				'sse-tinymce', 
				SSE_ASSETS_URL . 'js/admin/admin-tinymce.js', 
				array( 'jquery', 'jquery-ui-resizable', 'jquery-ui-sortable', 'jquery-ui-draggable', 'underscore', 'backbone', 'plupload', 'plupload-all' ) 
			);

			wp_register_script( 
				'sse-mce-button', 
				SSE_ASSETS_URL . 'js/admin/admin-mce-button.js', 
				array( 'sse-general-methods' ) 
			);

		// QuickTags
			wp_register_script(
				'sse-quicktags',
				SSE_ASSETS_URL . 'js/admin/admin-quicktags.js',
				array( 'jquery', 'jquery-ui-resizable', 'jquery-ui-sortable', 'jquery-ui-draggable', 'underscore', 'backbone', 'plupload', 'plupload-all' ),
				false,
				true
			);

	// Widgets form
		// Setting Box
		wp_register_script( 
			'sse-widget-settings', 
			SSE_ASSETS_URL . 'js/admin/widget/popup-setting-box.js', 
			array( 'sse-general-methods' ) 
		);

		// Slide Gallery
			wp_register_script( 
				'sse-admin-widget-slide-gallery', 
				SSE_ASSETS_URL . 'js/admin/widget/slide-gallery.js', 
				array( 'sse-general-methods' ) 
			);

	// Admin Pages Settings
		// Frontend Settings
			wp_register_script(
				'sse-admin-page-frontend-settings',
				SSE_ASSETS_URL . 'js/admin/page/frontend/seo.js',
				array( 'sse-general-methods' ) 
			);
			wp_register_script( 
				'sse-admin-page-setting-page-tab', 
				SSE_ASSETS_URL . 'js/admin/page/frontend/setting-tab.js', 
				array( 'sse-general-methods' ) 
			);
			wp_register_script( 
				'sse-admin-page-widget-areas', 
				SSE_ASSETS_URL . 'js/admin/page/frontend/widget-areas.js', 
				array( 'sse-general-methods' ) 
			);

		// Custom Fonts
			wp_register_script( 
				'sse-admin-custom-fonts', 
				SSE_ASSETS_URL . 'js/admin/page/admin-custom-fonts.js', 
				array( 'sse-general-methods' ) 
			);

		// Image Fetcher
			wp_register_script( 
				'sse-pixabay-media-fetcher',
				SSE_ASSETS_URL . 'js/admin/page/pixabay-media-fetcher.js',
				array( 'sse-general-methods', 'jquery-ui-resizable', 'jquery-ui-sortable', 'jquery-ui-draggable', 'underscore', 'backbone', 'plupload', 'plupload-all' )
			);

	// Meta Boxes
		wp_register_script( 
			'sse-metaboxes', 
			SSE_ASSETS_URL . 'js/admin/metabox/admin-meta-boxes.js', 
			array( 'sse-general-methods', 'alpha-color-picker' ) 
		);

		// Icons
			wp_register_script( 
				'sse-metabox-icons-preview', 
				SSE_ASSETS_URL . 'js/admin/metabox/icons/preview-icons.js',
				array( 'sse-metaboxes' ) 
			);

		// Subcontents
			wp_register_script( 
				'sse-metabox-subcontents', 
				SSE_ASSETS_URL . 'js/admin/metabox/subcontents/edit-table.js',
				array( 'sse-metaboxes' ) 
			);

		// SEO
			wp_register_script( 
				'sse-metabox-seo', 
				SSE_ASSETS_URL . 'js/admin/metabox/seo/jsonify.js',
				array( 'sse-metaboxes' ) 
			);

	// User Meta Notices
		wp_register_script( 
			'sse-user-meta-notifications-dismiss', 
			SSE_ASSETS_URL . 'js/admin/usermeta/user-meta-updates.js', 
			array( 'sse-general-methods' ) 
		);

	// Nav Menu
		wp_register_script( 
			'sse-nav-menu', 
			SSE_ASSETS_URL . 'js/admin/navmenu/nav-menu-edit.js',
			array( 'sse-general-methods' ) 
		);

}

// Customize Preview
if ( is_customize_preview() ) {

	// Theme Customizer
	wp_register_script(
		'sse-theme-customizer',
		SSE_ASSETS_URL . 'js/theme-customizer.js',
		array( 'jquery', 'customize-preview' ),
		null,
		true
	);

}