<?php
// 一般設定
register_setting( SSE_THEME_OPTIONS, SHAPESHIFTER_EXTENSIONS_OPTION . 'general', array( 'SSE_Admin_Page_Manager', 'sanitize_general' ) );

// 標準設定を解除
register_setting( SSE_THEME_OPTIONS, SHAPESHIFTER_EXTENSIONS_OPTION . 'not_display_post_formats', array( 'SSE_Admin_Page_Manager', 'sanitize_not_display_post_formats' ) );

// 標準設定を解除
register_setting( SSE_THEME_OPTIONS, SHAPESHIFTER_EXTENSIONS_OPTION . 'remove_action', array( 'SSE_Admin_Page_Manager', 'sanitize_remove_actions' ) );

// 自動挿入設定
register_setting( SSE_THEME_OPTIONS, SHAPESHIFTER_EXTENSIONS_OPTION . 'auto_insert', array( 'SSE_Admin_Page_Manager', 'sanitize_auto_inserts' ) );

// 速度調節設定
register_setting( SSE_THEME_OPTIONS, SHAPESHIFTER_EXTENSIONS_OPTION . 'speed_adjust', array( 'SSE_Admin_Page_Manager', 'sanitize_speed_adjust' ) );

// ウィジェットエリア設定
register_setting( SSE_THEME_OPTIONS, SSE_THEME_OPTIONS . 'widget_areas_general', array( 'SSE_Admin_Page_Manager', 'sanitize_widget_areas_generals' ) );
register_setting( SSE_THEME_OPTIONS, SSE_THEME_OPTIONS . 'widget_areas', array( 'SSE_Admin_Page_Manager', 'sanitize_widget_areas' ) );

// SEO設定
register_setting( SSE_THEME_OPTIONS, SHAPESHIFTER_EXTENSIONS_OPTION . 'seo', array( 'SSE_Admin_Page_Manager', 'sanitize_seos' ) );

// その他の設定
register_setting( SSE_THEME_OPTIONS, SHAPESHIFTER_EXTENSIONS_OPTION . 'others', array( 'SSE_Admin_Page_Manager', 'sanitize_others' ) );

// デバッグモード設定
register_setting( SSE_THEME_OPTIONS, SHAPESHIFTER_EXTENSIONS_OPTION . 'debug_mode', array( 'SSE_Admin_Page_Manager', 'sanitize_debug_modes' ) );

?>