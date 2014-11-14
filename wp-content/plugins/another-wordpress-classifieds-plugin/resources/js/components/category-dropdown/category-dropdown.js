/*global AWPCP*/
AWPCP.define( 'awpcp/category-dropdown', [ 'jquery' ],
function( $ ) {
    $.AWPCP.CategoriesDropdown = function(hidden, dropdown) {
        var self = this, selected;

        self.hidden = $(hidden);
        self.dropdown = $(dropdown);

        // using multiple dropdowns
        if (self.hidden.length > 0) {
            self.identifier = self.dropdown.attr('target');
            self.category_id = parseInt(self.dropdown.val(), 10);

            self.widget = new $.AWPCP.CategoriesDropdownWidget(self.identifier,
                                                               self.dropdown,
                                                               null,
                                                               self.category_id);

            $.subscribe('/category/updated/' + self.identifier, function(event, category_id) {
                self.hidden.val(category_id);
            });

            selected = self.dropdown.attr('chain');
            if (selected && selected.length > 0) {
                selected = $.map(selected.split(','), function(v) {
                    return parseInt(v, 10);
                });

                self.widget.choose(selected);
            } else {
                setTimeout(function() {
                    self.widget.change(null);
                }, 100);
            }

        // using a single dropdown
        } else {
            self.dropdown.change(function() {
                var category_id = parseInt(self.dropdown.val(), 10);
                $.publish('/category/updated' , [self.dropdown, isNaN(category_id) ? null : category_id]);
            });
        }
    };

    $.AWPCP.CategoriesDropdownWidget = function(identifier, dropdown, parent, category_id) {
        var self = this;

        self.identifier = identifier;

        self.category_id = category_id;

        self.parent = parent;  // parent dropdown
        self.child = null;  // child dropdown

        if (!dropdown && parent) {
            self.default_option = self.parent.attr('next-default-option');
            self.dropdown = $('<select class="awpcp-category-dropdown">').insertAfter(parent).hide();
        } else if (dropdown) {
            self.dropdown = dropdown;
        } else {
            return;
        }

        self.dropdown.change(function() {
            self.change(parseInt($(this).val(), 10));
        });

        $.subscribe('/category/widget/updated/' + self.identifier, function(event, dropdown, parent_category_id) {
            if (self.parent === dropdown) {
                self.render(parent_category_id);
            }
        });

        self.dropdown.val(self.category ? self.category : '');
    };

    $.extend($.AWPCP.CategoriesDropdownWidget.prototype, {
        change: function(category_id) {
            var self = this;

            self.category_id = isNaN(category_id) ? null : category_id;

            if (self.child === null) {
                self.child = new $.AWPCP.CategoriesDropdownWidget(self.identifier, null, self.dropdown, null);
            }

            $.publish('/category/updated' , [self.dropdown, self.category_id]);
            $.publish('/category/updated/' + self.identifier , [self.category_id]);
            $.publish('/category/widget/updated/' + self.identifier, [self.dropdown, self.category_id]);
        },

        render: function(parent_category_id) {
            var self = this, children, categories, length;

            categories = $.AWPCP.get('categories') || window[ 'categories_' + self.identifier ];

            if (null === self.parent && categories.hasOwnProperty('root')) {
                children = categories.root;
            } else if (categories.hasOwnProperty(parent_category_id)) {
                children = categories[parent_category_id];
            } else {
                children = [];
            }

            self.dropdown.empty()
                         .append($('<option value="">' + self.default_option + '</option>'));

            length = children.length;
            for (var i = 0; i < length; i = i + 1) {
                self.dropdown.append($('<option value="' + children[i].id + '">' + children[i].name + '</option>'));
            }

            if (length > 0) {
                self.dropdown.show();
            } else {
                self.hide();
            }
        },

        choose: function(selected) {
            var self = this;

            if (selected.length > 0) {
                self.dropdown.val(selected[0]);
                self.change(selected[0]);
                self.child.choose(selected.slice(1));
            }
        },

        hide: function() {
            this.dropdown.hide();
        }
    });

    $.fn.categorydropdown = function() {
        $( this ).each(function() {
            var select = $( this ),
                hidden = $( '#awpcp-category-dropdown-' + select.attr( 'target' ) );

            $.noop( new $.AWPCP.CategoriesDropdown( hidden, select ) );
        });
    };
} );
