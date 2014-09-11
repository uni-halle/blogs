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
		var site = document.forms['myForm'].uwc_site_width;
		var siteLength = site.length;

		var sidebar = document.forms['myForm'].uwc_site_sidebars;
		var sidebarLength = sidebar.length;

		for (i = 0; i <siteLength; i++) {
			if (site[i].checked) {
				var sitePick = site[i].value
			}
		}
		
		for (i = 0; i <sidebarLength; i++) {
			if (sidebar[i].checked) {
				var sidebarPick = sidebar[i].value
			}
		}
		
		if (sitePick == "800") {
			document.getElementById("leftWidth").style.display = "none";
		} else {
			document.getElementById("leftWidth").style.display = "block";
		}
		
		if (sidebarPick == "2" && sitePick == "1024") {
			document.getElementById("rightWidth").style.display = "block";
		} else {
			document.getElementById("rightWidth").style.display = "none";			
		}
		
		if (sidebarPick == "2") {
			document.getElementById("twoSidebar").style.display = "block";
			document.getElementById("oneSidebar").style.display = "none";
		} else {
			document.getElementById("twoSidebar").style.display = "none";
			document.getElementById("oneSidebar").style.display = "block";
		}		
}