<?php

    extract(shortcode_atts(array(
    // size of the map
    'width'   => '450', 
    'height'  => '300',
    'lat'     => '48.213',
    'lon'     => '16.378',
    'zoom'    => '4',
    'kml_file'=> 'NoFile',
    'type'      => 'Osm',
    'jsname'  => 'dummy'
    ), $atts));

    if ($width < 1 || $height < 1){
      Osm::traceText(DEBUG_ERROR, "e_map_size");
      Osm::traceText(DEBUG_INFO, "Error: ($width: ".$width." $height: ".$height.")!");
      $width = 450; $height = 300;
    }

    $MapCounter += 1;
    $MapName = 'map_ol3js_'.$MapCounter;
    $showMapInfoDiv = 0;
    $MapInfoDiv = $MapName.'_info';

    $output = '<div id="'.$MapName.'" class="OSM_Map" style="width:'.$width.'px; height:'.$height.'px; overflow:hidden;">';

    if(!defined('OL3_LIBS_LOADED')) {
      $output .= '<link rel="stylesheet" href="http://ol3js.org/en/master/build/ol.css" type="text/css"> ';
      $output .= '<script src="http://ol3js.org/en/master/build/ol.js" type="text/javascript"></script> ';
      define ('OL3_LIBS_LOADED', 1);
    }
 
    $output .= '<script type="text/javascript">'; 
    $output .= '/* <![CDATA[ */';
    $output .= '(function($) {';

    if ($jsname == "dummy"){
      $output .= 'var raster = new ol.layer.Tile({';

      if ($type == "Osm"){
        $output .= '  source: new ol.source.OSM()';
      }
      else if ($type == "stamen_toner"){
        $output .= '  source: new ol.source.Stamen({layer: "toner"})';
      }
      else if ($type == "stamen_watercolor"){
        $output .= '  source: new ol.source.Stamen({layer: "watercolor"})';
      }
      else if ($type == "stamen_terrain-labels"){
        $output .= '  source: new ol.source.Stamen({layer: "terrain-labels"})';
      }
      else {// unknwon => OSM map
        $output .= '  source: new ol.source.OSM()';
      }
      $output .= '});';
    }
    else {
      $output .= file_get_contents($jsname);
 //     //echo file_get_contents($jsname);
    }
    if ($kml_file != "NoFile"){

       $showMapInfoDiv = 1;

      $output .= '
var style = {
  "Point": [new ol.style.Style({
    image: new ol.style.Circle({
      fill: new ol.style.Fill({
        color: "rgba(255,255,0,0.4)"
      }),
      radius: 5,
      stroke: new ol.style.Stroke({
        color: "#ff0",
        width: 1
      })
    })
  })],
  "LineString": [new ol.style.Style({
    stroke: new ol.style.Stroke({
      color: "#f00",
      width: 3
    })
  })],
  "MultiLineString": [new ol.style.Style({
    stroke: new ol.style.Stroke({
      color: "#0f0",
      width: 3
    })
  })]
};
';

    $output .= '
      var vector = new ol.layer.Vector({
        source: new ol.source.KML({
          projection: "EPSG:3857",
          url:"'.$kml_file.'"
        })
       //style: function(feature, resolution) {return style[feature.getGeometry().getType()];}
      });
    ';
    $output .= '
      var '.$MapName.' = new ol.Map({
        layers: [raster,vector],
        renderer: "canvas",
        target: "'.$MapName.'",
        view: new ol.View2D({
          center: ol.proj.transform(['.$lon.','.$lat.'], "EPSG:4326", "EPSG:3857"), zoom: '.$zoom.'
        })
      });
    ';

    $output .= '
      var displayFeatureInfo = function(pixel) {
        var features = [];
        '.$MapName.'.forEachFeatureAtPixel(pixel, function(feature, layer) {
          features.push(feature);
        });
      if (features.length > 0) {
        var name_str, desc_str, info = [];
        var i, ii;
        for (i = 0, ii = features.length; i < ii; ++i) {
          name_str = "<span style=\"font-weight:bold\">" + features[i].get("name") + "</span>";
          desc_str = features[i].get("description");
          name_str = name_str + "<br>" + desc_str;
          info.push(name_str);
        }
        document.getElementById("'.$MapInfoDiv.'").innerHTML = info.join("<br>") || "(unknown)";
        '.$MapName.'.getTarget().style.cursor = "pointer";
        } else {
          document.getElementById("'.$MapInfoDiv.'").innerHTML = "Move the mouse over the icons <br>&nbsp;";
          '.$MapName.'.getTarget().style.cursor = "pointer";
        }
      };
    ';
    $output .= '
      $('.$MapName.'.getViewport()).on("mousemove", function(evt) {
        var pixel = '.$MapName.'.getEventPixel(evt.originalEvent);
        displayFeatureInfo(pixel);
      });
    ';
    $output .= ''.$MapName.'.on("singleclick", function(evt) {displayFeatureInfo(evt.pixel);});';
    }
    else{
      $output .= '
        var '.$MapName.' = new ol.Map({
          layers: [raster],
          target: "'.$MapName.'",
          view: new ol.View2D({
            center: ol.proj.transform(['.$lon.','.$lat.'], "EPSG:4326", "EPSG:3857"),
            zoom: '.$zoom.'
          })
        });
      ';
    }
    $output .= '})(jQuery)';
    $output .= '/* ]]> */';
    $output .= ' </script>';
    $output .= '</div>';
    if ($showMapInfoDiv == 1){
      $div_width = $width-10;
      $output .= '  <div style="margin-top:30px; background-color:#CED8F6; padding:5px; width:'.$div_width.'px; height:170px" id="'.$MapInfoDiv.'" class="OSM_Map";>&nbsp;';
      $output .= '  Move the mouse over the icons <br>&nbsp;';
      $output .= '  </div>';
    }
?>
