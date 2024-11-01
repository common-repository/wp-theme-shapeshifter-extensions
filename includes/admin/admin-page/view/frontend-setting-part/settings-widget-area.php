<?php
// ウィジェットの数
$widget_areas_num = intval( count( $options['widget_areas'] ) );
?>
<div class="metabox-holder">
	<div id="widget-areas-settings-wrapper" class="settings-wrapper postbox">

		<h3 id="widget-areas-settings-h2" class="form-table-title hndle"><?php esc_html_e( 'Optional Widget Areas', ShapeShifter_Extensions::TEXTDOMAIN ); ?></h3>

		<div class="inside"><div class="main">

			<table id="widget-areas-settings" class="form-table">
				<tbody>
					<tr class="form-table-box">
						<th scope="row">
							<label for="widget_areas_num">
							</label><?php esc_html_e( 'Num of Optional Widget Areas', ShapeShifter_Extensions::TEXTDOMAIN ); ?>
							</label>
						</th>
						<td>
							<table>
								<tbody>
									<tr>
										<th><label for="<?php echo esc_attr( SSE_THEME_OPTIONS . 'widget_areas_general[num]' ); ?>">
											<?php esc_html_e( 'Enter Num', ShapeShifter_Extensions::TEXTDOMAIN ); ?>
										</label></th>
										<td><input
											type="number" 
											id="widget_areas_num" 
											class="regular-text-field change-target" 
											name="<?php echo esc_attr( SSE_THEME_OPTIONS . 'widget_areas_general[num]' ); ?>" 
											value="<?php echo $widget_areas_num; ?>" 
										/></td>
									</tr>
								</tbody>
							</table>
						</td>
					</tr>
				</tbody>
			</table>

			<p><small><?php esc_html_e( "* Choice 'Custom' for 'Hook to display' is for Child Themes or for Printing in Mobile Sidemenu, because this needs a function 'dynamic_sidebar'.", ShapeShifter_Extensions::TEXTDOMAIN ); ?></small></p>

			<p><small><?php esc_html_e( '* You can sort the widget areas by drag & drop.', ShapeShifter_Extensions::TEXTDOMAIN ); ?></small></p>


			<div id="each-widget-area-settings" class="ui-draggable ui-sortable">

			<?php 

			if( $widget_areas_num > 0 ) { 

				$widget_areas_num_limit = $widget_areas_num;

				for( $widget_areas_count = 0; $widget_areas_count < $widget_areas_num_limit; $widget_areas_count++ ) {

					$widget_areas_count = intval( $widget_areas_count );

					include( SHAPESHIFTER_EXTENSIONS_INCLUDES_DIR . 'admin/admin-page/template/template-form-widget-area.php' );

				}

			} ?>

			</div>		

		</div></div>

	</div>
</div>
