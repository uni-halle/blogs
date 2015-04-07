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

class Osm_OLJS3
{
  function addTileLayer($a_LayerName, $a_Type, $a_OverviewMapZoom, $a_MapControl, $a_ExtType, $a_ExtName, $a_ExtAddress, $a_ExtInit, $a_theme)
  {
    if ($a_Type == 'stamen_toner'){
      $output .= 'var raster = new ol.layer.Tile({';
      $output .= '  source: new ol.source.Stamen({layer: "toner"})';
      $output .= '});';
    }
    if ($a_Type == 'osm'){
      $output .= 'var raster = new ol.layer.Tile({';
      $output .= '  source: new ol.source.OSM()';
      $output .= '});';
    }
    else {
      //echo "ERROR with map_type";
      echo "** Error ** unknown a_Type ";
      echo $a_Type;
    }
  }

  function addVectorLayer($a_LayerName, $a_FileName, $a_Colour, $a_Type)
  {
    $output .= 'var style = {';
    $output .= '  "Point": [new ol.style.Style({';
    $output .= '    image: new ol.style.Circle({';
    $output .= '      fill: new ol.style.Fill({';
    $output .= '        color: "rgba(255,255,0,0.4)"';
    $output .= '      }),';
    $output .= '      radius: 5,';
    $output .= '      stroke: new ol.style.Stroke({';
    $output .= '        color: "#ff0",';
    $output .= '        width: 1';
    $output .= '      })';
    $output .= '    })';
    $output .= '  })],';
    $output .= '  "LineString": [new ol.style.Style({';
    $output .= '    stroke: new ol.style.Stroke({';
    $output .= '      color: "#f00",';
    $output .= '      width: 3';
    $output .= '    })';
    $output .= '  })],';
    $output .= '  "MultiLineString": [new ol.style.Style({';
    $output .= '    stroke: new ol.style.Stroke({';
    $output .= '      color: "#0f0",';
    $output .= '      width: 3';
    $output .= '    })';
    $output .= '  })]';
    $output .= '};';
    if ($a_Type == 'GPX'){
      $output .= 'var vector = new ol.layer.Vector({';
      $output .= '  source: new ol.source.GPX({';
      $output .= '    projection: "EPSG:3857",';
      $output .= '    url:"'.$a_FileName.'"';
      $output .= '  })';
 //   $output .= 'style: function(feature, resolution) {return style[feature.getGeometry().getType()];}';
      $output .= '});';
    }
    if ($a_Type == 'KML'){
      echo "KML Type";
      $output .= 'var vector = new ol.layer.Vector({';
      $output .= '  source: new ol.source.KML({';
      $output .= '    projection: "EPSG:3857",';
      $output .= '    url:"'.$a_FileName.'"';
      $output .= '  })';
 //   $output .= 'style: function(feature, resolution) {return style[feature.getGeometry().getType()];}';
      $output .= '});';
    }
  }
}
?>
