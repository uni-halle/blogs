<?php

/**
 * ======================================================================
 * LICENSE: This file is subject to the terms and conditions defined in *
 * file 'license.txt', which is part of this source code package.       *
 * ======================================================================
 */

/**
 * Backend view helper
 * 
 * @package AAM
 * @author Vasyl Martyniuk <vasyl@vasyltech.com>
 */
class AAM_Backend_View_Helper {

    /**
     * Prepare phrase or label
     * 
     * @param string $phrase
     * @param mixed  $...
     * 
     * @return string
     * 
     * @access protected
     */
    public static function preparePhrase($phrase) {
        //prepare search patterns
        $num = func_num_args();
        $search = array_fill(0, ($num - 1) * 2, null);
        array_walk($search, 'AAM_Backend_View_Helper::prepareWalk');

        $replace = array();
        foreach (array_slice(func_get_args(), 1) as $key) {
            array_push($replace, "<{$key}>", "</{$key}>");
        }

        //localize the phase first
        return preg_replace($search, $replace, __($phrase, AAM_KEY), 1);
    }
    
    /**
     * 
     * @param string $value
     * @param type $index
     */
    public static function prepareWalk(&$value, $index) {
        $value = '/\\' . ($index % 2 ? ']' : '[') . '/';
    }
    
    /**
     * Conver string to readable
     * 
     * @param string $text
     * 
     * @return string
     * 
     * @access public
     */
    public static function getHumanText($text) {
        return implode(' ', array_map('ucfirst', explode('_', $text)));
    }

}