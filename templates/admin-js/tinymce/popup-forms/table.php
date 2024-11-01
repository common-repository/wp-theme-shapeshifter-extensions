<?php ?>

<!-- Table -->
	<script id="wp-theme-shapeshifter-extensions-tinymce-button-settings-table" type="text/template">
		<div class="shapeshifter-settings-form-wrapper shapeshifter-table-settings-form-wrapper">

			<p>
				<label><?php esc_html_e( 'Table Type', ShapeShifter_Extensions::TEXTDOMAIN ); ?></label><br>
				<input type="radio" id="shapeshifter-table-feature" name="shapeshifter-table-feature" value="simple" checked>
				<label for="shapeshifter-table-feature"><?php esc_html_e( 'Simple', ShapeShifter_Extensions::TEXTDOMAIN ); ?></label>
			</p>

			<p>
				<label for="shapeshifter-table-caption"><?php esc_html_e( 'Caption', ShapeShifter_Extensions::TEXTDOMAIN ); ?></label>
				<input type="text" id="shapeshifter-table-caption" name="shapeshifter-table-caption" value="" placeholder="<?php esc_attr_e( 'Table Title', ShapeShifter_Extensions::TEXTDOMAIN ); ?>">
			</p>

			<p>
				<input type="checkbox" id="shapeshifter-table-has-header" name="shapeshifter-table-has-header" checked>
				<label for="shapeshifter-table-has-header"><?php esc_html_e( 'Has Header', ShapeShifter_Extensions::TEXTDOMAIN ); ?></label>
			</p>
			<p>
				<input type="checkbox" id="shapeshifter-table-has-footer" name="shapeshifter-table-has-footer" checked>
				<label for="shapeshifter-table-has-footer"><?php esc_html_e( 'Has Footer', ShapeShifter_Extensions::TEXTDOMAIN ); ?></label>
			</p>

			<p>
				<label for="shapeshifter-table-columns-number" class="shapeshifter-label-for-number"><?php esc_html_e( 'Columns Number', ShapeShifter_Extensions::TEXTDOMAIN ); ?></label>
				<input type="number" id="shapeshifter-table-columns-number" name="shapeshifter-table-columns-number" value="2" step="1" min="2">
			</p>
			<p>
				<label for="shapeshifter-table-rows-number" class="shapeshifter-label-for-number"><?php esc_html_e( 'Rows Number', ShapeShifter_Extensions::TEXTDOMAIN ); ?></label>
				<input type="number" id="shapeshifter-table-rows-number" name="shapeshifter-table-rows-number"  value="2" step="1" min="2">
			</p>

		</div>
	</script>

<?php ?>