(function( $ ) {
	'use strict';
	
	var that;
	
	var fgj2wp = {
	    
	    plugin_id: 'fgj2wp',
	    fatal_error: '',
	    
	    /**
	     * Manage the behaviour of the Skip Media checkbox
	     */
	    hide_unhide_media: function()  {
		$("#media_import_box").toggle(!$("#skip_media").is(':checked'));
	    },

	    /**
	     * Security question before deleting WordPress content
	     */
	    check_empty_content_option: function () {
		var confirm_message;
		var action = $('input:radio[name=empty_action]:checked').val();
		switch ( action ) {
		    case 'newposts':
			confirm_message = objectL10n.delete_new_posts_confirmation_message;
			break;
		    case 'all':
			confirm_message = objectL10n.delete_all_confirmation_message;
			break;
		    default:
			alert(objectL10n.delete_no_answer_message);
			return false;
			break;
		}
		return confirm(confirm_message);
	    },
	    
	    /**
	     * Start the logger
	     */
	    start_logger: function() {
		that.stop_logger_triggered = false;
		clearTimeout(that.timeout);
		that.timeout = setTimeout(that.update_display, 1000);
	    },
	    
	    /**
	     * Stop the logger
	     */
	    stop_logger: function() {
		that.stop_logger_triggered = true;
	    },
	    
	    
	    /**
	     * Update the display
	     */
	    update_display: function() {
		that.timeout = setTimeout(that.update_display, 1000);
		
		// Actions
		if ( $("#logger_autorefresh").is(":checked") ) {
		    that.display_logs();
		}
		that.update_progressbar();
		that.update_wordpress_info();
		
		if ( that.stop_logger_triggered ) {
		    clearTimeout(that.timeout);
		}
	    },
	    
	    /**
	     * Display the logs
	     */
	    display_logs: function() {
		$.ajax({
		    url: objectPlugin.log_file_url,
		    cache: false
		}).done(function(result) {
		    $("#logger").html('');
		    result.split("\n").forEach(function(row) {
			if ( row.substr(0, 7) === '[ERROR]' || row === 'IMPORT STOPPED BY USER') {
			    row = '<span class="error_msg">' + row + '</span>'; // Mark the errors in red
			}
			// Test if the import is complete
			else if ( row === 'IMPORT COMPLETE' ) {
			    row = '<span class="complete_msg">' + row + '</span>'; // Mark the complete message in green
			    $('#action_message').html(objectL10n.import_complete)
			    .removeClass('failure').addClass('success');
			}
			$("#logger").append(row + "<br />\n");
			
		    });
		    $("#logger").append('<span class="error_msg">' + that.fatal_error + '</span>' + "<br />\n");
		});
	    },

	    /**
	     * Update the progressbar
	     */
	    update_progressbar: function() {
		$.ajax({
		    url: objectPlugin.progress_url,
		    cache: false,
		    dataType: 'json'
		}).done(function(result) {
		    // Move the progress bar
		    var progress = Number(result.current) / Number(result.total) * 100;
		    $('#progressbar').progressbar('option', 'value', progress);
		});
	    },

	    /**
	     * Update WordPress database info
	     */
	    update_wordpress_info: function() {
		var data = 'action=' + that.plugin_id + '_import&plugin_action=update_wordpress_info';
		$.ajax({
		    method: "POST",
		    url: ajaxurl,
		    data: data
		}).done(function(result) {
		    $('#fgj2wp_database_info_content').html(result);
		});
	    },
	    
	    /**
	     * Empty WordPress content
	     * 
	     * @returns {Boolean}
	     */
	    empty_wp_content: function() {
		if (that.check_empty_content_option()) {
		    // Start displaying the logs
		    that.start_logger();
		    $('#empty').attr('disabled', 'disabled'); // Disable the button
		    
		    var data = $('#form_empty_wordpress_content').serialize() + '&action=' + that.plugin_id + '_import&plugin_action=empty';
		    $.ajax({
			method: "POST",
			url: ajaxurl,
			data: data
		    }).done(function() {
			that.stop_logger();
			$('#empty').removeAttr('disabled'); // Enable the button
			alert(objectL10n.content_removed_from_wordpress);
		    });
		}
		return false;
	    },
	    
	    /**
	     * Test the database connection
	     * 
	     * @returns {Boolean}
	     */
	    test_database: function() {
		// Start displaying the logs
		that.start_logger();
		$('#test_database').attr('disabled', 'disabled'); // Disable the button
		
		var data = $('#form_import').serialize() + '&action=' + that.plugin_id + '_import&plugin_action=test_database';
		$.ajax({
		    method: 'POST',
		    url: ajaxurl,
		    data: data,
		    dataType: 'json'
		}).done(function(result) {
		    that.stop_logger();
		    $('#test_database').removeAttr('disabled'); // Enable the button
		    if ( typeof result.message !== 'undefined' ) {
			$('#database_test_message').toggleClass('success', result.status === 'OK')
			.toggleClass('failure', result.status !== 'OK')
			.html(result.message);
		    }
		}).fail(function(result) {
		    that.stop_logger();
		    $('#test_database').removeAttr('disabled'); // Enable the button
		    that.fatal_error = result.responseText;
		});
		return false;
	    },
	    
	    /**
	     * Test the FTP connection
	     * 
	     * @returns {Boolean}
	     */
	    test_ftp: function() {
		// Start displaying the logs
		that.start_logger();
		$('#test_ftp').attr('disabled', 'disabled'); // Disable the button
		
		var data = $('#form_import').serialize() + '&action=' + that.plugin_id + '_import&plugin_action=test_ftp';
		$.ajax({
		    method: 'POST',
		    url: ajaxurl,
		    data: data,
		    dataType: 'json'
		}).done(function(result) {
		    that.stop_logger();
		    $('#test_ftp').removeAttr('disabled'); // Enable the button
		    if ( typeof result.message !== 'undefined' ) {
			$('#ftp_test_message').toggleClass('success', result.status === 'OK')
			.toggleClass('failure', result.status !== 'OK')
			.html(result.message);
		    }
		}).fail(function(result) {
		    that.stop_logger();
		    $('#test_ftp').removeAttr('disabled'); // Enable the button
		    that.fatal_error = result.responseText;
		});
		return false;
	    },
	    
	    /**
	     * Save the settings
	     * 
	     * @returns {Boolean}
	     */
	    save: function() {
		// Start displaying the logs
		that.start_logger();
		$('#save').attr('disabled', 'disabled'); // Disable the button
		
		var data = $('#form_import').serialize() + '&action=' + that.plugin_id + '_import&plugin_action=save';
		$.ajax({
		    method: "POST",
		    url: ajaxurl,
		    data: data
		}).done(function() {
		    that.stop_logger();
		    $('#save').removeAttr('disabled'); // Enable the button
		    alert(objectL10n.settings_saved);
		});
		return false;
	    },
	    
	    /**
	     * Start the import
	     * 
	     * @returns {Boolean}
	     */
	    start_import: function() {
		that.fatal_error = '';
		// Start displaying the logs
		that.start_logger();
		
		// Disable the import button
		that.import_button_label = $('#import').val();
		$('#import').val(objectL10n.importing).attr('disabled', 'disabled');
		// Show the stop button
		$('#stop-import').show();
		// Clear the action message
		$('#action_message').html('');
		
		// Run the import
		var data = $('#form_import').serialize() + '&action=' + that.plugin_id + '_import&plugin_action=import';
		$.ajax({
		    method: "POST",
		    url: ajaxurl,
		    data: data
		}).done(function(result) {
		    if (result) {
			that.fatal_error = result;
		    }
		    that.stop_logger();
		    that.reactivate_import_button();
		});
		return false;
	    },
	    
	    /**
	     * Reactivate the import button
	     * 
	     */
	    reactivate_import_button: function() {
		$('#import').val(that.import_button_label).removeAttr('disabled');
		$('#stop-import').hide();
	    },
	    
	    /**
	     * Stop import
	     * 
	     * @returns {Boolean}
	     */
	    stop_import: function() {
		$('#stop-import').attr('disabled', 'disabled');
		$('#action_message').html(objectL10n.import_stopped_by_user)
		.removeClass('success').addClass('failure');
		// Stop the import
		var data = $('#form_import').serialize() + '&action=' + that.plugin_id + '_import&plugin_action=stop_import';
		$.ajax({
		    method: "POST",
		    url: ajaxurl,
		    data: data
		}).done(function() {
		    $('#stop-import').removeAttr('disabled'); // Enable the button
		    that.reactivate_import_button();
		});
		return false;
	    },
	    
	    /**
	     * Modify the internal links
	     * 
	     * @returns {Boolean}
	     */
	    modify_links: function() {
		// Start displaying the logs
		that.start_logger();
		$('#modify_links').attr('disabled', 'disabled'); // Disable the button
		
		var data = $('#form_modify_links').serialize() + '&action=' + that.plugin_id + '_import&plugin_action=modify_links';
		$.ajax({
		    method: "POST",
		    url: ajaxurl,
		    data: data
		}).done(function(result) {
		    if (result) {
			that.fatal_error = result;
		    }
		    that.stop_logger();
		    $('#modify_links').removeAttr('disabled'); // Enable the button
		    alert(objectL10n.internal_links_modified);
		});
		return false;
	    }
	    
	};
	
	/**
	 * Actions to run when the DOM is ready
	 */
	$(function() {
	    that = fgj2wp;
	    
	    $('#progressbar').progressbar({value : 0});

	    // Skip media checkbox
	    $("#skip_media").bind('click', that.hide_unhide_media);
	    that.hide_unhide_media();

	    // Empty WordPress content confirmation
	    $("#form_empty_wordpress_content").bind('submit', that.check_empty_content_option);

	    // Partial import checkbox
	    $("#partial_import").hide();
	    $("#partial_import_toggle").click(function() {
		$("#partial_import").slideToggle("slow");
	    });
	    
	    // Empty button
	    $('#empty').click(that.empty_wp_content);
	    
	    // Test database button
	    $('#test_database').click(that.test_database);
	    
	    // Test FTP button
	    $('#test_ftp').click(that.test_ftp);
	    
	    // Save settings button
	    $('#save').click(that.save);
	    
	    // Import button
	    $('#import').click(that.start_import);
	    
	    // Stop import button
	    $('#stop-import').click(that.stop_import);
	    
	    // Modify links button
	    $('#modify_links').click(that.modify_links);
	    
	    // Display the logs
	    $('#logger_autorefresh').click(that.display_logs);
	});

	/**
	 * Actions to run when the window is loaded
	 */
	$( window ).load(function() {
	    
	});

})( jQuery );
