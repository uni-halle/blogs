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
 * Abstract class for Advanced Access Manager Filters
 * 
 * Is used to inforce AAM Filter Classes to specify necessary methods
 * 
 * @package AAM
 * @subpackage Abstract Models
 * @author Vasyl Martyniuk <martyniuk.vasyl@gmail.com>
 * @copyrights Copyright © 2011 Vasyl Martyniuk
 * @license GNU General Public License {@link http://www.gnu.org/licenses/}
 */

abstract class mvb_Model_Abstract_Filter{
	
	/**
	 * Class that called current
	 * 
	 * @access protected
	 * @var object
	 */
	protected $caller;
	
	/**
	 * Initiate Class
	 * 
	 * @access public
	 * @param object $caller 
	 */
	public function __construct($caller) {
		
		$this->caller = $caller;
	}
	
	/**
	 * Get Caller
	 * 
	 * @access public
	 * @return object
	 */
	public function getCaller(){
		
		return $this->caller;
	}

	/**
	 * Manage the filtering
	 * 
	 * @param string Specific Area
	 */
	abstract public function manage($area = '');
	
}
?>