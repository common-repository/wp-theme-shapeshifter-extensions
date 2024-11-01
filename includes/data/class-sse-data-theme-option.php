<?php

if ( ! defined( 'ABSPATH' ) ) exit;

class SSE_Data_Theme_Option extends SSE_Data_Option {

	/**
	 * Init
	**/
		/**
		 * Public Initializer
		 * @param string $id
		 * @param array  $defaults
		 * @return SSE_Data_Option
		**/
		public static function get_instance( $id, $defaults = null )
		{
			try {
				$instance = new Self( $id, $defaults );
			} catch ( SSE_Exception $e ) {
				if ( is_admin() ) sse()->add_notice_message( $e->getMessage() );
				return false;
			}
			return $instance;
		}

		/**
		 * Constructor
		 * @param string $id
		 * @param array  $defaults
		**/
		protected function __construct( $id, $defaults = null )
		{
			parent::__construct( $id, $defaults );
			$this->read();
		}

		/**
		 * Init
		 * @param string $id
		 * @param array  $defaults
		**/
		protected function init( $id, $defaults = null )
		{
			parent::init( $id, $defaults );
			$this->set_option_id( sse()->get_prefixed_theme_option_name( $id ) );
		}

}