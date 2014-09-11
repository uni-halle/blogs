<?php
    $options = get_option('candy_options');
    $excludelist = '';
    if(count($options['candy_exclude']) > 1) {
        $excludelist = '&exclude=';
        $excludelist .= join(',',$options['candy_exclude']);
    }
    if(trim($options['menu_items']) == "")
        $options['menu_items'] = 'pages';
    if(trim($options['sidebarloc']) == "")
        $options['sidebarloc'] = 'right';
    if($options['metaenabled'] != true and $options['metaenabled'] != false) $options['metaenabled'] = false;
    if($options['excerptfront'] != true and $options['excerptfront'] != false) $options['excerptfront'] = false;
    if($options['excerptarchives'] != true and $options['excerptarchives'] != false) $options['excerptarchives'] = true;
    if($options['excerptsearch'] != true and $options['excerptarchives'] != false) $options['excerptsearch'] = true;
    if($options['templatetitleshow'] != true and $options['templatetitleshow'] != false) $options['templatetitleshow'] = true;
    if($options['showcattag'] != true and $options['showcattag'] != false) $options['showcattag'] = true;
    if($options['showaddtoany'] != true and $options['showaddtoany'] != false) $options['showaddtoany'] = true;
    if($options['txtsizechange'] != true and $options['txtsizechange'] != false) $options['txtsizechange'] = true;
?>