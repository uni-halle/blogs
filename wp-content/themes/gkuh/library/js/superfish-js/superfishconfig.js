jQuery(function($){
    $(document).ready(function(){ // superFish
       $('ul.top-nav').supersubs({
		animation: {opacity:'show'},   // an object equivalent to first parameter of jQuery’s .animate() method. Used to animate the submenu open
		animationOut: {opacity:'hide'},   // an object equivalent to first parameter of jQuery’s .animate() method Used to animate the submenu closed
		speedOut: 'fast',
		delay: 200
		})
    .superfish({
	animation: {height:'show'},   // slide-down effect without fade-in
	animationOut: {height:'hide'},   // slide-down effect without fade-in
	cssArrows: true,               // set to false if you want to remove the CSS-based arrow triangles
	delay: 200               // 1.2 second delay on mouseout
 }); // call supersubs first, then superfish
     });
    console.log('works');
});