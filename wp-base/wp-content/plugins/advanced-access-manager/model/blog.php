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
 * Blog Model Class
 * 
 * @package AAM
 * @subpackage Models
 * @author Vasyl Martyniuk <martyniuk.vasyl@gmail.com>
 * @copyrights Copyright © 2011 Vasyl Martyniuk
 * @license GNU General Public License {@link http://www.gnu.org/licenses/}
 */
class mvb_Model_Blog {

	/**
	 * Blog ID
	 * 
	 * @var int
	 * @access proteced 
	 */
	protected $id;

	/**
	 * URL to Blog
	 * 
	 * @var string
	 * @access protected 
	 */
	protected $url;

	/**
	 * DB prefix
	 * 
	 * @var string
	 * @access protected
	 */
	protected $prefix;

	/**
	 *
	 * @param array $data 
	 */
	public function __construct($data = array()) {

		if (is_array($data)) {
			foreach ($data as $key => $value) {
				//TODO - Would be nice to implement __set & __get
				$this->$key = $value;
			}
		}
	}

	/**
	 * Get Blog ID
	 * 
	 * @return int 
	 */
	public function getID() {

		return $this->id;
	}

	/**
	 * Get Blog URL
	 * 
	 * @return string 
	 */
	public function getURL() {

		return $this->url;
	}

	/**
	 * Get DB Prefix
	 * 
	 * @return string
	 */
	public function getPrefix() {

		return $this->prefix;
	}

}

?>