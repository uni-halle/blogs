<?php

/*
 * @desc Classic Bookmarks Settings
 */

$shrsb_cb= shrsb_cb_set_options();

/*
 * @desc Set the classicbookmarks settings either from database or default
 */
function shrsb_cb_set_options( $action = NULL ) {
    
    $option_name = 'ShareaholicClassicBookmarks';
    
    $shrsb_cb_default = array(
        'cb'  => '0'
        , 'size' => '32'
        , 'pageorpost' => 'postpageindexcategory'
    );
    
    //Return default settings 
    if( $action == "reset" ) {
        delete_option($option_name);
        add_option($option_name,$shrsb_cb_default);
        return $shrsb_cb_default;
    }

    //Get the settings from the database
    $database_Settings =  get_option($option_name);


    if( $database_Settings ) {//got the settings in the database

        // Check only when upgrading
        if( SHRSB_UPGRADING == TRUE) {
            $need_to_update = false;

            //Check whether all the settings are present or not
            foreach( $shrsb_cb_default as $k => $v ){
                if( !array_key_exists( $k, $database_Settings )) {
                    $database_Settings[$k] = $v;
                    $need_to_update = true;
                }
            }
            
            if( $need_to_update ) update_option( $option_name, $database_Settings );

        }

        return $database_Settings;

    } else {
        //Add the settings to the database
        add_option( $option_name, $shrsb_cb_default );
        return $shrsb_cb_default;
    }
}
?>