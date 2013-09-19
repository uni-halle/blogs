var calendar1, calendar2;

function pageLoadCompleted(){
	calendar1 = new Epoch('cal1','popup',document.getElementById('calendarInit'),false);
}
if( window.addEventListener ) {
	//alert('i am not IE :)');
	window.addEventListener('load',pageLoadCompleted,false);
} else if( window.attachEvent ) {
	//alert('i am IE :(');
	window.attachEvent('onload',pageLoadCompleted);
} else {
	alert('problem with javascript');
}

function validate_form( thisform ) {
var startDate = thisform.startDat?thisform.startDat.value:thisform.start_date.value;
var endDate = thisform.deadline?thisform.deadline.value:thisform.end_date.value;

//Creating date in  'm/d/Y' format again
startDate = startDate.split('.');
var dateTmp  = startDate[0];
startDate[0] = startDate[1];
startDate[1] = dateTmp;
startDate = startDate.join('/');

endDate = endDate.split('.');
dateTmp  = endDate[0];
endDate[0] = endDate[1];
endDate[1] = dateTmp;
endDate = endDate.join('/');
//check if date validates

if(startDate.trim().length > 3) { 
	if(Date.parse(startDate) < Date.parse(endDate)) {
	   		return true;
	   		//Date.parse(endDate) > Date.parse(new Date()
	   }
	alert( 'Ending date must be greater than start date.' );
	return false;
}
}
