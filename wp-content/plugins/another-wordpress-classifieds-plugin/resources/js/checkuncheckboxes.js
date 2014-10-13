/*exported CheckAllAds, UncheckAll, CheckAll*/
function CheckAll() {
    var count = document.mycats.elements.length;
    for (var i=0; i < count; i=i+1) {
        if (document.mycats.elements[i].checked === true) {
            document.mycats.elements[i].checked = false;
        } else {
            document.mycats.elements[i].checked = true;
        }
    }
}

function UncheckAll(){
    var count = document.mycats.elements.length;
    for (var i=0; i < count; i=i+1) {
        if(document.mycats.elements[i].checked === true) {
            document.mycats.elements[i].checked = false;
        } else {
            document.mycats.elements[i].checked = true;
        }
    }
}

function CheckAllAds() {
    var count = document.manageads.elements.length;
    for (var i=0; i < count; i=i+1) {
        if(document.manageads.elements[i].checked === true) {
            document.manageads.elements[i].checked = false;
        } else {
            document.manageads.elements[i].checked = true;
        }
    }
}

function UncheckAll(){
    var count = document.manageads.elements.length;
    for (var i=0; i < count; i=i+1) {
        if(document.manageads.elements[i].checked === true) {
            document.manageads.elements[i].checked = false;
        } else {
            document.manageads.elements[i].checked = true;
        }
    }
}

if (typeof jQuery !== 'undefined') {
    (function($, undefined) {
        $.fn.toggleCheckboxes = function() {
            var element, index, table, checkboxes;

            element = $(this);
            table = element.closest('table');
            index = element.closest('th,td').prevAll().length + 1;

            checkboxes = table.find('tbody tr > :nth-child(' + index + ') :checkbox');

            if (element.attr('checked') !== 'checked') {
                checkboxes.attr('checked', 'checked');
            } else {
                checkboxes.removeAttr('checked');
            }
        };
    })(jQuery);
}
