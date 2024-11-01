( function( $ ) {

	if( window.location.href.indexOf( sseDirURLForJS.adminEditURL ) !== -1 )
	    return;

	$( document ).ready( function() {

		var shapeshifterPMSEO = {

			inputJSONSelector: "input#shapeshifter-meta-box-seo-json",

			init: function() {

				$( '.shapeshifter-meta-box-seo' ).on( 'change', function( e ) {

					shapeshifterPMSEO.setupJSONData( e, this );

				});

				$( '#publish, #save-post' ).on( 'click', function( e ) {
//					e.preventDefault();
					shapeshifterPMSEO.setupJSONData( e, this );
					shapeshifterPMSEO.disableInputs( e, this );

				});

				$( 'form#post' ).submit( function( e ) {
					shapeshifterPMSEO.setupJSONData( e, this );
				});

			},

			setupJSONData: function( e, context ) {

				var jsonData = {};

				$( '.shapeshifter-meta-box-seo' ).each( function() {
					
					var index = $( this ).attr( 'index' );

					jsonData[ index ] = shapeshifterPMSEO.getSettingValue( $( this ), index );//$( this ).val();

				});

				$( shapeshifterPMSEO.inputJSONSelector ).val( JSON.stringify( jsonData ) );

			},
				getSettingValue: function( $context, index ) {

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

			disableInputs: function( e, context ) {

				$( '.shapeshifter-meta-box-seo' ).prop( "disabled", true );

			}

		};
		shapeshifterPMSEO.init();

	});
} ) ( jQuery );