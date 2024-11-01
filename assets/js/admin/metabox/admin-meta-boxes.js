/*if( typeof shapeshifterGetGeneratedPopupBackground === 'undefined' ) {
	var shapeshifterGetGeneratedPopupBackground = function( selectorId, selectorClass, zIndex ) {

		backgroundDIV = '<div id="' + selectorId + '" class="shapeshifter-generated-popup-background ' + selectorClass + '" style="z-index: ' + parseInt( zIndex ) + ';"></div>';

		return backgroundDIV;

	}
}*/
( function( $ ) {

	if( window.location.href.indexOf( sseDirURLForJS.adminEditURL ) !== -1 )
	    return;

	// 公開ボタンの無効化
	var shapeshifterHandleSafetyCheckbox = function() {
		if( $( '#disable-publish-button:checked' ).length > 0 ) {
			$( 'input#publish' ).addClass( 'disabled' );
		} else {
			$( 'input#publish' ).removeClass( 'disabled' );
		}
	};

	$( document ).ready( function() {

		// Disable Publish Button 
		shapeshifterHandleSafetyCheckbox();
		$( '#disable-publish-button' ).click( function() {
			shapeshifterHandleSafetyCheckbox();
		});
		
		// Alpha Color Picker
		$( 'input.alpha-color-picker' ).alphaColorPicker();

	});
} ) ( jQuery );