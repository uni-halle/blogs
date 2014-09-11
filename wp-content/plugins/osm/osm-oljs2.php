<?php
/*  (c) Copyright 2014  Michael Kang (wp-osm-plugin.HanBlog.Net)

    This program is free software; you can redistribute it and/or modify
    it under the terms of the GNU General Public License as published by
    the Free Software Foundation; either version 2 of the License, or
    (at your option) any later version.

    This program is distributed in the hope that it will be useful,
    but WITHOUT ANY WARRANTY; without even the implied warranty of
    MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
    GNU General Public License for more details.

    You should have received a copy of the GNU General Public License
    along with this program; if not, write to the Free Software
    Foundation, Inc., 51 Franklin St, Fifth Floor, Boston, MA  02110-1301  USA
*/

class Osm_OpenLayers
{
  //support different types of GML Layers
  function addVectorLayer($a_LayerName, $a_FileName, $a_Colour, $a_Type)
  {
    Osm::traceText(DEBUG_INFO, "addVectorLayer(".$a_LayerName.",".$a_FileName.",".$a_Colour.",".$a_Type.")");
    $Layer = '';


// Functions for KML files
    $Layer .= '  function osm_'.$a_LayerName.'onPopupClose(evt) {';
    $Layer .= '    select.unselectAll();';
    $Layer .= '  }';

    $Layer .= '  function osm_'.$a_LayerName.'onFeatureSelect(event) {';
    $Layer .= '    var feature = event.feature;';
    $Layer .= '    var content = "<b>"+feature.attributes.name + "</b> <br>" + feature.attributes.description;';

    $Layer .= '    if (content.search("<script") != -1) {';
    $Layer .= '       content = "Content contained Javascript! Escaped content below.<br>" + content.replace(/</g, "&lt;");';
    $Layer .= '    }';
  
    $Layer .= '    popup = new OpenLayers.Popup.FramedCloud("OSM Plugin",';
    $Layer .= '      feature.geometry.getBounds().getCenterLonLat(),';
    $Layer .= '        new OpenLayers.Size(100,100),';
    $Layer .= '        content,';
    $Layer .= '        null, true, osm_'.$a_LayerName.'onPopupClose);';
    $Layer .= '    feature.popup = popup;';
    $Layer .= '    '.$a_LayerName.'.addPopup(popup);';
    $Layer .= '   }';

    $Layer .= '  function osm_'.$a_LayerName.'onFeatureUnselect(event) {';
    $Layer .= '    var feature = event.feature;';
    $Layer .= '    if(feature.popup) {';
    $Layer .= '      '.$a_LayerName.'.removePopup(feature.popup);';
    $Layer .= '      feature.popup.destroy();';
    $Layer .= '      delete feature.popup;';
    $Layer .= '    }   ';
    $Layer .= '  }';

    // Add the Layer with the GPX Track
    $Layer .= '  var lgml = new OpenLayers.Layer.Vector("'.$a_FileName.'",{';
    $Layer .= '   strategies: [new OpenLayers.Strategy.Fixed()],';
    $Layer .= '	  protocol: new OpenLayers.Protocol.HTTP({';
    $Layer .= '	   url: "'.$a_FileName.'",';


    if ($a_Type == 'GPX'){
    $Layer .= '	   format: new OpenLayers.Format.GPX()';
    $Layer .= '	  }),';
    
    $Layer .= '    style: {strokeColor: "'.$a_Colour.'", strokeWidth: 5, strokeOpacity: 0.5},';
    $Layer .= '    projection: new OpenLayers.Projection("EPSG:4326")';
    $Layer .= '  });';
    $Layer .= '  '.$a_LayerName.'.addLayer(lgml);';
    }
    else if ($a_Type == 'KML'){
    $Layer .= '	   format: new OpenLayers.Format.KML({';
    $Layer .= '	   extractStyles: true,';
    $Layer .= '	   extractAttributes: true,';
    $Layer .= '	   maxDepth: 2})';
    $Layer .= '	  }),';
    
    $Layer .= '    style: {strokeColor: "'.$a_Colour.'", strokeWidth: 5, strokeOpacity: 0.5},';
    $Layer .= '    projection: new OpenLayers.Projection("EPSG:4326")';
    $Layer .= '  });';
    $Layer .= '  '.$a_LayerName.'.addLayer(lgml);';

//+++
    $Layer .= '            select = new OpenLayers.Control.SelectFeature(lgml);';
            
    $Layer .= '            lgml.events.on({';
    $Layer .= '                "featureselected": osm_'.$a_LayerName.'onFeatureSelect,';
    $Layer .= '                "featureunselected": osm_'.$a_LayerName.'onFeatureUnselect';
    $Layer .= '            });';

    $Layer .= '            '.$a_LayerName.'.addControl(select);';
    $Layer .= '            select.activate();   ';
  //  $Layer .= '            map.zoomToExtent(new OpenLayers.Bounds(68.774414,11.381836,123.662109,34.628906));';
    }                 
    return $Layer;
  }

