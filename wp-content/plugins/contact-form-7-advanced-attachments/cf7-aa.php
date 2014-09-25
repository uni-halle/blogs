<?php

/**
 * Plugin Name: Contact Form 7 Extended File Attachments
 * Description: Add the possibility to use generic fields for file attachment in mails. Should be used with pipes
 * Version: 1.0
 * Author: Robert Jäckel
 * Author URI: mailto:robert.jaeckel@itz.uni-halle.de
 */

add_filter(@wpcf7_mail_components,function($comp){
    // read private and protected properties from object
    $getProp = function($object,$property) {
        $reflect = new ReflectionProperty(get_class($object), $property);
        $reflect->setAccessible(true);

        return $reflect->getValue($object);
    };
    // attachments in template
    $tplAttach = $getProp(WPCF7_Mail::get_current(),@template)[@attachments];
    // read real file tags, these will be skipped later
    $fileTags= array_map(function($item){
        // mapping creates the search strings
        return '['.$item[@name].']';
    },array_filter($getProp(WPCF7_ContactForm::get_current(),@scanned_form_tags),function($item){
        // match all file-tags
        return $item[@basetype]==@file;
    }));
    // remove the file-tags from template; remaining static files and non-file-tags
    $toAttach = str_replace($fileTags,'',$tplAttach);
    // search all remaining tags $attachMatch['names'] contains these without brackets
    preg_match_all('/\[(?P<name>[a-z0-9_\-]+)\]/i',$toAttach,$attachMatch);
    
    // merge form data and remaining tags into values
    $data = WPCF7_Submission::get_instance()->get_posted_data();
    $toAttach = array_intersect_key($data,array_flip($attachMatch[@name]));
    // for security only allow the files to be placed in wp_uploads of the current site
    $uploadDir = wp_upload_dir()[@basedir];
    // fetch current attachments by reference to extend
    $mailAttach = &$comp[@attachments];
    
    foreach($toAttach as $path) {
        $path[0]=='/' || $path=realpath(implode('/',[$uploadDir,$path]));
        
        if(strpos($uploadDir,$path)===0&&is_file($path)&&is_readable($path)) { // check for existence and readability
                $mailAttach[] = $path;
        }
    }
    //file_put_contents('/tmp/cf7-'.time(),print_r([$toAttach,$mailAttach,$comp],1));
    return $comp;
});
