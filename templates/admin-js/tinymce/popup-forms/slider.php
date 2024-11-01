<?php ?>

<!-- Slider -->
	<script id="wp-theme-shapeshifter-extensions-tinymce-button-settings-slider" type="text/template">
		<div class="shapeshifter-settings-form-wrapper shapeshifter-slider-settings-form-wrapper">
			<p>
				<input type="hidden" id="shapeshifter-slider-images" name="shapeshifter-slider-images">
				<button id="shapeshifter-set-images-button-on-tinymce" class="button-primary"><?php esc_html_e( 'Set Images', ShapeShifter_Extensions::TEXTDOMAIN ); ?></button>
				<button id="shapeshifter-remove-images-button-on-tinymce" class="button"><?php esc_html_e( 'Set Images', ShapeShifter_Extensions::TEXTDOMAIN ); ?></button>
			</p>
			<p>
				<input type="radio" id="shapeshifter-image-slider-feature" name="shapeshifter-image-slider-feature" value="simple" checked>
				<label for="shapeshifter-image-slider-feature"><?php esc_html_e( 'Simple', ShapeShifter_Extensions::TEXTDOMAIN ); ?></label>
			</p>
			<p>
				<input type="checkbox" id="shapeshifter-image-slider-side-control-arrows" name="shapeshifter-image-slider-side-control-arrows" checked>
				<label for="shapeshifter-image-slider-side-control-arrows"><?php esc_html_e( 'Side Control Arrow', ShapeShifter_Extensions::TEXTDOMAIN ); ?></label>
			</p>
			<p>
				<input type="checkbox" id="shapeshifter-image-slider-bottom-buttons" name="shapeshifter-image-slider-bottom-buttons" checked>
				<label for="shapeshifter-image-slider-bottom-buttons"><?php esc_html_e( 'Bottom Buttons', ShapeShifter_Extensions::TEXTDOMAIN ); ?></label>
			</p>
			<p>
				<label for="shapeshifter-image-slider-thumbnail"><?php esc_html_e( 'Slider Thumbnails', ShapeShifter_Extensions::TEXTDOMAIN ); ?></label><br>
				<input type="radio" id="shapeshifter-image-slider-thumbnail" name="shapeshifter-image-slider-thumbnail" value="none" checked><label for="shapeshifter-image-slider-thumbnail"><?php esc_html_e( 'None', ShapeShifter_Extensions::TEXTDOMAIN ); ?></label><br>
				<input type="radio" id="shapeshifter-image-slider-thumbnail" name="shapeshifter-image-slider-thumbnail" value="bottom"><label for="shapeshifter-image-slider-thumbnail"><?php esc_html_e( 'Bottom', ShapeShifter_Extensions::TEXTDOMAIN ); ?></label><br>
				<input type="radio" id="shapeshifter-image-slider-thumbnail" name="shapeshifter-image-slider-thumbnail" value="right"><label for="shapeshifter-image-slider-thumbnail"><?php esc_html_e( 'Right', ShapeShifter_Extensions::TEXTDOMAIN ); ?></label>
			</p>
			<p>
				<label for="shapeshifter-image-slider-slide-width" class="shapeshifter-image-slider-size-label"><?php esc_html_e( 'Slide Width', ShapeShifter_Extensions::TEXTDOMAIN ); ?></label>
				<input type="number" id="shapeshifter-image-slider-slide-width" name="shapeshifter-image-slider-slide-width" value="150">
			</p>
			<p>
				<label for="shapeshifter-image-slider-slide-height" class="shapeshifter-image-slider-size-label"><?php esc_html_e( 'Slide Height', ShapeShifter_Extensions::TEXTDOMAIN ); ?></label>
				<input type="number" id="shapeshifter-image-slider-slide-height" name="shapeshifter-image-slider-slide-height" value="100">
			</p>
			<p>
				<label for="shapeshifter-image-slider-slide-thumbnail-width" class="shapeshifter-image-slider-size-label"><?php esc_html_e( 'Thumbnail Width', ShapeShifter_Extensions::TEXTDOMAIN ); ?></label>
				<input type="number" id="shapeshifter-image-slider-slide-thumbnail-width" name="shapeshifter-image-slider-slide-thumbnail-width" value="100">
			</p>
			<p>
				<label for="shapeshifter-image-slider-slide-thumbnail-height" class="shapeshifter-image-slider-size-label"><?php esc_html_e( 'Thumbnail Height', ShapeShifter_Extensions::TEXTDOMAIN ); ?></label>
				<input type="number" id="shapeshifter-image-slider-slide-thumbnail-height" name="shapeshifter-image-slider-slide-thumbnail-height" value="75">
			</p>
		</div>
	</script>

	<script id="wp-theme-shapeshifter-extensions-tinymce-button-settings-slider-image-holder" type="text/template">
		<div id="shapeshifter-image-holder" class="shapeshifter-image-holder"></div>
	</script>

<?php ?>