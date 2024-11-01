<?php
if ( ! defined( 'ABSPATH' ) ) exit;

class SSE_Deprecated_Manager_Abstract {

	/**
	 * Static
	**/
		/**
		 * Current Version
		 * @var string Version Format
		**/
		protected static $instance = null;

	/**
	 * Properties
	**/
		/**
		 * Current Version
		 * @var string Version Format
		**/
		protected $current_version = '';

	/**
	 * Init
	**/
		/**
		 * Public Initializer
		 * @return SSE_Deprecated_Manager_SSE_Deprecated_Manager_Abstract
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
		protected function init()
		{

		}

		/**
		 * Init hooks
		**/
		protected function init_hooks()
		{
			add_action( 'all_admin_notices', array( $this, 'maybe_upgrade_data' ) );
		}

	/**
	 * Methods
	**/
		/**
		 * Method called by upgrade
		**/
		public function maybe_upgrade_data()
		{

			if (  ! is_admin()
				|| ! current_user_can( 'manage_options' )
				|| 'all_admin_notices' !== current_filter()
			) {
				return;
			}

			ob_start();

			$result = array();

			// Options
			$upgraded_option_version = get_option( sse()->get_prefixed_option_name( 'upgraded_option_version' ), '1.0.0' );
			if ( ShapeShifter_Extensions::VERSION !== $upgraded_option_version ) {
				$result['options'] = array();
				set_time_limit(0);
				$flag = true;
				foreach ( $this->deprecated_option_data as $version => $deprecated_option_data ) {
					if ( version_compare( $version, $upgraded_option_version, '<=' ) ) {
						continue;
					}
					try {
						$result['options'][ $version ] = $this->maybe_upgrade_options( $version, $deprecated_option_data );
					} catch ( Exception $e ) {
						$flag = false;
						break;
					}
				}
				if ( $flag ) {
					update_option( sse()->get_prefixed_option_name( 'upgraded_option_version' ), ShapeShifter_Extensions::VERSION );
				}
			}

			// Theme Options
			$upgraded_theme_option_version = get_option( sse()->get_prefixed_option_name( 'upgraded_theme_option_version' ), '1.0.0' );
			if ( ShapeShifter_Extensions::VERSION !== $upgraded_theme_option_version ) {
				$result['theme_options'] = array();
				set_time_limit(0);
				$flag = true;
				foreach ( $this->deprecated_theme_option_data as $version => $deprecated_theme_option_data ) {
					if ( version_compare( $version, $upgraded_theme_option_version, '<=' ) ) {
						continue;
					}
					try {
						$result['theme_options'][ $version ] = $this->maybe_upgrade_theme_options( $version, $deprecated_theme_option_data );
					} catch ( Exception $e ) {
						$flag = false;
						break;
					}
				}
				if ( $flag ) {
					update_option( sse()->get_prefixed_option_name( 'upgraded_theme_option_version' ), ShapeShifter_Extensions::VERSION );
				}
			}

			// Post Meta
			$upgraded_postmeta_version = get_option( sse()->get_prefixed_option_name( 'upgraded_postmeta_version' ), '1.0.0' );
			if ( ShapeShifter_Extensions::VERSION !== $upgraded_postmeta_version ) {
				$result['postmeta'] = array();
				set_time_limit(0);
				$flag = true;
				foreach ( $this->deprecated_postmeta_data as $version => $deprecated_postmeta_data ) {
					if ( version_compare( $version, $upgraded_postmeta_version, '<=' ) ) {
						continue;
					}
					try {
						$result['postmeta'][ $version ] = $this->maybe_upgrade_postmeta( $version, $deprecated_postmeta_data );
					} catch ( Exception $e ) {
						$flag = false;
						break;
					}
				}
				if ( $flag ) {
					update_option( sse()->get_prefixed_option_name( 'upgraded_postmeta_version' ), ShapeShifter_Extensions::VERSION );
				}
			}

			// Theme Post Meta
			$upgraded_theme_postmeta_version = get_option( sse()->get_prefixed_option_name( 'upgraded_theme_postmeta_version' ), '1.0.0' );
			if ( ShapeShifter_Extensions::VERSION !== $upgraded_theme_postmeta_version ) {
				$result['theme_postmeta'] = array();
				set_time_limit(0);
				$flag = true;
				foreach ( $this->deprecated_theme_postmeta_data as $version => $deprecated_theme_postmeta_data ) {
					if ( version_compare( $version, $upgraded_theme_postmeta_version, '<=' ) ) {
						continue;
					}
					try {
						$result['theme_postmeta'][ $version ] = $this->maybe_upgrade_theme_postmeta( $version, $deprecated_theme_postmeta_data );
					} catch ( Exception $e ) {
						$flag = false;
						break;
					}
				}
				if ( $flag ) {
					update_option( sse()->get_prefixed_option_name( 'upgraded_theme_postmeta_version' ), ShapeShifter_Extensions::VERSION );
				}
			}

			$output = ob_get_clean();

			if ( ! empty( $output ) 
				|| 0 < count( $result )
			) {
				echo '<div class="notice updated wc-stripe-apple-pay-notice is-dismissible"><p><pre>';
					if ( ! empty( $output ) ) var_dump( $output );
					if ( ! empty( $result ) ) var_dump( $result );
				echo '</pre></p></div>';
			}

		}

