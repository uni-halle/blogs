/* global ko */
if (typeof jQuery !== 'undefined') {

    var AWPCP = jQuery.AWPCP = jQuery.extend({}, jQuery.AWPCP, AWPCP);

    (function($) {
        /**
         * View model for a Multiple Region Selector.
         */
        $.AWPCP.RegionSelector = function(options, regions) {
            var self = this;

            self.options = $.extend({}, self.defaultOptions, options);
            self.regions = ko.observableArray([]);

            self.count = 0;

            /**
             * Controls whether the Add Region button should
             * be shown ot the user or not.
             */
            self.showAddRegionButton = ko.computed(function() {
                var regions = self.regions();

                if (self.options.disabled) {
                    return false;
                } else {
                    return regions.length < self.options.maxRegions;
                }
            }, this);

            /**
             * Controls whether the Remove Region button should
             * be shown ot the user or not.
             */
            self.showRemoveRegionButton = ko.computed(function() {
                var regions = self.regions();

                if (self.options.disabled) {
                    return false;
                } else {
                    return regions.length > 1;
                }
            }, this);

            /**
             * Populate previously selected regions.
             */
            $.each(regions, function(i, region) {
                self.addRegion( region, self.options.fields );
            });
        };

        $.extend($.AWPCP.RegionSelector.prototype, {
            defaultOptions: {
                context: 'details',
                maxRegions: 1,
                showTextField: false,
                disabled: false
            },

            addRegion: function(data, fields) {
                var region;

                if (this.regions().length < this.options.maxRegions) {
                    region = new $.AWPCP.Region(this, this.count, data, fields);
                    this.regions.push(region);
                    this.count = this.count + 1;
                }

                return region;
            },

            removeRegion: function(region) {
                this.regions.remove(region);
            },

            /**
             * Show error messages on duplicated regions.
             * Returns true if the value of the given field is the most
             * specific region part of a duplicated region:
             *
             * If USA->Colador->Denver is a duplicated region,
             * this function should return true for the field that
             * holds the "Denver" part.
             */
            checkDuplicatedRegionsForField: function(fieldId, showErrors) {
                var self = this,
                    objects = [], regions = [], subregions = {},

                    targetValue = false, targetRegionIndex = false,
                    fieldValueIsDuplicated = false;

                $.each(self.regions(), function(index, region) {
                    var partials = region.partials(),
                        selected = [],
                        subregion;

                    subregions[region.index] = [];

                    for (var i = 0; i < partials.length; i = i + 1) {
                        if (partials[i].selected()) {
                            selected.push(partials[i].selected());

                            subregion = selected.join('-');
                            subregions[region.index].push(subregion);

                            if (fieldId && partials[i].id === fieldId) {
                                targetValue = subregion;
                                targetRegionIndex = region.index;
                            }
                        }
                    }

                    objects.push(region);
                });

                // check the more specific regions first (the ones with more
                // subregions/partials selected). If there is tie, check the
                // ones that appear first in the page (have lower index).
                objects.sort(function(a, b) {
                    var aRegions = subregions[a.index].length,
                        bRegions = subregions[b.index].length;
                    if (bRegions - aRegions !== 0) {
                        return bRegions - aRegions;
                    } else {
                        return a.index - b.index;
                    }
                });

                $.each(objects, function(index, region) {
                    var values = subregions[region.index], check = true;

                    for (var i = values.length - 1; i >= 0; i = i - 1) {
                        if (check && $.inArray(values[i], regions) !== -1) {
                            if (showErrors) {
                                region.error(AWPCP.l10n('multiple-region-selector', 'duplicated-region'));
                            }

                            if (values[i] === targetValue && region.index === targetRegionIndex) {
                                fieldValueIsDuplicated = true;
                            }

                            break;
                        } else {
                            regions.push(values[i]);
                            region.error(false);
                            check = false;
                        }
                    }
                });

                return fieldValueIsDuplicated;
            },

            checkDuplicatedRegions: function() {
                return this.checkDuplicatedRegionsForField(null, false);
            },

            /**
             * Handler for the Add Region button.
             */
            onAddRegion: function(selector, event) {
                event.preventDefault();

                var region = this.addRegion(this.options.template,
                                            this.options.fields);

                // clean selected values
                region.reset();
            },

            /**
             * Handler for the Remove Region button.
             */
            onRemoveRegion: function() {
                var self = this;
                return function(region, event) {
                    event.preventDefault();
                    self.removeRegion(region);
                };
            },

            getLocalizedText: function(key) {
                return AWPCP.l10n('multiple-region-selector', key);
            }
        });


        /**
         * View model for a Single Region Selector, which allow
         * an user to filter the Regions hierarchy and choose one
         * region.
         */
        $.AWPCP.Region = function(selector, index, region, fields) {
            var self = this, partial, data, lastPartial;

            self.index = index;
            self.selector = selector;
            self.partials = ko.observableArray([]);
            self.error = ko.observable(false);

            $.each(fields, function(i, field) {
                if (region.hasOwnProperty(field)) {
                    data = region[field];

                    partial = new $.AWPCP.RegionPartial(
                        self,
                        data.type,
                        data.label,
                        'regions[' + index + '][' + data.param + ']',
                        data.options,
                        data.selected,
                        {
                            alwaysShown: data.alwaysShown || i === 0,
                            showTextField: selector.options.showTextField
                        }
                    );

                    self.partials.push(partial);

                    if (lastPartial && lastPartial.selected()) {
                        partial.show();
                    }

                    lastPartial = partial;
                }
            });
        };

        jQuery.extend($.AWPCP.Region.prototype, {
            /**
             * Returns ordered array of Partial objects associated to form fields
             * that represent region types that are below in the types hierarchy than
             * the provided type.
             */
            getNextPartials: function(type) {
                var partials = this.partials(),
                    length = partials.length,
                    i;

                for (i = 0; i < length; i=i+1) {
                    if (partials[i].type === type) {
                        return partials.slice(i + 1);
                    }
                }

                return [];
            },

            /**
             * Fetch regions data from the server to populate one of the partial
             * objects.
             */
            getPartialOptions: function(partial, type, parentType, parentValue) {
                var self = this;

                partial.loading(true);

                $.getJSON($.AWPCP.get('ajaxurl'), {
                    action: 'awpcp-get-regions-options',
                    parent_type: parentType,
                    parent: parentValue,
                    type: type,
                    context: self.selector.options.context
                }, function(response) {
                    if (response.status === 'ok') {
                        partial.options(response.options);
                    } else {
                        partial.options([]);
                    }

                    partial.loading(false);
                });
            },

            /**
             * Clear selected region.
             */
            reset: function() {
                $.each(this.partials(), function(i, partial) {
                    partial.selected('');
                });
            },

            selectionChanged: function(type, value) {
                var next = this.getNextPartials(type),
                    first, hidden, i, n;

                this.selector.checkDuplicatedRegions();

                // return if there are no more fileds to process
                if (0 === next.length) {
                    return;
                }

                // fetch new options only if the new value is one of
                // the options
                if (null !== value && undefined !== value && value.length) {
                    first = next[0];
                    first.selected(undefined);

                    this.getPartialOptions(first, first.type, type, value);

                    first.show(true);

                    // hide all other fields until a new value is selected
                    hidden = next.slice(1);

                // no valid value was selected, hide all fields
                } else {
                    hidden = next;
                }

                n = hidden.length;
                for (i = 0; i < n; i=i+1) {
                    hidden[i].selected(undefined);
                    hidden[i].hide();
                }
            }
        });


        /**
         * View model for a Partial Region (Country, State, County or City).
         */
        $.AWPCP.RegionPartial = function(region, type, label, param, options, selected, config) {
            var self = this;

            self.region = region;
            self.config = jQuery.extend({}, self.defaultConfig, config);

            // HTML id attribute
            self.id = type + Math.random();

            self.type = type;
            self.label = ko.observable(label);
            self.param = ko.observable(param);
            self.options = ko.observableArray(options);
            self.selectedOption = ko.observable(selected);
            self.selectedText = ko.observable(selected);

            self.loading = ko.observable(false);
            self._show = ko.observable(undefined);

            self.caption = ko.computed(function() {
                var caption = AWPCP.l10n('multiple-region-selector', 'select-placeholder').replace('%s', self.label());

                if (caption.lastIndexOf('*') === (caption.length - 1)) {
                    caption = caption.substr(0, caption.length - 1);
                }

                return caption;
            }, self);

            self.showTextField = ko.computed(function() {
                var options = self.options(),
                    loading = self.loading(),
                    show = self._show() || self.config.alwaysShown;

                if (show === true && !loading) {
                    return options.length === 0 && self.config.showTextField;
                } else {
                    return false;
                }
            }, self);

            self.showSelectField = ko.computed(function() {
                var options = self.options(),
                    loading = self.loading(),
                    show = self._show() || self.config.alwaysShown;

                if (show === true && !loading) {
                    return options.length > 0 || !self.config.showTextField;
                } else {
                    return false;
                }
            }, self);

            self.selected = ko.computed({
                read: function() {
                    var showTextField = self.showTextField(),
                        text = self.selectedText(),
                        option = self.selectedOption();
                    return showTextField ? text : option;
                },
                write: function(value) {
                    if (self.showTextField()) {
                        self.selectedText(value);
                    } else {
                        self.selectedOption(value);
                    }
                },
                owner: self
            });

            self.visible = ko.computed(function() {
                var selected = self.selected(),
                    show = self._show() ? true : false,
                    showTextField = self.showTextField();

                if (showTextField) {
                    return true;
                } else {
                    return self.config.alwaysShown || (undefined !== selected) || show;
                }
            });

            // save previous value, so we can decide to notify parent region
            // only when there is a NEW value available
            self.previousValue = null;
            self.selected.subscribe(function(previousValue) {
                self.previousValue = previousValue;
            }, null, 'beforeChange');

            // notify other fields when a new value is selected/provided
            self.selected.subscribe(function(value) {
                if (self.previousValue !== value && (self.showSelectField() || self.showTextField())) {
                    self.region.selectionChanged(self.type, value);
                }
            });
        };

        jQuery.extend($.AWPCP.RegionPartial.prototype, {
            defaultConfig: {
                showTextField: false,
                alwaysShown: false
            },

            show: function() {
                this._show(true);
            },

            hide: function() {
                this._show(false);
            }
        });


        /**--------------------------------------------------------------------
         * Validation
         */

        $.validator.addMethod('multiple-region', (function() {
            return function(value, element) {
                var field = $(element),
                    selector = field.closest('.awpcp-multiple-region-selector').data('RegionSelector');

                if (selector && selector.checkDuplicatedRegionsForField(field.attr('id'), true)) {
                    return false;
                }

                return true;
            };

        })()/*, the error message is shown with Knockoout JS */);

        $.validator.addClassRules('multiple-region', {
            'multiple-region': true
        });


        /**--------------------------------------------------------------------
         * Initialization
         */

        $(function() {
            $('.awpcp-multiple-region-selector').each(function() {
                var selector = $(this), data;

                data = $.AWPCP.get('multiple-region-selector-' + selector.attr('uuid'));
                selector.data('RegionSelector', new $.AWPCP.RegionSelector(data.options, data.regions));

                ko.applyBindings(selector.data('RegionSelector'), selector.get(0));
            });
        });

    })(jQuery);
}
