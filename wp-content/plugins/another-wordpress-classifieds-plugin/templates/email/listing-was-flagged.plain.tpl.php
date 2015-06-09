<?php _e( 'Hello', 'AWPCP' ) ?>,

<?php
$text = __( 'You have pending flagged listings to review at <site-name>. Please check them out here <flagged-listings-url>.', 'AWPCP' );
$text = str_replace( '<site-name>', $site_name, $text );
$text = str_replace( '<flagged-listings-url>', $flagged_listings_url, $text );

echo $text;
?> 
