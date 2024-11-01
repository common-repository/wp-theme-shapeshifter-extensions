<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

if( ! class_exists( 'SSE_Taxonomy_Editor' ) ) { 
class SSE_Taxonomy_Editor {

	/**
	 * Static
	**/
		/**
		 * Instance
		 * @var SSE_Taxonomy_Editor
		**/
		protected static $instance = null;

	/**
	 * Init
	**/
		/**
		 * Public Initialier
		 * @return SSE_Taxonomy_Editor
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

		protected function init() {

		}

		protected function init_hooks() {

			$taxonomies = get_taxonomies();

			foreach( $taxonomies as $index => $taxonomy ) {

				add_action( $taxonomy . '_edit_form', array( $this, 'add_metabox_to_tax' ) );

				//add_action( $taxonomy . '_edit', array( $this, 'update_term_meta' ) );

			}

			add_action( 'edit_term', array( $this, 'update_term_meta' ), 10, 3 );

		}

		public function update_term_meta( $term_id, $term_taxonomy_id = '', $taxonomy = '' ) {

			if( ! isset( $_POST['term_default_thumbnail'] ) ) {
				return;
			}

			check_admin_referer( 'meta-cat', 'meta-cat-nonce' );

			$term_id = absint( $term_id );

			$saved_meta = esc_url_raw( $_POST['term_default_thumbnail'] );

			update_term_meta( $term_id, 'term_default_thumbnail', $saved_meta );

		}

		function add_metabox_to_tax( $term ) { 

			wp_enqueue_media();
			
			wp_nonce_field( 'meta-cat', 'meta-cat-nonce' );

			$term_default_thumbnail = esc_url( get_term_meta( absint( $term->term_id ), 'term_default_thumbnail', true ) );

			?>
			<div class="metabox-holder"><div id="general-settings-wrapper" class="settings-wrapper postbox">

				<h3 id="general-settings-h3" class="form-table-title hndle" style="margin-left:0;"><?php echo esc_html( shapeshifter()->get_theme_data( 'Name' ) ); ?><?php esc_html_e( ' Custom Settings', ShapeShifter_Extensions::TEXTDOMAIN );//カスタム設定 ?></h3>

				<div class="inside"><div class="main">

					<table id="general-settings" class="form-table">
						<tbody>

							<tr>
								<th scope="row">
									<label for="term_default_thumbnail">
										<?php esc_html_e( 'Thumbnail', ShapeShifter_Extensions::TEXTDOMAIN );//サムネイルの設定 ?>
									</label>
								</th>
								<td>
									<input name="taxonomy_name" type="hidden" value="<?php echo esc_attr( $term->taxonomy ); ?>"/>
									<input 
										type="hidden" 
										id="term_default_thumbnail" 
										class="regular-hidden-field" 
										name="term_default_thumbnail" 
										value="<?php echo esc_url( $term_default_thumbnail ); ?>"
									/>
									<table><tbody>
										<tr>
											<td>
												<a href="javascript:void(0);" id="button_default_thumbnail" class="button customaddmedia"><?php esc_html_e( 'Select an Image', ShapeShifter_Extensions::TEXTDOMAIN );//画像を選ぶ ?></a>
												<a href="javascript:void(0);" id="remove_default_thumbnail" class="button customaddmedia"><?php esc_html_e( 'Reset', ShapeShifter_Extensions::TEXTDOMAIN );//リセット ?></a>
											</td>
											<td>
												<div id="tc_og_image_box" class="image-box">
													<?php if( ! empty( $term_default_thumbnail ) ) { ?>
														<img src="<?php echo esc_url( $term_default_thumbnail ); ?>" width="100" height="100" id="tc_og_img" class="tc_og_image" />
													<?php } ?>
												</div>
											</td>
										</tr>
									</tbody></table>
								</td>

								<td>
									<p>
										<small><?php esc_html_e( 'Priority of Thumbnail', ShapeShifter_Extensions::TEXTDOMAIN );//サムネイルが適用される優先順位 ?>
											<ol>
												<li><?php esc_html_e( 'Thumbnail for Each Page ( at edit page )', ShapeShifter_Extensions::TEXTDOMAIN );//各ページのサムネイル（各コンテンツ編集ページで設定） ?></li>
												<li><strong><?php esc_html_e( 'Thumbnail for Category ( at this page )', ShapeShifter_Extensions::TEXTDOMAIN );//カテゴリーの標準サムネイル（このページで設定） ?></strong></li>
												<li><?php esc_html_e( 'Thumbnail for User ( at Profile setting page )', ShapeShifter_Extensions::TEXTDOMAIN );//ユーザーの標準サムネイル（ユーザープロフィールページで設定） ?></li>
												<li><?php esc_html_e( 'Default Thumbnail ( at theme customizer )', ShapeShifter_Extensions::TEXTDOMAIN );//一般のサムネイル（テーマカスタマイザーで設定） ?></li>
											</ol>
										</small>
									</p>
								</td>
							</tr>

						</tbody>
					</table>

				</div></div>

			</div></div>

			<script>
			( function( $ ) {

				$( document ).ready( function() {

					// メディアの設定
					$( '#remove_default_thumbnail' ).click( function( e ) {
						$( 'div.image-box' ).children().remove();
						$( 'input#term_default_thumbnail' ).val( '' );
					});

					$( '#button_default_thumbnail' ).click( function( e ) {
						
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
							
							$( 'input#term_default_thumbnail' ).val( attachment.url );
								
						}).open();
						
					});
								
				});

			}) ( jQuery );
			</script>
		<?php }

}
}

