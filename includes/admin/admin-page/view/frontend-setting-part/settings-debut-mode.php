<div class="metabox-holder">
	<div id="debug-mode-settings-wrapper" class="settings-wrapper postbox">

		<h3 id="debug-mode-settings-h3" class="form-table-title hndle"><?php esc_html_e( 'Settings for Debug Mode', ShapeShifter_Extensions::TEXTDOMAIN ); ?></h3>

		<div class="inside"><div class="main">

			<table id="debug-mode-settings" class="form-table">
				<tbody>
				
					<tr>
						<th scope="row">
							<label for="<?php echo esc_attr( sse()->get_prefixed_option_name( 'debug_mode[is_debug_mode]' ) ); ?>">
								<?php esc_html_e( 'Enable Debug Mode', ShapeShifter_Extensions::TEXTDOMAIN ); ?>
							</label>
						</th>
						<td>
							<input
								type="checkbox" 
								id="is_debug_mode" 
								class="regular-checkbox" 
								name="<?php echo esc_attr( sse()->get_prefixed_option_name( 'debug_mode[is_debug_mode]' ) ); ?>" 
								value="is_debug_mode" 
								<?php checked( $options['debug_mode']['is_debug_mode'], 'is_debug_mode' ); ?>
							/>
						</td>
					</tr>
					
					<tr>
						<th scope="row">
							<label for="<?php echo esc_attr( sse()->get_prefixed_option_name( 'debug_mode[debug_mode_key]' ) ); ?>">
								<?php esc_html_e( 'Debug Mode Key', ShapeShifter_Extensions::TEXTDOMAIN ); ?>
							</label>
						</th>
						<td>
							<input
								type="text" 
								id="debug_mode_key" 
								class="regular-text regular-text-field" 
								name="<?php echo esc_attr( sse()->get_prefixed_option_name( 'debug_mode[debug_mode_key]' ) ); ?>" 
								value="<?php echo esc_attr( $options['debug_mode']['debug_mode_key'] ); ?>" 
							/>
						</td>
					</tr>

					<tr>
						<th scope="row">
							<label for="<?php echo esc_attr( sse()->get_prefixed_option_name( 'debug_mode[debug_mode_val]' ) ); ?>">
								<?php esc_html_e( 'Debug Mode Val', ShapeShifter_Extensions::TEXTDOMAIN ); ?>
							</label>
						</th>
						<td>
							<input
								type="text" 
								id="debug_mode_val" 
								class="regular-text regular-text-field" 
								name="<?php echo esc_attr( sse()->get_prefixed_option_name( 'debug_mode[debug_mode_val]' ) ); ?>" 
								value="<?php echo esc_attr( $options['debug_mode']['debug_mode_val'] ); ?>" 
							/>
						</td>
					</tr>
					
				</tbody>
			</table>

		</div></div>

	</div>
</div>
