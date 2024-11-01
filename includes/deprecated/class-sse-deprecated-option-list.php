<?php

if ( ! defined( 'ABSPATH' ) ) exit;

class SSE_Deprecated_Option_List {
	/**
	 * Options
	 * @var array
	**/
	public static $options = array(
		SSE_THEME_OPTIONS . 'general' => array( 
			'name' => ShapeShifter_Extensions::OPTION_PREFIX . 'general',
			'data' => array(
			),
		),
		SSE_THEME_OPTIONS . 'not_display_post_formats' => array( 
			'name' => ShapeShifter_Extensions::OPTION_PREFIX . 'not_display_post_formats',
		),
		SSE_THEME_OPTIONS . 'remove_action' => array( 
			'name' => ShapeShifter_Extensions::OPTION_PREFIX . 'remove_action',
		),
		SSE_THEME_OPTIONS . 'speed_adjust' => array( 
			'name' => ShapeShifter_Extensions::OPTION_PREFIX . 'speed_adjust',
		),
		SSE_THEME_OPTIONS . 'widget_areas_general' => array( 
			'name' => ShapeShifter_Extensions::OPTION_PREFIX . 'widget_areas_general',
		),
		SSE_THEME_OPTIONS . 'widget_areas' => array( 
			'name' => ShapeShifter_Extensions::OPTION_PREFIX . 'widget_areas',
		),
		SSE_THEME_OPTIONS . 'seo' => array( 
			'name' => ShapeShifter_Extensions::OPTION_PREFIX . 'seo',
		),
		SSE_THEME_OPTIONS . 'auto_insert' => array( 
			'name' => ShapeShifter_Extensions::OPTION_PREFIX . 'auto_insert',
		),
		SSE_THEME_OPTIONS . 'others' => array( 
			'name' => ShapeShifter_Extensions::OPTION_PREFIX . 'others',
		),
		SSE_THEME_OPTIONS . 'fonts_general' => array( 
			'name' => ShapeShifter_Extensions::OPTION_PREFIX . 'fonts_general',
		),
	);
}
