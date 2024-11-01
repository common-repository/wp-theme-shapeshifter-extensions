<?php ?>

<!-- Shortcodes -->
	<!-- New Entries -->
		<script id="wp-theme-shapeshifter-extensions-tinymce-button-settings-shortcode-new-entries" type="text/template">
			<div id="shapeshifter-shortcode-new-entries-settings-form-wrapper" class="shapeshifter-settings-form-wrapper-padding">
				<p>
					<label for="shapeshifter-shortcode-new-entries-number"><?php esc_html_e( 'Number', ShapeShifter_Extensions::TEXTDOMAIN ); ?></label>
					<input type="number" id="shapeshifter-shortcode-new-entries-number" name="shapeshifter-shortcode-new-entries-number" value="3" min="1">
				</p>
				<p>
					<input type="checkbox" id="shapeshifter-shortcode-new-entries-is-thumbnail-on" name="shapeshifter-shortcode-new-entries-is-thumbnail-on">
					<label for="shapeshifter-shortcode-new-entries-is-thumbnail-on"><?php esc_html_e( 'With Thumbnail', ShapeShifter_Extensions::TEXTDOMAIN ); ?></label>
				</p>
				<p>
					<label for="shapeshifter-shortcode-new-entries-excerpt-number"><?php esc_html_e( 'Excerpt Length', ShapeShifter_Extensions::TEXTDOMAIN ); ?></label>
					<input type="number" id="shapeshifter-shortcode-new-entries-excerpt-number" name="shapeshifter-shortcode-new-entries-excerpt-number" min="0" step="50" value="200">
				</p>
			</div>
		</script>

	<!-- Search Entries -->
		<script id="wp-theme-shapeshifter-extensions-tinymce-button-settings-shortcode-search-entries" type="text/template">
			<div id="shapeshifter-shortcode-search-entries-settings-form-wrapper" class="shapeshifter-settings-form-wrapper-padding">
				<p>
					<label for="shapeshifter-shortcode-search-entries-keywords"><?php esc_html_e( 'Search', ShapeShifter_Extensions::TEXTDOMAIN ); ?></label>
					<input type="text" id="shapeshifter-shortcode-search-entries-keywords" name="shapeshifter-shortcode-search-entries-keywords" style="width: 300px;">
				</p>
				<p>
					<label for="shapeshifter-shortcode-search-entries-number"><?php esc_html_e( 'Number', ShapeShifter_Extensions::TEXTDOMAIN ); ?></label>
					<input type="number" id="shapeshifter-shortcode-search-entries-number" name="shapeshifter-shortcode-search-entries-number" value="3" min="1">
				</p>
				<p>
					<label for="shapeshifter-shortcode-search-entries-orderby"><?php esc_html_e( 'Order by', ShapeShifter_Extensions::TEXTDOMAIN ); ?></label>
					<select id="shapeshifter-shortcode-search-entries-orderby" name="shapeshifter-shortcode-search-entries-orderby">
						<option value="new"><?php esc_html_e( 'New', ShapeShifter_Extensions::TEXTDOMAIN ); ?></option>
						<option value="rand"><?php esc_html_e( 'Random', ShapeShifter_Extensions::TEXTDOMAIN ); ?></option>
					</select>
				</p>
				<p>
					<input type="checkbox" id="shapeshifter-shortcode-search-entries-is-thumbnail-on" name="shapeshifter-shortcode-search-entries-is-thumbnail-on">
					<label for="shapeshifter-shortcode-search-entries-is-thumbnail-on"><?php esc_html_e( 'With Thumbnail', ShapeShifter_Extensions::TEXTDOMAIN ); ?></label>
				</p>
				<p>
					<label for="shapeshifter-shortcode-search-entries-excerpt-number"><?php esc_html_e( 'Excerpt Length', ShapeShifter_Extensions::TEXTDOMAIN ); ?></label>
					<input type="number" id="shapeshifter-shortcode-search-entries-excerpt-number" name="shapeshifter-shortcode-search-entries-excerpt-number" min="0" step="50" value="200">
				</p>
			</div>
		</script>



<?php ?>