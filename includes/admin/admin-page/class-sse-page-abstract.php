<?php 
if( ! defined( 'ABSPATH' ) ) exit; 

/**
 * 
**/
class SSE_Page_Abstract extends SSE_Unique_Abstract {

	/**
	 * Static
	**/
		protected static $instance;

	/**
	 * Properties
	**/
		/**
		 * Page
		 * @var string
		**/
		protected $admin_page = 'themes.php';

		/**
		 * Page Title
		 * @var string
		**/
		protected $page_title = 'Page Title';

		/**
		 * Menu Title
		 * @var string
		**/
		protected $menu_title = 'Menu Title';

		/**
		 * Capability
		 * @var string
		**/
		protected $capability = 'manage_options';

		/**
		 * Menu Slut
		 * @var string
		**/
		protected $menu_slug = '';

		/**
		 * Page
		 * @var string
		**/
		protected $method = 'render';

		/**
		 * Form Template 
		 * @var string
		**/
		protected $form_template = '';



		

	/**
	 * Init
	**/
		/**
		 * Public Initializer
		 * @return SSE_Admin_Page_Manager
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
			$this->init();
			$this->init_hooks();
		}
		
		/**
		 * Init
		**/
		protected function init() {}

		/**
		 * Init hooks
		**/
		protected function init_hooks()
		{
			add_action( 'admin_menu', array( $this, 'admin_menu' ) );
			add_action( 'admin_notices', array( $this, 'option_update_message' ) );
			add_action( 'admin_enqueue_scripts', array( $this, 'admin_enqueue_scripts' ) );
		}

		/**
		 * Init hooks
		 * @param string $hook
		**/
		public function admin_enqueue_scripts( $hook ) {}

		function option_update_message() {
			
			if( !empty( $_REQUEST['settings-updated'] )
				&& isset( $_REQUEST['page'] )
				&& $_REQUEST['page'] === $this->menu_slug
			) echo '<div class="updated"><p>' . __( 'Saved.', ShapeShifter_Extensions::TEXTDOMAIN ) . '</p></div>';
			
		}

		public function admin_menu() {

			add_submenu_page(
				$this->admin_page,
				$this->page_title,
				$this->menu_title,
				$this->capability,
				sse()->get_prefixed_name( $this->menu_slug ),
				array( $this, $this->method )
			);

		}
		
		public function render() {

			wp_enqueue_media();

			if ( ! is_string( $this->form_template ) || '' === $this->form_template ) return;

			include( $this->form_template );


		}


}

