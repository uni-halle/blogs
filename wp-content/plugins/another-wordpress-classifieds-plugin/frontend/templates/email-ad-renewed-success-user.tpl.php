<?php // emails are sent in plain text, blank lines in templates are required ?>
<?php echo $introduction ?>


<?php _e("Listing Title", "AWPCP") ?>: <?php echo $ad->ad_title ?>

<?php _e("Listing URL", "AWPCP") ?>: <?php echo urldecode( url_showad( $ad->ad_id ) ); ?>

<?php _e("Listing ID", "AWPCP") ?>: <?php echo $ad->ad_id ?>

<?php _e("Listing Edit Email", "AWPCP") ?>: <?php echo $ad->ad_contact_email ?>

<?php if ( get_awpcp_option( 'include-ad-access-key' ) ): ?>
<?php _e("Listing Edit Key", "AWPCP") ?>: <?php echo $ad->get_access_key() ?>
<?php endif; ?>

<?php _e("Listing End Date", "AWPCP") ?>: <?php echo $ad->get_end_date() ?>



<?php
    $text = __("If you have questions about your listing, please contact %s.", 'AWPCP');
    echo sprintf( $text, awpcp_admin_recipient_email_address() );
?>


<?php _e('Thank you for your business', 'AWPCP') ?>


<?php echo home_url() ?>
