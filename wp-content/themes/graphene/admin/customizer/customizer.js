jQuery(document).ready(function ($) {

	/* Highlight new features */
	$('#accordion-panel-graphene-general .accordion-section-title').append('<span class="new-label">' + grapheneCustomizer.l10n.new + '</span>');
	$('#accordion-panel-graphene-display .accordion-section-title').append('<span class="new-label">' + grapheneCustomizer.l10n.new + '</span>');
	// $('#accordion-panel-graphene-stacks .accordion-section-title').append('<span class="new-label">' + grapheneCustomizer.l10n.new + '</span>');
	// $('#accordion-section-title_tagline .accordion-section-title').append('<span class="new-label">' + grapheneCustomizer.l10n.new + '</span>');
	$('#accordion-section-graphene-general-header .accordion-section-title').append('<span class="new-label">' + grapheneCustomizer.l10n.new + '</span>');
	$('#accordion-section-graphene-general-slider .accordion-section-title').append('<span class="new-label">' + grapheneCustomizer.l10n.new + '</span>');
	// $('#accordion-section-graphene-general-social-profiles .accordion-section-title').append('<span class="new-label">' + grapheneCustomizer.l10n.new + '</span>');
	// $('#accordion-section-graphene-general-mentions-bar .accordion-section-title').append('<span class="new-label">' + grapheneCustomizer.l10n.new + '</span>');
	$('#accordion-section-graphene-display-posts .accordion-section-title').append('<span class="new-label">' + grapheneCustomizer.l10n.new + '</span>');
	$('#customize-control-graphene_settings-slider_disable_caption label').append('<span class="new-label">' + grapheneCustomizer.l10n.new + '</span>');
	$('#customize-control-graphene_settings-slider_exclude_posts .customize-control-title').append('<span class="new-label">' + grapheneCustomizer.l10n.new + '</span>');
	$('#customize-control-graphene_settings-slider_exclude_posts_cats .customize-control-title').append('<span class="new-label">' + grapheneCustomizer.l10n.new + '</span>');
	$('#customize-control-graphene_settings-disable_yarpp_template label').append('<span class="new-label">' + grapheneCustomizer.l10n.new + '</span>');
	$('#customize-control-graphene_settings-enable_sticky_menu label').append('<span class="new-label">' + grapheneCustomizer.l10n.new + '</span>');
	// $('#customize-control-graphene_settings-container_style .customize-control-title').append('<span class="new-label">' + grapheneCustomizer.l10n.new + '</span>');
	// $('#customize-control-graphene_settings-mobile_left_column_first label').append('<span class="new-label">' + grapheneCustomizer.l10n.new + '</span>');
	// $('#customize-control-graphene_settings-social_media_location label').append('<span class="new-label">' + grapheneCustomizer.l10n.new + '</span>');
	// $('#customize-control-graphene_settings-slider_as_header label').append('<span class="new-label">' + grapheneCustomizer.l10n.new + '</span>');

	/* Confirm click */
	$(document).on( 'click', '.confirm-click', function(e){
		e.preventDefault();
		var msg = $(this).data('message');
		
		if ( confirm(msg) == true ){

			if ($(this).hasClass('graphene-reset-settings')){
				button = $(this);
				button.siblings('.status-icon').removeClass('hide');

				var data = {
					action		: 'graphene-reset-settings',
					nonce		: $(this).data('nonce'),
				};
				$.post(grapheneCustomizer.ajaxurl, data, function(response) {
					if (response == '0') {
						button.siblings('.status-icon').removeClass( 'fa-refresh fa-spin');
						button.siblings('.status-icon').addClass( 'fa-check');
						
						setTimeout(function(){location.reload();}, 500);
					} else {
						$('.graphene-status-message', button.parents('.customize-control')).append('<li>' + response + '</li>');
						button.siblings('.status-icon').addClass('hide');
					}
				});
			}
		}

		return false;
	});
	  
	/* Improve <select> elements */
	$('.chzn-select').each(function () {
		var chosenOptions = new Object();
		chosenOptions.disable_search_threshold = 10;
		chosenOptions.allow_single_deselect = true;
		chosenOptions.no_results_text = grapheneCustomizer.chosen_no_search_result;
		if ($(this).attr('multiple')) chosenOptions.width = '100%';
		else chosenOptions.width = '250px';

		$(this).chosen(chosenOptions);
	});
	
	$('.chzn-select').on('change', function(e,params){
		settingID = $(this).data('customize-setting-link');
		if ( $(':selected', $(this)).length == 0 ) {
			wp.customize( settingID, function ( obj ) {
				obj.set( '' );
			} );
		}
	});

	/* Show the panel description by default */
	$('#sub-accordion-panel-graphene-colours .customize-panel-description').show();

	/* Hide slider options until they're needed */
	grapheneHideSliderControls();
	$('#customize-control-graphene_settings-slider_type select').on('change', grapheneHideSliderControls );

	function grapheneHideSliderControls(){
		$('#customize-control-graphene_settings-slider_specific_posts, \
		#customize-control-graphene_settings-slider_specific_categories, \
		#customize-control-graphene_settings-slider_exclude_categories, \
		#customize-control-graphene_settings-slider_random_category_posts').hide();

		sliderPosts = $('#customize-control-graphene_settings-slider_type select').val();
		if ( sliderPosts == 'posts_pages' ) $('#customize-control-graphene_settings-slider_specific_posts').show();
		if ( sliderPosts == 'categories' ) 
			$('#customize-control-graphene_settings-slider_specific_categories, \
				#customize-control-graphene_settings-slider_exclude_categories, \
				#customize-control-graphene_settings-slider_random_category_posts').show();
	}


	/* Hide generic Customizer options until they're needed */
	grapheneHideControls();
	$('#customize-control-graphene_settings-hide_post_nav input').on('change', grapheneHideControls );

	function grapheneHideControls(){
		$('#customize-control-graphene_settings-post_nav_in_term').hide();
		if ( ! wp.customize.value('graphene_settings[hide_post_nav]')() ) $('#customize-control-graphene_settings-post_nav_in_term').show();
	}


	/**
	 * Generic jQuery UI Sliders
	 */
	$('.graphene-control-slider').each(function(){
		var target = '#' + $(this).data('target');
		$(this).slider({
			min: $(this).data('min'),
			max: $(this).data('max'),
			step: $(this).data('step'),
			value: $(this).data('value'),
			slide: function (event, ui) {
				$(target).val(ui.value).trigger('change');
			}
		});
	});


	/**
	 * jQuery UI Sliders for columns width
	 */
	var gutter = graphene_settings.gutter_width;
	var grid_width = parseFloat(graphene_settings.grid_width);
	var container_width = parseFloat(graphene_settings.container_width);
	var container = container_width - gutter * 2;
	var content_2col = parseFloat(graphene_settings.column_width.two_col.content);
	var sidebar_left_3col = parseFloat(graphene_settings.column_width.three_col.sidebar_left);
	var sidebar_right_3col = parseFloat(graphene_settings.column_width.three_col.sidebar_right);

	/* Container */
	$('#container_width-slider').slider({
		min: 800,
		max: 1400,
		step: 10,
		value: container_width,
		slide: function (event, ui) {
			$('#container_width').val(ui.value).trigger('change');
		}
	});

	/* Two-column mode */
	$('#column_width_2col-slider').slider({
		min: 0,
		max: 12,
		value: content_2col,
		step: 1,
		slide: function (event, ui) {
			sidebar_2col = 12 - ui.value;
			content_2col = ui.value;

			$("#column_width_2col_sidebar").val(sidebar_2col).trigger('change');
			$("#column_width_2col_content").val(content_2col).trigger('change');
		}
	});

	/* Three-column mode */
	$('#column_width-slider').slider({
		range: true,
		min: 0,
		max: 12,
		values: [sidebar_left_3col, 12 - sidebar_right_3col],
		step: 1,
		slide: function (event, ui) {
			sidebar_left = ui.values[0];
			sidebar_right = 12 - ui.values[1];
			content = 12 - sidebar_left - sidebar_right;

			$("#column_width_sidebar_left").val(sidebar_left).trigger('change');
			$("#column_width_sidebar_right").val(sidebar_right).trigger('change');
			$("#column_width_content").val(content).trigger('change');
		}
	});

	/* Trigger change when columns width are modified */
	$('#customize-control-graphene_settings-column_width input[type="text"]').change(function(){
		colWidths = {
			three_col: {
				sidebar_left 	: $("#column_width_sidebar_left").val(),
				content 		: $("#column_width_content").val(),
				sidebar_right 	: $("#column_width_sidebar_right").val(),
			},
			two_col: {
				content 		: $("#column_width_2col_content").val(),
				sidebar  		: $("#column_width_2col_sidebar").val(),
			}
		};
		$(this).parents('.customize-control').find('input[type="hidden"]').val(JSON.stringify(colWidths)).trigger('change');
	});



	/**
	 * Hide the widget hooks until the file name is clicked
	 */
	$('a.toggle-widget-hooks').click(function () {
		$(this).closest('li').find('li.widget-hooks').fadeToggle(0);
		return false;
	});

	/**
	 * Trigger change event when widget hooks is (de)selected
	 */
	$('.hooks-list input[type="checkbox"]').change(function(){
		checkboxValues = $(this).parents('.customize-control').find('input[type="checkbox"]:checked').map(function(){
                return this.value;
            }).get().join(',');
        $(this).parents('.customize-control').find('input[type="hidden"]').val(checkboxValues).trigger('change');
	});


	/* Media Uploader in custom controls */
	var customUploader, uploaderTarget;
    $(document).on('click', '.media-upload', function(e) {
        e.preventDefault();
 
        // Extend the wp.media object
		var uploaderOpts = {
			title	: 'Choose Image',
			library	: { type: 'image' },
            button	: { text: 'Choose Image' },
            multiple: false
		};
		if ( $(this).data('title') ) uploaderOpts.title = $(this).data('title');
		if ( $(this).data('button') ) uploaderOpts.button.text = $(this).data('button');
		if ( $(this).data('multiple') ) uploaderOpts.multiple = $(this).data('multiple');
        customUploader = wp.media.frames.file_frame = wp.media(uploaderOpts);
 
		fieldName = $(this).data('field');
		uploaderTarget = '#' + fieldName;
		
        customUploader.on('select', function() {
			attachment = customUploader.state().get('selection').first().toJSON();
			
			if ( fieldName.indexOf('brand_icon') === 0 ) {
				$(uploaderTarget).val(attachment.id).trigger('change');
				$('.image-preview', $(uploaderTarget).parent()).html('<img src="' + attachment.url + '" alt="' + attachment.alt + '" width="' + attachment.width + '" height="' + attachment.height + '" />');
			} else {
				$(uploaderTarget).val(attachment.url);
			}				
        });
 
        //Open the uploader dialog
        customUploader.open();
    });


    /**
     * Repeatable fields
     */
    $(document).on('click', '.repeatable-del', function(){
		repeatable = $(this).parents('.repeatable-item');
		repeatable.slideUp(200);
		setTimeout(function(){repeatable.remove(); $('.graphene-repeatable').trigger('change');}, 200);
	});

	$(document).on('focusout', '.add-repeatable-item input[type="text"]', function(){
		if ( $(this).val() ) {
			html = $('.add-repeatable-item', $(this).parents('.graphene-repeatable')).get(0).outerHTML;
			$(this).parents('.graphene-repeatable').append(html);
			$(this).parents('.repeatable-item').removeClass('add-repeatable-item');
			$(this).parents('.graphene-repeatable').trigger('change');
		}
	});

	$('.graphene-repeatable').change(function(){
		repeatableItems = [];
		$('.repeatable-item input[type="text"]').each(function(){
			if ($(this).val())repeatableItems.push($(this).val());
		});
		$(this).parents('.customize-control').find('.graphene-repeatable-setting-link').val(JSON.stringify(repeatableItems)).trigger('change');
	});


    /* 
     * Sortable fields 
    */
	$(document).on('click', '.sortable-del', function(){
		sortable = $(this).parents('.graphene-sortable');
		sortable.slideUp(200);
		setTimeout(function(){sortable.remove(); $('.graphene-sortables').trigger('change');}, 200);
	});

	/* Make the profiles sortable */
	$('.graphene-sortables').sortable({
		items: '.graphene-sortable',
		// placeholder: 'socialprofile-dragging',
		stop: function( event, ui ){
			$('.graphene-sortables').trigger('change');
		},
		opacity: .8
	});


	/**
	 * Social profiles
	 */

	/* Show hidden fields when new social type selected is custom */
	$('#new-socialprofile-type').change(function(){
		if ($(this).val() == 'custom') {
			$('#new-socialprofile-icon-url').val('').parent().removeClass('hide');
			$('#new-socialprofile-icon-name').val('').parent().removeClass('hide');
		} else {
			$('#new-socialprofile-icon-url').val('').parent().addClass('hide');
			$('#new-socialprofile-icon-name').val('').parent().addClass('hide');
		}
	});

	/* Add new social profile */
	$('.add-social-profile .button').click(function(e){
		e.preventDefault();

		profileType = $('#new-socialprofile-type').val();
		profileDesc = $('#new-socialprofile-title').val();
		profileUrl = $('#new-socialprofile-url').val();
		profileIconUrl = $('#new-socialprofile-icon-url').val();
		profileIconName = $('#new-socialprofile-icon-name').val();

		hideClass = 'hide';
		if ( profileType == 'custom' ) hideClass = '';

		html = '<li class="graphene-social-profile graphene-sortable ui-sortable-handle">\
                    <span class="customize-control-title">\<i class="fa fa-' + profileType + '"></i>' + profileType + '</span>\
                	<input type="hidden" name="social-profile-data" data-type="' + profileType + '" data-name="' + profileType + '" data-title="' + profileType + '" data-url="' + profileUrl + '" data-icon-url="' + profileIconUrl + '" data-icon-name="' + profileIconName + '">\
                    <div class="inline-field">\
                    	<label>Description</label>\
                    	<input type="text" data-key="title" value="' + profileDesc + '">\
                    </div>\
                    <div class="inline-field">\
                    	<label>URL</label>\
                    	<input type="text" data-key="url" value="' + profileUrl + '">\
                    </div>\
                    <div class="inline-field ' + hideClass + '">\
                    	<label>Icon URL</label>\
                    	<input type="text" data-key="icon-url" value="' + profileIconUrl + '">\
                    </div>\
                    <div class="inline-field ' + hideClass + '">\
                    	<label>Icon name</label>\
                    	<input type="text" data-key="icon-name" value="' + profileIconName + '">\
                    </div>\
                    <span class="delete"><a href="#" class="sortable-del"><i class="fa fa-times" title="' + grapheneCustomizer.l10n.delete + '"></i></a></span>\
                    <span class="move"><i class="fa fa-arrows" title="' + grapheneCustomizer.l10n.drag_drop + '"></i></span>\
                </li>';
        $('.graphene-social-profiles').append(html);

        $('#new-socialprofile-type').val('');
		$('#new-socialprofile-title').val('');
		$('#new-socialprofile-url').val('');
		$('#new-socialprofile-icon-url').val('').parent().addClass('hide');
		$('#new-socialprofile-icon-name').val('').parent().addClass('hide');

		$('.graphene-social-profiles').trigger('change');
	});

	/* Update the social profile values and trigger change */
	$(document).on('change', '.graphene-social-profile input', function(){
		key = $(this).data('key');
		$('input[name="social-profile-data"]', $(this).parents('.graphene-social-profile')).data(key, $(this).val());

		$('.graphene-social-profiles').trigger('change');
	});

	/* Update the values when change is triggered */
	$('.graphene-social-profiles').change(function(){
		profiles = [];
		$('input[name="social-profile-data"]').each(function(){
			profileType = $(this).data('type');
			profileName = $(this).data('name');
			profileDesc = $(this).data('title');
			profileUrl = $(this).data('url');
			profileIconUrl = $(this).data('icon-url');
			profileIconName = $(this).data('icon-name');

			socialProfile = {
				type 	: profileType,
				name 	: profileName,
				title 	: profileDesc,
				url 	: profileUrl,
				icon_url: profileIconUrl,
				icon_name: profileIconName
			};
			profiles.push(socialProfile);
		});

		$(this).parents('.customize-control').find('#graphene_settings_social_profiles').val(JSON.stringify(profiles)).trigger('change');
	});



	/**
	 * Mentions bar
	 */

	/* Add new brand-icon */
	$('.add-brand-icon .button-primary').click(function(e){
		e.preventDefault();
		brandIcon = $(this).parents('.add-brand-icon');

		if (window.grapheneBrandIconIndex == undefined) window.grapheneBrandIconIndex = $(this).data('count') + 1;
		else window.grapheneBrandIconIndex += 1;

		imageId = $('.new-brand-icon-image-id', brandIcon).val();
		image = $('.image-preview', brandIcon).html();
		linkUrl = $('#new-brand-icon-url').val();

		html = '<li class="brand-icon graphene-sortable ui-sortable-handle">\
                	<input type="hidden" name="brand-icon-data" data-image-id="' + imageId + '" data-url="' + linkUrl + '" />\
                	<div class="inline-field">\
                		<label>\
                			<a data-field="brand_icon_' + window.grapheneBrandIconIndex + '" data-title="' + grapheneCustomizer.l10n.select_image + '" data-button="' + grapheneCustomizer.l10n.select_image + '" href="#" class="media-upload button">' + grapheneCustomizer.l10n.select_image + '</a>\
                		</label>\
                    	<div class="image-preview">' + image + '</div>\
                        <input type="hidden" id="brand_icon_' + window.grapheneBrandIconIndex + '" data-key="image-id" value="' + imageId + '" />\
                    </div>\
                    <div class="inline-field">\
                    	<label>' + grapheneCustomizer.l10n.links_to + '</label>\
                    	<input type="text" data-key="url" value="' + linkUrl + '" />\
                    </div>\
                    <span class="delete"><a href="#" class="sortable-del"><i class="fa fa-times" title="' + grapheneCustomizer.delete + '"></i></a></span>\
                    <span class="move"><i class="fa fa-arrows" title="' + grapheneCustomizer.l10n.drag_drop + '"></i></span>\
                </li>';
        $('.graphene-brand-icons').append(html);

        $('#new-brand-icon-image-id').val('');
		$('#new-brand-icon-url').val('');
		$('.image-preview', brandIcon).html('<span class="image-placeholder"></span>');

		$('.graphene-brand-icons').trigger('change');
	});

	/* Update the brand icon values and trigger change */
	$(document).on('change', '.brand-icon input', function(){
		key = $(this).data('key');
		$('input[name="brand-icon-data"]', $(this).parents('.brand-icon')).data(key, $(this).val());

		$('.graphene-brand-icons').trigger('change');
	});

	/* Update the values when change is triggered */
	$('.graphene-brand-icons').change(function(){
		brandIcons = [];
		$('input[name="brand-icon-data"]').each(function(){
			imageId = $(this).data('image-id');
			linkUrl = $(this).data('url');

			brandIcon = {
				image_id: imageId,
				url 	: linkUrl,
			};
			brandIcons.push(brandIcon);
		});

		$(this).parents('.customize-control').find('#graphene_settings_brand_icons').val(JSON.stringify(brandIcons)).trigger('change');
	});



	/**
	 * Import theme files
	 */
	 $('form.graphene-import').submit(function(e){
	 	$('.status-icon', this).removeClass('hide');
	 });


	 /**
	  * Multiple checkbox control
	  */
    $('.customize-control-checkbox-multiple input[type="checkbox"]').on( 'change', function() {
        checkbox_values = $(this).parents('.customize-control').find('input[type="checkbox"]:checked').map(function(){return this.value;}).get().join( ',' );
        $(this).parents('.customize-control').find('input[type="hidden"]').val(checkbox_values).trigger('change');
    } );
});