(function($) {
    window.quintlab = {};

    function init() {
        //put link contents into data attribute to allow css to reserve space for bold text
        $('.adjustbold a').each(function() {
            $(this)
            .addClass('adjustbold')
            .attr('data-bold-placeholder', $(this).text());
        });

        //slider
        $('.stage').slick({
            slide: '.slide',
            autoplay: true,
            autoplaySpeed: 6000,
            dots: true,
            appendArrows: '.controls',
            appendDots: '.controls .container'
        });

        //nav toggle
        $('.navtoggle').on('click', function() {
            var $nav = $(this).siblings('ul');
            $nav.toggleClass('open');
            if ($nav.hasClass('open')) {
                $nav.slideDown();
            } else {
                $nav.slideUp(function() {
                    $nav.attr('style', ''); //cleanup inline styles from slideUp()
                });
            }
        });

        //stage overlay toggle
        $('.stage .overlaycolumn h3').on('click', function() {
            var $content = $(this).closest('figcaption').find('summary');

            $(this).toggleClass('toggled');
            if ($(this).hasClass('toggled')) {
                $content.slideUp();
            } else {
                $content.slideDown();
            }
        });

        //font select
        $('.fontselect a').on('click', function() {
            $('body').removeClass('tiny medium large').addClass($(this).attr('class'));
            return false;
        });

        //maps
        $('.googlemap').each(function() {
            var map,
                marker,
                $map = $(this),
                attr = $map.attr('options'),
                opts = attr ? $.parseJSON(attr) : {},
                center = {lat: opts.lat, lng: opts.lng};

            map = new google.maps.Map($map[0], {
                center: center,
                zoom: opts.zoom
            });

            marker = new google.maps.Marker({
                position: center,
                map: map,
                icon: {
                    url: ql.THEME + 'img/icon_mapmarker.png',
                    size: new google.maps.Size(40, 40),
                    anchor: new google.maps.Point(20, 20)
                },
                title: opts.title
            })
        });
    }

    $(init);
}(jQuery));
