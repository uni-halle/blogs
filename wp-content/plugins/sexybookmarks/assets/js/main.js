(function($) {
    window.Shareaholic = window.Shareaholic || {};
    window.shareaholic_debug = true;

    Shareaholic.bind_button_clicks = function (click_object, off) {
        if (off) {
            $(click_object.selector).off('click.app_settings');
        }

        $(click_object.selector).off('click.app_settings').on('click.app_settings', function (e) {
            button = this;
            e.preventDefault();
            url = click_object.url(this);
            if (click_object.selector == '#general_settings' || click_object.selector == '#affiliate_settings') {
                window.open(url);
                return false;
            } else {
                $frame = $('<iframe>', { src:url }).appendTo('#iframe_container');
                if (click_object.callback) {
                    click_object.callback(this);
                }
                $('#editing_modal').reveal({
                    topPosition:50,
                    close:function () {
                        if (click_object.close) {
                            click_object.close(button);
                        }
                        $frame.remove();
                    }
                });
            }
        });
    }

    Shareaholic.click_objects = {
        'app_settings': {
            selector: '#app_settings button',
            url: function(button) {
                id = $(button).data('location_id');
                app = $(button).data('app')
                url = first_part_of_url + $(button).data('href') + '?embedded=true&'
                    + 'verification_key=' + verification_key;
                url = url.replace(/{{id}}/, id);
                return url;
            },
            callback: function(button) {
                $modal = $('.reveal-modal');
                $modal.addClass('has-shortcode')
                id = $(button).data('location_id');
                app = $(button).data('app');
                text = 'You can also use this shortcode to place this {{app}} App anywhere.';
                html = "<div id='shortcode_container'> \
          <span id='shortcode_description'></span> \
          <textarea id='shortcode' name='widget_div' onclick='select();' readonly='readonly'></textarea> \
        </div>"
                $(html).appendTo($modal);
                $('#shortcode_description').text(text.replace(/{{app}}/, Shareaholic.titlecase(app)));
                $('#shortcode').text('[shareaholic app="' + app + '" id="' + id + '"]');
            },
            close: function(button) {
                $('#shortcode_container').remove();
                $('.reveal-modal').removeClass('has-shortcode');
            }
        },

        'affiliate_settings': {
            selector: '#affiliate_settings',
            url: function(button) {
                return first_part_of_url + 'websites/edit/'
                    + '?verification_key=' + verification_key;
            }
        },
        
        'general_settings': {
            selector: '#general_settings',
            url: function(button) {
                return first_part_of_url + 'websites/edit/'
                    + '?verification_key=' + verification_key;
            }
        },
        'app_wide_settings': {
            selector: '.app_wide_settings',
            url: function(button) {
                url = first_part_of_url + $(button).data('href') + '?embedded=true&'
                    + 'verification_key=' + verification_key;
                return url
            }
        }
    }

    Shareaholic.Utils.PostMessage.receive('settings_saved', {
        success: function(data) {
            $('input[type="submit"]').click();
        },
        failure: function(data) {
            console.log(data);
        }
    });

    Shareaholic.titlecase = function(string) {
        return string.charAt(0).toUpperCase() + string.replace(/_[a-z]/g, function(match) {
            return match.toUpperCase().replace(/_/, ' ');
        }).slice(1);
    }

    Shareaholic.disable_buttons = function() {
        $('#app_settings button').each(function() {
            if (!$(this).data('location_id') && !this.id == 'app_wide_settings') {
                $(this).attr('disabled', 'disabled');
            } else {
                $(this).removeAttr('disabled');
            }
        });
    }

    Shareaholic.create_new_location = function(_this) {
        button = $(_this).siblings('button')
        app = button.data('app')
        location_id = button.data('location_id')
        if (!!location_id) {
            return;
        }

        data = {}
        data['configuration_' + app + '_location'] = {
            name: /.*\[(.*)\]/.exec($(_this).attr('name'))[1]
        }

        $.ajax({
            url: first_part_of_url + app + '/locations.json',
            type: 'POST',
            data: data,
            success: function(data, status, jqxhr) {
                data['action'] = 'shareaholic_add_location';
                data['nonce'] = window.shareaholic_add_location_nonce;
                button.data('location_id', data['location']['id']);
                Shareaholic.disable_buttons();
                Shareaholic.submit_to_admin(data, function(stuff) {
                    console.log(stuff);
                });
            },
            failure: function(things) {
                console.log(things);
            },
            xhrFields: {
                withCredentials: true
            }
        });
    }

    Shareaholic.submit_to_admin = function(data, callback) {
        $.ajax({
            url: ajaxurl,
            type: 'POST',
            data: data,
            success: function(response) {
                try {
                    response = JSON.parse(response);
                } catch (e) {
                    response = {};
                }
                callback(response);
            },
            failure: function(response) {
                try {
                    response = JSON.parse(response);
                } catch (e) {
                    response = {};
                }
                callback(response);
            }
        })
    }

    $(document).ready(function() {

        Shareaholic.disable_buttons();

        Shareaholic.bind_button_clicks(Shareaholic.click_objects['app_settings']);
        Shareaholic.bind_button_clicks(Shareaholic.click_objects['affiliate_settings']);
        Shareaholic.bind_button_clicks(Shareaholic.click_objects['general_settings']);
        Shareaholic.bind_button_clicks(Shareaholic.click_objects['app_wide_settings']);
        if (Shareaholic.click_objects['unverified_general_settings']) {
            Shareaholic.bind_button_clicks(Shareaholic.click_objects['unverified_general_settings'], true);
        }

        $('#terms_of_service_modal').reveal({
            closeonbackgroundclick: false,
            closeonescape: false,
            topPosition: 50
        });

        $('#failed_to_create_api_key').reveal({
            closeonbackgroundclick: false,
            closeonescape: false,
            topPosition: 50
        });

        $('#get_started').on('click', function(e) {
            e.preventDefault();
            data = {action: 'shareaholic_accept_terms_of_service'};
            // $('#terms_of_service_modal').trigger('reveal:close');
            Shareaholic.submit_to_admin(data, function(){
                location.reload();
            });
        })

        $('form input[type=checkbox]').on('click', function() {
            if($(this).is(':checked') && !$(this).data('location_id')) {
                Shareaholic.create_new_location(this);
            }
        });
    });
})(sQuery);
