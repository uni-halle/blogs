<?php
/**
 * Template part Imagemap
 *
 */

?>
<div class="themap">
<img src="<?php echo get_stylesheet_directory_uri(); ?>/assets/maps/imagemap_3x.jpg" class="map" usemap="#Map" style="width:100%; height:100%; margin-bottom: 1rem;" alt="Karte der Kliniken">


<!--
    <map name="Map" id="Map">
        <area alt="" title="" href="#" shape="poly" coords="208,385,270,384,258,444,212,435" />
        <area alt="" title="" href="#" shape="poly" coords="286,442,316,473,301,498,274,500,255,483,257,452" />
        <area alt="" title="" href="#" shape="poly" coords="388,354,414,370,413,397,392,415,365,405,355,384,370,361" />
        <area alt="" title="" href="#" shape="poly" coords="651,374,680,392,680,420,665,434,638,434,623,417,625,391" />
        <area alt="" title="" href="#" shape="poly" coords="420,627,433,605,465,604,483,623,478,645,461,660,440,660,426,648" />
        <area alt="" title="" href="#" shape="poly" coords="61,759,92,780,80,812,55,820,37,806,34,778" />
        <area alt="" title="" href="#" shape="poly" coords="1455,790,1484,812,1477,843,1449,851,1425,834,1429,798" />
        <area alt="" title="" href="#" shape="poly" coords="1527,460,1554,440,1581,451,1589,479,1573,501,1543,502,1527,483" />
        <area alt="" title="" href="#" shape="poly" coords="1633,370,1651,345,1679,346,1696,364,1695,388,1673,405,1649,404,1637,391" />
        <area alt="" title="" href="#" shape="poly" coords="1842,128,1852,108,1875,102,1900,112,1902,137,1890,159,1857,161,1841,150" />
        <area alt="" title="" href="#" shape="poly" coords="1924,133,1937,112,1966,102,1987,120,1992,146,1974,161,1943,164,1930,149" />
        <area alt="" title="" href="#" shape="poly" coords="1919,39,1954,55,1952,87,1930,102,1903,91,1895,67,1902,49" />
    </map>
-->

<map name="Map" id="Map">
    <!--
    <area alt="Stolberg" title="Stolberg" href="#" shape="circle" coords="62,789,35" />
    <area alt="Dortmund" title="Dortmund" href="#" shape="circle" coords="240,416,35" />
    <area alt="Dortmund" title="Dortmund" href="#" shape="circle" coords="283,472,35" />
    <area alt="Siegen" title="Siegen" href="#" shape="circle" coords="451,630,35" />
    <area alt="Dortmund" title="Dortmund" href="#" shape="circle" coords="388,384,35" />
    <area alt="Paderborn" title="Paderborn" href="#" shape="circle" coords="650,406,35" />
    <area alt="Jena" title="Jena" href="#" shape="circle" coords="1455,821,35" />
    <area alt="Halle (Saale)" title="Halle (Saale)" href="#" shape="circle" coords="1557,473,35" />
    <area alt="Dessau" title="Dessau" href="#" shape="circle" coords="1665,374,35" />
    <area alt="Berlin" title="Berlin" href="#" shape="circle" coords="1873,133,35" />
    <area alt="Berlin" title="Berlin" href="#" shape="circle" coords="1926,69,35" />
    <area alt="Berlin" title="Berlin" href="#" shape="circle" coords="1957,133,35" />
-->
    <area alt="Dortmund1" title="Dortmund" href="#Dortmund" shape="circle" coords="117,446,35" />
    <area alt="Dortmund2" title="Dortmund" href="#Dortmund" shape="circle" coords="169,521,35" />
    <area alt="Bochum" title="Bochum" href="#Bochum" shape="circle" coords="303,393,35" />
    <area alt="Stolberg" title="Stolberg" href="#Stolberg" shape="circle" coords="90,844,35" />
    <area alt="Siegen" title="Siegen" href="#Siegen" shape="circle" coords="394,721,35" />
    <area alt="Paderborn" title="Paderborn" href="#Paderborn" shape="circle" coords="638,440,35" />
    <area alt="Jena" title="Jena" href="#Jena" shape="circle" coords="1556,845,35" />
    <area alt="Halle" title="Halle(Saale)" href="#Hhalle" shape="circle" coords="1567,529,35" />
    <area alt="Dessau" title="Dessau" href="#Dessau" shape="circle" coords="1743,420,35" />
    <area alt="Berlin1" title="Berlin" href="#Berlin" shape="circle" coords="1918,140,35" />
    <area alt="Berlin2" title="Berlin" href="#Berlin" shape="circle" coords="2015,145,35" />
    <area alt="Berlin3" title="Berlin" href="#Berlin" shape="circle" coords="1967,58,35" />
</map>
<script type="text/javascript" src="<?php echo get_stylesheet_directory_uri(); ?>/assets/js/jquery.rwdImageMaps.min.js"></script>
<script>
    jQuery(document).ready(function(e) {
        jQuery('img[usemap]').rwdImageMaps();
        //$('img[usemap]').maphilight()

        $('[data-accordion]').on({
            'down.zf.accordion': function(){
                jQuery('img[usemap]').rwdImageMaps();;
            },
            'up.zf.accordion': function(){
                //alert('closed');
            }
        });

    $('area').on('click', function(event) {
        //alert($(this).attr('alt') + ' clicked');
        event.preventDefault();
        $thisone = '#reveal_'+$(this).attr('alt');
        //alert ($thisone);
        $($thisone).foundation('open');
    });

    $("area").on("mouseenter", function () {
        //stuff to do on mouseover
        //alert("woo");
        $theone = $(this).attr('alt');
        $('#'+$theone).toggleClass('maplink_hover');
    });
    $("area").on("mouseleave", function () {
        //stuff to do on mouseover
        //alert("woo");
        $theone = $(this).attr('alt');
        $('#'+$theone).toggleClass('maplink_hover');
    });
    // $('.cell').on('down.zf.accordion', function() {
    //     console.log('opened');
    //     alert("asssd");
    // });
    });
</script>
</div>