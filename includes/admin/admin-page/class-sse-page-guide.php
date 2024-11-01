<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

class SSE_Page_Guide extends SSE_Page_Abstract {

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
		protected $menu_slug = 'how_to_use_menu';

		/**
		 * Form Template 
		 * @var string
		**/
		protected $form_template = 'view/form-frontend-settings.php';

	/**
	 * Init
	**/
		/**
		 * Public Initializer
		 * @return SSE_Admin_Page_Manager
		**/
		public static function get_instance()
		{
			if ( null === self::$instance ) self::$instance = new Self();
			return self::$instance;
		}

		/**
		 * Constructor
		**/
		protected function __construct()
		{
			$this->init();
			$this->init_hooks();
		}
		
		/**
		 * Init
		**/
		protected function init()
		{
			$this->page_title = esc_html__( "How to use Theme 'ShapeShifter'", ShapeShifter_Extensions::TEXTDOMAIN );
			$this->menu_title = esc_html__( "How to use Theme 'ShapeShifter'", ShapeShifter_Extensions::TEXTDOMAIN );
		}

}

