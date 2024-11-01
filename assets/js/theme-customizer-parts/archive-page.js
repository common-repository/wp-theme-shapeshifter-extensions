( function( $ ) {
	
	console.log( 'archive-page.js' );

	/* Post List Title */
		/* FontAwesome Icon Select */
		wp.customize( 'archive_page_post_list_title_fontawesome_icon_select', function( value ) {
			value.bind( function( newval ) {
				window.sseThemeMods.archive_page_post_list_title_fontawesome_icon_select = newval;
				if( newval == 'none' ) {
					$( '#shapeshifter-theme-customize-preview' ).append( '.post-list-title-div .post-list-title-div-h2:before{ content: ""; } ' );
				} else {
					$( '#shapeshifter-theme-customize-preview' ).append( '.post-list-title-div .post-list-title-div-h2:before{ content: "\\' + newval + '"; } ' );
				}
			});
		});
		/* Icons Color */
		wp.customize( 'archive_page_post_list_title_fontawesome_icon_color', function( value ) {
			value.bind( function( newval ) {
				window.sseThemeMods.archive_page_post_list_title_fontawesome_icon_color = newval;
				$( '#shapeshifter-theme-customize-preview' ).append( '.post-list-title-div .post-list-title-div-h2:before{ color: '+ newval +'; } ' );
			});
		});



	/* Display */
		wp.customize( 'archive_page_read_later_text', function( value ) {
			value.bind( function( newval ) {
				window.sseThemeMods.archive_page_read_later_text = newval;
				$( '.post-list-read-later-span' ).html( newval ).text();
			});
		});

		wp.customize( 'archive_page_read_later_twitter', function( value ) {
			value.bind( function( newval ) {
				window.sseThemeMods.archive_page_read_later_twitter = newval;
				if( newval ) {
					$( '#shapeshifter-theme-customize-preview' ).append( '.shapeshifter-post-list-twitter-button-li { display: block; } ' );
					$( '#shapeshifter-theme-customize-preview' ).append( '.shapeshifter-post-list-twitter-icon-li { display: block; } ' );
					//$( '.shapeshifter-post-list-twitter-button-li' ).css({ 'display': 'block' });
					//$( '.shapeshifter-post-list-twitter-icon-li' ).css({ 'display': 'block' });
				} else {
					$( '#shapeshifter-theme-customize-preview' ).append( '.shapeshifter-post-list-twitter-button-li { display: none; } ' );
					$( '#shapeshifter-theme-customize-preview' ).append( '.shapeshifter-post-list-twitter-icon-li { display: none; } ' );
					//$( '.shapeshifter-post-list-twitter-button-li' ).css({ 'display': 'none' });
					//$( '.shapeshifter-post-list-twitter-icon-li' ).css({ 'display': 'none' });
				}
			});
		});
		wp.customize( 'archive_page_read_later_facebook', function( value ) {
			value.bind( function( newval ) {
				window.sseThemeMods.archive_page_read_later_facebook = newval;
				if( newval ) {
					$( '#shapeshifter-theme-customize-preview' ).append( '.shapeshifter-post-list-facebook-button-li { display: block; } ' );
					$( '#shapeshifter-theme-customize-preview' ).append( '.shapeshifter-post-list-facebook-icon-li { display: block; } ' );
					//$( '.shapeshifter-post-list-facebook-button-li' ).css({ 'display': 'block' });
					//$( '.shapeshifter-post-list-facebook-icon-li' ).css({ 'display': 'block' });
				} else {
					$( '#shapeshifter-theme-customize-preview' ).append( '.shapeshifter-post-list-facebook-button-li { display: none; } ' );
					$( '#shapeshifter-theme-customize-preview' ).append( '.shapeshifter-post-list-facebook-icon-li { display: none; } ' );
					//$( '.shapeshifter-post-list-facebook-button-li' ).css({ 'display': 'none' });
					//$( '.shapeshifter-post-list-facebook-icon-li' ).css({ 'display': 'none' });
				}
			});
		});
		wp.customize( 'archive_page_read_later_googleplus', function( value ) {
			value.bind( function( newval ) {
				window.sseThemeMods.archive_page_read_later_googleplus = newval;
				if( newval ) {
						$( '#shapeshifter-theme-customize-preview' ).append( '.shapeshifter-post-list-googleplus-button-li { display: block; } ' );
						$( '#shapeshifter-theme-customize-preview' ).append( '.shapeshifter-post-list-googleplus-icon-li { display: block; } ' );
					//$( '.shapeshifter-post-list-googleplus-button-li' ).css({ 'display': 'block' });
					//$( '.shapeshifter-post-list-googleplus-icon-li' ).css({ 'display': 'block' });
				} else {
					$( '#shapeshifter-theme-customize-preview' ).append( '.shapeshifter-post-list-googleplus-button-li { display: none; } ' );
					$( '#shapeshifter-theme-customize-preview' ).append( '.shapeshifter-post-list-googleplus-icon-li { display: none; } ' );
					//$( '.shapeshifter-post-list-googleplus-button-li' ).css({ 'display': 'none' });
					//$( '.shapeshifter-post-list-googleplus-icon-li' ).css({ 'display': 'none' });
				}
			});
		});
		wp.customize( 'archive_page_read_later_hatena', function( value ) {
			value.bind( function( newval ) {
				window.sseThemeMods.archive_page_read_later_hatena = newval;
				if( newval ) {
					$( '#shapeshifter-theme-customize-preview' ).append( '.shapeshifter-post-list-hatena-button-li { display: block; } ' );
					$( '#shapeshifter-theme-customize-preview' ).append( '.shapeshifter-post-list-hatena-icon-li { display: block; } ' );
					//$( '.shapeshifter-post-list-hatena-button-li' ).css({ 'display': 'block' });
					//$( '.shapeshifter-post-list-hatena-icon-li' ).css({ 'display': 'block' });
				} else {
					$( '#shapeshifter-theme-customize-preview' ).append( '.shapeshifter-post-list-hatena-button-li { display: none; } ' );
					$( '#shapeshifter-theme-customize-preview' ).append( '.shapeshifter-post-list-hatena-icon-li { display: none; } ' );
					//$( '.shapeshifter-post-list-hatena-button-li' ).css({ 'display': 'none' });
					//$( '.shapeshifter-post-list-hatena-icon-li' ).css({ 'display': 'none' });
				}
			});
		});
		wp.customize( 'archive_page_read_later_pocket', function( value ) {
			value.bind( function( newval ) {
				window.sseThemeMods.archive_page_read_later_pocket = newval;
				if( newval ) {
					$( '#shapeshifter-theme-customize-preview' ).append( '.shapeshifter-post-list-pocket-button-li { display: block; } ' );
					$( '#shapeshifter-theme-customize-preview' ).append( '.shapeshifter-post-list-pocket-icon-li { display: block; } ' );
					//$( '.shapeshifter-post-list-pocket-button-li' ).css({ 'display': 'block' });
					//$( '.shapeshifter-post-list-pocket-icon-li' ).css({ 'display': 'block' });
				} else {
					$( '#shapeshifter-theme-customize-preview' ).append( '.shapeshifter-post-list-pocket-button-li { display: none; } ' );
					$( '#shapeshifter-theme-customize-preview' ).append( '.shapeshifter-post-list-pocket-icon-li { display: none; } ' );
					//$( '.shapeshifter-post-list-pocket-button-li' ).css({ 'display': 'none' });
					//$( '.shapeshifter-post-list-pocket-icon-li' ).css({ 'display': 'none' });
				}
			});
		});
		wp.customize( 'archive_page_read_later_line', function( value ) {
			value.bind( function( newval ) {
				window.sseThemeMods.archive_page_read_later_line = newval;
				if( newval ) {
					$( '.shapeshifter-post-list-line-button-li' ).css({ 'display': 'block' });
					$( '.shapeshifter-post-list-line-icon-li' ).css({ 'display': 'block' });
				} else {
					$( '.shapeshifter-post-list-line-button-li' ).css({ 'display': 'none' });
					$( '.shapeshifter-post-list-line-icon-li' ).css({ 'display': 'none' });
				}
			});
		});
}) ( jQuery ); 