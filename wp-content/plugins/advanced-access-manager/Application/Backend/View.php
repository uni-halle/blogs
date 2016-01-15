<?php

/**
 * ======================================================================
 * LICENSE: This file is subject to the terms and conditions defined in *
 * file 'license.txt', which is part of this source code package.       *
 * ======================================================================
 */

/**
 * Backend view manager
 * 
 * @package AAM
 * @author Vasyl Martyniuk <vasyl@vasyltech.com>
 */
class AAM_Backend_View {

    /**
     * Instance of itself
     * 
     * @var AAM_Backend_View
     * 
     * @access private 
     */
    private static $_instance = null;

    /**
     * Current Subject
     * 
     * @var AAM_Core_Subject
     * 
     * @access private
     */
    private static $_subject = null;

    /**
     * Construct the view object
     * 
     * @return void
     * 
     * @access protected
     */
    protected function __construct() {
        $classname = 'AAM_Core_Subject_' . ucfirst(
                        AAM_Core_Request::request('subject')
        );
        if (class_exists($classname)) {
            $this->setSubject(new $classname(
                    AAM_Core_Request::request('subjectId')
            ));
        }

        //register default features
        AAM_Backend_Menu::register();
        AAM_Backend_Metabox::register();
        AAM_Backend_Capability::register();
        AAM_Backend_Post::register();
        AAM_Backend_Extension::register();
        //feature registration hook
        do_action('aam-feature-registration');
    }

    /**
     * Run the Manager
     *
     * @return string
     *
     * @access public
     */
    public function renderPage() {
        ob_start();
        require_once(dirname(__FILE__) . '/view/index.phtml');
        $content = ob_get_contents();
        ob_end_clean();

        return $content;
    }

    /**
     * Process the ajax call
     *
     * @return string
     *
     * @access public
     */
    public function processAjax() {
        $response = null;
        
        $action = AAM_Core_Request::request('sub_action');
        $parts  = explode('.', $action);
        
        if (method_exists($this, $parts[0])) {
            $response = call_user_func(array($this, $parts[0]));
        } elseif (count($parts) == 2) { //cover the Model.method pattern
            $classname = 'AAM_Backend_' . $parts[0];
            if (class_exists($classname)) {
                $object = new $classname;
                if (method_exists($object, $parts[1])) {
                    $response = call_user_func(array($object, $parts[1]));
                }
            }
        }
        
        return apply_filters(
                'aam-ajax-filter', $response, $this->getSubject(), $action
        );
    }
    
    /**
     * Render the Main Control Area
     *
     * @return void
     *
     * @access public
     */
    public function renderContent() {
        ob_start();
        require_once(dirname(__FILE__) . '/view/main-panel.phtml');
        $content = ob_get_contents();
        ob_end_clean();

        return $content;
    }
    
    /**
     * 
     * @param type $partial
     * @return type
     */
    public function loadPartial($partial) {
        ob_start();
        require_once(dirname(__FILE__) . '/view/partial/' . $partial);
        $content = ob_get_contents();
        ob_end_clean();

        return $content;
    }

    /**
     * Save AAM options
     * 
     * Important notice! This function excepts "value" to be only boolean value
     *
     * @return string
     *
     * @access public
     */
    public function save() {
        $object = AAM_Core_Request::post('object');
        $objectId = AAM_Core_Request::post('objectId', 0);
        
        $param = AAM_Core_Request::post('param');
        $value = filter_var(
                AAM_Core_Request::post('value'), FILTER_VALIDATE_BOOLEAN
        );
        
        $result = $this->getSubject()->save($param, $value, $object, $objectId);

        return json_encode(array('status' => ($result ? 'success' : 'failure')));
    }
    
    /**
     * 
     * @return type
     */
    public function confirmWelcome() {
        return json_encode(array(
            'status' => AAM_Core_API::updateOption('aam-welcome', 0)
        ));
    }
    
    /**
     * 
     * @return type
     */
    public function getErrorFixStatus() {
        $plugin = AAM_Core_Repository::getInstance()->pluginStatus('WP Error Fix');
        
        if ($plugin['status'] == 'install') {
            $response = array('status' => 'show', 'url' => $plugin['url']);
        } else {
            $response = array('status' => 'hide');
        }
        
        return json_encode($response);
    }

    /**
     * Get Subject
     * 
     * @return AAM_Core_Subject
     * 
     * @access public
     */
    public static function getSubject() {
        return self::$_subject;
    }

    /**
     * Set Subject
     * 
     * @param AAM_Core_Subject $subject
     * 
     * @return void
     * 
     * @access public
     */
    protected function setSubject(AAM_Core_Subject $subject) {
        self::$_subject = $subject;
    }

    /**
     * Get instance of itself
     * 
     * @return AAM_Backend_View
     * 
     * @access public
     */
    public static function getInstance() {
        if (is_null(self::$_instance)) {
            self::$_instance = new self;
        }

        return self::$_instance;
    }

}