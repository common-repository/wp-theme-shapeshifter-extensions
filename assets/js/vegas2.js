( function( $ ) { $( document ).ready( function() {
// Vegas2
	if( typeof vegasData != 'undefined' ) {
		vegasData.forEach( function( data, index ) {
			//console.log( data.properties );
			$( data.selectorId ).vegas( data.properties );
		} );
	}
}); }) ( jQuery );