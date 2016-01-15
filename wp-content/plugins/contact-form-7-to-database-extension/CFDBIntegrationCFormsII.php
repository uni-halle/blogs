<?php

/*
    "Contact Form to Database" Copyright (C) 2011-2016 Michael Simpson  (email : michael.d.simpson@gmail.com)

    This file is part of Contact Form to Database.

    Contact Form to Database is free software: you can redistribute it and/or modify
    it under the terms of the GNU General Public License as published by
    the Free Software Foundation, either version 3 of the License, or
    (at your option) any later version.

    Contact Form to Database is distributed in the hope that it will be useful,
    but WITHOUT ANY WARRANTY; without even the implied warranty of
    MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
    GNU General Public License for more details.

    You should have received a copy of the GNU General Public License
    along with Contact Form to Database.
    If not, see <http://www.gnu.org/licenses/>.
*/


class CFDBIntegrationCFormsII {

    /**
     * @var CF7DBPlugin
     */
    var $plugin;

    /**
     * @param $plugin CF7DBPlugin
     */
    function __construct($plugin) {
        $this->plugin = $plugin;
    }

    public function registerHooks() {
        if (!function_exists('my_cforms_action')) {
            function my_cforms_action($cformsdata) {

                ### Extract Data
                ### Note: $formID = '' (empty) for the first form!
                $formID = $cformsdata['id'];
                $data = $cformsdata['data'];

                $cforms_settings = get_option('cforms_settings');
                $form_name = $cforms_settings["form{$formID}"]["cforms{$formID}_fname"];

                /*
                $cformsdata['data']:Array
                (
                    [$$$1] => New Field
                    [New Field] => stuff
                    [$$$2] => Fieldset1
                    [Fieldset1] => My Fieldset
                    [$$$3] => Your Name
                    [Your Name] => Mike
                    [$$$4] => Email
                    [Email] => mike@example.com
                    [$$$5] => Website
                    [Website] => http://www.example.com
                    [$$$6] => Message
                    [Message] => hi there
                how are you?
                )
                */
                $form_data = array();
                foreach ($data as $key => $value) {
                    if (strpos($key, '$$$') !== 0) {
                        $form_data[$key] = $value;
                    }
                }

                $uploaded_files = array(); // todo: handle file uploads

                $cfdb_data = (object)array(
                        'title' => $form_name,
                        'posted_data' => $form_data,
                        'uploaded_files' => $uploaded_files);

                do_action_ref_array('cfdb_submit', array(&$cfdb_data));
            }
        }
    }

}
