<?php
?>
<div class="metabox-holder">
	<div id="general-settings-wrapper" class="settings-wrapper postbox">
		<h3 id="general-settings-h2" class="form-table-title hndle"><?php esc_html_e( 'General Settings', ShapeShifter_Extensions::TEXTDOMAIN ); ?></h3>
		<div class="inside"><div class="main">
			<table id="general-settings" class="form-table">
				<tbody>
				
					<tr>
						<th scope="row">
							<label for="<?php echo esc_attr( sse()->get_prefixed_option_name( 'general' ) ); ?>general[default_settings_tab]">
								<?php esc_html_e( 'Initial Tab', ShapeShifter_Extensions::TEXTDOMAIN );//標準タブ設定 ?>
							</label>
						</th>
						<td>
							<p>
								<small><?php esc_html_e( 'Select the tab you want to display first when this page is loaded', ShapeShifter_Extensions::TEXTDOMAIN );//「テーマの設定」を開いた時のタブを選択します。 ?></small>
							</p>
							<select
								id="default_settings_tab" 
								name="<?php echo esc_attr( sse()->get_prefixed_option_name( 'general' ) . '[default_settings_tab]' ); ?>" 
							>
								<?php foreach( $table_tabs as $class => $text ) { ?>
									<option value="<?php echo esc_attr( $class ); ?>" <?php selected( $options['general']['default_settings_tab'], $class ); ?>>
										<?php echo esc_html( $text ); ?>
									</option>
								<?php } ?>
							</select>
						</td>
					</tr>

					<tr>
						<th scope="row">
							<label for="<?php echo esc_attr( sse()->get_prefixed_option_name( 'not_display_post_formats' ) ); ?>">
								<?php esc_html_e( 'Post Format Not to Display', ShapeShifter_Extensions::TEXTDOMAIN ); ?>
							</label>
						</th>
						<?php 
						$post_formats = array(
							'aside' => esc_html__( 'Not Display Aisde', ShapeShifter_Extensions::TEXTDOMAIN ),
							'gallery' => esc_html__( 'Not Display Gallary', ShapeShifter_Extensions::TEXTDOMAIN ),
							'image' => esc_html__( 'Not Display Image', ShapeShifter_Extensions::TEXTDOMAIN ),
							'link' => esc_html__( 'Not Display Link', ShapeShifter_Extensions::TEXTDOMAIN ),
							'quote' => esc_html__( 'Not Display Quote', ShapeShifter_Extensions::TEXTDOMAIN ),
							'status' => esc_html__( 'Not Display Status', ShapeShifter_Extensions::TEXTDOMAIN ),
							'video' => esc_html__( 'Not Display Video', ShapeShifter_Extensions::TEXTDOMAIN ),
							'audio' => esc_html__( 'Not Display Audio', ShapeShifter_Extensions::TEXTDOMAIN ),
							'chat' => esc_html__( 'Not Display Chat', ShapeShifter_Extensions::TEXTDOMAIN ),
						);
						?>
						<td>
							<p><small>
							<?php 
								esc_html_e( 'Select Post Formats Not to display in post list of archive pages that include home page.', ShapeShifter_Extensions::TEXTDOMAIN );
							?>
							</small></p>
							<?php foreach( $post_formats as $post_format => $text ) { ?>
								<input 
									id="not_display_post_formats_<?php echo esc_attr( $post_format ); ?>" 
									class="regular-checkbox not_display_post_formats <?php echo esc_attr( $post_format ); ?>"
									type="checkbox"
									name="<?php echo esc_attr( sse()->get_prefixed_option_name( 'not_display_post_formats[' . $post_format . ']' ) ); ?>" 
									value="on"
									<?php checked( $options['not_display_post_formats'][ $post_format ], 'on' ); ?>
								/>
								<label for="<?php echo esc_attr( sse()->get_prefixed_option_name( 'not_display_post_formats[' . $post_format . ']' ) ); ?>">
									<?php echo esc_html( $text ); ?>
								</label><br>
							<?php } ?>
						</td>
					</tr>

					<tr>
						<th scope="row">
							<label for="<?php echo esc_attr( sse()->get_prefixed_option_name( 'remove_action' ) ); ?>">
								<?php esc_html_e( 'Remove Actions', ShapeShifter_Extensions::TEXTDOMAIN ); ?>
							</label>
						</th>
						<?php 
						$removed_actions = array(
							'rsd_link' => esc_html__( 'RSD', ShapeShifter_Extensions::TEXTDOMAIN ),
							'wlwmanifest_link' => esc_html__( 'WLW Manifest', ShapeShifter_Extensions::TEXTDOMAIN ),
							'wp_generator' => esc_html__( 'WP Generator', ShapeShifter_Extensions::TEXTDOMAIN ),
							'feed_links_extra' => esc_html__( 'Feed Links Extra', ShapeShifter_Extensions::TEXTDOMAIN ),
							'feed_links' => esc_html__( 'Feed Links', ShapeShifter_Extensions::TEXTDOMAIN ),
							'index_rel_link' => esc_html__( 'Index Rel Link', ShapeShifter_Extensions::TEXTDOMAIN ),
							'parent_post_rel_link' => esc_html__( 'Parent Post Rel Link', ShapeShifter_Extensions::TEXTDOMAIN ),
							'start_post_rel_link' => esc_html__( 'Start Post Rel Link', ShapeShifter_Extensions::TEXTDOMAIN ),
							'adjacent_posts_rel_link_wp_head' => esc_html__( 'Adjacent Posts Rel Link WP Head', ShapeShifter_Extensions::TEXTDOMAIN ),
						);
						?>
						<td>
							<p><small>
								<?php esc_html_e( 'You can remove default actions by wordpress', ShapeShifter_Extensions::TEXTDOMAIN ); ?>
							</small></p>
							<?php foreach( $removed_actions as $action => $text ) { ?>
								<input 
									id="<?php echo esc_attr( 'remove_action_' . $action ); ?>" 
									class="regular-checkbox"
									type="checkbox"
									name="<?php echo esc_attr( sse()->get_prefixed_option_name( 'remove_action[' . $action . ']' ) ); ?>" 
									value="on"
									<?php checked( $options['remove_action'][ $action ], 'on' ); ?>
								/>
								<label for="<?php echo esc_attr( sse()->get_prefixed_option_name( 'remove_action[' . $action . ']' ) ); ?>">
									<?php echo esc_html( $text ); ?>
								</label><br>
							<?php } ?>
						</td>
					</tr>

				</tbody>
			</table>
		</div></div>
	</div>
</div>
