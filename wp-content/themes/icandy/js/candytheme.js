function changeFontSize() {
    if(document.getElementById('fontoperation').value == "inc") {
        document.getElementById('fontoperation').value = "dec";
        document.body.style.fontSize = "14px";
    }
    else if(document.getElementById('fontoperation').value == "dec") {
        document.getElementById('fontoperation').value = "inc";
        document.body.style.fontSize = "12px";
    }
}

function showComments() {
    var $j = jQuery.noConflict();
    document.getElementById('commentstab').style.backgroundPosition= "bottom left";
    document.getElementById('pingstab').style.backgroundPosition= "top left";
    $j('#pings_section').fadeOut(500);
    $j('#comments_section').fadeIn(500);
}

function showTrackbacks() {
    var $j = jQuery.noConflict();
    document.getElementById('commentstab').style.backgroundPosition= "top left";
    document.getElementById('pingstab').style.backgroundPosition= "bottom left";
    $j('#comments_section').fadeOut(500);
    $j('#pings_section').fadeIn(500);
}