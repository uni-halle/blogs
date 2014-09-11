<?php

function awpcp_facebook_cache_helper() {
    return new AWPCP_FacebookCacheHelper();
}

class AWPCP_FacebookCacheHelper {

    public function on_place_ad( $ad ) {
        $this->schedule_clear_cache_action( $ad );
    }

    private function schedule_clear_cache_action( $ad ) {
        wp_schedule_single_event( time() + 60, 'awpcp-clear-ad-facebook-cache', array( $ad->ad_id ) );
    }

    public function on_edit_ad( $ad ) {
        $this->clear_ad_cache( $ad );
    }

    public function on_approve_ad( $ad ) {
        $this->schedule_clear_cache_action( $ad );
    }

    public function handle_clear_cache_event_hook( $ad_id ) {
        $this->clear_ad_cache( AWPCP_Ad::find_by_id( $ad_id ) );
    }

    private function clear_ad_cache( $ad ) {
        if (is_null( $ad ) || $ad->disabled ) {
            return;
        }

        $args = array(
            'timeout' => 60,
            'body' => array(
                'id' => url_showad( $ad->ad_id ),
                'scrape' => true
            ),
        );

        $response = wp_remote_post( 'https://graph.facebook.com/', $args  );


        if ( isset( $response['response']['code'] ) && $response['response']['code'] == 200 ) {
            return;
        }

        $this->schedule_clear_cache_action( $ad );
    }
}
