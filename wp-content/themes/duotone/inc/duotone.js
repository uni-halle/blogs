jQuery( document ).ready( function( $ ) {
	$( '#sidebar > ul > li' ).css( 'display', 'none' );
	$( '#sidebar > ul > *:lt(3)' ).css( 'display', 'block' );

	$( '.archive #cat' ).change( function() {
		var catID = $( this ).val();
		if ( 0 < catID )
			window.location.href = Duotone.homeUrl + '?cat=' + catID;
	} );

	$( '.archive #archive-dropdown' ).change( function() {
		window.location.href = $( this ).val();
	} );
} );