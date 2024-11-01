<div class="wrap">
	<h1><?php printf( esc_html__( 'Recommended Settings by %s', ShapeShifter_Extensions::TEXTDOMAIN ), SSE_THEME_NAME ) ; ?></h1>

	<form id="<?php echo esc_attr( SHAPESHIFTER_EXTENSIONS_OPTION ); ?>" method="post" action="options.php">
		
		<?php 
		settings_fields( SHAPESHIFTER_EXTENSIONS_OPTION . 'required' );
		do_settings_sections( SHAPESHIFTER_EXTENSIONS_OPTION . 'required' );
		?>

		<!-- 初期設定 -->
		<div class="metabox-holder">
			<div id="general-settings-wrapper" class="settings-wrapper postbox">
			
				<h3 id="general-settings-h3" class="form-table-title hndle"><?php esc_html_e( 'General Settings', ShapeShifter_Extensions::TEXTDOMAIN ); ?></h3>

				<div class="inside"><div class="main">
					
					<table id="general-settings" class="form-table">
						<tbody>
						
							<tr>
								<th scope="row">
									<label for="blogname">
										<?php esc_html_e( 'Name of the Website', ShapeShifter_Extensions::TEXTDOMAIN ); ?>
									</label>
								</th>
								<td>
									<p>
										<small><?php esc_html_e( 'Edit the Name of the Website', ShapeShifter_Extensions::TEXTDOMAIN ); ?></small>
									</p>
									<input
										id="blogname" 
										class="regular-text"
										name="blogname" 
										type="text"
										value="<?php echo esc_attr( $options['blogname'] ); ?>"
									>
								</td>
							</tr>

							<tr>
								<th scope="row">
									<label for="blogdescription">
										<?php esc_html_e( 'Description of the Website', ShapeShifter_Extensions::TEXTDOMAIN ); ?>
									</label>
								</th>
								<td>
									<p class="settings-description">
										<small><?php esc_html_e( 'Edit Description of the Website', ShapeShifter_Extensions::TEXTDOMAIN ); ?></small>
									</p>
									<textarea 
										id="blogdescription" 
										class="regular-text"
										name="blogdescription" 
										cols="30"
										rows="5"
									><?php esc_html_e( $options['blogdescription'] ); ?></textarea>
								</td>
							</tr>
							
						</tbody>
					</table>

				</div></div>

			</div>
		</div>

		<!-- SEO設定 -->
		<div class="metabox-holder">
			<div id="seo-settings-wrapper" class="settings-wrapper postbox">
			<?php include_once( SHAPESHIFTER_EXTENSIONS_TEMPLATES_DIR . 'admin-pages/page-seo-settings.php' ); ?>
			</div>
		</div>

		<!-- パーマリンク -->
		<div class="metabox-holder">
			<div id="permalink-settings-wrapper" class="settings-wrapper postbox">

				<h3 id="general-settings-h3" class="form-table-title hndle"><?php esc_html_e( 'Setting for Permalink', ShapeShifter_Extensions::TEXTDOMAIN ); ?></h3>

				<div class="inside"><div class="main">

					<p><?php printf( __( 'After Saved, Please go to "<a target="_blank" href="%s">Permalink Settings</a>"', ShapeShifter_Extensions::TEXTDOMAIN ), esc_url( admin_url( 'options-permalink.php' ) ) ); ?></p>

				</div></div>
			</div>
		</div>
		
		<?php submit_button(); ?>
		
	</form>
</div>
