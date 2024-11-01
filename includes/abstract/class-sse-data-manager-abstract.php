<?php

if ( ! defined( 'ABSPATH' ) ) exit;

class SSE_Data_Manager_Abstract extends SSE_Unique_Abstract {

	/**
	 * Static
	**/

	/**
	 * Properties
	**/
		/**
		 * Data
		 * @var array
		**/
		protected $data = array();

		/**
		 * Data
		 * @return SSE_Data_Abstract
		**/
		public function get_data( $key )
		{
			if ( ! is_string( $key )
				|| '' === $key
				|| ! isset( $this->data[ $key ] ) 
			) {
				return false;
			}
			return $this->data[ $key ];
		}

}
