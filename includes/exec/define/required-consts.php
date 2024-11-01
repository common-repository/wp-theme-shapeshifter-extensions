<?php


# Dir Path
	# Assets
		if( ! defined( 'SSE_ASSETS_DIR' ) ) define( 'SSE_ASSETS_DIR', SHAPESHIFTER_EXTENSIONS_DIR_PATH . 'assets/' );
		if( ! defined( 'SSE_ASSETS_URL' ) ) define( 'SSE_ASSETS_URL', SHAPESHIFTER_EXTENSIONS_DIR_URL . 'assets/' );
	# Includes
		if( ! defined( 'SHAPESHIFTER_EXTENSIONS_INCLUDES_DIR' ) ) define( 'SHAPESHIFTER_EXTENSIONS_INCLUDES_DIR', SHAPESHIFTER_EXTENSIONS_DIR_PATH . 'includes/' );
		if( ! defined( 'SHAPESHIFTER_EXTENSIONS_INCLUDES_URL' ) ) define( 'SHAPESHIFTER_EXTENSIONS_INCLUDES_URL', SHAPESHIFTER_EXTENSIONS_DIR_URL . 'includes/' );
	# 3rd
		if( ! defined( 'SHAPESHIFTER_EXTENSIONS_THIRD_DIR' ) ) define( 'SHAPESHIFTER_EXTENSIONS_THIRD_DIR', SHAPESHIFTER_EXTENSIONS_INCLUDES_DIR . '3rd/' );
		if( ! defined( 'SHAPESHIFTER_EXTENSIONS_THIRD_URL' ) ) define( 'SHAPESHIFTER_EXTENSIONS_THIRD_URL', SHAPESHIFTER_EXTENSIONS_INCLUDES_URL . '3rd/' );
	# Templates
		if( ! defined( 'SHAPESHIFTER_EXTENSIONS_TEMPLATES_DIR' ) ) define( 'SHAPESHIFTER_EXTENSIONS_TEMPLATES_DIR', SHAPESHIFTER_EXTENSIONS_DIR_PATH . 'templates/' );

# Prefixes
	# Prefix
		if( ! defined( 'SHAPESHIFTER_EXTENSIONS_PREFIX' ) ) define( 'SHAPESHIFTER_EXTENSIONS_PREFIX', 'shapeshifter-' );
	# Options
		if( ! defined( 'SHAPESHIFTER_EXTENSIONS_OPTION' ) ) define( 'SHAPESHIFTER_EXTENSIONS_OPTION', 'shapeshifter_option_' );
	# Post Meta
		if( ! defined( 'SHAPESHIFTER_EXTENSIONS_POST_META' ) ) define( 'SHAPESHIFTER_EXTENSIONS_POST_META', '_shapeshifter_extensions_' );

# Theme Infos
	$shapeshifter_extension_theme = wp_get_theme();
		if( ! defined( 'SSE_THEME_NAME' ) ) define( 'SSE_THEME_NAME', $shapeshifter_extension_theme['Name'] );
		if( ! defined( 'SSE_THEME_URI' ) ) define( 'SSE_THEME_URI', $shapeshifter_extension_theme['ThemeURI'] );
		if( ! defined( 'SSE_THEME_DESCRIPTION' ) ) define( 'SSE_THEME_DESCRIPTION', $shapeshifter_extension_theme['Description'] );
		if( ! defined( 'SSE_THEME_AUTHOR' ) ) define( 'SSE_THEME_AUTHOR', $shapeshifter_extension_theme['Author'] );
		if( ! defined( 'SSE_THEME_AUTHOR_URI' ) ) define( 'SSE_THEME_AUTHOR_URI', $shapeshifter_extension_theme['AuthorURI'] );
		if( ! defined( 'SSE_THEME_VERSION' ) ) define( 'SSE_THEME_VERSION', $shapeshifter_extension_theme['Version'] );
		if( ! defined( 'SSE_THEME_TEMPLATE' ) ) define( 'SSE_THEME_TEMPLATE', $shapeshifter_extension_theme['Template'] );
	unset( $shapeshifter_extension_theme );

