function hide_all()
{
	for(var i=3; i<=6; i++)
	{
		document.getElementById("cat-"+i).style.display = "none";	
	}
}

function show_category(id)
{
	hide_all();
	document.getElementById("cat-"+id).style.display = "block";
	
}