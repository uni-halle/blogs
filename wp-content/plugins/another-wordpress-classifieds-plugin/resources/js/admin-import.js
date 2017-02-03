/*global AWPCP */

AWPCP.run('awpcp/admin-import', [
    'jquery',
    'awpcp/datepicker-field',
    'awpcp/settings',
    'awpcp/jquery-userfield',
    'awpcp/knockout-progress',
],
function( $, DatepickerField, settings ) {
    $(function() {
        $( '#awpcp-import-listings-upload-source-files' ).usableform();

        $( '#awpcp-import-listings-configuration-form' ).each(function() {
            var $form = $( this );

            $form.find( '[name="default_start_date"], [name="default_end_date"]' ).each( function() {
                $.noop( new DatepickerField( $(this), {
                    datepicker: {
                        changeMonth: true,
                        changeYear: true,
                        altFormat: 'mm/dd/yy'
                    }
                } ) );
            } );

            $form.usableform();
        });

        $( '#awpcp-import-listings-import-form' ).each(function() {
            var $form = $( this );

            var ImportSessionOptions = settings.get( 'csv-import-session' );

            var ImportTask = function() {
                this.rowsCount = ko.observable( ImportSessionOptions.numberOfRows );
                this.rowsImported = ko.observable( ImportSessionOptions.numberOfRowsImported );
                this.rowsRejected = ko.observable( ImportSessionOptions.numberOfRowsRejected );

                this.completed = ko.computed( function() {
                    var rowsImported = this.rowsImported();
                    var rowsRejected = this.rowsRejected();

                    if ( this.rowsCount() > 0 ) {
                        return this.rowsCount() <= ( rowsImported + rowsRejected );
                    }

                    return false;
                }, this );

                this.paused = ko.observable( true );

                this.errors = ko.observableArray( [] );

                this.progress = ko.computed( function() {
                    var rowsProcessed = this.rowsImported() + this.rowsRejected();
                    console.log( 'progress', Math.round( 100 * rowsProcessed / this.rowsCount() ) );
                    return Math.round( 100 * rowsProcessed / this.rowsCount() ) + '%';
                }, this );

                this.progressReport = ko.computed( function() {
                    var message = settings.l10n( 'csv-import-session', 'progress-report' );

                    message = message.replace(
                        '<number-of-rows-processed>',
                        '<strong>' + ( this.rowsImported() + this.rowsRejected() ) + '</strong>'
                    );
                    message = message.replace( '<number-of-rows>', '<strong>' + this.rowsCount() + '</strong>' );
                    message = message.replace( '<percentage>', '<strong>' + this.progress() + '</strong>' );
                    message = message.replace( '<number-of-rows-imported>', '<strong>' + this.rowsImported() + '</strong>' );
                    message = message.replace( '<number-of-rows-rejected>', '<strong>' + this.rowsRejected() + '</strong>' );

                    return message;
                }, this );
            }

            $.extend( ImportTask.prototype, {
                start: function( data, event ) {
                    var self = this;

                    if ( event && event.preventDefault ) {
                        event.preventDefault();
                    }

                    self.paused( false );

                    setTimeout( $.proxy( self._runStep, self ), 1 );
                },

                _runStep: function() {
                    if ( this.completed() ) {
                        return;
                    }

                    if ( this.paused() ) {
                        return;
                    }

                    $.getJSON( settings.get( 'ajaxurl' ), { action: 'awpcp-import-listings' } )
                        .done( $.proxy( this._handleAjaxResponse, this ) )
                        .fail( $.proxy( this._handleFailedRequest, this ) );
                },

                _handleAjaxResponse: function( response ) {
                    if ( response.status === 'ok' ) {
                        this._handleSuccessfulResponse( response );
                    } else {
                        this._handleErrorResponse( response );
                    }
                },

                _handleSuccessfulResponse: function( response ) {
                    var self = this;

                    this.rowsCount( response.rowsCount );
                    this.rowsImported( response.rowsImported );
                    this.rowsRejected( response.rowsRejected );

                    this._handleMessages( response.errors );

                    setTimeout( $.proxy( self._runStep, self ), 1 );
                },

                _handleMessages: function( messages ) {
                    var self = this, template = settings.l10n( 'csv-import-session', 'message-description' );

                    $.each( messages, function( index, message ) {
                        var description = template
                            .replace( '<message-type>', message.type.substr( 0, 1 ).toUpperCase() + message.type.substr( 1 ) )
                            .replace( '<message-line>', message.line );

                        self.errors.push( {
                            description: description,
                            content: message.content
                        } );
                    } );
                },

                _handleErrorResponse: function( response ) {
                    this._handleMessages( [ {
                        type: 'error',
                        line: 0,
                        content: response.error
                    } ] );
                },

                _handleFailedRequest: function( jqxhr, _, message ) {
                    this._handleMessages( [ {
                        type: 'error',
                        line: 0,
                        content: message
                    } ] );
                    this.paused( true );
                },

                pause: function( data, event ) {
                    if ( event && event.preventDefault ) {
                        event.preventDefault();
                    }

                    this.paused( true );
                }
            } );

            ko.applyBindings( new ImportTask(), $form.get( 0 ) );
        });
    });
});
