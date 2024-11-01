( function( global, factory ) {

	"use strict";

	if ( typeof module === "object" && typeof module.exports === "object" ) {

		// For CommonJS and CommonJS-like environments where a proper `window`
		// is present, execute the factory and get jQuery.
		// For environments that do not have a `window` with a `document`
		// (such as Node.js), expose a factory as module.exports.
		// This accentuates the need for the creation of a real `window`.
		// e.g. var jQuery = require("jquery")(window);
		// See ticket #14549 for more info.
		module.exports = global.document ?
			factory( global, true ) :
			function( w ) {
				if ( ! w.document ) {
					throw new Error( "jQuery requires a window with a document" );
				}
				return factory( w );
			};
	} else {
		factory( global );
	}

// Pass this if window is not defined yet
} ) ( typeof window !== "undefined" ? window : this, function( window, noGlobal ) {


	// Before Define Test
		var viewTypeReference = {
			row: "rowView",
			slider: "sliderView",
			shortcode: "shortcodeView"
		};

		var shapeshifterSavedViews = [
			{ 
				viewType: "row",
				attributes: {
					name: "Row",
					idName: "shapeshifter-row-" + this.cid,
					className: "shapeshifter-row",
					columns: [
						{ size: 1 },
						{ size: 2 },
						{ size: 1 }
					],
					margin: "0px",
					padding: "0px",
					border: "solid #eee 1px"
				}
			},
			{
				viewType: "slider",
				attributes: {
					name: "Column",
					size: 1,
					margin: "0px",
					padding: "0px",
					border: "solid #eee 1px"
				}
			}
		];

		var shapeshifterBackbone = {
			model: {},
			collection: {},
			view: {}
		};
		var ShapeShifterBackbone = {
			model: {},
			collection: {},
			view: {}
		};


	// Init
		var ShapeShifterTinyMCEObject,
			ShapeShifterModel = Backbone.Model,
			ShapeShifterCollection = Backbone.Collection,
			ShapeShifterView = Backbone.View;

		var $ = window.jQuery;


	// Columns
		// Column Base Model
			var ShapeShifterColumn = ShapeShifterModel.extend({
				// Default Data
					defaults: {
						name: "Column",
						size: 1,
						margin: "0",
						padding: "0",
					},
				// Init
					initialize: function( atts, options ) {

					},
				// Validate for Setting Attribute
					validate: function( atts ) {
						if( _.isEmpty( atts.name ) ) {
							return "Title must not be empty.";
						}
						if( ! _.isNumber( atts.size ) && atts.size < 1 ) {
							return "Size must be a number.";
						}
					},
				// Set Attributes
					setSize: function( int ) {
						this.set( 'size', int );
						this.setNamebySize( int );
					},
					setName: function( name ) {
						this.set( 'name', name );
					},
					setNamebySize: function( int ) {
						this.set( 'name', 'Col ' + int );
					}

			});

		// Column Collection
			var ShapeShifterColumnCollection = ShapeShifterCollection.extend({
				model: new ShapeShifterColumn
			});

		// View ( Without Template )
			var ShapeShifterColumnView = ShapeShifterView.extend({

				// Init
					initialize: function() {
						this.setTemplate( 'wp-theme-shapeshifter-extensions-tinymce-button-template-column' );
					},

				// Render
					render: function() {
						var template = this.template( this.model.toJSON() );
						this.$el.html( template );
						return this;
					},

				// Manipulate Template
					setTemplate: function( templateId ) {
						this.template = _.template( $( '#' + templateId ).html() );
					},
					setSettingsFormTemplate: function() {
						this.setTemplate( 'wp-theme-shapeshifter-extensions-tinymce-button-settings-column' );
					},
					setInsertTemplate: function() {
						this.setTemplate( 'wp-theme-shapeshifter-extensions-tinymce-button-template-column' );
					},

				// Manipulate Model Attributes
					model: new ShapeShifterColumn,
					setModelAttributes: function( atts ) {
						this.model.set( atts );
					},
					resetModelAttributes: function( Model ) {
						this.model = new Model;
					},

				// Manipulate Model Attributes
					collection: new ShapeShifterColumnCollection,


			});
				// Settings
					ShapeShifterColumnSettingsView = ShapeShifterColumnView.extend({

						// Init
							initialize: function( atts ) {
								this.model.set( atts );
								this.template = setTemplate( 'wp-theme-shapeshifter-extensions-tinymce-button-column-settings' );
							},

					});

				// Contents
					ShapeShifterColumnContentsView = ShapeShifterColumnView.extend({

						// Init
							initialize: function( atts ) {
								this.model.set( atts );
								this.template = setTemplate( 'wp-theme-shapeshifter-extensions-tinymce-button-column-contents' );
							},

					});

	// Rows
		// Base Row Model
			var ShapeShifterRow = ShapeShifterModel.extend({
				defaults: {
					id: "",
					margin: "0px",
					padding: "0px",
					border: "solid #eee 1px"
				},
				validate: function( atts ) {
					if( _.isEmpty( atts.name ) ) {
						return "Title must not be empty."
					}
				}
			});
				// Settings
					var ShapeShifterRowSettings = ShapeShifterRow.extend({
						defaults: {
							type: "settings",
							margin: "0px",
							padding: "0px",
							border: "solid #eee 1px",
							templateId: "shapeshifter-tinymce-button-html",
						},
						initialize: function( atts ) {
							console.log( atts );
						}
					});

				// Content
					var ShapeShifterRowContents = ShapeShifterRow.extend({
						defaults: {
							type: "contents",
							id: "Row",
							margin: "0px",
							padding: "0px",
							border: "solid #eee 1px",
							templateId: "shapeshifter-tinymce-button-html",
							columns: []
						},
						initialize: function( atts ) {

							this.set( atts );
							this.set( 'columns', new ShapeShifterColumnCollection );

						},
						addColumn: function( atts ) {
							atts.columns.add( atts );
						}
					});

		// View
			var ShapeShifterRowView = ShapeShifterView.extend({

				// Init
					initialize: function( atts ) {

						// Model
							if( atts.type = 'settings' ) {
								this.model = new ShapeShifterRowSettings;
							} else if( atts.type = 'contents' ) {
								this.model = new ShapeShifterRowContents;
							}
						// Collection
							this.collection = new ShapeShifterColumnCollection;

					},

				// Manipulate Columns
					addColumn: function( columns ) {

						if( this.model.get( 'type' ) == 'settings' ) {

							this.collection.add( columns );

						} else if( this.model.get( 'type' ) == 'contents' ) {

							this.collection.add( columns );

						}
						
					},

				// Template
					template: _.template( $( '#wp-theme-shapeshifter-extensions-tinymce-button-template-row' ).html() ),

					// Methods
						setTemplate: function( templateId ) {
							this.template = _.template( $( '#' + templateId ).html() );
						},
						setSettingsFormTemplate: function() {
							this.setTemplate( 'wp-theme-shapeshifter-extensions-tinymce-button-settings-column' );
						},
						setInsertTemplate: function() {
							this.setTemplate( 'wp-theme-shapeshifter-extensions-tinymce-button-template-column' );
						},

				// Render
					render: function() {
						var template = this.template( this.model.toJSON() );
						this.$el.html( template );
						return this;
					}

			});


	// Global Vars
		ShapeShifterTinyMCEObject = {
			column: {
				model: function( modelType ) {
					if( modelType === 'standard' ) {
						return ShapeShifterColumn;
					}
				},
				collection: ShapeShifterColumnCollection,
				view: ShapeShifterColumnView
			},
			row: {
				model: function( modelType ) {
					if( modelType === 'standard' ) {
						return ShapeShifterRow;
					} else if( modelType === 'settings' ) {
						return ShapeShifterRowSettings;
					} else if( modelType === 'contents' ) {
						return ShapeShifterRowContents;
					} else {
						throw new Error( "modelType '" + modelType + "' doesn't exist." );
					}
				},
				view: ShapeShifterRowView
			}
		};

	// Final
	if ( ! noGlobal ) {
		window.ShapeShifterTinyMCEObject = window.SSTMCEO = ShapeShifterTinyMCEObject;
	}

	return ShapeShifterTinyMCEObject;

} );