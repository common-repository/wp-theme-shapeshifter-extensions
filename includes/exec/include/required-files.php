<?php

// 3rd
	// Mobile Detect
	if ( ! class_exists( 'Mobile_Detect' ) ) include_once( SHAPESHIFTER_EXTENSIONS_THIRD_DIR . 'Mobile-Detect/Mobile_Detect.php' );
	// Alpha Color Picker

	// ShapeShifter_Theme_Customizer_Settings
		if ( ! class_exists( 'ShapeShifter_Theme_Customizer_Settings' ) )
			include_once( SHAPESHIFTER_EXTENSIONS_THIRD_DIR . 'nora-custom-theme-customizer-settings/nora-custom-theme-customizer-settings.php' );

// Deprecated
	// Deprecated Option List
	//include_once( SHAPESHIFTER_EXTENSIONS_INCLUDES_DIR . 'deprecated/class-sse-deprecated-option-list.php' );
	// Deprecated Manager Abstract
	include_once( SHAPESHIFTER_EXTENSIONS_INCLUDES_DIR . 'abstract/class-sse-deprecated-abstract.php' );
	// Deprecated Manager
	include_once( SHAPESHIFTER_EXTENSIONS_INCLUDES_DIR . 'deprecated/class-sse-deprecated-manager.php' );

// Functions
	// Generals
	include_once( SHAPESHIFTER_EXTENSIONS_INCLUDES_DIR . 'functions/functions-general.php' );
	// Frontend
	include_once( SHAPESHIFTER_EXTENSIONS_INCLUDES_DIR . 'functions/functions-frontend.php' );
	// Filter Frontend
	//include_once( SHAPESHIFTER_EXTENSIONS_INCLUDES_DIR . 'functions/filters/filter-functions-frontend.php' );

// Methods
	// Array Methods ( Static Methods )
	include_once( SHAPESHIFTER_EXTENSIONS_INCLUDES_DIR . 'methods/class-sse-array-methods.php' );

// Abstract
	// Data
	include_once( SHAPESHIFTER_EXTENSIONS_INCLUDES_DIR . 'abstract/class-sse-data-abstract.php' );
	// Data
	include_once( SHAPESHIFTER_EXTENSIONS_INCLUDES_DIR . 'abstract/class-sse-data-crud-abstract.php' );
	// Unique
	include_once( SHAPESHIFTER_EXTENSIONS_INCLUDES_DIR . 'abstract/class-sse-unique-abstract.php' );
	// Data Manager
	include_once( SHAPESHIFTER_EXTENSIONS_INCLUDES_DIR . 'abstract/class-sse-data-manager-abstract.php' );

// Data
	// Theme Mod Manager
	include_once( SHAPESHIFTER_EXTENSIONS_INCLUDES_DIR . 'data/class-sse-theme-mod-manager.php' );
	// Data Option
	include_once( SHAPESHIFTER_EXTENSIONS_INCLUDES_DIR . 'data/class-sse-data-option.php' );
	// Data Theme Option
	include_once( SHAPESHIFTER_EXTENSIONS_INCLUDES_DIR . 'data/class-sse-data-theme-option.php' );
	// Option Manager
	include_once( SHAPESHIFTER_EXTENSIONS_INCLUDES_DIR . 'data/class-sse-option-manager.php' );
	// Data Post Meta
	include_once( SHAPESHIFTER_EXTENSIONS_INCLUDES_DIR . 'data/class-sse-data-post-meta.php' );

// Notification
	// Notification Manager
	include_once( SHAPESHIFTER_EXTENSIONS_INCLUDES_DIR . 'notification/class-sse-notification-manager.php' );

// General
	// Shortcode Manager
	include_once( SHAPESHIFTER_EXTENSIONS_INCLUDES_DIR . 'shortcode/class-sse-shortcode-manager.php' );

// Walker
	// Nav Menu
	include_once( SHAPESHIFTER_EXTENSIONS_INCLUDES_DIR . 'walkers/class-sse-walker-nav-menu.php' );

