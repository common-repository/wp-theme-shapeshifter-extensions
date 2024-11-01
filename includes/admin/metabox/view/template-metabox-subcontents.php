<?php
// Max Item Num
if ( isset( $this->postmeta['sub_contents'] ) 
	&& is_array( $this->postmeta['sub_contents'] )
) {
	$items_number_for_the_content = count( $this->postmeta['sub_contents'] );
}
$max_item_num = absint( 
	! empty( $items_number_for_the_content )
	? $items_number_for_the_content
	: 0
);

// Explanation
echo '<p><small>' . esc_html__( 'Outputs Settings here are Applied as higher priorities to Deactivation Settings Above.', ShapeShifter_Extensions::TEXTDOMAIN ) . '</small></p>';

	echo '<ol>';
		echo '<li>' . esc_html__( 'Append Items as much as you want to print in widget areas', ShapeShifter_Extensions::TEXTDOMAIN ) . '</li>';
		echo '<li>' . esc_html__( 'Select "Type" and "Hook"', ShapeShifter_Extensions::TEXTDOMAIN ) . '</li>';
		echo '<li>' . esc_html__( 'Click the Edit Button.', ShapeShifter_Extensions::TEXTDOMAIN ) . '</li>';
		echo '<li>' . esc_html__( 'To Remove Items, Select items by checkbox and click "Remove" button, then, Save.', ShapeShifter_Extensions::TEXTDOMAIN ) . '</li>';
	echo '</ol>';

// Item Num
	echo '<input type="hidden" 
		id="item-number-of-the-sub-contents"
		class="items-num-hidden" 
		name="' . esc_attr( sse()->get_prefixed_post_meta_name( 'items_number_for_the_content' ) ) . '" 
		value="' . $max_item_num . '" 
	>';

// JSON
	$sub_contents_json_data_string = json_encode( $this->postmeta['sub_contents'], JSON_UNESCAPED_UNICODE );
	echo '<input type="hidden" 
		id="shapeshifter-meta-box-optional-output-json"
		class="items-num-hidden" 
		name="' . esc_attr( sse()->get_prefixed_post_meta_name( 'sub_contents_json' ) ) . '" 
		value="' . esc_attr( $sub_contents_json_data_string ) . '" 
	>';

echo '<div class="inside"><div class="main">';

	echo '<!-- ファイルの適用 -->';
	echo '<table cellspacing="0" id="meta-boxes-prints-settings" class="wp-list-table widefat fixed subscribers">';

		echo '<thead>';
			echo '<tr>';
				echo '<th style="" class="manage-column column-cb check-column" id="cb" scope="col">';
					echo '<input type="checkbox">';
				echo '</th>';
				echo '<!--span class="sorting-indicator">css-family</span-->';
				echo '<th style="" class="manage-column column-item-title" scope="col">';
					echo '<span>' . esc_html__( 'Item Title', ShapeShifter_Extensions::TEXTDOMAIN ) . '</span>';
				echo '</th>';
				echo '<th style="" class="manage-column column-item-type" scope="col">';
					echo '<span>' . esc_html__( 'Type', ShapeShifter_Extensions::TEXTDOMAIN ) . '</span>';
				echo '</th>';
				echo '<th style="" class="manage-column column-item-hook" scope="col">';
					echo '<span>' . esc_html__( 'Hook', ShapeShifter_Extensions::TEXTDOMAIN ) . '</span>';
				echo '</th>';
				echo '<th style="" class="manage-column column-edit-button" scope="col">';
					echo '<span>' . esc_html__( 'Edit', ShapeShifter_Extensions::TEXTDOMAIN ) . '</span>';
				echo '</th>';
			echo '</tr>';
		echo '</thead>';

		echo '<tfoot>';
			echo '<tr>';
				echo '<th style="" class="manage-column column-cb check-column" scope="col">';
					echo '<input type="checkbox">';
				echo '</th>';
				echo '<th style="" class="manage-column column-item-title" scope="col">';
					echo '<span>' . esc_html__( 'Item Title', ShapeShifter_Extensions::TEXTDOMAIN ) . '</span>';
				echo '</th>';
				echo '<th style="" class="manage-column column-item-type" scope="col">';
					echo '<span>' . esc_html__( 'Type', ShapeShifter_Extensions::TEXTDOMAIN ) . '</span>';
				echo '</th>';
				echo '<th style="" class="manage-column column-item-hook" scope="col">';
					echo '<span>' . esc_html__( 'Hook', ShapeShifter_Extensions::TEXTDOMAIN ) . '</span>';
				echo '</th>';
				echo '<th style="" class="manage-column column-edit-button" scope="col">';
					echo '<span>' . esc_html__( 'Edit', ShapeShifter_Extensions::TEXTDOMAIN ) . '</span>';
				echo '</th>';
			echo '</tr>';
		echo '</tfoot>';

		echo '<tbody>';
			if ( is_array( $this->postmeta['sub_contents'] ) 
				&& 0 <= count( $this->postmeta['sub_contents'] ) 
			) {
			foreach ( $this->postmeta['sub_contents'] as $item_num => $item_data ) {
				$item_data = (
					isset( $this->postmeta['sub_contents'][ $item_num ] )
					? $this->postmeta['sub_contents'][ $item_num ]
					: array()
				);
				$this->print_table_row( 
					absint( $post->ID ), 
					absint( $item_num ), 
					$item_data
				);
			}
			}
		echo '</tbody>';

	echo '</table>';
	echo '<br>';

	echo '<a id="append-an-item-to-print-in-widget-area-hook" class="button-primary" href="javascript:void(0);">' . esc_html__( 'Append An Item', ShapeShifter_Extensions::TEXTDOMAIN ) . '</a>';

	echo '&nbsp;';

	echo '<a id="remove-selected-items-to-print-in-widget-area-hook" class="button-primary" href="javascript:void(0);">' . esc_html__( 'Remove Selected Items', ShapeShifter_Extensions::TEXTDOMAIN ) . '</a>';

echo '</div></div>';

echo '<div 
	id="meta-box-popup-background"
	class="meta-box-popup-background"
></div>';

