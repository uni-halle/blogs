<?php _e('Your Ad has been successfully updated. Ad information is shown below.', 'AWPCP') ?> 

<?php if (!empty($message)): ?>
<?php echo $message ?> 
<?php endif ?>

<?php _e('Ad Information', 'AWPCP') ?> 

<?php _e("Listing Title", "AWPCP") ?>: <?php echo $ad->get_title() ?> 
<?php _e("Listing URL", "AWPCP") ?>: <?php echo urldecode( url_showad( $ad->ad_id ) ); ?> 
<?php _e("Listing ID", "AWPCP") ?>: <?php echo $ad->ad_id ?> 
<?php _e("Listing Edit Email", "AWPCP") ?>: <?php echo $ad->ad_contact_email ?> 
<?php if ( get_awpcp_option( 'include-ad-access-key' ) ): ?>
<?php _e( "Listing Edit Key", "AWPCP" ); ?>: <?php echo $ad->get_access_key(); ?> 
<?php endif; ?>


<?php echo sprintf(__("If you have questions about your listing contact %s. Thank you for your business.", 'AWPCP'), $admin_email) ?> 

<?php echo awpcp_get_blog_name() ?> 
<?php echo home_url() ?> 
