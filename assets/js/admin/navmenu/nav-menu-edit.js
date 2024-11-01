( function( $ ) {

    if( window.location.href.indexOf( sseDirURLForJS.adminNavMenuURL ) === -1 )
        return;

	shapeshifterNavMenuEditData = shapeshifterNavMenuEditData || {};

	var shapeshifterNavMenuEdit = {
		init: function() {

			// 
				$( '#description-hide' ).parent().remove();

			// Popup
				$( '.shapeshifter-nav-menu-custom-edit-popup' ).on( 'click', function( e ) {

					shapeshifterNavMenuEdit.popupMenu( e );

				});

			// Close Button Action
				$( '.shapeshifter-nav-menu-item-button-close-popup' ).on( 'click', function( e ) {

					shapeshifterNavMenuEdit.closePopupMenu( e );

				});

			// Image Set
				$( '.shapeshifter-nav-menu-item-button-set-images' ).on( 'click', function( e ) {

					shapeshifterNavMenuEdit.setImages( e );

				});

			// Image Remove
				$( '.shapeshifter-nav-menu-item-button-remove-images' ).on( 'click', function( e ) {

					shapeshifterNavMenuEdit.removeImages( e );

				});


		},

		popupMenu: function( e ) {

			e.preventDefault();

			// Settings Wrapper jQuery Object
				$itemWrapper = $( e.target ).parent().parent();

			// Popup ( Change CSS )
				$( 'body' ).css({
					'overflow': 'hidden'
				});
				$itemWrapper.find( '.shapeshifter-nav-menu-item-popup-background' ).css({
					'display': 'block'
				});
				$itemWrapper.find( '.shapeshifter-nav-menu-item-custom-edit-settings-wrapper' ).css({
					'display': 'block'
				});

		},

		closePopupMenu: function( e ) {

			// Popup Wrapper jQuery Object
				var $popupWrapper = $( e.target ).parent().parent().parent();

			// Close
				$( 'body' ).css({
					'overflow': 'auto'
				});
				$popupWrapper.find( '.shapeshifter-nav-menu-item-popup-background' ).css({
					'display': 'none'
				});
				$popupWrapper.find( '.shapeshifter-nav-menu-item-custom-edit-settings-wrapper' ).css({
					'display': 'none'
				});

		},
 
		setImages: function( e ) {

			// Image Srcs
				var $inputHidden = $( e.target ).parent().parent().find( '.edit-menu-item-thumbnails' );
			// Image Holder
				var $imageHolder = $( e.target ).parent().parent().find( '.thumbnail-image-holder' );

				var imageUploader = wp.media({
					title: '画像の選択',
					button: {
						text: '画像を決定'	
					},
					multiple: true
				}).on( 'select', function() {
				
					var selection = imageUploader.state().get( 'selection' );
					
					// 「url」の配列を取得
						var attachments = [];
						selection.map( function( attachment ){
							attachment = attachment.toJSON();
							attachments.push( attachment.url );
						} );
					
					// 「imageBox」内に各imgタグを出力
						$imageHolder.children().remove();
						attachments.forEach( function( element, index ) {
							$imageHolder.append( '<img src="' + element + '" width="100" height="100" style="float:left;">' );
						} );
					
					// データをStringで取得
						$inputHidden.val( attachments.join( ',' ) );

				}).open();

		},

		removeImages: function( e ) {

			// Image Srcs
				var $inputHidden = $( e.target ).parent().parent().find( '.edit-menu-item-thumbnails' );
				$inputHidden.val( '' );

			// Image Holder
				var $imageHolder = $( e.target ).parent().parent().find( '.thumbnail-image-holder' );
				$imageHolder.children().remove();

		}

	};

	$( document ).on( 'ready', function( event, request, settings ) {
		shapeshifterNavMenuEdit.init();
	});

}) ( jQuery );