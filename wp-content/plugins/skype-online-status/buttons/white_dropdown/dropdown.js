document.write('<link rel="stylesheet" href="' + SkpWhite_url + 'buttons/white_dropdown/dropdown.css" type="text/css" media="screen" />');

SkpWhite_preload1 = new Image();
SkpWhite_preload1.src = SkpWhite_url + "buttons/white_dropdown/call_over.png";
SkpWhite_preload2 = new Image();
SkpWhite_preload2.src = SkpWhite_url + "buttons/white_dropdown/call.png";
SkpWhite_preload3 = new Image();
SkpWhite_preload3.src = SkpWhite_url + "buttons/white_dropdown/up.png";
SkpWhite_preload4 = new Image();
SkpWhite_preload4.src = SkpWhite_url + "buttons/white_dropdown/down.png";
SkpWhite_preload5 = new Image();
SkpWhite_preload5.src = SkpWhite_url + "buttons/white_dropdown/2_over.png";
SkpWhite_preload6 = new Image();
SkpWhite_preload6.src = SkpWhite_url + "buttons/white_dropdown/3_over.png";
SkpWhite_preload7 = new Image();
SkpWhite_preload7.src = SkpWhite_url + "buttons/white_dropdown/4_over.png";
SkpWhite_preload8 = new Image();
SkpWhite_preload8.src = SkpWhite_url + "buttons/white_dropdown/5_over.png";
SkpWhite_preload9 = new Image();
SkpWhite_preload9.src = SkpWhite_url + "buttons/white_dropdown/6_over.png";

var timerWhite = false;

function skpBtnOvrWhite(where) {
    skypeWhiteDrpDown()
	var myId = where.id;
	var myImage = document.getElementById(myId);
	var idArray = myId.split("-");
	myImage.src = SkpWhite_url + "buttons/white_dropdown/"+idArray[1]+"_over.png"
}
function skpBtnOutWhite(where) {
    skypeWhiteDrpUp()
	var myId = where.id;
	var myImage = document.getElementById(myId);
	var idArray = myId.split("-");
	myImage.src = SkpWhite_url + "buttons/white_dropdown/"+idArray[1]+".png"
}
function skypeWhiteDrpDown() {
    if(timerWhite) { clearTimeout(timerWhite); timerWhite = false; }
    var skypeWhiteDrpCall = document.getElementById('skypeWhiteDrpCall');
    var skypeWhiteDrpArrow = document.getElementById('skypeWhiteDrpArrow');
    var skypeDropdownwhite = document.getElementById('skypeDropdown-white');
    skypeDropdownwhite.style.display = "block";
    skypeWhiteDrpCall.src = SkpWhite_url + "buttons/white_dropdown/call_over.png";
    skypeWhiteDrpCall.height = "44";
    skypeWhiteDrpArrow.src = SkpWhite_url + "buttons/white_dropdown/up.png";
    skypeWhiteDrpArrow.height = "44";
}
function skypeWhiteDrpUp() {
    timerWhite = setTimeout("skypeWhiteDrpClose()", 600);
}
function skypeWhiteDrpClose() {
    var skypeDropdownwhite = document.getElementById('skypeDropdown-white');
    skypeDropdownwhite.style.display = "none";
    var skypeWhiteDrpCall = document.getElementById('skypeWhiteDrpCall');
    var skypeWhiteDrpArrow = document.getElementById('skypeWhiteDrpArrow');
    skypeWhiteDrpCall.src = SkpWhite_url + "buttons/white_dropdown/call.png";
    skypeWhiteDrpCall.height = "52";
    skypeWhiteDrpArrow.src = SkpWhite_url + "buttons/white_dropdown/down.png";
    skypeWhiteDrpArrow.height = "52";
}
