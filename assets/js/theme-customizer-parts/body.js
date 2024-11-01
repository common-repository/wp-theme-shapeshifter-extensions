( function( $ ) {
	
	console.log( 'body.js' );

	var bodyBackgroundPreview = function( data, index ) {

		// 背景色
			wp.customize( 'body_' + data.id + '_background_color', function( value ) {
				value.bind( function( newval ) {
					window.sseThemeMods['body_' + data.id + '_background_color'] = newval;
					if( newval ) {
						//$( data.selectors.bodyBackgroundColor ).css({
						//	'background-color': newval
						//});
						$( '#shapeshifter-theme-customize-preview' ).append( data.selectors.bodyBackgroundColor + '{background-color:'+ newval +';}' );
					} else {
						//$( data.selectors.bodyBackgroundColor ).css({
						//	'background-color': 'transparent'
						//});
						$( '#shapeshifter-theme-customize-preview' ).append( data.selectors.bodyBackgroundColor + '{background-color: initial;}' );
					}
				});
			});
		// 背景イメージ
			wp.customize( 'body_' + data.id + '_background_image', function( value ) {
				value.bind( function( newval ) {
					window.sseThemeMods['body_' + data.id + '_background_image'] = newval;
					if( newval ) {
						///$( data.selectors.bodyBackgroundImage ).css({
						//	'background-image': 'url( ' + newval + ' )'
						//});
						$( '#shapeshifter-theme-customize-preview' ).append( data.selectors.bodyBackgroundImage + '{ background-image:url( '+ newval +' );}' );
					} else {
						//$( data.selectors.bodyBackgroundImage ).css({
						//	'background-image': 'none'
						//});
						$( '#shapeshifter-theme-customize-preview' ).append( data.selectors.bodyBackgroundImage + '{ background-image: url( ' + window.sseThemeMods['background_image'] + ' );}' );
						//$( '#shapeshifter-theme-customize-preview' ).append( data.selectors.bodyBackgroundImage + '{ background-image: url( ' + window.sseThemeMods['background_image'] + ' );}' );
					}
				});
			});
		// 背景イメージサイズ
			wp.customize( 'body_' + data.id + '_background_image_size', function( value ) {
				value.bind( function( newval ) {
					window.sseThemeMods['body_' + data.id + '_background_image_size'] = newval;
					if ( 'undefined' !== typeof( window.sseThemeMods['body_' + data.id + '_background_image_size'] )
						&& window.sseThemeMods['body_' + data.id + '_background_image_size'] != ''
					) {
						$( '#shapeshifter-theme-customize-preview' )
						.append( data.selectors.bodyBackgroundImageSize + '{ background-size: '+ newval + ';} ' );
					} else {
						if ( 'undefined' !== window.sseThemeMods['background_size'] ) {
							$( '#shapeshifter-theme-customize-preview' )
							.append( data.selectors.bodyBackgroundImageSize + '{ background-size: '+ window.sseThemeMods['background_size'] + ';} ' );
						} else {
							$( '#shapeshifter-theme-customize-preview' )
							.append( data.selectors.bodyBackgroundImageSize + '{ background-size: auto;} ' );
						}
					}
				});
			});
		// 背景イメージ位置　縦
		wp.customize( 'body_' + data.id + '_background_image_position_row', function( value ) {
			value.bind( function( newval ) {
				window.sseThemeMods['body_' + data.id + '_background_image_position_row'] = newval;
				//$( data.selectors.bodyBackgroundImagePositionRow ).css({
				//	'background-position-y': newval
				//});
				$( '#shapeshifter-theme-customize-preview' ).append( data.selectors.bodyBackgroundImagePositionRow + '{ background-position-y: '+ newval + ';}' );
			});
		});
		// 背景イメージ位置　横
		wp.customize( 'body_' + data.id + '_background_image_position_column', function( value ) {
			value.bind( function( newval ) {
				window.sseThemeMods['body_' + data.id + '_background_image_position_column'] = newval;
				//$( data.selectors.bodyBackgroundImagePositionColumn ).css({
				//	'background-position-x': newval
				//});
				$( '#shapeshifter-theme-customize-preview' ).append( data.selectors.bodyBackgroundImagePositionColumn + '{ background-position-x: '+ newval + ';}' );
			});
		});
		// 背景イメージ 繰り返し
		wp.customize( 'body_' + data.id + '_background_image_repeat', function( value ) {
			value.bind( function( newval ) {
				window.sseThemeMods['body_' + data.id + '_background_image_repeat'] = newval;
				//$( data.selectors.bodyBackgroundImageRepeat ).css({
				//	'background-repeat': newval
				//});
				$( '#shapeshifter-theme-customize-preview' ).append( data.selectors.bodyBackgroundImageRepeat + '{ background-repeat: '+ newval + ';}' );
			});
		});
		// 背景イメージ 固定
		wp.customize( 'body_' + data.id + '_background_image_attachment', function( value ) {
			value.bind( function( newval ) {
				window.sseThemeMods['body_' + data.id + '_background_image_attachment'] = newval;
				//$( data.selectors.bodyBackgroundImageAttachment ).css({
				//	'background-attachment': newval
				//});
				$( '#shapeshifter-theme-customize-preview' ).append( data.selectors.bodyBackgroundImageAttachment + '{ background-attachment: '+ newval + ';}' );
			});
		});

	};

	//console.log( ssePageTypes );
	var bodyTypes = [];
	var bodyTypesArgs = [];
	for( var type in ssePageTypes ) {
		bodyTypes.push({
			"id": type,
			"name": ssePageTypes[ type ]['name'],
			"selectorClass": ssePageTypes[ type ]['class']
		});
		bodyTypesArgs.push({
			"id": type,
			"selectors": {
				"bodyBackgroundColor": 'body' + ssePageTypes[ type ]['class'],
				"bodyBackgroundImage": 'body' + ssePageTypes[ type ]['class'],
				"bodyBackgroundImageSize": 'body' + ssePageTypes[ type ]['class'],
				"bodyBackgroundImagePositionRow": 'body' + ssePageTypes[ type ]['class'],
				"bodyBackgroundImagePositionColumn": 'body' + ssePageTypes[ type ]['class'],
				"bodyBackgroundImageRepeat": 'body' + ssePageTypes[ type ]['class'],
				"bodyBackgroundImageAttachment": 'body' + ssePageTypes[ type ]['class']
			}
		});
	}
	
	for( var index in bodyTypesArgs ) {
		bodyBackgroundPreview( bodyTypesArgs[ index ], index );
	}
	//console.log( bodyTypes );

}) ( jQuery );