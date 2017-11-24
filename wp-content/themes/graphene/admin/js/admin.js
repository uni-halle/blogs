jQuery(document).ready(function ($) {
	
});


function hexToR(h) {
	if (h.length == 4)
		return parseInt((cutHex(h)).substring(0, 1) + (cutHex(h)).substring(0, 1), 16);
	if (h.length == 7)
		return parseInt((cutHex(h)).substring(0, 2), 16);
}

function hexToG(h) {
	if (h.length == 4)
		return parseInt((cutHex(h)).substring(1, 2) + (cutHex(h)).substring(1, 2), 16);
	if (h.length == 7)
		return parseInt((cutHex(h)).substring(2, 4), 16);
}

function hexToB(h) {
	if (h.length == 4)
		return parseInt((cutHex(h)).substring(2, 3) + (cutHex(h)).substring(2, 3), 16);
	if (h.length == 7)
		return parseInt((cutHex(h)).substring(4, 6), 16);
}

function cutHex(h) {
	return (h.charAt(0) == "#") ? h.substring(1, 7) : h
}

function grapheneCheckFile(f, type) {
	type = (typeof type === "undefined") ? 'options' : type;
	f = f.elements;
	if (/.*\.(txt)$/.test(f['upload'].value.toLowerCase()))
		return true;
	if (type == 'options') alert(grapheneAdminScript.import_select_file);
	else if (type == 'colours') alert(grapheneAdminScript.preset_select_file);
	f['upload'].focus();
	return false;
};

function grapheneSetCookie(name, value, days) {
	if (days) {
		var date = new Date();
		date.setTime(date.getTime() + (days * 24 * 60 * 60 * 1000));
		var expires = "; expires=" + date.toGMTString();
	} else var expires = "";
	document.cookie = name + "=" + value + expires + "; path=/";
}

function grapheneGetCookie(name) {
	var nameEQ = name + "=";
	var ca = document.cookie.split(';');
	for (var i = 0; i < ca.length; i++) {
		var c = ca[i];
		while (c.charAt(0) == ' ') c = c.substring(1, c.length);
		if (c.indexOf(nameEQ) == 0) return c.substring(nameEQ.length, c.length);
	}
	return null;
}

function grapheneDeleteCookie(name) {
	grapheneSetCookie(name, "", -1);
}

function grapheneSelectText(element) {
	var doc = document;
	var text = doc.getElementById(element);

	if (doc.body.createTextRange) { // ms
		var range = doc.body.createTextRange();
		range.moveToElementText(text);
		range.select();
	} else if (window.getSelection) { // moz, opera, webkit
		var selection = window.getSelection();
		var range = doc.createRange();
		range.selectNodeContents(text);
		selection.removeAllRanges();
		selection.addRange(range);
	}
}

function graphene_show_message(response) {
	jQuery('.graphene-ajax-response').html(response).fadeIn(400);
}

function graphene_fade_message() {
	jQuery('.graphene-ajax-response').fadeOut(1000);
	clearTimeout(t);
}