  function addGoogleTileLayer($a_LayerName, $a_Type){
    $Layer = '';
    if ($a_Type == 'GooglePhysical'){
    $Layer .= '
    var '.$a_LayerName.' = new OpenLayers.Map("'.$a_LayerName.'", {projection: "EPSG:3857", displayProjection: "EPSG:4326",
        layers: [new OpenLayers.Layer.Google("Google Physical",
                {type: google.maps.MapTypeId.TERRAIN, zoomMethod: null, animationEnabled: false, numZoomLevels: 23, MAX_ZOOM_LEVEL: 22}),
                new OpenLayers.Layer.Vector("OSM-plugin",{attribution:"<a href=\"http://mapicons.nicolasmollet.com/\">Icon</a> and <a href=\"http://wp-osm-plugin.hanblog.net\">OSM-Plugin<br><br></a>"})]});
    ';
    }
    else if ($a_Type == 'GoogleStreet'){
      $Layer .= '
      var '.$a_LayerName.' = new OpenLayers.Map(
        "'.$a_LayerName.'", 
        {projection: "EPSG:3857", 
         displayProjection: "EPSG:4326",
         layers: [new OpenLayers.Layer.Google("Google Streets",
                   {zoomMethod: null, animationEnabled: false, numZoomLevels: 23, MAX_ZOOM_LEVEL: 22}),
                  new OpenLayers.Layer.Vector("OSM-plugin",{attribution:"<a href=\"http://mapicons.nicolasmollet.com/\">Icon</a> and <a href=\"http://wp-osm-plugin.hanblog.net\">OSM-Plugin<br><br></a>"})]});
      ';
    }
    else if ($a_Type == 'GoogleHybrid'){
    $Layer .= '
    var '.$a_LayerName.' = new OpenLayers.Map("'.$a_LayerName.'", {projection: "EPSG:3857", displayProjection: "EPSG:4326",
        layers: [new OpenLayers.Layer.Google("Google Hybrid",
                {type: google.maps.MapTypeId.HYBRID, zoomMethod: null, animationEnabled: false, numZoomLevels: 23, MAX_ZOOM_LEVEL: 22}),
                 new OpenLayers.Layer.Vector("OSM-plugin",{attribution:"<a href=\"http://mapicons.nicolasmollet.com/\">Icon</a> and <a href=\"http://wp-osm-plugin.hanblog.net\">OSM-Plugin<br><br></a>"})]});
    ';
    }
    else if ($a_Type == 'GoogleSatellite'){
    $Layer .= '

    var '.$a_LayerName.' = new OpenLayers.Map("'.$a_LayerName.'", {projection: "EPSG:3857", displayProjection: "EPSG:4326",
        layers: [new OpenLayers.Layer.Google("Google Satellite",
                {type: google.maps.MapTypeId.SATELLITE, zoomMethod: null, animationEnabled: false, numZoomLevels: 23, MAX_ZOOM_LEVEL: 22}),
            new OpenLayers.Layer.Vector("OSM-plugin",{attribution:"<a href=\"http://mapicons.nicolasmollet.com/\">Icon</a> and <a href=\"http://wp-osm-plugin.hanblog.net\">OSM-Plugin<br><br></a>"})]});
    ';
    }
    else if ($a_Type == 'AllGoogle'){
    $Layer .= '
    var '.$a_LayerName.' = new OpenLayers.Map("'.$a_LayerName.'", {
        projection: "EPSG:3857", displayProjection: "EPSG:4326",
        layers: [
            new OpenLayers.Layer.Google(
                "Google Physical",
                {type: google.maps.MapTypeId.TERRAIN, zoomMethod: null}
            ),
            new OpenLayers.Layer.Google(
                "Google Streets", 
                {numZoomLevels: 20, zoomMethod: null}
            ),
            new OpenLayers.Layer.Google(
                "Google Hybrid",
                {type: google.maps.MapTypeId.HYBRID, numZoomLevels: 20, zoomMethod: null}
            ),
            new OpenLayers.Layer.Google(
                "Google Satellite",
                {type: google.maps.MapTypeId.SATELLITE, numZoomLevels: 22, zoomMethod: null}
            ),
            new OpenLayers.Layer.Vector("OSM-plugin",{attribution:"<a href=\"http://mapicons.nicolasmollet.com/\">Icon</a> and <a href=\"http://wp-osm-plugin.hanblog.net\">OSM-Plugin<br><br></a>"})
        ]});
    '.$a_LayerName.'.addControl(new OpenLayers.Control.LayerSwitcher());
    ';
    }
    else {// ERROR => set to default
    $Layer .= '
    var '.$a_LayerName.' = new OpenLayers.Map("'.$a_LayerName.'", {projection: "EPSG:3857"});
     var Street = new OpenLayers.Layer.Google("Google Streets", {numZoomLevels: 20});
    '.$a_LayerName.'.addLayers([Street]);
    ';
    }
    return $Layer;
}

// support different types of GML Layers
  function addTileLayer($a_LayerName, $a_Type, $a_OverviewMapZoom, $a_MapControl, $a_ExtType, $a_ExtName, $a_ExtAddress, $a_ExtInit, $a_theme)
  {
    Osm::traceText(DEBUG_INFO, "addTileLayer(".$a_LayerName.",".$a_Type.",".$a_OverviewMapZoom.")");

    $Layer = '';
    if ($a_theme == 'private'){
      $Layer .= ' OpenLayers.ImgPath = "'.OSM_OPENLAYERS_THEMES_URL.'";';
    }
    else {
      $Layer .= ' OpenLayers.ImgPath = "'.OSM_PLUGIN_THEMES_URL.$a_theme.'/";';
    }
    $Layer .= ' '.$a_LayerName.' = new OpenLayers.Map ("'.$a_LayerName.'", {';
    $Layer .= '            controls:[';
    if (($a_MapControl[0] != 'off') && (strtolower($a_Type)!= 'ext')) {
      $Layer .= '              new OpenLayers.Control.Navigation(),';
      $Layer .= '              new OpenLayers.Control.PanZoom(),';
      $Layer .= '              new OpenLayers.Control.Attribution()';
    }
    else if (($a_MapControl[0] == 'off') && (strtolower($a_Type)!= 'ext')){
      $Layer .= '              new OpenLayers.Control.Attribution()';
    }
    else if (($a_MapControl[0] != 'off') && (strtolower($a_Type)== 'ext')){
      $Layer .= '              new OpenLayers.Control.Navigation(),';
      $Layer .= '              new OpenLayers.Control.PanZoom(),';
      $Layer .= '              new OpenLayers.Control.Attribution()';
    }
    else if (($a_MapControl[0] == 'off') && (strtolower($a_Type)== 'ext')){
      // there is nothing to do
    }
    else {
      Osm::traceText(DEBUG_ERROR, "addOsmLayer(".$a_MapControl[0].",".$a_Type.")");
    }
    $Layer .= '              ],';
    $Layer .= '          maxExtent: new OpenLayers.Bounds(-20037508.34,-20037508.34,20037508.34,20037508.34),';
    $Layer .= '          maxResolution: 156543.0399,';
    $Layer .= '          numZoomLevels: 19,';
    $Layer .= '          units: "m",';
    $Layer .= '          projection: new OpenLayers.Projection("EPSG:900913"),';
    $Layer .= '          displayProjection: new OpenLayers.Projection("EPSG:4326")';
    $Layer .= '      } );';
    if (($a_Type == 'AllOsm') || ($a_Type == 'All')){
      $Layer .= 'var layerMapnik = new OpenLayers.Layer.OSM.Mapnik("Mapnik");';
      $Layer .= 'var layerCycle  = new OpenLayers.Layer.OSM.CycleMap("CycleMap");';
      //$Layer .= 'var layerOSM_Attr = new OpenLayers.Layer.Vector("OSM-plugin",{attribution:"<a href=\"http://wp-osm-plugin.hanblog.net\">OSM plugin</a>"});';
      $Layer .= ''.$a_LayerName.'.addLayers([layerMapnik, layerCycle]);';
      $Layer .= ''.$a_LayerName.'.addControl(new OpenLayers.Control.LayerSwitcher());';
    }
    else if ($a_Type == 'basemap_at'){
 	// Create a WMTS layer, with given matrix IDs.
      $Layer .= 'var matrixIds = new Array(18);';
      $Layer .= 'for (var i=0; i<=18; ++i) {';
      $Layer .= '  matrixIds[i] = new Object();';
      $Layer .= '  matrixIds[i].identifier = "" + i;';
      $Layer .= '} ';
      $Layer .= 'var layerosm = new OpenLayers.Layer.OSM.Mapnik("Mapnik");';
      $Layer .= 'var layerbasemap_at = new OpenLayers.Layer.WMTS({
                   url: "'.Osm_BaseMap_Tiles.'{Style}/{TileMatrixSet}/{TileMatrix}/{TileRow}/{TileCol}.jpeg",
                   name: "basemap.at",
                   layer: "geolandbasemap",
                   style: "normal",
                   matrixSet: "google3857",
                   requestEncoding: "REST",
                   matrixIds: matrixIds,
                   tileOptions: {crossOriginKeyword: null},
                   transitionEffect: "resize"
                  });
      ';
      $Layer .= 'layerbasemap_at.metadata = {link: "http://www.basemap.at/"};';
      $Layer .= 'var layerOSM_Attr = new OpenLayers.Layer.Vector("OSM-plugin",{attribution:"<a href=\"http://mapicons.nicolasmollet.com/\">Icon</a> and <a href=\"http://basemap.at\">basemap.at</a> and <a href=\"http://wp-osm-plugin.hanblog.net\">OSM-Plugin</a>"});';
      $Layer .= ''.$a_LayerName.'.addLayers([layerbasemap_at, layerosm, layerOSM_Attr]);';
      $Layer .= ''.$a_LayerName.'.addControl(new OpenLayers.Control.LayerSwitcher());';
    }
    else if ($a_Type == 'OpenSeaMap'){
      $Layer .= 'var layerMapnik   = new OpenLayers.Layer.OSM.Mapnik("Mapnik");';
      $Layer .= 'var layerSeamark  = new OpenLayers.Layer.TMS("Seezeichen", "http://t1.openseamap.org/seamark/", { numZoomLevels: 18, type: "png", getURL: getTileURL, tileOptions: {crossOriginKeyword: null}, isBaseLayer: false, displayOutsideMaxExtent: true});';
      $Layer .= 'var layerPois = new OpenLayers.Layer.Vector("Haefen", { projection: new OpenLayers.Projection("EPSG:4326"), visibility: true, displayOutsideMaxExtent:true});';
      $Layer .= 'layerPois.setOpacity(0.8);';
      $Layer .= ''.$a_LayerName.'.addLayers([layerMapnik, layerSeamark, layerPois]);';
      $Layer .= ''.$a_LayerName.'.addControl(new OpenLayers.Control.LayerSwitcher());';
    }
    else if ($a_Type == 'OpenWeatherMap'){
    	$Layer .= 'var layerMapnik   = new OpenLayers.Layer.OSM.Mapnik("Mapnik");';
    	$Layer .= 'var layerWeather = new OpenLayers.Layer.Vector.OWMWeather("Weather");';
    	$Layer .= ''.$a_LayerName.'.addLayers([layerMapnik, layerWeather]);';
    	$Layer .= ''.$a_LayerName.'.addControl(new OpenLayers.Control.LayerSwitcher());';
    }
    else if ($a_Type == 'stamen_watercolor'){
        $Layer .= 'var lmap = new OpenLayers.Layer.OSM.StamenWC("Stamen watercolor");';
        $Layer .= ''.$a_LayerName.'.addLayers([lmap]);';
    }
    else if ($a_Type == 'stamen_toner'){
        $Layer .= 'var lmap = new OpenLayers.Layer.OSM.StamenToner("Stamen Toner");';
        $Layer .= ''.$a_LayerName.'.addLayers([lmap]);';
    }
    else{
      if ($a_Type == 'Mapnik'){
        $Layer .= 'var lmap = new OpenLayers.Layer.OSM.Mapnik("Mapnik");';
        $Layer .= ''.$a_LayerName.'.addLayers([lmap]);';
      } 
      if ($a_Type == 'mapnik_ssl'){  
        $Layer .= 'var lmap = new OpenLayers.Layer.OSM.Mapnik("Mapnik");';
        $Layer .= ''.$a_LayerName.'.addLayers([lmap]);';
      }
      else if ($a_Type == 'CycleMap'){
        $Layer .= 'var lmap = new OpenLayers.Layer.OSM.CycleMap("CycleMap");';
        $Layer .= ''.$a_LayerName.'.addLayers([lmap]);';
      }
      else if (($a_Type == 'Ext') || ($a_Type == 'ext')) {
        $Layer .= 'var lmap = new OpenLayers.Layer.'.$a_ExtType.'("'.$a_ExtName.'","'.$a_ExtAddress.'",{'.$a_ExtInit.', attribution: "OpenLayers with"});';
        $Layer .= 'var layerOSM_Attr = new OpenLayers.Layer.Vector("OSM-plugin",{attribution:"<a href=\"http://mapicons.nicolasmollet.com/\">Icon</a> and <a href=\"http://wp-osm-plugin.hanblog.net\">OSM plugin</a>"});';
        $Layer .= ''.$a_LayerName.'.addLayers([lmap,layerOSM_Attr]);';
      }
    }
    if ($a_MapControl[0] != 'No'){
      foreach ( $a_MapControl as $MapControl ){
        $MapControl = strtolower($MapControl);
        if ( $MapControl == 'scaleline'){
          $Layer .= ''.$a_LayerName.'.addControl(new OpenLayers.Control.ScaleLine({geodesic: true}));';
        }
        elseif ($MapControl == 'scale'){
          //$Layer .= ''.$a_LayerName.'.addControl(new OpenLayers.Control.Scale());';
          $Layer .= ''.$a_LayerName.'.addControl(new OpenLayers.Control.Scale({geodesic: true}));';
//var scalebar = new OpenLayers.Control.ScaleLine({geodesic: true});

        }
        elseif ($MapControl == 'mouseposition'){
          $Layer .= ''.$a_LayerName.'.addControl(new OpenLayers.Control.MousePosition({displayProjection: new OpenLayers.Projection("EPSG:4326")}));';
        }
      }
    }

    // add the overview map
    if ($a_OverviewMapZoom >= 0){  
      $Layer .= 'layer_ov = new OpenLayers.Layer.OSM;';
      if ($a_OverviewMapZoom > 0 && $a_OverviewMapZoom < 18 ){
        $Layer .= 'var options = {
                      layers: [layer_ov],
                      mapOptions: {numZoomLevels: '.$a_OverviewMapZoom.'}
                      };';
      }
      else{
        $Layer .= 'var options = {layers: [layer_ov]};';
      }
      $Layer .= ''.$a_LayerName.'.addControl(new OpenLayers.Control.OverviewMap(options));';
    }
    return $Layer;
  }

  function AddClickHandler($a_MapName, $a_msgBox)
  {
    Osm::traceText(DEBUG_INFO, "AddClickHandler(".$a_msgBox.")");
    $a_msgBox = strtolower($a_msgBox);
    $Layer = '';

//++ //++ set marker
    $Layer .= '  var markerslayer = new OpenLayers.Layer.Markers( "Markers" );';
   $Layer .= '	 var size = new OpenLayers.Size(21,25);';
    $Layer .= '  var offset = new OpenLayers.Pixel(-(size.w/2), -size.h);';
    $Layer .= '  var click_icon = new OpenLayers.Icon("http://www.openlayers.org/dev/img/marker.png",size,offset); ';  
    $Layer .= '  '.$a_MapName.'.addLayer(markerslayer);';
//--  
    $Layer .= 'OpenLayers.Control.Click = OpenLayers.Class(OpenLayers.Control, {';               
    $Layer .= ' 	                defaultHandlerOptions: {';
    $Layer .= ' 	                    "single": true,';
    $Layer .= ' 	                    "double": false,';
    $Layer .= ' 	                    "pixelTolerance": 0,';
    $Layer .= ' 	                    "stopSingle": false,';
    $Layer .= ' 	                    "stopDouble": false';
    $Layer .= ' 	                },';

    $Layer .= ' 	                initialize: function(options) {';
    $Layer .= ' 	                    this.handlerOptions = OpenLayers.Util.extend(';
    $Layer .= ' 	                        {}, this.defaultHandlerOptions';
    $Layer .= ' 	                    );';
    $Layer .= ' 	                    OpenLayers.Control.prototype.initialize.apply(';
    $Layer .= ' 	                        this, arguments';
    $Layer .= ' 	                    );';
    $Layer .= ' 	                    this.handler = new OpenLayers.Handler.Click(';
    $Layer .= ' 	                        this, {';
    $Layer .= ' 	                            "click": this.trigger';
    $Layer .= ' 	                        }, this.handlerOptions';
    $Layer .= ' 	                    );';
    $Layer .= ' 	                },';

    $Layer .= ' 	                trigger: function(e) {';
    $Layer .= '                     var LayerName =    '.$a_MapName.'.baseLayer.name; ';  
    $Layer .= ' 	            var Centerlonlat = '.$a_MapName.'.getCenter(e.xy).clone();';
    $Layer .= ' 	            var Clicklonlat = '.$a_MapName.'.getLonLatFromViewPortPx(e.xy);';
    $Layer .= ' 	            var zoom = '.$a_MapName.'.getZoom(e.xy);';
    $Layer .= '                     Centerlonlat.transform('.$a_MapName.'.getProjectionObject(), '.$a_MapName.'.displayProjection);';
    $Layer .= '                     Clicklonlat.transform('.$a_MapName.'.getProjectionObject(), '.$a_MapName.'.displayProjection);';
    $Layer .= '                     Centerlonlat.lat = Math.round( Centerlonlat.lat * 1000. ) / 1000.;'; // mapcenter
    $Layer .= '                     Centerlonlat.lon = Math.round( Centerlonlat.lon * 1000. ) / 1000.;';
    $Layer .= '                     Clicklonlat.lat = Math.round( Clicklonlat.lat * 100000. ) / 100000.;';// markerposition
    $Layer .= '                     Clicklonlat.lon = Math.round( Clicklonlat.lon * 100000. ) / 100000.;';  

    if ($a_msgBox == 'sc_gen'){  
    $Layer .= ' div = document.getElementById("ShortCode_Div");';

    $Layer .= ' var MarkerName    = osm_getRadioValue("Markerform");';
    $Layer .= ' var GpxColour     = osm_getRadioValue("GPXcolourform");';
    $Layer .= ' var BorderColour  = osm_getRadioValue("Bordercolourform");';
    $Layer .= ' var NaviName      = osm_getRadioValue("Naviform");';
    $Layer .= ' var CntrStyle     = osm_getRadioValue("ControlStyleform");';

    $Layer .= ' MarkerField = "";';
    $Layer .= ' NaviField = "";';
    $Layer .= ' MarkerTextField_01 = "";';
    $Layer .= ' MarkerTextField_02 = "";';
    $Layer .= ' MarkerTextField_03 = "";';
    $Layer .= ' MarkerTextField_04 = "";';
    $Layer .= ' GpxFileField = "";';
    $Layer .= ' GpxColourField = "";';
    $Layer .= ' MarkerFileField = "";';
    $Layer .= ' MapControlField = "";';
    $Layer .= ' BorderColourField = "";';
    $Layer .= ' ControlStyleField = "";';
    $Layer .= ' ZIndexField = "";';

    $Layer .= ' if (MarkerName != "undefined"){';
    $Layer .= '   MarkerField = " marker=\""+Clicklonlat.lat+","+Clicklonlat.lon+
"\" marker_name=\"" + MarkerName + "\"";';  

    $Layer .= '   if (NaviName != "undefined"){';
    $Layer .= '     NaviField = " marker_routing=\""+ NaviName + "\"";';  
    $Layer .= '    }';

    $Layer .= '   if (document.Markertextform.MarkerText_01.value != "Max Mustermann"){';
    $Layer .= '     MarkerTextField_01 = " m_txt_01=\""+ document.Markertextform.MarkerText_01.value + "\"";';  
    $Layer .= '   }';    
    $Layer .= '   if (document.Markertextform.MarkerText_02.value != "Musterstr. 90"){';
    $Layer .= '    MarkerTextField_02 = " m_txt_02=\""+ document.Markertextform.MarkerText_02.value + "\"";';  
    $Layer .= '   }';
    $Layer .= '   if (document.Markertextform.MarkerText_03.value != "1020 Mustercity"){';
    $Layer .= '    MarkerTextField_03 = " m_txt_03=\""+ document.Markertextform.MarkerText_03.value + "\"";';  
    $Layer .= '   }';
    $Layer .= '   if (document.Markertextform.MarkerText_04.value != "MusterCountry"){';
    $Layer .= '    MarkerTextField_04 = " m_txt_04=\""+ document.Markertextform.MarkerText_04.value + "\"";';  
    $Layer .= '    }';

    $Layer .= '  }'; // if (MarkerName != "undefined")

    $Layer .= ' if (document.GPXfileform.GpxFile.value != "http://"){';
    $Layer .= '   GpxFileField = " gpx_file=\""+ document.GPXfileform.GpxFile.value + "\"";';  
    $Layer .= '  }';
    $Layer .= ' if (GpxColour != "undefined"){';
    $Layer .= '   GpxColourField = " gpx_colour=\""+ GpxColour + "\"";';  
    $Layer .= '  }';
    $Layer .= ' if (CntrStyle != "undefined"){';
    $Layer .= '   ControlStyleField = " theme=\""+ CntrStyle + "\"";';  
    $Layer .= '  }';
    $Layer .= ' if (document.ZIndexform.ZIndex.checked){';
    $Layer .= '   ZIndexField = " z_index=\""+ document.ZIndexform.ZIndex.value + "\"";';  
    $Layer .= '  }';    
    $Layer .= ' if (document.Markerfileform.MarkerFile.value != "http://"){';
    $Layer .= '   MarkerFileField = " marker_file=\""+ document.Markerfileform.MarkerFile.value + "\"";';  
    $Layer .= '  }';

    $Layer .= ' if (document.MapControlform.MapControl.checked){';
    $Layer .= '   MapControlField = " control=\""+ document.MapControlform.MapControl.value;';  
    $Layer .= '  }';
    $Layer .= ' if (document.MapControlform.Mouseposition.checked){';
    $Layer .= '   if (document.MapControlform.MapControl.checked){';
    $Layer .= '     MapControlField = MapControlField + "," + document.MapControlform.Mouseposition.value;';  
    $Layer .= '    }';
    $Layer .= '    else{';
    $Layer .= '      MapControlField = " control=\""+ document.MapControlform.Mouseposition.value;';  
    $Layer .= '    }';
    $Layer .= '  }';
    $Layer .= ' if ((document.MapControlform.Mouseposition.checked) || (document.MapControlform.MapControl.checked)) {';
    $Layer .= '  MapControlField = MapControlField + "\"";';
    $Layer .= '  }';

    $Layer .= ' if (BorderColour != "undefined"){';
    $Layer .= '   BorderColourField = " map_border=\"thin solid "+ BorderColour + "\"";';  
    $Layer .= '  }';

    $Layer .= ' div.innerHTML = "[osm_map lat=\"" + Centerlonlat.lat + "\" lon=\"" + Centerlonlat.lon + "\" zoom=\"" + zoom + "\" width=\"600\" height=\"450\"" + MarkerField + GpxFileField + GpxColourField + BorderColourField + MarkerFileField + MapControlField + MarkerTextField_01 + MarkerTextField_02 + MarkerTextField_03 + MarkerTextField_04 + NaviField + ZIndexField + ControlStyleField + " type=\""+LayerName+"\"]";';

    $Layer .= '  markerslayer.clearMarkers();';
    $Layer .= '    var lonlat = new OpenLayers.LonLat(Centerlonlat.lon,Centerlonlat.lat); ';

//    $Layer .= '  var lonlat = new OpenLayers.LonLat(Centerlonlat.lon,Centerlonlat.lat).transform(map.displayProjection, map.projection);';
    $Layer .= '  markerslayer.addMarker(new OpenLayers.Marker(lonlat, click_icon.clone()));';
    }
    else if( $a_msgBox == 'metabox_sc_gen'){
    $Layer .= ' MarkerField = "";';
    $Layer .= ' ThemeField  = "";';
    $Layer .= ' TypeField   = "";';
    $Layer .= ' ImportField = "";';
    $Layer .= ' if (document.post.osm_map_type.value != "none"){';
    $Layer .= '   ImportField = " type=\""+ document.post.osm_map_type.value + "\"";';  
    $Layer .= ' }';
    $Layer .= ' if ((document.post.osm_import.value != "none") && (document.post.osm_import.value != "single")){';
    $Layer .= ' TypeField = " import=\""+ document.post.osm_import.value + "\"";';  
    $Layer .= ' }';
    $Layer .= ' if (document.post.osm_marker.value != "none"){';
    $Layer .= ' MarkerField = " marker=\""+Clicklonlat.lat+","+Clicklonlat.lon+
"\" marker_name=\"" + document.post.osm_marker.value + "\"";';  
    $Layer .= '  }';
    $Layer .= ' if (document.post.osm_theme.value == "dark"){';
    $Layer .= ' ThemeField = " control=\"mouseposition,scaleline\" map_border=\"thin solid grey\" theme=\"dark\"";';  
    $Layer .= '  }';
    $Layer .= ' if (document.post.osm_theme.value == "blue"){';
    $Layer .= ' ThemeField = " control=\"mouseposition,scaleline\" map_border=\"thin solid blue\" theme=\"ol\"";';  
    $Layer .= '  }';
    $Layer .= ' if (document.post.osm_theme.value == "orange"){';
    $Layer .= ' ThemeField = " control=\"mouseposition,scaleline\" map_border=\"thin solid orange\" theme=\"ol_orange\"";';  
    $Layer .= '  }';

    $Layer .= ' if (document.post.osm_mode.value == "sc_gen"){';
    $Layer .= ' GenTxt = "[osm_map lat=\"" + Centerlonlat.lat + "\" lon=\"" + Centerlonlat.lon + "\" zoom=\"" + zoom + "\" width=\"600\" height=\"450\" " + ThemeField + MarkerField + TypeField + "]";';  
    $Layer .= '  }';
    $Layer .= ' if (document.post.osm_mode.value == "geotagging"){';
    $Layer .= ' GenTxt = "For geotagging your post/page create a custom field. <br>Name: OSM_geo_data <br>Value: "+Clicklonlat.lat+","+Clicklonlat.lon;';  
    $Layer .= '  }';

      $Layer .= ' div = document.getElementById("ShortCode_Div");';
      $Layer .= ' div.innerHTML = GenTxt;';
    }
    else if( $a_msgBox == 'lat_long'){
      $Layer .= ' alert("Lat= " + Clicklonlat.lat + " Lon= " + Clicklonlat.lon);';   
    }
    $Layer .= ' 	                }';
    $Layer .= ' 	';
    $Layer .= ' 	            });';
    $Layer .= 'var click = new OpenLayers.Control.Click();';
    $Layer .= ''.$a_MapName.'.addControl(click);';
    $Layer .= 'click.activate();';
    return $Layer;
  }

  function addMarkerListLayer($a_MapName, $Icon ,$a_MarkerArray, $a_DoPopUp)
  {
    Osm::traceText(DEBUG_INFO, "addMarkerListLayer(".$a_MapName.",".$Icon[name].",".$Icon[width].",".$Icon[height].",".$a_MarkerArray.",".$Icon[offset_width].",".$Icon[offset_height].",".$a_DoPopUp.")");

    $Layer = '';
    $Layer .= 'var MarkerLayer = new OpenLayers.Layer.Markers("Marker");';
    $Layer .= $a_MapName.'.addLayer(MarkerLayer);';

    $Layer .= '
      function osm_'.$a_MapName.'MarkerPopUpClick(a_evt){
        if (this.popup == null){
          this.popup = this.createPopup(this.closeBox);
          '.$a_MapName.'.addPopup(this.popup);
          this.popup.show();
        }
        else{
          for (var i = 0; i < '.$a_MapName.'.popups.length; i++){
          '.$a_MapName.'.popups[i].hide();
          }
          this.popup.toggle();
        }
        OpenLayers.Event.stop(a_evt);
      }
    ';

    $Layer .= 'var data = {};';
    $Layer .= 'var currentPopup;';
    $Layer .= '
      data.icon = new OpenLayers.Icon("'.OSM_PLUGIN_ICONS_URL.$Icon[name].'",
        new OpenLayers.Size('.$Icon[width].','.$Icon[height].'),
        new OpenLayers.Pixel('.$Icon[offset_width].', '.$Icon[offset_height].'));
    ';   
    
    $NumOfMarker = count($a_MarkerArray);
    for ($row = 0; $row < $NumOfMarker; $row++){

      // add the the backslashes
      $OSM_HTML_TEXT = addslashes($a_MarkerArray[$row][text]);

      $Layer .= 'var ll = new OpenLayers.LonLat('.$a_MarkerArray[$row][lon].','.$a_MarkerArray[$row][lat].').transform('.$a_MapName.'.displayProjection, '.$a_MapName.'.projection);';

      $Layer .= 'var feature = new OpenLayers.Feature(MarkerLayer, ll, data);';         
      $Layer .= 'feature.closeBox = true;';
      $Layer .= 'feature.popupClass = OpenLayers.Class(OpenLayers.Popup.FramedCloud, {"autoSize": true, minSize: new OpenLayers.Size('.$a_MarkerArray[$row][popup_width].','.$a_MarkerArray[$row][popup_height].'),"keepInMap": true } );';      
      $Layer .= 'feature.data.popupContentHTML = "'.$OSM_HTML_TEXT.'";';
      $Layer .= 'feature.data.overflow = "hidden";';

      $Layer .= 'var marker = new OpenLayers.Marker(ll,data.icon.clone());';

      $Layer .= 'marker.feature = feature;';
      if ($a_DoPopUp == 'true'){
        $Layer .= 'marker.events.register("mousedown", feature, osm_'.$a_MapName.'MarkerPopUpClick);';
      }

      $Layer .= 'MarkerLayer.addMarker(marker);';

      // if there is just one marker, let's pop it up
      if ($a_DoPopUp == 'true'){
        $Layer .= $a_MapName.'.addPopup(feature.createPopup(feature.closeBox));';   // maybe there is a better way to do 
        if ($NumOfMarker > 1){
          $Layer .= 'feature.popup.toggle();';                              // it than create and toggle!
        }
      }
     // if (($a_DoPopUp == 'true')&&($NumOfMarker == 1)){
     //   $Layer .= ''.$a_MapName.'.addPopup(feature.createPopup(feature.closeBox));'; 
     // }
    }
    return $Layer;
  }

