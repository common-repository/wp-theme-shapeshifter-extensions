<?php
/**
 * Required Params:
 * 	$widget_index : starts from 0
 *  $widget_number: Used as default widget number for title
 *  $
**/
$widget_index  = absint( $widget_areas_count );
$widget_number = $widget_index + 1;

$saved_hook              = $options['widget_areas'][ $widget_areas_count ]['hook'];
$saved_width             = $options['widget_areas'][ $widget_areas_count ]['width'];
$saved_is_on_mobile_menu = ( 
	( isset( $options['widget_areas'][ $widget_areas_count ]['is_on_mobile_menu'] ) 
		&& '' !== $options['widget_areas'][ $widget_areas_count ]['is_on_mobile_menu']
	)
	? 'is_on_mobile_menu' 
	: ''
);
$saved_name              = $options['widget_areas'][ $widget_areas_count ]['name'];
$saved_description       = $options['widget_areas'][ $widget_areas_count ]['description'];

$row_hook = '<tr class="widget-area-hook">
	<th scope="row"><label for="' . esc_attr( sse()->get_prefixed_theme_option_name( 'widget_areas[' . $widget_areas_count . '][hook]' ) ) . '">' . esc_html__( 'Hook to display', ShapeShifter_Extensions::TEXTDOMAIN ) . '</label></th>
	<td>
		<select 
			id="' . esc_attr( 'widget_area_' . $widget_areas_count . '_hook' ) . '" 
			name="' . esc_attr( sse()->get_prefixed_theme_option_name( 'widget_areas[' . $widget_areas_count . '][hook]' ) ) . '" 
			class="widget-area-hook-select"
			data-widget-area-count="' . esc_attr( $widget_areas_count ) . '"
		>
			<option value="customize" ' . selected( $saved_hook, 'customize', false ) . '>' . esc_html__( 'Custom', ShapeShifter_Extensions::TEXTDOMAIN ) . '</option>
			<option value="after_header" ' . selected( $saved_hook, 'after_header', false ) . '>' . esc_html__( 'After the Header', ShapeShifter_Extensions::TEXTDOMAIN ) . '</option>
			<option value="before_content_area" ' . selected( $saved_hook, 'before_content_area', false ) . '>' . esc_html__( 'Before Content Area', ShapeShifter_Extensions::TEXTDOMAIN ) . '</option>
			<option value="before_content" ' . selected( $saved_hook, 'before_content', false ) . '>' . esc_html__( 'Before the Content', ShapeShifter_Extensions::TEXTDOMAIN ) . '</option>
			<option value="beginning_of_content" ' . selected( $saved_hook, 'beginning_of_content', false ) . '>' . esc_html__( 'At the Beginning of the Content', ShapeShifter_Extensions::TEXTDOMAIN ) . '</option>
			<option value="before_1st_h2_of_content" ' . selected( $saved_hook, 'before_1st_h2_of_content', false ) . '>' . esc_html__( 'Before the First H2 tag of the Content', ShapeShifter_Extensions::TEXTDOMAIN ) . '</option>
			<option value="end_of_content" ' . selected( $saved_hook, 'end_of_content', false ) . '>' . esc_html__( 'At the End of the Content', ShapeShifter_Extensions::TEXTDOMAIN ) . '</option>
			<option value="after_content" ' . selected( $saved_hook, 'after_content', false ) . '>' . esc_html__( 'After the Content', ShapeShifter_Extensions::TEXTDOMAIN ) . '</option>
			<option value="before_footer" ' . selected( $saved_hook, 'before_footer', false ) . '>' . esc_html__( 'Before the Footer', ShapeShifter_Extensions::TEXTDOMAIN ) . '</option>
			<option value="in_footer" ' . selected( $saved_hook, 'in_footer', false ) . '>' . esc_html__( 'In the Footer', ShapeShifter_Extensions::TEXTDOMAIN ) . '</option>
		</select>
	</td>
</tr>';

