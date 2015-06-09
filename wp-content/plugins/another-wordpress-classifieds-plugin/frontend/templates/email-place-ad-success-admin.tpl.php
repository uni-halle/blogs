<?php $message = __("A new classifieds listing has been submitted. A copy of the details sent to the customer can be found below. You can follow this link %s to go to the Manage Ad Listing section to approve/reject/spam and see the full version of the Ad.", "AWPCP") ?>
<?php echo sprintf( $message, esc_url( $url ) ); ?> 

<?php echo $content ?>
