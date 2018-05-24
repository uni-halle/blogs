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
 * Implements a generic caching mechanism for WordPress.
 *
 * @since 1.0.0
 *
 * @author Peter Putzer <github@mundschenk.at>
 */
abstract class Abstract_Cache {

	/**
	 * Incrementor for cache invalidation.
	 *
	 * @var int
	 */
	protected $incrementor;

	/**
	 * The prefix added to all keys.
	 *
	 * @var string
	 */
	private $prefix;

	/**
	 * Create new cache instance.
	 *
	 * @param string $prefix The prefix automatically added to cache keys.
	 */
	public function __construct( $prefix ) {
		$this->prefix = $prefix;

		if ( empty( $this->incrementor ) ) {
			$this->invalidate();
		}
	}

	/**
	 * Invalidate all cached elements by reseting the incrementor.
	 *
	 * @return void
	 */
	abstract public function invalidate();

	/**
	 * Retrieves a cached value.
	 *
	 * @param string $key The cache key root.
	 *
	 * @return mixed
	 */
	abstract public function get( $key );

	/**
	 * Sets an entry in the cache and stores the key.
	 *
	 * @param string $key       The cache key root.
	 * @param mixed  $value     The value to store.
	 * @param int    $duration  Optional. The duration in seconds. Default 0 (no expiration).
	 *
	 * @return bool True if the cache could be set successfully.
	 */
	abstract public function set( $key, $value, $duration = 0 );

	/**
	 * Deletes an entry from the cache.
	 *
	 * @param string $key The cache key root.
	 *
	 * @return bool True on successful removal, false on failure.
	 */
	abstract public function delete( $key );

	/**
	 * Retrieves the complete key to use.
	 *
	 * @param  string $key The cache key root.
	 *
	 * @return string
	 */
	protected function get_key( $key ) {
		return "{$this->prefix}{$this->incrementor}_{$key}";
	}

	/**
	 * Retrieves the set prefix.
	 *
	 * @return string
	 */
	protected function get_prefix() {
		return $this->prefix;
	}
}