//++
  function addLineLayer($a_LayerName, $a_MarkerArray)
  {
    Osm::traceText(DEBUG_INFO, "addLineLayer(".$a_LayerName.")");

    $Layer = '';
    $Layer .= 'var LonList = new Array()  ';
    $Layer .= 'var LatList = new Array()  ';
    $NumOfMarker = count($a_MarkerArray);
    for ($ii = 0; $ii < $NumOfMarker; $ii++){
      $Layer .= 'LonList['.$ii.']='.$a_MarkerArray[$ii][lon];
      $Layer .= 'LatList['.$ii.']='.$a_MarkerArray[$ii][lat];
    }
    return $Layer;
  }
//--

    
  function addTextLayer($a_MapName, $a_MarkerName, $a_marker_file)
  {
    Osm::traceText(DEBUG_INFO, "addTextLayer(".$a_marker_file.")");   

    $Layer = '';
    $Layer .= 'var pois = new OpenLayers.Layer.Text( "'.$a_MarkerName.'",';
    $Layer .= '        { location:"'.$a_marker_file.'",';
    $Layer .= '          projection: '.$a_MapName.'.displayProjection';
    $Layer .= '        });';
    $Layer .= $a_MapName.'.addLayer(pois);';
    return $Layer; 
  } 

/// discs
   function addDiscs($centerListArray,$radiusListArray,$centerOpacityListArray,$centerColorListArray,
                     $borderWidthListArray,$borderColorListArray,$borderOpacityListArray,$fillColorListArray,$fillOpacityListArray,$a_MapName) {

   $layer ='var discLayer = new OpenLayers.Layer.Vector("Disc Layer");';

   for($i=0;$i<sizeof($centerListArray);$i++){
    // $centerListArray[$i] = lon lat -> lon,lat
    // only center and radius must be defined for each disc to be shown, else use first/default value (ie [0])
    $layer .= 'osm_getFeatureDiscCenter('.$a_MapName.', discLayer,'.implode(",",explode( " ", trim($centerListArray[$i]) )).', '.$radiusListArray[$i].', '.
                      ((isset($centerOpacityListArray[$i]))? $centerOpacityListArray[$i] : $centerOpacityListArray[0]).', "'.
                      ((isset($centerColorListArray[$i]))?   $centerColorListArray[$i]   : $centerColorListArray[0]).'", '.
                      ((isset($borderWidthListArray[$i]))?   $borderWidthListArray[$i]   : $borderWidthListArray[0]).', "'.
                      ((isset($borderColorListArray[$i]))?   $borderColorListArray[$i]   : $borderColorListArray[0]).'", '.
                      ((isset($borderOpacityListArray[$i]))? $borderOpacityListArray[$i] : $borderOpacityListArray[0]).', "'.
                      ((isset($fillColorListArray[$i]))?     $fillColorListArray[$i]     : $fillColorListArray[0]).'", '.
                      ((isset($fillOpacityListArray[$i]))?   $fillOpacityListArray[$i]   : $fillOpacityListArray[0]).');';
    }
    $layer.=' '.$a_MapName.'.addLayer(discLayer);';
    return $layer;
   }
 /// end discs 
