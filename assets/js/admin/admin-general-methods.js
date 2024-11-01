jQuery.fn.extend({
	exists: function() { 
		return Boolean( this.length > 0 ); 
	},
});
var shapeshifterGeneralMethods = {

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

	/**
	 * [parseTargetInputs description]
	 *  Make obj like {
		index1: {
			index2: val
			...
		}
	 }
	 * @param  {[type]} baseName [description]
	 * @return {[type]}          [description]
	 */
	parseTargetInputs: function( baseName )
	{

		var $ = jQuery, nameObj = {};
		$targetInputs = $( "input[name^='" + baseName + "['],select[name^='" + baseName + "['],textarea[name^='" + baseName + "[']" );

		$targetInputs.each( function( index, element ) {

			var nameHie = [];// [ index1, index2, ... ]

			var $element = $( $targetInputs[ index ] );
			var name = $element.attr( 'name' );
			var val = shapeshifterGeneralMethods.inputVal( $element );
			if ( 'undefined' === typeof name ) {
				return;
			}
			regex = /\[([^\]]+)\]/g;
			var namesWithBracket = name.match( regex );
			if ( null === namesWithBracket ) {
				return;
			}

			var nameNum = parseInt( namesWithBracket.length );
			maxNameIndex = nameNum - 1;
			nextHoler = nameObj;
			for ( var i in namesWithBracket ) {

				var nameIndex = namesWithBracket[ i ].replace( /[\[\]]/g, '' );
				nameHie.push( nameIndex );

				if ( i == maxNameIndex ) {
					nextHoler[ nameIndex ] = val;
				} else {
					if ( 'undefined' === typeof nextHoler[ nameIndex ] ) {
						nextHoler[ nameIndex ] = {};
					}
				}
				nextHoler = nextHoler[ nameIndex ];

			}

		});

		return nameObj;

	},

	inputVal: function( selector )
	{
		var $ = jQuery, val = '',
			inputType = $( selector ).attr( 'type' );

		if ( 'checkbox' === inputType 
			|| 'radio' === inputType
		) {
			if ( $( selector ).prop( 'checked' ) ) {
				val = $( selector ).val();
			}
		} else {
			val = $( selector ).val();
		}

		return val;

	}

};