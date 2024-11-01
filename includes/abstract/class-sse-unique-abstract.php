<?php

if ( ! defined( 'ABSPATH' ) ) exit;

class SSE_Unique_Abstract {

	/**
	 * Statics
	**/
		/**
		 * Instance of the Class
		 * 
		 * @var object ShapeShifter_Data_Option
		**/
		protected static $instance = null;

	/**
	 * Settings
	**/
		/**
		 * Cloning is forbidden.
		 * @since 1.0.0
		 */
		public function __clone()
		{
			_doing_it_wrong( __FUNCTION__, esc_html__( 'DO NOT Clone.', 'shapeshifter' ), '1.0.0' );
		}

		/**
		 * Unserializing instances of this class is forbidden.
		 * @since 1.0.0
		 */
		public function __wakeup() {
			_doing_it_wrong( __FUNCTION__, esc_html__( 'DO NOT Unserialize', 'shapeshifter' ), '1.0.0' );
		}

}