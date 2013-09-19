jQuery(function () {
		jQuery("#nav > li > a").addClass('trigger'); 
		jQuery("#nav li ul").addClass('children'); 
		jQuery("#nav li a").removeAttr('title');
		jQuery("#toolbar > li > a").addClass('trigger'); 
		jQuery("#toolbar li ul").addClass('children'); 
	});

    jQuery(function () {
        jQuery('#nav li').each(function () {
            var distance = 10;
            var time = 150;
            var hideDelay = 150;

            var hideDelayTimer = null;

            var beingShown = false;
            var shown = false;
            var trigger = jQuery('.trigger', this);
            var info = jQuery('.children', this).css('opacity', 0);


            jQuery([trigger.get(0), info.get(0)]).mouseover(function () {
                if (hideDelayTimer) clearTimeout(hideDelayTimer);
                if (beingShown || shown) {
                    // don't trigger the animation again
                    return;
                } else {
                    // reset position of info box
                    beingShown = true;

                    info.css({
                        top: 39,
                        left: 20,
                        display: 'block'
                    }).animate({
                        top: '-=' + distance + 'px',
                        opacity: 1
                    }, time, 'swing', function() {
                        beingShown = false;
                        shown = true;
                    });
                }

                return false;
            }).mouseout(function () {
                if (hideDelayTimer) clearTimeout(hideDelayTimer);
                hideDelayTimer = setTimeout(function () {
                    hideDelayTimer = null;
                    info.animate({
                        top: '-=' + distance + 'px',
                        opacity: 0
                    }, time, 'swing', function () {
                        shown = false;
                        info.css('display', 'none');
                    });

                }, hideDelay);

                return false;
            });
        });
    });
	
	
	
	
	
	
	
	
	 jQuery(function () {
        jQuery('#toolbar li').each(function () {
            var distance = 10;
            var time = 150;
            var hideDelay = 150;

            var hideDelayTimer = null;

            var beingShown = false;
            var shown = false;
            var trigger = jQuery('.trigger', this);
            var info = jQuery('.children', this).css('opacity', 0);


            jQuery([trigger.get(0), info.get(0)]).mouseover(function () {
                if (hideDelayTimer) clearTimeout(hideDelayTimer);
                if (beingShown || shown) {
                    // don't trigger the animation again
                    return;
                } else {
                    // reset position of info box
                    beingShown = true;

                    info.css({
                        top: 35,
                        left: 0,
                        display: 'block'
                    }).animate({
                        top: '-=' + distance + 'px',
                        opacity: 1
                    }, time, 'swing', function() {
                        beingShown = false;
                        shown = true;
                    });
                }

                return false;
            }).mouseout(function () {
                if (hideDelayTimer) clearTimeout(hideDelayTimer);
                hideDelayTimer = setTimeout(function () {
                    hideDelayTimer = null;
                    info.animate({
                        top: '-=' + distance + 'px',
                        opacity: 0
                    }, time, 'swing', function () {
                        shown = false;
                        info.css('display', 'none');
                    });

                }, hideDelay);

                return false;
            });
        });
    });
