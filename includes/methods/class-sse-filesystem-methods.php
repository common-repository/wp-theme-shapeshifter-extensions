<?php
if( ! class_exists( 'SSE_Filesystem_Methods' ) ) {
class SSE_Filesystem_Methods {

	/**
	 * Init WP Filesystem
	 *
	 * @return true if init succeeds, otherwise false
	**/
	public static function init_file_system( $url, $nonce ) {

		# Check if User Authority
			if ( ! current_user_can( 'manage_options' ) ) {
				return;
			}

		# Nonce URL
			$nonce_url = esc_url( wp_nonce_url( $url, $nonce ) );
			
		# If is writable
			if( false === ( $creds = request_filesystem_credentials( $nonce_url, '', false, false, null ) ) ) {
				return false; // ここで処理を停止
			}
		
		# Try WP_Filesystem_Base
			if ( ! WP_Filesystem( $creds ) ) {
				request_filesystem_credentials( $nonce_url, '', true, false, null );
				return false;
			}

		# Succeed
			return true;

	}

	/**
	 * AJAX Init Filesystem
	 *
	 * @return true if init succeeds, otherwise false
	**/
	public static function 	ajax_init_filesystem( $nonce ) {

		$access_type = get_filesystem_method();
		if( $access_type === 'direct' ) {

			$nonce_url = esc_url( wp_nonce_url( admin_url(), $nonce ) );

			$creds = request_filesystem_credentials( $nonce_url, '', false, false, array() );

			if ( ! WP_Filesystem( $creds ) ) {
				return false;
			}	

			return true;

		}

		return false;

	}

}
}

?>