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
 * @author Vasyl Martyniuk <vasyl@vasyltech.com>
 */
class AAM_Core_Repository {
    
    /**
     * Extension status: installed
     * 
     * Extension has been installed and is up to date
     */
    const STATUS_INSTALLED = 'installed';
    
    /**
     * Extension status: download
     * 
     * Extension is not installed and either needs to be purchased or 
     * downloaded for free.
     */
    const STATUS_DOWNLOAD = 'download';
    
    /**
     * Extension status: update
     * 
     * New version of the extension has been detected.
     */
    const STATUS_UPDATE = 'update';

    /**
     * Relative path to extension directory
     */
    const RELPATH = '/aam/extension';

    /**
     * Single instance of itself
     * 
     * @var AAM_Core_Repository
     * 
     * @access private
     * @static 
     */
    private static $_instance = null;

    /**
     * Consturctor
     *
     * @return void
     *
     * @access protected
     */
    protected function __construct() {}

    /**
     * Get single instance of itself
     * 
     * @param AAM $parent
     * 
     * @return AAM_Core_Repository
     * 
     * @access public
     * @static
     */
    public static function getInstance() {
        if (is_null(self::$_instance)) {
            self::$_instance = new self;
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
        $basedir = WP_CONTENT_DIR . self::RELPATH;

        if (file_exists($basedir)) {
            //iterate through each active extension and load it
            foreach (scandir($basedir) as $module) {
                if (!in_array($module, array('.', '..'))) {
                    $this->bootstrapExtension($basedir . '/' . $module);
                }
            }
        }
    }

    /**
     * Bootstrap the Extension
     *
     * In case of any errors, the output can be found in console
     *
     * @param string $path
     *
     * @return void
     * @access protected
     */
    protected function bootstrapExtension($path) {
        $bootstrap = "{$path}/bootstrap.php";

        if (file_exists($bootstrap)) { //bootstrap the extension
            require($bootstrap);
        }
    }

    /**
     * Add new extension
     * 
     * @param blob $content
     * 
     * @return boolean|WP_Error
     * @access public
     * @global type $wp_filesystem
     */
    public function addExtension($content) {
        $filepath  = WP_CONTENT_DIR . self::RELPATH . '/' . uniqid('aam_');
        
        $response = file_put_contents($filepath, $content);
        if (!is_wp_error($response)) { //unzip the archive
            WP_Filesystem(false, false, true); //init filesystem
            $response = unzip_file($filepath, WP_CONTENT_DIR . self::RELPATH);
            if (!is_wp_error($response)) {
                $response = true;
            }
            @unlink($filepath); //remove the working archive
        }

        return $response;
    }
    
    /**
     * Check extension status
     * 
     * The list of extensions is comming from the external server. This list is
     * updated daily by the registered cron-job.
     * Each extension is following by next naming convension and stardard - the 
     * title of an extension contains only latin letters and spaces and name is 
     * no longer than 50 characters. As a standard, each extension defines the 
     * global contant that indicates an extension version. The name of the 
     * contants derives from the extension title by transforming all letters to 
     * upper case and replacing the white spaces with underscore "_" 
     * (e.g AAM Plus Package defines the contant AAM_PLUS_PACKAGE). 
     * 
     * @param string $title
     * 
     * @return string
     * 
     * @access public
     */
    public function extensionStatus($title) {
        static $cache = null;
        
        $status = self::STATUS_INSTALLED;
        $const = str_replace(' ', '_', strtoupper($title));
        
        if (is_null($cache)) {
            $cache = $this->prepareExtensionCache();
        }
        
        if (!defined($const)) { //extension does not exist
            $status = self::STATUS_DOWNLOAD;
        } elseif (!empty($cache[$title])) {
            $ver = constant($const);
            //Check if there is a version mismatch. Also ignore if there is no 
            //license stored for this extension
            if ($ver != $cache[$title]->version && !empty($cache[$title]->license)) { 
                $status = self::STATUS_UPDATE;
            }
        }
        
        return $status;
    }
    
    /**
     * 
     * @return type
     */
    protected function prepareExtensionCache() {
        $list = AAM_Core_API::getOption('aam-extension-list', array());
        $licenses = AAM_Core_API::getOption('aam-extension-license', array());

        $cache = array();
        foreach ($list as $row) {
            $cache[$row->title] = $row;
            if (isset($licenses[$row->title])) {
                $cache[$row->title]->license = $licenses[$row->title];
            }
        }
        
        return $cache;
    }

    /**
     * Check extension directory
     * 
     * @return boolean|sstring
     * 
     * @access public
     * 
     * @global type $wp_filesystem
     */
    public function checkDirectory() {
        $error = false;

        //create a directory if does not exist
        $basedir = WP_CONTENT_DIR . self::RELPATH;
        if (!file_exists($basedir)) {
            if (!@mkdir($basedir, fileperms(ABSPATH) & 0777 | 0755, true)) {
                $error = sprintf(__('Failed to create %s', AAM_KEY), $basedir);
            }
        } elseif (!is_writable($basedir)) {
            $error = sprintf(
                    __('Directory %s is not writable', AAM_KEY), $basedir
            );
        }

        return $error;
    }

}