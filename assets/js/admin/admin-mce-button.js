var shapeshifterCapturedText = '';

( function( window, jQuery, _ ) {

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

    var tinymce = window.tinymce;

    jQuery.fn.isChildOf = function( element ) {
        return jQuery( element ).has( this ).length > 0;
    }

    tinymce.PluginManager.add( 'shapeshifter_button', function( editor, url ) {

        var $ = jQuery;

        // Popup Window Manage ( want to close all window at once )
            var popupWindowHolder = {

                popupWindows: [],
                set: function( popupWindow ) {
                    this.popupWindows.push( popupWindow )
                },
                closeAll: function() {
                    _( this.popupWindows ).each( function( popupWindow ) {
                        popupWindow.close();
                    })
                },
                getItemSettingsFormByItem: function( itemType, JSON ) {

                }

            };

        // Settings Form Object
            var popupSettingsForms = {
                column: {
                    items: {
                        type: 'container',
                        minWidth: 150,
                        minHeight: 300,
                        html: document.getElementById( 'wp-theme-shapeshifter-extensions-tinymce-button-side-nav-menu' ).innerHTML,
                        onmouseover: function( e ) {

                            if( e.target.className !== 'shapeshifter-tinymce-button-section-tabs' ) {

                                var tab = $( e.target ).data( 'display' );
                                $( 'div[role=application] .shapeshifter-tinymce-button-section' ).css({
                                    'display': 'none'
                                });
                                $( 'div[role=application] #shapeshifter-tinymce-button-' + tab ).css({
                                    'display': 'block'
                                });

                            }

                        }
                    }

                },
                row: {

                },
                slider: {

                },

            };

        // Add button
            editor.addButton( 'shapeshifter_button', {

                //icon: 'shapeshifter_button',
                tooltip: 'ShapeShifer Button',
                onclick: function( e ) {

                    arrayUnitlWrapper = $( e.target ).parentsUntil( 'div.shapeshifter-col-content-editor-form-wrapper' );

                    isColEditorOn = $( arrayUnitlWrapper[ ( arrayUnitlWrapper.length - 1 ) ] ).parent().hasClass( 'shapeshifter-col-content-editor-form-wrapper' );

                    editor.execCommand( 
                        'shapeshifter_items_popup', 
                        e, 
                        { // Params
                            itemName : 'none',
                            isColEditorOn: isColEditorOn
                        }
                    );

                }

            });

                // add Commands
                    // MCE Button
                        editor.addCommand( 'shapeshifter_items_popup', function( e, params ) {

                            editor.prevEditorId = editor.id;

                            if( _.isEmpty( params.editorMode ) ) {
                                editor.editorMode = "tmce";
                            } else {
                                editor.editorMode = params.editorMode;
                            }

                            if( _.isEmpty( params.itemName ) )
                                params.itemName = 'none';

                            // Side Menu
                                var sideNavMenuTabs = {
                                    type: 'container',
                                    minWidth: 150,
                                    minHeight: 300,
                                    html: document.getElementById( 'wp-theme-shapeshifter-extensions-tinymce-button-side-nav-menu' ).innerHTML,
                                    onmouseover: function( e ) {

                                        if( e.target.className !== 'shapeshifter-tinymce-button-section-tabs' ) {

                                            var tab = $( e.target ).data( 'display' );
                                            $( 'div[role=application] .shapeshifter-tinymce-button-section' ).css({
                                                'display': 'none'
                                            });
                                            $( 'div[role=application] #shapeshifter-tinymce-button-' + tab ).css({
                                                'display': 'block'
                                            });

                                        }

                                    }
                                };

                            // Main Menu
                                var selectionSections = {
                                    type: 'container',
                                    minWidth: 400,
                                    minHeight: 300,
                                    overflow: "scroll",
                                    html: document.getElementById( 'wp-theme-shapeshifter-extensions-tinymce-button-main-selections' ).innerHTML,
                                    onclick: function( e ) {

                                        //console.log( e.target );

                                        itemType = $( e.target ).data( 'item-type' );

                                        //console.log( itemType );

                                        editor.execCommand(
                                            'shapeshifter_item_settings_popup',
                                            e,
                                            {
                                                $target: $( e.target ),
                                                itemType: itemType,
                                            }
                                        );

                                    }
                                }

                            // Open the Popup
                                var win = editor.windowManager.open( {
                                    title: 'Inserts',
                                    modal: true,
                                    items: {
                                        type: 'container',
                                        layout: 'flex',
                                        direction: 'row',
                                        align: 'stretch',
                                        padding: 0,
                                        spacing: 0,
                                        items: [
                                            sideNavMenuTabs, // Side Menu
                                            selectionSections // Main Menu
                                        ]
                                    },
                                    buttons: [
                                        {
                                            text: "Close",
                                            onclick: function() {
                                                win.close();
                                            }
                                        }
                                    ]
                                });
                                popupWindowHolder.set( win );
                                if( params.isColEditorOn ) {
                                    $( '.shapeshifter-tinymce-button-section-tabs' ).find( 'li[data-display="rows"]' ).css({
                                        'display': 'none'
                                    });
                                    $( '#shapeshifter-tinymce-button-rows.shapeshifter-tinymce-button-section' ).css({
                                        'display': 'none'
                                    });
                                }

                        });

                    // Item Settings Form
                        editor.addCommand( 'shapeshifter_item_settings_popup', function( e, params ) {

                            //console.log( e );
                            //console.log( params );

                            //var data = {};
                                var settingsFormWin;
                                var nextPopup = false;
                                var initTMCE = false;

                            // Check Item Type
                                if( params.itemType == 'row' ) {

                                    editor.execCommand( 
                                        'shapeshifter_insert_row',
                                        e,
                                        params
                                    );

                                } else if( params.itemType == 'map' ) {

                                    var nextPopup = true;
                                    var popupData = editor.shapeshifterGetAJAXParamsForMap( params );

                                } else if( params.itemType == 'table' ) {

                                    var nextPopup = true;
                                    var popupData = editor.shapeshifterGetAJAXParamsForTable( params );

                                } else if( params.itemType == 'slider' ) {

                                    var nextPopup = true;
                                    var popupData = editor.shapeshifterGetAJAXParamsForSlider( params );

                                } else if( params.itemType == 'link' ) {

                                    var nextPopup = true;
                                    var popupData = editor.shapeshifterGetAJAXParamsForLink( params );

                                } else if( params.itemType == 'balloon' ) {

                                    var nextPopup = true;
                                    var popupData = editor.shapeshifterGetAJAXParamsForBalloon( params );
                                    initTMCE = {
                                        editorId: "shapeshifterballoondialog"
                                    };

                                } else if( params.itemType == 'shortcode' ) {

                                    var nextPopup = true;
                                    var popupData = editor.shapeshifterGetAJAXParamsForShortcode( params );

                                } else if( params.itemType == 'html' ) {

                                    // HTML Type
                                        htmlType = params.$target.data( 'html-type' );

                                    // Popup Data
                                        nextPopup = true;
                                        popupData = {
                                            title: 'HTML',
                                            modal: true,
                                            items: {
                                                type: 'container',
                                                layout: 'flex',
                                                direction: 'column',
                                                align: 'stretch',
                                                padding: 0,
                                                spacing: 0,
                                                minWidth: 600,
                                                minHeight: 200,
                                                html: '<textarea name="enter-inline-html" style="width: 580px; height: 100%; padding: 10px; white-space: normal;">' + ( shapeshifterCapturedText != '' ? shapeshifterCapturedText : '' ) + '</textarea>'
                                            },
                                            buttons: [
                                                {
                                                    text: "Insert",
                                                    class: "primary",
                                                    onclick: function( e ) {

                                                        textareaContents = decodeURIComponent( $( 'textarea[name="enter-inline-html"]' ).val() );

                                                        console.log( editor.editorMode );

                                                        if( editor.editorMode == "tmce" ) {
                                                            editor.insertContent( textareaContents );
                                                        } else if( editor.editorMode == "html" ) {
                                                            QTags.insertContent( textareaContents );
                                                        }

                                                        popupWindowHolder.closeAll();
                                                    }
                                                },
                                            ]
                                        }

                                } else {

                                }

                            // Open the Popup
                                if( nextPopup ) {

                                    popupData.buttons.push({
                                        text: "Close",
                                        onclick: function() {
                                            settingsFormWin.close();
                                            //popupData.close();
                                        }
                                    });

                                    settingsFormWin = editor.windowManager.open( popupData );
                                    popupWindowHolder.set( settingsFormWin );

                                } else {

                                    console.log( editor.insertContents );
                                    if( ! _.isEmpty( editor.insertContents ) ) {

                                        if( editor.editorMode == 'tmce' ) {
                                            editor.insertContent( editor.insertContents );
                                        } else if( editor.editorMode == "html" ) {
                                            QTags.insertContent( editor.insertContents );
                                        }

                                    }
                                    popupWindowHolder.closeAll();

                                }

                            // Init Editor
                                if( initTMCE !== false ) {
                                    editor.execCommand( 
                                        'shapeshifter_set_tinymce_on_popup',
                                        e, 
                                        {
                                            editorId: initTMCE.editorId
                                        }
                                    );
                                }

                        });
                            // Map
                                editor.shapeshifterGetAJAXParamsForMap = function( params ) {

                                    // Map Type
                                        mapType = params.$target.data( 'map-type' );

                                    popupData = {
                                        title: 'Google Map',
                                        modal: true,
                                        items: {
                                            type: 'container',
                                            layout: 'flex',
                                            direction: 'row',
                                            align: 'stretch',
                                            padding: 0,
                                            spacing: 0,
                                            minWidth: 600,
                                            minHeight: 100,
                                            html: '<p style="padding: 10px;"><label for="google-map-address">Map Address:</label><input id="google-map-address" name="google-map-address" style="margin: 5px; border: solid #000 1px;"></p>'
                                        },
                                        buttons: [
                                            {
                                                text: "Insert",
                                                class: "primary",
                                                onclick: function( e ) {
                                                    var googleMapContents = _.template( $( '#wp-theme-shapeshifter-extensions-tinymce-button-template-map' ).html() );

                                                    if( editor.editorMode == 'tmce' ) {
                                                        editor.insertContent( googleMapContents({
                                                            address: decodeURIComponent( $( 'input[name="google-map-address"]' ).val() )
                                                        }));
                                                    } else if( editor.editorMode == "html" ) {
                                                        QTags.insertContent( googleMapContents({
                                                            address: decodeURIComponent( $( 'input[name="google-map-address"]' ).val() )
                                                        }));
                                                    }

                                                    popupWindowHolder.closeAll();
                                                }
                                            }
                                        ]
                                    };

                                    return popupData;

                                };

                            // Table
                                editor.shapeshifterGetAJAXParamsForTable = function( params ) {

                                    // Table Type
                                        tableType = params.$target.data( 'table-type' );

                                    popupData = {
                                        title: 'Table',
                                        modal: true,
                                        items: {
                                            type: 'container',
                                            layout: 'flex',
                                            direction: 'row',
                                            align: 'stretch',
                                            padding: 0,
                                            spacing: 0,
                                            minWidth: 600,
                                            minHeight: 300,
                                            html: $( '#wp-theme-shapeshifter-extensions-tinymce-button-settings-table' ).html()
                                        },
                                        buttons: [
                                            {
                                                text: "Insert",
                                                class: "primary",
                                                onclick: function( e ) {
                                                    var tableContents = _.template( $( '#wp-theme-shapeshifter-extensions-tinymce-button-template-table' ).html() );

                                                    // Data
                                                        var tableFeature = $( '#shapeshifter-table-feature:checked' ).val();
                                                        var tableCaption = $( '#shapeshifter-table-caption' ).val();
                                                        var hasHeader = ( $( '#shapeshifter-table-has-header' ).prop( 'checked' )
                                                            ? true
                                                            : false
                                                        );
                                                        var hasFooter = (
                                                            $( '#shapeshifter-table-has-footer' ).prop( 'checked' )
                                                            ? true
                                                            : false
                                                        );
                                                        var colsNum = parseInt( $( '#shapeshifter-table-columns-number' ).val() );
                                                        var rowsNum = parseInt( $( '#shapeshifter-table-rows-number' ).val() );

                                                    if( editor.editorMode == 'tmce' ) {
                                                        editor.insertContent( tableContents({
                                                            tableFeature: tableFeature,
                                                            tableCaption: tableCaption,
                                                            hasHeader: hasHeader,
                                                            hasFooter: hasFooter,
                                                            colsNum: colsNum,
                                                            rowsNum: rowsNum
                                                        }));
                                                    } else if( editor.editorMode == "html" ) {
                                                        QTags.insertContent( tableContents({
                                                            tableFeature: tableFeature,
                                                            tableCaption: tableCaption,
                                                            hasHeader: hasHeader,
                                                            hasFooter: hasFooter,
                                                            colsNum: colsNum,
                                                            rowsNum: rowsNum
                                                        }));
                                                    }

                                                    popupWindowHolder.closeAll();
                                                }
                                            }
                                        ]
                                    };

                                    return popupData;

                                };

                            // Slider
                                editor.shapeshifterGetAJAXParamsForSlider = function( params ) {

                                    // Slider Type
                                        sliderType = params.$target.data( 'slider-type' );

                                    popupData = {
                                        title: 'Slider',
                                        modal: true,
                                        items: {
                                            type: 'container',
                                            layout: 'flex',
                                            direction: 'row',
                                            align: 'stretch',
                                            padding: 0,
                                            spacing: 0,
                                            minWidth: 600,
                                            minHeight: 300,
                                            items: [
                                                {
                                                    type: 'container',
                                                    align: 'stretch',
                                                    padding: 0,
                                                    spacing: 0,
                                                    minWidth: 300,
                                                    minHeight: 300,
                                                    html: $( '#wp-theme-shapeshifter-extensions-tinymce-button-settings-slider' ).html(),
                                                    onclick: function( e ) {

                                                        console.log( e.target );

                                                        if( $( e.target ).attr( 'id' ) == 'shapeshifter-set-images-button-on-tinymce' ) {

                                                            $this = $( e.target );

                                                            var $el = $this.parent();
                                                            e.preventDefault();
                                                            
                                                            var uploader = wp.media({
                                                                title: 'Select Images',
                                                                button: {
                                                                    text: 'Set Images'   
                                                                },
                                                                multiple: true
                                                            }).on( 'select', function() {
                                                                
                                                                var selection = uploader.state().get( 'selection' );
                                                                
                                                                // 「url」の配列を取得
                                                                var attachments = [];
                                                                selection.map( function( attachment ){
                                                                    attachment = attachment.toJSON();
                                                                    attachments.push( attachment.url );
                                                                } );
                                                                
                                                                // 「imageHolderSelector」内に各imgタグを出力
                                                                var imageHolderSelector = 'div#shapeshifter-image-holder';
                                                                $( imageHolderSelector ).children().remove();
                                                                attachments.forEach( function( element, index ) {
                                                                    $( imageHolderSelector ).append( '<img src="' + element + '" width="100" height="100" style="float:left;">' );
                                                                } );
                                                                
                                                                // データをStringで取得
                                                                $( 'input#shapeshifter-slider-images' ).val( attachments.join( ',' ) );
                                                            }).open();
                                                            
                                                        } else if( $( e.target ).attr( 'id' ) == 'shapeshifter-remove-images-button-on-tinymce' ) {

                                                            var imageHolderSelector = 'div#shapeshifter-image-holder';
                                                            $( imageHolderSelector ).children().remove();

                                                        } else if( $( e.target ).attr( 'id' ) == '' ) {

                                                        }

                                                    }
                                                },
                                                {
                                                    type: 'container',
                                                    align: 'stretch',
                                                    padding: 0,
                                                    spacing: 0,
                                                    minWidth: 300,
                                                    minHeight: 300,
                                                    html: $( '#wp-theme-shapeshifter-extensions-tinymce-button-settings-slider-image-holder' ).html()                                                    
                                                }
                                            ],
                                        },
                                        buttons: [
                                            {
                                                text: "Insert",
                                                class: "primary",
                                                onclick: function( e ) {

                                                    if( $( 'input[name="shapeshifter-slider-images"]' ).val() !== "" ) {
                                                        var images = $( 'input[name="shapeshifter-slider-images"]' ).val().split( /,\s*/i );
                                                    } else {
                                                        var images = [];
                                                    }

                                                    // Setup Data for Template

                                                        // Slides
                                                            var imagesData = [];
                                                            for( var index in images ) {
                                                                console.log( index );
                                                                imagesData.push({
                                                                    imageSrc: images[ index ],
                                                                });
                                                            }

                                                            var sliderFeature = $( 'input:checked[name="shapeshifter-image-slider-feature"]' ).val();

                                                            var sliderType = $( 'input:checked[name="shapeshifter-image-slider-type"]' ).val();

                                                            var sideControlArrows = $( 'shapeshifter-image-slider-side-control-arrows' ).prop( 'checked' );

                                                            var bottomButtons = $( 'shapeshifter-image-slider-bottom-buttons' ).prop( 'checked' );

                                                            var sliderContents = _.template(
                                                                $( '#wp-theme-shapeshifter-extensions-tinymce-button-template-slider' ).html() );

                                                            var slideWidth = $( '#shapeshifter-image-slider-slide-width' ).val();
                                                            var slideHeight = $( '#shapeshifter-image-slider-slide-height' ).val();

                                                        // Thumbnails
                                                            var thumbnailType = $( 'input:checked[name="shapeshifter-image-slider-thumbnail"]' ).val();
                                                            var thumbnailWidth = $( '#shapeshifter-image-slider-slide-thumbnail-width' ).val();
                                                            var thumbnailHeight = $( '#shapeshifter-image-slider-slide-thumbnail-height' ).val();

                                                    // Insert Template
                                                        if( editor.editorMode == 'tmce' ) {
                                                            editor.insertContent( sliderContents({
                                                                images: imagesData,
                                                                sliderType: sliderType,
                                                                sideControlArrows: sideControlArrows,
                                                                bottomButtons: bottomButtons,
                                                                slideWidth: slideWidth,
                                                                slideHeight: slideHeight,
                                                                thumbnailType: thumbnailType,
                                                                thumbnailWidth: thumbnailWidth,
                                                                thumbnailHeight: thumbnailHeight
                                                            }));
                                                        } else if( editor.editorMode == "html" ) {
                                                            QTags.insertContent( sliderContents({
                                                                images: imagesData,
                                                                sliderType: sliderType,
                                                                sideControlArrows: sideControlArrows,
                                                                bottomButtons: bottomButtons,
                                                                slideWidth: slideWidth,
                                                                slideHeight: slideHeight,
                                                                thumbnailType: thumbnailType,
                                                                thumbnailWidth: thumbnailWidth,
                                                                thumbnailHeight: thumbnailHeight
                                                            }));
                                                        }


                                                        popupWindowHolder.closeAll();
                                                }
                                            }
                                        ]
                                    };
                                    return popupData;

                                };

                            // Link
                                editor.shapeshifterGetAJAXParamsForLink = function( params ) {

                                    // Link Type
                                        var linkType = params.$target.data( 'link-type' );

                                    // Template
                                        var linkSettingsForm = _.template( $( '#wp-theme-shapeshifter-extensions-tinymce-button-settings-link' ).html() );

                                        popupData = {
                                            title: 'Link',
                                            modal: true,
                                            items: {
                                                type: 'container',
                                                layout: 'flex',
                                                direction: 'column',
                                                align: 'stretch',
                                                padding: 0,
                                                spacing: 0,
                                                minWidth: 600,
                                                minHeight: 200,
                                                html: linkSettingsForm({
                                                    linkType: linkType
                                                }),
                                            },
                                            buttons: [
                                                {
                                                    text: "Insert",
                                                    class: "primary",
                                                    onclick: function( e ) {

                                                        $( 'div[role=application] button' ).prop( 'disabled', true );
                                                        $( 'div[role=application] button' ).addClass( 'disabled' );

                                                        // Template
                                                            var linkTemplate = _.template( $( '#wp-theme-shapeshifter-extensions-tinymce-button-template-link' ).html() );

                                                            var postId = $( '#shapeshifter-slider-links[name="shapeshifter-slider-links"]' ).val();

                                                            if( postId === "none" ) {
                                                                popupWindowHolder.closeAll();
                                                                return;
                                                            }

                                                            $.ajax({
                                                                url: ajaxurl,
                                                                method: "POST",
                                                                context: $( e.target ),
                                                                dataType: "JSON",
                                                                data: {
                                                                    "post_id": postId,
                                                                    action: "shapeshifter_get_post_data"
                                                                },
                                                                success: function( data, textStatus, jqHXR ) {
                                                                    //console.log( data );
                                                                    // Setup
                                                                        var linkPermalink = data[ "permalink" ];
                                                                        var thumbnailTitle = data[ "post_title" ];
                                                                        var thumbnailURL = data[ "thumbnail_url" ];

                                                                        var isThumbnailOn = $( '#shapeshifter-slider-link-is-thumbnail-on' ).prop( 'checked' );
                                                                        var linkTarget = $( '#shapeshifter-slider-link-target' ).prop( 'checked' );
                                                                        var linkRel = $( '#shapeshifter-slider-link-nofollow' ).prop( 'checked' );

                                                                    // Insert Content
                                                                        var linkContents = linkTemplate({
                                                                            linkPermalink: linkPermalink,
                                                                            thumbnailTitle: thumbnailTitle,
                                                                            thumbnailURL: thumbnailURL,

                                                                            isThumbnailOn: isThumbnailOn,
                                                                            linkTarget: linkTarget,
                                                                            linkRel: linkRel
                                                                        });

                                                                        if( editor.editorMode == 'tmce' ) {
                                                                            editor.insertContent( linkContents );
                                                                        } else if( editor.editorMode == "html" ) {
                                                                            QTags.insertContent( linkContents );
                                                                        }


                                                                    // Close
                                                                        $( 'div[role=application] button' ).removeClass( 'disabled' );
                                                                        $( 'div[role=application] button' ).prop( 'disabled', false );
                                                                        popupWindowHolder.closeAll();
                                                                }
                                                            });


                                                    }
                                                }
                                            ]
                                        };

                                    return popupData;

                                };

                            // Balloon
                                editor.shapeshifterGetAJAXParamsForBalloon = function( params ) {

                                    // Balloon Type
                                        var balloonType = params.$target.data( 'balloon-type' );

                                    // Popup Data
                                        var popupData = {
                                            title: 'Balloon',
                                            modal: true,
                                            items: {
                                                type: 'container',
                                                layout: 'flex',
                                                direction: 'row',
                                                align: 'stretch',
                                                padding: 0,
                                                spacing: 0,
                                                minWidth: 600,
                                                minHeight: 300,
                                                html: $( '#wp-theme-shapeshifter-extensions-tinymce-button-settings-balloon' ).html(),
                                                onclick: function( e ) {

                                                    //console.log( $( e.target ).attr( 'class' ) );

                                                    // Image Set
                                                        if( $( e.target ).attr( 'id' ) == "shapeshifter-balloon-set-image" ) {

                                                            editor.execCommand( 
                                                                'shapeshifter_get_image_from_library', 
                                                                e, 
                                                                {
                                                                    inputHiddenId: "#shapeshifter-balloon-image-input",
                                                                    imageId: "#shapeshifter-balloon-image",
                                                                }
                                                            );

                                                            var alignSide = $( '.shapeshifter-balloon-image-align:checked' ).val();
                                                            $( "#shapeshifter-balloon-image" ).addClass( 'has-image' );

                                                        }

                                                    // Image Reset
                                                        else if( $( e.target ).hasClass( "shapeshifter-balloon-reset-image" ) ) {

                                                            $( "#shapeshifter-balloon-image-input" ).val( '' );
                                                            $( "#shapeshifter-balloon-image" ).attr( 'src', '' );
                                                            $( "#shapeshifter-balloon-image" ).removeClass( 'has-image' );

                                                        }

                                                    // Image Align
                                                        else if( $( e.target ).attr( 'class' ) == "shapeshifter-balloon-image-align" ) {

                                                            var value = $( e.target ).val();

                                                            // Align
                                                                $( '.shapeshifter-balloon-preview .shapeshifter-balloon-wrapper' ).removeClass( 'align-right' );
                                                                $( '.shapeshifter-balloon-preview .shapeshifter-balloon-wrapper' ).removeClass( 'align-left' );
                                                                $( '.shapeshifter-balloon-preview .shapeshifter-balloon-wrapper' ).addClass( 'align-' + value );

                                                            // Box Shadow
                                                                $( "#shapeshifter-balloon-image" ).removeClass( 'shadow-left-bottom' );
                                                                $( "#shapeshifter-balloon-image" ).removeClass( 'shadow-right-bottom' );
                                                                $( "#shapeshifter-balloon-image" ).addClass( 'shadow-' + value + '-bottom' );

                                                        }

                                                }
                                            },
                                            buttons: [
                                                {
                                                    text: "Insert",
                                                    class: "button button-primary",
                                                    onclick: function( e ) {

                                                        // No Image
                                                            if( ! $( "#shapeshifter-balloon-image" ).hasClass( 'has-image' ) ) {
                                                                alert( "Please Set an Image." );
                                                                return;
                                                            }

                                                        var balloonContents = _.template( $( '#wp-theme-shapeshifter-extensions-tinymce-button-template-balloon' ).html() );

                                                        // Data
                                                            var balloonImageCaption = $( '#shapeshifter-balloon-image-name' ).val();
                                                            var balloonImage = $( '#shapeshifter-balloon-image-input' ).val();
                                                            //var balloonDialog = $( '#shapeshifterballoondialog' ).val();
                                                            var balloonDialog = $( '#shapeshifterballoondialog_ifr' ).contents().find( '#tinymce' ).html();
                                                            var balloonImageAlign = $( '.shapeshifter-balloon-image-align:checked' ).val();

                                                        // Remove
                                                            tinymce.execCommand( 'mceRemoveEditor', true, "shapeshifterballoondialog" );

                                                        // Insert HTML
                                                            var insertedContent = balloonContents({
                                                                balloonType: balloonType,
                                                                balloonImageCaption: balloonImageCaption,
                                                                balloonImage: balloonImage,
                                                                balloonDialog: balloonDialog,
                                                                balloonImageAlign: balloonImageAlign
                                                            });


                                                        // Insert
                                                            if( editor.editorMode == "tmce" ) {
                                                                editor.insertContent( insertedContent );
                                                            } else if( editor.editorMode == "html" ) {
                                                                // Switch Active Editor
                                                                    window.wpActiveEditor = editor.prevEditorId;
                                                                    window.switchEditors.go( editor.prevEditorId, "html" );

                                                                QTags.insertContent( insertedContent );
                                                                document.getElementById("content").focus();
                                                            }

                                                        // Close
                                                            popupWindowHolder.closeAll();

                                                    }
                                                }
                                            ]
                                        };

                                    return popupData;

                                };

                            // Shorcode
                                editor.shapeshifterGetAJAXParamsForShortcode = function( params ) {

                                    // Shortcode Type
                                    // "new-entries", "search-entries"
                                        var shortcodeType = params.$target.data( 'shortcode-type' );
                                        var labelSuffix = "";
                                        if( shortcodeType === "new-entries" ) {
                                            labelSuffix = " New Entries";
                                        } else if( shortcodeType === "search-entries" ) {
                                            labelSuffix = " Search Entries";
                                        } else {
                                            labelSuffix = "";
                                        }

                                    // Template
                                        var shortcodeSettingsForm = _.template( $( '#wp-theme-shapeshifter-extensions-tinymce-button-settings-shortcode-' + shortcodeType ).html() );

                                        var popupData = {
                                            title: 'Shortcode' + labelSuffix,
                                            modal: true,
                                            items: {
                                                type: 'container',
                                                layout: 'flex',
                                                direction: 'column',
                                                align: 'stretch',
                                                padding: 0,
                                                spacing: 0,
                                                minWidth: 600,
                                                minHeight: 200,
                                                html: shortcodeSettingsForm({

                                                })
                                            },
                                            buttons: [
                                                {
                                                    text: "Insert",
                                                    class: "primary",
                                                    onclick: function( e ) {
                                                        console.log( e );
                                                        // Setup
                                                            var shorcodeName = "";
                                                            var shorcodeData = new Map();
                                                            if( shortcodeType === "new-entries" ) {

                                                                // Shortcode
                                                                    shorcodeName = "shapeshifter_new_entries";
                                                                // Atts
                                                                    shorcodeData.set( "number", $( '#shapeshifter-shortcode-new-entries-number' ).val() );
                                                                    shorcodeData.set( "is-thumbnail-on", ( $( '#shapeshifter-shortcode-new-entries-is-thumbnail-on' ).prop( 'checked' ) ? "true" : "" ) );
                                                                    shorcodeData.set( "excerpt-number", $( '#shapeshifter-shortcode-new-entries-excerpt-number' ).val() );

                                                            } else if( shortcodeType === "search-entries" ) {

                                                                // Shortcode
                                                                    shorcodeName = "shapeshifter_search_entries";
                                                                // Atts
                                                                    shorcodeData.set( "keywords", $( '#shapeshifter-shortcode-search-entries-keywords' ).val() );
                                                                    shorcodeData.set( "number", $( '#shapeshifter-shortcode-search-entries-number' ).val() );
                                                                    shorcodeData.set( "orderby", $( '#shapeshifter-shortcode-search-entries-orderby' ).val() );
                                                                    shorcodeData.set( "is-thumbnail-on", ( $( '#shapeshifter-shortcode-search-entries-is-thumbnail-on' ).prop( 'checked' ) ? "true" : "" ) );
                                                                    shorcodeData.set( "excerpt-number", $( '#shapeshifter-shortcode-search-entries-excerpt-number' ).val() );

                                                            }

                                                        // Shortcode
                                                            var shortcodeContents = editor.shapeshifterMakeShortcodeStr( shorcodeName, shorcodeData, "" );

                                                        // Insert
                                                            if( editor.editorMode == 'tmce' ) {
                                                                editor.insertContent( shortcodeContents );
                                                            } else if( editor.editorMode == "html" ) {
                                                                QTags.insertContent( shortcodeContents );
                                                            }
                                                            popupWindowHolder.closeAll();

                                                    }
                                                }
                                            ]
                                        };

                                    return popupData;

                                };

                                    // Make Shortcode
                                        editor.shapeshifterMakeShortcodeStr = function( shortcodeName, shorcodeData, text ) {

                                            var shortcodeContents = '[' + shortcodeName;

                                            shorcodeData.forEach( function( value, key, map ) {
                                                console.log( value );
                                                console.log( map );
                                                shortcodeContents += ' ' + key + '="' + value + '"';
                                            });

                                            shortcodeContents += ']';

                                            if( typeof text == "string" && text ) {
                                                shortcodeContents += text + '[/' + shortcodeName + ']';
                                            }

                                            return shortcodeContents;

                                        };

        // Add Exec
            // Row
                editor.addCommand( 'shapeshifter_insert_row', function( e, params ) {

                    // Get the Row Wrapper Template
                        $insertContents = $( $( '#wp-theme-shapeshifter-extensions-tinymce-button-template-row' ).html() );
                        console.log( $insertContents );

                    // Get Columns
                        columnNumber = params.$target.data( 'column-number' );
                        for( var i = 1; i <= columnNumber; i++ ) {
                            $insertContents.append( $( '#wp-theme-shapeshifter-extensions-tinymce-button-template-column' ).html() );
                        }

                    // Set the Return
                        editor.insertContents = $insertContents[ 0 ].outerHTML;

                });

            // Get an Image and Set
                editor.addCommand( 'shapeshifter_get_image_from_library', function( e, params ) {

                    // Vars
                        var inputId = params.inputHiddenId;
                        var imageId = params.imageId;

                    // Open Media Library
                        e.preventDefault();
                        var uploader = wp.media({
                            title: 'Select Images',
                            button: {
                                text: 'Set Images'   
                            },
                            multiple: false
                        }).on( 'select', function( e ) {

                            // Image
                                var selection = uploader.state().get( 'selection' );
                                var attachment = selection.first().toJSON();

                            // Set Values
                                $( inputId ).val( attachment.url );
                                $( imageId ).attr( 'src', attachment.url );

                        }).open();

                } );

            // Init TinyMCE
                editor.addCommand( 'shapeshifter_set_tinymce_on_popup', function( e, params ) {

                    // ID Contents
                        var editorId = params.editorId;

                    // Remove
                        tinymce.execCommand( 'mceRemoveEditor', true, editorId );

                    // tinyMCE Settings
                        tinyMCEGlobalSettings =  tinyMCEPreInit.mceInit.content;
                        //console.log( tinyMCEGlobalSettings );
                        tinyMCEGlobalSettings.selector = "#" + editorId;
                        tinyMCEGlobalSettings.height = "200px";
                        //console.log( tinyMCEGlobalSettings );

                    // tinyMCE Initialize
                        tinymce.init( tinyMCEGlobalSettings ); 
                        tinyMCE.execCommand( 'mceAddEditor', false, editorId ); 
                        /*
                        quicktags({ 
                            id : editorId
                        });
                        */

                } );

        // Event
            // Replacement for Visual Editor
                editor.on( 'BeforeSetcontent', function( event ) {

                    // For the class "shapeshifter-col"
                        $withWrapper = $( '<div class="shapeshifter-wrapper"></div>' ).append( event.content )
                        event.content = editor.getShapeShifterContentsOnVisualEditor( $withWrapper );

                    // Escape
                        $withWrapper = $( '<div class="shapeshifter-wrapper"></div>' ).append( event.content )
                        //event.content = editor.escapeAttsValuesOnVisualEditor( $withWrapper );

                });
                    // For Columns
                        editor.getShapeShifterContentsOnVisualEditor = function( $eventContent ) {

                            // Columns
                                $eventContent.find( '.shapeshifter-col' ).each( function( index, element ) {

                                    $this = $( this );

                                    var editorContents = decodeURIComponent( editor.returnContentsFromInputs( $this.html(), 'input.shapeshifter-col-content-box' ) );
                                    //console.log( editorContents );

                                    var colSize = $this.data( 'column-size' );
                                    if( typeof colSize === 'undefined' ) colSize = 1;
                                    
                                    inputHidden = '<input class="shapeshifter-col-content-box" type="hidden" value="' + encodeURIComponent( editorContents ) + '">';
                                    $this.html( inputHidden );

                                    $this.css({
                                        "flex-grow": colSize,
                                        "padding": "0px",
                                        "margin": "0px"
                                    });

                                    $this.attr( 'class', 'shapeshifter-col shapeshifter-col-' + colSize );

                                });
                                editButton = '<button class="shapeshifter-edit-col">Edit this Column</button>';
                                $eventContent.find( '.shapeshifter-col' ).prepend( editButton );

                            // Slider
                                $eventContent.find( '.shapeshifter-slider-pro' ).each( function( index, element ) {

                                    $this = $( this );

                                    // Images
                                        $this.find( 'img' ).each( function() {
                                            // Get
                                                var imageSrc = $( this ).attr( 'src' );
                                                var dataSrc = $( this ).data( 'src' );
                                            // Set
                                                $( this ).attr( 'src', dataSrc );
                                        });

                                });

                            // Adjust the Row Cols Size
                                editor.shapeshifterGetRowWithColsWidths( $eventContent );

                            return $eventContent.html();

                        };
                            // Convert Input into Contents
                                editor.returnContentsFromInputs = function( editorContents, inputSelector ) {

                                    wrapper = document.createElement( 'div' );
                                    wrapper.innerHTML = editorContents;

                                    $inputSelector = $( wrapper ).find( inputSelector );

                                    if( $inputSelector.exists() ) {

                                        editorContents = decodeURIComponent( $inputSelector.val() );
                                        return editor.returnContentsFromInputs( editorContents, inputSelector );

                                    }

                                    return editorContents;

                                };

                    // Escape
                        editor.escapeAttsValuesOnVisualEditor = function( $eventContent ) {

                            $eventContent.find( '*' ).each( function( index ) {
                                // Setup
                                    var $this = $( this );
                                    var atts = $this.context.attributes;
                                    var attsNum = atts.length;
                                // Escape
                                    if( attsNum == 0 )
                                        return;
                                    for( var i = 0; i < attsNum; i++ ) {
                                        console.log( _.escape( $this.attr( atts[ i ].name ) ) );
                                        $this.attr( atts[ i ].name, _.escape( $this.attr( atts[ i ].name ) ) );
                                    }
                                    console.log( $this );
                            });

                            return $eventContent.html();

                        };

            // Replacement for HTML Editor
                editor.on( 'GetContent', function( event ){

                    // For the class "shapeshifter-col"
                        $withWrapper = $( '<div class="shapeshifter-wrapper"></div>' ).append( event.content )
                        event.content = editor.getShapeShifterColOnHTMLEditor( $withWrapper );

                    // Escape
                        $withWrapper = $( '<div class="shapeshifter-wrapper"></div>' ).append( event.content )
                        event.content = editor.escapeAttsValuesOnHTMLEditor( $withWrapper )

                });

                    // For Columns
                        editor.getShapeShifterColOnHTMLEditor = function( $eventContent ) {

                            //console.log( $eventContent );

                            $eventContent.find( '.shapeshifter-col' ).each( function( index, element ) {

                                $this = $( this );

                                // Setup
                                    var colSize = $this.data( 'column-size' );
                                    if( typeof colSize === 'undefined' ) colSize = 1;
                                    var colPadding = $this.data( 'column-padding' );
                                    if( typeof colPadding === 'undefined' ) colPadding = 0;

                                eventContent = decodeURIComponent( editor.returnContentsFromInputs( $( this ).find( '.shapeshifter-col-content-box' ).val(), '.shapeshifter-col-content-box' ) );
                                $( this ).html( eventContent );

                                $this.css({
                                    "flex-grow": colSize,
                                    "padding": colPadding + "px",
                                    "margin": "0px"
                                });
                                $this.attr( 'class', 'shapeshifter-col shapeshifter-col-' + colSize );

                            });

                            // Adjust the Row Cols Size
                                editor.shapeshifterGetRowWithColsWidths( $eventContent );

                            return $eventContent.html();

                        };

                    // Escape
                        editor.escapeAttsValuesOnHTMLEditor = function( $eventContent ) {

                            $eventContent.find( '*' ).each( function( index ) {

                                //if( _.indexOf( ['A', 'IFRAME'], $( this )[ 0 ].nodeName ) ) return;

                                // Setup
                                    var $this = $( this );
                                    var atts = $this.context.attributes;
                                    var attsNum = atts.length;

                                // Escape
                                    if( attsNum == 0 )
                                        return;

                                    for( var i = 0; i < attsNum; i++ ) {
                                        if( $this.attr( atts[ i ].name ).match( /^http\s?\:\/\//i ) 
                                            || atts[ i ].name === "src"
                                            || atts[ i ].name === "href"
                                        ) {
                                        } else {
                                            $this.attr( atts[ i ].name, _.escape( $this.attr( atts[ i ].name ) ) );
                                        }
                                    }
                                    //console.log( $this );
                            });

                            return $eventContent.html();

                        };

            // Click Event
                editor.on( 'click', function( e ) {

                    $target = $( e.target );

                    // Edit
                        // For ".shapeshifter-col"
                            if( $target.hasClass( 'shapeshifter-edit-col' ) ) {

                                $selectedShapeShifterCol = $target.parent();// '.shapeshifter-col' );
                                console.log( $selectedShapeShifterCol );
                                editor.execCommand(
                                    'shapeshifter_col_text_settings_popup',
                                    e,
                                    {
                                        $col: $selectedShapeShifterCol,
                                        $content: $target.next( '.shapeshifter-col-content-box' )
                                    }
                                );
                                document.body.focus();

                            } else {

                            }

                } );

                    // Edit Textarea for Column
                        editor.addCommand( 'shapeshifter_col_text_settings_popup', function( e, params ) {

                            //console.log( params );

                            tinymce.execCommand( 'mceRemoveEditor', true, 'sseditor' );

                            // Col Contents
                                var editColContents = {
                                    type: 'container',
                                    minWidth: 780,
                                    //minWidth: '100%',
                                    minHeight: 300,
                                    html: $( '#wp-theme-shapeshifter-extensions-tinymce-button-column-contents' ).html()
                                };

                            // Col Styles
                                var editColSettings = {
                                    type: 'container',
                                    minWidth: 150,
                                    minHeight: 300,
                                    html: $( '#wp-theme-shapeshifter-extensions-tinymce-button-column-settings' ).html()
                                };

                            // Popup
                                var colSettingsFormWin = editor.windowManager.open({
                                    title: 'Edit',
                                    modal: true,
                                    items: {
                                        type: 'container',
                                        layout: 'flex',
                                        direction: 'row',
                                        align: 'stretch',
                                        padding: 0,
                                        spacing: 0,
                                        items: [
                                            editColContents, // Side Menu
                                            editColSettings // Main Menu
                                        ]
                                    },
                                    buttons: [
                                        {
                                            text: "Save",
                                            onclick: function( e ) {

                                                // Save Contents
                                                    $editorBody = $( 'iframe#sseditor_ifr' ).contents().find( 'body' );
                                                    encodedReturn = encodeURIComponent( $editorBody.html() );

                                                    //console.log( encodedReturn );

                                                    params.$content.val( encodedReturn );

                                                // Save Col Data
                                                    // Get
                                                        var colSize = $( '.tinymce-button-column-settings-popup' ).find( 'input[name="column-size"]' ).val();
                                                        if( typeof colSize === "undefined" ) colSize = 1;
                                                        var colPadding = $( '.tinymce-button-column-settings-popup' ).find( 'input[name="column-padding"]' ).val();
                                                    // Return
                                                        params.$col.attr( 'data-column-size', colSize );
                                                        params.$col.attr( 'data-column-padding', colPadding );
                                                        params.$col.attr( 'data-column-margin', '0' );
                                                    // CSS
                                                        params.$col.css({
                                                            "flex-grow": colSize,
                                                            "padding": "0px",
                                                            "margin": "0px"
                                                        });
                                                    // Class
                                                        params.$col.attr( 'class', 'shapeshifter-col shapeshifter-col-' + colSize );

                                                    //console.log( params.$col );

                                                colSettingsFormWin.close();

                                                $( 'input#publish' ).removeClass( 'disabled' );
                                                // Adjust the Row Cols Size
                                                    editor.execCommand( 'shapeshifterSetRowColsWidths', '', {} );

                                            }
                                        },
                                        {
                                            text: "Remove",
                                            onclick: function( e ) {
                                                params.$col.remove();
                                                colSettingsFormWin.close();
                                            // Adjust the Row Cols Size
                                                editor.execCommand( 'shapeshifterSetRowColsWidths', '', {} );
                                            }
                                        },
                                        {
                                            text: "Cancel",
                                            onclick: function( e ) {
                                                colSettingsFormWin.close();
                                            }
                                        }
                                    ]
                                });
                                popupWindowHolder.set( colSettingsFormWin );

                            // Col Editor

                            // Get Col Style Data
                                var $colSizeInput = $( '.tinymce-button-column-settings-popup' ).find( 'input[name="column-size"]' );
                                var $colPaddingInput = $( '.tinymce-button-column-settings-popup' ).find( 'input[name="column-padding"]' );

                                $colSizeInput.val( params.$col.data( 'column-size' ) );
                                $colPaddingInput.val( params.$col.data( 'column-padding' ) );

                            // ID Contents
                                var editorId = "sseditor";
                                var editorContents = decodeURIComponent( params.$content.val() );
                                //console.log( editorContents );
                                editorContents = decodeURIComponent( editor.returnContentsFromInputs( editorContents, 'input.shapeshifter-col-content-box' ) );
                                //console.log( editorContents );

                            // tinyMCE Settings
                                tinyMCEGlobalSettings =  tinyMCEPreInit.mceInit.content;
                                //console.log( tinyMCEGlobalSettings );
                                tinyMCEGlobalSettings.selector = "#sseditor";
                                tinyMCEGlobalSettings.height = "200px";
                                //console.log( tinyMCEGlobalSettings );

                            // tinyMCE Initialize
                                tinymce.init( tinyMCEGlobalSettings ); 
                                tinyMCE.execCommand( 'mceAddEditor', false, editorId ); 
                                quicktags({ 
                                    id : editorId
                                });

                            // Editor Contents
                                $editorBody = $( 'iframe#sseditor_ifr' ).contents().find( 'body' );
                                $editorBody.html( editorContents );

                            // Adjust the Row Cols Size
                                editor.execCommand( 'shapeshifterSetRowColsWidths', '', {} );

                        });

            // KeyDown Disable
                // in Column and Slider
                    editor.on( 'keydown', function( e ) {

                        var $currentNode = $( editor.selection.getNode() );

                        //console.log( currentNode );

                        var hasElement = 0;
                        $( this.contentDocument ).find( '.shapeshifter-row, .shapeshifter-slider-pro' ).each( function( index ) {
                            var has = $( this ).find( $currentNode ).length;
                            hasElement = ( has > 0 ? has : hasElement );
                        });

                        if( hasElement )
                            e.preventDefault();

                    });

            // open popup on placeholder double click
                editor.on( 'DblClick', function( e ) {

                    console.log( 'double clicked' );

                    var cls = e.target.className.indexOf( 'wp-shapeshifter_item' );

                    if ( e.target.nodeName == 'IMG' 
                        && e.target.className.indexOf( 'wp-shapeshifter_item' ) > -1
                    ) {

                        var title = e.target.attributes['data-sh-attr'].value;
                        title = window.decodeURIComponent( title );

                        //console.log( title );

                        var content = e.target.attributes['data-sh-content'].value;
                        editor.execCommand( 'shapeshifter_item_popup', '', {
                            header : editor.getAttr( title, 'header' ),
                            footer : editor.getAttr( title, 'footer' ),
                            type   : editor.getAttr( title, 'type' ),
                            content: content
                        });
                    }

                });

                    // Helper Functions
                        editor.getAttr = function( s, n ) {

                            n = new RegExp( n + '=\"([^\"]+)\"', 'g' ).exec( s );
                            return n ?  window.decodeURIComponent( n[ 1 ] ) : '';

                        };

                    // HTML of Holder in Visual Editor
                        editor.getColOnVisualEditor = function( cls, data, con ) {

                            var placeholder = url + '/img/' + editor.getAttr( data, 'type' ) + '.jpg';
                            data = window.encodeURIComponent( data );
                            content = window.encodeURIComponent( con );
                 
                            return '<img src="' + placeholder + '" class="mceItem ' + cls + '" ' + 'data-sh-attr="' + data + '" data-sh-content="' + con + '" data-mce-resize="false" data-mce-placeholder="1" />';

                        };

                            // Into Holder
                                editor.replaceShortcodes = function( content ) {

                                    content = content.replace( 
                                        /\<div[^>]+class\=[\'"]shapeshifter-col[^\'"]+[\'"][^>]+?>([^\]]*)\]([^\]]*)\[\/shapeshifter_item\]/g, 
                                        function( all, attr, con ) {
                                            return editor.getColOnVisualEditor( 'wp-shapeshifter_item', attr , con );
                                        }
                                    );
                                    return content;

                                };

                            // Into Shortcodes
                                editor.restoreShortcodes = function( content ) {

                                    //match any image tag with our class and replace it with the shortcode's content and attributes
                                    return content.replace( /(?:<p(?: [^>]+)?>)*(<img [^>]+>)(?:<\/p>)*/g, function( match, image ) {
                                        var data = editor.getAttr( image, 'data-sh-attr' );
                                        var con = editor.getAttr( image, 'data-sh-content' );
                         
                                        if ( data ) {
                                            return '<p>MCEテスト</p>';
                                        }
                                        return match;
                                    });

                                };
                 
            // Selection Change 
                editor.on( "selectionchange", function( e ) {

                    e.preventDefault();

                    window.shapeshifterCapturedText = editor.selection.getContent();
                    //console.log( shapeshifterCapturedText );

                    if( editor.getWin().getSelection().toString() !== "" ) {
                        editor.shapeshifterSelection = editor.getWin().getSelection().toString();
                        editor.shapeshifterEditorNode = editor.selection.getNode();
                        editor.shapeshifterEditorRange = editor.selection.getRng();
                        editor.shapeshifterEditorSelection = editor.selection.getSel();
                        //console.log( editor.shapeshifterSelection );
                        //console.log( editor.shapeshifterEditorNode );
                        //console.log( editor.shapeshifterEditorRange );
                        //console.log( editor.shapeshifterEditorSelection );
                   }

                } );

        // Popup Editor
            editor.shapeshifterPopupEditor = function( editorId, editorContents ) {

                // TinyMCE for Popup Init
                    // Reference "http://stackoverflow.com/questions/21519322/load-wordpress-wp-editor-dynamically-ajax"
                        var init = tinymce.extend( {}, tinyMCEPreInit.mceInit[ editorId ] );
                        try { 
                            tinymce.init( init );
                            $( '#' + sseditor ).val( editorContents );
                        } catch( e ) { return; }

                        $( 'textarea[id="' + editorId + '"]' ).closest( 'form' ).find( 'input[type="submit"]' ).click( function() {
                            if( getUserSetting( 'editor' ) == 'tmce' ) {
                                var editorId = mce.find( 'textarea' ).attr( 'id' );
                                tinymce.execCommand( 'mceRemoveEditor', false, editorId );
                                tinymce.execCommand( 'mceAddEditor', false, editorId );
                            }
                            return true;
                        });

            };

        // Set Row and the Cols Attributes ( Size Width )
            editor.addCommand( 'shapeshifterSetRowColsWidths', function( e, params ) {

                $( '#content_ifr' ).contents().find( '.shapeshifter-row' ).each( function( index ) {
                    $thisRow = $( this );

                    console.log( $thisRow );

                    // Cols Size Total
                        colsSizeTotal = 0;
                        $thisRow.children().each( function( index ) {
                            colsSizeTotal += $( this ).data( 'column-size' );
                        });
                        $thisRow.attr( 'data-cols-size-total', colsSizeTotal );

                    // Set Each Col Size width
                        $thisRow.children().each( function( index ) {
                            colSize = ( $( this ).data( 'column-size' ) / $thisRow.data( 'cols-size-total' ) ) * 100;
                            $( this ).css({
                                'width': colSize + "%"
                            });
                            console.log( $( this ) );
                        });

                });

            });

        // Get Row with Cols Width
            editor.shapeshifterGetRowWithColsWidths = function( $contents ) {

                $contents.find( '.shapeshifter-row' ).each( function( index ) {
                    $thisRow = $( this );

                    //console.log( $thisRow );

                    // Cols Size Total
                        colsSizeTotal = 0;
                        $thisRow.children().each( function( index ) {
                            colsSizeTotal += $( this ).data( 'column-size' );
                        });
                        $thisRow.attr( 'data-cols-size-total', colsSizeTotal );

                    // Set Each Col Size width
                        $thisRow.children().each( function( index ) {
                            colSize = ( $( this ).data( 'column-size' ) / $thisRow.data( 'cols-size-total' ) ) * 100;
                            $( this ).css({
                                'width': colSize + "%"
                            });
                            //console.log( $( this ) );
                        });

                });

                return $contents;

            };


        // Exec when Columns Appended on Visual Editor by Enter key ( or soemthing else )
            //$( document ).ready( function() {
            editor.on( "ready", function( e ) {

                console.log( e );

                //$( this.contentDocument ).find( '.shapeshifter-draggable' ).draggable();
               
            });



        // Globalize
            window.shapeshifterEditor = editor;

    });

} ) ( window, jQuery, _ );