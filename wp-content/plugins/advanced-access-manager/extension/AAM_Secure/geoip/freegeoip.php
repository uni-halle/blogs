<?php
/**
 * ======================================================================
 * LICENSE: This file is subject to the terms and conditions defined in *
 * file 'license.txt', which is part of this source code package.       *
 * ======================================================================
*/

require_once(dirname(__FILE__) . '/geoip.php');

class FreeGeoIP extends GeoIP {

    public static function query($ip) {
        $response = aam_Core_API::cURL('http://freegeoip.net/xml/' . $ip, false, true);
        $geodata = null;
        if ($response['status'] == 'success') {
            $data = @simplexml_load_string($response['content']);
            if ($data) {
                $geodata = (object) array(
                    'countryCode' => (string) $data->CountryCode,
                    'countryName' => (string) $data->CountryName,
                    'region' => (string) $data->RegionCode,
                    'city' => (string) $data->City,
                    'zip' => (string) $data->ZipCode
                );
            }
        }
        
        return $geodata;
    } 

}
