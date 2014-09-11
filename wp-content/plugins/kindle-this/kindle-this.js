/*
Plugin Name: Kindle This
Plugin URI: http://www.blogseye.com
Description: Sends a blog post or page to a user's kindle.
Author: Keith P. Graham
Version: 2.3
Requires at least: 2.9
Author URI: http://www.blogseye.com
Tested up to: 3.3.1
Donate link: http://www.blogseye.com/buy-the-book/

*/


var kpg_kindle_count=0;

function kpg_kindle_it(t) {
	// this is the function triggered by submitting the form
	// this has to do the ajaxload function.
	// first thing is to check the data coming in to make sure that it is correct.
	if (t.kindle_email.value==""||t.kindle_email.value=="your-id") {
		alert("Please enter a valid Kindle ID");
		t.kindle_email.focus();
		return false;
	}
	if (t.from_email.value==""||t.from_email.value=="good@email") {
		alert("Please enter a valid Approved E-mail");
		t.from_email.focus();
		return false;
	}
	// need to build the GET command line for the ajax-admin call
	var url=t.kpg_kindle_aurl.value+"?action=kindle_this";
	// get the radio button value.
	var kindlecom='kindle.com';
	
	//alert ("hey");
	for (k=0;k<t.kindlecom.length;k++) {
		if (t.kindlecom[k].checked) {
			kindlecom=t.kindlecom[k].value;
		}
	}
	// build the parameters
	url=url+"&kindle_email="+t.kindle_email.value;
	url=url+"&from_email="+t.from_email.value;
	url=url+"&kindlecom="+escape(kindlecom);
	url=url+"&postarray="+t.kpg_kindle_posts.value;
	url=url+"&kindletitle="+escape(document.title);
	url=url+"&kindleloc="+escape(document.location);
	url=url+"&kindlethis_nonce="+t.kpg_kindle_nonce.value;
	// sent message here
	kpg_kindle_count=t.kpg_kindle_count.value;
	var kid=document.getElementById("kpg_kc_"+kpg_kindle_count);
	kid.innerHTML="Sending Document to Kindle</br>";
	kjaxLoad(url,kjax_setData);
	return false;
}
function kjaxObject() {
   this.handler=null;this.done=false;this.url="";this.where="";
}
var kjax_reqs=new Array();

function kjaxLoad(url,handler) {
	// handler is the procedure that will deal with the data
    // in my tests this is kjaxLoad("testdata.txt,kjax_setData);
	// testdata.txt is any xml formatted html file - it will eventually be a program to porduce the xhtml
	// kjax_setData is the generic innerHTML setter below
	// kbox is the id of a div where I put the results.
	// create a handler object for this request
    try {
		var j=0;
		for (j=0;j<kjax_reqs.length;j++) {
			if (kjax_reqs[j].done) 
				break;
		}
		kjax_reqs[j]=new kjaxObject();	
		kjax_reqs[j].done=false;
		kjax_reqs[j].url=url;
		kjax_reqs[j].handler=handler;
		if (window.XMLHttpRequest) {
			kjax_reqs[j].req=new XMLHttpRequest();
			kjax_reqs[j].req.onreadystatechange = kjax_processReqChange;
			kjax_reqs[j].req.open("GET", url, true);
			kjax_reqs[j].req.send(null);
		} else if (window.ActiveXObject) {
			kjax_reqs[j].req=new ActiveXObject("Microsoft.XMLHTTP");
			kjax_reqs[j].req.onreadystatechange = kjax_processReqChange;
			kjax_reqs[j].req.open("GET", url, true);
			kjax_reqs[j].req.send();
		}
	} catch (e) {
		alert("ajax load error:"+e);
	}
}
function kjax_processReqChange() {
 	var j=0;
        try {
	for (j=0;j<kjax_reqs.length;j++) {
		if (!kjax_reqs[j].done) {
			if (kjax_reqs[j].req.readyState == 4) {
				if (kjax_reqs[j].req.status == 200||kjax_reqs[j].req.status == 0) {
					kjax_reqs[j].done=true;
					kjax_reqs[j].handler(kjax_reqs[j].req.responseText);
				 } else {
					kjax_reqs[j].done=true;
					kjax_reqs[j].handler("Failed"); // sends back a blank
				 }
			}
		}
	}
        } catch (e) {
            alert("ajax process error:"+e);
        }
}
function kjax_setData(s) {
	var kid=document.getElementById("kpg_kc_"+kpg_kindle_count);
	kid.innerHTML="Returned from Sending Document to Kindle<br/>";
	try {
		var idsc;
		alert(s);
	} catch (e) {
		alert("ajax set data error:"+e);
	}
}
