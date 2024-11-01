<?php ?>

<!-- Link -->
	<script id="wp-theme-shapeshifter-extensions-tinymce-button-settings-link" type="text/template">
		<div id="shapeshifter-link-settings-form-wrapper" class="shapeshifter-settings-form-wrapper shapeshifter-settings-form-wrapper-padding">
			<p>
				<label for="shapeshifter-slider-links"><?php esc_html_e( 'Link', ShapeShifter_Extensions::TEXTDOMAIN ); ?></label>
				<select id="shapeshifter-slider-links" name="shapeshifter-slider-links" class="" style="width: 300px;">
					<option value="none"><?php esc_html_e( 'Select One', ShapeShifter_Extensions::TEXTDOMAIN ); ?></option>
					<?php 
						$post_types_for_query = array();
						foreach( $post_types as $name => $post_type ) {
							$post_types_for_query[] = $name;
						}
						$posts = get_posts( array(
							'post_type' => $post_types_for_query,
							'posts_per_page' => -1
						) );
						foreach( $posts as $post_data ) {
					?>
						<option value="<?php echo esc_attr( $post_data->ID ); ?>"
							data-post-id="<?php echo esc_attr( $post_data->ID ); ?>"
							data-post-author="<?php echo esc_attr( $post_data->post_author ); ?>"
							data-post-title="<?php echo esc_attr( $post_data->post_title ); ?>"
							data-post-date="<?php echo esc_attr( $post_data->post_date ); ?>"
							data-post-modified="<?php echo esc_attr( $post_data->post_modified ); ?>"
							data-post-excerpt="<?php echo esc_attr( mb_substr( str_replace( array( "\n", "\r", "\s" ), '', wp_strip_all_tags( $post_data->post_content ) ), 0, 200 ) ); ?>"
							data-post-type="<?php echo esc_attr( $post_data->post_type ); ?>"
							style="<% if( linkType !== "<?php echo $post_data->post_type; ?>" ) { print( 'display: none;' ) } %>"
						><?php echo esc_html( mb_substr( $post_data->post_title, 0, 20 ) ); ?></option>
					<?php
						}
					?>
				</select>
			</p>
			<p>
				<input type="checkbox" id="shapeshifter-slider-link-is-thumbnail-on" name="shapeshifter-slider-link-is-thumbnail-on" value="on">
				<label for="shapeshifter-slider-link-is-thumbnail-on"><?php esc_html_e( 'With Thumbnail', ShapeShifter_Extensions::TEXTDOMAIN ); ?></label>
			</p>
			<p>
				<input type="checkbox" id="shapeshifter-slider-link-target" name="shapeshifter-slider-link-target">
				<label for="shapeshifter-slider-link-target"><?php esc_html_e( 'New Tab', ShapeShifter_Extensions::TEXTDOMAIN ); ?></label>
			</p>
			<p>
				<input type="checkbox" id="shapeshifter-slider-link-nofollow" name="shapeshifter-slider-link-nofollow">
				<label for="shapeshifter-slider-link-nofollow"><?php esc_html_e( 'Rel Nofollow', ShapeShifter_Extensions::TEXTDOMAIN ); ?></label>
			</p>
		</div>
	</script>

<?php ?>