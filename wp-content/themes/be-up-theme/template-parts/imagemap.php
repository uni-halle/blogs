<?php
/**
 * Template part Imagemap
 *
 */ 

 // 2160x945 bearbeiten auf Seite

?>
<div class="themap">
<img src="<?php echo get_stylesheet_directory_uri(); ?>/assets/maps/imagemap_3x.jpg" class="map" usemap="#Map" style="width:100%; height:100%; margin-bottom: 1rem;" alt="Karte der Kliniken">

<map name="Map" id="Map">
	<area shape="circle" alt="Bochum" title="Bochum" coords="310,373,39" href="#Bochum" target="" />
	<area shape="circle" alt="Dortmund" title="Dortmund" coords="106,472,38" href="#Dortmund" target="" />
	<area shape="circle" alt="Dortmund2" title="Dortmund" coords="187,516,37" href="#Dortmund2" target="" />
	<area shape="circle" alt="Bielefeld" title="Bielefeld" coords="570,340,40" href="#Bielefeld" target="" />
	<area shape="circle" alt="Wolfsburg" title="Wolfsburg" coords="1195,190,41" href="#Wolfsburg" target="" />
	<area shape="circle" alt="Brandenburg" title="Brandenburg" coords="1786,190,38" href="#Brandenburg" target="" />
	<area shape="circle" alt="Berlin" title="Berlin" coords="1945,129,38" href="#Berlin" target="" />
	<area shape="circle" alt="Halle" title="Halle" coords="1561,528,37" href="#Halle" target="" />
	<area shape="circle" alt="Neuss" title="Neuss" coords="84,663,40" href="#Neuss" target="" />
	<area shape="circle" alt="Siegen" title="Siegen" coords="395,721,42" href="#Siegen" target="" />
	<area shape="circle" alt="Stolberg" title="Stolberg" coords="92,841,39" href="#Stolberg" target="" />
	<area shape="circle" alt="Jena" title="Jena" coords="1554,845,39" href="#Jena" target="" />

<!--
    <area alt="Dortmund1" title="Dortmund" href="#Dortmund" shape="circle" coords="117,446,35" />
    <area alt="Dortmund2" title="Dortmund" href="#Dortmund" shape="circle" coords="169,521,35" />
    <area alt="Bochum" title="Bochum" href="#Bochum" shape="circle" coords="303,393,35" />
    <area alt="Stolberg" title="Stolberg" href="#Stolberg" shape="circle" coords="90,844,35" />
    <area alt="Siegen" title="Siegen" href="#Siegen" shape="circle" coords="394,721,35" />
    <area alt="Bielefeld" title="Bielefeld" href="#Bielefeld" shape="circle" coords="638,440,35" />
    <area alt="Jena" title="Jena" href="#Jena" shape="circle" coords="1556,845,35" />
    <area alt="Halle" title="Halle(Saale)" href="#Hhalle" shape="circle" coords="1567,529,35" />
    <area alt="Wolfsburg" title="Wolfsburg" href="#Wolfsburg" shape="circle" coords="1743,420,35" />
    <area alt="Berlin1" title="Berlin" href="#Berlin" shape="circle" coords="1918,140,35" />
    <area alt="Berlin2" title="Berlin" href="#Berlin" shape="circle" coords="2015,145,35" />
    <area alt="Berlin3" title="Berlin" href="#Berlin" shape="circle" coords="1967,58,35" />
-->
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