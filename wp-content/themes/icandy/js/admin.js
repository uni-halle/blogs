// Admin js

function incrementWidth() {
    var widthval = parseInt(document.getElementById('sidebarwidth').value);
    if(widthval < 500) {
        widthval = widthval + 5;
    }
    document.getElementById('sidebarwidth').value = widthval;
}

function decrementWidth() {
    var widthval = parseInt(document.getElementById('sidebarwidth').value);
    if(widthval > 100) {
        widthval = widthval - 5;
    }
    document.getElementById('sidebarwidth').value = widthval;
}