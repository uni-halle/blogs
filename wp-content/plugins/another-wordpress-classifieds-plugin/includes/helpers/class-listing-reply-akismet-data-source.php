<?php

function awpcp_listing_reply_spam_filter() {
    return new AWPCP_SpamFilter( awpcp_akismet_wrapper_factory()->get_akismet_wrapper(), awpcp_listing_reply_akismet_data_source() );
}

function awpcp_listing_reply_akismet_data_source() {
    return new AWPCP_ListingReplyAkismetDataSource();
}

class AWPCP_ListingReplyAkismetDataSource {

    public function get_request_data( $subject ) {
        return array(
            'comment_type' => 'comment',
            'comment_author' => $subject['sender_name'],
            'comment_author_email' => $subject['sender_email'],
            'comment_content' => $subject['message'],
            'permalink' => url_showad( $subject['ad_id'] ),
        );
    }
}
