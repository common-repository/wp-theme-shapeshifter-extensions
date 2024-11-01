<?php
//print_r( get_option( SHAPESHIFTER_EXTENSIONS_OPTION . 'custom_fonts' ) );
?>
<div class="metabox-holder"><div id="custom-fonts-settings-wrapper" class="settings-wrapper postbox">

	<h3 id="upload-fonts-settings-h2" class="form-table-title hndle"><?php esc_html_e( 'Google Fonts', ShapeShifter_Extensions::TEXTDOMAIN ); ?></h3>

	<div class="inside"><div class="main">

		<p><?php esc_html_e( 'How to Use Google Fonts', ShapeShifter_Extensions::TEXTDOMAIN ) ?></p>
		<ol>
			<li><p><?php esc_html_e( 'Enter your Google Fonts API Key and click "Save".', ShapeShifter_Extensions::TEXTDOMAIN ) ?></p></li>
			<li><p><?php esc_html_e( 'Select as many as you want from Listed Fonts and click "Use These Fonts".', ShapeShifter_Extensions::TEXTDOMAIN ) ?></p></li>
		</ol>
		<p><?php esc_html_e( 'These settings enable you to use the font-family ( automatically load in public page ).', ShapeShifter_Extensions::TEXTDOMAIN ) ?></p>
		<p><?php esc_html_e( 'Also, Selected Fonts will be added to fonts list in theme customizer so that you can select there.', ShapeShifter_Extensions::TEXTDOMAIN ) ?></p>
		<p><strong><?php esc_html_e( 'Notice: Loading many fonts might make your website slow. You should remove the check for the Font you won\'t use.', ShapeShifter_Extensions::TEXTDOMAIN ) ?></strong></p>

		<?php
		# Get Google Fonts API Key
			echo '<p>';
				echo '<label for="google-fonts-api-key">' . esc_html__( 'Google Fonts API Key:', ShapeShifter_Extensions::TEXTDOMAIN ) . '</label>';
				echo '<input type="text" id="google-fonts-api-key" name="google-fonts-api-key" value="' . $this->google_fonts_api_key . '">';
				echo '<button id="save-google-fonts-api-key" class="button-primary">' . esc_html__( 'Save', ShapeShifter_Extensions::TEXTDOMAIN ) . '</button>';
			echo '</p>';
		?>

		<form id="google-fonts-settings-form" method="post" action="themes.php?page=sse_font_settings_menu">

			<?php wp_nonce_field( 'google-font-file-check', 'google-font-file-check-nonce' ); ?>

			<!-- Use -->
			<div style="height: 300px; overflow: auto; border: solid #EEEEEE 1px;">
				<table cellspacing="0" id="google-fonts-settings-table" class="wp-list-table widefat fixed subscribers">

					<thead>
						<tr>
							<th style="" class="manage-column column-cb check-column" id="cb" scope="col"><input type="checkbox"></th>
							<th style="" class="manage-column column-font-family" id="email" scope="col">
								<span><?php esc_html_e( 'Font-Family', ShapeShifter_Extensions::TEXTDOMAIN ); ?></span>
							</th>
						</tr>
					</thead>

					<tbody>
						<?php $this->shapeshifter_print_google_fonts_list(); ?>
					</tbody>

					<tfoot>
						<tr>
							<th style="" class="manage-column column-cb check-column" scope="col"><input type="checkbox"></th>
							<th style="" class="manage-column column-font-family" scope="col">
								<span><?php esc_html_e( 'Font-Family', ShapeShifter_Extensions::TEXTDOMAIN ); ?></span>
							</th>
						</tr>
					</tfoot>

				</table>
			</div>
			<br>
			<button id="save-applied-google-fonts" class="button-primary"><?php esc_html_e( 'Use These Fonts', ShapeShifter_Extensions::TEXTDOMAIN ); ?></button>

		</form>
	</div></div>

</div></div>


