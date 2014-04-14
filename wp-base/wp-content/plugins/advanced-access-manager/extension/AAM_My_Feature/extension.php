<?php

/**
 * ======================================================================
 * LICENSE: This file is subject to the terms and conditions defined in *
 * file 'license.txt', which is part of this source code package.       *
 * ======================================================================
 */

/**
 * My Feature View Controller
 *
 * @package AAM
 * @author Vasyl Martyniuk <support@wpaam.com>
 * @copyright Copyright C  Vasyl Martyniuk
 * @license GNU General Public License {@link http://www.gnu.org/licenses/}
 */
class AAM_Extension_MyFeature extends AAM_Core_Extension {

    /**
     * Constructor
     *
     * @param aam $parent
     *
     * @return void
     *
     * @access public
     */
    public function __construct(aam $parent) {
        parent::__construct($parent);

        if (is_admin()) {
            $this->registerFeature();
        }
    }

    /**
     * Register feature
     *
     * @return void
     *
     * @access protected
     */
    protected function registerFeature() {
        //add feature
        $capability = aam_Core_ConfigPress::getParam(
                        'aam.feature.my_feature.capability', 'administrator'
        );

        if (current_user_can($capability)) {
            add_action('admin_print_scripts', array($this, 'printScripts'));
            add_action('admin_print_styles', array($this, 'printStyles'));
            aam_View_Collection::registerFeature((object)array(
                'uid' => 'my_feature',
                'position' => 100,
                'title' => __('My Feature', 'aam'),
                'subjects' => array(
                    aam_Control_Subject_Role::UID,
                    aam_Control_Subject_User::UID,
                    aam_Control_Subject_Visitor::UID
                ),
                'controller' => $this
            ));
        }
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
                    'aam-my-feature-admin',
                    AAM_MY_FEATURE_BASE_URL . '/my_feature.js',
                    array('aam-admin')
            );
        }
    }

    /**
     * Register stylesheets
     *
     * @return void
     *
     * @access public
     */
    public function printStyles() {
        if ($this->getParent()->isAAMScreen()) {
            wp_enqueue_style(
                    'aam-my-feature-admin',
                    AAM_MY_FEATURE_BASE_URL . '/my_feature.css'
            );
        }
    }

}