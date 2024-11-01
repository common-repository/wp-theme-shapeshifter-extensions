<?php ?>

<!-- TinyMCE Popup Main Menu Sections -->
	<script id="wp-theme-shapeshifter-extensions-tinymce-button-main-selections" type="text/template">

		<!-- Rows -->
			<div id="shapeshifter-tinymce-button-rows" class="shapeshifter-tinymce-button-section">
				<ul class="shapeshifter-tinymce-button-section-list">
					<!--li><button data-item-type="row" data-column-number="1"><?php esc_html_e( '1 Column', ShapeShifter_Extensions::TEXTDOMAIN ); ?></button></li-->
					<li><button data-item-type="row" data-column-number="2"><?php esc_html_e( '2 Columns', ShapeShifter_Extensions::TEXTDOMAIN ); ?></button></li>
					<li><button data-item-type="row" data-column-number="3"><?php esc_html_e( '3 Columns', ShapeShifter_Extensions::TEXTDOMAIN ); ?></button></li>
					<li><button data-item-type="row" data-column-number="4"><?php esc_html_e( '4 Columns', ShapeShifter_Extensions::TEXTDOMAIN ); ?></button></li>
					<li><button data-item-type="row" data-column-number="5"><?php esc_html_e( '5 Columns', ShapeShifter_Extensions::TEXTDOMAIN ); ?></button></li>
					<li><button data-item-type="row" data-column-number="6"><?php esc_html_e( '6 Columns', ShapeShifter_Extensions::TEXTDOMAIN ); ?></button></li>
				</ul>
			</div>

		<!-- Google Map -->
			<div id="shapeshifter-tinymce-button-maps" class="shapeshifter-tinymce-button-section">
				<ul class="shapeshifter-tinymce-button-section-list">
					<li><button data-item-type="map" data-map-type="standard"><?php esc_html_e( 'Map', ShapeShifter_Extensions::TEXTDOMAIN ); ?></button></li>
				</ul>
			</div>

		<!-- Tables -->
			<div id="shapeshifter-tinymce-button-tables" class="shapeshifter-tinymce-button-section">
				<ul class="shapeshifter-tinymce-button-section-list">
					<li><button data-item-type="table" data-table-type="regular"><?php esc_html_e( 'Regular', ShapeShifter_Extensions::TEXTDOMAIN ); ?></button></li>
				</ul>
			</div>

		<!-- Sliders -->
			<div id="shapeshifter-tinymce-button-sliders" class="shapeshifter-tinymce-button-section">
				<ul class="shapeshifter-tinymce-button-section-list">
					<li><button data-item-type="slider" data-slider-type="images"><?php esc_html_e( 'Images', ShapeShifter_Extensions::TEXTDOMAIN ); ?></button></li>
					<!--li><button data-item-type="slider" data-slider-type="posts"><?php esc_html_e( 'Posts', ShapeShifter_Extensions::TEXTDOMAIN ); ?></button></li>
					<li><button data-item-type="slider" data-slider-type="pages"><?php esc_html_e( 'Pages', ShapeShifter_Extensions::TEXTDOMAIN ); ?></button></li-->
				</ul>
			</div>

		<!-- Links -->
			<div id="shapeshifter-tinymce-button-links" class="shapeshifter-tinymce-button-section">
				<ul class="shapeshifter-tinymce-button-section-list">
					<?php 
					$post_types = get_post_types( array(
						'public' => true,
						//'publicly_queryable' => true
					), 'objects' );
					$post_types['attachment'] = null;
						foreach( $post_types as $name => $post_type ) { if( $post_type === null ) continue;?>
							<li><button data-item-type="link" data-link-type="<?php echo $name; ?>"><?php echo $post_type->label; ?></button></li>
						<?php } 
					?>
				</ul>
			</div>

		<!-- Balloon -->
			<div id="shapeshifter-tinymce-button-balloon" class="shapeshifter-tinymce-button-section">
				<ul class="shapeshifter-tinymce-button-section-list">
					<li><button data-item-type="balloon" data-balloon-type="standard"><?php esc_html_e( 'Standard', ShapeShifter_Extensions::TEXTDOMAIN ); ?></button></li>
				</ul>
			</div>

		<!-- Shortcodes -->
			<div id="shapeshifter-tinymce-button-shortcodes" class="shapeshifter-tinymce-button-section">
				<ul class="shapeshifter-tinymce-button-section-list">
					<li><button data-item-type="shortcode" data-shortcode-type="new-entries"><?php esc_html_e( 'New Entries', ShapeShifter_Extensions::TEXTDOMAIN ); ?></button></li>
					<li><button data-item-type="shortcode" data-shortcode-type="search-entries"><?php echo esc_html_x( 'Search Entries', 'TinyMCE Buttons Menu', ShapeShifter_Extensions::TEXTDOMAIN ); ?></button></li>
				</ul>
			</div>

		<!-- Edit -->
			<div id="shapeshifter-tinymce-button-edit" class="shapeshifter-tinymce-button-section">
				<ul class="shapeshifter-tinymce-button-section-list">
					<!-- CSS Animations -->
					<li><button data-item-type="edit" data-edit-type="css-animations"><?php esc_html_e( 'CSS Animations', ShapeShifter_Extensions::TEXTDOMAIN ); ?></button></li>
				</ul>
			</div>

		<!-- HTML -->
			<div id="shapeshifter-tinymce-button-html" class="shapeshifter-tinymce-button-section">
				<ul class="shapeshifter-tinymce-button-section-list">
					<li><button data-item-type="html" data-html-type="standard"><?php esc_html_e( 'HTML', ShapeShifter_Extensions::TEXTDOMAIN ); ?></button></li>
					<!--li><button data-item-type="html" data-html-type="small-wrapper"><?php esc_html_e( 'Small Wrapper', ShapeShifter_Extensions::TEXTDOMAIN ); ?></button></li-->
				</ul>
			</div>

	</script>



<?php ?>