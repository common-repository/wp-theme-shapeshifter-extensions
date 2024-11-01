<a id="popup-next-detect-settings-box-<?php echo esc_attr( $widget->number ); ?>" class="button popup-next-settings-box" href="javascript:void( 0 );"><?php esc_html_e( 'Settings to Detect Conditions', ShapeShifter_Extensions::TEXTDOMAIN ); ?></a>

<div id="widget-detect-settings-box-<?php echo esc_attr( $widget->number ); ?>" class="widget-settings-box widget-display-detect-settings-box">

	<p>
		<label>
			<strong><?php esc_html_e( 'Priority Settings for Display', ShapeShifter_Extensions::TEXTDOMAIN ); ?></strong>
		</label><br>

		<input type="checkbox" id="<?php echo esc_attr( $widget->get_field_id( 'shapeshifter_display_home' ) ); ?>" class="widefat" name="<?php echo esc_attr( $widget->get_field_name( 'shapeshifter_display_home' ) ); ?>" value="shapeshifter_display_home" <?php checked( $shapeshifter_display_home, 'shapeshifter_display_home' ); ?> style="width:0;"/><span><?php esc_html_e( 'Home', ShapeShifter_Extensions::TEXTDOMAIN ); ?></span><br>

		<input type="checkbox" id="<?php echo esc_attr( $widget->get_field_id( 'shapeshifter_display_front' ) ); ?>" class="widefat" name="<?php echo esc_attr( $widget->get_field_name( 'shapeshifter_display_front' ) ); ?>" value="shapeshifter_display_front" <?php checked( $shapeshifter_display_front, 'shapeshifter_display_front' ); ?> style="width:0;"/><span><?php esc_html_e( 'Front Page', ShapeShifter_Extensions::TEXTDOMAIN ); ?></span><br>

		<input type="checkbox" id="<?php echo esc_attr( $widget->get_field_id( 'shapeshifter_display_blog' ) ); ?>" class="widefat" name="<?php echo esc_attr( $widget->get_field_name( 'shapeshifter_display_blog' ) ); ?>" value="shapeshifter_display_blog" <?php checked( $shapeshifter_display_blog, 'shapeshifter_display_blog' ); ?> style="width:0;"/><span><?php esc_html_e( 'Blog Page', ShapeShifter_Extensions::TEXTDOMAIN ); ?></span><br>

		<label>
			<strong><?php esc_html_e( 'Display Settings for each pages', ShapeShifter_Extensions::TEXTDOMAIN ); ?></strong>
		</label><br>

		<input type="checkbox" id="<?php echo esc_attr( $widget->get_field_id( 'shapeshifter_display_archive' ) ); ?>" class="widefat" name="<?php echo esc_attr( $widget->get_field_name( 'shapeshifter_display_archive' ) ); ?>" value="shapeshifter_display_archive" <?php checked( $shapeshifter_display_archive, 'shapeshifter_display_archive' ); ?> style="width:0;"/><span><?php esc_html_e( 'Archive Pages', ShapeShifter_Extensions::TEXTDOMAIN ); ?></span><br>

		<input type="checkbox" id="<?php echo esc_attr( $widget->get_field_id( 'shapeshifter_display_singular' ) ); ?>" class="widefat" name="<?php echo esc_attr( $widget->get_field_name( 'shapeshifter_display_singular' ) ); ?>" value="shapeshifter_display_singular" <?php checked( $shapeshifter_display_singular, 'shapeshifter_display_singular' ); ?> style="width:0;"/><span><?php esc_html_e( 'Content Pages', ShapeShifter_Extensions::TEXTDOMAIN ); ?></span><br>
	</p>

	<p>
		<label>
			<strong><?php esc_html_e( 'Check Not to be Displayed in Pages Below', ShapeShifter_Extensions::TEXTDOMAIN ); ?></strong>
		</label><br>

		<?php foreach( $post_types as $post_type ) { 
			if( in_array( $post_type, array( 'revision', 'nav_menu_item', 'product', 'product_variation', 'shop_order', 'shop_order_refund', 'shop_coupon', 'shop_webhook' ) ) ) continue;
			?>
			<input type="checkbox" id="<?php echo esc_attr( $widget->get_field_id( 'shapeshifter_display_' . $post_type ) ); ?>" class="widefat" name="<?php echo esc_attr( $widget->get_field_name( 'shapeshifter_display_' . $post_type ) ); ?>" value="shapeshifter_display_<?php echo esc_attr( $post_type ); ?>" <?php checked( $shapeshifter_display[ $post_type ], 'shapeshifter_display_' . $post_type ); ?> style="width:0;"/><span><?php echo esc_html( $post_type ); ?></span><br>
		<?php }
		?>
	</p>

	<?php if( function_exists( 'is_woocommerce' ) ) { 
		$shapeshifter_not_display_woocommerce = esc_attr( 
			( isset( $instance['shapeshifter_not_display_woocommerce'] ) 
				&& $instance['shapeshifter_not_display_woocommerce'] != '' 
			? $instance['shapeshifter_not_display_woocommerce'] 
			: '' )
		);
		$shapeshifter_not_display_cart = esc_attr( 
			( isset( $instance['shapeshifter_not_display_cart'] ) 
				&& $instance['shapeshifter_not_display_cart'] != '' 
			? $instance['shapeshifter_not_display_cart'] 
			: '' ) 
		);
		$shapeshifter_not_display_checkout = esc_attr(  
			( isset( $instance['shapeshifter_not_display_checkout'] ) 
				&& $instance['shapeshifter_not_display_checkout'] != '' 
			? $instance['shapeshifter_not_display_checkout'] 
			: '' ) 
		);
		$shapeshifter_not_display_account_page = esc_attr(
			( isset( $instance['shapeshifter_not_display_account_page'] ) 
				&& $instance['shapeshifter_not_display_account_page'] != '' 
			? $instance['shapeshifter_not_display_account_page'] 
			: '' ) 
		);
		?>
		<p>

			<label>
				<strong><?php esc_html_e( 'Check Not to be Displayed in Pages Below ( for WooCommerce Pages )', ShapeShifter_Extensions::TEXTDOMAIN ); ?></strong>
			</label><br>

			<input type="checkbox" id="<?php echo esc_attr( $widget->get_field_id( 'shapeshifter_not_display_woocommerce' ) ); ?>" class="widefat" name="<?php echo esc_attr( $widget->get_field_name( 'shapeshifter_not_display_woocommerce' ) ); ?>" value="shapeshifter_not_display_woocommerce" <?php checked( $shapeshifter_not_display_woocommerce, 'shapeshifter_not_display_woocommerce' ); ?> style="width:0;"/><span><?php esc_html_e( 'Shop or Product Pages', ShapeShifter_Extensions::TEXTDOMAIN ); ?></span><br>

			<input type="checkbox" id="<?php echo esc_attr( $widget->get_field_id( 'shapeshifter_not_display_cart' ) ); ?>" class="widefat" name="<?php echo esc_attr( $widget->get_field_name( 'shapeshifter_not_display_cart' ) ); ?>" value="shapeshifter_not_display_cart" <?php checked( $shapeshifter_not_display_cart, 'shapeshifter_not_display_cart' ); ?> style="width:0;"/><span><?php esc_html_e( 'Cart Page', ShapeShifter_Extensions::TEXTDOMAIN ); ?></span><br>

			<input type="checkbox" id="<?php echo esc_attr( $widget->get_field_id( 'shapeshifter_not_display_checkout' ) ); ?>" class="widefat" name="<?php echo esc_attr( $widget->get_field_name( 'shapeshifter_not_display_checkout' ) ); ?>" value="shapeshifter_not_display_checkout" <?php checked( $shapeshifter_not_display_checkout, 'shapeshifter_not_display_checkout' ); ?> style="width:0;"/><span><?php esc_html_e( 'Checkout Page', ShapeShifter_Extensions::TEXTDOMAIN ); ?></span><br>

			<input type="checkbox" id="<?php echo esc_attr( $widget->get_field_id( 'shapeshifter_not_display_account_page' ) ); ?>" class="widefat" name="<?php echo esc_attr( $widget->get_field_name( 'shapeshifter_not_display_account_page' ) ); ?>" value="shapeshifter_not_display_account_page" <?php checked( $shapeshifter_not_display_account_page, 'shapeshifter_not_display_account_page' ); ?> style="width:0;"/><span><?php esc_html_e( 'Account Page', ShapeShifter_Extensions::TEXTDOMAIN ); ?></span><br>

		</p>
	<?php } ?>

	<p>
		<label>
			<strong><?php esc_html_e( 'Select displayed Devices', ShapeShifter_Extensions::TEXTDOMAIN ); ?></strong>
		</label><br>
		<input type="radio" id="<?php echo esc_attr( $widget->get_field_id( 'shapeshifter_display_device' ) ); ?>" class="widefat" name="<?php echo esc_attr( $widget->get_field_name( 'shapeshifter_display_device' ) ); ?>" value="shapeshifter_display_any" <?php checked( $shapeshifter_display_device, 'shapeshifter_display_any' ); ?> style="width:0;"/><span><?php esc_html_e( 'All Devices', ShapeShifter_Extensions::TEXTDOMAIN ); ?></span><br>
		<input type="radio" id="<?php echo esc_attr( $widget->get_field_id( 'shapeshifter_display_device' ) ); ?>" class="widefat" name="<?php echo esc_attr( $widget->get_field_name( 'shapeshifter_display_device' ) ); ?>" value="shapeshifter_display_pc" <?php checked( $shapeshifter_display_device, 'shapeshifter_display_pc' ); ?> style="width:0;"/><span><?php esc_html_e( 'PC', ShapeShifter_Extensions::TEXTDOMAIN ); ?></span><br>
		<input type="radio" id="<?php echo esc_attr( $widget->get_field_id( 'shapeshifter_display_device' ) ); ?>" class="widefat" name="<?php echo esc_attr( $widget->get_field_name( 'shapeshifter_display_device' ) ); ?>" value="shapeshifter_display_mobile" <?php checked( $shapeshifter_display_device, 'shapeshifter_display_mobile' ); ?> style="width:0;"/><span><?php esc_html_e( 'Only Mobile Devices', ShapeShifter_Extensions::TEXTDOMAIN ); ?></span><br>
		<input type="radio" id="<?php echo esc_attr( $widget->get_field_id( 'shapeshifter_display_device' ) ); ?>" class="widefat" name="<?php echo esc_attr( $widget->get_field_name( 'shapeshifter_display_device' ) ); ?>" value="shapeshifter_display_tablet" <?php checked( $shapeshifter_display_device, 'shapeshifter_display_tablet' ); ?> style="width:0;"/><span><?php esc_html_e( 'Only Tablet Devices ( not Phones )', ShapeShifter_Extensions::TEXTDOMAIN ); ?></span><br>
		<input type="radio" id="<?php echo esc_attr( $widget->get_field_id( 'shapeshifter_display_device' ) ); ?>" class="widefat" name="<?php echo esc_attr( $widget->get_field_name( 'shapeshifter_display_device' ) ); ?>" value="shapeshifter_display_phone" <?php checked( $shapeshifter_display_device, 'shapeshifter_display_phone' ); ?> style="width:0;"/><span><?php esc_html_e( 'Only Phones ( not Tablet Devices )', ShapeShifter_Extensions::TEXTDOMAIN ); ?></span><br>
	</p>

	<a id="close-detect-settings-box-<?php echo esc_attr( $widget->number ); ?>" class="button close-this-settings-box" href="javascript:void( 0 );"><?php esc_html_e( 'Close', ShapeShifter_Extensions::TEXTDOMAIN ); ?></a>

</div>
