<?php


class SSE_Metabox_Abstract extends SSE_Unique_Abstract {

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
		 * Slug
		 * @var string
		**/
		protected $id;

		/**
		 * Title
		 * @var string
		**/
		protected $title;

		/**
		 * Title
		 * @var string[]
		**/
		protected $post_types = array();

		/**
		 * Context : 'normal', 'side' and 'advanced'
		**/
		protected $context = 'advanced';

		/**
		 * Priority
		 * 'high', 'low' : Default 'default'
		**/
		protected $priority = 'default';

		/**
		 * Args
		**/
		protected $args = array();

	/**
	 * Init
	**/
		/**
		 * Public Initializer
		 * @return Self
		**/
		public static function get_instance() {
			if ( null === self::$instance ) self::$instance = new Self();
			return self::$instance;
		}

		/**
		 * Constructor
		**/
		protected function __construct()
		{
			$this->post_types = apply_filters( sse()->get_prefixed_filter_hook( 'post_types' ),
				$this->post_types,
				'metabox',
				$this->id
			);
			$this->init();
			$this->init_hooks();
		}

		/**
		 * Please Define '$this->title' and $this->args
		**/
		protected function init() {

		}

		/**
		 * Required to add_action
		**/
		protected function init_hooks()
		{
			add_action( 'save_post', array( $this, 'save_metabox_settings' ) );
			add_action( 'add_meta_boxes', array( $this, 'add_meta_boxes' ), 10, 2 );
			add_action( 'admin_enqueue_scripts', array( $this, 'admin_enqueue_scripts' ) );
		}

		public function admin_enqueue_scripts( $hook )
		{
			if ( ! in_array( $hook, array( 'post.php', 'post-new.php' ) ) ) {
				return;
			}

			global $post;
			if ( ! in_array( $post->post_type, $this->post_types ) ) {
				return;
			}

			$this->enqueue_scripts();
		}

		protected function enqueue_scripts() {}

	/**
	 * Actions
	**/
		/**
		 * Save Action
		 * @param int $post_id
		**/
		public function save_metabox_settings( $post_id ) { do_action( sse()->get_prefixed_action_hook( 'save_metabox' ), $post_id ); }

		/**
		 * Add Metaboxes
		 * @param string $post_type
		 * @param object $post
		**/
		public function add_meta_boxes( $post_type = '', $post = '' ) {

			if ( ! in_array( $post_type, $this->post_types ) 
				|| 'add_meta_boxes' !== current_filter()
			) {
				return;
			}

			add_meta_box(
				sse()->get_prefixed_name( $this->id ),
				$this->title,
				array( $this, 'render' ),
				$post_type,
				$this->context,
				$this->priority,
				$this->args
			);

		}

		/**
		 * Add Metaboxes
		 * @param WP_Post $post
		 * @param array $args Default array()
		**/
		public function render( $post, $args = array() ) {}

	/**
	 * Help
	**/
		/**
		 * Get Post Meta Prefix
		**/
		public function get_prefixed_post_meta_name( $name )
		{
			return sse()->get_prefixed_post_meta_name( $name );
		}

}


