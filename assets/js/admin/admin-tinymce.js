( function( $ ) {

    //if( window.location.href.indexOf( sseDirURLForJS.adminPostURL ) === -1 )
		//return;

	var tinymceHandler = {
		init: function() {
            $( 'iframe#content_ifr' ).contents().find( '.shapeshifter-draggable' ).draggable();
            console.log( $( 'iframe#content_ifr' ).contents().find( '.shapeshifter-draggable' ) );
		}
	};
	$( document ).ready( function( $ ) {
		tinymceHandler.init();
	});
}) ( jQuery );