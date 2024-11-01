<div class="metabox-holder">
	<div id="auto-insert-settings-wrapper" class="settings-wrapper postbox">

		<h3 id="auto-insert-settings-h3" class="form-table-title hndle"><?php esc_html_e( 'Settings for Auto Insert', ShapeShifter_Extensions::TEXTDOMAIN ); ?></h3>

		<div class="inside"><div class="main">

		<table id="auto-insert-settings" class="form-table" style="margin-top: 20px; margin-bottom: 20px;">
			<tbody>
				
				<!-- 抜粋の文字数 -->
				<tr>
				
					<th class="form-table-title" scope="row" style="vertical-align: middle;">
						<label for="<?php echo esc_attr( sse()->get_prefixed_option_name( 'auto_insert[content_editor]' ) ); ?>">
							<?php esc_html_e( 'Excerpt length', ShapeShifter_Extensions::TEXTDOMAIN ); ?>
						</label>
					</th>
					
					<td>
						<p class="description"><small>
							<strong>
								<?php esc_html_e( 'This is settings to change "Excerpt Length" for Post List in Archive Page. Please set Positive Integer.', ShapeShifter_Extensions::TEXTDOMAIN ); ?>
							</strong>
						</small></p>
					</td>
					
					<td>
						<input
							id="content_editor" 
							name="<?php echo esc_attr( sse()->get_prefixed_option_name( 'auto_insert[excerpt_length]' ) ); ?>" 
							type="number"
							value="<?php echo absint( 
								intval( $options['auto_insert']['excerpt_length'] ) < 1 
								? 200 
								: $options['auto_insert']['excerpt_length'] 
							); ?>"
						>
					</td>

				</tr>

				<!-- コンテンツ初期値 -->
				<tr>
				
					<th class="form-table-title" scope="row" style="vertical-align: middle;">
						<label for="<?php echo esc_attr( sse()->get_prefixed_option_name( 'auto_insert[content_editor]' ) ); ?>">
							<?php esc_html_e( 'Default Value of Content', ShapeShifter_Extensions::TEXTDOMAIN ); ?>
						</label>
					</th>
					
					<td>
						<p class="description"><small>
							<strong>
								<?php esc_html_e( 'When you add New Page, this settings value will be printed as default value.', ShapeShifter_Extensions::TEXTDOMAIN ); ?>
							</strong>
						</small></p>
					</td>
					
					<td>
						<textarea 
							id="content_editor" 
							name="<?php echo esc_attr( sse()->get_prefixed_option_name( 'auto_insert[content_editor]' ) ); ?>" 
							cols="50" rows="5"
						><?php echo html_entity_decode( $options['auto_insert']['content_editor'] ); ?></textarea>
					</td>

				</tr>

				<!-- headタグ内に出力 -->
				<tr>
				
					<th class="form-table-title" scope="row" style="vertical-align: middle;">
						<label for="<?php echo esc_attr( sse()->get_prefixed_option_name( 'auto_insert[header_code]' ) ); ?>">
							<?php esc_html_e( 'Print in HEAD tag', ShapeShifter_Extensions::TEXTDOMAIN ); ?>
						</label>
					</th>
					
					<td>
						<p class="description"><small>
							<?php esc_html_e( "Text will be printed in head tag hooked by 'wp_head'.", ShapeShifter_Extensions::TEXTDOMAIN ); ?>
						</small></p>
					</td>
					
					<td>
						<textarea 
							id="header_code" 
							name="<?php echo esc_attr( sse()->get_prefixed_option_name( 'auto_insert[header_code]' ) ); ?>" 
							cols="50" rows="5"
						><?php echo html_entity_decode( $options['auto_insert']['header_code'] ); ?></textarea>
					</td>

				</tr>

				<!-- ヘッダーに出力 -->
				<tr>
				
					<th class="form-table-title" scope="row" style="vertical-align: middle;">
						<label for="<?php echo esc_attr( sse()->get_prefixed_option_name( 'auto_insert[after_start_body_code]' ) ); ?>">
							<?php esc_html_e( 'Print in header', ShapeShifter_Extensions::TEXTDOMAIN ); ?>
						</label>
					</th>
					
					<td>
						<p class="description"><small>
							<?php esc_html_e( "Texts will be printed in header after the starting BODY tag '&lt;body&gt;'.", ShapeShifter_Extensions::TEXTDOMAIN ); ?>
						</small></p>
					</td>
					
					<td>
						<textarea 
							id="header_code" 
							name="<?php echo esc_attr( sse()->get_prefixed_option_name( 'auto_insert[after_start_body_code]' ) ); ?>" 
							cols="50" rows="5"
						><?php echo html_entity_decode( $options['auto_insert']['after_start_body_code'] ); ?></textarea>
					</td>

				</tr>

				<!-- フッターに出力 -->
				<tr>
				
					<th class="form-table-title" scope="row" style="vertical-align: middle;">
						<label for="<?php echo esc_attr( sse()->get_prefixed_option_name( 'auto_insert[footer_code]' ) ); ?>">
							<?php esc_html_e( 'Print in footer', ShapeShifter_Extensions::TEXTDOMAIN ); ?>
						</label>
					</th>
					
					<td>
						<p class="description"><small>
							<?php esc_html_e( "Texts will be printed in footer before the ending BODY tag '&lt;/body&gt;'.", ShapeShifter_Extensions::TEXTDOMAIN ); ?>
						</small></p>
					</td>
					
					<td>
						<textarea 
							id="header_code" 
							name="<?php echo esc_attr( sse()->get_prefixed_option_name( 'auto_insert[footer_code]' ) ); ?>" 
							cols="50" rows="5"
						><?php echo html_entity_decode( $options['auto_insert']['footer_code'] ); ?></textarea>
					</td>

				</tr>
			

			</tbody>
		</table>

		</div></div>

	</div>
</div>
