<?php
/*
Adds the functionality that links in studylog will be redirected to their fitting destination.
External Links will open in a new window. Links from widgets will call the corresponding javascript to call the functionality in the flash UI.
*/

if(!isset($_REQUEST['nofl'])){
add_action('wp_head', 'redirectlinks_wp_header');
}
else
{
add_action('wp_head', 'redirectlinks__nofl_wp_header');
}


function redirectlinks__nofl_wp_header()
{
	$blogdomain = parse_url(get_settings('home'));
	
	echo "<script type=\"text/javascript\">
	<!--
	
	function addLocalRedirect() {
		//alert('/".$blogdomain['host']."/');
	    if (!document.links) {
			document.links = document.getElementsByTagName('a');
		}
		for (var t=0; t<document.links.length; t++)
		{
		    var patchlinks = document.links[t];
            if (patchlinks.href.search(/http/) != -1) 
            {
               if (patchlinks.href.search('/".$blogdomain['host']."/') != -1) 
               {
                   if (patchlinks.href.search(/fl=yes/) != -1) 
                   {
		    	     //do nothing
		           }
		           else 
		           {
		              patchlinks.href=patchlinks.href+'&nofl=yes';
		           }               
                }
                else if (patchlinks.href.search('/".$blogdomain['host']."/') == -1) 
                {
		    	   patchlinks.setAttribute('target', '_blank');
		    	}
            
            }
		 }
	    
	}
	
	
	function addLoadEvent(func)
	{	
		var oldonload = window.onload;
		if (typeof window.onload != 'function'){
			window.onload = func;
		} else {
			window.onload = function(){
				oldonload();
				func();
			}
		}
	}

	addLoadEvent(addLocalRedirect);	// adds the redirect mapping

	//-->
	</script>";
}


function redirectlinks_wp_header()
{
	$blogdomain = parse_url(get_settings('home'));
	
	echo "<script type=\"text/javascript\">
	<!--
	
	function addLocalRedirect() {
		if (!document.links) {
			document.links = document.getElementsByTagName('a');
		}

		for (var t=0; t<document.links.length; t++) {
			var patchlinks = document.links[t];
			
			if (patchlinks.href.search(/http/) != -1) {
		  	if (patchlinks.href.search('/".$blogdomain['host']."/') != -1) {
		  	  if (patchlinks.href.search(/m=/) != -1) {
		    	   var chronologicalLinkIndex = patchlinks.href.indexOf('?m=')+3;
		    	   var chronologicalLink = patchlinks.href.substring(chronologicalLinkIndex);
		    	   patchlinks.href='javascript:callStudyLogSortChronological('+chronologicalLink+')';
		       }
		       else if (patchlinks.href.search(/tag=/) != -1)
		       {
		           var decodedUri = decodeURIComponent(patchlinks.href);
		           var tagLinkIndex = decodedUri.indexOf('?tag=')+5;
		    	   var tagLink = '\"' +decodedUri.substring(tagLinkIndex)+ '\"';
		    	   patchlinks.href='javascript:parent.callStudyLogSortByTagName('+tagLink+')';
		       }
		       else if (patchlinks.href.search(/javascript/) != -1)
		       {
		       	 
		       }
		       else if (patchlinks.href.search(/nofl=yes/) != -1)
		       {
		       	 
		       }
		       else if (patchlinks.href.search(/fl=yes/) != -1)
		       {
		       	 
		       }
		       else if (patchlinks.href.search(/p=/) != -1)
		       {
		         if(patchlinks.href.search(/bookmark/) != -1)
		         {
		           patchlinks.setAttribute('target', '_parent');
		         }
		         else
		         {
		       	   patchlinks.href=patchlinks.href+'&tb_content=true';
		       	 }
		       }
		       else
		       { 
		       	 patchlinks.setAttribute('target', '_blank');
		       }
		    }
		    else if (patchlinks.href.search('/".$blogdomain['host']."/') == -1) {
		    	   patchlinks.setAttribute('target', '_blank');
		    }
		  }
		}
	}
	
	
	function addLoadEvent(func)
	{	
		var oldonload = window.onload;
		if (typeof window.onload != 'function'){
			window.onload = func;
		} else {
			window.onload = function(){
				oldonload();
				func();
			}
		}
	}

	addLoadEvent(addLocalRedirect);	// adds the redirect mapping

	//-->
	</script>";

//		$content = preg_replace("/\starget=\"_blank\"/Ui", "", $content);
//		$content = preg_replace("/<a\s(.*)>/Ui", "<a \\1 target=\"_blank\">", $content);

}
?>