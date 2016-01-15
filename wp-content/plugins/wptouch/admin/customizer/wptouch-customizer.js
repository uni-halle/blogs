function wptouchCustomizerCloseButton(){
	jQuery( '#customize-header-actions' ).unbind().on( 'click', 'a.customize-controls-close', function( e ){
		e.preventDefault();
		window.location = WPtouchCustomizer.settings_url;
	});
}

function wptouchCustomizerDeviceToggles() {
	html = '<div class="toggle-wrapper">';
//	html += '<i class="customize-tooltip" title="Click to change orientation"></i>';
	html += '<div class="toggle-inner portrait">';
	html += '<span>' + WPtouchCustomizer.device_orientation + ':</span>';
	html += '<i class="icon-mobile toggle active"></i>';
	if ( WPtouchCustomizer.device_tags.indexOf( 'tablet' ) > -1 ) {
		html += '<i class="icon-tablet toggle"></i>';
	}
	html += '</div>';
	html += '</div>';

	jQuery( '.wp-full-overlay' ).append( html);

//	jQuery( 'i.customize-tooltip' ).tooltip( { placement:'top' } );

	jQuery( '.toggle-inner' ).on( 'click', 'i', function( e ){

		var previewDiv = jQuery( '#customize-preview' );
		var innerDiv = jQuery( '.toggle-inner' );

		// Active device
		if ( !jQuery( this ).hasClass( 'active' ) ) {
			jQuery( '.toggle-inner i' ).removeClass( 'active' );
			jQuery( this ).addClass( 'active' );
			if ( jQuery( this ).hasClass( 'icon-tablet' ) ) {
				previewDiv.addClass( 'tablet' );
			} else {
				previewDiv.removeClass( 'tablet' );
			}
			if ( jQuery( this ).hasClass( 'icon-mobile' ) ) {
				previewDiv.removeClass( 'tablet' );
			} else {
				previewDiv.addClass( 'tablet' );
			}
			// Cleanup orientation on device switch
			innerDiv.removeClass( 'landscape' ).addClass( 'portrait' );
			previewDiv.removeClass( 'landscape' );
			// Don't do the orientation change on active switch
			return;
		}
		// Handle Orientation
		if ( innerDiv.hasClass( 'portrait' ) ) {
			innerDiv.removeClass( 'portrait' ).addClass( 'landscape' );
			previewDiv.addClass( 'landscape' );
		} else {
			innerDiv.removeClass( 'landscape' ).addClass( 'portrait' );
			previewDiv.removeClass( 'landscape' );
		}

		e.preventDefault();

	});
}

function wptouchCustomizerAddRangeValue(){
	jQuery( 'input[type="range"]', '#customize-theme-controls' ).each( function(){
		jQuery( this ).after( '<span class="rangeval"></span>' );
		jQuery( this ).on( 'click mousemove', function(){
			rangeValue = jQuery( this ).val();
			jQuery( this ).next( 'span' ).text( rangeValue );
		}).click();
	});
}

function wptouchCustomizerGetLuma( hexvalue ) {
	var c = hexvalue.substring( 1 );      // strip #
	var rgb = parseInt( c, 16 );   		// convert rrggbb to decimal
	var r = ( rgb >> 16 ) & 0xff;  		// extract red
	var g = ( rgb >>  8 ) & 0xff;  		// extract green
	var b = ( rgb >>  0 ) & 0xff;  		// extract blue

	return 0.2126 * r + 0.7152 * g + 0.0722 * b; // per ITU-R BT.709'
}

function wptouchCustomizerFoundationSettings(){

	// Sharing Links on/off
	var sharingCheckbox = jQuery( '[id$=show_share]' );
	sharingCheckbox.on( 'change wptouch.customizerReady', 'input', function(){
		if ( jQuery( this ).is( ':checked' ) ) {
			sharingCheckbox.nextAll( 'li' ).css( 'visibility', '' );
		} else {
			sharingCheckbox.nextAll( 'li' ).css( 'visibility', 'hidden' );
		}
	});

	// Featured Slider on/off
	var featuredCheckbox = jQuery( '[id$=featured_enabled]' );
	featuredCheckbox.on( 'change wptouch.customizerReady', 'input', function(){
		if ( jQuery( this ).is( ':checked' ) ) {
			featuredCheckbox.nextAll( 'li' ).css( 'visibility', '' );
		} else {
			featuredCheckbox.nextAll( 'li' ).css( 'visibility', 'hidden' );
		}
	});

	// Featured slider source select
	jQuery( '[id$="featured_type"]' ).on( 'change wptouch.customizerReady', 'select', function() {
		var tagSetting = jQuery( '[id$=featured_tag] *' );
		var catSetting = jQuery( '[id$=featured_category] *' );
		var posttySetting = jQuery( '[id$=featured_post_type] *' );
		var postSetting = jQuery( '[id$=featured_post_ids] *' );

		switch( jQuery( this ).val() ) {
			case 'tag':
				tagSetting.show();
				catSetting.hide();
				posttySetting.hide();
				postSetting.hide();
			break;
			case 'category':
				tagSetting.hide();
				catSetting.show();
				posttySetting.hide();
				postSetting.hide();
			break;
			case 'post_type':
				tagSetting.hide();
				catSetting.hide();
				posttySetting.show();
				postSetting.hide();
			break;
			case 'posts':
				tagSetting.hide();
				catSetting.hide();
				posttySetting.hide();
				postSetting.show();
			break;
			case 'latest':
			default:
				tagSetting.hide();
				catSetting.hide();
				posttySetting.hide();
				postSetting.hide();
			break;
		}
	});

	// Fire a change to deal with Customizer controlof .change()
	jQuery( '#customize-theme-controls' ).find( 'input[type="checkbox"], select' ).trigger( 'wptouch.customizerReady' );

}

function wptouchCustomizerChecklist() {
	jQuery( '.customize-control-checklist input[type="checkbox"]' ).on(
        'change',
        function() {

            checkbox_values = jQuery( this ).parents( '.customize-control' ).find( 'input[type="checkbox"]:checked' ).map(
                function() {
                    return this.value;
                }
            ).get().join( ',' );

            jQuery( this ).parents( '.customize-control' ).find( 'input[type="hidden"]' ).val( checkbox_values ).trigger( 'change' );
        }
    );
}

function wptouchCustomizerAdminReady(){
	wptouchCustomizerCloseButton();
	wptouchCustomizerDeviceToggles();
	wptouchCustomizerAddRangeValue();
	wptouchCustomizerFoundationSettings();
	wptouchCustomizerChecklist();
}

jQuery( document ).ready( function() {
	wptouchCustomizerAdminReady();
});