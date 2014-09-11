<?php

/* 
 * @desc All Topbar functions and values which are used on every page load
*/

//Initialing the topbar settings array
$shrsb_tb_plugopts = shrsb_tb_set_options();

/*
 * @desc Set the settings either from database or default
 */
function shrsb_tb_set_options($action = NULL){
    
    $defaultLikeButtonOrder = array(
        'shr-fb-like',
        'shr-fb-send',
        'shr-plus-one',
        'shr-tw-button'
    );
    
    //Default Settigs array
    $shrsb_tb_plugopts_default = array(         
        'topbar' => '0',
        'useSbSettings' => '1',
        'tb_bg_color' => '#000000',
        'tb_border_color' => '#000000',//#343434'
        'addv' => '1',
        'pageorpost' => 'postpageindexcategory',
        'likeButtonSetTop' => '1', // Include like button below the Post Title
        'fbLikeButtonTop' => '1', // Include fb like button
        'fbSendButtonTop' => '1', // Include fb like button
        'googlePlusOneButtonTop' => '1', // Include Google Plus One button
        'tweetButtonTop' => '1', // Include Tweet button
        'likeButtonSetSizeTop' => "1", // Size of like buttons
        'likeButtonSetCountTop' => "true", // Show count with +1 button
        'likeButtonOrderTop' => $defaultLikeButtonOrder,
        'likeButtonSetAlignmentTop' => '0' // Alignment 0 => left, 1 => rights
    );
    
    //Return default settings 
    if($action == "reset"){
        delete_option("ShareaholicTopbar");
        add_option("ShareaholicTopbar",$shrsb_tb_plugopts_default);
        return $shrsb_tb_plugopts_default;
    }
    
    //Get the settings from the database
    $database_Settings =  get_option('ShareaholicTopbar');
    if($database_Settings){
        $need_to_update = false;
            
            //Check whether all the settings are present or not
            foreach($shrsb_tb_plugopts_default as $k => $v){
                if( !array_key_exists( $k, $database_Settings)) {
                    $database_Settings[$k] = $v;
                    $need_to_update = true;
                }
            }
            if($need_to_update) update_option("ShareaholicTopbar",$database_Settings);
        return $database_Settings;
    }else{
        //Add the settings
        add_option('ShareaholicTopbar',$shrsb_tb_plugopts_default);
        return $shrsb_tb_plugopts_default;
    }
}
?>