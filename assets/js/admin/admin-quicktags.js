( function( $ ) {

    //if( window.location.href.indexOf( sseDirURLForJS.adminEditURL ) === -1 )
        //return;

    var require = function( script, data ) {
        jQuery.ajax({
            url: script,
            dataType: "script",
            //async: false,
            success: function () {
            },
            error: function () {
                //require.call( this, script );
                throw new Error( "Could not load script " + script + ". Now reloading..." );
            }
        });
    };
	//require( shapeshifterExtensionsTMCEObject.pluginRootURI + 'assets/js/admin/admin-mce-buttons.js' );

	var shapeshifterButtonMethods = {

		init: function() {

			// Visual Editor Activate
				if( typeof shapeshifterButtonMethods.shapeshifterEditor == 'undefined' ) {

					// Init Visual Editor
						window.tinymce.init( window.tinyMCEPreInit.mceInit['content'] );

					if( shapeshifterButtonMethods.isHTMLActive() ) {

					}

				}

		},

		popup: function( inputTagHTML, contentent, QTags ) {

			if( typeof shapeshifterButtonMethods.shapeshifterEditor != 'undefined' ) {

				shapeshifterButtonMethods.shapeshifterEditor.execCommand( 
					'shapeshifter_items_popup', 
					false, 
					{ // Params
						editorMode: 'html',
						itemName : 'none',
						isColEditorOn: false
					}
				);

			}

		},

		isHTMLActive: function() {

			return $( '#wp-content-wrap.html-active' ).exists();

		}



	};

	$( document ).ready( function( e ) {

		if( typeof window.tinymce == "undefined" ) return;

		if ( window.tinyMCEPreInit.mceInit['content'] !== undefined ) {

			// Init Data
				window.tinyMCEPreInit.mceInit['content'].init_instance_callback = function( editor ) {

					if( editor.id === "content" ) {

						// Get Editor
							shapeshifterButtonMethods.shapeshifterEditor = window.shapeshifterEditor;
							if( shapeshifterButtonMethods.isHTMLActive() ) {
								window.switchEditors.go( 'content', 'html' );
							}

					}

				};

			// Init
				shapeshifterButtonMethods.init();

			// Add Button
				QTags.addButton(
					'shapeshifter-ss-button',
					'SS',
					shapeshifterButtonMethods.popup
				);

			// Events
				// Window Select
					$( window ).on( 'select', function( e ) {
						window.shapeshifterCapturedText = window.getSelection().toString();
					});

		}

	});


}) ( jQuery );