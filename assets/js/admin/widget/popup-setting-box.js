( function( $ ) {

	if( window.location.href.indexOf( sseDirURLForJS.adminWidgetsURL ) === -1 )
		return;

	$( document ).on( 'ready widget-updated widget-added', function() {

		$( '.popup-next-settings-box' ).click( function( e ) {

			var popupBGId = 'shapeshifter-general-popup-background';

			if( ! $( '#' + popupBGId ).exists() ) {

				shapeshifterGeneralMethods.insertPopupBackground( 
					this,
					popupBGId, 
					popupBGId, 
					9999 
				);

			}

			shapeshifterGeneralMethods.popupNextSettingsBox( 
				'#' + popupBGId + ' + .widget-settings-box', 
				'#' + popupBGId
			);
			//popupNextSettingsBox( '#' + e.target.id + ' + .widget-settings-box' );

			$( '.close-this-settings-box, #' + popupBGId ).click( function( e ) {
				shapeshifterGeneralMethods.closeSettingsBox( 
					'#' + popupBGId + ' + .widget-settings-box', 
					'#' + popupBGId
				);
				//closeSettingsBox();
			});

		});

	});

	$( document ).ready( function() {
		//$( '.color-setting' ).alphaColorPicker();
	});
	$( document ).on( 'widget-updated', function() {
		//$( '.color-setting' ).alphaColorPicker();
	});
	$( document ).on( 'widget-added', function() {
		//$( '.color-setting' ).alphaColorPicker();
	});
}) ( jQuery );
