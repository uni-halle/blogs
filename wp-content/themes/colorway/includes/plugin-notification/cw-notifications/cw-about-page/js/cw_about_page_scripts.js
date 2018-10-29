/////**
//// * Main scripts file for the About Colorway Page
//// *
//// * @package Colorway
//// */
////
/////* global tiAboutPageObject */
/////* global console */
////
//jQuery( document ).ready(
//	function () {
//
//		/* If there are required actions, add an icon with the number of required actions in the About cw-about-page page -> Actions required tab */
//		var cw_about_page_nr_actions_required = tiAboutPageObject.nr_actions_required;
//
//		if ( (typeof cw_about_page_nr_actions_required !== 'undefined') && (cw_about_page_nr_actions_required !== '0') ) {
//			jQuery( 'li.cw-about-page-w-red-tab a' ).append( '<span class="cw-about-page-actions-count">' + cw_about_page_nr_actions_required + '</span>' );
//		}
//
//		/* Dismiss required actions */
//		jQuery( '.cw-about-page-required-action-button' ).click(
//			function() {
//
//				var id = jQuery( this ).attr( 'id' ),
//				action = jQuery( this ).attr( 'data-action' );
//
//				jQuery.ajax(
//					{
//						type      : 'GET',
//						data      : { action: 'cw_about_page_dismiss_required_action', id: id, todo: action },
//						dataType  : 'html',
//						url       : tiAboutPageObject.ajaxurl,
//						beforeSend: function () {
//							jQuery( '.cw-about-page-tab-pane#actions_required h1' ).append( '<div id="temp_load" style="text-align:center"><img src="' + tiAboutPageObject.template_directory + '/includes/plugin-notification/cw-notifications/cw-about-page/images/ajax-loader.gif" /></div>' );
//						},
//						success   : function () {
//							location.reload();
//							jQuery( '#temp_load' ).remove();
//							/* Remove loading gif */
//						},
//						error     : function (jqXHR, textStatus, errorThrown) {
//							console.log( jqXHR + ' :: ' + textStatus + ' :: ' + errorThrown );
//						}
//					}
//				);
//			}
//		);
//		      // Remove activate button and replace with activation in progress button.
//            jQuery('.activate-now').on( "click", function() 
//                   {
//                        
//                        var activateButton = jQuery('.activate-now');
//                        if (activateButton.length) {
//                            var url = jQuery(activateButton).attr('href');
//                            if (typeof url !== 'undefined') {
//                                // Request plugin activation.
//                                jQuery.ajax(
//                                        {
//                                            beforeSend: function () {
//                                                jQuery(activateButton).replaceWith('<a class="button updating-message">' + tiCustomizerNotifyObject.activating_string + '...</a>');
//                                            },
//                                            async: true,
//                                            type: 'GET',
//                                            url: url,
//                                            success: function () {
//                                                // Reload the page.
//                                                location.reload();
//                                            }
//                                        }
//                                );
//                            }
//                        }
//                    }
//            );
//	}
//);
