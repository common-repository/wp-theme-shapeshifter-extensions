<?php
if ( ! defined( 'ABSPATH' ) ) exit;

class SSE_Data_Post_Meta extends SSE_Data_CURD_Abstract {

	/**
	 * Properties
	**/
		/**
		 * Post ID
		 * @var [int]
		 */
		protected $post_id = 0;

		/**
		 * Data ID
		 * @var string
		 */
		protected $data_id = '';

		/**
		 * Defaults Data
		 * @var array
		 */
		protected $defaults = array();

		/**
		 * Attributes for this object.
		 * @var [array]
		 */
		protected $attributes = array(
			'id'          => 0, // can be string
			'object_read' => false, // This is false until the object is read from the DB.
			'data_type'   => 'post_meta', // like 'data' 'option' 'post' 'meta'
			'object_type' => '', // like 'single' 'downloadable'
		);

	/**
	 * Init
	**/
		/**
		 * Public Initializer
		 * @param int    $post_id
		 * @param string $data_id
		 * @param array  $defaults
		 * @return SSE_Data_Post_Meta
		**/
		public static function get_instance( $post_id, $data_id = '', $defaults = null )
		{
			try {
				$instance = new Self( $post_id, $data_id, $defaults );
			} catch ( SSE_Exception $e ) {
				if ( is_admin() ) sse()->add_notice_message( $e->getMessage() );
				return false;
			}
			return $instance;
		}

		/**
		 * Constructor
		 * @param int    $post_id
		 * @param string $data_id
		 * @param array  $defaults
		**/
		protected function __construct( $post_id, $data_id = '', $defaults = null )
		{
			if ( ! is_numeric( $post_id )
				|| 0 >= intval( $post_id )
				|| ! is_string( $data_id ) 
				|| empty( $data_id )
			) {
				throw new SSE_Exception( esc_html__( 'Wrong ID.', 'shapeshifter' ) );
			}
			$this->init( $post_id, $data_id, $defaults );
		}

		/**
		 * Init
		 * @param int    $post_id
		 * @param string $data_id
		 * @param array  $defaults
		**/
		protected function init( $post_id, $data_id = '', $defaults = null )
		{

			$this->post_id  = $post_id;
			$this->data_id = sse()->get_prefixed_post_meta_name( $data_id );
			$this->defaults = $defaults;
			$this->read();

			if ( is_array( $this->data ) && is_array( $this->defaults ) ) {
				$this->data = wp_parse_args( $this->data, $this->defaults );
			}

			do_action( 'shapeshifter_action_init_data', $this, $this->post_id, $this->data_id, $this->data );

		}

	/**
	 * Getters
	**/
		/**
		 * Get Post ID
		 * @return int
		**/
		public function get_post_id()
		{
			return intval( $this->post_id );
		}

		/**
		 * Get Data ID
		 * @return string
		**/
		public function get_data_id()
		{
			return $this->data_id;
		}

	/**
	 * CRUD
	**/
		/**
		 * Create
		**/
			/**
			 * Create Post Meta from $this->data
			**/
			public function create()
			{
				$value = $this->maybe_json_encode( $this->data );
				if ( ! is_int( $value ) || is_string( $value ) || is_bool( $value ) ) {
					$this->delete();
					add_post_meta( $this->get_post_id(), $this->get_data_id(), $value );
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
				$data = $this->maybe_json_decode( get_post_meta( $this->get_post_id(), $this->get_data_id(), true ) );

				if ( null === $data ) {
					$this->data = $this->defaults;
				} else {
					$this->data = $data;
				}
			}

		/**
		 * Update
		**/
			/**
			 * Update Post Meta from $this->data
			**/
			public function update()
			{
				$result = false;
				$value = $this->maybe_json_encode( $this->data );
				if ( ! is_int( $value ) || is_string( $value ) || is_bool( $value ) ) {
					$result = update_post_meta( $this->get_post_id(), $this->get_data_id(), $value );
				}
				return $result;
			}

		/**
		 * Delete
		**/
			/**
			 * Delete Post Meta
			**/
			public function delete()
			{
				delete_post_meta( $this->get_post_id(), $this->get_data_id() );
			}

}



