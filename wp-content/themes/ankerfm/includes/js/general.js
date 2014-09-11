jQuery(window).load(function(){

var height_1 = jQuery('#extended-footer .one').height();
var height_2 = jQuery('#extended-footer .two').height();
var height_3 = jQuery('#extended-footer .three').height();


if ((height_1 > height_2) && (height_1 > height_3)){
	jQuery('#extended-footer .two').height(height_1);
	jQuery('#extended-footer .three').height(height_1);
} else if ((height_2 > height_1) && (height_1 > height_3)){
	jQuery('#extended-footer .one').height(height_2);
	jQuery('#extended-footer .three').height(height_2);
} else if ((height_3 > height_1) && (height_3 > height_2)){
	jQuery('#extended-footer .two').height(height_3);
	jQuery('#extended-footer .one').height(height_3);
}

});