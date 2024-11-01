<?php ?>

<!-- Balloon -->
	<script id="wp-theme-shapeshifter-extensions-tinymce-button-settings-balloon" type="text/template">
		<div id="shapeshifter-balloon-settings-form-wrapper" class="shapeshifter-settings-form-wrapper shapeshifter-balloon-settings-form-wrapper">

			<p>
				<label for="shapeshifter-balloon-image" style="font-weight: bold;"><?php esc_html_e( 'Image :', ShapeShifter_Extensions::TEXTDOMAIN ); ?></label>
				<input type="hidden" id="shapeshifter-balloon-image-input" name="shapeshifter-balloon-image" value="" placeholder="<?php esc_attr_e( 'Name', ShapeShifter_Extensions::TEXTDOMAIN ); ?>">
				<br>	
				<button id="shapeshifter-balloon-set-image" class="button button-primary shapeshifter-balloon-set-image"><?php esc_html_e( 'Set the Image', ShapeShifter_Extensions::TEXTDOMAIN ); ?></button>
				<button id="shapeshifter-balloon-reset-image" class="button shapeshifter-balloon-reset-image"><?php esc_html_e( 'Reset the Image', ShapeShifter_Extensions::TEXTDOMAIN ); ?></button>
			</p>

			<p>
				<span style="font-weight: bold;"><?php esc_html_e( 'Image Align :', ShapeShifter_Extensions::TEXTDOMAIN ); ?></span><br>
				<label for="shapeshifter-balloon-image-align"><?php esc_html_e( 'Left', ShapeShifter_Extensions::TEXTDOMAIN ); ?></label>
				<input type="radio" name="shapeshifter-balloon-image-align" id="shapeshifter-balloon-image-align-left" class="shapeshifter-balloon-image-align" value="left" placeholder="<?php esc_attr_e( 'Left', ShapeShifter_Extensions::TEXTDOMAIN ); ?>" checked>
				<br>
				<label for="shapeshifter-balloon-image-align"><?php esc_html_e( 'Right', ShapeShifter_Extensions::TEXTDOMAIN ); ?></label>
				<input type="radio" name="shapeshifter-balloon-image-align" id="shapeshifter-balloon-image-align-right" class="shapeshifter-balloon-image-align" value="right">
			</p>


			<p style="font-weight: bold;"><?php esc_html_e( 'Preview', ShapeShifter_Extensions::TEXTDOMAIN ); ?></p>
			<div class="shapeshifter-balloon-preview">
				<div class="shapeshifter-balloon-wrapper align-left">
					<div class="shapeshifter-balloon-image-wrapper">
						<figure class="shapeshifter-balloon-image-figure">
							<img id="shapeshifter-balloon-image" class="shapeshifter-balloon-image shadow-left-bottom" alt="" src="" width="100" height="100"><figcaption class="shapeshifter-balloon-image-caption">
								<input type="text" id="shapeshifter-balloon-image-name" name="shapeshifter-balloon-image-name" class="shapeshifter-balloon-image-name" value="" placeholder="<?php esc_attr_e( 'Enter a Name', ShapeShifter_Extensions::TEXTDOMAIN ); ?>">
							</figcaption>
						</figure>
					</div>
					<div class="shapeshifter-balloon-dialog-wrapper">
						<textarea name="shapeshifter-balloon-dialog" id="shapeshifterballoondialog"></textarea>
					</div>
				</div>
			</div>

		</div>
	</script>


<?php ?>