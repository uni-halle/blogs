function staticSizeChanger()
{
	var width = window.innerWidth;
	var height = window.innerHeight;
	
	if(width < 1070)
	{
		turnSidebarTop();

		document.getElementById('body').style.padding = "0px";
		document.getElementById('main').style.width = "100%";
		document.getElementById('main').style.backgroundSize = "100% 1px";
		document.getElementById('cimy_div_id_0').style.width = "100%";
		document.getElementById('cimy_div_id_0').style.height = "";
		document.getElementById('header').style.width = "100%";
		document.getElementById("header_images").style.borderLeft = "0px";
		document.getElementById("header_images").style.width = "90%";
		document.getElementById("page").style.maxWidth = "100%";

		document.getElementById('topbar').style.zIndex = "1000";
		document.getElementById('topbar').style.margin = "0px 0% 0px 0px";
		
		document.getElementById("cimy_div_id_0").style.height = (0.8*width*238/799).toString()+"px";
		document.getElementById("topbar").style.height = (0.8*width*238/799).toString()+"px";
		document.getElementById('colophon').style.backgroundSize = "100% 1px";
		
	}
	else
	{
		turnSidebarLeft();
		
		document.getElementById('body').style.padding = "0 2em";
		document.getElementById('main').style.width = "799px";
		document.getElementById('main').style.backgroundSize = "";
		document.getElementById('cimy_div_id_0').style.width = "799px";
		document.getElementById('cimy_div_id_0').style.height = "";
		document.getElementById('header').style.width = "799px";
		document.getElementById("header_images").style.borderLeft = "1px solid #000";
		document.getElementById("header_images").style.width = "100%";
		document.getElementById("page").style.maxWidth = "998px";
		
		document.getElementById("topbar").style.margin= "0px 0.9% 0px 0px";
		
		document.getElementById("cimy_div_id_0").style.height = "238px";
		document.getElementById("topbar").style.height = "238px";
		document.getElementById('colophon').style.backgroundSize = "";
	}
	
	
	if(width < 480)
	{
		document.getElementById('topbar').style.width = "15%";
		document.getElementById('header_images').style.width = "85%";
		document.getElementById('topwidgetarea').style.left = "15%";
	}
	else
	{
		document.getElementById('topbar').style.width = "10%";
		document.getElementById('header_images').style.width = "90%";
	}
}

function turnSidebarTop()
{
	document.getElementById("topbar").style.display = "block";
	document.getElementById("sidebar").style.display = "none";
}

function turnSidebarLeft()
{
	document.getElementById("topbar").style.display = "none";
	document.getElementById("sidebar").style.display = "block";
}

function resizer()
{
	staticSizeChanger();
}

function pageLoad()
{
}