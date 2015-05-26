document.write('<link rel="stylesheet" href="' + SkpTransparent_url + 'buttons/transparent_dropdown/dropdown.css" type="text/css" media="screen" />');

SkpTransparent_preload1 = new Image();
SkpTransparent_preload1.src = SkpTransparent_url + "buttons/transparent_dropdown/call_over.png";
SkpTransparent_preload2 = new Image();
SkpTransparent_preload2.src = SkpTransparent_url + "buttons/transparent_dropdown/call.png";
SkpTransparent_preload3 = new Image();
SkpTransparent_preload3.src = SkpTransparent_url + "buttons/transparent_dropdown/up.png";
SkpTransparent_preload4 = new Image();
SkpTransparent_preload4.src = SkpTransparent_url + "buttons/transparent_dropdown/down.png";
SkpTransparent_preload5 = new Image();
SkpTransparent_preload5.src = SkpTransparent_url + "buttons/transparent_dropdown/2_over.png";
SkpTransparent_preload6 = new Image();
SkpTransparent_preload6.src = SkpTransparent_url + "buttons/transparent_dropdown/3_over.png";
SkpTransparent_preload7 = new Image();
SkpTransparent_preload7.src = SkpTransparent_url + "buttons/transparent_dropdown/4_over.png";
SkpTransparent_preload8 = new Image();
SkpTransparent_preload8.src = SkpTransparent_url + "buttons/transparent_dropdown/5_over.png";
SkpTransparent_preload9 = new Image();
SkpTransparent_preload9.src = SkpTransparent_url + "buttons/transparent_dropdown/6_over.png";

var timerTransparent = false;

function skpBtnOvr(where) {
    skypeTransparentDrpDown()
	var myId = where.id;
	var myImage = document.getElementById(myId);
	var idArray = myId.split("-");
	myImage.src = SkpTransparent_url + "buttons/transparent_dropdown/"+idArray[1]+"_over.png"
}
function skpBtnOut(where) {
    skypeTransparentDrpUp()
	var myId = where.id;
	var myImage = document.getElementById(myId);
	var idArray = myId.split("-");
	myImage.src = SkpTransparent_url + "buttons/transparent_dropdown/"+idArray[1]+".png"
}
function skypeTransparentDrpDown() {
    if(timerTransparent) { clearTimeout(timerTransparent); timerTransparent = false; }
    var skypeTransparentDrpCall = document.getElementById('skypeTransparentDrpCall');
    var skypeTransparentDrpArrow = document.getElementById('skypeTransparentDrpArrow');
    var skypeDropdowntransparent = document.getElementById('skypeDropdown-transparent');
    skypeDropdowntransparent.style.display = "block";
    skypeTransparentDrpCall.src = SkpTransparent_url + "buttons/transparent_dropdown/call_over.png";
    skypeTransparentDrpArrow.src = SkpTransparent_url + "buttons/transparent_dropdown/up.png";
}
function skypeTransparentDrpUp() {
    timerTransparent = setTimeout("skypeTransparentDrpClose()", 600);
}
function skypeTransparentDrpClose() {
    var skypeDropdowntransparent = document.getElementById('skypeDropdown-transparent');
    skypeDropdowntransparent.style.display = "none";
    var skypeTransparentDrpCall = document.getElementById('skypeTransparentDrpCall');
    var skypeTransparentDrpArrow = document.getElementById('skypeTransparentDrpArrow');
    skypeTransparentDrpCall.src = SkpTransparent_url + "buttons/transparent_dropdown/call.png";
    skypeTransparentDrpArrow.src = SkpTransparent_url + "buttons/transparent_dropdown/down.png";
}
