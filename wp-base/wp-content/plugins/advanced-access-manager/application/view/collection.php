<?php

/**
 * ======================================================================
 * LICENSE: This file is subject to the terms and conditions defined in *
 * file 'license.txt', which is part of this source code package.       *
 * ======================================================================
 */

/**
 * View collection
 *
 * Collection of features and subjects
 *
 * @package AAM
 * @author Vasyl Martyniuk <support@wpaam.com>
 * @copyright Copyright C Vasyl Martyniuk
 * @license GNU General Public License {@link http://www.gnu.org/licenses/}
 */
class aam_View_Collection {

    /**
     * Collection of subjects
     *
     * @var array
     *
     * @access private
     * @static
     */
    static private $_subjects = array();

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
     * Register Subject
     *
     * @param stdClass $subject
     *
     * @return boolean
     *
     * @access public
     * @static
     */
    public static function registerSubject(stdClass $subject) {
        self::$_subjects[] = $subject;

        return true;
    }

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
            $cap = aam_Core_ConfigPress::getParam(
                    'aam.default_feature_capability', 'administrator'
            );
        } else {
            $cap = $feature->capability;
        }

        if (self::accessGranted($feature->uid, $cap)) {
            self::$_features[] = $feature;
            $response = true;
        }

        return $response;
    }

    /**
     * Check if feature registered
     *
     * If feature is restricted for current user or it does not exist, this function
     * will return false result
     *
     * @param string $search
     *
     * @return boolean
     *
     * @access public
     * @static
     */
    public static function hasFeature($search){
        $found = false;
        foreach(self::$_features as $feature){
            if ($feature->uid == $search){
                $found = true;
                break;
            }
        }

        return $found;
    }

    /**
     * Check if subject is registered
     *
     * @param string $search
     *
     * @return boolean
     *
     * @access public
     * @static
     */
    public static function hasSubject($search){
        $found = false;
        foreach(self::$_subjects as $subject){
            if ($subject->uid == $search){
                $found = true;
                break;
            }
        }

        return $found;
    }

    /**
     * Get initiated feature
     *
     * Find the feature in the collection of registered features and initiate it if
     * was not initiated already
     *
     * @param string $search
     * @return boolean
     */
    public static function getFeature($search){
        $response = null;
        foreach(self::$_features as $feature){
            if ($feature->uid == $search){
                $response = self::initController($feature);
                break;
            }
        }

        return $response;
    }

    /**
     * Get initiated subject
     *
     * Find the subject in the collection of registered subjects and initiate it if
     * was not initiated already
     *
     * @param string $search
     * @return boolean
     */
    public static function getSubject($search){
        $response = null;
        foreach(self::$_subjects as $subject){
            if ($subject->uid == $search){
                $response = self::initController($subject);
                break;
            }
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
    public static function initController(stdClass $feature){
        if (is_string($feature->controller)){
            $feature->controller = new $feature->controller;
        }

        return $feature;
    }

    /**
     * Retrieve subjects
     *
     * @return array
     *
     * @access public
     * @static
     */
    public static function retrieveSubjects() {
        $subjects = self::$_subjects; //keep originally copy
        usort($subjects, 'aam_View_Collection::positionOrder');

        //initiate controller
        foreach($subjects as $subject){
            self::initController($subject);
        }

        return $subjects;
    }

    /**
     * Retrieve list of features
     *
     * Retrieve sorted list of featured based on current subject
     *
     * @param aam_Control_Subject $subject
     *
     * @return array
     *
     * @access public
     * @static
     */
    public static function retriveFeatures(aam_Control_Subject $subject) {
        $response = array();

        foreach (self::$_features as $feature) {
            if (in_array($subject->getUID(), $feature->subjects)) {
                $response[] = self::initController($feature);
            }
        }
        usort($response, 'aam_View_Collection::positionOrder');

        return $response;
    }

    /**
     * Check if current user can use feature
     *
     * Make sure that current user has enough capabilities to use feature
     *
     * @param string $feature
     * @param string $cap
     *
     * @return boolean
     *
     * @access protected
     * @static
     */
    protected static function accessGranted($feature, $cap = 'administrator') {
        $capability = aam_Core_ConfigPress::getParam(
                        "aam.feature.{$feature}.capability", $cap
        );

        return current_user_can($capability);
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
    public static function positionOrder($feature_a, $feature_b){
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