// Widgets
	// Widget Manager
	include_once( SHAPESHIFTER_EXTENSIONS_INCLUDES_DIR . 'widget/class-sse-widget-manager.php' );

	// Widget Base
	include_once( SHAPESHIFTER_EXTENSIONS_INCLUDES_DIR . 'widget/class-sse-widget-abstract.php' );
	// Text
	include_once( SHAPESHIFTER_EXTENSIONS_INCLUDES_DIR . 'widget/class-sse-widget-text.php' );
	// New Entries
	include_once( SHAPESHIFTER_EXTENSIONS_INCLUDES_DIR . 'widget/class-sse-widget-new-entries.php' );
	// Related Entries
	include_once( SHAPESHIFTER_EXTENSIONS_INCLUDES_DIR . 'widget/class-sse-widget-related-entries.php' );
	// Popular Entries
	include_once( SHAPESHIFTER_EXTENSIONS_INCLUDES_DIR . 'widget/class-sse-widget-popular-entries.php' );
	// Switch
	include_once( SHAPESHIFTER_EXTENSIONS_INCLUDES_DIR . 'widget/class-sse-widget-entries-channels.php' );
	// TOC
	include_once( SHAPESHIFTER_EXTENSIONS_INCLUDES_DIR . 'widget/class-sse-widget-toc.php' );
	// SNS Share Icons
	include_once( SHAPESHIFTER_EXTENSIONS_INCLUDES_DIR . 'widget/class-sse-widget-sns-share-icons.php' );
	// SNS Share Buttons
	include_once( SHAPESHIFTER_EXTENSIONS_INCLUDES_DIR . 'widget/class-sse-widget-sns-share-buttons.php' );
	// Feed Reader
	include_once( SHAPESHIFTER_EXTENSIONS_INCLUDES_DIR . 'widget/class-sse-widget-feed-reader.php' );
	// Download Link
	include_once( SHAPESHIFTER_EXTENSIONS_INCLUDES_DIR . 'widget/class-sse-widget-download-link.php' );
	// Slide Gallaries
	include_once( SHAPESHIFTER_EXTENSIONS_INCLUDES_DIR . 'widget/class-sse-widget-slide-gallery.php' );

// Widget Areas
	// Widget Area Manager
	include_once( SHAPESHIFTER_EXTENSIONS_INCLUDES_DIR . 'widget-area/class-sse-widget-area-manager.php' );

// Style
	// Style Manager
	include_once( SHAPESHIFTER_EXTENSIONS_INCLUDES_DIR . 'style/class-sse-style-manager.php' );

// Page View
	// Page View Counter
	include_once( SHAPESHIFTER_EXTENSIONS_INCLUDES_DIR . 'page-view-counter/class-shapeshifter-page-view-count.php' );

// Frontend
	// Frontend Manager
	include_once( SHAPESHIFTER_EXTENSIONS_INCLUDES_DIR . 'frontend/class-sse-frontend-manager.php' );
	// Filter Manager
	include_once( SHAPESHIFTER_EXTENSIONS_INCLUDES_DIR . 'frontend/class-sse-frontend-filter-manager.php' );
	// Rendering Methods
	include_once( SHAPESHIFTER_EXTENSIONS_INCLUDES_DIR . 'frontend/class-sse-frontend-rendering-methods.php' );
	// Rendering Manager
	include_once( SHAPESHIFTER_EXTENSIONS_INCLUDES_DIR . 'frontend/class-sse-rendering-manager.php' );

// Walker

