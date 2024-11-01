( function( $ ) {

	console.log( 'content-page.js' );

	/* Headlines */
		function shapeshifterExtensionsModsLivePreviewHeadings( element ) {
			/* FontAwesome Icon Select */
			wp.customize( 'singular_page_' + element + '_fontawesome_icon_select', function( value ) {
				value.bind( function( newval ){
					window.sseThemeMods['singular_page_' + element + '_fontawesome_icon_select'] = newval;
					targetClass = '.entry-content ' + element + ':before';
					if ( 'h1' === element ) {
						targetClass += ',.main-content ' + element + '.entry-title:before';
					}
					if( newval == 'none' ) {
						$( '#shapeshifter-theme-customize-preview' ).append( targetClass + '{ content: "" !important; } ' );
					} else {
						$( '#shapeshifter-theme-customize-preview' ).append( targetClass + '{ content: "\\' + newval + '" !important; } ' );
					}
				});
			});
			/* Icons Color */
			wp.customize( 'singular_page_' + element + '_fontawesome_icon_color', function( value ) {
				value.bind( function( newval ){ 
					window.sseThemeMods['singular_page_' + element + '_fontawesome_icon_color'] = newval;
					$( '#shapeshifter-theme-customize-preview' ).append( '.entry-content ' + element + ':before{ color: '+ newval +'; } ' );
									
				});
			});

		}
		var elements = ['h1', 'h2', 'h3', 'h4', 'h5', 'h6', 'p'];
		elements.forEach( function( data, index ) {
			shapeshifterExtensionsModsLivePreviewHeadings( data );
		});



}) ( jQuery );