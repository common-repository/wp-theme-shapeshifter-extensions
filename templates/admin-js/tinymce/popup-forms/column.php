<?php ?>

<!-- Row -->
	<!-- Column -->
		<!-- TinyMCE to Insert into Visual Editor -->
			<script id="wp-theme-shapeshifter-extensions-tinymce-button-column-contents" type="text/template">
				<div class="shapeshifter-col-content-editor-form-wrapper">
					<textarea id="sseditor" name="sseditor"></textarea>
				</div>
			</script>

		<!-- Settings of Data ( like Size ) -->
			<script id="wp-theme-shapeshifter-extensions-tinymce-button-column-settings" type="text/template">
				<div class="tinymce-button-column-settings-popup">
					<!-- Size -->
						<p><label for="column-size">
							<?php esc_html_e( 'Column Size', ShapeShifter_Extensions::TEXTDOMAIN ); ?>
						</label></p>
						<p><small><?php esc_html_e( '1~10( flex-grow )', ShapeShifter_Extensions::TEXTDOMAIN ); ?></small></p>
						<p>
							<input type="number" name="column-size" value="1" min="1" max="10">
						</p>

					<!-- Padding -->
						<p><label for="column-padding">
							<?php esc_html_e( 'Padding', ShapeShifter_Extensions::TEXTDOMAIN ); ?>
						</label></p>
						<p>
							<input type="number" name="column-padding" value="0" min="0" max="100">
							<span><?php _e( 'px', ShapeShifter_Extensions::TEXTDOMAIN ) ?></span>
						</p>

				</div>
			</script>


<?php ?>