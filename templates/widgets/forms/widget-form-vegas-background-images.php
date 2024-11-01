<?php

$defaults = array(
	'image_url_str' => '',
	'download_transition' => 'fade',
	'download_delay' => 3000,
	'download_transition_duration' => 2000,
);
$instance = wp_parse_args( ( array ) $instance, $defaults );

$image_url_str = $instance['image_url_str'];
$image_slider_array = preg_split( '/\,/', $image_url_str );

?>
<a id="popup-next-vegas-settings-box-<?php echo esc_attr( $this->number ); ?>" class="button popup-next-settings-box" href="javascript:void( 0 );"><?php esc_html_e( 'Settings of Sliding Background Images', ShapeShifter_Extensions::TEXTDOMAIN ); ?></a>

<div id="widget-settings-box-<?php echo esc_attr( $this->number ); ?>" class="widget-settings-box widget-vegas-background-images-settings-box">

	<p>
		<label for="<?php echo esc_attr( $this->get_field_id( 'image_url_str' ) ); ?>">
			<strong><?php esc_html_e( 'Select Background Images', ShapeShifter_Extensions::TEXTDOMAIN ); //背景画像の選択 ?></strong><br/>
		</label>

		<div id="image-box-<?php echo esc_attr( $this->id ); ?>" class="image-box">
			<?php if( is_array( $image_slider_array ) && 0 < count( $image_slider_array ) ) {
			foreach( $image_slider_array as $image_slider_url ) { ?>
				<img src="<?php echo esc_url( $image_slider_url ); ?>" class="images-from-library" style="width:100px; height:100px; float:left;">
			<?php }
			} ?>
		</div>
		<div style="clear: both; margin-bottom: 10px;"></div>

		<input class="widefat image-urls" id="<?php echo esc_attr( $this->get_field_id( 'image_url_str' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'image_url_str' ) ); ?>" type="hidden" value="<?php echo esc_attr( $image_url_str ); ?>" />
		<a href="javascript:shapeshifterGeneralMethods.getMediaFromLibrary( 'textarea', '#<?php echo esc_attr( $this->get_field_id( 'image_url_str' ) ); ?>', '#image-box-<?php echo esc_attr( $this->id ); ?>', true );" id="set-image-box-<?php echo esc_attr( $this->id ); ?>" class="button customaddmedia" style="margin: 10px;">
			<?php esc_html_e( 'Search for Images', ShapeShifter_Extensions::TEXTDOMAIN ); ?>
		</a>
		<a href="javascript:shapeshifterGeneralMethods.removeImagesFromBox( '#<?php echo esc_attr( $this->get_field_id( 'image_url_str' ) ); ?>', '#image-box-<?php echo esc_attr( $this->id ); ?>' );" id="remove-image-box-<?php echo esc_attr( $this->id ); ?>" class="button customaddmedia" style="margin-top: 10px;">
			<?php esc_html_e( 'Reset', ShapeShifter_Extensions::TEXTDOMAIN ); ?>
		</a>
	</p>

	<p>
		<label for="<?php echo esc_attr( $this->get_field_id( 'download_transition' ) ); ?>">
			<strong><?php esc_html_e( 'Transition Type to change images', ShapeShifter_Extensions::TEXTDOMAIN ); ?></strong>
		</label><br>
		<select name="<?php echo esc_attr( $this->get_field_name( 'download_transition' ) ); ?>"
			id="<?php echo esc_attr( $this->get_field_id( 'download_transition' ) ); ?>"
			class="regular-select" 
	   	>
	   		<?php foreach( self::$vegas_transition_array as $index => $value ) { ?>
				<option value="<?php echo esc_attr( $index ); ?>" <?php selected( $index, $instance['download_transition'], true ); ?>><?php echo esc_html( $value ); ?></option>
	   		<?php } ?>
		</select>
	</p>

	<p>
		<label for="<?php echo esc_attr( $this->get_field_id( 'download_delay' ) ); ?>">
			<strong><?php esc_html_e( 'Delay to change images', ShapeShifter_Extensions::TEXTDOMAIN ); ?></strong>
		</label><br>
		<input name="<?php echo esc_attr( $this->get_field_name( 'download_delay' ) ); ?>"
			type="range"
			id="<?php echo esc_attr( $this->get_field_id( 'download_delay' ) ); ?>"
			class="regular-slider" 
			min="1000"
			max="20000"
			value="<?php echo intval( $instance['download_delay'] ); ?>"
	   	/>
	</p>

	<p>
		<label for="<?php echo esc_attr( $this->get_field_id( 'download_transition_duration' ) ); ?>">
			<strong><?php esc_html_e( 'Transition Duration to change Images', ShapeShifter_Extensions::TEXTDOMAIN ); ?></strong>
		</label><br>
		<input name="<?php echo esc_attr( $this->get_field_name( 'download_transition_duration' ) ); ?>"
			type="range"
			id="<?php echo esc_attr( $this->get_field_id( 'download_transition_duration' ) ); ?>"
			class="regular-slider" 
			min="1000"
			max="10000"
			value="<?php echo intval( $instance['download_transition_duration'] ); ?>"
	   	/>
	</p>

	<a id="close-vegas-settings-box-<?php echo esc_attr( $this->number ); ?>" class="button close-this-settings-box" href="javascript:void( 0 );"><?php esc_html_e( 'Close', ShapeShifter_Extensions::TEXTDOMAIN ); ?></a>

</div>
