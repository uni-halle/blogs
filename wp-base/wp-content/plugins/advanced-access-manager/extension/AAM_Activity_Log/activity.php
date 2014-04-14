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
class aam_Control_Object_Activity extends aam_Control_Object {

    /**
     * Control Object UID
     */
    const UID = 'activity';

    /**
     * Activity User Login
     */
    const ACTIVITY_LOGIN = 'login';

    /**
     * Activity User Logout
     */
    const ACTIVITY_LOGOUT = 'logout';

    /**
     * Set of Activities
     *
     * @var array
     *
     * @access private
     */
    private $_option = array();

    /**
     * Initialize the Activity list
     *
     * Based on subject type, load the list of activities
     *
     * @param int $object_id
     *
     * @return void
     *
     * @access public
     */
    public function init($object_id) {
        if ($this->getSubject()->getUID() == aam_Control_Subject_User::UID) {
            //get single user activity list
            $option = array(
                $this->getSubject()->getId() => $this->getSubject()->readOption(
                        self::UID, $object_id, false
            ));
        } else {
            //get all users in Role and combine the activities
            $query = new WP_User_Query(array(
                'number' => '',
                'blog_id' => get_current_blog_id(),
                'role' => $this->getSubject()->getId(),
                'fields' => 'id'
            ));
            $option = array();

            foreach ($query->get_results() as $user) {
                $dump = get_user_option('aam_activity', $user);
                if (is_array($dump) && count($dump)) {
                    $option[$user] = $dump;
                }
            }
        }

        if (is_array($option)) {
            $this->setOption($option);
            //filter old activities
            $this->filter();
        }
    }

    /**
     * Decorate Activity description
     *
     * @param array $activity
     *
     * @return string
     *
     * @access public
     */
    public function decorate($activity) {
        switch ($activity['action']) {
            case self::ACTIVITY_LOGIN:
                $response = __('System Login', 'aam');
                break;

            case self::ACTIVITY_LOGOUT:
                $response = __('System Logout', 'aam');
                break;

            default:
                $response = apply_filters(
                        'aam_activity_decorator',
                        __('Unknown Activity', 'aam'),
                        $activity
                );
                break;
        }

        return $response;
    }

    /**
     * Add User's Activity
     *
     * This method can be used only for Subject User
     *
     * @param int   $timestamp
     * @param array $activity
     *
     * @return void
     *
     * @access public
     */
    public function add($timestamp, array $activity) {
        //make sure that user's activity is array
        $user_id = $this->getSubject()->getId();
        if (empty($this->_option[$user_id]) || !is_array($this->_option[$user_id])) {
            $this->_option[$user_id] = array();
        }
        //add activity
        $this->_option[$user_id][$timestamp] = $activity;

        //finally save the activity
        $this->save($this->_option[$user_id]);
    }

    /**
     * Filter old activities
     *
     * Based on aam.extension.AAM_Activity_Log.date config, filter old activities
     *
     * @return void
     *
     * @access public
     */
    public function filter() {
        $date = strtotime(
                aam_Core_ConfigPress::getParam(
                        'aam.extension.AAM_Activity_Log.date', 'today - 30 days'
                )
        );
        foreach ($this->_option as $user_id => $activities) {
            if (is_array($activities)) {
                foreach ($activities as $timestamp => $activity) {
                    if ($timestamp < $date) {
                        unset($this->_option[$user_id][$timestamp]);
                    }
                }
            }
        }
    }

    /**
     * Save Activities
     *
     * @param array $events
     *
     * @return void
     *
     * @access public
     */
    public function save($activities = null) {
        if (is_array($activities)) {
            $this->getSubject()->updateOption($activities, self::UID);
        }
    }

    /**
     * @inheritdoc
     */
    public function cacheObject() {
        return false;
    }

    /**
     *
     * @return type
     */
    public function getUID() {
        return self::UID;
    }

    /**
     *
     * @param type $option
     */
    public function setOption($option) {
        $this->_option = (is_array($option) ? $option : array());
    }

    /**
     *
     * @return type
     */
    public function getOption() {
        return $this->_option;
    }

}