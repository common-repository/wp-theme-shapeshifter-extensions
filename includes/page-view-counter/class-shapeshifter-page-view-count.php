<?php

class SSE_Page_View_Counter extends SSE_Unique_Abstract {

	// settings from includes
	private $reset_page_view_count;
	private $auto_page_view_count_reset;

	/**
	 * Init
	**/
		/**
		 * Public Initializer
		 * @return SSE_Page_View_Counter
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

			$this->init();
			$this->init_hooks();

		}

		function init() {

			$options = sse()->get_options();
			$this->reset_page_view_count = absint( get_option( sse()->get_prefixed_option_name( 'reset_page_view_count' ) ) );
			$this->auto_page_view_count_reset = ( 
				isset( $options[ sse()->get_prefixed_option_name( 'auto_page_view_count_reset' ) ] ) 
				? $options[ sse()->get_prefixed_option_name( 'auto_page_view_count_reset' ) ] 
				: 'no' 
			);

			# Reset View Count
				if( is_admin() && $this->reset_page_view_count ) {
					$this->reset_views_count();
				}

		}

		function init_hooks() {

			add_action( 'publish_post', array( $this, 'set_views_count' ) );
			add_action( 'publish_page', array( $this, 'set_views_count' ) );

			if( ! is_admin() ) {
				add_action( 'wp_head', array( $this, 'count_views' ) ); 
			}
			add_action( 'wp_footer', array( $this, 'get_auto_reset_views_count_duration' ) );

		}

	# Creat Post Meta when the Post is Created
		function set_views_count( $post_ID ) {
			global $wpdb;
			if( ! wp_is_post_revision( $post_ID ) ) {
				add_post_meta( $post_ID, 'shapeshifter-views', 0, true );
			}
		}

		function get_auto_reset_views_count_duration() {

			switch ( $this->auto_page_view_count_reset ) {
				
				case 'no':
				
					return false; 
					break;
								
				case 'day':
					
					# 1, 2, 3
					$duration = date( 'j' ); 
					break;
				
				case 'week':
					
					# Mon, Tue, Wed
					$duration = date( 'D' ); 

					if( false === ( $sameday = get_transient( '24hrs' ) ) ) {
						
						# When No Transient Exists, Data saved
							$sameday = 'sameday';
							set_transient( '24hrs', $sameday, 24 * HOUR_IN_SECONDS );

						# Not Sunday
							if( $duration !== 'Sun' ) {
								return false;
							}

						# Reset View Count
							$this->reset_views_count();

						return true;

					}

					return false;
					break;

				case 'month':

					# Jan, Feb, Mar
					$duration = date( 'M' ); 
					break;

				case 'year':

					# 2015, 2016, 2017
					$duration = date( 'Y' ); 
					break;

				default:

					return false;
					break;

			}

			$prev_duration = get_option( sse()->get_prefixed_option_name( 'reset_page_view_count' ), 0 );
			
			if( $prev_duration == $duration ) {

				# No Exec
				return false;

			} else {

				# Update Duration
					update_option( sse()->get_prefixed_option_name( 'reset_page_view_count' ), sanitize_text_field( $duration ), false );

				# Reset View Count
					$this->reset_views_count();

				return true;

			}

		}

	# Count Views by Visiters
		function count_views() {

			if( ! is_single() ) { // 投稿ページでない場合
				return;
			}
			if ( ! isset( $_SERVER['HTTP_USER_AGENT'] ) || empty( $_SERVER['HTTP_USER_AGENT'] ) )
				return; // No UA? Bot (probably)

			$user_agent = strtolower( $_SERVER['HTTP_USER_AGENT'] );
			$bots = array(
				'Google Bot' => 'googlebot', 
				'Google Bot' => 'google', 
				'MSN' => 'msnbot', 
				'Alex' => 'ia_archiver', 
				'Lycos' => 'lycos', 
				'Ask Jeeves' => 'jeeves', 
				'Altavista' => 'scooter', 
				'AllTheWeb' => 'fast-webcrawler', 
				'Inktomi' => 'slurp@inktomi', 
				'Turnitin.com' => 'turnitinbot', 
				'Technorati' => 'technorati', 
				'Yahoo' => 'yahoo', 
				'Findexa' => 'findexa', 
				'NextLinks' => 'findlinks', 
				'Gais' => 'gaisbo', 
				'WiseNut' => 'zyborg', 
				'WhoisSource' => 'surveybot', 
				'Bloglines' => 'bloglines', 
				'BlogSearch' => 'blogsearch', 
				'PubSub' => 'pubsub', 
				'Syndic8' => 'syndic8', 
				'RadioUserland' => 'userland', 
				'Gigabot' => 'gigabot', 
				'Become.com' => 'become.com', 
				'Baidu' => 'baiduspider', 
				'so.com' => '360spider', 
				'Sogou' => 'spider', 
				'soso.com' => 'sosospider', 
				'Yandex' => 'yandex'
			);

			foreach ( $bots as $name => $bot ) {
				if ( stristr( $user_agent, $bot ) !== false ) {
					return;
				}
			} $bots = null;

			global $post;

			if( get_post_meta( $post->ID, 'shapeshifter-views', true ) ) {

				$prev_value = intval( get_post_meta( $post->ID, 'shapeshifter-views', true ) );
			
			} else {
				
				$prev_value = intval( 0 );
			
			}

			$meta_value = absint( $prev_value + 1 );

			update_post_meta( $post->ID, 'shapeshifter-views', $meta_value, $prev_value );

		}

		/**
		 * Reset View Count
		 * @return [type] [description]
		**/
		function reset_views_count() {

			$allposts = get_posts( array(
				'post_type' => 'post',
				'post_status' => 'publish',
				'posts_per_page' => -1
			) );
			
			foreach( $allposts as $post ) {
				
				$prev_val = absint( get_post_meta( absint( $post->ID ), 'shapeshifter-views', true ) );
				update_post_meta( $post->ID, 'shapeshifter-views', 0, $prev_val );

			} $allposts = null;

			update_option( sse()->get_prefixed_option_name( 'reset_page_view_count' ), 0 );

		}


}


?>