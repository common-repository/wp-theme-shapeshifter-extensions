<?php
//print_r( get_option( SHAPESHIFTER_EXTENSIONS_OPTION . 'custom_css' ) );
?>
<div class="metabox-holder"><div id="custom-css-settings-wrapper" class="settings-wrapper postbox">
	<h3 id="upload-css-settings-h2" class="form-table-title hndle"><?php esc_html_e( 'How to handle CSS Files', ShapeShifter_Extensions::TEXTDOMAIN ); ?></h3>
	<div class="inside"><div class="main">

		<p><?php esc_html_e( 'You can upload and enqueue your own CSS Files if you want', ShapeShifter_Extensions::TEXTDOMAIN ); ?></p>

		<p><?php esc_html_e( 'Not Possible to apply, to upload and to delete at once.', ShapeShifter_Extensions::TEXTDOMAIN ); ?></p>

	</div></div>
</div></div>

<div class="metabox-holder"><div id="custom-css-settings-wrapper" class="settings-wrapper postbox" style="">
	<h3 id="apply-css-settings-h2" class="form-table-title hndle"><?php esc_html_e( 'Apply CSS', ShapeShifter_Extensions::TEXTDOMAIN ); ?></h3>
	<div class="inside"><div class="main">

		<form id="apply-css-form" method="post" action="themes.php?page=sse_css_settings_menu">

			<?php wp_nonce_field( 'apply-css-file-check', 'apply-css-file-check-nonce' ); ?>

			<!-- ファイルの適用 -->
			<table cellspacing="0" id="custom-css-settings" class="wp-list-table widefat fixed subscribers">

				<thead>
					<tr>
						<th style="" class="manage-column column-cb check-column" id="cb" scope="col"><input type="checkbox"></th>
						<th style="" class="manage-column column-css-family" id="email" scope="col">
							<span><?php esc_html_e( 'CSS', ShapeShifter_Extensions::TEXTDOMAIN ); ?></span>
						</th>
						<th style="" class="manage-column column-file-name" id="type" scope="col">
							<span><?php esc_html_e( 'File Name', ShapeShifter_Extensions::TEXTDOMAIN ); ?></span>
						</th>
					</tr>
				</thead>
			
				<tfoot>
					<tr>
						<th style="" class="manage-column column-cb check-column" scope="col"><input type="checkbox"></th>
						<th style="" class="manage-column column-css-family" scope="col">
							<span><?php esc_html_e( 'CSS', ShapeShifter_Extensions::TEXTDOMAIN ); ?></span>
						</th>
						<th style="" class="manage-column column-file-name" scope="col">
							<span><?php esc_html_e( 'File Name', ShapeShifter_Extensions::TEXTDOMAIN ); ?></span>
						</th>
					</tr>
				</tfoot>

				<tbody>
					<?php foreach( $this->css_files_list as $index => $css_file ) { ?>
						<tr>
							<th class="check-column">
								<input type="checkbox" name="apply-css-file-name[]" value="<?php echo esc_attr( $index ); ?>" class="regular-checkbox" <?php if( is_array( $this->applied_css_files_list ) && in_array( $index, $this->applied_css_files_list ) ) echo 'checked'; ?>>
							</th>
							<td><label>
								<?php echo esc_html( $index ); ?>
							</label></td>
							<td>
								<input type="hidden" name="css-files[]" >
								<?php if( is_array( $css_file ) ) { foreach( $css_file as $index => $file_name ) { ?>
									<p><?php echo esc_html( $file_name ); ?></p>
								<?php } } elseif( is_string( $css_file ) ) { ?>
									<p><?php echo esc_html( $css_file ); ?></p>
								<?php } ?>
							</td>
						</tr>
					<?php } ?>
				</tbody>

			</table>
			<br>
			<input type="submit" name="submit-apply-css-files" class="button-primary" value="<?php esc_attr_e( 'Apply CSS', ShapeShifter_Extensions::TEXTDOMAIN ); ?>" />
		
		</form>

	</div></div>
