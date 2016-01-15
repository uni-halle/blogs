/**
 * ======================================================================
 * LICENSE: This file is subject to the terms and conditions defined in *
 * file 'license.txt', which is part of this source code package.       *
 * ======================================================================
 */

(function ($) {

    /**
     * Main AAM class
     * 
     * @returns void
     */
    function AAM() {

        /**
         * Current Subject
         */
        this.subject = {};

        /**
         * Different UI hooks
         */
        this.hooks = {};
    }

    /**
     * Add UI hook
     * 
     * @param {String}   name
     * @param {Function} callback
     * 
     * @returns {void}
     */
    AAM.prototype.addHook = function (name, callback) {
        if (typeof this.hooks[name] === 'undefined') {
            this.hooks[name] = new Array();
        }

        this.hooks[name].push(callback);
    };

    /**
     * Trigger UI hook
     * 
     * @param {String} name
     * @param {Object} params
     * 
     * @returns {void}
     */
    AAM.prototype.triggerHook = function (name, params) {
        if (typeof this.hooks[name] !== 'undefined') {
            for (var i in this.hooks[name]) {
                this.hooks[name][i].call(this, params);
            }
        }
    };

    /**
     * Initialize the AAM
     * 
     * @returns {undefined}
     */
    AAM.prototype.initialize = function () {
        //read default subject and set it for AAM object
        this.setSubject(
                aamLocal.subject.type, 
                aamLocal.subject.id,
                aamLocal.subject.name
        );
        
        //load the UI javascript support
        $.getScript(aamLocal.url.jsbase + '/aam-ui.js');

        //initialize help context
        $('.aam-help-menu').each(function() {
            var target = $(this).data('target');
            
            $(this).bind('click', function() {
                if ($(this).hasClass('active')) {
                    $('.aam-help-context', target).removeClass('active');
                    $('.aam-postbox-inside', target).show();
                    $(this).removeClass('active');
                } else {
                    $('.aam-postbox-inside', target).hide();
                    $('.aam-help-context', target).addClass('active');
                    $(this).addClass('active');
                }
            });
        });
        
        //welcome message
        if (parseInt(aamLocal.welcome) === 1) {
            $('.aam-welcome-message').toggleClass('active');
            $('.wrap').css('visibility', 'hidden');
            $('#confirm-welcome').bind('click', function (event) {
                event.preventDefault();
                
                $('i', $(this)).attr('class', 'icon-spin4 animate-spin');
                
                $.ajax(aamLocal.ajaxurl, {
                    type: 'POST',
                    dataType: 'json',
                    data: {
                        action: 'aam',
                        sub_action: 'confirmWelcome',
                        _ajax_nonce: aamLocal.nonce
                    },
                    beforeSend: function () {
                        $('.aam-welcome-message').toggleClass('active');
                        setTimeout(function() {
                            $('.aam-welcome-message').remove();
                        }, 1500);
                    },
                    complete: function () {
                        $('.wrap').css('visibility', 'visible');
                    }
                });
            });
        } else {
            $('.aam-welcome-message').remove();
        }
        
        //help tooltips
        $('body').delegate('[data-toggle="tooltip"]', 'hover', function (event) {
            event.preventDefault();
            $(this).tooltip({
                'placement' : 'top',
                'container' : 'body'
            });
            $(this).tooltip('show');
        });
        
        //if there is an error detected during the AAM load, show it
        if (typeof AAM_PageError !== 'undefined' && AAM_PageError) {
            $('.aam-error-list').append(
                $('<li/>').html(
                    this.__('Javascript error detected during the page load. AAM may not function properly.')
                )
            );
            $('.aam-notification-container').removeClass('hidden');
        }
        
        //Error Fix promotion code
        $.ajax(aamLocal.ajaxurl, {
            type: 'POST',
            dataType: 'json',
            data: {
                action: 'aam',
                sub_action: 'getErrorFixStatus',
                _ajax_nonce: aamLocal.nonce
            },
            success: function (response) {
                if (response.status === 'show') {
                    $('#errorfix-promotion').removeClass('hidden');
                    $('#errorfix-install-btn').attr(
                            'href', response.url.replace(/&amp;/g, '&')
                    );
                } else {
                    $('#errorfix-promotion').remove();
                }
            }
        });
    };

    /**
     * 
     * @param {type} label
     * @returns {unresolved}
     */
    AAM.prototype.__ = function (label) {
        return (aamLocal.translation[label] ? aamLocal.translation[label] : label);
    };

    /**
     * 
     * @param {type} type
     * @param {type} id
     * @returns {undefined}
     */
    AAM.prototype.setSubject = function (type, id, name) {
        this.subject = {
            type: type,
            id: id,
            name: name
        };
        
        //update the header
        $('.aam-current-subject').html(
                aam.__('Current ' + type) + ': <strong>' + name + '</strong>'
        );

        this.triggerHook('setSubject');
    };

    /**
     * 
     * @returns {aam_L1.AAM.subject}
     */
    AAM.prototype.getSubject = function () {
        return this.subject;
    };

    /**
     * 
     * @param {type} status
     * @param {type} message
     * @returns {undefined}
     */
    AAM.prototype.notification = function (status, message) {
        var notification = $('<div/>', {'class': 'aam-sticky-note'});
        notification.append(
                $('<span/>', {'class': 'text-' + status}).text(message)
                );
        $('.wrap').append(notification);
        setTimeout(function () {
            $('.aam-sticky-note').remove();
        }, 5000);
    };
    
    /**
     * 
     * @param {type} param
     * @param {type} value
     * @param {type} object
     * @param {type} object_id
     * @returns {undefined}
     */
    AAM.prototype.save = function(param, value, object, object_id) {
        var result = null;
        
        $.ajax(aamLocal.ajaxurl, {
            type: 'POST',
            dataType: 'json',
            async: false,
            data: {
                action: 'aam',
                sub_action: 'save',
                _ajax_nonce: aamLocal.nonce,
                subject: this.getSubject().type,
                subjectId: this.getSubject().id,
                param: param,
                value: value,
                object: object,
                objectId: object_id
            },
            success: function (response) {
                result = response;
            },
            error: function () {
                aam.notification('danger', aam.__('Application error'));
            }
        });
        
        return result;
    };

    /**
     * Initialize UI
     */
    $(document).ready(function () {
        aam = new AAM();
        aam.initialize();
    });

})(jQuery);