	/**
	 * Update
	**/
		/**
		 * Options
		**/
			/**
			 * Method called by upgrade
			 * @param string $version
			 * @param array  $deprecated_option_data
			**/
			protected function maybe_upgrade_options( $version, $deprecated_option_data )
			{
				$result = array();
				foreach ( $deprecated_option_data as $deprecated_index => $new_option_data ) {
					$deprecated_value = get_option( $deprecated_index, null );
					if ( null === $deprecated_value ) continue;
					//$option_name = sse()->get_prefixed_option_name( $new_option_data['name'] );
					$option_name  = $this->upgrade_option_key( $new_option_data['name'], $version );
					$option_value = $this->upgrade_option_value( $deprecated_value, $new_option_data['name'], $version );
					$result[ $option_name ] = update_option( $option_name, $option_value );
					if ( $result[ $option_name ] && $deprecated_index !== $option_name ) delete_option( $deprecated_index );
				}
				return $result;
			}

		/**
		 * Theme Options
		**/
			/**
			 * Method called by upgrade
			 * @param string $version
			 * @param array  $deprecated_theme_option_data
			**/
			protected function maybe_upgrade_theme_options( $version, $deprecated_theme_option_data )
			{
				$result = array();
				foreach ( $deprecated_theme_option_data as $deprecated_index => $new_theme_option_data ) {
					$deprecated_value = get_option( $deprecated_index, null );
					if ( null === $deprecated_value ) continue;
					//$option_name = sse()->get_prefixed_theme_option_name( $new_theme_option_data['name'] );
					$option_name  = $this->upgrade_theme_option_key( $new_theme_option_data['name'], $version );
					$option_value = $this->upgrade_theme_option_value( $deprecated_value, $new_theme_option_data['name'], $version );
					$result[ $option_name ] = update_option( $option_name, $option_value );
//					if ( $result[ $option_name ] && $deprecated_index !== $option_name ) delete_option( $deprecated_index );
				}
				return $result;
			}

		/**
		 * Post Meta
		**/
			/**
			 * Method called by upgrade
			 * @param string $version
			 * @param array  $deprecated_postmeta_data
			**/
			protected function maybe_upgrade_postmeta( $version, $deprecated_postmeta_data )
			{

				global $wpdb;
				$table = $wpdb->postmeta;

				$query_result = array();
				if ( ! isset( $this->deprecated_postmeta_data[ $version ] ) 
					|| ! is_array( $this->deprecated_postmeta_data[ $version ] )
					|| 0 >= count( $this->deprecated_postmeta_data[ $version ] )
				) {
					throw new Exception( sprintf(
						'There is No Deprecated Data in version %s',
						$version
					) );
				}

				echo '<div class="notice updated wc-stripe-apple-pay-notice is-dismissible"><p><pre>';

				foreach ( $this->deprecated_postmeta_data[ $version ] as $deprecated_index => $each_data ) {

					// New Post Meta Name
					$post_meta_index = $this->upgrade_postmeta_key( $each_data['name'], $version );

					// Select
					$where = "`meta_key` LIKE %s";
					$stmt = $wpdb->prepare(
						"SELECT `meta_id`, `meta_key`, `meta_value` FROM `{$table}` WHERE $where",
						$deprecated_index
					);

					// Search Result
					$results = $wpdb->get_results( $stmt, ARRAY_A );
					if ( ! is_array( $results ) || 0 >= count( $results ) ) {
						continue;
					}

					foreach ( $results as $index => $result_data ) {

						$searched_meta_key   = $result_data['meta_key'];
						$searched_meta_value = $result_data['meta_value'];

						// Upgraded Value
						$meta_value = $this->upgrade_postmeta_value( $searched_meta_value, $each_data['name'], $version );

						$where = "`meta_key`='$searched_meta_key'";
						$data = "`meta_key`='$post_meta_index',`meta_value`='$meta_value'";
						$sql = "UPDATE `$table` SET $data WHERE $where";

						// Should check something?
						$sql = apply_filters( sse()->get_prefixed_filter_hook( 'sql_upgrade_deprecated' ), $sql, $table, $data, $where );

						// Exec
						$query_result[] = $wpdb->query( $sql );

					}

				}

				echo '</pre></p></div>';

				return $query_result;

			}

