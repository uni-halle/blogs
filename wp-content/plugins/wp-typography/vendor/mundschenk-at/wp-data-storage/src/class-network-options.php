<?php
/**
 * This file is part of mundschenk-at/wp-data-storage.
 *
 * Copyright 2018 Peter Putzer.
 *
 * This program is free software; you can redistribute it and/or
 * modify it under the terms of the GNU General Public License
 * as published by the Free Software Foundation; either version 2
 * of the License, or ( at your option ) any later version.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with this program; if not, write to the Free Software
 * Foundation, Inc., 51 Franklin Street, Fifth Floor, Boston, MA  02110-1301, USA.
 *
 * @package mundschenk-at/wp-data-storage/tests
 * @license http://www.gnu.org/licenses/gpl-2.0.html
 */

namespace Mundschenk\Data_Storage;

/**
 * Implements an interface to the WordPress Network Options API.
 *
 * @since 1.0.0
 *
 * @author Peter Putzer <github@mundschenk.at>
 */
class Network_Options extends Options {

	/**
	 * The network ID.
	 *
	 * @var int
	 */
	private $network_id;

	/**
	 * Create new Network Options instance.
	 *
	 * @param string   $prefix     The prefix automatically added to option names.
	 * @param int|null $network_id Optional. The network ID or null for the current network. Default null.
	 */
	public function __construct( $prefix, $network_id = null ) {
		$this->network_id = ! empty( $network_id ) ? $network_id : \get_current_network_id();

		parent::__construct( $prefix );
	}

	/**
	 * Retrieves an option value.
	 *
	 * @param string $option  The option name (without the plugin-specific prefix).
	 * @param mixed  $default Optional. Default value to return if the option does not exist. Default null.
	 * @param bool   $raw     Optional. Use the raw option name (i.e. don't call get_name). Default false.
	 *
	 * @return mixed Value set for the option.
	 */
	public function get( $option, $default = null, $raw = false ) {
		$value = \get_network_option( $this->network_id, $raw ? $option : $this->get_name( $option ), $default );

		if ( is_array( $default ) && '' === $value ) {
			$value = [];
		}

		return $value;
	}

	/**
	 * Sets or updates an option.
	 *
	 * @param string $option   The option name (without the plugin-specific prefix).
	 * @param mixed  $value    The value to store.
	 * @param bool   $autoload Optional. This value is ignored for network options,
	 *                         which are always autoloaded. Default true.
	 * @param bool   $raw      Optional. Use the raw option name (i.e. don't call get_name). Default false.
	 *
	 * @return bool False if value was not updated and true if value was updated.
	 */
	public function set( $option, $value, $autoload = true, $raw = false ) {
		return \update_network_option( $this->network_id, $raw ? $option : $this->get_name( $option ), $value );
	}

	/**
	 * Deletes an option.
	 *
	 * @param string $option The option name (without the plugin-specific prefix).
	 * @param bool   $raw    Optional. Use the raw option name (i.e. don't call get_name). Default false.
	 *
	 * @return bool True, if option is successfully deleted. False on failure.
	 */
	public function delete( $option, $raw = false ) {
		return \delete_network_option( $this->network_id, $raw ? $option : $this->get_name( $option ) );
	}
}
