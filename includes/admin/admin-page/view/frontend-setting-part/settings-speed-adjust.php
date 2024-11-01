<div class="metabox-holder">
	<div id="speed-adjust-settings-wrapper" class="settings-wrapper postbox">

		<h3 id="general-settings-h2" class="form-table-title hndle"><?php esc_html_e( 'Page Speed', ShapeShifter_Extensions::TEXTDOMAIN ); ?></h3>

		<div class="inside"><div class="main">

			<table id="general-setting" class="form-table">
				<tbody>
					<tr>
						<th scope="row">
						<label for="<?php echo esc_attr( sse()->get_prefixed_option_name( 'speed_adjust[async_script_on]' ) ); ?>"><?php esc_html_e( "Asynchronize JS script tags enqueued by WP function 'wp_enqueue_script'", ShapeShifter_Extensions::TEXTDOMAIN ); ?></label>
						</th>
						<td><input type="checkbox" id="async_script_on" class="regular-checkbox" name="<?php echo esc_attr( sse()->get_prefixed_option_name( 'speed_adjust[async_script_on]' ) ); ?>" value="async_script_on" <?php checked( $options['speed_adjust']['async_script_on'], 'async_script_on' ); ?>"></td>
					</tr>
					<!--tr>
						<th scope="row">
						<label for="<?php //echo esc_attr( sse()->get_prefixed_option_name( 'speed_adjust[async_script_tags]' ) ); ?>"><?php //esc_html_e( 'Exceptional Handles not to be asynchronized', ShapeShifter_Extensions::TEXTDOMAIN ); ?></label>
						</th>
						
						<td><input type="text" id="async_script_tags" class="regular-text" name="<?php //echo esc_attr( sse()->get_prefixed_option_name( 'speed_adjust[async_script_tags]' ) ); ?>" value="<?php //echo esc_attr( shapeshifter_boolval( $options['speed_adjust']['async_script_tags'] ) ? $options['speed_adjust']['async_script_tags'] : 'jquery' ); ?>"></td>
					</tr-->
					<tr>
						<th scope="row">
						<label for="<?php echo esc_attr( sse()->get_prefixed_option_name( 'speed_adjust[lazy_load]' ) ); ?>"><?php esc_html_e( 'Lazyload to read image files', ShapeShifter_Extensions::TEXTDOMAIN ); ?></label>
						</th>
						<td><input type="checkbox" id="lazy_load" class="regular-checkbox" name="<?php echo esc_attr( sse()->get_prefixed_option_name( 'speed_adjust[lazy_load]' ) ); ?>" value="lazy_load" <?php checked( $options['speed_adjust']['lazy_load'], 'lazy_load' ); ?> style="width:0;"></td>
					</tr>

					<tr>
						<th scope="row">
						<label for="<?php echo esc_attr( sse()->get_prefixed_option_name( 'speed_adjust[ajax_load_posts]' ) ); ?>"><?php esc_html_e( 'AJAX load for Archive pages', ShapeShifter_Extensions::TEXTDOMAIN ); ?></label>
						</th>
						<td>
							<input type="checkbox" id="ajax_load_posts" class="regular-checkbox" name="<?php echo esc_attr( sse()->get_prefixed_option_name( 'speed_adjust[ajax_load_posts]' ) ); ?>" value="ajax_load_posts" <?php checked( $options['speed_adjust']['ajax_load_posts'], 'ajax_load_posts' ); ?> style="width:0;">
						</td>
						<td>
							<p><small>
							<?php echo wp_kses( 
								__( 'Enables AJAX-Load for Archive pages.<br>
								Append a load button at the end of the last element of post list.<br>
								By Clicking, you can append the posts from next page.', ShapeShifter_Extensions::TEXTDOMAIN ),
								array(
									'br' => array()
								)
							); ?>
							</small></p>
						</td>
					</tr>

				</tbody>
			</table>

		</div></div>

	</div>
</div>
