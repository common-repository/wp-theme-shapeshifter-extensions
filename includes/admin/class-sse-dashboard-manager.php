<?php 
if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

if( ! class_exists( 'SSE_Dashboard_Manager' ) ) { 
class SSE_Dashboard_Manager {

	/**
	 * Static
	**/
		/**
		 * Instance
		 * @var SSE_Dashboard_Manager
		**/
		protected static $instance = null;

	/**
	 * Properties
	**/
		public $items = array();
		public $options = array();

	/**
	 * Init
	**/
		/**
		 * Public Initialier
		 * @return SSE_Dashboard_Manager
		**/
		public static function get_instance()
		{
			if ( null === self::$instance ) self::$instance = new Self();
			return self::$instance;
		}
		/**
		 * Constructor
		**/
		protected function __construct() {

			# User Check
				if( ! current_user_can( 'manage_options' ) ) return;

			# Init
				$this->init();
				$this->add_actions();
				$this->add_filters();

		}

		function init() {
			$this->items = get_option( sse()->get_prefixed_option_name( 'dashboard_widget' ) );
		}

		function add_actions() {

			# Add Dashboard Widgets
				add_action( 'wp_dashboard_setup', array( $this, 'add_dashboard_widgets' ) );

		}

			# Add Dashboard Widgets
				function add_dashboard_widgets() {
					
				 	# Feed Reader
					 	wp_add_dashboard_widget(
							'feed_reader',
							esc_html__( 'ShapeShifter Feed Reader', ShapeShifter_Extensions::TEXTDOMAIN ),
							array( $this, 'dashboard_widget_feed_function' ),
							array( $this, 'dashboard_widget_feed_handle_function' )
						);

				}

				 	# Feed Reader
						function dashboard_widget_feed_function() {

							if( ! $widget_options = get_option( sse()->get_prefixed_option_name( 'dashboard_widget' ) ) )
								$widget_options = array();

							$widget_options['feed_count'] = absint( 
								isset( $widget_options['feed_count'] )
								? $widget_options['feed_count'] 
								: 5 
							);

							$rss = esc_url( 
								isset( $widget_options['feed_url'] )
								? $widget_options['feed_url'] 
								: 'http://wp-works.net/feed/' 
							);
							$rss = fetch_feed( $rss );

							$widget_options['feed_date'] = shapeshifter_boolval(
								isset( $widget_options['feed_date'] )
								? $widget_options['feed_date']
								: '1'
							);

							$maxitems = 0;
							
							if ( ! is_wp_error( $rss ) ) {
							
								# Max Item Num
									$maxitems = absint( $rss->get_item_quantity( $widget_options['feed_count'] ) );
							
								# Generate Items
									$rss_items = $rss->get_items( 0, $maxitems );
							
							echo '<h4><strong>' . esc_html( $rss->get_title() ) . '</strong></h4>';
							echo '<ul>';
								if( $maxitems == 0 ) {
									echo '<li>' . esc_html__( 'No items', ShapeShifter_Extensions::TEXTDOMAIN ) . '</li>';
								} else {
									# Loop through each feed item and display each item as a hyperlink.
									foreach( $rss_items as $item ) {
										echo '<li>';
											if( $widget_options['feed_date'] ) { 
												echo $item->get_date( 'j F Y | g:i a' ); 
											}

											echo '<br>';

											echo '<a href="' . esc_url( $item->get_permalink() ) . '" ';
												echo 'title="' . esc_attr( sprintf( 
													__( 'Posted %s', ShapeShifter_Extensions::TEXTDOMAIN ), 
													$item->get_date( 'j F Y | g:i a' ) 
												) ) . '"';
											echo '>';
												echo esc_html( $item->get_title() );
											echo '</a>';
										echo '</li>';
									}
								}
							echo '</ul>';

							}

						}
						function dashboard_widget_feed_handle_function() {

							# process update
								if( 'POST' == $_SERVER['REQUEST_METHOD'] && isset( $_POST['dashboard_widget_options'] ) ) {
									# minor validation
										$widget_options['feed_url'] = esc_url_raw( $_POST['dashboard_widget_options']['feed_url'] );
										$widget_options['feed_count'] = absint( $_POST['dashboard_widget_options']['feed_count'] );
										$widget_options['feed_date'] = ( isset( $_POST['dashboard_widget_options']['feed_date'] ) ? '1' : 0 );

									# save update
										update_option( sse()->get_prefixed_option_name( 'dashboard_widget' ), $widget_options );

									# Message
										echo '<h4>' . esc_html__( 'Saved Settings.', ShapeShifter_Extensions::TEXTDOMAIN ) . '</h4>';

								}

							# get saved data
								$widget_options = get_option( sse()->get_prefixed_option_name( 'dashboard_widget' ), array() );

							# set defaults  
								if( ! isset( $widget_options['feed_url'] ) ) 
									$widget_options['feed_url'] = esc_url( get_bloginfo( 'rss2_url' ) );

							echo '<h4><strong>' . esc_html__( 'Feed Reader Settings', ShapeShifter_Extensions::TEXTDOMAIN ) . '</strong></h4>';
							
							echo '<table class="form-table">';
								echo '<thead>';
									echo '<tr>';
										echo '<th></th>';
										echo '<td></td>';
									echo '</tr>';
								echo '</thead>';
								echo '<tbody>';
									echo '<tr>';
										echo '<th><label>' . esc_html__( 'Feed URL', ShapeShifter_Extensions::TEXTDOMAIN ) . '</label></th>';
										echo '<td><input type="text" name="dashboard_widget_options[feed_url]" id="feed_url" value="' . esc_url( $widget_options['feed_url'] ) . '" /></td>';
									echo '</tr>';
									
									echo '<tr>';
										echo '<th><label>' . esc_html__( 'Posts num to display', ShapeShifter_Extensions::TEXTDOMAIN ) . '</label></th>';
										echo '<td><input type="text" name="dashboard_widget_options[feed_count]" id="feed_count" value="' . absint( isset( $widget_options['feed_count'] ) ? $widget_options['feed_count'] : 5 ) . '" /></td>';
									echo '</tr>';
								
									echo '<tr>';
										echo '<th><label>' . esc_html__( 'Display the date', ShapeShifter_Extensions::TEXTDOMAIN ) . '</label></th>';
										echo '<td><input type="checkbox" name="dashboard_widget_options[feed_date]" id="feed_date" value="1" ' . checked( ( isset( $widget_options['feed_date'] ) ? $widget_options['feed_date'] : 0 ), '1', false ) . '/></td>';
									echo '</tr>';
								echo '</tbody>';
							echo '</table>';

						}


	function add_filters() {

	}

}
}


