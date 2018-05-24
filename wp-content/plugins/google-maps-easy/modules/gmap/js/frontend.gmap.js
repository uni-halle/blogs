var g_gmlAllMaps = [];
function gmpGetMembershipGmeViewId(map, oldViewId) {
	var newViewId = oldViewId;
	if(map && map.getParam && map.getParam('membershipEnable') == '1') {
		// prepare view id
		if(map._elementId && map._elementId.id && map._elementId.id.substr) {
			var viewIdKey = 'google_map_easy_'
			,	newIdPos = map._elementId.id.substr(viewIdKey.length);
			if(newIdPos) {
				newViewId = newIdPos;
			}
		}
	}
	return newViewId;
}
jQuery(document).ready(function(){
	if(typeof(gmpAllMapsInfo) !== 'undefined' && gmpAllMapsInfo && gmpAllMapsInfo.length) {
		for(var i = 0; i < gmpAllMapsInfo.length; i++) {
			if(jQuery('#'+ gmpAllMapsInfo[i].view_html_id).size()) {
				gmpInitMapOnPage( gmpAllMapsInfo[i] );
			}
		}
	}
});
function gmpInitMapOnPage(mapData) {
	var additionalData = {
		markerGroups: typeof(mapData.marker_groups) != 'undefined' ? mapData.marker_groups : []
	}
	,	newMap = null
	,	mapMarkersIds = []
	,	markerIdToShow = gmpIsMarkerToShow()
	,	infoWndToShow = gmpIsInfoWndToShow();

	if(mapData && mapData.view_html_mbs_id) {
		// for membership Activity ajax load
		newMap = new gmpGoogleMap(mapData.view_html_mbs_id, mapData.params, additionalData);
		newMap.setParam('view_html_mbs_id', mapData.view_html_mbs_id);
		newMap.refreshWithCenter(mapData.params.center.lat(), mapData.params.center.lng(), mapData.params.zoom);
	} else {
		newMap = new gmpGoogleMap('#'+ mapData.view_html_id, mapData.params, additionalData);
	}

	// for membership Google Maps "Get original"
	if(mapData.mbs_presets == 1) {
		newMap.setParam('mbs_presets', 1);
	}

	if(mapData.markers && mapData.markers.length) {
		mapData.markers = _gmpPrepareMarkersList( mapData.markers );

		for(var i in mapData.markers) {
			mapMarkersIds.push(mapData.markers[i].id);
		}
		if(toeInArray(markerIdToShow, mapMarkersIds) == -1) {
			markerIdToShow = false;
		}
		if(toeInArray(infoWndToShow, mapMarkersIds) == -1) {
			infoWndToShow = false;
		}
		for(var j = 0; j < mapData.markers.length; j++) {
			if(markerIdToShow && mapData.markers[j].id != markerIdToShow) continue;
			if(infoWndToShow) {
				mapData.markers[j].params.show_description = mapData.markers[j].id == infoWndToShow ? '1' : '0';
			}
			var newMarker = newMap.addMarker( mapData.markers[j] );
			// We will set this only when marker info window need to be loaded
			/*newMarker.setTitle( mapData.markers[j].title, true );
			newMarker.setDescription( mapData.markers[j].description );*/
		}
		newMap.markersRefresh();
		newMap.checkMarkersParams(newMap.getAllMarkers(), markerIdToShow);
	}
	if(mapData.shapes && mapData.shapes.length) {
		mapData.shapes = _gmpPrepareShapesList( mapData.shapes );
		for(var z = 0; z < mapData.shapes.length; z++) {
			var newShape = newMap.addShape( mapData.shapes[z] );
		}
	}
	if(mapData.heatmap) {
		mapData.heatmap = _gmpPrepareHeatmapList( mapData.heatmap );
		newMap.addHeatmap( mapData.heatmap );
	}
	if(newMap.getRawMapInstance().map_display_mode == 'popup') {
		var img = jQuery('.map-preview-img-container').find('img');

		img.attr('src', gmpGetMapImgSrc(newMap));
		img.click(function(e){
			e.stopPropagation();
			var mapContainer = jQuery('#mapConElem_'+ newMap.getViewId()+ '.display_as_popup')
			,	mapContainerWidth = mapContainer.width()
			,	mapContainerHeight = mapContainer.find('.gmpMapDetailsContainer:first').height()
			,	popupContainer = mapContainer.parent('#gmpWidgetMapPopupWnd')
			,	popupWidth = ''
			,	popupHeight = ''
			,	bodyWidth = jQuery('body').width();

			if(bodyWidth < mapContainerWidth) {
				mapContainerWidth = bodyWidth - 40;
			}
			else if((bodyWidth - mapContainerWidth) < 40){
				mapContainerWidth = mapContainerWidth - (bodyWidth - mapContainerWidth + 40);
			}
			popupWidth = mapContainerWidth;
			popupHeight = mapContainerHeight;

			if(!popupContainer.hasClass('ui-dialog-content'))
				popupContainer.dialog({
					modal:    true
				,	autoOpen: false
				,	width:	popupWidth + 40
				,	height: popupHeight + 70
				});
			popupContainer.dialog('open');
			mapContainer.css({
				width: '100%'
			});
			mapContainer.show();
			newMap.refresh();
		});
		img.show();
	}
	g_gmlAllMaps.push( newMap );
}
function gmpGetMapInfoById(id) {
	if(typeof(gmpAllMapsInfo) !== 'undefined' && gmpAllMapsInfo && gmpAllMapsInfo.length) {
		id = parseInt(id);
		for(var i = 0; i < gmpAllMapsInfo.length; i++) {
			if(gmpAllMapsInfo[i].id == id) {
				return gmpAllMapsInfo[i];
			}
		}
	}
	return false;
}
function gmpGetMapInfoByViewId(viewId) {
	if(typeof(gmpAllMapsInfo) !== 'undefined' && gmpAllMapsInfo && gmpAllMapsInfo.length) {
		for(var i = 0; i < gmpAllMapsInfo.length; i++) {
			if(gmpAllMapsInfo[i].view_id == viewId) {
				return gmpAllMapsInfo[i];
			}
		}
	}
	return false;
}
function gmpGetAllMaps() {
	return g_gmlAllMaps;
}
function gmpGetMapById(id) {
	var allMaps = gmpGetAllMaps();
	for(var i = 0; i < allMaps.length; i++) {
		if(allMaps[i].getId() == id) {
			return allMaps[i];
		}
	}
	return false;
}
function gmpGetMapByViewId(viewId) {
	var allMaps = gmpGetAllMaps();
	for(var i = 0; i < allMaps.length; i++) {
		var currViewId = allMaps[i].getViewId();
		if(window.gmpGetMembershipGmeViewId) {
			currViewId = gmpGetMembershipGmeViewId(allMaps[i], currViewId);
		}
		if(currViewId == viewId) {
			return allMaps[i];
		}
	}
	return false;
}
function gmpGetMapImgSrc(map) {
	var imgSize = map._mapParams.img_width ? map._mapParams.img_width : 175;
	imgSize += 'x';
	imgSize += map._mapParams.img_height ? map._mapParams.img_height : 175;

	var reqParams = {
		center: map._mapParams.map_center.coord_x+ ','+ map._mapParams.map_center.coord_y
	,	zoom: map._mapParams.zoom
	,	size: imgSize
	,	maptype: map._mapParams.type
	,	sensor: 'false'
	,	language: map._mapParams.language
	};
	var reqStr = (GMP_DATA.isHttps ? 'https' : 'http')+ '://maps.google.com/maps/api/staticmap?'+ jQuery.param(reqParams);

	if(map._markers && map._markers.length) {
		for(var i in map._markers) {
			reqStr += '&markers=color:green|label:none|'+ map._markers[i]._markerObj.coord_x+ ','+ map._markers[i]._markerObj.coord_y;
		}
	}
	return reqStr;
}
function gmpIsMarkerToShow() {
	var markerHash = 'gmpMarker'
	,	hashParams = toeGetHashParams();
	if(hashParams) {
		for(var i in hashParams) {
			if(!hashParams[i] || typeof(hashParams[i]) !== 'string') continue;
			var pair = hashParams[i].split('=');
			if(pair[0] == markerHash)
				return parseInt(pair[1]);
		}
	}
	return false;
}
function gmpIsInfoWndToShow() {
	var markerHash = 'gmpInfoWnd'
	,	hashParams = toeGetHashParams();
	if(hashParams) {
		for(var i in hashParams) {
			if(!hashParams[i] || typeof(hashParams[i]) !== 'string') continue;
			var pair = hashParams[i].split('=');
			if(pair[0] == markerHash)
				return parseInt(pair[1]);
		}
	}
	return false;
}