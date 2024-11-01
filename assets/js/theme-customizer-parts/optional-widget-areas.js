( function( $ ) {
	
	console.log( 'optional-widget-areas.js' );

	var optionalWidgetAreasWrapperPreview = function( data, index ) {

		// Widget Area Warapper
			// Background Color
				wp.customize( data.id + '_wrapper_background_color', function( value ) {
					value.bind( function( newval ) {
						window.sseThemeMods[ data.id + '_wrapper_background_color'] = newval;
						if( newval ) {
							$( data.selectors.wrapperBackgroundColor ).css({
								'background-color': newval
							});
						} else {
							$( data.selectors.wrapperBackgroundColor ).css({
								'background-color': 'none'
							});
						}
					});
				});

			// Background Image
				wp.customize( data.id + '_wrapper_background_image', function( value ) {
					value.bind( function( newval ) {
						window.sseThemeMods[ data.id + '_wrapper_background_image'] = newval;
						if( newval ) {
							$( data.selectors.wrapperBackgroundImage ).css({
								'background-image': 'url( ' + newval + ' )'
							});
						} else {
							$( data.selectors.wrapperBackgroundImage ).css({
								'background-image': 'none'
							});
						}
					});
				});

			// Background Image Size
				wp.customize( data.id + '_wrapper_background_image_size', function( value ) {
					value.bind( function( newval ) {
						window.sseThemeMods[ data.id + '_wrapper_background_image_size'] = newval;
						$( data.selectors.wrapperBackgroundImageSize ).css({
							'background-size': newval
						});
					});
				});

			// Background Image Position Row
				wp.customize( data.id + '_wrapper_background_image_position_row', function( value ) {
					value.bind( function( newval ) {
						window.sseThemeMods[ data.id + '_wrapper_background_image_position_row'] = newval;
						$( data.selectors.wrapperBackgroundImagePositionRow ).css({
							'background-position-y': newval
						});
					});
				});

			// Background Image Position Column
				wp.customize( data.id + '_wrapper_background_image_position_column', function( value ) {
					value.bind( function( newval ) {
						window.sseThemeMods[ data.id + '_wrapper_background_image_position_column'] = newval;
						$( data.selectors.wrapperBackgroundImagePositionColumn ).css({
							'background-position-x': newval
						});
					});
				});
			// Background Image Repeat
				wp.customize( data.id + '_wrapper_background_image_repeat', function( value ) {
					value.bind( function( newval ) {
						window.sseThemeMods[ data.id + '_wrapper_background_image_repeat'] = newval;
						$( data.selectors.wrapperBackgroundImageRepeat ).css({
							'background-repeat': newval
						});
					});
				});

			// Background Image Attachment
				wp.customize( data.id + '_wrapper_background_image_attachment', function( value ) {
					value.bind( function( newval ) {
						window.sseThemeMods[ data.id + '_wrapper_background_image_attachment'] = newval;
						$( data.selectors.wrapperBackgroundImageAttachment ).css({
							'background-attachment': newval
						});
					});
				});

	};

	var optionalWidgetAreasPreview = function( widgetAreaData, index ) {

		// Font Family
			wp.customize( widgetAreaData.id + '_font_family', function( value ) {
				value.bind( function( newval ) {
					window.sseThemeMods[ widgetAreaData.id + '_font_family'] = newval;
					if( newval !== "none" ) {
						$( '#shapeshifter-theme-customize-preview' ).append( widgetAreaData.selectors.fontFamily + '{ font-family: ' + newval + '; } ' );
					} else {
						$( '#shapeshifter-theme-customize-preview' ).append( widgetAreaData.selectors.fontFamily + '{ font-family: none; } ' );
					}
				});
			});

		// CSS Animation
			wp.customize( widgetAreaData.id + '_area_animation_enter', function( value ) {
				value.bind( function( newval ) {
					window.sseThemeMods[ widgetAreaData.id + '_area_animation_enter'] = newval;
					if( newval == "none" ) {
						$( widgetAreaData.selectors.animationEnter ).removeClass( 'shapeshifter-hidden enter-animated' );
						$( widgetAreaData.selectors.animationEnter ).data( 'animation-enter', '' );
					} else {
						$( widgetAreaData.selectors.animationEnter ).addClass( 'shapeshifter-hidden enter-animated' );
						$( widgetAreaData.selectors.animationEnter ).data( 'animation-enter', newval );
					}
				});
			});

		// Widget Area
			// Background Color
				wp.customize( widgetAreaData.id + '_area_background_color', function( value ) {
					value.bind( function( newval ) {
						window.sseThemeMods[ widgetAreaData.id + '_area_background_color'] = newval;
						if( newval != '' ) {
							$( '#shapeshifter-theme-customize-preview' ).append( widgetAreaData.selectors.areaBackgroundColor + '{ background-color: ' + newval + '; } ' );
						} else {
							$( '#shapeshifter-theme-customize-preview' ).append( widgetAreaData.selectors.areaBackgroundColor + '{ background-color: none; } ' );
						}
					});
				});

			// Background Image
				wp.customize( widgetAreaData.id + '_area_background_image', function( value ) {
					value.bind( function( newval ) {
						window.sseThemeMods[ widgetAreaData.id + '_area_background_image'] = newval;
						if( newval ) {
							$( '#shapeshifter-theme-customize-preview' ).append( widgetAreaData.selectors.areaBackgroundImage + '{ background-image: url( ' + newval + ' ); } ' );
						} else {
							$( '#shapeshifter-theme-customize-preview' ).append( widgetAreaData.selectors.areaBackgroundImage + '{ background-image: none; } ' );
						}
					});
				});

			// Background Image Size
				wp.customize( widgetAreaData.id + '_area_background_image_size', function( value ) {
					value.bind( function( newval ) {
						window.sseThemeMods[ widgetAreaData.id + '_area_background_image_size'] = newval;
						$( '#shapeshifter-theme-customize-preview' ).append( widgetAreaData.selectors.areaBackgroundImageSize + '{ background-size: ' + newval + '; } ' );
					});
				});

			// Background Image Position Row
				wp.customize( widgetAreaData.id + '_area_background_image_position_row', function( value ) {
					value.bind( function( newval ) {
						window.sseThemeMods[ widgetAreaData.id + '_area_background_image_position_row'] = newval;
						$( '#shapeshifter-theme-customize-preview' ).append( widgetAreaData.selectors.areaBackgroundImagePositionRow + '{ background-position-y: ' + newval + '; } ' );
					});
				});

			// Background Image Position Column
				wp.customize( widgetAreaData.id + '_area_background_image_position_column', function( value ) {
					value.bind( function( newval ) {
						window.sseThemeMods[ widgetAreaData.id + '_area_background_image_position_column'] = newval;
						$( '#shapeshifter-theme-customize-preview' ).append( widgetAreaData.selectors.areaBackgroundImagePositionColumn + '{ background-position-x: ' + newval + '; } ' );
					});
				});

			// Background Image Repeat
				wp.customize( widgetAreaData.id + '_area_background_image_repeat', function( value ) {
					value.bind( function( newval ) {
						window.sseThemeMods[ widgetAreaData.id + '_area_background_image_repeat'] = newval;
						$( '#shapeshifter-theme-customize-preview' ).append( widgetAreaData.selectors.areaBackgroundImageRepeat + '{ background-repeat: ' + newval + '; } ' );
					});
				});

			// Background Image Attachment
				wp.customize( widgetAreaData.id + '_area_background_image_attachment', function( value ) {
					value.bind( function( newval ) {
						window.sseThemeMods[ widgetAreaData.id + '_area_background_image_attachment'] = newval;
						$( '#shapeshifter-theme-customize-preview' ).append( widgetAreaData.selectors.areaBackgroundImageAttachment + '{ background-attachment: ' + newval + '; } ' );
					});
				});

			// Padding
				wp.customize( widgetAreaData.id + '_area_padding', function( value ) {
					value.bind( function( newval ) {
						window.sseThemeMods[ widgetAreaData.id + '_area_padding'] = newval;
						$( '#shapeshifter-theme-customize-preview' ).append( widgetAreaData.selectors.areaPadding + '{ padding: ' + newval + 'px; } ' );
					});
				});

		// Widget Outer Background
			// Color
				wp.customize( widgetAreaData.id + '_outer_background_color', function( value ) {
					value.bind( function( newval ) {
						window.sseThemeMods[ widgetAreaData.id + '_outer_background_color'] = newval;
						if( newval ) {
							$( '#shapeshifter-theme-customize-preview' ).append( widgetAreaData.selectors.outerBackgroundColor + '{ background-color: ' + newval + '; } ' );
						} else {
							$( '#shapeshifter-theme-customize-preview' ).append( widgetAreaData.selectors.outerBackgroundColor + '{ background-color: none; } ' );
						}
					});
				});

			// Image
				wp.customize( widgetAreaData.id + '_outer_background_image', function( value ) {
					value.bind( function( newval ) {
						window.sseThemeMods[ widgetAreaData.id + '_outer_background_image'] = newval;
						if( newval ) {
							$( '#shapeshifter-theme-customize-preview' ).append( widgetAreaData.selectors.outerBackgroundImage + '{ background-image: url( ' + newval + ' ); } ' );
						} else {
							$( '#shapeshifter-theme-customize-preview' ).append( widgetAreaData.selectors.outerBackgroundImage + '{ background-image: none; } ' );
						}
					});
				});

			// Image Size
				wp.customize( widgetAreaData.id + '_outer_background_image_size', function( value ) {
					value.bind( function( newval ) {
						window.sseThemeMods[ widgetAreaData.id + '_outer_background_image_size'] = newval;
						$( '#shapeshifter-theme-customize-preview' ).append( widgetAreaData.selectors.outerBackgroundImageSize + '{ background-size: ' + newval + '; } ' );
					});
				});

			// Image Position Row
				wp.customize( widgetAreaData.id + '_outer_background_image_position_row', function( value ) {
					value.bind( function( newval ) {
						window.sseThemeMods[ widgetAreaData.id + '_outer_background_image_position_row'] = newval;
						$( '#shapeshifter-theme-customize-preview' ).append( widgetAreaData.selectors.outerBackgroundImagePositionRow + '{ background-position-y: ' + newval + '; } ' );
					});
				});

			// Image Position Column
				wp.customize( widgetAreaData.id + '_outer_background_image_position_column', function( value ) {
					value.bind( function( newval ) {
						window.sseThemeMods[ widgetAreaData.id + '_outer_background_image_position_column'] = newval;
						$( '#shapeshifter-theme-customize-preview' ).append( widgetAreaData.selectors.outerBackgroundImagePositionColumn + '{ background-position-x: ' + newval + '; } ' );
					});
				});

			// Image Repeat
				wp.customize( widgetAreaData.id + '_outer_background_image_repeat', function( value ) {
					value.bind( function( newval ) {
						window.sseThemeMods[ widgetAreaData.id + '_outer_background_image_repeat'] = newval;
						$( '#shapeshifter-theme-customize-preview' ).append( widgetAreaData.selectors.outerBackgroundImageRepeat + '{ background-repeat: ' + newval + '; } ' );
					});
				});

			// Image Attachment
				wp.customize( widgetAreaData.id + '_outer_background_image_attachment', function( value ) {
					value.bind( function( newval ) {
						window.sseThemeMods[ widgetAreaData.id + '_outer_background_image_attachment'] = newval;
						$( '#shapeshifter-theme-customize-preview' ).append( widgetAreaData.selectors.outerBackgroundImageAttachment + '{ background-attachment: ' + newval + '; } ' );
					});
				});

		// Widget Inner Background
			// Color
				wp.customize( widgetAreaData.id + '_inner_background_color', function( value ) {
					value.bind( function( newval ) {
						window.sseThemeMods[ widgetAreaData.id + '_inner_background_color'] = newval;
						if( newval ) {
							$( '#shapeshifter-theme-customize-preview' ).append( widgetAreaData.selectors.innerBackgroundColor + '{ background-color: ' + newval + '; } ' );
						} else {
							$( '#shapeshifter-theme-customize-preview' ).append( widgetAreaData.selectors.innerBackgroundColor + '{ background-color: none; } ' );
						}
					});
				});

			// Image
				wp.customize( widgetAreaData.id + '_inner_background_image', function( value ) {
					value.bind( function( newval ) {
						window.sseThemeMods[ widgetAreaData.id + '_inner_background_image'] = newval;
						if( newval ) {
							$( '#shapeshifter-theme-customize-preview' ).append( widgetAreaData.selectors.innerBackgroundImage + '{ background-image: url( ' + newval + ' ); } ' );
						} else {
							$( '#shapeshifter-theme-customize-preview' ).append( widgetAreaData.selectors.innerBackgroundImage + '{ background-image: none; } ' );
						}
					});
				});

			// Image Size
				wp.customize( widgetAreaData.id + '_inner_background_image_size', function( value ) {
					value.bind( function( newval ) {
						window.sseThemeMods[ widgetAreaData.id + '_inner_background_image_size'] = newval;
						$( '#shapeshifter-theme-customize-preview' ).append( widgetAreaData.selectors.innerBackgroundImageSize + '{ background-size: ' + newval + '; } ' );
					});
				});

			// Image Position Row
				wp.customize( widgetAreaData.id + '_inner_background_image_position_row', function( value ) {
					value.bind( function( newval ) {
						window.sseThemeMods[ widgetAreaData.id + '_inner_background_image_position_row'] = newval;
						$( '#shapeshifter-theme-customize-preview' ).append( widgetAreaData.selectors.innerBackgroundImagePositionRow + '{ background-position-y: ' + newval + '; } ' );
					});
				});

			// Image Position Column
				wp.customize( widgetAreaData.id + '_inner_background_image_position_column', function( value ) {
					value.bind( function( newval ) {
						window.sseThemeMods[ widgetAreaData.id + '_inner_background_image_position_column'] = newval;
						$( '#shapeshifter-theme-customize-preview' ).append( widgetAreaData.selectors.innerBackgroundImagePositionColumn + '{ background-position-x: ' + newval + '; } ' );
					});
				});

			// Image Repeat
				wp.customize( widgetAreaData.id + '_inner_background_image_repeat', function( value ) {
					value.bind( function( newval ) {
						window.sseThemeMods[ widgetAreaData.id + '_inner_background_image_repeat'] = newval;
						$( '#shapeshifter-theme-customize-preview' ).append( widgetAreaData.selectors.innerBackgroundImageRepeat + '{ background-repeat: ' + newval + '; } ' );
					});
				});

			// Image Attachment
			wp.customize( widgetAreaData.id + '_inner_background_image_attachment', function( value ) {
				value.bind( function( newval ) {
					window.sseThemeMods[ widgetAreaData.id + '_inner_background_image_attachment'] = newval;
					$( '#shapeshifter-theme-customize-preview' ).append( widgetAreaData.selectors.innerBackgroundImageAttachment + '{ background-attachment: ' + newval + '; } ' );
				});
			});

		// Design
			// Widget Border
				wp.customize( widgetAreaData.id + '_widget_border', function( value ) {
					value.bind( function( newval ) {
						window.sseThemeMods[ widgetAreaData.id + '_widget_border'] = newval;
						if( newval ) {
							$( '#shapeshifter-theme-customize-preview' ).append( widgetAreaData.selectors.widgetBorder + '{ box-shadow: 0 0 5px; } ' );
						} else {
							$( '#shapeshifter-theme-customize-preview' ).append( widgetAreaData.selectors.widgetBorder + '{ box-shadow: none; } ' );
						}
					});
				});

			// Widget Border Radius
				wp.customize( widgetAreaData.id + '_widget_border_radius', function( value ) {
					value.bind( function( newval ) {
						window.sseThemeMods[ widgetAreaData.id + '_widget_border_radius'] = newval;
						$( '#shapeshifter-theme-customize-preview' ).append( widgetAreaData.selectors.widgetBorderRadius + '{ background-radius: ' + newval + 'px; } ' );
					});
				});

			// Widget Padding
				wp.customize( widgetAreaData.id + '_widget_inner_padding', function( value ) {
					value.bind( function( newval ) {
						window.sseThemeMods[ widgetAreaData.id + '_widget_inner_padding'] = newval;
						$( '#shapeshifter-theme-customize-preview' ).append( widgetAreaData.selectors.widgetInnerPadding + '{ padding: ' + newval + 'px; } ' );
					});
				});

		// Icons
			// Widget Title Icon
				wp.customize( widgetAreaData.id + '_widget_title_fontawesome_icon_select', function( value ) {
					value.bind( function( newval ) {
						window.sseThemeMods[ widgetAreaData.id + '_widget_title_fontawesome_icon_select'] = newval;
						if( newval ) {
							$( '#shapeshifter-theme-customize-preview' ).append( widgetAreaData.selectors.widgetTitleFontAwesomeIconSelect + '{ content: "\\' + newval + '"; } ' );
						} else {
							$( '#shapeshifter-theme-customize-preview' ).append( widgetAreaData.selectors.widgetTitleFontAwesomeIconSelect + '{ content: ""; } ' );
						}
					});
				});
			// Widget Title Icon Color
				wp.customize( widgetAreaData.id + '_widget_title_fontawesome_icon_color', function( value ) {
					value.bind( function( newval ) {
						window.sseThemeMods[ widgetAreaData.id + '_widget_title_fontawesome_icon_color'] = newval;
						if( newval ) {
							$( '#shapeshifter-theme-customize-preview' ).append( widgetAreaData.selectors.widgetTitleFontAwesomeIconColor + '{ color: ' + newval + '; } ' );
						} else {
							$( '#shapeshifter-theme-customize-preview' ).append( widgetAreaData.selectors.widgetTitleFontAwesomeIconColor + '{ color: none; } ' );
						}
					});
				});

			// Widget List Icon
				wp.customize( widgetAreaData.id + '_widget_list_fontawesome_icon_select', function( value ) {
					value.bind( function( newval ) {
						window.sseThemeMods[ widgetAreaData.id + '_widget_list_fontawesome_icon_select'] = newval;
						if( newval ) {
							$( '#shapeshifter-theme-customize-preview' ).append( widgetAreaData.selectors.widgetListFontAwesomeIconSelect + '{ content: "\\' + newval + '"; } ' );
						} else {
							$( '#shapeshifter-theme-customize-preview' ).append( widgetAreaData.selectors.widgetListFontAwesomeIconSelect + '{ content: ""; } ' );
						}
					});
				});

			// Widget List Icon Color
				wp.customize( widgetAreaData.id + '_widget_list_fontawesome_icon_color', function( value ) {
					value.bind( function( newval ) {
						window.sseThemeMods[ widgetAreaData.id + '_widget_list_fontawesome_icon_color'] = newval;
						if( newval ) {
							$( '#shapeshifter-theme-customize-preview' ).append( widgetAreaData.selectors.widgetListFontAwesomeIconColor + '{ color: ' + newval + '; } ' );
						} else {
							$( '#shapeshifter-theme-customize-preview' ).append( widgetAreaData.selectors.widgetListFontAwesomeIconColor + '{ color: none; } ' );
						}
					});
				});

		// Colors
			// Widget Title
				wp.customize( widgetAreaData.id + '_title_color', function( value ) {
					value.bind( function( newval ) {
						window.sseThemeMods[ widgetAreaData.id + '_title_color'] = newval;
						if( newval ) {
							$( '#shapeshifter-theme-customize-preview' ).append( widgetAreaData.selectors.widgetTitleColor + '{ color: ' + newval + '; } ' );
						} else {
							$( '#shapeshifter-theme-customize-preview' ).append( widgetAreaData.selectors.widgetTitleColor + '{ color: none; } ' );
						}
					});
				});

			// Text
				wp.customize( widgetAreaData.id + '_text_color', function( value ) {
					value.bind( function( newval ) {
						window.sseThemeMods[ widgetAreaData.id + '_text_color'] = newval;
						if( newval ) {
							$( '#shapeshifter-theme-customize-preview' ).append( widgetAreaData.selectors.widgetTextColor + '{ color: ' + newval + '; } ' );
						} else {
							$( '#shapeshifter-theme-customize-preview' ).append( widgetAreaData.selectors.widgetTextColor + '{ color: none; } ' );
						}
					});
				});

			// Text Link
				wp.customize( widgetAreaData.id + '_link_text_color', function( value ) {
					value.bind( function( newval ) {
						window.sseThemeMods[ widgetAreaData.id + '_link_text_color'] = newval;
						if( newval ) {
							$( '#shapeshifter-theme-customize-preview' ).append( widgetAreaData.selectors.widgetLinkTextColor + '{ color: ' + newval + '; } ' );
						} else {
							$( '#shapeshifter-theme-customize-preview' ).append( widgetAreaData.selectors.widgetLinkTextColor + '{ color: none; } ' );
						}
					});
				});

	};


	// Optional Widget Area Wrapper
		var optionalWidgetAreaWrappers = [
			{
				"index": "mobileSidebar",
				"id": "mobile_sidebar",
				"selectorsKey": {
					"wrapper": ".optional-widget-area-wrapper.mobile-side-menu",
					"area": ".widget-area.mobile-side-menu",
					"key": "mobile-side-menu",
				}
			},
			{
				"index": "afterHeader",
				"id": "after_header",
				"selectorsKey": {
					"wrapper": ".optional-widget-area-wrapper.after-header",
					"area": ".widget-area.after-header",
					"key": "after-header"
				}
			},
			{
				"index": "beforeContentArea",
				"id": "before_content_area",
				"selectorsKey": {
					"wrapper": ".optional-widget-area-wrapper.before-content-area",
					"area": ".widget-area.before-content-area",
					"key": "before-content-area"
				}
			},
			{
				"index": "beforeContent",
				"id": "before_content",
				"selectorsKey": {
					"wrapper": ".optional-widget-area-wrapper.before-content",
					"area": ".widget-area.before-content",
					"key": "before-content"
				}
			},
			{
				"index": "beginningOfContent",
				"id": "beginning_of_content",
				"selectorsKey": {
					"wrapper": ".optional-widget-area-wrapper.beginning-of-content",
					"area": ".widget-area.beginning-of-content",
					"key": "beginning-of-content"
				}
			},
			{
				"index": "before1stH2OfContent",
				"id": "before_1st_h2_of_content",
				"selectorsKey": {
					"wrapper": ".optional-widget-area-wrapper.before-1st-h2-of-content",
					"area": ".widget-area.before-1st-h2-of-content",
					"key": "before-1st-h2-of-content"
				}
			},
			{
				"index": "endOfContent",
				"id": "end_of_content",
				"selectorsKey": {
					"wrapper": ".optional-widget-area-wrapper.end-of-content",
					"area": ".widget-area.end-of-content",
					"key": "end-of-content"
				}
			},
			{
				"index": "afterContent",
				"id": "after_content",
				"selectorsKey": {
					"wrapper": ".optional-widget-area-wrapper.after-content",
					"area": ".widget-area.after-content",
					"key": "after-content"
				}
			},
			{
				"index": "beforeFooter",
				"id": "before_footer",
				"selectorsKey": {
					"wrapper": ".optional-widget-area-wrapper.before-footer",
					"area": ".widget-area.before-footer",
					"key": "before-footer"
				}
			},
			{
				"index": "inFooter",
				"id": "in_footer",
				"selectorsKey": {
					"wrapper": ".optional-widget-area-wrapper.in-footer",
					"area": ".widget-area.in-footer",
					"key": "in-footer"
				}
			}
		];
		var optionalWidgetAreaWrappersArgs = [];

		optionalWidgetAreaWrappers.forEach( function( data, index ) {
			optionalWidgetAreaWrappersArgs.push({
				"index": data.index,
				"id": data.id,
				"selectors": {
					/* Wrapper Background */
						"wrapperBackgroundColor": data.selectorsKey.wrapper,
						"wrapperBackgroundImage": data.selectorsKey.wrapper,
						"wrapperBackgroundImageSize": data.selectorsKey.wrapper,
						"wrapperBackgroundImagePositionRow": data.selectorsKey.wrapper,
						"wrapperBackgroundImagePositionColumn": data.selectorsKey.wrapper,
						"wrapperBackgroundImageRepeat": data.selectorsKey.wrapper,
						"wrapperBackgroundImageAttachment": data.selectorsKey.wrapper,
				}
			});
		});

		optionalWidgetAreaWrappersArgs.forEach( function( data, index ) {

			optionalWidgetAreasWrapperPreview( data, index );

		});
		optionalWidgetAreaWrappersArgs = null;
	
	// Each Widget Area
	var optionalWidgetAreasArgs = [];
	window.sseOptionalWidgetAreasData;
	var widgetAreaNum = 0;
	for ( var id in window.sseOptionalWidgetAreasData ) {

		// DIV
		widgetAreaClass = '#' + window.sseOptionalWidgetAreasData[ id ]['id'];
		// UL
		widgetAreaUlClass = '#widget-list-' + window.sseOptionalWidgetAreasData[ id ]['id'];
		// LI
		ssWidgetClass = '.widget-li';
		// DIV
		ssWidgetInnerClass = '.widget';
		// P
		widgetTitleClass = '.widget-title';

		idFix = window.sseOptionalWidgetAreasData[ id ]['hook'] + '_' + widgetAreaNum;

		optionalWidgetAreasArgs.push({
			"index": widgetAreaNum,
			"id": idFix,
			"selectors": {
				/* Font Family */
					"fontFamily": widgetAreaClass,
					"animationEnter": widgetAreaClass,
				/* Widget Area Background */
					"areaBackgroundColor": widgetAreaClass,
					"areaBackgroundImage": widgetAreaClass,
					"areaBackgroundImageSize": widgetAreaClass,
					"areaBackgroundImagePositionRow": widgetAreaClass,
					"areaBackgroundImagePositionColumn": widgetAreaClass,
					"areaBackgroundImageRepeat": widgetAreaClass,
					"areaBackgroundImageAttachment": widgetAreaClass,
					"areaPadding": widgetAreaClass,
				/* Widget Outer Background */
					"outerBackgroundColor": widgetAreaClass + " " + ssWidgetClass,
					"outerBackgroundImage": widgetAreaClass + " " + ssWidgetClass,
					"outerBackgroundImageSize": widgetAreaClass + " " + ssWidgetClass,
					"outerBackgroundImagePositionRow": widgetAreaClass + " " + ssWidgetClass,
					"outerBackgroundImagePositionColumn": widgetAreaClass + " " + ssWidgetClass,
					"outerBackgroundImageRepeat": widgetAreaClass + " " + ssWidgetClass,
					"outerBackgroundImageAttachment": widgetAreaClass + " " + ssWidgetClass,
				/* Widget Inner Background */
					"innerBackgroundColor": widgetAreaClass + " " + ssWidgetClass + " > " + ssWidgetInnerClass,
					"innerBackgroundImage": widgetAreaClass + " " + ssWidgetClass + " > " + ssWidgetInnerClass,
					"innerBackgroundImageSize": widgetAreaClass + " " + ssWidgetClass + " > " + ssWidgetInnerClass,
					"innerBackgroundImagePositionRow": widgetAreaClass + " " + ssWidgetClass + " > " + ssWidgetInnerClass,
					"innerBackgroundImagePositionColumn": widgetAreaClass + " " + ssWidgetClass + " > " + ssWidgetInnerClass,
					"innerBackgroundImageRepeat": widgetAreaClass + " " + ssWidgetClass + " > " + ssWidgetInnerClass,
					"innerBackgroundImageAttachment": widgetAreaClass + " " + ssWidgetClass + " > " + ssWidgetInnerClass,
				/* Widget Design */
					"widgetBorder" : widgetAreaClass + " " + ssWidgetClass,
					"widgetBorderRadius" : widgetAreaClass + " " + ssWidgetClass,
					"widgetInnerPadding": widgetAreaClass + " " + ssWidgetClass,
				/* Widget Icons */
					"widgetTitleFontAwesomeIconSelect": widgetAreaClass + " " + widgetTitleClass + ":before",
					"widgetTitleFontAwesomeIconColor": widgetAreaClass + " " + widgetTitleClass + ":before",
					"widgetListFontAwesomeIconSelect": widgetAreaClass + " " + ssWidgetInnerClass + " li:before",
					"widgetListFontAwesomeIconColor": widgetAreaClass + " " + ssWidgetInnerClass + " li:before",
				/* Widget Colors */
					"widgetTitleColor": widgetAreaClass + " " + widgetTitleClass,
					"widgetTextColor": widgetAreaClass + " " + ssWidgetInnerClass + " > div",
					"widgetLinkTextColor": widgetAreaClass + " " + ssWidgetInnerClass + " > div a, " + widgetAreaClass + " " + ssWidgetInnerClass + " > div a:link, " + widgetAreaClass + " " + ssWidgetInnerClass + " > div a:visited",
			}
		});
		//console.log( widgetAreaNum );
		//console.log( window.sseOptionalWidgetAreasData[ widgetAreaNum ] );
		widgetAreaNum++;

	}

	optionalWidgetAreasArgs.forEach( function( data, index ) {

		optionalWidgetAreasPreview( data, index );

	});


}) ( jQuery );