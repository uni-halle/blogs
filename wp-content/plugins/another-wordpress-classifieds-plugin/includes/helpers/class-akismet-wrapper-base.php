<?php

class AWPCP_AkismetWrapperBase {

    public function get_user_data() {
        return array();
    }

    public function http_post( $request, $path, $ip=null ) {
        return array( array(), '' );
    }
}