</div></div>
<div class="metabox-holder"><div id="custom-css-settings-wrapper" class="settings-wrapper postbox" style="">
	<h3 id="upload-css-settings-h2" class="form-table-title hndle"><?php esc_html_e( 'Upload CSS File', ShapeShifter_Extensions::TEXTDOMAIN ); ?></h3>
	<div class="inside"><div class="main">
		
		<form id="upload-css-form" method="post" action="themes.php?page=sse_css_settings_menu" enctype="multipart/form-data">

			<p><?php esc_html_e( 'Please check if file name includes multibyte letters ( like Japanese letters ) before upload it to avoid HTML warning.', ShapeShifter_Extensions::TEXTDOMAIN ); ?></p>
			<p><?php esc_html_e( 'Those letters ( specially combined one  ) sometimes causes "Text run is not in Unicode Normalization Form C" in test tools like HTML Validator', ShapeShifter_Extensions::TEXTDOMAIN ); ?></p>

			<?php wp_nonce_field( 'upload-css-file-check', 'upload-css-file-check-nonce' ); ?>
			<!-- ファイルの追加 -->
			<input type="file" 
				id="upload-css-file" 
				name="upload-css-file" 
				accept="text/css"
			/>
			<br>
			<input type="submit" name="submit-upload-css-file" class="button-primary" value="<?php esc_html_e( 'Upload', ShapeShifter_Extensions::TEXTDOMAIN ); ?>" />

		</form>
	</div></div>
</div></div>
<div class="metabox-holder"><div id="custom-css-settings-wrapper" class="settings-wrapper postbox" style="">
	<h3 id="remove-css-settings-h2" class="form-table-title hndle"><?php esc_html_e( 'Delete CSS', ShapeShifter_Extensions::TEXTDOMAIN ); ?></h3>
	<div class="inside"><div class="main">
		<form id="remove-css-form" method="post" action="themes.php?page=sse_css_settings_menu">

			<?php wp_nonce_field( 'remove-css-file-check', 'remove-css-file-check-nonce' ); ?>

			<!-- ファイルの削除 -->
			<table cellspacing="0" id="custom-css-settings" class="wp-list-table widefat fixed subscribers">

				<thead>
					<tr>
						<th style="" class="manage-column column-cb check-column" id="cb" scope="col"><input type="checkbox"></th>
						<th style="" class="manage-column column-css-family" id="email" scope="col">
							<span><?php esc_html_e( 'CSS', ShapeShifter_Extensions::TEXTDOMAIN ); ?></span>
						</th>
						<th style="" class="manage-column column-file-name" id="type" scope="col">
							<span><?php esc_html_e( 'File Name', ShapeShifter_Extensions::TEXTDOMAIN ); ?></span>
						</th>
					</tr>
				</thead>
			
				<tfoot>
					<tr>
						<th style="" class="manage-column column-cb check-column" scope="col"><input type="checkbox"></th>
						<th style="" class="manage-column column-css-family" scope="col">
							<span><?php esc_html_e( 'CSS', ShapeShifter_Extensions::TEXTDOMAIN ); ?></span>
						</th>
						<th style="" class="manage-column column-file-name" scope="col">
							<span><?php esc_html_e( 'File Name', ShapeShifter_Extensions::TEXTDOMAIN ); ?></span>
						</th>
					</tr>
				</tfoot>

				<tbody>
					<?php foreach( $this->css_files_list as $index => $css_file ) { ?>
						<tr>
							<th class="check-column">
								<input type="checkbox" name="target-css-name[]" value="<?php echo esc_attr( $index ); ?>" class="regular-checkbox">
							</th>
							<td><label>
								<?php echo esc_html( $index ); ?>
							</label></td>
							<td>
								<input type="hidden" name="css-files[]" >
								<?php if( is_array( $css_file ) ) { foreach( $css_file as $index => $file_name ) { ?>
									<p><?php echo esc_html( $file_name ); ?></p>
								<?php } } elseif( is_string( $css_file ) ) { ?>
									<p><?php echo esc_html( $css_file ); ?></p>
								<?php } ?>
							</td>
						</tr>
					<?php } ?>
				</tbody>

			</table>
			<br>
			<input type="submit" name="submit-remove-css-files" class="button-primary" value="<?php esc_attr_e( 'Delete CSS', ShapeShifter_Extensions::TEXTDOMAIN ); ?>" />
		
		</form>

	</div></div>
</div></div>