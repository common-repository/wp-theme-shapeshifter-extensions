<?php

if ( ! defined( 'ABSPATH' ) ) {
	return;
}

/**
 * Data formats
**/
class SSE_Data_CURD_Abstract extends SSE_Data_Abstract {

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
	 * Properties
	**/
		/**
		 * ID for this object.
		 * @var [int|string]
		 */
		protected $id = 0;

		/**
		 * Attributes for this object.
		 * @var [array]
		 */
		protected $attributes = array(
			'id'          => 0, // can be string
			'object_read' => false, // This is false until the object is read from the DB.
			'data_type'   => 'data', // like 'data' 'option' 'post' 'meta'
			'object_type' => '', // like 'single' 'downloadable'
		);

		/**
		 * Data
		 * @var array
		 */
		protected $data = array();

		/**
		 * Default Data.
		 * @var array
		 */
		protected $defaults = array();

	/**
	 * Init
	**/
		/**
		 * Public Initializer
		 * @param int|string $id
		 * @param mixed      $defaults
		 * @return string|SSE_Data_CURD_Abstract
		**/
		public static function get_instance( $id, $defaults = null )
		{
			try {
				$instance = new Self( $id, $defaults );
			} catch( SSE_Exception $e ) {
				if ( is_admin() ) sse()->get_notice_message( esc_html__( 'Failed to init SSE data.', ShapeShifter_Extensions::TEXTDOMAIN ) );
				return $e->getMessage();
			} catch( ShapeShifter_Exception $e ) {
				if ( is_admin() ) sse()->get_notice_message( esc_html__( 'Failed to init SS data.', ShapeShifter_Extensions::TEXTDOMAIN ) );
				return $e->getMessage();
			}
			return $instance;
		}

		/**
		 * Public Initializer
		 * @param int|string $id
		 * @param mixed      $defaults
		 * @throws SSE_Exception
		**/
		protected function __construct( $id, $defaults = null )
		{
			if ( ! is_int( $id ) && ! is_string( $id ) 
				|| empty( $id )
			) {
				throw new SSE_Exception( esc_html__( 'Wrong ID.', 'shapeshifter' ) );
			}
			parent::__construct( $id, $defaults );
		}

		/**
		 * Public Initializer
		 * @param int|string $id
		 * @param mixed      $defaults
		 * @throws SSE_Exception
		**/
		protected function init( $id, $defaults = null )
		{
			parent::init( $id, $defaults );
		}

	/**
	 * Create
	**/
		public function create() {}

	/**
	 * Read
	**/
		public function read() {}

	/**
	 * Update
	**/
		public function update() {}

	/**
	 * Delete
	**/
		public function delete() {}

	/**
	 * Format
	**/

	/**
	 * JSON
	**/
		/**
		 * Format
		 * @return int|string
		**/
		public function maybe_json_encode( $value )
		{

			if ( is_array( $value ) ) {
				$maybe_json_encoded = json_encode( $value, JSON_UNESCAPED_UNICODE );
				if ( false !== $maybe_json_encoded ) {
					return $maybe_json_encoded;
				}
			}

			return $value;

		}

		/**
		 * Format
		 * @param  mixed $value
		 * @return int|array
		**/
		public function maybe_json_decode( $value )
		{

			if ( is_string( $value ) && '' !== $value ) {
				$maybe_json_decoded = json_decode( $value, true );
				if ( null !== $maybe_json_decoded ) {
					return $maybe_json_decoded;
				}
			} elseif ( is_string( $value ) && '' === $value ) {
				$value = array();
			} elseif ( null === $value ) {
				$value = array();
			}

			return $value;

		}

	/**
	 * Setters
	**/
		/**
		 * Set Prop.
		 * @param string $key
		 * @param string $value
		 * @return bool
		 */
		public function set_props( $data )
		{

			if ( ! is_array( $data ) ) {
				return false;
			}

			foreach ( $data as $data_key => $data_value ) {
				$this->set_prop( $data_value, $data_key );
			}

			return true;

		}

		/**
		 * Set Prop.
		 * @param string $key
		 * @param string $value
		 * @return bool
		 */
		public function set_prop( $value, $key = '' )
		{
			if ( '' === $key ) {
				array_push( $this->data, $value );
			} else {
				$this->data[ $key ] = $value;
			}

			return true;
		}



}


