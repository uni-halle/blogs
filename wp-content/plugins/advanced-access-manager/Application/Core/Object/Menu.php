<?php

/**
 * ======================================================================
 * LICENSE: This file is subject to the terms and conditions defined in *
 * file 'license.txt', which is part of this source code package.       *
 * ======================================================================
 */

/**
 * Menu object
 * 
 * @package AAM
 * @author Vasyl Martyniuk <vasyl@vasyltech.com>
 */
class AAM_Core_Object_Menu extends AAM_Core_Object {

    /**
     * Constructor
     *
     * @param AAM_Core_Subject $subject
     *
     * @return void
     *
     * @access public
     */
    public function __construct(AAM_Core_Subject $subject) {
        parent::__construct($subject);
        
        $option = $this->getSubject()->readOption('menu');
        
        if (empty($option)) {
            $option = $this->getSubject()->inheritFromParent('menu');
        } else {
            $this->setOverwritten(true);
        }
        
        $this->setOption($option);
    }

    /**
     * Filter Menu List
     * 
     * Keep in mind that this funciton only filter the menu items but do not
     * restrict access to them. You have to explore roles and capabilities to
     * control the full access to menus.
     *
     * @global array $menu
     * @global array $submenu
     *
     * @return void
     *
     * @access public
     */
    public function filter() {
        global $menu, $submenu;
        
        foreach ($menu as $id => $item) {
            if ($this->has('menu-' . $item[2])) {
                unset($menu[$id]);
            }

            if (!empty($submenu[$item[2]])) {
                $this->filterSubmenu($item[2]);
            }
        }
    }

    /**
     * Filter submenu
     * 
     * @param string $parent
     * 
     * @return void
     * 
     * @access protected
     * @global array $menu
     * @global array $submenu
     */
    protected function filterSubmenu($parent) {
        global $submenu;

        foreach ($submenu[$parent] as $id => $item) {
            if ($this->has($item[2])) {
                unset($submenu[$parent][$id]);
            }
        }
    }

    /**
     * Check is menu defined
     * 
     * Check if menu defined in options based on the id
     * 
     * @param string $menu
     * 
     * @return boolean
     * 
     * @access public
     */
    public function has($menu) {
        //decode URL in case of any special characters like &amp;
        $decoded = htmlspecialchars_decode($menu);
        $options = $this->getOption();
        
        return !empty($options[$decoded]);
    }

    /**
     * @inheritdoc
     */
    public function save($menu, $granted) {
        $option = $this->getOption();
        $option[$menu] = $granted;

        return $this->getSubject()->updateOption($option, 'menu');
    }
    
    /**
     * 
     */
    public function reset() {
        return $this->getSubject()->deleteOption('menu');
    }

}