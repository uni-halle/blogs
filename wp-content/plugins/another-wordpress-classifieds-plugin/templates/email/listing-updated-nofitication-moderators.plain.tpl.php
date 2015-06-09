<?php
    $message = __( 'The classified listing "<listing-title>" was modified. A copy of the details sent to the customer can be found below. You can follow this link <manage-listing-link> to go to the Manage Ad Listing section to approve/reject/spam and see the full version of the Ad.', "AWPCP" );
    $message = str_replace( '<listing-title>', $listing->get_title(), $message );
    $message = str_replace( '<manage-listing-link>', $manage_listing_url, $message );

    echo $message; ?> 

<?php echo $content ?>
