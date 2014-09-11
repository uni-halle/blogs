/**
 * ======================================================================
 * LICENSE: This file is subject to the terms and conditions defined in *
 * file 'license.txt', which is part of this source code package.       *
 * ======================================================================
 */

google.load('visualization', '1', {'packages': ['geochart']});
google.setOnLoadCallback(drawRegionsMap);

function drawRegionsMap() {
    jQuery.ajax(aamLocal.ajaxurl, {
        type: 'POST',
        dataType: 'json',
        data: {
            action: 'aam_security',
            sub_action: 'map_data',
            _ajax_nonce: aamLocal.nonce
        },
        success: function(response) {
            var list = new Array();
            list.push(['Country', 'Failed Attempts']);
            for (var i in response.list) {
                list.push(response.list[i]);
            }
            var data = google.visualization.arrayToDataTable(list);

            var options = {
                colorAxis: {colors: ['#4374e0', '#e7711c']} // orange to blue
            };
            var chart = new google.visualization.GeoChart(
                    document.getElementById('geo_map')
                    );
            chart.draw(data, options);
        },
        failure: function() {

        }
    });

}