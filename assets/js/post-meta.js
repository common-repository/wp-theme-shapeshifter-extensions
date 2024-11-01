( function( $ ) { $( document ).ready( function() {
// Slider Pro
	// 投稿メタ
		// 新着投稿一覧・人気投稿一覧
			$( '.shapeshifter-post-meta-slider-new-posts-wrapper,.shapeshifter-post-meta-slider-popular-posts-wrapper' ).each( function( index ) {

				$this = $( this );

				wrapperWidth = $this.parent().parent().width();
				windowWidth = $( window ).width()

				var autoPlay = true;

				var aspectRatio = -1;

				var sliderWidth = 300 > wrapperWidth ? wrapperWidth : 300;
				var sliderHeight = 400;

				// スライドのスタイルの事前セット
					$this.find( '.sp-slide' ).css({
						'width': sliderWidth + 'px',
						//'height': sliderHeight + 'px'
					});
					$this.find( '.sp-slide' ).each( function() {
						slideOuterHeight = $( this ).outerHeight();
						sliderHeight = sliderHeight < slideOuterHeight ? slideOuterHeight : sliderHeight;
					});
					$this.find( '.sp-slide' ).css({
						'height': sliderHeight + 'px'
					});

				var startSlide = 0;

				var thumbnailWidth = 100;
				var thumbnailHeight = 75;

				var arrows = false;
				var buttons = false;

				var fullScreen = false;

				var thumbnailsPosition = "bottom";

				if( isAdminBarShowing() ) {
					autoPlay = false;
				}

				// サイズごとの設定
					if( wrapperWidth <= 320 ) {

						visibleSize = wrapperWidth;
						forceSize = visibleSize;

					} else if( wrapperWidth <= 640 ) {

						visibleSize = wrapperWidth;
						forceSize = visibleSize;

					} else if( wrapperWidth <= 900 ) {

						startSlide = 1;

						visibleSize = wrapperWidth;
						forceSize = visibleSize;

					} else {

						startSlide = 1;

						visibleSize = 900;
						forceSize = visibleSize;

					}

				if( $( this ).data( 'slider-style-sidecontrols' ) == 'sidecontrols'  ) {
					arrows = true;
				}

				if( $( this ).data( 'slider-style-buttons' ) == 'buttons' ) {
					buttons = true;
				}

				properties = {

						autoplay: autoPlay,

					// Settings with Vars above

						aspectRatio: aspectRatio,

						width: sliderWidth,
						height: sliderHeight,

						startSlide: startSlide,

						visibleSize: visibleSize,
						//forceSize: forceSize,

						arrows: arrows,
						buttons: buttons,
						fullScreen: fullScreen,

						thumbnailWidth: thumbnailWidth,
						thumbnailHeight: thumbnailHeight,
						thumbnailsPosition: thumbnailsPosition,

					// List
						// General Settings
							responsive: false,
							imageScaleMode: "cover",
							centerImage: true,
							allowScaleUp: true,
							autoHeight: false,
							shuffle: false,
							orientation: "horizontal",
							loop: true,

							slideDistance: 10,
							slideAnimationDuration: 700,
							heightAnimationDuration: 700,

						// for Fade
							fade: "false",
							fadeOutPreviousSlide: false,
							fadeDuration: 500,

						// for Autos
							autoplayDelay: 5000,
							autoplayDirection: "normal",
							autoplayOnHover: "pause",

						// Arrows
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
							fadeFullScreen: false,

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
							thumbnailPointer: false,
							thumbnailArrows: false,
							fadeThumbnailArrows: false,
							thumbnailTouchSwipe: false,

				};
				
				$this.sliderPro( properties );
				
				$this.css({ 
					"margin": "auto",
					"visibility": "visible" 
				});

				thisWidth = $this.innerWidth();
				$this.parent().css({
					'max-width': thisWidth + 'px'
				});
				/*
				$untilWrapper = $( $this.parentsUntil( '.shapeshifter-widget-wrapper' ) );
				if( $untilWrapper.hasClass( 'vegas-container' ) ) {
					console.log( $untilWrapper.find( '.vegas-container' ) );
					var wrapperHeight = sliderHeight + $untilWrapper.find( '.shapeshifter-post-meta-item-title-for-optional-widget-area' ).outerHeight();
					setTimeout(
						function() {
							$untilWrapper.find( '.vegas-container' ).css({
								'height': wrapperHeight + 'px'
							});
						},
						3000
					);
					$untilWrapper.find( '.vegas-container' ).css({
						'height': wrapperHeight + 'px'
					});

				}
				*/

			});

}); }) ( jQuery );