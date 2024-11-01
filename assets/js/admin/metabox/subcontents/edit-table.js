( function( $ ) {

	if( window.location.href.indexOf( sseDirURLForJS.adminEditURL ) !== -1 )
	    return;

	// Alpha Color Picker
	$( 'input.alpha-color-picker' ).alphaColorPicker();


	$( document ).ready( function() {

		// ウィジェットエリアに出力 PMSC: PostMeta SubContent
		var shapeshifterPMSC = {

			inputJSONSelector: "input#shapeshifter-meta-box-optional-output-json",

			init: function() {

				shapeshifterPMSC.resetupJSONData();

				// 投稿IDを取得
				shapeshifterPMSC.postId = $( '#post_ID' ).val();
				// アイテムの最大数
				shapeshifterPMSC.itemNumMax = parseInt( $( '#item-number-of-the-sub-contents' ).val() );

				// メニューのポップアップ
					$( '.edit-meta-box-item' ).click( function( e ) {

						e.preventDefault();

						shapeshifterPMSC.popupMenuBox( this, parseInt( $( this ).data( 'item-num' ) ) );

					});

				// メニューをクローズ
					/*$( '.close-post-meta-popup-menu, #meta-box-popup-background' ).click( function( e ) {

						e.preventDefault();

						shapeshifterPMSC.closeMenuBox( this, parseInt( $( this ).data( 'item-num' ) ) );

					});*/

				// アイテムタイプの変更
					$( '.sub-content-item-type' ).change( function( e ) {

						shapeshifterPMSC.selectItemType( parseInt( $( this ).data( 'item-num' ) ), $( this ).val() );

					});

				// アイテムを追加
					$( '#append-an-item-to-print-in-widget-area-hook' ).click( function( e ) {

						//e.preventDefault();

						shapeshifterPMSC.appendItemRow();

					});

				// イメージをセット
					$( '.post-meta-set-images' ).click( function( e ) {

						e.preventDefault();

						shapeshifterPMSC.setImages( $( this ).attr( 'data-input-hidden' ), $( this ).attr( 'data-image-box' ) );

					}); 

				// イメージを削除
					$( '.post-meta-remove-images' ).click( function( e ) {

						e.preventDefault();

						shapeshifterPMSC.removeImages( $( this ).attr( 'data-input-hidden' ), $( this ).attr( 'data-image-box' ) );

					}); 

				// アイテムを削除
					$( '#remove-selected-items-to-print-in-widget-area-hook' ).click( function( e ) {

						e.preventDefault();

						shapeshifterPMSC.removeItems();

					});

				// ウィジェットエリアの変更
					$( '.post-meta-widget-area-select' ).change( function( e ) {
						
						shapeshifterPMSC.changeValueOfWidgetArea( parseInt( $( this ).data( 'item-num' ) ), $( this ).val() );

					});

				// 登録されたウィジェットの変更
					$( '.post-meta-new-registered-widget-select' ).change( function( e ) {
						
						shapeshifterPMSC.changeValueOfWidgetSelect( parseInt( $( this ).data( 'item-num' ) ), $( this ).val() );

					});

			},

			resetupJSONData: function() {

				shapeshifterPMSC.addOptionalOutputClass();

				$( '.shapeshifter-meta-box-optional-output' ).on( 'change', function() {

					shapeshifterPMSC.addOptionalOutputClass();
					shapeshifterPMSC.setupJSONData();

				});

				$( '#post-preview, .post-preview' ).on( 'click', function( e ) {
					shapeshifterPMSC.addOptionalOutputClass();
					shapeshifterPMSC.setupJSONData( e, this );
					//window.wp.autosave();
				});

				$( '#publish, #save-post, .editor-post-publish-button' ).on( 'click', function( e ) {
					data = shapeshifterGeneralMethods.parseTargetInputs( '_sse_sub_contents_json' );
					jsonStr = JSON.stringify( data );
					shapeshifterPMSC.addOptionalOutputClass();
					shapeshifterPMSC.setupJSONData( data, this );
					shapeshifterPMSC.saveDataByAJAX();
					shapeshifterPMSC.disableInputs();

					//data = shapeshifterGeneralMethods.parseTargetInputs( '_sse_sub_contents_json' );
					//jsonStr = JSON.stringify( data );

					//$( '#shapeshifter-meta-box-optional-output-json' ).val( jsonStr );
					//var json = JSON.parse( $( '#shapeshifter-meta-box-optional-output-json' ).val() );

				});

			},

				setupJSONData: function( data, context ) {

					var jsonData = {};

					var data = shapeshifterGeneralMethods.parseTargetInputs( '_sse_sub_contents_json' );
					$( 'table#meta-boxes-prints-settings tbody > tr' ).each( function( index ) {

						var itemNum = $( this ).data( 'item-num' );
						var savedItemNum = index;

						sub_content_item_type = data[ savedItemNum ]["sub_content_item_type"];

						// Settings For Item Type
							itemWrapperSelector = ".post-meta-item-" + sub_content_item_type;
							if( "new_registered_widget" === sub_content_item_type ) {

								var receivedInputData = shapeshifterPMSC.changeAndGetAttsForCurrentRow( $( this ).find( '.post-meta-new-registered-widget-select' ), savedItemNum );

								if( receivedInputData === false ) return;

								var receivedInputDataName = receivedInputData['name'];
								var receivedInputDataValue = receivedInputData['value'];

								var regex = /\[([^\]]+)\]/g;
								var namesWithBrackets = receivedInputDataName.match( regex );
								modInputName = namesWithBrackets[1].replace( /[\[\]]/g, '' );


								data[ savedItemNum ][ modInputName ] = receivedInputDataValue;

								var widgetType = data[ savedItemNum ][ modInputName ];

								targetWidgetClass = 'widget-' + widgetType + '[' + savedItemNum + ']';
								targetWidgetData = shapeshifterGeneralMethods.parseTargetInputs( targetWidgetClass );

								data[ savedItemNum ]['sub_content_new_registered_widget_instance'] = targetWidgetData[ savedItemNum ];

							}

					});

					$( shapeshifterPMSC.inputJSONSelector ).val( JSON.stringify( data ) );

					return;


				},

				changeAndGetAttsForCurrentRow: function( $context, savedItemNum ) {

					var name = $context.attr( 'name' );
					var id = $context.attr( 'id' );
					var itemNum = $context.data( 'item-num' );

					if( typeof name === "undefined" ) return false;

					// Name
						var registeredName = name.replace( /\_[0-9]+/i, '_' + savedItemNum );
						registeredName = registeredName.replace( /\[[0-9]+\]/i, '[' + savedItemNum + ']' );
						$context.attr( 'name', registeredName );
						//console.log( registeredName );

					// ID
						if( typeof id !== 'undefined' ) {
							var registeredId = id.replace( /\_[0-9]+/i, '_' + savedItemNum );
							registeredId = registeredId.replace( /\[[0-9]+\]/i, '[' + savedItemNum + ']' );
							$context.attr( 'id', registeredId );
							//console.log( registeredId );
						}

					// ItemNum
						if( typeof itemNum !== 'undefined' ) {
							var registeredItemNum = savedItemNum;
							$context.attr( 'data-item-num', registeredItemNum );
							//console.log( registeredItemNum );
						}

					return {
						"name": registeredName,
						"value": shapeshifterPMSC.getSettingValue( $context, name )
					};

				},

				getSettingValue: function( $context, name ) {

					if( $context.attr( "type" ) === "checkbox" 
						|| $context.attr( "type" ) === "radio" 
					) {
						
						if( $context.prop( "checked" ) ) {
							value = $context.val();
						} else {
							value = "";
						}
						
					} else {

						value = $context.val();

					}

					return value;

				},

				disableInputs: function() {
					$( '.shapeshifter-meta-box-optional-output' ).prop( "disabled", true );
				},

				enableInputs: function() {
					$( '.shapeshifter-meta-box-optional-output' ).prop( "disabled", false );					
				},

				saveDataByAJAX: function() {

					jsonTypeData = JSON.parse( $( shapeshifterPMSC.inputJSONSelector ).val() );
					//console.log( jsonTypeData );

					$.ajax({
						type: "POST",
						url: ajaxurl,
						context: this,
						dataType: "html",
						data: {
							"post_id": shapeshifterPMSC.postId,
							"sub_contents_json": jsonTypeData,
							"output_in_widget_area_meta_box_nonce": $( '#_sse_output_in_widget_area_meta_box_nonce' ).val(),
							action: "save_to_output_widget_areas"
						},
						success: function( data ) {
							shapeshifterPMSC.enableInputs();
						}
					});

				},

			addOptionalOutputClass: function() {

				$( 'table#meta-boxes-prints-settings input, table#meta-boxes-prints-settings textarea, table#meta-boxes-prints-settings select, table#meta-boxes-prints-settings radio' ).addClass( 'shapeshifter-meta-box-optional-output' );

			},

			popupMenuBox: function( context, itemNum ) {

				//console.log( 'popup' );

				var popupBGPrefix = 'shapeshifter-general-popup-background';

				shapeshifterGeneralMethods.insertPopupBackground( 
						context,
						popupBGPrefix, 
//						popupBGPrefix + '-' + itemNum, 
						popupBGPrefix, 
						9992 
					);

				shapeshifterGeneralMethods.popupNextSettingsBox( 
					'#meta-box-popup-menu-' + itemNum, 
					'#' + popupBGPrefix 
//					'#' + popupBGPrefix + '-' + itemNum 
				);

				// Close
					$( '.close-post-meta-popup-menu' ).click( function( e ) {

						e.preventDefault();

						shapeshifterGeneralMethods.closeSettingsBox(
							'#meta-box-popup-menu-' + itemNum,
							'#' + popupBGPrefix
						);

					});

			},

			closeMenuBox: function( context, itemNum ) {

				//console.log( 'shapeshifterPMSC closeMenuBox' );

				shapeshifterGeneralMethods.popupNextSettingsBox( 
					'#meta-box-popup-menu-' + itemNum, 
					'#meta-box-popup-background' 
				);

				$( '#' + popupBGPrefix ).css({
//				$( '#' + popupBGPrefix + '-' + itemNum ).css({
					'display': 'none'
				});
				$( '.meta-box-popup-menu' ).css({
					'display': 'none'
				});
				$( 'body' ).css({
					'overflow': 'auto'
				});

			},

			selectItemType: function( itemNum, changedValue ) {
				//console.log( itemNum );
				//console.log( changedValue );
				$formWrapper = $( '#meta-box-popup-menu-' + itemNum );
				$formWrapper.find( '.post-meta-item-form' ).css({ 
					'display': 'none' 
				});
				$formWrapper.find( '.post-meta-item-' + changedValue ).css({ 
					'display': 'block' 
				});				
			},

			appendItemRow: function() {

				//console.log( 'shapeshifterPMSC appendItemRow' );

				var ajaxUpdateData = $.ajax({
					type: "POST",
					url: ajaxurl,
					context: this,
					dataType: "html",
					data: {
						"post_id": shapeshifterPMSC.postId,
						"item_num": shapeshifterPMSC.itemNumMax,
						action: "print_table_row"
					},
					error: function( jqHXR, textStatus, errorThrown ) {

						console.log( jqHXR );
						console.log( textStatus );
						console.log( errorThrown );

						jqHXR = textStatus = errorThrown = null;

					},
					success: function( data, textStatus, jqHXR ) {

						//console.log( $( data ).data( 'item-num' ) );

						postMetaRowItemSelector = '#post-meta-row-item-' + $( data ).data( 'item-num' );

						$( 'table#meta-boxes-prints-settings > tbody' ).append( data );

						// ポップアップ
							$( postMetaRowItemSelector ).find( '.edit-meta-box-item' ).click( function( e ) {

								e.preventDefault();
								//shapeshifterPMSC.popupMenuBox( parseInt( $( this ).data( 'item-num' ) ) );
								shapeshifterPMSC.popupMenuBox( this, parseInt( $( this ).data( 'item-num' ) ) );

							});

						// カラーピッカー
							$( postMetaRowItemSelector ).find( '.alpha-color-picker' ).alphaColorPicker();

						// アイテムタイプ
							$( postMetaRowItemSelector ).find( '.sub-content-item-type' ).change( function( e ) {

								e.preventDefault();
								//shapeshifterPMSC.selectItemType( parseInt( $( this ).data( 'item-num' ) ), $( this ).val() );
								shapeshifterPMSC.selectItemType( parseInt( $( this ).data( 'item-num' ) ), $( this ).val() );

							});

						// クローズボタン
							/*$( postMetaRowItemSelector ).find( '.close-post-meta-popup-menu' ).click(function( e ) {
								e.preventDefault();
								shapeshifterPMSC.closeMenuBox( this, parseInt( shapeshifterPMSC.itemNumMax ) );
							});*/

						// イメージをセット
							$( postMetaRowItemSelector ).find( '.post-meta-set-images' ).click( function( e ) {

								e.preventDefault();
								//shapeshifterPMSC.setImages( $( this ).attr( 'data-input-hidden' ), $( this ).attr( 'data-image-box' ) );
								shapeshifterPMSC.setImages( $( this ).attr( 'data-input-hidden' ), $( this ).attr( 'data-image-box' ) );

							}); 

						// イメージを削除
							$( postMetaRowItemSelector ).find( '.post-meta-remove-images' ).click( function( e ) {

								e.preventDefault();
								//shapeshifterPMSC.removeImages( $( this ).data( 'input-hidden' ), $( this ).attr( 'data-image-box' ) );
								shapeshifterPMSC.removeImages( $( this ).attr( 'data-input-hidden' ), $( this ).attr( 'data-image-box' ) );

							}); 

						// ウィジェットエリアの変更
							$( postMetaRowItemSelector ).find( '.post-meta-widget-area-select' ).change( function( e ) {
								
								e.preventDefault();

								shapeshifterPMSC.changeValueOfWidgetArea( parseInt( $( this ).data( 'item-num' ) ), $( this ).val() );

							});

						// 登録されたウィジェットの変更
							$( postMetaRowItemSelector ).find( '.post-meta-new-registered-widget-select' ).change( function( e ) {
								
								e.preventDefault();

								shapeshifterPMSC.changeValueOfWidgetSelect( parseInt( $( this ).data( 'item-num' ) ), $( this ).val() );

							});

						//console.log( postMetaRowItemSelector );

						shapeshifterPMSC.resetupJSONData();
						shapeshifterPMSC.setupJSONData();

						data = textStatus = jqHXR = null;
					}
				}).done( function( data ) {

					data = null;

				});
				shapeshifterPMSC.itemNumMax = shapeshifterPMSC.itemNumMax + 1;
				$( '#item-number-of-the-sub-contents' ).val( shapeshifterPMSC.itemNumMax );

			},

			removeItems: function() {

				$( '.selected-item-checkboxes-to-remove:checked' ).each( function( data, index ) {
					
					$( this ).parent().parent().remove();

					shapeshifterPMSC.itemNumMax = shapeshifterPMSC.itemNumMax - 1;
					$( '#item-number-of-the-sub-contents' ).val( shapeshifterPMSC.itemNumMax );

				});

				shapeshifterPMSC.setupJSONData();
				
			},

			setImages: function( inputHidden, imageBox ) {
				console.log( $( inputHidden ) );
				var $inputHidden = $( 'input[name="' + inputHidden + '"]' );

				var uploader = wp.media({
					title: '画像の選択',
					button: {
						text: '画像を決定'	
					},
					multiple: true, 
				}).on( 'select', function() {

					var selection = uploader.state().get( 'selection' );

					// 「url」の配列を取得
					var attachments = [];
					selection.map( function( attachment ){
						attachment = attachment.toJSON();
						attachments.push( attachment.url );
					} );

					// 「imageBox」内に各imgタグを出力
					$( imageBox ).children().remove();
					attachments.forEach( function( element, index ) {
						$( imageBox ).append( '<img class="post-meta-image" src="' + element + '" width="100" height="100" style="float:left;">' );
					} );

					// データをStringで取得
					$inputHidden.val( attachments.join( ',' ) );

				}).open();
			},

			removeImages: function( inputHidden, imageBox ) {

				var $inputHidden = $( 'input[name="' + inputHidden + '"]' );
				// データを削除
				$inputHidden.val( '' );

				// イメージを削除
				$( imageBox ).children().remove();

			},

			changeValueOfWidgetArea: function( itemNum, widgetAreaId ) {

				//console.log( itemNum );
				//console.log( widgetAreaId );

				$formWrapper = $( '#post-meta-item-widget_from_widget_areas-' + itemNum );

				$formWrapper.find( '.post-meta-select-widget-item' ).css({
					'display': 'none'
				});
				$formWrapper.find( '.post-meta-select-widget-item-' + widgetAreaId ).css({
					'display': 'block'
				});

			},

			changeValueOfWidgetSelect: function( itemNum, widgetName ) {

				//console.log( itemNum );
				//console.log( widgetName );

				$formWrapper = $( '#post-meta-item-new_registered_widget-' + itemNum );

				$formWrapper.find( '.post-meta-new-registered-widget-settings-form' ).css({
					'display': 'none'
				});
				$formWrapper.find( '.post-meta-new-registered-widget-settings-form-' + widgetName ).css({
					'display': 'block'
				});

			}

		};

		shapeshifterPMSC.init();
		wp.data.dispatch( 'core/editor' ).editPost( { featured_media: 6 } );


	});
} ) ( jQuery );