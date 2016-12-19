<?php

/**
 * Copyright (C) <2016>  CodePinch LLC <support@codepinch.com>
 *
 * This program is free software: you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation, either version 3 of the License, or
 * (at your option) any later version.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with this program.  If not, see <http://www.gnu.org/licenses/>.
 */

/**
 * CodePinch affiliate main class
 * 
 * @author Vasyl Martyniuk <vasyl@vasyltech.com>
 */
class AAM_Backend_View_CodePinch {

    /**
     * Plugin's slug
     */
    const SLUG = 'WP Error Fix';

    /**
     * Single instance of itself
     * 
     * @var AAM_Backend_View_CodePinch 
     * 
     * @access private
     */
    private static $_instance = null;

    /**
     * Affiliate construct
     * 
     * @return void
     * 
     * @access protected
     */
    protected function __construct() {
        if (is_admin()) {
            //store Affiliate code when plugin information pop-up is opened
            add_action(
                    'install_plugins_pre_search', 
                    array($this, 'saveAffiliate')
            );
        }
    }

    /**
     * Save affiliate code
     * 
     * Store affiliate code to the database when plugin's information pop-up is
     * open.
     * 
     * @return void
     * 
     * @access public
     */
    public function saveAffiliate() {
        $affiliate = filter_input(INPUT_GET, 'affiliate');

        if ($affiliate) {
            update_option('codepinch-affiliate', $affiliate);
        }
    }

    /**
     * Bootstrap the SKD
     * 
     * The best way to initialize the CodePinch affiliate SDK is in the init
     * action so it can register the menu for CodePinch installation process.
     * 
     * @return void
     * 
     * @access public
     * @static
     */
    public static function bootstrap() {
        self::$_instance = new self;
    }

    /**
     * Get URL
     * 
     * Prepare and return CodePinch installation URL based on the passed 
     * affiliate code
     * 
     * @param string $affiliate
     * 
     * @return string
     * 
     * @access public
     */
    public static function getUrl($affiliate = null) {
        $link  = 'plugin-install.php?tab=plugin-information&';
        $link .= 's=codepinch&affiliate=' . $affiliate . '&';
        $link .= 'tab=search&type=term';

        return self_admin_url($link);
    }

    /**
     * Check plugin's status
     * 
     * Check if CodePinch is already installed
     * 
     * @return boolean
     * 
     * @access public
     * @static
     */
    public static function isInstalled() {
        return in_array(
                self::getStatus(), 
                array('latest_installed', 'update_available')
        );
    }

    /**
     * Get plugin's status
     * 
     * @return string
     * 
     * @access protected
     * @static
     */
    protected static function getStatus() {
        static $status = null;

        if (is_null($status)) {
            require_once( ABSPATH . 'wp-admin/includes/plugin-install.php' );

            $args   = array('slug' => self::SLUG);
            $plugin = plugins_api('plugin_information', $args);
            $result = install_plugin_install_status($plugin);

            $status = empty($result) ? 'failed' : $result['status'];
        }

        return $status;
    }

}