$row_width = '<tr class="widget-area-width">
	<th scope="row"><label for="' . esc_attr( sse()->get_prefixed_theme_option_name( 'widget_areas[' . $widget_areas_count . '][width]' ) ) . '">' . esc_html__( 'Width', ShapeShifter_Extensions::TEXTDOMAIN ) . '</label></th>
	<td>
		<select 
			id="' . esc_attr( 'widget_area_' . $widget_areas_count . '_width' ) . '" 
			class="widget-area-width-select"
			name="' . esc_attr( sse()->get_prefixed_theme_option_name( 'widget_areas[' . $widget_areas_count . '][width]' ) ) . '" 
			data-widget-area-count="' . esc_attr( $widget_areas_count ) . '"
		>
			<option value="100%" ' . selected( $saved_width, '100%', false ) . '>' . esc_html__( 'Maximum ( 100% )', ShapeShifter_Extensions::TEXTDOMAIN ) . '</option>
			<option value="auto" ' . selected( $saved_width, 'auto', false ) . '>' . esc_html__( 'Adapted to Content Area Width', ShapeShifter_Extensions::TEXTDOMAIN ) . '</option>
			<option value="1280px" ' . selected( $saved_width, '1280px', false ) . '>' . esc_html__( '1280px ( width for 3 columns )', ShapeShifter_Extensions::TEXTDOMAIN ) . '</option>
			<option value="960px" ' . selected( $saved_width, '960px', false ) . '>' . esc_html__( '960px ( width for 2 columns )', ShapeShifter_Extensions::TEXTDOMAIN ) . '</option>
			<option value="870px" ' . selected( $saved_width, '870px', false ) . '>' . esc_html__( '870px ( width for 1 column )', ShapeShifter_Extensions::TEXTDOMAIN ) . '</option>
			<option value="600px" ' . selected( $saved_width, '600px', false ) . '>' . esc_html__( '600px', ShapeShifter_Extensions::TEXTDOMAIN ) . '</option>
			<option value="400px" ' . selected( $saved_width, '400px', false ) . '>' . esc_html__( '400px', ShapeShifter_Extensions::TEXTDOMAIN ) . '</option>
			<option value="300px" ' . selected( $saved_width, '300px', false ) . '>' . esc_html__( '300px', ShapeShifter_Extensions::TEXTDOMAIN ) . '</option>
		</select>
	</td>
</tr>';

$row_is_on_mobile_menu = '<tr class="widget-area-is_on_mobile_menu">
	<th scope="row"><label for="' . esc_attr( sse()->get_prefixed_theme_option_name( 'widget_areas[' . $widget_areas_count . '][is_on_mobile_menu]' ) ) . '">' . esc_html__( 'Print in Side Menu ( only for Mobile )', ShapeShifter_Extensions::TEXTDOMAIN ) . '</label></th>
	<td><input
		type="checkbox" 
		id="' . esc_attr( 'widget_area_' . $widget_areas_count . '_is_on_mobile_menu' ) . '" 
		class="regular-checkbox" 
		name="' . esc_attr( sse()->get_prefixed_theme_option_name( 'widget_areas[' . $widget_areas_count . '][is_on_mobile_menu]' ) ) . '" 
		value="is_on_mobile_menu"
		' . checked( $saved_is_on_mobile_menu, 'is_on_mobile_menu', false ) . '" 
		data-widget-area-count="' . esc_attr( $widget_areas_count ) . '"
	/></td>
</tr>';

/*
$row_id = '<tr class="widget-area-id" style="display: none;">
	<th scope="row"><label for="' . esc_attr( sse()->get_prefixed_theme_option_name( 'widget_areas[' . $widget_areas_count . '][id]' ) ) . '">' . esc_html__( 'ID', ShapeShifter_Extensions::TEXTDOMAIN ) . '</label></th>
	<td><input
		type="text" 
		id="' . esc_attr( 'widget_area_' . $widget_areas_count . '_id' ) . '" 
		class="regular-text-field change-target" 
		name="' . esc_attr( sse()->get_prefixed_theme_option_name( 'widget_areas[' . $widget_areas_count . '][id]' ) ) . '" 
		value="' . esc_attr( $saved_id ) . '" 
		style="width: 100%;"
	/></td>
</tr>';

$row_class = '<tr class="widget-area-class" style="display: none;">
	<th scope="row"><label for="' . esc_attr( sse()->get_prefixed_theme_option_name( 'widget_areas[' . $widget_areas_count . '][class]' ) ) . '">
		' . esc_html__( 'Class', ShapeShifter_Extensions::TEXTDOMAIN ) . '
	</label></th>
	<td><input
		type="text" 
		id="' . esc_attr( 'widget_area_' . $widget_areas_count . '_class' ) . '" 
		class="regular-text-field change-target" 
		name="' . esc_attr( sse()->get_prefixed_theme_option_name( 'widget_areas[' . $widget_areas_count . '][class]' ) ) . '" 
		value="' . esc_attr( $saved_class ) . '" 
		style="width: 100%;"
	/></td>
</tr>';
*/
$row_name = '<tr class="widget-area-name">
	<th scope="row"><label for="' . esc_attr( sse()->get_prefixed_theme_option_name( 'widget_areas[' . $widget_areas_count . '][name]' ) ) . '">' . esc_html__( 'Name', ShapeShifter_Extensions::TEXTDOMAIN ) . '</label></th>
	<td><input
		type="text" 
		id="' . esc_attr( 'widget_area_' . $widget_areas_count . '_name' ) . '" 
		class="regular-text-field change-target" 
		name="' . esc_attr( sse()->get_prefixed_theme_option_name( 'widget_areas[' . $widget_areas_count . '][name]' ) ) . '" 
		value="' . esc_attr( $saved_name ) . '" 
		style="width: 100%;"
	/></td>
