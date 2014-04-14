function CheckAll() {
    var count = document.mycats.elements.length;
    for (var i=0; i < count; i=i+1) {
        if (document.mycats.elements[i].checked == 1) {
            document.mycats.elements[i].checked = 0;
        } else {
            document.mycats.elements[i].checked = 1;
        }
    }
}

function UncheckAll(){
    var count = document.mycats.elements.length;
    for (var i=0; i < count; i=i+1) {
        if(document.mycats.elements[i].checked == 1) {
            document.mycats.elements[i].checked = 0;
        } else {
            document.mycats.elements[i].checked = 1;
        }
    }
}

function CheckAllAds() {
    var count = document.manageads.elements.length;
    for (var i=0; i < count; i=i+1) {
        if(document.manageads.elements[i].checked == 1) {
            document.manageads.elements[i].checked = 0;
        } else {
            document.manageads.elements[i].checked = 1;
        }
    }
}

function UncheckAll(){
    var count = document.manageads.elements.length;
    for (var i=0; i < count; i=i+1) {
        if(document.manageads.elements[i].checked == 1) {
            document.manageads.elements[i].checked = 0;
        } else {
            document.manageads.elements[i].checked = 1;
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