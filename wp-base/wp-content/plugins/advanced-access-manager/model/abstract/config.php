<?php

/*
  Copyright (C) <2011>  Vasyl Martyniuk <martyniuk.vasyl@gmail.com>

  This program is free software: you can redistribute it and/or modify
  it under the terms of the GNU General Public License as published by
  the Free Software Foundation, either version 3 of the License, or
  (at your option) any later version.

  This program is distributed in the hope that it will be useful,
  but WITHOUT ANY WARRANTY; without even the implied warranty of
  MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
  GNU General Public License for more details.

  You should have received a copy of the GNU General Public License
  along with this program.  If not, see <http://www.gnu.org/licenses/>.

 */

/**
 * Abstract class for Advanced Access Manager Configuration Object
 *
 * Define general logic for Configuration object
 *
 * @package AAM
 * @subpackage Abstract Models
 * @author Vasyl Martyniuk <martyniuk.vasyl@gmail.com>
 * @copyrights Copyright © 2011 Vasyl Martyniuk
 * @license GNU General Public License {@link http://www.gnu.org/licenses/}
 */
abstract class mvb_Model_Abstract_Config {

    /**
     * Current Object ID
     *
     * @var int|string
     * @access protected
     */
    protected $id;

    /**
     * Object type, weather it is User or Role
     *
     * @var string
     * @access protected
     */
    protected $type = '';

    /**
     * Admin Menu config
     *
     * @var array
     * @access protected
     */
    protected $menu = array();

    /**
     * Menu Order config
     *
     * @var array
     * @access protected
     */
    protected $menu_order = array();

    /**
     * Metaboxes config
     *
     * @var array
     * @access protected
     */
    protected $metaboxes = array();

    /**
     * Capabilities list
     *
     * @var array
     * @access protected
     */
    protected $capabilities = array();

    /**
     * Post & Taxonomy restrictions
     *
     * @var array
     * @access protected
     */
    protected $restrictions = array();

    /**
     * Exclude Pages from Navigation
     *
     * @var array
     * @access protected
     */
    protected $excludes = array();

    /**
     * Initialize object
     *
     * @param int|string $id
     */
    public function __construct($id) {

        $this->ID = $id;
        //get configuration from db
        $this->getConfig();
    }

    /**
     * For custom set and get methods
     *
     * @param string $name
     * @param array $arguments
     */
    public function __call($name, $arguments) {

        $result = NULL;
        if (preg_match('/^(set|get)([\w\d]+)$/i', $name, $match)) {
            $var = strtolower(preg_replace('/\B([A-Z])/', '_$1', $match[2]));
            switch ($match[1]) {
                case 'set':
                    $this->{$var} = $arguments[0];
                    break;

                case 'get':
                    $result = (isset($this->{$var}) ? $this->{$var} : NULL);
                    break;

                default:
                    break;
            }
        }

        return $result;
    }

    /**
     * Save Configuration to database
     *
     * @access public
     */
    abstract public function saveConfig();

    /**
     * Get Configuration from database
     *
     * @access protected
     */
    abstract protected function getConfig();

    /**
     * Return current ID
     *
     * @return int|string
     */
    public function getID() {

        return $this->ID;
    }

    /**
     * Return Object Type
     *
     * @return string
     */
    public function getType() {

        return $this->type;
    }

    /**
     * Set Menu Config array
     *
     * @param array $menu
     */
    public function setMenu($menu) {

        $this->menu = (is_array($menu) ? $menu : array());
    }

    /**
     * Get Menu Config array
     *
     * @param boolean $branch
     * @return array
     */
    public function getMenu($branch = FALSE) {

        if ($branch) {
            $result = ($this->hasMenu($branch) ? $this->menu[$branch] : FALSE);
        } else {
            $result = $this->menu;
        }

        return $result;
    }

    public function hasMenu($menu) {

        return (isset($this->menu[$menu]) ? TRUE : FALSE);
    }

    public function hasSubMenu($menu, $submenu) {

        $result = FALSE;
        if ($this->hasMenu($menu)) {
            $menu = $this->menu[$menu];
            if (isset($menu['sub'][$submenu]) || isset($menu['whole'])) {
                $result = TRUE;
            }
        }

        return $result;
    }

    /**
     * Set Menu Order
     *
     * @param array $menu_order
     */
    public function setMenuOrder($menu_order) {

        $this->menu_order = (is_array($menu_order) ? $menu_order : array());
    }

    /**
     * Get Menu Order
     *
     * @return array
     */
    public function getMenuOrder() {

        return $this->menu_order;
    }

    /**
     * Set Metaboxes Config array
     *
     * @param array $metaboxes
     */
    public function setMetaboxes($metaboxes) {

        $this->metaboxes = (is_array($metaboxes) ? $metaboxes : array());
    }

