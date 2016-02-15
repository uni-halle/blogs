<?php

/**
 * ======================================================================
 * LICENSE: This file is subject to the terms and conditions defined in *
 * file 'license.txt', which is part of this source code package.       *
 * ======================================================================
 */

/**
 * Backend extension manager
 * 
 * @package AAM
 * @author Vasyl Martyniuk <vasyl@vasyltech.com>
 */
class AAM_Backend_Extension {
    
    /**
     * Get HTML content
     * 
     * @return string
     * 
     * @access public
     */
    public function getContent() {
        ob_start();
        require_once(dirname(__FILE__) . '/view/extension.phtml');
        $content = ob_get_contents();
        ob_end_clean();

        return $content;
    }
    
    /**
     * Get Product List
     * 
     * @return array
     * 
     * @access protected
     */
    protected function getProductList() {
        return require(dirname(__FILE__) . '/ProductList.php');
    }
    
    /**
     * Install an extension
     * 
     * @param string $storedLicense
     * 
     * @return string
     * 
     * @access public
     */
    public function install($storedLicense = null) {
        $repo = AAM_Core_Repository::getInstance();
        $license = AAM_Core_Request::post('license', $storedLicense);
        
        //download the extension from the server first
        $package = AAM_Core_Server::download($license);
        
        if (is_wp_error($package)) {
            $response = array(
                'status' => 'failure', 'error'  => $package->get_error_message()
            );
        }elseif ($error = $repo->checkDirectory()) {
            $response = $this->installFailureResponse($error, $package);
            $this->storeLicense($package->title, $license);
        } else { //otherwise install the extension
            $result = $repo->addExtension(base64_decode($package->content));
            if (is_wp_error($result)) {
                $response = $this->installFailureResponse(
                        $result->get_error_message(), $package
                );
            } else {
                $response = array('status' => 'success');
            }
            $this->storeLicense($package->title, $license);
        }
        
        return json_encode($response);
    }
    
    /**
     * Update the extension
     * 
     * @return string
     * 
     * @access public
     */
    public function update() {
        $extension = AAM_Core_Request::post('extension');
        
        $list = AAM_Core_API::getOption('aam-extension-license', array());
        if (isset($list[$extension])) {
            $response = $this->install($list[$extension]);
        } else {
            $response = json_encode(array(
                'status' => 'failure', 
                'error' => __('License key is missing.', AAM_KEY)
            ));
        }
        
        return $response;
    }
    
    /**
     * Install extension failure response
     * 
     * In case the filesystem fails, AAM allows to download the extension for
     * manuall installation
     * 
     * @param string   $error
     * @param stdClass $package
     * 
     * @return array
     * 
     * @access protected
     */
    protected function installFailureResponse($error, $package) {
        return array(
            'status'  => 'failure',
            'error'   => $error,
            'title'   => $package->title,
            'content' => $package->content
        );
    }
    
    /**
     * Store the license key
     * 
     * This is important to have just for the update extension purposes
     * 
     * @param string $title
     * @param string $license
     * 
     * @return void
     * 
     * @access protected
     */
    protected function storeLicense($title, $license) {
        //retrieve the installed list of extensions
        $list = AAM_Core_API::getOption('aam-extension-license', array());
        $list[$title] = $license;
        
        //update the extension list
        AAM_Core_API::updateOption('aam-extension-license', $list);
    }
    
    /**
     * Register Extension feature
     * 
     * @return void
     * 
     * @access public
     */
    public static function register() {
        AAM_Backend_Feature::registerFeature((object) array(
            'uid' => 'extension',
            'position' => 999,
            'title' => __('Extensions', AAM_KEY),
            'notification' => self::getNotification(),
            'subjects' => array(
                'AAM_Core_Subject_Role', 
                'AAM_Core_Subject_User', 
                'AAM_Core_Subject_Visitor'
            ),
            'view' => __CLASS__
        ));
    }
    
    /**
     * 
     * @return int
     */
    protected static function getNotification() {
        $list = AAM_Core_API::getOption('aam-extension-repository', array());
        $repo = AAM_Core_Repository::getInstance();
        $count = 0;
        
        //WP Error Fix bug report
        $list = (is_array($list) ? $list : array());
        
        foreach($list as $extension) {
            $status = $repo->extensionStatus($extension->title);
            if ($status == AAM_Core_Repository::STATUS_UPDATE) {
                $count++;
            }
        }
        
        return $count;
    }

}