(function(e) {
    e.fn.meanmenu = function(t) {
        var n = {
            meanMenuTarget: jQuery(this),
            meanMenuClose: "X",
            meanMenuCloseSize: "18px",
            meanMenuOpen: "<span /><span /><span />",
            meanRevealPosition: "right",
            meanRevealPositionDistance: "0",
            meanRevealColour: "",
            meanRevealHoverColour: "",
            meanScreenWidth: "480",
            meanNavPush: "",
            meanShowChildren: true,
            meanExpandableChildren: true,
            meanExpand: "+",
            meanContract: "-",
            meanRemoveAttrs: false
        };
        var t = e.extend(n, t);
        currentWidth = window.innerWidth || document.documentElement.clientWidth;
        return this.each(function() {
            function g() {
                if (o == "center") {
                    var e = window.innerWidth || document.documentElement.clientWidth;
                    var t = e / 2 - 22 + "px";
                    meanRevealPos = "left:" + t + ";right:auto;";
                    if (!m) {
                        jQuery(".meanmenu-reveal").css("left", t)
                    } else {
                        jQuery(".meanmenu-reveal").animate({
                            left: t
                        })
                    }
                }
            }

            function y() {
                if (jQuery($navreveal).is(".meanmenu-reveal.meanclose")) {
                    $navreveal.html(r)
                } else {
                    $navreveal.html(s)
                }
            }

            function b() {
                jQuery(".mean-bar,.mean-push").remove();
                jQuery("body").removeClass("mean-container");
                jQuery(e).show();
                menuOn = false;
                meanMenuExist = false
            }

            function w() {
                if (currentWidth <= l) {
                    meanMenuExist = true;
                    jQuery("body").addClass("mean-container");
                    jQuery(".mean-container").prepend('<div class="mean-bar"><a href="#nav" class="meanmenu-reveal" style="' + meanStyles + '">Show Navigation</a><nav class="mean-nav"></nav></div>');
                    var t = jQuery(e).html();
                    jQuery(".mean-nav").html(t);
                    if (v) {
                        jQuery("nav.mean-nav *").each(function() {
                            jQuery(this).removeAttr("class");
                            jQuery(this).removeAttr("id")
                        })
                    }
                    jQuery(e).before('<div class="mean-push" />');
                    jQuery(".mean-push").css("margin-top", c);
                    jQuery(e).hide();
                    jQuery(".meanmenu-reveal").show();
                    jQuery(h).html(s);
                    $navreveal = jQuery(h);
                    jQuery(".mean-nav ul").hide();
                    if (meanShowChildren) {
                        if (meanExpandableChildren) {
                            jQuery(".mean-nav ul ul").each(function() {
                                if (jQuery(this).children().length) {
                                    jQuery(this, "li:first").parent().append('<a class="mean-expand" href="#" style="font-size: ' + i + '">' + p + "</a>")
                                }
                            });
                            jQuery(".mean-expand").on("click", function(e) {
                                e.preventDefault();
                                if (jQuery(this).hasClass("mean-clicked")) {
                                    jQuery(this).text(p);
                                    console.log("Been clicked");
                                    jQuery(this).prev("ul").slideUp(300, function() {})
                                } else {
                                    jQuery(this).text(d);
                                    jQuery(this).prev("ul").slideDown(300, function() {})
                                }
                                jQuery(this).toggleClass("mean-clicked")
                            })
                        } else {
                            jQuery(".mean-nav ul ul").show()
                        }
                    } else {
                        jQuery(".mean-nav ul ul").hide()
                    }
                    jQuery(".mean-nav ul li").last().addClass("mean-last");
                    $navreveal.removeClass("meanclose");
                    jQuery($navreveal).click(function(e) {
                        e.preventDefault();
                        if (menuOn == false) {
                            $navreveal.css("text-align", "center");
                            $navreveal.css("text-indent", "0");
                            $navreveal.css("font-size", i);
                            jQuery(".mean-nav ul:first").slideDown();
                            menuOn = true
                        } else {
                            jQuery(".mean-nav ul:first").slideUp();
                            menuOn = false
                        }
                        $navreveal.toggleClass("meanclose");
                        y()
                    })
                } else {
                    b()
                }
            }
            var e = t.meanMenuTarget;
            var n = t.meanReveal;
            var r = t.meanMenuClose;
            var i = t.meanMenuCloseSize;
            var s = t.meanMenuOpen;
            var o = t.meanRevealPosition;
            var u = t.meanRevealPositionDistance;
            var a = t.meanRevealColour;
            var f = t.meanRevealHoverColour;
            var l = t.meanScreenWidth;
            var c = t.meanNavPush;
            var h = ".meanmenu-reveal";
            meanShowChildren = t.meanShowChildren;
            meanExpandableChildren = t.meanExpandableChildren;
            var p = t.meanExpand;
            var d = t.meanContract;
            var v = t.meanRemoveAttrs;
            if (navigator.userAgent.match(/iPhone/i) || navigator.userAgent.match(/iPod/i) || navigator.userAgent.match(/iPad/i) || navigator.userAgent.match(/Android/i) || navigator.userAgent.match(/Blackberry/i) || navigator.userAgent.match(/Windows Phone/i)) {
                var m = true
            }
            if (navigator.userAgent.match(/MSIE 8/i) || navigator.userAgent.match(/MSIE 7/i)) {
                jQuery("html").css("overflow-y", "scroll")
            }
            menuOn = false;
            meanMenuExist = false;
            if (o == "right") {
                meanRevealPos = "right:" + u + ";left:auto;"
            }
            if (o == "left") {
                meanRevealPos = "left:" + u + ";right:auto;"
            }
            g();
            meanStyles = "background:" + a + ";color:" + a + ";" + meanRevealPos;
            if (!m) {
                jQuery(window).resize(function() {
                    currentWidth = window.innerWidth || document.documentElement.clientWidth;
                    if (currentWidth > l) {
                        b()
                    } else {
                        b()
                    }
                    if (currentWidth <= l) {
                        w();
                        g()
                    } else {
                        b()
                    }
                })
            }
            window.onorientationchange = function() {
                g();
                currentWidth = window.innerWidth || document.documentElement.clientWidth;
                if (currentWidth >= l) {
                    b()
                }
                if (currentWidth <= l) {
                    if (meanMenuExist == false) {
                        w()
                    }
                }
            };
            w()
        })
    }
})(jQuery)

