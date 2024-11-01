( function( $ ) {

    if( window.location.href.indexOf( sseDirURLForJS.adminUploadURL ) === -1 )
        return;

	var pixabayImageFetcher = {

		currentPage: 1,
		isHDImage: false,
		imageDataIndex: 0,

		init: function() {

			// Save API Key
				$( ".save-pixabay-api-key" ).on( "click", function( e ) {

					$( ".save-pixabay-api-key" ).addClass( 'disabled' );
					var apiKey = $( '#pixabay-api-key' ).val();
					var ssNonce = $( "#pixabay-media-fetcher-nonce" ).val();


					$.ajax({
						url: ajaxurl,
						method: "POST",
						dataType: "json",
						data: {
							ssNonce: ssNonce,
							apiKey: apiKey,
							action: "shapeshifter_save_pixabay_save_api_key"
						},
						success: function( data, textStatus, jqHXR ) {
							$( "#pixabay-api-key-result" ).html( data.message );
							setTimeout(
								function() {
									$( "#pixabay-api-key-result" ).empty();
									$( ".save-pixabay-api-key" ).removeClass( 'disabled' );
								},
								5000
							);
						}
					});
				});

			// Options
				// Open Popup
					$( "#pixabay-search-media-table .pixabay-images-search-options" ).on( "click", function( e ) {
						$( 'body.wp-admin' ).css({
							'overflow': 'hidden'
						});
						$( '.pixabay-media-fetcher-options-background' ).css({
							'display': 'block'
						});
						$( '.pixabay-media-fetcher-search-options-wrapper' ).css({
							'display': 'block'
						});
					});

				// Close Popup
					$( ".close-pixabay-media-fetcher-options-popup, .pixabay-media-fetcher-options-background" ).on( "click", function( e ) {
						$( 'body.wp-admin' ).css({
							'overflow': 'visible'
						});
						$( '.pixabay-media-fetcher-options-background' ).css({
							'display': 'none'
						});
						$( '.pixabay-media-fetcher-search-options-wrapper, .pixabay-media-fetcher-results' ).css({
							'display': 'none'
						});
						$( ".pixabay-media-control-preview-select .pixabay-media-control-preview-select-image" ).attr( 'src', '' );
						$( '.pixabay-media-fetcher-media-preview' ).css({
							'display': 'none'
						});
					});

			// Search Button
				$( "#pixabay-search-media-table .search-pixabay-images" ).on( "click", function( e ) {

					e.preventDefault();
					$( 'body.wp-admin' ).css({
						'overflow': 'hidden'
					});

					// Page
						pixabayImageFetcher.currentPage = 1;

					// Image Get
						pixabayImageFetcher.getImageData( e );

				});

			// Change Media Type
				$( ".pixabay-media-fetcher-type" ).on( "change", function( e ) {
					$this = $( this );
					var $inputImageType = $this.parent().find( '.pixabay-media-fetcher-option[name=image_type]' );
					var $inputVideoType = $this.parent().find( '.pixabay-media-fetcher-option[name=video_type]' );
					if( $this.val() === "movie" ) {
						$inputImageType.hide();	
						$inputVideoType.show();
					} else {
						$inputImageType.show();
						$inputVideoType.hide();
					}
				});
				pixabayImageFetcher.changeMediaTypeAction();

			// Select Images
				/*$( "#pixabay-media-views > li a.pixabay-images-results" ).on( "click", function( e ) {

					$listItem = $( this ).closest( '.pixabay-media' );

					console.log( $listItem );

					// Selected
						if( $listItem.hasClass( "selected" ) ) {
							$listItem.removeClass( "selected" );
						} else {
							$listItem.addClass( "selected" );
						}

					// Details
						$( ".pixabay-result-image-wrapper li" ).removeClass( "details" );
						$( listItem ).addClass( "details" );

				});*/

			// Import Image
				$( ".pixabay-media-control-buttons .import-selected-images" ).on( "click", function( e ) {
					$( e.target ).addClass( "disabled" );
					pixabayImageFetcher.importImages( e );
				} );

		},

		getImageData: function( e ) {

			// Vars
				// API URL
					// Image
						var apiURLBase = 'https://pixabay.com/api/'; // Image
					// Video
						var isSearchingMovie = $( '.pixabay-media-fetcher-type' ).val() === 'movie';
						if( isSearchingMovie ) {
							apiURLBase += 'videos/'; // Video
						}

				// Search
					var searchType = $( '.pixabay-media-fetcher-type' ).val();
				// API Key
					var apiKey = $( '#pixabay-api-key' ).val();
				// Keywords
					var keywords = $( '#pixabay-search-keywords' ).val();

				// Options
					// Required
						var options = {
							"key": apiKey,
							"page": pixabayImageFetcher.currentPage,
							"q": keywords
						};

					// Language
						options['lang'] = $( e.target ).closest( 'html' ).attr( 'lang' );

					// Category
						var imageCategory = $( '.pixabay-media-fetcher-option-category' ).val();
						if( imageCategory !== 'all' ) {
							options['category'] = imageCategory;
						}

					// High Resolution
						if( $( '.pixabay-media-fetcher-search-options-wrapper .pixabay-media-fetcher-option-high-resolution' ).is( ':checked' ) ) {
							options['response_group'] = $( '.pixabay-media-fetcher-option-high-resolution' ).val();
						}
						
					// Other Options
						var $inputOptions = $( '.pixabay-media-fetcher-option' );
						$.each( $inputOptions, function( index, inputOption ) {

							$inputOption = $( inputOption );
							optionKey = $inputOption.attr( 'name' );
							var val = $inputOption.val();

							if( _.isEmpty( val ) ) return;
							if( val == "all" ) return;

							if( $inputOption.attr( 'type' ) === 'checkbox' ) {
								if( $inputOption.prop( 'checked' ) ) {
									options[optionKey] = "true";
								}
							} else {
								options[optionKey] = val;
							}
						});

			// Check
				//console.log( options );

			// AJAX
				$.ajax({
					url: apiURLBase,
					method: "GET",
					dataType: "json",
					data: options,
					success: function( data, textStatus, jqHXR ) {

						//console.log( options );
						//console.log( data );

						if( isSearchingMovie ) {

							var pageTotal = parseInt( data.total / options.per_page )+ ( data.total % options.per_page > 0 ? 1 : 0 );

						} else {

							var pageTotal = parseInt( data.totalHits / options.per_page )+ ( data.totalHits % options.per_page > 0 ? 1 : 0 );
							//console.log( pageTotal );

							if ( parseInt( data.totalHits ) > 0 ) {

								// Popup
									$( '.pixabay-media-fetcher-options-background' ).css({
										'display': 'block'
									});
									$( '.pixabay-media-fetcher-results' ).css({
										'display': 'block'
									});

								// Set HTML
									// Image List
										var template = _.template( $( '#wp-theme-shapeshifter-extensions-pixabay-media-fetcher-results' ).html().trim() );
										var insertedHTML = template({
											data: data,
											options: options
										});
										$( '#pixabay-media-fetcher-wrapper .pixabay-media-fetcher-results .pixabay-result-image-wrapper > ul' ).html( insertedHTML );

									// Pagination
										var paginationTemplate = _.template( $( "#wp-theme-shapeshifter-extensions-pixabay-popup-pagination" ).html() );
										var insertedPagination = paginationTemplate({
											currentPage: options.page,
											pageTotal: pageTotal
										});
										$( '#pixabay-media-fetcher-wrapper #pixabay-media-fetcher-pagination-select' ).html( insertedPagination );

								// Event
									// Image Select
										pixabayImageFetcher.resetClickEventForImageList( data, options );

									// Next Page
										$( "#pixabay-media-views .load-next" ).on( "click", function( e ) {
											pixabayImageFetcher.nextPage( e );
										});

									// Popup Preview
										pixabayImageFetcher.setEventPreviewImage( data, options );

									// Paginaiton
										$( "#pixabay-media-fetcher-pagination-select" ).on( "change", function( e ) {
											$this = $( this );
											pixabayImageFetcher.goToPage( e, parseInt( $this.val() ) );
										});



							}
							else {

								console.log('No hits');

							}

						}

					}

				});

		},

		resetClickEventForImageList: function( data, options ) {

			// Select Images
				$( "#pixabay-media-views > li .thumbnail, #pixabay-media-views > li .button-link" ).on( "click", function( e ) {
					pixabayImageFetcher.setEventSelectImage( e );
				});

		},

		setEventSelectImage: function( e ) {

			// Vars
				var $listItem = $( e.target ).closest( ".pixabay-media" );
				var $imgTag = $listItem.find( ".thumbnail" );
				var imgData = $imgTag[ 0 ].dataset;

			// Buttons
				$( ".pixabay-media-control .preview-selected-image" ).removeClass( "disabled" );

			// Selected
				if( $listItem.hasClass( "selected" ) ) {
					$listItem.removeClass( "selected" );
					$listItem.removeClass( "save-ready" );
				} else {
					$listItem.addClass( "selected" );
					$listItem.addClass( "save-ready" );
				}

			// Details
				$( "#pixabay-media-views > li" ).removeClass( "details" );
				$listItem.addClass( "details" );

			// Image Set
				$( ".pixabay-media-control .pixabay-media-control-preview-select" ).css({
					"background-image": "url(" + $imgTag.data( 'imagepreviewurl' ) + ")"
				});
				for( var key in imgData ) {
					//console.log( key );
					//console.log( imgData[key] );
					//console.log( $( ".pixabay-media-control .pixabay-media-control-preview-select" ) );
					$( ".pixabay-media-control .pixabay-media-control-preview-select" ).attr( "data-" + key, imgData[key] );
					//console.log( $( ".pixabay-media-control .pixabay-media-control-preview-select" ) );
				}

		},

		setEventPreviewImage: function( data, options ) {

			$( ".pixabay-media-control-buttons .preview-selected-image" ).on( "click", function( e ) {
				
				$this = $( this );

				// Vars
					var imageData = $this.closest( ".pixabay-media-control" ).find( ".pixabay-media-control-preview-select" )[0].dataset;
					// Is HD Image
						pixabayImageFetcher.isHDImage = ( typeof imageData.imagefullhdurl == "undefined" ? false : true );

					// Width
						var previewImageSrc = ( pixabayImageFetcher.isHDImage ? imageData.imagefullhdurl : imageData.imagewebformaturl );
					// Height
						var previewImageSrc = ( pixabayImageFetcher.isHDImage ? imageData.imagefullhdurl : imageData.imagewebformaturl );

					// Image URL
						var previewImageSrc = ( pixabayImageFetcher.isHDImage ? imageData.imagefullhdurl : imageData.imagewebformaturl );

				// Template
					var template = _.template( $( "#wp-theme-shapeshifter-extensions-pixabay-popup-popup-image-preview" ).html() );
					var insertedHTML = template({
						isHDImage: pixabayImageFetcher.isHDImage,
						imageData: imageData,
						previewImageSrc: previewImageSrc
					});

				// Insert
					$( ".pixabay-media-fetcher-media-preview" ).html( insertedHTML ).css({
						'display': 'block'
					});

				// Event
					// Close
						$( ".pixabay-image-preview-popup-controls .close-media-preview-popup" ).on( "click", function( e ) {
							$( ".pixabay-media-fetcher-media-preview" ).css({
								'display': 'none'
							}).empty();
						} );

			} );

		},

		importImages: function( e ) {

			// Vars
				// Nonce
					var ssNonce = $( "#pixabay-media-fetcher-nonce" ).val();

				// Images Wrapper
					var $imagesWrapper = $( e.target ).closest( '.pixabay-result-image-wrapper' );


			// ImagesData
				var formData = new FormData();
					formData.append( "ssNonce", ssNonce );
					formData.append( "action", 'shapeshifter_import_pixabay_images' );

				var imagesData = {};
				pixabayImageFetcher.imageDataIndex;
				$imagesWrapper.find( 'li.selected .thumbnail' ).each( function( index ) {

					$this = $( this );
					var imageURL = pixabayImageFetcher.isHDImage ? $this[0].dataset['imagefullhdurl'] : $this[0].dataset['imagewebformaturl'] ;
					var data = {};
					for( var key in $this[0].dataset ) {
						data[key] = $this[0].dataset[key];
					}
					imagesData[index] = data;

					pixabayImageFetcher.imageDataIndex++;

				});

				formData.append( "imagesData", JSON.stringify( imagesData ) );
				formData.append( "imageCount", pixabayImageFetcher.imageDataIndex );

			// AJAX
				console.log( "call ajax" );
				$.ajax({
					url: ajaxurl,
					method: "POST",
					dataType: "json",
					processData: false,
					contentType: false,
					data: formData,
					success: function( data, textStatus, jqHXR ) {
						console.log( data );
						$( ".pixabay-media-control-buttons .import-selected-images" ).removeClass( "disabled" );
						$( 'body.wp-admin' ).css({
							'overflow': 'visible'
						});
						$( '.pixabay-media-fetcher-options-background' ).css({
							'display': 'none'
						});
						$( '.pixabay-media-fetcher-search-options-wrapper, .pixabay-media-fetcher-results' ).css({
							'display': 'none'
						});
						$( ".pixabay-media-control-preview-select .pixabay-media-control-preview-select-image" ).attr( 'src', '' );
						$( '.pixabay-media-fetcher-media-preview' ).css({
							'display': 'none'
						});

						if( window.confirm( "Go to Media Library Page?" ) )
							window.open( shapeshifterPixabayMediaFetcherObject.adminURL + 'upload.php', '_blank' );
						//wp.media.open();
						//alert( 'done' );
					}

				});


		},

		nextPage: function( e ) {

			pixabayImageFetcher.currentPage++;
			pixabayImageFetcher.getImageData( e );

		},

		goToPage( e, pageNum ) {

			pixabayImageFetcher.currentPage = pageNum;
			pixabayImageFetcher.getImageData( e );

		},

		changeMediaTypeAction: function() {

		},

		render: function() {

		}


	};




	$( document ).on( "ready", function() {

		// Init
			pixabayImageFetcher.init();

	} );

}) ( jQuery );