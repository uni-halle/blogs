<?php

/**
 * ======================================================================
 * LICENSE: This file is subject to the terms and conditions defined in *
 * file 'license.txt', which is part of this source code package.       *
 * ======================================================================
 */

/**
 * AAM Plugin Update Hook
 *
 * @package AAM
 * @author Vasyl Martyniuk <support@wpaam.com>
 * @copyright Copyright C Vasyl Martyniuk
 * @license GNU General Public License {@link http://www.gnu.org/licenses/}
 */
final class aam_Core_Update {

    /**
     * Reference to AAM
     * 
     * @var aam
     * 
     * @access private
     */
    
    private $_parent = null;
    /**
     * List of stages
     *
     * @var array
     *
     * @access private
     */
    private $_stages = array();

    /**
     * Constructoor
     *
     * @return void
     *
     * @access public
     */
    public function __construct($parent) {
        $this->_parent = $parent;
        //register update stages
        $this->_stages = apply_filters('aam_update_stages', array(
            array($this, 'downloadRepository'),
            array($this, 'flashCache'),
            array($this, 'updateFlag')
        ));
    }

    /**
     * Run the update if necessary
     *
     * @return void
     *
     * @access public
     */
    public function run() {
        foreach ($this->_stages as $stage) {
            //break the change if any stage failed
            if (call_user_func($stage) === false) {
                break;
            }
        }
    }

    /**
     * Download the Extension Repository
     *
     * This forces the system to retrieve the new set of extensions based on
     * license key
     *
     * @return boolean
     *
     * @access public
     */
    public function downloadRepository() {
        $response = true;
        if ($extensions = aam_Core_API::getBlogOption('aam_extensions')) {
            if (is_array($extensions)){
                $repo = aam_Core_Repository::getInstance($this->_parent);
                $repo->download();
            }
        }

        return $response;
    }

    /**
     * Flash all cache
     *
     * @return boolean
     *
     * @access public
     */
    public function flashCache(){
        global $wpdb;

        //clear visitor's cache first
        if (is_multisite()) {
            //get all sites first and iterate through each
            $query = 'SELECT blog_id FROM ' . $wpdb->blogs;
            $blog_list = $wpdb->get_results($query);
            if (is_array($blog_list)) {
                $query = 'DELETE FROM %s WHERE `option_name` = "aam_%s_cache"';
                foreach ($blog_list as $blog) {
                    $wpdb->query(
                        sprintf(
                            $query,
                            $wpdb->get_blog_prefix($blog->blog_id) . 'options',
                            aam_Control_Subject_Visitor::UID
                        )
                    );
                }
            }
        } else {
            $query = 'DELETE FROM ' . $wpdb->options . ' ';
            $query .= 'WHERE `option_name` = "aam_' . aam_Control_Subject_Visitor::UID . '_cache"';
            $wpdb->query($query);
        }

        //clear users cache
        $query = 'DELETE FROM ' . $wpdb->usermeta . ' ';
        $query .= 'WHERE `meta_key` = "aam_cache"';
        $wpdb->query($query);

        return true;
    }

    /**
     * Change the Update flag
     *
     * This will stop to run the update again
     *
     * @return boolean
     *
     * @access public
     */
    public function updateFlag() {
        return aam_Core_API::updateBlogOption('aam_updated', AAM_VERSION, 1);
    }

}