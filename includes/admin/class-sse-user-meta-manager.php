<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

if( ! class_exists( 'SSE_User_Meta_Manager' ) ) { 
class SSE_User_Meta_Manager {

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
		public $user_data = array();

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

			add_action( 'admin_init', array( $this, 'init' ), 11 );

			//$this->init();

		}

		# Define

		# Initializations
		function init() {

			$this->user_data = get_userdata( get_current_user_id() );
			if ( false === $this->user_data ) {
				return;
			}
			$this->user_data = ( array ) $this->user_data->data;

			# User Meta
				if( current_user_can( 'edit_user' ) ) {

					# Notifications
						# Save Settings
							add_action( 'admin_notices', array( $this, 'user_meta_notifications' ) );
						# Login User and Slug
							add_action( 'admin_notices', array( $this, 'notifications_to_user' ) );

					# Update Profiles
						add_action( 'personal_options_update', array( $this, 'update_extra_profile_fields' ) );
						add_action( 'edit_user_profile_update', array( $this, 'update_extra_profile_fields' ) );

					# Append User Profile Settings
						add_action( 'show_user_profile', array( $this, 'custom_user_profile_fields' ) );
						add_action( 'edit_user_profile', array( $this, 'custom_user_profile_fields' ) );

					# CSS JS Enqueue
						add_action( 'admin_enqueue_scripts', array( $this, 'admin_enqueue_scripts' ) );

						# AJAX
							add_action( 'wp_ajax_dismiss_message_for_user_slug', array( $this, 'dismiss_message_for_user_slug' ) );

				}

		}

		# CSS JS Enqueue
		function admin_enqueue_scripts() {

		}

		# Notifications
			# Save Settings
				function user_meta_notifications() {
					if( isset( $_REQUEST['settings-updated'] ) )//&& $_REQUEST['page'] === '' )
						echo '<div class="updated"><p>' . esc_html__( 'Saved', ShapeShifter_Extensions::TEXTDOMAIN ) . '</p></div>';//設定を保存しました。
				}

			# Login User and Slug
				function notifications_to_user() {

					wp_nonce_field( 'shapeshifter-dismiss-admin-message', 'shapeshifter_dismissing_message_nonce' );

					$user_id = absint( get_current_user_id() );
					$userdata = get_userdata( $user_id );
					$userdata = ( array ) $userdata->data;

					# Display Name
						if( strtolower( $userdata['user_login'] ) == strtolower( $userdata['display_name'] ) ) {
							if( ! get_user_meta( $user_id, 'shapeshifter_dismiss_display_name', true ) ) {
								# #dismiss-display-name-notice .shapeshifter-dismiss-admin-message-button
								printf( 
									'<div class="error notice">
										<p>%s</p>
										<p class="shapeshifter-submit-dismiss">
											<a id="dismiss-display-name-notice" 
												class="button shapeshifter-dismiss-admin-message-button" 
												href="javascript:void(0);" 
												data-user-id="' . $user_id . '" 
												data-button-type="display_name"
											>
												%s
											</a>
										</p>
									</div>', 
									esc_html__( "Now Using the same 'Display Name' with 'User Name for Login'. Please change your 'Display Name' at 'Your Profile'.", ShapeShifter_Extensions::TEXTDOMAIN ), 
									esc_html__( 'Dismiss this message', ShapeShifter_Extensions::TEXTDOMAIN ) 
								);
							}
						}

					# User Slug
						if( strtolower( $userdata['user_login'] ) == strtolower( $userdata['user_nicename'] ) ) {
							if( ! get_user_meta( $user_id, 'shapeshifter_dismiss_user_slug', true ) ) {
								# #dismiss-user-slug-notice .shapeshifter-dismiss-admin-message-button
								printf( 
									'<div class="error notice">
										<p>%s</p>
										<p class="shapeshifter-submit-dismiss">
											<a id="dismiss-user-slug-notice" 
												class="button shapeshifter-dismiss-admin-message-button" 
												href="javascript:void(0);" 
												data-user-id="' . $user_id . '" 
												data-button-type="user_slug"
											>
												%s
											</a>
										</p>
									</div>', 
									esc_html__( "Now Using the same 'Slug for Author Archive Page' with 'User Name for Login'. Please change your 'Slug' at 'Your Profile'.", ShapeShifter_Extensions::TEXTDOMAIN ), 
									esc_html__( 'Dismiss this message', ShapeShifter_Extensions::TEXTDOMAIN ) 
								);
							}
						}

				}

			# AJAX
				function dismiss_message_for_user_slug() {

					check_ajax_referer( 'shapeshifter-dismiss-admin-message', 'shapeshifterDismissAdminMessage' );

					if( ! empty( $_REQUEST['userID'] ) ) {
						$user_id = absint( $_REQUEST['userID'] );
					} else {
						wp_die( esc_html__( 'User ID is not set.', ShapeShifter_Extensions::TEXTDOMAIN ) );
					}

					if( isset( $_REQUEST['buttonType'] ) ) $button_type = $_REQUEST['buttonType'];

					if( $button_type == 'display_name' ) {

						// 「表示名とログイン名が同じ」を非表示にするメタデータを更新
						update_user_meta( $user_id, 'shapeshifter_dismiss_display_name', true );
						wp_die( __( 'Dismissed message for Display Name', ShapeShifter_Extensions::TEXTDOMAIN ) );

					} elseif( $button_type == 'user_slug' ) {

						// 「スラッグとログイン名が同じ」を非表示にするメタデータを更新
						update_user_meta( $user_id, 'shapeshifter_dismiss_user_slug', true );
						wp_die( __( 'Dismissed message for User Slug.', ShapeShifter_Extensions::TEXTDOMAIN ) );

					} else {
						wp_die( __( 'Non-registered Type', ShapeShifter_Extensions::TEXTDOMAIN ) );
					}

				}


		# Update Profiles
			function update_extra_profile_fields( $user_id ) {

				check_admin_referer( 'user-profile-update', 'user-profile-update-nonce' );

				remove_action( 'profile_update', array( $this, 'update_extra_profile_fields' ) );

				if( current_user_can( 'edit_user' ) ) {

					if( isset( $_POST['custom_user_nicename'] ) ) {
						
						$nicename = sanitize_user( $_POST['custom_user_nicename'], true );
						$tc_og_img = esc_url_raw( 
							isset( $_POST['tc_og_image'] )
							? $_POST['tc_og_image']
							: ''
						);
						
						update_user_meta( $user_id, 'tc_og_image', $tc_og_img );

						if( ! empty( $nicename ) ) {

							$user_id = wp_update_user( array( 
								'ID' => $user_id,
								'user_nicename' => $nicename,
							) );

							$userdata = get_userdata( $user_id );
							$userdata = ( array ) $userdata->data;

							$display_name = get_user_meta( $user_id, 'shapeshifter_dismiss_display_name', true );
							$user_slug = get_user_meta( $user_id, 'shapeshifter_dismiss_user_slug', true );

							if( strtolower( $userdata['user_login'] ) !== strtolower( $userdata['display_name'] ) ) {
								update_user_meta( $user_id, 'shapeshifter_dismiss_display_name', true );
							} else {
								update_user_meta( $user_id, 'shapeshifter_dismiss_display_name', false );
							}

							if( strtolower( $userdata['user_login'] ) !== strtolower( $nicename ) ) {
								update_user_meta( $user_id, 'shapeshifter_dismiss_user_slug', true );
							} else {
								update_user_meta( $user_id, 'shapeshifter_dismiss_user_slug', false );
							}

						}

					}

				}
				
				add_action( 'profile_update', array( $this, 'update_extra_profile_fields' ) );

			}

		# Append User Profile Settings
			function custom_user_profile_fields( $user ) {
				
				wp_nonce_field( 'user-profile-update', 'user-profile-update-nonce' );

				wp_enqueue_media();

				$user_data = ( array ) $user->data;

				$user_meta['tc_og_image'] = esc_url( get_user_meta( $user_data['ID'], 'tc_og_image', true ) );

				?>
				<table class="form-table">

					<thead>
						<tr>
							<th>
								<h3><?php esc_html( sprintf( __( '%s Optional Settings', ShapeShifter_Extensions::TEXTDOMAIN ), SSE_THEME_NAME ) );//オプショナル設定 ?></h3>
							</th>
						</tr>
					</thead>

					<tbody>

						<tr>
							<th>
								<label for="custom_user_nicename"><?php esc_html_e( 'Slug Settings', ShapeShifter_Extensions::TEXTDOMAIN ); ?></label>
							</th>
							<td>
								<?php if( strtolower( $user_data['user_login'] ) == $user_data['user_nicename'] ) { echo '<p style="color:red;">' . esc_html__( "It's the same 'User Name for login'. Please change", ShapeShifter_Extensions::TEXTDOMAIN ) . '</p>'; }//「ユーザー名」同じです！　必ず変更してください！ ?>
								<input type="text" name="custom_user_nicename" id="custom_user_nicename" value="<?php echo esc_attr( $user_data['user_nicename'] ); ?>" class="regular-text" />
								<br>
								<span class="description"><small>
								<?php
								echo wp_kses( 
									sprintf( 
										__( "If the slug is the same value with 'User Name for Login', Please change.<br>The slug is used after Website URL like '%s/author/SLUG/'", ShapeShifter_Extensions::TEXTDOMAIN ), 
										esc_url( SITE_URL )
									),
									array(
										'br' => array()
									)
								);
								/*
								「ユーザー名」同じ場合は変更してください。（半角英数のみ）<br>
								作者アーカイブページのURL「' . SITE_URL . '」に続く<br>
								「/author/スラッグ/」に使用されますので、ログイン用のユーザー名が<br>
								公開ページで見えてしまう原因となります。
								*/
								?>
								</small></span>
							</td>
						</tr>

						<tr>
							<th scope="row">
								<label for="tc_og_image">
									<?php esc_html_e( "Image Settings to use for 'Twitter Card' and 'Open Graph'.", ShapeShifter_Extensions::TEXTDOMAIN );//「Twitter Card」や「Open Graph」に使用するイメージの設定 ?>
								</label>
							</th>
							<td>
								<input 
									type="hidden" 
									id="tc_og_image" 
									class="regular-hidden-field" 
									name="tc_og_image" 
									value="<?php echo esc_url( $user_meta['tc_og_image'] );?>"
								/>
								<table><tbody>
								<tr>
									<td>
										<a href="javascript:void(0);" id="button_tc_og_image" class="button customaddmedia"><?php esc_html_e( 'Select an Image', ShapeShifter_Extensions::TEXTDOMAIN );//画像を選ぶ ?></a>
										<a href="javascript:void(0);" id="remove_button_tc_og_image" class="button customaddmedia"><?php esc_html_e( 'Reset', ShapeShifter_Extensions::TEXTDOMAIN );//リセット ?></a>
									</td>
									<td>
										<div id="tc_og_image_box" class="image-box">
											<?php if( isset( $user_meta['tc_og_image'] ) ) { ?>
												<img src="<?php echo esc_url( $user_meta['tc_og_image'] );?>" width="100" height="100" id="tc_og_img" class="tc_og_image"/>
											<?php } ?>
										</div>
									</td>
								</tr>
								</tbody></table>
							</td>
						</tr>

					</tbody>

				</table>

				<script>
				( function( $ ) {

					$( document ).ready( function() {

						// メディアの設定
						$( '#remove_button_tc_og_image' ).click( function( e ) {
							$('div.image-box' ).children().remove();
							$( '#tc_og_image' ).val( '' );
						});

						$( '#button_tc_og_image' ).click( function( e ) {
							
							console.log( 'clicked!' );
							
							e.preventDefault();
							
							var uploader = wp.media({
								title: "<?php _e( 'Select an Image', ShapeShifter_Extensions::TEXTDOMAIN );//画像の選択 ?>",
								button: {
									text: "<?php _e( 'Set the Image', ShapeShifter_Extensions::TEXTDOMAIN );//画像を決定 ?>"	
								},
								multiple: false
							})
							.on( 'select', function() {
								
								var selection = uploader.state().get( 'selection' );
								
								var attachment = selection.first().toJSON();
								
								$( '#tc_og_image_box' ).empty();
								$( '#tc_og_image_box' ).append( '<img src="' + attachment.url + '" width="100" height="100" style="float: left;" id="tc_og_img">' );
								
								$( '#tc_og_image' ).val( attachment.url );
									
							}).open();
							
						});
									
					});

				}) (jQuery);
				</script>

			<?php }

}
}

