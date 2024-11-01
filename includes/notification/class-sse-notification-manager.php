<?php
if ( ! defined( 'ABSPATH' ) ) exit;

class SSE_Notification_Manager {

	/**
	 * Static
	**/
		/**
		 * Instance
		 * @var SSE_Notification_Manager
		**/
		protected static $instance = null;

	/** 
	 * Properties
	**/
		/** 
		 * Messages
		 * @var string[]
		**/
		protected $notice_messages = array();

	/**
	 * Init
	**/
		/**
		 * Public Initialier
		 * @return SSE_Notification_Manager
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
			$this->init_hooks();
		}

		/**
		 * Init hooks
		**/
		protected function init_hooks()
		{
			add_action( 'all_admin_notices', array( $this, 'admin_notices' ) );
		}

	/**
	 * Notices
	**/
		/**
		 * Called in hook "all_admin_notices"
		 * 
		 * @uses $this->notice_messages
		**/
		public function admin_notices()
		{

			if ( array( current_filter(), array( 'all_admin_notices' ) ) ) {
				return;
			}

			$notice_messages = $this->get_notice_messages();

			if ( 0 < count( $notice_messages ) ) {
				foreach( $notice_messages as $notice_message ) {
					echo $this->wrap_as_notices( $notice_message['text'], $notice_message['type'] );
				}
			}

		}

		/**
		 * Wrap the text in notice format
		 * 
		 * @param string $notice_message : Message to be wrapped
		 * @param string $type           : 'notice', 'warning', 'updated'
		 * 
		 * @see ntvwc_is_string_and_not_empty( $string )
		 * 
		 * @return string
		**/
		protected function wrap_as_notices( $notice_message = '', $type = 'notice' )
		{

			// Check the param
			if ( ! is_string( $notice_message ) ) {
				ob_start();
				var_dump( $notice_message );
				$notice_message = ob_get_clean();
				ob_start();
				echo '<pre>';
				echo esc_html( $notice_message );
				echo '</pre>';
				$notice_message = ob_get_clean();
			}

			// Init Message
			$format = '<div class="notice %s wc-stripe-apple-pay-notice is-dismissible"><p>%s</p></div>' . PHP_EOL;
			$notice_type = ( in_array( $type, array( 'warning' ) )
				? 'notice-' . $type
				: $type
			);
			$notice = sprintf( $format, $notice_type, $notice_message );

			// End
			return $notice;

		}

		/**
		 * Get
		 * 
		 * @return [array] description
		**/
		public function get_notice_messages()
		{
			return apply_filters( $this->get_prefixed_filter_hook( 'notice_messages' ), $this->notice_messages );
		}

		/**
		 * Add
		 * 
		 * @param [string] $message
		 * @param [string] $type
		 * 
		 * @return [bool]
		**/
		public function add_notice_message( $text, $type = 'notice' )
		{

			if ( ! is_string( $text ) || '' === $text ) {
				ob_start();
				echo '<pre>';
				var_dump( $text );
				echo '</pre>';
				$text = ob_get_clean();
			}

			if ( ! is_string( $type ) || ! in_array( $type, apply_filters(
				sse()->get_prefixed_filter_hook( 'notice_types' ),
				array( 'succeed', 'notice', 'warning', 'error' )
			) ) ) {
				return false;
			}

			if ( did_action( 'all_admin_notices' ) ) {
				echo $this->wrap_as_notices( $text, $type );
				return true;
			}

			if ( 0 < count( $this->notice_messages ) ) {
				foreach ( $this->notice_messages as $notice_message ) {
					if ( $text === $notice_message['text'] ) {
						return false;
					}
				}
			}

			array_push( $this->notice_messages, array(
				'type' => $type,
				'text' => $text
			) );
			return true;

		}

		/**
		 * Set messages
		 * 
		 * @param [array] $messages
		 * 
		 * @return [array]
		**/
		public function set_notice_messages( $messages = array() )
		{

			if ( 0 < count( $messages ) ) {
			foreach ( $messages as $message ) {
				$this->add_notice_message( $message['text'], $message['type'] );
			}
			}

			return apply_filters( $this->get_prefixed_filter_hook( 'notice_messages' ), $this->notice_messages );

		}


}

