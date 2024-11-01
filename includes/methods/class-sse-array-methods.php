<?php
class SSE_Array_Methods {

	/**
	 * Reset Settings Array
	 * @param  [type] $json_assoc_array [description]
	 * @return [type]                   [description]
	**/
	public static function get_json_settings_assoc_array( $json_assoc_array )
	{

		# Init Array
			$returned_assoc_array = array();

		# Each Row Item
		if( is_array( $json_assoc_array ) ) { foreach( $json_assoc_array as $row_item => $settings ) {

			# Init Array
				$returned_assoc_array[ $row_item ] = array();

			# Each Setting of the Row
			if( is_array( $settings ) ) { foreach( $settings as $index => $value ) {

				# Search for Name Indexes of Inputs
					$is_matched = preg_match_all(
						'/\[([^\]]+?)\]/',
						$index,
						$matched
					);

				//echo count( $matched[ 0 ] ) . PHP_EOL;

				if( $is_matched ) { # If Has Index

					$first_index = str_replace(
						$matched[ 0 ],
						'',
						$index 
					);
					$matched[ 1 ] = array_reverse( $matched[ 1 ] );
					//print_r( $matched );

					$value = array(
						$first_index => SSE_Array_Methods::get_arranged_input_index_array( $matched, count( $matched[ 0 ] ), $value )
					);
					//print_r( $value );
					# When "row_item( Number )" already set
						if( isset( $returned_assoc_array[ $row_item ][ $first_index ][ $row_item ] ) ) {
							$returned_assoc_array[ $row_item ][ $first_index ][ $row_item ] = $returned_assoc_array[ $row_item ][ $first_index ][ $row_item ] + $value[ $first_index ][ $row_item ];
						}
					# When "$first_index" already set
						if( isset( $returned_assoc_array[ $row_item ][ $first_index ] ) ) {
							$returned_assoc_array[ $row_item ][ $first_index ] = $returned_assoc_array[ $row_item ][ $first_index ] + $value[ $first_index ];
						}
					# When "$first_index" not set
						if( ! isset( $returned_assoc_array[ $row_item ][ $first_index ] ) ) {
							$returned_assoc_array[ $row_item ][ $first_index ] = array();
							$returned_assoc_array[ $row_item ][ $first_index ] = $returned_assoc_array[ $row_item ][ $first_index ] + $value[ $first_index ];
						}
					//$returned_assoc_array[ $row_item ][ $first_index ] = array_merge_recursive( $returned_assoc_array[ $row_item ][ $first_index ], $value[ $first_index ] );
				} else { # No Index
					$first_index = $index;
					$value = array( $first_index => $value );
					$returned_assoc_array[ $row_item ] = array_merge_recursive( $returned_assoc_array[ $row_item ], $value );

				}

				//print_r( $value );

				//$returned_assoc_array[ $row_item ][ $first_index ] = array_merge_recursive( $returned_assoc_array[ $row_item ][ $first_index ], $value[ $first_index ] );

			} }

		} }

		return $returned_assoc_array;

	}

	/**
	 * 
	**/
	public static function get_arranged_input_index_array( $matched, $matched_count, $value )
	{

		if( $matched_count > 0 ) {

			$matched_count = $matched_count - 1;

			//echo $matched[][ $matched_count ] . PHP_EOL;

			return array(
				( string ) $matched[ 1 ][ $matched_count ] => SSE_Array_Methods::get_arranged_input_index_array( $matched, $matched_count, $value )
			);

		} else {
			return $value;
		}

	}

	/**
	 * parse inputs nmaes and vals
	 * @param assocArr $input_names: Should be assoc with val
	 * @param array    $holder
	**/
	public static function parse_input_names_vals( $arr, $holder = array() )
	{
		if ( ! is_array( $arr ) || 0 >= count( $arr ) ) {
			return $holder;
		}

		foreach ( $arr as $index => $val ) {

			if ( preg_match_all( '/\[([^\]]+)\]/i', $index, $matched ) ) {
//				var_dump( $matched );
				$elements = $matched[1];
				$holder = SSE_Array_Methods::set_holder_names_vals( $holder, $elements, $val );
			}

		}

		return $holder;

	}

	public static function set_holder_names_vals( $holder, $matched, $val )
	{

		$current_key = array_shift( $matched );
		if ( 0 === count( $matched ) ) {
			$holder[ $current_key ] = $val;
			return $holder;
		}

		if ( ! isset( $holder[ $current_key ] ) ) {
			$holder[ $current_key ] = array();
		}
		$holder[ $current_key ] = SSE_Array_Methods::set_holder_names_vals( $holder[ $current_key ], $matched, $val );

		return $holder;

	}


}


