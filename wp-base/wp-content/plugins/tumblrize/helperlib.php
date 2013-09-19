<?php
/**
 *
 * @file helperlib.php
 * @license GPL3 
 *
 *  Copyright 2009 by Meitar Moscovitz  (email : meitarm@gmail.com)
 *
 *  This program is free software; you can redistribute it and/or modify
 *  it under the terms of the GNU General Public License as published by
 *  the Free Software Foundation; either version 2 of the License, or
 *  (at your option) any later version.
 *
 *  This program is distributed in the hope that it will be useful,
 *  but WITHOUT ANY WARRANTY; without even the implied warranty of
 *  MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 *  GNU General Public License for more details.
 *
 *  You should have received a copy of the GNU General Public License
 *  along with this program; if not, write to the Free Software
 *  Foundation, Inc., 51 Franklin St, Fifth Floor, Boston, MA  02110-1301  USA
 *
 */

/**
 * Like strip_tags() but only strips the tags you specify.
 *
 * Copied from: http://www.php.net/manual/en/function.strip-tags.php#93567
 */
function strip_only ($str, $tags) {
    if (!is_array($tags)) {
        $tags = (strpos($str, '>') !== false ? explode('>', str_replace('<', '', $tags)) : array($tags));
        if (end($tags) == '') { array_pop($tags); }
    }

    foreach ($tags as $tag) {
        $str = preg_replace('#</?'.$tag.'[^>]*>#is', '', $str);
    }

    return $str;
}

/**
 * Compares two arbitrary version strings.
 *
 * @param mixed $ver The version to test. Can be a string or array created with parse_version().
 * @param string $op The comparison operation to perform.
 * @param mixed $num The version number to test the comparison again. Can also be a string or array.
 * @return mixed True or false if comparison is performed, otherwise returns an array of the passed-in version.
 *
 * @see parse_version(string $str, bool $named)
 */
function test_version ($ver, $op, $num) {
    $ver = (is_string($ver)) ? parse_version($ver) : $ver;
    $num = (is_string($num)) ? parse_version($num) : $num;

    // Be sure both arrays are the same size.
    if (count($ver) < count($num)) {
        $ver = array_pad($ver, count($num), 0);
    } else if (count($num) < count($ver)) {
        $num = array_pad($num, count($ver), 0);
    }

    // Based on $op, compare each element in turn.
    switch ($op) {
        case '>':
        case 'gt':
            for ($i = 0; $i < count($ver); $i++) {
                if ($ver[$i] > $num[$i]) {
                    return true;
                } else if ($ver[$i] === $num[$i]) {
                    continue;
                } else {
                    return false;
                }
            }
        case '<':
        case 'lt':
            for ($i = 0; $i < count($ver); $i++) {
                if ($ver[$i] < $num[$i]) {
                    return true;
                } else if ($ver[$i] === $num[$i]) {
                    continue;
                } else {
                    return false;
                }
            }
        case '=':
        case '==':
        case 'eq':
            for ($i = 0; $i < count($ver); $i++) {
                if ($ver[$i] === $num[$i]) {
                    continue;
                } else {
                    return false;
                }
            }
            // All are equal
            return true;
        case '>=':
        case 'gteq':
            for ($i = 0; $i < count($ver); $i++) {
                if ($ver[$i] >= $num[$i]) {
                    continue;
                } else {
                    return false;
                }
            }
            // All are greater than or equal to
            return true;
        case '<=':
        case 'lteq':
            for ($i = 0; $i < count($ver); $i++) {
                if ($ver[$i] <= $num[$i]) {
                    continue;
                } else {
                    return false;
                }
            }
            // All are less than or equal to
            return true;
        default:
            return $ver;
    }
}

/**
 * Parses a version number string into an array.
 *
 * @param string $str The version number to parse.
 * @param bool $named If true, adds extra elements to the array named "major," "minor," and "patch" for ease of use. Default is false.
 * @return array An object whose elements correspond to the dot-seperated version numbers.
 */
function parse_version ($str, $named = false) {
    if (!is_string($str)) { return array(); }
    $x = explode('.', $str);
    $r = array();
    for ($i = 0; $i < count($x); $i++) {
        if ($i === 0) {
            $r[0]       = (int)$x[0];
            if ($named) { $r['major'] = (int)$x[0]; }
        } else if ($i === 1) {
            $r[1]       = (int)$x[1];
            if ($named) { $r['minor'] = (int)$x[1]; }
        } else if ($i === 2) {
            $r[2]       = (int)$x[2];
            if ($named) { $r['patch'] = (int)$x[2]; }
        } else {
            $r[] = (int)$x[$i];
        }
    }
    return $r;
}
?>