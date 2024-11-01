<?php

if ( ! defined( 'ABSPATH' ) ) {
	return;
}

if ( ! class_exists( 'SSE_Data_Abstract' ) ) {
/**
 * Data formats
**/
class SSE_Data_Abstract extends ShapeShifter_Data_Abstract {

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
		 * @throws SSE_Exception
		**/
		protected function init( $id, $defaults = null )
		{
			parent::init( $id, $defaults );
		}

	/**
	 * Getters
	**/
		/**
		 * Get ID
		 * @return int|string
		**/
		//public function get_id()

		/**
		 * Get Defaults
		 * @return array
		**/
		//public function get_defaults()

		/**
		 * Get Attributes
		 * @param string $key : Default ''
		 * @uses array $this->attributes
		 * @return bool|mixed : Returns false for errors.
		**/
		//public function get_attributes( $key = '' )

		/**
		 * Get Data
		 * @uses array $this->data
		 * @return array
		**/
		//public function get_data()

		/**
		 * Get Prop
		 * @param string $key : Default ''
		 * @uses array $this->data
		 * @return bool|mixed : Returns false for errors.
		**/
		//public function get_prop( $key = '' )

	/**
	 * Setters
	**/
		/**
		 * Set ID
		 * @param int|string $id
		 * @return bool
		**/
		//public function set_id( $id )

		/**
		 * Set Defaults
		 * @param array $defaults : Default array()
		 * @return bool
		**/
		//public function set_defaults( $defaults = array() )

		/**
		 * Set Attributes
		 * @param array $attributes : Default array()
		 * @return bool
		**/
		//public function set_attributes( $attributes = array() )

		/**
		 * Set Attribute Value
		 * @param string $key
		 * @param mixed  $value
		 * @throws ShapeShifter_Exception
		 * @return bool
		**/
		//public function set_attribute( $key, $value = null )

		/**
		 * Set Prop.
		 * @param string $key
		 * @param string $value
		 * @return bool
		 */
		//public function set_props( $data )

		/**
		 * Set Prop.
		 * @param string $key
		 * @param string $value
		 * @return bool
		 */
		//public function set_prop( $value, $key = '' )

	/**
	 * Delete
	**/
		/**
		 * Delete Prop.
		 * @param string $key
		 * @param string $value
		 * @return bool
		 */
		//public function delete_prop( $key = '', $value = '' )

}

}



