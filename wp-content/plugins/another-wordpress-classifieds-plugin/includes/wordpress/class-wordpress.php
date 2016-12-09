<?php

function awpcp_wordpress() {
    return new AWPCP_WordPress();
}

class AWPCP_WordPress {

    public function schedule_single_event( $timestamp, $hook, $args ) {
        return wp_schedule_single_event( $timestamp, $hook, $args );
    }

    public function current_time( $time, $gmt = 0 ) {
        return current_time( $time, $gmt );
    }
}