		/**
		 * Theme Post Meta
		**/
			/**
			 * Method called by upgrade
			 * @param string $version
			 * @param array  $deprecated_theme_postmeta_data
			**/
			protected function maybe_upgrade_theme_postmeta( $version, $deprecated_theme_postmeta_data )
			{

				global $wpdb;
				$table = $wpdb->postmeta;

				$query_result = array();
				if ( ! isset( $this->deprecated_theme_postmeta_data[ $version ] ) 
					|| ! is_array( $this->deprecated_theme_postmeta_data[ $version ] )
					|| 0 >= count( $this->deprecated_theme_postmeta_data[ $version ] )
				) {
					throw new Exception( sprintf(
						'There is No Deprecated Data in version %s',
						$version
					) );
				}

				echo '<div class="notice updated wc-stripe-apple-pay-notice is-dismissible"><p><pre>';

				foreach ( $this->deprecated_theme_postmeta_data[ $version ] as $deprecated_index => $each_data ) {

					// New Post Meta Name
					$post_meta_index = $this->upgrade_postmeta_key( $each_data['name'], $version );

					// Select
					$where = "`meta_key` LIKE %s";
					$stmt = $wpdb->prepare(
						"SELECT `meta_id`, `meta_key`, `meta_value` FROM `{$table}` WHERE $where",
						$deprecated_index
					);

					// Search Result
					$results = $wpdb->get_results( $stmt, ARRAY_A );
					if ( ! is_array( $results ) || 0 >= count( $results ) ) {
						continue;
					}

					foreach ( $results as $index => $result_data ) {

						$searched_meta_key   = $result_data['meta_key'];
						$searched_meta_value = $result_data['meta_value'];

						// Upgraded Value
						$meta_value = $this->upgrade_postmeta_value( $searched_meta_value, $each_data['name'], $version );

						$where = "`meta_key`='$searched_meta_key'";
						$data = "`meta_key`='$post_meta_index',`meta_value`='$meta_value'";
						$sql = "UPDATE `$table` SET $data WHERE $where";

						// Should check something?
						$sql = apply_filters( sse()->get_prefixed_filter_hook( 'sql_upgrade_deprecated' ), $sql, $table, $data, $where );

						// Exec
						$query_result[] = $wpdb->query( $sql );

					}

				}

				echo '</pre></p></div>';

				return $query_result;

			}

	/**
	 * Tools
	**/
		/**
		 * Option
		**/
			/**
			 * Upgrade Option Key
			 * @param string $key     : Without prefix
			 * @param string $version
			 * @return string
			**/
			protected function upgrade_option_key( $key, $version )
			{

				$func = sprintf( 'upgrade_option_key_%1$s_%2$s', $version, $key );
				$method = array( $this, $func );
				if ( is_callable( $method ) ) {
					return call_user_func_array(
						$method,
						array( $key )
					);
				}

				$func = sprintf( 'upgrade_option_key_%1$s', $version );
				$method = array( $this, $func );
				if ( is_callable( $method ) ) {
					return call_user_func_array(
						$method,
						array( $key )
					);
				}

				return sse()->get_prefixed_option_name( $key );

			}

			/**
			 * Upgrade Option Value
			 * @param string $value   : db value without mods
			 * @param string $key     : without prefix
			 * @param string $version
			 * @return string
			**/
			protected function upgrade_option_value( $value, $key, $version )
			{

				$func = sprintf( 'upgrade_option_value_%1$s_%2$s', $version, $key );
				$method = array( $this, $func );
				if ( is_callable( $method ) ) {
					return call_user_func_array(
						$method,
						array( $value, $key )
					);
				}

				$func = sprintf( 'upgrade_option_value_%1$s', $version );
				$method = array( $this, $func );
				if ( is_callable( $method ) ) {
					return call_user_func_array(
						$method,
						array( $value, $key )
					);
				}

				return $value;

			}

