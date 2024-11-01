// 一般的なウィジェット用のライブラリー
	window.shapeshifterGeneralMethods.getMediaFromLibrary = function( widgetType, inputHidden, imageBox, isMulti ) {
		( function( $ ) {
				
			var uploader = wp.media({
				title: '画像の選択',
				button: {
					text: '画像を決定'	
				},
				multiple: function( isMulti ) {
					if( isMulti ) { 
						//console.log( 'multi' );
						return true; 
					} else { 
						//console.log( 'solo' );
						return false; 
					}
				}
			}).on( 'select', function() {
				
				var selection = uploader.state().get( 'selection' );
				
				if( isMulti ) { // 複数の場合
					
					// 「url」の配列を取得
					var attachments = [];
					selection.map( function( attachment ){
						attachment = attachment.toJSON();
						attachments.push( attachment.url );
					} );
					
					// 「imageBox」内に各imgタグを出力
					$( imageBox ).children().remove();
					attachments.forEach( function( src, index ) {
						$( imageBox ).append( shapeshifterGeneralMethods.insertWidgetImageItem( widgetType, src ) );
					} );
					
					// データをStringで取得
					$( inputHidden ).val( attachments.join( ',' ) );
				
				} else { // 単数の場合
					
					var attachment = selection.first().toJSON();
					
					$( imageBox + ' img' ).attr( 'src', attachment.url );
					$( inputHidden ).val( attachment.url );
					
				}
			}).open();

		} ) ( jQuery );
	};

	window.shapeshifterGeneralMethods['removeImagesFromBox'] = function( inputHidden, imageBox, widgetNumber ) {
		( function( $ ) {
			$( inputHidden ).val( '' );
			$( imageBox ).empty();
			if( typeof widgetNumber != 'undefined' ) {
				window['slideGallaryJSON' + widgetNumber ].image_item_num = 0;
				//console.log( window['slideGallaryJSON' + widgetNumber ] );
			}

		} ) ( jQuery );
	};

	window.shapeshifterGeneralMethods['insertWidgetImageItem'] = function( widgetType, src ) {

		if( widgetType == 'textarea' ) {
			return '<img src="' + src + '" width="100" height="100" style="float:left;">';
		} else if( widgetType == 'download' ) {
			return '<img src="' + src + '" width="100" height="100" style="float:left;">';
		} else {
			return '<a href="javascript:void(0);"><img src="' + src + '" width="100" height="100" style="float:left;"></a>';
		} 

	};

