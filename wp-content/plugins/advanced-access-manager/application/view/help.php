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
class aam_View_Help extends aam_View_Abstract {

    /**
     * Get View content
     * 
     * @return string
     * 
     * @access public
     */
    public function content($screen) {
        $basedir = dirname(__FILE__) . '/tmpl/';
        $screen->add_help_tab(array(
            'id' => 'faq',
            'title' => 'FAQ',
            'content' => $this->loadTemplate($basedir . 'help_faq.phtml')
        ));
        //add overview tab
        $screen->add_help_tab(array(
            'id' => 'overview',
            'title' => 'Overview',
            'content' => $this->loadTemplate($basedir . 'help_overview.phtml')
        ));
        $screen->add_help_tab(array(
            'id' => 'extensions',
            'title' => 'Extensions',
            'content' => $this->loadTemplate($basedir . 'help_extensions.phtml')
        ));
        $screen->add_help_tab(array(
            'id' => 'developers',
            'title' => 'Developers',
            'content' => $this->loadTemplate($basedir . 'help_developers.phtml')
        ));
    }

}
