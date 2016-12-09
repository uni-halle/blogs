<?php

/**
 * ======================================================================
 * LICENSE: This file is subject to the terms and conditions defined in *
 * file 'license.txt', which is part of this source code package.       *
 * ======================================================================
 */

class AAM_Backend_Feature {

    /**
     * Collection of features
     *
     * @var array
     *
     * @access private
     * @static
     */
    static private $_features = array();

    /**
     * Register UI Feature
     *
     * @param stdClass $feature
     *
     * @return boolean
     *
     * @access public
     * @static
     */
    public static function registerFeature(stdClass $feature) {
        $response = false;

        if (empty($feature->capability)){
            $cap = AAM_Core_Config::get('page.capability', 'administrator');
        } else {
            $cap = $feature->capability;
        }

        if (AAM::getUser()->hasCapability($cap)) {
            self::$_features[] = $feature;
            $response = true;
        }

        return $response;
    }

    /**
     * Initiate the Controller
     *
     * @param stdClass $feature
     *
     * @return stdClass
     *
     * @access public
     * @static
     */
    public static function initView(stdClass $feature){
        if (is_string($feature->view)){
            $feature->view = new $feature->view;
        }

        return $feature;
    }

    /**
     * Retrieve list of features
     *
     * Retrieve sorted list of featured based on current subject
     *
     * @return array
     *
     * @access public
     * @static
     */
    public static function retriveList() {
        $response = array();

        $subject = AAM_Backend_View::getSubject();
        foreach (self::$_features as $feature) {
            if (in_array(get_class($subject), $feature->subjects)) {
                $response[] = self::initView($feature);
            }
        }
        usort($response, 'AAM_Backend_Feature::reorder');

        return $response;
    }

    /**
     * Order list of features or subjectes
     *
     * Reorganize the list based on "position" attribute
     *
     * @param array $features
     *
     * @return array
     *
     * @access public
     * @static
     */
    public static function reorder($feature_a, $feature_b){
        $pos_a = (empty($feature_a->position) ? 9999 : $feature_a->position);
        $pos_b = (empty($feature_b->position) ? 9999 : $feature_b->position);

        if ($pos_a == $pos_b){
            $response = 0;
        } else {
            $response = ($pos_a < $pos_b ? -1 : 1);
        }

        return $response;
    }

}