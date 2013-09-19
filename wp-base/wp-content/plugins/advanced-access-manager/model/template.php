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
 * Template Model Class
 *
 * @package AAM
 * @subpackage Models
 * @author Vasyl Martyniuk <martyniuk.vasyl@gmail.com>
 * @copyrights Copyright © 2011 Vasyl Martyniuk
 * @license GNU General Public License {@link http://www.gnu.org/licenses/}
 */
class mvb_Model_Template {

    /**
     * Read template from file
     *
     * Read file
     *
     * @param string Absolute path to file with template
     * @return mixed Return string on success and False if failed
     */
    public static function readTemplate($path) {

        if ($path) {
            $template = file_get_contents($path);
        }

        return ($template ? $template : FALSE);
    }

    /**
     * Get subpart from template
     *
     * Searching for specified subpart in template and return subpart's content
     * if found. Example of subpart -
     * <!-- ###HELLO_WORLD### begin-->Some content<!-- ###HELLO_WORLD### end-->
     *
     * @param string Subpart name
     * @param string Template to search in
     * @return string
     */
    public static function retrieveSub($subTemplate, $template) {
        $subPart = '';
        $regExp = '/<!\-\-[\s]?###' . $subTemplate . '###[\s]?begin\-\->';
        $regExp .= '(.*)<!\-\-[\s]?###' . $subTemplate . '###[\s]?end\-\->/si';

        if (preg_match($regExp, $template, $matches)) {
            $subPart = $matches[1];
        }

        return $subPart;
    }

    /**
     * Replace subpart with content
     *
     * Find proper subpart and replace it with content
     *
     * @param string Subpart name
     * @param string HTML content to replace with
     * @param string Template to search and replace in
     * @return string Template with replaced subpart
     */
    public static function replaceSub($subTemplate, $content, $template) {

        $regExp = '/<!\-\-[\s]?###' . $subTemplate . '###[\s]?begin\-\->';
        $regExp .= '.*<!\-\-[\s]?###' . $subTemplate . '###[\s]?end\-\->/si';

        return preg_replace($regExp, $content, $template);
    }

    /**
     * Update markers in template
     *
     * Take array with key => value pair and replace key with value
     * Example of a marker - ###hello_world###
     *
     * @param array key => value pair array
     * @param string HTML template
     * @return string HTML template with replaced markers
     */
    public static function updateMarkers($markers, $template) {

        if (is_array($markers)) {
            foreach ($markers as $marker => $content) {
                $template = str_replace($marker, $content, $template);
            }
        }

        return $template;
    }

    /**
     * Clear template for not updated markers
     *
     * @access public
     * @param string $template
     * @param string $pattern
     * @return string
     */
    public static function clearTemplate($template, $pattern = '/(###[a-z0-9_\-]+###)/si') {

        return preg_replace($pattern, '', $template);
    }

}

?>