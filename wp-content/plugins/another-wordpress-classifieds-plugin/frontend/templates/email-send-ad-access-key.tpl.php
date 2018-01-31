<?php echo sprintf(__('Hello %s,', 'another-wordpress-classifieds-plugin'), $ad->ad_contact_name); ?> 
 
<?php $message = __('Below you will find the access key for your Ad "%s" associated to the email address %s.', 'another-wordpress-classifieds-plugin'); ?>
<?php echo sprintf($message, $ad->get_title(), $ad->ad_contact_email); ?> 

<?php _e('Access Key', 'another-wordpress-classifieds-plugin'); ?>: <?php echo $ad->get_access_key(); ?> 
<?php _e( 'Edit Link:', 'another-wordpress-classifieds-plugin' ); ?> <?php echo $edit_link; ?> 

<?php echo esc_html_x( 'The edit link will expire after 24 hours. If you use the link after it has expired, a new one will be delivered to your email address automatically.', 'edit link email', 'another-wordpress-classifieds-plugin' ); ?> 

<?php echo awpcp_get_blog_name(); ?> 
<?php echo home_url(); ?> 
