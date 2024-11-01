<div class="metabox-holder">
	<div id="others-settings-wrapper" class="settings-wrapper postbox">

		<h3 id="others-settings-h3" class="form-table-title hndle"><?php esc_html_e( 'Other Settings', ShapeShifter_Extensions::TEXTDOMAIN ); ?></h3>

		<div class="inside"><div class="main">

			<table id="others-settings" class="form-table">
				<tbody>
				
					<tr>
						<th scope="row">
							<label for="<?php echo esc_attr( sse()->get_prefixed_option_name( 'others[reset_page_view_count]' ) ); ?>">
								<?php esc_html_e( 'Reset Page View Count', ShapeShifter_Extensions::TEXTDOMAIN ); ?>
							</label>
						</th>
						<td>
							<input
								type="checkbox" 
								id="reset_page_view_count" 
								class="regular-checkbox" 
								name="<?php echo esc_attr( sse()->get_prefixed_option_name( 'others[reset_page_view_count]' ) ); ?>" 
								value="reset_page_view_count"
								<?php checked( $options['others']['reset_page_view_count'], 'reset_page_view_count' ); ?>
							/>
						</td>
					</tr>
					
					<tr>
						<th scope="row">
							<label for="<?php echo esc_attr( sse()->get_prefixed_option_name( 'others[auto_page_view_count_reset]' ) ); ?>">
								<?php esc_html_e( 'Auto Reset Page View Count', ShapeShifter_Extensions::TEXTDOMAIN ); ?>
							</label>
						</th>
						<td>
							<select id="auto_page_view_count_reset" name="<?php echo esc_attr( sse()->get_prefixed_option_name( 'others[auto_page_view_count_reset]' ) ); ?>">
								<option value="no" <?php selected( $options['others']['auto_page_view_count_reset'], 'no' ); ?>><?php esc_html_e( 'None', ShapeShifter_Extensions::TEXTDOMAIN ); ?></option>
								<option value="day" <?php selected( $options['others']['auto_page_view_count_reset'], 'day' ); ?>><?php esc_html_e( 'Every Day', ShapeShifter_Extensions::TEXTDOMAIN ); ?></option>
								<option value="week" <?php selected( $options['others']['auto_page_view_count_reset'], 'week' ); ?>><?php esc_html_e( 'Every Sunday', ShapeShifter_Extensions::TEXTDOMAIN ); ?></option>
								<option value="month" <?php selected( $options['others']['auto_page_view_count_reset'], 'month' ); ?>><?php esc_html_e( 'At the Beginning of a Month', ShapeShifter_Extensions::TEXTDOMAIN ); ?></option>
								<option value="year" <?php selected( $options['others']['auto_page_view_count_reset'], 'year' ); ?>><?php esc_html_e( 'At the Beginning of Year', ShapeShifter_Extensions::TEXTDOMAIN ); ?></option>
							 </select>
						</td>
					</tr>

				</tbody>
			</table>

		</div></div>

	</div>
</div>