    /**
     * Get Metaboxes Config Array
     *
     * @return array
     */
    public function getMetaboxes() {

        return $this->metaboxes;
    }

    /**
     * Check if metabox is set
     *
     * @param string $id
     * @return bool
     */
    public function hasMetabox($id) {

        return (isset($this->metaboxes[$id]) ? TRUE : FALSE);
    }

    /**
     * Set Capabilities
     *
     * @param array $capabilities
     */
    public function setCapabilities($capabilities) {

        $this->capabilities = (is_array($capabilities) ? $capabilities : array());
    }

    /**
     * Get Capabilities
     *
     * @return array
     */
    public function getCapabilities() {

        return $this->capabilities;
    }

    /**
     * Add New Capability
     *
     * @param string $capability
     */
    public function addCapability($capability) {

        if (!$this->hasCapability($capability)) {
            $this->capabilities[$capability] = 1;
        }
    }

    /**
     * Check if capability is present in config array
     *
     * @param string $capability
     * @return bool
     */
    public function hasCapability($capability) {

        return (isset($this->capabilities[$capability]) ? TRUE : FALSE);
    }

    /**
     * Set Restrictions
     *
     * @access public
     * @param bool $init
     */
    public function setRestrictions($restrictions) {

        $this->restrictions = $restrictions;
    }

    /**
     * Get Restrictions
     *
     * @return array
     */
    public function getRestrictions() {

        return $this->restrictions;
    }

    /**
     * Check if restriction specified
     *
     * @param string $type
     * @param int $id
     * @return bool
     */
    public function hasRestriction($type, $id) {

        return isset($this->restrictions[$type][$id]);
    }

    /**
     * Get Restriction info
     *
     * @param string $type
     * @param int $id
     * @return array
     */
    public function getRestriction($type, $id) {

        $result = NULL;

        if ($this->hasRestriction($type, $id)) {
            $result = $this->restrictions[$type][$id];
        } else { //get default restriction set
            if ($type == 'post') {
                $taxonomies = get_object_taxonomies(get_post($id));
                if (is_array($taxonomies) && count($taxonomies)) {
                    $cat_list = wp_get_object_terms(
                            $id, $taxonomies, array('fields' => 'ids')
                    );

                    if (is_array($cat_list)) {
                        $cat_list = array_reverse($cat_list);
                        foreach ($cat_list as $cat_id) {
                            if ($r = $this->getRestriction('taxonomy', $cat_id)) {
                                if (isset($r['post_in_category'])) {
                                    foreach ($r as $key => $value) {
                                        if (strpos($key, '_post_')) {
                                            $result[$key] = $value;
                                        }
                                    }
                                }
                                break;
                            }
                        }
                    }
                }
            } elseif ($type == 'taxonomy') {
                $taxonomy = mvb_Model_Helper::getTaxonomyByTerm($id);
                foreach (get_ancestors($id, $taxonomy) as $ans) {
                    if ($this->hasRestriction('taxonomy', $ans)) {
                        $result = $this->getRestriction('taxonomy', $ans);
                        break;
                    }
                }
            }

            //update restriction by configPress
            if (is_null($result)) {
                $result = $this->populateRestriction(
                        $type, $this->getType(), $this->getID()
                );
            }

            if (!empty($result)) {
                $this->addRestriction($type, $id, $result); //cache result
            }
        }

        return $result;
    }

    protected function populateRestriction($type) {

        $result = array();

        if (mvb_Model_Helper::isPremium()) {
            $result = mvb_Model_Pro::populateRestriction(
                    $type,
                    $this->getType(),
                    $this->getID()
            );
        }

        return $result;
    }

    /**
     * Update Restriction
     *
     * @param string $type
     * @param int $id
     * @param array $data
     */
    public function updateRestriction($type, $id, $data) {

        if (!$this->hasRestriction($type, $id)) {
            $this->addRestriction($type, $id, $data);
        } else {
            $this->restrictions[$type][$id] = $data;
        }
    }

    /**
     * Add Restriction
     *
     * @param type $type
     * @param type $id
     * @param type $data
     */
    public function addRestriction($type, $id, $data) {

        if (!isset($this->restrictions[$type])) {
            $this->restrictions[$type] = array();
        }

        $this->restrictions[$type][$id] = $data;
    }

    /**
     * Delete Restriction
     *
     * @param string $type
     * @param int $id
     */
    public function deleteRestriction($type, $id) {

        if ($this->hasRestriction($type, $id)) {
            $rests = $this->getRestrictions();
            unset($rests[$type][$id]);
            $this->setRestrictions($rests);
        }
    }

}

?>