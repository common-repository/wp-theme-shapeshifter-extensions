<?php
$options = array();
foreach ( sse()->get_options() as $index => $data ) {
	$options[ $index ] = $data->get_data();
}

?>
<div class="wrap">
	<h1><?php printf( 'Settings by %s', esc_html( SSE_THEME_NAME ) ); ?></h1>
	
	<form id="<?php echo SSE_THEME_OPTIONS; ?>" method="post">
		
	<?php 
		$table_tabs = array(
			'general-settings' => esc_html__( 'General Settings', ShapeShifter_Extensions::TEXTDOMAIN ),//一般設定
			'auto-insert-settings' => esc_html__( 'Auto Insert', ShapeShifter_Extensions::TEXTDOMAIN ),//自動挿入設定
			'speed-adjust-settings' => esc_html__( 'Page Speed', ShapeShifter_Extensions::TEXTDOMAIN ),//速度調節設定
			'widget-areas-settings' => esc_html__( 'Optional Widget Areas', ShapeShifter_Extensions::TEXTDOMAIN ),//ウィジェットエリアの設定
			'seo-settings' => esc_html__( 'SEO', ShapeShifter_Extensions::TEXTDOMAIN ),//SEOの標準設定
			'others-settings' => esc_html__( 'Other', ShapeShifter_Extensions::TEXTDOMAIN ),//その他の設定
			//'debug-mode-settings' => esc_html__( 'Debug Mode', ShapeShifter_Extensions::TEXTDOMAIN ),//デバッグモードの設定
		);
		
		echo '<ul class="tabs table-tabs frontend-settings">';
			foreach( $table_tabs as $class => $text ) {
				$classes = array( 'tab', $class );
				if( $class === $options['general']['default_settings_tab'] ) {
					array_push( $classes, 'selected' );
				}
				$text = esc_html( $text );
				echo '<li class="' . esc_attr( implode( ' ', $classes ) ) . '" data-tab="' . $class . '">
					<a class="tab-a ' . $class . '-tab-a" data-tab="' . $class . '" href="javascript:void(0);">' . $text . '</a>
				</li>';
			}			   
		echo '</ul>
		
		<div class="clearfix"></div>';

		wp_nonce_field(
			sse()->get_prefixed_option_name( 'nonce' ),
			sse()->get_prefixed_option_name( 'nonce_action' )
		);

	?>

		<!-- 一般設定 -->
		<?php require_once( 'frontend-setting-part/settings-general.php' ); ?>

		<!-- 自動挿入設定 -->
		<?php require_once( 'frontend-setting-part/settings-auto-insert.php' ); ?>

		<!-- 速度調節設定 -->
		<?php require_once( 'frontend-setting-part/settings-speed-adjust.php' ); ?>
		
		<!-- ウィジェットエリア設定 -->
		<?php require_once( 'frontend-setting-part/settings-widget-area.php' ); ?>
		
		<!-- SEO設定 -->
		<?php require_once( 'frontend-setting-part/settings-seo.php' ); ?>

		<!-- その他の設定 -->
		<?php require_once( 'frontend-setting-part/settings-others.php' ); ?>

		<!-- デバッグモード設定 -->
		<?php //require_once( 'frontend-setting-part/settings-debut-mode.php' ); ?>
		
		<?php submit_button( null, 'primary', 'sse-frontend-setting-submit' ); ?>
		
	</form>
</div>
<?php 
