jQuery(window).load(function(){

	var container_width = jQuery('input[name="generate_settings[container_width]"]').val();
	var font_size_body = jQuery('input[name="generate_settings[body_font_size]"]').val();
	var font_size_site_title = jQuery('input[name="generate_settings[site_title_font_size]"]').val();
	var font_size_site_tagline = jQuery('input[name="generate_settings[site_tagline_font_size]"]').val();
	var font_size_navigation = jQuery('input[name="generate_settings[navigation_font_size]"]').val();
	var font_size_widget_title = jQuery('input[name="generate_settings[widget_title_font_size]"]').val();
	var font_size_h1 = jQuery('input[name="generate_settings[heading_1_font_size]"]').val();
	var font_size_h2 = jQuery('input[name="generate_settings[heading_2_font_size]"]').val();
	var font_size_h3 = jQuery('input[name="generate_settings[heading_3_font_size]"]').val();
	var font_size_h4 = jQuery('input[name="generate_settings[heading_4_font_size]"]').val();

	// wait till iframe has been loaded
	setTimeout(function() {
	
		// Container width
		jQuery('input[name="generate_settings[container_width]"]').next('div.slider').slider({
			value: container_width,
			min: 700,
			max: 2000,
			step: 5,
			slide: function( event, ui ) {
				// Change input value and slider position
				jQuery('input[name="generate_settings[container_width]"]').val( ui.value ).change();
				jQuery('#customize-control-generate_settings-container_width .value').text( ui.value );
				// Change CSS in LivePreview			
				//$j("iframe").contents().find('body').css('font-size', ui.value+'px');
					
			}
		});
		
		// Body
		jQuery('input[name="generate_settings[body_font_size]"]').next('div.slider').slider({
			value: font_size_body,
			min: 6,
			max: 25,
			slide: function( event, ui ) {
				// Change input value and slider position
				jQuery('input[name="generate_settings[body_font_size]"]').val( ui.value ).change();
				jQuery('#customize-control-generate_settings-body_font_size .value').text( ui.value );
				// Change CSS in LivePreview			
				//$j("iframe").contents().find('body').css('font-size', ui.value+'px');
					
			}
		});
		
		// Site title
		jQuery('input[name="generate_settings[site_title_font_size]"]').next('div.slider').slider({
			value: font_size_site_title,
			min: 10,
			max: 200,
			slide: function( event, ui ) {
				// Change input value and slider position
				jQuery('input[name="generate_settings[site_title_font_size]"]').val( ui.value ).change();
				jQuery('#customize-control-generate_settings-site_title_font_size .value').text( ui.value );
				// Change CSS in LivePreview			
				//$j("iframe").contents().find('body').css('font-size', ui.value+'px');
					
			}
		});
		
		// Site tagline
		jQuery('input[name="generate_settings[site_tagline_font_size]"]').next('div.slider').slider({
			value: font_size_site_tagline,
			min: 6,
			max: 50,
			slide: function( event, ui ) {
				// Change input value and slider position
				jQuery('input[name="generate_settings[site_tagline_font_size]"]').val( ui.value ).change();
				jQuery('#customize-control-generate_settings-site_tagline_font_size .value').text( ui.value );
				// Change CSS in LivePreview			
				//$j("iframe").contents().find('body').css('font-size', ui.value+'px');
					
			}
		});
		
		// Navigation
		jQuery('input[name="generate_settings[navigation_font_size]"]').next('div.slider').slider({
			value: font_size_navigation,
			min: 6,
			max: 30,
			slide: function( event, ui ) {
				// Change input value and slider position
				jQuery('input[name="generate_settings[navigation_font_size]"]').val( ui.value ).change();
				jQuery('#customize-control-generate_settings-navigation_font_size .value').text( ui.value );
				// Change CSS in LivePreview			
				//$j("iframe").contents().find('body').css('font-size', ui.value+'px');
					
			}
		});
		
		// Widget titles
		jQuery('input[name="generate_settings[widget_title_font_size]"]').next('div.slider').slider({
			value: font_size_widget_title,
			min: 6,
			max: 30,
			slide: function( event, ui ) {
				// Change input value and slider position
				jQuery('input[name="generate_settings[widget_title_font_size]"]').val( ui.value ).change();
				jQuery('#customize-control-generate_settings-widget_title_font_size .value').text( ui.value );
				// Change CSS in LivePreview			
				//$j("iframe").contents().find('body').css('font-size', ui.value+'px');
					
			}
		});
		
		// h1
		jQuery('input[name="generate_settings[heading_1_font_size]"]').next('div.slider').slider({
			value: font_size_h1,
			min: 15,
			max: 100,
			slide: function( event, ui ) {
				// Change input value and slider position
				jQuery('input[name="generate_settings[heading_1_font_size]"]').val( ui.value ).change();
				jQuery('#customize-control-generate_settings-heading_1_font_size .value').text( ui.value );
				// Change CSS in LivePreview			
				//$j("iframe").contents().find('body').css('font-size', ui.value+'px');
					
			}
		});
		
		// h2
		jQuery('input[name="generate_settings[heading_2_font_size]"]').next('div.slider').slider({
			value: font_size_h2,
			min: 10,
			max: 80,
			slide: function( event, ui ) {
				// Change input value and slider position
				jQuery('input[name="generate_settings[heading_2_font_size]"]').val( ui.value ).change();
				jQuery('#customize-control-generate_settings-heading_2_font_size .value').text( ui.value );
				// Change CSS in LivePreview			
				//$j("iframe").contents().find('body').css('font-size', ui.value+'px');
					
			}
		});
		
		// h3
		jQuery('input[name="generate_settings[heading_3_font_size]"]').next('div.slider').slider({
			value: font_size_h3,
			min: 10,
			max: 80,
			slide: function( event, ui ) {
				// Change input value and slider position
				jQuery('input[name="generate_settings[heading_3_font_size]"]').val( ui.value ).change();
				jQuery('#customize-control-generate_settings-heading_3_font_size .value').text( ui.value );
				// Change CSS in LivePreview			
				//$j("iframe").contents().find('body').css('font-size', ui.value+'px');
					
			}
		});
		
		// h4
		jQuery('input[name="generate_settings[heading_4_font_size]"]').next('div.slider').slider({
			value: font_size_h4,
			min: 10,
			max: 80,
			slide: function( event, ui ) {
				// Change input value and slider position
				jQuery('input[name="generate_settings[heading_4_font_size]"]').val( ui.value ).change();
				jQuery('#customize-control-generate_settings-heading_4_font_size .value').text( ui.value );
				// Change CSS in LivePreview			
				//$j("iframe").contents().find('body').css('font-size', ui.value+'px');
					
			}
		});

	});

});