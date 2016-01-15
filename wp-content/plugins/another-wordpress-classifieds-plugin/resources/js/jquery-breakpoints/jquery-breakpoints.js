if ( typeof jQuery !== 'undefined' ) {
    (function ($) {
        var ResizeEvent = (function() {

            var callbacks = [],
                running = false;

            function resize() {
                if (!running) {
                    running = true;

                    if (window.requestAnimationFrame) {
                        window.requestAnimationFrame(runCallbacks);
                    } else {
                        setTimeout(runCallbacks, 66);
                    }
                }
            }

            function runCallbacks() {
                var length = callbacks.length;

                for ( var i = 0; i < length; i = i + 1 ) {
                    callbacks[i]();
                }

                running = false;
            }

            function addCallback(callback) {
                if (callback) {
                    callbacks.push(callback);
                }
            }

            return {
                add: function(callback) {
                    if (callbacks.length === 0) {
                        window.addEventListener('resize', resize);
                    }
                    addCallback(callback);
                }
            };
        }());

        var LocalBreakpoints = (function(){
            var targets = [], module;

            module = {
                watch: watch
            };

            function watch(element, options) {
                if (0 === targets.length) {
                    ResizeEvent.add(function() { onResize(); });
                }

                var target = addTarget(element, options);
                updateBreakpointsForTarget(target);
            }

            function addTarget(element, options) {
                targets.push({
                    element: element,
                    options: $.extend({}, options)
                });

                return targets[targets.length - 1];
            }

            function onResize() {
                var length = targets.length;
                for ( var i = 0; i < length; i = i + 1 ) {
                    updateBreakpointsForTarget(targets[i]);
                }
            }

            function updateBreakpointsForTarget(target) {
                var width = target.element.width(),
                    breakpoints = target.options.breakpoints,
                    breakpoint = null;

                for ( var i = breakpoints.length - 1; i >= 0; i = i - 1 ) {
                    if ( breakpoints[i] <= width ) {
                        breakpoint = breakpoints[i];
                        break;
                    }
                }

                if ( breakpoint ) {
                    updateTargetCSS(target, breakpoints[i]);
                } else {
                    removeBreakpointClass(target);
                }
            }

            function updateTargetCSS(target, breakpoint) {
                var breakpointClass = target.options.classPrefix + '-' + breakpoint;

                removeBreakpointClass(target);
                target.element.addClass(breakpointClass);
                target.element.data('breakpoint-class', breakpointClass);
            }

            // function getTargetBreakpointClass(target) {
            //     var cssClass = target.element.data('breakpoint-class-prefix');

            //     if ( typeof cssClass === 'undefined' ) {
            //         cssClass = target.element.attr('breakpoint-class-prefix');
            //     }
            // }

            function removeBreakpointClass(target) {
                target.element.removeClass(target.element.data('breakpoint-class'));
            }

            return module;
        }());

        $.fn.breakpoints = function(options) {
            return $(this).each(function() {
                LocalBreakpoints.watch($(this), options);
            });
        };

        $(function() {
            $('[data-breakpoints]').each(function(){
                var element = $(this), breakpoints, classPrefix;

                breakpoints = element.attr('data-breakpoints')
                    .split(',')
                    .filter(function(element) { return element.length > 0 ; })
                    .map(function(element) { return parseInt(element, 10); });
                classPrefix = element.attr('data-breakpoint-class-prefix');

                element.breakpoints({
                    classPrefix: classPrefix,
                    breakpoints: breakpoints
                });
            });
        });
    })(jQuery);
}