var shapeshifterSlideGallaryMethods = {

	getImagesForGallary: function( instanceJSON, widgetNumber, widgetId, inputHidden, imageBox, isMulti ) {
		( function( $ ) {
				
			var uploader = wp.media({
				title: '画像の選択',
				button: {
					text: '画像を決定'	
				},
				multiple: isMulti,

			}).on( 'select', function() {
							
				var selection = uploader.state().get( 'selection' );

				//console.log( widgetNumber );

				if( isMulti ) { // 複数の場合
					
					// 「url」の配列を取得
					var attachments = [];
					selection.map( function( attachment ){
						attachment = attachment.toJSON();
						attachments.push( attachment.url );
					} );
					
					// 「imageBox」内に各imgタグを出力
					$( imageBox ).children().remove();
					attachments.forEach( function( src, index ) {
						$( imageBox ).append( shapeshifterSlideGallaryMethods.insertWidgetImageForm( instanceJSON, widgetNumber, widgetId, ( index + 1 ), src ) );
						$.ajax({
							type: "POST",
							url: ajaxurl,
							context: this,
							data: {
								"instance": instanceJSON,
								"widget_number": widgetNumber,
								"item_num": ( index + 1 ),
								"image_src": src,
								action: "get_settings_form_for_each_item"
							},
							error: function( jqHXR, textStatus, errorThrown ) {
							},
							success: function( data, textStatus, jqHXR ) {
								//console.log( data );
								//var temp = document.createElement( data );
								var settingsFormDiv = document.createElement( 'div' );

								settingsFormDiv.innerHTML = data;
								insertHTMLArray = $( settingsFormDiv ).children();
								//console.log( insertHTMLArray );

								widgetNumber = $( settingsFormDiv ).find( '.settings-form-container' ).data( 'widget-number' );
								itemNum = $( settingsFormDiv ).find( '.settings-form-container' ).data( 'item-num' );

								//console.log( widgetNumber );
								//console.log( itemNum );

								$( insertHTMLArray[ 0 ]['outerHTML'] ).insertAfter( '#' + widgetNumber + '-list-img-a-tag-wrapper-' + itemNum );
								
							},

						})
						.done( function() {

							$( '#' + widgetNumber + '-list-img-a-tag-wrapper-' + itemNum ).css({ 'display': 'block' });
							$( '.item-type-select-for-widget-slider' ).change( function() {
								settingsContainerForItem = $( this ).parent().parent();
								typeSettingsContainer = settingsContainerForItem.find( '.type-settings-form-container' );
								changedValue = $( this ).val();
								widgetNumber = $( this ).data( 'widget-number' );
								itemNumber = $( this ).data( 'item-num' );
								
								//console.log( changedValue );

								if( changedValue == 'none' ) {
									
									$( this ).parent().parent().find( '.type-settings-form-container .slide-item-settings-form' ).css({ 'display': 'none' });

								} else {

									$( this ).parent().parent().find( '.type-settings-form-container .slide-item-settings-form' ).css({ 'display': 'none' });
									$( this ).parent().parent().find( '.type-settings-form-container .slide-item-' + changedValue ).css({ 'display': 'block' });

								}

							});
							$( '.color-setting' ).wpColorPicker();
						});
					} );

					window['slideGallaryJSON' + widgetNumber ].image_item_num = attachments.length;
					
					// データをStringで取得
					$( inputHidden ).val( attachments.length );
					//$( inputHidden ).val( attachments.join( ',' ) );

					$( '#shapeshifter-popup-background,.close-settings-form-container' ).click( function() {
						shapeshifterSlideGallaryMethods.closeSettingsMenuFormWidget();
					});
				
				} else { // 単数の場合
					
					var attachment = selection.first().toJSON();

					//console.log( imageBox );
					
					$( imageBox ).attr( 'src', attachment.url );
					$( inputHidden ).val( attachment.url );
					
				}
			}).open();

		} ) ( jQuery );
	},

	appendImagesForGallary: function( instanceJSON, widgetNumber, widgetId, inputHidden, imageBox, isMulti ) {

		//console.log( widgetNumber );
		( function( $ ) {
				
			var uploader = wp.media({
				title: '画像の選択',
				button: {
					text: '画像を決定'	
				},
				multiple: isMulti,

			}).on( 'select', function() {
							
				var selection = uploader.state().get( 'selection' );

				if( isMulti ) { // 複数の場合
					
					// 「url」の配列を取得
					var attachments = [];
					selection.map( function( attachment ){
						attachment = attachment.toJSON();
						attachments.push( attachment.url );
					} );
					
					// 「imageBox」内に各imgタグを出力
					//$( imageBox ).children().remove();
					attachments.forEach( function( src, index ) {

						appendedItemNum = ( instanceJSON.image_item_num + index + 1 );

						//console.log( appendedItemNum );
						
						$( imageBox ).append( shapeshifterSlideGallaryMethods.insertWidgetImageForm( 
							instanceJSON, 
							widgetNumber, 
							widgetId, 
							( instanceJSON.image_item_num + index + 1 ), 
							src 
						) );

						$.ajax({
							type: "POST",
							url: ajaxurl,
							context: this,
							dataType: 'html',
							data: {
								"instance": instanceJSON,
								"widget_number": widgetNumber,
								"item_num": ( instanceJSON.image_item_num + index + 1 ),
								"image_src": src,
								action: "get_settings_form_for_each_item"
							},
							error: function( jqHXR, textStatus, errorThrown ) {

								//console.log( jqHXR );

							},
							success: function( data, textStatus, jqHXR ) {
								
								//console.log( data );
								//var temp = document.createElement( data );
								var settingsFormDiv = document.createElement( 'div' );

								settingsFormDiv.innerHTML = data;
								insertHTMLArray = $( settingsFormDiv ).children();
								//console.log( insertHTMLArray );

								widgetNumber = $( settingsFormDiv ).find( '.settings-form-container' ).data( 'widget-number' );
								itemNum = $( settingsFormDiv ).find( '.settings-form-container' ).data( 'item-num' );

								//console.log( widgetNumber );
								//console.log( itemNum );

								$( insertHTMLArray[ 0 ]['outerHTML'] ).insertAfter( '#' + widgetNumber + '-list-img-a-tag-wrapper-' + itemNum );
								
							},

						})
						.done( function() {

							$( '#' + widgetNumber + '-list-img-a-tag-wrapper-' + itemNum ).css({ 'display': 'block' });
							$( '.item-type-select-for-widget-slider' ).change( function() {
								settingsContainerForItem = $( this ).parent().parent();
								typeSettingsContainer = settingsContainerForItem.find( '.type-settings-form-container' );
								changedValue = $( this ).val();
								widgetNumber = $( this ).data( 'widget-number' );
								itemNumber = $( this ).data( 'item-num' );
								
								//console.log( changedValue );

								if( changedValue == 'none' ) {
									
									$( this ).parent().parent().find( '.type-settings-form-container .slide-item-settings-form' ).css({ 'display': 'none' });

								} else {

									$( this ).parent().parent().find( '.type-settings-form-container .slide-item-settings-form' ).css({ 'display': 'none' });
									$( this ).parent().parent().find( '.type-settings-form-container .slide-item-' + changedValue ).css({ 'display': 'block' });

								}

							});
							$( '.color-setting' ).wpColorPicker();
						});
					} );

					
					window['slideGallaryJSON' + widgetNumber ].image_item_num = instanceJSON.image_item_num + attachments.length;
					//console.log( window['slideGallaryJSON' + widgetNumber ] );

					// データをStringで取得
					$( inputHidden ).val( window['slideGallaryJSON' + widgetNumber ].image_item_num );
					//$( inputHidden ).val( attachments.join( ',' ) );

					$( '#shapeshifter-popup-background,.close-settings-form-container' ).click( function() {
						shapeshifterSlideGallaryMethods.closeSettingsMenuFormWidget();
					});
				
				}
			}).open();

		} ) ( jQuery );
	},

	insertWidgetImageForm: function( instanceJSON, widgetNumber, widgetId, indexPlusOne, src ) {

		nameBase = 'widget-shapeshifterslidegallery[' + widgetNumber + ']';
		idBase = 'widget-shapeshifterslidegallery-' + widgetNumber + '-';

		//if( widgetType == 'slider' ) {
			returnVar = '<a id="' + widgetNumber + '-list-img-a-tag-wrapper-' + indexPlusOne + '" class="settings-form-popup" href="javascript:void( 0 );" onclick=\'shapeshifterSlideGallaryMethods.popupSettingsMenuFormWidget( window[ "slideGallaryJSON' + widgetNumber + '" ], "' + widgetId + '-settings-form-container-' + indexPlusOne + '", "' + widgetNumber + '-list-img-a-tag-wrapper-' + indexPlusOne + '" );\' data-widget-number="' + widgetNumber + '" data-item-num="' + indexPlusOne + '" style="display:none;">\
				<img id="' + widgetNumber + '-list-img-url-' + indexPlusOne + '" class="images-from-library" src="' + src + '" width="100" height="100" style="float:left;">\
			</a>\
			';
			return returnVar;
		//} 

	},

	popupSettingsMenuFormWidget: function( instanceJSON, settingsFormId, contextId ) {

		//console.log( contextId );
		//console.log( instanceJSON );
		//console.log( settingsFormId );

		if( typeof instanceJSON == 'string' ) instanceJSON = JSON.parse( instanceJSON );

		backgroundId = 'shapeshifter-widget-popup-background';

		( function( $ ) {

			shapeshifterGeneralMethods.insertPopupBackground( 
				$( '#' + contextId ), 
				backgroundId, 
				backgroundId, 
				9997 
			);

			shapeshifterGeneralMethods.popupNextSettingsBox( 
				'#' + settingsFormId, 
				'#' + backgroundId 
			);

		} ) ( jQuery );

	},

	closeSettingsMenuFormWidget: function() {
		( function( $ ) {
			$( '#shapeshifter-popup-background' ).css({ 'display': 'none' });
			$( '.settings-form-container' ).css({ 'display': 'none' });
			$( 'body' ).css({ 'overflow': 'initial' });
		} ) ( jQuery );
	},

	removeImageSource: function( inputHidden, imgTagSelector ) {
		( function( $ ) {
			$( inputHidden ).val( '' );
			$( imgTagSelector ).attr( 'src', '' );
		} ) ( jQuery );
	},

	addTextarea: function( name, widgetClassName, widgetNumber, itemNumber, textareaNumber, textareaNumberInput, context ) {
		( function( $ ) {

			//console.log( textareaNumber );

			$( '<p id="' + widgetNumber + '-textarea-wrapper-' + itemNumber + '-' + textareaNumber + '"></p>\
				<p id="' + widgetNumber + '-textarea-position-wrapper-' + itemNumber + '-' + textareaNumber + '"></p>\
				<p id="' + widgetNumber + '-textarea-text-color-wrapper-' + itemNumber + '-' + textareaNumber + '"></p>\
				<p id="' + widgetNumber + '-textarea-background-color-wrapper-' + itemNumber + '-' + textareaNumber + '"></p>' ).insertBefore( context )

			widgetId = widgetClassName + '-' + widgetNumber;

			nameBase = 'widget-' + widgetClassName + '[' + widgetNumber + ']';
			idBase = 'widget-' + widgetId + '-';

			inputId = idBase + 'slide_gallery_text_' + itemNumber + '_' + textareaNumber;
			inputName = nameBase + '[slide_gallery_text_' + itemNumber + '_' + textareaNumber + ']';

			$.ajax({
				type: "POST",
				url: ajaxurl,
				context: context,
				data: {
					"widget_number": widgetNumber,
					"item_num": itemNumber,
					"textarea_number": textareaNumber,
					"id_base": idBase,
					"name_base": nameBase,
					action: "get_settings_form_for_textarea"
				},
				error: function( jqHXR, textStatus, errorThrown ) {
				},
				success: function( data, textStatus, jqHXR ) {

					var textareaHTML = document.createElement( 'p' );

					textareaHTML.innerHTML = data;

					//console.log( textareaHTML.innerHTML );

					//$( textareaHTML.innerHTML ).appendTo( '#' + widgetNumber + '-textarea-wrapper-' + itemNumber + '-' + textareaNumber );

					$( textareaHTML.innerHTML ).insertBefore( context )
					
				},

			})
			.done( function() {

				$( textareaNumberInput ).val( parseInt( textareaNumber, 10 ) );

				$( context ).attr( 
					'href', 
					"javascript:shapeshifterSlideGallaryMethods.addTextarea( \'slide_gallery_text_" + itemNumber + "_" + ( textareaNumber + 1 ) + "\', \'" + widgetClassName + "\', \'" + widgetNumber + "\', \'" + itemNumber + "\', \'" + ( textareaNumber + 1 ) + "\', \'" + textareaNumberInput + "\', \'" + context + "\' );"
				);

				$( '#' + idBase + 'slide_gallery_text_color_' + itemNumber + '_' + textareaNumber ).alphaColorPicker();
				$( '#' + idBase + 'slide_gallery_text_background_color_' + itemNumber + '_' + textareaNumber ).alphaColorPicker();

			} );

		} ) ( jQuery );
	},




	// Insert Popup Background
	insertPopupBackground: function( context, backgroundId, backgroundClass, zIndex ) {

		if( ! jQuery( '#' + backgroundId ).exists() ) {

			html = shapeshifterGeneralMethods.getGeneratedPopupBackground( 
				backgroundId, 
				backgroundClass, 
				zIndex 
			);

			jQuery( html ).insertAfter( jQuery( context ) );

		}

	},

	// Get Background Element
	getGeneratedPopupBackground: function( elementId, elementClass, zIndex ) {

		return '<div id="' + elementId + '" class="shapeshifter-generated-popup-background ' + elementClass + '" style="z-index: ' + parseInt( zIndex ) + ';"></div>';

	},

	// Popup
	popupNextSettingsBox: function( popupBoxId, popupBackgroundId ) {

		jQuery( popupBackgroundId ).css({ 'display': 'block' });
		jQuery( popupBoxId ).css({ 'display': 'block' });
		jQuery( 'body' ).css({ 'overflow': 'hidden' });
		jQuery( '.widget.open' ).css({ 'position': 'static' });

		jQuery( popupBackgroundId ).click( function( e ) {

			shapeshifterGeneralMethods.closeSettingsBox( popupBoxId, popupBackgroundId );

		});

	},

	// Close
	closeSettingsBox: function( popupBoxId, popupBackgroundId ) {
		jQuery( popupBoxId ).css({ 'display': 'none' });
		jQuery( popupBackgroundId ).remove();
		//jQuery( popupBackgroundId ).css({ 'display': 'none' });
		//	jQuery( popupBoxId ).css({ 'display': 'none' });
		jQuery( 'body' ).css({ 'overflow': 'initial' });
		jQuery( '.widget.open' ).css({ 'position': 'relative' });
	},

};

