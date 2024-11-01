<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

class SSE_Page_Required_Settings extends SSE_Page_Abstract {
	
	/**
	 * Static
	**/
		protected static $instance;

	/**
	 * Properties
	**/
		/**
		 * Menu Slut
		 * @var string
		**/
		protected $menu_slug = 'required_settings_menu';

		private $options = array();
	/**
	 * Init
	**/
		/**
		 * Public Initialier
		 * @return SSE_Taxonomy_Editor
		**/
		public static function get_instance()
		{
			if ( null === self::$instance ) self::$instance = new Self();
			return self::$instance;
		}

		/**
		 * Constructor
		**/
		protected function __construct() {
			$this->init();
			$this->init_hooks();
		}

		/**
		 * Init
		**/
		protected function init()
		{
			$this->page_title = esc_html__( "Recommended Settings", ShapeShifter_Extensions::TEXTDOMAIN );
			$this->menu_title = esc_html__( "Recommended Settings", ShapeShifter_Extensions::TEXTDOMAIN );
		}

		/**
		 * 
		**/
		public function render() {

			$options = $this->options;

			wp_enqueue_media();
			
			$options['blogname'] = get_option( 'blogname' );
			$options['blogdescription'] = get_option( 'blogdescription' );
			$options['permalink_structure'] = get_option( 'permalink_structure' );
			$options['category_base'] = get_option( 'category_base' );

			$pattern = array(
				0 => '',
				1 => '/%year%/%monthnum%/%day%/%postname%/',
				2 => '/%year%/%monthnum%/%postname%/',
				3 => '/archives/%post_id%',
				4 => '/%postname%/',
			);

		}

}

