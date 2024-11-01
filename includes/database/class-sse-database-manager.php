<?php
if ( ! defined( 'ABSPATH' ) ) exit;
class SSE_Database_Manager {
	
	/**
	 * Consts
	**/

	/**
	 * Static
	**/
		/**
		 * Current Version
		 * @var string Version Format
		**/
		protected static $instance = null;

	/**
	 * Properties
	**/
		/**
		 * Current Version
		 * @var string Version Format
		**/
		protected $current_version = '1.0.0';

		/**
		 * Current Version
		 * @var string Version Format
		**/
		protected $versions = array();

		/**
		 * Current Version
		 * @var string Version Format
		**/
		protected $handlers = array();


	/**
	 * Init
	**/
		/**
		 * Public Initializer
		 * @return SSE_Deprecated_Manager
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
		}

	/**
	 * Consts
	**/



}