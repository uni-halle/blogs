/**
 * Header Fixed
 */
jQuery(window).on("scroll", function (e) {
    if (jQuery('.container-h.container-fluid').width() > 0) {      
        if (jQuery(window).scrollTop() > 0) {
            jQuery('.container-h.container-fluid').addClass('is-sticky');
        } else {
            jQuery('.container-h.container-fluid').removeClass('is-sticky');
        }
    }
});
