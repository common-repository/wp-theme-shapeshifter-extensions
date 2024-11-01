( function( $ ) {
	$( document ).ready( function() {
		// Slider Pro
			// ウィジェット
				$( '.shapeshifter-slider-wrapper' ).each( function( index ) {

					wrapperWidth = $( this ).parent().width();
					// For Debugging ( false )
						var autoPlay = true;

					var aspectRatio = -1;

					var sliderWidth = wrapperWidth;
					var sliderHeight = wrapperWidth * 0.75;

					var thumbnailWidth = 100;
					var thumbnailHeight = 75;

					var buttons = false;

					var fullScreen = false;

					var thumbnailsPosition = "bottom";

					if( wrapperWidth <= 320 ) {

						$( this )/*.find( '.sp-slide-post p, .sp-slide-page p' )*/.css({
							'font-size': '50%'
						});

						$( this ).find( '.sp-thumbnail > canvas' ).css({
							'width': thumbnailWidth + 'px',
							'height': thumbnailHeight + 'px'
						});

						$( this ).find( '.sp-thumbnail-text' ).css({
							'display': 'none'
						});

					} else if( wrapperWidth < 600 ) {

						$( this )/*.find( '.sp-slide-post p, .sp-slide-page p' )*/.css({
							'font-size': '75%'
						});

						thumbnailWidth = 120;
						thumbnailHeight = 90;

						$( this ).find( '.sp-thumbnail > canvas' ).css({
							'width': thumbnailWidth + 'px',
							'height': thumbnailHeight + 'px'
						});

						$( this ).find( '.sp-thumbnail-text' ).css({
							'display': 'none'
						});

					} else if( wrapperWidth < 850 ) {

						$( this )/*.find( '.sp-slide-post p, .sp-slide-page p' )*/.css({
							'font-size': '75%'
						});

						sliderWidth = wrapperWidth - 287;
						sliderHeight = ( wrapperWidth - 287 ) * 0.75;

						thumbnailWidth = 300;
						thumbnailHeight = 100;
						thumbnailsPosition = "right";

						$( this ).find( '.sp-image' ).css({
							'width': sliderWidth - thumbnailWidth
						});

						$( this ).find( '.sp-thumbnail > canvas + div.sp-layer' ).css({
							'left': '130px',
							'width': '150px',
							'height': '100px'
						});

					} else {

						sliderWidth = wrapperWidth - 287;
						sliderHeight = ( wrapperWidth - 287 ) * 0.75;

						thumbnailWidth = 300;
						thumbnailHeight = 100;
						thumbnailsPosition = "right";

						$( this ).find( '.sp-image' ).css({
							'width': sliderWidth - thumbnailWidth
						});

						$( this ).find( '.sp-thumbnail > canvas + div.sp-layer' ).css({
							'left': '130px',
							'width': '150px',
							'height': '100px'
						});

					}

						$( this ).find( '.sp-slide canvas.sp-image' ).css({
							//'width': sliderWidth + 'px',
							//'height': sliderHeight + 'px',
							//'background-size': sliderWidth + 'px ' + sliderHeight + 'px'
						});

						$videoImg = $( this ).find( ".sp-video > img" );
						if( $videoImg.length > 0 ) {
							$videoImg.attr( "width", sliderWidth + 'px' );
							$videoImg.attr( "height", sliderHeight + 'px' );
						}

						if( $( '#wpadminbar' ).exists() ) {
							autoPlay = false;
						}

						properties = {
								autoplay: autoPlay,

							// Settings with Vars above

								aspectRatio: aspectRatio,

								width: sliderWidth,
								height: sliderHeight,

								buttons: buttons,
								fullScreen: fullScreen,

								thumbnailWidth: thumbnailWidth,
								thumbnailHeight: thumbnailHeight,
								thumbnailsPosition: thumbnailsPosition,

							// List
								// General Settings
									responsive: true,
									imageScaleMode: "cover",
									centerImage: true,
									allowScaleUp: true,
									autoHeight: false,
									startSlide: 0,
									shuffle: false,
									orientation: "horizontal",
									forceSize: "none",
									loop: true,

									slideDistance: 10,
									slideAnimationDuration: 700,
									heightAnimationDuration: 700,
									visibleSize: "auto",

								// for Fade
									fade: "false",
									fadeOutPreviousSlide: true,
									fadeDuration: 500,

								// for Autos
									autoplayDelay: 5000,
									autoplayDirection: "normal",
									autoplayOnHover: "pause",

								// Arrows
									arrows: false,
									fadeArrows: true,

								// Buttons
									// Above

								// Keyboard
									keyboard: true,
									keyboardOnlyOnFocus: false,

								// Touch Swipes
									touchSwipe: true,
									touchSwipeThreshold: 50,

								// Captions
									fadeCaption: true,
									captionFadeDuration: 500,

								// Full Screen
									fadeFullScreen: true,

								// Layers and Scales
									waitForLayers: false,
									autoScaleLayers: true,
									autoScaleReference: -1,

								// Size
									smallSize: 480,
									mediumSize: 768,
									largeSize: 1024,

								// Hash
									updateHash: false,

								// Video Action
									reachVideoAction: "none",
									leaveVideoAction: "pauseVideo",
									playVideoAction: "stopAutoplay",
									pauseVideoAction: "none",
									endVideoAction: "none",									

								// Thumbnail
									thumbnailPointer: true,
									thumbnailArrows: true,
									fadeThumbnailArrows: true,
									thumbnailTouchSwipe: true,

								// Break Points
									/*breakpoints: { // default: null
										800: {
											thumbnailsPosition: "bottom",
											thumbnailWidth: 270,
											thumbnailHeight: 100
										},
										500: {
											orientation: "vertical",
											thumbnailsPosition: "bottom",
											thumbnailWidth: 120,
											thumbnailHeight: 50
										}
									},*/

						};
					
					$( this ).sliderPro( properties );
					
					$( this ).css({ "visibility": "visible" });

				});
	});
} ) ( jQuery );