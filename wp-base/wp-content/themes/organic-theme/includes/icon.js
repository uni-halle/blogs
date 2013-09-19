	window.onload=function()
	{
		var 
		base='../wp-content/themes/organic-theme/img/logos/',
		current = "$current";
		thumb=document.getElementById('thumb'),
		sel=document.getElementById('organic_theme_logo');
		
		sel.onchange=function()
		{
		thumb.src=base+this.value+'.gif';
		}
		thumb.src=base+current+'.gif';
	}