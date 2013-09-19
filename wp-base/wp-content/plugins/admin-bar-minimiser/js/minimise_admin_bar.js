jQuery(document).ready(function($) {
	
	$('#wpadminbar').append('<div title="Show/Hide Admin Bar" id="adminbar_tab">Show</div>');

	$('#adminbar_tab').click(function () {
		var cssTop = parseInt( $("#wpadminbar").css("top") );
		//alert(cssTop);

		if ($('body').is('.wp-admin')) { // wp-admin
			if (cssTop >= 0) {
				$("#wpadminbar").animate({"top": "-=28px"}, "slow");
				$("#adminbar_tab").text("Show");

				$("body.admin-bar #wphead").css("cssText", "padding-top: 0px;");
			}
			else {
				$("#wpadminbar").animate({"top": "+=28px"}, "slow");
				$("#adminbar_tab").text("Hide");

				$("body.admin-bar #wphead").css("cssText", "padding-top: 28px;");
			}
		} else { // front end
			if (cssTop >= 0) {
				$("#wpadminbar").animate({"top": "-=28px"}, "slow");
				$("#adminbar_tab").text("Show");

				$("html").css("cssText", "margin-top: 0px !important;");
			}
			else {
				$("#wpadminbar").animate({"top": "+=28px"}, "slow");
				$("#adminbar_tab").text("Hide");

				$("html").css("cssText", "margin-top: 28px !important;");
			}
		}
	});

});