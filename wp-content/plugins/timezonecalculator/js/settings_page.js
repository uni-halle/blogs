/*
hide all option-page sections
*/

function timezonecalculator_hide_sections() {
	for (var i=0; i<timezonecalculator_sections.length; i++) {
		jQuery('#timezonecalculator_'+timezonecalculator_sections[i]+'_link').removeClass('current');
		jQuery('#timezonecalculator_'+timezonecalculator_sections[i]).css('display', 'none');
	}
}

/*
opens admin-menu section
*/

function timezonecalculator_open_section(section) {
	timezonecalculator_hide_sections();

	var my_section='';

	if (section.length>0) {
		for (var i=0;i<timezonecalculator_sections.length;i++) {
			if (timezonecalculator_sections[i]==section) {
				my_section=section;
				break;
			}
		}
	}

	if (my_section.length===0)
		my_section=timezonecalculator_sections[0];

	jQuery('#timezonecalculator_'+my_section).css('display', 'block');
	jQuery('#timezonecalculator_'+my_section+'_link').addClass('current');
	jQuery('#timezonecalculator_section').val(my_section);
}

/*
- shows section-links only if menu is visible

- hides settings-page-menu and
displays all settings-page-sections
except selection_gui

if viewport < 440px
*/

function timezonecalculator_resize_settings_page() {
	if (jQuery(window).width()<440) {
		if (jQuery('#timezonecalculator_menu').is(':visible')) {
			jQuery('.timezonecalculator_section_link').hide();
			jQuery('.timezonecalculator_section_text').show();

			jQuery('#timezonecalculator_menu').hide();
			jQuery('#timezonecalculator_form_settings > div').show();
			jQuery('#timezonecalculator_selection_gui').hide();
		}
	}

	else {
		if (!jQuery('#timezonecalculator_menu').is(':visible')) {
			jQuery('.timezonecalculator_section_text').hide();
			jQuery('.timezonecalculator_section_link').show();

			jQuery('#timezonecalculator_menu').show();
			timezonecalculator_open_section(jQuery('#timezonecalculator_section').val());
		}
	}
}