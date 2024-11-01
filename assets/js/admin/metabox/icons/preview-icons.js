( function( $ ) {

	if( window.location.href.indexOf( sseDirURLForJS.adminEditURL ) !== -1 )
	    return;

	var ssePreviewIcons = {

		previewIconSelect: '.sse-preview-icons',

		init: function()
		{
			$( ssePreviewIcons.previewIconSelect ).each( function( index ) {
				$targetSelectTag = $( this );
				ssePreviewIcons.applyEventPreview( $targetSelectTag );
			});
		},

		applyEventPreview: function( $select )
		{
			ssePreviewIcons.resetClasses( $select )

			$select.on( 'click', function( e ) {
				var $targetSelect = $( this );
				ssePreviewIcons.resetClasses( $targetSelect )
			} );
		},

		resetClasses: function( $select )
		{
			console.log( $select.children() );
			$select.children().each( function( optionIndex ) {
				var optionVal = $( this ).val();
				var optionDataClass = $( this ).data( 'class' );
				var iconUnicode = $( this ).attr( 'data-icon-unicode' );
				$( this ).html( iconUnicode );
			});

		},


	};

	$( document ).ready( function() {

		//ssePreviewIcons.init();

	});


} ) ( jQuery );