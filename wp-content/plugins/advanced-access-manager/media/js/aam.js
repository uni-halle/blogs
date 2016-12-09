/**
 * ======================================================================
 * LICENSE: This file is subject to the terms and conditions defined in *
 * file 'license.txt', which is part of this source code package.       *
 * ======================================================================
 */

/**
 * 
 * @param {type} $
 * @returns {undefined}
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
                aamLocal.subject.name,
                aamLocal.subject.level
        );
        
        //load the UI javascript support
        $.getScript(aamLocal.url.jsbase + '/aam-ui.js');

        //initialize help context
        $('.aam-help-menu').each(function() {
            var target = $(this).data('target');
            
            if (target) {
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
            }
        });
        
        //help tooltips
        $('body').delegate('[data-toggle="tooltip"]', 'hover', function (event) {
            event.preventDefault();
            $(this).tooltip({
                'placement' : 'top',
                'container' : 'body'
            });
            $(this).tooltip('show');
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
    AAM.prototype.setSubject = function (type, id, name, level) {
        this.subject = {
            type: type,
            id: id,
            name: name,
            level: level
        };
        
        //update the header
        var subject = type.charAt(0).toUpperCase() + type.slice(1);
        $('.aam-current-subject').html(
                aam.__(subject) + ': <strong>' + name + '</strong>'
        );

        //highlight screen if the same level
        if (parseInt(level) >= aamLocal.level) {
            $('.aam-current-subject').addClass('danger');
        } else {
            $('.aam-current-subject').removeClass('danger');
        }

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
        var notification = $('<div/>', {'class': 'aam-sticky-note ' + status});
        
        notification.append($('<span/>').text(message));
        $('.wrap').append(notification);
        
        setTimeout(function () {
            $('.aam-sticky-note').remove();
        }, 9000);
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
     * 
     * @param {type} el
     * @returns {undefined}
     */
    AAM.prototype.readMore = function(el) {
        $(el).append($('<a/>').attr({
            'href'  : '#',
            'class' : 'aam-readmore' 
        }).text('Read More').bind('click', function(event){
            event.preventDefault();
            $(this).hide();
            $(el).append('<span>' + $(el).data('readmore') + '</span>');
        }));
    };

    /**
     * Initialize UI
     */
    $(document).ready(function () {
        aam = new AAM();
        aam.initialize();
    });

})(jQuery);