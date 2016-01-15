<?php _e('Your Ad has been successfully updated. Ad information is shown below.', 'another-wordpress-classifieds-plugin') ?> 

<?php if (!empty($message)): ?>
<?php echo $message ?> 
<?php endif ?>

<?php _e('Ad Information', 'another-wordpress-classifieds-plugin') ?> 

<?php _e("Listing Title", 'another-wordpress-classifieds-plugin') ?>: <?php echo $ad->get_title() ?> 
<?php _e("Listing URL", 'another-wordpress-classifieds-plugin') ?>: <?php echo urldecode( url_showad( $ad->ad_id ) ); ?> 
<?php _e("Listing ID", 'another-wordpress-classifieds-plugin') ?>: <?php echo $ad->ad_id ?> 
<?php _e("Listing Edit Email", 'another-wordpress-classifieds-plugin') ?>: <?php echo $ad->ad_contact_email ?> 
<?php if ( get_awpcp_option( 'include-ad-access-key' ) ): ?>
<?php _e( "Listing Edit Key", 'another-wordpress-classifieds-plugin' ); ?>: <?php echo $ad->get_access_key(); ?> 
<?php endif; ?>


<?php echo sprintf(__("If you have questions about your listing contact %s. Thank you for your business.", 'another-wordpress-classifieds-plugin'), $admin_email) ?> 

<?php echo awpcp_get_blog_name() ?> 
<?php echo home_url() ?> 
