
var WidgetWranglerData = WidgetWranglerData || { context: {} };

(function ($) {

    $(document).ready(function(){
        init();
    });

    /**
     * Template hidden on the page by the wrangler.
     */
    var widgetTemplate = wp.template('add-widget');

    /**
     * Starting row index large to avoid collisions. decremented as widgets are added
     */
    var widgetRowIndex = 10000;

    /**
     * jQuery object of sortable lists.
     */
    var $sortableLists = null;

    /**
     * Main wrangler form wrapper
     */
    var $form = $('#widget-wrangler-form');

    /**
     * Initialize the wrangler.
     */
    function init() {
        refresh();

        $form.on('click', '#ww-add-new-widget-button', addWidget );
        $form.on('change', 'ul.ww-sortable select', changeCorralSelect);
        $form.on('change', 'select[name="ww-preset-id-new"]', replaceFormContents );
    }

    /**
     * Refresh all the sortable lists
     */
    function refresh() {
        $sortableLists = $('ul.ww-sortable');

        makeSortable();
        toggleNoWidgets();
        updateWidgetWeights();
        updateWidgetCorrals();
        fixEvents();
    }

    /**
     * Indicate changes have occurred
     */
    function showMessage() {
        $('#ww-edited-message').show();
    }

    /**
     * When some actions are taken, it means we are no longer on preset.
     */
    function unsetPreset() {
        $('select[name=ww-preset-id-new]').val('0');
    }

    /**
     * Initialize jQuery.ui.sortable
     */
    function makeSortable() {
        $sortableLists
            .sortable({
                items: '> li:not(.ww-no-widgets)',
                connectWith: '.ww-sortable',
                cancel: 'select,input',
                start: function (event, ui){
                    ui.item.addClass('ww-dragging');
                },
                stop: function(event, ui){
                    ui.item.removeClass('ww-dragging');
                }
            })
            .on('sortupdate', function(event,ui){
                refresh();
                unsetPreset();
                showMessage();
            });
    }

    /**
     * Fix some form input issues
     * @link http://stackoverflow.com/questions/13898027/jquery-ui-sortable-cant-type-in-input-fields-with-cancel-method
     */
    function fixEvents() {
        var $sortableFields = $sortableLists.find('select, input[type=text]');
        $sortableFields.live('click', function(e) {
            $(this).trigger({
                type: 'mousedown',
                which: 3
            });
        });

        $sortableFields.live('mousedown', function(e) {
            if(e.which == 3){
                $(this).focus();
            }
        });
    }

    /**
     * Move a widget from one corral to another when the select box is changed.
     */
    function changeCorralSelect() {
        var $select = $(this);

        if ( $select.val() === 'disabled') {
            $select.parent('.ww-item').remove();
        }
        else {
            $select.parent('.ww-item').clone().prependTo("#ww-corral-"+$select.val()+"-items").removeClass("disabled");
            $select.parent('.ww-item').remove();
        }

        refresh();
        unsetPreset();
        showMessage();
    }

    /**
     * Ajax call to get preset form content and replace that of the current form.
     */
    function replaceFormContents() {
        // show throbber, it gets replace on success.
        $('.ajax-working').show().css('visibility', 'visible');

        // store original message
        var original_message = $('#ww-post-preset-message').html();

        $.ajax({
            url: ajaxurl,
            type: 'POST',
            data: {
                'action': 'ww_form_ajax',
                'preset_id': $('select[name="ww-preset-id-new"]').val(),
                'context' : WidgetWranglerData.context || {}
            },
            success: function (data){
                // replace panel contents with new widgets
                $('#widget-wrangler-form-content').html(data);

                // restore original messages
                $('#ww-post-preset-message').html(original_message);

                showMessage();
                refresh();
            },
            error: function(s,m) {}
        });
    }

    /**
     * Template a new widget sortable item and add it to the list.
     */
    function addWidget(){
        var $widget = $('select[name=ww-add-new-widget-widget]');
        var $corral = $('select[name=ww-add-new-widget-corral]');

        if ( $widget.val() !== '0' && $corral.val() !== '0' ) {
            var $sortable_corral = $('#ww-corral-'+$corral.val()+'-items');

            // replace all occurrences of tokens in template
            var row = widgetTemplate()
                .replace(/__widget-ID__/g, $widget.val() )
                .replace(/__widget-post_title__/g, $widget.find('option:selected').text() )
                .replace(/__widget-corral_slug__/g, $corral.val() )
                .replace(/__widget-weight__/g, 0)
                .replace(/__ROW-INDEX__/g, $corral.val() + '-' + widgetRowIndex);

            widgetRowIndex--;

            $sortable_corral.prepend(row).trigger('sortupdate');
        }
    }

    /**
     * Show & Hide the "no widget" list item if corral is empty
     */
    function toggleNoWidgets() {
        $.each($sortableLists, function(){
            var $none = $(this).children('.ww-no-widgets');

            if( $(this).children('.ww-item').length > 0) {
                $none.hide();
            }
            else{
                $none.show();
            }
        });
    }

    /**
     * Update all widget weight value
     */
    function updateWidgetWeights() {
        $.each($sortableLists, function(){
            $.each( $(this).find(".ww-item"), function(i) {
                $(this).find(".ww-widget-weight").val(i+1);
            });
        });
    }

    /**
     * Update all widgets corral select boxes to have the value of their current sortable list corralslug.
     */
    function updateWidgetCorrals() {
        $.each($sortableLists, function(){
            var corral_slug = $(this).data('corralslug');

            $.each( $(this).find(".ww-item"), function(i) {
                $(this).find("select").val(corral_slug);
            });
        });
    }

})(jQuery);
