<?php

/**
 * ======================================================================
 * LICENSE: This file is subject to the terms and conditions defined in *
 * file 'license.txt', which is part of this source code package.       *
 * ======================================================================
 */

/**
 * Extension Repository
 * 
 * @package AAM
 * @author Vasyl Martyniuk <support@wpaam.com>
 * @copyright Copyright C 2014 Vasyl Martyniuk
 * @license GNU General Public License {@link http://www.gnu.org/licenses/}
 */
class aam_Core_Repository {

    /**
     * Single instance of itself
     * 
     * @var aam_Core_Repository
     * 
     * @access private
     * @static 
     */
    private static $_instance = null;
    
    /**
     * Extension repository
     * 
     * @var array
     * 
     * @access private 
     */
    private $_repository = array();

    /**
     * Basedir to Extentions repository
     *
     * @var string
     *
     * @access private
     */
    private $_basedir = '';

    /**
     * Extension list cache
     * 
     * @var array
     * 
     * @access private
     */
    private $_cache = array();
    
    /**
     * Repository Errors
     * 
     * @var array
     * 
     * @access private 
     */
    private $_errors = array();

    /**
     * Main AAM class
     *
     * @var aam
     *
     * @access private
     */
    private $_parent;

    /**
     * Consturctor
     *
     * @return void
     *
     * @access protected
     */
    protected function __construct(aam $parent = null) {
        $this->setParent($parent);
        $this->_basedir = AAM_BASE_DIR . 'extension';
        //retrieve list of extensions from the database
        $repository = aam_Core_API::getBlogOption('aam_extensions', array(), 1);
        if (is_array($repository)){
            $this->_repository = $repository;
        }
    }
    
    /**
     * Get single instance of itself
     * 
     * @param aam $parent
     * 
     * @return aam_Core_Repository
     * 
     * @access public
     * @static
     */
    public static function getInstance(aam $parent = null){
        if (is_null(self::$_instance)){
            self::$_instance = new self($parent);
        }
        
        return self::$_instance;
    }

    /**
     * Load active extensions
     *
     * @return void
     *
     * @access public
     */
    public function load() {
        //iterate through each active extension and load it
        foreach (scandir($this->_basedir) as $module) {
            if (!in_array($module, array('.', '..'))) {
                $status = aam_Core_ConfigPress::getParam(
                        "aam.extension.{$module}.status"
                );
                if (strtolower($status) !== 'off'){
                    $this->bootstrapExtension($module);
                }
            }
        }
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
        return file_exists(
                $this->_basedir . '/' . $this->prepareExtFName($extension)
        );
    }
    
    /**
     * Check if license exists
     * 
     * @param string $extension
     * 
     * @return boolean
     * 
     * @access public
     */
    public function hasLicense($extension) {
        return (isset($this->_repository[$extension]) ? true : false);
    }
    
    /**
     * Get Extension info
     * 
     * @param string $ext
     * 
     * @return stdClass
     * 
     * @access public
     */
    public function getLicense($ext){
        return ($this->hasLicense($ext) ? $this->_repository[$ext]->license : '');
    }

    /**
     * Download extension from the external server
     * 
     * @return void
     * 
     * @access public
     */
    public function download() {
        $this->initFilesystem();
        $repository = aam_Core_API::getBlogOption('aam_extensions', array(), 1);

        if (is_array($repository)) {
            //get the list of extensions
            foreach ($repository as $data) {
                $this->retrieve($data->license);
            }
            aam_Core_API::updateBlogOption('aam_extensions', $repository, 1);
        }
    }

    /**
     * Add new extension to repository
     *
     * @param string $extension
     * @param string $license
     *
     * @return boolean
     *
     * @access public
     */
    public function add($extension, $license){
        $this->initFilesystem();
        $repository = aam_Core_API::getBlogOption('aam_extensions', array(), 1);

        if ($this->retrieve($license)){
            $repository[$extension] = (object) array(
                'license' => $license
            );
            aam_Core_API::updateBlogOption('aam_extensions', $repository, 1);
            $response = true;
        } else {
            $response = false;
        }

        return $response;
    }

