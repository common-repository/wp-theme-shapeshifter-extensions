( function( $ ) {
	
console.log( sseOptions );

	if( window.location.href !== sseDirURLForJS.adminFrontendSettingsPageURL ) 
		return;

	$( document ).ready( function() {

		$( '#' + sseOptions.general.default_settings_tab + '-wrapper' ).addClass( 'selected' );

		$( '.tab-a' ).on( 'click', function( e ) {

			var targetDataTab = $( e.currentTarget ).attr( 'data-tab' );

			$( '.table-tabs.frontend-settings .tab' ).removeClass( 'selected' );
			$( '.tab.' + targetDataTab ).addClass( 'selected' );

			$( '.settings-wrapper' ).removeClass( 'selected' );
			$( '#' + targetDataTab + '-wrapper' ).addClass( 'selected' );

		});

	});
	
} ) ( jQuery );