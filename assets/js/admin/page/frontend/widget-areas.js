( function( $ ) {

	if( window.location.href !== sseDirURLForJS.adminFrontendSettingsPageURL ) 
		return;

	console.log( sseSavedWidgetAreas );


	console.log( sseSettingsMenuWidgetAreasArgs );

	var optionPrefix = sseSettingsMenuWidgetAreasArgs.themeOption;

	var widgetAreasSettingsHandler = {

		init: function() {

			widgetAreasSettingsHandler.printWidgetAreas();
			$eachSettings = $( '#each-widget-area-settings' ).children();
			$eachSettings.each( function( index ) {
				widgetAreasSettingsHandler.changeNameByHook( index + 1 );
			} );
			
			widgetAreasSettingsHandler.changeWidgetAreasOrder();
			
		},

		printWidgetAreas: function() {

			$( '#widget_areas_num' ).on( 'keyup change', function( e ) {

				e.preventDefault();
				
				waNum = 0;
				
				if( e.target.value ) {
					waNum = e.target.value;
				}
				
				$( '#each-widget-area-settings' ).empty().css( 'display', 'none' );

				if( 1 <= waNum ) {

					for( count = 0; count < waNum; count++ ) {

						waHook = 'customize'; 
						waWidth = '100%'; 
						waMobileMenu = false; 
						waName = sseSettingsMenuWidgetAreasArgs.widget_area + ( parseInt( count ) + 1 ); 
						waDescription = sseSettingsMenuWidgetAreasArgs.ThisIsWidgetArea_d.replace( '%d', ( parseInt( count ) + 1 ) ); 

						if ( 'undefined' !== typeof sseSavedWidgetAreas[ count ] ) {
							if ( 'undefined' !== typeof sseSavedWidgetAreas[ count ]['hook'] ) 
								waHook        = sseSavedWidgetAreas[ count ]['hook'];
							if ( 'undefined' !== typeof sseSavedWidgetAreas[ count ]['width'] ) 
								waWidth       = sseSavedWidgetAreas[ count ]['width'];
							if ( 'undefined' !== typeof sseSavedWidgetAreas[ count ]['is_on_mobile_menu'] ) 
								waMobileMenu  = sseSavedWidgetAreas[ count ]['is_on_mobile_menu'];
							if ( 'undefined' !== typeof sseSavedWidgetAreas[ count ]['name'] ) 
								waName        = sseSavedWidgetAreas[ count ]['name'];
							if ( 'undefined' !== typeof sseSavedWidgetAreas[ count ]['description'] ) 
								waDescription = sseSavedWidgetAreas[ count ]['description'];
						}

						inputTableTags = "<table id=\"widget-areas-settings-" + count + "\" class=\"form-table\" data-widget-area-count=\"" + count + "\">";
							inputTableTags += "</tbody>";
								inputTableTags += "<tr class=\"form-table-box\">";
									inputTableTags += "<th scope=\"row\">";
										inputTableTags += "<label class=\"widget-area-number-label\">" + sseSettingsMenuWidgetAreasArgs.widget_area + ( parseInt( count ) + 1 ) + "</label>";
									inputTableTags += "</th>";
									inputTableTags += "<td>";
										inputTableTags += "<table>";
										inputTableTags += "<tbody>";

											inputTableTags += "<tr class=\"widget-area-hook\">";
												inputTableTags += "<th scope=\"row\"><label for=\"" + sseSettingsMenuWidgetAreasArgs.themeOption + "widget_areas[" + count + "][hook]\">" + sseSettingsMenuWidgetAreasArgs.hookToDisplay + "</label></th>";
												inputTableTags += "<td><select ";

													inputTableTags += "id=\"widget_area_" + count + "_hook\" ";
													inputTableTags += "name=\"" + sseSettingsMenuWidgetAreasArgs.themeOption + "widget_areas[" + count + "][hook]\"";
													inputTableTags += "class=\"widget-area-hook-select\"";
													inputTableTags += "data-widget-area-count=\"" + count + "\"";

												inputTableTags += ">";

													inputTableTags += "<option value=\"customize\" " + ( waHook == "customize" ? "selected" : "" ) + ">" + sseSettingsMenuWidgetAreasArgs.custom + "</option>";
													inputTableTags += "<option value=\"after_header\" " + ( waHook == "after_header" ? "selected" : "" ) + ">" + sseSettingsMenuWidgetAreasArgs.afterTheHeader + "</option>";
													inputTableTags += "<option value=\"before_content_area\" " + ( waHook == "before_content_area" ? "selected" : "" ) + ">" + sseSettingsMenuWidgetAreasArgs.beforeContentArea + "</option>";
													inputTableTags += "<option value=\"before_content\" " + ( waHook == "before_content" ? "selected" : "" ) + ">" + sseSettingsMenuWidgetAreasArgs.beforeTheContent + "</option>";
													inputTableTags += "<option value=\"beginning_of_content\" " + ( waHook == "beginning_of_content" ? "selected" : "" ) + ">" + sseSettingsMenuWidgetAreasArgs.atTheBeginningOfTheContent + "</option>";
													inputTableTags += "<option value=\"before_1st_h2_of_content\" " + ( waHook == "before_1st_h2_of_content" ? "selected" : "" ) + ">" + sseSettingsMenuWidgetAreasArgs.beforeTheFirstH2TagOfTheContent + "</option>";
													inputTableTags += "<option value=\"end_of_content\" " + ( waHook == "end_of_content" ? "selected" : "" ) + ">" + sseSettingsMenuWidgetAreasArgs.atTheEndOfTheContent + "</option>";
													inputTableTags += "<option value=\"after_content\" " + ( waHook == "after_content" ? "selected" : "" ) + ">" + sseSettingsMenuWidgetAreasArgs.afterTheContent + "</option>";
													inputTableTags += "<option value=\"before_footer\" " + ( waHook == "before_footer" ? "selected" : "" ) + ">" + sseSettingsMenuWidgetAreasArgs.beforeTheFooter + "</option>";
													inputTableTags += "<option value=\"in_footer\" " + ( waHook == "in_footer" ? "selected" : "" ) + ">" + sseSettingsMenuWidgetAreasArgs.inTheFooter + "</option>";

												inputTableTags += "</select></td>";
											inputTableTags += "</tr>";

											inputTableTags += "<tr class=\"widget-area-width\">";
												inputTableTags += "<th scope=\"row\"><label for=\"" + sseSettingsMenuWidgetAreasArgs.themeOption + "widget_areas[" + count + "][width]\">" + sseSettingsMenuWidgetAreasArgs.width + "</label></th>";
												inputTableTags += "<td><select ";

													inputTableTags += "id=\"widget_area_" + count + "_width\" ";
													inputTableTags += "name=\"" + sseSettingsMenuWidgetAreasArgs.themeOption + "widget_areas[" + count + "][width]\"";
												inputTableTags += ">";

													inputTableTags += "<option value=\"100%\" " + ( waWidth == "100%" ? "selected" : "" ) + ">" + sseSettingsMenuWidgetAreasArgs.max_100 + "</option>";
													inputTableTags += "<option value=\"auto\" " + ( waWidth == "auto" ? "selected" : "" ) + ">" + sseSettingsMenuWidgetAreasArgs.adaptedToContentAreaWidth + "</option>";
													inputTableTags += "<option value=\"1280px\" " + ( waWidth == "1280px" ? "selected" : "" ) + ">" + sseSettingsMenuWidgetAreasArgs.threeColumns1280 + "</option>";
													inputTableTags += "<option value=\"960px\" " + ( waWidth == "960px" ? "selected" : "" ) + ">" + sseSettingsMenuWidgetAreasArgs.twoColumns960 + "</option>";
													inputTableTags += "<option value=\"870px\" " + ( waWidth == "870px" ? "selected" : "" ) + ">" + sseSettingsMenuWidgetAreasArgs.oneColumn870 + "</option>";
													inputTableTags += "<option value=\"600px\" " + ( waWidth == "600px" ? "selected" : "" ) + ">" + sseSettingsMenuWidgetAreasArgs.sixHundred + "</option>";
													inputTableTags += "<option value=\"400px\" " + ( waWidth == "400px" ? "selected" : "" ) + ">" + sseSettingsMenuWidgetAreasArgs.fourHundred + "</option>";
													inputTableTags += "<option value=\"300px\" " + ( waWidth == "300px" ? "selected" : "" ) + ">" + sseSettingsMenuWidgetAreasArgs.threeHundred + "</option>";

												inputTableTags += "</select></td>";
											inputTableTags += "</tr>";

											inputTableTags += "<tr class=\"widget-area-is_on_mobile_menu\">";
												inputTableTags += "<th scope=\"row\"><label for=\"" + sseSettingsMenuWidgetAreasArgs.themeOption + "widget_areas[" + count + "][is_on_mobile_menu]\">" + sseSettingsMenuWidgetAreasArgs.printInSideMenu_onlyForMobile + "</label></th>";
												inputTableTags += "<td><input ";
													inputTableTags += "type=\"checkbox\" ";
													inputTableTags += "id=\"widget_area_" + count + "_is_on_mobile_menu\" ";
													inputTableTags += "class=\"regular-checkbox\" ";
													inputTableTags += "name=\"" + sseSettingsMenuWidgetAreasArgs.themeOption + "widget_areas[" + count + "][is_on_mobile_menu]\" ";
													inputTableTags += "value=\"is_on_mobile_menu\" ";
													inputTableTags += ( waMobileMenu == "is_on_mobile_menu" ? " checked" : "" );
												inputTableTags += "/></td>";
											inputTableTags += "</tr>";
											
											inputTableTags += "<tr class=\"widget-area-name\">";
												inputTableTags += "<th scope=\"row\"><label for=\"" + sseSettingsMenuWidgetAreasArgs.themeOption + "widget_areas[" + count + "][name]\">" + sseSettingsMenuWidgetAreasArgs.name + "</label></th>";
												inputTableTags += "<td><input ";
													inputTableTags += "type=\"text\" ";
													inputTableTags += "id=\"widget_area_" + count + "_name\" ";
													inputTableTags += "class=\"regular-text-field change-target\" ";
													inputTableTags += "name=\"" + sseSettingsMenuWidgetAreasArgs.themeOption + "widget_areas[" + count + "][name]\" ";
													inputTableTags += "value=\"" + waName + "\" ";
													inputTableTags += "style=\"width: 100%;\" ";
												inputTableTags += "/></td>";
											inputTableTags += "</tr>";
											
											inputTableTags += "<tr class=\"widget-area-description\">";
												inputTableTags += "<th scope=\"row\"><label for=\"" + sseSettingsMenuWidgetAreasArgs.themeOption + "widget_areas[" + count + "][description]\">" + sseSettingsMenuWidgetAreasArgs.description + "</label></th>";
												inputTableTags += "<td><textarea ";
													inputTableTags += "id=\"widget_area_" + count + "_description\" ";
													inputTableTags += "name=\"" + sseSettingsMenuWidgetAreasArgs.themeOption + "widget_areas[" + count + "][description]\" ";
													inputTableTags += "style=\"width: 100%;\" ";
												inputTableTags += ">" + waDescription + "</textarea></td>";
											inputTableTags += "</tr>";

										inputTableTags += "</tbody>";
										inputTableTags += "</table>";
									inputTableTags += "</td>";
								inputTableTags += "</tr>";
							inputTableTags += "</tbody>";
						inputTableTags += "</table>";
						
						$( '#each-widget-area-settings' ).append( inputTableTags );

						widgetAreasSettingsHandler.changeNameByHook( count );

					}
					
				}

				$( '#each-widget-area-settings' ).fadeIn();
				//widgetAreasSettingsHandler.changeNameByHook();
				widgetAreasSettingsHandler.changeWidgetAreasOrder();

			});

		},

		changeNameByHook: function( index ) {

			//$( '.widget-area-hook-select' ).unbind( 'change' );
			$( '#widget-areas-settings-' + index ).find( '.widget-area-hook-select' ).bind( 'change', function( e ) {

				//e.preventDefault();

				widgetAreaCount = $( this ).data( 'widget-area-count' );
				widgetAreaHookName = $( this ).find( 'option[value=' + $( this ).val() + ']' ).text();

				$( '#widget_area_' + widgetAreaCount + '_name' ).val( widgetAreaHookName + ' ' + ( parseInt( widgetAreaCount ) + 1 ) );
				$( '#widget_area_' + widgetAreaCount + '_description' ).val( sseSettingsMenuWidgetAreasArgs.description_s_d.replace( '%s', widgetAreaHookName ).replace( '%d', ( parseInt( widgetAreaCount ) + 1 ) ) );

			});

		},

		changeWidgetAreasOrder: function() {

			$( '#each-widget-area-settings' ).sortable( {
				placeholder: "ui-state-highlight",
				axis: "y",
				update: function( e, ui ) {

					widgetAreasSettingsHandler.sortWidgetAreasInputName( this );					
					//widgetAreasSettingsHandler.changeNameByHook();

				}

			} );

		},

		sortWidgetAreasInputName: function( context ) {

			console.log( context );

			$this = $( context ).children();

			$this.each( function( index ) {

				console.log( index );
				
				widgetAreaNum = index + 1;
				$item = $( $this[ index ] );

				$item.attr( 'id', 'widget-areas-settings' + widgetAreaNum );
				$item.attr( 'data-widget-area-count', index );
				$item.find( '.widget-area-number-label' ).text( sseSettingsMenuWidgetAreasArgs.widget_area + widgetAreaNum );

				// フック
					$item.find( '.widget-area-hook label' ).attr( 'for', optionPrefix + 'widget_areas[' + widgetAreaNum + '][hook]' );
					$item.find( '.widget-area-hook select' ).attr( 'id', 'widget_area_' + widgetAreaNum + '_hook' );
					$item.find( '.widget-area-hook select' ).attr( 'name', optionPrefix + 'widget_areas[' + widgetAreaNum + '][hook]' );
					$item.find( '.widget-area-hook select' ).attr( 'data-widget-area-count', index );

				// 横幅
					$item.find( '.widget-area-width label' ).attr( 'for', optionPrefix + 'widget_areas[' + widgetAreaNum + '][width]' );
					$item.find( '.widget-area-width select' ).attr( 'id', 'widget_area_' + widgetAreaNum + '_width' );
					$item.find( '.widget-area-width select' ).attr( 'name', optionPrefix + 'widget_areas[' + widgetAreaNum + '][width]' );
					$item.find( '.widget-area-width select' ).attr( 'data-widget-area-count', index );

				// モバイル
					$item.find( '.widget-area-is_on_mobile_menu label' ).attr( 'for', optionPrefix + 'widget_areas[' + widgetAreaNum + '][is_on_mobile_menu]' );
					$item.find( '.widget-area-is_on_mobile_menu input' ).attr( 'id', 'widget_area_' + widgetAreaNum + '_is_on_mobile_menu' );
					$item.find( '.widget-area-is_on_mobile_menu input' ).attr( 'name', optionPrefix + 'widget_areas[' + widgetAreaNum + '][is_on_mobile_menu]' );
					$item.find( '.widget-area-is_on_mobile_menu input' ).attr( 'data-widget-area-count', index );

				// 名前
					$item.find( '.widget-area-name label' ).attr( 'for', optionPrefix + 'widget_areas[' + widgetAreaNum + '][name]' );
					$item.find( '.widget-area-name input' ).attr( 'id', 'widget_area_' + widgetAreaNum + '_name' );
					$item.find( '.widget-area-name input' ).attr( 'name', optionPrefix + 'widget_areas[' + widgetAreaNum + '][name]' );
					$item.find( '.widget-area-name input' ).attr( 'data-widget-area-count', index );

				// 詳細
					$item.find( '.widget-area-description label' ).attr( 'for', optionPrefix + 'widget_areas[' + widgetAreaNum + '][description]' );
					$item.find( '.widget-area-description textarea' ).attr( 'id', 'widget_area_' + widgetAreaNum + '_description' );
					$item.find( '.widget-area-description textarea' ).attr( 'name', optionPrefix + 'widget_areas[' + widgetAreaNum + '][description]' );
					$item.find( '.widget-area-description textarea' ).attr( 'data-widget-area-count', index );

				console.log( $item );

				// 名前変更
					widgetAreasSettingsHandler.changeNameByHook( widgetAreaNum );

			} );

		}

	};
	
	$( document ).ready( function() {
		
		widgetAreasSettingsHandler.init();

	});
	
} ) ( jQuery );