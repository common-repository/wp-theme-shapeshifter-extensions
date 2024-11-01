<?php
if ( ! defined( 'ABSPATH' ) ) exit;

class SSE_Data_Option extends SSE_Data_CURD_Abstract {

	/**
	 * Properties
	**/
		/**
		 * Attributes for this object.
		 * @var string
		 */
		protected $option_id = '';

		/**
		 * Get Option ID.
		 * @var string
		 */
		public function set_option_id( string $option_id )
		{
			if ( '' !== $option_id ) {
				$this->option_id = $option_id;
				return true;
			}
			return false;
		}

		/**
		 * Get Option ID.
		 * @var string
		 */
		public function get_option_id()
		{
			return $this->option_id;
		}

		/**
		 * Attributes for this object.
		 * @var [array]
		 */
		protected $attributes = array(
			'id'          => 0, // can be string
			'object_read' => false, // This is false until the object is read from the DB.
			'data_type'   => 'option', // like 'data' 'option' 'post' 'meta'
			'object_type' => '', // like 'single' 'downloadable'
		);

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
			$this->set_option_id( sse()->get_prefixed_option_name( $id ) );
		}

	/**
	 * Create
	**/
		/**
		 * Create Option from $this->data
		**/
		public function create()
		{
			$this->data = wp_parse_args( $this->data, SSE_Option_Manager::$defaults[ $this->get_id() ] );
			$value = $this->maybe_json_encode( $this->data );
			if ( ! is_int( $value ) || is_string( $value ) || is_bool( $value ) ) {
				$this->delete();
				add_option( $this->get_option_id(), apply_filters( sse()->get_prefixed_filter_hook( 'sanitize_option_value' ), $value, $this->get_id() ) );
			}
		}

	/**
	 * Read
	**/
		/**
		 * Read Saved Data and set to $this->data
		**/
		public function read()
		{
			$this->data = $this->maybe_json_decode( get_option( $this->get_option_id(), $this->defaults ) );
			$this->data = wp_parse_args( $this->data, SSE_Option_Manager::$defaults[ $this->get_id() ] );
		}

	/**
	 * Update
	**/
		/**
		 * Update Option from $this->data
		**/
		public function update()
		{
			$result = false;
			$this->data = wp_parse_args( $this->data, SSE_Option_Manager::$defaults[ $this->get_id() ] );
			$value = $this->maybe_json_encode( $this->data );
			if ( ! is_int( $value ) || is_string( $value ) || is_bool( $value ) ) {
				$result = update_option( $this->get_option_id(), apply_filters( sse()->get_prefixed_filter_hook( 'sanitize_option_value' ), $value, $this->get_id() ) );
			}
			return $result;
		}

	/**
	 * Delete
	**/
		/**
		 * Delete Option
		**/
		public function delete()
		{
			delete_option( $this->get_option_id() );
			$this->data = SSE_Option_Manager::$defaults[ $this->get_id() ];
		}

}