// lines ++
   function addLines($PointListArray,$a_LineColor,$a_LineWidth, $a_MapName)  
   {   
     $layer = '';
     $layer.='var lineLayer = new OpenLayers.Layer.Vector("Line Layer");';
     $layer.='var Points = new Array();';
     $layer.='var lineWidth = '.$a_LineWidth.';';
     $layer.='var lineColor = "'.$a_LineColor.'";';

     for($i=0;$i<sizeof($PointListArray);$i++){
       $layer.='Points['.$i.'] = new Object();';
       $layer.='Points['.$i.']["lon"] = '.$PointListArray[$i][lon].';';
       $layer.='Points['.$i.']["lat"] = '.$PointListArray[$i][lat].';';
     }
     $layer.=' osm_setLinePoints('.$a_MapName.', lineLayer, lineWidth, lineColor, 0.7, Points);';
     $layer.=' '.$a_MapName.'.addLayer(lineLayer);';

     return $layer;
   }
// //lines --

  function setMapCenterAndZoom($a_MapName, $a_lat, $a_lon, $a_zoom)
  {
    Osm::traceText(DEBUG_INFO, "setMapCenterAndZoom(".$a_lat.",".$a_lon.",".$a_zoom.")");
    $Layer = '';

    if (strtolower($a_zoom) == ('auto')){
      $a_zoom = 'null';
    }
    if ((strtolower($a_lat) == ('auto')) || (strtolower($a_lon) == ('auto'))) {
      $Layer .= 'var lonLat = null;';
    }
    else {
      $Layer .= 'var lonLat = new OpenLayers.LonLat('.$a_lon.','.$a_lat.').transform('.$a_MapName.'.displayProjection, '.$a_MapName.'.projection);';
    }
    
    $Layer .= ''.$a_MapName.'.setCenter (lonLat,'.$a_zoom.');'; // Zoomstufe einstellen
    return $Layer;
  } 


  function setGoogleMapCenterAndZoom($a_MapName, $a_lat, $a_lon, $a_zoom)
  {
    Osm::traceText(DEBUG_INFO, "setGoogleMapCenterAndZoom(".$a_lat.",".$a_lon.",".$a_zoom.")");
    $Layer = '';

    if (strtolower($a_zoom) == ('auto')){
      $a_zoom = 'null';
    }
    if ((strtolower($a_lat) == ('auto')) || (strtolower($a_lon) == ('auto'))) {
      $Layer .= 'var lonLat = null;';
    }
    else {
      $Layer .= 'var lonLat = new OpenLayers.LonLat('.$a_lon.','.$a_lat.').transform("EPSG:4326", '.$a_MapName.'.projection);';
    }
    
    $Layer .= ''.$a_MapName.'.setCenter (lonLat,'.$a_zoom.');'; // Zoomstufe einstellen
    return $Layer;
  } 
      
  // check the map-type, remove whit space and replace Osnmarender
  function checkMapType($a_type){
    // Osmarender is replaced by Mapnik
    if ($a_type == 'Osmarender'){
      return "Mapnik";
    }
    if ($a_type != 'Mapnik' && $a_type != 'mapnik_ssl' && $a_type != 'CycleMap' && $a_type != 'OpenSeaMap' && $a_type != 'stamen_watercolor' && $a_type != 'stamen_toner' && $a_type != 'OpenWeatherMap' && $a_type != 'basemap_at' && $a_type != 'Google' && $a_type != 'All' && $a_type != 'AllGoogle' && $a_type != 'AllOsm' && $a_type != 'ext' && $a_type != 'GooglePhysical' && $a_type != 'GoogleStreet' && $a_type != 'GoogleHybrid' && $a_type != 'GoogleSatellite' && $a_type != 'Google Physical' && $a_type != 'Google Street' && $a_type != 'Google Hybrid' && $a_type != 'Google Satellite'&& $a_type != 'Ext'){
      return "AllOsm";
    }
    // eg "Google Hybrid" => "GoogleHybrid"
    $type = preg_replace('/\s+/', '', $a_type);
    return $type;
  }

  // check the num of zoomlevels
  function checkOverviewMapZoomlevels($a_Zoomlevels){
    if ( $a_Zoomlevels > 17){
      Osm::traceText(DEBUG_ERROR, "Zoomlevel out of range!");
      return 0;
    }
    return $a_Zoomlevels;
  }     
  
  function checkControlType($a_MapControl){
    foreach ( $a_MapControl as $MapControl ){
	  Osm::traceText(DEBUG_INFO, "Checking the Map Control");
	  $MapControl = strtolower($MapControl);
	  if (( $MapControl != 'scaleline') && ($MapControl != 'scale') && ($MapControl != 'no') && ($MapControl != 'mouseposition') && ($MapControl != 'off')) {
	    Osm::traceText(DEBUG_ERROR, "e_invalid_control");
	    $a_MapControl[0]='No';
	  }
    }
    return $a_MapControl;
  }
  
}
?>
