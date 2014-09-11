<?php

/*******************************************************************************

    File: lib.php
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

function transliterate_aeoeuess($title, $raw_title = NULL, $context = 'query') {
    // Hacky hook due to hacky core, see
    // http://core.trac.wordpress.org/ticket/16905

    if ($raw_title != NULL) {
        $title = $raw_title; // undo remove_accents
    }

    $title = str_replace('Ä', 'ae', $title);
    $title = str_replace('ä', 'ae', $title);
    $title = str_replace('Ö', 'oe', $title);
    $title = str_replace('ö', 'oe', $title);
    $title = str_replace('Ü', 'ue', $title);
    $title = str_replace('ü', 'ue', $title);
    $title = str_replace('ẞ', 'ss', $title);
    $title = str_replace('ß', 'ss', $title);

    if ($context == 'save') {
        $title = remove_accents($title); // redo remove_accents
    }

    return $title;
}
