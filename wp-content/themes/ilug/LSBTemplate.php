<?php /* Template Name: LSBTemplate */ ?><?php
	
	if(isset($_GET["sendrequest"]))
	{
		if(!isset($_POST["data"]))
			die("ERROR_NO_DATA");

		$data = $_POST["data"];
		
		$postdata = http_build_query(
			array('data' => $data)
		);

		$opts = array('http' =>
			array(
				'method'  => 'POST',
				'header'  => 'Content-type: application/x-www-form-urlencoded',
				'content' => $postdata
			)
		);

		$context  = stream_context_create($opts);
		$result = file_get_contents('http://www.hottenrott.info/apis/index.php?key=abc', false, $context);
		
		echo $result;
		
		die();
	}
?>



	<!DOCTYPE html>
	<html>
	<head>
	<meta charset="utf-8"/>
	<title>
	2. Sportkongress des LSB Sachsen-Anhalt e.V.
	</title>
	<meta content="width=device-width, initial-scale=1" name="viewport"/>
	<!--<link href="https://hottenrott.info/lsbimg/style.css" rel="stylesheet" type="text/css"/>-->
	<script src="https://ajax.googleapis.com/ajax/libs/webfont/1.4.7/webfont.js" type="text/javascript">
	</script>
	<script type="text/javascript">
	WebFont.load({  google: {    families: ["Open Sans:300,300italic,400,400italic,600,600italic,700,700italic,800,800italic","Roboto:300,regular,500"]  }});
	</script>
	<!--[if lt IE 9]>
	<script src="https://cdnjs.cloudflare.com/ajax/libs/html5shiv/3.7.3/html5shiv.min.js" type="text/javascript">
	</script>
	<![endif]-->
	<script type="text/javascript">
	!function(o,c){var n=c.documentElement,t=" w-mod-";n.className+=t+"js",("ontouchstart"in o||o.DocumentTouch&&c instanceof DocumentTouch)&&(n.className+=t+"touch")}(window,document);
	</script>
	<!--<link href="https://hottenrott.info/lsbimg/favicon_32x32.ico" rel="shortcut icon" type="image/x-icon"/>-->
	<link href="https://daks2k3a4ib2z.cloudfront.net/img/webclip.png" rel="apple-touch-icon"/>
	
	
	<style>
