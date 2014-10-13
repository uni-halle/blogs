<?php


function awpcp_listing_spam_filter() {
    return new AWPCP_SpamFilter( awpcp_akismet_wrapper_factory()->get_akismet_wrapper(), awpcp_listing_akismet_data_source() );
}

function awpcp_listing_akismet_data_source() {
    return new AWPCP_ListingAkismetDataSource();
}


class AWPCP_ListingAkismetDataSource {

    public function get_request_data( $subject ) {
        $subject_data = array(
            'comment_type' => 'comment',
            'comment_author' => $subject['ad_contact_name'],
            'comment_author_email' => $subject['ad_contact_email'],
            'comment_author_url' => $subject['websiteurl'],
            'comment_content' => $subject['ad_details'],
        );

        if ( isset( $subject['ad_id'] ) ) {
            $subject_data['permalink'] = url_showad( intval( $subject['ad_id'] ) );
        }

        return $subject_data;
    }
}
