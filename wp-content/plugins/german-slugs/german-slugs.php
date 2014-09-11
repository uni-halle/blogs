<?php

/*
Plugin Name: German Slugs
Plugin URI: http://wordpress.org/extend/plugins/german-slugs/
Description: German Slugs properly transliterates umlauts and the letter ß appearing in titles for slugs (i.e. for pretty permalinks).
Version: 0.2
Author: Kilian Evang
Author URI: http://texttheater.net
*/

/*******************************************************************************

    File: lexicographer.php
    Copyright (C) 2010 Kilian Evang

    This file is part of German Slugs.

    German Slugs is free software; you can redistribute it and/or modify
    it under the terms of the GNU General Public License as published by
    the Free Software Foundation; either version 2 of the License, or
    (at your option) any later version.

    Lexicographer is distributed in the hope that it will be useful,
    but WITHOUT ANY WARRANTY; without even the implied warranty of
    MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
    GNU General Public License for more details.

    You should have received a copy of the GNU General Public License
    along with Lexicographer; if not, write to the Free Software
    Foundation, Inc., 51 Franklin St, Fifth Floor, Boston, MA  02110-1301  USA

*******************************************************************************/

require('lib.php');
add_filter('sanitize_title', 'transliterate_aeoeuess', 5, 3);
