<?php

/**
 * ======================================================================
 * LICENSE: This file is subject to the terms and conditions defined in *
 * file 'license.txt', which is part of this source code package.       *
 * ======================================================================
 */

/**
 * Backend menu manager
 * 
 * @package AAM
 * @author Vasyl Martyniuk <vasyl@vasyltech.com>
 */
class AAM_Backend_Feature_Menu extends AAM_Backend_Feature_Abstract {

    /**
     * Reset menu settings
     * 
     * @return string
     * 
     * @access public
     */
    public function reset() {
        $object = AAM_Backend_View::getSubject()->getObject('menu');
        
        return json_encode(array(
            'status' => ($object->reset() ? 'success' : 'failure')
        ));
    }

    /**
     * Get subject's menu
     * 
     * Based on the list of capabilitis that current subject has, prepare
     * complete menu list and return it.
     * 
     * @return array
     * 
     * @access public
     * @global array  $menu
     */
    public function getMenu() {
        global $menu;
        
        $response = array();
        
        //let's create menu list with submenus
        foreach ($menu as $item) {
            if (preg_match('/^separator/', $item[2])) {
                continue; //skip separator
            }

            $submenu = $this->getSubmenu($item[2]);
            
            $allowed = AAM_Backend_View::getSubject()->hasCapability($item[1]);
            
            if ($allowed || count($submenu) > 0) {
                $response[] = array(
                    //add menu- prefix to define that this is the top level menu
                    //WordPress by default gives the same menu id to the first
                    //submenu
                    'id'         => 'menu-' . $item[2],
                    'name'       => $this->filterMenuName($item[0]),
                    'submenu'    => $submenu,
                    'capability' => $item[1]
                );
            }
        }

        return $response;
    }
    
    /**
     * @inheritdoc
     */
    public static function getAccessOption() {
        return 'feature.admin_menu.capability';
    }
    
    /**
     * @inheritdoc
     */
    public static function getTemplate() {
        return 'object/menu.phtml';
    }

    /**
     * Prepare filtered submenu
     * 
     * @param string $menu
     * 
     * @return array
     * 
     * @access protected
     * @global array  $submenu
     */
    protected function getSubmenu($menu) {
        global $submenu;
        
        $response = array();
        $subject = AAM_Backend_View::getSubject();
        
        if (isset($submenu[$menu])) {
            foreach ($submenu[$menu] as $item) {
                if ($subject->hasCapability($item[1])) {
                    $response[] = array(
                        'id'         => $item[2],
                        'name'       => $this->filterMenuName($item[0]),
                        'capability' => $item[1]
                    );
                }
            }
        }

        return $response;
    }
    
    /**
     * Filter menu name
     * 
     * Strip any HTML tags from the menu name and also remove the trailing
     * numbers in case of Plugin or Comments menu name.
     * 
     * @param string $name
     * 
     * @return string
     * 
     * @access protected
     */
    protected function filterMenuName($name) {
        $filtered = trim(strip_tags($name));
        
        return preg_replace('/([\d]+)$/', '', $filtered);
    }

    /**
     * Check if the entire menu branch is restricted
     * 
     * @param array $menu
     * 
     * @return boolean
     * 
     * @access protected
     */
    protected function hasRestrictedAll($menu) {
        $object = AAM_Backend_View::getSubject()->getObject('menu');
        $response = $object->has($menu['id']);

        foreach ($menu['submenu'] as $submenu) {
            if ($object->has($submenu['id']) === false) {
                $response = false;
                break;
            }
        }

        return $response;
    }
    
    /**
     * Check inheritance status
     * 
     * Check if menu settings are overwritten
     * 
     * @return boolean
     * 
     * @access protected
     */
    protected function isOverwritten() {
        $object = AAM_Backend_View::getSubject()->getObject('menu');
        
        return $object->isOverwritten();
    }

    /**
     * Register Menu feature
     * 
     * @return void
     * 
     * @access public
     */
    public static function register() {
        $cap = AAM_Core_Config::get(self::getAccessOption(), 'administrator');
        
        AAM_Backend_Feature::registerFeature((object) array(
            'uid'        => 'admin_menu',
            'position'   => 5,
            'title'      => __('Backend Menu', AAM_KEY),
            'capability' => $cap,
            'subjects'   => array('AAM_Core_Subject_Role', 'AAM_Core_Subject_User'),
            'view'       => __CLASS__
        ));
    }

}