<?php

function awpcp_user_profile_contact_information_controller() {
    return new AWPCP_UserProfileContactInformationController( awpcp_request() );
}

class AWPCP_UserProfileContactInformationController {

    private $request;

    public function __construct( $request ) {
        $this->request = $request;
    }

    public function show_contact_information_fields( $user ) {
        $profile = (array) get_user_meta( $user->ID, 'awpcp-profile', true );

        ob_start();
        include( AWPCP_DIR . '/templates/admin/profile/contact-information-fields.tpl.php' );
        $content = ob_get_contents();
        ob_end_clean();

        echo $content;
    }

    public function save_contact_information( $user_id ) {
        if ( ! current_user_can( 'edit_user', $user_id ) ) {
            return;
        }

        $profile = (array) get_user_meta( $user_id, 'awpcp-profile', true );
        $contact_information = $this->request->post( 'awpcp-profile' );

        $profile['phone'] = awpcp_array_data( 'phone', '', $contact_information );
        $profile['address'] = awpcp_array_data( 'address', '', $contact_information );
        $profile['email'] = $this->request->post( 'email' );
        $profile['website'] = $this->request->post( 'url' );

        $posted_regions = $this->request->post( 'regions', array() );
        $location = (array) array_shift( $posted_regions );

        $profile['country'] = awpcp_array_data( 'country', '', $location );
        $profile['state'] = awpcp_array_data( 'state', '', $location );
        $profile['city'] = awpcp_array_data( 'city', '', $location );
        $profile['county'] = awpcp_array_data( 'county', '', $location );

        update_user_meta( $user_id, 'awpcp-profile', $profile );

        do_action( 'awpcp-user-profile-updated', $profile, $user_id );
    }
}
