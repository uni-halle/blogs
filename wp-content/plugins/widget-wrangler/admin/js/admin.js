(function($){
    $(document).ready(function(){
        /**
         * Simple open and close boxes
         */
        $('.ww-box-toggle-content').hide();

        // open and close widget menu
        $('.ww-box-toggle h3').click(function(){
            $(this).parent().find('.ww-box-toggle-content').slideToggle('fast');
        });

        /**
         * Simple Next content toggle
         */
        $('body').on('click', '.toggle-next-content', function(){
            $(this).next('.togglable-content').slideToggle('fast');
        });

        /**
         * Js confirm with data Attribute
         */
        $('input[data-confirm]').click(function(event) {
           return confirm( $(this).data('confirm') );
        });

        /**
         * Sortable corrals
         */
        var $corrals = $('#ww-corrals-sort-list');
        if ( $corrals.length ) {
            $corrals.sortable({
                update: function(event,ui){
                    var all_corrals = $corrals.children('.ww-sortable-corral');

                    $.each(all_corrals, function(i){
                        var weight_input = $(this).children('input[type=hidden]');
                        var count = i+1;
                        $(weight_input).attr("name", "weight["+count+"]");
                    });
                }
            }).disableSelection();
        }
    });
})(jQuery);