    /**
     * Remove Extension from the repository
     *
     * @param string $extension
     *
     * @return boolean
     *
     * @access public
     */
    public function remove($extension){
        global $wp_filesystem;

        $repository = aam_Core_API::getBlogOption('aam_extensions', array(), 1);

        //if extension has been downloaded as part of dev license, it'll be
        //not present in the repository list
        if (isset($repository[$extension])){
            unset($repository[$extension]);
            aam_Core_API::updateBlogOption('aam_extensions', $repository, 1);
        }
                
        if ($this->hasExtension($extension)){
            $this->initFilesystem();
            $wp_filesystem->rmdir(
                    $this->_basedir . '/' . $this->prepareExtFName($extension), true
            );
        }

        return true;
    }
    
    /**
     * 
     * @param type $extension
     * @return type
     */
    protected function prepareExtFName($extension) {
        return str_replace(' ', '_', $extension);
    }

    /**
     * Initialize WordPress filesystem
     *
     * @return void
     *
     * @access protected
     */
    protected function initFilesystem(){
         require_once ABSPATH . 'wp-admin/includes/file.php';

        //initialize Filesystem
        WP_Filesystem();
    }

    /**
     * Retrieve extension based on license key
     *
     * @global WP_Filesystem $wp_filesystem
     * @param string $license
     *
     * @return boolean
     *
     * @access protected
     */
    protected function retrieve($license) {
        global $wp_filesystem;
        
        $url = WPAAM_REST_API . '?method=exchange&license=' . $license;
        $res = wp_remote_request($url, array('timeout' => 10));
        $response = false;
        if (!is_wp_error($res)) {
            //write zip archive to the filesystem first
            $zip = AAM_TEMP_DIR . '/' . uniqid();
            $content = base64_decode($res['body']);
            if ($content && $wp_filesystem->put_contents($zip, $content)) {
                $response = $this->insert($zip);
                $wp_filesystem->delete($zip);
            } elseif (empty($content)){
                $this->addError(__('Invalid License Key', 'aam'));
            } else {
                $this->addError(
                        __('Failed to write file to wp-content/aam folder', 'aam')
                );
            }
        } else {
            $this->addError(__('Failed to reach the WPAAM Server', 'aam'));
        }

        return $response;
    }

    /**
     *
     * @param type $zip
     * @return boolean
     */
    protected function insert($zip) {
        $response = true;
        if (is_wp_error(unzip_file($zip, $this->_basedir))) {
            $response = false;
            $this->addError(
                    __('Failed to insert extension to extension folder', 'aam')
            );
        }

        return $response;
    }

    /**
     * Bootstrap the Extension
     *
     * In case of any errors, the output can be found in console
     *
     * @param string $extension
     *
     * @return void
     *
     * @access protected
     */
    protected function bootstrapExtension($extension) {
        $bootstrap = $this->_basedir . "/{$extension}/index.php";
        if (file_exists($bootstrap) && !isset($this->_cache[$extension])) {
            //bootstrap the extension
            $this->_cache[$extension] = require_once($bootstrap);
            $this->_cache[$extension]->activate();
        }
    }

    /**
     * Set Parent class
     *
     * @param aam $parent
     *
     * @return void
     *
     * @access public
     */
    public function setParent($parent){
        $this->_parent = $parent;
    }

    /**
     * Get Parent class
     *
     * @return aam
     *
     * @access public
     */
    public function getParent(){
        return $this->_parent;
    }
    
    /**
     * Add error
     * 
     * @param string $message
     * 
     * @access public
     */
    public function addError($message){
        $this->_errors[] = $message;
    }
    
    /**
     * Get all errors
     * 
     * @return array
     * 
     * @access public
     */
    public function getErrors(){
        return $this->_errors;
    }

}