<?php

/**
 * ======================================================================
 * LICENSE: This file is subject to the terms and conditions defined in *
 * file 'license.txt', which is part of this source code package.       *
 * ======================================================================
 */

/**
 * Extension UI controller
 * 
 * @package AAM
 * @author Vasyl Martyniuk <support@wpaam.com>
 * @copyright Copyright C Vasyl Martyniuk
 * @license GNU General Public License {@link http://www.gnu.org/licenses/}
 */
class aam_View_Extension extends aam_View_Abstract {

    /**
     * Extensions Repository
     *
     * @var array
     *
     * @access private
     */
    private $_repository = array();
    
    /**
     * Constructor
     *
     * The filter "aam_cpanel" can be used to control the Control Panel items.
     *
     * @return void
     *
     * @access public
     */
    public function __construct() {
        parent::__construct();

        //get repository
        $repository = aam_Core_API::getBlogOption('aam_extensions', array(), 1);
        if (is_array($repository)){
            $this->_repository = $repository;
        }
    }

    /**
     * Install extension
     *
     * @return string
     *
     * @access public
     */
    public function install(){
        $repo = new aam_Core_Repository;
        $license = aam_Core_Request::post('license');
        $ext = aam_Core_Request::post('extension');

        if ($license && $repo->add($ext, $license)){
            $response = array('status' => 'success');
        } else {
            $response = array(
                'status' => 'failure',
                'reasons' => $repo->getErrors()
            );
        }

        return json_encode($response);
    }

    /**
     * Remove extension
     *
     * @return string
     *
     * @access public
     */
    public function remove(){
        $repo = new aam_Core_Repository;
        $license = aam_Core_Request::post('license');
        $ext = aam_Core_Request::post('extension');

        if ($repo && $repo->remove($ext, $license)){
            $response = array('status' => 'success');
        } else {
            $response = array(
                'status' => 'failure',
                'reasons' => $repo->getErrors()
            );
        }

        return json_encode($response);
    }

    /**
     * Run the Manager
     *
     * @return string
     *
     * @access public
     */
    public function run() {
        //check if plugins/advanced-access-manager/extension is writable
        if (!is_writable(AAM_BASE_DIR . 'extension')){
            aam_Core_Console::add(__(
                    'Folder advanced-access-manager/extension is not writable', 'aam'
            ));
        }
        
        return $this->loadTemplate(dirname(__FILE__) . '/tmpl/extension.phtml');
    }
    
    /**
     * Check if extensions exists
     *
     * @param string $extension
     *
     * @return boolean
     *
     * @access public
     */
    public function hasExtension($extension){
        return (isset($this->_repository[$extension]) ? true : false);
    }

    /**
     * Get Extension
     *
     * @param string $extension
     *
     * @return stdClass
     *
     * @access public
     */
    public function getExtension($extension){
        return ($this->hasExtension($extension) ? $this->_repository[$extension] : new stdClass);
    }

}