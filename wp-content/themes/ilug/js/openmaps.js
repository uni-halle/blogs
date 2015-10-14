
      function initOpenMaps(lon, lat)
      {
          var map = new OpenLayers.Map("basicMap");
          lonLat = new OpenLayers.LonLat(lon, lat).transform(
            new OpenLayers.Projection("EPSG:4326"), // transform from WGS 1984
            new OpenLayers.Projection("EPSG:900913") // to Spherical Mercator Projection
          );        
        var markers = new OpenLayers.Layer.Markers( "Markers" );
        markers.addMarker(new OpenLayers.Marker(lonLat));
        map.addLayer(markers); 
        var mapnik = new OpenLayers.Layer.OSM();
        map.addLayer(mapnik);
        map.setCenter(new OpenLayers.LonLat(lon, lat).transform(
            new OpenLayers.Projection("EPSG:4326"), // transform from WGS 1984
            new OpenLayers.Projection("EPSG:900913") // to Spherical Mercator Projection
          ), 16);
      }