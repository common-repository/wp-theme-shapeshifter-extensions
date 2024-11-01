( function( $ ) {
	$( document ).ready( function() {
		
		console.log( 'get-image.js loaded!' );
		
		// 「wp_enqueue_media」が必要

		var getMediaFromLibrary = function( button, inputHidden, imageBox, isMulti ) {

			console.log( 'function loaded!' );
			
			$( button ).click( function( e ) {
				
				console.log( 'clicked!' );
				
				var $el = $( this ).parent();
				e.preventDefault();
				
				var uploader = wp.media({
					title: '画像の選択',
					button: {
						text: '画像を決定'	
					},
					multiple: function( isMulti ) {
						if( isMulti ) { 
							console.log( 'multi' );
							return true; 
						} else { 
							console.log( 'solo' );
							return false; 
						}
					}
				}).on( 'select', function() {
					
					var selection = uploader.state().get( 'selection' );
					
					if( isMulti ) { // 複数の場合
						
						// 「url」の配列を取得
						var attachments = [];
						selection.map( function( attachment ){
							attachment = attachment.toJSON();
							attachments.push( attachment.url );
						} );
						
						// 「imageBox」内に各imgタグを出力
						$( imageBox ).children().remove();
						attachments.forEach( function( element, index ) {
							$( imageBox ).append( '<img src="' + element + '" width="100" height="100" style="float:left;">' );
						} );
						
						// データをStringで取得
						$( inputHidden ).val( attachments.join( ',' ) );
					
					} else { // 単数の場合
						
						var attachment = selection.first().toJSON();
						
						$( imageBox + ' img' ).attr( 'src', attachment.url );
						$( inputHidden ).val( attachment.url );
						
					}
				}).open();
				
			});
			
		};
	});
})(jQuery);