		/**
		 * Theme Option
		**/
			/**
			 * Upgrade Theme Option Key
			 * @param string $key     : Without prefix
			 * @param string $version
			 * @return string
			**/
			protected function upgrade_theme_option_key( $key, $version )
			{

				$func = sprintf( 'upgrade_theme_option_key_%1$s_%2$s', $version, $key );
				$method = array( $this, $func );
				if ( is_callable( $method ) ) {
					return call_user_func_array(
						$method,
						array( $key )
					);
				}

				$func = sprintf( 'upgrade_theme_option_key_%1$s', $version );
				$method = array( $this, $func );
				if ( is_callable( $method ) ) {
					return call_user_func_array(
						$method,
						array( $key )
					);
				}

				return sse()->get_prefixed_theme_option_name( $key );
			}

			/**
			 * Upgrade Theme Option Value
			 * @param string $value   : db value without mods
			 * @param string $key     : without prefix
			 * @param string $version
			 * @return string
			**/
			protected function upgrade_theme_option_value( $value, $key, $version )
			{

				$func = sprintf( 'upgrade_theme_option_value_%1$s_%2$s', $version, $key );
				$method = array( $this, $func );
				if ( is_callable( $method ) ) {
					return call_user_func_array(
						$method,
						array( $value, $key )
					);
				}

				$func = sprintf( 'upgrade_theme_option_value_%1$s', $version );
				$method = array( $this, $func );
				if ( is_callable( $method ) ) {
					return call_user_func_array(
						$method,
						array( $value, $key )
					);
				}

				return $value;

			}

		/**
		 * Post Meta
		**/
			/**
			 * Upgrade Post Meta Key
			 * @param string $key     : Without prefix
			 * @param string $version
			 * @return string
			**/
			protected function upgrade_postmeta_key( $key, $version )
			{

				$func = sprintf( 'upgrade_postmeta_key_%1$s_%2$s', $version, $key );
				$method = array( $this, $func );
				if ( is_callable( $method ) ) {
					return call_user_func_array(
						$method,
						array( $key )
					);
				}

				$func = sprintf( 'upgrade_postmeta_key_%1$s', $version );
				$method = array( $this, $func );
				if ( is_callable( $method ) ) {
					return call_user_func_array(
						$method,
						array( $key )
					);
				}

				return sse()->get_prefixed_post_meta_name( $key );
			}

			/**
			 * Upgrade Post Meta Value
			 * @param string $value   : db value without mods
			 * @param string $key     : without prefix
			 * @param string $version
			 * @return string
			**/
			protected function upgrade_postmeta_value( $value, $key, $version )
			{

				$func = sprintf( 'upgrade_postmeta_value_%1$s_%2$s', $version, $key );
				$method = array( $this, $func );
				if ( is_callable( $method ) ) {
					return call_user_func_array(
						$method,
						array( $value, $key )
					);
				}

				$func = sprintf( 'upgrade_postmeta_value_%1$s', $version );
				$method = array( $this, $func );
				if ( is_callable( $method ) ) {
					return call_user_func_array(
						$method,
						array( $value, $key )
					);
				}

				return $value;

			}

		/**
		 * Theme Post Meta
		**/
			/**
			 * Upgrade Post Meta Key
			 * @param string $key     : Without prefix
			 * @param string $version
			 * @return string
			**/
			protected function upgrade_theme_postmeta_key( $key, $version )
			{

				$func = sprintf( 'upgrade_postmeta_key_%1$s_%2$s', $version, $key );
				$method = array( $this, $func );
				if ( is_callable( $method ) ) {
					return call_user_func_array(
						$method,
						array( $key )
					);
				}

				$func = sprintf( 'upgrade_postmeta_key_%1$s', $version );
				$method = array( $this, $func );
				if ( is_callable( $method ) ) {
					return call_user_func_array(
						$method,
						array( $key )
					);
				}

				return sse()->get_prefixed_theme_post_meta_name( $key );
			}

			/**
			 * Upgrade Post Meta Value
			 * @param string $value   : db value without mods
			 * @param string $key     : without prefix
			 * @param string $version
			 * @return string
			**/
			protected function upgrade_theme_postmeta_value( $value, $key, $version )
			{

				$func = sprintf( 'upgrade_theme_postmeta_value_%1$s_%2$s', $version, $key );
				$method = array( $this, $func );
				if ( is_callable( $method ) ) {
					return call_user_func_array(
						$method,
						array( $value, $key )
					);
				}

				$func = sprintf( 'upgrade_theme_postmeta_value_%1$s', $version );
				$method = array( $this, $func );
				if ( is_callable( $method ) ) {
					return call_user_func_array(
						$method,
						array( $value, $key )
					);
				}

				return $value;

			}

}


