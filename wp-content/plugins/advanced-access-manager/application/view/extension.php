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
     * @var aam_Core_Repository
     *
     * @access private
     */
    private $_repository;
    
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
        $this->_repository = aam_Core_Repository::getInstance();
    }

    /**
     * Install extension
     *
     * @return string
     *
     * @access public
     */
    public function install(){
        $license = aam_Core_Request::post('license');
        $ext = aam_Core_Request::post('extension');

        if ($license && $this->getRepository()->add($ext, $license)){
            $response = array('status' => 'success');
        } else {
            $response = array(
                'status' => 'failure',
                'reasons' => $this->getRepository()->getErrors()
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
        $license = aam_Core_Request::post('license');
        $ext = aam_Core_Request::post('extension');

        if ($this->getRepository()->remove($ext, $license)){
            $response = array('status' => 'success');
        } else {
            $response = array(
                'status' => 'failure',
                'reasons' => $this->getRepository()->getErrors()
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
     * 
     * @return aam_Core_Respository
     */
    public function getRepository(){
        return $this->_repository;
    }
    
}