<?php
function woothemes_add_admin() {

	 global $themename, $options;
	
	if ( $_GET['page'] == basename(__FILE__) ) {	
        if ( 'save' == $_REQUEST['action'] ) {
	
                foreach ($options as $value) {
					if($value['type'] != 'multicheck'){
                    	update_option( $value['id'], $_REQUEST[ $value['id'] ] ); 
					}else{
						foreach($value['options'] as $mc_key => $mc_value){
							$up_opt = $value['id'].'_'.$mc_key;
							update_option($up_opt, $_REQUEST[$up_opt] );
						}
					}
				}

                foreach ($options as $value) {
					if($value['type'] != 'multicheck'){
                    	if( isset( $_REQUEST[ $value['id'] ] ) ) { update_option( $value['id'], $_REQUEST[ $value['id'] ]  ); } else { delete_option( $value['id'] ); } 
					}else{
						foreach($value['options'] as $mc_key => $mc_value){
							$up_opt = $value['id'].'_'.$mc_key;						
							if( isset( $_REQUEST[ $up_opt ] ) ) { update_option( $up_opt, $_REQUEST[ $up_opt ]  ); } else { delete_option( $up_opt ); } 
						}
					}
				}
						
				header("Location: admin.php?page=functions.php&saved=true");								
			
			die;

		} else if ( 'reset' == $_REQUEST['action'] ) {
			delete_option('sandbox_logo');
			
			header("Location: admin.php?page=functions.php&reset=true");
			die;
		}

	}

add_menu_page($themename." Options", $themename." Options", 'edit_themes', basename(__FILE__), 'woothemes_page');

}
?>