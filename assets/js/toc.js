( function( $ ) {
	// TOC機能
	if( $( '.toc' ).exists() ) {

		$( function() {

			var idcount = 1;
			var toc = '';
			var currentlevel = 0;
			var id

			$( ".entry-content :header", this ).each( function() {

				id = "toc_" + idcount;
				$( this ).wrapInner( '<span id="' + id + '"></span>' );
				idcount++; 
				var level = 0;

				if( this.nodeName.toLowerCase() == "h2" ) {
					level = 1;
				} else if( this.nodeName.toLowerCase() == "h3" ) {
					level = 2;
				} else if( this.nodeName.toLowerCase() == "h4" ) {
					level = 3;
				} else if( this.nodeName.toLowerCase() == "h5" ) {
					level = 4;
				} else if( this.nodeName.toLowerCase() == "h6" ) {
					level = 5;
				}

				while( currentlevel < level ) {
					toc += '<li><ul class="toc-sub-menu">';
					currentlevel++;
				}
				while( currentlevel > level ) {
					toc += '</ul></li>';
					currentlevel--;
				}
				toc += '<li><a href="#' + id + '">' + $( '#'+id ).text() + "<\/a><\/li>\n";
			});

			if( toc ) { 
				toc = '<div class="mokuji_wrap"><div class="mokuji">Contents</div><ul class="toc-menu">' + toc + '</ul></div>'; 
			} else { 
				$( '.widget-toc' ).remove(); 
			}

			$( ".toc" ).html( toc );
			//ページ内リンク#非表示。加速スクロール
			$( 'a[href^=#]' ).click( function( e ) {
				e.preventDefault();
				var speed = 1000,
				href= $(this).attr("href"),
				target = $(href == "#" || href == "" ? 'html' : href),
				position = target.offset().top;

			$( "html, body" ).animate({ scrollTop:position }, speed, "swing" );
				return false;
			});
		});

	}

}) ( jQuery );