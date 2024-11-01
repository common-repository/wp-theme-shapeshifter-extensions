( function( $ ) {

	if( window.location.href.indexOf( sseDirURLForJS.adminCustomizerURL ) !== -1 )
		return;

	var require = function( script ) {
		$.ajax({
			url: script,
			dataType: "script",
			//async: false,
			success: function () {
			},
			error: function () {
				//require.call( this, script );
				throw new Error( "Could not load script " + script + ". Now reloading..." );
			}
		});
	};

	window.sseThemeMods = sseThemeMods;
	window.shapeshifterOptionalWidgetAreasData = sseOptionalWidgetAreasData;

	// check if the selected item already exists
	jQuery.fn.exists = function() { return Boolean( this.length > 0 ); }
	
	if ( ! $( '#shapeshifter-theme-customize-preview' ).exists() ) {
		$( 'body' ).append( '<style id="shapeshifter-theme-customize-preview"></style>' );
	}

// Body
	require( window.sseThemeCustomizerObject.pluginAssetsURI + 'js/theme-customizer-parts/body.js' );
// Header
	require( window.sseThemeCustomizerObject.pluginAssetsURI + 'js/theme-customizer-parts/header.js' );
// Logo Image and etc
	require( window.sseThemeCustomizerObject.pluginAssetsURI + 'js/theme-customizer-parts/header-image.js' );
// Nav Menu
	require( window.sseThemeCustomizerObject.pluginAssetsURI + 'js/theme-customizer-parts/navi-menu.js' );
// Main Content
	require( window.sseThemeCustomizerObject.pluginAssetsURI + 'js/theme-customizer-parts/content-area.js' );
// Archive Page
	require( window.sseThemeCustomizerObject.pluginAssetsURI + 'js/theme-customizer-parts/archive-page.js' );
// Content Page
	require( window.sseThemeCustomizerObject.pluginAssetsURI + 'js/theme-customizer-parts/content-page.js' );
// Footer
	require( window.sseThemeCustomizerObject.pluginAssetsURI + 'js/theme-customizer-parts/footer.js' );
// Standard Widget Areas
	require( window.sseThemeCustomizerObject.pluginAssetsURI + 'js/theme-customizer-parts/standard-widget-areas.js' );
// Optional Widget Areas
	require( window.sseThemeCustomizerObject.pluginAssetsURI + 'js/theme-customizer-parts/optional-widget-areas.js' );
// Others
	require( window.sseThemeCustomizerObject.pluginAssetsURI + 'js/theme-customizer-parts/others.js' );

} ) ( jQuery );