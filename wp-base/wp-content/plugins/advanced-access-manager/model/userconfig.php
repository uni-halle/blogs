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
 * User Config Model Class
 *
 * User Config Object
 *
 * @package AAM
 * @subpackage Models
 * @author Vasyl Martyniuk <martyniuk.vasyl@gmail.com>
 * @copyrights Copyright © 2011 Vasyl Martyniuk
 * @license GNU General Public License {@link http://www.gnu.org/licenses/}
 */
class mvb_Model_UserConfig extends mvb_Model_Abstract_Config {

    /**
     * User's Object
     *
     * @var mvb_Model_User
     * @access protected
     */
    protected $user;

    /**
     * {@inheritdoc}
     */
    protected $type = 'user';

    /**
     * {@inheritdoc }
     */
    public function __construct($id) {

        $this->user = new mvb_Model_User($id);
        parent::__construct($id);
    }

    /**
     * {@inheritdoc }
     */
    public function saveConfig() {

        $options = (object) array(
                    'menu' => $this->getMenu(),
                    'metaboxes' => $this->getMetaboxes(),
                    'capabilities' => $this->getCapabilities(),
                    'menu_order' => $this->getMenuOrder(),
                    'restrictions' => $this->getRestrictions(),
        );

        update_user_meta($this->getID(), WPACCESS_PREFIX . 'config', $options);

        mvb_Model_Cache::clearCache();

        do_action(WPACCESS_PREFIX . 'do_save');
    }

    /**
     * {@inheritdoc }
     */
    protected function getConfig() {

        $config = get_user_meta($this->getID(), WPACCESS_PREFIX . 'config', TRUE);
        if ($config) {
            $this->setMenu($config->menu);
            $this->setMenuOrder($config->menu_order);
            $this->setMetaboxes($config->metaboxes);
            $this->setRestrictions($config->restrictions);
            $this->setCapabilities($config->capabilities);
        }else{
            $this->setCapabilities($this->user->getAllCaps());
        }
    }

    /**
     * Return current User Object
     *
     * @return mvb_Model_User
     */
    public function getUser() {

        return $this->user;
    }

}

?>