<?php echo sprintf(__('Hello %s,', 'AWPCP'), $ad->ad_contact_name); ?> 
 
<?php $message = __('Below you will find the access key for your Ad "%s" associated to the email address %s.', 'AWPCP'); ?>
<?php echo sprintf($message, $ad->get_title(), $ad->ad_contact_email); ?> 

<?php _e('Access Key', 'AWPCP'); ?>: <?php echo $ad->get_access_key(); ?> 
 
<?php echo awpcp_get_blog_name(); ?> 
<?php echo home_url(); ?> 
