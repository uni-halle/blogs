<?php

/**
 * ======================================================================
 * LICENSE: This file is subject to the terms and conditions defined in *
 * file 'license.txt', which is part of this source code package.       *
 * ======================================================================
 */

/**
 *
 * @package AAM
 * @author Vasyl Martyniuk <support@wpaam.com>
 * @copyright Copyright C 2013 Vasyl Martyniuk
 * @license GNU General Public License {@link http://www.gnu.org/licenses/}
 */
class aam_View_Security extends aam_View_Abstract {

    /**
     * Run the Manager
     *
     * @return string
     *
     * @access public
     */
    public function run() {
        return $this->loadTemplate(dirname(__FILE__) . '/view/security.phtml');
    }

    /**
     * 
     * @return type
     */
    public function processAjax() {
        switch (aam_Core_Request::post('sub_action')) {
            case 'map_data':
                $response = $this->getMapData();
                break;

            default:
                $response = json_encode(array('status' => 'failure'));
                break;
        }

        return $response;
    }

    protected function getMapData() {
        $stats = aam_Core_API::getBlogOption(
                            'aam_security_login_stats', array()
            );
        $list = array();
        foreach($stats as $country => $data){
            $list[] = array($country, $data['failed']);
        }
        return json_encode(
                array('list' => $list)
        );
    }

    /**
     * 
     * @return type
     */
    public function isGeoLookupOn() {
        $geo_lookup = aam_Core_ConfigPress::getParam(
                        'security.login.geo_lookup', 'false'
        );

        return ($geo_lookup == 'true' ? true : false);
    }

    /**
     * 
     * @return type
     */
    public function isLoginLockoutOn() {
        $login_lock = aam_Core_ConfigPress::getParam(
                        'security.login.lockout', 'false'
        );

        return ($login_lock == 'true' ? true : false);
    }

}
