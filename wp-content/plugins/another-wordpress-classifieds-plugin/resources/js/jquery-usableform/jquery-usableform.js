if (typeof jQuery !== 'undefined') {
    (function ($) {
        $.fn.usableform = function() {
            return $(this).each(function() {
                var form = $(this);
                var elements = form.find('[data-usableform]');

                elements.each(function() {
                    var element = $(this);
                    var condition = element.attr('data-usableform').split(':');
                    var target = form.find('[name="' + condition[1] + '"]');

                    if ( target.length > 1 ) {
                        target = target.not(':hidden');
                    }

                    target.change(function() {
                        onTargetChange($(this), element, condition);
                    });

                    // target.each(function() {
                    //     onTargetChange($(this), element, condition);
                    // });

                    onTargetChange(target, element, condition);
                });
            });
        };

        function onTargetChange(target, element, condition) {
            if (condition.length === 3 && elementValueMatchesCondition(target, condition[2])) {
                handleElementFulfilledCondition(element, condition[0]);
            } else if (condition.length === 2 && null !== getElementValue(target)) {
                handleElementFulfilledCondition(element, condition[0]);
            } else {
                handleElementUnfulfilledCondition(element, condition[0]);
            }
        }

        function elementValueMatchesCondition(element, condition) {
            var value = getElementValue(element);

            if ($.isArray(value)) {
                return value.indexOf(condition) !== -1;
            } else {
                return value === condition;
            }
        }

        function getElementValue(element) {
            var type = getElementType(element);
            var value;

            if ('checkbox' === type || 'radio' === type) {
                value = element.filter(':checked').val();
            } else {
                value = element.val();
            }

            return value ? value : null;
        }

        function getElementType(element) {
            return element[element.prop ? 'prop' : 'attr']('type');
        }

        function handleElementFulfilledCondition(element, condition) {
            if ('enable-if' === condition) {
                enableElement(element);
            } else if ('show-if' === condition) {
                getFormFieldContainer(element).show();
            } else if ('hide-if' === condition) {
                getFormFieldContainer(element).hide();
            }
        }

        function getFormFieldContainer(element) {
            var container = element.closest('.awpcp-admin-form-field');
            return container.length ? container : element;
        }

        function enableElement(element) {
            if (element.prop) {
                element.prop('disabled', false);
            } else {
                element.removeAttr('disabled');
            }
        }

        function handleElementUnfulfilledCondition(element, condition) {
            if ('enable-if' === condition) {
                disableElement(element);
            } else if ('show-if' === condition) {
                getFormFieldContainer(element).hide();
            } else if ('hide-if' === condition) {
                getFormFieldContainer(element).show();
            }
        }

        function disableElement(element) {
            if (element.prop) {
                element.prop('disabled', true);
            } else {
                element.attr('disabled', 'disabled');
            }
        }
    })(jQuery);
}
