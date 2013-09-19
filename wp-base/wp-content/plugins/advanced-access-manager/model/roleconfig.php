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
 * Role Config Model Class
 *
 * Role Config Object
 *
 * @package AAM
 * @subpackage Models
 * @author Vasyl Martyniuk <martyniuk.vasyl@gmail.com>
 * @copyrights Copyright © 2011 Vasyl Martyniuk
 * @license GNU General Public License {@link http://www.gnu.org/licenses/}
 */
class mvb_Model_RoleConfig extends mvb_Model_Abstract_Config {

    /**
     * {@inheritdoc}
     */
    protected $type = 'role';

    /**
     * User Role Name
     *
     * @access protected
     * @var string
     */
    protected $name = NULL;

    /**
     * {@inheritdoc }
     */
    public function saveConfig() {

        $roles = mvb_Model_API::getRoleList(FALSE);
        if (isset($roles[$this->getID()])) {
            $roles[$this->getID()]['capabilities'] = $this->getCapabilities();
            if (!is_null($this->name)){
                $roles[$this->getID()]['name'] = $this->name;
            }
            mvb_Model_API::updateBlogOption('user_roles', $roles);
        }

        $options = (object) array(
                    'menu' => $this->getMenu(),
                    'metaboxes' => $this->getMetaboxes(),
                    'menu_order' => $this->getMenuOrder(),
                    'restrictions' => $this->getRestrictions(),
        );
        mvb_Model_API::updateBlogOption(WPACCESS_PREFIX . 'config_' . $this->getID(), $options);

        mvb_Model_Cache::clearCache();

        do_action(WPACCESS_PREFIX . 'do_save');
    }

    /**
     * {@inheritdoc }
     */
    protected function getConfig() {

        $roles = mvb_Model_API::getRoleList(FALSE); //TODO - Potensially hole
        if (isset($roles[$this->getID()]['capabilities'])) {
            $config = mvb_Model_API::getBlogOption(WPACCESS_PREFIX . 'config_' . $this->getID());
            if ($config) {
                $this->setMenu($config->menu);
                $this->setMenuOrder($config->menu_order);
                $this->setMetaboxes($config->metaboxes);
                $this->setRestrictions($config->restrictions);
            }
            $this->setCapabilities($roles[$this->getID()]['capabilities']);
        }
    }

    public function setName($name){

        $this->name = $name;
    }

}

?>