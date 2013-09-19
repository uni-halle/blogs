/*
hide all option-page sections
*/

function timezonecalculator_hide_sections() {
	for (var i=0;i<timezonecalculator_sections.length;i++) {
		$('timezonecalculator_'+timezonecalculator_sections[i]).style.display="none";
		$('timezonecalculator_'+timezonecalculator_sections[i]+'_link').className="";
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

	$('timezonecalculator_'+my_section).style.display="block";
	$('timezonecalculator_'+my_section+'_link').className="current";
	$('timezonecalculator_section').value=my_section;
}