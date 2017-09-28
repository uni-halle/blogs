/**
 * Created by benohead on 09.06.14.
 */


/**
 * Wrapper function to safely use $
 */
function wpctcWrapper($) {
    var wpctc = {
        /**
         * Main entry point
         */
        init: function () {
            wpctc.prefix = 'wpctc_';
            wpctc.templateURL = $('#template-url').val();
            wpctc.ajaxPostURL = $('#ajax-post-url').val();
            wpctc.registerEventHandlers();
            $('.tagcloud-canvas').each(function () {
                $color = $(this).attr('data-tagcloud-color');
                $bordercolor = $(this).attr('data-tagcloud-bordercolor');
                $font = $(this).attr("data-cloud-font");
                $radiusx = $(this).attr("data-cloud-radiusx");
                $radiusy = $(this).attr("data-cloud-radiusy");
                $radiusz = $(this).attr("data-cloud-radiusz");
                if ($font == "null") $font=null;
                $(this).tagcanvas({
                    outlineThickness: 0,
                    outlineColour: $bordercolor,
                    textFont: $font,
                    textColour: $color,
                    radiusx: $radiusx,
                    radiusy: $radiusy,
                    radiusz: $radiusz,
                    maxSpeed: 0.06,
                    minBrightness: 0.1,
                    depth: 0.95,
                    pulsateTo: 0.2,
                    pulsateTime: 0.75,
                    decel: 0.9,
                    reverse: true,
                    fadeIn: 800,
                    zoom: parseInt($(this).attr("data-cloud-zoom")),
                    zoomStep: 0.05,
                    zoomMax: 3.0,
                    zoomMin: 0.3,
                    weight: true
                }, $(this).attr('id') + '_tags');
            });
        },
        /**
         * Registers event handlers
         */
        registerEventHandlers: function () {
        }
    }; // end wpctc
    $(document).ready(wpctc.init);
} // end wpctcWrapper()
wpctcWrapper(jQuery);
