( function( $ ) {

	if( window.location.href !== sseDirURLForJS.adminFrontendSettingsPageURL ) 
		return;

	$( document ).ready( function() {
		
		// 構造化データ用のロゴイメージの設定
			$( '#remove_button_json_ld_logo_image' ).click( function( e ) {
				$( '#json_ld_logo_image_box' ).children().remove();
				$( '#json_ld_logo' ).val( '' );
			});
			$( '#button_json_ld_logo_image' ).click( function( e ) {
				
				e.preventDefault();
				
				var uploader = wp.media({
					title: sseSettingsMenuWidgetAreasArgs.selectAnImage,
					button: {
						text: sseSettingsMenuWidgetAreasArgs.setTheImage   
					},
					multiple: false
				})
				.on( 'select', function() {
					
					var selection = uploader.state().get( 'selection' );
					
					var attachment = selection.first().toJSON();
					
					$( '#json_ld_logo_image_box' ).empty();
					$( '#json_ld_logo_image_box' ).append( '<img src="' + attachment.url + '" width="100" height="100" style="float: left;" id="tc_og_img">' );
					
					$( '#json_ld_logo' ).val( attachment.id );
						
				}).open();
				
			});

		// TC・OG用メディアの設定
			$( '#remove_button_tc_og_image' ).click( function( e ) {
				$( '#tc_og_image_box' ).children().remove();
				$( '#tc_og_image' ).val( '' );
			});
			$( '#button_tc_og_image' ).click( function( e ) {
				
				e.preventDefault();
				
				var uploader = wp.media({
					title: sseSettingsMenuWidgetAreasArgs.selectAnImage,
					button: {
						text: sseSettingsMenuWidgetAreasArgs.setTheImage
					},
					multiple: false
				})
				.on( 'select', function() {
					
					var selection = uploader.state().get( 'selection' );
					
					var attachment = selection.first().toJSON();
					
					$( '#tc_og_image_box' ).empty();
					$( '#tc_og_image_box' ).append( '<img src="' + attachment.url + '" width="100" height="100" style="float: left;" id="tc_og_img">' );
					
					$( '#tc_og_image' ).val( attachment.url );
						
				}).open();
				
			});
					
	});
	
} ) ( jQuery );