/* normalize.css v3.0.3 | MIT License | github.com/necolas/normalize.css */html{font-family:sans-serif;-ms-text-size-adjust:100%;-webkit-text-size-adjust:100%}body{margin:0}article,aside,details,figcaption,figure,footer,header,hgroup,main,menu,nav,section,summary{display:block}audio,canvas,progress,video{display:inline-block;vertical-align:baseline}audio:not([controls]){display:none;height:0}[hidden],template{display:none}a{background-color:transparent}a:active,a:hover{outline:0}abbr[title]{border-bottom:1px dotted}b,strong{font-weight:bold}dfn{font-style:italic}h1{font-size:2em;margin:.67em 0}mark{background:#ff0;color:#000}small{font-size:80%}sub,sup{font-size:75%;line-height:0;position:relative;vertical-align:baseline}sup{top:-0.5em}sub{bottom:-0.25em}img{border:0}svg:not(:root){overflow:hidden}figure{margin:1em 40px}hr{box-sizing:content-box;height:0}pre{overflow:auto}code,kbd,pre,samp{font-family:monospace,monospace;font-size:1em}button,input,optgroup,select,textarea{color:inherit;font:inherit;margin:0}button{overflow:visible}button,select{text-transform:none}button,html input[type="button"],input[type="reset"]{-webkit-appearance:button;cursor:pointer}button[disabled],html input[disabled]{cursor:default}button::-moz-focus-inner,input::-moz-focus-inner{border:0;padding:0}input{line-height:normal}input[type="checkbox"],input[type="radio"]{box-sizing:border-box;padding:0}input[type="number"]::-webkit-inner-spin-button,input[type="number"]::-webkit-outer-spin-button{height:auto}input[type="search"]{-webkit-appearance:none}input[type="search"]::-webkit-search-cancel-button,input[type="search"]::-webkit-search-decoration{-webkit-appearance:none}fieldset{border:1px solid silver;margin:0 2px;padding:.35em .625em .75em}legend{border:0;padding:0}textarea{overflow:auto}optgroup{font-weight:bold}table{border-collapse:collapse;border-spacing:0}td,th{padding:0}@font-face{font-family:'webflow-icons';src:url("data:application/x-font-ttf;charset=utf-8;base64,AAEAAAALAIAAAwAwT1MvMg8SBiUAAAC8AAAAYGNtYXDpP+a4AAABHAAAAFxnYXNwAAAAEAAAAXgAAAAIZ2x5ZmhS2XEAAAGAAAADHGhlYWQTFw3HAAAEnAAAADZoaGVhCXYFgQAABNQAAAAkaG10eCe4A1oAAAT4AAAAMGxvY2EDtALGAAAFKAAAABptYXhwABAAPgAABUQAAAAgbmFtZSoCsMsAAAVkAAABznBvc3QAAwAAAAAHNAAAACAAAwP4AZAABQAAApkCzAAAAI8CmQLMAAAB6wAzAQkAAAAAAAAAAAAAAAAAAAABEAAAAAAAAAAAAAAAAAAAAABAAADpAwPA/8AAQAPAAEAAAAABAAAAAAAAAAAAAAAgAAAAAAADAAAAAwAAABwAAQADAAAAHAADAAEAAAAcAAQAQAAAAAwACAACAAQAAQAg5gPpA//9//8AAAAAACDmAOkA//3//wAB/+MaBBcIAAMAAQAAAAAAAAAAAAAAAAABAAH//wAPAAEAAAAAAAAAAAACAAA3OQEAAAAAAQAAAAAAAAAAAAIAADc5AQAAAAABAAAAAAAAAAAAAgAANzkBAAAAAAEBIAAAAyADgAAFAAAJAQcJARcDIP5AQAGA/oBAAcABwED+gP6AQAABAOAAAALgA4AABQAAEwEXCQEH4AHAQP6AAYBAAcABwED+gP6AQAAAAwDAAOADQALAAA8AHwAvAAABISIGHQEUFjMhMjY9ATQmByEiBh0BFBYzITI2PQE0JgchIgYdARQWMyEyNj0BNCYDIP3ADRMTDQJADRMTDf3ADRMTDQJADRMTDf3ADRMTDQJADRMTAsATDSANExMNIA0TwBMNIA0TEw0gDRPAEw0gDRMTDSANEwAAAAABAJ0AtAOBApUABQAACQIHCQEDJP7r/upcAXEBcgKU/usBFVz+fAGEAAAAAAL//f+9BAMDwwAEAAkAABcBJwEXAwE3AQdpA5ps/GZsbAOabPxmbEMDmmz8ZmwDmvxmbAOabAAAAgAA/8AEAAPAAB0AOwAABSInLgEnJjU0Nz4BNzYzMTIXHgEXFhUUBw4BBwYjNTI3PgE3NjU0Jy4BJyYjMSIHDgEHBhUUFx4BFxYzAgBqXV6LKCgoKIteXWpqXV6LKCgoKIteXWpVSktvICEhIG9LSlVVSktvICEhIG9LSlVAKCiLXl1qal1eiygoKCiLXl1qal1eiygoZiEgb0tKVVVKS28gISEgb0tKVVVKS28gIQABAAABwAIAA8AAEgAAEzQ3PgE3NjMxFSIHDgEHBhUxIwAoKIteXWpVSktvICFmAcBqXV6LKChmISBvS0pVAAAAAgAA/8AFtgPAADIAOgAAARYXHgEXFhUUBw4BBwYHIxUhIicuAScmNTQ3PgE3NjMxOAExNDc+ATc2MzIXHgEXFhcVATMJATMVMzUEjD83NlAXFxYXTjU1PQL8kz01Nk8XFxcXTzY1PSIjd1BQWlJJSXInJw3+mdv+2/7c25MCUQYcHFg5OUA/ODlXHBwIAhcXTzY1PTw1Nk8XF1tQUHcjIhwcYUNDTgL+3QFt/pOTkwABAAAAAQAAmM7nP18PPPUACwQAAAAAANciZKUAAAAA1yJkpf/9/70FtgPDAAAACAACAAAAAAAAAAEAAAPA/8AAAAW3//3//QW2AAEAAAAAAAAAAAAAAAAAAAAMBAAAAAAAAAAAAAAAAgAAAAQAASAEAADgBAAAwAQAAJ0EAP/9BAAAAAQAAAAFtwAAAAAAAAAKABQAHgAyAEYAjACiAL4BFgE2AY4AAAABAAAADAA8AAMAAAAAAAIAAAAAAAAAAAAAAAAAAAAAAAAADgCuAAEAAAAAAAEADQAAAAEAAAAAAAIABwCWAAEAAAAAAAMADQBIAAEAAAAAAAQADQCrAAEAAAAAAAUACwAnAAEAAAAAAAYADQBvAAEAAAAAAAoAGgDSAAMAAQQJAAEAGgANAAMAAQQJAAIADgCdAAMAAQQJAAMAGgBVAAMAAQQJAAQAGgC4AAMAAQQJAAUAFgAyAAMAAQQJAAYAGgB8AAMAAQQJAAoANADsd2ViZmxvdy1pY29ucwB3AGUAYgBmAGwAbwB3AC0AaQBjAG8AbgBzVmVyc2lvbiAxLjAAVgBlAHIAcwBpAG8AbgAgADEALgAwd2ViZmxvdy1pY29ucwB3AGUAYgBmAGwAbwB3AC0AaQBjAG8AbgBzd2ViZmxvdy1pY29ucwB3AGUAYgBmAGwAbwB3AC0AaQBjAG8AbgBzUmVndWxhcgBSAGUAZwB1AGwAYQByd2ViZmxvdy1pY29ucwB3AGUAYgBmAGwAbwB3AC0AaQBjAG8AbgBzRm9udCBnZW5lcmF0ZWQgYnkgSWNvTW9vbi4ARgBvAG4AdAAgAGcAZQBuAGUAcgBhAHQAZQBkACAAYgB5ACAASQBjAG8ATQBvAG8AbgAuAAAAAwAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAA==") format('truetype');font-weight:normal;font-style:normal}[class^="w-icon-"],[class*=" w-icon-"]{font-family:'webflow-icons' !important;speak:none;font-style:normal;font-weight:normal;font-variant:normal;text-transform:none;line-height:1;-webkit-font-smoothing:antialiased;-moz-osx-font-smoothing:grayscale}.w-icon-slider-right:before{content:"\e600"}.w-icon-slider-left:before{content:"\e601"}.w-icon-nav-menu:before{content:"\e602"}.w-icon-arrow-down:before,.w-icon-dropdown-toggle:before{content:"\e603"}.w-icon-file-upload-remove:before{content:"\e900"}*{-webkit-box-sizing:border-box;-moz-box-sizing:border-box;box-sizing:border-box}html{height:100%}body{margin:0;min-height:100%;background-color:#fff;font-family:Arial,sans-serif;font-size:14px;line-height:20px;color:#333}img{max-width:100%;vertical-align:middle;display:inline-block}html.w-mod-touch *{background-attachment:scroll !important}.w-block{display:block}.w-inline-block{max-width:100%;display:inline-block}.w-clearfix:before,.w-clearfix:after{content:" ";display:table}.w-clearfix:after{clear:both}.w-hidden{display:none}.w-button{display:inline-block;padding:9px 15px;background-color:#3898ec;color:white;border:0;line-height:inherit;text-decoration:none;cursor:pointer;border-radius:0}input.w-button{-webkit-appearance:button}html[data-w-dynpage] [data-w-cloak]{color:transparent !important}.w-webflow-badge,.w-webflow-badge *{position:static;left:auto;top:auto;right:auto;bottom:auto;z-index:auto;display:block;visibility:visible;overflow:visible;overflow-x:visible;overflow-y:visible;box-sizing:border-box;width:auto;height:auto;max-height:none;max-width:none;min-height:0;min-width:0;margin:0;padding:0;float:none;clear:none;border:0 none transparent;border-radius:0;background:0;background-image:none;background-position:0 0;background-size:auto auto;background-repeat:repeat;background-origin:padding-box;background-clip:border-box;background-attachment:scroll;background-color:transparent;box-shadow:none;opacity:1;transform:none;transition:none;direction:ltr;font-family:inherit;font-weight:inherit;color:inherit;font-size:inherit;line-height:inherit;font-style:inherit;font-variant:inherit;text-align:inherit;letter-spacing:inherit;text-decoration:inherit;text-indent:0;text-transform:inherit;list-style-type:disc;text-shadow:none;font-smoothing:auto;vertical-align:baseline;cursor:inherit;white-space:inherit;word-break:normal;word-spacing:normal;word-wrap:normal}.w-webflow-badge{position:fixed !important;display:inline-block !important;visibility:visible !important;z-index:2147483647 !important;top:auto !important;right:12px !important;bottom:12px !important;left:auto !important;color:#aaadb0 !important;background-color:#fff !important;border-radius:3px !important;padding:6px 8px 6px 6px !important;font-size:12px !important;opacity:1 !important;line-height:14px !important;text-decoration:none !important;transform:none !important;margin:0 !important;width:auto !important;height:auto !important;overflow:visible !important;white-space:nowrap;box-shadow:0 0 0 1px rgba(0,0,0,0.1),0 1px 3px rgba(0,0,0,0.1)}.w-webflow-badge>img{display:inline-block !important;visibility:visible !important;opacity:1 !important;vertical-align:middle !important}h1,h2,h3,h4,h5,h6{font-weight:bold;margin-bottom:10px}h1{font-size:38px;line-height:44px;margin-top:20px}h2{font-size:32px;line-height:36px;margin-top:20px}h3{font-size:24px;line-height:30px;margin-top:20px}h4{font-size:18px;line-height:24px;margin-top:10px}h5{font-size:14px;line-height:20px;margin-top:10px}h6{font-size:12px;line-height:18px;margin-top:10px}p{margin-top:0;margin-bottom:10px}blockquote{margin:0 0 10px 0;padding:10px 20px;border-left:5px solid #e2e2e2;font-size:18px;line-height:22px}figure{margin:0;margin-bottom:10px}figcaption{margin-top:5px;text-align:center}ul,ol{margin-top:0;margin-bottom:10px;padding-left:40px}.w-list-unstyled{padding-left:0;list-style:none}.w-embed:before,.w-embed:after{content:" ";display:table}.w-embed:after{clear:both}.w-video{width:100%;position:relative;padding:0}.w-video iframe,.w-video object,.w-video embed{position:absolute;top:0;left:0;width:100%;height:100%}fieldset{padding:0;margin:0;border:0}button,html input[type="button"],input[type="reset"]{border:0;cursor:pointer;-webkit-appearance:button}.w-form{margin:0 0 15px}.w-form-done{display:none;padding:20px;text-align:center;background-color:#ddd}.w-form-fail{display:none;margin-top:10px;padding:10px;background-color:#ffdede}label{display:block;margin-bottom:5px;font-weight:bold}.w-input,.w-select{display:block;width:100%;height:38px;padding:8px 12px;margin-bottom:10px;font-size:14px;line-height:1.42857143;color:#333;vertical-align:middle;background-color:#fff;border:1px solid #ccc}.w-input:-moz-placeholder,.w-select:-moz-placeholder{color:#999}.w-input::-moz-placeholder,.w-select::-moz-placeholder{color:#999;opacity:1}.w-input:-ms-input-placeholder,.w-select:-ms-input-placeholder{color:#999}.w-input::-webkit-input-placeholder,.w-select::-webkit-input-placeholder{color:#999}.w-input:focus,.w-select:focus{border-color:#3898ec;outline:0}.w-input[disabled],.w-select[disabled],.w-input[readonly],.w-select[readonly],fieldset[disabled] .w-input,fieldset[disabled] .w-select{cursor:not-allowed;background-color:#eee}textarea.w-input,textarea.w-select{height:auto}.w-select{background-image:-webkit-linear-gradient(white 0,#f3f3f3 100%);background-image:linear-gradient(white 0,#f3f3f3 100%)}.w-select[multiple]{height:auto}.w-form-label{display:inline-block;cursor:pointer;font-weight:normal;margin-bottom:0}.w-checkbox,.w-radio{display:block;margin-bottom:5px;padding-left:20px}.w-checkbox:before,.w-radio:before,.w-checkbox:after,.w-radio:after{content:" ";display:table}.w-checkbox:after,.w-radio:after{clear:both}.w-checkbox-input,.w-radio-input{margin:4px 0 0;margin-top:1px \9;line-height:normal;float:left;margin-left:-20px}.w-radio-input{margin-top:3px}.w-file-upload{display:block;margin-bottom:10px}.w-file-upload-input{width:.1px;height:.1px;opacity:0;overflow:hidden;position:absolute;z-index:-100}.w-file-upload-default,.w-file-upload-uploading{display:inline-block;color:#333}.w-file-upload-error,.w-file-upload-success{display:flex}.w-file-upload-default.w-hidden,.w-file-upload-uploading.w-hidden,.w-file-upload-error.w-hidden,.w-file-upload-success.w-hidden{display:none}.w-file-upload-uploading-btn{display:inline-block;font-size:14px;font-weight:normal;cursor:pointer;margin:0;padding:8px 12px 8px 40px;border:1px solid #ccc;background-color:#fafafa;background-image:url("data:image/svg+xml;base64,PHN2ZyB4bWxucz0iaHR0cDovL3d3dy53My5vcmcvMjAwMC9zdmciIHZpZXdCb3g9IjAgMCAzMCAzMCI+PGcgZmlsbD0ibm9uZSIgZmlsbC1ydWxlPSJldmVub2RkIj48cGF0aCBmaWxsPSIjQ0NDIiBkPSJNMTUgMzBhMTUgMTUgMCAxIDEgMC0zMCAxNSAxNSAwIDAgMSAwIDMwem0wLTNhMTIgMTIgMCAxIDAgMC0yNCAxMiAxMiAwIDAgMCAwIDI0eiIvPjxwYXRoIGZpbGw9IiMzMzMiIGQ9Ik0wIDE1QTE1IDE1IDAgMCAxIDE1IDB2M0ExMiAxMiAwIDAgMCAzIDE1SDB6Ij48YW5pbWF0ZVRyYW5zZm9ybSBhdHRyaWJ1dGVOYW1lPSJ0cmFuc2Zvcm0iIGF0dHJpYnV0ZVR5cGU9IlhNTCIgdHlwZT0icm90YXRlIiBmcm9tPSIwIDE1IDE1IiB0bz0iMzYwIDE1IDE1IiBkdXI9IjAuNnMiIHJlcGVhdENvdW50PSJpbmRlZmluaXRlIi8+PC9wYXRoPjwvZz48L3N2Zz4=");background-position:12px 50%;background-size:20px 20px;background-repeat:no-repeat}.w-file-upload-file{display:flex;flex-grow:1;justify-content:space-between;margin:0;padding:8px 9px 8px 11px;border:1px solid #ccc;background-color:#fafafa}.w-file-upload-file-name{font-size:14px;font-weight:normal;display:block}.w-file-remove-link{margin-top:3px;width:16px;height:16px;display:flex;cursor:pointer}.w-icon-file-upload-remove{margin:auto;font-size:10px}.w-file-upload-error-msg{color:#ea384c;margin-top:10px;padding:2px 0}.w-file-upload-info{display:inline-block;line-height:38px;padding:0 12px}.w-file-upload-label{display:inline-block;font-size:14px;font-weight:normal;cursor:pointer;margin:0;padding:8px 12px 8px 40px;border:1px solid #ccc;background-color:#fafafa;background-image:url("data:image/svg+xml;base64,PHN2ZyB4bWxucz0iaHR0cDovL3d3dy53My5vcmcvMjAwMC9zdmciIHZpZXdCb3g9IjAgMCAyMCAxNCI+PHBhdGggZmlsbD0iY3VycmVudENvbG9yIiBkPSJNMTUuOTIgNS4wMmE0LjUgNC41IDAgMCAxIC4wOCA4Ljk1VjE0SDRhNCA0IDAgMSAxIDAtOCA2IDYgMCAwIDEgMTEuOTItLjk4ek0xMSA5aDNsLTQtNS00IDVoM3YyaDJWOXoiLz48L3N2Zz4=");background-position:12px 50%;background-size:20px 20px;background-repeat:no-repeat}.w-icon-file-upload{width:20px;height:20px;margin-right:8px}.w-icon-file-upload-svg{width:100%;height:100%}.w-form-recaptcha{margin-bottom:8px}.w-container{margin-left:auto;margin-right:auto;max-width:940px}.w-container:before,.w-container:after{content:" ";display:table}.w-container:after{clear:both}.w-container .w-row{margin-left:-10px;margin-right:-10px}.w-row:before,.w-row:after{content:" ";display:table}.w-row:after{clear:both}.w-row .w-row{margin-left:0;margin-right:0}.w-col{position:relative;float:left;width:100%;min-height:1px;padding-left:10px;padding-right:10px}.w-col .w-col{padding-left:0;padding-right:0}.w-col-1{width:8.33333333%}.w-col-2{width:16.66666667%}.w-col-3{width:25%}.w-col-4{width:33.33333333%}.w-col-5{width:41.66666667%}.w-col-6{width:50%}.w-col-7{width:58.33333333%}.w-col-8{width:66.66666667%}.w-col-9{width:75%}.w-col-10{width:83.33333333%}.w-col-11{width:91.66666667%}.w-col-12{width:100%}.w-hidden-main{display:none !important}@media screen and (max-width:991px){.w-container{max-width:728px}.w-hidden-main{display:inherit !important}.w-hidden-medium{display:none !important}.w-col-medium-1{width:8.33333333%}.w-col-medium-2{width:16.66666667%}.w-col-medium-3{width:25%}.w-col-medium-4{width:33.33333333%}.w-col-medium-5{width:41.66666667%}.w-col-medium-6{width:50%}.w-col-medium-7{width:58.33333333%}.w-col-medium-8{width:66.66666667%}.w-col-medium-9{width:75%}.w-col-medium-10{width:83.33333333%}.w-col-medium-11{width:91.66666667%}.w-col-medium-12{width:100%}.w-col-stack{width:100%;left:auto;right:auto}}@media screen and (max-width:767px){.w-hidden-main{display:inherit !important}.w-hidden-medium{display:inherit !important}.w-hidden-small{display:none !important}.w-row,.w-container .w-row{margin-left:0;margin-right:0}.w-col{width:100%;left:auto;right:auto}.w-col-small-1{width:8.33333333%}.w-col-small-2{width:16.66666667%}.w-col-small-3{width:25%}.w-col-small-4{width:33.33333333%}.w-col-small-5{width:41.66666667%}.w-col-small-6{width:50%}.w-col-small-7{width:58.33333333%}.w-col-small-8{width:66.66666667%}.w-col-small-9{width:75%}.w-col-small-10{width:83.33333333%}.w-col-small-11{width:91.66666667%}.w-col-small-12{width:100%}}@media screen and (max-width:479px){.w-container{max-width:none}.w-hidden-main{display:inherit !important}.w-hidden-medium{display:inherit !important}.w-hidden-small{display:inherit !important}.w-hidden-tiny{display:none !important}.w-col{width:100%}.w-col-tiny-1{width:8.33333333%}.w-col-tiny-2{width:16.66666667%}.w-col-tiny-3{width:25%}.w-col-tiny-4{width:33.33333333%}.w-col-tiny-5{width:41.66666667%}.w-col-tiny-6{width:50%}.w-col-tiny-7{width:58.33333333%}.w-col-tiny-8{width:66.66666667%}.w-col-tiny-9{width:75%}.w-col-tiny-10{width:83.33333333%}.w-col-tiny-11{width:91.66666667%}.w-col-tiny-12{width:100%}}.w-widget{position:relative}.w-widget-map{width:100%;height:400px}.w-widget-map label{width:auto;display:inline}.w-widget-map img{max-width:inherit}.w-widget-map .gm-style-iw{width:90% !important;height:auto !important;top:7px !important;left:6% !important;display:inline;text-align:center;overflow:hidden}.w-widget-map .gm-style-iw+div{display:none}.w-widget-twitter{overflow:hidden}.w-widget-twitter-count-shim{display:inline-block;vertical-align:top;position:relative;width:28px;height:20px;text-align:center;background:white;border:#758696 solid 1px;border-radius:3px}.w-widget-twitter-count-shim *{pointer-events:none;-webkit-user-select:none;-moz-user-select:none;-ms-user-select:none;user-select:none}.w-widget-twitter-count-shim .w-widget-twitter-count-inner{position:relative;font-size:15px;line-height:12px;text-align:center;color:#999;font-family:serif}.w-widget-twitter-count-shim .w-widget-twitter-count-clear{position:relative;display:block}.w-widget-twitter-count-shim.w--large{width:36px;height:28px;margin-left:7px}.w-widget-twitter-count-shim.w--large .w-widget-twitter-count-inner{font-size:18px;line-height:18px}.w-widget-twitter-count-shim:not(.w--vertical){margin-left:5px;margin-right:8px}.w-widget-twitter-count-shim:not(.w--vertical).w--large{margin-left:6px}.w-widget-twitter-count-shim:not(.w--vertical):before,.w-widget-twitter-count-shim:not(.w--vertical):after{top:50%;left:0;border:solid transparent;content:" ";height:0;width:0;position:absolute;pointer-events:none}.w-widget-twitter-count-shim:not(.w--vertical):before{border-color:rgba(117,134,150,0);border-right-color:#5d6c7b;border-width:4px;margin-left:-9px;margin-top:-4px}.w-widget-twitter-count-shim:not(.w--vertical).w--large:before{border-width:5px;margin-left:-10px;margin-top:-5px}.w-widget-twitter-count-shim:not(.w--vertical):after{border-color:rgba(255,255,255,0);border-right-color:white;border-width:4px;margin-left:-8px;margin-top:-4px}.w-widget-twitter-count-shim:not(.w--vertical).w--large:after{border-width:5px;margin-left:-9px;margin-top:-5px}.w-widget-twitter-count-shim.w--vertical{width:61px;height:33px;margin-bottom:8px}.w-widget-twitter-count-shim.w--vertical:before,.w-widget-twitter-count-shim.w--vertical:after{top:100%;left:50%;border:solid transparent;content:" ";height:0;width:0;position:absolute;pointer-events:none}.w-widget-twitter-count-shim.w--vertical:before{border-color:rgba(117,134,150,0);border-top-color:#5d6c7b;border-width:5px;margin-left:-5px}.w-widget-twitter-count-shim.w--vertical:after{border-color:rgba(255,255,255,0);border-top-color:white;border-width:4px;margin-left:-4px}.w-widget-twitter-count-shim.w--vertical .w-widget-twitter-count-inner{font-size:18px;line-height:22px}.w-widget-twitter-count-shim.w--vertical.w--large{width:76px}.w-widget-gplus{overflow:hidden}.w-background-video{position:relative;overflow:hidden;height:500px;color:white}.w-background-video>video{background-size:cover;background-position:50% 50%;position:absolute;right:-100%;bottom:-100%;top:-100%;left:-100%;margin:auto;min-width:100%;min-height:100%;z-index:-100}.w-background-video>video::-webkit-media-controls-start-playback-button{display:none !important;-webkit-appearance:none}.w-slider{position:relative;height:300px;text-align:center;background:#ddd;clear:both;-webkit-tap-highlight-color:rgba(0,0,0,0);tap-highlight-color:rgba(0,0,0,0)}.w-slider-mask{position:relative;display:block;overflow:hidden;z-index:1;left:0;right:0;height:100%;white-space:nowrap}.w-slide{position:relative;display:inline-block;vertical-align:top;width:100%;height:100%;white-space:normal;text-align:left}.w-slider-nav{position:absolute;z-index:2;top:auto;right:0;bottom:0;left:0;margin:auto;padding-top:10px;height:40px;text-align:center;-webkit-tap-highlight-color:rgba(0,0,0,0);tap-highlight-color:rgba(0,0,0,0)}.w-slider-nav.w-round>div{border-radius:100%}.w-slider-nav.w-num>div{width:auto;height:auto;padding:.2em .5em;font-size:inherit;line-height:inherit}.w-slider-nav.w-shadow>div{box-shadow:0 0 3px rgba(51,51,51,0.4)}.w-slider-nav-invert{color:#fff}.w-slider-nav-invert>div{background-color:rgba(34,34,34,0.4)}.w-slider-nav-invert>div.w-active{background-color:#222}.w-slider-dot{position:relative;display:inline-block;width:1em;height:1em;background-color:rgba(255,255,255,0.4);cursor:pointer;margin:0 3px .5em;transition:background-color 100ms,color 100ms}.w-slider-dot.w-active{background-color:#fff}.w-slider-arrow-left,.w-slider-arrow-right{position:absolute;width:80px;top:0;right:0;bottom:0;left:0;margin:auto;cursor:pointer;overflow:hidden;color:white;font-size:40px;-webkit-tap-highlight-color:rgba(0,0,0,0);tap-highlight-color:rgba(0,0,0,0);-webkit-user-select:none;-moz-user-select:none;-ms-user-select:none;user-select:none}.w-slider-arrow-left [class^="w-icon-"],.w-slider-arrow-right [class^="w-icon-"],.w-slider-arrow-left [class*=" w-icon-"],.w-slider-arrow-right [class*=" w-icon-"]{position:absolute}.w-slider-arrow-left{z-index:3;right:auto}.w-slider-arrow-right{z-index:4;left:auto}.w-icon-slider-left,.w-icon-slider-right{top:0;right:0;bottom:0;left:0;margin:auto;width:1em;height:1em}.w-dropdown{display:inline-block;position:relative;text-align:left;margin-left:auto;margin-right:auto;z-index:900}.w-dropdown-btn,.w-dropdown-toggle,.w-dropdown-link{position:relative;vertical-align:top;text-decoration:none;color:#222;padding:20px;text-align:left;margin-left:auto;margin-right:auto;white-space:nowrap}.w-dropdown-toggle{-webkit-user-select:none;-moz-user-select:none;-ms-user-select:none;user-select:none;display:inline-block;cursor:pointer;padding-right:40px}.w-icon-dropdown-toggle{position:absolute;top:0;right:0;bottom:0;margin:auto;margin-right:20px;width:1em;height:1em}.w-dropdown-list{position:absolute;background:#ddd;display:none;min-width:100%}.w-dropdown-list.w--open{display:block}.w-dropdown-link{padding:10px 20px;display:block;color:#222}.w-dropdown-link.w--current{color:#0082f3}.w-nav[data-collapse="all"] .w-dropdown,.w-nav[data-collapse="all"] .w-dropdown-toggle{display:block}.w-nav[data-collapse="all"] .w-dropdown-list{position:static}@media screen and (max-width:991px){.w-nav[data-collapse="medium"] .w-dropdown,.w-nav[data-collapse="medium"] .w-dropdown-toggle{display:block}.w-nav[data-collapse="medium"] .w-dropdown-list{position:static}}@media screen and (max-width:767px){.w-nav[data-collapse="small"] .w-dropdown,.w-nav[data-collapse="small"] .w-dropdown-toggle{display:block}.w-nav[data-collapse="small"] .w-dropdown-list{position:static}.w-nav-brand{padding-left:10px}}@media screen and (max-width:479px){.w-nav[data-collapse="tiny"] .w-dropdown,.w-nav[data-collapse="tiny"] .w-dropdown-toggle{display:block}.w-nav[data-collapse="tiny"] .w-dropdown-list{position:static}}.w-lightbox-backdrop{color:#000;cursor:auto;font-family:serif;font-size:medium;font-style:normal;font-variant:normal;font-weight:normal;letter-spacing:normal;line-height:normal;list-style:disc;text-align:start;text-indent:0;text-shadow:none;text-transform:none;visibility:visible;white-space:normal;word-break:normal;word-spacing:normal;word-wrap:normal;position:fixed;top:0;right:0;bottom:0;left:0;color:#fff;font-family:"Helvetica Neue",Helvetica,Ubuntu,"Segoe UI",Verdana,sans-serif;font-size:17px;line-height:1.2;font-weight:300;text-align:center;background:rgba(0,0,0,0.9);z-index:2000;outline:0;opacity:0;-webkit-user-select:none;-moz-user-select:none;-ms-user-select:none;-webkit-tap-highlight-color:transparent;-webkit-transform:translate(0,0)}.w-lightbox-backdrop,.w-lightbox-container{height:100%;overflow:auto;-webkit-overflow-scrolling:touch}.w-lightbox-content{position:relative;height:100vh;overflow:hidden}.w-lightbox-view{position:absolute;width:100vw;height:100vh;opacity:0}.w-lightbox-view:before{content:"";height:100vh}.w-lightbox-group,.w-lightbox-group .w-lightbox-view,.w-lightbox-group .w-lightbox-view:before{height:86vh}.w-lightbox-frame,.w-lightbox-view:before{display:inline-block;vertical-align:middle}.w-lightbox-figure{position:relative;margin:0}.w-lightbox-group .w-lightbox-figure{cursor:pointer}.w-lightbox-img{width:auto;height:auto;max-width:none}.w-lightbox-image{display:block;float:none;max-width:100vw;max-height:100vh}.w-lightbox-group .w-lightbox-image{max-height:86vh}.w-lightbox-caption{position:absolute;right:0;bottom:0;left:0;padding:.5em 1em;background:rgba(0,0,0,0.4);text-align:left;text-overflow:ellipsis;white-space:nowrap;overflow:hidden}.w-lightbox-embed{position:absolute;top:0;right:0;bottom:0;left:0;width:100%;height:100%}.w-lightbox-control{position:absolute;top:0;width:4em;background-size:24px;background-repeat:no-repeat;background-position:center;cursor:pointer;-webkit-transition:all .3s;transition:all .3s}.w-lightbox-left{display:none;bottom:0;left:0;background-image:url("data:image/svg+xml;base64,PHN2ZyB4bWxucz0iaHR0cDovL3d3dy53My5vcmcvMjAwMC9zdmciIHZpZXdCb3g9Ii0yMCAwIDI0IDQwIiB3aWR0aD0iMjQiIGhlaWdodD0iNDAiPjxnIHRyYW5zZm9ybT0icm90YXRlKDQ1KSI+PHBhdGggZD0ibTAgMGg1djIzaDIzdjVoLTI4eiIgb3BhY2l0eT0iLjQiLz48cGF0aCBkPSJtMSAxaDN2MjNoMjN2M2gtMjZ6IiBmaWxsPSIjZmZmIi8+PC9nPjwvc3ZnPg==")}.w-lightbox-right{display:none;right:0;bottom:0;background-image:url("data:image/svg+xml;base64,PHN2ZyB4bWxucz0iaHR0cDovL3d3dy53My5vcmcvMjAwMC9zdmciIHZpZXdCb3g9Ii00IDAgMjQgNDAiIHdpZHRoPSIyNCIgaGVpZ2h0PSI0MCI+PGcgdHJhbnNmb3JtPSJyb3RhdGUoNDUpIj48cGF0aCBkPSJtMC0waDI4djI4aC01di0yM2gtMjN6IiBvcGFjaXR5PSIuNCIvPjxwYXRoIGQ9Im0xIDFoMjZ2MjZoLTN2LTIzaC0yM3oiIGZpbGw9IiNmZmYiLz48L2c+PC9zdmc+")}.w-lightbox-close{right:0;height:2.6em;background-image:url("data:image/svg+xml;base64,PHN2ZyB4bWxucz0iaHR0cDovL3d3dy53My5vcmcvMjAwMC9zdmciIHZpZXdCb3g9Ii00IDAgMTggMTciIHdpZHRoPSIxOCIgaGVpZ2h0PSIxNyI+PGcgdHJhbnNmb3JtPSJyb3RhdGUoNDUpIj48cGF0aCBkPSJtMCAwaDd2LTdoNXY3aDd2NWgtN3Y3aC01di03aC03eiIgb3BhY2l0eT0iLjQiLz48cGF0aCBkPSJtMSAxaDd2LTdoM3Y3aDd2M2gtN3Y3aC0zdi03aC03eiIgZmlsbD0iI2ZmZiIvPjwvZz48L3N2Zz4=");background-size:18px}.w-lightbox-strip{position:absolute;bottom:0;left:0;right:0;padding:0 1vh;line-height:0;white-space:nowrap;overflow-x:auto;overflow-y:hidden}.w-lightbox-item{display:inline-block;width:10vh;padding:2vh 1vh;box-sizing:content-box;cursor:pointer;-webkit-transform:translate3d(0,0,0)}.w-lightbox-active{opacity:.3}.w-lightbox-thumbnail{position:relative;height:10vh;background:#222;overflow:hidden}.w-lightbox-thumbnail-image{position:absolute;top:0;left:0}.w-lightbox-thumbnail .w-lightbox-tall{top:50%;width:100%;-webkit-transform:translate(0,-50%);-ms-transform:translate(0,-50%);transform:translate(0,-50%)}.w-lightbox-thumbnail .w-lightbox-wide{left:50%;height:100%;-webkit-transform:translate(-50%,0);-ms-transform:translate(-50%,0);transform:translate(-50%,0)}.w-lightbox-spinner{position:absolute;top:50%;left:50%;box-sizing:border-box;width:40px;height:40px;margin-top:-20px;margin-left:-20px;border:5px solid rgba(0,0,0,0.4);border-radius:50%;-webkit-animation:spin .8s infinite linear;animation:spin .8s infinite linear}.w-lightbox-spinner:after{content:"";position:absolute;top:-4px;right:-4px;bottom:-4px;left:-4px;border:3px solid transparent;border-bottom-color:#fff;border-radius:50%}.w-lightbox-hide{display:none}.w-lightbox-noscroll{overflow:hidden}@media(min-width:768px){.w-lightbox-content{height:96vh;margin-top:2vh}.w-lightbox-view,.w-lightbox-view:before{height:96vh}.w-lightbox-group,.w-lightbox-group .w-lightbox-view,.w-lightbox-group .w-lightbox-view:before{height:84vh}.w-lightbox-image{max-width:96vw;max-height:96vh}.w-lightbox-group .w-lightbox-image{max-width:82.3vw;max-height:84vh}.w-lightbox-left,.w-lightbox-right{display:block;opacity:.5}.w-lightbox-close{opacity:.8}.w-lightbox-control:hover{opacity:1}}.w-lightbox-inactive,.w-lightbox-inactive:hover{opacity:0}.w-richtext:before,.w-richtext:after{content:" ";display:table}.w-richtext:after{clear:both}.w-richtext[contenteditable="true"]:before,.w-richtext[contenteditable="true"]:after{white-space:initial}.w-richtext ol,.w-richtext ul{overflow:hidden}.w-richtext .w-richtext-figure-selected.w-richtext-figure-type-video div:before,.w-richtext .w-richtext-figure-selected[data-rt-type="video"] div:before{outline:2px solid #2895f7}.w-richtext .w-richtext-figure-selected.w-richtext-figure-type-image div,.w-richtext .w-richtext-figure-selected[data-rt-type="image"] div{outline:2px solid #2895f7}.w-richtext figure.w-richtext-figure-type-video>div:before,.w-richtext figure[data-rt-type="video"]>div:before{content:'';position:absolute;display:none;left:0;top:0;right:0;bottom:0;z-index:1}.w-richtext figure{position:relative;max-width:60%}.w-richtext figure>div:before{cursor:default !important}.w-richtext figure img{width:100%}.w-richtext figure figcaption.w-richtext-figcaption-placeholder{opacity:.6}.w-richtext figure div{font-size:0;color:transparent}.w-richtext figure.w-richtext-figure-type-image,.w-richtext figure[data-rt-type="image"]{display:table}.w-richtext figure.w-richtext-figure-type-image>div,.w-richtext figure[data-rt-type="image"]>div{display:inline-block}.w-richtext figure.w-richtext-figure-type-image>figcaption,.w-richtext figure[data-rt-type="image"]>figcaption{display:table-caption;caption-side:bottom}.w-richtext figure.w-richtext-figure-type-video,.w-richtext figure[data-rt-type="video"]{width:60%;height:0}.w-richtext figure.w-richtext-figure-type-video iframe,.w-richtext figure[data-rt-type="video"] iframe{position:absolute;top:0;left:0;width:100%;height:100%}.w-richtext figure.w-richtext-figure-type-video>div,.w-richtext figure[data-rt-type="video"]>div{width:100%}.w-richtext figure.w-richtext-align-center{margin-right:auto;margin-left:auto;clear:both}.w-richtext figure.w-richtext-align-center.w-richtext-figure-type-image>div,.w-richtext figure.w-richtext-align-center[data-rt-type="image"]>div{max-width:100%}.w-richtext figure.w-richtext-align-normal{clear:both}.w-richtext figure.w-richtext-align-fullwidth{width:100%;max-width:100%;text-align:center;clear:both;display:block;margin-right:auto;margin-left:auto}.w-richtext figure.w-richtext-align-fullwidth>div{display:inline-block;padding-bottom:inherit}.w-richtext figure.w-richtext-align-fullwidth>figcaption{display:block}.w-richtext figure.w-richtext-align-floatleft{float:left;margin-right:15px;clear:none}.w-richtext figure.w-richtext-align-floatright{float:right;margin-left:15px;clear:none}.w-nav{position:relative;background:#ddd;z-index:1000}.w-nav:before,.w-nav:after{content:" ";display:table}.w-nav:after{clear:both}.w-nav-brand{position:relative;float:left;text-decoration:none;color:#333}.w-nav-link{position:relative;display:inline-block;vertical-align:top;text-decoration:none;color:#222;padding:20px;text-align:left;margin-left:auto;margin-right:auto}.w-nav-link.w--current{color:#0082f3}.w-nav-menu{position:relative;float:right}.w--nav-menu-open{display:block !important;position:absolute;top:100%;left:0;right:0;background:#c8c8c8;text-align:center;overflow:visible;min-width:200px}.w--nav-link-open{display:block;position:relative}.w-nav-overlay{position:absolute;overflow:hidden;display:none;top:100%;left:0;right:0;width:100%}.w-nav-overlay .w--nav-menu-open{top:0}.w-nav[data-animation="over-left"] .w-nav-overlay{width:auto}.w-nav[data-animation="over-left"] .w-nav-overlay,.w-nav[data-animation="over-left"] .w--nav-menu-open{right:auto;z-index:1;top:0}.w-nav[data-animation="over-right"] .w-nav-overlay{width:auto}.w-nav[data-animation="over-right"] .w-nav-overlay,.w-nav[data-animation="over-right"] .w--nav-menu-open{left:auto;z-index:1;top:0}.w-nav-button{position:relative;float:right;padding:18px;font-size:24px;display:none;cursor:pointer;-webkit-tap-highlight-color:rgba(0,0,0,0);tap-highlight-color:rgba(0,0,0,0);-webkit-user-select:none;-moz-user-select:none;-ms-user-select:none;user-select:none}.w-nav-button.w--open{background-color:#c8c8c8;color:white}.w-nav[data-collapse="all"] .w-nav-menu{display:none}.w-nav[data-collapse="all"] .w-nav-button{display:block}@media screen and (max-width:991px){.w-nav[data-collapse="medium"] .w-nav-menu{display:none}.w-nav[data-collapse="medium"] .w-nav-button{display:block}}@media screen and (max-width:767px){.w-nav[data-collapse="small"] .w-nav-menu{display:none}.w-nav[data-collapse="small"] .w-nav-button{display:block}.w-nav-brand{padding-left:10px}}@media screen and (max-width:479px){.w-nav[data-collapse="tiny"] .w-nav-menu{display:none}.w-nav[data-collapse="tiny"] .w-nav-button{display:block}}.w-tabs{position:relative}.w-tabs:before,.w-tabs:after{content:" ";display:table}.w-tabs:after{clear:both}.w-tab-menu{position:relative}.w-tab-link{position:relative;display:inline-block;vertical-align:top;text-decoration:none;padding:9px 30px;text-align:left;cursor:pointer;color:#222;background-color:#ddd}.w-tab-link.w--current{background-color:#c8c8c8}.w-tab-content{position:relative;display:block;overflow:hidden}.w-tab-pane{position:relative;display:none}.w--tab-active{display:block}@media screen and (max-width:479px){.w-tab-link{display:block}}.w-ix-emptyfix:after{content:""}@keyframes spin{0%{transform:rotate(0)}100%{transform:rotate(360deg)}}.w-dyn-empty{padding:10px;background-color:#ddd}.w-dyn-bind-empty{display:none !important}.w-condition-invisible{display:none !important}body{background-color:#edeff2;font-family:'Open Sans',sans-serif;color:#6a859c;font-size:16px;line-height:20px}h1{margin-top:0;margin-bottom:10px;font-size:38px;line-height:44px;font-weight:700}h2{margin-top:0;margin-bottom:10px;color:#676770;font-size:32px;line-height:36px;font-weight:300;text-align:center}h3{margin-top:0;margin-bottom:0;color:#676770;font-size:20px;line-height:30px;font-weight:300;letter-spacing:7px;text-transform:uppercase}h4{margin-top:0;margin-bottom:10px;font-size:18px;line-height:24px;font-weight:700}h5{margin-top:0;margin-bottom:20px;color:#676770;font-size:18px;line-height:20px;font-weight:300;letter-spacing:4px;text-transform:uppercase}h6{margin-top:0;margin-bottom:10px;font-size:12px;line-height:18px;font-weight:700}p{margin-top:10px;margin-bottom:10px;font-size:14px;line-height:25px;font-weight:300}.button{display:inline-block;margin-right:10px;margin-left:10px;padding:12px 30px;border-radius:4px;background-color:#69b9ff;-webkit-transition:background-color 300ms ease;transition:background-color 300ms ease;color:#fff;font-size:16px;line-height:21px;font-weight:300;text-align:center;letter-spacing:2px;text-decoration:none;text-transform:uppercase}.button:hover{background-color:#2e9dff}.button.w--current{background-color:#2e80b6}.button.full-width{display:block;width:100%;margin-right:0;margin-left:0}.button.tab{margin-right:8px;margin-left:8px;background-color:#92a0ad}.button.tab:hover{background-color:#2e80b6}.button.tab.w--current{background-color:#2e80b6}.navigation-link{-webkit-transition:all 300ms ease-in-out;transition:all 300ms ease-in-out;color:#676770}.navigation-link:hover{color:#2e9dff}.navigation-bar{background-color:#fff}.navigation-menu{float:left}.brand-text{margin-top:0;margin-bottom:0;font-family:'Open Sans',sans-serif;color:#69b9ff;font-size:25px;line-height:25px;font-weight:300;letter-spacing:4px;text-transform:uppercase}.brand-link{padding-top:16px;padding-bottom:16px}.section{position:relative;padding:20px 10px 80px;background-color:#fff;text-align:center}.section.accent{background-color:#192024}.white-box{padding:15px;border:1px solid #dcebf7;border-radius:5px;background-color:#fff;text-align:center}.white-box.transparent{border-style:none;background-color:transparent}.hero-section{padding-top:242px;padding-bottom:242px}.hero-section.centered{position:relative;width:100%;height:auto;padding-top:0;padding-bottom:0;border-style:none;border-bottom-width:4px;border-bottom-color:#69b9ff;text-align:center}.hero-heading{margin-bottom:30px;color:#fff;font-size:60px;line-height:60px;font-weight:300;letter-spacing:4px;text-transform:uppercase}.hero-subheading{margin-bottom:40px;color:#2e9dff;font-size:25px;line-height:25px;font-weight:300;letter-spacing:3px;text-transform:uppercase}.hollow-button{display:inline-block;margin-right:10px;margin-left:10px;padding:10px 30px;border:1px solid #fff;border-radius:4px;-webkit-transition:background-color 300ms ease,border 300ms ease,color 300ms ease;transition:background-color 300ms ease,border 300ms ease,color 300ms ease;color:#fff;line-height:21px;font-weight:300;letter-spacing:2px;text-decoration:none;text-transform:uppercase}.hollow-button:hover{border-color:#2e9dff;color:#2e9dff}.hollow-button.all-caps{text-transform:uppercase}.section-heading{margin-top:0;margin-bottom:16px}.section-heading.centered{color:#676770;font-size:30px;font-weight:300;text-align:center;letter-spacing:5px;text-transform:uppercase}.section-heading.centered.white{color:#fff}.section-subheading.center{color:#8e8e9c;font-size:18px;font-weight:300;text-align:center;letter-spacing:3px;text-transform:uppercase}.section-subheading.center.off-white{padding-bottom:0;color:#e8e8e8}.section-title-group{margin-bottom:60px}.form-field{height:45px;margin-bottom:17px;border:0 solid #000;border-radius:3px;box-shadow:0 0 0 1px rgba(64,64,71,.3)}.form-field.text-area{height:110px}.footer{padding-top:35px;padding-bottom:35px}.footer.center{border-top:1px solid #dbdbdb;background-color:#383838;text-align:center}.footer-text{margin-top:5px;margin-bottom:5px;color:#9e9e9e;font-size:16px}.grid-image{display:block;width:35%;margin:20px auto;padding:20px;border:10px solid #fff;border-radius:50%;background-color:#69b9ff;box-shadow:0 0 0 1px #2e9dff}.info-icon{float:left}.footer-link{display:block;margin-bottom:6px;padding-bottom:10px;border-bottom:1px solid #d5d5e0;color:#668cad;font-size:14px;font-weight:300;text-decoration:none}.footer-link:hover{color:rgba(0,140,255,.84)}.footer-link.with-icon{margin-left:30px}.tab-menu{margin-bottom:40px;text-align:center}.tabs-wrapper{text-align:center}.fullwidth-image{width:100%;margin-bottom:20px}.white-text{margin-bottom:20px;color:#fff}.form{margin-top:40px}.image{position:relative;width:100%}.heading{margin-bottom:40px}.text-block{margin-bottom:45px}.heading-2{font-size:38px;font-weight:400}.field-label{padding-top:0}.heading-3{padding-top:37px;font-weight:400}.field-label-2{padding-top:6px}.field-label-3{padding-top:15px}.radio-button-field-1{position:static;margin-right:0;margin-left:0;padding-right:0;padding-left:40px;text-align:left}.radio-button-field-4{padding-left:40px;text-align:left}.field-label-4{text-align:left}.form-2{width:75%;margin-right:auto;margin-left:auto;text-align:left}.div-block{width:50%;margin-right:0;margin-left:0;padding-left:0}.form-3{width:75%;margin-right:auto;margin-left:auto;text-align:left}.checkbox-field{width:100%;margin-left:auto;text-align:left}.checkbox-field-2{width:100%;margin-right:auto;text-align:left}.field-label-5{text-align:left}.field-label-6{font-size:12px}.checkbox-field-3{width:100%;margin:33px auto 18px;text-align:left}.field-label-7{width:100%;margin-right:auto;margin-left:auto;padding-top:25px;text-align:left}.bold-text{margin-bottom:0;padding-top:0}.field-label-8{font-weight:700;text-align:left}.field-label-9{text-align:left}.form-4{width:75%;margin-right:auto;margin-left:auto;text-align:left}.column{text-align:center}.form-5{width:75%;margin-right:auto;margin-left:auto}.row{width:75%;margin-right:auto;margin-left:auto}.submit-button{margin-top:19px}.field-label-10{width:100%;text-align:left}.form-6{width:100%;margin-right:auto;margin-left:auto}.form-block{display:block;width:37.5%;min-width:37.5%;margin-right:auto;margin-left:auto;-webkit-box-pack:start;-webkit-justify-content:flex-start;-ms-flex-pack:start;justify-content:flex-start;-webkit-flex-wrap:nowrap;-ms-flex-wrap:nowrap;flex-wrap:nowrap;-webkit-box-align:stretch;-webkit-align-items:stretch;-ms-flex-align:stretch;align-items:stretch;-webkit-box-flex:0;-webkit-flex:0 auto;-ms-flex:0 auto;flex:0 auto}.text-field{width:100%;text-align:center}.container{display:-webkit-box;display:-webkit-flex;display:-ms-flexbox;display:flex}.div-block-2{position:static;display:-webkit-box;display:-webkit-flex;display:-ms-flexbox;display:flex;width:75%;margin-right:auto;margin-left:auto;-webkit-box-orient:horizontal;-webkit-box-direction:normal;-webkit-flex-direction:row;-ms-flex-direction:row;flex-direction:row;-webkit-box-pack:start;-webkit-justify-content:flex-start;-ms-flex-pack:start;justify-content:flex-start;-webkit-flex-wrap:wrap;-ms-flex-wrap:wrap;flex-wrap:wrap;-webkit-box-align:stretch;-webkit-align-items:stretch;-ms-flex-align:stretch;align-items:stretch;text-align:center}.text-field-2{text-align:center}.text-field-3{text-align:center}.text-field-4{text-align:center}.text-field-5{text-align:center}.text-field-6{text-align:center}.text-field-7{text-align:center}.text-field-8{text-align:center}.text-block-2{margin-top:30px}.image-2{margin-right:5px;margin-left:-5px;float:left}.column-2{text-align:left}.column-3{padding-right:0;padding-left:0}.div-block-3{width:25%;float:left}.div-block-4{position:relative;display:inline-block;width:49%;text-align:left}.image-3{position:relative;float:none}.section-2{background-color:#fff}.image-4{width:100%}.heading-4{display:inline-block;margin-bottom:0;padding-left:0;color:#192024;font-size:16px;line-height:24px;text-align:left}.div-block-5{display:block;text-align:right}.image-5{width:30%;margin-right:4%}.image-6{width:15%;margin-right:4%}.image-7{position:static;width:49%;text-align:left}.div-block-6{display:inline-block;width:35%;float:none;text-align:left}.div-block-7{display:-webkit-box;display:-webkit-flex;display:-ms-flexbox;display:flex;width:20%;margin-right:auto;float:left;-webkit-box-pack:center;-webkit-justify-content:center;-ms-flex-pack:center;justify-content:center;-webkit-box-align:center;-webkit-align-items:center;-ms-flex-align:center;align-items:center;-webkit-box-flex:0;-webkit-flex:0 auto;-ms-flex:0 auto;flex:0 auto}.div-block-8{display:block}.image-8{width:35%}.row-2{background-color:#fff}.column-4{position:relative;display:block;padding-right:0;padding-left:0}.column-5{padding-right:0;padding-left:0}.image-9{width:30%;margin-right:5%}.image-10{width:44%;margin-right:1%}.field-label-11{font-weight:400}.field-label-12{font-weight:400}.form-block-2{margin-bottom:20px}html.w-mod-js *[data-ix="fade-in-bottom-page-loads"]{opacity:0;-webkit-transform:translate(0,50px);-ms-transform:translate(0,50px);transform:translate(0,50px)}html.w-mod-js *[data-ix="fade-in-left-scroll-in"]{opacity:0;-webkit-transform:translate(-50px,0);-ms-transform:translate(-50px,0);transform:translate(-50px,0)}html.w-mod-js *[data-ix="fade-in-right-scroll-in"]{opacity:0;-webkit-transform:translate(50px,0);-ms-transform:translate(50px,0);transform:translate(50px,0)}html.w-mod-js *[data-ix="fade-in-top-scroll-in"]{opacity:0;-webkit-transform:translate(0,-50px);-ms-transform:translate(0,-50px);transform:translate(0,-50px)}html.w-mod-js *[data-ix="fade-in-bottom-scroll-in"]{opacity:0;-webkit-transform:translate(0,50px);-ms-transform:translate(0,50px);transform:translate(0,50px)}html.w-mod-js *[data-ix="bounce-in-scroll-in"]{opacity:0;-webkit-transform:scale(0.6000000000000001,0.6000000000000001);-ms-transform:scale(0.6000000000000001,0.6000000000000001);transform:scale(0.6000000000000001,0.6000000000000001)}html.w-mod-js *[data-ix="scale-on-scroll"]{opacity:0;-webkit-transform:scale(0.01,0.01);-ms-transform:scale(0.01,0.01);transform:scale(0.01,0.01)}@media(max-width:991px){.navigation-link{color:hsla(0,0%,100%,.52)}.navigation-link.w--current{color:#fff}.hamburger-button.w--open{background-color:#3b99d9}.navigation-menu{background-color:#3b99d9}.hero-section.centered{padding-top:0;padding-bottom:0}.grid-image{width:50%;padding:15px}.heading{font-size:30px}.heading-2{font-size:30px}.heading-3{font-size:30px}.form-2{width:100%}.form-3{width:100%}.form-4{width:100%}.form-5{width:100%}.form-block{width:45%}.div-block-2{width:100%}.div-block-3{width:30%}.heading-4{font-size:12px;line-height:18px}}@media(max-width:767px){.button.full-width{margin-right:auto;margin-left:auto}.button.tab{font-size:12px}.section{font-size:12px;line-height:16px}.white-box{margin-bottom:30px}.hero-section.centered{padding-top:0;padding-bottom:0}.hero-heading{margin-bottom:15px;font-size:50px}.hero-subheading{font-size:18px}.form-field.text-area{display:block}.grid-image{width:20%;padding:20px}.spc{margin-bottom:30px}.heading{font-size:22px;line-height:28px}.heading-2{font-size:22px;line-height:28px}.heading-3{font-size:22px;line-height:28px}.form-block{width:45%}.div-block-3{width:50%}.heading-4{font-size:8px;line-height:12px}.div-block-6{width:45%}}@media(max-width:479px){.button{margin-bottom:25px}.hero-section.centered{padding-top:0;padding-bottom:0}.section-subheading.center{line-height:30px}.grid-image{width:35%}.heading{margin-bottom:31px}.text-block{margin-bottom:30px}.form-2{width:100%}.form-3{width:100%}.form-4{width:100%}.form-5{width:100%}.form-block{width:100%}.div-block-2{width:100%}.div-block-3{width:50%}.div-block-5{-webkit-box-align:stretch;-webkit-align-items:stretch;-ms-flex-align:stretch;align-items:stretch}.div-block-6{position:static;margin-top:-8px;-webkit-box-flex:0;-webkit-flex:0 auto;-ms-flex:0 auto;flex:0 auto;text-align:left}}
	</style>
	
	</head>
	<body>
	
	
	
	<div class="hero-section centered">
	</div>
	<div class="section-2 w-clearfix">
	<div class="div-block-3">
	<img src="https://ilug.uni-halle.de/files/2018/06/LSB-Sachsen-Anhalt2.jpg" class="image-10"/>
	<img src="https://ilug.uni-halle.de/files/2018/06/Logo-Uni-Halle-Sportwissenschaften-klein-1.jpg" class="image-7"/>
	</div>
	<div class="div-block-5">
	<div class="div-block-6">
	<h1 class="heading-4">In Kooperation mit:
	</h1>
	<div class="div-block-8">
	<img src="https://ilug.uni-halle.de/files/2018/06/ILUG_logo_klein.jpg" class="image-5"/>
	<img src="https://ilug.uni-halle.de/files/2018/06/osp-logo-vektor-4c-ohne-hintergrund-1.png" class="image-6"/>
	<img src="https://ilug.uni-halle.de/files/2018/06/OvGU-Logo_grau-2.png" class="image-8"/>
	</div>
	</div>
	</div>
	</div>
	
	
	
	
	<div class="section">
	<div class="w-container">
	<h1 class="heading">Online-Anmeldung geschlossen.
	<br/>Für mehr Informationen wenden Sie sich bitte direkt an den Veranstalter.
	</h1>
	</div>
<!--
	<div class="text-block">
	Zum 2. Sportkongress des LSB Sachsen-Anhalt e.V. am Samstag, den 22. September 2018 in Halle (Saale), melde ich mich verbindlich an.
	</div>
	<div>
	<h1 class="heading-2">
	Persönliche Daten
	</h1>
	</div>
	<div class="div-block-2">
	
	<div class="form-block w-form">
	<form id="email-form-6" name="email-form-6" data-name="Email Form 6" class="form-6">
	<label for="prename">
	Vorname*
	</label>
	<input type="text" class="text-field-6 w-input" maxlength="256" name="prename" data-name="prename" placeholder="Bitte geben Sie Ihren Vornamen ein" id="prename" required=""/>
	</form>
	
	</div>
	
	<div class="form-block w-form">
	<form id="email-form-6" name="email-form-6" data-name="Email Form 6" class="form-6">
	<label for="name">
	Name*
	</label>
	<input type="text" class="text-field w-input" maxlength="256" name="name" data-name="Name" placeholder="Bitte geben Sie Ihren Nachnamen ein" id="name" required=""/>
	</form>
	
	</div>
	<div class="form-block w-form">
	<form id="email-form-6" name="email-form-6" data-name="Email Form 6" class="form-6">
	<label for="street">
	Straße, Nr. *
	</label>
	<input type="text" class="text-field-2 w-input" maxlength="256" name="street" data-name="street" placeholder="Bitte geben Sie Ihre Straße ein" id="street" required=""/>
	</form>
	
	</div>
	<div class="form-block w-form">
	<form id="email-form-6" name="email-form-6" data-name="Email Form 6" class="form-6">
	<label for="plz_city">
	PLZ / Ort *
	</label>
	<input type="text" class="text-field-7 w-input" maxlength="256" name="plz_city" data-name="plz_city" placeholder="Bitte geben Sie Ihre PLZ und Ort ein" id="plz_city" required=""/>
	</form>
	
	</div>
	<div class="form-block w-form">
	<form id="email-form-6" name="email-form-6" data-name="Email Form 6" class="form-6">
	<label for="bday">
	Geburtsdatum*
	</label>
	<input type="text" class="text-field-3 w-input" maxlength="256" name="bday" data-name="bday" placeholder="Bitte geben Sie Ihr Geburtsdatum ein" id="bday" required=""/>
	</form>
	
	</div>
	<div class="form-block w-form">
	<form id="email-form-6" name="email-form-6" data-name="Email Form 6" class="form-6">
	<label for="job-3">
	‍
	</label>
	</form>
	
	</div>
	</div>
	<div class="div-block-2">
	<div class="form-block w-form">
	<form id="email-form-6" name="email-form-6" data-name="Email Form 6" class="form-6">
	<label for="tel-2" class="field-label">
	Telefon*
	</label>
	<input type="text" class="text-field-4 w-input" maxlength="256" name="tel" data-name="tel" placeholder="Bitte geben Sie Ihre Telefonnummer ein" id="tel" required=""/>
	</form>
	
	</div>
	<div class="form-block w-form">
	<form id="email-form-6" name="email-form-6" data-name="Email Form 6" class="form-6">
	<label for="email-7">
	E-Mail*
	</label>
	<input type="email" class="text-field-8 w-input" maxlength="256" name="email" data-name="email" placeholder="Bitte geben Sie Ihre E-Mail ein" id="email" required=""/>
	</form>
	
	</div>
	<div class="form-block w-form">
	<form id="email-form-6" name="email-form-6" data-name="Email Form 6" class="form-6">
	<label for="job-3">
	‍
	</label>
	</form>
	
	</div>
	</div>
	<div>
	<h1 class="heading-3">
	Weitere Angaben
	</h1>
	</div>
	<div class="w-form">
	<form id="email-form-3" name="email-form-3" data-name="Email Form 3" class="form-2">
	<label for="email-3" class="field-label-3">
	Ich bin Mitglied in einem Sportverein des LSB Sachsen-Anhalt e.V. *
	</label>
	<div class="div-block">
	<div class="radio-button-field-1 w-radio">
	<input type="radio" id="memberyes" name="member" value="yes" data-name="member" required="" class="w-radio-input" onclick="UpdateInvoiceValueView(); ShowMemberForm();"/>
	<label for="yes" class="field-label-4 w-form-label">
	ja
	</label>
	</div>
	<div class="radio-button-field-4 w-radio">
	<input type="radio" id="memberno" name="member" value="no" data-name="member" required="" class="w-radio-input" onclick="UpdateInvoiceValueView(); HideMemberForm();"/>
	<label for="no" class="w-form-label">
	nein
	</label>
	</div>
	</div>
	</form>
	
	</div>
	<div id="div-member-1" class="div-block-2">
	<div class="form-block w-form">
	<form id="email-form-6" name="email-form-6" data-name="Email Form 6" class="form-6">
	<label for="club">
	Verein*
	</label>
	<input type="text" class="text-field w-input" maxlength="256" name="club" data-name="club" placeholder="Bitte geben Sie Ihren Verein ein" id="club" required=""/>
	</form>
	
	</div>
	<div class="form-block w-form">
	<form id="email-form-6" name="email-form-6" data-name="Email Form 6" class="form-6">
	<label for="clubnr">
	Vereinsnummer
	</label>
	<input type="text" class="text-field-6 w-input" maxlength="256" name="clubnr" data-name="clubnr" placeholder="Bitte geben Sie die Vereinsnummer ein" id="clubnr" required=""/>
	</form>
	
	</div>
	
	<div class="form-block w-form">
	<form id="email-form-6" name="email-form-6" data-name="Email Form 6" class="form-6">
	<label for="job-5">
	‍
	</label>
	</form>
	
	</div>
	</div>
	<div id="div-member-2" class="w-form">
	<form id="email-form-4" name="email-form-4" data-name="Email Form 4" class="form-4">
	<label for="name-4" class="field-label-8">
	Ich bin im Verein / KSB/SSB, Landesfachverband oder LSB ehrenamtlich tätig als
	</label>
	<label for="name-2" class="field-label-6">
	Mehrfachnennungen möglich
	</label>
	<div class="w-row">
	<div class="w-col w-col-6">
	<div class="checkbox-field w-checkbox">
	<input type="checkbox" id="unsaleried1" name="checkbox" data-name="Checkbox" class="w-checkbox-input"/>
	<label for="checkbox" class="field-label-5 w-form-label">
	Übungsleiter/in bzw. Trainer/in
	</label>
	</div>
	<div class="checkbox-field w-checkbox">
	<input type="checkbox" id="unsaleried2" name="checkbox-10" data-name="Checkbox 10" class="w-checkbox-input"/>
	<label for="checkbox" class="field-label-5 w-form-label">
	Vereinsmanager/in
	</label>
	</div>
	<div class="checkbox-field w-checkbox">
	<input type="checkbox" id="unsaleried3" name="checkbox-9" data-name="Checkbox 9" class="w-checkbox-input"/>
	<label for="checkbox" class="field-label-5 w-form-label">
	Jugendleiter/in
	</label>
	</div>
	<div class="checkbox-field w-checkbox">
	<input type="checkbox" id="unsaleried4" name="checkbox-8" data-name="Checkbox 8" class="w-checkbox-input"/>
	<label for="checkbox" class="field-label-5 w-form-label">
	Präsidiums- oder Vorstandsmitglied
	</label>
	</div>
	<div class="checkbox-field w-checkbox">
	<input type="checkbox" id="unsaleried5" name="checkbox-7" data-name="Checkbox 7" class="w-checkbox-input"/>
	<label for="checkbox" class="field-label-5 w-form-label">
	Abteilungsleiter/in
	</label>
	</div>
	<div class="checkbox-field w-checkbox">
	<input type="checkbox" id="unsaleried6" name="checkbox-6" data-name="Checkbox 6" class="w-checkbox-input"/>
	<label for="checkbox" class="field-label-5 w-form-label">
	Sonstiges
	</label>
	</div>
	</div>
	<div class="w-col w-col-6">
	</div>
	</div>
	</form>
	
	</div>
	<div id="div-member-3" class="w-form">
	<form id="email-form-4" name="email-form-4" data-name="Email Form 4" class="form-4">
	<label for="name-2" class="field-label-8">
	Ich bin im Verein / KSB/SSB, Landesfachverband oder LSB hauptamtlich tätig als
	</label>
	<label for="name-2" class="field-label-6">
	Mehrfachnennungen möglich
	</label>
	<div class="w-row">
	<div class="w-col w-col-6">
	<div class="checkbox-field w-checkbox">
	<input type="checkbox" id="fulltime1" name="checkbox" data-name="Checkbox" class="w-checkbox-input"/>
	<label for="checkbox" class="field-label-5 w-form-label">
	Geschäftsführer/in
	</label>
	</div>
	<div class="checkbox-field w-checkbox">
	<input type="checkbox" id="fulltime2" name="checkbox-10" data-name="Checkbox 10" class="w-checkbox-input"/>
	<label for="checkbox" class="field-label-5 w-form-label">
	Referent/in order Sachbearbeiter/in
	</label>
	</div>
	<div class="checkbox-field w-checkbox">
	<input type="checkbox" id="fulltime3" name="checkbox-9" data-name="Checkbox 9" class="w-checkbox-input"/>
	<label for="checkbox" class="field-label-5 w-form-label">
	Trainer/in
	</label>
	</div>
	<div class="checkbox-field w-checkbox">
	<input type="checkbox" id="fulltime4" name="checkbox-8" data-name="Checkbox 8" class="w-checkbox-input"/>
	<label for="checkbox" class="field-label-5 w-form-label">
	Sonstiges
	</label>
	</div>
	</div>
	<div class="w-col w-col-6">
	</div>
	</div>
	</form>
	
	</div>



	
	<div class="w-form">
	<form id="email-form-3" name="email-form-3" data-name="Email Form 3" class="form-3">
	<label for="name-2" class="field-label-8">Ich bin Inhaber einer DOSB-Lizenz (Übungsleiter, Vereinsmanager) und benötige eine gesonderte Teilnahmebestätigung zur Lizenzverlängerung.
	</label>
	<div class="div-block">
	<div class="radio-button-field-1 w-radio">
	<input type="radio" id="confirmyes" name="confirm" value="yes" data-name="confirm" required="" class="w-radio-input"/>
	<label for="yes" class="field-label-4 w-form-label">ja
	</label>
	</div>
	<div class="radio-button-field-4 w-radio">
	<input type="radio" id="confirmno" name="confirm" value="no" data-name="confirm" required="" class="w-radio-input"/>
	<label for="no" class="w-form-label">nein
	</label>
	</div>
	</div>
	</form>
	</div>
	
	
	<div class="w-form">
	<form id="email-form-3" name="email-form-3" data-name="Email Form 3" class="form-3">
	<label for="name-2" class="field-label-8">Ich bin als Lehrkraft in Sachsen-Anhalt tätig und nehme am Kongress als Fortbildungsmaßnahme unter der Reg.-Nr. WT 2018-400-48 teil
	</label>
	<div class="div-block">
	<div class="radio-button-field-1 w-radio">
	<input type="radio" id="teacheryes" name="teacher" value="yes" data-name="teacher" required="" class="w-radio-input"/>
	<label for="yes" class="field-label-4 w-form-label">ja
	</label>
	</div>
	<div class="radio-button-field-4 w-radio">
	<input type="radio" id="teacherno" name="teacher" value="no" data-name="teacher" required="" class="w-radio-input"/>
	<label for="no" class="w-form-label">nein
	</label>
	</div>
	</div>
	</form>
	</div>



	<div class="w-form">
	<form id="email-form-4" name="email-form-4" data-name="Email Form 4" class="form-4">
	<label for="email-6" class="field-label-9">
	An welchem Arbeitskreis möchten Sie teilnehmen?</label>
	<div class="w-row">
	<div class="column-2 w-clearfix w-col w-col-6">
	<img src="https://ilug.uni-halle.de/files/2018/06/viber-image-grey.png" width="20" class="image-2" id="ak1img"/>
	<label for="email-8" class="field-label-9">
	11:00 Uhr</label>
	<div class="radio-button-field-1 w-radio">
	<input type="radio" id="ak11" name="ak1" value="ak11" data-name="ak1" class="w-radio-input" onclick="UpdateCircles(this);"/>
	<label for="ak-50" class="field-label-4 w-form-label">
	1.1 Athletiktraining im Spannungsfeld bewährter Methoden und innovativer Ansätze (Teil I+II)</label>
	</div>
	<div class="radio-button-field-1 w-radio">
	<input type="radio" id="ak12" name="ak1" value="ak12" data-name="ak1" class="w-radio-input" onclick="UpdateCircles(this);"/>
	<label for="ak-51" class="field-label-4 w-form-label">
	1.2 Sportvereine vernetzen (Teil I+II)<br/>
	</label>
	</div>
	<div class="radio-button-field-1 w-radio">
	<input type="radio" id="ak13" name="ak1" value="ak13" data-name="ak1" class="w-radio-input" onclick="UpdateCircles(this);"/>
	<label for="ak-50" class="field-label-4 w-form-label">
	1.3 Bewegte Kinder (Teil I+II)</label>
	</div>
	<div class="radio-button-field-1 w-radio">
	<input type="radio" id="ak14" name="ak1" value="ak14" data-name="ak1" class="w-radio-input" onclick="UpdateCircles(this);"/>
	<label for="ak-50" class="field-label-4 w-form-label">
	1.4 Die Zukunft des Sports weitergedacht</label>
	</div>
	<div class="radio-button-field-1 w-radio">
	<input type="radio" id="ak15" name="ak1" value="ak15" data-name="ak1" class="w-radio-input" onclick="UpdateCircles(this);"/>
	<label for="ak-50" class="field-label-4 w-form-label">
	1.5 Sport für alle - Vielfalt erleben (Teil I+II)</label>
	</div>
	<div class="radio-button-field-1 w-radio">
	<input type="radio" id="ak16" name="ak1" value="ak16" data-name="ak1" class="w-radio-input" onclick="UpdateCircles(this);"/>
	<label for="ak-50" class="field-label-4 w-form-label">
	1.6 Junges Engagement entwickeln und fördern</label>
	</div>
	<div class="radio-button-field-1 w-radio">
	<input type="radio" id="ak17" name="ak1" value="ak17" data-name="ak1" class="w-radio-input" onclick="UpdateCircles(this);"/>
	<label for="ak-58" class="field-label-4 w-form-label">
	1.7 Spezielle Themen der Trainingswissenschaft</label>
	</div>
	<div class="radio-button-field-1 w-radio">
	<input type="radio" id="ak18" name="ak1" value="ak18" data-name="ak1" class="w-radio-input" onclick="UpdateCircles(this);"/>
	<label for="ak-51" class="field-label-4 w-form-label">
	1.8 Sport in der Jugendsozialarbeit</label>
	</div>
	<div class="radio-button-field-1 w-radio">
	<input type="radio" id="ak19" name="ak1" value="ak19" data-name="ak1" class="w-radio-input" onclick="UpdateCircles(this);"/>
	<label for="ak-51" class="field-label-4 w-form-label">
	1.9 Mut zur Zukunft: neugedachte Sportstätteninfrastruktur</label>
	</div>
	<img src="https://ilug.uni-halle.de/files/2018/06/viber-image.png" width="20" class="image-2" id="ak2img"/>
	<label for="email-8" class="field-label-9">
	12:15 Uhr</label>
	<div id="divak21" class="radio-button-field-1 w-radio">
	<input type="radio" id="ak21" name="ak2" value="ak21" data-name="ak2" class="w-radio-input" onclick="UpdateCircles(this);"/>
	<label for="ak-51" class="field-label-4 w-form-label">
	2.1 Athletiktraining im Spannungsfeld bewährter Methoden und innovativer Ansätze (Teil I+II)</label>
	</div>
	<div id="divak22" class="radio-button-field-1 w-radio">
	<input type="radio" id="ak22" name="ak2" value="ak22" data-name="ak2" class="w-radio-input" onclick="UpdateCircles(this);"/>
	<label for="ak-52" class="field-label-4 w-form-label">
	2.2 Sportvereine vernetzen (Teil I+II)<br/>
	</label>
	</div>
	<div id="divak23" class="radio-button-field-1 w-radio">
	<input type="radio" id="ak23" name="ak2" value="ak23" data-name="ak2" class="w-radio-input" onclick="UpdateCircles(this);"/>
	<label for="ak-53" class="field-label-4 w-form-label">
	2.3 Bewegte Kinder (Teil I+II)</label>
	</div>
	<div id="divak24" class="radio-button-field-1 w-radio">
	<input type="radio" id="ak24" name="ak2" value="ak24" data-name="ak2" class="w-radio-input" onclick="UpdateCircles(this);"/>
	<label for="ak-54" class="field-label-4 w-form-label">
	2.4 Zukunftsmodell - innovative Sportvereinsarbeit</label>
	</div>
	<div id="divak25" class="radio-button-field-1 w-radio">
	<input type="radio" id="ak25" name="ak2" value="ak25" data-name="ak2" class="w-radio-input" onclick="UpdateCircles(this);"/>
	<label for="ak-55" class="field-label-4 w-form-label">
	2.5 Sport für alle - Vielfalt erleben (Teil I+II)</label>
	</div>
	<div id="divak26" class="radio-button-field-1 w-radio">
	<input type="radio" id="ak26" name="ak2" value="ak26" data-name="ak2" class="w-radio-input" onclick="UpdateCircles(this);"/>
	<label for="ak-56" class="field-label-4 w-form-label">
	2.6 Engagiert im Verein - kompetent im Job</label>
	</div>
	<div id="divak27" class="radio-button-field-1 w-radio">
	<input type="radio" id="ak27" name="ak2" value="ak27" data-name="ak2" class="w-radio-input" onclick="UpdateCircles(this);"/>
	<label for="ak-50" class="field-label-4 w-form-label">
	2.7 Individualisierung und Optimierung des Trainings</label>
	</div>
	<div id="divak28" class="radio-button-field-1 w-radio">
	<input type="radio" id="ak28" name="ak2" value="ak28" data-name="ak2" class="w-radio-input" onclick="UpdateCircles(this);"/>
	<label for="ak-58" class="field-label-4 w-form-label">
	2.8 Neurokognition und Bewegung</label>
	</div>
	<div id="divak29" class="radio-button-field-1 w-radio">
	<input type="radio" id="ak29" name="ak2" value="ak29" data-name="ak2" class="w-radio-input" onclick="UpdateCircles(this);"/>
	<label for="ak-58" class="field-label-4 w-form-label">
	2.9 Vereinsentwicklung durch ärztlich verordneten Reha-Sport</label>
	</div>
	</div>
	<div class="w-clearfix w-col w-col-6">
	<img src="https://ilug.uni-halle.de/files/2018/06/viber-image.png" width="20" class="image-2" id="ak3img"/>
	<label for="email-8" class="field-label-9">
	15:00 Uhr</label>
	<div class="radio-button-field-1 w-radio">
	<input type="radio" id="ak31" name="ak3" value="ak31" data-name="ak3" class="w-radio-input" onclick="UpdateCircles(this);"/>
	<label for="ak-30" class="field-label-4 w-form-label">
	3.1 Ernährung und Sport (Teil I+II)</label>
	</div>
	<div class="radio-button-field-1 w-radio">
	<input type="radio" id="ak32" name="ak3" value="ak32" data-name="ak3" class="w-radio-input" onclick="UpdateCircles(this);"/>
	<label for="ak-31" class="field-label-4 w-form-label">
	3.2 Funktionelles (Kraft-)Training (Teil I+II)</label>
	</div>
	<div class="radio-button-field-1 w-radio">
	<input type="radio" id="ak33" name="ak3" value="ak33" data-name="ak3" class="w-radio-input" onclick="UpdateCircles(this);"/>
	<label for="ak-32" class="field-label-4 w-form-label">
	3.3 Bewegtes Alter - Bewegung und Leistungsfähigkeit (Teil I+II)</label>
	</div>
	<div class="radio-button-field-1 w-radio">
	<input type="radio" id="ak34" name="ak3" value="ak34" data-name="ak3" class="w-radio-input" onclick="UpdateCircles(this);"/>
	<label for="ak-33" class="field-label-4 w-form-label">
	3.4 Freiwilliges Engagement als Strategie zur Vereinsentwicklung</label>
	</div>
	<div class="radio-button-field-1 w-radio">
	<input type="radio" id="ak35" name="ak3" value="ak35" data-name="ak3" class="w-radio-input" onclick="UpdateCircles(this);"/>
	<label for="ak-34" class="field-label-4 w-form-label">
	3.5 Digital Wissen vernetzen (Teil I+II)</label>
	</div>
	<div class="radio-button-field-1 w-radio">
	<input type="radio" id="ak36" name="ak3" value="ak36" data-name="ak3" class="w-radio-input" onclick="UpdateCircles(this);"/>
	<label for="ak-35" class="field-label-4 w-form-label">
	3.6 Sportvereine als soziales Zentrum (Teil I+II)</label>
	</div>
	<div class="radio-button-field-1 w-radio">
	<input type="radio" id="ak37" name="ak3" value="ak37" data-name="ak3" class="w-radio-input" onclick="UpdateCircles(this);"/>
	<label for="ak-36" class="field-label-4 w-form-label">
	3.7 Kinderschutz im Sport - Prävention und Intervention</label>
	</div>
	<div class="radio-button-field-1 w-radio">
	<input type="radio" id="ak38" name="ak3" value="ak38" data-name="ak3" class="w-radio-input" onclick="UpdateCircles(this);"/>
	<label for="ak-37" class="field-label-4 w-form-label">
	3.8 Sportpsychologie in der Praxis (Teil I+II)</label>
	</div>
	<div class="radio-button-field-1 w-radio">
	<input type="radio" id="ak39" name="ak3" value="ak39" data-name="ak3" class="w-radio-input" onclick="UpdateCircles(this);"/>
	<label for="ak-38" class="field-label-4 w-form-label">
	3.9 Digitalisierung im Sportunterricht und Training</label>
	</div>
	<img src="https://ilug.uni-halle.de/files/2018/06/viber-image.png" width="20" class="image-2" id="ak4img"/>
	<label for="email-8" class="field-label-9">
	16:15 Uhr</label>
	<div id="divak41" class="radio-button-field-1 w-radio">
	<input type="radio" id="ak41" name="ak4" value="ak41" data-name="ak4" class="w-radio-input" onclick="UpdateCircles(this);"/>
	<label for="ak-39" class="field-label-4 w-form-label">
	4.1 Ernährung und Sport (Teil I+II)</label>
	</div>
	<div id="divak42" class="radio-button-field-1 w-radio">
	<input type="radio" id="ak42" name="ak4" value="ak42" data-name="ak4" class="w-radio-input" onclick="UpdateCircles(this);"/>
	<label for="ak-40" class="field-label-4 w-form-label">
	4.2 Funktionelles (Kraft-)Training (Teil I+II)</label>
	</div>
	<div id="divak43" class="radio-button-field-1 w-radio">
	<input type="radio" id="ak43" name="ak4" value="ak43" data-name="ak4" class="w-radio-input" onclick="UpdateCircles(this);"/>
	<label for="ak-41" class="field-label-4 w-form-label">
	4.3 Bewegtes Alter - Bewegung und Leistungsfähigkeit (Teil I+II)</label>
	</div>
	<div id="divak44" class="radio-button-field-1 w-radio">
	<input type="radio" id="ak44" name="ak4" value="ak44" data-name="ak4" class="w-radio-input" onclick="UpdateCircles(this);"/>
	<label for="ak-42" class="field-label-4 w-form-label">
	4.4 Ehrenamt - m(eine) persönliche Herausforderung</label>
	</div>
	<div id="divak45" class="radio-button-field-1 w-radio">
	<input type="radio" id="ak45" name="ak4" value="ak45" data-name="ak4" class="w-radio-input" onclick="UpdateCircles(this);"/>
	<label for="ak-43" class="field-label-4 w-form-label">
	4.5 Digital Wissen vernetzen (Teil I+II)</label>
	</div>
	<div id="divak46" class="radio-button-field-1 w-radio">
	<input type="radio" id="ak46" name="ak4" value="ak46" data-name="ak4" class="w-radio-input" onclick="UpdateCircles(this);"/>
	<label for="ak-44" class="field-label-4 w-form-label">
	4.6 Sportvereine als soziales Zentrum (Teil I+II)</label>
	</div>
	<div id="divak47" class="radio-button-field-1 w-radio">
	<input type="radio" id="ak47" name="ak4" value="ak47" data-name="ak4" class="w-radio-input" onclick="UpdateCircles(this);"/>
	<label for="ak-45" class="field-label-4 w-form-label">
	4.7 Vereine als "Mitspieler" im Gesundheitssport</label>
	</div>
	<div id="divak48" class="radio-button-field-1 w-radio">
	<input type="radio" id="ak48" name="ak4" value="ak48" data-name="ak4" class="w-radio-input" onclick="UpdateCircles(this);"/>
	<label for="ak-46" class="field-label-4 w-form-label">
	4.8 Sportpsychologie in der Praxis (Teil I+II)</label>
	</div>
	<div id="divak49" class="radio-button-field-1 w-radio">
	<input type="radio" id="ak49" name="ak4" value="ak49" data-name="ak4" class="w-radio-input" onclick="UpdateCircles(this);"/>
	<label for="ak-47" class="field-label-4 w-form-label">
	4.9 E-Sports - Vereinssport der Zukunft</label>
	</div>
	</div>
	</div>
	</form>
	
	</div>
	
	
	<div class="w-form">
	<form id="email-form-3" name="email-form-3" data-name="Email Form 3" class="form-3">
	<label for="name-2" class="field-label-8">Ich willige ein, dass im Rahmen des Sportkongresses von mir angefertigte Fotos zum Zwecke der Berichterstattung verarbeitet werden dürfen. Dies betrifft auch die Veröffentlichung im Internet (z. B. Bildergalerie) und in sozialen Netzwerken wie Facebook. Ich willige weiter ein, dass die im Rahmen der Veranstaltung gefertigten Aufnahmen für die Ankündigung weiterer Veranstaltungen des LSB Sachsen-Anhalt e.V. verwendet werden dürfen.
	<br/>
	</label>
	<label for="name-2" class="field-label-11">Ich bin darüber informiert, dass es trotz ausreichender technischer Maßnahmen zur Gewährleistung des Datenschutzes bei einer Veröffentlichung von personenbezogenen Daten (z.B. Fotos) im Internet ein umfassender Datenschutz nicht garantiert werden kann. Die damit verbundenen Risiken für eine eventuelle Persönlichkeitsverletzung sind mir bewusst. Mir ist insbesondere bekannt, dass personenbezogenen Daten durch Veröffentlichung im Internet auch in Staaten abrufbar sind, die keine der Europäischen Union vergleichbaren Datenschutzbestimmungen kennen und dass die Vertraulichkeit, die Integrität (Unverletzlichkeit), die Authentizität (Echtheit) und die Verfügbarkeit der personenbezogenen Daten nicht garantiert werden kann.
	<br/>
	</label>
	<label for="name-2" class="field-label-12">Diese Einwilligung kann ich jederzeit für die Zukunft widerrufen (Kontaktdaten 
	<a href="https://ilug.uni-halle.de/files/2018/07/AGB_LSB-Sportkongress.docx.pdf" target="_blank">siehe AGB
	</a>). Meine Einwilligung erfolgt freiwillig. Wenn ich diese nicht erteile, hat das keine Auswirkungen auf mein Teilnahmerecht am Sportkongress. Die 
	<a href="https://ilug.uni-halle.de/files/2018/06/Datenschutzerklärung_LSB-Sportkongress.pdf" target="_blank">Datenschutzbestimmungen 
	</a>habe ich zur Kenntnis genommen.
	<br/>
	</label>
	<div class="div-block">
	<div class="radio-button-field-1 w-radio">
	<input type="radio" id="mediayes" name="confirmation" value="yes" data-name="confirmation" required="" class="w-radio-input"/>
	<label for="yes" class="field-label-4 w-form-label">ja
	</label>
	</div>
	<div class="radio-button-field-4 w-radio">
	<input type="radio" id="mediano" name="confirmation" value="no" data-name="confirmation" required="" class="w-radio-input"/>
	<label for="no" class="w-form-label">nein
	</label>
	</div>
	</div>
	</form>
	
	</div>
	<div class="w-form">
	<form id="email-form-5" name="email-form-5" data-name="Email Form 5" class="form-5">
	<label for="name-2" class="field-label-7">
	<strong class="bold-text">Bitte informieren Sie mich über weitere Informationen, z. B. Bildungsangebote, des LSB Sachsen-Anhalt e.V. Mir ist bewusst, dass zu diesem Zweck mein Name, meine Kommunikationsdaten (Post- und E-Mailadresse) bis auf Widerruf (Kontaktdaten 
	</strong>
	<a href="https://ilug.uni-halle.de/files/2018/07/AGB_LSB-Sportkongress.docx.pdf" target="_blank">
	<strong class="bold-text">siehe AGB
	</strong>
	</a>
	<strong class="bold-text">) verarbeitet werden. Dazu erteile ich meine Einwilligung. Diese kann ich jederzeit für die Zukunft widerrufen. Das bedeutet, dass die Rechtmäßigkeit der bis zum Widerruf auf Grund der Einwilligung erfolgten Verarbeitung nicht berührt wird.
	</strong>
	<br/>
	</label>
	<div class="div-block">
	<div class="radio-button-field-1 w-radio">
	<input type="radio" id="newsyes" name="newsletter" value="yes" data-name="newsletter" class="w-radio-input"/>
	<label for="yes" class="field-label-4 w-form-label">ja
	</label>
	</div>
	<div class="radio-button-field-4 w-radio">
	<input type="radio" id="newsno" name="newsletter" value="no" data-name="newsletter" class="w-radio-input"/>
	<label for="no" class="w-form-label">nein
	</label>
	</div>
	</div>
	<div class="checkbox-field-3 w-checkbox">
	<input type="checkbox" id="acceptagb" name="checkbox-11" data-name="Checkbox 11" class="w-checkbox-input"/>
	<label for="checkbox-12" class="w-form-label">Es gelten die hier verlinkten (
	<a href="https://ilug.uni-halle.de/files/2018/07/AGB_LSB-Sportkongress.docx.pdf" target="_blank">AGB
	</a>, 
	<a href="https://ilug.uni-halle.de/files/2018/06/Datenschutzerklärung_LSB-Sportkongress.pdf" target="_blank">Datenschutzbestimmungen
	</a>) und auf der Kongresshomepage des LSB veröffentlichten 
	<a href="https://ilug.uni-halle.de/files/2018/07/AGB_LSB-Sportkongress.docx.pdf" target="_blank">AGB 
	</a>und 
	<a href="https://ilug.uni-halle.de/files/2018/06/Datenschutzerklärung_LSB-Sportkongress.pdf" target="_blank">Datenschutzbestimmungen
	</a>. Hiermit bestätige ich, dass ich diese zur Kenntnis genommen und verstanden habe und diese akzeptiere.
	</label>
	</div>
	
	
	<div id="errorsuccess" class="text-block-2">
	error or success
	</div>
	<input type="submit" value="Verbindlich anmelden (Teilnahmegebühr: 50€)" data-wait="Bitte warten..." id="submitbutton" class="submit-button w-button"onclick="return false;"/>
	</form>
	
	</div>
	</div>
-->
	<script src="https://code.jquery.com/jquery-3.3.1.min.js" type="text/javascript" integrity="sha256-FgpCb/KJQlLNfOu91ta32o/NMZxltwRo8QtmkMRdAu8=" crossorigin="anonymous">
	</script>
	
	
	<script>
		function SubmitForm()
		{
			
			var today = new Date();
			var end = new Date("2018-09-20"); // < 20.9.
			
			if(today >= end)
			{
				document.getElementById("errorsuccess").style.color = "#992200";
				document.getElementById("errorsuccess").style.fontWeight = "bold";
				document.getElementById("errorsuccess").innerHTML = "Anmeldung nicht mehr online möglich!";
				
				return false;
			}
			
			// Werte abrufen
			
			var name = document.getElementById("name").value;
			var prename = document.getElementById("prename").value;
			var street = document.getElementById("street").value;
			var plz_city = document.getElementById("plz_city").value;
			var bday = document.getElementById("bday").value;
			var tel = document.getElementById("tel").value;
			var email = document.getElementById("email").value;
			
			// member or not
			var memberyes = document.getElementById("memberyes").checked;
			var memberno = document.getElementById("memberno").checked;
			
			var club = document.getElementById("club").value;
			var clubnr = document.getElementById("clubnr").value;
			
			var unsaleried = [];
			for(var i = 1; i < 7; i++)
			{
				unsaleried.push(document.getElementById("unsaleried"+i.toString()).checked);
			}

			var fulltime = [];
			for(var i = 1; i < 5; i++)
			{
				fulltime.push(document.getElementById("fulltime"+i.toString()).checked);
			}

			// confirmation or not
			var confirmyes = document.getElementById("confirmyes").checked;
			var confirmno = document.getElementById("confirmno").checked;
			
			// teacher or not
			var teacheryes = document.getElementById("teacheryes").checked;
			var teacherno = document.getElementById("teacherno").checked;
			
			var ak = [];
			
			ak[1] = GetAk("ak1");
			ak[2] = GetAk("ak2");
			ak[3] = GetAk("ak3");
			ak[4] = GetAk("ak4");

			// media or not
			var mediayes = document.getElementById("mediayes").checked;
			var mediano = document.getElementById("mediano").checked;
			
			var acceptagb = document.getElementById("acceptagb").checked;

			// news or not
			var newsyes = document.getElementById("newsyes").checked;
			var newsno = document.getElementById("newsno").checked;

			
			
			
			// prüfen ob alle Werte angegeben
			
			var missing = "";
			
			if(!name.trim())
				missing += "Name</br>";
			
			if(!prename.trim())
				missing += "Vorname</br>";
				
			if(!street.trim())
				missing += "Straße, Nr.</br>";

			if(!plz_city.trim())
				missing += "PLZ / Ort</br>";
			
			if(!bday.trim())
				missing += "Geburtstag</br>";

			if(!tel.trim())
				missing += "Telefon</br>";
			
			if(!email.trim())
				missing += "E-Mail</br>";

			if(!memberyes && !memberno)
				missing += "Angabe, ob Sie Mitglied in einem Verein des LSB sind</br>";
			
			if(memberyes && !club.trim())
				missing += "Vereinsname</br>";
			
			if(!acceptagb)
				missing += "Akzeptieren der AGB und Datenschutzbestimmungen</br>";
			
			
			if(missing)
			{
				document.getElementById("errorsuccess").style.color = "#992200";
				document.getElementById("errorsuccess").style.fontWeight = "bold";
				document.getElementById("errorsuccess").innerHTML = "Folgende Informationen fehlen:</br></br>"+missing;
				
				return false;
			}
			
			// invoice value berechnen
			
			var invoicevalue = CalcInvoiceValue();
			
			// in Objekt verpacken
			
			var data = {
				invoicevalue: invoicevalue,
				
				name: name,
				prename: prename,
				street: street,
				plz_city: plz_city,
				bday: bday,

				tel: tel,
				email: email,

				member_or_not: memberyes?"yes":"no",
				
				club: club,
				clubnr: clubnr,
				
				unsaleried: unsaleried,
				fulltime: fulltime,
				
				confirm_or_not: confirmyes?"yes":"no",
				teacher_or_not: teacheryes?"yes":"no",

				ak: ak,

				acceptmedia: mediayes?"yes":"no",
				acceptagb: acceptagb,

				news_or_not: newsyes?"yes":"no"
				
			};
			
			// senden
			
			var http = new XMLHttpRequest();
			
			var url = '?sendrequest=true';
			var params = "data="+JSON.stringify(data);
			http.open('POST', url, true);

			//Send the proper header information along with the request
			http.setRequestHeader('Content-type', 'application/x-www-form-urlencoded');

			http.onreadystatechange = function() {//Call a function when the state changes.
				if(http.readyState == 4 && http.status == 200) {
					//alert(http.responseText);
				}

				if(http.readyState == 4)
				{
					if(http.status == 200)
					{
						document.getElementById("errorsuccess").style.color = "#009922";
						document.getElementById("errorsuccess").style.fontWeight = "bold";
						document.getElementById("errorsuccess").innerHTML = "Die Anmeldung war erfolgreich! Sie erhalten in Kürze eine E-Mail!";
					}
					else
					{
						document.getElementById("errorsuccess").style.color = "#e82c2c";
						document.getElementById("errorsuccess").style.fontWeight = "bold";
						document.getElementById("errorsuccess").innerHTML = "Es ist ein Fehler aufgetreten! Bitte versuchen Sie es erneut!";

						document.getElementById("submitbutton").style.display = "block";
					}
				}
			}
			http.send(params);
			
			document.getElementById("errorsuccess").style.color = "#f2e30f";
			document.getElementById("errorsuccess").style.fontWeight = "bold";
			document.getElementById("errorsuccess").innerHTML = "Ihre Anmeldung wird bearbeitet...";
			
			document.getElementById("submitbutton").style.display = "none";
			
			return false;
		}

		function UpdateInvoiceValueView()
		{
			var value = CalcInvoiceValue();
			
			document.getElementById("submitbutton").value= "Verbindlich anmelden (Teilnahmegebühr: "+value.toString()+"€)";
		}

		function CalcInvoiceValue()
		{
			var value = DateBaseInvoiceValue();
			
			var memberyes = document.getElementById("memberyes").checked;
			if(!memberyes)
				value += 15;
						
			return value;
		}

		function DateBaseInvoiceValue()
		{
			var today = new Date();
			var firstDeadline = new Date("2018-09-08"); // < 8.9.
			var secondDeadline = new Date("2018-09-22"); // < 22.9.
			
			if(today < firstDeadline)
			{
				return 35;
			}
			else if(today < secondDeadline)
			{
				return 45;
			}
			else
			{
				return 55;
			}
		}

		function ShowMemberForm()
		{
			document.getElementById("div-member-1").style.display = "block";
			document.getElementById("div-member-2").style.display = "block";
			document.getElementById("div-member-3").style.display = "block";
		}

		function HideMemberForm()
		{
			document.getElementById("div-member-1").style.display = "none";
			document.getElementById("div-member-2").style.display = "none";
			document.getElementById("div-member-3").style.display = "none";
		}
		
		function UpdateCircles(obj)
		{
			switch(obj.name)
			{
				case "ak1":
					HideAk2Doubles();
					if(obj.value == "ak11")
						CheckAndShow("ak21");
					else if(obj.value == "ak12")
						CheckAndShow("ak22");
					else if(obj.value == "ak13")
						CheckAndShow("ak23");
					else if(obj.value == "ak15")
						CheckAndShow("ak25");
					else
					{
						document.getElementById("ak21").checked = false;
						document.getElementById("ak22").checked = false;
						document.getElementById("ak23").checked = false;
						document.getElementById("ak25").checked = false;
					}
					
					break;
				case "ak2":
					HideAk2Doubles();
					if(obj.value == "ak21")
						CheckAndShow("ak11");
					else if(obj.value == "ak22")
						CheckAndShow("ak12");
					else if(obj.value == "ak23")
						CheckAndShow("ak13");
					else if(obj.value == "ak25")
						CheckAndShow("ak15");
					else
					{
						document.getElementById("ak11").checked = false;
						document.getElementById("ak12").checked = false;
						document.getElementById("ak13").checked = false;
						document.getElementById("ak15").checked = false;
					}
					break;
				case "ak3":
					HideAk4Doubles();
					if(obj.value == "ak31")
						CheckAndShow("ak41");
					else if(obj.value == "ak32")
						CheckAndShow("ak42");
					else if(obj.value == "ak33")
						CheckAndShow("ak43");
					else if(obj.value == "ak35")
						CheckAndShow("ak45");
					else if(obj.value == "ak36")
						CheckAndShow("ak46");
					else if(obj.value == "ak38")
						CheckAndShow("ak48");
					else
					{
						document.getElementById("ak41").checked = false;
						document.getElementById("ak42").checked = false;
						document.getElementById("ak43").checked = false;
						document.getElementById("ak45").checked = false;
						document.getElementById("ak46").checked = false;
						document.getElementById("ak48").checked = false;
					}
					break;
				case "ak4":
					HideAk4Doubles();
					if(obj.value == "ak41")
						CheckAndShow("ak31");
					else if(obj.value == "ak42")
						CheckAndShow("ak32");
					else if(obj.value == "ak43")
						CheckAndShow("ak33");
					else if(obj.value == "ak45")
						CheckAndShow("ak35");
					else if(obj.value == "ak46")
						CheckAndShow("ak36");
					else if(obj.value == "ak48")
						CheckAndShow("ak38");
					else
					{
						document.getElementById("ak31").checked = false;
						document.getElementById("ak32").checked = false;
						document.getElementById("ak33").checked = false;
						document.getElementById("ak35").checked = false;
						document.getElementById("ak36").checked = false;
						document.getElementById("ak38").checked = false;
					}
					break;
			}
			
			UpdateCheckmarks();
		}
		
		function UpdateCheckmarks()
		{
			for(var i = 1; i < 5; i++)
			{
				var isset = false;
				for(var j = 1; j < 10; j++)
				{
					if(document.getElementById("ak"+i.toString()+j.toString()).checked == true)
						isset = true;
				}
				
				if(isset == true)
					document.getElementById("ak"+i.toString()+"img").src = "https://ilug.uni-halle.de/files/2018/06/viber-image.png";
				else
					document.getElementById("ak"+i.toString()+"img").src = "https://ilug.uni-halle.de/files/2018/06/viber-image-grey.png";
			}
		}
		
		function CheckAndShow(id)
		{
			document.getElementById(id).checked = true;
			document.getElementById("div"+id).style.display = "block";
		}
		
		function HideAk2Doubles()
		{
			document.getElementById("divak21").style.display="none";
			document.getElementById("divak22").style.display="none";
			document.getElementById("divak23").style.display="none";
			document.getElementById("divak25").style.display="none";
		}
		
		function HideAk4Doubles()
		{
			document.getElementById("divak41").style.display="none";
			document.getElementById("divak42").style.display="none";
			document.getElementById("divak43").style.display="none";
			document.getElementById("divak45").style.display="none";
			document.getElementById("divak46").style.display="none";
			document.getElementById("divak48").style.display="none";
		}

		function GetAk(name)
		{
			for(var i = 1; i < 10; i++)
			{
				if(document.getElementById(name+i.toString()).checked == true)
					return name+i.toString();
			}
			
			return "";
		}
		
		HideMemberForm();
		UpdateInvoiceValueView();
		UpdateCheckmarks();
		HideAk2Doubles();
		HideAk4Doubles();
		document.getElementById("errorsuccess").innerHTML = "";
	</script>
	
	
	</body>
	
	

	</html>
	
