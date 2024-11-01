( function( $ ) {

	if( window.location.href !== sseDirURLForJS.adminProfileURL ) 
		return;

	var shapeshifterUserMetaForAdminMessage = {

		init: function() {

			$( '.shapeshifter-dismiss-admin-message-button' ).click( function( e ) {

				userID = $( this ).data( 'user-id' );
				buttonType = $( this ).data( 'button-type' );
				wpnonce = $( '#shapeshifter_dismissing_message_nonce' ).val();

				$( this ).parent().parent().remove();

				shapeshifterUserMetaForAdminMessage.buttonPressed( this, parseInt( userID ), buttonType, wpnonce )

			} );

		},

		buttonPressed: function( context, userID, buttonType, wpnonce ) {

			$.ajax({
				type: "POST",
				url: ajaxurl,
				context: context,
				dataType: "html",
				data: {
					"shapeshifterDismissAdminMessage": wpnonce,
					"userID": userID,
					"buttonType": buttonType,
					action: "dismiss_message_for_user_slug"
				},
				error: function( jqHXR, textStatus, errorThrown ) {

					jqHXR = textStatus = errorThrown = null;

				},
				success: function( data, textStatus, jqHXR ) {

					console.log( data );

				}

			}).done( function( data ) {

			} );

		}
	};

	$( document ).ready( function() {
		shapeshifterUserMetaForAdminMessage.init();
	} );

} ) ( jQuery );