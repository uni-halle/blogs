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
     * Undocumented function
     *
     * @return void
     */
    public function save() {
       $items  = AAM_Core_Request::post('items', array());
       $status = AAM_Core_Request::post('status');

       $object = AAM_Backend_View::getSubject()->getObject('menu');

       foreach($items as $item) {
           $object->save($item, $status);
       }

       return json_encode(array('status' => 'success'));
    }

    /**
     * Get subject's menu
     * 
     * Based on the list of capabilities that current subject has, prepare
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
     * 
     * @param array $menu
     * @return array
     */
    protected function normalizeItem($menu) {
        if (strpos($menu, 'customize.php') === 0) {
            $menu = 'customize.php';
        }
        
        return $menu;
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
        
        $response  = array();
        $subject   = AAM_Backend_View::getSubject();
        $isDefault = is_a($subject, 'AAM_Core_Subject_Default');
        
        if (isset($submenu[$menu])) {
            foreach ($submenu[$menu] as $item) {
                if ($subject->hasCapability($item[1]) || $isDefault) {
                    $response[] = array(
                        'id'         => $this->normalizeItem($item[2]),
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
        if (AAM_Core_API::capabilityExists('aam_manage_admin_menu')) {
            $cap = 'aam_manage_admin_menu';
        } else {
            $cap = AAM_Core_Config::get(
                    self::getAccessOption(), AAM_Backend_View::getAAMCapability()
            );
        }
        
        AAM_Backend_Feature::registerFeature((object) array(
            'uid'        => 'admin_menu',
            'position'   => 5,
            'title'      => __('Backend Menu', AAM_KEY),
            'capability' => $cap,
            'subjects'   => array(
                'AAM_Core_Subject_Role', 
                'AAM_Core_Subject_User',
                'AAM_Core_Subject_Default'
            ),
            'option'     => 'backend-access-control',
            'view'       => __CLASS__
        ));
    }

}