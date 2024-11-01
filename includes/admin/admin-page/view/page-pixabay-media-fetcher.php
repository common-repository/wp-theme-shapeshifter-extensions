<?php

$api_key = get_option( sse()->get_prefixed_option_name( 'pixabay_api_key' ), '' );
wp_nonce_field( 'pixabay-media-fetcher', $name = "pixabay-media-fetcher-nonce", $referer = true, $echo = true );
?>

<div class="metabox-holder">
	<div id="pixabay-media-fetcher-api-key-setting-wrapper" class="settings-wrapper postbox">
		<h3 id="general-settings-h2" class="form-table-title hndle"><?php esc_html_e( 'API Key', ShapeShifter_Extensions::TEXTDOMAIN ); ?></h3>

		<div class="inside"><div class="main">

			<table id="pixabay-api-key-setting" class="form-table">
				<tbody>

					<tr>
						<th scope="row">
							<label for="<?php echo esc_attr( sse()->get_prefixed_option_name( 'others[auto_page_view_count_reset]' ) ); ?>">
								<?php esc_html_e( 'Pixabay Key', ShapeShifter_Extensions::TEXTDOMAIN ); ?>
							</label>
						</th>
						<td>
							<p><?php _e( 'Please Enter Pixabay API key of your account.', ShapeShifter_Extensions::TEXTDOMAIN ); ?><?php printf( __( 'You can get <a href="%s">here</a>.', '' ), 'https://pixabay.com/api/docs/' ); ?></p>
							<input 
								type="text" 
								id="pixabay-api-key" 
								class="regular-checkbox" 
								name="<?php echo esc_attr( sse()->get_prefixed_option_name( 'pixabay_api_key' ) ); ?>" 
								value="<?php echo esc_attr( $api_key ); ?>"
							>
							<button class="button button-primary save-pixabay-api-key"><?php esc_html_e( 'Save', ShapeShifter_Extensions::TEXTDOMAIN ); ?></button>
							<p id="pixabay-api-key-result"></p>
						</td>
					</tr>

				</tbody>
			</table>

		</div></div>
	</div>

	<div id="pixabay-media-fetcher-wrapper" class="settings-wrapper postbox">
		<h3 id="general-settings-h2" class="form-table-title hndle"><?php esc_html_e( 'Media Fetcher', ShapeShifter_Extensions::TEXTDOMAIN ); ?></h3>

		<div class="inside"><div class="main">

			<table id="pixabay-search-media-table" class="form-table">
				<tbody>

					<tr>
						<th scope="row">
							<label for="<?php echo esc_attr( sse()->get_prefixed_option_name( 'others[auto_page_view_count_reset]' ) ); ?>">
								<?php esc_html_e( 'Fetch Images', ShapeShifter_Extensions::TEXTDOMAIN ); ?><br>
							</label>
						</th>
						<td>
							<p>
								<select class="pixabay-media-fetcher-type" style="display: none; height: 25.5px;; margin-top: -2px; margin-right: -6px;">
									<option value="image" selected><?php esc_html_e( 'Image', ShapeShifter_Extensions::TEXTDOMAIN ); ?></option>
									<option value="movie"><?php esc_html_e( 'Movie', ShapeShifter_Extensions::TEXTDOMAIN ); ?></option>
								</select>
								<input 
									type="text" 
									id="pixabay-search-keywords" 
									class="regular-text pixabay-search-keywords" 
									name="<?php echo esc_attr( sse()->get_prefixed_option_name( 'others[pixabay_key]' ) ); ?>" 
									value=""
								>
								<button class="button button-primary search-pixabay-images"><?php esc_html_e( 'Search', ShapeShifter_Extensions::TEXTDOMAIN ); ?></button>
								<button class="button pixabay-images-search-options"><?php esc_html_e( 'Options', ShapeShifter_Extensions::TEXTDOMAIN ); ?></button>
								<?php
									printf(
										__( 'Powered by %s', ShapeShifter_Extensions::TEXTDOMAIN ),
										'<a href="https://pixabay.com/" target="_blank">Pixabay</a>'
									);
								?>
								<a href="https://pixabay.com/"><img src="<?php echo esc_attr( SSE_ASSETS_URL . 'images/pixabay-logo.png' ); ?>" alt="Pixabay" width="30" height="30" style="margin-bottom: -10px;"></a>
							</p>
						</td>
					</tr>

				</tbody>
			</table>

			<div class="pixabay-media-fetcher-options-background" style="
				display: none;
				position: fixed;
				top: 32px;
				left: 0px;
				right: 0px;
				bottom: 0px;
				background-color: rgba( 0, 0, 0, 0.5 );
				z-index: 100000;
			"></div>

			<div class="pixabay-media-fetcher-search-options-wrapper" style="
				display: none;
				position: fixed;
				top: 42px;
				left: 10px;
				right: 10px;
				bottom: 10px;
				padding: 20px;
				background-color: #FFFFFF;
				z-index: 100001;
				overflow: auto;
			">
				<p><?php esc_html_e( 'These options settings values won\'t be saved.', ShapeShifter_Extensions::TEXTDOMAIN ); ?></p>
				<table class="form-table">
					<tbody>
						<tr>
							<th scope="row">
								<label for="pixabay-search-option"><?php esc_html_e( 'Image Type', ShapeShifter_Extensions::TEXTDOMAIN ); ?></label>
							</th>
							<td>
								<select name="image_type" class="pixabay-media-fetcher-option">
									<option value="all" selected><?php esc_html_e( 'All', ShapeShifter_Extensions::TEXTDOMAIN ); ?></option>
									<option value="photo"><?php esc_html_e( 'Photo', ShapeShifter_Extensions::TEXTDOMAIN ); ?></option>
									<option value="illustration"><?php esc_html_e( 'Illustration', ShapeShifter_Extensions::TEXTDOMAIN ); ?></option>
									<option value="vector"><?php esc_html_e( 'Vector', ShapeShifter_Extensions::TEXTDOMAIN ); ?></option>
								</select>
							</td>
						</tr>
						<tr>
							<th scope="row">
								<label for="video_type"><?php esc_html_e( 'Video Type', ShapeShifter_Extensions::TEXTDOMAIN ); ?></label>
							</th>
							<td>
								<select name="video_type" class="pixabay-media-fetcher-option">
									<option value="all" selected><?php esc_html_e( 'All', ShapeShifter_Extensions::TEXTDOMAIN ); ?></option>
									<option value="film"><?php esc_html_e( 'Film', ShapeShifter_Extensions::TEXTDOMAIN ); ?></option>
									<option value="animation"><?php esc_html_e( 'Animation', ShapeShifter_Extensions::TEXTDOMAIN ); ?></option>
								</select>
							</td>
						</tr>
						<tr>
							<th scope="row">
								<label for="pixabay-search-option"><?php esc_html_e( 'Orientation', ShapeShifter_Extensions::TEXTDOMAIN ); ?></label>
							</th>
							<td>
								<select name="orientation" class="pixabay-media-fetcher-option">
									<option value="all" selected><?php esc_html_e( 'All', ShapeShifter_Extensions::TEXTDOMAIN ); ?></option>
									<option value="horizontal"><?php esc_html_e( 'Horizontal', ShapeShifter_Extensions::TEXTDOMAIN ); ?></option>
									<option value="vertical"><?php esc_html_e( 'Vertical', ShapeShifter_Extensions::TEXTDOMAIN ); ?></option>
								</select>
							</td>
						</tr>
						<tr>
							<th scope="row">
								<label for="pixabay-search-option"><?php esc_html_e( 'Category', ShapeShifter_Extensions::TEXTDOMAIN ); ?></label>
							</th>
							<td>
								<select name="category" class="pixabay-media-fetcher-option-category">
									<option value="all" checked><?php esc_html_e( 'All', ShapeShifter_Extensions::TEXTDOMAIN ); ?></option>
									<option value="fashion"><?php esc_html_e( 'Fashion', ShapeShifter_Extensions::TEXTDOMAIN ); ?></option>
									<option value="nature"><?php esc_html_e( 'Nature', ShapeShifter_Extensions::TEXTDOMAIN ); ?></option>
									<option value="backgrounds"><?php esc_html_e( 'Backgrounds', ShapeShifter_Extensions::TEXTDOMAIN ); ?></option>
									<option value="science"><?php esc_html_e( 'Science', ShapeShifter_Extensions::TEXTDOMAIN ); ?></option>
									<option value="education"><?php esc_html_e( 'Education', ShapeShifter_Extensions::TEXTDOMAIN ); ?></option>
									<option value="people"><?php esc_html_e( 'People', ShapeShifter_Extensions::TEXTDOMAIN ); ?></option>
									<option value="feelings"><?php esc_html_e( 'Feelings', ShapeShifter_Extensions::TEXTDOMAIN ); ?></option>
									<option value="religion"><?php esc_html_e( 'Religion', ShapeShifter_Extensions::TEXTDOMAIN ); ?></option>
									<option value="health"><?php esc_html_e( 'Health', ShapeShifter_Extensions::TEXTDOMAIN ); ?></option>
									<option value="places"><?php esc_html_e( 'Places', ShapeShifter_Extensions::TEXTDOMAIN ); ?></option>
									<option value="animals"><?php esc_html_e( 'Animals', ShapeShifter_Extensions::TEXTDOMAIN ); ?></option>
									<option value="industry"><?php esc_html_e( 'Industry', ShapeShifter_Extensions::TEXTDOMAIN ); ?></option>
									<option value="food"><?php esc_html_e( 'Food', ShapeShifter_Extensions::TEXTDOMAIN ); ?></option>
									<option value="computer"><?php esc_html_e( 'Computer', ShapeShifter_Extensions::TEXTDOMAIN ); ?></option>
									<option value="sports"><?php esc_html_e( 'Sports', ShapeShifter_Extensions::TEXTDOMAIN ); ?></option>
									<option value="transportation"><?php esc_html_e( 'Transportation', ShapeShifter_Extensions::TEXTDOMAIN ); ?></option>
									<option value="travel"><?php esc_html_e( 'Travel', ShapeShifter_Extensions::TEXTDOMAIN ); ?></option>
									<option value="buildings"><?php esc_html_e( 'Buildings', ShapeShifter_Extensions::TEXTDOMAIN ); ?></option>
									<option value="business"><?php esc_html_e( 'Business', ShapeShifter_Extensions::TEXTDOMAIN ); ?></option>
									<option value="music"><?php esc_html_e( 'Music', ShapeShifter_Extensions::TEXTDOMAIN ); ?></option>
								</select>
							</td>
						</tr>
						<tr style="display: none;">
							<th scope="row">
								<label for="pixabay-search-option"><?php esc_html_e( 'Search High Resolution', ShapeShifter_Extensions::TEXTDOMAIN ); ?></label>
							</th>
							<td>
								<input type="checkbox" name="response_group" class="pixabay-media-fetcher-option-high-resolution" value="high_resolution">
								<?php printf( __( 'This requires permission. Please go to %s and Request full API Access', ShapeShifter_Extensions::TEXTDOMAIN ), '<a target="_blank" href="https://pixabay.com/api/docs/">Pixabay</a>' ); ?>
							</td>
						</tr>
						<tr>
							<th scope="row">
								<label for="pixabay-search-option"><?php esc_html_e( 'Min Width', ShapeShifter_Extensions::TEXTDOMAIN ); ?></label>
							</th>
							<td>
								<input type="number" name="min_width" class="pixabay-media-fetcher-option" step="10" min="0" max="10000" size="5">px
							</td>
						</tr>
						<tr>
							<th scope="row">
								<label for="pixabay-search-option"><?php esc_html_e( 'Min Height', ShapeShifter_Extensions::TEXTDOMAIN ); ?></label>
							</th>
							<td>
								<input type="number" name="min_height" class="pixabay-media-fetcher-option" step="10" min="0" max="10000" size="5">px
							</td>
						</tr>
						<tr>
							<th scope="row">
								<label for="pixabay-search-option"><?php esc_html_e( 'Recieved Editor\'s Choice Award', ShapeShifter_Extensions::TEXTDOMAIN ); ?></label>
							</th>
							<td>
								<input type="checkbox" name="editors_choice" class="pixabay-media-fetcher-option" value="true"	>
							</td>
						</tr>
						<tr>
							<th scope="row">
								<label for="safesearch"><?php esc_html_e( 'Only Images Suitable for All Ages', ShapeShifter_Extensions::TEXTDOMAIN ); ?></label>
							</th>
							<td>
								<input type="checkbox" name="safesearch" class="pixabay-media-fetcher-option">
							</td>
						</tr>
						<tr>
							<th scope="row">
								<label for="order"><?php esc_html_e( 'order', ShapeShifter_Extensions::TEXTDOMAIN ); ?></label>
							</th>
							<td>
								<select name="order" class="pixabay-media-fetcher-option">
									<option value="popular" checked><?php esc_html_e( 'Popular', ShapeShifter_Extensions::TEXTDOMAIN ); ?></option>
									<option value="latest" checked><?php esc_html_e( 'Latest', ShapeShifter_Extensions::TEXTDOMAIN ); ?></option>
								</select>
							</td>
						</tr>
						<tr>
							<th scope="row">
								<label for="per_page"><?php esc_html_e( 'Per Page', ShapeShifter_Extensions::TEXTDOMAIN ); ?></label>
							</th>
							<td>
								<input type="number" name="per_page" class="pixabay-media-fetcher-option" value="20" step="10" min="10" max="100" size="3">
							</td>
						</tr>
					</tbody>
				</table>
				<button class="button close-pixabay-media-fetcher-options-popup"><?php esc_html_e( 'Close', ShapeShifter_Extensions::TEXTDOMAIN ); ?></button>
			</div>

			<div class="pixabay-media-fetcher-results wp-core-ui" style="
				display: none;
				position: fixed;
				top: 42px;
				left: 10px;
				right: 10px;
				bottom: 10px;
				padding: 20px;
				background-color: #FFFFFF;
				z-index: 100001;
				overflow: hidden;
			">
				<div class="pixabay-result-image-wrapper" style="display: flex; flex-wrap: wrap; height: 100%; overflow: hidden;">
					<ul class="attachments ui-sortable ui-sortable-disabled" id="pixabay-media-views" style="display: flex; flex-wrap: wrap; width: 70%; overflow: auto;">
					</ul>
					<div class="pixabay-media-control" style="width: 30%; overflow: auto;">
						<div class="pixabay-media-control-preview-select" style="
							margin: 10px auto;
							width: 200px;
							height: 200px;
							background-size: cover;
							background-position: center center;
							background-repeat: no-repeat;
						">
							<!--img class="pixabay-media-control-preview-select-image" src="" style="width:100%; height: 300px;"-->
						</div>
						<div class="pixabay-media-control-buttons" style="text-align: center;">
							<p>
								<select id="pixabay-media-fetcher-pagination-select">
								</select>
								<button class="button preview-selected-image disabled"><?php esc_html_e( 'Preview', ShapeShifter_Extensions::TEXTDOMAIN ); ?></button>
								<button class="button button-primary import-selected-images"><?php esc_html_e( 'Import', ShapeShifter_Extensions::TEXTDOMAIN ); ?></button>
								<button class="button close-pixabay-media-fetcher-options-popup"><?php esc_html_e( 'Close', ShapeShifter_Extensions::TEXTDOMAIN ); ?></button>
							</p>
						</div>
					</div>
				</div>
			</div>

			<div class="pixabay-media-fetcher-media-preview" style="
				display: none;
				position: fixed;
				top: 42px;
				left: 10px;
				right: 10px;
				bottom: 10px;
				padding: 20px;
				background-color: #FFFFFF;
				z-index: 100002;
				overflow: auto;
			">
			</div>

			<div class="pixabay-media-fetcher-results-loading">
				<div class="pixabay-media-fetcher-loading-icon"></div>
				<strong
					data-loading="<?php esc_attr_e( 'Loading Images...', ShapeShifter_Extensions::TEXTDOMAIN ) ?>"
					data-importing="<?php esc_attr_e( 'Importing Image...', ShapeShifter_Extensions::TEXTDOMAIN ) ?>">
				</strong>
			</div>
			<div class="pixabay-media-fetcher-results-more" style="display: none;">
				<button class="button-secondary"><?php esc_html_e( 'Load More', ShapeShifter_Extensions::TEXTDOMAIN ) ?></button>
			</div>


		</div></div>
	</div>
</div>