( function( $ ) {

	if( window.location.href.indexOf( sseDirURLForJS.adminCustomizerURL ) >= 0 )
		return;

	$( document ).on( 'ready widget-updated widget-added', function() {

		$( document ).trigger( 'widget-after' );

		// ポップアップメニューを閉じる
		/*$( '#shapeshifter-popup-background' ).click( function() {
			shapeshifterSlideGallaryMethods.closeSettingsMenuFormWidget();
		});*/

		// カラーピッカー
		colorSettings = $( '.color-setting' );
		for( i = 0; i < colorSettings.length; i++ ) {
			//if( ! $( '#' + colorSettings[ i ].id ).hasClass( 'wp-color-picker' ) ) {
				$( '#' + colorSettings[ i ].id ).alphaColorPicker();
			//}
		}

		// アイテムタイプ
		$( '.item-type-select-for-widget-slider' ).change( function() {

			settingsContainerForItem = $( this ).parent().parent();
			typeSettingsContainer = settingsContainerForItem.find( '.type-settings-form-container' );
			changedValue = $( this ).val();
			widgetNumber = $( this ).data( 'widget-number' );
			itemNumber = $( this ).data( 'item-num' );

			if( changedValue == 'none' ) {
				
				$( this ).parent().parent().find( '.type-settings-form-container .slide-item-settings-form' ).css({ 'display': 'none' });

			} else {

				$( this ).parent().parent().find( '.type-settings-form-container .slide-item-settings-form' ).css({ 'display': 'none' });
				$( this ).parent().parent().find( '.type-settings-form-container .slide-item-' + changedValue ).css({ 'display': 'block' });

			}

		});

	});
} ) ( jQuery );

