( function( $ ) { 

$( document ).ready( function() {

	// AJAX ロード（アーカイブページのみ）
		if( $( '.ajax-post-list' ).exists() ) {
			var shapeshifterClickCount = 1;
			$( '#ajax-next-page' ).click( function( e ) {
				e.preventDefault();

				$( '#ajax-load-posts-button' ).css({ 'display': 'none' });

				//count = $( '#post-list' ).children().length;
				//posts_per_page = $( '#query-var-posts_per_page' ).attr( 'value' );

				// 現在のページのURL
				//if( typeof currentUrl != 'undefined' ) {
					currentUrl = document.URL;
				//}

				if( shapeshifterOptionPage.structure === 'custom' ) {

					// 前のページを持っているか（/page/num/）があるかどうか確認
					hasPrevUrl = currentUrl.match( /(\/page\/[\d]+\/)/ );
					// 次のページリンクを作成
					if( hasPrevUrl == null ) {
						//console.log( hasPrevUrl );
					 	lastLetterOfUrl = currentUrl.slice(-1);
						//console.log( 'lastLetterOfUrl : ' + lastLetterOfUrl );
						if( lastLetterOfUrl !== '/' ) {
							nextUrl = currentUrl + '/page/' + ( 1 + shapeshifterClickCount ) + '/';
						} else {
							nextUrl = currentUrl + 'page/' + ( 1 + shapeshifterClickCount ) + '/';
						}
						//console.log( nextUrl );

					} else {
						//console.log( hasPrevUrl );
						var pageNumReplace = function( match, m1, m2, m3, offset, string ) {
							m2 = parseInt( m2 ) + parseInt( shapeshifterClickCount );
							return m1 + m2 + m3;
						}
						nextUrl = currentUrl.replace( /(\/?page\/)([\d]+)(\/)?/, pageNumReplace );
						//console.log( nextUrl );
					}

				} else {

					// 前のページを持っているか（/page/num/）があるかどうか確認
					hasPrevUrl = currentUrl.match( /\?[.]*?paged\=/ );
					// 次のページリンクを作成
					if( hasPrevUrl == null ) {
						hasQueryVars = currentUrl.match( /\?/ );
						if( hasQueryVars == null ) {
							nextUrl = currentUrl + '?paged=' + ( 1 + shapeshifterClickCount );
						} else {
							nextUrl = currentUrl + '&paged=' + ( 1 + shapeshifterClickCount );							
						}
					} else {
						var pageNumReplace = function( match, m1, m2, offset, string ) {
							m2 = parseInt( m2 ) + parseInt( shapeshifterClickCount );
							return m1 + m2;
						}
						nextUrl = currentUrl.replace( /(\?paged=)([\d]+)/, pageNumReplace );
					}

				}

				// AJAX 設定
				var value = $.ajax({
					type: "POST",
					url: nextUrl,
					context: this,
					data: {},
					error: function( jqHXR, textStatus, errorThrown ) {
					},
					success: function( data, textStatus, jqHXR ) {

						//var temp = document.createElement( data );
						var nextPostsParent = document.createElement( 'div' );
						nextPostsParent.innerHTML = data;
						//console.log( nextPostsParent );

						nextPosts = $( nextPostsParent ).find( '#post-list' ).children();

						for( var postOffset = 0; postOffset < nextPosts.length; postOffset++ ) {
							$( '#post-list' ).append( nextPosts[ postOffset ] );
						}
						//$( '#post-list' ).append( nextPosts );
						$( '#ajax-load-posts-button' ).css({ 'display': 'block' });

						shapeshifterClickCount++;

						// Google+
						$.getScript("https://apis.google.com/js/platform.js");										

					},
					
				});
			});
		}

}); }) ( jQuery );