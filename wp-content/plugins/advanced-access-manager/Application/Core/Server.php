<?php

/**
 * ======================================================================
 * LICENSE: This file is subject to the terms and conditions defined in *
 * file 'license.txt', which is part of this source code package.       *
 * ======================================================================
 */

/**
 * AAM server
 * 
 * Connection to the external AAM server.
 * 
 * @package AAM
 * @author Vasyl Martyniuk <vasyl@vasyltech.com>
 */
final class AAM_Core_Server {

    /**
     * Server endpoint
     */
    const SERVER_URL = 'http://rest.vasyltech.com/v1';

    /**
     * Fetch the extension list
     * 
     * Fetch the extension list with versions from the server
     * 
     * @return array
     * 
     * @access public
     */
    public static function check() {
        $domain   = parse_url(site_url(), PHP_URL_HOST);
        
        //prepare check params
        $params = array('domain' => $domain, 'extensions' => array());
        
        //add list of all premium installed extensions
        foreach(AAM_Core_Repository::getInstance()->getExtensionList() as $ext) {
            if ($ext->status !== AAM_Core_Repository::STATUS_DOWNLOAD 
                    && ($ext->price !== 'Free')) {
                $params['extensions'][$ext->title] = (isset($ext->license) ? $ext->license : null);
            }
        }
        
        $response = self::send(self::SERVER_URL . '/check', $params);
        $result   = array();
        
        if (!is_wp_error($response)) {
            //WP Error Fix bug report
            if ($response->error !== true && !empty($response->products)) {
                $result = $response->products;
            }
        }

        return $result;
    }

    /**
     * Download the extension
     * 
     * @param string $license
     * 
     * @return base64|WP_Error
     * 
     * @access public
     */
    public static function download($license) {
        $host = parse_url(site_url(), PHP_URL_HOST);

        $response = self::send(
                self::SERVER_URL . '/download', 
                array('license' => $license, 'domain' => $host)
        );
        
        if (!is_wp_error($response)) {
            if ($response->error === true) {
                $result = new WP_Error($response->code, $response->message);
            } else {
                $result = $response;
            }
        } else {
            $result = $response;
        }

        return $result;
    }

    /**
     * Send request
     * 
     * @param string $request
     * 
     * @return stdClass|WP_Error
     * 
     * @access protected
     */
    protected static function send($request, $params) {
        $response = AAM_Core_API::cURL($request, false, $params);

        if (!is_wp_error($response)) {
            $response = json_decode($response['body']);
        }

        return $response;
    }

}