# Check if is Child Theme
	if( ! defined( 'IS_CHILD_THEME' ) ) define( 'IS_CHILD_THEME', ( ( SSE_THEME_NAME !== 'ShapeShifter' ) ? true : false ) );

# Theme
	# Theme Root
		if( ! defined( 'SSE_THEME_ROOT_URI' ) ) define( 'SSE_THEME_ROOT_URI', get_template_directory_uri() );
		if( ! defined( 'SSE_THEME_ROOT_DIR' ) ) define( 'SSE_THEME_ROOT_DIR', get_template_directory() );
	# Key
		if( ! defined( 'SSE_THEME_KEY' ) ) define( 'SSE_THEME_KEY', str_replace( array( ' ', '-' ), '_', strtolower( SSE_THEME_NAME ) ) );
	# Prefix
		if( ! defined( 'SSE_THEME_PREFIX' ) ) define( 'SSE_THEME_PREFIX', str_replace( array( ' ', '-' ), '_', strtolower( SSE_THEME_NAME ) ) . '-' );
	# Options Prefix
		if( ! defined( 'SSE_THEME_OPTIONS' ) ) define( 'SSE_THEME_OPTIONS', str_replace( array( ' ', '-' ), '_', SSE_THEME_PREFIX ) );
	# Post Meta Prefix
		if( ! defined( 'SSE_THEME_POST_META' ) ) define( 'SSE_THEME_POST_META', '_' . str_replace( array( ' ', '-' ), '_', SSE_THEME_PREFIX ) );
	# Old Options Prefix
		if( ! defined( 'SSE_THEME_OLD_OPTIONS' ) ) define( 'SSE_THEME_OLD_OPTIONS', str_replace( array( ' ', '-' ), '_', SSE_THEME_PREFIX ) . 'option_' );
	# Old Post Meta Prefix
		if( ! defined( 'SSE_THEME_OLD_POST_META' ) ) define( 'SSE_THEME_OLD_POST_META', '_' . str_replace( array( ' ', '-' ), '_', SSE_THEME_PREFIX ) . 'post_meta_' );

	# DIR URL
		$theme_shapeshifter_root_dir = WP_CONTENT_DIR . '/themes/shapeshifter';
		$theme_shapeshifter_root_uri = get_theme_root_uri() . '/shapeshifter';
		if( ! defined( 'SHAPESHIFTER_THEME_ROOT_DIR' ) ) define( 'SHAPESHIFTER_THEME_ROOT_DIR', $theme_shapeshifter_root_dir );
		if( ! defined( 'SHAPESHIFTER_THEME_ROOT_URI' ) ) define( 'SHAPESHIFTER_THEME_ROOT_URI', $theme_shapeshifter_root_uri );
	# 3rd Directory
		if( ! defined( 'SHAPESHIFTER_THEME_THIRD_DIR' ) ) define( 'SHAPESHIFTER_THEME_THIRD_DIR', SHAPESHIFTER_THEME_ROOT_DIR . '/includes/3rd/' );
		if( ! defined( 'SHAPESHIFTER_THEME_THIRD_DIR_URI' ) ) define( 'SHAPESHIFTER_THEME_THIRD_DIR_URI', SHAPESHIFTER_THEME_ROOT_URI . '/includes/3rd/' );

# Site Data
	# Site Name
		if( ! defined( 'SITE_NAME' ) ) define( 'SITE_NAME', get_bloginfo( 'name' ) );
	# Site Deescription
		if( ! defined( 'SITE_DESCRIPTION' ) ) define( 'SITE_DESCRIPTION', get_bloginfo( 'description' ) );
	# Site Home URL
		if( ! defined( 'SITE_URL' ) ) define( 'SITE_URL', esc_url( home_url() ) );

