<?php 
global $post, $wp_widget_factory;

?>
<!-- Forms -->
	<!-- TinyMCE Popup Side Menu -->
		<?php include_once( 'tinymce/popup-menu/side-menu.php' ); ?>

	<!-- TinyMCE Popup Main Menu Sections -->
		<?php include_once( 'tinymce/popup-menu/main-menu.php' ); ?>

<!-- TinyMCE Settings Form -->
	<!-- Row -->
		<?php include_once( 'tinymce/popup-forms/row.php' ); ?>

	<!-- Column -->
		<?php include_once( 'tinymce/popup-forms/column.php' ); ?>

	<!-- Google Map -->
		<?php include_once( 'tinymce/popup-forms/google-map.php' ); ?>

	<!-- Table -->
		<?php include_once( 'tinymce/popup-forms/table.php' ); ?>

	<!-- Slider -->
		<?php include_once( 'tinymce/popup-forms/slider.php' ); ?>

	<!-- Link -->
		<?php include_once( 'tinymce/popup-forms/link.php' ); ?>

	<!-- Balloon -->
		<?php include_once( 'tinymce/popup-forms/balloon.php' ); ?>

	<!-- Shortcodes -->
		<?php include_once( 'tinymce/popup-forms/shortcodes.php' ); ?>

<!-- TinyMCE Insert -->
	<!-- Row -->
		<?php include_once( 'tinymce/inserts/row.php' ); ?>

			<!-- Column -->
				<?php include_once( 'tinymce/inserts/column.php' ); ?>

	<!-- Google Map -->
		<?php include_once( 'tinymce/inserts/google-map.php' ); ?>

	<!-- Table -->
		<?php include_once( 'tinymce/inserts/table.php' ); ?>

	<!-- Slider -->
		<!-- Image Slider -->
			<?php include_once( 'tinymce/inserts/image-slider.php' ); ?>

	<!-- Link -->
		<!-- Post -->
			<?php include_once( 'tinymce/inserts/link.php' ); ?>

	<!-- Balloon -->
		<?php include_once( 'tinymce/inserts/balloon.php' ); ?>

	<!-- Shortcode -->
		<?php include_once( 'tinymce/inserts/shortcodes.php' ); ?>
		<script id="wp-theme-shapeshifter-extensions-tinymce-button-template-shortcode" type="text/template">
		</script>

<!-- Shortcode Slider -->
	<script id="shapeshifter-js-templates-shortcode-slider" type="text/template">
		<div id="<%- wrapperID %>" class="<%- wrapperClass %>">
			<div class="sp-slides">
				<div class="sp-slide">
					<img class="sp-image" src="" data-src="">
					<div class="sp-layer"></div>
				</div>
				<div class="sp-thumbnail">
					
				</div>
			</div>
		</div>
	</script>
