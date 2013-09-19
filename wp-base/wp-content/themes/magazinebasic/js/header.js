var oldOnload = window.onload;

if(typeof oldOnload == "function") {
	window.onload = function() {
		if(oldOnload) {
			oldOnload();
		}
		showMe();
	}
} else {
	window.onload = showMe;
}

function showMe() {
		var logo = document.forms['myForm'].uwc_logo_header;
		var logoLength = logo.length;
		
		for (i = 0; i <logoLength; i++) {
			if (logo[i].checked) {
				var logoPick = logo[i].value
			}
		}
		
		if (logoPick == "no") {
			document.getElementById("headerLogo").style.display = "none";
		} else {
			document.getElementById("headerLogo").style.display = "block";
		}

		var location = document.forms['myForm'].uwc_logo_location;
		var locationLength = location.length;

		for (i = 0; i <locationLength; i++) {
			if (location[i].checked) {
				var locationPick = location[i].value
			}
		}
		
		if (locationPick == "middle") {
			document.getElementById("noSearch").style.display = "block";
			document.getElementById("searchHeader").style.display = "none";
		} else {
			document.getElementById("searchHeader").style.display = "block";
			document.getElementById("noSearch").style.display = "none";
		}
}