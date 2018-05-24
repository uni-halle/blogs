<?php
/**
 * This file is part of mundschenk-at/wp-data-storage.
 *
 * Copyright 2017-2018 Peter Putzer.
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
 * Implements an inteface to the WordPress Object Cache.
 *
 * @since 1.0.0
 *
 * @author Peter Putzer <github@mundschenk.at>
 */
class Cache extends Abstract_Cache {

	/**
	 * The incrementor cache key.
	 *
	 * @var string
	 */
	private $incrementor_key;

	/**
	 * The cache group.
	 *
	 * @var string
	 */
	private $group;

	/**
	 * Create new cache instance.
	 *
	 * @param string      $prefix The prefix automatically added to cache keys.
	 * @param string|null $group  Optional. The cache group. Defaults to $prefix.
	 */
	public function __construct( $prefix, $group = null ) {

		$this->group           = ! isset( $group ) ? $prefix : $group;
		$this->incrementor_key = "{$prefix}cache_incrementor";
		$this->incrementor     = (int) \wp_cache_get( $this->incrementor_key, $this->group );

		parent::__construct( $prefix );
	}

	/**
	 * Invalidate all cached elements by reseting the incrementor.
	 */
	public function invalidate() {
		$this->incrementor = time();
		\wp_cache_set( $this->incrementor_key, $this->incrementor, $this->group, 0 );
	}

	/**
	 * Retrieves a cached value.
	 *
	 * @param string    $key   The cache key.
	 * @param bool|null $found Optional. Whether the key was found in the cache. Disambiguates a return of false as a storable value. Passed by reference. Default null.
	 *
	 * @return mixed
	 */
	public function get( $key, &$found = null ) {
		return \wp_cache_get( $this->get_key( $key ), $this->group, false, $found );
	}

	/**
	 * Sets an entry in the cache and stores the key.
	 *
	 * @param string $key       The cache key.
	 * @param mixed  $value     The value to store.
	 * @param int    $duration  Optional. The duration in seconds. Default 0 (no expiration).
	 *
	 * @return bool True if the cache could be set successfully.
	 */
	public function set( $key, $value, $duration = 0 ) {
		return \wp_cache_set( $this->get_key( $key ), $value, $this->group, $duration );
	}

	/**
	 * Deletes an entry from the cache.
	 *
	 * @param string $key The cache key root.
	 *
	 * @return bool True on successful removal, false on failure.
	 */
	public function delete( $key ) {
		return \wp_cache_delete( $this->get_key( $key ), $this->group );
	}
}
