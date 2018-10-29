jQuery(function($) {

    var nextgen_simplelightbox_init = function() {
        var selector = nextgen_lightbox_filter_selector($, $(".ngg-simplelightbox"));
        selector.simpleLightbox({
            history: false,
            animationSlide: false,
            animationSpeed: 100
        });
    };

    $(window).bind('refreshed', nextgen_simplelightbox_init);
    nextgen_simplelightbox_init();
});