<div class="metabox-holder"><div id="custom-fonts-settings-wrapper" class="settings-wrapper postbox">
	<h3 id="upload-fonts-settings-h2" class="form-table-title hndle"><?php esc_html_e( 'How to handle Font Files', ShapeShifter_Extensions::TEXTDOMAIN ); ?></h3>
	<div class="inside"><div class="main">

		<p><?php esc_html_e( 'Not Possible to upload and to delete at once.', ShapeShifter_Extensions::TEXTDOMAIN ); ?></p>

		<p><?php esc_html_e( "For uploads, only allows files with tails 'oet' 'otf' 'ttf' 'woff' 'woff2'.", ShapeShifter_Extensions::TEXTDOMAIN ); ?></p>

		<p><?php esc_html_e( "Delete tool deletes all files related to the selected 'Font-Family's.", ShapeShifter_Extensions::TEXTDOMAIN ); ?></p>

		<p><strong>
			<?php esc_html_e( 'You can set the uploaded fonts on theme customizer.', ShapeShifter_Extensions::TEXTDOMAIN ); ?>
		</strong></p>

		<p><strong>
			<?php esc_html_e( "Font files will be uploaded to '/wp-content/uploads/custom-fonts/'.
				If fonts are not displayed correctly, please define 'font-face' by yourself.
				'font-face's uploaded and defined in your own way won't be displayed as choices of font settings of theme customizer.", ShapeShifter_Extensions::TEXTDOMAIN ); ?>
		</strong></p>

	</div></div>
</div></div>

<div class="metabox-holder"><div id="custom-fonts-settings-wrapper" class="settings-wrapper postbox" style="/*display: none;*/">
	<h3 id="upload-fonts-settings-h2" class="form-table-title hndle"><?php _e( 'Upload Font File', ShapeShifter_Extensions::TEXTDOMAIN ); ?></h3>
	<div class="inside"><div class="main">
		
		<form id="upload-font-form" method="post" action="themes.php?page=sse_font_settings_menu" enctype="multipart/form-data">

			<p><?php esc_html_e( 'Please check if file name has multibyte characters ( like Japanese characters ) before upload it. Otherwise, the name will be Hashed.', ShapeShifter_Extensions::TEXTDOMAIN ); ?></p>

			<?php wp_nonce_field( 'upload-font-file-check', 'upload-font-file-check-nonce' ); ?>

			<!-- ファイルの追加 -->
			<input type="file" 
				id="upload-font-file" 
				name="upload-font-file" 
			/>
			<br>
			<input type="submit" name="submit-upload-font-file" class="button-primary" value="<?php esc_attr_e( 'Upload', ShapeShifter_Extensions::TEXTDOMAIN ); ?>" />

		</form>
	</div></div>
</div></div>
<div class="metabox-holder"><div id="custom-fonts-settings-wrapper" class="settings-wrapper postbox" style="">
	<h3 id="remove-fonts-settings-h2" class="form-table-title hndle"><?php esc_html_e( 'Delete Fonts', ShapeShifter_Extensions::TEXTDOMAIN ); ?></h3>
	<div class="inside"><div class="main">
		<form id="remove-font-form" method="post" action="themes.php?page=sse_font_settings_menu">

			<?php wp_nonce_field( 'remove-font-file-check', 'remove-font-file-check-nonce' ); ?>

			<!-- ファイルの削除 -->
			<table cellspacing="0" id="custom-fonts-settings" class="wp-list-table widefat fixed subscribers">

				<thead>
					<tr>
						<th style="" class="manage-column column-cb check-column" id="cb" scope="col"><input type="checkbox"></th>
						<th style="" class="manage-column column-font-family" id="email" scope="col">
							<span><?php esc_html_e( 'Font-Family', ShapeShifter_Extensions::TEXTDOMAIN ); ?></span>
						</th>
						<th style="" class="manage-column column-file-name" id="type" scope="col">
							<span><?php esc_html_e( 'File Name', ShapeShifter_Extensions::TEXTDOMAIN ); ?></span>
						</th>
					</tr>
				</thead>
			
				<tfoot>
					<tr>
						<th style="" class="manage-column column-cb check-column" scope="col"><input type="checkbox"></th>
						<th style="" class="manage-column column-font-family" scope="col">
							<span><?php esc_html_e( 'Font-Family', ShapeShifter_Extensions::TEXTDOMAIN ); ?></span>
						</th>
						<th style="" class="manage-column column-file-name" scope="col">
							<span><?php esc_html_e( 'File Name', ShapeShifter_Extensions::TEXTDOMAIN ); ?></span>
						</th>
					</tr>
				</tfoot>

				<tbody>
					<?php if( is_array( $this->font_files_list ) ) { foreach( $this->font_files_list as $index => $font_file ) { ?>
						<tr>
							<th class="check-column">
								<input type="checkbox" name="target-font-name[]" value="<?php echo esc_attr( $index ); ?>" class="regular-checkbox">
							</th>
							<td><label>
								<?php echo esc_html( $index ); ?>
							</label></td>
							<td>
								<input type="hidden" name="font-files[]" >
								<?php if( is_array( $font_file ) ) { foreach( $font_file as $index => $file_name ) { ?>
									<p><?php echo esc_html( $file_name ); ?></p>
								<?php } } elseif( is_string( $font_file ) ) { ?>
									<p><?php echo esc_html( $font_file ); ?></p>
								<?php } ?>
							</td>
						</tr>
					<?php } } ?>
				</tbody>

			</table>
			<br>
			<input type="submit" name="submit-remove-fonts-files" class="button-primary" value="<?php esc_attr_e( 'Delete Fonts', ShapeShifter_Extensions::TEXTDOMAIN ); ?>" />
		
		</form>

	</div></div>
</div></div>

<?php

?>