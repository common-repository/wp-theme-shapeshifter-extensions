<div class="metabox-holder">
	<div id="seo-settings-wrapper" class="settings-wrapper postbox">

		<h3 id="seo-settings-h3" class="form-table-title hndle"><?php esc_html_e( 'SEO Settings', ShapeShifter_Extensions::TEXTDOMAIN ); ?></h3>

		<div class="inside"><div class="main">

			<p><?php esc_html_e( 'SEO Settings by this plugin are NOT recommended because this plugin is activated for the Theme ShapeShifter. Just keep them empty if you don\'t use these SEO Settings.', ShapeShifter_Extensions::TEXTDOMAIN ); ?></p>

			<table id="seo-settings" class="form-table" style="margin-top: 20px; margin-bottom: 20px;">
				<tbody>
				
					<tr>
						<th class="form-table-title" scope="row" style="vertical-align: middle;">
							<label for="<?php echo esc_attr( sse()->get_prefixed_option_name( 'seo[json_ld_markup_on]' ) ); ?>">
								<?php esc_html_e( 'JSON-LD Structure Data', ShapeShifter_Extensions::TEXTDOMAIN ); ?>
							</label>
						</th>
						<td>
							<input 
								type="checkbox" 
								id="json_ld_markup_on" 
								class="regular-checkbox" 
								name="<?php echo esc_attr( sse()->get_prefixed_option_name( 'seo[json_ld_markup_on]' ) ); ?>" 
								value="json_ld_markup_on" 
								<?php checked( $options['seo']['json_ld_markup_on'], 'json_ld_markup_on' ); ?> 
							/>
						</td>
						<td>
							<p class="description"><small>
							<?php esc_html_e( 'Generate JSON-LD Structure Data and print it inside the HEAD tag.', ShapeShifter_Extensions::TEXTDOMAIN ); ?>
							</small></p>
						</td>
					</tr>

					<tr>
						<th scope="row">
							<label for="<?php echo esc_attr( sse()->get_prefixed_option_name( 'seo[json_ld_logo]' ) ); ?>">
								<?php esc_html_e( 'Logo of Website for JSON-LD Structure Data', ShapeShifter_Extensions::TEXTDOMAIN ); ?>
							</label>
						</th>
						<td>
							<input 
								type="hidden" 
								id="json_ld_logo" 
								class="regular-hidden-field" 
								name="<?php echo esc_attr( sse()->get_prefixed_option_name( 'seo[json_ld_logo]' ) ); ?>" 
								value="<?php echo esc_attr( $options['seo']['json_ld_logo'] );?>"
							/>
							<a href="javascript:void(0);" id="button_json_ld_logo_image" class="button customaddmedia"><?php esc_html_e( 'Select an Image', ShapeShifter_Extensions::TEXTDOMAIN ); ?></a>
							<a href="javascript:void(0);" id="remove_button_json_ld_logo_image" class="button customaddmedia"><?php esc_html_e( 'Reset', ShapeShifter_Extensions::TEXTDOMAIN ); ?></a>
						</td>
						<td>
							<div id="json_ld_logo_image_box" class="image-box">
								<?php if( ! empty( $options['seo']['json_ld_logo'] ) 
									&& is_numeric( $options['seo']['json_ld_logo'] ) 
								) { 
									$json_ld_logo_image = wp_get_attachment_image_src( $options['seo']['json_ld_logo'] );
									if ( isset( $json_ld_logo_image[0] )
										&& is_string( $json_ld_logo_image[0] ) 
										&& '' !== esc_url( $json_ld_logo_image[0] ) 
									) { ?>
										<img src="<?php echo esc_url( $json_ld_logo_image[0] );?>" width="100" height="100" id="json_ld_logo_image" class="json_ld_logo"/>
									<?php } ?>
								<?php } ?>
							</div>
						</td>
					</tr>

					<tr>
						<th class="form-table-title" scope="row" style="vertical-align: middle;">
							<label for="<?php echo esc_attr( sse()->get_prefixed_option_name( 'seo[insert_prev_next_link]' ) ); ?>">
								<?php esc_html_e( "Link tags of 'next' 'prev'", ShapeShifter_Extensions::TEXTDOMAIN ); ?>
							</label>
						</th>
						<td>
							<input 
								type="checkbox" 
								id="insert_prev_next_link" 
								class="regular-checkbox" 
								name="<?php echo esc_attr( sse()->get_prefixed_option_name( 'seo[insert_prev_next_link]' ) ); ?>" 
								value="insert_prev_next_link" 
								<?php checked( $options['seo']['insert_prev_next_link'], 'insert_prev_next_link' ); ?> 
							/>
						</td>
						<td>
							<p class="description"><small>
							<?php echo wp_kses(
								__( 'When the displayed page has a prev or/and next page like archive pages and post pages devided into several pages, <br>print link tags with these related page\'s links.', ShapeShifter_Extensions::TEXTDOMAIN ),
								array(
									'br' => array()
								)
							); ?>
							</small></p>
						</td>
					</tr>


					<tr>
						<th class="form-table-title" scope="row" style="vertical-align: middle;">
							<label for="<?php echo esc_attr( sse()->get_prefixed_option_name( 'seo[canonical_link_on]' ) ); ?>">
								<?php esc_html_e( 'Canonical Link', ShapeShifter_Extensions::TEXTDOMAIN ); ?>
							</label>
						</th>
						<td>
							<input 
								type="checkbox" 
								id="canonical_link_on" 
								class="regular-checkbox" 
								name="<?php echo esc_attr( sse()->get_prefixed_option_name( 'seo[canonical_link_on]' ) ); ?>" 
								value="canonical_link_on" 
								<?php checked( $options['seo']['canonical_link_on'], 'canonical_link_on' ); ?> 
							/>
						</td>
						<td>
							<p class="description"><small>
							<?php echo wp_kses( 
								__( 'To avoid being registered duplicated contents to Search Engine.<br>
								Print Tag at the pages following :<br>
								Home, Front Page : Home URL<br>
								Contents Page : Permalink<br>
								Archive Page : Category, Tag, Year, Month, Day, Author', ShapeShifter_Extensions::TEXTDOMAIN ),
								array(
									'br' => array()
								)
							); ?>
							</small></p>
						</td>
					</tr>
				
					<tr>
						<th class="form-table-title" scope="row" style="vertical-align: middle;">
							<label for="<?php echo esc_attr( sse()->get_prefixed_option_name( 'seo[meta_robots_on]' ) ); ?>">
								<?php esc_html_e( 'Auto Robots Meta', ShapeShifter_Extensions::TEXTDOMAIN ); ?>
							</label>
						</th>
						<td>
							<input 
								type="checkbox" 
								id="meta_robots_on" 
								class="regular-checkbox" 
								name="<?php echo esc_attr( sse()->get_prefixed_option_name( 'seo[meta_robots_on]' ) ); ?>" 
								value="meta_robots_on" 
								<?php checked( $options['seo']['meta_robots_on'], 'meta_robots_on' ); ?> 
							/>
						</td>
						<td>
							<p class="description"><small>
							<?php echo wp_kses( 
								__( "Print Robots Meta tag of 'noindex,following' except the pages following :<br>
								Home, Front Page, Content Pages ( like Posts and Pages ), Category", ShapeShifter_Extensions::TEXTDOMAIN ),
								array(
									'br' => array()
								)
							); ?>
							</small></p>
						</td>
					</tr>
					
					<tr>
						<th class="form-table-title" scope="row" style="vertical-align: middle;">
							<label for="<?php echo esc_attr( sse()->get_prefixed_option_name( 'seo[meta_description_on]' ) ); ?>">
								<?php esc_html_e( 'Auto Description Meta', ShapeShifter_Extensions::TEXTDOMAIN ); ?>
							</label>
						</th>
						<td>
							<input 
								type="checkbox" 
								id="meta_description_on" 
								class="regular-checkbox" 
								name="<?php echo esc_attr( sse()->get_prefixed_option_name( 'seo[meta_description_on]' ) ); ?>" 
								value="meta_description_on" 
								<?php checked( $options['seo']['meta_description_on'], 'meta_description_on' ); ?> 
							/>
						</td>
						<td>
							<p class="description"><small>
							<?php echo wp_kses( 
								__( "Print content page's excerpt ( 200 letters from the Beginning of the content, which doesn't include html tags  ) as the content of description meta tag.<br>
								For Home and Front Page, This print Website Description.<br>
								For each Content Page, You can override the value in metabox of edit page.", ShapeShifter_Extensions::TEXTDOMAIN ),
								array(
									'br' => array()
								)
							); ?>
							</small></p>
						</td>
					</tr>

					<tr>
						<th class="form-table-title" scope="row" style="vertical-align: middle;">
							<label for="<?php echo esc_attr( sse()->get_prefixed_option_name( 'seo[meta_keywords_on]' ) ); ?>">
								<?php esc_html_e( 'Auto Keywords Meta', ShapeShifter_Extensions::TEXTDOMAIN ); ?>
							</label>
						</th>
						<td>
							<input 
								type="checkbox" 
								id="meta_keywords_on" 
								class="regular-checkbox" 
								name="<?php echo esc_attr( sse()->get_prefixed_option_name( 'seo[meta_keywords_on]' ) ); ?>" 
								value="meta_keywords_on" 
								<?php checked( $options['seo']['meta_keywords_on'], 'meta_keywords_on' ); ?> 
							/>
						</td>
						<td>
							<p class="description"><small>
							<?php echo wp_kses( 
								__( 'Print terms\' name of Cat and Tag only for Post pages.<br>
								You can override the value in metabox of edit page.', ShapeShifter_Extensions::TEXTDOMAIN ),
								array(
									'br' => array()
								)
							); ?>
							</small></p>
						</td>
					</tr>

					<tr>
						<th class="form-table-title" scope="row" style="vertical-align: middle;">
							<label for="<?php echo esc_attr( sse()->get_prefixed_option_name( 'seo[twitter_card_on]' ) ); ?>">
								<?php esc_html_e( 'Twitter Card', ShapeShifter_Extensions::TEXTDOMAIN ); ?>
							</label>
						</th>
						<td>
							<input 
								type="checkbox" 
								id="twitter_card_on" 
								class="regular-checkbox" 
								name="<?php echo esc_attr( sse()->get_prefixed_option_name( 'seo[twitter_card_on]' ) ); ?>" 
								value="twitter_card_on" 
								<?php checked( $options['seo']['twitter_card_on'], 'twitter_card_on' ); ?> 
							/>
						</td>
						<td>
							<p class="description"><small>
							<?php echo wp_kses(
								__( "Print Twitter Card with 'Title', 'Thumbnail', 'Description'... refering to the page data.<br>Printed Data refered to be tweeted by visiters", ShapeShifter_Extensions::TEXTDOMAIN ),
								array(
									'br' => array()
								)
							); ?>
							</small></p>
						</td>
					</tr>
					
					<tr>
						<th class="form-table-title" scope="row" style="vertical-align: middle;">
							<label for="<?php echo esc_attr( sse()->get_prefixed_option_name( 'seo[twitter_card_account]' ) ); ?>">
							</label><?php esc_html_e( 'Twitter Account', ShapeShifter_Extensions::TEXTDOMAIN ); ?>
							</label>
						</th>
						<td>
							<input 
								type="text" 
								id="twitter_card_account" 
								class="regular-text-field" 
								name="<?php echo esc_attr( sse()->get_prefixed_option_name( 'seo[twitter_card_account]' ) ); ?>" 
								value="<?php echo esc_attr( $options['seo']['twitter_card_account'] ); ?>"
							/>
						</td>
						<td>
							<p class="description"><small>
								<?php esc_html_e( "Account for Twitter Card. Enter the value after '@'.", ShapeShifter_Extensions::TEXTDOMAIN ); ?>
							</small></p>
						</td>
					</tr>
					
					<tr>
						<th class="form-table-title" scope="row" style="vertical-align: middle;">
							<label for="<?php echo esc_attr( sse()->get_prefixed_option_name( 'seo[open_graph_on]' ) ); ?>">
								<?php esc_html_e( 'Open Graph', ShapeShifter_Extensions::TEXTDOMAIN ); ?>
							</label>
						</th>
						<td>
							<input 
								type="checkbox" 
								id="open_graph_on" 
								class="regular-checkbox" 
								name="<?php echo esc_attr( sse()->get_prefixed_option_name( 'seo[open_graph_on]' ) ); ?>" 
								value="open_graph_on" 
								<?php checked( $options['seo']['open_graph_on'], 'open_graph_on' ); ?> 
							/>
						</td>
						<td>
							<p class="description"><small>
							<?php echo wp_kses(
								__( "Print 'Open Graph' Data refering the page data.<br>Like Twitter Card, This data refered to share by SNS buttons.", ShapeShifter_Extensions::TEXTDOMAIN ),
								array(
									'br' => array()
								)
							); ?>
							</small></p>
						</td>
					</tr>

					<tr>
						<th scope="row">
							<label for="<?php echo esc_attr( sse()->get_prefixed_option_name( 'seo[tc_og_image]' ) ); ?>">
								<?php esc_html_e( "Default Image settings for 'Twitter Card' and 'Open Graph'.", ShapeShifter_Extensions::TEXTDOMAIN ); ?>
							</label>
						</th>
						<td>
							<input 
								type="hidden" 
								id="tc_og_image" 
								class="regular-hidden-field" 
								name="<?php echo esc_attr( sse()->get_prefixed_option_name( 'seo[tc_og_image]' ) ); ?>" 
								value="<?php echo esc_attr( $options['seo']['tc_og_image'] );?>"
							/>
							<a href="javascript:void(0);" id="button_tc_og_image" class="button customaddmedia"><?php esc_html_e( 'Select an Image', ShapeShifter_Extensions::TEXTDOMAIN ); ?></a>
							<a href="javascript:void(0);" id="remove_button_tc_og_image" class="button customaddmedia"><?php esc_html_e( 'Reset', ShapeShifter_Extensions::TEXTDOMAIN ); ?></a>
						</td>
						<td>
							<div id="tc_og_image_box" class="image-box">
								<?php if( $options['seo']['tc_og_image'] ) { ?>
									<img src="<?php echo esc_url( $options['seo']['tc_og_image'] );?>" width="100" height="100" id="tc_og_img" class="tc_og_image"/>
								<?php } ?>
							</div>
						</td>
					</tr>

					<tr>
						<th scope="row">
							<label for="<?php echo esc_attr( sse()->get_prefixed_option_name( 'seo[google_plus_url]' ) ); ?>">
								<?php esc_html_e( 'Link tag URL of Google+', ShapeShifter_Extensions::TEXTDOMAIN ); ?>
							</label>
						</th>
						<td>
							<input 
								type="url" 
								id="google_plus_url" 
								class="regular-url-field" 
								name="<?php echo esc_attr( sse()->get_prefixed_option_name( 'seo[google_plus_url]' ) ); ?>" 
								value="<?php echo esc_attr( $options['seo']['google_plus_url'] );?>"
							/>
						</td>
						<td>
							<p class="description"><small>
								<?php esc_html_e( 'Print Link Tag as Google+ Link when This Box filled with URL.', ShapeShifter_Extensions::TEXTDOMAIN ); ?>
							</small></p>
						</td>
					</tr>
					
				</tbody>
			</table>

		</div></div>

	</div>
</div>
