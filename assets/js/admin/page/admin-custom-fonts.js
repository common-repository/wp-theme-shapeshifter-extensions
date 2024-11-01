( function( $ ) {

    if( window.location.href.indexOf( sseDirURLForJS.adminFontSettingsPageURL ) === -1 )
        return;

	var shapeshifterGoogleFontsMethods = {
		init: function() {

			// Setup
				shapeshifterGoogleFontsMethods.ajaxurl = ajaxurl;
				shapeshifterGoogleFontsMethods.googleFontsAPIURL = 'https://www.googleapis.com/webfonts/v1/webfonts';

			$( 'button#save-google-fonts-api-key' ).on( 'click', function( e ) {

				// Save the API Key
					// Set the API Key
						shapeshifterGoogleFontsMethods.apiKey = $( 'input#google-fonts-api-key' ).val();
					// AJAX Exec
						var saveAPIKeyAjax = shapeshifterGoogleFontsMethods.saveAPIKey( e );

				// Get the API and Set "shapeshifterGoogleFontsMethods.googleFontsJSONData"
					if( shapeshifterGoogleFontsMethods.apiKey == '' ) {

						$( 'table#google-fonts-settings-table tbody' ).empty();
						$( 'input[type="submit"]' ).removeClass( 'disabled' );
						return;

					} else {

						shapeshifterGoogleFontsMethods.getGoogleFontsAPIVar = shapeshifterGoogleFontsMethods.getGoogleFontsAPI( e );
						shapeshifterGoogleFontsMethods.googleFontsJSONData = shapeshifterGoogleFontsMethods.getGoogleFontsAPIVar['responseText'];

					}

			});

			$( 'button#save-applied-google-fonts' ).on( 'click', function( e ) {
				e.preventDefault();
				shapeshifterGoogleFontsMethods.saveAppliedFonts( e );
			});

		},
		saveAPIKey: function( e ) {

			$( 'input[type="submit"]' ).addClass( 'disabled' );

			// Save the Key
				return $.ajax({
					method: "POST",
					url: shapeshifterGoogleFontsMethods.ajaxurl,
					//context: e.target,
					//async: false,
					dataType: "html",
					data: {
						//"_wpnonce": wpnonce,
						"googleFontsAPIKey": shapeshifterGoogleFontsMethods.apiKey,
						action: "shapeshifter_save_google_fonts_api_key"
					},
					success: function( message, textStatus, jqHXR ) {
						//console.log( message );
					}
				});

		},
			getGoogleFontsAPI: function( e ) {

				return $.ajax({
					method: "GET",
					url: shapeshifterGoogleFontsMethods.googleFontsAPIURL,
					//context: e.target,
					//async: false,
					dataType: "json",
					data: {
						"key": shapeshifterGoogleFontsMethods.apiKey,
					},
					error: function( data, textStatus, jqHXR ) {
						alert( 'Please set your valid browser key' );
					},
					success: function( data, textStatus, jqHXR ) {
						// Print TBODY > TR
							var googleFontsData = shapeshifterGoogleFontsMethods.printGoogleFontsList( e, jqHXR['responseText'] );
					}
				});

			},
				printGoogleFontsList: function( e, data ) {

					//console.log( JSON.parse( data ) );

					return $.ajax({
						method: "POST",
						url: shapeshifterGoogleFontsMethods.ajaxurl,
						//context: e.target,
						dataType: "html",
						//async: true,
						data: {
							"ss_google_fonts_check_nonce": $( '#google-font-file-check-nonce' ).val(),
							"googleFontsData": data,
							action: "shapeshifter_print_google_fonts_list"
						},
						error: function( e ) {
							alert( e );
						},
						success: function( returnedHTML, textStatus, jqHXR ) {

							console.log( returnedHTML );

							$( 'table#google-fonts-settings-table tbody' ).html( returnedHTML );

							$( 'input[type="submit"]' ).removeClass( 'disabled' );

						}
					});

				},
		setAppliedFonts: function() {
			shapeshifterGoogleFontsMethods.appliedGoogleFonts = [];
			$( 'input.applied-font-input:checked' ).each( function( index ) {

				//console.log( $( this ).val() );

				category = $( this ).data( 'category' );
				subFontFamily = $( this ).data( 'font-family-in-css' );

				fontFamily = $( this ).data( 'font-family' );
				fontFamilyInURL = $( this ).data( 'font-family-in-url' );
				fontFamilyInCSS = $( this ).data( 'font-family-in-css' );
				fontNameDisplay = $( this ).data( 'font-name-display' );

				shapeshifterGoogleFontsMethods.appliedGoogleFonts.push({
					"font-name-display": fontNameDisplay,
					"font-family": fontFamily,
					"font-family-in-url": fontFamilyInURL,
					"font-family-in-css": fontFamilyInCSS,
					"category": category,
					"sub-font-family": subFontFamily
				});
			});
			//console.log( shapeshifterGoogleFontsMethods.appliedGoogleFonts );
		},
		saveAppliedFonts: function( e ) {

			$( 'input[type="submit"]' ).addClass( 'disabled' );

			// Set Applied Fonts
				shapeshifterGoogleFontsMethods.setAppliedFonts();

			$.ajax({
				method: "POST",
				url: shapeshifterGoogleFontsMethods.ajaxurl,
				//context: e,
				dataType: "json",
				data: {
					//"_wpnonce": wpnonce,
					"appliedGoogleFonts": shapeshifterGoogleFontsMethods.appliedGoogleFonts,
					action: "shapeshifter_save_applied_google_fonts_list"
				}
			}).done( function( returnedHTML ) {
				$( 'input[type="submit"]' ).removeClass( 'disabled' );
			});

		}
	};

	$( document ).ready( function() {
		// Google Fonts
			shapeshifterGoogleFontsMethods.init();
	});

}) ( jQuery );