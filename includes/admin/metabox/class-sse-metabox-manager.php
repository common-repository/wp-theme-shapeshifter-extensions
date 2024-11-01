<?php
if ( ! defined( 'ABSPATH' ) ) exit;

/**
 * 
 */
class SSE_Metabox_Manager extends SSE_Unique_Abstract
{
	
	/**
	 * Static
	**/
		/**
		 * Instance of this Class
		 * 
		 * @var $instance
		**/
		protected static $instance = null;

	/**
	 * Properties
	**/
		/**
		 * FontAwesome
		 * @var SSE_Metabox_FontAwesome
		**/
		protected $fontawesome;

		/**
		 * Get FontAwesome Metabox
		 * @return SSE_Metabox_FontAwesome
		**/
		public function get_fontawesome()
		{
			return $this->fontawesome;
		}

		/**
		 * FontAwesome
		 * @var SSE_Metabox_Subcontents
		**/
		protected $subcontents;

		/**
		 * Get Subcontents Metabox
		 * @return SSE_Metabox_Subcontents
		**/
		public function get_subcontents()
		{
			return $this->subcontents;
		}

	/**
	 * Init
	**/
		/**
		 * Public Initializer
		**/
		public static function get_instance() 
		{
			if ( null === self::$instance ) self::$instance = new Self();
			return self::$instance;
		}

		/**
		 * Construct
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
			$this->fontawesome = SSE_Metabox_FontAwesome::get_instance();
			$this->subcontents = SSE_Metabox_Subcontents::get_instance();
			$this->seo         = SSE_Metabox_SEO::get_instance();
		}

		/**
		 * Init hooks
		**/
		protected function init_hooks()
		{

		}



}