( function( $ ) {
	
	console.log( 'standard-widget-areas.js' );

	/* Top Right Fix */
		// Fix Widget Area
			wp.customize( 'is_widget_area_top_right_fixed', function( value ) {
				value.bind( function( newval ) {
					window.sseThemeMods.is_widget_area_top_right_fixed = newval;
					if( newval ) {
						$( '#shapeshifter-theme-customize-preview' ).append( '.widget-area-top-right { position: fixed; } ' );
						//$( '.widget-area-top-right' ).css({ 'position' : 'fixed' });
					} else {
						$( '#shapeshifter-theme-customize-preview' ).append( '.widget-area-top-right { position: absolute; } ' );
						//$( '.widget-area-top-right' ).css({ 'position' : 'absolute' });
					}
				});
			});
		// Distance from Top
			wp.customize( 'top_right_fixed_area_top', function( value ) {
				value.bind( function( newval ) {
					window.sseThemeMods.widget_area_top_right_fixed_top = newval;
					$( '#shapeshifter-theme-customize-preview' ).append( '.widget-area-top-right { top: ' + newval + 'px; } ' );
					//$( '.widget-area-top-right' ).css({ 'top' : newval + 'px' });
				});
			});
		// Distance from Right
			wp.customize( 'top_right_fixed_area_side', function( value ) {
				value.bind( function( newval ) {
					window.sseThemeMods.widget_area_top_right_side = newval;
					$( '#shapeshifter-theme-customize-preview' ).append( '.widget-area-top-right { right: ' + newval + 'px; } ' );
					//$( '.widget-area-top-right' ).css({ 'right' : newval + 'px' });
				});
			});


	var defaultWidgetAreas = [
		{
			"index" : "slidebar_left",
			"id" : "slidebar_left",
			"selectorsKey": "slidebar-left"
		},
		{
			"index" : "sidebar_left",
			"id" : "sidebar_left",
			"selectorsKey": "sidebar-left"
		},
		{
			"index" : "sidebar_left_fixed",
			"id" : "sidebar_left_fixed",
			"selectorsKey": "sidebar-left-fixed"
		},
		{
			"index" : "slidebar_right",
			"id" : "slidebar_right",
			"selectorsKey": "slidebar-right"
		},
		{
			"index" : "sidebar_right",
			"id" : "sidebar_right",
			"selectorsKey": "sidebar-right"
		},
		{
			"index" : "sidebar_right_fixed",
			"id" : "sidebar_right_fixed",
			"selectorsKey": "sidebar-right-fixed"
		},

	];
	var defaultWidgetAreasData = [];
	defaultWidgetAreas.forEach( function( data, index ) {
		defaultWidgetAreasData.push({
			"index": data.index,
			"id": data.id,
			//"name": window.shapeshifterJSTranslatedObject.mobileSidebar,
			"selectors": {
				/* Wrapper Background */
					/*
					"wrapperBackgroundColor": "",
					"wrapperBackgroundImage": "",
					"wrapperBackgroundImageSize": "",
					"wrapperBackgroundImagePositionRow": "",
					"wrapperBackgroundImagePositionColumn": "",
					"wrapperBackgroundImageRepeat": "",
					*/
				/* Font Family */
					"fontFamily": ".widget-area-" + data.selectorsKey + "-li",
				/* CSS Animation */
					"animationEnter": ".widget-area-" + data.selectorsKey,
				/* Item Outer Background */
					"outerBackgroundColor": ".widget-area-" + data.selectorsKey + "-li",
					"outerBackgroundImage": ".widget-area-" + data.selectorsKey + "-li",
					"outerBackgroundImageSize": ".widget-area-" + data.selectorsKey + "-li",
					"outerBackgroundImagePositionRow": ".widget-area-" + data.selectorsKey + "-li",
					"outerBackgroundImagePositionColumn": ".widget-area-" + data.selectorsKey + "-li",
					"outerBackgroundImageRepeat": ".widget-area-" + data.selectorsKey + "-li",
					"outerBackgroundImageAttachment": ".widget-area-" + data.selectorsKey + "-li",
				/* Item Inner Background */
					"innerBackgroundColor": ".widget-area-" + data.selectorsKey + "-li-div",
					"innerBackgroundImage": ".widget-area-" + data.selectorsKey + "-li-div",
					"innerBackgroundImageSize": ".widget-area-" + data.selectorsKey + "-li-div",
					"innerBackgroundImagePositionRow": ".widget-area-" + data.selectorsKey + "-li-div",
					"innerBackgroundImagePositionColumn": ".widget-area-" + data.selectorsKey + "-li-div",
					"innerBackgroundImageRepeat": ".widget-area-" + data.selectorsKey + "-li-div",
					"innerBackgroundImageAttachment": ".widget-area-" + data.selectorsKey + "-li-div",
				/* item Design */
					"widgetBorder" : ".widget-area-" + data.selectorsKey + "-li",
					"widgetBorderRadius" : ".widget-area-" + data.selectorsKey + "-li",
					"widgetMargin": ".widget-area-" + data.selectorsKey + "-li-div",
				/* Item Icons */
					"widgetTitleFontAwesomeIconSelect": ".widget-area-" + data.selectorsKey + "-li .widget-area-" + data.selectorsKey + "-p:before",
					"widgetTitleFontAwesomeIconColor": ".widget-area-" + data.selectorsKey + "-li .widget-area-" + data.selectorsKey + "-p:before",
					"widgetListFontAwesomeIconSelect": ".widget-area-" + data.selectorsKey + "-li .li.archive-list-item:before, .widget-area-" + data.selectorsKey + "-li li.cat-item:before, .widget-area-" + data.selectorsKey + "-li li.menu-item:before",
					"widgetListFontAwesomeIconColor": ".widget-area-" + data.selectorsKey + "-li .li.archive-list-item:before, .widget-area-" + data.selectorsKey + "-li li.cat-item:before, .widget-area-" + data.selectorsKey + "-li li.menu-item:before",
				/* Item Colors */
					"widgetBackgroundColor": ".widget-area-" + data.selectorsKey + "-li",
					"widgetTitleColor": ".widget-area-" + data.selectorsKey + "-li .widget-area-" + data.selectorsKey + "-p",
					"widgetTextColor": {
						"itemWrapper": ".widget-area-" + data.selectorsKey + "-li-div",
						"itemExcerpt": ".widget-area-" + data.selectorsKey + "-li-div .widget-entry-excerpt",
					} ,
					"widgetLinkTextColor": ".widget-area-" + data.selectorsKey + "-li a, .widget-area-" + data.selectorsKey + "-li a:link, .widget-area-" + data.selectorsKey + "-li a:visited",
			}
		});
	});

	defaultWidgetAreasData.forEach( function( data, index ){

		/* Icons */
			/* FontAwesome Icon Select */
			wp.customize( data.id + '_widget_title_fontawesome_icon_select', function( value ) {
				value.bind( function( newval ) {
					window.sseThemeMods[ data.id + '_widget_title_fontawesome_icon_select'] = newval;
					if( newval == 'none' ) {
						$( '#shapeshifter-theme-customize-preview' ).append( data.selectors.widgetTitleFontAwesomeIconSelect + '{ content: ""; } ' );
					} else {
						$( '#shapeshifter-theme-customize-preview' ).append( data.selectors.widgetTitleFontAwesomeIconSelect + '{ content: "\\' + newval + '"; } ' );
					}
					
				});
			});
			/* Icons Color */
			wp.customize( data.id + '_widget_title_fontawesome_icon_color', function( value ) {
				value.bind( function( newval ) {
					window.sseThemeMods[ data.id + '_widget_title_fontawesome_icon_color'] = newval;
					$( '#shapeshifter-theme-customize-preview' ).append( data.selectors.widgetTitleFontAwesomeIconColor + '{ color: '+ newval +'; } ' );
				});
			});

			/* FontAwesome Icon Select */
			wp.customize( data.id + '_widget_list_fontawesome_icon_select', function( value ) {
				value.bind( function( newval ) {
					window.sseThemeMods[ data.id + '_widget_list_fontawesome_icon_select'] = newval;
					if( newval == 'none' ) {
						$( '#shapeshifter-theme-customize-preview' ).append( data.selectors.widgetListFontAwesomeIconSelect + '{ content: ""; } ' );
					} else {
						$( '#shapeshifter-theme-customize-preview' ).append( data.selectors.widgetListFontAwesomeIconSelect + ' { content: "\\' + newval + '"; } ' );
					}
				});
			});
			/* Icons Color */
			wp.customize( data.id + '_widget_list_fontawesome_icon_color', function( value ) {
				value.bind( function( newval ) {
					window.sseThemeMods[ data.id + '_widget_list_fontawesome_icon_color'] = newval;
					$( '#shapeshifter-theme-customize-preview' ).append( data.selectors.widgetListFontAwesomeIconColor + '{ color: '+ newval +'; } ' );
				});
			});

	});

}) ( jQuery );