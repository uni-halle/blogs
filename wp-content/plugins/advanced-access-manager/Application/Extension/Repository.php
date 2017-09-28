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
class AAM_Extension_Repository {
    
    /**
     * Extension status: installed
     * 
     * Extension has been installed and is up to date
     */
    const STATUS_INSTALLED = 'installed';
    
    /**
     * License violation
     * 
     * Extension is inactive
     */
    const STATUS_INACTIVE = 'inactive';
    
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
     * Single instance of itself
     * 
     * @var AAM_Extension_Repository
     * 
     * @access private
     * @static 
     */
    private static $_instance = null;
    
    /**
     * Extension list
     * 
     * @var array
     * 
     * @access protected 
     */
    protected $list = array();

    /**
     * Constructor
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
     * @return AAM_Extension_Repository
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
    public function load($dir = null) {
        $basedir = (is_null($dir) ? $this->getBasedir() : $dir);
        
        //since release 3.4 some extensions get intergreated into core
        AAM_Core_Compatibility::initExtensions();

        if (file_exists($basedir)) {
            //iterate through each active extension and load it
            foreach (scandir($basedir) as $extension) {
                if (!in_array($extension, array('.', '..'))) {
                    $this->bootstrapExtension($basedir . '/' . $extension);
                }
            }
            //Very important hook for cases when there is extensions dependancy.
            //For example AAM Plus Package depends on AAM Utitlities properties
            do_action('aam-post-extensions-load');
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
     * Store the license key
     * 
     * This is important to have just for the update extension purposes
     * 
     * @param stdClass $package
     * @param string   $license
     * 
     * @return void
     * 
     * @access public
     */
    public function storeLicense($package, $license) {
        //retrieve the installed list of extensions
        $list = AAM_Core_Compatibility::getLicenseList();
        
        $list[$package->id] = array(
            'license' => $license, 'expire' => $package->expire
        );
        
        //update the extension list
        AAM_Core_API::updateOption('aam-extensions', $list);
    }
    
    
    /**
     * Add new extension
     * 
     * @param blob $zip
     * 
     * @return boolean
     * @access public
     */
    public function add($zip) {
        $filepath = $this->getBasedir() . '/' . uniqid('aam_');
        $result   = false;
        
        if (file_put_contents($filepath, $zip)) { //unzip the archive
            WP_Filesystem(false, false, true); //init filesystem
            $result = unzip_file($filepath, $this->getBasedir());
            if (!is_wp_error($result)) {
                $result = true;
            }
            @unlink($filepath); //remove the working archive
        }

        return $result;
    }
    
    /**
     * Get extension version
     * 
     * @param string $id
     * 
     * @return string|null
     * 
     * @access public
     */
    public function getVersion($id) {
        return (defined($id) ? constant($id) : null);
    }
    
    /**
     * Get extension list
     * 
     * @return array
     * 
     * @access public
     */
    public function getList() {
        if (empty($this->list)) {
            $list  = require dirname(__FILE__) . '/List.php';
            $index = AAM_Core_Compatibility::getLicenseList();
            $check = AAM_Core_API::getOption('aam-check', array(), 'site');
            
            foreach ($list as $id => &$item) {
                //get premium license from the stored license index
                if (empty($item['license'])) {
                    // TODO - Fix bug with EXTENDED license
                    if (!empty($index[$id . '_EXTENDED']['license'])) {
                        $item['license'] = $index[$id . '_EXTENDED']['license'];
                        $item['expire']  = (isset($index[$id . '_EXTENDED']['expire']) ? $index[$id . '_EXTENDED']['expire'] : null);
                    } elseif (!empty($index[$id]['license'])) {
                        $item['license'] = $index[$id]['license'];
                        $item['expire']  = (isset($index[$id]['expire']) ? $index[$id]['expire'] : null);
                    } else {
                        $item['license'] = '';
                    }
                }
                
                //update extension status
                $item['status'] = $this->checkStatus($item, $check);
            }
            
            $this->list = $list;
        }
        
        return $this->list;
    }
    
    /**
     * 
     * @param type $item
     * @param type $index
     * @return type
     */
    protected function checkStatus($item, $index) {
        $id     = $item['id'];
        $status = AAM_Extension_Repository::STATUS_INSTALLED;
        
        if ($item['type'] == 'commercial') {
            $valid = !empty($item['license']);
        } else {
            $valid = true;
        }
        
        if (defined($id)) { //extension is installed
            if ($valid && isset($index->$id) 
                    && version_compare(constant($id), $index->$id->version) == -1) {
                    $status = AAM_Extension_Repository::STATUS_UPDATE;
            }
        } else {
            $status = AAM_Extension_Repository::STATUS_DOWNLOAD;
        }
        
        return $status;
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
        $error   = false;
        $basedir = $this->getBasedir();
        
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
    
    /**
     * Get base directory
     * 
     * @return string
     * 
     * @access public
     */
    public function getBasedir() {
        return AAM_Core_Config::get('extention.directory', AAM_EXTENSION_BASE);
    }

}