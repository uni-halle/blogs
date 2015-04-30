<?php

    extract(shortcode_atts(array(
    // size of the map
    'width'      => '100%', 
    'height'     => '300',
    'map_center' => '58.213, 6.378',
    'zoom'       => '4',
	'file_list'  => 'NoFile',
	'file_color_list'  => 'NoColor',
    'type'       => 'Osm',
    'jsname'     => 'dummy',
    'marker_latlon'  => 'No',
    'map_border'  => '2px solid grey',
    'marker_name' => 'NoName'
    ), $atts));
    $VectorLayer_Marker = 'NO';
    $type = strtolower($type);

    $map_center = preg_replace('/\s*,\s*/', ',',$map_center);
    // get pairs of coordination
    $map_center_Array = explode( ' ', $map_center );
    list($lat, $lon) = explode(',', $map_center_Array[0]); 

    $pos = strpos($width, "%");
    if ($pos == false) {
      if ($width < 1){
        Osm::traceText(DEBUG_ERROR, "e_map_size");
        Osm::traceText(DEBUG_INFO, "Error: ($width: ".$width.")!");
        $width = 450;
      }
      $width_str = $width."px"; // make it 30px
    } else {// it's 30%
      $width_perc = substr($width, 0, $pos ); // make it 30 
      if (($width_perc < 1) || ($width_perc >100)){
        Osm::traceText(DEBUG_ERROR, "e_map_size");
        Osm::traceText(DEBUG_INFO, "Error: ($width: ".$width.")!");
        $width = "100%";
      }
      $width_str = substr($width, 0, $pos+1 ); // make it 30% 
    }

    $pos = strpos($height, "%");
    if ($pos == false) {
      if ($height < 1){
        Osm::traceText(DEBUG_ERROR, "e_map_size");
        Osm::traceText(DEBUG_INFO, "Error: ($height: ".$height.")!");
        $height = 300;
      }
      $height_str = $height."px"; // make it 30px
    } else {// it's 30%
      $height_perc = substr($height, 0, $pos ); // make it 30 
      if (($height_perc < 1) || ($height_perc >100)){
        Osm::traceText(DEBUG_ERROR, "e_map_size");
        Osm::traceText(DEBUG_INFO, "Error: ($height: ".$height.")!");
        $height = "100%";
      }
      $height_str = substr($height, 0, $pos+1 ); // make it 30% 
    }

    $marker_name = Osm_icon::replaceOldIcon($marker_name);

    $MapCounter += 1;
    $MapName = 'map_ol3js_'.$MapCounter;
    $showMapInfoDiv = 0;
    $MapInfoDiv = $MapName.'_info';

    $output = '<div id="'.$MapName.'" class="OSM_Map" style="width:'.$width_str.'; height:'.$height_str.'; overflow:hidden;border:'.$map_border.';">';

    if(!defined('OL3_LIBS_LOADED')) {
      $output .= '<link rel="stylesheet" href="'.Osm_OL_3_CSS.'" type="text/css"> ';
      $output .= '<script src="'.Osm_OL_3_LibraryLocation.'" type="text/javascript"></script> ';
      define ('OL3_LIBS_LOADED', 1);
    }
 
    $output .= '<script type="text/javascript">'; 
    $output .= '/* <![CDATA[ */';
    $output .= '(function($) {';

    if ($jsname == "dummy"){
      $ov_map = "ov_map";
      $array_control = "array_control";
      $extmap_type = "extmap_type";
      $extmap_name = "extmap_name";
      $extmap_address = "extmap_address";
      $extmap_init = "extmap_init";
      $theme = "theme";
      $output .= Osm_OLJS3::addTileLayer($MapName, $type, $ov_map, $array_control, $extmap_type, $extmap_name, $extmap_address, $extmap_init, $theme);
    }
    else {
      $output .= file_get_contents($jsname);
    }

    if (strtolower($marker_latlon) == 'osm_geotag'){ 
      $VectorLayer_Marker = $marker_latlon;
      global $post;
      $CustomFieldName = get_option('osm_custom_field','OSM_geo_data');
      $Data = get_post_meta($post->ID, $CustomFieldName, true);  
      $PostMarker = get_post_meta($post->ID, 'OSM_geo_icon', true);
      if ($PostMarker == ""){
        $PostMarker = $marker_name;
      }

      $Data = preg_replace('/\s*,\s*/', ',',$Data);
      // get pairs of coordination
      $GeoData_Array = explode( ' ', $Data );
      list($temp_lat, $temp_lon) = explode(',', $GeoData_Array[0]); 
      $DoPopUp = 'false';

      $PostMarker = Osm_icon::replaceOldIcon($PostMarker);
      if (Osm_icon::isOsmIcon($PostMarker) == 1){
        $Icon = Osm_icon::getIconsize($PostMarker);
        $Icon["name"]  = $PostMarker;
      }
      else { // if no marker is set for the post
        $this->traceText(DEBUG_ERROR, "e_not_osm_icon");
        $this->traceText(DEBUG_ERROR, $PostMarker);
        $Icon = Osm_icon::getIconsize($PostMarker);
        $Icon["name"]  = $marker_name;
      }

      list($temp_lat, $temp_lon) = Osm::checkLatLongRange('Marker',$temp_lat, $temp_lon,'no');
      if (($temp_lat != 0) || ($temp_lon != 0)){
      // set the center of the map to the first geotag
        $lat = $temp_lat;
        $lon = $temp_lon;
        $MarkerArray[] = array('lat'=> $temp_lat,'lon'=>$temp_lon,'text'=>$temp_popup,'popup_height'=>'150', 'popup_width'=>'150');
        $output .= '
        var iconFeature = new ol.Feature({
          geometry: new ol.geom.Point(
            ol.proj.transform(['.$lon.', '.$lat.'], "EPSG:4326", "EPSG:3857")),
          name: "Mein Inhalt",
        });

        var iconStyle = new ol.style.Style({
          image: new ol.style.Icon(/** @type {olx.style.IconOptions} */ ({
            anchor: [0.5, 46],
            anchorXUnits: "fraction",
            anchorYUnits: "pixels",
            opacity: 0.9,
            src: "'.OSM_PLUGIN_ICONS_URL.$Icon["name"].'"
          }))
        });
        iconFeature.setStyle(iconStyle);

        var vectorMarkerSource = new ol.source.Vector({
          features: [iconFeature]
        });

        var vectorMarkerLayer = new ol.layer.Vector({
          source: vectorMarkerSource
        });


        ';
      }// templat lon != 0
    } //($marker_latlon  == 'OSM_geotag')
    else if (strtolower($marker_latlon) != 'no'){
      $VectorLayer_Marker = $marker_latlon;

      $DoPopUp = 'false';

      $marker_name = Osm_icon::replaceOldIcon($marker_name);
      if (Osm_icon::isOsmIcon($marker_name) == 1){
        $Icon = Osm_icon::getIconsize($marker_name);
        $Icon["name"]  = $marker_name;
      }
      else { // if no marker is set for the post
        $this->traceText(DEBUG_ERROR, "e_not_osm_icon");
        $this->traceText(DEBUG_ERROR, $marker_name);
        $Icon = Osm_icon::getIconsize($marker_name);
        $Icon["name"]  = $marker_name;
      }

      $marker_latlon_temp = preg_replace('/\s*,\s*/', ',',$marker_latlon);
      // get pairs of coordination
      $GeoData_Array = explode( ' ', $marker_latlon_temp);
      list($temp_lat, $temp_lon) = explode(',', $GeoData_Array[0]); 

      list($temp_lat, $temp_lon) = Osm::checkLatLongRange('Marker',$temp_lat, $temp_lon,'no');
      if (($temp_lat != 0) || ($temp_lon != 0)){
        $lat_marker = $temp_lat;
        $lon_marker = $temp_lon;
        $MarkerArray[] = array('lat'=> $temp_lat,'lon'=>$temp_lon,'text'=>$temp_popup,'popup_height'=>'150', 'popup_width'=>'150');
        $output .= '
        var iconFeature = new ol.Feature({
          geometry: new ol.geom.Point(
            ol.proj.transform(['.$lon_marker.', '.$lat_marker.'], "EPSG:4326", "EPSG:3857")),
          name: "Mein Inhalt",
        });

        var iconStyle = new ol.style.Style({
          image: new ol.style.Icon(/** @type {olx.style.IconOptions} */ ({
            anchor: [0.5, 46],
            anchorXUnits: "fraction",
            anchorYUnits: "pixels",
            opacity: 0.9,
            src: "'.OSM_PLUGIN_ICONS_URL.$Icon["name"].'"
          }))
        });
        iconFeature.setStyle(iconStyle);

        var vectorMarkerSource = new ol.source.Vector({
          features: [iconFeature]
        });

        var vectorMarkerLayer = new ol.layer.Vector({
          source: vectorMarkerSource
        });


        ';
      }// templat lon != 0

    }
    if ($type == "openseamap"){
      $output .= '
      var '.$MapName.' = new ol.Map({
        layers: [raster, Layer2],
        target: "'.$MapName.'",
        view: new ol.View({
          center: ol.proj.transform(['.$lon.','.$lat.'], "EPSG:4326", "EPSG:3857"),
          zoom: '.$zoom.'
        })
      });';
    }
    else if ($type == "basemap_at"){
      $output .= '

      var template = "{Layer}/{Style}/{TileMatrixSet}/{TileMatrix}/{TileRow}/{TileCol}.png";
      var urls_basemap = [
        "http://maps1.wien.gv.at/basemap/" + template,
        "http://maps2.wien.gv.at/basemap/" + template,
        "http://maps3.wien.gv.at/basemap/" + template,
        "http://maps4.wien.gv.at/basemap/" + template,
        "http://maps.wien.gv.at/basemap/" + template
      ];

      // HiDPI support:
      // * Use "bmaphidpi" layer (pixel ratio 2) for device pixel ratio > 1
      // * Use "geolandbasemap" layer (pixel ratio 1) for device pixel ratio == 1
      var hiDPI = ol.has.DEVICE_PIXEL_RATIO > 1;

      var source_basemap = new ol.source.WMTS({
        projection: "EPSG:3857",
        layer: hiDPI ? "bmaphidpi" : "geolandbasemap",
        tilePixelRatio: hiDPI ? 2 : 1,
        style: "normal",
        matrixSet: "google3857",
        urls: urls_basemap,
        requestEncoding: "REST",
        tileGrid: new ol.tilegrid.WMTS({
          origin: [-20037508.3428, 20037508.3428],
            resolutions: [
            559082264.029 * 0.28E-3,
            279541132.015 * 0.28E-3,
            139770566.007 * 0.28E-3,
            69885283.0036 * 0.28E-3,
            34942641.5018 * 0.28E-3,
            17471320.7509 * 0.28E-3,
            8735660.37545 * 0.28E-3,
            4367830.18773 * 0.28E-3,
            2183915.09386 * 0.28E-3,
            1091957.54693 * 0.28E-3,
            545978.773466 * 0.28E-3,
            272989.386733 * 0.28E-3,
            136494.693366 * 0.28E-3,
            68247.3466832 * 0.28E-3,
            34123.6733416 * 0.28E-3,
            17061.8366708 * 0.28E-3,
            8530.91833540 * 0.28E-3,
            4265.45916770 * 0.28E-3,
            2132.72958385 * 0.28E-3,
            1066.36479193 * 0.28E-3
            ],
            matrixIds: [
              0, 1, 2, 3, 4, 5, 6, 7, 8, 9, 10, 11, 12, 13, 14, 15, 16, 17, 18, 19
            ]
       }),

       attributions: [
         new ol.Attribution({
           html: "Tiles &copy; " +
           "<a href=\"http://www.basemap.at/\">basemap.at</a>"
         }),
         ol.source.OSM.ATTRIBUTION
      ],
   });

   var '.$MapName.' = new ol.Map({
     layers: [
       new ol.layer.Tile({
         extent: [977844.377599999, 5837774.6617, 1915609.8654, 6295560.8122],
         source: source_basemap
       })
     ],
     target: "'.$MapName.'",
     view: new ol.View({
     center: ol.proj.transform(['.$lon.','.$lat.'], "EPSG:4326", "EPSG:3857"),
     zoom: '.$zoom.'
   })
    });';
    }
    else{
      $output .= '
      var '.$MapName.' = new ol.Map({
        layers: [raster],
        target: "'.$MapName.'",
        view: new ol.View({
          center: ol.proj.transform(['.$lon.','.$lat.'], "EPSG:4326", "EPSG:3857"),
          zoom: '.$zoom.'
        })
      });
      ';
    }

    if ($file_list != "NoFile"){
	  $VectorLayer_Marker = 'NO';
      $FileListArray   = explode( ',', $file_list ); 
	  $FileColorListArray = explode( ',', $file_color_list);
	  $this->traceText(DEBUG_INFO, "(NumOfFiles: ".sizeof($FileListArray)." NumOfColours: ".sizeof($FileColorListArray).")!");
	  if ((sizeof($FileColorListArray) > 0) && (sizeof($FileColorListArray) != sizeof($FileListArray))){
        $this->traceText(DEBUG_ERROR, "e_gpx_list_error");
	  }
	  else{
        for($x=0;$x<sizeof($FileListArray);$x++){
          $temp = explode(".",$FileListArray[$x]);
		  $FileType = strtolower($temp[(count($temp)-1)]);
		  if (($FileType == "gpx")||($FileType == "kml")){
		    if (sizeof($FileColorListArray) == 0){$Color = "blue";}
			else {$Color = $FileColorListArray[$x];}
			$gpx_marker_name = "mic_blue_pinother_02.png";
			if ($Color == "blue"){$gpx_marker_name = "mic_blue_pinother_02.png";}
			else if ($Color == "red"){$gpx_marker_name = "mic_red_pinother_02.png";}
			else if ($Color == "green"){$gpx_marker_name = "mic_green_pinother_02.png";}
			else if ($Color == "black"){$gpx_marker_name = "mic_black_pinother_02.png";}
		    $output .= Osm_OLJS3::addVectorLayer($MapName, $FileListArray[$x], $Color, $FileType, $x, $gpx_marker_name);
			}
		  else {
            $this->traceText(DEBUG_ERROR, "e_gpx_type_error");
			echo "ERROR";
		  }
        }
        $showMapInfoDiv = 0;

$output .= '

		    var element = document.createElement("div");
            element.className = "popup_div";
            

            var popup = new ol.Overlay({
              element: element,
              positioning: "bottom-center",
              stopEvent: false
            });
            '.$MapName.'.addOverlay(popup);

        var ClickdisplayFeatureInfo = function(a_evt) {
		
		  var lonlat = ol.proj.transform(a_evt.coordinate, "EPSG:3857", "EPSG:4326");
          var lon = lonlat[0];
          var lat = lonlat[1];

		  pixel = a_evt.pixel;
		
          var features = [];
          '.$MapName.'.forEachFeatureAtPixel(pixel, function(feature, layer) {
            features.push(feature);
          });
          if (features.length > 0) {
            var name_str, desc_str, info = [];
            var i, ii;
            for (i = 0, ii = features.length; i < ii; ++i) {
			  if (features[i].get("name")){
                name_str = "<span style=\"font-weight:bold; background-color: white\">" + features[i].get("name");
				desc_str = features[i].get("desc");
                name_str = name_str + "</span>";
			  }
			  else{
			    name_str = "" + "</span>";
			  }
			  info.push(name_str);
            }
			
			element.innerHTML = info.join("<br>") || "(unknown)";
            var coord = a_evt.coordinate;
            popup.setPosition(coord);
            $(document.getElementById("popup_div")).popover({
              "placement": "top",
              "html": true,
              "content": "test - FOR ME !!!!"
            });
			$(document.getElementById("popup_div")).popover("show");
            /**$("element").popover("show");*/
          } else {
		    element.innerHTML = "";
            $("element").popover("destroy");
          }
        };

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
              desc_str = features[i].get("desc");
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
        $('.$MapName.'.getViewport()).on("mousemove", function(evt) {
          var pixel = '.$MapName.'.getEventPixel(evt.originalEvent);
          displayFeatureInfo(pixel);
        });
        '.$MapName.'.on("singleclick", function(evt) {ClickdisplayFeatureInfo(evt);}); 
		
		
		
      '.$MapName.'.on("pointermove", function(e) {
        if (e.dragging) {
          $(element).popover("destroy");
          return;
        }
        var pixel = '.$MapName.'.getEventPixel(e.originalEvent);
        var hit = '.$MapName.'.hasFeatureAtPixel(pixel);
        '.$MapName.'.getTarget().style.cursor = hit ? "pointer" : "";
      });
		
		
';
		
	  }
    } // $file_list != "NoFile"


    if ($VectorLayer_Marker != 'NO'){
      $output .= '
      '.$MapName.'.addLayer(vectorMarkerLayer);';
      if ($VectorLayer_Marker_PopUp == "1") {
        $output .= '
        var element = document.createElement("div");
        element.className = "myclass";
        element.innerHTML = iconFeature.get("name");

        var popup = new ol.Overlay({
          element: element,
          positioning: "bottom-center",
          stopEvent: false
        });
        '.$MapName.'.addOverlay(popup);

        // display popup on click
        '.$MapName.'.on("click", function(evt) {
        var feature = '.$MapName.'.forEachFeatureAtPixel(evt.pixel,
          function(feature, layer) {
            return feature;
        });

        if (feature) {
          var geometry = feature.getGeometry();
          var coord = geometry.getCoordinates();
          popup.setPosition(coord);
          /**   $(element).popover({
            "placement": "top",
            "html": true,
            "content": "test";
          });
          $(element).popover("show");*/
        } 
        else {
        /** $(element).popover("destroy");*/
        }
      });
      // change mouse cursor when over marker
      '.$MapName.'.on("pointermove", function(e) {
      if (e.dragging) {
        $(element).popover("destroy");
        return;
      }
      var pixel = '.$MapName.'.getEventPixel(e.originalEvent);
      var hit = '.$MapName.'.hasFeatureAtPixel(pixel);
      '.$MapName.'.getTarget().style.cursor = hit ? "pointer" : "";
      });';
      }
    }
    

    $output .= '})(jQuery)';
    $output .= '/* ]]> */';
    $output .= ' </script>';
    $output .= '</div>';
    if ($showMapInfoDiv == 1){
      $div_width = $width-10;
      $output .= '  <div style="margin-top:30px; background-color:#CED8F6; padding:5px; width:'.$width_str.'; height:170px" id="'.$MapInfoDiv.'" class="OSM_Map";>&nbsp;';
      $output .= '  Move the mouse over the icons <br>&nbsp;';
      $output .= '  </div>';
    }
?>