// Admin
if ( is_admin() ) {

// Methods
	// Sanitize Methods ( Static Methods )
	include_once( SHAPESHIFTER_EXTENSIONS_INCLUDES_DIR . 'methods/class-sse-sanitize-methods.php' );
	// Filesystem Methods
	include_once( SHAPESHIFTER_EXTENSIONS_INCLUDES_DIR . 'methods/class-sse-filesystem-methods.php' );

// Walkder
	// Nav Menu Edit
	//include_once( SHAPESHIFTER_EXTENSIONS_INCLUDES_DIR . 'walkers/class-sse-walker-nav-menu-edit.php' );

// Class
	// TinyMCE Manager
	include_once( SHAPESHIFTER_EXTENSIONS_INCLUDES_DIR . 'tinymce/class-sse-tinymce-manager.php' );

	// Admin Pages
	include_once( SHAPESHIFTER_EXTENSIONS_INCLUDES_DIR . 'admin/admin-page/class-sse-admin-page-manager.php' );

		// Abstract
		include_once( SHAPESHIFTER_EXTENSIONS_INCLUDES_DIR . 'admin/admin-page/class-sse-page-abstract.php' );

		// Frontend Settings
		include_once( SHAPESHIFTER_EXTENSIONS_INCLUDES_DIR . 'admin/admin-page/class-sse-page-frontend-settings.php' );

		// Required Settings
		include_once( SHAPESHIFTER_EXTENSIONS_INCLUDES_DIR . 'admin/admin-page/class-sse-page-required-settings.php' );

		// Frontend Settings
		include_once( SHAPESHIFTER_EXTENSIONS_INCLUDES_DIR . 'admin/admin-page/class-sse-page-frontend-settings.php' );

		// Font Settings
		include_once( SHAPESHIFTER_EXTENSIONS_INCLUDES_DIR . 'admin/admin-page/class-sse-page-custom-font-settings.php' );

		// Custom CSS
		include_once( SHAPESHIFTER_EXTENSIONS_INCLUDES_DIR . 'admin/admin-page/class-sse-page-custom-css-settings.php' );

		// Pixabay Image Fetcher
		include_once( SHAPESHIFTER_EXTENSIONS_INCLUDES_DIR . 'admin/admin-page/class-sse-page-pixabay-media-fetcher.php' );

	// User Meta
	include_once( SHAPESHIFTER_EXTENSIONS_INCLUDES_DIR . 'admin/class-sse-user-meta-manager.php' );

	// Nav Menu Edit
	include_once( SHAPESHIFTER_EXTENSIONS_INCLUDES_DIR . 'admin/class-sse-nav-menu-editor.php' );

	// Taxonomies Mods
	include_once( SHAPESHIFTER_EXTENSIONS_INCLUDES_DIR . 'admin/class-sse-taxonomy-editor.php' );

	// Dashboard
	include_once( SHAPESHIFTER_EXTENSIONS_INCLUDES_DIR . 'admin/class-sse-dashboard-manager.php' );

	// Notification Manager
	//include_once( SHAPESHIFTER_EXTENSIONS_INCLUDES_DIR . 'notification/class-sse-notification-manager.php' );

// Meta box
	// Meta Box Manager
	include_once( SHAPESHIFTER_EXTENSIONS_INCLUDES_DIR . 'admin/metabox/class-sse-metabox-manager.php' );

		// Abstract
		include_once( SHAPESHIFTER_EXTENSIONS_INCLUDES_DIR . 'abstract/class-sse-metabox-abstract.php' );

		// FontAwesome
		include_once( SHAPESHIFTER_EXTENSIONS_INCLUDES_DIR . 'admin/metabox/class-sse-metabox-fontawesome.php' );

		// Subcontents
		include_once( SHAPESHIFTER_EXTENSIONS_INCLUDES_DIR . 'admin/metabox/class-sse-metabox-subcontents.php' );

		// Subcontents
		include_once( SHAPESHIFTER_EXTENSIONS_INCLUDES_DIR . 'admin/metabox/class-sse-metabox-seo.php' );

	// Icon Handler
	include_once( SHAPESHIFTER_EXTENSIONS_INCLUDES_DIR . 'admin/class-sse-icon-manager.php' );

	// Admin Main
	include_once( SHAPESHIFTER_EXTENSIONS_INCLUDES_DIR . 'admin/class-sse-admin-manager.php' );


}

// Theme Customizer
if ( is_customize_preview() ) {

// 3rd
	// Customize_Alpha_Color_Control
	include_once( SHAPESHIFTER_EXTENSIONS_THIRD_DIR . 'customizer/alpha-color-picker/alpha-color-picker.php' );

	// Customize_Multi_Color_Control
	include_once( SHAPESHIFTER_EXTENSIONS_THIRD_DIR . 'customizer/multi-color-picker/multi-color-picker.php' );

// Methods
	// Sanitize Methods ( Static Methods )
	include_once( SHAPESHIFTER_EXTENSIONS_INCLUDES_DIR . 'methods/class-sse-sanitize-methods.php' );

// Class
	// Theme Customizer
	include_once( SHAPESHIFTER_EXTENSIONS_INCLUDES_DIR . 'theme-customizer/class-sse-theme-customizer.php' );

}