</tr>';

$row_description = '<tr class="widget-area-description">
	<th scope="row"><label for="' . esc_attr( sse()->get_prefixed_theme_option_name( 'widget_areas[' . $widget_areas_count . '][description]' ) ) . '">' . esc_html__( 'Description', ShapeShifter_Extensions::TEXTDOMAIN ) . '</label></th>
	<td><textarea
		id="' . esc_attr( 'widget_area_' . $widget_areas_count . '_description' ) . '" 
		name="' . esc_attr( sse()->get_prefixed_theme_option_name( 'widget_areas[' . $widget_areas_count . '][description]' ) ) . '" 
		style="width: 100%;"
	>' . html_entity_decode( $saved_description ) . '</textarea></td>
</tr>';

/*
$row_before_widget = '<tr class="widget-area-before_widget" style="display: none;">
	<th scope="row"><label for="' . esc_attr( sse()->get_prefixed_theme_option_name( 'widget_areas[' . $widget_areas_count . '][before_widget]' ) ) . '">' . esc_html__( 'Before Widget', ShapeShifter_Extensions::TEXTDOMAIN ) . '</label></th>
	<td><textarea
		id="' . esc_attr( 'widget_area_' . $widget_areas_count . '_before_widget' ) . '" 
		name="' . esc_attr( sse()->get_prefixed_theme_option_name( 'widget_areas[' . $widget_areas_count . '][before_widget]' ) ) . '" 
		style="width: 100%;"
	>' . html_entity_decode( $saved_before_widget ) . '</textarea></td>
</tr>';

$row_after_widget = '<tr class="widget-area-after_widget" style="display: none;">
	<th scope="row"><label for="' . esc_attr( sse()->get_prefixed_theme_option_name( 'widget_areas[' . $widget_areas_count . '][after_widget]' ) ) . '">' . esc_html__( 'After Widget', ShapeShifter_Extensions::TEXTDOMAIN ) . '</label></th>
	<td><textarea 
		id="' . esc_attr( 'widget_area_' . $widget_areas_count . '_after_widget' ) . '" 
		name="' . esc_attr( sse()->get_prefixed_theme_option_name( 'widget_areas[' . $widget_areas_count . '][after_widget]' ) ) . '" 
		style="width: 100%;"
	>' . html_entity_decode( $saved_after_widget ) . '</textarea></td>
</tr>';

$row_before_title = '<tr class="widget-area-before_title" style="display: none;">
	<th scope="row"><label for="' . esc_attr( sse()->get_prefixed_theme_option_name( 'widget_areas[' . $widget_areas_count . '][before_title]' ) ) . '">' . esc_html__( 'Before Widget Title', ShapeShifter_Extensions::TEXTDOMAIN ) . '</label></th>
	<td><textarea 
		id="' . esc_attr( 'widget_area_' . $widget_areas_count . '_before_title' ) . '" 
		name="' . esc_attr( sse()->get_prefixed_theme_option_name( 'widget_areas[' . $widget_areas_count . '][before_title]' ) ) . '" 
		style="width: 100%;"
	>' . html_entity_decode( $saved_before_title ) . '</textarea></td>
</tr>';

$row_after_title = '<tr class="widget-area-after_title" style="display: none;">
	<th scope="row"><label for="' . esc_attr( sse()->get_prefixed_theme_option_name( 'widget_areas[' . $widget_areas_count . '][after_title]' ) ) . '">' . esc_html__( 'After Widget Title', ShapeShifter_Extensions::TEXTDOMAIN ) . '</label></th>
	<td><textarea 
		id="' . esc_attr( 'widget_area_' . $widget_areas_count . '_after_title' ) . '" 
		name="' . esc_attr( sse()->get_prefixed_theme_option_name( 'widget_areas[' . $widget_areas_count . '][after_title]' ) ) . '" 
		style="width: 100%;"
	>' . html_entity_decode( $saved_after_title ) . '</textarea></td>
</tr>';
*/
$table = '<table id="widget-areas-settings-' . $widget_areas_count . '" class="form-table" data-widget-area-count="' . $widget_areas_count . '">
	<tbody>
		<tr class="form-table-box">
			<th scope="row">
				<label class="widget-area-number-label">
					' . sprintf( esc_html__( 'Widget Area %d', ShapeShifter_Extensions::TEXTDOMAIN ), $widget_areas_count + 1 ) . '
				</label>
			</th>
			<td>
				<table>
					<tbody>

						' . $row_hook . '
						' . $row_width . '
						' . $row_is_on_mobile_menu . '
						' . $row_name . '
						' . $row_description . '

					</tbody>
				</table>
			</td>
		</tr>
	</tbody>
</table>';

echo $table;