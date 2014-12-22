<?php

/**
 * ======================================================================
 * LICENSE: This file is subject to the terms and conditions defined in *
 * file 'license.txt', which is part of this source code package.       *
 * ======================================================================
 */

/**
 * Activity Log Controller
 *
 * @package AAM
 * @author Vasyl Martyniuk <support@wpaam.com>
 * @copyright Copyright C 2013 Vasyl Martyniuk
 * @license GNU General Public License {@link http://www.gnu.org/licenses/}
 */
class AAM_Extension_ActivityLog extends AAM_Core_Extension {

    /**
     * Current subject
     *
     * @var aam_Control_Subject
     */
    private $_subject = null;

    /**
     *
     * @param aam|aam_View_Connector $parent
     */
    public function __construct(aam $parent) {
        parent::__construct($parent);

        //include activity object
        require_once(dirname(__FILE__) . '/activity.php');

        if (is_admin()) {
            $this->registerFeature();
        }

        //define new Activity Object
        add_filter('aam_object', array($this, 'activityObject'), 10, 3);

        //login & logout hooks
        add_action('wp_login', array($this, 'login'), 10, 2);
        add_action('wp_logout', array($this, 'logout'));
    }

    /**
     * Register new UI feature
     *
     * @return void
     *
     * @access protected
     */
    protected function registerFeature() {
        $capability = aam_Core_ConfigPress::getParam(
                        'aam.feature.activity_log.capability', 'administrator'
        );

        if (current_user_can($capability)) {
            add_action('admin_print_scripts', array($this, 'printScripts'));
            add_action('admin_print_styles', array($this, 'printStyles'));
            add_filter('aam_ajax_call', array($this, 'ajax'), 10, 2);
            add_action(
                    'aam_localization_labels', array($this, 'localizationLabels')
            );

            aam_View_Collection::registerFeature((object)array(
                'uid' => 'activity_log',
                'position' => 35,
                'title' => __('Activity Log', 'aam'),
                'subjects' => array(
                    aam_Control_Subject_Role::UID, aam_Control_Subject_User::UID
                ),
                'controller' => $this
            ));
        }
    }

    /**
     * 
     */
    public function login() {
        $this->getParent()->getUser()
                ->getObject(aam_Control_Object_Activity::UID)->add(
                    time(),
                    array(
                        'action' => aam_Control_Object_Activity::ACTIVITY_LOGIN
                    )
      );
    }

    /**
     *
     */
    public function logout() {
        $user = $this->getParent()->getUser();
        $user->getObject(aam_Control_Object_Activity::UID)->add(
                time(),
                array(
                    'action' => aam_Control_Object_Activity::ACTIVITY_LOGOUT
        ));
    }

    /**
     *
     * @param aam_Control_Object_Activity $object
     * @param type $object_uid
     * @param type $object_id
     * @return \aam_Control_Object_Activity
     */
    public function activityObject($object, $object_uid, $object_id) {
        if ($object_uid === aam_Control_Object_Activity::UID) {
            $object = new aam_Control_Object_Activity(
                    $this->getParent()->getUser(), $object_id
            );
        }

        return $object;
    }

    /**
     *
     * @return type
     */
    public function content() {
        ob_start();
        require dirname(__FILE__) . '/ui.phtml';
        $content = ob_get_contents();
        ob_end_clean();

        return $content;
    }

    /**
     * Print necessary scripts
     *
     * @return void
     *
     * @access public
     */
    public function printScripts() {
        if ($this->getParent()->isAAMScreen()) {
            wp_enqueue_script(
                    'aam-activity-log-admin',
                    AAM_ACTIVITY_LOG_BASE_URL . '/activity.js',
                    array('aam-admin')
            );
        }
    }

    /**
     *
     */
    public function printStyles() {
        if ($this->getParent()->isAAMScreen()) {
            wp_enqueue_style(
                    'aam-activity-log-admin',
                    AAM_ACTIVITY_LOG_BASE_URL . '/activity.css'
            );
        }
    }

    /**
     * Add extra UI labels
     *
     * @param array $labels
     *
     * @return array
     *
     * @access public
     */
    public function localizationLabels($labels) {
        $labels['Clear Logs'] = __('Clear Logs', 'aam');
        $labels['Get More'] = __('Get More', 'aam');

        return $labels;
    }

    /**
     * Hanlde Ajax call
     *
     * @param mixed $default
     * @param aam_Control_Subject $subject
     *
     * @return mixed
     *
     * @access public
     */
    public function ajax($default, aam_Control_Subject $subject = null) {
        $this->setSubject($subject);

        switch (aam_Core_Request::request('sub_action')) {
            case 'activity_list':
                $response = $this->getActivityList();
                break;

            case 'clear_activities':
                $response = $this->clearActivities();
                break;

            default:
                $response = $default;
                break;
        }

        return $response;
    }

    /**
     *
     * @return type
     */
    protected function getActivityList() {
        $response = array(
            'iTotalRecords' => 0,
            'iTotalDisplayRecords' => 0,
            'sEcho' => aam_Core_Request::request('sEcho'),
            'aaData' => array(),
        );

        $activity = $this->getSubject()->getObject(aam_Control_Object_Activity::UID);
        $activities = $activity->getOption();

        foreach ($activities as $user_id => $list) {
            $user = new WP_User($user_id);
            if ($user->ID && is_array($list)) {
                foreach ($list as $time => $data) {
                    $response['aaData'][] = array(
                        $user->ID,
                        ($user->display_name ? $user->display_name : $user->user_nicename),
                        $activity->decorate($data),
                        date('Y-m-d H:i:s', $time)
                    );
                }
            }
        }

        return json_encode($response);
    }

    /**
     * Clear the activities
     *
     * @global wpdb $wpdb
     *
     * @return string
     *
     * @access public
     */
    protected function clearActivities() {
        $activity = $this->getSubject()->getObject(aam_Control_Object_Activity::UID);
        foreach ($activity->getOption() as $user_id => $list) {
            delete_user_option($user_id, 'aam_activity');
        }

        return json_encode(array('status' => 'success'));
    }

    /**
     *
     * @param aam_Control_Subject $subject
     */
    public function setSubject($subject) {
        $this->_subject = $subject;
    }

    /**
     *
     * @return aam_Control_Subject
     */
    public function getSubject() {
        return $this->_subject;
    }

}