<?php

/*
  Copyright (C) <2011-2013>  Vasyl Martyniuk <martyniuk.vasyl@gmail.com>

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
 * Cache Model Class
 *
 * Caching system
 *
 * @package AAM
 * @subpackage Models
 * @author Vasyl Martyniuk <martyniuk.vasyl@gmail.com>
 * @copyrights © 2011-2013 Vasyl Martyniuk
 * @license GNU General Public License {@link http://www.gnu.org/licenses/}
 */
class mvb_Model_Cache
{

    /**
     *
     * @param type $type
     * @param type $id
     * @return type
     */
    public static function getCacheData($type, $id)
    {

        $data = FALSE;
        if (self::canBeCached()) {
            $data = mvb_Model_API::getBlogOption(WPACCESS_PREFIX . $type . '_' . $id);
        }

        return $data;
    }

    /**
     * Check if object can be cached
     *
     * @return boolean
     */
    public static function canBeCached()
    {
        return (mvb_Model_ConfigPress::getOption('aam.caching', 'true') == 'true');
    }

    /**
     *
     * @param type $type
     * @param type $id
     * @param type $data
     */
    public static function saveCacheData($type, $id, $data)
    {
        if (self::canBeCached()) {
            mvb_Model_API::updateBlogOption(
                WPACCESS_PREFIX . $type . '_' . $id, $data
            );
        }
    }

    /**
     * Clear Cache
     */
    public static function clearCache()
    {
        global $wpdb;

        $query = "DELETE FROM {$wpdb->options} WHERE option_name LIKE '";
        $query .= $wpdb->prefix . WPACCESS_PREFIX . "user_%'";
        $wpdb->query($query);
        //TODO - there is some mess with cache. Should be fixed
        mvb_Model_API::clearCache();
    }

}